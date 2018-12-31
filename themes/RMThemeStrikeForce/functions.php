<?php


function latest_post_content_slider_shortcode($atts, $content = null) {
    extract(shortcode_atts(
        array('type' => 'flex',
			'post_type' => 'post',
			'number' => 5,
            'slideshow_speed' => 5000,
            'animation_speed' => 600,
            'animation' => 'fade',
            'pause_on_action' => 'false',
            'pause_on_hover' => 'true',
            'direction_nav' => 'true',
            'control_nav' => 'true',
			'image_size' => 'recent-works-thumbnail',
            'easing' => 'swing',
            'style' => ''),
        $atts));

    $output = '';

    $controls_container = $type . '-slider-container';
    $namespace = 'flex';

    $output .= '<script type="text/javascript">' . "\n";
    $output .= 'jQuery(document).ready(function($) {';
    $output .= 'jQuery(\'.' . $controls_container . ' .flexslider\'). flexslider({';
    $output .= 'animation: "' . $animation . '",';
    $output .= 'controlsContainer: "' . $controls_container . '",';
    $output .= 'slideshowSpeed: ' . $slideshow_speed . ',';
    $output .= 'animationSpeed: ' . $animation_speed . ',';
    $output .= 'namespace: "' . $namespace . '-",';
    $output .= 'pauseOnAction:' . $pause_on_action . ',';
    $output .= 'pauseOnHover: ' . $pause_on_hover . ',';
    $output .= 'controlNav: ' . $control_nav . ',';
    $output .= 'directionNav: ' . $direction_nav . ',';
    $output .= 'prevText: ' . '"' . '<span></span>",';
    $output .= 'nextText: ' . '"' . '<span></span>",';
	$output .= 'smoothHeight: false,';
    $output .= 'easing: "' . $easing . '"';
    $output .= '})';
    $output .= '});' . "\n";
    $output .= '</script>' . "\n";

    if (!empty($style))
        $style .= ' style="' . $style . '"';

    $output .= '<div class="' . $controls_container . ($type == "flex" ? ' loading' : '') . '"' . $style . '>';

    $output .= '<div class="flexslider latestpost_box">';

	$output .= '<ul class="slides">';
	wp_reset_query();
	$args = array(
		'post_type' => $post_type,
		'posts_per_page' => $number
	);

	$slider_posts = new WP_Query($args);
	global $post;
	if($slider_posts->have_posts()):
	 	while($slider_posts->have_posts()): $slider_posts->the_post();
			$output .= '<li><div class="slider_content_inner">';

			$output .= '<div><h3><a href=" '.get_permalink("").' ">'.get_the_title() .'</a></h3></div>';
			$output .= '<div>'. substr(get_the_excerpt(), 0,250) .'</div>';
			$output .= '<div class="readMore"><a href=" '.get_permalink("").' ">Read More </a></div>';

			$output .= '</div></li>';

		endwhile;
	endif;

	wp_reset_query();

	$output .= '</ul>';
    $output .= '</div><!-- flexslider -->';
    $output .= '</div><!-- ' . $controls_container . ' -->';

    return $output;
}
add_shortcode('latestpost_slider', 'latest_post_content_slider_shortcode');


add_action( 'rm_after_header','event_page_banner_init' );
function event_page_banner_init(){
    if ( is_active_sidebar('inner_page_banner_wid') && !is_front_page() && !is_page(673) && !is_page(679)  ) {
    echo '<div class="inner_page_banner">';
        dynamic_sidebar('inner_page_banner_wid');
    echo '</div>';
    }
    
    // if ( is_active_sidebar('partner_page_banner_wid') && is_page(679)  ) {
    // echo '<div class="inner_page_banner partnersBanner">';
    //     dynamic_sidebar('partner_page_banner_wid');
    // echo '</div>';
    // }

} //function


add_action( 'widgets_init', 'custom_widgets_init' );
function custom_widgets_init() {
    register_sidebar( array(
        'name'          => __( 'Inner Page Top Banner', 'RMTheme' ),
        'id'            => 'inner_page_banner_wid',
        'description'   => __( 'Appears in the Inner Page Top Banner section.', 'RMTheme' ),
        'before_widget' => '<aside id="%1$s" class="widget %2$s">',
        'after_widget'  => '</aside>',
        'before_title'  => '<h3 class="widget-title">',
        'after_title'   => '</h3>',
    ) );

    // register_sidebar( array(
    //     'name'          => __( 'Partner Page Top Banner', 'RMTheme' ),
    //     'id'            => 'partner_page_banner_wid',
    //     'description'   => __( 'Appears in the Partner Page Top Banner section.', 'RMTheme' ),
    //     'before_widget' => '<aside id="%1$s" class="widget %2$s">',
    //     'after_widget'  => '</aside>',
    //     'before_title'  => '<h3 class="widget-title">',
    //     'after_title'   => '</h3>',
    // ) );
} // function end
