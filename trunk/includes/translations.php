<?php

namespace Concordamos;

function format_locale( $locale ) {
	$locales = get_wpml_locales();

	if ( ! empty( $locales[ $locale ] ) ) {
		$locale = $locales[ $locale ]['default_locale'];
	}

	return $locale;
}

add_filter( 'concordamos_locale', 'Concordamos\format_locale' );

function get_current_language() {
	$locale = apply_filters( 'wpml_current_language', null );

	if ( empty( $locale ) ) {
		$locale = determine_locale();
	}

	return apply_filters( 'concordamos_locale', $locale, 'current' );
}

function get_default_language() {
	$locale = apply_filters( 'wpml_default_language', null );

	if ( empty( $locale ) ) {
		$locale = get_locale();
	}

	return apply_filters( 'concordamos_locale', $locale, 'default' );
}

function get_language_options() {
	$locales = get_wpml_locales();
	$options = array();

	foreach ( $locales as $key => $locale ) {
		$options[] = array(
			'key'    => $locale['default_locale'],
			'label'  => empty( $locale['display_name'] ) ? $locale['translated_name'] : $locale['display_name'],
			'native' => $locale['native_name'],
		);
	}

	return $options;
}

function get_wpml_language_code( $locale ) {
	if ( empty( $locale ) ) {
		return $locale;
	}

	$locales = get_wpml_locales();

	foreach ( $locales as $key => $wpml_locale ) {
		if ( $wpml_locale['default_locale'] === $locale ) {
			return $wpml_locale['code'];
		}
	}

	return $locale;
}

function get_wpml_locales() {
	if ( class_exists( 'SitePress' ) ) {
		global $sitepress;
		if ( ! empty( $sitepress ) ) {
			return $sitepress->get_languages( $sitepress->get_current_language(), true, false );
		}
	}

	$options = array( 'skip_empty' => 0 );
	return apply_filters( 'wpml_active_languages', array(), $options );
}

function localize_plugin() {
	load_plugin_textdomain( 'concordamos', false, basename( CONCORDAMOS_PLUGIN_PATH ) . '/languages/' );
}

add_action( 'after_setup_theme', 'Concordamos\localize_plugin', 0 );

function set_post_language( $post_id, $post_type, $locale, $original_locale = null ) {
	$element_type   = 'post_' . $post_type;
	$translation_id = apply_filters( 'wpml_element_trid', null, $post_id, $element_type );

	if ( ! empty( $translation_id ) ) {
		$language_details = array(
			'element_id'           => $post_id,
			'element_type'         => $element_type,
			'trid'                 => $translation_id,
			'language_code'        => get_wpml_language_code( $locale ),
			'source_language_code' => get_wpml_language_code( $original_locale ),
		);

		do_action( 'wpml_set_element_language_details', $language_details );
	}
}
