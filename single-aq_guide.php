<?php
/**
 * One guide (06 §4): H1 → answer → review stamp → "you are here" on the
 * JourneyMap → table of contents (long guides) → text with VolatileFact and
 * DeadlineFact cards → who prepared it → where to go next → FAQ → sources →
 * review stamp → share → the one WhatsApp action.
 *
 * @package Aquariende
 */

get_header();
?>
<main id="main" class="site-main">
	<?php
	while ( have_posts() ) :
		the_post();
		$aq_id = get_the_ID();
		get_template_part(
			'template-parts/page-head',
			null,
			array( 'meta' => aquariende_part( 'review-stamp', array( 'post_id' => $aq_id ) ) )
		);
		?>
		<div class="section-pad section-pad--tight">
			<div class="wrap">
				<?php
				get_template_part(
					'template-parts/journey-map',
					null,
					array(
						'current' => aquariende_guide_stage( $aq_id ),
						'compact' => true,
					)
				);
				?>
			</div>
		</div>
		<article id="post-<?php echo esc_attr( (string) $aq_id ); ?>" <?php post_class( 'section-pad section-pad--top0' ); ?>>
			<div class="wrap with-toc">
				<?php get_template_part( 'template-parts/toc', null, array( 'post' => get_post() ) ); ?>
				<div class="prose">
					<?php the_content(); ?>
					<?php get_template_part( 'template-parts/author-box', null, array( 'post_id' => $aq_id ) ); ?>
					<?php get_template_part( 'template-parts/related', null, array( 'post_id' => $aq_id ) ); ?>
					<?php get_template_part( 'template-parts/faq', null, array( 'key' => aquariende_key_of( $aq_id ) ) ); ?>
					<?php get_template_part( 'template-parts/source-list', null, array( 'post_id' => $aq_id ) ); ?>
					<?php get_template_part( 'template-parts/review-stamp', null, array( 'post_id' => $aq_id ) ); ?>
					<?php get_template_part( 'template-parts/share-family' ); ?>
				</div>
			</div>
		</article>
	<?php endwhile; ?>
	<?php get_template_part( 'template-parts/wa-cta' ); ?>
</main>
<?php
get_footer();
