<?php


		// echo "still in process";



			$building=$_POST['_accounting_building_id'];

			
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

			if($building=="all")
			{
				$i=0;

				?>
<form action="" method="post" >
	
		<input type="hidden" name="_accounting_report_id" value="<?php echo $_REQUEST['_accounting_report_id']; ?>">	
		<input type="hidden" name="_accounting_building_id" value="<?php echo $_REQUEST['_accounting_building_id']; ?>">	
		<input type="hidden" name="_accounting_start_date" value="<?php echo $_REQUEST['_accounting_start_date']; ?>">	
		<input type="hidden" name="_accounting_end_date" value="<?php echo $_REQUEST['_accounting_end_date']; ?>">	
		<input type="hidden" name="accountingmk_submit" value="">	

	<input type="submit" name="Run" value="Download"/>
</form>
					<style type="text/css" media="screen">
					.mk_account_reports table{
					border-collapse: collapse;
					margin: 10px 0;
					}
					.mk_account_reports	th{
					font-weight: normal;
					border: 1px solid #D4D4D4;
					padding: 3px;
					text-align: center;
					}
					.mk_account_reports	td{
					border: 1px solid #D4D4D4;
					}
					</style>
					<div class="wrap">
					<h2 class="mkbis2">Rent Roll Reports for All From <?php echo $_POST['_accounting_start_date'];  ?> To <?php echo $_POST['_accounting_end_date']; ?> </h2>
					<div class="mk_account_reports">

					<table cellpadding="0" cellspacing="0" border="0" width="100%">
					<thead>
					<tr>
					<th>  Suite Number     </th>
					<th>Tennant Name</th>
					<th>Rent</th>
					<th>Aux Charges</th>

					</tr>
					</thead> 
					<tbody>
				<?php
			$the_total=0;

				foreach ($all_blogs_id as  $crid) {




			// echo "New Rent Report";
			// $building=$_POST['_accounting_building_id'];

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
							$start_Account2=strtotime($start_account);
							$end_Account2=strtotime($end_account);
							$args = array(
						    'post_type' => 'suites',
						    'post_status' => 'publish',
						    'posts_per_page' => -1,
						    'order' => 'ASC',
						    'orderby'=>'title',
						);
			$results = get_posts($args);
			/*echo "<pre>";
			print_r($results);
			echo "<pre>";*/
			$all_invoice_id_checker=array();
			$all_invoice_id_checker22=array();
			$mkk_total22=0;
			$all_data=array();
			$the_real_total=0;
			$multichecker_real_total=0;
			$multichecker_real_total2=0;
			$temp_total_multi=0;
$mk_cc2=array();
$mk_cc=array();
			$full_total=0;
			$k=0;
			foreach ($results as $key => $result) {
			$paymentdatefull=explode(" ", $result->post_modified);		
			$paymentdate=$paymentdatefull[0];	
			$suite_id=$result->ID;
			$suite_number=get_the_title( $suite_id );
							 $yl_ms_args = array(
										        'post_type'   => 'lease',
										        'post_status'   => 'publish',
										        'numberposts'  => -1,
										        'order' => 'ASC',
										        'meta_query' => array(
										          array(
										            'key' => '_yl_suite_number',
										            'value'   => $suite_number,
										            'compare' => '='
										          )
										        )
										      );

							$lease_results = get_posts($yl_ms_args);
							/*echo "<pre>";
							print_r($lease_results);
							echo "</pre>";*/
							$leases_ids=array();
								$invoice_ids=array();

							foreach ($lease_results as  $lease_result) {
								$in_ms_args = array(
										        'post_type'   => 'sa_invoice',
										        'post_status'   => 'any',
										        'posts_per_page'  => -1,
										        'order' => 'ASC',
										        'meta_query' => array(
										          array(
										            'key' => '_yl_lease_id',
										            'value'   => $lease_result->ID,
										            'compare' => '='
										          )
										        )
										      );	
								array_push($leases_ids, $lease_result->ID);

								$invoice_results = get_posts($in_ms_args);
								/*echo "<pre>";
								print_r($invoice_results);
								echo "</pre>";*/

							// exit();
								if($invoice_results!="")
								{	


									$k=0;
								foreach ($invoice_results as  $invoice_result) {
									// echo $invoice_result->ID;
									$due_date=get_post_meta( $invoice_result->ID, '_due_date', true );
									// echo "Due date".$due_date;
									// echo "start date".$start_Account2;
									// echo "end date".$end_Account2;

									if($due_date>=$start_Account2 && $due_date<=$end_Account2 )
									{
										if(!in_array($invoice_result->ID, $all_invoice_id_checker))
										{
											array_push($all_invoice_id_checker, $invoice_result->ID);
										}
										// echo "reached";
										// echo "string";
										// exit();
										/***Invoice Data*****/
													$invoice_id=$invoice_result->ID;
													$client_name=get_the_title(get_post_meta( $invoice_id, '_client_id', true ));
													// $company_id=yl_get_company_id_by_invoice_id($invoice_id);
												echo	"lease id".$leasd_id =	get_post_meta($invoice_id, '_yl_lease_id',true);
												echo "==";
													$company_id=	get_post_meta($leasd_id, '_yl_company_name', true);
												echo	$company_name=get_the_title( $company_id );
													echo "<br>";
													$total=get_post_meta( $invoice_id, '_total', true );
													$client_id=get_post_meta( $invoice_id, '_client_id', true);
													$client_name=get_the_title(get_post_meta( $invoice_id, '_client_id', true ));
													$suite_number="";
													$leasd_id= get_post_meta( $invoice_id, '_yl_lease_id', true );
													$suite_number=get_post_meta($leasd_id, '_yl_suite_number', true );
													if($suite_number=='-1')
													{
													$suite_number="Y-Memberships";
													}
													$invoice_num='=HYPERLINK("'.get_permalink($invoice_id).'", "'.$invoice_id.'")';

													$invoice_ids[$invoice_id]['client_name']=$client_name;
													$invoice_ids[$invoice_id]['company_name']=$company_name;
													$invoice_ids[$invoice_id]['invoice_num']=$invoice_num;
													$invoice_ids[$invoice_id]['suite_number']=$suite_number;
													$invoice_ids[$invoice_id]['num']=$invoice_id;
													$invoice_ids[$invoice_id]['totalmk']=$total;
													$invoice_ids[$invoice_id]['date']=$paymentdate;
													$invoice_ids[$invoice_id]['line_items']=maybe_unserialize(get_post_meta($invoice_id, '_doc_line_items', true ));

													
												//	$full_total=(float) ($full_total+$total);


									}

								
										/********************/
									// echo "In range".$invoice_result->ID;
									// array_push($invoice_ids, $invoice_result->ID);
									# code...

									}
// 										echo "<pre>";
// print_r($invoice_ids);
// echo "</pre>";
								
								}

							}




							// echo "<pre>";
							// print_r($lease_results);
							// echo "<pre>";
							$all_data[$suite_id]['leases']=$leases_ids;
							$all_data[$suite_id]['invoices']=$invoice_ids;
/*echo "<pre>";
print_r($all_data[$suite_id]['invoices']);
echo "</pre>";*/


           

			}



// echo "<pre>";
// print_r($all_data);
// echo "</pre>";
				/****Foreachmain*****/

			//Multisuite issue
							$start_Account2=strtotime($start_account);
							$end_Account2=strtotime($end_account);
							$args = array(
							'post_type' => 'sa_invoice',
						    'post_status' => 'any',

							'posts_per_page' => -1,
							'orderby' => 'meta_value',
							'order' => 'ASC',
							'meta_key' => '_due_date',
							'meta_query' => array(
							array(
							'key' => '_due_date',
							'value' => array($start_Account2, $end_Account2),
							'compare' => 'BETWEEN',
							'type' => 'Numeric'
							)
							)
							);

							$multisuite_invoices_loop = get_posts($args);

foreach ($multisuite_invoices_loop as $aa => $bb) {

	# code...
	array_push($all_invoice_id_checker22, 	$bb->ID);
$line_itemsmkkk=maybe_unserialize(get_post_meta($bb->ID, '_doc_line_items', true ));
		foreach ($line_itemsmkkk as $line_itemsmkkk_key => $line_itemsmkkk_value) {

$mkk_total22=$mkk_total22+$line_itemsmkkk_value['total'];

									}

// $mkk_total=get_post_meta( $bb->ID, "_total", true );
// $mkk_total22=$mkk_total22+$mkk_total;	
	
}
// echo "mk total== ".$mkk_total22;
     /*                      echo "<pre>";
print_r($multisuite_invoices_loop);
echo "</pre>";*/

							$multisuites_ids=array();
							$m_invoice_ids=array();
							$m_suite_ids_array=array();
							$m_suite_ids_array_chunk=array();
							$multisuite_alldata=array();
							$multisuite_alldata_chunk=array();
							$multisuite_ymember=array();

							$y_membership_items=array();

							$y_membership_clients=array();

						

							foreach ($multisuite_invoices_loop as $key_m => $value_m) {

								
								  // $value_m->ID;
								if(get_post_meta( $value_m->ID, '_yl_lease_id', true )=="")
								{
									// echo "<br>";
									// echo "Invoice ID ".$value_m->ID;
									// echo "</br>";
							

									if(!in_array($value_m->ID, $all_invoice_id_checker))
										{
											array_push($all_invoice_id_checker, $value_m->ID);
										}
									array_push($multisuites_ids, $value_m->ID);
									$line_items=maybe_unserialize(get_post_meta($value_m->ID, '_doc_line_items', true ));


									$multisuite_rent=0;
									$multisuite_aux=0;
									$count=1;
									$count_lineitem=count($line_items);

									$line_item_array_chunks=array();
									$aux_changer=0;
									$Differenece1=0;
									$Differenece2=0;
									foreach ($line_items as $l_key => $l_value) {
									$the_real_total=$the_real_total+$l_value['total'];
									// echo "<br>";
									// echo "==============";
									// echo $l_value['total'];
									// echo "<br>";

									$multichecker_real_total=$multichecker_real_total+$l_value['total'];
									$Differenece1=$Differenece1+$l_value['total'];
                                   // echo  $value_m->ID;echo "<br>";
										// echo "<br>";
										// echo $l_value['total'];
										// echo "<br>";
										if (strpos($l_value['desc'], 'Monthly Rent for ') !== false ) {
										$month_explode=explode('Monthly Rent for ', strip_tags($l_value['desc']));
										$multichecker_real_total2=$multichecker_real_total2+$l_value['total'];
										// ;
										// echo "Suite title=".$month_explode[1];
										// echo "<br>";
										// var_dump($month_explode[1]);
										// echo "here is title *******";
										  $title=str_replace(" Lease", "",$month_explode[1]);
										 // echo $title=str_replace(" Lease", "",$month_explode[1]);
										// var_dump($title);

										if (strpos($title, 'Y Membership') !== false) {
										  //  echo 'true';
											$multichecker_real_total2=$multichecker_real_total2+$l_value['total'];

										$aux_changer=$aux_changer+$l_value['total'];

										}
										else{
											$m_suite_idarr=get_page_by_title($title, 'ARRAY_A', 'suites' );	
										// echo "title=".$title;
										$m_suite_id=$m_suite_idarr['ID'];
										// echo "msuite id".$m_suite_id=$m_suite_idarr['ID'];

										// echo "Multisuite id=".$m_suite_id;
										// echo "<br>";
									// echo '<a href="'.get_post_permalink( $value_m->ID ).'" title="">'.$month_explode[1].'---->'.$value_m->ID.'</a>' ;
									// echo "<br>";
										array_push($m_suite_ids_array_chunk, $m_suite_id);
													// echo "<pre>";
													// echo "Suites Array";
													// print_r($m_suite_ids_array);
													// echo "<pre>";
										// array_push(array, var)
										$multisuite_alldata_chunk[$m_suite_id]['rent']=$l_value['total'];
										// $multisuite_alldata_chunk[$m_suite_id]['aux']=$aux_changer;
										$multisuite_alldata_chunk[$m_suite_id]['invoice_id']=$value_m->ID;
										$Differenece2=$Differenece2+$l_value['total'];
										if($count==$count_lineitem)
										{
											$multisuite_alldata_chunk[$m_suite_id]['aux']=$multisuite_alldata_chunk[$m_suite_id]['aux']+$aux_changer;
										}


										}
										
										// 			if($m_suite_id==729)
										// {
										// 	echo "<pre>";

										// 	print_r($multisuite_alldata_chunk);
										// 	echo "<pre>";
										// 	echo "total = ".$multichecker_real_total;
										// }
										// $multisuite_aux=0;
										// echo "<br>";
										}
										elseif (strpos($l_value['desc'], 'Multi Suite Discount') !== false ) {
											$multichecker_real_total2=$multichecker_real_total2+$l_value['total'];
										
											$aux_changer=$aux_changer+$l_value['total'];
											end($m_suite_ids_array_chunk);         // move the internal pointer to the end of the array
											$key = key($m_suite_ids_array_chunk);
											$last_suite_id=$m_suite_ids_array_chunk[$key];
											// echo "<br>";
											// echo "Last suite id ".$last_suite_id;
											// echo "<br>";
											// echo "Aux charger beforen Disccount ".$aux_changer;
											// echo "<br>";
											$multisuite_alldata_chunk[$last_suite_id]['aux']=$multisuite_alldata_chunk[$last_suite_id]['aux']+$aux_changer;
											$Differenece2=$Differenece2+$multisuite_alldata[$last_suite_id]['aux']+$aux_changer;
											// echo "Aux charger beforen Disccount ".$aux_changer;
										// $Differenece2=
										$aux_changer=0;

										}

										elseif($count==$count_lineitem)
										{
											// echo $count." count value =".$l_value['total'];
											$multichecker_real_total2=$multichecker_real_total2+$l_value['total'];

											// echo $count;
											// echo "<br>";
											// echo $count_lineitem;
											end($m_suite_ids_array_chunk);         // move the internal pointer to the end of the array
											$key = key($m_suite_ids_array_chunk);
											$last_suite_id=$m_suite_ids_array_chunk[$key];
											// echo "Last suite id ".$last_suite_id;

											if (strpos($lineitem['desc'], 'prorated moving') !== false) {
												$multisuite_alldata_chunk[$last_suite_id]['rent']=$multisuite_alldata_chunk[$last_suite_id]['rent']+$l_value['total'];
											}
											else{
														if (strpos($l_value['desc'], 'Security Deposit') == false ) {

															$aux_changer=$aux_changer+$l_value['total'];
														}

											//echo "Key";
											//echo $key;
											$multisuite_alldata_chunk[$last_suite_id]['aux']=$multisuite_alldata_chunk[$last_suite_id]['aux']+$aux_changer;
										
											$Differenece2=$Differenece2+$multisuite_alldata[$last_suite_id]['aux']+$aux_changer;


											}
								
										}
										else{



											// echo "Last suite id ".$last_suite_id;

											if (strpos($lineitem['desc'], 'prorated moving') !== false) {
																end($m_suite_ids_array_chunk);         // move the internal pointer to the end of the array
											$key = key($m_suite_ids_array_chunk);
											$last_suite_id=$m_suite_ids_array_chunk[$key];
												$multisuite_alldata_chunk[$last_suite_id]['rent']=$multisuite_alldata_chunk[$last_suite_id]['rent']+$l_value['total'];
											}
											else{

													$multichecker_real_total2=$multichecker_real_total2+$l_value['total'];
														if (strpos($l_value['desc'], 'Security Deposit') == false ) {

															$aux_changer=$aux_changer+$l_value['total'];
														}
												}
										}


										


// 										if (strpos($l_value['desc'], 'Monthly Rent for ') !== false ) {
// 										$month_explode=explode('Monthly Rent for ', strip_tags($l_value['desc']));
// 										// echo "Suite title=".$month_explode[1];
// 										// echo "<br>";
// 										// var_dump($month_explode[1]);
// 										$m_suite_idarr=get_page_by_title($month_explode[1], 'ARRAY_A', 'suites' );	
// 										$m_suite_id=$m_suite_idarr['ID'];
										
// 										// echo "Multisuite id=".$m_suite_id;
// 										// echo "<br>";
// 									// echo '<a href="'.get_post_permalink( $value_m->ID ).'" title="">'.$month_explode[1].'---->'.$value_m->ID.'</a>' ;
// 									// echo "<br>";
// 										array_push($m_suite_ids_array, $m_suite_id);
// 													/*echo "<pre>";
// 													echo "Suites Array";
// 													print_r($m_suite_ids_array);
// 													echo "<pre>";
// 										// array_push(array, var)*/
// 										$multisuite_alldata[$m_suite_id]['rent']=$l_value['total'];
// 										$multisuite_alldata[$m_suite_id]['aux']=$multisuite_aux;
// 										$multisuite_alldata[$m_suite_id]['invoice_id']=$value_m->ID;
// 										$multisuite_aux=0;
// 										// echo "<br>";
// 										}
// 										else{
// 										$multisuite_aux=(float) ($multisuite_aux+$l_value['total']);
// 										}


// 										if($count==$count_lineitem)
// 										{
// 											// echo $count;
// 											// echo "<br>";
// 											// echo $count_lineitem;
// 											end($m_suite_ids_array);         // move the internal pointer to the end of the array
// 											$key = key($m_suite_ids_array);
// 											$last_suite_id=$m_suite_ids_array[$key];
// 											//echo "Key";
// 											//echo $key;
// 											$multisuite_alldata[$last_suite_id]['aux']=(float) ($multisuite_alldata[$last_suite_id]['aux']+$multisuite_aux);
// // 							echo "<pre>";			
// // print_r($multisuite_alldata);
// // 							echo "</pre>";	
									
// 										}
											// echo $aux_changer;
											// echo "Aux changer = ".$aux_changer;
										$count++;
									}
									// echo "<br>";

									// echo "Real Difference ".$Differenece1;
									// echo "<br>";
									// echo "Fake Difference ".$Differenece2;
// 									if($Differenece1!=$Differenece2)
// 									{
// 										echo "Error is here !";
// 										echo "<br>";
// 										echo $Differenece1-$Differenece2;
// 									}
// 									echo "*************************";
// // echo $multichecker_real_total2;
// 									echo "<pre>";
// 									print_r($multisuite_alldata_chunk);
// 									echo "</pre>";
									// exit();

									// echo "<br>";
								}

								$yl_lease_id=get_post_meta( $value_m->ID, '_yl_lease_id', true );
							
								$yl_suite_number=get_post_meta( $yl_lease_id, '_yl_suite_number', true );
								// if(  $value_m->ID==2076)
								// {
								// 	echo "2961";
								// 	echo "2076";
								// 	echo "	";
								// 	echo "vardump";
								// 	var_dump($yl_suite_number);
								// }

								 // $yl_lease_id."suite id".$yl_suite_number;echo "<br>";

								$client_id=get_post_meta($value_m->ID, '_client_id', true );

                              

								if($yl_suite_number == -1 || $yl_suite_number == 'Y-Membership')
								{

									// echo "web".$value_m->ID;echo "<br>";
									array_push($y_membership_clients,$client_id);
									// echo get_the_title($yl_lease_id)."======".$yl_suite_number;

									$line_items=maybe_unserialize(get_post_meta($value_m->ID, '_doc_line_items', true ));
									// echo "<pre>";
									// echo "<pre>";
									$y_membership_items[$client_id]['aux']=0;
									$y_membership_items[$client_id]['invoice_id']=$value_m->ID;

									foreach ($line_items as $l_key => $l_value) {
									$the_real_total=$the_real_total+$l_value['total'];

									if (in_array($client_id, $y_membership_clients)) {
										# code...
													if (strpos($l_value['desc'], 'Monthly Rent') !== false ) {
													// echo $l_value['total'];
													// echo $value_m->ID;
													$y_membership_items[$client_id]['rent']=(float) ($l_value['total']+$y_membership_items[$client_id]['rent']);
													}
													else{
														if (strpos($l_value['desc'], 'Security Deposit') == false ) {

													$y_membership_items[$client_id]['aux']=(float) ($y_membership_items[$client_id]['aux']+$l_value['total']);
														}

													}

									}
									else{

													if (strpos($l_value['desc'], 'Monthly Rent') !== false ) {
														// echo $l_value['total'];
														// echo $value_m->ID;
													$y_membership_items[$client_id]['rent']=$l_value['total'];
													}
													else{
													if (strpos($l_value['desc'], 'Security Deposit') == false ) {

													$y_membership_items[$client_id]['aux']=(float) ($y_membership_items[$client_id]['aux']+$l_value['total']);

													}

													}
									}
								




								}
									// echo "<pre>";
									// print_r($line_items);
									// echo "<pre>";


								}



































								// echo "<pre>";
								// echo "Multisuite";
								// print_r($multisuite_alldata);
								// echo "<pre>";
								# code...
							}

							// echo $multichecker_real_total2;
							// 		echo "<pre>";
							// 		print_r($multisuite_alldata);
							// 		echo "</pre>";
									// exit();

							// echo "<pre>";
							// // echo "Suites Array";
							// print_r($y_membership_items);
							// echo "<pre>";

							// echo "<pre>";
							// print_r($all_data);
							// echo "<pre>";
			/***all data start**/
				 ?>
				
				 <?php


/*
echo "<pre>";
print_r($all_data);
echo "</pre>";
*/
			foreach ($y_membership_items as $ikkey => $lankk) {

				 $the_total=$the_total+$lankk['rent'];
				 $the_total=$the_total+$lankk['aux'];
				 $linketotal=$lankk['rent']+$lankk['aux'];
				 $company_id=	get_post_meta(get_post_meta($lankk['invoice_id'], '_yl_lease_id',true), '_yl_company_name', true);
				$company_name=get_the_title( $company_id );
				// echo "*********".$lankk['invoice_id']."**************".$linketotal;
				// echo "<br>";
				// echo $the_total;
				// echo "<br>";
									if(!in_array($lankk['invoice_id'], $all_invoice_id_checker))
										{
											array_push($all_invoice_id_checker, $lankk['invoice_id']);
										}
// echo "********".$ikkey."**** ".$lankk['rent']."****er******".$lankk['aux'];echo "<br>";
// echo $the_total;

// echo "<br>";
				# code...
					?>
					<tr>

					<td>Y Membership <?php echo $ikkey; ?></td>
					<td><?php echo $company_name; ?></td> 
					<td><a onclick="submitymember(<?php echo $ikkey;  ?>,'<?php echo $start_account; ?>','<?php echo $end_account; ?>',<?php  echo $building; ?>	);" href="javascript:void(0);" title="#">$<?php echo $lankk['rent']; ?></a></td>
					<td><a onclick="submitymemberaux(<?php echo $ikkey;  ?>,'<?php echo $start_account; ?>','<?php echo $end_account; ?>',<?php  echo $building; ?>	);" href="javascript:void(0);" title="#"><?php echo '$' . $lankk['aux']; ?></a></td>
					<!-- <td></td> -->
					</tr>

					<?php
							$csv_data_array[$i]['Suite Number']='Y Membership'.$ikkey;
							$csv_data_array[$i]['Tennant Name']= $company_name;
							$csv_data_array[$i]['Rent']='$'.number_format($lankk['rent'],2);
							$csv_data_array[$i]['Aux Charges']='$' . number_format($lankk['aux'],2);
			
$i++;
			}
// echo "<pre>";
// print_r($all_data);
// echo "</pre>";
			$abc2=0;
			foreach ($all_data as $suite_id => $main) {
               



				$leasd_id =	$main['leases'][0];				
				$invoices_ids= $main['invoices'];
				
				// var_dump($invoices_ids);
				// echo "<br>";
				// echo "lease id =".$leasd_id;
				// echo "<br>";
				// echo "lease id =".$leasd_id;
				// echo "<pre>";
				// print_r($invoices_ids);
				// echo "<pre>";
				// exit();
				 if(!is_array($invoice_ids))
				 {
				 	// echo "empty";
				?>
				<tr>

				<td><?php echo get_the_title( $suite_id ); ?></td>
				<td>0</td>
				<td>0</td>
				<td>0</td>
				<!-- <td></td> -->
				</tr>
				<?php
				 }
				 else
				 {

				 	if(!empty($invoices_ids))
				 	{
				 	$tenament_name="";
				 	$aux_charges=0;
				 	$rent_charges=0;
					$monthlyrent=0;
				 	$suite_name_mk=get_the_title( $suite_id );



				 	foreach ($invoices_ids as $key => $value) {
				 			$tenament_name=$value['company_name'];
				 				// 			if($key==1663)
									// {
									// 	echo "this is id 1663";
									// 	var_dump(get_post_meta( $value_m->ID, '_yl_lease_id', true ));
									// 		echo "<pre>";
									// print_r($line_items);
									// echo "</pre>";
									// }

							foreach ($value['line_items'] as $line => $lineitem) {
						$the_real_total=$the_real_total+$lineitem['total'];

echo "*********".$key."**************".$lineitem['total'];
echo "<br>";
						$the_total=$the_total+$lineitem['total'];
// echo "<br>";
// echo "********".$key."**** ";
// echo "rate".$lineitem['total'];echo "<br>";
// echo  $the_total;

// echo "<br>";

							if (strpos($lineitem['desc'], 'Rent') !== false || strpos($lineitem['desc'], 'prorated moving') !== false ) {
							$rent_charges=(float) ($rent_charges+$lineitem['total']);
							}
							else{
														if (strpos($lineitem['desc'], 'Security Deposit') == false ) {

							$aux_charges=(float) ($aux_charges+$lineitem['total']);
							}
							}


							}


				 		# code...
				 	}

				 	// echo $suite_id;
				 	// echo $suite_id;
				 	// echo "<br>";
				 	// if($suite_id==784 || $suite_id==772)
				 	// {
				 	// 	echo "**************************************reached".$suite_id;
				 	// }
				 	
				 if(get_post_meta( $key, '_yl_lease_id', true )!="")
				 {
// echo get_the_title( $suite_id )."Invoice".$key;
				 	$csv_data_array[$i]['Suite Number']=get_the_title( $suite_id );
							$csv_data_array[$i]['Tennant Name']= $tenament_name;
							$csv_data_array[$i]['Rent']='$'.number_format($rent_charges,2);
							$csv_data_array[$i]['Aux Charges']='$' . number_format($aux_charges,2);
// echo "<br>";
					?>
					<tr>

					<td><?php echo get_the_title( $suite_id ); ?></td>
					<td><?php echo $tenament_name; ?></td> 
					<td><a onclick="submitResources(<?php echo $suite_id;  ?>,'<?php echo $start_account; ?>','<?php echo $end_account; ?>',<?php  echo $building; ?>	);" href="javascript:void(0);" title="#">$<?php echo $rent_charges; ?></a></td>
					<td><a onclick="submitResourcesauxrent(<?php echo $suite_id;  ?>,'<?php echo $start_account; ?>','<?php echo $end_account; ?>',<?php  echo $building; ?>	);" href="javascript:void(0);" title="#"><?php echo '$' . $aux_charges; ?></a></td>
					<!-- <td></td> -->
					</tr>

					<?php
				 	

				 }


				
				}
				elseif(!empty($multisuite_alldata_chunk[$suite_id])){
					array_push($mk_cc, $suite_id);
					// echo "above multisuite";
				$mm1=$multisuite_alldata_chunk[$suite_id]['rent']+$multisuite_alldata_chunk[$suite_id]['aux'];
							$the_total=$the_total+$mm1;

$abc2=$abc2+$mm1;
						 	$csv_data_array[$i]['Suite Number']=get_the_title( $suite_id );
							$csv_data_array[$i]['Tennant Name']= $tenament_name;
							$csv_data_array[$i]['Rent']='$'.number_format($multisuite_alldata_chunk[$suite_id]['rent'],2);
							$csv_data_array[$i]['Aux Charges']='$' . number_format($multisuite_alldata_chunk[$suite_id]['aux'],2) ;

					// echo "<br>";

	?>
					<tr>

					<td><?php echo get_the_title( $suite_id ); ?></td>
				<td><?php echo $tenament_name; ?></td>
					<td><a class="multisuiteinvoicemk" onclick="submitResources_multisuite(<?php echo $suite_id;  ?>,<?php echo $multisuite_alldata_chunk[$suite_id]['invoice_id'];  ?>,'<?php echo $start_account; ?>','<?php echo $end_account; ?>',<?php  echo $building; ?>	);" href="javascript:void(0);" title="#">$<?php echo $multisuite_alldata_chunk[$suite_id]['rent']; ?></a></td>
					<td><a onclick="submitResourcesauxrent_multisite(<?php echo $mulkey;  ?>,<?php echo $multisuite_alldata_chunk[$suite_id]['invoice_id'];  ?>,'<?php echo $start_account; ?>','<?php echo $end_account; ?>',<?php  echo $building; ?>,'<?php echo $aux_show; ?>');" href="javascript:void(0);" title="#"><?php echo '$' . $multisuite_alldata_chunk[$suite_id]['aux']; ?></a></td>
					<!-- <td></td> -->
					</tr>
					<?php


				}
				else{

						 	$csv_data_array[$i]['Suite Number']=get_the_title( $suite_id );
							$csv_data_array[$i]['Tennant Name']= $tenament_name;
							$csv_data_array[$i]['Rent']='$0';
							$csv_data_array[$i]['Aux Charges']='$0';
?>
		<tr>

				<td><?php echo get_the_title( $suite_id ); ?></td>
				<td><?php echo $tenament_name; ?></td>
				<td>$0</td>
				<td>0</td>
				<!-- <td></td> -->
				</tr>
<?php

				}





				}
				 // exit();
				 // echo "<pre>";
				 // print_r($invoices_ids);
				 // echo "</pre>";
				 // $key=  														 
				 // $suite_id=	get_post_meta($leasd_id, '_yl_product_id',true);
				// echo "<pre>";
				// print_r($value);
				// echo "</pre>";
$i++;
			}	

$abc=0;
			foreach ($multisuite_alldata_chunk as $mulkey => $mulvalue) {
				// echo "below multisuite";
					array_push($mk_cc2, $mulkey);

					// echo "<br>";
				$mm=$multisuite_alldata_chunk[$mulkey]['rent']+$multisuite_alldata_chunk[$mulkey]['aux'];
				// echo "*********".$multisuite_alldata_chunk[$mulkey]['invoice_id']."**************".$mm;
// echo "<br>";
// echo								$the_total=$the_total+$mm;
$abc=$abc+$mm;
					?>
<!-- 					<tr>

					<td><?php echo get_the_title( $mulkey ); ?></td>
			
					<td><a class="multisuiteinvoicemk" onclick="submitResources_multisuite(<?php echo $mulkey;  ?>,<?php echo $multisuite_alldata_chunk[$mulkey]['invoices_id'];  ?>,'<?php echo $start_account; ?>','<?php echo $end_account; ?>',<?php  echo $building; ?>	);" href="javascript:void(0);" title="#">$<?php echo $multisuite_alldata_chunk[$mulkey]['rent']; ?></a></td>
					<td><a onclick="submitResourcesauxrent_multisite(<?php echo $mulkey;  ?>,<?php echo $multisuite_alldata_chunk[$mulkey]['invoice_id'];  ?>,'<?php echo $start_account; ?>','<?php echo $end_account; ?>',<?php  echo $building; ?>,'<?php echo $aux_show; ?>');" href="javascript:void(0);" title="#"><?php echo '$' . $multisuite_alldata_chunk[$mulkey]['aux']; ?></a></td>
			
					</tr>  -->
					<?php
			}

			// echo "Multichekerrrrrrrrr".$abc;
			// echo "Multichekerrrrrrrrr222".$abc2;
			
// echo "<pre>";

// echo "this is total of multisuite".$temp_total_multi;
//            print_r($multisuite_alldata);  

// echo "</pre>";

// echo "<br>";echo "<br>";echo "<br>";echo "<br>";echo "<br>";

         
// echo "<pre>";
// print_r($y_membership_items);

				 	// echo "<pre>";
				 	// print_r( $multisuite_alldata);
				 	// echo "<pre>";




// 				echo "Mk real total is ".$the_real_total;
// 				echo "<br>";
// echo "Unrealmultichechker ".$multichecker_real_total2;
// 				echo "<br>";
// echo "Multichechker ".$multichecker_real_total;
				
// 				echo "<pre>";
// print_r($all_invoice_id_checker);
// echo "</pre>";

// 				echo "<pre>";
// print_r($all_invoice_id_checker22);
// echo "</pre>";
// $result13 = array_diff($all_invoice_id_checker22,$all_invoice_id_checker);
// echo "Differenece";
// 				echo "<pre>";

// print_r($result13);

// echo "<pre>";
// print_r($mk_cc);
// echo "</pre>";
// echo "<pre>";
// print_r($mk_cc2);
// echo "</pre>";


// $resultxx = array_diff($all_invoice_id_checker22,$all_invoice_id_checker);
// print_r($resultxx);

// 				echo "</pre>";
			restore_current_blog();

			

			


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


				</tbody>
				</table>
				</div>
				</div>
<!-- <input type="submit" name="Run" value="Run">		 -->
				<?php
				echo "Total Amount is ".$the_total;
				if(isset($_REQUEST['Run']))
				{

				reporst_csv("Rent report invoice all.csv",$csv_data_array,"rental_report");
				}


			}
			

			else{

?>
<form action="" method="post" >
	
		<input type="hidden" name="_accounting_report_id" value="<?php echo $_REQUEST['_accounting_report_id']; ?>">	
		<input type="hidden" name="_accounting_building_id" value="<?php echo $_REQUEST['_accounting_building_id']; ?>">	
		<input type="hidden" name="_accounting_start_date" value="<?php echo $_REQUEST['_accounting_start_date']; ?>">	
		<input type="hidden" name="_accounting_end_date" value="<?php echo $_REQUEST['_accounting_end_date']; ?>">	
		<input type="hidden" name="accountingmk_submit" value="">	

	<input type="submit" name="Run" value="Download"/>
</form>
<?php

			// echo "New Rent Report";
			$building=$_POST['_accounting_building_id'];

			switch_to_blog($building);
			switch ($building) {
				case '4':
				$class="DEV";
				break;
				case '9':
				$class="MCK";
				break;
				case '10':
				$class="FR";
				break;
				case '11':
				$class="FHRA";
				break;	
				case '12':
				$class="C1";
				break;
				case '13':
				$class="F1";
				break;
				case '14':
				$class="F2";
				break;
				case '15':
				$class="GW";
				break;
				case '16':
				$class="N1";
				break;
				case '17':
				$class="N2";
				break;
				case '18':
				$class="OSW";
				break;										
				default:
				$class="DEV";
					break;
			}

			global $wpdb;
			global $post;
			// echo "this is time".strtotime('2016-06-21');
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
							$start_Account2=strtotime($start_account);
							$end_Account2=strtotime($end_account);
							$args = array(
						    'post_type' => 'suites',
						    'post_status' => 'publish',
						    'posts_per_page' => -1,
						    'order' => 'ASC',
						    'orderby'=>'title',
						);
			$results = get_posts($args);
			/*echo "<pre>";
			print_r($results);
			echo "<pre>";*/
			$all_invoice_id_checker=array();
			$all_invoice_id_checker22=array();
			$mkk_total22=0;
			$all_data=array();
			$the_total=0;
			$the_real_total=0;
			$the_total_rent=0;
			$the_total_aux=0;
			$multichecker_real_total=0;
			$multichecker_real_total2=0;
			$temp_total_multi=0;
$mk_cc2=array();
$mk_cc=array();
			$full_total=0;
			$k=0;
			foreach ($results as $key => $result) {
			$paymentdatefull=explode(" ", $result->post_modified);		
			$paymentdate=$paymentdatefull[0];	
			$suite_id=$result->ID;
			$suite_number=get_the_title( $suite_id );
							 $yl_ms_args = array(
										        'post_type'   => 'lease',
										        'post_status'   => 'publish',
										        'numberposts'  => -1,
										        'order' => 'ASC',
										        'meta_query' => array(
										          array(
										            'key' => '_yl_suite_number',
										            'value'   => $suite_number,
										            'compare' => '='
										          )
										        )
										      );

							$lease_results = get_posts($yl_ms_args);
							// if($result->ID==1198)
							// {
							// 	echo "This is suite number".$suite_number;
							// 	echo "<pre>";
							// 	print_r($lease_results);
							// 	var_dump($lease_results);
							// 	echo "</pre>";
							// }
							/*echo "<pre>";
							print_r($lease_results);
							echo "</pre>";*/
							$leases_ids=array();
								$invoice_ids=array();

							foreach ($lease_results as  $lease_result) {
								$in_ms_args = array(
										        'post_type'   => 'sa_invoice',
										        'post_status'   => 'any',
										        'posts_per_page'  => -1,
										        'order' => 'ASC',
										        'meta_query' => array(
										          array(
										            'key' => '_yl_lease_id',
										            'value'   => $lease_result->ID,
										            'compare' => '='
										          )
										        )
										      );	
								array_push($leases_ids, $lease_result->ID);

								$invoice_results = get_posts($in_ms_args);
								// if($lease_result->ID==1345)
								// {
								// 	echo "This is lease=".$lease_result->ID;
								// 	echo "<pre>";
								// print_r($invoice_results);
								// echo "</pre>";
								// echo "<pre>";
								// var_dump($invoice_results);
								// echo "</pre>";
								
								// }
								/*echo "<pre>";
								print_r($invoice_results);
								echo "</pre>";*/

							// exit();
								if($invoice_results!="")
								{	


									$k=0;
								foreach ($invoice_results as  $invoice_result) {

									$due_date=get_post_meta( $invoice_result->ID, '_due_date', true );
									// 				if($lease_result->ID==1345)
									// {

									// echo "This is custom for 1345-->".$invoice_result->ID;
									// echo "<br>";
									// echo $due_date;
									// echo "<br>";
									// echo "Due date".$due_date;
									// echo "<br>";
									// echo "start date".$start_Account2;
									// echo "<br>";
									// echo "end date".$end_Account2;
									// }
									// echo "Due date".$due_date;
									// echo "start date".$start_Account2;
									// echo "end date".$end_Account2;

									if($due_date>=$start_Account2 && $due_date<=$end_Account2 )
									{
										if(!in_array($invoice_result->ID, $all_invoice_id_checker))
										{
											array_push($all_invoice_id_checker, $invoice_result->ID);
										}
										// echo "reached";
										// echo "string";
										// exit();
										/***Invoice Data*****/
													$invoice_id=$invoice_result->ID;
													$client_name=get_the_title(get_post_meta( $invoice_id, '_client_id', true ));
													// $company_id=yl_get_company_id_by_invoice_id($invoice_id);
													$leasd_id =	get_post_meta($invoice_id, '_yl_lease_id',true);
													// echo "===";
													$company_id=	get_post_meta($leasd_id, '_yl_company_name', true);
													$company_name=get_the_title( $company_id );
													// echo "<br>";
													$total=get_post_meta( $invoice_id, '_total', true );
													$client_id=get_post_meta( $invoice_id, '_client_id', true);
													$client_name=get_the_title(get_post_meta( $invoice_id, '_client_id', true ));
													$suite_number="";
													$leasd_id= get_post_meta( $invoice_id, '_yl_lease_id', true );
													$suite_number=get_post_meta($leasd_id, '_yl_suite_number', true );
													if($suite_number=='-1')
													{
													$suite_number="Y-Memberships";
													}
													$invoice_num='=HYPERLINK("'.get_permalink($invoice_id).'", "'.$invoice_id.'")';
													// echo "Invoice id=".$invoice_id." has lease id=".$leasd_id." with suite=".$suite_number;
													// echo "<br>";
													$invoice_ids[$invoice_id]['client_name']=$client_name;
													$invoice_ids[$invoice_id]['company_name']=$company_name;
													$invoice_ids[$invoice_id]['invoice_num']=$invoice_num;
													$invoice_ids[$invoice_id]['suite_number']=$suite_number;
													$invoice_ids[$invoice_id]['num']=$invoice_id;
													$invoice_ids[$invoice_id]['totalmk']=$total;
													$invoice_ids[$invoice_id]['date']=$paymentdate;
													$invoice_ids[$invoice_id]['line_items']=maybe_unserialize(get_post_meta($invoice_id, '_doc_line_items', true ));

													
												//	$full_total=(float) ($full_total+$total);


									}

								
										/********************/
									// echo "In range".$invoice_result->ID;
									// array_push($invoice_ids, $invoice_result->ID);
									# code...

									}
// 										echo "<pre>";
// print_r($invoice_ids);
// echo "</pre>";
								
								}

							}




							// echo "<pre>";
							// print_r($lease_results);
							// echo "<pre>";
							$all_data[$suite_id]['leases']=$leases_ids;
							$all_data[$suite_id]['invoices']=$invoice_ids;
/*echo "<pre>";
print_r($all_data[$suite_id]['invoices']);
echo "</pre>";*/


           

			}



// echo "<pre>";
// print_r($all_data);
// echo "</pre>";
				/****Foreachmain*****/

			//Multisuite issue
							$start_Account2=strtotime($start_account);
							$end_Account2=strtotime($end_account);
							$args = array(
							'post_type' => 'sa_invoice',
						    'post_status' => 'any',

							'posts_per_page' => -1,
							'orderby' => 'meta_value',
							'order' => 'ASC',
							'meta_key' => '_due_date',
							'meta_query' => array(
							array(
							'key' => '_due_date',
							'value' => array($start_Account2, $end_Account2),
							'compare' => 'BETWEEN',
							'type' => 'Numeric'
							)
							)
							);

							$multisuite_invoices_loop = get_posts($args);

foreach ($multisuite_invoices_loop as $aa => $bb) {

	# code...
	array_push($all_invoice_id_checker22, 	$bb->ID);
$line_itemsmkkk=maybe_unserialize(get_post_meta($bb->ID, '_doc_line_items', true ));
		foreach ($line_itemsmkkk as $line_itemsmkkk_key => $line_itemsmkkk_value) {

$mkk_total22=$mkk_total22+$line_itemsmkkk_value['total'];

									}

// $mkk_total=get_post_meta( $bb->ID, "_total", true );
// $mkk_total22=$mkk_total22+$mkk_total;	
	
}
// echo "mk total== ".$mkk_total22;
     /*                      echo "<pre>";
print_r($multisuite_invoices_loop);
echo "</pre>";*/

							$multisuites_ids=array();
							$m_invoice_ids=array();
							$m_suite_ids_array=array();
							$m_suite_ids_array_chunk=array();
							$multisuite_alldata=array();
							$multisuite_alldata_chunk=array();
							$multisuite_ymember=array();

							$y_membership_items=array();

							$y_membership_clients=array();

						
							$weirdymember=array();
							foreach ($multisuite_invoices_loop as $key_m => $value_m) {

								
								  // $value_m->ID;
								if(get_post_meta( $value_m->ID, '_yl_lease_id', true )=="")
								{
									// echo "invoice id=".$value_m->ID;
										$leasd_id =	get_post_meta($value_m->ID, '_yl_lease_id',true);
													// echo "===";
													$company_id=	get_post_meta($leasd_id, '_yl_company_name', true);
													$company_name=get_the_title( $company_id );
													// echo "<br>";
									// echo "<br>";
									// echo "Invoice ID ".$value_m->ID;
									// echo "</br>";
									// if($value_m->ID==2961)
									// {
									// 	echo "this is id 2961";
									// 	var_dump(get_post_meta( $value_m->ID, '_yl_lease_id', true ));
									// }

									if(!in_array($value_m->ID, $all_invoice_id_checker))
										{
											array_push($all_invoice_id_checker, $value_m->ID);
										}
									array_push($multisuites_ids, $value_m->ID);
									$line_items=maybe_unserialize(get_post_meta($value_m->ID, '_doc_line_items', true ));
									// echo "<pre>";
									// print_r($line_items);
									// echo "</pre>";
									$multisuite_rent=0;
									$multisuite_aux=0;
									$count=1;
									$count_lineitem=count($line_items);

									$line_item_array_chunks=array();
									$aux_changer=0;
									$Differenece1=0;
									$Differenece2=0;
									foreach ($line_items as $l_key => $l_value) {
									$the_real_total=$the_real_total+$l_value['total'];
									// echo "<br>";
									// echo "==============";
									// echo $l_value['total'];
									// echo "<br>";

									$multichecker_real_total=$multichecker_real_total+$l_value['total'];
									$Differenece1=$Differenece1+$l_value['total'];
                                   // echo  $value_m->ID;echo "<br>";
										// echo "<br>";
										// echo $l_value['total'];
										// echo "<br>";
										if (strpos($l_value['desc'], 'Monthly Rent for ') !== false ) {
										$month_explode=explode('Monthly Rent for ', strip_tags($l_value['desc']));
										$multichecker_real_total2=$multichecker_real_total2+$l_value['total'];
										// ;
										// echo "Suite title=".$month_explode[1];
										// echo "<br>";
										// var_dump($month_explode[1]);
										// echo "here is title *******";
										  $title=str_replace(" Lease", "",$month_explode[1]);
										 // echo $title=str_replace(" Lease", "",$month_explode[1]);
										// var_dump($title);

										if (strpos($title, 'Y Membership') !== false) {
										  //  echo 'true';
											
											$weirdymember[$value_m->ID]['rent']=$l_value['total'];
											$weirdymember[$value_m->ID]['company_name']=$company_name;
											// $company_name
											// array_push($weirdymember, var)
											$multichecker_real_total2=$multichecker_real_total2+$l_value['total'];

										// $aux_changer=$aux_changer+$l_value['total'];

										}
										else{
											$m_suite_idarr=get_page_by_title($title, 'ARRAY_A', 'suites' );	
										// echo "title=".$title;
										$m_suite_id=$m_suite_idarr['ID'];
										// echo "msuite id".$m_suite_id=$m_suite_idarr['ID'];

										// echo "Multisuite id=".$m_suite_id;
										// echo "<br>";
									// echo '<a href="'.get_post_permalink( $value_m->ID ).'" title="">'.$month_explode[1].'---->'.$value_m->ID.'</a>' ;
									// echo "<br>";
										array_push($m_suite_ids_array_chunk, $m_suite_id);
													// echo "<pre>";
													// echo "Suites Array";
													// print_r($m_suite_ids_array);
													// echo "<pre>";
										// array_push(array, var)
										$multisuite_alldata_chunk[$m_suite_id]['rent']=$l_value['total'];
										// $multisuite_alldata_chunk[$m_suite_id]['aux']=$aux_changer;
										$multisuite_alldata_chunk[$m_suite_id]['invoice_id']=$value_m->ID;
										$Differenece2=$Differenece2+$l_value['total'];
										if($count==$count_lineitem)
										{

											$multisuite_alldata_chunk[$m_suite_id]['aux']=$multisuite_alldata_chunk[$m_suite_id]['aux']+$aux_changer;
										}


										}
										
										// 			if($m_suite_id==729)
										// {
										// 	echo "<pre>";

										// 	print_r($multisuite_alldata_chunk);
										// 	echo "<pre>";
										// 	echo "total = ".$multichecker_real_total;
										// }
										// $multisuite_aux=0;
										// echo "<br>";
										}
										elseif (strpos($l_value['desc'], 'Multi Suite Discount') !== false ) {
											$multichecker_real_total2=$multichecker_real_total2+$l_value['total'];
										
											$aux_changer=$aux_changer+$l_value['total'];
											end($m_suite_ids_array_chunk);         // move the internal pointer to the end of the array
											$key = key($m_suite_ids_array_chunk);
											$last_suite_id=$m_suite_ids_array_chunk[$key];
											// echo "<br>";
											// echo "Last suite id ".$last_suite_id;
											// echo "<br>";
											// echo "Aux charger beforen Disccount ".$aux_changer;
											// echo "<br>";
											$multisuite_alldata_chunk[$last_suite_id]['aux']=$multisuite_alldata_chunk[$last_suite_id]['aux']+$aux_changer;
											$Differenece2=$Differenece2+$multisuite_alldata[$last_suite_id]['aux']+$aux_changer;
											// echo "Aux charger beforen Disccount ".$aux_changer;
										// $Differenece2=
										$aux_changer=0;

										}

										elseif($count==$count_lineitem)
										{
											// echo $count." count value =".$l_value['total'];
											$multichecker_real_total2=$multichecker_real_total2+$l_value['total'];

											// echo $count;
											// echo "<br>";
											// echo $count_lineitem;
											end($m_suite_ids_array_chunk);         // move the internal pointer to the end of the array
											$key = key($m_suite_ids_array_chunk);
											$last_suite_id=$m_suite_ids_array_chunk[$key];
											if(strpos($lineitem['desc'], 'prorated moving') !== false)
											{
												$multisuite_alldata_chunk[$last_suite_id]['rent']=$multisuite_alldata_chunk[$last_suite_id]['rent']+$l_value['total'];

											}
											else{
																// echo "Last suite id ".$last_suite_id;
												if (strpos($l_value['desc'], 'Security Deposit') == false ) {
											$aux_changer=$aux_changer+$l_value['total'];
										}

											//echo "Key";
											//echo $key;
											$multisuite_alldata_chunk[$last_suite_id]['aux']=$multisuite_alldata_chunk[$last_suite_id]['aux']+$aux_changer;
										
											$Differenece2=$Differenece2+$multisuite_alldata[$last_suite_id]['aux']+$aux_changer;

											}
							
										}
										else{
													// echo "Last suite id ".$last_suite_id;

											if (strpos($lineitem['desc'], 'prorated moving') !== false) {
																end($m_suite_ids_array_chunk);         // move the internal pointer to the end of the array
											$key = key($m_suite_ids_array_chunk);
											$last_suite_id=$m_suite_ids_array_chunk[$key];
												$multisuite_alldata_chunk[$last_suite_id]['rent']=$multisuite_alldata_chunk[$last_suite_id]['rent']+$l_value['total'];
											}
											else{

													$multichecker_real_total2=$multichecker_real_total2+$l_value['total'];
													if (strpos($l_value['desc'], 'Security Deposit') == false ) {
													$aux_changer=$aux_changer+$l_value['total'];
												}
												}
										}


										


// 										if (strpos($l_value['desc'], 'Monthly Rent for ') !== false ) {
// 										$month_explode=explode('Monthly Rent for ', strip_tags($l_value['desc']));
// 										// echo "Suite title=".$month_explode[1];
// 										// echo "<br>";
// 										// var_dump($month_explode[1]);
// 										$m_suite_idarr=get_page_by_title($month_explode[1], 'ARRAY_A', 'suites' );	
// 										$m_suite_id=$m_suite_idarr['ID'];
										
// 										// echo "Multisuite id=".$m_suite_id;
// 										// echo "<br>";
// 									// echo '<a href="'.get_post_permalink( $value_m->ID ).'" title="">'.$month_explode[1].'---->'.$value_m->ID.'</a>' ;
// 									// echo "<br>";
// 										array_push($m_suite_ids_array, $m_suite_id);
// 													/*echo "<pre>";
// 													echo "Suites Array";
// 													print_r($m_suite_ids_array);
// 													echo "<pre>";
// 										// array_push(array, var)*/
// 										$multisuite_alldata[$m_suite_id]['rent']=$l_value['total'];
// 										$multisuite_alldata[$m_suite_id]['aux']=$multisuite_aux;
// 										$multisuite_alldata[$m_suite_id]['invoice_id']=$value_m->ID;
// 										$multisuite_aux=0;
// 										// echo "<br>";
// 										}
// 										else{
// 										$multisuite_aux=(float) ($multisuite_aux+$l_value['total']);
// 										}


// 										if($count==$count_lineitem)
// 										{
// 											// echo $count;
// 											// echo "<br>";
// 											// echo $count_lineitem;
// 											end($m_suite_ids_array);         // move the internal pointer to the end of the array
// 											$key = key($m_suite_ids_array);
// 											$last_suite_id=$m_suite_ids_array[$key];
// 											//echo "Key";
// 											//echo $key;
// 											$multisuite_alldata[$last_suite_id]['aux']=(float) ($multisuite_alldata[$last_suite_id]['aux']+$multisuite_aux);
// // 							echo "<pre>";			
// // print_r($multisuite_alldata);
// // 							echo "</pre>";	
									
// 										}
											// echo $aux_changer;
											// echo "Aux changer = ".$aux_changer;
										$count++;
									}
									// echo "<br>";

									// echo "Real Difference ".$Differenece1;
									// echo "<br>";
									// echo "Fake Difference ".$Differenece2;
// 									if($Differenece1!=$Differenece2)
// 									{
// 										echo "Error is here !";
// 										echo "<br>";
// 										echo $Differenece1-$Differenece2;
// 									}
// 									echo "*************************";
// // echo $multichecker_real_total2;
// 									echo "<pre>";
// 									print_r($multisuite_alldata_chunk);
// 									echo "</pre>";
									// exit();

									// echo "<br>";
								}

								$yl_lease_id=get_post_meta( $value_m->ID, '_yl_lease_id', true );
							
								$yl_suite_number=get_post_meta( $yl_lease_id, '_yl_suite_number', true );
								// if(  $value_m->ID==1642)
								// {
								// 	echo "1642";
								// 	// echo "2076";
								// 	echo "	";
								// 	echo "vardump";
								// 	var_dump($yl_suite_number);
								// }

								 // $yl_lease_id."suite id".$yl_suite_number;echo "<br>";

								$client_id=get_post_meta($value_m->ID, '_client_id', true );

                              

								if($yl_suite_number == -1 || $yl_suite_number == 'Y-Membership')
								{

									// echo "web".$value_m->ID;echo "<br>";
									array_push($y_membership_clients,$client_id);
									// echo get_the_title($yl_lease_id)."======".$yl_suite_number;

									$line_items=maybe_unserialize(get_post_meta($value_m->ID, '_doc_line_items', true ));
								// 	if(  $value_m->ID==1642)
								// {
								// 	echo "<pre>";
								// 	print_r($line_items);
								// 	echo "</pre>";
								// }
									// echo "<pre>";
									// echo "<pre>";
									$y_membership_items[$client_id]['aux']=0;
									$y_membership_items[$client_id]['invoice_id']=$value_m->ID;

									foreach ($line_items as $l_key => $l_value) {
									$the_real_total=$the_real_total+$l_value['total'];

									if (in_array($client_id, $y_membership_clients)) {
										# code...
													if (strpos($l_value['desc'], 'Monthly Rent') !== false || strpos($l_value['desc'], 'Month Rent Rate') !== false ) {
													// echo $l_value['total'];
													// echo $value_m->ID;
													$y_membership_items[$client_id]['rent']=(float) ($l_value['total']+$y_membership_items[$client_id]['rent']);
													}
													else{

														if (strpos($l_value['desc'], 'Security Deposit') == false ) {
													$y_membership_items[$client_id]['aux']=(float) ($y_membership_items[$client_id]['aux']+$l_value['total']);
												}

													}

									}
									else{

													if (strpos($l_value['desc'], 'Monthly Rent for') !== false || strpos($l_value['desc'], 'Month Rent Rate') !== false  ) {
														// echo $l_value['total'];
														// echo $value_m->ID;
													$y_membership_items[$client_id]['rent']=$l_value['total'];
													}
													else{
														if (strpos($l_value['desc'], 'Security Deposit') == false ) {
														$y_membership_items[$client_id]['aux']=(float) ($y_membership_items[$client_id]['aux']+$l_value['total']);
														}

													}
									}
								




								}
									// echo "<pre>";
									// print_r($line_items);
									// echo "<pre>";


								}



































								// echo "<pre>";
								// echo "Multisuite";
								// print_r($multisuite_alldata);
								// echo "<pre>";
								# code...
							}

							// echo $multichecker_real_total2;
							// 		echo "<pre>";
							// 		print_r($multisuite_alldata);
							// 		echo "</pre>";
									// exit();

							// echo "<pre>";
							// // echo "Suites Array";
							// print_r($y_membership_items);
							// echo "<pre>";

							// echo "<pre>";
							// print_r($all_data);
							// echo "<pre>";
			/***all data start**/
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
					text-align: center;
					}
					.mk_account_reports	td{
					border: 1px solid #D4D4D4;
					}
					</style>
					<div class="wrap">
					<h2 class="mkbis2"> Rent Roll Reports for <?php echo $class; ?> From <?php echo $_POST['_accounting_start_date'];  ?> To <?php echo $_POST['_accounting_end_date'];?></h2>
					<div class="mk_account_reports">

					<table cellpadding="0" cellspacing="0" border="0" width="100%">
					<thead>
					<tr>
					<th>  Suite Number     </th>
					<th>Tennant Name</th>
					<th>Rent</th>
					<th>Aux Charges</th>

					</tr>
					</thead> 
					<tbody>
				 <?php

$csv_data_array=array();
/*
echo "<pre>";
print_r($all_data);
echo "</pre>";
*/
$array_rent=array();
$i=0;
			foreach ($y_membership_items as $ikkey => $lankk) {

				 $the_total=$the_total+$lankk['rent'];
				 $the_total_rent=$the_total_rent+$lankk['rent'];
				 $the_total_aux=$the_total_aux+$lankk['aux'];
				 $the_total=$the_total+$lankk['aux'];
				 $linketotal=$lankk['rent']+$lankk['aux'];
				 $array_rent[$lankk['invoice_id']]=$lankk['rent'];
				 // $leasd_id =	get_post_meta($invoice_id, '_yl_lease_id',true);
				$company_id=	get_post_meta(get_post_meta($lankk['invoice_id'], '_yl_lease_id',true), '_yl_company_name', true);
				$company_name=get_the_title( $company_id );
				// echo "*********".$lankk['invoice_id']."**************".$linketotal;
				// echo "<br>";
				// echo $the_total;
				// echo "<br>";
									if(!in_array($lankk['invoice_id'], $all_invoice_id_checker))
										{
											array_push($all_invoice_id_checker, $lankk['invoice_id']);
										}
// echo "********".$ikkey."**** ".$lankk['rent']."****er******".$lankk['aux'];echo "<br>";
// echo $the_total;

// echo "<br>";
				# code...
					?>
					<tr>

					<td>Y Membership <?php echo $lankk['invoice_id']; ?></td>
					<td><?php echo $company_name; ?></td>
					<td><a onclick="submitymember(<?php echo $ikkey;  ?>,'<?php echo $start_account; ?>','<?php echo $end_account; ?>',<?php  echo $building; ?>	);" href="javascript:void(0);" title="#">$<?php echo number_format($lankk['rent'],2); ?></a></td>
					<td><a onclick="submitymemberaux(<?php echo $ikkey;  ?>,'<?php echo $start_account; ?>','<?php echo $end_account; ?>',<?php  echo $building; ?>	);" href="javascript:void(0);" title="#"><?php echo '$' . number_format($lankk['aux'],2); ?></a></td>
					<!-- <td></td> -->
					</tr>

					<?php

						$csv_data_array[$i]['Suite Number']='Y Membership'.$ikkey;
							$csv_data_array[$i]['Tennant Name']= mb_convert_encoding($company_name, 'UTF-16LE', 'UTF-8');
							$csv_data_array[$i]['Rent']='$'.number_format($lankk['rent'],2);
							$csv_data_array[$i]['Aux Charges']='$' . number_format($lankk['aux'],2);
						
$i++;

			}
foreach ($weirdymember as $weirdymemberkey => $weirdymembervalue) {
$the_real_total=$the_real_total+$weirdymembervalue['rent'];
	$csv_data_array[$i]['Suite Number']='Y Membership'.$weirdymemberkey;
							$csv_data_array[$i]['Tennant Name']= mb_convert_encoding($weirdymembervalue['company_name'], 'UTF-16LE', 'UTF-8');
							$csv_data_array[$i]['Rent']='$'.number_format($weirdymembervalue['rent'],2);
							$csv_data_array[$i]['Aux Charges']='$' . number_format($weirdymembervalue['aux'],2);
						
	?>
				<tr>

					<td>Y Membership <?php echo $weirdymemberkey; ?></td>
					<td><?php echo $weirdymembervalue['company_name']; ?></td>
					<td><a onclick="submitymember(<?php echo $ikkey;  ?>,'<?php echo $start_account; ?>','<?php echo $end_account; ?>',<?php  echo $building; ?>	);" href="javascript:void(0);" title="#">$<?php echo number_format($weirdymembervalue['rent'],2); ?></a></td>
					<td><a onclick="submitymemberaux(<?php echo $ikkey;  ?>,'<?php echo $start_account; ?>','<?php echo $end_account; ?>',<?php  echo $building; ?>	);" href="javascript:void(0);" title="#"><?php echo '$' . number_format($weirdymembervalue['aux'],2); ?></a></td>
					<!-- <td></td> -->
					</tr>
	<?php
	# code...

$i++;
}
			// weirdymember
// echo "<pre>";
// print_r($all_data);
// echo "</pre>";
			$abc2=0;
			foreach ($all_data as $suite_id => $main) {
               



				$leasd_id =	$main['leases'][0];				
				$invoices_ids= $main['invoices'];
				
				// var_dump($invoices_ids);
				// echo "<br>";
				// echo "lease id =".$leasd_id;
				// echo "<br>";
				// echo "lease id =".$leasd_id;
				// echo "<pre>";
				// print_r($invoices_ids);
				// echo "<pre>";
				// exit();
				 if(!is_array($invoice_ids))
				 {
				 	// echo "empty";
				?>
				<tr>

				<td><?php echo get_the_title( $suite_id ); ?></td>
				<td>0</td>
				<td>0</td>
				<td>0</td>
				<!-- <td></td> -->
				</tr>
				<?php
				 }
				 else
				 {

				 	if(!empty($invoices_ids))
				 	{
				 	$tenament_name="";
				 	$aux_charges=0;
				 	$rent_charges=0;
					$monthlyrent=0;
				 	$suite_name_mk=get_the_title( $suite_id );



				 	foreach ($invoices_ids as $key => $value) {
				 			$tenament_name=$value['company_name'];;

							foreach ($value['line_items'] as $line => $lineitem) {
$the_total_rent=$the_total_rent+$lineitem['total'];

// echo "***".$key."***".$lineitem['desc']."***".$lineitem['total'];

// echo "<br>";re
// echo "<br>";
						$the_total=$the_total+$lineitem['total'];
						// if($lineitem['total']==205)
						// {
						// 	echo "This is 205 "
						// // }
						// 							if($lineitem['total']==205)
						// {
						// 	echo "This is 205 and  ".$key;
						// 	echo "<br>";
						// }
// echo "<br>";
// echo "********".$key."**** ";
// echo "rate".$lineitem['total'];echo "<br>";
// echo  $the_total;

// echo "<br>";

							if (strpos($lineitem['desc'], 'Rent') !== false || strpos($lineitem['desc'], 'prorated moving') !== false ) {
							$rent_charges=(float) ($rent_charges+$lineitem['total']);
							}
							else{
						// 					if($lineitem['total']==205)
						// {
						// 	echo "This is 205 and adding in Aux ".$key;
						// 	echo "<br>";
						// }
								if (strpos($lineitem['desc'], 'Security Deposit') == false ) {
							$aux_charges=(float) ($aux_charges+$lineitem['total']);
						}
							}


							}


				 		# code...
				 	}


				 	// echo $suite_id;
				 	// echo $suite_id;
				 	// echo "<br>";
				 	// if($suite_id==784 || $suite_id==772)
				 	// {
				 	// 	echo "**************************************reached".$suite_id;
				 	// }
				 	
				 if(get_post_meta( $key, '_yl_lease_id', true )!="")
				 {

// echo get_the_title( $suite_id )."Invoice".$key;
// echo "<br>";
				 		 $the_total_rent=$the_total_rent+$rent_charges;
				 $array_rent[$key]=$rent_charges;

				 $the_total_aux=$the_total_aux+$aux_charges;
				 	$csv_data_array[$i]['Suite Number']=get_the_title( $suite_id );
							$csv_data_array[$i]['Tennant Name']= mb_convert_encoding($tenament_name, 'UTF-16LE', 'UTF-8');
							$csv_data_array[$i]['Rent']='$'.number_format($rent_charges,2);
							$csv_data_array[$i]['Aux Charges']='$' . number_format($aux_charges,2);
					?>
					<tr>

					<td><?php echo get_the_title( $suite_id ); ?></td>
					<td><?php echo $tenament_name; ?></td> 
					<td><a onclick="submitResources(<?php echo $suite_id;  ?>,'<?php echo $start_account; ?>','<?php echo $end_account; ?>',<?php  echo $building; ?>	);" href="javascript:void(0);" title="#">$<?php echo number_format($rent_charges,2); ?></a></td>
					<td><a onclick="submitResourcesauxrent(<?php echo $suite_id;  ?>,'<?php echo $start_account; ?>','<?php echo $end_account; ?>',<?php  echo $building; ?>	);" href="javascript:void(0);" title="#"><?php echo '$' . number_format($aux_charges,2); ?></a></td>
					<!-- <td></td> -->
					</tr>

					<?php
				 	

				 }


				
				}
				elseif(!empty($multisuite_alldata_chunk[$suite_id])){
					array_push($mk_cc, $suite_id);
					// echo "This is multisuite invoice".$key;
					// echo "<br>";
					// echo "above multisuite";
				$mm1=$multisuite_alldata_chunk[$suite_id]['rent']+$multisuite_alldata_chunk[$suite_id]['aux'];
							$the_total=$the_total+$mm1;

							$the_total_rent=$the_total_rent+$multisuite_alldata_chunk[$suite_id]['rent'];
				 $the_total_aux=$the_total_aux+$multisuite_alldata_chunk[$suite_id]['aux'];

$abc2=$abc2+$mm1;
	 $yl_ms_args = array(
										        'post_type'   => 'lease',
										        'post_status'   => 'publish',
										        'numberposts'  => -1,
										        'order' => 'ASC',
										        'meta_query' => array(
										          array(
										            'key' => '_yl_suite_number',
										            'value'   => get_the_title( $suite_id ),
										            'compare' => '='
										          )
										        )
										      );

							$lease_results_mk = get_posts($yl_ms_args);
					$lease_id=$lease_results_mk[0]->ID;

				 $array_rent[$multisuite_alldata_chunk[$suite_id]['invoice_id']]=$multisuite_alldata_chunk[$suite_id]['rent'];

					// echo " lease=".$leasd_id =	get_post_meta($invoice_id, '_yl_lease_id',true);
													// echo "===";
													$company_id=	get_post_meta($leasd_id, '_yl_company_name', true);
													$company_name=get_the_title( $company_id );
							// if($suite_id==7831 || $suite_id==3464)
							// {
							// 	echo "<pre>";
							// print_r($lease_results_mk);
							// echo "</pre>";
							// echo "newwwwwwwwwwwwwwww******************";
							// }
					// echo "<br>";
				 
						 	$csv_data_array[$i]['Suite Number']=get_the_title( $suite_id );
							$csv_data_array[$i]['Tennant Name']= $company_name;
							$csv_data_array[$i]['Rent']='$'.number_format($multisuite_alldata_chunk[$suite_id]['rent'],2);
							$csv_data_array[$i]['Aux Charges']='$' . number_format($multisuite_alldata_chunk[$suite_id]['aux'],2) ;
	?>
					<tr>

					<td><?php echo get_the_title( $suite_id ); ?></td>
					<td><?php echo $company_name; ?></td>
					<td><a class="multisuiteinvoicemk" onclick="submitResources_multisuite(<?php echo $suite_id;  ?>,<?php echo $multisuite_alldata_chunk[$suite_id]['invoice_id'];  ?>,'<?php echo $start_account; ?>','<?php echo $end_account; ?>',<?php  echo $building; ?>	);" href="javascript:void(0);" title="#">$<?php echo number_format($multisuite_alldata_chunk[$suite_id]['rent'],2) ; ?></a></td>
					<td><a onclick="submitResourcesauxrent_multisite(<?php echo $mulkey;  ?>,<?php echo $multisuite_alldata_chunk[$suite_id]['invoice_id'];  ?>,'<?php echo $start_account; ?>','<?php echo $end_account; ?>',<?php  echo $building; ?>,'<?php echo $aux_show; ?>');" href="javascript:void(0);" title="#"><?php echo '$' . number_format($multisuite_alldata_chunk[$suite_id]['aux'],2) ; ?></a></td>
					<!-- <td></td> -->
					</tr>
					<?php


				}
				else{
											 	$csv_data_array[$i]['Suite Number']=get_the_title( $suite_id );
							$csv_data_array[$i]['Tennant Name']= mb_convert_encoding($tenament_name, 'UTF-16LE', 'UTF-8');
							$csv_data_array[$i]['Rent']='$0';
							$csv_data_array[$i]['Aux Charges']='$0';

?>
		<tr>

				<td><?php echo get_the_title( $suite_id ); ?> </td>
				<td><?php //echo $tenament_name; ?></td>
				<td>$0</td>
				<td>$0</td>
				<!-- <td>0</td> -->
				<!-- <td></td> -->
				</tr>
<?php

				}





				}
				 // exit();
				 // echo "<pre>";
				 // print_r($invoices_ids);
				 // echo "</pre>";
				 // $key=  														 
				 // $suite_id=	get_post_meta($leasd_id, '_yl_product_id',true);
				// echo "<pre>";
				// print_r($value);
				// echo "</pre>";
				$i++;

			}	

$abc=0;
			foreach ($multisuite_alldata_chunk as $mulkey => $mulvalue) {
				// echo "below multisuite";
					array_push($mk_cc2, $mulkey);

					// echo "<br>";
				$mm=$multisuite_alldata_chunk[$mulkey]['rent']+$multisuite_alldata_chunk[$mulkey]['aux'];
				// echo "*********".$multisuite_alldata_chunk[$mulkey]['invoice_id']."**************".$mm;
// echo "<br>";
// echo								$the_total=$the_total+$mm;
$abc=$abc+$mm;
					?>
<!-- 					<tr>

					<td><?php echo get_the_title( $mulkey ); ?></td>
			
					<td><a class="multisuiteinvoicemk" onclick="submitResources_multisuite(<?php echo $mulkey;  ?>,<?php echo $multisuite_alldata_chunk[$mulkey]['invoices_id'];  ?>,'<?php echo $start_account; ?>','<?php echo $end_account; ?>',<?php  echo $building; ?>	);" href="javascript:void(0);" title="#">$<?php echo $multisuite_alldata_chunk[$mulkey]['rent']; ?></a></td>
					<td><a onclick="submitResourcesauxrent_multisite(<?php echo $mulkey;  ?>,<?php echo $multisuite_alldata_chunk[$mulkey]['invoice_id'];  ?>,'<?php echo $start_account; ?>','<?php echo $end_account; ?>',<?php  echo $building; ?>,'<?php echo $aux_show; ?>');" href="javascript:void(0);" title="#"><?php echo '$' . $multisuite_alldata_chunk[$mulkey]['aux']; ?></a></td>
			
					</tr>  -->
					<?php
			}

			// echo "Multichekerrrrrrrrr".$abc;
			// echo "Multichekerrrrrrrrr222".$abc2;
			
// echo "<pre>";

// echo "this is total of multisuite".$temp_total_multi;
//            print_r($multisuite_alldata);  

// echo "</pre>";

// echo "<br>";echo "<br>";echo "<br>";echo "<br>";echo "<br>";

         
// echo "<pre>";
// print_r($y_membership_items);

				 	// echo "<pre>";
				 	// print_r( $multisuite_alldata);
				 	// echo "<pre>";


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


				</tbody>
				</table>
				</div>
				</div>

				<?php


// echo "<pre>";
// print_r($array_rent);
// echo "<pre>";
// 				echo "Mk real total is ".$the_real_total;
// 				echo "<br>";
// echo "Unrealmultichechker ".$multichecker_real_total2;
// 				echo "<br>";
// echo "Multichechker ".$multichecker_real_total;
				
// 				echo "<pre>";
// print_r($all_invoice_id_checker);
// echo "</pre>";

// 				echo "<pre>";
// print_r($all_invoice_id_checker22);
// echo "</pre>";
// $result13 = array_diff($all_invoice_id_checker22,$all_invoice_id_checker);
// echo "Differenece";
// 				echo "<pre>";

// print_r($result13);

// echo "<pre>";
// print_r($mk_cc);
// echo "</pre>";
// echo "<pre>";
// print_r($mk_cc2);
// echo "</pre>";


// $resultxx = array_diff($all_invoice_id_checker22,$all_invoice_id_checker);
// print_r($resultxx);
echo "Total Amount is ".$the_total;
echo "<br>";
echo "Total Rent is ".$the_total_rent;
echo "<br>";
echo "Total Aux is ".$the_total_aux;
// 				echo "</pre>";
			restore_current_blog();

			

			}
?>
<!-- <input type="submit" name="Run" value="Run">		 -->

<?php
if(isset($_REQUEST['Run']))
{

			reporst_csv("Rent Roll list.csv",$csv_data_array,"rental_report");
}




			restore_current_blog();