<?php
/**
 * template-parts/html-footer.php
 *
 * Shared closing HTML for all MuskPulse pages.
 * Includes wp_footer() for plugin hooks and the live UTC clock script.
 *
 * Usage:
 *   get_template_part('template-parts/html-footer');
 *
 * The clock script targets any element with class 'mp-clock'.
 * Add class="mp-clock" to any span you want to show the live UTC time.
 * This avoids hardcoding element IDs and works across all templates.
 */
?>
<script>
(function() {
  function mpClock() {
    var n   = new Date();
    var pad = function(v) { return String(v).padStart(2, '0'); };
    var t   = pad(n.getUTCHours()) + ':' + pad(n.getUTCMinutes()) + ':' + pad(n.getUTCSeconds()) + ' UTC';
    // Update all elements with class mp-clock
    document.querySelectorAll('.mp-clock').forEach(function(el) { el.textContent = t; });
  }
  setInterval(mpClock, 1000);
  mpClock();
})();
</script>

<?php wp_footer(); ?>
</body>
</html>
