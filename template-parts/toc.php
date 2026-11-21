<?php
/**
 * Table of contents for long guides (three or more H2 sections). No JS.
 *
 * Args: post (WP_Post).
 *
 * @package Aquariende
 */

$aq_post = isset( $args['post'] ) ? $args['post'] : get_post();
$aq_h    = $aq_post ? aquariende_headings( $aq_post ) : array();
if ( count( $aq_h ) < 3 ) {
	return;
}
?>
<nav class="toc" aria-labelledby="toc-title">
	<p id="toc-title" class="list-label">On this page</p>
	<ol>
		<?php foreach ( $aq_h as $aq_item ) : ?>
			<li><a href="#<?php echo esc_attr( $aq_item[0] ); ?>"><?php echo esc_html( $aq_item[1] ); ?></a></li>
		<?php endforeach; ?>
		<li><a href="#sources-title">Official sources</a></li>
	</ol>
</nav>
