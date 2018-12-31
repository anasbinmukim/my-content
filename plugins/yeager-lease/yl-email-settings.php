<?php


add_action('admin_menu', 'register_email_submenu_page');
function register_email_submenu_page() {
	add_submenu_page( 'edit.php?post_type=lease', 'Email Settings', 'Email Settings', 'edit_posts', 'email', 'email_submenu_page_callback' );
	add_submenu_page( 'edit.php?post_type=lease', 'BM Email Settings', 'Email Settings (BM)', 'edit_posts', 'bm-email', 'bm_email_submenu_page_callback' );
}

function email_submenu_page_callback() {

	if(isset($_POST['email_settings_save'])){
		update_option( 'clients_email_subject', $_POST['clients_email_subject'] );
		update_option( 'clients_email_message', $_POST['clients_email_message'] );
		update_option( 'clients_hold_email_subject', $_POST['clients_hold_email_subject'] );
		update_option( 'clients_hold_email_message', $_POST['clients_hold_email_message'] );
		update_option( 'clients_standalone_invoice_email_subject', $_POST['clients_standalone_invoice_email_subject'] );
		update_option( 'clients_standalone_invoice_email_message', $_POST['clients_standalone_invoice_email_message'] );
		update_option( 'clients_lease_summary_resend_email_subject', $_POST['clients_lease_summary_resend_email_subject'] );
		update_option( 'clients_lease_summary_resend_email_message', $_POST['clients_lease_summary_resend_email_message'] );
		update_option( 'clients_lease_resend_email_subject', $_POST['clients_lease_resend_email_subject'] );
		update_option( 'clients_lease_resend_email_message', $_POST['clients_lease_resend_email_message'] );

		echo '<div class="updated"><p>Successfully Updated</p></div>';
	}



	echo '<div class="wrap">';
		?>
		<form action="" method="post">
			<h2>New Client Notification</h2>
			<table class="form-table">
				<tr valign="top">
					<th scope="row"><label for="clients_email_subject">Subject</label></th>
					<td><input type="text" name="clients_email_subject" id="clients_email_subject" value="<?php echo get_option('clients_email_subject'); ?>" class="regular-text" /></td>
				</tr>
				<tr valign="top">
					<th scope="row"><label for="clients_email_message">Message</label></th>
					<td><textarea name="clients_email_message" id="clients_email_message" class="large-text" rows="15"><?php echo stripslashes(get_option('clients_email_message')); ?></textarea><p class="description">Supported tags:<br />
						%%client-name%% = (Client Name)<br />
						%%user-name%% = (Login User Name)<br />
						%%user-password%% = (Login Pass)<br />
						%%lease-sign-url%% = (Lease Sign URL)<br />
						</p></td>
				</tr>
			</table>

			<hr>

			<h2>Suite Hold Notification</h2>
			<table class="form-table">
				<tr valign="top">
					<th scope="row"><label for="clients_hold_email_subject">Subject</label></th>
					<td><input type="text" name="clients_hold_email_subject" id="clients_hold_email_subject" value="<?php echo get_option('clients_hold_email_subject'); ?>" class="regular-text" /></td>
				</tr>
				<tr valign="top">
					<th scope="row"><label for="clients_hold_email_message">Message</label></th>
					<td><textarea name="clients_hold_email_message" id="clients_hold_email_message" class="large-text" rows="15"><?php echo stripslashes(get_option('clients_hold_email_message')); ?></textarea><p class="description">Supported tags:<br />
						%%client-name%% = (Client Name)<br />
						%%invoice-url%% = (Invoice URL)<br />
						%%suite-name%% = (Suite name/number)<br />
						</p></td>
				</tr>

			</table>

			<hr>

			<h2>Standalone Invoices Notification</h2>
			<table class="form-table">
				<tr valign="top">
					<th scope="row"><label for="clients_standalone_invoice_email_subject">Subject</label></th>
					<td><input type="text" name="clients_standalone_invoice_email_subject" id="clients_standalone_invoice_email_subject" value="<?php echo get_option('clients_standalone_invoice_email_subject'); ?>" class="regular-text" /></td>
				</tr>
				<tr valign="top">
					<th scope="row"><label for="clients_standalone_invoice_email_message">Message</label></th>
					<td><textarea name="clients_standalone_invoice_email_message" id="clients_standalone_invoice_email_message" class="large-text" rows="15"><?php echo stripslashes(get_option('clients_standalone_invoice_email_message')); ?></textarea><p class="description">Supported tags:<br />
						%%client-name%% = (Client Name)<br />
						%%invoice-url%% = (Invoice URL)<br />
						%%suite-name%% = (Suite name/number)<br />
						%%invoice-description%% = (Reason for the invoice)
						%%cost%% = (Total for the invoice)
						</p></td>
				</tr>

				<tr valign="top">
					<th scope="row"><label for="email_settings_save"></label></th>
					<td><input type="submit" class="button button-primary button-large" value="Settings Save" id="email_settings_save" name="email_settings_save"></td>
				</tr>
			</table>

			<hr>

			<h2>BM Lease Summary Re-Send Email</h2>
			<table class="form-table">
				<tr valign="top">
					<th scope="row"><label for="clients_lease_summary_resend_email_subject">Subject</label></th>
					<td><input type="text" name="clients_lease_summary_resend_email_subject" id="clients_lease_summary_resend_email_subject" value="<?php echo get_option('clients_lease_summary_resend_email_subject'); ?>" class="regular-text" /></td>
				</tr>
				<tr valign="top">
					<th scope="row"><label for="clients_lease_summary_resend_email_message">Message</label></th>
					<td><textarea name="clients_lease_summary_resend_email_message" id="clients_lease_summary_resend_email_message" class="large-text" rows="15"><?php echo stripslashes(get_option('clients_lease_summary_resend_email_message')); ?></textarea><p class="description">Supported tags:<br />
						%%client-name%% = (Client Name)<br />
						%%lease-sign-url%% = (Lease Sign URL)<br />
						%%user-name%% = (Username/email)<br />
						</p></td>
				</tr>

				<tr valign="top">
					<th scope="row"><label for="email_settings_save"></label></th>
					<td><input type="submit" class="button button-primary button-large" value="Settings Save" id="email_settings_save" name="email_settings_save"></td>
				</tr>
			</table>

			<hr>

			<h2>BM Lease Re-Send Email</h2>
			<table class="form-table">
				<tr valign="top">
					<th scope="row"><label for="clients_lease_resend_email_subject">Subject</label></th>
					<td><input type="text" name="clients_lease_resend_email_subject" id="clients_lease_resend_email_subject" value="<?php echo get_option('clients_lease_resend_email_subject'); ?>" class="regular-text" /></td>
				</tr>
				<tr valign="top">
					<th scope="row"><label for="clients_lease_resend_email_message">Message</label></th>
					<td><textarea name="clients_lease_resend_email_message" id="clients_lease_resend_email_message" class="large-text" rows="15"><?php echo stripslashes(get_option('clients_lease_resend_email_message')); ?></textarea><p class="description">Supported tags:<br />
						%%client-name%% = (Client Name)<br />
						%%lease-sign-url%% = (Lease Sign URL)<br />
						%%user-name%% = (Username/email)<br />
						</p></td>
				</tr>

				<tr valign="top">
					<th scope="row"><label for="email_settings_save"></label></th>
					<td><input type="submit" class="button button-primary button-large" value="Settings Save" id="email_settings_save" name="email_settings_save"></td>
				</tr>
			</table>

		</form>

		<?php

	echo '</div>';

}


function bm_email_submenu_page_callback() {

	if(isset($_POST['bm_email_settings_save'])){
		update_option( 'bm_email_subject', $_POST['bm_email_subject'] );
		update_option( 'bm_email_message', $_POST['bm_email_message'] );

		echo '<div class="updated"><p>Successfully Updated</p></div>';
	}

	if(isset($_POST['bm_va_email_settings_save'])){
		update_option( 'bm_va_email_subject', $_POST['bm_va_email_subject'] );
		update_option( 'bm_va_email_message', $_POST['bm_va_email_message'] );

		echo '<div class="updated"><p>Vacate Addendum Successfully Updated</p></div>';
	}

	if(isset($_POST['bm_va_con_email_settings_save'])){
		update_option( 'bm_va_con_email_subject', $_POST['bm_va_con_email_subject'] );
		update_option( 'bm_va_con_email_message', $_POST['bm_va_con_email_message'] );

		echo '<div class="updated"><p>Vacate Addendum Confirmation Successfully Updated</p></div>';
	}


    if (isset($_POST['bm_va_con_email_settings_save'])) {
        update_option('bm_va_con_email_subject', $_POST['bm_va_con_email_subject']);
        update_option('bm_va_con_email_message', $_POST['bm_va_con_email_message']);

        echo '<div class="updated"><p>Vacate Addendum Confirmation Successfully Updated</p></div>';
    }

    if(isset($_POST['it_upgrade_settings_save'])) {
        update_option('it_upgrade_email_subject', $_POST['it_upgrade_email_subject']);
        update_option('it_upgrade_email_message', $_POST['it_upgrade_email_message']);
          echo '<div class="updated"><p>IT Upgradation Notification Email Successfully Updated</p></div>';
    }


	echo '<div class="wrap">';
		echo '<h2>Building Manager Notification</h2>';
		?>
		<form action="" method="post">
			<table class="form-table">
				<tr valign="top">
					<th scope="row"><label for="bm_email_subject">Subject</label></th>
					<td><input type="text" name="bm_email_subject" id="bm_email_subject" value="<?php echo get_option('bm_email_subject'); ?>" class="regular-text" /></td>
				</tr>
				<tr valign="top">
					<th scope="row"><label for="bm_email_message">Message</label></th>
					<td><textarea name="bm_email_message" id="bm_email_message" class="large-text" rows="15"><?php echo stripslashes(get_option('bm_email_message')); ?></textarea><p class="description">Supported tags:<br />
						%%name%% = (Building Manager Name)<br />
						%%lease-sign-url%% = (Lease Sign Page URL)<br />
						</p></td>
				</tr>

				<tr valign="top">
					<th scope="row"><label for="bm_email_settings_save"></label></th>
					<td><input type="submit" class="button button-primary button-large" value="Settings Save" id="bm_email_settings_save" name="bm_email_settings_save"></td>
				</tr>
			</table>
		</form>


		<h2>Early Vacate Addendum Sign Email</h2>
		<form action="" method="post">
			<table class="form-table">
				<tr valign="top">
					<th scope="row"><label for="bm_va_email_subject">Subject</label></th>
					<td><input type="text" name="bm_va_email_subject" id="bm_va_email_subject" value="<?php echo get_option('bm_va_email_subject'); ?>" class="regular-text" /></td>
				</tr>
				<tr valign="top">
					<th scope="row"><label for="bm_va_email_message">Message</label></th>
					<td><textarea name="bm_va_email_message" id="bm_va_email_message" class="large-text" rows="15"><?php echo stripslashes(get_option('bm_va_email_message')); ?></textarea><p class="description">Supported tags:<br />
						%%name%% = (Building Manager Name)<br />
						%%vacate-sign-url%% = (Vacate Sign Page URL)<br />
						</p></td>
				</tr>

				<tr valign="top">
					<th scope="row"><label for="bm_va_email_settings_save"></label></th>
					<td><input type="submit" class="button button-primary button-large" value="Settings Save" id="bm_va_email_settings_save" name="bm_va_email_settings_save"></td>
				</tr>
			</table>
		</form>


		<h2>Early Vacate Addendum Confirmation Email</h2>
		<form action="" method="post">
			<table class="form-table">
				<tr valign="top">
					<th scope="row"><label for="bm_va_con_email_subject">Subject</label></th>
					<td><input type="text" name="bm_va_con_email_subject" id="bm_va_con_email_subject" value="<?php echo get_option('bm_va_con_email_subject'); ?>" class="regular-text" /></td>
				</tr>
				<tr valign="top">
					<th scope="row"><label for="bm_va_con_email_message">Message</label></th>
					<td><textarea name="bm_va_con_email_message" id="bm_va_con_email_message" class="large-text" rows="15"><?php echo stripslashes(get_option('bm_va_con_email_message')); ?></textarea><p class="description">Supported tags:<br />
						%%name%% = (Building Manager Name)<br />
						%%suite-name%% = (Vacate Suite Name)<br />
						</p></td>
				</tr>

				<tr valign="top">
					<th scope="row"><label for="bm_va_con_email_settings_save"></label></th>
					<td><input type="submit" class="button button-primary button-large" value="Settings Save" id="bm_va_con_email_settings_save" name="bm_va_con_email_settings_save"></td>
				</tr>
			</table>

		</form>

    <h2>Y-Store Purchase Notification</h2>
    <form action="" method="post">
        <table class="form-table">
            <tr valign="top">
                <th scope="row"><label for="it_upgrade_email_subject">Subject</label></th>
                <td><input type="text" name="it_upgrade_email_subject" id="it_upgrade_email_subject" value="<?php echo get_option('it_upgrade_email_subject'); ?>" class="regular-text" /></td>
            </tr>
            <tr valign="top">
                <th scope="row"><label for="it_upgrade_email_message">Message</label></th>
                <td><textarea name="it_upgrade_email_message" id="it_upgrade_email_message" class="large-text" rows="15"><?php echo stripslashes(get_option('it_upgrade_email_message')); ?></textarea><p class="description">Supported tags:<br />
												Client Name = %%client-name%% <br />
												Product = %%product-title%% <br />
												Variation = %%variation-title%% <br />
												Quantity = %%quantity%% <br />
												Setup Cost = $%%setup-fees%% <br />
                        Total Order = $%%total-order%% <br />
												Billing Frequency = %%billing-frequency%% <br />
												Invoice URL =	%%invoice%%<br />
                    </p></td>
            </tr>


            <tr valign="top">
                <th scope="row"><label for="it_upgrade_settings_save"></label></th>
                <td><input type="submit" class="button button-primary button-large" value="Settings Save" id="it_upgrade_settings_save" name="it_upgrade_settings_save"></td>
            </tr>
        </table>
    </form>

		<?php

	echo '</div>';

}
