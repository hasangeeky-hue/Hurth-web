<?php
/**
 * FAQ: <details><summary> items, queried from aq_faq by the page's content key.
 * Each item is stored once and reused wherever its "Show on" list says.
 * FAQPage schema is printed only on the /faq/ page (owner decision).
 *
 * Args: key (content key), title, items (WP_Post[]; overrides key).
 *
 * @package Aquariende
 */

$aq_args  = isset( $args ) && is_array( $args ) ? $args : array();
$aq_items = isset( $aq_args['items'] ) ? $aq_args['items'] : aquariende_faqs_for( isset( $aq_args['key'] ) ? $aq_args['key'] : '' );
$aq_title = isset( $aq_args['title'] ) ? $aq_args['title'] : 'Questions students ask';
if ( ! $aq_items ) {
	return;
}
$aq_hid = wp_unique_id( 'faq-title-' );
?>
<section class="faq" aria-labelledby="<?php echo esc_attr( $aq_hid ); ?>">
	<h2 id="<?php echo esc_attr( $aq_hid ); ?>" class="block-title"><?php echo esc_html( $aq_title ); ?></h2>
	<div class="faq-list">
		<?php foreach ( $aq_items as $aq_item ) : ?>
			<details class="faq-item" id="<?php echo esc_attr( 'q-' . $aq_item->post_name ); ?>">
				<summary><?php echo esc_html( get_the_title( $aq_item ) ); ?></summary>
				<div class="faq-answer"><?php echo aquariende_faq_answer( $aq_item ); // phpcs:ignore WordPress.Security.EscapeOutput -- post content, filtered. ?></div>
			</details>
		<?php endforeach; ?>
	</div>
</section>
