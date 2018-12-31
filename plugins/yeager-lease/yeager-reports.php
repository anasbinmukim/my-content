<?php

add_action('network_admin_menu', 'register_report_network_menu_page');
//add_action('admin_menu', 'register_report_network_menu_page');

function register_report_network_menu_page() {
	add_submenu_page('settings.php', 'Reports', 'Reports', 'manage_options', 'yeager-reports', 'yeager_reports_callback');
}

function yeager_reports_callback(){
	?>
    <link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
    <script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
	<script>
		jQuery(function() {
			jQuery( "#from_date" ).datepicker({ dateFormat: "yy-mm-dd" });
			jQuery( "#to_date" ).datepicker({ dateFormat: "yy-mm-dd" });
			
			jQuery("#reports_form").submit(function() {
				//var check = true;
			}); 
			
			jQuery( "#reports_form input[type=submit]" ).on( "click", function() {
				var blog_id = jQuery(this).attr('data-blog');
				var from_date = jQuery("#from_date").val();
				var to_date = jQuery("#to_date").val();
				jQuery( "#reports_form" ).append('<input type="hidden" name="blog_id" value="'+blog_id+'" />').submit();
				//jQuery("#reports_form").submit();
			});
			
			/*jQuery( "input[type=button]" ).on( "click", function() {
				//alert( jQuery(this).attr("data-blog") );
				var dataContainer = {
					blog_id: jQuery(this).attr('data-blog'),
					from_date: jQuery("#from_date").val(),
					to_date: jQuery("#to_date").val(),
					action: "run-report"
				};
				jQuery.ajax({
					action: "run-report",
					type: "POST",
					dataType: "json",
					url: ajaxurl,			
					data: dataContainer,
					beforeSubmit: function() {
						//jQuery('#MoveinDate').val('Sending...');
					},
					success: function(data) {			
						alert(data.blog_id);
					}
				});
			});*/
		});
    </script>
  	<div class="wrap">
		<h2>Reports</h2>
		
		<form action="" method="post" id="reports_form">
        	<p>
                <label for="from_date">From</label>
                <input type="text" name="from_date" id="from_date" />
    
                <label for="to_date">To</label>
                <input type="text" name="to_date" id="to_date" />
                
                <!--<input type="hidden" name="blog_id" value="4" />-->
            </p>
            <!--<p>
                <input type="submit" name="report_date_submit" id="report_date_submit" value="Submit" class="button button-primary" />
            </p>-->
            <p>&nbsp;</p>
            <table border="1" cellpadding="10" cellspacing="0">
            <?php
				$sites = wp_get_sites();
				//print_r ($sites);
				foreach($sites as $site) {
					//echo $site['blog_id']. ' => '.$site['domain'];
					$domain_arr = explode(".", $site['domain']);
					echo '<tr>
							<td width="300">'.ucwords($domain_arr[0]).'</td>
							<td width="100"><input type="submit" name="run_report" value="Run Report" data-blog="'.$site['blog_id'].'" class="button button-primary" /></td>
						</tr>';
				}
			?>
            </table>
        </form>
	</div>
	<?php
}

function cleanData( &$str ) {
	$str = preg_replace("/\t/", "\\t", $str);
	$str = preg_replace("/\r?\n/", "\\n", $str);
	if(strstr($str, '"')) $str = '"' . str_replace('"', '""', $str) . '"';
}

add_action('admin_init', 'download_csv_file');
function download_csv_file() {
	if( isset($_POST['run_report']) ) {
		$blog_id = $_POST['blog_id'];
		$from_date = $_POST['from_date'];
		$to_date = $_POST['to_date'];
		
		global $wpdb;
		//$current_blog_id = get_current_blog_id();
		if ($blog_id > 1) {
			$posts_table = $wpdb->base_prefix . $blog_id . "_posts";
			$meta_table = $wpdb->base_prefix . $blog_id . "_postmeta";
		} else {
			$posts_table = $wpdb->base_prefix . "posts";
			$meta_table = $wpdb->base_prefix . "postmeta";
		}
	
		// output headers so that the file is downloaded rather than displayed
		/*header('Content-Type: application/csv');
		header('Content-Disposition: attachment; filename=lease-export.csv');*/
		header("Content-Disposition: attachment; filename=lease-export.xls");
		header("Content-Type: application/vnd.ms-excel");	

		/*$output = fopen('php://output', 'w');*/
		// output the column headings
		$columnArr = array();
		$columnArr[] = 'Tenant';
		$columnArr[] = 'Suite #';
		$columnArr[] = 'Rent';
		$columnArr[] = 'Total';
		/*fputcsv($output, $columnArr);*/
		echo implode("\t", $columnArr) . "\r\n";
	
		$results = $wpdb->get_results( $wpdb->prepare( "SELECT ID from $posts_table WHERE post_type='lease'" ), ARRAY_A );
		foreach($results as $result) {
			$contentArr = array();
			$post_id = $result['ID'];
			$lease_meta = $wpdb->get_results( $wpdb->prepare( "SELECT * from $meta_table WHERE post_id=$post_id" ), ARRAY_A );
			foreach($lease_meta as $lease) {
				if($lease['meta_key'] == '_yl_l_first_name') {
					$contentArr[] = $lease['meta_value'];
				} elseif($lease['meta_key'] == '_yl_suite_number') {
					$contentArr[] = $lease['meta_value'];
				} elseif($lease['meta_key'] == '_yl_monthly_rent') {
					$contentArr[] = $lease['meta_value'];
					$contentArr[] = $lease['meta_value'];
				}/* elseif($lease['meta_key'] == '_yl_l_first_name') {
					$contentArr[] = $lease['meta_value'];
				}*/
			}
			$name = $contentArr[count($contentArr)-1];
			array_pop($contentArr);
			array_unshift($contentArr, $name);
			
			/*fputcsv($output, $contentArr);*/
			array_walk($contentArr, 'cleanData');
			echo implode("\t", $contentArr) . "\r\n";
		}
		//$coupon_id = $result['ID'];
	
		/*$coupon_value = $wpdb->get_row( $wpdb->prepare( "SELECT meta_value from $meta_table WHERE meta_key='coupon_amount' AND post_id=$coupon_id" ), ARRAY_A );
		$coupon_amount = $coupon_value['meta_value'];*/
	
		//echo json_encode(array("msg" => $message, "blog_id" => $blog_id, 'success' => $success));
		exit();
	}
}

function yl_generate_report() {
	$blog_id = $_POST['blog_id'];
	$from_date = $_POST['from_date'];
	$to_date = $_POST['to_date'];
	
	global $wpdb;
	$current_blog_id = get_current_blog_id();
	if ($current_blog_id > 1) {
		$posts_table = $wpdb->base_prefix . $current_blog_id . "_posts";
		$meta_table = $wpdb->base_prefix . $current_blog_id . "_postmeta";
	} else {
		$posts_table = $wpdb->base_prefix . "posts";
		$meta_table = $wpdb->base_prefix . "postmeta";
	}

	// output headers so that the file is downloaded rather than displayed
	header('Content-Type: application/csv');
	header('Content-Disposition: attachment; filename=members-export.csv');

	$output = fopen('php://output', 'w');
	// output the column headings
	$columnArr = array();
	$columnArr[] = 'Tenant';
	$columnArr[] = 'Suite #';
	$columnArr[] = 'Rent';
	$columnArr[] = 'Total';
	fputcsv($output, $columnArr);

	$results = $wpdb->get_results( $wpdb->prepare( "SELECT ID from $posts_table WHERE post_type='lease'" ), ARRAY_A );
	$contentArr = array();
	foreach($results as $result) {
		$post_id = $result['ID'];
		$lease_meta = $wpdb->get_results( $wpdb->prepare( "SELECT * from $meta_table WHERE post_id=$post_id" ), ARRAY_A );
		$counter = 1;
		foreach($lease_meta as $lease) {
			if(($counter == 1) && ($lease['_yl_l_first_name'] != '')) {
				$contentArr[] = $lease['_yl_l_first_name'];
			} elseif(($counter == 2) && ($lease['_yl_suite_number'] != '')) {
				$contentArr[] = $lease['_yl_suite_number'];
			} elseif(($counter == 3) && ($lease['_yl_monthly_rent'] != '')) {
				$contentArr[] = $lease['_yl_monthly_rent'];
			} elseif(($counter == 4) && ($lease['_yl_monthly_rent'] != '')) {
				$contentArr[] = $lease['_yl_monthly_rent'];
			}
			$counter++;
		}
	}
	//$coupon_id = $result['ID'];

	/*$coupon_value = $wpdb->get_row( $wpdb->prepare( "SELECT meta_value from $meta_table WHERE meta_key='coupon_amount' AND post_id=$coupon_id" ), ARRAY_A );
	$coupon_amount = $coupon_value['meta_value'];*/

	fputcsv($output, $contentArr);

	echo json_encode(array("msg" => $message, "blog_id" => $blog_id, 'success' => $success));
	exit;
}
add_action('wp_ajax_run-report', 'yl_generate_report');
add_action('wp_ajax_nopriv_run-report', 'yl_generate_report');