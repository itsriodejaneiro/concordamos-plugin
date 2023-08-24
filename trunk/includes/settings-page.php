<?php

namespace Concordamos;

/**
 * Adds a new menu page in WordPress admin panel.
 *
 * @uses add_menu_page() WordPress function to add a new menu page.
 *
 * @return void
 */
function add_admin_menu() {
    add_menu_page(
        __( 'Concordamos Settings', 'concordamos' ),
        __( 'Concordamos Settings', 'concordamos' ),
        'manage_options',
        'concordamos-settings',
        'Concordamos\settings_page_html',
        '',
        null
    );
}
add_action( 'admin_menu', 'Concordamos\add_admin_menu' );

/**
 * Outputs the HTML for the plugin settings page.
 *
 * This function checks if the current user has the necessary permissions
 * (manage_options) before outputting the HTML of the settings page.
 *
 * @return void
 */
function settings_page_html() {
    if ( ! current_user_can( 'manage_options' ) ) {
        return;
    }

    echo '<div class="wrap">';
    echo '<h1>' . esc_html( get_admin_page_title() ) . '</h1>';
    echo '<form action="options.php" method="post">';

    settings_fields( 'concordamos' );
    do_settings_sections( 'concordamos-settings' );
    submit_button( __( 'Save Settings', 'concordamos' ) );

    echo '</form>';
    echo '</div>';
}

/**
 * Initializes settings for the Concordamos plugin.
 *
 * This function is used to register settings, add a settings section,
 * and add a settings field for the Concordamos plugin.
 *
 * @return void
 */
function settings_init() {
    register_setting( 'concordamos', 'concordamos_options' );

    add_settings_section(
        'concordamos_section',
        __('Pages Templates', 'concordamos'),
        'Concordamos\section_callback',
        'concordamos-settings'
    );

    add_settings_field(
        'voting_page',
        __( 'Create voting', 'concordamos' ),
        'Concordamos\select_field_render',
        'concordamos-settings',
        'concordamos_section',
        [
            'name' => 'voting_page',
        ]
    );

	add_settings_field(
        'faq_page',
        __( 'FAQ', 'concordamos' ),
        'Concordamos\select_field_render',
        'concordamos-settings',
        'concordamos_section',
        [
            'name' => 'faq_page',
        ]
    );
}
add_action( 'admin_init', 'Concordamos\settings_init' );

function section_callback() {
    echo '';
}

function select_field_render($args) {
	$options = get_option( 'concordamos_options' );
	$pages = get_pages();
    $name = $args['name'];

	?>
	<select name='concordamos_options[<?php echo $name; ?>]'>
		<option value='' <?php selected( $options[$name], '', false ); ?>><?php _e( 'Select a page', 'concordamos' ); ?></option>
		<?php
		foreach ( $pages as $page ) {
			echo "<option value='{$page->ID}' " . selected( $options[$name], $page->ID, false ) . ">{$page->post_title}</option>";
		}
		?>
	</select>
	<?php
}
