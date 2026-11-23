<?php
/**
 * Illustrations: one drawn picture per stage of the route and per kind of page.
 * Inline SVG in the logo's colours, so nothing is downloaded and nothing is a photo of a person.
 *
 * @package Aquariende
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * The drawings. Each is the inside of a 240 x 180 picture.
 *
 * @return array<string,string>
 */
function aquariende_art_shapes() {
	$ink = 'stroke="#071B3A" stroke-width="3" stroke-linejoin="round" stroke-linecap="round"';
	$dim = 'stroke="#B9C9E6" stroke-width="4" stroke-linecap="round"';

	return array(
		// Stage 1: a transcript under a magnifying glass.
		'eligibility'  => '<rect x="62" y="26" width="92" height="124" rx="10" fill="#fff" ' . $ink . '/>'
			. '<path d="M78 50h40M78 68h60M78 84h60M78 100h34" fill="none" ' . $dim . '/>'
			. '<path d="M78 50h22" fill="none" stroke="#0A5FD0" stroke-width="4" stroke-linecap="round"/>'
			. '<g class="art-float"><circle cx="150" cy="112" r="27" fill="#fff" stroke="#F59300" stroke-width="6"/>'
			. '<path d="m170 132 20 20" fill="none" stroke="#F59300" stroke-width="8" stroke-linecap="round"/>'
			. '<path class="art-tick" d="m138 112 9 9 16-18" fill="none" stroke="#0A5FD0" stroke-width="5" stroke-linecap="round" stroke-linejoin="round"/></g>',

		// Stage 2: an application leaving a laptop for a university.
		'applications' => '<rect x="30" y="58" width="96" height="66" rx="8" fill="#fff" ' . $ink . '/>'
			. '<path d="M20 124h116l-8 14H28z" fill="#fff" ' . $ink . '/>'
			. '<path d="M44 76h44M44 90h66M44 104h30" fill="none" ' . $dim . '/>'
			. '<path d="M130 62c18-26 44-30 62-14" fill="none" stroke="#F59300" stroke-width="3" stroke-dasharray="2 8" stroke-linecap="round"/>'
			. '<g class="art-float"><rect x="146" y="26" width="30" height="21" rx="3" fill="#F59300" ' . $ink . '/><path d="m146 28 15 11 15-11" fill="none" ' . $ink . '/></g>'
			. '<path d="M160 86 190 66l30 20z" fill="#0A5FD0" ' . $ink . '/>'
			. '<path d="M166 92v38M180 92v38M200 92v38M214 92v38" fill="none" stroke="#071B3A" stroke-width="5" stroke-linecap="round"/>'
			. '<path d="M158 136h64M162 88h56" fill="none" ' . $ink . '/>',

		// Stage 3: the admission letter coming out of its envelope.
		'offer'        => '<g class="art-float"><rect x="74" y="24" width="92" height="96" rx="8" fill="#fff" ' . $ink . '/>'
			. '<path d="M90 46h38M90 62h60M90 78h46" fill="none" ' . $dim . '/>'
			. '<path d="M90 46h20" fill="none" stroke="#0A5FD0" stroke-width="4" stroke-linecap="round"/></g>'
			. '<path d="M50 92 120 132l70-40v56a8 8 0 0 1-8 8H58a8 8 0 0 1-8-8z" fill="#fff" ' . $ink . '/>'
			. '<path d="m52 154 46-36M188 154l-46-36" fill="none" ' . $ink . '/>'
			. '<circle cx="120" cy="134" r="15" fill="#F59300" ' . $ink . '/>'
			. '<path class="art-tick" d="m113 134 5 5 9-10" fill="none" stroke="#fff" stroke-width="4" stroke-linecap="round" stroke-linejoin="round"/>',

		// Stage 4: a bank with a lock on it (the blocked account).
		'money'        => '<path d="M46 74 110 34l64 40z" fill="#0A5FD0" ' . $ink . '/>'
			. '<path d="M52 76h116v10H52zM46 136h128v12H46z" fill="#fff" ' . $ink . '/>'
			. '<path d="M66 90v42M94 90v42M126 90v42M154 90v42" fill="none" stroke="#071B3A" stroke-width="7" stroke-linecap="round"/>'
			. '<circle cx="110" cy="58" r="6" fill="#fff"/>'
			. '<g class="art-float"><path d="M168 104v-10a14 14 0 0 1 28 0v10" fill="none" stroke="#071B3A" stroke-width="5" stroke-linecap="round"/>'
			. '<rect x="156" y="104" width="52" height="42" rx="9" fill="#F59300" ' . $ink . '/>'
			. '<circle cx="182" cy="121" r="5" fill="#071B3A"/><path d="M182 124v10" stroke="#071B3A" stroke-width="4" stroke-linecap="round"/></g>',

		// Stage 5: a passport with a stamp.
		'visa'         => '<rect x="58" y="24" width="92" height="130" rx="12" fill="#0A5FD0" ' . $ink . '/>'
			. '<circle cx="104" cy="72" r="22" fill="none" stroke="#fff" stroke-width="3"/>'
			. '<path d="M82 72h44M104 50c-12 12-12 32 0 44M104 50c12 12 12 32 0 44" fill="none" stroke="#fff" stroke-width="3" stroke-linecap="round"/>'
			. '<path d="M80 116h48M88 132h32" fill="none" stroke="#fff" stroke-width="4" stroke-linecap="round"/>'
			. '<g class="art-float"><circle cx="166" cy="116" r="31" fill="#fff" stroke="#F59300" stroke-width="5"/>'
			. '<circle cx="166" cy="116" r="23" fill="none" stroke="#F59300" stroke-width="2" stroke-dasharray="3 5"/>'
			. '<path class="art-tick" d="m154 116 9 9 16-18" fill="none" stroke="#F59300" stroke-width="5" stroke-linecap="round" stroke-linejoin="round"/></g>',

		// Stage 6: landing in a German city with a suitcase.
		'arrival'      => '<path d="M34 148V96h30v52zM72 148V72h34v76zM150 148v-58h28v58zM186 148v-38h24v38z" fill="#fff" ' . $ink . '/>'
			. '<path d="M128 148V56" fill="none" stroke="#071B3A" stroke-width="5" stroke-linecap="round"/><circle cx="128" cy="62" r="11" fill="#0A5FD0" ' . $ink . '/><path d="M128 44V30" fill="none" ' . $ink . '/>'
			. '<path d="M44 108h10M44 122h10M82 86h14M82 102h14M82 118h14M158 102h12M158 118h12" fill="none" ' . $dim . '/>'
			. '<path d="M24 148h192" fill="none" ' . $ink . '/>'
			. '<path d="M30 58c26-22 58-28 92-22" fill="none" stroke="#F59300" stroke-width="3" stroke-dasharray="2 8" stroke-linecap="round"/>'
			. '<g class="art-float"><path d="m150 26 40 6-28 12-4 14-8-12-12-4z" fill="#F59300" ' . $ink . '/></g>'
			. '<rect x="190" y="124" width="26" height="30" rx="5" fill="#F59300" ' . $ink . '/><path d="M197 124v-8h12v8" fill="none" ' . $ink . '/>',

		// A WhatsApp conversation on a phone.
		'chat'         => '<rect x="78" y="18" width="84" height="146" rx="16" fill="#fff" ' . $ink . '/>'
			. '<path d="M108 30h24" fill="none" ' . $ink . '/>'
			. '<rect x="90" y="46" width="46" height="22" rx="9" fill="#EAF0F9"/>'
			. '<rect x="104" y="76" width="46" height="22" rx="9" fill="#25D366"/>'
			. '<rect x="90" y="106" width="38" height="22" rx="9" fill="#EAF0F9"/>'
			. '<g class="art-float"><path d="M168 54h40a10 10 0 0 1 10 10v22a10 10 0 0 1-10 10h-22l-14 12v-12h-4a10 10 0 0 1-10-10V64a10 10 0 0 1 10-10z" fill="#25D366" ' . $ink . '/>'
			. '<path class="art-tick" d="m176 76 7 7 12-13M190 83l12-13" fill="none" stroke="#fff" stroke-width="4" stroke-linecap="round" stroke-linejoin="round"/></g>'
			. '<path d="M22 96h30a8 8 0 0 1 8 8v16a8 8 0 0 1-8 8h-4v10l-12-10H22a8 8 0 0 1-8-8v-16a8 8 0 0 1 8-8z" fill="#fff" ' . $ink . '/>'
			. '<path d="M26 108h22M26 118h14" fill="none" ' . $dim . '/>',

		// Questions and an answer.
		'questions'    => '<path d="M36 34h74a12 12 0 0 1 12 12v38a12 12 0 0 1-12 12H74l-18 16V96H36a12 12 0 0 1-12-12V46a12 12 0 0 1 12-12z" fill="#fff" ' . $ink . '/>'
			. '<path d="M62 56c0-8 6-12 12-12s12 4 12 11c0 9-12 9-12 18" fill="none" stroke="#0A5FD0" stroke-width="6" stroke-linecap="round"/><circle cx="74" cy="84" r="3.5" fill="#0A5FD0"/>'
			. '<g class="art-float"><path d="M134 70h70a12 12 0 0 1 12 12v40a12 12 0 0 1-12 12h-20v16l-18-16h-32a12 12 0 0 1-12-12V82a12 12 0 0 1 12-12z" fill="#F59300" ' . $ink . '/>'
			. '<path class="art-tick" d="m150 102 12 12 24-26" fill="none" stroke="#fff" stroke-width="6" stroke-linecap="round" stroke-linejoin="round"/></g>',

		// The whole route: Dhaka to Germany.
		'route'        => '<path d="M52 132C70 60 140 40 190 62" fill="none" stroke="#F59300" stroke-width="4" stroke-dasharray="2 10" stroke-linecap="round"/>'
			. '<circle cx="66" cy="98" r="6" fill="#fff" ' . $ink . '/><circle cx="96" cy="72" r="6" fill="#fff" ' . $ink . '/><circle cx="132" cy="58" r="6" fill="#fff" ' . $ink . '/><circle cx="166" cy="56" r="6" fill="#fff" ' . $ink . '/>'
			. '<path d="M52 152c-14-16-20-26-20-36a20 20 0 0 1 40 0c0 10-6 20-20 36z" fill="#0A5FD0" ' . $ink . '/><circle cx="52" cy="116" r="7" fill="#fff"/>'
			. '<path d="M192 86c-14-16-20-26-20-36a20 20 0 0 1 40 0c0 10-6 20-20 36z" fill="#F59300" ' . $ink . '/><circle cx="192" cy="50" r="7" fill="#fff"/>'
			. '<g class="art-float"><path d="m104 22 38 8-28 10-5 14-7-12-12-5z" fill="#fff" ' . $ink . '/></g>'
			. '<path d="M30 164h44M170 98h44" fill="none" ' . $dim . '/>',

		// Health insurance: an insurance card under a shield.
		'health'       => '<rect x="36" y="66" width="126" height="84" rx="12" fill="#fff" ' . $ink . '/>'
			. '<path d="M52 90h44M52 108h66M52 126h38" fill="none" ' . $dim . '/>'
			. '<path d="M52 90h22" fill="none" stroke="#0A5FD0" stroke-width="4" stroke-linecap="round"/>'
			. '<g class="art-float"><path d="M164 28 204 42v36c0 26-16 44-40 54-24-10-40-28-40-54V42z" fill="#0A5FD0" ' . $ink . '/>'
			. '<path d="M164 58v40M144 78h40" fill="none" stroke="#fff" stroke-width="9" stroke-linecap="round"/></g>',

		// Travel insurance: a suitcase with a boarding pass and a small shield.
		'travel'       => '<rect x="40" y="62" width="104" height="88" rx="12" fill="#F59300" ' . $ink . '/>'
			. '<path d="M72 62V46a8 8 0 0 1 8-8h24a8 8 0 0 1 8 8v16" fill="none" ' . $ink . '/>'
			. '<path d="M64 62v88M120 62v88" fill="none" ' . $ink . '/>'
			. '<g class="art-float"><rect x="118" y="30" width="88" height="46" rx="8" fill="#fff" ' . $ink . '/>'
			. '<path d="M176 30v46" fill="none" stroke="#071B3A" stroke-width="3" stroke-dasharray="3 6"/>'
			. '<path d="m132 58 26-8-10 12-2 8-4-8z" fill="#0A5FD0"/><path d="M186 46h10M186 60h10" fill="none" ' . $dim . '/></g>'
			. '<path d="M178 96 204 106v20c0 16-10 26-26 32-16-6-26-16-26-32v-20z" fill="#0A5FD0" ' . $ink . '/>'
			. '<path class="art-tick" d="m166 126 9 9 16-18" fill="none" stroke="#fff" stroke-width="5" stroke-linecap="round" stroke-linejoin="round"/>',

		// The glossary: an open book.
		'book'         => '<path d="M120 52c-22-14-52-16-80-8v94c28-8 58-6 80 8 22-14 52-16 80-8V44c-28-8-58-6-80 8z" fill="#fff" ' . $ink . '/>'
			. '<path d="M120 52v94" fill="none" ' . $ink . '/>'
			. '<path d="M56 70c16-3 32-2 48 4M56 88c16-3 32-2 48 4M56 106c16-3 32-2 48 4M136 74c16-6 32-7 48-4M136 92c16-6 32-7 48-4" fill="none" ' . $dim . '/>'
			. '<g class="art-float"><path d="M156 30h24v44l-12-10-12 10z" fill="#F59300" ' . $ink . '/></g>',
	);
}

/**
 * One illustration as inline SVG (decorative: the text beside it says the same thing).
 *
 * @param string $name  Drawing name.
 * @param string $class Extra class.
 * @return string
 */
function aquariende_art( $name, $class = '' ) {
	$shapes = aquariende_art_shapes();
	if ( ! isset( $shapes[ $name ] ) ) {
		return '';
	}
	return '<svg class="art art-' . esc_attr( $name ) . ( $class ? ' ' . esc_attr( $class ) : '' ) . '" viewBox="0 0 240 180" width="240" height="180" aria-hidden="true" focusable="false">'
		. '<rect class="art-bg" x="6" y="10" width="228" height="160" rx="30"/>'
		. $shapes[ $name ] . '</svg>';
}

/**
 * Which drawing belongs to a service (by its position: 1 profile, 2 university, 3 visa).
 *
 * @param int $index Zero-based service index.
 * @return string
 */
function aquariende_art_for_service( $index ) {
	$map = array( 'eligibility', 'applications', 'visa' );
	return isset( $map[ $index ] ) ? $map[ $index ] : 'route';
}

/**
 * Which drawing belongs to the page being shown.
 *
 * @return string
 */
function aquariende_art_for_page() {
	if ( is_singular( 'aq_guide' ) ) {
		$stage  = aquariende_guide_stage( get_queried_object_id() );
		$shapes = aquariende_art_shapes();
		return isset( $shapes[ $stage ] ) ? $stage : 'route';
	}
	if ( is_singular( 'page' ) ) {
		$id   = get_queried_object_id();
		$slug = (string) get_post_field( 'post_name', $id );
		foreach ( aquariende_stages() as $i => $s ) {
			if ( $s['page'] === $slug ) {
				return aquariende_art_for_service( $i );
			}
		}
		$tpl = (string) get_page_template_slug( $id );
		$map = array(
			'template-how.php'      => 'chat',
			'template-contact.php'  => 'chat',
			'template-faq.php'      => 'questions',
			'template-glossary.php' => 'book',
			'template-login.php'    => '',
		);
		if ( isset( $map[ $tpl ] ) ) {
			return $map[ $tpl ];
		}
		return 'route';
	}
	if ( is_post_type_archive( 'aq_guide' ) ) {
		return 'route';
	}
	return '';
}
