<?php
/**
 * Bengali structure (03 §1, 06 §3.4), switched off by AQUARIENDE_ENABLE_BN.
 *
 * A Bengali item is a normal page or guide with the meta _aq_lang = bn and
 * _aq_translation_of = <English content key>. Its URL is the English URL
 * under /bn/ (English slugs, so WhatsApp shares stay readable). hreflang and
 * the language switch appear only for items that have a real translation.
 *
 * @package Aquariende
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Language of the current request.
 *
 * @return string en|bn
 */
function aquariende_current_lang() {
	if ( ! AQUARIENDE_ENABLE_BN ) {
		return 'en';
	}
	if ( get_query_var( 'aq_lang' ) === 'bn' ) {
		return 'bn';
	}
	if ( is_singular() && 'bn' === get_post_meta( get_queried_object_id(), '_aq_lang', true ) ) {
		return 'bn';
	}
	return 'en';
}

/**
 * Find the Bengali item translating an English content key.
 *
 * @param string $key English content key.
 * @return int
 */
function aquariende_bn_id_for( $key ) {
	if ( '' === $key ) {
		return 0;
	}
	$found = get_posts(
		array(
			'post_type'   => array( 'page', 'aq_guide' ),
			'post_status' => 'publish',
			'numberposts' => 1,
			'fields'      => 'ids',
			'meta_query'  => array( // phpcs:ignore WordPress.DB.SlowDBQuery
				array(
					'key'   => '_aq_lang',
					'value' => 'bn',
				),
				array(
					'key'   => '_aq_translation_of',
					'value' => $key,
				),
			),
		)
	);
	return $found ? (int) $found[0] : 0;
}

/**
 * /bn/ URL of a Bengali item: the English URL with /bn/ in front of the path.
 *
 * @param string $en_url English URL.
 * @return string
 */
function aquariende_bn_url( $en_url ) {
	$home = trailingslashit( home_url() );
	$path = ltrim( substr( $en_url, strlen( $home ) ), '/' );
	return $home . 'bn/' . $path;
}

/**
 * The EN/BN pair for the current page, or an empty array.
 *
 * @return array{en?:string,bn?:string}
 */
function aquariende_translation_pair() {
	if ( ! AQUARIENDE_ENABLE_BN || ! is_singular() && ! is_front_page() ) {
		return array();
	}
	$id = is_front_page() ? (int) get_option( 'page_on_front' ) : get_queried_object_id();
	if ( 'bn' === get_post_meta( $id, '_aq_lang', true ) ) {
		$en_key = (string) get_post_meta( $id, '_aq_translation_of', true );
		$en_id  = aquariende_content_id( $en_key );
		if ( ! $en_id ) {
			return array();
		}
		$en = ( 'home' === $en_key ) ? home_url( '/' ) : get_permalink( $en_id );
		return array(
			'en' => $en,
			'bn' => aquariende_bn_url( $en ),
		);
	}
	$key   = aquariende_key_of( $id );
	$bn_id = aquariende_bn_id_for( $key );
	if ( ! $bn_id ) {
		return array();
	}
	$en = is_front_page() ? home_url( '/' ) : get_permalink( $id );
	return array(
		'en' => $en,
		'bn' => aquariende_bn_url( $en ),
	);
}

/**
 * hreflang links (only for real pairs) plus x-default → English.
 */
function aquariende_hreflang() {
	$pair = aquariende_translation_pair();
	if ( empty( $pair['en'] ) || empty( $pair['bn'] ) ) {
		return;
	}
	echo '<link rel="alternate" hreflang="en" href="' . esc_url( $pair['en'] ) . '">' . "\n";
	echo '<link rel="alternate" hreflang="bn" href="' . esc_url( $pair['bn'] ) . '">' . "\n";
	echo '<link rel="alternate" hreflang="x-default" href="' . esc_url( $pair['en'] ) . '">' . "\n";
}
add_action( 'wp_head', 'aquariende_hreflang', 3 );

/**
 * <html lang> per page.
 *
 * @param string $output Attributes.
 * @return string
 */
function aquariende_language_attributes( $output ) {
	if ( 'bn' === aquariende_current_lang() ) {
		return preg_replace( '/lang="[^"]*"/', 'lang="bn"', $output );
	}
	return $output;
}
add_filter( 'language_attributes', 'aquariende_language_attributes' );

/**
 * Rewrite /bn/<english path>/ to the Bengali item (only when enabled).
 */
function aquariende_bn_rewrite() {
	if ( ! AQUARIENDE_ENABLE_BN ) {
		return;
	}
	add_rewrite_rule( '^bn/?$', 'index.php?aq_lang=bn&aq_bn_path=', 'top' );
	add_rewrite_rule( '^bn/(.+?)/?$', 'index.php?aq_lang=bn&aq_bn_path=$matches[1]', 'top' );
}
add_action( 'init', 'aquariende_bn_rewrite' );

/**
 * Query vars.
 *
 * @param array $vars Vars.
 * @return array
 */
function aquariende_bn_query_vars( $vars ) {
	$vars[] = 'aq_lang';
	$vars[] = 'aq_bn_path';
	return $vars;
}
add_filter( 'query_vars', 'aquariende_bn_query_vars' );

/**
 * Resolve a /bn/ request to the Bengali item of the English page at that path.
 *
 * @param array $vars Query vars.
 * @return array
 */
function aquariende_bn_request( $vars ) {
	if ( ! AQUARIENDE_ENABLE_BN || empty( $vars['aq_lang'] ) || 'bn' !== $vars['aq_lang'] ) {
		return $vars;
	}
	$path  = isset( $vars['aq_bn_path'] ) ? trim( $vars['aq_bn_path'], '/' ) : '';
	$en_id = '' === $path ? (int) get_option( 'page_on_front' ) : url_to_postid( home_url( '/' . $path . '/' ) );
	$bn_id = $en_id ? aquariende_bn_id_for( aquariende_key_of( $en_id ) ) : 0;
	if ( ! $bn_id ) {
		return array( 'error' => '404' );
	}
	$post = get_post( $bn_id );
	return array(
		'p'         => $bn_id,
		'post_type' => $post->post_type,
		'aq_lang'   => 'bn',
	);
}
add_filter( 'request', 'aquariende_bn_request' );

/**
 * Bengali items live at /bn/…: point their permalinks there.
 *
 * @param string  $url  URL.
 * @param WP_Post $post Post.
 * @return string
 */
function aquariende_bn_permalink( $url, $post ) {
	$post = get_post( $post );
	if ( ! AQUARIENDE_ENABLE_BN || ! $post || 'bn' !== get_post_meta( $post->ID, '_aq_lang', true ) ) {
		return $url;
	}
	$en_key = (string) get_post_meta( $post->ID, '_aq_translation_of', true );
	$en_id  = aquariende_content_id( $en_key );
	if ( ! $en_id ) {
		return $url;
	}
	$en = ( 'home' === $en_key ) ? home_url( '/' ) : get_permalink( $en_id );
	return aquariende_bn_url( $en );
}
add_filter( 'page_link', 'aquariende_bn_permalink', 10, 2 );
add_filter( 'post_type_link', 'aquariende_bn_permalink', 10, 2 );
