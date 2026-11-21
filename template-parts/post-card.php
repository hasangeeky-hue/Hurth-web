<?php
/**
 * Post card for listings. Use inside The Loop.
 *
 * Args: heading (h2|h3, default h3).
 *
 * @package Aquariende
 */

$aq_args = isset( $args ) && is_array( $args ) ? $args : array();
$aq_tag  = ( isset( $aq_args['heading'] ) && 'h2' === $aq_args['heading'] ) ? 'h2' : 'h3';
$aq_cats = get_the_category();
?>
<article <?php post_class( 'post-card' ); ?>>
	<?php if ( has_post_thumbnail() ) : ?>
		<div class="post-card-media"><?php the_post_thumbnail( 'medium_large', array( 'loading' => 'lazy' ) ); ?></div>
	<?php endif; ?>
	<div class="post-card-body">
		<p class="post-meta">
			<?php if ( $aq_cats && 'uncategorized' !== $aq_cats[0]->slug ) : ?>
				<span class="post-cat"><?php echo esc_html( $aq_cats[0]->name ); ?></span>
			<?php endif; ?>
			<time datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>"><?php echo esc_html( get_the_date() ); ?></time>
		</p>
		<<?php echo $aq_tag; // phpcs:ignore WordPress.Security.EscapeOutput -- h2 or h3. ?> class="post-card-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></<?php echo $aq_tag; // phpcs:ignore WordPress.Security.EscapeOutput ?>>
		<p class="post-card-excerpt"><?php echo esc_html( wp_strip_all_tags( get_the_excerpt() ) ); ?></p>
	</div>
</article>
