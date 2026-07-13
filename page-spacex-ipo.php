<?php
/**
 * Template Name: SpaceX IPO Landing
 * Template Post Type: page
 */
?>
<?php get_header(); ?>

<?php get_template_part('template-parts/site-nav'); ?>
<?php get_template_part('template-parts/ticker'); ?>

<div class="ipo-outer">

  <!-- HERO -->
  <header class="ipo-hero">
    <div class="ipo-status">
      <span class="live-dot"></span>
      <span>LISTING DAY — JUNE 12, 2026</span>
    </div>
    <h1 class="ipo-title">
      SpaceX Is Now<br>
      <span class="ipo-accent">$SPCX on Nasdaq.</span>
    </h1>
    <p class="ipo-sub">
      The largest IPO in history priced at $1.75T. Here's what every Tesla and SpaceX investor needs to know — right now.
    </p>
  </header>

  <!-- STATS GRID -->
  <div class="ipo-stats">
    <div class="ipo-stat">
      <div class="ipo-stat-label">Ticker</div>
      <div class="ipo-stat-val">SPCX</div>
      <div class="ipo-stat-sub">Nasdaq</div>
    </div>
    <div class="ipo-stat">
      <div class="ipo-stat-label">Valuation</div>
      <div class="ipo-stat-val">$1.75T</div>
      <div class="ipo-stat-sub">At pricing</div>
    </div>
    <div class="ipo-stat">
      <div class="ipo-stat-label">Capital Raised</div>
      <div class="ipo-stat-val">~$75B</div>
      <div class="ipo-stat-sub">Primary offering</div>
    </div>
    <div class="ipo-stat">
      <div class="ipo-stat-label">Share Structure</div>
      <div class="ipo-stat-val">5-for-1</div>
      <div class="ipo-stat-sub">Split completed May 22</div>
    </div>
    <div class="ipo-stat">
      <div class="ipo-stat-label">Pricing Date</div>
      <div class="ipo-stat-val">Jun 11</div>
      <div class="ipo-stat-sub">Confirmed</div>
    </div>
    <div class="ipo-stat">
      <div class="ipo-stat-label">First Trade</div>
      <div class="ipo-stat-val">Jun 12</div>
      <div class="ipo-stat-sub">9:30am ET</div>
    </div>
  </div>

  <!-- WHAT THIS MEANS -->
  <section class="ipo-section">
    <div class="ipo-section-label">// WHAT THIS MEANS FOR INVESTORS</div>
    <div class="ipo-section-body">
      <p>SpaceX's public debut is the largest IPO in market history, eclipsing every prior tech listing by a wide margin. The $1.75T valuation places SpaceX among the most valuable companies on the planet from day one of trading.</p>
      <p>The 5-for-1 stock split completed in late May brought the per-share price down to a more retail-accessible range, signalling SpaceX's intent to build a broad base of individual investors rather than relying solely on institutional capital.</p>
      <p>With xAI now merged into SpaceX as of February 2026, $SPCX represents exposure not just to launch and satellite operations, but to Musk's AI ambitions as well — a combination no other public company offers.</p>
    </div>
  </section>

  <!-- HOW TO TRADE -->
  <section class="ipo-section">
    <div class="ipo-section-label">// HOW TO TRADE $SPCX</div>
    <div class="ipo-section-body">
      <p>$SPCX will be available on Nasdaq from market open on June 12. Most major brokerages will support the ticker from day one of trading. As with any IPO, expect elevated volatility in the first sessions as price discovery occurs.</p>
    </div>
    <div class="ipo-broker-grid">
      <div class="ipo-broker">Robinhood</div>
      <div class="ipo-broker">Webull</div>
      <div class="ipo-broker">Public.com</div>
      <div class="ipo-broker">eToro</div>
    </div>
  </section>

  <!-- TESLA REFERRAL CROSS-SELL -->
  <div class="affiliate-cta">
    <div class="cta-text"><p>Already invested in the Musk portfolio? Order a Tesla and support MuskPulse using our referral link — you may be eligible for exclusive rewards.</p></div>
    <a href="https://ts.la/subhamaya21671" class="cta-btn" target="_blank" rel="noopener"><span>Order Tesla →</span></a>
  </div>

  <!-- KIT SIGNUP -->
  <section class="ipo-section ipo-briefing">
    <div class="ipo-section-label">// STAY AHEAD OF THE NEXT MOVE</div>
    <h2 class="ipo-briefing-title">Get post-IPO intel as it happens.</h2>
    <div class="ipo-kit-form">
      <script src="https://f.convertkit.com/ckjs/ck.5.js"></script>
      <form action="https://app.kit.com/forms/9428023/subscriptions" class="seva-form formkit-form" method="post" data-sv-form="9428023" data-uid="a1791d1632" data-format="inline" data-version="5" min-width="400 500 600 700 800">
        <div data-style="minimal">
          <ul class="formkit-alert formkit-alert-error" data-element="errors" data-group="alert"></ul>
          <div data-element="fields" data-stacked="false" class="seva-fields formkit-fields">
            <div class="formkit-field">
              <input class="formkit-input" name="email_address" aria-label="Email Address" placeholder="your@email.com" required type="email">
            </div>
            <button data-element="submit" class="formkit-submit">
              <div class="formkit-spinner"><div></div><div></div><div></div></div>
              <span>Join the Briefing</span>
            </button>
          </div>
        </div>
      </form>
    </div>
  </section>

  <!-- LATEST SPACEX IPO INTEL -->
  <section class="ipo-section">
    <div class="ipo-section-label">// LATEST SPACEX IPO INTEL</div>
    <div class="archive-grid">
      <?php
        $ipo_posts = new WP_Query([
          'category_name'  => 'spacex-ipo',
          'posts_per_page' => 6,
          'orderby'        => 'date',
          'order'          => 'DESC',
        ]);
        $ipo_num = 1;
        if ($ipo_posts->have_posts()) :
          while ($ipo_posts->have_posts()) : $ipo_posts->the_post();
            $ago       = human_time_diff(get_the_time('U'), current_time('timestamp')) . ' ago';
            $excerpt   = wp_trim_words(get_the_excerpt(), 22, '...');
            $read      = mp_reading_time();
            $permalink = get_permalink();
      ?>
        <article class="archive-card lp-sf-card" data-url="<?php echo esc_url($permalink); ?>" data-id="<?php the_ID(); ?>">
          <div class="archive-card-inner">
            <div class="archive-card-num"><?php echo str_pad($ipo_num, 2, '0', STR_PAD_LEFT); ?></div>
            <div class="archive-card-tag tag-spacex">
              <span class="dot"></span>
              SpaceX IPO
            </div>
            <a class="archive-card-title" href="<?php echo esc_url($permalink); ?>"><?php the_title(); ?></a>
            <p class="archive-card-excerpt"><?php echo esc_html($excerpt); ?></p>
            <div class="archive-card-meta">
              <span><?php echo esc_html($ago); ?></span>
              <span><?php echo esc_html($read); ?> min read</span>
              <button class="lp-sf-action lp-sf-share" type="button">
                <span class="lp-sf-ico">↗</span> Share
              </button>
              <button class="lp-sf-action lp-sf-save" type="button">
                <span class="lp-sf-ico">◇</span> <span class="lp-sf-save-label">Save</span>
              </button>
              <a class="archive-card-read" href="<?php echo esc_url($permalink); ?>">Read →</a>
            </div>
          </div>
          <div class="archive-card-accent" style="--card-accent:#00c8ff"></div>
        </article>
      <?php
            $ipo_num++;
          endwhile;
          wp_reset_postdata();
        else:
      ?>
        <p style="color:var(--muted);font-size:13px;">More coverage coming as the story develops.</p>
      <?php endif; ?>
    </div>
    <a href="<?php echo esc_url(home_url('/category/spacex-ipo')); ?>" class="ipo-archive-link">View all SpaceX IPO coverage →</a>
  </section>

</div>

<style>
.ipo-outer { max-width: 960px; margin: 0 auto; padding: 60px 24px 80px; position: relative; z-index: 10; }

.ipo-hero { text-align: center; margin-bottom: 48px; }
.ipo-status {
  display: inline-flex; align-items: center; gap: 8px;
  font-family: 'Share Tech Mono', monospace; font-size: 10px;
  letter-spacing: 3px; text-transform: uppercase; color: var(--green);
  border: 1px solid rgba(0,255,136,.3); padding: 6px 16px; margin-bottom: 24px;
}
.ipo-title {
  font-family: 'Orbitron', monospace; font-weight: 900;
  font-size: clamp(32px, 6vw, 64px); text-transform: uppercase;
  color: #fff; line-height: 1.1; letter-spacing: -1px; margin-bottom: 20px;
}
.ipo-accent { color: var(--accent); text-shadow: 0 0 40px rgba(0,200,255,.35); }
.ipo-sub { font-size: 16px; color: var(--text-dim); font-weight: 300; line-height: 1.7; max-width: 600px; margin: 0 auto; }

.ipo-stats {
  display: grid; grid-template-columns: repeat(3, 1fr); gap: 1px;
  background: var(--border); margin-bottom: 48px;
}
.ipo-stat { background: var(--surface); padding: 24px 20px; text-align: center; }
.ipo-stat-label { font-family:'Share Tech Mono',monospace; font-size:9px; letter-spacing:2px; text-transform:uppercase; color:var(--muted); margin-bottom:8px; }
.ipo-stat-val { font-family:'Orbitron',monospace; font-weight:900; font-size:28px; color:var(--accent); text-shadow:0 0 20px rgba(0,200,255,.3); margin-bottom:4px; }
.ipo-stat-sub { font-size: 11px; color: var(--muted); font-weight: 300; }

.ipo-section { margin-bottom: 40px; }
.ipo-section-label { font-family:'Orbitron',monospace; font-size:10px; letter-spacing:3px; text-transform:uppercase; color:var(--accent); margin-bottom:16px; border-bottom:1px solid var(--border); padding-bottom:12px; }
.ipo-section-body p { font-size:15px; color:var(--text-dim); line-height:1.8; font-weight:300; margin-bottom:16px; }

.ipo-broker-grid { display:flex; flex-wrap:wrap; gap:10px; margin-top:16px; }
.ipo-broker { font-family:'Share Tech Mono',monospace; font-size:11px; letter-spacing:2px; text-transform:uppercase; color:var(--muted); border:1px solid var(--border); padding:10px 18px; }

.ipo-briefing { text-align:center; }
.ipo-briefing-title { font-family:'Orbitron',monospace; font-weight:700; font-size:22px; color:#fff; margin-bottom:24px; text-transform:uppercase; }
.ipo-kit-form { max-width: 480px; margin: 0 auto; }
.ipo-kit-form .formkit-form { background:transparent !important; border:none !important; max-width:100% !important; }
.ipo-kit-form .formkit-input { background:var(--surface2) !important; border:1px solid var(--border) !important; color:var(--text) !important; font-family:'Share Tech Mono',monospace !important; padding:12px 14px !important; border-radius:0 !important; }
.ipo-kit-form .formkit-field { margin-bottom: 14px !important; }
.ipo-kit-form .formkit-submit { background:var(--accent) !important; color:var(--bg) !important; font-family:'Orbitron',monospace !important; font-size:10px !important; letter-spacing:3px !important; text-transform:uppercase !important; padding:12px 24px !important; border-radius:0 !important; clip-path:polygon(6px 0%,100% 0%,calc(100% - 6px) 100%,0% 100%) !important; }

.ipo-outer .archive-grid { margin-bottom: 16px; }
.ipo-archive-link { font-family:'Share Tech Mono',monospace; font-size:11px; letter-spacing:2px; text-transform:uppercase; color:var(--accent); text-decoration:none; }

@media (max-width: 700px) {
  .ipo-stats { grid-template-columns: repeat(2,1fr); }
}
</style>

<?php get_footer(); ?>