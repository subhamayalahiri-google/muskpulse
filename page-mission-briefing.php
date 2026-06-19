<?php
/**
 * Template Name: Mission Briefing
 * Template Post Type: page
 *
 * Used for the Join Mission Briefing signup page.
 * Assign this template in WordPress: Pages → Edit → Page Attributes → Template → Mission Briefing
 */
?>
<?php get_header(); ?>

<?php get_template_part('template-parts/site-nav'); ?>

<?php get_template_part('template-parts/ticker'); ?>

<div class="briefing-outer">

  <!-- LEFT — pitch copy -->
  <div class="briefing-left">

    <div class="briefing-tag">
      <span class="cat-badge cat-invest"><span class="dot"></span>Free Intelligence Briefing</span>
    </div>

    <h1 class="briefing-title">
      Join the<br>
      <span class="briefing-accent">Mission Briefing.</span>
    </h1>

    <p class="briefing-sub">
      Weekly intel on Tesla, SpaceX, xAI, Optimus and Neuralink — curated for retail investors who need signal, not noise.
    </p>

    <ul class="briefing-benefits">
      <li>
        <span class="benefit-icon">◆</span>
        <div>
          <strong>SpaceX IPO Alerts</strong>
          <span>Timeline updates, S-1 analysis, and retail allocation breakdowns as they happen.</span>
        </div>
      </li>
      <li>
        <span class="benefit-icon">◆</span>
        <div>
          <strong>TSLA Weekly Intel</strong>
          <span>Earnings breakdowns, production milestones, and FSD expansion updates.</span>
        </div>
      </li>
      <li>
        <span class="benefit-icon">◆</span>
        <div>
          <strong>xAI & Optimus Watch</strong>
          <span>The robotics and AI plays most investors are still underestimating.</span>
        </div>
      </li>
      <li>
        <span class="benefit-icon">◆</span>
        <div>
          <strong>Zero noise</strong>
          <span>No hype, no ads, no filler. Unsubscribe any time.</span>
        </div>
      </li>
    </ul>

    <div class="briefing-stats">
      <div class="briefing-stat">
        <div class="briefing-stat-num">10K</div>
        <div class="briefing-stat-label">Subscriber Cap</div>
      </div>
      <div class="briefing-stat">
        <div class="briefing-stat-num">Free</div>
        <div class="briefing-stat-label">Always</div>
      </div>
      <div class="briefing-stat">
        <div class="briefing-stat-num">Weekly</div>
        <div class="briefing-stat-label">Delivery</div>
      </div>
    </div>

  </div>

  <!-- RIGHT — Kit form -->
  <div class="briefing-right">
    <div class="briefing-form-wrap">

      <div class="briefing-form-header">
        <div class="briefing-form-label">// SECURE YOUR SPOT</div>
        <h2 class="briefing-form-title">Mission Briefing</h2>
        <p class="briefing-form-sub">Join investors tracking the full Elon Musk portfolio.</p>
      </div>

      <!-- Kit embed — white styling overridden by .briefing-form-wrap CSS below -->
      <div class="kit-form-container">
        <script src="https://f.convertkit.com/ckjs/ck.5.js"></script>
        <form action="https://app.kit.com/forms/9428023/subscriptions" class="seva-form formkit-form" method="post" data-sv-form="9428023" data-uid="a1791d1632" data-format="inline" data-version="5" data-options="{&quot;settings&quot;:{&quot;after_subscribe&quot;:{&quot;action&quot;:&quot;message&quot;,&quot;success_message&quot;:&quot;Success! Now check your email to confirm your subscription.&quot;,&quot;redirect_url&quot;:&quot;&quot;},&quot;analytics&quot;:{&quot;google&quot;:null,&quot;fathom&quot;:null,&quot;facebook&quot;:null,&quot;segment&quot;:null,&quot;pinterest&quot;:null,&quot;sparkloop&quot;:null,&quot;googletagmanager&quot;:null},&quot;modal&quot;:{&quot;trigger&quot;:&quot;timer&quot;,&quot;scroll_percentage&quot;:null,&quot;timer&quot;:5,&quot;devices&quot;:&quot;all&quot;,&quot;show_once_every&quot;:15},&quot;powered_by&quot;:{&quot;show&quot;:false,&quot;url&quot;:&quot;https://kit.com/features/forms?utm_campaign=poweredby&amp;utm_content=form&amp;utm_medium=referral&amp;utm_source=dynamic&quot;},&quot;recaptcha&quot;:{&quot;enabled&quot;:false},&quot;return_visitor&quot;:{&quot;action&quot;:&quot;show&quot;,&quot;custom_content&quot;:&quot;&quot;},&quot;slide_in&quot;:{&quot;display_in&quot;:&quot;bottom_right&quot;,&quot;trigger&quot;:&quot;timer&quot;,&quot;scroll_percentage&quot;:null,&quot;timer&quot;:5,&quot;devices&quot;:&quot;all&quot;,&quot;show_once_every&quot;:15},&quot;sticky_bar&quot;:{&quot;display_in&quot;:&quot;top&quot;,&quot;trigger&quot;:&quot;timer&quot;,&quot;scroll_percentage&quot;:null,&quot;timer&quot;:5,&quot;devices&quot;:&quot;all&quot;,&quot;show_once_every&quot;:15}},&quot;version&quot;:&quot;5&quot;}" min-width="400 500 600 700 800">
          <div class="formkit-background" style="opacity:0"></div>
          <div data-style="minimal">
            <ul class="formkit-alert formkit-alert-error" data-element="errors" data-group="alert"></ul>
            <div data-element="fields" data-stacked="true" class="seva-fields formkit-fields">
              <div class="formkit-field">
                <input class="formkit-input" name="email_address" aria-label="Email Address" placeholder="your@email.com" required type="email">
              </div>
              <button data-element="submit" class="formkit-submit">
                <div class="formkit-spinner"><div></div><div></div><div></div></div>
                <span>Join the Briefing →</span>
              </button>
            </div>
            <div class="formkit-guarantee" data-element="guarantee">
              <p>We won't send you spam. Unsubscribe at any time.</p>
            </div>
          </div>
        </form>
      </div>

    </div>
  </div>

</div><!-- /briefing-outer -->

<style>
/* ── PAGE LAYOUT ─────────────────────────────────────────────── */
body { background: var(--bg); }

.briefing-outer {
  max-width: 1280px;
  margin: 0 auto;
  display: grid;
  grid-template-columns: 1fr 480px;
  gap: 1px;
  background: var(--border);
  min-height: calc(100vh - 120px);
  position: relative;
  z-index: 10;
}

/* ── LEFT COPY PANEL ─────────────────────────────────────────── */
.briefing-left {
  background: var(--bg);
  padding: 64px 60px;
  position: relative;
}
.briefing-left::before {
  content: '';
  position: absolute; top: 0; left: 0; right: 0; height: 2px;
  background: linear-gradient(90deg, transparent, var(--accent) 30%, var(--red) 70%, transparent);
}

.briefing-tag { margin-bottom: 24px; }

.briefing-title {
  font-family: 'Orbitron', monospace;
  font-weight: 900;
  font-size: clamp(36px, 5vw, 64px);
  line-height: 1.05;
  text-transform: uppercase;
  color: #fff;
  margin-bottom: 24px;
  letter-spacing: -1px;
}
.briefing-accent {
  color: var(--accent);
  text-shadow: 0 0 40px rgba(0,200,255,.35);
  display: block;
}

.briefing-sub {
  font-size: 17px;
  color: var(--text-dim);
  line-height: 1.75;
  font-weight: 300;
  max-width: 520px;
  margin-bottom: 48px;
  border-left: 2px solid var(--accent);
  padding-left: 20px;
}

/* Benefits list */
.briefing-benefits {
  list-style: none;
  margin-bottom: 52px;
  display: flex;
  flex-direction: column;
  gap: 24px;
}
.briefing-benefits li {
  display: flex;
  gap: 16px;
  align-items: flex-start;
}
.benefit-icon {
  color: var(--accent);
  font-size: 8px;
  margin-top: 5px;
  flex-shrink: 0;
}
.briefing-benefits strong {
  display: block;
  font-family: 'Orbitron', monospace;
  font-size: 11px;
  letter-spacing: 2px;
  text-transform: uppercase;
  color: #fff;
  margin-bottom: 4px;
}
.briefing-benefits span {
  font-size: 14px;
  color: var(--muted);
  line-height: 1.6;
  font-weight: 300;
}

/* Stats row */
.briefing-stats {
  display: flex;
  gap: 0;
  border-top: 1px solid var(--border);
  padding-top: 32px;
}
.briefing-stat {
  flex: 1;
  padding-right: 32px;
  border-right: 1px solid var(--border);
  margin-right: 32px;
}
.briefing-stat:last-child {
  border-right: none;
  margin-right: 0;
  padding-right: 0;
}
.briefing-stat-num {
  font-family: 'Orbitron', monospace;
  font-weight: 900;
  font-size: 28px;
  color: var(--accent);
  text-shadow: 0 0 20px rgba(0,200,255,.3);
  line-height: 1;
  margin-bottom: 4px;
}
.briefing-stat-label {
  font-family: 'Share Tech Mono', monospace;
  font-size: 9px;
  letter-spacing: 2px;
  text-transform: uppercase;
  color: var(--muted);
}

/* ── RIGHT FORM PANEL ────────────────────────────────────────── */
.briefing-right {
  background: var(--surface);
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 48px 40px;
}

.briefing-form-wrap {
  width: 100%;
  max-width: 380px;
}

.briefing-form-label {
  font-family: 'Share Tech Mono', monospace;
  font-size: 9px;
  letter-spacing: 3px;
  text-transform: uppercase;
  color: var(--accent);
  margin-bottom: 12px;
}
.briefing-form-title {
  font-family: 'Orbitron', monospace;
  font-weight: 900;
  font-size: 28px;
  text-transform: uppercase;
  color: #fff;
  margin-bottom: 8px;
  letter-spacing: 1px;
}
.briefing-form-sub {
  font-size: 13px;
  color: var(--muted);
  line-height: 1.6;
  margin-bottom: 32px;
  font-weight: 300;
}

/* Kit form container */
.kit-form-container {
  background: transparent;
}

/* ── OVERRIDE KIT'S WHITE STYLING ────────────────────────────── */
.kit-form-container .formkit-form {
  background: transparent !important;
  border: none !important;
  max-width: 100% !important;
}
.kit-form-container .formkit-form [data-style="minimal"] {
  padding: 0 !important;
  background: transparent !important;
}
.kit-form-container .formkit-background {
  display: none !important;
}
/* Hide Kit's own header/subheader — we use our own above */
.kit-form-container .formkit-header,
.kit-form-container .formkit-subheader {
  display: none !important;
}
/* Input field */
.kit-form-container .formkit-input {
  width: 100% !important;
  background: var(--surface2) !important;
  border: 1px solid var(--border) !important;
  color: var(--text) !important;
  font-family: 'Share Tech Mono', monospace !important;
  font-size: 13px !important;
  padding: 14px 16px !important;
  border-radius: 0 !important;
  margin-bottom: 12px !important;
  transition: border-color .2s !important;
  box-sizing: border-box !important;
}
.kit-form-container .formkit-input:focus {
  border-color: var(--accent) !important;
  outline: none !important;
}
.kit-form-container .formkit-input::placeholder {
  color: var(--muted) !important;
  opacity: 1 !important;
}
/* Submit button */
.kit-form-container .formkit-submit {
  width: 100% !important;
  background: var(--accent) !important;
  color: var(--bg) !important;
  font-family: 'Orbitron', monospace !important;
  font-size: 10px !important;
  letter-spacing: 3px !important;
  text-transform: uppercase !important;
  padding: 14px 28px !important;
  border: none !important;
  border-radius: 0 !important;
  cursor: pointer !important;
  clip-path: polygon(6px 0%,100% 0%,calc(100% - 6px) 100%,0% 100%) !important;
  transition: background .2s !important;
  margin-bottom: 0 !important;
}
.kit-form-container .formkit-submit:hover {
  background: #fff !important;
}
.kit-form-container .formkit-submit span {
  padding: 0 !important;
  font-weight: 700 !important;
}
/* Fields container */
.kit-form-container .seva-fields {
  flex-direction: column !important;
  gap: 0 !important;
  margin-top: 0 !important;
}
.kit-form-container .formkit-field,
.kit-form-container .formkit-submit {
  flex: none !important;
  width: 100% !important;
  margin: 0 0 12px 0 !important;
}
/* Guarantee text */
.kit-form-container .formkit-guarantee {
  text-align: center !important;
  margin-top: 16px !important;
}
.kit-form-container .formkit-guarantee p {
  font-family: 'Share Tech Mono', monospace !important;
  font-size: 9px !important;
  letter-spacing: 1px !important;
  color: var(--muted) !important;
  text-transform: uppercase !important;
}
/* Success message */
.kit-form-container .formkit-alert-success {
  background: rgba(0,255,136,.08) !important;
  border-color: var(--green) !important;
  color: var(--green) !important;
  border-radius: 0 !important;
  font-family: 'Share Tech Mono', monospace !important;
  font-size: 12px !important;
}
/* Hide powered by Kit */
.kit-form-container .formkit-powered-by-convertkit-container {
  display: none !important;
}

/* ── RESPONSIVE ──────────────────────────────────────────────── */
@media (max-width: 900px) {
  .briefing-outer {
    grid-template-columns: 1fr;
  }
  .briefing-left {
    padding: 40px 24px;
  }
  .briefing-right {
    padding: 40px 24px;
  }
  .briefing-form-wrap {
    max-width: 100%;
  }
}
</style>

<?php get_footer(); ?>
