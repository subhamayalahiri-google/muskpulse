/**
 * cookie-consent.js
 * Shows the site-wide cookie banner (template-parts/cookie-banner.php)
 * once per visitor until they accept, then remembers the choice.
 */

(function () {
  'use strict';

  var KEY = 'mp_cookie_consent';

  function init() {
    var banner = document.getElementById('mpCookieBanner');
    var accept = document.getElementById('mpCookieAccept');
    if (!banner || !accept) return;

    var consent;
    try { consent = localStorage.getItem(KEY); } catch (e) { consent = null; }
    if (consent === '1') return;

    banner.style.display = 'flex';

    accept.addEventListener('click', function () {
      try { localStorage.setItem(KEY, '1'); } catch (e) {}
      banner.style.display = 'none';
    });
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init);
  } else {
    init();
  }
})();
