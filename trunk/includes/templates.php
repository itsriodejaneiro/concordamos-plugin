<?php

namespace Concordamos;

function add_create_voting_form( $content ) {
	if ( is_page() && is_main_query() ) {
		$voting_page = get_voting_page();

		if ( get_the_ID() == $voting_page ) {
			$content .= '<div id="concordamos-voting-form"></div>';
		}
	}

	return $content;
}

add_filter( 'the_content', 'Concordamos\add_create_voting_form' );

function load_single_voting_template( $template ) {
	if ( is_singular( 'voting' ) && $template !== locate_template( array( 'single-voting.php' ) ) ) {
		return CONCORDAMOS_PLUGIN_PATH . 'templates/single-voting.php';
	}

	return $template;
}

add_filter( 'single_template', 'Concordamos\load_single_voting_template' );
add_filter( 'singular_template', 'Concordamos\load_single_voting_template' );

function load_voting_archive_template( $template ) {
	if ( is_post_type_archive( 'voting' ) && $template !== locate_template( array( 'archive-voting.php' ) ) ) {
		return CONCORDAMOS_PLUGIN_PATH . 'templates/archive-voting.php';
	}

	return $template;
}

add_filter( 'archive_template', 'Concordamos\load_voting_archive_template' );

function custom_page_templates( array $templates ): array {
	$templates['concordamos/template-login.php']       = __( 'Login', 'concordamos' ) . ' [Concordamos]';
	$templates['concordamos/template-create-user.php'] = __( 'Create user', 'concordamos' ) . ' [Concordamos]';
	$templates['concordamos/template-my-account.php']  = __( 'My account', 'concordamos' ) . ' [Concordamos]';
	$templates['concordamos/template-change-pass.php'] = __( 'Change password', 'concordamos' ) . ' [Concordamos]';
	$templates['concordamos/privacy-policy.php'] = __( 'Privacy Policy', 'concordamos' ) . ' [Concordamos]';
	$templates['concordamos/terms-of-use.php'] = __( 'Terms of Use', 'concordamos' ) . ' [Concordamos]';



	return $templates;
}

add_filter( 'theme_page_templates', 'Concordamos\custom_page_templates', 10, 1 );

function page_templates( string $template ): string {
	$template_slug = get_page_template_slug();

	if ( strpos( $template_slug, 'concordamos/' ) === 0 ) {
		$template = CONCORDAMOS_PLUGIN_PATH . str_replace( 'concordamos/', 'templates/', $template_slug );
	}

	return $template;
}
add_filter( 'page_template', 'Concordamos\\page_templates', 10, 1 );
