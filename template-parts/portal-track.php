<?php
/**
 * Two things students get with every file: one-to-one consultations and their own portal to follow the file.
 * The picture is an example screen of the student portal with sample data.
 *
 * Args: heading (h2|h3).
 *
 * @package Aquariende
 */

$aq_login = aquariende_page_url( 'student-login' );
?>
<section class="rt-track" aria-labelledby="track-title">
	<div class="wrap">
		<div class="rt-two rt-track-grid">
			<div class="rt-track-copy">
				<h2 id="track-title" class="rt-h2">Follow every application from home</h2>
				<p class="rt-body">We built our own platform so that you can track and monitor your admission yourself. Your file is processed in front of you: you open your student portal on your phone or computer and see where each application stands.</p>
				<p class="list-label">What you see in your portal</p>
				<ul class="checklist rt-track-list" data-stagger>
					<?php foreach ( aquariende_portal_shows() as $aq_x ) : ?>
						<li><?php echo aquariende_icon( 'check' ); // phpcs:ignore WordPress.Security.EscapeOutput ?><span><?php echo esc_html( $aq_x ); ?></span></li>
					<?php endforeach; ?>
				</ul>
				<p class="rt-track-actions">
					<?php if ( $aq_login ) : ?>
						<a class="btn btn-outline" href="<?php echo esc_url( $aq_login ); ?>"><?php echo aquariende_icon( 'lock' ); // phpcs:ignore WordPress.Security.EscapeOutput ?><span>Student login</span></a>
					<?php endif; ?>
					<span class="rt-track-note">You receive your personal portal link by email after you pay the consultation fee. Nothing is paid on this website.</span>
				</p>
			</div>
			<figure class="rt-track-shot" data-tilt data-reveal>
				<?php
				echo aquariende_picture( // phpcs:ignore WordPress.Security.EscapeOutput -- escaped inside.
					'portal-dashboard',
					'Example of the student portal: the journey in four parts, a list of applications with deadlines and status, missing documents and the next step.',
					array(
						'width'  => 1200,
						'height' => 820,
					)
				);
				?>
				<figcaption>Example screen of the student portal, with sample data.</figcaption>
			</figure>
		</div>

		<div class="rt-one2one" data-reveal>
			<?php echo aquariende_art( 'chat', 'rt-one2one-art' ); // phpcs:ignore WordPress.Security.EscapeOutput -- fixed SVG. ?>
			<div>
				<h3 class="rt-h3">One-to-one consultation</h3>
				<p>You talk to a consultant who studies in Germany, on a video call, about your own documents: 30 minutes in the profile check, four consultations in the university application and three in the visa application. Management assigns one consultant to your file.</p>
			</div>
			<?php echo aquariende_wa_button( 'one2one', array( 'class' => 'btn btn-primary' ) ); // phpcs:ignore WordPress.Security.EscapeOutput -- escaped inside. ?>
		</div>
	</div>
</section>
