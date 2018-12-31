<?php
/**
 * Include and setup custom metaboxes and fields.
 *
 * @category YourThemeOrPlugin
 * @package  Metaboxes
 * @license  http://www.opensource.org/licenses/gpl-license.php GPL v2.0 (or later)
 * @link     https://github.com/webdevstudios/Custom-Metaboxes-and-Fields-for-WordPress
 */
add_filter( 'cmb_meta_boxes', 'config_quote_mgt_metaboxes' );
/**
 * Define the metabox and field configurations.
 *
 * @param  array $meta_boxes
 * @return array
 */
function config_quote_mgt_metaboxes( array $meta_boxes ) {
	// Start with an underscore to hide fields from custom fields list
	$prefix = '_cmb_';


	/**
	 * Meta box for tab features
	 */

//	global $post;
//	print_r($meta_boxes);
//	echo "Hello";
//	echo $post->ID;
	$last_ran_date = '';
	$current_post_id = '';
	if(isset($_GET['post'])){
		$current_post_id = $_GET['post'];
	}


	if( get_post_meta($current_post_id, '_rmt_quote_email', true) == 'yes' ) {
		$last_ran_date = get_post_meta($current_post_id, '_cmb_last_ran_date', true);
	}else{
		$last_ran_date = 'Not Sent';
	}

	$associate_email_id = '';
	if( get_post_meta($current_post_id, '_rmt_email_id', true) != '') {
		$associate_email_id = get_post_meta($current_post_id, '_rmt_email_id', true);
		$associate_email_id = 'Associate Email ID: '.$associate_email_id;
	}

	$generated_quote_id = '';
	$generated_quote_status = '';
	$generated_quote_edit_url = '';
	if( get_post_meta($current_post_id, '_rmt_quote_id', true)) {
		$generated_quote_id = get_post_meta($current_post_id, '_rmt_quote_id', true);
		$generated_quote_edit_url = get_edit_post_link($generated_quote_id);
		$generated_quote_edit_url = add_query_arg( 'quote_email_id', $current_post_id, $generated_quote_edit_url );
	}
	if( get_post_meta($current_post_id, '_rmt_quote_email', true)) {
		$generated_quote_status = get_post_meta($current_post_id, '_rmt_quote_email', true);
	}

	$rmt_email_secret_key = '';
	if( get_post_meta($current_post_id, '_rmt_email_secret_key', true)) {
		$rmt_email_secret_key = get_post_meta($current_post_id, '_rmt_email_secret_key', true);
	}

	$rmt_mc_campaign = '<span style="color:red;">No email found in mailchimp. Please approve and schedule it or delete this email and create another one for this date.</span>';
	$campaign_id = get_post_meta($generated_quote_id, '_rmt_campaign_id', true);
	if(check_mc_campaign_exist($campaign_id)){
		$rmt_mc_campaign = 'Yes';
		$rmt_mc_campaign .= ' and Status: '. check_mc_campaign_status($campaign_id);
	}


	/**
	 * Meta box for tab features
	 */
	$meta_boxes['quote_metabox'] = array(
		'id'         => 'quote_metabox',
		'title'      => __( 'Quote Specifications', 'cmb' ),
		'pages'      => array( 'quote', ), // Post type
		'context'    => 'normal',
		'priority'   => 'high',
		'show_names' => true, // Show field names on the left
		// 'cmb_styles' => true, // Enqueue the CMB stylesheet on the frontend
		'fields'     => array(
			array(
				'name' => __( 'Thought Behind Quote', 'cmb' ),
				'desc' => __( 'Thought Behind Quote', 'cmb' ),
				'id'   => $prefix . 'thought_behind_quote',
				'type'    => 'wysiwyg',
				'options' => array( 'textarea_rows' => 10, ),
			),
			array(
				'name' => __( 'Date First Used', 'cmb' ),
				'desc' => __( 'YYYY-MM-DD', 'cmb' ),
				'id'   => $prefix . 'date_first_used',
				'type' => 'text_medium',
				//'repeatable' => true,
			),
			array(
				'name' => __( 'Last Ran Date', 'cmb' ),
				'desc' => __( 'Last Ran Date', 'cmb' ),
				'id'   => $prefix . 'last_ran_date',
				'type'    => 'text_medium',
				'default' => $last_ran_date,
			),
			array(
				'name' => __( 'Sent to Email?', 'cmb' ),
				'desc' => __( 'If you want to send this email again leave it blank.<br />'.$associate_email_id.'', 'cmb' ),
				'id'   =>  '_rmt_quote_email',
				'type'    => 'text_small',
				'default' => $generated_quote_status,
			),
		),
	);

	$meta_boxes['email_metabox'] = array(
		'id'         => 'email_metabox',
		'title'      => __( 'Email Specifications', 'cmb' ),
		'pages'      => array( 'emails', ), // Post type
		'context'    => 'normal',
		'priority'   => 'high',
		'show_names' => true, // Show field names on the left
		'fields'     => array(
			array(
				'name' => 'Quote Edit URL:',
				'desc' => '<a href="'.$generated_quote_edit_url.'" target="_blank">'.$generated_quote_edit_url.'</a><br /><br />Secret Key: '.$rmt_email_secret_key.'<br /><br />MC Campaign: '.$rmt_mc_campaign,
				'type' => 'title',
				'id' => $prefix . 'quote_edit_url'
			),
			/*array(
				'name' => __( 'Thought Behind Quote', 'cmb' ),
				'desc' => __( 'Thought Behind Quote. Quote Edit URL: <a href="'.$generated_quote_edit_url.'" target="_blank">'.$generated_quote_edit_url.'</a>', 'cmb' ),
				'id'   => $prefix . 'email_thought_behind_quote',
				'type'    => 'wysiwyg',
				'options' => array( 'textarea_rows' => 10, ),
			),*/
		),
	);


	// Add other metaboxes as needed
	return $meta_boxes;
}
