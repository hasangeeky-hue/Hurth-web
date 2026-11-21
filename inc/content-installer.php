<?php
/**
 * Content installer: puts the Aquariende pages, posts, menus and reading
 * settings into WordPress from the theme itself, so a Git deploy is all a
 * site needs. No WP-CLI, no plugins.
 *
 * When it runs
 * - on after_switch_theme (the theme is activated);
 * - on admin_init for an administrator, when the option
 *   aquariende_content_version is lower than AQUARIENDE_CONTENT_VERSION
 *   (so a Git redeploy of an already active theme also triggers it);
 * - after a Deployer for Git package install (dfg_after_package_install);
 * - when "Run installer again" is pressed under Appearance → Aquariende content.
 *
 * What it does (safe to repeat)
 * - Every item it creates carries the meta _aquariende_content_key. Items are
 *   found by that key, never by title or slug, so nothing is duplicated and
 *   nothing that belongs to someone else is touched.
 * - Missing items are created. An item in the trash is left there.
 * - Existing items are updated only when the installer version has increased,
 *   the item still carries the key, and nobody has edited it since it was
 *   installed (a content hash is kept in _aquariende_content_hash).
 * - If a slug was taken when an item was created (WordPress then adds "-2"),
 *   the item gets its proper slug back as soon as the slug is free.
 * - Menus "Primary" and "Legal" are created and assigned to the theme's
 *   locations; front page, posts page, privacy page, site title, tagline and
 *   the /%postname%/ permalink structure are set. The admin email is never
 *   changed.
 *
 * Moving the old content away is a separate, manual action with a confirm
 * step: it moves to the trash every page and post without the key, and
 * detaches menus without the key from the theme locations. WordPress has no
 * trash for menus, so those are left in place rather than deleted.
 *
 * @package Aquariende
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/*
 * Raise this number whenever anything in inc/content/ changes. The next admin
 * visit after the deploy then installs the change.
 */
define( 'AQUARIENDE_CONTENT_VERSION', 1 );
define( 'AQUARIENDE_CONTENT_META', '_aquariende_content_key' );
define( 'AQUARIENDE_CONTENT_HASH', '_aquariende_content_hash' );

/* -------------------------------------------------------------------------
 * Data and small helpers
 * ---------------------------------------------------------------------- */

/**
 * Load one content file from inc/content/.
 *
 * @param string $name pages|posts|menus.
 * @return array
 */
function aquariende_content_data( $name ) {
	static $cache = array();
	if ( ! isset( $cache[ $name ] ) ) {
		$file           = get_template_directory() . '/inc/content/' . $name . '.php';
		$data           = is_readable( $file ) ? include $file : array();
		$cache[ $name ] = is_array( $data ) ? $data : array();
	}
	return $cache[ $name ];
}

/**
 * Body of a post from content/posts/*.html, without the header comment.
 *
 * @param string $relative Path relative to the theme folder.
 * @return string
 */
function aquariende_content_post_body( $relative ) {
	$file = get_template_directory() . '/' . ltrim( $relative, '/' );
	if ( ! is_readable( $file ) ) {
		return '';
	}
	$html = (string) file_get_contents( $file ); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents -- local theme file.
	$html = preg_replace( '/^\s*<!--.*?-->\s*/s', '', $html );
	return trim( $html );
}

/**
 * Append lines to the log option (last 50 lines are kept).
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
	update_option( 'aquariende_content_log', array_slice( $log, -50 ), false );
}

/**
 * Hash of an item as stored, used to detect edits by the owner.
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
 * Find an installer item by its key (any status, including trash).
 *
 * @param string $key       Content key.
 * @param string $post_type page|post.
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
 * ID of an installed page or post by key, when it is published.
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

/* -------------------------------------------------------------------------
 * Install
 * ---------------------------------------------------------------------- */

/**
 * Create or update one page or post.
 *
 * @param string $key     Content key.
 * @param array  $postarr Fields for wp_insert_post (unslashed).
 * @param array  $meta    Extra meta (unique keys).
 * @param bool   $upgrade Whether the installer version increased.
 * @param array  $log     Log lines, by reference.
 * @return int Post ID or 0.
 */
function aquariende_content_put( $key, $postarr, $meta, $upgrade, &$log ) {
	$type     = $postarr['post_type'];
	$label    = ( 'page' === $type ) ? __( 'page', 'aquariende' ) : __( 'post', 'aquariende' );
	$existing = aquariende_content_find( $key, $type );

	if ( $existing && 'trash' === $existing->post_status ) {
		/* translators: 1: page or post, 2: title, 3: ID. */
		$log[] = sprintf( __( 'Skipped %1$s "%2$s" (#%3$d): it is in the trash. Restore it, or delete it permanently to have it recreated.', 'aquariende' ), $label, $existing->post_title, $existing->ID );
		return 0;
	}

	if ( ! $existing ) {
		$postarr['meta_input'] = array_merge( $meta, array( AQUARIENDE_CONTENT_META => $key ) );
		$id                    = wp_insert_post( wp_slash( $postarr ), true );
		if ( is_wp_error( $id ) ) {
			/* translators: 1: page or post, 2: title, 3: error. */
			$log[] = sprintf( __( 'Could not create %1$s "%2$s": %3$s', 'aquariende' ), $label, $postarr['post_title'], $id->get_error_message() );
			return 0;
		}
		update_post_meta( $id, AQUARIENDE_CONTENT_HASH, aquariende_content_hash_of( $id ) );
		/* translators: 1: page or post, 2: title, 3: ID, 4: slug. */
		$log[] = sprintf( __( 'Created %1$s "%2$s" (#%3$d, /%4$s/).', 'aquariende' ), $label, $postarr['post_title'], $id, get_post_field( 'post_name', $id ) );
		return (int) $id;
	}

	$id = (int) $existing->ID;

	if ( $upgrade ) {
		$stored = get_post_meta( $id, AQUARIENDE_CONTENT_HASH, true );
		if ( $stored && aquariende_content_hash_of( $id ) !== $stored ) {
			/* translators: 1: page or post, 2: title, 3: ID. */
			$log[] = sprintf( __( 'Kept %1$s "%2$s" (#%3$d) as it is: it was edited after it was installed.', 'aquariende' ), $label, $existing->post_title, $id );
		} elseif ( $existing->post_title !== $postarr['post_title'] || $existing->post_content !== $postarr['post_content'] || $existing->post_excerpt !== $postarr['post_excerpt'] ) {
			$update = array(
				'ID'           => $id,
				'post_title'   => $postarr['post_title'],
				'post_content' => $postarr['post_content'],
				'post_excerpt' => $postarr['post_excerpt'],
			);
			wp_update_post( wp_slash( $update ) );
			foreach ( $meta as $meta_key => $meta_value ) {
				if ( '_wp_old_slug' !== $meta_key ) {
					update_post_meta( $id, $meta_key, $meta_value );
				}
			}
			update_post_meta( $id, AQUARIENDE_CONTENT_HASH, aquariende_content_hash_of( $id ) );
			/* translators: 1: page or post, 2: title, 3: ID. */
			$log[] = sprintf( __( 'Updated %1$s "%2$s" (#%3$d) to the current text.', 'aquariende' ), $label, $postarr['post_title'], $id );
		}
	}

	return $id;
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
	foreach ( aquariende_content_data( 'posts' ) as $key => $post ) {
		$wanted[ $key ] = array( 'post', $post['slug'] );
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
			/* translators: 1: title, 2: old slug, 3: new slug. */
			$log[] = sprintf( __( 'Slug of "%1$s" changed from /%2$s/ to /%3$s/.', 'aquariende' ), $item->post_title, $item->post_name, $want[1] );
		} else {
			/* translators: 1: title, 2: slug, 3: wanted slug. */
			$log[] = sprintf( __( '"%1$s" stays at /%2$s/ for now: /%3$s/ belongs to another page or post (moving the old content to the trash frees it).', 'aquariende' ), $item->post_title, $item->post_name, $want[1] );
		}
	}
}

/**
 * Find or create an installer menu and fill it.
 *
 * @param string $key      Menu key.
 * @param array  $menu     Menu definition.
 * @param array  $ids      Content key => post ID.
 * @param bool   $upgrade  Whether the installer version increased.
 * @param array  $log      Log lines, by reference.
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
			// A menu of another site already uses the name.
			$name   = sprintf( '%s (Aquariende)', $menu['name'] );
			$result = wp_create_nav_menu( $name );
		}
		if ( is_wp_error( $result ) ) {
			/* translators: 1: menu name, 2: error. */
			$log[] = sprintf( __( 'Could not create menu "%1$s": %2$s', 'aquariende' ), $menu['name'], $result->get_error_message() );
			return 0;
		}
		$menu_id = (int) $result;
		update_term_meta( $menu_id, AQUARIENDE_CONTENT_META, $key );
		$created = true;
		/* translators: 1: menu name, 2: ID. */
		$log[] = sprintf( __( 'Created menu "%1$s" (#%2$d).', 'aquariende' ), $name, $menu_id );
	}

	$items     = wp_get_nav_menu_items( $menu_id, array( 'post_status' => 'any' ) );
	$items     = is_array( $items ) ? $items : array();
	$signature = md5( wp_json_encode( array_map( 'aquariende_content_menu_item_sig', $items ) ) );
	$stored    = get_term_meta( $menu_id, AQUARIENDE_CONTENT_HASH, true );

	$rebuild = $created || ! $items || ( $upgrade && ( ! $stored || $stored === $signature ) );
	if ( ! $rebuild ) {
		if ( $upgrade ) {
			/* translators: %s: menu name. */
			$log[] = sprintf( __( 'Kept menu "%s" as it is: it was edited after it was installed.', 'aquariende' ), $menu['name'] );
		}
		return $menu_id;
	}

	foreach ( $items as $item ) {
		wp_delete_post( $item->ID, true ); // Our own menu items only.
	}

	$position = 1;
	foreach ( $menu['items'] as $item ) {
		$page_id = isset( $ids[ $item['page'] ] ) ? (int) $ids[ $item['page'] ] : 0;
		if ( ! $page_id ) {
			continue;
		}
		wp_update_nav_menu_item(
			$menu_id,
			0,
			array(
				'menu-item-title'     => $item['label'],
				'menu-item-object'    => 'page',
				'menu-item-object-id' => $page_id,
				'menu-item-type'      => 'post_type',
				'menu-item-status'    => 'publish',
				'menu-item-position'  => $position++,
			)
		);
	}

	$items = wp_get_nav_menu_items( $menu_id, array( 'post_status' => 'any' ) );
	update_term_meta( $menu_id, AQUARIENDE_CONTENT_HASH, md5( wp_json_encode( array_map( 'aquariende_content_menu_item_sig', is_array( $items ) ? $items : array() ) ) ) );
	/* translators: 1: menu name, 2: number of links. */
	$log[] = sprintf( __( 'Filled menu "%1$s" with %2$d links.', 'aquariende' ), $menu['name'], $position - 1 );

	return $menu_id;
}

/**
 * Signature of one menu item, for edit detection.
 *
 * @param WP_Post $item Menu item.
 * @return string
 */
function aquariende_content_menu_item_sig( $item ) {
	// Permalinks are left out: they change when a slug is freed, which is not an edit.
	return $item->menu_order . '|' . $item->type . '|' . $item->object_id . '|' . $item->post_title . '|' . ( 'custom' === $item->type ? $item->url : '' );
}

/**
 * Run the installer.
 *
 * @param string $reason Why it runs (for the log).
 * @return bool False when another run holds the lock.
 */
function aquariende_content_install( $reason = '' ) {
	// One run at a time; a lock older than five minutes is considered stale.
	if ( ! add_option( 'aquariende_content_lock', time(), '', false ) ) {
		$since = (int) get_option( 'aquariende_content_lock' );
		if ( $since && ( time() - $since ) < 300 ) {
			return false;
		}
		update_option( 'aquariende_content_lock', time(), false );
	}

	$installed = (int) get_option( 'aquariende_content_version', 0 );
	$upgrade   = $installed < AQUARIENDE_CONTENT_VERSION;
	$author    = aquariende_content_author();
	$log       = array();

	/* translators: 1: reason, 2: installed version, 3: installer version. */
	$log[] = sprintf( __( 'Installer started (%1$s). Installed content version: %2$d, installer version: %3$d.', 'aquariende' ), $reason ? $reason : __( 'manual', 'aquariende' ), $installed, AQUARIENDE_CONTENT_VERSION );

	$ids = get_option( 'aquariende_content_ids', array() );
	$ids = is_array( $ids ) ? $ids : array();

	// Pages.
	foreach ( aquariende_content_data( 'pages' ) as $key => $page ) {
		$meta = array( '_wp_page_template' => $page['template'] );
		if ( ! empty( $page['old_slug'] ) ) {
			$meta['_aquariende_old_slug'] = $page['old_slug'];
		}
		$id = aquariende_content_put(
			$key,
			array(
				'post_type'      => 'page',
				'post_status'    => 'publish',
				'post_title'     => $page['title'],
				'post_name'      => $key,
				'post_content'   => trim( $page['content'] ),
				'post_excerpt'   => $page['excerpt'],
				'menu_order'     => (int) $page['order'],
				'post_author'    => $author,
				'comment_status' => 'closed',
				'ping_status'    => 'closed',
			),
			$meta,
			$upgrade,
			$log
		);
		if ( $id ) {
			$ids[ $key ] = $id;
		}
	}

	// Categories.
	$menus_data = aquariende_content_data( 'menus' );
	$cat_ids    = array();
	foreach ( $menus_data['categories'] as $slug => $name ) {
		$term = term_exists( $slug, 'category' );
		if ( ! $term ) {
			$term = wp_insert_term( $name, 'category', array( 'slug' => $slug ) );
			if ( ! is_wp_error( $term ) ) {
				/* translators: %s: category name. */
				$log[] = sprintf( __( 'Created category "%s".', 'aquariende' ), $name );
			}
		}
		if ( is_array( $term ) && isset( $term['term_id'] ) ) {
			$cat_ids[ $slug ] = (int) $term['term_id'];
		}
	}

	// Posts.
	foreach ( aquariende_content_data( 'posts' ) as $key => $post ) {
		$postarr = array(
			'post_type'      => 'post',
			'post_status'    => 'publish',
			'post_title'     => $post['title'],
			'post_name'      => $post['slug'],
			'post_content'   => aquariende_content_post_body( $post['file'] ),
			'post_excerpt'   => '',
			'post_date'      => $post['date'],
			'post_date_gmt'  => get_gmt_from_date( $post['date'] ),
			'post_author'    => $author,
			'comment_status' => 'closed',
			'ping_status'    => 'closed',
		);
		if ( isset( $cat_ids[ $post['category'] ] ) ) {
			$postarr['post_category'] = array( $cat_ids[ $post['category'] ] );
		}
		$meta = empty( $post['old_slug'] ) ? array() : array( '_wp_old_slug' => $post['old_slug'] );
		$id   = aquariende_content_put( $key, $postarr, $meta, $upgrade, $log );
		if ( $id ) {
			$ids[ $key ] = $id;
		}
	}

	update_option( 'aquariende_content_ids', $ids, true );

	aquariende_content_fix_slugs( $log );

	// Menus and their locations.
	$locations = get_theme_mod( 'nav_menu_locations', array() );
	$locations = is_array( $locations ) ? $locations : array();
	foreach ( $menus_data['menus'] as $key => $menu ) {
		$menu_id = aquariende_content_put_menu( $key, $menu, $ids, $upgrade, $log );
		if ( $menu_id && ( ! isset( $locations[ $menu['location'] ] ) || (int) $locations[ $menu['location'] ] !== $menu_id ) ) {
			$locations[ $menu['location'] ] = $menu_id;
			/* translators: 1: menu name, 2: location. */
			$log[] = sprintf( __( 'Assigned menu "%1$s" to the "%2$s" location.', 'aquariende' ), $menu['name'], $menu['location'] );
		}
	}
	set_theme_mod( 'nav_menu_locations', $locations );

	// Reading settings, identity and permalinks. The admin email is not touched.
	$settings = array(
		'blogname'        => 'Aquariende',
		'blogdescription' => 'Consulting',
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
			/* translators: 1: option name, 2: value. */
			$log[] = sprintf( __( 'Set %1$s to "%2$s".', 'aquariende' ), $option, $value );
		}
	}

	if ( '/%postname%/' !== get_option( 'permalink_structure' ) ) {
		global $wp_rewrite;
		$wp_rewrite->set_permalink_structure( '/%postname%/' );
		$log[] = __( 'Set the permalink structure to /%postname%/.', 'aquariende' );
	}
	flush_rewrite_rules();

	update_option( 'aquariende_content_version', AQUARIENDE_CONTENT_VERSION, true );
	if ( $upgrade ) {
		update_option( 'aquariende_content_notice', AQUARIENDE_CONTENT_VERSION, false );
	}
	update_option( 'aquariende_content_last_run', time(), false );

	$log[] = __( 'Installer finished.', 'aquariende' );
	aquariende_content_log( $log );

	if ( function_exists( 'aquariende_purge_caches' ) ) {
		aquariende_purge_caches();
	}

	delete_option( 'aquariende_content_lock' );
	return true;
}

/**
 * Theme activation.
 */
function aquariende_content_on_switch() {
	aquariende_content_install( __( 'theme activated', 'aquariende' ) );
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
	aquariende_content_install( __( 'new installer version after deploy', 'aquariende' ) );
}
add_action( 'admin_init', 'aquariende_content_maybe_install', 5 );

/**
 * Deployer for Git finished a package install.
 */
function aquariende_content_after_deploy() {
	if ( (int) get_option( 'aquariende_content_version', 0 ) < AQUARIENDE_CONTENT_VERSION ) {
		aquariende_content_install( __( 'Deployer for Git', 'aquariende' ) );
	}
}
add_action( 'dfg_after_package_install', 'aquariende_content_after_deploy', 30 );

/**
 * Old page addresses of the 2022 site (/about-us/, /service/) go to the new
 * pages. Old post slugs are handled by WordPress through _wp_old_slug.
 */
function aquariende_content_old_slug_redirect() {
	if ( ! is_404() ) {
		return;
	}
	$path = trim( (string) wp_parse_url( isset( $_SERVER['REQUEST_URI'] ) ? wp_unslash( $_SERVER['REQUEST_URI'] ) : '', PHP_URL_PATH ), '/' ); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput -- compared only.
	if ( '' === $path ) {
		return;
	}
	$map = array( 'home-test' => 'home' );
	foreach ( aquariende_content_data( 'pages' ) as $key => $page ) {
		if ( ! empty( $page['old_slug'] ) ) {
			$map[ $page['old_slug'] ] = $key;
		}
	}
	$last = basename( $path );
	if ( isset( $map[ $last ] ) ) {
		$id = aquariende_content_id( $map[ $last ] );
		if ( $id ) {
			wp_safe_redirect( 'home' === $map[ $last ] ? home_url( '/' ) : get_permalink( $id ), 301 );
			exit;
		}
	}
}
add_action( 'template_redirect', 'aquariende_content_old_slug_redirect', 9 );

/* -------------------------------------------------------------------------
 * Moving the old site's content to the trash
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
	$log     = array( __( 'Moving the old content to the trash.', 'aquariende' ) );
	$trashed = 0;

	foreach ( aquariende_content_foreign_posts() as $post ) {
		if ( wp_trash_post( $post->ID ) ) {
			++$trashed;
			/* translators: 1: post type, 2: title, 3: ID. */
			$log[] = sprintf( __( 'Trashed %1$s "%2$s" (#%3$d).', 'aquariende' ), $post->post_type, $post->post_title, $post->ID );
		}
	}

	$menus     = aquariende_content_foreign_menus();
	$locations = get_theme_mod( 'nav_menu_locations', array() );
	$locations = is_array( $locations ) ? $locations : array();
	$detached  = 0;
	foreach ( $menus as $menu ) {
		$was = false;
		foreach ( $locations as $location => $menu_id ) {
			if ( (int) $menu_id === (int) $menu->term_id ) {
				unset( $locations[ $location ] );
				$was = true;
			}
		}
		++$detached;
		$log[] = $was
			/* translators: 1: menu name, 2: ID. */
			? sprintf( __( 'Detached menu "%1$s" (#%2$d) from the theme locations. WordPress has no trash for menus, so it was kept; delete it under Appearance → Menus if it is not needed.', 'aquariende' ), $menu->name, $menu->term_id )
			/* translators: 1: menu name, 2: ID. */
			: sprintf( __( 'Menu "%1$s" (#%2$d) is not shown anywhere. WordPress has no trash for menus, so it was kept.', 'aquariende' ), $menu->name, $menu->term_id );
	}
	// Put the installer menus back into any location that is now empty.
	$menus_data = aquariende_content_data( 'menus' );
	foreach ( wp_get_nav_menus() as $term ) {
		$key = (string) get_term_meta( $term->term_id, AQUARIENDE_CONTENT_META, true );
		if ( isset( $menus_data['menus'][ $key ] ) ) {
			$location = $menus_data['menus'][ $key ]['location'];
			if ( empty( $locations[ $location ] ) ) {
				$locations[ $location ] = (int) $term->term_id;
				/* translators: 1: menu name, 2: location. */
				$log[] = sprintf( __( 'Assigned menu "%1$s" to the "%2$s" location.', 'aquariende' ), $term->name, $location );
			}
		}
	}
	set_theme_mod( 'nav_menu_locations', $locations );

	// With the old pages gone, the installer pages can take their real slugs.
	aquariende_content_fix_slugs( $log );
	flush_rewrite_rules();

	/* translators: 1: number of pages and posts, 2: number of menus. */
	$log[] = sprintf( __( 'Done: %1$d pages and posts moved to the trash, %2$d old menus kept but not shown.', 'aquariende' ), $trashed, $detached );
	aquariende_content_log( $log );

	if ( function_exists( 'aquariende_purge_caches' ) ) {
		aquariende_purge_caches();
	}

	return array(
		'posts' => $trashed,
		'menus' => $detached,
	);
}

/* -------------------------------------------------------------------------
 * Admin screen: Appearance → Aquariende content
 * ---------------------------------------------------------------------- */

function aquariende_content_admin_menu() {
	add_theme_page(
		__( 'Aquariende content', 'aquariende' ),
		__( 'Aquariende content', 'aquariende' ),
		'manage_options',
		'aquariende-content',
		'aquariende_content_admin_page'
	);
}
add_action( 'admin_menu', 'aquariende_content_admin_menu' );

/**
 * URL of the admin screen.
 *
 * @param array $args Query args.
 * @return string
 */
function aquariende_content_admin_url( $args = array() ) {
	return add_query_arg( array_merge( array( 'page' => 'aquariende-content' ), $args ), admin_url( 'themes.php' ) );
}

function aquariende_content_admin_page() {
	if ( ! current_user_can( 'manage_options' ) ) {
		wp_die( esc_html__( 'You do not have permission to do this.', 'aquariende' ) );
	}

	$installed = (int) get_option( 'aquariende_content_version', 0 );
	$last_run  = (int) get_option( 'aquariende_content_last_run', 0 );
	$log       = get_option( 'aquariende_content_log', array() );
	$done      = isset( $_GET['aq_done'] ) ? sanitize_key( wp_unslash( $_GET['aq_done'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification -- display only.
	$count     = isset( $_GET['aq_count'] ) ? absint( $_GET['aq_count'] ) : 0; // phpcs:ignore WordPress.Security.NonceVerification -- display only.
	$step      = isset( $_GET['aq_step'] ) ? sanitize_key( wp_unslash( $_GET['aq_step'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification -- checked below.
	$confirm   = ( 'confirm-trash' === $step && isset( $_GET['_wpnonce'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_GET['_wpnonce'] ) ), 'aquariende_content_confirm' ) );
	$foreign   = aquariende_content_foreign_posts();
	$menus     = aquariende_content_foreign_menus();
	$shown     = array_map( 'intval', array_values( (array) get_theme_mod( 'nav_menu_locations', array() ) ) );
	$menus_on  = array_filter(
		$menus,
		function ( $term ) use ( $shown ) {
			return in_array( (int) $term->term_id, $shown, true );
		}
	);
	?>
	<div class="wrap">
		<h1><?php esc_html_e( 'Aquariende content', 'aquariende' ); ?></h1>

		<?php if ( 'run' === $done ) : ?>
			<div class="notice notice-success"><p><?php esc_html_e( 'The installer ran. Details are in the log below.', 'aquariende' ); ?></p></div>
		<?php elseif ( 'busy' === $done ) : ?>
			<div class="notice notice-warning"><p><?php esc_html_e( 'The installer is already running. Try again in a few minutes.', 'aquariende' ); ?></p></div>
		<?php elseif ( 'trash' === $done ) : ?>
			<div class="notice notice-success"><p>
				<?php
				/* translators: %d: number of items. */
				echo esc_html( sprintf( _n( '%d page or post was moved to the trash. You can restore it under Pages or Posts → Trash.', '%d pages and posts were moved to the trash. You can restore them under Pages or Posts → Trash.', $count, 'aquariende' ), $count ) );
				?>
			</p></div>
		<?php elseif ( 'unconfirmed' === $done ) : ?>
			<div class="notice notice-error"><p><?php esc_html_e( 'Nothing was moved: please tick the confirmation box first.', 'aquariende' ); ?></p></div>
		<?php endif; ?>

		<h2><?php esc_html_e( 'Status', 'aquariende' ); ?></h2>
		<table class="widefat striped" style="max-width:720px">
			<tbody>
				<tr><th scope="row"><?php esc_html_e( 'Installer version in the theme', 'aquariende' ); ?></th><td><?php echo esc_html( (string) AQUARIENDE_CONTENT_VERSION ); ?></td></tr>
				<tr><th scope="row"><?php esc_html_e( 'Content version installed on this site', 'aquariende' ); ?></th><td><?php echo $installed ? esc_html( (string) $installed ) : esc_html__( 'not installed yet', 'aquariende' ); ?></td></tr>
				<tr><th scope="row"><?php esc_html_e( 'State', 'aquariende' ); ?></th><td><?php echo ( $installed >= AQUARIENDE_CONTENT_VERSION ) ? esc_html__( 'Up to date', 'aquariende' ) : esc_html__( 'An update is waiting; it runs on the next admin page load.', 'aquariende' ); ?></td></tr>
				<tr><th scope="row"><?php esc_html_e( 'Last run', 'aquariende' ); ?></th><td><?php echo $last_run ? esc_html( wp_date( get_option( 'date_format' ) . ' ' . get_option( 'time_format' ), $last_run ) ) : esc_html__( 'never', 'aquariende' ); ?></td></tr>
			</tbody>
		</table>

		<h2><?php esc_html_e( 'Installed pages and posts', 'aquariende' ); ?></h2>
		<ul>
			<?php
			$aq_items = array();
			foreach ( aquariende_content_data( 'pages' ) as $aq_key => $aq_page ) {
				$aq_items[] = array( $aq_key, 'page', $aq_page['title'] );
			}
			foreach ( aquariende_content_data( 'posts' ) as $aq_key => $aq_post ) {
				$aq_items[] = array( $aq_key, 'post', $aq_post['title'] );
			}
			foreach ( $aq_items as $aq_item ) :
				$aq_found = aquariende_content_find( $aq_item[0], $aq_item[1] );
				?>
				<li>
					<?php if ( $aq_found ) : ?>
						<a href="<?php echo esc_url( get_edit_post_link( $aq_found->ID ) ); ?>"><?php echo esc_html( $aq_found->post_title ); ?></a>
						<?php echo esc_html( sprintf( '(#%d, /%s/, %s)', $aq_found->ID, $aq_found->post_name, $aq_found->post_status ) ); ?>
					<?php else : ?>
						<?php echo esc_html( $aq_item[2] ); ?> — <?php esc_html_e( 'missing (created on the next run)', 'aquariende' ); ?>
					<?php endif; ?>
				</li>
			<?php endforeach; ?>
		</ul>
		<p class="description"><?php esc_html_e( 'Items you edit in WordPress are never overwritten by a later installer version. Delete an item permanently to have it recreated.', 'aquariende' ); ?></p>

		<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
			<input type="hidden" name="action" value="aquariende_content_run">
			<?php wp_nonce_field( 'aquariende_content_run' ); ?>
			<?php submit_button( __( 'Run installer again', 'aquariende' ), 'primary', 'submit', false ); ?>
		</form>

		<h2><?php esc_html_e( 'Old content of the previous site', 'aquariende' ); ?></h2>
		<p>
			<?php
			/* translators: 1: number of pages and posts, 2: number of menus, 3: number of those menus still shown. */
			echo esc_html( sprintf( __( 'Pages and posts not installed by Aquariende: %1$d. Menus not installed by Aquariende: %2$d (%3$d still shown in a theme location).', 'aquariende' ), count( $foreign ), count( $menus ), count( $menus_on ) ) );
			?>
		</p>

		<?php if ( $confirm ) : ?>
			<div class="card" style="max-width:720px">
				<h3><?php esc_html_e( 'Please confirm', 'aquariende' ); ?></h3>
				<p><?php esc_html_e( 'These pages and posts will be moved to the trash. Nothing is deleted: you can restore each one under Pages → Trash or Posts → Trash.', 'aquariende' ); ?></p>
				<ul style="list-style:disc;padding-left:20px;max-height:320px;overflow:auto">
					<?php foreach ( $foreign as $aq_post ) : ?>
						<li><?php echo esc_html( sprintf( '%s — %s (#%d, %s)', $aq_post->post_type, '' !== $aq_post->post_title ? $aq_post->post_title : __( '(no title)', 'aquariende' ), $aq_post->ID, $aq_post->post_status ) ); ?></li>
					<?php endforeach; ?>
				</ul>
				<?php if ( $menus_on ) : ?>
					<p><?php esc_html_e( 'These menus will be removed from the theme locations but kept, because WordPress has no trash for menus:', 'aquariende' ); ?></p>
					<ul style="list-style:disc;padding-left:20px">
						<?php foreach ( $menus_on as $aq_menu ) : ?>
							<li><?php echo esc_html( $aq_menu->name ); ?></li>
						<?php endforeach; ?>
					</ul>
				<?php endif; ?>
				<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
					<input type="hidden" name="action" value="aquariende_content_trash">
					<?php wp_nonce_field( 'aquariende_content_trash' ); ?>
					<p><label><input type="checkbox" name="aq_confirm" value="1" required> <?php esc_html_e( 'I understand that these items move to the trash.', 'aquariende' ); ?></label></p>
					<p>
						<?php
						/* translators: %d: number of items. */
						submit_button( sprintf( __( 'Yes, move %d items to the trash', 'aquariende' ), count( $foreign ) ), 'delete', 'submit', false );
						?>
						<a class="button" href="<?php echo esc_url( aquariende_content_admin_url() ); ?>"><?php esc_html_e( 'Cancel', 'aquariende' ); ?></a>
					</p>
				</form>
			</div>
		<?php elseif ( $foreign || $menus_on ) : ?>
			<p><?php esc_html_e( 'This moves every page and post that the Aquariende installer did not create (for example the Friends Mobile pages) to the trash, and stops showing their menus. You will see the full list and confirm before anything happens.', 'aquariende' ); ?></p>
			<p><a class="button button-secondary" href="<?php echo esc_url( wp_nonce_url( aquariende_content_admin_url( array( 'aq_step' => 'confirm-trash' ) ), 'aquariende_content_confirm' ) ); ?>"><?php esc_html_e( 'Move Friends Mobile content to trash', 'aquariende' ); ?></a></p>
		<?php else : ?>
			<p><?php esc_html_e( 'Nothing to move: every page and post on this site was installed by Aquariende.', 'aquariende' ); ?></p>
		<?php endif; ?>

		<h2><?php esc_html_e( 'Log (last 50 lines)', 'aquariende' ); ?></h2>
		<?php if ( is_array( $log ) && $log ) : ?>
			<pre style="background:#fff;border:1px solid #c3c4c7;padding:12px;max-width:960px;max-height:420px;overflow:auto;white-space:pre-wrap"><?php echo esc_html( implode( "\n", array_reverse( $log ) ) ); ?></pre>
		<?php else : ?>
			<p><?php esc_html_e( 'The log is empty.', 'aquariende' ); ?></p>
		<?php endif; ?>
	</div>
	<?php
}

function aquariende_content_handle_run() {
	if ( ! current_user_can( 'manage_options' ) ) {
		wp_die( esc_html__( 'You do not have permission to do this.', 'aquariende' ), 403 );
	}
	check_admin_referer( 'aquariende_content_run' );
	$ok = aquariende_content_install( __( 'Run installer again', 'aquariende' ) );
	wp_safe_redirect( aquariende_content_admin_url( array( 'aq_done' => $ok ? 'run' : 'busy' ) ) );
	exit;
}
add_action( 'admin_post_aquariende_content_run', 'aquariende_content_handle_run' );

function aquariende_content_handle_trash() {
	if ( ! current_user_can( 'manage_options' ) ) {
		wp_die( esc_html__( 'You do not have permission to do this.', 'aquariende' ), 403 );
	}
	check_admin_referer( 'aquariende_content_trash' );
	if ( empty( $_POST['aq_confirm'] ) ) {
		wp_safe_redirect( aquariende_content_admin_url( array( 'aq_done' => 'unconfirmed' ) ) );
		exit;
	}
	$result = aquariende_content_trash_foreign();
	wp_safe_redirect(
		aquariende_content_admin_url(
			array(
				'aq_done'  => 'trash',
				'aq_count' => $result['posts'],
			)
		)
	);
	exit;
}
add_action( 'admin_post_aquariende_content_trash', 'aquariende_content_handle_trash' );

/* -------------------------------------------------------------------------
 * One admin notice after an install
 * ---------------------------------------------------------------------- */

function aquariende_content_admin_notice() {
	if ( ! current_user_can( 'manage_options' ) || ! get_option( 'aquariende_content_notice' ) ) {
		return;
	}
	$screen = function_exists( 'get_current_screen' ) ? get_current_screen() : null;
	if ( $screen && 'appearance_page_aquariende-content' === $screen->id ) {
		return;
	}
	$dismiss = wp_nonce_url( admin_url( 'admin-post.php?action=aquariende_content_dismiss' ), 'aquariende_content_dismiss' );
	?>
	<div class="notice notice-success is-dismissible aquariende-content-notice" data-dismiss="<?php echo esc_url( $dismiss ); ?>">
		<p>
			<?php
			/* translators: %d: content version. */
			echo esc_html( sprintf( __( 'Aquariende content was installed (version %d): pages, blog posts, menus, front page and site title.', 'aquariende' ), (int) get_option( 'aquariende_content_version', 0 ) ) );
			?>
			<a href="<?php echo esc_url( aquariende_content_admin_url() ); ?>"><?php esc_html_e( 'See what was done', 'aquariende' ); ?></a>
			·
			<a href="<?php echo esc_url( $dismiss ); ?>"><?php esc_html_e( 'Dismiss', 'aquariende' ); ?></a>
		</p>
	</div>
	<script>
	document.addEventListener('click', function (e) {
		var n = e.target.closest && e.target.closest('.aquariende-content-notice .notice-dismiss');
		if (n && window.fetch) { fetch(n.closest('.aquariende-content-notice').getAttribute('data-dismiss') + '&ajax=1', { credentials: 'same-origin' }); }
	});
	</script>
	<?php
}
add_action( 'admin_notices', 'aquariende_content_admin_notice' );

function aquariende_content_handle_dismiss() {
	if ( ! current_user_can( 'manage_options' ) ) {
		wp_die( esc_html__( 'You do not have permission to do this.', 'aquariende' ), 403 );
	}
	check_admin_referer( 'aquariende_content_dismiss' );
	delete_option( 'aquariende_content_notice' );
	if ( ! empty( $_GET['ajax'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification -- nonce checked above.
		wp_die( '', '', array( 'response' => 204 ) );
	}
	wp_safe_redirect( wp_get_referer() ? wp_get_referer() : admin_url() );
	exit;
}
add_action( 'admin_post_aquariende_content_dismiss', 'aquariende_content_handle_dismiss' );
