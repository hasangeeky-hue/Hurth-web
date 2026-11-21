<?php
/**
 * Template Name: How it works
 *
 * /how-it-works/ (04 #4): the five steps (mirrored exactly in HowTo schema),
 * then who owns your case, how you see progress, how to complain.
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
		<section class="section-pad" aria-labelledby="steps-title">
			<div class="wrap narrow">
				<h2 id="steps-title" class="section-title">The five steps</h2>
				<ol class="steps">
					<?php foreach ( aquariende_flow() as $aq_i => $aq_step ) : ?>
						<li class="step" id="step-<?php echo esc_attr( (string) ( $aq_i + 1 ) ); ?>">
							<h3 class="step-title"><?php echo esc_html( $aq_step['title'] ); ?></h3>
							<p><?php echo esc_html( $aq_step['text'] ); ?></p>
						</li>
					<?php endforeach; ?>
				</ol>
				<?php get_template_part( 'template-parts/never-promised' ); ?>
			</div>
		</section>
		<article id="post-<?php the_ID(); ?>" <?php post_class( 'section-pad tint' ); ?>>
			<div class="wrap narrow">
				<div class="prose"><?php the_content(); ?></div>
			</div>
		</article>
		<section class="section-pad">
			<div class="wrap narrow">
				<?php get_template_part( 'template-parts/faq', null, array( 'key' => aquariende_key_of( get_the_ID() ) ) ); ?>
			</div>
		</section>
	<?php endwhile; ?>
	<?php get_template_part( 'template-parts/wa-cta', null, array( 'title' => 'Ready for step one?', 'steps' => false ) ); ?>
</main>
<?php
get_footer();
