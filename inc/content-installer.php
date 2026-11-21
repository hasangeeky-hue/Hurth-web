<?php
/**
 * Content installer: puts the Aquariende pages, guides, glossary, FAQ items,
 * team records, menus and reading settings into WordPress from the theme, so
 * a Git deploy is all a site needs. No WP-CLI, no plugins.
 *
 * When it runs
 * - on after_switch_theme (the theme is activated);
 * - on admin_init for an administrator, when the option
 *   aquariende_content_version is lower than AQUARIENDE_CONTENT_VERSION
 *   (so a Git redeploy of an already active theme triggers it);
 * - after a Deployer for Git package install (dfg_after_package_install);
 * - when "Run installer again" is pressed under Appearance → Aquariende content.
 *
 * What it does (safe to repeat)
 * - Every item carries the meta _aquariende_content_key and is found by it,
 *   never by title or slug: nothing is duplicated, and nothing that belongs
 *   to someone else is touched.
 * - Missing items are created. An item in the trash is left there.
 * - When the installer version has increased, an existing item is updated
 *   only if nobody edited it since it was installed: its text hash
 *   (_aquariende_content_hash, same formula as version 1) and its field hash
 *   (_aquariende_meta_hash) must still match.
 * - Any item whose title, text, excerpt or fields contain a money amount or a
 *   currency symbol is refused and logged (06 §3: no hard-coded amounts).
 * - Version 2 upgrade: the three 2022 posts go to draft (unless edited); pages
 *   listed in inc/content/retired.php go to the trash (only if keyed and
 *   unedited); old URLs redirect through inc/content/redirects.php.
 *
 * Moving the previous site's content away is a separate, manual action with
 * a confirm step, unchanged from version 1.
 *
 * @package Aquariende
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/*
 * Raise this number whenever anything in inc/content/, content/ changes. The
 * next admin visit after the deploy then installs the change.
 */
define( 'AQUARIENDE_CONTENT_VERSION', 2 );
define( 'AQUARIENDE_CONTENT_META', '_aquariende_content_key' );
define( 'AQUARIENDE_CONTENT_HASH', '_aquariende_content_hash' );
define( 'AQUARIENDE_CONTENT_MHASH', '_aquariende_meta_hash' );

/* -------------------------------------------------------------------------
 * Data and small helpers
 * ---------------------------------------------------------------------- */

/**
 * Load one content file from inc/content/.
 *
 * @param string $name File name without extension.
 * @return array
 */
function aquariende_content_data( $name ) {
	static $cache = array();
	if ( ! isset( $cache[ $name ] ) ) {
		$file           = AQUARIENDE_DIR . '/inc/content/' . $name . '.php';
		$data           = is_readable( $file ) ? include $file : array();
		$cache[ $name ] = is_array( $data ) ? $data : array();
	}
	return $cache[ $name ];
}

/**
 * Body of an HTML content file, without its header comment.
 *
 * @param string $relative Path relative to the theme folder.
 * @return string
 */
function aquariende_content_post_body( $relative ) {
	$file = AQUARIENDE_DIR . '/' . ltrim( $relative, '/' );
	if ( ! is_readable( $file ) ) {
		return '';
	}
	$html = (string) file_get_contents( $file ); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents -- local theme file.
	$html = preg_replace( '/^\s*<!--.*?-->\s*/s', '', $html );
	return trim( $html );
}

/**
 * Append lines to the log option (last 120 lines are kept).
 *
 * @param string|string[] $lines Line or lines.
 */
function aquariende_content_log( $lines ) {
	$log = get_option( 'aquariende_content_log', array() );
	if ( ! is_array( $log ) ) {
		$log = array();
	}
	foreach ( (array) $lines as $line ) {
		$log[] = wp_date( 'Y-m-d H:i:s' ) . '  ' . $line;
	}
	update_option( 'aquariende_content_log', array_slice( $log, -120 ), false );
}

/**
 * Text hash of an item as stored (same formula as version 1).
 *
 * @param int $post_id Post ID.
 * @return string
 */
function aquariende_content_hash_of( $post_id ) {
	$post = get_post( $post_id );
	if ( ! $post ) {
		return '';
	}
	return md5( $post->post_title . "\n" . $post->post_excerpt . "\n" . $post->post_content );
}

/**
 * Hash of the installer-managed fields of an item, as stored.
 *
 * @param int      $post_id Post ID.
 * @param string[] $keys    Meta keys.
 * @return string
 */
function aquariende_content_meta_hash( $post_id, $keys ) {
	$values = array();
	sort( $keys );
	foreach ( $keys as $key ) {
		$values[ $key ] = (string) get_post_meta( $post_id, $key, true );
	}
	return md5( wp_json_encode( $values ) );
}

/**
 * Find an installer item by its key (any status, including trash).
 *
 * @param string $key       Content key.
 * @param string $post_type Post type.
 * @return WP_Post|null
 */
function aquariende_content_find( $key, $post_type ) {
	$found = get_posts(
		array(
			'post_type'        => $post_type,
			'post_status'      => array( 'publish', 'draft', 'pending', 'private', 'future', 'trash' ),
			'meta_key'         => AQUARIENDE_CONTENT_META, // phpcs:ignore WordPress.DB.SlowDBQuery
			'meta_value'       => $key, // phpcs:ignore WordPress.DB.SlowDBQuery
			'numberposts'      => 1,
			'orderby'          => 'ID',
			'order'            => 'ASC',
			'suppress_filters' => true,
		)
	);
	return $found ? $found[0] : null;
}

/**
 * ID of an installed item by key, when it is published.
 *
 * @param string $key Content key.
 * @return int 0 when unknown or not published.
 */
function aquariende_content_id( $key ) {
	$ids = get_option( 'aquariende_content_ids', array() );
	$id  = ( is_array( $ids ) && isset( $ids[ $key ] ) ) ? (int) $ids[ $key ] : 0;
	return ( $id && 'publish' === get_post_status( $id ) ) ? $id : 0;
}

/**
 * Author for new items: the current user, else the first administrator.
 *
 * @return int
 */
function aquariende_content_author() {
	$user = get_current_user_id();
	if ( $user ) {
		return $user;
	}
	$admins = get_users(
		array(
			'role'    => 'administrator',
			'number'  => 1,
			'orderby' => 'ID',
			'fields'  => 'ID',
		)
	);
	return $admins ? (int) $admins[0] : 0;
}

/**
 * The money rule: no amount and no currency symbol in any installed text.
 *
 * @param string[] $texts Texts to check.
 * @return string The first offending match, or '' when clean.
 */
function aquariende_content_money_check( $texts ) {
	$patterns = array(
		'/[€৳£₹¥$]/u',
		'/\d[\d.,]*\s?(?:EUR|euros?|BDT|Tk\.?|taka|lakhs?|crores?|USD|dollars?)(?![\w])/iu',
		'/(?<![\w])(?:EUR|BDT|Tk|USD)\s?\d/iu',
	);
	foreach ( $texts as $text ) {
		$text = html_entity_decode( wp_strip_all_tags( (string) $text ), ENT_QUOTES, 'UTF-8' );
		foreach ( $patterns as $pattern ) {
			if ( preg_match( $pattern, $text, $m ) ) {
				return $m[0];
			}
		}
	}
	return '';
}

/**
 * Sources JSON from source keys.
 *
 * @param string[] $keys Keys of aquariende_sources().
 * @return string
 */
function aquariende_content_sources_json( $keys ) {
	$all = aquariende_sources();
	$out = array();
	foreach ( (array) $keys as $key ) {
		if ( isset( $all[ $key ] ) ) {
			$out[] = $all[ $key ];
		}
	}
	return $out ? wp_json_encode( $out, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE ) : '';
}

/* -------------------------------------------------------------------------
 * Install
 * ---------------------------------------------------------------------- */

/**
 * Create or update one item of any post type.
 *
 * @param string $key     Content key.
 * @param array  $postarr Fields for wp_insert_post (unslashed).
 * @param array  $meta    Installer-managed meta.
 * @param bool   $upgrade Whether the installer version increased.
 * @param array  $log     Log lines, by reference.
 * @param array  $terms   taxonomy => slugs.
 * @return int Post ID or 0.
 */
function aquariende_content_put( $key, $postarr, $meta, $upgrade, &$log, $terms = array() ) {
	$type     = $postarr['post_type'];
	$obj      = get_post_type_object( $type );
	$label    = $obj ? strtolower( $obj->labels->singular_name ) : $type;
	$existing = aquariende_content_find( $key, $type );

	$money = aquariende_content_money_check( array_merge( array( $postarr['post_title'], $postarr['post_content'], isset( $postarr['post_excerpt'] ) ? $postarr['post_excerpt'] : '' ), array_values( $meta ) ) );
	if ( '' !== $money ) {
		$log[] = sprintf( 'REFUSED %1$s "%2$s" (%3$s): it contains a money amount or currency symbol ("%4$s"). Amounts belong in a VolatileFact card, never in the text.', $label, $postarr['post_title'], $key, $money );
		return $existing ? (int) $existing->ID : 0;
	}

	if ( $existing && 'trash' === $existing->post_status ) {
		$log[] = sprintf( 'Skipped %1$s "%2$s" (#%3$d): it is in the trash. Restore it, or delete it permanently to have it recreated.', $label, $existing->post_title, $existing->ID );
		return 0;
	}

	$meta_keys = array_keys( $meta );

	if ( ! $existing ) {
		$postarr['meta_input'] = array_merge( $meta, array( AQUARIENDE_CONTENT_META => $key ) );
		$id                    = wp_insert_post( wp_slash( $postarr ), true );
		if ( is_wp_error( $id ) ) {
			$log[] = sprintf( 'Could not create %1$s "%2$s": %3$s', $label, $postarr['post_title'], $id->get_error_message() );
			return 0;
		}
		foreach ( $terms as $taxonomy => $slugs ) {
			wp_set_object_terms( $id, $slugs, $taxonomy );
		}
		update_post_meta( $id, AQUARIENDE_CONTENT_HASH, aquariende_content_hash_of( $id ) );
		update_post_meta( $id, AQUARIENDE_CONTENT_MHASH, aquariende_content_meta_hash( $id, $meta_keys ) );
		update_post_meta( $id, '_aquariende_meta_keys', $meta_keys );
		$log[] = sprintf( 'Created %1$s "%2$s" (#%3$d, %4$s).', $label, $postarr['post_title'], $id, wp_make_link_relative( (string) get_permalink( $id ) ) );
		return (int) $id;
	}

	$id = (int) $existing->ID;
	if ( ! $upgrade ) {
		return $id;
	}

	$stored  = (string) get_post_meta( $id, AQUARIENDE_CONTENT_HASH, true );
	$mstored = (string) get_post_meta( $id, AQUARIENDE_CONTENT_MHASH, true );
	// Version 1 items have no field hash: their fields count as unedited.
	$mkeys_old = get_post_meta( $id, '_aquariende_meta_keys', true );
	$mkeys_old = is_array( $mkeys_old ) ? $mkeys_old : $meta_keys;
	$edited    = ( $stored && aquariende_content_hash_of( $id ) !== $stored )
		|| ( $mstored && aquariende_content_meta_hash( $id, $mkeys_old ) !== $mstored );

	if ( $edited ) {
		$log[] = sprintf( 'Kept %1$s "%2$s" (#%3$d) as it is: it was edited after it was installed.', $label, $existing->post_title, $id );
		return $id;
	}

	$changed = $existing->post_title !== $postarr['post_title']
		|| $existing->post_content !== $postarr['post_content']
		|| $existing->post_excerpt !== ( isset( $postarr['post_excerpt'] ) ? $postarr['post_excerpt'] : '' )
		|| ( isset( $postarr['menu_order'] ) && (int) $existing->menu_order !== (int) $postarr['menu_order'] )
		|| ( isset( $postarr['post_parent'] ) && (int) $existing->post_parent !== (int) $postarr['post_parent'] );
	foreach ( $meta as $meta_key => $value ) {
		if ( (string) get_post_meta( $id, $meta_key, true ) !== (string) $value ) {
			$changed = true;
		}
	}
	foreach ( $terms as $taxonomy => $slugs ) {
		$current = wp_get_object_terms( $id, $taxonomy, array( 'fields' => 'slugs' ) );
		if ( is_wp_error( $current ) || array_diff( (array) $slugs, $current ) || array_diff( $current, (array) $slugs ) ) {
			$changed = true;
		}
	}

	if ( $changed ) {
		$update = array(
			'ID'           => $id,
			'post_title'   => $postarr['post_title'],
			'post_content' => $postarr['post_content'],
			'post_excerpt' => isset( $postarr['post_excerpt'] ) ? $postarr['post_excerpt'] : '',
		);
		if ( isset( $postarr['menu_order'] ) ) {
			$update['menu_order'] = (int) $postarr['menu_order'];
		}
		if ( isset( $postarr['post_parent'] ) ) {
			$update['post_parent'] = (int) $postarr['post_parent'];
		}
		wp_update_post( wp_slash( $update ) );
		foreach ( $meta as $meta_key => $value ) {
			update_post_meta( $id, $meta_key, wp_slash( $value ) );
		}
		foreach ( $terms as $taxonomy => $slugs ) {
			wp_set_object_terms( $id, $slugs, $taxonomy );
		}
		$log[] = sprintf( 'Updated %1$s "%2$s" (#%3$d) to the current text.', $label, $postarr['post_title'], $id );
	}
	update_post_meta( $id, AQUARIENDE_CONTENT_HASH, aquariende_content_hash_of( $id ) );
	update_post_meta( $id, AQUARIENDE_CONTENT_MHASH, aquariende_content_meta_hash( $id, $meta_keys ) );
	update_post_meta( $id, '_aquariende_meta_keys', $meta_keys );
	return $id;
}

/**
 * Retire an installer item: draft or trash it, only if keyed and unedited.
 *
 * @param string $key     Content key.
 * @param string $type    Post type.
 * @param string $mode    draft|trash.
 * @param array  $log     Log lines, by reference.
 * @return bool Whether it was retired now.
 */
function aquariende_content_retire( $key, $type, $mode, &$log ) {
	$item = aquariende_content_find( $key, $type );
	if ( ! $item || 'trash' === $item->post_status || ( 'draft' === $mode && 'draft' === $item->post_status ) ) {
		return false;
	}
	$stored = (string) get_post_meta( $item->ID, AQUARIENDE_CONTENT_HASH, true );
	if ( ! $stored || aquariende_content_hash_of( $item->ID ) !== $stored ) {
		$log[] = sprintf( 'Kept %1$s "%2$s" (#%3$d) published: it should be retired, but it was edited after it was installed. Retire it by hand if you agree.', $type, $item->post_title, $item->ID );
		return false;
	}
	if ( 'trash' === $mode ) {
		wp_trash_post( $item->ID );
		$log[] = sprintf( 'Moved %1$s "%2$s" (#%3$d) to the trash: it is no longer part of the site.', $type, $item->post_title, $item->ID );
	} else {
		wp_update_post(
			array(
				'ID'          => $item->ID,
				'post_status' => 'draft',
			)
		);
		update_post_meta( $item->ID, AQUARIENDE_CONTENT_HASH, aquariende_content_hash_of( $item->ID ) );
		$log[] = sprintf( 'Set %1$s "%2$s" (#%3$d) to draft: retired, and its address now redirects.', $type, $item->post_title, $item->ID );
	}
	return true;
}

/**
 * Make sure a taxonomy term exists with an order.
 *
 * @param string $taxonomy Taxonomy.
 * @param string $slug     Slug.
 * @param string $name     Name.
 * @param int    $order    Order.
 * @param array  $log      Log lines, by reference.
 */
function aquariende_content_term( $taxonomy, $slug, $name, $order, &$log ) {
	$term = term_exists( $slug, $taxonomy );
	if ( ! $term ) {
		$term = wp_insert_term( $name, $taxonomy, array( 'slug' => $slug ) );
		if ( is_wp_error( $term ) ) {
			$log[] = sprintf( 'Could not create term "%1$s": %2$s', $name, $term->get_error_message() );
			return;
		}
		$log[] = sprintf( 'Created %1$s "%2$s".', $taxonomy, $name );
	}
	update_term_meta( (int) $term['term_id'], 'order', (int) $order );
}

/**
 * Give installer items their intended slug back once it is free.
 *
 * @param array $log Log lines, by reference.
 */
function aquariende_content_fix_slugs( &$log ) {
	$wanted = array();
	foreach ( aquariende_content_data( 'pages' ) as $key => $page ) {
		$wanted[ $key ] = array( 'page', $key );
	}
	foreach ( aquariende_content_data( 'guides' ) as $key => $guide ) {
		$wanted[ $key ] = array( 'aq_guide', $guide['slug'] );
	}
	foreach ( $wanted as $key => $want ) {
		$item = aquariende_content_find( $key, $want[0] );
		if ( ! $item || 'trash' === $item->post_status || $item->post_name === $want[1] ) {
			continue;
		}
		$free = wp_unique_post_slug( $want[1], $item->ID, 'publish', $want[0], (int) $item->post_parent );
		if ( $free === $want[1] ) {
			wp_update_post(
				array(
					'ID'        => $item->ID,
					'post_name' => $want[1],
				)
			);
			$log[] = sprintf( 'Slug of "%1$s" changed from /%2$s/ to /%3$s/.', $item->post_title, $item->post_name, $want[1] );
		} else {
			$log[] = sprintf( '"%1$s" stays at /%2$s/ for now: /%3$s/ belongs to another item (moving the old content to the trash frees it).', $item->post_title, $item->post_name, $want[1] );
		}
	}
}

/**
 * Signature of one menu item, for edit detection.
 *
 * @param WP_Post $item Menu item.
 * @return string
 */
function aquariende_content_menu_item_sig( $item ) {
	// Permalinks are left out: they change when a slug is freed, which is not an edit.
	return $item->menu_order . '|' . $item->type . '|' . $item->object_id . '|' . $item->post_title . '|' . ( 'custom' === $item->type ? wp_make_link_relative( $item->url ) : '' );
}

/**
 * Find or create an installer menu and fill it (two levels).
 *
 * @param string $key     Menu key.
 * @param array  $menu    Menu definition.
 * @param array  $ids     Content key => post ID.
 * @param bool   $upgrade Whether the installer version increased.
 * @param array  $log     Log lines, by reference.
 * @return int Menu term ID or 0.
 */
function aquariende_content_put_menu( $key, $menu, $ids, $upgrade, &$log ) {
	$menu_id = 0;
	foreach ( wp_get_nav_menus() as $term ) {
		if ( get_term_meta( $term->term_id, AQUARIENDE_CONTENT_META, true ) === $key ) {
			$menu_id = (int) $term->term_id;
			break;
		}
	}

	$created = false;
	if ( ! $menu_id ) {
		$name   = $menu['name'];
		$result = wp_create_nav_menu( $name );
		if ( is_wp_error( $result ) ) {
			$name   = sprintf( '%s (Aquariende)', $menu['name'] );
			$result = wp_create_nav_menu( $name );
		}
		if ( is_wp_error( $result ) ) {
			$log[] = sprintf( 'Could not create menu "%1$s": %2$s', $menu['name'], $result->get_error_message() );
			return 0;
		}
		$menu_id = (int) $result;
		update_term_meta( $menu_id, AQUARIENDE_CONTENT_META, $key );
		$created = true;
		$log[]   = sprintf( 'Created menu "%1$s" (#%2$d).', $name, $menu_id );
	}

	$items     = wp_get_nav_menu_items( $menu_id, array( 'post_status' => 'any' ) );
	$items     = is_array( $items ) ? $items : array();
	$signature = md5( wp_json_encode( array_map( 'aquariende_content_menu_item_sig', $items ) ) );
	$stored    = get_term_meta( $menu_id, AQUARIENDE_CONTENT_HASH, true );

	$rebuild = $created || ! $items || ( $upgrade && ( ! $stored || $stored === $signature ) );
	if ( ! $rebuild ) {
		if ( $upgrade ) {
			$log[] = sprintf( 'Kept menu "%s" as it is: it was edited after it was installed.', $menu['name'] );
		}
		return $menu_id;
	}

	foreach ( $items as $item ) {
		wp_delete_post( $item->ID, true ); // Our own menu items only.
	}

	$position = 1;
	$add      = function ( $item, $parent ) use ( $menu_id, $ids, &$position ) {
		$args = array(
			'menu-item-title'     => $item['label'],
			'menu-item-status'    => 'publish',
			'menu-item-position'  => $position,
			'menu-item-parent-id' => $parent,
		);
		if ( 'guides' === $item['page'] ) {
			$args['menu-item-type'] = 'custom';
			$args['menu-item-url']  = home_url( '/guides/' );
		} else {
			$page_id = isset( $ids[ $item['page'] ] ) ? (int) $ids[ $item['page'] ] : 0;
			if ( ! $page_id || 'publish' !== get_post_status( $page_id ) ) {
				return 0;
			}
			$args['menu-item-object']    = 'page';
			$args['menu-item-object-id'] = $page_id;
			$args['menu-item-type']      = 'post_type';
		}
		++$position;
		$new = wp_update_nav_menu_item( $menu_id, 0, $args );
		return is_wp_error( $new ) ? 0 : (int) $new;
	};
	foreach ( $menu['items'] as $item ) {
		$parent_id = $add( $item, 0 );
		if ( $parent_id && ! empty( $item['children'] ) ) {
			foreach ( $item['children'] as $child ) {
				$add( $child, $parent_id );
			}
		}
	}

	$items = wp_get_nav_menu_items( $menu_id, array( 'post_status' => 'any' ) );
	update_term_meta( $menu_id, AQUARIENDE_CONTENT_HASH, md5( wp_json_encode( array_map( 'aquariende_content_menu_item_sig', is_array( $items ) ? $items : array() ) ) ) );
	$log[] = sprintf( 'Filled menu "%1$s" with %2$d links.', $menu['name'], $position - 1 );
	return $menu_id;
}

/**
 * Run the installer.
 *
 * @param string $reason Why it runs (for the log).
 * @return bool False when another run holds the lock.
 */
function aquariende_content_install( $reason = '' ) {
	if ( ! add_option( 'aquariende_content_lock', time(), '', false ) ) {
		$since = (int) get_option( 'aquariende_content_lock' );
		if ( $since && ( time() - $since ) < 300 ) {
			return false;
		}
		update_option( 'aquariende_content_lock', time(), false );
	}

	if ( ! post_type_exists( 'aq_guide' ) ) {
		aquariende_register_content_model();
	}

	$installed = (int) get_option( 'aquariende_content_version', 0 );
	$upgrade   = $installed < AQUARIENDE_CONTENT_VERSION;
	$author    = aquariende_content_author();
	$log       = array();
	$log[]     = sprintf( 'Installer started (%1$s). Installed content version: %2$d, installer version: %3$d.', $reason ? $reason : 'manual', $installed, AQUARIENDE_CONTENT_VERSION );

	$ids = get_option( 'aquariende_content_ids', array() );
	$ids = is_array( $ids ) ? $ids : array();

	// Taxonomy terms.
	$n = 0;
	foreach ( aquariende_journey() as $slug => $stage ) {
		aquariende_content_term( 'aq_stage', $slug, $stage['label'], ++$n, $log );
	}
	$faq_data = aquariende_content_data( 'faqs' );
	foreach ( $faq_data['groups'] as $slug => $group ) {
		aquariende_content_term( 'aq_faq_group', $slug, $group['name'], $group['order'], $log );
	}

	// Pages (parents come before their children in pages.php).
	foreach ( aquariende_content_data( 'pages' ) as $key => $page ) {
		$content = isset( $page['file'] ) ? aquariende_content_post_body( $page['file'] ) : trim( (string) $page['content'] );
		$meta    = isset( $page['meta'] ) ? $page['meta'] : array();
		$meta['_wp_page_template'] = $page['template'];
		if ( ! empty( $page['sources'] ) ) {
			$meta['_aq_sources'] = aquariende_content_sources_json( $page['sources'] );
		}
		$postarr = array(
			'post_type'      => 'page',
			'post_status'    => 'publish',
			'post_title'     => $page['title'],
			'post_name'      => $key,
			'post_content'   => $content,
			'post_excerpt'   => $page['excerpt'],
			'menu_order'     => (int) $page['order'],
			'post_author'    => $author,
			'comment_status' => 'closed',
			'ping_status'    => 'closed',
		);
		if ( ! empty( $page['parent'] ) ) {
			$postarr['post_parent'] = isset( $ids[ $page['parent'] ] ) ? (int) $ids[ $page['parent'] ] : 0;
		}
		$id = aquariende_content_put( $key, $postarr, $meta, $upgrade, $log );
		if ( $id ) {
			$ids[ $key ] = $id;
		}
	}

	// Guides.
	foreach ( aquariende_content_data( 'guides' ) as $key => $guide ) {
		$meta = array_merge(
			$guide['meta'],
			array(
				'_aq_service'  => $guide['service'],
				'_aq_priority' => (string) $guide['priority'],
				'_aq_related'  => $guide['related'],
				'_aq_sources'  => aquariende_content_sources_json( $guide['sources'] ),
			)
		);
		$id   = aquariende_content_put(
			$key,
			array(
				'post_type'      => 'aq_guide',
				'post_status'    => 'publish',
				'post_title'     => $guide['title'],
				'post_name'      => $guide['slug'],
				'post_content'   => aquariende_content_post_body( 'content/guides/' . $guide['slug'] . '.html' ),
				'post_excerpt'   => $guide['excerpt'],
				'post_author'    => $author,
				'comment_status' => 'closed',
				'ping_status'    => 'closed',
			),
			$meta,
			$upgrade,
			$log,
			array( 'aq_stage' => array( $guide['stage'] ) )
		);
		if ( $id ) {
			$ids[ $key ] = $id;
		}
	}

	// Glossary terms.
	foreach ( aquariende_content_data( 'terms' ) as $key => $term ) {
		$id = aquariende_content_put(
			$key,
			array(
				'post_type'    => 'aq_term',
				'post_status'  => 'publish',
				'post_title'   => $term['title'],
				'post_name'    => $term['slug'],
				'post_content' => '',
				'post_excerpt' => $term['def'],
				'post_author'  => $author,
			),
			array(
				'_aq_def'          => $term['def'],
				'_aq_why'          => $term['why'],
				'_aq_term_de'      => $term['de'],
				'_aq_aliases'      => $term['aliases'],
				'_aq_source_url'   => $term['source']['url'],
				'_aq_source_label' => trim( $term['source']['publisher'] . ': ' . $term['source']['title'], ': ' ),
			),
			$upgrade,
			$log
		);
		if ( $id ) {
			$ids[ $key ] = $id;
		}
	}

	// FAQ items.
	foreach ( $faq_data['items'] as $key => $faq ) {
		$id = aquariende_content_put(
			$key,
			array(
				'post_type'    => 'aq_faq',
				'post_status'  => 'publish',
				'post_title'   => $faq['q'],
				'post_name'    => $key,
				'post_content' => $faq['a'],
				'post_excerpt' => '',
				'menu_order'   => (int) $faq['order'],
				'post_author'  => $author,
			),
			array( '_aq_faq_pages' => $faq['pages'] ),
			$upgrade,
			$log,
			array( 'aq_faq_group' => array( $faq['group'] ) )
		);
		if ( $id ) {
			$ids[ $key ] = $id;
		}
	}

	// Team members: drafts without consent. Nothing about them is public
	// until the owner records consent and publishes them (06 §3).
	foreach ( aquariende_team_seed() as $i => $person ) {
		$id = aquariende_content_put(
			$person['key'],
			array(
				'post_type'    => 'aq_person',
				'post_status'  => 'draft',
				'post_title'   => $person['name'],
				'post_name'    => $person['slug'],
				'post_content' => '',
				'post_excerpt' => '',
				'menu_order'   => $i,
				'post_author'  => $author,
			),
			array(
				'_aq_role'       => $person['role'],
				'_aq_university' => $person['university'],
				'_aq_programme'  => $person['programme'],
				'_aq_photo'      => $person['photo'],
			),
			$upgrade,
			$log
		);
		if ( $id ) {
			$ids[ $person['key'] ] = $id;
		}
	}

	update_option( 'aquariende_content_ids', $ids, true );

	// Retire: posts marked draft (never created, only moved), pages listed in retired.php.
	if ( $upgrade ) {
		foreach ( aquariende_content_data( 'posts' ) as $key => $post ) {
			if ( isset( $post['status'] ) && 'draft' === $post['status'] ) {
				aquariende_content_retire( $key, 'post', 'draft', $log );
			}
		}
		$retired = aquariende_content_data( 'retired' );
		$retired = (array) apply_filters( 'aquariende_content_retired_pages', isset( $retired['pages'] ) ? $retired['pages'] : array() );
		foreach ( $retired as $key ) {
			if ( aquariende_content_retire( $key, 'page', 'trash', $log ) ) {
				unset( $ids[ $key ] );
			}
		}
		update_option( 'aquariende_content_ids', $ids, true );
	}

	aquariende_content_fix_slugs( $log );

	// Menus and their locations.
	$menus_data = aquariende_content_data( 'menus' );
	$locations  = get_theme_mod( 'nav_menu_locations', array() );
	$locations  = is_array( $locations ) ? $locations : array();
	foreach ( $menus_data['menus'] as $key => $menu ) {
		$menu_id = aquariende_content_put_menu( $key, $menu, $ids, $upgrade, $log );
		if ( $menu_id && ( ! isset( $locations[ $menu['location'] ] ) || (int) $locations[ $menu['location'] ] !== $menu_id ) ) {
			$locations[ $menu['location'] ] = $menu_id;
			$log[]                          = sprintf( 'Assigned menu "%1$s" to the "%2$s" location.', $menu['name'], $menu['location'] );
		}
	}
	set_theme_mod( 'nav_menu_locations', $locations );

	// Reading settings, identity and permalinks. The admin email is not touched.
	$settings = array(
		'blogname'        => 'Aquariende',
		'blogdescription' => aquariende_info( 'positioning_short' ),
		'show_on_front'   => 'page',
	);
	if ( isset( $ids['home'] ) ) {
		$settings['page_on_front'] = (int) $ids['home'];
	}
	if ( isset( $ids['blog'] ) ) {
		$settings['page_for_posts'] = (int) $ids['blog'];
	}
	if ( isset( $ids['privacy-policy'] ) ) {
		$settings['wp_page_for_privacy_policy'] = (int) $ids['privacy-policy'];
	}
	foreach ( $settings as $option => $value ) {
		if ( (string) get_option( $option ) !== (string) $value ) {
			update_option( $option, $value );
			$log[] = sprintf( 'Set %1$s to "%2$s".', $option, $value );
		}
	}

	if ( '/%postname%/' !== get_option( 'permalink_structure' ) ) {
		global $wp_rewrite;
		$wp_rewrite->set_permalink_structure( '/%postname%/' );
		$log[] = 'Set the permalink structure to /%postname%/.';
	}
	flush_rewrite_rules();

	update_option( 'aquariende_content_version', AQUARIENDE_CONTENT_VERSION, true );
	if ( $upgrade ) {
		update_option( 'aquariende_content_notice', AQUARIENDE_CONTENT_VERSION, false );
	}
	update_option( 'aquariende_content_last_run', time(), false );

	$log[] = 'Installer finished.';
	aquariende_content_log( $log );

	if ( function_exists( 'aquariende_purge_caches' ) ) {
		aquariende_purge_caches();
	}

	delete_option( 'aquariende_content_lock' );
	return true;
}

function aquariende_content_on_switch() {
	aquariende_content_install( 'theme activated' );
}
add_action( 'after_switch_theme', 'aquariende_content_on_switch', 20 );

/**
 * After a Git redeploy: run once for an administrator when the version rose.
 */
function aquariende_content_maybe_install() {
	if ( wp_doing_ajax() || ( defined( 'WP_INSTALLING' ) && WP_INSTALLING ) ) {
		return;
	}
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}
	if ( (int) get_option( 'aquariende_content_version', 0 ) >= AQUARIENDE_CONTENT_VERSION ) {
		return;
	}
	aquariende_content_install( 'new installer version after deploy' );
}
add_action( 'admin_init', 'aquariende_content_maybe_install', 5 );

function aquariende_content_after_deploy() {
	if ( (int) get_option( 'aquariende_content_version', 0 ) < AQUARIENDE_CONTENT_VERSION ) {
		aquariende_content_install( 'Deployer for Git' );
	}
}
add_action( 'dfg_after_package_install', 'aquariende_content_after_deploy', 30 );

/* -------------------------------------------------------------------------
 * Moving the old site's content to the trash (manual, with confirmation)
 * ---------------------------------------------------------------------- */

/**
 * Pages and posts that do not belong to the installer.
 *
 * @return WP_Post[]
 */
function aquariende_content_foreign_posts() {
	$keep = array_filter( array( (int) get_option( 'page_on_front' ), (int) get_option( 'page_for_posts' ) ) );
	return get_posts(
		array(
			'post_type'        => array( 'page', 'post' ),
			'post_status'      => array( 'publish', 'draft', 'pending', 'private', 'future' ),
			'numberposts'      => -1,
			'orderby'          => 'post_type title',
			'order'            => 'ASC',
			'post__not_in'     => $keep, // phpcs:ignore WordPressVIPMinimum.Performance.WPQueryParams
			'suppress_filters' => true,
			'meta_query'       => array( // phpcs:ignore WordPress.DB.SlowDBQuery
				array(
					'key'     => AQUARIENDE_CONTENT_META,
					'compare' => 'NOT EXISTS',
				),
			),
		)
	);
}

/**
 * Menus that do not belong to the installer.
 *
 * @return WP_Term[]
 */
function aquariende_content_foreign_menus() {
	$out = array();
	foreach ( wp_get_nav_menus() as $term ) {
		if ( '' === (string) get_term_meta( $term->term_id, AQUARIENDE_CONTENT_META, true ) ) {
			$out[] = $term;
		}
	}
	return $out;
}

/**
 * Trash the foreign pages and posts; detach foreign menus from locations.
 *
 * @return array{posts:int,menus:int}
 */
function aquariende_content_trash_foreign() {
	$log     = array( 'Moving the old content to the trash.' );
	$trashed = 0;
	foreach ( aquariende_content_foreign_posts() as $post ) {
		if ( wp_trash_post( $post->ID ) ) {
			++$trashed;
			$log[] = sprintf( 'Trashed %1$s "%2$s" (#%3$d).', $post->post_type, $post->post_title, $post->ID );
		}
	}

	$menus     = aquariende_content_foreign_menus();
	$locations = get_theme_mod( 'nav_menu_locations', array() );
	$locations = is_array( $locations ) ? $locations : array();
	foreach ( $menus as $menu ) {
		foreach ( $locations as $location => $menu_id ) {
			if ( (int) $menu_id === (int) $menu->term_id ) {
				unset( $locations[ $location ] );
				$log[] = sprintf( 'Detached menu "%1$s" (#%2$d) from the theme locations; it is kept, as WordPress has no trash for menus.', $menu->name, $menu->term_id );
			}
		}
	}
	$menus_data = aquariende_content_data( 'menus' );
	foreach ( wp_get_nav_menus() as $term ) {
		$key = (string) get_term_meta( $term->term_id, AQUARIENDE_CONTENT_META, true );
		if ( isset( $menus_data['menus'][ $key ] ) ) {
			$location = $menus_data['menus'][ $key ]['location'];
			if ( empty( $locations[ $location ] ) ) {
				$locations[ $location ] = (int) $term->term_id;
			}
		}
	}
	set_theme_mod( 'nav_menu_locations', $locations );

	aquariende_content_fix_slugs( $log );
	flush_rewrite_rules();

	$log[] = sprintf( 'Done: %1$d pages and posts moved to the trash, %2$d old menus kept but not shown.', $trashed, count( $menus ) );
	aquariende_content_log( $log );
	if ( function_exists( 'aquariende_purge_caches' ) ) {
		aquariende_purge_caches();
	}
	return array(
		'posts' => $trashed,
		'menus' => count( $menus ),
	);
}
