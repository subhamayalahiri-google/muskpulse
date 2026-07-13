/**
 * launch.js
 * Handles: main render loop, live clock, IPO countdown, full launch sequence.
 *
 * Depends on: starfield.js (MP.drawStars, MP.launchProgress, MP.streakMode)
 *             smoke.js     (MP.smoke.*)
 *
 * All DOM references resolved once at init, not inside animation loops.
 */

(function() {
  'use strict';

  window.MP = window.MP || {};

  // ── SMOKE SHORTHAND ────────────────────────────────────────────
  // Declared at IIFE scope so ALL functions (runSequence, onRocketGone,
  // ascendFrame) can access it without redeclaring.
  // smoke.js must load before launch.js — enforced by front-page.php script order.
  let smoke; // assigned on window load — see START section below

  // ── DOM REFERENCES ─────────────────────────────────────────────
  // Resolved once here — never use getElementById inside animation loops
  const dom = {
    rocket:      document.getElementById('rocket'),
    plume:       document.getElementById('plume'),
    pCore:       document.querySelector('.plume-core'),
    pOuter:      document.querySelector('.plume-outer'),
    heat:        document.getElementById('heat'),
    gGlow:       document.getElementById('groundGlow'),
    cdn:         document.getElementById('countdown-number'),
    cdl:         document.getElementById('countdown-label'),
    mnm:         document.getElementById('mission-name'),
    flash:       document.getElementById('flash'),
    ltx:         document.getElementById('liftoff-text'),
    atm:         document.getElementById('atmosphere'),
    scene:       document.getElementById('scene'),
    hud:         document.getElementById('hud'),
    telemetry:   document.querySelector('.telemetry'),
    missionData: document.querySelector('.mission-data'),
    scanlines:   document.getElementById('scanlines'),
    sfCanvas:    MP.sfC,
    clock:       document.querySelector('.mp-clock'),
    ipoNum:      document.getElementById('ipo-countdown'),
    // Telemetry value elements
    alt:         document.getElementById('alt'),
    vel:         document.getElementById('vel'),
    throttle:    document.getElementById('throttle'),
    stage:       document.getElementById('stage'),
    altBar:      document.getElementById('altBar'),
    velBar:      document.getElementById('velBar'),
    throttleBar: document.getElementById('throttleBar'),
  };

  // ── HELPERS ────────────────────────────────────────────────────
  const wait = ms => new Promise(r => setTimeout(r, ms));

  function setTelemetry(alt, vel, thr, stage) {
    if (dom.alt)         dom.alt.textContent         = alt;
    if (dom.vel)         dom.vel.textContent         = vel;
    if (dom.throttle)    dom.throttle.textContent    = thr;
    if (dom.stage)       dom.stage.textContent       = stage;
    if (dom.altBar)      dom.altBar.style.width      = (parseFloat(alt) / 600 * 100) + '%';
    if (dom.velBar)      dom.velBar.style.width      = (parseFloat(vel) / 9000 * 100) + '%';
    if (dom.throttleBar) dom.throttleBar.style.width = thr;
  }

  function hideEl(el, delay = 0) {
    if (!el) return;
    // Must cancel CSS animation FIRST — animation-fill-mode:forwards locks opacity:1
    // and overrides inline style opacity:0 until the animation is removed.
    el.style.animation  = 'none';
    el.style.transition = 'opacity .3s ease';
    el.style.opacity    = '0';
    if (delay >= 0) setTimeout(() => { el.style.display = 'none'; }, delay + 400);
  }

  // ── LIVE CLOCK ─────────────────────────────────────────────────
  // Local timezone abbreviation (e.g. "EST", "PDT"), computed once.
  const tzLabel = (() => {
    try {
      const part = Intl.DateTimeFormat(undefined, { timeZoneName: 'short' })
        .formatToParts(new Date())
        .find(p => p.type === 'timeZoneName');
      return part ? part.value : '';
    } catch (e) { return ''; }
  })();
  function updateClock() {
    if (!dom.clock) return;
    const n = new Date();
    const pad = v => String(v).padStart(2, '0');
    dom.clock.textContent = `${pad(n.getHours())}:${pad(n.getMinutes())}:${pad(n.getSeconds())}${tzLabel ? ' ' + tzLabel : ''}`;
  }
  setInterval(updateClock, 1000);
  updateClock();

  // ── IPO COUNTDOWN ───────────────────────────────────────────────
  function updateIpoCountdown() {
    if (!dom.ipoNum) return;
    // Update this date when the IPO date is confirmed
    const target = new Date('2026-06-12T09:30:00-04:00');
    const diff   = Math.ceil((target - new Date()) / (1000 * 60 * 60 * 24));
    dom.ipoNum.textContent = diff > 0 ? diff : '—';
  }
  updateIpoCountdown();

  // ── MAIN RENDER LOOP ────────────────────────────────────────────
  // Draws to the starfield/smoke canvases behind the splash. Once the
  // splash is gone (skipped, or finished playing) neither canvas is
  // visible again, so the loop must stop itself — otherwise it keeps
  // calling requestAnimationFrame forever, burning CPU/battery on every
  // visit for as long as the tab stays open, for a canvas nobody sees.
  let rafId = null;
  function loop(ts) {
    MP.drawStars();
    MP.smoke.loop(ts);
    rafId = requestAnimationFrame(loop);
  }
  function stopLoop() {
    if (rafId !== null) {
      cancelAnimationFrame(rafId);
      rafId = null;
    }
  }

  // ── LAUNCH SEQUENCE ────────────────────────────────────────────
  async function runSequence() {
    await wait(600);
    dom.cdl.style.opacity = '1';
    dom.mnm.style.opacity = '1';
    dom.cdn.classList.add('big');
    await wait(400);

    // ── COUNTDOWN 5 → 1 ──────────────────────────────────────────

    for (let i = 5; i >= 1; i--) {
      dom.cdn.textContent = i;

      if (i === 3) {
        // Ignition — plume appears, smoke begins from engine base
        dom.plume.style.opacity = '1';
        dom.pCore.style.height  = '30px';
        dom.pOuter.style.height = '50px';
        dom.heat.style.opacity  = '0.6';
        dom.gGlow.style.opacity = '0.4';
        dom.rocket.style.filter = 'drop-shadow(0 0 15px rgba(255,140,40,.6))';
        setTelemetry('0 KM', '0 M/S', '30%', 'IGNITION');

        smoke.setActive(true);
        smoke.burst(30, { spread:120, minR:20, maxR:60,  vyB:0.6, vyR:0.5, vxR:1.8, minL:600, maxL:1200, alpha:0.55, grow:0.25 });
        smoke.trickle(150, { spread:100, minR:18, maxR:50, vyB:0.5, vyR:0.4, vxR:1.5, minL:500, maxL:1000, alpha:0.50, grow:0.22 });
      }

      if (i === 1) {
        // Full thrust — heavier smoke
        dom.pCore.style.height  = '80px';
        dom.pOuter.style.height = '120px';
        dom.gGlow.style.opacity = '0.9';
        dom.heat.style.opacity  = '1';
        dom.rocket.style.filter = 'drop-shadow(0 0 30px rgba(255,140,40,.9))';
        setTelemetry('0 KM', '0 M/S', '100%', 'MAX THRUST');

        smoke.stopTrickle();
        smoke.burst(60, { spread:300, minR:40, maxR:100, vyB:0.9, vyR:0.8, vxR:3.0, minL:700, maxL:1400, alpha:0.65, grow:0.40 });
        smoke.trickle(80, { spread:250, minR:35, maxR:90, vyB:0.8, vyR:0.7, vxR:2.5, minL:600, maxL:1200, alpha:0.60, grow:0.35 });
      }

      await wait(900);
    }

    // ── T-0: LIFTOFF ──────────────────────────────────────────────
    dom.cdn.style.opacity = '0';
    dom.cdl.style.opacity = '0';
    dom.mnm.style.opacity = '0';

    dom.flash.style.transition = 'opacity .08s ease';
    dom.flash.style.opacity    = '0.8';
    await wait(80);
    dom.flash.style.opacity    = '0';

    // Liftoff smoke explosion
    smoke.burst(120, { spread:MP.W*0.8, arc:Math.PI*1.5, minR:60, maxR:160, vyB:1.5, vyR:1.8, vxR:5.0, minL:800, maxL:1600, alpha:0.80, grow:0.70 });
    smoke.burstAt(40, MP.W/2 - 100, MP.H - 160, { angle:Math.PI, angleSpread:0.8, vyB:0.5, vyR:0.8, vxR:3.0, minR:50, maxR:140, minL:700, maxL:1400, alpha:0.70, grow:0.50 });
    smoke.burstAt(40, MP.W/2 + 100, MP.H - 160, { angle:0,       angleSpread:0.8, vyB:0.5, vyR:0.8, vxR:3.0, minR:50, maxR:140, minL:700, maxL:1400, alpha:0.70, grow:0.50 });

    dom.ltx.style.opacity    = '1';
    dom.ltx.style.transition = 'opacity .3s ease';
    await wait(700);
    dom.ltx.style.opacity    = '0';

    dom.pCore.style.height  = '140px';
    dom.pOuter.style.height = '200px';

    smoke.stopTrickle();
    smoke.trickle(40, { spread:MP.W*0.5, minR:50, maxR:140, vyB:1.2, vyR:1.0, vxR:4.0, minL:800, maxL:1500, alpha:0.75, grow:0.55 });

    // ── ASCENT — smoke fills screen progressively ─────────────────
    let   t0             = null;
    let   lastSmokeBurst = 0;
    const ASCENT_DUR     = 4500;

    function ascendFrame(ts) {
      if (!t0) t0 = ts;
      const t = Math.min((ts - t0) / ASCENT_DUR, 1);
      const e = t < 0.5 ? 2 * t * t : -1 + (4 - 2 * t) * t; // ease in-out
      const ra = e * (MP.H + 600);

      dom.rocket.style.transform = `translateX(-50%) translateY(-${ra}px)`;
      MP.launchProgress = e;
      MP.streakMode     = e > 0.3;

      const ph = 140 + e * 120;
      dom.pCore.style.height  = ph + 'px';
      dom.pOuter.style.height = (ph * 1.5) + 'px';
      dom.plume.style.bottom  = (164 + ra) + 'px';

      dom.gGlow.style.opacity = String(Math.max(0, 0.9 - e * 1.5));
      dom.heat.style.opacity  = String(Math.max(0, 1 - e * 2));

      const rb = Math.floor(e * 5), gb = Math.floor(20 + e * 15), bb = Math.floor(40 + e * 60);
      dom.atm.style.background = `radial-gradient(ellipse 120% 60% at 50% 100%,rgba(${rb},${gb},${bb},${e * 0.3}) 0%,transparent 100%)`;
      setTelemetry(`${(e * 80).toFixed(1)} KM`, `${Math.floor(e * 1800)} M/S`, '100%', e > 0.6 ? 'MECO' : 'ASCENT');

      // Progressive smoke fill — every 300ms during ascent
      if (ts - lastSmokeBurst > 300) {
        lastSmokeBurst = ts;
        const smokeY  = MP.H - 160 - (e * MP.H * 0.3);
        const spread  = 200 + e * MP.W * 0.6;
        const puffMax = 60 + e * 120;

        smoke.burstAt(
          Math.floor(15 + e * 30),
          MP.W / 2 + (Math.random() - 0.5) * spread * 0.3,
          smokeY,
          { spread, arc:Math.PI*1.2, minR:puffMax*0.5, maxR:puffMax, vyB:0.8+e*1.5, vyR:0.6+e, vxR:2+e*4, minL:800, maxL:1800, alpha:0.60+e*0.25, grow:0.3+e*0.5 }
        );

        // Seed upper screen after 40% ascent
        if (e > 0.4) {
          const upperCount = Math.floor((e - 0.4) * 40);
          for (let j = 0; j < upperCount; j++) {
            smoke.pushParticle(new smoke.Particle({
              ox: Math.random() * MP.W,
              oy: Math.random() * MP.H * 0.6,
              angle: Math.random() * Math.PI * 2,
              angleSpread: 0.5,
              vyB:0.1, vyR:0.3, vxR:0.5,
              minR:80+e*80, maxR:200+e*120,
              minL:700, maxL:1500,
              alpha:0.50+e*0.30, grow:0.15+e*0.2
            }));
          }
        }

        // Corner fill after 70% ascent — eliminate dark gaps
        if (e > 0.7) {
          const cf = Math.floor((e - 0.7) * 20);
          [[0,0],[MP.W,0],[0,MP.H*0.5],[MP.W,MP.H*0.5]].forEach(([cx,cy]) => {
            smoke.burstAt(cf, cx, cy, {
              angle:Math.PI/2, angleSpread:Math.PI,
              vyB:0.1, vyR:0.3, vxR:0.5,
              minR:120, maxR:280,
              minL:600, maxL:1400,
              alpha:0.65+e*0.2, grow:0.15
            });
          });
        }
      }

      if (t < 1) {
        requestAnimationFrame(ascendFrame);
      } else {
        onRocketGone();
      }
    }

    requestAnimationFrame(ascendFrame);
  }

  // ── POST-LAUNCH ────────────────────────────────────────────────
  async function onRocketGone() {
    smoke.stopTrickle();
    smoke.setActive(false);
    MP.streakMode     = false;
    MP.launchProgress = 0;

    dom.plume.style.transition = 'opacity .2s ease';
    dom.plume.style.opacity    = '0';

    // ── FULL SCREEN WHITE SMOKE WALL ─────────────────────────────
    // Wave 1: massive upward blast from pad base — fills bottom two-thirds
    smoke.burst(200, {
      spread: MP.W * 1.4, arc: Math.PI * 1.6,
      minR: 80, maxR: 220,
      vyB: 2.5, vyR: 3.0, vxR: 7.0,
      minL: 500, maxL: 900,
      alpha: 0.90, grow: 1.0
    });

    // Wave 2: seed entire viewport with large slow drifters
    smoke.fillScreen(150, 0.88, 400, 800);

    // Wave 3: explicit top-half coverage — the area visible in screenshot as dark
    for (let i = 0; i < 60; i++) {
      smoke.pushParticle(new smoke.Particle({
        ox:    Math.random() * MP.W,
        oy:    Math.random() * MP.H * 0.4, // top 40% of screen
        angle: Math.random() * Math.PI * 2,
        angleSpread: 0.5,
        vyB: 0.05, vyR: 0.1, vxR: 0.3,
        minR: 150, maxR: 380,
        minL: 400, maxL: 800,
        alpha: 0.85, grow: 0.10
      }));
    }

    // Wave 4: corner fills — guarantee no dark gaps at edges
    [[0, 0], [MP.W, 0], [0, MP.H * 0.5], [MP.W, MP.H * 0.5], [MP.W / 2, 0]].forEach(([cx, cy]) => {
      smoke.burstAt(25, cx, cy, {
        angle: Math.PI / 2, angleSpread: Math.PI * 1.8,
        vyB: 0.1, vyR: 0.3, vxR: 0.5,
        minR: 160, maxR: 360,
        minL: 400, maxL: 800,
        alpha: 0.82, grow: 0.14
      });
    });

    // Hide all splash UI — cancel CSS animations first to bypass forwards fill
    const hideAll = [dom.scene, dom.hud, dom.telemetry, dom.missionData, dom.scanlines];
    hideAll.forEach(el => hideEl(el, 0));
    document.querySelectorAll('.corner, #countdown-wrap, #liftoff-text, #atmosphere').forEach(el => hideEl(el, 0));

    // Fade starfield behind the smoke
    MP.sfC.style.transition = 'opacity 1s ease';
    MP.sfC.style.opacity    = '0';
    setTimeout(() => { MP.sfC.style.display = 'none'; }, 1100);

    // Hold briefly while screen is fully covered, then clear
    await wait(600);
    smoke.startClearing();

    // startClearing() has no completion callback (particles just fade/blow
    // away over time) — give it a generous fixed window to finish visually,
    // then stop the loop. Neither canvas is visible after this point.
    await wait(4000);
    stopLoop();
  }

  // ── SESSION CHECK ──────────────────────────────────────────────
  // Show splash only once per browser session.
  // sessionStorage clears when the tab is closed so user gets
  // the splash again on their next visit.
  // To re-test manually: sessionStorage.removeItem('mp_splash_seen') in DevTools console.
  const splashSeen = sessionStorage.getItem('mp_splash_seen');

  // ── SKIP SPLASH (returning visitors) ───────────────────────────
  function skipSplash() {
    // Nothing the loop draws is visible once we skip straight to the
    // landing page — stop it immediately rather than let it run forever.
    stopLoop();

    // Restore cursor immediately — splash CSS sets cursor:none on body
    document.body.style.cursor = 'default';

    // Hide all splash elements immediately — no animation
    var splashIds = ['scene','hud','scanlines','atmosphere','countdown-wrap','liftoff-text','flash'];
    splashIds.forEach(function(id) {
      var el = document.getElementById(id);
      if (el) { el.style.display = 'none'; }
    });
    document.querySelectorAll('.corner, .telemetry, .mission-data').forEach(function(el) {
      el.style.display = 'none';
    });
    if (MP.sfC) MP.sfC.style.display = 'none';
    if (MP.smC) MP.smC.style.display = 'none';

    // Show landing page instantly with no transition
    var lp = document.getElementById('landing-page');
    if (lp) {
      lp.style.transition    = 'none';
      lp.style.opacity       = '1';
      lp.style.pointerEvents = 'all';
    }
    ['lpNav','lpTicker','lpMain'].forEach(function(id) {
      var el = document.getElementById(id);
      if (el) { el.style.transition = 'none'; el.style.opacity = '1'; }
    });
  }

  // ── START ──────────────────────────────────────────────────────
  // smoke assignment registered first — runs before runSequence on load.
  requestAnimationFrame(loop);
    window.addEventListener('load', function() {
      smoke = MP.smoke;
      if (!window.MP_SPLASH_ENABLED || splashSeen) {
        skipSplash();
      } else {
        sessionStorage.setItem('mp_splash_seen', '1');
        runSequence();
      }
    });

})();
