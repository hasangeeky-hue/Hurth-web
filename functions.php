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

/*
 * Read the version from style.css rather than hardcoding it. A literal here
 * silently went stale: deploys bumped the stylesheet header while this stayed
 * at 3.0.0, so the version-change cache purge compared equal every time and
 * never fired. Deriving it means the two can no longer disagree.
 */
define( 'HURTH_VERSION', wp_get_theme( get_template() )->get( 'Version' ) ?: '0' );

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
		'street'   => 'Kaulardstraße 13-15',
		'zip'      => '50354',
		'town'     => 'Hürth',
		'country'  => 'DE',
		'phone'    => '+49 172 4054913',
		'phone_href' => '+491724054913',
		'email'    => 'huerth@friendsmobile.de',
		'maps'     => 'https://www.google.com/maps/search/?api=1&query=Friends+Mobile+Kaulardstra%C3%9Fe+13-15+50354+H%C3%BCrth',
		'founded'  => '2004',
		'languages' => 'Deutsch · Türkçe · English',
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
 * The single source of truth for site structure.
 *
 * Each entry pairs the German page with its English counterpart and carries
 * its own label, so navigation, the language switcher and hreflang all read
 * from one place and cannot drift apart.
 *
 * 'fallback' names the original imported page to use when the newer one has
 * not been imported yet, so navigation never renders empty.
 *
 * @return array
 */
function hurth_page_map() {
	return array(
		array(
			'label'    => array( 'de' => 'Reparatur', 'en' => 'Repair' ),
			'de'       => 'handy-reparatur-huerth',
			'en'       => 'en-phone-repair-huerth',
			'fallback' => 'mobile-phone-repair',
			'children' => array(
				array( 'label' => array( 'de' => 'iPhone Reparatur', 'en' => 'iPhone Repair' ),
					'de' => 'iphone-reparatur-huerth', 'en' => 'en-iphone-repair-huerth' ),
				array( 'label' => array( 'de' => 'Samsung Reparatur', 'en' => 'Samsung Repair' ),
					'de' => 'samsung-reparatur-huerth', 'en' => 'en-samsung-repair-huerth' ),
				array( 'label' => array( 'de' => 'Xiaomi, Pixel & andere', 'en' => 'Xiaomi, Pixel & others' ),
					'de' => 'xiaomi-pixel-reparatur-huerth', 'en' => 'en-xiaomi-pixel-repair-huerth' ),
				array( 'label' => array( 'de' => 'Displaytausch', 'en' => 'Screen Replacement' ),
					'de' => 'displaytausch-huerth', 'en' => 'en-screen-replacement-huerth' ),
				array( 'label' => array( 'de' => 'Akku wechseln', 'en' => 'Battery Replacement' ),
					'de' => 'akku-wechseln-huerth', 'en' => 'en-battery-replacement-huerth' ),
				array( 'label' => array( 'de' => 'Wasserschaden', 'en' => 'Water Damage' ),
					'de' => 'wasserschaden-handy-huerth', 'en' => 'en-water-damage-huerth' ),
				array( 'label' => array( 'de' => 'Ladebuchse & Kamera', 'en' => 'Charging Port & Camera' ),
					'de' => 'ladebuchse-kamera-reparatur-huerth', 'en' => 'en-charging-port-camera-repair-huerth' ),
			),
		),
		array(
			'label'    => array( 'de' => 'Ankauf', 'en' => 'Sell Your Phone' ),
			'de'       => 'handy-ankauf-huerth',
			'en'       => 'en-sell-your-phone-huerth',
			'fallback' => 'old-moble-phone-buy-sell',
			'children' => array(
				array( 'label' => array( 'de' => 'Defekte Geräte', 'en' => 'Broken Devices' ),
					'de' => 'defekte-geraete-ankauf-huerth', 'en' => 'en-broken-device-buyback-huerth' ),
				array( 'label' => array( 'de' => 'Tablets & Smartwatches', 'en' => 'Tablets & Smartwatches' ),
					'de' => 'tablet-smartwatch-ankauf-huerth', 'en' => 'en-tablet-smartwatch-buyback-huerth' ),
				array( 'label' => array( 'de' => 'Inzahlungnahme', 'en' => 'Trade-In' ),
					'de' => 'inzahlungnahme-huerth', 'en' => 'en-trade-in-huerth' ),
			),
		),
		array(
			'label'    => array( 'de' => 'Handys & Tarife', 'en' => 'Phones & Tariffs' ),
			'de'       => 'explore-our-products',
			'en'       => 'explore-our-products',
			'children' => array(
				array( 'label' => array( 'de' => 'Handytarife', 'en' => 'Mobile Tariffs' ),
					'de' => 'handytarife-huerth', 'en' => 'en-mobile-tariffs-huerth' ),
				array( 'label' => array( 'de' => 'Zubehör', 'en' => 'Accessories' ),
					'de' => 'handy-zubehoer-huerth', 'en' => 'en-phone-accessories-huerth' ),
			),
		),
		array(
			'label'    => array( 'de' => 'Über uns', 'en' => 'About' ),
			'de'       => 'ueber-uns',
			'en'       => 'en-about-us',
			'fallback' => 'about',
		),
		array(
			'label'    => array( 'de' => 'Ratgeber', 'en' => 'Blog' ),
			'de'       => 'blog',
			'en'       => 'blog',
			'children' => array(
				array( 'label' => array( 'de' => 'Häufige Fragen', 'en' => 'FAQ' ),
					'de' => 'faq-huerth', 'en' => 'en-faq' ),
			),
		),
		array(
			'label'    => array( 'de' => 'Kontakt', 'en' => 'Contact' ),
			'de'       => 'contact',
			'en'       => 'contact',
			'children' => array(
				array( 'label' => array( 'de' => 'Termin buchen', 'en' => 'Book an appointment' ),
					'de' => 'book-an-appointment', 'en' => 'book-an-appointment' ),
			),
		),
	);
}

/**
 * Legacy pages that now duplicate a better page, and where they should go.
 *
 * The imported English originals compete with the newer English pages for
 * the same terms. Rather than leave two versions ranking against each
 * other, the older ones redirect permanently to the canonical page.
 *
 * @return array old slug => new slug
 */
function hurth_legacy_redirects() {
	return array(
		'mobile-phone-repair'      => 'en-phone-repair-huerth',
		'old-moble-phone-buy-sell' => 'en-sell-your-phone-huerth',
		'about'                    => 'en-about-us',
		'services'                 => 'en-phone-repair-huerth',
	);
}

/**
 * Send the 301s.
 *
 * Only fires once the destination actually exists, so nothing breaks while
 * the content import is still pending.
 */
function hurth_do_legacy_redirects() {
	if ( is_admin() || ! is_page() ) {
		return;
	}

	$slug = get_post_field( 'post_name', get_queried_object_id() );
	$map  = hurth_legacy_redirects();

	if ( ! isset( $map[ $slug ] ) ) {
		return;
	}

	$target = get_page_by_path( $map[ $slug ] );

	if ( ! $target || 'publish' !== $target->post_status ) {
		return; // Destination not imported yet — leave the old page in place.
	}

	wp_safe_redirect( get_permalink( $target ), 301 );
	exit;
}
add_action( 'template_redirect', 'hurth_do_legacy_redirects', 1 );

/**
 * Resolve one map entry to a published page in the active language.
 *
 * Falls back to the other language, then to the legacy slug, so a partially
 * imported site still navigates instead of rendering an empty menu.
 *
 * @param array $entry Map entry.
 * @return WP_Post|null
 */
function hurth_resolve( $entry ) {
	$lang  = hurth_lang();
	$order = array( $entry[ $lang ] );

	$order[] = ( 'en' === $lang ) ? $entry['de'] : $entry['en'];

	if ( ! empty( $entry['fallback'] ) ) {
		$order[] = $entry['fallback'];
	}

	foreach ( array_unique( $order ) as $slug ) {
		$page = get_page_by_path( $slug );

		if ( $page && 'publish' === $page->post_status ) {
			return $page;
		}
	}

	return null;
}

/**
 * The URL of the current page in the other language.
 *
 * Used by both the language switcher and hreflang so the two cannot
 * disagree. Returns the real translated page where one exists, instead of
 * the same URL with a query string bolted on — which was wrong before.
 *
 * @param string $target 'de' or 'en'.
 * @return string
 */
function hurth_alt_url( $target ) {
	$target = ( 'en' === $target ) ? 'en' : 'de';
	$suffix = ( 'en' === $target ) ? '?lang=en' : '';

	if ( ! is_page() ) {
		return home_url( '/' ) . $suffix;
	}

	$id      = get_queried_object_id();
	$current = get_post_field( 'post_name', $id );

	foreach ( hurth_page_map() as $entry ) {
		$nodes = array( $entry );

		if ( ! empty( $entry['children'] ) ) {
			$nodes = array_merge( $nodes, $entry['children'] );
		}

		foreach ( $nodes as $node ) {
			$known = array( $node['de'], $node['en'] );

			if ( ! empty( $node['fallback'] ) ) {
				$known[] = $node['fallback'];
			}

			if ( ! in_array( $current, $known, true ) ) {
				continue;
			}

			$page = get_page_by_path( $node[ $target ] );

			if ( $page && 'publish' === $page->post_status ) {
				return get_permalink( $page ) . $suffix;
			}

			// No translation exists — stay on this page rather than link to a 404.
			return get_permalink( $id ) . $suffix;
		}
	}

	return get_permalink( $id ) . $suffix;
}

/**
 * The service index shown on the front page.
 *
 * Deliberately not derived from page titles and auto-excerpts. Doing that
 * printed English titles on the German site and run-together fragments like
 * "Select Your ServiceOld Mobile Phone Repair" pulled out of stripped
 * Elementor markup. Labels and blurbs are written here instead.
 *
 * @return array
 */
function hurth_service_index() {
	$de = ( 'de' === hurth_lang() );

	return array(
		array(
			'de'    => 'handy-reparatur-huerth',
			'brand' => 'iphone', 'state' => 'default',
			'en'    => 'en-phone-repair-huerth',
			'title' => $de ? 'Reparatur' : 'Repair',
			'blurb' => $de
				? 'Display, Akku, Ladebuchse, Kamera, Wasserschaden. Diagnose und Preis, bevor wir das Gerät öffnen.'
				: 'Screen, battery, charging port, camera, water damage. Diagnosis and price before we open the device.',
		),
		array(
			'de'    => 'handy-ankauf-huerth',
			'brand' => 'samsung', 'state' => 'buy',
			'en'    => 'en-sell-your-phone-huerth',
			'title' => $de ? 'Ankauf' : 'Buy-back',
			'blurb' => $de
				? 'Wir kaufen gebrauchte und defekte Geräte. Nachvollziehbare Bewertung statt einer Zahl ohne Erklärung.'
				: 'We buy used and faulty devices. A valuation you can follow, not a number without explanation.',
		),
		array(
			'de'    => 'explore-our-products',
			'brand' => 'pixel', 'state' => 'new',
			'en'    => 'explore-our-products',
			'title' => $de ? 'Neue Handys' : 'New phones',
			'blurb' => $de
				? 'Aktuelle Smartphones mit Beratung, die zu Ihrer tatsächlichen Nutzung passt — nicht zum größten Datenblatt.'
				: 'Current smartphones with advice that fits how you actually use a phone, not the longest spec sheet.',
		),
		array(
			'de'    => 'displaytausch-huerth',
			'brand' => 'iphone', 'state' => 'crack',
			'en'    => 'en-screen-replacement-huerth',
			'title' => $de ? 'Displaytausch' : 'Screen replacement',
			'blurb' => $de
				? 'Der häufigste Schaden überhaupt — und der, bei dem Warten am teuersten wird.'
				: 'The most common damage there is, and the one where waiting costs the most.',
		),
		array(
			'de'    => 'akku-wechseln-huerth',
			'brand' => 'samsung', 'state' => 'battery',
			'en'    => 'en-battery-replacement-huerth',
			'title' => $de ? 'Akkuwechsel' : 'Battery replacement',
			'blurb' => $de
				? 'Wenn das Handy mittags leer ist, obwohl es morgens voll war. Fast immer günstiger als ein neues Gerät.'
				: 'When the phone is flat by lunchtime though it was full at breakfast. Almost always cheaper than a new device.',
		),
		array(
			'de'    => 'wasserschaden-handy-huerth',
			'brand' => 'xiaomi', 'state' => 'water',
			'en'    => 'en-water-damage-huerth',
			'title' => $de ? 'Wasserschaden' : 'Water damage',
			'blurb' => $de
				? 'Hier zählt jede Minute. Was Sie in der ersten halben Stunde tun, entscheidet über Rettung oder Totalverlust.'
				: 'Here minutes matter. What you do in the first half hour decides between recovery and total loss.',
		),
	);
}

/**
 * Top-level pages for the footer and the home page card grid.
 *
 * @return array slug => label
 */
function hurth_nav_items() {
	$lang  = hurth_lang();
	$items = array();

	foreach ( hurth_page_map() as $entry ) {
		$page = hurth_resolve( $entry );

		if ( $page ) {
			$items[ $page->post_name ] = $entry['label'][ $lang ];
		}
	}

	return $items;
}

/**
 * Render navigation from the map, with dropdowns for the deeper sections.
 */
function hurth_menu_fallback() {
	$lang    = hurth_lang();
	$suffix  = ( 'en' === $lang ) ? '?lang=en' : '';
	$current = get_queried_object_id();

	echo '<ul>';

	printf(
		'<li class="%s"><a href="%s">%s</a></li>',
		is_front_page() ? 'current-menu-item' : '',
		esc_url( home_url( '/' ) . $suffix ),
		esc_html( hurth_t( 'nav_home' ) )
	);

	foreach ( hurth_page_map() as $entry ) {
		$page = hurth_resolve( $entry );

		if ( ! $page ) {
			continue;
		}

		$kids = array();

		if ( ! empty( $entry['children'] ) ) {
			foreach ( $entry['children'] as $child ) {
				$child_page = hurth_resolve( $child );

				if ( $child_page ) {
					$kids[] = array( $child_page, $child['label'][ $lang ] );
				}
			}
		}

		$classes = array();

		if ( (int) $current === (int) $page->ID ) {
			$classes[] = 'current-menu-item';
		}
		if ( $kids ) {
			$classes[] = 'has-children';
		}

		printf(
			'<li class="%s"><a href="%s">%s</a>',
			esc_attr( implode( ' ', $classes ) ),
			esc_url( get_permalink( $page ) . $suffix ),
			esc_html( $entry['label'][ $lang ] )
		);

		if ( $kids ) {
			echo '<ul class="submenu">';

			foreach ( $kids as $kid ) {
				printf(
					'<li class="%s"><a href="%s">%s</a></li>',
					(int) $current === (int) $kid[0]->ID ? 'current-menu-item' : '',
					esc_url( get_permalink( $kid[0] ) . $suffix ),
					esc_html( $kid[1] )
				);
			}

			echo '</ul>';
		}

		echo '</li>';
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
 * Responsive picture element for a theme image.
 *
 * Serves WebP with a JPEG fallback. Photos are licensed under the Pexels
 * licence (free for commercial use, no attribution required) — see the
 * credits in README.md. They are placeholders: real photographs of the
 * Hürth shop, the workbench and the team will outperform any stock image
 * for a local trust business.
 *
 * @param string $name  File stem inside images/.
 * @param string $alt   Alternative text.
 * @param array  $args  class, width, height, loading, fetchpriority, sizes.
 * @return string
 */
function hurth_picture( $name, $alt, $args = array() ) {
	$a = wp_parse_args( $args, array(
		'class'         => '',
		'width'         => 1200,
		'height'        => 800,
		'loading'       => 'lazy',
		'fetchpriority' => '',
		'sizes'         => '(max-width: 860px) 100vw, 50vw',
	) );

	$webp = get_theme_file_uri( 'images/' . $name . '.webp' );
	$jpg  = get_theme_file_uri( 'images/' . $name . '.jpg' );

	if ( ! file_exists( get_theme_file_path( 'images/' . $name . '.jpg' ) ) ) {
		return '';
	}

	return sprintf(
		'<picture><source type="image/webp" srcset="%s"><img src="%s" alt="%s" width="%d" height="%d" loading="%s"%s decoding="async" sizes="%s"%s></picture>',
		esc_url( $webp ),
		esc_url( $jpg ),
		esc_attr( $alt ),
		(int) $a['width'],
		(int) $a['height'],
		esc_attr( $a['loading'] ),
		$a['fetchpriority'] ? ' fetchpriority="' . esc_attr( $a['fetchpriority'] ) . '"' : '',
		esc_attr( $a['sizes'] ),
		$a['class'] ? ' class="' . esc_attr( $a['class'] ) . '"' : ''
	);
}

/**
 * 360° product viewer.
 *
 * Renders a drag-to-rotate viewer when a numbered frame sequence exists at
 * images/spin/<set>/frame-01.jpg … frame-NN.jpg. With no frames present it
 * falls back to a single still, so the page is never broken by missing
 * assets — the frames require a turntable shoot of the actual devices.
 *
 * @param string $set   Folder name under images/spin/.
 * @param string $alt   Alternative text.
 * @param string $still Fallback image stem in images/.
 * @return string
 */
function hurth_spin( $set, $alt, $still = 'hero-repair' ) {
	$dir    = get_theme_file_path( 'images/spin/' . $set );
	$frames = is_dir( $dir ) ? glob( $dir . '/frame-*.jpg' ) : array();

	if ( count( $frames ) < 8 ) {
		return hurth_picture( $still, $alt, array( 'class' => 'spin__still' ) );
	}

	sort( $frames );
	$urls = array_map(
		function ( $f ) use ( $set ) {
			return get_theme_file_uri( 'images/spin/' . $set . '/' . basename( $f ) );
		},
		$frames
	);

	return sprintf(
		'<div class="spin" data-spin="%s" role="img" aria-label="%s" tabindex="0">
			<img class="spin__frame" src="%s" alt="" width="900" height="900" decoding="async">
			<span class="spin__hint" aria-hidden="true">360°</span>
		</div>',
		esc_attr( wp_json_encode( $urls ) ),
		esc_attr( $alt ),
		esc_url( $urls[0] )
	);
}

/**
 * Interactive 3D device.
 *
 * A real rectangular prism built in CSS 3D — six faces, genuine perspective,
 * grab-and-rotate on both axes. This exists because the 360° frame viewer
 * needs a turntable shoot that has not happened, so it rendered nothing.
 * This needs no photography at all and works on day one.
 *
 * Auto-rotates gently until the visitor touches it, then hands over control.
 *
 * @param string $label Accessible label.
 * @return string
 */
function hurth_device3d( $variant = 'default', $label = '', $brand = 'iphone' ) {
	$de = ( 'de' === hurth_lang() );

	/*
	 * Brand geometry. These are the details people actually recognise a
	 * handset by, so each is modelled rather than recoloured:
	 *
	 *   iphone   pill Dynamic Island; square housing, three lenses in a
	 *            triangle; flat brushed sides
	 *   samsung  centred punch-hole; three bare lenses stacked vertically
	 *            with no housing; polished rails
	 *   pixel    centred punch-hole; the full-width camera bar across the
	 *            back, and a two-tone body split by it
	 *   xiaomi   centred punch-hole; large square module with one oversized
	 *            main lens and two smaller ones
	 */
	$brands = array(
		'iphone' => array(
			'name'   => 'iPhone',
			'notch'  => 'island',
			'camera' => 'square-tri',
		),
		'samsung' => array(
			'name'   => 'Samsung Galaxy',
			'notch'  => 'hole',
			'camera' => 'bare-stack',
		),
		'pixel' => array(
			'name'   => 'Google Pixel',
			'notch'  => 'hole',
			'camera' => 'bar',
		),
		'xiaomi' => array(
			'name'   => 'Xiaomi',
			'notch'  => 'hole',
			'camera' => 'square-big',
		),
	);

	if ( ! isset( $brands[ $brand ] ) ) {
		$brand = 'iphone';
	}

	$b = $brands[ $brand ];

	/*
	 * Each variant states, in the model itself, what the page is about — a
	 * cracked panel on the screen-replacement page, a drained cell on the
	 * battery page, and so on. The geometry is identical; only the screen
	 * overlay and the stage lighting change.
	 */
	$variants = array(
		'default' => array(
			'de' => 'Interaktives 3D-Modell eines Smartphones — zum Drehen ziehen',
			'en' => 'Interactive 3D smartphone model — drag to rotate',
		),
		'crack' => array(
			'de' => '3D-Modell mit gesprungenem Display — zum Drehen ziehen',
			'en' => '3D model with a cracked screen — drag to rotate',
		),
		'battery' => array(
			'de' => '3D-Modell mit schwachem Akku — zum Drehen ziehen',
			'en' => '3D model showing a drained battery — drag to rotate',
		),
		'water' => array(
			'de' => '3D-Modell mit Wasserschaden — zum Drehen ziehen',
			'en' => '3D model showing water damage — drag to rotate',
		),
		'camera' => array(
			'de' => '3D-Modell mit Kamera und Ladebuchse — zum Drehen ziehen',
			'en' => '3D model showing camera and charging port — drag to rotate',
		),
		'buy' => array(
			'de' => '3D-Modell eines Gebrauchtgeräts — zum Drehen ziehen',
			'en' => '3D model of a used device — drag to rotate',
		),
		'new' => array(
			'de' => '3D-Modell eines neuen Smartphones — zum Drehen ziehen',
			'en' => 'Interactive 3D model of a new smartphone — drag to rotate',
		),
	);

	if ( ! isset( $variants[ $variant ] ) ) {
		$variant = 'default';
	}

	if ( '' === $label ) {
		$label = $b['name'] . ' — ' . $variants[ $variant ][ $de ? 'de' : 'en' ];
	}

	$hint = $de ? 'Ziehen zum Drehen' : 'Drag to rotate';

	ob_start();
	?>
	<?php
	// Starting rotation, so the JS does not snap a variant away from the face
	// it is meant to present. The camera variant opens showing its back.
	$hurth_rot = ( 'camera' === $variant ) ? array( -10, -152 ) : array( -12, -28 );
	?>
	<div class="device3d device3d--<?php echo esc_attr( $variant ); ?> device3d--b-<?php echo esc_attr( $brand ); ?>"
		data-rx="<?php echo esc_attr( $hurth_rot[0] ); ?>"
		data-ry="<?php echo esc_attr( $hurth_rot[1] ); ?>"
		role="img" aria-label="<?php echo esc_attr( $label ); ?>" tabindex="0">
		<div class="device3d__stage">
			<div class="device3d__body">
				<div class="device3d__face device3d__face--front">
					<div class="device3d__screen">
						<span class="device3d__<?php echo 'island' === $b['notch'] ? 'notch' : 'hole'; ?>"></span>
						<span class="device3d__glow"></span>
						<span class="device3d__fx" aria-hidden="true"></span>
					</div>
				</div>
				<div class="device3d__face device3d__face--back">
					<?php if ( 'bar' === $b['camera'] ) : ?>
						<div class="device3d__bar"><i></i><i></i><span class="device3d__flash"></span></div>
					<?php else : ?>
						<div class="device3d__camera device3d__camera--<?php echo esc_attr( $b['camera'] ); ?>">
							<i></i><i></i><i></i>
						</div>
					<?php endif; ?>
					<span class="device3d__logo"><?php echo esc_html( 'iphone' === $brand ? 'F' : '' ); ?></span>
					<span class="device3d__brand"><?php echo esc_html( $b['name'] ); ?></span>
				</div>
				<div class="device3d__face device3d__face--right"></div>
				<div class="device3d__face device3d__face--left"></div>
				<div class="device3d__face device3d__face--top"></div>
				<div class="device3d__face device3d__face--bottom"></div>
			</div>
		</div>
		<span class="device3d__hint"><?php echo esc_html( $hint ); ?></span>
	</div>
	<?php
	return ob_get_clean();
}

/**
 * Compact angled device for cards and listings.
 *
 * The service cards were pure text. A full hurth_device3d() in each would
 * mean seven simultaneous rotation loops on the front page, so this is a
 * lightweight 2.5D chip instead: same brand cues and screen state, one
 * element, no JavaScript. It straightens and lifts on card hover.
 *
 * @param string $brand   iphone|samsung|pixel|xiaomi.
 * @param string $variant Screen state.
 * @return string
 */
function hurth_device_chip( $brand = 'iphone', $variant = 'default' ) {
	$allowed_brand = array( 'iphone', 'samsung', 'pixel', 'xiaomi' );
	$allowed_state = array( 'default', 'crack', 'battery', 'water', 'buy', 'new', 'camera' );

	if ( ! in_array( $brand, $allowed_brand, true ) ) {
		$brand = 'iphone';
	}
	if ( ! in_array( $variant, $allowed_state, true ) ) {
		$variant = 'default';
	}

	return sprintf(
		'<span class="chip chip--%1$s chip--v-%2$s" aria-hidden="true">
			<span class="chip__body"><span class="chip__screen"></span><span class="chip__fx"></span></span>
		</span>',
		esc_attr( $brand ),
		esc_attr( $variant )
	);
}

/**
 * Which 3D variant a page should show, keyed by slug.
 *
 * Pages not listed get no device — About, FAQ, Impressum and
 * Datenschutz are read, not looked at, and a decorative model there would
 * only push the text down.
 *
 * @param string $slug Page slug.
 * @return string|false Variant name, or false for no visual.
 */
function hurth_page_visual( $slug ) {
	// slug => array( fault variant, brand geometry )
	$map = array(
		// Repair — brand pages show their own handset.
		'handy-reparatur-huerth'                => array( 'default', 'iphone' ),
		'en-phone-repair-huerth'                => array( 'default', 'iphone' ),
		'iphone-reparatur-huerth'               => array( 'default', 'iphone' ),
		'en-iphone-repair-huerth'               => array( 'default', 'iphone' ),
		'samsung-reparatur-huerth'              => array( 'default', 'samsung' ),
		'en-samsung-repair-huerth'              => array( 'default', 'samsung' ),
		'xiaomi-pixel-reparatur-huerth'         => array( 'default', 'xiaomi' ),
		'en-xiaomi-pixel-repair-huerth'         => array( 'default', 'xiaomi' ),

		// Fault pages — spread across brands so the same body never repeats
		// on adjacent pages.
		'displaytausch-huerth'                  => array( 'crack', 'iphone' ),
		'en-screen-replacement-huerth'          => array( 'crack', 'iphone' ),
		'akku-wechseln-huerth'                  => array( 'battery', 'samsung' ),
		'en-battery-replacement-huerth'         => array( 'battery', 'samsung' ),
		'wasserschaden-handy-huerth'            => array( 'water', 'pixel' ),
		'en-water-damage-huerth'                => array( 'water', 'pixel' ),
		'ladebuchse-kamera-reparatur-huerth'    => array( 'camera', 'pixel' ),
		'en-charging-port-camera-repair-huerth' => array( 'camera', 'pixel' ),

		// Buy-back
		'handy-ankauf-huerth'                   => array( 'buy', 'samsung' ),
		'en-sell-your-phone-huerth'             => array( 'buy', 'samsung' ),
		'old-moble-phone-buy-sell'              => array( 'buy', 'samsung' ),
		'defekte-geraete-ankauf-huerth'         => array( 'crack', 'xiaomi' ),
		'en-broken-device-buyback-huerth'       => array( 'crack', 'xiaomi' ),
		'tablet-smartwatch-ankauf-huerth'       => array( 'buy', 'pixel' ),
		'en-tablet-smartwatch-buyback-huerth'   => array( 'buy', 'pixel' ),
		'inzahlungnahme-huerth'                 => array( 'buy', 'iphone' ),
		'en-trade-in-huerth'                    => array( 'buy', 'iphone' ),

		// Sales
		'explore-our-products'                  => array( 'new', 'iphone' ),
		'handytarife-huerth'                    => array( 'new', 'samsung' ),
		'en-mobile-tariffs-huerth'              => array( 'new', 'samsung' ),
		'handy-zubehoer-huerth'                 => array( 'new', 'xiaomi' ),
		'en-phone-accessories-huerth'           => array( 'new', 'xiaomi' ),

		// Company, contact and legal. These were left bare on the argument
		// that they are read rather than looked at, which left fifteen pages
		// visually inconsistent with the rest of the site.
		'ueber-uns'                             => array( 'new', 'iphone' ),
		'en-about-us'                           => array( 'new', 'iphone' ),
		'faq-huerth'                            => array( 'default', 'samsung' ),
		'en-faq'                                => array( 'default', 'samsung' ),
		'contact'                               => array( 'default', 'pixel' ),
		'book-an-appointment'                   => array( 'default', 'iphone' ),
		'blog'                                  => array( 'new', 'pixel' ),
		'impressum'                             => array( 'default', 'xiaomi' ),
		'datenschutz'                           => array( 'default', 'xiaomi' ),
		'en-imprint'                            => array( 'default', 'xiaomi' ),
		'en-privacy-policy'                     => array( 'default', 'xiaomi' ),

		// Blog posts.
		'display-gesprungen-reparieren-oder-ersetzen' => array( 'crack', 'iphone' ),
		'akku-wechseln-oder-neues-handy'              => array( 'battery', 'samsung' ),
		'wasserschaden-handy-erste-30-minuten'        => array( 'water', 'pixel' ),
		'handyvertrag-huerth-vodafone-o2-telekom'     => array( 'new', 'samsung' ),
	);

	if ( isset( $map[ $slug ] ) ) {
		return $map[ $slug ];
	}

	/*
	 * Imported posts carry long generated slugs, so match on keywords rather
	 * than listing every one. Anything still unmatched falls back to a plain
	 * model, so no page is left bare.
	 */
	$keywords = array(
		'broken'  => array( 'crack', 'xiaomi' ),
		'damaged' => array( 'crack', 'xiaomi' ),
		'iphone'  => array( 'buy', 'iphone' ),
		'sell'    => array( 'buy', 'pixel' ),
		'worth'   => array( 'buy', 'iphone' ),
		'water'   => array( 'water', 'pixel' ),
		'battery' => array( 'battery', 'samsung' ),
		'akku'    => array( 'battery', 'samsung' ),
		'display' => array( 'crack', 'iphone' ),
		'screen'  => array( 'crack', 'iphone' ),
	);

	foreach ( $keywords as $needle => $pair ) {
		if ( false !== strpos( $slug, $needle ) ) {
			return $pair;
		}
	}

	return array( 'default', 'samsung' );
}

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

		document.addEventListener('submit', function (e) {
			if (e.target.querySelector('[name=\"hurth_contact_submit\"]')) {
				track('form_submit', e.target.closest('.booking') ? 'booking' : 'contact');
			}
		}, { passive: true });
	})();

	// Booking steps. Progressive enhancement only: without JS every fieldset
	// stays visible and the form still submits in one POST.
	(function () {
		var form = document.querySelector('.booking form');
		if (!form) return;
		var steps = form.querySelectorAll('.step');
		var dots  = document.querySelectorAll('.booking .steps li');
		var submit = form.querySelector('button[type=submit]');
		if (steps.length < 2) return;

		var at = 0;
		var nav = document.createElement('div');
		nav.className = 'step-nav';
		var back = document.createElement('button');
		var next = document.createElement('button');
		back.type = next.type = 'button';
		back.className = 'btn btn--ghost';
		next.className = 'btn';
		back.textContent = document.documentElement.lang.indexOf('de') === 0 ? 'Zurück' : 'Back';
		next.textContent = document.documentElement.lang.indexOf('de') === 0 ? 'Weiter' : 'Next';
		nav.appendChild(back); nav.appendChild(next);
		submit.parentNode.insertBefore(nav, submit);

		function show() {
			steps.forEach(function (s, i) { s.hidden = i !== at; });
			dots.forEach(function (d, i) { d.classList.toggle('is-active', i <= at); });
			back.hidden = at === 0;
			next.hidden = at === steps.length - 1;
			submit.hidden = at !== steps.length - 1;
		}

		next.addEventListener('click', function () {
			var bad = Array.prototype.slice.call(steps[at].querySelectorAll('[required]'))
				.filter(function (f) { return !f.checkValidity(); });
			if (bad.length) { bad[0].reportValidity(); return; }
			at = Math.min(at + 1, steps.length - 1); show();
		});
		back.addEventListener('click', function () { at = Math.max(at - 1, 0); show(); });

		document.querySelector('.booking .steps').removeAttribute('aria-hidden');
		show();
	})();

	// Scroll reveals for browsers without native scroll-driven animation.
	// Where animation-timeline: view() is supported the CSS handles it and
	// this does nothing, so there is no duplicated work.
	(function () {
		if (CSS.supports('animation-timeline: view()')) return;
		var calm = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
		var els = document.querySelectorAll('.reveal, .reveal-rise, .stagger > *');
		if (!els.length) return;
		if (calm || !('IntersectionObserver' in window)) {
			els.forEach(function (el) { el.style.opacity = 1; });
			return;
		}
		els.forEach(function (el) {
			el.style.opacity = 0;
			el.style.transform = 'translateY(28px)';
			el.style.transition = 'opacity .7s cubic-bezier(.16,.84,.3,1), transform .7s cubic-bezier(.16,.84,.3,1)';
		});
		var io = new IntersectionObserver(function (entries) {
			entries.forEach(function (e, i) {
				if (!e.isIntersecting) return;
				var d = (Array.prototype.indexOf.call(e.target.parentNode.children, e.target) % 6) * 60;
				setTimeout(function () {
					e.target.style.opacity = 1;
					e.target.style.transform = 'none';
				}, d);
				io.unobserve(e.target);
			});
		}, { rootMargin: '0px 0px -8% 0px', threshold: 0.06 });
		els.forEach(function (el) { io.observe(el); });
	})();

	// Cursor-tracked light on cards.
	(function () {
		if (!window.matchMedia('(hover: hover) and (pointer: fine)').matches) return;
		document.addEventListener('pointermove', function (e) {
			var card = e.target.closest('.card');
			if (!card) return;
			var r = card.getBoundingClientRect();
			card.style.setProperty('--mx', ((e.clientX - r.left) / r.width * 100).toFixed(1) + '%');
			card.style.setProperty('--my', ((e.clientY - r.top) / r.height * 100).toFixed(1) + '%');
		}, { passive: true });
	})();

	// Interactive 3D device: grab and rotate on both axes.
	(function () {
		document.querySelectorAll('.device3d').forEach(function (el) {
			var body = el.querySelector('.device3d__body');
			if (!body) return;

			var calm = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
			var rx = parseFloat(el.dataset.rx);
			var ry = parseFloat(el.dataset.ry);
			if (isNaN(rx)) { rx = -12; }
			if (isNaN(ry)) { ry = -28; }
			var dragging = false, lx = 0, ly = 0, idle = !calm, raf;

			function apply() {
				body.style.transform = 'rotateX(' + rx.toFixed(2) + 'deg) rotateY(' + ry.toFixed(2) + 'deg)';
			}

			function spin() {
				if (idle) { ry += 0.22; apply(); }
				raf = requestAnimationFrame(spin);
			}

			function start(x, y) {
				dragging = true; idle = false;
				lx = x; ly = y;
				el.classList.add('is-dragging');
			}
			function move(x, y) {
				if (!dragging) return;
				ry += (x - lx) * 0.55;
				rx -= (y - ly) * 0.45;
				rx = Math.max(-70, Math.min(70, rx));
				lx = x; ly = y;
				apply();
			}
			function end() { dragging = false; el.classList.remove('is-dragging'); }

			el.addEventListener('pointerdown', function (e) {
				start(e.clientX, e.clientY);
				el.setPointerCapture(e.pointerId);
				e.preventDefault();
			});
			el.addEventListener('pointermove', function (e) { move(e.clientX, e.clientY); });
			el.addEventListener('pointerup', end);
			el.addEventListener('pointercancel', end);

			el.addEventListener('keydown', function (e) {
				var step = 8;
				if (e.key === 'ArrowRight') { idle = false; ry += step; apply(); e.preventDefault(); }
				if (e.key === 'ArrowLeft')  { idle = false; ry -= step; apply(); e.preventDefault(); }
				if (e.key === 'ArrowUp')    { idle = false; rx = Math.max(-70, rx - step); apply(); e.preventDefault(); }
				if (e.key === 'ArrowDown')  { idle = false; rx = Math.min(70, rx + step); apply(); e.preventDefault(); }
			});

			apply();
			if (!calm) { raf = requestAnimationFrame(spin); }
		});
	})();

	// 360 degree viewer. Drag, swipe or arrow-key through the frame sequence.
	(function () {
		document.querySelectorAll('.spin[data-spin]').forEach(function (el) {
			var urls;
			try { urls = JSON.parse(el.dataset.spin); } catch (e) { return; }
			if (!urls || urls.length < 8) return;

			var img = el.querySelector('.spin__frame');
			var i = 0, dragging = false, lastX = 0, acc = 0;
			var STEP = 6; // pixels of travel per frame

			urls.forEach(function (u) { var p = new Image(); p.src = u; });

			function draw(n) {
				i = ((n % urls.length) + urls.length) % urls.length;
				img.src = urls[i];
			}

			function start(x) { dragging = true; lastX = x; el.classList.add('is-dragging'); }
			function move(x) {
				if (!dragging) return;
				acc += x - lastX; lastX = x;
				while (Math.abs(acc) >= STEP) {
					draw(i + (acc > 0 ? 1 : -1));
					acc += acc > 0 ? -STEP : STEP;
				}
			}
			function end() { dragging = false; acc = 0; el.classList.remove('is-dragging'); }

			el.addEventListener('pointerdown', function (e) { start(e.clientX); el.setPointerCapture(e.pointerId); });
			el.addEventListener('pointermove', function (e) { move(e.clientX); });
			el.addEventListener('pointerup', end);
			el.addEventListener('pointercancel', end);

			el.addEventListener('keydown', function (e) {
				if (e.key === 'ArrowRight') { draw(i + 1); e.preventDefault(); }
				if (e.key === 'ArrowLeft')  { draw(i - 1); e.preventDefault(); }
			});
		});
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

	/*
	 * hreflang must point at the actual translated page. The earlier version
	 * pointed the English alternate at the same German URL with ?lang=en,
	 * which described content that did not exist there.
	 */
	$de_url = hurth_alt_url( 'de' );
	$en_url = hurth_alt_url( 'en' );

	printf( '<link rel="alternate" hreflang="de" href="%s">' . "\n", esc_url( $de_url ) );
	printf( '<link rel="alternate" hreflang="en" href="%s">' . "\n", esc_url( $en_url ) );
	printf( '<link rel="alternate" hreflang="x-default" href="%s">' . "\n", esc_url( $de_url ) );

	// Canonical for the page actually being viewed.
	if ( is_singular() ) {
		printf( '<link rel="canonical" href="%s">' . "\n", esc_url( get_permalink() ) );
	}
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
		/*
		 * No geo block. The coordinates here were for the previous address and
		 * are wrong for Kaulardstraße; asserting unverified coordinates in
		 * structured data is worse than omitting them, and Google geocodes the
		 * postal address anyway.
		 */
		'openingHoursSpecification' => $spec,
		'areaServed'  => array( 'Hürth', 'Efferen', 'Frechen', 'Brühl', 'Sülz', 'Ehrenfeld', 'Köln' ),
		'availableLanguage' => array( 'German', 'Turkish', 'English' ),
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

/* -------------------------------------------------------------------------
 * Cache purging
 *
 * LiteSpeed Cache serves pages for minutes after a deploy, so theme changes
 * stayed invisible to real visitors while cache-busted checks passed. This
 * purges on the events that actually change output.
 * ---------------------------------------------------------------------- */

/**
 * Purge every cache we know how to reach.
 */
function hurth_purge_caches() {
	/*
	 * LiteSpeed caches at the web-server level on this host, with no
	 * LiteSpeed Cache plugin installed — so the plugin action below has
	 * nothing listening to it. Server-level LSCache is purged by sending a
	 * response header instead, which is the only mechanism that reaches it.
	 *
	 * This must run on a request that actually executes PHP. Cached page
	 * responses never do, which is why the purge has to ride along with the
	 * deploy REST call (/wp-json/dfg/v1/package_update), where it does.
	 */
	if ( ! headers_sent() ) {
		header( 'X-LiteSpeed-Purge: *' );
	}

	// LiteSpeed Cache plugin, if it is ever installed.
	do_action( 'litespeed_purge_all' );

	// Common alternatives, harmless if absent.
	do_action( 'wpfc_clear_all_cache' );

	if ( function_exists( 'rocket_clean_domain' ) ) {
		rocket_clean_domain();
	}
	if ( function_exists( 'w3tc_flush_all' ) ) {
		w3tc_flush_all();
	}
	if ( function_exists( 'wp_cache_clear_cache' ) ) {
		wp_cache_clear_cache();
	}

	// WordPress object cache.
	wp_cache_flush();
}

/**
 * Purge automatically when the deployed theme version changes.
 *
 * Deployer for Git replaces theme files without touching the database, so
 * nothing else would invalidate the page cache after a deploy.
 */
function hurth_purge_on_version_change() {
	if ( get_option( 'hurth_deployed_version' ) === HURTH_VERSION ) {
		return;
	}

	update_option( 'hurth_deployed_version', HURTH_VERSION, false );
	hurth_purge_caches();
}
add_action( 'init', 'hurth_purge_on_version_change', 1 );

// Deployer for Git fires this after it installs a package.
add_action( 'dfg_after_package_install', 'hurth_purge_caches', 20 );
add_action( 'after_switch_theme', 'hurth_purge_caches' );

/**
 * On-demand purge, for when a deploy lands but the cache survives it.
 *
 * Reachable at any URL with ?hurth_purge=<key>, where the key is derived
 * from this installation's own salts — so it is unguessable and nothing
 * secret has to be stored or shared in the repository.
 */
function hurth_purge_key() {
	/*
	 * Reuse the Deployer for Git API secret when it is available. Whoever can
	 * trigger a deploy can already replace the theme, so granting the same
	 * holder a cache purge adds no privilege — and it means the purge can be
	 * driven from wherever deploys are driven from.
	 */
	if ( class_exists( '\DeployerForGit\Helper' )
		&& method_exists( '\DeployerForGit\Helper', 'get_api_secret' ) ) {
		$secret = \DeployerForGit\Helper::get_api_secret();

		if ( ! empty( $secret ) ) {
			return (string) $secret;
		}
	}

	return substr( hash_hmac( 'sha256', 'hurth-purge', wp_salt( 'auth' ) ), 0, 24 );
}

/**
 * Purge on demand.
 *
 * The automatic version-change purge is not sufficient on its own: a cached
 * page is served without ever executing PHP, so nothing in the theme can run
 * to invalidate it. This gives an explicit, uncached entry point that always
 * executes and always emits the purge header.
 */
function hurth_manual_purge() {
	if ( ! isset( $_GET['hurth_purge'] ) ) {
		return;
	}

	$given = sanitize_text_field( wp_unslash( $_GET['hurth_purge'] ) );

	if ( ! hash_equals( hurth_purge_key(), $given ) ) {
		return;
	}

	hurth_purge_caches();

	// Also ask LiteSpeed to drop the whole site, by tag and by URL prefix.
	if ( ! headers_sent() ) {
		header( 'X-LiteSpeed-Purge: *' );
		header( 'X-LiteSpeed-Purge: tag=*', false );
	}

	nocache_headers();

	wp_send_json_success( array(
		'purged'  => true,
		'version' => HURTH_VERSION,
	) );
}
add_action( 'init', 'hurth_manual_purge', 2 );

/**
 * Surface the purge URL to logged-in administrators only.
 */
function hurth_show_purge_url() {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}

	printf(
		'<!-- purge: %s -->' . "\n",
		esc_url( add_query_arg( 'hurth_purge', hurth_purge_key(), home_url( '/' ) ) )
	);
}
add_action( 'wp_head', 'hurth_show_purge_url', 99 );

/* -------------------------------------------------------------------------
 * Contact form — no plugin required
 *
 * Replaces the Contact Form 7 markup carried over in the imported Contact
 * page, which renders but cannot send without that plugin active.
 *
 * Protections: WordPress nonce, a honeypot field invisible to humans, and a
 * minimum fill time. No CAPTCHA, no third-party requests, no cookies.
 * ---------------------------------------------------------------------- */

/**
 * Handle the submission before any output is sent.
 */
function hurth_handle_contact() {
	if ( ! isset( $_POST['hurth_contact_submit'] ) ) {
		return;
	}

	$state = 'error';

	$nonce = isset( $_POST['hurth_contact_nonce'] )
		? sanitize_text_field( wp_unslash( $_POST['hurth_contact_nonce'] ) ) : '';

	// Honeypot: a real person never fills this in.
	$trap = isset( $_POST['hurth_website'] )
		? trim( sanitize_text_field( wp_unslash( $_POST['hurth_website'] ) ) ) : '';

	// Bots submit near-instantly; humans do not.
	$opened  = isset( $_POST['hurth_opened'] ) ? (int) $_POST['hurth_opened'] : 0;
	$elapsed = time() - $opened;

	/*
	 * Spam is dropped silently and the visitor is redirected as if nothing
	 * happened. State travels in the URL rather than a transient: transients
	 * were keyed on get_current_user_id(), which is 0 for every logged-out
	 * visitor, so one person's error could surface to another.
	 */
	if ( ! wp_verify_nonce( $nonce, 'hurth_contact' ) || '' !== $trap || $elapsed < 3 ) {
		hurth_contact_redirect( 'spam' );
	}

	$name    = sanitize_text_field( wp_unslash( $_POST['hurth_name'] ?? '' ) );
	$email   = sanitize_email( wp_unslash( $_POST['hurth_email'] ?? '' ) );
	$phone   = sanitize_text_field( wp_unslash( $_POST['hurth_phone'] ?? '' ) );
	$service = sanitize_text_field( wp_unslash( $_POST['hurth_service'] ?? '' ) );
	$message = sanitize_textarea_field( wp_unslash( $_POST['hurth_message'] ?? '' ) );
	$consent = isset( $_POST['hurth_consent'] );

	if ( '' === $name || ! is_email( $email ) || '' === $message || ! $consent ) {
		hurth_contact_redirect( 'invalid' );
	}

	// Extra fields the booking page adds; absent on the plain contact form.
	$device = sanitize_text_field( wp_unslash( $_POST['hurth_device'] ?? '' ) );
	$when   = sanitize_text_field( wp_unslash( $_POST['hurth_when'] ?? '' ) );

	$body = sprintf(
		"Name: %s\nE-Mail: %s\nTelefon: %s\nAnliegen: %s\nGerät: %s\nWunschzeit: %s\n\n%s\n\n---\nGesendet über %s",
		$name,
		$email,
		$phone ? $phone : '-',
		$service ? $service : '-',
		$device ? $device : '-',
		$when ? $when : '-',
		$message,
		home_url( '/' )
	);

	$sent = wp_mail(
		hurth_info( 'email' ),
		sprintf( '[Website] %s – %s', $service ? $service : 'Anfrage', $name ),
		$body,
		array(
			'Content-Type: text/plain; charset=UTF-8',
			'Reply-To: ' . $name . ' <' . $email . '>',
		)
	);

	hurth_contact_redirect( $sent ? 'sent' : 'failed' );
}
add_action( 'template_redirect', 'hurth_handle_contact', 5 );

/**
 * Redirect after POST so a refresh cannot resend, carrying state in the URL.
 *
 * @param string $state sent|failed|invalid|spam.
 */
function hurth_contact_redirect( $state ) {
	$back = wp_get_referer() ? wp_get_referer() : home_url( '/' );
	$back = remove_query_arg( array( 'hcf' ), $back );

	// Spam is redirected as a success so bots learn nothing from the response.
	wp_safe_redirect( add_query_arg( 'hcf', 'spam' === $state ? 'sent' : $state, $back ) . '#kontakt' );
	exit;
}

/**
 * Render the contact form.
 *
 * @return string
 */
function hurth_contact_form() {
	$de = ( 'de' === hurth_lang() );

	$labels = array(
		'title'   => $de ? 'Schreiben Sie uns' : 'Send us a message',
		'name'    => $de ? 'Name' : 'Name',
		'email'   => $de ? 'E-Mail' : 'Email',
		'phone'   => $de ? 'Telefon (optional)' : 'Phone (optional)',
		'service' => $de ? 'Anliegen' : 'Subject',
		'message' => $de ? 'Ihre Nachricht' : 'Your message',
		'consent' => $de
			? 'Ich bin damit einverstanden, dass meine Angaben zur Bearbeitung meiner Anfrage verarbeitet werden.'
			: 'I consent to my details being processed in order to answer my enquiry.',
		'submit'  => $de ? 'Nachricht senden' : 'Send message',
		'sent'    => $de ? 'Vielen Dank. Wir melden uns zeitnah bei Ihnen.' : 'Thank you. We will get back to you shortly.',
		'failed'  => $de
			? 'Die Nachricht konnte nicht gesendet werden. Bitte rufen Sie uns an.'
			: 'The message could not be sent. Please call us instead.',
		'invalid' => $de ? 'Bitte füllen Sie Name, E-Mail und Nachricht aus.' : 'Please complete name, email and message.',
	);

	$services = $de
		? array( 'Reparatur', 'Handy verkaufen', 'Neues Handy', 'Tarifberatung', 'Sonstiges' )
		: array( 'Repair', 'Sell a device', 'New phone', 'Tariff advice', 'Other' );

	$state  = isset( $_GET['hcf'] ) ? sanitize_key( wp_unslash( $_GET['hcf'] ) ) : '';
	$notice = '';

	if ( 'sent' === $state ) {
		$notice = '<p class="form-notice form-notice--ok" role="status">' . esc_html( $labels['sent'] ) . '</p>';
	} elseif ( 'failed' === $state ) {
		$notice = '<p class="form-notice form-notice--bad" role="alert">' . esc_html( $labels['failed'] ) . '</p>';
	} elseif ( 'invalid' === $state ) {
		$notice = '<p class="form-notice form-notice--bad" role="alert">' . esc_html( $labels['invalid'] ) . '</p>';
	}

	ob_start();
	?>
	<div class="contact-form" id="kontakt">
		<h2><?php echo esc_html( $labels['title'] ); ?></h2>
		<?php echo $notice; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>

		<form method="post" action="">
			<?php wp_nonce_field( 'hurth_contact', 'hurth_contact_nonce' ); ?>
			<input type="hidden" name="hurth_opened" value="<?php echo esc_attr( time() ); ?>">

			<p class="hurth-hp" aria-hidden="true">
				<label>Website<input type="text" name="hurth_website" tabindex="-1" autocomplete="off"></label>
			</p>

			<div class="form-row">
				<label for="hurth_name"><?php echo esc_html( $labels['name'] ); ?> *</label>
				<input id="hurth_name" type="text" name="hurth_name" required autocomplete="name">
			</div>

			<div class="form-row">
				<label for="hurth_email"><?php echo esc_html( $labels['email'] ); ?> *</label>
				<input id="hurth_email" type="email" name="hurth_email" required autocomplete="email">
			</div>

			<div class="form-row">
				<label for="hurth_phone"><?php echo esc_html( $labels['phone'] ); ?></label>
				<input id="hurth_phone" type="tel" name="hurth_phone" autocomplete="tel">
			</div>

			<div class="form-row">
				<label for="hurth_service"><?php echo esc_html( $labels['service'] ); ?></label>
				<select id="hurth_service" name="hurth_service">
					<?php foreach ( $services as $s ) : ?>
						<option value="<?php echo esc_attr( $s ); ?>"><?php echo esc_html( $s ); ?></option>
					<?php endforeach; ?>
				</select>
			</div>

			<div class="form-row">
				<label for="hurth_message"><?php echo esc_html( $labels['message'] ); ?> *</label>
				<textarea id="hurth_message" name="hurth_message" rows="6" required></textarea>
			</div>

			<div class="form-row form-row--check">
				<label>
					<input type="checkbox" name="hurth_consent" required>
					<span><?php echo esc_html( $labels['consent'] ); ?></span>
				</label>
			</div>

			<button class="btn btn--accent" type="submit" name="hurth_contact_submit" value="1">
				<?php echo esc_html( $labels['submit'] ); ?>
			</button>
		</form>
	</div>
	<?php
	return ob_get_clean();
}
add_shortcode( 'hurth_contact', 'hurth_contact_form' );

/**
 * Strip the dead Contact Form 7 markup and render ours instead.
 *
 * The imported Contact page carries CF7 output that displays but cannot
 * send. Removing the plugin leaves a form that silently fails, which is
 * worse than no form at all.
 */
function hurth_replace_cf7( $content ) {
	if ( is_admin() || ! is_page() || ! in_the_loop() || ! is_main_query() ) {
		return $content;
	}

	if ( false === strpos( $content, 'wpcf7' ) && false === strpos( $content, 'admin-ajax.php#wpcf7' ) ) {
		return $content;
	}

	// Remove the whole dead form, then append a working one.
	$content = preg_replace( '#<form[^>]*wpcf7[^>]*>.*?</form>#is', '', $content );
	$content = preg_replace( '#<form[^>]*admin-ajax\.php\#wpcf7[^>]*>.*?</form>#is', '', $content );
	$content = preg_replace( '#<div[^>]*class="[^"]*wpcf7[^"]*"[^>]*>.*?</div>#is', '', $content );

	return $content . hurth_contact_form();
}
add_filter( 'the_content', 'hurth_replace_cf7', 25 );

/**
 * Correct superseded contact details inside imported content.
 *
 * The pages were written against Luxemburger Straße 96 and the 0221 number,
 * which the owner has since confirmed are wrong — the shop is at
 * Kaulardstraße 13-15 with the 0172 number and the huerth@ mailbox. Those
 * strings are baked into stored post content, which git cannot reach.
 *
 * This rewrites them on output so every page, including the Impressum and
 * the privacy policy, is consistent immediately.
 *
 * It is a display-layer fix. The stored content still holds the old values,
 * so a theme change would bring them back — see README for the permanent
 * search-and-replace.
 *
 * @param string $content Post content.
 * @return string
 */
function hurth_fix_contact_details( $content ) {
	if ( is_admin() ) {
		return $content;
	}

	$swap = array(
		'Luxemburger Straße 96'  => 'Kaulardstraße 13-15',
		'Luxemburger Strasse 96' => 'Kaulardstraße 13-15',
		'Luxemburger Stra&szlig;e 96' => 'Kaulardstra&szlig;e 13-15',
		'tel:+492219928321'      => 'tel:+491724054913',
		'+49 221 9928321'        => '+49 172 4054913',
		'+492219928321'          => '+491724054913',
		'info@friendsmobile.de'  => 'huerth@friendsmobile.de',
	);

	return strtr( $content, $swap );
}
add_filter( 'the_content', 'hurth_fix_contact_details', 30 );
add_filter( 'the_excerpt', 'hurth_fix_contact_details', 30 );

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
