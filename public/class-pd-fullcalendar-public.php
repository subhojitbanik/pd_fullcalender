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
class Pd_Fullcalendar_Public {

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
	

	private $booking;

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

		// require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/test-control.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-pd-fullcalendar-booking.php';
		$this->booking = new Pd_Fullcalendar_Booking($this->plugin_name, $this->version);
		
		add_action( 'template_redirect', array($this, 'insert_booking_fn') );
		add_filter( 'fg_payment_success', array($this, 'update_booking') );
		add_shortcode( 'show_bookings', array($this, 'show_bookings') );
		add_shortcode( 'table_def', array($this, 'table_def') );
		add_shortcode( 'get_booking_status', array($this, 'get_booking_status') );
		add_shortcode( 'get_tutor_rate', array($this, 'get_tutor_rate') );
		add_action( 'wp_footer', array( $this, 'event_info') );
		add_filter( 'the_content', array( $this, 'booking_form'), 99 );
		add_action( 'wp_ajax_cancel_booking', array($this, 'cancel_booking') );
		add_action( 'wp_ajax_nopriv_cancel_booking', array($this, 'cancel_booking') );
		add_action( 'after_unattended_refund_success', array($this, 'unattended_refund_success'), 10, 1 );
		add_action('sb_video_after_update_remark',array($this, 'refund_if_tutor_failed_to_join'), 10, 2 );
		// add_action( 'elementor/element/after_section_end', array( $this, 'add_elementor_section'), 10, 3);
		// add_filter( 'elementor/frontend/widget/should_render', array( $this,'display_condition_for_buttons'), 10, 2 );
		// add_action( 'admin_post_nopriv_insert_booking', array($this, 'insert_booking_fn') );
		// add_action( 'wp_ajax_get_bookings', array($this, 'get_bookings') );
		// add_action( 'wp_ajax_nopriv_get_bookings', array($this, 'get_bookings') );

	}

	public function table_def($atts)
	{
		$atts = shortcode_atts( [
			'default' => 0,
		], $atts, 'table_def' );

		global $wpdb;
		$table = $wpdb->prefix . 'pd_fullcalendar';

		
		// ----------------------------------------------

		// $sql = "ALTER TABLE `{$table}`
		// 		ADD `canceled_by` INT NULL DEFAULT 0;";

		// $query_result = $wpdb->query( $sql );

		// ----------------------------------------------

		
		$existing_columns = $wpdb->get_col("DESC {$table}", 0);
		//print_r($existing_columns	);
		// $this->booking->update_booking($booking['request_id'], $booking['tutor_id'], 1);
		// $this->booking->update_booking(4692, 91, 'status',2);
		// $this->booking->update_booking(4589, 91, 'status',2);
		// $this->booking->update_booking(4606, 91, 'status',2);
		// $this->booking->update_booking(4620, 91, 'status',2);
		// $this->booking->update_booking(5449, 100, 'status',4);
		// $this->booking->update_booking(5443, 106, 'status',4);
		// $this->booking->update_booking(5434, 100, 'status',4);
		// $this->booking->update_booking(5430, 100, 'status',4);

		// $this->booking->update_booking(5449, 100, 'canceled_by',4);
		// $this->booking->update_booking(5443, 106, 'canceled_by',4);
		// $this->booking->update_booking(5434, 100, 'canceled_by',4);
		// $this->booking->update_booking(5430, 100, 'canceled_by',4);
		// $this->booking->update_booking(4620, 91, 2);
		$data = [
			'request_id' => 6709,
		];
		// ----------------------------------------------

		// $sql = "ALTER TABLE `{$table}`
		// 		ADD `canceled_by` INT NULL DEFAULT 0;";

		// $query_result = $wpdb->query( $sql );
		// $sql = "ALTER TABLE `{$table}`
		// 		ADD `cancel_reason` VARCHAR(255) NULL ;";
				

		// $query_result = $wpdb->query( $sql );

		// ----------------------------------------------
		

		ob_start();
		print_r($existing_columns	);
			// print_r(pd_stripe_process_refund(4688));
			echo '<pre>';
			print_r($this->get_booking_status($data));
			echo '</pre>';
			echo 'Get Booking Status : '. $this->get_booking_status($data);
			echo '<table>';
				echo '<tr>';
					echo '<th>request_id</th>';
					echo '<th>tutor_id</th>';
					echo '<th>student_id</th>';
					echo '<th>start_date</th>';
					echo '<th>time</th>';
					echo '<th>status</th>';
					echo '<th>canceled_by</th>';
					echo '<th>cancel_reason</th>';
					echo '<th>booking_value</th>';
				echo '</tr>';
			foreach($this->booking->get_bookings('all') as $booking){
				echo '<tr>';
					echo '<td>'.$booking['request_id'].'</td>';
					echo '<td>'.$booking['tutor_id'].'</td>';
					echo '<td>'.$booking['student_id'].'</td>';
					echo '<td>'.$booking['start_str'].'</td>';
					echo '<td>'.$booking['time'].'</td>';
					echo '<td>'.$booking['status'].'</td>';
					echo '<td>'.$booking['canceled_by'].'</td>';
					echo '<td>'.$booking['cancel_reason'].'</td>';
					echo '<td>'.$booking['booking_value'].'</td>';
				echo '</tr>';
			}
			echo '</table>';
			// echo '</br>';
			// $i = 0;
			// echo '$offices = array(</br>';
			// foreach($this->booking->get_bookings() as $booking){
			// 	echo '"" => "'.$booking['request_id'].'",</br>';
			// 	$i++;
			// }
			// echo ');</br>';
		return ob_get_clean();

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Pd_Fullcalendar_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Pd_Fullcalendar_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/pd-fullcalendar-public.css', array(), $this->version, 'all' );
		wp_enqueue_style( $this->plugin_name.'-fullcalendar', 'https://cdn.jsdelivr.net/npm/fullcalendar@5.10.2/main.min.css', array(), $this->version, 'all' );
		wp_enqueue_style( $this->plugin_name.'-flatpickr', 'https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Pd_Fullcalendar_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Pd_Fullcalendar_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name.'-flatpickr', 'https://cdn.jsdelivr.net/npm/flatpickr', array( 'jquery' ), $this->version, true );
		wp_enqueue_script( $this->plugin_name.'-moment', 'https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.8.3/moment.min.js', array( 'jquery' ), $this->version, true );
		wp_enqueue_script( $this->plugin_name.'-fullcalendar', 'https://cdn.jsdelivr.net/npm/fullcalendar@5.10.2/main.js', array( 'jquery', $this->plugin_name.'-moment' ), $this->version, true );
		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/pd-fullcalendar-public.js', array( 'jquery', $this->plugin_name.'-fullcalendar' ), $this->version, true );
		if(is_page('tutors-tutoring-session-timetable') || is_page('students-tutoring-session-timetable')){
			wp_localize_script( $this->plugin_name, 'fc', array(
				// 'ajax_url' => admin_url( 'admin-ajax.php' ),
				'events' => $this->get_bookings(2)
			) );
		}
	}

	public function get_adjusted_timestring($tutor_id, $student_id, $start_str){

		global $current_user;
		$user_roles = $current_user->roles;

		$studentTimezone = get_post_meta($this->get_user_profile_id($student_id), 'student_timezone', true);
		$tutorTimezone = get_post_meta($this->get_user_profile_id($tutor_id), 'tutor_timezone', true );

		if(!empty($tutorTimezone) && !empty($studentTimezone)){
			if(in_array('student', $user_roles)){
				$start = $start_str;
			}elseif(in_array('tutor', $user_roles)){
				// Get student timezone - Canada/Atlantic
				$sTimezone = new DateTimeZone( $studentTimezone );
				// Set time
				$start_str = new DateTime( $start_str, $sTimezone );
				// Get tutor Timezone - tutor Timezone
				$tTimezone = new DateTimeZone( $tutorTimezone );
				$start_str->setTimeZone( $tTimezone );
				// Convert student timezone to tutor Timezone
				$start = $start_str->format( 'Y-m-d H:i:s' ); // 2020-06-03 03:45:15
			}
		}else{
			$start = $start_str;
		}

		return $start;

	}

	public function booking_form($content)
	{

		if ( in_the_loop() && is_main_query() && is_page( 2404 ) && ( !empty($_GET['request_id']) || $_GET['success'] == 'true' ) ) {

			$request = $this->get_request_details($_GET['request_id']);
			$start_str = get_post_meta( $_GET['request_id'], 'preferred_time_slot', true );
			// $student_id = get_post_field( 'post_author', $_GET['request_id'] );

			$prefered_time_slot = $this->get_adjusted_timestring(get_current_user_id(), $request['student_id'], $start_str);
			$end_str = date(strtotime($prefered_time_slot) + 60*60);
			$budget = get_post_meta( $_GET['request_id'], 'budget', true );
			$date = date('Y-m-d', strtotime($prefered_time_slot));
			$start = date('h:i A', strtotime($prefered_time_slot));
			$start_hour = date('h:i A', strtotime($prefered_time_slot));
			$end = date('h:i A', strtotime($start_hour) + 60*60);
			$response_value = $this->get_response_value( get_current_user_id(), $_GET['request_id']);

			ob_start();
			echo $sTimezone;
			if(!isset($_GET['success']) || $_GET['success'] != 'true'){

				if(!empty($_GET['responce_id'])){?>
					<style>
						#custom_amt{
							display:none;
						}
						.cst_amt{
							display: none;
						}
					</style>
					<?php
				}
				
			echo '
				<div id="booking_form_wrapper" class="booking_form">
					<form action="" id="booking_form" method="post">
						<lable for="request_title">Request Title </lable>
						<input type="text" name="request_title" id="request_title" value="'.$request['title'].'" readonly>

						<lable for="booking_date">Booking Date</lable>
						<input type="text" name="booking_date" id="booking_date" value="' . $date . '" readonly>
						<lable for="booking_date">Start Time</lable>
						<input type="text" name="start_time" id="start_time" value="' . $start . '" readonly>
						<lable for="booking_date">End Time</lable>
						<input type="text" name="end_time" id="end_time" value="' . $end . '" readonly>

						<p>Propose Your Booking Request Value</p>
						<input type="radio" id="student" name="booking_value" value="student">
						<label for="student">Student\'s Max Budget :  $'. $budget .'/hr.</label>
						<br/>
						<input type="radio" id="tutor" name="booking_value" value="tutor">
						<label for="tutor">Your Rate : $'. $response_value .'/hr.</label>
						<br/>
						<input type="radio" id="custom_amt" name="booking_value" value="">
						<label for="custom_amt" class="cst_amt">Other Rate : $/hr.</label>
						<input type="number" name="booking_valuee" id="custom_val" style="width: 48%; margin: 0px 1%;">
						<br/>
						<br/>
						<input type="hidden" name="start_str" id="request_id" class="start_time" value="'.$start_str.'" />
						<input type="hidden" name="end_str" id="request_id" value="'.$end_str.'" />
						<input type="hidden" name="request_id" id="request_id" value="'.$_GET['request_id'].'" />
						<input type="hidden" name="student_id" id="student_id" value="'.$request['student_id'].'" />
						<input type="hidden" name="responce_id" id="responce_id" value="'.$_GET['responce_id'].'" />
						<input type="hidden" name="action" value="insert_booking">
						<input type="hidden" name="booking_form_nonce" value="'.wp_create_nonce( 'booking_form' ).'" />
						<input type="submit" id="sb_book" value="Book Now">
					</form>
					
				</div>
			';
			}elseif($_GET['success'] == 'true'){
				?>
					<div class="booking_success">
						<h2>Booking request is successfully sent</h2>
						<a href="<?php echo home_url('/tutor-dashboard/tutors-booking-requests/'); ?>" class="response_action_btn btn-green">Check Booking Status</a>
					</div>
				<?php
			}
			return ob_get_clean();

		}else{

			return $content;
		}

	}

	public function event_info()
	{
		echo '
			<div id="event_info"></div>
		';
	}

	public function get_bookings($status)
	{

		global $current_user;
		$user_roles = $current_user->roles;
		$booking_array = [];
		$bookings = $this->booking->get_bookings($status);
		//if(!empty($booking['request_id'])){
			foreach( $bookings as $booking){
				
				$subject = (  get_the_terms( $booking['request_id'], 'subject' )[0]->name ) ? get_the_terms( $booking['request_id'], 'subject' )[0]->name : '';
				$field_of_study = (  get_the_terms( $booking['request_id'], 'field_of_study' )[0]->name ) ? get_the_terms( $booking['request_id'], 'field_of_study' )[0]->name : '';
				$grade = (  get_the_terms( $booking['request_id'], 'grade' )[0]->name ) ? get_the_terms( $booking['request_id'], 'grade' )[0]->name : '';
				

				$studentTimezone = get_post_meta($this->get_user_profile_id($booking['student_id']), 'student_timezone', true);
				$tutorTimezone = get_post_meta($this->get_user_profile_id($booking['tutor_id']), 'tutor_timezone', true );

				if(!empty($tutorTimezone) && !empty($studentTimezone)){
					if(in_array('student', $user_roles)){
						$start = $booking['start_str'];
					}elseif(in_array('tutor', $user_roles)){
						// Get student timezone - Canada/Atlantic
						$sTimezone = new DateTimeZone( $studentTimezone );
						// Set time
						$start_str = new DateTime( $booking['start_str'], $sTimezone );
						// Get tutor Timezone - tutor Timezone
						$tTimezone = new DateTimeZone( $tutorTimezone );
						$start_str->setTimeZone( $tTimezone );
						// Convert student timezone to tutor Timezone
						$start = $start_str->format( 'Y-m-d H:i:s' ); // 2020-06-03 03:45:15
					}
				}else{
					$start = $booking['start_str'];
				}
				

				$booking_array[] = array(
					'id' => $booking['event_id'],
					'request_id' => $booking['request_id'],
					'groupId' => $booking['group_id'],
					'allDay' => ( $booking['all_day'] ) ? true : false,
					'start' => $start, // Convert student timezone to tutor Timezone
					//'end' => $booking['end_str'],
					'title' => get_the_title( $booking['request_id'] ),
					'extendedProps' => array(
						'subject' => $subject,
						'fieldOfStudy' => $field_of_study,
						'grade' => $grade,
						'join' => get_meeting_link( $booking['request_id'] ),
						'url' => get_the_permalink( $booking['request_id'] ),
						'reason' => get_meeting_status($booking['request_id'], true)
					),
					'tutor_id' => $booking['tutor_id'],
					'student_id' => $booking['student_id'],
					'booking_value' => $booking['booking_value'],
					'status' => $booking['status'],
					'canceled_by' => $booking['canceled_by'],
					'cancel_reason' => $booking['cancel_reason'],
				);

				// if(empty(get_meeting_link( $booking['request_id'] ))){
				// 	foreach(get_meeting_status($booking['request_id']) as $value){
				// 		$reason = $value;
				// 	}
				// 	$booking_array['extendedProps']['reason'] = $reason;
				// }

			}
		// }

		// ob_start();
		// // print_r(json_encode($booking_array));
		// print_r(json_encode($_POST['user']));
		// echo ob_get_clean();
		// die();

		return $booking_array;
	}

	public function insert_booking_fn()
	{		
		$booking = [];
		if( isset( $_POST['booking_form_nonce'] ) && wp_verify_nonce( $_POST['booking_form_nonce'], 'booking_form') ) {
			$booking['request_id'] = $_POST['request_id'];
			$booking['student_id'] = $_POST['student_id'];
			$booking['tutor_id'] = get_current_user_id();
			$booking['start_str'] = $_POST['start_str'];
			$booking['end_str'] = $_POST['end_str'];
			$booking['booking_value'] = $_POST['booking_value'];
			// $booking['status']	= 1;


			$booking_id = $this->booking->insert_booking($booking);

			if($booking_id){
				$this->booking->update_booking($booking['request_id'], $booking['tutor_id'], 'status' ,1);
				// update_post_meta( $_POST['responce_id'], 'booking_request_status', 'sent' );
				do_action( 'after_insert_booking', $booking_id, $booking );
				// 


				// $tutor = get_userdata( $booking['tutor_id'] );
				// $tutor_mail = $tutor->user_email;
				// $student = get_userdata( $booking['student_id'] );
				// $student_mail = $student->user_email;
				// $to = $student_mail;
				// $subject = "you have received a booking request";
				// $message = "Booking request ID: ".$booking['request_id']." (This is an automatically generated email, do not reply. Take action in the fastgrades website application.)";
				// sb_notif_mail_fn($to,$subject,$message);
				//

				//
				insert_session_data($booking_id, $booking);
				$redirect = add_query_arg( 'success', 'true', get_permalink() );
                wp_redirect( $redirect );
			}
		}
	}

	public function get_request_details($request_id)
	{
		$request = array();

		$request['title'] = get_the_title( $request_id );
		$request['student_id'] = get_post_field ('post_author', $request_id);

		return $request;
	}

	public function show_bookings()
	{
		// echo '<pre>';
		// print_r($this->get_bookings());
		// echo '</pre>';

		global $current_user;

    	$user_roles = $current_user->roles;						

		ob_start();
		
		foreach($this->get_bookings('all') as $booking){
			// print_r($this->get_response_value($booking['tutor_id'], $booking['request_id']));

			if(in_array('student', $user_roles)){
				$profile_id = $this->get_user_profile_id($booking['tutor_id']);
				$username_label = '<span class="label">Tutor Name : </span>';
			}elseif(in_array('tutor', $user_roles)){
				$profile_id = $this->get_user_profile_id($booking['student_id']);
				$username_label = '<span class="label">Student Name : </span>';
			}
			$profile_link = get_the_permalink( $profile_id );
			$user_name = '<span class="username">'.get_post_meta( $profile_id, 'first_name', true ).' '.get_post_meta( $profile_id, 'last_name', true ).'</span>';
			$booking_status = $this->get_booking_status(['request_id' => $booking['request_id'] ]);
		?>
			<div class="booking_wrapper" data-booking-id="<?php echo $booking['id']; ?>">
			<div class="pd-md-8">
				<h2 class="tutoring_request_title"><?php echo $booking['title']; ?></h2>
				<h4><?php echo get_post_meta( $booking['request_id'], 'request_type', true ); ?></h4>
				<ul>
					<li class="username">
						<?php
							echo $username_label;
							echo $user_name;
						?>
					</li>
				</ul>
				<ul>
					<li class="subject">
						<span class="label">Subject : </span>
						<span><?php echo $booking['extendedProps']['subject']; ?></span>
					</li>
					<li class="fieldofStudy">
						<span class="label">Field of Study : </span>
						<span><?php echo $booking['extendedProps']['fieldOfStudy']; ?></span>
					</li>
					<li class="grade">
						<span class="label">Level : </span>
						<span><?php echo $booking['extendedProps']['grade']; ?></span>
					</li>
				</ul>
				<ul>
					<li class="date">
						<span class="label">Date : </span>
						<span><?php echo date('Y-m-d', strtotime($booking['start'])); ?></span>
					</li>
					<li class="start">
						<span class="label">Start Time : </span>
						<span><?php echo date('h:i A', strtotime($booking['start'])); ?></span>
					</li>
					<li class="end">
						<span class="label">End Time : </span>
						<span><?php echo date('h:i A', ( strtotime($booking['start']) + 3600 ) ); ?></span>
					</li>
				</ul>
				<?php if(in_array('student', $user_roles) || in_array('administrator', $user_roles)){ ?>

					<?php if( $booking_status == 1 && $booking_status != 2){ ?>
					<ul>
						<li>
							<span>Tutors can propose a rate within your max budget or according to their own. Only pay tutors when you are in agreement with their price.</span>
						</li>
					</ul>
					<ul>
						<li>
							<form action="<?php echo home_url('/pay/'); ?>" method="POST" id="form-<?php echo $booking['id']; ?>">
								<?php wp_nonce_field( 'fg_secure_pay', 'wp_secure_pay'); ?>
								<input type="hidden" name="booking_id" value="<?php echo $booking['id']; ?>">
								<input type="hidden" name="tutor_id" value="<?php echo $booking['tutor_id']; ?>">
								<input type="hidden" name="request_id" value="<?php echo $booking['request_id']; ?>">
								<input type="hidden" name="amount" value="<?php echo $this->get_booking_value($booking); ?>">
								<input type="checkbox" name="agree_to_pay" class="agree_to_pay" data-target="pay-<?php echo $booking['id']; ?>" id="agree-<?php echo $booking['id']; ?>">
								<label for="agree-<?php echo $booking['id']; ?>">You are agreeing to pay the tutor's proposed price of $<?php echo $this->get_booking_value($booking); ?>/hr</label>
								<span class="msg-pay msg-pay-<?php echo $booking['id']; ?> disabled">Please read the agreement and click the check box before you can pay</span>
							</form>
						</li>
					</ul>
					<?php }elseif( $booking_status == 2 ){ ?>

					<ul>
						<li class="msg-pay msg-pay-<?php echo $booking['id']; ?>">You have already paid for this request at a price of $<?php echo $this->get_booking_value($booking); ?>/hr</li>
					</ul>

					<?php } 

				} ?>

				<?php if( in_array('tutor', $user_roles) && $booking_status == 2 ){ ?>

					<ul>
						<li class="msg-pay msg-pay-<?php echo $booking['id']; ?>">Student has paid for this request at a price of $<?php echo $this->get_booking_value($booking); ?>/hr</li>
					</ul>

				<?php } ?>

				<?php if( $booking_status == 3 ){ ?>

					<ul>
						<li class="msg-pay msg-pay-<?php echo $booking['id']; ?>"><?php echo $this->get_booking_canceled_by($booking); ?>.</li>
					</ul>

				<?php }elseif( $booking_status == 4 ){ ?>
					
					<ul>
						<li class="msg-pay msg-pay-<?php echo $booking['id']; ?>"><?php echo $this->get_booking_canceled_by($booking); ?></li>
					</ul>

				<?php } elseif(in_array('tutor', $user_roles) &&  $booking_status == 1 ){ ?>
					<ul>
						<?php $payment_record = pd_stripe_get_payment_record($booking['request_id']); 
							//print_r($pay_staus);
							$pay_status =  get_post_meta($payment_record[0]->ID, 'payment_status', true);

						?>
						<li class="msg-pay msg-pay-<?php echo $booking['id']; ?>">Booking Status for this request is : <span style="color:#fcb12b"><?php echo ($pay_status == 'succeeded') ? ' Closed':' Pending'; ?></span> </li>
					</ul>
				<?php } ?>


				<ul class="actions">

					<?php if(in_array('student', $user_roles) || in_array('administrator', $user_roles)){ ?>

						<li><a class="view_tutor_profile" href="<?php echo $profile_link; ?>">View Tutor Profile</a></li>
						<?php if( $booking_status < 2 ){ ?>
						<li>
							<a class="pay pay-disabled btn-blue" href="" data-form="form-<?php echo $booking['id']; ?>" data-agree="agree-<?php echo $booking['id']; ?>" id="pay-<?php echo $booking['id']; ?>">Pay</a>
						</li>
						<?php } ?>

					<?php } ?>

					<?php if( $booking_status == 2 && get_meeting_status($booking['request_id'], false) == 0 ){ ?>
						<!-- <li><a class="decline_booking" href="" data-bookingstatus="<?php echo $booking_status; ?>" data-requestid="<?php echo $booking['request_id']; ?>" data-tutorid="<?php echo $booking['tutor_id']; ?>" data-bookingid="<?php echo $booking['id']; ?>">Cancel</a></li> -->
						
						<li><a class="sb-open">Cancel</a></li>
						<div class="pop-container" id="pop-container"></div>
						<div class="modal" id="pop-modal">
							<div class="sb-header">
								<a href="#" class="cancel">X</a>
							</div>
							<div class="content">
								<form>
									<textarea name="cancel_reason" id="cancel_reason" cols="20" rows="5" placeholder="Enter cancellation reason" style="margin-bottom:15px;"></textarea>
									<a class="decline_booking" href="" data-bookingstatus="<?php echo $booking_status; ?>" data-requestid="<?php echo $booking['request_id']; ?>" data-tutorid="<?php echo $booking['tutor_id']; ?>" data-bookingid="<?php echo $booking['id']; ?>">Submit</a>						
								</form>
							</div>
							
						</div>
						
					<?php } ?>

					<li><a class="view_request" href="<?php echo get_permalink( $booking['request_id'] ); ?>">View Request</a></li>
				</ul>

			</div>
			</div>
		<?php
		}

		// print_r($this->get_bookings());
		return ob_get_clean();
	}

	public function get_user_profile_id($user_id)
	{
		$profile_id = get_user_meta( $user_id, 'profile_id', true );
		return $profile_id;
	}

	public function get_response_value($tutor_id, $request_id){

		$request_type = get_post_meta( $request_id, 'request_type', true );
		if($request_type == 'public'){
			$args = [
				'post_type' => 'fastgrade_bidding',
				'meta_key' => 'tutoring_request_id',
				'meta_value' => $request_id,
				'meta_compare' => '=',
				'author' => $tutor_id
			];
			$response = get_posts($args);

			$value = get_post_meta( $response[0]->ID, 'bid_value', true);
			return $value;

		}elseif($request_type == 'private'){
			return $this->get_tutor_rate($tutor_id, $request_id);
		}
	}

	public function get_tutor_rate($tutor_id, $request_id){
		// $tutor_profile_id = 3102;
		// $request_id = 3186;
		$subject = get_the_terms( $request_id, 'subject')[0]->term_id;
		$fields_of_study = get_the_terms( $request_id, 'field_of_study' )[0]->term_id;
		$grade = get_the_terms( $request_id, 'grade' )[0]->term_id;

		// ob_start();
		// var_dump($subject);
		// var_dump($fields_of_study);
		// var_dump($grade);
		// return ob_get_clean();

		$tutor_profile_id = $this->get_user_profile_id($tutor_id);
		$tutor_pricing = get_field('tutor_pricing', $tutor_profile_id);		
		
		// ob_start();
		// var_dump($tutor_pricing);
		// return ob_get_clean();

		foreach($tutor_pricing as $check){
			if($check['subjects'] == (int)$subject && $check['fields_of_study'] == (int)$fields_of_study && $check['grade'] == (int)$grade){
				return $check['price'];
			}
		}
	}

	public function get_response_id($tutor_id, $request_id){

		$args = [
			'post_type' => 'fastgrade_bidding',
			'meta_query' => array(
				'relation' => 'AND',
				array(
					'key'     => 'tutoring_request_id',
					'value'   => $request_id,
					'compare' => '=',
				),
				array(
					'key'     => 'tutor_profile_id',
					'value'   => $tutor_id,
					'compare' => '=',
				)
			)
		];
		$response = get_posts($args);

		$value = $response[0]->ID;
		return $value;
	}

	public function get_booking_value($booking)
	{
		if($booking['booking_value'] == 'tutor'){
			return $this->get_response_value($booking['tutor_id'], $booking['request_id']);
		}elseif($booking['booking_value'] == 'student'){
			return get_post_meta( $booking['request_id'], 'budget', true );
		}elseif(($booking['booking_value'] != 'student') || ($booking['booking_value'] != 'tutor')){
			return $booking['booking_value'];
		}
	}

	public function get_booking_status($atts)
	{
		$atts = shortcode_atts( [
			'label' => 'no',
			'bidding' => 'no',
			'tutoring_req' => 'no',
			'request_id' => '',
		], $atts, 'get_booking_status' );

		$user = get_current_user_id();
		if(pd_user_has_role($user, 'tutor')){
			$user_role = 'tutor_id';
		}elseif(pd_user_has_role($user, 'student')){
			$user_role = 'student_id';
		}else{
			$user_role = 'tutor_id';
		}

		if($atts['bidding'] === 'yes'){
			$request_id = get_post_meta( get_the_ID() , 'tutoring_request_id', true );
		}
		if($atts['tutoring_req'] === 'yes'){
			$request_id = get_the_ID();
		}
		if(!empty($atts['request_id'])){
			$request_id = $atts['request_id'];
		}

		$status = $this->booking->get_booking_status($request_id, $user_role, $user);

		if($atts['label'] == 'yes'){

			$statuses = array(
				'Not Sent',
				'Sent',
				'Paid',
				'Cancelled',
				'Refunded'
			);

			return $statuses[$status];

		}
		return $status;

	}

	public function display_condition_for_buttons($should_render, $widget)
	{
		$allowed_buttons = array(
			'button',
			'edit_button'
		);
		if ( in_array($widget->get_name(), $allowed_buttons) ){

			$settings = $widget->get_settings();
	
			if ( ! empty( $settings['button_css_id'] ) && $settings['button_css_id'] === 'display-condition') {
				// $widget_content .= '<i class="fa fa-external-link" aria-hidden="true"></i>';
				$post_type = get_post_type( get_the_id() );
				$should_render = false;
				return $should_render;

			}
			
			// return $should_render;
		}
	
		return $should_render;
	}

	public function add_elementor_section( $element, $section_id, $args ) {

		if ( 'section' === $element->get_name() && 'section_layout' === $section_id ) { // section_advanced
	
			$element->start_controls_section(
				'custom_section',
				[
					'tab' => \Elementor\Controls_Manager::TAB_STYLE,
					'label' => esc_html__( $section_id, 'plugin-name' ),
				]
			);
	
			$element->add_control(
				'custom_control',
				[
					'type' => \Elementor\Controls_Manager::NUMBER,
					'label' => esc_html__( 'Custom Control', 'plugin-name' ),
				]
			);
	
			$element->end_controls_section();
	
		}
	
	}

	public function unattended_refund_success($meeting)	
	{
		$meeting = array(
			'request_id' => $meeting->request_id,
			'tutor_id' => $meeting->tutuor_id,
			'booking_status' => 4
		);

		$updated_1 = $this->update_booking($meeting);
		$updated = $this->booking->update_booking($meeting['request_id'], $meeting['tutor_id'], 'canceled_by', 4 );
		// echo $meeting['request_id'].' '.$meeting['tutor_id'].' : '.$updated_1. ' ' .$updated.'<br/>';
	}
	public function update_booking($booking)
	{
		if(!empty($booking)){
			return $this->booking->update_booking($booking['request_id'], $booking['tutor_id'], 'status', $booking['booking_status']);
			// return true; 
		}else{
			// echo 'Error while confrming the payment';
			return false;
		}
	}

	public function refund_if_tutor_failed_to_join($remark, $request_id)
	{

		// if($remark == 1){

			foreach($this->booking->get_bookings_by('request_id', $request_id) as $booking){
				if($booking['status'] == 2){
					$this->booking->update_booking($request_id, $booking['tutor_id'], 'status', 4);
					$this->booking->update_booking($request_id, $booking['tutor_id'], 'canceled_by', $remark );
				}
			}
		// }
		
	}

	public function cancel_booking(){
		// if ( check_ajax_referer( '_ajax_nonce' ) ) {
			global $wpdb;
			$table = $wpdb->prefix . 'pd_fullcalendar';
			$status = $_POST['booking_status'];
			if(!empty($status)){

				switch ($status) {

					case 1:
						$_POST['booking_status'] = 3;
						$updated = $this->update_booking($_POST);
						$this->booking->update_booking($_POST['request_id'], $_POST['tutor_id'], 'canceled_by', get_current_user_id());
						break;

					case 2:
						$_POST['booking_status'] = 4;
						$refund_status = pd_stripe_process_refund($_POST['request_id']);
						if($refund_status === 'succeeded'){
							$updated = $this->update_booking($_POST);
							$this->booking->update_booking($_POST['request_id'], $_POST['tutor_id'], 'canceled_by', get_current_user_id());
							$wpdb->update( $table, array( 'cancel_reason' => $_POST['cancel_reason']), array( 'request_id' => $_POST['request_id'] ) );

						}
						break;
					
					default:
						$updated = false;
						break;

				}
				$data = [
					'success' => $updated,
					'booking_status' => $_POST['booking_status'],
					'booking_id' => $_POST['booking_id']
				];
				wp_send_json_success( $data, 200, 0);
			}
		// }
	}

	public function get_booking_canceled_by($booking){

		// if( 0 == $booking['canceled_by']){
		// 	return;
		// }
		// return $booking['canceled_by'];
		if($booking['tutor_id'] == $booking['canceled_by']){
			return 'Booking has been cancelled by tutor and refund has been initiated. <br> Cancellation reason : '.$booking['cancel_reason'];
		}elseif($booking['student_id'] == $booking['canceled_by']){
			return 'Booking has been cancelled by student and refund has been initiated. <br> Cancellation reason : '.$booking['cancel_reason'];
		}elseif($booking['canceled_by'] == 1){
			return 'Booking has been cancelled cause tutor failed to join and refund has been initiated.';
		}elseif($booking['canceled_by'] == 2){
			return 'Booking has been cancelled cause student failed to join and tutor payment has been initiated.';
		}elseif($booking['canceled_by'] == 3){
			return 'Session attended successfully';
		}elseif($booking['canceled_by'] == 4){
			return 'Due to session is not attended and time has elapsed, the refund has been initiated.';
		}
	}
}

function fg_pd_get_booking_status($atts){
	$booking = new Pd_Fullcalendar_Public('pd-fullcalendar', PD_FULLCALENDAR_VERSION);
	return $booking->get_booking_status($atts);
}


function sb_get_availability_fn($input_date_time,$user_id){
	global $wpdb;
	$table_name = $wpdb->prefix . 'pd_fullcalendar';

	//$input_date_time = '2023-01-09 05:00:00';
	//$input_date_tim = date ('Y-m-d H:i:s', strtotime($input_date_time));

	 $mEnd = new DateTime($input_date_time);
     $mEnd = $mEnd->modify('+ 1 hour');
     $mEnd = $mEnd->format('Y-m-d H:i:s');
	//  print_r('input date time: '.$input_date_time );
	//  print_r('<br> modifiy 1 hr time: '.$mEnd);

	//2023-01-05 05:30:00 SELECT * FROM `sb_pd_fullcalendar` WHERE `start_str` = '2022-09-28 15:00:00' AND `start_str`>= '2022-09-28 15:00:00' AND `start_str`<='2022-09-28 16:00:00' AND `start_str` BETWEEN '2022-09-28 15:00:00' AND '2022-09-28 16:00:00';
	//$results = $wpdb->get_results("SELECT * FROM $table_name WHERE ((`start_str` = '$input_date_time' AND `start_str`>='$input_date_time' AND `start_str`<= '$mEnd' AND `start_str` BETWEEN '$input_date_time' AND '$mEnd') AND `tutor_id` = '$user_id') OR ((`start_str` = '$input_date_time' AND `start_str`>='$input_date_time' AND `start_str`<= '$mEnd' AND `start_str` BETWEEN '$input_date_time' AND '$mEnd') AND `student_id` = '$user_id') ");
	
	$results = $wpdb->get_results("SELECT * FROM $table_name  WHERE(`start_str` >= '$input_date_time' AND `start_str`<= '$mEnd' AND `start_str` BETWEEN '$input_date_time' AND '$mEnd')AND (`tutor_id`='$user_id' OR `student_id`='$user_id');");
	
	// print_r('<pre>');
	// print_r($results);
	// print_r('</pre>');

	//  print_r('<br>count result'.count($results));
	if(count($results) > 0){
		if($results[0]->canceled_by != 0){
			$status = 1;
			//print_r('available');
		}else{
			$status = 0;
			//print_r('not-available');
		}
	}else{
		$status = 1;
		//print_r('available');
	}

	return $status;

}


function get_user_id_from_profile_id($profile_id){
    $sb_users = get_users( array(
        "meta_key" => "profile_id",
        "meta_value" => $profile_id,
        "fields" => "ID"
    ) );
	return $sb_users[0];
}





function sb_add_timecheck_script(){ ?>
	<script>
		jQuery(document).ready(function ($) {
			$('.acf-date-time-picker.acf-input-wrap').change(function (e) { 
				e.preventDefault();
				var date  = $('input#acff-post-field_62419ab552304').val();
				
				// alert(date);
				// console.info(date);

				jQuery.ajax({
					type: "POST",
					url: "<?php echo admin_url('admin-ajax.php'); ?>",
					data: {
						"date": date,
						"action": "sb_add_timecheck"
					},
					success: function (response) {
						console.log(response);
						//button.fea-submit-button.button
						if(response == 0){
							$('.sb_alert').remove();
							$('.acf-date-time-picker').css('border','red 1px solid');
							$('.acf-date-time-picker').after('<p class="sb_alert" style="margin: 5px auto;color: #fcb12b;">You are already booked for this time slot!</p>');
							$("button.fea-submit-button.button").prop('disabled', true);
						}else{
							$('.acf-date-time-picker').css('border','none');
							$('.sb_alert').remove();
							$("button.fea-submit-button.button").prop('disabled', false);
						}
					},
					error: function(e) {
						console.log(e);
						//$("button.fea-submit-button.button").prop('disabled', true);
					},
				});

				//nw func

				jQuery.ajax({
					type: "POST",
					url: "<?php echo admin_url('admin-ajax.php'); ?>",
					data: {
						"date": date,
						"action": "sb_check_tutors_availability"
					},
					success: function (response) {
						var res = JSON.parse(response);
						// console.log(res);
						// console.log(res[0].id);
						// console.log(res[0].availability);
						jQuery.each(res, function(key, val) {
							// console.log("#pro-"+ val.id +" p");
							// console.log("#pro-"+ val.availability +" p");
							if(val.availability == 0){
								$("#pro-"+ val.id +" p").text("Availablity status : Not available");
							console.log('Not available');
							}else if(val.availability == 1){
								$("#pro-"+ val.id +" p").text("Availablity status : Available");
								// console.log('available');
							}
							
						});

					},
					error: function(e) {
						console.log(e);

					},
				});

				//end

			});
		});
	</script>
	<?php
}
add_action( 'wp_head', 'sb_add_timecheck_script',20 );

function sb_add_timecheck_tutor_script(){
	if (is_user_logged_in()) {
        $user = wp_get_current_user();
        $role = $user->roles[0];  
		if ($role == "tutor") {?>
			<script>
				jQuery(document).ready(function ($) {
					//tutor-dashboard
					$('#custom_val').hide();
					$('#tutor').click(function () { 
						$('#custom_val').hide();						
					});
					$('#student').click(function () { 
						$('#custom_val').hide();						
					});
					
					$('#custom_amt').click(function () { 
						$('#custom_val').show();
						$('#custom_val').change(function () { 						
							var value = $('#custom_val').val();
							$('#custom_amt').val(value);
							
						});
					});

					var startTimee = $('.start_time').val();
					console.log(startTimee);
					jQuery.ajax({
							type: "POST",
							url: "<?php echo admin_url('admin-ajax.php'); ?>",
							data: {
								"start": startTimee,
								"action": "sb_add_timecheck_tutor"
							},
							success: function (response) {
								console.log(response);
								//button.fea-submit-button.button
								if(response == 0){
									$('.sb_alert').remove();
									$('#start_time').css('border','#fcb12b 1px solid');
									$('#start_time').after('<p class="sb_alert" style="margin: 5px auto;color: #fcb12b;">You are already booked for this time slot!</p>');
									$("#sb_book").prop('disabled', true);
								}else{
									$('#start_time').css('border','none');
									$('.sb_alert').remove();
									$("#sb_book").prop('disabled', false);
								}
							},
							error: function(e) {
								console.log(e);
								//$("button.fea-submit-button.button").prop('disabled', true);
							},
						});
					
				});
			</script>
			<?php
		}
	}
}
add_action( 'wp_head', 'sb_add_timecheck_tutor_script',25 );



add_action('wp_ajax_sb_add_timecheck', 'sb_add_timecheck_cb');
add_action('wp_ajax_nopriv_sb_add_timecheck', 'sb_add_timecheck_cb');
function sb_add_timecheck_cb(){

	$input_date_time = $_POST['date'];
	$user_id = get_current_user_id();
	echo sb_get_availability_fn($input_date_time,$user_id);
	die();
}


add_action('wp_ajax_sb_add_timecheck_tutor', 'sb_add_timecheck_tutor_cb');
add_action('wp_ajax_nopriv_sb_add_timecheck_tutor', 'sb_add_timecheck_tutor_cb');
function sb_add_timecheck_tutor_cb(){

	$input_date_time = $_POST['start'];
	$user_id = get_current_user_id();
	echo sb_get_availability_fn($input_date_time,$user_id);
	die();


}




add_action('wp_ajax_sb_check_tutors_availability', 'sb_check_tutors_availability_cb');
add_action('wp_ajax_nopriv_sb_check_tutors_availability', 'sb_check_tutors_availability_cb');
function sb_check_tutors_availability_cb(){

	//$date_time = '2023-02-01 06:00:00';
	$date_time = $_POST['date'];
	
	$tutor_ids = [];
	$tutor_profile_ids = $_SESSION['tutor_cart']['profile_ids'];
	foreach ($tutor_profile_ids as $profile_id) {
		//$tutor_ids[] = get_user_id_from_profile_id($profile_id);
		$tutor_ids[] = array(
			'id' => $profile_id,
			'availability'=> sb_get_availability_fn($date_time,get_user_id_from_profile_id($profile_id))
		);
	}
	echo json_encode($tutor_ids);
	//echo json_encode($arr); var res = JSON.parse(resultData);
	die();
}
