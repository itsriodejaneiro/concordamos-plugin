<?php

namespace Concordamos;

function get_current_language() {
	$locale = apply_filters( 'wpml_current_language', null );

	if ( empty( $locale ) ) {
		$locale = determine_locale();
	}

	return $locale;
}

function get_language_options() {
	$locales = apply_filters( 'wpml_active_languages', array() );
	$options  = array();

	foreach ( $locales as $key => $locale ) {
		$options[] = array(
			'key'    => $locale['default_locale'],
			'label'  => $locale['translated_name'],
			'native' => $locale['native_name'],
		);
	}

	return $options;
}

function localize_plugin() {
	load_plugin_textdomain( 'concordamos', false, basename( CONCORDAMOS_PLUGIN_PATH ) . '/languages/' );
}

add_action( 'after_setup_theme', 'Concordamos\localize_plugin', 0 );
