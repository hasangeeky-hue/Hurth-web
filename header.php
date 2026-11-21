<?php
/**
 * Site header: transparent over the dark top band, paper after 80 px of scroll.
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
<a class="skip-link" href="#main"><?php esc_html_e( 'Skip to content', 'aquariende' ); ?></a>

<header class="site-header" id="site-header">
	<div class="wrap header-bar">
		<?php aquariende_logo(); ?>

		<nav class="primary-nav" aria-label="<?php esc_attr_e( 'Main', 'aquariende' ); ?>">
			<?php
			wp_nav_menu(
				array(
					'theme_location' => 'primary',
					'container'      => false,
					'menu_class'     => 'nav-list',
					'depth'          => 1,
					'fallback_cb'    => 'aquariende_menu_fallback',
				)
			);
			?>
		</nav>

		<div class="header-actions">
			<a class="login-link" href="<?php echo esc_url( aquariende_info( 'portal_url' ) ); ?>">
				<?php echo aquariende_icon( 'lock' ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
				<span><?php esc_html_e( 'Student login', 'aquariende' ); ?></span>
			</a>
			<a class="btn btn-primary btn-sm header-wa" href="<?php echo esc_url( aquariende_whatsapp_url() ); ?>" target="_blank" rel="noopener">
				<?php echo aquariende_icon( 'whatsapp' ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
				<span><?php esc_html_e( 'WhatsApp', 'aquariende' ); ?></span>
			</a>
			<button class="menu-toggle" type="button" aria-expanded="false" aria-controls="mobile-sheet">
				<span class="menu-toggle-open"><?php echo aquariende_icon( 'menu' ); // phpcs:ignore WordPress.Security.EscapeOutput ?></span>
				<span class="menu-toggle-close"><?php echo aquariende_icon( 'close' ); // phpcs:ignore WordPress.Security.EscapeOutput ?></span>
				<span class="screen-reader-text"><?php esc_html_e( 'Menu', 'aquariende' ); ?></span>
			</button>
		</div>
	</div>

	<div class="mobile-sheet" id="mobile-sheet" hidden>
		<nav class="sheet-nav" aria-label="<?php esc_attr_e( 'Main (mobile)', 'aquariende' ); ?>">
			<?php
			wp_nav_menu(
				array(
					'theme_location' => 'primary',
					'container'      => false,
					'menu_class'     => 'sheet-list',
					'depth'          => 1,
					'fallback_cb'    => 'aquariende_menu_fallback',
				)
			);
			?>
		</nav>
		<div class="sheet-foot">
			<a class="btn btn-primary btn-lg btn-block" href="<?php echo esc_url( aquariende_whatsapp_url() ); ?>" target="_blank" rel="noopener">
				<?php echo aquariende_icon( 'whatsapp' ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
				<span><?php esc_html_e( 'Message us on WhatsApp', 'aquariende' ); ?></span>
			</a>
			<a class="sheet-login" href="<?php echo esc_url( aquariende_info( 'portal_url' ) ); ?>">
				<?php echo aquariende_icon( 'lock' ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
				<span><?php esc_html_e( 'Student login', 'aquariende' ); ?></span>
			</a>
		</div>
	</div>
</header>
