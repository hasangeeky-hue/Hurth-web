<?php
/**
 * Head, SEO and crawl (06 §6, 03 §4): titles, meta description, canonical,
 * Open Graph, robots (staging is never indexed), robots.txt with the AI
 * crawler allow list, core XML sitemaps, /llms.txt and /llms-full.txt, and
 * optional IndexNow.
 *
 * When Rank Math, Yoast, AIOSEO or SEOPress is active, the theme does not
 * print its own description, canonical, Open Graph or schema.
 *
 * @package Aquariende
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Whether an SEO plugin manages meta tags.
 *
 * @return bool
 */
function aquariende_has_seo_plugin() {
	$has = defined( 'RANK_MATH_VERSION' ) || defined( 'WPSEO_VERSION' ) || defined( 'AIOSEO_VERSION' ) || defined( 'SEOPRESS_VERSION' );
	return (bool) apply_filters( 'aquariende_has_seo_plugin', $has );
}

/**
 * Whether this site must not be indexed (staging).
 *
 * @return bool
 */
function aquariende_is_noindex_site() {
	if ( AQUARIENDE_NOINDEX ) {
		return true;
	}
	if ( defined( 'AQUARIENDE_ENV' ) && 'staging' === AQUARIENDE_ENV ) {
		return true;
	}
	$host = strtolower( (string) wp_parse_url( home_url(), PHP_URL_HOST ) );
	return '' !== $host && ( 'hostingersite.com' === $host || substr( $host, -strlen( '.hostingersite.com' ) ) === '.hostingersite.com' );
}

/* -------------------------------------------------------------------------
 * Titles: "{SEO title or title} · Aquariende"
 * ---------------------------------------------------------------------- */

function aquariende_title_separator() {
	return '·';
}
add_filter( 'document_title_separator', 'aquariende_title_separator' );

/**
 * Title parts.
 *
 * @param array $parts Parts.
 * @return array
 */
function aquariende_title_parts( $parts ) {
	if ( aquariende_has_seo_plugin() ) {
		return $parts;
	}
	unset( $parts['tagline'] );
	$parts['site'] = 'Aquariende';
	if ( is_front_page() ) {
		$parts['title'] = aquariende_info( 'positioning_short' );
		return $parts;
	}
	if ( is_singular() ) {
		$seo = (string) get_post_meta( get_queried_object_id(), '_aq_seo_title', true );
		if ( '' !== $seo ) {
			$parts['title'] = $seo;
		}
	} elseif ( is_post_type_archive( 'aq_guide' ) ) {
		$parts['title'] = 'Guides for Bangladeshi students going to Germany';
	}
	return $parts;
}
add_filter( 'document_title_parts', 'aquariende_title_parts' );

/* -------------------------------------------------------------------------
 * Description, canonical, Open Graph
 * ---------------------------------------------------------------------- */

/**
 * Meta description: answer block, else excerpt, trimmed to 155 characters.
 *
 * @return string
 */
function aquariende_meta_description() {
	if ( is_front_page() ) {
		$text = aquariende_info( 'positioning' );
	} elseif ( is_post_type_archive( 'aq_guide' ) ) {
		$text = 'Guides to studying in Germany from Bangladesh: eligibility, uni-assist and VPD, blocked account, student visa and costs, each linked to the official source.';
	} elseif ( is_singular() ) {
		$id   = get_queried_object_id();
		$post = get_post( $id );
		$text = aquariende_page_meta( $id, '_aq_answer' );
		if ( '' === $text && $post ) {
			$text = $post->post_excerpt ? $post->post_excerpt : wp_strip_all_tags( strip_shortcodes( $post->post_content ) );
		}
	} else {
		$text = aquariende_info( 'positioning' );
	}
	$text = trim( preg_replace( '/\s+/', ' ', (string) $text ) );
	if ( function_exists( 'mb_strlen' ) && mb_strlen( $text ) > 155 ) {
		$cut  = mb_substr( $text, 0, 154 );
		$text = rtrim( mb_substr( $cut, 0, (int) mb_strrpos( $cut, ' ' ) ), ' ,.;:' ) . '…';
	}
	return $text;
}

/**
 * Canonical URL of the current request without query arguments.
 *
 * @return string
 */
function aquariende_canonical_url() {
	if ( is_front_page() ) {
		return home_url( '/' );
	}
	if ( is_singular() ) {
		$url = wp_get_canonical_url( get_queried_object_id() );
		return $url ? strtok( $url, '?' ) : '';
	}
	if ( is_post_type_archive( 'aq_guide' ) ) {
		return aquariende_page_url( 'guides' );
	}
	if ( is_home() ) {
		return aquariende_page_url( 'blog' );
	}
	return '';
}

/**
 * Strip query strings from core's canonical (?text=, utm, …).
 *
 * @param string $url URL.
 * @return string
 */
function aquariende_filter_canonical( $url ) {
	return $url ? strtok( $url, '?' ) : $url;
}
add_filter( 'get_canonical_url', 'aquariende_filter_canonical' );

/**
 * Open Graph image per template (static 1200×630 cards in assets/img/og/).
 *
 * @return string
 */
function aquariende_og_image() {
	$name = 'og-default.png';
	if ( is_singular( 'aq_guide' ) || is_post_type_archive( 'aq_guide' ) || is_page_template( 'page-pillar.php' ) || is_page_template( 'template-glossary.php' ) ) {
		$name = 'og-guides.png';
	} elseif ( is_page_template( 'template-services.php' ) || is_page_template( 'template-service.php' ) || is_page_template( 'template-how.php' ) ) {
		$name = 'og-services.png';
	}
	return get_template_directory_uri() . '/assets/img/og/' . $name;
}

function aquariende_head_seo() {
	if ( aquariende_has_seo_plugin() ) {
		return;
	}
	$desc = aquariende_meta_description();
	if ( $desc ) {
		echo '<meta name="description" content="' . esc_attr( $desc ) . '">' . "\n";
	}

	$canonical = aquariende_canonical_url();
	// Core prints the canonical for singular content; add it for the rest.
	if ( $canonical && ! is_singular() ) {
		echo '<link rel="canonical" href="' . esc_url( $canonical ) . '">' . "\n";
	}
	if ( is_404() || is_search() ) {
		return;
	}

	$title = wp_get_document_title();
	$type  = ( is_singular( array( 'aq_guide', 'post' ) ) ) ? 'article' : 'website';
	$og    = array(
		'og:site_name'   => 'Aquariende',
		'og:locale'      => ( function_exists( 'aquariende_current_lang' ) && 'bn' === aquariende_current_lang() ) ? 'bn_BD' : 'en_US',
		'og:type'        => $type,
		'og:title'       => $title,
		'og:description' => $desc,
		'og:url'         => $canonical ? $canonical : home_url( '/' ),
		'og:image'       => aquariende_og_image(),
		'og:image:width' => '1200',
		'og:image:height' => '630',
		'og:image:alt'   => 'Aquariende: study in Germany from Bangladesh, in your own name',
	);
	foreach ( $og as $property => $content ) {
		if ( '' !== (string) $content ) {
			echo '<meta property="' . esc_attr( $property ) . '" content="' . esc_attr( $content ) . '">' . "\n";
		}
	}
	if ( is_singular( 'aq_guide' ) ) {
		$reviewed = (string) get_post_meta( get_queried_object_id(), '_aq_reviewed', true );
		if ( $reviewed ) {
			echo '<meta property="article:modified_time" content="' . esc_attr( $reviewed . 'T12:00:00+00:00' ) . '">' . "\n";
		}
	}
	echo '<meta name="twitter:card" content="summary_large_image">' . "\n";
}
add_action( 'wp_head', 'aquariende_head_seo', 2 );

/* -------------------------------------------------------------------------
 * Robots: staging is never indexed; search results and 404 are noindex.
 * ---------------------------------------------------------------------- */

/**
 * Robots meta directives.
 *
 * @param array $robots Directives.
 * @return array
 */
function aquariende_wp_robots( $robots ) {
	if ( aquariende_is_noindex_site() ) {
		unset( $robots['max-image-preview'], $robots['index'], $robots['follow'] );
		$robots['noindex']  = true;
		$robots['nofollow'] = true;
		return $robots;
	}
	if ( is_search() || is_404() ) {
		$robots['noindex'] = true;
		$robots['follow']  = true;
	}
	return $robots;
}
add_filter( 'wp_robots', 'aquariende_wp_robots', 99 );

/**
 * X-Robots-Tag header for every response on staging (also non-HTML).
 */
function aquariende_robots_header() {
	if ( aquariende_is_noindex_site() && ! headers_sent() ) {
		header( 'X-Robots-Tag: noindex, nofollow', true );
	}
}
add_action( 'send_headers', 'aquariende_robots_header' );

/**
 * AI and search crawlers explicitly allowed on production (03 §3.8).
 *
 * @return string[]
 */
function aquariende_allowed_crawlers() {
	return array( 'Googlebot', 'Bingbot', 'Google-Extended', 'OAI-SearchBot', 'ChatGPT-User', 'GPTBot', 'PerplexityBot', 'Perplexity-User', 'ClaudeBot', 'Claude-User', 'Claude-SearchBot', 'Applebot', 'DuckDuckBot' );
}

/**
 * robots.txt.
 *
 * @param string $output Core output.
 * @param bool   $public blog_public.
 * @return string
 */
function aquariende_robots_txt( $output, $public ) {
	if ( aquariende_is_noindex_site() ) {
		return "# Staging copy of aquariende.com: not for indexing.\nUser-agent: *\nDisallow: /\n";
	}
	$rules = "Disallow: /wp-admin/\nAllow: /wp-admin/admin-ajax.php\nDisallow: /?s=\nDisallow: /search/\n";
	$out   = "# aquariende.com: guides for students from Bangladesh. Search and AI answer engines are welcome to read and cite them.\n";
	$out  .= "# Short summary for language models: " . home_url( '/llms.txt' ) . "\n\n";
	foreach ( aquariende_allowed_crawlers() as $bot ) {
		$out .= 'User-agent: ' . $bot . "\n";
	}
	$out .= "Allow: /\n" . $rules . "\n";
	$out .= "User-agent: *\nAllow: /\n" . $rules . "\n";
	$out .= 'Sitemap: ' . home_url( '/wp-sitemap.xml' ) . "\n";
	return $out;
}
add_filter( 'robots_txt', 'aquariende_robots_txt', 99, 2 );

/* -------------------------------------------------------------------------
 * Core XML sitemaps: pages, posts, guides, consented team members.
 * ---------------------------------------------------------------------- */

add_filter( 'wp_sitemaps_enabled', '__return_true' );

/**
 * Post types in the sitemap.
 *
 * @param array $post_types Post types.
 * @return array
 */
function aquariende_sitemap_post_types( $post_types ) {
	return array_intersect_key( $post_types, array_flip( array( 'page', 'post', 'aq_guide', 'aq_person' ) ) );
}
add_filter( 'wp_sitemaps_post_types', 'aquariende_sitemap_post_types' );

/**
 * No user or taxonomy sitemaps (team pages replace author archives).
 *
 * @param WP_Sitemaps_Provider $provider Provider.
 * @param string               $name     Name.
 * @return WP_Sitemaps_Provider|false
 */
function aquariende_sitemap_providers( $provider, $name ) {
	return in_array( $name, array( 'users', 'taxonomies' ), true ) ? false : $provider;
}
add_filter( 'wp_sitemaps_add_provider', 'aquariende_sitemap_providers', 10, 2 );

/**
 * Only consented team members.
 *
 * @param array  $args      Query args.
 * @param string $post_type Post type.
 * @return array
 */
function aquariende_sitemap_query( $args, $post_type ) {
	if ( 'aq_person' === $post_type ) {
		$args['meta_query'] = array( // phpcs:ignore WordPress.DB.SlowDBQuery
			array(
				'key'   => '_aq_consent',
				'value' => '1',
			),
		);
	}
	return $args;
}
add_filter( 'wp_sitemaps_posts_query_args', 'aquariende_sitemap_query', 10, 2 );

/**
 * lastmod from the reviewed date, else the modified date.
 *
 * @param array   $entry Entry.
 * @param WP_Post $post  Post.
 * @return array
 */
function aquariende_sitemap_entry( $entry, $post ) {
	$reviewed = (string) get_post_meta( $post->ID, '_aq_reviewed', true );
	$entry['lastmod'] = $reviewed ? $reviewed : get_post_modified_time( 'Y-m-d', true, $post );
	return $entry;
}
add_filter( 'wp_sitemaps_posts_entry', 'aquariende_sitemap_entry', 10, 2 );

/* -------------------------------------------------------------------------
 * /llms.txt and /llms-full.txt, generated from the installed content.
 * ---------------------------------------------------------------------- */

function aquariende_llms_rewrite() {
	add_rewrite_rule( '^llms\.txt$', 'index.php?aq_llms=short', 'top' );
	add_rewrite_rule( '^llms-full\.txt$', 'index.php?aq_llms=full', 'top' );
}
add_action( 'init', 'aquariende_llms_rewrite' );

/**
 * Query var.
 *
 * @param array $vars Vars.
 * @return array
 */
function aquariende_llms_query_var( $vars ) {
	$vars[] = 'aq_llms';
	return $vars;
}
add_filter( 'query_vars', 'aquariende_llms_query_var' );

/**
 * Plain text of a post (shortcode cards become their text).
 *
 * @param WP_Post $post Post.
 * @return string
 */
function aquariende_plain_text( $post ) {
	$html = do_shortcode( $post->post_content );
	$html = preg_replace( '#</(p|li|h2|h3|aside|tr)>#i', "\n", $html );
	$html = preg_replace( '#<h2[^>]*>#i', "\n## ", $html );
	$html = preg_replace( '#<h3[^>]*>#i', "\n### ", $html );
	$html = preg_replace( '#<li[^>]*>#i', '- ', $html );
	$text = html_entity_decode( wp_strip_all_tags( $html ), ENT_QUOTES, 'UTF-8' );
	$text = preg_replace( "/[ \t]+/", ' ', $text );
	return trim( preg_replace( "/\n\s*\n+/", "\n\n", $text ) );
}

/**
 * A title as plain text (no HTML entities from wptexturize).
 *
 * @param int|WP_Post $post Post.
 * @return string
 */
function aquariende_plain_title( $post ) {
	return html_entity_decode( wp_strip_all_tags( get_the_title( $post ) ), ENT_QUOTES, 'UTF-8' );
}

/**
 * Build llms.txt (short) or llms-full.txt.
 *
 * @param bool $full Full text.
 * @return string
 */
function aquariende_llms_text( $full = false ) {
	$out  = "# Aquariende\n\n";
	$out .= '> ' . aquariende_info( 'positioning' ) . "\n\n";
	$out .= "## What we do and do not do\n\n";
	$out .= "- Three services, in the order students need them: a profile check, university application support and visa application support. Students can take only the stage they need.\n";
	$out .= "- No one can guarantee a visa. The decision belongs to the German Embassy alone.\n";
	$out .= "- Nothing is paid on this website. Fees are explained on WhatsApp before anything is paid.\n";
	$out .= "- We do not represent any university, DAAD, uni-assist or the German Embassy.\n";
	$out .= '- Contact only on WhatsApp ' . aquariende_info( 'whatsapp' ) . ' and by email ' . aquariende_info( 'email' ) . ".\n\n";

	$out .= "## Guides\n\n";
	$pillar = aquariende_content_id( 'study-in-germany-from-bangladesh' );
	if ( $pillar ) {
		$out .= '- [' . aquariende_plain_title( $pillar ) . '](' . get_permalink( $pillar ) . '): ' . get_post_meta( $pillar, '_aq_answer', true ) . "\n";
	}
	$guides = aquariende_guides_in_order();
	foreach ( $guides as $guide ) {
		$q    = (string) get_post_meta( $guide->ID, '_aq_question', true );
		$out .= '- [' . aquariende_plain_title( $guide ) . '](' . get_permalink( $guide ) . '): ' . ( $q ? $q : $guide->post_excerpt ) . ' Last reviewed ' . get_post_meta( $guide->ID, '_aq_reviewed', true ) . ".\n";
	}
	foreach ( array( 'glossary' => 'Glossary of German study and visa terms', 'faq' => 'Questions students and parents ask', 'services' => 'Our three services', 'how-it-works' => 'What happens after you message us' ) as $key => $label ) {
		if ( aquariende_has_page( $key ) ) {
			$out .= '- [' . $label . '](' . aquariende_page_url( $key ) . ")\n";
		}
	}

	$out .= "\n## Official sources we rely on\n\n";
	foreach ( aquariende_sources() as $source ) {
		$out .= '- ' . $source['publisher'] . ': ' . $source['title'] . ' ' . $source['url'] . ' (accessed ' . $source['accessed'] . ")\n";
	}

	$out .= "\n## Facts that change: always check the official page\n\n";
	$vol  = aquariende_volatile();
	foreach ( $vol['volatile'] as $fact ) {
		$out .= '- ' . $fact['what'] . ': ' . $fact['url'] . "\n";
	}

	if ( $full ) {
		$out .= "\n---\n";
		$items = $pillar ? array_merge( array( get_post( $pillar ) ), $guides ) : $guides;
		foreach ( $items as $item ) {
			$out .= "\n# " . aquariende_plain_title( $item ) . "\n\nURL: " . get_permalink( $item ) . "\n";
			$rev  = (string) get_post_meta( $item->ID, '_aq_reviewed', true );
			if ( $rev ) {
				$out .= 'Last reviewed: ' . $rev . "\n";
			}
			$answer = (string) get_post_meta( $item->ID, '_aq_answer', true );
			if ( $answer ) {
				$out .= "\n" . $answer . "\n";
			}
			$out .= "\n" . aquariende_plain_text( $item ) . "\n";
			$sources = aquariende_post_sources( $item->ID );
			if ( $sources ) {
				$out .= "\nSources:\n";
				foreach ( $sources as $s ) {
					$out .= '- ' . $s['publisher'] . ': ' . $s['title'] . ' ' . $s['url'] . "\n";
				}
			}
		}
	}
	return $out;
}

function aquariende_llms_serve() {
	$which = get_query_var( 'aq_llms' );
	if ( ! $which ) {
		return;
	}
	status_header( 200 );
	header( 'Content-Type: text/plain; charset=utf-8' );
	header( 'X-Content-Type-Options: nosniff' );
	if ( aquariende_is_noindex_site() ) {
		header( 'X-Robots-Tag: noindex, nofollow', true );
	}
	echo aquariende_llms_text( 'full' === $which ); // phpcs:ignore WordPress.Security.EscapeOutput -- text/plain.
	exit;
}
add_action( 'template_redirect', 'aquariende_llms_serve', 0 );

/**
 * Keep WordPress from adding a trailing slash to /llms.txt.
 *
 * @param string $redirect Redirect.
 * @return string|false
 */
function aquariende_llms_no_canonical( $redirect ) {
	return get_query_var( 'aq_llms' ) ? false : $redirect;
}
add_filter( 'redirect_canonical', 'aquariende_llms_no_canonical' );

/* -------------------------------------------------------------------------
 * IndexNow (optional, production only): ping on publish. Server-side only.
 * ---------------------------------------------------------------------- */

/**
 * Ping IndexNow when a public item is published or updated.
 *
 * @param string  $new  New status.
 * @param string  $old  Old status.
 * @param WP_Post $post Post.
 */
function aquariende_indexnow( $new, $old, $post ) {
	if ( '' === AQUARIENDE_INDEXNOW_KEY || 'publish' !== $new || aquariende_is_noindex_site() ) {
		return;
	}
	if ( ! in_array( $post->post_type, array( 'page', 'post', 'aq_guide' ), true ) || wp_is_post_revision( $post ) ) {
		return;
	}
	wp_remote_post(
		'https://api.indexnow.org/indexnow',
		array(
			'timeout'  => 3,
			'blocking' => false,
			'headers'  => array( 'Content-Type' => 'application/json; charset=utf-8' ),
			'body'     => wp_json_encode(
				array(
					'host'    => wp_parse_url( home_url(), PHP_URL_HOST ),
					'key'     => AQUARIENDE_INDEXNOW_KEY,
					'urlList' => array( get_permalink( $post ) ),
				)
			),
		)
	);
}
add_action( 'transition_post_status', 'aquariende_indexnow', 10, 3 );

/**
 * Serve the IndexNow key file at /<key>.txt.
 */
function aquariende_indexnow_keyfile() {
	if ( '' === AQUARIENDE_INDEXNOW_KEY || ! is_404() ) {
		return;
	}
	$path = trim( (string) wp_parse_url( isset( $_SERVER['REQUEST_URI'] ) ? wp_unslash( $_SERVER['REQUEST_URI'] ) : '', PHP_URL_PATH ), '/' ); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput -- compared only.
	if ( AQUARIENDE_INDEXNOW_KEY . '.txt' === $path ) {
		status_header( 200 );
		header( 'Content-Type: text/plain; charset=utf-8' );
		echo esc_html( AQUARIENDE_INDEXNOW_KEY );
		exit;
	}
}
add_action( 'template_redirect', 'aquariende_indexnow_keyfile', 0 );
