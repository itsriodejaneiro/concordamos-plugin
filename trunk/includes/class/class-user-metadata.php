<?php

namespace Concordamos;

class UserMetadata {
	private $user_role;
	private $metabox_id;
	private $metabox_title;
	private $fields;

	public function __construct( $user_role, $metabox_id, $metabox_title, $fields = [] ) {
		$this->user_role     = strtolower( str_replace( ' ', '_', $user_role ) );
		$this->metabox_id    = $metabox_id;
		$this->metabox_title = $metabox_title;
		$this->fields        = $fields;

		add_action( 'show_user_profile', [$this, 'show_user_meta_fields'] );
		add_action( 'edit_user_profile', [$this, 'show_user_meta_fields'] );

		add_action( 'personal_options_update', [$this, 'save_user_meta_fields'] );
		add_action( 'edit_user_profile_update', [$this, 'save_user_meta_fields'] );
	}

	public function show_user_meta_fields( $user ) {
		// Check if the current user has the role this meta box is intended for
		if( ! in_array( $this->user_role, (array) $user->roles ) ) {
			return;
		}

		// Nonce field for security
		wp_nonce_field( $this->metabox_id . '_save', $this->metabox_id . '_nonce' );

		// Get current meta data
		$metadata = get_user_meta( $user->ID );

		// do_action( 'qm/debug', $metadata);

		echo '<h3>' . $this->metabox_title . '</h3>';
		echo '<table class="form-table">';

			$this->render_user_meta_fields( $user );

		echo '</table>';
	}

	public function render_user_meta_fields( $user ) {
		foreach ( $this->fields as $field ) {
			// Get field ID and label
			$field_id = $field['id'];
			$field_label = $field['label'];
			$field_type = isset( $field['type'] ) ? $field['type'] : 'text';
			$field_css = isset( $field['css'] ) ? $field['css'] : '';

			// Get metadata value for the field or set it to an empty string
			$meta_value = get_user_meta( $user->ID, $field_id, true );

			// Render field based on type
			$html = $this->render_field( $field_id, $field_label, $field_type, $field_css, $meta_value );

			echo $html;
		}
	}

	public function save_user_meta_fields( $user_id ) {
		// Check if nonce is set
		if ( ! isset( $_POST[ $this->metabox_id . '_nonce' ] ) ) {
			return $user_id;
		}

		// Check if nonce is valid
		if ( ! wp_verify_nonce( $_POST[ $this->metabox_id . '_nonce' ], $this->metabox_id . '_save' ) ) {
			return $user_id;
		}

		// Check if user has permissions to save data
		if ( ! current_user_can( 'edit_user', $user_id ) ) {
			return $user_id;
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

				// Update user meta data
				update_user_meta( $user_id, $field['id'], $field_value );
			} else {
				// If not set, delete
				delete_user_meta( $user_id, $field['id'] );
			}
		}
	}

	public function render_field( $id, $label, $type, $css, $value ) {

		do_action('qm/debug', [$id, $label, $type, $css, $value]);

		$css_class = $css ? "field field-{$type} $css" : "field field-{$type}";

		$html = "
			<div class='{$css_class}'>
				<label>
					<span class='label'>{$label}</span>
					{$this->render_input( $id, $type, $value )}
				</label>
			</div>
		";

		return $html;

	}

	public function render_input( $id, $type, $value ) {
		switch ( $type ) {
			case 'text':
				return "<input type='text' id='{$id}' name='{$id}' value='{$value}' />";
			case 'textarea':
				$value = esc_textarea( $value );
				return "<textarea id='{$id}' name='{$id}'>{$value}</textarea>";
			case 'checkbox':
				$checked = $value ? 'checked' : '';
				return "<input type='checkbox' id='{$id}' name='{$id}' {$checked} />";
			case 'radio':
				// For radio input, value should be an array of options
				$html = '';
				foreach ($value as $option_value => $option_label) {
					$html .= "<input type='radio' id='{$id}_{$option_value}' name='{$id}' value='{$option_value}'>{$option_label}<br>";
				}
				return $html;
			default:
				return "<input type='text' id='{$id}' name='{$id}' value='{$value}' />";
		}
	}
}
