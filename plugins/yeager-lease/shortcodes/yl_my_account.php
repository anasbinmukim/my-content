<?php

function get_yl_client_cpt_id_by_user_id($current_user_id) {
    $args_client = array(
        'post_type' => 'sa_client',
        'posts_per_page' => 1,
        'meta_query' => array(
            array(
                'key' => '_associated_users',
                'value' => $current_user_id,
                'compare' => '='
            ),
        ),
    );
    $query_client = new WP_Query($args_client);
    $client_cpt_id = 0;
    if ($query_client->have_posts()) {
        global $post;
        while ($query_client->have_posts()) {
            $query_client->the_post();
            $client_cpt_id = $post->ID;
        }
        wp_reset_query();
    }


    return $client_cpt_id;
}

function get_yl_mylease_company_name($current_user_id) {
    $args = array(
        'post_type' => 'lease',
        'meta_query' => array(
            array(
                'key' => '_yl_lease_user',
                'value' => $current_user_id,
                'compare' => '='
            ),
        ),
    );
    $query = new WP_Query($args);

    $lease_company = array();
    $lease_id = '';
    if ($query->have_posts()) {
        global $post;
        while ($query->have_posts()) {
            $query->the_post();
            $lease_company[] = esc_html(get_the_title(get_post_meta($post->ID, '_yl_company_id', true)));
            //$lease_id = $post->ID;
            //echo get_the_title( $company_id );
        }
        wp_reset_query();
    }

    $lease_company = array_unique($lease_company);

    //print_r($lease_company);

    $company_names = implode(' / ', $lease_company);

    return $company_names;
}

function yl_my_account_function($atts, $content = null) {
    ob_start();
    if (is_user_logged_in()):
	if(current_user_can( 'building_manager' ) || current_user_can( 'lease_client' )){
		//all to access this page
	}else{
		return;
	}

        if (isset($_GET['request_profile'])) {
            $current_user_id = $_GET['request_profile'];
        } else {
            if (get_user_meta(get_current_user_id(), '_user_parent', true)) {
                $current_user_id = get_user_meta(get_current_user_id(), '_user_parent', true);
            } else {
                $current_user = wp_get_current_user();
                $current_user_id = get_current_user_id();
            }
        }

        //associates sa_client post ID of current user
        $client_cpt_id = get_yl_client_cpt_id_by_user_id($current_user_id);

        $myprofile_info = get_userdata($current_user_id);

		$switch_link = '';
		if ( is_super_admin() ) {
			$switch_link_url = wp_nonce_url( add_query_arg( array(
				'action'  => 'switch_to_user',
				'user_id' => $current_user_id,
			), wp_login_url() ), "switch_to_user_".$current_user_id );
			$switch_link = ' | <a style="color:#2ea3f2;" href="'.esc_attr($switch_link_url).'">Switch To</a>';
		}

        echo "<h2>Manage My Account - ".esc_html($myprofile_info->display_name)." ". $switch_link ."</h2>";

        if (get_user_meta(get_current_user_id(), '_user_parent', true)) {
            $parent_info = get_userdata($current_user_id);
            echo '<h4>Parent User: ' . esc_html($parent_info->first_name) . ' ' . esc_html($parent_info->last_name) . '</h4><br>';
            $subuser_permissions = explode(",", get_user_meta(get_current_user_id(), '_user_permissions', true));
            if (in_array("Directory Access", $subuser_permissions)) {
                $current_tab = 'information';
            } else {
                $current_tab = '';
            }
        } else {
            $current_tab = 'lnvoices';
        }

        if (isset($_GET['tab']) && ($_GET['tab'] == 'information')) {
            $current_tab = 'information';
        } elseif (isset($_GET['tab']) && ($_GET['tab'] == 'lease')) {
            $current_tab = 'lease';
        } elseif (isset($_GET['tab']) && ($_GET['tab'] == 'payments')) {
            $current_tab = 'payments';
        } elseif (isset($_GET['tab']) && ($_GET['tab'] == 'lnvoices')) {
            $current_tab = 'lnvoices';
        } elseif (isset($_GET['tab']) && ($_GET['tab'] == 'billing_information')) {
            $current_tab = 'billing_information';
        } elseif (isset($_GET['tab']) && ($_GET['tab'] == 'users')) {
            $current_tab = 'users';
        } elseif (isset($_GET['tab']) && ($_GET['tab'] == 'ystore')) {
            $current_tab = 'ystore';
        } elseif (isset($_GET['tab']) && ($_GET['tab'] == 'calendar')) {
            $current_tab = 'calendar';
        } else {
            if (get_user_meta(get_current_user_id(), '_user_parent', true)) {
                $subuser_permissions = explode(",", get_user_meta(get_current_user_id(), '_user_permissions', true));
                if (in_array("Directory Access", $subuser_permissions)) {
                    $current_tab = 'information';
                } else {
                    $current_tab = '';
                }
            } else {
                $current_tab = 'lnvoices';
            }
            //$current_tab = 'information';
        }


        if (isset($_GET['tab']) && ($_GET['tab'] == 'lease') && ($_GET['clear'] == 'available') && isset($_GET['lid'])) {
            $lease_id = $_GET['lid'];
            $suite_id = yl_get_suite_id_by_lease_id($lease_id);

            update_post_meta($suite_id, '_yl_available', 'No');
            ///update_post_meta($suite_id, '_yl_available_date', '');
            update_post_meta($suite_id, '_yl_early_vacate_addendum', '');
            update_post_meta($suite_id, '_yl_date_vacate_notice_given', '');

            update_post_meta($lease_id, '_yl_va_lessee', '');
            update_post_meta($lease_id, '_yl_va_building', '');
            update_post_meta($lease_id, '_yl_va_business_name', '');
            update_post_meta($lease_id, '_yl_va_security_deposit_held', '');
            update_post_meta($lease_id, '_yl_date_vacate_notice_given', '');
            update_post_meta($lease_id, '_yl_early_vacate_addendum', '');
            update_post_meta($lease_id, '_yl_ninty_day_vacate_date', '');
            update_post_meta($lease_id, '_yl_suites_leased', '');
            update_post_meta($lease_id, '_yl_suites_identified_agreement', '');
            update_post_meta($lease_id, '_yl_all_n_demand_multiple_suites', '');
            update_post_meta($lease_id, '_yl_tenant_contact_email', '');
            update_post_meta($lease_id, '_yl_va_cell_phone', '');
            update_post_meta($lease_id, '_yl_tenant_forwarding_address', '');
            update_post_meta($lease_id, '_yl_vn_client_signature_date', '');
            update_post_meta($lease_id, '_yl_vn_client_signature', '');
            update_post_meta($lease_id, '_yl_vn_bm_signature_date', '');
            update_post_meta($lease_id, '_yl_vn_bm_signature', '');
            update_post_meta($lease_id, '_yl_vacate_notice_pdf', '');
            update_post_meta($lease_id, '_yl_va_client_signature_date', '');
            update_post_meta($lease_id, '_yl_va_client_signature', '');
            update_post_meta($lease_id, '_yl_va_bm_signature_date', '');
            update_post_meta($lease_id, '_yl_va_bm_signature', '');
            update_post_meta($lease_id, '_yl_early_vacate_addendum_pdf', '');
        }
        ?>
        <style type="text/css">
            h1.entry-title.main_title, .company_info_form .new_company { display:none; }
            ul.nav-pills { padding-left: 0 !important; }
            .company_info_form .form-control { width: auto; display: inline-block; }
            .company_info_form strong { display: block; }
            .company_info_form input[type="submit"] { margin-bottom: 20px; }
            .table-striped .company_info_form input[readonly="readonly"] { background: #b8b8b8; }
        </style>

        <script type="text/javascript">
            jQuery(document).ready(function () {
                jQuery(".company_info_form .edit_input").on("click", function () {
                    var input = jQuery(this).prev();
                    input.removeAttr("readonly");
                    var selection = jQuery(this).prev();
                    selection.removeAttr("disabled");
                    jQuery(this).parent().siblings(".btn").show();
                });

                jQuery(".company_info_form .lease_company").on("change", function () {
                    var company = jQuery(this).val();
                    if (company == '') {
                        jQuery(this).parent().siblings(".new_company").show();
                    } else {
                        jQuery(this).parent().siblings(".new_company").hide();
                    }
                });

                jQuery("input[type=checkbox]").click(function () {
                    if (jQuery(this).prop("checked") != true) {
                        var check_id = jQuery(this).attr("data-id");
                        jQuery("#checkbox_value_" + check_id).removeAttr("disabled");
                    } else {
                        var check_id = jQuery(this).attr("data-id");
                        jQuery("#checkbox_value_" + check_id).attr("disabled", "disabled");
                    }
                });
            });
        </script>

        <ul class="nav nav-pills current_tab_<?php echo $current_tab; ?>" role="tablist">
            <?php
            if (get_user_meta(get_current_user_id(), '_user_parent', true)) {
                $user_permissions = explode(",", get_user_meta(get_current_user_id(), '_user_permissions', true));
                if (in_array("Directory Access", $user_permissions)) {
                    ?>
                                            <!--<li role="presentation" <?php //if ($current_tab == 'information') {   ?> class="active" <?php //}   ?> ><a href="/my-account/?tab=information&request_profile=<?php //echo $current_user_id;   ?>">Information</a></li>-->
                    <?php
                }
                if (in_array("View Past Invoices", $user_permissions)) {
                    ?>
                    <li role="presentation" <?php if ($current_tab == 'lnvoices') { ?> class="active nav-item-invoice" <?php }else{ ?> class="nav-item-invoice" <? } ?> ><a href="/my-account/?tab=lnvoices&request_profile=<?php echo $current_user_id; ?>">Invoices</a></li>
                    <?php
                }
                if (in_array("View Signed Lease", $user_permissions)) {
                    ?>
                    <li role="presentation" <?php if ($current_tab == 'lease') { ?> class="active nav-item-lease" <?php }else{ ?> class="nav-item-lease" <? } ?> ><a href="/my-account/?tab=lease&request_profile=<?php echo $current_user_id; ?>">Lease(s)</a></li>
                    <?php
                }
                if (in_array("Billing Access", $user_permissions)) {
                    ?>
                    <?php if (is_plugin_active('sprout-invoices-addon-auto-billing/auto-billing.php')) { ?>
            			<li role="presentation" <?php if ($current_tab == 'billing_information') { ?> class="active nav-item-billing" <?php }else{ ?> class="nav-item-billing" <? } ?> ><a href="/my-account/?tab=billing_information&request_profile=<?php echo $current_user_id; ?>">Billing Information</a></li>
				 	<?php } ?>
                    <?php
                }
                ?>
            <?php } else { ?>
            <!--<li role="presentation" <?php if ($current_tab == 'information') { ?> class="active" <?php } ?> ><a href="/my-account/?tab=information&request_profile=<?php echo $current_user_id; ?>">Information</a></li>-->
                <li role="presentation" <?php if ($current_tab == 'lnvoices') { ?> class="active nav-item-invoice" <?php }else{ ?> class="nav-item-invoice" <? } ?> ><a href="/my-account/?tab=lnvoices&request_profile=<?php echo $current_user_id; ?>">Invoices</a></li>
                <li role="presentation" <?php if ($current_tab == 'payments') { ?> class="active nav-item-payment" <?php }else{ ?> class="nav-item-payment" <? } ?> ><a href="/my-account/?tab=payments&request_profile=<?php echo $current_user_id; ?>">Payments</a></li>
                <li role="presentation" <?php if ($current_tab == 'lease') { ?> class="active nav-item-lease" <?php }else{ ?> class="nav-item-lease" <? } ?> ><a href="/my-account/?tab=lease&request_profile=<?php echo $current_user_id; ?>">Lease(s)</a></li>

            <!--Remove the billing tab until we roll this out to master-->
			<?php if (is_plugin_active('sprout-invoices-addon-auto-billing/auto-billing.php')) { ?>
            <li role="presentation" <?php if ($current_tab == 'billing_information') { ?> class="active nav-item-billing" <?php }else{ ?> class="nav-item-billing" <? } ?> ><a href="/my-account/?tab=billing_information&request_profile=<?php echo $current_user_id; ?>">Billing Information</a></li>
			<?php } ?>

                <li role="presentation" <?php if ($current_tab == 'users') { ?> class="active nav-item-users" <?php }else{ ?> class="nav-item-users" <? } ?> ><a href="/my-account/?tab=users&request_profile=<?php echo $current_user_id; ?>">User Information</a></li>
                <?php
                if (is_plugin_active('cw-products/cw-products.php')) {
                    // Only show y-store tab if products plugin is active
                    ?>
                    <li role="presentation" <?php if ($current_tab == 'ystore') { ?> class="active nav-item-ystore" <?php }else{ ?> class="nav-item-ystore" <? } ?> ><a href="/my-account/?tab=ystore&request_profile=<?php echo $current_user_id; ?>">Y-Store</a></li>
                    <?php
                }
                ?>
                <?php global $current_user;
                if (is_plugin_active('simplexity-room-calendar/SimplexityRoomCalendar.php') && in_array('administrator',$current_user->roles)) {
                    // Only show y-store tab if products plugin is active
                    ?>
                    <li role="presentation" <?php if ($current_tab == 'calendar') { ?> class="active nav-item-calendar" <?php }else{ ?> class="nav-item-calendar" <? } ?> ><a href="/my-account/?tab=calendar&request_profile=<?php echo $current_user_id; ?>">Calendar</a></li>
                    <?php
                }
                ?>

            <?php } ?>
        </ul>

        <?php
		if ($current_tab == 'ystore') {
            include_once(CW_PRODUCTS_ROOT . 'controllers/products_controller.php');
            include_once(CW_PRODUCTS_ROOT . 'helpers/products_helper.php');

            $current_user_id = get_current_user_id();
            if (isset($_GET['request_profile'])) {
                $current_user_id = $_GET['request_profile'];
            }

            // Lease list starts here.
            $products = get_products('suite');
            /*
            $args = array(
                'post_type' => 'lease',
                'post_status' => 'all',
                'numberposts' => 1,
                'posts_per_page' => 1,
                'meta_query' => array(
                    array(
                        'key' => '_yl_lease_user',
                        'value' => $current_user_id,
                        'compare' => '=',
                    ),
                ),
            );
            $leases = get_posts($args);

            $suite_id = get_post_meta($lease->ID, '_yl_product_id', true);
            */
            /*
            $lease_meta_company_id = get_post_meta($lease->ID, '_yl_company_id', true);
            $lease_id = $lease->ID;
            $lease_id = $lease_meta_company_id;
            */
            $client_id = yl_get_client_id_by_user_id($current_user_id);
            $lease_id = $client_id;
            $suite_id = $client_id;
            ?>

            <table id="products_table" class="table table-striped cw_table">
                <thead>
                    <tr>
                        <th>Item</th>
                        <th>Cost</th>
                        <th>Invoice Total</th>
                        <!-- <th>Quantity</th> -->
                        <th>Options</th>
                        <th></th>
                    </tr>
                </thead>

                <tbody>
                    <?php
                    foreach ($products as $p) {
                        $status = FALSE;
                        $upgrade = array();
                        $upgrade = get_post_meta($lease_id, 'yl_lease_upgrade_products', true);
                        $products_ids = explode(',', $upgrade);

                        if (in_array($p->ID, $products_ids)) {
                            $upgrade_meta = get_post_meta($lease_id, 'yl_lease_upgrade_details_' . $p->ID, true);
                            $status = $upgrade_meta['is_active'];
                        }

                        $variations = get_post_meta($p->ID, CMB2_PREFIX . 'variation', true);
                        $accepts_multiple = get_post_meta($p->ID, '_cw_multiple', true);
                        ?>

                        <tr id="<?php echo $p->ID; ?>_<?php echo $lease_id; ?>">

                            <form method="post" id="itupgrade_invoice_<?php echo $o->ID; ?>" class="itupgrade_invoice">
                                <input type="hidden" name="submit_form_<?php echo $p->ID; ?>" value="1" id="submit_form_<?php echo $p->ID; ?>">
                                <input type="hidden" name="product_title_<?php echo $p->ID; ?>" id="product_title_<?php echo $p->ID; ?>" value="<?php echo esc_attr(get_the_title($p->ID)); ?>">
                                <input type="hidden" name="lease_title_<?php echo $p->ID; ?>" id="lease_title_<?php echo $p->ID; ?>" value="<?php echo esc_attr(get_the_title($lease_id)); ?>">
                                <input  type="hidden" name="lease_id" value="<?php echo $lease_id; ?>" id="lease_id">
                                <input  type="hidden" name="product_id" value="<?php echo $p->ID; ?>" id="product_id">
                            </form>

                            <!-- Product name -->
                            <td class="td_product_name">
                                <?php echo ($status == TRUE) ? esc_html($p->post_title) : esc_html($p->post_title); ?>
                            </td>

                            <!-- cost -->
                            <td class="td_cost">
                                $<span id="monthly_cost_<?php echo $p->ID; ?>"><?php echo esc_html($variations[$upgrade_meta['variation']]['cost']); ?> <?php echo (($variations[$upgrade_meta['variation']] == 'monthly') ? "per month" : "each"); ?></span>
                            </td>

                            <!-- setup fee -->
                            <td class="td_setup_fee">
                                <?php
                                if ($variations[$upgrade_meta['variation']]['setup_fee']) { ?>
                                    $<span id="onetime_cost_<?php echo $p->ID; ?>"><?php echo esc_html($variations[$upgrade_meta['variation']]['setup_fee']); ?></span>
                                    <?php
                                }
                                else {
                                    ?>
                                    $<span id="onetime_cost_<?php echo $p->ID; ?>">
                                    <?php echo __('no setup fee', 'yl'); ?>
                                    </span>
                                    <?php
                                }
                                ?>
                            </td>

                            <!-- Variations dropdown -->
                            <td style="max-width:222px;" class="td_variation">
                                <?php
                                get_cw_product_variation($p->ID, $lease_id, $status);
                                ?>
                            </td>

                            <!-- Options/buttons column -->
                            <td class="td_options">


                                <?php
                                if (($status == TRUE) && ($accepts_multiple == 'no')) {
                                    ?>
                                    <span class="spinner spinner_<?php echo $p->ID; ?>"><img src="<?php echo includes_url(); ?>images/spinner.gif" alt="Loading"></span>
                                    <button  name="remove" id="remove_upgrade_<?php echo $p->ID ?>" data-product_id="<?php echo $p->ID ?>" value="remove_upgrade" class="remove_upgrade btn btn-danger btn-xs pull-right"><?php echo __( 'Remove Upgrade', 'yl' ); ?></button>
                                    <?php
                                }
                                else {
                                    ?>
                                    <span class="spinner spinner_<?php echo $p->ID; ?>"><img src="<?php echo includes_url(); ?>images/spinner.gif" alt="Loading"></span>
                                    <button  name="create" id="create_invoice_<?php echo $p->ID ?>" value="create_invoice" data-lease-id="<?php echo $lease_id; ?>" data-product-id="<?php echo $p->ID; ?>" class="create_invoice_btn btn btn-info pull-right btn-xs"><?php echo __( 'Purchase', 'yl' ); ?></button>
                                    <?php
                                }
                                ?>
                            </td>

                        </tr>

                        <?php
                    }
                    ?>
                </tbody>
            </table>

            <!-- Modal -->
            <div class="modal fade" id="confirmPurchaseModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <input  type="hidden" name="model_post_id" value="" id="model_post_id">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                            <h4 class="modal-title" id="myModalLabel"><?php echo __( 'Purchase Confirmation', 'yl' ); ?></h4>
                        </div>
                        <div class="modal-body">
                            Are you sure you would like to purchase this/these <strong id="product_title"></strong>?
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal"><?php echo __( 'No', 'yl' ); ?></button>
                            <button type="button" class="btn btn-primary" id="submt_model" ><?php echo __( 'Yes', 'yl' ); ?></button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal fade" id="returnMessageModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <input  type="hidden" name="model_post_id" value="" id="model_post_id">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                            <h4 class="modal-title" id="myModalLabel"><?php echo __( 'Purchase Confirmation', 'yl' ); ?></h4>
                        </div>
                        <div class="modal-body">

                        </div>
                        <div class="modal-footer">
                            <a href="" class="btn-view-invoice btn btn-success pull-left" target="_blank"><?php echo __( 'View Invoice', 'yl' ); ?></a>
                            <button type="button" class="btn-close-modal-refresh btn btn-secondary" data-dismiss="modal"><?php echo __( 'Close', 'yl' ); ?></button>
                        </div>
                    </div>
                </div>
            </div>


            <style>
            .spinner { display:none; float: right; }
            .modal{ top: 30% !important; }
            </style>

            <script>
                jQuery(document).ready(function ($) {

                    jQuery('body').on('keyup', '.form-product-qty', function(e) {
                        var _t = jQuery(this);
                        var _p = _t.closest('tr');
                        _p.find('.variation_type').change();
                    });

                    jQuery('body').on('keydown', '.form-product-qty', function(e) {
                        if (jQuery.inArray(e.keyCode, [46, 8, 9, 27, 13, 110, 190]) !== -1 ||
                             // Allow: Ctrl+A, Command+A
                            (e.keyCode === 65 && (e.ctrlKey === true || e.metaKey === true)) ||
                             // Allow: home, end, left, right, down, up
                            (e.keyCode >= 35 && e.keyCode <= 40)) {
                                 return;
                        }
                        // Ensure that it is a number and stop the keypress
                        if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
                            e.preventDefault();
                        }

                    });

                    jQuery('body').on('change', '.variation_type', function () {
                        var _t = jQuery(this);
                        var _p = _t.closest('tbody');
                        var _p = _t.closest('tr');
                        var r = _t.find(':selected');

                        var onetime_cost = r.data('onetime_cost');
                        var monthly_cost = r.data('monthly_cost');
                        var product_id = r.data('product_id');
                        var billing_type = r.data('billing_type');

                        if (_p.find('.form-product-qty').length > 0) {
                            _qty = _p.find('.form-product-qty').val();
                            _p.find('#onetime_cost_' + product_id).text(onetime_cost*_qty);
                            _p.find('#monthly_cost_' + product_id).parent().html(onetime_cost+' '+billing_type);
                        }
                        else {
                            _p.find('#onetime_cost_' + product_id).text(onetime_cost);
                            _p.find('#monthly_cost_' + product_id).text(monthly_cost + ' ' + billing_type);
                        }

                        var t = r.text();
                        jQuery('#confirmPurchaseModal #product_title').text(t);
                    }).find('.variation_type').change();

                    jQuery('body').on('click', '.create_invoice_btn', function(e) {
                        var _t = jQuery(this);
                        var _lease_id = _t.attr('data-lease-id');
                        var _p = _t.closest('tbody');
                        var _ptr = _t.closest('tr');

                        jQuery('#confirmPurchaseModal .modal-body').html('Are you sure you would like to purchase this/these <strong id="product_title"></strong> for <strong id="total_cost"></strong>?');
                        jQuery('#confirmPurchaseModal #myModalLabel').text('Purchase Confirmation');

                        _ptr.find('.variation_type').change();
                        var variation_type = _ptr.find('.variation_type option:selected').attr('data-value');
                        var v_title = _ptr.find('.variation_type :selected').text();

                        var p_id = _t.attr('data-product-id');
                        var cost = _p.find('#onetime_cost_' + p_id).text();
                        var m_cost = _p.find('#monthly_cost_' + p_id).text();
                        var qty = _ptr.find('.form-product-qty').val();

                        var p_title = _p.find('#product_title_' + p_id).val();
                        var l_name = _p.find('#lease_title_' + p_id).val();

                        var submit = _p.find('#submit_form_' + p_id).val();

                        jQuery('#confirmPurchaseModal #onetime_cost').text(cost);
                        jQuery('#confirmPurchaseModal #monthly_cost').text(m_cost);

                        if (m_cost) {
                            var display_cost = m_cost;
                        }
                        else {
                            var display_cost = _ptr.find('.td_cost').html().replace('<br>', ' ');
                        }
                        jQuery('#confirmPurchaseModal #total_cost').html(display_cost);

                        jQuery('#lease_name').text(l_name);
                        jQuery('#confirmPurchaseModal').modal('show');

                        // Unique row number
                        jQuery('#confirmPurchaseModal #model_post_id').val(p_id+'_'+_lease_id);

                        if (submit == 0) {
                            _p.find('.spinner_'+p_id).show();
                            _t.hide();

                            var ajax_data = {
                                'action': 'my_account_products_purchase',
                                'product': {},
                                'lease_id': _lease_id,
                                'product_id' : p_id,
                                'create' : 'create_invoice'
                            };

                            if (qty) {
                                ajax_data = {
										'action': 'my_account_products_purchase',
                                    'product': {},
                                    'lease_id': _lease_id,
                                    'product_id' : p_id,
                                    'qty': qty,
                                    'create' : 'create_invoice'
                                };
                            }

                            var post_submit_form = 'submit_form_'+p_id;
                            ajax_data[post_submit_form] = 0;
                            ajax_data['product'][p_id] = {
                                'variation_type': variation_type
                            };

                            jQuery.post(ajaxurl, ajax_data, function(response) {
                                console.log(response);
								var response_obj = jQuery.parseJSON(response);

                                var uid = response_obj.uid;
                                var _tr = jQuery('tr#'+uid);

                                if (response_obj.variation) {
                                    _tr.find('.td_cost').text(response_obj.cost+' '+response_obj.frequency);
                                    _tr.find('.td_setup_fee').text(response_obj.setup_fee);
                                    _tr.find('.td_variation').text(response_obj.variation);
                                    _tr.find('.td_options').html('<span class="spinner spinner_'+response_obj.product_id+'"><img src="<?php echo includes_url(); ?>images/spinner.gif" alt="Loading"></span><button  name="remove" id="remove_upgrade_'+response_obj.product_id+'" data-product_id="'+response_obj.product_id+'" value="remove_upgrade" class="remove_upgrade btn btn-danger btn-xs pull-right"><?php echo __( 'Remove Upgrade', 'yl' ); ?></button> ');
                                }

                                jQuery('#returnMessageModal .modal-body').html(response_obj.message);
                                jQuery('#returnMessageModal .btn-view-invoice').attr('href', response_obj.invoice_link);
                                jQuery('#returnMessageModal').modal('show');

                                _p.find('.spinner_'+p_id).hide();
                                _t.show();
                                _p.find('#submit_form_' + p_id).val(1);
                            });
                        }
                        else {
                            _p.find('.spinner_'+p_id).hide();
                            _t.show();
                        }

                    });


                    jQuery('body').on('click', '.remove_upgrade', function () {
                        var _t = jQuery(this);
                        var _p = _t.closest('tbody');
                        var _ptr = _t.closest('tr');
                        var p_id = _t.data('product_id');
                        var _lease_id = _ptr.find('#lease_id').val();

                        var p_title = _ptr.find('#remove_product_id_' + p_id).text();
                        var l_name = _p.find('#lease_title_' + p_id).val();

                        var submit = _p.find('#submit_form_' + p_id).val();

                        jQuery('#confirmPurchaseModal #myModalLabel').text('Remove upgrade');
                        jQuery('#confirmPurchaseModal .modal-body').html('Are you sure you would like to remove upgrade <strong>' + p_title + '</strong> from <strong>' + l_name + '</strong>?');
                        jQuery('#confirmPurchaseModal #model_post_id').val(p_id+'_'+_lease_id);
                        jQuery('#confirmPurchaseModal').find('#submt_model').addClass('remove');
                        jQuery('#confirmPurchaseModal').modal('show');

                        if (submit == 0) {
                            _p.find('.spinner_'+p_id).show();
                            _t.hide();

                            var ajax_data = {
                                'action': 'my_account_products_remove',
                                'product': {},
                                'lease_id': _lease_id,
                                'product_id' : p_id,
                                'remove' : 'remove_upgrade',
                                'product_type' : ''
                            };
                            var post_submit_form = 'submit_form_'+p_id;
                            ajax_data[post_submit_form] = 0;
                            ajax_data['product'][p_id] = {
                                'variation_type': ''
                            };

                            jQuery.post(ajaxurl, ajax_data, function(response) {
                                var response_obj = jQuery.parseJSON(response);

                                //console.log(response);
                                var uid = response_obj.uid;
                                var _tr = jQuery('tr#'+uid);

                                _tr.find('.td_cost').html(response_obj.cost);
                                _tr.find('.td_setup_fee').html(response_obj.setup_fee);
                                _tr.find('.td_variation').html(response_obj.select_html);

                                _tr.find('.td_options').html('<span class="spinner spinner_'+response_obj.product_id+'"><img src="<?php echo includes_url(); ?>images/spinner.gif" alt="Loading"></span><button  name="create" id="create_invoice_'+response_obj.product_id+'" value="create_invoice" data-lease-id="'+response_obj.lease_id+'" data-product-id="'+response_obj.product_id+'" class="create_invoice_btn btn btn-info btn-xs pull-right"><?php echo __( 'Purchase', 'yl' ); ?></button> ');
                                _tr.find('.variation_type').change();

                                _p.find('.spinner_'+p_id).hide();
                                _t.show();
                                _ptr.find('#submit_form_' + p_id).val(1);
                                jQuery('#confirmPurchaseModal').find('#submt_model').removeClass('remove');
                            });
                        }
                        else {
                            _p.find('.spinner_'+p_id).hide();
                            _t.show();
                        }

                    });


                    jQuery('#submt_model').on('click', function () {
                        var uid = jQuery('#confirmPurchaseModal #model_post_id').val();
                        var pid = uid.split('_')[0];
                        jQuery('#'+uid+' #submit_form_' + pid).val(0);
                        jQuery('#'+uid+' #create_invoice_' + pid).click();
                        jQuery('#'+uid+' #remove_upgrade_' + pid).click();
                        jQuery('#confirmPurchaseModal').modal('hide');
                    });

                });
            </script>

            <?php
        }


        if ($current_tab == 'information'){
            if (isset($_POST['company_info_submit'])) {
                $company_id = $_POST['company_id'];
                $lease_id = $_POST['lease_id'];

                if (isset($_POST['company_address'])) {
                    update_post_meta($lease_id, '_yl_l_street_address', esc_html($_POST['company_address']));
                }
                if (isset($_POST['company_phone'])) {
                    update_post_meta($lease_id, '_yl_l_phone', esc_html($_POST['company_phone']));
                }
                if (isset($_POST['client_email']) && is_email($_POST['client_email']) ) {
                    update_post_meta($lease_id, '_yl_l_email', esc_html($_POST['client_email']));
                    wp_update_user(array('ID' => $current_user_id, 'user_email' => esc_html($_POST['client_email'])));
                }
                if (isset($_POST['include_in_com_dir'])) {
                    update_post_meta($company_id, '_include_in_company_directory', esc_html($_POST['include_in_com_dir']));
                } else {
                    update_post_meta($company_id, '_include_in_company_directory', 'No');
                }
                if (isset($_POST['checkbox_value'])) {
                    update_post_meta($lease_id, '_include_in_company_directory', esc_html($_POST['checkbox_value']));
                }

                //print_r($_POST);

                if (isset($_POST['new_company']) && ($_POST['new_company'] != '')) {
                    $user_id = get_current_user_id();
                    $new_post = array(
                        'post_type' => 'company',
                        'post_title' => wp_strip_all_tags($_POST['new_company']),
                        'post_author' => $user_id,
                        'post_status' => 'publish'
                    );

                    // Insert the post into the database
                    $new_company_id = wp_insert_post($new_post);
                    update_post_meta($lease_id, '_yl_company_id', $new_company_id);
                } elseif (isset($_POST['lease_company'])) {
                    update_post_meta($lease_id, '_yl_company_id', $_POST['lease_company']);
                }

                echo '<p style="color: green;">Updated Successfully</p>';
            }
            $args = array(
                'post_type' => 'lease',
                'meta_query' => array(
                    array(
                        'key' => '_yl_lease_user',
                        'value' => $current_user_id,
                        'compare' => '='
                    ),
                ),
            );
            $query = new WP_Query($args);

            $lease_id = '';
            if ($query->have_posts()) {
                global $post;
                while ($query->have_posts()) {
                    $query->the_post();
                    $company_id = get_post_meta($post->ID, '_yl_company_id', true);
                    $lease_id = $post->ID;

                    $suite_number = get_post_meta($lease_id, '_yl_suite_number', true);
                    if ($suite_number == -1)
                        $suite_number = 'Y-Membership';
                    ?>
                    <form action="" method="post" class="company_info_form">
                        <h2><strong><?php echo $suite_number; ?>:</strong></h2>
                        <!--<p><strong>Company:</strong> <input type="text" name="company_<?php //echo $company_id;     ?>_name" value="<?php //echo get_the_title( $company_id );     ?>" readonly="readonly" class="form-control" /><img class="edit_input" src="http://devsite-yeagercommunity-com.yeagercomm.staging.wpengine.com/wp-content/plugins/yeager-lease/images/edit.png" alt="Edit" data-edit="credit_card_line_fee"></p>-->
                        <p><strong>Company:</strong> <select name="lease_company" disabled="disabled" class="form-control lease_company">
                                <option value="">New company</option>
                                <?php
                                $args = array(
                                    'post_type' => 'company',
                                    'orderby' => 'post_title',
                                    'order' => 'ASC',
                                    'numberposts' => -1,
                                );
                                $companies = get_posts($args);

                                foreach ($companies as $compny) {
                                    ?>
                                    <option value="<?php echo $compny->ID; ?>" <?php if ($compny->ID == $company_id) echo 'selected="selected"'; ?>><?php echo esc_html($compny->post_title); ?></option>
                                    <?php
                                }
                                ?>
                            </select><img class="edit_input" src="<?php echo YL_URL; ?>images/edit.png" alt="Edit" data-edit="credit_card_line_fee">
                        </p>
                        <p class="new_company"><input type="text" name="new_company" value="" placeholder="Company Name" class="form-control" /></p>
                        <p><label for="include_in_com_dir_<?php echo $company_id; ?>">Include in Company Directory</label> <input type="checkbox" name="include_in_com_dir" id="include_in_com_dir_<?php echo $company_id; ?>" data-id="<?php echo $company_id; ?>" value="Yes" disabled="disabled" <?php if (get_post_meta($company_id, '_include_in_company_directory', true) == 'Yes') echo 'checked="checked"'; ?> /><img class="edit_input" src="<?php echo YL_URL; ?>images/edit.png" alt="Edit" data-edit="credit_card_line_fee"></p>
                        <p><strong>Address:</strong> <input type="text" name="company_address" value="<?php echo get_post_meta($lease_id, '_yl_l_street_address', true); ?>" readonly="readonly" class="form-control" /><img class="edit_input" src="<?php echo YL_URL; ?>images/edit.png" alt="Edit" data-edit="credit_card_line_fee"></p>
                        <p><strong>Phone:</strong> <input type="text" name="company_phone" value="<?php echo get_post_meta($lease_id, '_yl_l_phone', true); ?>" readonly="readonly" class="form-control" /><img class="edit_input" src="<?php echo YL_URL; ?>images/edit.png" alt="Edit" data-edit="credit_card_line_fee"></p>
                        <!--<p><strong>Email:</strong> <?php //echo get_post_meta( $lease_id, '_yl_l_email', true );      ?><img class="edit_input" src="<?php //echo YL_URL;      ?>/images/edit.png" alt="Edit" data-edit="credit_card_line_fee"></p>-->
                        <p><strong>Email:</strong> <!--<select name="client_email" disabled="disabled" class="form-control">
                            <?php
                            /* $blogusers = get_users();
                              foreach ( $blogusers as $user ) {
                              if($user->user_email == get_post_meta( $lease_id, '_yl_l_email', true )) $select = 'selected="selected"';
                              else $select = '';
                              echo '<option value="'.$user->user_email.'" '.$select.'>'.$user->user_email.'</option>';
                              } */
                            ?>
                            </select>--><input type="email" name="client_email" value="<?php echo get_post_meta($lease_id, '_yl_l_email', true); ?>" readonly="readonly" class="form-control" /><img class="edit_input" src="<?php echo YL_URL; ?>images/edit.png" alt="Edit" data-edit="credit_card_line_fee">
                        </p>

                        <hr />

                        <input type="hidden" name="company_id" value="<?php echo $company_id; ?>" />
                        <input type="hidden" name="checkbox_value" id="checkbox_value_<?php echo $company_id; ?>" value="No" disabled="disabled" />
                        <input type="hidden" name="lease_id" value="<?php echo $lease_id; ?>" />
                        <input type="submit" name="company_info_submit" value="Save Changes" class="btn btn-primary" style="display:none;" />
                    </form>
                    <?php
                }
            }else {
                echo '<h2>No Company Information Found!</h2>';
            }
            ?>
        <?php } ?>

        <?php if ($current_tab == 'lease'){ ?>

            <h2 class="invoices_hide"><?php echo __('Rentals', 'yl'); ?></h2>
            <?php
            if (isset($_POST['company_directory_submit'])) {
                $directory_lease_id = $_POST['directory_lease_id'];
                 $directory_name = esc_html($_POST['directory_name']);
                //$compid = get_post_meta($directory_lease_id,'_yl_company_id',true);
                //$post_data = array('ID'=> $compid,'post_title' => $directory_name);
               // wp_update_post($post_data);

                update_post_meta($directory_lease_id, '_yl_company_directory_name', $directory_name);
                echo '<p style="color:green;">Directory name updated.</p>';
            }
            ?>
            <table class="table table-striped">
                <thead>
                <th>Suite #</th>
				<?php
				if (get_user_meta(get_current_user_id(), '_user_parent', true)) {
					$user_permissions = explode(",", get_user_meta(get_current_user_id(), '_user_permissions', true));
					if (in_array("Directory Access", $user_permissions)) {
						?>
						<th style="width:400px;">Directory Name</th>
						<?php
					}
				}else{
				?>
				<th style="width:400px;">Directory Name</th>
				<?php } ?>
                <th>Vacate</th>
                <!--<th>Addendum</th>-->
                <!--<th>Upgrade</th>-->
                <th align="right" style="text-align:right;">Lease PDF</th>
            </thead>
            <tbody>
                <?php
                // $meta=get_post_meta(11639);
                echo "<pre>";
                // echo $current_user_id;
                //  var_dump($meta);
                echo "</pre>";
               // $current_user_id = 1374;
                //die();
                //$my_company_id = get_yl_my_company_info($current_user_id);
                //print_r($my_company_id);
                // Get all the leases for this user.

                $args = array(
                    'post_type' => 'lease',
                    'post_status' => 'all',
                    'numberposts' => -1,
                    'posts_per_page' => -1,
                    'meta_query' => array(
                        array(
                            'key' => '_yl_lease_user',
                            'value' => $current_user_id,
                            'compare' => '=',
                        ),
                    ),
                );
                $leases = get_posts($args);

                foreach ($leases as $lease) {
                    $suite_id = get_post_meta($lease->ID, '_yl_product_id', true);
                    ?>

                    <tr>
                        <td>
                            <?php
                            $prefix = 'Suite';
                            $lease_title = esc_html($lease->post_title);

                            if (substr($lease_title, 0, strlen($prefix)) == $prefix) {
                                $lease_title = substr($lease_title, strlen($prefix));
                            }
                            $lease_title = preg_replace('/\bLease$/', '', $lease_title);
                            /* if (get_post_meta($lease->ID, '_yl_lease_pdf', true)) {
                              echo '<a href="' . get_post_meta($lease->ID, '_yl_lease_pdf', true) . '" target="_blank">' . esc_html($lease->post_title) . '</a>';
                              } else { */
                            echo trim($lease_title);
                            /* } */
                            ?>
                        </td>

						<?php
						if (get_user_meta(get_current_user_id(), '_user_parent', true)) {
							$user_permissions = explode(",", get_user_meta(get_current_user_id(), '_user_permissions', true));
							if (in_array("Directory Access", $user_permissions)) {
								?>
								<td>
									<form action="" method="post" class="company_info_form">
										<?php
										$company_directory_name = get_post_meta($lease->ID, '_yl_company_directory_name', true);
										if (!$company_directory_name) {
											$company_directory_name = esc_html(get_the_title(get_post_meta($lease->ID, '_yl_company_name', true)));
										}
										?>
										<p><input type="text" style="width:350px;" name="directory_name" id="directory_name_<?php echo $lease->ID; ?>" value="<?php echo esc_attr($company_directory_name); ?>" readonly="readonly" />
                    <?php if(current_user_can( 'building_manager' )){ ?>
                    <img class="edit_input" src="<?php echo YL_URL; ?>images/edit.png" alt="Edit" data-edit="credit_card_line_fee"></p>
										<input type="hidden" name="directory_lease_id" value="<?php echo $lease->ID; ?>" />
										<input type="submit" name="company_directory_submit" value="Save" class="btn btn-primary btn-xs" style="display:none; margin-bottom: 0; margin-top: 5px;" />
                    <?php } ?>
                  </form>
								</td>
								<?php
							}
						}else{
						?>
						<td>
                            <form action="" method="post" class="company_info_form">
                                <?php
                                $company_directory_name = get_post_meta($lease->ID, '_yl_company_directory_name', true);
                                if (!$company_directory_name) {
                                    $company_directory_name = esc_html(get_the_title(get_post_meta($lease->ID, '_yl_company_name', true)));
                                }
                                ?>
                                <p><input type="text" style="width:350px;" name="directory_name" id="directory_name_<?php echo $lease->ID; ?>" value="<?php echo esc_attr($company_directory_name); ?>" readonly="readonly" />
                                <?php if(current_user_can( 'building_manager' )){ ?>
                                <img class="edit_input" src="<?php echo YL_URL; ?>images/edit.png" alt="Edit" data-edit="credit_card_line_fee"></p>
                                <input type="hidden" name="directory_lease_id" value="<?php echo $lease->ID; ?>" />
                                <input type="submit" name="company_directory_submit" value="Save" class="btn btn-primary btn-xs" style="display:none; margin-bottom: 0; margin-top: 5px;" />
                                <?php } ?>
                            </form>
                        </td>
						<?php } ?>
                        <td>
                            <?php
                            //$avail_date = get_post_meta(yl_get_suite_id_by_lease_id($lease->ID), '_yl_date_vacate_notice_given', true);
							$avail_date = get_post_meta($lease->ID, '_yl_date_vacate_notice_given', true);
							$is_early_vacate = get_post_meta($lease->ID, '_yl_early_vacate_addendum', true);
							if($avail_date)
							{
								$is_early_vacate = get_post_meta($lease->ID, '_yl_early_vacate_addendum', true);
								if($is_early_vacate=='yes')
								{
									$vacate_datetime = get_post_meta($lease->ID, '_yl_ninty_day_vacate_date', true);
									$avail_date = $vacate_datetime;
								}else{
									$vacate_datetime = get_post_meta($lease->ID, '_yl_ninty_day_vacate_date', true);
									$avail_date = $vacate_datetime;
									$vacate_aval_datetime = get_post_meta(yl_get_suite_id_by_lease_id($lease->ID), '_yl_available_date', true);
								}
							}
                            if ($avail_date) {
                                ?>
                                <span class="available-at-date">MUST VACATE ON <strong><?php echo $vacate_datetime; ?></strong> <?php if(isset($vacate_aval_datetime) && $vacate_aval_datetime!=$vacate_datetime){ ?>And avaliable by <strong><?php echo $vacate_aval_datetime; echo "</strong>"; } ?></span>
                                <a onclick="return confirm('Are you sure?')" href="/my-account/?tab=lease&clear=available&lid=<?php echo $lease->ID; ?>" class="btn btn-danger btn-xs">Cancel Vacate</a>
                                <?php
                            } elseif (!get_post_meta($lease->ID, '_yl_lease_valid', true)) {
                                ?><a href="<?php echo get_permalink(get_option('yl_vacate_notice_page')); ?>?lid=<?php echo $lease->ID; ?>" target="_blank" class="btn btn-danger btn-xs">Give Notice to Move Out</a>
                            <?php } ?>
                            <!--</td>
                            <td>-->
                            <?php
                            if ((get_post_meta($lease->ID, '_yl_vn_client_signature', true) == '') && (get_post_meta($lease->ID, '_yl_vn_bm_signature', true) != '')) {
                                echo 'Vacate Notice: Waiting for client signature!';
                                if (current_user_can('lease_client'))
                                    echo ' <a class="btn btn-danger btn-xs" href="' . get_permalink(get_option('yl_vacate_notice_page')) . '?lid=' . $lease->ID . '" target="_blank">' . __('Sign Now', 'yl') . '</a><br />';
                            }elseif ((get_post_meta($lease->ID, '_yl_vn_bm_signature', true) == '') && (get_post_meta($lease->ID, '_yl_vn_client_signature', true) != '')) {
                                echo 'Vacate Notice: Waiting for Building Manager signature!';
                                if (current_user_can('building_manager'))
                                    echo ' <a class="btn btn-danger btn-xs" href="' . get_permalink(get_option('yl_vacate_notice_page')) . '?lid=' . $lease->ID . '" target="_blank">' . __('Sign Now', 'yl') . '</a><br />';
                            }elseif (get_post_meta($lease->ID, '_yl_vacate_notice_pdf', true)) {
                                echo '<a href="' . get_post_meta($lease->ID, '_yl_vacate_notice_pdf', true) . '" target="_blank">' . __('Vacate Notice', 'yl') . '</a><br />';
                            }

                            if ((get_post_meta($lease->ID, '_yl_va_client_signature', true) == '') && (get_post_meta($lease->ID, '_yl_va_bm_signature', true) != '')) {
                                echo 'Early Vacate: Waiting for client signature!';
                                if (current_user_can('lease_client'))
                                    echo ' <a class="btn btn-danger btn-xs" href="' . get_permalink(get_option('yl_vacate_notice_page')) . '?lid=' . $lease->ID . '&step1=yes&suite_id=' . $suite_id . '" target="_blank">' . __('Sign Now', 'yl') . '</a><br />';
                            }elseif ((get_post_meta($lease->ID, '_yl_va_bm_signature', true) == '') && (get_post_meta($lease->ID, '_yl_va_client_signature', true) != '')) {
                                echo 'Early Vacate: Waiting for Building Manager signature!';
                                if (current_user_can('building_manager'))
                                    echo ' <a class="btn btn-danger btn-xs" href="' . get_permalink(get_option('yl_vacate_notice_page')) . '?lid=' . $lease->ID . '&step1=yes&suite_id=' . $suite_id . '" target="_blank">' . __('Sign Now', 'yl') . '</a><br />';
                            }elseif (get_post_meta($lease->ID, '_yl_early_vacate_addendum_pdf', true) || $is_early_vacate=='yes') {
                                echo ' <a href="' . get_post_meta($lease->ID, '_yl_early_vacate_addendum_pdf', true) . '" target="_blank">' . __('Early Vacate Addendum', 'yl') . '</a>';
                            } elseif (get_post_meta($lease->ID, '_yl_date_vacate_notice_given', true) && $is_early_vacate!='yes') {
                                echo '<a class="btn btn-danger btn-xs" href="' . get_permalink(get_option('yl_vacate_notice_page')) . '?lid=' . $lease->ID . '&step1=yes&suite_id=' . $suite_id . '" target="_blank">' . __('Sign Early Vacate Addendum', 'yl') . '</a>';
                            }
                            ?>
                        </td>
                        <!--<td class="cls-it-upgrade">
                            <?php
                            $lease_id = $lease->ID;
                            $upgrade = get_post_meta($lease_id, 'yl_lease_upgrade_products', true);
                            if ($upgrade) {
                                ?>
                                <a href="<?= get_post_type_archive_link(CW_PRODUCTS_POST_TYPE); ?>?lease_id=<?= $lease->ID ?>&req=remove_upgrade" type="button" class="btn btn-danger btn-xs">Remove Upgrades</a>
                            <?php }
                            ?>
                            <a href="<?= get_post_type_archive_link(CW_PRODUCTS_POST_TYPE); ?>?lease_id=<?= $lease->ID ?>" type="button" class="btn btn-info btn-xs">Upgrades</a>
                        </td>-->
                        <td align="right">
                            <?php
                            if (get_post_meta($lease->ID, '_yl_full_lease_pdf', true)) {
                                echo '<a title="Download Lease PDF" href="' . get_post_meta($lease->ID, '_yl_full_lease_pdf', true) . '" target="_blank"> <i class="fa fa-download" aria-hidden="true"></i></a>';
                            } else {
                                echo '<a title="Download Lease PDF" href="/my-account/?tab=lease&download_lease=' . $lease->ID . '"> <i class="fa fa-download" aria-hidden="true"></i></a>';
                            }
                            ?>
                        </td>
                        <?php
                        //print_r($lease);
                        //$leasemeta = get_post_meta($lease->ID);
                        //print_r($leasemeta);
                        ?>
                    </tr>

                    <?php
                }
                ?>
            </tbody>
            </table>

        <?php } ?>
        <?php if ($current_tab == 'lnvoices') { ?>
            <h2 class="invoices_hide"><?php echo __('Invoices', 'yl'); ?></h2>

            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Suite #SDS</th>
                        <th>Invoice Name</th>
                        <th>Client</th>
                        <th>Status</th>
                        <th>Outstanding Balance</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $current_user_id = get_current_user_id();
                    if (isset($_GET['request_profile'])) {
                        $current_user_id = $_GET['request_profile'];
                    }
                    $client_id = yl_get_client_id_by_user_id($current_user_id);

					if (isset($_GET['request_client'])) {
                        $client_id = $_GET['request_client'];
                    }

                    $args = array(
                        'post_type' => 'sa_invoice',
                        'numberposts' => -1,
                        'post_status' => 'any',
                        'meta_query' => array(
                            array(
                                'key' => '_client_id',
                                'value' => $client_id,
                                'compare' => '='
                            )
                        )

                    );
                    $tmp_posts = get_posts($args);
                    $posts = array();
                    foreach ($tmp_posts as $curr_post) {
                        $posts[$curr_post->ID] = $curr_post;
                    }

                    foreach ($posts as $post) {
                        $client_id = get_post_meta($post->ID, '_client_id', true);
                        $total = get_post_meta($post->ID, '_total', true);
                        $invoice_obj = SI_Invoice::get_instance($post->ID);
                        $post_meta = get_post_meta($post->ID);
                        $client_obj = get_post($client_id);
                        ?>
                        <tr>
                            <th><?php echo $post->ID; ?></th>
                            <!--
                            <td><?php echo $post->post_date; ?></td>
                            -->
                            <td>
                                <?php
                                if ($post_meta['_yl_suite_id'][0]) {
                                    $suite_obj_suite_number = get_post_meta($post_meta['_yl_suite_id'][0], '_yl_room_number', true);
                                    echo $suite_obj_suite_number;
                                } elseif (($post_meta['_yl_lease_id'][0]) && (get_post_meta($post_meta['_yl_lease_id'][0], '_yl_suite_number', true) != '-1')) {
                                    $lease_obj_suite_number = get_post_meta($post_meta['_yl_lease_id'][0], '_yl_suite_number', true);
                                    echo $lease_obj_suite_number;
                                } else {
                                    if ($post_meta['_yl_lease_user'][0]) {
                                        $user_obj = get_user_by('id', $post_meta['_yl_lease_user'][0]);
                                        $args = array(
                                            'post_type' => 'lease',
                                            'numberposts' => 1,
                                            'post_status' => array('publish'),
                                            'meta_query' => array(
                                                array(
                                                    'key' => '_yl_lease_user',
                                                    'value' => $post_meta['_yl_lease_user'][0],
                                                    'compare' => '='
                                                )
                                            )
                                        );
                                        $leases = get_posts($args);
                                        $first_lease = $leases[0];
                                        $first_lease_suite = get_post_meta($first_lease->ID, '_yl_suite_number', true);

                                        if ($first_lease_suite == '-1') {
                                            echo 'Y-membership';
                                        } else {
                                            echo $first_lease_suite;
                                        }
                                    }
                                }
                                ?>

                            </td>
                            <td><?php echo esc_html($post->post_title); ?></td>
                            <td><?php echo esc_html($client_obj->post_title); ?></td>
                            <td class="<?php echo $post->post_status; ?>"><?php echo $invoice_obj->get_status_label($invoice_obj->get_status()); ?></td>
                            <td><strong>
                                    <?php
                                    if ((float) round($invoice_obj->get_balance(), 2) < (float) round($invoice_obj->get_calculated_total(), 2)) {
                                        ?>
                                        $<?php echo number_format($invoice_obj->get_balance(), 2, '.', ''); ?> of $<?php echo number_format($invoice_obj->get_calculated_total(), 2, '.', ''); ?>
                                        <?php
                                    } else {
                                        ?>
                                        $<?php echo number_format($invoice_obj->get_balance(), 2, '.', ''); ?>
                                        <?php
                                    }
                                    ?></strong>
                            </td>
                            <td class="text-right bm-buttons-block">
                                <a alt="View" title="View" href="<?php echo get_permalink($post->ID); ?>" class="btn btn-xs btn-success"><i class="fa fa-search-plus" aria-hidden="true"></i></a>
                            </td>
                        </tr>
                        <?php
                    }
                    ?>
                </tbody>
            </table>

        <?php } ?>
        <?php if ($current_tab == 'payments') { ?>
            <h2 class="invoices_hide"><?php echo __('Payments', 'yl'); ?></h2>
            <style>
              .display_payment_row.credit{ display: none; }
            </style>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Check #</th>
                        <th>Status</th>
                        <th>Payment</th>
                        <th>Total</th>
                        <th>Source</th>
                        <th>Suite #</th>
                        <th></th>
                    </tr>
                </thead>
                    <?php
                    $current_user_id = get_current_user_id();
                    if (isset($_GET['request_profile'])) {
                        $current_user_id = $_GET['request_profile'];
                    }
                    $client_id = yl_get_client_id_by_user_id($current_user_id);
                    $client_obj = SI_Client::get_instance($client_id);
                    $payments = $client_obj->get_payments();

                    $statuses = array(
                        'authorized' => __( 'Authorized', 'sprout-invoices' ),
                        'cancelled' => __( 'Cancelled', 'sprout-invoices' ),
                        'payment-partial' => __( 'Partial Payment', 'sprout-invoices' ),
                        'void' => __( 'Void', 'sprout-invoices' ),
                        'publish' => __('Complete', 'sprout-invoices' ),
                        'refunded' => __( 'Refunded', 'sprout-invoices' ),
                        'recurring' => __( 'Recurring', 'sprout-invoices' ),
                    );

                    foreach ($payments as $payment) {
                        $payment_obj = SI_Payment::get_instance($payment);
                        $payment_data = $payment_obj->get_data();
                        $status = $payment_obj->get_status();
                        $method = $payment_obj->get_payment_method();
                        $amount = $payment_obj->get_amount();
						            $amount = number_format($amount, 2, '.', '');
                        $invoice = $payment_obj->get_invoice_id();
                        $source = $payment_obj->get_source();
                        $trans_id = $payment_obj->get_transaction_id();
                        $lease_id = get_post_meta($invoice, '_yl_lease_id', true);
                        $suite_id = get_post_meta($lease_id, '_yl_product_id', true);
                        $method_class = $method;
                        $method_class = sanitize_title($method_class);
                        if(isset($payment_data['check_number'])){
                          $check_number = $payment_data['check_number'];
                        }else{
                          $check_number = 'N/A';
                        }
                        $payment_date = get_the_date( 'Y-m-d', $payment );
                        ?>
                        <tr class="display_payment_row <?php echo $method_class; ?>">
                            <td><?php echo $payment_date; ?></td>
                            <td><?php echo $check_number; ?></td>
                            <td><?php echo $statuses[$status]; ?></td>
                            <td><a href="<?php echo get_permalink($invoice); ?>" target="_blank">Invoice #<?php echo $invoice;?></a></td>
                            <td>$<strong><?php echo $amount; ?></strong></td>
                            <td><?php echo $method; ?></td>
                            <td><?php echo (($suite_id) ? get_the_title($suite_id) : ''); ?></td>
                            <td><?php echo ''; ?></td>
                        </tr>
                        <?php
                    }
                    ?>
                <tbody>
                </tbody>
            </table>

        <?php } ?>

        <?php if ($current_tab == 'billing_information'){ ?>
            <?php echo do_shortcode('[sprout_invoices_payments_dashboard]'); ?>
        <?php } ?>
		<?php if ($current_tab == 'calendar' ){ ?>
            <?php echo do_shortcode('[sim-ycal]'); ?>
        <?php } ?>
        <?php if ($current_tab == 'users') : ?>
            <?php if (!isset($_GET['action'])) { ?>
                <h3 class="my_profile">My Profile</h3>
                <?php
				$profile_user_id = $current_user_id;
                $curr_user_info = get_userdata($current_user_id);
				if ($_POST['check']==1) {

                    $str = 'true';


                    $first_name = esc_html($_POST['user_first_name']);
					$last_name = esc_html($_POST['user_last_name']);
					$display_name = $first_name.' '.$last_name;
                    $user_email = esc_html($_POST['user_email_address']);

                     if($first_name =='')
                     {
                         $str = 'false';
                         $fname = "Please fill first name";
                     }
                    if($last_name =='')
                     {
                         $str = 'false';
                         $lname = "Please fill last name";
                     }
                    if($user_email =='')
                     {
                         $str = 'false';
                         $email = "Please fill email";
                     }
                    if($str == 'true'){
                        $new_user_id = wp_update_user(array('ID' => (int)$profile_user_id, 'display_name' => $display_name, 'first_name' => $first_name, 'last_name' => $last_name, 'user_email' => $user_email));
                        $user_password = esc_html($_POST['user_new_password']);
                        if (isset($user_password)) {
                            $new_user_id = wp_update_user(array('ID' => $profile_user_id, 'user_pass' => $user_password));
                        }
                        $curr_user_info = get_userdata($current_user_id);
                        echo "<p style='color:green'>User details updated successfully.</p>";
                    }
				}

                //Add new user to associates
//				$associates_new_user_id = 1289;
                //Remove user from associates in client profile
//				$removed_user_id = 622;
//				delete_post_meta($client_cpt_id, '_associated_users', $removed_user_id);
                ?>
                <form action="" method="post" class="company_info_form2" id="saveform" style="padding-bottom:30px;">

					<div class="col-md-6">
						<div class="form-group">
							<label for="user_first_name">First Name</label><br />
                        	<input type="text" name="user_first_name" id="user_first_name" value="<?php isset($_POST['user_first_name']) ?  $result = $_POST['user_first_name'] : $result = esc_attr($curr_user_info->first_name) ;echo $result; ?>" class="form-control" />

						</div>
                        <p style="color:red"><?php echo $fname; ?></p>
					</div>

					<div class="col-md-6">
						<div class="form-group">
							<label for="user_last_name">Last Name</label><br />
                        	<input type="text" name="user_last_name" id="user_last_name" value="<?php isset($_POST['user_last_name']) ?  $result = $_POST['user_last_name'] : $result = esc_attr($curr_user_info->last_name);echo $result;  ?>" class="form-control" />

						</div>
                         <p style="color:red"><?php echo $lname ;?></p>
					</div>

					<div class="col-md-6">
						<div class="form-group">
							<label for="user_email_address">Email Address</label><br />
                        	<input type="email" name="user_email_address" id="user_email" value="<?php isset($_POST['user_email_address']) ?  $result = $_POST['user_email_address'] : $result = esc_attr($curr_user_info->user_email);echo $result;?>" class="form-control" />
                             <input type="hidden" class="form-control"  name="check" value="1" >

						</div>
                         <p style="color:red"><?php echo $email; ?></p>
                        <p id="email_error" style="color:red" ></p>
					</div>
                    <div> </div>
					<div class="col-md-6">
						<div class="form-group">
							 <label for="user_new_password">New Password</label><br />
                        	<input type="password" name="user_new_password" id="user_new_password" class="form-control" value="" />
						</div>
					</div>

					<div class="col-md-12">
						<div class="form-group text-right">
							<button type="button" name="user_save_submit" id="user_save_submit" onclick="check_update_user_email(<?php echo $profile_user_id; ?>,'save')" class="btn btn-primary">Save</button>
						</div>
					</div>
                </form>

			<?php
				if (isset($_GET['action']) && $_GET['action'] == 'delete_user') {

					require_once(ABSPATH.'wp-admin/includes/user.php' );
					$user_id = $_GET['uid'];
					wp_delete_user( $user_id );

					$request_profile_id = $_GET['request_profile'];
					$redirect_url = '/my-account/?tab=users&request_profile='.$request_profile_id.'&delete_done=success';
					wp_redirect( $redirect_url );
					exit;

				}

				if (isset($_GET['delete_done']) && $_GET['delete_done'] == 'success') {
					echo "User Removed!";
				}
			?>


              <div style="float: left;"> <h3>Users</h3></div>
                <?php
                $args = array(
                    'meta_query' => array(
                        array(
                            'key' => '_user_parent',
                            'value' => $current_user_id,
                            'compare' => '='
                        )
                    )
                );

                // The Query
                $user_query = new WP_User_Query($args);
                ?>
                <table class="table table-striped user_sc">
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>&nbsp;</th>
                    </tr>
                <?php
                $user_info = get_userdata($current_user_id);
                $user_permissions = explode(",", get_user_meta($user_id, '_user_permissions', true));
                echo '<tr>
				<td>' . esc_html($user_info->display_name) . '</td>
				<td>' . esc_html($user_info->user_email) . '</td>
				<td><a title="Edit ' . esc_html($user_info->display_name) . '" href="/my-account/?tab=users&request_profile=' . $current_user_id . '&action=edit_user&uid=' . $user_info->ID . '"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a></td>
			</tr>';

                // User Loop
                if (!empty($user_query->results)) {
                    foreach ($user_query->results as $user) {
                        echo '<tr>
						<td>' . esc_html($user->display_name) . '</td>
						<td>' . esc_html($user->user_email) . '</td>
						<td><a title="Edit ' . esc_attr($user->display_name) . '" href="/my-account/?tab=users&request_profile=' . $current_user_id . '&action=edit_user&uid=' . $user->ID . '"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>
						&nbsp;&nbsp;&nbsp;<a onclick="return confirm(\'Are you sure you want to remove this user?\');" title="Delete ' . esc_attr($user->display_name) . '" href="/my-account/?tab=users&request_profile=' . $current_user_id . '&action=delete_user&uid=' . $user->ID . '"><i class="fa fa-trash" aria-hidden="true"></i></a></td>
					</tr>';
                    }
                }
                ?>
                </table>

                    <?php if (!get_user_meta($current_user_id, '_user_parent', true)) { ?>
                    <p><a href="/my-account/?tab=users&request_profile=<?php echo $current_user_id; ?>&action=add_user" class="btn btn-primary">Add User</a></p>
                    <?php } ?>

                <?php } ?>
                <?php if (isset($_GET['action']) && $_GET['action'] == 'add_user') { ?>
                <?php

                if ($_POST['check'] == 1) {

                   // echo "<pre>";print_r($_POST);die;
                    $first_name = esc_html($_POST['first_name']);
					$last_name = esc_html($_POST['last_name']);
					$display_name = $first_name.' '.$last_name;
                    $user_email = esc_html($_POST['user_email']);
                    $user_phone = esc_html($_POST['user_phone']);
                    $permissions = $_POST['permissions'];

                    $parent_id = $current_user_id;

					$new_user_password = wp_generate_password( 8, false );

                    $userdata = array(
                        'user_login' => $user_email,
						'first_name' => $first_name,
						'last_name' => $last_name,
                        'display_name' => $display_name,
                        'user_email' => $user_email,
                        'role' => 'lease_client',
                        'user_pass' => $new_user_password  // When creating an user, `user_pass` is expected.
                    );

                    $user_id = wp_insert_user($userdata);
                    $associates_user = get_post_meta($client_cpt_id, '_associated_users');
                    if (!is_wp_error($user_id)) {
                        update_user_meta($user_id, '_user_permissions', join(",", $permissions));
                        update_user_meta($user_id, '_user_parent', $parent_id);
                        update_user_meta($user_id, '_user_phone', $user_phone);

                        //Add this new user to invoice client cpt
                        if (in_array("Receive / Pay Invoices", $permissions)) {
                            if (!in_array($user_id, $associates_user)) {
                                add_post_meta($client_cpt_id, '_associated_users', $user_id);
                            }
                        } else {
                            delete_post_meta($client_cpt_id, '_associated_users', $user_id);
                        }
						yl_add_member_wp_new_user_notification( $user_id, $new_user_password, $user_email );

                        echo '<p style="color:green;">User added successfully.</p>';
                    }
                }
                ?>
                <form action="" method="post" id="adduser">
                    <div class="form-group">
                        <label for="first_name">First Name</label>
                        <input type="text" class="form-control" id="first_name" name="first_name">
                    </div>
					<div class="form-group">
                        <label for="last_name">Last Name</label>
                        <input type="text" class="form-control" id="last_name" name="last_name">
                    </div>
                    <div class="form-group">
                        <label for="user_email">Email</label>
                        <input type="email" class="form-control" id="user_email" name="user_email">
                        <input type="hidden" class="form-control"  name="check" value="1" >
                    </div>
                    <div><p id="email_error" style="color:red" ></p></div>
                    <div class="form-group">
                        <label for="user_phone">Phone</label>
                        <input type="text" class="form-control" id="user_phone" name="user_phone">
                    </div>
                    <div class="form-group">
                        <label>Permissions</label>
						<br />
                        <input type="checkbox" name="select_all" id="select_all" value="" /> <label for="select_all">Select All</label>
                        <br />
                        <input type="checkbox" class="per_checkbox" name="permissions[]" value="Directory Access" /> Directory Access
                        <br />
                        <input type="checkbox" class="per_checkbox" name="permissions[]" value="Billing Access" /> Billing Access
                        <br />
                        <!-- <input type="checkbox" class="per_checkbox" name="permissions[]" value="Billing Notifications" /> Billing Notifications
                        <br /> -->
                        <input type="checkbox" class="per_checkbox" name="permissions[]" value="View Past Invoices" /> View Past Invoices
                        <br />
                        <input type="checkbox" class="per_checkbox" name="permissions[]" value="Receive / Pay Invoices" /> Receive / Pay Invoices
                        <br />
                        <!-- <input type="checkbox" class="per_checkbox" name="permissions[]" value="Submit Work Order" /> Submit Work Order (Coming)
                        <br />
                        <input type="checkbox" class="per_checkbox" name="permissions[]" value="Schedule Conference Room Usage" /> Schedule Conference Room Usage (Coming)
                        <br /> -->
                        <input type="checkbox" class="per_checkbox" name="permissions[]" value="View Signed Lease" /> View Signed Lease
                    </div>

                    <button type="button" name="user_submit" id="user_submit" onclick="check_user_email()" class="btn btn-primary">Submit</button>
                </form>
            <?php } ?>

            <?php if (isset($_GET['action']) && $_GET['action'] == 'edit_user') { ?>
                <?php
                $user_id = $_GET['uid'];
                if ($_POST['check'] == 1) {
                   // echo "<pre>";print_r($_POST);die;
                    $str = 'true';

                    $first_name = esc_html($_POST['first_name']);
					$last_name = esc_html($_POST['last_name']);
					$display_name = $first_name.' '.$last_name;
                    $user_email = esc_html($_POST['user_email']);
                    $user_phone = esc_html($_POST['user_phone']);

                    if($first_name =='')
                     {
                         $str = 'false';
                         $fname = "Please fill first name";
                     }
                    if($last_name =='')
                     {
                         $str = 'false';
                         $lname = "Please fill last name";
                     }
                    if($user_email =='')
                     {
                         $str = 'false';
                         $email = "Please fill email";
                     }
                    if($str == 'true'){

                            if (isset($_POST['permissions'])) {
                                $permissions = $_POST['permissions'];
                            }

                            $new_user_id = wp_update_user(array('ID' => $user_id, 'display_name' => $display_name, 'first_name' => $first_name, 'last_name' => $last_name, 'user_email' => $user_email));
                            $user_password = $_POST['user_password'];
                            if (isset($user_password)) {
                                $new_user_id = wp_update_user(array('ID' => $user_id, 'user_pass' => $user_password));
                            }
                            $associates_user = get_post_meta($client_cpt_id, '_associated_users');
                            if (isset($permissions)) {
                                update_user_meta($new_user_id, '_user_permissions', join(",", $permissions));

                                //Add this new user to invoice client cpt
                                if (in_array("Receive / Pay Invoices", $permissions)) {
                                    if (!in_array($new_user_id, $associates_user)) {
                                        add_post_meta($client_cpt_id, '_associated_users', $new_user_id);
                                    }
                                } else {
                                    delete_post_meta($client_cpt_id, '_associated_users', $new_user_id);
                                }
                            }
                            update_user_meta($new_user_id, '_user_phone', $user_phone);
                            echo '<p style="color:green;">User details updated successfully.</p>';
                     }
                }

                $user_info = get_userdata($user_id);
                $user_permissions = explode(",", get_user_meta($user_id, '_user_permissions', true));
                ?>
                <form action="" method="post" id="edituser">
                    <div class="form-group">
                        <label for="first_name">First Name</label>
                        <input type="text" class="form-control" id="first_name" name="first_name" value="<?php isset($_POST['first_name']) ?  $result = $_POST['first_name'] : $result = esc_attr($user_info->first_name); echo $result; ?>">
                    </div>
                    <p style="color:red"><?php echo $fname; ?></p>
					<div class="form-group">
                        <label for="last_name">Last Name</label>
                        <input type="text" class="form-control" id="last_name" name="last_name" value="<?php isset($_POST['last_name']) ?  $result = $_POST['last_name'] : $result = esc_attr($user_info->last_name); echo $result; ?>">
                    </div>
                    <p style="color:red"><?php echo $lname; ?></p>
                    <div class="form-group">
                        <label for="user_email">Email</label>
                        <input type="email" class="form-control" id="user_email" name="user_email" value="<?php isset($_POST['user_email']) ?  $result = $_POST['user_email'] : $result = esc_attr($user_info->user_email); echo $result; ?>">
                        <input type="hidden" class="form-control"  name="check" value="1" >

                    </div>
                    <p style="color:red"><?php echo $email; ?></p>
                    <div><p id="email_error" style="color:red" ></p></div>
                    <div class="form-group">
                        <label for="user_phone">Phone</label>
                        <input type="text" class="form-control" id="user_phone" name="user_phone" value="<?php echo esc_attr(get_user_meta($user_id, '_user_phone', true)); ?>">
                    </div>
					<div class="form-group">
                        <label for="user_password">Password</label>
                        <input type="password" class="form-control" id="user_password" name="user_password" value="">
                    </div>
                <?php if ($user_id != $current_user_id): ?>
                        <div class="form-group">
                            <label>Permissions</label>
							<br />
							<input type="checkbox" name="select_all" id="select_all" value="" /> <label for="select_all">Select All</label>
                            <br />
                            <input type="checkbox" class="per_checkbox" name="permissions[]" value="Directory Access" <?php if (in_array("Directory Access", $user_permissions)) echo 'checked="checked"'; ?> /> Directory Access
                            <br />
                            <input type="checkbox" class="per_checkbox" name="permissions[]" value="Billing Access" <?php if (in_array("Billing Access", $user_permissions)) echo 'checked="checked"'; ?> /> Billing Access
                            <br />
                            <!-- <input type="checkbox" class="per_checkbox" name="permissions[]" value="Billing Notifications" <?php if (in_array("Billing Notifications", $user_permissions)) echo 'checked="checked"'; ?> /> Billing Notifications
                            <br /> -->
                            <input type="checkbox" class="per_checkbox" name="permissions[]" value="View Past Invoices" <?php if (in_array("View Past Invoices", $user_permissions)) echo 'checked="checked"'; ?> /> View Past Invoices
                            <br />
                            <input type="checkbox" class="per_checkbox" name="permissions[]" value="Receive / Pay Invoices" <?php if (in_array("Receive / Pay Invoices", $user_permissions)) echo 'checked="checked"'; ?> /> Receive / Pay Invoices
                            <br />
                            <!-- <input type="checkbox" class="per_checkbox" name="permissions[]" value="Submit Work Order" <?php if (in_array("Submit Work Order", $user_permissions)) echo 'checked="checked"'; ?> /> Submit Work Order (Coming)
                            <br />
                            <input type="checkbox" class="per_checkbox" name="permissions[]" value="Schedule Conference Room Usage" <?php if (in_array("Schedule Conference Room Usage", $user_permissions)) echo 'checked="checked"'; ?> /> Schedule Conference Room Usage (Coming)
                            <br /> -->
                            <input type="checkbox" class="per_checkbox" name="permissions[]" value="View Signed Lease" <?php if (in_array("View Signed Lease", $user_permissions)) echo 'checked="checked"'; ?> /> View Signed Lease
                        </div>
                <?php endif; ?>
                    <button type="button" name="user_update_submit" id="user_update_submit" onclick="check_update_user_email(<?php echo $user_id; ?>)"  class="btn btn-primary">Save Changes</button>
                </form>
            <?php } ?>

			<script type="text/javascript">
			jQuery(document).ready(function () {
				//select all checkboxes
				jQuery("#select_all").change(function(){  //"select all" change
					var status = this.checked; // "select all" checked status
					jQuery('.per_checkbox').each(function(){ //iterate all listed checkbox items
						this.checked = status; //change ".checkbox" checked status
					});
				});

				jQuery('.per_checkbox').change(function(){ //".checkbox" change
					//uncheck "select all", if one of the listed checkbox item is unchecked
					if(this.checked == false){ //if this item is unchecked
						jQuery("#select_all")[0].checked = false; //change "select all" checked status to false
					}

					//check "select all" if all checkbox items are checked
					if (jQuery('.per_checkbox:checked').length == $('.per_checkbox').length ){
						jQuery("#select_all")[0].checked = true; //change "select all" checked status to true
					}
				});
			});
			</script>

        <?php endif; ?>

        <?php
    else:
        echo '<p>Please login to view this page</p>';
    endif;
    return ob_get_clean();
}

add_shortcode('yl_my_account', 'yl_my_account_function');

// Redefine user notification function
function yl_add_member_wp_new_user_notification( $user_id, $plaintext_pass, $user_email ) {

	$user_info = get_userdata($user_id);
	$user_name = $user_info->user_login;
	$user_display_name = $user_info->display_name;
	$new_user_email = $user_info->user_email;

	$email_subject = get_option('newuser_email_subject');
	$email_message = get_option('newuser_email_message');
	$search = array();
	$replace = array();

	$search[] = '%%DISPLAYNAME%%';
	$replace[] = $user_display_name;

	$search[] = '%%USERNAME%%';
	$replace[] = $user_name;

	$search[] = '%%USEREMAIL%%';
	$replace[] = $user_email;

	$search[] = '%%PASSWORD%%';
	$replace[] = $plaintext_pass;

	$search[] = '%%LOGINURL%%';
	$replace[] = wp_login_url();

	$get_message = str_replace($search, $replace, $email_message);
	$get_message = 	stripslashes($get_message);
	$get_message = nl2br($get_message);

	$headers  = 'MIME-Version: 1.0' . "\r\n";
	$headers .= 'Content-type: text/plain; charset=iso-8859-1' . "\r\n";
	add_filter('wp_mail_content_type',create_function('', 'return "text/html"; '));
	@wp_mail($user_email, $email_subject, $get_message, $headers);

}

/*if ( ! empty( $_GET['action'] ) && ($_GET['action'] == 'delete_user')) {
	add_action( 'init', 'remove_yl_associates_user' );
}


function remove_yl_associates_user() {
	// Verify that the user intended to take this action.
	if ( ! wp_verify_nonce( 'delete_account' ) ) {
		return;
	}

	require_once(ABSPATH.'wp-admin/includes/user.php' );
	$user_id = $_GET['uid'];
	wp_delete_user( $user_id );

	$request_profile_id = $_GET['request_profile'];
	$redirect_url = '/my-account/?tab=users&request_profile='.$request_profile_id;
	wp_redirect( $redirect_url );
	exit;
}*/


add_action( 'admin_menu', 'yl_newuser_email_setting_page_menu' );
function yl_newuser_email_setting_page_menu() {
	add_options_page( __( 'Add User Email', 'YL' ), __( 'Add User Email', 'YL' ), 'administrator', 'yl-newuser-email-page-settings', 'yl_newuser_email_page_settings' );

}

function yl_newuser_email_page_settings(){

	if(isset($_POST['newuser_email_submit'])){

		update_option( 'newuser_email_subject', $_POST['newuser_email_subject'] );
		update_option( 'newuser_email_message', $_POST['newuser_email_message'] );

		?>

		<div class="updated"><p><?php echo __('Successfully Updated', 'YL'); ?></p></div>

    <?php }	?>

    <div class="wrap">
        	<h2><?php echo __('Add User Email Settings', 'YL'); ?></h2>
			<form name="newuser_email" method="post">
			<table class="form-table">
                	<tr valign="top">
                		<th scope="row"><?php echo __('Subject', 'YL'); ?></th>
                		<td>
                        <label for="newuser_email_subject"><?php echo __('Subject', 'YL'); ?></label><br/>
						<input type="text" name="newuser_email_subject" id="newuser_email_subject" value="<?php echo stripslashes(get_option('newuser_email_subject')); ?>" />
                        </td>
                	</tr>
                	<tr valign="top">
                		<th scope="row"><?php echo __('Message', 'YL'); ?></th>
                		<td>
                        <label for="blogname"><?php echo __('Message', 'YL'); ?></label><br/>
						<textarea name="newuser_email_message" id="newuser_email_message" rows="15" cols="150"><?php echo stripslashes(get_option('newuser_email_message')); ?></textarea><br /><code>%%DISPLAYNAME%%, %%USERNAME%%, %%USEREMAIL%%, %%PASSWORD%%, %%LOGINURL%%</code>
                        </td>
                	</tr>

                    <tr valign="top">
                		<th scope="row"><label for="blogname"></label></th>
                		<td><input type="submit" class="button button-primary button-large" value="Save" id="newuser_email_submit" name="newuser_email_submit"></td>
                	</tr>


            </table>
	       </form>
        </div>
    <?php

} ?>
