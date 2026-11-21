<?php
/**
 * Content model (06 §3): guides, glossary terms, FAQ items and team members,
 * their taxonomies and their meta fields.
 *
 * @package Aquariende
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function aquariende_register_content_model() {
	register_post_type(
		'aq_guide',
		array(
			'labels'       => array(
				'name'          => 'Guides',
				'singular_name' => 'Guide',
				'add_new_item'  => 'Add new guide',
				'edit_item'     => 'Edit guide',
				'all_items'     => 'All guides',
			),
			'public'       => true,
			'has_archive'  => 'guides',
			'rewrite'      => array(
				'slug'       => 'guides',
				'with_front' => false,
			),
			'menu_icon'    => 'dashicons-book-alt',
			'menu_position' => 20,
			'supports'     => array( 'title', 'editor', 'excerpt', 'revisions' ),
			'show_in_rest' => true,
		)
	);

	register_post_type(
		'aq_term',
		array(
			'labels'             => array(
				'name'          => 'Glossary',
				'singular_name' => 'Glossary term',
				'add_new_item'  => 'Add new term',
				'edit_item'     => 'Edit term',
				'all_items'     => 'All terms',
			),
			'public'             => false,
			'publicly_queryable' => false,
			'show_ui'            => true,
			'show_in_rest'       => true,
			'menu_icon'          => 'dashicons-editor-spellcheck',
			'menu_position'      => 21,
			'supports'           => array( 'title', 'editor', 'excerpt', 'revisions' ),
		)
	);

	register_post_type(
		'aq_faq',
		array(
			'labels'             => array(
				'name'          => 'FAQ items',
				'singular_name' => 'FAQ item',
				'add_new_item'  => 'Add new question',
				'edit_item'     => 'Edit question',
				'all_items'     => 'All questions',
			),
			'public'             => false,
			'publicly_queryable' => false,
			'show_ui'            => true,
			'show_in_rest'       => true,
			'menu_icon'          => 'dashicons-editor-help',
			'menu_position'      => 22,
			'supports'           => array( 'title', 'editor', 'revisions', 'page-attributes' ),
		)
	);

	register_post_type(
		'aq_person',
		array(
			'labels'       => array(
				'name'          => 'Team',
				'singular_name' => 'Team member',
				'add_new_item'  => 'Add team member',
				'edit_item'     => 'Edit team member',
				'all_items'     => 'All team members',
			),
			'public'       => true,
			'has_archive'  => false,
			'rewrite'      => array(
				'slug'       => 'about/team',
				'with_front' => false,
			),
			'menu_icon'    => 'dashicons-groups',
			'menu_position' => 23,
			'supports'     => array( 'title', 'editor', 'thumbnail', 'revisions' ),
			'show_in_rest' => true,
		)
	);

	register_taxonomy(
		'aq_stage',
		array( 'aq_guide' ),
		array(
			'labels'            => array(
				'name'          => 'Journey stages',
				'singular_name' => 'Journey stage',
			),
			'public'            => false,
			'show_ui'           => true,
			'show_admin_column' => true,
			'show_in_rest'      => true,
			'hierarchical'      => true,
			'rewrite'           => false,
		)
	);

	register_taxonomy(
		'aq_faq_group',
		array( 'aq_faq' ),
		array(
			'labels'            => array(
				'name'          => 'FAQ groups',
				'singular_name' => 'FAQ group',
			),
			'public'            => false,
			'show_ui'           => true,
			'show_admin_column' => true,
			'show_in_rest'      => true,
			'hierarchical'      => true,
			'rewrite'           => false,
		)
	);

	register_term_meta(
		'aq_stage',
		'order',
		array(
			'type'         => 'integer',
			'single'       => true,
			'show_in_rest' => true,
		)
	);
	register_term_meta(
		'aq_faq_group',
		'order',
		array(
			'type'         => 'integer',
			'single'       => true,
			'show_in_rest' => true,
		)
	);

	foreach ( aquariende_meta_fields() as $post_type => $fields ) {
		foreach ( $fields as $key => $field ) {
			register_post_meta(
				$post_type,
				$key,
				array(
					'type'          => 'string',
					'single'        => true,
					'show_in_rest'  => true,
					'default'       => '',
					'auth_callback' => function () {
						return current_user_can( 'edit_posts' );
					},
				)
			);
		}
	}
}
add_action( 'init', 'aquariende_register_content_model', 5 );

/**
 * Meta fields per post type, with the label and help shown in the editor box.
 * All values are strings; lists are JSON or one item per line.
 *
 * @return array<string,array<string,array<string,string>>>
 */
function aquariende_meta_fields() {
	$page_common = array(
		'_aq_answer'     => array(
			'label' => 'Answer block (40–60 words, plain text, shown directly under the H1)',
			'type'  => 'answer',
		),
		'_aq_seo_title'  => array(
			'label' => 'SEO title (optional; " · Aquariende" is added)',
			'type'  => 'text',
		),
		'_aq_wa_code'    => array(
			'label' => 'WhatsApp page code (e.g. G-BLOCKED); ends the prefilled message as [CODE]',
			'type'  => 'text',
		),
		'_aq_wa_message' => array(
			'label' => 'WhatsApp prefilled message (empty = the general greeting)',
			'type'  => 'text',
		),
		'_aq_sources'    => array(
			'label' => 'Sources, one per line: Publisher | Title | URL | accessed YYYY-MM-DD',
			'type'  => 'sources',
		),
		'_aq_reviewed'   => array(
			'label' => 'Last reviewed (YYYY-MM-DD)',
			'type'  => 'date',
		),
	);

	return array(
		'page'      => $page_common,
		'aq_guide'  => array_merge(
			$page_common,
			array(
				'_aq_question'    => array(
					'label' => 'Primary question the guide answers',
					'type'  => 'text',
				),
				'_aq_next_review' => array(
					'label' => 'Next review due (YYYY-MM-DD; visa and blocked account every 3 months, others every 6)',
					'type'  => 'date',
				),
				'_aq_author'      => array(
					'label' => 'Written by (team member slug; shown only if that member gave consent)',
					'type'  => 'person',
				),
				'_aq_reviewer'    => array(
					'label' => 'Reviewed by (team member slug; shown only if that member gave consent)',
					'type'  => 'person',
				),
				'_aq_service'     => array(
					'label' => 'Related service: profile | application | visa',
					'type'  => 'text',
				),
				'_aq_priority'    => array(
					'label' => 'Order within its stage (1 = first)',
					'type'  => 'text',
				),
				'_aq_related'     => array(
					'label' => 'Related guides (content keys, comma separated)',
					'type'  => 'text',
				),
			)
		),
		'aq_term'   => array(
			'_aq_def'        => array(
				'label' => 'Definition (one sentence)',
				'type'  => 'textarea',
			),
			'_aq_why'        => array(
				'label' => 'Why it matters for an applicant from Bangladesh (one sentence)',
				'type'  => 'textarea',
			),
			'_aq_term_de'    => array(
				'label' => 'German original (optional)',
				'type'  => 'text',
			),
			'_aq_aliases'    => array(
				'label' => 'Other spellings linked to this term in guides (comma separated)',
				'type'  => 'text',
			),
			'_aq_source_url' => array(
				'label' => 'Official source URL',
				'type'  => 'url',
			),
			'_aq_source_label' => array(
				'label' => 'Official source name',
				'type'  => 'text',
			),
		),
		'aq_faq'    => array(
			'_aq_faq_pages' => array(
				'label' => 'Show on (content keys, comma separated, e.g. home, guide-visa)',
				'type'  => 'text',
			),
		),
		'aq_person' => array(
			'_aq_role'         => array(
				'label' => 'Role at Aquariende',
				'type'  => 'text',
			),
			'_aq_university'   => array(
				'label' => 'German university',
				'type'  => 'text',
			),
			'_aq_programme'    => array(
				'label' => 'Programme',
				'type'  => 'text',
			),
			'_aq_status'       => array(
				'label' => 'Status (e.g. "Student since 2022" or "Alumnus, 2024"); confirm with the member',
				'type'  => 'text',
			),
			'_aq_linkedin'     => array(
				'label' => 'LinkedIn URL (only with the member\'s consent)',
				'type'  => 'url',
			),
			'_aq_knows_about'  => array(
				'label' => 'Knows about (comma separated, e.g. uni-assist, German student visa)',
				'type'  => 'text',
			),
			'_aq_photo'        => array(
				'label' => 'Bundled photo file name in assets/img (without extension)',
				'type'  => 'text',
			),
			'_aq_consent'      => array(
				'label' => 'This person has given written consent to show their name, photo, university and LinkedIn, and to be listed as an author or reviewer',
				'type'  => 'checkbox',
			),
		),
	);
}

/**
 * Whether a team member may be shown anywhere (published + consent).
 *
 * @param int|WP_Post $person Post.
 * @return bool
 */
function aquariende_person_ok( $person ) {
	$post = get_post( $person );
	return $post && 'aq_person' === $post->post_type && 'publish' === $post->post_status && '1' === (string) get_post_meta( $post->ID, '_aq_consent', true );
}

/**
 * Team members who may be shown.
 *
 * @return WP_Post[]
 */
function aquariende_people() {
	$people = get_posts(
		array(
			'post_type'   => 'aq_person',
			'post_status' => 'publish',
			'numberposts' => 20,
			'orderby'     => 'menu_order title',
			'order'       => 'ASC',
		)
	);
	return array_values( array_filter( $people, 'aquariende_person_ok' ) );
}

/**
 * A consented team member by slug.
 *
 * @param string $slug Post slug.
 * @return WP_Post|null
 */
function aquariende_person_by_slug( $slug ) {
	if ( '' === (string) $slug ) {
		return null;
	}
	$post = get_page_by_path( sanitize_title( $slug ), OBJECT, 'aq_person' );
	return ( $post && aquariende_person_ok( $post ) ) ? $post : null;
}

/**
 * Sources of a page or guide, decoded.
 *
 * @param int $post_id Post ID.
 * @return array<int,array<string,string>>
 */
function aquariende_post_sources( $post_id ) {
	$raw = get_post_meta( $post_id, '_aq_sources', true );
	$out = json_decode( (string) $raw, true );
	if ( ! is_array( $out ) ) {
		return array();
	}
	return array_values(
		array_filter(
			$out,
			function ( $s ) {
				return is_array( $s ) && ! empty( $s['url'] ) && wp_http_validate_url( $s['url'] );
			}
		)
	);
}

/**
 * Guides of one journey stage, ordered by priority.
 *
 * @param string $stage Stage slug.
 * @return WP_Post[]
 */
function aquariende_stage_guides( $stage ) {
	$guides = get_posts(
		array(
			'post_type'   => 'aq_guide',
			'post_status' => 'publish',
			'numberposts' => 50,
			'tax_query'   => array( // phpcs:ignore WordPress.DB.SlowDBQuery
				array(
					'taxonomy' => 'aq_stage',
					'field'    => 'slug',
					'terms'    => $stage,
				),
			),
		)
	);
	usort(
		$guides,
		function ( $a, $b ) {
			return (int) get_post_meta( $a->ID, '_aq_priority', true ) - (int) get_post_meta( $b->ID, '_aq_priority', true );
		}
	);
	return $guides;
}

/**
 * All guides in journey order.
 *
 * @return WP_Post[]
 */
function aquariende_guides_in_order() {
	$all = array();
	foreach ( array_keys( aquariende_journey() ) as $stage ) {
		foreach ( aquariende_stage_guides( $stage ) as $guide ) {
			$all[ $guide->ID ] = $guide;
		}
	}
	return array_values( $all );
}

/**
 * Journey stage slug of a guide.
 *
 * @param int $post_id Guide ID.
 * @return string
 */
function aquariende_guide_stage( $post_id ) {
	$terms = get_the_terms( $post_id, 'aq_stage' );
	return ( $terms && ! is_wp_error( $terms ) ) ? $terms[0]->slug : '';
}
