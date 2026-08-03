<?php
/**
 * 404 template.
 *
 * @package Hurth
 */

get_header();
?>

<div class="page-hero">
	<div class="wrap">
		<h1><?php esc_html_e( 'Page not found', 'hurth' ); ?></h1>
		<p><?php esc_html_e( 'That page does not exist or has moved.', 'hurth' ); ?></p>
	</div>
</div>

<div class="section">
	<div class="wrap content-area">
		<p>
			<a class="btn" href="<?php echo esc_url( home_url( '/' ) ); ?>">
				<?php esc_html_e( 'Back to home', 'hurth' ); ?>
			</a>
		</p>

		<h2><?php esc_html_e( 'Looking for one of these?', 'hurth' ); ?></h2>
		<?php hurth_menu_fallback(); ?>
	</div>
</div>

<?php
get_footer();
