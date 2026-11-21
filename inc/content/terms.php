<?php
/**
 * Glossary terms (aq_term), rendered on /glossary/#<slug> (05 brief 8).
 * Format: one sentence of definition, one on why it matters, an official link.
 *
 * Left out until an official source is found (see strategy/NOT-BUILT.md):
 * Studienkolleg, Anmeldung.
 *
 * @package Aquariende
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$aq_src = function ( $key ) {
	$s = aquariende_sources();
	return isset( $s[ $key ] ) ? $s[ $key ] : array(
		'url'       => '',
		'publisher' => '',
		'title'     => '',
	);
};

return array(
	'term-vpd'           => array(
		'title'   => 'VPD',
		'slug'    => 'vpd',
		'de'      => 'Vorprüfungsdokumentation',
		'aliases' => 'Vorprüfungsdokumentation',
		'def'     => 'A certificate from uni-assist, issued for one university, that lists your certificates, how uni-assist evaluates them and how it rates your grade on the German scale.',
		'why'     => 'Some universities require it in their own application; it is valid for one year, and uni-assist advises requesting it at least 8 weeks before the deadline.',
		'source'  => $aq_src( 'U1' ),
	),
	'term-uni-assist'    => array(
		'title'   => 'uni-assist',
		'slug'    => 'uni-assist',
		'de'      => '',
		'aliases' => '',
		'def'     => 'The organisation that reviews international applicants\' certificates for the universities that work with it and issues the VPD.',
		'why'     => 'Payment requests come only from uni-assist, through its own portal, and it states that it does not work with commercial agencies.',
		'source'  => $aq_src( 'U1' ),
	),
	'term-anabin'        => array(
		'title'   => 'anabin',
		'slug'    => 'anabin',
		'de'      => '',
		'aliases' => '',
		'def'     => 'The database on foreign qualifications and institutions run by the Central Office for Foreign Education (ZAB) of the German education ministers (KMK).',
		'why'     => 'It shows the status of your university in Bangladesh, and the grade conversion formula uses the ZAB\'s reference grades.',
		'source'  => $aq_src( 'K2' ),
	),
	'term-h-plus'        => array(
		'title'   => 'H+ (institution status)',
		'slug'    => 'h-plus',
		'de'      => '',
		'aliases' => 'H+/-',
		'def'     => 'A status in anabin showing that an institution is a state or state-recognised institution; H+/- means additional criteria apply.',
		'why'     => 'Universities check this status for your previous degree; click the status in the anabin record to see what it means for you.',
		'source'  => $aq_src( 'K2' ),
	),
	'term-bavarian'      => array(
		'title'   => 'Modified Bavarian formula',
		'slug'    => 'modified-bavarian-formula',
		'de'      => 'Modifizierte bayerische Formel',
		'aliases' => 'modified Bavarian formula',
		'def'     => 'The formula agreed by the German education ministers for converting a foreign grade: German grade = 1 + 3 × (best grade − your grade) ÷ (best grade − lowest passing grade).',
		'why'     => 'Online converters only estimate; the conversion by your university or by uni-assist is the one that counts.',
		'source'  => $aq_src( 'K3' ),
	),
	'term-aps'           => array(
		'title'   => 'APS',
		'slug'    => 'aps',
		'de'      => 'Akademische Prüfstelle',
		'aliases' => 'Akademische Prüfstelle',
		'def'     => 'An office at a German embassy that checks applicants\' academic documents, in countries that have one, such as India and Vietnam.',
		'why'     => 'The German Embassy Dhaka states that it does not use an APS office.',
		'source'  => $aq_src( 'E2' ),
	),
	'term-blocked'       => array(
		'title'   => 'Blocked account',
		'slug'    => 'blocked-account',
		'de'      => 'Sperrkonto',
		'aliases' => 'blocked account,Sperrkonto',
		'def'     => 'A bank account in Germany in your name from which only a set amount can be withdrawn each month, used to prove that you can pay for your living costs.',
		'why'     => 'The required amount depends on the purpose of your stay and changes, and the Federal Foreign Office recommends no provider.',
		'source'  => $aq_src( 'E5' ),
	),
	'term-csp'           => array(
		'title'   => 'Consular Services Portal',
		'slug'    => 'consular-services-portal',
		'de'      => '',
		'aliases' => 'CSP',
		'def'     => 'The online portal where new student visa applicants at the German Embassy Dhaka register.',
		'why'     => 'Only applicants with a valid admission letter can register in it, and you register yourself.',
		'source'  => $aq_src( 'E1' ),
	),
	'term-vfs'           => array(
		'title'   => 'VFS',
		'slug'    => 'vfs',
		'de'      => '',
		'aliases' => '',
		'def'     => 'The service provider where master\'s applicants in Dhaka hand in their documents and give biometrics, after the embassy emails them a booking link.',
		'why'     => 'VFS is not part of the decision; the embassy\'s staff decide every application.',
		'source'  => $aq_src( 'E2' ),
	),
	'term-qualified'     => array(
		'title'   => 'Qualified student',
		'slug'    => 'qualified-student',
		'de'      => '',
		'aliases' => 'qualified students',
		'def'     => 'The embassy\'s term for applicants who may be prioritised, such as certain PhD applicants, holders of scholarships funded by the German government or EU funds, and graduates of German schools.',
		'why'     => 'Eligibility is checked through the questionnaire in the Consular Services Portal, and a tuition waiver alone does not count.',
		'source'  => $aq_src( 'E2' ),
	),
	'term-sponsorship'   => array(
		'title'   => 'Sponsorship declaration',
		'slug'    => 'sponsorship-declaration',
		'de'      => 'Verpflichtungserklärung',
		'aliases' => 'Verpflichtungserklärung',
		'def'     => 'A formal sponsorship letter from a sponsor living in Germany, accepted by the embassy as proof that your living costs are covered.',
		'why'     => 'It is one of the three kinds of financial proof the German Embassy Dhaka lists, next to a scholarship and a blocked account.',
		'source'  => $aq_src( 'E1' ),
	),
	'term-admission'     => array(
		'title'   => 'Admission letter',
		'slug'    => 'admission-letter',
		'de'      => 'Zulassung',
		'aliases' => 'Zulassung',
		'def'     => 'The university\'s written confirmation that you are admitted to a programme.',
		'why'     => 'At the German Embassy Dhaka you need a valid admission letter before you can register for a student visa.',
		'source'  => $aq_src( 'E1' ),
	),
	'term-semester'      => array(
		'title'   => 'Semester contribution',
		'slug'    => 'semester-contribution',
		'de'      => '',
		'aliases' => 'semester contribution',
		'def'     => 'A fee every student pays each semester, before enrolment, for student services, the student government and often a public transport ticket.',
		'why'     => 'It is not tuition, and the amount varies by university.',
		'source'  => $aq_src( 'D1' ),
	),
	'term-moi'           => array(
		'title'   => 'MOI',
		'slug'    => 'moi',
		'de'      => '',
		'aliases' => 'Medium of Instruction',
		'def'     => 'A Medium of Instruction letter: a statement from your university that your degree was taught in English.',
		'why'     => 'Each programme decides whether it accepts one; for the visa, the embassy lists TOEFL or IELTS for English-taught programmes unless your bachelor\'s degree is from Australia, the UK or the US.',
		'source'  => $aq_src( 'E1' ),
	),
);
