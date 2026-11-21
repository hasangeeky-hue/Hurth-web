<?php
/**
 * Pages installed by the content installer (version 2).
 *
 * Keys are stable: they are stored in _aquariende_content_key and must never
 * change. The key is also the page slug. Bodies live in content/pages/*.html
 * (the four legal pages in content/legal/*.html, unchanged since version 1:
 * their rewrite waits for the owner and a lawyer).
 *
 * 'meta' holds the answer block, the WhatsApp page code and message, the
 * review date and an optional SEO title. 'sources' are keys of
 * inc/content/sources.php. Every claim traces to [OLD], [BF], [PIPE],
 * [SALES], an owner rule or an official source.
 *
 * Pages that the briefs mark "too thin to write honestly" are not here; see
 * strategy/NOT-BUILT.md.
 *
 * @package Aquariende
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$aq_reviewed = '2026-11-21';

return array(
	'home'                             => array(
		'title'    => 'Home',
		'template' => 'default',
		'order'    => 0,
		'excerpt'  => 'Study in Germany from Bangladesh, in your own name.',
		'content'  => '',
		'meta'     => array(
			'_aq_answer'     => 'Aquariende is a team of Bangladeshis who study in Germany. Online, we help students from Bangladesh check their profile, apply to German universities and prepare a complete visa file, in the student\'s own name and with every step visible to them. No one can guarantee a visa, and we never take payment on this website.',
			'_aq_wa_code'    => 'HOME',
			'_aq_wa_message' => 'Hello Aquariende! I want to study in Germany. Where do I start?',
		),
	),
	'study-in-germany-from-bangladesh' => array(
		'title'    => 'Study in Germany from Bangladesh: the steps, in order',
		'template' => 'page-pillar.php',
		'order'    => 1,
		'excerpt'  => 'The six stages of studying in Germany from Bangladesh, each with its official rules and links.',
		'file'     => 'content/pages/study-in-germany-from-bangladesh.html',
		'sources'  => array( 'E1', 'E2', 'E3', 'E4', 'E5', 'U1', 'D1', 'D2', 'D3', 'D4', 'K2', 'K3', 'X1' ),
		'meta'     => array(
			'_aq_answer'     => 'To study in Germany from Bangladesh you go through six stages: check your eligibility (degree recognition and grade), apply to programmes (sometimes through uni-assist), accept an offer, prove your finances, apply for the national student visa at the German Embassy Dhaka, and register after arrival. Each stage has official rules; this guide links them.',
			'_aq_seo_title'  => 'Study in Germany from Bangladesh: the steps',
			'_aq_wa_code'    => 'PILLAR',
			'_aq_wa_message' => 'Hello Aquariende! I read your guide on studying in Germany. Can you check my profile?',
			'_aq_reviewed'   => $aq_reviewed,
		),
	),
	'services'                         => array(
		'title'    => 'Our three services, in the order you need them',
		'template' => 'template-services.php',
		'order'    => 2,
		'excerpt'  => 'Profile check, university application and visa application: what each includes and what is never promised.',
		'file'     => 'content/pages/services.html',
		'meta'     => array(
			'_aq_answer'     => 'We offer three services, in the order students need them: a profile check (a video consultation and written assessment), university application support, and visa application support. You can take only the stage you need. Fees are explained on WhatsApp before you pay; nothing is paid on this website.',
			'_aq_seo_title'  => 'Services: profile check, applications, visa',
			'_aq_wa_code'    => 'SERVICES',
			'_aq_wa_message' => 'Hello Aquariende! Which of your services do I need?',
		),
	),
	'profile-check'                    => array(
		'title'    => 'Profile check: a written answer on your chances before you spend on applications',
		'parent'   => 'services',
		'template' => 'template-service.php',
		'order'    => 3,
		'excerpt'  => 'A video consultation and a written report rating six areas of your profile.',
		'file'     => 'content/pages/profile-check.html',
		'meta'     => array(
			'_aq_answer'     => 'The profile check is the first step: you upload your documents in your portal, a consultant who studies in Germany reviews them, and in a video consultation you get an honest view of your chances. Afterwards you receive a written report rating six areas (grades, English, subject fit, study gap, finances and documents) with what to fix.',
			'_aq_seo_title'  => 'Profile check before applying to Germany',
			'_aq_wa_code'    => 'S-PROFILE',
			'_aq_wa_message' => 'Hello Aquariende! I want a profile check.',
		),
	),
	'university-application'           => array(
		'title'    => 'University application: we prepare it with you, you approve every step',
		'parent'   => 'services',
		'template' => 'template-service.php',
		'order'    => 4,
		'excerpt'  => 'Shortlist, CV, motivation letters, uni-assist and VPD: prepared with you, submitted after your approval.',
		'file'     => 'content/pages/university-application.html',
		'sources'  => array( 'U1' ),
		'meta'     => array(
			'_aq_answer'     => 'In the university application service we build your shortlist with you, create your uni-assist and university accounts in your own email, start your VPD early, write your CV and motivation letters from your own story, and submit each application only after you approve it. You follow every step in your portal.',
			'_aq_seo_title'  => 'University application support for Germany',
			'_aq_wa_code'    => 'S-APPLY',
			'_aq_wa_message' => 'Hello Aquariende! I want help with my university applications.',
		),
	),
	'visa-application'                 => array(
		'title'    => 'Visa application: a complete file against the embassy checklist',
		'parent'   => 'services',
		'template' => 'template-service.php',
		'order'    => 5,
		'excerpt'  => 'Your national student visa file, prepared with you against the German Embassy Dhaka\'s checklist. No one can guarantee a visa.',
		'file'     => 'content/pages/visa-application.html',
		'sources'  => array( 'E1', 'E2', 'E3', 'E4', 'E5' ),
		'meta'     => array(
			'_aq_answer'     => 'In the visa service we prepare your national student visa file with you against the German Embassy Dhaka\'s official checklist: blocked account or other financial proof, insurance, application form, letter of intent and supporting documents, plus a mock appointment. You register and attend yourself. No one, including us, can guarantee a visa.',
			'_aq_seo_title'  => 'German student visa file preparation',
			'_aq_wa_code'    => 'S-VISA',
			'_aq_wa_message' => 'Hello Aquariende! I have an admission and need help with my visa file.',
		),
	),
	'how-it-works'                     => array(
		'title'    => 'What happens after you message us',
		'template' => 'template-how.php',
		'order'    => 6,
		'excerpt'  => 'Five steps from your first WhatsApp message to your portal.',
		'file'     => 'content/pages/how-it-works.html',
		'meta'     => array(
			'_aq_answer'     => 'After you message Aquariende on WhatsApp, a team member calls you from Germany within 24 hours. You decide which service you need, and only then pay the consultation fee, which is never paid on this website. Your portal link arrives by email, and in the portal you upload your documents and book your consultation.',
			'_aq_seo_title'  => 'How it works',
			'_aq_wa_code'    => 'HOW',
			'_aq_wa_message' => 'Hello Aquariende! I read how it works. I would like to start.',
		),
	),
	'for-parents'                      => array(
		'title'    => 'For parents: what your child\'s study plan involves',
		'template' => 'template-parents.php',
		'order'    => 7,
		'excerpt'  => 'The stages, the costs and what no one can promise, in plain words for families.',
		'file'     => 'content/pages/for-parents.html',
		'sources'  => array( 'E1', 'E3', 'D1', 'D2' ),
		'meta'     => array(
			'_aq_answer'     => 'If your child wants to study in Germany, the plan has clear official stages: checking eligibility, applying to universities, proving finances and applying for a student visa at the German Embassy Dhaka. No one, including any consultant, can guarantee a visa, and no fee to us is ever paid on a website.',
			'_aq_seo_title'  => 'For parents',
			'_aq_wa_code'    => 'PARENTS',
			'_aq_wa_message' => 'Hello Aquariende! I am a parent and have questions about my child\'s plan to study in Germany.',
		),
	),
	'glossary'                         => array(
		'title'    => 'Glossary: German study and visa terms',
		'template' => 'template-glossary.php',
		'order'    => 8,
		'excerpt'  => 'VPD, uni-assist, anabin, blocked account, CSP and other terms, each with an official link.',
		'content'  => '',
		'meta'     => array(
			'_aq_answer'     => 'This glossary explains the German study and visa terms that students from Bangladesh meet on the way, such as VPD, uni-assist, anabin, blocked account and the Consular Services Portal. Each term has a one-sentence definition, a sentence on why it matters for your application, and a link to the official source.',
			'_aq_wa_code'    => 'GLOSSARY',
			'_aq_wa_message' => 'Hello Aquariende! I have a question about a term in your glossary.',
			'_aq_reviewed'   => $aq_reviewed,
		),
	),
	'faq'                              => array(
		'title'    => 'Questions students and parents ask us',
		'template' => 'template-faq.php',
		'order'    => 9,
		'excerpt'  => 'Answers about Aquariende, the profile check, applications, the visa and the portal.',
		'content'  => '',
		'meta'     => array(
			'_aq_answer'     => 'These are the questions students and parents ask us about Aquariende, the profile check, university applications, the German student visa and the student portal. Each answer is short and, where a rule comes from an official body such as the German Embassy Dhaka or uni-assist, it links to that source.',
			'_aq_seo_title'  => 'FAQ',
			'_aq_wa_code'    => 'FAQ',
			'_aq_wa_message' => 'Hello Aquariende! My question is not in your FAQ.',
		),
	),
	'about'                            => array(
		'title'    => 'About Aquariende',
		'template' => 'template-about.php',
		'order'    => 10,
		'old_slug' => 'about-us',
		'excerpt'  => 'A team of Bangladeshis who study in Germany, helping students online since 2020.',
		'file'     => 'content/pages/about.html',
		'meta'     => array(
			'_aq_answer'     => 'Aquariende is a team of Bangladeshis who study in Germany. We help students from Bangladesh prepare their German university applications and visa file, online, in the student\'s own name, with every step visible to them. No one can guarantee a visa.',
			'_aq_seo_title'  => 'About Aquariende',
			'_aq_wa_code'    => 'ABOUT',
			'_aq_wa_message' => 'Hello Aquariende! I read about you and want to know more.',
		),
	),
	'contact'                          => array(
		'title'    => 'Talk to us on WhatsApp',
		'template' => 'template-contact.php',
		'order'    => 11,
		'excerpt'  => 'WhatsApp +49 1575 0129620 or info@aquariende.com. No forms, no payment on this website.',
		'content'  => '',
		'meta'     => array(
			'_aq_answer'     => 'Talk to Aquariende on WhatsApp at +49 1575 0129620, a German number, in English or Bengali, or write to info@aquariende.com. A team member calls you from Germany within 24 hours. There is no form on this site, and we never ask for payment here: fees are explained on WhatsApp first.',
			'_aq_seo_title'  => 'Contact',
			'_aq_wa_code'    => 'CONTACT',
			'_aq_wa_message' => 'Hello Aquariende! I want to study in Germany and would like to talk.',
		),
	),
	'student-login'                    => array(
		'title'    => 'Student login',
		'template' => 'template-login.php',
		'order'    => 12,
		'excerpt'  => 'The student portal link, and how to recognise a real one.',
		'file'     => 'content/pages/student-login.html',
		'sources'  => array( 'E3' ),
		'meta'     => array(
			'_aq_answer'     => 'The Aquariende student portal is where clients upload documents, book consultations and follow their file. You receive your personal portal link by email after you pay the consultation fee; nothing is paid on this website. If you are not sure a login link is really from us, ask us on WhatsApp before you use it.',
			'_aq_wa_code'    => 'LOGIN',
			'_aq_wa_message' => 'Hello Aquariende! I have a question about my portal login.',
		),
	),
	'blog'                             => array(
		'title'    => 'Updates',
		'template' => 'default',
		'order'    => 13,
		'excerpt'  => 'Dated notes on what changed in the rules, each with its official source.',
		'content'  => '',
		'meta'     => array(
			'_aq_wa_code' => 'BLOG',
		),
	),
	'privacy-policy'                   => array(
		'title'    => 'Privacy Policy',
		'template' => 'default',
		'order'    => 10,
		'excerpt'  => '',
		'file'     => 'content/legal/privacy-policy.html',
	),
	'terms-and-condition'              => array(
		'title'    => 'Terms and Condition',
		'template' => 'default',
		'order'    => 11,
		'excerpt'  => '',
		'file'     => 'content/legal/terms-and-condition.html',
	),
	'refund_returns'                   => array(
		'title'    => 'Refund and Returns',
		'template' => 'default',
		'order'    => 12,
		'excerpt'  => '',
		'file'     => 'content/legal/refund_returns.html',
	),
	'cancellation'                     => array(
		'title'    => 'Cancellation',
		'template' => 'default',
		'order'    => 13,
		'excerpt'  => '',
		'file'     => 'content/legal/cancellation.html',
	),
);
