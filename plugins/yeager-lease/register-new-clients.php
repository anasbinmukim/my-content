<?php
function yl_register_client_user($client_arg, $notification = false) {
	// Gather all the user information form the arguments array
	$user_name = 	$client_arg['user_login'];
	$user_email = 	$client_arg['user_email'];
	$lease_id = 	$client_arg['lease_id'];
	$first_name = 	$client_arg['first_name'];
	$middle_name = 	$client_arg['middle_name'];
	$last_name = 	$client_arg['last_name'];
	$phone = 		$client_arg['phone'];
	$address_1 = 	$client_arg['address_1'];
	$address_2 = 	$client_arg['address_2'];
	$city = 		$client_arg['city'];
	$zip = 			$client_arg['zip'];
	$state = 		$client_arg['state'];
	$company = 		$client_arg['company'];
	$company_obj =  get_post($company);
	
	// Check if the user exists
	$user_id = email_exists( $user_name );
	
	if ( !$user_id and email_exists($user_email) == false ) {
		$random_password = wp_generate_password( $length=12, $include_standard_special_chars=false );
		$user_id = wp_create_user( $user_name, $random_password, $user_email );
		$wp_user_object = new WP_User($user_id);
		$wp_user_object->set_role('lease_client');

		update_user_meta($user_id, '_yl_l_first_name', $first_name);
		update_user_meta($user_id, 'first_name', $first_name);
		update_user_meta($user_id, '_yl_l_middle_name', $middle_name);
		update_user_meta($user_id, '_yl_l_last_name', $last_name);
		update_user_meta($user_id, 'last_name', $last_name);

		update_user_meta($user_id, '_yl_l_phone', $phone);
		update_user_meta($user_id, '_yl_l_street_address', $address_1);
		update_user_meta($user_id, '_yl_l_address_line_2', $address_2);
		update_user_meta($user_id, '_yl_l_city', $city);
		update_user_meta($user_id, '_yl_l_zip_code', $zip);
		update_user_meta($user_id, '_yl_l_state', $state);

		//send mail
		if ($notification == true) {
			wp_new_user_notification($user_id, $random_password, $user_name, $first_name, $last_name, $user_email, $lease_id);
		}
		if ($lease_id) {
			update_post_meta($lease_id, '_yl_lease_user', $user_id );
		}
	}
	else{
		if ($notification == true) {
	    	sent_info_to_mail($first_name, $last_name, $user_email, $lease_id);
	    }
	    if ($lease_id) {
			update_post_meta($lease_id, '_yl_lease_user', $user_id );		
		}
	}

	// Create (if needed) a client element and associate it with a user
	$client_id = yl_create_user_associated_client($user_id, $first_name, $last_name, $phone, $company_obj);

	return $user_id;
}


function yl_create_user_associated_client($user_id, $first_name, $last_name, $phone, $company_obj) {
	global $us_states_codes_inverse;

	// Let's check if there is a client associated with this user. If it doesn't, lets create it
	// Sprout Invoices plugin is required
	$assoc_clients = SI_Client::get_clients_by_user($user_id);

	if (count($assoc_clients) == 0) {
		// There are no associated clients, let's create a new one
		$new_client_args = array(
			'company_name' => $first_name.' '.$last_name,
			'phone' => $phone,
			'user_id' => $user_id
		);

		$new_client_id = SI_Client::new_client($new_client_args);

		// Set client information form the user information
		$user_meta = get_user_meta( $user_id );
		$client_obj = SI_Client::get_instance( $new_client_id );

		$client_obj->set_address(array(
			'street' 		=> $user_meta['_yl_l_street_address'][0],
			'city' 			=> $user_meta['_yl_l_city'][0],
			'postal_code'	=> $user_meta['_yl_l_zip_code'][0],
			'zone'			=> $us_states_codes_inverse[$user_meta['_yl_l_state'][0]],
		));

		return $new_client_id;
	}
	return $assoc_clients[0];
}

// Pluggable wp mail function write here

if ( !function_exists('wp_new_user_notification') ) :

function wp_new_user_notification($user_id, $random_password, $user_name, $first_name, $last_name, $user_email, $lease_id) {

 	$email_subject = get_option('clients_email_subject');
	$email_message = get_option('clients_email_message');			
	$search = array();
	$replace = array();	
	
	$search[] = '%%client-name%%';
	$replace[] = $first_name;
	
	$search[] = '%%user-name%%';
	$replace[] = $user_name;
	
	$search[] = '%%user-password%%';
	$replace[] = $random_password;
	
	$search[] = '%%lease-sign-url%%';
	$replace[] = ( get_permalink(get_option('yl_summary_sign_page')).'?lid='.$lease_id );
	// wp_login_url
	
	$get_message = str_replace($search, $replace, $email_message);	
	$get_message = 	stripslashes($get_message);
	$get_message = nl2br($get_message);  
	
	$headers  = 'MIME-Version: 1.0' . "\r\n";
	$headers .= 'Content-type: text/plain; charset=iso-8859-1' . "\r\n";

	add_filter('wp_mail_content_type',create_function('', 'return "text/html"; '));			
	@wp_mail( $user_email, $email_subject, $get_message, $headers );
}

endif;

// if user already created just sent there information function
function sent_info_to_mail($first_name, $last_name, $user_email, $lease_id) {

 	$email_subject = get_option('clients_email_subject');
	$email_message = get_option('clients_email_message');			
	$search = array();
	$replace = array();	
	
	$search[] = '%%client-name%%';
	$replace[] = $first_name;
	
	$search[] = '%%user-name%%';
	$replace[] = $user_email;
	
	$search[] = '%%user-password%%';
	$replace[] = 'You are already registerd. Please login to view your lease.';	
	
	$search[] = '%%lease-sign-url%%';
	$replace[] = ( get_permalink(get_option('yl_summary_sign_page')).'?lid='.$lease_id );
	//$replace[] = wp_login_url();
	
	$get_message = str_replace($search, $replace, $email_message);	
	$get_message = stripslashes($get_message);
	$get_message = nl2br($get_message);  
	
	$headers  = 'MIME-Version: 1.0' . "\r\n";
	$headers .= 'Content-type: text/plain; charset=iso-8859-1' . "\r\n";

	add_filter('wp_mail_content_type',create_function('', 'return "text/html"; '));			
	@wp_mail( $user_email, $email_subject, $get_message, $headers );	
}

// change the wp default mail name
add_filter("wp_mail_from_name", "site_mail_from_name");
function site_mail_from_name() {
	return get_bloginfo('name');
}
?>
