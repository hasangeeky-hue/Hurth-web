<?php
/**
 * Standard page template.
 *
 * Page content is stored as plain HTML in post_content, so the_content()
 * renders it directly — no page builder required.
 *
 * @package Hurth
 */

get_header();
?>

<div class="page-hero">
	<div class="wrap">
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
