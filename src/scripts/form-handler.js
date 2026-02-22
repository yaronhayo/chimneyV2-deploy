/**
 * form-handler.js — 1st Class Chimney & Air Duct
 * Client-side form validation, submission, and confirmation.
 * Covers: FR9, FR10, FR11, FR21, FR44, FR45
 */

(function () {
  'use strict';

  var form = document.getElementById('contact-form');
  if (!form) return;

  // Read phone from data attributes injected by Astro (no hardcoding)
  var businessPhone = form.getAttribute('data-phone') || '(555) 123-4567';
  var businessPhoneRaw = form.getAttribute('data-phone-raw') || '5551234567';

  // Record load timestamp for time-to-submit bot check (FR44)
  var formLoadTime = Date.now();

  // ── UTM Capture (FR21) ─────────────────────────────────────────────────
  function getUTMParams() {
    var params = new URLSearchParams(window.location.search);
    var utmKeys = ['utm_source', 'utm_medium', 'utm_campaign', 'utm_term', 'utm_content'];
    var utm = {};
    utmKeys.forEach(function (key) {
      var val = params.get(key);
      if (val) utm[key] = val;
    });
    return utm;
  }

  // ── Validation Helpers ─────────────────────────────────────────────────
  function showError(field, message) {
    clearError(field);
    var el = document.createElement('div');
    el.className = 'contact__field-error';
    el.textContent = message;
    el.setAttribute('role', 'alert');
    field.parentElement.appendChild(el);
    field.classList.add('contact__input--error');
    field.setAttribute('aria-invalid', 'true');
  }

  function clearError(field) {
    var existing = field.parentElement.querySelector('.contact__field-error');
    if (existing) existing.remove();
    field.classList.remove('contact__input--error');
    field.removeAttribute('aria-invalid');
  }

  function clearAllErrors() {
    form.querySelectorAll('.contact__field-error').forEach(function (el) {
      el.remove();
    });
    form.querySelectorAll('.contact__input--error').forEach(function (el) {
      el.classList.remove('contact__input--error');
      el.removeAttribute('aria-invalid');
    });
  }

  function validatePhone(phone) {
    // Accept 10-digit US phone: (555) 123-4567, 555-123-4567, 5551234567
    var digits = phone.replace(/\D/g, '');
    return digits.length === 10;
  }

  function validateForm() {
    var isValid = true;
    clearAllErrors();

    var firstName = form.querySelector('#first-name');
    var lastName = form.querySelector('#last-name');
    var phone = form.querySelector('#phone');
    var email = form.querySelector('#email');
    var service = form.querySelector('#service-select');

    if (!firstName.value.trim()) {
      showError(firstName, 'First name is required');
      isValid = false;
    }

    if (!lastName.value.trim()) {
      showError(lastName, 'Last name is required');
      isValid = false;
    }

    // Phone is required (FR9, FR45)
    if (!phone.value.trim()) {
      showError(phone, 'Phone number is required');
      isValid = false;
    } else if (!validatePhone(phone.value.trim())) {
      showError(phone, 'Please enter a valid 10-digit phone number');
      isValid = false;
    }

    // Email is optional — validate format only if provided
    if (email.value.trim() && !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email.value.trim())) {
      showError(email, 'Please enter a valid email address');
      isValid = false;
    }

    if (!service.value) {
      showError(service, 'Please select a service');
      isValid = false;
    }

    return isValid;
  }

  // ── Submit Handler ─────────────────────────────────────────────────────
  var submitBtn = form.querySelector('.contact__submit');
  var originalBtnText = submitBtn ? submitBtn.textContent : 'Submit Request';

  function setLoading(loading) {
    if (!submitBtn) return;
    submitBtn.disabled = loading;
    submitBtn.textContent = loading ? 'Sending…' : originalBtnText;
    if (loading) {
      submitBtn.style.opacity = '0.7';
      submitBtn.style.cursor = 'not-allowed';
    } else {
      submitBtn.style.opacity = '';
      submitBtn.style.cursor = '';
    }
  }

  function showConfirmation() {
    var formCard = form.closest('.contact__form-card');
    if (!formCard) return;

    // Build confirmation via DOM API (XSS-safe — no innerHTML with user data)
    formCard.textContent = '';

    var wrap = document.createElement('div');
    wrap.className = 'contact__confirmation';

    // Icon
    var iconWrap = document.createElement('div');
    iconWrap.className = 'contact__confirmation-icon';
    var icon = document.createElement('span');
    icon.className = 'material-symbols-outlined';
    icon.textContent = 'check_circle';
    iconWrap.appendChild(icon);
    wrap.appendChild(iconWrap);

    // Title
    var title = document.createElement('h3');
    title.className = 'contact__confirmation-title';
    title.textContent = 'Request Received!';
    wrap.appendChild(title);

    // Text
    var text = document.createElement('p');
    text.className = 'contact__confirmation-text';
    text.textContent =
      "Thank you for choosing 1st Class. We'll contact you within 2 hours to confirm your appointment.";
    wrap.appendChild(text);

    // Timeline
    var timeline = document.createElement('div');
    timeline.className = 'contact__confirmation-timeline';

    var steps = [
      { emoji: '✅', label: 'Request Received' },
      { emoji: '📞', label: "We'll Call You" },
      { emoji: '🏠', label: 'We Arrive' },
    ];

    steps.forEach(function (step, i) {
      if (i > 0) {
        var connector = document.createElement('div');
        connector.className = 'contact__timeline-connector';
        timeline.appendChild(connector);
      }
      var stepEl = document.createElement('div');
      stepEl.className = 'contact__timeline-step';
      var emojiEl = document.createElement('span');
      emojiEl.className = 'contact__timeline-icon';
      emojiEl.textContent = step.emoji;
      stepEl.appendChild(emojiEl);
      var labelEl = document.createElement('span');
      labelEl.textContent = step.label;
      stepEl.appendChild(labelEl);
      timeline.appendChild(stepEl);
    });

    wrap.appendChild(timeline);

    // Call CTA — using dynamic phone from data attribute
    var callLink = document.createElement('a');
    callLink.href = 'tel:' + businessPhoneRaw;
    callLink.className = 'btn btn-primary contact__confirmation-call';
    var callIcon = document.createElement('span');
    callIcon.className = 'material-symbols-outlined';
    callIcon.textContent = 'call';
    callLink.appendChild(callIcon);
    var callText = document.createTextNode(" Can't Wait? Call " + businessPhone);
    callLink.appendChild(callText);
    wrap.appendChild(callLink);

    formCard.appendChild(wrap);
  }

  function showFormError(message) {
    var errorEl = form.querySelector('.contact__form-error');
    if (!errorEl) {
      errorEl = document.createElement('div');
      errorEl.className = 'contact__form-error';
      errorEl.setAttribute('role', 'alert');
      form.insertBefore(errorEl, form.firstChild);
    }
    // Build via DOM API — no innerHTML with user/server data (XSS-safe)
    errorEl.textContent = '';

    var errIcon = document.createElement('span');
    errIcon.className = 'material-symbols-outlined';
    errIcon.textContent = 'error';
    errorEl.appendChild(errIcon);

    var content = document.createElement('div');

    var strong = document.createElement('strong');
    strong.textContent = message;
    content.appendChild(strong);

    var phoneLink = document.createElement('a');
    phoneLink.href = 'tel:' + businessPhoneRaw;
    phoneLink.className = 'contact__form-error-phone';
    phoneLink.textContent = 'Call us directly: ' + businessPhone;
    content.appendChild(phoneLink);

    errorEl.appendChild(content);
  }

  form.addEventListener('submit', async function (e) {
    e.preventDefault();

    if (!validateForm()) return;

    var timeToSubmit = Date.now() - formLoadTime;
    var honeypot = form.querySelector('#website');

    var payload = {
      firstName: form.querySelector('#first-name').value.trim(),
      lastName: form.querySelector('#last-name').value.trim(),
      phone: form.querySelector('#phone').value.trim(),
      email: form.querySelector('#email').value.trim(),
      service: form.querySelector('#service-select').value,
      honeypot: honeypot ? honeypot.value : '',
      timeToSubmit: timeToSubmit,
      utm: getUTMParams(),
      pageUrl: window.location.href,
    };

    setLoading(true);

    try {
      var response = await fetch('/api/submit.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(payload),
      });

      var data = await response.json();

      if (data.success) {
        showConfirmation();
        // Fire GTM conversion event (FR20, FR21)
        if (typeof window.pushFormConversion === 'function') {
          window.pushFormConversion(payload.service, payload.utm);
        }
      } else {
        showFormError(data.error || 'Something went wrong. Please try again.');
        setLoading(false);
      }
    } catch (err) {
      showFormError('Something went wrong. Please call us directly.');
      setLoading(false);
    }
  });
})();
