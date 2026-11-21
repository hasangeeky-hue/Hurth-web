<?php
/**
 * Template Name: Student login
 *
 * /student-login/ (04 #13): the portal button and how to recognise the real
 * portal link. WhatsApp is secondary here.
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
		<section class="section-pad">
			<div class="wrap narrow">
				<p><a class="btn btn-primary btn-lg" href="<?php echo esc_url( aquariende_info( 'portal_url' ) ); ?>" data-portal="1"><?php echo aquariende_icon( 'lock' ); // phpcs:ignore WordPress.Security.EscapeOutput ?><span>Open the student portal</span></a></p>
				<div class="prose"><?php the_content(); ?></div>
				<p class="login-help">No portal link yet, or not sure a link is real? <a href="<?php echo esc_url( aquariende_whatsapp_url( aquariende_wa_context()['message'], aquariende_wa_context()['code'] ) ); ?>" target="_blank" rel="noopener" data-wa="<?php echo esc_attr( aquariende_wa_context()['code'] . ':inline' ); ?>">Ask us on WhatsApp<span class="screen-reader-text"> (opens WhatsApp)</span></a> or write to <?php echo aquariende_email_link(); // phpcs:ignore WordPress.Security.EscapeOutput ?>.</p>
				<?php get_template_part( 'template-parts/faq', null, array( 'key' => 'student-login' ) ); ?>
			</div>
		</section>
	<?php endwhile; ?>
</main>
<?php
get_footer();
