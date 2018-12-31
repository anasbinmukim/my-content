<?php
define( 'WPE_CACHE_FLUSH', 'KToQV7e9MSCM4lVY9H7f' );
date_default_timezone_set("America/Chicago");
include('elevate-team.php');
include('elevate-message.php');
include('class-ccbpress-upcoming-events-shortcode.php');
// Pull the Parent Theme CSS
add_action( 'wp_enqueue_scripts', 'theme_enqueue_styles' );
function theme_enqueue_styles() {
  wp_enqueue_style( 'parent-style', get_template_directory_uri() . '/style.css' );
	wp_enqueue_style( 'font-awesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css' );
	if(is_front_page()):
		  wp_enqueue_style( 'flex-style', get_stylesheet_directory_uri() . '/inc/flexslider.css' );
		  wp_enqueue_script( 'flex-script', get_stylesheet_directory_uri() .  '/inc/flexslider-min.js', array(), '2.6.4', true );
	endif;

  $dependencies_array = array( 'jquery' );
  $theme_version = et_get_theme_version();
  wp_enqueue_script( 'divi-custom-script', get_stylesheet_directory_uri() . '/js/custom.min.js', $dependencies_array , $theme_version, true );

}

add_shortcode( 'font-awesome', 'font_awesome_shortcode_init' );
function font_awesome_shortcode_init($atts) {
  $args = shortcode_atts(array(
    'icon_class' => ''
  ), $atts);
  extract($args);
  $icon_class = esc_attr($icon_class);
  return '<span class="fa '.$icon_class.'"></span>';
}

function elevate_life2018_dynamic_script() {
    ?>
    <style type="text/css">
      <?php if(get_option('enable_watch_live') || is_watch_live_on()){ ?>
        .watch_live.menu-item{ display: block !important; }
      <?php }else{ ?>
        .watch_live.menu-item{ display: none !important; }
      <?php } ?>


      /*** Take out the divider line between content and sidebar from all place except single lesson page ***/
      #main-content .container:before {background: none;}
      /*** Hide Sidebar ***/
      #sidebar {display:none;}

      /*** Expand the content area to fullwidth ***/
      @media (min-width: 981px){
        #left-area {
            width: 100%;
            padding: 23px 0px 0px !important;
            float: none !important;
        }
      }

    </style>
    <?php echo '<script type="text/javascript">
           var ajaxurl = "' . admin_url('admin-ajax.php') . '";
         </script>';
    ?>
    <script type="text/javascript">
      jQuery(document).ready(function($) {
          var elife_home_url = '<?php echo home_url(); ?>';
          $( ".et-search-field" ).keyup(function() {
              var search_request_val = $(this).val();
              var search_request_value = $.trim(search_request_val);

              if ((search_request_value == '')) {
                $( ".display_elev_live_search_result" ).html('');
                $( ".display_elev_live_search_result" ).hide();
              }else{
                var dataPush = {
                  search_val: search_request_value,
                  action: 'elevate_live_search_callback'
                }
                $.ajax({
                  action: "elevate_live_search_callback",
                  type: "GET",
                  dataType: "json",
                  url: ajaxurl,
                  data: dataPush,
                  success: function(data){
                    if(data.status == true){
                        $( ".display_elev_live_search_result" ).show();
                        $( ".display_elev_live_search_result" ).html(data.result);
                    }else{
                        $( ".display_elev_live_search_result" ).html('');
                        $( ".display_elev_live_search_result" ).hide();
                    }
                  }
                });
              }
          });
      });
    </script>
    <style type="text/css">
      #main-header .et_mobile_menu .menu-item-has-children > a { background-color: transparent; position: relative; }
      #main-header .et_mobile_menu .menu-item-has-children > a:after { font-family: 'ETmodules'; text-align: center; speak: none; font-weight: normal; font-variant: normal; text-transform: none; -webkit-font-smoothing: antialiased; position: absolute; }
      #main-header .et_mobile_menu .menu-item-has-children > a:after { font-size: 16px; content: '\4c'; top: 13px; right: 10px; }
      #main-header .et_mobile_menu .menu-item-has-children.visible > a:after { content: '\4d'; }
      #main-header .et_mobile_menu ul.sub-menu { display: none !important; visibility: hidden !important;  transition: all 1.5s ease-in-out;}
      #main-header .et_mobile_menu .visible > ul.sub-menu { display: block !important; visibility: visible !important; }
    </style>
    <script type="text/javascript">
    (function($) {
        function setup_collapsible_submenus() {
            var $menu = $('#mobile_menu'),
                top_level_link = '#mobile_menu .menu-item-has-children > a';

            $menu.find('a').each(function() {
                $(this).off('click');

                if ( $(this).is(top_level_link) ) {
                    $(this).attr('href', '#');
                }

                if ( ! $(this).siblings('.sub-menu').length ) {
                    $(this).on('click', function(event) {
                        $(this).parents('.mobile_nav').trigger('click');
                    });
                } else {
                    $(this).on('click', function(event) {
                        event.preventDefault();
                        $(this).parent().toggleClass('visible');
                    });
                }
            });
        }

        $(window).load(function() {
            setTimeout(function() {
                setup_collapsible_submenus();
            }, 700);
        });

    })(jQuery);
    </script>

    <script type="text/javascript">
  		jQuery(document).ready(function () {
  		  jQuery('#mobile_menu').css({ minHeight: jQuery(window).height() + 'px' });
  		  jQuery(window).resize(function() {
  			     jQuery('#mobile_menu').css({ minHeight: jQuery(window).height() + 'px' });
  		  });
  		});
  	</script>

    <style type="text/css">
    .elevate-show-more{}
    .elevate-display-show-more{ display: none; }
    </style>
    <script type="text/javascript">
    	jQuery(document).ready(function($) {
    			jQuery('.elevate-show-more').click(function (e) {
    				e.preventDefault();
            jQuery('.elevate-display-show-more').slideToggle( "slow");
            var current_text = $(this).text();
            if(current_text == 'More'){
              $(this).text('Less');
            }else{
              $(this).text('More');
            }
    			});


          jQuery('.select_pushpay_location').on('change', function(){
             window.location = jQuery(this).val();
          });
    	});
    </script>

    <?php
}
add_action('wp_head', 'elevate_life2018_dynamic_script');


/*******************************
 * live_search_callback:
*******************************/
add_action('wp_ajax_elevate_live_search_callback', 'elevate_live_search_callback_inc');
add_action('wp_ajax_nopriv_elevate_live_search_callback', 'elevate_live_search_callback_inc');
if (!function_exists('elevate_live_search_callback_inc')) {
  function elevate_live_search_callback_inc() {
     $search_str = sanitize_text_field( $_GET['search_val'] ); // Get the ajax call
     $search_str = trim($search_str);
     $result_str = '';
     $feedback = array();
     $saerch_query = new WP_Query( array( 's' => $search_str, 'posts_per_page' => 20 ) );

      if ( $saerch_query->have_posts() ) {
        $result_str .= '<ul class="live_search_items">';
      	while ( $saerch_query->have_posts() ) {
      		$saerch_query->the_post();
          $featured_image = '';
          $item_excerpt = '';
          $result_str .= '<li><a href="'.get_permalink().'">'.get_the_title().'</a></li>';
      	}
        $result_str .= '</ul>';
      	/* Restore original Post Data */
      	wp_reset_postdata();
        $feedback = array("result" => $result_str, "status" => true);
      } else {
      	// no posts found
        $feedback = array("result" => $result_str, "status" => false);
      }
     echo json_encode($feedback);
     die();

  }
}

function elevate_admin_menu_page_removing(){
  remove_menu_page( 'edit.php?post_type=project' );
}

add_action( 'admin_menu', 'elevate_admin_menu_page_removing' );

add_shortcode( 'watch_live_link', 'el_watch_live_url_shortcode_init' );
function el_watch_live_url_shortcode_init($atts) {
  $live_url = get_option('watch_live_url');
  $live_url = str_replace("https://", "", $live_url);
  return $live_url;
}

add_shortcode( 'watch_live_or_message_link', 'el_watch_live_or_message_url_shortcode_init' );
function el_watch_live_or_message_url_shortcode_init($atts) {

  // $search = array('https://', 'http://');
  // $replace = array('', '');
  //
  // $live_url = get_option('watch_live_url');
  // //$live_url = str_replace("https://", "", $live_url);
  // $live_url = str_replace($search, $replace, $live_url);
  //
  // $watch_now_url = get_option('watch_now_url');
  // //$watch_now_url = str_replace("https://", "", $watch_now_url);
  // $watch_now_url = str_replace($search, $replace, $watch_now_url);

  if(get_option('enable_watch_live') || is_watch_live_on()){
    $live_url = get_option('watch_live_url');
    $live_url = str_replace("https://", "", $live_url);
    return $live_url;
  }else{
    $watch_now_url = get_option('watch_now_url');
    $watch_now_url = str_replace("https://", "", $watch_now_url);
    return $watch_now_url;
  }
}

//add_shortcode( 'display-events', 'events_slider_shortcode_init' );
function events_slider_shortcode_init($atts) {
	$args_event = array(
		'post_type' => 'tribe_events',
		'posts_per_page' => '-1',
		'eventDisplay' => 'upcoming',
	);

	$query_event = new WP_Query( $args_event );

		$output = '';
		$output .='<script type="text/javascript">';
		$output .='jQuery(document).ready(function(){';
		$output .='jQuery(".event_slider").flexslider({';
		$output .='nextText: "Next",';
		$output .='prevText: "Previous",';
		$output .= 'controlNav: false,';
		$output .= 'slideshow: false,';
		$output .= 'animation: "slide",';
		$output .='})';
		$output .='})';
		$output .='</script>';

    if ( $query_event->have_posts() ):
		$output .='<div class="event_slider et_upcoming_events">';
		$output .='<ul class="slides">';
			 while ( $query_event->have_posts() ) : $query_event->the_post();
			 	$event_id = get_the_ID();
				$event_start_date = tribe_get_start_date($event_id);
				$event_start_time = tribe_get_start_time($event_id);
				$event_end_time = tribe_get_end_time($event_id);
			 	$output .= '<li>';
				$output .= '<h2 class="event_title"><a href="'.get_permalink().'" title="'.get_the_title().'">'.get_the_title().'</a></h2>';
				$output .= '<div class="event_date">'.$event_start_date.' - '.$event_end_time.'</div>';
				$output .= '<div class="event_entry_content">'.wp_trim_words( get_the_excerpt(), 55 ).'</div>';
				$output .= '</li>';
			 endwhile;
		$output .='</ul>';
		$output .='</div><!--testimonial_post_slider-->';
       endif;
	wp_reset_query();
	return $output;

}// End


//add_shortcode( 'display-events-slider', 'display_events_slider_shortcode_init' );
function display_events_slider_shortcode_init($atts) {
	$args_event = array(
		'post_type' => 'tribe_events',
		'posts_per_page' => '-1',
		'eventDisplay' => 'upcoming',
	);

	$query_event = new WP_Query( $args_event );

		$output = '';
		$output .='<script type="text/javascript">';
		$output .='jQuery(document).ready(function(){';
		$output .='jQuery(".event_slider").flexslider({';
		$output .='nextText: "",';
		$output .='prevText: "",';
		$output .= 'controlNav: false,';
		$output .= 'slideshow: false,';
		$output .='})';
		$output .='})';
		$output .='</script>';

    if ( $query_event->have_posts() ):
		$output .='<div class="event_slider et_upcoming_events">';
		$output .='<ul class="slides">';
			 while ( $query_event->have_posts() ) : $query_event->the_post();
			 	$event_id = get_the_ID();
				$event_start_date = tribe_get_start_date($event_id);
				$event_start_time = tribe_get_start_time($event_id);
				$event_end_time = tribe_get_end_time($event_id);
			 	$output .= '<li>';
				$output .= '<div class="et_pb_row et_section_events et_pb_equal_columns et_pb_gutters1">';

        $background_image = '';
        $output_photo = '';
        if(has_post_thumbnail()){
            $feature_thumb = wp_get_attachment_image_src(get_post_thumbnail_id(), 'large');
            $background_image = 'background-image:url('.$feature_thumb[0].');';
            $output_photo ='<a href="'.get_permalink().'"><img class="aligncenter event_slider_photo" src="'.$feature_thumb[0].'" alt="'.get_the_title().'" /></a>';
        }

				$output .='<div style="'.$background_image.' background-size: cover;"; class="et_pb_column colum_with_background et_pb_column_1_2">';
        $output .= $output_photo;
				$output .='</div><!--et_pb_column-->';


				$output .='<div class="et_pb_column colum_no_background et_pb_column_1_2 et-last-child">';
				$output .= '<h2 class="event_title"><a href="'.get_permalink().'" title="'.get_the_title().'">'.get_the_title().'</a></h2>';
				$output .= '<div class="event_date">'.$event_start_date.' - '.$event_end_time.'</div>';
				$output .= '<div class="event_entry_content">'.wp_trim_words( get_the_excerpt(), 55 ).'</div>';
				$output .='</div><!--et_pb_column-->';
				$output .='</div><!--et_pb_row-->';
				$output .= '</li>';
			 endwhile;
		$output .='</ul>';
		$output .='</div><!--testimonial_post_slider-->';
       endif;
	wp_reset_query();
	return $output;

}// End


add_action( 'init', 'register_elevate_stories_custompost_type' );
function register_elevate_stories_custompost_type() {
	$labels_stories = array(
		'name' => _x('Stories', 'Story name', 'Divi'),
		'singular_name' => _x('Content', 'Story type singular name', 'Divi'),
		'add_new' => _x('Add New', 'Story', 'Divi'),
		'add_new_item' => __('Add New Story', 'Divi'),
		'edit_item' => __('Edit Story', 'Divi'),
		'new_item' => __('New Story', 'Divi'),
		'view_item' => __('View Story', 'Divi'),
		'search_items' => __('Search Story', 'Divi'),
		'not_found' => __('No Story Found', 'Divi'),
		'not_found_in_trash' => __('No Story Found in Trash', 'Divi'),
		'parent_item_colon' => ''
	);

	register_post_type('elevate_stories', array('labels' => $labels_stories,
			'public' => true,
			'show_ui' => true,
			'show_in_menu' => true,
			'capability_type' => 'post',
		  'menu_icon' => 'dashicons-format-quote',
			'hierarchical' => false,
			'publicly_queryable' => true,
			'query_var' => true,
			'exclude_from_search' => false,
			'rewrite' => array('slug' => 'stories'),
			'show_in_nav_menus' => false,
			'supports' => array('title',  'editor', 'page-attributes', 'thumbnail')
		)
	);


    $labels_tag = array(
      'name'                       => _x( 'Tags', 'taxonomy general name' ),
      'singular_name'              => _x( 'Tag', 'taxonomy singular name' ),
      'search_items'               => __( 'Search Tag' ),
      'popular_items'              => __( 'Popular Tag' ),
      'all_items'                  => __( 'All Tags' ),
      'parent_item'                => __( 'Parent Tag' ),
      'parent_item_colon'          => __( 'Parent Tag:' ),
      'edit_item'                  => __( 'Edit Tag' ),
      'update_item'                => __( 'Update Tag' ),
      'add_new_item'               => __( 'Add New Tag' ),
      'new_item_name'              => __( 'New Tag Name' ),
      'separate_items_with_commas' => __( 'Separate tags with commas' ),
      'add_or_remove_items'        => __( 'Add or remove tag' ),
      'choose_from_most_used'      => __( 'Choose from the most used tags' ),
      'not_found'                  => __( 'No tags found.' ),
      'menu_name'                  => __( 'Tags' ),
    );

    $args_tag = array(
      'hierarchical'          => false,
      'labels'                => $labels_tag,
      'show_ui'               => true,
      'show_admin_column'     => true,
      'update_count_callback' => '_update_post_term_count',
      'query_var'             => true,
      'rewrite'               => array( 'slug' => 'quote-tag' ),
    );
    register_taxonomy( 'story_tag', 'elevate_stories', $args_tag );


    $labels_category = array(
      'name'                       => _x( 'Categories', 'taxonomy general name' ),
      'singular_name'              => _x( 'Category', 'taxonomy singular name' ),
      'search_items'               => __( 'Search Stroy Category' ),
      'popular_items'              => __( 'Popular Stroy Category' ),
      'all_items'                  => __( 'All Stroy Categories' ),
      'parent_item'                => __( 'Parent Stroy Category' ),
      'parent_item_colon'          => __( 'Parent Stroy Category:' ),
      'edit_item'                  => __( 'Edit Stroy Category' ),
      'update_item'                => __( 'Update Stroy Category' ),
      'add_new_item'               => __( 'Add New Stroy Category' ),
      'new_item_name'              => __( 'New Stroy Category Name' ),
      'separate_items_with_commas' => __( 'Separate Stroy Categories with commas' ),
      'add_or_remove_items'        => __( 'Add or remove Stroy category' ),
      'choose_from_most_used'      => __( 'Choose from the most used Stroy Categories' ),
      'not_found'                  => __( 'No Stroy Categories found.' ),
      'menu_name'                  => __( 'Categories' ),
    );

    $args_category = array(
      'hierarchical'          => true,
      'labels'                => $labels_category,
      'show_ui'               => true,
      'show_admin_column'     => true,
      'update_count_callback' => '_update_post_term_count',
      'query_var'             => true,
      'rewrite'               => array( 'slug' => 'story-category' ),
    );
    register_taxonomy( 'story_category', 'elevate_stories', $args_category );

}

/**
 * Add meta box
 *
 * @param post $post The post object
 * @link https://codex.wordpress.org/Plugin_API/Action_Reference/add_meta_boxes
 */
function elevate_stories_meta_boxes( $post ){
	add_meta_box( 'stories_info_meta_box', __( 'More Information', 'Divi' ), 'elevate_stories_settings_build_meta_box', 'elevate_stories', 'normal', 'default' );
}
add_action( 'add_meta_boxes_elevate_stories', 'elevate_stories_meta_boxes' );


/**
 * Build custom field meta box
 *
 * @param post $post The post object
 */
function elevate_stories_settings_build_meta_box( $post ){
	// make sure the form request comes from WordPress
	wp_nonce_field( basename( __FILE__ ), 'stories_info_meta_box_nonce' );
  $auth_first_name = esc_html(get_post_meta( $post->ID, '_auth_first_name', true ));
  $story_custom_title = esc_html(get_post_meta( $post->ID, '_story_custom_title', true ));
  $auth_last_name = esc_html(get_post_meta( $post->ID, '_auth_last_name', true ));
  $auth_email = esc_html(get_post_meta( $post->ID, '_auth_email', true ));
  $auth_phone = esc_html(get_post_meta( $post->ID, '_auth_phone', true ));
  $auth_campus = esc_html(get_post_meta( $post->ID, '_auth_campus', true ));
  $auth_video = esc_url(get_post_meta( $post->ID, '_auth_video_url', true ));
	?>
	<div class='inside'>
		<table class="form-table">
    <tr>
    <th scope="row"><label for="story_custom_title"><?php echo __( 'Custom Title', 'Divi' ); ?></label></th>
    <td><input type="text" class="regular-text" name="story_custom_title" id="story_custom_title" value="<?php echo $story_custom_title; ?>" placeholder="<?php echo __( 'Title goes here', 'Divi' ); ?>">
    </td>
    </tr>
		<tr>
		<th scope="row"><label for="auth_first_name"><?php echo __( 'First Name', 'Divi' ); ?></label></th>
		<td><input type="text" class="regular-text" name="auth_first_name" id="auth_first_name" value="<?php echo $auth_first_name; ?>" placeholder="<?php echo __( 'John', 'Divi' ); ?>">
		</td>
		</tr>
    <tr>
    <th scope="row"><label for="auth_last_name"><?php echo __( 'Last Name', 'Divi' ); ?></label></th>
    <td><input type="text" class="regular-text" name="auth_last_name" id="auth_last_name" value="<?php echo $auth_last_name; ?>" placeholder="<?php echo __( 'Doe', 'Divi' ); ?>">
    </td>
    </tr>

    <tr>
    <th scope="row"><label for="auth_email"><?php echo __( 'Email', 'Divi' ); ?></label></th>
    <td><input type="text" class="regular-text" name="auth_email" id="auth_email" value="<?php echo $auth_email; ?>" placeholder="<?php echo __( 'info@elc.com', 'Divi' ); ?>">
    </td>
    </tr>
    <tr>
    <th scope="row"><label for="auth_phone"><?php echo __( 'Phone', 'Divi' ); ?></label></th>
    <td><input type="text" class="regular-text" name="auth_phone" id="auth_phone" value="<?php echo $auth_phone; ?>" placeholder="<?php echo __( '555-5555-555', 'Divi' ); ?>">
    </td>
    </tr>
    <tr>
    <th scope="row"><label for="auth_campus"><?php echo __( 'Campus', 'Divi' ); ?></label></th>
    <td><input type="text" class="regular-text" name="auth_campus" id="auth_campus" value="<?php echo $auth_campus; ?>" placeholder="<?php echo __( '', 'Divi' ); ?>">
    </td>
    </tr>
    <tr>
    <th scope="row"><label for="auth_video"><?php echo __( 'Video URL', 'Divi' ); ?></label></th>
    <td><input type="text" class="regular-text" name="auth_video" id="auth_video" value="<?php echo $auth_video; ?>" placeholder="<?php echo __( '', 'Divi' ); ?>">
    </td>
    </tr>
		</table>
	</div>
	<?php
}


/**
 * Store custom field meta box data
 *
 * @param int $post_id The post ID.
 * @link https://codex.wordpress.org/Plugin_API/Action_Reference/save_post
 */
function elevate_stories_sttings_save_meta_box_data( $post_id ){
	// verify meta box nonce
	if ( !isset( $_POST['stories_info_meta_box_nonce'] ) || !wp_verify_nonce( $_POST['stories_info_meta_box_nonce'], basename( __FILE__ ) ) ){
		return;
	}
	// return if autosave
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ){
		return;
	}
  // Check the user's permissions.
	if ( ! current_user_can( 'edit_post', $post_id ) ){
		return;
	}
	// store custom fields values
  if ( isset( $_REQUEST['story_custom_title'] ) ) {
    update_post_meta( $post_id, '_story_custom_title', sanitize_text_field( $_POST['story_custom_title'] ) );
  }
	if ( isset( $_REQUEST['auth_first_name'] ) ) {
		update_post_meta( $post_id, '_auth_first_name', sanitize_text_field( $_POST['auth_first_name'] ) );
	}
  if ( isset( $_REQUEST['auth_last_name'] ) ) {
    update_post_meta( $post_id, '_auth_last_name', sanitize_text_field( $_POST['auth_last_name'] ) );
  }
  if ( isset( $_REQUEST['auth_email'] ) ) {
    update_post_meta( $post_id, '_auth_email', sanitize_text_field( $_POST['auth_email'] ) );
  }
  if ( isset( $_REQUEST['auth_phone'] ) ) {
    update_post_meta( $post_id, '_auth_phone', sanitize_text_field( $_POST['auth_phone'] ) );
  }
  if ( isset( $_REQUEST['auth_campus'] ) ) {
    update_post_meta( $post_id, '_auth_campus', sanitize_text_field( $_POST['auth_campus'] ) );
  }
  if ( isset( $_REQUEST['auth_video'] ) ) {
    update_post_meta( $post_id, '_auth_video_url', sanitize_text_field( $_POST['auth_video'] ) );
  }

}
add_action( 'save_post_elevate_stories', 'elevate_stories_sttings_save_meta_box_data' );


add_shortcode( 'display-stories', 'stories_slider_init' );
function stories_slider_init($atts) {
    $args = shortcode_atts(array(
      'count' => '5',
      'slide_rotated' => 'yes',
    ), $atts);
	extract($args);

	$args_stories = array(
		'post_type' => 'elevate_stories',
		'posts_per_page' => $count,
		'ignore_sticky_posts' => 1
	);

	$query_story = new WP_Query( $args_stories );

		$output = '';
		$output .='<script type="text/javascript">';
		$output .='jQuery(document).ready(function(){';
		$output .='jQuery(".testimonial_slider").flexslider({';
		$output .='nextText: "",';
		$output .='prevText: "",';
		$output .= 'animation: "slide",';
		$output .= 'direction: "vertical",';
		$output .='})';
		$output .='})';
		$output .='</script>';

    if ( $query_story->have_posts() ):
		if( $slide_rotated == 'yes'){
			$output .='<div class="testimonial_slider">';
			$output .='<ul class="slides">';
		} else {
			$output .='<div class="et_section_all_testimonials">';
			$output .='<ul>';
		}
			global $post;
			 while ( $query_story->have_posts() ) : $query_story->the_post();
			 	 $output .='<li>';
          if( $slide_rotated == 'yes'){
              $output .='<div class="testimonial_excerpt">"<a href="/stories/">'.wp_trim_words( get_the_excerpt(), 40 ).'</a>"</div>';
          }else{
				      $output .='<div class="testimonial_excerpt">"'. get_the_content() .'"</div>';
          }

         $output .='<h5 class="testimonial_author">'.get_the_title().'</h5>';
				 $output .='</li>';
			 endwhile;
		$output .='</ul>';
		$output .='</div><!--testimonial_slider-->';
       endif;
	wp_reset_query();
	return $output;

}// End



add_action('admin_menu', 'home_page_setting_options');
function home_page_setting_options() {
	add_submenu_page( 'edit.php?post_type=page', 'Watch Live', 'Watch Live', 'manage_options', 'watch-live-setting', 'watch_live_settings_callback' );
}

function is_watch_live_on($current_time = '', $current_day = ''){
  $result = false;
  if($current_time == ''){
    $current_time = time();
  }
  if($current_day == ''){
    $current_day = date("l", $current_time);
  }

  $current_day_number = date("j", $current_time);

  $watch_live_schedule = get_option('watch_live_schedule');
  if(isset($watch_live_schedule) && is_array($watch_live_schedule) && (count($watch_live_schedule) > 0)){
    foreach ($watch_live_schedule as $watch_live_time) {
        if((isset($watch_live_time['start']) && ($current_time >= strtotime($watch_live_time['start']))) && (isset($watch_live_time['end']) && ($current_time <= strtotime($watch_live_time['end'])))){
            $result = TRUE;
            return $result;
        }
    }
  }

  $day_watch_live_schedule = get_option('day_watch_live_schedule');
  if(isset($day_watch_live_schedule) && is_array($day_watch_live_schedule) && (count($day_watch_live_schedule) > 0)){
    foreach ($day_watch_live_schedule as $day_watch_live_time) {
        if(isset($day_watch_live_time['day']) && ($day_watch_live_time['day'] == $current_day)){
          //each_week first_week
          if(isset($day_watch_live_time['week'])){
            $week_of_month = $day_watch_live_time['week'];
          }else{
            $week_of_month = 'each_week';
          }

          $day_current_date = date("Y-m-d");
          $day_start_time = trim($day_watch_live_time['start']);
          $day_end_time = trim($day_watch_live_time['end']);
          $day_start_date_time = $day_current_date.' '.$day_start_time;
          $day_end_date_time = $day_current_date.' '.$day_end_time;
          if(($week_of_month == 'each_week') && (isset($day_start_date_time) && ($current_time >= strtotime($day_start_date_time))) && (isset($day_end_date_time) && ($current_time <= strtotime($day_end_date_time)))){
              $result = TRUE;
              return $result;
          }elseif((($week_of_month == 'first_week') && ($current_day_number <= 7 )) && (isset($day_start_date_time) && ($current_time >= strtotime($day_start_date_time))) && (isset($day_end_date_time) && ($current_time <= strtotime($day_end_date_time)))){
              $result = TRUE;
              return $result;
          }
        }
    }
  }

  return $result;
}

function watch_live_settings_callback(){
	?> <h2 style="margin:25px 0; font-weight:bold;">Watch Live and Watch Now Button URL Settings</h2> <?php

		if(isset($_POST['watch_live_option_submit'])){
		//update_option( 'id', $_POST['name'] );
		update_option( 'watch_live_url', $_POST['watch_live_url'] );
		update_option( 'watch_now_url', $_POST['watch_now_url'] );
    if(isset($_POST['enable_watch_live'])){
      update_option( 'enable_watch_live', $_POST['enable_watch_live'] );
    }else{
      update_option( 'enable_watch_live', '' );
    }
		?><div class="updated"><p><?php echo __('Successfully Updated', 'Divi'); ?></p></div><?php
    }

		?>
        	<form name="watch_live_settings" class="watch_live_button" method="post">

            	<div class="watch_live_url">
            		<label for="watch_live_url">Watch Live Button URL</label>
                	<input type="text" name="watch_live_url" id="watch_live_url" value="<?php  echo get_option('watch_live_url'); ?>" placeholder="https://" />
               </div>

               <div class="watch_now_url">
            		<label for="watch_now_url">Watch Now Button URL</label>
                	<input type="text" name="watch_now_url" id="watch_now_url" value="<?php echo get_option('watch_now_url'); ?>" placeholder="https://" />
               </div>

                <div>
            	<label for="enable_watch_live"> Enable Watch Live Button </label>
            	<input type="checkbox" name="enable_watch_live" id="enable_watch_live" <?php checked('Enable Watch Live Button', get_option('enable_watch_live'));?> value="Enable Watch Live Button" />
            	</div>


           		<div class="watch_live_submit">
                	<input type="submit" class="button button-primary" name="watch_live_option_submit" id="watch_live_option_submit" value="Save Changes" />
                </div>
            </form>
            <style type="text/css">
				.watch_live_button div{ margin:10px 0;}
				.watch_live_button label{ font-weight:bold; margin-right:8px; width:210px; display:inline-block; font-size:14px;}
				.watch_live_button input[type="text"]{ width:430px;}
        .schedule_date_time{ width:330px;}
        .schedule_day_time{ width: 275px; }
        .schedule_date_group{ margin-bottom: 20px; }
        span.dashicons.watch_live_status.watch_live_active.dashicons-lightbulb{ color: #FF0000; }
        span.dashicons.watch_live_status.watch_live_inactive.dashicons-lightbulb{ color: #666666; }
			</style>

      <br /><br /><br />
      <link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
      <link rel="stylesheet" media="all" type="text/css" href="<?php echo get_stylesheet_directory_uri(); ?>/timepicker/jquery-ui-timepicker-addon.css" />
      <script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
      <script type="text/javascript" src="<?php echo get_stylesheet_directory_uri(); ?>/timepicker/jquery-ui-timepicker-addon.js"></script>
    	<script>
    		jQuery(function() {
          jQuery('.schedule_date_time').each(function() {
            jQuery(this).datetimepicker({
              dateFormat: "yy-mm-dd",
              timeFormat:  "hh:mm tt"
            });
          });
          jQuery('.schedule_day_time').each(function() {
            jQuery(this).timepicker({
              timeFormat:  "hh:mm tt"
            });
          });
    		});
        </script>

      <?php
      if(isset($_POST['watch_live_schedule_submit'])){
          $start_date = $_POST['schedule_start_date'];
          $end_date = $_POST['schedule_end_date'];

          $combine_date = array();
          foreach ($start_date as $key => $start_d) {
            if(($start_d != '') && (strtotime($end_date[$key]) > strtotime($start_d))){
              $combine_date[] = array(
                'start' => $start_d,
                'end' => $end_date[$key]
              );
            }
          }
          update_option( 'watch_live_schedule', $combine_date );

          $day_combine_date = array();
          $week_of_month = $_POST['week_of_month'];
          $week_day = $_POST['day_of_week'];
          $day_start_date = $_POST['day_schedule_start_date'];
          $day_end_date = $_POST['day_schedule_end_date'];
          //day_of_week day_schedule_start_date  day_schedule_end_date
          foreach ($week_day as $key => $week_day) {
            if(($week_day != '') && (strtotime($day_end_date[$key]) > strtotime($day_start_date[$key]))){
              $day_combine_date[] = array(
                'week' => $week_of_month[$key],
                'day' => $week_day,
                'start' => $day_start_date[$key],
                'end' => $day_end_date[$key]
              );
            }
          }
          update_option( 'day_watch_live_schedule', $day_combine_date );

      }

      $watch_live_schedule = get_option('watch_live_schedule');

      $day_watch_live_schedule = get_option('day_watch_live_schedule');

      // echo '<pre>';
      // print_r($day_watch_live_schedule);
      // echo '</pre>';

      $current_time = time();
      $live_status_class = '';
      if(get_option('enable_watch_live') || is_watch_live_on()){
        $live_status_class = 'watch_live_active';
      }else{
        $live_status_class = 'watch_live_inactive';
      }
      ?>
      <h2 style="margin-bottom:0;">Watch Live Schedule <span class="dashicons watch_live_status <?php echo $live_status_class; ?>  dashicons-lightbulb"></span></h2>
      <p style="margin-bottom:20px;"><strong>Now: <?php echo date("l", $current_time); ?>, <?php echo date("Y-m-d g:i a", $current_time); ?></strong></p>
      <form name="watch_live_schedule_settings" class="watch_live_schedule" method="post">
      <div class="schedule_date_group">
        <?php if(isset($watch_live_schedule) && is_array($watch_live_schedule) && (count($watch_live_schedule) > 0)){ ?>
          <?php foreach ($watch_live_schedule as $watch_live_time) { ?>
            <div class="entry input-group">
              <input type="text" name="schedule_start_date[]" class="schedule_date_time" value="<?php echo esc_html($watch_live_time['start']) ?>" placeholder="2018-01-24 08:25:08" />
              To
              <input type="text" name="schedule_end_date[]" class="schedule_date_time" value="<?php echo esc_html($watch_live_time['end']) ?>" placeholder="2018-01-24 08:25:08" />

              <span class="input-group-btn">
                  <button class="btn btn-remove btn-danger" type="button">
                      <span class="dashicons dashicons-no-alt"></span>
                  </button>
              </span>
            </div>
          <?php } ?>
        <?php } ?>

          <div class="entry input-group">
            <input type="text" name="schedule_start_date[]" class="schedule_date_time" value="" placeholder="2018-01-24 08:25:08" />
            To
            <input type="text" name="schedule_end_date[]" class="schedule_date_time" value="" placeholder="2018-01-24 08:25:08" />

            <span class="input-group-btn">
                <button class="btn btn-success btn-add" type="button">
                    <span class="dashicons dashicons-plus"></span>
                </button>
            </span>
          </div>
      </div>

      <h2>Recurring schedule</h2>
      <div class="schedule_date_group_week">
        <?php if(isset($day_watch_live_schedule) && is_array($day_watch_live_schedule) && (count($day_watch_live_schedule) > 0)){ ?>
          <?php foreach ($day_watch_live_schedule as $day_watch_live_time) { ?>
            <div class="entry input-group">
              <?php $selected_week = esc_html($day_watch_live_time['week']); ?>
              <select name="week_of_month[]">
                <option value="each_week" <?php selected( $selected_week, 'each_week' ); ?>>Each Week</option>
                <option value="first_week" <?php selected( $selected_week, 'first_week' ); ?>>1st Week of the Month</option>
              </select>
              <?php $selected_day = esc_html($day_watch_live_time['day']); ?>
              <select name="day_of_week[]">
                <option value="Monday" <?php selected( $selected_day, 'Monday' ); ?>>Monday</option>
                <option value="Tuesday" <?php selected( $selected_day, 'Tuesday' ); ?>>Tuesday</option>
                <option value="Wednesday" <?php selected( $selected_day, 'Wednesday' ); ?>>Wednesday</option>
                <option value="Thursday" <?php selected( $selected_day, 'Thursday' ); ?>>Thursday</option>
                <option value="Friday" <?php selected( $selected_day, 'Friday' ); ?>>Friday</option>
                <option value="Saturday" <?php selected( $selected_day, 'Saturday' ); ?>>Saturday</option>
                <option value="Sunday" <?php selected( $selected_day, 'Sunday' ); ?>>Sunday</option>
              </select>
              <input type="text" name="day_schedule_start_date[]" class="schedule_day_time" value="<?php echo esc_html($day_watch_live_time['start']) ?>" placeholder="12:00 am" />
              To
              <input type="text" name="day_schedule_end_date[]" class="schedule_day_time" value="<?php echo esc_html($day_watch_live_time['end']) ?>" placeholder="12:00 am" />

              <span class="input-group-btn">
                  <button class="btn btn-remove btn-danger" type="button">
                      <span class="dashicons dashicons-no-alt"></span>
                  </button>
              </span>
            </div>
          <?php } ?>
        <?php } ?>

          <div class="entry input-group">
            <select name="week_of_month[]">
              <option value="each_week">Each Week</option>
              <option value="first_week">1st Week of the Month</option>
            </select>
            <select name="day_of_week[]">
              <option value="Monday">Monday</option>
              <option value="Tuesday">Tuesday</option>
              <option value="Wednesday">Wednesday</option>
              <option value="Thursday">Thursday</option>
              <option value="Friday">Friday</option>
              <option value="Saturday">Saturday</option>
              <option value="Sunday">Sunday</option>
            </select>
            <input type="text" name="day_schedule_start_date[]" class="schedule_day_time" value="" placeholder="12:00 am" />
            To
            <input type="text" name="day_schedule_end_date[]" class="schedule_day_time" value="" placeholder="12:00 am" />

            <span class="input-group-btn">
                <button class="btn btn-success btn-add-day" type="button">
                    <span class="dashicons dashicons-plus"></span>
                </button>
            </span>
          </div>
      </div>

      <br /><br />

      <input type="submit" class="button button-primary" name="watch_live_schedule_submit" id="watch_live_schedule_submit" value="Update Schedule" />
      </form>

      <script type="text/javascript">
      jQuery(document).ready(function($) {
        $(document).on('click', '.btn-add', function(e){
            var controlForm = $(this).parents('.schedule_date_group:first'),
                currentEntry = $(this).parents('.entry:first'),
                newEntry = $(currentEntry.clone()).appendTo(controlForm);
            newEntry.find('input').val('');
            controlForm.find('.entry:not(:last) .btn-add')
                .removeClass('btn-add').addClass('btn-remove')
                .removeClass('btn-success').addClass('btn-danger')
                .html('<span class="dashicons dashicons-no-alt"></span>');

            $('.schedule_date_time').removeClass('hasDatepicker');
            $('.schedule_date_time').each(function() {
              $(this).datetimepicker({
                dateFormat: "yy-mm-dd",
                timeFormat:  "hh:mm tt"
              });
            });

        }).on('click', '.btn-remove', function(e){
          $(this).parents('.entry:first').remove();
          return false;
        });
      });
      </script>

      <script type="text/javascript">
      jQuery(document).ready(function($) {
        $(document).on('click', '.btn-add-day', function(e){
            var controlForm = $(this).parents('.schedule_date_group_week:first'),
                currentEntry = $(this).parents('.entry:first'),
                newEntry = $(currentEntry.clone()).appendTo(controlForm);
            newEntry.find('input').val('');
            controlForm.find('.entry:not(:last) .btn-add-day')
                .removeClass('btn-add-day').addClass('btn-remove')
                .removeClass('btn-success').addClass('btn-danger')
                .html('<span class="dashicons dashicons-no-alt"></span>');

            $('.schedule_day_time').removeClass('hasDatepicker');
            $('.schedule_day_time').each(function() {
              $(this).timepicker({
                timeFormat:  "hh:mm tt"
              });
            });

        }).on('click', '.btn-remove', function(e){
          $(this).parents('.entry:first').remove();
          return false;
        });
      });
      </script>

        <?php
}


add_shortcode( 'display-watch-live', 'watch_live_button_shortcode_init' );
function watch_live_button_shortcode_init() {

	ob_start();
		if(get_option('enable_watch_live') || is_watch_live_on()){
			echo '<a class="btn_watch et_pb_button et_pb_module" target="_blank" href="'.get_option('watch_live_url').'">Watch Live</a>';
		} else {
			echo '<a class="btn_watch et_pb_button et_pb_module" target="_blank" href="'.get_option('watch_now_url').'">Watch Now</a>';
		}

	$content = ob_get_clean();
	return $content;

}// End



add_shortcode( 'display-banner-stories', 'banner_stories_slider_shortcode' );
function banner_stories_slider_shortcode($atts) {
    $args = shortcode_atts(array(
      'count' => '5',
    ), $atts);
	extract($args);

	$args_stories = array(
		'post_type' => 'elevate_stories',
		'posts_per_page' => $count,
		'ignore_sticky_posts' => 1
	);

	$query_story = new WP_Query( $args_stories );

		$output = '';
		$output .='<script type="text/javascript">';
		$output .='jQuery(document).ready(function(){';
		$output .='jQuery(".banner_testimonial_slider").flexslider({';
		$output .='nextText: "",';
		$output .='prevText: "",';
		$output .= 'animation: "slide",';
		$output .='})';
		$output .='})';
		$output .='</script>';

    if ( $query_story->have_posts() ):
		$output .='<div class="banner_testimonial_slider">';
		$output .='<ul class="slides">';
			 global $post;
			 while ( $query_story->have_posts() ) : $query_story->the_post();
			 	$output .='<li>';
              	$output .='<div class="testimonial_excerpt">"<a href="/stories/">'.wp_trim_words( get_the_excerpt(), 15 ).'</a>"<span class="testimonial_author"> - '.get_the_title().'</span></div>';
				$output .='</li>';
			 endwhile;
		$output .='</ul>';
		$output .='</div><!--banner_testimonial_slider-->';
       endif;
	wp_reset_query();
	return $output;

}// End


add_shortcode( 'display-banner-random-stories', 'banner_random_story_slider_shortcode' );
function banner_random_story_slider_shortcode($atts) {
    $args = shortcode_atts(array(
      'count' => '1',
      'word_count' => 10,
    ), $atts);
	extract($args);

	$args_stores = array(
		'post_type' => 'elevate_stories',
		'posts_per_page' => $count,
    'orderby' => 'rand',
		'ignore_sticky_posts' => 1
	);

	$query_story = new WP_Query( $args_stores );

		$output = '';

    if ( $query_story->have_posts() ):
		$output .='<div class="banner_testimonial_slider">';
		$output .='<ul class="slides">';
			 global $post;
			 while ( $query_story->have_posts() ) : $query_story->the_post();
       $auth_first_name = esc_html(get_post_meta( get_the_ID(), '_auth_first_name', true ));
       $auth_last_name = esc_html(get_post_meta( get_the_ID(), '_auth_last_name', true ));
       $author_full_name = $auth_first_name .' '. $auth_last_name;
       $actual_quote = '';
       $stories_content = get_the_content();
       $stories_content = apply_filters('the_content', $stories_content);
         // check and retrieve blockquote
        if(preg_match_all('~<blockquote>([\s\S]+?)</blockquote>~', $stories_content, $matches))
        if(isset($matches)){
          $quotes = $matches[0];
          if(is_array($quotes) && (count($quotes) > 0)){
            $value = $quotes[array_rand($quotes)];
            if($value != ''){
                $actual_quote = strip_tags($value);
            }
          }
        }
        if($actual_quote == ''){
          $actual_quote = get_the_excerpt();
        }

			 	$output .='<li>';
              	$output .='<div class="testimonial_excerpt">"<a href="/stories/">'.wp_trim_words( $actual_quote, $word_count ).'</a>"<span class="testimonial_author"> - '.$author_full_name.'</span></div>';
				$output .='</li>';
			 endwhile;
		$output .='</ul>';
		$output .='</div><!--banner_testimonial_slider-->';
       endif;
	wp_reset_query();
	return $output;

}// End



add_shortcode( 'display-stories-grid', 'stories_grid_view_init' );
function stories_grid_view_init($atts) {
    $args = shortcode_atts(array(
      'count' => '6',
      'display_mode' => 'latest',
    ), $atts);
	extract($args);

	$args_stories = array(
		'post_type' => 'elevate_stories',
		'posts_per_page' => $count,
		'ignore_sticky_posts' => 1
	);

	$query_story = new WP_Query( $args_stories );

    if ( $query_story->have_posts() ):
			$output .='<div class="et_section_stores_grid_view">';
			$output .='<ul>';
			global $post;
			 while ( $query_story->have_posts() ) : $query_story->the_post();
			 	 $output .='<li>';
         $output .='<div class="item_wrap">';

          if(has_post_thumbnail()){
            $feature_thumb = wp_get_attachment_image_src(get_post_thumbnail_id(), 'large');
            $output .='<a class="story_thumb" href="'.get_permalink().'"><img src="'.$feature_thumb[0].'" title="'.get_the_title().'" /></a>';
          }

          $output .='<div class="item_content_wrap">';
          $output .='<h3 class="story_title"><a href="'.get_permalink().'">'.get_the_title().'</a></h3>';
          $output .='<div class="story_excerpt">'.wp_trim_words( get_the_excerpt(), 40 ).'</div>';
          $output .='<a class="et_pb_button et_pb_bg_layout_light" href="'.get_permalink().'">Read More</a>';
          $output .='</div>';

         $output .='</div>';
				 $output .='</li>';
			 endwhile;
		$output .='</ul>';
		$output .='</div><!--story grid view-->';
       endif;
	wp_reset_query();
	return $output;

}// End

function add_header_watchlive_notification(){
  $output = '';
  if(get_option('enable_watch_live') || is_watch_live_on()){
    $output .= '<div class="watch_live_notification_section">';
    $output .= '<div class="watch_live_notification">';
    $output .= '<a target="_blank" href="'.get_option('watch_live_url').'">Elevate Life Church Is Live. Watch Now!</a>';
    $output .= '</div>';
    $output .= '</div>';


    //Flush cache if watch live and flus a day when live
    if(get_option('cache_flush') != date('Ymd')){
      if (function_exists('cache_flush')) {
        cache_flush();
        update_option('cache_flush', date('Ymd'));
      }
    }

  }

  return $output;
}

function change_elevate_role_name() {
    global $wp_roles;
    if ( ! isset( $wp_roles ) )
        $wp_roles = new WP_Roles();

    //You can replace "administrator" with any other role "editor", "author", "contributor" or "subscriber"...
    $wp_roles->roles['editor']['name'] = 'Stuff';
    $wp_roles->role_names['editor'] = 'Stuff';

}
add_action('admin_init', 'change_elevate_role_name');


//No need anymore, already created..
function add_user_role_elevate_life(){
	if(isset($_GET['addrole']) && ($_GET['addrole'] == 'do')){
		//add_role( 'staff', 'Staff', array( 'level_7' => true ) );
    add_role( 'elevate_message_editor', 'Message Editor', array( 'edit_posts' => true, 'edit_others_posts' => true, 'level_7' => true ) );
		//remove_role( 'elevate_message_editor' );
	}
}
add_action( 'init', 'add_user_role_elevate_life' );


function rmweb_admin_styles() {
  wp_enqueue_style( 'jquery-ui-datepicker-style' , '//ajax.googleapis.com/ajax/libs/jqueryui/1.10.4/themes/smoothness/jquery-ui.css');
}
add_action('admin_print_styles', 'rmweb_admin_styles');
function rmweb_admin_scripts() {
  wp_enqueue_script( 'jquery-ui-datepicker' );
  wp_enqueue_script( 'wp-jquery-date-picker', get_stylesheet_directory_uri() . '/admin-custom.js' );
}
add_action('admin_enqueue_scripts', 'rmweb_admin_scripts');

add_action('pre_get_posts', 'elevate_team_archive_sort_posts');
/**
 * Hooked into `pre_get_posts` this changes the orderby and order arguments of
 * the query, forcing the post order on post type archives for `your_custom_pt`
 * and a few taxonomies to follow the menu order.
 *
 * @param   object $q The WP_Query object.  This is passed by reference, you
 *          don't have to return anything.
 * @return  null
 */
function elevate_team_archive_sort_posts($query)
{
  if (($query->is_main_query()) && (is_tax('team_member_category'))){
    $query->set('orderby', 'menu_order');
    $query->set('order', 'ASC');
  }

  return $query;
}


add_filter('body_class', 'elevatelife_body_classes');

function elevatelife_body_classes($classes) {

    //Add class if have stories thumbnail
    if(is_singular( 'elevate_stories' )){
        $currnet_story_id = get_the_ID();
        if (has_post_thumbnail( $currnet_story_id ) ){
            $classes[] = 'elevate-transparent-header et_transparent_nav';
        }
    }

    return $classes;
}
