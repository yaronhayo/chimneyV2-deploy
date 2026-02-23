/**
 * form-handler.js — 1st Class Chimney & Air Duct
 * Client-side form validation, submission, and confirmation.
 * Covers: FR9, FR10, FR11, FR21, FR44, FR45
 */

(function () {
  'use strict';

  const form = document.getElementById('contact-form');
  if (!form) return;

  // Record load timestamp for time-to-submit bot check (FR44)
  const formLoadTime = Date.now();

  // ── UTM Capture (FR21) ─────────────────────────────────────────────────
  function getUTMParams() {
    const params = new URLSearchParams(window.location.search);
    const utmKeys = ['utm_source', 'utm_medium', 'utm_campaign', 'utm_term', 'utm_content'];
    const utm = {};
    utmKeys.forEach((key) => {
      const val = params.get(key);
      if (val) utm[key] = val;
    });
    return utm;
  }

  // ── Validation Helpers & Formatters ────────────────────────────────────
  function showError(field, message) {
    clearError(field);
    const el = document.createElement('div');
    el.className = 'contact__field-error';
    el.textContent = message;
    el.setAttribute('role', 'alert');
    field.parentElement.appendChild(el);
    field.classList.add('contact__input--error');
    field.setAttribute('aria-invalid', 'true');
  }

  function clearError(field) {
    const existing = field.parentElement.querySelector('.contact__field-error');
    if (existing) existing.remove();
    field.classList.remove('contact__input--error');
    field.removeAttribute('aria-invalid');
  }

  function clearAllErrors() {
    form.querySelectorAll('.contact__field-error').forEach((el) => el.remove());
    form.querySelectorAll('.contact__input--error').forEach((el) => {
      el.classList.remove('contact__input--error');
      el.removeAttribute('aria-invalid');
    });
  }

  function formatUSPhone(value) {
    const cleaned = ('' + value).replace(/\D/g, '');
    const match = cleaned.match(/^(\d{0,3})(\d{0,3})(\d{0,4})$/);
    if (!match) return value;
    if (!match[2]) return match[1];
    return `(${match[1]}) ${match[2]}${match[3] ? '-' + match[3] : ''}`;
  }

  function capitalizeName(val) {
    return val.replace(/\b\w/g, (c) => c.toUpperCase());
  }

  // Common typo domains
  const COMMON_DOMAINS = ['gmail.com', 'yahoo.com', 'hotmail.com', 'outlook.com', 'icloud.com'];
  function suggestEmailCorrection(emailStr) {
    const parts = emailStr.split('@');
    if (parts.length !== 2) return null;
    const domain = parts[1].toLowerCase();

    // Simple heuristic for 1-2 character typos
    for (const common of COMMON_DOMAINS) {
      if (domain === common) return null; // exact match
      const isTypo =
        domain.length > common.length - 3 &&
        domain.length < common.length + 3 &&
        (common.startsWith(domain.substring(0, 3)) || common.endsWith(domain.slice(-3)));
      if (isTypo && domain !== common) {
        return `${parts[0]}@${common}`;
      }
    }
    return null;
  }

  function validatePhone(phone) {
    const digits = phone.replace(/\D/g, '');
    return digits.length === 10;
  }

  function validateForm() {
    let isValid = true;
    clearAllErrors();

    const firstName = form.querySelector('#first-name');
    const lastName = form.querySelector('#last-name');
    const phone = form.querySelector('#phone');
    const email = form.querySelector('#email');
    const service = form.querySelector('#service-select');

    if (!firstName.value.trim()) {
      showError(firstName, 'First name is required');
      isValid = false;
    }

    if (!lastName.value.trim()) {
      showError(lastName, 'Last name is required');
      isValid = false;
    }

    if (!phone.value.trim()) {
      showError(phone, 'Phone number is required');
      isValid = false;
    } else if (!validatePhone(phone.value)) {
      showError(phone, 'Please enter a valid 10-digit phone number');
      isValid = false;
    }

    const emailVal = email.value.trim();
    if (emailVal) {
      if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(emailVal)) {
        showError(email, 'Please enter a valid email address');
        isValid = false;
      } else {
        const suggestion = suggestEmailCorrection(emailVal);
        if (suggestion && email.dataset.corrected !== 'true') {
          showError(email, `Did you mean ${suggestion}? Click submit again if correct.`);
          email.value = suggestion;
          email.dataset.corrected = 'true';
          isValid = false;
        }
      }
    }

    if (!service.value) {
      showError(service, 'Please select a service');
      isValid = false;
    }

    return isValid;
  }

  // Attach live formatters
  const fNameInput = form.querySelector('#first-name');
  if (fNameInput)
    Object.assign(fNameInput, {
      oninput: (e) => {
        e.target.value = capitalizeName(e.target.value);
      },
    });

  const lNameInput = form.querySelector('#last-name');
  if (lNameInput)
    Object.assign(lNameInput, {
      oninput: (e) => {
        e.target.value = capitalizeName(e.target.value);
      },
    });

  const phoneInput = form.querySelector('#phone');
  if (phoneInput)
    Object.assign(phoneInput, {
      oninput: (e) => {
        e.target.value = formatUSPhone(e.target.value);
      },
    });

  const emailInput = form.querySelector('#email');
  if (emailInput)
    Object.assign(emailInput, {
      oninput: (e) => {
        e.target.value = e.target.value.toLowerCase();
        e.target.dataset.corrected = 'false';
      },
    });

  // ── Submit Handler ─────────────────────────────────────────────────────
  const submitBtn = form.querySelector('.contact__submit');
  const originalBtnText = submitBtn ? submitBtn.textContent : 'Submit Request';

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
    const formCard = form.closest('.contact__form-card');
    if (!formCard) return;

    formCard.innerHTML = `
      <div class="contact__confirmation">
        <div class="contact__confirmation-icon">
          <span class="material-symbols-outlined">check_circle</span>
        </div>
        <h3 class="contact__confirmation-title">Request Received!</h3>
        <p class="contact__confirmation-text">
          Thank you for choosing 1st Class. We'll contact you within 2 hours to confirm your appointment.
        </p>
        <div class="contact__confirmation-timeline">
          <div class="contact__timeline-step">
            <span class="contact__timeline-icon">✅</span>
            <span>Request Received</span>
          </div>
          <div class="contact__timeline-connector"></div>
          <div class="contact__timeline-step">
            <span class="contact__timeline-icon">📞</span>
            <span>We'll Call You</span>
          </div>
          <div class="contact__timeline-connector"></div>
          <div class="contact__timeline-step">
            <span class="contact__timeline-icon">🏠</span>
            <span>We Arrive</span>
          </div>
        </div>
        <a href="tel:8886969296" class="btn btn-primary contact__confirmation-call">
          <span class="material-symbols-outlined">call</span>
          Can't Wait? Call Now
        </a>
      </div>
    `;
  }

  function showFormError(message) {
    let errorEl = form.querySelector('.contact__form-error');
    if (!errorEl) {
      errorEl = document.createElement('div');
      errorEl.className = 'contact__form-error';
      errorEl.setAttribute('role', 'alert');
      form.insertBefore(errorEl, form.firstChild);
    }
    // Build via DOM API — no innerHTML with user/server data (XSS-safe)
    errorEl.textContent = '';

    var icon = document.createElement('span');
    icon.className = 'material-symbols-outlined';
    icon.textContent = 'error';
    errorEl.appendChild(icon);

    var content = document.createElement('div');

    var strong = document.createElement('strong');
    strong.textContent = message;
    content.appendChild(strong);

    var phoneLink = document.createElement('a');
    phoneLink.href = 'tel:8886969296';
    phoneLink.className = 'contact__form-error-phone';
    phoneLink.textContent = 'Call us directly: (888) 696-9296';
    content.appendChild(phoneLink);

    errorEl.appendChild(content);
  }

  form.addEventListener('submit', async function (e) {
    e.preventDefault();

    if (!validateForm()) return;

    const timeToSubmit = Date.now() - formLoadTime;
    const honeypot = form.querySelector('#website');

    const payload = {
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

    const executeSubmit = async (token) => {
      payload['g-recaptcha-response'] = token;
      try {
        const response = await fetch('/api/submit.php', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify(payload),
        });

        const data = await response.json();

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
    };

    if (window.grecaptcha) {
      grecaptcha.ready(function () {
        grecaptcha
          .execute('6LeIxAcTAAAAAJcZVRqyHh71UMIEGNQ_MXjiZKhI', { action: 'submit' })
          .then(function (token) {
            executeSubmit(token);
          })
          .catch(function () {
            executeSubmit('');
          });
      });
    } else {
      executeSubmit('');
    }
  });
})();
