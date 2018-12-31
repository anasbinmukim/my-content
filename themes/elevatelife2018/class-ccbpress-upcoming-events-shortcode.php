<?php
/**
 * Events for CCBPress Upcoming Events Shortcode
 *
 * @package CCBPress
 * @subpackage Events for CCBPress
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * CCBPress Upcoming Events Shortcode class
 *
 * @since 1.0.0
 */
class CCBPress_Upcoming_Events_Gallery_Shortcode {

	/**
	 * Class construct
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		$this->actions();
		$this->shortcode();
	}

	/**
	 * Action hooks
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function actions() {
		add_action( 'wp_enqueue_scripts', array( $this, 'styles' ) );
	}

	/**
	 * Register the shortcode
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	private function shortcode() {
		add_shortcode( 'ccbpress_upcoming_events_gallery', array( $this, 'upcoming_events' ) );
	}

	/**
	 * Styles
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function styles() {
		wp_register_style( 'ccbpress-upcoming-events', CCBPRESS_EVENTS_PLUGIN_URL . 'assets/css/upcoming-events.css', array(), '1.0.0' );
	}

	/**
	 * Upcoming Events
	 *
	 * @since 1.0.0
	 *
	 * @param  array  $atts    Attributes.
	 * @param  string $content Content.
	 *
	 * @return string
	 */
	public function upcoming_events( $atts, $content = '' ) {

		wp_enqueue_style( 'ccbpress-upcoming-events' );

		$atts = shortcode_atts( array(
			'date_start'		=> date( 'Y-m-d', current_time( 'timestamp' ) ),
			'date_range'		=> '4 weeks',
			'filter_by'			=> 'group',
			'group_id'			=> null,
			'group_type'		=> null,
			'department'		=> null,
			'campus_id'			=> null,
			'exclude'			=> null,
			'how_many'			=> 5,
			'calendar_link'		=> 'hide',
			'theme'				=> 'text',
			'template'			=> '',
		), $atts );

		if ( 0 < strlen( $atts['template'] ) ) {
			$atts['template'] = '-' . preg_replace( "/[^[:alnum:]]/u", '', $atts['template'] );
		}

		$exclude_events = explode( ',', $atts['exclude'] );

		// Calculate the start date.
		$date_start = $atts['date_start'];

		// For backwards compatibility.
		switch ( $atts['date_range'] ) {

			case 'week':
				$atts['date_range'] = '1 week';
				break;

			case 'month':
				$atts['date_range'] = '4 weeks';
				break;

			case 'year':
				$atts['date_range'] = '52 weeks';
				break;

		}

		switch ( $atts['date_range'] ) {

			case 'today': // The end date is today.
				$date_end = date( 'Y-m-d', current_time( 'timestamp' ) );
				break;

			default: // The end date is sometime in the future.
				$daterange_string = '+' . $atts['date_range'];
				$date_end = date( 'Y-m-d', strtotime( $daterange_string, current_time( 'timestamp' ) ) );
				break;

		}

		$ccbpress_data = CCBPress()->ccb->get( array(
			'cache_lifespan'	=> CCBPress()->ccb->cache_lifespan( 'public_calendar_listing' ),
			'query_string'		=> array(
				'srv'	=> 'public_calendar_listing',
				'date_start'	=> $date_start,
				'date_end'		=> $date_end,
			),
		) );

		// Define the array to hold all found events.
		$found_events = array();

		// Keep track of how many events have been found.
		$how_many_found = 0;

		if ( 0 === (int) $ccbpress_data->response->items['count'] || strlen( $ccbpress_data->response->errors->error ) > 0 ) {

			$found_events = '';

		} else {

			// Loop through the events.
			foreach ( $ccbpress_data->response->items->item as $event ) {

				// Get the event group id.
				$event_group_id		= (string) $event->group_name['ccb_id'];
				$event_group_type	= (string) $event->group_type;
				$event_department	= (string) $event->grouping_name;

				// See if we have found as many as we need.
				if ( $how_many_found < $atts['how_many'] ) {

					switch ( $atts['filter_by'] ) {

						case 'group':
							// Check that it is the correct group id.
							if ( null === $atts['group_id'] || in_array( $event_group_id, explode( ',', $atts['group_id'] ), true ) ) {

								// Get the event id.
								$event_id = (string) $event->event_name['ccb_id'];
								$ccbpress_data->image = CCBPress()->ccb->get_image( $event_id, 'event' );

								// Check that we are not excluding the event.
								if ( ! in_array( $event_id, $exclude_events, true ) ) {

									if ( strtotime( $event->date . ' ' . $event->start_time, current_time( 'timestamp' ) ) > current_time( 'timestamp' ) ) {

										// Add the event to the $found_events array.
										$found_events[ $how_many_found ] = $event;

										// Increase the events found by 1.
										$how_many_found++;

									}
								} // if

							} // if
							break;

						case 'group_type':
							// Check that it is the correct group type.
							if ( null === $atts['group_type'] || in_array( $event_group_type, explode( '||', $atts['group_type'] ), true ) ) {

								// Get the event id.
								$event_id = (string) $event->event_name['ccb_id'];
								$ccbpress_data->image = CCBPress()->ccb->get_image( $event_id, 'event' );

								// Check that we are not excluding the event.
								if ( ! in_array( $event_id, $exclude_events, true ) ) {

									if ( strtotime( $event->date . ' ' . $event->start_time, current_time( 'timestamp' ) ) > current_time( 'timestamp' ) ) {

										// Add the event to the $found_events array.
										$found_events[ $how_many_found ] = $event;

										// Increase the events found by 1.
										$how_many_found++;

									}
								} // if

							} // if
							break;

						case 'department':
							// Check that it is the correct department.
							if ( null === $atts['department'] || in_array( $event_department, explode( '||', $atts['department'] ), true ) ) {

								// Get the event id.
								$event_id = (string) $event->event_name['ccb_id'];
								$ccbpress_data->image = CCBPress()->ccb->get_image( $event_id, 'event' );


								// Check that we are not excluding the event.
								if ( ! in_array( $event_id, $exclude_events, true ) ) {

									if ( strtotime( $event->date . ' ' . $event->start_time, current_time( 'timestamp' ) ) > current_time( 'timestamp' ) ) {

										// Add the event to the $found_events array.
										$found_events[ $how_many_found ] = $event;

										// Increase the events found by 1.
										$how_many_found++;

									}
								} // if

							} // if
							break;

						case 'campus':
							$event_campus_id = null;

							$group_profiles_db = new CCBPress_Group_Profiles_DB();
							$group_profile = $group_profiles_db->get( $event_group_id );
							$event_campus_id = (string) $group_profile->campus_id;

							unset( $group_profiles_db );
							unset( $group_profile );

							if ( null === $atts['campus_id'] || in_array( $event_campus_id, explode( '||', $atts['campus_id'] ), true ) ) {

								// Get the event id.
								$event_id = (string) $event->event_name['ccb_id'];
								$ccbpress_data->image = CCBPress()->ccb->get_image( $event_id, 'event' );

								// Check that we are not excluding the event.
								if ( ! in_array( $event_id, $exclude_events, true ) ) {

									if ( strtotime( $event->date . ' ' . $event->start_time, current_time( 'timestamp' ) ) > current_time( 'timestamp' ) ) {

										// Add the event to the $found_events array.
										$found_events[ $how_many_found ] = $event;

										// Increase the events found by 1.
										$how_many_found++;

									}
								} // if

							}
							break;

					}
				} else {
					break;
				} // if else
			} // foreach

			// Free up some memory.
			unset( $ccbpress_data );

		}

		// Setup the object to hold the events.
		$found_events_object = new stdClass();

		// Set the values passed from the widget options.
		$found_events_object->widget_options						= new stdClass();
		$found_events_object->widget_options->show_calendar_link	= $atts['calendar_link'];
		$found_events_object->widget_options->theme					= $atts['theme'];
		$found_events_object->template								= $atts['template'];

		// Add the events to the object.
		$found_events_object->events = $found_events;

		// Free up some memory.
		unset( $found_events );

		// Echo the event data and apply any filters.
		return $this->get_template( $found_events_object );

	}

	/**
	 * Get the template
	 *
	 * @since 1.0.0
	 *
	 * @param  object $events Events object.
	 *
	 * @return string
	 */
	public function get_template( $events ) {

		ob_start();

		$template = new CCBPress_Upcoming_Events_Template( 'upcoming-events-gallery' . (string) $events->template . '.php', CCBPRESS_EVENTS_PLUGIN_DIR );

		if ( false !== ( $template_path = $template->path() ) ) {
			include( $template_path ); // Include the template.
		} else {
			esc_html_e( 'Template not found. Please reinstall Events for CCBPress.', 'ccbpress-events' );
		}

		// Return the output.
		return ob_get_clean();

	}

}
new CCBPress_Upcoming_Events_Gallery_Shortcode();
