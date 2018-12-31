<?php
get_header();
?>
<div id="main-content" class="message_series_main_content">
<div class="section_container container">
<div class="series_header_section  et_pb_row et_pb_equal_columns et_pb_gutters1 clearfix">
  <?php
    $term_id = get_queried_object_id();
    $term_title = single_term_title( "", false );
    $term_description = category_description();
    $term = get_term( $term_id, 'message_series' );
    $term_link = esc_url( get_term_link( $term ) );
    $meta_image = get_wp_term_image($term_id);
    $term_meta = get_option( "taxonomy_$term_id" );
    $term_date = esc_attr( $term_meta['series_date'] ) ? esc_attr( $term_meta['series_date'] ) : '';

    $termf_video_url = '';
    $term_video_url = esc_url( isset($term_meta['series_video_url']) ) ? esc_url( $term_meta['series_video_url'] ) : '';

    $term_video_type  = elevateVideoType($term_video_url);
    if($term_video_type == 'vimeo'){
      if(preg_match("/(https?:\/\/)?(www\.)?(player\.)?vimeo\.com\/([a-z]*\/)*([0-9]{6,11})[?]?.*/", $video_url, $output_array)) {
          $term_vimeo_video_id = $output_array[5];
          $termf_video_url = 'https://player.vimeo.com/video/'.$term_vimeo_video_id.'?autoplay=1';
      }
    }elseif($term_video_type == 'youtube'){
        $term_youtube_video_id = elevate_get_youtube_id_from_url($term_video_url);
        $termf_video_url = 'https://youtu.be/'.$term_youtube_video_id.'?hd=1&fs=1&autoplay=1';
    }



    //$auth_video_url = esc_url(get_post_meta( $currnet_story_id, '_auth_video_url', true ));
    //$auth_video_url = 'https://youtu.be/-JtDbcfsHU0?hd=1&fs=1&autoplay=1';
    $auth_video_url = $termf_video_url;
    $auth_video_class = 'fancybox-youtube';
  ?>
  <div class="series_photo_left et_pb_column et_pb_column_1_2  ">
    <img src="<?php echo $meta_image; ?>" alt="<?php echo single_term_title( "", false ); ?>" title="<?php echo single_term_title( "", false ); ?>" />
    <?php
    if($auth_video_url != ''){
      $out_video ='<a class="message_player '.$auth_video_class.'" href="'.$auth_video_url.'"><i class="fa fa-play-circle"></i></a>';
      echo $out_video;
    }
    ?>
  </div><!-- series_photo_left -->

  <div class="series_info_right et_pb_column et_pb_column_1_2 et-last-child">
      <h1 class="series_cat_title"><?php echo single_term_title( "", false ); ?></h1>
      <!-- <div class="series_date"><?php echo $term_date; ?></div> -->
      <div class="series_desc"><?php echo category_description(); ?></div>
      <a class="et_pb_button" href="#series_message_section">Watch Messages</a>
  </div><!-- series_info_right -->

</div><!-- series_header_section -->
</div><!-- section_container -->

<div class="series_share_section story_share_section clearfix">
  <div class="container_story">
    <h2>Share This</h2>
    <?php
      $share_url = $term_link;
      $share_title = str_replace( ' ', '%20', $term_title);
      $share_content = $term_description;
      $share_content = str_replace( ' ', '%20', $share_content);
    ?>
    <ul>
        <li><a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo $share_url; ?>" class="btn btn--share color--facebook" target="_blank">Facebook</a></li>
        <li><a href="https://twitter.com/share?url=<?php echo $share_url; ?>&amp;via=elevatelc&amp;text=“<?php echo $share_title; ?>”&amp;hashtags=" class="btn btn--share color--twitter" target="_blank">Twitter</a></li>
        <li><a href="https://plus.google.com/share?url=<?php echo $share_url; ?>" class="btn btn--share color--google" target="_blank">Google+</a></li>
        <li><a href="http://pinterest.com/pin/create/button/?url=<?php echo $share_url; ?>&amp;media=<?php echo $feature_thumb[0]; ?>&amp;description=“<?php echo $share_title; ?>”" class="btn btn--share color--pinterest" target="_blank">Pinterest</a></li>
    </ul>
    </div> <!-- .container -->
</div><!-- series_share_section -->

<div id="series_message_section" class="series_message_section clearfix">
  <div class="container_story">
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
          $video_thumb_url = '';
          $message_id = get_the_ID();
          $speaker = get_post_meta($message_id, '_cmb2_speaker', true);
          $video_url = get_post_meta($message_id, '_cmb2_video_url', true);
          $video_type  = elevateVideoType($video_url);
          if($video_type == 'vimeo'){
            if(preg_match("/(https?:\/\/)?(www\.)?(player\.)?vimeo\.com\/([a-z]*\/)*([0-9]{6,11})[?]?.*/", $video_url, $output_array)) {
                $vimeo_video_id = $output_array[5];
                $video_thumb = getVimeoThumb($vimeo_video_id);
                $video_thumb_url = $video_thumb['large'];
                $video_url = 'https://player.vimeo.com/video/'.$vimeo_video_id.'?autoplay=1';
            }
          }elseif($video_type == 'youtube'){
              $youtube_video_id = elevate_get_youtube_id_from_url($video_url);
              $video_url = 'https://youtu.be/'.$youtube_video_id.'?hd=1&fs=1&autoplay=1';
          }else{
            //otheres video
          }

          if(has_post_thumbnail()){
              $feature_thumb = wp_get_attachment_image_src(get_post_thumbnail_id(), 'large');
              $video_thumb_url = $feature_thumb[0];
          }
        ?>
        <div class="message_wrap clearfix">
          <div class="one_fourth message_thumb"><a href="<?php echo $video_url; ?>" class="thumb permalink fancybox-youtube"><span class="msg_count"><?php echo $message_counter; ?></span><img src="<?php echo $video_thumb_url; ?>" width="" height="" title="<?php echo get_the_title(); ?>"></a></div>
          <div class="message_content three_fourth et_column_last">
            <h4><a href="<?php echo $video_url; ?>" class="message_title_link fancybox-youtube"><?php echo get_the_title(); ?></a></h4>
            <div class="message_date_author"><?php echo get_the_date('F jS Y'); ?> -
            <?php
              if($speaker != '')
                echo $speaker;
              else
                echo "Pastor Keith Craft";
            ?>
            </div>

            <div class="message_desc">
                <?php echo get_the_content(); ?>
            </div>
            <a class="et_pb_button fancybox-youtube" href="<?php echo $video_url; ?>">Watch Message</a>
          </div><!-- message_content -->
        </div><!-- message_wrap -->

        <?php
        $message_counter += 1;
      }
      /* Restore original Post Data */
      wp_reset_postdata();
    } else {
      // no posts found
    }
    //$message_counter = $message_counter - 1;
    ?>
  </div> <!-- .container_story -->
</div><!-- series_message_section -->

<div class="series_search_section clearfix">
  <div class="container_story">
      <h3>What Can We Help You Find?</h3>
      <div id="search_custom_btn" class="btn btn-search">Search <i class="fa fa fa-search"></i></div>
  </div> <!-- .container_story -->
</div><!-- series_message_section -->


<script type="text/javascript">
(function($) {

  $( '#search_custom_btn' ).click( function() {
    var $search_container = $( '.et_search_form_container' );

    if ( $search_container.hasClass('et_pb_is_animating') ) {
      return;
    }

    $( '.et_menu_container' ).removeClass( 'et_pb_menu_visible et_pb_no_animation' ).addClass('et_pb_menu_hidden');
    $search_container.removeClass( 'et_pb_search_form_hidden et_pb_no_animation' ).addClass('et_pb_search_visible et_pb_is_animating');
    setTimeout( function() {
      $( '.et_menu_container' ).addClass( 'et_pb_no_animation' );
      $search_container.addClass( 'et_pb_no_animation' ).removeClass('et_pb_is_animating');
    }, 1000);
    $search_container.find( 'input' ).focus();

    elevate_et_set_search_form_css();
  });

  function elevate_et_set_search_form_css() {
    var $search_container = $( '.et_search_form_container' );
    var $body = $( 'body' );
    if ( $search_container.hasClass( 'et_pb_search_visible' ) ) {
      var header_height = $( '#main-header' ).innerHeight(),
        menu_width = $( '#top-menu' ).width(),
        font_size = $( '#top-menu li a' ).css( 'font-size' );
      $search_container.css( { 'height' : header_height + 'px' } );
      $search_container.find( 'input' ).css( 'font-size', font_size );
      if ( ! $body.hasClass( 'et_header_style_left' ) ) {
        $search_container.css( 'max-width', menu_width + 60 );
      } else {
        $search_container.find( 'form' ).css( 'max-width', menu_width + 60 );
      }
    }
  }

})(jQuery);
</script>


</div> <!-- #main-content -->
<?php get_footer(); ?>
