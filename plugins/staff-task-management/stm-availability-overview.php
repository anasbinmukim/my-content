<?php

add_action('admin_menu', 'stm_availability_overview_page');
function stm_availability_overview_page() {
	add_submenu_page( 'edit.php?post_type=timesheet', 'My Work Schedule', 'My Work Schedule', 'read', 'my-work-schedule', 'add_work_schedule_menu_item_redirect' );
	add_submenu_page( 'edit.php?post_type=timesheet', 'Availability Overview', 'Availability', 'edit_posts', 'stm-availability-overview', 'stm_availability_overview_plug_page' );
	
}

function stm_availability_overview_plug_page(){
	echo "<div class='wrap'>";
		echo "<h2>Availability Overview</h2>";
		
	?>
	<style type="text/css">
		tr.stm_emp_post_Pending td{ color:#FF0000; }
	</style>
	<?php	
		
		
	global $wpdb;	
	$table_timesheet = $wpdb->base_prefix . "stm_timesheet_member";	
	
	//$timesheet_data = $wpdb->get_results("SELECT * FROM $table_timesheet WHERE timesheet_id <> ''", ARRAY_A);
	$timesheet_data = $wpdb->get_results("SELECT DISTINCT employee_id FROM $table_timesheet WHERE timesheet_id <> ''", ARRAY_A);
	

	if ( ! empty( $timesheet_data ) ) {	
	
		$timesheet_member_arr = array();
		foreach($timesheet_data as $timesheet){
			$employee_id = $timesheet['employee_id'];
			$employee_info = get_userdata($employee_id);
			$employee_name = $employee_info->display_name;	
			$timesheet_member_arr[$timesheet['employee_id']] = $employee_name;	
			asort($timesheet_member_arr);
				
		}	
		
	
		echo '<table class="form-table">
			<tr>
				<th>Name</th>
				<th>Hours <br /> Available</th>
				<th>M</th>				
				<th>Tu</th>
				<th>W</th>
				<th>Th</th>
				<th>F</th>
				<th>Availability<br /> Total</th>
			</tr>';	
			
		$set_total_av_hours = 0;
		$rest_total_av_hours = 0;
			
		foreach($timesheet_member_arr as $employee_id => $employee_name){
//		foreach($timesheet_data as $timesheet){		
//			$employee_id = $timesheet['employee_id'];
//			$employee_info = get_userdata($employee_id);
//			$employee_name = $employee_info->first_name;
			
			/* ########## */
			//get_available_hours($employee_id);
			/* ########## */
			$profile_post_id = esc_attr( get_the_author_meta( '_stm_timesheet_post_id', $employee_id ) );
			
			$emp_total_hour = 0;
			$emp_rest_total_hour = 0;
			$monday = 0;
			$tuesday = 0;
			$wednesday = 0;
			$thursday = 0;
			$friday = 0;
			
			$timesheet_status = 'stm_emp_status stm_emp_post_';
			
			if(get_status_class($profile_post_id))
				$timesheet_status .= get_status_class($profile_post_id);
			
			
			$emp_total_hour += get_hours_a_day($employee_id, 'Monday');
			$emp_total_hour += get_hours_a_day($employee_id, 'Tuesday');
			$emp_total_hour += get_hours_a_day($employee_id, 'Wednesday');
			$emp_total_hour += get_hours_a_day($employee_id, 'Thursday');
			$emp_total_hour += get_hours_a_day($employee_id, 'Friday');
			
			echo "<tr class='".$timesheet_status."'>";
				echo "<td>";
					echo $employee_name;
				echo "</td>";
					
				echo "<td>";
					echo $emp_total_hour;
					$set_total_av_hours += $emp_total_hour;
				echo "</td>";
				
				echo "<td>";
					//echo $monday = get_total_available_hours_a_day($employee_id, 'Monday')."<br />";
					/*echo $monday = calculate_available_hours_recursively( $employee_id, 'Monday' );*/
					$hoursArr = get_available_hours($employee_id);
					$emp_set_hours = get_hours_a_day($employee_id, 'Monday');
					$monday = ($emp_set_hours - $hoursArr[0]);
					/*if($monday < 0)
						$monday = 0;*/
					echo $monday;	
				echo "</td>";
				
				echo "<td>";
					//echo $tuesday = get_total_available_hours_a_day($employee_id, 'Tuesday')."<br />";
					/*echo $tuesday = calculate_available_hours_recursively( $employee_id, 'Tuesday' );*/
					$hoursArr = get_available_hours($employee_id);
					$emp_set_hours = get_hours_a_day($employee_id, 'Tuesday');
					$tuesday = ($emp_set_hours - $hoursArr[1]);
					/*if($tuesday < 0)
						$tuesday = 0;*/
					echo $tuesday;						
				echo "</td>";
				
				echo "<td>";
					//echo $wednesday = get_total_available_hours_a_day($employee_id, 'Wednesday')."<br />";
					/*echo $wednesday = calculate_available_hours_recursively( $employee_id, 'Wednesday' );*/
					$emp_set_hours = get_hours_a_day($employee_id, 'Wednesday');
					$hoursArr = get_available_hours($employee_id);
					$wednesday = ($emp_set_hours - $hoursArr[2]);
					/*if($wednesday < 0)
						$wednesday = 0;*/
					echo $wednesday;						
				echo "</td>";
				
				echo "<td>";
					//echo $thursday = get_total_available_hours_a_day($employee_id, 'Thursday')."<br />";
					/*echo $thursday = calculate_available_hours_recursively( $employee_id, 'Thursday' );*/
					$emp_set_hours = get_hours_a_day($employee_id, 'Thursday');
					$hoursArr = get_available_hours($employee_id);
					$thursday = ($emp_set_hours - $hoursArr[3]);
					/*if($thursday < 0)
						$thursday = 0;*/
					echo $thursday;						
				echo "</td>";
				
				echo "<td>";
					//echo $friday = get_total_available_hours_a_day($employee_id, 'Friday')."<br />";
					/*echo $friday = calculate_available_hours_recursively( $employee_id, 'Friday' );*/
					$emp_set_hours = get_hours_a_day($employee_id, 'Friday');
					$hoursArr = get_available_hours($employee_id);
					$friday = ($emp_set_hours - $hoursArr[4]);
					/*if($friday < 0)
						$friday = 0;*/
					echo $friday;						
				echo "</td>";
				
				echo "<td>";
					//echo $emp_rest_total_hour = $monday + $tuesday + $wednesday + $thursday + $friday;
					$emp_rest_total_hour = $monday + $tuesday + $wednesday + $thursday + $friday;
					$emp_rest_total_hour = number_format($emp_rest_total_hour, 2);
					echo $emp_rest_total_hour;
					$rest_total_av_hours += $emp_rest_total_hour;
					/*echo calculate_availability_hours( $employee_id );*/
				echo "</td>";				
			
			
			echo "</tr>";
				
		}	
		
		echo "<tr><td></td><td><strong>".$set_total_av_hours."</strong></td><td></td><td></td><td></td><td></td><td></td><td><strong>".$rest_total_av_hours."</strong></td></tr>";	
		
		
		//$percentage_available = 100 - ( ($rest_total_av_hours * 100 ) / $set_total_av_hours );
		$percentage_available = ( $rest_total_av_hours / $set_total_av_hours )*100;
		
		echo '<tr><td colspan="8" style="text-align:center; font-size:18px; "><strong>' . number_format($percentage_available, 2) . '% Available</strong></td></tr>';
		
		/* Custom Calculations */
		
		echo '</table>';
	}			

	echo '</div>';
}

// send availability overview to PM and Admin
function get_availitility_schedule_pdf(){
	$pdf = new PDF('L', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
	$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

	$pdf->head = "no";
	$pdf->foot = "no";
	$pdf->AddPage();
	$pdf->SetFont('helvetica','B',18);
	$pdf->SetTextColor(0, 0, 0);

	global $wpdb;	
	$table_timesheet = $wpdb->base_prefix . "stm_timesheet_member";
	
	$timesheet_data = $wpdb->get_results("SELECT DISTINCT employee_id FROM $table_timesheet WHERE timesheet_id <> ''", ARRAY_A);
	if ( ! empty( $timesheet_data ) ) {	
		$html = '<p style="margin-bottom: 20px; text-align:center;">Availability Overview</p>';
	
		$current_y = $pdf->GetY();
		$current_x = $pdf->GetX();
	
		$pdf->SetFont('helvetica','B',11);
		$pdf->SetTextColor(0, 0, 0);

		$html .= '<table cellpadding="10" cellspacing="0" border="1" style="text-align:center; font-size:12px;">';
		$html .= '<tr>
					<th>Name</th>
					<th>Hours Available</th>
					<th>M</th>
					<th>Tu</th>
					<th>W</th>
					<th>Th</th>
					<th>F</th>
					<th>Availability Total</th>
				</tr>';
	
		$pdf->SetFont('helvetica','',10);
		
		$timesheet_member_arr = array();
		foreach($timesheet_data as $timesheet){
			$employee_id = $timesheet['employee_id'];
			$employee_info = get_userdata($employee_id);
			$employee_name = $employee_info->display_name;	
			$timesheet_member_arr[$timesheet['employee_id']] = $employee_name;	
			asort($timesheet_member_arr);
		}
		
		$set_total_av_hours = 0;
		$rest_total_av_hours = 0;
			
		foreach($timesheet_member_arr as $employee_id => $employee_name){
			$profile_post_id = esc_attr( get_the_author_meta( '_stm_timesheet_post_id', $employee_id ) );
			
			$emp_total_hour = 0;
			$emp_rest_total_hour = 0;
			$monday = 0;
			$tuesday = 0;
			$wednesday = 0;
			$thursday = 0;
			$friday = 0;
			
			$timesheet_status = 'stm_emp_status stm_emp_post_';
			$current_y = $pdf->GetY();
			
			if(get_status_class($profile_post_id))
				$timesheet_status .= get_status_class($profile_post_id);
			
			
			$emp_total_hour += get_hours_a_day($employee_id, 'Monday');
			$emp_total_hour += get_hours_a_day($employee_id, 'Tuesday');
			$emp_total_hour += get_hours_a_day($employee_id, 'Wednesday');
			$emp_total_hour += get_hours_a_day($employee_id, 'Thursday');
			$emp_total_hour += get_hours_a_day($employee_id, 'Friday');

			$set_total_av_hours += $emp_total_hour;
			$hoursArr = get_available_hours($employee_id);
			$emp_set_hours = get_hours_a_day($employee_id, 'Monday');
			$monday = ($emp_set_hours - $hoursArr[0]);
			$hoursArr = get_available_hours($employee_id);
			$emp_set_hours = get_hours_a_day($employee_id, 'Tuesday');
			$tuesday = ($emp_set_hours - $hoursArr[1]);
			$emp_set_hours = get_hours_a_day($employee_id, 'Wednesday');
			$hoursArr = get_available_hours($employee_id);
			$wednesday = ($emp_set_hours - $hoursArr[2]);
			$emp_set_hours = get_hours_a_day($employee_id, 'Thursday');
			$hoursArr = get_available_hours($employee_id);
			$thursday = ($emp_set_hours - $hoursArr[3]);
			$emp_set_hours = get_hours_a_day($employee_id, 'Friday');
			$hoursArr = get_available_hours($employee_id);
			$friday = ($emp_set_hours - $hoursArr[4]);
			$emp_rest_total_hour = $monday + $tuesday + $wednesday + $thursday + $friday;
			$emp_rest_total_hour = number_format($emp_rest_total_hour, 2);

			$html .= '<tr nobr="true">
						<td>'.$employee_name.'</td>
						<td>'.$emp_total_hour.'</td>
						<td>'.$monday.'</td>
						<td>'.$tuesday.'</td>
						<td>'.$wednesday.'</td>
						<td>'.$thursday.'</td>
						<td>'.$friday.'</td>
						<td>'.$emp_rest_total_hour.'</td>
					</tr>';
			
			$rest_total_av_hours += $emp_rest_total_hour;
			
		}	

		$pdf->SetFont('helvetica','B',10);
		
		$html .= '<tr nobr="true">
					<td>&nbsp;</td>
					<td>'.$set_total_av_hours.'</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					<td>'.$rest_total_av_hours.'</td>
				</tr>';
	
		$html .= '</table>';
		
		$pdf->SetFont('helvetica','B',18);
		
		$percentage_available = ( $rest_total_av_hours / $set_total_av_hours )*100;
		
		$html .= '<p style="margin-top:20px; text-align:center;">'.number_format($percentage_available, 2).'% Available</p>';

		// output the HTML content
		$pdf->writeHTML($html, true, false, true, false, '');
	}
	
	$pdf_filename = $pdf->Output('', 'S');

	$dir = "/emp-workschedule-pdf";
	$upload_dir = wp_upload_dir();
	$schedule_pdf_dir = $upload_dir['basedir'].$dir;
	$schedule_pdf_url = $upload_dir['baseurl'].$dir;
	if( ! file_exists( $schedule_pdf_dir ) ){
		wp_mkdir_p( $schedule_pdf_dir );
	}		

	$available_filename_title = 'availability-overview-'.time();
	
	if( ! file_exists( $schedule_pdf_dir.'/'.$available_filename_title.'.pdf' ) ) {
		$pdf->Output($schedule_pdf_dir.'/'.$available_filename_title.'.pdf', 'F');
	} else {
		unlink($schedule_pdf_dir.'/'.$available_filename_title.'.pdf');
		$pdf->Output($schedule_pdf_dir.'/'.$available_filename_title.'.pdf', 'F');
	}		
	
	$availability_schedule_pdf_file_emp = $schedule_pdf_dir.'/'.$available_filename_title.'.pdf';
	
	return $availability_schedule_pdf_file_emp;

}

function get_available_hours($employee_id) {
	global $wpdb;
	$total_available_hours = 0;
	$total_estimate_hours = 0;

	$monday_hours = 0;
	$tuesday_hours = 0;
	$wednesday_hours = 0;
	$thursday_hours = 0;
	$friday_hours = 0;
	
	$table_timesheet = $wpdb->base_prefix . "stm_timesheet";
	$timesheet_data = $wpdb->get_results("SELECT estimate_hour, assigned_day, task_day, due_date FROM $table_timesheet WHERE assignee = '$employee_id'", ARRAY_A);
	
	foreach($timesheet_data as $timesheet) {
		$days = get_day_names_between_assign_and_due_date( $timesheet['assigned_day'], $timesheet['due_date'] );
		$day_num = get_number_of_days_between_due_and_assigned_date($timesheet['assigned_day'], $timesheet['due_date']);
			$hour = $timesheet['estimate_hour'] / $day_num;

			foreach($days as $day) {
				if($day == 'Monday') {
					$monday_hours += $hour;
				}
				if($day == 'Tuesday') {
					$tuesday_hours += $hour;
				}
				if($day == 'Wednesday') {
					$wednesday_hours += $hour;
				}
				if($day == 'Thursday') {
					$thursday_hours += $hour;
				}
				if($day == 'Friday') {
					$friday_hours += $hour;
				}
			}
	}
	return array(round($monday_hours, 2), round($tuesday_hours, 2), round($wednesday_hours, 2), round($thursday_hours, 2), round($friday_hours, 2));
}



function get_day_number($day = 'Monday'){
	$number = 0;	
	if($day == 'Monday')
		$number = 1;		
	if($day == 'Tuesday')
		$number = 2;		
	if($day == 'Wednesday')
		$number = 3;		
	if($day == 'Thursday')
		$number = 4;		
	if($day == 'Friday')
		$number = 5;		
	return $number;
}
function get_day_name($number = 1){
	$day = 0;
	if($number == 1)
		$day = 'Monday';
	if($number == 2)
		$day = 'Tuesday';
	if($number == 3)
		$day = 'Wednesday';
	if($number == 4)
		$day = 'Thursday';
	if($number == 5)
		$day = 'Friday';
	return $day;
}




function calculate_availability_hours( $employee_id ) {
	global $wpdb;
	$total_estimate_hours = 0;
	$table_timesheet = $wpdb->base_prefix . "stm_timesheet";
	$timesheet_data = $wpdb->get_results("SELECT estimate_hour FROM $table_timesheet WHERE assignee = '$employee_id'", ARRAY_A);
	foreach($timesheet_data as $timesheet){		
		$estimate_hours = $timesheet['estimate_hour'];
		$total_estimate_hours += $estimate_hours;
	}	
	
	//echo ' TEST = '.$total_estimate_hours;	
	//return $total_estimate_hours;	
	
}


function calculate_available_hours_recursively( $employee_id, $day ) {
	global $wpdb;
	$available_hour = 0;
	$total_estimate_hours = 0;
	//$due_day_interval = array();
	
	$weekdays = array('Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday');

	$table_timesheet = $wpdb->base_prefix . "stm_timesheet";
	
	$emp_set_hours = get_hours_a_day($employee_id, $day);
	$emp_assigned_hours = get_total_assigned_hours_a_day($employee_id, $day);
	
	$total_over_head_monday = 0;
	$total_over_head_tuesday = 0;
	$total_over_head_monday = 0;
	$total_over_head_monday = 0;
	$total_over_head_monday = 0;
	
	
	$overhead_monday = 0;
	$overhead_tuesday = 0;
	$overhead_wednesday = 0;
	$overhead_thursday = 0;
	$overhead_friday = 0;

	$hour_can_added = 0;

	$overhead_monday = get_total_over_head_hours_a_day($employee_id, 'Monday');
	$overhead_tuesday = get_total_over_head_hours_a_day($employee_id, 'Tuesday');
	$overhead_wednesday = get_total_over_head_hours_a_day($employee_id, 'Wednesday');
	$overhead_thursday = get_total_over_head_hours_a_day($employee_id, 'Thursday');
	$overhead_friday = get_total_over_head_hours_a_day($employee_id, 'Friday');
	
	$total_over_head_monday = $overhead_monday + $overhead_tuesday + $overhead_wednesday + $overhead_thursday + $overhead_friday;
	$total_over_head_tuesday = $overhead_tuesday + $overhead_wednesday + $overhead_thursday + $overhead_friday;
	
	if($day == 'Monday'){
		$emp_set_hours = get_hours_a_day($employee_id, 'Monday');
		$emp_assigned_hours_Monday = get_total_assigned_hours_a_day($employee_id, 'Monday');
		$emp_assigned_hours_Tuesday = get_total_assigned_hours_a_day($employee_id, 'Tuesday') / 2;
		$emp_assigned_hours_Wednesday = get_total_assigned_hours_a_day($employee_id, 'Wednesday') / 3;
		$emp_assigned_hours_Thursday = get_total_assigned_hours_a_day($employee_id, 'Thursday') / 4;
		$emp_assigned_hours_Friday = get_total_assigned_hours_a_day($employee_id, 'Friday') / 5;		
		$available_hour = 	$emp_set_hours - ($emp_assigned_hours_Monday + $emp_assigned_hours_Tuesday + $emp_assigned_hours_Wednesday + $emp_assigned_hours_Thursday + $emp_assigned_hours_Friday);
		//$available_hour = 	($emp_assigned_hours_Monday + $emp_assigned_hours_Tuesday + $emp_assigned_hours_Wednesday + $emp_assigned_hours_Thursday + $emp_assigned_hours_Friday);
	}	
	
	if($day == 'Tuesday'){
		$emp_set_hours = get_hours_a_day($employee_id, 'Tuesday');
		$emp_assigned_hours_Tuesday = get_total_assigned_hours_a_day($employee_id, 'Tuesday');
		$emp_assigned_hours_Wednesday = get_total_assigned_hours_a_day($employee_id, 'Wednesday') / 3;
		$emp_assigned_hours_Thursday = get_total_assigned_hours_a_day($employee_id, 'Thursday') / 4;
		$emp_assigned_hours_Friday = get_total_assigned_hours_a_day($employee_id, 'Friday') / 5;		
		$available_hour = 	$emp_set_hours - ($emp_assigned_hours_Tuesday + $emp_assigned_hours_Wednesday + $emp_assigned_hours_Thursday + $emp_assigned_hours_Friday);
		//$available_hour = 	($emp_assigned_hours_Tuesday + $emp_assigned_hours_Wednesday + $emp_assigned_hours_Thursday + $emp_assigned_hours_Friday);
	}	

	if($day == 'Wednesday'){
		$emp_set_hours = get_hours_a_day($employee_id, 'Wednesday');
		$emp_assigned_hours_Wednesday = get_total_assigned_hours_a_day($employee_id, 'Wednesday');
		$emp_assigned_hours_Thursday = get_total_assigned_hours_a_day($employee_id, 'Thursday') / 4;
		$emp_assigned_hours_Friday = get_total_assigned_hours_a_day($employee_id, 'Friday') / 5;		
		$available_hour = 	$emp_set_hours - ( $emp_assigned_hours_Wednesday + $emp_assigned_hours_Thursday + $emp_assigned_hours_Friday);
		//$available_hour = 	( $emp_assigned_hours_Wednesday + $emp_assigned_hours_Thursday + $emp_assigned_hours_Friday);
	}	

	if($day == 'Thursday'){
		$emp_set_hours = get_hours_a_day($employee_id, 'Thursday');
		$emp_assigned_hours_Thursday = get_total_assigned_hours_a_day($employee_id, 'Thursday');
		$emp_assigned_hours_Friday = get_total_assigned_hours_a_day($employee_id, 'Friday') / 5;		
		//$available_hour = 	( $emp_assigned_hours_Thursday + $emp_assigned_hours_Friday);
		$available_hour = 	$emp_set_hours - ( $emp_assigned_hours_Thursday + $emp_assigned_hours_Friday);
	}	
	

	if($day == 'Friday'){
		$emp_set_hours = get_hours_a_day($employee_id, 'Friday');
		$emp_assigned_hours_Friday = get_total_assigned_hours_a_day($employee_id, 'Friday');		
		$available_hour = 	$emp_set_hours - ($emp_assigned_hours_Friday);
		//$available_hour = 	($emp_assigned_hours_Friday);
	}	
	
	
	if($available_hour < 0)
		$available_hour = 0;
	else
		$available_hour = $available_hour;	
	
	return round($available_hour);

	//Monday
//	$hour_can_added_monday = 0;
//	$hour_can_added_monday = get_hours_a_day($employee_id, 'Monday') - get_total_assigned_hours_a_day($employee_id, 'Monday');
//	if($hour_can_added_monday > 0){
//		if($total_over_head_monday >= $hour_can_added_monday)
//			$monday_working_hour = $emp_assigned_hours + $hour_can_added_monday;
//		else
//			$monday_working_hour = $emp_assigned_hours + $total_over_head_monday;
//		
//		$total_over_head_monday = $total_over_head_monday - $hour_can_added_monday;
//	}



	//Tuesday
//	$hour_can_added_tuesday = 0;
//	$hour_can_added_tuesday = get_hours_a_day($employee_id, 'Tuesday') - get_total_assigned_hours_a_day($employee_id, 'Tuesday');
//	if($hour_can_added_tuesday > 0){
//		if($total_over_head_tuesday >= $hour_can_added_tuesday)
//			$tuesday_working_hour = $emp_assigned_hours + $hour_can_added_tuesday;
//		else	
//			$tuesday_working_hour = $emp_assigned_hours + $total_over_head_tuesday;
//			
//		$total_over_head_tuesday = $total_over_head_tuesday - $hour_can_added_tuesday;
//	}


	
	//Monday
//	if($day == 'Monday'){
//		echo $monday_working_hour;	
//	}
	
	//Monday
//	if($day == 'Tuesday'){
//		echo $tuesday_working_hour;	
//	}
	
	
	


}



function calculate_available_hours_recursively____old( $employee_id ) {
	global $wpdb;
	$total_available_hours = 0;
	$total_estimate_hours = 0;
	//$due_day_interval = array();
	
	$weekdays = array('Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday');

	$table_timesheet = $wpdb->base_prefix . "stm_timesheet";
	
	foreach($weekdays as $day) {
	
		$timesheet_data = $wpdb->get_results("SELECT estimate_hour, task_day FROM $table_timesheet WHERE assignee = '$employee_id' AND due_date = '$day'", ARRAY_A);
		
		foreach($timesheet_data as $timesheet) {
			$estimate_hours = $timesheet['estimate_hour'];

			$inbetween_days = get_day_names_between_assign_and_due_date( $timesheet['task_day'], $day );
			
			foreach($inbetween_days as $prev_day) {
				if( $prev_day != $day ) {
					$prev_timesheet_data = $wpdb->get_results("SELECT estimate_hour, task_day FROM $table_timesheet WHERE assignee = '$employee_id' AND due_date = '$prev_day'", ARRAY_A);
					foreach($prev_timesheet_data as $prev_timesheet) {
						$prev_estimate_hours = $prev_timesheet['estimate_hour'];
					}
					//calculate_available_hours_recursively( $employee_id );
				}
			}

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
	}
}