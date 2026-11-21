<?php
/**
 * Hub and spoke links for a guide (03 §6): up to the pillar, across to the
 * next guide, out to one service with a context sentence, plus related guides.
 *
 * Args: post_id.
 *
 * @package Aquariende
 */

$aq_pid     = isset( $args['post_id'] ) ? (int) $args['post_id'] : get_the_ID();
$aq_next    = aquariende_next_guide( $aq_pid );
$aq_related = aquariende_related_guides( $aq_pid );
$aq_service = aquariende_stage( (string) get_post_meta( $aq_pid, '_aq_service', true ) );
?>
<nav class="related" aria-label="Where to go next">
	<ul class="related-list">
		<li class="related-item">
			<p class="list-label">Part of</p>
			<a href="<?php echo esc_url( aquariende_page_url( 'study-in-germany-from-bangladesh' ) ); ?>">Study in Germany from Bangladesh: the steps, in order</a>
		</li>
		<?php if ( $aq_next ) : ?>
			<li class="related-item">
				<p class="list-label">Next in the journey</p>
				<a href="<?php echo esc_url( get_permalink( $aq_next ) ); ?>"><?php echo esc_html( get_the_title( $aq_next ) ); ?></a>
			</li>
		<?php endif; ?>
		<?php if ( $aq_service ) : ?>
			<li class="related-item">
				<p class="list-label">Where we help</p>
				<p class="related-text"><?php echo esc_html( $aq_service['summary'] ); ?> <a href="<?php echo esc_url( aquariende_page_url( $aq_service['page'] ) ); ?>">About the <?php echo esc_html( strtolower( $aq_service['short'] ) ); ?> service</a></p>
			</li>
		<?php endif; ?>
	</ul>
	<?php if ( $aq_related ) : ?>
		<p class="list-label">Related guides</p>
		<ul class="guide-links">
			<?php foreach ( $aq_related as $aq_r ) : ?>
				<li><a href="<?php echo esc_url( get_permalink( $aq_r ) ); ?>"><?php echo esc_html( get_the_title( $aq_r ) ); ?></a></li>
			<?php endforeach; ?>
		</ul>
	<?php endif; ?>
</nav>
