<?php
/**
 * 404.php
 * WordPress uses this automatically for all 404 Not Found errors.
 * Styled to match MuskPulse Mission Control design.
 */
?>
<?php get_header(); ?>

<?php get_template_part('template-parts/site-nav'); ?>

<?php get_template_part('template-parts/ticker'); ?>

<div class="error-outer">
  <div class="error-wrap">

    <!-- Corner decorations -->
    <div class="error-corner error-corner-tl"></div>
    <div class="error-corner error-corner-br"></div>

    <!-- Status -->
    <div class="error-status">
      <span class="error-dot"></span>
      <span>SIGNAL LOST</span>
    </div>

    <!-- Error code -->
    <div class="error-code">404</div>

    <!-- Title -->
    <h1 class="error-title">
      Transmission<br>
      <span class="error-accent">Not Found.</span>
    </h1>

    <p class="error-sub">
      The intel you're looking for doesn't exist, was moved, or is still classified. Re-calibrate your coordinates.
    </p>

    <!-- Telemetry block -->
    <div class="error-telemetry">
      <div class="error-telem-label">// LAST KNOWN POSITION</div>
      <div class="error-telem-val"><?php echo esc_html($_SERVER['REQUEST_URI'] ?? '/unknown'); ?></div>
    </div>

    <!-- Actions -->
    <div class="error-actions">
      <a href="<?php echo esc_url(home_url('/mission-feed')); ?>" class="error-btn-primary">
        <span>Return to Mission Feed →</span>
      </a>
      <a href="<?php echo esc_url(home_url('/')); ?>" class="error-btn-ghost">← Back to Base</a>
    </div>

    <!-- Quick links -->
    <div class="error-links">
      <div class="error-links-label">// KNOWN COORDINATES</div>
      <div class="error-links-grid">
        <a href="<?php echo esc_url(home_url('/category/tesla-news')); ?>">TSLA Intel</a>
        <a href="<?php echo esc_url(home_url('/category/spacex-ipo')); ?>">SpaceX IPO</a>
        <a href="<?php echo esc_url(home_url('/category/xai-optimus')); ?>">Optimus &amp; Neuralink</a>
        <a href="<?php echo esc_url(home_url('/mission-briefing')); ?>">Mission Briefing</a>
      </div>
    </div>

  </div>
</div>

<style>
.error-outer {
  min-height: calc(100vh - 120px);
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 60px 24px;
  position: relative;
  z-index: 10;
}

/* Animated grid background */
.error-outer::before {
  content: '';
  position: fixed; inset: 0; z-index: 0; pointer-events: none;
  background-image:
    linear-gradient(var(--grid-line) 1px, transparent 1px),
    linear-gradient(90deg, var(--grid-line) 1px, transparent 1px);
  background-size: 40px 40px;
}

.error-wrap {
  max-width: 680px;
  width: 100%;
  text-align: center;
  position: relative;
  z-index: 1;
  padding: 64px 56px;
  border: 1px solid var(--border);
  background: var(--surface);
}

/* Top accent line */
.error-wrap::before {
  content: '';
  position: absolute; top: 0; left: 0; right: 0; height: 2px;
  background: linear-gradient(90deg, transparent, var(--red) 30%, var(--accent) 70%, transparent);
}

/* Corner decorations */
.error-corner {
  position: absolute;
  width: 16px; height: 16px;
}
.error-corner-tl {
  top: 16px; left: 16px;
  border-top: 1px solid var(--red);
  border-left: 1px solid var(--red);
}
.error-corner-br {
  bottom: 16px; right: 16px;
  border-bottom: 1px solid var(--accent);
  border-right: 1px solid var(--accent);
}

/* Status */
.error-status {
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 8px;
  font-family: 'Share Tech Mono', monospace;
  font-size: 9px;
  letter-spacing: 3px;
  text-transform: uppercase;
  color: var(--red);
  margin-bottom: 24px;
}
.error-dot {
  width: 6px; height: 6px;
  border-radius: 50%;
  background: var(--red);
  box-shadow: 0 0 8px var(--red);
  animation: dotPulse 1.5s ease-in-out infinite;
}
@keyframes dotPulse { 0%,100%{opacity:1} 50%{opacity:.2} }

/* Error code */
.error-code {
  font-family: 'Orbitron', monospace;
  font-weight: 900;
  font-size: clamp(80px, 18vw, 160px);
  line-height: 1;
  color: transparent;
  -webkit-text-stroke: 2px rgba(232,39,75,.25);
  letter-spacing: -4px;
  margin-bottom: 8px;
  user-select: none;
  text-shadow: 0 0 60px rgba(232,39,75,.15);
}

/* Title */
.error-title {
  font-family: 'Orbitron', monospace;
  font-weight: 900;
  font-size: clamp(28px, 4vw, 48px);
  text-transform: uppercase;
  color: #fff;
  line-height: 1.1;
  margin-bottom: 20px;
  letter-spacing: -0.5px;
}
.error-accent {
  color: var(--accent);
  text-shadow: 0 0 30px rgba(0,200,255,.3);
}

.error-sub {
  font-size: 15px;
  color: var(--text-dim);
  line-height: 1.75;
  font-weight: 300;
  margin-bottom: 36px;
  max-width: 480px;
  margin-left: auto;
  margin-right: auto;
}

/* Telemetry */
.error-telemetry {
  background: var(--bg);
  border: 1px solid var(--border);
  padding: 16px 20px;
  margin-bottom: 36px;
  text-align: left;
}
.error-telem-label {
  font-family: 'Orbitron', monospace;
  font-size: 9px;
  letter-spacing: 3px;
  text-transform: uppercase;
  color: var(--red);
  margin-bottom: 8px;
}
.error-telem-val {
  font-family: 'Share Tech Mono', monospace;
  font-size: 12px;
  color: var(--muted);
  word-break: break-all;
}

/* Actions */
.error-actions {
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 16px;
  margin-bottom: 40px;
}
.error-btn-primary {
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
.error-btn-primary:hover { background: #fff; color: var(--bg); }
.error-btn-ghost {
  font-family: 'Share Tech Mono', monospace;
  font-size: 11px;
  letter-spacing: 2px;
  color: var(--muted);
  text-decoration: none;
  transition: color .2s;
}
.error-btn-ghost:hover { color: var(--accent); }

/* Quick links */
.error-links {
  border-top: 1px solid var(--border);
  padding-top: 28px;
}
.error-links-label {
  font-family: 'Orbitron', monospace;
  font-size: 9px;
  letter-spacing: 3px;
  text-transform: uppercase;
  color: var(--muted);
  margin-bottom: 16px;
}
.error-links-grid {
  display: flex;
  flex-wrap: wrap;
  justify-content: center;
  gap: 12px;
}
.error-links-grid a {
  font-family: 'Share Tech Mono', monospace;
  font-size: 10px;
  letter-spacing: 2px;
  text-transform: uppercase;
  color: var(--muted);
  text-decoration: none;
  padding: 6px 14px;
  border: 1px solid var(--border);
  transition: color .2s, border-color .2s;
}
.error-links-grid a:hover {
  color: var(--accent);
  border-color: var(--accent);
}

@media (max-width: 600px) {
  .error-wrap { padding: 40px 24px; }
  .error-code  { font-size: 100px; }
}
</style>

<?php get_footer(); ?>
