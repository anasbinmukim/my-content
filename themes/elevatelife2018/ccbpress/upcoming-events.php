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

<div class="section_ccbpress_events">
	<table class="ccbpress_upcoming_event">
		<thead>
			<tr>
				<th scope="col" class="th-col-1 th-image"></th>
				<th scope="col" class="th-col-2 th-date"> Date </th>
				<th scope="col" class="th-col-3 th-time"> Time </th>
				<th scope="col" class="th-col-4 th-event"> Event </th>
				<th scope="col" class="th-col-5"> &nbsp;</th>
			</tr>
		</thead>
		<tbody>
				<!--Loop through all of the events -->
			<?php foreach ($events->events as $event) :  ?>
				<?php $image_url = CCBPress()->ccb->get_image( $event->event_name['ccb_id'], 'event' ); ?>
				<tr>
					<td class="td-col-1-1 td-image">
						<?php if($image_url != ''){ ?>
							<img width="100" src="<?php echo $image_url; ?>" />
						<?php } ?>	
					</td>
					<td class="td-col-1 td-date"><?php echo date( 'M j', strtotime( $event->date ) ); ?></td>
					<td class="td-col-2 td-time"><?php echo $template->get_upcoming_event_time( $event->start_time, $event->end_time ); ?></td>
					<td class="td-col-3 td-event">
						<?php if ( $template->is_single_event_page_set() ) : ?>
							<div class="ccbpress_upcoming_events_graphical_name">
								<h4 class="upcoming_event_title"><a href="<?php echo $template->get_event_url( $event->event_name['ccb_id'] ); ?>"><?php echo $event->event_name; ?></a></h4>
									<a class="event_readmore" href="<?php echo $template->get_event_url( $event->event_name['ccb_id'] ); ?>">Read More</a>
							</div>
						<?php else : ?>
							<div class="ccbpress_upcoming_events_graphical_name">
								<?php echo $event->event_name; ?>
							</div>
						<?php endif; ?>
						<div class="envent_description"><?php //echo $template->recurrence_desc( $event ); ?></div>
					</td>
					<td class="td-col-4">
						<?php if ( $template->is_single_event_page_set() ) : ?>
						   <a class="single_go_icon" href="<?php echo $template->get_event_url( $event->event_name['ccb_id'] ); ?>"><i class="fa fa-angle-right" aria-hidden="true"></i></a>
						 <?php endif; ?>
					</td>
				</tr>
			<?php $found_events++; ?>
		<?php endforeach; ?>
		<!-- end Loop through all of the events-->
		</tbody>
	</table>
	<?php
	/**
	 * Clean the output buffer and save it to a variable
	 */
	$upcoming_events = ob_get_clean(); ?>
	<?php
	/**
	 * Only display the registration form section if we have at least 1 registration form
	 */
	if ( $found_events > 0 ) : ?>
		<?php echo $upcoming_events; ?>
	<?php else : ?>
		<div class="event_not_found">
			<?php echo apply_filters( 'ccbpress_upcoming_events_widget_no_events_text', __('No upcoming events are scheduled', 'ccbpress-events') ); ?>
		</div>
	<?php endif; ?>
	<?php
	/**
	 * Show the calendar link if it's turned on and the Calendar page is set up.
	 */
	if ( $events->widget_options->show_calendar_link == 'show' && $template->is_event_page_set() ) : ?>
		<div class="ccbpress_upcoming_events_calendar_link">
			<a href="<?php echo $template->get_events_url(); ?>"><?php echo apply_filters( 'ccbpress_upcoming_events_widget_view_calendar_text', __('Go To Calendar', 'ccbpress-events' ) ); ?></a>
		</div>
	<?php endif; ?>
</div>
