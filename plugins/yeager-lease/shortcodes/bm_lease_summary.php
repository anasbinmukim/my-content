<?php
function yl_bm_lease_summary_shortcode($atts, $content = null) {
	extract(shortcode_atts(array(
		'lease_id' => '',
	), $atts));

	if(!current_user_can( 'building_manager' )){
		?>

    <div class="yl-alert alert-danger">
      Only Building Manager are able to create lease. Please login to search available suites.
    </div>

		<?php
    return;
	}

	if ( isset($_POST['ls_submit']) || isset($_POST['ls_submit_continue']) ) {

    // signature convert an image and send to the wp signature directory
       // echo "<pre>";print_r($_POST);die;
       // echo $_POST['lessee_state'];die;
    $dir = "/signatures";
    $lease_id = $_GET['lid'];
    $data = $_POST['imgOutput'];
    $keypad_sig = $_POST['keypad_sig'];
	$key = '';
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
        update_post_meta($lease_id, '_yl_bm_ls_signature',  $fileurl);
      }
      else {
          error_log("Cannot create signature file in directory ".$filepath);
      }
    }

    else {
      // convert canvas to png image
      if (is_string($data) && strrpos($data, "data:image/png;base64", -strlen($data)) !== FALSE){
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
            update_post_meta($lease_id, '_yl_bm_ls_signature',  $fileurl);
          }
          else {
            error_log("Cannot create signature file in directory ".$filepath);
          }
        }
      }
    }

    // updated all of these field here
    // lease summary
    $lessor =         esc_html($_POST['lessor']);
    $location =       esc_html($_POST['lessorLocation']);
    $phone =          esc_html($_POST['locationPhone']);

    // lease field
    $l_f_name =       esc_html($_POST['lessee_first_name']);
    $l_m_name =       esc_html($_POST['lessee_middle_name']);
    $l_l_name =       esc_html($_POST['lessee_last_name']);
    $l_phone =        esc_html($_POST['lessee_phone']);
    $l_email =        esc_html($_POST['lessee_email']);
    $l_address =      esc_html($_POST['lessee_street_address']);
    $l_address2 =     esc_html($_POST['lessee_address_2']);
    $l_city =         esc_html($_POST['lessee_city']);
    $l_state =        esc_html($_POST['lessee_state']);
    $l_zip_code =     esc_html($_POST['lessee_zip_code']);
      
    $g_f_name =       esc_html($_POST['guarantor_first_name']);
    $g_m_name =       esc_html($_POST['guarantor_middle_name']);
    $g_l_name =       esc_html($_POST['guarantor_last_name']);
    $g_phone =        esc_html($_POST['guarantor_phone']);
    $g_email =        esc_html($_POST['guarantor_email']);
    $g_address =      esc_html($_POST['guarantor_street_address']);
    $g_address2 =     esc_html($_POST['guarantor_address_2']);
    $g_city =         esc_html($_POST['guarantor_city']);
    $g_state =        esc_html($_POST['guarantor_state']);
    $g_zip =          esc_html($_POST['guarantor_zip_code']);

    $company_name =   esc_html($_POST['select_company_company_name']);
    $company_type =   esc_html($_POST['company_type']);
    $suite_number =   esc_html($_POST['suite_number']);
    $l_start_date =   esc_html($_POST['lease_start_date']);

    $first_month_rent_rate = esc_html($_POST['first_month_rent_rate']);
    $monthly_recuring_rate = esc_html($_POST['monthly_rent']);
    $security_deposit = esc_html($_POST['security_deposit']);
    $vacate_notice = esc_html($_POST['vacate_notice']);

    $promotional_code = esc_html($_POST['promotional_code']);
    $service_fees = esc_html($_POST['service_fees']);
    $phone_fee = esc_html($_POST['phone_fee']);
    $ipservice_fee = esc_html($_POST['ipservice_fee']);
    $fax_fee = esc_html($_POST['fax_fee']);
    $postage_fee = esc_html($_POST['postage_fee']);
    $cable_fee = esc_html($_POST['cable_fee']);
    $credit_card_line_fee = esc_html($_POST['credit_card_line_fee']);

    $addendums = esc_html($_POST['addendums']);

    $date = esc_html($_POST['date']);
    $mk_aux_promo = esc_html($_POST['mk_aux_promo']);

    // lease summary
    update_post_meta($lease_id, '_yl_lessor', $lessor);
    update_post_meta($lease_id, '_yl_location', $location);
    update_post_meta($lease_id, '_yl_location_phone_number', $phone);

    // lease field
    update_post_meta($lease_id, '_yl_l_first_name', $l_f_name);
    update_post_meta($lease_id, '_yl_l_middle_name', $l_m_name);
    update_post_meta($lease_id, '_yl_l_last_name', $l_l_name);
    update_post_meta($lease_id, '_yl_l_phone', $l_phone);
    update_post_meta($lease_id, '_yl_l_email', $l_email);
    update_post_meta($lease_id, '_yl_l_street_address', $l_address);
    update_post_meta($lease_id, '_yl_l_address_line_2', $l_address2);
    update_post_meta($lease_id, '_yl_l_city', $l_city);
    if(!empty($l_state) ) {
      update_post_meta($lease_id, '_yl_l_state', $l_state);
    }
    update_post_meta($lease_id, '_yl_l_zip_code', $l_zip_code);

    // Guarantor fields update
    update_post_meta($lease_id, '_yl_g_first_name', $g_f_name);
    update_post_meta($lease_id, '_yl_g_middle_name', $g_m_name);
    update_post_meta($lease_id, '_yl_g_last_name', $g_l_name);
    update_post_meta($lease_id, '_yl_g_phone', $g_phone);
    update_post_meta($lease_id, '_yl_g_email', $g_email);
    update_post_meta($lease_id, '_yl_g_street_address', $g_address);
    update_post_meta($lease_id, '_yl_g_address_line_2', $g_address2);
    update_post_meta($lease_id, '_yl_g_city', $g_city);

		if(!empty($g_state) ) {
     	update_post_meta($lease_id, '_yl_g_state', $g_state);
		}

    update_post_meta($lease_id, '_yl_g_zip_code', $g_zip);
    update_post_meta($lease_id, '_yl_company_name', $company_name);
    update_post_meta($lease_id, '_yl_company_type', $company_type);
    update_post_meta($lease_id, '_yl_suite_number', $suite_number);
    update_post_meta($lease_id, '_yl_lease_start_date', $l_start_date);
    update_post_meta($lease_id, '_yl_first_month_rent_rate', $first_month_rent_rate);
    update_post_meta($lease_id, '_yl_monthly_rent', $monthly_recuring_rate);
    update_post_meta($lease_id, '_yl_security_deposit', $security_deposit);
    update_post_meta($lease_id, '_yl_vacate_notice', $vacate_notice);
    update_post_meta($lease_id, '_yl_promotional_code', $promotional_code);
    update_post_meta($lease_id, '_yl_addendums', $addendums);
    update_post_meta($lease_id, '_yl_bm_signature_date', $date);

    // Service Fees
    update_post_meta($lease_id, '_yl_service_fees', $service_fees);

    // Phone Fee
    update_post_meta($lease_id, '_yl_phone_fee', $phone_fee);

    // Cable Fee
    update_post_meta($lease_id, '_yl_cable_fee', $cable_fee);

    // IP Service Fee
    update_post_meta($lease_id, '_yl_ipservice_fee', $ipservice_fee);

    // Fax Fee
    update_post_meta($lease_id, '_yl_fax_fee', $fax_fee);

    // Postage Fee
    update_post_meta($lease_id, '_yl_postage_fee', $postage_fee);

    // Credit Card Line Fee
    update_post_meta($lease_id, '_yl_credit_card_line_fee', $credit_card_line_fee);

    // Multi suite discount
    update_post_meta($lease_id, '_yl_multi_suite_discount', $multi_suite_discount);

    if ($yl_multisite_coupon) {
      update_post_meta($lease_id, '_yl_multisite_coupon', true);
    }
    else {
      update_post_meta($lease_id, '_yl_multisite_coupon', false);
    }

    // Promo code
    update_post_meta($lease_id, '_yl_mk_aux_promo', $mk_aux_promo);

		//Register New Client
		$client_arg = array();
		$client_arg['user_email'] =   $_POST['lessee_email'];
		$client_arg['user_login'] =   $_POST['lessee_email'];
    $client_arg['lease_id'] =     $lease_id;

		$client_arg['first_name'] =   get_post_meta($lease_id, '_yl_l_first_name', true);
    $client_arg['middle_name'] =  get_post_meta($lease_id, '_yl_l_middle_name', true);
		$client_arg['last_name'] =    get_post_meta($lease_id, '_yl_l_last_name', true);
    $client_arg['phone'] =        get_post_meta($lease_id, '_yl_l_phone', true);
    $client_arg['address_1'] =    get_post_meta($lease_id, '_yl_l_street_address', true);
    $client_arg['address_2'] =    get_post_meta($lease_id, '_yl_l_address_line_2', true);
    $client_arg['city'] =         get_post_meta($lease_id, '_yl_l_city', true);
    $client_arg['zip'] =          get_post_meta($lease_id, '_yl_l_zip_code', true);
    $client_arg['state'] =        get_post_meta($lease_id, '_yl_l_state', true);
    $client_arg['company'] =      $company_name;

    $notif = false;
		if ( isset($_POST['sent_notification_to_client'])) {
			$message = 'New user registered and notification email sent to the user.';
      $notif = true;
		}

    if ($user_id = yl_register_client_user($client_arg, $notif)) {
      $assoc_clients = SI_Client::get_clients_by_user($user_id);
      $client_id = $assoc_clients[0];

      // Let's create an invoice now
      // This is this month's invoice
      // All values will be prorated to the
      // days left

      $yl_ms_args = array(
        'post_type'   => 'lease',
        'post_status'   => 'publish',
        'numberposts'  => -1,
        'meta_query' => array(
        'relation' => 'AND',
          array(
          'key' => '_yl_lease_user',
          'value'   => $user_id,
          'compare' => '='
          ),
          array(
            'relation' => 'OR',
            array(
              'key' => '_yl_is_storage',
              'value'   => '1',
              'compare' => '!='
            ),
            array(
              'key' => '_yl_is_storage',
              'compare' => 'NOT EXISTS'
            )
          ),
          array(
            'relation' => 'OR',
            array(
              'key' => '_yl_suite_number',
              'value'   => -1,
              'compare' => '!='
            ),
            array(
              'key' => '_yl_suite_number',
              'value'   => 'Y-Membership',
              'compare' => '!='
            ),
          ),
        )
      );
      $posts = get_posts($yl_ms_args);

      $multi_suite_discount = 0;
      $num_of_leases = count($posts);
// var_dump(strpos($suite_number, "Y-Membership"));
// if(strpos($suite_number, "Y-Membership") == false)
// {
//   echo "true";
// }
      if(strpos($suite_number, "Y-Membership") ==false && strpos($suite_number, "Storage")==false  && strpos($suite_number, "Y-Membership") != 0)
      {

      if ($num_of_leases >= 1) {
        $num_of_leases++; // Let's add 1, because the lease in process is not yet in the DB, but it counts for the discount.

        if ($num_of_leases == 2) {
          $multi_suite_discount = get_option('yl_multisite_discount');
        }
        elseif ($num_of_leases == 3) {
          $multi_suite_discount = get_option('yl_multisite_discount_3');
        }
        elseif ($num_of_leases == 4) {
          $multi_suite_discount = get_option('yl_multisite_discount_4');
        }
        elseif ($num_of_leases >= 5) {
          $multi_suite_discount = get_option('yl_multisite_discount_5');
        }
      }
      }

// print_r($multi_suite_discount);
      $invoice_args =  array(
        'subject' => 'Invoice for '.$suite_number,
        'client_id' => $client_id,
        'status' => 'publish',
        'currency' => '',
        'issue_date' => time(),
        'due_date' => strtotime($l_start_date),
        'expiration_date' => 0,
        'fields' => array(),
        'total' => 0
      );

      // Lets get the move in day.
      $date_parts = explode('-', $l_start_date);
      $date_parts_day = $date_parts[2];
      $date_parts_month = $date_parts[1];
      $first_day_next_month_to_lease_start = strtotime('first day of '.date("Y-m", strtotime("+1 month", strtotime($l_start_date))));

      $days_this_month = date('t', time());
      $month_this_month = date('m', time());
      $month_next_month = date('m', $first_day_next_month_to_lease_start);
      $days_left_this_month = ($days_this_month+1)-$date_parts_day;


      if (($date_parts_day >= 20) && (($month_this_month == $month_next_month) || (($month_this_month+1) == $month_next_month))) {
        // If move in date is the 20th or later, lets create
        // a second invoice.
        $invoice_args_next_month = array(
          'subject' => 'Invoice for '.$suite_number,
          'client_id' => $client_id,
          'status' => 'publish',
          'currency' => '',
          'issue_date' => time(),
          'due_date' => $first_day_next_month_to_lease_start,
          'expiration_date' => 0,
          'fields' => array(),
          'total' => 0
        );
      }

      // first_month_rent_rate comes from a previous step with the deposit included
      // thats why to get the real first month rate we need to remove a value of monthly_ret , considering
      // the deposit is 1 month value.
      if ($_POST['first_month_rent_rate'] <= $_POST['monthly_rent']) {
        $first_month_rent_rate = $_POST['first_month_rent_rate'];
      }
      else {
        $first_month_rent_rate = $_POST['first_month_rent_rate']-$_POST['monthly_rent'];
      }

      //echo $first_month_rent_rate;
      //exit();

      //if ($date_parts_day >= 20) {
      //if (($date_parts_day >= 20) && (($month_this_month == $month_next_month) || (($month_this_month+1) == $month_next_month))) {
      //
      //}
      $monthly_recuring_rate = $_POST['monthly_rent'];
      $security_deposit = $_POST['security_deposit'];

      //////////////////
      // Monthly Rate //
      //////////////////
      $invoice_args['line_items'][] = array(
        "desc" => "First Month Rent Rate",
        "qty" => 1,
        "rate" => (float) round($first_month_rent_rate, 2),
        "total" => (float) round($first_month_rent_rate, 2),
        "type" => "service",
		"accounting_cat" => yl_account_category_id_by_wordmatch('Rent')
      );
      $invoice_args['total'] += (float) round($first_month_rent_rate, 2);

      //if ($date_parts_day >= 20) {
      if (($date_parts_day >= 20) && (($month_this_month == $month_next_month) || (($month_this_month+1) == $month_next_month))) {
        $invoice_args_next_month['line_items'][] = array(
          "desc" => "Recurrent Rent Rate",
          "qty" => 1,
          "rate" => (float) round($monthly_recuring_rate, 2),
          "total" => (float) round($monthly_recuring_rate, 2),
          "type" => "service",
		  "accounting_cat" => yl_account_category_id_by_wordmatch('Rent')
        );
        $invoice_args_next_month['total'] += (float) round($monthly_recuring_rate, 2);
      }

      // There is a promo code?
      // Prorated invoice only gets fixed price promos, and not percent promos.
      $aux_promo_code_id = esc_html($_POST['mk_aux_promo']);
      $promo_type = get_post_meta($aux_promo_code_id, 'mk_promotional_code_prmotiontype', true);
      $promo_value = get_post_meta($aux_promo_code_id, 'mk_promotional_code_prmotionalval', true);
      $promo_line = get_post_meta($aux_promo_code_id, 'mk_promotional_code_producttype', true);

      if ($aux_promo_code_id) {
        if ($promo_line == 'rent') {
          if ($promo_type == 'fixed') {
            $invoice_args['line_items'][] = array(
              "desc" => "First Month Rent Discount - PROMO CODE: ".$promotional_code,
              "qty" => 1,
              "rate" => -round($promo_value, 2),
              "total" => -round($promo_value, 2),
              "type" => "credit",
			  "accounting_cat" => yl_account_category_id_by_wordmatch('Discount')
            );
            $invoice_args['total'] -= (float) round($promo_value, 2);
          }
        }
      }

      //if ($date_parts_day >= 20) {
      if (($date_parts_day >= 20) && (($month_this_month == $month_next_month) || (($month_this_month+1) == $month_next_month))) {
        if ($aux_promo_code_id) {
          if ($promo_line == 'rent') {
            if ($promo_type == 'percent') {
              $invoice_args_next_month['line_items'][] = array(
                "desc" => "Rent Discount (".$promo_value."%) - PROMO CODE: ".$promotional_code,
                "qty" => 1,
                "rate" => -round((($monthly_recuring_rate/100)*$promo_value), 2),
                "total" => -round((($monthly_recuring_rate/100)*$promo_value), 2),
                "type" => "credit",
				"accounting_cat" => yl_account_category_id_by_wordmatch('Discount')
              );
              $invoice_args_next_month['total'] -= (float) round((($monthly_recuring_rate/100)*$promo_value), 2);
            }
          }
        }
      }


      /////////////////////
      // Securit Deposit //
      /////////////////////
      $invoice_args['line_items'][] = array(
        "desc" => "Security Deposit",
        "qty" => 1,
        "rate" => (float) round($security_deposit, 2),
        "total" => (float) round($security_deposit, 2),
        "type" => "service",
		"accounting_cat" => yl_account_category_id_by_wordmatch('Deposit')
      );
      $invoice_args['total'] += (float) round($security_deposit, 2);

      // There is a promo code?
      // Prorated invoice only gets fixed price promos, and not percent promos.
      if ($aux_promo_code_id) {

        if ($promo_line == 'security_deposit') {
          if ($promo_type == 'percent') {
            $invoice_args['line_items'][] = array(
              "desc" => "Security Deposit Discount (".$promo_value."%) - PROMO CODE: ".$promotional_code,
              "qty" => 1,
              "rate" => -round((($security_deposit/100)*$promo_value), 2),
              "total" => -round((($security_deposit/100)*$promo_value), 2),
              "type" => "credit",
			  "accounting_cat" => yl_account_category_id_by_wordmatch('Deposit')
            );
            $invoice_args['total'] -= (float) round((($security_deposit/100)*$promo_value), 2);
          }
          else if ($promo_type == 'fixed') {
            $invoice_args['line_items'][] = array(
              "desc" => "Security Deposit Discount - PROMO CODE: ".$promotional_code,
              "qty" => 1,
              "rate" => -round(($promo_value), 2),
              "total" => -round(($promo_value), 2),
              "type" => "credit",
			  "accounting_cat" => yl_account_category_id_by_wordmatch('Deposit')
            );
            $invoice_args['total'] -= (float) round(($promo_value), 2);
          }
        }
      }

      /////////////////////
      // Hold Fee Credit //
      /////////////////////
      $product_id = get_post_meta($lease_id, '_yl_product_id', true);
      $was_hold = get_post_meta($product_id, '_yl_hold', true);

      if ($was_hold){
        // This suite was on hold
        // This means this lease has a 100 credit for that hold fee.
        $invoice_args['line_items'][] = array(
          "desc" => "Hold Fee Credit",
          "qty" => 1,
          "rate" => -(float) 100.0,
          "total" => -(float) 100.0,
          "type" => "credit",
		  "accounting_cat" => yl_account_category_id_by_wordmatch('Rent')
        );
        $invoice_args['total'] -= (float) 100.0;
      }


      //////////////////
      // Service Fees //
      //////////////////
      $serv_fees = $service_fees;
      if ($_POST['service_fees']) {
        if ($serv_fees > 0) {

          $invoice_args['line_items'][] = array(
            "desc" => "Service Fees",
            "qty" => 1,
            "rate" => (float) round((($serv_fees/$days_this_month)*$days_left_this_month), 2),
            "total" => (float) round((($serv_fees/$days_this_month)*$days_left_this_month), 2),
            "type" => "service",
			"accounting_cat" => yl_account_category_id_by_wordmatch('Utilities')
          );
          $invoice_args['total'] += (float) round((($serv_fees/$days_this_month)*$days_left_this_month), 2);

          //if ($date_parts_day >= 20) {
          if (($date_parts_day >= 20) && (($month_this_month == $month_next_month) || (($month_this_month+1) == $month_next_month))) {
            $invoice_args_next_month['line_items'][] = array(
              "desc" => "Service Fees",
              "qty" => 1,
              "rate" => (float) round($serv_fees, 2),
              "total" => (float) round($serv_fees, 2),
              "type" => "service",
			  "accounting_cat" => yl_account_category_id_by_wordmatch('Utilities')
            );
            $invoice_args_next_month['total'] += (float) round($serv_fees, 2);
          }

        }
      }

      ////////////////
      // Phone Fees //
      ////////////////
      if ($_POST['phone_fee']) {
        if ($phone_fee > 0) {

          $invoice_args['line_items'][] = array(
            "desc" => "Phone Service Fee",
            "qty" => 1,
            "rate" => (float) round((($phone_fee/$days_this_month)*$days_left_this_month), 2),
            "total" => (float) round((($phone_fee/$days_this_month)*$days_left_this_month), 2),
            "type" => "service",
			"accounting_cat" => yl_account_category_id_by_wordmatch('Phone')
          );
          $invoice_args['total'] += (float) round((($phone_fee/$days_this_month)*$days_left_this_month), 2);

          //if ($date_parts_day >= 20) {
          if (($date_parts_day >= 20) && (($month_this_month == $month_next_month) || (($month_this_month+1) == $month_next_month))) {
            $invoice_args_next_month['line_items'][] = array(
              "desc" => "Phone Service Fee",
              "qty" => 1,
              "rate" => (float) round($phone_fee, 2),
              "total" => (float) round($phone_fee, 2),
              "type" => "service",
			  "accounting_cat" => yl_account_category_id_by_wordmatch('Phone')
            );
            $invoice_args_next_month['total'] += (float) round($phone_fee, 2);
          }

        }
      }

      ////////////////
      // Cable Fees //
      ////////////////
      if ($_POST['cable_fee']) {
        if ($cable_fee > 0) {

          $invoice_args['line_items'][] = array(
            "desc" => "Cable Service Fee",
            "qty" => 1,
            "rate" => (float) round((($cable_fee/$days_this_month)*$days_left_this_month), 2),
            "total" => (float) round((($cable_fee/$days_this_month)*$days_left_this_month), 2),
            "type" => "service",
			"accounting_cat" => yl_account_category_id_by_wordmatch('Phone')
          );
          $invoice_args['total'] += (float) round((($cable_fee/$days_this_month)*$days_left_this_month), 2);

          //if ($date_parts_day >= 20) {
          if (($date_parts_day >= 20) && (($month_this_month == $month_next_month) || (($month_this_month+1) == $month_next_month))) {
            $invoice_args_next_month['line_items'][] = array(
              "desc" => "Cable Service Fee",
              "qty" => 1,
              "rate" => (float) round($cable_fee, 2),
              "total" => (float) round($cable_fee, 2),
              "type" => "service",
			  "accounting_cat" => yl_account_category_id_by_wordmatch('Phone')
            );
            $invoice_args_next_month['total'] += (float) round($cable_fee, 2);
          }

        }
      }

      /////////////////////
      // IP Service Fees //
      /////////////////////
      if ($_POST['ipservice_fee']) {
        if ($ipservice_fee > 0) {

          $invoice_args['line_items'][] = array(
            "desc" => "IP Service Fee",
            "qty" => 1,
            "rate" => (float) round((($ipservice_fee/$days_this_month)*$days_left_this_month), 2),
            "total" => (float) round((($ipservice_fee/$days_this_month)*$days_left_this_month), 2),
            "type" => "service",
			"accounting_cat" => yl_account_category_id_by_wordmatch('Phone')
          );
          $invoice_args['total'] += (float) round((($ipservice_fee/$days_this_month)*$days_left_this_month), 2);

          //if ($date_parts_day >= 20) {
          if (($date_parts_day >= 20) && (($month_this_month == $month_next_month) || (($month_this_month+1) == $month_next_month))) {
            $invoice_args_next_month['line_items'][] = array(
              "desc" => "IP Service Fee",
              "qty" => 1,
              "rate" => (float) round($ipservice_fee, 2),
              "total" => (float) round($ipservice_fee, 2),
              "type" => "service",
			  "accounting_cat" => yl_account_category_id_by_wordmatch('Phone')
            );
            $invoice_args_next_month['total'] += (float) round($ipservice_fee, 2);
          }

        }
      }

      //////////////
      // Fax Fees //
      //////////////
      if ($_POST['fax_fee']) {
        if ($fax_fee > 0) {

          $invoice_args['line_items'][] = array(
            "desc" => "Fax Service Fee",
            "qty" => 1,
            "rate" => (float) round((($fax_fee/$days_this_month)*$days_left_this_month), 2),
            "total" => (float) round((($fax_fee/$days_this_month)*$days_left_this_month), 2),
            "type" => "service",
			"accounting_cat" => yl_account_category_id_by_wordmatch('Phone')
          );
          $invoice_args['total'] += (float) round((($fax_fee/$days_this_month)*$days_left_this_month), 2);

          //if ($date_parts_day >= 20) {
          if (($date_parts_day >= 20) && (($month_this_month == $month_next_month) || (($month_this_month+1) == $month_next_month))) {
            $invoice_args_next_month['line_items'][] = array(
              "desc" => "Fax Service Fee",
              "qty" => 1,
              "rate" => (float) round($fax_fee, 2),
              "total" => (float) round($fax_fee, 2),
              "type" => "service",
			  "accounting_cat" => yl_account_category_id_by_wordmatch('Phone')
            );
            $invoice_args_next_month['total'] += (float) round($fax_fee, 2);
          }

        }
      }

      //////////////////
      // Postage Fees //
      //////////////////
      if ($_POST['postage_fee']) {
        if ($postage_fee > 0) {

          $invoice_args['line_items'][] = array(
            "desc" => "Postage Service Fee",
            "qty" => 1,
            "rate" => (float) round((($postage_fee/$days_this_month)*$days_left_this_month), 2),
            "total" => (float) round((($postage_fee/$days_this_month)*$days_left_this_month), 2),
            "type" => "service",
			"accounting_cat" => yl_account_category_id_by_wordmatch('Postage')
          );
          $invoice_args['total'] += (float) round((($postage_fee/$days_this_month)*$days_left_this_month), 2);

          //if ($date_parts_day >= 20) {
          if (($date_parts_day >= 20) && (($month_this_month == $month_next_month) || (($month_this_month+1) == $month_next_month))) {
            $invoice_args_next_month['line_items'][] = array(
              "desc" => "Postage Service Fee",
              "qty" => 1,
              "rate" => (float) round($postage_fee, 2),
              "total" => (float) round($postage_fee, 2),
              "type" => "service",
			  "accounting_cat" => yl_account_category_id_by_wordmatch('Postage')
            );
            $invoice_args_next_month['total'] += (float) round($postage_fee, 2);
          }

        }
      }

      ///////////////////////////
      // Credit Card Line Fees //
      ///////////////////////////
      if ($_POST['credit_card_line_fee']) {
        if ($credit_card_line_fee > 0) {

          $invoice_args['line_items'][] = array(
            "desc" => "Credit Card Line Service Fee",
            "qty" => 1,
            "rate" => (float) round((($credit_card_line_fee/$days_this_month)*$days_left_this_month), 2),
            "total" => (float) round((($credit_card_line_fee/$days_this_month)*$days_left_this_month), 2),
            "type" => "service",
			"accounting_cat" => yl_account_category_id_by_wordmatch('Phone')
          );
          $invoice_args['total'] += (float) round((($credit_card_line_fee/$days_this_month)*$days_left_this_month), 2);

          //if ($date_parts_day >= 20) {
          if (($date_parts_day >= 20) && (($month_this_month == $month_next_month) || (($month_this_month+1) == $month_next_month))) {
            $invoice_args_next_month['line_items'][] = array(
              "desc" => "Credit Card Line Service Fee",
              "qty" => 1,
              "rate" => (float) round($credit_card_line_fee, 2),
              "total" => (float) round($credit_card_line_fee, 2),
              "type" => "service",
			  "accounting_cat" => yl_account_category_id_by_wordmatch('Phone')
            );
            $invoice_args_next_month['total'] += (float) round($credit_card_line_fee, 2);
          }

        }
      }

      ///////////////////////
      // Multisuite Coupon //
      ///////////////////////

      if ($multi_suite_discount > 0) {

        $multi_suite_discount_total = (float) round( ($monthly_recuring_rate/100)*$multi_suite_discount, 2);
        $multi_suite_discount_first_month = (float) round( ($first_month_rent_rate/100)*$multi_suite_discount , 2);

        $invoice_args['line_items'][] = array(
          "desc" => "Multi Suite Discount (".$multi_suite_discount."% off rent)",
          "qty" => 1,
          "rate" => -round( ($first_month_rent_rate/100)*$multi_suite_discount , 2),
          "total" => -round( ($first_month_rent_rate/100)*$multi_suite_discount , 2),
          "type" => "credit",
		  "accounting_cat" => yl_account_category_id_by_wordmatch('Discount')
        );
        $invoice_args['total'] -= round( ($first_month_rent_rate/100)*$multi_suite_discount , 2);

        //if ($date_parts_day >= 20) {
        if (($date_parts_day >= 20) && (($month_this_month == $month_next_month) || (($month_this_month+1) == $month_next_month))) {
          $invoice_args_next_month['line_items'][] = array(
            "desc" => "Multi Suite Discount (".$multi_suite_discount."% off rent)",
            "qty" => 1,
            "rate" => -round( ($monthly_recuring_rate/100)*$multi_suite_discount , 2),
            "total" => -round( ($monthly_recuring_rate/100)*$multi_suite_discount , 2),
            "type" => "credit",
			"accounting_cat" => yl_account_category_id_by_wordmatch('Discount')
          );
          $invoice_args_next_month['total'] -= (float) round($multi_suite_discount_total, 2);
        }

      }


      // Generate invoice(s)
      // echo "<pre>";
      // print_r($invoice_args);
      // echo "<pre>";
      // exit();



		if(get_post_meta($lease_id, '_yl_step_invoice_id', true)){
				$previous_invoice_id = get_post_meta($lease_id, '_yl_step_invoice_id', true);
				//Remove previous invoice
				if ( get_post_status ( $previous_invoice_id ) != 'complete' ) {
					wp_delete_post( $previous_invoice_id, true );
				}
				$invoice_id = SI_Invoice::create_invoice( $invoice_args );
				// Let's save the lease id for this invoice.
				update_post_meta($invoice_id, '_yl_lease_id', $lease_id);
				update_post_meta($lease_id, '_yl_invoice_id', $invoice_id);
				//Optimize redundant invoice generation by steps back and forth
				update_post_meta($lease_id, '_yl_step_invoice_id', $invoice_id);
		}else{
      $invoice_id = SI_Invoice::create_invoice( $invoice_args );
      // Let's save the lease id for this invoice.
      update_post_meta($invoice_id, '_yl_lease_id', $lease_id);
      update_post_meta($lease_id, '_yl_invoice_id', $invoice_id);
			//Optimize redundant invoice generation by steps back and forth
			update_post_meta($lease_id, '_yl_step_invoice_id', $invoice_id);
		}

      //if ($date_parts_day >= 20) {
      if (($date_parts_day >= 20) && (($month_this_month == $month_next_month) || (($month_this_month+1) == $month_next_month))) {
				if(get_post_meta($lease_id, '_yl_step_nextmonth_invoice_id', true)){
						$previous_invoice_id = get_post_meta($lease_id, '_yl_step_nextmonth_invoice_id', true);
						//Remove previous invoice
						if ( get_post_status ( $previous_invoice_id ) != 'complete' ) {
							wp_delete_post( $previous_invoice_id, true );
						}
						$invoice_id = SI_Invoice::create_invoice( $invoice_args_next_month );
		        update_post_meta($invoice_id, '_yl_lease_id', $lease_id);
						//Optimize redundant invoice generation by steps back and forth
						update_post_meta($lease_id, '_yl_step_nextmonth_invoice_id', $invoice_id);
				}else{
				  $invoice_id = SI_Invoice::create_invoice( $invoice_args_next_month );
	        update_post_meta($invoice_id, '_yl_lease_id', $lease_id);
					//Optimize redundant invoice generation by steps back and forth
					update_post_meta($lease_id, '_yl_step_nextmonth_invoice_id', $invoice_id);
				}
			}

            update_post_meta($lease_id, '_yl_lease_updated_once', 1);
    }

    if (isset($_POST['ls_submit_continue'])) {
      // Save and continue. Lets go to client lease summary page

      ?>
      <div class="yl-alert alert alert-success">
        Redirecting to client lease summary...

      </div>
      <script>
        jQuery(document).ready(function() {
          setTimeout(function(){ window.location = "<?php echo get_permalink(get_option('yl_summary_sign_page')).'?lid='.$_GET['lid']; ?>&bmprocess=1"; }, 1000);
        });
      </script>
      <?php
    }
    else {
      ?>

      <div class="yl-alert alert alert-success">
        E-mail was sent to client with link to Lease Summary.
        <?php
        if ($_GET['redirect']) {
          echo "Redirecting...";
        }
        ?>
      </div>
      <?php
      if ($_GET['redirect']) {
        ?>
        <script>
        jQuery(document).ready(function() {
          setTimeout(function(){ window.location = "<?php echo $_GET['redirect']; ?>"; }, 1000);
        });
        </script>
        <?php
      }
    }

	}
  else {



  	if( isset($_GET['lid']) && $_GET['lid'] != '' ) {
  		$lease_id = $_GET['lid'];
       
  		$product_id = get_post_meta($lease_id, '_yl_product_id', true);
  		$args = array(
  			'post_type' => 'lease',
  			'p' => $lease_id,
  			'post_status' => 'any'
  		);
        $lease_created = get_post_meta($lease_id, '_yl_lease_updated_once', true);
  		$query = new WP_Query( $args );

  		ob_start();

  		if($query->have_posts()) :
  			global $post, $us_states_full;

  			while($query->have_posts()): $query->the_post();
  	?>
  	<div class="lease_summary_container">
    	<?php
			if( isset($message) ) {
				echo '<h4>'.$message.'</h4>';
			}
  		?>
          <div class="lease_details">

            <div class="yl_timeline_container">
              <div class="yl_timeline_line step_3">
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

                    <div class="yl_unit_block yl_one_third">
                      <div class="yl_circle">
                      </div>
                      <div class="yl_desc">
                        Lease Summary<br>Client
                      </div>
                    </div>

                    <div class="yl_unit_block yl_one_third">
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


      	<h2>Lease Summary <input type="button" name="ls_edit" id="ls_edit" value="Edit All" /></h2>
              <form action="" method="post" class="sigPad">

                  <div class="summary_top row">
                      <div class="col-md-4">
                        <label for="lessor">Lessor <img class="edit_input" src="<?php echo YL_URL; ?>images/edit.png" alt="Edit" data-edit = "lessor" /></label>
                        <input class="form-control" type="text" id="lessor" name="lessor" value="<?php echo esc_attr(get_post_meta($lease_id, '_yl_lessor', true)); ?>" readonly="readonly" />
                      </div>

                      <div class="col-md-4">
                        <label for="lessorLocation">Location <img class="edit_input" src="<?php echo YL_URL; ?>images/edit.png" alt="Edit" data-edit = "lessorLocation" /></label>
                        <input class="form-control" type="text" id="lessorLocation" name="lessorLocation" value="<?php echo esc_attr(get_post_meta($lease_id, '_yl_location', true)); ?>" readonly="readonly" />
                      </div>

                      <div class="col-md-4">
                        <label for="locationPhone">Location Phone Number <img class="edit_input" src="<?php echo YL_URL; ?>images/edit.png" alt="Edit" data-edit = "locationPhone" /></label>
                        <input class="form-control" type="text" id="locationPhone" name="locationPhone" value="<?php echo esc_attr(get_post_meta($lease_id, '_yl_location_phone_number', true)); ?>" readonly="readonly" />
                      </div>
                  </div>

                  <div class="row">

                    <div class="summary_left col-md-6">

                        <h3>Lessee</h3>

                        <div class="row">
                          <div class="col-md-4">
                              <label for="lessee_first_name">First Name <img class="edit_input" src="<?php echo YL_URL; ?>images/edit.png" alt="Edit" data-edit = "lessee_first_name" /></label>
                              <input class="form-control" type="text" id="lessee_first_name" name="lessee_first_name" value="<?php echo esc_attr(get_post_meta($lease_id, '_yl_l_first_name', true)); ?>" readonly="readonly" />
                          </div>
                          <div class="col-md-4">
                              <label for="lessee_middle_name">Middle Name <img class="edit_input" src="<?php echo YL_URL; ?>images/edit.png" alt="Edit" data-edit = "lessee_middle_name" /></label>
                              <input class="form-control" type="text" id="lessee_middle_name" name="lessee_middle_name" value="<?php echo esc_attr(get_post_meta($lease_id, '_yl_l_middle_name', true)); ?>" readonly="readonly" />
                          </div>
                          <div class="col-md-4">
                              <label for="lessee_last_name">Last Name <img class="edit_input" src="<?php echo YL_URL; ?>images/edit.png" alt="Edit" data-edit = "lessee_last_name" /></label>
                              <input class="form-control" type="text" id="lessee_last_name" name="lessee_last_name" value="<?php echo esc_attr(get_post_meta($lease_id, '_yl_l_last_name', true)); ?>" readonly="readonly" />
                          </div>
                        </div>


                        <div class="row">
                          <div class="col-md-4">
                              <label for="lessee_phone">Phone <img class="edit_input" src="<?php echo YL_URL; ?>images/edit.png" alt="Edit" data-edit = "lessee_phone" /></label>
                              <input class="form-control" type="text" name="lessee_phone" id="lessee_phone" value="<?php echo esc_attr(get_post_meta($lease_id, '_yl_l_phone', true)); ?>" readonly="readonly" />
                          </div>
                          <div class="col-md-8">
                              <label for="lessee_email">Email <img class="edit_input" src="<?php echo YL_URL; ?>images/edit.png" alt="Edit" data-edit = "lessee_email" /></label>
                              <input class="form-control" type="text" name="lessee_email" id="lessee_email" value="<?php echo esc_attr(get_post_meta($lease_id, '_yl_l_email', true)); ?>" readonly="readonly" />
                          </div>
                        </div>

                        <div class="row lessee_address">
                        	<div class="col-md-6 ls_address">
                              <label for="lessee_street_address">Address <img class="edit_input" src="<?php echo YL_URL; ?>images/edit.png" alt="Edit" data-edit = "lessee_street_address" /></label>
                              <input class="form-control" type="text" name="lessee_street_address" id="lessee_street_address" value="<?php echo esc_attr(get_post_meta($lease_id, '_yl_l_street_address', true)); ?>" readonly="readonly" />
                          </div>
                          <div class="col-md-6 ls_address_2">
                              <label for="lessee_address_2">Address Line 2 <img class="edit_input" src="<?php echo YL_URL; ?>images/edit.png" alt="Edit" data-edit = "lessee_address_2" /></label>
                              <input class="form-control" type="text" name="lessee_address_2" id="lessee_address_2" value="<?php echo esc_attr(get_post_meta($lease_id, '_yl_l_address_line_2', true)); ?>" readonly="readonly" />
                          </div>
                        </div>

                        <div class="row lessee_address">
                          <div class="col-md-5 ls_city">
                              <label for="lessee_city">City <img class="edit_input" src="<?php echo YL_URL; ?>images/edit.png" alt="Edit" data-edit = "lessee_city" /></label>
                              <input class="form-control" type="text" name="lessee_city" id="lessee_city" value="<?php echo esc_attr(get_post_meta($lease_id, '_yl_l_city', true)); ?>" readonly="readonly" />
                          </div>

        				<div class="col-md-4 ls_state">
                              <label for="lessee_state">State</label>
                              <select class="form-control" name="lessee_state" id="lessee_state" disabled="disabled">
                              <?php
                                  $lesseeState = get_post_meta($lease_id, '_yl_l_state', true);
                                    
                                  foreach($us_states_full as $us_state) {
                                      if($lesseeState == $us_state) $selected = 'selected="selected"';
                                      else $selected = '';
                                      echo '<option value="'.$us_state.'" '.$selected.'>'.$us_state.'</option>';
                                  }
                              ?>
                              </select>
              				    </div>
                          <div class="col-md-3 ls_zip">
                              <label for="lessee_zip_code">ZIP Code <img class="edit_input" src="<?php echo YL_URL; ?>images/edit.png" alt="Edit" data-edit = "lessee_zip_code" /></label>
                              <input class="form-control" type="text" name="lessee_zip_code" id="lessee_zip_code" value="<?php echo esc_attr(get_post_meta($lease_id, '_yl_l_zip_code', true)); ?>" readonly="readonly" />
                          </div>
                        </div>

                    </div>



                    <div class="summary_right col-md-6">
                        <h3>Guarantor</h3>
                        <div class="row">
                          <div class="col-md-4">
                              <label for="guarantor_first_name">First Name <img class="edit_input" src="<?php echo YL_URL; ?>images/edit.png" alt="Edit" data-edit = "guarantor_first_name" /></label>
                              <input class="form-control" type="text" id="guarantor_first_name" name="guarantor_first_name" value="<?php echo esc_attr(get_post_meta($lease_id, '_yl_g_first_name', true)); ?>" readonly="readonly" />
                          </div>
                          <div class="col-md-4">
                              <label for="guarantor_middle_name">Middle Name <img class="edit_input" src="<?php echo YL_URL; ?>images/edit.png" alt="Edit" data-edit = "guarantor_middle_name" /></label>
                              <input class="form-control" type="text" id="guarantor_middle_name" name="guarantor_middle_name" value="<?php echo esc_attr(get_post_meta($lease_id, '_yl_g_middle_name', true)); ?>" readonly="readonly" />
                          </div>
                          <div class="col-md-4">
                              <label for="guarantor_last_name">Last Name <img class="edit_input" src="<?php echo YL_URL; ?>images/edit.png" alt="Edit" data-edit = "guarantor_last_name" /></label>
                              <input class="form-control" type="text" id="guarantor_last_name" name="guarantor_last_name" value="<?php echo esc_attr(get_post_meta($lease_id, '_yl_g_last_name', true)); ?>" readonly="readonly" />
                          </div>
                        </div>

                        <div class="row">
                          <div class="col-md-4">
                              <label for="guarantor_phone">Phone <img class="edit_input" src="<?php echo YL_URL; ?>images/edit.png" alt="Edit" data-edit = "guarantor_phone" /></label>
                              <input class="form-control" type="text" name="guarantor_phone" id="guarantor_phone" value="<?php echo esc_attr(get_post_meta($lease_id, '_yl_g_phone', true)); ?>" readonly="readonly" />

                          </div>
                          <div class="col-md-8">
                              <label for="guarantor_email">Email <img class="edit_input" src="<?php echo YL_URL; ?>images/edit.png" alt="Edit" data-edit = "guarantor_email" /></label>
                              <input class="form-control" type="text" name="guarantor_email" id="guarantor_email" value="<?php echo esc_attr(get_post_meta($lease_id, '_yl_g_email', true)); ?>" readonly="readonly" />

                          </div>
                        </div>

                        <div class="row guar_address">
                        	<div class="col-md-6 lg_address">
                              <label for="guarantor_street_address">Address <img class="edit_input" src="<?php echo YL_URL; ?>images/edit.png" alt="Edit" data-edit = "guarantor_street_address" /></label>
                              <input class="form-control" type="text" name="guarantor_street_address" id="guarantor_street_address" value="<?php echo esc_attr(get_post_meta($lease_id, '_yl_g_street_address', true)); ?>" readonly="readonly" />
                          </div>
                          <div class="col-md-6 lg_address_2">
                              <label for="guarantor_address_2">Address Line 2 <img class="edit_input" src="<?php echo YL_URL; ?>images/edit.png" alt="Edit" data-edit = "guarantor_address_2" /></label>
                              <input class="form-control" type="text" name="guarantor_address_2" id="guarantor_address_2" value="<?php echo esc_attr(get_post_meta($lease_id, '_yl_g_address_line_2', true)); ?>" readonly="readonly" />
                          </div>
                        </div>

                        <div class="row guar_address">
                          <div class="col-md-5 lg_city">
                              <label for="guarantor_city">City <img class="edit_input" src="<?php echo YL_URL; ?>images/edit.png" alt="Edit" data-edit = "guarantor_city" /></label>
                              <input class="form-control" type="text" name="guarantor_city" id="guarantor_city" value="<?php echo esc_attr(get_post_meta($lease_id, '_yl_g_city', true)); ?>" readonly="readonly" />

        					        </div>
                          <div class="col-md-4 lg_state">
                              <label for="guarantor_state">State</label>
                              <select class="form-control" name="guarantor_state" id="guarantor_state" disabled="disabled">
                              <?php
                                  $guarantorState = get_post_meta($lease_id, '_yl_g_state', true);
                                  foreach($us_states_full as $us_state) {
                                      if($guarantorState == $us_state) $selected = 'selected="selected"';
                                      else $selected = '';
                                      echo '<option value="'.$us_state.'" '.$selected.'>'.$us_state.'</option>';
                                  }
                              ?>
                              </select>
        					        </div>
                          <div class="col-md-3 lg_zip">
                              <label for="guarantor_zip_code">ZIP Code <img class="edit_input" src="<?php echo YL_URL; ?>images/edit.png" alt="Edit" data-edit = "guarantor_zip_code" /></label>
                              <input class="form-control" type="text" name="guarantor_zip_code" id="guarantor_zip_code" value="<?php echo esc_attr(get_post_meta($lease_id, '_yl_g_zip_code', true)); ?>" readonly="readonly" />

                          </div>
                        </div>
                    </div>

                    <div class="col-md-12">
                      <h3>Company</h3>
                      <div class="row">
                          <div class="col-md-8">
                              <label for="company_name">Company Name</label>
                              <?php
                                  $company_name = get_post_meta($lease_id, '_yl_company_name', true);
                                  yl_post_select_field('company', 'company_name', $company_name, 'form-control');
                              ?>
                          </div>
                          <div class="col-md-4" id="companyType">
                              <label for="company_type">Type</label>
                              <?php
                                $c_type = get_post_meta($lease_id, '_yl_company_type', true);

                                $args = array(
                                  'show_option_none'  => __( 'Select Type' ),
                                  'show_count'        => 1,
                                  'orderby'           => 'name',
                                  'name'              => 'company_type',
                                  'taxonomy'          => 'companytype',
                                  'selected'          => $c_type,
                                  'show_count'        => 0,
                                  'hierarchical'      => 1,
                                  'hide_empty'        => 0,
                                  'class'             => 'form-control'
                                );
                                wp_dropdown_categories( $args );
                              ?>
                              </p>
                          </div>
                        </div>
                    </div>

                  </div> <!-- /. row -->

  				        <div class="row lease_bottom_info">
                      <div class="col-md-2">
                        <label for="suite_number">Suite Number</label>
                        <input class="form-control" type="text" name="suite_number" id="suite_number" value="<?php echo esc_attr(get_post_meta($lease_id, '_yl_suite_number', true)); ?>" readonly="readonly" />
                      </div>

                      <div class="col-md-2">
                        <label for="lease_start_date">Lease Start Date</label>
                        <div class="input-group">
                          <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
                          <input class="form-control" type="text" name="lease_start_date" id="lease_start_date" value="<?php echo esc_attr(get_post_meta($lease_id, '_yl_lease_start_date', true)); ?>" readonly="readonly" />
                        </div>
                      </div>

                      <div class="col-md-2">
                        <label for="monthly_rent">Monthly Recurring Rent <!--Rate--></label>
                        <div class="input-group">
                          <div class="input-group-addon">$</div>
                          <input class="form-control" type="text" name="monthly_rent" id="monthly_rent" value="<?php echo esc_attr(get_post_meta($lease_id, '_yl_monthly_rent', true)); ?>" readonly="readonly" />
                        </div>
                      </div>

                      <div class="col-md-2">
                        <label for="security_deposit">Security Deposit</label>
                        <div class="input-group">
                          <div class="input-group-addon">$</div>
                          <input class="form-control" type="text" name="security_deposit" id="security_deposit" value="<?php echo esc_attr(get_post_meta($lease_id, '_yl_security_deposit', true)); ?>" readonly="readonly" />
                        </div>
                      </div>

                      <div class="col-md-2">
                        <label for="first_month_rent_rate">First Month Due</label>
                        <div class="input-group">
                          <div class="input-group-addon">$</div>
                          <input class="form-control" type="text" name="first_month_rent_rate" id="first_month_rent_rate" value="<?php echo esc_attr(get_post_meta($lease_id, '_yl_first_month_rent_rate', true)); ?>" readonly="readonly" />
                        </div>
                      </div>

                      <?php
                      $product_id = get_post_meta($lease_id, '_yl_product_id', true);
                      $prod_post_terms = wp_get_post_terms( $product_id, 'suitestype' );
                      ?>

                      <div class="col-md-2">
                        <label for="vacate_notice">Vacate Notice <img class="edit_input" src="<?php echo YL_URL; ?>images/edit.png" alt="Edit" data-edit = "vacate_notice" /></label>
                        <?php
                            if($lease_created) {
                                $vacate_notice = get_post_meta($lease_id, '_yl_vacate_notice', true);
                            }
                            else {
                                if (($prod_post_terms[0]->slug == 'storage') || (get_post_meta($lease_id, '_yl_product_id', true) == -1)) {
                                  $vacate_notice = get_option('yl_storage_vacate_notice');
                                }
                                else {
                                  $vacate_notice = get_option('yl_vacate_notice');
                                }
                            }
                        ?>
                        <input class="form-control" type="text" name="vacate_notice" id="vacate_notice" value="<?php echo esc_attr($vacate_notice); ?>" readonly="readonly" />
                      </div>

                      <?php
                        if ($prod_post_terms[0]->slug != 'storage') {
                        ?>
                      <div class="lease_additional_info clear">
                          <div class="col-md-2">
                              <label for="service_fees">Service Fees <img class="edit_input" src="<?php echo YL_URL; ?>images/edit.png" alt="Edit" data-edit="service_fees"></label>
                                <div class="input-group">
                                  <div class="input-group-addon">$</div>
                                  <input type="text" class="form-control" name="service_fees" id="service_fees" readonly="readonly" value="<?php echo !empty($lease_created) ? esc_attr(get_post_meta($lease_id, '_yl_service_fees', true)) : esc_attr(get_option('yl_service_fees')); ?>" />
                                </div>
                          </div>

                          <div class="col-md-2">
                              <label for="phone_fee">Phone Service <img class="edit_input" src="<?php echo YL_URL; ?>images/edit.png" alt="Edit" data-edit="phone_fee"></label>
                                <div class="input-group">
                                  <div class="input-group-addon">$</div>
                                  <input type="text" class="form-control" name="phone_fee" id="phone_fee" readonly="readonly" value="<?php echo !empty($lease_created) ? esc_attr(get_post_meta($lease_id, '_yl_phone_fee', true)) : esc_attr(get_option('yl_phone_fee')); ?>" />
                                </div>
                          </div>

                          <div class="col-md-2">
                              <label for="cable_fee">Cable Service <img class="edit_input" src="<?php echo YL_URL; ?>images/edit.png" alt="Edit" data-edit="cable_fee"></label>
                                <div class="input-group">
                                  <div class="input-group-addon">$</div>
                                  <input type="text" class="form-control" name="cable_fee" id="cable_fee" readonly="readonly" value="<?php echo !empty($lease_created) ? esc_attr(get_post_meta($lease_id, '_yl_cable_fee', true)) : esc_attr(get_option('yl_cable_fee')); ?>" />
                                </div>
                          </div>

                          <div class="col-md-2">
                              <label for="ipservice_fee">IP Service <img class="edit_input" src="<?php echo YL_URL; ?>images/edit.png" alt="Edit" data-edit="ipservice_fee"></label>
                                <div class="input-group">
                                  <div class="input-group-addon">$</div>
                                  <input type="text" class="form-control" name="ipservice_fee" id="ipservice_fee" readonly="readonly" value="<?php echo !empty($lease_created) ? esc_attr(get_post_meta($lease_id,'_yl_ipservice_fee', true)) : esc_attr(get_option('yl_ipservice_fee')); ?>" />
                                </div>
                          </div>

                          <div class="col-md-2">
                              <label for="fax_fee">Fax Service <img class="edit_input" src="<?php echo YL_URL; ?>images/edit.png" alt="Edit" data-edit="fax_fee"></label>
                                <div class="input-group">
                                  <div class="input-group-addon">$</div>
                                  <input type="text" class="form-control" name="fax_fee" id="fax_fee" readonly="readonly" value="<?php echo !empty($lease_created) ? esc_attr(get_post_meta($lease_id, '_yl_fax_fee', true)) : esc_attr(get_option('yl_fax_fee')); ?>" />
                                </div>
                          </div>

                          <div class="col-md-2">
                              <label for="postage_fee">Postage Service <img class="edit_input" src="<?php echo YL_URL; ?>images/edit.png" alt="Edit" data-edit="postage_fee"></label>
                                <div class="input-group">
                                  <div class="input-group-addon">$</div>
                                  <input type="text" class="form-control" name="postage_fee" id="postage_fee" readonly="readonly" value="<?php echo !empty($lease_created) ? esc_attr(get_post_meta($lease_id, '_yl_postage_fee', true)) : esc_attr(get_option('yl_postage_fee')); ?>" />
                                </div>
                          </div>

                        </div>
                        <div class="lease_additional_info clear">

                          <div class="col-md-2">
                              <label for="credit_card_line_fee">Credit Card Line <img class="edit_input" src="<?php echo YL_URL; ?>images/edit.png" alt="Edit" data-edit="credit_card_line_fee"></label>
                                <div class="input-group">
                                  <div class="input-group-addon">$</div>
                                  <input type="text" class="form-control" name="credit_card_line_fee" id="credit_card_line_fee" readonly="readonly" value="<?php echo  !empty($lease_created) ? esc_attr(get_post_meta($lease_id, '_yl_credit_card_line_fee', true)) : esc_attr(get_option('yl_credit_card_line_fee')); ?>" />
                                </div>
                          </div>

                          <div class="col-md-3 promotional_code_wrap">
                            <label for="promotional_code">Promotional Code</label>
                            <div class="input-group">
                              <div class="input-group-addon"><i class="fa fa-ticket"></i></div>
                              <input class="form-control" type="text" name="promotional_code" id="promotional_code" value="<?php echo esc_attr(get_post_meta($lease_id, '_yl_promotional_code', true)); ?>" />
                            </div>
                          </div>

                          <div class="col-md-1">
                            <label>&nbsp;</label>
                            <button class="btn btn-primary form-control apply-btn" type="button" id="apply_coupon" name="apply_coupon" data-lease="<?php echo $lease_id; ?>">Apply</button>
                            <input type="hidden" value="<?php echo esc_attr(get_post_meta($lease_id, '_yl_mk_aux_promo', true)); ?>" name="mk_aux_promo" id="mk_aux_promo">
                          </div>

                          <!--
                          <div class="col-md-2">
                              <label for="multi_suite_discount" style="float: left; margin-right: 10px;">Multi Suite Discount</label>
                                  <input type="checkbox" name="yl_multisite_coupon" id="yl_multisite_coupon" value="multisite" style="width: auto;" <?php if(get_post_meta($lease_id, '_yl_multisite_coupon', true)) echo 'checked'; ?> />
                              <?php
                                  //if(get_post_meta($lease_id, '_yl_multisite_coupon', true)) {
                              ?>
                              <br />
                              <div class="input-group">
                                <div class="input-group-addon">$</div>
                                <input class="form-control" type="text" name="multi_suite_discount" id="multi_suite_discount" value="<?php echo get_post_meta($lease_id, '_yl_multi_suite_discount', true); ?>" readonly="readonly" />
                              </div>
                              <?php //} ?>
                          </div>
                          -->
                          <div class="col-md-6">
                              <label for="addendums">Addendums <img class="edit_input" src="<?php echo YL_URL; ?>images/edit.png" alt="Edit" data-edit = "addendums" /></label>
                              <input class="form-control" type="text" name="addendums" id="addendums" value="<?php echo esc_attr(get_post_meta($lease_id, '_yl_addendums', true)); ?>" readonly="readonly" />
                          </div>
                      </div>
                      <?php
                        }
                      ?>
                  </div>

                  <hr />

                  <div class="row signature_info clear">

                      <div class="col-md-4 text-left">
                        <label for="date">Date</label>
                        <?php
                          if(get_post_meta($lease_id, '_yl_bm_signature_date', true)) {
                            $signature_date = get_post_meta($lease_id, '_yl_bm_signature_date', true);
                          }
                          else {
                            $signature_date = date('Y-m-d');
                          }
                        ?>
                        <div class="input-group">
                          <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
                          <input class="form-control" type="text" class="leasedatepicker" name="date" id="date" value="<?php echo esc_attr($signature_date); ?>"/>
                        </div>
                      </div>

                      <div class="col-md-3">&nbsp;</div>

                    	<div class="col-md-5 sign_fields">
                          <p class="drawItDesc text-center">Building Manager signature:
                            <span>Type</span> <input checked="checked" type="radio" name="sig_type" id="sig_type" value="yes"/>
                            <span>or Draw</span> <input type="radio" name="sig_type" id="sig_draw" value="No" />
                          </p>

                          <p><input class="form-control text-center" type="text" name="keypad_sig" id="keypad_sig" value="" placeholder="Type your signature here" /></p>

                          <div class="draw_wrap">
                            <ul class="sigNav">
                              <li class="drawIt"><a href="#draw-it">Draw It</a></li>
                              <li class="clearButton"><a href="#clear">Clear</a></li>
                            </ul>
                            <div class="sig sigWrapper">
                              <canvas class="pad signature_pad" width="430" height="120"></canvas>
                              <input type="hidden" name="imgOutput" class="imgOutput" value="">
                            </div>
                          </div>

                          <?php
                           $sig = get_post_meta($lease_id, '_yl_bm_ls_signature', true);
                           if($sig) {
                              ?>
                              <div class="sig_out text-center">
                                <img src="<?php echo $sig; ?>" alt="Signature"/>
                              </div>
                           <?php
                            }
                          ?>

                      </div>


                            <!--</p>-->
                  </div>

                  <div class="row">
                    <div class="col-md-12">
                      <p class="client_notification clear"><input type="checkbox" name="sent_notification_to_client" id="sent_notification_to_client" value="yes" style="display:inline; width:20px;" /> <label for="sent_notification_to_client" style="display:inline;">Send Notification To Client</label></p>
                    </div>
                  </div>
                  <div class="row lease_buttons">
                    <div class="col-md-6 text-left">
                      <input type="submit" name="ls_submit" id="ls_submit" value="Save" />
                    </div>
                    <div class="col-md-6 text-right">
                      <input type="submit" name="ls_submit_continue" id="ls_submit_continue" value="Save &amp; Continue" />
                    </div>
                  </div>
              </form>
          </div>
      </div><!-- .lease_summary_container -->
  	  <?php
      endwhile;

      wp_reset_postdata();
      ?>
  	  <script type="text/javascript">
          jQuery(document).ready(function($) {

              jQuery('#searchSuites').click(function(event) {
                  event.preventDefault();
              });

              jQuery( "#lease_step_2 #is_lessee_guarantor" ).on( "change", function() {

              });

              jQuery("#ls_edit").click(function() {
                  jQuery("#lessor").focus();
                  jQuery(".sigPad").find('input').removeAttr('readonly');
                  jQuery(".sigPad").find('select').removeAttr('disabled');
				  jQuery('#first_month_rent_rate').attr('readonly', 'readonly');
              });

              jQuery( ".lease_details" ).on( "focus", "#promotional_code", function() {
  				      jQuery(this).removeAttr('readonly');
              });

			  jQuery( "#lease_start_date" ).on( "change", function() {
			  		var lease_start_date = jQuery('#lease_start_date').val();
					var ymd = lease_start_date.split('-');
					//var display_data = 'Year: '+ymd[0]+'Month:'+ymd[1]+'Day: '+ymd[2];
					var total_days_of_month = new Date(ymd[0],ymd[1],1,-1).getDate();
					var remaining_days = (total_days_of_month - ymd[2]) + 1;
					var monthly_rent_rate = jQuery('#monthly_rent').val();
					var fees_per_day = monthly_rent_rate / total_days_of_month;
					var fees_for_remaining_days = fees_per_day * remaining_days;
					fees_for_remaining_days = Number(fees_for_remaining_days).toFixed(2);
					jQuery('#first_month_rent_rate').val(fees_for_remaining_days);
					//alert(fees_for_remaining_days);
              });

        			jQuery(".edit_input").click(function() {
        				var input_field_id = jQuery(this).attr('data-edit');
        				jQuery('#'+input_field_id).removeAttr('readonly');
        				jQuery('#'+input_field_id).focus();
        			});


  			// Type Or Draw


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
  		endif;
  		$yl_lease_summary = ob_get_contents();
  		ob_end_clean();
  	}
  }

	return $yl_lease_summary;
}
add_shortcode('bm-lease-summary','yl_bm_lease_summary_shortcode');
