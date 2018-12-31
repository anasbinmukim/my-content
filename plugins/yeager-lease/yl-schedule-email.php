<?php
add_action('admin_menu', 'register_invoice_submenu_page');
function register_invoice_submenu_page() {
	// add_submenu_page( 'edit.php?post_type=lease', 'Invoice Settings', 'Invoice Settings', 'edit_posts', 'invoice-settings', 'nvoice_settings_submenu_page_callback' );
}

function nvoice_settings_submenu_page_callback() {

	if(isset($_POST['invoice_settings_save'])){
		update_option( 'invoice_email_subject', $_POST['invoice_email_subject'] );
		update_option( 'invoice_email_message', $_POST['invoice_email_message'] );
		
		echo '<div class="updated"><p>Successfully Updated</p></div>';
	}
	
	if(isset($_POST['send_client_report'])){
		$client_id = $_POST['client_id'];
		if($client_id > 0){
			yl_generate_invoice_sent_to_client($client_id);
			echo '<div class="updated"><p>Successfully Sent!</p></div>';
		}elseif($client_id == 'all'){
			yl_generated_invoice_sent_to_all_clients();
			echo '<div class="updated"><p>Successfully Sent to All Clients!</p></div>';
		}else{
			echo '<div class="updated error"><p>Faild Sent!</p></div>';
		}
	}
	

	

	
	echo '<div class="wrap">';
		echo '<h2>Invoice Report</h2>';
		?>
		
	
<?php 
//$subscription_all = WC_Subscriptions_Manager::get_all_users_subscriptions();
//print_r($subscription_all);

?>

<?php
$user_id = 2;
$total_amount = 0;
$subscriptions = WC_Subscriptions_Manager::get_users_subscriptions( $user_id );
foreach($subscriptions as $subscrip_data){
	$order_id = $subscrip_data['order_id'];
	$product_id = $subscrip_data['product_id'];
	$variation_id = $subscrip_data['variation_id'];
	$period = $subscrip_data['period'];
	$interval = $subscrip_data['interval'];		
	$start_date = $subscrip_data['start_date'];
	$expiry_date = $subscrip_data['expiry_date'];
	$end_date = $subscrip_data['end_date'];
	$last_payment_date = $subscrip_data['last_payment_date'];
	
	$status = $subscrip_data['status'];
	if($status == 'active'){
		$room_number = get_post_meta($product_id, '_yl_room_number', true);
		//echo "<br />";
		$subscription_amount = WC_Subscriptions_Order::get_price_per_period( $order_id, $product_id );
		$total_amount += $subscription_amount;
	
	}//eof active subscription	
	
}
?>

		
		
		<h2>Invoice Pre-Notification To Client</h2>
		<form action="" method="post">
			<select name="client_id" id="client_id">
			<option value="">Select Client</option>
			<?php
				$user_query = new WP_User_Query( array( 'role' => 'lease_client' ) );
				// User Loop
				if ( ! empty( $user_query->results ) ) {
					foreach ( $user_query->results as $user ) {
						echo '<option value="'.$user->ID.'">'.$user->display_name.'</option>';
					}
				}			
			?>
			<option value="all">All Clients</option>
			</select>
			<input type="submit" name="send_client_report" id="send_client_report" value="Send Invoice Notification" class="button button-primary button-large" />
		</form>
		
		<br /><br /><br />
				
		<h2>Invoice Email Settings</h2>
		<form action="" method="post">
			<table class="form-table">				
				<tr valign="top">
					<th scope="row"><label for="invoice_email_subject">Subject</label></th>
					<td><input type="text" name="invoice_email_subject" id="invoice_email_subject" value="<?php echo get_option('invoice_email_subject'); ?>" class="regular-text" /></td>
				</tr>
				<tr valign="top">
					<th scope="row"><label for="invoice_email_message">Message</label></th>
					<td><textarea name="invoice_email_message" id="invoice_email_message" class="large-text" rows="15"><?php echo stripslashes(get_option('invoice_email_message')); ?></textarea><p class="description">Supported tags:<br />
						%%client-name%% = (Client Name)<br />
						</p></td>
				</tr>	
				
				<tr valign="top">
					<th scope="row"><label for="invoice_settings_save"></label></th>
					<td><input type="submit" class="button button-primary button-large" value="Settings Save" id="invoice_settings_save" name="invoice_settings_save"></td>
				</tr>							
			</table>
		</form>

		
		<?php	
		
	echo '</div>';

}



add_action( 'wp', 'yl_setup_schedule_email' );
/**
 * On an early action hook, check if the hook is scheduled - if not, schedule it.
 */
function yl_setup_schedule_email() {
	if ( ! wp_next_scheduled( 'yl_schedule_email' ) ) {
		wp_schedule_event( time(), 'daily', 'yl_schedule_email');
	}
}


add_action( 'yl_schedule_email', 'yl_do_this_daily' );
/**
 * On the scheduled action hook, run a function.
 */
function yl_do_this_daily() {
	// do something every hour
	$day = date('l');
	$hour = date('g A');
	$day_number = date('j');
	

/*	if($day_number){
		$to = 'anasbinmukim@gmail.com';
		$subject = 'Yeager Lease day '.$day_number;             
		add_filter('wp_mail_content_type',create_function('', 'return "text/html"; '));
		$message = 'HTML messege from Yeager lease<br/>'; 
		wp_mail( $to, $subject, $message );		
	}else{
		$to = 'anasbinmukim@gmail.com';
		$subject = 'Yeager Lease day number not found';             
		add_filter('wp_mail_content_type',create_function('', 'return "text/html"; '));
		$message = 'HTML messege from Yeager lease<br/>'; 
		wp_mail( $to, $subject, $message );		
	}*/
	
}


//Send email to client with attach invoice PDF. 
function yl_generate_invoice_sent_to_client($client_id){

	if($client_id > 0){
		$client_info = get_userdata($client_id);
		$client_name = $client_info->user_login;
		$client_display_name = $client_info->display_name;
		$client_email = $client_info->user_email;
			
		$email_subject = get_option('invoice_email_subject');	
		$pdf_file = generate_invoice_pdf( $client_id );
			
		//process email message
		$email_message = get_option('invoice_email_message');			
		$search = array();
		$replace = array();	
		$search[] = '%%client-name%%';
		$replace[] = $client_display_name;
		$get_message = str_replace($search, $replace, $email_message);	
		$get_message = 	stripslashes($get_message);
		$get_message = nl2br($get_message);
	
		$headers  = 'MIME-Version: 1.0' . "\r\n";
		$headers .= 'Content-type: text/plain; charset=iso-8859-1' . "\r\n";
		add_filter('wp_mail_content_type',create_function('', 'return "text/html"; '));			
		@wp_mail( $client_email, $email_subject, $get_message, $headers, array($pdf_file) );
		//@wp_mail( 'azad.rmweblab@gmail.com', $email_subject, $get_message, $headers, array($pdf_file) );
		unlink($pdf_file);
		return true;
	}else{
		return false;
	}

}

//Send email to All clients with attach invoice PDF. 
function yl_generated_invoice_sent_to_all_clients(){
	$user_query = new WP_User_Query( array( 'role' => 'lease_client' ) );
	// User Loop
	if ( ! empty( $user_query->results ) ) {
		foreach ( $user_query->results as $user ) {
			yl_generate_invoice_sent_to_client($user->ID);
		}
	}			

}

function output_invoice_pdf() {
	if( isset($_GET['invoice_pdf']) && ($_GET['invoice_pdf'] == 'do') ) {
		generate_invoice_pdf();
	}
	
	/*$post_type = 'shop_subscription';
	$post = get_post($post_type);
	get_post_meta($post->ID, '_customer_user', true);
	get_post_meta($post->ID, '_order_total', true);
	get_post_meta($post->ID, '_schedule_next_payment', true);
	get_post_meta($post->ID, '_order_key', true);*/

	/*'meta_query'     => array(
		array(
			'key'     => '_customer_user',
			'compare' => '=',
			'value'   => $user_id,
			'type'    => 'numeric',
		),
	),*/
}
add_action('init', 'output_invoice_pdf');

// Generate invoice PDF
function generate_invoice_pdf( $user_id ) {
	$pdf = new PDF();
	$pdf->AddPage();
	$pdf->SetFont('Arial','',12);

	$pdf->Cell(100,6,'Yeager of Frisco, LLC',0,0,'L');
	$pdf->SetFont('Arial','B',20);
	$pdf->Cell(90,6,'Invoice',0,0,'R');
	$pdf->Ln();
	$pdf->SetFont('Arial','',12);
	$pdf->Cell(100,6,'2770 Main Street',0,0,'L');
	$pdf->SetFont('Arial','',11);
	$pdf->Cell(30,6,'',0,0,'C');
	$pdf->Cell(30,6,'Date',1,0,'C');
	$pdf->Cell(30,6,'Invoice #',1,0,'C');
	$pdf->Ln();
	$pdf->SetFont('Arial','',12);
	$pdf->Cell(100,6,'Frisco, TX 75033',0,0,'L');
	$pdf->SetFont('Arial','',11);
	$pdf->Cell(30,6,'',0,0,'C');
	$pdf->Cell(30,6,'2/1/2016',1,0,'C');
	$pdf->Cell(30,6,'2574',1,0,'C');
	$pdf->Ln();
	$pdf->Cell(100,20,'',0,0,'C');
	$pdf->Ln();

	$pdf->Cell(100,6,'Bill To',1,0,'L');
	$pdf->Ln();
	$pdf->MultiCell(100,6,"Cedar Waters Custom Software\nJeff VanDrimmelen \n637 Calliopsis St \nLittle Elm, TX 75068",1,'L');
	$pdf->Ln();
	$pdf->Cell(100,20,'',0,0,'C');
	$pdf->Ln();

	$pdf->Cell(135,6,'',0,0,'C');
	$pdf->Cell(50,6,'Terms',1,0,'C');
	$pdf->Ln();
	$pdf->Cell(135,6,'',0,0,'C');
	$pdf->Cell(50,10,'Due 1st day of the Month',1,0,'C');
	$pdf->Ln();

	$html = '<table border="1" cellpadding="10" cellspacing="10">
				<tr>
					<td width="190"> Item</td>
					<td width="190"> Description</td>
					<td width="190"> Suite #</td>
					<td width="190"> Amount</td>
				</tr>';			
			
	$user_id = 2;
	$total_amount = 0;
	$subscriptions = WC_Subscriptions_Manager::get_users_subscriptions( $user_id );
	foreach($subscriptions as $subscrip_data){
		$order_id = $subscrip_data['order_id'];
		$product_id = $subscrip_data['product_id'];
		$variation_id = $subscrip_data['variation_id'];
		$period = $subscrip_data['period'];
		$interval = $subscrip_data['interval'];		
		$start_date = $subscrip_data['start_date'];
		$expiry_date = $subscrip_data['expiry_date'];
		$end_date = $subscrip_data['end_date'];
		$last_payment_date = $subscrip_data['last_payment_date'];
		
		$status = $subscrip_data['status'];
		if($status == 'active'){
			$product_name = get_the_title($product_id);
			$room_number = get_post_meta($product_id, '_yl_room_number', true);
			//echo "<br />";
			$subscription_amount = WC_Subscriptions_Order::get_price_per_period( $order_id, $product_id );
			$total_amount += $subscription_amount;
			
			$html .= '<tr>
							<td width="190" height="100"> '.$product_name.'</td>
							<td width="190" height="100"> Rent</td>
							<td width="190" height="100"> '.$room_number.'</td>
							<td width="190" height="100"> $'.$subscription_amount.'</td>
						</tr>';				
		
		}//eof active subscription	
		
	}	
						
	$html .= '</table>';
	
	$pdf->WriteHTML($html);
	
	$total_amount_output = ' Total $'. $total_amount;
	
	$payments_credits = '';
	//$payments_credits = ' Payments/Credits $'. $payments_credits;
	
	$balance_due = $total_amount;
	$balance_due = ' Balance Due $'. $balance_due;	
	
	$customer_total_balance = 2;
	$customer_total_balance = ' Customer Total Balance $'. $customer_total_balance;				

	$pdf->Cell(125,12,'',1,0,'C');
	$pdf->Cell(65,8,$total_amount_output,1,0,'L');
	$pdf->Ln();
	$current_y = $pdf->GetY();
	$current_x = $pdf->GetX();
	$pdf->SetXY($current_x, $current_y+4);
	$pdf->Cell(125,12,'Pay online at: https://ipn.intuit.com/83p7qct6',1,0,'C',false,'https://ipn.intuit.com/83p7qct6');
	$current_y = $pdf->GetY();
	$current_x = $pdf->GetX();
	$pdf->SetXY($current_x, $current_y-4);
	$pdf->Cell(65,8,$payments_credits,1,0,'L');
	$pdf->Ln();
	$pdf->Cell(125,12,'',0,0,'C');
	$pdf->Cell(65,8,$balance_due,1,0,'L');
	$pdf->Ln();

	$pdf->Cell(110,8,'',0,0,'C');
	//$pdf->Cell(80,8,$customer_total_balance,0,0,'L');

	//$pdf->Output('invoice.pdf', 'D');
	$dir = "/lease-pdf";
	$upload_dir = wp_upload_dir();
	$lease_pdf_dir = $upload_dir['basedir'].$dir;
	$lease_pdf_url = $upload_dir['baseurl'].$dir;
	if( ! file_exists( $lease_pdf_dir ) ) {
		wp_mkdir_p( $lease_pdf_dir );
	}
	//$lease_title = strtolower(str_replace(" ", "-", get_the_title($lease_id))).'-'.date("Y-m-d_H-i-s");

	//if( ! file_exists( $lease_pdf_dir.'/invoice.pdf' ) ) {
		$pdf->Output($lease_pdf_dir.'/invoice.pdf', 'F');
	//}


	return $lease_pdf_dir.'/invoice.pdf';
		
}