<?php
/**
 * Front page.
 *
 * Order follows the conversion path: who/what → why trust → the page's own
 * content → services → advice → action.
 *
 * @package Hurth
 */

get_header();

$hurth_front_id = (int) get_option( 'page_on_front' );
$hurth_q        = ( 'en' === hurth_lang() ) ? '?lang=en' : '';
$hurth_tick     = '<svg viewBox="0 0 512 512" aria-hidden="true"><path d="M504 256c0 137-111 248-248 248S8 393 8 256 119 8 256 8s248 111 248 248zM227 387l184-184c6-6 6-16 0-23l-22-22c-7-7-17-7-23 0L216 308l-70-70c-6-6-16-6-23 0l-22 23c-6 6-6 16 0 22l104 104c6 6 16 6 22 0z"/></svg>';
?>

<section class="hero">
	<span class="hero__grid" aria-hidden="true"></span>
	<div class="wrap hero__inner">
		<div>
			<span class="hero__eyebrow"><?php echo esc_html( hurth_t( 'hero_eyebrow' ) ); ?></span>
			<h1><?php echo esc_html( hurth_t( 'hero_h1' ) ); ?></h1>
			<p class="lead"><?php echo esc_html( hurth_t( 'hero_lead' ) ); ?></p>

			<ul class="hero__points">
				<?php
				foreach ( array( 'point_local', 'point_since', 'point_brands', 'point_data' ) as $hurth_pt ) {
					echo '<li>' . $hurth_tick . '<span>' . esc_html( hurth_t( $hurth_pt ) ) . '</span></li>';
				}
				?>
			</ul>

			<div class="hero__actions">
				<a class="btn btn--accent" href="tel:<?php echo esc_attr( hurth_info( 'phone_href' ) ); ?>">
					<?php echo esc_html( hurth_t( 'call' ) . ' · ' . hurth_info( 'phone' ) ); ?>
				</a>
				<a class="btn btn--ghost" href="<?php echo esc_url( hurth_info( 'maps' ) ); ?>"
					target="_blank" rel="noopener">
					<?php echo esc_html( hurth_t( 'route' ) ); ?>
				</a>
			</div>
		</div>

		<div class="hero__media">
			<?php
			/*
			 * The interactive 3D device leads, because an interactive element
			 * was asked for and the frame-sequence viewer renders nothing
			 * without a turntable shoot. The workbench photo sits beneath it
			 * so the page still shows real work, in colour.
			 */
			echo hurth_device3d( 'default', '', 'iphone' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			?>
		</div>

		<div class="hero__photo tilt">
			<div class="tilt__inner">
				<?php
				$hurth_hero_alt = ( 'de' === hurth_lang() )
					? 'Zerlegtes Smartphone mit Werkzeug auf der Reparatur-Arbeitsmatte'
					: 'Disassembled smartphone with tools on a repair mat';

				if ( $hurth_front_id && has_post_thumbnail( $hurth_front_id ) ) {
					echo get_the_post_thumbnail( $hurth_front_id, 'large', array( 'class' => 'tilt__lift' ) );
				} elseif ( $hurth_photo = hurth_picture(
					'hero-repair',
					$hurth_hero_alt,
					array( 'class' => 'tilt__lift', 'loading' => 'eager', 'fetchpriority' => 'high' )
				) ) {
					echo $hurth_photo; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				} else {
					/*
					 * No featured image set, so an authored illustration stands in
					 * rather than a blank panel. A real photo of the shop should
					 * replace this — set a featured image on the front page.
					 */
					?>
					<div class="hero__figure tilt__lift">
						<svg viewBox="0 0 200 150" role="img"
							aria-label="<?php echo esc_attr( 'de' === hurth_lang() ? 'Smartphone-Reparatur Illustration' : 'Phone repair illustration' ); ?>">
							<rect x="62" y="16" width="76" height="126" rx="12"
								fill="#fff" stroke="var(--c-brand)" stroke-width="3"/>
							<rect x="70" y="28" width="60" height="94" rx="4" fill="var(--c-brand-tint)"/>
							<circle cx="100" cy="132" r="4" fill="var(--c-brand)" opacity=".45"/>
							<path d="M78 52l16 22-10 4 14 20" fill="none"
								stroke="var(--c-accent)" stroke-width="3.4" stroke-linecap="round"/>
							<path d="M140 96l16 16m0-16l-16 16" stroke="var(--c-trust)"
								stroke-width="4" stroke-linecap="round"/>
							<circle cx="46" cy="46" r="13" fill="none"
								stroke="var(--c-brand)" stroke-width="3.4"/>
							<path d="M56 56l12 12" stroke="var(--c-brand)"
								stroke-width="3.4" stroke-linecap="round"/>
						</svg>
					</div>
					<?php
				}
				?>
				<span class="tilt__glare" aria-hidden="true"></span>
			</div>
		</div>
	</div>
</section>

<div class="trustbar">
	<div class="wrap trustbar__grid">
		<?php
		$hurth_trust = array(
			array( 'point_local', hurth_info( 'street' ) . ', ' . hurth_info( 'town' ) ),
			array( 'point_since', hurth_t( 'areas' ) ),
			array( 'point_brands', 'iPhone · Samsung · Xiaomi · Google Pixel' ),
			array( 'point_data', hurth_info( 'email' ) ),
		);

		foreach ( $hurth_trust as $hurth_item ) {
			echo '<div class="trustbar__item">' . $hurth_tick
				. '<span><strong>' . esc_html( hurth_t( $hurth_item[0] ) ) . '</strong>'
				. esc_html( $hurth_item[1] ) . '</span></div>';
		}
		?>
	</div>
</div>

<?php
/*
 * The front page is composed, not dumped.
 *
 * Rendering the assigned page's the_content() here printed the original
 * imported Elementor body: English copy on a German-first site, repeated
 * references to DHL after that service was dropped, and a vertical stack of
 * brand logos each rendered as a full-width bordered box. It ran to roughly
 * 6,800px, most of it dead space.
 *
 * The page body is deliberately not output. Everything on the front page is
 * built from designed sections below.
 */

$hurth_brands = array( 'Apple iPhone', 'Samsung Galaxy', 'Xiaomi', 'Google Pixel', 'Huawei', 'OnePlus' );
?>

<section class="brandstrip" aria-label="<?php echo esc_attr( 'de' === hurth_lang() ? 'Marken' : 'Brands' ); ?>">
	<span class="brandstrip__label">
		<?php echo esc_html( 'de' === hurth_lang() ? 'Wir reparieren' : 'We repair' ); ?>
	</span>
	<div class="brandstrip__track">
		<ul>
			<?php
			// Printed twice so the marquee loops seamlessly at -50%.
			for ( $pass = 0; $pass < 2; $pass++ ) {
				foreach ( $hurth_brands as $b ) {
					printf(
						'<li%s>%s</li>',
						$pass ? ' aria-hidden="true"' : '',
						esc_html( $b )
					);
				}
			}
			?>
		</ul>
	</div>
</section>

<section class="section">
	<span class="glow" aria-hidden="true"></span>
	<div class="wrap">
		<div class="statement reveal">
			<p class="statement__lead">
				<?php
				echo esc_html(
					'de' === hurth_lang()
						? 'Ein defektes Handy ist selten nur ein technisches Problem. Termine, Fotos, Bankgeschäfte, Nachrichten an die Familie — alles liegt auf einem Gerät, das plötzlich nicht mehr funktioniert.'
						: 'A broken phone is rarely just a technical problem. Appointments, photos, banking, messages to family — it all sits on one device that has suddenly stopped working.'
				);
				?>
			</p>
			<div class="statement__cols stagger">
				<div>
					<h3><?php echo esc_html( 'de' === hurth_lang() ? 'Preis vor der Reparatur' : 'Price before the repair' ); ?></h3>
					<p>
						<?php
						echo esc_html(
							'de' === hurth_lang()
								? 'Wir sehen uns Ihr Gerät an, erklären verständlich, was defekt ist, und nennen den Preis. Erst danach entscheiden Sie. Keine Überraschung an der Kasse.'
								: 'We look at your device, explain in plain language what has failed, and tell you the price. Then you decide. No surprise at the counter.'
						);
						?>
					</p>
				</div>
				<div>
					<h3><?php echo esc_html( 'de' === hurth_lang() ? 'Wir sagen auch ab' : 'We turn work down' ); ?></h3>
					<p>
						<?php
						echo esc_html(
							'de' === hurth_lang()
								? 'Wenn eine Reparatur teurer wäre als der Wert des Geräts, sagen wir das. Wir verkaufen niemandem eine Reparatur, die sich nicht rechnet.'
								: 'If a repair would cost more than the device is worth, we say so. We do not sell repairs that do not add up.'
						);
						?>
					</p>
				</div>
				<div>
					<h3><?php echo esc_html( 'de' === hurth_lang() ? 'Ihre Daten' : 'Your data' ); ?></h3>
					<p>
						<?php
						echo esc_html(
							'de' === hurth_lang()
								? 'Bei den meisten Reparaturen ist kein Zugriff auf Ihre Inhalte nötig, und wir nehmen ihn auch nicht. Fotos, Nachrichten und Konten sind Ihre Angelegenheit.'
								: 'Most repairs need no access to your content, and we do not take it. Photos, messages and accounts are yours.'
						);
						?>
					</p>
				</div>
			</div>
		</div>
	</div>
</section>

<?php
// Service index — written labels and blurbs, not page titles and excerpts.
$hurth_cards = array();

foreach ( hurth_service_index() as $hurth_item ) {
	$hurth_page = get_page_by_path( $hurth_item[ hurth_lang() ] );

	if ( ! $hurth_page || 'publish' !== $hurth_page->post_status ) {
		$hurth_page = get_page_by_path( $hurth_item['de'] );
	}

	if ( $hurth_page && 'publish' === $hurth_page->post_status
		&& (int) $hurth_page->ID !== $hurth_front_id ) {
		$hurth_item['page'] = $hurth_page;
		$hurth_cards[]      = $hurth_item;
	}
}

if ( $hurth_cards ) :
	?>
	<section class="section section--surface"><span class="glow" aria-hidden="true"></span>
		<div class="wrap">
			<div class="band-head reveal-rise">
				<span class="band-head__no">01 / <?php echo esc_html( 'de' === hurth_lang() ? 'Leistungen' : 'Services' ); ?></span>
				<div>
					<h2><?php echo esc_html( hurth_t( 'what_we_do' ) ); ?></h2>
					<p>
						<?php
						echo esc_html(
							'de' === hurth_lang()
								? 'Drei Dinge, und die richtig: Reparatur, Ankauf, Verkauf.'
								: 'Three things, done properly: repair, buy-back, sales.'
						);
						?>
					</p>
				</div>
			</div>
			<div class="card-grid card-grid--three stagger">
				<?php foreach ( $hurth_cards as $hurth_card ) : ?>
					<article class="card card--visual"><span class="card__rule" aria-hidden="true"></span>
						<?php
						echo hurth_device_chip( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
							isset( $hurth_card['brand'] ) ? $hurth_card['brand'] : 'iphone',
							isset( $hurth_card['state'] ) ? $hurth_card['state'] : 'default'
						);
						?>
						<h3>
							<a class="card__link" href="<?php echo esc_url( get_permalink( $hurth_card['page'] ) . $hurth_q ); ?>">
								<?php echo esc_html( $hurth_card['title'] ); ?>
							</a>
						</h3>
						<p><?php echo esc_html( $hurth_card['blurb'] ); ?></p>
					</article>
				<?php endforeach; ?>
			</div>
		</div>
	</section>
	<?php
endif;

// Photo band — repair, buy-back and craftsmanship, each linking onward.
$hurth_de    = ( 'de' === hurth_lang() );
$hurth_bands = array(
	array( 'service-hands', $hurth_de ? 'Reparatur' : 'Repair',
		$hurth_de ? 'Techniker öffnet ein Smartphone mit einem Präzisionsschraubendreher' : 'Technician opening a smartphone with a precision screwdriver',
		'handy-reparatur-huerth', 'en-phone-repair-huerth' ),
	array( 'workbench', $hurth_de ? 'Ehrliche Diagnose' : 'Honest diagnosis',
		$hurth_de ? 'Zerlegtes Smartphone mit Akku und Platine auf der Arbeitsmatte' : 'Disassembled smartphone with battery and logic board on a work mat',
		'handy-ankauf-huerth', 'en-sell-your-phone-huerth' ),
	array( 'detail-board', $hurth_de ? 'Präzision' : 'Precision',
		$hurth_de ? 'Nahaufnahme einer Smartphone-Platine mit Pinzette' : 'Close-up of a smartphone logic board with tweezers',
		'displaytausch-huerth', 'en-screen-replacement-huerth' ),
);
?>

<section class="section photo-band">
	<div class="wrap">
		<div class="band-head reveal-rise">
			<span class="band-head__no">02 / <?php echo esc_html( 'de' === hurth_lang() ? 'Werkstatt' : 'Workshop' ); ?></span>
			<div>
				<h2>
					<?php
					echo esc_html(
						'de' === hurth_lang()
							? 'Was auf der Werkbank passiert'
							: 'What happens on the bench'
					);
					?>
				</h2>
			</div>
		</div>
	</div>
	<div class="wrap">
		<div class="photo-band__grid stagger">
			<?php
			foreach ( $hurth_bands as $b ) {
				$page = get_page_by_path( $hurth_de ? $b[3] : $b[4] );
				$img  = hurth_picture( $b[0], $b[2], array(
					'width'  => 900,
					'height' => 600,
					'sizes'  => '(max-width: 780px) 100vw, 33vw',
				) );

				if ( ! $img ) {
					continue;
				}

				echo '<figure class="photo-band__item tilt parallax"><div class="tilt__inner">';
				echo $img; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				echo '<span class="tilt__glare" aria-hidden="true"></span></div>';
				echo '<figcaption>';

				if ( $page && 'publish' === $page->post_status ) {
					printf(
						'<a href="%s">%s</a>',
						esc_url( get_permalink( $page ) . $hurth_q ),
						esc_html( $b[1] )
					);
				} else {
					echo esc_html( $b[1] );
				}

				echo '</figcaption></figure>';
			}
			?>
		</div>
	</div>
</section>

<?php
$hurth_posts = get_posts( array( 'numberposts' => 3 ) );

if ( $hurth_posts ) :
	?>
	<section class="section">
		<div class="wrap">
			<div class="band-head reveal-rise">
				<span class="band-head__no">03 / <?php echo esc_html( 'de' === hurth_lang() ? 'Ratgeber' : 'Journal' ); ?></span>
				<div>
					<h2><?php echo esc_html( hurth_t( 'from_blog' ) ); ?></h2>
				</div>
			</div>
			<div class="card-grid card-grid--dated stagger">
				<?php foreach ( $hurth_posts as $hurth_post ) : ?>
					<article class="card"><span class="card__rule" aria-hidden="true"></span>
						<span class="card__meta"><?php echo esc_html( get_the_date( '', $hurth_post ) ); ?></span>
						<h3>
							<a class="card__link" href="<?php echo esc_url( get_permalink( $hurth_post ) . $hurth_q ); ?>">
								<?php echo esc_html( get_the_title( $hurth_post ) ); ?>
							</a>
						</h3>
						<p><?php echo esc_html( wp_trim_words( wp_strip_all_tags( $hurth_post->post_content ), 24 ) ); ?></p>
					</article>
				<?php endforeach; ?>
			</div>
		</div>
	</section>
	<?php
endif;

get_footer();
