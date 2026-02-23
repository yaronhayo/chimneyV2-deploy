/**
 * Dynamic Text Replacement (DTR) Engine - Deeply Optimized
 * Features: Early exit, O(1) attribute scanning, FOUC prevention,
 * strict input sanitization, acronym preservation, and TTL persistent caching.
 */

document.addEventListener('DOMContentLoaded', async () => {
  const dtrLocationEls = document.querySelectorAll('[data-dtr="location"]');
  const dtrServiceEls = document.querySelectorAll('[data-dtr="service"]');

  // 1. Early Exit: Do nothing if no DTR elements exist
  if (dtrLocationEls.length === 0 && dtrServiceEls.length === 0) return;

  const urlParams = new URLSearchParams(window.location.search);

  // Strict sanitization: alphanumeric + spaces, max 30 chars
  const sanitizeInput = (input) => {
    if (!input) return null;
    const decoded = decodeURIComponent(input).replace(/\+/g, ' ');
    return decoded
      .replace(/[^a-zA-Z0-9\s-]/g, '')
      .substring(0, 30)
      .trim();
  };

  // Advanced formatting preserving industry acronyms & state codes
  const preserveAcronyms = ['HVAC', 'TX', 'CA', 'NY', 'FL', 'US', 'USA', 'DFW'];
  const toTitleCase = (str) => {
    return str
      .split(' ')
      .map((word) => {
        const upperWord = word.toUpperCase();
        if (preserveAcronyms.includes(upperWord)) return upperWord;
        return word.charAt(0).toUpperCase() + word.slice(1).toLowerCase();
      })
      .join(' ');
  };

  // Utility to inject and reveal to prevent FOUC
  const injectAndReveal = (elements, text, defaultText) => {
    elements.forEach((el) => {
      // Per-element default overrides global default (e.g., hero title vs body copy)
      const elDefault = el.getAttribute('data-dtr-default');
      const finalText = toTitleCase(text || elDefault || defaultText);
      const prefix = el.getAttribute('data-dtr-prefix') || '';
      const suffix = el.getAttribute('data-dtr-suffix') || '';
      el.textContent = `${prefix}${finalText}${suffix}`;
      el.classList.add('dtr-loaded');
    });
  };

  // 2. Service Resolution (Fast Path)
  if (dtrServiceEls.length > 0) {
    const service = sanitizeInput(urlParams.get('service'));
    injectAndReveal(dtrServiceEls, service, 'Chimney Sweep & Air Duct');
  }

  // 3. Location Resolution (URL -> LocalStorage TTL -> Network Fallback)
  if (dtrLocationEls.length > 0) {
    let loc = sanitizeInput(urlParams.get('loc'));
    const CACHE_KEY = 'kwikey_dtr_loc_data';
    const CACHE_TTL_MS = 30 * 24 * 60 * 60 * 1000; // 30 days

    const setCache = (city) => {
      try {
        localStorage.setItem(CACHE_KEY, JSON.stringify({ city, timestamp: Date.now() }));
      } catch (e) {}
    };

    const getCache = () => {
      try {
        const cached = JSON.parse(localStorage.getItem(CACHE_KEY));
        if (cached && Date.now() - cached.timestamp < CACHE_TTL_MS) {
          return cached.city;
        }
      } catch (e) {}
      return null;
    };

    if (loc) {
      // URL dictates truth; refresh cache
      setCache(loc);
      injectAndReveal(dtrLocationEls, loc, 'Your Area');
    } else {
      // Check persistent TTL cache
      loc = getCache();

      if (loc) {
        // Cache Hit
        injectAndReveal(dtrLocationEls, loc, 'Your Area');
      } else {
        // Network Fallback with preemptive visual default (to avoid hanging UI)
        try {
          const controller = new AbortController();
          const timeoutId = setTimeout(() => controller.abort(), 2000);

          const response = await fetch('https://get.geojs.io/v1/ip/geo.json', {
            signal: controller.signal,
          });
          clearTimeout(timeoutId);

          if (response.ok) {
            const geoData = await response.json();
            if (geoData && geoData.city) {
              const geoCity = sanitizeInput(geoData.city);
              setCache(geoCity);
              injectAndReveal(dtrLocationEls, geoCity, 'Your Area');
            } else {
              injectAndReveal(dtrLocationEls, null, 'Your Area');
            }
          } else {
            injectAndReveal(dtrLocationEls, null, 'Your Area');
          }
        } catch (e) {
          console.warn('GeoIP fetch failed or timed out:', e);
          injectAndReveal(dtrLocationEls, null, 'Your Area');
        }
      }
    }
  }
});
