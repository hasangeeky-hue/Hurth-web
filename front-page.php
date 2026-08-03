<?php
/**
 * Front page.
 *
 * Order follows the conversion path: who/what → why trust → the page's own
 * content → services → advice → action.
 *
 * @package Hurth
 */

get_header();

$hurth_front_id = (int) get_option( 'page_on_front' );
$hurth_q        = ( 'en' === hurth_lang() ) ? '?lang=en' : '';
$hurth_tick     = '<svg viewBox="0 0 512 512" aria-hidden="true"><path d="M504 256c0 137-111 248-248 248S8 393 8 256 119 8 256 8s248 111 248 248zM227 387l184-184c6-6 6-16 0-23l-22-22c-7-7-17-7-23 0L216 308l-70-70c-6-6-16-6-23 0l-22 23c-6 6-6 16 0 22l104 104c6 6 16 6 22 0z"/></svg>';
?>

<section class="hero">
	<div class="wrap hero__inner">
		<div>
			<span class="hero__eyebrow"><?php echo esc_html( hurth_t( 'hero_eyebrow' ) ); ?></span>
			<h1><?php echo esc_html( hurth_t( 'hero_h1' ) ); ?></h1>
			<p class="lead"><?php echo esc_html( hurth_t( 'hero_lead' ) ); ?></p>

			<ul class="hero__points">
				<?php
				foreach ( array( 'point_local', 'point_since', 'point_brands', 'point_data' ) as $hurth_pt ) {
					echo '<li>' . $hurth_tick . '<span>' . esc_html( hurth_t( $hurth_pt ) ) . '</span></li>';
				}
				?>
			</ul>

			<div class="hero__actions">
				<a class="btn btn--accent" href="tel:<?php echo esc_attr( hurth_info( 'phone_href' ) ); ?>">
					<?php echo esc_html( hurth_t( 'call' ) . ' · ' . hurth_info( 'phone' ) ); ?>
				</a>
				<a class="btn btn--ghost" href="<?php echo esc_url( hurth_info( 'maps' ) ); ?>"
					target="_blank" rel="noopener">
					<?php echo esc_html( hurth_t( 'route' ) ); ?>
				</a>
			</div>
		</div>

		<div class="hero__media tilt">
			<div class="tilt__inner">
				<?php
				if ( $hurth_front_id && has_post_thumbnail( $hurth_front_id ) ) {
					echo get_the_post_thumbnail( $hurth_front_id, 'large', array( 'class' => 'tilt__lift' ) );
				} else {
					/*
					 * No featured image set, so an authored illustration stands in
					 * rather than a blank panel. A real photo of the shop should
					 * replace this — set a featured image on the front page.
					 */
					?>
					<div class="hero__figure tilt__lift">
						<svg viewBox="0 0 200 150" role="img"
							aria-label="<?php echo esc_attr( 'de' === hurth_lang() ? 'Smartphone-Reparatur Illustration' : 'Phone repair illustration' ); ?>">
							<rect x="62" y="16" width="76" height="126" rx="12"
								fill="#fff" stroke="var(--c-brand)" stroke-width="3"/>
							<rect x="70" y="28" width="60" height="94" rx="4" fill="var(--c-brand-tint)"/>
							<circle cx="100" cy="132" r="4" fill="var(--c-brand)" opacity=".45"/>
							<path d="M78 52l16 22-10 4 14 20" fill="none"
								stroke="var(--c-accent)" stroke-width="3.4" stroke-linecap="round"/>
							<path d="M140 96l16 16m0-16l-16 16" stroke="var(--c-trust)"
								stroke-width="4" stroke-linecap="round"/>
							<circle cx="46" cy="46" r="13" fill="none"
								stroke="var(--c-brand)" stroke-width="3.4"/>
							<path d="M56 56l12 12" stroke="var(--c-brand)"
								stroke-width="3.4" stroke-linecap="round"/>
						</svg>
					</div>
					<?php
				}
				?>
				<span class="tilt__glare" aria-hidden="true"></span>
			</div>
		</div>
	</div>
</section>

<div class="trustbar">
	<div class="wrap trustbar__grid">
		<?php
		$hurth_trust = array(
			array( 'point_local', hurth_info( 'street' ) . ', ' . hurth_info( 'town' ) ),
			array( 'point_since', hurth_t( 'areas' ) ),
			array( 'point_brands', 'iPhone · Samsung · Xiaomi · Google Pixel' ),
			array( 'point_data', hurth_info( 'email' ) ),
		);

		foreach ( $hurth_trust as $hurth_item ) {
			echo '<div class="trustbar__item">' . $hurth_tick
				. '<span><strong>' . esc_html( hurth_t( $hurth_item[0] ) ) . '</strong>'
				. esc_html( $hurth_item[1] ) . '</span></div>';
		}
		?>
	</div>
</div>

<?php if ( have_posts() ) : ?>
	<div class="section">
		<div class="wrap">
			<?php
			while ( have_posts() ) :
				the_post();
				?>
				<article <?php post_class( 'content-area content-area--wide' ); ?>>
					<?php the_content(); ?>
				</article>
				<?php
			endwhile;
			?>
		</div>
	</div>
<?php endif; ?>

<?php
// Service pages, in navigation order, skipping anything without real content.
$hurth_cards = array();

foreach ( hurth_nav_items() as $hurth_slug => $hurth_key ) {
	$hurth_page = get_page_by_path( $hurth_slug );

	if ( $hurth_page && 'publish' === $hurth_page->post_status
		&& (int) $hurth_page->ID !== $hurth_front_id
		&& hurth_page_has_content( $hurth_page ) ) {
		$hurth_cards[] = $hurth_page;
	}
}

if ( $hurth_cards ) :
	?>
	<section class="section section--surface">
		<div class="wrap">
			<h2 class="text-center"><?php echo esc_html( hurth_t( 'what_we_do' ) ); ?></h2>
			<div class="card-grid">
				<?php foreach ( $hurth_cards as $hurth_page ) : ?>
					<article class="card">
						<h3>
							<a class="card__link" href="<?php echo esc_url( get_permalink( $hurth_page ) . $hurth_q ); ?>">
								<?php echo esc_html( get_the_title( $hurth_page ) ); ?>
							</a>
						</h3>
						<p><?php echo esc_html( wp_trim_words( wp_strip_all_tags( $hurth_page->post_content ), 24 ) ); ?></p>
					</article>
				<?php endforeach; ?>
			</div>
		</div>
	</section>
	<?php
endif;

$hurth_posts = get_posts( array( 'numberposts' => 3 ) );

if ( $hurth_posts ) :
	?>
	<section class="section">
		<div class="wrap">
			<h2 class="text-center"><?php echo esc_html( hurth_t( 'from_blog' ) ); ?></h2>
			<div class="card-grid">
				<?php foreach ( $hurth_posts as $hurth_post ) : ?>
					<article class="card">
						<span class="card__meta"><?php echo esc_html( get_the_date( '', $hurth_post ) ); ?></span>
						<h3>
							<a class="card__link" href="<?php echo esc_url( get_permalink( $hurth_post ) . $hurth_q ); ?>">
								<?php echo esc_html( get_the_title( $hurth_post ) ); ?>
							</a>
						</h3>
						<p><?php echo esc_html( wp_trim_words( wp_strip_all_tags( $hurth_post->post_content ), 24 ) ); ?></p>
					</article>
				<?php endforeach; ?>
			</div>
		</div>
	</section>
	<?php
endif;

get_footer();
