/**
 * cookie-consent.js
 * Shows the site-wide cookie banner (template-parts/cookie-banner.php)
 * once per visitor until they choose Accept or Reject, then remembers
 * the choice and updates Google Consent Mode accordingly (the default
 * signal itself is set earlier, in header.php, before any Google tag
 * loads — see that file for why).
 */

(function () {
  'use strict';

  var KEY = 'mp_cookie_consent';

  function pushConsent(granted) {
    if (typeof window.gtag !== 'function') return;
    var state = granted ? 'granted' : 'denied';
    window.gtag('consent', 'update', {
      'ad_storage':         state,
      'ad_user_data':       state,
      'ad_personalization': state,
      'analytics_storage':  state
    });
  }

  function init() {
    var banner = document.getElementById('mpCookieBanner');
    var accept = document.getElementById('mpCookieAccept');
    var reject = document.getElementById('mpCookieReject');
    if (!banner || !accept) return;

    var consent;
    try { consent = localStorage.getItem(KEY); } catch (e) { consent = null; }
    if (consent === 'accepted' || consent === 'rejected') return;

    banner.style.display = 'flex';

    accept.addEventListener('click', function () {
      try { localStorage.setItem(KEY, 'accepted'); } catch (e) {}
      pushConsent(true);
      banner.style.display = 'none';
    });

    if (reject) {
      reject.addEventListener('click', function () {
        try { localStorage.setItem(KEY, 'rejected'); } catch (e) {}
        pushConsent(false);
        banner.style.display = 'none';
      });
    }
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init);
  } else {
    init();
  }
})();
