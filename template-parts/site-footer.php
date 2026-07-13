<?php
/**
 * template-parts/site-footer.php
 *
 * Site-wide footer bar (copyright + social links). Shown on every page:
 * - Front page includes this directly, inside #landing-page (right before
 *   it closes) — that container is its own fixed, independently-scrolling
 *   viewport (see the "FRONT PAGE SCROLL" comment in global.css), so
 *   anything appended via the wp_footer hook instead would render outside
 *   it and never be reachable.
 * - Every other page gets it via the wp_footer hook in functions.php,
 *   which explicitly skips is_front_page() to avoid rendering this twice.
 */
?>
<footer class="mp-footer">
  <div class="mp-footer-inner">
    <span class="mp-footer-copy">&copy; <?php echo esc_html(date('Y')); ?> MuskPulse</span>
    <div class="mp-footer-social">
      <a href="https://x.com/muskpulse_" target="_blank" rel="noopener noreferrer" class="mp-social-icon x" aria-label="MuskPulse on X">𝕏</a>
      <a href="https://www.linkedin.com/company/muskpulse/" target="_blank" rel="noopener noreferrer" class="mp-social-icon linkedin" aria-label="MuskPulse on LinkedIn">in</a>
    </div>
  </div>
</footer>
