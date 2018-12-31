<?php
// THIS SHORTCODE IS ACTUALLY THE
// CLIENT LEASE SUMMARY, NOT THE FINAL LEASE
// FIRST CODER GOT THEM WRONG.
function yl_lease_client_sign_shortcode($atts, $content = null) {
	extract(shortcode_atts(array(
		'lease_id' => '',
	), $atts));

	if ( !is_user_logged_in() ){
		wp_login_form();
		return;
	}

  /*
	if(!current_user_can( 'lease_client' )){
		$lid = $_GET['lid'];
		echo 'Only Clients are able to sign lease. Please <a href="'.wp_login_url( get_permalink(get_option('yl_client_sign_page')).'?lid='.$lid ).'">login</a> to sign this lease.';
		return;
	}
  */

  if( isset($_POST['ls_submit']) ) {

    // signature convert an image and send to the wp signature directory
    $dir = "/signatures";
    $id = $_GET['lid'];

    $signature_date = $_POST['date'];

    $data = $_POST['imgOutput2'];
    $keypad_sig = $_POST['keypad_sig'];

    $data3 = $_POST['imgOutput3'];
    $keypad_sig_pw = $_POST['keypad_sig_pw'];

    // convert text to png image
    if ($keypad_sig) {
      $text = $keypad_sig;
      $font = YL_ROOT . '/signature/assets/fonts/Precious';
      $font_color = '000';
      $background_color = 'fff';
      $font_size = '20';
      $upload_dir = wp_upload_dir();
      $signature_dir = $upload_dir['basedir'].$dir;
      $signature_dir_url = $upload_dir['baseurl'].$dir;

      if( ! file_exists( $signature_dir ) ){
        wp_mkdir_p( $signature_dir );
      }

      $filename = time().".png";
      $filepath = $signature_dir."/".$filename;
      file_put_contents($filepath, $filename);

      if (file_exists($filepath)){
        sig()->text_to_PNG_file($text, $font, $font_color, $background_color, $font_size, $filepath); // call this method from signature class
        $fileurl = $signature_dir_url."/".$filename;
        update_post_meta($id, '_yl_client_ls_signature',  $fileurl);
        update_post_meta($id, '_yl_client_ls_signature_date', $signature_date);

      } else {
          error_log("Cannot create signature file in directory ".$filepath);
      }
    }
    else {
      if (isset($data) && is_string($data) && strrpos($data, "data:image/png;base64", -strlen($data)) !== FALSE){
        $data_pieces = explode(",", $data);
        $encoded_image = $data_pieces[1];
        $decoded_image = base64_decode($encoded_image);
        $upload_dir = wp_upload_dir();
        $signature_dir = $upload_dir['basedir'].$dir;
        $signature_dir_url = $upload_dir['baseurl'].$dir;
        if( ! file_exists( $signature_dir ) ){
          wp_mkdir_p( $signature_dir );
        }
        $filename = time().".png";
        $filepath = $signature_dir."/".$filename;

        if (strlen($decoded_image) > 441 ) {
          file_put_contents( $filepath,$decoded_image);

          if (file_exists($filepath)){
            // File created : changing posted data to the URL instead of base64 encoded image data
            $fileurl = $signature_dir_url."/".$filename;
            update_post_meta($id, '_yl_client_ls_signature',  $fileurl);
            update_post_meta($id, '_yl_client_ls_signature_date', $signature_date);
          }
          else {
              error_log("Cannot create signature file in directory ".$filepath);
          }
        }
      }
    }




		if ( get_post_meta($id, '_yl_client_ls_signature', true) ) {
			$lease_id = $_GET['lid'];
      // Generate lease summary pdf
      yl_generate_summary_pdf($lease_id);

			$client_lease_url  = get_permalink(get_option('yl_client_sign_page'));
      $bm_process = (($_GET['bmprocess']) ? '&bmprocess=1' : '');
			wp_redirect( $client_lease_url."?lid=$lease_id".$bm_process );
			exit;
		}
    else {
			echo 'Please sign the lease before proceeding';
			//return;
		}

  }

	if( isset($_GET['lid']) && $_GET['lid'] != '' ) {
		$lease_id = $_GET['lid'];
		$args = array(
			'post_type' => 'lease',
			'p' => $lease_id,
			'post_status' => 'any'
		);
		$query = new WP_Query( $args );

		ob_start();

		//if($query->have_posts()) :
			//while($query->have_posts()): $query->the_post();
			global $post,  $us_states_full;
	    ?>

      <div class="lease_summary_container">
    	<?php
			if( isset($message) ) {
				echo '<h4>'.$message.'</h4>';
			}
		?>

		<?php
		$product_id = get_post_meta($lease_id, '_yl_product_id', true);
		$prod_post_terms = wp_get_post_terms( $product_id, 'suitestype' );
		?>

    <div class="lease_details">

        <div class="yl_timeline_container">
          <div class="yl_timeline_line step_4">
            <span>
            </span>
          </div>

          <div class="yl_top_section">
            <div class="yl_bm_top_line">
              <div>
                <span>BM</span>

                <div class="yl_unit_block yl_one_third active">
                  <div class="yl_circle">
                  </div>
                  <div class="yl_desc">
                    Search
                  </div>
                </div>

                <div class="yl_unit_block yl_one_third step_client_info active">
                  <div class="yl_circle">
                  </div>
                  <div class="yl_desc">
                    Client<br>Info
                  </div>
                </div>

                <div class="yl_unit_block yl_one_third active">
                  <div class="yl_circle">
                  </div>
                  <div class="yl_desc">
                    Lease<br>Summary
                  </div>
                </div>
              </div>
            </div>
            <div class="yl_client_top_line">
              <div>
                <span>Client</span>

                <div class="yl_unit_block yl_one_third active">
                  <div class="yl_circle">
                  </div>
                  <div class="yl_desc">
                    Lease Summary<br>Client
                  </div>
                </div>

                <div class="yl_unit_block yl_one_third">
                  <div class="yl_circle">
                  </div>
                  <div class="yl_desc">
                    Client Review<br>&amp; Sign Lease
                  </div>
                </div>

                <div class="yl_unit_block yl_one_third">
                  <div class="yl_circle">
                  </div>
                  <div class="yl_desc">
                    Client<br>Payment
                  </div>
                </div>
              </div>
            </div>
            <div class="yl_bm_top_line yl_bm_last_step">
              <div>
                <span>BM</span>

                <div class="yl_unit_block yl_full_width">
                  <div class="yl_circle">
                  </div>
                  <div class="yl_desc">
                    BM<br>Finalize
                  </div>
                </div>
              </div>
            </div>
          </div>

      </div>

    	<h2>Lease Summary</h2>
        <form action="" method="post" class="sigPad2">


            <div class="summary_top row">
              <div class="col-md-4">
                <label for="lessor">Lessor</label>
                <input class="form-control" type="text" id="lessor" name="lessor" value="<?php echo esc_attr(get_post_meta($lease_id, '_yl_lessor', true)); ?>" readonly />
              </div>

              <div class="col-md-4">
                <label for="lessorLocation">Location</label>
                <input class="form-control" type="text" id="lessorLocation" name="lessorLocation" value="<?php echo esc_attr(get_post_meta($lease_id, '_yl_location', true)); ?>" readonly />
              </div>

              <div class="col-md-4">
                <label for="locationPhone">Location Phone Number</label>
                <input class="form-control" type="text" id="locationPhone" name="locationPhone" value="<?php echo esc_attr(get_post_meta($lease_id, '_yl_location_phone_number', true)); ?>" readonly />
              </div>
            </div>

            <div class="row">

            <div class="summary_left col-md-6">

            	<h3>Lessee</h3>
				        <div class="row">
                  <div class="col-md-4">
                    <label for="lessee_first_name">First Name</label>
                    <input class="form-control" type="text" id="lessee_first_name" name="lessee_first_name" value="<?php echo esc_attr(get_post_meta($lease_id, '_yl_l_first_name', true)); ?>" readonly />
                  </div>
                  <div class="col-md-4">
                    <label for="lessee_middle_name">Middle Name</label>
                    <input class="form-control" type="text" id="lessee_middle_name" name="lessee_middle_name" value="<?php echo esc_attr(get_post_meta($lease_id, '_yl_l_middle_name', true)); ?>" readonly />
                  </div>
                  <div class="col-md-4">
                    <label for="lessee_last_name">Last Name</label>
                    <input class="form-control" type="text" id="lessee_last_name" name="lessee_last_name" value="<?php echo esc_attr(get_post_meta($lease_id, '_yl_l_last_name', true)); ?>" readonly />
                  </div>
                </div>

                <div class="row">
                  <div class="col-md-4">
                    <label for="lessee_phone">Phone</label>
                    <input class="form-control" type="text" name="lessee_phone" id="lessee_phone" value="<?php echo esc_attr(get_post_meta($lease_id, '_yl_l_phone', true)); ?>" readonly />
                  </div>
                  <div class="col-md-8">
                    <label for="lessee_email">Email</label>
                    <input class="form-control" type="text" name="lessee_email" id="lessee_email" value="<?php echo esc_attr(get_post_meta($lease_id, '_yl_l_email', true)); ?>" readonly />
                  </div>
                </div>

                <div class="row lessee_address">
                  <div class="col-md-6 lg_address">
                    <label for="lessee_street_address">Address</label>
                    <input class="form-control" type="text" name="lessee_street_address" id="lessee_street_address" value="<?php echo esc_attr(get_post_meta($lease_id, '_yl_l_street_address', true)); ?>" readonly />
                  </div>
                  <div class="col-md-6 lg_address_2">
                    <label for="lessee_address_2">Address Line 2</label>
                    <input class="form-control" type="text" name="lessee_address_2" id="lessee_address_2" value="<?php echo esc_attr(get_post_meta($lease_id, '_yl_l_address_line_2', true)); ?>" readonly />
                  </div>
                </div>

                <div class="row lessee_address">
                  <div class="col-md-5 ls_city">
                    <label for="lessee_city">City</label>
                    <input class="form-control" type="text" name="lessee_city" id="lessee_city" value="<?php echo esc_attr(get_post_meta($lease_id, '_yl_l_city', true)); ?>" readonly />
                  </div>
                  <div class="col-md-4 ls_state">
                    <label for="lessee_state">State</label>
                    <select class="form-control" name="lessee_state" id="lessee_state" disabled="disabled">
                    <?php
                      $lesseeState = get_post_meta($lease_id, '_yl_l_state', true);
                      foreach($us_states_full as $us_state) {
                        if($lesseeState == $us_state) $selected = 'selected="selected"';
                        else $selected = '';
                        echo '<option value="'.$us_state.'" '.$selected.'>'.$us_state.'</option>';
                      }
                    ?>
                    </select>
                    <input type="hidden" name="lessee_state" id="lessee_state" value="<?php echo esc_attr(get_post_meta($lease_id, '_yl_l_state', true)); ?>" />
                  </div>
                  <div class="col-md-3 ls_zip">
                    <label for="lessee_zip_code">ZIP Code</label>
                    <input class="form-control" type="text" name="lessee_zip_code" id="lessee_zip_code" value="<?php echo esc_attr(get_post_meta($lease_id, '_yl_l_zip_code', true)); ?>" readonly />
                  </div>
                </div>

            </div><!-- .summary_left -->

            <div class="summary_right col-md-6">
            	<h3>Guarantor</h3>

              <div class="row">
                <div class="col-md-4">
                  <label for="guarantor_first_name">First Name</label>
                  <input class="form-control" type="text" id="guarantor_first_name" name="guarantor_first_name" value="<?php echo esc_attr(get_post_meta($lease_id, '_yl_g_first_name', true)); ?>" readonly />
                </div>
                <div class="col-md-4">
                  <label for="guarantor_middle_name">Middle Name</label>
                  <input class="form-control" type="text" id="guarantor_middle_name" name="guarantor_middle_name" value="<?php echo esc_attr(get_post_meta($lease_id, '_yl_g_middle_name', true)); ?>" readonly />
                </div>
                <div class="col-md-4">
                  <label for="guarantor_last_name">Last Name</label>
                  <input class="form-control" type="text" id="guarantor_last_name" name="guarantor_last_name" value="<?php echo esc_attr(get_post_meta($lease_id, '_yl_g_last_name', true)); ?>" readonly />
                </div>
              </div>

              <div class="row">
                <div class="col-md-4">
                  <label for="guarantor_phone">Phone</label>
                  <input class="form-control" type="text" name="guarantor_phone" id="guarantor_phone" value="<?php echo esc_attr(get_post_meta($lease_id, '_yl_g_phone', true)); ?>" readonly />
                </div>
                <div class="col-md-8">
                  <label for="guarantor_email">Email</label>
                  <input class="form-control" type="text" name="guarantor_email" id="guarantor_email" value="<?php echo esc_attr(get_post_meta($lease_id, '_yl_g_email', true)); ?>" readonly />
                </div>
              </div>

               <div class="row guar_address">
                <div class="col-md-6 lg_address">
                  <label for="guarantor_street_address">Address</label>
                  <input class="form-control" type="text" name="guarantor_street_address" id="guarantor_street_address" value="<?php echo esc_attr(get_post_meta($lease_id, '_yl_g_street_address', true)); ?>" readonly />
                </div>
                <div class="col-md-6 lg_address_2">
                  <label for="guarantor_address_2">Address Line 2</label>
                  <input class="form-control" type="text" name="guarantor_address_2" id="guarantor_address_2" value="<?php echo esc_attr(get_post_meta($lease_id, '_yl_g_address_line_2', true)); ?>" readonly />
                </div>
               </div>

               <div class="row guar_address">
                <div class="col-md-5 lg_city">
                  <label for="guarantor_city">City</label>
                  <input class="form-control" type="text" name="guarantor_city" id="guarantor_city" value="<?php echo esc_attr(get_post_meta($lease_id, '_yl_g_city', true)); ?>" readonly />
                </div>
                <div class="col-md-4 lg_state">
                  <label for="guarantor_state">State</label>
                  <select class="form-control" name="guarantor_state" id="guarantor_state" disabled="disabled">
                  <?php
                    $guarantorState = get_post_meta($lease_id, '_yl_g_state', true);
                    foreach($us_states_full as $us_state) {
                      if($guarantorState == $us_state) $selected = 'selected="selected"';
                      else $selected = '';
                      echo '<option value="'.$us_state.'" '.$selected.'>'.$us_state.'</option>';
                    }
                  ?>
                  </select>
                  <input type="hidden" name="guarantor_state" id="guarantor_state" value="<?php echo esc_attr(get_post_meta($lease_id, '_yl_g_state', true)); ?>" />
                </div>
                <div class="col-md-3 lg_zip">
                  <label for="guarantor_zip_code">ZIP Code</label>
                  <input class="form-control" type="text" name="guarantor_zip_code" id="guarantor_zip_code" value="<?php echo esc_attr(get_post_meta($lease_id, '_yl_g_zip_code', true)); ?>" readonly />
                </div>

               </div>

            </div><!-- .summary_right -->

          </div><!-- /row -->



          <div class="row lease_bottom_info">
            <div class="col-md-2">
              <label for="company_name">Company Name</label>
              <input class="form-control" type="text" name="company_name" id="company_name" value="<?php echo esc_attr(get_the_title(get_post_meta($lease_id, '_yl_company_name', true))); ?>" readonly />
            </div>

            <div class="col-md-2">
              <label for="company_type">Company Type</label>
              <?php
              $this_company_type = get_post_meta($lease_id, '_yl_company_type', true);
              $c_type = get_term( $this_company_type, 'companytype' );
              ?>
              <input class="form-control" type="text" name="company_type" id="company_type" value="<?php echo esc_attr($c_type->name); ?>" readonly />
            </div>

            <div class="col-md-2">
              <label for="suite_number">Suite Number</label>
              <input class="form-control" type="text" name="suite_number" id="suite_number" value="<?php echo esc_attr(get_post_meta($lease_id, '_yl_suite_number', true)); ?>" readonly />
            </div>

            <div class="col-md-2">
              <label for="lease_start_date">Lease Start Date</label>
              <div class="input-group">
                <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
                <input class="form-control" type="text" name="lease_start_date" id="lease_start_date" value="<?php echo esc_attr(get_post_meta($lease_id, '_yl_lease_start_date', true)); ?>" readonly />
              </div>
            </div>

            <div class="col-md-2">
              <label for="first_month_rent_rate">First Month Due</label>
              <div class="input-group">
                <div class="input-group-addon">$</div>
                <input class="form-control" type="text" name="first_month_rent_rate" id="first_month_rent_rate" value="<?php echo esc_attr(get_post_meta($lease_id, '_yl_first_month_rent_rate', true)); ?>" readonly />
              </div>
            </div>

            <div class="col-md-2">
              <label for="monthly_rent">Monthly Recurring Rate</label>
              <div class="input-group">
                <div class="input-group-addon">$</div>
                <input class="form-control" type="text" name="monthly_rent" id="monthly_rent" value="<?php echo esc_attr(get_post_meta($lease_id, '_yl_monthly_rent', true)); ?>" readonly />
              </div>
            </div>

            <div class="lease_additional_info clear">
              <div class="col-md-2">
                <label for="security_deposit">Security Deposit</label>
                <div class="input-group">
                  <div class="input-group-addon">$</div>
                  <input class="form-control" type="text" name="security_deposit" id="security_deposit" value="<?php echo esc_attr(get_post_meta($lease_id, '_yl_security_deposit', true)); ?>" readonly />
                </div>
              </div>

              <div class="col-md-2">
                <label for="vacate_notice">Vacate Notice</label>
								<?php
										if(get_post_meta($lease_id, '_yl_vacate_notice', true)) {
												$vacate_notice = get_post_meta($lease_id, '_yl_vacate_notice', true);
										}
										else {
												if (($prod_post_terms[0]->slug == 'storage') || (get_post_meta($lease_id, '_yl_product_id', true) == -1)) {
													$vacate_notice = get_option('yl_storage_vacate_notice');
												}
												else {
													$vacate_notice = get_option('yl_vacate_notice');
												}
										}
								?>
                <input class="form-control" type="text" name="vacate_notice" id="vacate_notice" value="<?php echo esc_attr($vacate_notice); ?>" readonly />
              </div>

              <div class="col-md-2">
                <label for="promotional_code">Promotional Code</label>
                <div class="input-group">
                  <div class="input-group-addon"><i class="fa fa-ticket"></i></div>
                  <input class="form-control" type="text" name="promotional_code" id="promotional_code" value="<?php echo esc_attr(get_post_meta($lease_id, '_yl_promotional_code', true)); ?>" readonly />
                </div>
              </div>

              <div class="col-md-2">
                <label for="service_fees">Service Fees</label>
                <div class="input-group">
                  <div class="input-group-addon">$</div>
                  <input class="form-control" type="text" name="service_fees" id="service_fees" value="<?php echo esc_attr(get_post_meta($lease_id, '_yl_service_fees', true)); ?>" readonly />
                </div>
              </div>

              <div class="col-md-2">
                <label for="phone_fee">Phone Fee</label>
                <div class="input-group">
                  <div class="input-group-addon">$</div>
                  <input class="form-control" type="text" name="phone_fee" id="phone_fee" value="<?php echo esc_attr(get_post_meta($lease_id, '_yl_phone_fee', true)); ?>" readonly />
                </div>
              </div>

              <div class="col-md-2">
                <label for="multi_suite_discount">Multi Suite Discount</label>
                <div class="input-group">
                  <div class="input-group-addon">$</div>
                  <input class="form-control" type="text" name="multi_suite_discount" id="multi_suite_discount" value="<?php echo esc_attr(get_post_meta($lease_id, '_yl_multi_suite_discount', true)); ?>" readonly /><!--<input type="checkbox" name="yl_multisite_coupon" id="yl_multisite_coupon" value="<?php //echo get_option('yl_multisite_coupon'); ?>" style="width: auto;" <?php //if(get_post_meta($lease_id, '_yl_multisite_coupon', true)) echo 'checked'; ?> />-->
                </div>
              </div>
            </div>

                <!--<p>
                    <label for="lease_term">Lease Term</label>
                    <input type="text" name="lease_term" id="lease_term" value="<?php //echo get_post_meta($lease_id, '_yl_lease_term', true); ?>" readonly="readonly" />
				</p>-->


            <div class="col-md-4">
              <label for="addendums">Addendums</label>
              <input class="form-control" type="text" name="addendums" id="addendums" value="<?php echo esc_attr(get_post_meta($lease_id, '_yl_addendums', true)); ?>" readonly />
            </div>
			</div>



			<div class="signature_info clear row">

        <hr />

        <div class="col-md-6">
          <label for="date">Date</label>
          <?php
            if(get_post_meta($lease_id, '_yl_client_ls_signature_date', true)) {
              $signature_date = get_post_meta($lease_id, '_yl_client_ls_signature_date', true);
            }
            else {
              $signature_date = date('Y-m-d');
            }
          ?>
          <input type="text" class="leasedatepicker" name="date" id="date" value="<?php echo esc_attr($signature_date); ?>" />
        </div>

        <div class="col-md-6 text-right">
          <div class="sign_fields">
            <p class="drawItDesc">Type <input type="radio" checked="checked" name="sig_type" id="sig_type" value="yes"/> Or Draw <input type="radio" name="sig_type" id="sig_draw" value="No" />your signature</p><!--<br/>-->

            <p><input type="text" name="keypad_sig" id="keypad_sig" value="" class="keypad_sig_input" placeholder="Type your signature here"/></p>

            <div class="draw_wrap" style="display:none;">
              <ul class="sigNav">
                <li class="drawIt"><a href="#draw-it">Draw It</a></li>
                <li class="clearButton"><a href="#clear">Clear</a></li>
              </ul>
              <div class="sig sigWrapper sigPad2_canvas">
                <canvas class="pad signature_pad" width="340" height="100"></canvas>

                <input type="hidden" name="imgOutput2" class="imgOutput2" value="">

              </div>
            </div>
          </div>

          <?php
          $sig = get_post_meta($lease_id, '_yl_client_ls_signature', true);
          if($sig) {
            ?>
            <div class="sig_out">
              <img src="<?php echo $sig; ?>" alt="Signature"/>
            </div>
          <?php
          }
          ?>
        </div>
      </div>

	<div class="row lease_buttons">
		<div class="col-md-6 text-left">
			<?php
			$previous_link = get_permalink(get_option('yl_lease_summary_page')).'?lid='.$_GET['lid'];
			?>
		  <a href="<?php echo $previous_link; ?>" class="ls_submit ls_button_back">Back</a>
		</div>
		<div class="col-md-6 text-right">
		  <input type="submit" name="ls_submit" id="ls_submit" value="Submit" />
		</div>
	</div>


    </div>
  </form>
</div><!-- .lease_summary_container -->

<?php
//endwhile;
wp_reset_postdata();
?>
<script type="text/javascript">

    // Type Or Draw
    jQuery(document).ready(function() {

      jQuery("#sig_type").click(function() {
        if(jQuery(this).prop("checked") == true ) {
          jQuery("#keypad_sig").show();
           jQuery(".draw_wrap").hide();
        }
      });

      jQuery("#sig_draw").click(function() {
        if(jQuery(this).prop("checked") == true ) {
          jQuery(".draw_wrap").show();
           jQuery("#keypad_sig").hide();
        }
      });

    });

    jQuery('input').not('.keypad_sig_input').focus(function() {
        jQuery(this).blur();
    });

</script>


<?php
		//endif;
		$yl_lease_sign = ob_get_contents();
		ob_end_clean();
	}

	return $yl_lease_sign;
}
add_shortcode('lease-client-sign','yl_lease_client_sign_shortcode');





/*******************************
 * send_email for search result
*******************************/
/*add_action('wp_ajax_show-available-suites', 'yl_show_available_suites');
add_action('wp_ajax_nopriv_show-available-suites', 'yl_show_available_suites');*/
