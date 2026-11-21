<?php
/**
 * Guide card: its question and its "last reviewed" date.
 *
 * Args: post (WP_Post), heading (h2|h3).
 *
 * @package Aquariende
 */

$aq_post = isset( $args['post'] ) ? $args['post'] : get_post();
$aq_tag  = ( isset( $args['heading'] ) && 'h2' === $args['heading'] ) ? 'h2' : 'h3';
if ( ! $aq_post ) {
	return;
}
$aq_q   = (string) get_post_meta( $aq_post->ID, '_aq_question', true );
$aq_rev = (string) get_post_meta( $aq_post->ID, '_aq_reviewed', true );
?>
<article class="guide-card">
	<<?php echo $aq_tag; // phpcs:ignore WordPress.Security.EscapeOutput -- h2 or h3. ?> class="guide-card-title"><a href="<?php echo esc_url( get_permalink( $aq_post ) ); ?>"><?php echo esc_html( get_the_title( $aq_post ) ); ?></a></<?php echo $aq_tag; // phpcs:ignore WordPress.Security.EscapeOutput ?>>
	<?php if ( $aq_q ) : ?>
		<p class="guide-card-q"><?php echo esc_html( $aq_q ); ?></p>
	<?php endif; ?>
	<?php if ( $aq_rev ) : ?>
		<p class="guide-card-date">Last reviewed <?php echo aquariende_time( $aq_rev ); // phpcs:ignore WordPress.Security.EscapeOutput ?></p>
	<?php endif; ?>
</article>
