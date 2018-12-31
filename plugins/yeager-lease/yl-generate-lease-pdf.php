<?php
add_action('admin_menu', 'register_lease_pdf_page');
function register_lease_pdf_page() {
	add_submenu_page( 'edit.php?post_type=lease', 'Generate Lease PDF', 'Lease PDF', 'edit_posts', 'lease-pdf', 'lease_pdf_page_callback' );
}

function lease_pdf_page_callback() {
if(isset($_POST['yl_lease_pdf_submit'])){
	yl_generate_complete_lease_pdf($_POST['lease']);
	echo "<div class='updated'><p>PDF Generated.</p></div>";
}
?>
<div class="wrap">
<h2><?php echo __('Generate Lease PDF'); ?></h2>
<form name="yl_lease_pdf" method="post" action="edit.php?post_type=lease&page=lease-pdf">
	<table class="form-table">
		<tr valign="top">
			<th scope="row" style="padding-top: 8px; width: 100px;"><?php echo __('Select Lease:'); ?></th>
			<td style="padding-top:0;">
            	<select name="lease">
            	<?php
					$args = array(
						'post_type' => 'lease',
						'orderby' => 'title', 
						'order' => 'ASC',
						'posts_per_page' => -1
					);	
					$lease_posts = new WP_Query($args);
					
					if($lease_posts->have_posts()){
						while($lease_posts->have_posts()): $lease_posts->the_post();
							$post_id = get_the_ID();
							$post_title = get_the_title($post_id);
							echo '<option value="'.$post_id.'">'.$post_title.'</option>';
						endwhile;
							
					}
					wp_reset_query();
				?>
                </select>
			</td>
		</tr>
					
	</table>			
	<p class="submit">
		<input type="submit" name="yl_lease_pdf_submit" class="button-primary" value="<?php _e('Generate PDF') ?>" />
	</p>
</form>	
</div>
<?php
}