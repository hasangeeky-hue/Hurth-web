<?php
/**
 * Site header.
 *
 * @package Hurth
 */

?><!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="https://gmpg.org/xfn/11">
	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<a class="skip-link" href="#main"><?php esc_html_e( 'Skip to content', 'hurth' ); ?></a>

<header class="site-header">
	<div class="wrap site-header__bar">

		<?php if ( has_custom_logo() ) : ?>
			<?php the_custom_logo(); ?>
		<?php else : ?>
			<a class="site-brand" href="<?php echo esc_url( home_url( '/' ) ); ?>">
				<?php
				$hurth_name = get_bloginfo( 'name' );
				$hurth_bits = explode( ' ', $hurth_name, 2 );
				echo esc_html( $hurth_bits[0] );
				if ( ! empty( $hurth_bits[1] ) ) {
					echo ' <span>' . esc_html( $hurth_bits[1] ) . '</span>';
				}
				?>
				<span class="site-brand__tag"><?php echo esc_html( hurth_info( 'tagline' ) ); ?></span>
			</a>
		<?php endif; ?>

		<button class="nav-toggle" type="button" aria-expanded="false" aria-controls="site-nav">
			<span class="screen-reader-text"><?php esc_html_e( 'Toggle menu', 'hurth' ); ?></span>
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
