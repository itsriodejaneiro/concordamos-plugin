<?php

namespace Concordamos;

function custom_rewrite_rule() {
	add_rewrite_rule( '^voting/([^/]*)/([^/]*)/?','index.php?post_type=voting&name=$matches[1]&unique_id=$matches[2]', 'top' );
}

add_action( 'init', 'Concordamos\\custom_rewrite_rule', 10, 0 );

function custom_query_vars( $vars ) {
	$vars[] = 'unique_id';
	return $vars;
}

add_filter( 'query_vars', 'Concordamos\\custom_query_vars' );
