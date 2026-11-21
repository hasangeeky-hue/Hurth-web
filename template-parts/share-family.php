<?php
/**
 * "Share with your family": a WhatsApp share of the page title and URL, with
 * no tracking parameters and no personal data.
 *
 * @package Aquariende
 */

$aq_text = wp_strip_all_tags( single_post_title( '', false ) ) . ' ' . get_permalink();
?>
<p class="share">
	<a class="link-arrow" href="<?php echo esc_url( 'https://wa.me/?text=' . rawurlencode( $aq_text ) ); ?>" target="_blank" rel="noopener" data-share="1"><?php echo aquariende_icon( 'share' ); // phpcs:ignore WordPress.Security.EscapeOutput ?><span>Share this page with your family on WhatsApp</span><span class="screen-reader-text"> (opens WhatsApp)</span></a>
</p>
