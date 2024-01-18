<?php

namespace Concordamos;

class CPT {
	private $post_type_args;
	private $post_type_labels;
	private $post_type_name;

	public function __construct( $name, $args, $labels ) {
		$this->post_type_name   = strtolower( str_replace( ' ', '_', $name ) );
		$this->post_type_args   = $args;
		$this->post_type_labels = $labels;

		add_action( 'init', array( $this, 'register_custom_post_type' ) );
	}

	public function register_custom_post_type() {
		$singular = $this->post_type_labels['singular_name'];
		$plural = $this->post_type_labels['name'];

		$labels = array(
			'name'           => $plural,
			'singular_name'  => $singular,
			'menu_name'      => $plural,
			'name_admin_bar' => $plural,
		);

		$this->post_type_labels = wp_parse_args( $this->post_type_labels, $labels );

		$args = array(
			'labels'        => $this->post_type_labels,
			'public'        => true,
			'has_archive'   => true,
			'supports'      => array( 'title', 'editor', 'thumbnail' ),
			'menu_position' => 20,
			'menu_icon'     => 'dashicons-admin-post',
			'rewrite'       => array( 'slug' => strtolower( $this->post_type_name ) ),
		);

		$this->post_type_args = wp_parse_args( $this->post_type_args, $args );

		register_post_type( $this->post_type_name, $this->post_type_args );
	}
}
