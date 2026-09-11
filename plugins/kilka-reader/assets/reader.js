/* Reading settings live only in this document; no storage or network calls. */
(function () {
  'use strict';
  document.querySelectorAll('[data-kilka-reader]').forEach(function (reader) {
    var controls = reader.querySelector('.kilka-reader-size');
    var body = reader.querySelector('.kilka-reader-body');
    if (!controls || !body) return;
    var blocks = Array.from(body.querySelectorAll('p, li, h2, h3, h4, h5, h6, figcaption, blockquote, pre'));
    var originals = blocks.map(function (element) {
      return {element: element, value: element.style.getPropertyValue('font-size'), priority: element.style.getPropertyPriority('font-size'), pixels: 0};
    });
    var scale = 100;
    function restore() {
      originals.forEach(function (item) {
        if (item.value) item.element.style.setProperty('font-size', item.value, item.priority);
        else item.element.style.removeProperty('font-size');
      });
    }
    function render() {
      restore();
      // Read every baseline before changing parents, so nested lists do not compound.
      originals.forEach(function (item) { item.pixels = parseFloat(getComputedStyle(item.element).fontSize); });
      if (scale !== 100) originals.forEach(function (item) {
        item.element.style.setProperty('font-size', (item.pixels * scale / 100) + 'px', 'important');
      });
      controls.querySelector('[data-reader-size="reset"]').textContent = scale + '%';
      controls.querySelector('[data-reader-size="decrease"]').disabled = scale === 80;
      controls.querySelector('[data-reader-size="increase"]').disabled = scale === 160;
    }
    controls.addEventListener('click', function (event) {
      var button = event.target.closest('button[data-reader-size]');
      if (!button) return;
      var action = button.dataset.readerSize;
      scale = action === 'reset' ? 100 : Math.max(80, Math.min(160, scale + (action === 'increase' ? 10 : -10)));
      render();
      var status = controls.querySelector('.kilka-reader-status');
      status.textContent = status.dataset.label + ': ' + scale + '%';
    });
    var frame;
    window.addEventListener('resize', function () {
      cancelAnimationFrame(frame);
      frame = requestAnimationFrame(render);
    });
    var alignment = reader.querySelector('.kilka-reader-alignment');
    if (alignment) {
      alignment.addEventListener('click', function (event) {
        var button = event.target.closest('button[data-reader-alignment]');
        if (!button) return;
        reader.dataset.readerAlign = button.dataset.readerAlignment;
        alignment.querySelectorAll('button').forEach(function (item) {
          item.setAttribute('aria-pressed', String(item === button));
        });
      });
      alignment.hidden = false;
    }
    var colors = reader.querySelector('.kilka-reader-colors');
    var surface = reader.closest('.kilka-reading') || reader;
    if (colors) {
      surface.dataset.readerColor = 'cream';
      colors.addEventListener('click', function (event) {
        var button = event.target.closest('button[data-reader-color-option]');
        if (!button) return;
        surface.dataset.readerColor = button.dataset.readerColorOption;
        colors.querySelectorAll('button').forEach(function (item) {
          item.setAttribute('aria-pressed', String(item === button));
        });
      });
      colors.hidden = false;
    }
    var toggle = reader.querySelector('.kilka-reader-settings-toggle');
    var panel = reader.querySelector('#kilka-reader-settings');
    if (toggle && panel) {
      function closeSettings(restoreFocus) {
        panel.hidden = true;
        toggle.setAttribute('aria-expanded', 'false');
        if (restoreFocus) toggle.focus({preventScroll: true});
      }
      toggle.addEventListener('click', function () {
        var opening = panel.hidden;
        panel.hidden = !opening;
        toggle.setAttribute('aria-expanded', String(opening));
        if (opening) panel.querySelector('button:not(:disabled)').focus({preventScroll: true});
      });
      document.addEventListener('keydown', function (event) {
        if (event.key === 'Escape' && !panel.hidden) {
          if (!document.fullscreenElement) event.preventDefault();
          closeSettings(true);
        }
      });
      document.addEventListener('pointerdown', function (event) {
        if (!panel.hidden && !panel.contains(event.target) && !toggle.contains(event.target)) {
          closeSettings(panel.contains(document.activeElement));
        }
      });
      document.addEventListener('focusin', function (event) {
        if (!panel.hidden && !panel.contains(event.target) && !toggle.contains(event.target)) closeSettings(false);
      });
      toggle.hidden = false;
    }
    var fullscreen = reader.querySelector('.kilka-reader-fullscreen');
    var fullscreenStatus = reader.querySelector('.kilka-reader-fullscreen-status');
    var root = document.documentElement;
    if (fullscreen && document.fullscreenEnabled && root.requestFullscreen && document.exitFullscreen) {
      function syncFullscreen() {
        var active = document.fullscreenElement === root;
        fullscreen.setAttribute('aria-pressed', String(active));
        fullscreen.setAttribute('aria-label', active ? fullscreen.dataset.exitLabel : fullscreen.dataset.enterLabel);
        fullscreen.querySelector('path').setAttribute('d', active
          ? 'M4 9h5V4m6 0v5h5M9 20v-5H4m16 0h-5v5'
          : 'M9 4H4v5m11-5h5v5M4 15v5h5m11-5v5h-5');
      }
      fullscreen.addEventListener('click', async function () {
        fullscreen.disabled = true;
        fullscreenStatus.textContent = '';
        try {
          if (document.fullscreenElement === root) await document.exitFullscreen();
          else await root.requestFullscreen();
        } catch (error) {
          fullscreenStatus.textContent = fullscreen.dataset.error;
        } finally {
          fullscreen.disabled = false;
          syncFullscreen();
        }
      });
      document.addEventListener('fullscreenchange', syncFullscreen);
      syncFullscreen();
      fullscreen.hidden = false;
    }
    render();
    controls.hidden = false;
  });
}());
