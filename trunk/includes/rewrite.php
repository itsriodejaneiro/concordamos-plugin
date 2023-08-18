<?php

namespace Concordamos;

function custom_rewrite_rule() {
	add_rewrite_rule( '^voting/([^/]*)/panel/?', 'index.php?post_type=voting&name=$matches[1]&panel=1', 'top' );
	add_rewrite_rule(
		'^voting/([^/]*)/(u-[a-fA-F0-9]+)?/?((?!u-)(a-[a-fA-F0-9]+))?/?$',
		'index.php?post_type=voting&name=$matches[1]&unique_id=$matches[2]&admin_id=$matches[3]',
		'top'
	);
}

add_action( 'init', 'Concordamos\\custom_rewrite_rule', 10, 0 );

function custom_query_vars( $vars ) {
	$vars[] = 'admin_id';
	$vars[] = 'unique_id';
	$vars[] = 'panel';
	return $vars;
}

add_filter( 'query_vars', 'Concordamos\\custom_query_vars' );
