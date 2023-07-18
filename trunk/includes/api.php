<?php

namespace Concordamos;

function register_endpoints() {
	register_rest_route( 'concordamos/v1', '/create-voting/', [
		'methods'             => 'POST',
		'callback'            => 'Concordamos\\create_voting_callback',
		'permission_callback' => 'Concordamos\\permission_check'
	] );
}
add_action( 'rest_api_init', 'Concordamos\\register_endpoints' );

function permission_check( \WP_REST_Request $request ) {

	if ( ! is_user_logged_in() ) {
		return new \WP_Error( 'rest_forbidden', 'Você não está logado.', array( 'status' => 401 ) );
	}

	$user = wp_get_current_user();

	if ( ! in_array( 'concordamos_network', $user->roles ) && ! in_array( 'administrator', $user->roles ) ) {
		return new \WP_Error( 'rest_forbidden', 'Você não tem permissões suficientes.', array( 'status' => 403 ) );
	}

	$params = $request->get_json_params();

	if ( $params['user_id'] != $user->ID ) {
		return new \WP_Error( 'rest_forbidden', 'Você não tem permissões suficientes.', array( 'status' => 403 ) );
	}

	return true;

}

function create_voting_callback( \WP_REST_Request $request ) {

	permission_check( $request );

	$params = $request->get_json_params();

	$required_params = [
		"credits_voter",
		"number_voters",
		"tags",
		"user_id",
		"voting_description",
		"voting_name",
		"voting_options",
		"voting_type"
	];

	foreach ( $required_params as $param ) {
		if ( ! isset( $params[$param] ) || empty( $params[$param] ) ) {
			$response = [
				'status' => 'error',
				'message' => 'Campo necessário não recebido ou está vazio: ' . $param
			];
			return new \WP_REST_Response( $response, 400 );
		}
	}

	$voting_options = $params['voting_options'];

	foreach ( $voting_options as &$item ) {
		$item['option_name'] = sanitize_text_field( $item['option_name'] );
		$item['option_description'] = wp_kses_post( $item['option_description'] );
		$item['option_link'] = esc_url( $item['option_link'] );
	}

	unset( $item );

	$args = [
		'post_type'    => 'voting',
		'post_title'   => sanitize_text_field( $params['voting_name'] ),
		'post_content' => wp_kses_post( $params['voting_description'] ),
		'post_status'  => 'draft',
		'post_author'  => get_current_user_id(),
		'tax_input'    => [
			"tag" => sanitize_text_field( $params['tags'] )
		],
		'meta_input' => [
			'voting_type'    => sanitize_text_field( $params['voting_type'] ),
			'voting_name'    => sanitize_text_field( $params['voting_name'] ),
			'description'    => wp_kses_post( $params['voting_description'] ),
			'number_voters'  => intval( $params['number_voters'] ),
			'credits_voter'  => intval( $params['credits_voter'] ),
			'voting_options' => $voting_options
		]
	];

	// Create post
	$post_id = wp_insert_post( $args );

	// Return
	if ( $post_id ) {
		$response = [
			'status' => 'success',
			'message' => 'Votação criada com sucesso!'
		];
		return new \WP_REST_Response( $response, 200 );
	} else {
		$response = [
			'status' => 'error',
			'message' => 'Verifique todos os campos e envie novamente.'
		];
		return new \WP_REST_Response( $response, 400 );
	}
}
