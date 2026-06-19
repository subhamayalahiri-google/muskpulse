/**
 * stock.js
 * Live TSLA stock price — fetches from MuskPulse server-side proxy.
 * API key lives in wp-content/mu-plugins/muskpulse-api.php — never exposed here.
 *
 * Proxy endpoint: /wp-json/muskpulse/v1/tsla
 * Updates every 5s during market hours, every 60s outside hours.
 */

(function () {
  'use strict';

  // Own server endpoint — API key is server-side only
  var ENDPOINT  = '/wp-json/muskpulse/v1/tsla';
  var INTERVAL  = 5000;  // ms during market hours
  var IDLE      = 60000; // ms outside market hours

  // Last known values — used as fallback if fetch fails
  var last = { price: null, change: null, pct: null, open: false };

  // ── MARKET HOURS CHECK ────────────────────────────────────────
  function isMarketOpen() {
    var now = new Date();
    var day = now.getUTCDay(); // 0=Sun, 6=Sat
    if (day === 0 || day === 6) return false;

    // Convert UTC to ET (UTC-4 in EDT, UTC-5 in EST)
    // Simplified: use UTC-4 (EDT, Mar–Nov). Close enough for display purposes.
    var etOffset = -4;
    var etHour   = now.getUTCHours() + etOffset;
    var etMin    = now.getUTCMinutes();
    var etTime   = etHour + etMin / 60;

    return etTime >= 9.5 && etTime < 16; // 9:30am to 4:00pm
  }

  // ── DOM UPDATERS ──────────────────────────────────────────────
  function setAll(selector, text) {
    document.querySelectorAll(selector).forEach(function (el) {
      el.textContent = text;
    });
  }

  function updateDOM(price, change, pct, marketOpen) {
    var priceStr  = '$' + price.toFixed(2);
    var sign      = change >= 0 ? '+' : '';
    var changeStr = sign + change.toFixed(2);
    var pctStr    = sign + pct.toFixed(2) + '%';
    var isUp      = change >= 0;

    // Price elements
    setAll('.mp-tsla-price', priceStr);

    // Change elements — also toggle up/neg class
    document.querySelectorAll('.mp-tsla-change').forEach(function (el) {
      el.textContent = changeStr;
      el.className   = el.className.replace(/\b(pos|neg)\b/g, '').trim();
      el.classList.add(isUp ? 'pos' : 'neg');
    });

    // Percent elements
    document.querySelectorAll('.mp-tsla-pct').forEach(function (el) {
      el.textContent = (isUp ? '▲ ' : '▼ ') + pct.toFixed(2) + '%';
      el.className   = el.className.replace(/\b(pos|neg)\b/g, '').trim();
      el.classList.add(isUp ? 'pos' : 'neg');
    });

    // Combined price + pct (ticker format: "$428.50 ▲ +3.2%")
    document.querySelectorAll('.mp-tsla-ticker').forEach(function (el) {
      el.innerHTML = priceStr + ' <span class="' + (isUp ? 'up' : 'down') + '">'
        + (isUp ? '▲' : '▼') + ' ' + sign + pct.toFixed(2) + '%</span>';
    });

    // Status badge — inline styles used to override CSS colour inheritance from parent elements
    var statusText  = marketOpen ? '● LIVE' : '○ MARKET CLOSED';
    var statusColor = marketOpen ? '#00ff88' : '#4a6070';
    var statusBg    = marketOpen ? 'rgba(0,255,136,0.05)' : 'rgba(74,96,112,0.05)';
    var statusBdr   = marketOpen ? 'rgba(0,255,136,0.3)'  : 'rgba(74,96,112,0.3)';

    document.querySelectorAll('.mp-tsla-status').forEach(function (el) {
      el.textContent          = statusText;
      el.style.color          = statusColor;
      el.style.background     = statusBg;
      el.style.borderColor    = statusBdr;
      el.className = el.className.replace(/\bmp-market-(open|closed)\b/g, '').trim();
      el.classList.add(marketOpen ? 'mp-market-open' : 'mp-market-closed');
    });
  }

  // ── FETCH ─────────────────────────────────────────────────────
  function fetchPrice() {
    fetch(ENDPOINT)
      .then(function (r) {
        if (!r.ok) throw new Error('HTTP ' + r.status);
        return r.json();
      })
      .then(function (data) {
        // Finnhub quote fields:
        // c = current price, d = change, dp = percent change, pc = previous close
        var price  = data.c  || data.pc || last.price;
        var change = data.d  !== undefined ? data.d  : (last.change || 0);
        var pct    = data.dp !== undefined ? data.dp : (last.pct    || 0);

        if (!price) return; // skip if no data yet

        last = { price: price, change: change, pct: pct, open: isMarketOpen() };
        updateDOM(price, change, pct, last.open);
      })
      .catch(function () {
        // Fetch failed — keep showing last known values, do nothing
      });
  }


  var ENDPOINT_SPCX = '/wp-json/muskpulse/v1/spcx';

    function fetchSPCXPrice() {
      fetch(ENDPOINT_SPCX)
        .then(function (r) {
          if (!r.ok) throw new Error('HTTP ' + r.status);
          return r.json();
        })
        .then(function (data) {
          if (!data.c) return; // not trading yet — keep static fallback price
    
          var price  = data.c;
          var change = data.d  || 0;
          var pct    = data.dp || 0;
          var isUp   = change >= 0;
    
          document.querySelectorAll('.mp-spcx-price').forEach(function (el) {
            el.textContent = '$' + price.toFixed(2);
          });
          document.querySelectorAll('.mp-spcx-pct').forEach(function (el) {
            el.textContent = (isUp ? '▲ ' : '▼ ') + (isUp ? '+' : '') + pct.toFixed(2) + '%';
            el.className = el.className.replace(/\b(pos|neg)\b/g, '').trim();
            el.classList.add(isUp ? 'pos' : 'neg');
          });
        })
        .catch(function () {});
    }
    
    
  var ENDPOINT_XOVR = '/wp-json/muskpulse/v1/xovr';
    
    function fetchXOVRPrice() {
      fetch(ENDPOINT_XOVR)
        .then(function(r) { if (!r.ok) throw new Error(); return r.json(); })
        .then(function(data) {
          if (!data.c) return;
          var isUp = data.d >= 0;
          document.querySelectorAll('.mp-xovr-price').forEach(function(el) {
            el.textContent = '$' + data.c.toFixed(2);
          });
          document.querySelectorAll('.mp-xovr-pct').forEach(function(el) {
            el.textContent = (isUp ? '▲ +' : '▼ ') + Math.abs(data.dp).toFixed(2) + '%';
            el.className = el.className.replace(/\b(pos|neg)\b/g, '').trim();
            el.classList.add(isUp ? 'pos' : 'neg');
          });
        })
        .catch(function() {});
    }    
  // ── SCHEDULER ─────────────────────────────────────────────────
  // Fetch immediately on load, then on adaptive interval
  var timer = null;

  function schedule() {
    clearTimeout(timer);
    fetchPrice();
    fetchSPCXPrice(); 
    fetchXOVRPrice();
    timer = setTimeout(schedule, isMarketOpen() ? INTERVAL : IDLE);
  }

  // Start when DOM is ready
  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', schedule);
  } else {
    schedule();
  }

})();
