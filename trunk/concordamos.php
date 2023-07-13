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
require_once( 'includes/init.php' );
