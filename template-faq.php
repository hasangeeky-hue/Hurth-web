<?php
/**
 * Template Name: FAQ
 *
 * /faq/ (04 #9): every aq_faq item, grouped by aq_faq_group in term order.
 * The only page that prints FAQPage schema.
 *
 * @package Aquariende
 */

get_header();

$aq_groups = get_terms(
	array(
		'taxonomy'   => 'aq_faq_group',
		'hide_empty' => true,
	)
);
$aq_groups = is_wp_error( $aq_groups ) ? array() : $aq_groups;
usort(
	$aq_groups,
	function ( $a, $b ) {
		return (int) get_term_meta( $a->term_id, 'order', true ) - (int) get_term_meta( $b->term_id, 'order', true );
	}
);
?>
<main id="main" class="site-main">
	<?php
	while ( have_posts() ) :
		the_post();
		get_template_part( 'template-parts/page-head' );
		?>
		<section class="section-pad">
			<div class="wrap rt-two rt-two--aside">
				<div class="rt-main">
				<?php if ( $aq_groups ) : ?>
					<nav class="az" aria-label="Question groups">
						<ul>
							<?php foreach ( $aq_groups as $aq_g ) : ?>
								<li><a href="#<?php echo esc_attr( $aq_g->slug ); ?>"><?php echo esc_html( $aq_g->name ); ?></a></li>
							<?php endforeach; ?>
						</ul>
					</nav>
				<?php endif; ?>
				<?php if ( '' !== trim( get_the_content() ) ) : ?>
					<div class="prose"><?php the_content(); ?></div>
				<?php endif; ?>
				<?php
				foreach ( $aq_groups as $aq_g ) :
					$aq_items = get_posts(
						array(
							'post_type'   => 'aq_faq',
							'post_status' => 'publish',
							'numberposts' => 200,
							'orderby'     => 'menu_order title',
							'order'       => 'ASC',
							'tax_query'   => array( // phpcs:ignore WordPress.DB.SlowDBQuery
								array(
									'taxonomy' => 'aq_faq_group',
									'field'    => 'term_id',
									'terms'    => $aq_g->term_id,
								),
							),
						)
					);
					?>
					<div class="faq-group" id="<?php echo esc_attr( $aq_g->slug ); ?>">
						<?php
						get_template_part(
							'template-parts/faq',
							null,
							array(
								'items' => $aq_items,
								'title' => $aq_g->name,
							)
						);
						?>
					</div>
				<?php endforeach; ?>
				</div>
				<?php get_template_part( 'template-parts/aside-help', null, array( 'title' => 'Your question is not here?' ) ); ?>
			</div>
		</section>
	<?php endwhile; ?>
	<?php get_template_part( 'template-parts/wa-cta', null, array( 'title' => 'Your question is not here?' ) ); ?>
</main>
<?php
get_footer();
