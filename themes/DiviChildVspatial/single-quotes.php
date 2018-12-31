<?php
get_header();
?>
<div id="main-content">
	<div class="container">
		<div id="content-area" class="clearfix">
			<div id="left-area">

			<?php while ( have_posts() ) : the_post(); ?>

				<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

					<!-- <h1 class="entry-title"><?php the_title(); ?></h1> -->

					<style type="text/css">
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
						.et_pb_gutter.et_pb_gutters1 #left-area{ width: 100%; }
						.quote_social_icon a .fa{ line-height: 30px; }
						.quote_sharing_box {
						    position: absolute;
						    bottom: -70px;
						    left: 45%;
						}
						.quote_sharing_box a {
					    display: inline-block;
					    background: #36C3F0;
					    height: 50px;
					    width: 50px;
					    text-align: center;
					    border-radius: 100%;
					    color: #000;
					    margin-left: 6px;
					}
					.quote_sharing_box a .fa{ color: #FFFFFF; font-size: 36px; line-height: 50px; }
					</style>
					<div class="entry-content">
							<div class="single_quote_wrapper">
									<?php
									global $post;
									 if(get_post_meta($post->ID, 'quote_description', true)):
									 	echo '<div class="single_quote_description">&ldquo;'.get_post_meta($post->ID, 'quote_description', true).'&rdquo;</div>';
									 endif;

									 if(get_post_meta($post->ID, 'quote_author', true)):
									 	 echo '<h3 class="single_author_name">'.get_post_meta($post->ID, 'quote_author', true).'</h3>';
									 endif;

									 if(get_post_meta($post->ID, 'quote_author_title', true)):
									 	echo '<h5 class="single_author_designation">'.get_post_meta($post->ID, 'quote_author_title', true).'</h5>';
									 endif;
									?>
									<div class="quote_sharing_box">
										<?php
										$output = '';
										$share_url = get_permalink($post->ID);
										$share_title = str_replace( ' ', '%20', get_the_title($post->ID));
										$quote_content = get_post_meta($post->ID, 'quote_description', true);
										$share_content = str_replace( ' ', '%20', $quote_content);
										$share_img = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'full' );
										$twitter_url = 'https://twitter.com/intent/tweet?text='.$share_content.'&amp;url='.$share_url.'';

										$facebook_url = 'https://www.facebook.com/sharer/sharer.php?image='.$share_img[0].'&amp;u='.$share_url.'?title='.$share_title.'';

										$output .='<a target="_blank" href="'.$facebook_url.'"><i class="fa fa-facebook" aria-hidden="true"></i></a>';
										$output .='<a target="_blank" href="'.$twitter_url.'"><i class="fa fa-twitter" aria-hidden="true"></i></a>';

										echo $output;
										?>
									</div><!-- quote_sharing_box -->
							</div><!-- single_quote_wrapper -->
					</div> <!-- .entry-content -->


				</article> <!-- .et_pb_post -->

			<?php endwhile; ?>
			</div> <!-- #left-area -->
		</div> <!-- #content-area -->
	</div> <!-- .container -->
</div> <!-- #main-content -->
<?php get_footer(); ?>
