<?php
/**
 * Pages installed by the content installer.
 *
 * Every text below comes from the 2022-23 aquariende.com site
 * (aquariende-site-content.md), cleaned only as the owner allowed: spelling
 * and grammar, duplicated lines, prices, "Buy Now", Lorem Ipsum, the
 * Bangladeshi phone number and personal email addresses removed.
 *
 * Home, Services, About and Contact are rendered by their theme templates,
 * whose copy is taken from the same old pages, so their editor content stays
 * empty. Anything the owner types there later is shown below the designed
 * sections (except on Home).
 *
 * Keys are stable: they are stored in the _aquariende_content_key meta and
 * must never change. The key is also the page slug.
 *
 * @package Aquariende
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

return array(
	'home'                => array(
		'title'    => 'Home',
		'template' => 'default',
		'order'    => 0,
		'source'   => 'Page #903 "Home" (rendered by front-page.php)',
		'excerpt'  => 'Aquariende is an online based higher education consulting platform that enables you to process your university admission and visa in a manner that is simple and transparent.',
		'content'  => '',
	),
	'services'            => array(
		'title'    => 'Services',
		'template' => 'template-services.php',
		'order'    => 1,
		'source'   => 'Page #12 "Service" (rendered by template-services.php)',
		'old_slug' => 'service',
		'excerpt'  => 'We provide a variety of services for getting admission in higher education institutions in Germany, ranging from university entrance to visa processing.',
		'content'  => '',
	),
	'about'               => array(
		'title'    => 'About',
		'template' => 'template-about.php',
		'order'    => 2,
		'source'   => 'Page #10 "About Us" (rendered by template-about.php)',
		'old_slug' => 'about-us',
		'excerpt'  => 'Aquariende is an online-based higher studies consultancy firm in Bangladesh. We provide solutions for studying in German universities, including obtaining German visas.',
		'content'  => '',
	),
	'contact'             => array(
		'title'    => 'Contact',
		'template' => 'template-contact.php',
		'order'    => 3,
		'source'   => 'Page #11 "Contact" (rendered by template-contact.php)',
		'excerpt'  => "We're open for any suggestion or just to have a chat.",
		'content'  => '',
	),
	'blog'                => array(
		'title'    => 'Blog',
		'template' => 'default',
		'order'    => 4,
		'source'   => 'Posts page (page #26, slug blog, on the old site)',
		'excerpt'  => '',
		'content'  => '',
	),
	'privacy-policy'      => array(
		'title'    => 'Privacy Policy',
		'template' => 'default',
		'order'    => 10,
		'source'   => 'Page #3 "Privacy Policy" (the unedited WordPress default text) plus the required notice',
		'excerpt'  => '',
		'content'  => '
<p><strong>This page must be completed by Aquariende before launch.</strong></p>
<h2>Who we are</h2>
<p>Suggested text: Our website address is: https://aquariende.com.</p>
<h2>Comments</h2>
<p>Suggested text: When visitors leave comments on the site we collect the data shown in the comments form, and also the visitor’s IP address and browser user agent string to help spam detection.</p>
<p>An anonymized string created from your email address (also called a hash) may be provided to the Gravatar service to see if you are using it. The Gravatar service privacy policy is available here: https://automattic.com/privacy/. After approval of your comment, your profile picture is visible to the public in the context of your comment.</p>
<h2>Media</h2>
<p>Suggested text: If you upload images to the website, you should avoid uploading images with embedded location data (EXIF GPS) included. Visitors to the website can download and extract any location data from images on the website.</p>
<h2>Cookies</h2>
<p>Suggested text: If you leave a comment on our site you may opt-in to saving your name, email address and website in cookies. These are for your convenience so that you do not have to fill in your details again when you leave another comment. These cookies will last for one year.</p>
<p>If you visit our login page, we will set a temporary cookie to determine if your browser accepts cookies. This cookie contains no personal data and is discarded when you close your browser.</p>
<p>When you log in, we will also set up several cookies to save your login information and your screen display choices. Login cookies last for two days, and screen options cookies last for a year. If you select “Remember Me”, your login will persist for two weeks. If you log out of your account, the login cookies will be removed.</p>
<p>If you edit or publish an article, an additional cookie will be saved in your browser. This cookie includes no personal data and simply indicates the post ID of the article you just edited. It expires after 1 day.</p>
<h2>Embedded content from other websites</h2>
<p>Suggested text: Articles on this site may include embedded content (e.g. videos, images, articles, etc.). Embedded content from other websites behaves in the exact same way as if the visitor has visited the other website.</p>
<p>These websites may collect data about you, use cookies, embed additional third-party tracking, and monitor your interaction with that embedded content, including tracking your interaction with the embedded content if you have an account and are logged in to that website.</p>
<h2>Who we share your data with</h2>
<p>Suggested text: If you request a password reset, your IP address will be included in the reset email.</p>
<h2>How long we retain your data</h2>
<p>Suggested text: If you leave a comment, the comment and its metadata are retained indefinitely. This is so we can recognize and approve any follow-up comments automatically instead of holding them in a moderation queue.</p>
<p>For users that register on our website (if any), we also store the personal information they provide in their user profile. All users can see, edit, or delete their personal information at any time (except they cannot change their username). Website administrators can also see and edit that information.</p>
<h2>What rights you have over your data</h2>
<p>Suggested text: If you have an account on this site, or have left comments, you can request to receive an exported file of the personal data we hold about you, including any data you have provided to us. You can also request that we erase any personal data we hold about you. This does not include any data we are obliged to keep for administrative, legal, or security purposes.</p>
<h2>Where your data is sent</h2>
<p>Suggested text: Visitor comments may be checked through an automated spam detection service.</p>
',
	),
	'terms-and-condition' => array(
		'title'    => 'Terms and Condition',
		'template' => 'default',
		'order'    => 11,
		'source'   => 'Page #1484 "Terms And Condition" (duplicated lines removed)',
		'excerpt'  => '',
		'content'  => '
<h2>Our terms and conditions</h2>
<p>Our terms and conditions should be followed as we have stated them below.</p>
<ul>
<li>For processing purposes, we will keep your information for a full year. After that, it will be removed in accordance with data privacy regulations.</li>
<li>You have agreed to give your data to us.</li>
<li>You have an obligation to us to submit only authentic required documents to the institution.</li>
<li>If you submit any forged or altered papers during the application process, we will not reimburse your service charge.</li>
<li>This service requires a signed agreement for full disclosure.</li>
<li>If we make an error processing your application, we will reimburse 75% of your money (less our service fee of 25%) within 15 days.</li>
<li>That money will be refunded through the aamarPay payment gateway.</li>
<li>Before refunding money, our team will scrutinize the facts and other issues.</li>
<li>During the consultancy period, you have to give us access to your “Email, uni-assist, and university login account”.</li>
<li>During the consultancy, the customer cannot take advice from other consultants or third parties. If the customer is involved in this kind of activity, we will not reimburse the service charge.</li>
<li>This is a consultancy service. For the mandatory profile check, there is no refund policy; it is a one-time payment.</li>
<li>For the application to the university service: once you get your offer letter from the university, it is non-refundable.</li>
<li>The customer needs to sign an acknowledgment paper of receiving the services.</li>
<li>The same applies to the visa procedure services.</li>
<li>The customer cannot ask for a refund in the middle of the consultation, and cannot cancel the services in the middle of the consultation.</li>
<li>Customers can only cancel from 7 days before the service start time to 7 days after.</li>
</ul>
',
	),
	'refund_returns'      => array(
		'title'    => 'Refund and Returns',
		'template' => 'default',
		'order'    => 12,
		'source'   => 'Page #485 "Refund and Returns Policy" (duplicated lines removed)',
		'excerpt'  => '',
		'content'  => '
<h2>Overview</h2>
<p>Our refund policy follows the terms and conditions we have stated.</p>
<ul>
<li>For processing purposes, we will keep your information for a full year. After that, it will be removed in accordance with data privacy regulations.</li>
<li>You have agreed to give your data to us.</li>
<li>You have an obligation to us to submit only authentic required documents to the institution.</li>
<li>If you submit any forged or altered papers during the application process, we will not reimburse your service charge.</li>
<li>This service requires a signed agreement for full disclosure.</li>
<li>If we make an error processing your application, we will reimburse 75% of your money (less our service fee of 25%) within 15 days.</li>
<li>That money will be refunded through the aamarPay payment gateway.</li>
<li>Before refunding money, our team will scrutinize the facts and other issues.</li>
<li>During the consultancy period, you have to give us access to your “Email, uni-assist, and university login account”.</li>
<li>During the consultancy, the customer cannot take advice from other consultants or third parties. If the customer is involved in this kind of activity, we will not reimburse the service charge.</li>
<li>This is a consultancy service. For the mandatory profile check, there is no refund policy; it is a one-time payment.</li>
<li>For the application to the university service: once you get your offer letter from the university, it is non-refundable.</li>
<li>The customer needs to sign an acknowledgment paper of receiving the services.</li>
<li>The same applies to the visa procedure services.</li>
<li>The customer cannot ask for a refund in the middle of the consultation, and cannot cancel the services in the middle of the consultation.</li>
<li>Customers can only cancel from 7 days before the service start time to 7 days after.</li>
</ul>
',
	),
	'cancellation'        => array(
		'title'    => 'Cancellation',
		'template' => 'default',
		'order'    => 13,
		'source'   => 'Page #1534 "Cancellation"',
		'excerpt'  => '',
		'content'  => '
<h2>Our cancellation process</h2>
<p>This is a consultancy service, so we have conditions regarding this issue.</p>
<p>Please let us know 48 hours before if you do not want to take our service.</p>
<p>You are not allowed to cancel in the middle of the consultation.</p>
',
	),
);
