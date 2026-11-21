<?php
/**
 * Team member card. Rendered only for a published member with consent.
 *
 * Args: person (WP_Post), heading (h3|p).
 *
 * @package Aquariende
 */

$aq_person = isset( $args['person'] ) ? $args['person'] : null;
if ( ! $aq_person || ! aquariende_person_ok( $aq_person ) ) {
	return;
}
$aq_photo = (string) get_post_meta( $aq_person->ID, '_aq_photo', true );
$aq_uni   = (string) get_post_meta( $aq_person->ID, '_aq_university', true );
$aq_prog  = (string) get_post_meta( $aq_person->ID, '_aq_programme', true );
$aq_role  = (string) get_post_meta( $aq_person->ID, '_aq_role', true );
$aq_stat  = (string) get_post_meta( $aq_person->ID, '_aq_status', true );
?>
<li class="team-card">
	<a class="team-link" href="<?php echo esc_url( get_permalink( $aq_person ) ); ?>">
		<span class="team-photo">
			<?php
			if ( has_post_thumbnail( $aq_person ) ) {
				echo get_the_post_thumbnail( $aq_person, 'medium', array( 'alt' => '', 'loading' => 'lazy' ) );
			} elseif ( $aq_photo && is_readable( AQUARIENDE_DIR . '/assets/img/' . $aq_photo . '.webp' ) ) {
				echo aquariende_picture( $aq_photo, '', array( 'width' => 480, 'height' => 600 ) ); // phpcs:ignore WordPress.Security.EscapeOutput -- escaped inside.
			}
			?>
		</span>
		<span class="team-name"><?php echo esc_html( get_the_title( $aq_person ) ); ?></span>
	</a>
	<?php if ( $aq_role ) : ?>
		<p class="team-role"><?php echo esc_html( $aq_role ); ?></p>
	<?php endif; ?>
	<?php if ( $aq_uni ) : ?>
		<p class="team-uni"><?php echo aquariende_icon( 'cap' ); // phpcs:ignore WordPress.Security.EscapeOutput ?><span><?php echo esc_html( $aq_uni ); ?><?php echo $aq_prog ? '<small>' . esc_html( $aq_prog ) . '</small>' : ''; ?><?php echo $aq_stat ? '<small>' . esc_html( $aq_stat ) . '</small>' : ''; ?></span></p>
	<?php endif; ?>
</li>
