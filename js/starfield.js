/**
 * starfield.js
 * Handles: canvas setup, star field rendering, milky way band.
 *
 * Performance fixes vs original:
 * - Milky way generated ONCE at initStars(), stored in milkyWayStars[]
 *   (original called Math.random() inside drawStars() = 60x per second = flickering noise)
 * - Background gradient drawn to offscreen canvas ONCE at init
 *   (original called createLinearGradient() every frame)
 * - All shared state exposed on window.MP namespace, not raw globals
 */

(function() {
  'use strict';

  // ── SHARED NAMESPACE ───────────────────────────────────────────
  // smoke.js and launch.js read/write these
  window.MP = window.MP || {};

  // Canvas elements — exposed so smoke.js can share resize
  window.MP.sfC = document.getElementById('starfield');
  window.MP.smC = document.getElementById('smokeCanvas');
  window.MP.sfX = MP.sfC.getContext('2d');
  window.MP.smX = MP.smC.getContext('2d');
  window.MP.W   = 0;
  window.MP.H   = 0;

  // Launch state — written by launch.js, read by drawStars
  window.MP.launchProgress = 0;
  window.MP.streakMode     = false;

  // ── INTERNAL STATE ─────────────────────────────────────────────
  let stars        = [];
  let milkyWayStars = [];  // generated once, never randomised again
  let bgCanvas     = null; // offscreen canvas for the static background gradient

  // ── INIT ───────────────────────────────────────────────────────
  function resize() {
    MP.W = MP.sfC.width = MP.smC.width = window.innerWidth;
    MP.H = MP.sfC.height = MP.smC.height = window.innerHeight;
    buildBgCanvas();
    initStars();
  }

  // Render the static gradient background to an offscreen canvas once.
  // drawStars() copies it with drawImage() — much cheaper than re-creating
  // the gradient object every frame.
  function buildBgCanvas() {
    bgCanvas        = document.createElement('canvas');
    bgCanvas.width  = MP.W;
    bgCanvas.height = MP.H;
    const bx = bgCanvas.getContext('2d');
    const g  = bx.createLinearGradient(0, 0, 0, MP.H);
    g.addColorStop(0,   '#000008');
    g.addColorStop(0.6, '#00010a');
    g.addColorStop(1,   '#010408');
    bx.fillStyle = g;
    bx.fillRect(0, 0, MP.W, MP.H);
  }

  function initStars() {
    // Main stars
    stars = Array.from({ length: 320 }, () => ({
      x:       Math.random() * MP.W,
      y:       Math.random() * MP.H,
      r:       Math.random() * 1.4 + 0.2,
      alpha:   Math.random() * 0.8 + 0.2,
      twinkle: Math.random() * Math.PI * 2,
      speed:   Math.random() * 0.02 + 0.005
    }));

    // Milky way — fixed positions, generated once here
    // Original generated these with Math.random() inside drawStars()
    // which caused 60fps noise flickering, not a stable milky way band
    milkyWayStars = Array.from({ length: 60 }, (_, i) => ({
      x:     MP.W * 0.3 + Math.sin(i * 0.3) * MP.W * 0.25,
      y:     i * (MP.H / 60),
      r:     Math.random() * 0.6 + 0.1,
      alpha: Math.random() * 0.08 + 0.02
    }));
  }

  // ── RENDER ──────────────────────────────────────────────────────
  function drawStars() {
    const ctx = MP.sfX;

    // Draw pre-rendered background (one drawImage call, no gradient creation)
    if (bgCanvas) ctx.drawImage(bgCanvas, 0, 0);

    // Draw milky way from pre-computed positions
    milkyWayStars.forEach(s => {
      ctx.beginPath();
      ctx.arc(s.x, s.y, s.r, 0, Math.PI * 2);
      ctx.fillStyle = `rgba(200,210,255,${s.alpha})`;
      ctx.fill();
    });

    // Draw main stars with twinkle and optional launch streaks
    stars.forEach(s => {
      s.twinkle += s.speed;
      const a = s.alpha * (0.7 + 0.3 * Math.sin(s.twinkle));

      if (MP.streakMode && MP.launchProgress > 0) {
        ctx.beginPath();
        ctx.moveTo(s.x, s.y);
        ctx.lineTo(s.x, s.y + MP.launchProgress * 40);
        ctx.strokeStyle = `rgba(255,255,255,${a * 0.6})`;
        ctx.lineWidth   = s.r * 0.5;
        ctx.stroke();
      } else {
        ctx.beginPath();
        ctx.arc(s.x, s.y, s.r, 0, Math.PI * 2);
        ctx.fillStyle = `rgba(255,255,255,${a})`;
        ctx.fill();
      }
    });
  }

  // ── EXPOSE DRAW FUNCTION ────────────────────────────────────────
  window.MP.drawStars = drawStars;

  // ── RESIZE ─────────────────────────────────────────────────────
  window.addEventListener('resize', resize);
  resize();

})();
