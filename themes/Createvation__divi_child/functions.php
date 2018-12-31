<?php

add_action( 'wp_enqueue_scripts', 'enqueue_child_theme_styles');
function enqueue_child_theme_styles() {
  wp_enqueue_style( 'Parents_theme_style', get_template_directory_uri().'/style.css' );

	wp_enqueue_style( 'flex-style', get_stylesheet_directory_uri() . '/inc/flexslider.css' );
  wp_enqueue_script( 'flex-script', get_stylesheet_directory_uri() .  '/inc/jquery.flexslider-min.js', array(), '1.0.0', true );

} //function



add_filter( 'wp_nav_menu_items', 'custom_menu_button_item', 10, 2 );
function custom_menu_button_item ( $items, $args ) {
	?>
	<script src="https://widgets.healcode.com/javascripts/healcode.js" type="text/javascript"></script>
    <?php
    if ($args->theme_location == 'primary-menu') {
        $items .='<li class="taggle_item"><a href="#"><i class="fa fa-bars"></i></a>';
		$items .='<span class="toggle_dropdown"><healcode-widget data-type="account-link" data-version="0.2" data-site-id="15612" data-inner-html="Login | Register" data-link-class="healcode-login-register-text-link"></healcode-widget></span>';		
		//$items .='<span class="toggle_dropdown">';
		//$items .='<a href="#" class="popmake-login-area">Login</a>';
		//$items .='<a href="#">Account</a>';
		//$items .='</span>';
		$items .='</li>';
    }
    return $items;
} //custom_menu_button_item

add_filter( 'wp_nav_menu_items', 'custom_menu_login_item', 10, 2 );
function custom_menu_login_item ( $items, $args ) {
	if ($args->theme_location == 'primary-menu'){
		if(is_user_logged_in()){
			$items .='<li class="custom_login"><a href="'.wp_logout_url( get_permalink() ).'">Logout</a></li>';
		}
		else{
			$items .='<li class="custom_logout"><healcode-widget data-type="account-link" data-version="0.2" data-site-id="15612" data-inner-html="Login" data-link-class="healcode-login-register-text-link"></healcode-widget></li>';
		}
	}
	return $items;	
}



add_shortcode('display_login_form', 'custom_login_form');
function custom_login_form(){	?>

        <script src="https://widgets.healcode.com/javascripts/healcode.js" type="text/javascript"></script>

<healcode-widget data-type="registrations" data-widget-partner="mb" data-widget-id="2d1547576b1" data-widget-version="0.1"></healcode-widget>

<?php
	}


/*
 * CUSTOM QUOTE
*/

add_action( 'init', 'register_dexafit_custompost_type' );
function register_dexafit_custompost_type() {

	$labels_quote = array(
		'name' 					=> __( 'Quotes', 'divi' ),
		'singular_name' 		=> __( 'Quote', 'divi' ),
		'menu_name'				=> _x( 'Quotes', 'Admin menu name', 'divi' ),
		'add_new' 				=> __( 'Add Quote', 'divi' ),
		'add_new_item' 			=> __( 'Add New Quote', 'divi' ),
		'edit' 					=> __( 'Edit', 'divi' ),
		'edit_item' 			=> __( 'Edit Quote', 'divi' ),
		'new_item' 				=> __( 'New Quote', 'divi' ),
		'view' 					=> __( 'View Quote', 'divi' ),
		'view_item' 			=> __( 'View Quote', 'divi' ),
		'search_items' 			=> __( 'Search Quotes', 'divi' ),
		'not_found' 			=> __( 'No Quotes found', 'divi' ),
		'not_found_in_trash' 	=> __( 'No Quotes found in trash', 'divi' ),
		'parent' 				=> __( 'Parent Quotes', 'divi' )
	);

	register_post_type('quote', array('labels' => $labels_quote,
			'description' 			=> __( 'This is where you can add new Companies to your site.', 'divi' ),
			'public' 				=> true,
			'show_ui' 				=> true,
			'capability_type' => 'post',
			'publicly_queryable' 	=> true,
			'exclude_from_search' 	=> false,
			'hierarchical' 			=> false, // Hierarchical causes memory issues - WP loads all records!
			'rewrite' => array('slug' => 'quote'),
			'query_var' 			=> true,
			'menu_position'      => 2,
			'supports' 				=> array('title', 'editor', 'custom-fields', 'page-attributes', 'thumbnail'),
			'show_in_nav_menus' 	=> true
		)
	);

	register_taxonomy( 'quotecategory',
		apply_filters( 'quotecategory_taxonomy_objects_task', array( 'quote' ) ),
		apply_filters( 'quotecategory_taxonomy_args_task', array(
			'hierarchical' 			=> true,
			'label' 				=> __( 'Category', 'STM' ),
			'labels' => array(
					'name' 				=> __( 'Category', 'STM' ),
					'singular_name' 	=> __( 'Category', 'STM' ),
					'menu_name'			=> _x( 'Category', 'Admin menu name', 'STM' ),
					'search_items' 		=> __( 'Search Category', 'STM' ),
					'all_items' 		=> __( 'All Categorys', 'STM' ),
					'parent_item' 		=> __( 'Parent Category', 'STM' ),
					'parent_item_colon' => __( 'Parent Category:', 'STM' ),
					'edit_item' 		=> __( 'Edit Category', 'STM' ),
					'update_item' 		=> __( 'Update Category', 'STM' ),
					'add_new_item' 		=> __( 'Add New Category', 'STM' ),
					'new_item_name' 	=> __( 'New Category Name', 'STM' )
				),
			'show_ui' 				=> true,
			'show_admin_column'     => true,
			'query_var' 			=> true,
			'rewrite' => array( 'slug' => 'quote-category' ),
		) )
	);
}


// [show-quote-slider category="slug"]
add_shortcode( 'show-quote-slider', 'rm_quote_slider_shortcode_init' );
function rm_quote_slider_shortcode_init( $atts ) {
    $args = shortcode_atts(array(
    'post_count' => '-1',
		'image_size' => 'full',
		'category' => ''
    ), $atts);
    extract($args);

    ob_start();

	$post_order_by = 'menu_order';
	$post_order = 'ASC';

	$args_post_tax = array(
		'post_type' => 'quote',
		'tax_query' => array(
			array(
				'taxonomy' => 'quotecategory',//resister taxonomy
				'field' => 'slug',
				'terms' => $category // category slug, or id
			)
		),
		'posts_per_page' => $post_count,
		'orderby' => $post_order_by,
		'order' => $post_order,
		'ignore_sticky_posts' => 1
	);

	$custom_quote_post = new WP_Query( $args_post_tax );


		if ( $custom_quote_post->have_posts() ) { ?>

		<script type="text/javascript">
		jQuery(document).ready(function(){
		   jQuery('.quote_slider .flexslider').flexslider({
			animation: "slide",
			slideshow: false,
			controlNav: true,
			prevText: "",
			nextText: "",
			pauseOnHover: true,
			slideshow: true
		  });
	   });
		</script>

        		<div class="quote_slider">
        		<div class="flexslider">
        			<ul class="slides">
					<?php while ( $custom_quote_post->have_posts() ) : $custom_quote_post->the_post(); ?>
             <li>
					  	<?php
							$full_image = wp_get_attachment_image_src(get_post_thumbnail_id(), $image_size);
							$image_url = $full_image[0];
						?>


<div class="testimonialsWrap" >
<div class="tmonialsTxtWrap">
<div class="tmonialsTxt">
<?php the_content(); ?>
</div>
<div class="tmonialAuthor"><?php the_title(); ?></div>
</div>
</div>

                        </li><!--quote_slide-->
                    <?php endwhile;
                   wp_reset_query(); ?>
				   </ul><!--slides-->
				   </div><!--flexslider-->
               </div><!--quote_slider-->
    <?php $myvariable = ob_get_clean();
    return $myvariable;
    }
}// End




add_action( 'wp_head', 'custom_head_script' );
function custom_head_script() {  ?>
	<style type="text/css">
		.more_content{ display:none; }
	</style>
	<script type="text/javascript">
		jQuery( document ).ready(function() {
			jQuery(".more_less_button #less_details").hide();

			jQuery( ".more_less_button" ).on( "click", "#less_details", function() {
				jQuery(".more_content").slideUp(1000);
				jQuery(".more_less_button #less_details").hide();
				jQuery(".more_less_button #more_details").show();				
			});
			jQuery( ".more_less_button" ).on( "click", "#more_details", function() {
				jQuery(".more_content").slideDown(1000);
				jQuery(".more_less_button #more_details").hide();
				jQuery(".more_less_button #less_details").show();
			});
		});
	</script>
<?php } // function   end


