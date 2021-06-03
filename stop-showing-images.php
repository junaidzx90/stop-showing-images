<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://www.fiverr.com/junaidzx90
 * @since             1.0.0
 * @package           Stop_Showing_Images
 *
 * @wordpress-plugin
 * Plugin Name:       Stop showing images
 * Plugin URI:        https://github.com/junaidzx90/stop-showing-images
 * Description:       This is a short description of what the plugin does. It's displayed in the WordPress admin area.
 * Version:           1.0.0
 * Author:            Md Junayed
 * Author URI:        https://www.fiverr.com/junaidzx90
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       stop-showing-images
 * Domain Path:       /languages
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
define( 'STOP_SHOWING_IMAGES_VERSION', '1.0.0' );
define( 'STOP_SHOWING_IMAGES_URL', plugin_dir_url( __FILE__ ));
/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-stop-showing-images-activator.php
 */
function activate_stop_showing_images() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-stop-showing-images-activator.php';
	Stop_Showing_Images_Activator::activate();
}


/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-stop-showing-images-deactivator.php
 */
function deactivate_stop_showing_images() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-stop-showing-images-deactivator.php';
	Stop_Showing_Images_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_stop_showing_images' );
register_deactivation_hook( __FILE__, 'deactivate_stop_showing_images' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-stop-showing-images.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_stop_showing_images() {

	$plugin = new Stop_Showing_Images();
	$plugin->run();

}
run_stop_showing_images();
