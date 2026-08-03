<?php
/**
 * Hurth child theme functions.
 *
 * @package Hurth
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Prevent direct access.
}

define( 'HURTH_VERSION', '1.0.0' );

/**
 * Load the child stylesheet after the Hello Elementor parent styles.
 *
 * Priority 20 ensures the parent theme has registered its handles first, so
 * the dependency array below resolves correctly.
 */
function hurth_enqueue_styles() {
	wp_enqueue_style(
		'hurth-style',
		get_stylesheet_directory_uri() . '/style.css',
		array(
			'hello-elementor',
			'hello-elementor-theme-style',
			'hello-elementor-header-footer',
		),
		HURTH_VERSION
	);
}
add_action( 'wp_enqueue_scripts', 'hurth_enqueue_styles', 20 );
