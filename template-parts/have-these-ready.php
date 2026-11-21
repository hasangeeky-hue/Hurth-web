<?php
/**
 * HaveTheseReady: what we ask on the first call [SALES] and the documents for
 * the profile check [PIPE A1]. Print friendly; easy to screenshot.
 *
 * Args: heading (h2|h3).
 *
 * @package Aquariende
 */

$aq_args  = isset( $args ) && is_array( $args ) ? $args : array();
$aq_tag   = ( isset( $aq_args['heading'] ) && 'h2' === $aq_args['heading'] ) ? 'h2' : 'h3';
$aq_ready = aquariende_have_ready();
?>
<div class="ready">
	<<?php echo $aq_tag; // phpcs:ignore WordPress.Security.EscapeOutput -- h2 or h3. ?> class="block-title">Have these ready</<?php echo $aq_tag; // phpcs:ignore WordPress.Security.EscapeOutput ?>>
	<div class="ready-grid">
		<div>
			<p class="list-label">We will ask you</p>
			<ul class="checklist">
				<?php foreach ( $aq_ready['questions'] as $aq_q ) : ?>
					<li><?php echo aquariende_icon( 'check' ); // phpcs:ignore WordPress.Security.EscapeOutput ?><span><?php echo esc_html( $aq_q ); ?></span></li>
				<?php endforeach; ?>
			</ul>
		</div>
		<div>
			<p class="list-label">Documents for the profile check</p>
			<ul class="checklist">
				<?php foreach ( $aq_ready['documents'] as $aq_d ) : ?>
					<li><?php echo aquariende_icon( 'check' ); // phpcs:ignore WordPress.Security.EscapeOutput ?><span><?php echo esc_html( $aq_d ); ?></span></li>
				<?php endforeach; ?>
			</ul>
		</div>
	</div>
</div>
