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

$firstName = sanitizeInput($data['firstName'] ?? '');
$lastName  = sanitizeInput($data['lastName'] ?? '');
$email     = filter_var(trim($data['email'] ?? ''), FILTER_SANITIZE_EMAIL);
$service   = sanitizeInput($data['service'] ?? '');
$pageUrl   = sanitizeInput($data['pageUrl'] ?? '');

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

if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = 'Valid email address is required';
}

$validServices = ['chimney-sweep', 'air-duct', 'dryer-vent', 'other'];
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
    'chimney-sweep' => 'Chimney Inspection & Sweep',
    'air-duct'      => 'Air Duct Cleaning',
    'dryer-vent'    => 'Dryer Vent Repair',
    'other'         => 'Other Inquiry',
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
    <div style="background:linear-gradient(135deg,#D4AF37,#F4D35E,#D4AF37);padding:20px;text-align:center;">
      <h1 style="margin:0;color:#121212;font-size:20px;font-weight:900;text-transform:uppercase;">New Lead — {$businessName}</h1>
    </div>
    <div style="padding:30px;">
      <table style="width:100%;border-collapse:collapse;">
        <tr style="border-bottom:1px solid #333;">
          <td style="padding:12px;color:#D4AF37;font-weight:bold;width:120px;font-size:12px;text-transform:uppercase;">Name</td>
          <td style="padding:12px;color:#E5E5E5;font-size:16px;">{$firstName} {$lastName}</td>
        </tr>
        <tr style="border-bottom:1px solid #333;">
          <td style="padding:12px;color:#D4AF37;font-weight:bold;font-size:12px;text-transform:uppercase;">Email</td>
          <td style="padding:12px;color:#E5E5E5;font-size:16px;"><a href="mailto:{$email}" style="color:#E5E5E5;">{$email}</a></td>
        </tr>
        <tr style="border-bottom:1px solid #333;">
          <td style="padding:12px;color:#D4AF37;font-weight:bold;font-size:12px;text-transform:uppercase;">Service</td>
          <td style="padding:12px;color:#E5E5E5;font-size:16px;">{$serviceLabel}</td>
        </tr>
        <tr style="border-bottom:1px solid #333;">
          <td style="padding:12px;color:#D4AF37;font-weight:bold;font-size:12px;text-transform:uppercase;">Submitted</td>
          <td style="padding:12px;color:#E5E5E5;font-size:14px;">{$timestamp}</td>
        </tr>
        <tr>
          <td style="padding:12px;color:#D4AF37;font-weight:bold;font-size:12px;text-transform:uppercase;">UTM Source</td>
          <td style="padding:12px;color:#A3A3A3;font-size:14px;">{$utmString}</td>
        </tr>
      </table>
    </div>
    <div style="padding:20px;text-align:center;border-top:1px solid #333;">
      <p style="color:#A3A3A3;font-size:12px;margin:0;">Reply to the customer's email above to respond directly.</p>
    </div>
  </div>
</body>
</html>
HTML;

// ── Build Autoresponder Email (FR15, FR18) ───────────────────────────────
$autoresponderHtml = <<<HTML
<!DOCTYPE html>
<html>
<head><meta charset="UTF-8"></head>
<body style="margin:0;padding:0;background:#121212;font-family:'Helvetica Neue',Arial,sans-serif;">
  <div style="max-width:600px;margin:0 auto;background:#1A1A1A;border:1px solid #333;">
    <div style="background:linear-gradient(135deg,#D4AF37,#F4D35E,#D4AF37);padding:24px;text-align:center;">
      <h1 style="margin:0;color:#121212;font-size:22px;font-weight:900;text-transform:uppercase;letter-spacing:1px;">{$businessName}</h1>
      <p style="margin:4px 0 0;color:#121212;font-size:12px;font-style:italic;opacity:0.8;">Elite Level Protection for Your Home</p>
    </div>
    <div style="padding:30px;">
      <h2 style="color:#FFFFFF;font-size:20px;margin:0 0 16px;">Thank You, {$firstName}!</h2>
      <p style="color:#E5E5E5;font-size:16px;line-height:1.6;margin:0 0 20px;">
        We've received your request for <strong style="color:#D4AF37;">{$serviceLabel}</strong> and a team member will contact you within <strong style="color:#D4AF37;">2 hours</strong> to schedule your appointment.
      </p>
      <div style="background:#2D2D2D;border-radius:8px;padding:20px;margin:20px 0;">
        <h3 style="color:#D4AF37;font-size:14px;text-transform:uppercase;letter-spacing:2px;margin:0 0 16px;">What Happens Next</h3>
        <div style="color:#E5E5E5;font-size:14px;line-height:2;">
          ✅ Your request has been received<br>
          📞 A team member will call to confirm scheduling<br>
          🏠 Our certified technician arrives at your home<br>
          📋 You receive a detailed inspection report
        </div>
      </div>
      <div style="text-align:center;margin:24px 0;">
        <a href="tel:5551234567" style="display:inline-block;background:linear-gradient(135deg,#D4AF37,#F4D35E,#D4AF37);color:#121212;font-weight:bold;text-transform:uppercase;letter-spacing:1px;padding:14px 32px;text-decoration:none;border-radius:8px;font-size:14px;">
          Can't Wait? Call Us Now
        </a>
      </div>
      <div style="border-top:1px solid #333;padding-top:20px;margin-top:20px;">
        <p style="color:#A3A3A3;font-size:13px;line-height:1.6;margin:0;">
          <strong style="color:#D4AF37;">Our Credentials:</strong> CSIA Certified • NFI Certified • Fully Insured • Background Checked
        </p>
      </div>
    </div>
    <div style="padding:16px;text-align:center;background:#0B132B;">
      <p style="color:#A3A3A3;font-size:11px;margin:0;">© {$businessName} | {$businessPhone}</p>
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
    // Dev mode: no API key configured, return success anyway
    echo json_encode(['success' => true, 'message' => 'Request received (dev mode — no emails sent)']);
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
