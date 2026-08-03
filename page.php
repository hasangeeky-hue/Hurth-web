<?php
/**
 * Standard page template.
 *
 * @package Hurth
 */

get_header();

$hurth_q = ( 'en' === hurth_lang() ) ? '?lang=en' : '';
?>

<div class="page-hero">
	<div class="wrap">
		<ul class="breadcrumb">
			<li><a href="<?php echo esc_url( home_url( '/' ) . $hurth_q ); ?>"><?php echo esc_html( hurth_t( 'nav_home' ) ); ?></a></li>
			<li aria-hidden="true">/</li>
			<li><?php the_title(); ?></li>
		</ul>
		<h1><?php the_title(); ?></h1>
		<?php if ( has_excerpt() ) : ?>
			<p><?php echo esc_html( get_the_excerpt() ); ?></p>
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
