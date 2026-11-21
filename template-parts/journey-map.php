<?php
/**
 * JourneyMap: the six stages as an ordered list, each linking to its pillar
 * section. aria-current="step" marks the current stage (guides); "Covered"
 * marks the stages a service covers. Scroll-snaps sideways under 600 px.
 *
 * Args: current (stage slug), covered (stage slugs), title, compact (bool).
 *
 * @package Aquariende
 */

$aq_args    = isset( $args ) && is_array( $args ) ? $args : array();
$aq_current = isset( $aq_args['current'] ) ? $aq_args['current'] : '';
$aq_covered = isset( $aq_args['covered'] ) ? (array) $aq_args['covered'] : array();
$aq_title   = isset( $aq_args['title'] ) ? $aq_args['title'] : '';
$aq_compact = ! empty( $aq_args['compact'] );
$aq_pillar  = aquariende_page_url( 'study-in-germany-from-bangladesh' );
$aq_n       = 0;
?>
<nav class="journey<?php echo $aq_compact ? ' journey--compact' : ''; ?>" aria-label="<?php echo esc_attr( $aq_title ? $aq_title : 'The six stages, in order' ); ?>">
	<?php if ( $aq_title ) : ?>
		<p class="journey-title"><?php echo esc_html( $aq_title ); ?></p>
	<?php endif; ?>
	<ol class="journey-list" tabindex="0">
		<?php
		foreach ( aquariende_journey() as $aq_slug => $aq_stage ) :
			++$aq_n;
			$aq_is_current = ( $aq_slug === $aq_current );
			$aq_is_covered = in_array( $aq_slug, $aq_covered, true );
			?>
			<li class="journey-step<?php echo $aq_is_current ? ' is-current' : ''; ?><?php echo $aq_is_covered ? ' is-covered' : ''; ?>">
				<a href="<?php echo esc_url( $aq_pillar . '#' . $aq_slug ); ?>"<?php echo $aq_is_current ? ' aria-current="step"' : ''; ?>>
					<span class="journey-num" aria-hidden="true"><?php echo esc_html( (string) $aq_n ); ?></span>
					<span class="journey-label"><?php echo esc_html( $aq_stage['label'] ); ?></span>
					<?php if ( $aq_is_current ) : ?>
						<span class="journey-tag">You are here</span>
					<?php elseif ( $aq_is_covered ) : ?>
						<span class="journey-tag">Covered</span>
					<?php endif; ?>
					<?php if ( ! $aq_compact ) : ?>
						<span class="journey-sum"><?php echo esc_html( $aq_stage['summary'] ); ?></span>
					<?php endif; ?>
				</a>
			</li>
		<?php endforeach; ?>
	</ol>
</nav>
