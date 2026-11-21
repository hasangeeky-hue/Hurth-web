<?php
/**
 * WhatHappensNext: the five steps after a WhatsApp message.
 *
 * Args: heading (h2|h3), title, dark (bool).
 *
 * @package Aquariende
 */

$aq_args  = isset( $args ) && is_array( $args ) ? $args : array();
$aq_tag   = ( isset( $aq_args['heading'] ) && 'h2' === $aq_args['heading'] ) ? 'h2' : 'h3';
$aq_title = isset( $aq_args['title'] ) ? $aq_args['title'] : 'What happens after you message us';
$aq_dark  = ! empty( $aq_args['dark'] );
?>
<div class="next-steps">
	<<?php echo $aq_tag; // phpcs:ignore WordPress.Security.EscapeOutput -- h2 or h3. ?> class="block-title"><?php echo esc_html( $aq_title ); ?></<?php echo $aq_tag; // phpcs:ignore WordPress.Security.EscapeOutput ?>>
	<ol class="flow<?php echo $aq_dark ? '' : ' flow--light'; ?>">
		<?php foreach ( aquariende_flow() as $aq_step ) : ?>
			<li><strong><?php echo esc_html( $aq_step['title'] ); ?></strong><span><?php echo esc_html( $aq_step['text'] ); ?></span></li>
		<?php endforeach; ?>
	</ol>
</div>
