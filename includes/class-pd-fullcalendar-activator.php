<?php

/**
 * Fired during plugin activation
 *
 * @link       https://pradeepdabane.com/
 * @since      1.0.0
 *
 * @package    Pd_Fullcalendar
 * @subpackage Pd_Fullcalendar/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Pd_Fullcalendar
 * @subpackage Pd_Fullcalendar/includes
 * @author     Pradeep Dabane <developerpaddy@gmail.com>
 */
class Pd_Fullcalendar_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {

		global $wpdb;
		$charset_collate = $wpdb->get_charset_collate();
		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

		//* Create the teams table
		$table_name = $wpdb->prefix . 'pd_fullcalendar';
		$sql = "CREATE TABLE $table_name (
		event_id INTEGER NOT NULL AUTO_INCREMENT,
		group_id INTEGER NOT NULL,
		all_day INTEGER NOT NULL,
		tutor_id INTEGER NOT NULL,
		student_id INTEGER NOT NULL,
		request_id INTEGER NOT NULL,
		start_date DATE NOT NULL,
		end_date DATE NOT NULL,
		start_str VARCHAR(255) NULL,
		end_str VARCHAR(255) NULL,
		booking_status INTEGER NOT NULL,
		booking_value VARCHAR(255) NULL,
		PRIMARY KEY  (event_id)
		) $charset_collate;";
		dbDelta( $sql );

		// $table_booking = $wpdb->prefix . 'pd_booking';
		// $sql_booking = "CREATE TABLE $table_name (
		// booking_id INTEGER NOT NULL AUTO_INCREMENT,
		// tutor_id INTEGER NOT NULL,
		// student_id INTEGER NOT NULL,
		// request_id INTEGER NOT NULL,
		// booking_date DATE NOT NULL,
		// booking_value INTEGER NULL,
		// booking_status INTEGER NULL,
		// reference_id INTEGER NULL,
		// PRIMARY KEY  (booking_id)
		// ) $charset_collate;";
		// dbDelta( $sql );

		$event_meta_table = $wpdb->prefix . 'pd_event_meta';
		$sql_event_meta = "CREATE TABLE $event_meta_table (
		event_meta_id INTEGER NOT NULL AUTO_INCREMENT,
		event_id INTEGER NOT NULL,
		title VARCHAR(255) NULL,
		url VARCHAR(255) NULL,
		class_names VARCHAR(255) NULL,
		editable INTEGER NULL,
		start_editable INTEGER NULL,
		duration_editable INTEGER NULL,
		resource_editable INTEGER NULL,
		display VARCHAR(255) NULL,
		overlap INTEGER NULL,
		PRIMARY KEY (event_meta_id),
		FOREIGN KEY (event_id) REFERENCES $table_name (event_id)
		) $charset_collate;";
		dbDelta( $sql_event_meta );

	}

}
