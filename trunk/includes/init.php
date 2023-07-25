<?php

namespace Concordamos;

// Custom post type `vote`
new CPT( 'vote', [
	'menu_icon' => 'dashicons-forms'
] );


// Custom post type `voting`
new CPT( 'voting', [
	'menu_icon' => 'dashicons-yes-alt',
	'supports' => ['author', 'editor', 'title']
] );

new Metadata( 'voting', 'info', __( 'Additional information', 'concordamos-textdomain' ), [
	[
		'id'    => 'voting_type',
		'label' => __( 'Voting type', 'concordamos-textdomain' ),
		'type'  => 'radio',
		'css'   => 'inline',
		'options' => [
			'public' => 'Publico',
			'private' => 'Privada'
		]
	],
	[
		'id'    => 'voting_access',
		'label' => __( 'Require login?', 'concordamos-textdomain' ),
		'type'  => 'radio',
		'css'   => 'inline',
		'options' => [
			'yes' => 'Sim',
			'no'  => 'Não'
		]
	],
	[
		'id'          => 'voting_name',
		'label'       => __( 'Voting name', 'concordamos-textdomain' ),
		'placeholder' => __( 'Give the event a name', 'concordamos-textdomain' ),
	],
	[
		'id'          => 'description',
		'label'       => __( 'Description of the voting', 'concordamos-textdomain' ),
		'placeholder' => __( 'Give the describe event details', 'concordamos-textdomain' ),
	],
	[
		'id'          => 'number_voters',
		'label'       => __( 'Number of voters', 'concordamos-textdomain' ),
		'placeholder' => __( 'How many voting links would you like to generate?', 'concordamos-textdomain' ),
		'type'        => 'number'
	],
	[
		'id'          => 'credits_voter',
		'label'       => __( 'Voting credits per voter', 'concordamos-textdomain' ),
		'placeholder' => __( 'How many votes will each voter receive?', 'concordamos-textdomain' ),
		'type'        => 'number'
	],
	[
		'id'    => 'date_start',
		'label' => __( 'Start date', 'concordamos-textdomain' ),
		'type'  => 'date'
	],
	[
		'id'    => 'date_end',
		'label' => __( 'End date', 'concordamos-textdomain' ),
		'type'  => 'date'
	],
	[
		'id'    => 'unique_ids',
		'label' => __( 'Unique IDs to restrict voting', 'concordamos-textdomain' ),
		'type'  => 'csv'
	]
] );

// Taxonomy `tag`
new Taxonomy( 'voting', 'tag', [
	'hierarchical'      => false,
	'public'            => true,
	'show_in_nav_menus' => true,
] );


// Custom post type `options`
new CPT( 'option', [
	'menu_icon' => 'dashicons-table-row-after',
	'supports' => ['author', 'title']
] );

new Metadata( 'option', 'info', __( 'Additional information', 'concordamos-textdomain' ), [
	[
		'id'          => 'option_name',
		'label'       => __( 'Option name', 'concordamos-textdomain' ),
		'placeholder' => __( 'Give the option a name', 'concordamos-textdomain' )
	],
	[
		'id'          => 'option_description',
		'label'       => __( 'Option description', 'concordamos-textdomain' ),
		'placeholder' => __( 'Give the describe option details', 'concordamos-textdomain' )
	],
	[
		'id'          => 'option_link',
		'label'       => __( 'Option link', 'concordamos-textdomain' ),
		'placeholder' => __( 'Give the link option', 'concordamos-textdomain' )
	],
	[
		'id'          => 'voting_id',
		'label'       => __( 'Voting ID', 'concordamos-textdomain' ),
		'placeholder' => __( 'Relationship with the voting', 'concordamos-textdomain' )
	]
] );


// Register usermeta on role `concordamos_network`
new UserMetadata( 'concordamos_network', 'info', __( 'Additional information (by Concordamos)', 'concordamos-textdomain' ), [
	[
		'id' => 'sample',
		'label' => 'Sample'
	]
] );
