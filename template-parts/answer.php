<?php
/**
 * Answer block: 40–60 words that stand alone (AI engines lift it whole).
 * A <p>, never a heading.
 *
 * Args: text.
 *
 * @package Aquariende
 */

$aq_text = isset( $args['text'] ) ? trim( (string) $args['text'] ) : '';
if ( '' === $aq_text ) {
	return;
}
?>
<p class="answer"><?php echo esc_html( $aq_text ); ?></p>
