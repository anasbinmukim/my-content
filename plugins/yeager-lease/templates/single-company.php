<?php
/**
 * The template for company post details
 *
 */
get_header();
?>

<style type="text/css">
    #main-content .container:before { display: none; }
</style>

<div id="main-content" class="content-area company_single_post_details">
    <div class="container">

        <?php if (is_user_logged_in() && current_user_can('building_manager')) { ?>	


            <?php
            // Start the loop.
            while (have_posts()) : the_post();
                $post_id = get_the_ID();

                $args = array(
                    'post_type' => 'lease',
                    'posts_per_page' => -1,
                    'meta_query' => array(
                        array(
                            'key' => '_yl_company_id',
                            'value' => $post_id,
                            'compare' => '=',
                        ),
                    ),
                    'orderby' => 'date',
                    'order' => 'DESC',
                    'post_status' => 'all'
                );

                $query_top = new WP_Query($args);
                if ($query_top->have_posts()) {
                    global $post;
                    while ($query_top->have_posts()) {
                        $query_top->the_post();
                        $lease_id_top = get_the_ID();

                        $lease_user_id = get_post_meta(get_the_ID(), '_yl_lease_user', true);
                    }
                    wp_reset_query();
                }

                //echo $lease_user_id;
                ?>	

                <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

                    <header class="entry-header">
                        <?php the_title('<h1 class="entry-title" style="float:left;">', '</h1>'); ?>

                        <a class="btn btn-primary" style="float:right;" href="/my-account/?tab=lease&request_profile=<?php echo $lease_user_id; ?>">View Profile</a>
                    </header><!-- .entry-header -->		


                    <div class="entry-content">
                        <div class="featured_photo"><?php echo get_the_post_thumbnail($post_id, 'full'); ?></div>
                        <div class="company_details">

                            <div class="row">
                                <div class="col-md-12">
                                    <table class="lease_list lease_list_table table table-striped" data-page-length="50" data-order="[[ 0, &quot;asc&quot; ]]">
                                        <thead>
                                            <tr>
                                                <th>Suite</th>
                                                <th>Start Date</th>
                                                <th>Monthly Rent</th>
                                                <th>Security Deposit</th>
                                                <th>Copier Codes, Postage Codes</th>
                                                <th></th>
                                            </tr>
                                        </thead>
                                        <tbody>

                                            <?php
                                            $query = new WP_Query($args);
                                            if ($query->have_posts()) {
                                                global $post;
                                                while ($query->have_posts()) {
                                                    $query->the_post();
                                                    $lease_id = get_the_ID();
                                                    ?>
                                                    <tr>
                                                        <td data-suite-name>
                <?php
                echo ((get_post_meta(get_the_ID(), '_yl_suite_number', true) == -1) ? 'Y-membership' : get_post_meta(get_the_ID(), '_yl_suite_number', true));
                ?>
                                                        </td>								
                                                        <td><?php echo get_post_meta(get_the_ID(), '_yl_lease_start_date', true); ?></td>
                                                        <td>$<?php echo get_post_meta(get_the_ID(), '_yl_monthly_rent', true); ?> USD</td>
                                                        <td>$<?php echo get_post_meta(get_the_ID(), '_yl_security_deposit', true); ?> USD</td>
                                                        <td class="form-group lease_properties_group">
                                                            <h5>Copy Machine Password</h5>
                                                            <label>Password <a class="yl_tinfo_copy_machine" data-view_pass_id = "<?php echo $lease_id; ?>" href="javascript:void(0)"><i class="fa fa-eye" aria-hidden="true"></i></a>	</label>
                                                            <input type="password" class="form-control" name="yl_tinfo_copy_machine_<?php echo $lease_id; ?>" id="yl_tinfo_copy_machine_<?php echo $lease_id; ?>" value="<?php echo esc_attr(get_post_meta(get_the_ID(), '_yl_tinfo_copy_machine', true)); ?>" /><br />
                                                            <h5>Website Conference Room Log-in - Meeting Calendar</h5>
                                                            <label>Username</label>
                                                            <input type="text" class="form-control" name="yl_tinfo_user_name_<?php echo $lease_id; ?>" id="yl_tinfo_user_name_<?php echo $lease_id; ?>" value="<?php echo esc_attr(get_post_meta(get_the_ID(), '_yl_tinfo_user_name', true)); ?>" /> <br />
                                                            <label>Password <a class="yl_tinfo_password" data-view_pass_id = "<?php echo $lease_id; ?>" href="javascript:void(0)"><i class="fa fa-eye" aria-hidden="true"></i></a></label>
                                                            <input type="password" class="form-control" name="yl_tinfo_password_<?php echo $lease_id; ?>" id="yl_tinfo_password_<?php echo $lease_id; ?>" value="<?php echo esc_attr(get_post_meta(get_the_ID(), '_yl_tinfo_password', true)); ?>" /> <br />
                                                            <h5>Postage Machine Password</h5>
                                                            <label>Password <a class="yl_tinfo_postage_password" data-view_pass_id = "<?php echo $lease_id; ?>" href="javascript:void(0)"><i class="fa fa-eye" aria-hidden="true"></i></a></label>
                                                            <input type="password" class="form-control" name="yl_tinfo_postage_password_<?php echo $lease_id; ?>" id="yl_tinfo_postage_password_<?php echo $lease_id; ?>" value="<?php echo esc_attr(get_post_meta(get_the_ID(), '_yl_tinfo_postage_password', true)); ?>" /><br />
                                                            <label>Account #</label>
                                                            <input type="text" class="form-control" name="yl_tinfo_account_number_<?php echo $lease_id; ?>" id="yl_tinfo_account_number_<?php echo $lease_id; ?>" value="<?php echo esc_attr(get_post_meta(get_the_ID(), '_yl_tinfo_account_number', true)); ?>" /><br />

                                                            <h5>Fobs</h5>  
                                                            <label>Fob #1 name</label>                                  
                                                            <input type="text" class="form-control" name="yl_tinfo_fob_1_name_<?php echo $lease_id; ?>" id="yl_tinfo_fob_1_name_<?php echo $lease_id; ?>" value="<?php echo esc_attr(get_post_meta(get_the_ID(), '_yl_tinfo_fob_1_name', true)); ?>" /> <br />
                                                            <label>Fob #1 #(s)</label>
                                                            <input type="text" class="form-control" name="yl_tinfo_fob_1_no_<?php echo $lease_id; ?>" id="yl_tinfo_fob_1_no_<?php echo $lease_id; ?>" value="<?php echo esc_attr(get_post_meta(get_the_ID(), '_yl_tinfo_fob_1_no', true)); ?>" /> <br />
                                                            <label>Fob #2 name</label>
                                                            <input type="text" class="form-control" name="yl_tinfo_fob_2_name_<?php echo $lease_id; ?>" id="yl_tinfo_fob_2_name_<?php echo $lease_id; ?>" value="<?php echo esc_attr(get_post_meta(get_the_ID(), '_yl_tinfo_fob_2_name', true)); ?>" /> <br />
                                                            <label>Fob #2 #(s)</label>
                                                            <input type="text" class="form-control" name="yl_tinfo_fob_2_no_<?php echo $lease_id; ?>" id="yl_tinfo_fob_2_no_<?php echo $lease_id; ?>" value="<?php echo esc_attr(get_post_meta(get_the_ID(), '_yl_tinfo_fob_2_no', true)); ?>" /> <br />  
                                                            <label>Fob #3 name</label>
                                                            <input type="text" class="form-control" name="yl_tinfo_fob_3_name_<?php echo $lease_id; ?>" id="yl_tinfo_fob_3_name_<?php echo $lease_id; ?>" value="<?php echo esc_attr(get_post_meta(get_the_ID(), '_yl_tinfo_fob_3_name', true)); ?>" /> <br />
                                                            <label>Fob #3 #(s)</label>
                                                            <input type="text" class="form-control" name="yl_tinfo_fob_3_no_<?php echo $lease_id; ?>" id="yl_tinfo_fob_3_no_<?php echo $lease_id; ?>" value="<?php echo esc_attr(get_post_meta(get_the_ID(), '_yl_tinfo_fob_3_no', true)); ?>" /> <br />                                                                       

                                                            <h5>Tenant Building Directory</h5>
                                                            <label style="margin-top:0;">Name as you wish it to appear</label> 
                                                            <input type="text" class="form-control" name="yl_tinfo_name_as_you_wish_<?php echo $lease_id; ?>" id="yl_tinfo_name_as_you_wish_<?php echo $lease_id; ?>" value="<?php echo esc_attr(get_post_meta(get_the_ID(), '_yl_tinfo_name_as_you_wish', true)); ?>" />
                                                        </td>
                                                        <td valign="bottom"><a class="btn btn-primary btn-xs btn_copier_save" data-lease_id = "<?php echo $lease_id; ?>" href="javascript:void(0)"><i class="fa fa-floppy-o" aria-hidden="true"></i> Save</a> <br /><br /><br /> <!--<a class="btn btn-primary btn-xs" href="/my-account/?tab=lease&request_profile=<?php echo get_post_meta(get_the_ID(), '_yl_lease_user', true); ?>">View Profile</a>--></td>
                                                    </tr>

                <?php
            }
            wp_reset_postdata();
        } else {
            echo "<p>Not found.</p>";
        }
        ?>

                                        </tbody>
                                    </table>
                                </div>
                            </div>



                        </div><!--company_details-->
                    </div><!-- .entry-content -->		

                </article><!-- #post-## -->
        <?php
    // End the loop.
    endwhile;
} else {
    echo "Only building manager can access this page!";
    wp_login_form();
}
?>		
    </div><!-- .container -->
</div><!--content-area-->

<script type="text/javascript">
    jQuery(document).ready(function ($) {
        jQuery(".btn_copier_save").click(function (e) {

            var leas_id = jQuery(this).data("lease_id");

            var dataContainer = {
                leas_id: leas_id,
                tinfo_copy_machine: jQuery("#yl_tinfo_copy_machine_" + leas_id + "").val(),
                yl_tinfo_user_name: jQuery("#yl_tinfo_user_name_" + leas_id + "").val(),
                yl_tinfo_password: jQuery("#yl_tinfo_password_" + leas_id + "").val(),
                yl_tinfo_postage_password: jQuery("#yl_tinfo_postage_password_" + leas_id + "").val(),
                yl_tinfo_account_number: jQuery("#yl_tinfo_account_number_" + leas_id + "").val(),
                yl_tinfo_fob_1_name: jQuery("#yl_tinfo_fob_1_name_" + leas_id + "").val(),
                yl_tinfo_fob_1_no: jQuery("#yl_tinfo_fob_1_no_" + leas_id + "").val(),
                yl_tinfo_fob_2_name: jQuery("#yl_tinfo_fob_2_name_" + leas_id + "").val(),
                yl_tinfo_fob_2_no: jQuery("#yl_tinfo_fob_2_no_" + leas_id + "").val(),
                yl_tinfo_fob_3_name: jQuery("#yl_tinfo_fob_3_name_" + leas_id + "").val(),
                yl_tinfo_fob_3_no: jQuery("#yl_tinfo_fob_3_no_" + leas_id + "").val(),
                yl_tinfo_name_as_you_wish: jQuery("#yl_tinfo_name_as_you_wish_" + leas_id + "").val(),
                action: 'yl_lease_company_edit'
            };



            jQuery.ajax({
                action: "yl_lease_company_edit",
                type: "POST",
                dataType: "json",
                url: ajaxurl,
                data: dataContainer,
                beforeSubmit: function () {
                    //jQuery('#submit_email_this_record').val('Sending...');
                },
                success: function (data) {
                    alert(data.msg);
					location.reload();

                }
            });

        });
		
		
        jQuery(".yl_tinfo_copy_machine").click(function (e) {
            var view_pass_id = jQuery(this).data("view_pass_id");
			var change_input_copy_machine = '#yl_tinfo_copy_machine_'+view_pass_id;
			swip_password_text(change_input_copy_machine);	
		});
		
		jQuery(".yl_tinfo_password").click(function (e) {
            var view_pass_id = jQuery(this).data("view_pass_id");
			var change_input_copy_machine = '#yl_tinfo_password_'+view_pass_id;
			swip_password_text(change_input_copy_machine);	
		});
		
		jQuery(".yl_tinfo_postage_password").click(function (e) {
            var view_pass_id = jQuery(this).data("view_pass_id");
			var change_input_copy_machine = '#yl_tinfo_postage_password_'+view_pass_id;
			swip_password_text(change_input_copy_machine);	
		});	
		
    });
	
	function swip_password_text(input_id){
		var inputType = jQuery(input_id).attr('type');
		if(inputType == 'password'){
			jQuery(input_id).attr('type', 'text');
		}else{
			jQuery(input_id).attr('type', 'password');
		}	
	}
	
</script>

<style type="text/css">
    .lease_properties_group h5{ margin-bottom:15px; font-size:13px; border-bottom:1px solid #C4C4C4; font-weight:600;}
    .lease_properties_group label{ width:28%; margin-right:4%; margin-top:8px; color:#000000; font-size:13px; font-weight:600; float:left;}
    .lease_properties_group input{ width:68%;}
</style>


<?php get_footer(); ?>
