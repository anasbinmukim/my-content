<?php
/*
Plugin Name: History Slider
Plugin URI: https://axissoftwaredynamics.com/
Description: Add Custom History slider and past [rmt_display_history] Shortcode any where.
Author: Axis Software Dynamics
Author URI: https://axissoftwaredynamics.com/
Version: 1.0.0
Text Domain: familylife
Domain Path: /languages
*****/

add_action( 'wp_enqueue_scripts', 'rmt_display_history_script_init' );
function rmt_display_history_script_init() {
	wp_register_style( 'flex-style', plugin_dir_url( __FILE__ ) . 'css/flexslider.css');
	wp_register_style( 'history-style', plugin_dir_url( __FILE__ ) . 'css/history.css');
	wp_register_script( 'flex-script', plugin_dir_url( __FILE__ ) . 'js/flexslider-min.js', array(), '2.7.2', true);
	wp_register_script('history-slider', plugin_dir_url( __FILE__ ) . 'js/history-slider.js');
}

add_action( 'init', 'rmt_register_history_slider_custompost_type' );
function rmt_register_history_slider_custompost_type() {
	$labels_history = array(
		'name' => _x('History Slider', 'History Slide', 'familylife'),
		'singular_name' => _x('Content', 'History type singular name', 'familylife'),
		'add_new' => _x('Add New', 'History', 'familylife'),
		'add_new_item' => __('Add New History', 'familylife'),
		'edit_item' => __('Edit History', 'familylife'),
		'new_item' => __('New History', 'familylife'),
		'view_item' => __('View History', 'familylife'),
		'search_items' => __('Search Histories', 'familylife'),
		'not_found' => __('No History Found', 'familylife'),
		'not_found_in_trash' => __('No History Found in Trash', 'familylife'),
		'parent_item_colon' => ''
	);

	register_post_type('history_slider', array('labels' => $labels_history,
			'public' => true,
			'show_ui' => true,
			'show_in_menu' => true,
			'capability_type' => 'post',
			'hierarchical' => false,
			'publicly_queryable' => true,
			'query_var' => true,
			'exclude_from_search' => false,
			'rewrite' => array('slug' => 'history'),
			'show_in_nav_menus' => false,
			'supports' => array('title',  'editor', 'page-attributes', 'revisions', 'thumbnail')
		)
	);
}


add_shortcode( 'rmt_display_history', 'rmt_get_history_slider_shortcode' );
function rmt_get_history_slider_shortcode($atts) {

	wp_enqueue_style('flex-style');
	wp_enqueue_style('history-style');
	wp_enqueue_script('flex-script');
	wp_enqueue_script('history-slider');

	$args_history = array(
		'post_type' => 'history_slider',
		'posts_per_page' => '-1',
		'orderby' => 'menu_order',
		'order' => 'ASC',
		'ignore_sticky_posts' => 1
	);

	$query_slide = new WP_Query( $args_history );

	$output_nav_item = '';
	$output = '';
	$slider_count = 0;
	if ( $query_slide->have_posts() ):
		$output .='<div class="history-slider">';
		$output .='<ul class="slides">';
		global $post;
		$slider_total_item = $query_slide->post_count;
		while ( $query_slide->have_posts() ) : $query_slide->the_post();
			$slider_count += 1;
			$output .='<li>';
			$output .='<div class="slider_cntent_wrap">';
			if(has_post_thumbnail()){
				$feature_img = wp_get_attachment_image_src(get_post_thumbnail_id(), 'full');
				$output .='<div class="history_slider_left_area"><img src="'.$feature_img[0].'" alt="'.get_the_title().'" /></div>';
			}
			$output .='<div class="history_slider_right_area">';
			$output .='<h4>'.get_the_title().'</h4>';
			$output .= '<div class="history_content">'.apply_filters( 'the_content', get_the_content()).'</div>';
			if($slider_count != $slider_total_item){
				$output .= '<a class="btn-view-next-year" href="javascript:void(0)">View Next Year &rarr;</a>';
			}
			$output .='</div>';
			$output .='</div>';
			$output .='</li>';
			$output_nav_item .= '<li>'.get_the_title().'</li>';
		endwhile;
		wp_reset_query();
		$output .='</ul>';
		$output .='</div><!--history-slider-->';
		$output .='<div class="flexslider-controls">';
		$output .='<ol class="flex-control-nav">';
		$output .= $output_nav_item;
		$output .='</ol>';
		$output .='</div><!--flexslider-controls-->';
	endif;
	return $output;
}// End
