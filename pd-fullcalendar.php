<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://pradeepdabane.com/
 * @since             1.0.0
 * @package           Pd_Fullcalendar
 *
 * @wordpress-plugin
 * Plugin Name:       PD Fullcalendar
 * Plugin URI:        https://pradeepdabane.com/plugins/pd-fullcalendar
 * Description:       This is a short description of what the plugin does. It's displayed in the WordPress admin area.
 * Version:           1.0.0
 * Author:            Pradeep Dabane
 * Author URI:        https://pradeepdabane.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       pd-fullcalendar
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
define( 'PD_FULLCALENDAR_VERSION', '1.0.1' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-pd-fullcalendar-activator.php
 */
function activate_pd_fullcalendar() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-pd-fullcalendar-activator.php';
	Pd_Fullcalendar_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-pd-fullcalendar-deactivator.php
 */
function deactivate_pd_fullcalendar() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-pd-fullcalendar-deactivator.php';
	Pd_Fullcalendar_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_pd_fullcalendar' );
register_deactivation_hook( __FILE__, 'deactivate_pd_fullcalendar' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-pd-fullcalendar.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_pd_fullcalendar() {

	$plugin = new Pd_Fullcalendar();
	$plugin->run();

}
run_pd_fullcalendar();
