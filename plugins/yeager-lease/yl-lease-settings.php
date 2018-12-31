<?php
add_action('admin_menu', 'register_lease_settings_page');
function register_lease_settings_page() {
	add_submenu_page( 'edit.php?post_type=lease', 'Lease Settings', 'Settings', 'edit_posts', 'lease-settings', 'lease_settings_page_callback' );
	add_submenu_page( 'edit.php?post_type=lease', 'Lease Summary', 'Lease Summary', 'edit_posts', 'lease-summary', 'lease_summary_settings_page_callback' );
}

function lease_settings_page_callback() {
if(isset($_POST['yl_settings_options_submit'])){
	
	update_option( 'yl_lessor', $_POST['yl_lessor'] );
	update_option( 'yl_location', $_POST['yl_location'] );
	update_option( 'yl_location_phone', $_POST['yl_location_phone'] );
	update_option( 'yl_accountant_email', $_POST['yl_accountant_email'] );
	update_option( 'yl_lease_search_page', $_POST['select_page_search'] );
	update_option( 'yl_lease_summary_page', $_POST['select_page_summary'] );
	update_option( 'yl_client_sign_page', $_POST['select_page_client_sign'] );
	update_option( 'yl_summary_sign_page', $_POST['select_page_summary_sign'] );
	update_option( 'yl_vacate_notice_page', $_POST['select_page_vacate_notice_page'] );
	update_option( 'yl_thank_you_page', $_POST['select_page_thank_you_page'] );
	update_option( 'yl_lease_checkout_page', $_POST['select_page_checkout'] );
	update_option( 'yl_tenant_information_page', $_POST['select_page_tenant_information'] );
	update_option( 'yl_bm_sign_page', $_POST['select_page_bm_sign'] );	
	update_option( 'yl_bm_summary_sign_page', $_POST['select_page_bm_summary_sign'] );	
	update_option( 'yl_vacate_notice', $_POST['yl_vacate_notice'] );
	update_option( 'yl_storage_vacate_notice', $_POST['yl_storage_vacate_notice'] );
	update_option( 'yl_service_fees', $_POST['yl_service_fees'] );
	update_option( 'yl_phone_fee', $_POST['yl_phone_fee'] );

	update_option( 'yl_cable_fee', $_POST['yl_cable_fee'] );
	update_option( 'yl_ipservice_fee', $_POST['yl_ipservice_fee'] );
	update_option( 'yl_fax_fee', $_POST['yl_fax_fee'] );
	update_option( 'yl_postage_fee', $_POST['yl_postage_fee'] );
	update_option( 'yl_credit_card_line_fee', $_POST['yl_credit_card_line_fee'] );

	update_option( 'yl_multisite_coupon', $_POST['yl_multisite_coupon'] );	
	update_option( 'yl_multisite_discount', $_POST['yl_multisite_discount'] );
	update_option( 'yl_multisite_discount_3', $_POST['yl_multisite_discount_3'] );
	update_option( 'yl_multisite_discount_4', $_POST['yl_multisite_discount_4'] );
	update_option( 'yl_multisite_discount_5', $_POST['yl_multisite_discount_5'] );	
	update_option( 'yl_y_membership_monthly_rate', $_POST['yl_y_membership_monthly_rate'] );	
	update_option( 'yl_y_membership_deposit', $_POST['yl_y_membership_deposit'] );	
	update_option( 'yl_rate_increase_default_message', $_POST['yl_rate_increase_default_message'] );	


	update_option( 'yl_category_id_phone', $_POST['yl_category_id_phone'] );
	update_option( 'yl_category_id_improvements', $_POST['yl_category_id_improvements'] );
	update_option( 'yl_category_id_fees', $_POST['yl_category_id_fees'] );
	update_option( 'yl_category_id_copies', $_POST['yl_category_id_copies'] );
	update_option( 'yl_category_id_discounts', $_POST['yl_category_id_discounts'] );
	update_option( 'yl_category_id_longdistance', $_POST['yl_category_id_longdistance'] );
	update_option( 'yl_category_id_postage', $_POST['yl_category_id_postage'] );
	update_option( 'yl_category_id_rent', $_POST['yl_category_id_rent'] );
	update_option( 'yl_category_id_deposit', $_POST['yl_category_id_deposit'] );
	update_option( 'yl_category_id_oldinvoices', $_POST['yl_category_id_oldinvoices'] );
		
	
	echo "<div class='updated'><p>Successfully Updated</p></div>";
}
?>
<div class="wrap">
<h2><?php echo __('Lease Settings'); ?></h2>
<form name="yl_settings" method="post" action="edit.php?post_type=lease&page=lease-settings">
	<table class="form-table">

		<tr valign="top">
			<th scope="row"><?php echo __('Lessor'); ?></th>
			<td style="padding-top:0;">
				<span class="dashicons dashicons-admin-users"></span> <input type="text" name="yl_lessor" value="<?php echo get_option('yl_lessor'); ?>" />
			</td>
		</tr>

		<tr valign="top">
			<th scope="row" style="padding-top:0;"><?php echo __('Location'); ?></th>
			<td style="padding-top:0;">
				<span class="dashicons dashicons-location"></span> <input type="text" name="yl_location" class="regular-text" value="<?php echo get_option('yl_location'); ?>" />
			</td>
		</tr>	

		<tr valign="top">
			<th scope="row" style="padding-top:0;"><?php echo __('Location Phone Number'); ?></th>
			<td style="padding-top:0;">
				<span class="dashicons dashicons-phone"></span> <input type="text" name="yl_location_phone" value="<?php echo get_option('yl_location_phone'); ?>" />
			</td>
		</tr>	

		<tr><th colspan="2"><h3>Accountant</h3></th></tr>

		<tr valign="top">
			<th scope="row" style="padding-top:0;"><?php echo __('Accountant Email'); ?></th>
			<td style="padding-top:0;">
				<input type="text" name="yl_accountant_email" class="regular-text" value="<?php echo get_option('yl_accountant_email'); ?>" />
			</td>
		</tr>

		<tr><th colspan="2"><h3>Pages</h3></th></tr>

		<tr valign="top">
			<th scope="row" style="padding-top:0;">1. / 2. <?php echo __('Search Page'); ?></th>
			<td style="padding-top:0;">
				<?php yl_post_select_field('page', 'search', get_option('yl_lease_search_page')); ?>
			</td>
		</tr>		

		<tr valign="top">
			<th scope="row" style="padding-top:0;">3. <?php echo __('BM Summary Page'); ?></th>
			<td style="padding-top:0;">
				<?php yl_post_select_field('page', 'summary', get_option('yl_lease_summary_page')); ?>
			</td>
		</tr>	

		<tr valign="top">
			<th scope="row" style="padding-top:0;">4. <?php echo __('Client Summary Sign Page'); ?></th>
			<td style="padding-top:0;">
				<?php yl_post_select_field('page', 'summary_sign', get_option('yl_summary_sign_page')); ?>
			</td>
		</tr>		

		<tr valign="top">
			<th scope="row" style="padding-top:0;">5. <?php echo __('Client Lease Sign Page'); ?></th>
			<td style="padding-top:0;">
				<?php yl_post_select_field('page', 'client_sign', get_option('yl_client_sign_page')); ?>
			</td>
		</tr>	

		<tr valign="top">
			<th scope="row" style="padding-top:0;">6. <?php echo __('Client Checkout'); ?></th>
			<td style="padding-top:0;">
				<?php yl_post_select_field('page', 'checkout', get_option('yl_lease_checkout_page')); ?>
			</td>
		</tr>	

		<tr valign="top">
			<th scope="row" style="padding-top:0;">7. <?php echo __('Client Tenant Information'); ?></th>
			<td style="padding-top:0;">
				<?php yl_post_select_field('page', 'tenant_information', get_option('yl_tenant_information_page')); ?>
			</td>
		</tr>	

		<tr valign="top">
			<th scope="row" style="padding-top:0;">8. <?php echo __('BM Lease Sign Page'); ?></th>
			<td style="padding-top:0;">
				<?php yl_post_select_field('page', 'bm_sign', get_option('yl_bm_sign_page')); ?>
			</td>
		</tr>

		<tr valign="top">
			<th scope="row" style="padding-top:0;"><?php echo __('BM Summary Sign Page'); ?></th>
			<td style="padding-top:0;">
				<?php yl_post_select_field('page', 'bm_summary_sign', get_option('yl_bm_summary_sign_page')); ?>
			</td>
		</tr>		

		<tr valign="top">
			<th scope="row"><?php echo __('Suite Vacate Notice Default'); ?></th>
			<td style="padding-top:0;">
				<input type="text" name="yl_vacate_notice" value="<?php echo get_option('yl_vacate_notice'); ?>" />
			</td>
		</tr>

		<tr valign="top">
			<th scope="row"><?php echo __('Storage Vacate Notice Default'); ?></th>
			<td style="padding-top:0;">
				<input type="text" name="yl_storage_vacate_notice" value="<?php echo get_option('yl_storage_vacate_notice'); ?>" />
			</td>
		</tr>

		<tr valign="top">
			<th scope="row" style="padding-top:0;"><?php echo __('Vacate Notice Page'); ?></th>
			<td style="padding-top:0;">
				<?php yl_post_select_field('page', 'vacate_notice_page', get_option('yl_vacate_notice_page')); ?>
			</td>
		</tr>

		<tr valign="top">
			<th scope="row" style="padding-top:0;"><?php echo __('Thank You Page'); ?></th>
			<td style="padding-top:0;">
				<?php yl_post_select_field('page', 'thank_you_page', get_option('yl_thank_you_page')); ?>
			</td>
		</tr>

		<tr><th colspan="2"><h3>Fees and Discounts</h3></th></tr>

		<tr valign="top">
			<th scope="row"><?php echo __('Service Fees'); ?></th>
			<td style="padding-top:0;">
				$<input type="text" name="yl_service_fees" value="<?php echo get_option('yl_service_fees'); ?>" />
                <br />
                <small>Service Fees (fixed price)</small>
			</td>
		</tr>

		<tr valign="top">
			<th scope="row"><?php echo __('Phone Service'); ?></th>
			<td style="padding-top:0;">
				$<input type="text" name="yl_phone_fee" value="<?php echo get_option('yl_phone_fee'); ?>" />
                <br />
                <small>Phone Fee (fixed price)</small>
			</td>
		</tr>

		<tr valign="top">
			<th scope="row"><?php echo __('Cable Service'); ?></th>
			<td style="padding-top:0;">
				$<input type="text" name="yl_cable_fee" value="<?php echo get_option('yl_cable_fee'); ?>" />
                <br />
                <small>Cable Fee (fixed price)</small>
			</td>
		</tr>

		<tr valign="top">
			<th scope="row"><?php echo __('IP Service'); ?></th>
			<td style="padding-top:0;">
				$<input type="text" name="yl_ipservice_fee" value="<?php echo get_option('yl_ipservice_fee'); ?>" />
                <br />
                <small>IP Service Fee (fixed price)</small>
			</td>
		</tr>

		<tr valign="top">
			<th scope="row"><?php echo __('Fax Service'); ?></th>
			<td style="padding-top:0;">
				$<input type="text" name="yl_fax_fee" value="<?php echo get_option('yl_fax_fee'); ?>" />
                <br />
                <small>Fax Fee (fixed price)</small>
			</td>
		</tr>

		<tr valign="top">
			<th scope="row"><?php echo __('Postage Service'); ?></th>
			<td style="padding-top:0;">
				$<input type="text" name="yl_postage_fee" value="<?php echo get_option('yl_postage_fee'); ?>" />
                <br />
                <small>Postage Fee (fixed price)</small>
			</td>
		</tr>

		<tr valign="top">
			<th scope="row"><?php echo __('Credit Card Line Service'); ?></th>
			<td style="padding-top:0;">
				$<input type="text" name="yl_credit_card_line_fee" value="<?php echo get_option('yl_credit_card_line_fee'); ?>" />
                <br />
                <small>Credit Card Line Fee (fixed price)</small>
			</td>
		</tr>

		<tr valign="top">
			<th scope="row"><?php echo __('Multi Suite Coupon'); ?></th>
			<td style="padding-top:0;">
				<input type="text" name="yl_multisite_coupon" value="<?php echo get_option('yl_multisite_coupon'); ?>" />
                <br />
                <small>Multi Suite Coupon Code</small>
			</td>
		</tr>	

		<tr valign="top">
			<th scope="row"><?php echo __('Multi Suite Discount %'); ?></th>
			<td style="padding-top:0;">
				<span class="_yl_ms_span">2 suites</span> <input class="small-text" type="text" name="yl_multisite_discount" value="<?php echo get_option('yl_multisite_discount'); ?>" /> %
                <br />
                <span class="_yl_ms_span">3 suites</span> <input class="small-text" type="text" name="yl_multisite_discount_3" value="<?php echo get_option('yl_multisite_discount_3'); ?>" /> %
                <br />
                <span class="_yl_ms_span">4 suites</span> <input class="small-text" type="text" name="yl_multisite_discount_4" value="<?php echo get_option('yl_multisite_discount_4'); ?>" /> %
                <br />
                <span class="_yl_ms_span">5+ suites</span> <input class="small-text" type="text" name="yl_multisite_discount_5" value="<?php echo get_option('yl_multisite_discount_5'); ?>" /> %
			</td>
		</tr>	

		<tr><th colspan="2"><h3>Y-Membership</h3></th></tr>

		<tr valign="top">
			<th scope="row"><?php echo __('Monthly Rent Rate'); ?></th>
			<td style="padding-top:0;">
				$<input type="text" name="yl_y_membership_monthly_rate" value="<?php echo get_option('yl_y_membership_monthly_rate'); ?>" />
			</td>
		</tr>

		<tr valign="top">
			<th scope="row"><?php echo __('Deposit'); ?></th>
			<td style="padding-top:0;">
				$<input type="text" name="yl_y_membership_deposit" value="<?php echo get_option('yl_y_membership_deposit'); ?>" />
			</td>
		</tr>

		<tr><th colspan="2"><h3>Rate Increases</h3></th></tr>
		<tr valign="top">
			<th scope="row"><?php echo __('Default Message'); ?></th>
			<td style="padding-top:0;">
				<input type="text" class="widefat" name="yl_rate_increase_default_message" value="<?php echo get_option('yl_rate_increase_default_message'); ?>" />
				<i>
					<strong>%suite_number%</strong> : suite number<br>
					<strong>%new_rate%</strong> : new rate for a given suite number<br>
				</i>
			</td>
		</tr>
		<tr valign="top">
			<th scope="row"><?php echo __('Increase %'); ?></th>
			<td style="padding-top:0;">
				<input type="text" class="only-number yl_rate_increase_perc" name="yl_rate_increase_perc" value="<?php echo get_option('yl_new_monthly_rate_perc_value'); ?>" /> %  
				<input type="button" name="yl_rate_increase_set_new_rates" class="button-primary yl_rate_increase_set_new_rates" value="Set new rates"> 
				<?php
				if (get_option('yl_new_monthly_rate_perc_value')) {
					?>
					<input type="button" name="yl_rate_increase_roll_out_new_rate" class="button-secondary yl_rate_increase_roll_out_new_rate" value="Roll out new rate"> 
					<?php
				}
				?>
				<div class="yl_rate_debug_block"></div>
			</td>
		</tr>

		<tr><th colspan="2"><h3>Accounting Categories</h3></th></tr>
		<tr valign="top">
			<th scope="row"><?php echo __('Phone'); ?></th>
			<td style="padding-top:0;">
				<?php yl_account_categories_select_field('acc_category', 'yl_category_id_phone', get_option('yl_category_id_phone')); ?>
			</td>
		</tr>
		<tr valign="top">
			<th scope="row"><?php echo __('Tenant Improvement'); ?></th>
			<td style="padding-top:0;">
				<?php yl_account_categories_select_field('acc_category', 'yl_category_id_improvements', get_option('yl_category_id_improvements')); ?>
			</td>
		</tr>
		<tr valign="top">
			<th scope="row"><?php echo __('Fees'); ?></th>
			<td style="padding-top:0;">
				<?php yl_account_categories_select_field('acc_category', 'yl_category_id_fees', get_option('yl_category_id_fees')); ?>
			</td>
		</tr>
		<tr valign="top">
			<th scope="row"><?php echo __('Copies'); ?></th>
			<td style="padding-top:0;">
				<?php yl_account_categories_select_field('acc_category', 'yl_category_id_copies', get_option('yl_category_id_copies')); ?>
			</td>
		</tr>
		<tr valign="top">
			<th scope="row"><?php echo __('Discounts'); ?></th>
			<td style="padding-top:0;">
				<?php yl_account_categories_select_field('acc_category', 'yl_category_id_discounts', get_option('yl_category_id_discounts')); ?>
			</td>
		</tr>
		<tr valign="top">
			<th scope="row"><?php echo __('Long Distance'); ?></th>
			<td style="padding-top:0;">
				<?php yl_account_categories_select_field('acc_category', 'yl_category_id_longdistance', get_option('yl_category_id_longdistance')); ?>
			</td>
		</tr>
		<tr valign="top">
			<th scope="row"><?php echo __('Postage'); ?></th>
			<td style="padding-top:0;">
				<?php yl_account_categories_select_field('acc_category', 'yl_category_id_postage', get_option('yl_category_id_postage')); ?>
			</td>
		</tr>
		<tr valign="top">
			<th scope="row"><?php echo __('Rent'); ?></th>
			<td style="padding-top:0;">
				<?php yl_account_categories_select_field('acc_category', 'yl_category_id_rent', get_option('yl_category_id_rent')); ?>
			</td>
		</tr>
		<tr valign="top">
			<th scope="row"><?php echo __('Sec. Deposit'); ?></th>
			<td style="padding-top:0;">
				<?php yl_account_categories_select_field('acc_category', 'yl_category_id_deposit', get_option('yl_category_id_deposit')); ?>
			</td>
		</tr>
		<tr valign="top">
			<th scope="row"><?php echo __('Old Invoices'); ?></th>
			<td style="padding-top:0;">
				<?php yl_account_categories_select_field('acc_category', 'yl_category_id_oldinvoices', get_option('yl_category_id_oldinvoices')); ?>
			</td>
		</tr>


		<script>
		jQuery(document).ready(function() {
			jQuery(".only-number").keydown(function (e) {
		        // Allow: backspace, delete, tab, escape, enter and .
		        if (jQuery.inArray(e.keyCode, [46, 8, 9, 27, 13, 110, 190]) !== -1 ||
		             // Allow: Ctrl+A
		            (e.keyCode == 65 && e.ctrlKey === true) ||
		             // Allow: Ctrl+C
		            (e.keyCode == 67 && e.ctrlKey === true) ||
		             // Allow: Ctrl+X
		            (e.keyCode == 88 && e.ctrlKey === true) ||
		             // Allow: home, end, left, right
		            (e.keyCode >= 35 && e.keyCode <= 39)) {
		                 // let it happen, don't do anything
		                 return;
		        }
		        // Ensure that it is a number and stop the keypress
		        if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
		            e.preventDefault();
		        }
		    });

			// Set new rate button
		    jQuery('.yl_rate_increase_set_new_rates').click(function() {
		    	var data = {
					'action': 'admin_set_new_rate',
					'rate_increase': jQuery('.yl_rate_increase_perc').val()
				};
				jQuery.post(ajaxurl, data, function(response) {
					jQuery('.yl_rate_debug_block').html(response);
				});
		    });

		    // Roll out new rate button
		    jQuery('.yl_rate_increase_roll_out_new_rate').click(function() {
		    	var data = {
					'action': 'admin_roll_out_new_rate',
				};
				jQuery.post(ajaxurl, data, function(response) {
					if (response == 'ok') {
						jQuery('.yl_rate_increase_roll_out_new_rate').hide();
						jQuery('.yl_rate_increase_perc').val('');
						jQuery('.yl_rate_debug_block').html('New rate was set for all suites.<br>');
					}
					else {
						jQuery('.yl_rate_debug_block').html(response);
					}
				});
		    });
		    

		});
		</script>

	</table>			
	<p class="submit">
		<input type="submit" name="yl_settings_options_submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
	</p>
</form>	
</div>

<?php
}


function lease_summary_settings_page_callback() {
	if(isset($_POST['summary_settings_save'])){
		update_option( 'lease_summary', $_POST['lease_summary'] );
		update_option( 'lease_version', $_POST['lease_version'] );	
		update_option( 'ym_lease_summary', $_POST['ym_lease_summary'] );
		update_option( 'ym_lease_version', $_POST['ym_lease_version'] );	
		echo '<div class="updated"><p>Successfully Updated</p></div>';
	}
		
	echo '<div class="wrap">';
		?>
		<form action="" method="post">
			<table class="form-table">

				<tr valign="top">
					<td>
						<hr>
						<h2>Lease</h2>
					</td>
				</tr>

				<tr valign="top">
					<td style="padding-top:0;">
					<?php echo __('Lease Version'); ?>
						<input type="text" name="lease_version" value="<?php echo get_option('lease_version'); ?>" />
					</td>
				</tr>									
				<tr valign="top">
					<td>
						<?php
							$content_summary = stripslashes(get_option('lease_summary'));
							$settings = array( 'media_buttons' => false );
							wp_editor( $content_summary, 'lease_summary', $settings );
						?>
						Supported tags for lease info:<br />
						<code>%%CompanyName%%, %%LesseeGuarantorPersonalPhoneNumber%%, %%CompanyType%%, %%Lessee%%, %%Guarantor%%, %%SuiteNo%%, %%FirstMonthRentRate%%, %%RecurringRentRate%%, %%SecurityDeposit%%, %%MoveinLeaseStartDate%%, %%LesseeGuarantorHomeAddress%%, %%CurrentDate%%, %%LesseeGuarantorEmail%%, %%ServiceCompanyCheckbox%%, %%Addendum%%, %%PromoCode%%, %%MultisuiteDiscountIfApplicable%%, %%Lessor%%, %%Location%%, %%BuildingManager%%, %%BuildingManagerEmail%%, %%BuildingManagerPhoneNumber%%, %%LocationPhoneNumber%%, %%LeaseTerm%%, %%ServiceFees%%, %%DueAtSigning%%, %%IsLesseeGuarantor%%, %%LesseePhone%%, %%LesseeEmail%%, %%LesseeHomeAddress%%, %%LesseeSignature%%, %%LesseeSignatureDate%%, %%BuildingManagerSignatureDate%%, %%BuildingManagerSignature%%, %%LeasePDF%%, %%LeaseSummaryPDF%%</code></p></td>
				</tr>	

				<tr valign="top">
					<td>
						<hr>
						<h2>Y-Membership Lease</h2>
					</td>
				</tr>

				<tr valign="top">
					<td style="padding-top:0;">
					<?php echo __('Y-Membership Lease Version'); ?>
						<input type="text" name="ym_lease_version" value="<?php echo get_option('ym_lease_version'); ?>" />
					</td>
				</tr>									
				<tr valign="top">
					<td>
						<?php echo __('Y-Membership Lease'); ?>
						<?php
							$content_summary = stripslashes(get_option('ym_lease_summary'));
							$settings = array( 'media_buttons' => false );
							wp_editor( $content_summary, 'ym_lease_summary', $settings );
						?>
						Supported tags for lease info:<br />
						<code>%%CompanyName%%, %%LesseeGuarantorPersonalPhoneNumber%%, %%CompanyType%%, %%Lessee%%, %%Guarantor%%, %%SuiteNo%%, %%FirstMonthRentRate%%, %%RecurringRentRate%%, %%SecurityDeposit%%, %%MoveinLeaseStartDate%%, %%LesseeGuarantorHomeAddress%%, %%CurrentDate%%, %%LesseeGuarantorEmail%%, %%ServiceCompanyCheckbox%%, %%Addendum%%, %%PromoCode%%, %%MultisuiteDiscountIfApplicable%%, %%Lessor%%, %%Location%%, %%BuildingManager%%, %%BuildingManagerEmail%%, %%BuildingManagerPhoneNumber%%, %%LocationPhoneNumber%%, %%LeaseTerm%%, %%ServiceFees%%, %%DueAtSigning%%, %%IsLesseeGuarantor%%, %%LesseePhone%%, %%LesseeEmail%%, %%LesseeHomeAddress%%, %%LesseeSignature%%, %%LesseeSignatureDate%%, %%BuildingManagerSignatureDate%%, %%BuildingManagerSignature%%, %%LeasePDF%%, %%LeaseSummaryPDF%%</code></p></td>
				</tr>	
				
				<tr valign="top">
					<td><label for="summary_settings_save"></label><input type="submit" class="button button-primary button-large" value="Settings Save" id="summary_settings_save" name="summary_settings_save"></td>
				</tr>							
			</table>
		</form>
		
		<?php			
	echo '</div>';

}
