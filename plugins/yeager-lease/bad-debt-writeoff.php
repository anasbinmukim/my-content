<?php
add_action('admin_menu', 'bad_debt_writeoff_submenu_page');

function bad_debt_writeoff_submenu_page() {
    add_submenu_page('edit.php?post_type=sa_invoice', 'Bad Debt', 'Bad Debt', 'edit_posts', 'bad-debt-writeoff', 'bad_debt_writeoff_page_callback');
}

function bad_debt_writeoff_page_callback() {

    if (isset($_GET['action_invoice_id'])) {
		$action_invoice_id = $_GET['action_invoice_id'];		
		$action_client_id = $_GET['client_id'];
		$bad_debt_credit_amount = get_post_meta($action_invoice_id, '_total', true);
		
		/*
		$data_credit = array(
			'client_id' => (int) $action_client_id, // the client id
			'credit_val' => (float) si_get_number_format( (float) $bad_debt_credit_amount ), // creates credit
			'note' => 'Bad Debt Credit for '.$action_invoice_id, // a note
			'date' => (int) current_time( 'timestamp' ), 
			'user_id' => get_current_user_id(), // admin user id that is creating the credit
		);
		$new_credit_id = SI_Account_Credits_Clients::create_associated_credit( $action_client_id, $data_credit );
		*/
		
		$number = '';
		$date = date('Y-m-d H:i:s');
		$notes = 'Bad Debt Payment';
		
		// create new payment
		$payment_id = SI_Payment::new_payment( array(
			'payment_method' => 'Bad Debt Payment',
			'invoice' => $action_invoice_id,
			'amount' => $bad_debt_credit_amount,
			'transaction_id' => $number,
			'data' => array(
			'amount' => $bad_debt_credit_amount,
			'check_number' => $number,
			'date' => strtotime( $date ),
			'notes' => $notes,
			),
		) );
	
		$invoice = SI_Invoice::get_instance( $action_invoice_id );
		$invoice->set_as_paid();		

        echo '<div class="updated"><p>Successfully Updated</p></div>';
    }


    echo '<div class="wrap">';
    echo '<h2>Bad Debt Writeoff</h2>';
    ?>
    
	<div class="form-group">
			<?php
			// Get all active clients
			$clients_args = array(
				'post_type' => 'sa_client',
				'status' => 'publish',
				'orderby' => 'title',
				'order' => 'ASC',				
				'numberposts' => -1,
			);
			$clients = get_posts($clients_args);
			
			$current_client_id = 0;
			if(isset($_GET['client_id'])){
				$current_client_id = $_GET['client_id'];
			}
			?> 
			<form action="/wp-admin/edit.php?post_type=sa_invoice&page=bad-debt-writeoff" method="get">   
			<input type="hidden" name="post_type" value="sa_invoice" />
			<input type="hidden" name="page" value="bad-debt-writeoff" />			                            
			<select class="form-control" name="client_id">
				<option value="">Select Client</option>
				<?php
				foreach ($clients as $client) {
					$client_id = $client->ID;
					$client_name = get_the_title($client->ID);
					if($client_name != ''){
					?>
					<option <?php if($current_client_id == $client_id){ ?> selected="selected" <?php } ?> value="<?php echo $client_id; ?>"><?php echo $client_name; ?></option>
					<?php				
					}						
				}
				?>
			</select>
			<input type="submit" name="get_client_invoices" class="button button-primary button-large" value="Show Invoices" />
			</form>
		</div>
		
		
		<?php if($current_client_id > 0){ ?>
		
		<?php
			$invoice_args = array(
				'post_type' => 'sa_invoice',
				'status' => array('publish', 'partial', 'write-off'),
				'numberposts' => -1,
				'meta_query' => array(
					'relation' => 'AND',
					array(
						'key'     => '_client_id',
						'value'   => $current_client_id,
						'compare' => '='
					),
					array(
						'key'     => '_total',
						'value'   => 0,
						'compare' => '>'
					),
				)
			);
			$invoices_query = get_posts($invoice_args);	
					
		?>
		
 		<table class="form-table">				
            <tr valign="top">
                <th scope="row">Invoices</th>
				<th>Amount</th>
				<th>Action</th>
            </tr>
           	<?php
			foreach ($invoices_query as $invoices) {
                    $invoice_id = $invoices->ID;
					$invoice_title = get_the_title($invoice_id);
					$invoice_amount = get_post_meta($invoice_id, '_total', true);
					echo "<tr><td>".$invoice_title.'</td>';					
					echo "<td>$".$invoice_amount." USD</td>";
					
					echo "<td>";
					echo '<a class="button" href="/wp-admin/edit.php?post_type=sa_invoice&page=bad-debt-writeoff&client_id='.$current_client_id.'&action_invoice_id='.$invoice_id.'&get_client_invoices=Show+Invoices">Mark As Bad Debt</a>';
					echo "</td>";
					
					echo "</tr>";
					
			}				
			?>					
        </table>		
		
		<?php }//eof if current clients ?>
    	

    <?php
	
    echo '</div>';
}
