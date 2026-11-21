<?php
/**
 * Front-page hero: headline, one action, and the open file.
 *
 * @package Aquariende
 */

?>
<section class="hero dark" aria-labelledby="hero-title">
	<div class="wrap hero-grid">
		<div class="hero-copy">
			<h1 id="hero-title" class="hero-title">
				<?php
				/* translators: %s: highlighted phrase "to fulfill your dream!" */
				$aq_h1 = sprintf( esc_html__( 'We\'re always here %s', 'aquariende' ), '<span class="hl-mark">' . esc_html__( 'to fulfill your dream!', 'aquariende' ) . '</span>' );
				echo $aq_h1; // phpcs:ignore WordPress.Security.EscapeOutput -- both parts escaped above.
				?>
			</h1>
			<p class="hero-lede"><?php esc_html_e( 'Aquariende is an online based higher education consulting platform that enables you to process your university admission and visa in a manner that is simple and transparent.', 'aquariende' ); ?></p>
			<div class="hero-actions">
				<a class="btn btn-primary btn-lg" href="<?php echo esc_url( aquariende_whatsapp_url() ); ?>" target="_blank" rel="noopener">
					<?php echo aquariende_icon( 'whatsapp' ); // phpcs:ignore WordPress.Security.EscapeOutput -- static SVG. ?>
					<span><?php esc_html_e( 'Message us on WhatsApp', 'aquariende' ); ?></span>
				</a>
				<a class="btn-text" href="#how"><?php esc_html_e( 'See how it works', 'aquariende' ); ?></a>
			</div>
			<p class="hero-note">
				<?php
				/* translators: %s: WhatsApp number. */
				echo esc_html( sprintf( __( 'WhatsApp %s · English or Bengali', 'aquariende' ), aquariende_info( 'whatsapp' ) ) );
				?>
			</p>
		</div>
		<div class="hero-file">
			<?php
			get_template_part(
				'template-parts/file-card',
				null,
				array(
					'stage'   => 1,
					'current' => 2,
					'id'      => 'hero-file',
					'class'   => 'file-card--hero',
					'caption' => true,
				)
			);
			?>
		</div>
	</div>
</section>
