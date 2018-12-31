<?php

// Let's start by registering a new taxonomy for Invoices
// Let's call it 'accounting categories'
add_action( 'init', '_yl_create_accounting_category_taxonomy' );
function _yl_create_accounting_category_taxonomy() {
	$labels = array(
		'name'              => _x( 'Accounting Categories', 'taxonomy general name', 'yl' ),
		'singular_name'     => _x( 'Accounting Category', 'taxonomy singular name', 'yl' ),
		'search_items'      => __( 'Search Accounting Categories', 'yl' ),
		'all_items'         => __( 'All Accounting Categories', 'yl' ),
		'parent_item'       => __( 'Parent Accounting Category', 'yl' ),
		'parent_item_colon' => __( 'Parent Accounting Category:', 'yl' ),
		'edit_item'         => __( 'Edit Accounting Category', 'yl' ),
		'update_item'       => __( 'Update Accounting Category', 'yl' ),
		'add_new_item'      => __( 'Add New Accounting Category', 'yl' ),
		'new_item_name'     => __( 'New Accounting Category Name', 'yl' ),
		'menu_name'         => __( 'Accounting Categories', 'yl' ),
	);

	$args = array(
		'hierarchical'      => true,
		'labels'            => $labels,
		'show_ui'           => true,
		'show_admin_column' => true,
		'query_var'         => true,
		'rewrite'           => array( 'slug' => 'acc_category' ),
	);

	register_taxonomy( 'acc_category', 'sa_invoice', $args );
}


// Hide the custom taxonomy metabox from the invoice edit page
function _yl_remove_custom_taxonomy_acc_category_metabox() {
	$custom_post_type = 'sa_invoice';
	// Lets remove it from both 'normal' and 'sidebar' locations.
	remove_meta_box( 'acc_categorydiv', $custom_post_type, 'normal' );
	remove_meta_box( 'acc_categorydiv', $custom_post_type, 'side' );
}
add_action( 'admin_menu', '_yl_remove_custom_taxonomy_acc_category_metabox' );

// Remove taxonomy from the invoices list
add_filter( 'manage_sa_invoice_posts_columns', '_yl_create_accounting_category_remove_taxonomy_from_list_items' );
function _yl_create_accounting_category_remove_taxonomy_from_list_items($columns) {
	unset($columns['taxonomy-acc_category']);
	return $columns;
}


// Apply some filters to invoice columns
// This filter 'si_line_item_columns' will allow us to edit the $columns array and
// include our custom column in there.
function _yl_invoice_line_item_accountant_cat_filter_callback( $columns, $type, $item_data, $position = 1, $prev_type = '', $has_children = false ) {

    $columns['accounting_cat'] = array(
    	'label' => __( 'Acc. Cat.', 'sprout-invoices' ),
		'type' => 'hidden',
		'placeholder' => '',
		'calc' => false,
		'numeric' => false,
		'weight' => 3,
    );
	
	$columns['suite_id'] = array(
    	'label' => __( 'Suite', 'sprout-invoices' ),
		'type' => 'hidden',
		'placeholder' => 'Select Suite',
		'calc' => false,
		'numeric' => false,
		'weight' => 3,
    );

	return $columns;
}
add_filter( 'si_line_item_columns', '_yl_invoice_line_item_accountant_cat_filter_callback', 10, 3 );


// Filter 'si_line_item_option' will let us change the output for our newly
// created column 'accounting_cat'. We populate this dropbox with the
// terms from the acc_category' taxonomy.
function _yl_invoice_line_item_accountant_cat_filter_option_callback($option, $column_slug, $item_data) {
	if ($column_slug == 'accounting_cat') {
		$terms = get_terms( 'acc_category', array(
		    'hide_empty' => false,
		) );

		$option = '<select name="line_item_accounting_cat[]">';
			$option .= '<option value="">No Category</option>';
		foreach ($terms as $term) {
			$option .= '<option value="'.$term->term_id.'" '.(($item_data['accounting_cat'] == $term->term_id) ? 'selected="selected"' : '').'>'.$term->name.'</option>';
		}
		$option .= '</select>';
	}
	if ($column_slug == 'suite_id') {
	
	 $request_invoice_id = isset($_REQUEST['post']) ? $_REQUEST['post']:'';
	
		$invoice_args = array(
			'post_type' => 'sa_invoice',
			'posts_per_page' => 1,
			'post__in' => array($request_invoice_id),
			'post_status' => 'any',
		
		);
		
		$invoce_results_data = get_posts($invoice_args);
		$suite_ids ='';
		foreach($invoce_results_data as $reskey => $resval){
		
			$invoice_id = isset($resval->ID) ? $resval->ID:'';
			
			$lease_id = get_post_meta($invoice_id, '_yl_lease_id', true);
			
			 $company_id = get_post_meta($lease_id, '_yl_company_id', true);
		
			if(!empty($company_id)){
			
				
				$suite_ids_arr = client_suite_ids_in($company_id);
			
			}
		}
		
		foreach($suite_ids_arr['suite_id'] as $sekey => $seval){
		
				$suite_ids = $seval;
				
		}

		$args = array(
				'posts_per_page' => -1,
				'post_type'=>'suites',
				'post__in' => $suite_ids,
				
				);
		$posts_array = get_posts( $args );
		//print_r($posts_array); exit();
		$option = '<select class="line_item_suite_id" name="line_item_suite_id[]">';
        $option .='<option value="">Select</option>';
		foreach($suite_ids as $member_suite){
				
			if($member_suite ==-1  || $member_suite =='' ){
			
				
			$option .= '<option value="-1" '.(($item_data['suite_id'] == -1) ? 'selected="selected"' : '').'>Y-Membership</option>';
			}
		}
		foreach ($posts_array as $post) {
			$option .= '<option value="'.$post->ID.'" '.(($item_data['suite_id'] == $post->ID) ? 'selected="selected"' : '').'>'.$post->post_title.'</option>';
		}
		$option .= '</select>';
	}
	return $option;
}
add_filter( 'si_line_item_option', '_yl_invoice_line_item_accountant_cat_filter_option_callback', 10, 3 );

add_action('admin_footer', 'add_select2_in_suite_dropdown');
function add_select2_in_suite_dropdown(){ 
	global $post;
	if(!empty($post) && $post->post_type == 'sa_invoice'){
	?>
	<script>
		jQuery(document).ready(function(){
			jQuery('.line_item_suite_id').select2();
			jQuery('.select2').css('height','30px');
		});
	</script>
<?php 
	}
}
// Function to get a selectbox populated with the current accounting categories
// and the selections from the 'lease settings' page.
function yl_account_categories_select_field($taxonomy = 'acc_category', $field_name = '1', $selected_page = '', $class = '') {
	$terms = get_terms( $taxonomy, array(
	    'hide_empty' => false,
	) );

	echo '<select name="'.$field_name.'" id="select_'.$taxonomy.'_'.$field_name.'" class="'.$class.'">';
	foreach ($terms as $term) { 
		$selected_value = (($term->term_id == $selected_page) ? 'selected="selected"' : '');
		echo '<option '.$selected_value.' value="'.$term->term_id.'">' . $term->name . '</option>'; 
	}
	echo '</select>';
}

global $yl_account_category_array;
add_action('init', 'yl_get_ready_account_category_array');
function yl_get_ready_account_category_array(){
	global $yl_account_category_array;	
	$terms = get_terms( 'acc_category', array(
		'hide_empty' => false,
	) );	
	foreach ($terms as $term) {
		$yl_account_category_array[$term->term_id] = $term->name;	
	}
	
	$blog_id = get_current_blog_id();
	$update_category_arr_option = 'account_category_array_'.$blog_id;	
	update_site_option( $update_category_arr_option, $yl_account_category_array );	
}

//@param word return category ID
function yl_account_category_id_by_wordmatch($search_string){
	global $yl_account_category_array;
	$result_term_id = 0;
	$default_result_term_id = 0;
	
	//Default category
	foreach($yl_account_category_array as $term_id => $term_name){
		if (strpos($term_name, 'Fees') !== false) {
			$default_result_term_id = $term_id;
		}
	}	
		
	foreach($yl_account_category_array as $term_id => $term_name){
		if (strpos($term_name, $search_string) !== false) {
			$result_term_id = $term_id;
		}
	}
	
	if($result_term_id == 0){
		$result_term_id = $default_result_term_id;
	}
	
	
	return $result_term_id;
}