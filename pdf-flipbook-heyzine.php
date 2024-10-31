<?php
/**
 * PDF Flipbook Heyzine
 *
 * @package           Cl_Heyzine
 * @author            Heyzine
 * @copyright         2023 Heyzine
 * @license           GPL-2.0-or-later
 *
 * @wordpress-plugin
 * Plugin Name:       PDF Flipbook Heyzine
 * Plugin URI:        https://heyzine.com
 * Description:       Heyzine is a flipbook maker for realistic page turn effects or slideshows with your PDF. Customize the PDF viewer with your own brand and style and add interactivity like videos, audios, or any kind of embed to your PDF.
 * Version:           1.0.0
 * Requires at least: 5.0
 * Requires PHP:      7.2
 * Tested up to:      6.4.2
 * Author:            Heyzine
 * Developer:         Carlos Longarela <dev@heyzine.com>
 * Text Domain:       pdf-flipbook-heyzine
 * Domain Path:       /languages
 * License:           GPL v2 or later
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'CL_HEYZINE_VERSION', '1.0.3' );
define( 'CL_HEYZINE_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );
define( 'CL_HEYZINE_PLUGIN_URL', plugin_dir_url( __FILE__ ) );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-cl-heyzine-activator.php
 */
function cl_heyzine_activate() {
	require_once CL_HEYZINE_PLUGIN_PATH . 'includes/class-cl-heyzine-activator.php';
	Cl_Heyzine_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-cl-heyzine-deactivator.php
 */
function cl_heyzine_deactivate() {
	require_once CL_HEYZINE_PLUGIN_PATH . 'includes/class-cl-heyzine-deactivator.php';
	Cl_Heyzine_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'cl_heyzine_activate' );
register_deactivation_hook( __FILE__, 'cl_heyzine_deactivate' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require CL_HEYZINE_PLUGIN_PATH . 'includes/class-cl-heyzine.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function cl_heyzine_run() {
	$plugin = new Cl_Heyzine();
	$plugin->run();
}
cl_heyzine_run();
