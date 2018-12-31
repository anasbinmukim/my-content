<?php



function yl_checkout_url_function($atts, $content = null) {
	ob_start();

	echo get_permalink(get_option('yl_lease_checkout_page'));

	return ob_get_clean();
}
add_shortcode('yl_checkout_url','yl_checkout_url_function');