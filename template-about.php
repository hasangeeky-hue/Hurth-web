<?php
/**
 * Template Name: About
 *
 * Story since 2020, how we work, mission, team.
 *
 * @package Aquariende
 */

get_header();
?>
<main id="main" class="site-main">
	<?php
	while ( have_posts() ) :
		the_post();
		get_template_part(
			'template-parts/page-head',
			null,
			array(
				'eyebrow' => __( 'About', 'aquariende' ),
				'title'   => __( 'About us', 'aquariende' ),
				'lede'    => __( 'Aquariende is an online-based higher studies consultancy firm in Bangladesh. We provide solutions for studying in German universities, including obtaining German visas.', 'aquariende' ),
			)
		);
		?>

		<section class="story section-pad" aria-labelledby="story-title">
			<div class="wrap story-grid">
				<div class="story-copy">
					<h2 id="story-title" class="section-title"><?php esc_html_e( 'Our story', 'aquariende' ); ?></h2>
					<p><?php esc_html_e( 'Our mission started in the year 2020, when the COVID-19 pandemic began. During that period, the normal process for obtaining a visa and entering the country was disrupted. We opened our Facebook group to anybody who is interested in pursuing higher education and wants to get support for free.', 'aquariende' ); ?></p>
					<p><?php esc_html_e( 'After three years of progress along this path, we have reached the point where we have 50,000 members in our community, and we now provide consultation for students who want to enroll in German institutions. From the application procedure to obtaining the visa, we cover everything.', 'aquariende' ); ?></p>
					<p><?php esc_html_e( 'We have a very experienced workforce, all of them students from highly regarded German institutions.', 'aquariende' ); ?></p>
				</div>
				<dl class="facts">
					<div><dt><?php esc_html_e( 'Started', 'aquariende' ); ?></dt><dd>2020</dd></div>
					<div><dt><?php esc_html_e( 'Community', 'aquariende' ); ?></dt><dd><?php echo esc_html( sprintf( /* translators: %s: number. */ __( '%s members', 'aquariende' ), aquariende_info( 'community' ) ) ); ?></dd></div>
					<div><dt><?php esc_html_e( 'Team', 'aquariende' ); ?></dt><dd><?php esc_html_e( 'Students at five German universities', 'aquariende' ); ?></dd></div>
					<div><dt><?php esc_html_e( 'Works', 'aquariende' ); ?></dt><dd><?php esc_html_e( 'Online, by video and WhatsApp', 'aquariende' ); ?></dd></div>
				</dl>
			</div>
		</section>

		<section class="how-we-work section-pad service--tint" aria-labelledby="hww-title">
			<div class="wrap hww-grid">
				<div>
					<h2 id="hww-title" class="section-title"><?php esc_html_e( 'Why are we the best?', 'aquariende' ); ?></h2>
					<ul class="feature-list">
						<li>
							<?php echo aquariende_icon( 'file' ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
							<div><h3><?php esc_html_e( 'Track your admission', 'aquariende' ); ?></h3><p><?php esc_html_e( 'We have established a platform via which a student can track and monitor his admission activities directly.', 'aquariende' ); ?></p></div>
						</li>
						<li>
							<?php echo aquariende_icon( 'video' ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
							<div><h3><?php esc_html_e( 'Video consultations', 'aquariende' ); ?></h3><p><?php esc_html_e( 'We provide video consultation based on the services you take. There are no iron curtains between you and us, so you can complete your admission file from anywhere in the world.', 'aquariende' ); ?></p></div>
						</li>
						<li>
							<?php echo aquariende_icon( 'users' ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
							<div><h3><?php esc_html_e( 'Help beyond the application', 'aquariende' ); ?></h3><p><?php esc_html_e( 'We assist students in processing their files for Germany, paying their tuition fees, and opening health and travel insurance policies.', 'aquariende' ); ?></p></div>
						</li>
					</ul>
				</div>
				<?php
				get_template_part(
					'template-parts/file-card',
					null,
					array(
						'stage'   => 2,
						'id'      => 'about-file',
						'caption' => true,
					)
				);
				?>
			</div>
		</section>

		<section class="mission section-pad" aria-labelledby="mission-title">
			<div class="wrap mission-grid">
				<div>
					<h2 id="mission-title" class="section-title"><?php esc_html_e( 'Our mission and vision', 'aquariende' ); ?></h2>
					<p class="section-lede"><?php esc_html_e( 'We want to develop a hassle-free platform where a student can fulfill the dream of higher studies in Europe. We are here for your help.', 'aquariende' ); ?></p>
				</div>
				<ul class="mission-list">
					<li><?php esc_html_e( 'We want to create a suitable platform where a student can track his admission process transparently.', 'aquariende' ); ?></li>
					<li><?php esc_html_e( 'We provide better and more affordable consultation than any other agency.', 'aquariende' ); ?></li>
					<li><?php esc_html_e( 'We established an easy platform where a student does not need to face any hassle.', 'aquariende' ); ?></li>
				</ul>
			</div>
		</section>

		<section class="team section-pad" id="team" aria-labelledby="team-title">
			<div class="wrap">
				<header class="section-head">
					<h2 id="team-title" class="section-title"><?php esc_html_e( 'Our talented team', 'aquariende' ); ?></h2>
				</header>
				<ul class="team-grid team-grid--open">
					<?php
					foreach ( aquariende_team() as $aq_i => $aq_m ) {
						get_template_part(
							'template-parts/team-card',
							null,
							array(
								'member' => $aq_m,
								'index'  => $aq_i,
							)
						);
					}
					?>
				</ul>
			</div>
		</section>

		<?php if ( '' !== trim( get_the_content() ) ) : ?>
			<section class="section-pad">
				<div class="wrap"><div class="prose"><?php the_content(); ?></div></div>
			</section>
		<?php endif; ?>

	<?php endwhile; ?>

	<?php get_template_part( 'template-parts/cta', null, array( 'form' => false ) ); ?>
</main>
<?php
get_footer();
