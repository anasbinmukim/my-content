<?php
	if ( ! is_active_sidebar( 'sidebar-2' ) && ! is_active_sidebar( 'sidebar-3' ) && ! is_active_sidebar( 'sidebar-4' ) && ! is_active_sidebar( 'sidebar-5' ) && ! is_active_sidebar( 'et_pb_widget_area_5' ) )
		return;
?>

<div class="container">
	<div id="footer-widgets" class="clearfix">
	<?php
		$footer_sidebars = array( 'sidebar-2', 'sidebar-3', 'sidebar-4', 'sidebar-5', 'et_pb_widget_area_5' );

		foreach ( $footer_sidebars as $key => $footer_sidebar ) :
			if ( is_active_sidebar( $footer_sidebar ) ) :
				echo '<div class="footer-widget">';
				dynamic_sidebar( $footer_sidebar );
				echo '</div> <!-- end .footer-widget -->';
			endif;
		endforeach;
	?>
	</div> <!-- #footer-widgets -->

</div>	<!-- .container -->