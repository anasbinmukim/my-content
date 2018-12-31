<style>
	table{
	}

	td{
		padding-left: 10px;
		padding-right: 10px;
	}

	.admin_popup{
		background-color: darkgrey;
		color: black;
		width: 100%;
		padding: 4px;
		margin-top: 4px;
		margin-bottom: 4px;
		margin-left: 15px;
	}

	.aux_summary{
		margin-left: 80px;
	}

	.admin_popup > h3{
		color: white !important;
		font-weight: 600;
	}

	.admin_popup_closer{
		color: white;
		font-size: 20pt;
		margin-bottom: 5px;
		font-weight: 600;
		text-align: right;
		cursor: pointer;
	}

	.accounting_entry.negative{
		color: red;
	}

	.accounting_entry.negative a{
		color: red;
	}
</style>

<?php
	function getBuildingDataHash(){
		$building_data = array();
		$suites = getSuites(null);

		// Prepare data Hash with all Suites
		foreach($suites as $suite){
			$suite_id = $suite->ID;
			$suite_string = get_the_title($suite_id);
			$ste_num = extractSuiteNum($suite_string);
			if($ste_num){
				$building_data[$ste_num] = array();
			}
		}

		return $building_data;
	}

	function fillBuildingDataHash($buildings, $report_start_date, $report_end_date){
		$data = array();

		foreach($buildings as $building_id => $building_name){
			switch_to_blog($building_id);
			// in rent_roll_utilities.php
			$building_data = getBuildingDataHash();

			$invoice_offset = 0;
			$invoices = getInvoices($invoice_offset);

			// echo $building_id . ' => ' . $building_name . ' : ' . count($invoices) . '<br>';

			while(count($invoices) > 0){
				foreach($invoices as $i){
					$invoice_id = $i->ID;

					$due_date = get_post_meta($invoice_id, '_due_date', true);

					if(!$report_start_date || $report_start_date <= $due_date){
						if(!$report_end_date || $report_end_date >= $due_date){

							// Get Lease Id
							$lease_id = get_post_meta($invoice_id, '_yl_lease_id', true);

							// Get Company Name
							$company = getCompanyName($lease_id);

							if(!$company){
								$client_id = get_post_meta($invoice_id, '_client_id', true);
								$company = get_the_title($client_id);
							}

							$items = get_post_meta($invoice_id, '_doc_line_items', true);

							if($items){
								// Declare $ste_num here, so it remains the same once it is set
								$ste_num = null;
								$last_ste_num = null;
								$temp_data = array();

								foreach($items as $item){
									if($item != ''){
										$desc = trim(strip_tags($item['desc']));
										$total = (float) $item['total'];

										$a = null;

										# Begin Parsing Line Items -------------------------
										if(preg_match('/Monthly Rent for Suite #? ?([\w-]+)/i', $desc, $match) ||
										   preg_match('/Monthly Rent Suite #? ?([\w-]+)/i', $desc, $match) ||
											 preg_match('/Rent Prorated for Suite #? ?([\w-]+)/i', $desc, $match)){
											$ste_num = $match[1];
											$a = array($desc, $total, $invoice_id, $lease_id, true);
										} elseif(preg_match('/First Month Rent Rate/i', $desc, $match) ||
														 preg_match('/Monthly Rent for Retail Lease/i', $desc, $match)||
														 preg_match('/Recurrent Rent Rate/i', $desc, $match) ||
														 preg_match('/First Monthly Rent Rate/i', $desc, $match) ||
														 preg_match('/Monthly Rent for Mailing Address Lease/i', $desc, $match) ||
														 preg_match('/prorated moving out June 17/', $desc, $match)){
											# Get Suite Num from the Lease attched to the
											$ste_num_str = get_post_meta($lease_id, '_yl_suite_number', true);
											$ste_num = extractSuiteNum($ste_num_str);
											$a = array($desc, $total, $invoice_id, $lease_id, true);
										} elseif(preg_match('/Invoice for/i', $desc, $match) ||
														 preg_match('/Security Deposit/i', $desc, $match) ||
														 preg_match('/Standard Credit/i', $desc, $match) ||
														 preg_match('/Hold Service/i', $desc, $match) ||
														 preg_match('/Hold Fee/i', $desc, $match) ||
														 preg_match('/Invoice Rebill/i', $desc, $match)){
							        //IGNORE
										} elseif(preg_match('/Monthly Rent for Y Membership/i', $desc, $match) ||
														 preg_match('/Y-Membership Monthly Rent/i', $desc, $match) ||
														 preg_match('/Monthly Rent for Y Membership/i', $desc, $match)){
												$ste_num = 'Y Membership: ' . $company;
													// $clear_ste_num = true;
													# Special rents are not Suites, but Storeage Unite or Y Memberships
													$a = array($desc, $total, $invoice_id, $lease_id, true);
										} elseif(preg_match('/Rent for (Storage Unit #Str ?\d+)/i', $desc, $match) ||
														 preg_match('/Monthly Rent - (Storage # ?\d+)/i', $desc, $match)){
									  	$ste_num = $match[1];

											$a = array($desc, $total, $invoice_id, $lease_id, true);
										} else {
											$a = array($desc, $total, $invoice_id, $lease_id, false);
										}
							// END PARSING LINE_ITEMS ------------------------------
										if($a){

											if(!$lease_id && $ste_num){
												// In case no lease_id is discovered.
												// This is a necessary hack, as this whole algorithm is
												// ported over from the original Rent Roll Report
												$lease_args = array('post_type' => 'lease', 'numberposts' => 1,
																						'title' => 'Suite #' . $ste_num . ' Lease');
												$lease = get_posts($lease_args);

												if(!$lease){
													$lease_args = array('post_type' => 'lease', 'numberposts' => 1,
																							'post_title' => $ste_num . ' Lease');
													$lease = get_posts($lease_args);
												}
												$lease_id = $lease[0]->ID;
												$a[3] = $lease_id;
												// echo var_dump($lease[0]) . '<br>' . '<br>' . '<br>'. '<br>';
											}

											# If a line item is created.
											array_push($a, $company);
											$url = $i->guid;
											array_push($a, $url);

											//--- Specific to Rent Roll 2 ---
											$move_in_date = get_post_meta($lease_id, '_yl_lease_start_date', true);
											array_push($a, $move_in_date);

											// Get Suite ID
											$suite_id = null;
											$suite_id = get_post_meta($lease_id, '_yl_product_id', true);
											// echo $suite_id . '<br>';

											if(!$suite_id){
												$suite_id_args = array('post_type' => 'suites', 'title' => 'Suite #' . $ste_num);
												$suite = get_posts($suite_id_args)[0];
												$suite_id = $suite->ID;
											}

											if(!$suite_id){
												// echo $ste_num . '<br><br>';
												$suite_id_args = array('post_type' => 'suites', 'post_title' => $ste_num . " Lease");
												$suite = get_posts($suite_id_args)[0];
												$suite_id = $suite->ID;
											}

											array_push($a, $suite_id);

											$sq_footage = get_post_meta($suite_id, "_yl_square_feet", true);
											array_push($a, $sq_footage);

											//--- End Specifc to Rent Roll 2 ---

											if($ste_num){


												if(count($temp_data) > 0){
													// Push $temp_data to it's proper Suite Num.
													if(!$building_data[$ste_num]){
														$building_data[$ste_num] = array();
													}
													foreach($temp_data as $temp){
														array_push($building_data[$ste_num], $temp);
													}
													$temp_data = array();
												}

												if(!$building_data[$ste_num]){
													$building_data[$ste_num] = array();
												}
												array_push($building_data[$ste_num], $a);

												if(preg_match('/Multi Suite Discount/i', $a[0], $match)){
													$last_ste_num = $ste_num;
													$ste_num = null;
												}
											} else {
												array_push($temp_data, $a);
											}
										}
									} // if item is not a blank string
								}	// Each LineItem

								// After each line item has been parsed, if $ste_num doesn't get set
								if($ste_num == null){
										if($last_ste_num){
											// $last_ste_num
											$ste_num = $last_ste_num;
										} else {
											#pass through all items, and no $ste_num is set
 	 								 	 $ste_num_str = get_post_meta($lease_id, '_yl_suite_number', true);
	 								 	 $ste_num = extractSuiteNum($ste_num_str);
										}

										if(!$ste_num){
											$ste_num_str = get_post_meta($lease_id, '_yl_tinfo_suite_numbers', true);
											if($ste_num_str){
												$ste_num = $ste_num_str . ': ' . $company;
											}
										}

										if(!$ste_num){
											$ste_num_str = get_post_meta($lease_id, '_yl_suites_leased', true);
											if($ste_num_str){
												$ste_num = $ste_num_str . ': ' . $company;
											}
										}

										if(!$ste_num){
											# occasionally, Suite# is on the invoice.
											$ste_num_str = get_post_meta($invoice_id, "_yls_suite_number", true);
												if($ste_num_str){
													$ste_num = $ste_num_str . ': ' . $company;
												}
										}

									 if(!$ste_num){
										 // If no Suite associated with Lease
										 $ste_num = $company;
									 }

									 foreach($temp_data as $item){
										 if($building_data[$ste_num] == null){
											 $building_data[$ste_num] = array();
										 }

										array_push($building_data[$ste_num], $item);
										// echo $item[1] . ' - ' . $ste_num . ' - ' . $invoice_id . ':' . $item[2] . '<br>';
									 }
								 }
							}
						}
					}
				} // Each Invoice

				$invoice_offset += 500;
				$invoices = getInvoices($invoice_offset);

			}
			$data[$building_name] = $building_data;
		}

		return $data;
	}
?>
