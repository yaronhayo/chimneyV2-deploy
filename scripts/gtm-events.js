/**
 * gtm-events.js — GTM dataLayer event helpers
 * Fires conversion events on form submission (FR20, FR21)
 */

(function () {
  'use strict';

  window.dataLayer = window.dataLayer || [];

  /**
   * Push a form submission conversion event to GTM dataLayer.
   * Called by form-handler.js on successful submission.
   */
  window.pushFormConversion = function (service, utm) {
    var eventData = {
      event: 'form_submission',
      form_name: 'lead_form',
      service_type: service || '',
      conversion_time: new Date().toISOString(),
    };

    // Append UTM params if available
    if (utm && typeof utm === 'object') {
      if (utm.utm_source) eventData.utm_source = utm.utm_source;
      if (utm.utm_medium) eventData.utm_medium = utm.utm_medium;
      if (utm.utm_campaign) eventData.utm_campaign = utm.utm_campaign;
    }

    window.dataLayer.push(eventData);
  };
})();
