<?php
/**
 * env.php — 1st Class Chimney & Air Duct
 * Production credentials — DO NOT commit to git.
 * On Hostinger: /home/username/env.php (above public_html)
 * For local dev: api/env.php
 */

// SMTP2GO
define('SMTP2GO_API_KEY', 'api-C86A462F0DFC4A28A0A06490EB81161C');
define('SMTP2GO_SENDER', 'noreply@1stclasschimneyandairduct.com');

// Lead notification recipients
define('NOTIFICATION_EMAIL_1', 'yaron@gettmarketing.com');
define('NOTIFICATION_EMAIL_2', 'sandrahmarketing@gmail.com');

// Google reCAPTCHA v3 (server-side secret key)
// Get from: https://www.google.com/recaptcha/admin
define('RECAPTCHA_SECRET_KEY', '6Le4j3YsAAAAAGZh4zVCqvmKt_W7B2lJD7qMT6YG');
