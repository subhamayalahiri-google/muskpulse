/**
 * share-popover.js
 * Shared "Share to..." popover used by card-actions.js and saved-posts.js
 * whenever a .lp-sf-share button is clicked. Exposes window.MPSharePopover.
 *
 * Appended to <body> as position:fixed (rather than nested inside the
 * card) so it isn't clipped by .archive-card's overflow:hidden.
 */

(function () {
  'use strict';

  var current = null;

  function close() {
    if (current) { current.remove(); current = null; }
    document.removeEventListener('click', onOutsideClick, true);
    window.removeEventListener('scroll', close, true);
    window.removeEventListener('resize', close);
  }

  function onOutsideClick(e) {
    if (current && !current.contains(e.target)) close();
  }

  function badgeGlyph(key) {
    if (key === 'facebook') return 'f';
    if (key === 'x')        return '𝕏';
    if (key === 'linkedin') return 'in';
    return '';
  }

  function platforms(url, title) {
    var u = encodeURIComponent(url);
    var t = encodeURIComponent(title);
    return [
      { key: 'facebook', label: 'Facebook', href: 'https://www.facebook.com/sharer/sharer.php?u=' + u },
      { key: 'x',        label: 'X',        href: 'https://twitter.com/intent/tweet?url=' + u + '&text=' + t },
      { key: 'linkedin', label: 'LinkedIn', href: 'https://www.linkedin.com/sharing/share-offsite/?url=' + u }
    ];
  }

  function build(url, title) {
    var pop = document.createElement('div');
    pop.className = 'lp-share-pop';

    platforms(url, title).forEach(function (p) {
      var a = document.createElement('a');
      a.className = 'lp-share-opt';
      a.href = p.href;
      a.target = '_blank';
      a.rel = 'noopener noreferrer';
      a.innerHTML = '<span class="lp-share-badge ' + p.key + '">' + badgeGlyph(p.key) + '</span>' + p.label;
      a.addEventListener('click', close);
      pop.appendChild(a);
    });

    var copyBtn = document.createElement('button');
    copyBtn.type = 'button';
    copyBtn.className = 'lp-share-opt lp-share-copy';
    copyBtn.innerHTML = '<span class="lp-share-badge copy">⧉</span>Copy Link';
    copyBtn.addEventListener('click', function () {
      if (!navigator.clipboard) { close(); return; }
      navigator.clipboard.writeText(url).then(function () {
        copyBtn.innerHTML = '<span class="lp-share-badge copy">✓</span>Copied';
        setTimeout(close, 1000);
      });
    });
    pop.appendChild(copyBtn);

    // Native OS share sheet (Messages, WhatsApp, Instagram, Mail, AirDrop,
    // etc.) — only offered where the browser actually supports it. Placed
    // last since Facebook/X/LinkedIn/Copy Link are the primary options.
    if (navigator.share) {
      var moreBtn = document.createElement('button');
      moreBtn.type = 'button';
      moreBtn.className = 'lp-share-opt';
      moreBtn.innerHTML = '<span class="lp-share-badge more">↗</span>More options…';
      moreBtn.addEventListener('click', function () {
        close();
        navigator.share({ title: title, url: url }).catch(function () {});
      });
      pop.appendChild(moreBtn);
    }

    return pop;
  }

  function toggle(button, url, title) {
    if (current) { close(); return; }

    var pop = build(url, title);
    pop.style.visibility = 'hidden';
    document.body.appendChild(pop);

    var r       = button.getBoundingClientRect();
    var popRect = pop.getBoundingClientRect();
    var top  = r.bottom + 6;
    var left = r.left;
    if (top + popRect.height > window.innerHeight)  top  = r.top - popRect.height - 6;
    if (left + popRect.width > window.innerWidth)   left = window.innerWidth - popRect.width - 8;
    if (left < 8) left = 8;

    pop.style.top        = top + 'px';
    pop.style.left       = left + 'px';
    pop.style.visibility = '';

    current = pop;
    setTimeout(function () { document.addEventListener('click', onOutsideClick, true); }, 0);
    window.addEventListener('scroll', close, true);
    window.addEventListener('resize', close);
  }

  window.MPSharePopover = { toggle: toggle, close: close };
})();
