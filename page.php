<?php
/**
 * Default page template: legal pages and any page without its own template.
 *
 * @package Aquariende
 */

get_header();
?>
<main id="main" class="site-main">
	<?php
	while ( have_posts() ) :
		the_post();
		get_template_part( 'template-parts/page-head' );
		?>
		<article id="post-<?php the_ID(); ?>" <?php post_class( 'section-pad' ); ?>>
			<div class="wrap">
				<div class="prose">
					<?php the_content(); ?>
				</div>
				<?php get_template_part( 'template-parts/source-list', null, array( 'post_id' => get_the_ID() ) ); ?>
				<?php get_template_part( 'template-parts/faq', null, array( 'key' => aquariende_key_of( get_the_ID() ) ) ); ?>
			</div>
		</article>
	<?php endwhile; ?>
	<?php get_template_part( 'template-parts/wa-cta' ); ?>
</main>
<?php
get_footer();
