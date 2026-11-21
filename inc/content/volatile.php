<?php
/**
 * Facts that change (VolatileFact) and timing rules (DeadlineFact).
 *
 * Rule (03 §2): if a number would be wrong next year, it is never written in
 * the text. It lives behind a link to the official page instead. Used through
 * the shortcodes [aq_volatile topic="…"] and [aq_deadline topic="…"].
 *
 * 'volatile' topics: authority, url, checked, what (what the figure is).
 * 'deadline' topics: rule (a stable rule, worded as the source words it), url, publisher, checked.
 *
 * @package Aquariende
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

return array(
	'volatile' => array(
		'blocked-account'  => array(
			'what'      => 'The amount you must hold in a blocked account',
			'authority' => 'the German authorities',
			'link'      => 'German Embassy Dhaka: Information regarding Study Visa',
			'url'       => 'https://dhaka.diplo.de/bd-en/service/2685884-2685884',
			'checked'   => '2026-11-21',
		),
		'visa-wait'        => array(
			'what'      => 'The waiting time for a student visa appointment',
			'authority' => 'the German Embassy Dhaka',
			'link'      => 'German Embassy Dhaka: Information regarding Study Visa',
			'url'       => 'https://dhaka.diplo.de/bd-en/service/2685884-2685884',
			'checked'   => '2026-11-21',
		),
		'visa-processing'  => array(
			'what'      => 'The processing time after your visa appointment',
			'authority' => 'the German Embassy Dhaka',
			'link'      => 'German Embassy Dhaka: Information regarding Study Visa',
			'url'       => 'https://dhaka.diplo.de/bd-en/service/2685884-2685884',
			'checked'   => '2026-11-21',
		),
		'visa-fee'         => array(
			'what'      => 'The national visa fee',
			'authority' => 'the German Embassy Dhaka (fixed in euro, paid in taka cash)',
			'link'      => 'German Embassy Dhaka: Information regarding Study Visa',
			'url'       => 'https://dhaka.diplo.de/bd-en/service/2685884-2685884',
			'checked'   => '2026-11-21',
		),
		'vpd-processing'   => array(
			'what'      => 'How long uni-assist usually takes to issue a VPD',
			'authority' => 'uni-assist',
			'link'      => 'uni-assist: VPD – preliminary review documentation',
			'url'       => 'https://uni-assist.de/en/how-to-apply/plan-your-application/vpd',
			'checked'   => '2026-11-21',
		),
		'uni-assist-fee'   => array(
			'what'      => 'The uni-assist handling fee',
			'authority' => 'uni-assist (paid only through its own portal)',
			'link'      => 'uni-assist official website',
			'url'       => 'https://www.uni-assist.de/en/',
			'checked'   => '2026-11-21',
		),
		'tuition'          => array(
			'what'      => 'Tuition fees and the semester contribution',
			'authority' => 'each German state and university',
			'link'      => 'DAAD: Costs of education and living',
			'url'       => 'https://www.daad.de/en/studying-in-germany/living-in-germany/finances/',
			'checked'   => '2026-11-21',
		),
		'deadlines'        => array(
			'what'      => 'The application deadline for your programme',
			'authority' => 'each university, per programme',
			'link'      => 'JLU Giessen: Application deadlines (one example)',
			'url'       => 'https://www.uni-giessen.de/en/study/application/deadlines',
			'checked'   => '2026-11-21',
		),
	),
	'deadline' => array(
		'vpd-8-weeks'      => array(
			'rule'      => 'uni-assist advises requesting your VPD as early as possible, ideally at least 8 weeks before the application deadline.',
			'publisher' => 'uni-assist',
			'url'       => 'https://uni-assist.de/en/how-to-apply/plan-your-application/vpd',
			'checked'   => '2026-11-21',
		),
		'visa-3-months'    => array(
			'rule'      => 'The German Embassy Dhaka can issue the student visa only within 3 months of your arrival date in Germany.',
			'publisher' => 'German Embassy Dhaka',
			'url'       => 'https://dhaka.diplo.de/bd-en/service/2685884-2685884',
			'checked'   => '2026-11-21',
		),
		'enrolment-dates'  => array(
			'rule'      => 'The German Embassy Dhaka states that enrolment and examination dates cannot be considered. Plan with your university, not with the embassy, if your visa may come late.',
			'publisher' => 'German Embassy Dhaka',
			'url'       => 'https://dhaka.diplo.de/bd-en/service/2689586-2689586',
			'checked'   => '2026-11-21',
		),
		'admission-letter' => array(
			'rule'      => 'Only applicants with a valid admission letter can register in the Consular Services Portal for a student visa at the German Embassy Dhaka.',
			'publisher' => 'German Embassy Dhaka',
			'url'       => 'https://dhaka.diplo.de/bd-en/service/2685884-2685884',
			'checked'   => '2026-11-21',
		),
		'late-15-minutes'  => array(
			'rule'      => 'If you arrive more than 15 minutes late for your appointment, the embassy schedules a new appointment.',
			'publisher' => 'German Embassy Dhaka',
			'url'       => 'https://dhaka.diplo.de/bd-en/service/2685884-2685884',
			'checked'   => '2026-11-21',
		),
	),
);
