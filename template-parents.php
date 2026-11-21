<?php
/**
 * Template Name: For parents
 *
 * /for-parents/ (04 #7): the steps in plain words, what we never promise,
 * costs (linked), and "share with your family".
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
			<div class="wrap narrow">
				<div class="prose"><?php the_content(); ?></div>
				<?php get_template_part( 'template-parts/share-family' ); ?>
			</div>
		</article>
		<section class="section-pad tint">
			<div class="wrap split">
				<?php get_template_part( 'template-parts/what-happens-next', null, array( 'heading' => 'h2' ) ); ?>
				<div>
					<?php get_template_part( 'template-parts/never-promised' ); ?>
				</div>
			</div>
		</section>
		<section class="section-pad">
			<div class="wrap narrow">
				<?php get_template_part( 'template-parts/faq', null, array( 'key' => aquariende_key_of( get_the_ID() ) ) ); ?>
				<?php get_template_part( 'template-parts/source-list', null, array( 'post_id' => get_the_ID() ) ); ?>
			</div>
		</section>
	<?php endwhile; ?>
	<?php get_template_part( 'template-parts/wa-cta', null, array( 'title' => 'Questions as a parent?', 'steps' => false ) ); ?>
</main>
<?php
get_footer();
