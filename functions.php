<?php
/**
 * Hurth theme functions.
 *
 * Standalone theme for Friends Mobile, Hürth. No parent theme, no page
 * builder and no plugin dependencies.
 *
 * @package Hurth
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'HURTH_VERSION', '2.0.0' );

/**
 * Core theme supports and menu locations.
 */
function hurth_setup() {
	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'automatic-feed-links' );
	add_theme_support( 'responsive-embeds' );
	add_theme_support( 'custom-logo', array(
		'height'      => 60,
		'width'       => 220,
		'flex-height' => true,
		'flex-width'  => true,
	) );
	add_theme_support( 'html5', array(
		'search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script',
	) );

	register_nav_menus( array(
		'primary' => __( 'Primary Menu', 'hurth' ),
		'footer'  => __( 'Footer Menu', 'hurth' ),
	) );
}
add_action( 'after_setup_theme', 'hurth_setup' );

/**
 * Front-end assets.
 */
function hurth_assets() {
	wp_enqueue_style( 'hurth-style', get_stylesheet_uri(), array(), HURTH_VERSION );

	// Mobile navigation toggle. Small enough to inline rather than ship a file.
	// Registering with src false is the documented way to attach inline-only JS.
	wp_register_script( 'hurth-nav', false, array(), HURTH_VERSION, true );
	wp_enqueue_script( 'hurth-nav' );
	wp_add_inline_script( 'hurth-nav', "
		document.addEventListener('click', function (e) {
			var btn = e.target.closest('.nav-toggle');
			if (!btn) return;
			var nav = document.getElementById('site-nav');
			if (!nav) return;
			var open = nav.classList.toggle('is-open');
			btn.setAttribute('aria-expanded', open ? 'true' : 'false');
		});
	" );
}
add_action( 'wp_enqueue_scripts', 'hurth_assets' );

/**
 * Business details used across the templates.
 *
 * Kept in one place so they are edited once, in git.
 *
 * @param string $key Which detail to return.
 * @return string
 */
function hurth_info( $key ) {
	$info = array(
		// Hard-coded so the brand never depends on the WordPress Site Title
		// setting — which on a temporary domain is the hosting hostname.
		'name'    => 'Friends Mobile',
		'street'  => 'Luxemburger Straße 96',
		'city'    => '50354 Hürth',
		'region'  => 'Hürth · Köln',
		'areas'   => 'Hürth, Efferen, Frechen, Brühl, Sülz, Ehrenfeld & Köln',
		'tagline' => 'Mobile & DHL Service Center',
	);

	return isset( $info[ $key ] ) ? $info[ $key ] : '';
}

/**
 * Navigation defined in code, so no WordPress menu needs creating.
 *
 * Keys are page slugs; values are the label to display. Slugs that do not
 * resolve to a published page are skipped silently.
 *
 * @return array
 */
function hurth_nav_items() {
	return array(
		'services'              => __( 'Services', 'hurth' ),
		'mobile-phone-repair'   => __( 'Repairs', 'hurth' ),
		'explore-our-products'  => __( 'Products', 'hurth' ),
		'old-moble-phone-buy-sell' => __( 'Buy & Sell', 'hurth' ),
		'about'                 => __( 'About', 'hurth' ),
		'contact'               => __( 'Contact', 'hurth' ),
	);
}

/**
 * Render the code-defined navigation.
 *
 * Used as the wp_nav_menu fallback, so an admin-created menu still wins if
 * one is ever assigned to the primary location.
 */
function hurth_menu_fallback() {
	$current = get_queried_object_id();

	echo '<ul>';

	printf(
		'<li class="%s"><a href="%s">%s</a></li>',
		is_front_page() ? 'current-menu-item' : '',
		esc_url( home_url( '/' ) ),
		esc_html__( 'Home', 'hurth' )
	);

	foreach ( hurth_nav_items() as $slug => $label ) {
		$page = get_page_by_path( $slug );

		if ( ! $page || 'publish' !== $page->post_status ) {
			continue;
		}

		printf(
			'<li class="%s"><a href="%s">%s</a></li>',
			(int) $current === (int) $page->ID ? 'current-menu-item' : '',
			esc_url( get_permalink( $page ) ),
			esc_html( $label )
		);
	}

	echo '</ul>';
}

/**
 * Whether a page has enough content to be worth linking from the home page.
 *
 * @param WP_Post $page Page object.
 * @return bool
 */
function hurth_page_has_content( $page ) {
	return strlen( trim( wp_strip_all_tags( $page->post_content ) ) ) > 60;
}

/**
 * Trim the automatic excerpt to a friendlier length.
 *
 * @param int $length Incoming length.
 * @return int
 */
function hurth_excerpt_length( $length ) {
	return 28;
}
add_filter( 'excerpt_length', 'hurth_excerpt_length' );

/**
 * Replace the default [...] excerpt suffix.
 *
 * @return string
 */
function hurth_excerpt_more() {
	return '&hellip;';
}
add_filter( 'excerpt_more', 'hurth_excerpt_more' );
