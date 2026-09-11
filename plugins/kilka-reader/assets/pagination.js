/* Optional pagination of the original DOM. No copies, storage or requests. */
(function () {
  'use strict';
  window.kilkaReaderPagination = function (reader, surface, body) {
    var mode = reader.querySelector('.kilka-reader-mode');
    var nav = reader.querySelector('.kilka-reader-pages');
    if (!mode || !nav || !window.ResizeObserver) return null;
    var viewport = document.createElement('div');
    viewport.className = 'kilka-reader-viewport';
    body.before(viewport);
    viewport.append(body);
    var article = reader.closest('article');
    var heading = article && (article.querySelector('.kilka-reading__header') || article.querySelector('h1'));
    var headingMarker = document.createComment('Reader heading position');
    var enabled = false, page = 0, count = 1, stride = 1, place = null, frame;
    var previous = nav.querySelector('[data-reader-turn="previous"]');
    var next = nav.querySelector('[data-reader-turn="next"]');
    var number = nav.querySelector('.kilka-reader-page-number');
    var interactive = 'a, button, input, textarea, select, summary, [role="button"], [contenteditable]';
    function selected() { var s = getSelection(); return s && !s.isCollapsed; }
    function rangeAt(mark) {
      if (!mark || !mark.node.isConnected) return null;
      var r = document.createRange();
      r.setStart(mark.node, Math.min(mark.offset, mark.node.length - 1));
      r.setEnd(mark.node, Math.min(mark.offset + 1, mark.node.length));
      return r;
    }
    // Locate a text character, rather than a paragraph which may span many pages.
    function capture() {
      var box = enabled ? viewport.getBoundingClientRect() : surface.getBoundingClientRect();
      var walker = document.createTreeWalker(body, NodeFilter.SHOW_TEXT);
      var node;
      while ((node = walker.nextNode())) {
        if (!node.textContent.trim() || node.parentElement.closest('script, style, [hidden]')) continue;
        var r = document.createRange(); r.selectNodeContents(node);
        var visible = Array.from(r.getClientRects()).some(function (b) {
          return b.bottom > box.top + 1 && b.top < box.bottom && b.right > box.left && b.left < box.right;
        });
        if (!visible) continue;
        // Binary search the first character on this page (linear DOM order).
        var low = 0, high = node.length - 1;
        while (low < high) {
          var mid = Math.floor((low + high) / 2);
          var b = rangeAt({node: node, offset: mid}).getBoundingClientRect();
          var before = enabled ? b.right <= box.left : b.bottom <= box.top + 1;
          if (before) low = mid + 1; else high = mid;
        }
        return {node: node, offset: low};
      }
      return null;
    }
    function update() {
      previous.disabled = page === 0;
      next.disabled = page >= count - 1;
      number.textContent = (page + 1) + ' / ' + count;
      number.setAttribute('aria-label', number.dataset.label.replace('%1$s', page + 1).replace('%2$s', count));
    }
    function go(target, remember) {
      page = Math.max(0, Math.min(count - 1, target));
      viewport.scrollLeft = page * stride;
      viewport.scrollTop = 0;
      surface.scrollTop = 0;
      update();
      if (remember !== false) place = capture();
    }
    function reflow(mark) {
      if (!enabled || window.matchMedia('print').matches) return;
      if (mark) place = mark;
      var css = getComputedStyle(surface);
      var height = Math.floor(surface.clientHeight - parseFloat(css.paddingTop) - parseFloat(css.paddingBottom));
      if (height < 100) { setMode(false); return; }
      viewport.style.width = Math.floor(reader.getBoundingClientRect().width) + 'px';
      viewport.style.setProperty('--reader-page-height', height + 'px');
      stride = viewport.clientWidth + 48;
      count = Math.max(1, Math.round((viewport.scrollWidth + 48) / stride));
      var r = rangeAt(place);
      var target = r ? Math.floor((r.getBoundingClientRect().left - viewport.getBoundingClientRect().left + viewport.scrollLeft + 1) / stride) : page;
      go(target, false);
    }
    function setMode(paged) {
      var mark = enabled ? place || capture() : capture();
      if (paged === enabled) return;
      enabled = paged;
      if (enabled && heading) { heading.before(headingMarker); body.prepend(heading); }
      if (!enabled && headingMarker.isConnected) { headingMarker.replaceWith(heading); }
      surface.dataset.readerMode = enabled ? 'pages' : 'scroll';
      nav.hidden = !enabled;
      mode.querySelectorAll('button').forEach(function (b) { b.setAttribute('aria-pressed', String((b.dataset.readerMode === 'pages') === enabled)); });
      if (enabled) {
        place = mark;
        reflow();
      } else {
        viewport.scrollLeft = 0;
        viewport.style.removeProperty('--reader-page-height');
        viewport.style.removeProperty('width');
        var r = rangeAt(mark);
        if (r) surface.scrollTop += r.getBoundingClientRect().top - surface.getBoundingClientRect().top - 24;
      }
    }
    mode.addEventListener('click', function (e) {
      var button = e.target.closest('[data-reader-mode]');
      if (button) setMode(button.dataset.readerMode === 'pages');
    });
    nav.addEventListener('click', function (e) {
      var button = e.target.closest('[data-reader-turn]');
      if (button) go(page + (button.dataset.readerTurn === 'next' ? 1 : -1));
    });
    document.addEventListener('keydown', function (e) {
      if (!enabled || e.defaultPrevented || e.altKey || e.ctrlKey || e.metaKey || selected()) return;
      if ((e.target.closest(interactive) && !nav.contains(e.target)) || !reader.querySelector('#kilka-reader-settings').hidden) return;
      var delta = {ArrowRight: 1, PageDown: 1, ArrowLeft: -1, PageUp: -1}[e.key];
      if (!delta) return;
      e.preventDefault(); go(page + delta);
    });
    var touch = null;
    viewport.addEventListener('touchstart', function (e) {
      touch = enabled && e.touches.length === 1 && !selected() && !e.target.closest(interactive)
        ? {x: e.touches[0].clientX, y: e.touches[0].clientY, time: Date.now()} : null;
    }, {passive: true});
    viewport.addEventListener('touchcancel', function () { touch = null; }, {passive: true});
    viewport.addEventListener('touchend', function (e) {
      if (!touch || e.touches.length || selected()) { touch = null; return; }
      var dx = e.changedTouches[0].clientX - touch.x, dy = e.changedTouches[0].clientY - touch.y;
      if (Date.now() - touch.time < 800 && Math.abs(dx) > 60 && Math.abs(dx) > Math.abs(dy) * 1.5) {
        e.preventDefault(); go(page + (dx < 0 ? 1 : -1));
      }
      touch = null;
    }, {passive: false});
    // Keyboard focus / fragment links may scroll an offscreen column into view.
    viewport.addEventListener('scroll', function () {
      if (!enabled) return;
      var target = Math.round(viewport.scrollLeft / stride);
      if (target !== page) go(target);
    }, {passive: true});
    body.addEventListener('focusin', function (e) {
      if (!enabled) return;
      var b = e.target.getClientRects()[0] || e.target.getBoundingClientRect();
      go(Math.floor((b.left - viewport.getBoundingClientRect().left + viewport.scrollLeft + 1) / stride));
    });
    new ResizeObserver(function () {
      cancelAnimationFrame(frame); frame = requestAnimationFrame(function () { reflow(); });
    }).observe(surface);
    body.addEventListener('load', function () { reflow(); }, true);
    if (document.fonts) document.fonts.ready.then(function () { reflow(); });
    mode.hidden = false;
    return {capture: function () { return enabled ? place || capture() : capture(); }, reflow: reflow, active: function () { return enabled; }};
  };
}());
