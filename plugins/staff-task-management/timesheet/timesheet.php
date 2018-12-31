<?php
/**
 * Adds a box to the main column on the Post and Page edit screens.
 */
function stm_timesheet_add_meta_box() {
	$screens = array( 'timesheet');
	foreach ( $screens as $screen ) {
		add_meta_box('stm_timesheet_section_monday_id',	__( 'MONDAY', 'stm_timesheet_textdomain' ),'stm_timesheet_monday_meta_box_callback',  $screen );
		add_meta_box('stm_timesheet_section_tuesday_id',	__( 'TUESDAY', 'stm_timesheet_textdomain' ),'stm_timesheet_tuesday_meta_box_callback',  $screen );
		add_meta_box('stm_timesheet_section_wednesday_id',	__( 'WEDNESDAY', 'stm_timesheet_textdomain' ),'stm_timesheet_wednesday_meta_box_callback',  $screen );
		add_meta_box('stm_timesheet_section_thursday_id',	__( 'THURSDAY', 'stm_timesheet_textdomain' ),'stm_timesheet_thursday_meta_box_callback',  $screen );
		add_meta_box('stm_timesheet_section_friday_id',	__( 'FRIDAY', 'stm_timesheet_textdomain' ),'stm_timesheet_friday_meta_box_callback',  $screen );
		add_meta_box('stm_timesheet_section_hour_left',	__( 'Hours Left', 'stm_timesheet_hour_left_textdomain' ),'stm_timesheet_hour_left_meta_box_callback',  $screen );
		add_meta_box('stm_timesheet_section_clear',	__( 'Clear', 'stm_timesheet_textdomain' ),'stm_timesheet_clear_meta_box_callback',  $screen );
	}
}
add_action( 'add_meta_boxes', 'stm_timesheet_add_meta_box' );

/**
 * Prints section fro Reset timesheet
 *
 * @param WP_Post $post The object for the current post/page.
 */
function stm_timesheet_clear_meta_box_callback( $post ) {
	global $post;
	// Add a nonce field so we can check for it later.
	wp_nonce_field( 'stm_timesheet_clear_save_meta_box_data', 'stm_timesheet_clear_meta_box_nonce' );

	$current_user_id = get_current_user_id();
	$current_timesheet_author_id = get_post_meta( $post->ID, '_stm_employee_id', true );

//	if(get_post_meta( $post->ID, 'unassigned_task', true ))
//		return;

//	if(isset($_POST['clear_timesheet')){
//		//echo $employee_id = $_POST['employee_id'];
//	}

	?>

	<style type="text/css">

	#poststuff #post-body.columns-2 #side-sortables{ position:fixed; }

	#poststuff #post-body.columns-2 #side-sortables #major-publishing-actions a{
		float:left;
		margin:5px 0;
	}

	<?php if(get_post_meta( $post->ID, 'unassigned_task', true )){ ?>
	#delete-action{ display:none;}
	<?php } ?>

	.clear_message{ text-align:center; }
	.clear_message{ color:#FF0000; }
	</style>

		<form action="" name="frm_clear" id="frm_clear">
		<div class="clear_message" id="clear_message"></div>
		<?php
			$unassigned_task = 'no';
			if(get_post_meta( $post->ID, 'unassigned_task', true )){
				$unassigned_task = 'yes';
			}
		?>
		<input type="hidden" name="unassigned_timesheet_status" id="unassigned_timesheet_status" value="<?php echo $unassigned_task; ?>" />
		<p><input type="button" name="clear_timesheet" id="clear_timesheet" value="Clear Now" class="button delete button-primary" /></p>
		<input type="hidden" name="request_employee_id" id="request_employee_id" value="<?php echo $current_user_id; ?>" />
		<input type="hidden" name="request_employee_timesheet_id" id="request_employee_timesheet_id" value="<?php echo $current_timesheet_author_id; ?>" />
		<p></p>
		</form>
	<?php
}


/**
 * Prints section fro Reset timesheet
 *
 * @param WP_Post $post The object for the current post/page.
 */
function stm_timesheet_hour_left_meta_box_callback( $post ) {
	global $post;
	// Add a nonce field so we can check for it later.
	wp_nonce_field( 'stm_timesheet_hour_left_save_meta_box_data', 'stm_timesheet_hour_meta_box_nonce' );

	if(get_post_meta( $post->ID, 'unassigned_task', true ))
		return;

	$current_user_id = get_current_user_id();
	$current_timesheet_author_id = get_post_meta( $post->ID, '_stm_employee_id', true );

	$total_working_hours = get_total_available_employee_hour_a_week($current_timesheet_author_id);
	$total_assigned_hours = calculate_assigned_hours_for_week($current_timesheet_author_id);
	$hours_left = $total_working_hours - $total_assigned_hours;
	echo $hours_left . ' Hours';

}



/**
 * Prints section fro Monday
 *
 * @param WP_Post $post The object for the current post/page.
 */
function stm_timesheet_monday_meta_box_callback( $post ) {
	global $post;
	// Add a nonce field so we can check for it later.
	wp_nonce_field( 'stm_timesheet_monday_save_meta_box_data', 'stm_timesheet_monday_meta_box_nonce' );



	?>
	<style type="text/css">
	.form-table th{ text-align:left !important; }
	.message_row_box{ text-align:center; }
	.message_row_box .update{ color:#7ad03a; }
	.message_row_box .error{ color:#FF0000; }
	.new_avai_hours{ color:#7ad03a; font-weight:bold; }
	select.assignee option.negative{ color:#FF0000; }
	/*tr.status_Pending td input[type=text]{ color:#FF0000; }*/

	tr.unassigned_task{ background:#ffff00; color:#000000; }
	tr.unassigned_task td a{ color:#000000; }

	tr.assigned_by_others{ background:#6fda04; color:#FFFFFF; }
	tr.assigned_by_others td a{ color:#FFFFFF; }

	tr.assigned_by_me{ background:#00a0d2; color:#FFFFFF; }
	tr.assigned_by_me td a{ color:#FFFFFF; }

	<?php
	if ( !current_user_can( 'manage_options' ) ) {
		?>#postcustom{ display:none; }<?php
	}
	?>
	</style>
	<?php
		global $wpdb;
		$row_day = 1;
		$row_timesheet = 1;

		$current_user_id = get_current_user_id();
		$current_timesheet_author_id = get_post_meta( $post->ID, '_stm_employee_id', true );

		$allow_like_team = false;
		if(get_user_meta( $current_user_id, '_stm_assign_own_tasks', true )){
			$allow_like_team = TRUE;
		}

		$day = 'Monday';
		$select_emp_id = '';

	?>
		<table class="form-table cmb_metabox">
			<tr>
				<th>Client Name</th>
				<th>My Task</th>
				<th>Hours</th>
				<th>Assigned to</th>
				<?php if($allow_like_team){ ?>
					<th>PM</th>
				<?php }else{ ?>
					<th style="display:none;">PM</th>
				<?php } ?>
				<th>Due Date</th>
				<th style="width:190px;"></th>
			</tr>

			<?php
				$table_timesheet = $wpdb->base_prefix . "stm_timesheet";
				if(get_post_meta( $post->ID, 'unassigned_task', true )){
					$timesheet_data = $wpdb->get_results("SELECT ID FROM $table_timesheet WHERE (assignee = 0 || assignee = '') AND assigned_day = '$day'", ARRAY_A);
				}elseif(($current_timesheet_author_id == $current_user_id) && (check_current_user_role( 'project_manager' ))){
					$timesheet_data = $wpdb->get_results("SELECT ID FROM $table_timesheet WHERE ( assignee = '$current_timesheet_author_id' OR created_by = '$current_timesheet_author_id' OR task_pm = '$current_timesheet_author_id' OR task_pm = '$current_user_id' ) AND status <> 'Complete' AND assigned_day = '$day'", ARRAY_A);
				}elseif(($current_timesheet_author_id != $current_user_id) && (check_current_user_role( 'project_manager' ))){
					$timesheet_data = $wpdb->get_results("SELECT ID FROM $table_timesheet WHERE ( assignee = '$current_timesheet_author_id' ) AND status <> 'Complete' AND assigned_day = '$day'", ARRAY_A);
				}else{
					$timesheet_data = $wpdb->get_results("SELECT ID FROM $table_timesheet WHERE ( assignee = '$current_timesheet_author_id' ) AND status <> 'Complete' AND assigned_day = '$day'", ARRAY_A);
				}

					//$timesheet_data = $wpdb->get_results("SELECT ID FROM $table_timesheet WHERE ( assignee = '$current_timesheet_author_id' OR created_by = '$current_timesheet_author_id' ) AND status <> 'Complete' AND assigned_day = '$day'", ARRAY_A);

					//$timesheet_data = $wpdb->get_results("SELECT ID FROM $table_timesheet WHERE ( ( (assigned_by = '$current_user_id' OR created_by = '$current_user_id') AND timesheet_user_id = '$current_timesheet_author_id' ) OR assignee = '$current_timesheet_author_id' ) AND  ( (assignee = 0 AND timesheet_user_id = '$current_timesheet_author_id') OR assignee <> 0 ) AND  status <> 'Complete' AND assigned_day = '$day'", ARRAY_A);

					//$timesheet_data = $wpdb->get_results("SELECT ID FROM $table_timesheet WHERE ( assigned_by = '$current_timesheet_author_id' OR created_by = '$current_timesheet_author_id' OR assignee = '$current_timesheet_author_id' ) AND status <> 'Complete' AND assigned_day = '$day'", ARRAY_A);

				//print_r($timesheet_data);

				foreach($timesheet_data as $timesheet){
					$timesheet_id = $timesheet['ID'];
					$set_row_id = '_'.$row_day.'_'.$row_timesheet;
					create_timesheet_row_html($set_row_id, $day, $select_emp_id, $timesheet_id, true);
					$row_timesheet += 1;
				}

			?>


			<?php
			if( !get_post_meta( $post->ID, 'unassigned_task', true ) ){

				for( $i = 1; $i <= 8; $i++){
					$set_row_id = '_'.$row_day.'_'.$row_timesheet;
					$row_timesheet += 1;
					create_timesheet_row_html($set_row_id, $day, $select_emp_id);
				}

				/*$set_row_id = '_'.$row_day.'_'.$row_timesheet;
				$row_timesheet += 1;
				create_timesheet_row_html($set_row_id, $day, $select_emp_id, $db_row);

				$set_row_id = '_'.$row_day.'_'.$row_timesheet;
				$row_timesheet += 1;
				create_timesheet_row_html($set_row_id, $day, $select_emp_id);*/
			}
			?>


		</table>

		<a href="" class="button button-primary">Add Row +</a> <br />
		<small>Note: Please save all rows first before adding new row. It will reload this page and see 3 blank rows</small>

	<?php
}


/**
 * Prints section fro Tuesday
 *
 * @param WP_Post $post The object for the current post/page.
 */
function stm_timesheet_tuesday_meta_box_callback( $post ) {
	global $post;
	// Add a nonce field so we can check for it later.
	wp_nonce_field( 'stm_timesheet_tuesday_save_meta_box_data', 'stm_timesheet_tuesday_meta_box_nonce' );

	?>
	<style type="text/css">
	.form-table th{ text-align:left !important; }
	</style>
	<?php
		global $wpdb;
		$row_day = 2;
		$row_timesheet = 0;
		$current_user_id = get_current_user_id();
		$current_timesheet_author_id = get_post_meta( $post->ID, '_stm_employee_id', true );
		$allow_like_team = false;
		if(get_user_meta( $current_user_id, '_stm_assign_own_tasks', true )){
			$allow_like_team = TRUE;
		}
		$day = 'Tuesday';
		$select_emp_id = '';
	?>
		<table class="form-table cmb_metabox">
			<tr>
				<th>Client Name</th>
				<th>My Task</th>
				<th>Hours</th>
				<th>Assigned to</th>
				<?php if($allow_like_team){ ?>
					<th>PM</th>
				<?php }else{ ?>
					<th style="display:none;">PM</th>
				<?php } ?>
				<th>Due Date</th>
				<th style="width:190px;"></th>
			</tr>
			<?php
				$table_timesheet = $wpdb->base_prefix . "stm_timesheet";
				if(get_post_meta( $post->ID, 'unassigned_task', true )){
					$timesheet_data = $wpdb->get_results("SELECT ID FROM $table_timesheet WHERE (assignee = 0 || assignee = '') AND assigned_day = '$day'", ARRAY_A);
				}elseif(($current_timesheet_author_id == $current_user_id) && (check_current_user_role( 'project_manager' ))){
					$timesheet_data = $wpdb->get_results("SELECT ID FROM $table_timesheet WHERE ( assignee = '$current_timesheet_author_id' OR created_by = '$current_timesheet_author_id' OR task_pm = '$current_user_id' ) AND status <> 'Complete' AND assigned_day = '$day'", ARRAY_A);
				}elseif(($current_timesheet_author_id != $current_user_id) && (check_current_user_role( 'project_manager' ))){
					$timesheet_data = $wpdb->get_results("SELECT ID FROM $table_timesheet WHERE ( assignee = '$current_timesheet_author_id' ) AND status <> 'Complete' AND assigned_day = '$day'", ARRAY_A);
				}else{
					$timesheet_data = $wpdb->get_results("SELECT ID FROM $table_timesheet WHERE ( assignee = '$current_timesheet_author_id' ) AND status <> 'Complete' AND assigned_day = '$day'", ARRAY_A);
				}

				foreach($timesheet_data as $timesheet){
					$timesheet_id = $timesheet['ID'];
					$set_row_id = '_'.$row_day.'_'.$row_timesheet;
					create_timesheet_row_html($set_row_id, $day, $select_emp_id, $timesheet_id, true);
					$row_timesheet += 1;
				}

			?>


			<?php
			if( !get_post_meta( $post->ID, 'unassigned_task', true ) ){
				for( $i = 1; $i <= 8; $i++){
					$set_row_id = '_'.$row_day.'_'.$row_timesheet;
					$row_timesheet += 1;
					create_timesheet_row_html($set_row_id, $day, $select_emp_id);
				}

			/*$set_row_id = '_'.$row_day.'_'.$row_timesheet;
			$row_timesheet += 1;
			create_timesheet_row_html($set_row_id, $day, $select_emp_id, $db_row);

			$set_row_id = '_'.$row_day.'_'.$row_timesheet;
			$row_timesheet += 1;
			create_timesheet_row_html($set_row_id, $day, $select_emp_id);*/
			}
			?>


		</table>
		<a href="" class="button button-primary">Add Row +</a> <br />
		<small>Note: Please save all rows first before adding new row. It will reload this page and see 3 blank rows</small>

	<?php
}


/**
 * Prints section fro Wednesday
 *
 * @param WP_Post $post The object for the current post/page.
 */
function stm_timesheet_wednesday_meta_box_callback( $post ) {
	global $post;
	// Add a nonce field so we can check for it later.
	wp_nonce_field( 'stm_timesheet_wednesday_save_meta_box_data', 'stm_timesheet_wednesday_meta_box_nonce' );

	?>
	<style type="text/css">
	.form-table th{ text-align:left !important; }
	</style>
	<?php
		global $wpdb;
		$row_day = 3;
		$row_timesheet = 1;
		$current_user_id = get_current_user_id();
		$current_timesheet_author_id = get_post_meta( $post->ID, '_stm_employee_id', true );
		$allow_like_team = false;
		if(get_user_meta( $current_user_id, '_stm_assign_own_tasks', true )){
			$allow_like_team = TRUE;
		}
		$day = 'Wednesday';
		$select_emp_id = '';
	?>
		<table class="form-table cmb_metabox">
			<tr>
				<th>Client Name</th>
				<th>My Task</th>
				<th>Hours</th>
				<th>Assigned to</th>
				<?php if($allow_like_team){ ?>
					<th>PM</th>
				<?php }else{ ?>
					<th style="display:none;">PM</th>
				<?php } ?>
				<th>Due Date</th>
				<th style="width:190px;"></th>
			</tr>
			<?php
				$table_timesheet = $wpdb->base_prefix . "stm_timesheet";
				if(get_post_meta( $post->ID, 'unassigned_task', true )){
					$timesheet_data = $wpdb->get_results("SELECT ID FROM $table_timesheet WHERE (assignee = 0 || assignee = '') AND assigned_day = '$day'", ARRAY_A);
				}elseif(($current_timesheet_author_id == $current_user_id) && (check_current_user_role( 'project_manager' ))){
					$timesheet_data = $wpdb->get_results("SELECT ID FROM $table_timesheet WHERE ( assignee = '$current_timesheet_author_id' OR created_by = '$current_timesheet_author_id' OR task_pm = '$current_user_id' ) AND status <> 'Complete' AND assigned_day = '$day'", ARRAY_A);
				}elseif(($current_timesheet_author_id != $current_user_id) && (check_current_user_role( 'project_manager' ))){
					$timesheet_data = $wpdb->get_results("SELECT ID FROM $table_timesheet WHERE ( assignee = '$current_timesheet_author_id' ) AND status <> 'Complete' AND assigned_day = '$day'", ARRAY_A);
				}else{
					$timesheet_data = $wpdb->get_results("SELECT ID FROM $table_timesheet WHERE ( assignee = '$current_timesheet_author_id' ) AND status <> 'Complete' AND assigned_day = '$day'", ARRAY_A);
				}

				foreach($timesheet_data as $timesheet){
					$timesheet_id = $timesheet['ID'];
					$set_row_id = '_'.$row_day.'_'.$row_timesheet;
					create_timesheet_row_html($set_row_id, $day, $select_emp_id, $timesheet_id, true);
					$row_timesheet += 1;
				}

			?>


			<?php
			if( !get_post_meta( $post->ID, 'unassigned_task', true ) ){
				for( $i = 1; $i <= 8; $i++){
					$set_row_id = '_'.$row_day.'_'.$row_timesheet;
					$row_timesheet += 1;
					create_timesheet_row_html($set_row_id, $day, $select_emp_id);
				}

			/*$set_row_id = '_'.$row_day.'_'.$row_timesheet;
			$row_timesheet += 1;
			create_timesheet_row_html($set_row_id, $day, $select_emp_id, $db_row);

			$set_row_id = '_'.$row_day.'_'.$row_timesheet;
			$row_timesheet += 1;
			create_timesheet_row_html($set_row_id, $day, $select_emp_id);*/
			}
			?>


		</table>
		<a href="" class="button button-primary">Add Row +</a> <br />
		<small>Note: Please save all rows first before adding new row. It will reload this page and see 3 blank rows</small>

	<?php
}



/**
 * Prints section fro Thursday
 *
 * @param WP_Post $post The object for the current post/page.
 */
function stm_timesheet_thursday_meta_box_callback( $post ) {
	global $post;
	// Add a nonce field so we can check for it later.
	wp_nonce_field( 'stm_timesheet_thursday_save_meta_box_data', 'stm_timesheet_thursday_meta_box_nonce' );

	?>
	<style type="text/css">
	.form-table th{ text-align:left !important; }
	</style>
	<?php
		global $wpdb;
		$row_day = 4;
		$row_timesheet = 1;
		$current_user_id = get_current_user_id();
		$current_timesheet_author_id = get_post_meta( $post->ID, '_stm_employee_id', true );
		$allow_like_team = false;
		if(get_user_meta( $current_user_id, '_stm_assign_own_tasks', true )){
			$allow_like_team = TRUE;
		}
		$day = 'Thursday';
		$select_emp_id = '';
	?>
		<table class="form-table cmb_metabox">
			<tr>
				<th>Client Name</th>
				<th>My Task</th>
				<th>Hours</th>
				<th>Assigned to</th>
				<?php if($allow_like_team){ ?>
					<th>PM</th>
				<?php }else{ ?>
					<th style="display:none;">PM</th>
				<?php } ?>
				<th>Due Date</th>
				<th style="width:190px;"></th>
			</tr>
			<?php
				$table_timesheet = $wpdb->base_prefix . "stm_timesheet";
				if(get_post_meta( $post->ID, 'unassigned_task', true )){
					$timesheet_data = $wpdb->get_results("SELECT ID FROM $table_timesheet WHERE (assignee = 0 || assignee = '') AND assigned_day = '$day'", ARRAY_A);
				}elseif(($current_timesheet_author_id == $current_user_id) && (check_current_user_role( 'project_manager' ))){
					$timesheet_data = $wpdb->get_results("SELECT ID FROM $table_timesheet WHERE ( assignee = '$current_timesheet_author_id' OR created_by = '$current_timesheet_author_id' OR task_pm = '$current_user_id' ) AND status <> 'Complete' AND assigned_day = '$day'", ARRAY_A);
				}elseif(($current_timesheet_author_id != $current_user_id) && (check_current_user_role( 'project_manager' ))){
					$timesheet_data = $wpdb->get_results("SELECT ID FROM $table_timesheet WHERE ( assignee = '$current_timesheet_author_id' ) AND status <> 'Complete' AND assigned_day = '$day'", ARRAY_A);
				}else{
					$timesheet_data = $wpdb->get_results("SELECT ID FROM $table_timesheet WHERE ( assignee = '$current_timesheet_author_id' ) AND status <> 'Complete' AND assigned_day = '$day'", ARRAY_A);
				}

				foreach($timesheet_data as $timesheet){
					$timesheet_id = $timesheet['ID'];
					$set_row_id = '_'.$row_day.'_'.$row_timesheet;
					create_timesheet_row_html($set_row_id, $day, $select_emp_id, $timesheet_id, true);
					$row_timesheet += 1;
				}

			?>


			<?php
			if( !get_post_meta( $post->ID, 'unassigned_task', true ) ){
				for( $i = 1; $i <= 5; $i++){
					$set_row_id = '_'.$row_day.'_'.$row_timesheet;
					$row_timesheet += 1;
					create_timesheet_row_html($set_row_id, $day, $select_emp_id);
				}

				/*$set_row_id = '_'.$row_day.'_'.$row_timesheet;
				$row_timesheet += 1;
				create_timesheet_row_html($set_row_id, $day, $select_emp_id, $db_row);

				$set_row_id = '_'.$row_day.'_'.$row_timesheet;
				$row_timesheet += 1;
				create_timesheet_row_html($set_row_id, $day, $select_emp_id);*/
			}
			?>


		</table>
		<a href="" class="button button-primary">Add Row +</a> <br />
		<small>Note: Please save all rows first before adding new row. It will reload this page and see 3 blank rows</small>

	<?php
}



/**
 * Prints section fro Friday
 *
 * @param WP_Post $post The object for the current post/page.
 */
function stm_timesheet_friday_meta_box_callback( $post ) {
	global $post;
	// Add a nonce field so we can check for it later.
	wp_nonce_field( 'stm_timesheet_friday_save_meta_box_data', 'stm_timesheet_friday_meta_box_nonce' );

	?>
	<style type="text/css">
	.form-table th{ text-align:left !important; }
	</style>
	<?php
		global $wpdb;
		$row_day = 5;
		$row_timesheet = 1;
		$current_user_id = get_current_user_id();
		$current_timesheet_author_id = get_post_meta( $post->ID, '_stm_employee_id', true );
		$allow_like_team = false;
		if(get_user_meta( $current_user_id, '_stm_assign_own_tasks', true )){
			$allow_like_team = TRUE;
		}
		$day = 'Friday';
		$select_emp_id = '';
	?>
		<table class="form-table cmb_metabox">
			<tr>
				<th>Client Name</th>
				<th>My Task</th>
				<th>Hours</th>
				<th>Assigned to</th>
				<?php if($allow_like_team){ ?>
					<th>PM</th>
				<?php }else{ ?>
					<th style="display:none;">PM</th>
				<?php } ?>
				<th>Due Date</th>
				<th style="width:190px;"></th>
			</tr>
			<?php
				$table_timesheet = $wpdb->base_prefix . "stm_timesheet";
				if(get_post_meta( $post->ID, 'unassigned_task', true )){
					$timesheet_data = $wpdb->get_results("SELECT ID FROM $table_timesheet WHERE (assignee = 0 || assignee = '') AND assigned_day = '$day'", ARRAY_A);
				}elseif(($current_timesheet_author_id == $current_user_id) && (check_current_user_role( 'project_manager' ))){
					$timesheet_data = $wpdb->get_results("SELECT ID FROM $table_timesheet WHERE ( assignee = '$current_timesheet_author_id' OR created_by = '$current_timesheet_author_id' OR task_pm = '$current_user_id' ) AND status <> 'Complete' AND assigned_day = '$day'", ARRAY_A);
				}elseif(($current_timesheet_author_id != $current_user_id) && (check_current_user_role( 'project_manager' ))){
					$timesheet_data = $wpdb->get_results("SELECT ID FROM $table_timesheet WHERE ( assignee = '$current_timesheet_author_id' ) AND status <> 'Complete' AND assigned_day = '$day'", ARRAY_A);
				}else{
					$timesheet_data = $wpdb->get_results("SELECT ID FROM $table_timesheet WHERE ( assignee = '$current_timesheet_author_id' ) AND status <> 'Complete' AND assigned_day = '$day'", ARRAY_A);
				}


				foreach($timesheet_data as $timesheet){
					$timesheet_id = $timesheet['ID'];
					$set_row_id = '_'.$row_day.'_'.$row_timesheet;
					create_timesheet_row_html($set_row_id, $day, $select_emp_id, $timesheet_id, true);
					$row_timesheet += 1;
				}

			?>


			<?php
			if( !get_post_meta( $post->ID, 'unassigned_task', true ) ){
				for( $i = 1; $i <= 5; $i++){
					$set_row_id = '_'.$row_day.'_'.$row_timesheet;
					$row_timesheet += 1;
					create_timesheet_row_html($set_row_id, $day, $select_emp_id);
				}

				/*$set_row_id = '_'.$row_day.'_'.$row_timesheet;
				$row_timesheet += 1;
				create_timesheet_row_html($set_row_id, $day, $select_emp_id, $db_row);

				$set_row_id = '_'.$row_day.'_'.$row_timesheet;
				$row_timesheet += 1;
				create_timesheet_row_html($set_row_id, $day, $select_emp_id);*/
			}
			?>


		</table>
		<a href="" class="button button-primary">Add Row +</a> <br />
		<small>Note: Please save all rows first before adding new row. It will reload this page and see 3 blank rows</small>

<script type="text/javascript">
    jQuery(document).ready(function($) {
			jQuery(".update_timesheet_row").on('click', function(e){
				var row_id = jQuery(this).attr("id");
				var dataContainer = {
					client_name: jQuery('#cname_'+ row_id ).val(),
					task_name: jQuery('#task_'+ row_id ).val(),
					hours: jQuery('#hours_'+ row_id ).val(),
					due: jQuery('#due_'+ row_id ).val(),
					assignee: jQuery('#assignee_'+ row_id ).val(),
					assign_by_team: jQuery('#assigned_by_team_'+ row_id ).val(),
					db_row: jQuery('#db_'+ row_id ).val(),
					task_day: jQuery('#task_day_'+ row_id ).val(),
					timesheet_user_id: jQuery('#timesheet_user_id_'+ row_id ).val(),
					action: 'save-timesheet-data-item'
				};
				var timesheet_id = jQuery('#db_'+ row_id ).val();

				jQuery.ajax({
					action: "save-timesheet-data-item",
					type: "POST",
					dataType: "json",
					url: ajaxurl,
					data: dataContainer,
					beforeSubmit: function() {
						//jQuery('#submit_email_this_record').val('Sending...');
					},
					success: function(data){
						//alert(data.msg);
						//alert(row_id);
						jQuery('#save_message_'+ row_id ).html(data.msg);
						if( timesheet_id == '' ) {
							jQuery('#db_'+ row_id ).val( data.timesheet_id );
							jQuery('#'+ row_id ).after( ' &nbsp;<a href="javascript:void(0)" class="primary button delete_timesheet_row" data-timesheet_id="'+data.timesheet_id+'">Delete</a>' );
							jQuery('#before-tr-'+ row_id ).data('data-timesheet_id', data.timesheet_id );
						}
						//jQuery('#save_message_'+ row_id ).fadeIn().delay(1000).fadeOut();
					}
				});


        });

		jQuery(document).on('click','.delete_timesheet_row', function(){
			if( !confirm('Are you sure?') ) {
				return false;
			}
			var timesheet_id = jQuery(this).data("timesheet_id");
			var dataContainer = {
				timesheet_id: timesheet_id,
				action: 'delete-timesheet-data-item'
			};
			jQuery.ajax({
				action: "delete-timesheet-data-item",
				type: "POST",
				dataType: "json",
				url: ajaxurl,
				data: dataContainer,
				success: function(data){
					jQuery('tr[data-timesheet_id="'+timesheet_id+'"]').remove();
				}
			});
		});

		/*jQuery( ".due_date" ).change(function() {

		  var due_day = jQuery(this).val();
		  var row_id_due = jQuery(this).attr("id");
		  var row_id = row_id_due.substring(4);
		  var assignee_id = jQuery('#assignee_'+ row_id ).val();

		  //alert(assignee_value_with_hour);

			var availableDataContainer = {
				due_day: jQuery(this).val(),
				assignee: jQuery('#assignee_'+ row_id ).val(),
				section_day: jQuery('#section_'+ row_id ).val(),
				action: 'get-assignee-available-hours'
			};


			//alert(availableDataContainer);

			jQuery.ajax({
				action: "get-assignee-available-hours",
				type: "POST",
				dataType: "json",
				url: ajaxurl,
				data: availableDataContainer,
				beforeSubmit: function() {
					//jQuery('#submit_email_this_record').val('Sending...');
					//alert('hello');
				},
				success: function(data){
					//alert(data.msg);
					//alert(row_id);
					jQuery('#new_hour_assignee_'+ row_id ).html(data.msg);
					//jQuery('#save_message_'+ row_id ).fadeIn().delay(3000).fadeOut();
				}
			});


		});	*/



			jQuery("#clear_timesheet").on('click', function(e){

				var status = confirm("Are you sure that you want to reset your timesheet?");
				if(status == false){
					return false;
					e.preventDefault();
				}

				var clearDataContainer = {
					request_employee_id: jQuery('#request_employee_id').val(),
					request_employee_timesheet_id: jQuery('#request_employee_timesheet_id').val(),
					unassigned_timesheet_status: jQuery('#unassigned_timesheet_status').val(),
					action: 'clear-timesheet-data-item'
				};
				//alert(dataContainer);

				jQuery.ajax({
					action: "clear-timesheet-data-item",
					type: "POST",
					dataType: "json",
					url: ajaxurl,
					data: clearDataContainer,
					beforeSubmit: function() {
						//jQuery('#submit_email_this_record').val('Sending...');
					},
					success: function(data){
						//alert(data.msg);
						jQuery('#clear_message').html(data.msg);
						jQuery('#clear_message').fadeIn().delay(1000).fadeOut();
						location.reload();
					}
				});


        });


		jQuery(".update_status").on('click', function(e){
			var task_id = jQuery(this).data("task_id");
			var status = jQuery(this).data("status");
			var row_id = jQuery(this).data("row_id");
			var dataContainer = {
				task_id: jQuery(this).data("task_id"),
				status: jQuery(this).data("status"),
				action: 'update-timesheet-data-status'
			};
			//alert(dataContainer);

			jQuery.ajax({
				action: "update-timesheet-data-status",
				type: "POST",
				dataType: "json",
				url: ajaxurl,
				data: dataContainer,
				beforeSubmit: function() {
					//jQuery('#submit_email_this_record').val('Sending...');
				},
				success: function(data){
					//alert(data.msg);
					//alert(row_id);
					jQuery('#save_message_'+ row_id ).html(data.msg);
					//jQuery('#save_message_'+ row_id ).fadeIn().delay(1000).fadeOut();
				}
			});


		});




    });
</script>

	<?php
}
