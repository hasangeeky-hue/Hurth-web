<?php
/**
 * Template Name: Pillar guide
 *
 * /study-in-germany-from-bangladesh/ (04 #2): answer → JourneyMap → one H2
 * per stage with its guides (the [aq_stage_guides] shortcode in the content)
 * → what you can do yourself → where we help → FAQ → sources.
 *
 * @package Aquariende
 */

get_header();
?>
<main id="main" class="site-main">
	<?php
	while ( have_posts() ) :
		the_post();
		get_template_part(
			'template-parts/page-head',
			null,
			array( 'meta' => aquariende_part( 'review-stamp', array( 'post_id' => get_the_ID() ) ) )
		);
		?>
		<div class="section-pad section-pad--tight">
			<div class="wrap">
				<?php get_template_part( 'template-parts/journey-map', null, array( 'title' => 'The six stages' ) ); ?>
			</div>
		</div>
		<article id="post-<?php the_ID(); ?>" <?php post_class( 'section-pad section-pad--top0' ); ?>>
			<div class="wrap with-toc">
				<?php get_template_part( 'template-parts/toc', null, array( 'post' => get_post() ) ); ?>
				<div class="prose">
					<?php the_content(); ?>
					<?php get_template_part( 'template-parts/faq', null, array( 'key' => aquariende_key_of( get_the_ID() ) ) ); ?>
					<?php get_template_part( 'template-parts/source-list', null, array( 'post_id' => get_the_ID() ) ); ?>
					<?php get_template_part( 'template-parts/review-stamp', null, array( 'post_id' => get_the_ID() ) ); ?>
					<?php get_template_part( 'template-parts/share-family' ); ?>
				</div>
			</div>
		</article>
	<?php endwhile; ?>
	<?php get_template_part( 'template-parts/wa-cta', null, array( 'title' => 'Want us to check your profile?' ) ); ?>
</main>
<?php
get_footer();
