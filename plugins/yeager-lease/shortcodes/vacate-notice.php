<?php
//$receiver_type = building_manager/client
//$notification_step = vstep1/Tenant Initiates Vacate (No Early vacate chosen):
//$notification_step = vstep2/Tenant Initiates Vacate (Early vacte chosen):

//$initiates_by = bm/client
function send_vacate_notice_addendum_notification($lease_id, $suite_id, $receiver_type, $notification_step, $vacate_pdf = ''){

	$initiates_by = get_post_meta($lease_id, '_yl_va_initiates_by', true);

 	$email_subject = get_option('bm_va_email_subject');
	$email_message = get_option('bm_va_email_message');

 	$con_email_subject = get_option('bm_va_con_email_subject');
	$con_email_message = get_option('bm_va_con_email_message');

	$headers  = 'MIME-Version: 1.0' . "\r\n";
	$headers .= 'Content-type: text/plain; charset=iso-8859-1' . "\r\n";
	add_filter('wp_mail_content_type',create_function('', 'return "text/html"; '));

	$lease_user_id = get_post_meta($lease_id, '_yl_lease_user', true);
	$lease_user_info = get_userdata($lease_user_id);
	$lease_first_name = $lease_user_info->first_name;
	$lease_last_name = $lease_user_info->last_name;
	$lease_user_email = $lease_user_info->user_email;

	$lease_bm_user_id = get_post_meta($lease_id, '_yl_author_id', true);
	$lease_bm_user_info = get_userdata($lease_bm_user_id);
	$lease_bm_first_name = $lease_bm_user_info->first_name;
	$lease_bm_last_name = $lease_bm_user_info->last_name;
	$lease_bm_user_email = $lease_bm_user_info->user_email;


	$vacate_notice_page_url  = get_permalink(get_option('yl_vacate_notice_page'));
	$vacate_notice_default_page_url  =  add_query_arg( 'lid', $lease_id, $vacate_notice_page_url );

	$lease_suite = get_post_meta($lease_id, '_yl_suites_leased', true);


	//Email sent to BM with No early vacate and initiate by client
	if(($receiver_type == 'building_manager') && (($notification_step == 'vstep1') || ($notification_step == 'vstep2'))){
		$search = array();
		$replace = array();
		$search[] = '%%name%%';
		$replace[] = $lease_bm_first_name;
		$search[] = '%%vacate-sign-url%%';
		$replace[] = $vacate_notice_default_page_url;
		$get_message = str_replace($search, $replace, $email_message);
		$get_message = 	stripslashes($get_message);
		$get_message = nl2br($get_message);
		//$lease_bm_user_email = 'anasbinmukim@gmail.com';
		@wp_mail( $lease_bm_user_email, $email_subject, $get_message, $headers );

	}

	//notification sent to Client
	if(($receiver_type == 'client') && (($notification_step == 'vstep1') || ($notification_step == 'vstep2'))){
		//Sent to client
		$search = array();
		$replace = array();
		$search[] = '%%name%%';
		$replace[] = $lease_first_name;
		$search[] = '%%vacate-sign-url%%';
		$replace[] = $vacate_notice_default_page_url;
		$get_message = str_replace($search, $replace, $email_message);
		$get_message = 	stripslashes($get_message);
		$get_message = nl2br($get_message);
		//$lease_user_email = 'anasbinmukim@gmail.com';
		@wp_mail( $lease_user_email, $email_subject, $get_message, $headers );

	}

	//Notification sent to BM and Tenent after confirmation
	if(($receiver_type == 'client_bm') && (($notification_step == 'vstep1') || ($notification_step == 'vstep2'))){
		//Sent to client
		$search = array();
		$replace = array();
		$search[] = '%%suite-name%%';
		$replace[] = $lease_suite;
		$search[] = '%%name%%';
		$replace[] = $lease_first_name;
		$get_message = str_replace($search, $replace, $con_email_message);
		$get_message = 	stripslashes($get_message);
		$get_message = nl2br($get_message);
		//$lease_user_email = 'anasbinmukim@gmail.com';
		@wp_mail( $lease_user_email, $con_email_subject, $get_message, $headers, array($vacate_pdf) );


		//Sent to BM
		$search = array();
		$replace = array();
		$search[] = '%%suite-name%%';
		$replace[] = $lease_suite;
		$search[] = '%%name%%';
		$replace[] = $lease_bm_first_name;
		$get_message = str_replace($search, $replace, $con_email_message);
		$get_message = 	stripslashes($get_message);
		$get_message = nl2br($get_message);
		//$lease_bm_user_email = 'anasbinmukim@gmail.com';
		@wp_mail( $lease_bm_user_email, $con_email_subject, $get_message, $headers, array($vacate_pdf) );

	}


}



// Specific user data list
add_shortcode('vacate-notice', 'yl_vacate_notice');
function yl_vacate_notice($content = null) {

	ob_start();
	$lease_id = '';


	if ( ! is_user_logged_in() ){
		echo "Please login to your account and visit my account page";
		return;
	}

	if(current_user_can( 'lease_client' )){
		$class_sig_pad_form = 'sigPadClient';
	}
	if(current_user_can( 'building_manager' )){
		$class_sig_pad_form = 'sigPadBM';
	}
	if(current_user_can( 'manage_options' )){
		$class_sig_pad_form = 'sigPad3';
	}

	echo '<style type="text/css">
			.form-control { max-width: 300px; }
			.warning_message{ margin: 20px 0 20px 0; padding:10px; }
			p.warning_message:last-of-type{ padding:10px; }
			.warning_message_date{ margin: 20px 0 20px 0; padding:10px; }
			p.warning_message_date:last-of-type{ padding:10px; }
			.sigWrapper.current{float:none;}
		</style>';

	if(isset($_GET['lid']) && isset($_GET['view_early_vacate_addendum'])){
		$lease_id = $_GET['lid'];
		$suite_id = get_post_meta($lease_id, '_yl_product_id', true);
		echo '<p>&nbsp;</p>';
		echo "<h2>Early Vacate Addendum</h2>";
        echo '<div class="lease_summary_content">';
                $early_vacate = get_option('early_vacate_addendum');
                $search = array();
                $replace = array();

                $search[] = '%%Suite%%';
                $replace[] = get_the_title($suite_id);

                $search[] = '%%Location%%';
                $replace[] = get_post_meta($lease_id, '_yl_location', true);

                $summary = str_replace($search, $replace, $early_vacate);
                $summary = stripslashes($summary);
                $summary = nl2br($summary);
                echo $summary;

        echo '</div>';
	    echo '<p>&nbsp;</p>';

		return;
	}

	$vacate_notice_page_url  = get_permalink(get_option('yl_vacate_notice_page'));
	if(isset($_GET['lid'])){
		$subs_id = $_GET['lid'];
		$lease_id = $_GET['lid'];
		$vacate_notice_default_page_url  =  add_query_arg( 'lid', $subs_id, $vacate_notice_page_url );
	}

	if(isset($_GET['step1'])){
		if( (get_post_meta($lease_id, '_yl_va_client_signature', true) == '') && get_post_meta($lease_id, '_yl_va_initiates_by', true) ) {
			echo '<p class="bg-danger warning_message">Waiting for client signature!</p>';
		}
		if( (get_post_meta($lease_id, '_yl_va_bm_signature', true) == '') && get_post_meta($lease_id, '_yl_va_initiates_by', true) ) {
			echo '<p class="bg-danger warning_message">Waiting for Building Manager signature!</p>';
		}

	}else{
		if( (get_post_meta($lease_id, '_yl_vn_client_signature', true) == '') && get_post_meta($lease_id, '_yl_va_initiates_by', true) ) {
			echo '<p class="bg-danger warning_message">Waiting for client signature!</p>';
		}
		if( (get_post_meta($lease_id, '_yl_vn_bm_signature', true) == '') && get_post_meta($lease_id, '_yl_va_initiates_by', true) ) {
			echo '<p class="bg-danger warning_message">Waiting for Building Manager signature!</p>';
		}
	}


	if( isset($_POST['vacate_submit']) ) {
		
		$user_id = get_current_user_id();
		$lease_id = $_POST['lease_id'];
		$lease_meta = get_post_meta($lease_id);
		$suite_id = get_post_meta($lease_id, '_yl_product_id', true);

		$date_vacate = isset($_POST['move_out_date']) ? $_POST['move_out_date']:'';
		
		
		$current_vacate_signup_date = date('Y-m-d');
		$date_select_min = '';
		//$current_vacate_signup_date = '2016-08-03';
		if((get_post_meta($lease_id, '_yl_suite_number', true) == 'Y-Membership') || (get_post_meta($lease_id, '_yl_product_id', true) == -1) || (get_post_meta($lease_id, '_yl_product_id', true) == '') ){
			$day_90_vacate_actual_date = date( "Y-m-d", strtotime("+90 days", strtotime($current_vacate_signup_date)) );
			$date_select_min = 90;
		
		}else{
			$day_90_vacate_actual_date = date( "Y-m-d", strtotime("+30 days", strtotime($current_vacate_signup_date)) );
			$date_select_min = 30;
			
		}
		
		$day_90_vacate_date =  date("Y-m-d", strtotime($day_90_vacate_actual_date));
		if($date_vacate >= $day_90_vacate_date) {
		
	
		

	  // signature convert an image and send to the wp signature directory
		$dir = "/signatures";

		$data3 = $_POST['imgOutput3'];
		$keypad_sig_pw = $_POST['keypad_sig_pw'];

		$data4 = $_POST['imgOutput4'];
		$keypad_sig_pw_2 = $_POST['keypad_sig_pw_2'];


		//Client Signature
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
			update_post_meta($lease_id, '_yl_vn_client_signature',  $fileurl);
			update_post_meta($lease_id, '_yl_vn_client_signature_date', $_POST['client_sig_date']);

	      } else {
	          error_log("Cannot create signature file in directory ".$filepath);
	      }
	    }elseif (isset($data3) && is_string($data3) && strrpos($data3, "data:image/png;base64", -strlen($data3)) !== FALSE){
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
				update_post_meta($lease_id, '_yl_vn_client_signature',  $fileurl);
				update_post_meta($lease_id, '_yl_vn_client_signature_date', $_POST['client_sig_date']);
	          }
	          else {
	              error_log("Cannot create signature file in directory ".$filepath);
	          }
	        }
	      }

		//BM Signature
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
			update_post_meta($lease_id, '_yl_vn_bm_signature',  $fileurl);
			update_post_meta($lease_id, '_yl_vn_bm_signature_date', $_POST['bm_sig_date']);

	      } else {
	          error_log("Cannot create signature file in directory ".$filepath);
	      }
	    }elseif(isset($data4) && is_string($data4) && strrpos($data4, "data:image/png;base64", -strlen($data4)) !== FALSE){
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
				update_post_meta($lease_id, '_yl_vn_bm_signature',  $fileurl);
				update_post_meta($lease_id, '_yl_vn_bm_signature_date', $_POST['bm_sig_date']);
	          }
	          else {
	              error_log("Cannot create signature file in directory ".$filepath);
	          }
	        }
	      }



		if( get_post_meta($lease_id, '_yl_vn_client_signature', true) || get_post_meta($lease_id, '_yl_vn_bm_signature', true) ) {
			// Send notification to the BM
			update_post_meta($lease_id, '_yl_va_lessee', esc_html($_POST['va_lessee']));
			update_post_meta($lease_id, '_yl_date_vacate_notice_given', esc_html($_POST['date_vacate_notice_given']));
			update_post_meta($lease_id, '_yl_ninty_day_vacate_date', esc_html($_POST['move_out_date']));
			update_post_meta($lease_id, '_yl_suites_leased', esc_html($_POST['suites_leased']));
			update_post_meta($lease_id, '_yl_suites_identified_agreement', esc_html($_POST['suites_identified_agreement']));
			update_post_meta($lease_id, '_yl_all_n_demand_multiple_suites', esc_html($_POST['all_n_demand_multiple_suites']));
			update_post_meta($lease_id, '_yl_tenant_contact_email', esc_html($_POST['tenant_contact_email']));
			update_post_meta($lease_id, '_yl_va_cell_phone', esc_html($_POST['va_cell_phone']));
			update_post_meta($lease_id, '_yl_tenant_forwarding_address', esc_html($_POST['tenant_forwarding_address']));

			update_post_meta($lease_id, '_yl_va_building', esc_html($_POST['va_building']));
			update_post_meta($lease_id, '_yl_va_suite_number', esc_html($_POST['va_suite_number']));
			update_post_meta($lease_id, '_yl_va_business_name', esc_html($_POST['va_business_name']));
			update_post_meta($lease_id, '_yl_va_security_deposit_held', esc_html($_POST['va_security_deposit_held']));


			if(current_user_can( 'lease_client' ) && !get_post_meta($lease_id, '_yl_va_initiates_by', true)){
	  			update_post_meta($lease_id, '_yl_va_initiates_by', 'client');
			}

			if(current_user_can( 'building_manager' ) && !get_post_meta($lease_id, '_yl_va_initiates_by', true)){
				update_post_meta($lease_id, '_yl_va_initiates_by', 'bm');
			}


			$product_name = get_the_title($suite_id);
			//$move_out_date = $_POST['ninty_day_vacate_date'];

			$move_out_date = $_POST['move_out_date'];	
			
					

			$available_date = date( "Y-m-d", strtotime("+1 days", strtotime($move_out_date)) );
			//$available_date = date( "Y-m-d", strtotime($move_out_date) );
			$move_out_date = date( "Y-m-d", strtotime($move_out_date) );
			update_post_meta($suite_id, '_yl_available_date', $available_date);
			update_post_meta($suite_id, '_yl_available', 'Yes');
			update_post_meta($suite_id, '_yl_date_vacate_notice_given', $move_out_date);

		} else {
			echo '<p class="bg-danger warning_message">Please Add Your Signature Before Proceeding</p>';
			?>
			<script type="text/javascript">
				window.location = "<?php echo $vacate_notice_default_page_url; ?>";
			</script>
			<?php
			//return;
		}

		$initiates_by = get_post_meta($lease_id, '_yl_va_initiates_by', true);

		if ( !isset($_POST['move_in_someone']) ) {
			$product_name = get_the_title($suite_id);
			$move_out_date = $_POST['move_out_date'];
			$move_out_date = date( "Y-m-d", strtotime($move_out_date) );
			$available_date = date( "Y-m-d", strtotime("+1 days", strtotime($move_out_date)) );
			update_post_meta($suite_id, '_yl_available_date', $available_date);
			update_post_meta($suite_id, '_yl_available', 'Yes');

			$move_out_submitted = true;

			update_post_meta($suite_id, '_yl_date_vacate_notice_given', $available_date);

			if(current_user_can( 'lease_client' )){
				$receiver_type = 'building_manager';
				$notification_step = 'vstep1';
			}

			if(current_user_can( 'building_manager' )){
				$receiver_type = 'client';
				$notification_step = 'vstep1';
			}

			$generated_pdf = '';
			//Send notification
			if( get_post_meta($lease_id, '_yl_vn_client_signature', true) && get_post_meta($lease_id, '_yl_vn_bm_signature', true) ) {
				$generated_pdf = generate_vacate_notice_pdf($lease_id);
				$receiver_type = 'client_bm';
			}

			send_vacate_notice_addendum_notification($lease_id, $suite_id, $receiver_type, $notification_step, $generated_pdf);

			$success_redirect_1  =  add_query_arg( 'vstep1', 'yes', $vacate_notice_default_page_url );
			$success_redirect  =  add_query_arg( 'receiver_type', $receiver_type, $success_redirect_1 );


			// Redirect to thank you page
			//$thank_you_page = get_option('yl_thank_you_page');
			?>
			<script type="text/javascript">
				window.location = "<?php echo $success_redirect; ?>";
			</script>
			<?php
			//break;
		}else{
			//$show_page_3 = true;
			//$suite_id = $suite_id;

			$generated_pdf = '';
			//Send notification
			if( get_post_meta($lease_id, '_yl_vn_client_signature', true) && get_post_meta($lease_id, '_yl_vn_bm_signature', true) ) {
				$generated_pdf = generate_vacate_notice_pdf($lease_id);
				$receiver_type = 'client_bm';
			}
			send_vacate_notice_addendum_notification($lease_id, $suite_id, $receiver_type, $notification_step, $generated_pdf);

			$suite_id = get_post_meta($lease_id, '_yl_product_id', true);
			$step1_redirect_1  =  add_query_arg( 'step1', 'yes', $vacate_notice_default_page_url );
			$step1_redirect  =  add_query_arg( 'suite_id', $suite_id, $step1_redirect_1 );

			?>
			<script type="text/javascript">
				window.location = "<?php echo $step1_redirect; ?>";
			</script>
			<?php
			//break;
			}
		
		}else{
		
		
			echo '<p class="bg-danger warning_message_date">Invalid Date Please fill next '.$date_select_min.' days !</p>';
		
		}

	}
	
	


	//Proceed early vacate addendum
	if( isset($_POST['vn_submit']) ) {

		$lease_id = $_POST['lease_id'];

		$lease_meta = get_post_meta($lease_id);
		//$suite_id = $lease_meta['_yl_product_id'][0];
		$suite_id = get_post_meta($lease_id, '_yl_product_id', true);

	  // signature convert an image and send to the wp signature directory
		$dir = "/signatures";

		$data3 = $_POST['imgOutput3'];
		$keypad_sig_pw = $_POST['keypad_sig_pw'];

		$data4 = $_POST['imgOutput4'];
		$keypad_sig_pw_2 = $_POST['keypad_sig_pw_2'];

		//Client Signature
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
			update_post_meta($lease_id, '_yl_va_client_signature',  $fileurl);
			update_post_meta($lease_id, '_yl_va_client_signature_date', $_POST['client_sig_date']);

	      } else {
	          error_log("Cannot create signature file in directory ".$filepath);
	      }
	    }elseif (isset($data3) && is_string($data3) && strrpos($data3, "data:image/png;base64", -strlen($data3)) !== FALSE){
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
				update_post_meta($lease_id, '_yl_va_client_signature',  $fileurl);
				update_post_meta($lease_id, '_yl_va_client_signature_date', $_POST['client_sig_date']);
	          }
	          else {
	              error_log("Cannot create signature file in directory ".$filepath);
	          }
	        }
	      }

		//BM Signature
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
			update_post_meta($lease_id, '_yl_va_bm_signature',  $fileurl);
			update_post_meta($lease_id, '_yl_va_bm_signature_date', $_POST['bm_sig_date']);

	      } else {
	          error_log("Cannot create signature file in directory ".$filepath);
	      }
	    }elseif(isset($data4) && is_string($data4) && strrpos($data4, "data:image/png;base64", -strlen($data4)) !== FALSE){
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
				update_post_meta($lease_id, '_yl_va_bm_signature',  $fileurl);
				update_post_meta($lease_id, '_yl_va_bm_signature_date', $_POST['bm_sig_date']);
	          }
	          else {
	              error_log("Cannot create signature file in directory ".$filepath);
	          }
	        }
	      }







		$suite_id = get_post_meta($lease_id, '_yl_product_id', true);

		update_post_meta($lease_id, '_yl_early_vacate_addendum', 'yes');
		update_post_meta($suite_id, '_yl_early_vacate_addendum', 'yes');

		//$available_date = date( "Y-m-d" );
		//update_post_meta($suite_id, '_yl_available_date', $available_date);
		update_post_meta($suite_id, '_yl_available', 'Yes');



		//Send notification
		if(current_user_can( 'lease_client' )){
			$receiver_type = 'building_manager';
			$notification_step = 'vstep2';
		}

		if(current_user_can( 'building_manager' )){
			$receiver_type = 'client';
			$notification_step = 'vstep2';
		}

		$generated_pdf = '';
		//Send notification
		if( get_post_meta($lease_id, '_yl_va_client_signature', true) && get_post_meta($lease_id, '_yl_va_bm_signature', true) ) {
			$generated_pdf = generate_early_vacate_addendum_pdf($lease_id);
			$receiver_type = 'client_bm';
		}
		send_vacate_notice_addendum_notification($lease_id, $suite_id, $receiver_type, $notification_step, $generated_pdf);

		$success_redirect_2  =  add_query_arg( 'vstep2', 'yes', $vacate_notice_default_page_url );
		$success_redirect  =  add_query_arg( 'receiver_type', $receiver_type, $success_redirect_2 );

		echo '<script type="text/javascript">window.location = "'.$success_redirect.'";</script>';

	}



	//step 1
	if(isset($_GET['receiver_type']) && ($_GET['receiver_type'] == 'client') && ($_GET['vstep1'] == 'yes') ){
		echo "<br /><br /><h2>Notification has been successfully sent!</h2>";
	}elseif(isset($_GET['receiver_type']) && ($_GET['receiver_type'] == 'building_manager') && ($_GET['vstep1'] == 'yes') ){
		echo "<br /><br /><h2>Notification has been successfully sent!</h2>";
	}elseif(isset($_GET['receiver_type']) && ($_GET['receiver_type'] == 'client_bm') && ($_GET['vstep1'] == 'yes') ){
		echo "<br /><br /><h2>Notification has been successfully sent!</h2>";
		if(current_user_can( 'lease_client' )){
			echo '<p>Thank you for your time with us. We wish you the best. Please let us know if we can help in the future!</p>';
		}

	}elseif(isset($_GET['receiver_type']) && ($_GET['receiver_type'] == 'client') && ($_GET['vstep2'] == 'yes') ){
		echo "<br /><br /><h2>Notification has been successfully sent!</h2>";
	}elseif(isset($_GET['receiver_type']) && ($_GET['receiver_type'] == 'building_manager') && ($_GET['vstep2'] == 'yes') ){
		echo "<br /><br /><h2>Notification has been successfully sent!</h2>";
	}elseif(isset($_GET['receiver_type']) && ($_GET['receiver_type'] == 'client_bm') && ($_GET['vstep2'] == 'yes') ){
		echo "<br /><br /><h2>Notification has been successfully sent!</h2>";
		if(current_user_can( 'lease_client' )){
			echo '<p>Thank you for your time with us. We wish you the best. Please let us know if we can help in the future!</p>';
		}
	}elseif(isset($_GET['step1'])){
		$suite_id = $_GET['suite_id'];
		$lease_id = $_GET['lid'];
		?>

        <div class="lease_summary_content">
            <?php
                $early_vacate = get_option('early_vacate_addendum');
				$suite_id = $suite_id;
                $search = array();
                $replace = array();

                $search[] = '%%Suite%%';
                $replace[] = esc_html(get_the_title($suite_id));

                $search[] = '%%Location%%';
                $replace[] = esc_html(get_post_meta($lease_id, '_yl_location', true));

                $summary = str_replace($search, $replace, $early_vacate);
                $summary = stripslashes($summary);
                $summary = nl2br($summary);
                echo $summary;
            ?>
        </div>

	    <p>&nbsp;</p>

	    <form action="" method="post" class="<?php echo $class_sig_pad_form; ?>">
			<?php if(current_user_can( 'lease_client' )){ ?>
	        <p>
	            <label for="date">Client Signature Date</label>
	            <?php
	                if(get_post_meta($lease_id, '_yl_va_client_signature_date', true))
	                    $c_signature_date = get_post_meta($lease_id, '_yl_va_client_signature_date', true);
	                else
	                    $c_signature_date = date('Y-m-d');
	            ?>
	            <input type="text" class="leasedatepicker form-control" name="client_sig_date" id="client_sig_date" value="<?php echo esc_attr($c_signature_date); ?>"/>
	        </p>

			<div class="sign_fields">
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
	             $sig = get_post_meta($lease_id, '_yl_va_client_signature', true);
	             if($sig) {
	                ?>
	                <div class="sig_out">
	                <img src="<?php echo $sig; ?>" alt="Signature"/>
	                </div>
	             <?php
	            }
	        ?>
	        </div>

			<?php }//eof if client logged in signature ?>


			<?php if(current_user_can( 'building_manager' )){ ?>
			<br /><br />
	        <p>
	            <label for="date">BM Signature Date</label>
	            <?php
	                if(get_post_meta($lease_id, '_yl_va_bm_signature_date', true))
	                    $bm_signature_date = get_post_meta($lease_id, '_yl_va_bm_signature_date', true);
	                else
	                    $bm_signature_date = date('Y-m-d');
	            ?>
	            <input type="text" class="leasedatepicker  form-control" name="bm_sig_date" id="bm_sig_date" value="<?php echo esc_attr($bm_signature_date); ?>"/>
	        </p>

			<div class="sign_fields">
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
	             $sig = get_post_meta($lease_id, '_yl_va_bm_signature', true);
	             if($sig) {
	                ?>
	                <div class="sig_out">
	                <img src="<?php echo $sig; ?>" alt="Signature"/>
	                </div>
	             <?php
	            }
	        ?>
	        </div>

			<?php }//eof building manager signature ?>



	        <p style="margin-top:20px;">
	        	<input type="hidden" name="lease_id" value="<?php echo $lease_id; ?>" />
	            <input type="submit" name="vn_submit" id="vn_submit" class="btn btn-primary" value="Submit" />
	        </p>
	        </form>
	    <?php

	}elseif(isset($_GET['step1success'])){
		echo "<h2>Notification has been successfully sent!</h2>";
	}else{
		$suite_id = $_GET['suite_id'];
		$lease_id = $_GET['lid'];

		$current_vacate_signup_date = date('Y-m-d');
		//$current_vacate_signup_date = '2016-08-03';
		if((get_post_meta($lease_id, '_yl_suite_number', true) == 'Y-Membership') || (get_post_meta($lease_id, '_yl_product_id', true) == -1) || (get_post_meta($lease_id, '_yl_product_id', true) == '') ){
			$day_90_vacate_actual_date = date( "Y-m-d", strtotime("+30 days", strtotime($current_vacate_signup_date)) );
			$display_number_of_days_to_vacate = 30;
		}else{
			$day_90_vacate_actual_date = date( "Y-m-d", strtotime("+90 days", strtotime($current_vacate_signup_date)) );
			$display_number_of_days_to_vacate = 90;
		}
		$day_90_vacate_actual_date_arr = explode("-", $day_90_vacate_actual_date);
		$d90v_month = $day_90_vacate_actual_date_arr[1];
		if( substr($d90v_month, 0, 1) == 0 ) {
			$d90v_month = substr($d90v_month, 1, 1);
		}
		
		
		
		$d90v_day = $day_90_vacate_actual_date_arr[2];
		
		$d90v_year = $day_90_vacate_actual_date_arr[0];


//		echo $day_90_vacate_actual_date;
//
//		echo "<br />";
//		echo $d90v_day;
//
//		echo "<br />";
//		echo $d90v_month;
//
//		echo "<br />";
//		echo $d90v_year;


		//if day between 1 to 5 set last day of that date previous month.
		if( ($d90v_day >= 1) && ($d90v_day <= 5) ){
			//previous last day of that date previous month
			$timestamp_previous_month = strtotime ("-1 month",strtotime ($day_90_vacate_actual_date));
			$day_90_vacate_date = date("Y-m-d", $timestamp_previous_month);
			
			
		}elseif( ($d90v_day >= 6) ){
			//last day of that month
			$day_90_vacate_date = date("Y-m-d", strtotime($day_90_vacate_actual_date));

		}else{
			//OR direct after 90 days
			$day_90_vacate_date = date('Y-m-d', strtotime("+90 days"));
			
		}

//		echo "<br />";
//		echo $day_90_vacate_date;

		//Set actual 90 days vacate date.

		$day_90_vacate_date =  date("Y-m-d", strtotime($day_90_vacate_actual_date));
	
		
	?>
        <style>
            #Move_Out_Date{
                background-color: #fff!important;
            }
        </style>
		<form action="" method="post" class="<?php echo $class_sig_pad_form; ?>">
		<br />
        	<input type="hidden" class="lease_min_day" value="<?php echo esc_html($display_number_of_days_to_vacate); ?>">
			<p>Your lease requires <?php echo esc_html($display_number_of_days_to_vacate); ?> days notice to vacate. That means the earliest date we can move you out is <span id="earliest_date"><?php echo $day_90_vacate_date; ?></span>.</p>

			<p>Please select your desired move out date: <input type="text" name="move_out_date" id="Move_Out_Date" class="form-control" value="<?php echo esc_attr($day_90_vacate_date); ?>" readonly /></p>


			<?php
				if(get_post_meta($lease_id, '_yl_va_lessee', true))
					$lease_name = get_post_meta($lease_id, '_yl_va_lessee', true);
				else
					$lease_name = get_post_meta($lease_id, '_yl_l_first_name', true) .' '. get_post_meta($lease_id, '_yl_l_last_name', true);
			?>
			<p><label for="va_lessee">Lessee:</label><input type="text" id="va_lessee" class="form-control" name="va_lessee" value="<?php echo esc_attr($lease_name); ?>" readonly="readonly" /></p>
			<?php
				if(get_post_meta($lease_id, '_yl_va_building', true))
					$va_building = get_post_meta($lease_id, '_yl_va_building', true);
				else
					$va_building = get_bloginfo( 'name' );
			?>
			<p><label for="va_building">Building:</label><input type="text" id="va_building" class="form-control" name="va_building" value="<?php echo esc_attr($va_building); ?>"  readonly="readonly" /></p>
			<?php
				$lease_company_id = get_post_meta($lease_id, '_yl_company_name', true);

				if(get_post_meta($lease_id, '_yl_va_business_name', true))
					$va_business_name = get_post_meta($lease_id, '_yl_va_business_name', true);
				else
					$va_business_name = get_the_title( $lease_company_id );
			?>
			<p><label for="va_business_name">Business Name:</label><input type="text" id="va_business_name" class="form-control" name="va_business_name" value="<?php echo esc_attr($va_business_name); ?>"  readonly="readonly" /></p>

			<?php
				if(get_post_meta($lease_id, '_yl_tenant_contact_email', true))
					$tenant_contact_email = get_post_meta($lease_id, '_yl_tenant_contact_email', true);
				else
					$tenant_contact_email = get_post_meta($lease_id, '_yl_l_email', true);
			?>
			<p><label for="tenant_contact_email">Forwarding Email:</label><input type="text" id="tenant_contact_email" class="form-control" name="tenant_contact_email" value="<?php echo esc_attr($tenant_contact_email); ?>" /></p>

			<?php
				if(get_post_meta($lease_id, '_yl_va_cell_phone', true))
					$va_cell_phone = get_post_meta($lease_id, '_yl_va_cell_phone', true);
				else
					$va_cell_phone = get_post_meta($lease_id, '_yl_l_phone', true);
			?>
			<p><label for="va_cell_phone">Forwarding Phone:</label><input type="text" id="va_cell_phone" class="form-control" name="va_cell_phone" value="<?php echo esc_attr($va_cell_phone); ?>" /></p>

			<?php
				if(get_post_meta($lease_id, '_yl_tenant_forwarding_address', true))
					$tenant_forwarding_address = get_post_meta($lease_id, '_yl_tenant_forwarding_address', true);
				else
					$tenant_forwarding_address = get_post_meta($lease_id, '_yl_l_address_line_2', true);
			?>
			<p><label for="tenant_forwarding_address">Forwarding Address:</label><input type="text" id="tenant_forwarding_address" class="form-control" name="tenant_forwarding_address" value="<?php echo esc_attr($tenant_forwarding_address); ?>" /></p>


			<p><label for="va_security_deposit_held">Security Deposit Held:</label><input type="text" id="va_security_deposit_held" class="form-control" name="va_security_deposit_held" value="<?php echo esc_attr(get_post_meta($lease_id, '_yl_security_deposit', true)); ?>"  readonly="readonly" /><span style="font-size:90%; font-style:italic;">Your security deposit balance returns to the address provided us 45 days from vacate. This balance considers suite refreshing charges, keys and fob fees, and other outstanding fees.</span></p>

			<?php
				if(get_post_meta($lease_id, '_yl_date_vacate_notice_given', true)){
					$signature_date = get_post_meta($lease_id, '_yl_date_vacate_notice_given', true);
				}elseif(get_post_meta($lease_id, '_yl_va_client_signature_date', true)){
					$signature_date = get_post_meta($lease_id, '_yl_va_client_signature_date', true);
				}else{
					$signature_date = date("Y-m-d");
				}
			?>
			<p><label for="date_vacate_notice_given">Date Vacate Notice Given:</label><input type="text" id="date_vacate_notice_given" class="form-control" name="date_vacate_notice_given" value="<?php echo esc_attr($signature_date); ?>" readonly="readonly" /></p>

			<?php
//				if(get_post_meta($lease_id, '_yl_ninty_day_vacate_date', true))
//					$day_90_vacate_date = get_post_meta($lease_id, '_yl_ninty_day_vacate_date', true);
//				else
//					$day_90_vacate_date = $day_90_vacate_date;
			?>
			<p><label for="ninty_day_vacate_date">Vacate Date:</label><input type="text" id="ninty_day_vacate_date" class="form-control" name="ninty_day_vacate_date" value="<?php echo esc_attr($day_90_vacate_date); ?>" readonly="readonly" /></p>

			<?php
				if(get_post_meta($lease_id, '_yl_suites_leased', true)){
					$yl_suites_leased = get_post_meta($lease_id, '_yl_suites_leased', true);
				}else{
					$yl_suites_leased = get_post_meta($lease_id, '_yl_suite_number', true);
				}
			?>
			<p><label for="suites_leased">Suites Leased:</label><input type="text" id="suites_leased" class="form-control" name="suites_leased" value="<?php echo esc_attr($yl_suites_leased); ?>" readonly="readonly" /></p>

			<?php
				if(get_post_meta($lease_id, '_yl_suites_identified_agreement', true)){
					$suites_identified_agreement = get_post_meta($lease_id, '_yl_suites_identified_agreement', true);
				}else{
					$suites_identified_agreement = get_post_meta($lease_id, '_yl_suite_number', true);
				}
			?>
			<p><label for="suites_identified_agreement">Suites Identified in this Agreement:</label><input type="text" id="suites_identified_agreement" class="form-control" name="suites_identified_agreement" value="<?php echo esc_attr($suites_identified_agreement); ?>" readonly="readonly" /></p>

			<?php
				if(get_post_meta($lease_id, '_yl_all_n_demand_multiple_suites', true))
					$all_n_demand_multiple_suites = get_post_meta($lease_id, '_yl_all_n_demand_multiple_suites', true);
				else
					$all_n_demand_multiple_suites = get_post_meta($lease_id, '_yl_all_n_demand_multiple_suites', true);
			?>
			<p><label for="all_n_demand_multiple_suites">All-or-Nothing Demand for Multiple Suites:</label><input type="text" id="all_n_demand_multiple_suites" class="form-control" name="all_n_demand_multiple_suites" value="<?php echo esc_attr($all_n_demand_multiple_suites); ?>" readonly="readonly" /></p>



			<?php if(current_user_can( 'lease_client' )){ ?>

	        <p>
	            <label for="date">Client Signature Date</label>
	            <?php
	                if(get_post_meta($lease_id, '_yl_vn_client_signature_date', true))
	                    $c_signature_date = get_post_meta($lease_id, '_yl_vn_client_signature_date', true);
	                else
	                    $c_signature_date = date('Y-m-d');
	            ?>
	            <input type="text" class="leasedatepicker form-control" name="client_sig_date" id="client_sig_date" value="<?php echo esc_attr($c_signature_date); ?>"/>
	        </p>

			<div class="sign_fields">
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
	             $sig = get_post_meta($lease_id, '_yl_vn_client_signature', true);
	             if($sig) {
	                ?>
	                <div class="sig_out">
	                <img src="<?php echo $sig; ?>" alt="Signature"/>
	                </div>
	             <?php
	            }
	        ?>
	        </div>

			<?php }//eof if client logged in signature ?>


			<?php if(current_user_can( 'building_manager' )){ ?>
			<br /><br />
	        <p>
	            <label for="date">BM Signature Date</label>
	            <?php
	                if(get_post_meta($lease_id, '_yl_vn_bm_signature_date', true))
	                    $bm_signature_date = get_post_meta($lease_id, '_yl_vn_bm_signature_date', true);
	                else
	                    $bm_signature_date = date('Y-m-d');
	            ?>
	            <input type="text" class="leasedatepicker  form-control" name="bm_sig_date" id="bm_sig_date" value="<?php echo esc_attr($bm_signature_date); ?>"/>
	        </p>

			<div class="sign_fields">
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
	             $sig = get_post_meta($lease_id, '_yl_vn_bm_signature', true);
	             if($sig) {
	                ?>
	                <div class="sig_out">
	                <img src="<?php echo $sig; ?>" alt="Signature"/>
	                </div>
	             <?php
	            }
	        ?>
	        </div>

			<?php }//eof building manager signature ?>

			<br /><br />

			<p>Please review our <a target="_blank" href="/vacate-notice/?lid=<?php echo $lease_id; ?>&view_early_vacate_addendum">Early Vacate Addendum</a> option. This option allows for a potential early release of your 90-day vacate notice requirement.</p>


			<p><input type="checkbox" <?php if(get_post_meta($lease_id, '_yl_early_vacate_addendum', true)){ ?> checked="checked" <?php } ?> value="Yes" name="move_in_someone" id="move_in_someone" /> <label style="display:inline;" for="move_in_someone">Yes, please try to find someone to move in sooner for me.</label></p>

			<input type="hidden" name="lease_id" value="<?php echo $_GET['lid']; ?>">
			<p><input name="vacate_submit" class="btn btn-red" type="submit" value="Submit" /></p>
		</form>
	<?php
	}//default step
	?>
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

			var lease_min_day = jQuery('.lease_min_day').val();
			
			var minuse_date = '';
			if(lease_min_day !=''){
				
				minuse_date = lease_min_day;
				
			}else{
			
				minuse_date = 0;
				
			}
			  

			jQuery("#Move_Out_Date").datepicker({
				dateFormat: 'yy-mm-dd',
				 minDate: minuse_date,
				beforeShowDay: function (date) {
					//if (date.getDate() == 15 || date.getDate() == 1) {}
					
					var d1 = new Date(date);
					var earliest_date = jQuery("#earliest_date").text();
					var d2 = new Date(earliest_date);
				
					
					//if (date.getDate() == LastDayOfMonth(date.getFullYear(),date.getMonth())  ) {						
						return [true, ''];
					//}
					//return [false, ''];
				},
				onSelect: function(date) {
					//alert(date);
					var d1 = new Date(date);
					var earliest_date = jQuery("#earliest_date").text();
					var d2 = new Date(earliest_date);
					if(d1 < d2) {
						alert("Date is before your move out date, please select another date.");								
						jQuery(this).datepicker('setDate', '');
					}else{
						var MoveOutDate = jQuery("#Move_Out_Date").val();
						jQuery( "#ninty_day_vacate_date" ).val( MoveOutDate );
					}
				}
			});

			function LastDayOfMonth(Year, Month){
			
				
				return(new Date((new Date(Year, Month+1,1))-1)).getDate();
			}


		    });




		</script>

	<?php

	$content = ob_get_contents();
	ob_end_clean();

	return $content;

}

?>
