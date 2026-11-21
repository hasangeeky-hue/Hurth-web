<?php
/**
 * Structured data (06 §5, 03 §5): one JSON-LD @graph per request.
 *
 * Organisation type: ProfessionalService (owner decision pending, 00 Q15).
 * FAQPage only on /faq/. Person nodes only for team members with consent.
 * No offers, prices or ratings anywhere.
 *
 * @package Aquariende
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * dateModified: the review date (noon UTC), never earlier than publication.
 *
 * @param int $post_id Post ID.
 * @return string
 */
function aquariende_date_modified( $post_id ) {
	$reviewed  = (string) get_post_meta( $post_id, '_aq_reviewed', true );
	$published = (int) get_post_time( 'U', true, $post_id );
	$modified  = $reviewed ? (int) strtotime( $reviewed . 'T12:00:00+00:00' ) : (int) get_post_modified_time( 'U', true, $post_id );
	return gmdate( 'c', max( $published, $modified ) );
}

function aquariende_schema_id( $fragment, $url = '' ) {
	return ( $url ? strtok( $url, '#' ) : home_url( '/' ) ) . '#' . $fragment;
}

/**
 * Organisation node.
 *
 * @return array
 */
function aq_schema_org() {
	$info = aquariende_info_all();
	$node = array(
		'@type'             => $info['org_type'],
		'@id'               => aquariende_schema_id( 'org' ),
		'name'              => $info['name'],
		'url'               => home_url( '/' ),
		'description'       => $info['positioning'],
		'email'             => $info['email'],
		'logo'              => array(
			'@type'  => 'ImageObject',
			'@id'    => aquariende_schema_id( 'logo' ),
			'url'    => get_template_directory_uri() . '/assets/img/aquariende-logo.png',
			'width'  => 506,
			'height' => 640,
		),
		'image'             => array( '@id' => aquariende_schema_id( 'logo' ) ),
		'areaServed'        => array(
			'@type' => 'Country',
			'name'  => 'Bangladesh',
		),
		'knowsLanguage'     => array( 'en', 'bn' ),
		'knowsAbout'        => array( 'Studying in Germany', 'uni-assist', 'German national student visa', 'Blocked account (Sperrkonto)', 'anabin' ),
		'contactPoint'      => array(
			'@type'             => 'ContactPoint',
			'contactType'       => 'customer service',
			'telephone'         => $info['whatsapp'],
			'email'             => $info['email'],
			'availableLanguage' => array( 'en', 'bn' ),
			'url'               => aquariende_whatsapp_url(),
		),
	);
	if ( ! empty( $info['legal_name'] ) ) {
		$node['legalName'] = $info['legal_name'];
	}
	if ( ! empty( $info['sameas'] ) ) {
		$node['sameAs'] = array_values( $info['sameas'] );
	}
	if ( aquariende_show_address() ) {
		$node['address'] = array(
			'@type'           => 'PostalAddress',
			'streetAddress'   => $info['street'],
			'addressLocality' => $info['locality'],
			'postalCode'      => $info['postal_code'],
			'addressCountry'  => $info['country'],
		);
	}
	return $node;
}

function aq_schema_website() {
	return array(
		'@type'      => 'WebSite',
		'@id'        => aquariende_schema_id( 'website' ),
		'url'        => home_url( '/' ),
		'name'       => 'Aquariende',
		'description' => aquariende_info( 'positioning_short' ),
		'publisher'  => array( '@id' => aquariende_schema_id( 'org' ) ),
		'inLanguage' => 'en',
	);
}

/**
 * WebPage node for the current URL.
 *
 * @param string $type WebPage type.
 * @param string $url  URL.
 * @return array
 */
function aq_schema_webpage( $type, $url ) {
	$node = array(
		'@type'      => $type,
		'@id'        => aquariende_schema_id( 'webpage', $url ),
		'url'        => $url,
		'name'       => wp_get_document_title(),
		'isPartOf'   => array( '@id' => aquariende_schema_id( 'website' ) ),
		'inLanguage' => function_exists( 'aquariende_current_lang' ) ? aquariende_current_lang() : 'en',
	);
	$desc = aquariende_meta_description();
	if ( $desc ) {
		$node['description'] = $desc;
	}
	if ( is_front_page() || is_page_template( 'template-about.php' ) || is_page_template( 'template-contact.php' ) ) {
		$node['about'] = array( '@id' => aquariende_schema_id( 'org' ) );
	}
	if ( count( aquariende_breadcrumbs() ) > 1 ) {
		$node['breadcrumb'] = array( '@id' => aquariende_schema_id( 'breadcrumb', $url ) );
	}
	if ( is_singular() ) {
		$id       = get_queried_object_id();
		$reviewed = (string) get_post_meta( $id, '_aq_reviewed', true );
		$node['datePublished'] = get_post_time( 'c', true, $id );
		$node['dateModified']  = aquariende_date_modified( $id );
		if ( $reviewed ) {
			$node['lastReviewed'] = $reviewed;
			$reviewer             = aquariende_person_by_slug( get_post_meta( $id, '_aq_reviewer', true ) );
			$node['reviewedBy']   = $reviewer ? array( '@id' => aquariende_person_id( $reviewer ) ) : array( '@id' => aquariende_schema_id( 'org' ) );
		}
	}
	return $node;
}

function aq_schema_breadcrumbs( $url ) {
	$trail = aquariende_breadcrumbs();
	if ( count( $trail ) < 2 ) {
		return null;
	}
	$items = array();
	foreach ( $trail as $i => $crumb ) {
		$item = array(
			'@type'    => 'ListItem',
			'position' => $i + 1,
			'name'     => $crumb[0],
		);
		$target = '' !== $crumb[1] ? $crumb[1] : $url;
		if ( $target ) {
			$item['item'] = $target;
		}
		$items[] = $item;
	}
	return array(
		'@type'           => 'BreadcrumbList',
		'@id'             => aquariende_schema_id( 'breadcrumb', $url ),
		'itemListElement' => $items,
	);
}

/**
 * Service node (no offers, no price).
 *
 * @param array $stage Stage from aquariende_stages().
 * @return array
 */
function aq_schema_service( $stage ) {
	$url = aquariende_page_url( $stage['page'] );
	return array(
		'@type'       => 'Service',
		'@id'         => aquariende_schema_id( 'service', $url ),
		'name'        => $stage['name'],
		'serviceType' => $stage['service'],
		'description' => $stage['summary'],
		'url'         => $url,
		'provider'    => array( '@id' => aquariende_schema_id( 'org' ) ),
		'areaServed'  => array(
			'@type' => 'Country',
			'name'  => 'Bangladesh',
		),
		'availableLanguage' => array( 'en', 'bn' ),
	);
}

/**
 * @id of a team member.
 *
 * @param WP_Post $person Person.
 * @return string
 */
function aquariende_person_id( $person ) {
	return aquariende_schema_id( 'person', get_permalink( $person ) );
}

/**
 * Person node; null without consent. Only facts from BRAND-FACTS.md and
 * what the owner entered with the member's consent.
 *
 * @param WP_Post $person Person.
 * @return array|null
 */
function aq_schema_person( $person ) {
	if ( ! aquariende_person_ok( $person ) ) {
		return null;
	}
	$node = array(
		'@type'    => 'Person',
		'@id'      => aquariende_person_id( $person ),
		'name'     => get_the_title( $person ),
		'url'      => get_permalink( $person ),
		'worksFor' => array( '@id' => aquariende_schema_id( 'org' ) ),
	);
	$role = (string) get_post_meta( $person->ID, '_aq_role', true );
	$uni  = (string) get_post_meta( $person->ID, '_aq_university', true );
	$li   = (string) get_post_meta( $person->ID, '_aq_linkedin', true );
	$ka   = (string) get_post_meta( $person->ID, '_aq_knows_about', true );
	if ( $role ) {
		$node['jobTitle'] = $role;
	}
	if ( $uni ) {
		$node['affiliation'] = array(
			'@type' => 'CollegeOrUniversity',
			'name'  => $uni,
		);
	}
	if ( $li ) {
		$node['sameAs'] = array( $li );
	}
	if ( $ka ) {
		$node['knowsAbout'] = array_values( array_filter( array_map( 'trim', explode( ',', $ka ) ) ) );
	}
	return $node;
}

/**
 * Glossary slugs mentioned in a post's content.
 *
 * @param WP_Post $post Post.
 * @return array<string,string> slug => name
 */
function aquariende_terms_in( $post ) {
	$text  = wp_strip_all_tags( $post->post_content );
	$found = array();
	foreach ( aquariende_glossary_map() as $name => $term ) {
		if ( isset( $found[ $term['slug'] ] ) ) {
			continue;
		}
		if ( preg_match( '/(?<![\w-])' . preg_quote( $name, '/' ) . '(?![\w-])/u', $text ) ) {
			$found[ $term['slug'] ] = $name;
		}
	}
	return $found;
}

/**
 * Article node for a guide (or the pillar page).
 *
 * @param WP_Post $post Post.
 * @param string  $url  URL.
 * @param array   $graph Graph, by reference (term nodes are added).
 * @return array
 */
function aq_schema_article( $post, $url, &$graph ) {
	$reviewed = (string) get_post_meta( $post->ID, '_aq_reviewed', true );
	$author   = aquariende_person_by_slug( get_post_meta( $post->ID, '_aq_author', true ) );
	$node     = array(
		'@type'               => 'Article',
		'@id'                 => aquariende_schema_id( 'article', $url ),
		'headline'            => get_the_title( $post ),
		'description'         => aquariende_meta_description(),
		'url'                 => $url,
		'mainEntityOfPage'    => array( '@id' => aquariende_schema_id( 'webpage', $url ) ),
		'datePublished'       => get_post_time( 'c', true, $post ),
		'dateModified'        => aquariende_date_modified( $post->ID ),
		'author'              => $author ? array( '@id' => aquariende_person_id( $author ) ) : array( '@id' => aquariende_schema_id( 'org' ) ),
		'publisher'           => array( '@id' => aquariende_schema_id( 'org' ) ),
		'inLanguage'          => 'en',
		'isAccessibleForFree' => true,
		'image'               => aquariende_og_image(),
	);
	$citations = array();
	foreach ( aquariende_post_sources( $post->ID ) as $s ) {
		$citations[] = array(
			'@type'     => 'CreativeWork',
			'name'      => $s['title'],
			'url'       => $s['url'],
			'publisher' => array(
				'@type' => 'Organization',
				'name'  => $s['publisher'],
			),
		);
	}
	if ( $citations ) {
		$node['citation'] = $citations;
	}
	$glossary = aquariende_has_page( 'glossary' ) ? aquariende_page_url( 'glossary' ) : '';
	if ( $glossary ) {
		$about = array();
		foreach ( aquariende_terms_in( $post ) as $slug => $name ) {
			$term_id = strtok( $glossary, '#' ) . '#' . $slug;
			$about[] = array( '@id' => $term_id );
			$graph[] = array(
				'@type'            => 'DefinedTerm',
				'@id'              => $term_id,
				'name'             => $name,
				'url'              => $term_id,
				'inDefinedTermSet' => $glossary,
			);
		}
		if ( $about ) {
			$node['about'] = $about;
		}
	}
	return $node;
}

/**
 * FAQPage main entities.
 *
 * @param WP_Post[] $items FAQ posts.
 * @return array
 */
function aq_schema_faq( $items ) {
	$out = array();
	foreach ( $items as $item ) {
		$answer = aquariende_faq_text( $item );
		if ( '' === $answer ) {
			continue;
		}
		$out[] = array(
			'@type'          => 'Question',
			'name'           => get_the_title( $item ),
			'acceptedAnswer' => array(
				'@type' => 'Answer',
				'text'  => $answer,
			),
		);
	}
	return $out;
}

/**
 * HowTo for /how-it-works/ only, mirroring the visible steps.
 *
 * @param string $url URL.
 * @return array
 */
function aq_schema_howto( $url ) {
	$steps = array();
	foreach ( aquariende_flow() as $i => $step ) {
		$steps[] = array(
			'@type'    => 'HowToStep',
			'position' => $i + 1,
			'name'     => $step['title'],
			'text'     => $step['text'],
			'url'      => $url . '#step-' . ( $i + 1 ),
		);
	}
	return array(
		'@type' => 'HowTo',
		'@id'   => aquariende_schema_id( 'howto', $url ),
		'name'  => 'What happens after you message Aquariende',
		'step'  => $steps,
	);
}

/**
 * DefinedTermSet for /glossary/.
 *
 * @param string $url Glossary URL.
 * @return array
 */
function aq_schema_termset( $url ) {
	$terms = get_posts(
		array(
			'post_type'   => 'aq_term',
			'post_status' => 'publish',
			'numberposts' => 200,
			'orderby'     => 'title',
			'order'       => 'ASC',
		)
	);
	$set_id  = aquariende_schema_id( 'glossary', $url );
	$defined = array();
	foreach ( $terms as $term ) {
		$node = array(
			'@type'            => 'DefinedTerm',
			'@id'              => $url . '#' . $term->post_name,
			'name'             => $term->post_title,
			'description'      => (string) get_post_meta( $term->ID, '_aq_def', true ),
			'url'              => $url . '#' . $term->post_name,
			'inDefinedTermSet' => array( '@id' => $set_id ),
		);
		$de = (string) get_post_meta( $term->ID, '_aq_term_de', true );
		if ( $de ) {
			$node['alternateName'] = $de;
		}
		$src = (string) get_post_meta( $term->ID, '_aq_source_url', true );
		if ( $src ) {
			$node['sameAs'] = $src;
		}
		$defined[] = $node;
	}
	return array(
		'@type'          => 'DefinedTermSet',
		'@id'            => $set_id,
		'name'           => 'Glossary: German study and visa terms',
		'url'            => $url,
		'inLanguage'     => 'en',
		'hasDefinedTerm' => $defined,
	);
}

/**
 * Build the whole graph for this request.
 *
 * @return array
 */
function aquariende_schema_graph() {
	$url   = aquariende_canonical_url();
	$graph = array( aq_schema_org(), aq_schema_website() );
	if ( ! $url ) {
		return $graph;
	}

	$type = 'WebPage';
	$id   = is_singular() ? get_queried_object_id() : 0;
	$post = $id ? get_post( $id ) : null;

	if ( is_page_template( 'template-about.php' ) ) {
		$type = 'AboutPage';
	} elseif ( is_page_template( 'template-contact.php' ) ) {
		$type = 'ContactPage';
	} elseif ( is_page_template( 'template-faq.php' ) ) {
		$type = 'FAQPage';
	} elseif ( is_post_type_archive( 'aq_guide' ) || is_home() || is_page_template( 'template-glossary.php' ) || is_page_template( 'template-services.php' ) ) {
		$type = 'CollectionPage';
	} elseif ( is_singular( 'aq_person' ) ) {
		$type = 'ProfilePage';
	}

	$page = aq_schema_webpage( $type, $url );

	if ( is_front_page() ) {
		foreach ( aquariende_stages() as $stage ) {
			$graph[] = aq_schema_service( $stage );
		}
	} elseif ( is_page_template( 'template-services.php' ) ) {
		$parts = array();
		foreach ( aquariende_stages() as $stage ) {
			$graph[] = aq_schema_service( $stage );
			$parts[] = array( '@id' => aquariende_schema_id( 'service', aquariende_page_url( $stage['page'] ) ) );
		}
		$page['hasPart'] = $parts;
	} elseif ( is_page_template( 'template-service.php' ) && $post ) {
		foreach ( aquariende_stages() as $stage ) {
			if ( $stage['page'] === aquariende_key_of( $post->ID ) ) {
				$graph[]           = aq_schema_service( $stage );
				$page['mainEntity'] = array( '@id' => aquariende_schema_id( 'service', $url ) );
			}
		}
	} elseif ( is_page_template( 'template-how.php' ) ) {
		$graph[]            = aq_schema_howto( $url );
		$page['mainEntity'] = array( '@id' => aquariende_schema_id( 'howto', $url ) );
	} elseif ( ( is_singular( 'aq_guide' ) || is_page_template( 'page-pillar.php' ) ) && $post ) {
		$graph[]            = aq_schema_article( $post, $url, $graph );
		$page['mainEntity'] = array( '@id' => aquariende_schema_id( 'article', $url ) );
	} elseif ( is_page_template( 'template-glossary.php' ) ) {
		$graph[]            = aq_schema_termset( $url );
		$page['mainEntity'] = array( '@id' => aquariende_schema_id( 'glossary', $url ) );
	} elseif ( is_page_template( 'template-faq.php' ) ) {
		$page['mainEntity'] = aq_schema_faq( aquariende_faqs_for( '*' ) );
	} elseif ( is_singular( 'aq_person' ) && $post ) {
		$person = aq_schema_person( $post );
		if ( $person ) {
			$graph[]            = $person;
			$page['mainEntity'] = array( '@id' => $person['@id'] );
		}
	} elseif ( is_singular( 'post' ) && $post ) {
		$graph[] = array(
			'@type'            => 'BlogPosting',
			'@id'              => aquariende_schema_id( 'article', $url ),
			'headline'         => get_the_title( $post ),
			'url'              => $url,
			'mainEntityOfPage' => array( '@id' => aquariende_schema_id( 'webpage', $url ) ),
			'datePublished'    => get_post_time( 'c', true, $post ),
			'dateModified'     => get_post_modified_time( 'c', true, $post ),
			'author'           => array( '@id' => aquariende_schema_id( 'org' ) ),
			'publisher'        => array( '@id' => aquariende_schema_id( 'org' ) ),
			'inLanguage'       => 'en',
		);
	}

	// Team members shown on Home and About.
	if ( is_front_page() || is_page_template( 'template-about.php' ) ) {
		foreach ( aquariende_people() as $person ) {
			$node = aq_schema_person( $person );
			if ( $node ) {
				$graph[] = $node;
			}
		}
	}
	// Author and reviewer of a guide.
	if ( is_singular( 'aq_guide' ) && $post ) {
		$seen = array();
		foreach ( array( '_aq_author', '_aq_reviewer' ) as $meta ) {
			$person = aquariende_person_by_slug( get_post_meta( $post->ID, $meta, true ) );
			if ( $person && ! isset( $seen[ $person->ID ] ) ) {
				$seen[ $person->ID ] = true;
				$graph[]             = aq_schema_person( $person );
			}
		}
	}

	$graph[] = $page;
	$crumbs  = aq_schema_breadcrumbs( $url );
	if ( $crumbs ) {
		$graph[] = $crumbs;
	}
	return $graph;
}

function aquariende_output_schema() {
	if ( is_404() || is_search() ) {
		return;
	}
	if ( ! apply_filters( 'aquariende_output_schema', ! aquariende_has_seo_plugin() ) ) {
		return;
	}
	$data = array(
		'@context' => 'https://schema.org',
		'@graph'   => array_values( array_filter( aquariende_schema_graph() ) ),
	);
	echo '<script type="application/ld+json">' . wp_json_encode( $data, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE ) . '</script>' . "\n";
}
add_action( 'wp_head', 'aquariende_output_schema', 20 );
