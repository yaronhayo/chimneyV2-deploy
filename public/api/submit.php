<?php
/**
 * submit.php — 1st Class Chimney & Air Duct
 * Lead form submission handler.
 *
 * Anti-spam: honeypot, time-to-submit, rate limiting, input sanitization.
 * Notification: SMTP2GO API for lead alerts + autoresponder.
 * Security: No PII stored on server. All data pass-through to SMTP2GO.
 *
 * FR9, FR14-18, FR43-48, NFR6, NFR8-11, NFR13
 */

header('Content-Type: application/json');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

// Handle preflight
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204);
    exit;
}

// Only accept POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'error' => 'Method not allowed']);
    exit;
}

// ── Load Configuration ───────────────────────────────────────────────────
$config = require __DIR__ . '/config.php';

// ── Parse Request Body ───────────────────────────────────────────────────
$rawInput = file_get_contents('php://input');
$data = json_decode($rawInput, true);

if (!$data || !is_array($data)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'Invalid request body']);
    exit;
}

// ── Anti-Spam: Honeypot (FR43) ───────────────────────────────────────────
if (!empty($data['honeypot'])) {
    // Silently accept but don't process — bot filled the hidden field
    echo json_encode(['success' => true, 'message' => 'Request received']);
    exit;
}

// ── Anti-Spam: Time-to-Submit (FR44) ─────────────────────────────────────
$timeToSubmit = isset($data['timeToSubmit']) ? (int)$data['timeToSubmit'] : 0;
if ($timeToSubmit > 0 && $timeToSubmit < 3000) {
    // Less than 3 seconds — likely a bot
    echo json_encode(['success' => true, 'message' => 'Request received']);
    exit;
}

// ── Anti-Spam: Rate Limiting (FR47) ──────────────────────────────────────
$rateLimitDir = $config['rate_limit']['storage_dir'];
$maxPerHour = $config['rate_limit']['max_per_hour'];
$clientIP = $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
$rateLimitFile = $rateLimitDir . hash('sha256', $clientIP) . '.json';

if (!is_dir($rateLimitDir)) {
    mkdir($rateLimitDir, 0700, true);
}

$rateLimited = false;
if (file_exists($rateLimitFile)) {
    $fh = fopen($rateLimitFile, 'r+');
    if ($fh && flock($fh, LOCK_EX)) {
        $contents = fread($fh, filesize($rateLimitFile) ?: 1);
        $timestamps = json_decode($contents, true) ?: [];

        // Filter to last hour
        $oneHourAgo = time() - 3600;
        $timestamps = array_filter($timestamps, function ($ts) use ($oneHourAgo) {
            return $ts > $oneHourAgo;
        });

        if (count($timestamps) >= $maxPerHour) {
            $rateLimited = true;
        } else {
            $timestamps[] = time();
        }

        // Write back
        ftruncate($fh, 0);
        rewind($fh);
        fwrite($fh, json_encode(array_values($timestamps)));
        flock($fh, LOCK_UN);
        fclose($fh);
    }
} else {
    file_put_contents($rateLimitFile, json_encode([time()]), LOCK_EX);
}

if ($rateLimited) {
    http_response_code(429);
    echo json_encode([
        'success' => false,
        'error' => 'Too many requests. Please try again later or call us directly.'
    ]);
    exit;
}

// ── Input Sanitization (FR46, NFR9) ──────────────────────────────────────
function sanitizeInput(string $input): string {
    $input = trim($input);
    $input = strip_tags($input);
    $input = htmlspecialchars($input, ENT_QUOTES | ENT_HTML5, 'UTF-8');
    // Limit length
    return mb_substr($input, 0, 255);
}

$firstName  = sanitizeInput($data['firstName'] ?? '');
$lastName   = sanitizeInput($data['lastName'] ?? '');
$phone      = sanitizeInput($data['phone'] ?? '');
$email      = filter_var(trim($data['email'] ?? ''), FILTER_SANITIZE_EMAIL);
$service    = sanitizeInput($data['service'] ?? '');
$pageUrl    = sanitizeInput($data['pageUrl'] ?? '');

// UTM data
$utm = [];
if (isset($data['utm']) && is_array($data['utm'])) {
    foreach ($data['utm'] as $key => $value) {
        if (is_string($key) && is_string($value)) {
            $utm[sanitizeInput($key)] = sanitizeInput($value);
        }
    }
}

// ── Validation ───────────────────────────────────────────────────────────
$errors = [];

if (empty($firstName)) {
    $errors[] = 'First name is required';
}

if (empty($lastName)) {
    $errors[] = 'Last name is required';
}

if (empty($phone)) {
    $errors[] = 'Phone number is required';
}

// Email is optional in UI (FR14-18), validate only if provided
if (!empty($email) && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = 'Please provide a valid email address';
}

$validServices = [
    'chimney-sweep',
    'chimney-inspection',
    'chimney-repair',
    'caps-liners',
    'fireplace-services',
    'dryer-vent',
    'air-duct',
    'other'
];
if (empty($service) || !in_array($service, $validServices)) {
    $errors[] = 'Please select a valid service';
}

if (!empty($errors)) {
    http_response_code(422);
    echo json_encode(['success' => false, 'error' => implode(', ', $errors)]);
    exit;
}

// ── Service Label Map ────────────────────────────────────────────────────
$serviceLabels = [
    'chimney-sweep'      => 'Chimney Sweep & Cleaning',
    'chimney-inspection' => 'Chimney Inspection',
    'chimney-repair'     => 'Chimney Repair',
    'caps-liners'        => 'Caps, Liners & Flues',
    'fireplace-services' => 'Fireplace Services',
    'dryer-vent'         => 'Dryer Vent Cleaning',
    'air-duct'           => 'Air Duct Cleaning',
    'other'              => 'Other Inquiry',
];
$serviceLabel = $serviceLabels[$service] ?? $service;

// ── Build Notification Email (FR14, FR16, FR17) ──────────────────────────
$timestamp = date('F j, Y g:i A T');
$utmString = !empty($utm) ? implode(', ', array_map(
    function ($k, $v) { return "$k=$v"; },
    array_keys($utm),
    $utm
)) : 'Direct / None';

$businessName = $config['business']['name'];
$businessPhone = $config['business']['phone'];

$notificationHtml = <<<HTML
<!DOCTYPE html>
<html>
<head><meta charset="UTF-8"></head>
<body style="margin:0;padding:0;background:#121212;font-family:'Helvetica Neue',Arial,sans-serif;">
  <div style="max-width:600px;margin:0 auto;background:#1A1A1A;border:1px solid #333;">
    <div style="background:linear-gradient(135deg,#FFC107,#FFD54F,#FFC107);padding:20px;text-align:center;">
      <h1 style="margin:0;color:#121212;font-size:20px;font-weight:900;text-transform:uppercase;">New Lead — {$businessName}</h1>
    </div>
    <div style="padding:30px;">
      <table style="width:100%;border-collapse:collapse;">
        <tr style="border-bottom:1px solid #333;">
          <td style="padding:12px;color:#FFC107;font-weight:bold;width:120px;font-size:12px;text-transform:uppercase;">Name</td>
          <td style="padding:12px;color:#E5E5E5;font-size:16px;">{$firstName} {$lastName}</td>
        </tr>
        <tr style="border-bottom:1px solid #333;">
          <td style="padding:12px;color:#FFC107;font-weight:bold;font-size:12px;text-transform:uppercase;">Phone</td>
          <td style="padding:12px;color:#E5E5E5;font-size:18px;font-weight:bold;"><a href="tel:{$phone}" style="color:#FFC107;text-decoration:none;">{$phone}</a></td>
        </tr>
        <tr style="border-bottom:1px solid #333;">
          <td style="padding:12px;color:#FFC107;font-weight:bold;font-size:12px;text-transform:uppercase;">Email</td>
          <td style="padding:12px;color:#E5E5E5;font-size:16px;"><a href="mailto:{$email}" style="color:#E5E5E5;">{$email}</a></td>
        </tr>
        <tr style="border-bottom:1px solid #333;">
          <td style="padding:12px;color:#FFC107;font-weight:bold;font-size:12px;text-transform:uppercase;">Service</td>
          <td style="padding:12px;color:#E5E5E5;font-size:16px;">{$serviceLabel}</td>
        </tr>
        <tr style="border-bottom:1px solid #333;">
          <td style="padding:12px;color:#FFC107;font-weight:bold;font-size:12px;text-transform:uppercase;">Submitted</td>
          <td style="padding:12px;color:#E5E5E5;font-size:14px;">{$timestamp}</td>
        </tr>
        <tr style="border-bottom:1px solid #333;">
          <td style="padding:12px;color:#FFC107;font-weight:bold;font-size:12px;text-transform:uppercase;">Page URL</td>
          <td style="padding:12px;color:#A3A3A3;font-size:12px;">{$pageUrl}</td>
        </tr>
        <tr>
          <td style="padding:12px;color:#FFC107;font-weight:bold;font-size:12px;text-transform:uppercase;">UTM Data</td>
          <td style="padding:12px;color:#A3A3A3;font-size:12px;">{$utmString}</td>
        </tr>
      </table>
    </div>
    <div style="padding:24px;text-align:center;background:#0B132B;border-top:1px solid #333;">
      <p style="color:#FFC107;font-size:14px;margin:0;font-weight:bold;">Reply directly to the customer or click the phone number to call.</p>
    </div>
  </div>
</body>
</html>
HTML;

// ── Build Autoresponder Email (FR15, FR18) ───────────────────────────────
$logoUrl = "https://1stclasschimneyandairduct.com/images/brand/logo.png";

$autoresponderHtml = <<<HTML
<!DOCTYPE html>
<html>
<head><meta charset="UTF-8"></head>
<body style="margin:0;padding:0;background:#121212;font-family:'Helvetica Neue',Arial,sans-serif;">
  <div style="max-width:600px;margin:0 auto;background:#1A1A1A;border:1px solid #333;">
    <!-- Logo Header -->
    <div style="padding:40px 20px;text-align:center;background:#0B132B;">
      <img src="{$logoUrl}" alt="{$businessName}" style="max-width:200px;height:auto;display:inline-block;">
    </div>

    <div style="background:linear-gradient(135deg,#FFC107,#FFD54F,#FFC107);padding:2px;"></div>

    <div style="padding:40px 30px;">
      <h2 style="color:#FFFFFF;font-size:24px;margin:0 0 16px;font-weight:900;text-transform:uppercase;letter-spacing:1px;">Thank You, {$firstName}!</h2>
      <p style="color:#E5E5E5;font-size:16px;line-height:1.6;margin:0 0 24px;">
        We've received your request for <strong style="color:#FFC107;">{$serviceLabel}</strong>. At {$businessName}, we take your home's safety seriously.
      </p>

      <div style="background:rgba(255,193,7,0.05);border:1px solid rgba(255,193,7,0.2);border-radius:12px;padding:24px;margin:24px 0;">
        <h3 style="color:#FFC107;font-size:14px;text-transform:uppercase;letter-spacing:2px;margin:0 0 16px;font-weight:bold;">What Happens Next</h3>
        <table style="width:100%;border-collapse:separate;border-spacing:0 12px;">
          <tr>
            <td style="width:24px;vertical-align:top;color:#FFC107;font-size:16px;">✓</td>
            <td style="color:#E5E5E5;font-size:14px;padding-left:12px;"><strong>Instant Confirmation:</strong> Your request is in our system.</td>
          </tr>
          <tr>
            <td style="width:24px;vertical-align:top;color:#FFC107;font-size:16px;">📞</td>
            <td style="color:#E5E5E5;font-size:14px;padding-left:12px;"><strong>Expert Callback:</strong> A team member will call you within <strong style="color:#FFC107;">2 hours</strong> to confirm scheduling.</td>
          </tr>
          <tr>
            <td style="width:24px;vertical-align:top;color:#FFC107;font-size:16px;">🏠</td>
            <td style="color:#E5E5E5;font-size:14px;padding-left:12px;"><strong>On-Site Assessment:</strong> A CSIA certified technician arrives in uniform to protect your home.</td>
          </tr>
        </table>
      </div>

      <div style="text-align:center;margin:32px 0;">
        <p style="color:#A3A3A3;font-size:14px;margin-bottom:16px;">Average response time is currently under 30 minutes.</p>
        <a href="tel:{$businessPhone}" style="display:inline-block;background:linear-gradient(135deg,#FFC107,#FFD54F,#FFC107);color:#121212;font-weight:900;text-transform:uppercase;letter-spacing:1px;padding:18px 36px;text-decoration:none;border-radius:8px;font-size:15px;box-shadow:0 4px 15px rgba(255,193,7,0.3);">
          Call Us Directly: {$businessPhone}
        </a>
      </div>

      <div style="border-top:1px solid #333;padding-top:24px;margin-top:24px;text-align:center;">
        <p style="color:#A3A3A3;font-size:12px;line-height:1.6;margin:0;">
          <strong style="color:#E5E5E5;">The 1st Class Standard:</strong><br>
          CSIA & NFI Certified Professionals • Fully Insured • Background Checked • Elite Care
        </p>
      </div>
    </div>

    <div style="padding:24px;text-align:center;background:#0B132B;border-top:1px solid #333;">
      <p style="color:#666;font-size:11px;margin:0;">
        © 2026 {$businessName}. All rights reserved.<br>
        Providing elite chimney and air duct services.
      </p>
    </div>
  </div>
</body>
</html>
HTML;

// ── Send via SMTP2GO API ─────────────────────────────────────────────────
function sendViaSMTP2GO(string $apiKey, string $sender, array $recipients, string $subject, string $htmlBody, string $replyTo = ''): array {
    $payload = [
        'api_key'    => $apiKey,
        'sender'     => $sender,
        'to'         => $recipients,
        'subject'    => $subject,
        'html_body'  => $htmlBody,
    ];

    if ($replyTo) {
        $payload['custom_headers'] = [
            ['header' => 'Reply-To', 'value' => $replyTo],
        ];
    }

    $ch = curl_init('https://api.smtp2go.com/v3/email/send');
    curl_setopt_array($ch, [
        CURLOPT_POST           => true,
        CURLOPT_POSTFIELDS     => json_encode($payload),
        CURLOPT_HTTPHEADER     => ['Content-Type: application/json'],
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT        => 10,
        CURLOPT_CONNECTTIMEOUT => 5,
    ]);

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $curlError = curl_error($ch);
    curl_close($ch);

    if ($curlError) {
        return ['success' => false, 'error' => 'Network error: ' . $curlError];
    }

    $decoded = json_decode($response, true);
    if ($httpCode >= 200 && $httpCode < 300 && isset($decoded['data']['succeeded'])) {
        return ['success' => true];
    }

    return ['success' => false, 'error' => $decoded['data']['error'] ?? 'SMTP2GO API error'];
}

// ── Send Notification Email ──────────────────────────────────────────────
$apiKey = $config['smtp2go']['api_key'];
$sender = $config['smtp2go']['sender'];
$recipients = array_filter($config['recipients']); // Remove empties

if (empty($apiKey) || empty($recipients)) {
    // Dev mode: no API key configured
    // On production, this means env.php is missing or empty.
    echo json_encode([
        'success' => true,
        'message' => 'Dev Mode: env.php not found or credentials empty. No email sent.'
    ]);
    exit;
}

$notifSubject = "🔔 New Lead: {$firstName} {$lastName} — {$serviceLabel}";
$notifResult = sendViaSMTP2GO($apiKey, $sender, $recipients, $notifSubject, $notificationHtml, $email);

// ── Send Autoresponder ───────────────────────────────────────────────────
$autoSubject = "Thank You, {$firstName} — Your {$serviceLabel} Request is Confirmed";
sendViaSMTP2GO($apiKey, $sender, [$email], $autoSubject, $autoresponderHtml);

// ── Response ─────────────────────────────────────────────────────────────
if ($notifResult['success']) {
    echo json_encode(['success' => true, 'message' => 'Request received. We will contact you shortly.']);
} else {
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => 'Unable to process your request. Please call us directly.']);
}
