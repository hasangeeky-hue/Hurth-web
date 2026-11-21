<?php
/**
 * Admin: the "Aquariende fields" box on pages, guides, terms, FAQ items and
 * team members; the guides "Next review" column; and the screen
 * Appearance → Aquariende content (installer status, guides due for review,
 * missing consent, guide quality checks, old content, log).
 *
 * @package Aquariende
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/* -------------------------------------------------------------------------
 * Fields box
 * ---------------------------------------------------------------------- */

function aquariende_add_meta_boxes() {
	foreach ( array_keys( aquariende_meta_fields() ) as $type ) {
		add_meta_box( 'aquariende-fields', 'Aquariende fields', 'aquariende_render_meta_box', $type, 'normal', 'high' );
	}
}
add_action( 'add_meta_boxes', 'aquariende_add_meta_boxes' );

/**
 * Text shown in the box for a stored value.
 *
 * @param string $type  Field type.
 * @param string $value Stored value.
 * @return string
 */
function aquariende_meta_display( $type, $value ) {
	if ( 'sources' === $type ) {
		$list  = json_decode( (string) $value, true );
		$lines = array();
		foreach ( is_array( $list ) ? $list : array() as $s ) {
			$lines[] = implode( ' | ', array( isset( $s['publisher'] ) ? $s['publisher'] : '', isset( $s['title'] ) ? $s['title'] : '', isset( $s['url'] ) ? $s['url'] : '', isset( $s['accessed'] ) ? $s['accessed'] : '' ) );
		}
		return implode( "\n", $lines );
	}
	return (string) $value;
}

/**
 * Render the box.
 *
 * @param WP_Post $post Post.
 */
function aquariende_render_meta_box( $post ) {
	$fields = aquariende_meta_fields();
	$fields = isset( $fields[ $post->post_type ] ) ? $fields[ $post->post_type ] : array();
	wp_nonce_field( 'aquariende_fields', 'aquariende_fields_nonce' );
	$key = aquariende_key_of( $post->ID );
	if ( $key ) {
		echo '<p class="description">Installed by the theme (content key <code>' . esc_html( $key ) . '</code>). Once you edit this item, later theme versions leave it as you left it.</p>';
	}
	echo '<table class="form-table" role="presentation"><tbody>';
	foreach ( $fields as $meta_key => $field ) {
		$value   = (string) get_post_meta( $post->ID, $meta_key, true );
		$display = aquariende_meta_display( $field['type'], $value );
		$id      = 'aq-' . sanitize_html_class( $meta_key );
		echo '<tr><th scope="row"><label for="' . esc_attr( $id ) . '">' . esc_html( $field['label'] ) . '</label></th><td>';
		echo '<input type="hidden" name="aq_orig[' . esc_attr( $meta_key ) . ']" value="' . esc_attr( md5( $display ) ) . '">';
		switch ( $field['type'] ) {
			case 'answer':
			case 'textarea':
			case 'sources':
				$rows = ( 'sources' === $field['type'] ) ? 6 : 3;
				echo '<textarea class="large-text" rows="' . (int) $rows . '" id="' . esc_attr( $id ) . '" name="aq_meta[' . esc_attr( $meta_key ) . ']"' . ( 'answer' === $field['type'] ? ' data-aq-words="1"' : '' ) . '>' . esc_textarea( $display ) . '</textarea>';
				if ( 'answer' === $field['type'] ) {
					echo '<p class="description" id="' . esc_attr( $id ) . '-count" aria-live="polite"></p>';
				}
				break;
			case 'checkbox':
				echo '<input type="hidden" name="aq_meta[' . esc_attr( $meta_key ) . ']" value="0"><input type="checkbox" id="' . esc_attr( $id ) . '" name="aq_meta[' . esc_attr( $meta_key ) . ']" value="1"' . checked( '1', $value, false ) . '>';
				break;
			default:
				echo '<input type="' . ( 'url' === $field['type'] ? 'url' : 'text' ) . '" class="regular-text" id="' . esc_attr( $id ) . '" name="aq_meta[' . esc_attr( $meta_key ) . ']" value="' . esc_attr( $display ) . '"' . ( 'date' === $field['type'] ? ' placeholder="YYYY-MM-DD" pattern="\d{4}-\d{2}-\d{2}"' : '' ) . '>';
		}
		echo '</td></tr>';
	}
	echo '</tbody></table>';
	?>
	<script>
	document.querySelectorAll('[data-aq-words]').forEach(function (t) {
		var out = document.getElementById(t.id + '-count');
		function count() {
			var n = t.value.trim() ? t.value.trim().split(/\s+/).length : 0;
			out.textContent = n + ' words' + (n && (n < 40 || n > 60) ? ' (aim for 40 to 60)' : '');
			out.style.color = n && (n < 40 || n > 60) ? '#b32d2e' : '';
		}
		t.addEventListener('input', count);
		count();
	});
	</script>
	<?php
}

/**
 * Save the box. A field is written only when its text changed, so opening
 * and saving a page does not mark untouched fields as edited.
 *
 * @param int $post_id Post ID.
 */
function aquariende_save_meta_box( $post_id ) {
	if ( ! isset( $_POST['aquariende_fields_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['aquariende_fields_nonce'] ) ), 'aquariende_fields' ) ) {
		return;
	}
	if ( ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) || ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}
	$type   = get_post_type( $post_id );
	$fields = aquariende_meta_fields();
	$fields = isset( $fields[ $type ] ) ? $fields[ $type ] : array();
	$input  = isset( $_POST['aq_meta'] ) && is_array( $_POST['aq_meta'] ) ? wp_unslash( $_POST['aq_meta'] ) : array(); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput -- sanitized per field below.
	$orig   = isset( $_POST['aq_orig'] ) && is_array( $_POST['aq_orig'] ) ? wp_unslash( $_POST['aq_orig'] ) : array(); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput -- compared only.

	foreach ( $fields as $meta_key => $field ) {
		if ( ! isset( $input[ $meta_key ] ) ) {
			continue;
		}
		$raw = (string) $input[ $meta_key ];
		$raw = str_replace( "\r\n", "\n", $raw );
		if ( isset( $orig[ $meta_key ] ) && md5( $raw ) === $orig[ $meta_key ] ) {
			continue;
		}
		switch ( $field['type'] ) {
			case 'sources':
				$list = array();
				foreach ( preg_split( '/\n+/', trim( $raw ) ) as $line ) {
					$parts = array_map( 'trim', explode( '|', $line ) );
					if ( count( $parts ) >= 3 && wp_http_validate_url( $parts[2] ) ) {
						$list[] = array(
							'publisher' => sanitize_text_field( $parts[0] ),
							'title'     => sanitize_text_field( $parts[1] ),
							'url'       => esc_url_raw( $parts[2] ),
							'accessed'  => isset( $parts[3] ) && preg_match( '/^\d{4}-\d{2}-\d{2}$/', $parts[3] ) ? $parts[3] : '',
						);
					}
				}
				$value = $list ? wp_json_encode( $list, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE ) : '';
				break;
			case 'url':
				$value = esc_url_raw( trim( $raw ) );
				break;
			case 'date':
				$value = preg_match( '/^\d{4}-\d{2}-\d{2}$/', trim( $raw ) ) ? trim( $raw ) : '';
				break;
			case 'checkbox':
				$value = '1' === $raw ? '1' : '0';
				break;
			case 'person':
				$value = sanitize_title( $raw );
				break;
			case 'answer':
			case 'textarea':
				$value = sanitize_textarea_field( $raw );
				break;
			default:
				$value = sanitize_text_field( $raw );
		}
		update_post_meta( $post_id, $meta_key, wp_slash( $value ) );
	}
}
add_action( 'save_post', 'aquariende_save_meta_box' );

/* -------------------------------------------------------------------------
 * Guides list: "Next review" column, red when due
 * ---------------------------------------------------------------------- */

/**
 * Columns.
 *
 * @param array $cols Columns.
 * @return array
 */
function aquariende_guide_columns( $cols ) {
	$cols['aq_reviewed'] = 'Last reviewed';
	$cols['aq_next']     = 'Next review';
	return $cols;
}
add_filter( 'manage_aq_guide_posts_columns', 'aquariende_guide_columns' );

/**
 * Column values.
 *
 * @param string $col     Column.
 * @param int    $post_id Post ID.
 */
function aquariende_guide_column( $col, $post_id ) {
	if ( 'aq_reviewed' === $col ) {
		echo esc_html( (string) get_post_meta( $post_id, '_aq_reviewed', true ) );
	} elseif ( 'aq_next' === $col ) {
		$next = (string) get_post_meta( $post_id, '_aq_next_review', true );
		$due  = $next && $next < wp_date( 'Y-m-d' );
		echo $due ? '<strong style="color:#b32d2e">' . esc_html( $next ) . ' (due)</strong>' : esc_html( $next );
	}
}
add_action( 'manage_aq_guide_posts_custom_column', 'aquariende_guide_column', 10, 2 );

/* -------------------------------------------------------------------------
 * Checks shown on the admin screen
 * ---------------------------------------------------------------------- */

/**
 * Number of distinct published items whose text links to a guide, plus the
 * structural links every guide gets (pillar list, /guides/, journey order).
 *
 * @param WP_Post $guide Guide.
 * @return int
 */
function aquariende_guide_inbound( $guide ) {
	global $wpdb;
	$path  = wp_make_link_relative( get_permalink( $guide ) );
	$key   = aquariende_key_of( $guide->ID );
	$like1 = '%' . $wpdb->esc_like( $path ) . '%';
	$like2 = '%' . $wpdb->esc_like( 'key="' . $key . '"' ) . '%';
	// phpcs:ignore WordPress.DB.DirectDatabaseQuery
	$count = (int) $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(DISTINCT ID) FROM {$wpdb->posts} WHERE post_status = 'publish' AND post_type IN ('page','aq_guide','post','aq_faq') AND ID <> %d AND (post_content LIKE %s OR post_content LIKE %s)", $guide->ID, $like1, $like2 ) );
	$related = 0;
	foreach ( aquariende_guides_in_order() as $other ) {
		if ( $other->ID !== $guide->ID && in_array( $key, array_map( 'trim', explode( ',', (string) get_post_meta( $other->ID, '_aq_related', true ) ) ), true ) ) {
			++$related;
		}
	}
	$structural = 2; // The pillar's stage list and the /guides/ archive.
	return $count + $related + $structural;
}

/**
 * Quality warnings for one guide (06 §3.7).
 *
 * @param WP_Post $guide Guide.
 * @return string[]
 */
function aquariende_guide_warnings( $guide ) {
	$warn   = array();
	$answer = trim( (string) get_post_meta( $guide->ID, '_aq_answer', true ) );
	$words  = $answer ? count( preg_split( '/\s+/', $answer ) ) : 0;
	if ( $words < 40 || $words > 60 ) {
		$warn[] = sprintf( 'answer block has %d words (40–60)', $words );
	}
	if ( ! aquariende_post_sources( $guide->ID ) ) {
		$warn[] = 'no sources';
	}
	if ( ! aquariende_faqs_for( aquariende_key_of( $guide->ID ) ) ) {
		$warn[] = 'no FAQ items';
	}
	$inbound = aquariende_guide_inbound( $guide );
	if ( $inbound < 3 ) {
		$warn[] = sprintf( 'only %d inbound internal links (3 or more)', $inbound );
	}
	if ( ! get_post_meta( $guide->ID, '_aq_reviewed', true ) ) {
		$warn[] = 'no "last reviewed" date';
	}
	return $warn;
}

/* -------------------------------------------------------------------------
 * Appearance → Aquariende content
 * ---------------------------------------------------------------------- */

function aquariende_content_admin_menu() {
	add_theme_page( 'Aquariende content', 'Aquariende content', 'manage_options', 'aquariende-content', 'aquariende_content_admin_page' );
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
		wp_die( 'You do not have permission to do this.' );
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
	$today     = wp_date( 'Y-m-d' );
	$guides    = get_posts(
		array(
			'post_type'   => 'aq_guide',
			'post_status' => 'publish',
			'numberposts' => 200,
		)
	);
	$people    = get_posts(
		array(
			'post_type'   => 'aq_person',
			'post_status' => array( 'publish', 'draft', 'pending', 'private' ),
			'numberposts' => 50,
		)
	);
	$refused   = array_filter(
		is_array( $log ) ? $log : array(),
		function ( $line ) {
			return false !== strpos( $line, 'REFUSED' );
		}
	);
	?>
	<div class="wrap">
		<h1>Aquariende content</h1>

		<?php if ( 'run' === $done ) : ?>
			<div class="notice notice-success"><p>The installer ran. Details are in the log below.</p></div>
		<?php elseif ( 'busy' === $done ) : ?>
			<div class="notice notice-warning"><p>The installer is already running. Try again in a few minutes.</p></div>
		<?php elseif ( 'trash' === $done ) : ?>
			<div class="notice notice-success"><p><?php echo esc_html( sprintf( '%d pages and posts were moved to the trash. You can restore them under Pages or Posts → Trash.', $count ) ); ?></p></div>
		<?php elseif ( 'unconfirmed' === $done ) : ?>
			<div class="notice notice-error"><p>Nothing was moved: please tick the confirmation box first.</p></div>
		<?php endif; ?>

		<?php if ( $refused ) : ?>
			<div class="notice notice-error"><p><strong>Some content was refused because it contains a money amount or currency symbol.</strong> See the lines marked REFUSED in the log.</p></div>
		<?php endif; ?>

		<h2>Status</h2>
		<table class="widefat striped" style="max-width:720px">
			<tbody>
				<tr><th scope="row">Installer version in the theme</th><td><?php echo esc_html( (string) AQUARIENDE_CONTENT_VERSION ); ?></td></tr>
				<tr><th scope="row">Content version installed on this site</th><td><?php echo $installed ? esc_html( (string) $installed ) : 'not installed yet'; ?></td></tr>
				<tr><th scope="row">State</th><td><?php echo ( $installed >= AQUARIENDE_CONTENT_VERSION ) ? 'Up to date' : 'An update is waiting; it runs on the next admin page load.'; ?></td></tr>
				<tr><th scope="row">Last run</th><td><?php echo $last_run ? esc_html( wp_date( get_option( 'date_format' ) . ' ' . get_option( 'time_format' ), $last_run ) ) : 'never'; ?></td></tr>
				<tr><th scope="row">Indexing</th><td><?php echo aquariende_is_noindex_site() ? 'Not indexed (staging: noindex, nofollow; robots.txt disallows all)' : 'Open to search and AI crawlers'; ?></td></tr>
				<tr><th scope="row">robots.txt file on the server</th><td>
					<?php if ( file_exists( ABSPATH . 'robots.txt' ) ) : ?>
						A leftover <code>robots.txt</code> file in the site folder is served instead of the rules from this theme.
						<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" style="margin-top:8px">
							<input type="hidden" name="action" value="aquariende_robots_file">
							<?php wp_nonce_field( 'aquariende_robots_file' ); ?>
							<?php submit_button( 'Rename it so the theme rules apply', 'secondary', 'submit', false ); ?>
						</form>
					<?php else : ?>
						None in the site folder. WordPress serves the rules from this theme. If <code>/robots.txt</code> still shows something else, the host adds it in front of WordPress.
					<?php endif; ?>
				</td></tr>
			</tbody>
		</table>

		<h2>Guides due for review</h2>
		<table class="widefat striped" style="max-width:960px">
			<thead><tr><th>Guide</th><th>Last reviewed</th><th>Next review</th><th>Quality check</th></tr></thead>
			<tbody>
				<?php foreach ( $guides as $aq_g ) : ?>
					<?php
					$aq_next = (string) get_post_meta( $aq_g->ID, '_aq_next_review', true );
					$aq_due  = $aq_next && $aq_next < $today;
					$aq_warn = aquariende_guide_warnings( $aq_g );
					?>
					<tr>
						<td><a href="<?php echo esc_url( (string) get_edit_post_link( $aq_g->ID ) ); ?>"><?php echo esc_html( $aq_g->post_title ); ?></a></td>
						<td><?php echo esc_html( (string) get_post_meta( $aq_g->ID, '_aq_reviewed', true ) ); ?></td>
						<td<?php echo $aq_due ? ' style="color:#b32d2e;font-weight:600"' : ''; ?>><?php echo esc_html( $aq_next . ( $aq_due ? ' (due)' : '' ) ); ?></td>
						<td><?php echo $aq_warn ? '<span style="color:#b32d2e">' . esc_html( implode( '; ', $aq_warn ) ) . '</span>' : 'OK'; ?></td>
					</tr>
				<?php endforeach; ?>
			</tbody>
		</table>

		<h2>Team members</h2>
		<?php
		$aq_missing = array_filter(
			$people,
			function ( $p ) {
				return '1' !== (string) get_post_meta( $p->ID, '_aq_consent', true );
			}
		);
		?>
		<?php if ( $aq_missing ) : ?>
			<div class="notice notice-warning inline"><p><strong>Missing consent:</strong> <?php echo esc_html( implode( ', ', wp_list_pluck( $aq_missing, 'post_title' ) ) ); ?>. These people are not shown anywhere on the site (no card, no page, no author line, no schema) until their written consent is recorded in their "Aquariende fields" box and the entry is published. Confirm their current status (student or alumnus) at the same time.</p></div>
		<?php else : ?>
			<p>Every team member has recorded consent.</p>
		<?php endif; ?>

		<h2>Installed content</h2>
		<?php
		$aq_ids    = get_option( 'aquariende_content_ids', array() );
		$aq_counts = array();
		foreach ( (array) $aq_ids as $aq_key => $aq_id ) {
			$aq_type                = get_post_type( $aq_id );
			$aq_status              = get_post_status( $aq_id );
			$aq_label               = $aq_type . ' (' . $aq_status . ')';
			$aq_counts[ $aq_label ] = isset( $aq_counts[ $aq_label ] ) ? $aq_counts[ $aq_label ] + 1 : 1;
		}
		?>
		<ul>
			<?php foreach ( $aq_counts as $aq_label => $aq_n ) : ?>
				<li><?php echo esc_html( $aq_label . ': ' . $aq_n ); ?></li>
			<?php endforeach; ?>
		</ul>
		<p class="description">Items you edit in WordPress are never overwritten by a later installer version. Delete an item permanently to have it recreated.</p>

		<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
			<input type="hidden" name="action" value="aquariende_content_run">
			<?php wp_nonce_field( 'aquariende_content_run' ); ?>
			<?php submit_button( 'Run installer again', 'primary', 'submit', false ); ?>
		</form>

		<h2>Old content of the previous site</h2>
		<p><?php echo esc_html( sprintf( 'Pages and posts not installed by Aquariende: %1$d. Menus not installed by Aquariende: %2$d.', count( $foreign ), count( $menus ) ) ); ?></p>
		<?php if ( $confirm ) : ?>
			<div class="card" style="max-width:720px">
				<h3>Please confirm</h3>
				<p>These pages and posts will be moved to the trash. Nothing is deleted: you can restore each one under Pages → Trash or Posts → Trash.</p>
				<ul style="list-style:disc;padding-left:20px;max-height:320px;overflow:auto">
					<?php foreach ( $foreign as $aq_post ) : ?>
						<li><?php echo esc_html( sprintf( '%s — %s (#%d, %s)', $aq_post->post_type, '' !== $aq_post->post_title ? $aq_post->post_title : '(no title)', $aq_post->ID, $aq_post->post_status ) ); ?></li>
					<?php endforeach; ?>
				</ul>
				<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
					<input type="hidden" name="action" value="aquariende_content_trash">
					<?php wp_nonce_field( 'aquariende_content_trash' ); ?>
					<p><label><input type="checkbox" name="aq_confirm" value="1" required> I understand that these items move to the trash.</label></p>
					<p>
						<?php submit_button( sprintf( 'Yes, move %d items to the trash', count( $foreign ) ), 'delete', 'submit', false ); ?>
						<a class="button" href="<?php echo esc_url( aquariende_content_admin_url() ); ?>">Cancel</a>
					</p>
				</form>
			</div>
		<?php elseif ( $foreign ) : ?>
			<p>This moves every page and post that the Aquariende installer did not create to the trash, and stops showing their menus. You will see the full list and confirm before anything happens.</p>
			<p><a class="button button-secondary" href="<?php echo esc_url( wp_nonce_url( aquariende_content_admin_url( array( 'aq_step' => 'confirm-trash' ) ), 'aquariende_content_confirm' ) ); ?>">Move the old content to the trash</a></p>
		<?php else : ?>
			<p>Nothing to move: every page and post on this site was installed by Aquariende.</p>
		<?php endif; ?>

		<h2>Log (last 120 lines)</h2>
		<?php if ( is_array( $log ) && $log ) : ?>
			<pre style="background:#fff;border:1px solid #c3c4c7;padding:12px;max-width:960px;max-height:420px;overflow:auto;white-space:pre-wrap"><?php echo esc_html( implode( "\n", array_reverse( $log ) ) ); ?></pre>
		<?php else : ?>
			<p>The log is empty.</p>
		<?php endif; ?>
	</div>
	<?php
}

function aquariende_content_handle_run() {
	if ( ! current_user_can( 'manage_options' ) ) {
		wp_die( 'You do not have permission to do this.', 403 );
	}
	check_admin_referer( 'aquariende_content_run' );
	$ok = aquariende_content_install( 'Run installer again' );
	wp_safe_redirect( aquariende_content_admin_url( array( 'aq_done' => $ok ? 'run' : 'busy' ) ) );
	exit;
}
add_action( 'admin_post_aquariende_content_run', 'aquariende_content_handle_run' );

function aquariende_content_handle_trash() {
	if ( ! current_user_can( 'manage_options' ) ) {
		wp_die( 'You do not have permission to do this.', 403 );
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

/**
 * Renames a leftover robots.txt in the site folder (kept as a dated copy),
 * so WordPress serves the rules from inc/seo.php.
 */
function aquariende_content_handle_robots_file() {
	if ( ! current_user_can( 'manage_options' ) ) {
		wp_die( 'You do not have permission to do this.', 403 );
	}
	check_admin_referer( 'aquariende_robots_file' );
	$file = ABSPATH . 'robots.txt';
	$ok   = false;
	if ( file_exists( $file ) ) {
		$to = ABSPATH . 'robots.txt.replaced-' . gmdate( 'Ymd-His' );
		$ok = @rename( $file, $to ); // phpcs:ignore WordPress.PHP.NoSilencedErrors.Discouraged, WordPress.WP.AlternativeFunctions.rename_rename
		aquariende_content_log( $ok ? 'Renamed the leftover robots.txt to ' . basename( $to ) . '.' : 'Could not rename the leftover robots.txt (file permissions).' );
	}
	wp_safe_redirect( aquariende_content_admin_url( array( 'aq_done' => $ok ? 'robots' : 'robots-failed' ) ) );
	exit;
}
add_action( 'admin_post_aquariende_robots_file', 'aquariende_content_handle_robots_file' );

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
	<div class="notice notice-success">
		<p>
			<?php echo esc_html( sprintf( 'Aquariende content was installed (version %d): pages, guides, glossary, FAQ, menus and settings.', (int) get_option( 'aquariende_content_version', 0 ) ) ); ?>
			<a href="<?php echo esc_url( aquariende_content_admin_url() ); ?>">See what was done</a>
			·
			<a href="<?php echo esc_url( $dismiss ); ?>">Dismiss</a>
		</p>
	</div>
	<?php
}
add_action( 'admin_notices', 'aquariende_content_admin_notice' );

function aquariende_content_handle_dismiss() {
	if ( ! current_user_can( 'manage_options' ) ) {
		wp_die( 'You do not have permission to do this.', 403 );
	}
	check_admin_referer( 'aquariende_content_dismiss' );
	delete_option( 'aquariende_content_notice' );
	wp_safe_redirect( wp_get_referer() ? wp_get_referer() : admin_url() );
	exit;
}
add_action( 'admin_post_aquariende_content_dismiss', 'aquariende_content_handle_dismiss' );
