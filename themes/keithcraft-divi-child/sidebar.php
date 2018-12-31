<?php

if ( 'et_full_width_page' === get_post_meta( get_queried_object_id(), '_et_pb_page_layout', true ) )
	return;

if(is_singular('lsi_posts') || is_archive('lsi_posts')){
	if ( is_active_sidebar( 'et_pb_widget_area_8' ) ) : ?>
		<div id="sidebar">
			<?php dynamic_sidebar( 'et_pb_widget_area_8' ); ?>
		</div> <!-- end #sidebar -->
	<?php endif; 
  }
elseif(is_singular('post') || is_home() || is_archive('post')){
	if ( is_active_sidebar( 'et_pb_widget_area_7' ) ) : ?>
		<div id="sidebar">
			<?php dynamic_sidebar( 'et_pb_widget_area_7' ); ?>
		</div> <!-- end #sidebar -->
	<?php endif;
	}
else {
	if ( is_active_sidebar( 'sidebar-1' ) ) : ?>
	<div id="sidebar">
		<?php dynamic_sidebar( 'sidebar-1' ); ?>
	</div> <!-- end #sidebar -->
   <?php endif; 
	}
?>
