<?php
/**
 * Installer items that left the information architecture.
 *
 * 'pages': content keys of pages installed by an earlier version that are no
 * longer part of the site. Each is moved to the trash, but only if it still
 * carries the installer key and nobody edited it since it was installed.
 * (Version 2 removes no page: every version 1 page is still in the site map;
 * the legal pages wait for their rewrite.)
 *
 * Posts are retired through 'status' => 'draft' in posts.php.
 *
 * @package Aquariende
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

return array(
	'pages' => array(),
);
