<?php
/**
 * DeadlineFact: a timing rule, never a countdown. Labelled "Timing rule".
 *
 * Args: topic.
 *
 * @package Aquariende
 */

$aq_topic = isset( $args['topic'] ) ? $args['topic'] : '';
$aq_all   = aquariende_volatile();
if ( empty( $aq_all['deadline'][ $aq_topic ] ) ) {
	return;
}
$aq_d = $aq_all['deadline'][ $aq_topic ];
?>
<aside class="fact fact--deadline" role="note">
	<p class="fact-label"><?php echo aquariende_icon( 'clock' ); // phpcs:ignore WordPress.Security.EscapeOutput ?><span>Timing rule</span></p>
	<p class="fact-text"><?php echo esc_html( $aq_d['rule'] ); ?></p>
	<p class="fact-checked">Source: <a href="<?php echo esc_url( $aq_d['url'] ); ?>" data-ext="<?php echo esc_attr( (string) wp_parse_url( $aq_d['url'], PHP_URL_HOST ) ); ?>"><?php echo esc_html( $aq_d['publisher'] ); ?><span class="screen-reader-text"> (external site)</span></a>, checked <?php echo aquariende_time( $aq_d['checked'] ); // phpcs:ignore WordPress.Security.EscapeOutput ?>.</p>
</aside>
