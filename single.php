<?php
/**
 * Single blog post.
 *
 * @package Aquariende
 */

get_header();
?>
<main id="main" class="site-main">
	<?php
	while ( have_posts() ) :
		the_post();
		$aq_cats = get_the_category();
		$aq_meta = '<time datetime="' . esc_attr( get_the_date( 'c' ) ) . '">' . esc_html( get_the_date() ) . '</time>';
		if ( $aq_cats && 'uncategorized' !== $aq_cats[0]->slug ) {
			$aq_meta = '<a href="' . esc_url( get_category_link( $aq_cats[0] ) ) . '">' . esc_html( $aq_cats[0]->name ) . '</a> · ' . $aq_meta;
		}
		get_template_part(
			'template-parts/page-head',
			null,
			array(
				'eyebrow' => __( 'Blog', 'aquariende' ),
				'meta'    => $aq_meta,
			)
		);
		?>
		<article id="post-<?php the_ID(); ?>" <?php post_class( 'section-pad' ); ?>>
			<div class="wrap">
				<?php if ( has_post_thumbnail() ) : ?>
					<div class="post-hero-media"><?php the_post_thumbnail( 'large', array( 'loading' => 'eager' ) ); ?></div>
				<?php endif; ?>
				<div class="prose">
					<?php the_content(); ?>
					<?php wp_link_pages(); ?>
				</div>

				<aside class="post-aside" aria-label="<?php esc_attr_e( 'Next step', 'aquariende' ); ?>">
					<p class="post-aside-title"><?php esc_html_e( 'Questions about your own case?', 'aquariende' ); ?></p>
					<p><?php esc_html_e( 'Message us on WhatsApp. We reply in English or Bengali.', 'aquariende' ); ?></p>
					<a class="btn btn-primary" href="<?php echo esc_url( aquariende_whatsapp_url() ); ?>" target="_blank" rel="noopener">
						<?php echo aquariende_icon( 'whatsapp' ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
						<span><?php esc_html_e( 'Message us on WhatsApp', 'aquariende' ); ?></span>
					</a>
				</aside>

				<nav class="post-nav" aria-label="<?php esc_attr_e( 'More posts', 'aquariende' ); ?>">
					<?php
					previous_post_link( '<div class="post-nav-prev">%link</div>', '<span class="post-nav-label">' . esc_html__( 'Previous', 'aquariende' ) . '</span> %title' );
					next_post_link( '<div class="post-nav-next">%link</div>', '<span class="post-nav-label">' . esc_html__( 'Next', 'aquariende' ) . '</span> %title' );
					?>
				</nav>
			</div>
		</article>
		<?php
	endwhile;
	?>
</main>
<?php
get_footer();
