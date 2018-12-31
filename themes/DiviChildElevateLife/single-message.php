<?php
get_header();
?>

<div id="main-content">
	<div class="container">
		<div id="content-area" class="clearfix series_message_details message_details">
			<div class="fullwidth_container">
				<?php while ( have_posts() ) : the_post(); ?>
				<?php
					$message_id = get_the_ID();
					$speaker = get_post_meta($message_id, '_cmb2_speaker', true);
					$term_list = wp_get_post_terms($message_id, 'message_series', array("fields" => "ids"));
					if(is_array($term_list) && (count($term_list) > 0)){
						$term_id = $term_list[0];
					}
				?>

				<div class="message_content_wrap clearfix et_pb_row et_pb_equal_columns">
						<div class="singe_message_content">
								<h1 class="message_title"><?php echo get_the_title($message_id); ?></h1>
								<div class="pastor">
									<?php
										if($speaker != '')
											echo $speaker;
										else
											echo "Pastor Keith Craft";
									?>
								</div>
								<div class="date"><?php echo get_the_date('F jS Y'); ?></div>
						</div><!-- singe_message_content -->

						<div class="message_tab_info">
								<div class="et_pb_module et_pb_tabs  et_pb_tabs_0 et_slide_transition_to_1 et_slide_transition_to_0">
									<ul class="et_pb_tabs_controls clearfix" style="min-height: 36px;">
										<li class="et_pb_tab_0 et_pb_tab_active"><a href="#">Description</a></li>
										<li class="et_pb_tab_1"><a href="#">Speaker Notes</a></li>
										<li class="et_pb_tab_2"><a href="#">Scripture</a></li>
									</ul>
									<div class="et_pb_all_tabs">
										<div class="et_pb_tab clearfix et_pb_active_content et_pb_tab_0 et-pb-active-slide" style="z-index: 1; display: block; opacity: 1;">
											<?php echo get_the_content(); ?>
										</div> <!-- .et_pb_tab -->
										<div class="et_pb_tab clearfix et_pb_tab_1 et-pb-moved-slide" style="z-index: 2; display: none; opacity: 0;">
											<?php echo get_post_meta($message_id, '_cmb2_speaker_notes', true); ?>
										</div> <!-- .et_pb_tab -->
										<div class="et_pb_tab clearfix et_pb_tab_2 et-pb-moved-slide" style="z-index: 2; display: none; opacity: 0;">
											<?php echo get_post_meta($message_id, '_cmb2_scripture', true); ?>
										</div> <!-- .et_pb_tab -->
									</div> <!-- .et_pb_all_tabs -->
								</div>
						</div><!-- message_tab_info -->
				</div><!-- message_content_wrap -->

				<?php endwhile; ?>


				<?php
					$term = get_term( $term_id, 'message_series' );
				?>

				<div class="series-timeline-title-wrap"><h1 class="series-title"><?php echo $term->name; ?></h1></div>

				<div class="timeline series-timeline">
					<div class="container_message">

					<?php
					$args = array(
						'post_type' => 'message',
						'tax_query' => array(
							array(
								'taxonomy' => 'message_series',
								'field'    => 'ID',
								'terms'    => $term_id,
							),
						),
					);
					$query_messages = new WP_Query( $args );
					$message_counter = 1;
					// The Loop
					if ( $query_messages->have_posts() ) {
						while ( $query_messages->have_posts() ) {
							$query_messages->the_post();

								$message_id = get_the_ID();
								$speaker = get_post_meta($message_id, '_cmb2_speaker', true);
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
								$message_counter += 1;
							?>
							<div class="moment">
								<a href="<?php echo get_permalink(); ?>" class="thumb permalink"><img src="<?php echo $video_thumb_url; ?>" width="" height="" title="<?php echo get_the_title(); ?>"></a>
								<div class="opposite">
									<h3><?php echo get_the_title(); ?></h3><br>
									<br>
									<span class="tag"><?php echo get_the_date('F jS Y'); ?></span>
									<br>
									<br>
									<?php
										if($speaker != '')
											echo $speaker;
										else
											echo "Pastor Keith Craft";
									?>
								</div><!-- opposite -->
							</div><!-- moment -->

							<?php
						}
						/* Restore original Post Data */
						wp_reset_postdata();
					} else {
						// no posts found
					}
					?>
					</div><!-- container_message -->

					<div class="line">
						<?php
							$top_position = 42;
							for($ds = 1; $ds < $message_counter; $ds++){
									echo '<div class="dot" style="top: '.$top_position.'px;"></div>';
									$top_position += 244;
							}
						?>
					</div>
			</div>

			<h2 class="serise_grid_title">Message series</h2>

			<?php echo do_shortcode('[elevate-serise-archive]'); ?>


		  </div> <!-- fullwidth_container -->
		</div> <!-- #content-area -->
	</div> <!-- .container -->
</div> <!-- #main-content -->

<?php get_footer(); ?>
