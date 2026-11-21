<?php
/**
 * Dark page header used by every page except the front page.
 *
 * Args: title, lede, meta (all optional; title defaults to the page title).
 * An 'eyebrow' arg is accepted for back-compat but no longer rendered: the heading carries the page.
 *
 * @package Aquariende
 */

$aq_args    = isset( $args ) && is_array( $args ) ? $args : array();
$aq_eyebrow = isset( $aq_args['eyebrow'] ) ? $aq_args['eyebrow'] : '';
$aq_title   = isset( $aq_args['title'] ) ? $aq_args['title'] : get_the_title();
$aq_lede    = isset( $aq_args['lede'] ) ? $aq_args['lede'] : '';
$aq_meta    = isset( $aq_args['meta'] ) ? $aq_args['meta'] : '';
?>
<header class="page-head dark">
	<div class="wrap page-head-inner">
		<h1 class="page-title"><?php echo esc_html( wp_strip_all_tags( $aq_title ) ); ?></h1>
		<?php if ( $aq_lede ) : ?>
			<p class="page-lede"><?php echo esc_html( $aq_lede ); ?></p>
		<?php endif; ?>
		<?php if ( $aq_meta ) : ?>
			<p class="page-meta"><?php echo wp_kses_post( $aq_meta ); ?></p>
		<?php endif; ?>
	</div>
</header>
