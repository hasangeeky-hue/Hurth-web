<?php
/**
 * The right-hand column of reading pages: the WhatsApp action and, optionally, the "we never" list.
 * It fills the space beside a reading column and stays in view while the reader scrolls.
 *
 * Args: never (bool, default true), title, text.
 *
 * @package Aquariende
 */

$aq_args  = isset( $args ) && is_array( $args ) ? $args : array();
$aq_never = ! isset( $aq_args['never'] ) || $aq_args['never'];
$aq_title = isset( $aq_args['title'] ) ? $aq_args['title'] : 'Questions about your own case?';
$aq_text  = isset( $aq_args['text'] ) ? $aq_args['text'] : 'Message us on WhatsApp. A team member calls you from Germany within 24 hours. Nothing is paid on this website.';
?>
<aside class="rt-aside" aria-label="<?php echo esc_attr( $aq_title ); ?>">
	<div class="rt-help dark">
		<p class="rt-help-title"><?php echo esc_html( $aq_title ); ?></p>
		<p class="rt-help-text"><?php echo esc_html( $aq_text ); ?></p>
		<?php echo aquariende_wa_button( 'aside', array( 'class' => 'btn btn-primary btn-block' ) ); // phpcs:ignore WordPress.Security.EscapeOutput -- escaped inside. ?>
	</div>
	<?php
	if ( $aq_never ) {
		get_template_part( 'template-parts/never-promised' );
	}
	?>
</aside>
