<?php

namespace Concordamos;

// Custom post type `vote`
new CPT( 'vote', [
	'menu_icon' => 'dashicons-forms'
] );


// Custom post type `voting`
new CPT( 'voting', [
	'menu_icon' => 'dashicons-yes-alt'
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
		'id'          => 'voting_name',
		'label'       => __( 'Voting name', 'concordamos-textdomain' ),
		'placeholder' => __( 'Give the event a name', 'concordamos-textdomain' ),
	],
	[
		'id'          => 'description',
		'label'       => __( 'Description of the voting', 'concordamos-textdomain' ),
		'placeholder' => __( 'Give theDescribe event details', 'concordamos-textdomain' ),
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
		'id'          => 'voting_options',
		'label'       => __( 'Options of the voting', 'concordamos-textdomain' ),
		'placeholder' => __( 'Select options of the voting', 'concordamos-textdomain' ),
		'type'        => 'hidden'
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
	'menu_icon' => 'dashicons-table-row-after'
] );


// Register usermeta on role `concordamos_network`
new UserMetadata( 'concordamos_network', 'info', __( 'Additional information (by Concordamos)', 'concordamos-textdomain' ), [
	[
		'id' => 'sample',
		'label' => 'Sample'
	]
] );
