<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://pradeepdabane.com/
 * @since      1.0.0
 *
 * @package    Pd_Fullcalendar
 * @subpackage Pd_Fullcalendar/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Pd_Fullcalendar
 * @subpackage Pd_Fullcalendar/includes
 * @author     Pradeep Dabane <developerpaddy@gmail.com>
 */
class Pd_Fullcalendar_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'pd-fullcalendar',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
