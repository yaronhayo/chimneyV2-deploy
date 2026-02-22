/**
 * fraud-guard.js — 1st Class Chimney & Air Duct
 * Passive click fraud detection and logging (FR49-53).
 *
 * Rules:
 * - Zero CAPTCHA, interstitials, or friction for real users (FR53)
 * - Detection runs passively via GTM custom events
 * - Known bot user agents flagged (FR49)
 * - Rapid repeat visits fingerprinted (FR50)
 * - Suspicious patterns logged: zero scroll, zero time-on-page, rapid revisits (FR51)
 */

(function () {
  'use strict';

  window.dataLayer = window.dataLayer || [];

  var sessionKey = '__fc_session';
  var visitKey = '__fc_visits';
  var signals = {
    isSuspicious: false,
    reasons: [],
  };

  // ── Bot UA Detection (FR49) ────────────────────────────────────────────
  var botPatterns = [
    /bot/i,
    /crawl/i,
    /spider/i,
    /headless/i,
    /phantom/i,
    /puppeteer/i,
    /selenium/i,
    /webdriver/i,
    /lighthouse/i,
    /pingdom/i,
    /gtmetrix/i,
    /pagespeed/i,
  ];

  var ua = navigator.userAgent || '';
  for (var i = 0; i < botPatterns.length; i++) {
    if (botPatterns[i].test(ua)) {
      signals.isSuspicious = true;
      signals.reasons.push('bot_ua');
      break;
    }
  }

  // ── WebDriver detection ────────────────────────────────────────────────
  if (navigator.webdriver) {
    signals.isSuspicious = true;
    signals.reasons.push('webdriver');
  }

  // ── Repeat Visit Tracking (FR50) ───────────────────────────────────────
  try {
    var now = Date.now();
    var visits = JSON.parse(sessionStorage.getItem(visitKey) || '[]');

    // Filter to last 30 minutes
    var thirtyMinAgo = now - 30 * 60 * 1000;
    visits = visits.filter(function (ts) {
      return ts > thirtyMinAgo;
    });
    visits.push(now);
    sessionStorage.setItem(visitKey, JSON.stringify(visits));

    // More than 10 page loads in 30 minutes = suspicious
    if (visits.length > 10) {
      signals.isSuspicious = true;
      signals.reasons.push('rapid_revisit');
    }
  } catch (e) {
    // sessionStorage may be blocked
  }

  // ── Zero Scroll / Zero Time Detection (FR51) ──────────────────────────
  var hasScrolled = false;
  var hasInteracted = false;
  var pageLoadTime = Date.now();

  window.addEventListener(
    'scroll',
    function () {
      hasScrolled = true;
    },
    { passive: true, once: true }
  );

  window.addEventListener(
    'click',
    function () {
      hasInteracted = true;
    },
    { once: true }
  );

  // Check after 30 seconds — if no scroll AND no interaction, flag
  setTimeout(function () {
    if (!hasScrolled && !hasInteracted) {
      signals.isSuspicious = true;
      signals.reasons.push('zero_engagement');
    }

    // Log signals to GTM (FR51)
    if (signals.isSuspicious) {
      window.dataLayer.push({
        event: 'fraud_signal',
        fraud_reasons: signals.reasons.join(','),
        fraud_ua: ua.substring(0, 100),
        fraud_time_on_page: Math.round((Date.now() - pageLoadTime) / 1000),
        fraud_scroll: hasScrolled,
      });
    }
  }, 30000);
})();
