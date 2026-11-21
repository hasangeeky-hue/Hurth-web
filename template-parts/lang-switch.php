<?php
/**
 * Language switch: text, not flags. Shown only when Bengali is enabled and the
 * current page has a real translation (never pointing at an untranslated page).
 *
 * @package Aquariende
 */

$aq_pair = function_exists( 'aquariende_translation_pair' ) ? aquariende_translation_pair() : array();
if ( empty( $aq_pair['bn'] ) || empty( $aq_pair['en'] ) ) {
	return;
}
$aq_lang = aquariende_current_lang();
?>
<p class="lang-switch">
	<?php if ( 'bn' === $aq_lang ) : ?>
		<a href="<?php echo esc_url( $aq_pair['en'] ); ?>" hreflang="en" lang="en" data-lang-to="en">English</a>
	<?php else : ?>
		<a href="<?php echo esc_url( $aq_pair['bn'] ); ?>" hreflang="bn" lang="bn" data-lang-to="bn">বাংলা</a>
	<?php endif; ?>
</p>
