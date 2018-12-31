<?php
function kc_get_current_user_role() {
    if( is_user_logged_in() ) {
      $user = wp_get_current_user();
      $role = ( array ) $user->roles;
      return $role[0];
    } else {
      return false;
    }
 }

/**
 * Redirect back to homepage and not allow access to
 * WP admin for Students.
 */
function kc_profile_redirect_admin(){
    if ( ! defined('DOING_AJAX') && (kc_get_current_user_role() == 'subscriber') ) {
        wp_redirect( site_url() );
        exit;
    }
}
add_action( 'admin_init', 'kc_profile_redirect_admin' );

/* Pull the Parent Theme CSS */
add_action( 'wp_enqueue_scripts', 'theme_enqueue_styles' );
function theme_enqueue_styles() {
    wp_enqueue_style( 'parent-style', get_template_directory_uri() . '/style.css' );

}

/* Hide Divi Builder "project" type */
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








add_action( 'init', 'cmb_initialize_cmb_meta_boxes', 9999 );
function cmb_initialize_cmb_meta_boxes() {
    if ( ! class_exists( 'cmb_Meta_Box' ) )
        require_once 'CMB/init.php';
}//cmb_initialize_cmb_meta_boxes


add_filter( 'cmb_meta_boxes', 'cmb_sample_metaboxes' );
function cmb_sample_metaboxes( array $meta_boxes ) {
    $prefix = '_cmb_';

    $meta_boxes['testimonial_metabox'] = array(
        'id'         => 'testimonial_metabox',
        'title'      => __( 'Testimonial Specifications', 'cmb' ),
        'pages'      => array( 'testimonial', ), // Post type
        'context'    => 'normal',
        'priority'   => 'high',
        'show_names' => true, // Show field names on the left
        // 'cmb_styles' => true, // Enqueue the CMB stylesheet on the frontend
        'fields'     => array(
            array(
                'name' => __( 'Designation', 'cmb' ),
                'desc' => __( 'Thought Behind Testimonial', 'cmb' ),
                'id'   => $prefix . 'testimonials_designation',
                'type'    => 'text',
            ),
        ),
    );

    return $meta_boxes;
} //cmb_sample_metaboxes

/*
    * Testimonials Custom PostType
    * CustomPostType Begain Here
*/
add_action( 'init', 'register_testimonials_custompost_type' );
function register_testimonials_custompost_type() {

    $Testimonials_labels = array(
        'name' => _x('Testimonials', 'Testimonial name', 'RaddsIT'),
        'singular_name' => _x('Testimonial', 'Testimonial type singular name', 'RaddsIT'),
        'add_new' => _x('Add New', 'Testimonial', 'RaddsIT'),
        'all_items' => __('All Testimonial', 'RaddsIT'),
        'add_new_item' => __('Add New Testimonial', 'RaddsIT'),
        'edit_item' => __('Edit Testimonial', 'RaddsIT'),
        'new_item' => __('New Testimonial', 'RaddsIT'),
        'view_item' => __('View Testimonial', 'RaddsIT'),
        'search_items' => __('Search Testimonial', 'RaddsIT'),
        'not_found' => __('No Testimonial Found', 'RaddsIT'),
        'not_found_in_trash' => __('No Testimonial Found in Trash', 'RaddsIT'),
        'parent_item_colon' => ''
    );

// Post_Type Create Here
    register_post_type( 'testimonial',
      array('labels' => $Testimonials_labels,
      'public' => true,
      'show_ui' => true,
      'show_in_menu' => true,
      'menu_icon' => 'dashicons-testimonial',
      'menu_position' => null,
      'capability_type' => 'post',
      'map_meta_cap' => true,
      'hierarchical' => false,
      'publicly_queryable' => true,
      'query_var' => true,
      'exclude_from_search' => false,
      'rewrite' => array('slug' => 'testimonial'),
      'show_in_nav_menus' => false,
      'supports' => array('title',  'editor', 'page-attributes', 'thumbnail', 'excerpt' )
        )
    );



/*
* CustomPostTypy Category
*/

    // Category Labels _________________________________________
    $labels_category = array(
        'name'                       => _x( 'Category', 'taxonomy general name' ),
        'singular_name'              => _x( 'Category', 'taxonomy singular name' ),
        'search_items'               => __( 'Search Categories' ),
        'popular_items'              => __( 'Popular Categories' ),
        'all_items'                  => __( 'All Categories' ),
        'parent_item'                => __( 'Parent Category' ),
        'parent_item_colon'          => __( 'Parent Category:' ),
        'edit_item'                  => __( 'Edit Category' ),
        'update_item'                => __( 'Update Category' ),
        'add_new_item'               => __( 'Add New Category' ),
        'new_item_name'              => __( 'New Category Name' ),
        'separate_items_with_commas' => __( 'Separate categories with commas' ),
        'add_or_remove_items'        => __( 'Add or remove Category' ),
        'choose_from_most_used'      => __( 'Choose from the most used categories' ),
        'not_found'                  => __( 'No tags found.' ),
        'menu_name'                  => __( 'Category' ),
    );

    $args_category = array(
        'hierarchical'          => true,
        'labels'                => $labels_category,
        'capabilities' => array (
    'manage_terms' => 'read',
    'edit_terms' => 'read',
    'delete_terms' => 'read',
    'assign_terms' => 'read'
        ),
        'show_ui'      => true,
        'show_admin_column'     => true,
        'update_count_callback' => '_update_post_term_count',
        'query_var'   => true,
        'rewrite'  => array( 'slug' => 'testimonial-category' ), //category taxonomy
    );
    //explicitcategory is register for Category
    register_taxonomy( 'testimonialcategory', 'testimonial', $args_category );

} //Testimonials_custompost_type ____________








/**
  * Custom Post ShortCode Block
  * [Testimonials cat_name=""  post_count="-1"]
**/
add_shortcode( 'Testimonials', 'register_testimonials_post_type_init' );
function register_testimonials_post_type_init( $atts ) {
    $args = shortcode_atts(array(
    'post_type' => 'testimonial',
    'cat_name' => '',
    'post_count' => '-1',
    ), $atts);
    extract($args);

    $args_post = array( //for Category
        'post_type' => $post_type,
        'tax_query' => array(
            array(
                'taxonomy' => 'testimonialcategory',
                'field' => 'slug',
                'terms' => $cat_name
            )
        ),
        'posts_per_page' => $post_count,
        'orderby' => $post_order_by,
        'order' => $post_order,
        'ignore_sticky_posts' => 1
    );


$custom_recent_post = new WP_Query( $args_post );
ob_start();
if($custom_recent_post->have_posts()) {?>
<div id="page_id_<?php the_ID(); ?>" class="testimonialsWrap clearfix">
   <ul class="clearfix">
      <?php while ( $custom_recent_post->have_posts() ) : $custom_recent_post->the_post(); ?>

      <li class="TestimonialInner clearfix">
      <?php
      $c_pageID = get_the_ID();
      $post_img = wp_get_attachment_image_src(get_post_thumbnail_id(), 'medium');?>

      <?php if(isset($post_img[0])){ ?>
          <img src="<?php echo $post_img[0]; ?>" alt="" />
      <?php } ?>

      <div class="TestimonialBox">
      <h5 class="TestimonialTitle"><?php the_title(); ?></h5>
      <div class="contentBx"><?php echo get_the_content(); ?></div>
      </div>
      </li>
      <?php endwhile;
      wp_reset_postdata(); ?>
   </ul>
</div>

  <?php

  $myvariable = ob_get_clean();
  return $myvariable;
  }
}// End Shortcode for Staff







/**
  * Custom Post ShortCode Block
  * [TestimonialsSlide cat_name="" post_count="3" word_count="100" more_link="" read_more="See More Stories"]
**/
add_shortcode( 'TestimonialsSlide', 'register_testimonials_slide_post_type_init' );
function register_testimonials_slide_post_type_init( $atts ) {
    $args = shortcode_atts(array(
    'post_type' => 'testimonial',
    'cat_name' => '',
    'post_count' => '-1',
    'word_count' => '100',
    'more_link' => '',
    'read_more' => '',
    ), $atts);
    extract($args);

  $args_post = array( //for Category
    'post_type' => $post_type,
//    'tax_query' => array(
//      array(
//        'taxonomy' => 'testimonialcategory',
//        'field' => 'slug',
//        'terms' => $cat_name
//      )
//    ),
    'posts_per_page' => $post_count,
    'orderby' => $post_order_by,
    'order' => $post_order,
    'ignore_sticky_posts' => 1
  );


$custom_recent_post = new WP_Query( $args_post );
ob_start();
if($custom_recent_post->have_posts()) {?>
<div  class="et_pb_module et_pb_slider et_pb_slider_fullwidth_off et_slider_auto et_slider_speed_7000 et_pb_slider_no_shadow  et_pb_slider_0 et_slide_transition_to_next et_pb_bg_layout_dark   <?php echo $cat_name; ?>">
   <ul class="et_pb_slides clearfix">
    <?php
      global $post;
      while ( $custom_recent_post->have_posts() ) : $custom_recent_post->the_post(); ?>
      <?php
          $trimcontent = get_the_content();
          $shortcontent = wp_trim_words( $trimcontent, $num_words = $word_count, $more = '...' );
       ?>
      <li class="et_pb_slide et_pb_slide_with_image et_pb_bg_layout_dark et_pb_media_alignment_center et_pb_slide_0 et-pb-moved-slide">
       <div class="et_pb_container clearfix">
      <?php
      $c_pageID = get_the_ID();
      $post_img = wp_get_attachment_image_src(get_post_thumbnail_id(), 'medium');?>


      <div class="TestimonialBox">
      <?php if(isset($post_img[0])){ ?>
          <div class="tmbsBx"><img src="<?php echo $post_img[0]; ?>" alt="" /></div>
      <?php } ?>
<div class="titleBx">
    <h5 class="TestimonialTitle"><?php the_title(); ?></h5>
<?php if(get_post_meta($post->ID, '_cmb_testimonials_designation', true) ){ ?>
    <div class="designation"> <?php echo get_post_meta($post->ID, '_cmb_testimonials_designation', true); ?></div>
<?php } ?>
    </div>
      </div>
      <div class="contentBx"><?php echo $shortcontent; ?></div>

      </div>
      </li>


      <?php endwhile;
      wp_reset_postdata(); ?>
   </ul>
   <div class="testi_more"><a href="<?php echo $more_link; ?>"><?php echo $read_more; ?></a></div>
<div class="et-pb-slider-arrows"><a href="#" class="et-pb-arrow-prev" style="color: inherit;"><span>Previous</span></a><a href="#" class="et-pb-arrow-next" style="color: inherit;"><span>Next</span></a></div>
</div>

  <?php

  $myvariable = ob_get_clean();
  return $myvariable;
  }
}// End Shortcode for Staff


add_shortcode( 'display-latest-blog', 'latest_blog_shortcode' );
function latest_blog_shortcode() {
	$args = shortcode_atts(array(
        'post_count' => '3',
		'excerpt_count' => '100'
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

	 ob_start();

    if ( $query_post->have_posts() ) { ?>
        	  <ul class="section_latest_blog">
                    <?php global $post; ?>
					<?php while ( $query_post->have_posts() ) : $query_post->the_post(); ?>
                      <li class="latest_blog_inner">
                      <?php $post_id= $post->ID; ?>
							<h3 class="post_slider_title"><a href="<?php the_permalink(); ?>"><?php the_title();?></a></h3>
                             <div class="post_slider_inner"><?php echo rm_truncate_string(get_the_excerpt(), $excerpt_count); ?></div>
														 <div class="post_time_and_read_more clerifix">
                             <span class="sldier_post_time">
                             	<?php
									$post_date= get_the_time( 'F, m.d.Y', $post_id );
									echo $post_date ;
								?>
                             </span>
                             <span class="post_read_more">
                             	<a href="<?php the_permalink(); ?>">Read More</a>
                             </span>
														 </div>
                       </li>
                    <?php endwhile;
                    wp_reset_postdata(); ?>
				   </ul><!--section_latest_blog-->
    <?php $myvariable = ob_get_clean();
    return $myvariable;
    }
}// End



if (!function_exists('rm_truncate_string')) {
    /* Original PHP code by Chirp Internet: www.chirp.com.au
    http://www.the-art-of-web.com/php/truncate/ */
    function rm_truncate_string($string, $limit, $strip_tags = true, $strip_shortcodes = true, $break = " ", $pad = "...") {
        if ($strip_shortcodes)
            $string = strip_shortcodes($string);
        if ($strip_tags)
            $string = strip_tags($string, '<p>'); // retain the p tag for formatting
        // return with no change if string is shorter than $limit
        if (strlen($string) <= $limit)
            return $string;
        elseif ($limit === 0 || $limit == '0')
            return '';
        // is $break present between $limit and the end of the string?
        if (false !== ($breakpoint = strpos($string, $break, $limit))) {
            if ($breakpoint < strlen($string) - 1) {
                $string = substr($string, 0, $breakpoint) . $pad;
            }
        }
        return $string;
    }
}


add_shortcode( 'get_login_url', 'get_login_url_shortcode' );
function get_login_url_shortcode(){
	$output ='';
	if ( ! is_user_logged_in() ) {
		$login_url = wp_login_url( get_permalink() );
		$output .='<a href="'.$login_url.'">Login</a>';
	}
	return $output;
}

add_shortcode( 'get_logout_url', 'get_logout_url_shortcode' );
function get_logout_url_shortcode(){
	$output ='';
	if ( is_user_logged_in() ) {
		$logout_url = wp_logout_url( get_permalink() );
		$output .='<a href="'.$logout_url.'">Logout</a>';
	}
	return $output;
}

add_shortcode( 'logged_in_content', 'dispaly_logged_in_content_shortcode' );
function dispaly_logged_in_content_shortcode($atts, $content = null){
	$output ='';
	if ( is_user_logged_in() ) {
		$output .= $content;
	}
	return $output;
}


add_action( 'wp_head', 'custom_head_script' );
function custom_head_script() {  ?>
	<style type="text/css">
		.free_resources ul{ text-align:center; }
		#more_content{ display:none; }
		#tab_free_resource_content, #tab_additional_resource_content{ display:none; }
		/*.free_resources li.tab_active button{ background:#9b040c; }*/
	</style>
	<script type="text/javascript">
		jQuery( document ).ready(function() {
			jQuery(".tab_free_resource").click(function(){
				jQuery("#tab_additional_resource_content").hide(1000);
				jQuery("#tab_free_resource_content").show(1000);
				jQuery( ".tab_free_resource" ).addClass( "tab_active" );
				jQuery( ".tab_additional_resource" ).removeClass( "tab_active" );
			});
			jQuery(".tab_additional_resource").click(function(){
				jQuery("#tab_free_resource_content").hide(1000);
				jQuery("#tab_additional_resource_content").show(1000);
				jQuery( ".tab_additional_resource" ).addClass( "tab_active" );
				jQuery( ".tab_free_resource" ).removeClass( "tab_active" );
			});
		});

	</script>
<?php }





// [vimeo_link link="https://player.vimeo.com/video/74776672" autoplay="1"]
if( !function_exists('vimeo_link_shortcode') ) {
function vimeo_link_shortcode( $atts , $content = null ) {
	extract(shortcode_atts(array(
		'link' => '',
		'autoplay' => '0',
	), $atts));

	$output .= '<iframe src="'.$link.'?autoplay='.$autoplay.'&byline=0" width="640" height="480" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>';

	return  $output;
} //function
add_shortcode( 'vimeo_link', 'vimeo_link_shortcode' );
} //function_exists

/**
 * Fix Gravity Form Tabindex Conflicts
 * http://gravitywiz.com/fix-gravity-form-tabindex-conflicts/
 */
add_filter( 'gform_tabindex', 'gform_tabindexer', 10, 2 );
function gform_tabindexer( $tab_index, $form = false ) {
    $starting_index = 1000; // if you need a higher tabindex, update this number
    if( $form )
        add_filter( 'gform_tabindex_' . $form['id'], 'gform_tabindexer' );
    return GFCommon::$tab_index >= $starting_index ? GFCommon::$tab_index : $starting_index;
}



add_action( 'init', 'update_keith_other_custom_type', 99 );

/**
 * update_my_custom_type
 *
 */
function update_keith_other_custom_type() {
	global $wp_post_types;

	if ( post_type_exists( 'ms_invoice' ) ) {

		// exclude from search results
		$wp_post_types['ms_invoice']->exclude_from_search = true;
	}

	if ( post_type_exists( 'ms_membership' ) ) {

		// exclude from search results
		$wp_post_types['ms_membership']->exclude_from_search = true;
	}

}


add_shortcode('display-lsi-tag', 'lsi_dropdown_tag_list');
function lsi_dropdown_tag_list(){
	ob_start();
	?>
       <div class="lis_tages">
         <select name="event-dropdown" onchange='document.location.href=this.options[this.selectedIndex].value;'>
                <option value=""><?php echo esc_attr(__('Select LSI Tag')); ?></option>

                <?php
                    $categories = get_categories(array('taxonomy' => 'lsi_tags'));
                    foreach ($categories as $category) {
                        $option .= '<option value="'.get_option('home').'/lsi_tags/'.$category->slug.'">';
                        $option .= $category->cat_name;
                        $option .= ' ('.$category->category_count.')';
                        $option .= '</option>';
                    }
                    echo $option;
                ?>
            </select>
         </div><!--lis_tages-->
    <?php

	$content = ob_get_clean();
	return $content;

}

/*

add_filter('wp_mail_from', 'keith_quote_email_mail_from');
function keith_quote_email_mail_from($original_email_address) {
    return get_option('quote_email_from_email');
}

add_filter('wp_mail_from_name', 'keith_quote_email_mail_from_name');
function keith_quote_email_mail_from_name($original_email_from) {
    return get_option('quote_email_from_name');
}
*/
