/**
 * Aquariende theme script (no dependencies, deferred).
 *
 * 1. Header state after scrolling.
 * 2. Mobile sheet menu and submenu disclosure buttons.
 * 3. Sticky WhatsApp bar on phones (hidden while a WhatsApp action is in view).
 * 4. Analytics events (cookieless): wa_click, email_click, portal_click,
 *    lang_switch, source_click, share_family. Sent to a self-hosted tool if
 *    one is present (window.plausible or window.umami) and always dispatched
 *    as the DOM event "aquariende:track". No names, numbers or message text.
 */
(function () {
	'use strict';

	var doc = document;
	var root = doc.documentElement;
	var header = doc.getElementById('site-header');

	/* 1. Header state */
	if (header) {
		var ticking = false;
		var setHeader = function () {
			header.classList.toggle('is-scrolled', window.scrollY > 40);
			ticking = false;
		};
		window.addEventListener('scroll', function () {
			if (!ticking) {
				ticking = true;
				window.requestAnimationFrame(setHeader);
			}
		}, { passive: true });
		setHeader();
	}

	/* 2. Menus */
	var toggle = doc.querySelector('.menu-toggle');
	var sheet = doc.getElementById('mobile-sheet');

	function setSheet(open) {
		if (!toggle || !sheet) {
			return;
		}
		toggle.setAttribute('aria-expanded', open ? 'true' : 'false');
		sheet.hidden = !open;
		root.classList.toggle('menu-locked', open);
		if (header) {
			header.classList.toggle('menu-open', open);
		}
		if (open) {
			var first = sheet.querySelector('a, button');
			if (first) {
				first.focus();
			}
		}
	}

	if (toggle && sheet) {
		toggle.addEventListener('click', function () {
			setSheet(toggle.getAttribute('aria-expanded') !== 'true');
		});
		window.matchMedia('(min-width: 1040px)').addEventListener('change', function (e) {
			if (e.matches) {
				setSheet(false);
			}
		});
	}

	function closeSubs(except) {
		doc.querySelectorAll('.sub-toggle[aria-expanded="true"]').forEach(function (btn) {
			if (btn !== except) {
				btn.setAttribute('aria-expanded', 'false');
				var sub = doc.getElementById(btn.getAttribute('aria-controls'));
				if (sub) {
					sub.classList.remove('is-open');
				}
			}
		});
	}

	doc.addEventListener('click', function (e) {
		var btn = e.target.closest ? e.target.closest('.sub-toggle') : null;
		if (btn) {
			var open = btn.getAttribute('aria-expanded') !== 'true';
			closeSubs(btn);
			btn.setAttribute('aria-expanded', open ? 'true' : 'false');
			var sub = doc.getElementById(btn.getAttribute('aria-controls'));
			if (sub) {
				sub.classList.toggle('is-open', open);
			}
			return;
		}
		if (!e.target.closest || !e.target.closest('.sub-menu')) {
			closeSubs(null);
		}
	});

	doc.addEventListener('keydown', function (e) {
		if (e.key !== 'Escape') {
			return;
		}
		var openSub = doc.querySelector('.primary-nav .sub-toggle[aria-expanded="true"]');
		if (openSub) {
			closeSubs(null);
			openSub.focus();
		} else if (toggle && toggle.getAttribute('aria-expanded') === 'true') {
			setSheet(false);
			toggle.focus();
		}
	});

	/* 3. Sticky WhatsApp bar */
	var bar = doc.getElementById('sticky-cta');
	var top = doc.querySelector('.hero, .page-head');
	if (bar && top && 'IntersectionObserver' in window) {
		var topVisible = true;
		var actionVisible = false;
		var seen = new Set();
		var update = function () {
			var show = !topVisible && !actionVisible;
			bar.classList.toggle('is-visible', show);
		};
		new IntersectionObserver(function (entries) {
			topVisible = entries[0].isIntersecting;
			update();
		}).observe(top);
		var io = new IntersectionObserver(function (entries) {
			entries.forEach(function (entry) {
				if (entry.isIntersecting) {
					seen.add(entry.target);
				} else {
					seen.delete(entry.target);
				}
			});
			actionVisible = seen.size > 0;
			update();
		});
		doc.querySelectorAll('.cta, .site-footer, .contact-wa').forEach(function (el) {
			io.observe(el);
		});
	}

	/* 4. Analytics events */
	function track(name, props) {
		try {
			window.dispatchEvent(new CustomEvent('aquariende:track', { detail: { name: name, props: props } }));
			if (typeof window.plausible === 'function') {
				window.plausible(name, { props: props });
			} else if (window.umami && typeof window.umami.track === 'function') {
				window.umami.track(name, props);
			}
		} catch (err) {
			/* Analytics must never break a click. */
		}
	}

	var lang = root.getAttribute('lang') || 'en';
	doc.addEventListener('click', function (e) {
		var a = e.target.closest ? e.target.closest('a') : null;
		if (!a) {
			return;
		}
		if (a.hasAttribute('data-wa')) {
			var parts = a.getAttribute('data-wa').split(':');
			track('wa_click', { code: parts[0], position: parts[1] || '', lang: lang.slice(0, 2) });
		} else if (a.hasAttribute('data-email')) {
			track('email_click', { page: window.location.pathname });
		} else if (a.hasAttribute('data-portal')) {
			track('portal_click', {});
		} else if (a.hasAttribute('data-lang-to')) {
			track('lang_switch', { to: a.getAttribute('data-lang-to') });
		} else if (a.hasAttribute('data-share')) {
			track('share_family', {});
		} else if (a.hasAttribute('data-ext')) {
			track('source_click', { host: a.getAttribute('data-ext') });
		}
	});
}());
