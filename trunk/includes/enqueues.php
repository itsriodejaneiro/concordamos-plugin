<?php

namespace Concordamos;

function enqueue_scripts_admin() {
	wp_enqueue_style( 'concordamos-admin', CONCORDAMOS_PLUGIN_URL . 'build/css/admin.css', array(), CONCORDAMOS_PLUGIN_VERSION );
}

add_action( 'admin_enqueue_scripts', 'Concordamos\enqueue_scripts_admin' );

function enqueue_scripts_frontend() {
	wp_register_style( 'concordamos-style', CONCORDAMOS_PLUGIN_URL . 'build/css/plugin.css', array(), CONCORDAMOS_PLUGIN_VERSION );

	if ( is_page() ) {
		$template_slug = get_page_template_slug();

		if ( $template_slug === 'concordamos/template-create-voting.php' ) {
			wp_enqueue_style( 'concordamos-create-voting-style', CONCORDAMOS_PLUGIN_URL . 'build/css/template-create-voting.css', array( 'concordamos-style' ), CONCORDAMOS_PLUGIN_VERSION );

			enqueue_localized_script(
				'concordamos-create-voting',
				CONCORDAMOS_PLUGIN_URL . 'build/js/create-voting/index.js',
				array( 'wp-element', 'wp-i18n' ),
				'concordamos',
				array(
					'locale'     => get_current_language(),
					'locales'    => get_language_options(),
					'nonce'      => wp_create_nonce( 'wp_rest' ),
					'plugin_url' => CONCORDAMOS_PLUGIN_URL,
					'rest_url'   => rest_url( 'concordamos/v1/' ),
					'user_id'    => get_current_user_id(),
				)
			);
		}

		if ( $template_slug === 'concordamos/template-translate-voting.php' ) {
			wp_enqueue_style( 'concordamos-translate-voting-style', CONCORDAMOS_PLUGIN_URL . 'build/css/template-translate-voting.css', array( 'concordamos-style' ), CONCORDAMOS_PLUGIN_VERSION );

			$voting_slug = sanitize_key( filter_input( INPUT_GET, 'v_id' ) );

			enqueue_localized_script(
				'concordamos-translate-voting',
				CONCORDAMOS_PLUGIN_URL . 'build/js/translate-voting/index.js',
				array( 'wp-element', 'wp-i18n' ),
				'concordamos',
				array(
					'locale'               => get_current_language(),
					'locales'              => get_language_options(),
					'nonce'                => wp_create_nonce( 'wp_rest' ),
					'plugin_url'           => CONCORDAMOS_PLUGIN_URL,
					'rest_url'             => rest_url( 'concordamos/v1/' ),
					'user_id'              => get_current_user_id(),
					'translation_template' => get_translation_template( $voting_slug ),
					'voting_id'            => $voting_slug,
				)
			);
		}

		if ( $template_slug === 'concordamos/template-login.php' ) {
			wp_enqueue_style( 'concordamos-template-login-style', CONCORDAMOS_PLUGIN_URL . 'build/css/template-login.css', array( 'concordamos-style' ), CONCORDAMOS_PLUGIN_VERSION );

			$redirect_to = filter_input( INPUT_GET, 'redirect_to', FILTER_VALIDATE_URL );

			enqueue_localized_script(
				'concordamos-login',
				CONCORDAMOS_PLUGIN_URL . 'build/js/user/login.js',
				array( 'wp-element' ),
				'concordamos_login',
				array(
					'nonce'       => wp_create_nonce( 'wp_rest' ),
					'redirect_to' => empty( $redirect_to ) ? get_permalink( get_page_by_template( 'concordamos/template-my-account.php' ) ) : esc_url( get_home_url( null, $redirect_to ) ),
				)
			);
		}

		if ( $template_slug === 'concordamos/template-create-user.php' ) {
			wp_enqueue_style( 'concordamos-template-login-style', CONCORDAMOS_PLUGIN_URL . 'build/css/template-login.css', array( 'concordamos-style' ), CONCORDAMOS_PLUGIN_VERSION );

			enqueue_localized_script(
				'concordamos-create-user',
				CONCORDAMOS_PLUGIN_URL . 'build/js/user/register.js',
				array(),
				'concordamos',
				array(
					'nonce'          => wp_create_nonce( 'wp_rest' ),
					'plugin_url'     => CONCORDAMOS_PLUGIN_URL,
					'privacy_policy' => get_the_content( null, false, get_page_by_template( 'concordamos/template-privacy-policy.php' ) ),
					'rest_url'       => rest_url( 'concordamos/user/v1' ),
					'terms_of_use'   => get_the_content( null, false, get_page_by_template( 'concordamos/template-terms-of-use.php' ) ),
					'my_account_url' => get_permalink( get_page_by_template( 'concordamos/template-my-account.php' ) ),
				)
			);
		}

		if ( $template_slug === 'concordamos/template-my-account.php' ) {
			wp_enqueue_style( 'concordamos-my-account-style', CONCORDAMOS_PLUGIN_URL . 'build/css/my-account.css', array( 'concordamos-style' ), CONCORDAMOS_PLUGIN_VERSION );

			enqueue_localized_script(
				'concordamos-my-account',
				CONCORDAMOS_PLUGIN_URL . 'build/js/my-account/index.js',
				array( 'wp-element', 'wp-i18n' ),
				'concordamos',
				array(
					'voting_archive_url' => get_post_type_archive_link( 'voting' ),
					'create_voting_url'  => get_permalink( get_page_by_template( 'concordamos/template-create-voting.php' ) ),
					'locale'             => get_current_language(),
					'login_url'          => get_permalink( get_page_by_template( 'concordamos/template-login.php' ) ),
					'nonce'              => wp_create_nonce( 'wp_rest' ),
					'plugin_url'         => CONCORDAMOS_PLUGIN_URL,
					'privacy_policy'     => get_the_content( null, false, get_page_by_template( 'concordamos/template-privacy-policy.php' ) ),
					'rest_url'           => rest_url( 'concordamos/v1/' ),
					'terms_of_use'       => get_the_content( null, false, get_page_by_template( 'concordamos/template-terms-of-use.php' ) ),
					'user_id'            => get_current_user_id(),
				)
			);
		}

		if ( $template_slug === 'concordamos/template-change-pass.php' ) {
			wp_enqueue_style( 'concordamos-template-template-change-pass', CONCORDAMOS_PLUGIN_URL . 'build/css/template-change-pass.css', array( 'concordamos-style' ), CONCORDAMOS_PLUGIN_VERSION );

			enqueue_localized_script(
				'concordamos-change-pass',
				CONCORDAMOS_PLUGIN_URL . 'build/js/user/change-pass.js',
				array(),
				'concordamos',
				array(
					'nonce'      => wp_create_nonce( 'wp_rest' ),
					'plugin_url' => CONCORDAMOS_PLUGIN_URL,
					'rest_url'   => rest_url( 'concordamos/user/v1' ),
					'login_url'  => get_permalink( get_page_by_template( 'concordamos/template-login.php' ) ),
				)
			);
		}
	}

	if ( is_singular( 'voting' ) ) {
		wp_enqueue_style( 'concordamos-single-voting-style', CONCORDAMOS_PLUGIN_URL . 'build/css/single-voting.css', array( 'concordamos-style' ), CONCORDAMOS_PLUGIN_VERSION );

		$unique_id = sanitize_title( get_query_var( 'unique_id' ) );
		$voting_id = get_the_ID();
		$source_id = get_source_post_id( 'voting', $voting_id );

		enqueue_localized_script(
			'concordamos-voting-single',
			CONCORDAMOS_PLUGIN_URL . 'build/js/voting-single/index.js',
			array( 'wp-element', 'wp-i18n' ),
			'concordamos',
			array(
				'a_id'           => is_user_logged_in() ? get_post_meta( $source_id, 'admin_id', true ) : '',
				'admin_email'    => get_bloginfo( 'admin_email' ),
				'archive_url'    => get_post_type_archive_link( 'voting' ),
				'faq_url'        => get_permalink( get_faq_page() ),
				'is_author'      => is_voting_owner( $voting_id ),
				'locale'         => get_current_language(),
				'negative_votes' => get_post_meta( $voting_id, 'negative_votes', true ),
				'nonce'          => wp_create_nonce( 'wp_rest' ),
				'plugin_url'     => CONCORDAMOS_PLUGIN_URL,
				'rest_url'       => rest_url( 'concordamos/v1/' ),
				'u_id'           => $unique_id,
				'user_id'        => get_current_user_id(),
				'v_id'           => $source_id,
			)
		);
	}

	if ( is_post_type_archive( 'voting' ) ) {
		wp_enqueue_style( 'concordamos-voting-archive-style', CONCORDAMOS_PLUGIN_URL . 'build/css/archive-voting.css', array( 'concordamos-style' ), CONCORDAMOS_PLUGIN_VERSION );

		enqueue_localized_script(
			'concordamos-voting-archive',
			CONCORDAMOS_PLUGIN_URL . 'build/js/voting-archive/index.js',
			array( 'wp-element', 'wp-i18n' ),
			'concordamos',
			array(
				'locale'     => get_current_language(),
				'nonce'      => wp_create_nonce( 'wp_rest' ),
				'plugin_url' => CONCORDAMOS_PLUGIN_URL,
				'rest_url'   => rest_url( 'concordamos/v1/' ),
			)
		);
	}

	$id = get_the_ID();

	$block_list = array(
		'concordamos/login' => function () {
			wp_enqueue_style(
				'concordamos-login',
				CONCORDAMOS_PLUGIN_URL . 'build/css/blocks/login.css',
				array(),
				CONCORDAMOS_PLUGIN_VERSION,
				'all'
			);
		},
	);

	// Enqueue only used blocks
	foreach ( $block_list as $key => $block ) {
		if ( has_block( $key, $id ) ) {
			$block();
		}
	}
}

add_action( 'wp_enqueue_scripts', 'Concordamos\enqueue_scripts_frontend' );
