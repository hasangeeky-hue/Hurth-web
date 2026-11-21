<?php
/**
 * Template Name: Services
 *
 * The three packages in detail and the FAQ. No prices. Copy from the 2022
 * "Service" and "Home" pages and the three 2022 posts.
 *
 * @package Aquariende
 */

get_header();

$aq_stages = aquariende_stages();

$aq_details = array(
	'profile'     => array(
		__( 'Do you have many questions in your mind about whether you are eligible for a German university application or not? When is the best time to apply to a German university? There are lots of questions in your mind and you don\'t know the exact answers. We are here to solve your problems.', 'aquariende' ),
		__( 'Under our mandatory profile check service, you upload all your documents and book an appointment to talk with our experts. We will analyze each of your documents and advise you which steps would be better for your applications.', 'aquariende' ),
		__( 'We have an expert team with versatile experience in German university admission and visa processing. You don\'t need to be confused about your higher studies journey: we are here to help with the right information and the right procedure.', 'aquariende' ),
	),
	'application' => array(
		__( 'Application to a German university seems easy, but it is not so easy if you do not have a structured plan. By applying randomly you may get an offer letter, but it is not going to be sustainable in the long term. Our advice is “look before you leap”: it is better to think first than to go ahead without a sustainable plan.', 'aquariende' ),
		__( 'We have a team of experts. We are going to find the right subject and the right university for you according to your profile. Our expert team will make your CV and write your motivation letter aligned with your interests and experience. We also calculate your grade very precisely for the VPD. In addition, we will arrange your notary and send your documents to Germany (DHL).', 'aquariende' ),
		__( 'Under this service, you will have 4 video consultations with our expert. We process every application step in front of you, so you can monitor your application process directly. There is no hide-and-seek: we do all the application procedures in a very transparent way, and you can track your whole application process in real time.', 'aquariende' ),
	),
	'visa'        => array(
		__( 'Processing a visa is not so easy. No one can guarantee a visa: the visa application for the German embassy strictly follows some procedures. The main challenge is to submit every document in a timely manner.', 'aquariende' ),
		__( 'Under the visa application process service, we will assist you to take the visa appointment in a timely manner, fill out the visa form properly, open a blocked account, find better health and travel insurance, get air tickets at a reasonable price, and manage train and bus tickets after you arrive in Germany.', 'aquariende' ),
		__( 'In addition, you will get 3 live consultations with our expert, who will assist you directly to make it easy for you.', 'aquariende' ),
	),
);

$aq_faq = array(
	array(
		__( 'Can I take only one service?', 'aquariende' ),
		__( 'Yes. Aquariende offers you specific services. For example, if you need service until you get an offer letter, go for it; or if you need service only for a visa application, take that one.', 'aquariende' ),
	),
	array(
		__( 'Do you guarantee a visa?', 'aquariende' ),
		__( 'No one can guarantee a visa. The visa application for the German embassy strictly follows some procedures. We will help you prepare your visa documentation properly.', 'aquariende' ),
	),
	array(
		__( 'Why start with a profile check?', 'aquariende' ),
		__( 'Before submitting an application to the university, you should evaluate your qualifications to determine whether or not you have a chance of being accepted given the requirements of the German education system. Without it, you can spend money on applications and still get no offer letter.', 'aquariende' ),
	),
	array(
		__( 'Is everything online?', 'aquariende' ),
		__( 'Yes. There are no iron curtains between the student and the service providers, so you can complete your admission file from anywhere in the world.', 'aquariende' ),
	),
	array(
		__( 'What else do you help with?', 'aquariende' ),
		__( 'We also assist students in processing their files for Germany, paying their tuition fees, and opening health and travel insurance policies.', 'aquariende' ),
	),
	array(
		__( 'How do I get into the portal?', 'aquariende' ),
		__( 'After you pay the consultation fee, we send a link to your portal to your email address. That is where you upload your documents and book your consultation. There is no sign-up on this website.', 'aquariende' ),
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
				'title'   => __( 'Our services', 'aquariende' ),
				'lede'    => __( 'Our service starts with a profile check and ends with the visa application. You will find services according to your needs.', 'aquariende' ),
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
					<h2 id="faq-title" class="section-title"><?php esc_html_e( 'Questions', 'aquariende' ); ?></h2>
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
