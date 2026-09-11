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
    var effect = reader.querySelector('.kilka-reader-animation');
    var effectNote = reader.querySelector('.kilka-reader-animation-note');
    var reduced = window.matchMedia('(prefers-reduced-motion: reduce)');
    var animated = false, turnAnimation = null, turnTarget = null, turnSerial = 0;
    function cancelTurn(commit) {
      var target = turnTarget;
      turnTarget = null;
      turnSerial++;
      if (turnAnimation) { turnAnimation.cancel(); turnAnimation = null; }
      if (commit && target !== null) go(target);
    }
    function effectState() {
      if (!effect) return;
      effect.hidden = !enabled || !viewport.animate;
      effect.disabled = reduced.matches;
      effect.setAttribute('aria-pressed', String(animated && !reduced.matches));
      effectNote.hidden = !enabled || !reduced.matches;
    }
    if (effect) effect.addEventListener('click', function () {
      cancelTurn(true);
      animated = !animated;
      effectState();
    });
    reduced.addEventListener('change', function () { cancelTurn(true); effectState(); });
    window.addEventListener('beforeprint', function () { cancelTurn(true); });
    document.addEventListener('visibilitychange', function () { if (document.hidden) cancelTurn(true); });
    async function turn(delta) {
      // A rapid second turn completes the first destination before continuing.
      cancelTurn(true);
      var target = Math.max(0, Math.min(count - 1, page + delta));
      if (target === page) return;
      if (!animated || reduced.matches || !viewport.animate) { go(target); return; }
      var direction = delta > 0 ? -1 : 1;
      var origin = delta > 0 ? 'left center' : 'right center';
      var serial = ++turnSerial;
      turnTarget = target;
      try {
        turnAnimation = viewport.animate([
          {transform: 'perspective(1400px) rotateY(0deg)', transformOrigin: origin},
          {transform: 'perspective(1400px) rotateY(' + (direction * 90) + 'deg)', transformOrigin: origin}
        ], {duration: 140, easing: 'ease-in', fill: 'forwards'});
        await turnAnimation.finished;
        if (serial !== turnSerial) return;
        // Remove transforms before measuring the new text anchor.
        cancelTurn(false);
        go(target);
        serial = ++turnSerial;
        turnAnimation = viewport.animate([
          {transform: 'perspective(1400px) rotateY(' + (-direction * 90) + 'deg)', transformOrigin: origin},
          {transform: 'perspective(1400px) rotateY(0deg)', transformOrigin: origin}
        ], {duration: 180, easing: 'ease-out', fill: 'forwards'});
        await turnAnimation.finished;
        if (serial === turnSerial) cancelTurn(false);
      } catch (error) {
        // Cancellation is expected on resize, mode changes and repeated input.
        if (serial === turnSerial) { cancelTurn(false); go(target); }
      }
    }
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
      cancelTurn(false);
      page = Math.max(0, Math.min(count - 1, target));
      viewport.scrollLeft = page * stride;
      viewport.scrollTop = 0;
      surface.scrollTop = 0;
      update();
      if (remember !== false) place = capture();
    }
    function reflow(mark) {
      if (!enabled || window.matchMedia('print').matches) return;
      cancelTurn(true);
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
      cancelTurn(true);
      var mark = enabled ? place || capture() : capture();
      if (paged === enabled) return;
      enabled = paged;
      if (enabled && heading) { heading.before(headingMarker); body.prepend(heading); }
      if (!enabled && headingMarker.isConnected) { headingMarker.replaceWith(heading); }
      surface.dataset.readerMode = enabled ? 'pages' : 'scroll';
      nav.hidden = !enabled;
      effectState();
      mode.querySelectorAll('button[data-reader-mode]').forEach(function (b) { b.setAttribute('aria-pressed', String((b.dataset.readerMode === 'pages') === enabled)); });
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
      var button = e.target.closest('button[data-reader-mode]');
      if (button) setMode(button.dataset.readerMode === 'pages');
    });
    nav.addEventListener('click', function (e) {
      var button = e.target.closest('[data-reader-turn]');
      if (button) turn(button.dataset.readerTurn === 'next' ? 1 : -1);
    });
    document.addEventListener('keydown', function (e) {
      if (!enabled || e.defaultPrevented || e.altKey || e.ctrlKey || e.metaKey || selected()) return;
      if ((e.target.closest(interactive) && !nav.contains(e.target)) || !reader.querySelector('#kilka-reader-settings').hidden) return;
      var delta = {ArrowRight: 1, PageDown: 1, ArrowLeft: -1, PageUp: -1}[e.key];
      if (!delta) return;
      e.preventDefault(); turn(delta);
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
        e.preventDefault(); turn(dx < 0 ? 1 : -1);
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
    return {capture: function () { cancelTurn(true); return enabled ? place || capture() : capture(); }, reflow: reflow, active: function () { return enabled; }};
  };
}());
