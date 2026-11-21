<?php
/**
 * A package card with included / not-included checklists. No prices.
 *
 * Args: index (0-2), stage (array), featured (bool), heading (h2|h3).
 *
 * @package Aquariende
 */

$aq_args     = isset( $args ) && is_array( $args ) ? $args : array();
$aq_index    = isset( $aq_args['index'] ) ? (int) $aq_args['index'] : 0;
$aq_stage    = isset( $aq_args['stage'] ) ? $aq_args['stage'] : array();
$aq_featured = ! empty( $aq_args['featured'] );
$aq_tag      = ( isset( $aq_args['heading'] ) && 'h2' === $aq_args['heading'] ) ? 'h2' : 'h3';
$aq_n        = $aq_index + 1;
$aq_bare     = ! empty( $aq_args['bare'] );
?>
<article class="package<?php echo $aq_featured ? ' package--featured' : ''; ?><?php echo $aq_bare ? ' package--bare' : ''; ?>"<?php echo $aq_bare ? '' : ' id="package-' . esc_attr( $aq_stage['key'] ) . '"'; ?> data-reveal-list>
	<?php if ( ! $aq_bare ) : ?>
	<header class="package-head">
		<p class="package-num">
			<?php
			/* translators: %d: stage number. */
			echo esc_html( sprintf( __( 'Stage %d', 'aquariende' ), $aq_n ) );
			?>
		</p>
		<<?php echo $aq_tag; // phpcs:ignore WordPress.Security.EscapeOutput -- h2 or h3. ?> class="package-title"><?php echo esc_html( $aq_stage['name'] ); ?></<?php echo $aq_tag; // phpcs:ignore WordPress.Security.EscapeOutput ?>>
		<p class="package-tagline"><?php echo esc_html( $aq_stage['tagline'] ); ?></p>
	</header>
	<?php endif; ?>

	<p class="list-label"><?php esc_html_e( 'Included', 'aquariende' ); ?></p>
	<ul class="checklist">
		<?php foreach ( $aq_stage['included'] as $aq_i => $aq_item ) : ?>
			<li style="--i:<?php echo esc_attr( $aq_i ); ?>"><?php echo aquariende_icon( 'check' ); // phpcs:ignore WordPress.Security.EscapeOutput ?><span><?php echo esc_html( $aq_item ); ?></span></li>
		<?php endforeach; ?>
	</ul>

	<?php if ( ! empty( $aq_stage['excluded'] ) ) : ?>
		<p class="list-label"><?php esc_html_e( 'Not included', 'aquariende' ); ?></p>
		<ul class="checklist checklist--no">
			<?php foreach ( $aq_stage['excluded'] as $aq_item ) : ?>
				<li><?php echo aquariende_icon( 'cross' ); // phpcs:ignore WordPress.Security.EscapeOutput ?><span><?php echo esc_html( $aq_item ); ?></span></li>
			<?php endforeach; ?>
		</ul>
	<?php endif; ?>

	<div class="package-foot">
		<a class="btn <?php echo $aq_featured ? 'btn-primary' : 'btn-outline'; ?> btn-block" href="<?php echo esc_url( aquariende_whatsapp_url( $aq_stage['wa'] ) ); ?>" target="_blank" rel="noopener">
			<?php echo aquariende_icon( 'whatsapp' ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
			<span><?php esc_html_e( 'Start on WhatsApp', 'aquariende' ); ?><span class="screen-reader-text"><?php echo esc_html( ': ' . $aq_stage['name'] ); ?></span></span>
		</a>
	</div>
</article>
