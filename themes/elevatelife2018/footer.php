<?php
if ( ! is_page_template( 'page-template-blank.php' ) ) : ?>

	<?php echo do_shortcode('[et_pb_section global_module="161"][/et_pb_section]'); ?>

			<footer id="main-footer">
				<?php get_sidebar( 'footer' ); ?>


		<?php
			if ( has_nav_menu( 'footer-menu' ) ) : ?>

				<div id="et-footer-nav">
					<div class="container">
						<?php
							wp_nav_menu( array(
								'theme_location' => 'footer-menu',
								'depth'          => '1',
								'menu_class'     => 'bottom-nav',
								'container'      => '',
								'fallback_cb'    => '',
							) );
						?>
					</div>
				</div> <!-- #et-footer-nav -->

			<?php endif; ?>

            	<?php if ( 'on' == et_get_option( 'divi_back_to_top', 'false' ) ) : ?>
   					<div class="container clearfix">
                    	<span class="et_pb_scroll_top"><img class="aligncenter" src="<?php echo get_stylesheet_directory_uri(); ?>/images/back-to-top-icon.png" alt="" />SCROLL  TO TOP</span>
                	</div>
                <?php endif; ?>


				<div id="footer-bottom">
					<div class="container clearfix">
				<?php
					if ( false !== et_get_option( 'show_footer_social_icons', true ) ) {
						get_template_part( 'includes/social_icons', 'footer' );
					}

					echo et_get_footer_credits();
				?>
					</div>	<!-- .container -->
				</div>
			</footer> <!-- #main-footer -->
		</div> <!-- #et-main-area -->

<?php endif; // ! is_page_template( 'page-template-blank.php' ) ?>

	</div> <!-- #page-container -->

	<?php wp_footer(); ?>
</body>
</html>
