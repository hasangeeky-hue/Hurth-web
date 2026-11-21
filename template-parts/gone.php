<?php
/**
 * Body of the 410 Gone response for removed shop and account pages.
 *
 * @package Aquariende
 */

get_header();
?>
<main id="main" class="site-main">
	<?php
	get_template_part(
		'template-parts/page-head',
		null,
		array(
			'title'  => 'This page was removed',
			'answer' => '',
			'lede'   => 'The old shop and account pages no longer exist. Nothing is sold or paid on this website.',
		)
	);
	?>
	<section class="section-pad">
		<div class="wrap narrow">
			<ul class="link-list">
				<li><a class="link-arrow" href="<?php echo esc_url( aquariende_page_url( 'services' ) ); ?>">Our three services<?php echo aquariende_icon( 'arrow' ); // phpcs:ignore WordPress.Security.EscapeOutput ?></a></li>
				<li><a class="link-arrow" href="<?php echo esc_url( aquariende_page_url( 'student-login' ) ); ?>">Student login<?php echo aquariende_icon( 'arrow' ); // phpcs:ignore WordPress.Security.EscapeOutput ?></a></li>
				<li><a class="link-arrow" href="<?php echo esc_url( home_url( '/' ) ); ?>">Home<?php echo aquariende_icon( 'arrow' ); // phpcs:ignore WordPress.Security.EscapeOutput ?></a></li>
			</ul>
		</div>
	</section>
</main>
<?php
get_footer();
