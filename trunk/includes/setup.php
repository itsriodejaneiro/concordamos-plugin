<?php

namespace Concordamos;

// Custom post type `vote`
new CPT(
	'vote',
	array(
		'menu_icon' => 'dashicons-forms',
		'show_ui'   => false,
		'supports'  => array( 'author', 'title' ),
	),
	array(
		'name'           => __( 'Votes', 'concordamos' ),
		'singular_name'  => _x( 'Vote', 'noun', 'concordamos' ),
	),
);


// Custom post type `voting`
new CPT(
	'voting',
	array(
		'menu_icon' => 'dashicons-yes-alt',
		'supports'  => array( 'author', 'editor', 'title' ),
	),
	array(
		'name'           => __( 'Votings', 'concordamos' ),
		'singular_name'  => __( 'Voting', 'concordamos' ),
	),
);


// Taxonomy `tag`
new Taxonomy(
	'voting',
	'tag',
	array(
		'hierarchical'      => false,
		'public'            => true,
		'show_in_nav_menus' => true,
	)
);


// Custom post type `options`
new CPT(
	'option',
	array(
		'menu_icon' => 'dashicons-table-row-after',
		'show_ui'   => false,
		'supports'  => array( 'author', 'title' ),
	),
	array(
		'name'           => __( 'Options', 'concordamos' ),
		'singular_name'  => __( 'Option', 'concordamos' ),
	),
);


/**
 * Remove the admin_bar of the users with role `concordamos_network`
 */
function remove_admin_bar() {
	$user = wp_get_current_user();
	if ( in_array( 'concordamos_network', (array) $user->roles ) ) {
		show_admin_bar( false );
	}
}
add_action( 'after_setup_theme', 'Concordamos\remove_admin_bar' );


/**
 * Delete options when a voting is deleted
 */
function delete_options_by_voting( $post_id, $post ) {
	if ( 'voting' !== $post->post_type ) {
		return;
	}

	$args = array(
		'author'         => $post->post_author,
		'post_type'      => 'option',
		'posts_per_page' => -1,
		'meta_query'     => array(
			array(
				'key'   => 'voting_id',
				'value' => $post_id,
			),
		),
	);

	$options = new \WP_Query( $args );

	if ( $options ) {
		foreach ( $options->posts as $option ) {
			wp_delete_post( $option->ID, true );
		}
	}
}
add_action( 'after_delete_post', 'Concordamos\delete_options_by_voting', 10, 2 );
