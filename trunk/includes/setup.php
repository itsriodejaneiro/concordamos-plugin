<?php

namespace Concordamos;

// Custom post type `vote`
new CPT(
	'vote',
	array(
		'menu_icon' => 'dashicons-forms',
		'show_ui'   => false,
		'supports'  => array( 'author', 'title' ),
	)
);

// new Metadata( 'vote', 'info', __( 'Additional information', 'concordamos' ), [
// [
// 'id'    => 'logged_user',
// 'label' => __( 'User logged?', 'concordamos' ),
// 'type'  => 'radio',
// 'css'   => 'inline',
// 'options' => [
// 'yes' => 'Sim',
// 'no'  => 'Não'
// ]
// ],
// [
// 'id'          => 'unique_id',
// 'label'       => __( 'Unique ID', 'concordamos' ),
// 'placeholder' => __( 'Unique ID used to vote on private voting', 'concordamos' ),
// ],
// [
// 'id'    => 'voting_date',
// 'label' => __( 'Date voting', 'concordamos' ),
// 'type'  => 'date'
// ],
// [
// 'id'          => 'voting_id',
// 'label'       => __( 'Voting ID', 'concordamos' ),
// 'placeholder' => __( 'Voting ID relationship', 'concordamos' ),
// ],
// [
// 'id'          => 'voting_options',
// 'label'       => __( 'Options and credits', 'concordamos' ),
// 'placeholder' => __( 'Options and credits of the vote', 'concordamos' ),
// ]
// ] );


// Custom post type `voting`
new CPT(
	'voting',
	array(
		'menu_icon' => 'dashicons-yes-alt',
		'show_ui'   => false,
		'supports'  => array( 'author', 'editor', 'title' ),
	)
);

// new Metadata( 'voting', 'info', __( 'Additional information', 'concordamos' ), [
// [
// 'id'    => 'voting_type',
// 'label' => __( 'Voting type', 'concordamos' ),
// 'type'  => 'radio',
// 'css'   => 'inline',
// 'options' => [
// 'public' => 'Publico',
// 'private' => 'Privada'
// ]
// ],
// [
// 'id'    => 'voting_access',
// 'label' => __( 'Require login?', 'concordamos' ),
// 'type'  => 'radio',
// 'css'   => 'inline',
// 'options' => [
// 'yes' => 'Sim',
// 'no'  => 'Não'
// ]
// ],
// [
// 'id'          => 'voting_name',
// 'label'       => __( 'Voting name', 'concordamos' ),
// 'placeholder' => __( 'Give the event a name', 'concordamos' ),
// ],
// [
// 'id'          => 'description',
// 'label'       => __( 'Description of the voting', 'concordamos' ),
// 'placeholder' => __( 'Give the describe event details', 'concordamos' ),
// ],
// [
// 'id'          => 'number_voters',
// 'label'       => __( 'Number of voters', 'concordamos' ),
// 'placeholder' => __( 'How many voting links would you like to generate?', 'concordamos' ),
// 'type'        => 'number'
// ],
// [
// 'id'          => 'credits_voter',
// 'label'       => __( 'Voting credits per voter', 'concordamos' ),
// 'placeholder' => __( 'How many votes will each voter receive?', 'concordamos' ),
// 'type'        => 'number'
// ],
// [
// 'id'    => 'date_start',
// 'label' => __( 'Start date', 'concordamos' ),
// 'type'  => 'date'
// ],
// [
// 'id'    => 'date_end',
// 'label' => __( 'End date', 'concordamos' ),
// 'type'  => 'date'
// ],
// [
// 'id'    => 'unique_ids',
// 'label' => __( 'Unique IDs to restrict voting', 'concordamos' ),
// 'type'  => 'csv'
// ],
// [
// 'id' => 'expired_unique_ids',
// 'label' => __( 'Expired unique IDs', 'concordamos' ),
// 'type'  => 'csv'
// ]
// ] );

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
	)
);

// new Metadata( 'option', 'info', __( 'Additional information', 'concordamos' ), [
// [
// 'id'          => 'option_name',
// 'label'       => __( 'Option name', 'concordamos' ),
// 'placeholder' => __( 'Give the option a name', 'concordamos' )
// ],
// [
// 'id'          => 'option_description',
// 'label'       => __( 'Option description', 'concordamos' ),
// 'placeholder' => __( 'Give the describe option details', 'concordamos' )
// ],
// [
// 'id'          => 'option_link',
// 'label'       => __( 'Option link', 'concordamos' ),
// 'placeholder' => __( 'Give the link option', 'concordamos' )
// ],
// [
// 'id'          => 'voting_id',
// 'label'       => __( 'Voting ID', 'concordamos' ),
// 'placeholder' => __( 'Relationship with the voting', 'concordamos' )
// ]
// ] );


// Register usermeta on role `concordamos_network`
new UserMetadata(
	'concordamos_network',
	'info',
	__( 'Additional information (by Concordamos)', 'concordamos' ),
	array(
		array(
			'id'    => 'sample',
			'label' => 'Sample',
		),
	)
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
