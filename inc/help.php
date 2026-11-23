<?php
/**
 * "How we help" topics students ask for by name (blocked account, health insurance, travel insurance,
 * the visa appointment and VFS), and what the student portal shows.
 *
 * Every "we do" line is from the company's own published service list [OLD] or its process [PIPE].
 * Every rule is quoted from the official page named beside it, checked on the date given.
 *
 * @package Aquariende
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * The four help topics.
 *
 * @return array<string,array<string,mixed>>
 */
function aquariende_help_topics() {
	$embassy = array(
		'name' => 'German Embassy Dhaka: Information regarding Study Visa',
		'url'  => 'https://dhaka.diplo.de/bd-en/service/2685884-2685884',
	);

	return array(
		'blocked-account'  => array(
			'title'   => 'Blocked account help',
			'short'   => 'Opening the blocked account that proves your money for the visa.',
			'art'     => 'money',
			'part'    => 'Part of the visa application service',
			'rule'    => 'The embassy accepts a blocked account (Sperrkonto) in Germany, in the applicant\'s own name, as proof of money for a student visa. The required balance is set by the German government and changes; our guide shows the current figure with its official source.',
			'source'  => $embassy,
			'checked' => '2026-11-23',
			'we_do'   => array(
				'Assist you in opening the blocked account', // [OLD Visa card].
				'Check that the bank\'s confirmation shows what the embassy asks for before it goes into your visa file', // 05 brief 3c, [PIPE C6].
				'Plan the opening against your visa timeline', // [OLD] "Defer semester according to visa timeline".
			),
			'you_do'  => array(
				'Choose the provider yourself. We recommend none.',
				'Open the account in your own name. The money goes from you to the bank and stays yours.',
			),
			'guide'   => 'blocked-account',
			'wa'      => 'Hello Aquariende! I need help with my blocked account.',
			'wa_code' => 'HELP-BLOCKED',
		),
		'health-insurance' => array(
			'title'   => 'Health insurance',
			'short'   => 'The health cover every student in Germany must have.',
			'art'     => 'health',
			'part'    => 'Extra help, besides the three services',
			'rule'    => 'Everyone in Germany must have health insurance. International students have to prove cover that meets German standards by the time they enrol or, depending on the country, by the time they apply for a visa.',
			'source'  => array(
				'name' => 'DAAD: Healthcare and Health Insurance',
				'url'  => 'https://www.daad.de/en/studying-in-germany/living-in-germany/health-insurance/',
			),
			'checked' => '2026-11-23',
			'we_do'   => array(
				'Assist you in opening a health insurance policy for your studies', // [OLD About].
				'Explain the two kinds DAAD describes, statutory and private, so you can choose', // DAAD page.
			),
			'you_do'  => array(
				'Choose the insurer yourself.',
				'Take the policy in your own name.',
			),
			'guide'   => 'cost-of-studying-in-germany',
			'wa'      => 'Hello Aquariende! I have a question about health insurance for Germany.',
			'wa_code' => 'HELP-HEALTH',
		),
		'travel-insurance' => array(
			'title'   => 'Travel insurance',
			'short'   => 'The travel health cover the embassy asks for in your visa file.',
			'art'     => 'travel',
			'part'    => 'Part of the visa application service',
			'rule'    => 'The embassy\'s checklist asks for travel health insurance valid from your arrival in Germany to the date you enrol at the university (minimum 3 months).',
			'source'  => $embassy,
			'checked' => '2026-11-23',
			'we_do'   => array(
				'Assist you in opening travel insurance', // [OLD Visa card].
				'Check that it covers the period the embassy asks for', // 05 brief 3c.
				'Help with the air ticket and local transport in Germany', // [OLD Visa card].
			),
			'you_do'  => array(
				'Take the policy in your own name.',
				'Tell us your travel date as soon as you know it.',
			),
			'guide'   => 'student-visa-documents-checklist',
			'wa'      => 'Hello Aquariende! I need help with travel insurance for my visa.',
			'wa_code' => 'HELP-TRAVEL',
		),
		'visa-appointment' => array(
			'title'   => 'Visa appointment and VFS',
			'short'   => 'Getting the appointment and handing in a complete file.',
			'art'     => 'visa',
			'part'    => 'Part of the visa application service',
			'rule'    => 'Student visa applicants register online for an appointment. Entry to the embassy\'s visa section, or to its external service provider VFS, is only at the time of the appointment; more than 15 minutes late means a new appointment. Only applications with complete documents are accepted.',
			'source'  => $embassy,
			'checked' => '2026-11-23',
			'we_do'   => array(
				'Assist you in taking the visa appointment', // [OLD Visa card].
				'Prepare your file in the order of the embassy\'s checklist', // 05 brief 3c, [PIPE C6].
				'Fill in the visa form with you and write the letter of intent', // [OLD Visa card].
				'A mock appointment before the real one', // [PIPE C7].
			),
			'you_do'  => array(
				'Register yourself in the Consular Services Portal.', // [E1], [E3].
				'Attend the appointment yourself and pay the visa fee there.', // [E3].
			),
			'never'   => 'A visa. The embassy decides, and no one can guarantee a visa.',
			'guide'   => 'german-student-visa-from-bangladesh',
			'wa'      => 'Hello Aquariende! I need help with my visa appointment.',
			'wa_code' => 'HELP-VFS',
		),
	);
}

/**
 * Address of one help topic (a section of the visa application service page).
 *
 * @param string $key Topic key.
 * @return string
 */
function aquariende_help_url( $key ) {
	return aquariende_page_url( 'visa-application' ) . '#help-' . $key;
}

/**
 * The guide a help topic rests on, if it is published.
 *
 * @param string $slug Guide slug.
 * @return WP_Post|null
 */
function aquariende_help_guide( $slug ) {
	$found = get_posts(
		array(
			'post_type'   => 'aq_guide',
			'name'        => $slug,
			'post_status' => 'publish',
			'numberposts' => 1,
		)
	);
	return $found ? $found[0] : null;
}

/**
 * The help topics a guide leads to (by guide slug).
 *
 * @param string $slug Guide slug.
 * @return string[]
 */
function aquariende_help_for_guide( $slug ) {
	$map = array(
		'blocked-account'                     => array( 'blocked-account' ),
		'cost-of-studying-in-germany'         => array( 'blocked-account', 'health-insurance' ),
		'german-student-visa-from-bangladesh' => array( 'visa-appointment', 'travel-insurance' ),
		'student-visa-documents-checklist'    => array( 'visa-appointment', 'travel-insurance', 'blocked-account' ),
	);
	return isset( $map[ $slug ] ) ? $map[ $slug ] : array();
}

/**
 * What a student sees in the portal (each line is a part of the portal's first screen).
 *
 * @return string[]
 */
function aquariende_portal_shows() {
	return array(
		'Your journey in four parts, each marked done, in progress or not started',
		'Every application: the university, the programme, its deadline and its status',
		'Your documents: what is uploaded and how many are still missing',
		'The next step, who is doing it (you or your consultant) and when it is due',
		'Your consultant, with a button to send a message',
	);
}
