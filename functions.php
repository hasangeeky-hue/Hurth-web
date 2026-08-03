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
 * Fallback navigation when no menu has been assigned yet.
 *
 * Lists published pages so the site is never left without navigation.
 */
function hurth_menu_fallback() {
	echo '<ul>';
	wp_list_pages( array(
		'title_li' => '',
		'depth'    => 1,
		'exclude'  => get_option( 'page_on_front' ),
	) );
	echo '</ul>';
}

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
		'street'  => 'Luxemburger Straße 96',
		'city'    => '50354 Hürth',
		'region'  => 'Hürth · Köln',
		'areas'   => 'Hürth, Efferen, Frechen, Brühl, Sülz, Ehrenfeld & Köln',
		'tagline' => 'Mobile & DHL Service Center',
	);

	return isset( $info[ $key ] ) ? $info[ $key ] : '';
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
