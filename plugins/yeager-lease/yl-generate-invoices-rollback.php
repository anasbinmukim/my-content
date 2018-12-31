<?php

add_action('admin_menu', 'yl_register_generate_invoices_rollback_page');
function yl_register_generate_invoices_rollback_page() {
	add_submenu_page( 'edit.php?post_type=sa_invoice', 'Rollback', 'Rollback', 'edit_posts', 'generate-invoices-rollback', 'yl_generate_invoices_rollback_callback' );
}


function yl_generate_invoices_rollback_callback() {
	?>
	<div class="wrap">
		<?php
		$rollback_date = get_option('_yl_generated_invoices_batch');
		?>

		<h1>Rollback Invoices <?php if ($rollback_date) { ?><a href="#" data-date="<?php echo $rollback_date; ?>" class="page-title-action">Rollback last batch: <?php echo $rollback_date; ?></a><?php } ?></h1>
	
		<?php
		if (!$rollback_date) { 
			echo "There is no invoice batch to roll back to.";
		}
		?>	

		<pre class="return">
		</pre>

	</div>

	<?php

	// Insert JS and CSS code
	yl_generate_invoices_rollback_js_callback();	
	yl_generate_invoices_rollback_css_callback();
}



function yl_generate_invoices_rollback_js_callback() {
	?>

	<script>
	jQuery(document).ready(function() {
		var ids_arr = '';
		var ids_total = 0;
		var ids_i = 0;
		var ids_i_display = 1;
		var posts_pert_batch = 10;
		
		jQuery('.page-title-action').click(function(e) {
			e.preventDefault();
			var _d = jQuery(this).attr('data-date'); 
			if (confirm('You are about to delete all invoices generated on the last batch. Are you sure you want to continue?')) {
				yl_start_rollback_ajax(_d);
			}
		})

		// Start the AJAX rollback
		var yl_start_rollback_ajax = (function(_d) {
			ids_arr = '';
			ids_total = 0;
			ids_i = 0;
			ids_i_display = 1;

			yl_clear();
			yl_print('Fetching invoices... please wait.');
			yl_print('<strong>Do not close this window until we tell you it is safe to do so.</strong>');

			var data = {
				'action': 'yl_generated_invoices_batch_start',
				'date'	: _d
			};
			jQuery.post(ajaxurl, data, function(response) {
				//yl_clear();
				yl_print('');

				ids_arr = jQuery.parseJSON(response);
				ids_total = ids_arr.length;

				yl_print('<strong>'+ids_total+'</strong> invoices found...');
				yl_print('');

				yl_start_loop();
			});
		});

		function yl_start_loop() {
			var ids_to_send = new Array()

			var ids_to_loop = ids_i+posts_pert_batch;
			if (ids_to_loop > ids_total) {
				ids_to_loop = ids_total;
			}

			for (i = ids_i; i < ids_to_loop; i++) {
				ids_to_send[i] = ids_arr[i].id;
			}

			if (ids_to_send.length > 0) {
				yl_delete_invoices(ids_to_send);
			}
			else {
				yl_print('<center><strong>Deleting meta from leases...</strong></center>');
				yl_clear_leases_meta();
			}
		}

		function yl_delete_invoices(_ids_to_send) {
			//console.log('sending: '+_ids_to_send);

			var fetching_max = (ids_i_display+posts_pert_batch);
			if (fetching_max > ids_total) {
				fetching_max = ids_total;
			}

			yl_print('<center><strong>['+ids_i_display+'-'+fetching_max+' of '+ids_total+']</strong></center>');
			//yl_print(response);

			var data = {
				'action': 'yl_generated_invoices_delete_invoices',
				'ids'	: _ids_to_send
			};
			jQuery.post(ajaxurl, data, function(response) {
				yl_print(response);
				jQuery("html, body").animate({ scrollTop: jQuery(document).height() }, 1000);
				ids_i = ids_i+posts_pert_batch;
				ids_i_display = ids_i_display+posts_pert_batch;
				yl_start_loop();
			});
		}

		function yl_clear_leases_meta() {
			var _d = jQuery('.page-title-action').attr('data-date');

			var data = {
				'action': 'yl_generated_invoices_clear_meta',
				'date'	: _d
			};
			jQuery.post(ajaxurl, data, function(response) {
				yl_print(response);
			});

		}

		function yl_clear() {
			jQuery('.return').html('');
		}

		function yl_print(msg) {
			jQuery('.return').html(jQuery('.return').html()+'<br>'+msg);
		}
	});
	</script>

	<?php
}

function yl_generate_invoices_rollback_css_callback() {
	?>

	<style>
	
	</style>
	
	<?php
}


/**
 * Ajax functions
 *
 * The following actions and functions belong to the invoice rollback process.
 */
add_action( 'wp_ajax_yl_generated_invoices_batch_start', 'yl_generated_invoices_batch_start_callback' );
function yl_generated_invoices_batch_start_callback() {
	global $wpdb; // this is how you get access to the database

	$date = $_POST['date'];

	$args = array(
		'post_type' 	=> 'sa_invoice',
        'numberposts'	=> -1,
        'post_status'	=> array( 'all' ),
        'orderby'		=> 'ID',
        'order'			=> 'DESC',
		'meta_query' 	=> array(
			array(
				'key' => '_yl_generated_in_batch_of',
				'value' => $date,
				'compare' => '==',
			)
		)
	);
	$invoices = get_posts($args);

	$return = array();
	foreach ($invoices as $invoice) {
		$return[] = array(
			'id' => $invoice->ID,
			'title' => $invoice->post_title,
			'url' => $invoice->guid
		);
	}

	echo json_encode($return);

	wp_die(); // this is required to terminate immediately and return a proper response
}




add_action( 'wp_ajax_yl_generated_invoices_delete_invoices', 'yl_generated_invoices_delete_invoices_callback' );
function yl_generated_invoices_delete_invoices_callback() {
	global $wpdb; // this is how you get access to the database

	$ids = $_POST['ids'];

	$args = array(
		'post_type' 	=> 'sa_invoice',
        'numberposts'	=> -1,
        'post__in' 		=> $ids,
        'post_status'	=> array( 'all' ),
        'orderby'		=> 'ID',
        'order'			=> 'DESC'
	);
	$invoices = get_posts($args);

	foreach ($invoices as $invoice) {
		// Get the SI_Invoice object
		$invoice_obj = SI_Invoice::get_instance($invoice->ID);
		$invoice_status = $invoice_obj->get_status_label( $invoice_obj->get_status() );

		echo '<table class="wp-list-table widefat fixed striped posts"><tr><td>';
		echo 'Fetching invoice <a href="post.php?post='.$invoice->ID.'&action=edit" target="_blank">#'.$invoice->ID.'</a>: <strong>'.$invoice->post_title.'</strong><br>';
		echo 'Status: <strong>'.$invoice_status.'</strong><br>';
		echo 'Balance: <strong>';

		if ((float) round($invoice_obj->get_balance(),2) < 0 ) {
			$total_paid = abs($invoice_obj->get_balance()) + $invoice_obj->get_calculated_total();
			echo '$'.(float)round($total_paid,2); ?> of $<?php echo (float)round($invoice_obj->get_calculated_total(),2);
		}elseif ((float) round($invoice_obj->get_balance(),2) == 0 ) {
			echo '$'.(float)round($invoice_obj->get_calculated_total(),2); ?> of $<?php echo (float)round($invoice_obj->get_calculated_total(),2);
		}elseif ((float) round($invoice_obj->get_balance(),2) < (float)round($invoice_obj->get_calculated_total(),2)) {
			echo '$'.(float) round($invoice_obj->get_balance(),2); ?> of $<?php echo (float)round($invoice_obj->get_calculated_total(),2);
		}
		else {
			echo '$'.$invoice_obj->get_balance();
		}

		echo '</strong><br>';


		// Get client information
		$client_id = $invoice_obj->get_client_id();
		$client_obj = SI_Client::get_instance($client_id);
		if ($client_obj) {
			$client_usrs = $client_obj->get_associated_users();
			$client_data = get_user_meta($client_usrs[0]);
			echo 'Client <a href="post.php?post='.$client_id.'&action=edit" target="_blank">#'.$client_id.'</a>: <strong>'.$client_data['first_name'][0].' '.$client_data['last_name'][0].'</strong><br>';
		}

		// Check for credits for this client
		// Only reason to show this is to try the 'account credits' class
		/*
		if ($client_id) {
			$credits = SI_Account_Credits_Clients::get_associated_credits($client_id);
		
			if (count($credits) > 0) {
				echo '</td></tr><tr><td>';
				echo '<strong>Found: '.count($credits).' credits.</strong><br><br>';
				foreach ($credits as $credit_id) {
					$credit = SI_Credit::get_credit_entry( $credit_id );
					$credit_data = $credit->get_data();
					//print_r($credit_data);
				}
			}
		}
		*/

		// Check if there are payments for this invoice
		$payments = $invoice_obj->get_payments();
		$total_payments = 0;
		if (count($payments) > 0) {
			echo '</td></tr><tr><td>';
			echo '<strong>Found: '.count($payments).' payment(s).</strong><br><br>';

			
			foreach ($payments as $payment) {
				//echo $payment.'<br>';
				$payment_obj = SI_Payment::get_instance($payment);
				echo 'Status #'.$payment.': '.$payment_obj->get_status().' - Amount: '.$payment_obj->get_amount().' - Trans ID: '.$payment_obj->get_transaction_id().'<br>';
				$total_payments += $payment_obj->get_amount();
			}
		}
		if ($total_payments > 0) {
			echo 'Creating credit for $'.$total_payments.'<br>';

			// If the invoice had (a) payment(s), make a credit for that
			$credit_data = array(
				'client_id' => (int) $client_id,
				'type_id' => (int) SI_Credit::get_payment_credit_type(),
				'credit_val' => (float) $total_payments,
				'note' => 'Credit for Payment Applied to Rolled Back (deleted) Invoice #'.$invoice->ID.': '.get_the_title( $invoice ),
				'date' => (int) current_time( 'timestamp' ),
				'user_id' => get_current_user_id(),
			);
			//print_r($credit_data);

			$new_credit_id = SI_Account_Credits_Clients::create_associated_credit( $client_id, $credit_data );
		}

		/*
		// Make a payment
		$payment_id = Credits_Payment::create_admin_payment( $invoice_id, $amount, $new_credit_id, date( get_option( 'date_format' ), time() ), sprintf( __( 'Credit Applied from Account Balance. Payment Credit ID #%s', 'sprout-invoices' ), $new_credit_id ) );

		if ( ! $payment_id ) {
			return;
		}

		do_action( 'si_credit_payment', $new_credit_id, $payment_id );

		$data = array(
			'payment_credit_id' => $new_credit_id,
			'payment_id' => $payment_id,
			);

		$credit = SI_Credit::get_credit_entry( $new_credit_id );
		$data = array_merge( $credit->get_data(), $data );
		$credit->set_data( $data );
		*/




		// Check if there are written off invoices associated to this invoice
		$wo_invoices = array_filter(get_post_meta($invoice->ID, '_written_off_invoices'));
		if (!empty($wo_invoices) > 0) {
			$wo_i = count($wo_invoices[0]);
			echo '</td></tr><tr><td>';
			echo '<strong>Found: '.$wo_i.' written off invoice(s).</strong><br><br>';

			//$payments = $invoice->get_pending_payments_total();
			//echo print_r($payments, true).'<br>';

			foreach ($wo_invoices[0] as $wo_invoice) {
				// Let's check this invoice balance
				$wo_invoice_obj = SI_Invoice::get_instance($wo_invoice);
				$wo_invoice_balance = $wo_invoice_obj->get_balance();
				$wo_calc_paid = $wo_invoice_obj->get_payments_total();
				$wo_calc_total = $wo_invoice_obj->get_calculated_total();

				echo 'Recovering invoice <strong>#'.$wo_invoice.'</strong> - Balance: $'.$wo_invoice_balance.' - Paid: $'.$wo_calc_paid.' - Calc total: $'.$wo_calc_total.'<br>';
				if ($wo_calc_paid == 0) {
					$wo_invoice_obj->set_pending();
					echo '- Marking as <strong>Pending</strong>...<br>';
				}
				else {
					$wo_invoice_obj->set_as_partial();
					echo '- Marking as <strong>Partial Payment</strong>...<br>';
				}
			}
		}

		// Delete this invoice now
		echo '</td></tr><tr><td>';
		echo 'Deleting invoice...<br>';
		wp_delete_post($invoice->ID);
		
		echo '</td></tr></table>';
		echo '<br>';
	}
	
	wp_die();
}



add_action( 'wp_ajax_yl_generated_invoices_clear_meta', 'yl_generated_invoices_clear_meta_callback' );
function yl_generated_invoices_clear_meta_callback() {
	$date = $_POST['date'];

	$args = array(
		'post_type' => 'lease',
		'posts_per_page' => -1,
		'post_status' => 'publish',
		'orderby' => 'meta_value',
		'order' => 'ASC',
		'meta_query' => array(
			array(
				'key' 		=> '_yl_mk_i_'.$date,
				'compare' 	=> 'EXISTS'
			),
		)
	);	

	$posts = get_posts($args);
	foreach ($posts as $post) {
		$date_key = '_yl_mk_i_'.$date;
		delete_post_meta($post->ID, $date_key);
	}

	// Delete the option so user can not run the rollback again.
	delete_option('_yl_generated_invoices_batch');

	echo '<center>All DONE!</center>';

	wp_die();
}