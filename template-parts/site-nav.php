<?php
/**
 * template-parts/site-nav.php
 *
 * Shared nav bar for all MuskPulse pages.
 *
 * Usage:
 *   get_template_part('template-parts/site-nav', null, ['clock_id' => 'live-clock']);
 *
 * Args:
 *   clock_id (string) — ID for the UTC clock span. Defaults to 'live-clock'.
 *                       Use a unique ID per page if multiple templates load this.
 */
$clock_id = isset($args['clock_id']) ? esc_attr($args['clock_id']) : 'live-clock';
?>
<nav class="mv-nav" id="mvNav">
  <a href="<?php echo esc_url(home_url('/')); ?>" class="mv-logo">MUSK<span>PULSE</span></a>
  <ul class="mv-nav-links" id="mvNavLinks">
    <li><a href="<?php echo esc_url(home_url('/mission-feed')); ?>">Mission Feed</a></li>
    <li><a href="<?php echo esc_url(home_url('/category/tesla-news')); ?>">TSLA Intel</a></li>
    <li><a href="<?php echo esc_url(home_url('/spacex-ipo')); ?>">SpaceX IPO</a></li>
    <li><a href="<?php echo esc_url(home_url('/category/xai-optimus')); ?>">Optimus &amp; Neuralink</a></li>
  </ul>
  <div class="mv-nav-right">
    <span class="live-dot"></span>
    <span class="mv-live-label">LIVE FEED ACTIVE</span>
    <span id="<?php echo $clock_id; ?>" class="mp-clock">00:00:00 UTC</span>
  </div>
  <!-- Mobile only: hamburger -->
  <button class="mv-hamburger" id="mvHamburger" aria-label="Toggle menu" aria-expanded="false">
    <span></span><span></span><span></span>
  </button>
</nav>
<!-- Mobile only: dropdown menu — hidden by default via inline style (JS controls display) -->
<div class="mv-mobile-menu" id="mvMobileMenu" style="display:none">
  <a href="<?php echo esc_url(home_url('/mission-feed')); ?>">Mission Feed</a>
  <a href="<?php echo esc_url(home_url('/category/tesla-news')); ?>">TSLA Intel</a>
  <a href="<?php echo esc_url(home_url('/category/spacex-ipo')); ?>">SpaceX IPO</a>
  <a href="<?php echo esc_url(home_url('/category/xai-optimus')); ?>">Optimus &amp; Neuralink</a>
  <div class="mv-mobile-menu-foot">
    <span class="live-dot"></span>
    <span id="<?php echo $clock_id; ?>-mob" class="mp-clock mv-mob-clock">00:00:00 UTC</span>
  </div>
</div>
<script>
(function() {
  // Desktop clock
  var clockEl = document.getElementById('<?php echo $clock_id; ?>');
  // Mobile clock (same time, separate element)
  var clockMob = document.getElementById('<?php echo $clock_id; ?>-mob');
  function tick() {
    var n = new Date();
    var p = function(v) { return String(v).padStart(2,'0'); };
    var t = p(n.getUTCHours())+':'+p(n.getUTCMinutes())+':'+p(n.getUTCSeconds())+' UTC';
    if (clockEl)  clockEl.textContent  = t;
    if (clockMob) clockMob.textContent = t;
  }
  setInterval(tick, 1000);
  tick();

  // Hamburger toggle — drives display via inline style, not class, so CSS can't interfere
  var btn  = document.getElementById('mvHamburger');
  var menu = document.getElementById('mvMobileMenu');
  if (btn && menu) {
    btn.addEventListener('click', function() {
      var opening = menu.style.display === 'none' || menu.style.display === '';
      menu.style.display = opening ? 'flex' : 'none';
      btn.classList.toggle('open', opening);
      btn.setAttribute('aria-expanded', opening ? 'true' : 'false');
    });
    // Close on outside click
    document.addEventListener('click', function(e) {
      if (!btn.contains(e.target) && !menu.contains(e.target)) {
        menu.style.display = 'none';
        btn.classList.remove('open');
        btn.setAttribute('aria-expanded', 'false');
      }
    });
  }
})();
</script>
