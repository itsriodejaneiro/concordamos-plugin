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

	echo '<div class="wrap concordamos-settings">';
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
	add_settings_section(
		'concordamos_section',
		__( 'Pages Templates', 'concordamos' ),
		'Concordamos\section_callback',
		'concordamos-settings'
	);

	register_setting( 'concordamos', 'concordamos_faq_page' );
	add_settings_field(
		'concordamos_faq_page',
		__( 'FAQ', 'concordamos' ),
		'Concordamos\select_field_render',
		'concordamos-settings',
		'concordamos_section',
		array(
			'name' => 'concordamos_faq_page',
		)
	);

	add_settings_section(
		'concordamos_section_private_votings',
		__( 'Private votings', 'concordamos' ),
		'Concordamos\section_callback',
		'concordamos-settings'
	);

	register_setting( 'concordamos', 'concordamos_use_unique_links' );
	add_settings_field(
		'concordamos_use_unique_links',
		__( 'Use unique links on private votings?', 'concordamos' ),
		'Concordamos\switch_field_render',
		'concordamos-settings',
		'concordamos_section_private_votings',
		array(
			'name' => 'concordamos_use_unique_links',
			'help' => __( 'By enabling this setting, only private votings that are created from now on will display the unique poll links.', 'concordamos' ),
		)
	);
}
add_action( 'admin_init', 'Concordamos\settings_init' );

function section_callback() {
	echo '';
}

function select_field_render( $args ) {
	$name   = $args['name'];
	$option = get_option( $name );
	$pages  = get_pages();
	?>
	<select name='<?php echo esc_attr( $name ); ?>'>
		<option value='' <?php selected( $option, '', false ); ?>><?php esc_html_e( 'Select a page', 'concordamos' ); ?></option>
		<?php
		foreach ( $pages as $page ) {
			echo "<option value='" . esc_attr( $page->ID ) . "' " . selected( $option, $page->ID, false ) . '>' . wp_kses( $page->post_title, 'data' ) . '</option>';
		}
		?>
	</select>
	<?php
}

function switch_field_render( $args ) {
	$name   = $args['name'];
	$option = get_option( $name );
	?>
	<label class="switch">
		<input type="checkbox" value="yes" name="<?php echo esc_attr( $name ); ?>"
			<?php
			if ( 'yes' === $option ) {
				echo 'checked="checked"';}
			?>
		>
		<span class="slider round"></span>
	</label>

	<?php if ( isset( $args['help'] ) && ! empty( $args['help'] ) ) : ?>
		<p class="help"><?php echo esc_html( $args['help'] ); ?></p>
		<?php
	endif;
}
