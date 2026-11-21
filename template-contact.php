<?php
/**
 * Template Name: Contact
 *
 * WhatsApp first, then email, the call-back form and the portal note.
 *
 * @package Aquariende
 */

get_header();
?>
<main id="main" class="site-main">
	<?php
	while ( have_posts() ) :
		the_post();
		get_template_part(
			'template-parts/page-head',
			null,
			array(
				'eyebrow' => __( 'Contact', 'aquariende' ),
				'title'   => __( 'Talk to a person on WhatsApp.', 'aquariende' ),
				'lede'    => __( 'Every conversation starts on WhatsApp. Write in English or Bengali; a consultant replies.', 'aquariende' ),
			)
		);
		?>

		<section class="section-pad">
			<div class="wrap contact-grid">
				<div class="contact-main">
					<div class="contact-wa">
						<p class="list-label"><?php esc_html_e( 'WhatsApp', 'aquariende' ); ?></p>
						<p class="contact-number"><?php echo esc_html( aquariende_info( 'whatsapp' ) ); ?></p>
						<a class="btn btn-primary btn-lg" href="<?php echo esc_url( aquariende_whatsapp_url() ); ?>" target="_blank" rel="noopener">
							<?php echo aquariende_icon( 'whatsapp' ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
							<span><?php esc_html_e( 'Message us on WhatsApp', 'aquariende' ); ?></span>
						</a>
						<p class="contact-small"><?php esc_html_e( 'This is a German number. WhatsApp messages to it cost the same as any other WhatsApp message.', 'aquariende' ); ?></p>
					</div>

					<ul class="contact-list">
						<li>
							<?php echo aquariende_icon( 'mail' ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
							<div><p class="list-label"><?php esc_html_e( 'Email', 'aquariende' ); ?></p><a href="mailto:<?php echo esc_attr( aquariende_info( 'email' ) ); ?>"><?php echo esc_html( aquariende_info( 'email' ) ); ?></a></div>
						</li>
						<?php if ( aquariende_info( 'facebook_group' ) ) : ?>
							<li>
								<?php echo aquariende_icon( 'facebook' ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
								<div><p class="list-label"><?php esc_html_e( 'Community', 'aquariende' ); ?></p><a href="<?php echo esc_url( aquariende_info( 'facebook_group' ) ); ?>" target="_blank" rel="noopener"><?php esc_html_e( 'Join the Facebook group', 'aquariende' ); ?></a></div>
							</li>
						<?php endif; ?>
						<?php if ( aquariende_show_address() ) : ?>
							<li>
								<?php echo aquariende_icon( 'pin' ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
								<div><p class="list-label"><?php esc_html_e( 'Office', 'aquariende' ); ?></p><p><?php echo esc_html( aquariende_info( 'address' ) ); ?></p></div>
							</li>
						<?php endif; ?>
						<li>
							<?php echo aquariende_icon( 'lock' ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
							<div>
								<p class="list-label"><?php esc_html_e( 'Already a client?', 'aquariende' ); ?></p>
								<a href="<?php echo esc_url( aquariende_info( 'portal_url' ) ); ?>"><?php esc_html_e( 'Student login', 'aquariende' ); ?></a>
								<p class="contact-small"><?php esc_html_e( 'Portal access is sent to you by email after the consultation fee is paid.', 'aquariende' ); ?></p>
							</div>
						</li>
					</ul>

					<h2 class="contact-subhead"><?php esc_html_e( 'What happens after your message', 'aquariende' ); ?></h2>
					<ol class="flow flow--light">
						<?php foreach ( aquariende_flow() as $aq_step ) : ?>
							<li><strong><?php echo esc_html( $aq_step['title'] ); ?></strong><span><?php echo esc_html( $aq_step['text'] ); ?></span></li>
						<?php endforeach; ?>
					</ol>
				</div>

				<div class="contact-side dark panel">
					<?php get_template_part( 'template-parts/callback-form' ); ?>
				</div>
			</div>
		</section>

		<?php if ( '' !== trim( get_the_content() ) ) : ?>
			<section class="section-pad section-pad--top0">
				<div class="wrap"><div class="prose"><?php the_content(); ?></div></div>
			</section>
		<?php endif; ?>

	<?php endwhile; ?>
</main>
<?php
get_footer();
