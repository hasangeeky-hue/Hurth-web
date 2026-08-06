<?php
/**
 * Booking page.
 *
 * The imported page carried 13 words and a dead Contact Form 7 block. This
 * replaces it with a three-step request form.
 *
 * Two behavioural reasons for the step structure (plan §9): a visible
 * progress indicator exploits the Zeigarnik effect — started tasks pull
 * toward completion — and the confirmation screen is the remembered moment
 * under the peak–end rule, so it is written warmly rather than as a bare
 * "thank you".
 *
 * Steps are client-side only; the form still submits and validates in one
 * POST, so it degrades to a plain long form without JavaScript.
 *
 * @package Hurth
 */

get_header();

$hurth_de = ( 'de' === hurth_lang() );
$hurth_q  = ( 'en' === hurth_lang() ) ? '?lang=en' : '';
$hurth_st = isset( $_GET['hcf'] ) ? sanitize_key( wp_unslash( $_GET['hcf'] ) ) : '';

$t = $hurth_de
	? array(
		'h1'      => 'Termin anfragen',
		'lead'    => 'Sagen Sie uns kurz, worum es geht. Sie bekommen eine Rückmeldung, keine automatische Bestätigung.',
		's1'      => 'Anliegen',
		's2'      => 'Gerät',
		's3'      => 'Kontakt',
		'service' => 'Worum geht es?',
		'device'  => 'Marke und Modell',
		'devph'   => 'z. B. iPhone 13, Samsung Galaxy A54',
		'issue'   => 'Was ist passiert?',
		'when'    => 'Wann passt es Ihnen ungefähr?',
		'name'    => 'Name',
		'email'   => 'E-Mail',
		'phone'   => 'Telefon',
		'consent' => 'Ich bin damit einverstanden, dass meine Angaben zur Bearbeitung meiner Anfrage verarbeitet werden.',
		'submit'  => 'Anfrage senden',
		'okh'     => 'Danke — wir haben Ihre Anfrage.',
		'okp'     => 'Wir melden uns zu den Öffnungszeiten bei Ihnen zurück und sagen Ihnen ehrlich, was möglich ist. Wenn es eilig ist, rufen Sie einfach an.',
		'badh'    => 'Das hat leider nicht geklappt.',
		'badp'    => 'Bitte rufen Sie uns kurz an — dann klären wir es direkt.',
		'inv'     => 'Bitte füllen Sie Name, E-Mail und Ihr Anliegen aus.',
		'nb'      => 'Ein Termin ist nicht zwingend nötig — Sie können auch einfach vorbeikommen.',
	)
	: array(
		'h1'      => 'Request an appointment',
		'lead'    => 'Tell us briefly what it is about. You will get a real reply, not an automated confirmation.',
		's1'      => 'Subject',
		's2'      => 'Device',
		's3'      => 'Contact',
		'service' => 'What is it about?',
		'device'  => 'Make and model',
		'devph'   => 'e.g. iPhone 13, Samsung Galaxy A54',
		'issue'   => 'What happened?',
		'when'    => 'Roughly when suits you?',
		'name'    => 'Name',
		'email'   => 'Email',
		'phone'   => 'Phone',
		'consent' => 'I consent to my details being processed in order to answer my enquiry.',
		'submit'  => 'Send request',
		'okh'     => 'Thank you — we have your request.',
		'okp'     => 'We will get back to you during opening hours and tell you honestly what is possible. If it is urgent, just call.',
		'badh'    => 'That did not go through.',
		'badp'    => 'Please give us a quick call and we will sort it directly.',
		'inv'     => 'Please complete name, email and your enquiry.',
		'nb'      => 'An appointment is not strictly necessary — you are welcome to just drop in.',
	);

$services = $hurth_de
	? array( 'Reparatur', 'Handy verkaufen', 'Neues Handy', 'Tarifberatung', 'Sonstiges' )
	: array( 'Repair', 'Sell a device', 'New phone', 'Tariff advice', 'Other' );
?>

<div class="page-hero page-hero--visual">
	<div class="wrap page-hero__split">
		<div>
			<ul class="breadcrumb">
				<li><a href="<?php echo esc_url( home_url( '/' ) . $hurth_q ); ?>"><?php echo esc_html( hurth_t( 'nav_home' ) ); ?></a></li>
				<li aria-hidden="true">/</li>
				<li><?php echo esc_html( $t['h1'] ); ?></li>
			</ul>
			<h1><?php echo esc_html( $t['h1'] ); ?></h1>
			<p><?php echo esc_html( $t['lead'] ); ?></p>
		</div>
		<div class="page-hero__media">
			<?php echo hurth_device3d( 'default', '', 'iphone' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
		</div>
	</div>
</div>

<div class="section">
	<div class="wrap">

	<?php if ( 'sent' === $hurth_st ) : ?>

		<div class="booking-done" role="status">
			<svg viewBox="0 0 512 512" aria-hidden="true" class="booking-done__tick">
				<path d="M504 256c0 137-111 248-248 248S8 393 8 256 119 8 256 8s248 111 248 248zM227 387l184-184c6-6 6-16 0-23l-22-22c-7-7-17-7-23 0L216 308l-70-70c-6-6-16-6-23 0l-22 23c-6 6-6 16 0 22l104 104c6 6 16 6 22 0z"/>
			</svg>
			<h2><?php echo esc_html( $t['okh'] ); ?></h2>
			<p><?php echo esc_html( $t['okp'] ); ?></p>
			<p class="booking-done__actions">
				<a class="btn btn--accent" href="tel:<?php echo esc_attr( hurth_info( 'phone_href' ) ); ?>">
					<?php echo esc_html( hurth_t( 'call' ) . ' · ' . hurth_info( 'phone' ) ); ?>
				</a>
				<a class="btn btn--ghost" href="<?php echo esc_url( hurth_info( 'maps' ) ); ?>" target="_blank" rel="noopener">
					<?php echo esc_html( hurth_t( 'route' ) ); ?>
				</a>
			</p>
		</div>

	<?php else : ?>

		<?php if ( 'failed' === $hurth_st ) : ?>
			<p class="form-notice form-notice--bad" role="alert">
				<strong><?php echo esc_html( $t['badh'] ); ?></strong> <?php echo esc_html( $t['badp'] ); ?>
			</p>
		<?php elseif ( 'invalid' === $hurth_st ) : ?>
			<p class="form-notice form-notice--bad" role="alert"><?php echo esc_html( $t['inv'] ); ?></p>
		<?php endif; ?>

		<div class="contact-form booking" id="kontakt">
			<ol class="steps" aria-hidden="true">
				<li class="is-active"><span>1</span><?php echo esc_html( $t['s1'] ); ?></li>
				<li><span>2</span><?php echo esc_html( $t['s2'] ); ?></li>
				<li><span>3</span><?php echo esc_html( $t['s3'] ); ?></li>
			</ol>

			<form method="post" action="">
				<?php wp_nonce_field( 'hurth_contact', 'hurth_contact_nonce' ); ?>
				<input type="hidden" name="hurth_opened" value="<?php echo esc_attr( time() ); ?>">

				<p class="hurth-hp" aria-hidden="true">
					<label>Website<input type="text" name="hurth_website" tabindex="-1" autocomplete="off"></label>
				</p>

				<fieldset class="step" data-step="1">
					<legend class="screen-reader-text"><?php echo esc_html( $t['s1'] ); ?></legend>
					<div class="form-row">
						<label for="b_service"><?php echo esc_html( $t['service'] ); ?></label>
						<select id="b_service" name="hurth_service">
							<?php foreach ( $services as $s ) : ?>
								<option value="<?php echo esc_attr( $s ); ?>"><?php echo esc_html( $s ); ?></option>
							<?php endforeach; ?>
						</select>
					</div>
				</fieldset>

				<fieldset class="step" data-step="2">
					<legend class="screen-reader-text"><?php echo esc_html( $t['s2'] ); ?></legend>
					<div class="form-row">
						<label for="b_device"><?php echo esc_html( $t['device'] ); ?></label>
						<input id="b_device" type="text" name="hurth_device"
							placeholder="<?php echo esc_attr( $t['devph'] ); ?>">
					</div>
					<div class="form-row">
						<label for="b_issue"><?php echo esc_html( $t['issue'] ); ?> *</label>
						<textarea id="b_issue" name="hurth_message" rows="5" required></textarea>
					</div>
					<div class="form-row">
						<label for="b_when"><?php echo esc_html( $t['when'] ); ?></label>
						<input id="b_when" type="text" name="hurth_when">
					</div>
				</fieldset>

				<fieldset class="step" data-step="3">
					<legend class="screen-reader-text"><?php echo esc_html( $t['s3'] ); ?></legend>
					<div class="form-row">
						<label for="b_name"><?php echo esc_html( $t['name'] ); ?> *</label>
						<input id="b_name" type="text" name="hurth_name" required autocomplete="name">
					</div>
					<div class="form-row">
						<label for="b_email"><?php echo esc_html( $t['email'] ); ?> *</label>
						<input id="b_email" type="email" name="hurth_email" required autocomplete="email">
					</div>
					<div class="form-row">
						<label for="b_phone"><?php echo esc_html( $t['phone'] ); ?></label>
						<input id="b_phone" type="tel" name="hurth_phone" autocomplete="tel">
					</div>
					<div class="form-row form-row--check">
						<label>
							<input type="checkbox" name="hurth_consent" required>
							<span><?php echo esc_html( $t['consent'] ); ?></span>
						</label>
					</div>
				</fieldset>

				<button class="btn btn--accent" type="submit" name="hurth_contact_submit" value="1">
					<?php echo esc_html( $t['submit'] ); ?>
				</button>
				<p class="booking__note"><?php echo esc_html( $t['nb'] ); ?></p>
			</form>
		</div>

	<?php endif; ?>

	</div>
</div>

<?php
get_footer();
