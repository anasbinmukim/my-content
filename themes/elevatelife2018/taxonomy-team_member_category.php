<?php
get_header();
?>
<div id="main-content">
<div class="cate_header_section">
  <div class="container">
    <h1 class="team_member_cat_title"><?php echo single_term_title( "", false ); ?></h1>
  </div>
</div><!-- cate_header_section -->

	<div class="container">
		<div id="content-area">
			<div id="left-area" class="clearfix">
          <div class="one_fourth">
              <?php echo do_shortcode('[team_cat_nav]'); ?>
          </div>
          <div class="three_fourth et_column_last">
            <?php if(category_description() != ''){ ?>
            <div class="team_description">
            <?php
              echo category_description();
            ?>
            </div><!-- team_description -->
            <?php } ?>
            <?php
            $output = '';
            if ( have_posts() ):
              $output .='<div class="et_section_team_grid_view">';
              $output .='<ul class="team_grid">';
              global $post;
               while ( have_posts() ) : the_post();
                 $member_id = get_the_ID();
                 $output .='<li>';
                 $output .='<div class="team_item_wrap">';

                  if(has_post_thumbnail()){
                    $feature_thumb = wp_get_attachment_image_src(get_post_thumbnail_id(), 'large');
                    $output .='<div class="member_photo"><img src="'.$feature_thumb[0].'" title="'.get_the_title().'"  alt="'.get_the_title().'" /></div>';
                  }else{
                    $output .='<div class="member_photo"><img src="'.get_stylesheet_directory_uri().'/images/staff3.jpg" title="'.get_the_title().'"  alt="'.get_the_title().'" /></div>';
                  }

                  $nikename = get_the_title();
                  if(get_post_meta( $member_id, '_nickname', true )){
                      $nikename = esc_html(get_post_meta( $member_id, '_nickname', true ));
                  }

                  $email = esc_html(get_post_meta( $member_id, '_email', true ));

                  $output .='<div class="team_item_content_wrap">';
                  $output .='<h3 class="member_title et_pb_module_header">'.get_the_title().'</h3>';
                  $output .='<p class="et_pb_member_position">'.esc_html(get_post_meta( $member_id, '_member_designation', true )).'</p>';
                  $output .='<a class="et_pb_button et_pb_bg_layout_light et_pb_team_button" href="mailto:'.$email.'">Email '.$nikename.'</a>';
                  $output .='</div>';

                 $output .='</div>';
                 $output .='</li>';
               endwhile;
            $output .='</ul>';
            $output .='</div><!--story grid view-->';
               endif;

            echo $output;
            ?>
          </div>
			</div> <!-- #left-area -->
		</div> <!-- #content-area -->
	</div> <!-- .container -->
</div> <!-- #main-content -->
<?php get_footer(); ?>
