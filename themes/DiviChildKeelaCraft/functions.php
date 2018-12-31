<?php
include('like_post.php');

add_action( 'wp_enqueue_scripts', 'enqueue_child_theme_styles');
function enqueue_child_theme_styles() {
  wp_enqueue_style( 'Parents_theme_style', get_template_directory_uri().'/style.css' );
  wp_enqueue_style( 'font-awesome', '//maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css' );
}


add_filter( 'gform_enable_field_label_visibility_settings', '__return_true' );



add_shortcode( 'display-post', 'post_shortcode_init' );
function post_shortcode_init($atts) {
    $args = shortcode_atts(array(
        'category_name' => '',
		'post_count' => '-1'
    ), $atts);
	extract($args);

	$paged = get_query_var( 'paged' ) ? get_query_var( 'paged' ) : 1;

	$args_post = array(
		'post_type' => 'post',
		'posts_per_page' => $post_count,
		'category_name' => $category_name,
		'paged' => $paged,
        'order' => 'DESC',
        'orderby' => 'date',
		'ignore_sticky_posts' => 1
	);

	$query_post = new WP_Query( $args_post );

	$output = '';
    if ( $query_post->have_posts() ):
		$output .='<div class="et_custom_grid_style">';
			global $post;
			 while ( $query_post->have_posts() ) : $query_post->the_post();
			 	 $output .='<article id="'.$post->ID.'" class="et_post clearfix">';
				 $output .='<div class="post_img_area">';
				 if(has_post_thumbnail()){
                 $feature_thumb = wp_get_attachment_image_src(get_post_thumbnail_id(), 'feature_thumb');
				 $output .='<a href="'.get_permalink().'"><img src="'.$feature_thumb[0].'" title="'.get_the_title().'" /></a>';
                  }
				 $output .='</div><!--post_img_area-->';

				 $output .='<div class="post_content_area">';
				 $output .='<p class="cat_name">'.get_the_term_list( $post->ID, 'category', '', ' &nbsp;' ).'</p>';
				 $output .='<h2 class="entry-title"><a href="'.get_permalink().'">'.get_the_title().'</a></h2>';
				 $output .='<p class="post_date">'.get_the_date( 'F j, Y', $post->ID ).'</p>';
				 $output .='<div class="post_excerpt">'.wp_trim_words( get_the_excerpt(), 50 ).'</div>';
				 $output .='<div class="post_social_like_section">';
				 $comment_numbers = add_comment_number_to_keelacraft( $post->ID );
				 $output .= '<span class="single-comment-o"><a href="'.get_comments_link( $post->ID ).'"><i class="fa fa-comment-o"></i></a>'.$comment_numbers.'</span>';
				 $output .= do_shortcode('[ess_post]');
         $output .= '<span class="post_like_count"><a class="rm_like" href="javascript:void(0)" data-rel="'.$post->ID.'"><i class="fa fa-heart-o"></i> '.likeCount($post->ID).'</a></span>';
         $output .='</div><!--.post_social_like_section-->';
				 $output .='</div><!--.post_content_area-->';
				 $output .='</article>';
			 endwhile;
		$output .='</div><!--et_custom_grid_style-->';
		wp_reset_query();
		$output .='<div class="nav-single clearfix">';
		$output .='<div class="nav-next">'.get_next_posts_link('Next Page link', $query_post->max_num_pages).'</div>';
		$output .='<div class="nav-previous">'.get_previous_posts_link('Previous Page link', $query_post->max_num_pages).'</div>';
		$output .='</div><!--.nav-single clearfix-->';
        endif;
	    wp_reset_postdata();
	return $output;


}// End


add_image_size( 'feature_thumb', 518, 346, true );


add_shortcode('display_social_list', 'social_list_shortcode_init');
function social_list_shortcode_init(){
	ob_start();
		?>
		<div class="et_social_list">
			<ul>
				<li><a target="_blank" href="https://www.facebook.com/KEELACAMBROSE/?view_public_for=1747735242135891"><i class="fa fa-facebook" aria-hidden="true"></i></a></li>
				<li><a target="_blank" href="https://twitter.com/keelacraft"><i class="fa fa-twitter" aria-hidden="true"></i></a></li>
				<li><a target="_blank" href="https://instagram.com/keelacraft"><i class="fa fa-instagram" aria-hidden="true"></i></a></li>
				<li><a target="_blank" href="https://www.pinterest.com/keelacraft/"><i class="fa fa-pinterest" aria-hidden="true"></i></a></li>
				<li><a target="_blank" href="https://www.youtube.com/channel/UClxGGEiD4NnoCNwR6jRJ3cw"><i class="fa fa-youtube-play" aria-hidden="true"></i></a></li>
        <li><a target="_blank" href="https://itunes.apple.com/us/podcast/love-fiercely/id1385194570?mt=2"><i class="fa fa-rss" aria-hidden="true"></i></a></li>
			</ul>
		</div>
		<?php
	$output = ob_get_clean();
	return $output;
}

function add_comment_number_to_keelacraft( $post_id ){
  $num_comments = get_comments_number( $post_id );
  if ( $num_comments == 0 ) {
		$comments = '';
	} elseif ( $num_comments > 1 ) {
		$comments = $num_comments;
	} else {
		$comments = __('1');
	}
  return $comments;
}

add_filter( 'the_content', 'add_social_section_after_post_content' );

 function add_social_section_after_post_content( $content ) {
    if ( is_singular('post') ) {
        global $post;
        $post_id = $post->ID;
        $comment_numbers = add_comment_number_to_keelacraft( $post_id );
        $output .= '<div class="post_social_like_section">';
        $output .= '<span class="single-comment-o"><i class="fa fa-comment-o"></i>'.$comment_numbers.'</span>';
        $output .= do_shortcode('[ess_post]');
        $output .= '<span class="post_like_count"><a class="rm_like" href="javascript:void(0)" data-rel="'.$post_id.'"><i class="fa fa-heart-o"></i> '.likeCount($post_id).'</a></span>';
        $output .= '</div>';
        $content = $content . $output;
		}

    return $content;
}


function keelacraft_alter_comment_form_fields($fields) {
    unset($fields['email']);
    unset($fields['url']);
    return $fields;
}
add_filter('comment_form_default_fields', 'keelacraft_alter_comment_form_fields');
