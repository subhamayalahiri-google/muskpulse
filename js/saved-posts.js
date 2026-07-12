/**
 * saved-posts.js
 * Renders the Saved Posts view (page-saved-posts.php) entirely client-side:
 * reads bookmarked post IDs from localStorage, fetches them via the WP
 * REST API, and builds .archive-card markup — the same card component used
 * on the front page and Mission Feed / category archives — so styling
 * stays visually consistent everywhere, with Share/Save added in.
 */

(function () {
  'use strict';

  var SAVE_KEY   = 'mp_saved_posts';
  var CHUNK_SIZE = 25; // avoid overly long ?include= query strings
  var CAT_MAP    = {
    'spacex-ipo':  ['tag-spacex', '#00c8ff'],
    'tesla-news':  ['tag-tesla',  '#f5a623'],
    'xai-optimus': ['tag-xai',    '#cc88ff']
  };
  var DEFAULT_MAP = ['tag-invest', '#00ff88'];

  var loadingEl = document.getElementById('spLoading');
  var feedEl    = document.getElementById('spFeed');
  var emptyEl   = document.getElementById('spEmpty');
  if (!feedEl) return;

  // ── localStorage helpers (same key/shape as front-page.php's inline script) ──
  function getSaved() {
    try { return JSON.parse(localStorage.getItem(SAVE_KEY)) || []; }
    catch (e) { return []; }
  }
  function setSaved(arr) {
    try { localStorage.setItem(SAVE_KEY, JSON.stringify(arr)); } catch (e) {}
  }

  function showEmpty() {
    if (loadingEl) loadingEl.style.display = 'none';
    if (emptyEl)   emptyEl.style.display   = '';
  }

  function hideLoading() {
    if (loadingEl) loadingEl.style.display = 'none';
  }

  // ── formatting helpers ──────────────────────────────────────────
  function timeAgo(dateStr) {
    var diffMs = Date.now() - new Date(dateStr).getTime();
    var mins   = Math.max(1, Math.round(diffMs / 60000));
    if (mins < 60) return mins + (mins === 1 ? ' minute ago' : ' minutes ago');
    var hours = Math.round(mins / 60);
    if (hours < 24) return hours + (hours === 1 ? ' hour ago' : ' hours ago');
    var days = Math.round(hours / 24);
    return days + (days === 1 ? ' day ago' : ' days ago');
  }

  function readingTime(html) {
    var text  = (html || '').replace(/<[^>]*>/g, ' ');
    var words = text.trim().split(/\s+/).filter(Boolean).length;
    return Math.max(1, Math.ceil(words / 200));
  }

  function decodeEntities(str) {
    var el = document.createElement('textarea');
    el.innerHTML = str || '';
    return el.value;
  }

  function chunk(arr, size) {
    var out = [];
    for (var i = 0; i < arr.length; i += size) out.push(arr.slice(i, i + size));
    return out;
  }

  // ── card builder ─────────────────────────────────────────────────
  function buildCard(post, num) {
    var terms   = (post._embedded && post._embedded['wp:term']) ? post._embedded['wp:term'] : [];
    var cats    = terms.length ? terms[0].filter(function (t) { return t.taxonomy === 'category'; }) : [];
    var catName = cats.length ? cats[0].name : 'Tesla News';
    var map     = (cats.length && CAT_MAP[cats[0].slug]) ? CAT_MAP[cats[0].slug] : DEFAULT_MAP;

    var title   = decodeEntities(post.title.rendered);
    var preview = decodeEntities((post.excerpt.rendered || '').replace(/<[^>]*>/g, '').trim());
    var readMin = readingTime(post.content.rendered);
    var numStr  = String(num).padStart(2, '0');

    var card = document.createElement('article');
    card.className = 'lp-sf-card archive-card';
    card.dataset.url = post.link;
    card.dataset.id  = post.id;

    card.innerHTML =
      '<div class="archive-card-inner">' +
        '<div class="archive-card-num">' + numStr + '</div>' +
        '<div class="archive-card-tag ' + map[0] + '">' +
          '<span class="dot"></span>' + catName +
        '</div>' +
        '<a class="archive-card-title" href="' + post.link + '">' + title + '</a>' +
        '<p class="archive-card-excerpt">' + preview + '</p>' +
        '<div class="archive-card-meta">' +
          '<span>' + timeAgo(post.date) + '</span>' +
          '<span>' + readMin + ' min read</span>' +
          '<button class="lp-sf-action lp-sf-share" type="button">' +
            '<span class="lp-sf-ico">↗</span> Share' +
          '</button>' +
          '<button class="lp-sf-action lp-sf-save saved" type="button">' +
            '<span class="lp-sf-ico">◇</span> <span class="lp-sf-save-label">Saved</span>' +
          '</button>' +
          '<a class="archive-card-read" href="' + post.link + '">Read →</a>' +
        '</div>' +
      '</div>' +
      '<div class="archive-card-accent" style="--card-accent:' + map[1] + '"></div>';

    return card;
  }

  // ── unsave / share interactions (delegated) ─────────────────────
  feedEl.addEventListener('click', function (e) {
    var shareBtn = e.target.closest('.lp-sf-share');
    var saveBtn  = e.target.closest('.lp-sf-save');
    if (!shareBtn && !saveBtn) return;

    var card = e.target.closest('.lp-sf-card');
    var url  = card.dataset.url;

    if (shareBtn) {
      var title = card.querySelector('.archive-card-title').textContent;
      if (window.MPSharePopover) window.MPSharePopover.toggle(shareBtn, url, title);
    }

    if (saveBtn) {
      var id  = card.dataset.id;
      var arr = getSaved();
      var idx = arr.indexOf(id);
      if (idx !== -1) arr.splice(idx, 1);
      setSaved(arr);

      card.classList.add('sp-removing');
      card.addEventListener('transitionend', function () {
        card.remove();
        if (!feedEl.querySelector('.lp-sf-card')) showEmpty();
      }, { once: true });
    }
  });

  // ── load ─────────────────────────────────────────────────────────
  var ids = getSaved();
  if (!ids.length) {
    showEmpty();
    return;
  }

  var batches = chunk(ids, CHUNK_SIZE);
  Promise.all(batches.map(function (batchIds) {
    var url = '/wp-json/wp/v2/posts?include=' + batchIds.join(',') +
      '&per_page=' + batchIds.length + '&orderby=include&_embed=1';
    return fetch(url)
      .then(function (r) { return r.ok ? r.json() : []; })
      .catch(function () { return null; }); // null = network failure, not "post gone"
  })).then(function (results) {
    var posts = [];
    var anyFailure = false;

    results.forEach(function (result) {
      if (result === null) { anyFailure = true; return; }
      posts = posts.concat(result);
    });

    posts.forEach(function (post, idx) {
      feedEl.appendChild(buildCard(post, idx + 1));
    });

    // Only prune IDs confirmed missing from a successful response —
    // never on a network failure, so an offline moment doesn't wipe saves.
    if (!anyFailure) {
      var foundIds = posts.map(function (p) { return String(p.id); });
      var pruned   = ids.filter(function (id) { return foundIds.indexOf(String(id)) !== -1; });
      if (pruned.length !== ids.length) setSaved(pruned);
    }

    hideLoading();
    if (!posts.length) showEmpty();
  });

})();
