<?php
/**
 * VerifyStrip: facts a stranger can check (02 Part B #1, #7).
 * Sources: [OLD About/Home], [BF], owner rule.
 *
 * @package Aquariende
 */

?>
<section class="verify" aria-labelledby="verify-title">
	<div class="wrap">
		<h2 id="verify-title" class="screen-reader-text">Who we are, in three facts</h2>
		<ul class="verify-list">
			<li class="verify-item">
				<p class="verify-big">A team in Germany</p>
				<p class="verify-small">Our team members went to Germany to study at TUM, HAW Hamburg and the universities of Bremen, Potsdam and Schmalkalden. We call you from Germany.</p>
			</li>
			<li class="verify-item">
				<p class="verify-big">Since 2020, free help first</p>
				<p class="verify-small">We started in 2020 as a free Facebook group for anyone who wanted to study abroad. <a href="<?php echo esc_url( aquariende_page_url( 'about' ) ); ?>">Read our story</a></p>
			</li>
			<li class="verify-item">
				<p class="verify-big">We never…</p>
				<ul class="never-mini">
					<li><?php echo aquariende_icon( 'cross' ); // phpcs:ignore WordPress.Security.EscapeOutput ?><span>promise a visa</span></li>
					<li><?php echo aquariende_icon( 'cross' ); // phpcs:ignore WordPress.Security.EscapeOutput ?><span>take payment on this website</span></li>
					<li><?php echo aquariende_icon( 'cross' ); // phpcs:ignore WordPress.Security.EscapeOutput ?><span>claim to represent a university or the embassy</span></li>
				</ul>
			</li>
		</ul>
	</div>
</section>
