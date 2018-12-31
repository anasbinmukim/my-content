<?php

/**
 * SUITES CTP 
 */
$labels_suites = array(
	'name' 					=> __( 'Suites', 'YL' ),
	'singular_name' 		=> __( 'Suite', 'YL' ),
	'menu_name'				=> _x( 'Suites', 'Admin menu name', 'YL' ),
	'add_new' 				=> __( 'Add Suite', 'YL' ),
	'add_new_item' 			=> __( 'Add New Suite', 'YL' ),
	'edit' 					=> __( 'Edit', 'YL' ),
	'edit_item' 			=> __( 'Edit Suite', 'YL' ),
	'new_item' 				=> __( 'New Suite', 'YL' ),
	'view' 					=> __( 'View Suite', 'YL' ),
	'view_item' 			=> __( 'View Suite', 'YL' ),
	'search_items' 			=> __( 'Search Suites', 'YL' ),
	'not_found' 			=> __( 'No Suites found', 'YL' ),
	'not_found_in_trash' 	=> __( 'No Suites found in trash', 'YL' ),
	'parent' 				=> __( 'Parent Suites', 'YL' )
);
register_post_type('suites', array('labels' => $labels_suites,		
		'description' 			=> __( 'This is where you can add new suites to your site.', 'YL' ),
		'public' 				=> true,
		'show_ui' 				=> true,
		'capability_type' 		=> 'post',
		'map_meta_cap'			=> true,
		'publicly_queryable' 	=> true,
		'exclude_from_search' 	=> false,
		'hierarchical' 			=> false, // Hierarchical causes memory issues - WP loads all records!
		'rewrite' 				=> array('slug' => 'suites'),
		'query_var' 			=> true,
		'menu_position'      	=> 25,
		'supports' 				=> array('title'),
		'show_in_nav_menus' 	=> true,
		'menu_icon'				=> 'dashicons-admin-multisite'
	)
);

$labels_suites_types = array(
	'name'                       => _x( 'Suite Type', 'taxonomy general name' ),
	'singular_name'              => _x( 'Suite Type', 'taxonomy singular name' ),
	'search_items'               => __( 'Search Suite Types' ),
	'popular_items'              => __( 'Popular Suite Type' ),
	'all_items'                  => __( 'All Suite Type' ),
	'parent_item'                => __( 'Parent Suite Type' ),
	'parent_item_colon'          => __( 'Parent Suite Type:' ),
	'edit_item'                  => __( 'Edit Suite Type' ),
	'update_item'                => __( 'Update Suite Type' ),
	'add_new_item'               => __( 'Add New Suite Type' ),
	'new_item_name'              => __( 'New Suite Type Name' ),
	'separate_items_with_commas' => __( 'Separate Suite Type with commas' ),
	'add_or_remove_items'        => __( 'Add or remove Suite Type' ),
	'choose_from_most_used'      => __( 'Choose from the most used Suite Type' ),
	'not_found'                  => __( 'No Suite Type found.' ),
	'menu_name'                  => __( 'Suite Type' ),
);

$args_suites_types = array(
	'hierarchical'          => true,
	'labels'                => $labels_suites_types,	
	'show_ui'               => true,
	'show_admin_column'     => true,
	'update_count_callback' => '_update_post_term_count',
	'query_var'             => true,
	'rewrite'               => array( 'slug' => 'suitestype' ),
);

register_taxonomy( 'suitestype', 'suites', $args_suites_types );	



/**
 * LEASE CTP 
 */
$labels_lease = array(
	'name' 					=> __( 'Lease', 'YL' ),
	'singular_name' 		=> __( 'Lease', 'YL' ),
	'menu_name'				=> _x( 'Leases', 'Admin menu name', 'YL' ),
	'add_new' 				=> __( 'Add Lease', 'YL' ),
	'add_new_item' 			=> __( 'Add New Lease', 'YL' ),
	'edit' 					=> __( 'Edit', 'YL' ),
	'edit_item' 			=> __( 'Edit Lease', 'YL' ),
	'new_item' 				=> __( 'New Lease', 'YL' ),
	'view' 					=> __( 'View Lease', 'YL' ),
	'view_item' 			=> __( 'View Lease', 'YL' ),
	'search_items' 			=> __( 'Search Lease', 'YL' ),
	'not_found' 			=> __( 'No Lease found', 'YL' ),
	'not_found_in_trash' 	=> __( 'No Lease found in trash', 'YL' ),
	'parent' 				=> __( 'Parent Lease', 'YL' )
);
register_post_type('lease', array('labels' => $labels_lease,		
		'description' 			=> __( 'This is where you can add new leases to your site.', 'YL' ),
		'public' 				=> true,
		'show_ui' 				=> true,
		'capability_type' 		=> 'post',
		'map_meta_cap'			=> true,
		'publicly_queryable' 	=> true,
		'exclude_from_search' 	=> false,
		'hierarchical' 			=> false, // Hierarchical causes memory issues - WP loads all records!
		'rewrite' 				=> array('slug' => 'lease'),
		'query_var' 			=> true,
		'menu_position'      	=> 26,
		'supports' 				=> array('title'),
		'show_in_nav_menus' 	=> true,
		'menu_icon' 			=> 'dashicons-welcome-write-blog'
	)
);

/**
 * COMPANY CTP 
 */
$labels_company = array(
	'name' 					=> __( 'Companies', 'YL' ),
	'singular_name' 		=> __( 'Company', 'YL' ),
	'menu_name'				=> _x( 'Companies', 'Admin menu name', 'YL' ),
	'add_new' 				=> __( 'Add Company', 'YL' ),
	'add_new_item' 			=> __( 'Add New', 'YL' ),
	'edit' 					=> __( 'Edit', 'YL' ),
	'edit_item' 			=> __( 'Edit Company', 'YL' ),
	'new_item' 				=> __( 'New Company', 'YL' ),
	'view' 					=> __( 'View Company', 'YL' ),
	'view_item' 			=> __( 'View Company', 'YL' ),
	'search_items' 			=> __( 'Search Company', 'YL' ),
	'not_found' 			=> __( 'No Company found', 'YL' ),
	'not_found_in_trash' 	=> __( 'No Company found in trash', 'YL' ),
	'parent' 				=> __( 'Parent Company', 'YL' )
);
register_post_type('company', array('labels' => $labels_company,		
		'description' 			=> __( 'This is where you can add new Companies to your site.', 'YL' ),
		'public' 				=> true,
		'show_ui' 				=> true,
		'capability_type' 		=> 'post',
		'map_meta_cap'			=> true,
		'publicly_queryable' 	=> true,
		'exclude_from_search' 	=> false,
		'hierarchical' 			=> false, // Hierarchical causes memory issues - WP loads all records!
		'rewrite' 				=> array('slug' => 'company'),
		'query_var' 			=> true,
		'menu_position'      	=> 27,
		'supports' 				=> array('title', 'editor'),
		'show_in_nav_menus' 	=> true,
		'menu_icon' 			=> 'dashicons-building'
	)
);

$labels_company_types = array(
	'name'                       => _x( 'Company Type', 'taxonomy general name' ),
	'singular_name'              => _x( 'Company Type', 'taxonomy singular name' ),
	'search_items'               => __( 'Search Company Types' ),
	'popular_items'              => __( 'Popular Company Type' ),
	'all_items'                  => __( 'All Company Type' ),
	'parent_item'                => __( 'Parent Company Type' ),
	'parent_item_colon'          => __( 'Parent Company Type:' ),
	'edit_item'                  => __( 'Edit Company Type' ),
	'update_item'                => __( 'Update Company Type' ),
	'add_new_item'               => __( 'Add New Company Type' ),
	'new_item_name'              => __( 'New Company Type Name' ),
	'separate_items_with_commas' => __( 'Separate Company Type with commas' ),
	'add_or_remove_items'        => __( 'Add or remove Company Type' ),
	'choose_from_most_used'      => __( 'Choose from the most used Company Type' ),
	'not_found'                  => __( 'No Company Type found.' ),
	'menu_name'                  => __( 'Company Type' ),
);

$args_company_types = array(
	'hierarchical'          => true,
	'labels'                => $labels_company_types,	
	'show_ui'               => true,
	'show_admin_column'     => true,
	'update_count_callback' => '_update_post_term_count',
	'query_var'             => true,
	'rewrite'               => array( 'slug' => 'companytype' ),
);

register_taxonomy( 'companytype', 'company', $args_company_types );	

/**
 * Prospects CPT 
 */
$labels_company = array(
	'name' 					=> __( 'Prospects', 'YL' ),
	'singular_name' 		=> __( 'Prospect', 'YL' ),
	'menu_name'				=> _x( 'Prospects', 'Admin menu name', 'YL' ),
	'add_new' 				=> __( 'Add Prospect', 'YL' ),
	'add_new_item' 			=> __( 'Add New', 'YL' ),
	'edit' 					=> __( 'Edit', 'YL' ),
	'edit_item' 			=> __( 'Edit Prospect', 'YL' ),
	'new_item' 				=> __( 'New Prospect', 'YL' ),
	'view' 					=> __( 'View Prospect', 'YL' ),
	'view_item' 			=> __( 'View Prospect', 'YL' ),
	'search_items' 			=> __( 'Search Prospect', 'YL' ),
	'not_found' 			=> __( 'No Prospect found', 'YL' ),
	'not_found_in_trash' 	=> __( 'No Prospect found in trash', 'YL' ),
	'parent' 				=> __( 'Parent Prospect', 'YL' )
);
register_post_type('prospects', array('labels' => $labels_company,		
		'description' 			=> __( 'This is where you can add new Prospects to your site.', 'YL' ),
		'public' 				=> true,
		'show_ui' 				=> true,
		'capability_type' 		=> 'post',
		'map_meta_cap'			=> true,
		'publicly_queryable' 	=> true,
		'exclude_from_search' 	=> false,
		'hierarchical' 			=> false, // Hierarchical causes memory issues - WP loads all records!
		'rewrite' 				=> array('slug' => 'prospects'),
		'query_var' 			=> true,
		'menu_position'      	=> 27,
		'supports' 				=> array('title', 'editor'),
		'show_in_nav_menus' 	=> true,
		'menu_icon' 			=> 'dashicons-universal-access'
	)
);
