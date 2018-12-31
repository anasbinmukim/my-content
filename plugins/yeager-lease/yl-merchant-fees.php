<?php
add_action('admin_menu', 'register_merchant_fees_page');
function register_merchant_fees_page() {
	add_submenu_page( 'edit.php?post_type=sa_invoice', 'Merchant Fees', 'Merchant Fees', 'edit_posts', 'merchant-fees', 'yl_merchant_fees_page_callback' );
}

function yl_merchant_fees_page_callback() {
	?>
	<div class="wrap">
	<h2><?php echo __('Merchant Fees'); ?></h2>
	<form name="yl_settings" method="post" action="edit.php?post_type=lease&page=merchant-fees">
		<table class="form-table">

			<tr valign="top">
				<th scope="row"></th>
				<td style="padding-top:0;">
				</td>
			</tr>
		</table>
	</form>
	<?php
}