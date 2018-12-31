<?php

function yl_available_suites_shortcodes($atts, $content = null) {
    extract(shortcode_atts(array(
        'search' => 'yes',
                    ), $atts));

    global $us_states_full;

    if (!current_user_can('building_manager')) {
        echo "Only Building Manager are able to create lease. Please login to search available suites.";
        wp_login_form();
        return;
    }

    ob_start();

    if (!$_POST) {
        ?>
        <div class="lease_steps">

            <!-- - - - - - -->
            <!-- TIMELINE  -->
            <!-- - - - - - -->

            <div class="yl_timeline_container">
                <div class="yl_timeline_line">
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

                            <div class="yl_unit_block yl_one_third step_client_info">
                                <div class="yl_circle">
                                </div>
                                <div class="yl_desc">
                                    Client<br>Info
                                </div>
                            </div>

                            <div class="yl_unit_block yl_one_third">
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

                            <div class="yl_unit_block yl_one_third">
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

            <!-- - - - - -->
            <!-- STEP 1  -->
            <!-- - - - - -->

            <div id="lease_step_1">
                <form action="" method="get" id="search_suite_form">
                    <p>
                        <label for="MoveinDate">Move in Date</label> 
                        <input type="text" name="MoveinDate" id="MoveinDate" style="max-width: 125px;" value="<?php echo (($_GET['MoveinDate']) ? $_GET['MoveinDate'] : date('Y-m-d', time())); ?>" />
                    </p>
                    <!--
                    <p id="suiteType"><label for="type">Type</label>
                    <?php
                    $select_args = array(
                        'name' => 'suitestype_field',
                        'taxonomy' => 'suitestype',
                        'show_count' => 0,
                        'hierarchical' => 1,
                        'hide_if_empty' => false,
                        'hide_empty' => 0
                    );
                    wp_dropdown_categories($select_args);
                    ?>
                    </p>
                    -->
                    <p class="lease_step_1_button"><button id="searchSuites" name="searchSuites">Go</button></p>
                    <?php
                    if (isset($_GET['searchSuites'])) {
                        yl_show_available_suites();
                    }
                    ?>
                </form>
            </div>

            <!-- - - - - -->
            <!-- STEP 2  -->
            <!-- - - - - -->
            <div id="lease_step_2" style="display:none;">
                <form action="" method="post" id="save_client_lease_company_form">
                    <div class="lease_info_left">
                        <p>
                            <label for="guarantor"><?php echo __('Is the Lessee the same as the Guarantor?', 'YL'); ?></label>
                            <select name="is_lessee_guarantor" id="is_lessee_guarantor">
                                <option value="Yes"><?php echo __('Yes', 'YL'); ?></option>
                                <option value="No"><?php echo __('No', 'YL'); ?></option>
                            </select>
                        </p>
                    </div>
                    <div class="lease_info_right">

                        <h2 class="lessee_info"><?php echo __('Lessee/Guarantor Info', 'YL'); ?></h2>
                        <h2 class="lessee_info_2"><?php echo __('Lessee Info', 'YL'); ?></h2>
                        <p>
                            <select name="lessee_user" id="lessee_user">
                                <option value="">New user</option>
                                <?php
                                $args = array(
                                    'blog_id' => $GLOBALS['blog_id'],
                                    'orderby' => 'login',
                                    'role' => 'lease_client',
                                    'orderby' => 'meta_value',
                                    'meta_key' => 'first_name',
                                    'order' => 'ASC',
                                    'count_total' => false,
                                    'fields' => 'all'
                                );
                                $users = get_users($args);

                                foreach ($users as $user) {
                                    $user_meta = get_user_meta($user->ID);
                                    ?>
                                    <option value="<?php echo $user->ID; ?>"><?php echo $user_meta['first_name'][0] . ' ' . $user_meta['last_name'][0] . ' (' . $user->data->user_nicename . ')'; ?></option>
                                    <?php
                                }
                                ?>
                            </select>
                        </p>

                        <div class="new_lessee_block">
                            <p>
                                <input type="text" name="lessee_first_name" id="lessee_first_name" placeholder="First Name (*)" size="20" />
                                <input type="text" name="lessee_middle_name" id="lessee_middle_name" placeholder="Middle Name" size="20" />
                                <input type="text" name="lessee_last_name" id="lessee_last_name" placeholder="Last Name (*)" size="20" />
                            </p>
                            <p><input type="text" name="lessee_phone" id="lessee_phone" placeholder="Phone (*)" /></p>
                            <p><input type="text" name="lessee_email" id="lessee_email" placeholder="Email (*)" /></p>
                            <p>
                                <input type="text" name="lessee_street_address" id="lessee_street_address" placeholder="Street Address (*)" />
                                <input type="text" name="lessee_address_2" id="lessee_address_2" placeholder="Address Line 2" />
                                <input type="text" name="lessee_city" id="lessee_city" placeholder="City (*)" />
                                <label for="lessee_address">State (*)</label>
                                <select name="lessee_state" id="lessee_state">
                                    <?php 
                                    foreach ($us_states_full as $us_state) {
                                        echo '<option value="' . $us_state . '">' . $us_state . '</option>';
                                    }
                                    ?>
                                </select>
                                <input type="text" name="lessee_zip_code" id="lessee_zip_code" placeholder="ZIP Code (*)" />
                            </p>
                            <p class="required-small">(*) required field</p>
                        </div>

                        <div id="guarantor_info" style="display:none;">
                            <h2 class="guarator_info">Guarantor Info</h2>

                            <div class="new_guarator_block">
                                <p>
                                    <input type="text" name="guarantor_first_name" id="guarantor_first_name" placeholder="First Name (*)" size="20" />
                                    <input type="text" name="guarantor_middle_name" id="guarantor_middle_name" placeholder="Middle Name" size="20" />
                                    <input type="text" name="guarantor_last_name" id="guarantor_last_name" placeholder="Last Name (*)" size="20" />
                                </p>
                                <p><input type="text" name="guarantor_phone" id="guarantor_phone" placeholder="Phone (*)" /></p>
                                <p><input type="text" name="guarantor_email" id="guarantor_email" placeholder="Email (*)" /></p>
                                <p>
                                    <input type="text" name="guarantor_street_address" id="guarantor_street_address" placeholder="Street Address (*)" />
                                    <input type="text" name="guarantor_address_2" id="guarantor_address_2" placeholder="Address Line 2" />
                                    <input type="text" name="guarantor_city" id="guarantor_city" placeholder="City (*)" />
                                    <label for="guarantor_address">State (*)</label>
                                    <select name="guarantor_state" id="guarantor_state">
                                        <?php
                                        foreach ($us_states_full as $us_state) {
                                            echo '<option value="' . $us_state . '">' . $us_state . '</option>';
                                        }
                                        ?>
                                    </select>
                                    <input type="text" name="guarantor_zip_code" id="guarantor_zip_code" placeholder="ZIP Code (*)" />
                                </p>
                            </div>
                        </div>

                        <h2 class="company_info">Company Info</h2>
                        <p>
                            <select name="company_id" id="company_id">
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
                                    <option value="<?php echo $compny->ID; ?>"><?php echo esc_html($compny->post_title); ?></option>
                                    <?php
                                }
                                ?>
                            </select>
                        </p>

                        <div class="new_company_block">
                            <p>
                                <input type="text" name="company_name" id="company_name" placeholder="Company Name" />
                            </p>
                            <p id="companyType">
                                <label for="company_type">Type</label>
                                <?php wp_dropdown_categories('name=company_type&taxonomy=companytype&show_count=0&hierarchical=1&hide_empty=0'); ?>
                            </p>
                            <p>
                                <input type="hidden" id="bm_id" name="bm_id" value="<?php echo get_current_user_id(); ?>" />
                                <input type="hidden" id="suite_id" name="suite_id" value="" />
                                <input type="hidden" id="first_month" name="first_month" value="" />
                                <input type="hidden" id="rent_rate" name="rent_rate" value="" />
                                <input type="hidden" id="deposit" name="deposit" value="" />
                                <input type="hidden" id="move_in_date" name="move_in_date" value="" />
                            </p>
                        </div>

                        <input type="submit" name="submitLease" id="submitLease" value="Next" />
                        <input type="submit" name="holdLease" id="holdLease" value="Hold" />
                    </div>
                </form>
            </div>

        </div>
        <?php
    }

    if ($_POST) {
        if ($_POST['holdLease']) {
            // Hold suite
            // Set the suite as 'on hold'
            // and create the client user if necessary

            $product_id = $_POST['suite_id'];
            $bm_id = $_POST['bm_id'];
            $user_id = $_POST['lessee_user'];
            $company_id = $_POST['company_id'];


            if (!$_POST['company_id']) {
                // Creating new company
                $company_name = $_POST['company_name'];
                $company_type = $_POST['company_type'];
                $company_args = array(
                    'post_type' => 'company',
                    'post_title' => $company_name,
                    'post_status' => 'publish',
                    'post_content' => ''
                );

                $company_id = yl_create_company($company_args, $company_type);
            }

            if (!$_POST['lessee_user']) {
                // Creating a new user
                $client_arg = array();
                $client_arg['user_email'] = esc_html($_POST['lessee_email']);
                $client_arg['user_login'] = esc_html($_POST['lessee_email']);
                $client_arg['first_name'] = esc_html($_POST['lessee_first_name']);
                $client_arg['middle_name'] = esc_html($_POST['lessee_middle_name']);
                $client_arg['last_name'] = esc_html($_POST['lessee_last_name']);
                $client_arg['phone'] = esc_html($_POST['lessee_phone']);
                $client_arg['address_1'] = esc_html($_POST['lessee_street_address']);
                $client_arg['address_2'] = esc_html($_POST['lessee_address_2']);
                $client_arg['city'] = esc_html($_POST['lessee_city']);
                $client_arg['zip'] = esc_html($_POST['lessee_zip_code']);
                $client_arg['state'] = esc_html($_POST['lessee_state']);
                $client_arg['company'] = $company_id;

                $user_id = yl_register_client_user($client_arg, false);
            } else {
                $user_id = $_POST['lessee_user'];
                $user_obj = get_user_by('id', $user_id);
                $user_meta = get_user_meta($user_id);
                $user_data = get_userdata($user_id);

                $client_arg = array();
                $client_arg['user_email'] = $user_obj->user_email;
                $client_arg['user_login'] = $user_obj->user_email;
                $client_arg['first_name'] = $user_meta['first_name'][0];
                $client_arg['middle_name'] = $user_meta['_yl_l_middle_name'][0];
                $client_arg['last_name'] = $user_meta['last_name'][0];
                $client_arg['phone'] = $user_meta['_yl_l_phone'][0];
                $client_arg['address_1'] = $user_meta['_yl_l_street_address'][0];
                $client_arg['address_2'] = $user_meta['_yl_l_address_line_2'][0];
                $client_arg['city'] = $user_meta['_yl_l_city'][0];
                $client_arg['zip'] = $user_meta['_yl_l_zip_code'][0];
             
                $client_arg['state'] = $user_meta['_yl_l_state'][0];
                $client_arg['company'] = $company_id;
            }

            // Create (if needed) a client element and associate it with a user
            $company_obj = get_post($client_arg['company']);
            $client_id = yl_create_user_associated_client($user_id, $client_arg['first_name'], $client_arg['last_name'], $client_arg['phone'], $company_obj);

            // Generate an invoice and send the email so the client can pay this invoice.
            // Once invoice for $100 is paid, suite will be set as 'on hold' for
            // 48 hours
            $invoice_id = yl_create_hold_invoice($product_id, $user_id, $company_id, $bm_id, $client_id);

            // Send the invoice via email to the client
            send_hold_suite_email_to_client($user_id, $client_id, $invoice_id);
            ?>
            <p>An invoice has been generated and sent to the client. Once paid, suite will be set on hold for 48hrs.</p>
            <?php
            //break;
            exit();
        } else {
            // Generate lease
            // the regular way
            if ($_POST['lessee_user']) {
                $product_id = $_POST['suite_id'];
                $client_id = $_POST['lessee_user'];

                $args_tmp = array(
                    'post_type' => 'lease',
                    'post_status' => 'publish',
                    'posts_per_page' => -1,
                    'meta_query' => array(
                        array(
                            'key' => '_yl_product_id',
                            'value' => $product_id,
                            'compare' => '='
                        ),
                        array(
                            'key' => '_yl_lease_user',
                            'value' => $client_id,
                            'compare' => '='
                        )
                    )
                );

                $tmp_leases = get_posts($args_tmp);
                if (count($tmp_leases) > 0) {
                    ?>
                    <p>This client already has an active Y-Membership lease.</p>
                    <p><a href="javascript:window.location.href = '<?php echo $_SERVER['REQUEST_URI']; ?>';">Reload</a></p>
                    <?php
                    //continue;
                    exit();
                }
            }

            $lease_id = yl_insert_lease_company();
            ?>

            <p class="generating_lease">
                Generating lease... please wait a moment
            </p>

            <script>
                jQuery(document).ready(function () {
                    setTimeout(function () {
                        window.location = "<?php echo get_permalink(get_option('yl_lease_summary_page')) . "?lid=" . $lease_id; ?>";
                    }, 1000);
                });
            </script>
            <?php
        }
    }

    $yl_search_form = ob_get_contents();
    ob_end_clean();
    return $yl_search_form;
}

add_shortcode('available-suites', 'yl_available_suites_shortcodes');

function yl_hold_check_for_expiration_dates() {
    $args = array(
        'post_type' => 'suites',
        'meta_query' => array(
            'relation' => 'AND',
            array(
                'key' => '_yl_available',
                'value' => 'Yes'
            ),
            array(
                'key' => '_yl_hold',
                'value' => true
            ),
            array(
                'key' => '_yl_hold_expiration',
                'value' => time(),
                'compare' => '<'
            )
        )
    );
    $posts = get_posts($args);

    // The following suites have expired
    //print_r($posts);

    foreach ($posts as $post) {
        $user_id = get_post_meta($post->ID, '_yl_hold_client');
        $bm_id = get_post_meta($post->ID, '_yl_hold_bm');

        // Erase all the hold meta data
        delete_post_meta($post->ID, '_yl_hold');
        delete_post_meta($post->ID, '_yl_hold_start');
        delete_post_meta($post->ID, '_yl_hold_expiration');
        delete_post_meta($post->ID, '_yl_hold_client');
        delete_post_meta($post->ID, '_yl_hold_bm');
        delete_post_meta($post->ID, '_yl_hold_company');

        // Send notifications to both client and building manager
    }
}

add_action('init', 'yl_hold_check_for_expiration_dates');

/**
 * yl_create_hold_invoice
 *
 * Params
 * - product_id
 * - user_id
 * - company_id
 * - bm_id
 * - client_id
 *
 * Returns
 * - invoice_id
 *
 * @since: 04/27/2016
 */
function yl_create_hold_invoice($product_id, $user_id, $company_id, $bm_id, $client_id) {
    $suite_obj = get_post($product_id);

    // Let's create an invoice now
    $invoice_args = array(
        'subject' => 'Invoice for ' . $suite_obj->post_title . ' 48hr HOLD',
        'client_id' => $client_id,
        'status' => 'publish',
        'currency' => '',
        'total' => (float) 100.0,
        'issue_date' => time(),
        'due_date' => 0,
        'expiration_date' => 0,
        'fields' => array(),
    );

    $invoice_args['line_items'][] = array(
        "desc" => "Hold Service",
        "qty" => 1,
        "rate" => (float) 100.0,
        "total" => (float) 100.0,
        "type" => "service",
		"accounting_cat" => yl_account_category_id_by_wordmatch('Rent')
    );

    $invoice_id = SI_Invoice::create_invoice($invoice_args);

    update_post_meta($invoice_id, '_yl_hold', true);
    update_post_meta($invoice_id, '_yl_hold_client', $user_id);
    update_post_meta($invoice_id, '_yl_hold_bm', $bm_id);
    update_post_meta($invoice_id, '_yl_hold_company', $company_id);
    update_post_meta($invoice_id, '_yl_hold_suite', $product_id);

    return $invoice_id;
}

function send_hold_suite_email_to_client($user_id, $client_id, $invoice_id) {
    $user = get_user_by('id', $user_id);
    $user_email = $user->user_email;
    $suite = get_post(get_post_meta($invoice_id, '_yl_hold_suite', true));

    $email_subject = get_option('clients_hold_email_subject');
    $email_message = get_option('clients_hold_email_message');
    $search = array();
    $replace = array();

    $search[] = '%%client-name%%';
    $replace[] = $user->first_name;

    $search[] = '%%suite-name%%';
    $replace[] = $suite->post_title;

    $search[] = '%%invoice-url%%';
    $replace[] = '<a href="' . get_permalink(get_option('yl_lease_checkout_page')) . '?iid=' . $invoice_id . '">Go to checkout page</a>';

    $message = str_replace($search, $replace, $email_message);
    $message = stripslashes($message);
    $message = nl2br($message);

    $headers = 'MIME-Version: 1.0' . "\r\n";
    $headers = array('Content-Type: text/html; charset=UTF-8');
    if (wp_mail($user_email, $email_subject, $message, $headers)) {
        //update_post_meta($product_id, '_yl_available', 'No');
    }
}

function order_by_meta_key($orderby) {
    global $wpdb;
    // order by 'pro' values first, then random
    $orderbydate = "$wpdb->postmeta.meta_value = '" . $orderby . "' DESC";
    return $orderbydate;
}

function yl_show_available_suites() {
    ob_start();

    //$suites_type = $_GET['suitestype_field']; //Get the $_POST[] call
    $move_in_date = $_GET['MoveinDate']; //Get the $_POST[] call
    //$term = get_term( $suites_type, 'suitestype' );
    $termName = $term->name;

    $move_in_date_arr = explode("-", $move_in_date);
    $month = $move_in_date_arr[1];
    if (substr($month, 0, 1) == 0) {
        $month = substr($month, 1, 1);
    }
    $day = $move_in_date_arr[2];
    $year = $move_in_date_arr[0];

    $days_month_of_moving_date = date("t");
    if ($month && $year) {
        $days_month_of_moving_date = cal_days_in_month(CAL_GREGORIAN, $month, $year);
    }

    $timestamp = strtotime("$year-$month-$day");
    $daysRemaining = (int) date('t', $timestamp) - (int) date('j', $timestamp);
    $daysRemaining = ($daysRemaining + 1);
    //$outputHTML .= 'Days Remaining: ' . ($firstDayNextMonth - mktime()) / (24 * 3600);



    $date_parts = explode('-', $move_in_date);
    $date_parts_day = $date_parts[2];
    $date_parts_month = $date_parts[1];

    //$move_in_date_next_month = (explode('-', $move_in_date)[1]) + 1;
	$moveindate_next_month = date('Y-m-d', strtotime('+1 month', strtotime($move_in_date)));
	$move_in_date_next_month = (explode('-', $moveindate_next_month)[1]);
    $days_in_move_in_date_next_month = cal_days_in_month(CAL_GREGORIAN, $move_in_date_next_month, date("Y", strtotime($moveindate_next_month)));
    $first_day_next_month_to_lease_start = strtotime('first day of ' . date("Y-m", strtotime("+" . $days_in_move_in_date_next_month . " days", strtotime($move_in_date))));
    $days_this_month = date('t', time());
    $month_this_month = date('m', time());
    $month_next_month = date('m', $first_day_next_month_to_lease_start);


    $args = array(
        'post_type' => 'suites',
        'orderby' => 'post_title',
        'order' => 'ASC',
        'numberposts' => -1,
        'posts_per_page' => -1,
        'meta_query' => array(
            'relation' => 'OR',
            array(
                'key' => '_yl_available',
                'value' => 'Yes',
                'compare' => '='
            ),
            array(
                'relation' => 'AND',
                array(
                    'key' => '_yl_date_vacate_notice_given',
                    'compare' => 'EXISTS',
                ),
                array(
                    'key' => '_yl_date_vacate_notice_given',
                    'value' => '',
                    'compare' => '!=',
                )
            )
        ),
    );
    $query = new WP_Query($args);
    ?>
    <div id="product_list_wrap" class="search_container">

    <?php
    foreach ($query->posts as $post) {

        $meta = get_post_meta($post->ID);
        $perDayRent = ($meta['_yl_rent_rate'][0] / $days_month_of_moving_date);

        $move_in_posible = 'move-in-possible';
        if ($_GET['MoveinDate'] < $meta['_yl_available_date'][0]) {
            $move_in_posible = 'move-in-not-possible';
        }

        if ($day == "01" || $day == "1") {
            $first_month_rate = $meta['_yl_rent_rate'][0];
        } else {
            //$first_month_rate = round((($daysRemaining * $perDayRent) + get_post_meta($post->ID, '_yl_rent_rate', true)), 2);
            $first_month_rate = round((($daysRemaining * $perDayRent)), 2);
        }


        $is_hold = get_post_meta($post->ID, '_yl_hold', true);
        $is_hold_exp = get_post_meta($post->ID, '_yl_hold_expiration', true);

        if ($is_hold) {
            $hold_user = get_user_by('id', get_post_meta($post->ID, '_yl_hold_client', true));
            $hold_company = get_post(get_post_meta($post->ID, '_yl_hold_company', true));
            $extra_attrs = array(
                'data-hold-client-email="' . $hold_user->user_email . '"',
                'data-hold-company-name="' . $hold_company->post_title . '"',
                'data-hold-client-id="' . get_post_meta($post->ID, '_yl_hold_client', true) . '"',
                'data-hold-company-id="' . get_post_meta($post->ID, '_yl_hold_company', true) . '"',
                'data-hold-bm-id="' . get_post_meta($post->ID, '_yl_hold_bm', true) . '"'
            );
        }

        if (
                ($date_parts_day >= 20) &&
                (
                ($month_this_month == $month_next_month) ||
                (($month_this_month + 1) == $month_next_month)
                )
        ) {
            $first_month_rate_by_moveindate = ($meta['_yl_rent_rate'][0] + $first_month_rate);
        } else {
            $first_month_rate_by_moveindate = ($first_month_rate);
        }

        $is_storage = '';
        $post_terms = wp_get_post_terms($post->ID, 'suitestype');
        if ($post_terms[0]->slug == 'storage') {
            $is_storage = 'is_storage';
        }

        $vacate_available_date = '';

        if (get_post_meta($post->ID, '_yl_date_vacate_notice_given', true) != '') {
            $move_in_posible = 'upcoming_available';
            $vacate_available_date = get_post_meta($post->ID, '_yl_date_vacate_notice_given', true);
        }

        $early_vacate_addendum = '';
        if (get_post_meta($post->ID, '_yl_early_vacate_addendum', true) != '') {
            $early_vacate_addendum = get_post_meta($post->ID, '_yl_early_vacate_addendum', true);
        }
		
		if($vacate_available_date)
		{
			$is_early_vacate = get_post_meta($post->ID, '_yl_early_vacate_addendum', true);
			if($is_early_vacate=='yes')
			{
				$vacate_available_date = get_post_meta($post->ID, '_yl_available_date', true);
			}else{
				$vacate_available_date = get_post_meta($post->ID, '_yl_available_date', true);
				//$vacate_available_date = get_post_meta($post->ID, '_yl_date_vacate_notice_given', true);
				////$vacate_available_date = date('Y-m-d', strtotime($vacate_available_date . ' -1 days'));
			}
		}
        ?>
            <!-- /classes/
            immediate
            upcoming_available
            future_available
            -->
            <?php
            $lease_start_date = (($vacate_available_date) ? $vacate_available_date : $_GET['MoveinDate']);
            if ($early_vacate_addendum == 'yes') {
                $lease_start_date = $_GET['MoveinDate'];
            }
            ?>
            <div class="sigle_product_container <?php echo $is_storage; ?> <?php echo (($is_hold) ? 'is_hold' : ''); ?> <?php echo $move_in_posible; ?>" <?php echo (($is_hold) ? implode(' ', $extra_attrs) : ''); ?> data-move-in-date="<?php echo $lease_start_date; ?>" data-first-month="<?php echo $first_month_rate_by_moveindate; ?>" data-deposit="<?php echo $meta['_yl_rent_rate'][0]; ?>" data-rate="<?php echo $meta['_yl_rent_rate'][0]; ?>" data-suite="<?php echo esc_attr($post->ID); ?>">
                <h2><?php echo esc_html($post->post_title); ?></h2>

                <div class="membership_details">

                    <p><?php echo __('Available Date: ', 'YL'); ?> 
                        <span class="available_date"><?php echo (($vacate_available_date) ? $vacate_available_date : $meta['_yl_available_date'][0]); ?></span>
            <?php
            if (($vacate_available_date) && (!empty($vacate_available_date))) {
                if ($early_vacate_addendum == 'yes') {
                    echo '<br /><span class="vacate_notice_given early_vacate">Early Vacate</span>';
                } else {
                    ?>
                                <span class="vacate_notice_given">Vacate notice given</span>
                <?php
            }
        }
        ?>
                    </p>

                        <?php
                        if ($meta['_yl_square_feet'][0] != '') {
                            echo '<p>' . __('Square Feet: ', 'YL') . $meta['_yl_square_feet'][0] . '</p>';
                        }
                        ?>

                    <p><?php echo __('Rate: ', 'YL'); ?> 
                        <span class="rate">$<?php echo $meta['_yl_rent_rate'][0]; ?></span>
                    </p>

                    <p class="lease_deposit"><?php echo __('Deposit: ', 'YL'); ?>
                        <span class="deposit">$<?php echo $meta['_yl_rent_rate'][0]; ?></span>
                    </p>

                    <p class="lease_first_month_rent"><?php echo __("Due for First Month's Rent: ", 'YL'); ?> 
                        <span class="due_for_first_month">$ <?php echo $first_month_rate_by_moveindate; ?></span>
                    </p>

                    <p class="choose_suite" data-membership="1">
                        <a href="#" data-suite="<?php echo $post->ID; ?>">
        <?php
        echo __('Choose this', 'YL');

        if ($is_hold) {
            ?>
                                <span>On hold until <strong><?php echo date('m/j/Y', $is_hold_exp); ?></strong></span>
            <?php
        }
        ?>
                        </a>
                    </p>

                </div>

            </div>
                            <?php
                        }
                        ?>

        <!-- Y Membership -->
                        <?php
                        $ym_monthly_rate = get_option('yl_y_membership_monthly_rate');
                        $ym_deposit = get_option('yl_y_membership_deposit');
                        $perDayRent = ($ym_monthly_rate / $days_month_of_moving_date);

                        if ($day == "01" || $day == "1") {
                            $first_month_rate = $ym_monthly_rate;
                        } else {
                            //$first_month_rate = round((($daysRemaining * $perDayRent) + get_post_meta($post->ID, '_yl_rent_rate', true)), 2);
                            $first_month_rate = round((($daysRemaining * $perDayRent)), 2);
                        }
                        ?>
        <div class="sigle_product_container is_ym" data-move-in-date="<?php echo $_GET['MoveinDate']; ?>" data-first-month="<?php echo $first_month_rate; ?>" data-deposit="<?php echo $ym_deposit; ?>" data-rate="<?php echo $ym_monthly_rate; ?>" data-suite="-1">
            <h2>Y-Membership</h2>

            <div class="membership_details">

                <p><?php echo __('Available Date: ', 'YL'); ?> 
                    <span class="available_date"><?php echo date('Y-m-d', time()); ?></span>
                </p>

                <p><?php echo __('Rate: ', 'YL'); ?> 
                    <span class="rate">$<?php echo $ym_monthly_rate; ?></span>
                </p>

                <p class="lease_deposit"><?php echo __('Deposit: ', 'YL'); ?>
                    <span class="deposit">$<?php echo $ym_deposit; ?></span>
                </p>

                <p class="lease_first_month_rent"><?php echo __("Due for First Month's Rent: ", 'YL'); ?> 
                    <span class="due_for_first_month">$ <?php echo $first_month_rate; ?></span>
                </p>

                <p class="choose_suite" data-membership="1">
                    <a href="#" data-suite="<?php echo $post->ID; ?>"><?php echo __('Choose this', 'YL'); ?></a>
                </p>

            </div>

        </div>

    </div>

    <?php
    echo ob_get_clean();
}

/* * *****************************
 * send_email for search result
 * ***************************** */
add_action('wp_ajax_show-available-suites', 'yl_show_available_suites');
add_action('wp_ajax_nopriv_show-available-suites', 'yl_show_available_suites');

function yl_get_remaining_days() {
    $move_in_date = $_POST['move_in_date'];
    $post_id = $_POST['post_id'];
    $move_in_date_arr = explode("-", $move_in_date);
    $month = $move_in_date_arr[1];
    if (substr($month, 0, 1) == 0) {
        $month = substr($month, 1, 1);
    }
    $day = $move_in_date_arr[2];
    $year = $move_in_date_arr[0];

    $days_month_of_moving_date = date("t");
    if ($month && $year) {
        $days_month_of_moving_date = cal_days_in_month(CAL_GREGORIAN, $month, $year);
    }

    $timestamp = strtotime("$year-$month-$day");

    $daysRemaining = (int) date('t', $timestamp) - (int) date('j', $timestamp);
    $daysRemaining = ($daysRemaining + 1);

    $perDayRent = (get_post_meta($post_id, '_yl_rent_rate', true) / $days_month_of_moving_date);

    $outputHTML = '<p>' . __('Due for First Month\'s Rent: ', 'YL') . '$';
    if ($day == "01" || $day == "1") {
        $first_month_rate = get_post_meta($post_id, '_yl_rent_rate', true);
    } else {
        //$first_month_rate = round((($daysRemaining * $perDayRent) + get_post_meta($post_id, '_yl_rent_rate', true)), 2);
        $first_month_rate = round((($daysRemaining * $perDayRent)), 2);
    }
    $outputHTML .= '<span class="due_for_first_month">' . $first_month_rate . '</span>';
    $outputHTML .= '</p>';

    echo json_encode(array("days_remain" => $daysRemaining, "first_month_rent" => $outputHTML));
    exit;
}

add_action('wp_ajax_get-remaining-days', 'yl_get_remaining_days');
add_action('wp_ajax_nopriv_get-remaining-days', 'yl_get_remaining_days');

function yl_insert_lease_company() {

    $product_id = $_POST['suite_id'];
    $user_id = $_POST['bm_id'];
    $rent_rate = $_POST['rent_rate'];
    $deposit = $_POST['deposit'];
    $move_in_date = $_POST['move_in_date'];
   // echo "<pre>";print_r($_POST);
    if ($product_id == -1) {
        $ym_monthly_rate = get_option('yl_y_membership_monthly_rate');
        $ym_deposit = get_option('yl_y_membership_deposit');

        $rent_rate = $ym_monthly_rate;
        $deposit = $ym_deposit;
    }

    $move_in_date_arr = explode("-", $move_in_date);
    $day = $move_in_date_arr[2];
    /*
      if($day == "01" || $day == "1") {
      $due_for_first_month = 0;
      } else {
      $due_for_first_month = $_POST['first_month'];
      }
     */
    $due_for_first_month = $_POST['first_month'];

    $is_lessee_guarantor = $_POST['is_lessee_guarantor'];

 if (!empty($_POST['lessee_user'])) {
       
        $lessee_user_id = $_POST['lessee_user'];
        $existing_user = get_userdata($_POST['lessee_user']);
        $existing_user_meta = get_user_meta($_POST['lessee_user']);

        $lessee_email = $existing_user_meta['nickname'][0];
        $lessee_first_name = $existing_user_meta['_yl_l_first_name'][0];
        $lessee_middle_name = $existing_user_meta['_yl_l_middle_name'][0];
        $lessee_last_name = $existing_user_meta['_yl_l_last_name'][0];
        $lessee_phone = $existing_user_meta['_yl_l_phone'][0];
        $lessee_street_address = $existing_user_meta['_yl_l_street_address'][0];
        $lessee_address_2 = $existing_user_meta['_yl_l_address_line_2'][0];
        $lessee_city = $existing_user_meta['_yl_l_city'][0];
        $lessee_state = $existing_user_meta['_yl_l_state'][0];
        $lessee_zip_code = $existing_user_meta['_yl_l_zip_code'][0];
    } else {
      
     
    
        $lessee_first_name = esc_html($_POST['lessee_first_name']);
        $lessee_middle_name = esc_html($_POST['lessee_middle_name']);
        $lessee_last_name = esc_html($_POST['lessee_last_name']);
        $lessee_phone = esc_html($_POST['lessee_phone']);
        $lessee_email = esc_html($_POST['lessee_email']);
        $lessee_street_address = esc_html($_POST['lessee_street_address']);
        $lessee_address_2 = esc_html($_POST['lessee_address_2']);
        $lessee_city = esc_html($_POST['lessee_city']);
        $lessee_state = esc_html($_POST['lessee_state']);
        $lessee_zip_code = esc_html($_POST['lessee_zip_code']);
    }
  // echo $lessee_state;die;
    $guarantor_first_name = esc_html($_POST['guarantor_first_name']);
    $guarantor_middle_name = esc_html($_POST['guarantor_middle_name']);
    $guarantor_last_name = esc_html($_POST['guarantor_last_name']);
    $guarantor_phone = esc_html($_POST['guarantor_phone']);
    $guarantor_email = esc_html($_POST['guarantor_email']);
    $guarantor_street_address = esc_html($_POST['guarantor_street_address']);
    $guarantor_address_2 = esc_html($_POST['guarantor_address_2']);
    $guarantor_city = esc_html($_POST['guarantor_city']);
    $guarantor_state = esc_html($_POST['guarantor_state']);
    $guarantor_zip_code = esc_html($_POST['guarantor_zip_code']);
    

    if ($_POST['company_id']) {
        $existing_company = get_post($_POST['company_id']);
        $terms = wp_get_post_terms($_POST['company_id'], 'companytype');

        $company_name = $existing_company->post_title;
        $company_type = $terms[0]->term_id;
        $company_id = $existing_company->ID;
    } else {
        $company_name = esc_html($_POST['company_name']);
        $company_type = esc_html($_POST['company_type']);
    }

    $lease_title = get_the_title($product_id);
    if ($product_id == -1) {
        $lease_title = 'Y-Membership';
    }

    $lease_args = array(
        'post_type' => 'lease',
        'post_title' => $lease_title . ' Lease',
        'post_status' => 'draft',
        'post_content' => '',
        'post_author' => $user_id
    );

    if ($lease_id = wp_insert_post($lease_args)) {
        // Product ID is the id of the suite being rented.
        // If this is a Y-membership, then ID will be -1
        update_post_meta($lease_id, '_yl_product_id', $product_id);
        update_post_meta($lease_id, '_yl_author_id', $user_id);

        update_post_meta($lease_id, '_yl_lease_user', $lessee_user_id);

        $user_data = get_userdata($user_id);
        update_post_meta($lease_id, '_yl_author_email', $user_data->user_email);
        update_post_meta($lease_id, '_yl_author_name', $user_data->first_name);
        update_post_meta($lease_id, '_yl_lessor', get_option('yl_lessor'));
        update_post_meta($lease_id, '_yl_location', get_option('yl_location'));
        update_post_meta($lease_id, '_yl_location_phone_number', get_option('yl_location_phone'));

        if ($product_id == -1) {
            update_post_meta($lease_id, '_yl_suite_number', 'Y-Membership');

            $ym_monthly_rate = get_option('yl_y_membership_monthly_rate');
            $ym_deposit = get_option('yl_y_membership_deposit');

            update_post_meta($lease_id, '_yl_security_deposit', $ym_deposit);
            update_post_meta($lease_id, '_yl_monthly_rent', $ym_monthly_rate);
        } else {
            update_post_meta($lease_id, '_yl_suite_number', get_the_title($product_id));
            update_post_meta($lease_id, '_yl_security_deposit', $deposit);
            update_post_meta($lease_id, '_yl_monthly_rent', $rent_rate);
        }

        $prod_post_terms = wp_get_post_terms($product_id, 'suitestype');
        if (strtolower($prod_post_terms[0]->slug) == 'storage') {
            update_post_meta($lease_id, '_yl_is_storage', '1');
        }

        update_post_meta($lease_id, '_yl_lease_start_date', $move_in_date);
        update_post_meta($lease_id, '_yl_first_month_rent_rate', $due_for_first_month);
        update_post_meta($lease_id, '_yl_actual_prorated_rent', $due_for_first_month);

        update_post_meta($lease_id, '_yl_lessee_guarantor_same', $is_lessee_guarantor);

        update_post_meta($lease_id, '_yl_l_first_name', $lessee_first_name);
        update_post_meta($lease_id, '_yl_l_middle_name', $lessee_middle_name);
        update_post_meta($lease_id, '_yl_l_last_name', $lessee_last_name);
        update_post_meta($lease_id, '_yl_l_phone', $lessee_phone);
        update_post_meta($lease_id, '_yl_l_email', $lessee_email);
        update_post_meta($lease_id, '_yl_l_street_address', $lessee_street_address);
        update_post_meta($lease_id, '_yl_l_address_line_2', $lessee_address_2);
        update_post_meta($lease_id, '_yl_l_city', $lessee_city);
        update_post_meta($lease_id, '_yl_l_state', $lessee_state);
        update_post_meta($lease_id, '_yl_l_zip_code', $lessee_zip_code);

        if ($is_lessee_guarantor == 'No') {
            update_post_meta($lease_id, '_yl_g_first_name', $guarantor_first_name);
            update_post_meta($lease_id, '_yl_g_middle_name', $guarantor_middle_name);
            update_post_meta($lease_id, '_yl_g_last_name', $guarantor_last_name);
            update_post_meta($lease_id, '_yl_g_phone', $guarantor_phone);
            update_post_meta($lease_id, '_yl_g_email', $guarantor_email);
            update_post_meta($lease_id, '_yl_g_street_address', $guarantor_street_address);
            update_post_meta($lease_id, '_yl_g_address_line_2', $guarantor_address_2);
            update_post_meta($lease_id, '_yl_g_city', $guarantor_city);
            update_post_meta($lease_id, '_yl_g_state', $guarantor_state);
            update_post_meta($lease_id, '_yl_g_zip_code', $guarantor_zip_code);
        } else {
            update_post_meta($lease_id, '_yl_g_first_name', $lessee_first_name);
            update_post_meta($lease_id, '_yl_g_middle_name', $lessee_middle_name);
            update_post_meta($lease_id, '_yl_g_last_name', $lessee_last_name);
            update_post_meta($lease_id, '_yl_g_phone', $lessee_phone);
            update_post_meta($lease_id, '_yl_g_email', $lessee_email);
            update_post_meta($lease_id, '_yl_g_street_address', $lessee_street_address);
            update_post_meta($lease_id, '_yl_g_address_line_2', $lessee_address_2);
            update_post_meta($lease_id, '_yl_g_city', $lessee_city);
            update_post_meta($lease_id, '_yl_g_state', $lessee_state);
            update_post_meta($lease_id, '_yl_g_zip_code', $lessee_zip_code);
        }

        update_post_meta($lease_id, '_yl_company_type', $company_type);

        if (!$company_id) {
            $company_args = array(
                'post_type' => 'company',
                'post_title' => $company_name,
                'post_status' => 'publish',
                'post_content' => ''
            );

            $company_id = yl_create_company($company_args, $company_type);
        }
        update_post_meta($lease_id, '_yl_company_id', $company_id);
        update_post_meta($lease_id, '_yl_company_name', $company_id);
		yl_update_calendar_credit_for_single_blog(0,$lease_id);
    }

    //echo json_encode(array("msg" => "Lease Added", "lease_id" => $lease_id));
    //exit;
    return $lease_id;
}

function yl_create_company($company_args, $company_type = '') {
    if ($company_id = wp_insert_post($company_args)) {

        if ($company_type) {
            $c_type = get_term($company_type, 'companytype');
            $company_taxonomy_id = wp_set_object_terms($company_id, $c_type->slug, 'companytype');
        }

        return $company_id;
    }
    return false;
}

//add_action('wp_ajax_insert-lease-company', 'yl_insert_lease_company');
//add_action('wp_ajax_nopriv_insert-lease-company', 'yl_insert_lease_company');