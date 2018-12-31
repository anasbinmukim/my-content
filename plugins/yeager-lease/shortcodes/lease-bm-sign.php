<?php
function yl_lease_bm_sign_shortcode($atts, $content = null) {
	extract(shortcode_atts(array(
		'lease_id' => '',
	), $atts));

	if ( !is_user_logged_in() ){
		wp_login_form();
		return;
	}

	if(!current_user_can( 'building_manager' )){
		echo "Only Building Manager are able to sign lease. Please login.";
		return;
	}

    if( isset($_POST['ls_submit']) ) {

      // signature convert an image and send to the wp signature directory
          $dir = "/signatures";
          $id = $_GET['lid'];
          $data = $_POST['imgOutput2'];
          $keypad_sig = $_POST['keypad_sig'];

        // convert text to png image

        if($keypad_sig) {

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
              update_post_meta($id, '_yl_bm_signature',  $fileurl);

            } else {
                error_log("Cannot create signature file in directory ".$filepath);
            }

          }elseif (isset($data) && is_string($data) && strrpos($data, "data:image/png;base64", -strlen($data)) !== FALSE){
		  	// convert canvas to png image
              $data_pieces = explode(",", $data);
              $encoded_image = $data_pieces[1];
              $decoded_image = base64_decode($encoded_image);
              $upload_dir = wp_upload_dir();
              $signature_dir = $upload_dir['basedir'].$dir;
              $signature_dir_url = $upload_dir['baseurl'].$dir;
              if( ! file_exists( $signature_dir ) ){
              wp_mkdir_p( $signature_dir );
             }
             $filename = $key."-".time().".png";
             $filepath = $signature_dir."/".$filename;

            if (strlen($decoded_image) > 441 ) {
            file_put_contents( $filepath,$decoded_image);

            if (file_exists($filepath)){
              // File created : changing posted data to the URL instead of base64 encoded image data
              $fileurl = $signature_dir_url."/".$filename;
              update_post_meta($id, '_yl_bm_signature',  $fileurl);

            } else {
                error_log("Cannot create signature file in directory ".$filepath);
            }
           }
        }

        $date = $_POST['date'];
        update_post_meta($id, '_yl_bm_signature_date', $date);

		if( get_post_meta($id, '_yl_bm_signature', true) ) {
			$post_args = array(
						'ID'           => $id,
						'post_status'   => 'publish'
					);
			wp_update_post( $post_args );

			// generate the PDF
			yl_generate_pdf($id);
			yl_generate_summary_pdf($id);

			echo 'PDF generated for this lease.';
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
			global $post,  $us_states_full;
			//while($query->have_posts()): $query->the_post();
	?>
	<?php
	$product_id = get_post_meta($lease_id, '_yl_product_id', true);
	$prod_post_terms = wp_get_post_terms( $product_id, 'suitestype' );
	?>
	<div class="lease_summary_container">
    	<?php
			if( isset($message) ) {
				echo '<h4>'.$message.'</h4>';
			}
		?>
        <div class="lease_details">
    	<h2>Lease Summary</h2>
        <form action="" method="post" class="sigPad2">
            <div class="summary_left">
                <p>
                    <label for="lessor">Lessor</label>
                    <input type="text" id="lessor" name="lessor" value="<?php echo esc_attr(get_post_meta($lease_id, '_yl_lessor', true)); ?>" readonly="readonly" />
                </p>
                <p>
                    <label for="lessorLocation">Location</label>
                    <input type="text" id="lessorLocation" name="lessorLocation" value="<?php echo esc_attr(get_post_meta($lease_id, '_yl_location', true)); ?>" readonly="readonly" />
                </p>
                <p>
                    <label for="locationPhone">Location Phone Number</label>
                    <input type="text" id="locationPhone" name="locationPhone" value="<?php echo esc_attr(get_post_meta($lease_id, '_yl_location_phone_number', true)); ?>" readonly="readonly" />
                </p>

                <hr />

            	<h3>Lessee</h3>
				<p>
                    <label for="lessee_first_name">First Name</label>
                    <input type="text" id="lessee_first_name" name="lessee_first_name" value="<?php echo esc_attr(get_post_meta($lease_id, '_yl_l_first_name', true)); ?>" readonly="readonly" />
                </p>
                <p>
                    <label for="lessee_middle_name">Middle Name</label>
                    <input type="text" id="lessee_middle_name" name="lessee_middle_name" value="<?php echo esc_attr(get_post_meta($lease_id, '_yl_l_middle_name', true)); ?>" readonly="readonly" />
                </p>
                <p>
                    <label for="lessee_last_name">Last Name</label>
                    <input type="text" id="lessee_last_name" name="lessee_last_name" value="<?php echo esc_attr(get_post_meta($lease_id, '_yl_l_last_name', true)); ?>" readonly="readonly" />
                </p>                <p>
                    <label for="lessee_phone">Phone</label>
                	<input type="text" name="lessee_phone" id="lessee_phone" value="<?php echo esc_attr(get_post_meta($lease_id, '_yl_l_phone', true)); ?>" readonly="readonly" />
                </p>
                <p>
                    <label for="lessee_email">Email</label>
                	<input type="text" name="lessee_email" id="lessee_email" value="<?php echo esc_attr(get_post_meta($lease_id, '_yl_l_email', true)); ?>" readonly="readonly" />
                </p>
                <p class="lessee_address">
					<span class="lg_address">
						<label for="lessee_street_address">Address</label>
						<input type="text" name="lessee_street_address" id="lessee_street_address" value="<?php echo esc_attr(get_post_meta($lease_id, '_yl_l_street_address', true)); ?>" readonly="readonly" />
					</span>
					<span class="lg_address_2">
						<label for="lessee_address_2">Address Line 2</label>
						<input type="text" name="lessee_address_2" id="lessee_address_2" value="<?php echo esc_attr(get_post_meta($lease_id, '_yl_l_address_line_2', true)); ?>" readonly="readonly" />
					</span>
					<span class="lg_city">
						<label for="lessee_city">City</label>
						<input type="text" name="lessee_city" id="lessee_city" value="<?php echo esc_attr(get_post_meta($lease_id, '_yl_l_city', true)); ?>" readonly="readonly" />
					</span>
					<span class="lg_state">
						<label for="lessee_state">State</label>
						<select name="lessee_state" id="lessee_state" disabled="disabled">
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
					</span>
					<span class="lg_zip">
						<label for="lessee_zip_code">ZIP Code</label>
						<input type="text" name="lessee_zip_code" id="lessee_zip_code" value="<?php echo esc_attr(get_post_meta($lease_id, '_yl_l_zip_code', true)); ?>" readonly="readonly" />
					</span>
				</p>
            </div><!-- .summary_left -->

            <div class="summary_right">
            	<h3>Guarantor</h3>
                <p>
                    <label for="guarantor_first_name">First Name</label>
                    <input type="text" id="guarantor_first_name" name="guarantor_first_name" value="<?php echo esc_attr(get_post_meta($lease_id, '_yl_g_first_name', true)); ?>" readonly="readonly" />
                </p>
                <p>
                    <label for="guarantor_middle_name">Middle Name</label>
                    <input type="text" id="guarantor_middle_name" name="guarantor_middle_name" value="<?php echo esc_attr(get_post_meta($lease_id, '_yl_g_middle_name', true)); ?>" readonly="readonly" />
                </p>
                <p>
                    <label for="guarantor_last_name">Last Name</label>
                    <input type="text" id="guarantor_last_name" name="guarantor_last_name" value="<?php echo esc_attr(get_post_meta($lease_id, '_yl_g_last_name', true)); ?>" readonly="readonly" />
                </p>
                <p>
                    <label for="guarantor_phone">Phone</label>
                	<input type="text" name="guarantor_phone" id="guarantor_phone" value="<?php echo esc_attr(get_post_meta($lease_id, '_yl_g_phone', true)); ?>" readonly="readonly" />
                </p>
                <p>
                    <label for="guarantor_email">Email</label>
                	<input type="text" name="guarantor_email" id="guarantor_email" value="<?php echo esc_attr(get_post_meta($lease_id, '_yl_g_email', true)); ?>" readonly="readonly" />
                </p>
                <p class="guar_address">
					<span class="lg_address">
						<label for="guarantor_street_address">Address</label>
						<input type="text" name="guarantor_street_address" id="guarantor_street_address" value="<?php echo esc_attr(get_post_meta($lease_id, '_yl_g_street_address', true)); ?>" readonly="readonly" />
					</span>
					<span class="lg_address_2">
						<label for="guarantor_address_2">Address Line 2</label>
						<input type="text" name="guarantor_address_2" id="guarantor_address_2" value="<?php echo esc_attr(get_post_meta($lease_id, '_yl_g_address_line_2', true)); ?>" readonly="readonly" />
					</span>
					<span class="lg_city">
						<label for="guarantor_city">City</label>
						<input type="text" name="guarantor_city" id="guarantor_city" value="<?php echo esc_attr(get_post_meta($lease_id, '_yl_g_city', true)); ?>" readonly="readonly" />
					</span>
					<span class="lg_state">
						<label for="guarantor_state">State</label>
						<select name="guarantor_state" id="guarantor_state" disabled="disabled">
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
				</span>
					<span class="lg_zip">
						<label for="guarantor_zip_code">ZIP Code</label>
						<input type="text" name="guarantor_zip_code" id="guarantor_zip_code" value="<?php echo esc_attr(get_post_meta($lease_id, '_yl_g_zip_code', true)); ?>" readonly="readonly" />
					</span>
				</p>

                <hr />

            </div><!-- .summary_right -->

            <div class="lease_bottom_info">
                <p>
                    <label for="company_name">Company Name</label>
                    <input type="text" name="company_name" id="company_name" value="<?php echo esc_attr(get_the_title(get_post_meta($lease_id, '_yl_company_name', true))); ?>" readonly="readonly" />
				</p>
                <p>
                    <label for="company_type">Company Type</label>
                    <?php
                    	$c_type = get_term( get_post_meta($lease_id, '_yl_company_type', true), 'companytype' );
					?>
                    <input type="text" name="company_type" id="company_type" value="<?php echo esc_attr($c_type->name); ?>" readonly="readonly" />
				</p>
                <p>
                    <label for="suite_number">Suite Number</label>
                    <input type="text" name="suite_number" id="suite_number" value="<?php echo esc_attr(get_post_meta($lease_id, '_yl_suite_number', true)); ?>" readonly="readonly" />
				</p>
                <p>
                    <label for="lease_start_date">Lease Start Date</label>
                    <input type="text" name="lease_start_date" id="lease_start_date" value="<?php echo esc_attr(get_post_meta($lease_id, '_yl_lease_start_date', true)); ?>" readonly="readonly" />
				</p>
                <p>
                    <label for="first_month_rent_rate">First Month Rent Rate</label>
                    <input type="text" name="first_month_rent_rate" id="first_month_rent_rate" value="<?php echo esc_attr(get_post_meta($lease_id, '_yl_first_month_rent_rate', true)); ?>" readonly="readonly" />
				</p>
                <p>
                    <label for="monthly_rent">Monthly Recurring Rent Rate</label>
                    <input type="text" name="monthly_rent" id="monthly_rent" value="<?php echo esc_attr(get_post_meta($lease_id, '_yl_monthly_rent', true)); ?>" readonly="readonly" />
				</p>
                <p>
                    <label for="security_deposit">Security Deposit</label>
                    <input type="text" name="security_deposit" id="security_deposit" value="<?php echo esc_attr(get_post_meta($lease_id, '_yl_security_deposit', true)); ?>" readonly="readonly" />
				</p>
                <!--<p>
                    <label for="lease_term">Lease Term</label>
                    <input type="text" name="lease_term" id="lease_term" value="<?php //echo get_post_meta($lease_id, '_yl_lease_term', true); ?>" readonly="readonly" />
				</p>-->
                <p>
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
                    <input type="text" name="vacate_notice" id="vacate_notice" value="<?php echo esc_attr($vacate_notice); ?>" readonly="readonly" />
				</p>
                <p>
                    <label for="promotional_code">Promotional Code</label>
                    <input type="text" name="promotional_code" id="promotional_code" value="<?php echo esc_attr(get_post_meta($lease_id, '_yl_promotional_code', true)); ?>" readonly="readonly" />
				</p>
                <p>
                    <label for="service_fees">Service Fees</label>
                    <input type="text" name="service_fees" id="service_fees" value="<?php echo esc_attr(get_post_meta($lease_id, '_yl_service_fees', true)); ?>" readonly="readonly" />
				</p>
                <p>
                    <label for="multi_suite_discount">Multi Suite Discount</label>
                    <input type="text" name="multi_suite_discount" id="multi_suite_discount" value="<?php echo esc_attr(get_post_meta($lease_id, '_yl_multi_suite_discount', true)); ?>" readonly="readonly" />
				</p>
                <p>
                    <label for="addendums">Addendums</label>
                    <input type="text" name="addendums" id="addendums" value="<?php echo esc_attr(get_post_meta($lease_id, '_yl_addendums', true)); ?>" readonly="readonly" />
				</p>
            </div>

            <div class="signature_info clear">
                <p style="float: left; width: 20%;">
                    <label for="date">Date</label>
					<?php
						if(get_post_meta($lease_id, '_yl_bm_signature_date', true))
							$signature_date = get_post_meta($lease_id, '_yl_bm_signature_date', true);
						else
							$signature_date = date('Y-m-d');
					?>
                    <input type="text" class="leasedatepicker" name="date" id="date" value="<?php echo esc_attr($signature_date); ?>" />
				</p>
                <!--<p>-->

                <div class="sign_fields" style="float: left; width: 40%;">
                     <p class="drawItDesc">Type <input type="radio" checked="checked" name="sig_type" id="sig_type" value="yes"/> Or Draw <input type="radio" name="sig_type" id="sig_draw" value="No" />your signature</p><!--<br/>-->

                      <p><input type="text" name="keypad_sig" id="keypad_sig" value="" placeholder="Type your signature here"/></p>

                    <div class="draw_wrap" style="display:none;">
                      <ul class="sigNav">
                        <li class="drawIt"><a href="#draw-it">Draw It</a></li>
                        <li class="clearButton"><a href="#clear">Clear</a></li>
                      </ul>
                      <div class="sig sigWrapper">
                        <canvas class="pad" width="198" height="55"></canvas>

                        <input type="hidden" name="imgOutput2" class="imgOutput2" value="">

                      </div>
                    </div>
                </div>
                <?php
                $sig = get_post_meta($lease_id, '_yl_bm_signature', true);
                if($sig) {
                    ?>
                    <div class="sig_out">
                    <img src="<?php echo $sig; ?>" alt="Signature"/>
                    </div>
                <?php
                }
            ?>
            <!--</p>-->

                <!--<p>

				</p>
                <br/><br/><br/>-->
                <p class="lease_buttons">
                	<input type="submit" name="ls_submit" id="ls_submit" value="Approve Now" />
                </p>
			</div>
        </form>
        </div>
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

</script>
<?php
		//endif;
		$yl_lease_sign = ob_get_contents();
		ob_end_clean();
	}

	return $yl_lease_sign;
}
add_shortcode('lease-bm-sign','yl_lease_bm_sign_shortcode');
