<?php
/**
 * Fallback template: the blog ("Updates": dated change notes), archives and
 * search results (search results are noindex).
 *
 * @package Aquariende
 */

get_header();

if ( is_search() ) {
	$aq_title = sprintf( 'Search: %s', get_search_query() );
	$aq_lede  = '';
} elseif ( is_archive() ) {
	$aq_title = wp_strip_all_tags( get_the_archive_title() );
	$aq_lede  = wp_strip_all_tags( get_the_archive_description() );
} else {
	$aq_page  = (int) get_option( 'page_for_posts' );
	$aq_title = $aq_page ? get_the_title( $aq_page ) : 'Updates';
	$aq_lede  = $aq_page ? (string) get_post_field( 'post_excerpt', $aq_page ) : '';
}
?>
<main id="main" class="site-main">
	<?php
	get_template_part(
		'template-parts/page-head',
		null,
		array(
			'title'  => $aq_title,
			'answer' => '',
			'lede'   => $aq_lede,
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
						if ( 'aq_guide' === get_post_type() ) {
							get_template_part( 'template-parts/guide-card', null, array( 'post' => get_post(), 'heading' => 'h2' ) );
						} else {
							get_template_part( 'template-parts/post-card', null, array( 'heading' => 'h2' ) );
						}
					}
					?>
				</div>
				<?php
				the_posts_pagination(
					array(
						'mid_size'  => 1,
						'prev_text' => 'Previous',
						'next_text' => 'Next',
					)
				);
				?>
			<?php else : ?>
				<div class="empty">
					<h2><?php echo is_search() ? 'Nothing found.' : 'No updates yet.'; ?></h2>
					<p>The guides explain each stage, with official sources.</p>
					<p><a class="link-arrow" href="<?php echo esc_url( aquariende_page_url( 'guides' ) ); ?>">All guides<?php echo aquariende_icon( 'arrow' ); // phpcs:ignore WordPress.Security.EscapeOutput ?></a></p>
				</div>
			<?php endif; ?>
		</div>
	</section>
	<?php get_template_part( 'template-parts/wa-cta', null, array( 'steps' => false ) ); ?>
</main>
<?php
get_footer();
