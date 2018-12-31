<?php
add_action( 'post_submitbox_misc_actions', 'stm_custom_preview_and_print_button' );

function stm_custom_preview_and_print_button(){
	global $current_screen;
	if( $current_screen->post_type == 'timesheet' ) {	
		global $post;
		$current_post_id = $post->ID;
		
		$emp_id = get_post_meta( $post->ID, '_stm_employee_id', true );
		
		$user_profile_url = get_edit_user_link( $emp_id );
		
		//Auto generated preview timesheet.
		generate_workschedule_pdf_add_to_timesheet($current_post_id, $emp_id);
		
		$titmesheet_edit_url = get_edit_post_link( $current_post_id );
		$titmesheet_edit_url  =  add_query_arg( 'preview_pdf', 'do', $titmesheet_edit_url );
		$titmesheet_edit_url  =  add_query_arg( 'empid', $emp_id, $titmesheet_edit_url );

        $html  = '<div id="major-publishing-actions" style="overflow:hidden">';
		$html .= '<a id="print_preview_timesheet" data-employee_id="'.$emp_id.'" data-timesheet_id="'.$current_post_id.'" href="javascript:void(0)" class="preview button">Schedule Preview</a>';
		$html .= '<a title="Click Update View Before Printing Your Latest Schedule" id="print_timesheet" data-employee_id="'.$emp_id.'" data-timesheet_id="'.$current_post_id.'" style="margin-right:50px;" href="javascript:void(0)" class="preview button">Schedule Print</a><div id="load_doc_iframe"></div>';	
		
		$html .= '<a id="view_timesheet_profile" style="margin-right:50px;" href="'.$user_profile_url.'" class="preview button">Profile</a>';	
		
        $html .= '</div>';

        echo $html;
		?>
		<script type="text/javascript">
			jQuery(document).on('click','#print_timesheet', function(){
				var employee_id = jQuery(this).data("employee_id");
				var timesheet_id = jQuery(this).data("timesheet_id");
				//alert(employee_id);		
				var dataContainer = {
					employee_id: employee_id,
					timesheet_id: timesheet_id,
					action: 'print-employee-data-item'
				};	
				jQuery.ajax({
					action: "print-employee-data-item",
					type: "POST",
					dataType: "json",
					url: ajaxurl,			
					data: dataContainer,
					success: function(data){
						//alert(data.msg);
						var createNewFrame = '<iframe style="display:none;" id="iFramePdf_Print" src="'+data.fileurl+'"></iframe>';
						jQuery('#load_doc_iframe').html(createNewFrame);
						//jQuery('#iFramePdf_Print').attr('src',data.fileurl);
						//relaod iframe with latest value
						//document.getElementById('#iFramePdf_Print').reload(true);
						var getMyFrame = document.getElementById('iFramePdf_Print');
						try {
							getMyFrame.focus();
							getMyFrame.contentWindow.print();
							//jQuery('#iFramePdf_Print').attr('src','');
							return false;
						}
						catch (e) {
							window.print(false);
						}
					
					}
				});
			});

			jQuery(document).on('click','#print_preview_timesheet', function(){
				var employee_id = jQuery(this).data("employee_id");
				var timesheet_id = jQuery(this).data("timesheet_id");
				//alert(employee_id);		
				var dataContainer = {
					employee_id: employee_id,
					timesheet_id: timesheet_id,
					action: 'print-employee-data-item'
				};	
				jQuery.ajax({
					action: "print-employee-data-item",
					type: "POST",
					dataType: "json",
					url: ajaxurl,
					data: dataContainer,
					success: function(data) {
						//alert(data.msg);
						var pdf_file_url = data.fileurl;
						//window.location.href = pdf_file_url;
						window.open(pdf_file_url, '_blank');
					}
				});
			});
		</script>
		<style type="text/css">
			#edit-slug-buttons, .edit-post-status.hide-if-no-js, #preview-action, #edit-slug-box{ display:none; }
		</style>
		<?php
	}	
}   


add_action('wp_ajax_print-employee-data-item', 'print_employee_timesheet_data_item');
add_action('wp_ajax_nopriv_print-employee-data-item', 'print_employee_timesheet_data_item');
function print_employee_timesheet_data_item(){

	$current_post_id = $_POST['timesheet_id'];
	$employee_id = $_POST['employee_id'];
	$timesheet_id = $_POST['timesheet_id'];
	
	generate_workschedule_pdf_add_to_timesheet($current_post_id, $employee_id);
	
	$employee_user_info = get_userdata($employee_id);
	
	if( in_array('team_leader', $employee_user_info->roles) || in_array('project_manager', $employee_user_info->roles)) {
		$request_file_url = get_post_meta($current_post_id, '_stm_schedule_team_pdf', true);
	}else{
		$request_file_url = get_post_meta($current_post_id, '_stm_schedule_pdf', true);
	}
	
	echo json_encode(array("fileurl" => $request_file_url, "timesheet_id" => $current_post_id));
	exit;
		
}

function generate_workschedule_pdf_add_to_timesheet($current_post_id, $employee_id) {
	global $wpdb;		
	$employee_user_info = get_userdata($employee_id);
	$employee_name = $employee_user_info->display_name;	
	
	if( in_array('team_leader', $employee_user_info->roles) || in_array('project_manager', $employee_user_info->roles) ) {
	
		$table_timesheet = $wpdb->base_prefix . "stm_timesheet";
		$timesheet_arr = $wpdb->get_results("SELECT DISTINCT ID, client_name, my_task, estimate_hour, due_date, assigned_day, assignee, created_by, assigned_by FROM $table_timesheet WHERE ( created_by = '$employee_id' OR assignee = '$employee_id' ) ORDER BY FIELD(assigned_day, 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday')", ARRAY_A);

		$employee_info = get_userdata($employee_id);
		$employee_name = $employee_info->first_name;
		
		$pdf = new PDF('L', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
		$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
		$pdf->head = "no";
		$pdf->foot = "no";
		$pdf->AddPage();
		$pdf->SetFont('helvetica','B',11);
		$pdf->SetTextColor(0, 0, 0);			

		$html = "<p style=\"font-size:14px; font-weight:bold;\">".$employee_name."'s Work Schedule</p>";

		$current_y = $pdf->GetY();
		$current_x = $pdf->GetX();

		$pdf->SetFont('helvetica','B',11);
		$pdf->SetTextColor(0, 0, 0);

		$html .= '<table cellpadding="10" cellspacing="0" border="1" style="text-align:center;">';
		$html .= '<tr>
					<th width="165" style="font-weight:bold;">Client Name</th>
					<th width="165" style="font-weight:bold;">My Task</th>';
		if( in_array('team_leader', $employee_info->roles) || in_array('project_manager', $employee_info->roles) ) {
			$html .= '<th width="47" style="font-weight:bold;">My Time</th>
					<th width="53" style="font-weight:bold;">Others Time</th>';
		} else {
			$html .= '<th width="100" style="font-weight:bold;">My Time</th>';
		}
		$html .= '<th width="74" style="font-weight:bold;">Start Date</th>
				<th width="74" style="font-weight:bold;">Due Date</th>
				<th width="83" style="font-weight:bold;">Assigned by</th>
				<th width="83" style="font-weight:bold;">Assigned to</th>
			</tr>';

		$pdf->SetFont('helvetica','',10);
		$my_total = $others_total = $est_total = $row_total = 0;
		$start_days = array();
		foreach($timesheet_arr as $emp_timesheet) {
			$assignee_user_info = get_userdata($emp_timesheet['created_by']);
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
				$pdf->SetFillColor(255,255,255);
				$trbgcolor = '';
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
		for($bline = 1; $bline <= 6; $bline++) {
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
			$html .= '<tr nobr="true">
						<td colspan="2">Total</td>';
			if( in_array('team_leader', $employee_info->roles) ) {
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
		
		$pdf_filename = $pdf->Output('', 'S');
		
		$dir = "/emp-workschedule-pdf";
		$upload_dir = wp_upload_dir();
		$lease_pdf_dir = $upload_dir['basedir'].$dir;
		$lease_pdf_url = $upload_dir['baseurl'].$dir;
		if( ! file_exists( $lease_pdf_dir ) ){
			wp_mkdir_p( $lease_pdf_dir );
		}		
		$filename_title = strtolower(str_replace(" ", "-", $employee_name)).'-workschedule-team';	
		if( ! file_exists( $lease_pdf_dir.'/'.$filename_title.'.pdf' ) ) {
			$pdf->Output($lease_pdf_dir.'/'.$filename_title.'.pdf', 'F');
		} else {
			unlink($lease_pdf_dir.'/'.$filename_title.'.pdf');
			$pdf->Output($lease_pdf_dir.'/'.$filename_title.'.pdf', 'F');
		}		
		$generated_pdf_url = $lease_pdf_url.'/'.$filename_title.'.pdf';
		update_post_meta($current_post_id, '_stm_schedule_team_pdf', $generated_pdf_url);
			
	}else{				
		$table_timesheet = $wpdb->base_prefix . "stm_timesheet";
		$timesheet_arr = $wpdb->get_results("SELECT DISTINCT ID, client_name, my_task, estimate_hour, due_date, assigned_day, assignee, created_by, assigned_by FROM $table_timesheet WHERE ( created_by = '$employee_id' OR assignee = '$employee_id' ) ORDER BY FIELD(assigned_day, 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday')", ARRAY_A);		
		$pdf = new PDF('L', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
		$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
		$pdf->head = "no";
		$pdf->foot = "no";
		$pdf->AddPage();
		$pdf->SetFont('helvetica','B',11);
		$pdf->SetTextColor(0, 0, 0);		
		$html = "<p style=\"font-size:14px; font-weight:bold;\">".$employee_name."'s Work Schedule</p>";

		$html .= '<table cellpadding="10" cellspacing="0" border="1" style="text-align:center;">';
		$html .= '<tr>
					<th width="220" style="font-weight:bold;">Client Name</th>
					<th width="220" style="font-weight:bold;">My Task</th>
					<th width="52" style="font-weight:bold;">My Time</th>
					<th width="74" style="font-weight:bold;">Start Date</th>
					<th width="74" style="font-weight:bold;">Due Date</th>
					<th width="100" style="font-weight:bold;">Assigned by</th>
				</tr>';

		$pdf->SetFont('helvetica','',10);
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
			//$assignee_user_info = get_userdata($timesheet_assign_log['assigned_by']);
			$assignee_user_info = get_userdata($emp_timesheet['created_by']);
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
		$lease_pdf_dir = $upload_dir['basedir'].$dir;
		$lease_pdf_url = $upload_dir['baseurl'].$dir;
		if( ! file_exists( $lease_pdf_dir ) ){
			wp_mkdir_p( $lease_pdf_dir );
		}
		$filename_title = strtolower(str_replace(" ", "-", $employee_name)).'-workschedule';
		if( ! file_exists( $lease_pdf_dir.'/'.$filename_title.'.pdf' ) ) {
			$pdf->Output($lease_pdf_dir.'/'.$filename_title.'.pdf', 'F');
		} else {
			unlink($lease_pdf_dir.'/'.$filename_title.'.pdf');
			$pdf->Output($lease_pdf_dir.'/'.$filename_title.'.pdf', 'F');
		}
		$generated_pdf_url = $lease_pdf_url.'/'.$filename_title.'.pdf';
		update_post_meta($current_post_id, '_stm_schedule_pdf', $generated_pdf_url);
	}
	
	return true;
}