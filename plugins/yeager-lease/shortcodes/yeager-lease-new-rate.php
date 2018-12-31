<?php
function yeager_lease_new_rate_shortcode_func($atts, $content = null) {
	global $post;

	extract(shortcode_atts(array(
		'id' => '0',
	), $atts));
	
	
	ob_start();
	
	// This is going to be used on an invoice 'alert message' or 'memo'. It should return the new
	// rate for the suite/lease being invoiced.
	$today_date = date('Y-m-d', time());

	$client_id = get_post_meta($post->ID, '_client_id', true);
	$user_id = get_post_meta($client_id, '_associated_users', true);
	$args = array(
        'post_type' => 'lease',
        'post_status' => 'all',
        'numberposts' => -1,
        'posts_per_page' => -1,
        'post_status' => array( 'publish' ),
        'meta_query' => array(
        	'relation' => 'AND',
            array(
                'relation' => 'OR',
                array(
                    'key' => '_yl_ninty_day_vacate_date',
                    'value' => $today_date,
                    'compare' => '>=',
                    'type' => 'Date'
                ),
                array(
                    'key' => '_yl_ninty_day_vacate_date',
                    'compare' => 'NOT EXISTS',
                ),
            ),
            array(
                'key' => '_yl_lease_start_date',
                'value' => $today_date,
                'compare' => '<=',
                'type' => 'Date'
            ),
            array(
                'relation' => 'OR',
                array(
                    'key' => '_yl_available_date',
                    'value' => $today_date,
                    'compare' => '>=',
                    'type' => 'Date'
                ),
                array(
                    'key' => '_yl_available_date',
                    'compare' => 'NOT EXISTS',
                ),
            ),
            array(
                'key' => '_yl_lease_user',
                'value' => $user_id,
                'compare' => '==',
            ),
        ),
    );
    $leases = get_posts($args);

    /*
	?>

	Client id: <?php echo $client_id; ?><br>
	User id: <?php echo $user_id; ?><br>
	Leases: <?php echo print_r($leases, true); ?><br>

	<br><br>

	<?php
	*/
 
	if (count($leases) > 0) {
		echo '<div class="rent_increase_block">';
	}
	foreach ($leases as $lease) {
	
		$meta = get_post_meta($lease->ID);
			
		$lease_id = $lease->ID;
		$suite_id = $meta['_yl_product_id'][0];
		$suite_name_str = $meta['_yl_suite_number'][0];
		$cur_rent = $meta['_yl_monthly_rent'][0];
		$new_rent = isset($meta['_yl_new_monthly_rent'][0]) ? $meta['_yl_new_monthly_rent'][0]:''; 
		
		if ($suite_name_str == "-1") {
			$suite_name_str = 'Y-Membership';
		}
		
		//echo print_r($meta, true);
		if($new_rent !=''){
			$message = get_option('yl_rate_increase_default_message').'<br>';
			
			
			$message = str_replace('%suite_number%', '<strong>'.$suite_name_str.'</strong>', $message);
			$message = str_replace('%new_rate%', '<strong>$'.$new_rent.'</strong>', $message);
			
			echo $message;
			echo '<style>
					.rent_increase_block {
					background: #F8F5C1;
					padding: 6px 10px;
					border-radius: 3px;
					line-height: 1.5em;
					}
					</style>';
		}
	}
	if (count($leases) > 0) {
		echo '</div>';
	}

	$yl_return_content = ob_get_contents();
	ob_end_clean();	
	return $yl_return_content;	
	
}
add_shortcode('yeager-lease-new-rate','yeager_lease_new_rate_shortcode_func');