<?php
/**
 * Home, "The route" (v2.1): the page is one journey from Dhaka to Germany.
 *
 * 1. Hero: the offer, the WhatsApp action, and the route drawing itself.
 * 2. "Where are you now?": four starting points, each with its next steps.
 * 3. The six stages on one line that fills as you scroll.
 * 4. The three services laid over the stages they cover.
 * 5. What happens after a message, as a five-step diagram.
 * 6. Who is on the other end, and what we never do.
 * 7. Guides, questions, and the action again.
 *
 * Every sentence comes from inc/data.php or the installed content; nothing is
 * written here that is not already on the site.
 *
 * @package Aquariende
 */

get_header();

$aq_front    = (int) get_option( 'page_on_front' );
$aq_answer   = $aq_front ? aquariende_page_meta( $aq_front, '_aq_answer' ) : '';
$aq_journey  = aquariende_journey();
$aq_keys     = array_keys( $aq_journey );
$aq_services = aquariende_stages();
$aq_guides   = aquariende_guides_in_order();
$aq_pillar   = aquariende_page_url( 'study-in-germany-from-bangladesh' );

// Which service covers which stage, and where each service starts and ends on the six-stage line.
$aq_cover = array();
$aq_spans = array();
foreach ( $aq_services as $aq_i => $aq_s ) {
	$aq_at = array();
	foreach ( (array) $aq_s['journey'] as $aq_slug ) {
		$aq_pos = array_search( $aq_slug, $aq_keys, true );
		if ( false !== $aq_pos ) {
			$aq_at[]              = $aq_pos + 1;
			$aq_cover[ $aq_slug ] = $aq_s;
		}
	}
	$aq_spans[ $aq_i ] = $aq_at ? array( min( $aq_at ), max( $aq_at ) ) : array( 1, 1 );
}

// Guides per stage, once.
$aq_stage_guides = array();
foreach ( $aq_keys as $aq_slug ) {
	$aq_stage_guides[ $aq_slug ] = aquariende_stage_guides( $aq_slug );
}

/**
 * The four starting points of the "Where are you now?" picker.
 * Each one points at stages and a service that already exist on the site.
 */
$aq_starts = array(
	array(
		'id'      => 'start',
		'label'   => 'I am just starting',
		'stages'  => array( 'eligibility' ),
		'service' => 0,
	),
	array(
		'id'      => 'apply',
		'label'   => 'I am choosing universities',
		'stages'  => array( 'applications', 'offer' ),
		'service' => 1,
	),
	array(
		'id'      => 'visa',
		'label'   => 'I have an admission letter',
		'stages'  => array( 'money', 'visa' ),
		'service' => 2,
	),
);

// First screen: one sentence of who we are (the full answer follows in the next section).
$aq_lede = '';
if ( $aq_answer && preg_match( '/^.*?[.!?](?=\s|$)/u', $aq_answer, $aq_m ) ) {
	$aq_lede = $aq_m[0];
}

// The questions students ask first: cost, the visa, and whether this is an agency (02 A1).
// Each line is the question already stored with that guide or FAQ item.
$aq_quick = array();
foreach ( array( 'guide-cost', 'guide-visa' ) as $aq_key ) {
	$aq_id = aquariende_content_id( $aq_key );
	$aq_qt = $aq_id ? (string) get_post_meta( $aq_id, '_aq_question', true ) : '';
	if ( $aq_id && $aq_qt ) {
		$aq_quick[] = array(
			'text' => $aq_qt,
			'url'  => get_permalink( $aq_id ),
		);
	}
}
foreach ( (array) aquariende_faqs_for( 'home' ) as $aq_f ) {
	if ( count( $aq_quick ) >= 4 ) {
		break;
	}
	$aq_quick[] = array(
		'text' => get_the_title( $aq_f ),
		'url'  => aquariende_page_url( 'faq' ) . '#q-' . $aq_f->post_name,
	);
}
?>
<main id="main" class="site-main rt-home">

	<section class="rt-hero dark" aria-labelledby="hero-title">
		<div class="wrap rt-hero-grid">
			<div class="rt-hero-copy">
				<h1 id="hero-title" class="rt-hero-title">Study in Germany from Bangladesh, in your own name</h1>
				<?php if ( $aq_lede ) : ?>
					<p class="rt-hero-lede"><?php echo esc_html( $aq_lede ); ?></p>
				<?php endif; ?>
				<div class="rt-hero-actions">
					<?php echo aquariende_wa_button( 'hero', array( 'class' => 'btn btn-primary btn-xl' ) ); // phpcs:ignore WordPress.Security.EscapeOutput -- escaped inside. ?>
					<ul class="rt-assure">
						<li><?php echo aquariende_icon( 'check' ); // phpcs:ignore WordPress.Security.EscapeOutput ?><span>We call you from Germany within 24 hours</span></li>
						<li><?php echo aquariende_icon( 'check' ); // phpcs:ignore WordPress.Security.EscapeOutput ?><span>Nothing is paid on this website</span></li>
						<li><?php echo aquariende_icon( 'check' ); // phpcs:ignore WordPress.Security.EscapeOutput ?><span>English or Bengali</span></li>
					</ul>
				</div>
			</div>

			<nav class="rt-quick" aria-labelledby="quick-title">
				<h2 id="quick-title" class="rt-quick-title">Questions students ask first</h2>
				<ul>
					<?php foreach ( $aq_quick as $aq_q ) : ?>
						<li><a href="<?php echo esc_url( $aq_q['url'] ); ?>"><span><?php echo esc_html( $aq_q['text'] ); ?></span><?php echo aquariende_icon( 'arrow' ); // phpcs:ignore WordPress.Security.EscapeOutput ?></a></li>
					<?php endforeach; ?>
				</ul>
			</nav>
		</div>

		<div class="wrap">
			<ol class="rt-line" aria-label="The six stages from Dhaka to Germany">
				<?php
				$aq_n = 0;
				foreach ( $aq_journey as $aq_slug => $aq_stage ) :
					++$aq_n;
					?>
					<li style="--i:<?php echo esc_attr( (string) ( $aq_n - 1 ) ); ?>">
						<a href="<?php echo esc_url( $aq_pillar . '#' . $aq_slug ); ?>">
							<span class="rt-line-n" aria-hidden="true"><?php echo esc_html( (string) $aq_n ); ?></span>
							<span class="rt-line-l"><?php echo esc_html( $aq_stage['label'] ); ?></span>
						</a>
					</li>
				<?php endforeach; ?>
			</ol>
			<p class="rt-line-ends" aria-hidden="true"><span>Dhaka</span><span>Germany</span></p>
		</div>
	</section>

	<section class="rt-trust" aria-labelledby="trust-title">
		<div class="wrap rt-two">
			<div>
				<h2 id="trust-title" class="rt-h2">A team in Germany</h2>
				<?php if ( $aq_answer ) : ?>
					<p class="rt-body"><?php echo esc_html( $aq_answer ); ?></p>
				<?php endif; ?>
				<ul class="rt-facts">
					<li>Our team members went to Germany to study at TUM, HAW Hamburg and the universities of Bremen, Potsdam and Schmalkalden.</li>
					<li>We started in 2020 as a free Facebook group for anyone who wanted to study abroad. <a href="<?php echo esc_url( aquariende_page_url( 'about' ) ); ?>">Read our story</a></li>
				</ul>
			</div>
			<div class="rt-never-box">
				<h3 class="rt-h3">We never</h3>
				<ul class="rt-never">
					<?php foreach ( aquariende_never() as $aq_item ) : ?>
						<li><?php echo aquariende_icon( 'cross' ); // phpcs:ignore WordPress.Security.EscapeOutput ?><span><?php echo esc_html( $aq_item ); ?></span></li>
					<?php endforeach; ?>
				</ul>
			</div>
		</div>
	</section>

	<section class="rt-where" id="where" aria-labelledby="where-title">
		<div class="wrap">
			<h2 id="where-title" class="rt-h2">Where are you now?</h2>
			<p class="rt-sub">Choose the line that fits you. You get your next steps and the one service that covers them.</p>

			<div class="rt-picker" data-picker>
				<div class="rt-tabs" role="tablist" aria-label="Where are you now?">
					<?php foreach ( $aq_starts as $aq_i => $aq_st ) : ?>
						<button class="rt-tab" type="button" role="tab" id="tab-<?php echo esc_attr( $aq_st['id'] ); ?>" aria-controls="panel-<?php echo esc_attr( $aq_st['id'] ); ?>" aria-selected="<?php echo 0 === $aq_i ? 'true' : 'false'; ?>"<?php echo 0 === $aq_i ? '' : ' tabindex="-1"'; ?>>
							<span class="rt-tab-n" aria-hidden="true"><?php echo esc_html( implode( '–', array_unique( array( array_search( $aq_st['stages'][0], $aq_keys, true ) + 1, array_search( end( $aq_st['stages'] ), $aq_keys, true ) + 1 ) ) ) ); ?></span>
							<span><?php echo esc_html( $aq_st['label'] ); ?></span>
						</button>
					<?php endforeach; ?>
					<button class="rt-tab" type="button" role="tab" id="tab-parent" aria-controls="panel-parent" aria-selected="false" tabindex="-1">
						<span class="rt-tab-n" aria-hidden="true"><?php echo aquariende_icon( 'cap' ); // phpcs:ignore WordPress.Security.EscapeOutput ?></span>
						<span>I am a parent</span>
					</button>
				</div>

				<?php
				foreach ( $aq_starts as $aq_i => $aq_st ) :
					$aq_svc   = $aq_services[ $aq_st['service'] ];
					$aq_links = array();
					foreach ( $aq_st['stages'] as $aq_slug ) {
						foreach ( $aq_stage_guides[ $aq_slug ] as $aq_g ) {
							$aq_links[ $aq_g->ID ] = $aq_g;
						}
					}
					$aq_links = array_slice( $aq_links, 0, 3 );
					?>
					<div class="rt-panel" role="tabpanel" id="panel-<?php echo esc_attr( $aq_st['id'] ); ?>" aria-labelledby="tab-<?php echo esc_attr( $aq_st['id'] ); ?>"<?php echo 0 === $aq_i ? '' : ' data-collapsed'; ?>>
						<div class="rt-panel-steps">
							<h3 class="rt-h3">Your next steps</h3>
							<ol class="rt-next">
								<?php foreach ( $aq_st['stages'] as $aq_slug ) : ?>
									<li>
										<strong><?php echo esc_html( $aq_journey[ $aq_slug ]['label'] ); ?></strong>
										<span><?php echo esc_html( $aq_journey[ $aq_slug ]['summary'] ); ?></span>
									</li>
								<?php endforeach; ?>
							</ol>
							<?php if ( $aq_links ) : ?>
								<p class="rt-read">Read first</p>
								<ul class="rt-links">
									<?php foreach ( $aq_links as $aq_g ) : ?>
										<li><a href="<?php echo esc_url( get_permalink( $aq_g ) ); ?>"><?php echo esc_html( get_the_title( $aq_g ) ); ?><?php echo aquariende_icon( 'arrow' ); // phpcs:ignore WordPress.Security.EscapeOutput ?></a></li>
									<?php endforeach; ?>
								</ul>
							<?php endif; ?>
						</div>
						<div class="rt-panel-service">
							<h3 class="rt-h3"><?php echo esc_html( $aq_svc['short'] ); ?></h3>
							<p><?php echo esc_html( $aq_svc['summary'] ); ?></p>
							<div class="rt-panel-actions">
								<a class="btn btn-primary" href="<?php echo esc_url( aquariende_whatsapp_url( $aq_svc['wa'], 'HOME-' . $aq_svc['wa_code'] ) ); ?>" target="_blank" rel="noopener" data-wa="<?php echo esc_attr( 'HOME-' . $aq_svc['wa_code'] . ':picker' ); ?>">
									<?php echo aquariende_icon( 'whatsapp' ); // phpcs:ignore WordPress.Security.EscapeOutput ?><span>Ask about this on WhatsApp</span><span class="screen-reader-text"> (opens WhatsApp)</span>
								</a>
								<a class="btn-text" href="<?php echo esc_url( aquariende_page_url( $aq_svc['page'] ) ); ?>">What is included</a>
							</div>
						</div>
					</div>
				<?php endforeach; ?>

				<div class="rt-panel" role="tabpanel" id="panel-parent" aria-labelledby="tab-parent" data-collapsed>
					<div class="rt-panel-steps">
						<h3 class="rt-h3">What you can check yourself</h3>
						<ul class="rt-never">
							<?php foreach ( aquariende_never() as $aq_item ) : ?>
								<li><?php echo aquariende_icon( 'cross' ); // phpcs:ignore WordPress.Security.EscapeOutput ?><span>We never: <?php echo esc_html( lcfirst( $aq_item ) ); ?></span></li>
							<?php endforeach; ?>
						</ul>
					</div>
					<div class="rt-panel-service">
						<h3 class="rt-h3">For parents</h3>
						<p>What your child's study plan involves.</p>
						<div class="rt-panel-actions">
							<a class="btn btn-primary" href="<?php echo esc_url( aquariende_page_url( 'for-parents' ) ); ?>">Read the page for parents</a>
							<a class="btn-text" href="<?php echo esc_url( aquariende_page_url( 'how-it-works' ) ); ?>">How it works</a>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>

	<section class="rt-journey" aria-labelledby="journey-title">
		<div class="wrap">
			<h2 id="journey-title" class="rt-h2">Six stages, in order</h2>
			<p class="rt-sub">Every student from Bangladesh goes through the same stages. Each one has official rules; our guides link them.</p>

			<ol class="rt-route" data-route>
				<li class="rt-route-fill" aria-hidden="true"></li>
				<?php
				$aq_n = 0;
				foreach ( $aq_journey as $aq_slug => $aq_stage ) :
					++$aq_n;
					?>
					<li class="rt-station" data-station>
						<span class="rt-dot" aria-hidden="true"><?php echo esc_html( (string) $aq_n ); ?></span>
						<div class="rt-station-main">
							<?php echo aquariende_art( $aq_slug, 'rt-station-art' ); // phpcs:ignore WordPress.Security.EscapeOutput -- fixed SVG. ?>
							<h3 class="rt-station-title"><a href="<?php echo esc_url( $aq_pillar . '#' . $aq_slug ); ?>"><?php echo esc_html( $aq_stage['label'] ); ?></a></h3>
							<p><?php echo esc_html( $aq_stage['summary'] ); ?></p>
						</div>
						<div class="rt-station-side">
							<?php if ( ! empty( $aq_stage_guides[ $aq_slug ] ) ) : ?>
								<ul class="rt-links">
									<?php foreach ( array_slice( $aq_stage_guides[ $aq_slug ], 0, 3 ) as $aq_g ) : ?>
										<li><a href="<?php echo esc_url( get_permalink( $aq_g ) ); ?>"><?php echo esc_html( get_the_title( $aq_g ) ); ?><?php echo aquariende_icon( 'arrow' ); // phpcs:ignore WordPress.Security.EscapeOutput ?></a></li>
									<?php endforeach; ?>
								</ul>
							<?php endif; ?>
							<?php if ( isset( $aq_cover[ $aq_slug ] ) ) : ?>
								<p class="rt-covered">We help here: <a href="<?php echo esc_url( aquariende_page_url( $aq_cover[ $aq_slug ]['page'] ) ); ?>"><?php echo esc_html( $aq_cover[ $aq_slug ]['short'] ); ?></a></p>
							<?php endif; ?>
						</div>
					</li>
				<?php endforeach; ?>
			</ol>

			<p class="rt-more"><a class="btn btn-outline" href="<?php echo esc_url( $aq_pillar ); ?>">Read the full guide to studying in Germany from Bangladesh</a></p>
		</div>
	</section>

	<section class="rt-services" aria-labelledby="services-title">
		<div class="wrap">
			<h2 id="services-title" class="rt-h2">Three services. Take only the stage you need.</h2>
			<p class="rt-sub">Fees are explained on WhatsApp before you pay; nothing is paid on this website.</p>

			<div class="rt-svc-list">
				<?php foreach ( $aq_services as $aq_i => $aq_s ) : ?>
					<article class="rt-svc rt-svc--<?php echo esc_attr( (string) ( $aq_i + 1 ) ); ?>">
						<?php echo aquariende_art( aquariende_art_for_service( $aq_i ), 'rt-svc-art' ); // phpcs:ignore WordPress.Security.EscapeOutput -- fixed SVG. ?>
						<h3 class="rt-svc-title"><a href="<?php echo esc_url( aquariende_page_url( $aq_s['page'] ) ); ?>"><?php echo esc_html( $aq_s['name'] ); ?></a></h3>
						<?php
						$aq_from = $aq_spans[ $aq_i ][0];
						$aq_to   = $aq_spans[ $aq_i ][1];
						?>
						<p class="rt-svc-stages">
							<span class="rt-dots" aria-hidden="true">
								<?php for ( $aq_d = 1; $aq_d <= 6; $aq_d++ ) : ?>
									<i<?php echo ( $aq_d >= $aq_from && $aq_d <= $aq_to ) ? ' class="on"' : ''; ?>><?php echo esc_html( (string) $aq_d ); ?></i>
								<?php endfor; ?>
							</span>
							<span><?php echo esc_html( $aq_from === $aq_to ? 'Covers stage ' . $aq_from : 'Covers stages ' . $aq_from . ' to ' . $aq_to ); ?></span>
						</p>
						<p><?php echo esc_html( $aq_s['summary'] ); ?></p>
						<ul class="checklist">
							<?php foreach ( $aq_s['we_do'] as $aq_item ) : ?>
								<li><?php echo aquariende_icon( 'check' ); // phpcs:ignore WordPress.Security.EscapeOutput ?><span><?php echo esc_html( $aq_item ); ?></span></li>
							<?php endforeach; ?>
						</ul>
						<a class="btn btn-outline" href="<?php echo esc_url( aquariende_page_url( $aq_s['page'] ) ); ?>">See what is included</a>
					</article>
				<?php endforeach; ?>
			</div>
		</div>
	</section>

	<section class="rt-flow dark" aria-labelledby="how-title">
		<div class="wrap">
			<h2 id="how-title" class="rt-h2">What happens after you message us</h2>
			<p class="rt-sub">Five steps, and you decide before you pay anything.</p>
			<ol class="rt-steps" data-reveal>
				<?php foreach ( aquariende_flow() as $aq_i => $aq_step ) : ?>
					<li style="--i:<?php echo esc_attr( (string) $aq_i ); ?>">
						<span class="rt-step-n" aria-hidden="true"><?php echo esc_html( (string) ( $aq_i + 1 ) ); ?></span>
						<strong><?php echo esc_html( $aq_step['title'] ); ?></strong>
						<span><?php echo esc_html( $aq_step['text'] ); ?></span>
					</li>
				<?php endforeach; ?>
			</ol>
			<div class="rt-flow-foot">
				<?php echo aquariende_wa_button( 'flow', array( 'class' => 'btn btn-primary btn-lg', 'label' => 'Send the first message' ) ); // phpcs:ignore WordPress.Security.EscapeOutput -- escaped inside. ?>
				<a class="btn-text" href="<?php echo esc_url( aquariende_page_url( 'how-it-works' ) ); ?>">How it works, in detail</a>
			</div>
		</div>
	</section>

	<?php if ( $aq_guides ) : ?>
		<section class="rt-guides" aria-labelledby="guides-title">
			<div class="wrap">
				<header class="rt-head-row">
					<h2 id="guides-title" class="rt-h2">Guides with official sources</h2>
					<a class="btn btn-outline" href="<?php echo esc_url( aquariende_page_url( 'guides' ) ); ?>">All guides</a>
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

	<section class="rt-faq">
		<div class="wrap">
			<?php get_template_part( 'template-parts/faq', null, array( 'key' => 'home' ) ); ?>
		</div>
	</section>

	<?php get_template_part( 'template-parts/wa-cta', null, array( 'title' => 'Start with one message', 'steps' => false ) ); ?>

</main>
<?php
get_footer();
