<?php
/**
 * Site header: transparent over the dark top band, paper after scrolling.
 * Navigation: Study in Germany ▾ · Services ▾ · How it works · About (04 §2).
 *
 * @package Aquariende
 */

?><!doctype html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<a class="skip-link" href="#main">Skip to content</a>

<header class="site-header" id="site-header">
	<div class="wrap header-bar">
		<?php aquariende_logo(); ?>

		<nav class="primary-nav" aria-label="Main">
			<?php
			wp_nav_menu(
				array(
					'theme_location' => 'primary',
					'container'      => false,
					'menu_class'     => 'nav-list',
					'depth'          => 2,
					'walker'         => new Aquariende_Nav_Walker( 'nav' ),
					'fallback_cb'    => 'aquariende_menu_fallback',
				)
			);
			?>
		</nav>

		<div class="header-actions">
			<?php get_template_part( 'template-parts/lang-switch' ); ?>
			<a class="login-link" href="<?php echo esc_url( aquariende_info( 'portal_url' ) ); ?>" data-portal="1">
				<?php echo aquariende_icon( 'lock' ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
				<span>Student login</span>
			</a>
			<?php echo aquariende_wa_button( 'header', array( 'class' => 'btn btn-primary btn-sm header-wa', 'label' => 'WhatsApp' ) ); // phpcs:ignore WordPress.Security.EscapeOutput -- escaped inside. ?>
			<button class="menu-toggle" type="button" aria-expanded="false" aria-controls="mobile-sheet">
				<span class="menu-toggle-open"><?php echo aquariende_icon( 'menu' ); // phpcs:ignore WordPress.Security.EscapeOutput ?></span>
				<span class="menu-toggle-close"><?php echo aquariende_icon( 'close' ); // phpcs:ignore WordPress.Security.EscapeOutput ?></span>
				<span class="screen-reader-text">Menu</span>
			</button>
		</div>
	</div>

	<div class="mobile-sheet" id="mobile-sheet" hidden>
		<nav class="sheet-nav" aria-label="Main (mobile)">
			<?php
			wp_nav_menu(
				array(
					'theme_location' => 'primary',
					'container'      => false,
					'menu_class'     => 'sheet-list',
					'depth'          => 2,
					'walker'         => new Aquariende_Nav_Walker( 'sheet' ),
					'fallback_cb'    => 'aquariende_menu_fallback',
				)
			);
			?>
		</nav>
		<div class="sheet-foot">
			<?php echo aquariende_wa_button( 'menu', array( 'class' => 'btn btn-primary btn-lg btn-block' ) ); // phpcs:ignore WordPress.Security.EscapeOutput -- escaped inside. ?>
			<a class="sheet-login" href="<?php echo esc_url( aquariende_info( 'portal_url' ) ); ?>" data-portal="1">
				<?php echo aquariende_icon( 'lock' ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
				<span>Student login</span>
			</a>
			<?php get_template_part( 'template-parts/lang-switch' ); ?>
		</div>
	</div>
</header>
