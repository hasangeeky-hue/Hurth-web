<?php
/**
 * Guides (aq_guide) installed by the content installer: the Priority 1 set of
 * 03 §1 that the briefs mark as writable. Bodies: content/guides/<slug>.html.
 *
 * Every guide carries: an answer block (40–60 words), its primary question,
 * a stage, a service, a WhatsApp page code, its official sources, the review
 * date (21 November 2026, the day every source was re-opened) and the next
 * review date (visa and money guides: 3 months; others: 6 months).
 *
 * Author and reviewer stay empty until the owner names a team member who has
 * given consent (see strategy/NOT-BUILT.md).
 *
 * @package Aquariende
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$aq_reviewed = '2026-11-21';
$aq_3m       = '2027-02-21';
$aq_6m       = '2027-05-21';

return array(
	'guide-masters' => array(
		'title'    => 'Master\'s in Germany with a Bangladeshi bachelor\'s: what you need',
		'slug'     => 'masters-in-germany-requirements',
		'stage'    => 'eligibility',
		'priority' => 1,
		'service'  => 'profile',
		'excerpt'  => 'Recognition, grade, language proof and the VPD: the checklist for a master\'s in Germany.',
		'sources'  => array( 'K2', 'K3', 'T1', 'E1', 'E2', 'U1', 'D4' ),
		'related'  => 'guide-grade,guide-vpd,guide-intakes',
		'meta'     => array(
			'_aq_question'    => 'What do I need to apply for a master\'s in Germany with a Bangladeshi bachelor\'s?',
			'_aq_answer'      => 'For a master\'s in Germany with a Bangladeshi bachelor\'s you usually need: a degree from a recognised institution (check anabin), a converted grade that meets the programme\'s minimum, proof of English or German as the programme requires, and sometimes a uni-assist VPD. Each programme sets its own rules, so check its page first.',
			'_aq_seo_title'   => 'Master\'s in Germany from Bangladesh: requirements',
			'_aq_wa_code'     => 'G-MASTERS',
			'_aq_wa_message'  => 'Hello Aquariende! I read your guide on master\'s requirements. Can you check my profile?',
			'_aq_reviewed'    => $aq_reviewed,
			'_aq_next_review' => $aq_6m,
		),
	),
	'guide-grade'   => array(
		'title'    => 'How your CGPA becomes a German grade, and how to check anabin',
		'slug'     => 'cgpa-to-german-grade-and-anabin',
		'stage'    => 'eligibility',
		'priority' => 2,
		'service'  => 'profile',
		'excerpt'  => 'The modified Bavarian formula, a worked example and the anabin status of your university.',
		'sources'  => array( 'K3', 'T1', 'K2', 'U1' ),
		'related'  => 'guide-masters,guide-vpd',
		'meta'     => array(
			'_aq_question'    => 'How is my CGPA converted to the German grade, and how do I check my university on anabin?',
			'_aq_answer'      => 'German universities usually convert foreign grades with the modified Bavarian formula: German grade = 1 + 3 × (best possible grade − your grade) ÷ (best possible grade − lowest passing grade). The reference grades come from the ZAB\'s evaluations in anabin. The conversion made by your university, or by uni-assist in a VPD, is the one that counts.',
			'_aq_seo_title'   => 'CGPA to German grade, and anabin',
			'_aq_wa_code'     => 'G-GRADE',
			'_aq_wa_message'  => 'Hello Aquariende! I read your guide on grade conversion. Can you check my profile?',
			'_aq_reviewed'    => $aq_reviewed,
			'_aq_next_review' => $aq_6m,
		),
	),
	'guide-vpd'     => array(
		'title'    => 'uni-assist and the VPD, explained',
		'slug'     => 'uni-assist-and-vpd',
		'stage'    => 'applications',
		'priority' => 1,
		'service'  => 'application',
		'excerpt'  => 'What a VPD is, when you need one, the steps and the timing rule.',
		'sources'  => array( 'U1', 'D2' ),
		'related'  => 'guide-intakes,guide-grade',
		'meta'     => array(
			'_aq_question'    => 'What is a VPD and when should I request it?',
			'_aq_answer'      => 'A VPD (Vorprüfungsdokumentation) is an evaluation of your certificates that uni-assist issues for one specific university. It shows your qualifications and your grade on the German scale; you then upload it to that university\'s own application. Only some universities require it. It is valid for one year.',
			'_aq_seo_title'   => 'uni-assist VPD explained',
			'_aq_wa_code'     => 'G-VPD',
			'_aq_wa_message'  => 'Hello Aquariende! I read your guide on uni-assist and the VPD. Can you help with my applications?',
			'_aq_reviewed'    => $aq_reviewed,
			'_aq_next_review' => $aq_6m,
		),
	),
	'guide-intakes' => array(
		'title'    => 'German intakes and deadlines: how to plan your year',
		'slug'     => 'intakes-and-deadlines',
		'stage'    => 'applications',
		'priority' => 2,
		'service'  => 'application',
		'excerpt'  => 'Winter and summer semester, deadlines per programme, and the timing rules that cost semesters.',
		'sources'  => array( 'X1', 'U1', 'E1', 'E2' ),
		'related'  => 'guide-vpd,guide-visa',
		'meta'     => array(
			'_aq_question'    => 'When are the German intakes and application deadlines?',
			'_aq_answer'      => 'German universities run a winter and a summer semester, but there is no national application deadline: each university sets deadlines per programme, sometimes different for international applicants. Plan backwards from your programme\'s own deadline, request a uni-assist VPD at least eight weeks before it, and remember that the visa is issued only within three months of arrival.',
			'_aq_seo_title'   => 'German intakes and deadlines',
			'_aq_wa_code'     => 'G-DEADLINES',
			'_aq_wa_message'  => 'Hello Aquariende! I read your guide on intakes and deadlines. Can you help me plan?',
			'_aq_reviewed'    => $aq_reviewed,
			'_aq_next_review' => $aq_6m,
		),
	),
	'guide-cost'    => array(
		'title'    => 'What studying in Germany costs: every category, with official links',
		'slug'     => 'cost-of-studying-in-germany',
		'stage'    => 'money',
		'priority' => 1,
		'service'  => 'visa',
		'excerpt'  => 'Every cost from application to arrival, who sets it, and the official page with the current figure.',
		'sources'  => array( 'D1', 'E1', 'E3', 'E5', 'UA' ),
		'related'  => 'guide-blocked,guide-visa',
		'meta'     => array(
			'_aq_question'    => 'What will I have to pay for, from application to arrival?',
			'_aq_answer'      => 'Studying in Germany involves several separate costs, each set by a different body: application fees (uni-assist or the university), a semester contribution, tuition only in some states or private universities, financial proof for the visa (usually a blocked account), the visa fee, insurance, translations and travel. Amounts change; each row links to the official current figure.',
			'_aq_seo_title'   => 'Cost of studying in Germany from Bangladesh',
			'_aq_wa_code'     => 'G-COST',
			'_aq_wa_message'  => 'Hello Aquariende! I read your guide on costs. Can you help me plan?',
			'_aq_reviewed'    => $aq_reviewed,
			'_aq_next_review' => $aq_3m,
		),
	),
	'guide-blocked' => array(
		'title'    => 'The blocked account (Sperrkonto) for your German student visa',
		'slug'     => 'blocked-account',
		'stage'    => 'money',
		'priority' => 2,
		'service'  => 'visa',
		'excerpt'  => 'How a blocked account works, where to check the current amount, and the other kinds of financial proof.',
		'sources'  => array( 'E5', 'E1', 'E2' ),
		'related'  => 'guide-cost,guide-visa',
		'meta'     => array(
			'_aq_question'    => 'What is a blocked account and how much must be in it?',
			'_aq_answer'      => 'A blocked account (Sperrkonto) is a bank account in Germany in your name from which you can withdraw only a set amount each month. It is one of three kinds of financial proof the German Embassy Dhaka accepts for a student visa. The required amount changes, so check the current figure on the embassy\'s page.',
			'_aq_seo_title'   => 'Blocked account for the German student visa',
			'_aq_wa_code'     => 'G-BLOCKED',
			'_aq_wa_message'  => 'Hello Aquariende! I read your guide on the blocked account. Can you help with my visa file?',
			'_aq_reviewed'    => $aq_reviewed,
			'_aq_next_review' => $aq_3m,
		),
	),
	'guide-visa'    => array(
		'title'    => 'German student visa from Bangladesh: how it works now',
		'slug'     => 'german-student-visa-from-bangladesh',
		'stage'    => 'visa',
		'priority' => 1,
		'service'  => 'visa',
		'excerpt'  => 'The Consular Services Portal, VFS or the embassy, the old waiting list, the 3-month rule and the appointment day.',
		'sources'  => array( 'E1', 'E2', 'E3', 'E4' ),
		'related'  => 'guide-docs,guide-blocked,guide-aps',
		'meta'     => array(
			'_aq_question'    => 'How do I apply for a German student visa in Bangladesh?',
			'_aq_answer'      => 'To get a German student visa in Bangladesh, you first need an admission letter. You then register in the Consular Services Portal and hand in documents and biometrics: at VFS for master\'s applicants, at the German Embassy Dhaka for bachelor\'s applicants. The visa is issued only within three months of your arrival.',
			'_aq_seo_title'   => 'German student visa from Bangladesh',
			'_aq_wa_code'     => 'G-VISA',
			'_aq_wa_message'  => 'Hello Aquariende! I read your visa guide. Can you help with my visa file?',
			'_aq_reviewed'    => $aq_reviewed,
			'_aq_next_review' => $aq_3m,
		),
	),
	'guide-docs'    => array(
		'title'    => 'Student visa documents: the checklist, with the embassy\'s own list',
		'slug'     => 'student-visa-documents-checklist',
		'stage'    => 'visa',
		'priority' => 2,
		'service'  => 'visa',
		'excerpt'  => 'A summary of the German Embassy Dhaka\'s student visa checklist and the mistakes it names.',
		'sources'  => array( 'E4', 'E1', 'E2', 'E3' ),
		'related'  => 'guide-visa,guide-blocked',
		'meta'     => array(
			'_aq_question'    => 'Which documents does the German Embassy Dhaka ask for?',
			'_aq_answer'      => 'The German Embassy Dhaka\'s own checklist PDF is the complete list of documents for a student visa. In summary it asks for a valid passport, a biometric photo, the signed form, your admission, certificates, language proof, a motivation letter, a CV, travel health insurance, financial proof and the fee. Never submit documents in two languages.',
			'_aq_seo_title'   => 'German student visa documents checklist',
			'_aq_wa_code'     => 'G-DOCS',
			'_aq_wa_message'  => 'Hello Aquariende! I read your documents checklist. Can you check my visa file?',
			'_aq_reviewed'    => $aq_reviewed,
			'_aq_next_review' => $aq_3m,
		),
	),
	'guide-aps'     => array(
		'title'    => 'Do Bangladeshi students need an APS certificate?',
		'slug'     => 'aps-certificate-bangladesh',
		'stage'    => 'visa',
		'priority' => 3,
		'service'  => 'visa',
		'excerpt'  => 'What the German Embassy Dhaka says about APS, and what APS is in other countries.',
		'sources'  => array( 'E2', 'A1', 'A2', 'E3' ),
		'related'  => 'guide-visa,guide-docs',
		'meta'     => array(
			'_aq_question'    => 'Do Bangladeshi students need an APS certificate?',
			'_aq_answer'      => 'The German Embassy Dhaka states that it does not use an APS office, so applicants from Bangladesh do not go through an APS procedure for the visa. APS certificates exist for countries with an APS office, such as India and Vietnam. Check each university\'s requirements, since your documents are still verified.',
			'_aq_seo_title'   => 'APS certificate for Bangladeshi students',
			'_aq_wa_code'     => 'G-APS',
			'_aq_wa_message'  => 'Hello Aquariende! I read your guide on APS. Can you help with my visa file?',
			'_aq_reviewed'    => $aq_reviewed,
			'_aq_next_review' => $aq_3m,
		),
	),
);
