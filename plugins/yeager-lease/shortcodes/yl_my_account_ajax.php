<?php
function my_account_products_remove_callback() {
    global $wpdb;

    include_once(CW_PRODUCTS_ROOT . 'controllers/products_controller.php');
    include_once(CW_PRODUCTS_ROOT . 'helpers/products_helper.php');

    if (isset($_POST['remove'])) {
        $to_return = array();

        $product_id = $_POST['product_id'];
        $lease_id = $_POST['lease_id'];
        $upgrade_meta = get_post_meta($lease_id, 'yl_lease_upgrade_details_' . $product_id, true);
        $upgrade_meta['is_active'] = FALSE;
        update_post_meta($lease_id, 'yl_lease_upgrade_details_' . $product_id, $upgrade_meta);

        $products = get_products('suite');
        $lease = get_posts($lease_id);
        $suite_id = get_post_meta($lease->ID, '_yl_product_id', true);

        foreach ($products as $p) {

            if ($p->ID == $product_id) {
                $status = FALSE;
                $upgrade = array();
                $upgrade = get_post_meta($lease_id, 'yl_lease_upgrade_products', true);
                $products_ids = explode(',', $upgrade);

                if (in_array($p->ID, $products_ids)) {
                    $upgrade_meta = get_post_meta($lease_id, 'yl_lease_upgrade_details_' . $p->ID, true);
                    $status = $upgrade_meta['is_active'];
                }
                $variations = get_post_meta($p->ID, CMB2_PREFIX . 'variation', true);


                $to_return['uid'] = $product_id.'_'.$lease_id;
                $to_return['product_id'] = $product_id;
                $to_return['lease_id'] = $lease_id;
                $to_return['cost'] = '$<span id="monthly_cost_'.$p->ID.'">'.$variations[$upgrade_meta['variation']]['cost'].' '.(($variations[$upgrade_meta['variation']] == 'monthly') ? "per month" : "each").'</span>';

                if ($variations[$upgrade_meta['variation']]['setup_fee']) {
                    $to_return['setup_fee'] = '$<span id="onetime_cost_'.$p->ID.'">'.$variations[$upgrade_meta['variation']]['setup_fee'].'</span>';
                }
                else {
                    $to_return['setup_fee'] = __('no setup fee', 'yl');
                }

                ob_start();
                get_cw_product_variation($p->ID, $lease_id, $status);
                $to_return['select_html'] = ob_get_clean();
            }
        }
        /*
        ?>
        <div class="modal fade" id="success_model" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <input  type="hidden" name="model_post_id" value="" id="model_post_id">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <h4 class="modal-title" id="myModalLabel">Upgrade</h4>
                    </div>
                    <div class="modal-body">
                        <!-- Your request to remove  <?= $upgrade_meta['variation_title'] ?> for <?= get_the_title($lease_id) ?> has been successfully completed.   -->
                        Your request to remove  <?= str_replace("Bandwidth Upgrade to", "", $upgrade_meta['variation_title']) ?> for <?= get_the_title($lease_id) ?> has been successfully completed.
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Ok</button>

                    </div>
                </div>
            </div>
        </div>
        <script>
            jQuery('#success_model').modal('show');
        </script>
        <?php
        */
        echo json_encode($to_return);
    }
    echo false;

    wp_die();
}
add_action( 'wp_ajax_check_user_allready_exist', 'check_user_allready_exist_callback' );
add_action( 'wp_ajax_check_update_user_already_exist', 'check_update_user_already_exist_callback' );
add_action( 'wp_ajax_my_account_products_remove', 'my_account_products_remove_callback' );
add_action( 'wp_ajax_nopriv_my_account_products_remove', 'my_account_products_remove_callback' );
function check_user_allready_exist_callback()
{
    $email = $_REQUEST['email'];
    
    $user = get_user_by_email($email);
   // echo $user->data->ID;
    if (!empty($user->data->ID) ) {
            echo $user->data->ID ;exit;
        }
        else{
            echo 0;exit;
        }
    


}
function check_update_user_already_exist_callback()
{
    $email = $_REQUEST['email'];
    $userid = $_REQUEST['userid'];
    
    $user = get_user_by_email($email);
    // echo "<pre>";print_r($user);exit;
    if ($user->data->ID === $userid ) {
            echo 1 ;exit;
        }
    elseif(empty($user)){
           echo 2;exit;
     }
    else{
            echo 0;exit;
    }
        


}

function my_account_products_purchase_callback() {
    include_once(CW_PRODUCTS_ROOT . 'controllers/products_controller.php');
    include_once(CW_PRODUCTS_ROOT . 'helpers/products_helper.php');

    global $wpdb;

    if (isset($_POST['create'])) {

        $product_meta = get_post_meta($_POST['product_id']);

        $up_ar = array();
        $fields_listing = array();
        $c_upgrade = array();

        $total_cost = 0;
        $set_up = 0;
        $v_cost = 0;
        $cost = 0;

        $product = $_POST['product'];
        $product_id = $_POST['product_id'];

        if ($_POST['qty']) {
            $qty = $_POST['qty'];
        }
        else {
            $qty = 1;
        }
        $user_id = get_current_user_id();
        //$client_id = yl_get_client_id_by_user_id($user_id);

        $client_id = $_POST['lease_id'];
        $accounting_cat = get_post_meta($product_id, '_cw_accounting_cat', true);
		
		$email_product_id = '';

        foreach ($product as $key => $val) {
            $ur = array();
			$email_ur = array();
			$email_product_id = '';
            $variation_cost = 0;

            $c_upgrade[] = $key;
            $variations = get_post_meta($key, CMB2_PREFIX . 'variation', true);

            $v_cost = $variations[$val['variation_type']]['cost'];
            $v_setup = $variations[$val['variation_type']]['setup_fee'];

            $cost = (float) $v_setup;
            //$qty = $val['quantity'] ? $val['quantity'] : 1;
            $bill = $variations[$val['variation_type']]['billing_frequency'];
            $variation_cost = $cost * $qty;
			
			$accounting_cat =  $variations[$val['variation_type']]['accounting_cat'];

            $setup = array(
                "desc" => $variations[$val['variation_type']]['invoice_description'].' (setup)',
                "qty" => $qty,
                "rate" => round($cost, 2),
                "total" => round($variation_cost, 2),
                "type" => "service",
                "accounting_cat" => $accounting_cat
            );

            $rad = rand(1000, 9999);

            update_post_meta($client_id, 'yl_lease_upgrade_' . $key, TRUE);
            update_post_meta($client_id, 'yl_lease_upgrade_variation_' . $key, $val['variation_type']);
            update_post_meta($client_id, 'yl_lease_upgrade_qty_' . $key, $qty);
            update_post_meta($client_id, 'yl_lease_upgrade_cost_' . $key, $variation_cost);
            update_post_meta($client_id, 'yl_lease_upgrade_key_' . $key, $rad);
            update_post_meta($client_id, 'yl_lease_upgrade_billing_type_' . $key, $bill);
            update_post_meta($client_id, 'yl_lease_upgrade_client_id_' . $key, $client_id);

            $ur['is_active'] = TRUE;
            $ur['variation'] = $val['variation_type'];
            $ur['variation_title'] = $variations[$val['variation_type']]['variation_title'];
            $ur['quantity'] = $qty;
            $ur['cost'] = $v_cost;
            $ur['setup_cost'] = $v_setup;
            $ur['billing_type'] = $bill;
            $ur['activedate'] = date("Y-m-d H:i:s");
            $ur['key'] = $rad;
            $ur['total_cost'] = $variation_cost;

            array_push($fields_listing, $setup);
			$email_product_id = $key;
			$email_ur = $ur;
			//send_notification_email($client_id, $key, $ur);
            $total_cost = $total_cost + $variation_cost;
            // uncomment
            update_post_meta($client_id, 'yl_lease_upgrade_details_' . $key, $ur);
        }

        // Generate the invoice
        $invoice_args_subject = __( 'Invoice for upgrade setup', 'yl' );
        if ($_POST['qty']) {
            $invoice_args_subject = __( 'Invoice for purchase', 'yl' );
        }
		
	 	if(get_post_meta($product_id, '_cw_function',true) == 1 && (int)$qty > 0){
			yeager_add_calendar_credits($user_id, (int)$qty);
		}
		
        $invoice_args = array(
            'subject' => $invoice_args_subject,
            'user_id' => $user_id,
            'client_id' => $client_id,
            'status' => 'publish',
            'total' => (float) ($total_cost),
            'currency' => '',
            'issue_date' => time(),
            //'due_date' => date('Y-m-d', strtotime("+30 days")),
			'due_date' => date('Y-m-d'),
            'expiration_date' => 0,
            'line_items' => $fields_listing, //array
            'fields' => array(),
        );

        $invoice_id = SI_Invoice::create_invoice($invoice_args);
		
		$email_ur['invoice_id'] = $invoice_id;
		send_notification_email($client_id, $email_product_id, $email_ur);

        update_post_meta($invoice_id, '_yl_client_id', $client_id);
        update_post_meta($invoice_id, '_yl_lease_user', $user_id);
        update_post_meta($invoice_id, '_yl_lease_id', $client_id);
        update_user_meta($user_id, '_yl_lease_upgrade_' . $client_id, $invoice_id);
        update_user_meta($user_id, '_yl_lease_upgrade_' . $client_id, $invoice_id);

        $invoice_permalink = get_post_permalink($invoice_id);

        $upgrade = get_post_meta($client_id, 'yl_lease_upgrade_products', true);
        $up_val = array();
        if ($upgrade) {
            $up_val = explode(',', $upgrade);
            $c_upgrade = array_merge($up_val, $c_upgrade);
            $c_upgrade = array_unique($c_upgrade);
        }
        $up_val = implode(',', $c_upgrade);

        update_post_meta($client_id, 'yl_lease_upgrade_products', $up_val);
        update_post_meta($client_id, 'yl_lease_upgrade', TRUE);

        $to_return = array(
            'uid' => $product_id.'_'.$client_id,
            'product_id' => $product_id,
            'lease_id' => $client_id,
            'invoice_link' => $invoice_permalink,
            'message' =>    'Your purchase of <strong>'.$variations[$val['variation_type']]['variation_title'].'</strong> was successfully completed.<br><br>An invoice was sent to you to be paid and you will see this new charge reflected on your monthly invoice if applicable.
            You can cancel monthly upgrades at any time by logging into your account and going to your y-store.',
        );

        if (!$_POST['qty']) {
            $to_return['variation'] = $variations[$val['variation_type']]['variation_title'];
            $to_return['cost'] = '$'.$variations[$val['variation_type']]['cost'];
            $to_return['setup_fee'] = (($variations[$val['variation_type']]['setup_fee']) ? '$'.$variations[$val['variation_type']]['setup_fee'] : 'no setup feee' );
            $to_return['frequency'] = (($variations[$val['variation_type']]['billing_frequency'] == 'monthly') ? 'per month' : 'each');
        }

        echo json_encode($to_return);
    }

    wp_die();
}
add_action( 'wp_ajax_my_account_products_purchase', 'my_account_products_purchase_callback' );
add_action( 'wp_ajax_nopriv_my_account_products_purchase', 'my_account_products_purchase_callback' );

function yeager_add_calendar_credits($user_id, $qty){
	$args = array(
		'post_type' => 'lease',
		'post_status' => 'all',
		'numberposts' => -1,
		'posts_per_page' => -1,
		'meta_query' => array(
			array(
				'key' => '_yl_lease_user',
				'value' => $user_id,
				'compare' => '=',
			),
		),
	);
	$leases = get_posts($args);
	$company_id = get_post_meta($leases[0]->ID, '_yl_company_name', true);
	$calendar_credits = (int)get_post_meta($company_id, '_yl_pc_credits', true);
	update_post_meta((int)$company_id, '_yl_pc_credits', $calendar_credits+$qty);
	//echo $company_id.'rrr'.($calendar_credits+$qty);
}
