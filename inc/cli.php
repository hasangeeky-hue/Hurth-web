<?php
/**
 * WP-CLI commands.
 *
 *   wp aquariende schema-check <url>   Validate the JSON-LD of a page (06 §5).
 *   wp aquariende install              Run the content installer.
 *
 * @package Aquariende
 */

if ( ! defined( 'ABSPATH' ) || ! class_exists( 'WP_CLI' ) ) {
	return;
}

/**
 * Aquariende commands.
 */
class Aquariende_CLI {

	/**
	 * Check the JSON-LD of a URL: valid JSON, no empty strings, absolute URLs,
	 * no price/offers/rating keys, every @id reference resolves in the graph.
	 *
	 * ## OPTIONS
	 *
	 * <url>
	 * : Page URL.
	 *
	 * [--file=<path>]
	 * : Read the HTML from a saved file instead of fetching the URL.
	 *
	 * @subcommand schema-check
	 *
	 * @param array $args       Args.
	 * @param array $assoc_args Options.
	 */
	public function schema_check( $args, $assoc_args = array() ) {
		$url = $args[0];
		if ( ! empty( $assoc_args['file'] ) ) {
			$html = (string) file_get_contents( $assoc_args['file'] ); // phpcs:ignore WordPress.WP.AlternativeFunctions
		} else {
			$response = wp_remote_get( $url, array( 'timeout' => 20, 'sslverify' => false ) );
			if ( is_wp_error( $response ) ) {
				WP_CLI::error( $response->get_error_message() );
			}
			$html = wp_remote_retrieve_body( $response );
		}
		if ( ! preg_match_all( '#<script type="application/ld\+json">(.*?)</script>#s', $html, $m ) ) {
			WP_CLI::error( 'No JSON-LD found.' );
		}
		$errors = array();
		$ids    = array();
		$refs   = array();
		$types  = array();
		foreach ( $m[1] as $json ) {
			$data = json_decode( $json, true );
			if ( null === $data ) {
				$errors[] = 'Invalid JSON: ' . json_last_error_msg();
				continue;
			}
			$walk = function ( $node, $path ) use ( &$walk, &$errors, &$ids, &$refs, &$types ) {
				if ( ! is_array( $node ) ) {
					if ( '' === $node ) {
						$errors[] = 'Empty string at ' . $path;
					}
					return;
				}
				if ( isset( $node['@type'] ) ) {
					$types[] = is_array( $node['@type'] ) ? implode( '+', $node['@type'] ) : $node['@type'];
				}
				if ( isset( $node['@id'] ) ) {
					if ( count( $node ) === 1 ) {
						$refs[] = array( $node['@id'], $path );
					} else {
						$ids[ $node['@id'] ] = true;
					}
				}
				foreach ( $node as $key => $value ) {
					if ( in_array( $key, array( 'price', 'offers', 'priceCurrency', 'aggregateRating', 'review' ), true ) ) {
						$errors[] = 'Forbidden key "' . $key . '" at ' . $path;
					}
					if ( in_array( $key, array( 'url', 'item', 'logo', 'image', 'sameAs', '@id', 'inDefinedTermSet' ), true ) && is_string( $value ) && ! preg_match( '#^https?://#', $value ) ) {
						$errors[] = 'Relative URL in "' . $key . '" at ' . $path . ': ' . $value;
					}
					$walk( $value, $path . '/' . $key );
				}
			};
			$walk( $data, '' );
		}
		foreach ( $refs as $ref ) {
			if ( ! isset( $ids[ $ref[0] ] ) ) {
				$errors[] = 'Unresolved @id ' . $ref[0] . ' at ' . $ref[1];
			}
		}
		WP_CLI::log( 'Types: ' . implode( ', ', array_unique( $types ) ) );
		if ( $errors ) {
			foreach ( $errors as $e ) {
				WP_CLI::warning( $e );
			}
			WP_CLI::error( count( $errors ) . ' problem(s) in ' . $url );
		}
		WP_CLI::success( 'Schema OK: ' . $url );
	}

	/**
	 * Run the content installer.
	 */
	public function install() {
		$ok = aquariende_content_install( 'WP-CLI' );
		$ok ? WP_CLI::success( 'Installer ran. See Appearance → Aquariende content for the log.' ) : WP_CLI::error( 'Another run holds the lock.' );
	}
}

WP_CLI::add_command( 'aquariende', 'Aquariende_CLI' );
