<?php
function yl_bm_lease_summary_client_sign_shortcode($atts, $content = null) {
	extract(shortcode_atts(array(
		'lease_id' => '',
	), $atts));
	
	if ( !is_user_logged_in() ){
		wp_login_form();
		return;
	}	
	
	if(!current_user_can( 'building_manager' )){
		$lid = $_GET['lid'];
		echo 'Only Building Manager are able to sign lease. Please <a href="'.wp_login_url( get_permalink(get_option('yl_bm_summary_sign_page')).'?lid='.$lid ).'">login</a>.';
		return;
	}		

    if( isset($_POST['lss_submit']) ) {

		//$id = $_GET['lid'];

      // signature convert an image and send to the wp signature directory
          $dir = "/signatures";
          $id = $_GET['lid'];
          $data = $_POST['imgOutput2'];
          $keypad_sig = $_POST['keypad_sig'];
       
    // convert text to png image

        if($keypad_sig) {

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
              update_post_meta($id, '_yl_bm_signature',  $fileurl);
              update_post_meta($id, '_yl_bm_signature_date', $_POST['date']);
			  //Add lease summary version
			  update_post_meta($id, '_yl_lease_version', get_option('lease_version'));			  
         
            } else { 
                error_log("Cannot create signature file in directory ".$filepath);
            }

          }elseif (is_string($data) && strrpos($data, "data:image/png;base64", -strlen($data)) !== FALSE){
		  	// convert canvas to png image
              $data_pieces = explode(",", $data);
              $encoded_image = $data_pieces[1];
              $decoded_image = base64_decode($encoded_image);

              $upload_dir = wp_upload_dir();
              $signature_dir = $upload_dir['basedir'].$dir;
              $signature_dir_url = $upload_dir['baseurl'].$dir;
              if( ! file_exists( $signature_dir ) ){
              wp_mkdir_p( $signature_dir );
             }
             $filename = $key."-".time().".png";
             $filepath = $signature_dir."/".$filename;

            if (strlen($decoded_image) > 441 ) { 

            file_put_contents( $filepath,$decoded_image);

            if (file_exists($filepath)){
              // File created : changing posted data to the URL instead of base64 encoded image data
              $fileurl = $signature_dir_url."/".$filename;
              update_post_meta($id, '_yl_bm_signature', $fileurl);
              update_post_meta($id, '_yl_bm_signature_date', $_POST['date']);
			  //Add lease summary version
			  update_post_meta($id, '_yl_lease_version', get_option('lease_version'));
         
            } else { 
                error_log("Cannot create signature file in directory ".$filepath);
            }
           
           }

          }        

		if( get_post_meta($id, '_yl_bm_signature', true) ) {

			// Set lease status as publish
			$lease_id = $_GET['lid'];
			$post_to_update = get_post($lease_id);
			$update_values = array(
				'ID' => $post_to_update->ID,
				'post_status' => 'publish'
			);
			$post_rsp = wp_update_post( $update_values );
			
			
			//add lease version to database
			$product_id = get_post_meta($lease_id, '_yl_product_id', true);                                    
			if ($product_id == -1) {
				$lease_version = get_option('ym_lease_version');
			}else{
				$lease_version = get_option('lease_version');
			}
			update_post_meta($lease_id, '_yl_lease_version', $lease_version);
			
			

			// Generate lease summary pdf
			yl_generate_pdf($id);
			yl_generate_complete_lease_pdf($id);


				//update previous lease 90 days vacate date
/*				$suite_id = get_post_meta($lease_id, '_yl_product_id', true);
				echo $previous_lease_id = get_previous_lease_id_by_suite_id($suite_id, $lease_id);
				$current_lease_moving_date = get_post_meta($lease_id, '_yl_lease_start_date', true);
				echo "<br />";
				echo $previous_lease_90days_vacate_date = date( "Y-m-d", strtotime("-1 days", strtotime($current_lease_moving_date)) );
				exit;*/
			
			// Set suite vacate notice date to 'null'
			// It will be set again when vacate notice is given
			
			$product_id = get_post_meta($lease_id, '_yl_product_id', true);
			update_post_meta($product_id, '_yl_available', 'No');			
			delete_post_meta($product_id, '_yl_date_vacate_notice_given');
			
			//If previous lease is has ealry vacate addendum or suite is under early vacate addendum
			$suite_id = get_post_meta($lease_id, '_yl_product_id', true);
			$early_vacate_addendum = get_post_meta($suite_id, '_yl_early_vacate_addendum', true);
			if($early_vacate_addendum != ''){
				update_post_meta($suite_id, '_yl_available', 'No');				
				//update_post_meta($suite_id, '_yl_available_date', '');
				update_post_meta($suite_id, '_yl_date_vacate_notice_given', '');
				update_post_meta($suite_id, '_yl_early_vacate_addendum', '');
				
				
				//update previous lease 90 days vacate date
				$previous_lease_id = get_previous_lease_id_by_suite_id($suite_id, $lease_id);
				$current_lease_moving_date = get_post_meta($lease_id, '_yl_lease_start_date', true);
				$previous_lease_90days_vacate_date = date( "Y-m-d", strtotime("-1 days", strtotime($current_lease_moving_date)) );				
				update_post_meta($previous_lease_id, '_yl_ninty_day_vacate_date', $previous_lease_90days_vacate_date);
				//Set previous lease valid date only when new lease signed for same suite.
				update_post_meta($previous_lease_id, '_yl_lease_valid', $previous_lease_90days_vacate_date);
				
				//Old lease moveout date
				$old_lease_moveout_date = get_post_meta($previous_lease_id, '_yl_ninty_day_vacate_date', true);
				$old_lease_moveout_date_timestamp = strtotime($old_lease_moveout_date);
				$old_lease_moveout_date_month = date('n', $old_lease_moveout_date_timestamp);
				//New lease client sign date
				$new_lease_clientsign_date = get_post_meta($lease_id, '_yl_client_signature_date', true);
				$new_lease_clientsign_date_timestamp = strtotime($new_lease_clientsign_date);
				$new_lease_clientsign_date_month = date('n', $new_lease_clientsign_date_timestamp);
				
				
				
				//Credit 1st month rent rate to previous lease client.
				$current_lease_1stmonth_rent_rate = get_post_meta($lease_id, '_yl_first_month_rent_rate', true);
				$current_lease_monthly_rent = get_post_meta($lease_id, '_yl_monthly_rent', true);
				$credit_to_previous_lease = $current_lease_1stmonth_rent_rate - $current_lease_monthly_rent;
				
				$previous_lease_client_id = get_post_meta($previous_lease_id, '_yl_lease_user', true);
				$assoc_clients = SI_Client::get_clients_by_user($previous_lease_client_id);				
				$invoice_client_id = $assoc_clients[0];
		
		
				//Check if moving date is 1st day of month		
				$lease_moving_date_arr = explode("-", $current_lease_moving_date);
				$lease_mov_month = $lease_moving_date_arr[1];
				if( substr($lease_mov_month, 0, 1) == 0 ) {
					$lease_mov_month = substr($lease_mov_month, 1, 1);
				}
				$lease_mov_day = $lease_moving_date_arr[2];
				$lease_mov_year = $lease_moving_date_arr[0];	
						
					
				$suite_number = get_post_meta($previous_lease_id, '_yl_suite_number', true);	
				$data_credit = array(
					'client_id' => (int) $invoice_client_id, // the client id
					'type_id' => (int) 11207, // use the default if one isn't given
					'credit_val' => (float) si_get_number_format( (float) $credit_to_previous_lease ), // creates credit
					'note' => 'Early Vacate Credit from '.$suite_number, // a note
					'date' => (int) current_time( 'timestamp' ), 
					'user_id' => get_current_user_id(), // admin user id that is creating the credit
				);
				
				//Don't apply credit if moving date is 1st day of month and if new cleint sign month and old lease movemout month not same.
				if(($lease_mov_day != 1) && ($old_lease_moveout_date_month == $new_lease_clientsign_date_month))
					$new_credit_id = SI_Account_Credits_Clients::create_associated_credit( $invoice_client_id, $data_credit );				
								
			}//eof if early vacate addendum yes
			
			
			// Redirect to homepage			
			wp_redirect( get_home_url( get_current_blog_id() ) );
			exit;

		} else {
			echo 'Please sign the lease before proceeding';
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
	<div class="lease_summary_container bm-lease-summary-sign">
    	<?php
			if( isset($message) ) {
				echo '<h4>'.$message.'</h4>';
			}
		?>
    	<!--<h2>Lease Summary</h2>-->
        <form action="" method="post" class="sigPad2">
        <div class="lease_details">

        	<div class="yl_timeline_container">
              <div class="yl_timeline_line step_7">
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

                    <div class="yl_unit_block yl_one_third active">
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

                    <div class="yl_unit_block yl_full_width active">
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

            <div class="summary_left">
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


            <div class="summary_right">
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
                        }
                        else {
                            $lease_summary = stripslashes(get_option('lease_summary'));
                        }

                    	//$lease_summary = get_option('lease_summary');
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

						/*$search[] = '%%ServiceCompanyCheckbox%%';
						$replace[] = get_post_meta($lease_id, '_yl_g_email', true);*/

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
						echo '<h2>'.get_post_meta($lease_id, '_yl_addendums', true).'</h2>';
					}
				?>
				
                <p>
                    <label for="date">Date</label>
					<?php
						if(get_post_meta($lease_id, '_yl_bm_signature_date', true))
							$signature_date = get_post_meta($lease_id, '_yl_bm_signature_date', true);
						else	
							$signature_date = date('Y-m-d');	
					?>					
                    <input type="text" class="leasedatepicker" name="date" id="date" value="<?php echo esc_attr($signature_date); ?>"/>
				</p>
                <!--<p>-->

	                <div class="sign_fields">
	                   <p class="drawItDesc">Type <input type="radio" checked="checked" name="sig_type" id="sig_type" value="yes"/> Or Draw <input type="radio" name="sig_type" id="sig_draw" value="No" />your signature</p><!--<br/>-->
	                      
	                      <p><input type="text" name="keypad_sig" id="keypad_sig" value="" placeholder="Type your signature here"/></p>

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
                     $sig = get_post_meta($lease_id, '_yl_bm_signature', true);
                     if($sig) {
                        ?>
                        <div class="sig_out">
                        <img src="<?php echo $sig; ?>" alt="Signature"/>
                        </div>
                     <?php
                    }
                ?>    
				<!--</p>-->

                <!--<hr />-->

                <!--<br/><br/><br/>-->
				<p>By clicking button, you've read the lease summary and proceed to approve.</p>
				<div class="row lease_buttons">
					<div class="col-md-6 text-left">
						<?php
						$previous_link = get_permalink(get_option('yl_tenant_information_page')).'?lid='.$_GET['lid'].'&bmprocess=1&check_payment=1';		
						?>
					  <a href="<?php echo $previous_link; ?>" class="ls_submit ls_button_back">Back</a>
					</div>
					<div class="col-md-6 text-right">
					  <input type="submit" name="lss_submit" id="lss_submit" value="Submit" />
					</div>
				</div>
				
            </div><!-- .summary_right -->
        </div>
        </form>
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
add_shortcode('bm-lease-summary-sign','yl_bm_lease_summary_client_sign_shortcode');



function get_previous_lease_id_by_suite_id($suit_id, $current_lease_id) {
	$args_post = array(
		'post_type'  => 'lease',
		'post_status'  => array( 'publish', 'draft' ),
		'orderby'  => 'ID',
		'posts_per_page' => 2,
		'meta_query' => array(
			array(
				'key'     => '_yl_product_id',
				'value'   => $suit_id,
				'compare' => '=',
			),
		),
	);	
	$leaseID = array();
	$previous_lease_id = 0;
	wp_reset_query();
		
	$lease_post = new WP_Query( $args_post );
	if ( $lease_post->have_posts() ) {
		global $post;
		while ( $lease_post->have_posts() ) : $lease_post->the_post();
			$leaseID[] = $post->ID;
		endwhile;
		wp_reset_query();
	}
	
	//print_r($leaseID);
	
	foreach($leaseID as $lease_get_id){
		if($lease_get_id != $current_lease_id){
			$previous_lease_id = $lease_get_id;
		}
	}
		
	return $previous_lease_id;
}