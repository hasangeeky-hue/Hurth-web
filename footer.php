<?php
/**
 * Site footer.
 *
 * The NAP block uses one canonical address, in a single consistent format.
 * Inconsistent name/address/phone data across the web damages local ranking,
 * so this must match the Google Business Profile exactly.
 *
 * @package Hurth
 */

$hurth_q = ( 'en' === hurth_lang() ) ? '?lang=en' : '';
?>
</main>

<section class="cta-band">
	<div class="wrap cta-band__inner">
		<div>
			<h2><?php echo esc_html( hurth_t( 'cta_title' ) ); ?></h2>
			<p><?php echo esc_html( hurth_t( 'cta_text' ) ); ?></p>
		</div>
		<div class="cta-band__actions">
			<a class="btn btn--accent" href="tel:<?php echo esc_attr( hurth_info( 'phone_href' ) ); ?>">
				<?php echo esc_html( hurth_t( 'call' ) ); ?>
			</a>
			<a class="btn btn--light" href="<?php echo esc_url( hurth_info( 'maps' ) ); ?>"
				target="_blank" rel="noopener">
				<?php echo esc_html( hurth_t( 'route' ) ); ?>
			</a>
		</div>
	</div>
</section>

<footer class="site-footer">
	<div class="wrap">

		<div class="footer-grid">

			<div>
				<h3><?php echo esc_html( hurth_info( 'name' ) . ' ' . hurth_info( 'city_tag' ) ); ?></h3>
				<p>
					<?php echo esc_html( hurth_info( 'street' ) ); ?><br>
					<?php echo esc_html( hurth_info( 'zip' ) . ' ' . hurth_info( 'town' ) ); ?><br>
					<a href="tel:<?php echo esc_attr( hurth_info( 'phone_href' ) ); ?>">
						<?php echo esc_html( hurth_info( 'phone' ) ); ?>
					</a><br>
					<a href="mailto:<?php echo esc_attr( hurth_info( 'email' ) ); ?>">
						<?php echo esc_html( hurth_info( 'email' ) ); ?>
					</a>
				</p>
			</div>

			<div>
				<h3><?php echo esc_html( hurth_t( 'f_services' ) ); ?></h3>
				<ul>
					<?php
					foreach ( hurth_nav_items() as $hurth_slug => $hurth_key ) {
						$hurth_page = get_page_by_path( $hurth_slug );

						if ( ! $hurth_page || 'publish' !== $hurth_page->post_status ) {
							continue;
						}

						printf(
							'<li><a href="%s">%s</a></li>',
							esc_url( get_permalink( $hurth_page ) . $hurth_q ),
							esc_html( hurth_t( $hurth_key ) )
						);
					}
					?>
				</ul>
			</div>

			<div>
				<h3><?php echo esc_html( hurth_t( 'f_hours' ) ); ?></h3>
				<table class="hours">
					<tbody>
					<?php foreach ( hurth_hours() as $hurth_day => $hurth_range ) : ?>
						<tr>
							<th scope="row"><?php echo esc_html( hurth_day_label( $hurth_day ) ); ?></th>
							<td>
								<?php
								echo null === $hurth_range
									? esc_html( hurth_t( 'closed' ) )
									: esc_html( $hurth_range[0] . '–' . $hurth_range[1] );
								?>
							</td>
						</tr>
					<?php endforeach; ?>
					</tbody>
				</table>
			</div>

			<div>
				<h3><?php echo esc_html( hurth_t( 'f_areas' ) ); ?></h3>
				<p><?php echo esc_html( hurth_t( 'areas' ) ); ?></p>
				<p style="margin-top:1rem">
					<a class="btn btn--light" href="<?php echo esc_url( hurth_info( 'maps' ) ); ?>"
						target="_blank" rel="noopener">
						<?php echo esc_html( hurth_t( 'route' ) ); ?>
					</a>
				</p>
			</div>

		</div>

		<div class="site-footer__legal">
			<span>
				&copy; <?php echo esc_html( gmdate( 'Y' ) ); ?>
				<?php echo esc_html( hurth_info( 'name' ) . ' ' . hurth_info( 'city_tag' ) ); ?>.
				<?php echo esc_html( hurth_t( 'rights' ) ); ?>
			</span>

			<span>
				<?php
				/*
				 * Impressum and Datenschutzerklärung must be reachable from every
				 * page of a German commercial site. The German pages are the legally
				 * binding versions; the English ones are convenience translations.
				 */
				$hurth_legal = ( 'en' === hurth_lang() )
					? array( 'en-imprint' => 'Imprint', 'en-privacy-policy' => 'Privacy Policy' )
					: array( 'impressum' => 'Impressum', 'datenschutz' => 'Datenschutz' );

				$hurth_links = array();

				foreach ( $hurth_legal as $hurth_slug => $hurth_label ) {
					$hurth_page = get_page_by_path( $hurth_slug );

					if ( $hurth_page && 'publish' === $hurth_page->post_status ) {
						$hurth_links[] = sprintf(
							'<a href="%s">%s</a>',
							esc_url( get_permalink( $hurth_page ) . $hurth_q ),
							esc_html( $hurth_label )
						);
					}
				}

				echo wp_kses_post( implode( ' · ', $hurth_links ) );
				?>
			</span>
		</div>

	</div>
</footer>

<nav class="mobile-bar" aria-label="<?php esc_attr_e( 'Quick actions', 'hurth' ); ?>">
	<a href="tel:<?php echo esc_attr( hurth_info( 'phone_href' ) ); ?>">
		<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M6.6 10.8a15.1 15.1 0 006.6 6.6l2.2-2.2c.3-.3.7-.4 1-.2 1.2.4 2.4.6 3.6.6.6 0 1 .4 1 1V20c0 .6-.4 1-1 1A17 17 0 013 4c0-.6.4-1 1-1h3.5c.6 0 1 .4 1 1 0 1.3.2 2.5.6 3.6.1.3 0 .7-.2 1l-2.3 2.2z"/></svg>
		<?php echo esc_html( hurth_t( 'call' ) ); ?>
	</a>
	<a href="https://wa.me/<?php echo esc_attr( ltrim( hurth_info( 'phone_href' ), '+' ) ); ?>"
		target="_blank" rel="noopener">
		<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M12 2a10 10 0 00-8.6 15L2 22l5.2-1.4A10 10 0 1012 2zm0 18a8 8 0 01-4.1-1.1l-.3-.2-3 .8.8-2.9-.2-.3A8 8 0 1112 20zm4.4-5.8c-.2-.1-1.4-.7-1.6-.8-.2-.1-.4-.1-.5.1l-.7.9c-.1.2-.3.2-.5.1a6.5 6.5 0 01-3.2-2.8c-.1-.2 0-.4.1-.5l.4-.5c.1-.2.1-.3 0-.5l-.7-1.7c-.2-.4-.4-.4-.5-.4h-.5c-.2 0-.5.1-.7.3-.2.3-.9.9-.9 2.1s.9 2.4 1 2.6c.1.2 1.8 2.8 4.4 3.9 1.6.7 2.2.7 3 .6.5-.1 1.4-.6 1.6-1.2.2-.6.2-1.1.1-1.2 0-.1-.2-.2-.4-.3z"/></svg>
		<?php echo esc_html( hurth_t( 'whatsapp' ) ); ?>
	</a>
	<a href="<?php echo esc_url( hurth_info( 'maps' ) ); ?>" target="_blank" rel="noopener">
		<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M12 2a7 7 0 00-7 7c0 5 7 13 7 13s7-8 7-13a7 7 0 00-7-7zm0 9.5A2.5 2.5 0 1112 6.5a2.5 2.5 0 010 5z"/></svg>
		<?php echo esc_html( hurth_t( 'route' ) ); ?>
	</a>
</nav>

<?php wp_footer(); ?>
</body>
</html>
