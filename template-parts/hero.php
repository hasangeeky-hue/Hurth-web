<?php
/**
 * Front-page hero: the positioning H1, the answer block, one action, and the
 * SVG "open file" (no raster image, so the LCP element is the text).
 *
 * @package Aquariende
 */

$aq_front  = (int) get_option( 'page_on_front' );
$aq_answer = $aq_front ? aquariende_page_meta( $aq_front, '_aq_answer' ) : '';
?>
<section class="hero dark" aria-labelledby="hero-title">
	<div class="wrap hero-grid">
		<div class="hero-copy">
			<h1 id="hero-title" class="hero-title">Study in Germany from Bangladesh, <span class="hl-mark">in your own name</span></h1>
			<?php get_template_part( 'template-parts/answer', null, array( 'text' => $aq_answer ) ); ?>
			<div class="hero-actions">
				<?php echo aquariende_wa_button( 'hero', array( 'class' => 'btn btn-primary btn-lg' ) ); // phpcs:ignore WordPress.Security.EscapeOutput -- escaped inside. ?>
				<a class="btn-text" href="<?php echo esc_url( aquariende_page_url( 'how-it-works' ) ); ?>">What happens after you message us</a>
			</div>
			<p class="hero-note">WhatsApp <?php echo esc_html( aquariende_info( 'whatsapp' ) ); ?> · English or Bengali · or email <?php echo aquariende_email_link(); // phpcs:ignore WordPress.Security.EscapeOutput ?></p>
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
