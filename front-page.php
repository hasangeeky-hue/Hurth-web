<?php
/**
 * Home (04 #1): hero with the answer → VerifyStrip → JourneyMap → three
 * services → how it works → team (consented members only) → latest guides →
 * FAQ → the one WhatsApp action. The page's editor content is not shown.
 *
 * @package Aquariende
 */

get_header();

$aq_people = aquariende_people();
$aq_guides = aquariende_guides_in_order();
?>
<main id="main" class="site-main">

	<?php get_template_part( 'template-parts/hero' ); ?>

	<?php get_template_part( 'template-parts/verify-strip' ); ?>

	<section class="section-pad" aria-labelledby="journey-title">
		<div class="wrap">
			<header class="section-head">
				<h2 id="journey-title" class="section-title">Six stages, in order</h2>
				<p class="section-lede">Every student from Bangladesh goes through the same stages. Each one has official rules; our guides link them. <a href="<?php echo esc_url( aquariende_page_url( 'study-in-germany-from-bangladesh' ) ); ?>">Read the full guide to studying in Germany from Bangladesh</a>.</p>
			</header>
			<?php get_template_part( 'template-parts/journey-map' ); ?>
		</div>
	</section>

	<section class="section-pad tint" aria-labelledby="services-title">
		<div class="wrap">
			<header class="section-head">
				<h2 id="services-title" class="section-title">Three services. Take only the stage you need.</h2>
				<p class="section-lede">What is included is listed below. Fees are explained on WhatsApp before you pay; nothing is paid on this website.</p>
			</header>
			<div class="service-cards">
				<?php foreach ( aquariende_stages() as $aq_i => $aq_s ) : ?>
					<article class="service-card">
						<p class="service-num">Service <?php echo esc_html( (string) ( $aq_i + 1 ) ); ?></p>
						<h3 class="service-card-title"><a href="<?php echo esc_url( aquariende_page_url( $aq_s['page'] ) ); ?>"><?php echo esc_html( $aq_s['name'] ); ?></a></h3>
						<p><?php echo esc_html( $aq_s['summary'] ); ?></p>
						<ul class="checklist">
							<?php foreach ( $aq_s['we_do'] as $aq_item ) : ?>
								<li><?php echo aquariende_icon( 'check' ); // phpcs:ignore WordPress.Security.EscapeOutput ?><span><?php echo esc_html( $aq_item ); ?></span></li>
							<?php endforeach; ?>
						</ul>
					</article>
				<?php endforeach; ?>
			</div>
			<p class="section-more"><a class="link-arrow" href="<?php echo esc_url( aquariende_page_url( 'services' ) ); ?>">Compare the three services<?php echo aquariende_icon( 'arrow' ); // phpcs:ignore WordPress.Security.EscapeOutput ?></a></p>
		</div>
	</section>

	<section class="section-pad" aria-labelledby="how-title">
		<div class="wrap split">
			<div>
				<h2 id="how-title" class="section-title">What happens after you message us</h2>
				<p class="section-lede">Five steps, and you decide before you pay anything. <a href="<?php echo esc_url( aquariende_page_url( 'how-it-works' ) ); ?>">How it works, in detail</a>.</p>
				<?php get_template_part( 'template-parts/never-promised' ); ?>
			</div>
			<?php get_template_part( 'template-parts/what-happens-next', null, array( 'title' => 'The five steps' ) ); ?>
		</div>
	</section>

	<?php if ( $aq_people ) : ?>
		<section class="section-pad tint" id="team" aria-labelledby="team-title">
			<div class="wrap">
				<header class="section-head">
					<h2 id="team-title" class="section-title">The team</h2>
				</header>
				<ul class="team-grid">
					<?php
					foreach ( $aq_people as $aq_p ) {
						get_template_part( 'template-parts/team-card', null, array( 'person' => $aq_p ) );
					}
					?>
				</ul>
			</div>
		</section>
	<?php endif; ?>

	<?php if ( $aq_guides ) : ?>
		<section class="section-pad" aria-labelledby="guides-title">
			<div class="wrap">
				<header class="section-head section-head--row">
					<h2 id="guides-title" class="section-title">Guides with official sources</h2>
					<a class="link-arrow" href="<?php echo esc_url( aquariende_page_url( 'guides' ) ); ?>">All guides<?php echo aquariende_icon( 'arrow' ); // phpcs:ignore WordPress.Security.EscapeOutput ?></a>
				</header>
				<div class="guide-grid">
					<?php
					// The three questions asked most often in both languages (02 A1): visa, money, costs.
					$aq_shown = 0;
					foreach ( array( 'guide-visa', 'guide-blocked', 'guide-cost' ) as $aq_key ) {
						$aq_id = aquariende_content_id( $aq_key );
						if ( $aq_id ) {
							get_template_part( 'template-parts/guide-card', null, array( 'post' => get_post( $aq_id ) ) );
							++$aq_shown;
						}
					}
					if ( ! $aq_shown ) {
						foreach ( array_slice( $aq_guides, 0, 3 ) as $aq_g ) {
							get_template_part( 'template-parts/guide-card', null, array( 'post' => $aq_g ) );
						}
					}
					?>
				</div>
			</div>
		</section>
	<?php endif; ?>

	<section class="section-pad tint">
		<div class="wrap narrow">
			<?php get_template_part( 'template-parts/faq', null, array( 'key' => 'home' ) ); ?>
		</div>
	</section>

	<?php get_template_part( 'template-parts/wa-cta', null, array( 'title' => 'Start with one message', 'steps' => false ) ); ?>

</main>
<?php
get_footer();
