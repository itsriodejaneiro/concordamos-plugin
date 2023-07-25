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

	// Check if user has permission
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
		"date_start",
		"date_end",
		"voting_type"
	];

	// Check required params
	foreach ( $required_params as $param ) {
		if ( ! isset( $params[$param] ) || empty( $params[$param] ) ) {
			$response = [
				'status' => 'error',
				'message' => 'Campo necessário não recebido ou está vazio: ' . $param
			];
			return new \WP_REST_Response( $response, 400 );
		}
	}

	$number_voters = intval( $params['number_voters'] );
	$voting_type = sanitize_text_field( $params['voting_type'] );

	$unique_ids = '';

	// Generate all unique links to voting if is private
	if ( $voting_type === 'private' ) {
		for ( $i = 0; $i < $number_voters; $i++ ) {
			$prefix = 'u-' . random_int( 100, 999 );
			$unique_ids .= uniqid( $prefix ) . ',';
		}
	}

	// Generate the post_name using random_int and uniqid functions
	$prefix = 'v-' . random_int( 100, 999 );
	$post_name = uniqid( $prefix );

	$args = [
		'post_author'  => get_current_user_id(), // @todo: add option to vote without login
		'post_content' => wp_kses_post( $params['voting_description'] ),
		'post_name'    => $post_name,
		'post_status'  => 'draft',
		'post_title'   => sanitize_text_field( $params['voting_name'] ),
		'post_type'    => 'voting',
		'tax_input'    => [
			'tag' => sanitize_text_field( $params['tags'] )
		],
		'meta_input' => [
			'credits_voter' => intval( $params['credits_voter'] ),
			'date_end'      => intval( $params['date_end'] ),
			'date_start'    => intval( $params['date_start'] ),
			'description'   => wp_kses_post( $params['voting_description'] ),
			'number_voters' => $number_voters,
			'unique_ids'    => $unique_ids,
			'voting_access' => sanitize_text_field( $params['voting_access'] ),
			'voting_name'   => sanitize_text_field( $params['voting_name'] ),
			'voting_type'   => $voting_type
		]
	];

	// Create post
	$post_id = wp_insert_post( $args );

	if ( $post_id ) {

		$voting_options = $params['voting_options'];

		foreach ( $voting_options as $item ) {
			$option_name        = sanitize_text_field( $item['option_name'] );
			$option_description = wp_kses_post( $item['option_description'] );
			$option_link        = esc_url( $item['option_link'] );

			$options_args = [
				'post_type'   => 'option',
				'post_title'  => $option_name,
				'post_status' => 'publish',
				'post_author' => get_current_user_id(),
				'meta_input'  => [
					'option_name'        => $option_name,
					'option_description' => $option_description,
					'option_link'        => $option_link,
					'voting_id'          => $post_id
				]
			];

			// Create option
			$option_id = wp_insert_post( $options_args );
		}

		$response = [
			'status'  => 'success',
			'message' => 'Votação criada com sucesso!'
		];
		return new \WP_REST_Response( $response, 200 );

	} else {
		$response = [
			'status'  => 'error',
			'message' => 'Verifique todos os campos e envie novamente.'
		];
		return new \WP_REST_Response( $response, 400 );
	}
}
