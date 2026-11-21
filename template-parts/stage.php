<?php
/**
 * One stage of "How it works". On wide screens with motion, main.js pins the
 * section and drives a shared file card; otherwise each stage shows its own.
 *
 * Args: index (0-2), stage (array from aquariende_stages()).
 *
 * @package Aquariende
 */

$aq_args  = isset( $args ) && is_array( $args ) ? $args : array();
$aq_index = isset( $aq_args['index'] ) ? (int) $aq_args['index'] : 0;
$aq_stage = isset( $aq_args['stage'] ) ? $aq_args['stage'] : array();
$aq_n     = $aq_index + 1;
?>
<article class="stage" id="stage-<?php echo esc_attr( $aq_n ); ?>" data-stage="<?php echo esc_attr( $aq_n ); ?>" data-reveal>
	<div class="stage-copy">
		<p class="stage-num" aria-hidden="true"><?php echo esc_html( sprintf( '%02d', $aq_n ) ); ?></p>
		<h3 class="stage-title"><?php echo esc_html( $aq_stage['name'] ); ?></h3>
		<p class="stage-tagline"><?php echo esc_html( $aq_stage['tagline'] ); ?></p>
		<p class="stage-text"><?php echo esc_html( $aq_stage['summary'] ); ?></p>
	</div>
	<div class="stage-file">
		<?php
		get_template_part(
			'template-parts/file-card',
			null,
			array(
				'stage' => $aq_n,
				'id'    => 'stage-file-' . $aq_n,
				'class' => 'file-card--small',
			)
		);
		?>
	</div>
</article>
