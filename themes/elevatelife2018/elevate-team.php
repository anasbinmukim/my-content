<?php

add_action( 'init', 'register_elevate_team_custompost_type' );
function register_elevate_team_custompost_type() {
	$labels_team = array(
		'name' => _x('Teams', 'Team name', 'Divi'),
		'singular_name' => _x('Team', 'Team type singular name', 'Divi'),
		'add_new' => _x('Add New', 'Member', 'Divi'),
		'add_new_item' => __('Add New Member', 'Divi'),
		'edit_item' => __('Edit Member', 'Divi'),
		'new_item' => __('New Member', 'Divi'),
		'view_item' => __('View Member', 'Divi'),
		'search_items' => __('Search Member', 'Divi'),
		'not_found' => __('No Member Found', 'Divi'),
		'not_found_in_trash' => __('No Member Found in Trash', 'Divi'),
		'parent_item_colon' => ''
	);

	register_post_type('elevate_team', array('labels' => $labels_team,
			'public' => false,
			'show_ui' => true,
			'show_in_menu' => true,
			'capability_type' => 'post',
		  'menu_icon' => 'dashicons-groups',
			'hierarchical' => false,
			'publicly_queryable' => false,
			'query_var' => true,
			'exclude_from_search' => false,
			'rewrite' => array('slug' => 'team-member'),
			'show_in_nav_menus' => false,
			'supports' => array('title', 'page-attributes', 'thumbnail')
		)
	);

    $labels_category = array(
      'name'                       => _x( 'Categories', 'taxonomy general name' ),
      'singular_name'              => _x( 'Category', 'taxonomy singular name' ),
      'search_items'               => __( 'Search Member Category' ),
      'popular_items'              => __( 'Popular Member Category' ),
      'all_items'                  => __( 'All Member Categories' ),
      'parent_item'                => __( 'Parent Member Category' ),
      'parent_item_colon'          => __( 'Parent Member Category:' ),
      'edit_item'                  => __( 'Edit Member Category' ),
      'update_item'                => __( 'Update Member Category' ),
      'add_new_item'               => __( 'Add New Member Category' ),
      'new_item_name'              => __( 'New Member Category Name' ),
      'separate_items_with_commas' => __( 'Separate Member Categories with commas' ),
      'add_or_remove_items'        => __( 'Add or remove Member category' ),
      'choose_from_most_used'      => __( 'Choose from the most used Member Categories' ),
      'not_found'                  => __( 'No Member Categories found.' ),
      'menu_name'                  => __( 'Categories' ),
    );

    $args_category = array(
      'hierarchical'          => true,
      'labels'                => $labels_category,
      'show_ui'               => true,
      'show_admin_column'     => true,
      'update_count_callback' => '_update_post_term_count',
      'query_var'             => true,
      'rewrite'               => array( 'slug' => 'team' ),
    );
    register_taxonomy( 'team_member_category', 'elevate_team', $args_category );

}



/**
 * Add meta box
 *
 * @param post $post The post object
 * @link https://codex.wordpress.org/Plugin_API/Action_Reference/add_meta_boxes
 */
function elevate_team_meta_boxes( $post ){
	add_meta_box( 'team_info_meta_box', __( 'Member Information', 'Divi' ), 'elevate_team_settings_build_meta_box', 'elevate_team', 'normal', 'default' );
}
add_action( 'add_meta_boxes_elevate_team', 'elevate_team_meta_boxes' );


/**
 * Build custom field meta box
 *
 * @param post $post The post object
 */
function elevate_team_settings_build_meta_box( $post ){
	// make sure the form request comes from WordPress
	wp_nonce_field( basename( __FILE__ ), 'team_info_meta_box_nonce' );
  $member_designation = esc_html(get_post_meta( $post->ID, '_member_designation', true ));
	$nickname = esc_html(get_post_meta( $post->ID, '_nickname', true ));
	$email = esc_html(get_post_meta( $post->ID, '_email', true ));
	?>
	<div class='inside'>
		<table class="form-table">
		<tr>
	    <th scope="row"><label for="nickname"><?php echo __( 'Nickname', 'Divi' ); ?></label></th>
	    <td><input type="text" class="regular-text" name="nickname" id="nickname" value="<?php echo $nickname; ?>" placeholder="<?php echo __( 'John', 'Divi' ); ?>"></td>
    </tr>
    <tr>
	    <th scope="row"><label for="member_designation"><?php echo __( 'Designation', 'Divi' ); ?></label></th>
	    <td><input type="text" class="regular-text" name="member_designation" id="member_designation" value="<?php echo $member_designation; ?>" placeholder="<?php echo __( 'Pastor of Culture', 'Divi' ); ?>"></td>
    </tr>
		<tr>
	    <th scope="row"><label for="email"><?php echo __( 'Email', 'Divi' ); ?></label></th>
	    <td><input type="text" class="regular-text" name="email" id="email" value="<?php echo $email; ?>" placeholder="<?php echo __( 'john@webmail.com', 'Divi' ); ?>"></td>
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
function elevate_team_sttings_save_meta_box_data( $post_id ){
	// verify meta box nonce
	if ( !isset( $_POST['team_info_meta_box_nonce'] ) || !wp_verify_nonce( $_POST['team_info_meta_box_nonce'], basename( __FILE__ ) ) ){
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
  if ( isset( $_REQUEST['nickname'] ) ) {
    update_post_meta( $post_id, '_nickname', sanitize_text_field( $_POST['nickname'] ) );
  }
	if ( isset( $_REQUEST['member_designation'] ) ) {
		update_post_meta( $post_id, '_member_designation', sanitize_text_field( $_POST['member_designation'] ) );
	}
	if ( isset( $_REQUEST['email'] ) ) {
		update_post_meta( $post_id, '_email', sanitize_email( $_POST['email'] ) );
	}

}
add_action( 'save_post_elevate_team', 'elevate_team_sttings_save_meta_box_data' );



add_shortcode( 'display-team-member-grid', 'team_member_grid_view_init' );
function team_member_grid_view_init($atts) {
    $args = shortcode_atts(array(
      'count' => '-1',
      'cat_slug' => '',
    ), $atts);
	extract($args);

	$args_team = array(
		'post_type' => 'elevate_team',
		'posts_per_page' => $count,
		'order' => 'ASC',
		'orderby' => 'menu_order',
		'ignore_sticky_posts' => 1
	);

	//print_r($args_team);

	if($cat_slug != ''){
		$args_team['tax_query'] = array(
			array(
				'taxonomy' => 'team_member_category',
				'field' => 'slug',
				'terms' => $cat_slug
			)
		);
	}

	//print_r($args_team);

	$output = '';

	$query_team = new WP_Query( $args_team );

    if ( $query_team->have_posts() ):
			$output .='<div class="et_section_team_grid_view">';
			$output .='<ul class="team_grid">';
			global $post;
			 while ( $query_team->have_posts() ) : $query_team->the_post();
			 	 $member_id = get_the_ID();
			 	 $output .='<li>';
         $output .='<div class="team_item_wrap">';

          if(has_post_thumbnail()){
            $feature_thumb = wp_get_attachment_image_src(get_post_thumbnail_id(), 'large');
            $output .='<div class="member_photo"><img src="'.$feature_thumb[0].'" title="'.get_the_title().'"  alt="'.get_the_title().'" /></div>';
          }else{
						$output .='<div class="member_photo"><img src="'.get_stylesheet_directory_uri().'/images/staff3.jpg" title="'.get_the_title().'"  alt="'.get_the_title().'" /></div>';
					}

					$nikename = get_the_title();
					if(get_post_meta( $member_id, '_nickname', true )){
							$nikename = esc_html(get_post_meta( $member_id, '_nickname', true ));
					}

					$email = esc_html(get_post_meta( $member_id, '_email', true ));

          $output .='<div class="team_item_content_wrap">';
          $output .='<h3 class="member_title et_pb_module_header">'.get_the_title().'</h3>';
          $output .='<p class="et_pb_member_position">'.esc_html(get_post_meta( $member_id, '_member_designation', true )).'</p>';
          $output .='<a class="et_pb_button et_pb_bg_layout_light et_pb_team_button" href="mailto:'.$email.'">Email '.$nikename.'</a>';
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

add_shortcode( 'team_cat_nav', 'team_member_category_navigation_shortcode' );
function team_member_category_navigation_shortcode($atts) {
    $args = shortcode_atts(array(
      'exclude_cat_ids' => '',
    ), $atts);
	extract($args);
	$output = '';

	$selected_parent_cat_id = '';
	$current_cat_term = '';
	if(is_tax( 'team_member_category' )){
		$current_cat_term = get_query_var("term");
		$term = get_term_by("slug", $current_cat_term, get_query_var("taxonomy") );
		$parent = get_term($term->parent, get_query_var("taxonomy"));
		if(isset($parent->term_id) && ($parent->term_id)){
			$selected_parent_cat_id = $parent->term_id;
		}
	}


	$member_teams = get_terms( array(
		'taxonomy' => 'team_member_category',
		'orderby' => 'term_order',
		'parent' => 0,
		'hide_empty' => false
	) );

	if ( ! empty( $member_teams ) && ! is_wp_error( $member_teams ) ) :
		$output .= '<ul id="team_member_menu" class="member_nav_first">';
		//$output .= $output_all_from_cat;
		foreach( $member_teams as $member_team ) {

				$parent_team_id = $member_team->term_id;
				$sub_teams = get_terms( array(
					'taxonomy' => 'team_member_category',
					'parent' => $parent_team_id,
					'orderby' => 'term_order',
					'hide_empty' => false
				) );
				$has_sub_item_class = '';
				if ( ! empty( $sub_teams ) && ! is_wp_error( $sub_teams ) ) :
					$has_sub_item_class = ' menu-item-has-subitems';
				endif;

				$selected_parent_class = '';
				if(($selected_parent_cat_id != '') && ($selected_parent_cat_id == $parent_team_id)){
						//$selected_parent_class = ' visible';
				}

				if($current_cat_term == $member_team->slug){
						//$selected_parent_class = ' visible';
				}

				$output .= '<li class="member_item_1 '.$has_sub_item_class.' '.$selected_parent_class.'">';
				$output .= '<a href="' . get_term_link( $member_team ) . '" title="' . sprintf( __( 'View all member under %s', 'Divi' ), $member_team->name ) . '">' . $member_team->name . '</a>';


				if ( ! empty( $sub_teams ) && ! is_wp_error( $sub_teams ) ) :
					$output .= '<ul class="sub_team_items">';
					//$output .= $output_all_from_cat;
					foreach( $sub_teams as $sub_team ) {
							$current_cat_term_class = '';
							if($current_cat_term == $sub_team->slug){
									$current_cat_term_class = ' current_item';
							}
							$output .= '<li class="member_item_2 '.$current_cat_term_class.'">';
							$output .= '<a href="' . get_term_link( $sub_team ) . '" title="' . sprintf( __( 'View all team under %s', 'Divi' ), $sub_team->name ) . '">' . $sub_team->name . '</a>';
							$output .= '</li>';
					}
					$output .= '</ul>';
				endif;


				$output .= '</li>';
		}
		$output .= '</ul>';
	endif;

	return $output;

}



function elevate_team_dynamic_script() {
    ?>
    <style type="text/css">
			#team_member_menu .menu-item-has-subitems{ position: relative; }
      #team_member_menu .menu-item-has-subitems > a:after { font-family: 'ETmodules'; text-align: center; speak: none; font-weight: normal; font-variant: normal; text-transform: none; -webkit-font-smoothing: antialiased; position: absolute; }
      #team_member_menu .menu-item-has-subitems > a:after { font-size: 32px; content: "3"; top: 15px; right: 10px; }
      #team_member_menu .menu-item-has-subitems.visible > a:after { content: "2"; }
			#team_member_menu .menu-item-has-subitems.visible > a{ background-color: #c1c1c1; }
      #team_member_menu .menu-item-has-subitems ul.sub_team_items { display: none !important; visibility: hidden !important;  transition: all 1.5s ease-in-out; position: relative; left: 0 !important; top: 0; border-top: none; box-shadow: none; background: none; opacity: 1; }
      #team_member_menu .menu-item-has-subitems.visible > ul.sub_team_items { display: block !important; visibility: visible !important; }
			#team_member_menu .current_item > a{ color: #00b3f9 !important; }
    </style>
    <script type="text/javascript">
    (function($) {
        function setup_collapsible_team_submenus_items() {
            var $menu = $('#team_member_menu'),
                top_level_link = '#team_member_menu .menu-item-has-subitems > a';

            $menu.find('a').each(function() {
                $(this).off('click');

                if ( $(this).is(top_level_link) ) {
                    $(this).attr('href', '#');
                }

                if ( ! $(this).siblings('.sub_team_items').length ) {
                    $(this).on('click', function(event) {
                        $(this).parents('#team_member_menu').trigger('click');
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
                setup_collapsible_team_submenus_items();
            }, 700);
        });

    })(jQuery);
    </script>
    <?php
}
add_action('wp_head', 'elevate_team_dynamic_script');
