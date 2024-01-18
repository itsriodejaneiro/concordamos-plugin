<?php

namespace Concordamos;

function register_endpoints() {
	register_rest_route(
		'concordamos/v1',
		'/voting/',
		array(
			'methods'             => 'POST',
			'callback'            => 'Concordamos\\create_voting_callback',
			'permission_callback' => 'Concordamos\\permission_check',
		)
	);

	register_rest_route(
		'concordamos/v1',
		'/voting/',
		array(
			'methods'             => 'PATCH',
			'callback'            => 'Concordamos\\patch_voting_callback',
			'permission_callback' => 'Concordamos\\permission_check',
		)
	);

	register_rest_route(
		'concordamos/v1',
		'/vote/',
		array(
			'methods'             => 'POST',
			'callback'            => 'Concordamos\\vote_callback',
			'permission_callback' => 'Concordamos\\permission_vote_check',
		)
	);

	register_rest_route(
		'concordamos/v1',
		'/votes/',
		array(
			'methods'             => 'GET',
			'callback'            => 'Concordamos\\get_votes_callback',
			'permission_callback' => 'Concordamos\\permission_vote_check',
		)
	);

	register_rest_route(
		'concordamos/v1',
		'/my-account/',
		array(
			'methods'             => 'GET',
			'callback'            => 'Concordamos\\get_my_account_callback',
			'permission_callback' => 'Concordamos\\permission_check',
		)
	);

	register_rest_route(
		'concordamos/v1',
		'/my-account/',
		array(
			'methods'             => 'PATCH',
			'callback'            => 'Concordamos\\patch_my_account_callback',
			'permission_callback' => 'Concordamos\\permission_check',
		)
	);

	register_rest_route(
		'concordamos/v1',
		'/my-account/',
		array(
			'methods'             => 'DELETE',
			'callback'            => 'Concordamos\\delete_my_account_callback',
			'permission_callback' => 'Concordamos\\permission_check',
		)
	);

	register_rest_route(
		'concordamos/v1',
		'/my-vote/',
		array(
			'methods'             => 'GET',
			'callback'            => 'Concordamos\\get_my_vote_callback',
			'permission_callback' => 'Concordamos\\permission_check',
		)
	);

	register_rest_route(
		'concordamos/v1',
		'/my-votings/',
		array(
			'methods'             => 'GET',
			'callback'            => 'Concordamos\\list_my_votings_callback',
			'permission_callback' => 'Concordamos\\permission_check',
		)
	);

	register_rest_route(
		'concordamos/v1',
		'/voting-links/',
		array(
			'methods'             => 'GET',
			'callback'            => 'Concordamos\\get_voting_links_callback',
			'permission_callback' => 'Concordamos\\permission_check',
		)
	);

	register_rest_route(
		'concordamos/v1',
		'/participated-votings/',
		array(
			'methods'             => 'GET',
			'callback'            => 'Concordamos\\list_participated_votings_callback',
			'permission_callback' => 'Concordamos\\permission_check',
		)
	);

	register_rest_route(
		'concordamos/v1',
		'/votings/',
		array(
			'methods'             => 'GET',
			'callback'            => 'Concordamos\\search_votings_callback',
			'permission_callback' => '__return_true',
		)
	);

	register_rest_route(
		'concordamos/v1',
		'/logout/',
		array(
			'methods'             => 'POST',
			'callback'            => 'Concordamos\\logout_callback',
			'permission_callback' => 'Concordamos\\permission_check',
		)
	);
}
add_action( 'rest_api_init', 'Concordamos\\register_endpoints' );

function permission_check( \WP_REST_Request $request ) {
	if ( ! is_user_logged_in() ) {
		return new \WP_Error( 'rest_forbidden', __( 'You are not signed in.', 'concordamos' ), array( 'status' => 401 ) );
	}

	$a_id = $request->get_param( 'a_id' );

	if ( $a_id ) {
		return true;
	}

	$user = wp_get_current_user();

	if ( ! in_array( 'concordamos_network', $user->roles, true ) && ! in_array( 'administrator', $user->roles, true ) ) {
		return new \WP_Error( 'rest_forbidden', __( "You don't have enough permissions.", 'concordamos' ), array( 'status' => 403 ) );
	}

	if ( $request->get_method() !== 'GET' ) {
		$params = $request->get_json_params();

		if ( intval( $params['user_id'] ) !== $user->ID ) {
			return new \WP_Error( 'rest_forbidden', __( "You don't have enough permissions.", 'concordamos' ), array( 'status' => 403 ) );
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

function build_query_from_params( array $query, array $params ) {
	$meta_query = empty( $query['meta_query'] ) ? array() : $query['meta_query'];

	if ( ! empty( $params['query'] ) ) {
		$query['s'] = $params['query'];
	}

	if ( ! empty( $params['type'] ) ) {
		$meta_query[] = array(
			'key'   => 'voting_type',
			'value' => $params['type'],
		);
	}

	if ( ! empty( $params['access'] ) ) {
		$meta_query[] = array(
			'key'   => 'voting_access',
			'value' => $params['access'],
		);
	}

	if ( ! empty( $params['time'] ) ) {
		$now = 1000 * time();

		if ( $params['time'] === 'present' ) {
			$meta_query[] = array(
				array(
					'key'     => 'date_end',
					'compare' => '>',
					'value'   => $now,
				),
				array(
					'key'     => 'date_start',
					'compare' => '<',
					'value'   => $now,
				),
				'relation' => 'AND',
			);
		} elseif ( $params['time'] === 'past' ) {
			$meta_query[] = array(
				'key'     => 'date_end',
				'compare' => '<',
				'value'   => $now,
			);
		} elseif ( $params['time'] === 'future' ) {
			$meta_query[] = array(
				'key'     => 'date_start',
				'compare' => '>',
				'value'   => $now,
			);
		}
	}

	if ( ! empty( $meta_query ) ) {
		$query['meta_query'] = $meta_query;
	}

	return $query;
}

function list_my_votings_callback( \WP_REST_Request $request ) {
	$params = $request->get_params();

	$current_page = empty( $params['page'] ) ? 1 : intval( $params['page'] );

	$args = array(
		'post_type'      => 'voting',
		'post_status'    => 'publish',
		'author'         => get_current_user_id(),
		'posts_per_page' => 6,
		'paged'          => $current_page,
	);

	$args = build_query_from_params( $args, $params );

	$query = new \WP_Query( $args );

	return array(
		'num_pages' => $query->max_num_pages,
		'posts'     => array_map( 'Concordamos\\prepare_voting_for_api', $query->posts ),
	);
}

function list_participated_votings_callback( \WP_REST_Request $request ) {
	$params       = $request->get_params();
	$current_page = empty( $params['page'] ) ? 1 : intval( $params['page'] );

	$args = array(
		'author'         => get_current_user_id(),
		'fields'         => 'ids',
		'paged'          => $current_page,
		'post_status'    => 'publish',
		'post_type'      => 'vote',
		'posts_per_page' => 6,
	);

	$get_votes       = get_posts( $args );
	$get_votings_ids = array();

	foreach ( $get_votes as $vote_id ) {
		$voting_id = get_post_meta( $vote_id, 'voting_id', true );
		if ( $voting_id ) {
			$voting = get_post( $voting_id );
			if ( $voting ) {
				$get_votings_ids[] = $voting->ID;
			}
		}
	}

	$args_voting = array(
		'post__in'       => $get_votings_ids,
		'post_type'      => 'voting',
		'posts_per_page' => -1,
	);

	$args_voting = build_query_from_params( $args_voting, $params );

	$query = new \WP_Query( $args_voting );

	$$prepare_voting = function ( $voting ) {
		return prepare_voting_for_api( $voting );
	};

	return array(
		'num_pages' => $query->max_num_pages,
		'posts'     => array_map( $$prepare_voting, $query->posts ),
	);
}

function search_votings_callback( \WP_REST_Request $request ) {
	$params = $request->get_params();

	$current_page   = empty( $params['page'] ) ? 1 : intval( $params['page'] );
	$posts_per_page = empty( $params['per_page'] ) ? 6 : intval( $params['per_page'] );

	$args = array(
		'post_type'      => 'voting',
		'post_status'    => 'publish',
		'posts_per_page' => $posts_per_page,
		'paged'          => $current_page,
	);

	$args = build_query_from_params( $args, $params );

	$query = new \WP_Query( $args );

	return array(
		'num_pages' => $query->max_num_pages,
		'posts'     => array_map( 'Concordamos\\prepare_voting_for_api', $query->posts ),
	);
}

function create_voting_callback( \WP_REST_Request $request ) {

	// Check if user has permission
	permission_check( $request );

	$params = $request->get_json_params();

	$required_params = array(
		'credits_voter',
		'date_end',
		'date_start',
		'number_voters',
		'tags',
		'user_id',
		'voting_description',
		'voting_name',
		'voting_options',
		'voting_type',
	);

	// Check required params
	foreach ( $required_params as $param ) {
		if ( ! isset( $params[ $param ] ) || empty( $params[ $param ] ) ) {
			$response = array(
				'status'  => 'error',
				'message' => __( 'Required field is either missing or blank:', 'concordamos' ) . ' ' . $param,
			);
			return new \WP_REST_Response( $response, 400 );
		}
	}

	$number_voters = intval( $params['number_voters'] );
	$voting_type   = sanitize_text_field( $params['voting_type'] );

	$unique_ids = '';

	// Generate all unique links to voting if is private when config use_unique_links is activated
	if ( $voting_type === 'private' && use_unique_links() ) {
		for ( $i = 0; $i < $number_voters; $i++ ) {
			$prefix      = 'u-' . bin2hex( random_bytes( 6 ) );
			$unique_ids .= $prefix . ',';
		}
	}

	// Generate the post_name using random_int and uniqid functions
	$prefix    = 'v-' . random_int( 100, 999 );
	$post_name = uniqid( $prefix );

	// Generate admin id to use as permalink
	$admin_id = 'a-' . bin2hex( random_bytes( 6 ) );

	$args = array(
		'post_author'  => get_current_user_id(), // @todo: add option to vote without login
		'post_content' => wp_kses_post( $params['voting_description'] ),
		'post_name'    => $post_name,
		'post_status'  => 'publish',
		'post_title'   => sanitize_text_field( $params['voting_name'] ),
		'post_type'    => 'voting',
		'tax_input'    => array(
			'tag' => sanitize_text_field( $params['tags'] ),
		),
		'meta_input'   => array(
			'admin_id'       => $admin_id,
			'credits_voter'  => intval( $params['credits_voter'] ),
			'date_end'       => intval( $params['date_end'] ),
			'date_start'     => intval( $params['date_start'] ),
			'description'    => wp_kses_post( $params['voting_description'] ),
			'negative_votes' => sanitize_text_field( $params['negative_votes'] ),
			'number_voters'  => $number_voters,
			'results_end'    => sanitize_text_field( $params['results_end'] ),
			'unique_ids'     => $unique_ids,
			'voting_access'  => sanitize_text_field( $params['voting_access'] ),
			'voting_name'    => sanitize_text_field( $params['voting_name'] ),
			'voting_type'    => $voting_type,
		),
	);

	// Create post
	$post_id = wp_insert_post( $args );

	if ( $post_id ) {

		$voting_options = $params['voting_options'];

		foreach ( $voting_options as $item ) {
			$option_name        = sanitize_text_field( $item['option_name'] );
			$option_description = wp_kses_post( $item['option_description'] );
			$option_link        = esc_url( $item['option_link'] );

			$options_args = array(
				'post_type'   => 'option',
				'post_title'  => $option_name,
				'post_status' => 'publish',
				'post_author' => get_current_user_id(),
				'meta_input'  => array(
					'option_name'        => $option_name,
					'option_description' => $option_description,
					'option_link'        => $option_link,
					'voting_id'          => $post_id,
				),
			);

			// Create option
			$option_id = wp_insert_post( $options_args );
		}

		$response = array(
			'status'   => 'success',
			'message'  => __( 'Voting created successfully!', 'concordamos' ),
			'post_url' => get_permalink( $post_id ),
		);
		return new \WP_REST_Response( $response, 200 );

	} else {
		$response = array(
			'status'  => 'error',
			'message' => __( 'Verify all fields and try again', 'concordamos' ),
		);
		return new \WP_REST_Response( $response, 400 );
	}
}

function patch_voting_callback( \WP_REST_Request $request ) {
	$params = $request->get_json_params();

	if ( get_post_field( 'post_author', $params['v_id'] ) !== $params['user_id'] ) {
		$response = array(
			'status'  => 'error',
			'message' => __( "You don't have enough permissions.", 'concordamos' ),
		);
		return new \WP_REST_Response( $response, 403 );
	}

	$args = array(
		'ID'         => intval( $params['v_id'] ),
		'meta_input' => array(
			'date_end'   => intval( $params['date_end'] ),
			'date_start' => intval( $params['date_start'] ),
		),
	);

	$post_id = wp_update_post( $args );

	if ( $post_id ) {
		$response = array(
			'status'  => 'success',
			'message' => __( 'Voting updated successfully!', 'concordamos' ),
		);
		return new \WP_REST_Response( $response, 200 );
	} else {
		$response = array(
			'status'  => 'error',
			'message' => __( 'Error updating voting, please try again', 'concordamos' ),
		);
		return new \WP_REST_Response( $response, 400 );
	}
}

function get_voting_links_callback( \WP_REST_Request $request ) {
	$params          = $request->get_params();
	$voting_id       = intval( $params['v_id'] );
	$voting_admin_id = get_post_meta( $voting_id, 'admin_id', true );

	$has_access = false;

	/**
	 * Check the `a_id` parameter passed in the URL with the post's `admin_id` metadata
	 */
	if ( isset( $params['a_id'] ) && ! empty( $params['a_id'] ) ) {
		$admin_id = sanitize_title( $params['a_id'] );

		if ( $admin_id == $voting_admin_id ) {
			$has_access = true;
		}
	}

	/**
	 * Check if the current user is the `post_author`
	 */
	if ( get_post_field( 'post_author', $voting_id ) == get_current_user_id() ) {
		$has_access = true;
	}

	/**
	 * Case user not has access, return error on API
	 */
	if ( ! $has_access ) {
		$response = array(
			'status'  => 'error',
			'message' => __( "You don't have enough permissions.", 'concordamos' ),
		);
		return new \WP_REST_Response( $response, 403 );
	}

	$all_uids = get_post_meta( $voting_id, 'unique_ids', true );
	if ( empty( $all_uids ) ) {
		$all_uids = array();
	} else {
		$all_uids = explode( ',', $all_uids );
	}

	$expired_uids = get_post_meta( $voting_id, 'expired_unique_ids', true );
	if ( empty( $expired_uids ) ) {
		$expired_uids = array();
	} else {
		$expired_uids = explode( ',', $expired_uids );
	}

	$valid_uids = array();
	foreach ( $all_uids as $uid ) {
		if ( ! empty( $uid ) && ! in_array( $uid, $expired_uids, true ) ) {
			$valid_uids[] = $uid;
		}
	}

	return array(
		'a_id'      => $voting_admin_id,
		'ID'        => $voting_id,
		'permalink' => get_permalink( $voting_id ),
		'slug'      => get_post_field( 'post_name', $voting_id ),
		'status'    => get_post_meta( $voting_id, 'voting_type', true ),
		'uids'      => $valid_uids,
	);
}

function get_my_account_callback( \WP_REST_Request $request ) {
	$user = get_user_by( 'ID', get_current_user_id() );

	return array(
		'ID'    => $user->ID,
		'email' => $user->user_email,
		'name'  => $user->display_name,
		'roles' => $user->roles,
	);

	return $user;
}

function patch_my_account_callback( \WP_REST_Request $request ) {
	$params = $request->get_params();

	$args = array(
		'ID' => $params['user_id'],
	);

	if ( ! empty( $params['name'] ) ) {
		$args['display_name'] = $params['name'];
	}

	if ( ! empty( $params['email'] ) ) {
		$args['user_email'] = $params['email'];
	}

	if ( ! empty( $params['password'] ) ) {
		$args['user_pass'] = $params['password'];
	}

	$user_id = wp_update_user( $args );

	if ( $user_id ) {
		$response = array(
			'status'  => 'success',
			'message' => __( 'User updated successfully!', 'concordamos' ),
		);
		return new \WP_REST_Response( $response, 200 );
	} else {
		$response = array(
			'status'  => 'error',
			'message' => __( 'Error updating user, please try again', 'concordamos' ),
		);
		return new \WP_REST_Response( $response, 400 );
	}
}

function delete_my_account_callback( \WP_REST_Request $request ) {
	// Required for using `wp_delete_user` function
	require_once ABSPATH . 'wp-admin/includes/user.php';

	$user_id = get_current_user_id();

	$args = array(
		'author'      => $user_id,
		'numberposts' => -1,
		'post_type'   => array( 'vote', 'voting' ),
	);

	$posts = get_posts( $args );

	foreach ( $posts as $post ) {
		$args = array(
			'ID'          => $post->ID,
			'post_author' => 0,
			'meta_input'  => array(
				'_deleted_user' => 1,
			),
		);

		wp_update_post( $args );
	}

	wp_logout();

	wp_delete_user( $user_id, 0 );

	$response = array(
		'status'  => 'success',
		'message' => __( 'Account deleted successfully' ),
	);

	return new \WP_REST_Response( $response, 200 );
}

function get_my_vote_callback( \WP_REST_Request $request ) {
	$params = $request->get_params();

	if ( empty( $params['v_id'] ) ) {
		$response = array(
			'status'  => 'error',
			'message' => __( 'Required field is either missing or blank:', 'concordamos' ) . ' v_id',
		);
		return new \WP_REST_Response( $response, 400 );
	}

	$args = array(
		'post_type'   => 'vote',
		'post_status' => 'publish',
		'author'      => get_current_user_id(),
		'meta_query'  => array(
			array(
				'key'   => 'voting_id',
				'value' => $params['v_id'],
			),
		),
	);

	$query = new \WP_Query( $args );

	if ( $query->have_posts() ) {
		$votes = get_post_meta( $query->post->ID, 'voting_options', true );
		return maybe_unserialize( $votes );
	} else {
		$response = array(
			'status'  => 'error',
			'message' => __( 'Vote not found', 'concordamos' ),
		);
		return new \WP_REST_Response( $response, 404 );
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

	if ( $is_private && use_unique_links() ) {
		if ( empty( $unique_id ) ) {
			$response = array(
				'status'  => 'error',
				'message' => __( 'Invalid link', 'concordamos' ),
			);
			return new \WP_REST_Response( $response, 401 );
		} elseif ( preg_match( '/^u-[A-Za-z0-9]+$/i', $unique_id ) ) {
			$expired_unique_ids = array_filter( explode( ',', $raw_post_meta['expired_unique_ids'][0] ) );

			// Checks that unique ID was not used
			if ( in_array( $unique_id, $expired_unique_ids, true ) ) {
				$response = array(
					'status'  => 'error',
					'message' => __( 'Expired link', 'concordamos' ),
				);
				return new \WP_REST_Response( $response, 403 );
			}
		}
	}

	if ( is_voting_closed( $voting_id ) ) {
		$response = array(
			'status'  => 'error',
			'message' => __( 'Voting phase is already closed', 'concordamos' ),
		);
		return new \WP_REST_Response( $response, 400 );
	}

	// Generate the post_title using random_int and uniqid functions
	$prefix     = 'v-' . random_int( 100, 999 );
	$post_title = uniqid( $prefix );

	$args = array(
		'post_author' => get_current_user_id(),
		'post_status' => 'publish',
		'post_title'  => $post_title,
		'post_type'   => 'vote',
		'meta_input'  => array(
			'logged_user'    => is_user_logged_in() ? 'yes' : 'no',
			'unique_id'      => $unique_id,
			'voting_date'    => gmdate( 'Y-m-d H:i:s' ),
			'voting_id'      => $voting_id,
			'voting_options' => $params['votes'],
		),
	);

	// Create post
	$post_id = wp_insert_post( $args );

	if ( $post_id ) {
		if ( $is_private ) {
			$get_expired_unique_ids  = get_post_meta( $voting_id, 'expired_unique_ids', true );
			$get_expired_unique_ids .= $unique_id . ',';
			update_post_meta( $voting_id, 'expired_unique_ids', $get_expired_unique_ids );
		}

		$response = array(
			'status'  => 'success',
			'message' => __( 'Vote registered successfully!', 'concordamos' ),
		);

		return new \WP_REST_Response( $response, 200 );
	} else {
		$response = array(
			'status'  => 'error',
			'message' => __( 'Error registering your vote, please try again', 'concordamos' ),
		);

		return new \WP_REST_Response( $response, 400 );
	}
}

// Get votes
function get_votes_callback( \WP_Rest_Request $request ) {
	$params        = $request->get_params();
	$voting_id     = intval( $params['v_id'] );
	$number_voters = get_post_meta( $voting_id, 'number_voters', true );

	$data_graphic = array(
		'labels'             => array(),
		'dataset'            => array(),
		'dataset_percentage' => array(),
	);

	$args = array(
		'post_type'   => 'vote',
		'post_status' => 'publish',
		'meta_query'  => array(
			array(
				'key'   => 'voting_id',
				'value' => $voting_id,
			),
		),
	);

	$votes        = new \WP_Query( $args );
	$used_credits = 0;

	if ( $votes->have_posts() ) {
		while ( $votes->have_posts() ) {
			$votes->the_post();

			$voting_options = get_post_meta( get_the_ID(), 'voting_options', true );

			if ( is_array( $voting_options ) ) {
				foreach ( $voting_options as $option ) {
					$label = get_post_meta( $option['id'], 'option_name', true );
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

					$index = array_search( $label, $data_graphic['labels'], true );

					if ( $index !== false ) {
						$data_graphic['dataset'][ $index ] += $value;
						$data_graphic['votes'][ $index ]   += 1;
					} else {
						$data_graphic['labels'][]  = $label;
						$data_graphic['dataset'][] = $value;
						$data_graphic['votes'][]   = 1;
					}
				}
			}
		}
	}

	$dataset_percentage = array();

	foreach ( $data_graphic['dataset'] as $dataset ) {
		$dataset_percentage[] = round( ( ( abs( $dataset ) / array_sum( $data_graphic['dataset'] ) ) * 100 ), 2 );
	}

	wp_reset_postdata();

	return array(
		'dataset'            => $data_graphic['dataset'],
		'dataset_percentage' => $dataset_percentage,
		'labels'             => $data_graphic['labels'],
		'number_voters'      => $number_voters,
		'participants'       => $votes->found_posts,
		'total_credits'      => get_post_meta( $voting_id, 'credits_voter', true ) * $votes->found_posts,
		'used_credits'       => $used_credits,
	);
}

function logout_callback( \WP_REST_Request $request ) {
	wp_logout();

	return array(
		'status'  => 'success',
		'message' => __( 'User signed out', 'concordamos' ),
	);
}
