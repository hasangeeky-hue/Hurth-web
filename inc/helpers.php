<?php
/**
 * Small helpers: URLs by content key, WhatsApp links, icons, images, logo,
 * breadcrumbs and the navigation walker.
 *
 * @package Aquariende
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Permalink of installer content by key, with a fallback before it exists.
 *
 * @param string $key Content key (page keys equal their slug).
 * @return string
 */
function aquariende_page_url( $key ) {
	if ( function_exists( 'aquariende_content_id' ) ) {
		$id = aquariende_content_id( $key );
		if ( $id ) {
			return get_permalink( $id );
		}
	}
	if ( 'blog' === $key ) {
		$posts_page = (int) get_option( 'page_for_posts' );
		if ( $posts_page ) {
			return get_permalink( $posts_page );
		}
	}
	if ( 'guides' === $key ) {
		$link = get_post_type_archive_link( 'aq_guide' );
		return $link ? $link : home_url( '/guides/' );
	}
	if ( 'home' === $key ) {
		return home_url( '/' );
	}
	$page = get_page_by_path( $key );
	if ( $page && 'publish' === $page->post_status ) {
		return get_permalink( $page );
	}
	return home_url( '/' . trim( $key, '/' ) . '/' );
}

/**
 * Whether a published item exists for a key.
 *
 * @param string $key Content key.
 * @return bool
 */
function aquariende_has_page( $key ) {
	return function_exists( 'aquariende_content_id' ) && aquariende_content_id( $key ) > 0;
}

/**
 * A page field, falling back to the theme's data for installer pages, so a
 * page the owner edited (which the installer leaves alone) still gets its
 * page code and answer block.
 *
 * @param int    $id  Post ID.
 * @param string $key Meta key.
 * @return string
 */
function aquariende_page_meta( $id, $key ) {
	$value = (string) get_post_meta( $id, $key, true );
	if ( '' !== $value || 'page' !== get_post_type( $id ) || ! function_exists( 'aquariende_content_data' ) ) {
		return $value;
	}
	$pages = aquariende_content_data( 'pages' );
	$ckey  = (string) get_post_meta( $id, '_aquariende_content_key', true );
	return isset( $pages[ $ckey ]['meta'][ $key ] ) ? (string) $pages[ $ckey ]['meta'][ $key ] : '';
}

/**
 * WhatsApp URL with a prefilled message that ends in the page code, e.g.
 * "… [G-BLOCKED]". The code carries no personal data (03 §7).
 *
 * @param string $message Message; defaults to the greeting.
 * @param string $code    Page code.
 * @param string $lang    en|bn.
 * @return string
 */
function aquariende_whatsapp_url( $message = '', $code = '', $lang = 'en' ) {
	if ( '' === $message ) {
		$message = ( 'bn' === $lang ) ? aquariende_info( 'whatsapp_hello_bn' ) : aquariende_info( 'whatsapp_hello' );
	}
	if ( '' !== $code ) {
		$message .= ' [' . strtoupper( preg_replace( '/[^A-Za-z0-9-]/', '', $code ) ) . ']';
	}
	return 'https://wa.me/' . aquariende_info( 'whatsapp_e164' ) . '?text=' . rawurlencode( $message );
}

/**
 * The page's WhatsApp context: code and message (one action per page).
 *
 * @return array{code:string,message:string,lang:string}
 */
function aquariende_wa_context() {
	static $ctx = null;
	if ( null !== $ctx ) {
		return $ctx;
	}
	$code    = 'SITE';
	$message = '';
	$lang    = function_exists( 'aquariende_current_lang' ) ? aquariende_current_lang() : 'en';

	if ( is_front_page() ) {
		$code = 'HOME';
	} elseif ( is_post_type_archive( 'aq_guide' ) ) {
		$code    = 'GUIDES';
		$message = 'Hello Aquariende! I read your guides and want to study in Germany.';
	} elseif ( is_home() || is_singular( 'post' ) || is_category() ) {
		$code = 'BLOG';
	} elseif ( is_singular( 'aq_person' ) ) {
		$code = 'TEAM';
	}

	if ( is_singular() || is_front_page() ) {
		$id = is_front_page() ? (int) get_option( 'page_on_front' ) : get_queried_object_id();
		if ( $id ) {
			$c = aquariende_page_meta( $id, '_aq_wa_code' );
			$m = aquariende_page_meta( $id, '_aq_wa_message' );
			if ( '' !== $c ) {
				$code = $c;
			}
			if ( '' !== $m ) {
				$message = $m;
			}
		}
	}

	$ctx = array(
		'code'    => $code,
		'message' => $message,
		'lang'    => $lang,
	);
	return $ctx;
}

/**
 * The one WhatsApp action, rendered for a position (header, hero, inline, cta, sticky).
 *
 * @param string $position Position for analytics.
 * @param array  $args     class, label, tabindex.
 * @return string
 */
function aquariende_wa_button( $position, $args = array() ) {
	$args = wp_parse_args(
		$args,
		array(
			'class'    => 'btn btn-primary',
			'label'    => 'Message us on WhatsApp',
			'tabindex' => '',
			'hide_label_small' => false,
		)
	);
	$ctx = aquariende_wa_context();
	return sprintf(
		'<a class="%1$s" href="%2$s" target="_blank" rel="noopener" data-wa="%3$s"%4$s>%5$s<span class="wa-label">%6$s</span><span class="screen-reader-text"> (opens WhatsApp)</span></a>',
		esc_attr( $args['class'] ),
		esc_url( aquariende_whatsapp_url( $ctx['message'], $ctx['code'], $ctx['lang'] ) ),
		esc_attr( $ctx['code'] . ':' . $position ),
		'' !== $args['tabindex'] ? ' tabindex="' . esc_attr( $args['tabindex'] ) . '"' : '',
		aquariende_icon( 'whatsapp' ),
		esc_html( $args['label'] )
	);
}

/**
 * Email link (secondary, never a button).
 *
 * @return string
 */
function aquariende_email_link() {
	$email = aquariende_info( 'email' );
	return '<a href="mailto:' . esc_attr( $email ) . '" data-email="1">' . esc_html( $email ) . '</a>';
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
		'cross'    => '<path fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" d="M7 7l10 10M17 7 7 17"/>',
		'arrow'    => '<path fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M5 12h14m-5-5 5 5-5 5"/>',
		'mail'     => '<rect x="3" y="5" width="18" height="14" rx="2" fill="none" stroke="currentColor" stroke-width="1.8"/><path fill="none" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round" d="m4 7 8 6 8-6"/>',
		'menu'     => '<path fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" d="M4 7h16M4 12h16M4 17h16"/>',
		'close'    => '<path fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" d="M6 6l12 12M18 6 6 18"/>',
		'lock'     => '<rect x="5" y="10.5" width="14" height="10" rx="2" fill="none" stroke="currentColor" stroke-width="1.8"/><path fill="none" stroke="currentColor" stroke-width="1.8" d="M8.5 10.5V8a3.5 3.5 0 0 1 7 0v2.5"/>',
		'chevron'  => '<path fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="m7 10 5 5 5-5"/>',
		'external' => '<path fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" d="M14 4h6v6M20 4l-9 9M18 14v5a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1V7a1 1 0 0 1 1-1h5"/>',
		'clock'    => '<circle cx="12" cy="12" r="8.5" fill="none" stroke="currentColor" stroke-width="1.8"/><path fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" d="M12 7.5V12l3 2"/>',
		'info'     => '<circle cx="12" cy="12" r="8.5" fill="none" stroke="currentColor" stroke-width="1.8"/><path fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" d="M12 11v5.5M12 7.6v.1"/>',
		'cap'      => '<path fill="none" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round" d="M2.5 9 12 4.5 21.5 9 12 13.5z"/><path fill="none" stroke="currentColor" stroke-width="1.8" d="M6.5 11v5c1.5 1.6 3.4 2.4 5.5 2.4s4-.8 5.5-2.4v-5M21.5 9v5"/>',
		'share'    => '<circle cx="6" cy="12" r="2.5" fill="none" stroke="currentColor" stroke-width="1.8"/><circle cx="18" cy="6" r="2.5" fill="none" stroke="currentColor" stroke-width="1.8"/><circle cx="18" cy="18" r="2.5" fill="none" stroke="currentColor" stroke-width="1.8"/><path fill="none" stroke="currentColor" stroke-width="1.8" d="m8.2 10.8 7.6-3.6M8.2 13.2l7.6 3.6"/>',
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
 * @param array  $args width, height, ext, class, lazy.
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
	return sprintf(
		'<picture><source srcset="%1$s" type="image/webp"><img src="%2$s" alt="%3$s" width="%4$d" height="%5$d"%6$s%7$s decoding="async"></picture>',
		esc_url( $base . '.webp' ),
		esc_url( $base . '.' . $args['ext'] ),
		esc_attr( $alt ),
		(int) $args['width'],
		(int) $args['height'],
		$args['class'] ? ' class="' . esc_attr( $args['class'] ) . '"' : '',
		$args['lazy'] ? ' loading="lazy"' : ''
	);
}

/**
 * Site logo: the custom logo if set, otherwise the bundled mark + wordmark.
 */
function aquariende_logo() {
	echo '<a class="brand" href="' . esc_url( home_url( '/' ) ) . '" rel="home">';
	if ( has_custom_logo() ) {
		echo wp_get_attachment_image(
			(int) get_theme_mod( 'custom_logo' ),
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
 * Date in the site's long format, wrapped in <time>.
 *
 * @param string $ymd Y-m-d.
 * @return string
 */
function aquariende_time( $ymd ) {
	$ts = strtotime( $ymd . ' 12:00:00' );
	if ( ! $ts ) {
		return '';
	}
	return '<time datetime="' . esc_attr( gmdate( 'Y-m-d', $ts ) ) . '">' . esc_html( wp_date( 'j F Y', $ts ) ) . '</time>';
}

/**
 * Breadcrumb trail for the current request: list of [name, url].
 * Used by the visible breadcrumbs and by BreadcrumbList schema.
 *
 * @return array<int,array{0:string,1:string}>
 */
function aquariende_breadcrumbs() {
	static $trail = null;
	if ( null !== $trail ) {
		return $trail;
	}
	$trail = array();
	if ( is_front_page() ) {
		return $trail;
	}
	$trail[] = array( 'Home', home_url( '/' ) );

	if ( is_singular( 'aq_guide' ) ) {
		$trail[] = array( 'Guides', aquariende_page_url( 'guides' ) );
		$trail[] = array( single_post_title( '', false ), get_permalink() );
	} elseif ( is_post_type_archive( 'aq_guide' ) ) {
		$trail[] = array( 'Guides', aquariende_page_url( 'guides' ) );
	} elseif ( is_singular( 'aq_person' ) ) {
		$trail[] = array( 'About', aquariende_page_url( 'about' ) );
		$trail[] = array( 'Team', aquariende_page_url( 'about' ) . '#team' );
		$trail[] = array( single_post_title( '', false ), get_permalink() );
	} elseif ( is_singular( 'post' ) ) {
		$blog    = (int) get_option( 'page_for_posts' );
		$trail[] = array( $blog ? get_the_title( $blog ) : 'Updates', aquariende_page_url( 'blog' ) );
		$trail[] = array( single_post_title( '', false ), get_permalink() );
	} elseif ( is_home() ) {
		$blog    = (int) get_option( 'page_for_posts' );
		$trail[] = array( $blog ? get_the_title( $blog ) : 'Updates', aquariende_page_url( 'blog' ) );
	} elseif ( is_page() ) {
		$id = get_queried_object_id();
		foreach ( array_reverse( get_post_ancestors( $id ) ) as $ancestor ) {
			$trail[] = array( get_the_title( $ancestor ), get_permalink( $ancestor ) );
		}
		$trail[] = array( get_the_title( $id ), get_permalink( $id ) );
	} elseif ( is_search() ) {
		$trail[] = array( 'Search', '' );
	} elseif ( is_404() ) {
		$trail[] = array( 'Not found', '' );
	} elseif ( is_archive() ) {
		$trail[] = array( wp_strip_all_tags( get_the_archive_title() ), '' );
	}
	return $trail;
}

/**
 * Menu fallback for wp_nav_menu() before the installer has run.
 *
 * @param array $args wp_nav_menu arguments.
 */
function aquariende_menu_fallback( $args ) {
	$class = isset( $args['menu_class'] ) ? $args['menu_class'] : 'menu';
	$items = array(
		'Study in Germany' => aquariende_page_url( 'study-in-germany-from-bangladesh' ),
		'Services'         => aquariende_page_url( 'services' ),
		'How it works'     => aquariende_page_url( 'how-it-works' ),
		'About'            => aquariende_page_url( 'about' ),
	);
	echo '<ul class="' . esc_attr( $class ) . '">';
	foreach ( $items as $label => $url ) {
		printf( '<li class="menu-item"><a href="%s">%s</a></li>', esc_url( $url ), esc_html( $label ) );
	}
	echo '</ul>';
}

/**
 * Footer legal links fallback.
 *
 * @param array $args wp_nav_menu arguments.
 */
function aquariende_legal_fallback( $args ) {
	$legal = array(
		'terms-and-condition' => 'Terms and Condition',
		'privacy-policy'      => 'Privacy Policy',
		'refund_returns'      => 'Refund and Returns',
		'cancellation'        => 'Cancellation',
	);
	$items = '';
	foreach ( $legal as $key => $label ) {
		if ( aquariende_has_page( $key ) ) {
			$items .= sprintf( '<li><a href="%s">%s</a></li>', esc_url( aquariende_page_url( $key ) ), esc_html( $label ) );
		}
	}
	if ( $items ) {
		$class = isset( $args['menu_class'] ) ? $args['menu_class'] : 'menu';
		echo '<ul class="' . esc_attr( $class ) . '">' . $items . '</ul>'; // phpcs:ignore WordPress.Security.EscapeOutput -- escaped above.
	}
}

/**
 * Navigation walker: a parent link plus a separate disclosure button for its
 * submenu. Without JavaScript the submenu opens on hover and keyboard focus.
 */
class Aquariende_Nav_Walker extends Walker_Nav_Menu {

	/**
	 * ID of the parent whose submenu is being opened.
	 *
	 * @var string
	 */
	private $parent_id = '';

	/**
	 * Unique prefix per menu instance.
	 *
	 * @var string
	 */
	private $prefix;

	/**
	 * Constructor.
	 *
	 * @param string $prefix ID prefix (desktop/mobile menus differ).
	 */
	public function __construct( $prefix = 'nav' ) {
		$this->prefix = sanitize_html_class( $prefix );
	}

	/**
	 * Start a submenu.
	 *
	 * @param string   $output Output.
	 * @param int      $depth  Depth.
	 * @param stdClass $args   Args.
	 */
	public function start_lvl( &$output, $depth = 0, $args = null ) {
		$output .= '<ul class="sub-menu" id="' . esc_attr( $this->parent_id ) . '">';
	}

	/**
	 * End a submenu.
	 *
	 * @param string   $output Output.
	 * @param int      $depth  Depth.
	 * @param stdClass $args   Args.
	 */
	public function end_lvl( &$output, $depth = 0, $args = null ) {
		$output .= '</ul>';
	}

	/**
	 * Start an element.
	 *
	 * @param string   $output            Output.
	 * @param WP_Post  $data_object       Menu item.
	 * @param int      $depth             Depth.
	 * @param stdClass $args              Args.
	 * @param int      $current_object_id Current object ID.
	 */
	public function start_el( &$output, $data_object, $depth = 0, $args = null, $current_object_id = 0 ) {
		$item    = $data_object;
		$classes = empty( $item->classes ) ? array() : (array) $item->classes;
		$has_sub = in_array( 'menu-item-has-children', $classes, true );
		$current = in_array( 'current-menu-item', $classes, true ) || in_array( 'current_page_item', $classes, true );
		$keep    = array_intersect( $classes, array( 'menu-item-has-children', 'current-menu-item', 'current-menu-ancestor', 'current-menu-parent' ) );

		$output .= '<li class="menu-item' . ( $keep ? ' ' . esc_attr( implode( ' ', $keep ) ) : '' ) . '">';
		$output .= '<a href="' . esc_url( $item->url ) . '"' . ( $current ? ' aria-current="page"' : '' ) . '>' . esc_html( $item->title ) . '</a>';

		if ( $has_sub && 0 === $depth ) {
			$this->parent_id = $this->prefix . '-sub-' . (int) $item->ID;
			$output         .= '<button class="sub-toggle" type="button" aria-expanded="false" aria-controls="' . esc_attr( $this->parent_id ) . '">'
				. aquariende_icon( 'chevron' )
				/* translators: %s: menu item title. */
				. '<span class="screen-reader-text">' . esc_html( sprintf( 'Show the %s pages', $item->title ) ) . '</span></button>';
		}
	}

	/**
	 * End an element.
	 *
	 * @param string   $output      Output.
	 * @param WP_Post  $data_object Item.
	 * @param int      $depth       Depth.
	 * @param stdClass $args        Args.
	 */
	public function end_el( &$output, $data_object, $depth = 0, $args = null ) {
		$output .= '</li>';
	}
}
