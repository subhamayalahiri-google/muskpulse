<?php get_header(); ?>

<?php get_template_part('template-parts/site-nav'); ?>

<?php get_template_part('template-parts/ticker'); ?>

<div id="reading-bar"></div>

<div class="article-outer">

  <!-- ── MAIN ARTICLE COLUMN ── -->
  <article class="article-main">

    <header class="article-header">

      <div class="article-breadcrumb">
        <a href="<?php echo esc_url(home_url('/')); ?>">MuskPulse</a>
        <span class="sep">›</span>
        <?php
          $categories = get_the_category();
          if ($categories) {
            echo '<a href="' . esc_url(get_category_link($categories[0]->term_id)) . '">'
              . esc_html($categories[0]->name) . '</a>';
          }
        ?>
        <span class="sep">›</span>
        <span><?php the_title(); ?></span>
      </div>

      <?php
        $cat_name    = isset($categories[0]) ? $categories[0]->name : 'News';
        $cat_slug    = isset($categories[0]) ? $categories[0]->slug : 'news';
        $badge_map   = ['spacex-ipo' => 'cat-spacex', 'tesla-news' => 'cat-tesla', 'xai-optimus' => 'cat-xai'];
        $badge_class = isset($badge_map[$cat_slug]) ? $badge_map[$cat_slug] : 'cat-invest';
      ?>
      <div class="article-category">
        <div class="cat-badge <?php echo esc_attr($badge_class); ?>">
          <span class="dot"></span>
          <?php echo esc_html($cat_name); ?>
        </div>
      </div>

      <h1 class="article-title"><?php the_title(); ?></h1>

      <?php
        $manual_excerpt = get_post_field('post_excerpt', get_the_ID());
        if ($manual_excerpt) :
      ?>
        <p class="article-deck"><?php echo wp_strip_all_tags($manual_excerpt); ?></p>
      <?php endif; ?>

      <div class="article-meta">
        <div class="meta-item accent">MuskPulse Intel</div>
        <div class="meta-item"><?php echo get_the_date('M j, Y'); ?></div>
        <div class="meta-item read-time"><?php echo mp_reading_time(); ?> min read</div>
        <div class="meta-item"><?php echo esc_html($cat_name); ?></div>
      </div>

    </header>

    <div class="article-body">
      <?php the_content(); ?>
    </div>

    <div class="article-tags">
      <?php
        $tags = get_the_tags();
        if ($tags) {
          foreach ($tags as $tag) {
            echo '<a href="' . esc_url(get_tag_link($tag->term_id)) . '" class="article-tag">'
              . esc_html($tag->name) . '</a>';
          }
        }
      ?>
    </div>

  </article>

  <!-- ── SIDEBAR ── -->
  <aside class="article-sidebar">

    <!-- MARKET INTEL -->
    <div class="sb-section">
      <div class="sb-title">Market Intel <span class="mp-tsla-status mp-market-closed" style="color:#4a6070;border-color:rgba(74,96,112,.3);background:rgba(74,96,112,.05)">MARKET CLOSED</span></div>
      <div class="sb-stat-row">
        <span class="sb-stat-label">TSLA</span>
        <div>
          <span class="sb-stat-val mp-tsla-price">$--</span>
          <span class="sb-stat-change mp-tsla-pct pos">▲ --%</span>
        </div>
      </div>
      <div class="sb-stat-row">
        <span class="sb-stat-label">SPCX</span>
        <div>
          <span class="sb-stat-val mp-spcx-price">$--</span>
          <span class="sb-stat-change mp-spcx-pct">-- %</span>
        </div>
      </div>
        <div class="sb-stat-row">
          <span class="sb-stat-label">XOVR ETF</span>
          <div>
            <span class="sb-stat-val mp-xovr-price">$--</span>
            <span class="sb-stat-change mp-xovr-pct">--%</span>
          </div>
        </div>
    </div>

    <!-- MISSION BRIEFING — Kit embed -->
    <div class="sb-section">
      <div class="sb-title">Mission Briefing</div>
      <div class="sb-newsletter">
        <script src="https://f.convertkit.com/ckjs/ck.5.js"></script>
        <form action="https://app.kit.com/forms/9428023/subscriptions" class="seva-form formkit-form" method="post" data-sv-form="9428023" data-uid="a1791d1632" data-format="inline" data-version="5" min-width="400 500 600 700 800">
          <div data-style="minimal">
            <ul class="formkit-alert formkit-alert-error" data-element="errors" data-group="alert"></ul>
            <div data-element="fields" data-stacked="true" class="seva-fields formkit-fields">
              <div class="formkit-field">
                <input class="formkit-input sb-nl-input" name="email_address" aria-label="Email Address" placeholder="your@email.com" required type="email">
              </div>
              <button data-element="submit" class="formkit-submit sb-nl-btn">
                <div class="formkit-spinner"><div></div><div></div><div></div></div>
                <span>Join the Briefing</span>
              </button>
            </div>
            <div class="formkit-guarantee" data-element="guarantee" style="font-family:'Share Tech Mono',monospace;font-size:9px;letter-spacing:1px;color:#4a6070;text-align:center;margin-top:10px;text-transform:uppercase;">
              <p>We won't send you spam. Unsubscribe any time.</p>
            </div>
          </div>
        </form>
      </div>
    </div>

    <!-- RELATED INTEL -->
    <div class="sb-section" style="flex:1">
      <div class="sb-title">Related Intel</div>
      <?php
        $related = new WP_Query([
          'category__in'   => wp_get_post_categories(get_the_ID()),
          'post__not_in'   => [get_the_ID()],
          'posts_per_page' => 4,
          'orderby'        => 'date',
          'order'          => 'DESC',
        ]);
        if ($related->have_posts()) :
          while ($related->have_posts()) : $related->the_post();
            $rel_cats = get_the_category();
            $rel_cat  = isset($rel_cats[0]) ? $rel_cats[0]->name : '';
      ?>
        <a class="related-item" href="<?php the_permalink(); ?>">
          <div class="related-cat"><?php echo esc_html($rel_cat); ?></div>
          <div class="related-title"><?php the_title(); ?></div>
          <div class="related-time"><?php echo human_time_diff(get_the_time('U'), current_time('timestamp')); ?> ago</div>
        </a>
      <?php
          endwhile;
          wp_reset_postdata();
        endif;
      ?>
    </div>

  </aside>
</div><!-- /article-outer -->

<script>
// Reading progress bar
(function() {
  var bar = document.getElementById('reading-bar');
  if (!bar) return;
  window.addEventListener('scroll', function() {
    var total = document.body.scrollHeight - window.innerHeight;
    bar.style.width = (total > 0 ? (window.scrollY / total) * 100 : 0) + '%';
  });
})();

// IPO countdown
(function() {
  var el = document.getElementById('ipo-days');
  if (!el) return;
  var diff = Math.ceil((new Date('2026-06-12T09:30:00-04:00') - new Date()) / 86400000);
  el.textContent = diff > 0 ? diff : '—';
})();

// Scroll fade-in for article body elements
(function() {
  var obs = new IntersectionObserver(function(entries) {
    entries.forEach(function(e) {
      if (e.isIntersecting) {
        e.target.style.opacity = '1';
        e.target.style.transform = 'translateY(0)';
      }
    });
  }, { threshold: 0.1 });

  document.querySelectorAll('.article-body p, .insight-box, .risk-box, .stat-grid, .pull-quote, .affiliate-cta').forEach(function(el) {
    el.style.opacity   = '0';
    el.style.transform = 'translateY(16px)';
    el.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
    obs.observe(el);
  });
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

/* ── PANEL ─────────────────────────────────────────────────────── */
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

/* Top accent line */
.mp-popup-panel::before {
  content:    '';
  position:   absolute;
  top: 0; left: 0; right: 0;
  height:     2px;
  background: linear-gradient(90deg, transparent, var(--accent) 30%, var(--red) 70%, transparent);
}

/* Grid background */
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

/* ── CLOSE BUTTON ──────────────────────────────────────────────── */
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

/* ── CORNER DECORATIONS ────────────────────────────────────────── */
.mp-popup-corner {
  position: absolute;
  width: 12px; height: 12px;
  z-index: 2;
}
.mp-popup-tl { top: 14px; left: 14px; border-top: 1px solid var(--accent); border-left: 1px solid var(--accent); }
.mp-popup-br { bottom: 14px; right: 14px; border-bottom: 1px solid var(--accent); border-right: 1px solid var(--accent); }

/* ── CONTENT ───────────────────────────────────────────────────── */
.mp-popup-status {
  display:         flex;
  align-items:     center;
  justify-content: center;
  gap:             8px;
  font-family:     'Share Tech Mono', monospace;
  font-size:       9px;
  letter-spacing:  3px;
  text-transform:  uppercase;
  color:           var(--accent);
  margin-bottom:   20px;
  position:        relative;
  z-index:         2;
}

.mp-popup-icon {
  font-size:    36px;
  color:        var(--accent);
  text-shadow:  0 0 24px rgba(0, 200, 255, 0.4);
  margin-bottom: 16px;
  position:     relative;
  z-index:      2;
}

.mp-popup-title {
  font-family:   'Orbitron', monospace;
  font-weight:   900;
  font-size:     clamp(22px, 5vw, 30px);
  text-transform: uppercase;
  color:         #fff;
  line-height:   1.15;
  letter-spacing: -0.5px;
  margin-bottom: 12px;
  position:      relative;
  z-index:       2;
}
.mp-popup-title span { color: var(--accent); text-shadow: 0 0 20px rgba(0, 200, 255, 0.35); }

.mp-popup-sub {
  font-size:    13px;
  color:        var(--text-dim);
  line-height:  1.65;
  font-weight:  300;
  margin-bottom: 28px;
  position:     relative;
  z-index:      2;
}

/* ── FORM ──────────────────────────────────────────────────────── */
.mp-popup-form {
  display:       flex;
  flex-direction: column;
  gap:           10px;
  position:      relative;
  z-index:       2;
}

.mp-popup-input {
  width:          100%;
  background:     var(--bg);
  border:         1px solid var(--border);
  color:          var(--text);
  font-family:    'Share Tech Mono', monospace;
  font-size:      12px;
  letter-spacing: 1px;
  padding:        12px 16px;
  outline:        none;
  transition:     border-color 0.2s;
}
.mp-popup-input::placeholder { color: var(--muted); }
.mp-popup-input:focus        { border-color: var(--accent); }

.mp-popup-btn {
  width:          100%;
  background:     var(--accent);
  border:         none;
  color:          var(--bg);
  font-family:    'Orbitron', monospace;
  font-size:      10px;
  font-weight:    700;
  letter-spacing: 3px;
  text-transform: uppercase;
  padding:        13px 20px;
  cursor:         pointer;
  clip-path:      polygon(8px 0%, 100% 0%, calc(100% - 8px) 100%, 0% 100%);
  transition:     background 0.2s, color 0.2s;
}
.mp-popup-btn:hover { background: #fff; }

.mp-popup-guarantee {
  font-family:    'Share Tech Mono', monospace;
  font-size:      9px;
  letter-spacing: 1px;
  color:          var(--muted);
  text-transform: uppercase;
  margin-top:     14px;
  position:       relative;
  z-index:        2;
}

/* ── MOBILE ────────────────────────────────────────────────────── */
@media (max-width: 600px) {
  .mp-popup-panel { padding: 40px 20px 32px; }
}
</style>

<script>
/* ── NEWSLETTER POPUP ─────────────────────────────────────────── */
(function() {

  /* 1 — Already subscribed? (cookie set by thank-you page) */
  function hasCookie(name) {
    return document.cookie.split(';').some(function(c) {
      return c.trim().indexOf(name + '=') === 0;
    });
  }
  if (hasCookie('mp_subscribed')) return;

  /* 2 — Already shown this session? */
  if (sessionStorage.getItem('mp_popup_seen')) return;

  var popup   = document.getElementById('mp-newsletter-popup');
  var closeBtn = document.getElementById('mp-popup-close');
  if (!popup) return;

/* 3 — Trigger: 10 seconds OR 50% page scroll — whichever comes first */
  var triggered = false;
  var timer     = null;

  function trigger() {
    if (triggered) return;
    triggered = true;
    clearTimeout(timer);
    window.removeEventListener('scroll', checkScroll);
    openPopup();
  }

  // Timer trigger — 10 seconds
  timer = setTimeout(trigger, 10000);

  // Scroll trigger — 50% of total page height
  function checkScroll() {
    var total = document.documentElement.scrollHeight - window.innerHeight;
    if (total > 0 && (window.scrollY / total) >= 0.5) {
      trigger();
    }
  }
  window.addEventListener('scroll', checkScroll, { passive: true });

  /* ── Open / close ── */
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

  closeBtn.addEventListener('click', closePopup);

  /* Close on overlay click (not panel click) */
  popup.addEventListener('click', function(e) {
    if (e.target === popup) closePopup();
  });

  /* Close on Escape */
  document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape' && popup.classList.contains('open')) closePopup();
  });

})();
</script>

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


<?php get_footer(); ?>
