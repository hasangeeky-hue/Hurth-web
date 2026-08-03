<?php
/**
 * Front page template.
 *
 * Renders the assigned front page's own content, then a card grid linking to
 * the main service pages and the latest posts.
 *
 * @package Hurth
 */

get_header();

$hurth_front_id = (int) get_option( 'page_on_front' );
?>

<section class="hero">
	<div class="wrap hero__inner">
		<div>
			<span class="hero__eyebrow"><?php echo esc_html( hurth_info( 'region' ) ); ?></span>
			<h1><?php echo esc_html( hurth_info( 'name' ) ); ?></h1>
			<p class="hero__lead">
				<?php esc_html_e( 'Smartphones, repairs and DHL services in one place.', 'hurth' ); ?>
			</p>
			<div class="hero__actions">
				<?php
				$hurth_book = get_page_by_path( 'book-an-appointment' );
				if ( $hurth_book ) :
					?>
					<a class="btn btn--accent" href="<?php echo esc_url( get_permalink( $hurth_book ) ); ?>">
						<?php esc_html_e( 'Book an appointment', 'hurth' ); ?>
					</a>
				<?php endif; ?>

				<?php
				$hurth_contact = get_page_by_path( 'contact' );
				if ( $hurth_contact ) :
					?>
					<a class="btn btn--ghost" href="<?php echo esc_url( get_permalink( $hurth_contact ) ); ?>">
						<?php esc_html_e( 'Contact us', 'hurth' ); ?>
					</a>
				<?php endif; ?>
			</div>
		</div>

		<div class="hero__media">
			<?php
			if ( $hurth_front_id && has_post_thumbnail( $hurth_front_id ) ) {
				echo get_the_post_thumbnail( $hurth_front_id, 'large' );
			}
			?>
		</div>
	</div>
</section>

<?php
// The front page's own content, exactly as stored.
if ( have_posts() ) :
	?>
	<div class="section">
		<div class="wrap">
			<?php
			while ( have_posts() ) :
				the_post();
				?>
				<article <?php post_class( 'content-area content-area--wide' ); ?>>
					<?php the_content(); ?>
				</article>
				<?php
			endwhile;
			?>
		</div>
	</div>
	<?php
endif;

// Service pages, in the order defined by hurth_nav_items() and skipping any
// page that is effectively empty (the imported "Blog" page has no content).
$hurth_pages = array();

foreach ( array_keys( hurth_nav_items() ) as $hurth_slug ) {
	$hurth_page = get_page_by_path( $hurth_slug );

	if ( $hurth_page && 'publish' === $hurth_page->post_status
		&& (int) $hurth_page->ID !== $hurth_front_id
		&& hurth_page_has_content( $hurth_page ) ) {
		$hurth_pages[] = $hurth_page;
	}
}

if ( $hurth_pages ) :
	?>
	<section class="section section--alt">
		<div class="wrap">
			<h2 class="text-center"><?php esc_html_e( 'What we do', 'hurth' ); ?></h2>
			<div class="card-grid">
				<?php foreach ( $hurth_pages as $hurth_page ) : ?>
					<article class="card">
						<h3>
							<a href="<?php echo esc_url( get_permalink( $hurth_page ) ); ?>">
								<?php echo esc_html( get_the_title( $hurth_page ) ); ?>
							</a>
						</h3>
						<p><?php echo esc_html( wp_trim_words( wp_strip_all_tags( $hurth_page->post_content ), 22 ) ); ?></p>
					</article>
				<?php endforeach; ?>
			</div>
		</div>
	</section>
	<?php
endif;

// Latest posts.
$hurth_posts = get_posts( array( 'numberposts' => 3 ) );

if ( $hurth_posts ) :
	?>
	<section class="section">
		<div class="wrap">
			<h2 class="text-center"><?php esc_html_e( 'From the blog', 'hurth' ); ?></h2>
			<div class="card-grid">
				<?php foreach ( $hurth_posts as $hurth_post ) : ?>
					<article class="card">
						<span class="card__meta">
							<?php echo esc_html( get_the_date( '', $hurth_post ) ); ?>
						</span>
						<h3>
							<a href="<?php echo esc_url( get_permalink( $hurth_post ) ); ?>">
								<?php echo esc_html( get_the_title( $hurth_post ) ); ?>
							</a>
						</h3>
						<p><?php echo esc_html( wp_trim_words( wp_strip_all_tags( $hurth_post->post_content ), 22 ) ); ?></p>
					</article>
				<?php endforeach; ?>
			</div>
		</div>
	</section>
	<?php
endif;

get_footer();
