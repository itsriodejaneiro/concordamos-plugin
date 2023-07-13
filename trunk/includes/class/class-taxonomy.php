<?php

namespace Concordamos;

class Taxonomy {
	private $post_type;
	private $taxonomy_slug;
	private $taxonomy_args;
	private $labels;

	public function __construct( $post_type, $taxonomy_slug, $taxonomy_args = [], $labels = [] ) {
		$this->post_type     = $post_type;
		$this->taxonomy_slug = $taxonomy_slug;
		$this->taxonomy_args = $taxonomy_args;
		$this->labels        = $labels;

		add_action( 'init', [$this, 'register_taxonomy'] );
	}

	public function register_taxonomy() {
		$default_labels = [
			'name'              => _x( 'Tags', 'taxonomy general name', 'concordamos-textdomain' ),
			'singular_name'     => _x( 'Tag', 'taxonomy singular name', 'concordamos-textdomain' ),
			'search_items'      => __( 'Search Tags', 'concordamos-textdomain' ),
			'all_items'         => __( 'All Tags', 'concordamos-textdomain' ),
			'parent_item'       => __( 'Parent Tag', 'concordamos-textdomain' ),
			'parent_item_colon' => __( 'Parent Tag:', 'concordamos-textdomain' ),
			'edit_item'         => __( 'Edit Tag', 'concordamos-textdomain' ),
			'update_item'       => __( 'Update Tag', 'concordamos-textdomain' ),
			'add_new_item'      => __( 'Add New Tag', 'concordamos-textdomain' ),
			'new_item_name'     => __( 'New Tag Name', 'concordamos-textdomain' ),
			'menu_name'         => __( 'Tags', 'concordamos-textdomain' ),
		];
		$labels = wp_parse_args( $this->labels, $default_labels );

		$default_args = [
			'hierarchical'      => false,
			'labels'            => $labels,
			'show_ui'           => true,
			'show_admin_column' => true,
			'query_var'         => true,
			'rewrite'           => ['slug' => $this->taxonomy_slug],
		];
		$args = wp_parse_args( $this->taxonomy_args, $default_args );

		register_taxonomy( $this->taxonomy_slug, $this->post_type, $args );
	}
}
