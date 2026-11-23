/**
 * Aquariende, "The route" (no dependencies, deferred).
 *
 * 1. "Where are you now?": tabs that show one panel of next steps.
 * 2. The six-stage line fills as the reader scrolls past it; each stop lights up when reached.
 * 3. One-time reveals: the service bars and the five steps, when they come into view.
 *
 * The page is complete without this file: every panel is open and the line is full.
 */
(function () {
	'use strict';

	var doc = document;
	var reduce = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;

	/* 1. Picker */
	doc.querySelectorAll('[data-picker]').forEach(function (picker) {
		var tabs = Array.prototype.slice.call(picker.querySelectorAll('[role="tab"]'));
		var select = function (tab, focus) {
			tabs.forEach(function (t) {
				var on = t === tab;
				var panel = doc.getElementById(t.getAttribute('aria-controls'));
				t.setAttribute('aria-selected', on ? 'true' : 'false');
				t.tabIndex = on ? 0 : -1;
				if (panel) {
					if (on) {
						panel.removeAttribute('data-collapsed');
					} else {
						panel.setAttribute('data-collapsed', '');
					}
				}
			});
			if (focus) {
				tab.focus();
			}
		};
		tabs.forEach(function (tab, i) {
			tab.addEventListener('click', function () {
				select(tab, false);
			});
			tab.addEventListener('keydown', function (e) {
				var next = null;
				if (e.key === 'ArrowRight' || e.key === 'ArrowDown') {
					next = tabs[(i + 1) % tabs.length];
				} else if (e.key === 'ArrowLeft' || e.key === 'ArrowUp') {
					next = tabs[(i - 1 + tabs.length) % tabs.length];
				} else if (e.key === 'Home') {
					next = tabs[0];
				} else if (e.key === 'End') {
					next = tabs[tabs.length - 1];
				}
				if (next) {
					e.preventDefault();
					select(next, true);
				}
			});
		});
	});

	/* 2. The line that fills with the scroll */
	var route = doc.querySelector('[data-route]');
	if (route && !reduce) {
		var stations = Array.prototype.slice.call(route.querySelectorAll('[data-station]'));
		var queued = false;
		var paint = function () {
			queued = false;
			var box = route.getBoundingClientRect();
			// The line's tip sits 55% down the screen, so a stop lights up as the reader arrives at it.
			var tip = window.innerHeight * 0.55 - box.top;
			var p = Math.max(0, Math.min(1, tip / Math.max(1, box.height - 48)));
			route.style.setProperty('--p', p.toFixed(4));
			stations.forEach(function (st) {
				st.classList.toggle('is-reached', st === stations[0] || st.offsetTop + 24 <= tip);
			});
		};
		var ask = function () {
			if (!queued) {
				queued = true;
				window.requestAnimationFrame(paint);
			}
		};
		window.addEventListener('scroll', ask, { passive: true });
		window.addEventListener('resize', ask);
		paint();
	} else if (route) {
		route.querySelectorAll('[data-station]').forEach(function (st) {
			st.classList.add('is-reached');
		});
	}

	/* 3. One-time reveals */
	var reveals = doc.querySelectorAll('[data-reveal]');
	if (reveals.length && 'IntersectionObserver' in window && !reduce) {
		var io = new IntersectionObserver(function (entries) {
			entries.forEach(function (entry) {
				if (entry.isIntersecting) {
					entry.target.classList.add('is-seen');
					io.unobserve(entry.target);
				}
			});
		}, { threshold: 0.35 });
		reveals.forEach(function (el) {
			io.observe(el);
		});
	} else {
		reveals.forEach(function (el) {
			el.classList.add('is-seen');
		});
	}
}());

/* 4. Guides: the contents list marks the section being read. */
(function () {
	'use strict';
	var links = Array.prototype.slice.call(document.querySelectorAll('.toc a[href^="#"]'));
	if (!links.length || !('IntersectionObserver' in window)) {
		return;
	}
	var byId = {};
	links.forEach(function (a) {
		var el = document.getElementById(decodeURIComponent(a.getAttribute('href').slice(1)));
		if (el) {
			byId[el.id] = a;
		}
	});
	var mark = function (id) {
		links.forEach(function (a) {
			if (a === byId[id]) {
				a.setAttribute('aria-current', 'true');
			} else {
				a.removeAttribute('aria-current');
			}
		});
	};
	var io = new IntersectionObserver(function (entries) {
		entries.forEach(function (entry) {
			if (entry.isIntersecting) {
				mark(entry.target.id);
			}
		});
	}, { rootMargin: '-20% 0px -65% 0px' });
	Object.keys(byId).forEach(function (id) {
		io.observe(document.getElementById(id));
	});
}());

/* 5. Buttons: a press leaves a ripple where it landed. */
(function () {
	'use strict';
	document.addEventListener('pointerdown', function (e) {
		var btn = e.target.closest ? e.target.closest('.btn') : null;
		if (!btn) {
			return;
		}
		var box = btn.getBoundingClientRect();
		var dot = document.createElement('span');
		dot.className = 'btn-ripple';
		dot.style.left = (e.clientX - box.left) + 'px';
		dot.style.top = (e.clientY - box.top) + 'px';
		btn.appendChild(dot);
		window.setTimeout(function () {
			dot.remove();
		}, 600);
	});
}());

/* 6. The comparison table: show one column at a time. */
(function () {
	'use strict';
	var pick = document.querySelector('[data-compare-pick]');
	var table = document.querySelector('[data-compare]');
	if (!pick || !table) {
		return;
	}
	pick.hidden = false;
	var chips = Array.prototype.slice.call(pick.querySelectorAll('.chip'));
	chips.forEach(function (chip) {
		chip.addEventListener('click', function () {
			var col = chip.getAttribute('data-col');
			chips.forEach(function (c) {
				c.setAttribute('aria-pressed', c === chip ? 'true' : 'false');
			});
			if (col) {
				table.setAttribute('data-focus', col);
			} else {
				table.removeAttribute('data-focus');
			}
		});
	});
}());
