<?php
$building=$_POST['_accounting_building_id'];
if($building=="all")
{
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
			// $csv_data_array=array();



			
			$sites = wp_get_sites();


			$all_blogs_id=array();
			$removed_ids=array(1,20,19,6);
			$i=0;

			foreach ($sites as $key => $current_blog) {

				if(!in_array($current_blog['blog_id'], $removed_ids))
				{
				array_push($all_blogs_id, $current_blog['blog_id']);
				}
			}
				$csv_data_array=array();
?>

<div class="wrap">
				<h2 class="mkbis2">Income journal entry</h2>

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
									<th>Account</th>
									<th>Debit</th>
									<th>Credit</th>
									<th>Memo</th>
									<th>Name</th>
									<th>Billable</th>
									<th>Class</th>

								</tr>
							</thead> 
							<tbody>
				<?php
foreach ($all_blogs_id as $crid) {



// switch ($crid) {
// 	case '4':
// 	$class="DEV";
// 	break;
// 	case '9':
// 	$class="MCK";
// 	break;
// 	case '10':
// 	$class="FR";
// 	break;
// 	case '11':
// 	$class="FHRA";
// 	break;	
// 	case '12':
// 	$class="C1";
// 	break;
// 	case '13':
// 	$class="F1";
// 	break;
// 	case '14':
// 	$class="F2";
// 	break;
// 	case '15':
// 	$class="GW";
// 	break;
// 	case '16':
// 	$class="N1";
// 	break;
// 	case '17':
// 	$class="N2";
// 	break;
// 	case '18':
// 	$class="OSW";
// 	break;										
// 	default:
// 	$class="DEV";
// 		break;
// }

				?>
				
				<?php


					$bank="Busey Bank";
					switch ($crid) {
					case '4':
					$class="DEV";
					break;
					case '9':
					$class="MCK";
					$bank="Texas Capital";

					break;
					case '10':
					$class="FR";
					$bank="Texas Capital Bank";

					break;
					case '11':
					$class="FHRA";
					$bank="Star Bank";
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
										case '24':
					$bank="Texas Capital Bank";
					break;											
					default:
					$class="DEV";
						break;
				}


				$building=$crid;


			
			$sites = wp_get_sites();

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
			$the_total=0;
			// $args = array(
			// 	'post_type' => 'sa_invoice',
			// 	'post_status' => array('publish,partial,complete,write-off'),
			// 		'date_query' => array(
			// 			array(
			// 				'column' => 'post_modified',
			// 				'after'     => array(
			// 					'year'  => $str_yr,
			// 					'month' => $str_mn,
			// 					'day'   => $str_dy,
			// 				),
			// 				'before'    => array(
			// 					'year'  => $end_yr,
			// 					'month' => $end_mn,
			// 					'day'   => $end_dy,
			// 				),
			// 				'inclusive' => true,
			// 			),
			// 		),
			// 	'posts_per_page' => -1,
			// 	);

														// 				$args = array(
														//     'post_type' => 'sa_invoice',
														//      'posts_per_page' => -1,
														//     'post_status' =>  array('complete','publish','partial'),
														//      // 'orderby' => 'meta_value',
														//  	'order' => 'ASC',
														//     'meta_query' => array(
														// 				'relation' => 'AND',
														// 		    	    array(
														//     	    			    'key' => '_due_date',
														// 				            'value' => $start_account,
														// 			                'compare' => '>=',
														// 						    'type'    => 'Date'
														// 			  			 ),
														// 		    	    	array(
														//     	    			    'key' => '_due_date',
														// 				            'value' => $end_account,
														// 			                'compare' => '<=',
														// 						    'type'    => 'Date'
														// 			  			 ),
												
														// 					 )
														// );


// 																			          		$args = array(
// 						    'post_type' => 'sa_invoice',
// 						    'posts_per_page' => -1,
// //						    'post_status' =>  array('draft', 'publish'),
// 						    'post_status' => "any",
// 						    'orderby' => 'meta_value',
// 						    'order' => 'ASC',
// 						    'meta_query' => array(


// 						 				'relation' => 'AND',
// 						    	    array(

// 									            'key' => '_due_date',
// 									            'value' => "2016-05-31",
// 									            'compare' => '>=',
// 									            'type'    => 'Date'
// 									            ),


// 						    	     	 array(
// 						     				    'key' => '_due_date',
// 									            'value' => "2016-06-30",
// 									            'compare' => '<=',
// 									            'type'    => 'Date'

// 						    	    	),
// 						    	     	 ),
						    	     	     	 
								       
// 						    // )
// 								);	
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
														// $invoice_id=array();
							// 							$loop = new WP_Query($args);

							// while ( $loop->have_posts() ) : $loop->the_post();
							// 							// echo "checkpoint3";
							// 			the_title();


														// endwhile;
				$results = get_posts($args);
/*echo "<pre>";
print_r($results);
echo "</pre>";*/

             $k=0;
			foreach ($results as $key => $result) {
			$paymentdatefull=explode(" ", $result->post_modified);		
			$paymentdate=$paymentdatefull[0];	
			$invoice_id=$result->ID;
			$all_data[$invoice_id]['date']=$paymentdate;
			$all_data[$invoice_id]['line_items']=maybe_unserialize(get_post_meta($invoice_id, '_doc_line_items', true ));
			$total=get_post_meta($invoice_id, '_total', true );
			$credit_fees==get_post_meta($invoice_id, '_doc_tax2', true );
			if($credit_fees==""){ $credit_fees=0; }
			$total_credit_fee=($total*$credit_fees)/100;
			$full_total=(float) ($full_total+$total);


			$k++;
			}
// echo "Total Run".$k.
			$final_data_table=array();
			$final_data_table['total']="";
			$final_data_table['all_dates']=array();
			$final_data_table['all_dates']['Bad Debt']=0;
			$final_data_table['all_dates']['Phone']=0;
			$final_data_table['all_dates']['Tenant Improvement']=0;
			$final_data_table['all_dates']['Copies']=0;
			$final_data_table['all_dates']['Fees']=$total_credit_fee;
			$final_data_table['all_dates']['Discounts']=0;
			$final_data_table['all_dates']['Phone']=0;
			$final_data_table['all_dates']['Fax']=0;
			$final_data_table['all_dates']['Fees']=0;
			$final_data_table['all_dates']['Phone']=0;
			// $final_data_table['all_dates']['Tenant Improvement']=0;
			$final_data_table['all_dates']['Long Distance']=0;
			$final_data_table['all_dates']['Postage']=0;
			$final_data_table['all_dates']['Rent']=0;
			$final_data_table['all_dates']['Sec. Deposit']=0;
			$final_data_table['all_dates'][$bank]=0;
			$final_data_table['all_dates']['Utilities']=0;
			// $final_data_table['all_dates']['Bank']=$bank;
			$mk_count=1;

			foreach ($all_data as $key => $value) {
				$mainurl=home_url();
					
// http://mckinney.yeagercommunity.com/wp-admin/post.php?post=6853&action=edit

				$invediturl=$mainurl."/wp-admin/post.php?post=".$key."&action=edit";
				// echo "<br>";
				// echo "<a target='_blank' href='".$invediturl."'>Invoice id ".$key."</a>";
				// echo "<br>";
				$final_total2=0;
				$final_total3=0;

					foreach ($value['line_items'] as  $lineitem) {
							$the_total=$the_total+$lineitem['total'];
				// $the_total=$the_total+$lankk['aux'];
// echo "********".$key."**** ".$lineitem['total'];echo "<br>";
// echo $the_total;

// echo "<br>";
				//	echo $lineitem['desc']."===".$lineitem['total'];
					//echo "<br>";
					if (strpos($lineitem['desc'], 'Discount') !== false) {
					$final_data_table['total']=$final_data_table['total']+$lineitem['total'];
					$final_total2=$final_total2+$lineitem['total'];
					// $final_total3=$final_total3-$lineitem['total'];
					}
					else{
					$final_data_table['total']=$final_data_table['total']+$lineitem['total'];
					$final_total2=$final_total2+$lineitem['total'];
					// $final_total3=$final_total3+$lineitem['total'];

					}

					if (strpos($lineitem['desc'], 'IP Static') !== false) {
					$final_data_table['all_dates']['Phone']=$final_data_table['all_dates']['Phone']+$lineitem['total'];
					$final_total3=$final_total3+$lineitem['total'];

					}

					elseif (strpos($lineitem['desc'], 'Bad Debt') !== false) {
					$final_data_table['all_dates']['Bad Debt']=$final_data_table['all_dates']['Bad Debt']+$lineitem['total'];
					$final_total3=$final_total3+$lineitem['total'];

					}
					elseif (strpos($lineitem['desc'], 'Copier') !== false) {
					$final_data_table['all_dates']['Copies']=$final_data_table['all_dates']['Copies']+$lineitem['total'];
					$final_total3=$final_total3+$lineitem['total'];

					}
										elseif (strpos($lineitem['desc'], 'Copies') !== false) {
					$final_data_table['all_dates']['Copies']=$final_data_table['all_dates']['Copies']+$lineitem['total'];
					$final_total3=$final_total3+$lineitem['total'];

					}

					elseif (strpos($lineitem['desc'], 'Keys') !== false) {
					$final_data_table['all_dates']['Tenant Improvement']=$final_data_table['all_dates']['Tenant Improvement']+$lineitem['total'];
					$final_total3=$final_total3+$lineitem['total'];
					
					}
					elseif (strpos($lineitem['desc'], 'Key') !== false && strpos($lineitem['desc'], 'Invoice for') == false ) {
					$final_data_table['all_dates']['Tenant Improvement']=$final_data_table['all_dates']['Tenant Improvement']+$lineitem['total'];
					$final_total3=$final_total3+$lineitem['total'];
					
					}
					elseif (strpos($lineitem['desc'], 'Late Fee') !== false) {
					$final_data_table['all_dates']['Fees']=$final_data_table['all_dates']['Fees']+$lineitem['total'];
					$final_total3=$final_total3+$lineitem['total'];
					
					} 

					elseif (strpos($lineitem['desc'], 'Ancillaries') !== false) {
					$final_data_table['all_dates']['Fees']=$final_data_table['all_dates']['Fees']+$lineitem['total'];
					$final_total3=$final_total3+$lineitem['total'];
					
					} 


					elseif (strpos($lineitem['desc'], 'Cleaning Service Fee') !== false) {
					$final_data_table['all_dates']['Fees']=$final_data_table['all_dates']['Fees']+$lineitem['total'];
					$final_total3=$final_total3+$lineitem['total'];
					
					} 




					elseif (strpos($lineitem['desc'], 'Long Distance') !== false) {
					$final_data_table['all_dates']['Long Distance']=$final_data_table['all_dates']['Long Distance']+$lineitem['total'];
					$final_total3=$final_total3+$lineitem['total'];
					
					}
					//  elseif (strpos($lineitem['desc'], 'Phone Line') !== false) {
					// $final_data_table['all_dates']['Phone']=$final_data_table['all_dates']['Phone']+$lineitem['total'];
					// $final_total3=$final_total3+$lineitem['total'];
					
					// }

					 elseif (strpos($lineitem['desc'], 'Phone') !== false) {
					$final_data_table['all_dates']['Phone']=$final_data_table['all_dates']['Phone']+$lineitem['total'];
					$final_total3=$final_total3+$lineitem['total'];
					
					}


					//  elseif (strpos($lineitem['desc'], 'IP Service Fee') !== false) {
					// $final_data_table['all_dates']['Phone']=$final_data_table['all_dates']['Phone']+$lineitem['total'];
					// $final_total3=$final_total3+$lineitem['total'];
					
					// }

								 elseif (strpos($lineitem['desc'], 'Ip Service') !== false) {
					$final_data_table['all_dates']['Phone']=$final_data_table['all_dates']['Phone']+$lineitem['total'];
					$final_total3=$final_total3+$lineitem['total'];
					
					}


								 elseif (strpos($lineitem['desc'], 'Cable') !== false) {
					$final_data_table['all_dates']['Phone']=$final_data_table['all_dates']['Phone']+$lineitem['total'];
					$final_total3=$final_total3+$lineitem['total'];
					
					}


								 elseif (strpos($lineitem['desc'], 'Credit Card Line') !== false) {
					$final_data_table['all_dates']['Phone']=$final_data_table['all_dates']['Phone']+$lineitem['total'];
					$final_total3=$final_total3+$lineitem['total'];
					
					}






					 elseif (strpos($lineitem['desc'], 'Postage') !== false) {
					$final_data_table['all_dates']['Postage']=$final_data_table['all_dates']['Postage']+$lineitem['total'];	
					$final_total3=$final_total3+$lineitem['total'];
					
					}


				 //    elseif (strpos($lineitem['desc'], 'IP Service Fees') !== false) {
					// $final_data_table['all_dates']['Phone']=$final_data_table['all_dates']['Phone']+$lineitem['total'];	
					// $final_total3=$final_total3+$lineitem['total'];
					
					// }


				    elseif (strpos($lineitem['desc'], 'Service Fees') !== false) {
					$final_data_table['all_dates']['Utilities']=$final_data_table['all_dates']['Utilities']+$lineitem['total'];	
					$final_total3=$final_total3+$lineitem['total'];
					
					}


									    elseif (strpos($lineitem['desc'], 'Utility Service') !== false) {
					$final_data_table['all_dates']['Utilities']=$final_data_table['all_dates']['Utilities']+$lineitem['total'];	
					$final_total3=$final_total3+$lineitem['total'];
					
					}

					
				    elseif (strpos($lineitem['desc'], 'Fax Fees') !== false) {
					$final_data_table['all_dates']['Fax']=$final_data_table['all_dates']['Fax']+$lineitem['total'];	
					$final_total3=$final_total3+$lineitem['total'];
					
					}
					 elseif (strpos($lineitem['desc'], 'Fax Service') !== false) {
					$final_data_table['all_dates']['Phone']=$final_data_table['all_dates']['Phone']+$lineitem['total'];	
					$final_total3=$final_total3+$lineitem['total'];
					
					}
					
					 // elseif (strpos($lineitem['desc'], 'Suite Discount') !== false) {
					elseif (strpos($lineitem['desc'], 'Discount') !== false) {

					$final_data_table['all_dates']['Discounts']=$final_data_table['all_dates']['Discounts']+$lineitem['total'];
					$final_total3=$final_total3+$lineitem['total'];
					
					}


					elseif (strpos($lineitem['desc'], 'Refreshing Fee') !== false) {
					$final_data_table['all_dates']['Tenant Improvement']=$final_data_table['all_dates']['Tenant Improvement']+$lineitem['total'];		
					$final_total3=$final_total3+$lineitem['total'];
					
					}

					 elseif (strpos($lineitem['desc'], 'Rent') !== false) {
					$final_data_table['all_dates']['Rent']=$final_data_table['all_dates']['Rent']+$lineitem['total'];	
					$final_total3=$final_total3+$lineitem['total'];
					
					}



					 elseif (strpos($lineitem['desc'], 'prorated moving') !== false) {
					$final_data_table['all_dates']['Rent']=$final_data_table['all_dates']['Rent']+$lineitem['total'];	
					$final_total3=$final_total3+$lineitem['total'];
					
					}




					elseif (strpos($lineitem['desc'], 'Security Deposit') !== false) {
					$final_data_table['all_dates']['Sec. Deposit']=$final_data_table['all_dates']['Sec. Deposit']+$lineitem['total'];	
					$final_total3=$final_total3+$lineitem['total'];
					
					}

					elseif (strpos($lineitem['desc'], 'Tenant Improvements') !== false) {
					$final_data_table['all_dates']['Tenant Improvement']=$final_data_table['all_dates']['Tenant Improvement']+$lineitem['total'];	
					$final_total3=$final_total3+$lineitem['total'];
					
					}


					// 					elseif (strpos($lineitem['desc'], 'Overpayment from') !== false) {
					// $final_data_table['all_dates']['Tenant Improvement']=$final_data_table['all_dates']['Tenant Improvement']+$lineitem['total'];	
					// $final_total3=$final_total3+$lineitem['total'];
					
					// }

					elseif (strpos($lineitem['desc'], 'Utilities') !== false) {
					$final_data_table['all_dates']['Utilities']=$final_data_table['all_dates']['Utilities']+$lineitem['total'];	
					$final_total3=$final_total3+$lineitem['total'];
					
					}

					elseif (strpos($lineitem['desc'], 'Water Utliity Fee') !== false) {
					$final_data_table['all_dates']['Utilities']=$final_data_table['all_dates']['Utilities']+$lineitem['total'];	
					$final_total3=$final_total3+$lineitem['total'];
					
					}

					// 					elseif (strpos($lineitem['desc'], 'Overpayment from') !== false) {
					// $final_data_table['all_dates']['Utilities']=$final_data_table['all_dates']['Utilities']+$lineitem['total'];	
					// $final_total3=$final_total3+$lineitem['total'];
					
					// }


					 

					elseif (strpos($lineitem['desc'], 'Work Orders') !== false) {
					$final_data_table['all_dates']['Work Orders']=$final_data_table['all_dates']['Work Orders']+$lineitem['total'];
					$final_total3=$final_total3+$lineitem['total'];
					
					}

					elseif (strpos($lineitem['desc'], 'NSF Fee') !== false) {
					$final_data_table['all_dates']['Fees']=$final_data_table['all_dates']['Fees']+$lineitem['total'];		
					$final_total3=$final_total3+$lineitem['total'];
					
					}
					elseif (strpos($lineitem['desc'], 'Fees') !== false) {
					$final_data_table['all_dates']['Fees']=$final_data_table['all_dates']['Fees']+$lineitem['total'];		
					$final_total3=$final_total3+$lineitem['total'];
					
					}

					elseif (strpos($lineitem['desc'], 'Convenience Fee') !== false) {
					$final_data_table['all_dates']['Fees']=$final_data_table['all_dates']['Fees']+$lineitem['total'];		
					$final_total3=$final_total3+$lineitem['total'];
					
					}


					elseif (strpos($lineitem['desc'], 'April Payment Convienence Fee') !== false) {
					$final_data_table['all_dates']['Fees']=$final_data_table['all_dates']['Fees']+$lineitem['total'];		
					$final_total3=$final_total3+$lineitem['total'];
					
					}


					elseif (strpos($lineitem['desc'], 'Workorder') !== false) {
					$final_data_table['all_dates']['Tenant Improvement']=$final_data_table['all_dates']['Tenant Improvement']+$lineitem['total'];		
					$final_total3=$final_total3+$lineitem['total'];
					
					}

					elseif (strpos($lineitem['desc'], 'Work Order') !== false) {
					$final_data_table['all_dates']['Tenant Improvement']=$final_data_table['all_dates']['Tenant Improvement']+$lineitem['total'];		
					$final_total3=$final_total3+$lineitem['total'];
					
					}

					elseif (strpos($lineitem['desc'], 'Rebill') !== false) {
					$final_data_table['all_dates'][$bank]=$final_data_table['all_dates'][$bank]+$lineitem['total'];		
					$final_total3=$final_total3+$lineitem['total'];
					
					}
					// $final_data_table['all_dates']['Rebill']=0;

					elseif (strpos($lineitem['desc'], 'Plumbing Work Order') !== false) {
					$final_data_table['all_dates']['Tenant Improvement']=$final_data_table['all_dates']['Tenant Improvement']+$lineitem['total'];		
					$final_total3=$final_total3+$lineitem['total'];
					
					}

					


					# code...
				}

				// echo "<br>";
				// echo "Line total ALL items =".$final_total2;
				// echo "<br>";
				// echo "Line total our items =".$final_total3;
				// if($final_total2!=$final_total3)
				// {
				// 	echo "Error is here !";
				// }
				// echo "<pre>";
				// print_r($final_data_table['all_dates']);

				// echo "<br>";
				// echo "Final Total = ".$final_data_table['total'];

				// echo "</pre>";
				// # code...
				// 			echo "***********************************************************************************";
							$mk_count++;
							}


							// echo "Total invoice looped".$mk_count;
							?>

							<?php
							// $i=0;
							foreach ($final_data_table['all_dates'] as $key => $value) {
								// echo $i;
								if($value!=0)
								{
								?>
							<tr>
							<td><?php echo $key;?></td>
							<td>  </td>


							   <td><a onclick="submitResources_phone('<?php echo $start_account; ?>','<?php echo $end_account; ?>',<?php  echo $building; ?>,'<?php echo $key; ?>');" href="javascript:void(0);" title="#">$<?php echo $value; ?></a></td>
							<td>Week of <?php echo $end_account; ?></td>
							<td> </td>
							<td> </td>
							<td><?php echo $class; ?> </td>
							<!-- <td><?php echo $bank; ?></td> -->
							
							</tr>
								<?php
										$csv_data_array[$i]['Account']=$key;
							$csv_data_array[$i]['Debit']=" ";
							$csv_data_array[$i]['Credit']='$' . number_format($value,2);
							$csv_data_array[$i]['Memo']="Week of ". $end_account;
							$csv_data_array[$i]['Name']=" ";
							$csv_data_array[$i]['Billable']=" ";
							$csv_data_array[$i]['Class']=$class;
							// $csv_data_array[$i]['bank']=$bank;
							// $csv_data_array[$i]['Rebill']=$value;
								
							}
							$i++;
					
							}


							?>

							
							<!-- // reporst_csv("Income journal list.csv",$csv_data_array,"rental_report");	 -->
							<?php
							restore_current_blog();

					














			
}



?>
							<tr>
							<td>Accounts Receivable</td>
							<td><?php echo $final_data_table['total']; ?></td>
							<td></td>
							<td> </td>
							<td> </td>
							<td> </td>
							<td> </td>
							
							</tr>
							<?php
							$csv_data_array[$i]['Account']="Accounts Receivable";
							$csv_data_array[$i]['Debit']=$final_data_table['total'];
							$csv_data_array[$i]['Credit']="";
							$csv_data_array[$i]['Memo']="";
							$csv_data_array[$i]['Name']=" ";
							$csv_data_array[$i]['Billable']=" ";
							$csv_data_array[$i]['Class']=$class;



			
// }

if (isset($_REQUEST['Run'])) {

			reporst_csv("Income journal list.csv",$csv_data_array,"rental_report");
	# code...
}







}
else{





$bank="Busey Bank";
					switch ($_POST['_accounting_building_id']) {
					case '4':
					$class="DEV";
					break;
					case '9':
					$class="MCK";
					$bank="Texas Capital";

					break;
					case '10':
					$class="FR";
					$bank="Texas Capital Bank";

					break;
					case '11':
					$class="FHRA";
					$bank="Star Bank";
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
										case '24':
					$bank="Texas Capital Bank";
					break;										
					default:
					$class="DEV";
						break;
				}

				?>
				<form action="" method="post" >
	
		<input type="hidden" name="_accounting_report_id" value="<?php echo $_REQUEST['_accounting_report_id']; ?>">	
		<input type="hidden" name="_accounting_building_id" value="<?php echo $_REQUEST['_accounting_building_id']; ?>">	
		<input type="hidden" name="_accounting_start_date" value="<?php echo $_REQUEST['_accounting_start_date']; ?>">	
		<input type="hidden" name="_accounting_end_date" value="<?php echo $_REQUEST['_accounting_end_date']; ?>">	
		<input type="hidden" name="accountingmk_submit" value="">	

	<input type="submit" name="Run" value="Download"/>
</form>
				<div class="wrap">
				<h2 class="mkbis2">Income journal Reports for <?php echo $class; ?> From <?php echo $_POST['_accounting_start_date'];  ?> To <?php echo $_POST['_accounting_end_date'];?></h2>
				<?php
				$building=$_POST['_accounting_building_id'];


			
			$sites = wp_get_sites();

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
			$the_total=0;
			// $args = array(
			// 	'post_type' => 'sa_invoice',
			// 	'post_status' => array('publish,partial,complete,write-off'),
			// 		'date_query' => array(
			// 			array(
			// 				'column' => 'post_modified',
			// 				'after'     => array(
			// 					'year'  => $str_yr,
			// 					'month' => $str_mn,
			// 					'day'   => $str_dy,
			// 				),
			// 				'before'    => array(
			// 					'year'  => $end_yr,
			// 					'month' => $end_mn,
			// 					'day'   => $end_dy,
			// 				),
			// 				'inclusive' => true,
			// 			),
			// 		),
			// 	'posts_per_page' => -1,
			// 	);

														// 				$args = array(
														//     'post_type' => 'sa_invoice',
														//      'posts_per_page' => -1,
														//     'post_status' =>  array('complete','publish','partial'),
														//      // 'orderby' => 'meta_value',
														//  	'order' => 'ASC',
														//     'meta_query' => array(
														// 				'relation' => 'AND',
														// 		    	    array(
														//     	    			    'key' => '_due_date',
														// 				            'value' => $start_account,
														// 			                'compare' => '>=',
														// 						    'type'    => 'Date'
														// 			  			 ),
														// 		    	    	array(
														//     	    			    'key' => '_due_date',
														// 				            'value' => $end_account,
														// 			                'compare' => '<=',
														// 						    'type'    => 'Date'
														// 			  			 ),
												
														// 					 )
														// );


// 																			          		$args = array(
// 						    'post_type' => 'sa_invoice',
// 						    'posts_per_page' => -1,
// //						    'post_status' =>  array('draft', 'publish'),
// 						    'post_status' => "any",
// 						    'orderby' => 'meta_value',
// 						    'order' => 'ASC',
// 						    'meta_query' => array(


// 						 				'relation' => 'AND',
// 						    	    array(

// 									            'key' => '_due_date',
// 									            'value' => "2016-05-31",
// 									            'compare' => '>=',
// 									            'type'    => 'Date'
// 									            ),


// 						    	     	 array(
// 						     				    'key' => '_due_date',
// 									            'value' => "2016-06-30",
// 									            'compare' => '<=',
// 									            'type'    => 'Date'

// 						    	    	),
// 						    	     	 ),
						    	     	     	 
								       
// 						    // )
// 								);	
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
														// $invoice_id=array();
							// 							$loop = new WP_Query($args);

							// while ( $loop->have_posts() ) : $loop->the_post();
							// 							// echo "checkpoint3";
							// 			the_title();


														// endwhile;
				$results = get_posts($args);
/*echo "<pre>";
print_r($results);
echo "</pre>";*/

             $k=0;
			foreach ($results as $key => $result) {
			$paymentdatefull=explode(" ", $result->post_modified);		
			$paymentdate=$paymentdatefull[0];	
			$invoice_id=$result->ID;
			$all_data[$invoice_id]['date']=$paymentdate;
			$all_data[$invoice_id]['line_items']=maybe_unserialize(get_post_meta($invoice_id, '_doc_line_items', true ));
			$total=get_post_meta($invoice_id, '_total', true );
			$credit_fees==get_post_meta($invoice_id, '_doc_tax2', true );
			if($credit_fees==""){ $credit_fees=0; }
			$total_credit_fee=($total*$credit_fees)/100;
			$full_total=(float) ($full_total+$total);


			$k++;
			}
// echo "Total Run".$k.
			$final_data_table=array();
			$rebill=array();

			$final_data_table['total']="";
			$final_data_table['all_dates']=array();
			$final_data_table['all_dates']['Bad Debt']=0;
			$final_data_table['all_dates']['Phone']=0;
			$final_data_table['all_dates']['Tenant Improvement']=0;
			$final_data_table['all_dates']['Copies']=0;
			$final_data_table['all_dates']['Fees']=$total_credit_fee;
			$final_data_table['all_dates']['Discounts']=0;
			$final_data_table['all_dates']['Phone']=0;
			$final_data_table['all_dates']['Fax']=0;
			$final_data_table['all_dates']['Fees']=0;
			$final_data_table['all_dates']['Phone']=0;
			// $final_data_table['all_dates']['Tenant Improvement']=0;
			$final_data_table['all_dates']['Long Distance']=0;
			$final_data_table['all_dates']['Postage']=0;
			$final_data_table['all_dates']['Rent']=0;
			$final_data_table['all_dates']['Sec. Deposit']=0;
			$final_data_table['all_dates'][$bank]=0;
			$final_data_table['all_dates']['Utilities']=0;
			// $final_data_table['all_dates']['Bank']=$bank;
			$mk_count=1;

			$array_rent=array();

$the_total_aux=0;
			foreach ($all_data as $key => $value) {
				$mainurl=home_url();
					
// http://mckinney.yeagercommunity.com/wp-admin/post.php?post=6853&action=edit

				$invediturl=$mainurl."/wp-admin/post.php?post=".$key."&action=edit";
				// echo "<br>";
				// echo "<a target='_blank' href='".$invediturl."'>Invoice id ".$key."</a>";
				// echo "<br>";
				$final_total2=0;
				$final_total3=0;
					foreach ($value['line_items'] as  $lineitem) {

						if(!empty($lineitem))
						{
						// echo "<pre>";
						// var_dump($lineitem);
						// echo "</pre>";
							$the_total=$the_total+$lineitem['total'];
							$the_total=$the_total+$lankk['aux'];
							// echo "<br>";
							// echo $lineitem['desc']."******* ".$lineitem['total'];
							// echo "<br>";
// echo $the_total;

		 if (strpos($lineitem['desc'], 'Monthly Rent') !== false || strpos($lineitem['desc'], 'prorated moving') !== false || strpos($lineitem['desc'], 'Month Rent') !== false || strpos($lineitem['desc'], 'Recurrent Rent') !== false) {
					 	$array_rent[$key]=$array_rent[$key]+$lineitem['total'];

							// echo "<br>";
						$final_data_table['all_dates']['Rent']=$final_data_table['all_dates']['Rent']+$lineitem['total'];	
						$final_total3=$final_total3+$lineitem['total'];

						}
						else{
							if(strpos($lineitem['desc'], 'Standard Credit') == false && strpos($lineitem['desc'], 'Invoice for') == false)
							{
							$the_total_aux=$the_total_aux+$lineitem['total'];
							}
						}





// echo "<br>";
				//	echo $lineitem['desc']."===".$lineitem['total'];
					//echo "<br>";
// if($key==1587)
// {

// 	if(strpos($lineitem['desc'], 'Invoice for') == false)
// 						{
// 							echo "Still issue is there!";
// 							var_dump($lineitem['desc']);
// 						}
// 					var_dump(strip_tags($lineitem['desc']));
// }


					if(strpos($lineitem['desc'], 'Invoice for') == false && strpos($lineitem['desc'], 'Standard Credit')== false )
						{
					if (strpos($lineitem['desc'], 'Discount') !== false ) {
					$final_data_table['total']=$final_data_table['total']+$lineitem['total'];
					$final_total2=$final_total2+$lineitem['total'];
					// $final_total3=$final_total3-$lineitem['total'];
					}
					else{
						if(strpos($lineitem['desc'], 'Standard Credit') == false && strpos($lineitem['desc'], 'Invoice for') == false)
						{
		$final_data_table['total']=$final_data_table['total']+$lineitem['total'];
					$final_total2=$final_total2+$lineitem['total'];
						}
			
					// $final_total3=$final_total3+$lineitem['total'];

					}

					if (strpos($lineitem['desc'], 'IP Static') !== false) {
					$final_data_table['all_dates']['Phone']=$final_data_table['all_dates']['Phone']+$lineitem['total'];
					$final_total3=$final_total3+$lineitem['total'];

					}

					elseif (strpos($lineitem['desc'], 'Bad Debt') !== false) {
					$final_data_table['all_dates']['Bad Debt']=$final_data_table['all_dates']['Bad Debt']+$lineitem['total'];
					$final_total3=$final_total3+$lineitem['total'];

					}
					elseif (strpos($lineitem['desc'], 'Copier') !== false) {
					$final_data_table['all_dates']['Copies']=$final_data_table['all_dates']['Copies']+$lineitem['total'];
					$final_total3=$final_total3+$lineitem['total'];

					}
										elseif (strpos($lineitem['desc'], 'Copies') !== false) {
					$final_data_table['all_dates']['Copies']=$final_data_table['all_dates']['Copies']+$lineitem['total'];
					$final_total3=$final_total3+$lineitem['total'];

					}

					elseif (strpos($lineitem['desc'], 'Keys') !== false) {
					$final_data_table['all_dates']['Tenant Improvement']=$final_data_table['all_dates']['Tenant Improvement']+$lineitem['total'];
					$final_total3=$final_total3+$lineitem['total'];
					
					}
					elseif (strpos($lineitem['desc'], 'Key') !== false && strpos($lineitem['desc'], 'Invoice for') == false && strpos($lineitem['desc'], 'Invoice for') != 0) {

					$final_data_table['all_dates']['Tenant Improvement']=$final_data_table['all_dates']['Tenant Improvement']+$lineitem['total'];
					$final_total3=$final_total3+$lineitem['total'];
					
					}
					elseif (strpos($lineitem['desc'], 'Late Fee') !== false) {
					$final_data_table['all_dates']['Fees']=$final_data_table['all_dates']['Fees']+$lineitem['total'];
					$final_total3=$final_total3+$lineitem['total'];
					
					} 

					elseif (strpos($lineitem['desc'], 'Ancillaries') !== false || strpos($lineitem['desc'], 'Convenience fee') !== false) {
					$final_data_table['all_dates']['Fees']=$final_data_table['all_dates']['Fees']+$lineitem['total'];
					$final_total3=$final_total3+$lineitem['total'];
					
					} 


					elseif (strpos($lineitem['desc'], 'Cleaning Service Fee') !== false) {
					$final_data_table['all_dates']['Fees']=$final_data_table['all_dates']['Fees']+$lineitem['total'];
					$final_total3=$final_total3+$lineitem['total'];
					
					} 




					elseif (strpos($lineitem['desc'], 'Long Distance') !== false || strpos($lineitem['desc'], 'Long Distance') !== false) {
					$final_data_table['all_dates']['Long Distance']=$final_data_table['all_dates']['Long Distance']+$lineitem['total'];
					$final_total3=$final_total3+$lineitem['total'];
					
					}
					//  elseif (strpos($lineitem['desc'], 'Phone Line') !== false) {
					// $final_data_table['all_dates']['Phone']=$final_data_table['all_dates']['Phone']+$lineitem['total'];
					// $final_total3=$final_total3+$lineitem['total'];
					
					// }

					 elseif (strpos($lineitem['desc'], 'Phone') !== false) {
					$final_data_table['all_dates']['Phone']=$final_data_table['all_dates']['Phone']+$lineitem['total'];
					$final_total3=$final_total3+$lineitem['total'];
					
					}


					 elseif (strpos($lineitem['desc'], 'IP Service Fee') !== false) {
					$final_data_table['all_dates']['Phone']=$final_data_table['all_dates']['Phone']+$lineitem['total'];
					$final_total3=$final_total3+$lineitem['total'];
					
					}

								 elseif (strpos($lineitem['desc'], 'Ip Service') !== false) {
					$final_data_table['all_dates']['Phone']=$final_data_table['all_dates']['Phone']+$lineitem['total'];
					$final_total3=$final_total3+$lineitem['total'];
					
					}


								 elseif (strpos($lineitem['desc'], 'Cable') !== false) {
					$final_data_table['all_dates']['Phone']=$final_data_table['all_dates']['Phone']+$lineitem['total'];
					$final_total3=$final_total3+$lineitem['total'];
					
					}


								 elseif (strpos($lineitem['desc'], 'Credit Card Line') !== false) {
					$final_data_table['all_dates']['Phone']=$final_data_table['all_dates']['Phone']+$lineitem['total'];
					$final_total3=$final_total3+$lineitem['total'];
					
					}






					 elseif (strpos($lineitem['desc'], 'Postage') !== false) {
					$final_data_table['all_dates']['Postage']=$final_data_table['all_dates']['Postage']+$lineitem['total'];	
					$final_total3=$final_total3+$lineitem['total'];
					
					}


				    elseif (strpos($lineitem['desc'], 'IP Service Fees') !== false) {
					$final_data_table['all_dates']['Phone']=$final_data_table['all_dates']['Phone']+$lineitem['total'];	
					$final_total3=$final_total3+$lineitem['total'];
					
					}


				    elseif (strpos($lineitem['desc'], 'Service Fees') !== false) {
					$final_data_table['all_dates']['Utilities']=$final_data_table['all_dates']['Utilities']+$lineitem['total'];	
					$final_total3=$final_total3+$lineitem['total'];
					
					}


									    elseif (strpos($lineitem['desc'], 'Utility Service') !== false) {
					$final_data_table['all_dates']['Utilities']=$final_data_table['all_dates']['Utilities']+$lineitem['total'];	
					$final_total3=$final_total3+$lineitem['total'];
					
					}

					
				    elseif (strpos($lineitem['desc'], 'Fax Fees') !== false) {
					$final_data_table['all_dates']['Fax']=$final_data_table['all_dates']['Fax']+$lineitem['total'];	
					$final_total3=$final_total3+$lineitem['total'];
					
					}
					 elseif (strpos($lineitem['desc'], 'Fax Service') !== false) {
					$final_data_table['all_dates']['Phone']=$final_data_table['all_dates']['Phone']+$lineitem['total'];	
					$final_total3=$final_total3+$lineitem['total'];
					
					}
					
					 // elseif (strpos($lineitem['desc'], 'Suite Discount') !== false) {
					elseif (strpos($lineitem['desc'], 'Discount') !== false ) {

					$final_data_table['all_dates']['Discounts']=$final_data_table['all_dates']['Discounts']+$lineitem['total'];
					$final_total3=$final_total3+$lineitem['total'];
					
					}


					elseif (strpos($lineitem['desc'], 'Refreshing Fee') !== false) {
					$final_data_table['all_dates']['Tenant Improvement']=$final_data_table['all_dates']['Tenant Improvement']+$lineitem['total'];		
					$final_total3=$final_total3+$lineitem['total'];
					
					}

					//  elseif (strpos($lineitem['desc'], 'Monthly Rent') !== false) {
					//  //	echo "Invoice id= ".$key." rent=".$lineitem['total'];

					//  	$array_rent[$key]=$array_rent[$key]+$lineitem['total'];
					//  //	echo "<br>";
					// $final_data_table['all_dates']['Rent']=$final_data_table['all_dates']['Rent']+$lineitem['total'];	
					// $final_total3=$final_total3+$lineitem['total'];
					
					// }



					//  elseif (strpos($lineitem['desc'], 'prorated moving') !== false) {
					//  	//echo "Invoice id= ".$key." rent=".$lineitem['total'];
					//  	$array_rent[$key]=$array_rent[$key]+$lineitem['total'];

					//  	//echo "<br>";

					// $final_data_table['all_dates']['Rent']=$final_data_table['all_dates']['Rent']+$lineitem['total'];	
					// $final_total3=$final_total3+$lineitem['total'];
					
					// }




					elseif (strpos($lineitem['desc'], 'Security Deposit') !== false) {
					$final_data_table['all_dates']['Sec. Deposit']=$final_data_table['all_dates']['Sec. Deposit']+$lineitem['total'];	
					$final_total3=$final_total3+$lineitem['total'];
					
					}

					elseif (strpos($lineitem['desc'], 'Tenant Improvements') !== false) {
					$final_data_table['all_dates']['Tenant Improvement']=$final_data_table['all_dates']['Tenant Improvement']+$lineitem['total'];	
					$final_total3=$final_total3+$lineitem['total'];
					
					}


					// 					elseif (strpos($lineitem['desc'], 'Overpayment from') !== false) {
					// $final_data_table['all_dates']['Tenant Improvement']=$final_data_table['all_dates']['Tenant Improvement']+$lineitem['total'];	
					// $final_total3=$final_total3+$lineitem['total'];
					
					// }

					elseif (strpos($lineitem['desc'], 'Utilities') !== false) {
					$final_data_table['all_dates']['Utilities']=$final_data_table['all_dates']['Utilities']+$lineitem['total'];	
					$final_total3=$final_total3+$lineitem['total'];
					
					}

					elseif (strpos($lineitem['desc'], 'Water Utliity Fee') !== false) {
					$final_data_table['all_dates']['Utilities']=$final_data_table['all_dates']['Utilities']+$lineitem['total'];	
					$final_total3=$final_total3+$lineitem['total'];
					
					}

					// 					elseif (strpos($lineitem['desc'], 'Overpayment from') !== false) {
					// $final_data_table['all_dates']['Utilities']=$final_data_table['all_dates']['Utilities']+$lineitem['total'];	
					// $final_total3=$final_total3+$lineitem['total'];
					
					// }


					 

					elseif (strpos($lineitem['desc'], 'Work Orders') !== false) {
					$final_data_table['all_dates']['Work Orders']=$final_data_table['all_dates']['Work Orders']+$lineitem['total'];
					$final_total3=$final_total3+$lineitem['total'];
					
					}

					elseif (strpos($lineitem['desc'], 'NSF Fee') !== false) {
					$final_data_table['all_dates']['Fees']=$final_data_table['all_dates']['Fees']+$lineitem['total'];		
					$final_total3=$final_total3+$lineitem['total'];
					
					}
					elseif (strpos($lineitem['desc'], 'Fees') !== false) {
					$final_data_table['all_dates']['Fees']=$final_data_table['all_dates']['Fees']+$lineitem['total'];		
					$final_total3=$final_total3+$lineitem['total'];
					
					}

					elseif (strpos($lineitem['desc'], 'Convenience Fee') !== false || strpos($lineitem['desc'], 'Hold Fee') !== false) {
					$final_data_table['all_dates']['Fees']=$final_data_table['all_dates']['Fees']+$lineitem['total'];		
					$final_total3=$final_total3+$lineitem['total'];
					
					}


					elseif (strpos($lineitem['desc'], 'April Payment Convienence Fee') !== false) {
					$final_data_table['all_dates']['Fees']=$final_data_table['all_dates']['Fees']+$lineitem['total'];		
					$final_total3=$final_total3+$lineitem['total'];
					
					}


					elseif (strpos($lineitem['desc'], 'Workorder') !== false) {
					$final_data_table['all_dates']['Tenant Improvement']=$final_data_table['all_dates']['Tenant Improvement']+$lineitem['total'];		
					$final_total3=$final_total3+$lineitem['total'];
					
					}

					elseif (strpos($lineitem['desc'], 'Work Order') !== false) {
					$final_data_table['all_dates']['Tenant Improvement']=$final_data_table['all_dates']['Tenant Improvement']+$lineitem['total'];		
					$final_total3=$final_total3+$lineitem['total'];
					
					}


					elseif (strpos($lineitem['desc'], 'Rebill') !== false) {
					$final_data_table['all_dates'][$bank]=$final_data_table['all_dates'][$bank]+$lineitem['total'];		
					$rebill['rebill']=$rebill['rebill']+$lineitem['total'];		
					$final_total3=$final_total3+$lineitem['total'];
					
					}

					elseif (strpos($lineitem['desc'], 'Plumbing Work Order') !== false) {
					$final_data_table['all_dates']['Tenant Improvement']=$final_data_table['all_dates']['Tenant Improvement']+$lineitem['total'];		
					$final_total3=$final_total3+$lineitem['total'];
					
					}

					}

				}
					# code...
				}

// 				echo "<br>";
// 				echo "Line total ALL items =".$final_total2;
// 				echo "<br>";
// 				echo "Line total our items =".$final_total3;
// 				if($final_total2!=$final_total3)
// 				{
// 					echo "Error is here !";
// 				}
// 				// echo "<pre>";
// 				echo "<br>";
// 				echo "<a target='_blank' href='".$invediturl."'>Invoice id ".$key."</a>";
// 				// print_r($final_data_table['all_dates']);

// 				echo "<br>";
// 				echo "Final Total = ".$final_data_table['total'];
// // echo "<pre>";
// // 					 	// $array_rent[$key]=$array_rent[$key]+$lineitem['total'];
// // print_r($array_rent);
// // echo "</pre>";
// 				// echo "</pre>";
// 				// # code...
// 				echo "<br>";
// 							echo "***********************************************************************************";
// 				echo "<br>";
							$mk_count++;
							}


							// echo "Total invoice looped".$mk_count;
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
									<th>Account</th>
									<th>Debit</th>
									<th>Credit</th>
									<th>Memo</th>
									<th>Name</th>
									<th>Billable</th>
									<th>Class</th>
									<!-- <th>Bank</th> -->

								</tr>
							</thead> 
							<tbody>
							<?php
							$csv_data_array=array();
							$i=0;
							foreach ($final_data_table['all_dates'] as $key => $value) {
								// echo "<pre>";
								// echo "<pre>";
								// var_dump($value);
								if($value!=0)
								{
								?>
							<tr>
							<td><?php echo $key;?></td>
							<td>  </td>


							   <td><a onclick="submitResources_phone('<?php echo $start_account; ?>','<?php echo $end_account; ?>',<?php  echo $building; ?>,'<?php echo $key; ?>');" href="javascript:void(0);" title="#">$<?php echo $value; ?></a></td>
							<td>Week of <?php echo $end_account; ?></td>
							<td> </td>
							<td> </td>
							<td><?php echo $class; ?> </td>
							<!-- <td><?php echo $bank; ?> </td> -->
							
							</tr>
								<?php
							}
							$csv_data_array[$i]['Account']=$key;
							$csv_data_array[$i]['Debit']=" ";
							$csv_data_array[$i]['Credit']='$' . number_format($value,2);
							$csv_data_array[$i]['Memo']="Week of ". $end_account;
							$csv_data_array[$i]['Name']=" ";
							$csv_data_array[$i]['Billable']=" ";
							$csv_data_array[$i]['Class']=$class;
							// $csv_data_array[$i]['Bank']=$bank;
								
							$i++;
							}
							$csv_data_array[$i]['Account']="Accounts Receivable";
							$csv_data_array[$i]['Debit']=$final_data_table['total'];
							$csv_data_array[$i]['Credit']="";
							$csv_data_array[$i]['Memo']="";
							$csv_data_array[$i]['Name']=" ";
							$csv_data_array[$i]['Billable']=" ";
							$csv_data_array[$i]['Class']=$class;
							// $csv_data_array[$i]['Bank']=$bank;
							if(isset($_REQUEST['Run']))
							{

							reporst_csv("Income journal list.csv",$csv_data_array,"rental_report");	
							}

							?>
							<tr>
							<td>Accounts Receivable</td>
							<td><?php echo $final_data_table['total']; ?></td>
							<td></td>
							<td> </td>
							<td> </td>
							<td> </td>
							<td> </td>
							<td> </td>
							
							</tr>
							</tbody>
							</table>
							</div>


							<?php
$pendingaux=$the_total_aux-$final_data_table['all_dates']['Sec. Deposit']-$rebill['rebill'];
							echo "Total Aux value is ".$the_total_aux;
							echo "<br>";
							echo "Total Aux value without security deposit and without rebill is ".$pendingaux;
							restore_current_blog();

}