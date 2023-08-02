<?php

namespace Concordamos;

function register_endpoints() {
	register_rest_route( 'concordamos/v1', '/create-voting/', [
		'methods'             => 'POST',
		'callback'            => 'Concordamos\\create_voting_callback',
		'permission_callback' => 'Concordamos\\permission_check'
	] );

	register_rest_route( 'concordamos/v1', '/vote/', [
		'methods'             => 'POST',
		'callback'            => 'Concordamos\\vote_callback',
		'permission_callback' => 'Concordamos\\permission_vote_check'
	] );

	register_rest_route( 'concordamos/v1', '/my-votings/', [
		'methods'             => 'GET',
		'callback'            => 'Concordamos\\list_my_votings_callback',
		'permission_callback' => 'Concordamos\\permission_vote_check',
	]);

	register_rest_route( 'concordamos/v1', '/votings/', [
		'methods'             => 'GET',
		'callback'            => 'Concordamos\\search_votings_callback',
		'permission_callback' => '__return_true',
	]);
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

function list_my_votings_callback ( \WP_REST_Request $request ) {
	$params = $request->get_params();

	$currentPage = empty($params['page']) ? 1 : intval($params['page']);

	$args = [
		'post_type' => 'voting',
		'post_status' => 'publish',
		'post_author' => get_current_user_id(),
		'posts_per_page' => 6,
		'paged' => $currentPage,
	];

	$query = new \WP_Query($args);

	return [
		'num_pages' => $query->max_num_pages,
		'posts' => array_map('Concordamos\\prepare_voting_for_api', $query->posts),
	];
}

function search_votings_callback ( \WP_REST_Request $request ) {
	$params = $request->get_params();

	$currentPage = empty($params['page']) ? 1 : intval($params['page']);

	$args = [
		'post_type' => 'voting',
		'post_status' => 'publish',
		'posts_per_page' => 6,
		'paged' => $currentPage,
	];

	$meta_query = [];

	if (!empty($params['query'])) {
		$args['s'] = $params['query'];
	}

	if (!empty($params['type'])) {
		$meta_query[] = [ 'key' => 'voting_type', 'value' => $params['type'] ];
	}

	if (!empty($params['access'])) {
		$meta_query[] = [ 'key' => 'voting_access', 'value' => $params['access'] ];
	}

	if (!empty($params['time'])) {
		$now = 1000 * time();

		if ($params['time'] === 'present') {
			$meta_query[] = [
				[ 'key' => 'date_end', 'compare' => '>', 'value' => $now ],
				[ 'key' => 'date_start', 'compare' => '<', 'value' => $now ],
				'relation' => 'AND',
			];
		} else if ($params['time'] === 'past') {
			$meta_query[] = [ 'key' => 'date_end', 'compare' => '<', 'value' => $now ];
		} else if ($params['time'] === 'future') {
			$meta_query[] = [ 'key' => 'date_start', 'compare' => '>', 'value' => $now ];
		}
	}

	if (!empty($meta_query)) {
		$args['meta_query'] = $meta_query;
	}

	$query = new \WP_Query($args);

	return [
		'num_pages' => $query->max_num_pages,
		'posts' => array_map('Concordamos\\prepare_voting_for_api', $query->posts),
	];
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
	// @todo: https://www.php.net/manual/en/function.random-bytes.php
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
		'post_status'  => 'publish',
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

// Vote
function vote_callback( \WP_REST_Request $request ) {

	$params = $request->get_json_params();

	$unique_id = sanitize_title( $params['u_id'] );
	$voting_id = intval( $params['v_id'] );

	$raw_post_meta = get_post_meta( $voting_id );

	$is_private = false;

	if ( $raw_post_meta['voting_type'][0] === 'private' ) {
		$is_private = true;
	}

	// Check if voting has unique ID and is private
	if ( preg_match( '/^u-[a-z0-9]+$/i', $unique_id ) && $is_private ) {
		$expired_unique_ids = array_filter( explode( ',', $raw_post_meta['expired_unique_ids'][0] ) );

		// Checks that unique ID was not used
		if ( in_array( $unique_id, $expired_unique_ids ) ) {
			$response = [
				'status'  => 'error',
				'message' => __( 'Expired link', 'concordamos' )
			];
			return new \WP_REST_Response( $response, 400 );
		}
	}

	// Generate the post_title using random_int and uniqid functions
	$prefix = 'v-' . random_int( 100, 999 );
	$post_title = uniqid( $prefix );

	$args = [
		'post_author' => get_current_user_id(),
		'post_status' => 'publish',
		'post_title'  => $post_title,
		'post_type'   => 'vote',
		'meta_input'  => [
			'logged_user'    => is_user_logged_in() ? 'yes' : 'no',
			'unique_id'      => $unique_id,
			'voting_date'    => gmdate( 'Y-m-d H:i:s' ),
			'voting_id'      => $voting_id,
			'voting_options' => $params['votes']
		],
	];

	// Create post
	$post_id = wp_insert_post( $args );

	if ( $post_id ) {
		if ( $is_private ) {
			$get_expired_unique_ids = get_post_meta( $voting_id, 'expired_unique_ids', true );
			$get_expired_unique_ids .= $unique_id . ',';
			update_post_meta( $voting_id, 'expired_unique_ids', $get_expired_unique_ids );
		}

		$response = [
			'status'  => 'success',
			'message' => __( 'Vote registered successfully', 'concordamos')
		];

		return new \WP_REST_Response( $response, 200 );
	} else {
		$response = [
			'status'  => 'error',
			'message' => __( 'Error registering your vote, please try again', 'concordamos' )
		];

		return new \WP_REST_Response( $response, 400 );
	}
}

function permission_vote_check( \WP_REST_Request $request ) {
	$nonce = isset( $_SERVER['HTTP_X_WP_NONCE'] ) ? $_SERVER['HTTP_X_WP_NONCE'] : '';

	if ( ! wp_verify_nonce( $nonce, 'wp_rest' ) ) {
		return false;
	}

	return true;
}
