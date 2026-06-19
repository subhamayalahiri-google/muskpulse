<?php
/**
 * footer.php
 * Loaded by get_footer() in all templates.
 * Outputs the live clock, wp_footer() hooks, and closing HTML.
 */
?>
<script>
(function() {
  function mpClock() {
    var n   = new Date();
    var pad = function(v) { return String(v).padStart(2, '0'); };
    var t   = pad(n.getUTCHours()) + ':' + pad(n.getUTCMinutes()) + ':' + pad(n.getUTCSeconds()) + ' UTC';
    document.querySelectorAll('.mp-clock').forEach(function(el) { el.textContent = t; });
  }
  setInterval(mpClock, 1000);
  mpClock();
})();
</script>

<?php wp_footer(); ?>
</body>
</html>
