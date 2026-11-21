<?php
/**
 * Fallback template: blog listing, archives and search results.
 *
 * @package Aquariende
 */

get_header();

if ( is_search() ) {
	/* translators: %s: search query. */
	$aq_title = sprintf( __( 'Search: %s', 'aquariende' ), get_search_query() );
	$aq_lede  = '';
} elseif ( is_archive() ) {
	$aq_title = get_the_archive_title();
	$aq_lede  = wp_strip_all_tags( get_the_archive_description() );
} else {
	$aq_page  = (int) get_option( 'page_for_posts' );
	$aq_title = $aq_page ? get_the_title( $aq_page ) : __( 'Blog', 'aquariende' );
	$aq_lede  = __( 'Guides to the German application and visa process, written by our team.', 'aquariende' );
}
?>
<main id="main" class="site-main">
	<?php
	get_template_part(
		'template-parts/page-head',
		null,
		array(
			'eyebrow' => is_search() ? '' : __( 'Blog', 'aquariende' ),
			'title'   => $aq_title,
			'lede'    => $aq_lede,
		)
	);
	?>
	<section class="section-pad">
		<div class="wrap">
			<?php if ( is_search() ) : ?>
				<div class="search-again"><?php get_search_form(); ?></div>
			<?php endif; ?>

			<?php if ( have_posts() ) : ?>
				<div class="post-grid">
					<?php
					while ( have_posts() ) {
						the_post();
						get_template_part( 'template-parts/post-card', null, array( 'heading' => 'h2' ) );
					}
					?>
				</div>
				<?php
				the_posts_pagination(
					array(
						'mid_size'  => 1,
						'prev_text' => __( 'Previous', 'aquariende' ),
						'next_text' => __( 'Next', 'aquariende' ),
					)
				);
				?>
			<?php else : ?>
				<div class="empty">
					<h2><?php esc_html_e( 'Nothing here yet.', 'aquariende' ); ?></h2>
					<p><?php esc_html_e( 'Try another search, or ask us directly on WhatsApp.', 'aquariende' ); ?></p>
					<?php if ( ! is_search() ) : ?>
						<?php get_search_form(); ?>
					<?php endif; ?>
				</div>
			<?php endif; ?>
		</div>
	</section>
</main>
<?php
get_footer();
