<?php
/**
 * Template Name: About
 *
 * /about/ (04 #10): positioning → facts you can verify → our story (editor
 * content) → how we work → the team (consented members only).
 * Company facts (legal name, licence) appear once the owner supplies them.
 *
 * @package Aquariende
 */

get_header();

$aq_people = aquariende_people();
$aq_legal  = aquariende_info( 'legal_name' );
$aq_lic    = aquariende_info( 'trade_licence' );
?>
<main id="main" class="site-main">
	<?php
	while ( have_posts() ) :
		the_post();
		get_template_part( 'template-parts/page-head' );
		get_template_part( 'template-parts/verify-strip' );
		?>
		<article id="post-<?php the_ID(); ?>" <?php post_class( 'section-pad' ); ?>>
			<div class="wrap rt-two rt-two--aside">
				<div class="rt-main prose"><?php the_content(); ?></div>
				<?php get_template_part( 'template-parts/aside-help', null, array( 'never' => false ) ); ?>
			</div>
		</article>

		<section class="section-pad tint" aria-labelledby="hww-title">
			<div class="wrap">
				<h2 id="hww-title" class="section-title">How we work</h2>
				<ol class="principles">
					<?php foreach ( aquariende_principles() as $aq_p ) : ?>
						<li><h3><?php echo esc_html( $aq_p['title'] ); ?></h3><p><?php echo esc_html( $aq_p['text'] ); ?></p></li>
					<?php endforeach; ?>
				</ol>
			</div>
		</section>

		<?php if ( $aq_people ) : ?>
			<section class="section-pad" id="team" aria-labelledby="team-title">
				<div class="wrap">
					<h2 id="team-title" class="section-title">The team</h2>
					<ul class="team-grid">
						<?php
						foreach ( $aq_people as $aq_person ) {
							get_template_part( 'template-parts/team-card', null, array( 'person' => $aq_person ) );
						}
						?>
					</ul>
				</div>
			</section>
		<?php endif; ?>

		<?php if ( $aq_legal || $aq_lic ) : ?>
			<section class="section-pad section-pad--top0" aria-labelledby="company-title">
				<div class="wrap narrow">
					<h2 id="company-title" class="section-title">Company facts</h2>
					<dl class="facts">
						<?php if ( $aq_legal ) : ?>
							<div><dt>Legal name</dt><dd><?php echo esc_html( $aq_legal ); ?></dd></div>
						<?php endif; ?>
						<?php if ( $aq_lic ) : ?>
							<div><dt>Trade licence</dt><dd><?php echo esc_html( $aq_lic ); ?></dd></div>
						<?php endif; ?>
					</dl>
				</div>
			</section>
		<?php endif; ?>

		<section class="section-pad section-pad--top0">
			<div class="wrap narrow">
				<?php get_template_part( 'template-parts/faq', null, array( 'key' => 'about' ) ); ?>
			</div>
		</section>
	<?php endwhile; ?>
	<?php get_template_part( 'template-parts/wa-cta' ); ?>
</main>
<?php
get_footer();
