<?php
/**
 * FAQ items (aq_faq), stored once and shown wherever 'pages' lists them
 * (content keys). Groups follow 04 #9. The "Payments and refunds" group is
 * not built: it waits for one consistent refund policy (DEC #1, W4).
 *
 * Answers: 2–4 sentences, each fact traced to its source in the comment.
 *
 * @package Aquariende
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

return array(
	'groups' => array(
		'about'        => array( 'name' => 'About us', 'order' => 1 ),
		'profile'      => array( 'name' => 'Profile check', 'order' => 2 ),
		'applications' => array( 'name' => 'Applications', 'order' => 3 ),
		'visa'         => array( 'name' => 'Visa and money', 'order' => 4 ),
		'portal'       => array( 'name' => 'Portal', 'order' => 5 ),
	),
	'items'  => array(
		// About us.
		'faq-agency'           => array(
			'group'    => 'about',
			'order'    => 1,
			'q'        => 'Is Aquariende an agency?',
			'a'        => '<p>Aquariende is a team of Bangladeshis who study in Germany. We help students online with their profile check, university applications and visa file, in the student\'s own name. We do not represent any university, DAAD, uni-assist or the German Embassy.</p>', // 03 §0, [BF]
			'pages'    => 'home,about,faq',
		),
		'faq-guarantee'        => array(
			'group'    => 'about',
			'order'    => 2,
			'q'        => 'Do you guarantee admission or a visa?',
			'a'        => '<p>No. Each university decides on its own applications, and every visa application is decided by the German Embassy\'s staff. The embassy itself states that no agency can guarantee that a visa will be approved.</p>', // [OLD], E2, E3
			'pages'    => 'home,services,visa-application,university-application,faq',
		),
		'faq-one-service'      => array(
			'group'    => 'about',
			'order'    => 3,
			'q'        => 'Can I take only one service?',
			'a'        => '<p>Yes. You can take only the stage you need: for example only the university application, or only the visa application if you already have an admission.</p>', // [OLD post]
			'pages'    => 'home,services,faq',
		),
		'faq-start'            => array(
			'group'    => 'about',
			'order'    => 4,
			'q'        => 'How do I start?',
			'a'        => '<p>Message us on WhatsApp at +49 1575 0129620. A team member calls you from Germany within 24 hours. Nothing is paid on this website: the fee is explained on WhatsApp before you decide.</p>', // [BF], owner rule
			'pages'    => 'home,contact,how-it-works,faq',
		),
		'faq-online'           => array(
			'group'    => 'about',
			'order'    => 5,
			'q'        => 'Is everything online?',
			'a'        => '<p>Yes. We work by video consultation and WhatsApp, and you follow your file in your own portal, so you can complete your admission file from anywhere.</p>', // [OLD About]
			'pages'    => 'about,how-it-works,faq',
		),
		'faq-other-help'       => array(
			'group'    => 'about',
			'order'    => 6,
			'q'        => 'What else do you help with?',
			'a'        => '<p>Besides the three services, we assist students with paying tuition fees where a university charges them, and with opening health and travel insurance.</p>', // [OLD About]
			'pages'    => 'faq',
		),
		'faq-apply-yourself'   => array(
			'group'    => 'about',
			'order'    => 7,
			'q'        => 'Can I apply on my own?',
			'a'        => '<p>Yes. The German Embassy Dhaka strongly encourages applicants to complete their visa applications on their own, and universities and uni-assist publish their rules online. Our [aq_link key="study-in-germany-from-bangladesh"]step-by-step guide[/aq_link] links every official page.</p>', // E3
			'pages'    => 'study-in-germany-from-bangladesh,for-parents,faq',
		),
		'faq-our-fee'          => array(
			'group'    => 'about',
			'order'    => 8,
			'q'        => 'How much does Aquariende charge?',
			'a'        => '<p>We tell you the fee for each service on WhatsApp before you pay anything. Nothing is paid on this website.</p>', // owner rule
			'pages'    => 'guide-cost,services,faq',
		),

		// Profile check.
		'faq-why-profile'      => array(
			'group'    => 'profile',
			'order'    => 1,
			'q'        => 'Why start with a profile check?',
			'a'        => '<p>Applications cost money and time. The profile check tells you, before you spend on them, whether you have a chance of being accepted under the requirements of the German education system, and what to fix first.</p>', // [OLD Home, post]
			'pages'    => 'profile-check,services,faq',
		),
		'faq-profile-weak'     => array(
			'group'    => 'profile',
			'order'    => 2,
			'q'        => 'What if my profile is not strong enough?',
			'a'        => '<p>You receive the written report with the reasons and what to work on. Whether you go further, and when, is your decision.</p>', // [PIPE A6]
			'pages'    => 'profile-check,faq',
		),
		'faq-profile-docs'     => array(
			'group'    => 'profile',
			'order'    => 3,
			'q'        => 'Which documents do I need for the profile check?',
			'a'        => '<p>Your CV, SSC and HSC certificates, your bachelor\'s certificate and transcript if you have them, your English test result if you have one, and your passport. You upload them in your portal before you book the consultation.</p>', // [PIPE A1-A2]
			'pages'    => 'profile-check,contact,faq',
		),
		'faq-gap'              => array(
			'group'    => 'profile',
			'order'    => 4,
			'q'        => 'Does a study gap stop me?',
			'a'        => '<p>There is no single official rule: each programme decides. Your study gap, and how you explain it, is one of the six areas we assess in the profile check.</p>', // [PIPE A4]; 05 brief 5.14
			'pages'    => 'profile-check,guide-masters,faq',
		),

		// Applications.
		'faq-apply-behalf'     => array(
			'group'    => 'applications',
			'order'    => 1,
			'q'        => 'Do you apply on my behalf?',
			'a'        => '<p>We prepare and submit the applications with you. Your accounts are created with your own email address, and each application is submitted only after you have approved it.</p>', // [PIPE B5, B10]
			'pages'    => 'university-application,faq',
		),
		'faq-uniassist-fee'    => array(
			'group'    => 'applications',
			'order'    => 2,
			'q'        => 'Who pays the uni-assist fee?',
			'a'        => '<p>You pay uni-assist directly. uni-assist states that its payment requests come only from uni-assist and are processed only through its own online portal.</p>', // U1, [PIPE B6]
			'pages'    => 'university-application,guide-vpd,guide-cost,faq',
		),
		'faq-offer-guarantee'  => array(
			'group'    => 'applications',
			'order'    => 3,
			'q'        => 'Can you guarantee an offer from a university?',
			'a'        => '<p>No. Each university decides on its own applications, and we have no partnership with any university.</p>', // 05 brief 3b
			'pages'    => 'university-application,faq',
		),
		'faq-3year'            => array(
			'group'    => 'applications',
			'order'    => 4,
			'q'        => 'Is a 3-year bachelor\'s enough for a master\'s in Germany?',
			'a'        => '<p>It depends on each programme\'s own admission rules, so there is no general answer. Check the admission requirements on the programme\'s page; the profile check looks at it for your case.</p>', // 05 brief 5.1
			'pages'    => 'guide-masters,faq',
		),
		'faq-moi'              => array(
			'group'    => 'applications',
			'order'    => 5,
			'q'        => 'Is an MOI letter accepted instead of IELTS?',
			'a'        => '<p>Universities decide this per programme. For the visa, the German Embassy Dhaka lists TOEFL or IELTS for English-taught programmes, unless your bachelor\'s degree is from Australia, the UK or the US.</p>', // E1
			'pages'    => 'guide-masters,faq',
		),
		'faq-hsc'              => array(
			'group'    => 'applications',
			'order'    => 6,
			'q'        => 'Can I start a bachelor\'s in Germany right after HSC?',
			'a'        => '<p>The rules depend on the university and on how your school certificate is rated in Germany. We have not yet published a guide on this route; ask the university\'s international office, or message us with your certificates.</p>', // 05 brief 5.2 (guide not built)
			'pages'    => 'study-in-germany-from-bangladesh,faq',
		),
		'faq-vpd-early'        => array(
			'group'    => 'applications',
			'order'    => 7,
			'q'        => 'When should I request the VPD?',
			'a'        => '<p>As early as possible. uni-assist advises requesting it at least 8 weeks before the application deadline, so that there is time to send any missing document.</p>', // U1
			'pages'    => 'guide-vpd,guide-intakes,faq',
		),
		'faq-vpd-time'         => array(
			'group'    => 'applications',
			'order'    => 8,
			'q'        => 'How long does a VPD take?',
			'a'        => '<p>uni-assist states its usual processing time on its <a href="https://uni-assist.de/en/how-to-apply/plan-your-application/vpd">VPD page</a>. Check it there, because it can change, and plan with the 8-week rule.</p>', // U1
			'pages'    => 'guide-vpd,faq',
		),
		'faq-vpd-affiliation'  => array(
			'group'    => 'applications',
			'order'    => 9,
			'q'        => 'Is any agency affiliated with uni-assist?',
			'a'        => '<p>uni-assist states that it does not work with commercial agencies and that some organisations falsely claim to be affiliated with it. Official information is only on uni-assist.de and on the universities\' own websites.</p>', // U1
			'pages'    => 'guide-vpd,faq',
		),
		'faq-intake-deadline'  => array(
			'group'    => 'applications',
			'order'    => 10,
			'q'        => 'Is there one deadline for all German universities?',
			'a'        => '<p>No. Each university sets its own deadlines, often different per programme and for applicants with foreign qualifications. Always use the deadline on your programme\'s own page.</p>', // X1
			'pages'    => 'guide-intakes,faq',
		),
		'faq-grade-binding'    => array(
			'group'    => 'applications',
			'order'    => 11,
			'q'        => 'Is the result of an online CGPA converter binding?',
			'a'        => '<p>No. Converters only estimate. TUM, for example, states that its converter result is non-binding and that each programme\'s examination regulations govern the official conversion.</p>', // T1
			'pages'    => 'guide-grade,faq',
		),
		'faq-grade-who'        => array(
			'group'    => 'applications',
			'order'    => 12,
			'q'        => 'Whose grade conversion counts?',
			'a'        => '<p>The university\'s own conversion, or uni-assist\'s rating of your grade on the German scale inside a VPD.</p>', // U1, T1
			'pages'    => 'guide-grade,faq',
		),
		'faq-anabin-missing'   => array(
			'group'    => 'applications',
			'order'    => 13,
			'q'        => 'My university is not in anabin. What now?',
			'a'        => '<p>anabin states that its database is not complete, so an institution may be missing or not yet rated. Ask the university you are applying to how it will assess your degree.</p>', // K2
			'pages'    => 'guide-grade,faq',
		),

		// Visa and money.
		'faq-book-appointment' => array(
			'group'    => 'visa',
			'order'    => 1,
			'q'        => 'Do you book my embassy appointment?',
			'a'        => '<p>No. You register yourself in the Consular Services Portal once you hold a valid admission letter, and you attend the appointment yourself. We help you prepare. The embassy encourages applicants to apply on their own and warns about agents who cancel appointments without telling their clients.</p>', // E1, E3
			'pages'    => 'visa-application,faq',
		),
		'faq-visa-wait'        => array(
			'group'    => 'visa',
			'order'    => 2,
			'q'        => 'How long is the wait for a student visa appointment?',
			'a'        => '<p>The embassy publishes the situation on its <a href="https://dhaka.diplo.de/bd-en/service/2685884-2685884">study visa page</a> and states that it cannot give an exact waiting time. We do not quote waiting times, because they change.</p>', // E1, E2
			'pages'    => 'guide-visa,visa-application,study-in-germany-from-bangladesh,faq',
		),
		'faq-online-tests'     => array(
			'group'    => 'visa',
			'order'    => 3,
			'q'        => 'Are online English tests accepted for the visa?',
			'a'        => '<p>The German Embassy Dhaka states that language certificates must be obtained in person and that online certificates are generally not accepted.</p>', // E2
			'pages'    => 'guide-visa,guide-masters,guide-docs,faq',
		),
		'faq-visa-fee-refund'  => array(
			'group'    => 'visa',
			'order'    => 4,
			'q'        => 'Is the visa fee refundable?',
			'a'        => '<p>No. The embassy states that the fee is fixed in euro, paid in taka in cash at the appointment, and not refunded if the visa application is rejected.</p>', // E1, E3
			'pages'    => 'guide-visa,guide-cost,faq',
		),
		'faq-visa-late'        => array(
			'group'    => 'visa',
			'order'    => 5,
			'q'        => 'What if my visa comes after the semester starts?',
			'a'        => '<p>The embassy states that enrolment and examination dates cannot be considered. Ask your university\'s international office early whether you can defer; in our visa service we plan the semester deferral according to the visa timeline.</p>', // E2, [OLD]
			'pages'    => 'guide-visa,guide-intakes,visa-application,faq',
		),
		'faq-agent-register'   => array(
			'group'    => 'visa',
			'order'    => 6,
			'q'        => 'Can an agent register in the Consular Services Portal for me?',
			'a'        => '<p>Register yourself. The embassy strongly encourages applicants to apply on their own, because it keeps them in control of their data, and it warns about identity theft by agents.</p>', // E3
			'pages'    => 'guide-visa,faq',
		),
		'faq-blocked-amount'   => array(
			'group'    => 'visa',
			'order'    => 7,
			'q'        => 'How much must be in the blocked account?',
			'a'        => '<p>The amount is set by the German authorities and changes, so we do not copy it here. Check the current figure on the <a href="https://dhaka.diplo.de/bd-en/service/2685884-2685884">German Embassy Dhaka\'s study visa page</a>.</p>', // E1, E5
			'pages'    => 'guide-blocked,faq',
		),
		'faq-blocked-provider' => array(
			'group'    => 'visa',
			'order'    => 8,
			'q'        => 'Which blocked account provider should I use?',
			'a'        => '<p>We recommend none. The Federal Foreign Office recommends no provider either and advises you to choose carefully after your own research.</p>', // E5
			'pages'    => 'guide-blocked,visa-application,faq',
		),
		'faq-blocked-parents'  => array(
			'group'    => 'visa',
			'order'    => 9,
			'q'        => 'Can my parents\' bank account replace the blocked account?',
			'a'        => '<p>The embassy lists three kinds of financial proof: a scholarship confirmation, a formal sponsorship declaration from a sponsor living in Germany, or a blocked account in your name. A parent\'s account in Bangladesh is not one of them.</p>', // E1
			'pages'    => 'guide-blocked,for-parents,faq',
		),
		'faq-blocked-scholar'  => array(
			'group'    => 'visa',
			'order'    => 10,
			'q'        => 'Can a scholarship reduce the blocked amount?',
			'a'        => '<p>Yes. The embassy states that a scholarship can be credited to your means of living, so the amount you deposit may be lower.</p>', // E2
			'pages'    => 'guide-blocked,faq',
		),
		'faq-aps'              => array(
			'group'    => 'visa',
			'order'    => 11,
			'q'        => 'Do Bangladeshi students need an APS certificate?',
			'a'        => '<p>The German Embassy Dhaka states that it does not use an APS office and that applicants should not apply through another country\'s APS office (checked 21 November 2026).</p>', // E2
			'pages'    => 'guide-aps,study-in-germany-from-bangladesh,faq',
		),
		'faq-aps-what'         => array(
			'group'    => 'visa',
			'order'    => 12,
			'q'        => 'What is an APS certificate?',
			'a'        => '<p>It is issued by an APS office (Akademische Prüfstelle) after it checks an applicant\'s academic documents. Such offices exist at German embassies in some countries, for example India and Vietnam.</p>', // A1, A2
			'pages'    => 'guide-aps,faq',
		),
		'faq-aps-checked'      => array(
			'group'    => 'visa',
			'order'    => 13,
			'q'        => 'Without APS, are my documents still checked?',
			'a'        => '<p>Yes. Universities and uni-assist evaluate your certificates, and the embassy states that any attempt to submit fake or falsified documents is subject to penalties.</p>', // U1, E3
			'pages'    => 'guide-aps,faq',
		),
		'faq-docs-language'    => array(
			'group'    => 'visa',
			'order'    => 14,
			'q'        => 'Should I submit my documents in English and German?',
			'a'        => '<p>No. The embassy asks you not to submit documents in both languages and prefers the German version.</p>', // E1
			'pages'    => 'guide-docs,faq',
		),
		'faq-docs-bengali'     => array(
			'group'    => 'visa',
			'order'    => 15,
			'q'        => 'Do Bengali documents need a translation?',
			'a'        => '<p>Yes. Documents in Bengali, or any language other than English or German, need an English or German translation.</p>', // E1
			'pages'    => 'guide-docs,faq',
		),
		'faq-docs-missing'     => array(
			'group'    => 'visa',
			'order'    => 16,
			'q'        => 'What if a document is missing at my appointment?',
			'a'        => '<p>The embassy explains on its appointment page how to send a missing document through its webform, and how to hand in originals later in a sealed envelope with your name, passport number and barcode number.</p>', // E3
			'pages'    => 'guide-docs,faq',
		),
		'faq-visa-when'        => array(
			'group'    => 'visa',
			'order'    => 17,
			'q'        => 'When can I apply for the visa?',
			'a'        => '<p>The embassy can issue the student visa only within 3 months of your arrival date in Germany. If your arrival is further away, it asks you not to apply yet.</p>', // E1
			'pages'    => 'guide-intakes,faq',
		),
		'faq-free'             => array(
			'group'    => 'visa',
			'order'    => 18,
			'q'        => 'Is studying in Germany free?',
			'a'        => '<p>Bachelor\'s and most master\'s programmes at state universities generally charge no tuition, with exceptions such as Baden-Württemberg for students from outside the EU. Every student pays a semester contribution, which is not tuition, and you need financial proof for the visa.</p>', // D1, E1
			'pages'    => 'study-in-germany-from-bangladesh,guide-cost,for-parents,faq',
		),

		// Portal.
		'faq-portal'           => array(
			'group'    => 'portal',
			'order'    => 1,
			'q'        => 'How do I get into the portal?',
			'a'        => '<p>Your portal account is created when you pay the consultation fee, and the link comes to you by email.</p>', // owner rule, [PIPE S8]
			'pages'    => 'student-login,how-it-works,faq',
		),
		'faq-portal-link'      => array(
			'group'    => 'portal',
			'order'    => 2,
			'q'        => 'I received a login link and I am not sure it is real. What should I do?',
			'a'        => '<p>We send the portal link only by email, after you have paid the consultation fee. If you are unsure, ask us on WhatsApp at +49 1575 0129620 before you use the link.</p>', // owner rule (03 B2)
			'pages'    => 'student-login,faq',
		),
	),
);
