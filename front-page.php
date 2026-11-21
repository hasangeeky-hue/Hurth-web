<?php
/**
 * Front page: hero, trust strip, how it works, packages, why, team, posts, CTA.
 *
 * The copy lives here (translatable) rather than in the editor so the layout
 * cannot drift. The static front page's own editor content is not shown.
 *
 * @package Aquariende
 */

get_header();

$aq_stages = aquariende_stages();
?>
<main id="main" class="site-main">

	<?php get_template_part( 'template-parts/hero' ); ?>

	<section class="trust" aria-label="<?php esc_attr_e( 'About Aquariende in brief', 'aquariende' ); ?>">
		<ul class="wrap trust-list">
			<li class="trust-item">
				<p class="trust-big"><?php esc_html_e( 'Since 2020', 'aquariende' ); ?></p>
				<p class="trust-small"><?php esc_html_e( 'Our mission started in 2020, when COVID-19 disrupted the normal process for obtaining a visa.', 'aquariende' ); ?></p>
			</li>
			<li class="trust-item">
				<p class="trust-big">
					<?php
					/* translators: %s: number of members. */
					echo esc_html( sprintf( __( '%s members', 'aquariende' ), aquariende_info( 'community' ) ) );
					?>
				</p>
				<p class="trust-small"><?php esc_html_e( 'in our community, which started as a free Facebook group.', 'aquariende' ); ?></p>
			</li>
			<li class="trust-item">
				<p class="trust-big"><?php esc_html_e( 'Team in Germany', 'aquariende' ); ?></p>
				<p class="trust-small"><?php esc_html_e( 'Our team members study at TUM, Bremen, Potsdam, HAW Hamburg and Schmalkalden.', 'aquariende' ); ?></p>
			</li>
		</ul>
	</section>

	<section class="hiw" id="how" aria-labelledby="how-title">
		<div class="wrap">
			<header class="section-head">
				<h2 id="how-title" class="section-title"><?php esc_html_e( 'Our service starts with a profile check and ends with the visa application.', 'aquariende' ); ?></h2>
				<p class="section-lede"><?php esc_html_e( 'You will find services according to your needs: take only the one you need.', 'aquariende' ); ?></p>
			</header>

			<div class="hiw-stage-area">
				<div class="hiw-rail">
					<ol class="hiw-nav" aria-label="<?php esc_attr_e( 'Stages', 'aquariende' ); ?>">
						<?php foreach ( $aq_stages as $aq_i => $aq_s ) : ?>
							<li><a class="hiw-nav-link<?php echo 0 === $aq_i ? ' is-active' : ''; ?>" href="#stage-<?php echo esc_attr( $aq_i + 1 ); ?>" data-go="<?php echo esc_attr( $aq_i + 1 ); ?>"><span class="hiw-nav-num"><?php echo esc_html( sprintf( '%02d', $aq_i + 1 ) ); ?></span> <span class="hiw-nav-name"><?php echo esc_html( $aq_s['name'] ); ?></span></a></li>
						<?php endforeach; ?>
					</ol>
					<div class="hiw-stages">
						<?php
						foreach ( $aq_stages as $aq_i => $aq_s ) {
							get_template_part(
								'template-parts/stage',
								null,
								array(
									'index' => $aq_i,
									'stage' => $aq_s,
								)
							);
						}
						?>
					</div>
				</div>
				<div class="hiw-file" aria-hidden="true">
					<?php
					get_template_part(
						'template-parts/file-card',
						null,
						array(
							'stage' => 1,
							'id'    => 'hiw-file',
							'class' => 'file-card--pinned',
						)
					);
					?>
				</div>
			</div>
		</div>
	</section>

	<section class="packages" id="packages" aria-labelledby="packages-title">
		<div class="wrap">
			<header class="section-head">
				<h2 id="packages-title" class="section-title"><?php esc_html_e( 'Our exclusive services', 'aquariende' ); ?></h2>
				<p class="section-lede"><?php esc_html_e( 'We provide a variety of services for getting admission in higher education institutions in Germany, ranging from university entrance to visa processing.', 'aquariende' ); ?></p>
			</header>
			<div class="package-grid">
				<?php
				foreach ( $aq_stages as $aq_i => $aq_s ) {
					get_template_part(
						'template-parts/package-card',
						null,
						array(
							'index'    => $aq_i,
							'stage'    => $aq_s,
							'featured' => ( 1 === $aq_i ),
						)
					);
				}
				?>
			</div>
			<p class="section-more"><a class="link-arrow" href="<?php echo esc_url( aquariende_page_url( 'services' ) ); ?>"><?php esc_html_e( 'Read the full service details', 'aquariende' ); ?><?php echo aquariende_icon( 'arrow' ); // phpcs:ignore WordPress.Security.EscapeOutput ?></a></p>
		</div>
	</section>

	<section class="why" id="why" aria-labelledby="why-title">
		<div class="wrap why-grid">
			<header class="section-head">
				<h2 id="why-title" class="section-title"><?php esc_html_e( 'Why are we the best?', 'aquariende' ); ?></h2>
				<p class="section-lede"><?php esc_html_e( 'There are a number of agencies in Bangladesh that provide counselling services to students who wish to pursue their higher education in Europe, particularly in Germany. In reality, they ask for a significant amount of money, and several students have been tricked into losing their money.', 'aquariende' ); ?></p>
			</header>
			<div class="table-scroll" tabindex="0" role="region" aria-label="<?php esc_attr_e( 'Comparison, scrolls sideways', 'aquariende' ); ?>">
				<table class="compare">
					<thead>
						<tr>
							<th scope="col"><span class="screen-reader-text"><?php esc_html_e( 'Topic', 'aquariende' ); ?></span></th>
							<th scope="col"><?php esc_html_e( 'Agencies', 'aquariende' ); ?></th>
							<th scope="col" class="compare-us"><?php esc_html_e( 'Aquariende', 'aquariende' ); ?></th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<th scope="row"><?php esc_html_e( 'What you pay', 'aquariende' ); ?></th>
							<td><?php esc_html_e( 'Students who go to agencies usually waste 3–4 lakhs, and most of the time they get cheated.', 'aquariende' ); ?></td>
							<td class="compare-us"><?php esc_html_e( 'A convenient amount that students can afford easily.', 'aquariende' ); ?></td>
						</tr>
						<tr>
							<th scope="row"><?php esc_html_e( 'Individual service', 'aquariende' ); ?></th>
							<td><?php esc_html_e( 'They do not offer any individual service.', 'aquariende' ); ?></td>
							<td class="compare-us"><?php esc_html_e( 'Specific services: help until you get your offer letter, or help with the visa application.', 'aquariende' ); ?></td>
						</tr>
						<tr>
							<th scope="row"><?php esc_html_e( 'Transparency', 'aquariende' ); ?></th>
							<td><?php esc_html_e( 'Several students were tricked and lost their money.', 'aquariende' ); ?></td>
							<td class="compare-us"><?php esc_html_e( 'You track and monitor your admission activities directly. There are no iron curtains between you and us.', 'aquariende' ); ?></td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
	</section>

	<section class="team" id="team" aria-labelledby="team-title">
		<div class="wrap">
			<header class="section-head">
				<h2 id="team-title" class="section-title"><?php esc_html_e( 'Our talented team', 'aquariende' ); ?></h2>
				<p class="section-lede"><?php esc_html_e( 'All of our team members are students at highly regarded German institutions.', 'aquariende' ); ?></p>
			</header>
			<ul class="team-grid">
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

	<?php
	$aq_posts = new WP_Query(
		array(
			'posts_per_page'      => 3,
			'ignore_sticky_posts' => true,
			'no_found_rows'       => true,
		)
	);
	if ( $aq_posts->have_posts() ) :
		?>
		<section class="latest" aria-labelledby="latest-title">
			<div class="wrap">
				<header class="section-head section-head--row">
					<h2 id="latest-title" class="section-title"><?php esc_html_e( 'Our blogs', 'aquariende' ); ?></h2>
					<a class="link-arrow" href="<?php echo esc_url( aquariende_page_url( 'blog' ) ); ?>"><?php esc_html_e( 'All posts', 'aquariende' ); ?><?php echo aquariende_icon( 'arrow' ); // phpcs:ignore WordPress.Security.EscapeOutput ?></a>
				</header>
				<div class="post-grid">
					<?php
					while ( $aq_posts->have_posts() ) {
						$aq_posts->the_post();
						get_template_part( 'template-parts/post-card' );
					}
					wp_reset_postdata();
					?>
				</div>
			</div>
		</section>
	<?php endif; ?>

	<?php get_template_part( 'template-parts/cta' ); ?>

</main>
<?php
get_footer();
