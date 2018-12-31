<?php
/**
 *
 * Front End Invoice Management for Building Managers
 *
 * Displays a form for BMs to be able to add payments
 * and re-send invoices from the front-end.
 *
 */

add_shortcode('bm-front-end-invoice-management', 'yl_bm_front_end_invoices_sc');
function yl_bm_front_end_invoices_sc($content = null) {

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

        if (isset($_GET['iid']) && !$_POST) {
        	$invoice_id = $_GET['iid'];
        	$invoice_obj = SI_Invoice::get_instance($invoice_id);
			$invoice_total = number_format($invoice_obj->get_calculated_total(), 2, '.', '');
			$invoice_client_id = $invoice_obj->get_client_id();
			$invoice_client_name = get_the_title($invoice_client_id);			
        	?>

        	<hr>
			<h3 class="text-center">Payment for Invoice #<?php echo $invoice_id; ?> for <?php echo esc_html($invoice_client_name); ?>.  Invoice Total is $<?php echo esc_html($invoice_total); ?></h3>
        	<div class="col-md-3"></div>
        	<div class="col-md-6">
	        	<div class="row">
		        	<div class="col-md-12 form-group">
		        		<label>Amount</label>
		        		<div class="input-group">
	                        <span class="input-group-addon">$</span>
	                        <input class="text-center add-payment-frontend-amount form-control" type="text" value="">
	                    </div>
		        	</div>

		        	<div class="col-md-12 form-group">
		        		<label>Check Number</label>
	                    <input class="text-center add-payment-frontend-id form-control" type="text" value="">
		        	</div>

		        	<div class="col-md-12 form-group">
		        		<label>Date</label>
	                    <input class="text-center add-payment-frontend-date form-control" type="text" value="<?php echo date('Y-m-j', time()); ?>">
	                    <p class="small">Please use a <strong>YYYY-MM-DD</strong> format.</p>
		        	</div>

		        	<div class="col-md-12 form-group">
		        		<label>Note</label>
	                    <textarea class="text-center add-payment-frontend-note form-control"></textarea>
		        	</div>

		        	<div class="col-md-12 text-center form-group">
		        		<span class="add-payment-frontend-submit btn btn-danger" data-invoice-id="<?php echo $invoice_id; ?>">Add Payment</span>
		        	</div>

		        	<input type="hidden" class="add-payment-frontend-nonce" value="<?php echo wp_create_nonce( 'si_payments_nonce' ); ?>">
		        </div>
		    </div>
		    <div class="col-md-3"></div>

		    <div class="row">
	        	<div class="col-md-12 form-group">
			        <hr >
			    </div>
			</div>

        	<?php
        }
        ?>

        <br><br>
		<style type="text/css">
			.icon_has_autopay{ background:#000000; color:#FFFFFF; border-radius:50%; padding:2px 5px; font-size:12px; }
		</style>
        <form action="" method="post">
	        <div class="row">
	        	<div class="col-md-10 form-group">
					<?php
					if(isset($_POST['search-invoices'])){ 
						$search_text = $_POST['search-invoices'];
					}else{
						$search_text = '';
					}	
					?>
	        		<input type="text" class="form-control manage_invoices_search_field" name="search-invoices" value="<?php echo esc_attr($search_text); ?>">
	        	</div>
	        	<!--
	        	<div class="col-md-3 form-group">
	        		<select name="search_filter" class="form-control">
	        			<option value="clients" <?php echo (($_POST['search_filter'] == 'clients') ? 'selected="selected"' : ''); ?>>Client names</option>
						<option value="company" <?php echo (($_POST['search_filter'] == 'company') ? 'selected="selected"' : ''); ?>>Company name</option>
	        			<option value="invoice" <?php echo (($_POST['search_filter'] == 'invoice') ? 'selected="selected"' : ''); ?>>Invoice name</option>
						<option value="suite" <?php echo (($_POST['search_filter'] == 'suite') ? 'selected="selected"' : ''); ?>>Suite #</option>
	        		</select>
	        	</div>
		        -->
	        	<div class="col-md-2 form-group">
	        		<input type="submit" class="btn btn-success manage_invoices_form_submit form-control" value="Search">
	        	</div>
	        </div>
	        <div class="row">
	        	<div class="col-md-6 form-group">
	        		<label for="all_invoices"><input type="radio" name="display" id="all_invoices" value="all" <?php echo (((isset($_POST['display'])) && ($_POST['display'] == 'all') || (!$_POST)) ? 'checked="checked"' : ''); ?>> All invoices</label>
	        		&nbsp;&nbsp;-&nbsp;&nbsp;<label for="paid_invoices"><input type="radio" name="display" id="paid_invoices" value="paid" <?php echo ((isset($_POST['display'])) && ($_POST['display'] == 'paid') ? 'checked="checked"' : ''); ?>> Paid invoices</label>
	        		&nbsp;&nbsp;-&nbsp;&nbsp;<label for="unpaid_invoices"><input type="radio" name="display" id="unpaid_invoices" value="unpaid" <?php echo ((isset($_POST['display'])) && ($_POST['display'] == 'unpaid') ? 'checked="checked"' : ''); ?>> Pending invoices</label>
	        	</div>
	        	<div class="col-md-3 text-right">
	        		<label>Since</label>
	        	</div>
	        	<div class="col-md-3 form-group">
	        		<div class='input-group date yl-datepicker'>
	                    <input type='text' class="form-control" name="since_date" value="<?php echo ((isset($_POST['display'])) && ($_POST['since_date']) ? $_POST['since_date'] : date('m/d/Y', strtotime('-90 days')) ); ?>" />
	                    <span class="input-group-addon">
	                        <span class="glyphicon glyphicon-calendar"></span>
	                    </span>
	                </div>
	        	</div>
	        </div>
	    </form>

        <?php
        if ($_POST) {
        	$posts = array();

        	// Post status
        	if ((isset($_POST['display'])) && ($_POST['display'] == 'paid')) {
        		$post_status = array( 'complete' );
        	}
        	elseif ((isset($_POST['display'])) && ($_POST['display'] == 'all')) {
        		$post_status = array( 'all' );
        	}
        	else {
        		$post_status = array( 'publish', 'future', 'partial', 'archived' );
        	}

        	// Date
        	$date = array(
		    	'after' => date('Y-m-d', strtotime($_POST['since_date']))
		    );


        	// Lets first search for the suite # on invoices titles.
        	$args = array(
        		'post_type' 		=> 'sa_invoice',
        		'numberposts'		=> -1,
        		'post_status'		=> $post_status,
        		's'					=> esc_html($_POST['search-invoices']),
        		'date_query'		=> $date,
        		//'suppress_filters' 	=> false
        	);
        	$tmp_posts = get_posts($args);
        	foreach ($tmp_posts as $curr_post) {
        		$posts[$curr_post->ID] = $curr_post;
        	}
			wp_reset_postdata();

        	// Let's now search on invoices line items
        	// This is because pooja's line items usually include the name of the LEASE, which includes the room #.
        	$args = array(
        		'post_type' 	=> 'sa_invoice',
        		'numberposts'	=> -1,
        		'post_status'	=> $post_status,
        		'meta_query'	=> array(
        			array(
        				'key' => '_doc_line_items',
			            'value'   => esc_html($_POST['search-invoices']),
			            'compare' => 'LIKE'
        			)
        		),
        		'date_query'		=> $date,
        		//'suppress_filters' => false
        	);
        	$tmp_posts = get_posts($args);
        	foreach ($tmp_posts as $curr_post) {
        		$posts[$curr_post->ID] = $curr_post;
        	}
			wp_reset_postdata();

        	// Now let's search for client names and then get all posts for that client
        	$args = array(
        		'post_type' 	=> 'sa_client',
        		'numberposts'	=> -1,
        		'post_status'	=> array( 'publish' ),
        		's'				=> esc_html($_POST['search-invoices']),
        		'date_query'	=> $date,
        		//'suppress_filters' => false
        	);
        	$tmp_posts = get_posts($args);
        	$client_ids = array();
        	foreach ($tmp_posts as $client) {
        		$client_ids[] = $client->ID;
        	}
			wp_reset_postdata();

    }else{
            	$posts = array();

            	// Post status
    			$post_status = array( 'publish', 'future', 'partial', 'archived', 'all', 'complete' );

            	// Date
            	$date = array(
    		    	'after' => date('Y-m-d', strtotime('-90 days'))
    		    );


            	// Lets first search for the suite # on invoices titles.
            	$args = array(
            		'post_type' 		=> 'sa_invoice',
            		'numberposts'		=> -1,
            		'post_status'		=> $post_status,
            		's'					=> '*',
            		'date_query'		=> $date,
            		//'suppress_filters' 	=> false
            	);
            	$tmp_posts = get_posts($args);
            	foreach ($tmp_posts as $curr_post) {
            		$posts[$curr_post->ID] = $curr_post;
            	}
    			wp_reset_postdata();

            	// Let's now search on invoices line items
            	// This is because pooja's line items usually include the name of the LEASE, which includes the room #.
            	$args = array(
            		'post_type' 	=> 'sa_invoice',
            		'numberposts'	=> -1,
            		'post_status'	=> $post_status,
            		'meta_query'	=> array(
            			array(
            				'key' => '_doc_line_items',
    			            'value'   => '*',
    			            'compare' => 'LIKE'
            			)
            		),
            		'date_query'		=> $date,
            		//'suppress_filters' => false
            	);
            	$tmp_posts = get_posts($args);
            	foreach ($tmp_posts as $curr_post) {
            		$posts[$curr_post->ID] = $curr_post;
            	}
    			wp_reset_postdata();

            	// Now let's search for client names and then get all posts for that client
            	$args = array(
            		'post_type' 	=> 'sa_client',
            		'numberposts'	=> -1,
            		'post_status'	=> array( 'publish' ),
            		's'				=> '*',
            		'date_query'		=> $date,
            		//'suppress_filters' => false
            	);
            	$tmp_posts = get_posts($args);
            	$client_ids = array();
            	foreach ($tmp_posts as $client) {
            		$client_ids[] = $client->ID;
            	}
    			wp_reset_postdata();
    		}


        	$args = array(
        		'post_type' 	=> 'sa_invoice',
        		'numberposts'	=> -1,
        		'post_status'	=> $post_status,
        		'meta_query'	=> array(
        			array(
        				'key' => '_client_id',
			            'value'   => $client_ids,
			            'compare' => 'IN'
        			)
        		),
        		'date_query'		=> $date,
        		//'suppress_filters' => false
        	);
        	$tmp_posts = get_posts($args);
        	foreach ($tmp_posts as $curr_post) {
        		$posts[$curr_post->ID] = $curr_post;
        	}
			wp_reset_postdata();

			$postIdSuites = array();
			$suite_number = '';
			foreach ($posts as $post) {
				//$post_ids[] = $post->ID;
	        	$post_meta = get_post_meta($post->ID);
				if ((isset($post_meta['_yl_suite_id'][0])) && ($post_meta['_yl_suite_id'][0])) {
					$suite_number = get_post_meta($post_meta['_yl_suite_id'][0], '_yl_room_number', true);
					//echo $suite_obj_suite_number;
				}elseif ((isset($post_meta['_yl_lease_id'][0])) && ($post_meta['_yl_lease_id'][0]) && (get_post_meta($post_meta['_yl_lease_id'][0], '_yl_suite_number', true) != '-1'))  {
					$suite_number = get_post_meta($post_meta['_yl_lease_id'][0], '_yl_suite_number', true);
					//echo $lease_obj_suite_number;
				}else {
					if ((isset($post_meta['_yl_lease_user'][0])) && $post_meta['_yl_lease_user'][0]) {
						$user_obj = get_user_by('id', $post_meta['_yl_lease_user'][0]);
						$args = array(
							'post_type' 	=> 'lease',
							'numberposts'	=> 1,
							'post_status'	=> array( 'publish' ),
                          
							'meta_query'	=> array(
								array(
									'key' 		=> '_yl_lease_user',
									'value'   	=> $post_meta['_yl_lease_user'][0],
									'compare' 	=> '='
								)
							)
						);
						$leases = get_posts($args);
						$first_lease = $leases[0];
						$first_lease_suite = get_post_meta($first_lease->ID, '_yl_suite_number', true);

						if ($first_lease_suite == '-1') {
							$suite_number = 'Y-membership';
						}
						else {
							$suite_number = $first_lease_suite;
						}
						wp_reset_postdata();
					}
				}
				$postIdSuites[$post->ID] = strtolower(trim($suite_number));
			}
			//print_r($postIdSuites);
			//echo '<br>';
			//asort($postIdSuites);
			//print_r($postIdSuites);

			//echo '<br>';
			//$post_ids = array_keys( $postIdSuites );
			//print_r($post_ids);
			/*
        	$args = array(
        		'post_type' => 'sa_invoice',
				'posts_per_page' => -1,
				'post_status'	=> $post_status,
        		'post__in' => $post_ids,
				'orderby'   => 'post__in',
        	);
        	$tmp_posts = get_posts($args);
			$posts = array();
        	foreach ($tmp_posts as $curr_post) {
        		$posts[$curr_post->ID] = $curr_post;
        	}
			echo '<br>';
			$post_ids = array_keys( $posts );
			print_r($post_ids);*/
        	?>
        	<br><br>
			<div class="row">
	        	<div class="col-md-12">
	        		<table class="table table-striped yl-manage-invoices-table" data-page-length="50" data-order="[[ 0, &quot;desc&quot; ]]">
	        			<thead>
	        				<tr>
	        					<th>#</th>
	        					<!--
	        					<th>Date</th>
		        				-->
	        					<th>Suite #</th>
	        					<th>Invoice Name</th>
	        					<th>Client</th>
	        					<th>Status</th>
								<th></th>
	        					<th>Outstanding Balance</th>
	        					<th></th>
	        				</tr>
	        			</thead>

	        			<tbody>
	        			<?php
	        			//foreach ($post_ids as $post_id) {
                        
                        krsort($postIdSuites);
                       // echo "<pre>";print_r($postIdSuites);
						foreach ($postIdSuites as $post_id => $suite_number) {
                            
                            
							$post = get_post( $post_id );
							//echo "<pre>";print_r($post);

							setup_postdata( $post );
	        				$client_id = get_post_meta($post->ID, '_client_id', true);
	        				$total = get_post_meta($post->ID, '_total', true);
	        				$invoice_obj = SI_Invoice::get_instance($post->ID);
	        				$post_meta = get_post_meta($post->ID);
	        				$client_obj = get_post($client_id);
	        				?>
	        				<tr>
								<?php if(current_user_can( 'manage_options' )){ ?>
	        						<th><a href="<?php echo get_edit_post_link($post->ID); ?>"><?php echo $post->ID; ?></a></th>
								<?php }else{ ?>
									<th><?php echo $post->ID; ?></th>
								<?php } ?>
	        					<!--
	        					<td><?php echo $post->post_date; ?></td>
		        				-->
	        					<td>
	        						<?php
	        						/*
	        						// echo "Invoice id".$post_id;
	        						if(get_post_meta( $post_id, '_yl_lease_id', true )=="")
									{
									$line_items=maybe_unserialize(get_post_meta($post_id, '_doc_line_items', true ));
									$title="";
									foreach ($line_items as $l_key => $l_value) {
									if (strpos($l_value['desc'], 'Monthly Rent for ') !== false ) {
										$month_explode=explode('Monthly Rent for ', strip_tags($l_value['desc']));
										$title_without =str_replace(" Lease", "",$month_explode[1]);
										$title .=str_replace("Suite #", " ", $title_without)."</br>";
									}

									}
									echo rtrim($title,',');
									}
									else{
		        						if ($post_meta['_yl_suite_id'][0]) {
		        							$suite_obj_suite_number = get_post_meta($post_meta['_yl_suite_id'][0], '_yl_room_number', true);
		        							echo str_replace("Suite #", " ", $suite_obj_suite_number);
		        						}
		        						elseif (($post_meta['_yl_lease_id'][0]) && (get_post_meta($post_meta['_yl_lease_id'][0], '_yl_suite_number', true) != '-1'))  {
		        							$lease_obj_suite_number = get_post_meta($post_meta['_yl_lease_id'][0], '_yl_suite_number', true);
		        							echo str_replace("Suite #", " ", $lease_obj_suite_number);
			        					}
			        					else {
			        						if ($post_meta['_yl_lease_user'][0]) {
			        							$user_obj = get_user_by('id', $post_meta['_yl_lease_user'][0]);
			        							$args = array(
			        								'post_type' 	=> 'lease',
			        								'numberposts'	=> 1,
			        								'post_status'	=> array( 'publish' ),
			        								'meta_query'	=> array(
			        									array(
				        									'key' 		=> '_yl_lease_user',
												            'value'   	=> $post_meta['_yl_lease_user'][0],
												            'compare' 	=> '='
												        )
			        								)
			        							);
			        							$leases = get_posts($args);
			        							$first_lease = $leases[0];
			        							$first_lease_suite = get_post_meta($first_lease->ID, '_yl_suite_number', true);

			        							if ($first_lease_suite == '-1') {
			        								echo 'Y-membership';
			        							}
			        							else {
			        								echo str_replace("Suite #", " ", $first_lease_suite);
			        							}
												wp_reset_postdata();
			        						}
			        					}
			        				}
			        				*/

			        				$post_id = $post->ID;
			        				$lease_id = get_post_meta($post_id, '_yl_lease_id', true);
									$client_id = get_post_meta($post_id, '_user_id', true);
									$suite_number = get_post_meta($lease_id, '_yl_suite_number', true);
									$invoice_due_date = get_post_meta($post_id, '_due_date', true);

									//echo '<pre>';
									$rent_acc_cat_id = get_option('yl_category_id_rent');
									$line_items = get_post_meta($post_id, '_doc_line_items', true);
                                    //echo "<pre>";print_r($line_items);
									$line_suites = array();
									if(is_array($line_items) && (count($line_items) > 0)){
										foreach ($line_items as $line) {
											if (isset($line['accounting_cat']) && ($line['accounting_cat'] == $rent_acc_cat_id)) {
												$line_suites[] = str_replace("Monthly Rent for ", "", $line['desc']);
											}
										}
									}
									// echo "<pre>";print_r($line_suites);
									//echo '</pre>';

									if($suite_number == -1)
                                    {
										echo 'Y-Membership<br />';
                                    }
									else
                                    {    
										if (count($line_suites) > 1) {
											echo implode('<br>', $line_suites)."<br>";
										}
										else {
											echo $suite_number."<br />";
										}
                                    }
									//echo "<small>Due: ".date("Y-m-d", $invoice_due_date).'</small>';
	        						?>

	        					</td>
	        					<td><?php echo esc_html($post->post_title); ?></td>
	        					<td><?php echo esc_html($client_obj->post_title); ?></td>
	        					<td class="<?php echo $post->post_status; ?>"><?php echo $invoice_obj->get_status_label( $invoice_obj->get_status() ); ?></td>
	        					<td><?php 
									$invoice_client_id = $invoice_obj->get_client_id();
									echo yl_is_autopay_setup($invoice_client_id); 
									?></td>
								<td><strong>
	        						<?php
									
	        						if ((float) round($invoice_obj->get_balance(),2) < 0 ) {
										$total_paid = abs($invoice_obj->get_balance()) + $invoice_obj->get_calculated_total();
	        							?>
	        							$<?php echo number_format($total_paid, 2, '.', ''); ?> of $<?php echo number_format($invoice_obj->get_calculated_total(), 2, '.', ''); ?>
	        							<?php
		        					}elseif ((float) round($invoice_obj->get_balance(),2) == 0 ) {
										?>
										$<?php echo number_format($invoice_obj->get_calculated_total(), 2, '.', ''); ?> of $<?php echo number_format($invoice_obj->get_calculated_total(), 2, '.', ''); ?>
										<?php
									}elseif ((float) round($invoice_obj->get_balance(),2) < (float)round($invoice_obj->get_calculated_total(),2)) {
	        							?>
	        							$<?php echo number_format($invoice_obj->get_balance(), 2, '.', ''); ?> of $<?php echo number_format($invoice_obj->get_calculated_total(), 2, '.', ''); ?>
	        							<?php
		        					}
		        					else {
		        						?>
		        						$<?php echo number_format($invoice_obj->get_balance(), 2, '.', ''); ?>
		        						<?php
		        					}
		        					?></strong>
	        					</td>
	        					<td class="text-right bm-buttons-block">
	        						<a alt="View" title="View" href="<?php echo get_site_url(); ?>/sprout-invoice/<?php echo $post->post_name; ?>" class="btn btn-xs btn-success"><i class="fa fa-search-plus" aria-hidden="true"></i></a>
	        						<a alt="Add Payment" title="Add payment" href="?iid=<?php echo $post->ID; ?>" class="btn btn-xs btn-primary"><i class="fa fa-plus" aria-hidden="true"></i> <i class="fa fa-credit-card" aria-hidden="true"></i></a>
	        						<a href="#" alt="Re-send" title="Re-send" data-invoice-id="<?php echo $post->ID; ?>" data-user-id="<?php echo get_post_meta($invoice_client_id, '_associated_users', true); ?>" data-user-nonce="<?php echo wp_create_nonce( 'sprout_invoices_controller_nonce' ); ?>" class="btn btn-xs btn-info resend-invoice-frontend-submit"><i class="fa fa-envelope-o" aria-hidden="true"></i> <i class="fa fa-share" aria-hidden="true"></i></a>
									
									<?php if ( is_super_admin() ) { ?>
									<a href="#" alt="Delete" data-client-name="<?php echo $client_obj->post_title; ?>" data-invoice-amount="<?php echo round($invoice_obj->get_calculated_total(),2); ?>" title="Delete" data-invoice-id="<?php echo $post->ID; ?>" data-user-id="<?php echo get_post_meta($invoice_client_id, '_associated_users', true); ?>" data-user-nonce="<?php echo wp_create_nonce( 'sprout_invoices_delete_nonce' ); ?>" class="btn btn-xs bg-danger-delete remove-invoice-frontend-admin"><i class="fa fa-trash-o" aria-hidden="true"></i></a>
									<?php } ?>
									
	        					</td>
	        				</tr>
	        				<?php
	    	    		}
		        		?>
			        	</tbody>
		        	</table>
	        	</div>
	        </div>
        	<?php
        }
      //}

    $content = ob_get_clean();
    return $content;
}
