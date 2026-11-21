<?php
/**
 * Blog posts known to the installer.
 *
 * Version 2 retires the three 2022 posts (04 §1, 06 §1): their claims are
 * stale or superlative [BF]. 'status' => 'draft' moves each one to draft,
 * unless the owner edited it; their old addresses redirect (redirects.php).
 * Their bodies are no longer shipped: the posts are only moved to draft, never created.
 *
 * Keys are stable and must never change.
 *
 * @package Aquariende
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

return array(
	'post-process-till-offer-letter' => array(
		'title'    => 'Process till getting the offer letter',
		'slug'     => 'process-till-getting-the-offer-letter',
		'old_slug' => 'personal-branding-for-consultants-be-authentic-to-get-more-clients-2',
		'date'     => '2022-09-13 18:31:49',
		'category' => 'process',
		'status'   => 'draft',
	),
	'post-mandatory-documents-check' => array(
		'title'    => 'Why a mandatory documents check?',
		'slug'     => 'why-mandatory-documents-check',
		'old_slug' => 'the-7-best-crms-for-consultants',
		'date'     => '2022-09-13 18:33:44',
		'category' => 'documents',
		'status'   => 'draft',
	),
	'post-why-are-we-the-best'       => array(
		'title'    => 'Why are we the best?',
		'slug'     => 'why-are-we-the-best',
		'old_slug' => '6-ways-consultants-can-handle-capacity-issues-increased-demand',
		'date'     => '2022-09-13 18:35:59',
		'category' => 'process',
		'status'   => 'draft',
	),
);
