<?php
/**
 * header.php
 * Loaded by get_header() in all templates.
 * This correctly triggers wp_head() and wp_enqueue_scripts hooks.
 */
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
  <meta charset="<?php bloginfo('charset'); ?>">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <script>
  /* Google Consent Mode default — must run before any Google tag (AdSense,
     gtag/Analytics) so they hold non-essential cookies until the visitor
     accepts via the cookie banner (js/cookie-consent.js). Consent already
     recorded from a prior visit is applied immediately instead of denied. */
  (function () {
    window.dataLayer = window.dataLayer || [];
    function gtag() { dataLayer.push(arguments); }
    window.gtag = gtag;

    var consent;
    try { consent = localStorage.getItem('mp_cookie_consent'); } catch (e) { consent = null; }
    var granted = consent === 'accepted' ? 'granted' : 'denied';

    gtag('consent', 'default', {
      'ad_storage':         granted,
      'ad_user_data':       granted,
      'ad_personalization': granted,
      'analytics_storage':  granted,
      'wait_for_update':    500
    });
  })();
  </script>
  <?php wp_head(); ?>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700;900&family=Share+Tech+Mono&family=Exo+2:wght@300;400;600&display=swap" rel="stylesheet">
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
