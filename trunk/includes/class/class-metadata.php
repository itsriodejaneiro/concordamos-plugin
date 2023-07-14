<?php

namespace Concordamos;

class Metadata {
	private $post_type_name;
	private $metabox_id;
	private $metabox_title;
	private $fields;

	public function __construct( $post_type_name, $metabox_id, $metabox_title, $fields = [] ) {
		$this->post_type_name = strtolower( str_replace( ' ', '_', $post_type_name ) );
		$this->metabox_id     = $metabox_id;
		$this->metabox_title  = $metabox_title;
		$this->fields         = $fields;

		add_action( 'add_meta_boxes', [$this, 'add_meta_box'] );
		add_action( 'save_post', [$this, 'save_meta_box'] );
	}

	public function add_meta_box() {
		add_meta_box(
			$this->metabox_id,
			$this->metabox_title,
			[$this, 'render_meta_box'],
			$this->post_type_name
		);
	}

	public function render_meta_box( $post ) {
		// Nonce field for security
		wp_nonce_field( $this->metabox_id . '_save', $this->metabox_id . '_nonce' );

		// Get current meta data
		$metadata = get_post_meta( $post->ID );

		echo '<div class="post-metadata-fields">';

			foreach ( $this->fields as $field ) {
				// Get field ID and label
				$field_id = $field['id'];

				// Get metadata value for the field or set it to an empty string
				$meta_value = isset( $metadata[$field_id][0] ) ? esc_attr( $metadata[$field_id][0] ) : '';

				// Render field based on type
				$html = $this->render_field( $field, $meta_value );

				echo $html;
			}

		echo '</div>';
	}

	public function save_meta_box( $post_id ) {
		// Check if nonce is set
		if ( ! isset( $_POST[ $this->metabox_id . '_nonce' ] ) ) {
			return $post_id;
		}

		// Check if nonce is valid
		if ( ! wp_verify_nonce( $_POST[ $this->metabox_id . '_nonce' ], $this->metabox_id . '_save' ) ) {
			return $post_id;
		}

		// Check if user has permissions to save data
		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return $post_id;
		}

		// Loop through fields
		foreach ( $this->fields as $field ) {

			// Check if field is set in $_POST
			if ( isset( $_POST[ $field['id'] ] ) ) {
				// Sanitize user input
				if ( $field['type'] === 'textarea' ) {
					$field_value = sanitize_textarea_field( $_POST[ $field['id'] ] );
				} else {
					$field_value = sanitize_text_field( $_POST[ $field['id'] ] );
				}

				// Update meta data
				update_post_meta( $post_id, $field['id'], $field_value );
			} else {
				// If not set, delete
				delete_post_meta( $post_id, $field['id'] );
			}
		}
	}

	public function render_field( $field, $value ) {
		$label     = $field['label'];
		$type      = isset( $field['type'] ) ? $field['type'] : 'text';
		$css       = isset( $field['css'] ) ? $field['css'] : '';
		$css_class = $css ? "field field-{$type} $css" : "field field-{$type}";

		$html = "
			<div class='{$css_class}'>
				<label>
					<span class='label'>{$label}</span>
					{$this->render_input( $field, $value )}
				</label>
			</div>
		";

		return $html;
	}

	public function render_input( $field, $value ) {
		$id      = $field['id'];
		$type    = isset( $field['type'] ) ? $field['type'] : 'text';
		$options = isset( $field['options'] ) ? $field['options'] : [];

		switch ( $type ) {
			case 'text':
				return "<input type='text' id='{$id}' name='{$id}' value='{$value}' />";
			case 'number':
				return "<input type='number' id='{$id}' name='{$id}' value='{$value}' min='0' />";
			case 'textarea':
				$value = esc_textarea( $value );
				return "<textarea id='{$id}' name='{$id}'>{$value}</textarea>";
			case 'checkbox':
				$checked = $value ? 'checked' : '';
				return "<input type='checkbox' id='{$id}' name='{$id}' {$checked} />";
			case 'hidden':
				return "<input type='text' id='{$id}' name='{$id}' value='{$value}' /><span class='input-hidden'>(Input hidden)</span>";
			case 'radio':
				// For radio input, value should be an array of options
				$html = '<div class="radio-options">';
				foreach ( $options as $option_value => $option_label ) {
					$checked = ($value == $option_value) ? 'checked' : '';
					$html .= "<input type='radio' id='{$id}_{$option_value}' name='{$id}' value='{$option_value}' {$checked}>{$option_label}<br>";
				}
				$html .= '</div>';
				return $html;
			default:
				return "<input type='text' id='{$id}' name='{$id}' value='{$value}' />";
		}
	}
}
