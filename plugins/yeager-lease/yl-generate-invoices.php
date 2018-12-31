<?php
add_action('admin_menu', 'yl_register_generate_invoices_page');
function yl_register_generate_invoices_page() {
	add_submenu_page( 'edit.php?post_type=sa_invoice', 'Generate Invoices', 'Generate Invoices', 'edit_posts', 'generate-invoices-bq', 'yl_generate_invoices_callback' );
}


function yl_generate_invoices_callback() {
	?>
	<div class="wrap">
		<h1>Generate Invoices</h1>

		<div class="yl_generate_form">

			<?php
						
			$rollback_date = get_option('_yl_generated_invoices_batch');
			if ($rollback_date) { 
				?>
				<div class="yl_row">
					<p>The last batch of invoices generated is from <strong><?php echo $rollback_date; ?></strong>. Please remember that if you generate a new batch you will not be able to rollback to anything earlier than <?php echo $rollback_date; ?>.</p>
				</div>
				<?php
			} 
			?>

			<div class="yl_row">
			    <label for="_invoice_auxiliary_date" class="yl_label">Invoice Due Date</label>
			    <input type="text" name="_invoice_auxiliary_date" value="<?php echo ((isset($_SESSION['due_date'])) ? $_SESSION['due_date'] : date('Y-m-d', strtotime('first day of next month')) ); ?>" class="datepicker yl_gen_date" data-bvalidator="required" data-bvalidator-msg="Please Add due date it is required " />
			</div>

			<div class="yl_row">
			    <label for="_invoice_auxiliary_id" class="yl_label">Aux Charges File</label>
			    <select name="_invoice_auxiliary_id" class="_invoice_auxiliary_id">
			    	<option value="11683" selected="selected">November</option>
			        <option value="0">Select</option>

			        <?php
			        $iiii = 0;

			        $argsinvoice = array(
			            'post_type' => 'auxiliary_charges',
			            'posts_per_page' => -1,
			            'post_status' => 'publish',
			            'orderby' => 'id',
			            'order' => 'DESC'
			        );

			        $the_query = new WP_Query($argsinvoice);
			        if ($the_query->have_posts()) {

			            while ($the_query->have_posts()) {
			                $the_query->the_post();
			                $id = get_the_id();
			                // echo get_the_title( );
			                ?>
			                <option value="<?php echo $id; ?>" <?php
			                if (isset($_SESSION['auxiliary_charges_id']) && $id == $_SESSION['auxiliary_charges_id']) {
			                    echo "selected";
			                }
			                if ($iiii == 0) {
			                	echo "selected";
			                }
			                ?>><?php echo get_the_title(); ?></option>
	                        <?php

	                        $iiii++;
	                    }
	                }
	                wp_reset_postdata();
	                wp_reset_query();
	                ?>

			    </select>
			</div>
            
            <div class="yl_row">
			    <label for="_invoice_auxiliary_id" class="yl_label">Client</label>
			    <select name="_lease_user" class="_lease_user">
			    	<option value="0">All</option>

			        <?php
			        $iiii = 0;

			        $args = array( 'role' => 'lease_client' );

			        $the_query = new WP_User_Query( $args );
//echo '<pre>';					print_r($the_query); exit;
			        if(!empty($the_query->results)) {

			            foreach($the_query->results as $user) {
			                // echo get_the_title( );
			                ?>
			                <option value="<?php echo $user->ID; ?>"> <?php echo $user->user_email; ?></option>
	                        <?php

	                        $iiii++;
	                    }
	                }
	                wp_reset_query();
	                ?>

			    </select>
			</div>

			<div class="yl_row">
			    <label class="yl_label"></label>
			    <input type="submit" class="button invoicemk_submit" name="invoicemk_submit" value="Generate"/>
			</div>

		</div>
		
		<pre class="return">
		</pre>
		
		<form class="generated_invoice_log_form" action="" method="post">
			<!--<input type="hidden" class="generated_log_html" name="generated_log_html" id="generated_log_html" value="" />-->
			<div class="generated_log_html" style="display:none;"></div>
			<input type="text" class="email_this_log" name="email_this_log" id="email_this_log" value="" placeholder="Enter Email Address" />
			<input class="button primary submit_generated_log" type="submit" name="submit_generated_log" id="submit_generated_log" value="Send Now!" />
		</form>

	</div>

	<?php

	// Insert JS and CSS code
	yl_generate_invoices_js_callback();	
	yl_generate_invoices_css_callback();
}



function yl_generate_invoices_js_callback() {
	?>

	<script>
	function dump(arr,level) {
		var dumped_text = "";
		if(!level) level = 0;
		
		//The padding given at the beginning of the line.
		var level_padding = "";
		for(var j=0;j<level+1;j++) level_padding += "    ";
		
		if(typeof(arr) == 'object') { //Array/Hashes/Objects 
			for(var item in arr) {
				var value = arr[item];
				
				if(typeof(value) == 'object') { //If it is an array,
					dumped_text += level_padding + "'" + item + "' ...\n";
					dumped_text += dump(value,level+1);
				} else {
					dumped_text += level_padding + "'" + item + "' => \"" + value + "\"\n";
				}
			}
		} else { //Stings/Chars/Numbers etc.
			dumped_text = "===>"+arr+"<===("+typeof(arr)+")";
		}
		return dumped_text;
	}

	jQuery(document).ready(function() {
	
        jQuery('.submit_generated_log').click(function(e) {
        	e.preventDefault();
        	
        	var _generated_log_html = jQuery('.generated_log_html').html();
        	var _email_this_log = jQuery('.email_this_log').val();
        	var data = {
				'action': 'yl_generate_invoices_log_saved',
				'generated_log_html'	: _generated_log_html,
				'email_this_log'	: _email_this_log
			};

			jQuery.post(ajaxurl, data, function(response) {
				msg_arr = jQuery.parseJSON(response);
				var message = msg_arr.msg;
				var generated_log_html = msg_arr.generated_log_html;
				var email_this_log = msg_arr.email_this_log;
				//alert(generated_log_html);
				//yl_clear();
				yl_print('<div class="updated"><p>Log email successfully sent!</p></div>');
				
			});

        });

        jQuery('.invoicemk_submit').click(function(e) {
        	e.preventDefault();
        	
        	ids_arr = '';
			ids_total = 0;
			ids_i = 0;
			ids_i_display = 1;

        	yl_clear();
			yl_print('Fetching leases... please wait.');
			yl_print('<strong>Do not close this window until we tell you it is safe to do so.</strong>');


        	var _d = jQuery('.yl_gen_date').val();
        	var _aux = jQuery('._invoice_auxiliary_id').val();
			var _user = jQuery('._lease_user').val();
        	var data = {
				'action': 'yl_generate_invoices_get_clients_leases',
				'date'	: _d,
				'aux'	: _aux,
				'user' : _user
			};

			jQuery.post(ajaxurl, data, function(response) {
				yl_print('');
				//yl_print(response);
              
				ids_arr = jQuery.parseJSON(response);
				ids_total = ids_arr.length;

				yl_print('Found '+ids_total+' clients to generate invoices for.');
				yl_print('');
				if(ids_total > 0){
					yl_start_loop();
				}
			});

        });

        function yl_start_loop() {
			var client_id = ids_arr[ids_i].client_id;
			var client_leases = ids_arr[ids_i].leases;
			var _aux = jQuery('._invoice_auxiliary_id').val();
			var _d = jQuery('.yl_gen_date').val();

			yl_print('<center><strong>[Client '+ids_i_display+' of '+ids_total+']</strong></center>');

			var data = {
				'action': 'yl_generate_invoices_generate_client_invoice',
				'client_id'	: client_id,
				'client_leases' : client_leases,
				'aux'	: _aux,
				'date'	: _d,
			};

			jQuery.post(ajaxurl, data, function(response) {
				yl_print('');
				yl_print(response);
                
				jQuery("html, body").animate({ scrollTop: jQuery(document).height() }, 1000);

				// Finish process increase by one and jump to the
				// next client.
				ids_i++;
				ids_i_display++;

				if (ids_i_display > ids_total) {
					// Loop finished, so we generate the batch code.
					
					var data2 = {
						'action': 'yl_generate_invoices_end_generation_batch',
						'client_id'	: client_id,
						'aux'	: _aux
					};
					jQuery.post(ajaxurl, data2, function(response2) {
						//alert('end');
						yl_print('Invoice Generation Complete.  You can now close the window.');
						jQuery( ".generated_invoice_log_form" ).show( "slow", function() {
							// Animation complete.
						  });						
					});
					
					//alert('end');
				}
				else {
					// Continue the loop
					yl_start_loop();
				}
			}).fail(function() {
				// Possibily 404 error. Try again.
				yl_start_loop();
			});
		}

        function yl_clear() {
			jQuery('.return').html('');
		}

		function yl_print(msg) {
			jQuery('.return').html(jQuery('.return').html()+'<br>'+msg);
			jQuery('.generated_log_html').html(jQuery('.generated_log_html').html()+'<br>'+msg);
			//jQuery('.generated_log_html').val(jQuery('.generated_log_html').val()+'<br>'+msg);			
		}

	});
	</script>

	<?php
}

function yl_generate_invoices_css_callback() {
	?>

	<style>
		.yl_generate_form {
			padding: 20px 0px;
		}
		.yl_row {
			display: block;
			padding-bottom: 10px;
		}
		.yl_label {
			display: inline-block;
			width: 150px;
		}
		.generated_invoice_log_form{ display:none; }
	</style>
	
	<?php
} 
 
add_action( 'wp_ajax_yl_generate_invoices_log_saved', 'yl_generate_invoices_log_saved_callback' );
function yl_generate_invoices_log_saved_callback() {
	$generated_log_html = $_POST['generated_log_html'];
    $email_this_log = $_POST['email_this_log'];
	 
	$message = array("msg" => "Under Construction!", "generated_log_html" => $generated_log_html, "email_this_log" => $email_this_log);
	
	$user_email = $email_this_log;
	$email_subject = 'Generated Invoice Log - '.date('Y-m-d');
	$message_send = $generated_log_html;		  
	$headers  = 'MIME-Version: 1.0' . "\r\n";
	//$headers .= 'Content-type: text/plain; charset=iso-8859-1' . "\r\n";
	add_filter('wp_mail_content_type',create_function('', 'return "text/html"; '));			
	@wp_mail( $user_email, $email_subject, $message_send, $headers );	
	
	echo json_encode($message);
    wp_die();
}

/**
 * Ajax functions
 *
 * The following actions and functions belong to the invoice rollback process.
 */
add_action( 'wp_ajax_yl_generate_invoices_get_clients_leases', 'yl_generate_invoices_get_clients_leases_callback' );
function yl_generate_invoices_get_clients_leases_callback() {
    
  
    
	/**
	 * This function retrieves a list of all leases, and generates an array of all the
	 * 'invoice-able' leases ordered by client id. This way we can then
	 * do 1 ajax call for each client and generate 1 invoice at a time.
	 */
	global $wpdb; // this is how you get access to the database

	$auxiliary_charges_id = $_POST['aux'];
    $issue_date = $_POST['date'];
	$lease_user = $_POST['user'];

    // Let's get information from the auxiliary charges being used
    $datepicker_startdate = get_post_meta($auxiliary_charges_id, 'mk_auxiliary_date_startdate', true);
    $datepicker_enddate = get_post_meta($auxiliary_charges_id, 'mk_auxiliary_date_enddate', true);

    $args = array(
        'post_type' => 'lease',
        'posts_per_page' => -1,
        'post_status' => 'publish',
        'orderby' => 'id',
        'order' => 'ASC',
        'meta_query' => array(
            'relation' => 'AND',
            array(
                'relation' => 'OR',
                array(
                    'key' => '_yl_ninty_day_vacate_date',
                    'value' => $datepicker_startdate,
                    'compare' => '>=',
                    'type' => 'Date'
                ),
                array(
                    'key' => '_yl_ninty_day_vacate_date',
                    'compare' => 'NOT EXISTS',
                ),
				array(
                    'key' => '_yl_ninty_day_vacate_date',
                    'value' => ' ',
                    'compare' => '=',
                ),
            ),
            array(
                'key' => '_yl_lease_start_date',
                'value' => $datepicker_enddate,
                'compare' => '<=',
                'type' => 'Date'
            ),
            array(
                'key' => '_yl_mk_i_' . date('Y_m_d', time()),
                'compare' => 'NOT EXISTS'
            ),
        )
    );
	if(!empty($lease_user)){
	$args['meta_query'][]=array(
                'key' => '_yl_lease_user',
                'value' => $lease_user,
                'compare' => '='
            );
	}
	$leases = get_posts($args);
//echo '<pre>'; print_r($leases); exit;
	//echo '# leases: '.count($leases).'<br>';

	$to_return = array();
	foreach ($leases as $lease) {
		$leasd_id = $lease->ID;
		$suite_id = get_post_meta($leasd_id, '_yl_product_id', true);
        $user_id = get_post_meta($leasd_id, '_yl_lease_user', true);

		$meta = get_post_meta($leasd_id);

		if (is_numeric($meta['_yl_lease_user'][0])) {
			$to_return[$meta['_yl_lease_user'][0]]['client_id'] = yl_get_client_id_by_user_id($user_id);

			if ($meta['_yl_lease_user'][0]) {
				// Lets do our first check to decide if the lease goes in the list or not
				$_yl_lease_start_date = get_post_meta($leasd_id, '_yl_lease_start_date', true);
	            $_yl_ninty_day_vacate_date = get_post_meta($leasd_id, '_yl_ninty_day_vacate_date', true);

	            $todaydate = date('Y-m-d');
	            $todate = explode('-', $todaydate);
	            $monthmk = $todate[1] + 1;
	            if ($monthmk == 13) {
	                $monthmk = '01';
	                $todate[0] = ($todate[0]+1);
	            }

	            $days = cal_days_in_month(CAL_GREGORIAN, $monthmk, $todate[0]);
	            $frst_check = $todate[0] . "-" . $monthmk . "-01";
	            $last_date = $todate[0] . "-" . $monthmk . "-" . $days;

	            $frst_check = strtotime($frst_check);
	            $last_date_check = strtotime($last_date);

	            $_yl_lease_start_date_check = strtotime($_yl_lease_start_date);

	            if ($frst_check <= $_yl_lease_start_date_check && $_yl_lease_start_date_check <= $last_date_check) {
	            	/*
	                $link = get_home_url() . "/wp-admin/post.php?post=" . $leasd_id . "&action=edit";
	                echo "This Lease is new lease and will not be included in invoice generation process <a href='" . $link . "'>" . $leasd_id . "</a>";
	                echo "<br>";
	                */
	            } 
	            else {
					$to_return[$meta['_yl_lease_user'][0]]['leases'][] = $lease->ID;
				}
			}
		}
	}

	// Lets clean the array so we return an array with numeric 'non id' indexes
	// This way it is easier to loop through in JS.
	$to_return_two = array();
	foreach ($to_return as $item) {
		$to_return_two[] = $item;
	}

	echo json_encode($to_return_two);
    wp_die();
}


add_action( 'wp_ajax_yl_generate_invoices_generate_client_invoice', 'yl_generate_invoices_generate_client_invoice_callback' );
function yl_generate_invoices_generate_client_invoice_callback() {
	global $wpdb; // this is how you get access to the database

	echo '<table class="wp-list-table widefat fixed striped posts">';
	
	$blog_home_link = get_home_url() . "/wp-admin/";

	$client_id = $_POST['client_id'];
	$client_leases = $_POST['client_leases'];
	$auxiliary_charges_id = $_POST['aux'];
	$issue_date = $_POST['date'];

	echo '<tr><td>Client: <a href="'.$blog_home_link.'post.php?post='.$client_id.'&action=edit">#'.$client_id.'</a></td></tr>';

	$datepicker_startdate = get_post_meta($auxiliary_charges_id, 'mk_auxiliary_date_startdate', true);
    $datepicker_enddate = get_post_meta($auxiliary_charges_id, 'mk_auxiliary_date_enddate', true);

    $client_id_mk_check = array();
	$lease_total_array = array();
	$client_total_array = array();
	$written_off_total_array = array();
	$fields_listing = array();
    $credit_listing = array();

	foreach ($client_leases as $lease_id) {
		$suite_id = get_post_meta($lease_id, '_yl_product_id', true);
        $user_id = get_post_meta($lease_id, '_yl_lease_user', true);

        if (!$suite_id) {
        	// This lease doesn't have a suite ID associated to it. So we try to get the suite
        	// from the 'title' saved within the meta
        	$suite_name_str = get_post_meta($lease_id, '_yl_suite_number', true);
        	$tmp_suite = get_page_by_title( $suite_name_str, 'OBJECT', 'suites' );
			$suite_id = $tmp_suite->ID;
        }

		// Mark this lease as looper through in the DB
		update_post_meta($lease_id, '_yl_mk_i_' . date('Y_m_d', time()), '1');

		$suite_title = get_the_title($suite_id);
		if ($suite_id == '-1') {
            $suite_title = "Y Membership";
        }
        else if ($suite_id == '') {
            $suite_title = "Y Membership";
        }

        echo '<tr><td>Fetching lease <strong><a href="'.$blog_home_link.'post.php?post='.$lease_id.'&action=edit">#'.$lease_id.'</a></strong> for suite <strong><a href="'.$blog_home_link.'post.php?post='.$suite_id.'&action=edit">'.$suite_title.'</a></strong><br>';

        // Let's check if this lease has already started
        // If move-in-date is in the future, then lets not bill it.
        $lease_start_date = get_post_meta($lease_id, '_yl_lease_start_date', true);
        echo '<tr><td><strong>Lease Start Date:</strong> '.$lease_start_date.' ';
        $first_day_of_month_timestamp = strtotime(date('Y-m-01', time()));
        $last_day_of_month_timestamp = strtotime(date('Y-m-t', time()));
        $lease_start_date_timestamp = strtotime($lease_start_date);

        if (($lease_start_date_timestamp >= $first_day_of_month_timestamp) && ($lease_start_date_timestamp <= $last_day_of_month_timestamp)) {
        	echo 'this month<br>';
		}
		elseif ($lease_start_date_timestamp > $last_day_of_month_timestamp) {
			echo 'coming months<br>';
		}
		else {
			echo 'past months<br>';
		}

		// If move in date is in the future, then we need to avoid billing this lease.
		if ($lease_start_date_timestamp > $last_day_of_month_timestamp) {
			continue;
		}


        if ($suite_id == NULL || $suite_id == "") {
            $suite_id = get_post_meta($lease_id, '_yl_lease_user', true);
        }

        $company_id = get_post_meta($lease_id, '_yl_company_name', true);
        $company_title = get_the_title($company_id);
        $invoice_id = yl_get_invoice_id_by_lease_id($lease_id);
        $subject_name = get_the_title($company_id);

		////////////////////////////////////////////////////////
        // Let's start generating all the line items
        // including the new accounting category parameter
        ////////////////////////////////////////////////////////

        // Monthly rent (rent)      
        $monthly_rent = get_post_meta($lease_id, '_yl_monthly_rent', true);

        // We have a 'base' monthly rent, but we need to make sure we prorate the rent
        // if the client is leaving the office this month.
		$ninty_day_vacate_date = get_post_meta($lease_id, '_yl_ninty_day_vacate_date', true);
        if ($ninty_day_vacate_date) {
	        echo '<tr><td><strong>Startdate:</strong> '.$datepicker_startdate.' - <strong>Enddate:</strong> '.$datepicker_enddate.'<br>';
	    	echo 'Vacate date: '.$ninty_day_vacate_date.'<br>';

			$firstDayNextMonth = date('Y-m-d', strtotime('first day of next month'));
			$lastDayNextMonth = date('Y-m-d', strtotime('last day of next month'));

			if ((strtotime($ninty_day_vacate_date) >= strtotime($firstDayNextMonth)) && (strtotime($ninty_day_vacate_date) <= strtotime($lastDayNextMonth))) {
				$vacate_parts = explode('-', $ninty_day_vacate_date);
				$lastday_parts = explode('-', $lastDayNextMonth);
				echo 'Day leaving: '.$vacate_parts[2].' of '.$lastday_parts[2].'<br>';
				echo 'Total rent: $'.get_post_meta($lease_id, '_yl_monthly_rent', true).'<br>';
				$prorated_rent = round((get_post_meta($lease_id, '_yl_monthly_rent', true)/$lastday_parts[2])*$vacate_parts[2], 2, PHP_ROUND_HALF_DOWN);
				echo 'Prorated rent: $'.$prorated_rent;

				if ($prorated_rent < $monthly_rent) {
					$monthly_rent = $prorated_rent;
				}
			}
	    	echo '</td></tr>';
	    }
        
        $monthly_rent_listing = array(
            "desc" 				=> "Monthly Rent for " . $suite_title,
            "qty" 				=> 1,
            "rate" 				=> round($monthly_rent, 2),
            "total" 			=> round($monthly_rent, 2),
            "type" 				=> "service",
			"suite_id" 		=> $suite_id,
            "accounting_cat" 	=> yl_account_category_id_by_wordmatch('Rent')
        );

        // Promotional code (discounts)
        $promotional_code = get_post_meta($lease_id, '_yl_promotional_code', true);
        $promotional_code_listing = array(
            "desc" 				=> "Promotional Code",
            "qty" 				=> 1,
            "rate"	 			=> -round($promotional_code, 2),
            "total" 			=> -round($promotional_code, 2),
            "type" 				=> "service",
			"suite_id" 		=> $suite_id,
            "accounting_cat"	=> yl_account_category_id_by_wordmatch('Discounts')
        );

        // Service Fees (fees)
        $service_fees = get_post_meta($lease_id, '_yl_service_fees', true);
        $service_fees_listing = array(
            "desc" 				=> "Service Fees",
            "qty" 				=> 1,
            "rate" 				=> round($service_fees, 2),
            "total" 			=> round($service_fees, 2),
            "type" 				=> "service",
			"suite_id" 		=> $suite_id,
            "accounting_cat"	=> yl_account_category_id_by_wordmatch('Utilities')
        );

        // Phone Service Fee (phone)
        $lease_phone = get_post_meta($lease_id, '_yl_phone_fee', true);
        $lease_phone_listing = array(
            "desc" 				=> "Phone Service Fee",
            "qty" 				=> 1,
            "rate" 				=> round($lease_phone, 2),
            "total"	 			=> round($lease_phone, 2),
            "type" 				=> "service",
			"suite_id" 		=> $suite_id,
            "accounting_cat"	=> yl_account_category_id_by_wordmatch('Phone')
        );

        // Cable Service Fee (fees)
        $lease_cable = get_post_meta($lease_id, '_yl_cable_fee', true);
        $lease_cable_listing = array(
            "desc" 				=> "Cable Service Fee",
            "qty" 				=> 1,
            "rate" 				=> round($lease_cable, 2),
            "total" 			=> round($lease_cable, 2),
            "type" 				=> "service",
			"suite_id" 		=> $suite_id,
            "accounting_cat"	=> yl_account_category_id_by_wordmatch('Phone')
        );

        // IP Services Fee (fees)
        $lease_ipservices = get_post_meta($lease_id, '_yl_ipservice_fee', true);
	    $lease_ipservices_listing = array(
	        "desc" 				=> "Ip Services Fee",
	        "qty" 				=> 1,
	        "rate" 				=> round($lease_ipservices, 2),
	        "total"	 			=> round($lease_ipservices, 2),
	        "type" 				=> "service",
			"suite_id" 		=> $suite_id,
	        "accounting_cat"	=> yl_account_category_id_by_wordmatch('Phone')
	    );

	    // Fax Service Fee (fees)
	    $lease_faxfee = get_post_meta($lease_id, '_yl_fax_fee', true);
        $lease_faxfee_listing = array(
            "desc" 				=> "Fax Service Fee",
            "qty" 				=> 1,
            "rate" 				=> round($lease_faxfee, 2),
            "total" 			=> round($lease_faxfee, 2),
            "type" 				=> "service",
			"suite_id" 		=> $suite_id,
	        "accounting_cat"	=> yl_account_category_id_by_wordmatch('Phone')
        );

        // Postage Service Fee (postage)
        $lease_postagefee = get_post_meta($lease_id, '_yl_postage_fee', true);
        $lease_postagefee_listing = array(
            "desc" 				=> "Postage Service Fee",
            "qty" 				=> 1,
            "rate" 				=> round($lease_postagefee, 2),
            "total" 			=> round($lease_postagefee, 2),
            "type" 				=> "service",
			"suite_id" 		=> $suite_id,
	        "accounting_cat"	=> yl_account_category_id_by_wordmatch('Postage')
        );

        // Credit Card Line Service Fee (fees)
        $lease_cardline = get_post_meta($lease_id, '_yl_credit_card_line_fee', true);
        $lease_cardline_listing = array(
            "desc" 				=> "Credit Card Line Service Fee",
            "qty" 				=> 1,
            "rate" 				=> round($lease_cardline, 2),
            "total" 			=> round($lease_cardline, 2),
            "type" 				=> "service",
			"suite_id" 		=> $suite_id,
	        "accounting_cat"	=> yl_account_category_id_by_wordmatch('Phone')
        );

        ////////////////////////////////////////////////////////
        // It is now time to generate the line items
        // from the auxiliary charges file
        ////////////////////////////////////////////////////////
        $meta_aux = get_post_meta($auxiliary_charges_id, "mk_auxiliary_" . $company_id);
        //echo "<pre>";print_r($meta_aux);die;
        // Long Distance (long distance)
        $phone_charges = trim($meta_aux[0][$suite_id]['charges'], '$');
        $phone_charges_listing = array(
            "desc" 				=> "Long DIstance",
            "qty" 				=> 1,
            "rate" 				=> round($phone_charges, 2),
            "total" 			=> round($phone_charges, 2),
            "type" 				=> "service",
			"suite_id" 		=> $suite_id,
            "accounting_cat"	=> yl_account_category_id_by_wordmatch('Long')
        );

        // Copier (copies)
        $copier = trim($meta_aux[0][$suite_id]['copier'], '$');
        $copier_fees_listing = array(
            "desc" 				=> "Copier",
            "qty" 				=> 1,
            "rate" 				=> round($copier, 2),
            "total" 			=> round($copier, 2),
            "type" 				=> "service",
			"suite_id" 		=> $suite_id,
            "accounting_cat" 	=> yl_account_category_id_by_wordmatch('Copies')
        );

        // Fax Fees (fees)
        $fax = trim($meta_aux[0][$suite_id]['fax'], '$');
        $fax_fees_listing = array(
            "desc" 				=> "Fax Fees",
            "qty" 				=> 1,
            "rate" 				=> round($fax, 2),
            "total" 			=> round($fax, 2),
            "type" 				=> "service",
			"suite_id" 		=> $suite_id,
            "accounting_cat"	=> yl_account_category_id_by_wordmatch('Phone')
        );

        // Postage Fees (postage)
        $postage = trim($meta_aux[0][$suite_id]['postage'], '$');
        $Postage_fees_listing = array(
            "desc" 				=> "Postage Fees",
            "qty" 				=> 1,
            "rate" 				=> round($postage, 2),
            "total" 			=> round($postage, 2),
            "type" 				=> "service",
			"suite_id" 		=> $suite_id,
            "accounting_cat"	=> yl_account_category_id_by_wordmatch('Postage')
        );
         $retail_utility = trim($meta_aux[0][$suite_id]['retail_utilities'], '$');

                // $service_fees=get_post_meta( $leasd_id, '_yl_service_fees' ,true);
                $retail_utility_fees = array(
                    "desc" => "Retail Utility charge",
                    "qty" => 1,
                    "rate" => round($retail_utility, 2),
                    "total" => round($retail_utility, 2),
                    "type" => "service",
					"accounting_cat"	=> yl_account_category_id_by_wordmatch('Retail Utilities')
                );
                
                $reatil_cam = trim($meta_aux[0][$suite_id]['retail_cam_charges'], '$');

                // $service_fees=get_post_meta( $leasd_id, '_yl_service_fees' ,true);
                $retail_cam_charge = array(
                    "desc" => "Retail CAM charge",
                    "qty" => 1,
                    "rate" => round($reatil_cam, 2),
                    "total" => round($reatil_cam, 2),
                    "type" => "service",
					"accounting_cat"	=> yl_account_category_id_by_wordmatch('Retail CAM charges')
                );

        if ($service_fees != 0 && $service_fees != "") {
            array_push($fields_listing, $service_fees_listing);
        }
		
        if ($phone_charges != 0 && $phone_charges != "") {
            array_push($fields_listing, $phone_charges_listing);
        }
        if ($copier != 0 && $copier != "") {
            array_push($fields_listing, $copier_fees_listing);
        }
        if ($fax != 0 && $fax != "") {
            array_push($fields_listing, $fax_fees_listing);
        }
        if ($postage != 0 && $postage != "") {
            array_push($fields_listing, $Postage_fees_listing);
        }
         if ($retail_utility != 0 && $retail_utility != "") {
                    array_push($fields_listing, $retail_utility_fees);
           }
        if ($reatil_cam != 0 && $reatil_cam != "") {
                    array_push($fields_listing, $retail_cam_charge);
        }
        if ($lease_phone != 0 && $lease_phone != "") {
            array_push($fields_listing, $lease_phone_listing);
        }
        if ($lease_cable != 0 && $lease_cable != "") {
            array_push($fields_listing, $lease_cable_listing);
        }
        if ($lease_ipservices != 0 && $lease_ipservices != "") {
            array_push($fields_listing, $lease_ipservices_listing);
        }
        if ($lease_faxfee != 0 && $lease_faxfee != "") {
            array_push($fields_listing, $lease_faxfee_listing);
        }
        if ($lease_postagefee != 0 && $lease_postagefee != "") {
            array_push($fields_listing, $lease_postagefee_listing);
        }
        if ($lease_cardline != 0 && $lease_cardline != "") {
            array_push($fields_listing, $lease_cardline_listing);
        }
        if ($monthly_rent != 0 && $monthly_rent != "") {
            array_push($fields_listing, $monthly_rent_listing);
        }

        /*
        // No idea what this is,. just in case i paste it here.

        $fields_listing_fun = create_invoice_variation_array($leasd_id,$fields_listing);
		$fields_listing=$fields_listing_fun['listing'];
		$upg_total_price=$fields_listing_fun['total_price'];
		*/

        // Now, depending on the number of active leases, we need to get the % of the discount
        //$num_of_leases = count($client_leases);
        $num_of_leases = count_leases_no_storage_or_ymembership($client_leases);

        $multi_suite_discount = 0;
        if ($num_of_leases >= 1) {
            if ($num_of_leases == 2) {
                $multi_suite_discount = get_option('yl_multisite_discount');
            } elseif ($num_of_leases == 3) {
                $multi_suite_discount = get_option('yl_multisite_discount_3');
            } elseif ($num_of_leases == 4) {
                $multi_suite_discount = get_option('yl_multisite_discount_4');
            } elseif ($num_of_leases >= 5) {
                $multi_suite_discount = get_option('yl_multisite_discount_5');
            }
        }

        if ($multi_suite_discount > 0) {
            // Let's check if this suite deserves a multisuite discount
            // Only real suites do. Not storages or y-memberships
            $_yl_is_storage = get_post_meta($lease_id, '_yl_is_storage', true);
	        $_yl_suite_number = get_post_meta($lease_id, '_yl_suite_number', true);

	        if ($_yl_is_storage == 1 || strpos($_yl_suite_number, 'torage') != false || $_yl_suite_number == 'Y-Membership') {
	        	// Do nothing
	        }
	        else {
	            $multi_suite_discount_total = (float) round(($monthly_rent / 100) * $multi_suite_discount, 2);

	            $invoice_args_disc = array(
	                "desc" 				=> "Multi Suite Discount (" . $multi_suite_discount . "% off rent for ".$suite_title.")",
	                "qty" 				=> 1,
	                "rate" 				=> -round(($monthly_rent / 100) * $multi_suite_discount, 2),
	                "total" 			=> -round(($monthly_rent / 100) * $multi_suite_discount, 2),
	                "type" 				=> "credit",
					"suite_id" 		=> $suite_id,
					"accounting_cat"	=> yl_account_category_id_by_wordmatch('Discounts')
	            );
	            array_push($credit_listing, $invoice_args_disc);
	        }
        }

        // Let's run some filters
        $fields_listing = array_filter($fields_listing);
        $fields_listing = array_values($fields_listing);


        echo '</td></tr>';
	}


	//////////////////////////////////////////////////////
    // Let's start with the upgrades
    // ---------------------------------------------------
    // If any upgrade is found for this client, generate 
    // the needed line items for all active services
    //////////////////////////////////////////////////////
	$upgrades_total = 0;

	$upgr = array();
    $up_val = array();
    $upgrval = array();

    $upgr = get_post_meta($client_id, 'yl_company_upgrade_company', true);

    if ($upgr) {
        $upgr = explode(',', $upgr);
        if (is_array($upgr)) {
            $upgrval = array_merge($upgrval, $upgr);
        }
    }
    $upgrade = get_post_meta($client_id, 'yl_lease_upgrade_products', true);
    if ($upgrade) {
        $val = explode(',', $upgrade);
        if (is_array($val)) {
            $upgrval = array_merge($upgrval, $val);
        }
    }
    $meta = get_post_meta($client_id, 'upgrade_history', true);
    $upgrval = array_unique($upgrval);

    foreach ($upgrval as $val) {
    	$product_id = $val;
    	
    	$product_accounting_cat = get_post_meta($val, '_cw_accounting_cat', true);

        $variations = get_post_meta($product_id, CMB2_PREFIX.'variation', true);
        $upgrade_meta = get_post_meta($client_id, 'yl_lease_upgrade_details_'.$product_id, true);

        $status = $upgrade_meta['is_active'];
        $vid = $upgrade_meta['variation'];

        $variation_title = $variations[$vid]['variation_title'];
        $invoice_title = $variations[$vid]['invoice_description'];
        $upgrades_total = $variations[$vid]['cost']*$upgrade_meta['quantity'];
        
        // Only add a line item for the product if this product/variation has a monthly
        // billing frequency.
        if (strtolower($upgrade_meta['billing_type']) == strtolower('Monthly')) {
	        $upgrades_fees_listing = array(
	            "desc" 				=> $invoice_title,
	            "qty" 				=> $upgrade_meta['quantity'],
	            "rate" 				=> round($upgrades_total, 2),
	            "total" 			=> round($upgrades_total, 2),
	            "type" 				=> "service",
				"suite_id" 		=> $suite_id,
	            "accounting_cat"	=> $product_accounting_cat
	        );

	        $upgrades_total += round($upgrades_total, 2);
	        array_push($fields_listing, $upgrades_fees_listing);
        }

    }



    // Merge arrays
    /*
    THIS HAS TO BE MODDED TO ACCEPT AN ARRAY

    if ($upgrades_total != 0 && $upgrades_total != "") {
        array_push($fields_listing, $upgrades_fees_listing);
    }
	*/


	//////////////////////////////////////////////////////
    // Let's start with the invoices
    // ---------------------------------------------------
    // We first get all the invoices and check which ones
    // can be written off and which ones can't.
    //
    // After that we will generate one big invoices with
    // all the smaller invoices in it and the monthly
    // lease rents.
    //////////////////////////////////////////////////////
    $inv_args = array(
        'post_type' => 'sa_invoice',
        'posts_per_page' => -1,
        'post_status' => array('publish', 'partial'),
        'order' => 'ASC',
        'meta_query' => array(
            array(
                'key' => '_client_id',
                'value' => $client_id,
                'compare' => '=',
            ),
        ),
    );
    $invoice_id = array();
    $invoice_id2 = array();
    $loop = get_posts($inv_args);

    foreach ($loop as $inv) {
    	array_push($invoice_id, $inv->ID);
    }

    $total_due_pay = 0;
    $written_off_invoices = array();

    if (!in_array($client_id, $client_id_mk_check)) {
        if (!empty($invoice_id)) {

        	echo '<tr><td>Found <strong>'.count($invoice_id).'</strong> invoices for this client.<br>Checking for ones that can be added to the new invoice.<br>';
            foreach ($invoice_id as $inid) {
                $balinvoice = SI_Invoice::get_instance($inid);
                $balance = $balinvoice->get_balance();
                $next_month_title_invoice = get_the_title($inid);

                $cll = get_post_meta($inid, '_doc_line_items', false);
                $cll = maybe_unserialize($cll);
                $ymembership = check_lienitem($cll);

                if ($balance <= 350 && $ymembership == false) {
                	// If an suite invoice has a balance of less than $350, it will get
                    // included in the new invoice.
                    $past_invoice = array(
                        "desc" => get_the_title($inid),
                        "qty" => 1,
                        "rate" => $balance,
                        "total" => $balance,
                        "type" => "service",
						"suite_id" 		=> $suite_id,
						"accounting_cat"	=> yl_account_category_id_by_wordmatch('Old Invoices')
                    );

                    array_push($fields_listing, $past_invoice);
                    $total_due_pay = (float) ($total_due_pay + $balance);

                    // Balance change status invoices
                    $date_due = get_post_meta($inid, '_due_date', true);
                    $changestatus = SI_Invoice::get_instance($inid);
                    $changestatus->set_status('write-off');
                    $changestatus->set_client_id($client_id);
                    $changestatus->set_due_date($date_due);

                    // Logging
                    // Let's save this invoice ID so we can later add a meta
                    // to it with the new invoice number.
                    $written_off_invoices[] = $inid;
                    echo '- Invoice <a href="'.$blog_home_link.'post.php?post='.$inid.'&action=edit">#'.$inid.'</a>: <strong>written-off</strong><br>';
                } 
                elseif ($balance <= 195 && $ymembership !== false) {
                	// If a Y-M invoice has a balance of less than $195, it will be
                	// included in the new invoice.
                    $past_invoice = array(
                        "desc" => get_the_title($inid),
                        "qty" => 1,
                        "rate" => $balance,
                        "total" => $balance,
                        "type" => "service",
						"suite_id" 		=> $suite_id,
						"accounting_cat"	=> yl_account_category_id_by_wordmatch('Old Invoices')
                    );
                    array_push($fields_listing, $past_invoice);

                    $total_due_pay = (float) ($total_due_pay + $balance);

                    $changestatus = SI_Invoice::get_instance($inid);
                    $changestatus->set_status('write-off');
                    $changestatus->set_client_id($client_id);

                    // Logging
                    // Let's save this invoice ID so we can later add a meta
                    // to it with the new invoice number.
                    $written_off_invoices[] = $inid;
                    echo '- Invoice <a href="'.$blog_home_link.'post.php?post='.$inid.'&action=edit">#'.$inid.'</a>: <strong>written-off</strong><br>';
                }

                if ($key_replacement_check == false && $build_maintain_check == false) {
                    $amount = get_post_meta($inid, '_total', false);
                    $cll = get_post_meta($inid, '_doc_line_items', false);

                    $key_replacement_check = aux_contains_str("Key Replacement", $cll[0]);
                    $build_maintain_check = aux_contains_str("Building Maintance", $cll[0]);
                    $val_str = aux_contains_str("1st Month", $cll[0]);

                    $client_id_array_org = get_post_meta($inid, '_client_id', false);
                    $client_id_org = $client_id_array_org[0];

                    // $changestatus = SI_Invoice::get_instance($inid);
                    // $changestatus->set_status( 'write-off');
                    // $changestatus->set_client_id( $client_id_org );
                }
            }
            echo '</tr></td>';
        }
    }

    $issue_date_mk = $issue_date;

    $invoice_args = array(
	    'subject' => $company_title . ' Invoice for ' . $issue_date_mk,
	    'user_id' => $user_id,
	    'client_id' => $client_id,
	    'status' => 'publish',
	    'total' => (float) ($upg_total_prices + $monthly_rent + $service_fees + $phone_charges + $copier + $fax + $postage + $total_due_pay + $lease_phone + $lease_cable + $lease_ipservices + $lease_faxfee + $lease_postagefee + $lease_cardline),
	    'currency' => '',
	    'issue_date' => time(),
	    'due_date' => $issue_date,
	    'expiration_date' => 0,
	    'line_items' => $fields_listing,
	    'credit_items' => $credit_listing,
	    'fields' => array(),
	);

    if (!in_array($client_id, $client_id_mk_check)) {
        $client_total_array[$client_id] = array($invoice_args);
        $lease_total_array[$client_id] = array($lease_id);
        array_push($client_id_mk_check, $client_id);
    } 
    else {
        array_push($client_total_array[$client_id], $invoice_args);
        array_push($lease_total_array[$client_id], $invoice_args);
    }

    if (count($written_off_invoices) > 0) {
        $written_off_total_array[$client_id] = $written_off_invoices;
    }


    if (!empty($client_total_array)) {
		
        foreach ($client_total_array as $key => $mm) {
            $count = count($mm);
            $user_id_tmp = get_post_meta($client_id, '_associated_users', true);
            $is_empty = true;

            if ($count > 1) {
                $submk = "";
                $client_idmk = "";
                $user_id_mk = "";
                $status = "publish";
                $total_mk_totla = 0;
                $currency_mk = "";
                $due_date_mk = "";
                $line_items_mk = array();
                $fields = array();
                $mm = array_reverse($mm);

                foreach ($mm as $key => $sarr) {
				
					
					
                    $total_mk_totla = (float) ($total_mk_totla + $sarr['total']);
                    $due_date_mk = $sarr['due_date'];
                    $client_idmk = $sarr['client_id'];
                    $subject_idmk = $sarr['subject'];
                    $user_id_mk = $sarr['user_id'];

                    foreach ($sarr['line_items'] as $key => $line) {
                        array_push($line_items_mk, $line); # code...
                    }

                    foreach ($sarr['credit_items'] as $key2 => $line2) {
                        array_push($line_items_mk, $line2); # code...
                    }
                }

                $invoice_args2 = array(
                    'subject' => $subject_idmk,
                    'user_id' => $user_id_mk,
                    'client_id' => $client_idmk,
                    'status' => 'publish',
                    'total' => $total_mk_totla,
                    'currency' => '',
                    'may_be_apply' => "Not apply",
                    'issue_date' => time(),
                    'due_date' => $issue_date_mk,
                    'expiration_date' => 0,
                    'line_items' => $line_items_mk,
                    'fields' => array(),
                );

                if (count($line_items_mk) > 0) {
                	$is_empty = false;
                }
            } 
            else {
                $line_items = $mm[0]['line_items'];
                $credit_items = $mm[0]['credit_items'];
                $merge = array_merge($line_items, $credit_items);
					
                $invoice_args2 = array(
                    'subject' => $mm[0]['subject'],
                    'user_id' => $mm[0]['user_id'],
                    'may_be_apply' => "Not apply",
                    'client_id' => $mm[0]['client_id'],
                    'status' => 'publish',
                    'total' => $mm[0]['total'],
                    'currency' => '',
                    'issue_date' => time(),
                    'due_date' => $mm[0]['due_date'],
                    'expiration_date' => 0,
                    'line_items' => $merge,
                    'fields' => array(),
                );

                if (count($merge) > 0) {
                	$is_empty = false;
                }
            }

            if (is_array($lease_total_array[$key])) {
                $leaser_id = implode(",", $lease_total_array[$key]);
            } else {
                $leaser_id = $lease_total_array[$key];
            }

            if (!$is_empty) {
	            // Generate invoice
	            $invoice_id = SI_Invoice::create_invoice($invoice_args2);
	            update_post_meta($invoice_id, '_yl_client_id', $mm[0]['client_id']);
	            update_post_meta($invoice_id, '_yl_lease_user', $mm[0]['user_id']);
	            update_post_meta($invoice_id, '_yl_lease_id', $leaser_id);

	            // Let's log the new invoice id into the written off invoices and vice versa
	            update_post_meta($invoice_id, '_yl_generated_in_batch_of', date('Y_m_d', time()) );
	            update_post_meta($invoice_id, '_written_off_invoices', $written_off_total_array[$invoice_args2['client_id']]);

	            
	            if (count($written_off_total_array[$invoice_args2['client_id']]) > 0) {
	                foreach ((array)$written_off_total_array[$invoice_args2['client_id']] as $woi) {
	                    update_post_meta($woi, '_written_off_to', $invoice_id);
	                }
	            }
				
	            // Set client for this new invoice
	            $existing_invoice = SI_Invoice::get_instance($invoice_id);
	            $existing_invoice->set_client_id($client_id);

	            // For auto credit on invoice generation mk_auto_credit
	            auto_credit_mk($invoice_args2['client_id'], $invoice_id);
	            
	            echo '<tr><td>Invoice <a href="'.$blog_home_link.'post.php?post='.$invoice_id.'&action=edit">#'.$invoice_id.'</a> <strong>'.$company_title . ' Invoice for ' . $issue_date_mk.'</strong> generated...</tr></td>';
	        }
	        else {
	        	echo '<tr><td>No invoice generated for this client...</tr></td>';
	        }
        }
    }

    echo '</table><br>';

    wp_die();
}


add_action( 'wp_ajax_yl_generate_invoices_end_generation_batch', 'yl_generate_invoices_end_generation_batch_callback' );
function yl_generate_invoices_end_generation_batch_callback() {
	update_option( '_yl_generated_invoices_batch', date('Y_m_d', time()) );
    wp_die();
}