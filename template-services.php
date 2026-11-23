<?php
/**
 * Template Name: Services
 *
 * /services/ (04 #3): answer → comparison table (stage · we do · you do ·
 * never promised) → the three service pages → HaveTheseReady → FAQ.
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
		<section class="section-pad" aria-labelledby="compare-title">
			<div class="wrap">
				<h2 id="compare-title" class="section-title">The three services side by side</h2>
				<?php if ( '' !== trim( get_the_content() ) ) : ?>
					<div class="prose prose--lede"><?php the_content(); ?></div>
				<?php endif; ?>
				<div class="compare-pick" data-compare-pick hidden>
					<span class="compare-pick-label" id="compare-pick-label">Show</span>
					<div class="compare-pick-set" role="group" aria-labelledby="compare-pick-label">
						<button type="button" class="chip" data-col="" aria-pressed="true">Everything</button>
						<button type="button" class="chip" data-col="2" aria-pressed="false">We do</button>
						<button type="button" class="chip" data-col="3" aria-pressed="false">You do</button>
						<button type="button" class="chip" data-col="4" aria-pressed="false">Never promised</button>
					</div>
				</div>
				<div class="table-wrap">
					<table class="compare" data-compare data-reveal>
						<caption>What each service includes, what stays with you, and what is never promised</caption>
						<thead>
							<tr>
								<th scope="col">Service</th>
								<th scope="col">We do</th>
								<th scope="col">You do</th>
								<th scope="col">Never promised</th>
							</tr>
						</thead>
						<tbody>
							<?php foreach ( aquariende_stages() as $aq_i => $aq_s ) : ?>
								<tr>
									<th scope="row" data-label="Service"><a href="<?php echo esc_url( aquariende_page_url( $aq_s['page'] ) ); ?>"><?php echo esc_html( ( $aq_i + 1 ) . '. ' . $aq_s['name'] ); ?></a></th>
									<td data-label="We do"><ul><?php foreach ( $aq_s['we_do'] as $aq_x ) : ?><li><?php echo esc_html( $aq_x ); ?></li><?php endforeach; ?></ul></td>
									<td data-label="You do"><ul><?php foreach ( $aq_s['you_do'] as $aq_x ) : ?><li><?php echo esc_html( $aq_x ); ?></li><?php endforeach; ?></ul></td>
									<td data-label="Never promised"><ul class="never-cell"><?php foreach ( $aq_s['never'] as $aq_x ) : ?><li><?php echo aquariende_icon( 'cross' ); // phpcs:ignore WordPress.Security.EscapeOutput ?><span><?php echo esc_html( $aq_x ); ?></span></li><?php endforeach; ?></ul></td>
								</tr>
							<?php endforeach; ?>
						</tbody>
					</table>
				</div>
			</div>
		</section>

		<section class="section-pad tint" aria-labelledby="pick-title">
			<div class="wrap">
				<h2 id="pick-title" class="section-title">Read each service in detail</h2>
				<div class="service-cards">
					<?php foreach ( aquariende_stages() as $aq_i => $aq_s ) : ?>
						<article class="service-card">
							<?php echo aquariende_art( aquariende_art_for_service( $aq_i ), 'service-card-art' ); // phpcs:ignore WordPress.Security.EscapeOutput -- fixed SVG. ?>
							<p class="service-num">Service <?php echo esc_html( (string) ( $aq_i + 1 ) ); ?></p>
							<h3 class="service-card-title"><a href="<?php echo esc_url( aquariende_page_url( $aq_s['page'] ) ); ?>"><?php echo esc_html( $aq_s['name'] ); ?></a></h3>
							<p><?php echo esc_html( $aq_s['summary'] ); ?></p>
						</article>
					<?php endforeach; ?>
				</div>
			</div>
		</section>

		<section class="section-pad">
			<div class="wrap split">
				<?php get_template_part( 'template-parts/have-these-ready', null, array( 'heading' => 'h2' ) ); ?>
				<?php get_template_part( 'template-parts/faq', null, array( 'key' => aquariende_key_of( get_the_ID() ) ) ); ?>
			</div>
		</section>
	<?php endwhile; ?>
	<?php get_template_part( 'template-parts/wa-cta', null, array( 'title' => 'Not sure which one you need?' ) ); ?>
</main>
<?php
get_footer();
