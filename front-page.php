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
				<p class="trust-small"><?php esc_html_e( 'Started as a free Facebook support group when COVID-19 disrupted visas.', 'aquariende' ); ?></p>
			</li>
			<li class="trust-item">
				<p class="trust-big">
					<?php
					/* translators: %s: number of members. */
					echo esc_html( sprintf( __( '%s members', 'aquariende' ), aquariende_info( 'community' ) ) );
					?>
				</p>
				<p class="trust-small"><?php esc_html_e( 'in our study-in-Germany community on Facebook.', 'aquariende' ); ?></p>
			</li>
			<li class="trust-item">
				<p class="trust-big"><?php esc_html_e( 'Team in Germany', 'aquariende' ); ?></p>
				<p class="trust-small"><?php esc_html_e( 'Our consultants study at TUM, Bremen, Potsdam, HAW Hamburg and Schmalkalden.', 'aquariende' ); ?></p>
			</li>
		</ul>
	</section>

	<section class="hiw" id="how" aria-labelledby="how-title">
		<div class="wrap">
			<header class="section-head">
				<h2 id="how-title" class="section-title"><?php esc_html_e( 'Three stages. Take only the one you need.', 'aquariende' ); ?></h2>
				<p class="section-lede"><?php esc_html_e( 'Each stage is its own package, and your file shows exactly where you are in it.', 'aquariende' ); ?></p>
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
				<h2 id="packages-title" class="section-title"><?php esc_html_e( 'What each stage includes, and what it does not.', 'aquariende' ); ?></h2>
				<p class="section-lede"><?php esc_html_e( 'Ask us on WhatsApp for current prices. You pay per stage, so you never pay for a stage you do not need.', 'aquariende' ); ?></p>
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
				<h2 id="why-title" class="section-title"><?php esc_html_e( 'Built for students who were tired of agency games.', 'aquariende' ); ?></h2>
				<p class="section-lede"><?php esc_html_e( 'Many students in Bangladesh have paid an agency a lot and seen little of their own file. This is how we work instead.', 'aquariende' ); ?></p>
			</header>
			<div class="table-scroll" tabindex="0" role="region" aria-label="<?php esc_attr_e( 'Comparison, scrolls sideways', 'aquariende' ); ?>">
				<table class="compare">
					<thead>
						<tr>
							<th scope="col"><span class="screen-reader-text"><?php esc_html_e( 'Topic', 'aquariende' ); ?></span></th>
							<th scope="col"><?php esc_html_e( 'A typical agency', 'aquariende' ); ?></th>
							<th scope="col" class="compare-us"><?php esc_html_e( 'Aquariende', 'aquariende' ); ?></th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<th scope="row"><?php esc_html_e( 'Your file', 'aquariende' ); ?></th>
							<td><?php esc_html_e( 'Kept in their office. You hear news when you ask.', 'aquariende' ); ?></td>
							<td class="compare-us"><?php esc_html_e( 'In your own portal, with a step tracker you can open any time.', 'aquariende' ); ?></td>
						</tr>
						<tr>
							<th scope="row"><?php esc_html_e( 'What you pay for', 'aquariende' ); ?></th>
							<td><?php esc_html_e( 'One large bundle, paid up front.', 'aquariende' ); ?></td>
							<td class="compare-us"><?php esc_html_e( 'Three separate stages. Take only the one you need.', 'aquariende' ); ?></td>
						</tr>
						<tr>
							<th scope="row"><?php esc_html_e( 'Who advises you', 'aquariende' ); ?></th>
							<td><?php esc_html_e( 'Counsellors in Bangladesh.', 'aquariende' ); ?></td>
							<td class="compare-us"><?php esc_html_e( 'Students at German universities, going through the same system.', 'aquariende' ); ?></td>
						</tr>
						<tr>
							<th scope="row"><?php esc_html_e( 'Where it happens', 'aquariende' ); ?></th>
							<td><?php esc_html_e( 'At their office, in person.', 'aquariende' ); ?></td>
							<td class="compare-us"><?php esc_html_e( 'Online, in video consultations, from wherever you are.', 'aquariende' ); ?></td>
						</tr>
						<tr>
							<th scope="row"><?php esc_html_e( 'The visa', 'aquariende' ); ?></th>
							<td><?php esc_html_e( 'Sometimes "guaranteed".', 'aquariende' ); ?></td>
							<td class="compare-us"><?php esc_html_e( 'No one can guarantee a visa. We prepare it properly.', 'aquariende' ); ?></td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
	</section>

	<section class="team" id="team" aria-labelledby="team-title">
		<div class="wrap">
			<header class="section-head">
				<h2 id="team-title" class="section-title"><?php esc_html_e( 'Advice from people studying in Germany.', 'aquariende' ); ?></h2>
				<p class="section-lede"><?php esc_html_e( 'Our consultants are students at German universities, so the advice comes from people who went through the same applications.', 'aquariende' ); ?></p>
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
					<h2 id="latest-title" class="section-title"><?php esc_html_e( 'From the blog', 'aquariende' ); ?></h2>
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
