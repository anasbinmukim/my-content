<?php
// THIS SHORTCODE IS ACTUALLY THE  
// CLIENT LEASE, NOT THE LEASE SUMMARY
// FIRST CODER GOT THEM WRONG.
function yl_lease_summary_client_sign_shortcode($atts, $content = null) {

	extract(shortcode_atts(array(
		'lease_id' => '',
	), $atts));
	
	if ( !is_user_logged_in() ){
		wp_login_form();
		return;
	}

    /*
	if(!current_user_can( 'lease_client' )){
		$lid = $_GET['lid'];
		echo 'Only Clients are able to sign lease. Please <a href="'.wp_login_url( get_permalink(get_option('yl_client_sign_page')).'?lid='.$lid ).'">login</a> to sign this lease.';
		return;
	}		
    */

    if ( isset($_POST['lss_submit']) || isset($_POST['lss_submit_continue']) ) {

        // signature convert an image and send to the wp signature directory
        $dir = "/signatures";
        $lease_id = $_GET['lid'];
        $data_singature_draw = $_POST['imgOutput2'];
        $keypad_sig = $_POST['keypad_sig'];

        // convert text to png image
        if ($keypad_sig) {
            $text = $keypad_sig;
            $font = YL_ROOT . '/signature/assets/fonts/Precious'; 
            $font_color = '000'; 
            $background_color = 'fff'; 
            $font_size = '20'; 

            $upload_dir = wp_upload_dir();
            $signature_dir = $upload_dir['basedir'].$dir;
            $signature_dir_url = $upload_dir['baseurl'].$dir;
            if( ! file_exists( $signature_dir ) ){
                wp_mkdir_p( $signature_dir );
            }

            $filename = time().".png";
            $filepath = $signature_dir."/".$filename;

            file_put_contents($filepath, $filename);

            if (file_exists($filepath)){
                sig()->text_to_PNG_file($text, $font, $font_color, $background_color, $font_size, $filepath); // call this method from signature class
                $fileurl = $signature_dir_url."/".$filename;
                update_post_meta($lease_id, '_yl_client_signature',  $fileurl);
                update_post_meta($lease_id, '_yl_client_signature_date', $_POST['date']);
            } 
            else { 
                error_log("Cannot create signature file in directory ".$filepath);
            }
        }elseif (isset($data_singature_draw) && is_string($data_singature_draw) && strrpos($data_singature_draw, "data:image/png;base64", -strlen($data_singature_draw)) !== FALSE){
	        // convert canvas to png image
            $data_pieces = explode(",", $data_singature_draw);
            $encoded_image = $data_pieces[1];
            $decoded_image = base64_decode($encoded_image);
            $upload_dir = wp_upload_dir();
            $signature_dir = $upload_dir['basedir'].$dir;
            $signature_dir_url = $upload_dir['baseurl'].$dir;
            
            if( ! file_exists( $signature_dir ) ){
                wp_mkdir_p( $signature_dir );
            }

            $filename = time().".png";
            $filepath = $signature_dir."/".$filename;


                if (strlen($decoded_image) > 441 ) { 
                file_put_contents( $filepath,$decoded_image);

                if (file_exists($filepath)){
                    // File created : changing posted data to the URL instead of base64 encoded image data
                    $fileurl = $signature_dir_url."/".$filename;
                    update_post_meta($lease_id, '_yl_client_signature',  $fileurl);
                    update_post_meta($lease_id, '_yl_client_signature_date', $_POST['date']);			  
              
                } 
                else { 
                    error_log("Cannot create signature file in directory ".$filepath);
                } 
            }      
        }        

		if( get_post_meta($lease_id, '_yl_client_signature', true) ) {

			$product_id = get_post_meta($lease_id, '_yl_product_id', true);
			
			$lease_start_date = get_post_meta($lease_id, '_yl_lease_start_date', true);
			$move_in_date_arr = explode("-", $lease_start_date);
			$month = $move_in_date_arr[1];

			$day = $move_in_date_arr[2];
			$year = $move_in_date_arr[0];
			
			if($day == "01" || $day == "1") {
				$moveNext = 0;
				$cur_month = $month;
			} else {
				$moveNext = 1;
				$cur_month = 01;
			}
			
			if ($month == 12) {
				$year = ($year+$moveNext);
				$timestamp = date("$year-$cur_month-01");
			} else {
				$month = ($month+$moveNext);
				$timestamp = date("$year-$month-01");
			}
			
			$date1 = date_create(date("Y-m-d"));
			$date2 = date_create($timestamp);
			$diff = date_diff($date1, $date2);
			$daysRemaining = $diff->format("%a");
			
			$service_fees_product = get_option('yl_service_fees');

			if($daysRemaining > 0) {
				update_post_meta($product_id, '_subscription_trial_length', $daysRemaining);
				/*if(get_post_meta($lease_id, '_yl_service_fees', true)) {
					update_post_meta($service_fees_product, '_subscription_trial_length', $daysRemaining);
				}*/
			} else {
				// if there are no trial days, means this is the first day of the month and also the subscription & move-in date
				update_post_meta($product_id, '_subscription_trial_length', 0);
				/*if(get_post_meta($lease_id, '_yl_service_fees', true)) {
					update_post_meta($service_fees_product, '_subscription_trial_length', 0);
				}*/
			}

			$security_deposit = get_post_meta($lease_id, '_yl_security_deposit', true);

			// check if it is the 1st day of the month
			if( ($lease_start_date == date("Y-m-d")) && (date("j") == 1) ) {
				//$sign_up_fee = get_post_meta($lease_id, '_yl_monthly_rent', true);
			} else {
				//$sign_up_fee = $security_deposit;
				update_post_meta($product_id, '_subscription_sign_up_fee', $security_deposit);
				/*if(get_post_meta($lease_id, '_yl_service_fees', true)) {
					update_post_meta($service_fees_product, '_subscription_sign_up_fee', 0);
				}*/
			}

			$coupon_code = get_post_meta($lease_id, '_yl_promotional_code', true);
			if($coupon_code) {
                
			}
			
			//Apply if have Multisite discount coupon.
			$multisite_coupon = get_post_meta($lease_id, '_yl_multisite_coupon', true);
			if($multisite_coupon) {
				
			}			

            $checkout_url = get_permalink(get_option('yl_lease_checkout_page')).'?lid='.$lease_id;
            if (isset($_POST['lss_submit_continue'])) {
                $checkout_url .= '&redirect='.urlencode(get_permalink(get_option('yl_tenant_information_page')).'?lid='.$_GET['lid'].'&bmprocess=1');
            }
            else {
                $checkout_url .= '&redirect='.urlencode(get_permalink(get_option('yl_tenant_information_page')).'?lid='.$_GET['lid']);
            }
			wp_redirect( $checkout_url );
			exit;
		} else {
			echo 'Please sign the lease before proceeding';
			//return;
		}
    }

	if( isset($_GET['lid']) && $_GET['lid'] != '' ) {
		$lease_id = $_GET['lid'];
		$args = array(
			'post_type' => 'lease',
			'p' => $lease_id,
			'post_status' => 'any'
		);
		$query = new WP_Query( $args );

		ob_start();
	
		//if($query->have_posts()) :
			global $post, $us_states_full;
			//while($query->have_posts()): $query->the_post();
        	?>
            <style type="text/css">
				.summary_left:after { clear: both; content: ""; display: table; }
				/*.lease_summary_content { clear: both; max-height: 600px; max-width: 680px; overflow-y: scroll; }*/
				.summary_right{ width:100%; }
				.lease_summary_content > br { display: none; }
			</style>
        	<div class="lease_summary_container bm-lease-summary-sign">
            	<?php
        			if( isset($message) ) {
        				echo '<h4>'.esc_html($message).'</h4>';
        			}
        		?>
            	<!--<h2>Lease Summary</h2>-->
                <div class="lease_details">

                    <div class="yl_timeline_container">
                      <div class="yl_timeline_line step_5">
                        <span>
                        </span>
                      </div>

                      <div class="yl_top_section">
                        <div class="yl_bm_top_line">
                          <div>
                            <span>BM</span>

                            <div class="yl_unit_block yl_one_third active">
                              <div class="yl_circle">
                              </div>
                              <div class="yl_desc">
                                Search
                              </div>
                            </div>

                            <div class="yl_unit_block yl_one_third step_client_info active">
                              <div class="yl_circle">
                              </div>
                              <div class="yl_desc">
                                Client<br>Info
                              </div>
                            </div>

                            <div class="yl_unit_block yl_one_third active">
                              <div class="yl_circle">
                              </div>
                              <div class="yl_desc">
                                Lease<br>Summary
                              </div>
                            </div>
                          </div>
                        </div>
                        <div class="yl_client_top_line">
                          <div>
                            <span>Client</span>

                            <div class="yl_unit_block yl_one_third active">
                              <div class="yl_circle">
                              </div>
                              <div class="yl_desc">
                                Lease Summary<br>Client
                              </div>
                            </div>

                            <div class="yl_unit_block yl_one_third active">
                              <div class="yl_circle">
                              </div>
                              <div class="yl_desc">
                                Client Review<br>&amp; Sign Lease
                              </div>
                            </div>

                            <div class="yl_unit_block yl_one_third">
                              <div class="yl_circle">
                              </div>
                              <div class="yl_desc">
                                Client<br>Payment
                              </div>
                            </div>
                          </div>
                        </div>
                        <div class="yl_bm_top_line yl_bm_last_step">
                      <div>
                            <span>BM</span>

                            <div class="yl_unit_block yl_full_width">
                              <div class="yl_circle">
                              </div>
                              <div class="yl_desc">
                                BM<br>Finalize
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>

                    </div>


                    <form action="" method="post" class="sigPad2">
            <!--        	<p>
                            <label for="lessor">Lessor</label>
                            <input type="text" id="lessor" name="lessor" value="<?php //echo get_post_meta($lease_id, '_yl_lessor', true); ?>" readonly="readonly" />
                        </p>
                        <p>
                            <label for="lessorLocation">Location</label>
                            <input type="text" id="lessorLocation" name="lessorLocation" value="<?php //echo get_post_meta($lease_id, '_yl_location', true); ?>" readonly="readonly" />
                        </p>
                        <p>
                            <label for="locationPhone">Location Phone Number</label>
                            <input type="text" id="locationPhone" name="locationPhone" value="<?php //echo get_post_meta($lease_id, '_yl_location_phone_number', true); ?>" readonly="readonly" />
                        </p>
            
                        <hr />-->
            
                        <div class="summary_left" style="float: none; width: auto;">
                            <h3>Client Info</h3>
                            <p>
                                <label for="lessee_first_name">First Name</label>
                                <input type="text" id="lessee_first_name" name="lessee_first_name" value="<?php echo esc_attr(get_post_meta($lease_id, '_yl_l_first_name', true)); ?>" readonly="readonly" />
                            </p>
                            <p>
                                <label for="lessee_middle_name">Middle Name</label>
                                <input type="text" id="lessee_middle_name" name="lessee_middle_name" value="<?php echo esc_attr(get_post_meta($lease_id, '_yl_l_middle_name', true)); ?>" readonly="readonly" />
                            </p>
                            <p>
                                <label for="lessee_last_name">Last Name</label>
                                <input type="text" id="lessee_last_name" name="lessee_last_name" value="<?php echo esc_attr(get_post_meta($lease_id, '_yl_l_last_name', true)); ?>" readonly="readonly" />
                            </p>  
                            <p>
                                <label for="suite_name">Suite Name</label>
                                <?php
                                    $product_id = get_post_meta($lease_id, '_yl_product_id', true);
                                ?>
                                <input type="text" name="suite_name" id="suite_name" value="<?php echo esc_attr(get_the_title($product_id)); ?>" readonly="readonly" />
                            </p>				
                            <p>
                                <label for="lease_start_date">Moving Date</label>
                                <input type="text" name="lease_start_date" id="lease_start_date" value="<?php echo esc_attr(get_post_meta($lease_id, '_yl_lease_start_date', true)); ?>" readonly="readonly" />
                            </p>				
                            
                            </div>
                            <!--
                            <p>
                                <label for="lessee_phone">Phone</label>
                                <input type="text" name="lessee_phone" id="lessee_phone" value="<?php echo get_post_meta($lease_id, '_yl_l_phone', true); ?>" readonly="readonly" />
                            </p>
                            <p>
                                <label for="lessee_email">Email</label>
                                <input type="text" name="lessee_email" id="lessee_email" value="<?php echo get_post_meta($lease_id, '_yl_l_email', true); ?>" readonly="readonly" />
                            </p>
                            <p>
                                <label for="lessee_street_address">Address</label>
                                <input type="text" name="lessee_street_address" id="lessee_street_address" value="<?php echo get_post_meta($lease_id, '_yl_l_street_address', true); ?>" readonly="readonly" />
                                <label for="lessee_address_2">Address Line 2</label>
                                <input type="text" name="lessee_address_2" id="lessee_address_2" value="<?php echo get_post_meta($lease_id, '_yl_l_address_line_2', true); ?>" readonly="readonly" />
                                <label for="lessee_city">City</label>
                                <input type="text" name="lessee_city" id="lessee_city" value="<?php echo get_post_meta($lease_id, '_yl_l_city', true); ?>" readonly="readonly" />
            
                                <label for="lessee_state">State</label>
                                <select name="lessee_state" id="lessee_state" disabled="disabled">
                                <?php
                                    $lesseeState = get_post_meta($lease_id, '_yl_l_state', true);
                                    foreach($us_states_full as $us_state) {
                                        if($lesseeState == $us_state) $selected = 'selected="selected"';
                                        else $selected = '';
                                        echo '<option value="'.$us_state.'" '.$selected.'>'.$us_state.'</option>';
                                    }
                                ?>
                                </select>
                                <input type="hidden" name="lessee_state" id="lessee_state" value="<?php echo get_post_meta($lease_id, '_yl_l_state', true); ?>" />
                            </p>
                            <p>
                                <label for="lessee_zip_code">ZIP Code</label>
                                <input type="text" name="lessee_zip_code" id="lessee_zip_code" value="<?php echo get_post_meta($lease_id, '_yl_l_zip_code', true); ?>" readonly="readonly" />
                            </p>
            
                            <h3>Guarantor</h3>
                            <p>
                                <label for="guarantor_first_name">First Name</label>
                                <input type="text" id="guarantor_first_name" name="guarantor_first_name" value="<?php echo get_post_meta($lease_id, '_yl_g_first_name', true); ?>" readonly="readonly" />
                            </p>
                            <p>
                                <label for="guarantor_middle_name">Middle Name</label>
                                <input type="text" id="guarantor_middle_name" name="guarantor_middle_name" value="<?php echo get_post_meta($lease_id, '_yl_g_middle_name', true); ?>" readonly="readonly" />
                            </p>
                            <p>
                                <label for="guarantor_last_name">Last Name</label>
                                <input type="text" id="guarantor_last_name" name="guarantor_last_name" value="<?php echo get_post_meta($lease_id, '_yl_g_last_name', true); ?>" readonly="readonly" />
                            </p>
                            <p>
                                <label for="guarantor_phone">Phone</label>
                                <input type="text" name="guarantor_phone" id="guarantor_phone" value="<?php echo get_post_meta($lease_id, '_yl_g_phone', true); ?>" readonly="readonly" />
                            </p>
                            <p>
                                <label for="guarantor_email">Email</label>
                                <input type="text" name="guarantor_email" id="guarantor_email" value="<?php echo get_post_meta($lease_id, '_yl_g_email', true); ?>" readonly="readonly" />
                            </p>
                            <p>
                                <label for="guarantor_street_address">Address</label>
                                <input type="text" name="guarantor_street_address" id="guarantor_street_address" value="<?php echo get_post_meta($lease_id, '_yl_g_street_address', true); ?>" readonly="readonly" />
                                <label for="guarantor_address_2">Address Line 2</label>
                                <input type="text" name="guarantor_address_2" id="guarantor_address_2" value="<?php echo get_post_meta($lease_id, '_yl_g_address_line_2', true); ?>" readonly="readonly" />
                                <label for="guarantor_city">City</label>
                                <input type="text" name="guarantor_city" id="guarantor_city" value="<?php echo get_post_meta($lease_id, '_yl_g_city', true); ?>" readonly="readonly" />
            
                                <label for="guarantor_state">State</label>
                                <select name="guarantor_state" id="guarantor_state" disabled="disabled">
                                <?php
                                    $guarantorState = get_post_meta($lease_id, '_yl_g_state', true);
                                    foreach($us_states_full as $us_state) {
                                        if($guarantorState == $us_state) $selected = 'selected="selected"';
                                        else $selected = '';
                                        echo '<option value="'.$us_state.'" '.$selected.'>'.$us_state.'</option>';
                                    }
                                ?>
                                </select>
                                <input type="hidden" name="guarantor_state" id="guarantor_state" value="<?php echo get_post_meta($lease_id, '_yl_g_state', true); ?>" />
                            </p>
                            <p>
                                <label for="guarantor_zip_code">ZIP Code</label>
                                <input type="text" name="guarantor_zip_code" id="guarantor_zip_code" value="<?php echo get_post_meta($lease_id, '_yl_g_zip_code', true); ?>" readonly="readonly" />
                            </p>
                        </div>--><!-- .summary_left -->
                        
                        <div class="summary_right">
                           <!-- <p>
                                <label for="company_name">Company Name</label>
                                <input type="text" name="company_name" id="company_name" value="<?php echo get_the_title(get_post_meta($lease_id, '_yl_company_name', true)); ?>" readonly="readonly" />
                            </p>
                            <p>
                                <label for="company_type">Company Type</label>
                                <?php
                                    $c_type = get_term( get_post_meta($lease_id, '_yl_company_type', true), 'companytype' );
                                ?>
                                <input type="text" name="company_type" id="company_type" value="<?php echo $c_type->name; ?>" readonly="readonly" />
                            </p>
                            <p>
                                <label for="suite_number">Suite Number</label>
                                <input type="text" name="suite_number" id="suite_number" value="<?php echo get_post_meta($lease_id, '_yl_suite_number', true); ?>" readonly="readonly" />
                            </p>
                            <p>
                                <label for="lease_start_date">Lease Start Date</label>
                                <input type="text" name="lease_start_date" id="lease_start_date" value="<?php echo get_post_meta($lease_id, '_yl_lease_start_date', true); ?>" readonly="readonly" />
                            </p>
                            <p>
                                <label for="first_month_rent_rate">First Month Rent Rate</label>
                                <input type="text" name="first_month_rent_rate" id="first_month_rent_rate" value="<?php echo get_post_meta($lease_id, '_yl_first_month_rent_rate', true); ?>" readonly="readonly" />
                            </p>
                            <p>
                                <label for="monthly_rent">Monthly Recurring Rent Rate</label>
                                <input type="text" name="monthly_rent" id="monthly_rent" value="<?php echo get_post_meta($lease_id, '_yl_monthly_rent', true); ?>" readonly="readonly" />
                            </p>
                            <p>
                                <label for="security_deposit">Security Deposit</label>
                                <input type="text" name="security_deposit" id="security_deposit" value="<?php echo get_post_meta($lease_id, '_yl_security_deposit', true); ?>" readonly="readonly" />
                            </p>-->
                            <!--<p>
                                <label for="lease_term">Lease Term</label>
                                <input type="text" name="lease_term" id="lease_term" value="<?php echo get_post_meta($lease_id, '_yl_lease_term', true); ?>" readonly="readonly" />
                            </p>-->
                            <!--<p>
                                <label for="vacate_notice">Vacate Notice</label>
                                <?php
                                    if(get_post_meta($lease_id, '_yl_vacate_notice', true))
                                        $vacate_notice = get_post_meta($lease_id, '_yl_vacate_notice', true);
                                    else	
                                        $vacate_notice = get_option('yl_vacate_notice');
                                ?>
                                <input type="text" name="vacate_notice" id="vacate_notice" value="<?php echo $vacate_notice; ?>" readonly="readonly" />
                            </p>
                            <p>
                                <label for="promotional_code">Promotional Code</label>
                                <input type="text" name="promotional_code" id="promotional_code" value="<?php echo get_post_meta($lease_id, '_yl_promotional_code', true); ?>" readonly="readonly" />
                            </p>
                            <p>
                                <label for="service_fees">Service Fees</label>
                                <input type="text" name="service_fees" id="service_fees" value="<?php echo get_post_meta($lease_id, '_yl_service_fees', true); ?>" readonly="readonly" />
                            </p>
                            <p>
                                <label for="multi_suite_discount">Multi Suite Discount</label>
                                <input type="text" name="multi_suite_discount" id="multi_suite_discount" value="<?php echo get_post_meta($lease_id, '_yl_multi_suite_discount', true); ?>" readonly="readonly" />
                            </p>-->
							<style type="text/css">
								.lease_summary_content{}
								.lease_summary_content ol{ margin-left:20px; }
								.lease_summary_content ol li{ margin-left:20px; }
							</style>            
                            <div class="lease_summary_content" style="max-height: 600px; overflow-y: scroll;">
                                <?php
                                    $product_id = get_post_meta($lease_id, '_yl_product_id', true);
                                    
                                    if ($product_id == -1) {
                                        $lease_summary = stripslashes(get_option('ym_lease_summary'));
                                    }else{
                                        $lease_summary = stripslashes(get_option('lease_summary'));
                                    }

                                    $search = array();
                                    $replace = array();	
                                    
                                    $search[] = '%%CompanyName%%';
                                    $replace[] = get_the_title(get_post_meta($lease_id, '_yl_company_name', true));
                                    
                                    $search[] = '%%LesseeGuarantorPersonalPhoneNumber%%';
                                    $replace[] = get_post_meta($lease_id, '_yl_g_phone', true);
            
                                    $search[] = '%%CompanyType%%';
                                    $c_term = get_term_by('id', get_post_meta($lease_id, '_yl_company_type', true), 'companytype');
                                    $replace[] = $c_term->name;
									
									$search[] = '%%Lessee%%';
									$lessee_full_name = get_post_meta($lease_id, '_yl_l_first_name', true);
									if(get_post_meta($lease_id, '_yl_l_middle_name', true))
										$lessee_full_name .= ' '. get_post_meta($lease_id, '_yl_l_middle_name', true);
									if(get_post_meta($lease_id, '_yl_l_last_name', true))
										$lessee_full_name .= ' '. get_post_meta($lease_id, '_yl_l_last_name', true);			
									$replace[] = $lessee_full_name;
								
									$search[] = '%%Guarantor%%';
									$guarantor_full_name = get_post_meta($lease_id, '_yl_g_first_name', true);
									if(get_post_meta($lease_id, '_yl_g_middle_name', true))
										$guarantor_full_name .= ' '. get_post_meta($lease_id, '_yl_g_middle_name', true);
									if(get_post_meta($lease_id, '_yl_g_last_name', true))
										$guarantor_full_name .= ' '. get_post_meta($lease_id, '_yl_g_last_name', true);			
									$replace[] = $guarantor_full_name;									
            
                                    $search[] = '%%SuiteNo%%';
                                    $replace[] = get_post_meta($lease_id, '_yl_suite_number', true);
            
                                    $search[] = '%%FirstMonthRentRate%%';
                                    $replace[] = get_post_meta($lease_id, '_yl_first_month_rent_rate', true);
            
                                    $search[] = '%%RecurringRentRate%%';
                                    $replace[] = get_post_meta($lease_id, '_yl_monthly_rent', true);
            
                                    $search[] = '%%SecurityDeposit%%';
                                    $replace[] = get_post_meta($lease_id, '_yl_security_deposit', true);
            
                                    $search[] = '%%MoveinLeaseStartDate%%';
                                    $replace[] = get_post_meta($lease_id, '_yl_lease_start_date', true);
            
                                    $search[] = '%%LesseeGuarantorHomeAddress%%';
                                    if( get_post_meta($lease_id, '_yl_g_address_line_2', true) ) {
                                        $g_street2 = ', '.get_post_meta($lease_id, '_yl_g_address_line_2', true);
                                    } else {
                                        $g_street2 = '';
                                    }
                                    $g_address = get_post_meta($lease_id, '_yl_g_street_address', true).$g_street2.', '.get_post_meta($lease_id, '_yl_g_city', true).', '.get_post_meta($lease_id, '_yl_g_state', true).', '.get_post_meta($lease_id, '_yl_g_zip_code', true);
                                    $replace[] = $g_address;
            
                                    $search[] = '%%CurrentDate%%';
                                    $replace[] = date("Y-m-d");
            
                                    $search[] = '%%LesseeGuarantorEmail%%';
                                    $replace[] = get_post_meta($lease_id, '_yl_g_email', true);
            
                                    $search[] = '%%ServiceCompanyCheckbox%%';
            //						$replace[] = get_post_meta($lease_id, '_yl_g_email', true);
            
                                    $search[] = '%%Addendum%%';
                                    $replace[] = get_post_meta($lease_id, '_yl_addendums', true);
            
                                    $search[] = '%%PromoCode%%';
                                    $replace[] = get_post_meta($lease_id, '_yl_promotional_code', true);
            
                                    $search[] = '%%MultisuiteDiscountIfApplicable%%';
                                    $replace[] = get_post_meta($lease_id, '_yl_multi_suite_discount', true);
            
                                    $search[] = '%%Lessor%%';
                                    $replace[] = get_post_meta($lease_id, '_yl_lessor', true);
            
                                    $search[] = '%%Location%%';
                                    $replace[] = get_post_meta($lease_id, '_yl_location', true);
            
                                    $author_id = $post->post_author;
                                    $user = get_user_by( 'id', $author_id );
            
                                    $search[] = '%%BuildingManager%%';
                                    $replace[] = $user->first_name;
            
                                    $search[] = '%%BuildingManagerEmail%%';
                                    $replace[] = $user->user_email;
            
                                    $search[] = '%%BuildingManagerPhoneNumber%%';
                                    $replace[] = get_user_meta( $author_id, 'billing_phone', true );
            
                                    $search[] = '%%LesseeHomeAddress%%';
                                    if( get_post_meta($lease_id, '_yl_l_address_line_2', true) ) {
                                        $l_street2 = ', '.get_post_meta($lease_id, '_yl_l_address_line_2', true);
                                    } else {
                                        $l_street2 = '';
                                    }
                                    $l_address = get_post_meta($lease_id, '_yl_l_street_address', true).$l_street2.', '.get_post_meta($lease_id, '_yl_l_city', true).', '.get_post_meta($lease_id, '_yl_l_state', true).', '.get_post_meta($lease_id, '_yl_l_zip_code', true);
                                    $replace[] = $l_address;
                                
                                    $search[] = '%%LocationPhoneNumber%%';
                                    $replace[] = get_post_meta($lease_id, '_yl_location_phone_number', true);
                                
                                    $search[] = '%%LeaseTerm%%';
                                    $replace[] = get_post_meta($lease_id, '_yl_lease_term', true);
                                
                                    $search[] = '%%ServiceFees%%';
                                    $replace[] = get_post_meta($lease_id, '_yl_service_fees', true);
                                
                                    $search[] = '%%DueAtSigning%%';
                                    $replace[] = get_post_meta($lease_id, '_yl_due_at_signing', true);
                                
                                    $search[] = '%%IsLesseeGuarantor%%';
                                    $replace[] = get_post_meta($lease_id, '_yl_lessee_guarantor_same', true);
                                
                                    $search[] = '%%LesseePhone%%';
                                    $replace[] = get_post_meta($lease_id, '_yl_l_phone', true);
                                
                                    $search[] = '%%LesseeEmail%%';
                                    $replace[] = get_post_meta($lease_id, '_yl_l_email', true);
                                
                                    $search[] = '%%LesseeSignature%%';
                                    $replace[] = '<img src="'.get_post_meta($lease_id, '_yl_client_signature', true).'" />';
                                
                                    $search[] = '%%BuildingManagerSignature%%';
                                    $replace[] = '<img src="'.get_post_meta($lease_id, '_yl_bm_signature', true).'" />';
                                
                                    $search[] = '%%LesseeSignatureDate%%';
                                    $replace[] = get_post_meta($lease_id, '_yl_client_signature_date', true);
                                
                                    $search[] = '%%BuildingManagerSignatureDate%%';
                                    $replace[] = get_post_meta($lease_id, '_yl_bm_signature_date', true);
                                
                                    $search[] = '%%LeasePDF%%';
                                    $replace[] = get_post_meta($lease_id, '_yl_lease_pdf', true);
                                
                                    $search[] = '%%LeaseSummaryPDF%%';
                                    $replace[] = get_post_meta($lease_id, '_yl_lease_summary_pdf', true);
                                    
                                    $summary = str_replace($search, $replace, $lease_summary);	
									$summary = apply_filters('the_content', $summary);
									
                                    //$summary = stripslashes($summary);
                                    //$summary = nl2br($summary);
                                    echo $summary;
                                ?>
                            </div>
            
                            <?php
                                if( get_post_meta($lease_id, '_yl_addendums', true) ) {
                                    echo '<h2>'.esc_html(get_post_meta($lease_id, '_yl_addendums', true)).'</h2>';
                                }
                            ?>
                            
                            <p>
                                <label for="date">Date</label>
                                <?php
                                    if(get_post_meta($lease_id, '_yl_client_signature_date', true))
                                        $signature_date = get_post_meta($lease_id, '_yl_client_signature_date', true);
                                    else	
                                        $signature_date = date('Y-m-d');	
                                ?>						
                                <input type="text" class="leasedatepicker" name="date" id="date" value="<?php echo esc_attr($signature_date); ?>"/>
                            </p>
                            <p>
                
                                <div class="sign_fields">
                                    <p class="drawItDesc">Type <input checked="checked" type="radio" name="sig_type" id="sig_type" value="yes"/> Or Draw <input type="radio" name="sig_type" id="sig_draw" value="No" />your signature</p><br/>
                                      
                            
                                      </p><input type="text" name="keypad_sig" id="keypad_sig" value="" placeholder="Type your signature here"/></p>
                
                                    <div class="draw_wrap" style="display:none;">
                                      <ul class="sigNav">
                                        <li class="drawIt"><a href="#draw-it">Draw It</a></li>
                                        <li class="clearButton"><a href="#clear">Clear</a></li>
                                      </ul>
                                      <div class="sig sigWrapper">
                                        <canvas class="pad signature_pad" width="340" height="100"></canvas>
                
                                        <input type="hidden" name="imgOutput2" class="imgOutput2" value="">
                
                                      </div>
                                    </div>  
                                </div>
            
                                <?php 
                                 $sig = get_post_meta($lease_id, '_yl_client_signature', true);
                                 if($sig) {
                                    ?>
                                    <div class="sig_out">
                                    <img src="<?php echo $sig; ?>" alt="Signature"/>
                                    </div>
                                 <?php
                                }
                            ?>    
                            </p>
            
                            <!--<hr />-->
            
                            <!--<br/><br/><br/>-->
                            <p>By clicking button, you've read the lease summary and proceed to payment.</p>
							
							<div class="row lease_buttons">
								<div class="col-md-6 text-left">
									<?php 			
									$previous_link = get_permalink(get_option('yl_summary_sign_page')).'?lid='.$_GET['lid'].'&bmprocess=1';			
									?>
								  <a href="<?php echo $previous_link; ?>" class="ls_submit ls_button_back">Back</a>
								</div>
								<div class="col-md-6 text-right">
                                <?php
                                if (!$_GET['bmprocess']) {
                                    ?>
                                    <input type="submit" name="lss_submit" id="lss_submit" value="Submit" />
                                    <?php
                                }
                                ?>
                                <?php
                                if(current_user_can( 'building_manager' )){ 
                                    ?>
                                    <input type="submit" name="lss_submit_continue" id="lss_submit_continue" value="Submit &amp; Continue" />
                                    <?php
                                    }
                                ?>
								</div>
							</div>							
                        </div><!-- .summary_right -->
                    </form>
                </div>
            </div><!-- .lease_summary_container -->
        	<?php
    	//endwhile;
		wp_reset_postdata();
?>
<script type="text/javascript">
      // Type Or Draw  
    jQuery(document).ready(function() {

      jQuery("#sig_type").click(function() {
        if(jQuery(this).prop("checked") == true ) {
          jQuery("#keypad_sig").show();
           jQuery(".draw_wrap").hide();
        } 
      });

      jQuery("#sig_draw").click(function() {
        if(jQuery(this).prop("checked") == true ) {
          jQuery(".draw_wrap").show();
           jQuery("#keypad_sig").hide();
        } 
      });
    });

</script>
<?php
		//endif;
		$yl_lease_sign = ob_get_contents();
		ob_end_clean();	
	}

	return $yl_lease_sign;
}
add_shortcode('lease-summary-sign','yl_lease_summary_client_sign_shortcode');