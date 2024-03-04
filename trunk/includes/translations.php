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

	return apply_filters( 'concordamos_language_options', $options );
}

/**
 * For translated posts, return the post ID of the source post, i.e.
 * the original, not-translated post
 */
function get_source_post_id( string $post_type, $post_id ) {
	$element_type   = 'post_' . $post_type;
	$translation_id = apply_filters( 'wpml_element_trid', null, $post_id, $element_type );

	if ( ! empty( $translation_id ) ) {
		$translations = apply_filters( 'wpml_get_element_translations', array(), $translation_id, $element_type );
		foreach ( $translations as $key => $translation ) {
			if ( $translation->original === '1' ) {
				return $translation->element_id;
			}
		}
	}

	// On failure, return the input ID
	return $post_id;
}

function get_translation_ids( string $post_type, $post_id, $include_input_id = true ) {
	$element_type   = 'post_' . $post_type;
	$translation_id = apply_filters( 'wpml_element_trid', null, $post_id, $element_type );

	if ( ! empty( $translation_id ) ) {
		$translations = apply_filters( 'wpml_get_element_translations', array(), $translation_id, $element_type );

		$post_ids = array();
		foreach ( $translations as $translation ) {
			if ( $include_input_id || $translation->element_id != $post_id ) {
				$post_ids[] = intval( $translation->element_id );
			}
		}
		return $post_ids;
	}

	// On failure, return the input ID
	if ( $include_input_id ) {
		return array( intval( $post_id ) );
	} else {
		return array();
	}
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
			return $sitepress->get_languages( $sitepress->get_current_language(), true, false, false, 'display_name' );
		}
	}

	$options = array( 'skip_empty' => 0 );
	return apply_filters( 'wpml_active_languages', array(), $options );
}

function localize_plugin() {
	load_plugin_textdomain( 'concordamos', false, basename( CONCORDAMOS_PLUGIN_PATH ) . '/languages/' );
}

add_action( 'after_setup_theme', 'Concordamos\localize_plugin', 0 );

/**
 * Set language for source (i.e. non-translated) post
 */
function set_post_language( $post_type, $post_id, $locale ) {
	$element_type   = 'post_' . $post_type;
	$translation_id = apply_filters( 'wpml_element_trid', null, $post_id, $element_type );

	if ( ! empty( $translation_id ) ) {
		$language_details = array(
			'element_id'           => $post_id,
			'element_type'         => $element_type,
			'trid'                 => $translation_id,
			'language_code'        => get_wpml_language_code( $locale ),
			'source_language_code' => null,
		);

		do_action( 'wpml_set_element_language_details', $language_details );
	}
}

/**
 * Mark an post as a translation for another post
 */
function set_post_translation( $post_type, $source_id, $post_id, $locale ) {
	$element_type = 'post_' . $post_type;

	$source_locale = get_post_meta( $source_id, 'locale', true );
	$source_locale = empty( $source_locale ) ? get_default_language() : $source_locale;

	$get_language_details = array(
		'element_id'   => $source_id,
		'element_type' => $post_type,
	);
	$get_language_details = apply_filters( 'wpml_element_language_details', null, $get_language_details );

	if ( ! empty( $get_language_details ) ) {
		$set_language_details = array(
			'element_id'           => $post_id,
			'element_type'         => $element_type,
			'trid'                 => $get_language_details->trid,
			'language_code'        => get_wpml_language_code( $locale ),
			'source_language_code' => $get_language_details->language_code,
		);

		do_action( 'wpml_set_element_language_details', $set_language_details );
	}
}
