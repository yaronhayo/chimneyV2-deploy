# Hostinger Deployment Guide

This guide ensures your site is safely and correctly deployed to Hostinger using Git.

## 1. Prepare Environment Files (CRITICAL)

To protect your API keys, the application is configured to look for `env.php` **one level above** your web root.

1.  Log in to your Hostinger File Manager.
2.  Navigate to `/home/uXXXXXX/` (your user home, one level above `public_html`).
3.  Create a file named `env.php`.
4.  Paste the following content (using your real credentials):

```php
<?php
define('SMTP2GO_API_KEY', 'api-C86A462F0DFC4A28A0A06490EB81161C');
define('SMTP2GO_SENDER', 'noreply@1stclasschimneyandairduct.com');

define('NOTIFICATION_EMAIL_1', 'yaron@gettmarketing.com');
define('NOTIFICATION_EMAIL_2', 'sandrahmarketing@gmail.com');
define('NOTIFICATION_EMAIL_3', 'backup@example.com');
```

> [!IMPORTANT]
> By placing `env.php` here, it is NOT accessible via a web browser, but the PHP backend can still load it.

## 2. Trigger Git Deployment

1.  Go to the **Hostinger Dashboard** -> **Website** -> **Git**.
2.  Connect your repository: `https://github.com/yaronhayo/chimneyV2-deploy.git`.
3.  Set the Branch to `main`.
4.  Set the Install Directory to `public_html`.
5.  Click **Deploy**.

## 3. Verify Deployment

1.  Visit your website.
2.  Open the **Contact Form**.
3.  Submit a test lead.
4.  Check your email (and the recipient emails) to confirm SMTP2GO is firing correctly.

## 4. Troubleshooting

- **Form Errors**: Ensure the `api/rate_limits` folder is writable by the web server (usually chmod 755).
- **No Emails**: Check that `env.php` is accurately placed and contains the correct API key.
