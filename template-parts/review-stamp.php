<?php
/**
 * Review stamp: "Last reviewed <date> by <Person>; written by <Person>".
 * A person is named only with their consent; until then the stamp says the
 * page was checked against the official sources it lists.
 *
 * Args: post_id.
 *
 * @package Aquariende
 */

$aq_pid      = isset( $args['post_id'] ) ? (int) $args['post_id'] : get_the_ID();
$aq_reviewed = (string) get_post_meta( $aq_pid, '_aq_reviewed', true );
if ( '' === $aq_reviewed ) {
	return;
}
$aq_author   = aquariende_person_by_slug( get_post_meta( $aq_pid, '_aq_author', true ) );
$aq_reviewer = aquariende_person_by_slug( get_post_meta( $aq_pid, '_aq_reviewer', true ) );
?>
<p class="review-stamp">
	<?php echo aquariende_icon( 'check' ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
	<span>
		Last reviewed <?php echo aquariende_time( $aq_reviewed ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
		<?php if ( $aq_reviewer ) : ?>
			by <a href="<?php echo esc_url( get_permalink( $aq_reviewer ) ); ?>"><?php echo esc_html( get_the_title( $aq_reviewer ) ); ?></a>
		<?php else : ?>
			against the official sources listed on this page
		<?php endif; ?>
		<?php if ( $aq_author ) : ?>
			· written by <a href="<?php echo esc_url( get_permalink( $aq_author ) ); ?>"><?php echo esc_html( get_the_title( $aq_author ) ); ?></a>
		<?php else : ?>
			· prepared by the Aquariende team
		<?php endif; ?>
	</span>
</p>
