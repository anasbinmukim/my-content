<?php
function stm_save_timesheet_data_item() {

	$client_name = $_POST['client_name'];
	$task_name = $_POST['task_name'];
	$hours = $_POST['hours'];
	$due = $_POST['due'];
	$assignee = $_POST['assignee'];
	$assign_by_team = $_POST['assign_by_team'];
	$db_row = $_POST['db_row'];
	$task_day = $_POST['task_day'];
	$timesheet_user_id = $_POST['timesheet_user_id'];

//	$custom_data .= $client_name;
//	$custom_data .= $task_name;
//	$custom_data .= $hours;
//	$custom_data .= $due;
//	$custom_data .= $assignee;
//	$custom_data .= $db_row;
//
//		echo json_encode(array("msg" => $custom_data));
//		exit;

	if(isset($_POST['db_row']) && $_POST['db_row'] > 0){
		//update data
		if(stm_update_timesheet_data($client_name, $task_name, $hours, $due, $assignee, $db_row, $task_day, $timesheet_user_id, $assign_by_team)){
			echo json_encode(array("msg" => "<span class='update'>Successfully Updated.</span>"));
			exit;
		}
	}else{
		//Insert data
		if( date("w", strtotime($task_day)) <= date("w", strtotime($due)) ) {
			$timesheet_id = stm_add_timesheet_data( $client_name, $task_name, $hours, $due, $assignee, $task_day, $timesheet_user_id, $assign_by_team );
			if( $timesheet_id ){
				echo json_encode(array("msg" => "<span class='update'>Successfully inserted.</span>", 'timesheet_id' => $timesheet_id));
				exit;
			}else{
				echo json_encode(array("msg" => "<span class='error'>Not Saved.</span>"));
				exit;
			}
		} else {
			echo json_encode(array("msg" => "<span class='error'>Please select a due date that is after or same the start date. All tasks should be assigned in the same week.</span>"));
			exit;
		}
	}

}

function delete_timesheet_data_item() {
	$timesheet_id = $_POST['timesheet_id'];
	if(stm_delete_timesheet_data($timesheet_id)){
		echo json_encode(array("msg" => "<span class='update'>Successfully deleted.</span>"));
		exit;
	}else{
		echo json_encode(array("msg" => "<span class='error'>Not Deleted.</span>"));
		exit;
	}
}


/*******************************
 * send_email for search result
*******************************/
add_action('wp_ajax_delete-timesheet-data-item', 'delete_timesheet_data_item');

add_action('wp_ajax_save-timesheet-data-item', 'stm_save_timesheet_data_item');
add_action('wp_ajax_nopriv_save-timesheet-data-item', 'stm_save_timesheet_data_item');


function stm_get_assignee_available_hours() {

	$due_day = $_POST['due_day'];
	$assignee = $_POST['assignee'];
	$section_day = $_POST['section_day'];

	$assign_by_user_info = get_userdata($assignee);
	$assignee_name = $assign_by_user_info->display_name;


	$custom_data .= $due_day;
	$custom_data .= $assignee;
	$custom_data .= $section_day;

	$avaialbe_hours = calculate_available_hours_for_day($assignee, $due_day, $section_day);

	echo json_encode(array("msg" => $avaialbe_hours .'hours'));
	exit;

}


/*******************************
 * send_email for search result
*******************************/
add_action('wp_ajax_get-assignee-available-hours', 'stm_get_assignee_available_hours');
add_action('wp_ajax_nopriv_get-assignee-available-hours', 'stm_get_assignee_available_hours');







function stm_clear_timesheet_data_item() {

	$request_employee_id = $_POST['request_employee_id'];
	$request_employee_timesheet_id = $_POST['request_employee_timesheet_id'];
	$unassigned_timesheet_status = $_POST['unassigned_timesheet_status'];



//	$custom_data .= $request_employee_id . '==';
//	$custom_data .= $request_employee_timesheet_id;
//	echo json_encode(array("msg" => $custom_data));
//	exit;
	if($request_employee_id == 'yes'){
		stm_clear_unassigned_timesheet_data();
		echo json_encode(array("msg" => "<span class='update'>Successfully Reset.</span>"));
		exit;
	}elseif(stm_clear_timesheet_data($request_employee_id)){
		echo json_encode(array("msg" => "<span class='update'>Successfully Reset.</span>"));
		exit;
	}else{
		echo json_encode(array("msg" => "<span class='update'>Please try again.</span>"));
		exit;
	}


}


/*******************************
 * send_email for search result
*******************************/
add_action('wp_ajax_clear-timesheet-data-item', 'stm_clear_timesheet_data_item');
add_action('wp_ajax_nopriv_clear-timesheet-data-item', 'stm_clear_timesheet_data_item');




function stm_update_timesheet_data_status() {

	$task_id = $_POST['task_id'];
	$status = $_POST['status'];



//	$custom_data .= $task_id . '==';
//	$custom_data .= $status;
//	echo json_encode(array("msg" => $custom_data));
//	exit;

	if(stm_update_timesheet_status($status, $task_id)){
		echo json_encode(array("msg" => "<span class='update'>Successfully Updated.</span>"));
		exit;
	}else{
		echo json_encode(array("msg" => "<span class='update'>Please try again.</span>"));
		exit;
	}


}


/*******************************
 * send_email for search result
*******************************/
add_action('wp_ajax_update-timesheet-data-status', 'stm_update_timesheet_data_status');
add_action('wp_ajax_nopriv_update-timesheet-data-status', 'stm_update_timesheet_data_status');
