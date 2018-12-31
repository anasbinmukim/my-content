<?php
/*
Plugin Name: RM Responsive Post Slider
Plugin URI: http://rmweblab.com/
Description:
Author: RM Web Lab
Author URI: http://rmweblab.com/
*****/

add_action( 'wp_enqueue_scripts', 'my_custom_style_init' );
function my_custom_style_init() {
	wp_enqueue_style( 'flex-style', plugin_dir_url( __FILE__ ) . 'css/bxslider.css');
	wp_enqueue_style( 'slider-style', plugin_dir_url( __FILE__ ) . 'css/slider_style.css');
	wp_enqueue_script( 'flex-script', plugin_dir_url( __FILE__ ) . 'js/bxslider.min.js', array(), '3.4.5', true);
}


add_shortcode( 'display-rm-post-slider', 'rm_post_slider_shortcode' );
function rm_post_slider_shortcode() {

	$args_post = array(
		'post_type' => 'post',
		'posts_per_page' => '-1',
		'category_name' => 'slide',
        'order' => 'DESC',
        'orderby' => 'date',
		'ignore_sticky_posts' => 1
	);

	$query_post = new WP_Query( $args_post );

	?>
    	<script type="text/jscript">
			jQuery(window).load(function() {
			  jQuery('#rm_post_slider').bxSlider({
				mode: "fade",
				pager: false,
				nextText: "",
				prevText: "",
				speed: 800,
				pause: 8000,
				autoHover: true,
				auto: true,
				onSliderLoad: function(){
 				jQuery(".rm_post_slider_wrap").css("height", "auto");//.rm_post_slider_wrap{ height:400px; overflow:hidden;}
                 }
			  });
			});
		</script>
    <?php

	$output = '';
    if ( $query_post->have_posts() ):

		$output .='<div class="rm_post_slider_wrap">';
		$output .='<ul id="rm_post_slider">';
			global $post;
			 while ( $query_post->have_posts() ) : $query_post->the_post();
			 	 $output .='<li>';
				 if(has_post_thumbnail()){
                 $feature_thumb = wp_get_attachment_image_src(get_post_thumbnail_id(), 'slider-thumb');
				 $output .='<img src="'.$feature_thumb[0].'" title="'.get_the_title().'" />';
                  }
				 $output .='<h2 class="slider_post_title"><a href="'.get_permalink().'">'.get_the_title().'</a> <a href="'.get_permalink().'" class="slider_button">- Click to view -</a> </h2>';
				 $output .='</li>';
			 endwhile;
		$output .='</ul>';
		$output .='</div><!--rm_post_slider_wrap-->';
       endif;
	wp_reset_query();
	return $output;


}// End

add_image_size( 'slider-thumb', 1600, 650, true );
