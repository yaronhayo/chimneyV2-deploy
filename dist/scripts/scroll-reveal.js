/**
 * scroll-reveal.js — Lightweight scroll-triggered reveal animations
 * Uses IntersectionObserver to add .revealed class when elements enter viewport.
 * Supports: data-reveal, data-reveal-delay, data-reveal-group (auto-stagger children).
 * ~30 lines. GPU-friendly (transform + opacity only). Respects prefers-reduced-motion.
 */
(function () {
  'use strict';

  // Skip entirely if user prefers reduced motion
  if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
    document.querySelectorAll('[data-reveal]').forEach(function (el) {
      el.classList.add('revealed');
    });
    return;
  }

  var observer = new IntersectionObserver(
    function (entries) {
      entries.forEach(function (entry) {
        if (!entry.isIntersecting) return;

        var el = entry.target;
        var delay = parseInt(el.getAttribute('data-reveal-delay') || '0', 10);

        if (delay > 0) {
          setTimeout(function () {
            el.classList.add('revealed');
          }, delay);
        } else {
          el.classList.add('revealed');
        }

        observer.unobserve(el);
      });
    },
    { threshold: 0.15, rootMargin: '0px 0px -40px 0px' }
  );

  // Observe individual reveal elements
  document.querySelectorAll('[data-reveal]').forEach(function (el) {
    observer.observe(el);
  });

  // Auto-stagger groups: find [data-reveal-group] and set delays on children
  document.querySelectorAll('[data-reveal-group]').forEach(function (group) {
    var stagger = parseInt(group.getAttribute('data-reveal-group') || '80', 10);
    var children = group.querySelectorAll('[data-reveal]');
    children.forEach(function (child, i) {
      child.setAttribute('data-reveal-delay', String(i * stagger));
    });
  });

  // Re-observe after stagger assignment
  document.querySelectorAll('[data-reveal]').forEach(function (el) {
    if (!el.classList.contains('revealed')) {
      observer.observe(el);
    }
  });
})();
