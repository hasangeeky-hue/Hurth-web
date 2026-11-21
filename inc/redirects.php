<?php
/**
 * Old-URL redirects (301) and removed pages (410), from inc/content/redirects.php.
 * Runs on template_redirect before WordPress guesses a redirect itself.
 *
 * @package Aquariende
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * The redirect map.
 *
 * @return array<string,string>
 */
function aquariende_redirect_map() {
	$file = AQUARIENDE_DIR . '/inc/content/redirects.php';
	$map  = is_readable( $file ) ? include $file : array();
	return (array) apply_filters( 'aquariende_redirect_map', is_array( $map ) ? $map : array() );
}

/**
 * Where an old path goes: a content key, 'gone', or ''.
 *
 * @param string $path Path without slashes at the ends.
 * @return string
 */
function aquariende_redirect_target( $path ) {
	$path = strtolower( trim( $path, '/' ) );
	if ( '' === $path ) {
		return '';
	}
	$map = aquariende_redirect_map();
	if ( isset( $map[ $path ] ) ) {
		return $map[ $path ];
	}
	foreach ( $map as $from => $to ) {
		if ( '*' === substr( $from, -1 ) ) {
			$base = rtrim( $from, '*' );
			if ( $path === $base || 0 === strpos( $path, $base . '/' ) ) {
				return $to;
			}
		}
	}
	return '';
}

/**
 * Send a 410 Gone with a small page.
 */
function aquariende_send_gone() {
	global $wp_query;
	status_header( 410 );
	nocache_headers();
	if ( $wp_query ) {
		$wp_query->is_404 = false;
	}
	get_template_part( 'template-parts/gone' );
	exit;
}

function aquariende_old_url_redirect() {
	// Old WooCommerce links: ?add-to-cart=… is gone wherever it appears.
	if ( isset( $_GET['add-to-cart'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification -- read-only check.
		aquariende_send_gone();
	}

	// Team pages exist only with the member's consent.
	if ( is_singular( 'aq_person' ) && ! aquariende_person_ok( get_queried_object() ) ) {
		global $wp_query;
		$wp_query->set_404();
		status_header( 404 );
		nocache_headers();
		return;
	}

	if ( ! is_404() ) {
		return;
	}
	$uri    = isset( $_SERVER['REQUEST_URI'] ) ? wp_unslash( $_SERVER['REQUEST_URI'] ) : ''; // phpcs:ignore WordPress.Security.ValidatedSanitizedInput -- compared only.
	$path   = (string) wp_parse_url( $uri, PHP_URL_PATH );
	$home   = (string) wp_parse_url( home_url( '/' ), PHP_URL_PATH );
	$path   = ( '' !== $home && '/' !== $home && 0 === strpos( $path, $home ) ) ? substr( $path, strlen( $home ) ) : $path;
	$target = aquariende_redirect_target( rawurldecode( $path ) );
	if ( '' === $target ) {
		return;
	}
	if ( 'gone' === $target ) {
		aquariende_send_gone();
	}
	if ( 'home' === $target ) {
		wp_safe_redirect( home_url( '/' ), 301, 'Aquariende' );
		exit;
	}
	$id = aquariende_content_id( $target );
	if ( $id ) {
		wp_safe_redirect( get_permalink( $id ), 301, 'Aquariende' );
		exit;
	}
}
add_action( 'template_redirect', 'aquariende_old_url_redirect', 1 );
