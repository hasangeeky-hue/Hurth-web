<?php
/**
 * Old URLs (2022 site and staging) and where they go now (04 §5).
 *
 * 'from' is a path without slashes at the ends; a trailing '*' matches the
 * path and everything below it. 'to' is a content key (301 to that item, only
 * once it is published) or 'gone' (410). Redirects apply only to requests
 * that would otherwise be a 404, so a page that still exists is never hidden.
 *
 * @package Aquariende
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

return array(
	// Version 1 redirects, kept.
	'home-test'                                                     => 'home',
	'service'                                                       => 'services',
	'about-us'                                                      => 'about',

	// The three 2022 posts (drafted in version 2) and their 2022 slugs.
	'why-are-we-the-best'                                           => 'about',
	'6-ways-consultants-can-handle-capacity-issues-increased-demand' => 'about',
	'why-mandatory-documents-check'                                 => 'profile-check',
	'the-7-best-crms-for-consultants'                               => 'profile-check',
	'process-till-getting-the-offer-letter'                         => 'university-application',
	'personal-branding-for-consultants-be-authentic-to-get-more-clients-2' => 'university-application',

	// Legal pages: these targets are not built yet (owner and lawyer); the
	// redirects switch on by themselves once pages with these keys exist.
	'terms-and-condition'                                           => 'terms',
	'refund_returns'                                                => 'refund-and-cancellation',
	'cancellation'                                                  => 'refund-and-cancellation',

	// Old account pages go to the anti-phishing login page.
	'my-account*'                                                   => 'student-login',
	'account'                                                       => 'student-login',
	'login'                                                         => 'student-login',
	'login-2'                                                       => 'student-login',
	'register'                                                      => 'student-login',
	'members'                                                       => 'student-login',
	'user'                                                          => 'student-login',
	'password-recovery'                                             => 'student-login',
	'password-reset'                                                => 'student-login',
	'messages'                                                      => 'student-login',
	'submissions'                                                   => 'student-login',

	// The shop and test pages are gone for good.
	'shop*'                                                         => 'gone',
	'cart'                                                          => 'gone',
	'checkout*'                                                     => 'gone',
	'product*'                                                      => 'gone',
	'product-category*'                                             => 'gone',
	'product-data-table'                                            => 'gone',
	'case-studies'                                                  => 'gone',
	'user-directory'                                                => 'gone',
	'test-code'                                                     => 'gone',
	'form-test'                                                     => 'gone',
	'logout'                                                        => 'gone',
);
