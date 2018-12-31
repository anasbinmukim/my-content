<?php
/*add_action( 'woocommerce_order_status_completed', 'woocommerce_order_status_completed_action' );
function woocommerce_order_status_completed_action( $order_id ) {
    $order = new WC_Order( $order_id );
    //$myuser_id = (int)$order->user_id;
    //$user_info = get_userdata($myuser_id);
    $items = $order->get_items();
    foreach ($items as $item) {
		$product_id = $item['product_id'];
    }

	$headers = array('Content-Type: text/html; charset=UTF-8');
	if( wp_mail( 'azad.rmweblab@gmail.com', 'Subscription Payment Notification', 'Dear BM, Subscription payment done for '.$product_id, $headers ) ) {
		update_post_meta($product_id, '_yl_available', 'No');
	}
}*/


add_action('woocommerce_payment_complete', 'send_notification_to_bm', 10, 1);
function send_notification_to_bm( $order_id ) {
    $order = new WC_Order( $order_id );
    $items = $order->get_items();
	$product_id = 0;
    foreach ($items as $item) {
		//$product_id = $item['product_id'];
		if( has_term( 'add-ons', 'product_cat', $item['product_id'] ) ) {
			continue;
		} else {
			$product_id = $item['product_id'];
		}
    }

	$args_post = array(
		'post_type' => 'lease',
		'posts_per_page' => 1,
		'orderby' => 'date',
		'order' => 'DESC',
		'post_status' => array( 'publish', 'draft' ),
		'meta_key'     => '_yl_product_id',
		'meta_value'   => $product_id,
		'meta_compare' => '='
	);
		
	$lease_post = new WP_Query( $args_post );
	if ( $lease_post->have_posts() ) {
		global $post;
		while ( $lease_post->have_posts() ) : $lease_post->the_post();
			//$author_id = get_the_author_meta( 'ID' );
			$author_id = $post->post_author;
			$lease_id = $post->ID;
		endwhile;
		wp_reset_postdata();
	}
	
	$user = get_user_by( 'id', $author_id );
	$user_email = $user->user_email;
	

 	$email_subject = get_option('bm_email_subject');
	$email_message = get_option('bm_email_message');			
	$search = array();
	$replace = array();	
	
	$search[] = '%%name%%';
	$replace[] = $user->first_name;
	
	$search[] = '%%lease-sign-url%%';
	$replace[] = '<a href="'.get_permalink(get_option('yl_bm_summary_sign_page')).'?lid='.$lease_id.'">'.get_the_title($lease_id).'</a>';
	
	$message = str_replace($search, $replace, $email_message);	
	$message = 	stripslashes($message);
	$message = nl2br($message);  
	
	$headers  = 'MIME-Version: 1.0' . "\r\n";
	$headers = array('Content-Type: text/html; charset=UTF-8');
	if( wp_mail( $user_email, $email_subject, $message, $headers ) ) {
		update_post_meta($product_id, '_yl_available', 'No');
	}
}

//add_action('subscriptions_created_for_order', 'set_trial_days_for_product', 10, 1);
function set_trial_days_for_product( $order ) {
    $items = $order->get_items();
    foreach ($items as $item) {
		$product_id = $item['product_id'];
    }
	update_post_meta($product_id, '_subscription_trial_length', 5);
}


//add_action('woocommerce_review_order_before_order_total', 'yl_review_product_order_in_checkout', 10, 0);
function yl_review_product_order_in_checkout(){
	
	$security_deposite =  100;
	
	?>
		<tr class="order-description">
			<th><?php _e( 'Security Deposit', 'woocommerce' ); ?></th>
			<td><?php echo '$'. $security_deposite; ?></td>
		</tr>	
	<?php
}


function yl_process_checkout_page($lease_id){
		global $woocommerce;
		$product_id = get_post_meta($lease_id, '_yl_product_id', true);
		$checkout_url = $woocommerce->cart->get_checkout_url();
		
		$lease_start_date = get_post_meta($lease_id, '_yl_lease_start_date', true);
		$move_in_date_arr = explode("-", $lease_start_date);
		$month = $move_in_date_arr[1];
		/*if( substr($month, 0, 1) == 0 ) {
			$month = substr($month, 1, 1);
		}*/
		$day = $move_in_date_arr[2];
		$year = $move_in_date_arr[0];
		
		if($day == "01" || $day == "1") {
			$moveNext = 0;
			$cur_month = $month;
		} else {
			$moveNext = 1;
			$cur_month = 01;
		}
		
		//$curMonth = date('n');
		//$curYear  = date('Y');
		if ($month == 12) {
			//$firstDayNextMonth = mktime(0, 0, 0, 0, 0, $year+$moveNext);
			$year = ($year+$moveNext);
			$timestamp = date("$year-$cur_month-01");
		} else {
			$month = ($month+$moveNext);
			$timestamp = date("$year-$month-01");
			//$firstDayNextMonth = mktime(0, 0, 0, $month+$moveNext, 1);
		}
		
		$date1 = date_create(date("Y-m-d"));
		$date2 = date_create($timestamp);
		$diff = date_diff($date1, $date2);
		$daysRemaining = $diff->format("%a");
	
		//$timestamp = strtotime("$year-$month-$day");
		/*$daysRemaining = (int)date('t', $timestamp) - (int)date('j', $timestamp);
		$daysRemaining = (int)date('t', $timestamp) - (int)date('j', $timestamp);
		$daysRemaining = ($daysRemaining + 1);*/
		//$daysDifference = ($firstDayNextMonth - mktime()) / (24 * 3600);
		
		$service_fees_product = get_option('yl_service_fees');

		if($daysRemaining > 0) {
			update_post_meta($product_id, '_subscription_trial_length', $daysRemaining);
			if(get_post_meta($lease_id, '_yl_service_fees', true)) {
				update_post_meta($service_fees_product, '_subscription_trial_length', $daysRemaining);
			}
		} else {
			// if there are no trial days, means this is the first day of the month and also the subscription & move-in date
			update_post_meta($product_id, '_subscription_trial_length', 0);
			if(get_post_meta($lease_id, '_yl_service_fees', true)) {
				update_post_meta($service_fees_product, '_subscription_trial_length', 0);
			}
		}
		//$first_month_rent_rate = get_post_meta($lease_id, '_yl_first_month_rent_rate', true);
		//$monthly_rent = get_post_meta($lease_id, '_yl_monthly_rent', true);
		$security_deposit = get_post_meta($lease_id, '_yl_security_deposit', true);
		/*$proratedRent = 0;
		if($first_month_rent_rate > $monthly_rent) {
			$proratedRent = ($first_month_rent_rate - $monthly_rent);
		}*/
		
		// check if it is the 1st day of the month
		if( ($lease_start_date == date("Y-m-d")) && (date("j") == 1) ) {
			//$sign_up_fee = get_post_meta($lease_id, '_yl_monthly_rent', true);
		} else {
			//$sign_up_fee = $security_deposit;
			update_post_meta($product_id, '_subscription_sign_up_fee', $security_deposit);
			if(get_post_meta($lease_id, '_yl_service_fees', true)) {
				update_post_meta($service_fees_product, '_subscription_sign_up_fee', 0);
			}
		}
		//$sign_up_fee = ($security_deposit + $proratedRent);
		
		$woocommerce->cart->add_to_cart($product_id);
		if(get_post_meta($lease_id, '_yl_service_fees', true)) {
			$woocommerce->cart->add_to_cart(get_option('yl_service_fees'));
		}

		$coupon_code = get_post_meta($lease_id, '_yl_promotional_code', true);
		if($coupon_code) {
			if (!$woocommerce->cart->add_discount( sanitize_text_field( $coupon_code ))) {
				//$woocommerce->show_messages();
			} else {
				//$woocommerce->clear_messages();
				//$woocommerce->add_message('Coupon Applied');
				//$woocommerce->show_messages();
			}
		}
		
		//Apply if have Multisite discount coupon.
		$multisite_coupon = get_post_meta($lease_id, '_yl_multisite_coupon', true);
		if($multisite_coupon) {
			if (!$woocommerce->cart->add_discount( sanitize_text_field( get_option('yl_multisite_coupon') ))) {
				//$woocommerce->show_messages();
			} else {
			}
		}			

		// Manually recalculate totals.  If you do not do this, a refresh is required before user will see updated totals when discount is removed.
		$woocommerce->cart->calculate_totals();
		
		wp_redirect( $checkout_url );
		exit;		
	
}


function yl_coupon_discount( $price, $type, $amount ){
    switch( $type ){
	    case 'percent_product':
		    $newprice = $price * ( 1 - $amount/100 );
		    break;
	    case 'fixed_product':
		    $newprice = $price - $amount;
		    break;
	    case 'percent_cart':
		    $newprice = $price * ( 1 - $amount/100 );
		    break;
	    case 'fixed_cart':
		    $newprice = $price - $amount;
		    break;
	    default:
		    $newprice = $price;
	}

	return $newprice;
}