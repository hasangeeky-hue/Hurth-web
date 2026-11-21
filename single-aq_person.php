<?php
/**
 * /about/team/<slug>/: a team member's page (ProfilePage). Without the
 * member's recorded consent the page does not exist (404).
 *
 * @package Aquariende
 */

if ( ! aquariende_person_ok( get_queried_object() ) ) {
	global $wp_query;
	$wp_query->set_404();
	status_header( 404 );
	nocache_headers();
	include get_query_template( '404' );
	return;
}

get_header();

$aq_id = get_queried_object_id();
$aq_m  = function ( $key ) use ( $aq_id ) {
	return (string) get_post_meta( $aq_id, $key, true );
};

$aq_written = get_posts(
	array(
		'post_type'   => 'aq_guide',
		'post_status' => 'publish',
		'numberposts' => 50,
		'meta_query'  => array( // phpcs:ignore WordPress.DB.SlowDBQuery
			'relation' => 'OR',
			array(
				'key'   => '_aq_author',
				'value' => get_post_field( 'post_name', $aq_id ),
			),
			array(
				'key'   => '_aq_reviewer',
				'value' => get_post_field( 'post_name', $aq_id ),
			),
		),
	)
);
?>
<main id="main" class="site-main">
	<?php
	get_template_part(
		'template-parts/page-head',
		null,
		array(
			'answer' => '',
			'lede'   => trim( $aq_m( '_aq_role' ) . ( $aq_m( '_aq_university' ) ? ' · ' . $aq_m( '_aq_university' ) : '' ) ),
		)
	);
	?>
	<section class="section-pad">
		<div class="wrap person-layout">
			<div class="person-photo">
				<?php
				if ( has_post_thumbnail( $aq_id ) ) {
					echo get_the_post_thumbnail( $aq_id, 'medium', array( 'alt' => '' ) );
				} elseif ( $aq_m( '_aq_photo' ) && is_readable( AQUARIENDE_DIR . '/assets/img/' . $aq_m( '_aq_photo' ) . '.webp' ) ) {
					echo aquariende_picture( $aq_m( '_aq_photo' ), '', array( 'lazy' => false ) ); // phpcs:ignore WordPress.Security.EscapeOutput -- escaped inside.
				}
				?>
			</div>
			<div>
				<dl class="facts">
					<?php if ( $aq_m( '_aq_university' ) ) : ?>
						<div><dt>University in Germany</dt><dd><?php echo esc_html( $aq_m( '_aq_university' ) ); ?></dd></div>
					<?php endif; ?>
					<?php if ( $aq_m( '_aq_programme' ) ) : ?>
						<div><dt>Programme</dt><dd><?php echo esc_html( $aq_m( '_aq_programme' ) ); ?></dd></div>
					<?php endif; ?>
					<?php if ( $aq_m( '_aq_status' ) ) : ?>
						<div><dt>Status</dt><dd><?php echo esc_html( $aq_m( '_aq_status' ) ); ?></dd></div>
					<?php endif; ?>
				</dl>
				<div class="prose"><?php echo wp_kses_post( wpautop( get_post_field( 'post_content', $aq_id ) ) ); ?></div>
				<?php if ( $aq_m( '_aq_linkedin' ) ) : ?>
					<p><a href="<?php echo esc_url( $aq_m( '_aq_linkedin' ) ); ?>" rel="noopener me" data-ext="linkedin.com">LinkedIn profile<span class="screen-reader-text"> (external site)</span></a></p>
				<?php endif; ?>
				<?php if ( $aq_written ) : ?>
					<h2 class="block-title">Guides written or reviewed</h2>
					<ul class="guide-links">
						<?php foreach ( $aq_written as $aq_g ) : ?>
							<li><a href="<?php echo esc_url( get_permalink( $aq_g ) ); ?>"><?php echo esc_html( get_the_title( $aq_g ) ); ?></a></li>
						<?php endforeach; ?>
					</ul>
				<?php endif; ?>
			</div>
		</div>
	</section>
	<?php get_template_part( 'template-parts/wa-cta', null, array( 'steps' => false ) ); ?>
</main>
<?php
get_footer();
