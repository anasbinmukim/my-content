<?php
function elevate_serise_archive_func( $atts, $content = "" ) {
    $message_series = get_terms( 'message_series', array(
      'orderby'    => 'count',
      'hide_empty' => 0
    ) );
    if ( ! empty( $message_series ) && ! is_wp_error( $message_series ) ) {
        $count = count( $message_series );
        $result_arr = array();
        foreach ( $message_series as $term ) {
            $term_link = esc_url( get_term_link( $term ) );
            $term_id = $term->term_id;
            $meta_image = get_wp_term_image($term_id);
            $term_meta = get_option( "taxonomy_$term_id" );
            $term_date = esc_attr( $term_meta['series_date'] ) ? esc_attr( $term_meta['series_date'] ) : '';
            $term_series_display = esc_attr( $term_meta['series_display'] ) ? esc_attr( $term_meta['series_display'] ) : '';
            //$term_date = date('F jS Y', strtotime($term_date));
            if($term_series_display == 'Yes'){
              $result_arr[$term_id] = array(
                'link_url' => $term_link,
                'image_url' => $meta_image,
                'name' => $term->name,
                'date' => $term_date,
              );
            }

        }
    }

    usort($result_arr, 'series_date_compare');
    $descending_series = array_reverse($result_arr, true);
    // echo "<pre>";
    // print_r($descending_series);
    // echo "</pre>";
    if(is_array($descending_series) && (count($descending_series) > 0)){
      $content = '<ul class="serise-archive">';
      $count_series = 1;
      $latest_series = array();
      $latest_series_archive = array();
      foreach ($descending_series as $term_id => $term_value) {
        //save latest series
        if($count_series == 1){
            $latest_series['id'] =  $term_id;
            $latest_series['name'] = $term_value['name'];
            $latest_series['url'] =  $term_value['link_url'];
            $latest_series['image_url'] =  $term_value['image_url'];
            update_option('latest_series', $latest_series);
        }
        if($count_series <= 9){
            $series_archive = array();
            $series_archive['id'] =  $term_id;
            $series_archive['name'] = $term_value['name'];
            $series_archive['url'] =  $term_value['link_url'];
            $series_archive['image_url'] =  $term_value['image_url'];
            $latest_series_archive[] = $series_archive;
        }
        $term_date = $term_value['date'];
        $term_date = date('F jS Y', strtotime($term_date));
        $content .= '<li>';
        $content .= '<a href="'.$term_value['link_url'].'"><img src="'.$term_value['image_url'].'" width="600" /></a>';
        $content .= '<h2><a href="'.$term_value['link_url'].'">'.$term_value['name'].'</a></h2>';
        $content .= '<div class="serise_date"><a href="'.$term_value['link_url'].'">'.$term_date.'</a></div>';
        $content .= '</li>';
        $count_series++;
      }

      update_option('latest_series_archive', $latest_series_archive);

      $content .= '</ul>';
    }

	return $content;
}
add_shortcode( 'elevate-serise-archive', 'elevate_serise_archive_func' );

function series_date_compare($a, $b)
{
    $t1 = strtotime($a['date']);
    $t2 = strtotime($b['date']);
    return $t1 - $t2;
}

function elevate_latest_series_func(){
  $latest_series = get_option('latest_series', true);
  if(is_array($latest_series) && (count($latest_series) > 0)){
    return '<a href="'.$latest_series['url'].'" title="'.$latest_series['name'].'"><img src="'.$latest_series['image_url'].'" /></a>';

  }
}
add_shortcode( 'elevate-latest-series', 'elevate_latest_series_func' );

function elevate_latest_series_archive_func(){
  $latest_series_archive = get_option('latest_series_archive', true);
  $output = '';
  if(is_array($latest_series_archive) && (count($latest_series_archive) > 0)){
    $output .= '<div class="series_menu_thumb series_menu_archive_thumb">';
    foreach ($latest_series_archive as $key => $series_archive) {
      //$output .= '<a href="'.$series_archive['url'].'" title="'.$series_archive['name'].'"><img src="'.$series_archive['image_url'].'" /></a>';
      $output .= '<a href="/series/" title="'.$series_archive['name'].'"><img src="'.$series_archive['image_url'].'" /></a>';
    }
    $output .= '</div>';
  }
  return $output;
}
add_shortcode( 'elevate-latest-series-archive', 'elevate_latest_series_archive_func' );



function elevate_latest_series_name_func(){
  $latest_series = get_option('latest_series', true);
  if(is_array($latest_series) && (count($latest_series) > 0)){
    return $latest_series['name'];

  }
}
add_shortcode( 'elevate-latest-series-name', 'elevate_latest_series_name_func' );


function elevate_latest_message_func(){
  $args = array(
    'post_type' => 'message',
    'posts_per_page' => '1'
  );
  $query_latest_messages = new WP_Query( $args );

  if ( $query_latest_messages->have_posts() ) {
    while ( $query_latest_messages->have_posts() ) {
      $query_latest_messages->the_post();

      $message_id = get_the_ID();
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

      $output = '<a class="play" href="'.get_permalink().'" title="'.get_the_title().'"><img src="'.$video_thumb_url.'" /></a>';
    }
    wp_reset_postdata();
    wp_reset_query();
  }

  return $output;

}
add_shortcode( 'elevate-latest-message', 'elevate_latest_message_func' );

function elevate_latest_message_list_func(){
  $args = array(
    'post_type' => 'message',
    'posts_per_page' => '3'
  );
  $query_latest_messages = new WP_Query( $args );

  if ( $query_latest_messages->have_posts() ) {
    $output = '<ul class="latest_message">';
    while ( $query_latest_messages->have_posts() ) {
      $query_latest_messages->the_post();

      $message_id = get_the_ID();
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
      $speaker = get_post_meta($message_id, '_cmb2_speaker', true);
      $output .= '<li>';
      $output .= '<div class="message_meta"><span>'.get_the_date('m/d').'</span> -- <span>'.$speaker .'</span></div>';
      $output .= '<a href="'.get_permalink().'" title="'.get_the_title().'">'.get_the_title().'</a>';
      $output .= '</li>';
    }
    $output .= '</ul>';
    wp_reset_postdata();
    wp_reset_query();
  }

  return $output;

}
add_shortcode( 'elevate-latest-message-list', 'elevate_latest_message_list_func' );


function elevate_latest_news_list_func(){
  $args = array(
    'post_type' => 'news',
    'posts_per_page' => '5'
  );
  $query_latest_news = new WP_Query( $args );

  if ( $query_latest_news->have_posts() ) {
    $output = '<ul class="latest_news">';
    while ( $query_latest_news->have_posts() ) {
      $query_latest_news->the_post();

      $news_id = get_the_ID();
      $output .= '<li>';
      //$output .= '<span>'.get_the_date('m/d/Y').'</span>';
      $output .= '<a href="'.get_permalink().'" title="'.get_the_title().'">'.get_the_title().'</a>';
      $output .= '</li>';

    }
    $output .= '</ul>';
    wp_reset_postdata();
    wp_reset_query();
  }

  return $output;

}
add_shortcode( 'elevate-latest-news-list', 'elevate_latest_news_list_func' );

function elevate_news_archive_func(){
  $args = array(
    'post_type' => 'news',
    'posts_per_page' => '-1'
  );
  $query_latest_news = new WP_Query( $args );

  if ( $query_latest_news->have_posts() ) {
    $output = '<div class="news_archive">';
    while ( $query_latest_news->have_posts() ) {
      $query_latest_news->the_post();

      $news_id = get_the_ID();
      $output .= '<div class="news_item">';
      $output .= '<h2><a href="'.get_permalink().'">'.get_the_title().'</a></h2>';
      //$output .= '<div class="news_meta">Posted by '.get_the_author($news_id).' On '.get_the_date('l, F jS Y').'</div>';
      $output .= '<div class="news_content">'.get_the_content().'</div>';

      $output .= '<div class="news_social_link"><a href="http://twitter.com/home/?status='.get_permalink().'" class="tweet" rel="external" target="_blank">Tweet</a> |
      			<a href="http://www.facebook.com/sharer.php?u='.get_permalink().'" class="fb" rel="external" target="_blank">Post to Facebook</a></div>';
      $output .= '</div>';

    }
    $output .= '</div>';
    wp_reset_postdata();
    wp_reset_query();
  }

  return $output;

}
add_shortcode( 'elevate-news-archive', 'elevate_news_archive_func' );

function elevate_latest_event_list_func(){
  $args_event_post = array(
    'eventDisplay' => 'upcoming',
    'posts_per_page' => '3',
	);
  // $query_latest_events = new WP_Query( $args );

  $events = tribe_get_events($args_event_post);

  $output = '';

  // The result set may be empty
  if ( empty( $events ) ) {
      $output .= 'Sorry, no upcoming events.';
  }else{
      $output .= '<ul class="latest_events">';
      foreach( $events as $event ) {
        $event_id = get_the_ID($event);
        $event_start_day = tribe_get_start_date($event, false, 'D');
        $event_start_date = tribe_get_start_date($event, false, 'm/d');
        $event_start_time = tribe_get_start_time($event);
        $event_end_time = tribe_get_end_time($event);

        $output .= '<li>';
        $output .= '<div class="event_meta"><span>'.$event_start_date.'</span><span>'.$event_start_day.' '.$event_start_time.'</span></div>';
        $output .= '<a href="'.get_permalink($event).'" title="'.get_the_title($event).'">'.get_the_title($event).'</a>';
        $output .= '</li>';
      }
      $output .= '</ul>';
  }

  return $output;

}
add_shortcode( 'elevate-latest-event-list', 'elevate_latest_event_list_func' );



function elevate_upcoming_event_list_func(){
  $args_event_post = array(
		'posts_per_page' => '-1',
    'eventDisplay' => 'upcoming',

	);
  // $query_latest_events = new WP_Query( $args );

  $events = tribe_get_events($args_event_post);

  $output = '';

  // The result set may be empty
  if ( empty( $events ) ) {
      $output .= 'Sorry, no upcoming events.';
  }else{
      $output .= '<ul class="latest_events upcoming_events">';
      foreach( $events as $event ) {
        $event_id = get_the_ID($event);
        $event_start_day = tribe_get_start_date($event, false, 'D');
        $event_start_date = tribe_get_start_date($event, false, 'm/d');
        $event_start_time = tribe_get_start_time($event);
        $event_end_time = tribe_get_end_time($event);

        $output .= '<li>';
        $output .= '<div class="event_meta"><span>'.$event_start_date.'</span><span>'.$event_start_day.' '.$event_start_time.'</span></div>';
        $output .= '<a href="'.get_permalink($event).'" title="'.get_the_title($event).'">'.get_the_title($event).'</a>';
        $output .= '</li>';
      }
      $output .= '</ul>';
  }

  return $output;

}
add_shortcode( 'elevate-upcoming-event-list', 'elevate_upcoming_event_list_func' );
