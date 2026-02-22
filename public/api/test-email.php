<?php
/**
 * test-email.php — 1st Class Chimney & Air Duct
 * Diagnostic script to verify SMTP2GO configuration.
 */

error_reporting(E_ALL);
ini_set('display_errors', 1);

header('Content-Type: text/plain');

$configPath = __DIR__ . '/config.php';
if (!file_exists($configPath)) {
    die("Error: config.php not found.");
}

$config = require $configPath;

$apiKey = $config['smtp2go']['api_key'] ?? '';
$sender = $config['smtp2go']['sender'] ?? '';
$recipients = array_filter($config['recipients'] ?? []);

echo "--- SMTP2GO Diagnostic ---\n";
echo "Config Loaded: Yes\n";
echo "API Key Found: " . ($apiKey ? "Yes (" . substr($apiKey, 0, 8) . "...)" : "No") . "\n";
echo "Sender: $sender\n";
echo "Recipients: " . implode(', ', $recipients) . "\n\n";

if (!$apiKey || empty($recipients)) {
    die("Error: Credentials not found. Please run setup.php first.\n");
}

echo "Attempting to send test email...\n";

$payload = [
    'api_key'    => $apiKey,
    'sender'     => $sender,
    'to'         => $recipients,
    'subject'    => "🚀 Test Email: 1st Class Chimney Integration",
    'html_body'  => "<h1>Integration Successful!</h1><p>This is a test email from your website at " . $_SERVER['HTTP_HOST'] . ".</p><p>Time: " . date('Y-m-d H:i:s') . "</p>",
];

$ch = curl_init('https://api.smtp2go.com/v3/email/send');
curl_setopt_array($ch, [
    CURLOPT_POST           => true,
    CURLOPT_POSTFIELDS     => json_encode($payload),
    CURLOPT_HTTPHEADER     => ['Content-Type: application/json'],
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_TIMEOUT        => 10,
]);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$curlError = curl_error($ch);
curl_close($ch);

if ($curlError) {
    echo "FAILED: Network/CURL error: " . $curlError . "\n";
} else {
    echo "HTTP Status: $httpCode\n";
    echo "API Response: $response\n\n";

    $decoded = json_decode($response, true);
    if ($httpCode === 200 && isset($decoded['data']['succeeded'])) {
        echo "✅ SUCCESS! Test email sent successfully to " . count($recipients) . " addresses.\n";
    } else {
        echo "❌ FAILED: SMTP2GO returned an error.\n";
    }
}
?>
