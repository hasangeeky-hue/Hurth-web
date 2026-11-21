<?php
/**
 * Search form.
 *
 * @package Aquariende
 */

$aq_sid = wp_unique_id( 'search-' );
?>
<form role="search" method="get" class="search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>">
	<label class="screen-reader-text" for="<?php echo esc_attr( $aq_sid ); ?>"><?php esc_html_e( 'Search the site', 'aquariende' ); ?></label>
	<input type="search" id="<?php echo esc_attr( $aq_sid ); ?>" class="search-field" name="s" value="<?php echo esc_attr( get_search_query() ); ?>" placeholder="<?php esc_attr_e( 'Search guides, for example “blocked account”', 'aquariende' ); ?>">
	<button type="submit" class="btn btn-primary"><?php esc_html_e( 'Search', 'aquariende' ); ?></button>
</form>
