<?php
/**
 * Blog listing, archives, search, and template of last resort.
 *
 * @package Hurth
 */

get_header();

$hurth_q = ( 'en' === hurth_lang() ) ? '?lang=en' : '';
?>

<div class="page-hero">
	<div class="wrap">
		<h1>
			<?php
			if ( is_home() && ! is_front_page() ) {
				single_post_title();
			} elseif ( is_archive() ) {
				the_archive_title();
			} elseif ( is_search() ) {
				echo esc_html( hurth_t( 'search_for' ) ) . ' &ldquo;' . esc_html( get_search_query() ) . '&rdquo;';
			} else {
				echo esc_html( hurth_t( 'latest' ) );
			}
			?>
		</h1>
	</div>
</div>

<div class="section">
	<div class="wrap">
		<?php if ( have_posts() ) : ?>

			<div class="card-grid">
				<?php
				while ( have_posts() ) :
					the_post();
					?>
					<article <?php post_class( 'card' ); ?>>
						<span class="card__meta"><?php echo esc_html( get_the_date() ); ?></span>
						<h3>
							<a class="card__link" href="<?php echo esc_url( get_permalink() . $hurth_q ); ?>">
								<?php the_title(); ?>
							</a>
						</h3>
						<p><?php echo esc_html( wp_trim_words( wp_strip_all_tags( get_the_content() ), 26 ) ); ?></p>
					</article>
					<?php
				endwhile;
				?>
			</div>

			<?php
			the_posts_pagination( array(
				'class'     => 'pagination',
				'mid_size'  => 2,
				'prev_text' => '&larr;',
				'next_text' => '&rarr;',
			) );
			?>

		<?php else : ?>
			<div class="content-area">
				<p><?php echo esc_html( hurth_t( 'nothing' ) ); ?></p>
			</div>
		<?php endif; ?>
	</div>
</div>

<?php
get_footer();
