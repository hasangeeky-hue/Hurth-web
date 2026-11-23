<?php
/**
 * Footer (04 §3) and the sticky mobile WhatsApp bar.
 *
 * @package Aquariende
 */

$aq_link = function ( $key, $label ) {
	if ( 'guides' !== $key && ! aquariende_has_page( $key ) ) {
		return;
	}
	echo '<li><a href="' . esc_url( aquariende_page_url( $key ) ) . '">' . esc_html( $label ) . '</a></li>';
};
?>
<footer class="site-footer dark">
	<div class="wrap footer-grid">
		<div class="footer-brand">
			<?php aquariende_logo(); ?>
			<p><?php echo esc_html( aquariende_info( 'positioning_short' ) ); ?>.</p>
			<p><strong>No one can guarantee a visa.</strong></p>
		</div>

		<nav class="footer-col" aria-label="Guides">
			<h2 class="footer-head">Guides</h2>
			<ul>
				<?php
				$aq_link( 'study-in-germany-from-bangladesh', 'Study in Germany from Bangladesh' );
				$aq_link( 'guide-visa', 'Student visa' );
				$aq_link( 'guide-blocked', 'Blocked account' );
				$aq_link( 'guide-vpd', 'uni-assist and VPD' );
				$aq_link( 'guide-cost', 'Costs' );
				$aq_link( 'glossary', 'Glossary' );
				$aq_link( 'guides', 'All guides' );
				?>
			</ul>
		</nav>

		<nav class="footer-col" aria-label="Company">
			<h2 class="footer-head">Company</h2>
			<ul>
				<?php
				$aq_link( 'about', 'About' );
				$aq_link( 'how-it-works', 'How it works' );
				$aq_link( 'services', 'Services' );
				$aq_link( 'for-parents', 'For parents' );
				$aq_link( 'faq', 'FAQ' );
				$aq_link( 'blog', 'Updates' );
				?>
			</ul>
		</nav>

		<div class="footer-col">
			<h2 class="footer-head">Contact</h2>
			<ul class="footer-contact">
				<li>
					<a href="<?php echo esc_url( aquariende_whatsapp_url( aquariende_wa_context()['message'], aquariende_wa_context()['code'] ) ); ?>" target="_blank" rel="noopener" data-wa="<?php echo esc_attr( aquariende_wa_context()['code'] . ':footer' ); ?>">
						<?php echo aquariende_icon( 'whatsapp' ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
						<span><?php echo esc_html( aquariende_info( 'whatsapp' ) ); ?></span><span class="screen-reader-text"> (opens WhatsApp)</span>
					</a>
				</li>
				<li>
					<a href="mailto:<?php echo esc_attr( aquariende_info( 'email' ) ); ?>" data-email="1">
						<?php echo aquariende_icon( 'mail' ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
						<span><?php echo esc_html( aquariende_info( 'email' ) ); ?></span>
					</a>
				</li>
				<?php $aq_link( 'student-login', 'Student login' ); ?>
				<?php if ( aquariende_info( 'facebook_page' ) ) : ?>
					<li><a href="<?php echo esc_url( aquariende_info( 'facebook_page' ) ); ?>" target="_blank" rel="noopener" data-ext="facebook.com"><?php echo aquariende_icon( 'facebook' ); // phpcs:ignore WordPress.Security.EscapeOutput ?><span>Facebook page<span class="screen-reader-text"> (opens Facebook)</span></span></a></li>
				<?php endif; ?>
				<?php if ( aquariende_info( 'facebook_group' ) ) : ?>
					<li><a href="<?php echo esc_url( aquariende_info( 'facebook_group' ) ); ?>" rel="noopener">Facebook community<span class="screen-reader-text"> (external site)</span></a></li>
				<?php endif; ?>
				<?php if ( aquariende_show_address() ) : ?>
					<li class="footer-address"><span><?php echo esc_html( aquariende_info( 'address' ) ); ?></span></li>
				<?php endif; ?>
			</ul>
		</div>

		<nav class="footer-col" aria-label="Legal">
			<h2 class="footer-head">Legal</h2>
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
		<p>We never take payment on this website. Your portal link comes by email after you pay the consultation fee.</p>
		<p>© <?php echo esc_html( wp_date( 'Y' ) ); ?> Aquariende</p>
	</div>
</footer>

<div class="sticky-cta" id="sticky-cta">
	<?php echo aquariende_wa_button( 'sticky', array( 'class' => 'btn btn-primary btn-block' ) ); // phpcs:ignore WordPress.Security.EscapeOutput -- escaped inside. ?>
</div>

<?php wp_footer(); ?>
</body>
</html>
