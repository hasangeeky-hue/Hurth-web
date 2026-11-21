<?php
/**
 * WhatsApp call-to-action with the four-step flow and the optional call-back form.
 *
 * Args: title (string), form (bool, default true).
 *
 * @package Aquariende
 */

$aq_args  = isset( $args ) && is_array( $args ) ? $args : array();
$aq_title = isset( $aq_args['title'] ) ? $aq_args['title'] : __( "We're open for any suggestion or just to have a chat.", 'aquariende' );
$aq_form  = ! isset( $aq_args['form'] ) || $aq_args['form'];
?>
<section class="cta dark" id="start" aria-labelledby="cta-title">
	<div class="wrap cta-grid<?php echo $aq_form ? '' : ' cta-grid--solo'; ?>">
		<div class="cta-main">
			<h2 id="cta-title" class="section-title"><?php echo esc_html( $aq_title ); ?></h2>
			<ol class="flow">
				<?php foreach ( aquariende_flow() as $aq_step ) : ?>
					<li><strong><?php echo esc_html( $aq_step['title'] ); ?></strong><span><?php echo esc_html( $aq_step['text'] ); ?></span></li>
				<?php endforeach; ?>
			</ol>
			<div class="cta-actions">
				<a class="btn btn-primary btn-lg" href="<?php echo esc_url( aquariende_whatsapp_url() ); ?>" target="_blank" rel="noopener">
					<?php echo aquariende_icon( 'whatsapp' ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
					<span><?php esc_html_e( 'Message us on WhatsApp', 'aquariende' ); ?></span>
				</a>
				<p class="cta-alt">
					<span><?php echo esc_html( aquariende_info( 'whatsapp' ) ); ?></span>
					<span aria-hidden="true">·</span>
					<a href="mailto:<?php echo esc_attr( aquariende_info( 'email' ) ); ?>"><?php echo esc_html( aquariende_info( 'email' ) ); ?></a>
				</p>
			</div>
		</div>
		<?php if ( $aq_form ) : ?>
			<div class="cta-form">
				<?php get_template_part( 'template-parts/callback-form' ); ?>
			</div>
		<?php endif; ?>
	</div>
</section>
