<?php
/**
 * Template Name: Service
 *
 * One service page (04 #3a–3c). The stage data comes from aquariende_stages(),
 * matched by the page's content key; the page text is the editor content.
 *
 * @package Aquariende
 */

get_header();

$aq_key   = aquariende_key_of( get_queried_object_id() );
$aq_stage = null;
foreach ( aquariende_stages() as $aq_s ) {
	if ( $aq_s['page'] === $aq_key ) {
		$aq_stage = $aq_s;
	}
}
?>
<main id="main" class="site-main">
	<?php
	while ( have_posts() ) :
		the_post();
		get_template_part( 'template-parts/page-head' );
		?>
		<?php if ( $aq_stage ) : ?>
			<div class="section-pad section-pad--tight">
				<div class="wrap">
					<?php
					get_template_part(
						'template-parts/journey-map',
						null,
						array(
							'covered' => $aq_stage['journey'],
							'title'   => 'Stages this service covers',
							'compact' => true,
						)
					);
					?>
				</div>
			</div>
		<?php endif; ?>
		<article id="post-<?php the_ID(); ?>" <?php post_class( 'section-pad section-pad--top0' ); ?>>
			<div class="wrap service-layout">
				<div class="prose">
					<?php the_content(); ?>
				</div>
				<?php if ( $aq_stage ) : ?>
					<aside class="service-aside" aria-label="This service at a glance">
						<p class="list-label">We do</p>
						<ul class="checklist">
							<?php foreach ( $aq_stage['we_do'] as $aq_x ) : ?>
								<li><?php echo aquariende_icon( 'check' ); // phpcs:ignore WordPress.Security.EscapeOutput ?><span><?php echo esc_html( $aq_x ); ?></span></li>
							<?php endforeach; ?>
						</ul>
						<p class="list-label">You do</p>
						<ul class="checklist checklist--you">
							<?php foreach ( $aq_stage['you_do'] as $aq_x ) : ?>
								<li><?php echo aquariende_icon( 'arrow' ); // phpcs:ignore WordPress.Security.EscapeOutput ?><span><?php echo esc_html( $aq_x ); ?></span></li>
							<?php endforeach; ?>
						</ul>
						<?php
						get_template_part(
							'template-parts/never-promised',
							null,
							array(
								'title' => 'Never promised',
								'items' => $aq_stage['never'],
							)
						);
						?>
					</aside>
				<?php endif; ?>
			</div>
		</article>

		<section class="section-pad tint">
			<div class="wrap split">
				<?php get_template_part( 'template-parts/what-happens-next', null, array( 'heading' => 'h2' ) ); ?>
				<?php get_template_part( 'template-parts/have-these-ready', null, array( 'heading' => 'h2' ) ); ?>
			</div>
		</section>

		<?php if ( $aq_stage ) : ?>
			<?php
			$aq_guides = array();
			foreach ( aquariende_guides_in_order() as $aq_g ) {
				if ( get_post_meta( $aq_g->ID, '_aq_service', true ) === $aq_stage['key'] ) {
					$aq_guides[] = $aq_g;
				}
			}
			?>
			<?php if ( $aq_guides ) : ?>
				<section class="section-pad" aria-labelledby="guides-title">
					<div class="wrap">
						<h2 id="guides-title" class="section-title">The guides behind this service</h2>
						<div class="guide-grid">
							<?php
							foreach ( $aq_guides as $aq_g ) {
								get_template_part( 'template-parts/guide-card', null, array( 'post' => $aq_g ) );
							}
							?>
						</div>
					</div>
				</section>
			<?php endif; ?>
		<?php endif; ?>

		<section class="section-pad section-pad--top0">
			<div class="wrap narrow">
				<?php get_template_part( 'template-parts/faq', null, array( 'key' => $aq_key ) ); ?>
				<?php get_template_part( 'template-parts/source-list', null, array( 'post_id' => get_the_ID() ) ); ?>
			</div>
		</section>
	<?php endwhile; ?>
	<?php get_template_part( 'template-parts/wa-cta', null, array( 'steps' => false ) ); ?>
</main>
<?php
get_footer();
