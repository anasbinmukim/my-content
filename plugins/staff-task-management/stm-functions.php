<?php

function debug($msg, $die=false) {
	echo "<pre>"; print_r($msg); echo "</pre>";
	if($die) die();
}

add_action('admin_init', 'stm_manual_update_from_admin');
function stm_manual_update_from_admin(){
	if(isset($_GET['stm_update']) && ($_GET['stm_update'] == 'profile')){
		$profile_id = $_GET['stm_profile_id'];
		$timesheet_id = $_GET['timesheet_id'];
		update_user_meta( $profile_id, '_stm_timesheet_post_id', $timesheet_id );
		//echo "Updated";
	}
}


function get_employee_arrays_for_cmb_metabox($role = 'employee'){
	$result = array();

	$user_query = new WP_User_Query( array( 'role' => $role ) );

	// User Loop
	if ( ! empty( $user_query->results ) ) {
		foreach ( $user_query->results as $user ) {
			$result[$user->ID] = $user->display_name;
		}
	}else{
		$result['1'] = 'Admin';
	}


	return $result;

}

function get_status_class($post_id){
	$terms = get_the_terms( $post_id, 'timesheet_status' );
	if ( $terms && ! is_wp_error( $terms ) ) :
		$status_name = array();
		foreach ( $terms as $term ) {
			$draught_links[] = $term->name;
		}
		$status_name = join( " ", $draught_links );
	endif;

	return $status_name;
}

global $employee_with_available_hours_array;
add_action( 'init', 'keep_titlesheet_auto_load_data' );
function keep_titlesheet_auto_load_data() {
	global $employee_with_available_hours_array;
	$employee_with_available_hours_array = employee_with_available_hours_array();
}



function employee_with_available_hours_array(){
	global $wpdb, $employee_with_available_hours_array;
	$table_timesheet = $wpdb->base_prefix . "stm_timesheet_member";

	$emp_with_team = array();
	$emp_without_team = array();
	$employee_with_available_hours_array = array();


	$current_user_id = get_current_user_id();
	$my_team = get_user_meta( $current_user_id, '_stm_team', true );
	$timesheet_data = $wpdb->get_results("SELECT employee_id FROM $table_timesheet WHERE (timesheet_id <> '')", ARRAY_A);

	if ( ! empty( $timesheet_data ) ) {
		foreach($timesheet_data as $timesheet){
			$employee_id = $timesheet['employee_id'];
			//$emp_with_team[$employee_id] = calculate_available_hours_for_week($employee_id);
			$emp_with_team[$employee_id] = get_user_total_available_hours($employee_id);

		}
		arsort($emp_with_team);
		foreach ($emp_with_team as $employee_id => $available_val) {
			$avai_rank = ' positive';
			if($available_val <= 0){$avai_rank = ' negative'; }
			$employee_info = get_userdata($employee_id);
			$employee_name = $employee_info->first_name;
			$employee_name .= ' '.$employee_info->last_name;
			$select_point = '';
			$avai_rank = ' positive';
			$employee_with_available_hours_array[$employee_id] = array('employee_name'=> $employee_name, 'available_val'=> $available_val, 'avai_rank'=> $avai_rank);
		}
	}

	return $employee_with_available_hours_array;
}



function get_employee_select_box($select_emp_id = 1, $due_row_id, $day = 'Monday'){

	$current_user_id = get_current_user_id();

	if(current_user_can('employee') && !get_user_meta( $current_user_id, '_stm_assign_to_other_permission', true )){
		$employee_info = get_userdata($current_user_id);
		$employee_name = $employee_info->first_name;
		$select_point = '';
		printf( '<select name="%s" id="%s" class="assignee">', esc_html( $due_row_id ), esc_html( $due_row_id ) );
		print( '<option value="0">Unassigned</option>');

		if($current_user_id == $select_emp_id ){ $select_point = ' selected="selected"'; }
		printf( '<option value="%d" %s>%s</option>', $current_user_id, $select_point, esc_html( $employee_name ));

		if($current_user_id != $select_emp_id){
			$employee_info = get_userdata($select_emp_id);
			$employee_name = $employee_info->first_name;
			$select_point = ' selected="selected"';
			printf( '<option value="%d" %s>%s</option>', $select_emp_id, $select_point, esc_html( $employee_name ));
		}
		printf( '</select><span class="new_avai_hours" id="new_hour_%s"></span>', esc_html( $due_row_id ) );

		return;
	}

	global $employee_with_available_hours_array;
	if ( ! empty( $employee_with_available_hours_array ) ) {
		printf( '<select name="%s" id="%s" class="assignee">', esc_html( $due_row_id ), esc_html( $due_row_id ) );
		print( '<option value="0">Unassigned</option>');

		foreach ($employee_with_available_hours_array as $employee_id => $emp_details) {


			$avai_rank = ' positive';
			if($emp_details['available_val'] <= 0){$avai_rank = ' negative'; }
			$employee_name = $emp_details['employee_name'];
			if($employee_id == $select_emp_id ){ $select_point = ' selected="selected"'; }
			printf( '<option id="%s_%d"  class="%s" value="%d" %s>%s ('.$emp_details['available_val'].')</option>', esc_html( $due_row_id ), $employee_id, esc_html( $avai_rank ), $employee_id, $select_point, esc_html( $employee_name ));
			$select_point = '';
			$avai_rank = ' positive';

		}

		printf( '</select><span class="new_avai_hours" id="new_hour_%s"></span>', esc_html( $due_row_id ) );
	}


}


function get_team_select_box($select_team_id, $due_row_id, $day = 'Monday'){
	$args = array('role__in' => array('project_manager'));
	$users = get_users( $args );
	foreach($users as $user){
		$team_leaders[$user->ID] = get_user_meta($user->ID, 'last_name', true);
	}

	$current_user_id = get_current_user_id();
	$current_user_info = get_userdata($current_user_id);

	if((($select_team_id == 0) || ($select_team_id == '')) && (in_array('project_manager', $current_user_info->roles))){
		$select_team_id = $current_user_id;
	}elseif($select_team_id > 0){
		$select_team_id = $select_team_id;
	}else {
		$select_team_id = get_the_author_meta( '_stm_team', $current_user_id );
	}

	if ( ! empty( $team_leaders ) ) {
		printf( '<select name="%s" id="%s" class="assigned_by_team">', esc_html( $due_row_id ), esc_html( $due_row_id ) );
		echo '<option value="">Select PM</option>';
		foreach ($team_leaders as $employee_id => $emp_name) {

			if($employee_id == $select_team_id ){ $select_point = ' selected="selected"'; }
			printf( '<option id="%s_%d"  class="" value="%d" %s>%s</option>', esc_html( $due_row_id ), $employee_id, $employee_id, $select_point, esc_html( $emp_name ));
			$select_point = '';
		}

		printf( '</select>' );
	}


}




function get_due_date_select_box($day = 'Monday', $day_row_id){

	$date_arr = array('Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday');

	// User Loop
	if ( ! empty( $date_arr ) ) {

		printf( '<select name="%s" id="%s" class="due_date">', esc_html( $day_row_id ), esc_html( $day_row_id ) );
		$select_point = '';
		foreach ( $date_arr as $due_day ) {
			if($due_day == $day ){ $select_point = ' selected="selected"'; }
			printf( '<option value="%s" %s>%s</option>', esc_html( $due_day ), $select_point, esc_html( $due_day ) );
			$select_point = '';
		}
		print( '</select>' );


	}

}



function create_timesheet_row_html($set_row_id, $day, $select_emp_id, $timesheet_id = '', $delete_option = false) {
	global $wpdb;
	$timesheet_detais = get_timesheet_details_by_id($timesheet_id);
	$table_timesheet_member = $wpdb->base_prefix . "stm_timesheet_member";
	$timesheet_data = $wpdb->get_results("SELECT * FROM $table_timesheet_member where timesheet_id =  ".$_GET['post'], ARRAY_A);
	$timesheetUserId = $timesheet_data[0]['employee_id'];

	$client_name = '';
	$task_pm = '';
	$task_name = '';
	$hours = '';
	$status = 'open';

	if(isset($timesheet_detais['client_name']))
		$client_name = $timesheet_detais['client_name'];
	if(isset($timesheet_detais['my_task']))
		$task_name = $timesheet_detais['my_task'];
	if(isset($timesheet_detais['estimate_hour']))
		$hours = $timesheet_detais['estimate_hour'];
	if(isset($timesheet_detais['due_date']))
		$day = $timesheet_detais['due_date'];
	if(isset($timesheet_detais['assignee']))
		$select_emp_id = $timesheet_detais['assignee'];
	if(isset($timesheet_detais['status']))
		$status = $timesheet_detais['status'];
	if(isset($timesheet_detais['assigned_by']))
		$assigned_by = $timesheet_detais['assigned_by'];
	if(isset($timesheet_detais['task_pm']))
		$task_pm = $timesheet_detais['task_pm'];

	if(isset($timesheet_detais['created_by'])){
		$created_by_id = $timesheet_detais['created_by'];
		$creator_user_info = get_userdata($created_by_id);
		$creator_name = $creator_user_info->first_name;
	}

	//echo $select_emp_id." ";
	//echo '-'. $assigned_by. ' ';

	$current_user_id = get_current_user_id();
	$allow_like_team = false;
	if(get_user_meta( $current_user_id, '_stm_assign_own_tasks', true )){
		$allow_like_team = TRUE;
	}


	$class_assigned_by_me = '';
	if(! $task_name){
		$class_assigned_by_me = ' unassigned_no_task ';
	}elseif($task_name && ($select_emp_id == 0)){
		$class_assigned_by_me = ' unassigned_task ';
	}elseif($select_emp_id == $assigned_by){
		$class_assigned_by_me = ' assigned_by_me ';
	}elseif($select_emp_id != $assigned_by){
		$class_assigned_by_me = ' assigned_by_others ';
	}else{
		$class_assigned_by_me = ' unassigned_task ';
	}

	$assign_by_user_info = get_userdata($assigned_by);
	$assigned_by_name = $assign_by_user_info->first_name;
	$assigned_by_name .= ' '.$assign_by_user_info->last_name;

	//if(($select_emp_id == "") || ($select_emp_id == $timesheetUserId) || ($select_emp_id == 0)) {


		?>
		<tr data-timesheet_id="<?php echo $timesheet_id; ?>" id="before-tr-row<?php echo $set_row_id; ?>"><td colspan="6" style="border-top:none; padding:0;"><div class="message_row_box" id="save_message_row<?php echo $set_row_id; ?>"></div></td></tr>
		<tr id="tr_row<?php echo $set_row_id; ?>" class="status_<?php echo $status; ?> <?php echo $class_assigned_by_me; ?>" data-timesheet_id="<?php echo $timesheet_id; ?>">
			<input type="hidden" name="db_row<?php echo $set_row_id; ?>" id="db_row<?php echo $set_row_id; ?>" value="<?php echo $timesheet_id; ?>" />
            <input type="hidden" name="task_day_row<?php echo $set_row_id; ?>" id="task_day_row<?php echo $set_row_id; ?>" value="<?php echo $day; ?>" />
			<input type="hidden" name="section_row<?php echo $set_row_id; ?>" id="section_row<?php echo $set_row_id; ?>" value="<?php echo $day; ?>" />
            <input type="hidden" name="timesheet_user_id_row<?php echo $set_row_id; ?>" id="timesheet_user_id_row<?php echo $set_row_id; ?>" value="<?php echo $timesheetUserId; ?>" />
			<td><input type="text" class="medium-text" name="cname_row<?php echo $set_row_id; ?>" id="cname_row<?php echo $set_row_id; ?>" value="<?php echo stripslashes($client_name); ?>" placeholder="" /></td>
			<td><input type="text" class="medium-text" name="task_row<?php echo $set_row_id; ?>" id="task_row<?php echo $set_row_id; ?>" value="<?php echo stripslashes($task_name); ?>" placeholder="" /></td>
			<td><input type="text" class="small-text" name="hours_row<?php echo $set_row_id; ?>" id="hours_row<?php echo $set_row_id; ?>" value="<?php echo $hours; ?>" placeholder="" /></td>

			<td>
			<?php
				$due_row_id = 'assignee_row'.$set_row_id;
				get_employee_select_box($select_emp_id, $due_row_id, $day);
			?>
				<?php if($select_emp_id){ ?><br /><a href="<?php echo get_edit_user_link( $select_emp_id ); ?>">Profile</a><?php } ?>
			</td>
			<?php if($allow_like_team){ ?>
			<td>
			<?php }else{ ?>
			<td style="display:none;">
			<?php } ?>
			<?php
				$due_row_id = 'assigned_by_team_row'.$set_row_id;
				get_team_select_box($task_pm, $due_row_id, $day);
			?>
			</td>
			<td>
				<?php
					$day_row_id = 'due_row'.$set_row_id;
					get_due_date_select_box($day, $day_row_id);
				?>
			</td>
			<td align="center"><a href="javascript:void(0)" class="primary button update_timesheet_row" id="row<?php echo $set_row_id; ?>">Save Task</a>

            <?php if($delete_option) {?>
            &nbsp;<a href="javascript:void(0)" class="primary button delete_timesheet_row" data-timesheet_id="<?php echo $timesheet_id; ?>">Delete</a>
            <?php }?>

            </td>
		</tr>
		<tr data-timesheet_id="<?php echo $timesheet_id; ?>"><td colspan="6" style="border-top:none; padding:0; text-align:center;"><?php if($creator_name){ ?><span style="font-size:12px; font-style:italic;">Assigned by <?php echo $assigned_by_name; ?></span><?php } if($timesheet_id == 'noneedfornow'){ ?><span style="text-align:right; font-size:12px; float:right;">Mark as <a href='javascript:void(0)' class='update_status' data-status='Review' data-row_id='row<?php echo $set_row_id; ?>' data-task_id="<?php echo $timesheet_id; ?>">Pending Review</a> | <a href='javascript:void(0)' class='update_status' data-status='Complete' data-row_id='row<?php echo $set_row_id; ?>' data-task_id="<?php echo $timesheet_id; ?>">Complete</a></span><?php } ?></td></tr>


		<?php
	//}
}



function stm_add_timesheet_data( $client_name, $task_name, $hours, $due, $assignee, $task_assigned_day, $timesheet_user_id, $assign_by_team = 0){
	global $wpdb;
	$table = $wpdb->base_prefix . "stm_timesheet";

	$date_and_time = date('Y-m-d h:i:s A');
	$project_manager = '';
	if($assign_by_team != 0){
		$project_manager = $assign_by_team;
		$created_by = $assign_by_team;
	}else{
		$project_manager = get_current_user_id();
		$created_by = get_current_user_id();
	}

	$task_c_date = current_time( 'mysql' );
	$datetime = new DateTime($task_c_date);
	$task_c_day = $datetime->format('l'); // Tuesday

	$wpdb->query($wpdb->prepare("INSERT INTO " . $wpdb->base_prefix . "stm_timesheet (client_name, my_task, estimate_hour, due_date, assignee, create_date_time, created_by, assigned_by, timesheet_user_id, assigned_date, assigned_day, task_day, task_pm) VALUES (%s, %s, %s, %s, %d, %s, %d, %d, %d, %s, %s, %s, %d)", $client_name, $task_name, $hours, $due, $assignee, $date_and_time, $created_by, $created_by, $timesheet_user_id, $task_c_date, $task_assigned_day, $task_c_day, $project_manager));

    $last = $wpdb->get_row("SHOW TABLE STATUS LIKE '$table'");
    $lastid = $last->Auto_increment;
	$lastid_timesheet_id = $lastid - 1;


	$wpdb->query($wpdb->prepare("INSERT INTO " . $wpdb->base_prefix . "stm_timesheet_relation (timesheet_id, assignee) VALUES (%d, %d)", $lastid_timesheet_id, $assignee));

	//update log data
	stm_add_timesheet_assignee_log($lastid_timesheet_id, $assignee);

	return $lastid_timesheet_id;


}

function stm_update_timesheet_data($client_name, $task_name, $hours, $due, $assignee, $timesheet_id, $task_assigned_day, $timesheet_user_id, $assign_by_team = 0){

	global $wpdb;
	$table = $wpdb->base_prefix . "stm_timesheet";

	//assign by team will work as a project manager
	$project_manager = '';
	if($assign_by_team != 0){
		$project_manager = $assign_by_team;
	}

	//update log data
	stm_add_timesheet_assignee_log($timesheet_id, $assignee);

	$task_c_date = current_time( 'mysql' );
	$datetime = new DateTime($task_c_date);
	$task_c_day = $datetime->format('l'); // Tuesday

	//$wpdb->query($wpdb->prepare("UPDATE ".$wpdb->base_prefix."stm_timesheet SET `client_name` = %s, `my_task` = %s, `estimate_hour` = %s, `due_date` = %s, `assignee` = %d, `assigned_by` = %d, `timesheet_user_id` = %d, `assigned_date` = %s, `assigned_day` = %s, `task_day` = %s WHERE `ID` = %d", $client_name, $task_name, $hours, $due, $assignee, $assigned_by, $timesheet_user_id, $task_c_date, $task_assigned_day, $task_c_day,  $timesheet_id));
	$wpdb->query($wpdb->prepare("UPDATE ".$wpdb->base_prefix."stm_timesheet SET `client_name` = %s, `my_task` = %s, `estimate_hour` = %s, `due_date` = %s, `assignee` = %d, `timesheet_user_id` = %d, `task_day` = %s, `task_pm` = %d WHERE `ID` = %d", $client_name, $task_name, $hours, $due, $assignee, $timesheet_user_id, $task_c_day, $project_manager,  $timesheet_id));

	//check assignee update
	$already_assigned_id = $wpdb->get_row($wpdb->prepare("SELECT ID FROM ".$wpdb->base_prefix."stm_timesheet_relation WHERE assignee = %s AND timesheet_id = %d", $assignee, $timesheet_id));

	if(empty($already_assigned_id) ){
		$wpdb->query($wpdb->prepare("INSERT INTO " . $wpdb->base_prefix . "stm_timesheet_relation (timesheet_id, assignee) VALUES (%d, %d)", $timesheet_id, $assignee));
	}

	return true;

}


function stm_add_timesheet_assignee_log($timesheet_id, $employee_id){

	global $wpdb;
	$date_and_time = date('Y-m-d h:i:s A');
	$assigned_by = get_current_user_id();


	//check if same assignee
	//$already_assigned_id = $wpdb->get_row($wpdb->prepare("SELECT employee_id FROM ".$wpdb->base_prefix."stm_timesheet WHERE employee_id = %d ORDER BY ID DESC LIMIT 1", $employee_id));
	$already_assigned_id = $wpdb->get_row($wpdb->prepare("SELECT assignee FROM ".$wpdb->base_prefix."stm_timesheet WHERE ID = %d", $timesheet_id));

	if(isset($already_assigned_id->assignee) && ($already_assigned_id->assignee == $employee_id)){
		//No change
	}else{
		$wpdb->query($wpdb->prepare("INSERT INTO " . $wpdb->base_prefix . "stm_timesheet_assign_log (timesheet_id, employee_id, assigned_by, update_date_time) VALUES (%d, %d, %d, %s)", $timesheet_id, $employee_id, $assigned_by, $date_and_time));
		return true;
	}
}



// get timesheet details by id
function get_timesheet_details_by_id($timesheet_id){
	global $wpdb;
	$details = array();

	$table_timesheet = $wpdb->base_prefix . "stm_timesheet";

	$timesheet_data = $wpdb->get_results("SELECT * FROM $table_timesheet WHERE ID = '$timesheet_id'");

	$details['ID'] = $timesheet_data[0]->ID;
	$details['client_name'] = $timesheet_data[0]->client_name;
	$details['my_task'] = $timesheet_data[0]->my_task;
	$details['estimate_hour'] = $timesheet_data[0]->estimate_hour;
	$details['due_date'] = $timesheet_data[0]->due_date;
	$details['assignee'] = $timesheet_data[0]->assignee;
	$details['create_date_time'] = $timesheet_data[0]->create_date_time;
	$details['created_by'] = $timesheet_data[0]->created_by;
	$details['assigned_by'] = $timesheet_data[0]->assigned_by;
	$details['task_pm'] = $timesheet_data[0]->task_pm;
	$details['status'] = $timesheet_data[0]->status;

	return $details;
}

// get last assignee by id
function get_last_assignee_log($timesheet_id){
	global $wpdb;
	$details = array();

	$table_timesheet_log = $wpdb->base_prefix . "stm_timesheet_assign_log";

	$timesheet_data = $wpdb->get_results("SELECT * FROM $table_timesheet_log WHERE timesheet_id = '$timesheet_id' ORDER BY ID DESC LIMIT 1");

	$details['ID'] = $timesheet_data[0]->ID;
	$details['timesheet_id'] = $timesheet_data[0]->timesheet_id;
	$details['employee_id'] = $timesheet_data[0]->employee_id;
	$details['assigned_by'] = $timesheet_data[0]->assigned_by;
	$details['update_date_time'] = $timesheet_data[0]->update_date_time;

	return $details;
}

// return total assigned hours
function calculate_assigned_hours_for_week($employee_id){
	global $wpdb;
	$total_available_hours = 0;
	$total_estimate_hours = 0;

	$table_timesheet = $wpdb->base_prefix . "stm_timesheet";
	$timesheet_data = $wpdb->get_results("SELECT estimate_hour FROM $table_timesheet WHERE assignee = '$employee_id'", ARRAY_A);

	foreach($timesheet_data as $timesheet){
		$estimate_hours = $timesheet['estimate_hour'];
		$total_estimate_hours += $estimate_hours;
	}

	return $total_estimate_hours;
}

// return total avaialble hours
function get_total_available_hours($employee_id){
	global $wpdb;
	$total_available_hours = 0;
	$total_estimate_hours = 0;

//	$table_timesheet = $wpdb->base_prefix . "stm_timesheet";
//
//	$timesheet_data = $wpdb->get_results("SELECT estimate_hour FROM $table_timesheet WHERE assignee = '$employee_id'", ARRAY_A);
//	//print_r($timesheet_data);
//
//	foreach($timesheet_data as $timesheet){
//		$estimate_hours = $timesheet['estimate_hour'];
//		$total_estimate_hours += $estimate_hours;
//	}
//
//	$available_hours = get_user_meta( $employee_id, '_stm_available_hours', true );
//
//	$total_available_hours = $available_hours - $total_estimate_hours;


	$total_working_hours = get_total_available_employee_hour_a_week($employee_id);
	$total_assigned_hours = calculate_assigned_hours_for_week($employee_id);
	$total_available_hours = $total_working_hours - $total_assigned_hours;
	if($total_available_hours < 0 )
		$total_available_hours = 0;

	return $total_available_hours;
}

function get_user_total_available_hours($employee_id){
	global $wpdb;
	$total_available_hours = 0;
	$total_estimate_hours = 0;

	$total_working_hours = get_total_available_employee_hour_a_week($employee_id);
	$total_assigned_hours = calculate_assigned_hours_for_week($employee_id);
	$total_available_hours = $total_working_hours - $total_assigned_hours;
	if($total_available_hours < 0 )
		$total_available_hours = 0;

	return $total_available_hours;
}

// return total avaialble hours
function get_hours_a_day($employee_id, $day){
	global $wpdb;
	$hours = 0;

	if($day == 'Monday')
		$hours = get_user_meta( $employee_id, '_stm_available_hours_monday', true );
	if($day == 'Tuesday')
		$hours = get_user_meta( $employee_id, '_stm_available_hours_tuesday', true );
	if($day == 'Wednesday')
		$hours = get_user_meta( $employee_id, '_stm_available_hours_wednesday', true );
	if($day == 'Thursday')
		$hours = get_user_meta( $employee_id, '_stm_available_hours_thursday', true );
	if($day == 'Friday')
		$hours = get_user_meta( $employee_id, '_stm_available_hours_friday', true );

	return $hours;
}

// return total avaialble hours
function get_total_available_employee_hour_a_week($employee_id){
	global $wpdb;
	$hours = 0;

	$hours += get_user_meta( $employee_id, '_stm_available_hours_monday', true );
	$hours += get_user_meta( $employee_id, '_stm_available_hours_tuesday', true );
	$hours += get_user_meta( $employee_id, '_stm_available_hours_wednesday', true );
	$hours += get_user_meta( $employee_id, '_stm_available_hours_thursday', true );
	$hours += get_user_meta( $employee_id, '_stm_available_hours_friday', true );

	return $hours;
}

// return total avaialble hours
function get_total_available_hours_a_day($employee_id, $day){
	global $wpdb;
	$total_available_hours = 0;
	$total_estimate_hours = 0;
	//$due_day_interval = array();

	$table_timesheet = $wpdb->base_prefix . "stm_timesheet";

	$timesheet_data = $wpdb->get_results("SELECT estimate_hour, assigned_day FROM $table_timesheet WHERE assignee = '$employee_id' AND due_date = '$day'", ARRAY_A);
	//print_r($timesheet_data);

	foreach($timesheet_data as $timesheet){
		$estimate_hours = $timesheet['estimate_hour'];

		/*$due_day_interval = get_number_of_days_between_due_and_assigned_date( $timesheet['assigned_day'], $day );
		$estimate_hours = sprintf ("%.2f", ($estimate_hours/$due_day_interval));*/

		$total_estimate_hours += $estimate_hours;

		/*$weekdays = array('Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday');
		if($due_day_interval > 1) {
			for($i = 1; $i < $due_day_interval; $i++) {
				$prev_day = date("l", strtotime($day."-".$i."days"));
			}
		}*/
	}

	$available_hours = get_hours_a_day($employee_id, $day);

	$total_available_hours = $available_hours - $total_estimate_hours;

	return $total_available_hours;
}

// return total assigned hours a day
function get_total_assigned_hours_a_day($employee_id, $day){
	global $wpdb;
	$total_estimate_hours = 0;
	$table_timesheet = $wpdb->base_prefix . "stm_timesheet";
	$timesheet_data = $wpdb->get_results("SELECT estimate_hour FROM $table_timesheet WHERE assignee = '$employee_id' AND due_date = '$day'", ARRAY_A);
	foreach($timesheet_data as $timesheet){
		$estimate_hours = $timesheet['estimate_hour'];
		$total_estimate_hours += $estimate_hours;
	}

	return $total_estimate_hours;
}


// return total over headhour a day
function get_total_over_head_hours_a_day($employee_id, $day){
	$total_overhead_hours = 0;

	$emp_set_hours = get_hours_a_day($employee_id, $day);
	$emp_assigned_hours = get_total_assigned_hours_a_day($employee_id, $day);

	$total_overhead_hours = $emp_set_hours - $emp_assigned_hours;

	if($total_overhead_hours >= 0)
		$total_overhead_hours = 0;
	else
		$total_overhead_hours = $total_overhead_hours;

	//retrun value if negative value zero otherwise
	return abs($total_overhead_hours);
}



//add_action("init", "get_total_avilable_hours_by_dividing_estimate_hours");
function get_total_avilable_hours_by_dividing_estimate_hours($employee_id, $day) {
	if($_GET['get_days'] == 'do') {
	$days = get_day_names_between_assign_and_due_date( "Thursday", "Monday" );
	print_r($days);
	}

	/*$days = get_day_names_between_assign_and_due_date( "Monday", "Friday" );

	global $wpdb;
	$total_available_hours = 0;
	$total_estimate_hours = 0;
	//$due_day_interval = array();

	$table_timesheet = $wpdb->base_prefix . "stm_timesheet";

	$timesheet_data = $wpdb->get_results("SELECT estimate_hour, assigned_day FROM $table_timesheet WHERE assignee = '$employee_id' AND due_date = '$day'", ARRAY_A);
	//print_r($timesheet_data);

	foreach($timesheet_data as $timesheet){
		$estimate_hours = $timesheet['estimate_hour'];
		$total_estimate_hours += $estimate_hours;
	}

	$available_hours = get_hours_a_day($employee_id, $day);

	$total_available_hours = $available_hours - $total_estimate_hours;

	return $total_available_hours;*/
}

function get_day_names_between_assign_and_due_date( $assigned_day, $due_day ) {
	$number_of_days = get_number_of_days_between_due_and_assigned_date( $assigned_day, $due_day );

	$weekdays = array('Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday');
	$assign = get_day_number_of_the_week($assigned_day);
	$due = get_day_number_of_the_week($due_day);
	if($assign > $due) {
		$assigned_day = 'Monday';
	}

	$assigned_day_pos = array_search($assigned_day, $weekdays);
	//$due_day_pos = array_search($due_day, $weekdays);

	$day_names = array();

	if($number_of_days > 0) {
		/*if($assigned_day_pos > $due_day_pos) {

			for($i = 0; $i < $number_of_days; $i++) {
				//if( $due_day_pos == 0 ) $prev_pos = 4;
				//else $prev_pos = ($due_day_pos - $i);
				$next_pos = ($assigned_day_pos + $i);

				if( ($next_pos == 5) ) $next_pos = 0;
				if( ($next_pos == 6) ) $next_pos = 1;
				if( ($next_pos == 7) ) $next_pos = 2;
				if( ($next_pos == 8) ) $next_pos = 3;
				if( ($next_pos == 9) ) $next_pos = 4;
				//if( ($prev_pos == -2) ) $prev_pos = 3;

				$day_names[] = $weekdays[$next_pos];
				//$day_names[] = date("l", strtotime($due_day."-".$i."days"));
			}

			if( in_array($assigned_day, $day_names) ) {
				$key = array_search($assigned_day, $day_names);
				//array_push($day_names, $assigned_day);
				unset($day_names[$key]);
				$day_names = array_values($day_names);
				array_unshift($day_names, $assigned_day);
			}
		}*/
		//elseif($assigned_day_pos < $due_day_pos) {
			// $i=1 to skip the last day, because we know the assigned day & due day    ( returns -[assign/due days] )
			for($i = 0; $i < $number_of_days; $i++) {
				$index = ($i + $assigned_day_pos);
				$day_names[] = $weekdays[$index];
			}

			/*if( !in_array($assigned_day, $day_names) ) {
				array_unshift($day_names, $assigned_day);
			}*/

		//}
	}


	// return the day name including the assinged day name
	/*if( in_array($assigned_day, $day_names) ) {
		$key = array_search($assigned_day, $day_names);
		//array_push($day_names, $assigned_day);
		unset($day_names[$key]);
		$day_names = array_values($day_names);
		array_unshift($day_names, $assigned_day);
	}
	if( !in_array($due_day, $day_names) ) {
		array_push($day_names, $due_day);
	}*/
	return $day_names;
}

function get_number_of_days_between_due_and_assigned_date( $assigned_day, $due_day ) {
	/*$datetime1 = new DateTime($timesheet['assigned_day']);
	$datetime2 = new DateTime($day);
	$interval = $datetime1->diff($datetime2);
	$number_of_days = $interval->format('%a'); //format('%a days')
	*/

	$assigned = get_day_number_of_the_week($assigned_day);
	$due = get_day_number_of_the_week($due_day);

	if($due < $assigned) {
		$assigned = 1; // 1 = Monday in PHP date format
	}
	$number_of_days = (($due - $assigned) + 1); // +1 for matching the actual number of days

	/*if( $number_of_days == -4 ) {
		$number_of_days = 2;
	} elseif( $number_of_days == -3 ) {
		$number_of_days = 3;
	} elseif( $number_of_days == -2 ) {
		$number_of_days = 4;
	} elseif( $number_of_days == -1 ) {
		$number_of_days = 5;
	} elseif( $number_of_days == 0 ) { // Friday - Friday
		$number_of_days = 1;
	}*/

	return $number_of_days;
}


function get_day_number_of_the_week($day) {
	$day_number = date("w", strtotime($day));
	return $day_number;
}


// return total avaialble hours
function get_total_marge_available_hours_a_day($employee_id, $day){
	global $wpdb;
	$total_available_hours = 0;
	$total_estimate_hours = 0;

	$table_timesheet = $wpdb->base_prefix . "stm_timesheet";

	$timesheet_data = $wpdb->get_results("SELECT estimate_hour FROM $table_timesheet WHERE assignee = '$employee_id' AND due_date = '$day'", ARRAY_A);
	//print_r($timesheet_data);

	foreach($timesheet_data as $timesheet){
		$estimate_hours = $timesheet['estimate_hour'];
		$total_estimate_hours += $estimate_hours;
	}


	$available_hours = get_hours_a_day($employee_id, $day);


	if($day == 'Friday'){
		$marge_hour = $total_estimate_hours / 5;
	}elseif($day == 'Thursday'){
		$marge_hour = $total_estimate_hours / 4;
	}elseif($day == 'Wednesday'){
		$marge_hour = $total_estimate_hours / 3;
	}elseif($day == 'Tuesday'){
		$marge_hour = $total_estimate_hours / 2;
	}elseif($day == 'Monday'){
		$marge_hour = $total_estimate_hours;
		//$total_available_hours = $available_hours - $total_estimate_hours;
	}





//	if($total_estimate_hours > $available_hours){
//		//marge hour to previous day
//	}else{
//		$total_available_hours = $available_hours - $total_estimate_hours;
//	}


	return $total_available_hours;
}





function calculate_available_hours_for_day($employee_id, $day, $section_day = ''){
	$hours = 0;

	$available_hours_monday = get_total_available_hours_a_day($employee_id, 'Monday');
	$available_hours_tuesday = get_total_available_hours_a_day($employee_id, 'Tuesday');
	$available_hours_wednesday = get_total_available_hours_a_day($employee_id, 'Wednesday');
	$available_hours_thursday = get_total_available_hours_a_day($employee_id, 'Thursday');
	$available_hours_friday = get_total_available_hours_a_day($employee_id, 'Friday');


	if($section_day == '')
		$today = date("l");
	else
		$today = $section_day;



	if($day == 'Monday'){
		$hours = $available_hours_monday;
	}
	if($day == 'Tuesday'){
		if($today == 'Tuesday'){
			$available_hours_monday = 0;
		}
		$hours = $available_hours_monday + $available_hours_tuesday;
	}
	if($day == 'Wednesday'){
		if($today == 'Wednesday'){
			$available_hours_monday = 0;
			$available_hours_tuesday = 0;
		}
		$hours = $available_hours_monday + $available_hours_tuesday + $available_hours_wednesday;
	}
	if($day == 'Thursday'){
		if($today == 'Thursday'){
			$available_hours_monday = 0;
			$available_hours_tuesday = 0;
			$available_hours_wednesday = 0;
		}
		$hours = $available_hours_monday + $available_hours_tuesday + $available_hours_wednesday + $available_hours_thursday;
	}
	if($day == 'Friday'){
		if($today == 'Friday'){
			$available_hours_monday = 0;
			$available_hours_tuesday = 0;
			$available_hours_wednesday = 0;
			$available_hours_thursday = 0;
		}
		$hours = $available_hours_monday + $available_hours_tuesday + $available_hours_wednesday + $available_hours_thursday + $available_hours_friday;
	}

	return $hours;
}


function calculate_available_hours_for_week($employee_id){
	$hours = 0;

	$available_hours_monday = get_total_available_hours_a_day($employee_id, 'Monday');
	$available_hours_tuesday = get_total_available_hours_a_day($employee_id, 'Tuesday');
	$available_hours_wednesday = get_total_available_hours_a_day($employee_id, 'Wednesday');
	$available_hours_thursday = get_total_available_hours_a_day($employee_id, 'Thursday');
	$available_hours_friday = get_total_available_hours_a_day($employee_id, 'Friday');

	$hours = $available_hours_monday + $available_hours_tuesday + $available_hours_wednesday + $available_hours_thursday + $available_hours_friday;

	return $hours;
}

function stm_add_timesheet_member_data($employee_id, $timesheet_id, $team, $monday_hour, $tuesday_hour, $wednesday_hour, $thursday_hour, $friday_hour, $skills){

	global $wpdb;
	$table = $wpdb->base_prefix . "stm_timesheet_member";

	//check assignee update
	$already_added_id = $wpdb->get_row($wpdb->prepare("SELECT ID FROM ".$wpdb->base_prefix."stm_timesheet_member WHERE employee_id = %d AND timesheet_id = %d", $employee_id, $timesheet_id));

	if($already_added_id){
		$wpdb->query($wpdb->prepare("UPDATE ".$wpdb->base_prefix."stm_timesheet_member SET `team` = %s, `monday_hour` = %d, `tuesday_hour` = %d, `wednesday_hour` = %d, `thursday_hour` = %d, `friday_hour` = %d, `skills` = %s WHERE `employee_id` = %d", $team, $monday_hour, $tuesday_hour, $wednesday_hour, $thursday_hour, $friday_hour, $skills, $employee_id));
	}else{
		$wpdb->query($wpdb->prepare("INSERT INTO " . $wpdb->base_prefix . "stm_timesheet_member (employee_id, timesheet_id, team, monday_hour, tuesday_hour, wednesday_hour, thursday_hour, friday_hour, skills) VALUES (%d, %d, %s, %d, %d, %d, %d, %d, %s)", $employee_id, $timesheet_id, $team, $monday_hour, $tuesday_hour, $wednesday_hour, $thursday_hour, $friday_hour, $skills));
	}

}



function stm_clear_timesheet_data($employee_id){
		global $wpdb;

		$table_timesheet = $wpdb->base_prefix . "stm_timesheet";
		$wpdb->query("DELETE FROM $table_timesheet WHERE (assignee = $employee_id) OR (created_by = $employee_id)");

		$table_timesheet_relation = $wpdb->base_prefix . "stm_timesheet_relation";
		$wpdb->query("DELETE FROM $table_timesheet_relation WHERE assignee = $employee_id");

		return true;

}

function stm_delete_timesheet_data($timesheet_id) {
	global $wpdb;

	$table_timesheet = $wpdb->base_prefix . "stm_timesheet";
	$wpdb->query("DELETE FROM $table_timesheet WHERE ID = $timesheet_id");

	$table_timesheet_relation = $wpdb->base_prefix . "stm_timesheet_relation";
	$wpdb->query("DELETE FROM $table_timesheet_relation WHERE timesheet_id = $timesheet_id");

	return true;
}


function stm_clear_unassigned_timesheet_data(){
		global $wpdb;
		$employee_id = 0;

		$table_timesheet = $wpdb->base_prefix . "stm_timesheet";
		$wpdb->query("DELETE FROM $table_timesheet WHERE (assignee = $employee_id) OR (created_by = $employee_id)");
		$table_timesheet_relation = $wpdb->base_prefix . "stm_timesheet_relation";
		$wpdb->query("DELETE FROM $table_timesheet_relation WHERE assignee = $employee_id");
		return true;
}



function stm_update_timesheet_status($status, $timesheet_id){

	global $wpdb;
	$table = $wpdb->base_prefix . "stm_timesheet";

	$wpdb->query($wpdb->prepare("UPDATE ".$wpdb->base_prefix."stm_timesheet SET `status` = %s WHERE `ID` = %d", $status, $timesheet_id));

	return true;

}


class PDF extends TCPDF {
	var $head;
	var $foot;

	// Page header
	function Header() {
		if($this->head != "no") {
			$this->SetFont('helvetica', 'B', 28);
			$this->Cell(0,10,'CONTRAN PHONE LIST',0,1,'C');
			$this->Line(5, 22, 210-5, 22);

			$this->SetFont('helvetica', '', 12);
			$this->SetTextColor(30, 30, 30);
			$this->Cell(0,15,'(First Name, Last Name, Extension)',0,1,'C');
		}
	}

	// Page footer
	function Footer() {
		if($this->foot != "no") {
			// Position at 1.5 cm from bottom
			$this->SetY(-15);
			$this->SetFont('helvetica', 'B', 10);

			$this->Cell(0,5,'EXTERNAL PHONE NUMBER: 972-450-42xx, 972-934-53xx, 972-448-14xx, 214-313-2xxx',0,0,'C');
			$this->Ln();
			$this->Cell(0,5,'THREE LINCOLN CENTER, 5430 LBJ FREEWAY, SUITE 1700, DALLAS TX 75240',0,0,'C');

			$this->Ln();
			$this->Cell(0,5,$lastPage,0,0,'C');
		}
	}
}
add_action( 'wp_ajax_send_schedule_email', 'generate_work_schedule_pdf' );
add_action('admin_init', 'generate_work_schedule_pdf');
function generate_work_schedule_pdf() {
	$single_id = '';
	if(!empty($_POST['single_id'])){
		$single_id = $_POST['single_id'];
		$_POST[$_POST['sub_action']] = 'do';
	}
	if( isset($_POST['emp_email_pdf']) && ($_POST['emp_email_pdf'] == 'do') ) {
		global $wpdb;
		$employees = get_employees($single_id);
		foreach($employees as $employee_id) {
			$employee_user_info = get_userdata($employee_id);
			//debug($employee_user_info->user_role[0]);
			$employee_name = $employee_user_info->display_name;

			$table_timesheet = $wpdb->base_prefix . "stm_timesheet";
			$timesheet_arr = $wpdb->get_results("SELECT DISTINCT ID, client_name, my_task, estimate_hour, due_date, assigned_day, assignee, created_by, assigned_by FROM $table_timesheet WHERE ( created_by = '$employee_id' OR assignee = '$employee_id' ) ORDER BY FIELD(assigned_day, 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday')", ARRAY_A);

			$pdf = new PDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
			$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

			$pdf->head = "no";
			$pdf->foot = "no";
			$pdf->AddPage();
			$pdf->SetFont('helvetica','B',10);
			$pdf->SetTextColor(0, 0, 0);

			//$html = "<p style=\"font-size:14px; font-weight:bold;\">".$employee_name."'s Work Schedule</p>";
			$frmdate = $_POST['supper_admin_email_from_date'];
			$tilldate = $_POST['supper_admin_email_till_date'];

			$html = '<table border="0" style="font-size:14px; font-weight:bold;">';
			$html .= '<tr>
						<td width="220" height="20">'.$employee_name.'\'s Work Schedule</td>
						<td width="120" height="20"></td>
						<td width="52" height="20"></td>
						<td width="74" height="20"></td>
						<td width="74" height="20"></td>
						<td width="200" height="20">'.$frmdate.' - '.$tilldate.'</td>
				</tr></table>';

			$html .= '<table cellpadding="5" cellspacing="0" border="1" style="text-align:center;">';
			$html .= '<tr>
						<th width="220" style="font-weight:bold;">Client Name</th>
						<th width="220" style="font-weight:bold;">My Task</th>
						<th width="52" style="font-weight:bold;">My Time</th>
						<th width="74" style="font-weight:bold;">Start Date</th>
						<th width="74" style="font-weight:bold;">Due Date</th>
						<th width="100" style="font-weight:bold;">Assigned by</th>
					</tr>';

			$pdf->SetFont('helvetica','',9);
			$est_total = $row_total = 0;
			$start_days = array();
			foreach($timesheet_arr as $emp_timesheet) {
				$border = '';
				if( in_array($emp_timesheet['assigned_day'], $start_days) == false ) {
					$start_days[] = $emp_timesheet['assigned_day'];

					if( count($start_days) > 1 ) {
						$border = 'border-top: 3px solid #000; border-right: 1px solid #000;';
					}
				}

				$current_y = $pdf->GetY();
				$current_x = $pdf->GetX();

				$timesheet_id = $emp_timesheet['ID'];

				$table_timesheet_assign_log = $wpdb->base_prefix . "stm_timesheet_assign_log";
				$timesheet_assign_log = $wpdb->get_row("SELECT assigned_by FROM $table_timesheet_assign_log WHERE timesheet_id = '$timesheet_id'", ARRAY_A); //DISTINCT

				$assignee_user_info = get_userdata($emp_timesheet['assigned_by']);
				$assigned_by = $assignee_user_info->first_name;

				$curr_x_pos = $pdf->GetX();
				$curr_y_pos = $pdf->GetY();

				$html .= '<tr nobr="true">
							<td style="'.$border.'">'.stripslashes($emp_timesheet['client_name']).'</td>
							<td style="'.$border.'">'.stripslashes($emp_timesheet['my_task']).'</td>
							<td style="'.$border.'">'.trim($emp_timesheet['estimate_hour']).'</td>
							<td style="'.$border.'">'.trim($emp_timesheet['assigned_day']).'</td>
							<td style="'.$border.'">'.trim($emp_timesheet['due_date']).'</td>
							<td style="'.$border.'">'.trim($assigned_by).'</td>
						</tr>';

				$est_total += $emp_timesheet['estimate_hour'];
				$row_total++;
			}

			//Add blank line
			for($bline = 1; $bline <= 6; $bline++){
				$html .= '<tr nobr="true">
							<td>&nbsp;</td>
							<td>&nbsp;</td>
							<td>&nbsp;</td>
							<td>&nbsp;</td>
							<td>&nbsp;</td>
							<td>&nbsp;</td>
						</tr>';
				$row_total++;
			}


			if( count($timesheet_arr) > 0 ) {

				$current_y = $pdf->GetY();
				$current_x = $pdf->GetX();

				$html .= '<tr nobr="true">
							<td colspan="2">Total</td>
							<td>'.$est_total.'</td>
							<td colspan="3">&nbsp;</td>
						</tr>';

				$row_total++;
			}
			$html .= '</table>';

			// output the HTML content
			$pdf->writeHTML($html, true, false, true, false, '');

			$pdf_filename = $pdf->Output('', 'S');

			$dir = "/emp-workschedule-pdf";
			$upload_dir = wp_upload_dir();
			$schedule_pdf_dir = $upload_dir['basedir'].$dir;
			$schedule_pdf_url = $upload_dir['baseurl'].$dir;
			if( ! file_exists( $schedule_pdf_dir ) ){
				wp_mkdir_p( $schedule_pdf_dir );
			}

			$filename_title = strtolower(str_replace(" ", "-", $employee_user_info->display_name)).'-work-schedule-'.time();

			if( ! file_exists( $schedule_pdf_dir.'/'.$filename_title.'.pdf' ) ) {
				$pdf->Output($schedule_pdf_dir.'/'.$filename_title.'.pdf', 'F');
			} else {
				unlink($schedule_pdf_dir.'/'.$filename_title.'.pdf');
				$pdf->Output($schedule_pdf_dir.'/'.$filename_title.'.pdf', 'F');
			}

			$schedule_pdf_file_emp = $schedule_pdf_dir.'/'.$filename_title.'.pdf';


			$email_subject = 'Work Schedule';
			$get_message = 'Please see attach work schedule.';
			$headers  = 'MIME-Version: 1.0' . "\r\n";
			$headers .= 'Content-type: text/plain; charset=iso-8859-1' . "\r\n";
			add_filter('wp_mail_content_type',create_function('', 'return "text/html"; '));
			@wp_mail($employee_user_info->user_email, $email_subject, $get_message, $headers, array($schedule_pdf_file_emp) );
			//@wp_mail('vikassharma20144@gmail.com', $email_subject, $get_message, $headers, array($schedule_pdf_file_emp) );
            unlink($schedule_pdf_dir.'/'.$filename_title.'.pdf');
		}
		if(empty($single_id)){
			wp_redirect(home_url()."/wp-admin/edit.php?post_type=timesheet&page=email&empsent=yes&supper_admin_email_from_date=".$frmdate."&supper_admin_email_till_date=".$tilldate);
		}else{
			echo  json_encode(array($employee_user_info->user_email));
		}
		exit;

	}


	if( isset($_POST['team_email_pdf']) && ($_POST['team_email_pdf'] == 'do') ) {

		global $wpdb;
		//generate availability overview page.
		$availability_schedule_pdf_file_emp = get_availitility_schedule_pdf();

		$team_leaders_team = get_team_leaders_team($single_id);
		$email_to = array();
		foreach($team_leaders_team as $user_id => $team) {
			$pdf = new PDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
			$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

			$table_timesheet_member = $wpdb->base_prefix . "stm_timesheet_member";
			$timesheet_member_arr = $wpdb->get_results("SELECT employee_id, timesheet_id FROM $table_timesheet_member WHERE ( team = '$team' ) GROUP BY(employee_id)", ARRAY_A);
			//$team_exists = 0;
			foreach($timesheet_member_arr as $timesheet_member) {
				//$team_exists = 1;
				$pdf->head = "no";
				$pdf->foot = "no";
				$pdf->AddPage();
				$pdf->SetFont('helvetica','B',18);
				$pdf->SetTextColor(0, 0, 0);

				$employee_id = $timesheet_member['employee_id'];
				$table_timesheet = $wpdb->base_prefix . "stm_timesheet";
				$timesheet_arr = $wpdb->get_results("SELECT DISTINCT ID, client_name, my_task, estimate_hour, due_date, assigned_day, assignee, created_by, assigned_by FROM $table_timesheet WHERE ( created_by = '$employee_id' OR assignee = '$employee_id' ) ORDER BY FIELD(assigned_day, 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday')", ARRAY_A);

				$employee_info = get_userdata($employee_id);
				$employee_name = $employee_info->first_name;
				if(in_array('project_manager',$employee_info->roles)){
					$email_to[] = $employee_info->data->user_email;
				}
				//$html = "<p style=\"font-size:14px; font-weight:bold;\">".$employee_name."'s Work Schedule</p>";
				$frmdate = $_POST['supper_admin_email_from_date'];
				$tilldate = $_POST['supper_admin_email_till_date'];

				$html = '<table border="0" style="font-size:14px; font-weight:bold;">';
				$html .= '<tr>
							<td width="220" height="20">'.$employee_name.'\'s Work Schedule</td>
							<td width="120" height="20"></td>
							<td width="52" height="20"></td>
							<td width="74" height="20"></td>
							<td width="74" height="20"></td>
							<td width="200" height="20">'.$frmdate.' - '.$tilldate.'</td>
					</tr></table>';

				$pdf->SetFont('helvetica','B',10);
				$pdf->SetTextColor(0, 0, 0);

				$html .= '<table cellpadding="5" cellspacing="0" border="1" style="text-align:center;">';
				$html .= '<tr>
							<th width="160" style="font-weight:bold;">Client Name</th>
							<th width="160" style="font-weight:bold;">My Task</th>';
				if( in_array('team_leader', $employee_info->roles) || in_array('project_manager', $employee_info->roles) ) {
					$html .= '<th width="47" style="font-weight:bold;">My Time</th>
							<th width="53" style="font-weight:bold;">Others Time</th>';
				} else {
					$html .= '<th width="100" style="font-weight:bold;">My Time</th>';
				}
				$html .= '<th width="74" style="font-weight:bold;">Start Date</th>
						<th width="74" style="font-weight:bold;">Due Date</th>
						<th width="88" style="font-weight:bold;">Assigned by</th>
						<th width="88" style="font-weight:bold;">Assigned to</th>
					</tr>';

				$pdf->SetFont('helvetica','',9);
				$my_total = $others_total = $est_total = $row_total = 0;
				$start_days = array();
				foreach($timesheet_arr as $emp_timesheet) {

					$assignee_user_info = get_userdata($emp_timesheet['assigned_by']);
					$assigned_by = $assignee_user_info->first_name;

					$assigned_user_info = get_userdata($emp_timesheet['assignee']);
					$assigned_to = $assigned_user_info->first_name;

					$current_y = $pdf->GetY();
					$current_x = $pdf->GetX();

					$timesheet_id = $emp_timesheet['ID'];

					$table_timesheet_assign_log = $wpdb->base_prefix . "stm_timesheet_assign_log";
					$timesheet_assign_log = $wpdb->get_row("SELECT assigned_by, employee_id FROM $table_timesheet_assign_log WHERE timesheet_id = '$timesheet_id'", ARRAY_A);
					$trbgcolor = '';
					if( $employee_id == $emp_timesheet['assignee'] ) {
						$trbgcolor = '';
						$pdf->SetFillColor(255,255,255);
						$pdf->SetTextColor(0, 0, 0);
						$same_user = true;
					} else if ( $emp_timesheet['created_by'] != $emp_timesheet['assignee'] ) {
						if($employee_id == $emp_timesheet['created_by']){
							$trbgcolor = 'background-color:#F8F8F8;';
							$pdf->SetFillColor(221,221,221);
							$pdf->SetTextColor(0,0,0);
						} else {
							$trbgcolor = '';
							$pdf->SetFillColor(255,255,255);
							$pdf->SetTextColor(0,0,0);
						}
						$same_user = false;
					} else {
						$trbgcolor = '';
						$pdf->SetFillColor(255,255,255);
						$pdf->SetTextColor(0, 0, 0);
						$same_user = true;
					}

					$curr_x_pos = $pdf->GetX();
					$curr_y_pos = $pdf->GetY();

					$border = '';
					if( in_array($emp_timesheet['assigned_day'], $start_days) == false ) {
						$start_days[] = $emp_timesheet['assigned_day'];

						if( count($start_days) > 1 ) {
							$border = 'border-top: 3px solid #000; border-right: 1px solid #000;';
						}
					}

					$curr_x_pos = $pdf->GetX();
					$curr_y_pos = $pdf->GetY();

					$html .= '<tr nobr="true">
								<td style="'.$border. $trbgcolor.'">'.stripslashes($emp_timesheet['client_name']).'</td>
								<td style="'.$border. $trbgcolor.'">'.stripslashes($emp_timesheet['my_task']).'</td>';
					if( in_array('team_leader', $employee_info->roles) || in_array('project_manager', $employee_info->roles) ) {
						if( $same_user ) {
							$html .= '<td style="'.$border. $trbgcolor.'">'.trim($emp_timesheet['estimate_hour']).'</td>
									<td style="'.$border. $trbgcolor.'">&nbsp;</td>';
							$my_total += $emp_timesheet['estimate_hour'];
						} else {
							$html .= '<td style="'.$border. $trbgcolor.'">&nbsp;</td>
									<td style="'.$border. $trbgcolor.'">'.trim($emp_timesheet['estimate_hour']).'</td>';
							$others_total += $emp_timesheet['estimate_hour'];
						}
					} else {
						$html .= '<td style="'.$border. $trbgcolor.'">'.trim($emp_timesheet['estimate_hour']).'</td>';
						$est_total += $emp_timesheet['estimate_hour'];
					}
					$html .= '<td style="'.$border. $trbgcolor.'">'.trim($emp_timesheet['assigned_day']).'</td>
							<td style="'.$border. $trbgcolor.'">'.trim($emp_timesheet['due_date']).'</td>
							<td style="'.$border. $trbgcolor.'">'.$assigned_by.'</td>
							<td style="'.$border. $trbgcolor.'">'.$assigned_to.'</td>
						</tr>';

					$row_total++;
				}

				//Add blank line
				for($bline = 1; $bline <= 6; $bline++){
					$html .= '<tr nobr="true">
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
							</tr>';

					$row_total++;
				}


				if( count($timesheet_arr) > 0 ) {

					$html .= '<tr nobr="true">
								<td colspan="2">Total</td>';
					if( in_array('team_leader', $employee_info->roles)  || in_array('project_manager', $employee_info->roles) ) {
						$html .= '<td>'.$my_total.'</td>
								<td>'.$others_total.'</td>';
					} else {
						$html .= '<td>'.$est_total.'</td>';
					}
					$html .= '<td colspan="4">&nbsp;</td>
							</tr>';

					$row_total++;
				}
				$html .= '</table>';

				// output the HTML content
				$pdf->writeHTML($html, true, false, true, false, '');
			}

			$pdf_filename = $pdf->Output('', 'S');

			$team_leader_info = get_userdata($user_id);
			//debug($employee_user_info->user_role[0]);
			$team_leader_name = $team_leader_info->display_name;
			//echo $team_leader_info->user_email;


			$dir = "/emp-workschedule-pdf";
			$upload_dir = wp_upload_dir();
			$schedule_pdf_dir = $upload_dir['basedir'].$dir;
			$schedule_pdf_url = $upload_dir['baseurl'].$dir;
			if( ! file_exists( $schedule_pdf_dir ) ){
				wp_mkdir_p( $schedule_pdf_dir );
			}

			$filename_title = strtolower(str_replace(" ", "-", get_user_meta($team_leader_info->ID,'last_name',true))).'-work-schedule-'.time();

			if( ! file_exists( $schedule_pdf_dir.'/'.$filename_title.'.pdf' ) ) {
				$pdf->Output($schedule_pdf_dir.'/'.$filename_title.'.pdf', 'F');
			} else {
				unlink($schedule_pdf_dir.'/'.$filename_title.'.pdf');
				$pdf->Output($schedule_pdf_dir.'/'.$filename_title.'.pdf', 'F');
			}

			$schedule_pdf_file_team_lead = $schedule_pdf_dir.'/'.$filename_title.'.pdf';



			$email_subject = 'Work Schedule';
			$get_message = 'Please see attach work schedule.';
			$headers  = 'MIME-Version: 1.0' . "\r\n";
			$headers .= 'Content-type: text/plain; charset=iso-8859-1' . "\r\n";
			add_filter('wp_mail_content_type',create_function('', 'return "text/html"; '));
			$attachment = array($schedule_pdf_file_team_lead, $availability_schedule_pdf_file_emp);
            $emails = array();
            foreach($email_to as $email){
				$emails[] = $email;
            }
            //$emails = array('acevedo.oliver@gmail.com','vikassharma20144@gmail.com');
            //@wp_mail($main_email, $email_subject, $get_message, $headers,  $attachment);
            @wp_mail(implode(',',$emails), $email_subject, $get_message, $headers,  $attachment);
			 echo json_encode($emails);

			//sleep(1);
           // unlink($schedule_pdf_dir.'/'.$filename_title.'.pdf');

		}

       // sleep(30);
		//unlink($availability_schedule_pdf_file_emp);
		if(empty($single_id)){
			wp_redirect(home_url()."/wp-admin/edit.php?post_type=timesheet&page=email&teamsent=yes&supper_admin_email_from_date=".$frmdate."&supper_admin_email_till_date=".$tilldate);
		}
		exit;

	}


	if( isset($_POST['supper_admin_email_pdf']) && ($_POST['supper_admin_email_pdf'] == 'do') ) {
		global $wpdb;
		$pdf = new PDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
		$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

		$all_emp_team = get_employees_and_team();
		foreach($all_emp_team as $member_key => $member_name){
			$member_type_ar = explode('_', $member_key);
			$employee_id = $member_type_ar[1];

			if(($member_type_ar[0] == 'emp') && $employee_id){
				//echo $member_name.' - Emplyee ID:'.$member_type_ar[1].'<br />';
				$employee_user_info = get_userdata($employee_id);
				$employee_name = $employee_user_info->first_name;
				$employee_name .= ' '.$employee_user_info->last_name;

				$table_timesheet = $wpdb->base_prefix . "stm_timesheet";
				$timesheet_arr = $wpdb->get_results("SELECT DISTINCT ID, client_name, my_task, estimate_hour, due_date, assigned_day, assignee, created_by, assigned_by FROM $table_timesheet WHERE ( created_by = '$employee_id' OR assignee = '$employee_id' ) ORDER BY FIELD(assigned_day, 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday')", ARRAY_A);

				$pdf->head = "no";
				$pdf->foot = "no";
				$pdf->AddPage();
				$pdf->SetFont('helvetica','B',10);
				$pdf->SetTextColor(0, 0, 0);

				//$html = "<p style=\"font-size:14px; font-weight:bold;\">".$employee_name."'s Work Schedule</p>";
				$frmdate = $_POST['supper_admin_email_from_date'];
				$tilldate = $_POST['supper_admin_email_till_date'];

				$html = '<table border="0" style="font-size:14px; font-weight:bold;">';
				$html .= '<tr>
							<td width="220" height="20">'.$employee_name.'</td>
							<td width="120" height="20"></td>
							<td width="52" height="20"></td>
							<td width="74" height="20"></td>
							<td width="74" height="20"></td>
							<td width="200" height="20">'.$frmdate.' - '.$tilldate.'</td>
					</tr></table>';

				$current_y = $pdf->GetY();
				$current_x = $pdf->GetX();

				$html .= '<table cellpadding="5" cellspacing="0" border="1" style="text-align:center;">';
				$html .= '<tr>
							<th width="220" style="font-weight:bold;">Client Name</th>
							<th width="220" style="font-weight:bold;">My Task</th>
							<th width="52" style="font-weight:bold;">My Time</th>
							<th width="74" style="font-weight:bold;">Start Date</th>
							<th width="74" style="font-weight:bold;">Due Date</th>
							<th width="100" style="font-weight:bold;">Assigned by</th>
					</tr>';

				$pdf->SetFont('helvetica','',9);
				$est_total = $row_total = 0;
				$start_days = array();
				foreach($timesheet_arr as $emp_timesheet) {
					$border = '';
					if( in_array($emp_timesheet['assigned_day'], $start_days) == false ) {
						$start_days[] = $emp_timesheet['assigned_day'];

						if( count($start_days) > 1 ) {
							$border = 'border-top: 3px solid #000; border-right: 1px solid #000;';
						}
					}

					$current_y = $pdf->GetY();
					$current_x = $pdf->GetX();

					$timesheet_id = $emp_timesheet['ID'];

					$table_timesheet_assign_log = $wpdb->base_prefix . "stm_timesheet_assign_log";
					$timesheet_assign_log = $wpdb->get_row("SELECT assigned_by FROM $table_timesheet_assign_log WHERE timesheet_id = '$timesheet_id'", ARRAY_A); //DISTINCT

					$assignee_user_info = get_userdata($emp_timesheet['assigned_by']);
					$assigned_by = $assignee_user_info->first_name;

					$curr_x_pos = $pdf->GetX();
					$curr_y_pos = $pdf->GetY();

					$html .= '<tr nobr="true">
								<td style="'.$border.'">'.stripslashes($emp_timesheet['client_name']).'</td>
								<td style="'.$border.'">'.stripslashes($emp_timesheet['my_task']).'</td>
								<td style="'.$border.'">'.trim($emp_timesheet['estimate_hour']).'</td>
								<td style="'.$border.'">'.trim($emp_timesheet['assigned_day']).'</td>
								<td style="'.$border.'">'.trim($emp_timesheet['due_date']).'</td>
								<td style="'.$border.'">'.trim($assigned_by).'</td>
							</tr>';

					$est_total += $emp_timesheet['estimate_hour'];

					$row_total++;
				}

				//Add blank line
				for($bline = 1; $bline <= 6; $bline++){

					$current_y = $pdf->GetY();
					$current_x = $pdf->GetX();

					$html .= '<tr nobr="true">
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
							</tr>';

					$row_total++;
				}


				if( count($timesheet_arr) > 0 ) {
					$current_y = $pdf->GetY();
					$current_x = $pdf->GetX();
					$pdf->SetFont('helvetica','B',10);
					$pdf->SetTextColor(0, 0, 0);

					$html .= '<tr nobr="true">
								<td colspan="2">Total</td>
								<td>'.$est_total.'</td>
								<td colspan="3">&nbsp;</td>
							</tr>';

					$row_total++;
				}

				$html .= '</table>';

				// output the HTML content
				$pdf->writeHTML($html, true, false, true, false, '');

			}elseif(($member_type_ar[0] == 'team') && $employee_id){
				//echo $member_name.' - Team ID:'.$member_type_ar[1].'<br />';
				$table_timesheet = $wpdb->base_prefix . "stm_timesheet";
				$timesheet_arr = $wpdb->get_results("SELECT DISTINCT ID, client_name, my_task, estimate_hour, due_date, assigned_day, assignee, created_by, assigned_by FROM $table_timesheet WHERE ( created_by = '$employee_id' OR assignee = '$employee_id' ) ORDER BY FIELD(assigned_day, 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday')", ARRAY_A);

				$employee_team_info = get_userdata($employee_id);
				$employee_name = $employee_team_info->first_name;
				$employee_name .= ' '.$employee_team_info->last_name;

				$pdf->head = "no";
				$pdf->foot = "no";
				$pdf->AddPage();
				$pdf->SetFont('helvetica','B',11);
				$pdf->SetTextColor(0, 0, 0);

				//$html = "<p style=\"font-size:14px; font-weight:bold;\">".$employee_name."'s Work Schedule</p>";

				$frmdate = $_POST['supper_admin_email_from_date'];
				$tilldate = $_POST['supper_admin_email_till_date'];

				$html = '<table border="0" style="font-size:14px; font-weight:bold;">';
				$html .= '<tr>
							<td width="220" height="20">'.$employee_name.'</td>
							<td width="120" height="20"></td>
							<td width="52" height="20"></td>
							<td width="74" height="20"></td>
							<td width="74" height="20"></td>
							<td width="200" height="20">'.$frmdate.' - '.$tilldate.'</td>
					</tr></table>';

				$current_y = $pdf->GetY();
				$current_x = $pdf->GetX();

				$pdf->SetFont('helvetica','B',10);
				$pdf->SetTextColor(0, 0, 0);

				$html .= '<table cellpadding="5" cellspacing="0" border="1" style="text-align:center;">';
				$html .= '<tr>
							<th width="160" style="font-weight:bold;">Client Name</th>
							<th width="160" style="font-weight:bold;">My Task</th>';
				if( in_array('team_leader', $employee_team_info->roles)  || in_array('project_manager', $employee_team_info->roles)  ) {
					$html .= '<th width="47" style="font-weight:bold;">My Time</th>
							<th width="53" style="font-weight:bold;">Others Time</th>';
				} else {
					$html .= '<th width="100" style="font-weight:bold;">My Time</th>';
				}
				$html .= '<th width="74" style="font-weight:bold;">Start Date</th>
						<th width="74" style="font-weight:bold;">Due Date</th>
						<th width="88" style="font-weight:bold;">Assigned by</th>
						<th width="88" style="font-weight:bold;">Assigned to</th>
					</tr>';

				$pdf->SetFont('helvetica','',9);
				$my_total = $others_total = $est_total = $row_total = 0;
				$start_days = array();
				foreach($timesheet_arr as $emp_timesheet) {

					$assignee_user_info = get_userdata($emp_timesheet['assigned_by']);
					$assigned_by = $assignee_user_info->first_name;

					$assigned_user_info = get_userdata($emp_timesheet['assignee']);
					$assigned_to = $assigned_user_info->first_name;

					$current_y = $pdf->GetY();
					$current_x = $pdf->GetX();

					$timesheet_id = $emp_timesheet['ID'];

					$table_timesheet_assign_log = $wpdb->base_prefix . "stm_timesheet_assign_log";
					$timesheet_assign_log = $wpdb->get_row("SELECT assigned_by, employee_id FROM $table_timesheet_assign_log WHERE timesheet_id = '$timesheet_id'", ARRAY_A);
					$trbgcolor = '';
					if( $employee_id == $emp_timesheet['assignee'] ) {
						$trbgcolor = '';
						$pdf->SetFillColor(255,255,255);
						$pdf->SetTextColor(0, 0, 0);
						$same_user = true;
					} else if ( $emp_timesheet['created_by'] != $emp_timesheet['assignee'] ) {
						if($employee_id == $emp_timesheet['created_by']){
							$trbgcolor = 'background-color:#F8F8F8;';
							$pdf->SetFillColor(221,221,221);
							$pdf->SetTextColor(0,0,0);
						} else {
							$trbgcolor = '';
							$pdf->SetFillColor(255,255,255);
							$pdf->SetTextColor(0,0,0);
						}
						$same_user = false;
					} else {
						$trbgcolor = '';
						$pdf->SetFillColor(255,255,255);
						$pdf->SetTextColor(0, 0, 0);
						$same_user = true;
					}

					$curr_x_pos = $pdf->GetX();
					$curr_y_pos = $pdf->GetY();

					$border = '';
					if( in_array($emp_timesheet['assigned_day'], $start_days) == false ) {
						$start_days[] = $emp_timesheet['assigned_day'];

						if( count($start_days) > 1 ) {
							$border = 'border-top: 3px solid #000; border-right: 1px solid #000;';
						}
					}

					$curr_x_pos = $pdf->GetX();
					$curr_y_pos = $pdf->GetY();

					$html .= '<tr nobr="true">
								<td style="'.$border. $trbgcolor.'">'.stripslashes($emp_timesheet['client_name']).'</td>
								<td style="'.$border. $trbgcolor.'">'.stripslashes($emp_timesheet['my_task']).'</td>';
					if( in_array('team_leader', $employee_team_info->roles) || in_array('project_manager', $employee_team_info->roles) ) {
						if( $same_user ) {
							$html .= '<td style="'.$border. $trbgcolor.'">'.trim($emp_timesheet['estimate_hour']).'</td>
									<td style="'.$border. $trbgcolor.'">&nbsp;</td>';
							$my_total += $emp_timesheet['estimate_hour'];
						} else {
							$html .= '<td style="'.$border. $trbgcolor.'">&nbsp;</td>
									<td style="'.$border. $trbgcolor.'">'.trim($emp_timesheet['estimate_hour']).'</td>';
							$others_total += $emp_timesheet['estimate_hour'];
						}
					} else {
						$html .= '<td style="'.$border. $trbgcolor.'">'.trim($emp_timesheet['estimate_hour']).'</td>';
						$est_total += $emp_timesheet['estimate_hour'];
					}
					$html .= '<td style="'.$border. $trbgcolor.'">'.trim($emp_timesheet['assigned_day']).'</td>
							<td style="'.$border. $trbgcolor.'">'.trim($emp_timesheet['due_date']).'</td>
							<td style="'.$border. $trbgcolor.'">'.$assigned_by.'</td>
							<td style="'.$border. $trbgcolor.'">'.$assigned_to.'</td>
						</tr>';

					$row_total++;
				}

				//Add blank line
				for($bline = 1; $bline <= 6; $bline++){
					$current_y = $pdf->GetY();
					$current_x = $pdf->GetX();

					$html .= '<tr nobr="true">
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
							</tr>';

					$row_total++;
				}



				if( count($timesheet_arr) > 0 ) {
					$current_y = $pdf->GetY();
					$current_x = $pdf->GetX();
					$pdf->SetFont('helvetica','B',11);
					$pdf->SetTextColor(0, 0, 0);

					$html .= '<tr nobr="true">
								<td colspan="2">Total</td>';
					if( in_array('team_leader', $employee_team_info->roles)   || in_array('project_manager', $employee_team_info->roles)  ) {
						$html .= '<td>'.$my_total.'</td>
								<td>'.$others_total.'</td>';
					} else {
						$html .= '<td>'.$est_total.'</td>';
					}
					$html .= '<td colspan="4">&nbsp;</td>
							</tr>';

					$row_total++;
				}

				$html .= '</table>';

				// output the HTML content
				$pdf->writeHTML($html, true, false, true, false, '');

			}//Eof team member task

		}


		$pdf_filename = $pdf->Output('', 'S');

		$dir = "/emp-workschedule-pdf";
		$upload_dir = wp_upload_dir();
		$schedule_pdf_dir = $upload_dir['basedir'].$dir;
		$schedule_pdf_url = $upload_dir['baseurl'].$dir;
		if( ! file_exists( $schedule_pdf_dir ) ){
			wp_mkdir_p( $schedule_pdf_dir );
		}
		$filename_title = 'emp-workschedule-all';
		if( ! file_exists( $schedule_pdf_dir.'/'.$filename_title.'.pdf' ) ) {
			$pdf->Output($schedule_pdf_dir.'/'.$filename_title.'.pdf', 'F');
		} else {
			unlink($schedule_pdf_dir.'/'.$filename_title.'.pdf');
			$pdf->Output($schedule_pdf_dir.'/'.$filename_title.'.pdf', 'F');
		}

		$schedule_pdf_file = $schedule_pdf_dir.'/'.$filename_title.'.pdf';

		$email_subject = 'Work Schedule';
		$get_message = 'Please see attach work schedule for all employes and team leaders.';

		$headers  = 'MIME-Version: 1.0' . "\r\n";
		$headers .= 'Content-type: text/plain; charset=iso-8859-1' . "\r\n";
		add_filter('wp_mail_content_type',create_function('', 'return "text/html"; '));

		if(isset($_POST['supper_admin_email_pdf_test'])){
			$admin_email = $_POST['supper_admin_email_pdf_test'];
			$availability_schedule_pdf_file_emp = get_availitility_schedule_pdf();
			@wp_mail( $admin_email, $email_subject, $get_message, $headers, array($schedule_pdf_file, $availability_schedule_pdf_file_emp) );
			unlink($schedule_pdf_dir.'/'.$schedule_pdf_file.'.pdf');
			unlink($$availability_schedule_pdf_file_emp);
		}

		if(isset($_POST['include_all_admin'])){
			$availability_schedule_pdf_file_emp = get_availitility_schedule_pdf();
			@wp_mail( 'bbarnes@entosdesign.com', $email_subject, $get_message, $headers, array($schedule_pdf_file, $availability_schedule_pdf_file_emp) );
			@wp_mail( 'bmaners@entosdesign.com', $email_subject, $get_message, $headers, array($schedule_pdf_file, $availability_schedule_pdf_file_emp) );
			@wp_mail( 'kcostigan@entosdesign.com', $email_subject, $get_message, $headers, array($schedule_pdf_file, $availability_schedule_pdf_file_emp) );
			@wp_mail( 'lwinkler@entosdesign.com', $email_subject, $get_message, $headers, array($schedule_pdf_file, $availability_schedule_pdf_file_emp) );
			unlink($schedule_pdf_dir.'/'.$schedule_pdf_file.'.pdf');
			unlink($$availability_schedule_pdf_file_emp);
		}


		wp_redirect(home_url()."/wp-admin/edit.php?post_type=timesheet&page=email&adminsent=yes&supper_admin_email_from_date=".$frmdate."&supper_admin_email_till_date=".$tilldate);
		exit;

	}

}

function set_team_leaders_to_themselves() {
	add_filter( 'update_user_metadata', 'set_team_leaders_to_themselves_callback', 10, 5 );
}

function set_team_leaders_to_themselves_callback( $null, $object_id, $meta_key, $meta_value, $prev_value ) {

	if ( 'wp_capabilities' == $meta_key && array_key_exists('team_leader',$meta_value) ) {
		global $wpdb;
		$table_timesheet_member = $wpdb->base_prefix . "stm_timesheet_member";
		$query = "UPDATE $table_timesheet_member SET team='$object_id' WHERE employee_id = $object_id";
		update_user_meta( $object_id, '_stm_team', $object_id );
		$wpdb->query($query);
	}

	return null;

}

add_action( 'init', 'set_team_leaders_to_themselves' );


function get_team_leaders_team($id = 0) {
	$args = array( 'role__in' => array('team_leader') );
	if(!empty($id)){
		$args['include'] = array($id);
	}
	$blogusers = get_users( $args );
	// Array of WP_User objects.
	global $wpdb;
	$team_leaders_team = array();
	foreach ( $blogusers as $user ) {
		$table_timesheet_member = $wpdb->base_prefix . "stm_timesheet_member";
		$timesheet_member_team = $wpdb->get_row("SELECT team FROM $table_timesheet_member WHERE team = '$user->ID'", ARRAY_A);
		$team_leaders_team[$user->ID] = $timesheet_member_team['team'];
	}

	return $team_leaders_team;
}

function get_administrators() {
	$blogusers = get_users( array( 'role' => 'administrator' ) );
	// Array of WP_User objects.
	$administrators = array();
	foreach ( $blogusers as $user ) {
		$administrators[] = $user->user_email;
	}

	return $administrators;
}

function get_employees($id = 0) {
	$args = array( 'role' => 'employee', 'orderby' => 'nicename', 'order' => 'ASC' );
	if(!empty($id)){
		$args['include'] = array($id);
	}
	$blogusers = get_users( $args );
	// Array of WP_User objects.
	$employees = array();
	foreach ( $blogusers as $user ) {
		$employees[] = $user->ID;
	}

	return $employees;
}

function get_team_members($id = 0) {
	$args = array( 'role' => 'team_leader', 'orderby' => 'nicename', 'order' => 'ASC' );
	if(!empty($id)){
		$args['include'] = array($id);
	}
	$blogusers = get_users( $args );
	// Array of WP_User objects.
	$employees = array();
	foreach ( $blogusers as $user ) {
		$employees[] = $user->ID;
	}

	return $employees;
}

function get_employees_and_team() {
	$blogusers = get_users( array( 'role' => 'employee', 'orderby' => 'nicename', 'order' => 'ASC' ) );
	$employees = array();
	foreach ( $blogusers as $user ) {
		$employees['emp_'.$user->ID] = $user->display_name;
	}

	$blogteamusers = get_users( array( 'role__in' => array('team_leader','project_manager'), 'orderby' => 'nicename', 'order' => 'ASC' ) );
	foreach ( $blogteamusers as $user ) {
		$employees['team_'.$user->ID] = $user->display_name;
	}

	asort($employees);

	return $employees;
}


add_action( 'admin_init', 'call_generate_email_notification');
function call_generate_email_notification(){
	if(isset($_GET['clear_all_timesheet']) && ($_GET['clear_all_timesheet'] == 'yes')){
		clear_all_timesheet_of_a_week();
	}
	if(isset($_GET['generate_email']) && ($_GET['generate_email'] == 'yes')){
		//generate_weekly_email_12();
		//generate_weekly_email_4();
	}

	//run by hosting corn automatically in every night 2am
	/*if(isset($_GET['corn']) && ($_GET['corn'] == 'dodaily')){
		$to = 'azad.rmweblab@gmail.com';
		$subject = 'Sample wp email testing from leadershipology';
		add_filter('wp_mail_content_type',create_function('', 'return "text/html"; '));
		$message .= 'HTML messege<br/>';
		wp_mail( $to, $subject, $message );
	}*/
}

function generate_weekly_email_12() {
	// Todo
	$contractors = get_contractor();
	foreach($contractors as $contractor) {
		$args = array(
			'post_type' => 'timesheet',
			'meta_query' => array(
				array(
					'key'     => '_stm_employee_id',
					'value'   => $contractor,
					'compare' => '=',
				),
			),
			'tax_query' => array(
				array(
					'taxonomy' => 'timesheet_status',
					'field'    => 'slug',
					'terms'    => 'pending',
				),
			),
		);
		$query = new WP_Query( $args );
		if ($query->have_posts()) :
			global $post;
			while ($query->have_posts()) : $query->the_post();

//				$term_list = wp_get_post_terms($post->ID, 'timesheet_status', array("fields" => "all"));
//				foreach($term_list as $term) {
//					if( $term->slug == 'pending' ) {

						$contractor_info = get_userdata($contractor);
						$contractor_email = $contractor_info->user_email;

						$message = "<p>This is a reminder to fill out your timesheet for the upcoming week and mark it \"Complete\". All work schedules for contractors need to be completed by 12pm on Friday. Thank you.</p>";
						$message .= "<p><a href='".get_edit_post_link()."'>".get_edit_post_link()."</a></p>";

						$headers  = 'MIME-Version: 1.0' . "\r\n";
						$headers .= 'Content-type: text/html; charset=UTF-8' . "\r\n";
						add_filter('wp_mail_content_type',create_function('', 'return "text/html"; '));
						@wp_mail( $contractor_email, "Reminder to fill out your timesheet", $message, $headers );

//					}
//				}

			endwhile;
			wp_reset_query();
		endif;
	}

	//$user_id = get_post_meta($post_id, '_stm_employee_id', true);
}

function generate_weekly_email_4() {
	// Todo
	$employees = get_employees();
	foreach($employees as $employee) {
		$args = array(
			'post_type' => 'timesheet',
			'meta_query' => array(
				array(
					'key'     => '_stm_employee_id',
					'value'   => $employee,
					'compare' => '=',
				),
			),
			'tax_query' => array(
				array(
					'taxonomy' => 'timesheet_status',
					'field'    => 'slug',
					'terms'    => 'pending',
				),
			),
		);
		$query = new WP_Query( $args );
		if ($query->have_posts()) :
			global $post;
			while ($query->have_posts()) : $query->the_post();

//				$term_list = wp_get_post_terms($post->ID, 'timesheet_status', array("fields" => "all"));
//				foreach($term_list as $term) {
//					if( $term->slug == 'pending' ) {

						$employee_info = get_userdata($employee);
						$employee_email = $employee_info->user_email;

						$message = "<p>This is a reminder to fill out your timesheet for the upcoming week and mark it \"Complete\". All work schedules for staff need to be completed by 4pm on Friday. Thank you.</p>";
						$message .= "<p><a href='".get_edit_post_link()."'>".get_edit_post_link()."</a></p>";

						$headers  = 'MIME-Version: 1.0' . "\r\n";
						$headers .= 'Content-type: text/html; charset=UTF-8' . "\r\n";
						add_filter('wp_mail_content_type',create_function('', 'return "text/html"; '));
						@wp_mail( $employee_email, "Reminder to fill out your timesheet", $message, $headers );

//					}
//				}

			endwhile;
			wp_reset_query();
		endif;
	}

	//$user_id = get_post_meta($post_id, '_stm_employee_id', true);
}

function get_contractor() {
	$blogusers = get_users( array( 'role' => 'contractor' ) );
	// Array of WP_User objects.
	$contractors = array();
	foreach ( $blogusers as $user ) {
		$contractors[] = $user->ID;
	}

	return $contractors;
}

function check_current_user_role( $role ) {
	$user = wp_get_current_user();
	if( in_array($role, (array) $user->roles) )
		return true;
	else
		return false;
}



function clear_all_timesheet_of_a_week(){
	global $wpdb;

	$table_timesheet = $wpdb->base_prefix . "stm_timesheet";
	$wpdb->query("DELETE FROM $table_timesheet");

	$table_timesheet_relation = $wpdb->base_prefix . "stm_timesheet_relation";
	$wpdb->query("DELETE FROM $table_timesheet_relation");

	$args = array(
		'post_type' => 'timesheet',
		'posts_per_page' => -1,
		'orderby' => 'title',
		'order' => 'ASC'
	);
	$select_query = new WP_Query( $args );
	if ( $select_query->have_posts() ) {
		while ( $select_query->have_posts() ) {
			$select_query->the_post();
			$timesheet_id = get_the_ID();
			$tag = array( 16 );
			$taxonomy = 'timesheet_status';
			wp_remove_object_terms( $timesheet_id, $tag, $taxonomy );
			//wp_set_post_terms( $timesheet_id, $tag, $taxonomy );
		}
		wp_reset_postdata();
	}

	return true;

}

add_filter( 'gettext', 'change_publish_button', 10, 2 );

function change_publish_button( $translation, $text ) {
	if ( 'timesheet' == get_post_type() && $text == 'Update' && isset($_GET['post'])) {
        return 'Update View';
    } else {
        return $translation;
    }
}

add_filter('post_row_actions','timesheet_remove_row_actions', 10, 2 );

function timesheet_remove_row_actions( $actions, $post ){
	global $current_screen;
	if( $current_screen->post_type == 'timesheet' ) {
		unset( $actions['edit'] );
		unset( $actions['view'] );
		unset( $actions['trash'] );
		unset( $actions['inline hide-if-no-js'] );
	}
	return $actions;
}

function highest_cell_rows($array) {
   foreach($array as $key => $value) {
       if (is_array($value)) {
           $array[$key] = highest($value);
       }
   }

   sort($array);

   return array_pop($array);
}

function get_line_numbers($line_numbers) {
	$max_lines = max($line_numbers);
	$max_lines_key = array_search($max_lines, $line_numbers);
	$new_line_arr = array();
	for($i=0; $i<count($line_numbers); $i++) {
		$new_line_arr[] = $line_numbers[$max_lines_key] - $line_numbers[$i];
	}
	return $new_line_arr;
}
