<?php
/**
 * Author box for guides: the writer and reviewer, when they consented.
 * Without consent, an organisation box says who prepared the guide and how.
 *
 * Args: post_id.
 *
 * @package Aquariende
 */

$aq_pid    = isset( $args['post_id'] ) ? (int) $args['post_id'] : get_the_ID();
$aq_people = array_filter(
	array(
		'Written by'  => aquariende_person_by_slug( get_post_meta( $aq_pid, '_aq_author', true ) ),
		'Reviewed by' => aquariende_person_by_slug( get_post_meta( $aq_pid, '_aq_reviewer', true ) ),
	)
);
?>
<aside class="author-box" aria-label="Who prepared this guide">
	<?php if ( $aq_people ) : ?>
		<?php foreach ( $aq_people as $aq_label => $aq_p ) : ?>
			<div class="author-row">
				<p class="list-label"><?php echo esc_html( $aq_label ); ?></p>
				<p class="author-name"><a href="<?php echo esc_url( get_permalink( $aq_p ) ); ?>"><?php echo esc_html( get_the_title( $aq_p ) ); ?></a></p>
				<p class="author-meta"><?php echo esc_html( trim( get_post_meta( $aq_p->ID, '_aq_role', true ) . ' · ' . get_post_meta( $aq_p->ID, '_aq_university', true ), ' ·' ) ); ?></p>
			</div>
		<?php endforeach; ?>
	<?php else : ?>
		<p class="list-label">Who prepared this guide</p>
		<p class="author-name">The Aquariende team</p>
		<p class="author-meta">Every rule on this page is taken from the official sources listed above and was checked on the review date. Where a figure changes, we link the official page instead of copying it.</p>
	<?php endif; ?>
</aside>
