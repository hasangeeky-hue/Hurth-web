<?php
/**
 * Not found.
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
			'eyebrow' => __( 'Error 404', 'aquariende' ),
			'title'   => __( 'This page is not in the file.', 'aquariende' ),
			'lede'    => __( 'The link may be old: the site was rebuilt and some pages were removed. Try a search or one of the pages below.', 'aquariende' ),
		)
	);
	?>
	<section class="section-pad">
		<div class="wrap narrow">
			<?php get_search_form(); ?>
			<ul class="link-list">
				<?php foreach ( aquariende_default_nav() as $aq_label => $aq_url ) : ?>
					<li><a class="link-arrow" href="<?php echo esc_url( $aq_url ); ?>"><?php echo esc_html( $aq_label ); ?><?php echo aquariende_icon( 'arrow' ); // phpcs:ignore WordPress.Security.EscapeOutput ?></a></li>
				<?php endforeach; ?>
				<li><a class="link-arrow" href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Home', 'aquariende' ); ?><?php echo aquariende_icon( 'arrow' ); // phpcs:ignore WordPress.Security.EscapeOutput ?></a></li>
			</ul>
		</div>
	</section>
</main>
<?php
get_footer();
