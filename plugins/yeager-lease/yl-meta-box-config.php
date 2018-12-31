<?php

function get_lease_client_bm_arrays_for_cmb_metabox() {
    $result = array();
    $result[] = 'Select User';

    $bm_user_query = new WP_User_Query(array('role' => 'building_manager'));
    // User Loop
    if (!empty($bm_user_query->results) && ( !is_wp_error($bm_user_query->results))) {
        foreach ($bm_user_query->results as $user) {
			if(is_int($user->ID)){
            	$result[$user->ID] = esc_html($user->display_name);
			}	
        }
    }

    $user_query = new WP_User_Query(array('role' => 'lease_client'));
    // User Loop
    if (!empty($user_query->results) && ( !is_wp_error($user_query->results))) {
        foreach ($user_query->results as $user) {
			if(is_int($user->ID)){
            	$result[$user->ID] = esc_html($user->display_name);
			}
        }
    }

    $result['1'] = 'Admin';

    return $result;
}

function get_lease_user_arrays_for_cmb_metabox($role = 'lease_client') {
    $result = array();
    $user_query = new WP_User_Query(array('role' => $role));
    // User Loop
    if (!empty($user_query->results) && ( !is_wp_error($user_query->results))) {
        $result[] = 'Select User';
        foreach ($user_query->results as $user) {
			if(is_int($user->ID)){
            	$result[$user->ID] = esc_html($user->display_name);
			}
        }
    } else {
        $result['1'] = 'Admin';
    }
    return $result;
}

function get_company_names_array() {
    $args = array(
        'post_type' => 'company',
        'posts_per_page' => -1,
        'orderby' => 'title',
        'order' => 'ASC'
    );

    $company_query = new WP_Query($args);
    $companies = array();

    if ($company_query->have_posts()) {
        while ($company_query->have_posts()) {
            $company_query->the_post();
            if (get_the_title())
                $companies[get_the_ID()] = esc_html(get_the_title());
        }
        wp_reset_postdata();
    }

    return $companies;
}

function get_company_types() {
    $company_types = get_terms('companytype', 'hide_empty=0');
    $companies = array();
    if (!empty($company_types) && !is_wp_error($company_types)) {
        foreach ($company_types as $term) {
            $companies[$term->term_id] = esc_html($term->name);
        }
    }

    return $companies;
}

/**
 * Include and setup custom metaboxes and fields.
 *
 * @category YourThemeOrPlugin
 * @package  Metaboxes
 * @license  http://www.opensource.org/licenses/gpl-license.php GPL v2.0 (or later)
 * @link     https://github.com/webdevstudios/Custom-Metaboxes-and-Fields-for-WordPress
 */
add_filter('cmb_meta_boxes', 'config_stm_metaboxes');

/**
 * Define the metabox and field configurations.
 *
 * @param  array $meta_boxes
 * @return array
 */
function config_stm_metaboxes(array $meta_boxes) {
    // Start with an underscore to hide fields from custom fields list
    $prefix = '_yl_';

    global $us_states_full, $current_date, $us_states_codes;
	$post_id = '';
    $current_date = date("Y-m-d");
    if(isset($_GET['post']))
		$post_id = $_GET['post'];	
    $post_title = get_the_title($post_id);
    $auxiliary_suite_number_display = get_post_meta($post_id, '_yl_suite_number', true);
    $suite_type = get_post_meta($post_id, '_yl_product_id', true);


    if ($suite_type == -1) {
        $amc_credits_init = 15;
    } else {
        $amc_credits_init = 20;
    }


    /**
     * Company Metabox
     */
    $meta_boxes['company_specifications_metabox'] = array(
        'id' => 'company_specifications',
        'title' => __('More Information', 'cmb'),
        'pages' => array('company',), // Post type
        'context' => 'normal',
        'priority' => 'high',
        'show_names' => true, // Show field names on the left
        // 'cmb_styles' => true, // Enqueue the CMB stylesheet on the frontend
        'fields' => array(
            array(
                'name' => __('Public', 'cmb'),
                'desc' => __('Information that will allow the public directory to be turned on and off.', 'cmb'),
                'id' => $prefix . 'com_public',
                'type' => 'select',
                'options' => array(
                    'Yes' => __('Yes', 'cmb'),
                    'No' => __('No', 'cmb'),
                ),
                'default' => 'Yes',
            ),
            array(
                'name' => __('Company Phone Number', 'cmb'),
                'desc' => __('', 'cmb'),
                'id' => $prefix . 'company_phone',
                'type' => 'text',
            ),
            array(
                'name' => __('Contact Means for Guests', 'cmb'),
                'desc' => __('', 'cmb'),
                'id' => $prefix . 'company_contact_for_guests',
                'type' => 'text',
            ),
            array(
                'name' => __('Contact Means for Clients', 'cmb'),
                'desc' => __('', 'cmb'),
                'id' => $prefix . 'company_contact_for_clints',
                'type' => 'text',
            ),
            array(
                'name' => __('Lease Holder', 'cmb'),
                'desc' => __('', 'cmb'),
                'id' => $prefix . 'lease_holder',
                'type' => 'text',
            ),
            array(
                'name' => __('Guarantor', 'cmb'),
                'desc' => __('', 'cmb'),
                'id' => $prefix . 'guarantor',
                'type' => 'text',
            ),
            array(
                'name' => __('Employees', 'cmb'),
                'desc' => __('', 'cmb'),
                'id' => $prefix . 'employees',
                'type' => 'text',
            ),
            array(
                'name' => __('Referred', 'cmb'),
                'desc' => __('', 'cmb'),
                'id' => $prefix . 'referred',
                'type' => 'text',
            ),
            array(
                'name' => __('Company Login', 'cmb'),
                'desc' => __('', 'cmb'),
                'id' => $prefix . 'company_login',
                'type' => 'text',
            ),
            array(
                'name' => __('Company Password', 'cmb'),
                'desc' => __('', 'cmb'),
                'id' => $prefix . 'company_password',
                'type' => 'text',
            ),
            array(
                'name' => __('First Month Rent Rate', 'cmb'),
                'desc' => __('', 'cmb'),
                'id' => $prefix . 'first_month_rent_rate',
                'type' => 'text',
            ),
            array(
                'name' => __('Recurring Rent Rate', 'cmb'),
                'desc' => __('', 'cmb'),
                'id' => $prefix . 'recurring_rent_rate',
                'type' => 'text',
            ),
            array(
                'name' => __('Security Deposit', 'cmb'),
                'desc' => __('', 'cmb'),
                'id' => $prefix . 'security_deposit',
                'type' => 'text',
            ),
            array(
                'name' => __('Move in Date', 'cmb'),
                'desc' => __('', 'cmb'),
                'id' => $prefix . 'move_in_date',
                'type' => 'text_date',
            ),
            array(
                'name' => __('Move Out Date', 'cmb'),
                'desc' => __('', 'cmb'),
                'id' => $prefix . 'move_out_date',
                'type' => 'text_date',
            ),
            array(
                'name' => __('Suite Number', 'cmb'),
                'desc' => __('What about with multiple suites?', 'cmb'),
                'id' => $prefix . 'suite_number',
                'type' => 'text',
            ),
            array(
                'name' => __('Company Address', 'cmb'),
                'desc' => __('', 'cmb'),
                'id' => $prefix . 'company_address',
                'type' => 'text',
            ),
            array(
                'name' => __('Forwarding Address', 'cmb'),
                'desc' => __('', 'cmb'),
                'id' => $prefix . 'forwarding_address',
                'type' => 'text',
            ),
            array(
                'name' => __('Delinquency Warnings', 'cmb'),
                'desc' => __('', 'cmb'),
                'id' => $prefix . 'delinquency_warnings',
                'type' => 'text',
            ),
            array(
                'name' => __('Special Received', 'cmb'),
                'desc' => __('', 'cmb'),
                'id' => $prefix . 'special_received',
                'type' => 'text',
            ),
            array(
                'name' => __('Multi Suite Discount', 'cmb'),
                'desc' => __('', 'cmb'),
                'id' => $prefix . 'multi_suite_discount',
                'type' => 'text',
            ),
            array(
                'name' => __('Lease', 'cmb'),
                'desc' => __('', 'cmb'),
                'id' => $prefix . 'lease',
                'type' => 'text',
            ),
            array(
                'name' => __('Addendums', 'cmb'),
                'desc' => __('', 'cmb'),
                'id' => $prefix . 'addendums',
                'type' => 'text',
            ),
            array(
                'name' => __('Forms', 'cmb'),
                'desc' => __('', 'cmb'),
                'id' => $prefix . 'forms',
                'type' => 'text',
            ),
            array(
                'name' => __('Maintenance Record', 'cmb'),
                'desc' => __('', 'cmb'),
                'id' => $prefix . 'maintenance_record',
                'type' => 'text',
            ),
            array(
                'name' => __('Charges', 'cmb'),
                'desc' => __('', 'cmb'),
                'id' => $prefix . 'charges',
                'type' => 'text',
            ),
            array(
                'name' => __('Late Payment Notice', 'cmb'),
                'desc' => __('', 'cmb'),
                'id' => $prefix . 'late_payment_notice',
                'type' => 'text',
            ),
            array(
                'name' => __('Notes', 'cmb'),
                'desc' => __('', 'cmb'),
                'id' => $prefix . 'notes',
                'type' => 'text',
            ),
            array(
                'name' => __('Allocated Monthly Calendar Credits', 'cmb'),
                'desc' => __('', 'cmb'),
                'id' => $prefix . 'amc_credits',
                'default' => $amc_credits_init,
                'type' => 'text',
            ),
            array(
                'name' => __('Purchased Calendar Credits', 'cmb'),
                'desc' => __('', 'cmb'),
                'id' => $prefix . 'pc_credits',
                'type' => 'text',
            ),
            array(
                'name' => __('Purchased Company upgrades', 'cmb'),
                'desc' => __('', 'cmb'),
                'id' => $prefix . 'pc_upgrades',
                'type' => 'text',
            )
        ),
    );


    /**
     * Suites field type included
     */
    $meta_boxes['suites_specifications_metabox'] = array(
        'id' => 'suites_specifications',
        'title' => __('Suites More Information', 'cmb'),
        'pages' => array('suites',), // Post type
        'context' => 'normal',
        'priority' => 'high',
        'show_names' => true, // Show field names on the left
        // 'cmb_styles' => true, // Enqueue the CMB stylesheet on the frontend
        'fields' => array(
            array(
                'name' => __('Room Number', 'cmb'),
                'desc' => __('', 'cmb'),
                'id' => $prefix . 'room_number',
                'type' => 'text',
            ),
            array(
                'name' => __('Rent Rate', 'cmb'),
                'desc' => __('', 'cmb'),
                'id' => $prefix . 'rent_rate',
                'type' => 'text',
            ),
            array(
                'name' => __('New Rent Rate', 'cmb'),
                'desc' => __('', 'cmb'),
                'id' => $prefix . 'new_rent_rate',
                'type' => 'text',
            ),
            array(
                'name' => __('Available', 'cmb'),
                'desc' => __('', 'cmb'),
                'id' => $prefix . 'available',
                'type' => 'select',
                'options' => array(
                    'Yes' => __('Yes', 'cmb'),
                    'No' => __('No', 'cmb'),
                //'Pending'   => __( 'Pending', 'cmb' ),
                ),
                'default' => 'Yes',
            ),
            array(
                'name' => __('Available Date', 'cmb'),
                'desc' => __('', 'cmb'),
                'id' => $prefix . 'available_date',
                'type' => 'text_date',
                'default' => '',
            ),
            array(
                'name' => __('Floor Level', 'cmb'),
                'desc' => __('', 'cmb'),
                'id' => $prefix . 'floor_level',
                'type' => 'select',
                'options' => array(
                    '1st' => __('1st', 'cmb'),
                    '2nd' => __('2nd', 'cmb'),
                    '3rd' => __('3rd', 'cmb'),
                ),
                'default' => '1st',
            ),
            array(
                'name' => __('Dimensions', 'cmb'),
                'desc' => __('', 'cmb'),
                'id' => $prefix . 'dimensions',
                'type' => 'text',
            ),
            array(
                'name' => __('Location Type', 'cmb'),
                'desc' => __('', 'cmb'),
                'id' => $prefix . 'location_type',
                'type' => 'text',
            /*
              'type' => 'select',
              'options' => array(
              'Lobby' => __( 'Lobby', 'yl' ),
              'E-BD' => __( 'E-BD', 'yl' ),
              'Ext. Door' => __( 'Ext. Door', 'cmb' ),
              'Int. Door'   => __( 'Int. Door', 'cmb' ),
              'Ext. Window'   => __( 'Ext. Window', 'cmb' ),
              'Int. Window'   => __( 'Int. Window', 'cmb' ),
              'Ext. Window/L' => __( 'Ext. Window/L', 'yl' ),
              'Int. Window/L' => __( 'Int. Window/L', 'yl' ),
              ),
              'default' => 'Int. Door',
             */
            ),
            array(
                'name' => __('Square Feet', 'cmb'),
                'desc' => __('', 'cmb'),
                'id' => $prefix . 'square_feet',
                'type' => 'text',
            ),
            array(
                'name' => __('Vacate Date', 'cmb'),
                'desc' => __('', 'cmb'),
                'id' => $prefix . 'date_vacate_notice_given',
                'type' => 'text',
            ),
            array(
                'name' => __('Early Vacate Addendum', 'cmb'),
                'desc' => __('', 'cmb'),
                'id' => $prefix . 'early_vacate_addendum',
                'type' => 'text',
            )
        ),
    );

    /**
     * Lease type included
     */
    $meta_boxes['lease_early_vacate_metabox'] = array(
        'id' => 'lease_early_vacate_info',
        'title' => __('Vacate Addendum', 'cmb'),
        'pages' => array('lease',), // Post type
        'context' => 'normal',
        'priority' => 'high',
        'show_names' => true, // Show field names on the left
        // 'cmb_styles' => true, // Enqueue the CMB stylesheet on the frontend
        'fields' => array(
            array(
                'name' => __('Lessee', 'cmb'),
                'desc' => __('', 'cmb'),
                'id' => $prefix . 'va_lessee',
                'type' => 'text',
            ),
            array(
                'name' => __('Building', 'cmb'),
                'desc' => __('', 'cmb'),
                'id' => $prefix . 'va_building',
                'type' => 'text',
            ),
            array(
                'name' => __('Business Name', 'cmb'),
                'desc' => __('', 'cmb'),
                'id' => $prefix . 'va_business_name',
                'type' => 'text',
            ),
            array(
                'name' => __('Security Deposit Held', 'cmb'),
                'desc' => __('', 'cmb'),
                'id' => $prefix . 'va_security_deposit_held',
                'type' => 'text',
            ),
            array(
                'name' => __('Date Vacate Notice Given', 'cmb'),
                'desc' => __('', 'cmb'),
                'id' => $prefix . 'date_vacate_notice_given',
                'type' => 'text',
            ),
            array(
                'name' => __('Early Vacate Addendum', 'cmb'),
                'desc' => __('', 'cmb'),
                'id' => $prefix . 'early_vacate_addendum',
                'type' => 'text',
            ),
            array(
                'name' => __('Vacate Date', 'cmb'),
                'desc' => __('', 'cmb'),
                'id' => $prefix . 'ninty_day_vacate_date',
                'type' => 'text',
            ),
            array(
                'name' => __('Suites Leased', 'cmb'),
                'desc' => __('', 'cmb'),
                'id' => $prefix . 'suites_leased',
                'type' => 'text',
            ),
            array(
                'name' => __('Suites Identified in this Agreement', 'cmb'),
                'desc' => __('', 'cmb'),
                'id' => $prefix . 'suites_identified_agreement',
                'type' => 'text',
            ),
            array(
                'name' => __('All-or-Nothing Demand for Multiple Suites', 'cmb'),
                'desc' => __('', 'cmb'),
                'id' => $prefix . 'all_n_demand_multiple_suites',
                'type' => 'text',
            ),
            array(
                'name' => __('Tenant Contact Email', 'cmb'),
                'desc' => __('', 'cmb'),
                'id' => $prefix . 'tenant_contact_email',
                'type' => 'text',
            ),
            array(
                'name' => __('Forwarding Phone', 'cmb'),
                'desc' => __('', 'cmb'),
                'id' => $prefix . 'va_cell_phone',
                'type' => 'text',
            ),
            array(
                'name' => __('Tenant Forwarding Address', 'cmb'),
                'desc' => __('', 'cmb'),
                'id' => $prefix . 'tenant_forwarding_address',
                'type' => 'text',
            ),
            array(
                'name' => __('Client Signature Date', 'cmb'),
                'desc' => __('Vacate Notice', 'cmb'),
                'id' => $prefix . 'vn_client_signature_date',
                'type' => 'text_date',
            ),
            array(
                'name' => __('Client Signature', 'cmb'),
                'desc' => __('Vacate Notice', 'cmb'),
                'id' => $prefix . 'vn_client_signature',
                'type' => 'file',
            ),
            array(
                'name' => __('BM Signature Date', 'cmb'),
                'desc' => __('Vacate Notice', 'cmb'),
                'id' => $prefix . 'vn_bm_signature_date',
                'type' => 'text_date',
            ),
            array(
                'name' => __('BM Signature', 'cmb'),
                'desc' => __('Vacate Notice', 'cmb'),
                'id' => $prefix . 'vn_bm_signature',
                'type' => 'file',
            ),
            array(
                'name' => __('Vacate Notice PDF', 'cmb'),
                'desc' => __('Auto Generated Vacate Notice PDF', 'cmb'),
                'id' => $prefix . 'vacate_notice_pdf',
                'type' => 'file',
            ),
            array(
                'name' => __('Client Signature Date', 'cmb'),
                'desc' => __('Vacate Addendum', 'cmb'),
                'id' => $prefix . 'va_client_signature_date',
                'type' => 'text_date',
            ),
            array(
                'name' => __('Client Signature', 'cmb'),
                'desc' => __('Vacate Addendum', 'cmb'),
                'id' => $prefix . 'va_client_signature',
                'type' => 'file',
            ),
            array(
                'name' => __('BM Signature Date', 'cmb'),
                'desc' => __('Vacate Addendum', 'cmb'),
                'id' => $prefix . 'va_bm_signature_date',
                'type' => 'text_date',
            ),
            array(
                'name' => __('BM Signature', 'cmb'),
                'desc' => __('Vacate Addendum', 'cmb'),
                'id' => $prefix . 'va_bm_signature',
                'type' => 'file',
            ),
            array(
                'name' => __('Vacate Addendum PDF', 'cmb'),
                'desc' => __('Auto Generated Vacate Addendum PDF', 'cmb'),
                'id' => $prefix . 'early_vacate_addendum_pdf',
                'type' => 'file',
            )
        ),
    );



    /**
     * Lease type included
     */
    $meta_boxes['lease_specifications_metabox'] = array(
        'id' => 'lease_specifications',
        'title' => __('Lease More Information', 'cmb'),
        'pages' => array('lease',), // Post type
        'context' => 'normal',
        'priority' => 'high',
        'show_names' => true, // Show field names on the left
        // 'cmb_styles' => true, // Enqueue the CMB stylesheet on the frontend
        'fields' => array(
            array(
                'name' => __('Lessor', 'cmb'),
                'desc' => __('', 'cmb'),
                'id' => $prefix . 'lessor',
                'type' => 'text',
            ),
            array(
                'name' => __('Location', 'cmb'),
                'desc' => __('', 'cmb'),
                'id' => $prefix . 'location',
                'type' => 'text',
            ),
            array(
                'name' => __('Location Phone Number', 'cmb'),
                'desc' => __('', 'cmb'),
                'id' => $prefix . 'location_phone_number',
                'type' => 'text',
            ),
            array(
                'name' => __('Suite Number', 'cmb'),
                'desc' => __('', 'cmb'),
                'id' => $prefix . 'suite_number',
                'type' => 'text',
            ),
            array(
                'name' => __('Suite Number Auxiliary', 'cmb'),
                'desc' => $auxiliary_suite_number_display,
                'id' => $prefix . 'product_id',
                'type' => 'text',
            ),
            array(
                'name' => __('Lease Start Date', 'cmb'),
                'desc' => __('', 'cmb'),
                'id' => $prefix . 'lease_start_date',
                'type' => 'text_date',
            ),
            array(
                'name' => __('First Month Rent Rate', 'cmb'),
                'desc' => __('', 'cmb'),
                'id' => $prefix . 'first_month_rent_rate',
                'type' => 'text',
            ),
            array(
                'name' => __('Monthly Rent', 'cmb'),
                'desc' => __('', 'cmb'),
                'id' => $prefix . 'monthly_rent',
                'type' => 'text',
            ),
            array(
                'name' => __('New Monthly Rent', 'cmb'),
                'desc' => __('', 'cmb'),
                'id' => $prefix . 'new_monthly_rent',
                'type' => 'text',
            ),
            array(
                'name' => __('Security Deposit', 'cmb'),
                'desc' => __('', 'cmb'),
                'id' => $prefix . 'security_deposit',
                'type' => 'text',
            ),
            array(
                'name' => __('Security Deposit Refund Date', 'cmb'),
                'desc' => __('', 'cmb'),
                'id' => $prefix . 'lease_security_refund_date',
                'type' => 'text_date',
            ),
            array(
                'name' => __('Security Deposit Refund Amount', 'cmb'),
                'desc' => __('', 'cmb'),
                'id' => $prefix . 'lease_security_refund_amount',
                'type' => 'text',
            ),
            array(
                'name' => __('Security Deposit Notes', 'cmb'),
                'desc' => __('', 'cmb'),
                'id' => $prefix . 'security_deposit_notes',
                'type' => 'textarea',
            ),
            /* array(
              'name' => __( 'Lease Term', 'cmb' ),
              'desc' => __( '', 'cmb' ),
              'id'   => $prefix . 'lease_term',
              'type' => 'text',
              ), */
            array(
                'name' => __('Vacate Notice', 'cmb'),
                'desc' => __('', 'cmb'),
                'id' => $prefix . 'vacate_notice',
                'type' => 'text',
            ),
            array(
                'name' => __('Promotional Code', 'cmb'),
                'desc' => __('', 'cmb'),
                'id' => $prefix . 'promotional_code',
                'type' => 'text',
            ),
            array(
                'name' => __('Service Fees', 'cmb'),
                'desc' => __('', 'cmb'),
                'id' => $prefix . 'service_fees',
                'type' => 'text',
            ),
            array(
                'name' => __('Phone Service Fee', 'cmb'),
                'desc' => __('', 'cmb'),
                'id' => $prefix . 'phone_fee',
                'type' => 'text',
            ),
            array(
                'name' => __('Cable Service Fee', 'cmb'),
                'desc' => __('', 'cmb'),
                'id' => $prefix . 'cable_fee',
                'type' => 'text',
            ),
            array(
                'name' => __('IP Service Service Fee', 'cmb'),
                'desc' => __('', 'cmb'),
                'id' => $prefix . 'ipservice_fee',
                'type' => 'text',
            ),
            array(
                'name' => __('Fax Service Fee', 'cmb'),
                'desc' => __('', 'cmb'),
                'id' => $prefix . 'fax_fee',
                'type' => 'text',
            ),
            array(
                'name' => __('Postage Service Fee', 'cmb'),
                'desc' => __('', 'cmb'),
                'id' => $prefix . 'postage_fee',
                'type' => 'text',
            ),
            array(
                'name' => __('Credit Card Line Service Fee', 'cmb'),
                'desc' => __('', 'cmb'),
                'id' => $prefix . 'credit_card_line_fee',
                'type' => 'text',
            ),
            array(
                'name' => __('Multi Suite Discount applied?', 'cmb'),
                'desc' => __('', 'cmb'),
                'id' => $prefix . 'multisite_coupon',
                'type' => 'radio_inline',
                'options' => array(
                    true => __('Yes', 'cmb'),
                    false => __('No', 'cmb'),
                ),
                'default' => false,
            ),
            array(
                'name' => __('Multi Suite Discount', 'cmb'),
                'desc' => __('', 'cmb'),
                'id' => $prefix . 'multi_suite_discount',
                'type' => 'text',
            ),
            array(
                'name' => __('Addendums', 'cmb'),
                'desc' => __('', 'cmb'),
                'id' => $prefix . 'addendums',
                'type' => 'text',
            ),
            array(
                'name' => __('Due at Signing', 'cmb'),
                'desc' => __('', 'cmb'),
                'id' => $prefix . 'due_at_signing',
                'type' => 'text',
            ),
            array(
                'name' => __('Is the Lessee the same as the Guarantor', 'cmb'),
                'desc' => __('', 'cmb'),
                'id' => $prefix . 'lessee_guarantor_same',
                'type' => 'radio_inline',
                'options' => array(
                    'Yes' => __('Yes', 'cmb'),
                    'No' => __('No', 'cmb'),
                ),
                'default' => 'Yes',
            ),
            array(
                'name' => __('Lease Version', 'cmb'),
                'desc' => __('', 'cmb'),
                'id' => $prefix . 'lease_version',
                'type' => 'text',
            )
        ),
    );


    /**
     * Lease type included
     */
    $meta_boxes['lease_tenant_info_metabox'] = array(
        'id' => 'lease_tenant_info',
        'title' => __('Tenant Information', 'cmb'),
        'pages' => array('lease',), // Post type
        'context' => 'normal',
        'priority' => 'high',
        'show_names' => true, // Show field names on the left
        // 'cmb_styles' => true, // Enqueue the CMB stylesheet on the frontend
        'fields' => array(
            array(
                'name' => __('Copy Machine Password', 'cmb'),
                'desc' => __('', 'cmb'),
                'id' => $prefix . 'passwords_title',
                'type' => 'title',
            ),
            array(
                'name' => __('Password', 'cmb'),
                'desc' => __('', 'cmb'),
                'id' => $prefix . 'tinfo_copy_machine',
                'type' => 'text_medium',
            ),
            array(
                'name' => __('Website Conference Room Log-in - Meeting Calendar', 'cmb'),
                'desc' => __('', 'cmb'),
                'id' => $prefix . 'passwords_title_2',
                'type' => 'title',
            ),
            array(
                'name' => __('Username', 'cmb'),
                'desc' => __('', 'cmb'),
                'id' => $prefix . 'tinfo_user_name',
                'type' => 'text_medium',
            ),
            array(
                'name' => __('Password', 'cmb'),
                'desc' => __('', 'cmb'),
                'id' => $prefix . 'tinfo_password',
                'type' => 'text_medium',
            ),
            array(
                'name' => __('Postage Machine Password', 'cmb'),
                'desc' => __('', 'cmb'),
                'id' => $prefix . 'passwords_title_3',
                'type' => 'title',
            ),
            array(
                'name' => __('Password', 'cmb'),
                'desc' => __('', 'cmb'),
                'id' => $prefix . 'tinfo_postage_password',
                'type' => 'text_medium',
            ),
            array(
                'name' => __('Account #', 'cmb'),
                'desc' => __('', 'cmb'),
                'id' => $prefix . 'tinfo_account_number',
                'type' => 'text_medium',
            ),
            array(
                'name' => __('FOBS', 'cmb'),
                'desc' => __('', 'cmb'),
                'id' => $prefix . 'passwords_title_4',
                'type' => 'title',
            ),
            array(
                'name' => __('Fob #1 name', 'cmb'),
                'desc' => __('', 'cmb'),
                'id' => $prefix . 'tinfo_fob_1_name',
                'type' => 'text_medium',
            ),
            array(
                'name' => __('Fob #1 #(s)', 'cmb'),
                'desc' => __('', 'cmb'),
                'id' => $prefix . 'tinfo_fob_1_no',
                'type' => 'text',
            ),
            array(
                'name' => __('Fob #2 name', 'cmb'),
                'desc' => __('', 'cmb'),
                'id' => $prefix . 'tinfo_fob_2_name',
                'type' => 'text_medium',
            ),
            array(
                'name' => __('Fob #2 #(s)', 'cmb'),
                'desc' => __('', 'cmb'),
                'id' => $prefix . 'tinfo_fob_2_no',
                'type' => 'text',
            ),
            array(
                'name' => __('Fob #3 name', 'cmb'),
                'desc' => __('', 'cmb'),
                'id' => $prefix . 'tinfo_fob_3_name',
                'type' => 'text_medium',
            ),
            array(
                'name' => __('Fob #3 #(s)', 'cmb'),
                'desc' => __('', 'cmb'),
                'id' => $prefix . 'tinfo_fob_3_no',
                'type' => 'text',
            ),
            array(
                'name' => __('Contact info', 'cmb'),
                'desc' => __('', 'cmb'),
                'id' => $prefix . 'passwords_title_5',
                'type' => 'title',
            ),
            array(
                'name' => __('E-mail', 'cmb'),
                'desc' => __('', 'cmb'),
                'id' => $prefix . 'tinfo_email',
                'type' => 'text_medium',
            ),
            array(
                'name' => __('Name/phone', 'cmb'),
                'desc' => __('', 'cmb'),
                'id' => $prefix . 'tinfo_name_nhone',
                'type' => 'text_medium',
            ),
            array(
                'name' => __('Emergency contact', 'cmb'),
                'desc' => __('', 'cmb'),
                'id' => $prefix . 'tinfo_emergency_contact',
                'type' => 'text_medium',
            ),
            array(
                'name' => __('Tenant/Corporate Address', 'cmb'),
                'desc' => __('', 'cmb'),
                'id' => $prefix . 'tinfo_corporate_address',
                'type' => 'text',
            ),
            array(
                'name' => __('Billing/Corporate Contact/Phone', 'cmb'),
                'desc' => __('', 'cmb'),
                'id' => $prefix . 'tinfo_billing_contact',
                'type' => 'text',
            ),
            array(
                'name' => __('Tenant Building Directory', 'cmb'),
                'desc' => __('', 'cmb'),
                'id' => $prefix . 'passwords_title_6',
                'type' => 'title',
            ),
            array(
                'name' => __('Name as you wish it to appear', 'cmb'),
                'desc' => __('', 'cmb'),
                'id' => $prefix . 'tinfo_name_as_you_wish',
                'type' => 'text_medium',
            ),
            array(
                'name' => __('Suite #', 'cmb'),
                'desc' => __('', 'cmb'),
                'id' => $prefix . 'tinfo_suite_numbers',
                'type' => 'text_small',
            ),
            array(
                'name' => __('PACKAGE DELIVERY WAIVER', 'cmb'),
                'desc' => __('', 'cmb'),
                'id' => $prefix . 'passwords_title_7',
                'type' => 'title',
            ),
            array(
                'name' => __('Authorized Representative #1', 'cmb'),
                'desc' => __('', 'cmb'),
                'id' => $prefix . 'tinfo_auth_representative_1',
                'type' => 'text_medium',
            ),
            array(
                'name' => __('Date #1', 'cmb'),
                'desc' => __('', 'cmb'),
                'id' => $prefix . 'tinfo_date_1',
                'type' => 'text_date',
            ),
            array(
                'name' => __('Authorized Representative #2', 'cmb'),
                'desc' => __('', 'cmb'),
                'id' => $prefix . 'tinfo_auth_representative_2',
                'type' => 'text_medium',
            ),
            array(
                'name' => __('Date #2', 'cmb'),
                'desc' => __('', 'cmb'),
                'id' => $prefix . 'tinfo_date_2',
                'type' => 'text_date',
            ),
            array(
                'name' => __('How did you first find out about us?', 'cmb'),
                'desc' => __('', 'cmb'),
                'id' => $prefix . 'tinfo_first_find_out_about_us',
                'type' => 'text',
            ),
            array(
                'name' => __('What is the main reason you chose Yeager Office Suites?', 'cmb'),
                'desc' => __('', 'cmb'),
                'id' => $prefix . 'tinfo_main_reason_you_chose',
                'type' => 'text',
            ),
            array(
                'name' => __('Lease Summary Signature (Package Waiver)', 'cmb'),
                'desc' => __('', 'cmb'),
                'id' => $prefix . 'client_pw_signature',
                'type' => 'file',
            ),
            array(
                'name' => __('Lease Summary Signature 2 (Package Waiver)', 'cmb'),
                'desc' => __('', 'cmb'),
                'id' => $prefix . 'client_pw_signature_2',
                'type' => 'file',
            ),
        ),
    );


    /**
     * Lease type included
     */
    $meta_boxes['lease_info_metabox'] = array(
        'id' => 'lease_info',
        'title' => __('Lease Information', 'cmb'),
        'pages' => array('lease',), // Post type
        'context' => 'normal',
        'priority' => 'high',
        'show_names' => true, // Show field names on the left
        // 'cmb_styles' => true, // Enqueue the CMB stylesheet on the frontend
        'fields' => array(
            array(
                'name' => __('First Name', 'cmb'),
                'desc' => __('', 'cmb'),
                'id' => $prefix . 'l_first_name',
                'type' => 'text',
            ),
            array(
                'name' => __('Middle Name', 'cmb'),
                'desc' => __('', 'cmb'),
                'id' => $prefix . 'l_middle_name',
                'type' => 'text',
            ),
            array(
                'name' => __('Last Name', 'cmb'),
                'desc' => __('', 'cmb'),
                'id' => $prefix . 'l_last_name',
                'type' => 'text',
            ),
            array(
                'name' => __('Phone', 'cmb'),
                'desc' => __('', 'cmb'),
                'id' => $prefix . 'l_phone',
                'type' => 'text',
            ),
            array(
                'name' => __('Email', 'cmb'),
                'desc' => __('', 'cmb'),
                'id' => $prefix . 'l_email',
                'type' => 'text_email',
            ),
            array(
                'name' => __('Street Address', 'cmb'),
                'desc' => __('', 'cmb'),
                'id' => $prefix . 'l_street_address',
                'type' => 'text',
            ),
            array(
                'name' => __('Address Line 2', 'cmb'),
                'desc' => __('', 'cmb'),
                'id' => $prefix . 'l_address_line_2',
                'type' => 'text',
            ),
            array(
                'name' => __('City', 'cmb'),
                'desc' => __('', 'cmb'),
                'id' => $prefix . 'l_city',
                'type' => 'text',
            ),
            array(
                'name' => __('State', 'cmb'),
                'desc' => __('', 'cmb'),
                'id' => $prefix . 'l_state',
                'type' => 'select',
                'options' => $us_states_full,
                'default' => 'Texas',
            ),
            array(
                'name' => __('ZIP Code', 'cmb'),
                'desc' => __('', 'cmb'),
                'id' => $prefix . 'l_zip_code',
                'type' => 'text',
            )
        ),
    );


    /**
     * Lease type included
     */
    $meta_boxes['guarantor_info_metabox'] = array(
        'id' => 'guarantor_info',
        'title' => __('Guarantor Information', 'cmb'),
        'pages' => array('lease',), // Post type
        'context' => 'normal',
        'priority' => 'high',
        'show_names' => true, // Show field names on the left
        // 'cmb_styles' => true, // Enqueue the CMB stylesheet on the frontend
        'fields' => array(
            array(
                'name' => __('First Name', 'cmb'),
                'desc' => __('', 'cmb'),
                'id' => $prefix . 'g_first_name',
                'type' => 'text',
            ),
            array(
                'name' => __('Middle Name', 'cmb'),
                'desc' => __('', 'cmb'),
                'id' => $prefix . 'g_middle_name',
                'type' => 'text',
            ),
            array(
                'name' => __('Last Name', 'cmb'),
                'desc' => __('', 'cmb'),
                'id' => $prefix . 'g_last_name',
                'type' => 'text',
            ),
            array(
                'name' => __('Phone', 'cmb'),
                'desc' => __('', 'cmb'),
                'id' => $prefix . 'g_phone',
                'type' => 'text',
            ),
            array(
                'name' => __('Email', 'cmb'),
                'desc' => __('', 'cmb'),
                'id' => $prefix . 'g_email',
                'type' => 'text_email',
            ),
            array(
                'name' => __('Street Address', 'cmb'),
                'desc' => __('', 'cmb'),
                'id' => $prefix . 'g_street_address',
                'type' => 'text',
            ),
            array(
                'name' => __('Address Line 2', 'cmb'),
                'desc' => __('', 'cmb'),
                'id' => $prefix . 'g_address_line_2',
                'type' => 'text',
            ),
            array(
                'name' => __('City', 'cmb'),
                'desc' => __('', 'cmb'),
                'id' => $prefix . 'g_city',
                'type' => 'text',
            ),
            array(
                'name' => __('State', 'cmb'),
                'desc' => __('', 'cmb'),
                'id' => $prefix . 'g_state',
                'type' => 'select',
                'options' => $us_states_full,
                'default' => 'Texas',
            ),
            array(
                'name' => __('ZIP Code', 'cmb'),
                'desc' => __('', 'cmb'),
                'id' => $prefix . 'g_zip_code',
                'type' => 'text',
            )
        ),
    );

    /**
     * Lease type included
     */
    $meta_boxes['company_info_metabox'] = array(
        'id' => 'company_info',
        'title' => __('Company Information', 'cmb'),
        'pages' => array('lease',), // Post type
        'context' => 'normal',
        'priority' => 'high',
        'show_names' => true, // Show field names on the left
        // 'cmb_styles' => true, // Enqueue the CMB stylesheet on the frontend
        'fields' => array(
            array(
                'name' => __('Company Name', 'cmb'),
                'desc' => __('', 'cmb'),
                'id' => $prefix . 'company_name',
                'type' => 'select',
                'options' => get_company_names_array(),
            ),
            array(
                'name' => __('Company Type', 'cmb'),
                'desc' => __('', 'cmb'),
                'id' => $prefix . 'company_type',
                'type' => 'select',
                'options' => get_company_types(),
            ),
            array(
                'name' => __('Company Directory Name', 'cmb'),
                'desc' => __('', 'cmb'),
                'id' => $prefix . 'company_directory_name',
                'type' => 'text',
            )
        ),
    );

    /**
     * Lease type included
     */
    $meta_boxes['signature_info_building_manager_metabox'] = array(
        'id' => 'signature_info_building_manager',
        'title' => __('Signature Building Manager', 'cmb'),
        'pages' => array('lease',), // Post type
        'context' => 'normal',
        'priority' => 'high',
        'show_names' => true, // Show field names on the left
        // 'cmb_styles' => true, // Enqueue the CMB stylesheet on the frontend
        'fields' => array(
            array(
                'name' => __('Date', 'cmb'),
                'desc' => __('', 'cmb'),
                'id' => $prefix . 'bm_signature_date',
                'type' => 'text_date',
            ),
            array(
                'name' => __('Signature', 'cmb'),
                'desc' => __('', 'cmb'),
                'id' => $prefix . 'bm_signature',
                'type' => 'file',
            ),
            array(
                'name' => __('Date (Lease Summary)', 'cmb'),
                'desc' => __('', 'cmb'),
                'id' => $prefix . 'bm_ls_signature_date',
                'type' => 'text_date',
            ),
            array(
                'name' => __('Lease Summary Signature', 'cmb'),
                'desc' => __('', 'cmb'),
                'id' => $prefix . 'bm_ls_signature',
                'type' => 'file',
            ),
            array(
                'name' => __('Building Manager', 'cmb'),
                'desc' => __('', 'cmb'),
                //'id'   => $prefix . 'lease_building_manager',
                'id' => $prefix . 'author_id',
                'type' => 'select',
                'options' => get_lease_client_bm_arrays_for_cmb_metabox(),
                'default' => '1',
            )
        ),
    );




    /**
     * Lease type included
     */
    $meta_boxes['signature_info_client_metabox'] = array(
        'id' => 'signature_info_client',
        'title' => __('Signature Client', 'cmb'),
        'pages' => array('lease',), // Post type
        'context' => 'normal',
        'priority' => 'high',
        'show_names' => true, // Show field names on the left
        // 'cmb_styles' => true, // Enqueue the CMB stylesheet on the frontend
        'fields' => array(
            array(
                'name' => __('Date', 'cmb'),
                'desc' => __('', 'cmb'),
                'id' => $prefix . 'client_signature_date',
                'type' => 'text_date',
            ),
            array(
                'name' => __('Signature', 'cmb'),
                'desc' => __('', 'cmb'),
                'id' => $prefix . 'client_signature',
                'type' => 'file',
            ),
            array(
                'name' => __('Date (Lease Summary)', 'cmb'),
                'desc' => __('', 'cmb'),
                'id' => $prefix . 'client_ls_signature_date',
                'type' => 'text_date',
            ),
            array(
                'name' => __('Lease Summary Signature', 'cmb'),
                'desc' => __('', 'cmb'),
                'id' => $prefix . 'client_ls_signature',
                'type' => 'file',
            ),
            array(
                'name' => __('Client', 'cmb'),
                'desc' => __('', 'cmb'),
                'id' => $prefix . 'lease_user',
                'type' => 'select',
                'options' => get_lease_client_bm_arrays_for_cmb_metabox('lease_client'),
                'default' => '1',
            )
        ),
    );

    /**
     * Lease type included
     */
    $meta_boxes['lease_pdf_info_metabox'] = array(
        'id' => 'lease_pdf_info',
        'title' => __('Lease PDF', 'cmb'),
        'pages' => array('lease',), // Post type
        'context' => 'normal',
        'priority' => 'high',
        'show_names' => false, // Show field names on the left
        // 'cmb_styles' => true, // Enqueue the CMB stylesheet on the frontend
        'fields' => array(
            array(
                'name' => __('Lease PDF', 'cmb'),
                'desc' => __('Auto Generated Lease PDF', 'cmb'),
                'id' => $prefix . 'lease_pdf',
                'type' => 'file',
            ),
            array(
                'name' => __('Summary PDF', 'cmb'),
                'desc' => __('Auto Generated Lease Summary PDF', 'cmb'),
                'id' => $prefix . 'lease_summary_pdf',
                'type' => 'file',
            ),
            array(
                'name' => __('Tenant Information PDF', 'cmb'),
                'desc' => __('Auto Generated Tenant Information PDF', 'cmb'),
                'id' => $prefix . 'tenant_info_pdf',
                'type' => 'file',
            ),
            array(
                'name' => __('Complete Lease PDF', 'cmb'),
                'desc' => __('Complete Lease PDF including summary and lease info', 'cmb'),
                'id' => $prefix . 'full_lease_pdf',
                'type' => 'file',
            )
        ),
    );

    /**
     * Prospect type included
     */
    $meta_boxes['prospect_info_metabox'] = array(
        'id' => 'prospect_info',
        'title' => __('Prospect Information', 'cmb'),
        'pages' => array('prospects',), // Post type
        'context' => 'normal',
        'priority' => 'high',
        'show_names' => true, // Show field names on the left
        // 'cmb_styles' => true, // Enqueue the CMB stylesheet on the frontend
        'fields' => array(
            array(
                'name' => __('Date', 'cmb'),
                'desc' => __('', 'cmb'),
                'id' => $prefix . 'pros_date',
                'type' => 'text_date',
            ),
            /* array(
              'name' => __( 'Name', 'cmb' ),
              'desc' => __( '', 'cmb' ),
              'id'   => $prefix . 'pros_name',
              'type' => 'text',
              ), */
            array(
                'name' => __('Phone', 'cmb'),
                'desc' => __('', 'cmb'),
                'id' => $prefix . 'pros_phone',
                'type' => 'text',
            ),
            array(
                'name' => __('Email', 'cmb'),
                'desc' => __('', 'cmb'),
                'id' => $prefix . 'pros_email',
                'type' => 'text_email',
            ),
            array(
                'name' => __('Company', 'cmb'),
                'desc' => __('', 'cmb'),
                'id' => $prefix . 'pros_company',
                'type' => 'text',
            ),
            array(
                'name' => __('How Did You Discover Us?', 'cmb'),
                'desc' => __('', 'cmb'),
                'id' => $prefix . 'pros_discover',
                'type' => 'select',
                'options' => array(
                    'tenant' => __('Tenant', 'yl'),
                    'friend' => __('Friend', 'yl'),
                    'postcard' => __('Postcard', 'cmb'),
                    'website' => __('Website', 'cmb'),
                    'newspaper' => __('Newspaper', 'cmb'),
                    'other' => __('Other', 'cmb'),
                ),
                'default' => 'tenant',
            ),
            array(
                'name' => __('Which suites do you have an interest in?', 'cmb'),
                'desc' => __('', 'cmb'),
                'id' => $prefix . 'pros_interest',
                'type' => 'select',
                'options' => get_suites_list(),
            ),
            array(
                'name' => __('When are you available to move in?', 'cmb'),
                'desc' => __('', 'cmb'),
                'id' => $prefix . 'pros_move_in',
                'type' => 'text_date',
            ),
            array(
                'name' => __('When/how should we next contact you?', 'cmb'),
                'desc' => __('', 'cmb'),
                'id' => $prefix . 'pros_contact_option',
                'type' => 'text',
            ),
        ),
    );

    // Add other metaboxes as needed
    return $meta_boxes;
}

add_filter('manage_edit-lease_columns', 'yl_edit_lease_columns');

function yl_edit_lease_columns($columns) {

    $columns['yl_suite'] = __('Suite Number', 'yl_lease');
    $columns['yl_client'] = __('Client', 'yl_lease');
    $columns['yl_company'] = __('Company', 'yl_lease');
    $columns['yl_bm'] = __('B.Manager', 'yl_lease');
    $columns['lease_version'] = __('Lease Version', 'yl_lease');

    return $columns;
}

add_action('manage_lease_posts_custom_column', 'yl_manage_lease_columns', 10, 2);

function yl_manage_lease_columns($column, $post_id) {
    global $post;

    switch ($column) {
        case 'yl_suite' :
            if (get_post_meta($post_id, '_yl_suite_number', true)) {
                $suite_number = get_post_meta($post_id, '_yl_suite_number', true);
                if ($suite_number == -1)
                    echo 'Y-Membership';
                else
                    echo $suite_number;
            }
            break;
        case 'yl_client' :
            $client_id = get_post_meta($post_id, '_yl_lease_user', true);
            $client_meta = get_user_meta($client_id);
			if(isset($client_meta['first_name'][0]))
            	echo $client_meta['first_name'][0] . ' ' . $client_meta['last_name'][0];
            break;
        case 'yl_company' :
            echo get_the_title(get_post_meta($post_id, '_yl_company_name', true));
            break;
        case 'yl_bm' :
            $post = get_post($post_id);
            $author = get_user_by('id', $post->post_author);
            echo $author->data->user_login;
            break;
        case 'lease_version' :
            echo get_post_meta($post_id, '_yl_lease_version', true);
            break;
        default :
            break;
    }
}

add_filter('manage_edit-suites_columns', 'yl_edit_suites_columns');

function yl_edit_suites_columns($columns) {
    unset($columns['date']);
    //unset($columns['taxonomy-suitestype']);

    $columns['yl_floor'] = __('Floor', 'yl_lease');
    $columns['yl_square_feet'] = __('Square Feet', 'yl_lease');
	$columns['yl_rate'] = __('Rent Rate', 'yl_lease');
    $columns['yl_available'] = __('Avail.?', 'yl_lease');
    $columns['yl_available_date'] = __('Avail. date', 'yl_lease');

    return $columns;
}

add_action('manage_suites_posts_custom_column', 'yl_manage_suites_columns', 10, 2);

function yl_manage_suites_columns($column, $post_id) {
    global $post;

    $meta = get_post_meta($post_id);
    //print_r($meta);

    switch ($column) {
        case 'yl_available' :
            if (get_post_meta($post_id, '_yl_available', true) == 'Yes') {
                ?> <span class="dashicons dashicons-yes _yl_available_yes"></span> <?php

            } else {
                ?> <span class="dashicons dashicons-no _yl_available_no"></span> <?php

            }

            if (get_post_meta($post_id, '_yl_hold', true)) {
                $client_meta = get_user_meta(get_post_meta($post_id, '_yl_hold_client', true));
                echo '<br><strong>On HOLD</strong> by <strong>' . $client_meta['first_name'][0] . ' ' . $client_meta['last_name'][0] . '</strong>';
            }
            break;

        case 'yl_floor' :
            echo get_post_meta($post_id, '_yl_floor_level', true);
            break;

        case 'yl_rate' :
            echo '$' . get_post_meta($post_id, '_yl_rent_rate', true);
            break;

        case 'yl_available_date' :
            echo get_post_meta($post_id, '_yl_available_date', true);
            break;

		case 'yl_square_feet' :
            echo get_post_meta($post_id, '_yl_square_feet', true);
            break;
			
        /* Just break out of the switch statement for everything else. */
        default :
            break;
    }
}

/**
 * Save post metadata when a product is saved.
 *
 * @param int $post_id The post ID.
 * @param post $post The post object.
 * @param bool $update Whether this is an existing post being updated or not.
 */
function save_yl_product_meta($post_id, $post, $update) {

    /*
     * In production code, $slug should be set only once in the plugin,
     * preferably as a class property, rather than in each function that needs it.
     */
    $slug = 'product';

    // If this isn't a 'book' post, don't update it.
    if ($slug != $post->post_type) {
        return;
    }

    // - Update the post's metadata.
//    if ( isset( $_REQUEST['publisher'] ) ) {
//        update_post_meta( $post_id, 'publisher', sanitize_text_field( $_REQUEST['publisher'] ) );
//    }
}

//add_action( 'save_post', 'save_yl_product_meta', 10, 3 );


function get_suites_list() {
    $args = array(
        'post_type' => 'suites',
        'posts_per_page' => -1
    );

    $query = new WP_Query($args);

    $suites = array();
    if ($query->have_posts()) {
        while ($query->have_posts()) {
            $query->the_post();

            $suites[get_the_ID()] = esc_html(get_the_title());
        }
        wp_reset_postdata();
    }

    return $suites;
}