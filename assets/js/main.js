/* =============================================================================
   Right Way Medical Billing — front-end behavior.
   Vanilla JS, no jQuery. Everything degrades gracefully without it.
   ========================================================================== */
(function () {
  'use strict';

  var reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

  function $(sel, root) { return (root || document).querySelector(sel); }
  function $$(sel, root) { return Array.prototype.slice.call((root || document).querySelectorAll(sel)); }

  /* ---------------------------------------------------- scroll reveal --- */
  function initAos() {
    if (typeof AOS === 'undefined') return;
    AOS.init({
      duration: reduceMotion ? 0 : 600,
      easing: 'ease-out-cubic',
      once: true,
      offset: 60,
      disable: reduceMotion
    });
  }

  /* --------------------------------------------------- sticky masthead --- */
  function initStickyHeader() {
    var header = $('#rw-header');
    if (!header) return;
    var ticking = false;

    function update() {
      header.classList.toggle('is-stuck', window.scrollY > 24);
      ticking = false;
    }
    window.addEventListener('scroll', function () {
      if (!ticking) { window.requestAnimationFrame(update); ticking = true; }
    }, { passive: true });
    update();
  }

  /* -------------------------------------------------------- mega menus --- */
  function initMegaMenus() {
    var items = $$('.rw-nav__item--mega');
    if (!items.length) return;

    var closeTimer = null;

    function close(item) {
      item.classList.remove('is-open');
      var trigger = $('[data-rw-mega]', item);
      if (trigger) trigger.setAttribute('aria-expanded', 'false');
    }
    function closeAll(except) {
      items.forEach(function (i) { if (i !== except) close(i); });
    }
    function open(item) {
      window.clearTimeout(closeTimer);
      closeAll(item);
      item.classList.add('is-open');
      var trigger = $('[data-rw-mega]', item);
      if (trigger) trigger.setAttribute('aria-expanded', 'true');
    }

    items.forEach(function (item) {
      var trigger = $('[data-rw-mega]', item);

      item.addEventListener('mouseenter', function () { open(item); });
      item.addEventListener('mouseleave', function () {
        closeTimer = window.setTimeout(function () { close(item); }, 140);
      });

      // Keyboard: the trigger is still a real link, so Enter navigates to the
      // hub page. Space and Arrow Down open the panel instead.
      if (trigger) {
        trigger.addEventListener('keydown', function (ev) {
          if (ev.key === ' ' || ev.key === 'ArrowDown') {
            ev.preventDefault();
            open(item);
            var first = $('.rw-mega__card', item);
            if (first) first.focus();
          }
        });
      }

      item.addEventListener('focusin', function () { open(item); });
      item.addEventListener('focusout', function (ev) {
        if (!item.contains(ev.relatedTarget)) close(item);
      });
    });

    document.addEventListener('keydown', function (ev) {
      if (ev.key === 'Escape') closeAll(null);
    });
    document.addEventListener('click', function (ev) {
      if (!ev.target.closest('.rw-nav__item--mega')) closeAll(null);
    });
  }

  /* --------------------------------------------------- mobile drawer ----- */
  function initMobileNav() {
    var drawer = $('#rw-mobile-nav');
    var burger = $('.rw-burger');
    if (!drawer || !burger) return;

    var panel = $('.rw-mobile__panel', drawer);
    var lastFocused = null;

    function open() {
      lastFocused = document.activeElement;
      drawer.hidden = false;
      burger.setAttribute('aria-expanded', 'true');
      document.body.classList.add('rw-locked');
      // A single forced reflow (void offsetWidth) between removing [hidden]
      // and adding .is-open is the common fix for "transition doesn't play
      // when unhiding an element", but it is not reliable in every timing
      // scenario — the browser can still coalesce both changes into one
      // frame, in which case the panel jumps straight to (or stays at) its
      // closed transform with no visible slide-in. A double rAF guarantees
      // the closed state has actually been painted at least once before the
      // transitioning class is applied, which a single sync reflow does not.
      requestAnimationFrame(function () {
        requestAnimationFrame(function () {
          drawer.classList.add('is-open');
          // Focus the close button, not "the first focusable element" — that
          // was the logo link (first in markup order), which threw a highly
          // visible gold focus ring around the logo on every open.
          var closeBtn = $('.rw-mobile__close', panel);
          if (closeBtn) closeBtn.focus();
        });
      });
    }

    function close() {
      drawer.classList.remove('is-open');
      burger.setAttribute('aria-expanded', 'false');
      document.body.classList.remove('rw-locked');
      window.setTimeout(function () { drawer.hidden = true; }, reduceMotion ? 0 : 340);
      if (lastFocused) lastFocused.focus();
    }

    burger.addEventListener('click', function () {
      if (drawer.hidden) { open(); } else { close(); }
    });

    $$('[data-rw-close-mobile], .rw-mobile__close', drawer).forEach(function (el) {
      el.addEventListener('click', close);
    });

    // Close when a real navigation link is used.
    $$('.rw-mobile__nav a', drawer).forEach(function (a) {
      a.addEventListener('click', close);
    });

    document.addEventListener('keydown', function (ev) {
      if (ev.key === 'Escape' && !drawer.hidden) close();
      if (ev.key === 'Tab' && !drawer.hidden) trapFocus(ev, panel);
    });

    // Submenu accordions
    $$('.rw-mobile__toggle', drawer).forEach(function (btn) {
      btn.addEventListener('click', function () {
        var group = btn.closest('.rw-mobile__group');
        var isOpen = group.classList.toggle('is-open');
        btn.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
      });
    });
  }

  function trapFocus(ev, container) {
    var focusables = $$('a[href], button:not([disabled]), input, select, textarea', container)
      .filter(function (el) { return el.offsetParent !== null; });
    if (!focusables.length) return;
    var first = focusables[0];
    var last = focusables[focusables.length - 1];
    if (ev.shiftKey && document.activeElement === first) {
      ev.preventDefault(); last.focus();
    } else if (!ev.shiftKey && document.activeElement === last) {
      ev.preventDefault(); first.focus();
    }
  }

  /* ------------------------------------------------------- accordions ---- */
  function initAccordions() {
    $$('[data-rw-accordion]').forEach(function (group) {
      var buttons = $$('.rw-accordion__btn', group);

      buttons.forEach(function (btn, index) {
        btn.addEventListener('click', function () {
          var item = btn.closest('.rw-accordion__item');
          var panel = document.getElementById(btn.getAttribute('aria-controls'));
          var isOpen = item.classList.contains('is-open');

          // One panel at a time, as specified.
          buttons.forEach(function (other) {
            var otherItem = other.closest('.rw-accordion__item');
            var otherPanel = document.getElementById(other.getAttribute('aria-controls'));
            otherItem.classList.remove('is-open');
            other.setAttribute('aria-expanded', 'false');
            if (otherPanel) otherPanel.hidden = true;
          });

          if (!isOpen) {
            item.classList.add('is-open');
            btn.setAttribute('aria-expanded', 'true');
            if (panel) panel.hidden = false;
          }
        });

        // Arrow-key navigation between headers.
        btn.addEventListener('keydown', function (ev) {
          var dir = ev.key === 'ArrowDown' ? 1 : ev.key === 'ArrowUp' ? -1 : 0;
          if (!dir) return;
          ev.preventDefault();
          var next = (index + dir + buttons.length) % buttons.length;
          buttons[next].focus();
        });
      });
    });
  }

  /* --------------------------------------------------- number counters --- */
  function animateCount(el) {
    var target = parseFloat(el.getAttribute('data-rw-count'));
    var decimals = parseInt(el.getAttribute('data-rw-decimals') || '0', 10);
    if (isNaN(target)) return;

    if (reduceMotion) {
      el.textContent = target.toFixed(decimals);
      return;
    }

    var duration = 1600;
    var start = null;

    function frame(ts) {
      if (start === null) start = ts;
      var progress = Math.min((ts - start) / duration, 1);
      // easeOutExpo, so the number settles rather than stopping dead
      var eased = progress === 1 ? 1 : 1 - Math.pow(2, -10 * progress);
      el.textContent = (target * eased).toFixed(decimals);
      if (progress < 1) window.requestAnimationFrame(frame);
    }
    window.requestAnimationFrame(frame);
  }

  function initCounters() {
    var counters = $$('[data-rw-count]');
    var meters = $$('[data-rw-meter]');
    if (!counters.length && !meters.length) return;

    if (!('IntersectionObserver' in window)) {
      counters.forEach(animateCount);
      meters.forEach(function (m) { m.style.width = m.getAttribute('data-rw-meter') + '%'; });
      return;
    }

    var observer = new IntersectionObserver(function (entries) {
      entries.forEach(function (entry) {
        if (!entry.isIntersecting) return;
        var el = entry.target;
        // Fire once only — re-triggering on every scroll looks broken.
        observer.unobserve(el);
        if (el.hasAttribute('data-rw-count')) {
          animateCount(el);
        } else {
          el.style.width = el.getAttribute('data-rw-meter') + '%';
        }
      });
    }, { threshold: 0.4 });

    counters.concat(meters).forEach(function (el) { observer.observe(el); });
  }

  /* -------------------------------------------------------- testimonials - */
  function initQuotes() {
    var el = $('[data-rw-quotes]');
    if (!el || typeof Swiper === 'undefined') return;

    new Swiper(el, {
      slidesPerView: 1,
      spaceBetween: 24,
      loop: true,
      grabCursor: true,
      autoplay: reduceMotion ? false : {
        delay: 6000,
        disableOnInteraction: false,
        pauseOnMouseEnter: true
      },
      keyboard: { enabled: true },
      a11y: {
        enabled: true,
        prevSlideMessage: 'Previous testimonial',
        nextSlideMessage: 'Next testimonial'
      },
      pagination: {
        el: '[data-rw-quotes-pagination]',
        clickable: true
      },
      navigation: {
        prevEl: '[data-rw-quotes-prev]',
        nextEl: '[data-rw-quotes-next]'
      },
      breakpoints: {
        768:  { slidesPerView: 2 },
        1200: { slidesPerView: 3 }
      }
    });
  }

  /* -------------------------------------------------------- quote modal -- */
  function initModal() {
    var modal = $('#rw-quote-modal');
    if (!modal) return;
    var dialog = $('.rw-modal__dialog', modal);
    var lastFocused = null;

    function open() {
      lastFocused = document.activeElement;
      modal.hidden = false;
      document.body.classList.add('rw-locked');
      // See the matching comment in the mobile drawer's open() — a single
      // forced reflow does not reliably commit the closed state before the
      // transitioning class is applied, so this uses a double rAF instead.
      requestAnimationFrame(function () {
        requestAnimationFrame(function () {
          modal.classList.add('is-open');
          var first = $('input, button', dialog);
          if (first) first.focus();
        });
      });
    }
    function close() {
      modal.classList.remove('is-open');
      document.body.classList.remove('rw-locked');
      window.setTimeout(function () { modal.hidden = true; }, reduceMotion ? 0 : 300);
      if (lastFocused) lastFocused.focus();
    }

    $$('[data-rw-quote]').forEach(function (btn) {
      btn.addEventListener('click', open);
    });
    $$('[data-rw-close-modal]', modal).forEach(function (btn) {
      btn.addEventListener('click', close);
    });
    document.addEventListener('keydown', function (ev) {
      if (ev.key === 'Escape' && !modal.hidden) close();
      if (ev.key === 'Tab' && !modal.hidden) trapFocus(ev, dialog);
    });
  }

  /* ------------------------------------------------------- back to top --- */
  function initBackToTop() {
    var btn = $('.rw-totop');
    if (!btn) return;
    var ticking = false;

    function update() {
      btn.hidden = window.scrollY < 500;
      ticking = false;
    }
    window.addEventListener('scroll', function () {
      if (!ticking) { window.requestAnimationFrame(update); ticking = true; }
    }, { passive: true });

    btn.addEventListener('click', function () {
      window.scrollTo({ top: 0, behavior: reduceMotion ? 'auto' : 'smooth' });
    });
    update();
  }

  /* ------------------------------------------------------------- forms --- */
  var VALIDATORS = {
    name: function (v) {
      return v.trim().length >= 2 ? '' : 'Please enter your full name.';
    },
    email: function (v) {
      return /^[^\s@]+@[^\s@]+\.[^\s@]{2,}$/.test(v.trim())
        ? '' : 'Please enter a valid email address.';
    },
    phone: function (v) {
      var digits = v.replace(/[^0-9]/g, '');
      return digits.length >= 10 ? '' : 'Please enter a phone number with at least 10 digits.';
    },
    message: function (v) {
      return v.trim().length >= 15 ? '' : 'Please give us a little more detail (15 characters or more).';
    },
    consent: function (v, field) {
      return field.checked ? '' : 'Please confirm you agree to be contacted.';
    }
  };

  function validateField(field) {
    if (!field.name || !field.required) return '';
    var validator = VALIDATORS[field.name];
    var error = validator
      ? validator(field.value, field)
      : (field.value.trim() ? '' : 'This field is required.');

    var wrap = field.closest('.col-12, .col-md-6, .col-sm-6, .form-check, .rw-check') || field.parentNode;
    var errEl = wrap.querySelector('[data-rw-err]');

    field.classList.toggle('is-invalid', !!error);
    field.classList.toggle('is-valid', !error && !!field.value);
    if (errEl) {
      errEl.textContent = error;
      errEl.classList.toggle('is-shown', !!error);
    }
    field.setAttribute('aria-invalid', error ? 'true' : 'false');
    return error;
  }

  function initForms() {
    $$('[data-rw-form]').forEach(function (form) {
      var fields = $$('input, select, textarea', form).filter(function (f) {
        return f.name && f.name.indexOf('rw_') !== 0;
      });
      var msg = $('[data-rw-msg]', form);
      var button = form.querySelector('button[type="submit"]');
      var label = button ? $('[data-rw-label]', button) : null;
      var spinner = button ? $('[data-rw-spinner]', button) : null;

      fields.forEach(function (field) {
        field.addEventListener('blur', function () { validateField(field); });
        field.addEventListener('input', function () {
          if (field.classList.contains('is-invalid')) validateField(field);
        });
      });

      form.addEventListener('submit', function (ev) {
        ev.preventDefault();

        var firstBad = null;
        fields.forEach(function (field) {
          if (validateField(field) && !firstBad) firstBad = field;
        });
        if (firstBad) {
          showMessage(msg, 'is-err', 'Please correct the highlighted fields and try again.');
          firstBad.focus();
          return;
        }

        setLoading(true);
        var data = new FormData(form);

        fetch(form.action, {
          method: 'POST',
          body: data,
          headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
          .then(function (res) { return res.json().catch(function () { return null; }); })
          .then(function (json) {
            setLoading(false);
            if (json && json.ok) {
              form.reset();
              fields.forEach(function (f) { f.classList.remove('is-valid', 'is-invalid'); });
              if (json.redirect) {
                window.location.href = json.redirect;
                return;
              }
              showMessage(msg, 'is-ok', json.message || 'Thanks — we will be in touch shortly.');
            } else {
              showMessage(msg, 'is-err',
                (json && json.message) || 'Something went wrong. Please try again or call us.');
              if (json && json.errors) {
                Object.keys(json.errors).forEach(function (key) {
                  var field = form.querySelector('[name="' + key + '"]');
                  if (!field) return;
                  var wrap = field.closest('.col-12, .col-md-6, .col-sm-6, .form-check, .rw-check') || field.parentNode;
                  var errEl = wrap.querySelector('[data-rw-err]');
                  field.classList.add('is-invalid');
                  if (errEl) {
                    errEl.textContent = json.errors[key];
                    errEl.classList.add('is-shown');
                  }
                });
              }
            }
          })
          .catch(function () {
            setLoading(false);
            showMessage(msg, 'is-err',
              'We could not reach the server. Please check your connection or call us instead.');
          });

        function setLoading(state) {
          if (!button) return;
          button.classList.toggle('is-loading', state);
          button.disabled = state;
          if (spinner) spinner.hidden = !state;
          if (label && state) {
            label.dataset.rwOriginal = label.dataset.rwOriginal || label.textContent;
            label.textContent = 'Sending...';
          } else if (label && label.dataset.rwOriginal) {
            label.textContent = label.dataset.rwOriginal;
          }
        }
      });
    });
  }

  function showMessage(el, cls, text) {
    if (!el) return;
    el.classList.remove('is-ok', 'is-err');
    el.classList.add(cls);
    el.textContent = text;
  }

  /* --------------------------------------------------------------- boot -- */
  function boot() {
    initAos();
    initStickyHeader();
    initMegaMenus();
    initMobileNav();
    initAccordions();
    initCounters();
    initQuotes();
    initModal();
    initBackToTop();
    initForms();
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', boot);
  } else {
    boot();
  }
})();
