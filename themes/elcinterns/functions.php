<?php
// Pull the Parent Theme CSS
add_action( 'wp_enqueue_scripts', 'theme_enqueue_styles' );
function theme_enqueue_styles() {
  wp_enqueue_style( 'parent-style', get_template_directory_uri() . '/style.css' );
	wp_enqueue_style( 'font-awesome', '//maxcdn.bootstrapcdn.com/font-awesome/latest/css/font-awesome.min.css' );
	//wp_enqueue_style( 'flex-style', get_stylesheet_directory_uri() . '/inc/flexslider.css' );
  //wp_enqueue_script( 'flex-script', get_stylesheet_directory_uri() .  '/inc/flexslider-min.js', array(), '2.6.4', true );
}

add_shortcode('iframe', 'iframe_shortcode_init');
function iframe_shortcode_init($atts) {
   extract(shortcode_atts(array(
      'src' => "",
      'width' => "640",
      'height' => "480"
   ), $atts));

   $iframe = '<iframe src="'.$src.'" width="'.$width.'" height="'.$height.'" scrolling="no" allowtransparency="yes" frameborder="0" ></iframe>';

   return $iframe;
}


function elevate_inc_2018_dynamic_script() {
    ?>
    <script type="text/javascript">
      jQuery(document).ready(function () {
        var current_window_height = jQuery(window).height();
        //alert(current_window_height);
        //jQuery('#mobile_menu').height(current_window_height);
        //jQuery('#mobile_menu').css('height', current_window_height);
      });
    </script>
    <?php
}
add_action('wp_head', 'elevate_inc_2018_dynamic_script');
