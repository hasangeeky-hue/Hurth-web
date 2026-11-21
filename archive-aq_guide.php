<?php
/**
 * /guides/ (04 #5): guides grouped by JourneyMap stage. Each card shows its
 * question and its "last reviewed" date.
 *
 * @package Aquariende
 */

get_header();
?>
<main id="main" class="site-main">
	<?php
	get_template_part(
		'template-parts/page-head',
		null,
		array(
			'title'  => 'Guides for Bangladeshi students going to Germany',
			'answer' => 'These guides explain each stage of studying in Germany from Bangladesh: eligibility, applications, money, the visa and arrival. Every rule links to the official page it comes from, such as the German Embassy Dhaka, uni-assist or DAAD, and each guide shows the date it was last checked.',
		)
	);
	?>
	<div class="section-pad section-pad--tight">
		<div class="wrap">
			<?php get_template_part( 'template-parts/journey-map', null, array( 'compact' => true ) ); ?>
		</div>
	</div>
	<div class="section-pad section-pad--top0">
		<div class="wrap">
			<?php
			$aq_any = false;
			foreach ( aquariende_journey() as $aq_slug => $aq_stage ) :
				$aq_guides = aquariende_stage_guides( $aq_slug );
				if ( ! $aq_guides ) {
					continue;
				}
				$aq_any = true;
				?>
				<section class="guide-group" id="<?php echo esc_attr( $aq_slug ); ?>" aria-labelledby="<?php echo esc_attr( 'g-' . $aq_slug ); ?>">
					<h2 id="<?php echo esc_attr( 'g-' . $aq_slug ); ?>" class="section-title"><?php echo esc_html( $aq_stage['label'] ); ?></h2>
					<p class="section-lede"><?php echo esc_html( $aq_stage['summary'] ); ?></p>
					<div class="guide-grid">
						<?php
						foreach ( $aq_guides as $aq_g ) {
							get_template_part( 'template-parts/guide-card', null, array( 'post' => $aq_g ) );
						}
						?>
					</div>
				</section>
			<?php endforeach; ?>
			<?php if ( ! $aq_any ) : ?>
				<p>No guides are published yet.</p>
			<?php endif; ?>
			<p class="section-more"><a class="link-arrow" href="<?php echo esc_url( aquariende_page_url( 'glossary' ) ); ?>">Glossary: German study and visa terms<?php echo aquariende_icon( 'arrow' ); // phpcs:ignore WordPress.Security.EscapeOutput ?></a></p>
		</div>
	</div>
	<?php get_template_part( 'template-parts/wa-cta' ); ?>
</main>
<?php
get_footer();
