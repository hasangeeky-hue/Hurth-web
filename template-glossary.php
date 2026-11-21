<?php
/**
 * Template Name: Glossary
 *
 * /glossary/ (04 #8): every aq_term A–Z, anchored at #<slug>. Each term:
 * <dfn>, one sentence of definition, one on why it matters, an official link.
 *
 * @package Aquariende
 */

get_header();

$aq_terms = get_posts(
	array(
		'post_type'   => 'aq_term',
		'post_status' => 'publish',
		'numberposts' => 200,
		'orderby'     => 'title',
		'order'       => 'ASC',
	)
);
usort(
	$aq_terms,
	function ( $a, $b ) {
		return strcasecmp( $a->post_title, $b->post_title );
	}
);
$aq_letters = array();
foreach ( $aq_terms as $aq_t ) {
	$aq_letters[ strtoupper( substr( $aq_t->post_title, 0, 1 ) ) ][] = $aq_t;
}
?>
<main id="main" class="site-main">
	<?php
	while ( have_posts() ) :
		the_post();
		get_template_part( 'template-parts/page-head' );
		?>
		<section class="section-pad">
			<div class="wrap narrow">
				<?php if ( '' !== trim( get_the_content() ) ) : ?>
					<div class="prose"><?php the_content(); ?></div>
				<?php endif; ?>
				<?php if ( $aq_letters ) : ?>
					<nav class="az" aria-label="Glossary letters">
						<ul>
							<?php foreach ( array_keys( $aq_letters ) as $aq_l ) : ?>
								<li><a href="#letter-<?php echo esc_attr( strtolower( $aq_l ) ); ?>"><?php echo esc_html( $aq_l ); ?></a></li>
							<?php endforeach; ?>
						</ul>
					</nav>
					<?php foreach ( $aq_letters as $aq_l => $aq_list ) : ?>
						<section class="az-group" aria-labelledby="letter-<?php echo esc_attr( strtolower( $aq_l ) ); ?>">
							<h2 id="letter-<?php echo esc_attr( strtolower( $aq_l ) ); ?>" class="az-letter"><?php echo esc_html( $aq_l ); ?></h2>
							<dl class="terms">
								<?php
								foreach ( $aq_list as $aq_t ) :
									$aq_de  = (string) get_post_meta( $aq_t->ID, '_aq_term_de', true );
									$aq_def = (string) get_post_meta( $aq_t->ID, '_aq_def', true );
									$aq_why = (string) get_post_meta( $aq_t->ID, '_aq_why', true );
									$aq_src = (string) get_post_meta( $aq_t->ID, '_aq_source_url', true );
									$aq_sl  = (string) get_post_meta( $aq_t->ID, '_aq_source_label', true );
									?>
									<div class="term-item" id="<?php echo esc_attr( $aq_t->post_name ); ?>">
										<dt><dfn><?php echo esc_html( $aq_t->post_title ); ?></dfn><?php echo $aq_de ? ' <span class="term-de" lang="de">(' . esc_html( $aq_de ) . ')</span>' : ''; ?></dt>
										<dd>
											<p><?php echo esc_html( $aq_def ); ?></p>
											<?php if ( $aq_why ) : ?>
												<p class="term-why"><strong>Why it matters:</strong> <?php echo esc_html( $aq_why ); ?></p>
											<?php endif; ?>
											<?php if ( $aq_src ) : ?>
												<p class="term-src">Official source: <a href="<?php echo esc_url( $aq_src ); ?>" data-ext="<?php echo esc_attr( (string) wp_parse_url( $aq_src, PHP_URL_HOST ) ); ?>"><?php echo esc_html( $aq_sl ? $aq_sl : $aq_src ); ?><span class="screen-reader-text"> (external site)</span></a></p>
											<?php endif; ?>
										</dd>
									</div>
								<?php endforeach; ?>
							</dl>
						</section>
					<?php endforeach; ?>
				<?php endif; ?>
				<?php get_template_part( 'template-parts/review-stamp', null, array( 'post_id' => get_the_ID() ) ); ?>
			</div>
		</section>
	<?php endwhile; ?>
	<?php get_template_part( 'template-parts/wa-cta' ); ?>
</main>
<?php
get_footer();
