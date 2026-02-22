# Deployment Guide — 1st Class Chimney & Air Duct

## Build

```bash
npm run build
```

Output goes to `dist/`. Build should complete in <500ms with 0 errors.

## Deploy to Hostinger

### Static Files

Upload `dist/*` contents to `public_html/`:

```
public_html/
├── index.html
├── favicon.svg
├── robots.txt
├── sitemap-index.xml
├── sitemap-0.xml
├── scripts/
│   ├── form-handler.js
│   ├── gtm-events.js
│   └── fraud-guard.js
└── _astro/          # hashed CSS/JS bundles
```

### PHP API

Upload `api/` directory to `public_html/api/`:

```
public_html/api/
├── config.php
├── submit.php
└── env.php          # ← CREATE THIS (never in Git)
```

### Server-Side Credentials

Create `env.php` by copying `env.php.example`:

```php
<?php
$_ENV['SMTP2GO_API_KEY'] = 'api-XXXXXXXXXXXXXXXX';
$_ENV['SMTP2GO_SENDER'] = 'leads@1stclasschimney.com';
$_ENV['NOTIFICATION_EMAIL_1'] = 'owner@1stclasschimney.com';
$_ENV['NOTIFICATION_EMAIL_2'] = 'manager@1stclasschimney.com';
$_ENV['NOTIFICATION_EMAIL_3'] = '';
```

### Server Config

Upload `.htaccess` to `public_html/`.

### GTM Setup

Replace `GTM-XXXXXX` in `BaseLayout.astro` with actual GTM container ID before building.

## DNS Records for SMTP2GO (NFR14)

Add these to your domain DNS:

- **SPF**: `v=spf1 include:spf.smtp2go.com ~all`
- **DKIM**: Follow SMTP2GO dashboard → Settings → Sender Domains
- **DMARC**: `v=DMARC1; p=quarantine; rua=mailto:dmarc@1stclasschimney.com`

## Post-Deploy Verification

1. ✅ Page loads over HTTPS
2. ✅ Form submission sends notification to all 3 emails
3. ✅ Autoresponder sends to test email
4. ✅ GTM fires `form_submission` event (check GTM Preview)
5. ✅ All images load without 404s
6. ✅ Mobile sticky CTA visible on phone
7. ✅ robots.txt and sitemap accessible
