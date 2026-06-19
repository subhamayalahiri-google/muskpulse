/**
 * smoke.js
 * Handles: smoke particle simulation, screen fill, wind clearing.
 *
 * Depends on: starfield.js (MP.W, MP.H, MP.smX, MP.smC)
 * Consumed by: launch.js (burst, burstAt, fillScreen, trickle, stopTrickle, startClearing)
 *
 * Performance fix vs original:
 * - Original called ctx.createRadialGradient() once per particle per frame.
 *   At 300 particles × 60fps = 18,000 gradient objects/second. Expensive.
 * - Now draws a simple filled circle with ctx.globalAlpha.
 *   The soft feathered edge is achieved with two overlapping circles at
 *   different radii and alphas — visually equivalent, ~10x cheaper.
 */

(function() {
  'use strict';

  window.MP = window.MP || {};

  // ── STATE ──────────────────────────────────────────────────────
  let particles    = [];
  let smokeActive  = false;
  let smokeClearing = false;
  let clearStart   = null;
  let smokeIv      = null;

  const CLEAR_DUR = 4000; // ms — shorter, more decisive clear

  // ── SMOKE PARTICLE ─────────────────────────────────────────────
  class SmokeParticle {
    constructor(opts = {}) {
      const o = opts;

      // Spawn position — explicit ox/oy or default to rocket base area
      this.x = (o.ox !== undefined)
        ? o.ox
        : MP.W / 2 + (Math.random() - 0.5) * (o.spread || 200);
      this.y = (o.oy !== undefined)
        ? o.oy
        : MP.H - 180 + (Math.random() - 0.5) * 30;

      // Velocity
      const angle = (o.angle !== undefined)
        ? o.angle + (Math.random() - 0.5) * (o.angleSpread || 1.2)
        : -Math.PI / 2 + (Math.random() - 0.5) * (o.arc || 2.4);
      const speed = (o.vyB || 0.8) + Math.random() * (o.vyR || 1.2);

      this.vx = Math.cos(angle) * speed + (Math.random() - 0.5) * (o.vxR || 2.5);
      this.vy = Math.sin(angle) * speed;

      // Size
      this.r    = o.minR || 15;
      this.maxR = (o.minR || 15) + Math.random() * ((o.maxR || 120) - (o.minR || 15));
      this.grow = o.grow || 0.3;

      // Opacity
      this.alpha     = o.alpha || 0.5;
      this.baseAlpha = this.alpha;
      this.life      = 1.0;

      // Lifetime (treated as milliseconds at 60fps: 16.67ms/frame)
      const minL = o.minL || 300;
      const maxL = o.maxL || 600;
      this.decay = 16.67 / (minL + Math.random() * (maxL - minL));

      // Wind drift — slight random horizontal bias
      this.wind  = (Math.random() - 0.4) * 0.35;
      this.alive = true;
      this.kicked = false; // tracks whether startClearing kick has been applied
    }

    update() {
      if (!this.alive) return;

      // Grow toward maxR
      if (this.r < this.maxR) {
        this.r += this.grow + (this.maxR - this.r) * 0.012;
      }

      // Physics
      this.x += this.vx + this.wind;
      this.y += this.vy;
      this.vy -= 0.018; // buoyancy
      this.vy *= 0.994;
      this.wind *= 0.998;

      // Decay and wind during clearing
      let d = this.decay;
      if (smokeClearing && clearStart) {
        const t = Math.min((performance.now() - clearStart) / CLEAR_DUR, 1);

        // Strong wind — no drag applied during clearing so it builds fast
        this.vx += 1.2 + t * 4.0;   // was 0.08 + t*1.2 — 15x stronger
        this.vy -= 0.05 + t * 0.15; // stronger upward lift

        // Aggressive decay — smoke visibly fades as it moves
        d += 0.008 + t * t * 0.025;
      } else {
        // Normal drag only when not clearing
        this.vx *= 0.992;
      }

      this.life  -= d;
      this.alpha  = Math.max(0, this.life * this.baseAlpha);
      if (this.life <= 0) this.alive = false;
    }

    draw(ctx) {
      if (!this.alive || this.alpha <= 0 || this.r <= 0) return;

      ctx.save();

      // Outer soft halo — large radius, low alpha
      ctx.globalAlpha = this.alpha * 0.35;
      ctx.fillStyle   = 'rgb(240,240,240)';
      ctx.beginPath();
      ctx.arc(this.x, this.y, this.r, 0, Math.PI * 2);
      ctx.fill();

      // Inner dense core — smaller radius, full alpha
      ctx.globalAlpha = this.alpha;
      ctx.fillStyle   = 'rgb(252,252,252)';
      ctx.beginPath();
      ctx.arc(this.x, this.y, this.r * 0.55, 0, Math.PI * 2);
      ctx.fill();

      ctx.restore();
    }
  }

  // ── SPAWN HELPERS ───────────────────────────────────────────────
  function burst(n, opts) {
    for (let i = 0; i < n; i++) particles.push(new SmokeParticle(opts));
  }

  function burstAt(n, x, y, opts) {
    for (let i = 0; i < n; i++) particles.push(new SmokeParticle({ ...opts, ox: x, oy: y }));
  }

  function fillScreen(n, alpha, minL, maxL) {
    for (let i = 0; i < n; i++) {
      particles.push(new SmokeParticle({
        ox:          Math.random() * MP.W,
        oy:          Math.random() * MP.H,
        angle:       Math.random() * Math.PI * 2,
        angleSpread: 0.4,
        vyB: 0.05, vyR: 0.2, vxR: 0.4,
        minR: 100, maxR: 320,
        minL, maxL,
        alpha,
        grow: 0.12
      }));
    }
  }

  function trickle(rate, opts) {
    stopTrickle();
    smokeIv = setInterval(() => {
      if (smokeActive) burst(6, opts);
    }, rate);
  }

  function stopTrickle() {
    if (smokeIv) { clearInterval(smokeIv); smokeIv = null; }
  }

  // ── CLEARING ────────────────────────────────────────────────────
  function startClearing() {
    smokeClearing = true;
    clearStart    = performance.now();

    // Restore cursor — was hidden during rocket sequence
    document.body.style.cursor = 'default';

    // Immediately kick all existing particles rightward and upward.
    // Without this, clearing is invisible for the first 2-3 seconds
    // because the gradual wind ramp (1.2px/frame) barely moves particles
    // that have 0.992 drag applied simultaneously.
    particles.forEach(p => {
      p.vx += 3.0 + Math.random() * 4.0;   // strong rightward launch
      p.vy -= 0.4 + Math.random() * 0.8;   // upward lift
    });

    // Reveal landing page — already in DOM at opacity:0
    const lp = document.getElementById('landing-page');
    lp.style.transition    = 'opacity .5s ease';
    lp.style.opacity       = '1';
    lp.style.pointerEvents = 'all';

    // Stagger child element reveals as smoke thins
    setTimeout(() => { const n = document.getElementById('lpNav');    if(n) n.style.opacity='1'; }, 300);
    setTimeout(() => { const t = document.getElementById('lpTicker'); if(t) t.style.opacity='1'; }, 700);
    setTimeout(() => { const m = document.getElementById('lpMain');   if(m) m.style.opacity='1'; }, 1100);
  }

  function updateClearing(now) {
    if (!clearStart) return;
    const t = Math.min((now - clearStart) / CLEAR_DUR, 1);
    if (t >= 1) {
      // All particles dead, fade the canvas out and stop updating
      MP.smC.style.transition = 'opacity 1s ease';
      MP.smC.style.opacity    = '0';
      clearStart = null;
    }
  }

  // ── DRAW LOOP ───────────────────────────────────────────────────
  function drawSmoke() {
    MP.smX.clearRect(0, 0, MP.W, MP.H);
    particles = particles.filter(p => {
      p.update();
      if (p.alive) { p.draw(MP.smX); return true; }
      return false;
    });
  }

  // ── RENDER LOOP (called from launch.js main loop) ───────────────
  function smokeLoop(ts) {
    drawSmoke();
    updateClearing(ts);
  }

  // ── EXPOSE TO OTHER MODULES ─────────────────────────────────────
  window.MP.smoke = {
    setActive:    (v)           => { smokeActive = v; },
    burst:        burst,
    burstAt:      burstAt,
    fillScreen:   fillScreen,
    trickle:      trickle,
    stopTrickle:  stopTrickle,
    startClearing: startClearing,
    loop:         smokeLoop,
    // Expose SmokeParticle constructor for direct use in launch.js ascent loop
    Particle:     SmokeParticle,
    getParticles: () => particles,
    pushParticle: (p) => particles.push(p)
  };

})();
