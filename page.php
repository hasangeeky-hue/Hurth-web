<?php
/**
 * Default page template (legal pages and anything else).
 *
 * @package Aquariende
 */

get_header();
?>
<main id="main" class="site-main">
	<?php
	while ( have_posts() ) :
		the_post();
		get_template_part( 'template-parts/page-head' );
		?>
		<article id="post-<?php the_ID(); ?>" <?php post_class( 'section-pad' ); ?>>
			<div class="wrap">
				<div class="prose">
					<?php
					the_content();
					wp_link_pages(
						array(
							'before' => '<nav class="page-links" aria-label="' . esc_attr__( 'Page', 'aquariende' ) . '">',
							'after'  => '</nav>',
						)
					);
					?>
				</div>
			</div>
		</article>
		<?php
	endwhile;
	?>
</main>
<?php
get_footer();
