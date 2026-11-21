<?php
/**
 * Team member card. The university shows on hover and on focus; it is always
 * in the markup for screen readers and always visible on touch screens.
 *
 * Args: member (array from aquariende_team()), index (int).
 *
 * @package Aquariende
 */

$aq_args   = isset( $args ) && is_array( $args ) ? $args : array();
$aq_member = isset( $aq_args['member'] ) ? $aq_args['member'] : array();
$aq_index  = isset( $aq_args['index'] ) ? (int) $aq_args['index'] : 0;
?>
<li class="team-card" style="--i:<?php echo esc_attr( $aq_index ); ?>" data-reveal tabindex="0">
	<div class="team-photo">
		<?php
		echo aquariende_picture( // phpcs:ignore WordPress.Security.EscapeOutput -- escaped inside.
			$aq_member['slug'],
			/* translators: %s: person's name. */
			sprintf( __( 'Portrait of %s', 'aquariende' ), $aq_member['name'] ),
			array(
				'width'  => 480,
				'height' => 600,
			)
		);
		?>
		<p class="team-uni"><?php echo aquariende_icon( 'cap' ); // phpcs:ignore WordPress.Security.EscapeOutput ?><span><?php echo esc_html( $aq_member['university'] ); ?><small><?php echo esc_html( $aq_member['study'] ); ?></small></span></p>
	</div>
	<p class="team-name"><?php echo esc_html( $aq_member['name'] ); ?></p>
	<p class="team-role"><?php echo esc_html( $aq_member['role'] ); ?></p>
</li>
