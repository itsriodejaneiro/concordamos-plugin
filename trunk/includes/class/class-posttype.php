<?php

namespace Concordamos;

class CPT {
	private $post_type_args;
	private $post_type_labels;
	private $post_type_name;

	public function __construct( $name, $args = [], $labels = [] ) {
		$this->post_type_name   = strtolower( str_replace( ' ', '_', $name ) );
		$this->post_type_args   = $args;
		$this->post_type_labels = $labels;

		add_action( 'init', [$this, 'register_custom_post_type'] );
	}

	public function register_custom_post_type() {
		$name = ucwords( str_replace( '_', ' ', $this->post_type_name ) );
		$plural = $name . 's';

		$labels = [
			'name'           => _x( $plural, 'Post type general name', 'concordamos-textdomain' ),
			'singular_name'  => _x( $name, 'Post type singular name', 'concordamos-textdomain' ),
			'menu_name'      => _x( $plural, 'Admin menu text', 'concordamos-textdomain' ),
			'name_admin_bar' => _x( $name, 'Add New on toolbar', 'concordamos-textdomain' ),
		];

		$args = [
			'labels'        => $labels,
			'public'        => true,
			'has_archive'   => true,
			'supports'      => ['title', 'editor', 'thumbnail'],
			'menu_position' => 20,
			'menu_icon'     => 'dashicons-admin-post',
			'rewrite'       => ['slug' => strtolower( $name )],
		];

		$this->post_type_args = wp_parse_args( $this->post_type_args, $args );
		$this->post_type_labels = wp_parse_args( $this->post_type_labels, $labels );

		register_post_type( $this->post_type_name, $this->post_type_args );
	}
}

