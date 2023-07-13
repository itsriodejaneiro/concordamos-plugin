<?php

namespace Concordamos;

// Custom post type `vote`
new CPT( 'vote', [
	'menu_icon' => 'dashicons-forms'
] );

new Metadata( 'vote', 'info', __( 'Additional information', 'concordamos-textdomain' ), [
	[
		'id' => 'sample',
		'label' => 'Sample'
	]
] );


// Custom post type `voting`
new CPT( 'voting', [
	'menu_icon' => 'dashicons-yes-alt'
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




