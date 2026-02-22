<?php
/**
 * setup.php — 1st Class Chimney & Air Duct
 * Secure web-based credential installer.
 * Allows setting up env.php without FTP access.
 */

error_reporting(E_ALL);
ini_set('display_errors', 1);

// 1. Security Check: Don't allow re-running if env.php exists
$envFile = __DIR__ . '/env.php';
if (file_exists($envFile)) {
    die("<h1>Security Notice</h1><p>Credentials are already configured. For security, please delete this file (setup.php) via FTP or File Manager.</p>");
}

$message = '';
$messageClass = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $apiKey    = trim($_POST['api_key'] ?? '');
    $sender    = trim($_POST['sender'] ?? 'info@1stclasschimney.com');
    $email1    = trim($_POST['email1'] ?? '');
    $email2    = trim($_POST['email2'] ?? '');

    if (empty($apiKey) || empty($email1)) {
        $message = "Error: API Key and at least one recipient email are required.";
        $messageClass = "error";
    } else {
        $content = "<?php\n";
        $content .= "/**\n";
        $content .= " * env.php — Generated electronically on " . date('Y-m-d H:i:s') . "\n";
        $content .= " */\n\n";
        $content .= "define('SMTP2GO_API_KEY', '" . addslashes($apiKey) . "');\n";
        $content .= "define('SMTP2GO_SENDER', '" . addslashes($sender) . "');\n\n";
        $content .= "define('NOTIFICATION_EMAIL_1', '" . addslashes($email1) . "');\n";
        $content .= "define('NOTIFICATION_EMAIL_2', '" . addslashes($email2) . "');\n";
        $content .= "define('NOTIFICATION_EMAIL_3', '');\n";

        if (file_put_contents($envFile, $content)) {
            $message = "Success! Credentials saved. You can now test your forms.";
            $messageClass = "success";
        } else {
            $message = "Error: Unable to write to $envFile. Check folder permissions.";
            $messageClass = "error";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Secure Setup | 1st Class Chimney</title>
    <style>
        :root {
            --gold: #D4AF37;
            --gold-bright: #F4D35E;
            --dark: #0A0A0A;
            --surface: #1A1A1A;
            --text: #E5E5E5;
        }
        body {
            background-color: var(--dark);
            color: var(--text);
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            margin: 0;
        }
        .container {
            background-color: var(--surface);
            padding: 40px;
            border-radius: 12px;
            border: 1px solid #333;
            width: 100%;
            max-width: 450px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.5);
        }
        h1 {
            color: var(--gold);
            margin: 0 0 10px;
            font-size: 24px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        p { margin-bottom: 30px; color: #A3A3A3; font-size: 14px; }
        .form-group { margin-bottom: 20px; }
        label { display: block; margin-bottom: 8px; font-weight: bold; color: var(--gold); font-size: 12px; text-transform: uppercase; }
        input {
            width: 100%;
            padding: 12px;
            border: 1px solid #333;
            background: #121212;
            color: white;
            border-radius: 6px;
            box-sizing: border-box;
        }
        .btn {
            width: 100%;
            padding: 14px;
            background: linear-gradient(135deg, var(--gold), var(--gold-bright), var(--gold));
            border: none;
            color: #121212;
            font-weight: bold;
            border-radius: 6px;
            cursor: pointer;
            text-transform: uppercase;
            margin-top: 10px;
        }
        .message {
            padding: 15px;
            border-radius: 6px;
            margin-bottom: 20px;
            font-size: 14px;
            text-align: center;
        }
        .error { background: #441111; color: #FF9999; border: 1px solid #662222; }
        .success { background: #114411; color: #99FF99; border: 1px solid #226622; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Secure Setup</h1>
        <p>Initialize your site's credentials securely.</p>

        <?php if ($message): ?>
            <div class="message <?php echo $messageClass; ?>"><?php echo $message; ?></div>
            <?php if ($messageClass === 'success'): ?>
                <p style="text-align:center;"><a href="/" style="color:var(--gold);">Back to Home</a></p>
                <?php exit; ?>
            <?php endif; ?>
        <?php endif; ?>

        <form method="POST">
            <div class="form-group">
                <label>SMTP2GO API Key</label>
                <input type="password" name="api_key" placeholder="api-XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX" required>
            </div>
            <div class="form-group">
                <label>Notification Email 1</label>
                <input type="email" name="email1" placeholder="yaron@..." required>
            </div>
            <div class="form-group">
                <label>Notification Email 2</label>
                <input type="email" name="email2" placeholder="office@...">
            </div>
            <button type="submit" class="btn">Save & Activate</button>
        </form>
    </div>
</body>
</html>
