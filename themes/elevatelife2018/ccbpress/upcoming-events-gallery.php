<?php
	/**
	 * Lets start our event count at 0
	 */
	$found_events = 0; ?>
	<?php
	/**
	 * Start the output buffer
	 */
	ob_start(); ?>

<?php
$output = '';
$output .='<script type="text/javascript">';
$output .='jQuery(document).ready(function(){';
$output .='jQuery(".event_slider").flexslider({';
$output .='nextText: "",';
$output .='prevText: "",';
$output .= 'controlNav: false,';
$output .= 'slideshow: false,';
$output .='})';
$output .='})';
$output .='</script>';

$output .='<div class="event_slider et_upcoming_events">';
$output .='<ul class="slides">';
	 foreach ($events->events as $event){
		 	$event_id = $event->event_name['ccb_id'];
		 	$image_url = CCBPress()->ccb->get_image( $event_id, 'event' );

			$event_date = date( 'M j', strtotime( $event->date ) );
			$event_time = $template->get_upcoming_event_time( $event->start_time, $event->end_time );
			$event_start_date = '';
			$event_start_time = '';
			$event_end_time = '';

			$event_name = $event->event_name;
			$event_permalink = $template->get_event_url( $event_id );

			$event_description = '';
			$ccbpress_events_db = new CCBPress_Event_Profiles_DB();
			$ccbpress_db_data = $ccbpress_events_db->get( $event_id );
			if ( $ccbpress_db_data->description ) {
				$event_description = $ccbpress_db_data->description;
			}

			$output .= '<li>';
			$output .= '<div class="et_pb_row et_section_events et_pb_equal_columns et_pb_gutters1">';

			$background_image = '';
			$output_photo = '';
			if($image_url != ''){
					$background_image = 'background-image:url('.$image_url.');';
					$output_photo ='<a href="'.$event_permalink.'"><img class="aligncenter event_slider_photo" src="'.$image_url.'" alt="'.$event_name.'" /></a>';
			}

			$output .='<div style="'.$background_image.'"; class="et_pb_column home-event-background colum_with_background et_pb_column_1_2">';
			$output .= $output_photo;
			$output .='</div><!--et_pb_column-->';


			$output .='<div class="et_pb_column colum_no_background et_pb_column_1_2 et-last-child">';
			$output .= '<h2 class="event_title"><a href="'.$event_permalink.'" title="'.$event_name.'">'.$event_name.'</a></h2>';
			$output .= '<div class="event_date">'.$event_date.' - '.$event_time.'</div>';
			$output .= '<div class="event_entry_content">'.wp_trim_words( $event_description, 20 ).'</div>';
			$output .='</div><!--et_pb_column-->';
			$output .='</div><!--et_pb_row-->';
			$output .= '</li>';
	}
	$output .='</ul>';
	$output .='</div><!--testimonial_post_slider-->';

	 echo $output;
?>
