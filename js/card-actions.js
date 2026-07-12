/**
 * card-actions.js
 * Shared Share/Save behavior for any .lp-sf-card on the page — used by the
 * front page, Mission Feed, and category archives (all server-rendered
 * .archive-card grids). The Saved Posts view has its own self-contained
 * script (js/saved-posts.js) since it builds cards from a REST fetch
 * rather than server-rendered markup.
 */

(function () {
  'use strict';

  var SAVE_KEY = 'mp_saved_posts';

  function getSaved() {
    try { return JSON.parse(localStorage.getItem(SAVE_KEY)) || []; }
    catch (e) { return []; }
  }
  function setSaved(arr) {
    try { localStorage.setItem(SAVE_KEY, JSON.stringify(arr)); } catch (e) {}
  }

  function markSaved() {
    var saved = getSaved();
    document.querySelectorAll('.lp-sf-card').forEach(function (card) {
      if (saved.indexOf(card.dataset.id) !== -1) {
        var btn = card.querySelector('.lp-sf-save');
        var lbl = card.querySelector('.lp-sf-save-label');
        if (btn) btn.classList.add('saved');
        if (lbl) lbl.textContent = 'Saved';
      }
    });
  }

  document.addEventListener('click', function (e) {
    var shareBtn = e.target.closest('.lp-sf-share');
    var saveBtn  = e.target.closest('.lp-sf-save');
    if (!shareBtn && !saveBtn) return;

    var card = e.target.closest('.lp-sf-card');
    if (!card) return;
    var url = card.dataset.url;

    if (shareBtn) {
      var title = card.querySelector('.archive-card-title').textContent;
      if (navigator.share) {
        navigator.share({ title: title, url: url }).catch(function () {});
      } else if (navigator.clipboard) {
        navigator.clipboard.writeText(url).then(function () {
          shareBtn.innerHTML = '<span class="lp-sf-ico">✓</span> Copied';
          setTimeout(function () {
            shareBtn.innerHTML = '<span class="lp-sf-ico">↗</span> Share';
          }, 1800);
        });
      }
    }

    if (saveBtn) {
      var id  = card.dataset.id;
      var arr = getSaved();
      var idx = arr.indexOf(id);
      var lbl = saveBtn.querySelector('.lp-sf-save-label');
      if (idx === -1) {
        arr.push(id);
        saveBtn.classList.add('saved');
        if (lbl) lbl.textContent = 'Saved';
      } else {
        arr.splice(idx, 1);
        saveBtn.classList.remove('saved');
        if (lbl) lbl.textContent = 'Save';
      }
      setSaved(arr);
    }
  });

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', markSaved);
  } else {
    markSaved();
  }
})();
