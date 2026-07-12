<?php
/**
 * footer.php
 * Loaded by get_footer() in all templates.
 * Outputs the live clock, wp_footer() hooks, and closing HTML.
 */
?>
<script>
(function() {
  var tzLabel = (function() {
    try {
      var part = Intl.DateTimeFormat(undefined, { timeZoneName: 'short' })
        .formatToParts(new Date())
        .find(function (p) { return p.type === 'timeZoneName'; });
      return part ? part.value : '';
    } catch (e) { return ''; }
  })();
  function mpClock() {
    var n   = new Date();
    var pad = function(v) { return String(v).padStart(2, '0'); };
    var t   = pad(n.getHours()) + ':' + pad(n.getMinutes()) + ':' + pad(n.getSeconds()) + (tzLabel ? ' ' + tzLabel : '');
    document.querySelectorAll('.mp-clock').forEach(function(el) { el.textContent = t; });
  }
  setInterval(mpClock, 1000);
  mpClock();
})();
</script>

<?php wp_footer(); ?>
</body>
</html>
