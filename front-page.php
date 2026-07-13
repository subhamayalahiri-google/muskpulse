<?php get_header(); ?>

<?php if (!MP_SPLASH_ENABLED) : ?>
<style>
  #scene, #hud, #scanlines, #atmosphere, #countdown-wrap, #liftoff-text, #flash,
  .corner, .telemetry, .mission-data, #starfield, #smokeCanvas { display:none !important; }
  #landing-page { opacity:1 !important; pointer-events:all !important; }
  body { cursor: default !important; }
</style>
<?php endif; ?>

<!-- ══ LANDING PAGE (z:3 — revealed as smoke clears) ══ -->
<div id="landing-page">

  <nav class="lp-nav" id="lpNav">
    <div class="lp-nav-brand">
      <div class="lp-logo">MUSK<span>PULSE</span></div>
      <span class="lp-nav-clock" id="lpNavClock">00:00:00</span>
    </div>
    <ul class="lp-nav-links">
      <li><a href="<?php echo esc_url(home_url('/mission-feed')); ?>">Mission Feed</a></li>
      <li><a href="<?php echo esc_url(home_url('/category/tesla-news')); ?>">TSLA Intel</a></li>
      <li><a href="<?php echo esc_url(home_url('/spacex-ipo')); ?>">SpaceX IPO</a></li>
      <li><a href="<?php echo esc_url(home_url('/category/xai-optimus')); ?>">Optimus &amp; Neuralink</a></li>
      <li class="mv-nav-more">
        <button class="mv-nav-more-trigger" id="lpMoreTrigger" type="button" aria-expanded="false">More ▾</button>
        <div class="mv-nav-more-pop" id="lpMorePop" style="display:none">
          <a href="<?php echo esc_url(home_url('/saved-posts')); ?>">Saved Posts</a>
          <a href="<?php echo esc_url(home_url('/faq')); ?>">FAQ</a>
          <a href="<?php echo esc_url(home_url('/contact')); ?>">Contact</a>
        </div>
      </li>
    </ul>
    <div class="lp-status-bar">
      <span class="live-dot"></span>
      <span>LIVE FEED ACTIVE</span>
      <span class="mp-clock">00:00:00</span>
    </div>
    <!-- Mobile only: hamburger (reuses .mv-hamburger/.mv-mobile-menu from
         template-parts/site-nav.php, already loaded via global.css) -->
    <button class="mv-hamburger" id="lpHamburger" aria-label="Toggle menu" aria-expanded="false">
      <span></span><span></span><span></span>
    </button>
  </nav>
  <div class="mv-mobile-menu" id="lpMobileMenu" style="display:none">
    <a href="<?php echo esc_url(home_url('/mission-feed')); ?>">Mission Feed</a>
    <a href="<?php echo esc_url(home_url('/category/tesla-news')); ?>">TSLA Intel</a>
    <a href="<?php echo esc_url(home_url('/spacex-ipo')); ?>">SpaceX IPO</a>
    <a href="<?php echo esc_url(home_url('/category/xai-optimus')); ?>">Optimus &amp; Neuralink</a>
    <a href="<?php echo esc_url(home_url('/saved-posts')); ?>">Saved Posts</a>
    <a href="<?php echo esc_url(home_url('/faq')); ?>">FAQ</a>
    <a href="<?php echo esc_url(home_url('/contact')); ?>">Contact</a>
    <div class="mv-mobile-menu-foot">
      <span class="live-dot"></span>
      <span class="mp-clock mv-mob-clock">00:00:00</span>
    </div>
  </div>
  <script>
  (function(){
    // All of this page's own clocks — status bar (desktop) and the mobile
    // dropdown's footer clock aren't reliably driven by anything else, so
    // this script owns all three directly rather than relying on a shared
    // global updater.
    var clockNav    = document.getElementById('lpNavClock');
    var clockStatus = document.querySelector('.lp-status-bar .mp-clock');
    var clockMob    = document.querySelector('#lpMobileMenu .mp-clock');

    if (clockNav || clockStatus || clockMob) {
      // Local timezone abbreviation (e.g. "EST", "PDT"), computed once.
      var tzLabel = (function() {
        try {
          var part = Intl.DateTimeFormat(undefined, { timeZoneName: 'short' })
            .formatToParts(new Date())
            .find(function (p) { return p.type === 'timeZoneName'; });
          return part ? part.value : '';
        } catch (e) { return ''; }
      })();
      var tick = function() {
        var n = new Date(), p = function(v){ return String(v).padStart(2,'0'); };
        var t = p(n.getHours())+':'+p(n.getMinutes())+':'+p(n.getSeconds()) + (tzLabel ? ' ' + tzLabel : '');
        if (clockNav)    clockNav.textContent    = t;
        if (clockStatus) clockStatus.textContent = t;
        if (clockMob)    clockMob.textContent    = t;
      };
      setInterval(tick, 1000); tick();
    }

    var btn  = document.getElementById('lpHamburger');
    var menu = document.getElementById('lpMobileMenu');
    if (btn && menu) {
      btn.addEventListener('click', function() {
        var opening = menu.style.display === 'none' || menu.style.display === '';
        menu.style.display = opening ? 'flex' : 'none';
        btn.classList.toggle('open', opening);
        btn.setAttribute('aria-expanded', opening ? 'true' : 'false');
      });
      document.addEventListener('click', function(e) {
        if (!btn.contains(e.target) && !menu.contains(e.target)) {
          menu.style.display = 'none';
          btn.classList.remove('open');
          btn.setAttribute('aria-expanded', 'false');
        }
      });
    }

    // Desktop "More" popup (Saved Posts, FAQ)
    var moreBtn = document.getElementById('lpMoreTrigger');
    var morePop = document.getElementById('lpMorePop');
    if (moreBtn && morePop) {
      moreBtn.addEventListener('click', function(e) {
        e.stopPropagation();
        var opening = morePop.style.display === 'none' || morePop.style.display === '';
        morePop.style.display = opening ? 'flex' : 'none';
        moreBtn.setAttribute('aria-expanded', opening ? 'true' : 'false');
      });
      document.addEventListener('click', function(e) {
        if (!moreBtn.contains(e.target) && !morePop.contains(e.target)) {
          morePop.style.display = 'none';
          moreBtn.setAttribute('aria-expanded', 'false');
        }
      });
    }
  })();
  </script>

  <?php get_template_part('template-parts/ticker', null, ['variant' => 'lp']); ?>

  <!-- ── MOBILE ONLY: Market data strip ── -->
  <div class="lp-market-strip">
    <div class="lp-ms-cell">
      <div class="lp-ms-sym">TSLA</div>
      <div class="lp-ms-price mp-tsla-price">$--</div>
      <div class="lp-ms-chg mp-tsla-pct pos">▲ --%</div>
    </div>
    <div class="lp-ms-cell">
      <div class="lp-ms-sym">SPCX</div>
      <div class="lp-ms-price mp-spcx-price">$135.00</div>
      <div class="lp-ms-chg mp-spcx-pct">PRE-IPO</div>
    </div>
    <div class="lp-ms-cell">
      <div class="lp-ms-sym">XOVR ETF</div>
      <div class="lp-ms-price">$31.40</div>
      <div class="lp-ms-chg pos">▲ 1.8%</div>
    </div>
  </div>

  <!-- ── MOBILE ONLY: Category tabs ── -->
  <div class="lp-mob-tabs">
    <a href="<?php echo esc_url(home_url('/mission-feed')); ?>"          class="lp-mob-tab active">ALL</a>
    <a href="<?php echo esc_url(home_url('/category/spacex-ipo')); ?>"   class="lp-mob-tab">SPACEX IPO</a>
    <a href="<?php echo esc_url(home_url('/category/tesla-news')); ?>"   class="lp-mob-tab">TSLA INTEL</a>
    <a href="<?php echo esc_url(home_url('/category/xai-optimus')); ?>"  class="lp-mob-tab">xAI</a>
    <a href="<?php echo esc_url(home_url('/category/xai-optimus')); ?>"  class="lp-mob-tab">NEURALINK</a>
  </div>

  <div class="lp-main" id="lpMain">

    <div class="lp-hero">
      <div class="c-tl"></div>
      <div class="lp-mission-tag">Mission Feed — Active</div>
      <h1 class="lp-h1">
        <span class="w1">INTEL ON</span>
        <span class="w2">TESLA. SPACEX.</span>
        <span class="w3">THE MUSK UNIVERSE.</span>
      </h1>
      <p class="lp-sub">Real-time intelligence on Tesla, SpaceX, xAI, and the full Elon Musk portfolio — built for investors who need signal, not noise.</p>
      <div class="lp-cta-row">
        <a href="<?php echo esc_url(home_url('/mission-briefing')); ?>" class="lp-cta"><span>Join Mission Briefing →</span></a>
        <a href="#latest" class="lp-cta-ghost">↓ Latest Intel</a>
      </div>
      <p class="lp-disclaimer">Not financial advice. For educational and informational purposes only.</p>
      <div class="c-br"></div>
    </div>

    <div class="lp-side">
      <div class="lp-panel-section">
        <div class="lp-panel-title">Market Intel <span class="mp-tsla-status mp-market-closed">MARKET CLOSED</span></div>
        <div class="lp-stat-row">
          <span class="lp-stat-label">TSLA</span>
          <div>
            <span class="lp-stat-val mp-tsla-price">$--</span>
            <span class="lp-stat-change mp-tsla-pct pos">▲ --%</span>
          </div>
        </div>
        <div class="lp-stat-row">
            <span class="lp-stat-label">SPCX</span>
              <div>
                <span class="lp-stat-val mp-spcx-price">$135.00</span>
                <span class="lp-stat-change mp-spcx-pct">PRE-IPO</span>
              </div>
        </div>
        <div class="lp-stat-row">
          <span class="lp-stat-label">XOVR ETF</span>
          <div>
            <span class="lp-stat-val mp-xovr-price">$--</span>
            <span class="lp-stat-change mp-xovr-pct">--%</span>
          </div>
        </div>
    </div>
      <div class="lp-panel-section" style="flex:1;border-bottom:none">
        <div class="lp-panel-title">Hot Topics</div>
        <?php
          $hot = get_posts(['numberposts' => 20, 'post_status' => 'publish']);
          if ($hot) {
            foreach ($hot as $post) {
              echo '<div class="lp-hot-item" onclick="window.location=\'' . esc_url(get_permalink($post)) . '\'">→ ' . esc_html(get_the_title($post)) . '</div>';
            }
          } else {
            foreach (['Cybercab Production Ramp','FSD: 7 New Cities','Optimus Mass Production','xAI + SpaceX Merger','Tesla Energy Record Q1'] as $item) {
              echo '<div class="lp-hot-item">→ ' . esc_html($item) . '</div>';
            }
          }
        ?>
      </div>
    </div>


    <!-- ══ MISSION FEED (post wall) — shown on mobile and desktop, cards
         match .archive-card used on Mission Feed / category archive pages ══ -->
    <div class="lp-social-feed archive-grid" id="latest">
      <div class="lp-sf-header">// MISSION FEED</div>
      <?php
        $sf_posts   = get_posts(['numberposts' => 10, 'post_status' => 'publish']);
        $sf_cat_map = [
          'spacex-ipo'  => ['tag-spacex', '#00c8ff'],
          'tesla-news'  => ['tag-tesla',  '#f5a623'],
          'xai-optimus' => ['tag-xai',    '#cc88ff'],
        ];
        $sf_num = 1;
        foreach ($sf_posts as $sf_post) {
          $sf_cats    = get_the_category($sf_post->ID);
          $sf_slug    = $sf_cats ? $sf_cats[0]->slug : 'tesla-news';
          $sf_name    = $sf_cats ? $sf_cats[0]->name : 'Tesla News';
          $sf_map     = isset($sf_cat_map[$sf_slug]) ? $sf_cat_map[$sf_slug] : ['tag-invest', '#00ff88'];
          $sf_ago     = human_time_diff(get_post_time('U', false, $sf_post), current_time('timestamp')) . ' ago';
          $sf_read    = mp_reading_time($sf_post);
          $sf_link    = get_permalink($sf_post);
          $sf_title   = get_the_title($sf_post);
          $sf_preview = wp_trim_words(strip_shortcodes(strip_tags($sf_post->post_content)), 22, '...');
      ?>
      <article class="lp-sf-card archive-card" data-url="<?php echo esc_url($sf_link); ?>" data-id="<?php echo (int) $sf_post->ID; ?>">
        <div class="archive-card-inner">
          <div class="archive-card-num"><?php echo str_pad($sf_num, 2, '0', STR_PAD_LEFT); ?></div>
          <div class="archive-card-tag <?php echo esc_attr($sf_map[0]); ?>">
            <span class="dot"></span>
            <?php echo esc_html($sf_name); ?>
          </div>
          <a class="archive-card-title" href="<?php echo esc_url($sf_link); ?>"><?php echo esc_html($sf_title); ?></a>
          <p class="archive-card-excerpt"><?php echo esc_html($sf_preview); ?></p>
          <div class="archive-card-meta">
            <span><?php echo esc_html($sf_ago); ?></span>
            <span><?php echo esc_html($sf_read); ?> min read</span>
            <button class="lp-sf-action lp-sf-share" type="button">
              <span class="lp-sf-ico">↗</span> Share
            </button>
            <button class="lp-sf-action lp-sf-save" type="button">
              <span class="lp-sf-ico">◇</span> <span class="lp-sf-save-label">Save</span>
            </button>
            <a class="archive-card-read" href="<?php echo esc_url($sf_link); ?>">Read →</a>
          </div>
        </div>
        <div class="archive-card-accent" style="--card-accent:<?php echo esc_attr($sf_map[1]); ?>"></div>
      </article>
      <?php
          $sf_num++;
        } ?>
    </div>

  </div>

  <?php get_template_part('template-parts/site-footer'); ?>

</div><!-- /landing-page -->

<!-- ══ SPLASH LAYERS ══ -->
<canvas id="starfield"></canvas>
<canvas id="smokeCanvas"></canvas>
<div id="scanlines"></div>
<div id="atmosphere"></div>
<div id="scene">
  <div id="groundGlow"></div><div id="heat"></div>
  <div class="ground"></div><div class="tower"></div>
  <div id="plume">
    <div class="plume-outer"></div><div class="plume-core"></div>
    <div class="shock-diamonds">
      <div class="diamond"></div><div class="diamond"></div><div class="diamond"></div>
    </div>
  </div>
  <div id="rocket">
    <div class="nose"></div><div class="body2"></div><div class="interstage"></div><div class="body1"></div>
    <div class="legs"><div class="leg"></div><div class="leg"></div><div class="leg"></div><div class="leg"></div><div class="leg"></div></div>
    <div class="engines"><div class="engine"></div><div class="engine"></div><div class="engine"></div><div class="engine"></div></div>
  </div>
</div>
<div id="hud">
  <div class="hud-top">
    <div class="hud-logo">MUSK<span>PULSE</span></div>
    <div class="hud-status">
      <div class="status-line active">● SYSTEMS NOMINAL</div>
      <div class="status-line active">● PROPELLANT LOADED</div>
      <div class="status-line warn">◆ T-MINUS SEQUENCE</div>
    </div>
  </div>
</div>
<div class="corner corner-tl"></div><div class="corner corner-tr"></div>
<div class="corner corner-bl"></div><div class="corner corner-br"></div>
<div class="telemetry">
  <div class="telem-row"><div class="telem-label">Altitude</div><div class="telem-val" id="alt">0 KM</div><div class="telem-bar"><div class="telem-fill" id="altBar" style="width:0%"></div></div></div>
  <div class="telem-row"><div class="telem-label">Velocity</div><div class="telem-val" id="vel">0 M/S</div><div class="telem-bar"><div class="telem-fill" id="velBar" style="width:0%"></div></div></div>
  <div class="telem-row"><div class="telem-label">Throttle</div><div class="telem-val" id="throttle">0%</div><div class="telem-bar"><div class="telem-fill" id="throttleBar" style="width:0%;background:#ff9900"></div></div></div>
  <div class="telem-row"><div class="telem-label">Stage</div><div class="telem-val" id="stage">PRE-LAUNCH</div></div>
</div>
<div class="mission-data">
  <div class="mdata-row"><div class="telem-label">Mission</div><div class="telem-val">MV-001</div></div>
  <div class="mdata-row"><div class="telem-label">Vehicle</div><div class="telem-val">FALCON 9</div></div>
  <div class="mdata-row"><div class="telem-label">Payload</div><div class="telem-val">MUSKPULSE</div></div>
  <div class="mdata-row"><div class="telem-label">Orbit</div><div class="telem-val">LEO 550KM</div></div>
</div>
<div id="countdown-wrap">
  <div id="countdown-number">5</div>
  <div id="countdown-label">T-MINUS</div>
  <div id="mission-name">MISSION: MUSKPULSE LAUNCH</div>
</div>
<div id="liftoff-text"><span>LIFTOFF</span></div>
<div id="flash"></div>

<!-- JS loaded by direct URL — bypasses WP script loader and cache plugins -->
<script src="<?php echo esc_url(get_stylesheet_directory_uri()); ?>/js/starfield.js"></script>
<script src="<?php echo esc_url(get_stylesheet_directory_uri()); ?>/js/smoke.js"></script>
<script src="<?php echo esc_url(get_stylesheet_directory_uri()); ?>/js/launch.js"></script>
<script src="<?php echo esc_url(get_stylesheet_directory_uri()); ?>/js/stock.js"></script>

<script>
(function() {
  var TARGET = new Date('2026-06-12T09:30:00-04:00');

  function daysRemaining() {
    var diff = Math.ceil((TARGET - new Date()) / 86400000);
    return diff > 0 ? diff : 0;
  }

  function syncCountdowns() {
    var days = daysRemaining();

    // Sidebar / landing page big countdown numbers
    var ipoNum  = document.getElementById('ipo-countdown');
    if (ipoNum)  ipoNum.textContent  = days;

    var ipoDays = document.getElementById('ipo-days');
    if (ipoDays) ipoDays.textContent = days;

    // "T-MINUS X DAYS" badge — find it by text content, wherever it lives
    document.querySelectorAll('*').forEach(function(el) {
      if (el.children.length === 0 && /T-MINUS\s+\d+\s+DAYS?/i.test(el.textContent)) {
        el.textContent = el.textContent.replace(
          /T-MINUS\s+\d+\s+DAYS?/i,
          'T-MINUS ' + days + (days === 1 ? ' DAY' : ' DAYS')
        );
      }
    });
  }

  setInterval(syncCountdowns, 1000);
  syncCountdowns();
})();
</script>

<!-- ══ NEWSLETTER POPUP ══════════════════════════════════════════ -->
<div id="mp-newsletter-popup" role="dialog" aria-modal="true" aria-label="Join Mission Briefing">
  <div class="mp-popup-panel">

    <button class="mp-popup-close" id="mp-popup-close" aria-label="Close">✕</button>

    <div class="mp-popup-corner mp-popup-tl"></div>
    <div class="mp-popup-corner mp-popup-br"></div>

    <div class="mp-popup-status">
      <span class="live-dot"></span>
      <span>SIGNAL INCOMING</span>
    </div>

    <div class="mp-popup-icon">◈</div>

    <h2 class="mp-popup-title">Don't Miss the<br><span>Next Move.</span></h2>

    <p class="mp-popup-sub">
      Join investors tracking Tesla, SpaceX & the full Musk universe.
      Weekly intelligence — no noise.
    </p>

    <form class="mp-popup-form"
          action="https://app.kit.com/forms/9428023/subscriptions"
          method="post">
      <input type="hidden" name="utf8" value="✓">
      <input
        class="mp-popup-input"
        type="email"
        name="email_address"
        placeholder="your@email.com"
        required
        autocomplete="email">
      <button type="submit" class="mp-popup-btn">
        <span>JOIN MISSION BRIEFING →</span>
      </button>
    </form>

    <p class="mp-popup-guarantee">No spam. Unsubscribe any time.</p>

  </div>
</div>

<style>
/* ── POPUP OVERLAY ─────────────────────────────────────────────── */
#mp-newsletter-popup {
  display:         none;
  position:        fixed;
  inset:           0;
  z-index:         9000;
  align-items:     center;
  justify-content: center;
  padding:         20px;
  background:      rgba(2, 5, 8, 0.88);
  backdrop-filter: blur(6px);
  opacity:         0;
  transition:      opacity 0.3s ease;
}
#mp-newsletter-popup.open { opacity: 1; }
.mp-popup-panel {
  position:   relative;
  width:      100%;
  max-width:  480px;
  background: var(--surface);
  border:     1px solid var(--border);
  padding:    48px 40px 40px;
  text-align: center;
  transform:  translateY(24px);
  transition: transform 0.35s cubic-bezier(0.22, 1, 0.36, 1);
}
#mp-newsletter-popup.open .mp-popup-panel { transform: translateY(0); }
.mp-popup-panel::before {
  content:    '';
  position:   absolute;
  top: 0; left: 0; right: 0;
  height:     2px;
  background: linear-gradient(90deg, transparent, var(--accent) 30%, var(--red) 70%, transparent);
}
.mp-popup-panel::after {
  content:          '';
  position:         absolute;
  inset:            0;
  pointer-events:   none;
  background-image: linear-gradient(var(--grid-line) 1px, transparent 1px),
                    linear-gradient(90deg, var(--grid-line) 1px, transparent 1px);
  background-size:  30px 30px;
  z-index:          0;
}
.mp-popup-close {
  position:    absolute;
  top:         14px;
  right:       14px;
  background:  transparent;
  border:      1px solid var(--border);
  color:       var(--muted);
  font-size:   12px;
  width:       28px;
  height:      28px;
  cursor:      pointer;
  display:     flex;
  align-items: center;
  justify-content: center;
  transition:  border-color 0.15s, color 0.15s;
  z-index:     2;
  line-height: 1;
}
.mp-popup-close:hover { border-color: var(--red); color: var(--red); }
.mp-popup-corner { position: absolute; width: 12px; height: 12px; z-index: 2; }
.mp-popup-tl { top: 14px; left: 14px; border-top: 1px solid var(--accent); border-left: 1px solid var(--accent); }
.mp-popup-br { bottom: 14px; right: 14px; border-bottom: 1px solid var(--accent); border-right: 1px solid var(--accent); }
.mp-popup-status {
  display: flex; align-items: center; justify-content: center; gap: 8px;
  font-family: 'Share Tech Mono', monospace; font-size: 9px;
  letter-spacing: 3px; text-transform: uppercase; color: var(--accent);
  margin-bottom: 20px; position: relative; z-index: 2;
}
.mp-popup-icon {
  font-size: 36px; color: var(--accent);
  text-shadow: 0 0 24px rgba(0,200,255,0.4);
  margin-bottom: 16px; position: relative; z-index: 2;
}
.mp-popup-title {
  font-family: 'Orbitron', monospace; font-weight: 900;
  font-size: clamp(22px, 5vw, 30px); text-transform: uppercase;
  color: #fff; line-height: 1.15; letter-spacing: -0.5px;
  margin-bottom: 12px; position: relative; z-index: 2;
}
.mp-popup-title span { color: var(--accent); text-shadow: 0 0 20px rgba(0,200,255,0.35); }
.mp-popup-sub {
  font-size: 13px; color: var(--text-dim); line-height: 1.65;
  font-weight: 300; margin-bottom: 28px; position: relative; z-index: 2;
}
.mp-popup-form { display: flex; flex-direction: column; gap: 10px; position: relative; z-index: 2; }
.mp-popup-input {
  width: 100%; background: var(--bg); border: 1px solid var(--border);
  color: var(--text); font-family: 'Share Tech Mono', monospace;
  font-size: 12px; letter-spacing: 1px; padding: 12px 16px; outline: none;
  transition: border-color 0.2s;
}
.mp-popup-input::placeholder { color: var(--muted); }
.mp-popup-input:focus { border-color: var(--accent); }
.mp-popup-btn {
  width: 100%; background: var(--accent); border: none; color: var(--bg);
  font-family: 'Orbitron', monospace; font-size: 10px; font-weight: 700;
  letter-spacing: 3px; text-transform: uppercase; padding: 13px 20px;
  cursor: pointer; clip-path: polygon(8px 0%,100% 0%,calc(100% - 8px) 100%,0% 100%);
  transition: background 0.2s;
}
.mp-popup-btn:hover { background: #fff; }
.mp-popup-guarantee {
  font-family: 'Share Tech Mono', monospace; font-size: 9px;
  letter-spacing: 1px; color: var(--muted); text-transform: uppercase;
  margin-top: 14px; position: relative; z-index: 2;
}
@media (max-width: 600px) {
  .mp-popup-panel { padding: 40px 20px 32px; }
}
</style>

<script>
/* ── LANDING PAGE POPUP — 10 second timer only ────────────────── */
(function() {

  /* 1 — Already subscribed? */
  function hasCookie(name) {
    return document.cookie.split(';').some(function(c) {
      return c.trim().indexOf(name + '=') === 0;
    });
  }
  if (hasCookie('mp_subscribed')) return;

  /* 2 — Already shown this session? (shared key with article popup) */
  if (sessionStorage.getItem('mp_popup_seen')) return;

  var popup    = document.getElementById('mp-newsletter-popup');
  var closeBtn = document.getElementById('mp-popup-close');
  if (!popup) return;

  /* 3 — Trigger: 10 seconds on page only (no scroll trigger on landing page) */
  function openPopup() {
    sessionStorage.setItem('mp_popup_seen', '1');
    popup.style.display = 'flex';
    requestAnimationFrame(function() {
      requestAnimationFrame(function() { popup.classList.add('open'); });
    });
    document.body.style.overflow = 'hidden';
  }

  function closePopup() {
    popup.classList.remove('open');
    setTimeout(function() {
      popup.style.display = 'none';
      document.body.style.overflow = '';
    }, 300);
  }

  setTimeout(openPopup, 10000);

  closeBtn.addEventListener('click', closePopup);
  popup.addEventListener('click', function(e) {
    if (e.target === popup) closePopup();
  });
  document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape' && popup.classList.contains('open')) closePopup();
  });

})();
</script>

<?php get_footer(); ?>
