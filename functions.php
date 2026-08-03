<?php
/**
 * Friends Mobile Hürth — theme functions.
 *
 * Standalone. No parent theme, no page builder, no plugin dependencies.
 * Bilingual DE/EN via a UI string table plus a ?lang= switch.
 *
 * @package Hurth
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'HURTH_VERSION', '3.0.0' );

/* -------------------------------------------------------------------------
 * Verified business data
 *
 * Address confirmed by the site owner and corroborated by cylex.de and
 * oeffnungszeitenbuch.de. Phone and email from those same listings.
 *
 * NOT included: review counts (the 5.0/440 rating belongs to the separate
 * Kaulardstraße branch), repair prices (owner: commercially confidential)
 * and warranty terms (owner: varies by brand). Nothing here is invented.
 * ---------------------------------------------------------------------- */
function hurth_info( $key ) {
	$info = array(
		'name'     => 'Friends Mobile',
		'city_tag' => 'Hürth',
		'street'   => 'Luxemburger Straße 96',
		'zip'      => '50354',
		'town'     => 'Hürth',
		'country'  => 'DE',
		'phone'    => '+49 221 9928321',
		'phone_href' => '+492219928321',
		'email'    => 'info@friendsmobile.de',
		'lat'      => '50.8768',
		'lng'      => '6.8730',
		'maps'     => 'https://www.google.com/maps/search/?api=1&query=Friends+Mobile+Luxemburger+Stra%C3%9Fe+96+50354+H%C3%BCrth',
		'founded'  => '2004',
	);

	return isset( $info[ $key ] ) ? $info[ $key ] : '';
}

/**
 * Opening hours. 24h clock, used for display and for schema.org.
 */
function hurth_hours() {
	return array(
		'Mo' => array( '10:00', '18:30' ),
		'Tu' => array( '10:00', '18:30' ),
		'We' => array( '10:00', '18:30' ),
		'Th' => array( '10:00', '18:30' ),
		'Fr' => array( '10:00', '18:30' ),
		'Sa' => array( '10:00', '15:00' ),
		'Su' => null,
	);
}

/* -------------------------------------------------------------------------
 * Bilingual layer
 * ---------------------------------------------------------------------- */

/**
 * Current language: 'de' (default) or 'en'.
 *
 * Chosen by ?lang=en, remembered in a cookie for the session.
 */
function hurth_lang() {
	static $lang = null;

	if ( null !== $lang ) {
		return $lang;
	}

	$lang = 'de';

	if ( isset( $_GET['lang'] ) ) {
		$req = sanitize_key( wp_unslash( $_GET['lang'] ) );
		$lang = ( 'en' === $req ) ? 'en' : 'de';
	} elseif ( isset( $_COOKIE['hurth_lang'] ) && 'en' === sanitize_key( wp_unslash( $_COOKIE['hurth_lang'] ) ) ) {
		$lang = 'en';
	}

	return $lang;
}

/**
 * Persist the language choice once, without blocking page output.
 */
function hurth_remember_lang() {
	if ( isset( $_GET['lang'] ) && ! headers_sent() ) {
		setcookie( 'hurth_lang', hurth_lang(), time() + YEAR_IN_SECONDS, COOKIEPATH ? COOKIEPATH : '/', COOKIE_DOMAIN, is_ssl(), true );
	}
}
add_action( 'template_redirect', 'hurth_remember_lang' );

/**
 * Translate a UI string.
 *
 * Only chrome is translated here — navigation, buttons, labels. Page bodies
 * are WordPress content and are translated as content, not in code.
 *
 * @param string $key String key.
 * @return string
 */
function hurth_t( $key ) {
	$s = array(
		'nav_home'      => array( 'de' => 'Startseite',        'en' => 'Home' ),
		'nav_repair'    => array( 'de' => 'Reparatur',         'en' => 'Repair' ),
		'nav_buy'       => array( 'de' => 'Ankauf',            'en' => 'Sell Your Phone' ),
		'nav_phones'    => array( 'de' => 'Handys',            'en' => 'Phones' ),
		'nav_about'     => array( 'de' => 'Über uns',          'en' => 'About' ),
		'nav_blog'      => array( 'de' => 'Ratgeber',          'en' => 'Blog' ),
		'nav_contact'   => array( 'de' => 'Kontakt',           'en' => 'Contact' ),

		'skip'          => array( 'de' => 'Zum Inhalt springen', 'en' => 'Skip to content' ),
		'menu'          => array( 'de' => 'Menü',              'en' => 'Menu' ),
		'call'          => array( 'de' => 'Anrufen',           'en' => 'Call' ),
		'route'         => array( 'de' => 'Route',             'en' => 'Directions' ),
		'whatsapp'      => array( 'de' => 'WhatsApp',          'en' => 'WhatsApp' ),
		'open_now'      => array( 'de' => 'Jetzt geöffnet',    'en' => 'Open now' ),
		'closed_now'    => array( 'de' => 'Geschlossen',       'en' => 'Closed' ),
		'until'         => array( 'de' => 'bis',               'en' => 'until' ),

		'hero_eyebrow'  => array( 'de' => 'Hürth · Köln',      'en' => 'Hürth · Cologne' ),
		'hero_lead'     => array(
			'de' => 'Handy kaufen, reparieren lassen oder Ihr altes Gerät verkaufen — persönlich vor Ort in Hürth.',
			'en' => 'Buy a phone, get it repaired, or sell your old device — in person here in Hürth.',
		),
		'hero_h1'       => array(
			'de' => 'Handy Reparatur & Ankauf in Hürth',
			'en' => 'Phone Repair & Buy-Back in Hürth',
		),

		'point_local'   => array( 'de' => 'Persönlich vor Ort in Hürth',   'en' => 'In person, here in Hürth' ),
		'point_since'   => array( 'de' => 'Friends Mobile seit 2004',      'en' => 'Friends Mobile since 2004' ),
		'point_brands'  => array( 'de' => 'Alle Marken: iPhone, Samsung, Xiaomi, Pixel', 'en' => 'All brands: iPhone, Samsung, Xiaomi, Pixel' ),
		'point_data'    => array( 'de' => 'Ihre Daten bleiben geschützt',  'en' => 'Your data stays protected' ),

		'what_we_do'    => array( 'de' => 'Unsere Leistungen',  'en' => 'What we do' ),
		'from_blog'     => array( 'de' => 'Aus dem Ratgeber',   'en' => 'From the blog' ),
		'read_more'     => array( 'de' => 'Mehr erfahren',      'en' => 'Read more' ),
		'all_posts'     => array( 'de' => 'Alle Beiträge',      'en' => 'All posts' ),

		'cta_title'     => array(
			'de' => 'Defektes Handy? Kommen Sie einfach vorbei.',
			'en' => 'Broken phone? Just drop in.',
		),
		'cta_text'      => array(
			'de' => 'Wir schauen uns Ihr Gerät direkt im Laden an und sagen Ihnen ehrlich, was möglich ist.',
			'en' => 'We look at your device in the shop and tell you honestly what can be done.',
		),

		'f_services'    => array( 'de' => 'Leistungen',         'en' => 'Services' ),
		'f_hours'       => array( 'de' => 'Öffnungszeiten',     'en' => 'Opening hours' ),
		'f_contact'     => array( 'de' => 'Kontakt',            'en' => 'Contact' ),
		'f_areas'       => array( 'de' => 'Einzugsgebiet',      'en' => 'Service area' ),
		'areas'         => array(
			'de' => 'Hürth, Efferen, Frechen, Brühl, Sülz, Ehrenfeld und Köln',
			'en' => 'Hürth, Efferen, Frechen, Brühl, Sülz, Ehrenfeld and Cologne',
		),
		'rights'        => array( 'de' => 'Alle Rechte vorbehalten.', 'en' => 'All rights reserved.' ),
		'closed'        => array( 'de' => 'geschlossen',        'en' => 'closed' ),
		'notfound_h1'   => array( 'de' => 'Seite nicht gefunden', 'en' => 'Page not found' ),
		'notfound_p'    => array( 'de' => 'Diese Seite existiert nicht oder wurde verschoben.', 'en' => 'That page does not exist or has moved.' ),
		'back_home'     => array( 'de' => 'Zur Startseite',     'en' => 'Back to home' ),
		'latest'        => array( 'de' => 'Neueste Beiträge',   'en' => 'Latest posts' ),
		'nothing'       => array( 'de' => 'Hier ist noch nichts.', 'en' => 'Nothing here yet.' ),
		'search_for'    => array( 'de' => 'Suchergebnisse für',  'en' => 'Search results for' ),
	);

	$lang = hurth_lang();

	return isset( $s[ $key ][ $lang ] ) ? $s[ $key ][ $lang ] : $key;
}

/**
 * Weekday abbreviations for the hours table.
 */
function hurth_day_label( $code ) {
	$d = array(
		'de' => array( 'Mo' => 'Mo', 'Tu' => 'Di', 'We' => 'Mi', 'Th' => 'Do', 'Fr' => 'Fr', 'Sa' => 'Sa', 'Su' => 'So' ),
		'en' => array( 'Mo' => 'Mon', 'Tu' => 'Tue', 'We' => 'Wed', 'Th' => 'Thu', 'Fr' => 'Fri', 'Sa' => 'Sat', 'Su' => 'Sun' ),
	);

	return $d[ hurth_lang() ][ $code ];
}

/**
 * Whether the shop is open right now, in Europe/Berlin.
 *
 * @return array{open:bool,until:string}
 */
function hurth_open_state() {
	$tz  = new DateTimeZone( 'Europe/Berlin' );
	$now = new DateTime( 'now', $tz );
	$map = array( 1 => 'Mo', 2 => 'Tu', 3 => 'We', 4 => 'Th', 5 => 'Fr', 6 => 'Sa', 7 => 'Su' );

	$today = hurth_hours()[ $map[ (int) $now->format( 'N' ) ] ];

	if ( null === $today ) {
		return array( 'open' => false, 'until' => '' );
	}

	$mins  = (int) $now->format( 'G' ) * 60 + (int) $now->format( 'i' );
	$start = (int) substr( $today[0], 0, 2 ) * 60 + (int) substr( $today[0], 3, 2 );
	$end   = (int) substr( $today[1], 0, 2 ) * 60 + (int) substr( $today[1], 3, 2 );

	return array(
		'open'  => ( $mins >= $start && $mins < $end ),
		'until' => $today[1],
	);
}

/* -------------------------------------------------------------------------
 * Theme setup
 * ---------------------------------------------------------------------- */

function hurth_setup() {
	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'automatic-feed-links' );
	add_theme_support( 'responsive-embeds' );
	add_theme_support( 'custom-logo', array( 'height' => 60, 'width' => 220, 'flex-height' => true, 'flex-width' => true ) );
	add_theme_support( 'html5', array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script' ) );

	register_nav_menus( array( 'primary' => 'Primary Menu' ) );
}
add_action( 'after_setup_theme', 'hurth_setup' );

/**
 * Navigation, defined in code so no WordPress menu is required.
 *
 * Slug => translation key.
 */
function hurth_nav_items() {
	return array(
		'mobile-phone-repair'      => 'nav_repair',
		'old-moble-phone-buy-sell' => 'nav_buy',
		'explore-our-products'     => 'nav_phones',
		'about'                    => 'nav_about',
		'contact'                  => 'nav_contact',
	);
}

function hurth_menu_fallback() {
	$current = get_queried_object_id();
	$lang    = ( 'en' === hurth_lang() ) ? '?lang=en' : '';

	echo '<ul>';

	printf(
		'<li class="%s"><a href="%s">%s</a></li>',
		is_front_page() ? 'current-menu-item' : '',
		esc_url( home_url( '/' ) . $lang ),
		esc_html( hurth_t( 'nav_home' ) )
	);

	foreach ( hurth_nav_items() as $slug => $key ) {
		$page = get_page_by_path( $slug );

		if ( ! $page || 'publish' !== $page->post_status ) {
			continue;
		}

		printf(
			'<li class="%s"><a href="%s">%s</a></li>',
			(int) $current === (int) $page->ID ? 'current-menu-item' : '',
			esc_url( get_permalink( $page ) . $lang ),
			esc_html( hurth_t( $key ) )
		);
	}

	echo '</ul>';
}

/**
 * A page is worth linking only if it carries real content.
 */
function hurth_page_has_content( $page ) {
	return strlen( trim( wp_strip_all_tags( $page->post_content ) ) ) > 60;
}

/* -------------------------------------------------------------------------
 * Assets
 * ---------------------------------------------------------------------- */

/**
 * Preload the two self-hosted variable fonts.
 *
 * They are referenced from inside style.css, so the browser only discovers
 * them after the stylesheet parses. Preloading removes that round trip and
 * protects Largest Contentful Paint.
 */
function hurth_preload_fonts() {
	foreach ( array( 'inter-var-latin', 'jost-var-latin' ) as $file ) {
		printf(
			'<link rel="preload" as="font" type="font/woff2" crossorigin href="%s">' . "\n",
			esc_url( get_theme_file_uri( 'fonts/' . $file . '.woff2' ) )
		);
	}
}
add_action( 'wp_head', 'hurth_preload_fonts', 1 );

function hurth_assets() {
	wp_enqueue_style( 'hurth-style', get_stylesheet_uri(), array(), HURTH_VERSION );

	wp_register_script( 'hurth-js', false, array(), HURTH_VERSION, true );
	wp_enqueue_script( 'hurth-js' );
	wp_add_inline_script( 'hurth-js', "
	(function () {
		// Mobile navigation
		document.addEventListener('click', function (e) {
			var btn = e.target.closest('.nav-toggle');
			if (!btn) return;
			var nav = document.getElementById('site-nav');
			if (!nav) return;
			var open = nav.classList.toggle('is-open');
			btn.setAttribute('aria-expanded', open ? 'true' : 'false');
		});

		// 3D tilt. Skipped entirely on touch devices and when the visitor
		// has asked for reduced motion.
		var fine = window.matchMedia('(hover: hover) and (pointer: fine)').matches;
		var calm = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
		if (!fine || calm) return;

		var MAX = 7; // degrees
		document.querySelectorAll('.tilt').forEach(function (el) {
			var inner = el.querySelector('.tilt__inner');
			if (!inner) return;
			var frame = null;

			el.addEventListener('pointermove', function (ev) {
				if (frame) return;
				frame = requestAnimationFrame(function () {
					frame = null;
					var r = el.getBoundingClientRect();
					var px = (ev.clientX - r.left) / r.width;
					var py = (ev.clientY - r.top) / r.height;
					inner.style.setProperty('--ry', ((px - 0.5) * MAX * 2).toFixed(2) + 'deg');
					inner.style.setProperty('--rx', ((0.5 - py) * MAX * 2).toFixed(2) + 'deg');
					el.style.setProperty('--gx', (px * 100).toFixed(1) + '%');
					el.style.setProperty('--gy', (py * 100).toFixed(1) + '%');
				});
			});

			el.addEventListener('pointerleave', function () {
				inner.style.setProperty('--rx', '0deg');
				inner.style.setProperty('--ry', '0deg');
			});
		});
	})();

	// Conversion measurement. Records the three actions that matter for a
	// walk-in shop: phone calls, WhatsApp, and route requests. Fires into
	// dataLayer/gtag if either exists, and always leaves a console trace so
	// the events are verifiable before any analytics tool is connected.
	// No cookies, no identifiers, no third-party requests -> no consent
	// banner required for this layer.
	(function () {
		function track(action, label) {
			var payload = { event: 'hurth_conversion', action: action, label: label };
			if (window.dataLayer && window.dataLayer.push) { window.dataLayer.push(payload); }
			if (typeof window.gtag === 'function') {
				window.gtag('event', action, { event_category: 'contact', event_label: label });
			}
			if (window.console && console.debug) { console.debug('[hurth]', action, label); }
		}
		document.addEventListener('click', function (e) {
			var a = e.target.closest('a');
			if (!a || !a.href) return;
			if (a.href.indexOf('tel:') === 0)            { track('call', a.href.slice(4)); }
			else if (a.href.indexOf('wa.me') > -1)       { track('whatsapp', 'sticky_bar'); }
			else if (a.href.indexOf('google.com/maps') > -1) { track('route', 'maps'); }
			else if (a.href.indexOf('mailto:') === 0)    { track('email', a.href.slice(7)); }
		}, { passive: true });
	})();
	" );
}
add_action( 'wp_enqueue_scripts', 'hurth_assets' );

/* -------------------------------------------------------------------------
 * SEO / AEO / GEO
 * ---------------------------------------------------------------------- */

/**
 * Meta description, Open Graph and hreflang.
 *
 * Answer-engine and generative-engine visibility depends on machine-readable
 * facts, which is what the schema block below provides.
 */
function hurth_head_meta() {
	$desc = ( 'en' === hurth_lang() )
		? 'Friends Mobile Hürth — phone repair, new smartphones and used-device buy-back at Luxemburger Straße 96, 50354 Hürth near Cologne.'
		: 'Friends Mobile Hürth — Handy Reparatur, neue Smartphones und Ankauf gebrauchter Geräte in der Luxemburger Straße 96, 50354 Hürth bei Köln.';

	if ( is_singular() ) {
		$post_desc = wp_strip_all_tags( get_the_excerpt() );
		if ( $post_desc ) {
			$desc = wp_trim_words( $post_desc, 30, '' );
		}
	}

	printf( '<meta name="description" content="%s">' . "\n", esc_attr( $desc ) );
	printf( '<meta property="og:site_name" content="%s">' . "\n", esc_attr( hurth_info( 'name' ) . ' ' . hurth_info( 'city_tag' ) ) );
	printf( '<meta property="og:type" content="%s">' . "\n", is_singular() ? 'article' : 'website' );
	printf( '<meta property="og:title" content="%s">' . "\n", esc_attr( wp_get_document_title() ) );
	printf( '<meta property="og:description" content="%s">' . "\n", esc_attr( $desc ) );
	printf( '<meta property="og:locale" content="%s">' . "\n", 'en' === hurth_lang() ? 'en_GB' : 'de_DE' );
	echo '<meta name="twitter:card" content="summary_large_image">' . "\n";

	$base = is_singular() ? get_permalink() : home_url( '/' );
	printf( '<link rel="alternate" hreflang="de" href="%s">' . "\n", esc_url( $base ) );
	printf( '<link rel="alternate" hreflang="en" href="%s">' . "\n", esc_url( add_query_arg( 'lang', 'en', $base ) ) );
	printf( '<link rel="alternate" hreflang="x-default" href="%s">' . "\n", esc_url( $base ) );
}
add_action( 'wp_head', 'hurth_head_meta', 2 );

/**
 * schema.org LocalBusiness.
 *
 * Contains only verified facts. No aggregateRating is emitted: the 5.0/440
 * rating found online belongs to the separate Kaulardstraße branch, and
 * claiming another location's reviews would be false structured data.
 */
function hurth_schema() {
	if ( ! is_front_page() && ! is_page() ) {
		return;
	}

	$spec = array();

	foreach ( hurth_hours() as $day => $range ) {
		if ( null === $range ) {
			continue;
		}
		$spec[] = array(
			'@type'        => 'OpeningHoursSpecification',
			'dayOfWeek'    => 'https://schema.org/' . array(
				'Mo' => 'Monday', 'Tu' => 'Tuesday', 'We' => 'Wednesday',
				'Th' => 'Thursday', 'Fr' => 'Friday', 'Sa' => 'Saturday',
			)[ $day ],
			'opens'        => $range[0],
			'closes'       => $range[1],
		);
	}

	$data = array(
		'@context'    => 'https://schema.org',
		'@type'       => 'MobilePhoneStore',
		'name'        => hurth_info( 'name' ) . ' ' . hurth_info( 'city_tag' ),
		'url'         => home_url( '/' ),
		'telephone'   => hurth_info( 'phone' ),
		'email'       => hurth_info( 'email' ),
		'foundingDate' => hurth_info( 'founded' ),
		'address'     => array(
			'@type'           => 'PostalAddress',
			'streetAddress'   => hurth_info( 'street' ),
			'postalCode'      => hurth_info( 'zip' ),
			'addressLocality' => hurth_info( 'town' ),
			'addressCountry'  => hurth_info( 'country' ),
		),
		'geo'         => array(
			'@type'     => 'GeoCoordinates',
			'latitude'  => hurth_info( 'lat' ),
			'longitude' => hurth_info( 'lng' ),
		),
		'openingHoursSpecification' => $spec,
		'areaServed'  => array( 'Hürth', 'Efferen', 'Frechen', 'Brühl', 'Sülz', 'Ehrenfeld', 'Köln' ),
		'availableLanguage' => array( 'German', 'English' ),
		'hasOfferCatalog' => array(
			'@type' => 'OfferCatalog',
			'name'  => 'Services',
			'itemListElement' => array(
				array( '@type' => 'Offer', 'itemOffered' => array( '@type' => 'Service', 'name' => 'Handy Reparatur / Phone repair' ) ),
				array( '@type' => 'Offer', 'itemOffered' => array( '@type' => 'Service', 'name' => 'Handy Ankauf / Used device buy-back' ) ),
				array( '@type' => 'Offer', 'itemOffered' => array( '@type' => 'Service', 'name' => 'Smartphone Verkauf / Phone sales' ) ),
			),
		),
	);

	echo '<script type="application/ld+json">'
		. wp_json_encode( $data, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE )
		. '</script>' . "\n";
}
add_action( 'wp_head', 'hurth_schema', 3 );

/**
 * Tag the <html> element with the active language.
 */
function hurth_html_lang( $output ) {
	return 'en' === hurth_lang() ? 'lang="en-GB"' : 'lang="de-DE"';
}
add_filter( 'language_attributes', 'hurth_html_lang' );

function hurth_excerpt_length() {
	return 26;
}
add_filter( 'excerpt_length', 'hurth_excerpt_length' );

function hurth_excerpt_more() {
	return '&hellip;';
}
add_filter( 'excerpt_more', 'hurth_excerpt_more' );

/**
 * Repair the broken heading hierarchy in the imported posts.
 *
 * The migrated content contains several <h1> elements per article, with <h2>
 * appearing before them. Exactly one <h1> per document is correct for both
 * SEO and screen readers, and the template already renders the title as <h1>,
 * so every <h1> inside post content is demoted to <h2>.
 */
function hurth_fix_headings( $content ) {
	if ( ! is_singular() || is_admin() ) {
		return $content;
	}

	$content = preg_replace( '#<h1(\s[^>]*)?>#i', '<h2$1>', $content );
	$content = preg_replace( '#</h1>#i', '</h2>', $content );

	return $content;
}
add_filter( 'the_content', 'hurth_fix_headings', 20 );
