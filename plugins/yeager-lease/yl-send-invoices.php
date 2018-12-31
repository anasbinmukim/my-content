<?php
function remove_invoice_from_frontend_admin_callback() {
    global $wpdb;

    $invoice_id = $_POST['invoice_id'];
	wp_trash_post( $invoice_id );
	
	echo wp_json_encode( array( 'response' => __( 'Successfully Trashed', 'sprout-invoices' ) ) );

    wp_die();
}
add_action( 'wp_ajax_remove_invoice_from_frontend_admin', 'remove_invoice_from_frontend_admin_callback' );
add_action( 'wp_ajax_nopriv_remove_invoice_from_frontend_admin', 'remove_invoice_from_frontend_admin_callback' );




function send_invoices_via_ajax_callback() {
    global $wpdb;

    $step = $_POST['step'];
    $invoice_id_list = $_POST['invoice_id'];

    if ($step == '1') {
    	$args = array(
    		'post_type' 	=> 'sa_invoice',
    		'numberposts'	=> -1,
    		'post_status'	=> array( 'publish', 'future', 'partial', 'archived' ),
    		'meta_query' 	=> array(
    			array(
					'key' 		=> '_yl_mailed_i_'.date('Y_m_d', time()),
					'compare' 	=> 'NOT EXISTS'
				)
    		)
    	);
    	$posts = get_posts($args);

    	foreach ($posts as $post) {
    		$to_return[] = $post->ID;
    	}

    	echo json_encode($to_return);
    }
    else {
    	foreach ($invoice_id_list as $invoice_id) {
    		
    		
    		$invoice_obj = SI_Invoice::get_instance($invoice_id);

    		if ($invoice_obj->get_client_id() != 0) {
	    		$client_obj = SI_Client::get_instance($invoice_obj->get_client_id());
	    		
	    		$assoc_users = $client_obj->get_associated_users();
	    		if (count($assoc_users) > 0) {
	    			$user_id = $assoc_users[0];
	    		}
	    		else {
	    			$user_id = 0;
	    		}
			
	    		if ($user_id != 0) {
		    		
		    		$user = get_user_by('id', $user_id);
					$user_email = $user->user_email;
		    		$recipients = array( $user_email );
		    		$from_email = get_option('admin_email');
		    		$from_name = get_option('blogname');

		    		if ($recipients[0] == "") {
		    			echo "\n".'- User #'.$user_email.' does not have an email address.'."\n";
		    		}
		    		
		    		do_action( 'send_invoice', $invoice_obj, $recipients, $from_email, $from_name );
		    		update_post_meta($invoice_id, '_yl_mailed_i_'.date('Y_m_d', time()), '1');
		    	}
		    }
		    else {
		    	echo "\n".'- Invoice #'.$invoice_id.' has no client associated to it. Can not send.'."\n";
		    }
    	}
    }

    wp_die();
}
add_action( 'wp_ajax_send_invoices_via_ajax', 'send_invoices_via_ajax_callback' );
add_action( 'wp_ajax_nopriv_send_invoices_via_ajax', 'send_invoices_via_ajax_callback' );



add_action('admin_menu', 'register_send_invoices_page');
function register_send_invoices_page() {
	add_submenu_page( 'edit.php?post_type=sa_invoice', 'Send Invoices', 'Send Invoices', 'edit_posts', 'send-invoices', 'yl_send_invoices_page_callback' );
}

function yl_send_invoices_page_callback() {
	if ($_POST['yl_send_emails_submit']) {

		$args = array(
    		'post_type' 	=> 'sa_invoice',
    		'numberposts'	=> -1,
    		'post_status'	=> array( 'publish', 'future', 'partial', 'archived' ),
    		'meta_query' 	=> array(
    			array(
					'key' 		=> '_yl_mailed_i_'.date('Y_m_d', time()),
					'compare' 	=> 'EXISTS'
				)
    		)
    	);
    	$posts = get_posts($args);

    	foreach ($posts as $post) {
    		delete_post_meta($post->ID, '_yl_mailed_i_'.date('Y_m_d', time()));
    	}

    	echo '<strong>All meta deleted</strong><br><br><br>';
	}
	?>

	<div class="wrap">
		<h2><?php echo __('Send Invoices'); ?></h2>

		<p class="submit">
			<input type="submit" name="yl_send_emails_submit" class="button-primary send_emails_btn" value="<?php _e('Send all pending invoices now') ?>" />
		</p>

		<p class="echo"></p>

		<br><br><br><br>
		<form name="yl_send_invoices" id="yl_send_invoices" method="post" action="edit.php?post_type=sa_invoice&page=send-invoices">
			<input type="submit" name="yl_send_emails_submit" class="button" value="<?php _e('Reset invoices') ?>" />	
		</form>
	</div>

	<script>
	jQuery(document).ready(function() {
		var response_obj;
		var current_n = 0;
		var total_invoices = 0;
		var n_per_batch = 10;

		jQuery('.send_emails_btn').click(function() {

			jQuery(this).val('Sending, please hold on and dont leave this page.').prop('disabled', true);

			var ajax_data = {
                'action': 'send_invoices_via_ajax',
                'step': '1'
            };

			jQuery.post(ajaxurl, ajax_data, function(response) {
			    response_obj = jQuery.parseJSON(response);
			    jQuery('.echo').html(jQuery('.echo').html()+'<br>There are '+response_obj.length+' invoices to send...<br><br>');
			    total_invoices = response_obj.length;

			    start_sending();
			});
		});

		function start_sending() {
			var invoices_list = new Array();

			for (var i = 0; i < n_per_batch; i++) {
				if (response_obj.hasOwnProperty(i)) {
					invoices_list[i] = response_obj[i];
				}
			}
			response_obj.splice(0, n_per_batch);


			

			var ajax_data = {
                'action': 'send_invoices_via_ajax',
                'step': '2',
                'invoice_id' : invoices_list
            };
            if (invoices_list.length > 0) {

            	var up_to_what = ((current_n+1)*n_per_batch);
            	if (up_to_what > total_invoices) {
            		up_to_what = total_invoices;
            	}
            	jQuery('.echo').html(jQuery('.echo').html()+'<br>Sending invoices '+((current_n*n_per_batch)+1)+' to '+up_to_what+'...');

				jQuery.post(ajaxurl, ajax_data, function(response) {
				    //response_obj = jQuery.parseJSON(response);
				    console.log(response);
				    jQuery('.echo').html(jQuery('.echo').html()+' DONE');

				    current_n++;

				    start_sending();
				});
			}
		}
	});
	</script>

	<?php
	/*
	?>
	<div class="wrap">
		<h2><?php echo __('Send Invoices'); ?></h2>

		<?php
		if ($_POST) {
			$emails_sent = 0;

			$args = array(
	    		'post_type' 	=> 'sa_invoice',
	    		'numberposts'	=> -1,
	    		'post_status'	=> array( 'publish', 'future', 'partial', 'archived' ),
	    		'meta_query' 	=> array(
	    			array(
						'key' 		=> '_yl_mailed_i_'.date('Y_m_d', time()),
						'compare' 	=> 'NOT EXISTS'
					)
	    		)
	    	);
	    	$posts = get_posts($args);
	    	$total_invoices = count($posts);

			$args = array(
	    		'post_type' 	=> 'sa_invoice',
	    		'numberposts'	=> 35,
	    		'post_status'	=> array( 'publish', 'future', 'partial', 'archived' ),
	    		'meta_query' 	=> array(
	    			array(
						'key' 		=> '_yl_mailed_i_'.date('Y_m_d', time()),
						'compare' 	=> 'NOT EXISTS'
					)
	    		)
	    	);
	    	$posts = get_posts($args);
	    	foreach ($posts as $post) {
	    		update_post_meta($post->ID, '_yl_mailed_i_'.date('Y_m_d', time()), '1');

	    		$invoice_obj = SI_Invoice::get_instance($post->ID);
	    		$client_obj = SI_Client::get_instance($invoice_obj->get_client_id());
	    		$user_id = $client_obj->get_associated_users()[0];
	    		$user = get_user_by('id', $user_id);
    			$user_email = $user->user_email;
	    		$recipients = array( $user_email );

	    		$from_email = get_option('admin_email');
	    		$from_name = get_option('blogname');

	    		do_action( 'send_invoice', $invoice_obj, $recipients, $from_email, $from_name );

	    		$emails_sent++;
	    	}

	    	echo 'Sent '.$emails_sent.' of '.$total_invoices.'. Press the button again to send more invoices.';
		}
		
		?>
		<form name="yl_send_invoices" id="yl_send_invoices" method="post" action="edit.php?post_type=sa_invoice&page=send-invoices">	
			<p class="submit">
				<input type="hidden" name="yl_send_emails_placeholder" value="1">
				<input type="submit" name="yl_send_emails_submit" class="button-primary send_emails_btn" value="<?php _e('Send all pending invoices now') ?>" />
			</p>
		</form>	
		<script>
		jQuery(document).ready(function() {
			jQuery('.send_emails_btn').click(function() {

				jQuery(this).val('Sending, please hold on and dont leave this page.').prop('disabled', true);
				jQuery('#yl_send_invoices').submit();
			});
		});
		</script>

	</div>
	<?php
	*/
}