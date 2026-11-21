<?php
/**
 * The "open file": an illustration of the client portal's step bar.
 *
 * Args:
 *   stage   int    1-3. Rows up to this stage are ticked, the next one is in progress.
 *   current int    Row that carries the highlighter (defaults to stage).
 *   id      string Unique id prefix.
 *   class   string Extra classes on the <figure>.
 *   caption bool   Show the "illustration" caption.
 *
 * @package Aquariende
 */

$aq_args  = isset( $args ) && is_array( $args ) ? $args : array();
$aq_stage = isset( $aq_args['stage'] ) ? max( 1, min( 3, (int) $aq_args['stage'] ) ) : 1;
$aq_id    = isset( $aq_args['id'] ) ? sanitize_html_class( $aq_args['id'] ) : 'file-' . wp_rand( 100, 999 );
$aq_class = isset( $aq_args['class'] ) ? $aq_args['class'] : '';
$aq_cap   = ! empty( $aq_args['caption'] );
$aq_cur   = isset( $aq_args['current'] ) ? (int) $aq_args['current'] : $aq_stage; // Row with the highlighter.

$aq_stages = aquariende_stages();
$aq_labels = array(
	__( 'Profile', 'aquariende' ),
	__( 'Application', 'aquariende' ),
	__( 'Visa', 'aquariende' ),
);

$aq_state = function ( $i ) use ( $aq_stage ) {
	if ( $i <= $aq_stage ) {
		return 'is-done';
	}
	return ( $i === $aq_stage + 1 ) ? 'is-active' : 'is-todo';
};

$aq_state_words = array(
	'is-done'   => __( 'done', 'aquariende' ),
	'is-active' => __( 'in progress', 'aquariende' ),
	'is-todo'   => __( 'not started', 'aquariende' ),
);
$aq_desc_parts  = array();
foreach ( $aq_stages as $aq_i => $aq_s ) {
	$aq_desc_parts[] = $aq_s['short'] . ': ' . $aq_state_words[ $aq_state( $aq_i + 1 ) ];
}
?>
<figure class="file-card <?php echo esc_attr( $aq_class ); ?>" data-stage="<?php echo esc_attr( $aq_stage ); ?>">
	<svg class="file-svg" viewBox="0 0 440 344" width="440" height="344" role="img" aria-labelledby="<?php echo esc_attr( $aq_id ); ?>-t <?php echo esc_attr( $aq_id ); ?>-d">
		<title id="<?php echo esc_attr( $aq_id ); ?>-t"><?php esc_html_e( 'Example student file in the Aquariende portal', 'aquariende' ); ?></title>
		<desc id="<?php echo esc_attr( $aq_id ); ?>-d"><?php echo esc_html( implode( '; ', $aq_desc_parts ) ); ?></desc>

		<g class="fc-body">
			<path class="fc-folder" d="M16 26V10A10 10 0 0 1 26 0h148a10 10 0 0 1 8.7 5L194 26Z"/>
			<rect class="fc-folder" x="0" y="18" width="440" height="326" rx="18"/>
			<text class="fc-tab" x="32" y="16"><?php esc_html_e( 'FILE · SADIA R.', 'aquariende' ); ?></text>
			<rect class="fc-sheet" x="12" y="34" width="416" height="298" rx="12"/>

			<text class="fc-meta" x="38" y="60"><?php esc_html_e( 'Aquariende portal', 'aquariende' ); ?></text>
			<text class="fc-meta" x="402" y="60" text-anchor="end"><?php esc_html_e( 'Ref. AQ-0142', 'aquariende' ); ?></text>

			<?php
			foreach ( array( 38, 164, 290 ) as $aq_i => $aq_x ) :
				$aq_n = $aq_i + 1;
				?>
				<rect class="fc-seg <?php echo esc_attr( $aq_state( $aq_n ) ); ?>" data-row="<?php echo esc_attr( $aq_n ); ?>" x="<?php echo esc_attr( $aq_x ); ?>" y="74" width="112" height="6" rx="3"/>
				<text class="fc-seglabel" x="<?php echo esc_attr( $aq_x ); ?>" y="98"><?php echo esc_html( $aq_n . ' · ' . $aq_labels[ $aq_i ] ); ?></text>
			<?php endforeach; ?>
		</g>

		<?php
		foreach ( $aq_stages as $aq_i => $aq_s ) :
			$aq_n   = $aq_i + 1;
			$aq_top = 114 + $aq_i * 52;
			$aq_cy  = $aq_top + 26;
			$aq_hlw = (int) round( ( function_exists( 'mb_strlen' ) ? mb_strlen( $aq_s['short'] ) : strlen( $aq_s['short'] ) ) * 8.6 + 10 );
			?>
			<g class="fc-row <?php echo esc_attr( $aq_state( $aq_n ) ); ?><?php echo ( $aq_n === $aq_cur ) ? ' is-current' : ''; ?>" data-row="<?php echo esc_attr( $aq_n ); ?>">
				<line class="fc-rule" x1="38" y1="<?php echo esc_attr( $aq_top ); ?>" x2="402" y2="<?php echo esc_attr( $aq_top ); ?>"/>
				<rect class="fc-hl" x="80" y="<?php echo esc_attr( $aq_top + 8 ); ?>" width="<?php echo esc_attr( $aq_hlw ); ?>" height="19" rx="3"/>
				<g class="fc-ic fc-ic-todo"><circle cx="58" cy="<?php echo esc_attr( $aq_cy ); ?>" r="12"/></g>
				<g class="fc-ic fc-ic-active"><circle cx="58" cy="<?php echo esc_attr( $aq_cy ); ?>" r="13"/><path d="M54 <?php echo esc_attr( $aq_cy - 6 ); ?>l9 6-9 6z"/></g>
				<g class="fc-ic fc-ic-done"><circle cx="58" cy="<?php echo esc_attr( $aq_cy ); ?>" r="13"/><path d="m51.5 <?php echo esc_attr( $aq_cy ); ?> 4.5 4.5 8.5-9"/></g>
				<text class="fc-title" x="84" y="<?php echo esc_attr( $aq_top + 23 ); ?>"><?php echo esc_html( $aq_s['short'] ); ?></text>
				<text class="fc-note" x="84" y="<?php echo esc_attr( $aq_top + 41 ); ?>"><?php echo esc_html( $aq_s['file_note'] ); ?></text>
			</g>
		<?php endforeach; ?>

		<g class="fc-stamp" transform="translate(364 138) rotate(-9)">
			<g class="fc-stamp-inner">
				<rect x="-48" y="-21" width="96" height="42" rx="5"/>
				<text class="fc-stamp-s" x="0" y="-5" text-anchor="middle"><?php esc_html_e( 'PROFILE', 'aquariende' ); ?></text>
				<text class="fc-stamp-l" x="0" y="13" text-anchor="middle"><?php esc_html_e( 'CHECKED', 'aquariende' ); ?></text>
			</g>
		</g>

		<g class="fc-foot">
			<line class="fc-rule" x1="38" y1="282" x2="402" y2="282"/>
			<circle class="fc-due-dot" cx="43" cy="304" r="4"/>
			<text class="fc-due" x="54" y="308"><?php esc_html_e( 'Next deadline · 15 Jan', 'aquariende' ); ?></text>
			<text class="fc-meta" x="402" y="308" text-anchor="end"><?php esc_html_e( 'Updated today', 'aquariende' ); ?></text>
		</g>
	</svg>
	<?php if ( $aq_cap ) : ?>
		<figcaption class="file-caption"><?php esc_html_e( 'Illustration of the student portal. Paying clients see their own file like this.', 'aquariende' ); ?></figcaption>
	<?php endif; ?>
</figure>
