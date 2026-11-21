<?php
/**
 * Business facts and the structured data the templates share.
 *
 * Every fact here traces to the 2022-23 site [OLD], BRAND-FACTS.md [BF], the
 * owner's process documents [PIPE], the sales-call notes [SALES] or the owner's
 * own rules. No prices, no success rates, no testimonials, no Bangladeshi
 * phone number.
 *
 * @package Aquariende
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * All business details, filterable through 'aquariende_info'.
 *
 * @return array<string,mixed>
 */
function aquariende_info_all() {
	static $info = null;
	if ( null !== $info ) {
		return $info;
	}
	$sameas = array_values( array_filter( array_map( 'trim', explode( ',', (string) AQUARIENDE_SAMEAS ) ) ) );
	$info   = array(
		'name'                 => 'Aquariende',
		// 03 §0: use unchanged in Organization description, llms.txt, About and the Home meta description.
		'positioning'          => 'Aquariende is a team of Bangladeshis who study in Germany. We help students from Bangladesh prepare their German university applications and visa file, online, in the student\'s own name, with every step visible to them. No one can guarantee a visa.',
		'positioning_short'    => 'Study in Germany from Bangladesh, in your own name',
		'slogan'               => 'Fetch your dream',
		'whatsapp'             => '+49 1575 0129620',
		'whatsapp_e164'        => '4915750129620',
		'whatsapp_hello'       => 'Hello Aquariende! I want to study in Germany. Where do I start?',
		// Bengali greeting, used only on Bengali pages (AQUARIENDE_ENABLE_BN). Owner to confirm the wording.
		'whatsapp_hello_bn'    => 'হ্যালো Aquariende! আমি জার্মানিতে পড়তে চাই। কোথা থেকে শুরু করব?',
		'email'                => 'info@aquariende.com',
		'mission_started'      => '2020',
		'portal_url'           => AQUARIENDE_PORTAL_URL,
		'facebook_group'       => AQUARIENDE_FACEBOOK_GROUP_URL,
		// Owner must supply (00 Q8). Empty values are never shown or put in schema.
		'legal_name'           => '',
		'trade_licence'        => '',
		'org_type'             => 'ProfessionalService',
		'sameas'               => $sameas,
		// 50,000 members is from 2022-23 [BF]; it is shown only with a fresh date (00 Q9).
		'community_size'       => '',
		'community_size_as_of' => '',
		'address'              => 'Chalkpoilanpur, Pabna 6600, Bangladesh',
		'locality'             => 'Pabna',
		'postal_code'          => '6600',
		'street'               => 'Chalkpoilanpur',
		'country'              => 'BD',
	);
	$info = apply_filters( 'aquariende_info', $info );
	return $info;
}

/**
 * One business detail as a string (arrays are returned as-is by aquariende_info_all()).
 *
 * @param string $key Key.
 * @return string
 */
function aquariende_info( $key ) {
	$info = aquariende_info_all();
	if ( ! isset( $info[ $key ] ) || is_array( $info[ $key ] ) ) {
		return '';
	}
	return (string) $info[ $key ];
}

/**
 * Whether the office address may be shown.
 *
 * @return bool
 */
function aquariende_show_address() {
	return (bool) AQUARIENDE_SHOW_ADDRESS;
}

/**
 * The three services, in the order students need them. Names are the old
 * site's names (owner decision pending, 00 Q5). Each line carries its source
 * in a comment.
 *
 * @return array<int,array<string,mixed>>
 */
function aquariende_stages() {
	return array(
		array(
			'key'        => 'profile',
			'page'       => 'profile-check',
			'name'       => 'Mandatory profile check',
			'short'      => 'Profile check',
			'service'    => 'Profile check before applying to German universities',
			'wa_code'    => 'S-PROFILE',
			'wa'         => 'Hello Aquariende! I want a profile check.',
			// [OLD Home] + [PIPE A4, A6].
			'summary'    => 'Before you spend money on applications: a consultant who studies in Germany checks your documents and tells you honestly, in a video consultation and a written report, whether you have a chance and what to fix.',
			'we_do'      => array(
				'A 30-minute video consultation', // [OLD]
				'CV checking and document evaluation', // [OLD]
				'Universities related to your previous study', // [OLD post]
				'A written report rating six areas, with what to fix', // [PIPE A4, A6]
			),
			'you_do'     => array(
				'Upload your documents in your portal before booking', // [PIPE A1-A2]
				'Decide afterwards whether to continue',
			),
			'never'      => array(
				'A chance or a percentage of admission', // 05 brief 3a "Must not"
			),
			'journey'    => array( 'eligibility' ),
			'file_note'  => 'CV checking · document evaluation',
		),
		array(
			'key'        => 'application',
			'page'       => 'university-application',
			'name'       => 'Application to the University',
			'short'      => 'University application',
			'service'    => 'University application support for Germany',
			'wa_code'    => 'S-APPLY',
			'wa'         => 'Hello Aquariende! I want help with my university applications.',
			'summary'    => 'We build your shortlist with you, prepare your CV, motivation letters and uni-assist/VPD applications, and submit each application only after you approve it. You follow every step in your portal.',
			'we_do'      => array(
				'A subject and university shortlist that you approve', // [OLD] + [PIPE B4]
				'A standard CV and motivation letters, written from your own story', // [OLD] + [PIPE B8-B9]
				'VPD and uni-assist application support', // [OLD]
				'Notary and DHL support where a programme needs paper', // [OLD] + [PIPE B7]
				'Four video consultations', // [OLD]
			),
			'you_do'     => array(
				'Approve the shortlist, your letters and each application', // [PIPE B4, B8-B10]
				'Pay uni-assist and university fees directly to them', // [PIPE B6], [U1]
			),
			'never'      => array(
				'An offer from a university', // 05 brief 3b
			),
			'journey'    => array( 'applications', 'offer' ),
			'file_note'  => 'CV · motivation letter · VPD',
		),
		array(
			'key'        => 'visa',
			'page'       => 'visa-application',
			'name'       => 'Visa application process',
			'short'      => 'Visa application',
			'service'    => 'German national student visa file preparation',
			'wa_code'    => 'S-VISA',
			'wa'         => 'Hello Aquariende! I have an admission and need help with my visa file.',
			'summary'    => 'We prepare your national student visa file with you against the German Embassy Dhaka\'s official checklist. You register and attend the appointment yourself. No one can guarantee a visa.',
			'we_do'      => array(
				'Preparing your file against the embassy\'s checklist', // 05 brief 3c, [PIPE C6]
				'Opening the blocked account, visa form and letter of intent', // [OLD]
				'Semester deferral planned according to the visa timeline', // [OLD]
				'Travel insurance, air ticket and local transport in Germany', // [OLD]
				'Three video consultations and a mock appointment', // [OLD] + [PIPE C7]
			),
			'you_do'     => array(
				'Register yourself in the Consular Services Portal', // [E1], [E3]
				'Attend the appointment and pay the visa fee in cash there', // [E3]
			),
			'never'      => array(
				'A visa: no one can guarantee a visa', // [OLD Home], [E3]
			),
			'journey'    => array( 'money', 'visa' ),
			'file_note'  => 'Visa form · blocked account',
		),
	);
}

/**
 * One service by key.
 *
 * @param string $key profile|application|visa.
 * @return array|null
 */
function aquariende_stage( $key ) {
	foreach ( aquariende_stages() as $stage ) {
		if ( $stage['key'] === $key ) {
			return $stage;
		}
	}
	return null;
}

/**
 * The six stages of the student's journey (the JourneyMap). Each stage links
 * to the pillar section and lists its guides (aq_stage taxonomy).
 *
 * @return array<string,array<string,string>>
 */
function aquariende_journey() {
	return array(
		'eligibility'  => array(
			'label'   => 'Eligibility',
			'summary' => 'Check your degree and how your grade converts.',
		),
		'applications' => array(
			'label'   => 'Applications',
			'summary' => 'Apply to programmes, sometimes through uni-assist.',
		),
		'offer'        => array(
			'label'   => 'Offer',
			'summary' => 'Accept an admission: you need it for the visa.',
		),
		'money'        => array(
			'label'   => 'Money',
			'summary' => 'Prove your finances, usually with a blocked account.',
		),
		'visa'         => array(
			'label'   => 'Visa',
			'summary' => 'Apply for the national student visa in Dhaka.',
		),
		'arrival'      => array(
			'label'   => 'Arrival',
			'summary' => 'Enrol and register after you arrive.',
		),
	);
}

/**
 * What happens after a student messages us (04 #4, 05 brief 4).
 *
 * @return array<int,array<string,string>>
 */
function aquariende_flow() {
	return array(
		array(
			'title' => 'Message us on WhatsApp',
			'text'  => 'Tell us what you want to study and where you are in the process.',
		),
		array(
			'title' => 'We call you from Germany within 24 hours',
			'text'  => 'A team member answers your questions and explains which service fits your stage.', // [BF Click to Chat]
		),
		array(
			'title' => 'You decide, then pay the consultation fee',
			'text'  => 'The fee is told to you on WhatsApp before you pay. Nothing is paid on this website.', // owner rule
		),
		array(
			'title' => 'Your portal link arrives by email',
			'text'  => 'After payment you get a link to your own file in the Aquariende portal.', // owner rule, [PIPE S8]
		),
		array(
			'title' => 'Upload your documents and book',
			'text'  => 'Inside the portal you upload your documents and book your video consultation.', // owner rule, [PIPE A1-A2]
		),
	);
}

/**
 * The "we never" list (stamp component). Text, never colour only.
 *
 * @return string[]
 */
function aquariende_never() {
	return array(
		'Promise a visa or an admission. No one can guarantee a visa.', // [OLD Home], [E3]
		'Take payment on this website.', // owner rule
		'Claim to represent a university, DAAD, uni-assist or the embassy.', // [BF "Not in the content"]
		'Accept forged or altered documents.', // [OLD Terms]
	);
}

/**
 * "Have these ready": the five things we ask on the first call [SALES; PIPE S5]
 * and the documents uploaded before the profile check [PIPE A1].
 *
 * @return array{questions:string[],documents:string[]}
 */
function aquariende_have_ready() {
	return array(
		'questions' => array(
			'The degree you want: bachelor\'s or master\'s',
			'Your last degree and your CGPA',
			'Your English test result, if you have one',
			'Your budget, including the financial proof for the visa',
			'The intake you are aiming for',
		),
		'documents' => array(
			'Your CV',
			'SSC and HSC certificates',
			'Bachelor\'s certificate and transcript (if you have them)',
			'English test result (if you have one)',
			'Passport',
		),
	);
}

/**
 * How we work: five principles from the process documents [PIPE].
 *
 * @return array<int,array<string,string>>
 */
function aquariende_principles() {
	return array(
		array(
			'title' => 'One consultant owns your case',
			'text'  => 'One person is responsible for your file from start to finish.', // [PIPE rules: one owner per case]
		),
		array(
			'title' => 'Your accounts, in your own email',
			'text'  => 'uni-assist and university accounts are created with your email address, and you can see the login details.', // [PIPE B5, #7]
		),
		array(
			'title' => 'Nothing is submitted without you',
			'text'  => 'You approve the shortlist, your letters and every application before it is sent.', // [PIPE B4, B8-B10]
		),
		array(
			'title' => 'Every step is visible',
			'text'  => 'Your tracker in the portal shows each step of your application as it changes.', // [OLD About], [PIPE B12]
		),
		array(
			'title' => 'No guarantee, said out loud',
			'text'  => 'The visa decision belongs to the embassy. We say so before you pay, not after.', // [OLD], [SALES]
		),
	);
}

/**
 * Team background as published on the 2022-23 site [BF]. Shown only through
 * aq_person posts with consent; this list feeds the installer.
 *
 * @return array<int,array<string,string>>
 */
function aquariende_team_seed() {
	return array(
		array(
			'key'        => 'person-murtuja-hasan',
			'slug'       => 'murtuja-hasan',
			'name'       => 'Md Murtuja Hasan',
			'role'       => 'Team lead',
			'university' => 'Technical University of Munich (TUM)',
			'programme'  => 'Master\'s, Science and Technology Studies',
			'photo'      => 'team-murtuja-hasan',
		),
		array(
			'key'        => 'person-munna-khan',
			'slug'       => 'munna-khan',
			'name'       => 'Munna Khan',
			'role'       => 'Head of communication',
			'university' => 'Schmalkalden University of Applied Sciences',
			'programme'  => 'Master\'s, Applied Computer Science',
			'photo'      => 'team-munna-khan',
		),
		array(
			'key'        => 'person-imon-hosen',
			'slug'       => 'imon-hosen',
			'name'       => 'Imon Hosen',
			'role'       => 'Head of technical department',
			'university' => 'HAW Hamburg (Hamburg University of Applied Sciences)',
			'programme'  => 'Bachelor\'s, Information Engineering',
			'photo'      => 'team-imon-hosen',
		),
		array(
			'key'        => 'person-khaled-bin-musa',
			'slug'       => 'khaled-bin-musa',
			'name'       => 'Khaled Bin Musa',
			'role'       => 'Senior consultant',
			'university' => 'University of Bremen',
			'programme'  => 'Master\'s, English-Speaking Cultures: Language, Text, Media',
			'photo'      => 'team-khaled-bin-musa',
		),
		array(
			'key'        => 'person-al-mahmud',
			'slug'       => 'al-mahmud',
			'name'       => 'Al Mahmud',
			'role'       => 'Senior consultant',
			'university' => 'University of Potsdam',
			'programme'  => 'Master\'s, National and International Administration and Policy',
			'photo'      => 'team-al-mahmud',
		),
	);
}

/**
 * Official sources, by key (05 "Source keys"). Each was opened on the date in
 * 'accessed' and its wording checked against the guides.
 *
 * @return array<string,array<string,string>>
 */
function aquariende_sources() {
	static $sources = null;
	if ( null === $sources ) {
		$file    = AQUARIENDE_DIR . '/inc/content/sources.php';
		$sources = is_readable( $file ) ? include $file : array();
	}
	return $sources;
}

/**
 * Volatile facts (amounts, fees, waiting and processing times) and timing rules.
 *
 * @return array<string,array<string,string>>
 */
function aquariende_volatile() {
	static $data = null;
	if ( null === $data ) {
		$file = AQUARIENDE_DIR . '/inc/content/volatile.php';
		$data = is_readable( $file ) ? include $file : array();
	}
	return $data;
}
