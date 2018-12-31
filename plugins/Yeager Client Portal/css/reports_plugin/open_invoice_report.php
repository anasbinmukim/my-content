<div class="wrap">
				<h2 class="mkbis2">Open Invoice Report</h2>
				<?php
				echo $building=$_POST['_accounting_building_id'];
				echo "*****************";
			$sites = wp_get_sites();
			$all_blogs_id=array();
			$removed_ids=array(1,20,19,6);
			foreach ($sites as $key => $current_blog) {

				if(!in_array($current_blog['blog_id'], $removed_ids))
				{
				array_push($all_blogs_id, $current_blog['blog_id']);
				}
			}

			if($building=="all")
			{

				echo "22222222222*****************";

				// echo "functionality for all";
			$all_data_total=array();
			foreach ($all_blogs_id as $crid) {

			switch_to_blog($crid);
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
				'post_type' => 'sa_invoice',
				'post_status' => 'partial,publish',
				// 'post_status' => array('publish,partial'),
					// 'date_query' => array(
					// 	array(
					// 		'column' => 'post_modified',
					// 		'after'     => array(
					// 			'year'  => $str_yr,
					// 			'month' => $str_mn,
					// 			'day'   => $str_dy,
					// 		),
					// 		'before'    => array(
					// 			'year'  => $end_yr,
					// 			'month' => $end_mn,
					// 			'day'   => $end_dy,
					// 		),
					// 		'inclusive' => true,
					// 	),
					// ),
				'posts_per_page' => -1,
				);


				$results = get_posts($args);

			$all_data=array();

			$full_total=0;
			foreach ($results as $key => $result) {
	if($result->post_status=="partial" || $result->post_status=="publish")
				{
			//$paymentdatefull=explode(" ", $result->post_modified);		
			//$paymentdate=$paymentdatefull[0];	
			$invoice_id=$result->ID;
			$paymentdate = get_post_meta( $invoice_id, '_due_date', true );	
			$paymentdate = gmdate("m/d/Y", $paymentdate);
			$client_name=get_the_title(get_post_meta( $invoice_id, '_client_id', true ));
			$total=get_post_meta( $invoice_id, '_total', true );

			$client_id=get_post_meta( $invoice_id, '_client_id', true);
			$user_id=get_post_meta( $client_id, '_associated_users', true);
			$yl_ms_args = array(
						        'post_type'   => 'lease',
						        'post_status'   => 'publish',
						        'numberposts'  => -1,
						        'meta_query' => array(
						          array(
						            'key' => '_yl_lease_user',
						            'value'   => $user_id,
						            'compare' => '='
						          )
						        )
						      );
			 $suite_number="";
		      $posts = get_posts($yl_ms_args);
		      foreach ($posts as $key2 => $value) {
		      	# code...
		      	$lid=$value->ID;
		      	if(get_post_meta($lid, '_yl_suite_number',true)=='-1')
		      	{
		      		$sutname="Y-Memberships";
		      	}
		      	else{
		      		$sutname=get_post_meta($lid, '_yl_suite_number',true);

		      	}
		      	
		      	$suite_number.="(".$sutname.")";

		      }
			$all_data[$invoice_id]['client_name']=$client_name;
			$all_data[$invoice_id]['suite_number']=$suite_number;
			$all_data[$invoice_id]['num']=$invoice_id;
			$all_data[$invoice_id]['total']='$' . number_format($total);
			$all_data[$invoice_id]['date']=$paymentdate;

			$all_data_total[$invoice_id]['client_name']=$client_name;
			$all_data_total[$invoice_id]['suite_number']=$suite_number;
			$all_data_total[$invoice_id]['num']=$invoice_id;
			$all_data_total[$invoice_id]['total']='$' . number_format($total);
			$all_data_total[$invoice_id]['date']=$paymentdate;

			
			$full_total=(float) ($full_total+$total);
				# code...
			}

			}
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
					<th></th>
					<th>Type</th>
					<th>Date</th>
					<th>Num</th>
					<th>Class</th>
					<th>Open Balance</th>
					<!-- <th></th> -->

				</tr>
			</thead>
			<tbody>
			<?php
			foreach ($all_data as $key => $value) {
				?>
			<tr>
			<td><?php echo $value['client_name'] ?></td>
			<td></td>
			<td><?php echo $value['date'] ?></td>
			<td><?php echo $key ?></td>
			<td><?php echo $value['suite_number'] ?></td>
			<td><?php $value['total']; ?></td>
			
			</tr>
				<?php
			}
			?>
			<tr>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td>Total</td>
			<td><?php echo $full_total ?></td>
			
			</tr>
			</tbody>
			</table>
			</div>
			</div>
			<?php


			restore_current_blog();
		



}


reporst_csv("Open invoice.csv",$all_data,'open_invoice');
}
else{
				// echo "3333333333333333333*****************";

// echo $building;
			$sites = wp_get_sites();

// }
			switch_to_blog($building);


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
				'post_type' => 'sa_invoice',
				'post_status' => 'any',
				// 'post_status' => array('partial'),
				'posts_per_page' => -1,
				);

				// $args = array(
				// 	'date_query' => array(
				// 		array(
				// 			'after'     => 'January 1st, 2013',
				// 			'before'    => array(
				// 				'year'  => 2013,
				// 				'month' => 2,
				// 				'day'   => 28,
				// 			),
				// 			'inclusive' => true,
				// 		),
				// 	),
				// 	'posts_per_page' => -1,
				// );

				$results = get_posts($args);
				// print_r($results);

			$all_data=array();

			$full_total=0;
			foreach ($results as $key => $result) {
				if($result->post_status=="partial" || $result->post_status=="publish")
				{
				// echo "<pre>";
				// print_r($result);
				// echo "</pre>";
				// exit();
			//$paymentdatefull=explode(" ", $result->post_modified);		
			//$paymentdate=$paymentdatefull[0];	
			 $invoice_id=$result->ID;
			 $paymentdate = get_post_meta( $invoice_id, '_due_date', true );
			 $paymentdate = gmdate("m/d/Y", $paymentdate);
			 $client_name=get_the_title(get_post_meta( $invoice_id, '_client_id', true ));
				$total=get_post_meta( $invoice_id, '_total', true );

			 $client_id=get_post_meta( $invoice_id, '_client_id', true);
				$user_id=get_post_meta( $client_id, '_associated_users', true);
			 $yl_ms_args = array(
						        'post_type'   => 'lease',
						        'post_status'   => 'publish',
						        'numberposts'  => -1,
						        'meta_query' => array(
						          array(
						            'key' => '_yl_lease_user',
						            'value'   => $user_id,
						            'compare' => '='
						          )
						        )
						      );
			 $suite_number="";
		      $posts = get_posts($yl_ms_args);
		      foreach ($posts as $key2 => $value) {
		      	# code...
		      	$lid=$value->ID;
		      	if(get_post_meta($lid, '_yl_suite_number',true)=='-1')
		      	{
		      		$sutname="Y-Memberships";
		      	}
		      	else{
		      		$sutname=get_post_meta($lid, '_yl_suite_number',true);

		      	}
		      	
		      	$suite_number.="(".$sutname.")";

		      }
		      // $invoice_num='"=HYPERLINK(""'.get_permalink($invoice_id).'"",)"';
		      $invoice_num='=HYPERLINK("'.get_permalink($invoice_id).'", "'.$invoice_id.'")';


			 // $leasd_id= get_post_meta( $invoice_id, '_yl_lease_id', true );
			 // $suite_number=get_post_meta($leasd_id, '_yl_suite_number', true );
			$all_data[$invoice_id]['client_name']=$client_name;
			$all_data[$invoice_id]['suite_number']=$suite_number;
			$all_data[$invoice_id]['num']=$invoice_num;
			$all_data[$invoice_id]['total']='$' . number_format($total);
			$all_data[$invoice_id]['date']=$paymentdate;
			$full_total=(float) ($full_total+$total);
				# code...
			}
		}
			// $all_week_data2=array();
// print_r($all_data);
			// print_r($final_data_table);
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
					<th></th>
					<th>Type</th>
					<th>Date</th>
					<th>Num</th>
					<th>Class</th>
					<th>Open Balance</th>
					<!-- <th></th> -->

				</tr>
			</thead>
			<tbody>
			<?php
			foreach ($all_data as $key => $value) {
				?>
			<tr>
			<td><?php echo $value['client_name'] ?></td>
			<td></td>
			<td><?php echo $value['date'] ?></td>
			<td><?php echo $key ?></td>
			<td><?php echo $value['suite_number'] ?></td>
			<td><?php $value['total']; ?></td>
			<!-- <td></td> -->
			
			</tr>
				<?php
				# code...
			}
			?>
			<tr>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td>Total</td>
			<td><?php echo $full_total ?></td>
			<!-- <td></td> -->
			
			</tr>
			</tbody>
			</table>
			</div>
			</div>
			<?php
			// echo $final_data_table['all_dates']['2016-04-26'];
			// echo "</pre>";
			// echo "<pre>";

														// 				global $wpdb;
														// 	$query = array(
														//     'post_type' => 'sa_invoice',
														//      'posts_per_page' => -1,
														//     'post_status' =>  array('complete'),
														//      // 'orderby' => 'meta_value',
														//  	'order' => 'ASC',
														//     'meta_query' => array(
														// 				// 'relation' => 'AND',
														// 		    	    array(
														//     	    			    'key' => '_due_date',
														// 				            'value' => '2016-04-28',
														// 			                'compare' => '<=',
														// 						    'type'    => 'Date'
														// 			  			 ),
														// 		    	  //   array(
														//     	    // 			    'key' => '_client_id',
														// 				     //        'value' => in_array( $client_id, '_client_id') ,
														// 			      //           // 'compare' => '=',
														// 						   //  // 'type'    => 'Date'
														// 			  			 // ),
														// 		    	  //   )
														// 					 );	   
														// );
														// $invoice_id=array();
														// $loopre = new WP_Query($query);
														// // echo "checkpoint1";

														// while ( $loopre->have_posts() ) : $loopre->the_post();
														// 	the_id();

														// endwhile;



			reporst_csv("Open invoice.csv",$all_data,'open_invoice');



			restore_current_blog();
		}