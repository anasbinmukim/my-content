<?php
get_header();
?>

<div id="main-content">
	<?php while ( have_posts() ) : the_post(); ?>
				<?php
					$currnet_story_id = get_the_ID();
					$current_story_content = get_the_content();
					$current_story_content = apply_filters('the_content', $current_story_content);

					//class="fancybox-youtube" for Youtube, class="fancybox-vimeo"
					//http://youtu.be/N_tONWXYviM?hd=1&fs=1&autoplay=1
					$auth_video_url = esc_url(get_post_meta( $currnet_story_id, '_auth_video_url', true ));
					$auth_video_class = 'fancybox-youtube';

					$out_put_top  = '';
					$feature_thumb[0] = '';
					$top_section_bg_photo = '';
					$top_section_css_class = '';
					$show_image_top = true;
					if(($auth_video_url != '') && $show_image_top){
						$feature_thumb = wp_get_attachment_image_src(get_post_thumbnail_id(), 'full');
						if(isset($feature_thumb[0])){
							$top_section_bg_photo = 'background-image:url('.$feature_thumb[0].');';
							$top_section_css_class = 'story_top_section_has_video';
						}
						$out_put_top .='<div class="story_top_section '.$top_section_css_class.'" style="'.$top_section_bg_photo.'">';
							//<img src="'.$feature_thumb[0].'" title="'.get_the_title().'" />';
							$out_put_top .='<div class="container">';
							if(get_post_meta( $currnet_story_id, '_story_custom_title', true )){
								$story_custom_title = esc_html(get_post_meta( $currnet_story_id, '_story_custom_title', true ));
								$out_put_top .='<h1 class="story_title">'.$story_custom_title.'</h1>';
							}else{
								$out_put_top .='<h1 class="story_title">'.get_the_title().'</h1>';
							}
							if($auth_video_url != ''){
								$out_put_top .='<a class="story_player '.$auth_video_class.'" href="'.$auth_video_url.'"><i class="fa fa-play-circle"></i></a>';
							}
						$out_put_top .= '</div>';

						$out_put_top .= '</div>';
						echo $out_put_top;
					}else {
						$out_put_top = '';
						$out_put_top .='<div class="story_top_section_title">';
							$out_put_top .='<div class="container">';
							if(get_post_meta( $currnet_story_id, '_story_custom_title', true )){
								$story_custom_title = esc_html(get_post_meta( $currnet_story_id, '_story_custom_title', true ));
								$out_put_top .='<h1 class="story_title">'.$story_custom_title.'</h1>';
							}else{
								$out_put_top .='<h1 class="story_title">'.get_the_title().'</h1>';
							}
							$out_put_top .= '</div>';
						$out_put_top .= '</div>';
						echo $out_put_top;
					}
				?>
	<?php endwhile; ?>
	<div class="container">
		<div id="content-area" class="clearfix story_details">
			<div class="fullwidth_container">

				<div class="story_content">
						<?php echo $current_story_content; ?>
				</div><!-- story_content -->

		  </div> <!-- fullwidth_container -->
		</div> <!-- #content-area -->
	</div> <!-- .container -->

	<div class="related_stories clearfix" style="display:none;">
		<div class="container_story">
			<h2>Related Stories</h2>
			<?php
			wp_reset_query();
			$args = '';
			$item_array = array();
			$item_cats = get_the_terms($currnet_story_id, 'story_category');
			if($item_cats):
			foreach($item_cats as $item_cat) {
				$item_array[] = $item_cat->term_id;
			}
			endif;

			$args = wp_parse_args($args, array(
				'posts_per_page' => 3,
				'post__not_in' => array($currnet_story_id),
				'ignore_sticky_posts' => 0,
				'post_type' => 'elevate_stories',
				'tax_query' => array(
					array(
						'taxonomy' => 'story_category',
						'field' => 'id',
						'terms' => $item_array
					)
				)
			));
			$query_story = new WP_Query($args);
			$output_related_story = '';
			if ( $query_story->have_posts() ):
				$output_related_story .='<div class="et_section_stores_grid_view">';
				$output_related_story .='<ul>';
				global $post;
				 while ( $query_story->have_posts() ) : $query_story->the_post();
					 $output_related_story .='<li>';
					 $output_related_story .='<div class="item_wrap">';

						if(has_post_thumbnail()){
							$feature_thumb = wp_get_attachment_image_src(get_post_thumbnail_id(), 'large');
							$output_related_story .='<a class="story_thumb" href="'.get_permalink().'"><img src="'.$feature_thumb[0].'" title="'.get_the_title().'" /></a>';
						}

						$output_related_story .='<div class="item_content_wrap">';
						$output_related_story .='<h3 class="story_title"><a href="'.get_permalink().'">'.get_the_title().'</a></h3>';
						//$output_related_story .='<div class="story_excerpt">'.wp_trim_words( get_the_excerpt(), 40 ).'</div>';
						$output_related_story .='<a class="et_pb_button et_pb_bg_layout_light" href="'.get_permalink().'">Read More</a>';
						$output_related_story .='</div>';

					 $output_related_story .='</div>';
					 $output_related_story .='</li>';
				 endwhile;
			$output_related_story .='</ul>';
			$output_related_story .='</div><!--story grid view-->';
				 endif;
			wp_reset_query();
			echo $output_related_story;
			?>
			</div> <!-- .container -->
	</div><!-- related_stories -->
	<div class="story_share_section clearfix">
		<div class="container_story">
			<h2>Share This</h2>
			<?php
				$share_url = get_permalink($currnet_story_id);
				$share_title = str_replace( ' ', '%20', get_the_title($currnet_story_id));
				$share_content = get_the_excerpt($currnet_story_id);
				$share_content = str_replace( ' ', '%20', $share_content);
			?>
			<ul>
					<li><a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo $share_url; ?>" class="btn btn--share color--facebook" target="_blank">Facebook</a></li>
					<li><a href="https://twitter.com/share?url=<?php echo $share_url; ?>&amp;via=elevatelc&amp;text=“<?php echo $share_title; ?>”&amp;hashtags=" class="btn btn--share color--twitter" target="_blank">Twitter</a></li>
					<li><a href="https://plus.google.com/share?url=<?php echo $share_url; ?>" class="btn btn--share color--google" target="_blank">Google+</a></li>
					<li><a href="http://pinterest.com/pin/create/button/?url=<?php echo $share_url; ?>&amp;media=<?php echo $feature_thumb[0]; ?>&amp;description=“<?php echo $share_title; ?>”" class="btn btn--share color--pinterest" target="_blank">Pinterest</a></li>
			</ul>
			</div> <!-- .container -->
	</div><!-- story_share_section -->
</div> <!-- #main-content -->

<?php get_footer(); ?>
