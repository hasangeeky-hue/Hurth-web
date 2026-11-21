<?php
/**
 * SourceList: the official pages a page relies on, with the access date.
 *
 * Args: post_id.
 *
 * @package Aquariende
 */

$aq_pid     = isset( $args['post_id'] ) ? (int) $args['post_id'] : get_the_ID();
$aq_sources = aquariende_post_sources( $aq_pid );
if ( ! $aq_sources ) {
	return;
}
?>
<section class="sources" aria-labelledby="sources-title">
	<h2 id="sources-title" class="block-title">Official sources</h2>
	<ol class="sources-list">
		<?php foreach ( $aq_sources as $aq_s ) : ?>
			<li>
				<span class="sources-pub"><?php echo esc_html( $aq_s['publisher'] ); ?>:</span>
				<cite><a href="<?php echo esc_url( $aq_s['url'] ); ?>" data-ext="<?php echo esc_attr( (string) wp_parse_url( $aq_s['url'], PHP_URL_HOST ) ); ?>"><?php echo esc_html( $aq_s['title'] ); ?><span class="screen-reader-text"> (external site)</span></a></cite>
				<?php if ( ! empty( $aq_s['accessed'] ) ) : ?>
					<span class="sources-date">Accessed <?php echo aquariende_time( $aq_s['accessed'] ); // phpcs:ignore WordPress.Security.EscapeOutput ?></span>
				<?php endif; ?>
			</li>
		<?php endforeach; ?>
	</ol>
</section>
