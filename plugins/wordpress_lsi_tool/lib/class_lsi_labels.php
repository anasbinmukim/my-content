<?php
require_once( CedarWaterLSITOOL_ROOT . '/tcpdf_min/tcpdf.php');
class custom_wp_lsi_posts_label
{
    public function __construct()
	{
	    /*****Declaration of action hooks*****/
    add_action( 'admin_menu', array(&$this,'elevate_labels_page_menu'));
	}

  function elevate_labels_page_menu(){
    add_submenu_page('edit.php?post_type=lsi_posts', __( 'Labels', 'cedarwaters' ), __( 'Labels', 'cedarwaters' ), 'manage_options', 'elevate-labels-generates', array(&$this,'elevate_labels_generates_page_callback'));
  }

  function elevate_labels_generates_page_callback() {
    $user_id = 90;
    //elevate_generate_pdf_label($user_id);


  ?>
  <div style="width:100%; height:2px; float:left;">&nbsp;</div>
    <div class="wrap">
      <h2>Labels &nbsp;&nbsp;<a class="button button-primary" href="/wp-admin/edit.php?post_type=lsi_posts&page=elevate-labels-generates&labelgenerate=all">Generate All</a></h2>

      <?php
      if(isset($_GET['label']) && isset($_GET['request_member_id'])){
          $user_id = $_GET['request_member_id'];
          $generated_pdf_url = elevate_generate_pdf_label($user_id);
          echo '<div class="updated"><p>Generated done. <a href="'.$generated_pdf_url.'" target="_blank">Click here</a> to download label.</p></div>';
      }

      if(isset($_GET['labelgenerate']) && ($_GET['labelgenerate'] == 'all')){
          $generated_pdf_url = elevate_generate_all_pdf_label();
          echo '<div class="updated"><p>Generated done. <a href="'.$generated_pdf_url.'" target="_blank">Click here</a> to download label.</p></div>';
      }

      $getallmembersgip = MS_Model_Membership::get_memberships();
      if(!empty($getallmembersgip))
      {

        echo '<table class="wp-list-table widefat fixed striped posts"><thead>
        <tr>
          <th>Name</th>
          <th>Email</th>
          <th>Labels</th>
        </tr>
        </thead>
        <tbody>';

        foreach($getallmembersgip as $singlemembership)
        {
          $membership_id=$singlemembership->id;
          $singlemembershipmembers='';
          $singlemembershipmembers=$singlemembership->get_members();
          if(!empty($singlemembershipmembers) && ($membership_id == 7964))
          {
            foreach($singlemembershipmembers as $key=>$memberval)
            {
              $memebrid = $key;
              $getuser_info = get_userdata($memebrid);
              $getusername = $getuser_info->display_name;
              $getuseremaill = $getuser_info->user_email;
              $first_name = $getuser_info->first_name;
              $last_name = $getuser_info->last_name;
              $label_user_name = $first_name.' '.$last_name;
              ?>
              <tr>
                <td><?php echo $label_user_name; ?></td>
                <td><?php echo $getuseremaill; ?></td>
                <td>
                  <?php if(get_user_meta( $memebrid, 'lsi_level_pdf', true )){ ?>
                    <a class="button" href="/wp-admin/edit.php?post_type=lsi_posts&page=elevate-labels-generates&label=generate&request_member_id=<?php echo $memebrid; ?>">Re-generate Label</a>
                    &nbsp;<a class="button" target="_blank" href="<?php echo get_user_meta( $memebrid, 'lsi_level_pdf', true ); ?>">Download</a>
                  <?php }else{ ?>
                    <a class="button" href="/wp-admin/edit.php?post_type=lsi_posts&page=elevate-labels-generates&label=generate&request_member_id=<?php echo $memebrid; ?>">Generate Label</a>
                  <?php } ?>
                </td>
              </tr>
              <?php
            }
          }
        }
        echo '</tbody></table>';
      }
      ?>


  <?php
  }

}

$custom_wp_lsi_posts_label = new custom_wp_lsi_posts_label();

function elevate_generate_pdf_label($user_id) {
	$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

	// remove default header/footer
	$pdf->setPrintHeader(false);
	$pdf->setPrintFooter(false);

	// set auto page breaks
	$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

	$pdf->AddPage();

	$pdf->SetFont('Helvetica','',10);

  $getuser_info = get_userdata($user_id);
  $first_name = $getuser_info->first_name;
  $last_name = $getuser_info->last_name;
  $label_user_name = $first_name.' '.$last_name;
  $ms_reg_lsi_address_line1 = get_user_meta( $user_id, 'ms_reg_lsi_address_line1', true );
  $ms_reg_lsi_address_line2 = get_user_meta( $user_id, 'ms_reg_lsi_address_line2', true );
  $ms_reg_lsi_city_txt = get_user_meta( $user_id, 'ms_reg_lsi_city_txt', true );
  $ms_reg_lsi_state_txt = get_user_meta( $user_id, 'ms_reg_lsi_state_txt', true );
  $ms_reg_lsi_postal_code = get_user_meta( $user_id, 'ms_reg_lsi_postal_code', true );

  $label_user_address = '';
  if($ms_reg_lsi_address_line1){
    $label_user_address .= $ms_reg_lsi_address_line1;
  }
  if($ms_reg_lsi_address_line2){
    $label_user_address .= ', '.$ms_reg_lsi_address_line2;
  }
  if($ms_reg_lsi_city_txt){
    $label_user_address .= '<br />'.$ms_reg_lsi_city_txt;
  }
  if($ms_reg_lsi_state_txt){
    $label_user_address .= ', '.$ms_reg_lsi_state_txt;
  }
  if($ms_reg_lsi_postal_code){
    $label_user_address .= ' '.$ms_reg_lsi_postal_code;
  }

  $logo = '<img style="max-width:100%;" src="'.plugins_url( 'images/elevate-label.png', dirname(__FILE__) ).'" />';
  $html = '<div style="color:#000000; height:auto; background:#FFFFFF; padding:10px 20px; ">';
  $html .= '<div style="">'.$logo.'</div>';
  $html .= '<div style="padding:0px; margin:0px; color:#000000; font-size:18px; line-height:20px;"><span style="text-transform: capitalize; font-size:18px;">'.$label_user_name.'</span>';
  $html .= '<br />'.$label_user_address.'</div>';
  $html .= '</div>';


  $output_label_rows = '';

  $output_label_rows = '<table border="0" cellpadding="10" cellspacing="10">
  <tr>
    <td>'.$html.'</td>
    <td>&nbsp;</td>
  </tr>
  </table>';

	$pdf->WriteHTML($output_label_rows);

	$dir = "/label-pdf";
	$upload_dir = wp_upload_dir();
	$label_pdf_dir = $upload_dir['basedir'].$dir;
	$label_pdf_url = $upload_dir['baseurl'].$dir;
	if( ! file_exists( $label_pdf_dir ) ){
		wp_mkdir_p( $label_pdf_dir );
	}

  //$pdf_name = 'label';
  $pdf_name = sanitize_title($label_user_name).'-label-'.date("Y-m-d_H-i-s");

	if( ! file_exists( $label_pdf_dir.'/'.$pdf_name.'.pdf' ) ) {
		$pdf->Output($label_pdf_dir.'/'.$pdf_name.'.pdf', 'F');
	} else {
		unlink($label_pdf_dir.'/'.$pdf_name.'.pdf');
		$pdf->Output($label_pdf_dir.'/'.$pdf_name.'.pdf', 'F');
	}

  $output_pdf_url = $label_pdf_url.'/'.$pdf_name.'.pdf';

  update_user_meta($user_id, "lsi_level_pdf", $output_pdf_url);

  return $output_pdf_url;
}

function elevate_generate_all_pdf_label(){

  $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

  // remove default header/footer
  $pdf->setPrintHeader(false);
  $pdf->setPrintFooter(false);
  $pdf->SetMargins(5, 10, 5, true);
  // set auto page breaks
  //$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

  $pdf->AddPage();

  $pdf->SetFont('Helvetica','',10);
  $generate_table = '';
  $getallmembersgip = MS_Model_Membership::get_memberships();
  if(!empty($getallmembersgip))
  {

    foreach($getallmembersgip as $singlemembership)
    {
      $membership_id=$singlemembership->id;
      $singlemembershipmembers='';
      $singlemembershipmembers=$singlemembership->get_members();
      if(!empty($singlemembershipmembers) && ($membership_id == 7964))
      {
        $member_count = 1;
        foreach($singlemembershipmembers as $key=>$memberval)
        {
          $user_id = $key;
          $getuser_info = get_userdata($user_id);

          $first_name = $getuser_info->first_name;
          $last_name = $getuser_info->last_name;
          $label_user_name = $first_name.' '.$last_name;

          $ms_reg_lsi_address_line1 = get_user_meta( $user_id, 'ms_reg_lsi_address_line1', true );
          $ms_reg_lsi_address_line2 = get_user_meta( $user_id, 'ms_reg_lsi_address_line2', true );
          $ms_reg_lsi_city_txt = get_user_meta( $user_id, 'ms_reg_lsi_city_txt', true );
          $ms_reg_lsi_state_txt = get_user_meta( $user_id, 'ms_reg_lsi_state_txt', true );
          $ms_reg_lsi_postal_code = get_user_meta( $user_id, 'ms_reg_lsi_postal_code', true );

          $label_user_address = '';
          if($ms_reg_lsi_address_line1){
            $label_user_address .= $ms_reg_lsi_address_line1;
          }
          if($ms_reg_lsi_address_line2){
            $label_user_address .= ', '.$ms_reg_lsi_address_line2;
          }
          if($ms_reg_lsi_city_txt){
            $label_user_address .= '<br />'.$ms_reg_lsi_city_txt;
          }
          if($ms_reg_lsi_state_txt){
            $label_user_address .= ', '.$ms_reg_lsi_state_txt;
          }
          if($ms_reg_lsi_postal_code){
            $label_user_address .= ' '.$ms_reg_lsi_postal_code;
          }

          $extra_br = '';
          if(($member_count == 1) || ($member_count == 2)){
            $extra_br = '<br />';
          }

          $logo = '<img style="max-width:100%;" src="'.plugins_url( 'images/elevate-label.png', dirname(__FILE__) ).'" />';
          $html = $extra_br . '<div style="color:#000000; height:auto; background:#FFFFFF; padding:5px 20px; ">';
          $html .= '<div style="">'.$logo.'</div>';
          $html .= '<div style="padding:0px; margin:0px; color:#000000; font-size:18px; line-height:20px;"><span style="text-transform: capitalize; font-size:18px;">'.$label_user_name.'</span>';
          $html .= '<br />'.$label_user_address.'</div>';
          $html .= '<br />';
          $html .= '</div>';

          if(($member_count % 2) != 0){
            $generate_table .= '<tr>';
          }

          $generate_table .= '<td>'.$html.'</td>';

          if(($member_count % 2) == 0){
            $generate_table .= '</tr>';
          }

          if(($member_count % 6) == 0){
            $generate_table .= '<tr style="padding:0; margin:0;"><td style="padding:0; margin:0; font-size:0; line-height:0;"><br pagebreak="true" /></td></tr>';
          }

          $member_count++;

        }
      }
    }
  }



  $output_label_rows = '';

  $output_label_rows = '<table style="padding:0; margin:0;" border="0" cellpadding="10" cellspacing="10">'.$generate_table.'</table>';

  //echo $output_label_rows;
  //return;

  $pdf->WriteHTML($output_label_rows);

  // Reset pointer to the last page
  $pdf->lastPage();

	$dir = "/label-pdf";
	$upload_dir = wp_upload_dir();
	$label_pdf_dir = $upload_dir['basedir'].$dir;
	$label_pdf_url = $upload_dir['baseurl'].$dir;
	if( ! file_exists( $label_pdf_dir ) ){
		wp_mkdir_p( $label_pdf_dir );
	}

  //$pdf_name = 'label';
  $pdf_name = 'all-labels-'.date("Y-m-d_H-i-s");

	if( ! file_exists( $label_pdf_dir.'/'.$pdf_name.'.pdf' ) ) {
		$pdf->Output($label_pdf_dir.'/'.$pdf_name.'.pdf', 'F');
	} else {
		unlink($label_pdf_dir.'/'.$pdf_name.'.pdf');
		$pdf->Output($label_pdf_dir.'/'.$pdf_name.'.pdf', 'F');
	}

  $output_pdf_url = $label_pdf_url.'/'.$pdf_name.'.pdf';

  return $output_pdf_url;
}
