<?php
/**
 * Template Name: Contact
 *
 * /contact/ (04 #12): WhatsApp (big), email, HaveTheseReady, WhatHappensNext.
 * No form: WhatsApp and email are the only channels (owner rule).
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
			<div class="wrap contact-grid">
				<div class="contact-main">
					<div class="contact-wa">
						<p class="list-label">WhatsApp</p>
						<p class="contact-number"><?php echo esc_html( aquariende_info( 'whatsapp' ) ); ?></p>
						<?php echo aquariende_wa_button( 'inline', array( 'class' => 'btn btn-primary btn-lg' ) ); // phpcs:ignore WordPress.Security.EscapeOutput -- escaped inside. ?>
						<p class="contact-small">This is a German WhatsApp number. A team member calls you from Germany within 24 hours. English or Bengali.</p>
					</div>
					<div class="contact-mail">
						<p class="list-label">Email</p>
						<p class="contact-email"><?php echo aquariende_email_link(); // phpcs:ignore WordPress.Security.EscapeOutput ?></p>
					</div>
					<?php if ( '' !== trim( get_the_content() ) ) : ?>
						<div class="prose"><?php the_content(); ?></div>
					<?php endif; ?>
					<p class="note"><?php echo aquariende_icon( 'info' ); // phpcs:ignore WordPress.Security.EscapeOutput ?><span>We never ask for payment on this website. The consultation fee is explained on WhatsApp before you pay, and your portal link comes by email afterwards.</span></p>
				</div>
				<div class="contact-side">
					<?php get_template_part( 'template-parts/have-these-ready', null, array( 'heading' => 'h2' ) ); ?>
				</div>
			</div>
		</section>
		<section class="section-pad tint">
			<div class="wrap narrow">
				<?php get_template_part( 'template-parts/what-happens-next', null, array( 'heading' => 'h2' ) ); ?>
				<?php get_template_part( 'template-parts/faq', null, array( 'key' => 'contact' ) ); ?>
			</div>
		</section>
	<?php endwhile; ?>
</main>
<?php
get_footer();
