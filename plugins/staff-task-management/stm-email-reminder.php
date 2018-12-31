<?php
add_action( 'wp', 'stm_setup_schedule_email_at_4pm' );
function stm_setup_schedule_email_at_4pm() {
	if ( ! wp_next_scheduled( 'stm_weekly_email_generate_4pm' ) ) {
		wp_schedule_event( time() + (6660), 'daily', 'stm_weekly_email_generate_4pm');
	}
}


add_action( 'stm_weekly_email_generate_4pm', 'do_this_weekly_email_generate_4pm' );
function do_this_weekly_email_generate_4pm() {
	// do something everyday at 4 pm
	$to = 'anasbinmukim@gmail.com';
	$subject = 'Email Reminer at 4 PM';             
	add_filter('wp_mail_content_type',create_function('', 'return "text/html"; '));
	$message = 'HTML messege<br/>'; 
	wp_mail( $to, $subject, $message );		

}



/*add_action( 'wp', 'stm_setup_schedule_email_at_12pm' );
function stm_setup_schedule_email_at_12pm() {
	if ( ! wp_next_scheduled( 'stm_weekly_email_generate_12pm' ) ) {
		wp_schedule_event( time(), 'daily', 'stm_weekly_email_generate_12pm');
	}
}


add_action( 'stm_weekly_email_generate_12pm', 'do_this_weekly_email_generate_12pm' );
function do_this_weekly_email_generate_12pm() {
	// do something everyday at 4 pm
	$to = 'anasbinmukim@gmail.com';
	$subject = 'Email Reminer at 12 PM';             
	add_filter('wp_mail_content_type',create_function('', 'return "text/html"; '));
	$message = 'HTML messege<br/>'; 
	wp_mail( $to, $subject, $message );		

}
*/