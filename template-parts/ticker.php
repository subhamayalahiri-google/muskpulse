<?php
/**
 * template-parts/ticker.php
 *
 * Shared scrolling market ticker — included by ALL page templates.
 * TSLA price uses mp-tsla-ticker class — updated live by stock.js.
 * All other items are static and edited here only.
 * Content is duplicated intentionally for seamless CSS scroll loop.
 *
 * Usage:
 *   Standard (mv-ticker):
 *     get_template_part('template-parts/ticker')
 *
 *   Landing page (lp-ticker with animation ID):
 *     get_template_part('template-parts/ticker', null, ['variant' => 'lp'])
 */

$variant  = isset($args['variant']) && $args['variant'] === 'lp' ? 'lp' : 'mv';
$wrap_cls = $variant === 'lp' ? 'lp-ticker" id="lpTicker' : 'mv-ticker';
$track_cls = $variant === 'lp' ? 'lp-ticker-track' : 'mv-ticker-track';
?>
<div class="<?php echo $wrap_cls; ?>">
  <div class="<?php echo $track_cls; ?>">
    <?php for ($i = 0; $i < 2; $i++) : /* duplicate for seamless CSS loop */ ?>
    <span><b>TSLA</b> <span class="mp-tsla-ticker">$-- <span class="up">▲ --%</span></span></span>
    <span class="sep">///</span>
    <span><b>SPACEX IPO</b> $SPCX ◆ JUNE 12 NASDAQ</span>
    <span class="sep">///</span>
    <span><b>FSD UNSUPERVISED</b> <span class="up">▲ 7 CITIES</span></span>
    <span class="sep">///</span>
    <span><b>CYBERCAB</b> IN PRODUCTION ◆ GIGA TX</span>
    <span class="sep">///</span>
    <span><b>OPTIMUS</b> MASS PROD ◆ 2026 TARGET</span>
    <span class="sep">///</span>
    <span><b>XAI + SPACEX</b> MERGED $230B</span>
    <span class="sep">///</span>
    <?php endfor; ?>
  </div>
</div>
