<?php
/**
 * The help topics students ask for by name.
 *
 * Args: mode (cards|full|links), keys (string[] to limit), heading (h2|h3).
 *  - cards: four short cards that lead to the full text (home page).
 *  - full:  each topic in full: the official rule, what we do, what you do (visa service page).
 *  - links: a short "we help with this" box (guides).
 *
 * @package Aquariende
 */

$aq_args   = isset( $args ) && is_array( $args ) ? $args : array();
$aq_mode   = isset( $aq_args['mode'] ) ? $aq_args['mode'] : 'cards';
$aq_topics = aquariende_help_topics();
if ( ! empty( $aq_args['keys'] ) ) {
	$aq_topics = array_intersect_key( $aq_topics, array_flip( (array) $aq_args['keys'] ) );
}
if ( ! $aq_topics ) {
	return;
}

if ( 'links' === $aq_mode ) :
	?>
	<aside class="help-links" aria-labelledby="help-links-title">
		<h2 id="help-links-title" class="help-links-title">We help with this</h2>
		<ul>
			<?php foreach ( $aq_topics as $aq_key => $aq_t ) : ?>
				<li><a href="<?php echo esc_url( aquariende_help_url( $aq_key ) ); ?>"><span><strong><?php echo esc_html( $aq_t['title'] ); ?>.</strong> <?php echo esc_html( $aq_t['short'] ); ?></span><?php echo aquariende_icon( 'arrow' ); // phpcs:ignore WordPress.Security.EscapeOutput ?></a></li>
			<?php endforeach; ?>
		</ul>
	</aside>
	<?php
	return;
endif;

if ( 'cards' === $aq_mode ) :
	?>
	<div class="help-cards" data-stagger>
		<?php foreach ( $aq_topics as $aq_key => $aq_t ) : ?>
			<article class="help-card" data-tilt>
				<?php echo aquariende_art( $aq_t['art'], 'help-card-art' ); // phpcs:ignore WordPress.Security.EscapeOutput -- fixed SVG. ?>
				<h3 class="help-card-title"><a href="<?php echo esc_url( aquariende_help_url( $aq_key ) ); ?>"><?php echo esc_html( $aq_t['title'] ); ?></a></h3>
				<p><?php echo esc_html( $aq_t['short'] ); ?></p>
				<p class="help-card-more" aria-hidden="true">How we help<?php echo aquariende_icon( 'arrow' ); // phpcs:ignore WordPress.Security.EscapeOutput ?></p>
			</article>
		<?php endforeach; ?>
	</div>
	<?php
	return;
endif;
?>
<div class="help-full">
	<?php foreach ( $aq_topics as $aq_key => $aq_t ) : ?>
		<?php $aq_guide = aquariende_help_guide( $aq_t['guide'] ); ?>
		<article class="help-topic" id="help-<?php echo esc_attr( $aq_key ); ?>" data-reveal>
			<div class="help-topic-head">
				<?php echo aquariende_art( $aq_t['art'], 'help-topic-art' ); // phpcs:ignore WordPress.Security.EscapeOutput -- fixed SVG. ?>
				<div>
					<h3 class="help-topic-title"><?php echo esc_html( $aq_t['title'] ); ?></h3>
					<p class="help-topic-part"><?php echo esc_html( $aq_t['part'] ); ?></p>
				</div>
			</div>
			<div class="help-topic-body">
				<div class="help-rule">
					<p class="list-label">The official rule</p>
					<p><?php echo esc_html( $aq_t['rule'] ); ?></p>
					<p class="help-source">Source: <a href="<?php echo esc_url( $aq_t['source']['url'] ); ?>" rel="noopener" data-ext="<?php echo esc_attr( (string) wp_parse_url( $aq_t['source']['url'], PHP_URL_HOST ) ); ?>"><?php echo esc_html( $aq_t['source']['name'] ); ?></a>, checked <?php echo aquariende_time( $aq_t['checked'] ); // phpcs:ignore WordPress.Security.EscapeOutput ?>.</p>
				</div>
				<div>
					<p class="list-label">We do</p>
					<ul class="checklist">
						<?php foreach ( $aq_t['we_do'] as $aq_x ) : ?>
							<li><?php echo aquariende_icon( 'check' ); // phpcs:ignore WordPress.Security.EscapeOutput ?><span><?php echo esc_html( $aq_x ); ?></span></li>
						<?php endforeach; ?>
					</ul>
				</div>
				<div>
					<p class="list-label">You do</p>
					<ul class="checklist checklist--you">
						<?php foreach ( $aq_t['you_do'] as $aq_x ) : ?>
							<li><?php echo aquariende_icon( 'arrow' ); // phpcs:ignore WordPress.Security.EscapeOutput ?><span><?php echo esc_html( $aq_x ); ?></span></li>
						<?php endforeach; ?>
					</ul>
					<?php if ( ! empty( $aq_t['never'] ) ) : ?>
						<p class="help-never"><?php echo aquariende_icon( 'cross' ); // phpcs:ignore WordPress.Security.EscapeOutput ?><span><strong>Never promised:</strong> <?php echo esc_html( $aq_t['never'] ); ?></span></p>
					<?php endif; ?>
				</div>
			</div>
			<p class="help-topic-foot">
				<a class="btn btn-primary" href="<?php echo esc_url( aquariende_whatsapp_url( $aq_t['wa'], $aq_t['wa_code'] ) ); ?>" target="_blank" rel="noopener" data-wa="<?php echo esc_attr( $aq_t['wa_code'] . ':help' ); ?>"><?php echo aquariende_icon( 'whatsapp' ); // phpcs:ignore WordPress.Security.EscapeOutput ?><span>Ask about this on WhatsApp<span class="screen-reader-text"> (opens WhatsApp)</span></span></a>
				<?php if ( $aq_guide ) : ?>
					<a class="help-topic-guide" href="<?php echo esc_url( get_permalink( $aq_guide ) ); ?>">Read the guide: <?php echo esc_html( get_the_title( $aq_guide ) ); ?><?php echo aquariende_icon( 'arrow' ); // phpcs:ignore WordPress.Security.EscapeOutput ?></a>
				<?php endif; ?>
			</p>
		</article>
	<?php endforeach; ?>
</div>
