<?php
/**
 * Breadcrumbs: <nav aria-label="Breadcrumb"><ol>. Not shown on Home.
 *
 * @package Aquariende
 */

$aq_trail = aquariende_breadcrumbs();
if ( count( $aq_trail ) < 2 ) {
	return;
}
$aq_last = count( $aq_trail ) - 1;
?>
<nav class="crumbs" aria-label="Breadcrumb">
	<ol>
		<?php foreach ( $aq_trail as $aq_i => $aq_crumb ) : ?>
			<li>
				<?php if ( $aq_i === $aq_last || '' === $aq_crumb[1] ) : ?>
					<span aria-current="page"><?php echo esc_html( $aq_crumb[0] ); ?></span>
				<?php else : ?>
					<a href="<?php echo esc_url( $aq_crumb[1] ); ?>"><?php echo esc_html( $aq_crumb[0] ); ?></a>
				<?php endif; ?>
			</li>
		<?php endforeach; ?>
	</ol>
</nav>
