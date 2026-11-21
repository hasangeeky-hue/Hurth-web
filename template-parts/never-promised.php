<?php
/**
 * Never-promised stamp: a list with a cross icon and words (never colour only).
 *
 * Args: items (string[]; defaults to aquariende_never()), title.
 *
 * @package Aquariende
 */

$aq_args  = isset( $args ) && is_array( $args ) ? $args : array();
$aq_items = ! empty( $aq_args['items'] ) ? $aq_args['items'] : aquariende_never();
$aq_title = isset( $aq_args['title'] ) ? $aq_args['title'] : 'We never';
?>
<div class="stamp" role="note">
	<p class="stamp-title"><?php echo esc_html( $aq_title ); ?></p>
	<ul class="stamp-list">
		<?php foreach ( $aq_items as $aq_item ) : ?>
			<li><?php echo aquariende_icon( 'cross' ); // phpcs:ignore WordPress.Security.EscapeOutput ?><span><?php echo esc_html( $aq_item ); ?></span></li>
		<?php endforeach; ?>
	</ul>
</div>
