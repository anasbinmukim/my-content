<?php
/**
 * Get the bootstrap!
 */
if ( file_exists( __DIR__ . '/cmb2/init.php' ) ) {
  require_once __DIR__ . '/cmb2/init.php';
} elseif ( file_exists(  __DIR__ . '/CMB2/init.php' ) ) {
  require_once __DIR__ . '/CMB2/init.php';
}

add_action( 'cmb2_admin_init', 'cmb2_companies_metaboxes' );
/**
 * Define the metabox and field configurations.
 * https://github.com/CMB2/CMB2/blob/master/example-functions.php
 */
function cmb2_companies_metaboxes() {

	// Start with an underscore to hide fields from custom fields list
	$prefix = '_cmb2_';

	/**
	 * Initiate the metabox
	 */
	$cmb = new_cmb2_box( array(
		'id'            => 'message_metabox',
		'title'         => __( 'Message More Information', 'cmb2' ),
		'object_types'  => array( 'message'), // Post type
		'context'       => 'normal',
		'priority'      => 'high',
		'show_names'    => true, // Show field names on the left
		// 'cmb_styles' => false, // false to disable the CMB stylesheet
		// 'closed'     => true, // Keep the metabox closed by default
	) );

	// URL text field
	$cmb->add_field( array(
		'name' => __( 'Video URL', 'cmb2' ),
		'desc' => __( 'Enter a Vimeo or YouTube url for the video.', 'cmb2' ),
		'id'   => $prefix . 'video_url',
		'type' => 'text_url',
		'protocols' => array('https'), // Array of allowed protocols
		// 'repeatable' => true,
	) );

	// Email text field
	$cmb->add_field( array(
		'name' => __( 'Speaker', 'cmb2' ),
		'desc' => __( 'Speaker', 'cmb2' ),
		'id'   => $prefix . 'speaker',
		'type' => 'text',
	) );

  $cmb->add_field( array(
    'name' => esc_html__( 'Speaker Notes', 'cmb2' ),
    'desc' => esc_html__( 'Speaker Notes', 'cmb2' ),
    'id'   => $prefix . 'speaker_notes',
    'type' => 'textarea_small',
  ) );

  $cmb->add_field( array(
    'name' => esc_html__( 'Scripture', 'cmb2' ),
    'desc' => esc_html__( 'Scripture', 'cmb2' ),
    'id'   => $prefix . 'scripture',
    'type' => 'textarea_small',
  ) );

	// Add other metaboxes as needed

}
