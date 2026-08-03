<?php
/**
 * Single post template.
 *
 * @package Hurth
 */

get_header();
?>

<div class="page-hero">
	<div class="wrap">
		<h1><?php the_title(); ?></h1>
	</div>
</div>

<div class="section">
	<div class="wrap">
		<?php
		while ( have_posts() ) :
			the_post();
			?>
			<article <?php post_class( 'content-area' ); ?>>

				<p class="post-meta">
					<time datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>">
						<?php echo esc_html( get_the_date() ); ?>
					</time>
				</p>

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
