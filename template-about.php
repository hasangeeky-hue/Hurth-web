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
				'title'   => __( 'Started as a free Facebook group. Run by students in Germany.', 'aquariende' ),
				'lede'    => __( 'Aquariende is an online consultancy for students from Bangladesh who want to study at a German university.', 'aquariende' ),
			)
		);
		?>

		<section class="story section-pad" aria-labelledby="story-title">
			<div class="wrap story-grid">
				<div class="story-copy">
					<h2 id="story-title" class="section-title"><?php esc_html_e( 'Our story', 'aquariende' ); ?></h2>
					<p><?php esc_html_e( 'Aquariende began in 2020, when COVID-19 disrupted visas and travel for students heading abroad. We opened a Facebook group where anyone interested in higher education in Germany could get support for free.', 'aquariende' ); ?></p>
					<p><?php esc_html_e( 'The group grew to 50,000 members. Out of the cases we worked through there, we built a consultancy that covers everything from the university application to the visa.', 'aquariende' ); ?></p>
					<p><?php esc_html_e( 'We work entirely online. Our consultants are students at German universities, so the advice comes from people who have been through the same system.', 'aquariende' ); ?></p>
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
					<h2 id="hww-title" class="section-title"><?php esc_html_e( 'Your file is never hidden from you.', 'aquariende' ); ?></h2>
					<ul class="feature-list">
						<li>
							<?php echo aquariende_icon( 'file' ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
							<div><h3><?php esc_html_e( 'Your own portal', 'aquariende' ); ?></h3><p><?php esc_html_e( 'You track every admission step yourself, in the same step bar we work from.', 'aquariende' ); ?></p></div>
						</li>
						<li>
							<?php echo aquariende_icon( 'video' ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
							<div><h3><?php esc_html_e( 'Video consultations', 'aquariende' ); ?></h3><p><?php esc_html_e( 'We process your application steps in front of you, not behind a closed door.', 'aquariende' ); ?></p></div>
						</li>
						<li>
							<?php echo aquariende_icon( 'users' ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
							<div><h3><?php esc_html_e( 'Help beyond the application', 'aquariende' ); ?></h3><p><?php esc_html_e( 'We also help with paying tuition fees and with health and travel insurance.', 'aquariende' ); ?></p></div>
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
					<h2 id="mission-title" class="section-title"><?php esc_html_e( 'What we are working towards', 'aquariende' ); ?></h2>
					<p class="section-lede"><?php esc_html_e( 'A hassle-free way for students to reach higher study in Europe, starting with Germany.', 'aquariende' ); ?></p>
				</div>
				<ul class="mission-list">
					<li><?php esc_html_e( 'A platform where every student can follow their admission transparently.', 'aquariende' ); ?></li>
					<li><?php esc_html_e( 'Consultation that students can afford, paid stage by stage.', 'aquariende' ); ?></li>
					<li><?php esc_html_e( 'A process simple enough that no one has to face it alone.', 'aquariende' ); ?></li>
				</ul>
			</div>
		</section>

		<section class="team section-pad" id="team" aria-labelledby="team-title">
			<div class="wrap">
				<header class="section-head">
					<h2 id="team-title" class="section-title"><?php esc_html_e( 'The people behind your file', 'aquariende' ); ?></h2>
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
