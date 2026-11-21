<?php
/**
 * VolatileFact: a figure that changes is never written in the text. This card
 * names who sets it and links the official page (03 §2).
 *
 * Args: topic.
 *
 * @package Aquariende
 */

$aq_topic = isset( $args['topic'] ) ? $args['topic'] : '';
$aq_all   = aquariende_volatile();
if ( empty( $aq_all['volatile'][ $aq_topic ] ) ) {
	return;
}
$aq_v = $aq_all['volatile'][ $aq_topic ];
?>
<aside class="fact fact--volatile" role="note">
	<p class="fact-label"><?php echo aquariende_icon( 'info' ); // phpcs:ignore WordPress.Security.EscapeOutput ?><span>A figure that changes</span></p>
	<p class="fact-text"><strong><?php echo esc_html( $aq_v['what'] ); ?></strong> is set by <?php echo esc_html( $aq_v['authority'] ); ?> and changes. Check the current figure: <a href="<?php echo esc_url( $aq_v['url'] ); ?>" data-ext="<?php echo esc_attr( (string) wp_parse_url( $aq_v['url'], PHP_URL_HOST ) ); ?>"><?php echo esc_html( $aq_v['link'] ); ?><span class="screen-reader-text"> (external site)</span></a>.</p>
	<p class="fact-checked">Link checked <?php echo aquariende_time( $aq_v['checked'] ); // phpcs:ignore WordPress.Security.EscapeOutput ?>.</p>
</aside>
