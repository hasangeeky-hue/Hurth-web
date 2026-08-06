<?php
/**
 * 404 template.
 *
 * @package Hurth
 */

get_header();

$hurth_q = ( 'en' === hurth_lang() ) ? '?lang=en' : '';
?>

<div class="page-hero">
	<div class="wrap">
		<h1><?php echo esc_html( hurth_t( 'notfound_h1' ) ); ?></h1>
		<p><?php echo esc_html( hurth_t( 'notfound_p' ) ); ?></p>
	</div>
</div>

<div class="section">
	<div class="wrap content-area">
		<p>
			<a class="btn" href="<?php echo esc_url( home_url( '/' ) . $hurth_q ); ?>">
				<?php echo esc_html( hurth_t( 'back_home' ) ); ?>
			</a>
		</p>
		<?php hurth_menu_fallback(); ?>
	</div>
</div>

<?php
// All four handsets, each draggable. Rendered on every template so no
// page shows a single model in isolation.
echo hurth_device_lineup( 'default' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
?>

<?php
get_footer();
