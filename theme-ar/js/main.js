/**
 * SoftVault AR — Main JavaScript (RTL-aware)
 */
(function () {
  'use strict';

  document.addEventListener('DOMContentLoaded', function () {
    initCarousels();
    initSearchOverlay();
    initMobileMenu();
    initFAQ();
  });

  /* ── Carousels (RTL: reverse scroll direction) ──────────── */
  function initCarousels() {
    document.querySelectorAll('.carousel-btn').forEach(function (btn) {
      btn.addEventListener('click', function () {
        var track = document.getElementById(btn.getAttribute('data-target'));
        if (!track) return;
        var card     = track.querySelector('.carousel-card');
        var scrollAmt = card ? (card.offsetWidth + 10) * 3 : 480;
        var isRTL    = document.documentElement.dir === 'rtl';

        // In RTL, prev scrolls right (+) and next scrolls left (-)
        var isPrev = btn.classList.contains('prev');
        var delta  = isRTL ? (isPrev ? scrollAmt : -scrollAmt)
                            : (isPrev ? -scrollAmt : scrollAmt);
        track.scrollBy({ left: delta, behavior: 'smooth' });
      });
    });
  }

  /* ── Search Overlay ─────────────────────────────────────── */
  function initSearchOverlay() {
    var toggle  = document.getElementById('search-toggle');
    var overlay = document.getElementById('search-overlay');
    var close   = document.getElementById('search-overlay-close');
    var input   = overlay ? overlay.querySelector('input[type="search"]') : null;
    if (!toggle || !overlay) return;

    function open()  { overlay.classList.add('open');    document.body.style.overflow='hidden'; if(input) setTimeout(function(){ input.focus(); },50); }
    function close_() { overlay.classList.remove('open'); document.body.style.overflow=''; }

    toggle.addEventListener('click', open);
    if (close) close.addEventListener('click', close_);
    overlay.addEventListener('click', function(e){ if(e.target===overlay) close_(); });
    document.addEventListener('keydown', function(e){ if(e.key==='Escape' && overlay.classList.contains('open')) close_(); });
  }

  /* ── Mobile Menu ────────────────────────────────────────── */
  function initMobileMenu() {
    var toggle = document.getElementById('mobile-toggle');
    var nav    = document.getElementById('primary-navigation');
    if (!toggle || !nav) return;
    toggle.addEventListener('click', function(){ nav.classList.toggle('open'); });
    document.addEventListener('click', function(e){
      if (!nav.contains(e.target) && !toggle.contains(e.target)) nav.classList.remove('open');
    });
  }

  /* ── FAQ Accordion ──────────────────────────────────────── */
  function initFAQ() {
    document.querySelectorAll('.faq-question').forEach(function(btn){
      btn.addEventListener('click', function(){
        var item   = btn.closest('.faq-item');
        var isOpen = item.classList.contains('open');
        document.querySelectorAll('.faq-item.open').forEach(function(i){ i.classList.remove('open'); });
        if (!isOpen) item.classList.add('open');
      });
    });
  }

  /* ── Screenshot Lightbox ────────────────────────────────── */
  document.addEventListener('click', function(e){
    var item = e.target.closest('.screenshot-item[data-lightbox]');
    if (!item) return;
    e.preventDefault();
    var href = item.getAttribute('href') || item.querySelector('img').src;
    var box  = document.createElement('div');
    box.style.cssText = 'position:fixed;inset:0;background:rgba(0,0,0,0.92);z-index:9999;display:flex;align-items:center;justify-content:center;cursor:pointer';
    var img = document.createElement('img');
    img.src = href;
    img.style.cssText = 'max-width:90vw;max-height:90vh;border-radius:8px;box-shadow:0 0 40px rgba(0,0,0,0.8)';
    box.appendChild(img);
    document.body.appendChild(box);
    document.body.style.overflow = 'hidden';
    var remove = function(){ document.body.removeChild(box); document.body.style.overflow=''; };
    box.addEventListener('click', remove);
    document.addEventListener('keydown', function onKey(e){ if(e.key==='Escape'){ remove(); document.removeEventListener('keydown',onKey); } });
  });

})();
