<?php
require_once( dirname(__FILE__) . '/custom-post-meta.php');
require_once( dirname(__FILE__) . '/serise-image.php');
require_once( dirname(__FILE__) . '/shortcodes.php');

add_action('init', 'elevate_life_message_cpt');
function elevate_life_message_cpt(){
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
        'menu_icon' => 'dashicons-playlist-video',
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


    unset($labels);
    unset($args);
}

function elevateVideoType($url) {
    if (strpos($url, 'youtu') > 0) {
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

function elevate_get_youtube_id_from_url($url)  {
     preg_match('/(http(s|):|)\/\/(www\.|)yout(.*?)\/(embed\/|watch.*?v=|)([a-z_A-Z0-9\-]{11})/i', $url, $results);
     if(isset($results[6])){
       return $results[6];
     }else{
       return false;
     }
}

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

// https://digwp.com/2016/06/remove-toolbar-items/
function elevate_remove_toolbar_node($wp_admin_bar){
  $user = wp_get_current_user();
  if ( in_array( 'editor', (array) $user->roles ) ){
  	// replace 'updraft_admin_node' with your node id
  	$wp_admin_bar->remove_node('my-sites');
    $wp_admin_bar->remove_node('new-content');
    $wp_admin_bar->remove_node('tribe-events');
    $wp_admin_bar->remove_node('comments');
  }

  if ( in_array( 'elevate_message_editor', (array) $user->roles ) ){
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
