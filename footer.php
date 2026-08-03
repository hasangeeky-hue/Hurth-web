<?php
/**
 * Site footer.
 *
 * @package Hurth
 */

?>
</main>

<footer class="site-footer">
	<div class="wrap">

		<div class="footer-grid">

			<div>
				<h3><?php echo esc_html( get_bloginfo( 'name' ) ); ?></h3>
				<p>
					<?php echo esc_html( hurth_info( 'tagline' ) ); ?><br>
					<?php echo esc_html( hurth_info( 'street' ) ); ?><br>
					<?php echo esc_html( hurth_info( 'city' ) ); ?>
				</p>
			</div>

			<div>
				<h3><?php esc_html_e( 'Pages', 'hurth' ); ?></h3>
				<?php
				if ( has_nav_menu( 'footer' ) ) {
					wp_nav_menu( array(
						'theme_location' => 'footer',
						'container'      => false,
						'depth'          => 1,
					) );
				} else {
					hurth_menu_fallback();
				}
				?>
			</div>

			<div>
				<h3><?php esc_html_e( 'Service area', 'hurth' ); ?></h3>
				<p><?php echo esc_html( hurth_info( 'areas' ) ); ?></p>
			</div>

		</div>

		<div class="site-footer__legal">
			<span>
				&copy; <?php echo esc_html( gmdate( 'Y' ) ); ?>
				<?php echo esc_html( get_bloginfo( 'name' ) ); ?>.
				<?php esc_html_e( 'All rights reserved.', 'hurth' ); ?>
			</span>
			<span><?php echo esc_html( hurth_info( 'region' ) ); ?></span>
		</div>

	</div>
</footer>

<?php wp_footer(); ?>
</body>
</html>
