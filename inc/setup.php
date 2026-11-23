<?php
/**
 * Theme setup, assets (self-hosted fonts, one stylesheet, one small script),
 * head clean-up and cache purge after a deploy.
 *
 * No third-party request is made on page load: no Google Fonts, no CDN, no
 * analytics script unless the owner adds a self-hosted one.
 *
 * @package Aquariende
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function aquariende_setup() {
	load_theme_textdomain( 'aquariende', AQUARIENDE_DIR . '/languages' );

	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'automatic-feed-links' );
	add_theme_support( 'responsive-embeds' );
	add_theme_support( 'html5', array( 'search-form', 'gallery', 'caption', 'style', 'script', 'navigation-widgets' ) );
	add_theme_support(
		'custom-logo',
		array(
			'height'      => 128,
			'width'       => 150,
			'flex-height' => true,
			'flex-width'  => true,
		)
	);

	register_nav_menus(
		array(
			'primary' => 'Primary menu',
			'footer'  => 'Footer menu (legal pages)',
		)
	);

	set_post_thumbnail_size( 960, 540, true );
}
add_action( 'after_setup_theme', 'aquariende_setup' );

function aquariende_content_width() {
	$GLOBALS['content_width'] = 720;
}
add_action( 'after_setup_theme', 'aquariende_content_width', 0 );

/**
 * Fonts preloaded on every English page: the display face (H1, the LCP
 * element) and the body face. Bengali loads by unicode-range only when
 * Bengali text is on the page.
 *
 * @return string[]
 */
function aquariende_preload_fonts() {
	return array(
		'assets/fonts/bricolage-grotesque-latin-var.woff2',
		'assets/fonts/ibm-plex-sans-latin-var.woff2',
	);
}

function aquariende_head_early() {
	foreach ( aquariende_preload_fonts() as $font ) {
		echo '<link rel="preload" href="' . esc_url( get_template_directory_uri() . '/' . $font ) . '" as="font" type="font/woff2" crossorigin>' . "\n";
	}
	echo '<meta name="theme-color" content="#0E2A33">' . "\n";
	if ( ! has_site_icon() ) {
		echo '<link rel="icon" type="image/png" href="' . esc_url( get_template_directory_uri() . '/assets/img/logo-mark.png' ) . '">' . "\n";
	}
}
add_action( 'wp_head', 'aquariende_head_early', 1 );

/**
 * Marks the page as scripted before it paints, so script-only states (closed picker panels,
 * unlit stops) never flash. Without scripts the page shows everything.
 */
function aquariende_js_flag() {
	echo "<script>document.documentElement.classList.add('js');</script>
";
}
add_action( 'wp_head', 'aquariende_js_flag', 0 );

function aquariende_assets() {
	wp_enqueue_style( 'aquariende', get_stylesheet_uri(), array(), AQUARIENDE_VERSION );
	// "The route": the shared colour, button and focus layer, and the home page.
	wp_enqueue_style( 'aquariende-route', get_template_directory_uri() . '/assets/css/route.css', array( 'aquariende' ), AQUARIENDE_VERSION );
	wp_enqueue_script(
		'aquariende-route',
		get_template_directory_uri() . '/assets/js/route.js',
		array(),
		AQUARIENDE_VERSION,
		array(
			'in_footer' => true,
			'strategy'  => 'defer',
		)
	);

	wp_enqueue_script(
		'aquariende',
		get_template_directory_uri() . '/assets/js/main.js',
		array(),
		AQUARIENDE_VERSION,
		array(
			'in_footer' => true,
			'strategy'  => 'defer',
		)
	);

	// The site's content is plain HTML, not blocks: no block library styles.
	wp_dequeue_style( 'wp-block-library' );
	wp_dequeue_style( 'wp-block-library-theme' );
	wp_dequeue_style( 'global-styles' );
	wp_dequeue_style( 'classic-theme-styles' );
}
add_action( 'wp_enqueue_scripts', 'aquariende_assets', 20 );

add_filter( 'should_load_separate_core_block_assets', '__return_true' );

/**
 * Defer for WordPress older than 6.3 (no loading strategies).
 *
 * @param string $tag    Script tag.
 * @param string $handle Handle.
 * @return string
 */
function aquariende_defer_scripts( $tag, $handle ) {
	if ( 'aquariende' === $handle && version_compare( get_bloginfo( 'version' ), '6.3', '<' ) && false === strpos( $tag, ' defer' ) ) {
		$tag = str_replace( ' src=', ' defer src=', $tag );
	}
	return $tag;
}
add_filter( 'script_loader_tag', 'aquariende_defer_scripts', 10, 2 );

/**
 * Remove the emoji script and styles (they also add a third-party DNS hint).
 */
function aquariende_disable_emoji() {
	remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
	remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
	remove_action( 'wp_print_styles', 'print_emoji_styles' );
	remove_action( 'admin_print_styles', 'print_emoji_styles' );
	remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
	remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );
	remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
	add_filter( 'emoji_svg_url', '__return_false' );
	if ( function_exists( 'wp_enqueue_emoji_styles' ) ) {
		remove_action( 'wp_enqueue_scripts', 'wp_enqueue_emoji_styles' );
		remove_action( 'admin_enqueue_scripts', 'wp_enqueue_emoji_styles' );
	}
	// Head noise that names no one and links nowhere useful.
	remove_action( 'wp_head', 'wp_generator' );
	remove_action( 'wp_head', 'rsd_link' );
	remove_action( 'wp_head', 'wlwmanifest_link' );
	remove_action( 'wp_head', 'wp_shortlink_wp_head' );
	remove_action( 'wp_head', 'wp_oembed_add_discovery_links' );
}
add_action( 'init', 'aquariende_disable_emoji' );

/**
 * Drop every third-party resource hint (s.w.org and the like).
 *
 * @param array  $urls          URLs.
 * @param string $relation_type Relation.
 * @return array
 */
function aquariende_resource_hints( $urls, $relation_type ) {
	if ( in_array( $relation_type, array( 'dns-prefetch', 'preconnect' ), true ) ) {
		$home = wp_parse_url( home_url(), PHP_URL_HOST );
		$urls = array_filter(
			$urls,
			function ( $url ) use ( $home ) {
				$href = is_array( $url ) ? ( isset( $url['href'] ) ? $url['href'] : '' ) : $url;
				$host = wp_parse_url( $href, PHP_URL_HOST );
				return ! $host || $host === $home;
			}
		);
	}
	return $urls;
}
add_filter( 'wp_resource_hints', 'aquariende_resource_hints', 10, 2 );

function aquariende_excerpt_length() {
	return 28;
}
add_filter( 'excerpt_length', 'aquariende_excerpt_length' );

function aquariende_excerpt_more() {
	return '…';
}
add_filter( 'excerpt_more', 'aquariende_excerpt_more' );

/**
 * Body classes.
 *
 * @param array $classes Classes.
 * @return array
 */
function aquariende_body_class( $classes ) {
	$classes[] = 'has-dark-top';
	return $classes;
}
add_filter( 'body_class', 'aquariende_body_class' );

/* -------------------------------------------------------------------------
 * Cache purge and rewrite flush after a deploy
 *
 * Deployer for Git replaces the theme files without touching the database,
 * so caches can keep serving old markup and new rewrite rules (custom post
 * types, /llms.txt) are unknown until flushed.
 * ---------------------------------------------------------------------- */

function aquariende_purge_caches() {
	if ( ! headers_sent() ) {
		header( 'X-LiteSpeed-Purge: *' );
	}
	do_action( 'litespeed_purge_all' );
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
	wp_cache_flush();
}

function aquariende_on_version_change() {
	if ( get_option( 'aquariende_deployed_version' ) === AQUARIENDE_VERSION ) {
		return;
	}
	update_option( 'aquariende_deployed_version', AQUARIENDE_VERSION, false );
	flush_rewrite_rules( false );
	aquariende_purge_caches();
}
add_action( 'init', 'aquariende_on_version_change', 99 );
add_action( 'dfg_after_package_install', 'aquariende_purge_caches', 20 );
add_action( 'after_switch_theme', 'aquariende_purge_caches' );

/**
 * The call-back form of version 1 is gone. Make sure any old cached form
 * posting to admin-post.php?action=aquariende_callback gets a plain 404.
 */
function aquariende_retired_callback() {
	status_header( 404 );
	exit;
}
add_action( 'admin_post_nopriv_aquariende_callback', 'aquariende_retired_callback' );
add_action( 'admin_post_aquariende_callback', 'aquariende_retired_callback' );
