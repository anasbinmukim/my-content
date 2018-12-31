<?php
function yl_bm_prospects() {
	ob_start();
	
	if( is_user_logged_in() ) {
		if( current_user_can('building_manager') ) {
			?>
            <script type="text/javascript">
				jQuery( document ).ready(function() {
					jQuery( "#prosDate" ).datepicker({ dateFormat: 'yy-mm-dd' });
				});
			</script>
            <div class="prospect_controller" style="padding-top:15px;">
            	<a href="/prospects/?prospect=all">All Prospects</a> | 
            	<a href="/prospects/?prospect=add-new">Add Prospect</a>
            </div>
            <?php
				if(isset($_POST['prospect_submit'])) {
					$pros_date = esc_html($_POST['pros_date']);
					$pros_name = esc_html($_POST['pros_name']);
					$pros_phone = esc_html($_POST['pros_phone']);
					$pros_email = esc_html($_POST['pros_email']);
					$pros_company = esc_html($_POST['pros_company']);
					$pros_discover = esc_html($_POST['pros_discover']);
					$pros_interest = esc_html($_POST['pros_interest']);
					$pros_move_in = esc_html($_POST['pros_move_in']);
					$pros_contact_option = esc_html($_POST['pros_contact_option']);
					
					$user_id = get_current_user_id();
					$new_post = array(
								  'post_type'    => 'prospects',
								  'post_title'   => wp_strip_all_tags( $pros_name ),
								  'post_author'  => $user_id,
								  'post_status'  => 'publish'
								);
								 
					// Insert the post into the database
					$prospect_id = wp_insert_post( $new_post );
					
					update_post_meta( $prospect_id, '_yl_pros_date', $pros_date );
					update_post_meta( $prospect_id, '_yl_pros_phone', $pros_phone );
					update_post_meta( $prospect_id, '_yl_pros_email', $pros_email );
					update_post_meta( $prospect_id, '_yl_pros_company', $pros_company );
					update_post_meta( $prospect_id, '_yl_pros_discover', $pros_discover );
					update_post_meta( $prospect_id, '_yl_pros_interest', $pros_interest );
					update_post_meta( $prospect_id, '_yl_pros_move_in', $pros_move_in );
					update_post_meta( $prospect_id, '_yl_pros_contact_option', $pros_contact_option );
					
					echo '<p style="color: green;">Prospect Added.</p>';
				}

				if(isset($_POST['prospect_update'])) {
					$pros_date = esc_html($_POST['pros_date']);
					$pros_name = esc_html($_POST['pros_name']);
					$pros_phone = esc_html($_POST['pros_phone']);
					$pros_email = esc_html($_POST['pros_email']);
					$pros_company = esc_html($_POST['pros_company']);
					$pros_discover = esc_html($_POST['pros_discover']);
					$pros_interest = esc_html($_POST['pros_interest']);
					$pros_move_in = esc_html($_POST['pros_move_in']);
					$pros_contact_option = esc_html($_POST['pros_contact_option']);
					
					$prospect_id = $_POST['pros_id'];
					
					update_post_meta( $prospect_id, '_yl_pros_date', $pros_date );
					update_post_meta( $prospect_id, '_yl_pros_phone', $pros_phone );
					update_post_meta( $prospect_id, '_yl_pros_email', $pros_email );
					update_post_meta( $prospect_id, '_yl_pros_company', $pros_company );
					update_post_meta( $prospect_id, '_yl_pros_discover', $pros_discover );
					update_post_meta( $prospect_id, '_yl_pros_interest', $pros_interest );
					update_post_meta( $prospect_id, '_yl_pros_move_in', $pros_move_in );
					update_post_meta( $prospect_id, '_yl_pros_contact_option', $pros_contact_option );
					
					echo '<p style="color: green;">Prospect Updated.</p>';
				}
				
				if(! isset($_GET['prospect']) || $_GET['prospect'] == 'add-new') {
			?>
            <h2 style="padding-top:15px; text-align:center;">Add Prospect</h2>
            
            <form action="" method="post" class="prospect-form">

                <div class="form-group">
                    <label for="prosDate">Date</label>
                    <input type="text" class="form-control" id="prosDate" name="pros_date">
                </div>
                <div class="form-group">
                    <label for="prosName">Name</label>
                    <input type="text" class="form-control" id="prosName" name="pros_name">
                </div>
                <div class="form-group">
                    <label for="prosPhone">Phone</label>
                    <input type="text" class="form-control" id="prosPhone" name="pros_phone">
                </div>
                <div class="form-group">
                    <label for="prosEmail">Email</label>
                    <input type="email" class="form-control" id="prosEmail" name="pros_email">
                </div>
                <div class="form-group">
                    <label for="prosCompany">Company</label>
                    <input type="text" class="form-control" id="prosCompany" name="pros_company">
                </div>
                <div class="form-group">
                    <label for="prosDiscover">How Did you Discover Us?</label>
                    <select name="pros_discover" id="prosDiscover" class="form-control">
                    	<option value="tenant">Tenant</option>
                    	<option value="friend">Friend</option>
                    	<option value="postcard">Postcard</option>
                    	<option value="website">Website</option>
                    	<option value="newspaper">Newspaper</option>
                    	<option value="other">Other</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="prosInterest">Which suites do you have an interest in?</label>
                    <input type="text" class="form-control" id="prosInterest" name="pros_interest">
                    <!--<select name="pros_interest" id="prosInterest" class="form-control">
                    <?php
						/*$args = array(
							'post_type' 		=> 'suites',
							'posts_per_page'	=> -1
						);
					
						$query = new WP_Query( $args );
						
						if($query->have_posts()) {
							while($query->have_posts()) {
								$query->the_post();

								echo '<option value="'.get_the_ID().'">'.get_the_title().'</option>';
							}
							wp_reset_postdata();
						}*/
					?>
                    </select>-->
                </div>
                <div class="form-group">
                    <label for="prosMoveInDate">When are you available to move in?</label>
                    <input type="text" class="form-control leasedatepicker" id="prosMoveInDate" name="pros_move_in">
                </div>
                <div class="form-group">
                    <label for="prosContactOpt">When/how should we next contact you?</label>
                    <input type="text" class="form-control" id="prosContactOpt" name="pros_contact_option">
                </div>
                <button type="submit" name="prospect_submit" class="btn btn-primary">Submit</button>

            </form>
            
            <?php
				}
				if(isset($_GET['prospect']) && $_GET['prospect'] == 'all') {
			?>
            	<h2 style="padding-top:15px; text-align:center;">All Prospects</h2>
                <div class="table-responsive">
                    <table class="table">
                    	<tr>
                        	<th>SL</th>
                        	<th>Name</th>
                        	<th>Email</th>
                        	<th>&nbsp;</th>
                        </tr>
                    <?php
						$args = array(
							'post_type' 		=> 'prospects',
							'posts_per_page'	=> -1
						);
					
						$query = new WP_Query( $args );
						
						if($query->have_posts()) {
							$i = 0;
							while($query->have_posts()) {
								$query->the_post();

								echo '<tr>
										<td>'.++$i.'</td>
										<td>'.esc_html(get_the_title()).'</td>
										<td>'.esc_html(get_post_meta(get_the_ID(), '_yl_pros_email', true)).'</td>
										<td><a href="/prospects/?prospect=edit&pros_id='.get_the_ID().'">Edit</a></td>
									</tr>';
							}
							wp_reset_postdata();
						}
					?>
                    </table>
                </div>
            <?php
				}
				if(isset($_GET['prospect']) && $_GET['prospect'] == 'edit') {
					$prospect_id = $_GET['pros_id'];
				?>
            	<h2 style="padding-top:15px; text-align:center;">Edit Prospect</h2>
                
                <form action="" method="post" class="prospect-form">

                    <div class="form-group">
                        <label for="prosDate">Date</label>
                        <input type="text" class="form-control" id="prosDate" name="pros_date" value="<?php echo esc_attr(get_post_meta($prospect_id, '_yl_pros_date', true)); ?>">
                    </div>
                    <div class="form-group">
                        <label for="prosName">Name</label>
                        <input type="text" class="form-control" id="prosName" name="pros_name" value="<?php echo esc_attr(get_the_title($prospect_id)); ?>">
                    </div>
                    <div class="form-group">
                        <label for="prosPhone">Phone</label>
                        <input type="text" class="form-control" id="prosPhone" name="pros_phone" value="<?php echo esc_attr(get_post_meta($prospect_id, '_yl_pros_phone', true)); ?>">
                    </div>
                    <div class="form-group">
                        <label for="prosEmail">Email</label>
                        <input type="email" class="form-control" id="prosEmail" name="pros_email" value="<?php echo esc_attr(get_post_meta($prospect_id, '_yl_pros_email', true)); ?>">
                    </div>
                    <div class="form-group">
                        <label for="prosCompany">Company</label>
                        <input type="text" class="form-control" id="prosCompany" name="pros_company" value="<?php echo esc_attr(get_post_meta($prospect_id, '_yl_pros_company', true)); ?>">
                    </div>
                    <div class="form-group">
                    	<?php $discover = esc_html(get_post_meta($prospect_id, '_yl_pros_discover', true)); ?>
                        <label for="prosDiscover">How Did you Discover Us?</label>
                        <select name="pros_discover" id="prosDiscover" class="form-control">
                            <option value="tenant" <?php if($discover == 'tenant') echo 'selected="selected"'; ?>>Tenant</option>
                            <option value="friend" <?php if($discover == 'friend') echo 'selected="selected"'; ?>>Friend</option>
                            <option value="postcard" <?php if($discover == 'postcard') echo 'selected="selected"'; ?>>Postcard</option>
                            <option value="website" <?php if($discover == 'website') echo 'selected="selected"'; ?>>Website</option>
                            <option value="newspaper" <?php if($discover == 'newspaper') echo 'selected="selected"'; ?>>Newspaper</option>
                            <option value="other" <?php if($discover == 'other') echo 'selected="selected"'; ?>>Other</option>
                        </select>
                    </div>
                    <div class="form-group">
                    	<?php $interest = get_post_meta($prospect_id, '_yl_pros_interest', true); ?>
                        <label for="prosInterest">Which suites do you have an interest in?</label>
                        <input type="text" class="form-control" id="prosInterest" name="pros_interest" value="<?php echo esc_attr($interest); ?>">
                        <!--<select name="pros_interest" id="prosInterest" class="form-control">
                        <?php
                            /*$args = array(
                                'post_type' 		=> 'suites',
                                'posts_per_page'	=> -1
                            );
                        
                            $query = new WP_Query( $args );
                            
                            if($query->have_posts()) {
                                while($query->have_posts()) {
                                    $query->the_post();
    								if($interest == get_the_ID()) $selected = 'selected="selected"';
									else $selected = '';
                                    echo '<option value="'.get_the_ID().'" '.$selected.'>'.get_the_title().'</option>';
                                }
                                wp_reset_postdata();
                            }*/
                        ?>
                        </select>-->
                    </div>
                    <div class="form-group">
                        <label for="prosMoveInDate">When are you available to move in?</label>
                        <input type="text" class="form-control leasedatepicker" id="prosMoveInDate" name="pros_move_in" value="<?php echo esc_attr(get_post_meta($prospect_id, '_yl_pros_move_in', true)); ?>">
                    </div>
                    <div class="form-group">
                        <label for="prosContactOpt">When/how should we next contact you?</label>
                        <input type="text" class="form-control" id="prosContactOpt" name="pros_contact_option" value="<?php echo esc_attr(get_post_meta($prospect_id, '_yl_pros_contact_option', true)); ?>">
                    </div>
                    <input type="hidden" name="pros_id" value="<?php echo esc_attr($prospect_id); ?>" />
                    <button type="submit" name="prospect_update" class="btn btn-primary">Save Changes</button>
    
                </form>
                <?php
				}
		} else {
			?>

			<p>Only building manager can view this content.</p>
			
			<?php
		}
	} else {
		?>

		<p>Please login to view this page.</p>
		
		<?php
	}

	return ob_get_clean();
}
add_shortcode('bm_prospects', 'yl_bm_prospects');