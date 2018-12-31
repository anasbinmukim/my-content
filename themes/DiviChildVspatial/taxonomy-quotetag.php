<?php get_header(); ?>

<div id="main-content">
	<div class="container">
		<div id="content-area" class="clearfix">
			<div id="left-area">
				<div class="et_section_quotes_archive">
		<?php
		$output = '';
			if ( have_posts() ) :
				$output .='<ul class="quote_archive">';
				while ( have_posts() ) : the_post();
					$post_format = et_pb_post_format(); ?>
						<?php

							global $post;
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
						?>


			<?php
					endwhile;
					$output .='</ul>';
					echo $output;

					if ( function_exists( 'wp_pagenavi' ) )
						wp_pagenavi();
					else
						get_template_part( 'includes/navigation', 'index' );
				else :
					get_template_part( 'includes/no-results', 'index' );
				endif;
			?>
		</div>
			</div> <!-- #left-area -->

			<?php get_sidebar(); ?>
		</div> <!-- #content-area -->
	</div> <!-- .container -->
</div> <!-- #main-content -->

<?php get_footer(); ?>
