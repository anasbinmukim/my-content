<?php
function stm_delete_user( $user_id ) {
	global $wpdb;
	//user profile post id
	$profile_post_id = esc_attr( get_the_author_meta( '_stm_timesheet_post_id', $user_id ) );
	//delete profile custom post
	wp_delete_post( $profile_post_id );

	$table_member = $wpdb->base_prefix . "stm_timesheet_member";
	$wpdb->query("DELETE FROM $table_member WHERE employee_id = $user_id");

}
add_action( 'delete_user', 'stm_delete_user' );



//add_action( 'user_register', 'stm_user_registration_save', 10, 1 );
function stm_user_registration_save( $user_id ) {
	$post_title =  '';
    if ( isset( $_POST['first_name'] ) )
		$post_title =  $_POST['first_name'];
	else{
		$user_info = get_userdata($user_id);
		//$username = $user_info->user_login;
		$first_name = $user_info->first_name;
		$last_name = $user_info->last_name;
		$post_title =  $first_name . ' ' . $last_name;
	}


	$defaults = array(
				  'post_type'      => 'ocbmembers',
				  'post_title'     => $post_title,
				  'post_content'  =>   'Replace with your content.',
				  'post_status'    => 'publish'
				);
	if($post_id = wp_insert_post( $defaults )) {
		// add to user profile
		add_post_meta($post_id, '_stm_user_id', $user_id);

		//add user profile to post
		update_user_meta( $user_id, '_stm_profile_post_id', $post_id );

		update_user_meta( $user_id, 'show_admin_bar_front', 'false' );

	}

}



/**
 * Include and setup custom metaboxes and fields.
 *
 * @category YourThemeOrPlugin
 * @package  Metaboxes
 * @license  http://www.opensource.org/licenses/gpl-license.php GPL v2.0 (or later)
 * @link     https://github.com/webdevstudios/Custom-Metaboxes-and-Fields-for-WordPress
 */
add_filter( 'cmb_meta_boxes', 'config_stm_metaboxes' );
/**
 * Define the metabox and field configurations.
 *
 * @param  array $meta_boxes
 * @return array
 */
function config_stm_metaboxes( array $meta_boxes ) {
	// Start with an underscore to hide fields from custom fields list
	$prefix = '_stm_';


	/**
	 * Sample metabox to demonstrate each field type included
	 */
	$meta_boxes['task_specifications_metabox'] = array(
		'id'         => 'task_specifications',
		'title'      => __( 'More Information', 'cmb' ),
		'pages'      => array( 'tasks', ), // Post type
		'context'    => 'normal',
		'priority'   => 'high',
		'show_names' => true, // Show field names on the left
		// 'cmb_styles' => true, // Enqueue the CMB stylesheet on the frontend
		'fields'     => array(
			array(
				'name' => __( 'Hours', 'cmb' ),
				'desc' => __( '', 'cmb' ),
				'id'   => $prefix . 'hours',
				'type' => 'text_small',
			),
			array(
				'name' => __( 'Due Date', 'cmb' ),
				'desc' => __( '', 'cmb' ),
				'id'   => $prefix . 'due_date',
				'type' => 'select',
				'options' => array(
					'Monday' => __( 'Monday', 'cmb' ),
					'Tuesday'   => __( 'Tuesday', 'cmb' ),
					'Wednesday'     => __( 'Wednesday', 'cmb' ),
					'Thursday'     => __( 'Thursday', 'cmb' ),
					'Friday'     => __( 'Friday', 'cmb' ),
				),
				'default' => 'Monday',
			)
		),
	);

	$meta_boxes['task_assignee_metabox'] = array(
		'id'         => 'task_assignee_metabox_id',
		'title'      => __( 'Assignee', 'cmb' ),
		'pages'      => array( 'tasks', ), // Post type
		'context'    => 'side',
		'priority'   => 'high',
		'show_names' => false, // Show field names on the left
		// 'cmb_styles' => true, // Enqueue the CMB stylesheet on the frontend
		'fields'     => array(
			array(
				'name'    => __( 'Assignee', 'cmb' ),
				'desc'    => __( '', 'cmb' ),
				'id'      => $prefix . 'assignee',
				'type'    => 'select',
				'options' => get_employee_arrays_for_cmb_metabox('employee'),
				// 'inline'  => true, // Toggles display to inline
			)
		),
	);

	if(current_user_can('manage_options')){
		$meta_boxes['emp_permission_metabox'] = array(
			'id'         => 'emp_permission_metabox_id',
			'title'      => __( 'Permmission', 'cmb' ),
			'pages'      => array( 'user', ), // Post type
			'show_names' => true,
			'cmb_styles' => false, // Show cmb bundled styles.. not needed on user profile page
			'fields'     => array(
				array(
					'name'    => __( 'Allow assign task to other', 'cmb' ),
					'desc'    => __( '', 'cmb' ),
					'id'      => $prefix . 'assign_to_other_permission',
					'type'    => 'checkbox',
				),
				array(
					'name'    => __( 'Allow user to assign their own tasks', 'cmb' ),
					'desc'    => __( '', 'cmb' ),
					'id'      => $prefix . 'assign_own_tasks',
					'type'    => 'checkbox',
				)
			),
		);

	}

	$args = array('role__in' => array('team_leader'));
	$users = get_users( $args );
	$team_leaders = array('0'=>'N/A');
	foreach($users as $user){
		$team_leaders[$user->ID] = get_user_meta($user->ID, 'last_name', true);
	}
	//print_r($team_leaders);
	/**
	 * Metabox for the user profile screen
	 */
	$meta_boxes['user_edit'] = array(
		'id'         => 'user_edit',
		'title'      => __( 'Employee Profile', 'cmb' ),
		'pages'      => array( 'user' ), // Tells CMB to use user_meta vs post_meta
		'show_names' => true,
		'cmb_styles' => false, // Show cmb bundled styles.. not needed on user profile page
		'fields'     => array(
			array(
				'name' => __( 'Available Hours Monday:', 'cmb' ),
				'desc' => __( '', 'cmb' ),
				'id'   => $prefix . 'available_hours_monday',
				'type' => 'text',
			),
			array(
				'name' => __( 'Available Hours Tuesday:', 'cmb' ),
				'desc' => __( '', 'cmb' ),
				'id'   => $prefix . 'available_hours_tuesday',
				'type' => 'text',
			),
			array(
				'name' => __( 'Available Hours Wednesday:', 'cmb' ),
				'desc' => __( '', 'cmb' ),
				'id'   => $prefix . 'available_hours_wednesday',
				'type' => 'text',
			),
			array(
				'name' => __( 'Available Hours Thursday:', 'cmb' ),
				'desc' => __( '', 'cmb' ),
				'id'   => $prefix . 'available_hours_thursday',
				'type' => 'text',
			),
			array(
				'name' => __( 'Available Hours Friday:', 'cmb' ),
				'desc' => __( '', 'cmb' ),
				'id'   => $prefix . 'available_hours_friday',
				'type' => 'text',
			),
			array(
				'name'    => __( 'Team', 'cmb' ),
				'desc'    => __( '', 'cmb' ),
				'id'      => $prefix . 'team',
				'type'    => 'select',
				'options' => $team_leaders,
			),
			array(
				'name'    => __( 'Skills', 'cmb' ),
				'desc'    => __( '', 'cmb' ),
				'id'      => $prefix . 'skills',
				'type'    => 'multicheck',
				'options' => array(
					'S.F. calcs/exhibits' => __( 'S.F. calcs/exhibits', 'cmb' ),
					'BOMA charts'   => __( 'BOMA charts ', 'cmb' ),
					'LSBs'     => __( 'LSBs', 'cmb' ),
					'Mktg plans'     => __( 'Mktg plans', 'cmb' ),
					'Stacking plans'     => __( 'Stacking plans', 'cmb' ),
					'Surveys'     => __( 'Surveys', 'cmb' ),
					'Space plans'     => __( 'Space plans', 'cmb' ),
					'Pricing plans'     => __( 'Pricing plans', 'cmb' ),
					'Int. Design - high'     => __( 'Int. Design - high', 'cmb' ),
					'Int. Design - moderate'     => __( 'Int. Design - moderate', 'cmb' ),
					'Int. CDs'     => __( 'Int. CDs', 'cmb' ),
					'Int. Detailing'     => __( 'Int. Detailing', 'cmb' ),
					'CA/submittals'     => __( 'CA/submittals', 'cmb' ),
					'Arch. Design'     => __( 'Arch. Design', 'cmb' ),
					'Arch. CDs'     => __( 'Arch. CDs', 'cmb' ),
					'Arch. Detailing'     => __( 'Arch. Detailing', 'cmb' ),
					'Finish boards'     => __( 'Finish boards', 'cmb' ),
					'Illustrator - high'     => __( 'Illustrator - high', 'cmb' ),
					'Illustrator - moderate'     => __( 'Illustrator - moderate', 'cmb' ),
					'Photoshop - high'     => __( 'Photoshop - high', 'cmb' ),
					'Photoshop - moderate'     => __( 'Photoshop - moderate', 'cmb' ),
					'Sketchup - high'     => __( 'Sketchup - high', 'cmb' ),
					'Sketchup - moderate'     => __( 'Sketchup - moderate', 'cmb' ),
					'TDLR'     => __( 'TDLR', 'cmb' ),
					'IECC'     => __( 'IECC', 'cmb' ),
				),
			)
		)
	);


	// Add other metaboxes as needed
	return $meta_boxes;
}



// This will show below the color scheme and above username field
add_action( 'personal_options', 'stm_show_extra_profile_fields_to_options_section' );

function stm_show_extra_profile_fields_to_options_section( $user ) {
	$profile_post_id = esc_attr( get_the_author_meta( '_stm_timesheet_post_id', $user->ID ) );
	if($profile_post_id){
	$generated_profile_edit_url = get_edit_post_link($profile_post_id);
    // do something with it.
    ?>
    <h3><a class="button button-primary" href="<?php echo $generated_profile_edit_url; ?>">Click Here To View Timesheet</a></h3>
    <?php

	}
}


add_action('personal_options_update', 'update_stm_extra_profile_fields_to_datatable');
add_action('edit_user_profile_update', 'update_stm_extra_profile_fields_to_datatable');
//add_action( 'user_register', 'create_stm_extra_profile_fields_to_datatable' );

function create_stm_extra_profile_fields_to_datatable( $user_id ) {

    $timesheet_id = esc_attr( get_the_author_meta( '_stm_timesheet_post_id', $user_id ) );
	if( $timesheet_id == '' || $timesheet_id == 0 ) {

		$available_hours_monday = $available_hours_tuesday = $available_hours_wednesday = $available_hours_thursday = $available_hours_friday = 8;
		update_user_meta( $user_id, '_stm_available_hours_monday', $available_hours_monday );
		update_user_meta( $user_id, '_stm_available_hours_tuesday', $available_hours_tuesday );
		update_user_meta( $user_id, '_stm_available_hours_wednesday', $available_hours_wednesday );
		update_user_meta( $user_id, '_stm_available_hours_thursday', $available_hours_thursday );
		update_user_meta( $user_id, '_stm_available_hours_friday', $available_hours_friday );

		$team = 'Front Office';
		update_user_meta( $user_id, '_stm_team', $team );

		$user_first_name = $_POST['first_name'];
		$post_title = $user_first_name.'\'s Work Schedule';

		$defaults_timesheet = array(
		  'post_type'      => 'timesheet',
		  'post_title'     => $post_title,
		  'post_author'    => $user_id,
		  'post_status'      => 'publish'
		);

		if($timesheet_id = wp_insert_post( $defaults_timesheet )) {
			// add post meta data
			add_post_meta($timesheet_id, '_stm_employee_id', $user_id);
			//add user profile to post
			update_user_meta( $user_id, '_stm_timesheet_post_id', $timesheet_id );
			update_user_meta( $user_id, 'show_admin_bar_front', 'false' );

			stm_add_timesheet_member_data($user_id, $timesheet_id, $team, $available_hours_monday, $available_hours_tuesday, $available_hours_wednesday, $available_hours_thursday, $available_hours_friday, '');
		}

	}

}

function update_stm_extra_profile_fields_to_datatable($user_id) {
	if ( current_user_can('edit_user', $user_id) ){
		//echo $user_id;
		$employee_id = $user_id;
//		$timesheet_id = esc_attr( get_the_author_meta( '_stm_timesheet_post_id', $user_id ) );
//		$team = esc_attr( get_the_author_meta( '_stm_team', $user_id ) );
//		$monday_hour = esc_attr( get_the_author_meta( '_stm_available_hours_monday', $user_id ) );
//		$tuesday_hour = esc_attr( get_the_author_meta( '_stm_available_hours_tuesday', $user_id ) );
//		$wednesday_hour = esc_attr( get_the_author_meta( '_stm_available_hours_wednesday', $user_id ) );
//		$thursday_hour = esc_attr( get_the_author_meta( '_stm_available_hours_thursday', $user_id ) );
//		$friday_hour = esc_attr( get_the_author_meta( '_stm_available_hours_friday', $user_id ) );

		$timesheet_id = esc_attr( get_the_author_meta( '_stm_timesheet_post_id', $user_id ) );
		$team = esc_attr( $_POST['_stm_team'] );
		$monday_hour = esc_attr( $_POST['_stm_available_hours_monday'] );
		$tuesday_hour = esc_attr( $_POST['_stm_available_hours_tuesday'] );
		$wednesday_hour = esc_attr( $_POST['_stm_available_hours_wednesday'] );
		$thursday_hour = esc_attr( $_POST['_stm_available_hours_thursday'] );
		$friday_hour = esc_attr( $_POST['_stm_available_hours_friday'] );

		//$skills = esc_attr( get_the_author_meta( '_stm_skills', $user_id ) );
		$skills = '';
		if(! get_post_meta($timesheet_id, '_stm_employee_id', true)){

			$user_first_name = $_POST['first_name'];
			$post_title = $user_first_name.'\'s Work Schedule';
			$defaults_timesheet = array(
				  'post_type'      => 'timesheet',
				  'post_title'     => $post_title,
				  'post_author'    => $employee_id,
				  'post_status'      => 'publish'
			);
			if($timesheet_id = wp_insert_post( $defaults_timesheet )) {
				// add post meta data
				add_post_meta($timesheet_id, '_stm_employee_id', $employee_id);

				//add user profile to post
				update_user_meta( $employee_id, '_stm_timesheet_post_id', $timesheet_id );

				update_user_meta( $employee_id, 'show_admin_bar_front', 'false' );

				stm_add_timesheet_member_data($employee_id, $timesheet_id, $team, $monday_hour, $tuesday_hour, $wednesday_hour, $thursday_hour, $friday_hour, $skills);

			}

		}elseif($timesheet_id > 0 ){
			stm_add_timesheet_member_data($employee_id, $timesheet_id, $team, $monday_hour, $tuesday_hour, $wednesday_hour, $thursday_hour, $friday_hour, $skills);
		}
	}
}


function stm_filter_post_class( $classes ) {
	global $post;
	$current_user_id = get_current_user_id();
	$profile_post_id = esc_attr( get_the_author_meta( '_stm_timesheet_post_id', $current_user_id ) );
	$current_post_id = $post->ID;
    if($profile_post_id == $current_post_id)
		$classes[] = 'my_time_sheet';
	else
		$classes[] = 'not_my_time_sheet';

    return $classes;
}
add_filter( 'post_class', 'stm_filter_post_class' );


add_action( 'admin_head', 'stm_style_for_admin_head' );
function stm_style_for_admin_head(){
	if(current_user_can('employee')){
	?>
	<style type="text/css">
	 tr.not_my_time_sheet, #menu-posts-timesheet{ display:none; }
     </style>

    <?php
		if('timesheet' == get_post_type()){
			echo '<style type="text/css">
			#favorite-actions {display:none;}
			.add-new-h2{display:none;}
			.tablenav{display:none;}
			.page-title-action{display:none;}
			</style>';
		}

	}
}

function add_work_schedule_menu_item(){
    add_menu_page( 'My Schedule', 'My Schedule', 'employee', 'my-work-schedule', 'add_work_schedule_menu_item_redirect', '', 2 );
	if(current_user_can('employee')) {
		global $submenu;
		unset($submenu['edit.php?post_type=timesheet'][10]); // Removes 'Add New'.
	}
}
add_action( 'admin_menu', 'add_work_schedule_menu_item' );

function add_work_schedule_menu_item_redirect() {
	//if(current_user_can('employee')){
		$menu_redirect = isset($_GET['page']) ? $_GET['page'] : false;
		if($menu_redirect == 'my-work-schedule' ) {
		$current_user_id = get_current_user_id();
		$profile_post_id = esc_attr( get_the_author_meta( '_stm_timesheet_post_id', $current_user_id ) );
			wp_redirect("/wp-admin/post.php?post=".$profile_post_id."&action=edit&edit_schedule=own");
			exit;
		}
	//}
}
add_action( 'admin_init', 'add_work_schedule_menu_item_redirect', 1 );
