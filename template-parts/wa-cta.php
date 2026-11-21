<?php
/**
 * The page's one call to action: WhatsApp with the page-coded message, the
 * email address beside it as a plain link, and what happens next.
 *
 * Args: title, text, steps (bool, default true).
 *
 * @package Aquariende
 */

$aq_args  = isset( $args ) && is_array( $args ) ? $args : array();
$aq_title = isset( $aq_args['title'] ) ? $aq_args['title'] : 'Questions about your own case?';
$aq_text  = isset( $aq_args['text'] ) ? $aq_args['text'] : 'Message us on WhatsApp. A team member calls you from Germany within 24 hours. Nothing is paid on this website.';
$aq_steps = ! isset( $aq_args['steps'] ) || $aq_args['steps'];
?>
<section class="cta dark" id="start" aria-labelledby="cta-title">
	<div class="wrap cta-grid<?php echo $aq_steps ? '' : ' cta-grid--solo'; ?>">
		<div class="cta-main">
			<h2 id="cta-title" class="section-title"><?php echo esc_html( $aq_title ); ?></h2>
			<p class="cta-text"><?php echo esc_html( $aq_text ); ?></p>
			<div class="cta-actions">
				<?php echo aquariende_wa_button( 'cta', array( 'class' => 'btn btn-primary btn-lg' ) ); // phpcs:ignore WordPress.Security.EscapeOutput -- escaped inside. ?>
				<p class="cta-alt">
					<span>WhatsApp <?php echo esc_html( aquariende_info( 'whatsapp' ) ); ?></span>
					<span aria-hidden="true">·</span>
					<span>Email <?php echo aquariende_email_link(); // phpcs:ignore WordPress.Security.EscapeOutput ?></span>
				</p>
			</div>
		</div>
		<?php if ( $aq_steps ) : ?>
			<div class="cta-side">
				<?php get_template_part( 'template-parts/what-happens-next', null, array( 'dark' => true ) ); ?>
			</div>
		<?php endif; ?>
	</div>
</section>
