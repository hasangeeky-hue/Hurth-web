<?php
/**
 * Blog posts installed by the content installer.
 *
 * The three posts of the 2022 site, with their original publication dates.
 * The body text lives in content/posts/*.html (one source for the installer
 * and for anyone who wants to read it); the header comment of each file is
 * stripped before installing.
 *
 * Keys are stable: they are stored in the _aquariende_content_key meta and
 * must never change.
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
		'source'   => 'Post #158 "Process till getting the offer letter"',
		'file'     => 'content/posts/01-process-till-getting-the-offer-letter.html',
	),
	'post-mandatory-documents-check' => array(
		'title'    => 'Why a mandatory documents check?',
		'slug'     => 'why-mandatory-documents-check',
		'old_slug' => 'the-7-best-crms-for-consultants',
		'date'     => '2022-09-13 18:33:44',
		'category' => 'documents',
		'source'   => 'Post #159 "Why mandatory documents check?"',
		'file'     => 'content/posts/02-why-mandatory-documents-check.html',
	),
	'post-why-are-we-the-best'       => array(
		'title'    => 'Why are we the best?',
		'slug'     => 'why-are-we-the-best',
		'old_slug' => '6-ways-consultants-can-handle-capacity-issues-increased-demand',
		'date'     => '2022-09-13 18:35:59',
		'category' => 'process',
		'source'   => 'Post #160 "Why are we the best?"',
		'file'     => 'content/posts/03-why-are-we-the-best.html',
	),
);
