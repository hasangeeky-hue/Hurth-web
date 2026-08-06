<?php
/**
 * Standard page template.
 *
 * Pages that describe a device or a fault carry an interactive 3D model in
 * the header, chosen to match the subject — a cracked panel on the screen
 * page, a drained cell on the battery page. Pages that are read rather than
 * looked at (About, FAQ, legal) get no visual, so the text starts higher.
 *
 * @package Hurth
 */

get_header();

$hurth_q      = ( 'en' === hurth_lang() ) ? '?lang=en' : '';
$hurth_slug   = get_post_field( 'post_name', get_queried_object_id() );
$hurth_visual = hurth_page_visual( $hurth_slug );
?>

<div class="page-hero <?php echo $hurth_visual ? 'page-hero--visual' : ''; ?>">
	<div class="wrap <?php echo $hurth_visual ? 'page-hero__split' : ''; ?>">
		<div>
			<ul class="breadcrumb">
				<li><a href="<?php echo esc_url( home_url( '/' ) . $hurth_q ); ?>"><?php echo esc_html( hurth_t( 'nav_home' ) ); ?></a></li>
				<li aria-hidden="true">/</li>
				<li><?php the_title(); ?></li>
			</ul>
			<h1><?php the_title(); ?></h1>
			<?php if ( has_excerpt() ) : ?>
				<p><?php echo esc_html( get_the_excerpt() ); ?></p>
			<?php endif; ?>

			<?php if ( $hurth_visual ) : ?>
				<div class="page-hero__actions">
					<a class="btn btn--accent" href="tel:<?php echo esc_attr( hurth_info( 'phone_href' ) ); ?>">
						<?php echo esc_html( hurth_t( 'call' ) ); ?>
					</a>
					<?php
					$hurth_book = get_page_by_path( 'book-an-appointment' );
					if ( $hurth_book ) :
						?>
						<a class="btn btn--ghost" href="<?php echo esc_url( get_permalink( $hurth_book ) . $hurth_q ); ?>">
							<?php echo esc_html( 'de' === hurth_lang() ? 'Termin anfragen' : 'Request appointment' ); ?>
						</a>
					<?php endif; ?>
				</div>
			<?php endif; ?>
		</div>

		<?php if ( $hurth_visual ) : ?>
			<div class="page-hero__media">
				<?php echo hurth_device3d( $hurth_visual ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			</div>
		<?php endif; ?>
	</div>
</div>

<div class="section">
	<div class="wrap">
		<?php
		while ( have_posts() ) :
			the_post();
			?>
			<article <?php post_class( 'content-area content-area--wide' ); ?>>
				<?php
				the_content();

				wp_link_pages( array(
					'before' => '<div class="pagination">',
					'after'  => '</div>',
				) );
				?>
			</article>
			<?php
		endwhile;
		?>
	</div>
</div>

<?php
get_footer();
