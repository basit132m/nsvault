/**
 * NSVault Main JavaScript
 */
(function () {
  'use strict';

  /* ──────────────────────────────────────────────────────
   * DOM Ready
   * ────────────────────────────────────────────────────── */
  document.addEventListener('DOMContentLoaded', function () {
    initCarousels();
    initSearchOverlay();
    initMobileMenu();
    initFAQ();
    initViewToggle();
  });

  /* ──────────────────────────────────────────────────────
   * CAROUSELS
   * Prev/Next buttons scroll the carousel track
   * ────────────────────────────────────────────────────── */
  function initCarousels() {
    var btns = document.querySelectorAll('.carousel-btn');
    btns.forEach(function (btn) {
      btn.addEventListener('click', function () {
        var targetId = btn.getAttribute('data-target');
        var track    = document.getElementById(targetId);
        if (!track) return;

        var cardWidth = track.querySelector('.carousel-card');
        var scrollAmt = cardWidth ? (cardWidth.offsetWidth + 10) * 3 : 480;

        if (btn.classList.contains('prev')) {
          track.scrollBy({ left: -scrollAmt, behavior: 'smooth' });
        } else {
          track.scrollBy({ left:  scrollAmt, behavior: 'smooth' });
        }
      });
    });
  }

  /* ──────────────────────────────────────────────────────
   * SEARCH OVERLAY
   * ────────────────────────────────────────────────────── */
  function initSearchOverlay() {
    var toggle  = document.getElementById('search-toggle');
    var overlay = document.getElementById('search-overlay');
    var close   = document.getElementById('search-overlay-close');
    var input   = overlay ? overlay.querySelector('input[type="search"]') : null;

    if (!toggle || !overlay) return;

    function openOverlay() {
      overlay.classList.add('open');
      document.body.style.overflow = 'hidden';
      if (input) setTimeout(function () { input.focus(); }, 50);
    }

    function closeOverlay() {
      overlay.classList.remove('open');
      document.body.style.overflow = '';
    }

    toggle.addEventListener('click', openOverlay);
    if (close) close.addEventListener('click', closeOverlay);

    // Click outside
    overlay.addEventListener('click', function (e) {
      if (e.target === overlay) closeOverlay();
    });

    // ESC key
    document.addEventListener('keydown', function (e) {
      if (e.key === 'Escape' && overlay.classList.contains('open')) {
        closeOverlay();
      }
    });
  }

  /* ──────────────────────────────────────────────────────
   * MOBILE MENU TOGGLE
   * ────────────────────────────────────────────────────── */
  function initMobileMenu() {
    var toggle = document.getElementById('mobile-toggle');
    var nav    = document.getElementById('primary-navigation');
    if (!toggle || !nav) return;

    toggle.addEventListener('click', function () {
      nav.classList.toggle('open');
      var isOpen = nav.classList.contains('open');
      toggle.setAttribute('aria-expanded', isOpen);
    });

    // Close on outside click
    document.addEventListener('click', function (e) {
      if (!nav.contains(e.target) && !toggle.contains(e.target)) {
        nav.classList.remove('open');
      }
    });
  }

  /* ──────────────────────────────────────────────────────
   * FAQ ACCORDION
   * ────────────────────────────────────────────────────── */
  function initFAQ() {
    var questions = document.querySelectorAll('.faq-question');
    questions.forEach(function (btn) {
      btn.addEventListener('click', function () {
        var item = btn.closest('.faq-item');
        if (!item) return;

        var isOpen = item.classList.contains('open');

        // Close all
        document.querySelectorAll('.faq-item.open').forEach(function (openItem) {
          openItem.classList.remove('open');
        });

        // Toggle current
        if (!isOpen) {
          item.classList.add('open');
        }
      });
    });
  }

  /* ──────────────────────────────────────────────────────
   * VIEW TOGGLE (grid ↔ list)
   * Persist preference in sessionStorage
   * ────────────────────────────────────────────────────── */
  function initViewToggle() {
    var grid = document.querySelector('.games-grid');
    if (!grid) return;

    // Read from sessionStorage if ?view= not in URL
    var params = new URLSearchParams(window.location.search);
    if (!params.has('view')) {
      var saved = sessionStorage.getItem('nsvault_view');
      if (saved === 'list') {
        grid.classList.add('list-view');
      }
    }
  }

  /* ──────────────────────────────────────────────────────
   * SCREENSHOT LIGHTBOX (simple)
   * ────────────────────────────────────────────────────── */
  document.addEventListener('click', function (e) {
    var item = e.target.closest('.screenshot-item[data-lightbox]');
    if (!item) return;
    e.preventDefault();

    var href = item.getAttribute('href') || item.querySelector('img').src;
    var lightbox = document.createElement('div');
    lightbox.style.cssText = [
      'position:fixed', 'inset:0', 'background:rgba(0,0,0,0.92)',
      'z-index:9999', 'display:flex', 'align-items:center', 'justify-content:center',
      'cursor:pointer'
    ].join(';');

    var img = document.createElement('img');
    img.src = href;
    img.style.cssText = 'max-width:90vw;max-height:90vh;border-radius:8px;box-shadow:0 0 40px rgba(0,0,0,0.8)';

    lightbox.appendChild(img);
    document.body.appendChild(lightbox);
    document.body.style.overflow = 'hidden';

    function closeLightbox() {
      document.body.removeChild(lightbox);
      document.body.style.overflow = '';
    }

    lightbox.addEventListener('click', closeLightbox);
    document.addEventListener('keydown', function onKey(e) {
      if (e.key === 'Escape') {
        closeLightbox();
        document.removeEventListener('keydown', onKey);
      }
    });
  });

})();
