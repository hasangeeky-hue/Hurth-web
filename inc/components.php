<?php
/**
 * Components (06 §4): shortcodes usable inside editable content, content
 * filters (glossary links, external-link labels, heading anchors) and the
 * queries behind the FAQ, related links and table of contents.
 *
 * The markup itself lives in template-parts/; shortcodes only buffer it.
 *
 * @package Aquariende
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Render a template part to a string.
 *
 * @param string $slug Part name under template-parts/.
 * @param array  $args Args.
 * @return string
 */
function aquariende_part( $slug, $args = array() ) {
	ob_start();
	get_template_part( 'template-parts/' . $slug, null, $args );
	return (string) ob_get_clean();
}

/**
 * Content key of a post (installer key), or ''.
 *
 * @param int $post_id Post ID.
 * @return string
 */
function aquariende_key_of( $post_id ) {
	return (string) get_post_meta( $post_id, '_aquariende_content_key', true );
}

/* -------------------------------------------------------------------------
 * Shortcodes
 * ---------------------------------------------------------------------- */

/**
 * [aq_volatile topic="blocked-account"]
 *
 * @param array $atts Attributes.
 * @return string
 */
function aquariende_sc_volatile( $atts ) {
	$atts = shortcode_atts( array( 'topic' => '' ), $atts, 'aq_volatile' );
	return aquariende_part( 'volatile-fact', array( 'topic' => sanitize_key( $atts['topic'] ) ) );
}
add_shortcode( 'aq_volatile', 'aquariende_sc_volatile' );

/**
 * [aq_deadline topic="vpd-8-weeks"]
 *
 * @param array $atts Attributes.
 * @return string
 */
function aquariende_sc_deadline( $atts ) {
	$atts = shortcode_atts( array( 'topic' => '' ), $atts, 'aq_deadline' );
	return aquariende_part( 'deadline-fact', array( 'topic' => sanitize_key( $atts['topic'] ) ) );
}
add_shortcode( 'aq_deadline', 'aquariende_sc_deadline' );

/**
 * [aq_stage_guides stage="visa"]: list of the stage's guides.
 *
 * @param array $atts Attributes.
 * @return string
 */
function aquariende_sc_stage_guides( $atts ) {
	$atts   = shortcode_atts( array( 'stage' => '' ), $atts, 'aq_stage_guides' );
	$guides = aquariende_stage_guides( sanitize_key( $atts['stage'] ) );
	if ( ! $guides ) {
		return '';
	}
	$out = '<ul class="guide-links">';
	foreach ( $guides as $guide ) {
		$q    = (string) get_post_meta( $guide->ID, '_aq_question', true );
		$out .= '<li><a href="' . esc_url( get_permalink( $guide ) ) . '">' . esc_html( get_the_title( $guide ) ) . '</a>'
			. ( $q ? '<span class="guide-links-q">' . esc_html( $q ) . '</span>' : '' ) . '</li>';
	}
	return $out . '</ul>';
}
add_shortcode( 'aq_stage_guides', 'aquariende_sc_stage_guides' );

/**
 * [aq_never]: the "we never" stamp list.
 *
 * @return string
 */
function aquariende_sc_never() {
	return aquariende_part( 'never-promised' );
}
add_shortcode( 'aq_never', 'aquariende_sc_never' );

/**
 * [aq_next_steps]: what happens after you message us.
 *
 * @return string
 */
function aquariende_sc_next_steps() {
	return aquariende_part( 'what-happens-next', array( 'heading' => 'h3' ) );
}
add_shortcode( 'aq_next_steps', 'aquariende_sc_next_steps' );

/**
 * [aq_have_ready]: the checklist to prepare before messaging.
 *
 * @return string
 */
function aquariende_sc_have_ready() {
	return aquariende_part( 'have-these-ready', array( 'heading' => 'h3' ) );
}
add_shortcode( 'aq_have_ready', 'aquariende_sc_have_ready' );

/**
 * [aq_service_link stage="visa"]text[/aq_service_link]: link to a service page.
 *
 * @param array  $atts    Attributes.
 * @param string $content Link text.
 * @return string
 */
function aquariende_sc_service_link( $atts, $content = '' ) {
	$atts  = shortcode_atts( array( 'stage' => '' ), $atts, 'aq_service_link' );
	$stage = aquariende_stage( sanitize_key( $atts['stage'] ) );
	if ( ! $stage ) {
		return esc_html( $content );
	}
	return '<a href="' . esc_url( aquariende_page_url( $stage['page'] ) ) . '">' . esc_html( $content ? $content : $stage['short'] ) . '</a>';
}
add_shortcode( 'aq_service_link', 'aquariende_sc_service_link' );

/**
 * [aq_link key="guide-visa"]text[/aq_link]: link to installer content by key,
 * so links survive slug changes.
 *
 * @param array  $atts    Attributes.
 * @param string $content Link text.
 * @return string
 */
function aquariende_sc_link( $atts, $content = '' ) {
	$atts = shortcode_atts( array( 'key' => '' ), $atts, 'aq_link' );
	$key  = sanitize_key( $atts['key'] );
	if ( '' === $key ) {
		return esc_html( $content );
	}
	if ( 'guides' !== $key && 'home' !== $key && ! aquariende_has_page( $key ) ) {
		return esc_html( $content );
	}
	return '<a href="' . esc_url( aquariende_page_url( $key ) ) . '">' . esc_html( $content ) . '</a>';
}
add_shortcode( 'aq_link', 'aquariende_sc_link' );

/* -------------------------------------------------------------------------
 * Content filters (priority 9: before wpautop and shortcodes)
 * ---------------------------------------------------------------------- */

/**
 * Glossary terms: title and aliases => anchor URL.
 *
 * @return array<string,string>
 */
function aquariende_glossary_map() {
	static $map = null;
	if ( null !== $map ) {
		return $map;
	}
	$map  = array();
	$base = aquariende_has_page( 'glossary' ) ? aquariende_page_url( 'glossary' ) : '';
	if ( '' === $base ) {
		return $map;
	}
	$terms = get_posts(
		array(
			'post_type'   => 'aq_term',
			'post_status' => 'publish',
			'numberposts' => 200,
		)
	);
	foreach ( $terms as $term ) {
		$url     = $base . '#' . $term->post_name;
		$names   = array( $term->post_title );
		$aliases = (string) get_post_meta( $term->ID, '_aq_aliases', true );
		foreach ( explode( ',', $aliases ) as $alias ) {
			$alias = trim( $alias );
			if ( '' !== $alias ) {
				$names[] = $alias;
			}
		}
		foreach ( $names as $name ) {
			$map[ $name ] = array(
				'url'  => $url,
				'slug' => $term->post_name,
			);
		}
	}
	// Longest names first, so "Consular Services Portal" wins over "CSP" inside it.
	uksort(
		$map,
		function ( $a, $b ) {
			return strlen( $b ) - strlen( $a );
		}
	);
	return $map;
}

/**
 * Glossary slugs linked in a guide (for schema "about").
 *
 * @var array<int,string[]>
 */
$GLOBALS['aquariende_linked_terms'] = array();

/**
 * Wrap the first occurrence of each glossary term in a guide in a link to
 * its glossary anchor. Text inside links, headings, code and dfn is skipped.
 *
 * @param string $html Content.
 * @return string
 */
function aquariende_glossary_links( $html ) {
	if ( ! is_singular( 'aq_guide' ) || ! in_the_loop() ) {
		return $html;
	}
	$map = aquariende_glossary_map();
	if ( ! $map ) {
		return $html;
	}
	$parts   = preg_split( '/(<[^>]+>)/', $html, -1, PREG_SPLIT_DELIM_CAPTURE );
	$skip    = 0;
	$done    = array();
	$linked  = array();
	$blocked = array( 'a', 'h1', 'h2', 'h3', 'h4', 'code', 'dfn', 'summary', 'button' );

	foreach ( $parts as $i => $part ) {
		if ( '' === $part ) {
			continue;
		}
		if ( '<' === $part[0] ) {
			if ( preg_match( '#^<(/?)([a-z0-9]+)#i', $part, $m ) && in_array( strtolower( $m[2] ), $blocked, true ) ) {
				$skip += ( '/' === $m[1] ) ? -1 : 1;
				$skip  = max( 0, $skip );
			}
			continue;
		}
		if ( $skip > 0 || false !== strpos( $part, '[' ) ) {
			continue;
		}
		foreach ( $map as $name => $term ) {
			if ( isset( $done[ $term['slug'] ] ) ) {
				continue;
			}
			$pattern = '/(?<![\w-])(' . preg_quote( $name, '/' ) . ')(?![\w-])/u';
			if ( preg_match( $pattern, $part ) ) {
				$part                  = preg_replace( $pattern, '<a class="term" href="' . esc_url( $term['url'] ) . '">$1</a>', $part, 1 );
				$done[ $term['slug'] ] = true;
				$linked[]              = $term['slug'];
				// Do not match inside the link just added.
				break;
			}
		}
		$parts[ $i ] = $part;
	}
	$GLOBALS['aquariende_linked_terms'][ get_the_ID() ] = $linked;
	return implode( '', $parts );
}
add_filter( 'the_content', 'aquariende_glossary_links', 9 );

/**
 * Label links to other sites as external for screen readers and mark them for
 * the source_click event.
 *
 * @param string $html Content.
 * @return string
 */
function aquariende_external_links( $html ) {
	$home = wp_parse_url( home_url(), PHP_URL_HOST );
	return preg_replace_callback(
		'#<a\s([^>]*href="(https?://[^"]+)"[^>]*)>(.*?)</a>#is',
		function ( $m ) use ( $home ) {
			$host = wp_parse_url( $m[2], PHP_URL_HOST );
			if ( ! $host || $host === $home || false !== strpos( $m[1], 'data-ext' ) || false !== strpos( $m[2], 'wa.me' ) ) {
				return $m[0];
			}
			return '<a ' . $m[1] . ' data-ext="' . esc_attr( $host ) . '">' . $m[3] . '<span class="screen-reader-text"> (external site)</span></a>';
		},
		$html
	);
}
add_filter( 'the_content', 'aquariende_external_links', 9 );

/**
 * Give every H2 an id, so the table of contents and pillar anchors work.
 *
 * @param string $html Content.
 * @return string
 */
function aquariende_heading_ids( $html ) {
	return preg_replace_callback(
		'#<h2(\s[^>]*)?>(.*?)</h2>#is',
		function ( $m ) {
			$attrs = isset( $m[1] ) ? $m[1] : '';
			if ( false !== stripos( $attrs, 'id=' ) ) {
				return $m[0];
			}
			$id = sanitize_title( wp_strip_all_tags( $m[2] ) );
			return '<h2 id="' . esc_attr( $id ) . '"' . $attrs . '>' . $m[2] . '</h2>';
		},
		$html
	);
}
add_filter( 'the_content', 'aquariende_heading_ids', 9 );

/**
 * H2 headings of a post's content: list of [id, text].
 *
 * @param WP_Post $post Post.
 * @return array<int,array{0:string,1:string}>
 */
function aquariende_headings( $post ) {
	$html = aquariende_heading_ids( $post->post_content );
	preg_match_all( '#<h2[^>]*id="([^"]+)"[^>]*>(.*?)</h2>#is', $html, $m, PREG_SET_ORDER );
	$out = array();
	foreach ( $m as $h ) {
		$out[] = array( $h[1], wp_strip_all_tags( $h[2] ) );
	}
	return $out;
}

/* -------------------------------------------------------------------------
 * Queries
 * ---------------------------------------------------------------------- */

/**
 * FAQ items shown on a page or guide, by its content key.
 *
 * @param string $key Content key ('*' for all).
 * @return WP_Post[]
 */
function aquariende_faqs_for( $key ) {
	$args = array(
		'post_type'   => 'aq_faq',
		'post_status' => 'publish',
		'numberposts' => 200,
		'orderby'     => 'menu_order title',
		'order'       => 'ASC',
	);
	if ( '*' !== $key ) {
		$args['meta_query'] = array( // phpcs:ignore WordPress.DB.SlowDBQuery
			array(
				'key'     => '_aq_faq_pages',
				'value'   => $key,
				'compare' => 'LIKE',
			),
		);
	}
	$items = get_posts( $args );
	if ( '*' === $key ) {
		return $items;
	}
	return array_values(
		array_filter(
			$items,
			function ( $item ) use ( $key ) {
				$keys = array_map( 'trim', explode( ',', (string) get_post_meta( $item->ID, '_aq_faq_pages', true ) ) );
				return in_array( $key, $keys, true );
			}
		)
	);
}

/**
 * FAQ answer HTML (paragraphs, shortcodes, external-link labels).
 *
 * @param WP_Post $item FAQ post.
 * @return string
 */
function aquariende_faq_answer( $item ) {
	$html = aquariende_external_links( (string) $item->post_content );
	return wp_kses_post( do_shortcode( wpautop( $html ) ) );
}

/**
 * Plain-text FAQ answer (schema, llms-full.txt).
 *
 * @param WP_Post $item FAQ post.
 * @return string
 */
function aquariende_faq_text( $item ) {
	return trim( preg_replace( '/\s+/', ' ', wp_strip_all_tags( strip_shortcodes( $item->post_content ) ) ) );
}

/**
 * The next guide in journey order, for the "across" link.
 *
 * @param int $post_id Current guide.
 * @return WP_Post|null
 */
function aquariende_next_guide( $post_id ) {
	$all = aquariende_guides_in_order();
	foreach ( $all as $i => $guide ) {
		if ( (int) $guide->ID === (int) $post_id ) {
			return isset( $all[ $i + 1 ] ) ? $all[ $i + 1 ] : ( $i > 0 ? $all[0] : null );
		}
	}
	return null;
}

/**
 * Related guides from the _aq_related keys.
 *
 * @param int $post_id Guide ID.
 * @return WP_Post[]
 */
function aquariende_related_guides( $post_id ) {
	$out = array();
	foreach ( explode( ',', (string) get_post_meta( $post_id, '_aq_related', true ) ) as $key ) {
		$key = trim( $key );
		$id  = $key ? aquariende_content_id( $key ) : 0;
		if ( $id && $id !== (int) $post_id ) {
			$out[] = get_post( $id );
		}
	}
	return $out;
}
