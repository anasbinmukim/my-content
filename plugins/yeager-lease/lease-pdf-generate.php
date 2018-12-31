<?php
// Extend the TCPDF class to create custom Header and Footer
class MYPDF extends TCPDF {

    //Page header
    public function Header() {
        // Logo
        $image_file = YL_URL.'images/yeager-logo.jpg';
        $this->Image($image_file, 16, 8, 50, '', 'JPG', '', 'T', false, 300, '', false, false, 0, false, false, false);
        // Set font
        $this->SetFont('helvetica', 'B', 20);
        // Title
		$this->setCellMargins(0, 5, 0, 0);
        $this->Cell(0, 20, 'TENANT INFORMATION', 0, 1, 'C', 0, '', 0, false, 'M', 'M');
        $this->SetY(22);
		$this->SetFillColor(0, 99, 100, 0);
		$this->SetTextColor(0, 99, 100, 0);
        $this->SetFont('helvetica', '', 5);
		$this->setCellMargins(0, 0, 0, 0);
        $this->Cell(0, 0.2, '&nbsp;', 0, 1, 'C', true, '', 0, false, 'M', 'M');
    }

    // Page footer
    public function Footer() {
        // Position at 15 mm from bottom
        $this->SetY(-22);
        /*$this->SetFont('helvetica', 'I', 8);
        $this->Cell(0, 10, 'Page '.$this->getAliasNumPage().'/'.$this->getAliasNbPages(), 0, false, 'C', 0, '', 0, false, 'T', 'M');*/

		$this->SetFillColor(0, 99, 100, 0);
		$this->SetTextColor(0, 99, 100, 0);
        $this->SetFont('helvetica', '', 5);
        $this->Cell(0, 0.2, '&nbsp;', 0, 1, 'C', true, '', 0, false, 'M', 'M');
        // Logo
        $image_file = YL_URL.'images/yeager-logo.jpg';
        $this->Image($image_file, 16, 278, 50, '', 'JPG', '', 'T', false, 300, '', false, false, 0, false, false, false);
        // Set font
        $this->SetFont('helvetica', 'B', 10);
		$this->SetTextColor(72, 66, 65, 74);
		$txt = "Yeager Properties | Yeager Construction 23 S. 8th Street, Noblesville, IN, 46060 Main: 317- Main: 317-770-7380 | Fax: 317 7380 | Fax: 317 7380 | Fax: 317-776-1867 yeagerproperties.com";
		$this->setCellPaddings(3, 0, 0, 0);
		$this->setCellMargins(5, 0, 0, 0);
		$this->MultiCell(120, 5, $txt, 'L', 'L', false, 0, '', '', true);
    }
}
function yl_generate_tenant_info_pdf($lease_id) {
	$pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
	//$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE.' 001', PDF_HEADER_STRING, array(0,64,255), array(0,64,128));
	$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE, PDF_HEADER_STRING, array(0,64,255), array(0,64,128));
	$pdf->setFooterData(array(0,64,0), array(0,64,128));

	// set margins
	$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
	$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
	$pdf->SetFooterMargin(25);
	
	// set auto page breaks
	$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

	$pdf->AddPage();
	//$pdf->SetFont('Arial','',10);

	if( get_post_meta($lease_id, '_yl_client_pw_signature', true) ) {
		$client_pw_signature = '<img src="'.get_post_meta($lease_id, '_yl_client_pw_signature', true).'" width="100" />';
	} else {
		$client_pw_signature = '';
	}

	//$logo = '<img src="'.YL_URL.'images/logo.jpg" width="210" />';
	$logo = '<img src="'.YL_URL.'images/yeager-logo.jpg" width="200" />';
	
	$html = '<table border="0" cellpadding="10" cellspacing="10">
	<tr>
		<td>
			<strong><u>LOCATIONS:</u></strong><br>
			Carmel Indiana: 600 E Carmel Dr. / 317-819-8500<br>
			Fishers Indiana: 11650 N Lantern Dr. / 317-576-8560<br>
			II Fishers Indiana: 14074 Trade Center Dr. / 317-774-2000<br>
			Fort Harrison (Lawrence/Indianapolis) Indiana: 9165 Otis Ave. / 317-532-9500<br>
			Greenwood Indiana: 3209 W Smith Valley Rd. / 317-888-5900<br>
			Noblesville Indiana: 23 S 8th St. / 317-774-1958<br>
			Plainfield Indiana: 2680 E Main St. / 317-837-7600<br>
			Frisco Texas: 2770 Main St. / 214-872-3535<br>
			McKinney Texas: 6401 Eldorado Pkwy. / 214-620-2020<br>
			Plano Texas: 8105 Rasor Blvd. / 844-631-8685<br>
			<br>
			<strong><u>BUILDING HOURS:</u></strong><br>
			- Building Manager available M-F: 9am - noon, 1pm - 5pm<br>
			- Front door unlocked M-F: 6:30am-7:00pm; Sat: 6:30am - 2pm<br>
			- Tenant Key fob access: 24/7 (key fobs operate all buildings)<br>
			<br>

			<strong><u>CONFERENCE ROOMS:</u></strong><br>
			- Carmel Indiana: Aqua*- seats 20, Fashions*- seats 6, Nations*-seats 6 , #4 - seats 6<br>
			- Fishers Indiana: #1* - seats 20, #2* - seats 8<br>
			- II Fishers Indiana: #1* - seats 20, #2* - seats 8, #3 - seats 8<br>
			- Ft. Harrison Indiana: Faces - seats 20, Passport - seats 8, Trees - seats 6, Giraffe - seats 4<br>
			- Greenwood Indiana: #1* - seats 20, #2 - seats 8, #3 - seats 6<br>
			- Noblesville Indiana: #1* - seats 8<br>
			- Plainfield Indiana: #1* - seats 20, Euro* - seats 6, Bamboo* - seats 6, #4 - seats 4<br>
			- McKinney Texas: Aqua* - seats 20, Lonestar* - seats 6, Overlook* - seats 4<br>
			- Frisco Texas: Aqua* - seats 20, #2* - seats 8, Overlook - seats* 6, #4 - seats 6<br>
			- Plano Texas: #1 - seats 40, #2 - seats 15, #3 - seats 5, #4 - seats 6, #5 - seats 6<br>
			Available 24/7 (Please observe maximum-use rules, and leave room clean and tidy after use.)<br>
			Reserve online at yeagerproperties.com with tenant password<br>
			* designates large monitor hookup and internet available<br>
			<br>

			<strong><u>FEE AMENITIES:</u></strong><br>
			Copier: 7 cents/copy with tenant password<br>
			Postage: Please see building manager for details<br>
			Fax: 50 cents/page<br>
			Phones: Please see your building manager<br>
			Additional Internet provisions: Static IPs and other options are available<br>
			Keys & Fobs: One building fob, suite, and mailbox key is provided at lease start<br>
			<br>

			<strong><u>MISCELLANEOUS:</u></strong><br>
			Deliveries: Building manager signs for incoming tenant packages with tenant authorization<br>
			Signs: Require Lessor approval for interior window signs and hall placards; exterior signs are prohibited<br>
			Internet: One jack per suite is included; wireless access is available.<br>
			Electrical: One 20A circuit is provided per suite<br>
			Maintenance: Please report any maintenance items in your suite or common areas to the building manager<br>
			Janitorial: Please report janitorial deficiencies to the building manager<br>
			Trash: Please place office trash in common dumpster. Please break down boxes. Please do not dump personal items in<br>
			dumpster, or office items in common trash or hallways. Please dump office trash frequently.<br>
			<br>

			<strong><u>PAYMENT OPTIONS:</u></strong><br>
			Pay online - monthly invoice email with online payment options. (Add a 3.25% fee for credit card payments)<br>
			Pay onsite or via mail - check, money orders, or cash (requires a receipt)<br>
			<br>

			<strong><u>IMPORTANT NUMBERS:</u></strong><br>
			Public Emergencies - fire, assault, burglary, vandalism, suspicious persons: 911<br>
			After hours non-emergency reporting : Please leave a VM at the Front Desk Main Number<br>
			Tenant Emergencies such as lockouts: a $100/hr. fee, minimum of 1 hr. Carmel: 819-8502 / <br>Greenwood: 884-3108 / Fishers and Ft.<br>
			Harrison: 576-8561 II Fishers : 774-2007 / McKinney and Frisco: (214)620-2022 / Noblesville: 716-4721 / <br>Office Suites West: 837-4919<br>
			<br>

			<strong><u>PASSWORDS:</u></strong><br>
			Copy Machine: '.get_post_meta($lease_id, '_yl_tinfo_copy_machine' , true).'<br>
			Website Conference Room Log-in - Meeting Calendar<br>
			        User Name: '.get_post_meta($lease_id, '_yl_tinfo_user_name' , true).'<br>
			        Password: '.get_post_meta($lease_id, '_yl_tinfo_password' , true).'<br>
			Postage Machine<br>
			        Password: '.get_post_meta($lease_id, '_yl_tinfo_postage_password' , true).'<br>
			        Account #: '.get_post_meta($lease_id, '_yl_tinfo_account_number' , true).'<br>

			<br>

			<strong><u>Fobs:</u></strong><br>
			Name: '.get_post_meta($lease_id, '_yl_tinfo_fob_1_name' , true).' #(s) '.get_post_meta($lease_id, '_yl_tinfo_fob_1_no' , true).'<br>
			Name: '.get_post_meta($lease_id, '_yl_tinfo_fob_2_name' , true).' #(s) '.get_post_meta($lease_id, '_yl_tinfo_fob_2_no' , true).'<br>
			Name: '.get_post_meta($lease_id, '_yl_tinfo_fob_3_name' , true).' #(s) '.get_post_meta($lease_id, '_yl_tinfo_fob_3_no' , true).'<br>

			<br>

			<strong><u>CONTACT INFO:</u></strong><br>
			Email: '.get_post_meta($lease_id, '_yl_tinfo_email' , true).'<br>
			Name/Phone: '.get_post_meta($lease_id, '_yl_tinfo_name_nhone' , true).'<br>
			Emergency Contact (Name & Phone): '.get_post_meta($lease_id, '_yl_tinfo_emergency_contact' , true).'<br>
			Tenant/Corporate Address: '.get_post_meta($lease_id, '_yl_tinfo_corporate_address' , true).'<br>
			Billing/Corporate Contact/Phone: '.get_post_meta($lease_id, '_yl_tinfo_billing_contact' , true).'<br>

			<br>

			<strong><u>WIFI CODES:</u></strong> livelife<br>

			<br>

			<strong><u>BUILDING TENANT DIRECTORY:</u></strong><br>
			Name as you wish it to appear: '.get_post_meta($lease_id, '_yl_tinfo_name_as_you_wish' , true).' (Recommend less than 20
characters; 50 maximum) Suite #(s): '.get_post_meta($lease_id, '_yl_tinfo_suite_numbers' , true).'<br>

			<br>

			<strong><u>PACKAGE DELIVERY WAIVER:</u></strong><br>
			I authorize representatives of Yeager Properties to receive package deliveries for suite(s) '.get_post_meta($lease_id, '_yl_suite_number', true).'.<br>
			I am an authorized representative for Lessee. I release Yeager Properties from all liability related to package delivery loss and damage.<br><br>
			How did you first find out about us? '.get_post_meta($lease_id, '_yl_tinfo_first_find_out_about_us' , true).'<br>
			What is the main reason you chose Yeager Office Suites? '.get_post_meta($lease_id, '_yl_tinfo_main_reason_you_chose' , true).'<br>
		</td>
	</tr>';


		$html .= '<tr>
			<td width="266" height="35"><u>Authorized Representative #1</u><br />'.get_post_meta($lease_id, '_yl_tinfo_auth_representative_1', true).'<br /><img src="'.get_post_meta($lease_id, '_yl_client_pw_signature', true).'" /><br />Date: '.get_post_meta($lease_id, '_yl_tinfo_date_1' , true).'</td>
			<td width="266" height="35"><u>Authorized Representative #2</u><br />'.get_post_meta($lease_id, '_yl_tinfo_auth_representative_2', true).'<br /><img src="'.get_post_meta($lease_id, '_yl_client_pw_signature_2', true).'" /><br />Date: '.get_post_meta($lease_id, '_yl_tinfo_date_2' , true).'</td>
		</tr>';

	$html .= '</table>';
	
	// output the HTML content
	$pdf->writeHTML($html, true, false, true, false, '');

	$dir = "/lease-pdf";
	$upload_dir = wp_upload_dir();
	$lease_pdf_dir = $upload_dir['basedir'].$dir;
	$lease_pdf_url = $upload_dir['baseurl'].$dir;
	if( ! file_exists( $lease_pdf_dir ) ){
		wp_mkdir_p( $lease_pdf_dir );
	}
	$lease_title = yl_seo_friendly_filename(strtolower(str_replace(" ", "-", get_the_title($lease_id))).'-tenant-info-'.date("Y-m-d_H-i-s"));

	if( ! file_exists( $lease_pdf_dir.'/'.$lease_title.'.pdf' ) ) {
		$pdf->Output($lease_pdf_dir.'/'.$lease_title.'.pdf', 'F');
	} else {
		unlink($lease_pdf_dir.'/'.$lease_title.'.pdf');
		$pdf->Output($lease_pdf_dir.'/'.$lease_title.'.pdf', 'F');
	}
	
	update_post_meta($lease_id, '_yl_tenant_info_pdf', $lease_pdf_url.'/'.$lease_title.'.pdf');
	
	$tenent_pdf_file = $lease_pdf_dir.'/'.$lease_title.'.pdf';
	
//	$email_subject = get_option('report_email_subject');		
//	//process email message
//	$email_message = get_option('report_email_message');			
//	$search = array();
//	$replace = array();	
//	$search[] = '%%USER%%';
//	$replace[] = '';
//	$get_message = str_replace($search, $replace, $email_message);	
//	$get_message = 	stripslashes($get_message);
//	$get_message = nl2br($get_message);

	$email_subject = 'Yeager Lease / Tenant Info';
	$get_message = 'Please see attach PDF file.';
	
	$headers  = 'MIME-Version: 1.0' . "\r\n";
	$headers .= 'Content-type: text/plain; charset=iso-8859-1' . "\r\n";
	add_filter('wp_mail_content_type',create_function('', 'return "text/html"; '));	

	//@wp_mail( $receiver_email, $email_subject, $get_message, $headers, array($report_file) );	
	
	$lease_user_id = get_post_meta($lease_id, '_yl_lease_user', true);
	$lease_user_info = get_userdata($lease_user_id);
	$lease_first_name = $lease_user_info->first_name;
	$lease_last_name = $lease_user_info->last_name;	
	$lease_user_email = $lease_user_info->user_email;	
	
	$lease_bm_user_id = get_post_meta($lease_id, '_yl_author_id', true);
	$lease_bm_user_info = get_userdata($lease_bm_user_id);
	$lease_bm_first_name = $lease_bm_user_info->first_name;
	$lease_bm_last_name = $lease_bm_user_info->last_name;	
	$lease_bm_user_email = $lease_bm_user_info->user_email;	
	
	$lease_pdf_file_url = get_post_meta($lease_id, '_yl_lease_pdf', true);
	$lease_pdf_file = str_replace($lease_pdf_url, $lease_pdf_dir, $lease_pdf_file_url);	

	$lease_summary_pdf_file_url = get_post_meta($lease_id, '_yl_lease_summary_pdf', true);
	$lease_summary_pdf_file = str_replace($lease_pdf_url, $lease_pdf_dir, $lease_summary_pdf_file_url);	
	
	
//	$get_message .= '<br />Client Email: '.$lease_user_email;	
//	$get_message .= '<br />BM Email: '.$lease_bm_user_email;	
	
	
	//@wp_mail( $lease_user_email, $email_subject, $get_message, $headers, array($tenent_pdf_file, $lease_pdf_file) );
	
	//@wp_mail( $lease_bm_user_email, $email_subject, $get_message, $headers, array($tenent_pdf_file, $lease_pdf_file) );		
	
	//$receiver_email = 'anasbinmukim@gmail.com';
	//$receiver_email = 'brad@cedarwaters.com';
	//@wp_mail( $receiver_email, $email_subject, $get_message, $headers, array($tenent_pdf_file, $lease_pdf_file) );
	
	
	
}


function yl_generate_summary_pdf($lease_id) {
	$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

	// remove default header/footer
	$pdf->setPrintHeader(false);
	$pdf->setPrintFooter(false);

	// set auto page breaks
	$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

	$pdf->AddPage();

	$pdf->SetFont('Helvetica','',10);

	
	if( get_post_meta($lease_id, '_yl_bm_ls_signature', true) ) {
		$bm_signature = '<img src="'.get_post_meta($lease_id, '_yl_bm_ls_signature', true).'" width="300" />';
	} else {
		$bm_signature = '';
	}

	if( get_post_meta($lease_id, '_yl_client_ls_signature', true) ) {
		$client_signature = '<img src="'.get_post_meta($lease_id, '_yl_client_ls_signature', true).'" width="300" />';
	} else {
		$client_signature = '';
	}

	if( get_post_meta($lease_id, '_yl_client_pw_signature', true) ) {
		$pw_signature = '<img src="'.get_post_meta($lease_id, '_yl_client_pw_signature', true).'" width="300" />';
	} else {
		$pw_signature = '';
	}
	
	
	if( get_post_meta($lease_id, '_yl_l_address_line_2', true) ) {
		$street2 = ', '.get_post_meta($lease_id, '_yl_l_address_line_2', true);
	} else {
		$street2 = '';
	}

	if( get_post_meta($lease_id, '_yl_g_address_line_2', true) ) {
		$g_street2 = ', '.get_post_meta($lease_id, '_yl_g_address_line_2', true);
	} else {
		$g_street2 = '';
	}
	
	if( get_post_meta($lease_id, '_yl_company_type', true) ) {
		//$c_term = get_term( get_post_meta($lease_id, '_yl_company_type', true), 'companytype' );
		$c_term = get_term_by('id', get_post_meta($lease_id, '_yl_company_type', true), 'companytype');
		$c_type = $c_term->name;
	} else {
		$c_type = '';
	}
		
	$logo = '<img src="'.YL_URL.'images/logo.jpg" width="210" />';
	
	$html = '<table border="0" cellpadding="10" cellspacing="10">
	<tr>
		<td width="532" height="58" colspan="3" align="center">'.$logo.'</td>
	</tr>	
	<tr>
		<td width="172">Lessor: '.get_post_meta($lease_id, '_yl_lessor', true).'</td>
		<td width="180">Location: '.get_post_meta($lease_id, '_yl_location', true).'</td>
		<td width="180">Location Phone Number: '.get_post_meta($lease_id, '_yl_location_phone_number', true).'</td>
	</tr>
	<tr>
		<td width="266" height="35"><strong>Lessee</strong></td>
		<td width="266" height="35">&nbsp;</td>
	</tr>
	<tr>
		<td width="266">Name: '.get_post_meta($lease_id, '_yl_l_first_name', true).' '.get_post_meta($lease_id, '_yl_l_middle_name', true).' '.get_post_meta($lease_id, '_yl_l_last_name', true).'</td>
		<td width="266">Company Name: '.get_the_title(get_post_meta($lease_id, '_yl_company_name', true)).'</td>
	</tr>
	<tr>
		<td width="266">Phone Number: '.get_post_meta($lease_id, '_yl_l_phone', true).'</td>
		<td width="266">Company Type: '.$c_type.'</td>
	</tr>
	<tr>
		<td width="266">Email: '.get_post_meta($lease_id, '_yl_l_email', true).'</td>
		<td width="266">Suite Number: '.get_post_meta($lease_id, '_yl_suite_number', true).'</td>
	</tr>
	<tr>
		<td width="266" valign="top">Address: '.get_post_meta($lease_id, '_yl_l_street_address', true).$street2.', '.get_post_meta($lease_id, '_yl_l_city', true).', '.get_post_meta($lease_id, '_yl_l_state', true).', '.get_post_meta($lease_id, '_yl_l_zip_code', true).'</td>
		<td width="266">Lease Start Date: '.get_post_meta($lease_id, '_yl_lease_start_date', true).'</td>
	</tr>
	<tr>
		<td width="266"><strong>Guarantor</strong></td>
		<td width="266">&nbsp;</td>
	</tr>
	<tr>
		<td width="266">Name: '.get_post_meta($lease_id, '_yl_g_first_name', true).'</td>
		<td width="266">First Month Rent Rate: '.get_post_meta($lease_id, '_yl_first_month_rent_rate', true).'</td>
	</tr>
	<tr>
		<td width="266">Phone Number: '.get_post_meta($lease_id, '_yl_g_phone', true).'</td>
		<td width="266">Monthly Recurring Rent Rate: '.get_post_meta($lease_id, '_yl_monthly_rent', true).'</td>
	</tr>
	<tr>
		<td width="266">Email: '.get_post_meta($lease_id, '_yl_g_email', true).'</td>
		<td width="266">Security Deposit: '.get_post_meta($lease_id, '_yl_security_deposit', true).'</td>
	</tr>
	<tr>
		<td width="266" valign="top">Address: '.get_post_meta($lease_id, '_yl_g_street_address', true).$g_street2.', '.get_post_meta($lease_id, '_yl_g_city', true).', '.get_post_meta($lease_id, '_yl_g_state', true).', '.get_post_meta($lease_id, '_yl_g_zip_code', true).'</td>
		<td width="266">Vacate Notice: '.get_post_meta($lease_id, '_yl_vacate_notice', true).'</td>
	</tr>
	<tr>
		<td width="266">&nbsp;</td>
		<td width="266">Promotional Code: '.get_post_meta($lease_id, '_yl_promotional_code', true).'</td>
	</tr>
	<tr>
		<td width="266">&nbsp;</td>
		<td width="266">Service Fees: '.get_post_meta($lease_id, '_yl_service_fees', true).'</td>
	</tr>
	<tr>
		<td width="266">&nbsp;</td>
		<td width="266">Multi Suite Discount: '.get_post_meta($lease_id, '_yl_multi_suite_discount', true).'</td>
	</tr>
	<tr>
		<td width="266">&nbsp;</td>
		<td width="266">Addendums: '.get_post_meta($lease_id, '_yl_addendums', true).'</td>
	</tr>
	<tr>
		<td width="266" height="50">&nbsp;</td>
		<td width="266" height="50">&nbsp;</td>
	</tr>
	<tr>
		<td width="266" height="55">Date: '.get_post_meta($lease_id, '_yl_client_ls_signature_date', true).'</td>
		<td width="266" height="55">-</td>
		<td width="266" height="55">Date: '.get_post_meta($lease_id, '_yl_bm_ls_signature_date', true).'</td>
	</tr>
	<tr>
		<td width="180">Signature (Client): </td>
		<td width="172">Package Waiver Sign. (Client): </td>
		<td width="180">Signature (BM): </td>
	</tr>
	<tr>
		<td width="180">'.$client_signature.'</td>
		<td width="172">'.$pw_signature.'</td>
		<td width="180">'.$bm_signature.'</td>
	</tr>	
	</table>';
	
	$pdf->WriteHTML($html);	

	$dir = "/lease-pdf";
	$upload_dir = wp_upload_dir();
	$lease_pdf_dir = $upload_dir['basedir'].$dir;
	$lease_pdf_url = $upload_dir['baseurl'].$dir;
	if( ! file_exists( $lease_pdf_dir ) ){
		wp_mkdir_p( $lease_pdf_dir );
	}
	$lease_title = yl_seo_friendly_filename(strtolower(str_replace(" ", "-", get_the_title($lease_id))).'-summary-'.date("Y-m-d_H-i-s"));

	if( ! file_exists( $lease_pdf_dir.'/'.$lease_title.'.pdf' ) ) {
		$pdf->Output($lease_pdf_dir.'/'.$lease_title.'.pdf', 'F');
	} else {
		unlink($lease_pdf_dir.'/'.$lease_title.'.pdf');
		$pdf->Output($lease_pdf_dir.'/'.$lease_title.'.pdf', 'F');
	}
	
	update_post_meta($lease_id, '_yl_lease_summary_pdf', $lease_pdf_url.'/'.$lease_title.'.pdf');
}

function generate_lease_pdf() {
	if( isset($_GET['lease_pdf']) ) {
		//yl_generate_pdf($_GET['lease_pdf']);
		//yl_generate_summary_pdf($_GET['lease_pdf']);
		yl_generate_complete_lease_pdf($_GET['lease_pdf']);
	}
}
add_action('init', 'generate_lease_pdf');

function download_complete_lease_pdf() {
	if( isset($_GET['download_lease']) ) {
		$request_lease_id = $_GET['download_lease'];
		yl_generate_complete_lease_pdf($request_lease_id);		
		$request_lease_file = get_post_meta($request_lease_id, '_yl_full_lease_pdf', true);
		
		$dir = "/lease-pdf";
		$upload_dir = wp_upload_dir();
		$lease_pdf_dir = $upload_dir['basedir'].$dir;
		$lease_pdf_url = $upload_dir['baseurl'].$dir;		
		$request_lease_to_donwload = str_replace($lease_pdf_url, $lease_pdf_dir, $request_lease_file);
		
		if(!is_file($request_lease_to_donwload)) {
			echo 'FIle not found.('.$request_lease_to_donwload.')';
		} elseif (is_dir($request_lease_to_donwload)) {
			echo "Cannto Download folder.";
		} else {
			yeager_send_download($request_lease_to_donwload);
		}
		
	}
}
add_action('init', 'download_complete_lease_pdf');

function yeager_send_download($file) {
	$basename	= basename($file);
	$length = sprintf("%u", filesize($file));
	set_time_limit(0);
	header('Content-Description: File Transfer');
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename="' . $basename . '"');
    header('Content-Transfer-Encoding: binary');
    header('Connection: Keep-Alive');
    header('Expires: 0');
    header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
    header('Pragma: public');
    header('Content-Length: ' . $length);
    ob_clean();
    flush();
    readfile($file);
}

function yl_generate_pdf($lease_id) {
	$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

	// remove default header/footer
	$pdf->setPrintHeader(false);
	$pdf->setPrintFooter(false);

	// set auto page breaks
	$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

	$pdf->AddPage();

	$pdf->SetFont('Helvetica','',10);
	
	if( get_post_meta($lease_id, '_yl_bm_signature', true) ) {
		$bm_signature = '<img src="'.get_post_meta($lease_id, '_yl_bm_signature', true).'" width="300" />';
	} else {
		$bm_signature = '';
	}

	if( get_post_meta($lease_id, '_yl_client_signature', true) ) {
		$client_signature = '<img src="'.get_post_meta($lease_id, '_yl_client_signature', true).'" width="300" />';
	} else {
		$client_signature = '';
	}
	
	if( get_post_meta($lease_id, '_yl_client_pw_signature', true) ) {
		$pw_signature = '<img src="'.get_post_meta($lease_id, '_yl_client_pw_signature', true).'" width="300" />';
	} else {
		$pw_signature = '';
	}

	$logo = '<img src="'.YL_URL.'images/logo.jpg" width="210" height="38" />';
	
	$html = '<table border="0" cellpadding="0" cellspacing="0" style="width:100%;">
	<tr>
		<td style="text-align: center;">'.$logo.'</td>
	</tr>
	</table>';
	
	$lease_product_id = get_post_meta($lease_id, '_yl_product_id', true);
	if ($lease_product_id == -1) {
		$lease_summary = stripslashes(get_option('ym_lease_summary'));
	}else{
		$lease_summary = stripslashes(get_option('lease_summary'));
	}	
	$search = array();
	$replace = array();	
	
	$search[] = '%%CompanyName%%';
	$replace[] = get_the_title(get_post_meta($lease_id, '_yl_company_name', true));
	
	$search[] = '%%LesseeGuarantorPersonalPhoneNumber%%';
	$replace[] = get_post_meta($lease_id, '_yl_g_phone', true);

	$search[] = '%%CompanyType%%';
	$c_term = get_term_by('id', get_post_meta($lease_id, '_yl_company_type', true), 'companytype');
	$replace[] = $c_term->name;

	$search[] = '%%Lessee%%';
	$lessee_full_name = get_post_meta($lease_id, '_yl_l_first_name', true);
	if(get_post_meta($lease_id, '_yl_l_middle_name', true))
		$lessee_full_name .= ' '. get_post_meta($lease_id, '_yl_l_middle_name', true);
	if(get_post_meta($lease_id, '_yl_l_last_name', true))
		$lessee_full_name .= ' '. get_post_meta($lease_id, '_yl_l_last_name', true);			
	$replace[] = $lessee_full_name;

	$search[] = '%%Guarantor%%';
	$guarantor_full_name = get_post_meta($lease_id, '_yl_g_first_name', true);
	if(get_post_meta($lease_id, '_yl_g_middle_name', true))
		$guarantor_full_name .= ' '. get_post_meta($lease_id, '_yl_g_middle_name', true);
	if(get_post_meta($lease_id, '_yl_g_last_name', true))
		$guarantor_full_name .= ' '. get_post_meta($lease_id, '_yl_g_last_name', true);			
	$replace[] = $guarantor_full_name;

	$search[] = '%%SuiteNo%%';
	$replace[] = get_post_meta($lease_id, '_yl_suite_number', true);

	$search[] = '%%FirstMonthRentRate%%';
	$replace[] = get_post_meta($lease_id, '_yl_first_month_rent_rate', true);

	$search[] = '%%RecurringRentRate%%';
	$replace[] = get_post_meta($lease_id, '_yl_monthly_rent', true);

	$search[] = '%%SecurityDeposit%%';
	$replace[] = get_post_meta($lease_id, '_yl_security_deposit', true);

	$search[] = '%%MoveinLeaseStartDate%%';
	$replace[] = get_post_meta($lease_id, '_yl_lease_start_date', true);

	$search[] = '%%LesseeGuarantorHomeAddress%%';
	if( get_post_meta($lease_id, '_yl_g_address_line_2', true) ) {
		$g_street2 = ', '.get_post_meta($lease_id, '_yl_g_address_line_2', true);
	} else {
		$g_street2 = '';
	}
	$g_address = get_post_meta($lease_id, '_yl_g_street_address', true).$g_street2.', '.get_post_meta($lease_id, '_yl_g_city', true).', '.get_post_meta($lease_id, '_yl_g_state', true).', '.get_post_meta($lease_id, '_yl_g_zip_code', true);
	$replace[] = $g_address;

	$search[] = '%%CurrentDate%%';
	$replace[] = date("Y-m-d");

	$search[] = '%%LesseeGuarantorEmail%%';
	$replace[] = get_post_meta($lease_id, '_yl_g_email', true);

	//$search[] = '%%ServiceCompanyCheckbox%%';
	//$replace[] = get_post_meta($lease_id, '_yl_g_email', true);

	$search[] = '%%Addendum%%';
	$replace[] = get_post_meta($lease_id, '_yl_addendums', true);

	$search[] = '%%PromoCode%%';
	$replace[] = get_post_meta($lease_id, '_yl_promotional_code', true);

	$search[] = '%%MultisuiteDiscountIfApplicable%%';
	$replace[] = get_post_meta($lease_id, '_yl_multi_suite_discount', true);

	$search[] = '%%Lessor%%';
	$replace[] = get_post_meta($lease_id, '_yl_lessor', true);

	$search[] = '%%Location%%';
	$replace[] = get_post_meta($lease_id, '_yl_location', true);

	$lease_post = get_post( $lease_id );
	$author_id = $lease_post->post_author;
	$user = get_user_by( 'id', $author_id );

	$search[] = '%%BuildingManager%%';
	$replace[] = $user->first_name;

	$search[] = '%%BuildingManagerEmail%%';
	$replace[] = $user->user_email;

	$search[] = '%%BuildingManagerPhoneNumber%%';
	$replace[] = get_user_meta( $author_id, 'billing_phone', true );

	$search[] = '%%LesseeHomeAddress%%';
	if( get_post_meta($lease_id, '_yl_l_address_line_2', true) ) {
		$l_street2 = ', '.get_post_meta($lease_id, '_yl_l_address_line_2', true);
	} else {
		$l_street2 = '';
	}
	$l_address = get_post_meta($lease_id, '_yl_l_street_address', true).$l_street2.', '.get_post_meta($lease_id, '_yl_l_city', true).', '.get_post_meta($lease_id, '_yl_l_state', true).', '.get_post_meta($lease_id, '_yl_l_zip_code', true);
	$replace[] = $l_address;

	$search[] = '%%LocationPhoneNumber%%';
	$replace[] = get_post_meta($lease_id, '_yl_location_phone_number', true);

	$search[] = '%%LeaseTerm%%';
	$replace[] = get_post_meta($lease_id, '_yl_lease_term', true);

	$search[] = '%%ServiceFees%%';
	$replace[] = get_post_meta($lease_id, '_yl_service_fees', true);

	$search[] = '%%DueAtSigning%%';
	$replace[] = get_post_meta($lease_id, '_yl_due_at_signing', true);

	$search[] = '%%IsLesseeGuarantor%%';
	$replace[] = get_post_meta($lease_id, '_yl_lessee_guarantor_same', true);

	$search[] = '%%LesseePhone%%';
	$replace[] = get_post_meta($lease_id, '_yl_l_phone', true);

	$search[] = '%%LesseeEmail%%';
	$replace[] = get_post_meta($lease_id, '_yl_l_email', true);

	$search[] = '%%LesseeSignature%%';
	$replace[] = '<img src="'.get_post_meta($lease_id, '_yl_client_signature', true).'" />';

	$search[] = '%%BuildingManagerSignature%%';
	$replace[] = '<img src="'.get_post_meta($lease_id, '_yl_bm_signature', true).'" />';

	$search[] = '%%LesseeSignatureDate%%';
	$replace[] = get_post_meta($lease_id, '_yl_client_signature_date', true);

	$search[] = '%%BuildingManagerSignatureDate%%';
	$replace[] = get_post_meta($lease_id, '_yl_bm_signature_date', true);

	$search[] = '%%LeasePDF%%';
	$replace[] = get_post_meta($lease_id, '_yl_lease_pdf', true);

	$search[] = '%%LeaseSummaryPDF%%';
	$replace[] = get_post_meta($lease_id, '_yl_lease_summary_pdf', true);
	
	$summary = str_replace($search, $replace, $lease_summary);	
	
	//$summary = stripslashes($summary);
	//$summary = nl2br($summary);
		
	$summary = apply_filters('the_content', $summary);

	$html .= $summary;
	
	$html .= '<table border="0" cellpadding="0" cellspacing="0">
				<tr>
					<td width="266" height="100">&nbsp;</td>
					<td width="266" height="100">&nbsp;</td>
				</tr>
				<tr>
					<td width="266" height="35">Date: '.get_post_meta($lease_id, '_yl_client_signature_date', true).'</td>
					<td width="266" height="35">Date: '.get_post_meta($lease_id, '_yl_bm_signature_date', true).'</td>
				</tr>
				<tr>
					<td width="266" height="50">Signature(Client): </td>
					<td width="266" height="50">Signature(BM): </td>
				</tr>
				<tr>
					<td width="266">'.$client_signature.'</td>
					<td width="266">'.$bm_signature.'</td>
				</tr>
				<tr>
					<td width="266" height="50">Package Waiver Sign. (Client): </td>
					<td width="266" height="50"></td>
				</tr>
				<tr>
					<td width="266">'.$pw_signature.'</td>
					<td width="266" height="55"></td>
				</tr>
			</table>';
	
	$pdf->WriteHTML($html);

	$dir = "/lease-pdf";
	$upload_dir = wp_upload_dir();
	$lease_pdf_dir = $upload_dir['basedir'].$dir;
	$lease_pdf_url = $upload_dir['baseurl'].$dir;
	if( ! file_exists( $lease_pdf_dir ) ){
		wp_mkdir_p( $lease_pdf_dir );
	}
	$lease_title = yl_seo_friendly_filename(strtolower(str_replace(" ", "-", get_the_title($lease_id))).'-'.date("Y-m-d_H-i-s"));

	if( ! file_exists( $lease_pdf_dir.'/'.$lease_title.'.pdf' ) ) {
		$pdf->Output($lease_pdf_dir.'/'.$lease_title.'.pdf', 'F');
	} else {
		unlink($lease_pdf_dir.'/'.$lease_title.'.pdf');
		$pdf->Output($lease_pdf_dir.'/'.$lease_title.'.pdf', 'F');
	}
	
	update_post_meta($lease_id, '_yl_lease_pdf', $lease_pdf_url.'/'.$lease_title.'.pdf');
	

	$email_subject = 'Yeager Lease / Tenant Info';
	$get_message = 'Please see attach PDF file.';
	
	$headers  = 'MIME-Version: 1.0' . "\r\n";
	$headers .= 'Content-type: text/plain; charset=iso-8859-1' . "\r\n";
	add_filter('wp_mail_content_type',create_function('', 'return "text/html"; '));	


	$lease_user_id = get_post_meta($lease_id, '_yl_lease_user', true);
	$lease_user_info = get_userdata($lease_user_id);
	$lease_first_name = $lease_user_info->first_name;
	$lease_last_name = $lease_user_info->last_name;	
	$lease_user_email = $lease_user_info->user_email;	
	
	$lease_bm_user_id = get_post_meta($lease_id, '_yl_author_id', true);
	$lease_bm_user_info = get_userdata($lease_bm_user_id);
	$lease_bm_first_name = $lease_bm_user_info->first_name;
	$lease_bm_last_name = $lease_bm_user_info->last_name;	
	$lease_bm_user_email = $lease_bm_user_info->user_email;	
	
	$tenant_pdf_file_url = get_post_meta($lease_id, '_yl_tenant_info_pdf', true);
	$tenant_pdf_file = str_replace($lease_pdf_url, $lease_pdf_dir, $tenant_pdf_file_url);	
	
	$lease_pdf_file_url = get_post_meta($lease_id, '_yl_lease_pdf', true);
	$lease_pdf_file = str_replace($lease_pdf_url, $lease_pdf_dir, $lease_pdf_file_url);		

	$lease_summary_pdf_file_url = get_post_meta($lease_id, '_yl_lease_summary_pdf', true);
	$lease_summary_pdf_file = str_replace($lease_pdf_url, $lease_pdf_dir, $lease_summary_pdf_file_url);	
	
	
//	$get_message .= '<br />Client Email: '.$lease_user_email;	
//	$get_message .= '<br />BM Email: '.$lease_bm_user_email;	
	
	
	@wp_mail( $lease_user_email, $email_subject, $get_message, $headers, array($tenant_pdf_file, $lease_pdf_file) );
	
	@wp_mail( $lease_bm_user_email, $email_subject, $get_message, $headers, array($tenant_pdf_file, $lease_pdf_file) );		
	
	//$receiver_email = 'anasbinmukim@gmail.com';
	//$receiver_email = 'brad@cedarwaters.com';
	//@wp_mail( $receiver_email, $email_subject, $get_message, $headers, array($tenant_pdf_file, $lease_pdf_file) );
	

	
}


function yl_generate_complete_lease_pdf($lease_id) {
	$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

	// remove default header/footer
	$pdf->setPrintHeader(false);
	$pdf->setPrintFooter(false);

	// set auto page breaks
	$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
	
	$html = '';
	
	$pdf->AddPage();

	$pdf->SetFont('Helvetica','',10);

	
	if( get_post_meta($lease_id, '_yl_bm_ls_signature', true) ) {
		$bm_signature = '<img src="'.get_post_meta($lease_id, '_yl_bm_ls_signature', true).'" width="300" />';
	} else {
		$bm_signature = '';
	}

	if( get_post_meta($lease_id, '_yl_client_ls_signature', true) ) {
		$client_signature = '<img src="'.get_post_meta($lease_id, '_yl_client_ls_signature', true).'" width="300" />';
	} else {
		$client_signature = '';
	}

	if( get_post_meta($lease_id, '_yl_client_pw_signature', true) ) {
		$pw_signature = '<img src="'.get_post_meta($lease_id, '_yl_client_pw_signature', true).'" width="300" />';
	} else {
		$pw_signature = '';
	}
	
	
	if( get_post_meta($lease_id, '_yl_l_address_line_2', true) ) {
		$street2 = ', '.get_post_meta($lease_id, '_yl_l_address_line_2', true);
	} else {
		$street2 = '';
	}

	if( get_post_meta($lease_id, '_yl_g_address_line_2', true) ) {
		$g_street2 = ', '.get_post_meta($lease_id, '_yl_g_address_line_2', true);
	} else {
		$g_street2 = '';
	}
	
	if( get_post_meta($lease_id, '_yl_company_type', true) ) {
		//$c_term = get_term( get_post_meta($lease_id, '_yl_company_type', true), 'companytype' );
		$c_term = get_term_by('id', get_post_meta($lease_id, '_yl_company_type', true), 'companytype');
		$c_type = $c_term->name;
	} else {
		$c_type = '';
	}
	
	$lessee_full_name = get_post_meta($lease_id, '_yl_l_first_name', true);
	if(get_post_meta($lease_id, '_yl_l_middle_name', true))
		$lessee_full_name .= ' '. get_post_meta($lease_id, '_yl_l_middle_name', true);
	if(get_post_meta($lease_id, '_yl_l_last_name', true))
		$lessee_full_name .= ' '. get_post_meta($lease_id, '_yl_l_last_name', true);			


	$guarantor_full_name = get_post_meta($lease_id, '_yl_g_first_name', true);
	if(get_post_meta($lease_id, '_yl_g_middle_name', true))
		$guarantor_full_name .= ' '. get_post_meta($lease_id, '_yl_g_middle_name', true);
	if(get_post_meta($lease_id, '_yl_g_last_name', true))
		$guarantor_full_name .= ' '. get_post_meta($lease_id, '_yl_g_last_name', true);
	
		
	$logo = '<img src="'.YL_URL.'images/logo.jpg" width="210" />';
	
	$html_lease_summary = '<table border="0" cellpadding="10" cellspacing="10">
	<tr>
		<td width="532" height="58" colspan="3" align="center">'.$logo.'</td>
	</tr>	
	<tr>
		<td width="172">Lessor: '.get_post_meta($lease_id, '_yl_lessor', true).'</td>
		<td width="180">Location: '.get_post_meta($lease_id, '_yl_location', true).'</td>
		<td width="180">Location Phone Number: '.get_post_meta($lease_id, '_yl_location_phone_number', true).'</td>
	</tr>
	<tr>
		<td width="266" height="35"><strong>Lessee</strong></td>
		<td width="266" height="35">&nbsp;</td>
	</tr>
	<tr>
		<td width="266">Name: '.$lessee_full_name.'</td>
		<td width="266">Company Name: '.get_the_title(get_post_meta($lease_id, '_yl_company_name', true)).'</td>
	</tr>
	<tr>
		<td width="266">Phone Number: '.get_post_meta($lease_id, '_yl_l_phone', true).'</td>
		<td width="266">Company Type: '.$c_type.'</td>
	</tr>
	<tr>
		<td width="266">Email: '.get_post_meta($lease_id, '_yl_l_email', true).'</td>
		<td width="266">Suite Number: '.get_post_meta($lease_id, '_yl_suite_number', true).'</td>
	</tr>
	<tr>
		<td width="266" valign="top">Address: '.get_post_meta($lease_id, '_yl_l_street_address', true).$street2.', '.get_post_meta($lease_id, '_yl_l_city', true).', '.get_post_meta($lease_id, '_yl_l_state', true).', '.get_post_meta($lease_id, '_yl_l_zip_code', true).'</td>
		<td width="266">Lease Start Date: '.get_post_meta($lease_id, '_yl_lease_start_date', true).'</td>
	</tr>
	<tr>
		<td width="266"><strong>Guarantor</strong></td>
		<td width="266">&nbsp;</td>
	</tr>
	<tr>
		<td width="266">Name: '.$guarantor_full_name.'</td>
		<td width="266">First Month Rent Rate: '.get_post_meta($lease_id, '_yl_first_month_rent_rate', true).'</td>
	</tr>
	<tr>
		<td width="266">Phone Number: '.get_post_meta($lease_id, '_yl_g_phone', true).'</td>
		<td width="266">Monthly Recurring Rent Rate: '.get_post_meta($lease_id, '_yl_monthly_rent', true).'</td>
	</tr>
	<tr>
		<td width="266">Email: '.get_post_meta($lease_id, '_yl_g_email', true).'</td>
		<td width="266">Security Deposit: '.get_post_meta($lease_id, '_yl_security_deposit', true).'</td>
	</tr>
	<tr>
		<td width="266" valign="top">Address: '.get_post_meta($lease_id, '_yl_g_street_address', true).$g_street2.', '.get_post_meta($lease_id, '_yl_g_city', true).', '.get_post_meta($lease_id, '_yl_g_state', true).', '.get_post_meta($lease_id, '_yl_g_zip_code', true).'</td>
		<td width="266">Vacate Notice: '.get_post_meta($lease_id, '_yl_vacate_notice', true).'</td>
	</tr>
	<tr>
		<td width="266">&nbsp;</td>
		<td width="266">Promotional Code: '.get_post_meta($lease_id, '_yl_promotional_code', true).'</td>
	</tr>
	<tr>
		<td width="266">&nbsp;</td>
		<td width="266">Service Fees: '.get_post_meta($lease_id, '_yl_service_fees', true).'</td>
	</tr>
	<tr>
		<td width="266">&nbsp;</td>
		<td width="266">Multi Suite Discount: '.get_post_meta($lease_id, '_yl_multi_suite_discount', true).'</td>
	</tr>
	<tr>
		<td width="266">&nbsp;</td>
		<td width="266">Addendums: '.get_post_meta($lease_id, '_yl_addendums', true).'</td>
	</tr>
	<tr>
		<td width="266" height="50">&nbsp;</td>
		<td width="266" height="50">&nbsp;</td>
	</tr>
	<tr>
		<td width="266" height="55">Date: '.get_post_meta($lease_id, '_yl_client_ls_signature_date', true).'</td>
		<td width="266" height="55">-</td>
		<td width="266" height="55">Date: '.get_post_meta($lease_id, '_yl_bm_ls_signature_date', true).'</td>
	</tr>
	<tr>
		<td width="180">Signature (Client): </td>
		<td width="172">Package Waiver Sign. (Client): </td>
		<td width="180">Signature (BM): </td>
	</tr>
	<tr>
		<td width="180">'.$client_signature.'</td>
		<td width="172">'.$pw_signature.'</td>
		<td width="180">'.$bm_signature.'</td>
	</tr>	
	</table>';
	
	
	
	//$html .= $html_lease_summary;
	
	$pdf->WriteHTML($html_lease_summary);
	
	

	$pdf->AddPage();
//
//	$pdf->SetFont('Helvetica','',10);
	
	if( get_post_meta($lease_id, '_yl_bm_signature', true) ) {
		$bm_signature = '<img src="'.get_post_meta($lease_id, '_yl_bm_signature', true).'" width="300" />';
	} else {
		$bm_signature = '';
	}

	if( get_post_meta($lease_id, '_yl_client_signature', true) ) {
		$client_signature = '<img src="'.get_post_meta($lease_id, '_yl_client_signature', true).'" width="300" />';
	} else {
		$client_signature = '';
	}
	
	if( get_post_meta($lease_id, '_yl_client_pw_signature', true) ) {
		$pw_signature = '<img src="'.get_post_meta($lease_id, '_yl_client_pw_signature', true).'" width="300" />';
	} else {
		$pw_signature = '';
	}

	$logo = '<img src="'.YL_URL.'images/logo.jpg" width="210" height="38" />';
	
	$html .= '<table border="0" cellpadding="0" cellspacing="0" style="width:100%;">
	<tr>
		<td style="text-align: center;">'.$logo.'</td>
	</tr>
	</table>';
	
	$lease_product_id = get_post_meta($lease_id, '_yl_product_id', true);
	if ($lease_product_id == -1) {
		$lease_summary = stripslashes(get_option('ym_lease_summary'));
	}else{
		$lease_summary = stripslashes(get_option('lease_summary'));
	}	
	
	$search = array();
	$replace = array();	
	
	$search[] = '%%CompanyName%%';
	$replace[] = get_the_title(get_post_meta($lease_id, '_yl_company_name', true));
	
	$search[] = '%%LesseeGuarantorPersonalPhoneNumber%%';
	$replace[] = get_post_meta($lease_id, '_yl_g_phone', true);

	$search[] = '%%CompanyType%%';
	$c_term = get_term_by('id', get_post_meta($lease_id, '_yl_company_type', true), 'companytype');
	$replace[] = $c_term->name;

	$search[] = '%%Lessee%%';			
	$replace[] = $lessee_full_name;

	$search[] = '%%Guarantor%%';	
	$replace[] = $guarantor_full_name;

	$search[] = '%%SuiteNo%%';
	$replace[] = get_post_meta($lease_id, '_yl_suite_number', true);

	$search[] = '%%FirstMonthRentRate%%';
	$replace[] = get_post_meta($lease_id, '_yl_first_month_rent_rate', true);

	$search[] = '%%RecurringRentRate%%';
	$replace[] = get_post_meta($lease_id, '_yl_monthly_rent', true);

	$search[] = '%%SecurityDeposit%%';
	$replace[] = get_post_meta($lease_id, '_yl_security_deposit', true);

	$search[] = '%%MoveinLeaseStartDate%%';
	$replace[] = get_post_meta($lease_id, '_yl_lease_start_date', true);

	$search[] = '%%LesseeGuarantorHomeAddress%%';
	if( get_post_meta($lease_id, '_yl_g_address_line_2', true) ) {
		$g_street2 = ', '.get_post_meta($lease_id, '_yl_g_address_line_2', true);
	} else {
		$g_street2 = '';
	}
	$g_address = get_post_meta($lease_id, '_yl_g_street_address', true).$g_street2.', '.get_post_meta($lease_id, '_yl_g_city', true).', '.get_post_meta($lease_id, '_yl_g_state', true).', '.get_post_meta($lease_id, '_yl_g_zip_code', true);
	$replace[] = $g_address;

	$search[] = '%%CurrentDate%%';
	$replace[] = date("Y-m-d");

	$search[] = '%%LesseeGuarantorEmail%%';
	$replace[] = get_post_meta($lease_id, '_yl_g_email', true);

	//$search[] = '%%ServiceCompanyCheckbox%%';
	//$replace[] = get_post_meta($lease_id, '_yl_g_email', true);

	$search[] = '%%Addendum%%';
	$replace[] = get_post_meta($lease_id, '_yl_addendums', true);

	$search[] = '%%PromoCode%%';
	$replace[] = get_post_meta($lease_id, '_yl_promotional_code', true);

	$search[] = '%%MultisuiteDiscountIfApplicable%%';
	$replace[] = get_post_meta($lease_id, '_yl_multi_suite_discount', true);

	$search[] = '%%Lessor%%';
	$replace[] = get_post_meta($lease_id, '_yl_lessor', true);

	$search[] = '%%Location%%';
	$replace[] = get_post_meta($lease_id, '_yl_location', true);

	$lease_post = get_post( $lease_id );
	$author_id = $lease_post->post_author;
	$user = get_user_by( 'id', $author_id );

	$search[] = '%%BuildingManager%%';
	$replace[] = $user->first_name;

	$search[] = '%%BuildingManagerEmail%%';
	$replace[] = $user->user_email;

	$search[] = '%%BuildingManagerPhoneNumber%%';
	$replace[] = get_user_meta( $author_id, 'billing_phone', true );

	$search[] = '%%LesseeHomeAddress%%';
	if( get_post_meta($lease_id, '_yl_l_address_line_2', true) ) {
		$l_street2 = ', '.get_post_meta($lease_id, '_yl_l_address_line_2', true);
	} else {
		$l_street2 = '';
	}
	$l_address = get_post_meta($lease_id, '_yl_l_street_address', true).$l_street2.', '.get_post_meta($lease_id, '_yl_l_city', true).', '.get_post_meta($lease_id, '_yl_l_state', true).', '.get_post_meta($lease_id, '_yl_l_zip_code', true);
	$replace[] = $l_address;

	$search[] = '%%LocationPhoneNumber%%';
	$replace[] = get_post_meta($lease_id, '_yl_location_phone_number', true);

	$search[] = '%%LeaseTerm%%';
	$replace[] = get_post_meta($lease_id, '_yl_lease_term', true);

	$search[] = '%%ServiceFees%%';
	$replace[] = get_post_meta($lease_id, '_yl_service_fees', true);

	$search[] = '%%DueAtSigning%%';
	$replace[] = get_post_meta($lease_id, '_yl_due_at_signing', true);

	$search[] = '%%IsLesseeGuarantor%%';
	$replace[] = get_post_meta($lease_id, '_yl_lessee_guarantor_same', true);

	$search[] = '%%LesseePhone%%';
	$replace[] = get_post_meta($lease_id, '_yl_l_phone', true);

	$search[] = '%%LesseeEmail%%';
	$replace[] = get_post_meta($lease_id, '_yl_l_email', true);

	$search[] = '%%LesseeSignature%%';
	$replace[] = '<img src="'.get_post_meta($lease_id, '_yl_client_signature', true).'" />';

	$search[] = '%%BuildingManagerSignature%%';
	$replace[] = '<img src="'.get_post_meta($lease_id, '_yl_bm_signature', true).'" />';

	$search[] = '%%LesseeSignatureDate%%';
	$replace[] = get_post_meta($lease_id, '_yl_client_signature_date', true);

	$search[] = '%%BuildingManagerSignatureDate%%';
	$replace[] = get_post_meta($lease_id, '_yl_bm_signature_date', true);

	$search[] = '%%LeasePDF%%';
	$replace[] = get_post_meta($lease_id, '_yl_lease_pdf', true);

	$search[] = '%%LeaseSummaryPDF%%';
	$replace[] = get_post_meta($lease_id, '_yl_lease_summary_pdf', true);
	
	$summary = str_replace($search, $replace, $lease_summary);	
	
	//$summary = stripslashes($summary);
	//$summary = nl2br($summary);
		
	$summary = apply_filters('the_content', $summary);

	$html .= $summary;
	
	$html .= '<table border="0" cellpadding="0" cellspacing="0">
				<tr>
					<td width="266" height="100">&nbsp;</td>
					<td width="266" height="100">&nbsp;</td>
				</tr>
				<tr>
					<td width="266" height="35">Date: '.get_post_meta($lease_id, '_yl_client_signature_date', true).'</td>
					<td width="266" height="35">Date: '.get_post_meta($lease_id, '_yl_bm_signature_date', true).'</td>
				</tr>
				<tr>
					<td width="266" height="50">Signature(Client): </td>
					<td width="266" height="50">Signature(BM): </td>
				</tr>
				<tr>
					<td width="266">'.$client_signature.'</td>
					<td width="266">'.$bm_signature.'</td>
				</tr>
				<tr>
					<td width="266" height="50">Package Waiver Sign. (Client): </td>
					<td width="266" height="50"></td>
				</tr>
				<tr>
					<td width="266">'.$pw_signature.'</td>
					<td width="266" height="55"></td>
				</tr>
			</table>';
	
	$pdf->WriteHTML($html);

	$dir = "/lease-pdf";
	$upload_dir = wp_upload_dir();
	$lease_pdf_dir = $upload_dir['basedir'].$dir;
	$lease_pdf_url = $upload_dir['baseurl'].$dir;
	if( ! file_exists( $lease_pdf_dir ) ){
		wp_mkdir_p( $lease_pdf_dir );
	}
	$lease_title = yl_seo_friendly_filename(strtolower(str_replace(" ", "-", get_the_title($lease_id))).'-'.date("Y-m-d_H-i-s"));

	if( ! file_exists( $lease_pdf_dir.'/'.$lease_title.'-full.pdf' ) ) {
		$pdf->Output($lease_pdf_dir.'/'.$lease_title.'-full.pdf', 'F');
	} else {
		unlink($lease_pdf_dir.'/'.$lease_title.'-full.pdf');
		$pdf->Output($lease_pdf_dir.'/'.$lease_title.'-full.pdf', 'F');
	}
	
	update_post_meta($lease_id, '_yl_full_lease_pdf', $lease_pdf_url.'/'.$lease_title.'-full.pdf');

}


// Extend the TCPDF class to create custom Header and Footer for addemdumn
class VACATENOTICEMYPDF extends TCPDF {

    //Page header
    public function Header() {
        // Logo
        $image_file = YL_URL.'images/yeager-logo.jpg';
        $this->Image($image_file, 16, 8, 50, '', 'JPG', '', 'T', false, 300, '', false, false, 0, false, false, false);
        // Set font
        $this->SetFont('helvetica', 'B', 20);
        // Title
		$this->setCellMargins(0, 5, 0, 0);
        $this->Cell(0, 20, 'NOTICE TO VACATE', 0, 1, 'C', 0, '', 0, false, 'M', 'M');
        $this->SetY(22);
		$this->SetFillColor(0, 99, 100, 0);
		$this->SetTextColor(0, 99, 100, 0);
        $this->SetFont('helvetica', '', 5);
		$this->setCellMargins(0, 0, 0, 0);
        $this->Cell(0, 0.2, '&nbsp;', 0, 1, 'C', true, '', 0, false, 'M', 'M');
    }

    // Page footer
    public function Footer() {
        // Position at 15 mm from bottom
        $this->SetY(-22);
        /*$this->SetFont('helvetica', 'I', 8);
        $this->Cell(0, 10, 'Page '.$this->getAliasNumPage().'/'.$this->getAliasNbPages(), 0, false, 'C', 0, '', 0, false, 'T', 'M');*/

		$this->SetFillColor(0, 99, 100, 0);
		$this->SetTextColor(0, 99, 100, 0);
        $this->SetFont('helvetica', '', 5);
        $this->Cell(0, 0.2, '&nbsp;', 0, 1, 'C', true, '', 0, false, 'M', 'M');
        // Logo
        $image_file = YL_URL.'images/yeager-logo.jpg';
        $this->Image($image_file, 16, 278, 50, '', 'JPG', '', 'T', false, 300, '', false, false, 0, false, false, false);
        // Set font
        $this->SetFont('helvetica', 'B', 10);
		$this->SetTextColor(72, 66, 65, 74);
		$txt = "Yeager Properties | Yeager Construction 23 S. 8th Street, Noblesville, IN, 46060 Main: 317- Main: 317-770-7380 | Fax: 317 7380 | Fax: 317 7380 | Fax: 317-776-1867 yeagerproperties.com";
		$this->setCellPaddings(3, 0, 0, 0);
		$this->setCellMargins(5, 0, 0, 0);
		$this->MultiCell(120, 5, $txt, 'L', 'L', false, 0, '', '', true);
    }
}


// Extend the TCPDF class to create custom Header and Footer for addemdumn
class EARLYVACATEADDENDUMMYPDF extends TCPDF {

    //Page header
    public function Header() {
        // Logo
        $image_file = YL_URL.'images/yeager-logo.jpg';
        $this->Image($image_file, 16, 8, 50, '', 'JPG', '', 'T', false, 300, '', false, false, 0, false, false, false);
        // Set font
        $this->SetFont('helvetica', 'B', 20);
        // Title
		$this->setCellMargins(0, 5, 0, 0);
        $this->Cell(0, 20, 'EARLY VACATE ADDENDUM', 0, 1, 'C', 0, '', 0, false, 'M', 'M');
        $this->SetY(22);
		$this->SetFillColor(0, 99, 100, 0);
		$this->SetTextColor(0, 99, 100, 0);
        $this->SetFont('helvetica', '', 5);
		$this->setCellMargins(0, 0, 0, 0);
        $this->Cell(0, 0.2, '&nbsp;', 0, 1, 'C', true, '', 0, false, 'M', 'M');
    }

    // Page footer
    public function Footer() {
        // Position at 15 mm from bottom
        $this->SetY(-22);
        /*$this->SetFont('helvetica', 'I', 8);
        $this->Cell(0, 10, 'Page '.$this->getAliasNumPage().'/'.$this->getAliasNbPages(), 0, false, 'C', 0, '', 0, false, 'T', 'M');*/

		$this->SetFillColor(0, 99, 100, 0);
		$this->SetTextColor(0, 99, 100, 0);
        $this->SetFont('helvetica', '', 5);
        $this->Cell(0, 0.2, '&nbsp;', 0, 1, 'C', true, '', 0, false, 'M', 'M');
        // Logo
        $image_file = YL_URL.'images/yeager-logo.jpg';
        $this->Image($image_file, 16, 278, 50, '', 'JPG', '', 'T', false, 300, '', false, false, 0, false, false, false);
        // Set font
        $this->SetFont('helvetica', 'B', 10);
		$this->SetTextColor(72, 66, 65, 74);
		$txt = "Yeager Properties | Yeager Construction 23 S. 8th Street, Noblesville, IN, 46060 Main: 317- Main: 317-770-7380 | Fax: 317 7380 | Fax: 317 7380 | Fax: 317-776-1867 yeagerproperties.com";
		$this->setCellPaddings(3, 0, 0, 0);
		$this->setCellMargins(5, 0, 0, 0);
		$this->MultiCell(120, 5, $txt, 'L', 'L', false, 0, '', '', true);
    }
}


function generate_vacate_notice_pdf($lease_id) {
	$pdf = new VACATENOTICEMYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
	//$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE.' 001', PDF_HEADER_STRING, array(0,64,255), array(0,64,128));
	$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE, PDF_HEADER_STRING, array(0,64,255), array(0,64,128));
	$pdf->setFooterData(array(0,64,0), array(0,64,128));

	// set margins
	$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
	$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
	$pdf->SetFooterMargin(25);
	
	// set auto page breaks
	$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

	$pdf->AddPage();
	//$pdf->SetFont('Arial','',10);

	$lease_user_id = get_post_meta($lease_id, '_yl_lease_user', true);
	$lease_user_info = get_userdata($lease_user_id);
	$lease_first_name = $lease_user_info->first_name;
	$lease_last_name = $lease_user_info->last_name;	
	$lease_user_email = $lease_user_info->user_email;	
	
	$lease_bm_user_id = get_post_meta($lease_id, '_yl_author_id', true);
	$lease_bm_user_info = get_userdata($lease_bm_user_id);
	$lease_bm_first_name = $lease_bm_user_info->first_name;
	$lease_bm_last_name = $lease_bm_user_info->last_name;	
	$lease_bm_user_email = $lease_bm_user_info->user_email;	


	if( get_post_meta($lease_id, '_yl_vn_client_signature', true) ) {
		$client_signature = '<img src="'.get_post_meta($lease_id, '_yl_vn_client_signature', true).'" width="250" />';
	} else {
		$client_signature = '';
	}
	$client_signature_date = get_post_meta($lease_id, '_yl_vn_client_signature_date', true);

	if( get_post_meta($lease_id, '_yl_vn_bm_signature', true) ) {
		$bm_signature = '<img src="'.get_post_meta($lease_id, '_yl_vn_bm_signature', true).'" width="250" />';
	} else {
		$bm_signature = '';
	}	
	$bm_signature_date = get_post_meta($lease_id, '_yl_vn_bm_signature_date', true);
	
	
	$early_vacate = get_option('early_vacate_addendum');
	$search = array();
	$replace = array();
	
	$search[] = '%%Suite%%';
	$replace[] = get_post_meta($lease_id, '_yl_suites_leased', true);
	
	$search[] = '%%Location%%';
	$replace[] = get_post_meta($lease_id, '_yl_location', true);
	
	$summary = str_replace($search, $replace, $early_vacate);
	$summary = stripslashes($summary);
	$summary = nl2br($summary);
	
	
	
	$vacate_output = '';
	
	$vacate_notice_out = '';
	$vacate_notice_out = '<table border="0" cellpadding="10" cellspacing="10">';
	
	$vacate_notice_out .= '<tr><td>';
	$vacate_notice_out .= '<u>TO WHOM IT MAY CONCERN</u>'.'<br />';
	$vacate_notice_out .= 'YEAGER PROPERTIES'.'<br />';
	$vacate_notice_out .= get_post_meta($lease_id, '_yl_va_building', true);
	$vacate_notice_out .= '</td></tr>';
	
	$vacate_notice_out .= '<tr><td>';
	$vacate_notice_out .= '<u>ADDRESS OF RENTAL PROPERTY</u>'.'<br />';
	$vacate_notice_out .= get_post_meta($lease_id, '_yl_suite_number', true).'<br />';		
	$vacate_notice_out .= get_option('yl_location').'<br />';
	$vacate_notice_out .= 'Phone: '.get_option('yl_location_phone');		
	$vacate_notice_out .= '</td></tr>';
	
	$vacate_notice_out .= '<tr><td>';
	$vacate_notice_out .= '<u>TENANT OF RENTAL PROPERTY</u>'.'<br />';
	$vacate_notice_out .= get_post_meta($lease_id, '_yl_va_business_name', true).'<br />';
	$vacate_notice_out .= get_post_meta($lease_id, '_yl_va_lessee', true).'<br />';
	$vacate_notice_out .= get_post_meta($lease_id, '_yl_tenant_forwarding_address', true).'<br />';
	$vacate_notice_out .= get_post_meta($lease_id, '_yl_va_cell_phone', true).'<br />';
	$vacate_notice_out .= get_post_meta($lease_id, '_yl_tenant_contact_email', true).'<br />';
	$vacate_notice_out .= '</td></tr>';		
	
	$vacate_notice_out .= '<tr><td>';
	$vacate_notice_out .= '<u>NOTICE TO VACATE</u>'.'<br />';
	$vacate_notice_out .= 'You are hereby informated of the Tenant\'s intention to vacate the occupied premises. The keys will be returned and the property will be vacant by: '.get_post_meta($lease_id, '_yl_ninty_day_vacate_date', true);
	$vacate_notice_out .= '</td></tr>';		
	
	$vacate_notice_out .= '<tr>';
	$vacate_notice_out .= '<td width="266"><strong>'.$lease_first_name.'</strong><br />'.$client_signature.'<br />'.$client_signature_date.'</td>';
	$vacate_notice_out .= '<td width="266"><strong>'.$lease_bm_first_name.'</strong><br />'.$bm_signature.'<br />'.$bm_signature_date.'</td>';
	$vacate_notice_out .= '</tr>';	
	
		
	
	$vacate_notice_out .= '</table>';
	
	
	
	// output the HTML content
	$pdf->writeHTML($vacate_notice_out, true, false, true, false, '');	
	$pdf->lastPage();
	
	$dir = "/lease-pdf";
	$upload_dir = wp_upload_dir();
	$lease_pdf_dir = $upload_dir['basedir'].$dir;
	$lease_pdf_url = $upload_dir['baseurl'].$dir;
	if( ! file_exists( $lease_pdf_dir ) ){
		wp_mkdir_p( $lease_pdf_dir );
	}
	$lease_title = yl_seo_friendly_filename(strtolower(str_replace(" ", "-", get_the_title($lease_id))).'-vacate-notice-'.date("Y-m-d_H-i-s"));

	if( ! file_exists( $lease_pdf_dir.'/'.$lease_title.'.pdf' ) ) {
		$pdf->Output($lease_pdf_dir.'/'.$lease_title.'.pdf', 'F');
	} else {
		unlink($lease_pdf_dir.'/'.$lease_title.'.pdf');
		$pdf->Output($lease_pdf_dir.'/'.$lease_title.'.pdf', 'F');
	}
	
	update_post_meta($lease_id, '_yl_vacate_notice_pdf', $lease_pdf_url.'/'.$lease_title.'.pdf');
		
	$vacate_addendum_pdf_file = $lease_pdf_dir.'/'.$lease_title.'.pdf';
	
	return $vacate_addendum_pdf_file;	
}

function generate_early_vacate_addendum_pdf($lease_id) {
	$pdf = new EARLYVACATEADDENDUMMYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
	//$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE.' 001', PDF_HEADER_STRING, array(0,64,255), array(0,64,128));
	$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE, PDF_HEADER_STRING, array(0,64,255), array(0,64,128));
	$pdf->setFooterData(array(0,64,0), array(0,64,128));

	// set margins
	$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
	$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
	$pdf->SetFooterMargin(25);
	
	// set auto page breaks
	$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

	$pdf->AddPage();
	//$pdf->SetFont('Arial','',10);

	$lease_user_id = get_post_meta($lease_id, '_yl_lease_user', true);
	$lease_user_info = get_userdata($lease_user_id);
	$lease_first_name = $lease_user_info->first_name;
	$lease_last_name = $lease_user_info->last_name;	
	$lease_user_email = $lease_user_info->user_email;	
	
	$lease_bm_user_id = get_post_meta($lease_id, '_yl_author_id', true);
	$lease_bm_user_info = get_userdata($lease_bm_user_id);
	$lease_bm_first_name = $lease_bm_user_info->first_name;
	$lease_bm_last_name = $lease_bm_user_info->last_name;	
	$lease_bm_user_email = $lease_bm_user_info->user_email;	


	if( get_post_meta($lease_id, '_yl_va_client_signature', true) ) {
		$client_signature = '<img src="'.get_post_meta($lease_id, '_yl_va_client_signature', true).'" width="250" />';
	} else {
		$client_signature = '';
	}
	$client_signature_date = get_post_meta($lease_id, '_yl_va_client_signature_date', true);

	if( get_post_meta($lease_id, '_yl_va_bm_signature', true) ) {
		$bm_signature = '<img src="'.get_post_meta($lease_id, '_yl_va_bm_signature', true).'" width="250" />';
	} else {
		$bm_signature = '';
	}	
	$bm_signature_date = get_post_meta($lease_id, '_yl_va_bm_signature_date', true);
	
	
	$early_vacate = get_option('early_vacate_addendum');
	$search = array();
	$replace = array();
	
	$search[] = '%%Suite%%';
	$replace[] = get_post_meta($lease_id, '_yl_suites_leased', true);
	
	$search[] = '%%Location%%';
	$replace[] = get_post_meta($lease_id, '_yl_location', true);
	
	$summary = str_replace($search, $replace, $early_vacate);
	$summary = stripslashes($summary);
	$summary = nl2br($summary);
	
	$vacate_addendum_out = '<table border="0" cellpadding="10" cellspacing="10">';
	$vacate_addendum_out .= '<tr><td>'.$summary.'</td></tr>';
	
	$vacate_addendum_out .= '<tr><td>';
	$vacate_addendum_out .= '<u>Lessee:</u> '.get_post_meta($lease_id, '_yl_va_lessee', true).'<br />';
	$vacate_addendum_out .= '<u>Building:</u> '.get_post_meta($lease_id, '_yl_va_building', true).'<br />';
	$vacate_addendum_out .= '<u>Business Name:</u> '.get_post_meta($lease_id, '_yl_va_business_name', true).'<br />';
	$vacate_addendum_out .= '<u>Forwarding Email:</u> '.get_post_meta($lease_id, '_yl_tenant_contact_email', true).'<br />';
	$vacate_addendum_out .= '<u>Forwarding Phone:</u> '.get_post_meta($lease_id, '_yl_va_cell_phone', true).'<br />';
	$vacate_addendum_out .= '<u>Forwarding Address:</u> '.get_post_meta($lease_id, '_yl_tenant_forwarding_address', true).'<br />';
	$vacate_addendum_out .= '<u>Security Deposit Held:</u> '.get_post_meta($lease_id, '_yl_security_deposit', true).'<br /><small></small><br />Your security deposit balance returns to the address provided us 45 days from vacate. This balance considers suite refreshing charges, keys and fob fees, and other outstanding fees.';
	$vacate_addendum_out .= '<u>Date Vacate Notice Given:</u> '.get_post_meta($lease_id, '_yl_date_vacate_notice_given', true).'<br />';
	$vacate_addendum_out .= '<u>90-day Vacate Date:</u> '.get_post_meta($lease_id, '_yl_ninty_day_vacate_date', true).'<br />';
	$vacate_addendum_out .= '<u>Suites Leased:</u> '.get_post_meta($lease_id, '_yl_suites_leased', true).'<br />';
	$vacate_addendum_out .= '<u>Suites Identified in this Agreement:</u> '.get_post_meta($lease_id, '_yl_suites_identified_agreement', true).'<br />';
	$vacate_addendum_out .= '<u>All-or-Nothing Demand for Multiple Suites:</u> '.get_post_meta($lease_id, '_yl_all_n_demand_multiple_suites', true).'<br />';
	$vacate_addendum_out .= '</td></tr>';

	$vacate_addendum_out .= '<tr>';
	$vacate_addendum_out .= '<td width="266"><strong>'.$lease_first_name.'</strong><br />'.$client_signature.'<br />'.$client_signature_date.'</td>';
	$vacate_addendum_out .= '<td width="266"><strong>'.$lease_bm_first_name.'</strong><br />'.$bm_signature.'<br />'.$bm_signature_date.'</td>';	
	$vacate_addendum_out .= '</tr>';

	$vacate_addendum_out .= '</table>';
	
	
	//Add early vacate addendum
	if(get_post_meta($lease_id, '_yl_early_vacate_addendum', true)){	
		$pdf->writeHTML($vacate_addendum_out, true, false, true, false, '');
		$pdf->lastPage();
	}
	

	$dir = "/lease-pdf";
	$upload_dir = wp_upload_dir();
	$lease_pdf_dir = $upload_dir['basedir'].$dir;
	$lease_pdf_url = $upload_dir['baseurl'].$dir;
	if( ! file_exists( $lease_pdf_dir ) ){
		wp_mkdir_p( $lease_pdf_dir );
	}
	$lease_title = yl_seo_friendly_filename(strtolower(str_replace(" ", "-", get_the_title($lease_id))).'-early-vacate-addendum-'.date("Y-m-d_H-i-s"));

	if( ! file_exists( $lease_pdf_dir.'/'.$lease_title.'.pdf' ) ) {
		$pdf->Output($lease_pdf_dir.'/'.$lease_title.'.pdf', 'F');
	} else {
		unlink($lease_pdf_dir.'/'.$lease_title.'.pdf');
		$pdf->Output($lease_pdf_dir.'/'.$lease_title.'.pdf', 'F');
	}
	
	update_post_meta($lease_id, '_yl_early_vacate_addendum_pdf', $lease_pdf_url.'/'.$lease_title.'.pdf');
		
	$vacate_addendum_pdf_file = $lease_pdf_dir.'/'.$lease_title.'.pdf';
	
	return $vacate_addendum_pdf_file;	
}

function generate_vacate_and_early_vacate_addendum_pdf($lease_id) {
	$pdf = new EARLYVACATEADDENDUMMYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
	//$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE.' 001', PDF_HEADER_STRING, array(0,64,255), array(0,64,128));
	$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE, PDF_HEADER_STRING, array(0,64,255), array(0,64,128));
	$pdf->setFooterData(array(0,64,0), array(0,64,128));

	// set margins
	$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
	$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
	$pdf->SetFooterMargin(25);
	
	// set auto page breaks
	$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

	$pdf->AddPage();
	//$pdf->SetFont('Arial','',10);

	$lease_user_id = get_post_meta($lease_id, '_yl_lease_user', true);
	$lease_user_info = get_userdata($lease_user_id);
	$lease_first_name = $lease_user_info->first_name;
	$lease_last_name = $lease_user_info->last_name;	
	$lease_user_email = $lease_user_info->user_email;	
	
	$lease_bm_user_id = get_post_meta($lease_id, '_yl_author_id', true);
	$lease_bm_user_info = get_userdata($lease_bm_user_id);
	$lease_bm_first_name = $lease_bm_user_info->first_name;
	$lease_bm_last_name = $lease_bm_user_info->last_name;	
	$lease_bm_user_email = $lease_bm_user_info->user_email;	


	if( get_post_meta($lease_id, '_yl_va_client_signature', true) ) {
		$client_signature = '<img src="'.get_post_meta($lease_id, '_yl_va_client_signature', true).'" width="250" />';
	} else {
		$client_signature = '';
	}
	$client_signature_date = get_post_meta($lease_id, '_yl_va_client_signature_date', true);

	if( get_post_meta($lease_id, '_yl_va_bm_signature', true) ) {
		$bm_signature = '<img src="'.get_post_meta($lease_id, '_yl_va_bm_signature', true).'" width="250" />';
	} else {
		$bm_signature = '';
	}	
	$bm_signature_date = get_post_meta($lease_id, '_yl_va_bm_signature_date', true);
	
	
	$early_vacate = get_option('early_vacate_addendum');
	$search = array();
	$replace = array();
	
	$search[] = '%%Suite%%';
	$replace[] = get_post_meta($lease_id, '_yl_suites_leased', true);
	
	$search[] = '%%Location%%';
	$replace[] = get_post_meta($lease_id, '_yl_location', true);
	
	$summary = str_replace($search, $replace, $early_vacate);
	$summary = stripslashes($summary);
	$summary = nl2br($summary);
	
	$vacate_addendum_out = '<table border="0" cellpadding="10" cellspacing="10">';
	$vacate_addendum_out .= '<tr><td>'.$summary.'</td></tr>';
	
	$vacate_addendum_out .= '<tr><td>';
	$vacate_addendum_out .= '<u>Lessee:</u> '.get_post_meta($lease_id, '_yl_va_lessee', true).'<br />';
	$vacate_addendum_out .= '<u>Building:</u> '.get_post_meta($lease_id, '_yl_va_building', true).'<br />';
	$vacate_addendum_out .= '<u>Business Name:</u> '.get_post_meta($lease_id, '_yl_va_business_name', true).'<br />';
	$vacate_addendum_out .= '<u>Forwarding Email:</u> '.get_post_meta($lease_id, '_yl_tenant_contact_email', true).'<br />';
	$vacate_addendum_out .= '<u>Forwarding Phone:</u> '.get_post_meta($lease_id, '_yl_va_cell_phone', true).'<br />';
	$vacate_addendum_out .= '<u>Forwarding Address:</u> '.get_post_meta($lease_id, '_yl_tenant_forwarding_address', true).'<br />';
	$vacate_addendum_out .= '<u>Security Deposit Held:</u> '.get_post_meta($lease_id, '_yl_security_deposit', true).'<br /><small></small><br />Your security deposit balance returns to the address provided us 45 days from vacate. This balance considers suite refreshing charges, keys and fob fees, and other outstanding fees.';
	$vacate_addendum_out .= '<u>Date Vacate Notice Given:</u> '.get_post_meta($lease_id, '_yl_date_vacate_notice_given', true).'<br />';
	$vacate_addendum_out .= '<u>90-day Vacate Date:</u> '.get_post_meta($lease_id, '_yl_ninty_day_vacate_date', true).'<br />';
	$vacate_addendum_out .= '<u>Suites Leased:</u> '.get_post_meta($lease_id, '_yl_suites_leased', true).'<br />';
	$vacate_addendum_out .= '<u>Suites Identified in this Agreement:</u> '.get_post_meta($lease_id, '_yl_suites_identified_agreement', true).'<br />';
	$vacate_addendum_out .= '<u>All-or-Nothing Demand for Multiple Suites:</u> '.get_post_meta($lease_id, '_yl_all_n_demand_multiple_suites', true).'<br />';
	$vacate_addendum_out .= '</td></tr>';

	$vacate_addendum_out .= '<tr>';
	$vacate_addendum_out .= '<td width="266"><strong>'.$lease_first_name.'</strong><br />'.$client_signature.'<br />'.$client_signature_date.'</td>';
	$vacate_addendum_out .= '<td width="266"><strong>'.$lease_bm_first_name.'</strong><br />'.$bm_signature.'<br />'.$bm_signature_date.'</td>';	
	$vacate_addendum_out .= '</tr>';

	$vacate_addendum_out .= '</table>';
	
	
	$vacate_output = '';
	
	$vacate_notice_out = '';
	$vacate_notice_out = '<table border="0" cellpadding="10" cellspacing="10">';
	
	$vacate_notice_out .= '<tr><td>';
	$vacate_notice_out .= '<u>TO WHOM IT MAY CONCERN</u>'.'<br />';
	$vacate_notice_out .= 'YEAGER PROPERTIES'.'<br />';
	$vacate_notice_out .= get_post_meta($lease_id, '_yl_va_building', true);
	$vacate_notice_out .= '</td></tr>';
	
	$vacate_notice_out .= '<tr><td>';
	$vacate_notice_out .= '<u>ADDRESS OF RENTAL PROPERTY</u>'.'<br />';
	$vacate_notice_out .= get_post_meta($lease_id, '_yl_suite_number', true).'<br />';		
	$vacate_notice_out .= get_option('yl_location').'<br />';
	$vacate_notice_out .= 'Phone: '.get_option('yl_location_phone');		
	$vacate_notice_out .= '</td></tr>';
	
	$vacate_notice_out .= '<tr><td>';
	$vacate_notice_out .= '<u>TENANT OF RENTAL PROPERTY</u>'.'<br />';
	$vacate_notice_out .= get_post_meta($lease_id, '_yl_va_business_name', true).'<br />';
	$vacate_notice_out .= get_post_meta($lease_id, '_yl_va_lessee', true).'<br />';
	$vacate_notice_out .= get_post_meta($lease_id, '_yl_tenant_forwarding_address', true).'<br />';
	$vacate_notice_out .= get_post_meta($lease_id, '_yl_va_cell_phone', true).'<br />';
	$vacate_notice_out .= get_post_meta($lease_id, '_yl_tenant_contact_email', true).'<br />';
	$vacate_notice_out .= '</td></tr>';		
	
	$vacate_notice_out .= '<tr><td>';
	$vacate_notice_out .= '<u>NOTICE TO VACATE</u>'.'<br />';
	$vacate_notice_out .= 'You are hereby informated of the Tenant\'s intention to vacate the occupied premises. The keys will be returned and the property will be vacant by: '.get_post_meta($lease_id, '_yl_ninty_day_vacate_date', true);
	$vacate_notice_out .= '</td></tr>';		
	
	$vacate_notice_out .= '<tr>';
	$vacate_notice_out .= '<td width="266"><strong>'.$lease_first_name.'</strong><br />'.$client_signature.'<br />'.$client_signature_date.'</td>';
	$vacate_notice_out .= '<td width="266"><strong>'.$lease_bm_first_name.'</strong><br />'.$bm_signature.'<br />'.$bm_signature_date.'</td>';
	$vacate_notice_out .= '</tr>';	
	
		
	
	$vacate_notice_out .= '</table>';
	
	
	//Add vacate notice to PDF
	$vacate_output .= $vacate_notice_out;
	
	// output the HTML content
	$pdf->writeHTML($vacate_notice_out, true, false, true, false, '');	
	$pdf->lastPage();
	
	//Add early vacate addendum
	if(get_post_meta($lease_id, '_yl_early_vacate_addendum', true)){	
		$pdf->AddPage();
		$pdf->writeHTML($vacate_addendum_out, true, false, true, false, '');
		$pdf->lastPage();
	}
	

	$dir = "/lease-pdf";
	$upload_dir = wp_upload_dir();
	$lease_pdf_dir = $upload_dir['basedir'].$dir;
	$lease_pdf_url = $upload_dir['baseurl'].$dir;
	if( ! file_exists( $lease_pdf_dir ) ){
		wp_mkdir_p( $lease_pdf_dir );
	}
	$lease_title = yl_seo_friendly_filename(strtolower(str_replace(" ", "-", get_the_title($lease_id))).'-vacate-addendum-'.date("Y-m-d_H-i-s"));

	if( ! file_exists( $lease_pdf_dir.'/'.$lease_title.'.pdf' ) ) {
		$pdf->Output($lease_pdf_dir.'/'.$lease_title.'.pdf', 'F');
	} else {
		unlink($lease_pdf_dir.'/'.$lease_title.'.pdf');
		$pdf->Output($lease_pdf_dir.'/'.$lease_title.'.pdf', 'F');
	}
	
	update_post_meta($lease_id, '_yl_vacate_addendum_pdf', $lease_pdf_url.'/'.$lease_title.'.pdf');
		
	$vacate_addendum_pdf_file = $lease_pdf_dir.'/'.$lease_title.'.pdf';
	
	return $vacate_addendum_pdf_file;	
}
