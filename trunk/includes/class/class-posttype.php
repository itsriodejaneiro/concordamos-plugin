<?php

namespace Concordamos;

class CPT {
	private $post_type_args;
	private $post_type_labels;
	private $post_type_name;

	public function __construct( $name, $args = array(), $labels = array() ) {
		$this->post_type_name   = strtolower( str_replace( ' ', '_', $name ) );
		$this->post_type_args   = $args;
		$this->post_type_labels = $labels;

		add_action( 'init', array( $this, 'register_custom_post_type' ) );
	}

	public function register_custom_post_type() {
		$name   = ucwords( str_replace( '_', ' ', $this->post_type_name ) );
		$plural = $name . 's';

		$labels = array(
			'name'           => _x( $plural, 'Post type general name', 'concordamos' ),
			'singular_name'  => _x( $name, 'Post type singular name', 'concordamos' ),
			'menu_name'      => _x( $plural, 'Admin menu text', 'concordamos' ),
			'name_admin_bar' => _x( $name, 'Add New on toolbar', 'concordamos' ),
		);

		$this->post_type_labels = wp_parse_args( $this->post_type_labels, $labels );

		$args = array(
			'labels'        => $this->post_type_labels,
			'public'        => true,
			'has_archive'   => true,
			'supports'      => array( 'title', 'editor', 'thumbnail' ),
			'menu_position' => 20,
			'menu_icon'     => 'dashicons-admin-post',
			'rewrite'       => array( 'slug' => strtolower( $name ) ),
		);

		$this->post_type_args = wp_parse_args( $this->post_type_args, $args );

		register_post_type( $this->post_type_name, $this->post_type_args );
	}
}
