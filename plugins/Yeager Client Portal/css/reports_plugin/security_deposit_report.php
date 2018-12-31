<?php
$building=$_POST['_accounting_building_id'];

				if($building=="all")
				{
			$sites = wp_get_sites();


			$all_blogs_id=array();
			$removed_ids=array(1,20,19,6);
			foreach ($sites as $key => $current_blog) {

				if(!in_array($current_blog['blog_id'], $removed_ids))
				{
				array_push($all_blogs_id, $current_blog['blog_id']);
				}
			}
				$csv_data_array=array();
			$i=0;

foreach ($all_blogs_id as  $crid) {
	# code...

		// echo "still in process";
						// $crid=$_POST['_accounting_building_id'];



			// $building=$_POST['_accounting_building_id'];

			
			// $sites = wp_get_sites();

			switch_to_blog($crid);

						$field_class_name="report_".$crid."_class";
						$field_bank_name="report_".$crid."_bank";
						$class=get_option($field_class_name);
						$bank=get_option($field_bank_name);
			global $wpdb;
			global $post;
			$start_account=$_POST['_accounting_start_date'];
			$start_acc=explode("-", $start_account);
			$str_yr=$start_acc[0];
			$str_mn=$start_acc[1];
			$str_dy=$start_acc[2];
			$end_account=$_POST['_accounting_end_date'];
			$end_acc=explode("-", $end_account);
			$end_yr=$end_acc[0];
			$end_mn=$end_acc[1];
			$end_dy=$end_acc[2];
			$args = array(
				'post_type' => 'lease',
				// 'post_status' => 'publish',
				    'meta_key' => '_yl_suite_number',
				    'orderby' => 'meta_value',
				    'order' =>'ASC',
					'date_query' => array(
						array(
							'column' => 'post_modified',
							'after'     => array(
								'year'  => $str_yr,
								'month' => $str_mn,
								'day'   => $str_dy,
							),
							'before'    => array(
								'year'  => $end_yr,
								'month' => $end_mn,
								'day'   => $end_dy,
							),
							'inclusive' => true,
						),
					),
				'posts_per_page' => -1,
				);

			$results = get_posts($args);
			$alldates=array();
			foreach ($results as $lease) {
				$lease_id=$lease->ID;
				$modified=explode(" ", $lease->post_modified);
				$date_sec=$modified[0];

				if(get_post_meta($lease_id, '_yl_suite_number',true)=='-1')
		      	{
		      		$sutname="Y-Memberships";
		      	}
		      	else{
		      		$sutname=get_post_meta($lease_id, '_yl_suite_number',true);
		      		
		      	}
		      	
		      	$suite_number="(".$sutname.")";
				// $suite_number=get_post_meta( $lease_id, '_yl_suite_number', true );
				// $alldates['suite_number']=
				$alldates[$suite_number]['customer']=get_post_meta( $lease_id, '_yl_l_first_name', true )." ".get_post_meta( $lease_id, '_yl_l_last_name', true );
				
				// if(array_key_exists($suite_number, $alldates))
				// {
				// 	array_push($alldates[$date_sec]['customer']['security'], )
				// 	$alldates[$date_sec]['customer']=(float) ($alldates[$date_sec]['security']+get_post_meta( $lease_id, '_yl_security_deposit', true ));
				// }
				// else{
					$alldates[$suite_number]['security']=(float) (get_post_meta( $lease_id, '_yl_security_deposit', true ));

				// }
				// print_r($lease);
				// echo $date_sec;
				// echo "<br>";
				// echo get_post_meta( $lease_id, '_yl_security_deposit', true );
				// echo "<br>";
				// exit();
				# code...
			}
			// $alldates[$date_sec];
			// print_r($alldates);
			?>
			<style type="text/css" media="screen">
					.mk_account_reports table{
							border-collapse: collapse;
							margin: 10px 0;
						}
					.mk_account_reports	th{
							font-weight: normal;
							border: 1px solid #D4D4D4;
							padding: 3px;
							text-align: left;
						}
					.mk_account_reports	td{
							border: 1px solid #D4D4D4;
							}
			</style>
			<div class="mk_account_reports">
				
			<table cellpadding="0" cellspacing="0" border="0" width="100%">
			<thead>
				<tr>
					<th>Customer</th>
					<th>Class</th>
					<th>Security Deposit</th>
			<!-- 		<th>Memo</th>
					<th>Name</th>
					<th>Billable</th>
					<th>Class</th> -->

				</tr>
			</thead> 
			<tbody>
			<?php
			// $csv_data_array=array();
			// $i=1;
			foreach ($alldates as $key => $value) {
				if($value!=0)
				{
				?>
			<tr>
			<td><?php echo $value['customer']; ?></td>
			<td><?php echo $key;?></td>
			<!-- <td></td> -->
			<!-- <td>Week of <?php echo $end_account; ?></td> -->
			<td> $<?php echo $value['security']; ?></td>
			<!-- <td> </td>
			<td> </td> -->
			
			</tr>
				<?php
			}
				# code...
			$csv_data_array[$i]['Customer']=$value['customer'];
			$csv_data_array[$i]['Class']=$class." ".$key;
			$csv_data_array[$i]['Security Deposit']='$' . number_format($value['security']);
			$i++;
			}
			?>
		<!-- 	<tr>
			<td>Accounts Receivable</td>
			<td><?php echo "$".$final_data_table['total']; ?></td>
			<td></td>
			<td> </td>
			<td> </td>
			<td> </td>
			<td> </td>
			
			</tr> -->
			</tbody>
			</table>
			</div>
			<?php






	









			restore_current_blog();

	

	
}
reporst_csv("Security deposit report.csv",$csv_data_array,"rental_report");



				}
				else{


		// echo "still in process";
				


			$building=$_POST['_accounting_building_id'];

			
			$sites = wp_get_sites();

			switch_to_blog($building);
		$crid=$_POST['_accounting_building_id'];
						$field_class_name="report_".$crid."_class";
						$field_bank_name="report_".$crid."_bank";
						$class=get_option($field_class_name);
						$bank=get_option($field_bank_name);

			global $wpdb;
			global $post;
			$start_account=$_POST['_accounting_start_date'];
			$start_acc=explode("-", $start_account);
			$str_yr=$start_acc[0];
			$str_mn=$start_acc[1];
			$str_dy=$start_acc[2];
			$end_account=$_POST['_accounting_end_date'];
			$end_acc=explode("-", $end_account);
			$end_yr=$end_acc[0];
			$end_mn=$end_acc[1];
			$end_dy=$end_acc[2];
			$args = array(
				'post_type' => 'lease',
				// 'post_status' => 'publish',
				    'meta_key' => '_yl_suite_number',
				    'orderby' => 'meta_value',
				    'order' =>'ASC',
					'date_query' => array(
						array(
							'column' => 'post_modified',
							'after'     => array(
								'year'  => $str_yr,
								'month' => $str_mn,
								'day'   => $str_dy,
							),
							'before'    => array(
								'year'  => $end_yr,
								'month' => $end_mn,
								'day'   => $end_dy,
							),
							'inclusive' => true,
						),
					),
				'posts_per_page' => -1,
				);

			$results = get_posts($args);
			$alldates=array();
			foreach ($results as $lease) {
				$lease_id=$lease->ID;
				$modified=explode(" ", $lease->post_modified);
				$date_sec=$modified[0];

				if(get_post_meta($lease_id, '_yl_suite_number',true)=='-1')
		      	{
		      		$sutname="Y-Memberships";
		      	}
		      	else{
		      		$sutname=get_post_meta($lease_id, '_yl_suite_number',true);
		      		
		      	}
		      	
		      	$suite_number="(".$sutname.")";
				// $suite_number=get_post_meta( $lease_id, '_yl_suite_number', true );
				// $alldates['suite_number']=
				$alldates[$suite_number]['customer']=get_post_meta( $lease_id, '_yl_l_first_name', true )." ".get_post_meta( $lease_id, '_yl_l_last_name', true );
				
				// if(array_key_exists($suite_number, $alldates))
				// {
				// 	array_push($alldates[$date_sec]['customer']['security'], )
				// 	$alldates[$date_sec]['customer']=(float) ($alldates[$date_sec]['security']+get_post_meta( $lease_id, '_yl_security_deposit', true ));
				// }
				// else{
					$alldates[$suite_number]['security']=(float) (get_post_meta( $lease_id, '_yl_security_deposit', true ));

				// }
				// print_r($lease);
				// echo $date_sec;
				// echo "<br>";
				// echo get_post_meta( $lease_id, '_yl_security_deposit', true );
				// echo "<br>";
				// exit();
				# code...
			}
			// $alldates[$date_sec];
			// print_r($alldates);
			?>
			<style type="text/css" media="screen">
					.mk_account_reports table{
							border-collapse: collapse;
							margin: 10px 0;
						}
					.mk_account_reports	th{
							font-weight: normal;
							border: 1px solid #D4D4D4;
							padding: 3px;
							text-align: left;
						}
					.mk_account_reports	td{
							border: 1px solid #D4D4D4;
							}
			</style>
			<div class="mk_account_reports">
				
			<table cellpadding="0" cellspacing="0" border="0" width="100%">
			<thead>
				<tr>
					<th>Customer</th>
					<th>Class</th>
					<th>Security Deposit</th>
			<!-- 		<th>Memo</th>
					<th>Name</th>
					<th>Billable</th>
					<th>Class</th> -->

				</tr>
			</thead> 
			<tbody>
			<?php
			$csv_data_array=array();
			$i=1;
			foreach ($alldates as $key => $value) {
				if($value!=0)
				{
				?>
			<tr>
			<td><?php echo $value['customer']; ?></td>
			<td><?php echo $key;?></td>
			<!-- <td></td> -->
			<!-- <td>Week of <?php echo $end_account; ?></td> -->
			<td> $<?php echo $value['security']; ?></td>
			<!-- <td> </td>
			<td> </td> -->
			
			</tr>
				<?php
			}
				# code...
			$csv_data_array[$i]['Customer']=$value['customer'];
			$csv_data_array[$i]['Class']=$class." ".$key;
			$csv_data_array[$i]['Security Deposit']='$' . number_format($value['security']);
			$i++;
			}
			?>
		<!-- 	<tr>
			<td>Accounts Receivable</td>
			<td><?php echo "$".$final_data_table['total']; ?></td>
			<td></td>
			<td> </td>
			<td> </td>
			<td> </td>
			<td> </td>
			
			</tr> -->
			</tbody>
			</table>
			</div>
			<?php






	

reporst_csv("Security deposit report.csv",$csv_data_array,"rental_report");








			restore_current_blog();

	





				}