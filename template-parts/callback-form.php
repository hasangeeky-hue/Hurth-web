<?php
/**
 * Optional call-back form: name and WhatsApp number only.
 * Posts to admin-post.php; main.js upgrades it to fetch() with a spinner.
 *
 * @package Aquariende
 */

$aq_state = isset( $_GET['callback'] ) ? sanitize_key( wp_unslash( $_GET['callback'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification -- display-only flag.
$aq_msgs  = array(
	'sent'    => __( 'Sent. We reply on WhatsApp within one working day.', 'aquariende' ),
	'invalid' => __( 'Please check your name and WhatsApp number and try again.', 'aquariende' ),
	'failed'  => __( 'That did not go through. Please message us on WhatsApp instead.', 'aquariende' ),
);
$aq_uid   = wp_unique_id( 'cb-' );
?>
<div class="callback" id="callback">
	<h3 class="callback-title"><?php esc_html_e( 'Prefer that we message you?', 'aquariende' ); ?></h3>
	<p class="callback-intro"><?php esc_html_e( 'Leave your name and WhatsApp number. We reply on WhatsApp within one working day.', 'aquariende' ); ?></p>

	<form class="callback-form<?php echo 'sent' === $aq_state ? ' is-sent' : ''; ?>" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" method="post" novalidate>
		<input type="hidden" name="action" value="aquariende_callback">
		<input type="hidden" name="aq_ts" value="<?php echo esc_attr( time() ); ?>">
		<input type="hidden" name="aq_page" value="<?php echo esc_url( home_url( add_query_arg( array() ) ) ); ?>">
		<?php wp_nonce_field( 'aquariende_callback', 'aq_nonce', false ); ?>

		<div class="field">
			<label for="<?php echo esc_attr( $aq_uid ); ?>-name"><?php esc_html_e( 'Your name', 'aquariende' ); ?></label>
			<input id="<?php echo esc_attr( $aq_uid ); ?>-name" name="aq_name" type="text" autocomplete="name" required minlength="2" maxlength="80" aria-describedby="<?php echo esc_attr( $aq_uid ); ?>-name-err">
			<p class="field-error" id="<?php echo esc_attr( $aq_uid ); ?>-name-err"></p>
		</div>

		<div class="field">
			<label for="<?php echo esc_attr( $aq_uid ); ?>-phone"><?php esc_html_e( 'WhatsApp number', 'aquariende' ); ?></label>
			<input id="<?php echo esc_attr( $aq_uid ); ?>-phone" name="aq_phone" type="tel" inputmode="tel" autocomplete="tel" required placeholder="+880 1712 345678" aria-describedby="<?php echo esc_attr( $aq_uid ); ?>-phone-hint <?php echo esc_attr( $aq_uid ); ?>-phone-err">
			<p class="field-hint" id="<?php echo esc_attr( $aq_uid ); ?>-phone-hint"><?php esc_html_e( 'Include the country code, for example +880.', 'aquariende' ); ?></p>
			<p class="field-error" id="<?php echo esc_attr( $aq_uid ); ?>-phone-err"></p>
		</div>

		<div class="hp" aria-hidden="true">
			<label for="<?php echo esc_attr( $aq_uid ); ?>-web"><?php esc_html_e( 'Leave this empty', 'aquariende' ); ?></label>
			<input id="<?php echo esc_attr( $aq_uid ); ?>-web" name="aq_website" type="text" tabindex="-1" autocomplete="off">
		</div>

		<button class="btn btn-light btn-block" type="submit">
			<span class="spinner" aria-hidden="true"></span>
			<span class="btn-label"><?php esc_html_e( 'Ask us to message you', 'aquariende' ); ?></span>
		</button>

		<p class="form-status<?php echo ( $aq_state && isset( $aq_msgs[ $aq_state ] ) ) ? ' is-' . esc_attr( $aq_state ) : ''; ?>" role="status" aria-live="polite"><?php echo isset( $aq_msgs[ $aq_state ] ) ? esc_html( $aq_msgs[ $aq_state ] ) : ''; ?></p>
		<p class="form-note"><?php esc_html_e( 'We use your number only to reply to you. Nothing is uploaded or paid on this website.', 'aquariende' ); ?></p>
	</form>
</div>
