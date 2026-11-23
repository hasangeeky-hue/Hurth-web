<?php
/**
 * Dark page header: breadcrumbs, the one H1, and the answer block directly
 * under it (03 §2). Used by every page except Home.
 *
 * Args: title, answer, lede, meta (HTML, escaped by the caller).
 *
 * @package Aquariende
 */

$aq_args   = isset( $args ) && is_array( $args ) ? $args : array();
$aq_title  = isset( $aq_args['title'] ) ? $aq_args['title'] : get_the_title();
$aq_answer = isset( $aq_args['answer'] ) ? $aq_args['answer'] : ( is_singular() ? aquariende_page_meta( get_queried_object_id(), '_aq_answer' ) : '' );
$aq_lede   = isset( $aq_args['lede'] ) ? $aq_args['lede'] : '';
$aq_meta   = isset( $aq_args['meta'] ) ? $aq_args['meta'] : '';
$aq_art    = aquariende_art( isset( $aq_args['art'] ) ? $aq_args['art'] : aquariende_art_for_page() );
?>
<header class="page-head dark">
	<div class="wrap page-head-inner<?php echo $aq_art ? ' page-head--art' : ''; ?>">
		<div class="page-head-copy">
		<?php get_template_part( 'template-parts/breadcrumbs' ); ?>
		<h1 class="page-title"><?php echo esc_html( wp_strip_all_tags( $aq_title ) ); ?></h1>
		<?php if ( $aq_answer ) : ?>
			<?php get_template_part( 'template-parts/answer', null, array( 'text' => $aq_answer ) ); ?>
		<?php elseif ( $aq_lede ) : ?>
			<p class="page-lede"><?php echo esc_html( $aq_lede ); ?></p>
		<?php endif; ?>
		<?php if ( $aq_meta ) : ?>
			<div class="page-meta"><?php echo wp_kses_post( $aq_meta ); ?></div>
		<?php endif; ?>
		</div>
		<?php if ( $aq_art ) : ?>
			<div class="page-head-art"><?php echo $aq_art; // phpcs:ignore WordPress.Security.EscapeOutput -- fixed SVG. ?></div>
		<?php endif; ?>
	</div>
</header>
