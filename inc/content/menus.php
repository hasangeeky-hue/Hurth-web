<?php
/**
 * Menus installed by the content installer (04 §2). Items point at content
 * keys; 'guides' is the guide archive. Children are nested under 'children'.
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
				array(
					'page'     => 'study-in-germany-from-bangladesh',
					'label'    => 'Study in Germany',
					'children' => array(
						array( 'page' => 'study-in-germany-from-bangladesh', 'label' => 'The steps, in order' ),
						array( 'page' => 'guides', 'label' => 'All guides' ),
						array( 'page' => 'glossary', 'label' => 'Glossary' ),
						array( 'page' => 'faq', 'label' => 'FAQ' ),
						array( 'page' => 'for-parents', 'label' => 'For parents' ),
					),
				),
				array(
					'page'     => 'services',
					'label'    => 'Services',
					'children' => array(
						array( 'page' => 'services', 'label' => 'Compare the three' ),
						array( 'page' => 'profile-check', 'label' => 'Profile check' ),
						array( 'page' => 'university-application', 'label' => 'University application' ),
						array( 'page' => 'visa-application', 'label' => 'Visa application' ),
					),
				),
				array( 'page' => 'how-it-works', 'label' => 'How it works' ),
				array( 'page' => 'about', 'label' => 'About' ),
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
	'categories' => array(
		'process'   => 'Process',
		'documents' => 'Documents',
	),
);
