<?php
get_header();
?>

<div id="main-content">
	<div class="container">
		<div id="content-area" class="clearfix news_details">
			<div class="fullwidth_container">
				<?php while ( have_posts() ) : the_post(); ?>
				<?php
					$news_id = get_the_ID();
				?>

				<div class="news_content_wrap">

					<?php
					$output .= '<div class="news_item">';
					$output .= '<h1>'.get_the_title().'</h1>';
					//$output .= '<div class="news_meta">Posted by '.get_the_author($news_id).' On '.get_the_date('l, F jS Y').'</div>';
					$output .= '<div class="news_content">'.get_the_content().'</div>';

					$output .= '<div class="news_social_link"><a href="http://twitter.com/home/?status='.get_permalink().'" class="tweet" rel="external" target="_blank">Tweet</a> |
								<a href="http://www.facebook.com/sharer.php?u='.get_permalink().'" class="fb" rel="external" target="_blank">Post to Facebook</a></div>';
					$output .= '</div>';

					echo $output;

					?>

					<p class="back_to_news" style="text-align:center;"><a href="/news/" class="back button"><i class="fa fa-long-arrow-left" aria-hidden="true"></i> &nbsp;&nbsp;Back to news</a></p>

				</div><!-- message_content_wrap -->

				<?php endwhile; ?>

		  </div> <!-- fullwidth_container -->
		</div> <!-- #content-area -->
	</div> <!-- .container -->
</div> <!-- #main-content -->

<?php get_footer(); ?>
