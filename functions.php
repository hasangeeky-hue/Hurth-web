<?php
/**
 * Aquariende theme functions (v2).
 *
 * Classic standalone theme: no parent theme, no page builder, no plugin
 * dependencies. This file only defines the settings and loads inc/.
 *
 * @package Aquariende
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/* -------------------------------------------------------------------------
 * Settings. Override any of these in wp-config.php, above "That's all".
 * ---------------------------------------------------------------------- */

// Client portal sign-in. The /student-login/ page links here.
defined( 'AQUARIENDE_PORTAL_URL' ) || define( 'AQUARIENDE_PORTAL_URL', 'https://ops.72-62-90-174.sslip.io/login' );

// URL of the community Facebook group. Empty hides every Facebook link.
defined( 'AQUARIENDE_FACEBOOK_GROUP_URL' ) || define( 'AQUARIENDE_FACEBOOK_GROUP_URL', '' );

// Profiles the owner controls and has confirmed, comma separated (schema sameAs). Empty by default.
defined( 'AQUARIENDE_SAMEAS' ) || define( 'AQUARIENDE_SAMEAS', '' );

// Show the Pabna office address on the Contact page, footer and schema.
defined( 'AQUARIENDE_SHOW_ADDRESS' ) || define( 'AQUARIENDE_SHOW_ADDRESS', false );

// Bengali (/bn/) pages, hreflang pairs and the language switch. Off until Bengali pages exist.
defined( 'AQUARIENDE_ENABLE_BN' ) || define( 'AQUARIENDE_ENABLE_BN', false );

// Force noindex,nofollow and a disallow-all robots.txt (staging). Hosts ending in hostingersite.com are always treated as staging.
defined( 'AQUARIENDE_NOINDEX' ) || define( 'AQUARIENDE_NOINDEX', false );

// Optional IndexNow key (production only). Empty disables IndexNow.
defined( 'AQUARIENDE_INDEXNOW_KEY' ) || define( 'AQUARIENDE_INDEXNOW_KEY', '' );

define( 'AQUARIENDE_VERSION', wp_get_theme( get_template() )->get( 'Version' ) ? wp_get_theme( get_template() )->get( 'Version' ) : '2.0.0' );
define( 'AQUARIENDE_DIR', get_template_directory() );

require_once AQUARIENDE_DIR . '/inc/data.php';
require_once AQUARIENDE_DIR . '/inc/helpers.php';
require_once AQUARIENDE_DIR . '/inc/setup.php';
require_once AQUARIENDE_DIR . '/inc/post-types.php';
require_once AQUARIENDE_DIR . '/inc/components.php';
require_once AQUARIENDE_DIR . '/inc/seo.php';
require_once AQUARIENDE_DIR . '/inc/schema.php';
require_once AQUARIENDE_DIR . '/inc/i18n-bn.php';
require_once AQUARIENDE_DIR . '/inc/content-installer.php';
require_once AQUARIENDE_DIR . '/inc/redirects.php';

if ( is_admin() ) {
	require_once AQUARIENDE_DIR . '/inc/admin.php';
}

if ( defined( 'WP_CLI' ) && WP_CLI ) {
	require_once AQUARIENDE_DIR . '/inc/cli.php';
}
