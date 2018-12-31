<?php
// Pull the Parent Theme CSS
add_action( 'wp_enqueue_scripts', 'theme_enqueue_styles' );
function theme_enqueue_styles() {
    wp_enqueue_style( 'parent-style', get_template_directory_uri() . '/style.css' );

}

// Remove the "project" custom post type in Divi
if (!function_exists('unregister_post_type')) :
function unregister_post_type()
{
    global $wp_post_types;
    if (isset($wp_post_types['project']))
	{
        unset($wp_post_types['project']);
        return true;
    }
    return false;
}
endif;

add_action('init', 'unregister_post_type', 11);

function unregister_taxonomies(){
    register_taxonomy('project_category', array());
	register_taxonomy('project_tag', array());
}
add_action('init', 'unregister_taxonomies', 11);

?>