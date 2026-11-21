<?php
/**
 * Single blog post (a dated update).
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
			array(
				'answer' => '',
				'meta'   => '<p>Published <time datetime="' . esc_attr( get_the_date( 'c' ) ) . '">' . esc_html( get_the_date( 'j F Y' ) ) . '</time></p>',
			)
		);
		?>
		<article id="post-<?php the_ID(); ?>" <?php post_class( 'section-pad' ); ?>>
			<div class="wrap">
				<div class="prose">
					<?php the_content(); ?>
				</div>
			</div>
		</article>
	<?php endwhile; ?>
	<?php get_template_part( 'template-parts/wa-cta' ); ?>
</main>
<?php
get_footer();
