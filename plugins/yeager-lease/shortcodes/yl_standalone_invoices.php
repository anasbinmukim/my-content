<?php
/**
 *
 * Stand Alone Invoicing Form
 *
 * Displays form for Building Manager to create invoices for clients
 * for Work Orders or Key Replacements.
 *
 */

add_shortcode('bm-standalone-invoices', 'yl_bm_standalone_invoices_sc');
function yl_bm_standalone_invoices_sc($content = null) {

    ob_start();

    /**
     * User has to be logged in
     */
    if ( is_user_logged_in() ) {

        /**
         * Only Building Managers have access to this form
         */
        if(!current_user_can( 'building_manager' )){
            echo "<p>Only Building Managers are allowed to access this page.</p>";
            return;
        }
        ?>
        <div class="yl-standalone-invoices">

            <?php
            /**
             * STEP 1 : Select the client
             */
            if (!$_POST) {
                ?>
                <form action="" method="post">
                    <div class="row">
                        <div class="col-md-12">

                            <div class="form-group">
                                <label>Client</label>
                                <?php
								$client_ids = array();
								
                                // Get all active leases and gather their users
                                $leases_args = array(
                                    'post_type' => 'lease',
                                    'status' => 'publish',
                                    'numberposts' => -1,
                                );
                                $leases = get_posts($leases_args);

                                
                                foreach ($leases as $lease) {
									if(get_post_meta($lease->ID, '_yl_lease_user', true)){
                                    	$client_id = get_post_meta($lease->ID, '_yl_lease_user', true);
										if(get_user_by('id', $client_id))
											$client_ids[$client_id] = $client_id;
									}	
                                }
								//print_r($client_ids);
								$select_client_arr = array();
								foreach ($client_ids as $cli) {
									$user_obj = get_user_by('id', $cli);
									$user_meta = get_user_meta($cli);
									$client_obj_id = SI_Client::get_clients_by_user($cli);
									$client_obj = get_post($client_obj_id[0]);
									$select_client_arr[$cli] = esc_html($client_obj->post_title);
								}								
								asort($select_client_arr);							
                                ?>
                                
                                <select class="form-control" name="client_id">
									<option value="">Select Client</option>
                                    <?php
                                    foreach ($select_client_arr as $client_id => $client_name) {
                                        ?>
                                        <option value="<?php echo $client_id; ?>"><?php echo $client_name; ?></option>
                                        <?php
                                    }
                                    ?>
                                </select>
                            </div>			
							
							<div class="form-group">
                                <label>Company</label>
                                <?php
                                // Get all active companies
                                $company_args = array(
                                    'post_type' => 'company',
                                    'status' => 'publish',
									'orderby' => 'title',
									'order' => 'ASC',
                                    'numberposts' => -1,
                                );
                                $companies = get_posts($company_args);
                                ?>                                
                                <select class="form-control" name="company_id">
									<option value="">Select Company</option>
                                    <?php
									foreach ($companies as $company) {
										$company_id = $company->ID;
										$company_name = esc_html(get_the_title($company->ID));
										if($company_name != ''){
                                        ?>
                                        <option value="<?php echo $company_id; ?>"><?php echo $company_name; ?></option>
                                        <?php					
										}					
									}
                                    ?>
                                </select>
                            </div>							

                        </div>
                        <div class="col-md-12">

                            <div class="form-group text-right">
                                <input type="submit" name="step_2" value="Continue" class="btn btn-primary">
                            </div>

                        </div>
                    </div>
                </form>
                <?php     
            }
            /**
             * STEP 2 : Fill work order or key replacement information
             */
            if (isset($_POST['step_2'])) {

                if ($_POST['action'] == 'key_r') {
                    $action = 'Key replacement';
                }elseif ($_POST['action'] == 'building_m') {
                    $action = 'Work Order';
                }

                
				if(!isset($_POST['client_id']) || $_POST['client_id'] == ''){
					$company_id = $_POST['company_id'];
					$client_id = get_standalone_yl_client_id_by_company_id($company_id);
				}else{
					$client_id = $_POST['client_id'];
				}				
				$user_obj = get_user_by('id', $client_id);
				$user_meta = get_user_meta($client_id);		
				
				
				
				if(!isset($_POST['company_id']) || $_POST['company_id'] == ''){
					$client_id = $_POST['client_id'];
					$company_id = get_standalone_yl_company_id_by_client_id($client_id);				
				}else{
					$company_id = $_POST['company_id'];
				}		
				
                ?>

                <?php
                $suite_list_select .= '<select class="form-control" name="suite">';
                    
                $leases_args = array(
                    'post_type' => 'lease',
                    'status' => 'publish',
                    'numberposts' => -1,
                    'meta_query' => array(
						'relation' => 'OR',
                        array(
                            'key'     => '_yl_lease_user',
                            'value'   => $client_id,
                            'compare' => '='
                        ),
						array(
                            'key'     => '_yl_company_name',
                            'value'   => $company_id,
                            'compare' => '='
                        ),
                    )
                );
                $leases = get_posts($leases_args);

                $has_ym = false;
                $has_suites = false;
                foreach ($leases as $lease) {
                    $product_id = get_post_meta($lease->ID, '_yl_product_id', true);

                    if ($product_id > 0) {
                        $suite = get_post($product_id);
                        $suite_list_select .= '<option value="'.$suite->ID.'">'.esc_html($suite->post_title).'</option>';
                        $has_suites = true;
                    }
                    else {
                        $has_ym = true;
                    }
                }

                if (($has_ym) && ($_POST['action'] = 'key_r')) {
                    $suite_list_select .= '<option value="-1">Y-Membership</option>';
                }
                
                $suite_list_select .= '</select>';
                ?>

                <form action="" method="post">
                    <input type="hidden" name="client_id" value="<?php echo $client_id; ?>">
					<?php
						$company_name = esc_html(get_the_title($company_id));
					?>
					<input type="hidden" name="company_name" value="<?php echo esc_attr($company_name); ?>">

                    <div class="row">
                        <div class="col-md-12">
                            <div>
                                <label>Invoice for:</label>
                                <div class="form-group">
                                    <select class="form-control standalone-type-select" name="action">
                                        <option value="key_r">Key Replacement</option>
										<option value="nsf_fees">NSF Fee</option>										
                                        <?php if ($has_suites) { ?>
                                        <option value="building_m">Work Order</option>
                                        <?php } ?>
										<option value="fees_fines">Fine</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label>Client:</label>
                                <h4><strong><?php echo $user_meta['first_name'][0].' '.$user_meta['last_name'][0]; ?></strong></h4>
							</div>
							<div class="col-md-6">	
                                <label>Company:</label>
                                <h4><strong><?php echo $company_name; ?></strong></h4>								
                            </div>
                        </div>

                        <div class="col-md-12">
                            <hr>
                        </div>

                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Date</label>
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                    <input class="text-center" type="text" name="date" value="<?php echo date( 'm/d/Y' , time() ); ?>">
                                </div>
                            </div>

                            <div class="form-group">
                                <label>Building</label>
                                <input class="form-control" type="text" name="building" value="<?php echo get_bloginfo( 'name' ); ?>">
                            </div>

                            <div class="form-group">
                                <label>Suite</label>
                                <?php echo $suite_list_select; ?>
                            </div>
                        </div>

                        <div class="key-r-block">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Fixed Price</label>
                                    <div class="input-group">
                                        <span class="input-group-addon">$</span>
                                        <input class="form-control text-right" readonly="readonly"  name="price" value="20">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-9"></div>
                        </div>
						
						<div class="fines-block">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Fine Fee</label>
                                    <div class="input-group">
                                        <span class="input-group-addon">$</span>
                                        <input class="form-control text-right"  name="fine_price" value="">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-9"></div>
							<div class="col-md-9">
								<div class="form-group">
									<label>Fine For:</label>
									<input class="form-control" type="text" name="fine_for" value="">
								</div>
							</div>
                        </div>
						
						<div class="nsf-block">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Fixed Price</label>
                                    <div class="input-group">
                                        <span class="input-group-addon">$</span>
                                        <input class="form-control text-right" readonly="readonly"  name="nsf_price" value="40">
                                    </div>
                                </div>
                            </div>
							
							<div class="col-md-3">
								<div class="form-group">
									<label>Invoice Rebill</label>
									<div class="input-group">
										<span class="input-group-addon">$</span>
										<input class="form-control text-right" type="text" name="invoice_rebill" id="invoice_rebill" value="">
									</div>
								</div>
							</div>
							
							<div class="col-md-6">
								<div class="form-group">
									<label>Invoice Note</label>
									<input class="form-control" type="text" name="nsf_note" value="">
								</div>
							</div>													
                        </div>										

                        <div class="building-m-block">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>E-mail Notifications</label>
                                    <div class="input-group">
                                        <span class="input-group-addon">@</span>
                                        <input class="form-control" name="email-notif-1" value="">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="input-group">
                                        <span class="input-group-addon">@</span>
                                        <input class="form-control" name="email-notif-2" value="">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="input-group">
                                        <span class="input-group-addon">@</span>
                                        <input class="form-control" name="email-notif-3" value="">
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Work order Number</label>
                                            <div class="input-group">
                                                <span class="input-group-addon"><i class="fa fa-paperclick"></i></span>
                                                <input class="form-control" name="work-order" value="">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-9"></div>
                                </div>
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Price</label>
                                            <div class="input-group">
                                                <span class="input-group-addon">$</span>
                                                <input class="form-control text-right" name="price2" value="">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-9"></div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <label>Work description</label>
                                        <textarea name="memo" class="form-control"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>						

                        <div class="col-md-12">
                            <div class="form-group text-right">
                                <br>
                                <input type="submit" name="step_3" value="Continue" class="btn btn-primary">
                            </div>
                        </div>

                    </div>
                </form>

                <?php
            }  
            /**
             * STEP 3 : Generate invoice
             */
            if (isset($_POST['step_3'])) {
				$company_name = '';
                $user_id = $_POST['client_id'];
				$company_name = $_POST['company_name'];				
                $client_id = SI_Client::get_clients_by_user($user_id)[0];
                $product_id = $_POST['suite'];
                $action = $_POST['action'];
                $date = $_POST['date'];
                
                $notes = (($_POST['memo']) ? $_POST['memo'] : '');
                $work_order = $_POST['work-order'];
                $building = $_POST['building'];
                $emails_to_notify = array(
                    $_POST['email-notif-1'],
                    $_POST['email-notif-2'],
                    $_POST['email-notif-3']
                );

                if ($product_id == -1) {
                    $product_name = 'Y-Membership';
                }
                else {
                    $suite_obj =  get_post($product_id);
                    $product_name = $suite_obj->post_title;
                }

                if ($action == 'key_r') {
                    $price = $_POST['price'];
                    $service_type = 'Key Replacement';
                    $accounting_cat = yl_account_category_id_by_wordmatch('Tenant');
                }elseif ($action == 'nsf_fees') {
                    $price = $_POST['nsf_price'];
                    $service_type = 'NSF Fee for '.$company_name;
                    $accounting_cat = yl_account_category_id_by_wordmatch('Fees');
                }elseif ($action == 'fees_fines') {
                    $price = $_POST['fine_price'];
                    $service_type = 'Fine for '.$company_name;
                    $accounting_cat = yl_account_category_id_by_wordmatch('Fees');
                }elseif ($action == 'building_m') {
                    $price = $_POST['price2'];
                    $service_type = 'Work Order';
                    $accounting_cat = yl_account_category_id_by_wordmatch('Tenant');
                }
				
				$accounting_cat_rebill = yl_account_category_id_by_wordmatch('Rebill');
				
				
				$invoice_subject_line = 'Invoice for '.$product_name.': '.$service_type.' '.$work_order;
				
				$total_invoice_amount = $price;
				
				$invoice_note = (($action == 'key_r') ? $notes : $notes.' - Work Order #: '.$work_order).' - Building: '.$building;
				
				if ($action == 'nsf_fees'){
					$invoice_subject_line = $service_type;
					
					$price_invoice_rebill = esc_html($_POST['invoice_rebill']);
					
					$invoice_note = esc_html($_POST['nsf_note']);
					
					$total_invoice_amount += $price_invoice_rebill;
				}
				
				if ($action == 'fees_fines'){
					$invoice_note = 'Fine For: '. esc_html($_POST['fine_for']);
				}
				

                // Let's create an invoice now
                $invoice_args = array(
                    'subject' => $invoice_subject_line,
                    'client_id' => $client_id,
                    'status' => 'publish',
                    'currency' => '',
                    'deposit' => (float) 0,
                    'total' => (float) $total_invoice_amount,
                    'issue_date' => $date,
                    'due_date' => time(),
                    'expiration_date' => 0,
                    'notes' => $invoice_note,
                    'fields' => array()
                );

                $invoice_args['line_items'][] = array(
                    "desc" => $service_type,
                    "qty" => 1,
                    "rate" => (float) $price,
                    "total" => (float) $price,
                    "type" => "service",
                    "accounting_cat" => $accounting_cat
                );
				
				if ($action == 'nsf_fees'){
					$price_invoice_rebill = $_POST['invoice_rebill'];				
					$invoice_args['line_items'][] = array(
						"desc" => 'NSF Invoice Rebill Amount',
						"qty" => 1,
						"rate" => (float) $price_invoice_rebill,
						"total" => (float) $price_invoice_rebill,
						"type" => "service",
                        "accounting_cat" => $accounting_cat_rebill
					);				
				}
				
/*				if ($action == 'fees_fines'){
					$price_invoice_rebill = $_POST['invoice_rebill'];				
					$invoice_args['line_items'][] = array(
						"desc" => 'Fine For Amount',
						"qty" => 1,
						"rate" => (float) $price_invoice_rebill,
						"total" => (float) $price_invoice_rebill,
						"type" => "service"
					);				
				}*/				

                $invoice_id = SI_Invoice::create_invoice( $invoice_args );
                update_post_meta($invoice_id, '_yl_suite_id', $product_id);
                update_post_meta($invoice_id, '_yl_standalone_invoice', true);
                update_post_meta($invoice_id, '_yl_work_order_number', $work_order);
                update_post_meta($invoice_id, '_yl_notify_emails', $emails_to_notify);

                // Send email to client
                send_standalone_invoice_email_to_client($user_id, $client_id, $invoice_id, $service_type, $price);
                ?>

                <p>
                    Invoice <strong>#<?php echo $invoice_id; ?></strong> has been e-mailed to the client.
                </p>

                <?php
            }
            ?>

        </div>

        <?php
    }
    else {
    	echo "User Not Logged In.";
    }

    $content = ob_get_clean();
    return $content;
}

/**
 * Send an Email to client telling him about the newly
 * generated invoice for a work order or key replacement
 */
function send_standalone_invoice_email_to_client($user_id, $client_id, $invoice_id, $reason, $cost) {
    $user = get_user_by('id', $user_id);
    $user_email = $user->user_email;
    $suite = get_post(get_post_meta($invoice_id, '_yl_suite_id', true));

    $email_subject = get_option('clients_standalone_invoice_email_subject');
    $email_message = get_option('clients_standalone_invoice_email_message');
    $search = array();
    $replace = array(); 

    $search[] = '%%client-name%%';
    $replace[] = $user->first_name;

    if (get_post_meta($invoice_id, '_yl_suite_id', true)) {
        $search[] = '%%suite-name%%';
        $replace[] = $suite->post_title;
    }   
    else {
        $search[] = '%%suite-name%%';
        $replace[] = 'one of your rentals';
    }

    $search[] = '%%invoice-description%%';
    $replace[] = $reason;

    $search[] = '%%cost%%';
    $replace[] = $cost;

    $search[] = '%%invoice-url%%';
    $replace[] = '<a href="'.get_permalink(get_option('yl_lease_checkout_page')).'?iid='.$invoice_id.'">Go to checkout page</a>';

    $message = str_replace($search, $replace, $email_message);  
    $message = stripslashes($message);
    $message = nl2br($message);  
    
    $headers  = 'MIME-Version: 1.0' . "\r\n";
    $headers = array('Content-Type: text/html; charset=UTF-8');
    wp_mail( $user_email, $email_subject, $message, $headers );
}


function get_standalone_yl_company_id_by_client_id($client_id) {
	$args_post = array(
		'post_type'  => 'lease',
		'posts_per_page' => 1,
		'meta_query' => array(
			array(
				'key'     => '_yl_lease_user',
				'value'   => $client_id,
				'compare' => '=',
			),
		),
	);	
	
	$lease_id = 0;
	wp_reset_query();
		
	$lease_post = new WP_Query( $args_post );
	if ( $lease_post->have_posts() ) {
		global $post;
		while ( $lease_post->have_posts() ) : $lease_post->the_post();
			$lease_id = $post->ID;
		endwhile;
		wp_reset_query();
	}
	
	$company_id = get_post_meta($lease_id, '_yl_company_name', true);
	
	return $company_id;
}


function get_standalone_yl_client_id_by_company_id($company_id) {
	$args_post = array(
		'post_type'  => 'lease',
		'posts_per_page' => 1,
		'meta_query' => array(
			array(
				'key'     => '_yl_company_name',
				'value'   => $company_id,
				'compare' => '=',
			),
		),
	);	
	
	$lease_id = 0;
	wp_reset_query();
		
	$lease_post = new WP_Query( $args_post );
	if ( $lease_post->have_posts() ) {
		global $post;
		while ( $lease_post->have_posts() ) : $lease_post->the_post();
			$lease_id = $post->ID;
		endwhile;
		wp_reset_query();
	}
	
	$lease_user_id = get_post_meta($lease_id, '_yl_lease_user', true);
	
	return $lease_user_id;
}
