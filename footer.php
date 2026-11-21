<?php
/**
 * Site footer and the sticky mobile WhatsApp bar.
 *
 * @package Aquariende
 */

?>
<footer class="site-footer dark">
	<div class="wrap footer-grid">
		<div class="footer-brand">
			<?php aquariende_logo(); ?>
			<p><?php esc_html_e( 'We provide solutions for studying in German universities, including obtaining German visas.', 'aquariende' ); ?></p>
		</div>

		<nav class="footer-col" aria-label="<?php esc_attr_e( 'Footer', 'aquariende' ); ?>">
			<h2 class="footer-head"><?php esc_html_e( 'Explore', 'aquariende' ); ?></h2>
			<ul>
				<?php foreach ( aquariende_default_nav() as $aq_label => $aq_url ) : ?>
					<li><a href="<?php echo esc_url( $aq_url ); ?>"><?php echo esc_html( $aq_label ); ?></a></li>
				<?php endforeach; ?>
				<li><a href="<?php echo esc_url( aquariende_info( 'portal_url' ) ); ?>"><?php esc_html_e( 'Student login', 'aquariende' ); ?></a></li>
			</ul>
		</nav>

		<div class="footer-col">
			<h2 class="footer-head"><?php esc_html_e( 'Contact', 'aquariende' ); ?></h2>
			<ul class="footer-contact">
				<li>
					<a href="<?php echo esc_url( aquariende_whatsapp_url() ); ?>" target="_blank" rel="noopener">
						<?php echo aquariende_icon( 'whatsapp' ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
						<span><?php echo esc_html( aquariende_info( 'whatsapp' ) ); ?></span>
					</a>
				</li>
				<li>
					<a href="mailto:<?php echo esc_attr( aquariende_info( 'email' ) ); ?>">
						<?php echo aquariende_icon( 'mail' ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
						<span><?php echo esc_html( aquariende_info( 'email' ) ); ?></span>
					</a>
				</li>
				<?php if ( aquariende_info( 'facebook_group' ) ) : ?>
					<li>
						<a href="<?php echo esc_url( aquariende_info( 'facebook_group' ) ); ?>" target="_blank" rel="noopener">
							<?php echo aquariende_icon( 'facebook' ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
							<span><?php esc_html_e( 'Facebook community', 'aquariende' ); ?></span>
						</a>
					</li>
				<?php endif; ?>
				<?php if ( aquariende_show_address() ) : ?>
					<li class="footer-address">
						<?php echo aquariende_icon( 'pin' ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
						<span><?php echo esc_html( aquariende_info( 'address' ) ); ?></span>
					</li>
				<?php endif; ?>
			</ul>
		</div>

		<nav class="footer-col" aria-label="<?php esc_attr_e( 'Legal', 'aquariende' ); ?>">
			<h2 class="footer-head"><?php esc_html_e( 'Legal', 'aquariende' ); ?></h2>
			<?php
			wp_nav_menu(
				array(
					'theme_location' => 'footer',
					'container'      => false,
					'menu_class'     => 'footer-legal',
					'depth'          => 1,
					'fallback_cb'    => 'aquariende_legal_fallback',
				)
			);
			?>
		</nav>
	</div>

	<div class="wrap footer-base">
		<p>
			<?php
			/* translators: %s: year. */
			echo esc_html( sprintf( __( 'Copyright © %s Aquariende. All rights reserved.', 'aquariende' ), wp_date( 'Y' ) ) );
			?>
		</p>
		<p><?php echo esc_html( aquariende_info( 'slogan' ) ); ?></p>
	</div>
</footer>

<div class="sticky-cta" id="sticky-cta" aria-hidden="true">
	<a class="btn btn-primary btn-block" href="<?php echo esc_url( aquariende_whatsapp_url() ); ?>" target="_blank" rel="noopener" tabindex="-1">
		<?php echo aquariende_icon( 'whatsapp' ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
		<span><?php esc_html_e( 'Message us on WhatsApp', 'aquariende' ); ?></span>
	</a>
</div>

<?php wp_footer(); ?>
</body>
</html>
