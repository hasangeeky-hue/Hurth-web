<?php
/**
 * Not found: the JourneyMap and the guides list (06 §2).
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
			'title'  => 'This page is not in the file',
			'answer' => '',
			'lede'   => 'The link may be old: the site was rebuilt and some pages were removed. Start from your stage below, or search.',
		)
	);
	?>
	<section class="section-pad">
		<div class="wrap">
			<?php get_template_part( 'template-parts/journey-map' ); ?>
		</div>
	</section>
	<section class="section-pad section-pad--top0">
		<div class="wrap narrow">
			<?php get_search_form(); ?>
			<h2 class="block-title">Guides</h2>
			<ul class="guide-links">
				<?php foreach ( aquariende_guides_in_order() as $aq_g ) : ?>
					<li><a href="<?php echo esc_url( get_permalink( $aq_g ) ); ?>"><?php echo esc_html( get_the_title( $aq_g ) ); ?></a></li>
				<?php endforeach; ?>
			</ul>
		</div>
	</section>
</main>
<?php
get_footer();
