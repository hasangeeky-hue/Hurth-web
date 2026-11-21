<?php
/**
 * Aquariende theme functions.
 *
 * Classic standalone theme: no parent theme, no page builder, no plugin
 * dependencies. Business details live in aquariende_info(); the optional
 * settings below can be overridden in wp-config.php.
 *
 * @package Aquariende
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/* -------------------------------------------------------------------------
 * Settings (override any of these in wp-config.php, above "That's all")
 * ---------------------------------------------------------------------- */

// Client portal sign-in. Access is created after the consultation fee is paid.
defined( 'AQUARIENDE_PORTAL_URL' ) || define( 'AQUARIENDE_PORTAL_URL', 'https://ops.72-62-90-174.sslip.io/login' );

// URL of the community Facebook group. Empty hides every Facebook link.
defined( 'AQUARIENDE_FACEBOOK_GROUP_URL' ) || define( 'AQUARIENDE_FACEBOOK_GROUP_URL', '' );

// Show the Pabna office address on the Contact page, footer and schema.
defined( 'AQUARIENDE_SHOW_ADDRESS' ) || define( 'AQUARIENDE_SHOW_ADDRESS', false );

// Optional: call-back form submissions are also POSTed as JSON to this URL.
defined( 'AQUARIENDE_LEAD_WEBHOOK' ) || define( 'AQUARIENDE_LEAD_WEBHOOK', '' );

define( 'AQUARIENDE_VERSION', wp_get_theme( get_template() )->get( 'Version' ) ? wp_get_theme( get_template() )->get( 'Version' ) : '1.0.0' );

/* -------------------------------------------------------------------------
 * Business details: the one place to change them
 *
 * Only facts from the 2022-23 site and the owner's instructions. No prices,
 * no success rates, no testimonials, no Bangladeshi phone number.
 * ---------------------------------------------------------------------- */

/**
 * Return one business detail.
 *
 * @param string $key Detail key.
 * @return string
 */
function aquariende_info( $key ) {
	$info = array(
		'name'           => 'Aquariende',
		'slogan'         => __( 'Fetch your dream', 'aquariende' ),
		'description'    => __( 'Aquariende is an online-based higher studies consultancy firm in Bangladesh. We provide solutions for studying in German universities, including obtaining German visas.', 'aquariende' ),
		'whatsapp'       => '+49 1575 0129620',
		'whatsapp_e164'  => '4915750129620',
		'whatsapp_hello' => __( 'Hello Aquariende! I would like to study in Germany. How do I start?', 'aquariende' ),
		'email'          => 'info@aquariende.com',
		'founded'        => '2020',
		'community'      => '50,000',
		'address'        => 'Chalkpoilanpur, Pabna 6600, Bangladesh',
		'locality'       => 'Pabna',
		'postal_code'    => '6600',
		'street'         => 'Chalkpoilanpur',
		'country'        => 'BD',
		'portal_url'     => AQUARIENDE_PORTAL_URL,
		'facebook_group' => AQUARIENDE_FACEBOOK_GROUP_URL,
	);

	$info = apply_filters( 'aquariende_info', $info );

	return isset( $info[ $key ] ) ? (string) $info[ $key ] : '';
}

/**
 * WhatsApp link with a prefilled English greeting.
 *
 * @param string $message Optional message; defaults to the general greeting.
 * @return string
 */
function aquariende_whatsapp_url( $message = '' ) {
	if ( '' === $message ) {
		$message = aquariende_info( 'whatsapp_hello' );
	}

	return 'https://wa.me/' . aquariende_info( 'whatsapp_e164' ) . '?text=' . rawurlencode( $message );
}

/**
 * Whether the office address may be shown.
 *
 * @return bool
 */
function aquariende_show_address() {
	return (bool) AQUARIENDE_SHOW_ADDRESS;
}

/* -------------------------------------------------------------------------
 * Theme setup
 * ---------------------------------------------------------------------- */

function aquariende_setup() {
	load_theme_textdomain( 'aquariende', get_template_directory() . '/languages' );

	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'automatic-feed-links' );
	add_theme_support( 'responsive-embeds' );
	add_theme_support(
		'html5',
		array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script', 'navigation-widgets' )
	);
	add_theme_support(
		'custom-logo',
		array(
			'height'      => 128,
			'width'       => 150,
			'flex-height' => true,
			'flex-width'  => true,
		)
	);

	register_nav_menus(
		array(
			'primary' => __( 'Primary menu', 'aquariende' ),
			'footer'  => __( 'Footer menu (legal pages)', 'aquariende' ),
		)
	);

	set_post_thumbnail_size( 960, 540, true );
}
add_action( 'after_setup_theme', 'aquariende_setup' );

/**
 * Content width for embeds.
 */
function aquariende_content_width() {
	$GLOBALS['content_width'] = 720;
}
add_action( 'after_setup_theme', 'aquariende_content_width', 0 );

/* -------------------------------------------------------------------------
 * Helpers
 * ---------------------------------------------------------------------- */

/**
 * URL of a page by slug, with a sensible fallback before pages exist.
 *
 * @param string $slug Page slug.
 * @return string
 */
function aquariende_page_url( $slug ) {
	// Pages made by the content installer are found by their key (= slug),
	// even while an older page of the previous site still holds the slug.
	if ( function_exists( 'aquariende_content_id' ) ) {
		$installed = aquariende_content_id( $slug );
		if ( $installed ) {
			return get_permalink( $installed );
		}
	}

	if ( 'blog' === $slug ) {
		$posts_page = (int) get_option( 'page_for_posts' );
		if ( $posts_page ) {
			return get_permalink( $posts_page );
		}
	}

	$page = get_page_by_path( $slug );
	if ( $page && 'publish' === $page->post_status ) {
		return get_permalink( $page );
	}

	return home_url( '/' . $slug . '/' );
}

/**
 * Default navigation, used until a primary menu is assigned.
 *
 * @return array<string,string> Label => URL.
 */
function aquariende_default_nav() {
	return array(
		__( 'Services', 'aquariende' ) => aquariende_page_url( 'services' ),
		__( 'About', 'aquariende' )    => aquariende_page_url( 'about' ),
		__( 'Blog', 'aquariende' )     => aquariende_page_url( 'blog' ),
		__( 'Contact', 'aquariende' )  => aquariende_page_url( 'contact' ),
	);
}

/**
 * Menu fallback for wp_nav_menu().
 *
 * @param array $args wp_nav_menu arguments.
 */
function aquariende_menu_fallback( $args ) {
	$class = isset( $args['menu_class'] ) ? $args['menu_class'] : 'menu';
	echo '<ul class="' . esc_attr( $class ) . '">';
	foreach ( aquariende_default_nav() as $label => $url ) {
		$current = ( untrailingslashit( $url ) === untrailingslashit( home_url( add_query_arg( array() ) ) ) );
		printf(
			'<li class="menu-item%1$s"><a href="%2$s"%3$s>%4$s</a></li>',
			$current ? ' current-menu-item' : '',
			esc_url( $url ),
			$current ? ' aria-current="page"' : '',
			esc_html( $label )
		);
	}
	echo '</ul>';
}

/**
 * Footer legal links fallback: lists the legal pages that exist.
 *
 * @param array $args wp_nav_menu arguments.
 */
function aquariende_legal_fallback( $args ) {
	$legal = array(
		'terms-and-condition' => __( 'Terms and Condition', 'aquariende' ),
		'privacy-policy'      => __( 'Privacy Policy', 'aquariende' ),
		'refund_returns'      => __( 'Refund and Returns', 'aquariende' ),
		'cancellation'        => __( 'Cancellation', 'aquariende' ),
	);

	$items = '';
	foreach ( $legal as $slug => $label ) {
		$installed = function_exists( 'aquariende_content_id' ) ? aquariende_content_id( $slug ) : 0;
		$page      = $installed ? get_post( $installed ) : get_page_by_path( $slug );
		if ( $page && 'publish' === $page->post_status ) {
			$items .= sprintf( '<li><a href="%s">%s</a></li>', esc_url( get_permalink( $page ) ), esc_html( $label ) );
		}
	}

	if ( $items ) {
		$class = isset( $args['menu_class'] ) ? $args['menu_class'] : 'menu';
		echo '<ul class="' . esc_attr( $class ) . '">' . $items . '</ul>'; // phpcs:ignore WordPress.Security.EscapeOutput -- built from escaped parts above.
	}
}

/**
 * Inline SVG icon. Decorative unless a label is given.
 *
 * @param string $name  Icon name.
 * @param string $label Accessible label (optional).
 * @return string
 */
function aquariende_icon( $name, $label = '' ) {
	$paths = array(
		'whatsapp' => '<path fill="currentColor" d="M12.04 2a9.9 9.9 0 0 0-8.5 15l-1.4 5.1 5.24-1.37A9.9 9.9 0 1 0 12.04 2Zm0 18.1a8.2 8.2 0 0 1-4.18-1.15l-.3-.18-3.1.82.83-3.03-.2-.31a8.22 8.22 0 1 1 6.95 3.85Zm4.5-6.16c-.25-.12-1.46-.72-1.69-.8-.23-.08-.39-.12-.55.12-.16.25-.64.8-.78.96-.14.17-.29.19-.53.06a6.74 6.74 0 0 1-3.37-2.94c-.25-.44.25-.41.72-1.36.08-.16.04-.3-.02-.43-.06-.12-.55-1.33-.76-1.82-.2-.48-.4-.41-.55-.42h-.47a.9.9 0 0 0-.65.3 2.74 2.74 0 0 0-.85 2.04 4.76 4.76 0 0 0 1 2.53 10.9 10.9 0 0 0 4.17 3.68c1.55.67 2.16.73 2.94.61.47-.07 1.46-.6 1.66-1.17.2-.58.2-1.07.15-1.17-.06-.11-.22-.17-.47-.29Z"/>',
		'check'    => '<path fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" d="m5 12.5 4.2 4.2L19 7"/>',
		'cross'    => '<path fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" d="M7 7l10 10M17 7 7 17"/>',
		'arrow'    => '<path fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M5 12h14m-5-5 5 5-5 5"/>',
		'mail'     => '<rect x="3" y="5" width="18" height="14" rx="2" fill="none" stroke="currentColor" stroke-width="1.8"/><path fill="none" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round" d="m4 7 8 6 8-6"/>',
		'menu'     => '<path fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" d="M4 7h16M4 12h16M4 17h16"/>',
		'close'    => '<path fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" d="M6 6l12 12M18 6 6 18"/>',
		'lock'     => '<rect x="5" y="10.5" width="14" height="10" rx="2" fill="none" stroke="currentColor" stroke-width="1.8"/><path fill="none" stroke="currentColor" stroke-width="1.8" d="M8.5 10.5V8a3.5 3.5 0 0 1 7 0v2.5"/>',
		'facebook' => '<path fill="currentColor" d="M13.5 21v-7.5h2.6l.4-3h-3V8.6c0-.87.25-1.46 1.5-1.46h1.6V4.45A21 21 0 0 0 14.27 4C11.97 4 10.5 5.4 10.5 7.97v2.53H7.9v3h2.6V21h3Z"/>',
		'pin'      => '<path fill="none" stroke="currentColor" stroke-width="1.8" d="M12 21s-6.5-6.2-6.5-11a6.5 6.5 0 0 1 13 0c0 4.8-6.5 11-6.5 11Z"/><circle cx="12" cy="10" r="2.3" fill="none" stroke="currentColor" stroke-width="1.8"/>',
		'video'    => '<rect x="3" y="6" width="13" height="12" rx="2" fill="none" stroke="currentColor" stroke-width="1.8"/><path fill="none" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round" d="m16 10.5 5-3v9l-5-3"/>',
		'file'     => '<path fill="none" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round" d="M6 3h8l4 4v14H6z"/><path fill="none" stroke="currentColor" stroke-width="1.8" d="M14 3v4h4M9 12h6M9 16h6"/>',
		'users'    => '<circle cx="9" cy="8" r="3.2" fill="none" stroke="currentColor" stroke-width="1.8"/><path fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" d="M3.5 19c.6-3.2 2.8-5 5.5-5s4.9 1.8 5.5 5M16 5.2a3 3 0 0 1 0 5.6M17.5 14.3c1.7.6 2.8 2.2 3 4.7"/>',
		'cap'      => '<path fill="none" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round" d="M2.5 9 12 4.5 21.5 9 12 13.5z"/><path fill="none" stroke="currentColor" stroke-width="1.8" d="M6.5 11v5c1.5 1.6 3.4 2.4 5.5 2.4s4-.8 5.5-2.4v-5M21.5 9v5"/>',
	);

	if ( ! isset( $paths[ $name ] ) ) {
		return '';
	}

	$a11y = $label
		? ' role="img" aria-label="' . esc_attr( $label ) . '"'
		: ' aria-hidden="true" focusable="false"';

	return '<svg class="icon icon-' . esc_attr( $name ) . '" viewBox="0 0 24 24" width="24" height="24"' . $a11y . '>' . $paths[ $name ] . '</svg>';
}

/**
 * <picture> with WebP and JPEG/PNG fallback from assets/img.
 *
 * @param string $name Base filename without extension.
 * @param string $alt  Alt text.
 * @param array  $args width, height, ext, class, lazy, sizes.
 * @return string
 */
function aquariende_picture( $name, $alt, $args = array() ) {
	$args = wp_parse_args(
		$args,
		array(
			'width'  => 480,
			'height' => 600,
			'ext'    => 'jpg',
			'class'  => '',
			'lazy'   => true,
		)
	);

	$base = get_template_directory_uri() . '/assets/img/' . $name;
	$mime = ( 'png' === $args['ext'] ) ? 'image/png' : 'image/jpeg';

	return sprintf(
		'<picture><source srcset="%1$s" type="image/webp"><img src="%2$s" alt="%3$s" width="%4$d" height="%5$d"%6$s%7$s decoding="async" data-type="%8$s"></picture>',
		esc_url( $base . '.webp' ),
		esc_url( $base . '.' . $args['ext'] ),
		esc_attr( $alt ),
		(int) $args['width'],
		(int) $args['height'],
		$args['class'] ? ' class="' . esc_attr( $args['class'] ) . '"' : '',
		$args['lazy'] ? ' loading="lazy"' : '',
		esc_attr( $mime )
	);
}

/**
 * Site logo: the custom logo if set, otherwise the bundled mark + wordmark.
 */
function aquariende_logo() {
	echo '<a class="brand" href="' . esc_url( home_url( '/' ) ) . '" rel="home">';
	if ( has_custom_logo() ) {
		$logo_id = (int) get_theme_mod( 'custom_logo' );
		echo wp_get_attachment_image(
			$logo_id,
			'full',
			false,
			array(
				'class' => 'brand-mark',
				'alt'   => '',
			)
		);
	} else {
		echo '<picture><source srcset="' . esc_url( get_template_directory_uri() . '/assets/img/logo-mark.webp' ) . '" type="image/webp"><img class="brand-mark" src="' . esc_url( get_template_directory_uri() . '/assets/img/logo-mark.png' ) . '" alt="" width="137" height="128"></picture>';
	}
	echo '<span class="brand-name">' . esc_html( aquariende_info( 'name' ) ) . '</span>';
	echo '</a>';
}

/**
 * Team members. Names, roles and universities from the 2022-23 site.
 * The owner should confirm each person is still on the team.
 *
 * @return array<int,array<string,string>>
 */
function aquariende_team() {
	return array(
		array(
			'slug'       => 'team-murtuja-hasan',
			'name'       => 'Md Murtuja Hasan',
			'role'       => __( 'Team lead', 'aquariende' ),
			'university' => __( 'Technical University of Munich (TUM)', 'aquariende' ),
			'study'      => __( "Master's, Science and Technology Studies", 'aquariende' ),
		),
		array(
			'slug'       => 'team-munna-khan',
			'name'       => 'Munna Khan',
			'role'       => __( 'Head of communication', 'aquariende' ),
			'university' => __( 'Schmalkalden University of Applied Sciences', 'aquariende' ),
			'study'      => __( "Master's, Applied Computer Science", 'aquariende' ),
		),
		array(
			'slug'       => 'team-imon-hosen',
			'name'       => 'Imon Hosen',
			'role'       => __( 'Head of technical department', 'aquariende' ),
			'university' => __( 'HAW Hamburg', 'aquariende' ),
			'study'      => __( "Bachelor's, Information Engineering", 'aquariende' ),
		),
		array(
			'slug'       => 'team-khaled-bin-musa',
			'name'       => 'Khaled Bin Musa',
			'role'       => __( 'Senior consultant', 'aquariende' ),
			'university' => __( 'University of Bremen', 'aquariende' ),
			'study'      => __( "Master's, English-Speaking Cultures: Language, Text, Media", 'aquariende' ),
		),
		array(
			'slug'       => 'team-al-mahmud',
			'name'       => 'Al Mahmud',
			'role'       => __( 'Senior consultant', 'aquariende' ),
			'university' => __( 'University of Potsdam', 'aquariende' ),
			'study'      => __( "Master's, National and International Administration and Policy", 'aquariende' ),
		),
	);
}

/**
 * The three stages / packages. One source for Home, Services and the file card.
 *
 * @return array<int,array<string,mixed>>
 */
function aquariende_stages() {
	return array(
		array(
			'key'       => 'profile',
			'name'      => __( 'Mandatory profile check', 'aquariende' ),
			'short'     => __( 'Profile check', 'aquariende' ),
			'tagline'   => __( 'Before you apply, evaluate your qualifications against the requirements of the German education system.', 'aquariende' ),
			'summary'   => __( 'Before submitting an application to the university, you should evaluate whether you have a chance of being accepted. Our expert team will help you evaluate your profile and answer all your questions.', 'aquariende' ),
			'included'  => array(
				__( '30-minute video consultation', 'aquariende' ),
				__( 'CV checking', 'aquariende' ),
				__( 'Document evaluation', 'aquariende' ),
				__( 'Good universities related to your previous study', 'aquariende' ),
			),
			'excluded'  => array(),
			'file_note' => __( 'CV checking · document evaluation', 'aquariende' ),
			'wa'        => __( 'Hello Aquariende! I am interested in the Mandatory profile check. How do I start?', 'aquariende' ),
		),
		array(
			'key'       => 'application',
			'name'      => __( 'Application to the University', 'aquariende' ),
			'short'     => __( 'Application', 'aquariende' ),
			'tagline'   => __( 'We help you find a suitable university and provide all documentation support.', 'aquariende' ),
			'summary'   => __( 'We find the right subject and the right university for your profile, make your CV, write your motivation letter and calculate your grade for the VPD. We arrange your notary and send your documents to Germany by DHL. In four video consultations we process every application step in front of you.', 'aquariende' ),
			'included'  => array(
				__( "Finding the candidate's subject and university", 'aquariende' ),
				__( 'Preparing a standard CV', 'aquariende' ),
				__( 'Writing the motivation letter', 'aquariende' ),
				__( 'VPD support', 'aquariende' ),
				__( 'uni-assist application support', 'aquariende' ),
				__( 'Notary support and DHL support', 'aquariende' ),
				__( 'Four video consultations', 'aquariende' ),
				__( 'Tracking your application in real time', 'aquariende' ),
			),
			'excluded'  => array(),
			'file_note' => __( 'CV · motivation letter · VPD', 'aquariende' ),
			'wa'        => __( 'Hello Aquariende! I am interested in the Application to the University. How do I start?', 'aquariende' ),
		),
		array(
			'key'       => 'visa',
			'name'      => __( 'Visa application process', 'aquariende' ),
			'short'     => __( 'Visa', 'aquariende' ),
			'tagline'   => __( 'No one can guarantee a visa. We will help you prepare your visa documentation properly.', 'aquariende' ),
			'summary'   => __( 'We assist you to take the embassy visa appointment in time, fill out the visa form properly and open a blocked account. We find better health and travel insurance, air tickets at a reasonable price, and train and bus tickets for after you arrive in Germany. You get three live consultations.', 'aquariende' ),
			'included'  => array(
				__( 'Embassy visa appointment', 'aquariende' ),
				__( 'Opening the blocked account', 'aquariende' ),
				__( 'Filling out the visa form properly', 'aquariende' ),
				__( 'Letter of intention for the visa application', 'aquariende' ),
				__( 'Deferring the semester according to the visa timeline', 'aquariende' ),
				__( 'Health and travel insurance', 'aquariende' ),
				__( 'Air ticket and local transport tickets in Germany', 'aquariende' ),
				__( 'Three video consultations', 'aquariende' ),
			),
			'excluded'  => array(
				__( 'A visa guarantee: no one can guarantee a visa', 'aquariende' ),
			),
			'file_note' => __( 'Visa form · blocked account', 'aquariende' ),
			'wa'        => __( 'Hello Aquariende! I am interested in the Visa application process. How do I start?', 'aquariende' ),
		),
	);
}

/**
 * The four steps from first message to portal. Used on Home, Services and Contact.
 *
 * @return array<int,array<string,string>>
 */
function aquariende_flow() {
	return array(
		array(
			'title' => __( 'Message us on WhatsApp', 'aquariende' ),
			'text'  => __( 'Within 24 hours our team will call you directly from Germany and answer your questions.', 'aquariende' ),
		),
		array(
			'title' => __( 'Pay the consultation fee', 'aquariende' ),
			'text'  => __( 'Nothing is paid on this website.', 'aquariende' ),
		),
		array(
			'title' => __( 'Get your portal link by email', 'aquariende' ),
			'text'  => __( 'After payment, a link to your own file is sent to your email address.', 'aquariende' ),
		),
		array(
			'title' => __( 'Upload documents and book', 'aquariende' ),
			'text'  => __( 'Inside the portal, upload your documents and book an appointment to talk with our experts.', 'aquariende' ),
		),
	);
}

/* -------------------------------------------------------------------------
 * Assets
 * ---------------------------------------------------------------------- */

/**
 * Google Fonts URL. Bengali glyphs only download when Bengali text is shown,
 * because Google serves each script as its own unicode-range subset.
 *
 * @return string
 */
function aquariende_fonts_url() {
	return 'https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:opsz,wght@12..96,600;12..96,800&family=IBM+Plex+Sans:wght@400;500;600&family=IBM+Plex+Sans+Bengali:wght@400;600&display=swap';
}

/**
 * Preconnect and non-blocking font stylesheet, early in <head>.
 */
function aquariende_head_early() {
	$fonts = aquariende_fonts_url();
	echo '<link rel="preconnect" href="https://fonts.googleapis.com">' . "\n";
	echo '<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>' . "\n";
	if ( is_front_page() ) {
		echo '<link rel="preconnect" href="https://cdnjs.cloudflare.com" crossorigin>' . "\n";
	}
	echo '<link rel="stylesheet" href="' . esc_url( $fonts ) . '" media="print" onload="this.media=\'all\'">' . "\n";
	echo '<noscript><link rel="stylesheet" href="' . esc_url( $fonts ) . '"></noscript>' . "\n";

	if ( ! has_site_icon() ) {
		echo '<link rel="icon" type="image/png" href="' . esc_url( get_template_directory_uri() . '/assets/img/logo-mark.png' ) . '">' . "\n";
	}

	/*
	 * Mark JS and motion state before first paint. The hero file card is hidden
	 * only while "hero-pending" is set; main.js removes it once GSAP has taken
	 * over, and this timer removes it anyway if GSAP never arrives.
	 */
	$front = is_front_page() ? 'true' : 'false';
	?>
<script>(function(d,w){var h=d.documentElement,q=/[?&]motion=off\b/.test(w.location.search),r=w.matchMedia&&w.matchMedia('(prefers-reduced-motion: reduce)').matches;h.classList.add('js');if(q||r){h.classList.add('reduce-motion');}else if('IntersectionObserver' in w){h.classList.add('motion-ok');if(<?php echo $front; // phpcs:ignore WordPress.Security.EscapeOutput -- literal true/false. ?>){h.classList.add('hero-pending');w.setTimeout(function(){h.classList.remove('hero-pending');},2500);}}})(document,window);</script>
	<?php
}
add_action( 'wp_head', 'aquariende_head_early', 1 );

function aquariende_assets() {
	wp_enqueue_style( 'aquariende', get_stylesheet_uri(), array(), AQUARIENDE_VERSION );

	$deps = array();

	if ( is_front_page() ) {
		wp_register_script( 'gsap', 'https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js', array(), null, array( 'in_footer' => true, 'strategy' => 'defer' ) ); // phpcs:ignore WordPress.WP.EnqueuedResourceParameters.MissingVersion -- version is in the CDN path.
		// ScrollTrigger is only needed for the desktop pin; main.js loads it on demand (see aquariendeL10n.scrollTrigger).
		$deps = array( 'gsap' );
	}

	wp_enqueue_script(
		'aquariende',
		get_template_directory_uri() . '/assets/js/main.js',
		$deps,
		AQUARIENDE_VERSION,
		array(
			'in_footer' => true,
			'strategy'  => 'defer',
		)
	);

	wp_localize_script(
		'aquariende',
		'aquariendeL10n',
		array(
			'sending'  => __( 'Sending…', 'aquariende' ),
			'sent'     => __( 'Sent. Within 24 hours our team will call you.', 'aquariende' ),
			'failed'   => __( 'That did not go through. Please message us on WhatsApp instead.', 'aquariende' ),
			'name'     => __( 'Please enter your name.', 'aquariende' ),
			'phone'    => __( 'Please enter a WhatsApp number with country code, for example +880 1712 345678.', 'aquariende' ),
			'openMenu' => __( 'Open menu', 'aquariende' ),
			'close'    => __( 'Close menu', 'aquariende' ),
			'scrollTrigger' => is_front_page() ? 'https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/ScrollTrigger.min.js' : '',
		)
	);

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}

	// No block styles are needed on the front page: it has no block content.
	if ( is_front_page() ) {
		wp_dequeue_style( 'wp-block-library' );
		wp_dequeue_style( 'wp-block-library-theme' );
		wp_dequeue_style( 'global-styles' );
		wp_dequeue_style( 'classic-theme-styles' );
	}
}
add_action( 'wp_enqueue_scripts', 'aquariende_assets' );

/**
 * Add defer for WordPress versions older than 6.3 (no loading strategies).
 *
 * @param string $tag    Script tag.
 * @param string $handle Handle.
 * @return string
 */
function aquariende_defer_scripts( $tag, $handle ) {
	if ( version_compare( get_bloginfo( 'version' ), '6.3', '>=' ) ) {
		return $tag;
	}
	if ( in_array( $handle, array( 'aquariende', 'gsap', 'gsap-scrolltrigger' ), true ) && false === strpos( $tag, ' defer' ) ) {
		$tag = str_replace( ' src=', ' defer src=', $tag );
	}
	return $tag;
}
add_filter( 'script_loader_tag', 'aquariende_defer_scripts', 10, 2 );

/* -------------------------------------------------------------------------
 * Head: meta description fallback, schema, emoji
 * ---------------------------------------------------------------------- */

/**
 * Whether an SEO plugin manages meta tags.
 *
 * @return bool
 */
function aquariende_has_seo_plugin() {
	return defined( 'RANK_MATH_VERSION' ) || defined( 'WPSEO_VERSION' ) || defined( 'AIOSEO_VERSION' ) || defined( 'SEOPRESS_VERSION' );
}

/**
 * Meta description and theme colour when no SEO plugin is active.
 */
function aquariende_head_meta() {
	echo '<meta name="theme-color" content="#0E2A33">' . "\n";

	if ( aquariende_has_seo_plugin() ) {
		return;
	}

	$desc = aquariende_info( 'description' );
	if ( is_singular() && ! is_front_page() ) {
		$post = get_queried_object();
		if ( $post instanceof WP_Post ) {
			$excerpt = has_excerpt( $post ) ? $post->post_excerpt : wp_strip_all_tags( strip_shortcodes( $post->post_content ) );
			$excerpt = trim( preg_replace( '/\s+/', ' ', $excerpt ) );
			if ( $excerpt ) {
				$desc = wp_trim_words( $excerpt, 28, '…' );
			}
		}
	}

	echo '<meta name="description" content="' . esc_attr( $desc ) . '">' . "\n";
}
add_action( 'wp_head', 'aquariende_head_meta', 2 );

/**
 * Organization schema. Skipped when Rank Math is active, since it outputs
 * its own Organization node (filter aquariende_output_schema to override).
 */
function aquariende_schema() {
	if ( ! apply_filters( 'aquariende_output_schema', ! defined( 'RANK_MATH_VERSION' ) ) ) {
		return;
	}
	if ( ! is_front_page() ) {
		return;
	}

	$schema = array(
		'@context'     => 'https://schema.org',
		'@type'        => 'EducationalOrganization',
		'name'         => aquariende_info( 'name' ),
		'url'          => home_url( '/' ),
		'logo'         => get_template_directory_uri() . '/assets/img/aquariende-logo.png',
		'description'  => aquariende_info( 'description' ),
		'email'        => aquariende_info( 'email' ),
		'foundingDate' => aquariende_info( 'founded' ),
		'areaServed'   => 'BD',
		'contactPoint' => array(
			'@type'             => 'ContactPoint',
			'telephone'         => aquariende_info( 'whatsapp' ),
			'contactType'       => 'customer service',
			'email'             => aquariende_info( 'email' ),
			'availableLanguage' => array( 'English', 'Bengali' ),
		),
	);

	if ( aquariende_info( 'facebook_group' ) ) {
		$schema['sameAs'] = array( aquariende_info( 'facebook_group' ) );
	}

	if ( aquariende_show_address() ) {
		$schema['address'] = array(
			'@type'           => 'PostalAddress',
			'streetAddress'   => aquariende_info( 'street' ),
			'addressLocality' => aquariende_info( 'locality' ),
			'postalCode'      => aquariende_info( 'postal_code' ),
			'addressCountry'  => aquariende_info( 'country' ),
		);
	}

	echo '<script type="application/ld+json">' . wp_json_encode( $schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE ) . '</script>' . "\n";
}
add_action( 'wp_head', 'aquariende_schema', 20 );

/**
 * Remove the emoji detection script and styles.
 */
function aquariende_disable_emoji() {
	remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
	remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
	remove_action( 'wp_print_styles', 'print_emoji_styles' );
	remove_action( 'admin_print_styles', 'print_emoji_styles' );
	remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
	remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );
	remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
	add_filter( 'emoji_svg_url', '__return_false' );
	if ( function_exists( 'wp_enqueue_emoji_styles' ) ) {
		remove_action( 'wp_enqueue_scripts', 'wp_enqueue_emoji_styles' );
		remove_action( 'admin_enqueue_scripts', 'wp_enqueue_emoji_styles' );
	}
}
add_action( 'init', 'aquariende_disable_emoji' );

/**
 * Drop the s.w.org dns-prefetch left behind by the emoji script.
 *
 * @param array  $urls          URLs.
 * @param string $relation_type Relation.
 * @return array
 */
function aquariende_resource_hints( $urls, $relation_type ) {
	if ( 'dns-prefetch' === $relation_type ) {
		$urls = array_filter(
			$urls,
			function ( $url ) {
				return false === strpos( is_array( $url ) ? ( $url['href'] ?? '' ) : $url, 's.w.org' );
			}
		);
	}
	return $urls;
}
add_filter( 'wp_resource_hints', 'aquariende_resource_hints', 10, 2 );

function aquariende_excerpt_length() {
	return 24;
}
add_filter( 'excerpt_length', 'aquariende_excerpt_length' );

function aquariende_excerpt_more() {
	return '…';
}
add_filter( 'excerpt_more', 'aquariende_excerpt_more' );

/**
 * Body classes for layout hooks.
 *
 * @param array $classes Classes.
 * @return array
 */
function aquariende_body_class( $classes ) {
	$classes[] = 'has-dark-top';
	return $classes;
}
add_filter( 'body_class', 'aquariende_body_class' );

/* -------------------------------------------------------------------------
 * Cache purge after a deploy
 *
 * Deployer for Git replaces theme files without touching the database, so a
 * page cache can keep serving the old markup. Purge when the version changes.
 * ---------------------------------------------------------------------- */

function aquariende_purge_caches() {
	if ( ! headers_sent() ) {
		header( 'X-LiteSpeed-Purge: *' );
	}
	do_action( 'litespeed_purge_all' );
	do_action( 'wpfc_clear_all_cache' );
	if ( function_exists( 'rocket_clean_domain' ) ) {
		rocket_clean_domain();
	}
	if ( function_exists( 'w3tc_flush_all' ) ) {
		w3tc_flush_all();
	}
	if ( function_exists( 'wp_cache_clear_cache' ) ) {
		wp_cache_clear_cache();
	}
	wp_cache_flush();
}

function aquariende_purge_on_version_change() {
	if ( get_option( 'aquariende_deployed_version' ) === AQUARIENDE_VERSION ) {
		return;
	}
	update_option( 'aquariende_deployed_version', AQUARIENDE_VERSION, false );
	aquariende_purge_caches();
}
add_action( 'init', 'aquariende_purge_on_version_change', 1 );
add_action( 'dfg_after_package_install', 'aquariende_purge_caches', 20 );
add_action( 'after_switch_theme', 'aquariende_purge_caches' );

/* -------------------------------------------------------------------------
 * Call-back form (admin-post.php)
 *
 * Name and WhatsApp number only. No uploads, no booking. Protections: nonce,
 * honeypot, minimum fill time and a per-IP hourly limit. The lead is emailed
 * to the site admin email and, if AQUARIENDE_LEAD_WEBHOOK is set, POSTed as
 * JSON to the ops system so it lands in the sales inbox at stage S1.
 * ---------------------------------------------------------------------- */

/**
 * Finish a call-back submission: JSON for fetch(), redirect otherwise.
 *
 * @param string $state sent|invalid|failed.
 */
function aquariende_callback_finish( $state ) {
	$is_ajax = isset( $_POST['ajax'] ) && '1' === $_POST['ajax']; // phpcs:ignore WordPress.Security.NonceVerification -- read-only flag.

	if ( $is_ajax ) {
		if ( 'sent' === $state ) {
			wp_send_json_success( array( 'state' => $state ) );
		}
		wp_send_json_error( array( 'state' => $state ), 'invalid' === $state ? 400 : 500 );
	}

	$back = wp_get_referer() ? wp_get_referer() : home_url( '/' );
	$back = remove_query_arg( 'callback', $back );
	$back = preg_replace( '/#.*$/', '', $back );

	wp_safe_redirect( add_query_arg( 'callback', $state, $back ) . '#callback' );
	exit;
}

/**
 * Normalise a phone number to + and digits.
 *
 * @param string $raw Raw input.
 * @return string Empty when invalid.
 */
function aquariende_clean_phone( $raw ) {
	$raw    = trim( (string) $raw );
	$plus   = ( 0 === strpos( $raw, '+' ) || 0 === strpos( $raw, '00' ) );
	$digits = preg_replace( '/\D+/', '', $raw );

	if ( 0 === strpos( $raw, '00' ) ) {
		$digits = substr( $digits, 2 );
	}

	$len = strlen( $digits );
	if ( $len < 8 || $len > 15 ) {
		return '';
	}

	return ( $plus ? '+' : '' ) . $digits;
}

function aquariende_handle_callback() {
	$nonce = isset( $_POST['aq_nonce'] ) ? sanitize_text_field( wp_unslash( $_POST['aq_nonce'] ) ) : '';
	$trap  = isset( $_POST['aq_website'] ) ? trim( sanitize_text_field( wp_unslash( $_POST['aq_website'] ) ) ) : '';
	$start = isset( $_POST['aq_ts'] ) ? absint( $_POST['aq_ts'] ) : 0;

	if ( ! wp_verify_nonce( $nonce, 'aquariende_callback' ) ) {
		aquariende_callback_finish( 'failed' );
	}

	// Bots: pretend success so they learn nothing.
	if ( '' !== $trap || ( $start && ( time() - $start ) < 3 ) ) {
		aquariende_callback_finish( 'sent' );
	}

	$name  = isset( $_POST['aq_name'] ) ? sanitize_text_field( wp_unslash( $_POST['aq_name'] ) ) : '';
	$phone = isset( $_POST['aq_phone'] ) ? aquariende_clean_phone( sanitize_text_field( wp_unslash( $_POST['aq_phone'] ) ) ) : '';
	$page  = isset( $_POST['aq_page'] ) ? esc_url_raw( wp_unslash( $_POST['aq_page'] ) ) : '';

	$name_len = function_exists( 'mb_strlen' ) ? mb_strlen( $name ) : strlen( $name );
	if ( $name_len < 2 || $name_len > 80 || '' === $phone ) {
		aquariende_callback_finish( 'invalid' );
	}

	// At most five requests per hour from one address.
	$ip  = isset( $_SERVER['REMOTE_ADDR'] ) ? sanitize_text_field( wp_unslash( $_SERVER['REMOTE_ADDR'] ) ) : '';
	$key = 'aq_cb_' . substr( hash_hmac( 'sha256', $ip, wp_salt( 'nonce' ) ), 0, 20 );
	$hit = (int) get_transient( $key );
	if ( $hit >= 5 ) {
		aquariende_callback_finish( 'failed' );
	}
	set_transient( $key, $hit + 1, HOUR_IN_SECONDS );

	$body = sprintf(
		/* translators: 1: name, 2: phone, 3: page URL, 4: time, 5: WhatsApp link. */
		__( "New call-back request from the website.\n\nName: %1\$s\nWhatsApp: %2\$s\nSent from: %3\$s\nTime: %4\$s\n\nReply on WhatsApp: %5\$s", 'aquariende' ),
		$name,
		$phone,
		$page ? $page : home_url( '/' ),
		wp_date( 'Y-m-d H:i T' ),
		'https://wa.me/' . ltrim( $phone, '+' )
	);

	$sent = wp_mail(
		get_option( 'admin_email' ),
		/* translators: %s: name. */
		sprintf( __( '[Aquariende] Call-back request: %s', 'aquariende' ), $name ),
		$body,
		array( 'Content-Type: text/plain; charset=UTF-8' )
	);

	$hooked = false;
	if ( AQUARIENDE_LEAD_WEBHOOK ) {
		$response = wp_remote_post(
			AQUARIENDE_LEAD_WEBHOOK,
			array(
				'timeout' => 5,
				'headers' => array( 'Content-Type' => 'application/json' ),
				'body'    => wp_json_encode(
					array(
						'source'       => 'website',
						'stage'        => 'S1',
						'name'         => $name,
						'whatsapp'     => $phone,
						'page'         => $page,
						'submitted_at' => gmdate( 'c' ),
					)
				),
			)
		);
		$code   = is_wp_error( $response ) ? 0 : (int) wp_remote_retrieve_response_code( $response );
		$hooked = ( $code >= 200 && $code < 300 );
	}

	aquariende_callback_finish( ( $sent || $hooked ) ? 'sent' : 'failed' );
}
add_action( 'admin_post_nopriv_aquariende_callback', 'aquariende_handle_callback' );
add_action( 'admin_post_aquariende_callback', 'aquariende_handle_callback' );

/* -------------------------------------------------------------------------
 * Content installer: pages, posts, menus and settings from the old site
 * ---------------------------------------------------------------------- */

require_once get_template_directory() . '/inc/content-installer.php';
