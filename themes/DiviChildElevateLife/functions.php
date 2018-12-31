<?php
function elevate_hook_css() {
    $user = wp_get_current_user();
    if ( in_array( 'editor', (array) $user->roles ) ) {
    ?>
        <style>
          #wp-admin-bar-popup-maker, #wp-admin-bar-tribe-events{ display: none; }
        </style>
    <?php
   }
   if ( in_array( 'elevate_message_editor', (array) $user->roles ) ) {
   ?>
       <style>
         #wp-admin-bar-popup-maker, #wp-admin-bar-tribe-events, #wp-admin-bar-new-content, #wp-admin-bar-my-sites, #wp-admin-bar-new-post, #wp-admin-bar-new-popup, #wp-admin-bar-new-news{ display: none; }
       </style>
   <?php
  }
}
add_action('wp_head', 'elevate_hook_css');
add_action('admin_head', 'elevate_hook_css' );

function custom_enqueue_styles() {
    wp_enqueue_style( 'parent-style', get_template_directory_uri() . '/style.css' );
    wp_enqueue_style( 'font-awesome', '//maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css' );
    wp_enqueue_script( 'theme-custom-script', get_stylesheet_directory_uri() . '/custom.js', array(), '', false );
    wp_enqueue_script( 'map-custom-script', get_stylesheet_directory_uri() . '/footer-map.js', array(), '', true );
    wp_enqueue_script( 'map-script', 'https://maps.googleapis.com/maps/api/js?key=AIzaSyCTK1MGD3Z31zXRJbg-Xpk1wLj4W1RK-dg&callback=initElevateMap', array(), '', true );
}
add_action( 'wp_enqueue_scripts', 'custom_enqueue_styles' );

function rmweb_admin_styles() {
  wp_enqueue_style( 'jquery-ui-datepicker-style' , '//ajax.googleapis.com/ajax/libs/jqueryui/1.10.4/themes/smoothness/jquery-ui.css');
}
add_action('admin_print_styles', 'rmweb_admin_styles');
function rmweb_admin_scripts() {
  wp_enqueue_script( 'jquery-ui-datepicker' );
  wp_enqueue_script( 'wp-jquery-date-picker', get_stylesheet_directory_uri() . '/admin-custom.js' );
}
add_action('admin_enqueue_scripts', 'rmweb_admin_scripts');


require_once( dirname(__FILE__) . '/custom-post-meta.php');
require_once( dirname(__FILE__) . '/serise-image.php');
require_once( dirname(__FILE__) . '/shortcodes.php');

function elevate_life_widgets_init() {
	register_sidebar( array(
		'name'          => __( 'Header Top Right', 'Divi' ),
		'id'            => 'header-top-right',
		'description'   => __( 'Appears in header Right section.', 'Divi' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h3 class="widget-title">',
		'after_title'   => '</h3>',
	) );

} // FOR Home page Banner end
add_action( 'widgets_init', 'elevate_life_widgets_init' );

add_action('init', 'elevate_life_cpt');
function elevate_life_cpt() {
    $labels = array(
        'name' => 'Messages',
        'singular_name' => 'Message',
        'add_new' => 'Add New',
        'add_new_item' => 'Add New Message',
        'edit_item' => 'Edit Message',
        'new_item' => 'New Message',
        'all_items' => 'All Messages',
        'view_item' => 'View Message',
        'search_items' => 'Search Messages',
        'not_found' => 'No Messages found',
        'not_found_in_trash' => 'No Messages found in Trash',
        'parent_item_colon' => '',
        'menu_name' => 'Messages'
    );
    $args = array(
        'labels' => $labels,
        'public' => true,
        'publicly_queryable' => true,
        'show_ui' => true,
        'show_in_menu' => true,
        'query_var' => true,
        'rewrite' => array('slug' => 'message', 'with_front' => false),
        //Adding custom rewrite tag
        'capability_type' => array('message_post','message_posts'),
        'map_meta_cap' => true,
        'hierarchical' => false,
        'menu_position' => null,
        'supports' => array('title', 'editor', 'author', 'thumbnail', 'excerpt'),
    );
    register_post_type('message', $args);


    $labels = array(
        'name' => 'Series',
        'singular_name' => 'Series',
        'search_items' => 'Search Series',
        'all_items' => 'All Series',
        'parent_item' => 'Parent Series',
        'parent_item_colon' => 'Parent Series:',
        'edit_item' => 'Edit Series',
        'update_item' => 'Update Series',
        'add_new_item' => 'Add New Series',
        'new_item_name' => 'New Series',
    );

    $args = array(
        'hierarchical' => true,
        'rewrite' => array('slug' => 'series'),
        'show_in_nav_menus' => true,
        'show_admin_column'     => true,
        'update_count_callback' => '_update_post_term_count',
        'labels' => $labels
    );

    register_taxonomy('message_series', 'message', $args);

    $labels = array(
        'name' => 'News',
        'singular_name' => 'News',
        'add_new' => 'Add New',
        'add_new_item' => 'Add New News',
        'edit_item' => 'Edit News',
        'new_item' => 'New News',
        'all_items' => 'All News',
        'view_item' => 'View News',
        'search_items' => 'Search News',
        'not_found' => 'No News found',
        'not_found_in_trash' => 'No News found in Trash',
        'parent_item_colon' => '',
        'menu_name' => 'News'
    );
    $args = array(
        'labels' => $labels,
        'public' => true,
        'publicly_queryable' => true,
        'show_ui' => true,
        'show_in_menu' => true,
        'query_var' => true,
        'rewrite' => array('slug' => 'news', 'with_front' => false),
        //Adding custom rewrite tag
        'capability_type' => 'post',
        'hierarchical' => false,
        'menu_position' => null,
        'supports' => array('title', 'editor', 'author', 'thumbnail', 'excerpt'),
    );
    register_post_type('news', $args);


    unset($labels);
    unset($args);
}

function elevateVideoType($url) {
    if (strpos($url, 'youtube') > 0) {
        return 'youtube';
    } elseif (strpos($url, 'vimeo') > 0) {
        return 'vimeo';
    } else {
        return 'unknown';
    }
}

function getVimeoThumb($id)
{
    $thumb = array();
    $vimeo = unserialize(file_get_contents("https://vimeo.com/api/v2/video/$id.php"));
    $thumb['small'] = $vimeo[0]['thumbnail_small'];
    $thumb['medium'] = $vimeo[0]['thumbnail_medium'];
    $thumb['large'] = $vimeo[0]['thumbnail_large'];
    return $thumb;
}

//Remove backend projects menu
add_filter( 'et_project_posttype_args', 'elevate_et_project_posttype_args', 10, 1 );
function elevate_et_project_posttype_args( $args ) {
	return array_merge( $args, array(
		'public'              => false,
		'exclude_from_search' => false,
		'publicly_queryable'  => false,
		'show_in_nav_menus'   => false,
		'show_ui'             => false
	));
}

add_filter('body_class', 'single_event_body_classes');
function single_event_body_classes($classes) {
      if(is_singular( 'tribe_events' )){
        $classes[] = 'et_pb_gutters3';
        return $classes;
      }
}

//Remove post from backend
function elevate_remove_menus(){
  remove_menu_page( 'edit.php' );                   //Posts
  $user = wp_get_current_user();
  if ( in_array( 'editor', (array) $user->roles ) ) {
      //The user is staff
      //remove_menu_page( 'index.php' );                  //Dashboard
      // remove_menu_page( 'jetpack' );                    //Jetpack*
      // remove_menu_page( 'edit.php' );                   //Posts
      remove_menu_page( 'upload.php' );                 //Media
      // remove_menu_page( 'edit.php?post_type=page' );    //Pages
      remove_menu_page( 'edit-comments.php' );          //Comments
      remove_menu_page( 'themes.php' );                 //Appearance
      remove_menu_page( 'plugins.php' );                //Plugins
      remove_menu_page( 'users.php' );                  //Users
      remove_menu_page( 'tools.php' );                  //Tools
      remove_menu_page( 'options-general.php' );        //Settings

      remove_menu_page( 'et_divi_options' );
      remove_menu_page( 'maxmegamenu' );
      remove_menu_page( 'edit.php?post_type=popup' );
      remove_menu_page( 'gf_edit_forms' );
      remove_menu_page( 'wpengine-common' );

      remove_menu_page( 'wp_stream' );

      //remove_menu_page( 'edit.php?post_type=message' );

  }

  if ( in_array( 'elevate_message_editor', (array) $user->roles ) ) {
    remove_menu_page( 'edit-comments.php' );          //Comments
    remove_menu_page( 'themes.php' );                 //Appearance
    remove_menu_page( 'edit.php?post_type=popup' );
    remove_menu_page( 'tools.php' );                  //Tools
    remove_menu_page( 'edit.php?post_type=news' );
  }

}
add_action( 'admin_init', 'elevate_remove_menus' );

function change_elevate_role_name() {
    global $wp_roles;
    if ( ! isset( $wp_roles ) )
        $wp_roles = new WP_Roles();

    //You can replace "administrator" with any other role "editor", "author", "contributor" or "subscriber"...
    $wp_roles->roles['editor']['name'] = 'Stuff';
    $wp_roles->role_names['editor'] = 'Stuff';

}
add_action('admin_init', 'change_elevate_role_name');

//Add custom capibility for message manager
function elevate_message_manager_caps() {

		// Add the roles you'd like to administer the custom post types
		$roles = array('elevate_message_editor','editor','administrator');

		// Loop through each role and assign capabilities
		foreach($roles as $the_role) {

		     $role = get_role($the_role);

	             $role->add_cap( 'read' );
	             $role->add_cap( 'read_message_post');
	             $role->add_cap( 'read_private_message_posts' );
	             $role->add_cap( 'edit_message_post' );
	             $role->add_cap( 'edit_message_posts' );
	             $role->add_cap( 'edit_others_message_posts' );
	             $role->add_cap( 'edit_published_message_posts' );
	             $role->add_cap( 'publish_message_posts' );
	             $role->add_cap( 'delete_others_message_posts' );
	             $role->add_cap( 'delete_private_message_posts' );
	             $role->add_cap( 'delete_published_message_posts' );
	}
}
add_action( 'admin_init', 'elevate_message_manager_caps');


// remove toolbar items
// https://digwp.com/2016/06/remove-toolbar-items/
function elevate_remove_toolbar_node($wp_admin_bar) {
  $user = wp_get_current_user();
  if ( in_array( 'editor', (array) $user->roles ) ) {
  	// replace 'updraft_admin_node' with your node id
  	$wp_admin_bar->remove_node('my-sites');
    $wp_admin_bar->remove_node('new-content');
    $wp_admin_bar->remove_node('tribe-events');
    $wp_admin_bar->remove_node('comments');

  }

  if ( in_array( 'elevate_message_editor', (array) $user->roles ) ) {
    $args = array(
      'id'    => 'manage_message_post',
      'title' => 'My Message',
      'href'  => '/wp-admin/edit.php?post_type=message',
      'meta'  => array( 'class' => 'my-message-toolbar-page' )
    );
    $wp_admin_bar->add_node( $args );
  }


}
add_action('admin_bar_menu', 'elevate_remove_toolbar_node', 999);


//No need anymore, already created..
function add_user_role_elevate_life(){
	if(isset($_GET['addrole']) && ($_GET['addrole'] == 'do')){
		//add_role( 'staff', 'Staff', array( 'level_7' => true ) );
    add_role( 'elevate_message_editor', 'Message Editor', array( 'edit_posts' => true, 'edit_others_posts' => true, 'level_7' => true ) );
		//remove_role( 'elevate_message_editor' );
	}
}
//add_action( 'init', 'add_user_role_elevate_life' );
