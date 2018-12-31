<?php
$labels_tasks = array(
	'name' 					=> __( 'Tasks', 'STM' ),
	'singular_name' 		=> __( 'Task', 'STM' ),
	'menu_name'				=> _x( 'Tasks', 'Admin menu name', 'STM' ),
	'add_new' 				=> __( 'Add Task', 'STM' ),
	'add_new_item' 			=> __( 'Add New Task', 'STM' ),
	'edit' 					=> __( 'Edit', 'STM' ),
	'edit_item' 			=> __( 'Edit Task', 'STM' ),
	'new_item' 				=> __( 'New Task', 'STM' ),
	'view' 					=> __( 'View Task', 'STM' ),
	'view_item' 			=> __( 'View Task', 'STM' ),
	'search_items' 			=> __( 'Search Tasks', 'STM' ),
	'not_found' 			=> __( 'No Tasks found', 'STM' ),
	'not_found_in_trash' 	=> __( 'No Tasks found in trash', 'STM' ),
	'parent' 				=> __( 'Parent Tasks', 'STM' )
);

//register_post_type('tasks', array('labels' => $labels_tasks,		
//		'description' 			=> __( 'This is where you can add new Companies to your site.', 'STM' ),
//		'public' 				=> true,
//		'show_ui' 				=> true,
//		'capability_type' => 'post',
//		'map_meta_cap'			=> true,
//		'publicly_queryable' 	=> true,
//		'exclude_from_search' 	=> false,
//		'hierarchical' 			=> false, // Hierarchical causes memory issues - WP loads all records!
//		'rewrite' => array('slug' => 'tasks'),
//		'query_var' 			=> true,
//		'menu_position'      => 2,
//		'supports' 				=> array('title', 'editor', 'page-attributes', 'revisions'),
//		'show_in_nav_menus' 	=> true
//		//'menu_icon' => STM_ROOT . 'images/company.png'
//	)
//);		
//
//register_taxonomy( 'taskclient',
//	apply_filters( 'stm_taxonomy_objects_task', array( 'tasks' ) ),
//	apply_filters( 'stm_taxonomy_args_task', array(
//		'hierarchical' 			=> true,
//		'label' 				=> __( 'Clients', 'STM' ),
//		'labels' => array(
//				'name' 				=> __( 'Client', 'STM' ),
//				'singular_name' 	=> __( 'Client', 'STM' ),
//				'menu_name'			=> _x( 'Client', 'Admin menu name', 'STM' ),
//				'search_items' 		=> __( 'Search Client', 'STM' ),
//				'all_items' 		=> __( 'All Clients', 'STM' ),
//				'parent_item' 		=> __( 'Parent Client', 'STM' ),
//				'parent_item_colon' => __( 'Parent Client:', 'STM' ),
//				'edit_item' 		=> __( 'Edit Client', 'STM' ),
//				'update_item' 		=> __( 'Update Client', 'STM' ),
//				'add_new_item' 		=> __( 'Add New Client', 'STM' ),
//				'new_item_name' 	=> __( 'New Client Name', 'STM' )
//			),
//		'show_ui' 				=> true,
//		'show_admin_column'     => true,
//		'query_var' 			=> true,
//		'rewrite' => array( 'slug' => 'task-client' ),
//	) )
//);		


$labels_timesheet = array(
	'name' 					=> __( 'Work Schedule', 'STM' ),
	'singular_name' 		=> __( 'Work Schedule', 'STM' ),
	'menu_name'				=> _x( 'Work Schedule', 'Admin menu name', 'STM' ),
	'add_new' 				=> __( 'Add Work Schedule', 'STM' ),
	'add_new_item' 			=> __( 'Add New Work Schedule', 'STM' ),
	'edit' 					=> __( 'Edit', 'STM' ),
	'edit_item' 			=> __( 'Edit Work Schedule', 'STM' ),
	'new_item' 				=> __( 'New Work Schedule', 'STM' ),
	'view' 					=> __( 'View Work Schedule', 'STM' ),
	'view_item' 			=> __( 'View Work Schedule', 'STM' ),
	'search_items' 			=> __( 'Search Work Schedule', 'STM' ),
	'not_found' 			=> __( 'No Work Schedule found', 'STM' ),
	'not_found_in_trash' 	=> __( 'No Work Schedule found in trash', 'STM' ),
	'parent' 				=> __( 'Parent Work Schedule', 'STM' )
);
register_post_type('timesheet', array('labels' => $labels_timesheet,		
		'description' 			=> __( 'This is where you can add new Companies to your site.', 'STM' ),
		'public' 				=> true,
		'show_ui' 				=> true,
		'capability_type' => 'timesheet_post',
		'map_meta_cap'			=> true,
		'publicly_queryable' 	=> true,
		'exclude_from_search' 	=> false,
		'hierarchical' 			=> false, // Hierarchical causes memory issues - WP loads all records!
		'rewrite' => array('slug' => 'timesheet'),
		'query_var' 			=> true,
		'menu_position'      => 2,
		'supports' 				=> array('title', 'custom-fields'),
		'show_in_nav_menus' 	=> true
		//'menu_icon' => STM_ROOT . 'images/company.png'
	)
);	

$labels_status = array(
	'name'                       => _x( 'Status', 'taxonomy general name' ),
	'singular_name'              => _x( 'Status', 'taxonomy singular name' ),
	'search_items'               => __( 'Search Status' ),
	'popular_items'              => __( 'Popular Status' ),
	'all_items'                  => __( 'All Status' ),
	'parent_item'                => __( 'Parent Status' ),
	'parent_item_colon'          => __( 'Parent Status:' ),
	'edit_item'                  => __( 'Edit Status' ),
	'update_item'                => __( 'Update Status' ),
	'add_new_item'               => __( 'Add New Status' ),
	'new_item_name'              => __( 'New Status Name' ),
	'separate_items_with_commas' => __( 'Separate authors with commas' ),
	'add_or_remove_items'        => __( 'Add or remove author' ),
	'choose_from_most_used'      => __( 'Choose from the most used authors' ),
	'not_found'                  => __( 'No tags found.' ),
	'menu_name'                  => __( 'Status' ),
);

$args_status = array(
	'hierarchical'          => true,
	'labels'                => $labels_status,	
	'show_ui'               => true,
	'show_admin_column'     => true,
	'update_count_callback' => '_update_post_term_count',
	'meta_box_cb'                => 'stm_drop_style_status',
	'query_var'             => true,
	'rewrite'               => array( 'slug' => 'timesheet-status' ),
);

register_taxonomy( 'timesheet_status', 'timesheet', $args_status );	


function stm_drop_style_status( $post, $box ) {
	global $post;
	if(get_post_meta( $post->ID, 'unassigned_task', true ))
		return;		

    $defaults = array( 'taxonomy' => 'timesheet_status' );
    if ( ! isset( $box['args'] ) || ! is_array( $box['args'] ) ) {
        $args = array();
    } else {
        $args = $box['args'];
    }
    $r = wp_parse_args( $args, $defaults );
    $tax_name = esc_attr( $r['taxonomy'] );
    $taxonomy = get_taxonomy( $r['taxonomy'] );
    ?>
    <div id="taxonomy-<?php echo $tax_name; ?>" class="categorydiv">

    <?php //took out tabs for most recent here ?>

        <div id="<?php echo $tax_name; ?>-all">
            <?php
            $name = ( $tax_name == 'category' ) ? 'post_category' : 'tax_input[' . $tax_name . ']';
            echo "<input type='hidden' name='{$name}[]' value='0' />"; // Allows for an empty term set to be sent. 0 is an invalid Term ID and will be ignored by empty() checks.
            ?>
           <?php $term_obj = wp_get_object_terms($post->ID, $tax_name ); //_log($term_obj[0]->term_id) ?>
            <ul id="<?php echo $tax_name; ?>checklist" data-wp-lists="list:<?php echo $tax_name; ?>" class="categorychecklist form-no-clear">
                <?php //wp_terms_checklist( $post->ID, array( 'taxonomy' => $tax_name, 'popular_cats' => $popular_ids ) ); ?>
                <li id="timesheet_status-16" class="popular-category">
                <label class="selectit"><input value="16" name="tax_input[timesheet_status][]" id="in-timesheet_status-16" type="checkbox" <?php echo $term_obj[0]->term_id == 16?'checked':'';?> > Complete</label></li>
            </ul>

           
            <?php //wp_dropdown_categories( array( 'taxonomy' => $tax_name, 'hide_empty' => 0, 'name' => "{$name}[]", 'selected' => $term_obj[0]->term_id, 'orderby' => 'name', 'hierarchical' => 0 ) ); ?>

        </div>
    <?php if ( current_user_can( $taxonomy->cap->edit_terms ) ) : 
            // removed code to add terms here dynamically, because doing so added a checkbox above the newly added drop menu, the drop menu would need to be re-rendered dynamically to display the newly added term ?>
        <?php endif; ?>

        <!--<p><a href="<?php echo site_url(); ?>/wp-admin/edit-tags.php?taxonomy=<?php echo $tax_name ?>&post_type=timesheet">Add New</a></p>-->
    </div>
    <?php 
}


//Add custom capibility for quote_manager
function stm_timesheet_caps() {

		// Add the roles you'd like to administer the custom post types
		$roles = array('employee', 'contractor', 'team_leader','administrator', 'project_manager');
		
		// Loop through each role and assign capabilities
		foreach($roles as $the_role) { 

		     $role = get_role($the_role);
			
	             $role->add_cap( 'read' );
	             $role->add_cap( 'read_timesheet_post');
	             $role->add_cap( 'read_private_timesheet_posts' );
	             $role->add_cap( 'edit_timesheet_post' );
	             $role->add_cap( 'edit_timesheet_posts' );
	             $role->add_cap( 'edit_others_timesheet_posts' );
	             $role->add_cap( 'edit_published_timesheet_posts' );
	             $role->add_cap( 'publish_timesheet_posts' );
	             $role->add_cap( 'delete_others_timesheet_posts' );
	             $role->add_cap( 'delete_private_timesheet_posts' );
	             $role->add_cap( 'delete_published_timesheet_posts' );
	}
}
add_action( 'admin_init', 'stm_timesheet_caps');



add_action('admin_head', 'stm_admin_css');

function stm_admin_css() {
	global $post_type; 
	if (($_GET['post_type'] == 'timesheet') || ($post_type == 'timesheet')) :
		echo "<link type='text/css' rel='stylesheet' href='" . plugins_url('/timesheetadmin.css', __FILE__) . "' />";
	endif;

}