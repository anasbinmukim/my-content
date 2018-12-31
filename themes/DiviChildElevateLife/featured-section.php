<?php global $featured_image_section_class; ?>
<?php if(is_singular('message')){ ?>
		<?php
			$message_id = get_the_ID();
			$video_url = get_post_meta($message_id, '_cmb2_video_url', true);
			$video_type  = elevateVideoType($video_url);
			if($video_type == 'vimeo'){
				if(preg_match("/(https?:\/\/)?(www\.)?(player\.)?vimeo\.com\/([a-z]*\/)*([0-9]{6,11})[?]?.*/", $video_url, $output_array)) {
						$vimeo_video_id = $output_array[5];
						$video_thumb = getVimeoThumb($vimeo_video_id);
						$video_thumb_url = $video_thumb['large'];
				}
			}elseif($video_type == 'youtube'){

			}else{
				//otheres video
			}
		?>
		<?php if($vimeo_video_id != ''){ ?>
		<div id="fullwidth_featured_image" class="fullwidth_featured_image">
			<div id="video"><iframe src="https://player.vimeo.com/video/<?php echo $vimeo_video_id; ?>?byline=0&amp;portrait=0&amp;autoplay=1" width="100%" height="640" frameborder="0" webkitAllowFullScreen allowFullScreen></iframe></div>
		</div>
		<?php $featured_image_section_class = 'featured_image_section_enable'; ?>
		<?php } ?>

<?php }elseif ( has_post_thumbnail() && (is_page()) ) { ?>
	<div id="fullwidth_featured_image" class="fullwidth_featured_image">
		<img src="<?php the_post_thumbnail_url('full'); ?>"/>
	</div>
	<?php $featured_image_section_class = 'featured_image_section_enable'; ?>
<?php } ?>

<?php if(is_tax( 'message_series' )){ ?>
			<?php
				$term_id = get_queried_object_id();
				$meta_image = get_wp_term_image($term_id);
				if($meta_image != ''){
			?>
			<div id="fullwidth_featured_image" class="fullwidth_featured_image">
				<img src="<?php echo $meta_image; ?>"/>
			</div>
			<?php $featured_image_section_class = 'featured_image_section_enable'; ?>
			<?php } ?>
<?php } ?>
