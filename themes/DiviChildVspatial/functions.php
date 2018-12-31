<?php

function custom_enqueue_styles() {
    wp_enqueue_style( 'parent-style', get_template_directory_uri() . '/style.css' );
	 wp_enqueue_style( 'font-awesome', '//maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css' );
	//wp_enqueue_style( 'Bx-Style', '/inc/bxslider.css' );
	wp_enqueue_script( 'Bx-Script', get_stylesheet_directory_uri() .  '/inc/bxslider.js', array(), 'v4.2.12', true );
}
add_action( 'wp_enqueue_scripts', 'custom_enqueue_styles' );


if ( file_exists( dirname( __FILE__ ) . '/cmb2/init.php' ) ) {
	require_once dirname( __FILE__ ) . '/cmb2/init.php';
} elseif ( file_exists( dirname( __FILE__ ) . '/CMB2/init.php' ) ) {
	require_once dirname( __FILE__ ) . '/CMB2/init.php';
}

add_action( 'cmb2_admin_init', 'register_quotes_metabox' );
function register_quotes_metabox() {
	$prefix = 'quote_';

	$cmb_quote = new_cmb2_box( array(
		'id'            => $prefix . 'quote_metabox',
		'title'         => esc_html__( 'Quote Specification', 'cmb2' ),
		'object_types'  => array( 'quotes' ), // Post type
	) );

	$cmb_quote->add_field( array(
		'name' => esc_html__( 'Quote', 'cmb2' ),
		'id'   => $prefix . 'description',
		'type' => 'textarea',
	) );

	$cmb_quote->add_field( array(
		'name' => esc_html__( 'Author', 'cmb2' ),
		'id'   => $prefix . 'author',
		'type' => 'text',
	) );

	$cmb_quote->add_field( array(
		'name' => esc_html__( 'Author Title', 'cmb2' ),
		'id'   => $prefix . 'author_title',
		'type' => 'text',
	) );
}


add_action( 'init', 'register_quotes_custompost_type' );
function register_quotes_custompost_type() {
	$labels_quotes = array(
		'name' => _x('Quotes', 'Quotes', 'Divi'),
		'singular_name' => _x('Quote', 'Quote type singular name', 'Divi'),
		'add_new' => _x('Add New', 'Quote', 'Divi'),
		'add_new_item' => __('Add New Quote', 'Divi'),
		'edit_item' => __('Edit Quote', 'Divi'),
		'new_item' => __('New Quote', 'Divi'),
		'view_item' => __('View Quote', 'Divi'),
		'search_items' => __('Search Quotes', 'Divi'),
		'not_found' => __('No Quotes Found', 'Divi'),
		'not_found_in_trash' => __('No Quote Found in Trash', 'Divi'),
		'parent_item_colon' => ''
	);

	register_post_type('quotes', array('labels' => $labels_quotes,
		'public' => true,
		'show_ui' => true,
		'show_in_menu' => true,
		'capability_type' => 'post',
		'menu_icon' => 'dashicons-format-quote',
		'hierarchical' => false,
		'publicly_queryable' => true,
		'query_var' => true,
		'exclude_from_search' => false,
		'rewrite' => array('slug' => 'quote'),
		'show_in_nav_menus' => false,
		'supports' => array('title', 'page-attributes', 'revisions', 'author')
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
  register_taxonomy( 'quotetag', 'quotes', $args_tag );


  $labels_category = array(
    'name'                       => _x( 'Categories', 'taxonomy general name' ),
    'singular_name'              => _x( 'Category', 'taxonomy singular name' ),
    'search_items'               => __( 'Search Quote Category' ),
    'popular_items'              => __( 'Popular Quote Category' ),
    'all_items'                  => __( 'All Quote Categories' ),
    'parent_item'                => __( 'Parent Quote Category' ),
    'parent_item_colon'          => __( 'Parent Quote Category:' ),
    'edit_item'                  => __( 'Edit Quote Category' ),
    'update_item'                => __( 'Update Quote Category' ),
    'add_new_item'               => __( 'Add New Quote Category' ),
    'new_item_name'              => __( 'New Quote Category Name' ),
    'separate_items_with_commas' => __( 'Separate Quote Categories with commas' ),
    'add_or_remove_items'        => __( 'Add or remove Quote category' ),
    'choose_from_most_used'      => __( 'Choose from the most used Quote Categories' ),
    'not_found'                  => __( 'No Quote Categories found.' ),
    'menu_name'                  => __( 'Categories' ),
  );

  $args_category = array(
    'hierarchical'          => true,
    'labels'                => $labels_category,
    'show_ui'               => true,
    'show_admin_column'     => true,
    'update_count_callback' => '_update_post_term_count',
    'query_var'             => true,
    'rewrite'               => array( 'slug' => 'quote-category' ),
  );
  register_taxonomy( 'quote_category', 'quotes', $args_category );

}



add_shortcode( 'display-quotes', 'quotes_slider_shortcode_init' );
function quotes_slider_shortcode_init() {

	$args_quote = array(
		'post_type' => 'quotes',
		'posts_per_page' => '-1',
		'ignore_sticky_posts' => 1
	);

	$query_quote = new WP_Query( $args_quote );

		$output = '';
		$output .='<script type="text/javascript">';
		$output .='jQuery(document).ready(function(){';
		$output .='jQuery(".quote_slider").bxSlider({';
		$output .='nextText: "",';
		$output .='prevText: "",';
		$output .= 'autoHover: true,';
    $output .= 'pause: 10000,';
		$output .= 'auto: true,';
		$output .= 'pager: false,';
		$output .= 'maxSlides: 3,';
		$output .= 'moveSlides: 1,';
		$output .= 'slideWidth: 320,';
		$output .= 'slideMargin: 20,';
		$output .= 'onSliderLoad:  function(){';
		$output .='jQuery(".et_section_quotes").css("height", "auto")';
		$output .='}';
		$output .='})';
		$output .='})';
		$output .='</script>';

    if ( $query_quote->have_posts() ):
		$output .='<div class="et_section_quotes">';
		$output .='<ul class="quote_slider">';
			global $post;
			 while ( $query_quote->have_posts() ) : $query_quote->the_post();
				 $output .='<li>';
         $quote_url = get_permalink($post->ID);
				 $output .='<div class="quote_description_wrap">';
				 if(get_post_meta($post->ID, 'quote_description', true)):
          $quote_description = wp_trim_words( get_post_meta($post->ID, 'quote_description', true), 16, '...' );
				 	$output .='<div class="quote_description"><a href="'.$quote_url.'">'.$quote_description.'</a></div>';
				 endif;
				 if(get_post_meta($post->ID, 'quote_author', true)):
				 	$output .='<h3 class="author_name">'.get_post_meta($post->ID, 'quote_author', true).'</h3>';
				 endif;

				 if(get_post_meta($post->ID, 'quote_author_title', true)):
				 	$output .='<h5 class="author_designation">'.get_post_meta($post->ID, 'quote_author_title', true).'</h5>';
				 endif;

				 $output .='</div><!--quote_description_wrap-->';


				 $output .='<div class="quote_social_icon">';
				 	$share_url = get_permalink($post->ID);
				 	$share_title = str_replace( ' ', '%20', get_the_title($post->ID));
          $quote_content = get_post_meta($post->ID, 'quote_description', true);
          $share_content = str_replace( ' ', '%20', $quote_content);
					$share_img = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'full' );
					$twitter_url = 'https://twitter.com/intent/tweet?text='.$share_content.'&amp;url='.$share_url.'';

					$facebook_url = 'https://www.facebook.com/sharer/sharer.php?image='.$share_img[0].'&amp;u='.$share_url.'?title='.$share_title.'';

				 	$output .='<a target="_blank" href="'.$facebook_url.'"><i class="fa fa-facebook" aria-hidden="true"></i></a>';
				 	$output .='<a target="_blank" href="'.$twitter_url.'"><i class="fa fa-twitter" aria-hidden="true"></i></a>';
				 $output .='</div><!--quote_social_icon-->';

				 $output .='</li>';
			 endwhile;
		$output .='</ul>';
		$output .='</div><!--et_section_quotes-->';
       endif;
	wp_reset_query();
	return $output;

}// End



add_shortcode( 'display-all-quotes', 'quotes_display_all_shortcode_init' );
function quotes_display_all_shortcode_init() {

	$args_quote = array(
		'post_type' => 'quotes',
		'posts_per_page' => '-1',
		'ignore_sticky_posts' => 1
	);

	$query_quote = new WP_Query( $args_quote );

		$output = '';

    if ( $query_quote->have_posts() ):
		$output .='<div class="et_section_quotes_archive">';
		$output .='<ul class="quote_archive">';
			global $post;
			 while ( $query_quote->have_posts() ) : $query_quote->the_post();
				 $output .='<li>';
         $quote_url = get_permalink($post->ID);
				 $output .='<div class="quote_description_wrap">';
				 if(get_post_meta($post->ID, 'quote_description', true)):
          $quote_description = wp_trim_words( get_post_meta($post->ID, 'quote_description', true), 16, '...' );
				 	$output .='<div class="quote_description"><a href="'.$quote_url.'">'.$quote_description.'</a></div>';
				 endif;
				 if(get_post_meta($post->ID, 'quote_author', true)):
				 	$output .='<h3 class="author_name">'.get_post_meta($post->ID, 'quote_author', true).'</h3>';
				 endif;

				 if(get_post_meta($post->ID, 'quote_author_title', true)):
				 	$output .='<h5 class="author_designation">'.get_post_meta($post->ID, 'quote_author_title', true).'</h5>';
				 endif;

				 $output .='</div><!--quote_description_wrap-->';


				 $output .='<div class="quote_social_icon">';
				 	$share_url = get_permalink($post->ID);
				 	$share_title = str_replace( ' ', '%20', get_the_title($post->ID));
          $quote_content = get_post_meta($post->ID, 'quote_description', true);
          $share_content = str_replace( ' ', '%20', $quote_content);
					$share_img = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'full' );
					$twitter_url = 'https://twitter.com/intent/tweet?text='.$share_content.'&amp;url='.$share_url.'';

					$facebook_url = 'https://www.facebook.com/sharer/sharer.php?image='.$share_img[0].'&amp;u='.$share_url.'?title='.$share_title.'';

				 	$output .='<a target="_blank" href="'.$facebook_url.'"><i class="fa fa-facebook" aria-hidden="true"></i></a>';
				 	$output .='<a target="_blank" href="'.$twitter_url.'"><i class="fa fa-twitter" aria-hidden="true"></i></a>';
				 $output .='</div><!--quote_social_icon-->';

				 $output .='</li>';
			 endwhile;
		$output .='</ul>';
		$output .='</div><!--et_section_quotes-->';
       endif;
	wp_reset_query();
	return $output;

}// End


add_shortcode( 'display-recent-news', 'recent_news_slider_shortcode_init' );
function recent_news_slider_shortcode_init() {

	$args_news = array(
		'post_type' => 'post',
		'posts_per_page' => '-1',
		'ignore_sticky_posts' => 1
	);

	$query_news = new WP_Query( $args_news );

		$output = '';
		$output .='<script type="text/javascript">';
		$output .='jQuery(document).ready(function(){';
		$output .='jQuery(".recent_news_slider").bxSlider({';
		$output .='nextText: "",';
		$output .='prevText: "",';
		$output .= 'autoHover: true,';
		$output .= 'auto: false,';
    $output .= 'pause: 10000,';
		$output .= 'pager: false,';
		$output .= 'maxSlides: 1,';
		$output .= 'moveSlides: 1,';
		$output .= 'slideWidth: 1000,';
		$output .= 'slideMargin: 0,';
		$output .= 'onSliderLoad:  function(){';
		$output .='jQuery(".recent_news_wrap").css("height", "auto")';
		$output .='}';
		$output .='})';
		$output .='})';
		$output .='</script>';

    if ( $query_news->have_posts() ):
		$output .='<div class="recent_news_wrap">';
		$output .='<ul class="recent_news_slider">';
			global $post;
			 while ( $query_news->have_posts() ) : $query_news->the_post();
				 $output .='<li>';
				 if(has_post_thumbnail()){
					 $feature_thumb = wp_get_attachment_image_src(get_post_thumbnail_id(), 'recent-news');
					 $output .='<a class="recent_thumb" href="'.get_permalink().'"><img src="'.$feature_thumb[0].'" title="'.get_the_title().'" /></a>';
                 }
				 $output .='<div class="news_overlay_wrap">';
				 	$output .='<h3 class="news_title"><a href="'.get_permalink().'">'.get_the_title().'</a></h3>';
					$output .='<div class="news_date">'.get_the_date('M j, Y', $post->ID).'</div>';
				 $output .='</div>';
				 $output .='</li>';
			 endwhile;
		$output .='</ul>';
		$output .='</div><!--et_section_quotes-->';
       endif;
	wp_reset_query();
	return $output;

}// End




add_shortcode( 'display-recent-post', 'recent_post_shortcode_init' );
function recent_post_shortcode_init($atts) {
	$args = shortcode_atts(array(
        'post_count' => '3',
    ), $atts);
	extract($args);

	$args_post = array(
		'post_type' => 'post',
		'posts_per_page' => $post_count,
        'order' => 'DESC',
        'orderby' => 'date',
		'ignore_sticky_posts' => 1
	);

	$query_post = new WP_Query( $args_post );

	$output = '';
    if ( $query_post->have_posts() ):
		$output .='<ul class="sidebar_post">';
			global $post;
			 while ( $query_post->have_posts() ) : $query_post->the_post();
			 	 $output .='<li>';
				 if(has_post_thumbnail()){
                 $feature_thumb = wp_get_attachment_image_src(get_post_thumbnail_id(), 'medium');
				 $output .='<a href="'.get_permalink().'"><img src="'.$feature_thumb[0].'" title="'.get_the_title().'" /></a>';
                  }
				  $output .='<div class="post_date">'.get_the_date('j F Y ', $post->ID).'</div>';
				  $output .='<h3><a href="'.get_permalink().'">'.get_the_title().'</a></h3>';

				 $output .='</li>';
			 endwhile;
		$output .='</ul><!--sidebar_post-->';
       endif;
	wp_reset_query();
	return $output;

}// End

// Add signature or ad after post content

function vspatial_after_post_content($content){
    if (is_single()) {
        $content .= '<div class="bottom_post_line">Keep the conversation going! Talk with us on <a href="https://twitter.com/vspatialvr" target="_blank">Twitter</a> and <a href="https://www.facebook.com/vspatialvr" target="_blank">Facebook</a>.</div>';
    }
    return $content;
}
//add_filter( "the_content", "vspatial_after_post_content", 0 );


remove_filter('the_content', 'guerrilla_add_post_content', 0);
//add_filter( "the_content", "guerrilla_add_post_content", 2 );


function vspatial_guerrilla_add_post_content() {
	if (is_single()) {
    $content = '';
    $content .= '<div class="bottom_post_line">Keep the conversation going! Talk with us on <a href="https://twitter.com/vspatialvr" target="_blank">Twitter</a> and <a href="https://www.facebook.com/vspatialvr" target="_blank">Facebook</a>.</div>';
		$content .= '
			<div class="guerrillawrap">
			<div class="guerrillagravatar">
				'. get_avatar( get_the_author_email(), '80' ) .'
			</div>
			<div class="guerrillatext">
				<h4>Author: <span>'. get_the_author_link('display_name',get_query_var('author') ) .'</span></h4>'. get_the_author_meta('description',get_query_var('author') ) .'
			</div>
		';
		$content .= '
			<div class="guerrillasocial">
			';
			if( get_the_author_meta('twitter',get_query_var('author') ) )
				$content .= '<a href="' . esc_url( get_the_author_meta( 'twitter' ) ) . '" target="_blank"><i class="fa fa-twitter"></i> Twitter</a> ';
			if( get_the_author_meta('facebook',get_query_var('author') ) )
				$content .= '<a href="' . esc_url( get_the_author_meta( 'facebook' ) ) . '" target="_blank"><i class="fa fa-facebook"></i> Facebook</a> ';
			if( get_the_author_meta('gplus',get_query_var('author') ) )
				$content .= '<a href="' . esc_url( get_the_author_meta( 'gplus' ) ) . '" target="_blank"><i class="fa fa-google-plus"></i> Google+</a> ';
			if( get_the_author_meta('linkedin',get_query_var('author') ) )
				$content .= '<a href="' . esc_url( get_the_author_meta( 'linkedin' ) ) . '" target="_blank"><i class="fa fa-linkedin"></i> Linkedin</a> ';
			if( get_the_author_meta('dribbble',get_query_var('author') ) )
				$content .= '<a href="' . esc_url( get_the_author_meta( 'dribbble' ) ) . '" target="_blank"><i class="fa fa-dribbble"></i> Dribbble</a> ';
			if( get_the_author_meta('github',get_query_var('author') ) )
				$content .= '<a href="' . esc_url( get_the_author_meta( 'github' ) ) . '" target="_blank"><i class="fa fa-github"></i> Github</a>';
		$content .= '
			</div>
			</div>
		';
	}
	return $content;
}

// Disable use XML-RPC
add_filter( 'xmlrpc_enabled', '__return_false' );

// Disable X-Pingback to header
add_filter( 'wp_headers', 'disable_x_pingback' );
function disable_x_pingback( $headers ) {
    unset( $headers['X-Pingback'] );

return $headers;
}


add_image_size( 'recent-news', 900, 450,  true );

add_filter('wpseo_metadesc','quote_change_yoast_description', 100, 1);
function quote_change_yoast_description($description)
{
  $new_description = $description;
  if (is_singular('quotes'))
  {
    global $post;
    if(get_post_meta($post->ID, 'quote_description', true))
        $new_description = get_post_meta($post->ID, 'quote_description', true);
  }
  return $new_description;
}

function vspatial_dynamic_script(){
  if(is_page('feedback')){
  ?>
  <script type="text/javascript">
      function isIncValidEmail(emailText) {
        var pattern = new RegExp(/^((([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+(\.([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+)*)|((\x22)((((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(([\x01-\x08\x0b\x0c\x0e-\x1f\x7f]|\x21|[\x23-\x5b]|[\x5d-\x7e]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(\\([\x01-\x09\x0b\x0c\x0d-\x7f]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))))*(((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(\x22)))@((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?$/i);
        return pattern.test(emailText);
    };
    jQuery(document).ready(function($) {
      jQuery(".gf_intercom_email_submit_wrapper .gform_button").click(function(){
          var thankyou_url = '<?php echo esc_url( home_url( '/' ) ); ?>';
          var submit_button = jQuery(this);
          //$email = $button.siblings(".intercom_email").val();
          //$email = $button.find('.intercom_email input.medium').val();
          var email_address = jQuery('.gf_intercom_email_submit_wrapper .gf_intercom_email input[type="text"').val();
          if(isIncValidEmail(email_address)){
            var url_ajax = '<?php echo admin_url( 'admin-ajax.php' );?>';
            var data = {
                    'action': 'send_intercom_email',
                    'intercom_email': email_address
                };
            jQuery.post(url_ajax, data, function(response) {
                    console.log('Got this from the server: ' + response);
                    jQuery('body').append(response);
                    //alert('You have successfully been subscribed.');
                    //$button.siblings(".intercom-sucess").html('<p>You have successfully been subscribed.</p>');
                 //console.log(response);
                 setTimeout(function(){ window.location.href = thankyou_url; }, 3500);

                });
          }
      });
    });
  </script>
  <?php } ?>
<script type="text/javascript">
		jQuery(document).ready(function(){
				jQuery('.btn_stay_in_know').click(function (e) {
					e.preventDefault();
					jQuery( ".stay_connected_footer" ).slideToggle( "slow");
					jQuery( this ).toggleClass( "open" );
				});
		});
</script>
  <?php
}
add_action('wp_head', 'vspatial_dynamic_script');

add_action('et_header_top', 'header_social_list');
function header_social_list(){ ?>
	<div class="header_social_list">
    	<?php dynamic_sidebar('et_pb_widget_area_6'); ?>
    </div>
<?php }
