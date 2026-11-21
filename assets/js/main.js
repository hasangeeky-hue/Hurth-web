/**
 * Aquariende theme script. Vanilla JS, no build step.
 *
 * - Header state (paper after 80 px) and the mobile sheet menu.
 * - Hero entrance and the pinned "How it works" scene with GSAP, front page only.
 * - Small scroll reveals with IntersectionObserver.
 * - Sticky mobile WhatsApp bar.
 * - Call-back form validation and fetch() submit.
 *
 * Motion is skipped under prefers-reduced-motion, with ?motion=off, and when
 * GSAP is missing. CSS always holds the final state: nothing stays hidden
 * without this file.
 */
(function () {
	'use strict';

	var d = document;
	var w = window;
	var html = d.documentElement;
	var L = w.aquariendeL10n || {};

	var motionOffParam = /[?&]motion=off\b/.test(w.location.search);
	var mqReduce = w.matchMedia ? w.matchMedia('(prefers-reduced-motion: reduce)') : null;

	function reduced() {
		return motionOffParam || !!(mqReduce && mqReduce.matches) || html.classList.contains('reduce-motion');
	}

	function each(list, fn) {
		Array.prototype.forEach.call(list, fn);
	}

	if (reduced()) {
		html.classList.add('reduce-motion');
		html.classList.remove('motion-ok', 'hero-pending');
	}

	/* ------------------------------------------------------------------
	 * ?motion=off travels with internal links, so a whole visit can be
	 * checked without motion.
	 * ---------------------------------------------------------------- */
	if (motionOffParam) {
		each(d.querySelectorAll('a[href]'), function (a) {
			if (a.host !== w.location.host || a.getAttribute('href').charAt(0) === '#') {
				return;
			}
			try {
				var u = new URL(a.href);
				u.searchParams.set('motion', 'off');
				a.href = u.toString();
			} catch (e) { /* ignore */ }
		});
	}

	/* ------------------------------------------------------------------
	 * Header: transparent over the dark top, paper after 80 px.
	 * ---------------------------------------------------------------- */
	var header = d.getElementById('site-header');
	var ticking = false;

	function updateHeader() {
		ticking = false;
		if (header) {
			header.classList.toggle('is-scrolled', (w.scrollY || w.pageYOffset) > 80);
		}
	}

	w.addEventListener('scroll', function () {
		if (!ticking) {
			ticking = true;
			w.requestAnimationFrame(updateHeader);
		}
	}, { passive: true });
	updateHeader();

	/* ------------------------------------------------------------------
	 * Mobile sheet menu
	 * ---------------------------------------------------------------- */
	var toggle = d.querySelector('.menu-toggle');
	var sheet = d.getElementById('mobile-sheet');
	var sheetTimer = null;
	var stickyUpdate = function () {};

	function sheetFocusables() {
		return [toggle].concat(Array.prototype.slice.call(sheet.querySelectorAll('a[href], button:not([disabled])')));
	}

	function openMenu() {
		w.clearTimeout(sheetTimer);
		sheet.hidden = false;
		void sheet.offsetWidth; // start the 240 ms transition from the hidden state
		sheet.classList.add('is-open');
		toggle.setAttribute('aria-expanded', 'true');
		if (L.close) {
			toggle.setAttribute('aria-label', L.close);
		}
		header.classList.add('menu-open');
		html.classList.add('menu-locked');
		var first = sheet.querySelector('a[href]');
		if (first) {
			first.focus();
		}
		stickyUpdate();
	}

	function closeMenu(returnFocus) {
		if (!sheet || sheet.hidden) {
			return;
		}
		sheet.classList.remove('is-open');
		toggle.setAttribute('aria-expanded', 'false');
		if (L.openMenu) {
			toggle.setAttribute('aria-label', L.openMenu);
		}
		header.classList.remove('menu-open');
		html.classList.remove('menu-locked');
		sheetTimer = w.setTimeout(function () {
			sheet.hidden = true;
		}, reduced() ? 0 : 240);
		if (returnFocus) {
			toggle.focus();
		}
		stickyUpdate();
	}

	if (toggle && sheet && header) {
		if (L.openMenu) {
			toggle.setAttribute('aria-label', L.openMenu);
		}

		toggle.addEventListener('click', function () {
			if ('true' === toggle.getAttribute('aria-expanded')) {
				closeMenu(false);
			} else {
				openMenu();
			}
		});

		sheet.addEventListener('click', function (e) {
			if (e.target.closest('a')) {
				closeMenu(false);
			}
		});

		d.addEventListener('keydown', function (e) {
			if ('true' !== toggle.getAttribute('aria-expanded')) {
				return;
			}
			if ('Escape' === e.key) {
				closeMenu(true);
				return;
			}
			if ('Tab' === e.key) {
				var items = sheetFocusables();
				var first = items[0];
				var last = items[items.length - 1];
				if (e.shiftKey && d.activeElement === first) {
					e.preventDefault();
					last.focus();
				} else if (!e.shiftKey && d.activeElement === last) {
					e.preventDefault();
					first.focus();
				} else if (items.indexOf(d.activeElement) === -1) {
					e.preventDefault();
					first.focus();
				}
			}
		});

		w.addEventListener('resize', function () {
			if (w.innerWidth >= 960) {
				closeMenu(false);
			}
		});
	}

	/* ------------------------------------------------------------------
	 * Scroll reveals (CSS hides only under html.motion-ok)
	 * ---------------------------------------------------------------- */
	function reveal(selector, threshold) {
		var els = d.querySelectorAll(selector);
		if (!els.length) {
			return;
		}
		if (!html.classList.contains('motion-ok') || !('IntersectionObserver' in w)) {
			each(els, function (el) { el.classList.add('is-in'); });
			return;
		}
		var io = new IntersectionObserver(function (entries) {
			entries.forEach(function (entry) {
				if (entry.isIntersecting) {
					entry.target.classList.add('is-in');
					io.unobserve(entry.target);
				}
			});
		}, { threshold: threshold });
		each(els, function (el) { io.observe(el); });
	}

	reveal('.stage[data-reveal]', 0.2);
	reveal('[data-reveal-list]', 0.2);
	reveal('.team-card[data-reveal]', 0.3);

	/* ------------------------------------------------------------------
	 * File card state: rows up to n ticked, n+1 in progress, highlighter on n.
	 * ---------------------------------------------------------------- */
	function setFileStage(card, n) {
		if (!card) {
			return;
		}
		card.setAttribute('data-stage', String(n));
		for (var i = 1; i <= 3; i++) {
			var state = i <= n ? 'is-done' : (i === n + 1 ? 'is-active' : 'is-todo');
			each(card.querySelectorAll('[data-row="' + i + '"]'), function (el) {
				el.classList.remove('is-done', 'is-active', 'is-todo', 'is-current');
				el.classList.add(state);
				if (i === n && el.classList.contains('fc-row')) {
					el.classList.add('is-current');
				}
			});
		}
	}

	/* ------------------------------------------------------------------
	 * GSAP: hero entrance (1.2 s) and the pinned "How it works" scene.
	 * ---------------------------------------------------------------- */
	var gsap = w.gsap;
	var ScrollTrigger = w.ScrollTrigger;

	function heroIntro() {
		var card = d.querySelector('.file-card--hero');
		if (!card) {
			html.classList.remove('hero-pending');
			return;
		}
		// The 2.5 s safety timer already revealed the card: do not hide it again.
		if (!html.classList.contains('hero-pending') || !gsap || reduced()) {
			html.classList.remove('hero-pending');
			return;
		}

		var stamp = card.querySelector('.fc-stamp-inner');
		var rows = card.querySelectorAll('.fc-row');

		gsap.set(card, { autoAlpha: 0, y: 24 });
		gsap.set(stamp, { autoAlpha: 0, scale: 1.3, transformOrigin: '50% 50%' });
		gsap.set(rows, { autoAlpha: 0, x: -12 });
		html.classList.remove('hero-pending');

		gsap.timeline({
			defaults: { ease: 'power3.out' },
			onComplete: function () {
				gsap.set([card, stamp].concat(Array.prototype.slice.call(rows)), { clearProps: 'all' });
			}
		})
			.to(card, { autoAlpha: 1, y: 0, duration: 0.4 })
			.to(stamp, { autoAlpha: 1, scale: 1, duration: 0.3 })
			.to(rows, { autoAlpha: 1, x: 0, duration: 0.3, stagger: 0.09 });
	}

	function pinnedHowItWorks() {
		ScrollTrigger = w.ScrollTrigger;
		var sec = d.querySelector('.hiw');
		if (!sec || !gsap || !ScrollTrigger || reduced()) {
			return;
		}

		gsap.registerPlugin(ScrollTrigger);
		var mm = gsap.matchMedia();

		mm.add('(min-width: 1024px) and (min-height: 600px)', function () {
			var stages = sec.querySelectorAll('.stage');
			var links = sec.querySelectorAll('.hiw-nav-link');
			var card = sec.querySelector('.hiw-file .file-card');
			var current = 0;
			var state = { v: 0 };

			sec.classList.add('is-pinned');
			each(stages, function (s) { s.classList.add('is-in'); });

			function setStage(n) {
				if (n === current) {
					return;
				}
				current = n;
				each(stages, function (s, i) {
					s.classList.toggle('is-current', i + 1 === n);
				});
				each(links, function (a, i) {
					a.classList.toggle('is-active', i + 1 === n);
					if (i + 1 === n) {
						a.setAttribute('aria-current', 'step');
					} else {
						a.removeAttribute('aria-current');
					}
				});
				setFileStage(card, n);
			}

			setStage(1);

			var tl = gsap.timeline({
				scrollTrigger: {
					trigger: sec,
					start: 'top top',
					end: function () { return '+=' + (w.innerHeight * 3); },
					pin: true,
					scrub: 0.6,
					anticipatePin: 1,
					invalidateOnRefresh: true
				}
			});
			tl.to(state, {
				v: 3,
				ease: 'none',
				duration: 1,
				onUpdate: function () {
					setStage(Math.min(3, Math.floor(state.v) + 1));
				}
			});

			var st = tl.scrollTrigger;
			var handlers = [];
			each(links, function (a, i) {
				var h = function (e) {
					e.preventDefault();
					var y = st.start + (st.end - st.start) * ((i + 0.5) / 3);
					w.scrollTo({ top: y, behavior: 'smooth' });
				};
				handlers.push(h);
				a.addEventListener('click', h);
			});

			return function () {
				each(links, function (a, i) {
					a.removeEventListener('click', handlers[i]);
					a.classList.toggle('is-active', 0 === i);
					a.removeAttribute('aria-current');
				});
				each(stages, function (s) { s.classList.remove('is-current'); });
				sec.classList.remove('is-pinned');
				setFileStage(card, 1);
			};
		});

		if (d.fonts && d.fonts.ready) {
			d.fonts.ready.then(function () { ScrollTrigger.refresh(); });
		}
		w.addEventListener('load', function () { ScrollTrigger.refresh(); });
	}

	/*
	 * ScrollTrigger is only needed for the desktop pin, so phones never
	 * download it. Below 1024 x 600 the section stays a plain stack.
	 */
	function loadPin() {
		var sec = d.querySelector('.hiw');
		var url = L.scrollTrigger;
		if (!sec || !gsap || !url || reduced()) {
			return;
		}
		var wide = w.matchMedia('(min-width: 1024px) and (min-height: 600px)');
		var started = false;
		function go() {
			if (started || !wide.matches) {
				return;
			}
			started = true;
			if (w.ScrollTrigger) {
				pinnedHowItWorks();
				return;
			}
			var s = d.createElement('script');
			s.src = url;
			s.async = true;
			s.onload = function () {
				try {
					pinnedHowItWorks();
				} catch (e) {
					sec.classList.remove('is-pinned');
				}
			};
			d.body.appendChild(s);
		}
		go();
		if (wide.addEventListener) {
			wide.addEventListener('change', go);
		}
	}

	try {
		heroIntro();
		loadPin();
	} catch (err) {
		// Never leave the hero hidden because of an animation error.
		html.classList.remove('hero-pending');
		if (w.console) {
			w.console.warn(err);
		}
	}

	/* ------------------------------------------------------------------
	 * Sticky mobile WhatsApp bar: after the top band leaves the viewport,
	 * hidden while the call-to-action, form or footer is on screen.
	 * ---------------------------------------------------------------- */
	var bar = d.getElementById('sticky-cta');
	var top = d.querySelector('.hero, .page-head');

	if (bar && top && 'IntersectionObserver' in w) {
		var barLink = bar.querySelector('a');
		var mqMobile = w.matchMedia('(max-width: 767px)');
		var topVisible = true;
		var blocking = [];

		stickyUpdate = function () {
			var menuOpen = toggle && 'true' === toggle.getAttribute('aria-expanded');
			var show = mqMobile.matches && !topVisible && 0 === blocking.length && !menuOpen;
			bar.classList.toggle('is-visible', show);
			bar.setAttribute('aria-hidden', show ? 'false' : 'true');
			if (barLink) {
				barLink.setAttribute('tabindex', show ? '0' : '-1');
			}
		};

		new IntersectionObserver(function (entries) {
			topVisible = entries[0].isIntersecting;
			stickyUpdate();
		}).observe(top);

		var blockIo = new IntersectionObserver(function (entries) {
			entries.forEach(function (entry) {
				var i = blocking.indexOf(entry.target);
				if (entry.isIntersecting && -1 === i) {
					blocking.push(entry.target);
				} else if (!entry.isIntersecting && -1 !== i) {
					blocking.splice(i, 1);
				}
			});
			stickyUpdate();
		});
		each(d.querySelectorAll('.cta, .callback, .contact-wa, .site-footer'), function (el) { blockIo.observe(el); });

		if (mqMobile.addEventListener) {
			mqMobile.addEventListener('change', stickyUpdate);
		}
	}

	/* ------------------------------------------------------------------
	 * Call-back form: validate on blur, submit with fetch(), spinner,
	 * then the success message. Falls back to a normal POST without JS.
	 * ---------------------------------------------------------------- */
	function digits(v) {
		return (v.match(/\d/g) || []).length;
	}

	var validators = {
		aq_name: function (v) {
			return v.trim().length >= 2 ? '' : (L.name || 'Please enter your name.');
		},
		aq_phone: function (v) {
			var t = v.trim();
			var ok = /^[+0-9 ()\-]+$/.test(t) && digits(t) >= 8 && digits(t) <= 15;
			return ok ? '' : (L.phone || 'Please enter a WhatsApp number with country code.');
		}
	};

	function checkField(input) {
		var fn = validators[input.name];
		if (!fn) {
			return true;
		}
		var msg = fn(input.value);
		var field = input.closest('.field');
		var err = field ? field.querySelector('.field-error') : null;
		if (field) {
			field.classList.toggle('is-invalid', !!msg);
		}
		if (msg) {
			input.setAttribute('aria-invalid', 'true');
		} else {
			input.removeAttribute('aria-invalid');
		}
		if (err) {
			err.textContent = msg;
		}
		return !msg;
	}

	each(d.querySelectorAll('.callback-form'), function (form) {
		var inputs = form.querySelectorAll('input[name="aq_name"], input[name="aq_phone"]');
		var status = form.querySelector('.form-status');
		var button = form.querySelector('button[type="submit"]');
		var label = button ? button.querySelector('.btn-label') : null;

		each(inputs, function (input) {
			input.addEventListener('blur', function () {
				if (input.value.trim() !== '') {
					checkField(input);
				}
			});
			input.addEventListener('input', function () {
				if ('true' === input.getAttribute('aria-invalid')) {
					checkField(input);
				}
			});
		});

		form.addEventListener('submit', function (e) {
			var firstBad = null;
			each(inputs, function (input) {
				if (!checkField(input) && !firstBad) {
					firstBad = input;
				}
			});
			if (firstBad) {
				e.preventDefault();
				firstBad.focus();
				return;
			}

			if (!w.fetch || !w.FormData) {
				return; // normal POST and redirect
			}

			e.preventDefault();
			var data = new FormData(form);
			data.append('ajax', '1');

			form.classList.add('is-loading');
			if (button) {
				button.disabled = true;
			}
			if (status) {
				status.className = 'form-status';
				status.textContent = L.sending || 'Sending…';
			}

			// form.action is shadowed by the hidden <input name="action">.
			w.fetch(form.getAttribute('action'), {
				method: 'POST',
				body: data,
				credentials: 'same-origin',
				headers: { Accept: 'application/json' }
			})
				.then(function (r) {
					return r.json().catch(function () { return { success: false }; });
				})
				.then(function (json) {
					if (json && json.success) {
						form.classList.add('is-sent');
						if (status) {
							status.className = 'form-status is-sent';
							status.textContent = L.sent || 'Sent.';
						}
					} else {
						throw new Error('failed');
					}
				})
				.catch(function () {
					if (status) {
						status.className = 'form-status is-failed';
						status.textContent = L.failed || 'That did not go through.';
					}
				})
				.then(function () {
					form.classList.remove('is-loading');
					if (button) {
						button.disabled = false;
					}
					if (label) {
						label.removeAttribute('aria-hidden');
					}
				});
		});
	});
}());
