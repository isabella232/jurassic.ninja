<?php

namespace jn;

define( 'GUTENBERG_NIGHTLY_PLUGIN_URL', 'https://builds.danielbachhuber.com/gutenberg-nightly.zip' );

add_action( 'jurassic_ninja_init', function() {
	$defaults = [
		'gutenberg-nightly' => false,
	];

	add_action( 'jurassic_ninja_install_features_before_companion', function( &$app = null, $features, $domain ) use ( $defaults ) {
		$features = array_merge( $defaults, $features );
		if ( $features['gutenberg-nightly'] ) {
			debug( '%s: Adding Gutenberg nightly release Plugin', $domain );
			add_gutenberg_nightly_plugin();
		}
	}, 10, 3 );

	add_filter( 'jurassic_ninja_feature_defaults_for_rest_api_request', function( $defaults ) {
		return array_merge( $defaults, [
			'gutenberg-nightly' => false,
		] );
	} );

	add_filter( 'jurassic_ninja_features_from_rest_api_request', function( $features, $json_params ) {
		if ( isset( $json_params['gutenberg-nightly'] ) ) {
			$features['gutenberg-nightly'] = $json_params['gutenberg-nightly'];
		}
		return $features;
	}, 10, 2 );

} );

/**
 * Installs and activates the Gutenberg Nightly plugin on the site.
 * https://builds.danielbachhuber.com/gutenberg-nightly.zip
 * https://danielbachhuber.com/2018/10/02/gutenberg-nightly-build/
 */
function add_gutenberg_nightly_plugin() {
	$gutenberg_nightly_plugin_url = GUTENBERG_NIGHTLY_PLUGIN_URL;
	$cmd = "wp plugin install $gutenberg_nightly_plugin_url --activate";
	add_filter( 'jurassic_ninja_feature_command', function ( $s ) use ( $cmd ) {
		return "$s && $cmd";
	} );
}