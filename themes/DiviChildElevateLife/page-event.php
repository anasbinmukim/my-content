<?php
/******
 * Template Name: Event Page
 * @author RM Web Lab
 *****/
get_header();
?>

<div id="main-content">
	<div class="container">
		<div id="content-area" class="clearfix event_details">
			<div class="fullwidth_container">
				<?php while ( have_posts() ) : the_post(); ?>
						<?php the_content(); ?>
				<?php endwhile; ?>

		  </div> <!-- fullwidth_container -->
		</div> <!-- #content-area -->
	</div> <!-- .container -->
</div> <!-- #main-content -->

<?php get_footer(); ?>
