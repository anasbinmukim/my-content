<?php
add_action('admin_menu', 'register_lease_early_vacate_settings_page');
function register_lease_early_vacate_settings_page() {
	add_submenu_page( 'edit.php?post_type=lease', 'Early Vacate', 'Early Vacate', 'edit_posts', 'lease-early-vacate', 'lease_early_vacate_settings_page_callback' );
}

function lease_early_vacate_settings_page_callback() {
	if(isset($_POST['vacate_addendum_settings_save'])){
		update_option( 'early_vacate_addendum', $_POST['early_vacate_addendum'] );	
		echo '<div class="updated"><p>Successfully Updated</p></div>';
	}
		
	echo '<div class="wrap">';
		echo '<h2>Early Vacate Addendum</h2>';
		?>
		<form action="" method="post">
			<table class="form-table">
				<tr valign="top">
					<td>
						<?php
						$content_early_vacate_addendum = stripslashes(get_option('early_vacate_addendum'));
						$settings = array( 'media_buttons' => false );
						wp_editor( $content_early_vacate_addendum, 'early_vacate_addendum', $settings );
						?>
						Supported tags for lease info:<br />						
						<code>%%Suite%%, %%Location%%</code></p></td>
				</tr>	
				
				<tr valign="top">
					<td><label for="vacate_addendum_settings_save"></label><input type="submit" class="button button-primary button-large" value="Settings Save" id="vacate_addendum_settings_save" name="vacate_addendum_settings_save"></td>
				</tr>							
			</table>
		</form>
		
		<?php			
	echo '</div>';

}
