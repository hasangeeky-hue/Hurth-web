<?php
/**
 * Single post.
 *
 * Posts carry an interactive 3D model matched to the subject, the same way
 * service pages do — a cracked panel on the screen article, a drained cell
 * on the battery one. Previously they had no visual at all.
 *
 * @package Hurth
 */

get_header();

$hurth_q      = ( 'en' === hurth_lang() ) ? '?lang=en' : '';
$hurth_slug   = get_post_field( 'post_name', get_queried_object_id() );
$hurth_visual = hurth_page_visual( $hurth_slug );
?>

<div class="page-hero page-hero--visual">
	<div class="wrap page-hero__split">
		<div>
			<ul class="breadcrumb">
				<li><a href="<?php echo esc_url( home_url( '/' ) . $hurth_q ); ?>"><?php echo esc_html( hurth_t( 'nav_home' ) ); ?></a></li>
				<li aria-hidden="true">/</li>
				<?php
				$hurth_blog = get_page_by_path( 'blog' );
				if ( $hurth_blog && 'publish' === $hurth_blog->post_status ) :
					?>
					<li><a href="<?php echo esc_url( get_permalink( $hurth_blog ) . $hurth_q ); ?>"><?php echo esc_html( hurth_t( 'nav_blog' ) ); ?></a></li>
					<li aria-hidden="true">/</li>
				<?php endif; ?>
				<li><time datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>"><?php echo esc_html( get_the_date() ); ?></time></li>
			</ul>
			<h1><?php the_title(); ?></h1>
			<?php if ( has_excerpt() ) : ?>
				<p><?php echo esc_html( get_the_excerpt() ); ?></p>
			<?php endif; ?>

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
		</div>

		<div class="page-hero__media">
			<?php echo hurth_device3d( $hurth_visual[0], '', $hurth_visual[1] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
		</div>
	</div>
</div>

<div class="section">
	<div class="wrap">
		<?php
		while ( have_posts() ) :
			the_post();
			?>
			<article <?php post_class( 'content-area' ); ?>>
				<?php
				if ( has_post_thumbnail() ) {
					the_post_thumbnail( 'large' );
				}

				the_content();
				?>
			</article>

			<nav class="pagination" aria-label="<?php esc_attr_e( 'Posts', 'hurth' ); ?>">
				<?php
				$hurth_prev = get_previous_post_link( '%link', '&larr; %title' );
				$hurth_next = get_next_post_link( '%link', '%title &rarr;' );

				if ( $hurth_prev ) {
					echo '<span class="page-numbers">' . wp_kses_post( $hurth_prev ) . '</span>';
				}
				if ( $hurth_next ) {
					echo '<span class="page-numbers">' . wp_kses_post( $hurth_next ) . '</span>';
				}
				?>
			</nav>
			<?php
		endwhile;
		?>
	</div>
</div>

<?php
get_footer();
