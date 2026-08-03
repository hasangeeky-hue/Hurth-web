<?php
/**
 * Site header.
 *
 * @package Hurth
 */

$hurth_state = hurth_open_state();
$hurth_lang  = hurth_lang();
$hurth_q     = ( 'en' === $hurth_lang ) ? '?lang=en' : '';
?><!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="theme-color" content="#0059a9">
	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<a class="skip-link" href="#main"><?php echo esc_html( hurth_t( 'skip' ) ); ?></a>

<div class="topbar">
	<div class="wrap topbar__inner">

		<a href="tel:<?php echo esc_attr( hurth_info( 'phone_href' ) ); ?>">
			<?php echo esc_html( hurth_info( 'phone' ) ); ?>
		</a>

		<span class="status <?php echo $hurth_state['open'] ? '' : 'status--closed'; ?>">
			<?php
			if ( $hurth_state['open'] ) {
				echo esc_html( hurth_t( 'open_now' ) . ' · ' . hurth_t( 'until' ) . ' ' . $hurth_state['until'] );
			} else {
				echo esc_html( hurth_t( 'closed_now' ) );
			}
			?>
		</span>

		<span class="topbar__hide-sm">
			<?php echo esc_html( hurth_info( 'street' ) . ', ' . hurth_info( 'zip' ) . ' ' . hurth_info( 'town' ) ); ?>
		</span>

		<span class="topbar__spacer"></span>

		<nav class="lang-switch" aria-label="Sprache / Language">
			<?php
			/*
			 * Points at the equivalent page in the other language, not at the
			 * same URL with a query string. Where no translation exists the
			 * visitor stays put instead of landing on a 404.
			 */
			?>
			<a href="<?php echo esc_url( hurth_alt_url( 'de' ) ); ?>"
				aria-current="<?php echo 'de' === $hurth_lang ? 'true' : 'false'; ?>" hreflang="de">DE</a>
			<a href="<?php echo esc_url( hurth_alt_url( 'en' ) ); ?>"
				aria-current="<?php echo 'en' === $hurth_lang ? 'true' : 'false'; ?>" hreflang="en">EN</a>
		</nav>

	</div>
</div>

<header class="site-header">
	<div class="wrap site-header__bar">

		<a class="site-brand" href="<?php echo esc_url( home_url( '/' ) . $hurth_q ); ?>">
			<span class="site-brand__mark" aria-hidden="true">F</span>
			<span class="site-brand__text">
				<?php echo esc_html( hurth_info( 'name' ) ); ?>
				<span class="site-brand__tag"><?php echo esc_html( hurth_info( 'city_tag' ) ); ?></span>
			</span>
		</a>

		<button class="nav-toggle" type="button" aria-expanded="false" aria-controls="site-nav">
			<span class="screen-reader-text"><?php echo esc_html( hurth_t( 'menu' ) ); ?></span>
			<svg width="22" height="22" viewBox="0 0 24 24" aria-hidden="true" fill="none"
				stroke="currentColor" stroke-width="2" stroke-linecap="round">
				<path d="M3 6h18M3 12h18M3 18h18" />
			</svg>
		</button>

		<nav class="site-nav" id="site-nav" aria-label="<?php esc_attr_e( 'Primary', 'hurth' ); ?>">
			<?php
			wp_nav_menu( array(
				'theme_location' => 'primary',
				'container'      => false,
				'depth'          => 2,
				'fallback_cb'    => 'hurth_menu_fallback',
			) );
			?>
		</nav>

	</div>
</header>

<main id="main">
