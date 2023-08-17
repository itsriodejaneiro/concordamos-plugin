<?php

namespace Concordamos;

function register_endpoints() {
	register_rest_route( 'concordamos/v1', '/voting/', [
		'methods'             => 'POST',
		'callback'            => 'Concordamos\\create_voting_callback',
		'permission_callback' => 'Concordamos\\permission_check'
	] );

	register_rest_route( 'concordamos/v1', '/voting/', [
		'methods'             => 'PATCH',
		'callback'            => 'Concordamos\\patch_voting_callback',
		'permission_callback' => 'Concordamos\\permission_check',
	]);

	register_rest_route( 'concordamos/v1', '/vote/', [
		'methods'             => 'POST',
		'callback'            => 'Concordamos\\vote_callback',
		'permission_callback' => 'Concordamos\\permission_vote_check'
	] );

	register_rest_route( 'concordamos/v1', '/votes/', [
		'methods'             => 'GET',
		'callback'            => 'Concordamos\\get_votes_callback',
		'permission_callback' => 'Concordamos\\permission_vote_check'
	]);

	register_rest_route( 'concordamos/v1', '/my-account/', [
		'methods'             => 'GET',
		'callback'            => 'Concordamos\\get_my_account_callback',
		'permission_callback' => 'Concordamos\\permission_check'
	] );

	register_rest_route( 'concordamos/v1', '/voting-links/', [
		'methods'             => 'GET',
		'callback'            => 'Concordamos\\get_voting_links_callback',
		'permission_callback' => 'Concordamos\\permission_check'
	] );

	register_rest_route( 'concordamos/v1', '/my-account/', [
		'methods'             => 'PATCH',
		'callback'            => 'Concordamos\\patch_my_account_callback',
		'permission_callback' => 'Concordamos\\permission_check'
	] );

	register_rest_route( 'concordamos/v1', '/my-vote/', [
		'methods'             => 'GET',
		'callback'            => 'Concordamos\\get_my_vote_callback',
		'permission_callback' => 'Concordamos\\permission_check',
	] );

	register_rest_route( 'concordamos/v1', '/my-votings/', [
		'methods'             => 'GET',
		'callback'            => 'Concordamos\\list_my_votings_callback',
		'permission_callback' => 'Concordamos\\permission_check',
	]);

	register_rest_route( 'concordamos/v1', '/participated-votings/', [
		'methods'             => 'GET',
		'callback'            => 'Concordamos\\list_participated_votings_callback',
		'permission_callback' => 'Concordamos\\permission_check',
	]);

	register_rest_route( 'concordamos/v1', '/votings/', [
		'methods'             => 'GET',
		'callback'            => 'Concordamos\\search_votings_callback',
		'permission_callback' => '__return_true',
	]);

	register_rest_route( 'concordamos/v1', '/logout/', [
		'methods'             => 'POST',
		'callback'            => 'Concordamos\\logout_callback',
		'permission_callback' => 'Concordamos\\permission_check',
	]);
}
add_action( 'rest_api_init', 'Concordamos\\register_endpoints' );

function permission_check( \WP_REST_Request $request ) {
	if ( ! is_user_logged_in() ) {
		return new \WP_Error( 'rest_forbidden', __('You are not signed in.', 'concordamos'), array( 'status' => 401 ) );
	}

	$user = wp_get_current_user();

	if ( ! in_array( 'concordamos_network', $user->roles ) && ! in_array( 'administrator', $user->roles ) ) {
		return new \WP_Error( 'rest_forbidden', __("You don't have enough permissions.", 'concordamos'), array( 'status' => 403 ) );
	}

	if ( $request->get_method() !== 'GET' ) {
		$params = $request->get_json_params();

		if ( $params['user_id'] != $user->ID ) {
			return new \WP_Error( 'rest_forbidden', __("You don't have enough permissions.", 'concordamos'), array( 'status' => 403 ) );
		}
	}

	return true;
}

function permission_vote_check( \WP_REST_Request $request ) {
	$nonce = isset( $_SERVER['HTTP_X_WP_NONCE'] ) ? $_SERVER['HTTP_X_WP_NONCE'] : '';

	if ( ! wp_verify_nonce( $nonce, 'wp_rest' ) ) {
		return false;
	}

	return true;
}

function build_query_from_params (array $query, array $params) {
	$meta_query = empty($query['meta_query']) ? [] : $query['meta_query'];

	if (!empty($params['query'])) {
		$query['s'] = $params['query'];
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
		$query['meta_query'] = $meta_query;
	}

	return $query;
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

	$args = build_query_from_params($args, $params);

	$query = new \WP_Query($args);

	return [
		'num_pages' => $query->max_num_pages,
		'posts' => array_map('Concordamos\\prepare_voting_for_api', $query->posts),
	];
}

function list_participated_votings_callback ( \WP_REST_Request $request ) {
	$params = $request->get_params();

	$currentPage = empty($params['page']) ? 1 : intval($params['page']);

	$args = [
		'post_type' => 'vote',
		'post_status' => 'publish',
		'post_author' => get_current_user_id(),
		'posts_per_page' => 6,
		'paged' => $currentPage,
	];

	$args = build_query_from_params($args, $params);

	$query = new \WP_Query($args);

	$prepareVoting = function ($vote) {
		$votingId = get_post_meta($vote->ID, 'voting_id', true);
		$voting = get_post($votingId);
		return prepare_voting_for_api($voting);
	};

	return [
		'num_pages' => $query->max_num_pages,
		'posts' => array_map($prepareVoting, $query->posts),
	];
}

function search_votings_callback ( \WP_REST_Request $request ) {
	$params = $request->get_params();

	$currentPage = empty($params['page']) ? 1 : intval($params['page']);
	$postsPerPage = empty($params['per_page']) ? 6 : intval($params['per_page']);

	$args = [
		'post_type' => 'voting',
		'post_status' => 'publish',
		'posts_per_page' => $postsPerPage,
		'paged' => $currentPage,
	];

	$args = build_query_from_params($args, $params);

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
		'credits_voter',
		'number_voters',
		'tags',
		'user_id',
		'voting_description',
		'voting_name',
		'voting_options',
		'date_start',
		'date_end',
		'voting_type'
	];

	// Check required params
	foreach ( $required_params as $param ) {
		if ( ! isset( $params[$param] ) || empty( $params[$param] ) ) {
			$response = [
				'status' => 'error',
				'message' => __('Required field is either missing or blank:', 'concordamos') . ' ' . $param
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
			$prefix = 'u-' . bin2hex( random_bytes( 6 ) );
			$unique_ids .= $prefix . ',';
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
			'message' => __('Voting created successfully!', 'concordamos'),
			'post_url' => get_permalink( $post_id ),
		];
		return new \WP_REST_Response( $response, 200 );

	} else {
		$response = [
			'status'  => 'error',
			'message' => __('Verify all fields and try again'. 'concordamos'),
		];
		return new \WP_REST_Response( $response, 400 );
	}
}

function patch_voting_callback ( \WP_REST_Request $request ) {
	$params = $request->get_json_params();

	if ( get_post_field( 'post_author', $params['v_id'] ) != $params['user_id'] ) {
		$response = [
			'status' => 'error',
			'message' => __( "You don't have enough permissions.", 'concordamos' ),
		];
		return new \WP_REST_Response( $response, 403 );
	}

	$args = [
		'ID' => intval( $params['v_id'] ),
		'meta_input' => [
			'date_end'      => intval( $params['date_end'] ),
			'date_start'    => intval( $params['date_start'] ),
		]
	];

	$postId = wp_update_post( $args );

	if ( $postId ) {
		$response = [
			'status' => 'success',
			'message' => __( 'Voting updated successfully!', 'concordamos' ),
		];
		return new \WP_REST_Response( $response, 200 );
	} else {
		$response = [
			'status' => 'error',
			'message' => __( 'Error updating voting, please try again', 'concordamos' ),
		];
		return new \WP_REST_Response( $response, 400 );
	}
}

function get_voting_links_callback ( \WP_REST_Request $request ) {
	$params = $request->get_params();
	$votingId = intval( $params['v_id'] );

	if ( get_post_field( 'post_author', $votingId ) != get_current_user_id() ) {
		$response = [
			'status' => 'error',
			'message' => __( "You don't have enough permissions.", 'concordamos' ),
		];
		return new \WP_REST_Response( $response, 403 );
	}

	$all_uids = get_post_meta( $votingId, 'unique_ids', true );
	if ( empty( $all_uids ) ) {
		$all_uids = [];
	} else {
		$all_uids = explode( ',', $all_uids );
	}

	$expired_uids = get_post_meta( $votingId, 'expired_unique_ids', true );
	if ( empty( $expired_uids ) ) {
		$expired_uids = [];
	} else {
		$expired_uids = explode( ',', $expired_uids );
	}

	$valid_uids = [];
	foreach ( $all_uids as $uid ) {
		if ( !empty( $uid ) && !in_array( $uid, $expired_uids ) ) {
			$valid_uids[] = $uid;
		}
	}

	return [
		'ID' => $votingId,
		'slug' => get_post_field( 'post_name', $votingId ),
		'permalink' => get_permalink( $votingId ),
		'status' => get_post_meta( $votingId, 'voting_type', true ),
		'uids' => $valid_uids,
	];
}

function get_my_account_callback ( \WP_REST_Request $request ) {
	$user = get_user_by('ID', get_current_user_id());

	return [
		'ID' => $user->ID,
		'email' => $user->user_email,
		'name' => $user->display_name,
		'roles' => $user->roles,
	];

	return $user;
}

function patch_my_account_callback ( \WP_REST_Request $request ) {
	$params = $request->get_params();

	$args = [
		'ID' => $params['user_id'],
	];

	if (!empty($params['name'])) {
		$args['display_name'] = $params['name'];
	}

	if (!empty($params['email'])) {
		$args['user_email'] = $params['email'];
	}

	if (!empty($params['password'])) {
		$args['user_pass'] = $params['password'];
	}

	$userId = wp_update_user($args);

	if ($userId) {
		$response = [
			'status' => 'success',
			'message' => __('User updated successfully!', 'concordamos'),
		];
		return new \WP_REST_Response($response, 200);
	} else {
		$response = [
			'status' => 'error',
			'message' => __('Error updating user, please try again', 'concordamos'),
		];
		return new \WP_REST_Response($response, 400);
	}
}

function get_my_vote_callback ( \WP_REST_Request $request ) {
	$params = $request->get_params();

	if (empty($params['v_id'])) {
		$response = [
			'status' => 'error',
			'message' => __('Required field is either missing or blank:', 'concordamos') . ' v_id',
		];
		return new \WP_REST_Response($response, 400);
	}

	$args = [
		'post_type' => 'vote',
		'post_status' => 'publish',
		'post_author' => get_current_user_id(),
		'meta_query' => [
			[ 'key' => 'voting_id', 'value' => $params['v_id'] ],
		],
	];

	$query = new \WP_Query($args);

	if ($query->have_posts()) {
		$votes = get_post_meta($query->post->ID, 'voting_options', true);
		return maybe_unserialize($votes);
	} else {
		$response = [
			'status' => 'error',
			'message' => __('Vote not found', 'concordamos'),
		];
		return new \WP_REST_Response($response, 404);
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

	if ( $is_private ) {
		if ( empty( $unique_id ) ) {
			$response = [
				'status' => 'error',
				'message' => __( 'Invalid link', 'concordamos' ),
			];
			return new \WP_REST_Response( $response, 401 );
		} elseif ( preg_match( '/^u-[A-Za-z0-9]+$/i', $unique_id ) ) {
			$expired_unique_ids = array_filter( explode( ',', $raw_post_meta['expired_unique_ids'][0] ) );

			// Checks that unique ID was not used
			if ( in_array( $unique_id, $expired_unique_ids ) ) {
				$response = [
					'status'  => 'error',
					'message' => __( 'Expired link', 'concordamos' )
				];
				return new \WP_REST_Response( $response, 403 );
			}
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
			'message' => __( 'Vote registered successfully!', 'concordamos')
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

// Get votes
function get_votes_callback( \WP_Rest_Request $request ) {
	$params = $request->get_params();
	$voting_id = intval( $params['v_id'] );
	$number_voters = get_post_meta( $voting_id, 'number_voters', true );

	$data_graphic = [
		'labels' => [],
		'dataset' => [],
		'dataset_percentage' => [],
	];

	$args = [
		'post_type'   => 'vote',
		'post_status' => 'publish',
		'meta_query'  => [
			[
				'key' => 'voting_id',
				'value' => $voting_id
			],
		]
	];

	$votes = new \WP_Query( $args );
	$used_credits = 0;

	if ( $votes->have_posts() ) {
		while ( $votes->have_posts() ) {
			$votes->the_post();

			$voting_options = get_post_meta( get_the_ID(), 'voting_options', true );

			if ( is_array( $voting_options ) ) {
				foreach ( $voting_options as $option ) {
					$label = get_post_meta( $option['id'], 'option_name', true ); ;
					$value = (int) $option['count'];

					if ( $value == 1 ) {
						$used_credits += $value;
					} elseif ( $value > 1 ) {
						$used_credits += $value ** 2;
					} elseif ( $value == -1 ) {
						$used_credits += 1;
					} elseif ( $value < -1 ) {
						$used_credits += abs( $value ) ** 2;
					}

					$index = array_search( $label, $data_graphic['labels'] );

					if ( $index !== false ) {
						$data_graphic['dataset'][$index] += $value;
						$data_graphic['votes'][$index] += 1;
					} else {
						$data_graphic['labels'][] = $label;
						$data_graphic['dataset'][] = $value;
						$data_graphic['votes'][] = 1;
					}

				}
			}
		}
	}

	$dataset_percentage = [];

	foreach ( $data_graphic['dataset'] as $dataset ) {
		$dataset_percentage[] = round( ( ( abs( $dataset ) / $used_credits ) * 100), 2 );
	}

	wp_reset_postdata();

	return [
		'dataset'            => $data_graphic['dataset'],
		'dataset_percentage' => $dataset_percentage,
		'labels'             => $data_graphic['labels'],
		'number_voters'      => $number_voters,
		'participants'       => $votes->found_posts,
		'total_credits'      => get_post_meta( $voting_id, 'credits_voter', true ) * $votes->found_posts,
		'used_credits'       => $used_credits
	];

}

function logout_callback ( \WP_REST_Request $request ) {
	wp_logout();

	return [
		'status' => 'success',
		'message' => __( 'User signed out', 'concordamos' ),
	];
}
