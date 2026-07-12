<?php
/**
 * template-parts/cookie-banner.php
 *
 * Site-wide cookie consent banner. Injected on every page via the
 * wp_footer hook in functions.php — hidden by default, shown by
 * js/cookie-consent.js only if no prior consent is stored.
 */
?>
<div class="mp-cookie-banner" id="mpCookieBanner" style="display:none">
  <p class="mp-cookie-text">
    This site uses cookies to remember your preferences and understand how it's used. By continuing, you agree to our use of cookies.
  </p>
  <div class="mp-cookie-actions">
    <button class="mp-cookie-accept" id="mpCookieAccept" type="button">Accept</button>
  </div>
</div>
