<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://pradeepdabane.com/
 * @since      1.0.0
 *
 * @package    Pd_Fullcalendar
 * @subpackage Pd_Fullcalendar/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Pd_Fullcalendar
 * @subpackage Pd_Fullcalendar/public
 * @author     Pradeep Dabane <developerpaddy@gmail.com>
 */
class Pd_Fullcalendar_Booking {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

    private $db;

    private $table_name;
	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

        global $wpdb;

        $this->db = $wpdb;
        $this->table_name = $this->db->prefix . 'pd_fullcalendar';

	}

    public function get_bookings($status)
    {

		$user = get_current_user_id();
		if(pd_user_has_role($user, 'tutor')){
			$user_role = 'tutor_id';
		}elseif(pd_user_has_role($user, 'student')){
			$user_role = 'student_id';
		}
		if($status != 'all'){
			$fg_where = "WHERE $user_role = $user AND status = $status";
		}else{
			$fg_where = "WHERE $user_role = $user";
		}
		if(!pd_user_has_role($user, 'administrator')){
			$bookings = $this->db->get_results( 
				"	
					SELECT * 
					FROM $this->table_name
					$fg_where
					ORDER BY `start_date` DESC
				", ARRAY_A
			); // AND `status` = $status 
		}else{
			$bookings = $this->db->get_results( 
				"	
					SELECT * 
					FROM $this->table_name
					ORDER BY `start_date` DESC
				", ARRAY_A
			);
		}

		return $bookings;

    }

	public function get_bookings_by($key, $value){

		$bookings = $this->db->get_results( 
			"	
				SELECT * 
				FROM $this->table_name
				WHERE $key = $value
				ORDER BY `event_id` DESC
			", ARRAY_A
		);

		return $bookings;
	}

	public function insert_booking($booking)
	{

        $this->db->insert( 
            $this->table_name, 
            array( 
                'group_id' => $booking['request_id'],
                'all_day' => '0',
                'tutor_id' => $booking['tutor_id'], 
                'request_id' => $booking['request_id'], 
                'student_id' => $booking['student_id'], 
                'start_date' => $booking['start_str'],
                'start_str' => $booking['start_str'],
                'end_str' => $booking['end_str'],
                'booking_value' => 'sent',
                'booking_value' => $booking['booking_value'],
            )
        );
		
		$booking_id = $this->db->insert_id;
		//do_action( 'after_insert_booking', $booking );
		return $booking_id;
	}

	public function update_booking($request_id, $tutor_id, $column, $value)
	{
		global $wpdb;
		return $wpdb->update( 
			$this->table_name, 
			array( 
				$column => $value, 
			), 
			array( 
				'request_id' => $request_id,
				'tutor_id'	=> $tutor_id
			), 
			array( '%d' ), 
			array( '%d' ) 
		);
	}

	public function get_booking_Status($request_id, $user_role, $user)
	{	
		global $wpdb;
		$booking = $wpdb->get_row( "SELECT * 
					FROM $this->table_name 
					WHERE request_id = $request_id AND $user_role = $user" );

		return $booking->status;
	}

}

function pd_user_has_role($user_id, $role_name)
{
    $user_meta = get_userdata($user_id);
    $user_roles = $user_meta->roles;
    return in_array($role_name, $user_roles);
}
