<?php
/**
 * Menus and categories installed by the content installer.
 *
 * Menu items point at installer pages by their content key (see pages.php).
 * Labels follow the theme's navigation and the old footer links
 * ("Terms & Condition", "Privacy & Policy", "Refund & Returns", "Cancellation").
 *
 * @package Aquariende
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

return array(
	'menus'      => array(
		'menu-primary' => array(
			'name'     => 'Primary',
			'location' => 'primary',
			'items'    => array(
				array( 'page' => 'services', 'label' => 'Services' ),
				array( 'page' => 'about', 'label' => 'About' ),
				array( 'page' => 'blog', 'label' => 'Blog' ),
				array( 'page' => 'contact', 'label' => 'Contact' ),
			),
		),
		'menu-legal'   => array(
			'name'     => 'Legal',
			'location' => 'footer',
			'items'    => array(
				array( 'page' => 'terms-and-condition', 'label' => 'Terms and Condition' ),
				array( 'page' => 'privacy-policy', 'label' => 'Privacy Policy' ),
				array( 'page' => 'refund_returns', 'label' => 'Refund and Returns' ),
				array( 'page' => 'cancellation', 'label' => 'Cancellation' ),
			),
		),
	),
	// Only the categories the three posts use.
	'categories' => array(
		'process'   => 'Process',
		'documents' => 'Documents',
	),
);
