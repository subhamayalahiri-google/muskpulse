<?php
/**
 * Template Name: Thank You
 * Template Post Type: page
 *
 * Shown after Kit form submission redirect.
 * Assign in WordPress: Pages → Thank You → Page Attributes → Template → Thank You
 */
?>
<?php get_header(); ?>

<?php get_template_part('template-parts/site-nav'); ?>

<?php get_template_part('template-parts/ticker'); ?>

<div class="thankyou-outer">

  <div class="thankyou-wrap">

    <!-- Corner decorations -->
    <div class="ty-corner ty-corner-tl"></div>
    <div class="ty-corner ty-corner-br"></div>

    <!-- Status indicator -->
    <div class="ty-status">
      <span class="live-dot"></span>
      <span>TRANSMISSION CONFIRMED</span>
    </div>

    <!-- Icon -->
    <div class="ty-icon">✓</div>

    <!-- Headline -->
    <h1 class="ty-title">
      You're in,<br>
      <span class="ty-accent">Investor.</span>
    </h1>

    <p class="ty-sub">
      Check your inbox to confirm your subscription — then your first Mission Briefing will be on its way.
    </p>

    <!-- What to expect -->
    <div class="ty-expect">
      <div class="ty-expect-label">// WHAT HAPPENS NEXT</div>
      <div class="ty-expect-items">
        <div class="ty-expect-item">
          <span class="ty-step">01</span>
          <span>Confirm your email — check your inbox now</span>
        </div>
        <div class="ty-expect-item">
          <span class="ty-step">02</span>
          <span>Receive your welcome brief with the SpaceX IPO timeline</span>
        </div>
        <div class="ty-expect-item">
          <span class="ty-step">03</span>
          <span>Get weekly intel every time something material moves</span>
        </div>
      </div>
    </div>

    <!-- CTA -->
    <div class="ty-actions">
      <a href="<?php echo esc_url(home_url('/mission-feed')); ?>" class="ty-btn-primary">
        <span>Read Latest Intel →</span>
      </a>
      <a href="<?php echo esc_url(home_url('/')); ?>" class="ty-btn-ghost">← Back to MuskPulse</a>
    </div>

  </div>

</div>

<style>
.thankyou-outer {
  min-height: calc(100vh - 120px);
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 60px 24px;
  position: relative;
  z-index: 10;
}

.thankyou-wrap {
  max-width: 640px;
  width: 100%;
  text-align: center;
  position: relative;
  padding: 64px 56px;
  border: 1px solid var(--border);
  background: var(--surface);
}

/* Top accent line */
.thankyou-wrap::before {
  content: '';
  position: absolute; top: 0; left: 0; right: 0; height: 2px;
  background: linear-gradient(90deg, transparent, var(--accent) 30%, var(--green) 70%, transparent);
}

/* Corner decorations */
.ty-corner {
  position: absolute;
  width: 16px; height: 16px;
}
.ty-corner-tl {
  top: 16px; left: 16px;
  border-top: 1px solid var(--accent);
  border-left: 1px solid var(--accent);
}
.ty-corner-br {
  bottom: 16px; right: 16px;
  border-bottom: 1px solid var(--accent);
  border-right: 1px solid var(--accent);
}

/* Status */
.ty-status {
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 8px;
  font-family: 'Share Tech Mono', monospace;
  font-size: 9px;
  letter-spacing: 3px;
  text-transform: uppercase;
  color: var(--green);
  margin-bottom: 32px;
}

/* Check icon */
.ty-icon {
  font-size: 48px;
  color: var(--green);
  text-shadow: 0 0 30px rgba(0,255,136,.4);
  margin-bottom: 24px;
  font-weight: 300;
}

/* Title */
.ty-title {
  font-family: 'Orbitron', monospace;
  font-weight: 900;
  font-size: clamp(32px, 5vw, 52px);
  text-transform: uppercase;
  color: #fff;
  line-height: 1.1;
  margin-bottom: 20px;
  letter-spacing: -0.5px;
}
.ty-accent {
  color: var(--accent);
  text-shadow: 0 0 30px rgba(0,200,255,.3);
}

.ty-sub {
  font-size: 15px;
  color: var(--text-dim);
  line-height: 1.75;
  font-weight: 300;
  margin-bottom: 40px;
  max-width: 480px;
  margin-left: auto;
  margin-right: auto;
}

/* What's next */
.ty-expect {
  background: var(--bg);
  border: 1px solid var(--border);
  padding: 24px 28px;
  margin-bottom: 40px;
  text-align: left;
}
.ty-expect-label {
  font-family: 'Orbitron', monospace;
  font-size: 9px;
  letter-spacing: 3px;
  text-transform: uppercase;
  color: var(--accent);
  margin-bottom: 16px;
}
.ty-expect-items {
  display: flex;
  flex-direction: column;
  gap: 14px;
}
.ty-expect-item {
  display: flex;
  align-items: center;
  gap: 16px;
  font-size: 13px;
  color: var(--text-dim);
  font-weight: 300;
  line-height: 1.5;
}
.ty-step {
  font-family: 'Orbitron', monospace;
  font-size: 11px;
  font-weight: 700;
  color: var(--accent);
  flex-shrink: 0;
  min-width: 24px;
}

/* Actions */
.ty-actions {
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 16px;
}
.ty-btn-primary {
  font-family: 'Orbitron', monospace;
  font-size: 10px;
  letter-spacing: 3px;
  text-transform: uppercase;
  padding: 14px 36px;
  background: var(--accent);
  color: var(--bg);
  text-decoration: none;
  clip-path: polygon(8px 0%,100% 0%,calc(100% - 8px) 100%,0% 100%);
  transition: background .2s;
  display: inline-block;
  font-weight: 700;
}
.ty-btn-primary:hover { background: #fff; color: var(--bg); }
.ty-btn-ghost {
  font-family: 'Share Tech Mono', monospace;
  font-size: 11px;
  letter-spacing: 2px;
  color: var(--muted);
  text-decoration: none;
  transition: color .2s;
}
.ty-btn-ghost:hover { color: var(--accent); }

@media (max-width: 600px) {
  .thankyou-wrap { padding: 40px 24px; }
}
</style>

<script>
/* Set subscribed cookie on thank-you page — suppresses newsletter popup for 1 year */
(function() {
  document.cookie = 'mp_subscribed=1; path=/; max-age=' + (365 * 24 * 60 * 60) + '; SameSite=Lax';
})();
</script>

<?php get_footer(); ?>
