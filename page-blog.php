<?php
/**
 * Blog listing.
 *
 * Applied automatically to the page with slug "blog", which was imported
 * with no content. Lists posts without needing Settings → Reading changed,
 * so the four articles have a home and a navigation entry.
 *
 * @package Hurth
 */

get_header();

$hurth_q  = ( 'en' === hurth_lang() ) ? '?lang=en' : '';
$hurth_de = ( 'de' === hurth_lang() );
$hurth_pg = max( 1, (int) get_query_var( 'paged' ) ?: (int) get_query_var( 'page' ) );

$hurth_posts = new WP_Query( array(
	'post_type'      => 'post',
	'post_status'    => 'publish',
	'posts_per_page' => 9,
	'paged'          => $hurth_pg,
) );
?>

<div class="page-hero page-hero--visual">
	<div class="wrap page-hero__split">
		<div>
			<ul class="breadcrumb">
				<li><a href="<?php echo esc_url( home_url( '/' ) . $hurth_q ); ?>"><?php echo esc_html( hurth_t( 'nav_home' ) ); ?></a></li>
				<li aria-hidden="true">/</li>
				<li><?php the_title(); ?></li>
			</ul>
			<h1><?php the_title(); ?></h1>
			<p>
				<?php
				echo esc_html(
					$hurth_de
						? 'Tipps und ehrliche Einschätzungen rund um Reparatur, Ankauf und Tarife.'
						: 'Practical advice on repairs, selling your device and choosing a tariff.'
				);
				?>
			</p>
		</div>
		<div class="page-hero__media">
			<?php echo hurth_device3d( 'new', '', 'pixel' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
		</div>
	</div>
</div>

<div class="section">
	<div class="wrap">
		<?php if ( $hurth_posts->have_posts() ) : ?>

			<div class="card-grid">
				<?php
				while ( $hurth_posts->have_posts() ) :
					$hurth_posts->the_post();
					?>
					<article <?php post_class( 'card' ); ?>>
						<span class="card__meta"><?php echo esc_html( get_the_date() ); ?></span>
						<h2 class="card__title">
							<a class="card__link" href="<?php echo esc_url( get_permalink() . $hurth_q ); ?>">
								<?php the_title(); ?>
							</a>
						</h2>
						<p><?php echo esc_html( wp_trim_words( wp_strip_all_tags( get_the_excerpt() ), 26 ) ); ?></p>
					</article>
					<?php
				endwhile;
				?>
			</div>

			<?php
			$hurth_links = paginate_links( array(
				'total'     => $hurth_posts->max_num_pages,
				'current'   => $hurth_pg,
				'prev_text' => '&larr;',
				'next_text' => '&rarr;',
				'type'      => 'array',
			) );

			if ( $hurth_links ) {
				echo '<nav class="pagination" aria-label="' . esc_attr( $hurth_de ? 'Seiten' : 'Pages' ) . '">';
				echo wp_kses_post( implode( '', $hurth_links ) );
				echo '</nav>';
			}
			?>

		<?php else : ?>
			<div class="content-area">
				<p><?php echo esc_html( hurth_t( 'nothing' ) ); ?></p>
			</div>
		<?php endif; ?>

		<?php wp_reset_postdata(); ?>
	</div>
</div>

<?php
get_footer();
