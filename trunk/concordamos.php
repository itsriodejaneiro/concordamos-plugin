<?php
/**
 * Plugin Name:       Concordamos
 * Description:       Sistema de voto quadrático do Concordamos
 * Version:           0.0.1
 * Requires at least: 5.8
 * Requires PHP:      7.0
 * Author:            Hacklab Team
 * License:           GPL-2.0-or-later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       concordamos-plugin
 * Domain Path:       /languages
 *
 * @package           create-block
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

define( 'CONCORDAMOS_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );
define( 'CONCORDAMOS_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define( 'CONCORDAMOS_PLUGIN_VERSION', '0.0.1' );

require_once( 'includes/class/class-posttype.php' );
require_once( 'includes/class/class-metadata.php' );
require_once( 'includes/class/class-taxonomy.php' );
require_once( 'includes/class/class-user-metadata.php' );

function concordamos_init() {
	// Add custom role `concordamos_network`
	remove_role( 'concordamos_network' );
	add_role( 'concordamos_network', __( 'Concordamos Network', 'concordamos-textdomain' ), ['read' => true] );
}

register_activation_hook( __FILE__, 'concordamos_init' );

require_once( 'includes/helpers.php' );
require_once( 'includes/enqueues.php' );
require_once( 'includes/templates.php' );
require_once( 'includes/settings-page.php' );
require_once( 'includes/api.php' );
require_once( 'includes/init.php' );