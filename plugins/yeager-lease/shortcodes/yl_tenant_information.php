<?php
add_shortcode('lease-tenant-information','yl_tenant_information_sc_callback');
function yl_tenant_information_sc_callback($atts, $content = null) {
	ob_start();
	
	if ( !is_user_logged_in() ){
		wp_login_form();
		return;
	}	

	if ($_GET['check_payment']) {
		$lease_id = $_POST['lid'];
		$product_id = yl_get_suite_id_by_lease_id($lease_id);
		update_post_meta($product_id, '_yl_available', 'No');
	}

	if ($_POST) {
		$dir = "/signatures";
		$lease_id = $_POST['lid'];
		$id = $_POST['lid'];
		$signature_date = $_POST['date'];

		$data3 = $_POST['imgOutput3'];
    	$keypad_sig_pw = $_POST['keypad_sig_pw'];

		$data4 = $_POST['imgOutput4'];
    	$keypad_sig_pw_2 = $_POST['keypad_sig_pw_2'];

		// -package waiver- convert text to png image
	    if ($keypad_sig_pw) {
	      $text = $keypad_sig_pw; 
	      $font = YL_ROOT . '/signature/assets/fonts/Precious'; 
	      $font_color = '000'; 
	      $background_color = 'fff'; 
	      $font_size = '20'; 
	      $upload_dir = wp_upload_dir();
	      $signature_pw_dir = $upload_dir['basedir'].$dir;
	      $signature_pw_dir_url = $upload_dir['baseurl'].$dir;

	      if( ! file_exists( $signature_dir ) ){
	        wp_mkdir_p( $signature_dir );
	      }

	      $filename = 'pw-'.time().".png";
	      $filepath = $signature_pw_dir."/".$filename;
	      file_put_contents($filepath, $filename);

	      if (file_exists($filepath)){
	        sig()->text_to_PNG_file($text, $font, $font_color, $background_color, $font_size, $filepath); // call this method from signature class
	        $fileurl = $signature_pw_dir_url."/".$filename;
	        update_post_meta($id, '_yl_client_pw_signature',  $fileurl);
	   
	      } else { 
	          error_log("Cannot create signature file in directory ".$filepath);
	      }
	    }
	    else {

	      if (isset($data3) && is_string($data3) && strrpos($data3, "data:image/png;base64", -strlen($data3)) !== FALSE){
	        $data_pieces = explode(",", $data3);
	        $encoded_image = $data_pieces[1];
	        $decoded_image = base64_decode($encoded_image);

	        $upload_dir = wp_upload_dir();
	        $signature_pw_dir = $upload_dir['basedir'].$dir;
	        $signature_pw_dir_url = $upload_dir['baseurl'].$dir;
	        if( ! file_exists( $signature_pw_dir ) ){
	          wp_mkdir_p( $signature_pw_dir );
	        }
	        $filename = "pw-".time()."_pw.png";
	        $filepath = $signature_pw_dir."/".$filename;

	        if (strlen($decoded_image) > 441 ) { 
	          file_put_contents( $filepath,$decoded_image);

	          if (file_exists($filepath)){
	            // File created : changing posted data to the URL instead of base64 encoded image data
	            $fileurl = $signature_pw_dir_url."/".$filename;
	            update_post_meta($id, '_yl_client_pw_signature',  $fileurl);      
	          } 
	          else { 
	              error_log("Cannot create signature file in directory ".$filepath);
	          } 
	        }      
	      }
	    }

	    if ($keypad_sig_pw_2) {
	      $text = $keypad_sig_pw_2; 
	      $font = YL_ROOT . '/signature/assets/fonts/Precious'; 
	      $font_color = '000'; 
	      $background_color = 'fff'; 
	      $font_size = '20'; 
	      $upload_dir = wp_upload_dir();
	      $signature_pw_dir = $upload_dir['basedir'].$dir;
	      $signature_pw_dir_url = $upload_dir['baseurl'].$dir;

	      if( ! file_exists( $signature_dir ) ){
	        wp_mkdir_p( $signature_dir );
	      }

	      $filename = 'pw-2-'.time().".png";
	      $filepath = $signature_pw_dir."/".$filename;
	      file_put_contents($filepath, $filename);

	      if (file_exists($filepath)){
	        sig()->text_to_PNG_file($text, $font, $font_color, $background_color, $font_size, $filepath); // call this method from signature class
	        $fileurl = $signature_pw_dir_url."/".$filename;
	        update_post_meta($id, '_yl_client_pw_signature_2',  $fileurl);
	   
	      } else { 
	          error_log("Cannot create signature file in directory ".$filepath);
	      }
	    }
	    else {

	      if (isset($data4) && is_string($data4) && strrpos($data4, "data:image/png;base64", -strlen($data4)) !== FALSE){
	        $data_pieces = explode(",", $data4);
	        $encoded_image = $data_pieces[1];
	        $decoded_image = base64_decode($encoded_image);
			
	        $upload_dir = wp_upload_dir();
	        $signature_pw_dir = $upload_dir['basedir'].$dir;
	        $signature_pw_dir_url = $upload_dir['baseurl'].$dir;
	        if( ! file_exists( $signature_pw_dir ) ){
	          wp_mkdir_p( $signature_pw_dir );
	        }
	        $filename = "pw-".time()."_pw_2.png";
	        $filepath = $signature_pw_dir."/".$filename;

	        if (strlen($decoded_image) > 441 ) { 
	          file_put_contents( $filepath,$decoded_image);

	          if (file_exists($filepath)){
	            // File created : changing posted data to the URL instead of base64 encoded image data
	            $fileurl = $signature_pw_dir_url."/".$filename;
	            update_post_meta($id, '_yl_client_pw_signature_2',  $fileurl);      
	          } 
	          else { 
	              error_log("Cannot create signature file in directory ".$filepath);
	          } 
	        }      
	      }
	    }

	    if ( get_post_meta($id, '_yl_client_pw_signature', true) ) {

		} 
	    else {
	    	?>
	    	<div class="yl-alert alert alert-warning">
				<p>Please sign the lease before proceeding</p>
			</div>
			<?php
			//return;
		}

		update_post_meta($lease_id, '_yl_tinfo_copy_machine', 				esc_html($_POST['copy_machine']));
		update_post_meta($lease_id, '_yl_tinfo_user_name', 					esc_html($_POST['user_name']));
		update_post_meta($lease_id, '_yl_tinfo_password', 					esc_html($_POST['password']));
		update_post_meta($lease_id, '_yl_tinfo_postage_password', 			esc_html($_POST['postage_password']));
		update_post_meta($lease_id, '_yl_tinfo_account_number', 			esc_html($_POST['account_number']));
		update_post_meta($lease_id, '_yl_tinfo_fob_1_name', 				esc_html($_POST['fob_1_name']));
		update_post_meta($lease_id, '_yl_tinfo_fob_1_no', 					esc_html($_POST['fob_1_no']));
		update_post_meta($lease_id, '_yl_tinfo_fob_2_name', 				esc_html($_POST['fob_2_name']));
		update_post_meta($lease_id, '_yl_tinfo_fob_2_no', 					esc_html($_POST['fob_2_no']));
		update_post_meta($lease_id, '_yl_tinfo_fob_3_name', 				esc_html($_POST['fob_3_name']));
		update_post_meta($lease_id, '_yl_tinfo_fob_3_no', 					esc_html($_POST['fob_3_no']));
		update_post_meta($lease_id, '_yl_tinfo_email', 						esc_html($_POST['email']));
		update_post_meta($lease_id, '_yl_tinfo_name_nhone', 				esc_html($_POST['name_nhone']));
		update_post_meta($lease_id, '_yl_tinfo_emergency_contact', 			esc_html($_POST['emergency_contact']));
		update_post_meta($lease_id, '_yl_tinfo_corporate_address', 			esc_html($_POST['corporate_address']));
		update_post_meta($lease_id, '_yl_tinfo_billing_contact', 			esc_html($_POST['billing_contact']));
		update_post_meta($lease_id, '_yl_tinfo_name_as_you_wish', 			esc_html($_POST['name_as_you_wish']));
		update_post_meta($lease_id, '_yl_tinfo_suite_numbers', 				esc_html($_POST['suite_numbers']));
		update_post_meta($lease_id, '_yl_tinfo_auth_representative_1', 		esc_html($_POST['auth_representative_1']));
		update_post_meta($lease_id, '_yl_tinfo_print_1', 					esc_html($_POST['print_1']));
		update_post_meta($lease_id, '_yl_tinfo_date_1', 					esc_html($_POST['date_1']));
		update_post_meta($lease_id, '_yl_tinfo_auth_representative_2', 		esc_html($_POST['auth_representative_2']));
		update_post_meta($lease_id, '_yl_tinfo_print_2', 					esc_html($_POST['print_2']));
		update_post_meta($lease_id, '_yl_tinfo_date_2', 					esc_html($_POST['date_2']));		
		update_post_meta($lease_id, '_yl_tinfo_first_find_out_about_us', 	esc_html($_POST['first_find_out_about_us']));
		update_post_meta($lease_id, '_yl_tinfo_main_reason_you_chose', 		esc_html($_POST['main_reason_you_chose']));
		
		yl_generate_tenant_info_pdf($lease_id);

		if ($_POST['bmprocess']) {
			$client_lease_url  = get_permalink(get_option('yl_bm_sign_page'));
			wp_redirect( $client_lease_url."?lid=$lease_id" );
			exit;
		}
		?>

		<div class="alert alert-info yl_alert">
			<p>Thank you for filling all the required information. An e-mail has been sent to both you and the Building Manager with a PDF containing all this info.</p>
		</div>

		<?php
	}
	else {
		$lease_id = $_GET['lid'];
		?>

		<!-- FORM HTML STARTS HERE -->
		<div class="row">
			<div class="col-md-12 lease-tenant-information">
				<div class="info">
					<h3>LOCATIONS:</h3>
					<p>
						Carmel Indiana: 600 E Carmel Dr. / 317-819-8500<br />
						Fishers Indiana: 11650 N Lantern Dr. / 317-576-8560<br />
						II Fishers Indiana: 14074 Trade Center Dr. / 317-774-2000<br />
						Fort Harrison (Lawrence/Indianapolis) Indiana: 9165 Otis Ave. / 317-532-9500<br />
						Greenwood Indiana: 3209 W Smith Valley Rd. / 317-888-5900<br />
						Noblesville Indiana: 23 S 8th St. / 317-774-1958<br />
						Plainfield Indiana: 2680 E Main St. / 317-837-7600
					</p>

					<hr />
					
					<p>
						Frisco Texas: 2770 Main St. / 214-872-3535<br />
						McKinney Texas: 6401 Eldorado Pkwy. / 214-620-2020
					</p>

					<h3>BUILDING HOURS:</h3>
					<ul>
						<li>Building Manager available M-F: 9am &ndash; noon, 1pm &ndash; 5pm</li>
						<li>Front door unlocked M-F: 6:30am-7:00pm; Sat: 6:30am &ndash; 2pm</li>
						<li>Tenant Key fob access: 24/7 (key fobs operate all buildings)</li>
					</ul>
					
					<h3>CONFERENCE ROOMS:</h3>
					<ul>
						<li>Carmel Indiana: Aqua*- seats 20, Fashions*- seats 6, Nations*-seats 6 , #4 &ndash; seats 6</li>
						<li>Fishers Indiana: #1* - seats 20, #2* - seats 8</li>
						<li>II Fishers Indiana: #1* - seats 20, #2* - seats 8, #3 &ndash; seats 8</li>
						<li>Ft. Harrison Indiana: Faces &ndash; seats 20, Passport &ndash; seats 8, Trees &ndash; seats 6, Giraffe &ndash; seats 4</li>
						<li>Greenwood Indiana: #1* - seats 20, #2 &ndash; seats 8, #3 &ndash; seats 6</li>
						<li>Noblesville Indiana: #1* - seats 8</li>
						<li>Plainfield Indiana: #1* - seats 20, Euro* - seats 6, Bamboo* &ndash; seats 6, #4 &ndash; seats 4</li>
						<li>McKinney Texas: Aqua* - seats 20, Lonestar* &ndash; seats 6, Overlook* - seats 4</li>
						<li>Frisco Texas: Aqua* - seats 20, #2* - seats 8, Overlook &ndash; seats* 6, #4 &ndash; seats 6</li>		
					</ul>
					
					<p>
						Available 24/7 (Please observe maximum-use rules, and leave room clean and tidy after use.) Reserve online at yeagerproperties.com with tenant password<br />
						* designates large monitor hookup and internet available
					</p>

					<h3>FEE AMENITIES:</h3>
					<p><strong><u>Copier:</u></strong> 7 cents/copy with tenant password<br />
					<strong><u>Postage:</u></strong> Please see building manager for details<br />
					<strong><u>Fax:</u></strong> 50 cents/page<br />
					<strong><u>Phones:</u></strong> Please see your building manager<br />
					<strong><u>Additional Internet provisions:</u></strong> Static IPs and other options are available<br />
					<strong><u>Keys & Fobs:</u></strong> One building fob, suite, and mailbox key is provided at lease start</p>
							
					<h3>MISCELLANEOUS:</h3>
					<p><strong><u>Deliveries:</u></strong> Building manager signs for incoming tenant packages with tenant authorization<br />
					<strong><u>Signs:</u></strong> Require Lessor approval for interior window signs and hall placards; exterior signs are prohibited<br />
					<strong><u>Internet:</u></strong> One jack per suite is included; wireless access is available.<br />
					<strong><u>Electrical:</u></strong> One 20A circuit is provided per suite<br />
					<strong><u>Maintenance:</u></strong> Please report any maintenance items in your suite or common areas to the building manager<br />
					<strong><u>Janitorial:</u></strong> Please report janitorial deficiencies to the building manager<br />
					<strong><u>Trash:</u></strong> Please place office trash in common dumpster. Please break down boxes. Please do not dump personal items in
					dumpster, or office items in common trash or hallways. Please dump office trash frequently.	</p>		
					
					<h3>PAYMENT OPTIONS:</h3>
					<p>Pay online - monthly invoice email with online payment options. (Add a 3.25% fee for credit card payments)<br />
					Pay onsite or via mail - check, money orders, or cash (requires a receipt)</p>
					
					<h3>IMPORTANT NUMBERS:</h3>
					<p>Public Emergencies - fire, assault, burglary, vandalism, suspicious persons: 911<br />
					After hours non-emergency reporting : Please leave a VM at the Front Desk Main Number<br />
					Tenant Emergencies such as lockouts: a $100/hr. fee, minimum of 1 hr. Carmel: 819-8502 / Greenwood: 884-3108 / Fishers and Ft.<br />
					Harrison: 576-8561 II Fishers : 774-2007 / McKinney and Frisco: (214)620-2022 / Noblesville: 716-4721 / Office Suites West: 837-4919</p>	
				</div>

				<br /><br />
				
				<form action="?lid=<?php echo $_GET['lid']; ?><?php echo (($_GET['bmprocess']) ? '&bmprocess=1' : ''); ?>" method="post" class="form-horizontal tenant-info-form sigPad3" role="form">
					<h3>PASSWORDS:</h3>
				  	<div class="form-group">
						<label class="control-label col-md-3" for="copy_machine">Copy Machine:</label>
						<div class="col-md-9">
							<input type="text" class="form-control" name="copy_machine" id="copy_machine" value="<?php echo esc_attr(get_post_meta($lease_id, '_yl_tinfo_copy_machine', true)); ?>" />
						</div>
			  		</div><!--form-group-->	
				  
				  	<div class="col-md-3"></div>
					<div class="col-md-9">
					  	<p>Website Conference Room Log-in - Meeting Calendar</p>
					</div>

				  	<div class="form-group">
						<label class="control-label col-md-3" for="user_name">User Name:</label>
						<div class="col-md-9">
						  	<input type="text" class="form-control" name="user_name" id="user_name" value="<?php echo esc_attr(get_post_meta($lease_id, '_yl_tinfo_user_name', true)); ?>" />
						</div>
					</div><!--form-group-->	
					<div class="form-group">
						<label class="control-label col-md-3" for="password">Password:</label>
						<div class="col-md-9">
						  <input type="text" class="form-control" name="password" id="password" value="<?php echo esc_attr(get_post_meta($lease_id, '_yl_tinfo_password', true)); ?>" />
						</div>			
				  	</div><!--form-group-->		  
				  
				  	<div class="col-md-3"></div>
					<div class="col-md-9">
					  	<p>Postage Machine</p>
					</div>

				  	<div class="form-group">
						<label class="control-label col-md-3" for="postage_password">Password:</label>
						<div class="col-md-9">
						  	<input type="text" class="form-control" name="postage_password" id="postage_password" value="<?php echo esc_attr(get_post_meta($lease_id, '_yl_tinfo_postage_password', true)); ?>" />
						</div>	
					</div><!--form-group-->	
					<div class="form-group">
						<label class="control-label col-md-3" for="account_number">Account #</label>
						<div class="col-md-9">
						  <input type="text" class="form-control" name="account_number" id="account_number" value="<?php echo esc_attr(get_post_meta($lease_id, '_yl_tinfo_account_number', true)); ?>" />
						</div>					
				  	</div><!--form-group-->	
				  
				  	<div class="col-md-3"></div>
					<div class="col-md-9">
					  	<p>Fobs:</p>
					</div>

					<div class="form-group">
						<label class="control-label col-md-3" for="fob_1_name">Name:</label>
						<div class="col-md-4">
						  	<input type="text" class="form-control" name="fob_1_name" id="fob_1_name" value="<?php echo esc_attr(get_post_meta($lease_id, '_yl_tinfo_fob_1_name', true)); ?>" />
						</div>	
						<label class="control-label  col-md-1" for="fob_1_no">#(s):</label>	
						<div class="col-md-4">
						  	<input type="text" class="form-control" name="fob_1_no" id="fob_1_no" value="<?php echo esc_attr(get_post_meta($lease_id, '_yl_tinfo_fob_1_no', true)); ?>" />
						</div>							
					</div><!--form-group-->			  
				  
					<div class="form-group">
						<label class="control-label col-md-3" for="fob_2_name">Name:</label>
						<div class="col-md-4">
						  	<input type="text" class="form-control" name="fob_2_name" id="fob_2_name" value="<?php echo esc_attr(get_post_meta($lease_id, '_yl_tinfo_fob_2_name', true)); ?>" />
						</div>	
						<label class="control-label  col-md-1" for="fob_2_no">#(s):</label>	
						<div class="col-md-4">
						  	<input type="text" class="form-control" name="fob_2_no" id="fob_2_no" value="<?php echo esc_attr(get_post_meta($lease_id, '_yl_tinfo_fob_2_no', true)); ?>" />
						</div>							
					</div><!--form-group-->			  


					<div class="form-group">
						<label class="control-label col-md-3" for="fob_3_name">Name:</label>
						<div class="col-md-4">
						  	<input type="text" class="form-control" name="fob_3_name" id="fob_3_name" value="<?php echo esc_attr(get_post_meta($lease_id, '_yl_tinfo_fob_3_name', true)); ?>" />
						</div>	
						<label class="control-label  col-md-1" for="fob_3_no">#(s):</label>	
						<div class="col-md-4">
						  	<input type="text" class="form-control" name="fob_3_no" id="fob_3_no" value="<?php echo esc_attr(get_post_meta($lease_id, '_yl_tinfo_fob_3_no', true)); ?>" />
						</div>							
					</div><!--form-group-->	
				
					<div class="col-md-3"></div>
					<div class="col-md-9">
					  	<p>Contact Info:</p>
					</div>

					<div class="form-group">
						<label class="control-label col-md-3" for="email">Email:</label>
						<div class="col-md-9">
						  	<input type="email" class="form-control" name="email" id="email" value="<?php echo esc_attr(get_post_meta($lease_id, '_yl_l_email', true)); ?>" />
						</div>
					</div><!--form-group-->		

					<div class="form-group">
						<label class="control-label col-md-3" for="name_nhone">Name/Phone:</label>
						<div class="col-md-9">
						  	<input type="text" class="form-control" name="name_nhone" id="name_nhone" value="<?php echo esc_attr(get_post_meta($lease_id, '_yl_l_first_name', true)); ?> <?php echo esc_attr(get_post_meta($lease_id, '_yl_l_last_name', true)); ?> / <?php echo esc_attr(get_post_meta($lease_id, '_yl_l_phone', true)); ?>" />
						</div>
					</div><!--form-group-->	

					<div class="form-group">
						<label class="control-label col-md-3" for="emergency_contact">Emergency Contact (Name &amp; Phone):</label>
						<div class="col-md-9">
						  	<input type="text" class="form-control" name="emergency_contact" id="emergency_contact" value="<?php echo esc_attr(get_post_meta($lease_id, '_yl_tinfo_emergency_contact', true)); ?>" />
						</div>
					</div><!--form-group-->		

					<div class="form-group">
						<label class="control-label col-md-3" for="corporate_address">Tenant/Corporate Address:</label>
						<div class="col-md-9">
						  	<input type="text" class="form-control" name="corporate_address" id="corporate_address" value="<?php echo esc_attr(get_post_meta($lease_id, '_yl_l_street_address', true)); ?>, <?php echo esc_attr(get_post_meta($lease_id, '_yl_l_address_line_2', true)); ?>, <?php echo esc_attr(get_post_meta($lease_id, '_yl_l_city', true)); ?>, <?php echo esc_attr(get_post_meta($lease_id, '_yl_l_state', true)); ?>, <?php echo esc_attr(get_post_meta($lease_id, '_yl_l_zip_code', true)); ?>" />
						</div>
					</div><!--form-group-->		

					<div class="form-group">
						<label class="control-label col-md-3" for="billing_contact">Billing/Corporate Contact/Phone:</label>
						<div class="col-md-9">
						  	<input type="text" class="form-control" name="billing_contact" id="billing_contact" value="<?php echo esc_attr(get_post_meta($lease_id, '_yl_tinfo_billing_contact', true)); ?>" />
						</div>
					</div><!--form-group-->			
				
					<div class="col-md-3"></div>
					<div class="col-md-9 alert alert-info">
				  		<p>WIFI CODES: <strong>livelife</strong></p>
					</div>

					<hr>			

					<h3>BUILDING TENANT DIRECTORY:</h3>
				
					<div class="form-group">
						<label class="control-label col-md-3" for="name_as_you_wish">Name as you wish it to appear:</label>
						<div class="col-md-9">
							  <input type="text" class="form-control" name="name_as_you_wish" id="name_as_you_wish" value="<?php echo esc_attr(get_post_meta($lease_id, '_yl_tinfo_name_as_you_wish', true)); ?>" />
							  <small>(Recommend less than 20 characters; 50 maximum)</small>
						</div>
					</div><!--form-group-->	

					<div class="form-group">
						<label class="control-label col-md-3" for="suite_numbers">Suite #(s):</label>
						<div class="col-md-9">
						  	<input type="text" class="form-control" name="suite_numbers" id="suite_numbers" value="<?php echo esc_attr(get_post_meta($lease_id, '_yl_suite_number', true)); ?>" />
						</div>
					</div><!--form-group-->					
				
					<hr>


					<!-- Package Waiver -->
				    <div class="row signature_info clear">
				        
				        <div class="col-md-12">
				          <h3>PACKAGE DELIVERY WAIVER:</h3>
				        </div>

				        <div class="col-md-12">  
				          <p>I authorize representatives of Yeager Properties to receive package deliveries for suite(s) <strong><?php echo get_post_meta($lease_id, '_yl_suite_number', true); ?></strong>.<br>
				            I am an authorized representative for Lessee.  I release Yeager Properties from all liability related to package delivery loss and damage.</p>
				        </div>      

				    </div>
					
				    <!-- -->						

					<div class="form-group">
					<hr />
						<label class="control-label col-md-3" for="first_find_out_about_us">How did you first find out about us?</label>
						<div class="col-md-9">
						  	<input type="text" class="form-control" name="first_find_out_about_us" id="first_find_out_about_us" value="<?php echo esc_attr(get_post_meta($lease_id, '_yl_tinfo_first_find_out_about_us', true)); ?>" />
						</div>
					</div><!--form-group-->		
				
					<div class="form-group">
						<label class="control-label col-md-3" for="main_reason_you_chose">What is the main reason you chose Yeager Office Suites?</label>
						<div class="col-md-9">
						  	<input type="text" class="form-control" name="main_reason_you_chose" id="main_reason_you_chose" value="<?php echo esc_attr(get_post_meta($lease_id, '_yl_tinfo_main_reason_you_chose', true)); ?>" />
						</div>
					</div><!--form-group-->		
					
					<hr>
					
					<div class="col-md-5 authorized_representative_1">
						<div class="form-group">
							<label class="control-label" for="auth_representative_1">Authorized Representative Signature #1:</label>
							<input type="text" class="form-control" name="auth_representative_1" id="auth_representative_1" value="<?php echo esc_attr(get_post_meta($lease_id, '_yl_tinfo_auth_representative_1', true)); ?>" />	
							<label class="control-label" for="date_1">Date:</label>	
							 <input type="text" class="form-control datepicker" name="date_1" id="date_1" value="<?php echo date('Y-m-d'); ?>" />									
						</div><!--form-group-->		
						
				          <div class="sign_fields_pw">
				            <p class="drawItDesc">Type <input type="radio" checked="checked" name="sig_type_pw" id="sig_type_pw" class="sig_type_pw" value="yes"/> Or Draw <input type="radio" name="sig_type_pw" id="sig_draw_pw" class="sig_draw_pw" value="No" />your signature</p><!--<br/>-->

				            <p><input type="text" name="keypad_sig_pw" id="keypad_sig_pw" class="keypad_sig_input" value="" placeholder="Type your signature here"/></p>

				            <div class="draw_wrap_pw" style="display:none;">

				              <ul class="sigNav">
				                <li class="drawIt"><a href="#draw-it">Draw It</a></li>
				                <li class="clearButton"><a href="#clear">Clear</a></li>
				              </ul>

				              <div class="sig sigWrapper sigPad3_canvas">
				                <canvas class="pad signature_pad" width="340" height="100"></canvas>
				                <input type="hidden" name="imgOutput3" class="imgOutput3" value="">
				              </div>

				            </div>  
				          </div>

				          <?php
				          $sig = get_post_meta($lease_id, '_yl_client_pw_signature', true); 
				          if($sig) {
				            ?>
				            <div class="sig_out_pw">
				              <img src="<?php echo $sig; ?>" alt="Signature"/>
				            </div>
				          <?php    
				          }
				          ?>										
					</div><!--authorized_representative_1-->
					<div class="col-md-2">&nbsp;</div>
					<div class="col-md-5 authorized_representative_2">
						<div class="form-group">
							<label class="control-label" for="auth_representative_2">Authorized Representative Signature #2:</label>
							<input type="text" class="form-control" name="auth_representative_2" id="auth_representative_2" value="<?php echo esc_attr(get_post_meta($lease_id, '_yl_tinfo_auth_representative_2', true)); ?>" />
							<label class="control-label" for="date_2">Date:</label>	
							<input type="text" class="form-control datepicker" name="date_2" id="date_2" value="<?php echo date('Y-m-d'); ?>" />									
						</div><!--form-group-->		
						
				          <div class="sign_fields_pw_2">
				            <p class="drawItDesc">Type <input type="radio" checked="checked" name="sig_type_pw" id="sig_type_pw" class="sig_type_pw" value="yes"/> Or Draw <input type="radio" name="sig_type_pw" id="sig_draw_pw" class="sig_draw_pw" value="No" />your signature</p><!--<br/>-->

				            <p><input type="text" name="keypad_sig_pw_2" id="keypad_sig_pw_2" class="keypad_sig_input" value="" placeholder="Type your signature here"/></p>

				            <div class="draw_wrap_pw" style="display:none;">

				              <ul class="sigNav">
				                <li class="drawIt"><a href="#draw-it">Draw It</a></li>
				                <li class="clearButton"><a href="#clear">Clear</a></li>
				              </ul>

				              <div class="sig sigWrapper sigPad4_canvas">
				                <canvas class="pad signature_pad" width="340" height="100"></canvas>
				                <input type="hidden" name="imgOutput4" class="imgOutput4" value="">
				              </div>

				            </div>  
				          </div>

				          <?php
				          $sig = get_post_meta($lease_id, '_yl_client_pw_signature_2', true);
				          if($sig) {
				            ?>
				            <div class="sig_out_pw">
				              <img src="<?php echo $sig; ?>" alt="Signature"/>
				            </div>
				          <?php    
				          }
				          ?>											
					</div><!--authorized_representative_2-->
					
					
										  
					<div class="row clear">
						<div class="form-group">
						<br  /><br />
							<div class="col-md-6 text-left">
								<?php
								//$previous_link = get_permalink(get_option('yl_client_sign_page')).'?lid='.$_GET['lid'].'&bmprocess=1';
								$previous_link = get_permalink(get_option('yl_lease_checkout_page')).'?lid='.$_GET['lid'];
								$previous_link .= '&redirect='.urlencode(get_permalink(get_option('yl_tenant_information_page')).'?lid='.$_GET['lid'].'&bmprocess=1');			
								?>
							  <a href="<?php echo $previous_link; ?>" class="ls_submit ls_button_back">Back</a>
							</div>						
							<div class="col-md-6 text-right">
								<input type="hidden" name="lid" value="<?php echo $_GET['lid']; ?>">
								<?php
								if ($_GET['bmprocess']) {
									?>
									<input type="hidden" name="bmprocess" value="1">
									<?php
								}
								?>
								<input type="submit" name="submit" value="Submit" class="btn btn-primary">
							</div>
						</div><!--form-group-->	
					</div>					  
				  	
				</form>
			
			</div>	<!--lease-tenant-information-->
		</div>
		<!-- FORM HTML ENDS HERE -->

		<script type="text/javascript">
	      	// Type Or Draw  
		    jQuery(document).ready(function() {
		      
		      jQuery(".sig_type_pw").click(function() {
		        if(jQuery(this).prop("checked") == true ) {
		          jQuery(this).parent().siblings().children(".keypad_sig_input").show();
		           jQuery(this).parent().siblings(".draw_wrap_pw").hide();
		        } 
		      });

		      jQuery(".sig_draw_pw").click(function() {
		        if(jQuery(this).prop("checked") == true ) {
		          jQuery(this).parent().siblings(".draw_wrap_pw").show();
		           jQuery(this).parent().siblings().children(".keypad_sig_input").hide();
		        } 
		      });

		    });

		</script>

		<?php
	}

	$to_return = ob_get_contents();
	ob_end_clean();	
	return $to_return;
}
