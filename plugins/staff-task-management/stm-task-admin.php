<?php
add_action('admin_menu', 'stm_task_admin_page');
function stm_task_admin_page() {
	add_submenu_page( 'edit.php?post_type=timesheet', 'Tasks', 'Tasks', 'manage_options', 'stm-task-admin', 'stm_task_admin_plug_page' );
}

function stm_task_admin_plug_page(){
	echo "<div class='wrap'>";
		echo "<h2>Tasks</h2>";
		
	// how many rows to show per page
	$rowsPerPage = 25;
	
	// by default we show first page
	$pageNum = 1;
	
	// if $_GET['page'] defined, use it as page number
	if(isset($_GET['p'])){
		$pageNum = $_GET['p'];
	}
	
	// counting the offset
	$offset = ($pageNum - 1) * $rowsPerPage;
		
	global $wpdb;	
	$table_timesheet = $wpdb->base_prefix . "stm_timesheet";	
	
	//$timesheet_data = $wpdb->get_results("SELECT * FROM $table_timesheet WHERE status <> 'Archive'", ARRAY_A);
	
	$sql_1 = "SELECT * FROM $table_timesheet WHERE status <> 'Archive' ORDER BY ID LIMIT $offset, $rowsPerPage";
	
		
	$timesheet_data = $wpdb->get_results($sql_1,ARRAY_A);	
	
	if ( ! empty( $timesheet_data ) ) {		
		echo "<div id='save_message_data' style='color:#7ad03a;'></div>";
		echo '<table width="100%" cellpadding="3" cellspacing="3" class="widefat" style="width: 100%;">';
		echo '<thead>';
		echo '<tr>';
		echo '<th scope="col">';
		echo 'Client';
		echo '</th>';	
		echo '<th scope="col">';
		echo 'Task';
		echo '</th>';
		echo '<th scope="col" style="text-align: left;">';
		echo 'Status';
		echo '</th>';
		echo '</tr>';
		echo '</thead>';		

			
	
		foreach($timesheet_data as $timesheet){		
			$task_id = $timesheet['ID'];
			$client_name = $timesheet['client_name'];
			$my_task = $timesheet['my_task'];
			$assignee = $timesheet['assignee'];
			$status = $timesheet['status'];			
			$employee_info = get_userdata($assignee);
			$assignee_name = $employee_info->first_name;
			

			
			echo "<tr>";
				echo "<td>";
					echo $client_name;
				echo "</td>";
					
				echo "<td>";
					echo $my_task;
				echo "</td>";
			
				echo "<td>";
					echo $status;
					echo "<br /><div style='font-size:12px;'>Mark as ";
					echo "<a href='javascript:void(0)' class='update_status' data-status='Complete' data-task_id=".$task_id.">Complete</a> | ";
					echo "<a href='javascript:void(0)' class='update_status' data-status='Pending' data-task_id=".$task_id.">Pending</a> | ";
					echo "<a href='javascript:void(0)' class='update_status' data-status='Archive' data-task_id=".$task_id.">Archive</a></div>";
				echo "</td>";				
			
			
			echo "</tr>";
				
		}		
		
		echo '</table>';
	
	?>
	
	
<div class="tablenav">
  <div class="tablenav-pages">
    <?php
       
	$pages = $wpdb->get_results("SELECT ID FROM $table_timesheet  WHERE status <> 'Archive' ORDER BY ID DESC");


    $numrows = count($pages);

    // how many pages we have when using paging?
    $maxPage = ceil($numrows/$rowsPerPage);

    // print the link to access each page
    $path = 'edit.php?post_type=timesheet&page=stm-task-admin';
    $nav  = '';

    for($page = 1; $page <= $maxPage; $page++){
      if ($page == $pageNum){
        $nav .= ' <span class="page-numbers current">' . $page . '</span>'; // no need to create a link to current page
      }else{
        $nav .= ' <a href="' . $path . '&tags=' . $tag . '&p=' . $page . '" class="page-numbers">' . $page . '</a>';
      }
    }

    if ($pageNum > 1){
      $page  = $pageNum - 1;

      $prev  ='<a href="' . $path . '&tags=' . $tag . '&p=' . $page . '" class="prev page-numbers">Previous</a>';
    }else{
      $prev  = '&nbsp;'; // we're on page one, don't print previous link
      $first = '&nbsp;'; // nor the first page link
    }

    if ($pageNum < $maxPage){
      $page = $pageNum + 1;
      $next = ' <a href="' . $path . '&tags=' . $tag . '&p=' . $page . '" class="next page-numbers">Next</a>';
    }else{
      $next = '&nbsp;'; // we're on the last page, don't print next link
      $last = '&nbsp;'; // nor the last page link
    }

    // print the navigation link
    echo $prev . $nav . $next;

  ?>
  </div>
  <br class="clear"/>
</div><!--tablenav-->	

<?php } ?>	
	
	
	
<script type="text/javascript">    
    jQuery(document).ready(function($) {
			jQuery(".update_status").on('click', function(e){
				var task_id = jQuery(this).data("task_id");
				var status = jQuery(this).data("status");					
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
						jQuery('#save_message_data').html(data.msg);
						jQuery('#save_message_data').fadeIn().delay(1000).fadeOut();
					}
				});

        
        });		
		
    });  
</script>			
	
	
	<?php
		
		
	echo '</div>';
}