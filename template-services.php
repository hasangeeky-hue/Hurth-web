<?php
/**
 * Template Name: Services
 *
 * The three packages in detail, the start flow and the FAQ. No prices.
 *
 * @package Aquariende
 */

get_header();

$aq_stages = aquariende_stages();

$aq_details = array(
	'profile'     => array(
		__( 'Are you eligible for a German university? When is the best time to apply? Most students start with questions like these and no clear answers. The profile check answers them before you spend money on applications.', 'aquariende' ),
		__( 'Once you have your portal link, you upload your documents there and book a 30-minute video consultation. We go through each document, check your CV, and tell you which next steps make sense and which subjects and universities fit what you studied before.', 'aquariende' ),
	),
	'application' => array(
		__( 'Applying to a German university looks easy. Applying at random rarely leads anywhere sustainable, even when an offer letter arrives. Look before you leap: plan the subject and the university first.', 'aquariende' ),
		__( 'We find the right subject and university for your profile, write a CV and a motivation letter that fit your interests and experience, and calculate your grade precisely for the VPD. We also arrange notary support and send your documents to Germany by DHL.', 'aquariende' ),
		__( 'You get four video consultations. We work through each application step in front of you, and you can follow your whole application in your portal in real time.', 'aquariende' ),
	),
	'visa'        => array(
		__( 'No one can guarantee a visa. The German embassy follows a strict procedure, and the hard part is having every document ready on time, because the window to submit them can be short.', 'aquariende' ),
		__( 'We help you book the visa appointment in time, fill in the visa form correctly, open a blocked account and find suitable health and travel insurance. We also help with a reasonably priced flight and with train and bus tickets once you arrive in Germany.', 'aquariende' ),
		__( 'You get three live video consultations with a consultant who works through it with you.', 'aquariende' ),
	),
);

$aq_faq = array(
	array(
		__( 'How much does each package cost?', 'aquariende' ),
		__( 'We share current prices on WhatsApp before you commit to anything. You pay per stage, and nothing is paid on this website.', 'aquariende' ),
	),
	array(
		__( 'Can I take only one stage?', 'aquariende' ),
		__( 'Yes. If you only need help until your offer letter, take the application stage. If you already have an offer, start with the visa stage.', 'aquariende' ),
	),
	array(
		__( 'Do you guarantee admission or a visa?', 'aquariende' ),
		__( 'No. No one can honestly guarantee either. We make sure your profile is checked, your applications are planned and your visa documents are prepared properly.', 'aquariende' ),
	),
	array(
		__( 'Why start with a profile check?', 'aquariende' ),
		__( 'Applying without knowing whether your profile fits costs money and months on applications that cannot succeed. The check tells you where you stand first.', 'aquariende' ),
	),
	array(
		__( 'Is everything online?', 'aquariende' ),
		__( 'Yes. Consultations happen by video, and your documents and progress live in your portal, so you can work with us from anywhere.', 'aquariende' ),
	),
	array(
		__( 'How do I get into the portal?', 'aquariende' ),
		__( 'After you pay the consultation fee, we email you a private link. That is where you upload documents and book your consultation. There is no sign-up on this website.', 'aquariende' ),
	),
);
?>
<main id="main" class="site-main">
	<?php
	while ( have_posts() ) :
		the_post();
		get_template_part(
			'template-parts/page-head',
			null,
			array(
				'eyebrow' => __( 'Services', 'aquariende' ),
				'title'   => __( 'Three stages, from profile check to visa.', 'aquariende' ),
				'lede'    => __( 'Each stage is its own package. Start with the one you need. Your file shows every step, and prices are shared on WhatsApp.', 'aquariende' ),
			)
		);
		?>

		<nav class="jump wrap" aria-label="<?php esc_attr_e( 'Packages on this page', 'aquariende' ); ?>">
			<ol>
				<?php foreach ( $aq_stages as $aq_i => $aq_s ) : ?>
					<li><a href="#<?php echo esc_attr( $aq_s['key'] ); ?>"><span><?php echo esc_html( sprintf( '%02d', $aq_i + 1 ) ); ?></span> <?php echo esc_html( $aq_s['name'] ); ?></a></li>
				<?php endforeach; ?>
			</ol>
		</nav>

		<?php foreach ( $aq_stages as $aq_i => $aq_s ) : ?>
			<section class="service section-pad<?php echo ( 1 === $aq_i ) ? ' service--tint' : ''; ?>" id="<?php echo esc_attr( $aq_s['key'] ); ?>" aria-labelledby="<?php echo esc_attr( $aq_s['key'] ); ?>-title">
				<div class="wrap service-grid">
					<div class="service-copy">
						<p class="stage-num" aria-hidden="true"><?php echo esc_html( sprintf( '%02d', $aq_i + 1 ) ); ?></p>
						<h2 id="<?php echo esc_attr( $aq_s['key'] ); ?>-title" class="section-title"><?php echo esc_html( $aq_s['name'] ); ?></h2>
						<p class="service-tagline"><?php echo esc_html( $aq_s['tagline'] ); ?></p>
						<?php foreach ( $aq_details[ $aq_s['key'] ] as $aq_p ) : ?>
							<p><?php echo esc_html( $aq_p ); ?></p>
						<?php endforeach; ?>
					</div>
					<?php
					get_template_part(
						'template-parts/package-card',
						null,
						array(
							'index'    => $aq_i,
							'stage'    => $aq_s,
							'featured' => true,
							'bare'     => true,
						)
					);
					?>
				</div>
			</section>
		<?php endforeach; ?>

		<section class="faq section-pad" aria-labelledby="faq-title">
			<div class="wrap faq-grid">
				<header class="section-head">
					<h2 id="faq-title" class="section-title"><?php esc_html_e( 'Before you message us', 'aquariende' ); ?></h2>
				</header>
				<div class="faq-list">
					<?php foreach ( $aq_faq as $aq_q ) : ?>
						<details class="faq-item">
							<summary><?php echo esc_html( $aq_q[0] ); ?></summary>
							<p><?php echo esc_html( $aq_q[1] ); ?></p>
						</details>
					<?php endforeach; ?>
				</div>
			</div>
		</section>

		<?php if ( '' !== trim( get_the_content() ) ) : ?>
			<section class="section-pad">
				<div class="wrap"><div class="prose"><?php the_content(); ?></div></div>
			</section>
		<?php endif; ?>

	<?php endwhile; ?>

	<?php get_template_part( 'template-parts/cta' ); ?>
</main>
<?php
get_footer();
