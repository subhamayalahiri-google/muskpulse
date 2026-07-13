<?php
/**
 * MuskPulse Theme — functions.php
 *
 * Responsibilities:
 * - Enqueue all CSS and JS assets
 * - Remove unused WordPress default output (emojis, RSD, generator tags)
 * - No third-party theme dependency
 */

// ── SPLASH SCREEN TOGGLE ─────────────────────────────────────────────────
// Set to false to disable the splash animation temporarily (e.g. high-traffic
// events like IPO day). Set back to true afterward.
define('MP_SPLASH_ENABLED', false);

// ── ASSETS ──────────────────────────────────────────────────────────────────

// Per-file cache-busting version — uses each asset's own last-modified time
// so browsers/host-level static caching (e.g. Hostinger's 7-day max-age on
// CSS/JS) automatically fetch the new file after every deploy, with no
// manual version bump needed on future edits.
function mp_asset_version($rel_path) {
  $path = get_stylesheet_directory() . $rel_path;
  return file_exists($path) ? (string) filemtime($path) : '1.0';
}

add_action('wp_enqueue_scripts', function() {

  $uri = get_stylesheet_directory_uri();

  // Global CSS — design tokens, reset, shared components
  wp_enqueue_style('mp-global', $uri . '/css/global.css', [], mp_asset_version('/css/global.css'));

  // Homepage splash + landing page CSS (only on front page)
  if (is_front_page()) {
    wp_enqueue_style('mp-splash', $uri . '/css/splash.css', ['mp-global'], mp_asset_version('/css/splash.css'));
  }

  // Article and archive CSS — loads on single posts, category archives, Mission Feed, the
  // front page (Mission Feed cards there reuse .archive-card so they stay visually consistent
  // with the other category pages), custom page templates, and error pages
    if (is_single() || is_archive() || is_home() || is_404() || is_front_page()
        || is_page_template('page-mission-briefing.php')
        || is_page_template('page-thank-you.php')
        || is_page_template('page-spacex-ipo.php')
        || is_page_template('page-saved-posts.php')
        || is_page_template('page-faq.php')
        || is_page_template('page-contact.php')) {
      wp_enqueue_style('mp-article', $uri . '/css/article.css', ['mp-global'], mp_asset_version('/css/article.css'));
    }

  // Mobile/tablet overrides (≤1080px) extracted from global/article/splash.css
  // into their own file, enqueued with a media query so viewports above that
  // width aren't render-blocked on it — the browser still fetches it (in case
  // the window gets resized/rotated) but at low priority, off the critical
  // path for first paint on desktop. Dependencies must only list handles
  // actually registered on this page — wp_style_is(..., 'registered') is
  // required here because listing a handle that was never registered (e.g.
  // mp-splash outside the front page) makes WP silently drop the entire
  // dependent stylesheet, not just skip that one dependency.
  $mobile_css_deps = array_filter(
    ['mp-global', 'mp-article', 'mp-splash'],
    function ($handle) { return wp_style_is($handle, 'registered'); }
  );
  wp_enqueue_style('mp-mobile', $uri . '/css/mobile.css', $mobile_css_deps, mp_asset_version('/css/mobile.css'), '(max-width: 1080px)');

  // Live TSLA stock price — loads on all pages except front page (splash handles its own JS)
  if (!is_front_page()) {
    wp_enqueue_script('mp-stock', $uri . '/js/stock.js', [], mp_asset_version('/js/stock.js'), true);
  }

  // "Share to..." popover (Facebook/X/LinkedIn/Copy Link) — needed on every
  // page that has Share buttons, loaded as a dependency of both scripts below
  if (is_front_page() || is_archive() || is_home() || is_page_template('page-spacex-ipo.php') || is_page_template('page-saved-posts.php')) {
    wp_enqueue_script('mp-share-popover', $uri . '/js/share-popover.js', [], mp_asset_version('/js/share-popover.js'), true);
  }

  // Saved Posts view — reads localStorage + WP REST API, only needed on that page
  if (is_page_template('page-saved-posts.php')) {
    wp_enqueue_script('mp-saved-posts', $uri . '/js/saved-posts.js', ['mp-share-popover'], mp_asset_version('/js/saved-posts.js'), true);
  }

  // Share/Save actions for server-rendered .archive-card grids (front page,
  // Mission Feed, category archives, SpaceX IPO landing page)
  if (is_front_page() || is_archive() || is_home() || is_page_template('page-spacex-ipo.php')) {
    wp_enqueue_script('mp-card-actions', $uri . '/js/card-actions.js', ['mp-share-popover'], mp_asset_version('/js/card-actions.js'), true);
  }

});

// ── REMOVE WORDPRESS BLOAT ───────────────────────────────────────────────────

add_action('init', function() {
  // Remove emoji scripts and styles — not needed
  remove_action('wp_head',         'print_emoji_detection_script', 7);
  remove_action('wp_print_styles', 'print_emoji_styles');

  // Remove unused head tags
  remove_action('wp_head', 'wp_generator');          // WordPress version (security risk)
  remove_action('wp_head', 'wlwmanifest_link');      // Windows Live Writer (unused)
  remove_action('wp_head', 'rsd_link');              // Really Simple Discovery (unused)
  remove_action('wp_head', 'wp_shortlink_wp_head');  // Shortlink (unused)
});

// Remove oEmbed scripts (not needed for this site)
remove_action('wp_head', 'wp_oembed_add_discovery_links');
remove_action('wp_head', 'wp_oembed_add_host_js');

// ── SUPPRESS "POWERED BY WORDPRESS" FOOTER ──────────────────────────────────
// The Hostinger Reach plugin injects "MuskPulse is proudly powered by WordPress"
// via wp_footer. Remove its callback and hide any remaining output via CSS.

add_action('wp_footer', function() {
  // Remove Hostinger Reach plugin footer callbacks
  global $wp_filter;
  if (isset($wp_filter['wp_footer'])) {
    foreach ($wp_filter['wp_footer']->callbacks as $priority => $callbacks) {
      foreach ($callbacks as $key => $callback) {
        $fn = $callback['function'];
        // Remove any callback from the Hostinger Reach plugin
        if (is_array($fn) && is_object($fn[0])) {
          $class = get_class($fn[0]);
          if (stripos($class, 'hostinger') !== false || stripos($class, 'reach') !== false) {
            unset($wp_filter['wp_footer']->callbacks[$priority][$key]);
          }
        } elseif (is_string($fn) && (stripos($fn, 'hostinger') !== false || stripos($fn, 'reach') !== false)) {
          unset($wp_filter['wp_footer']->callbacks[$priority][$key]);
        }
      }
    }
  }
}, 1);

// ── SITE FOOTER ──────────────────────────────────────────────────────────
// Every page except the front page, which includes template-parts/site-footer
// directly instead (inside #landing-page, its own scroll container — content
// appended here via wp_footer would render outside it and never be reachable).
add_action('wp_footer', function() {
  if (!is_front_page()) {
    get_template_part('template-parts/site-footer');
  }
});

// CSS safety net — catches anything that slips through
add_action('wp_head', function() {
  echo '<style>
    .site-info, #colophon, .powered-by,
    [class*="powered-by"], [id*="powered-by"],
    [class*="hostinger"], [id*="hostinger"],
    .hostinger-reach-footer { display:none !important; }
  </style>';
});

// ── THEME SUPPORT ────────────────────────────────────────────────────────────

add_action('after_setup_theme', function() {
  // Allow WordPress to manage the page title tag
  add_theme_support('title-tag');

  // Allow featured images on posts
  add_theme_support('post-thumbnails');

  // HTML5 markup for core elements
  add_theme_support('html5', ['search-form', 'comment-form', 'comment-list', 'gallery', 'caption']);
});

// ── READING TIME HELPER ──────────────────────────────────────────────────────
// Shared by every template that shows an estimated read time (single posts,
// Mission Feed, category archives, SpaceX IPO page). Pass a WP_Post (or its
// post_content) when iterating outside the main loop (e.g. a plain foreach
// over get_posts()); omit it inside a real Loop (get_the_content() then
// reads the post the_post()/WP_Query::the_post() already set up).

function mp_reading_time($source = null) {
  if ($source === null) {
    $content = get_the_content();
  } elseif ($source instanceof WP_Post) {
    $content = $source->post_content;
  } else {
    $content = $source;
  }
  $word_count = str_word_count(strip_tags($content));
  return max(1, ceil($word_count / 200));
}


add_action('wp_print_scripts', function() {
  if (!is_admin()) {
    wp_dequeue_script('quicktags');
    wp_dequeue_script('convertkit-admin-quicktags');
  }
}, 100);

add_action('wp_print_styles', function() {
  if (!is_admin()) {
    wp_dequeue_style('convertkit-admin-quicktags');
  }
}, 100);

// ── CONTACT FORM ─────────────────────────────────────────────────────────
// Handles the form in page-contact.php via admin-post.php (WordPress's
// standard pattern for custom form handlers, no plugin needed). Registered
// for both logged-in and logged-out visitors since the contact page is
// public.
function mp_handle_contact_form() {
  $redirect_base = wp_get_referer() ?: home_url('/contact');

  // Honeypot — a hidden field real visitors never fill in; bots usually do.
  // Pretend success rather than exposing that a spam check exists.
  if (!empty($_POST['mp_contact_website'])) {
    wp_safe_redirect(add_query_arg('mp_contact', 'sent', $redirect_base));
    exit;
  }

  $valid_nonce = isset($_POST['mp_contact_nonce'])
    && wp_verify_nonce($_POST['mp_contact_nonce'], 'mp_contact_submit');

  $name    = isset($_POST['mp_contact_name'])    ? sanitize_text_field(wp_unslash($_POST['mp_contact_name']))    : '';
  $email   = isset($_POST['mp_contact_email'])   ? sanitize_email(wp_unslash($_POST['mp_contact_email']))       : '';
  $subject = isset($_POST['mp_contact_subject']) ? sanitize_text_field(wp_unslash($_POST['mp_contact_subject'])) : '';
  $message = isset($_POST['mp_contact_message']) ? sanitize_textarea_field(wp_unslash($_POST['mp_contact_message'])) : '';

  if (!$valid_nonce || !$name || !$email || !is_email($email) || !$subject || !$message) {
    wp_safe_redirect(add_query_arg('mp_contact', 'error', $redirect_base));
    exit;
  }

  $mail_subject = 'MuskPulse Contact: ' . $subject;
  $mail_body    = "Name: {$name}\nEmail: {$email}\n\n{$message}";
  $mail_headers = [
    'Content-Type: text/plain; charset=UTF-8',
    'Reply-To: ' . $name . ' <' . $email . '>',
  ];

  $sent = wp_mail('info@muskpulse.com', $mail_subject, $mail_body, $mail_headers);

  wp_safe_redirect(add_query_arg('mp_contact', $sent ? 'sent' : 'error', $redirect_base));
  exit;
}
add_action('admin_post_mp_contact_submit',        'mp_handle_contact_form');
add_action('admin_post_nopriv_mp_contact_submit', 'mp_handle_contact_form');
