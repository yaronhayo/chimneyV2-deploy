<?php
/**
 * config.php — 1st Class Chimney & Air Duct
 * Loads environment configuration from env.php (outside web root on production).
 * On Hostinger, env.php sits in /home/user/env.php (above public_html).
 */

// Attempt to load env.php from common locations
$envPaths = [
    dirname($_SERVER['DOCUMENT_ROOT']) . '/env.php', // Above web root (Hostinger production)
    dirname(__DIR__) . '/env.php',                 // Project root
    __DIR__ . '/env.php',                          // Local dev fallback
];

$envLoaded = false;
foreach ($envPaths as $path) {
    if (file_exists($path)) {
        require_once $path;
        $envLoaded = true;
        break;
    }
}

// Configuration with defaults for development
return [
    'smtp2go' => [
        'api_key'    => defined('SMTP2GO_API_KEY') ? SMTP2GO_API_KEY : '',
        'sender'     => defined('SMTP2GO_SENDER') ? SMTP2GO_SENDER : 'noreply@1stclasschimney.com',
    ],
    'recipients' => [
        defined('NOTIFICATION_EMAIL_1') ? NOTIFICATION_EMAIL_1 : '',
        defined('NOTIFICATION_EMAIL_2') ? NOTIFICATION_EMAIL_2 : '',
        defined('NOTIFICATION_EMAIL_3') ? NOTIFICATION_EMAIL_3 : '',
    ],
    'business' => [
        'name'  => '1st Class Chimney & Air Duct',
        'phone' => '(888) 696-9296',
        'email' => 'info@1stclasschimney.com',
    ],
    'rate_limit' => [
        'max_per_hour' => 60,
        'storage_dir'  => sys_get_temp_dir() . '/chimney_rate_limits/',
    ],
];
