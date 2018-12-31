<?php


function debug($msg, $die=false) {
	echo "<pre>";
	print_r($msg);
	echo "</pre>";
	if($die) die();
}


/** 
 * Helper functions
 *
 * @since: 1.0
 */
function yl_get_lease_id_by_invoice_id($invoice_id) {
	return get_post_meta($invoice_id, '_yl_lease_id', true);
}

function yl_get_lm_id_by_lease_id($lease_id) {
	return get_post_meta($lease_id, '_yl_author_id', true);
}

function yl_get_suite_id_by_lease_id($lease_id) {
	return get_post_meta($lease_id, '_yl_product_id', true);
}

function yl_get_invoice_id_by_lease_id($lease_id) {
	return get_post_meta($lease_id, '_yl_invoice_id', true);
}

function yl_get_client_id_by_user_id($user_id) {
	$assoc_clients = SI_Client::get_clients_by_user($user_id);
    $client_id = $assoc_clients[0];
    return $client_id;
}

function yl_get_company_id_by_lease_id($lease_id) {
	$company_id = get_post_meta($lease_id, '_yl_company_id', true);
	return $company_id;
}

function yl_get_company_id_by_invoice_id($invoice_id) {
	$lease_id = yl_get_lease_id_by_invoice_id($invoice_id);
	$company_id = yl_get_company_id_by_lease_id($lease_id);
	return $company_id;
}



global $us_states_full;
$us_states_full = array(
	"Alabama" => "Alabama",	
	"Alaska" => "Alaska", 
	"Arizona" => "Arizona", 
	"Arkansas" => "Arkansas", 
	"California" => "California", 
	"Colorado" => "Colorado", 
	"Connecticut" => "Connecticut", 
	"Delaware" => "Delaware", 
	"District of Columbia" => "District of Columbia", 
	"Florida" => "Florida", 
	"Georgia" => "Georgia", 
	"Hawaii" => "Hawaii", 
	"Idaho" => "Idaho", 
	"Illinois" => "Illinois", 
	"Indiana" => "Indiana", 
	"Iowa" => "Iowa", 
	"Kansas" => "Kansas", 
	"Kentucky" => "Kentucky", 
	"Louisiana" => "Louisiana", 
	"Maine" => "Maine", 
	"Maryland" => "Maryland", 
	"Massachusetts" => "Massachusetts", 
	"Michigan" => "Michigan", 
	"Minnesota" => "Minnesota", 
	"Mississippi" => "Mississippi", 
	"Missouri" => "Missouri", 
	"Montana" => "Montana", 
	"Nebraska" => "Nebraska", 
	"Nevada" => "Nevada", 
	"New Hampshire" => "New Hampshire", 
	"New Jersey" => "New Jersey", 
	"New Mexico" => "New Mexico", 
	"New York" => "New York", 
	"North Carolina" => "North Carolina", 
	"North Dakota" => "North Dakota", 
	"Ohio" => "Ohio", 
	"Oklahoma" => "Oklahoma", 
	"Oregon" => "Oregon", 
	"Pennsylvania" => "Pennsylvania", 
	"Rhode Island" => "Rhode Island", 
	"South Carolina" => "South Carolina", 
	"South Dakota" => "South Dakota", 
	"Tennessee" => "Tennessee", 
	"Texas" => "Texas", 
	"Utah" => "Utah", 
	"Vermont" => "Vermont", 
	"Virginia" => "Virginia", 
	"Washington" => "Washington", 
	"West Virginia" => "West Virginia", 
	"Wisconsin" => "Wisconsin", 
	"Wyoming" => "Wyoming"
);

global $us_states_codes_inverse;
$us_states_codes_inverse = array(
	'Alabama'=>'AL',
	'Alaska'=>'AK',
	'Arizona'=>'AZ',
	'Arkansas'=>'AR',
	'California'=>'CA',
	'Colorado'=>'CO',
	'Connecticut'=>'CT',
	'Delaware'=>'DE',
	'Florida'=>'FL',
	'Georgia'=>'GA',
	'Hawaii'=>'HI',
	'Idaho'=>'ID',
	'Illinois'=>'IL',
	'Indiana'=>'IN',
	'Iowa'=>'IA',
	'Kansas'=>'KS',
	'Kentucky'=>'KY',
	'Louisiana'=>'LA',
	'Maine'=>'ME',
	'Maryland'=>'MD',
	'Massachusetts'=>'MA',
	'Michigan'=>'MI',
	'Minnesota'=>'MN',
	'Mississippi'=>'MS',
	'Missouri'=>'MO',
	'Montana'=>'MT',
	'Nebraska'=>'NE',
	'Nevada'=>'NV',
	'New Hampshire'=>'NH',
	'New Jersey'=>'NJ',
	'New Mexico'=>'NM',
	'New York'=>'NY',
	'North Carolina'=>'NC',
	'North Dakota'=>'ND',
	'Ohio'=>'OH',
	'Oklahoma'=>'OK',
	'Oregon'=>'OR',
	'Pennsylvania'=>'PA',
	'Rhode Island'=>'RI',
	'South Carolina'=>'SC',
	'South Dakota'=>'SD',
	'Tennessee'=>'TN',
	'Texas'=>'TX',
	'Utah'=>'UT',
	'Vermont'=>'VT',
	'Virginia'=>'VA',
	'Washington'=>'WA',
	'West Virginia'=>'WV',
	'Wisconsin'=>'WI',
	'Wyoming'=>'WY'
);

global $us_states_codes;
$us_states_codes = array(
	'AL'=>'Alabama',
	'AK'=>'Alaska',
	'AZ'=>'Arizona',
	'AR'=>'Arkansas',
	'CA'=>'California',
	'CO'=>'Colorado',
	'CT'=>'Connecticut',
	'DE'=>'Delaware',
	'FL'=>'Florida',
	'GA'=>'Georgia',
	'HI'=>'Hawaii',
	'ID'=>'Idaho',
	'IL'=>'Illinois',
	'IN'=>'Indiana',
	'IA'=>'Iowa',
	'KS'=>'Kansas',
	'KY'=>'Kentucky',
	'LA'=>'Louisiana',
	'ME'=>'Maine',
	'MD'=>'Maryland',
	'MA'=>'Massachusetts',
	'MI'=>'Michigan',
	'MN'=>'Minnesota',
	'MS'=>'Mississippi',
	'MO'=>'Missouri',
	'MT'=>'Montana',
	'NE'=>'Nebraska',
	'NV'=>'Nevada',
	'NH'=>'New Hampshire',
	'NJ'=>'New Jersey',
	'NM'=>'New Mexico',
	'NY'=>'New York',
	'NC'=>'North Carolina',
	'ND'=>'North Dakota',
	'OH'=>'Ohio',
	'OK'=>'Oklahoma',
	'OR'=>'Oregon',
	'PA'=>'Pennsylvania',
	'RI'=>'Rhode Island',
	'SC'=>'South Carolina',
	'SD'=>'South Dakota',
	'TN'=>'Tennessee',
	'TX'=>'Texas',
	'UT'=>'Utah',
	'VT'=>'Vermont',
	'VA'=>'Virginia',
	'WA'=>'Washington',
	'WV'=>'West Virginia',
	'WI'=>'Wisconsin',
	'WY'=>'Wyoming'
);


function yl_post_select_field($post_type = 'page', $field_name = '1', $selected_page = '', $class = '') {
	$args = array(
		'post_type' => $post_type,
		'posts_per_page' => -1,
		'orderby' => 'title', 
		'order' => 'ASC'
	);		
	$select_query = new WP_Query( $args );	
	//print_r($select_query);
	if ( $select_query->have_posts() ) {
		echo '<select name="select_'.$post_type.'_'.$field_name.'" id="select_'.$post_type.'_'.$field_name.'" class="'.$class.'">';
		while ( $select_query->have_posts() ) {
			$select_query->the_post();
			if(get_the_title()){
				$selected_value = '';
				if($selected_page == get_the_ID()){	$selected_value = ' selected="selected" '; }
				echo '<option '.$selected_value.' value="'.get_the_ID().'">' . esc_html(get_the_title()) . '</option>';
			}					
		}
		echo '</select>';
		wp_reset_postdata();
	}
}

function yl_post_select_field_name_select($post_type = 'page', $field_name = '1', $selected_page = '') {
	$args = array(
		'post_type' => $post_type,
		'posts_per_page' => -1,
		'orderby' => 'title', 
		'order' => 'ASC'
	);		
	$select_query = new WP_Query( $args );	
	//print_r($select_query);
	if ( $select_query->have_posts() ) {
		echo '<select name="select_'.$post_type.'_'.$field_name.'" id="select_'.$post_type.'_'.$field_name.'">';
		while ( $select_query->have_posts() ) {
			$select_query->the_post();
			if(get_the_title()){
				$selected_value = '';
				if($selected_page == get_the_title()){	$selected_value = ' selected="selected" '; }
				echo '<option '.$selected_value.' value="'.esc_attr(get_the_title()).'">' . esc_html(get_the_title()) . '</option>';
			}					
		}
		echo '</select>';
		wp_reset_postdata();
	}
}





//add_action('template_redirect', 'add_service_fees_to_cart');
function add_service_fees_to_cart() {
	if( is_page('checkout') ) {
		$args_post = array(
			'post_type' => 'lease',
			'posts_per_page' => 1,
			'post_status' => 'draft',
			'meta_key'     => '_yl_product_id',
			'meta_value'   => $_GET['add-to-cart'],
			'meta_compare' => '='
		);
			
		$lease_post = new WP_Query( $args_post );
		if ( $lease_post->have_posts() ) {
			global $post;
			while ( $lease_post->have_posts() ) : $lease_post->the_post();
				if(get_post_meta($post->ID, '_yl_service_fees', true)) {
					global $woocommerce;
					$product_id = get_option('yl_service_fees');
					$checkout_url = $woocommerce->cart->get_checkout_url();
					
					/*wp_redirect( $checkout_url."?add-to-cart=$product_id" );
					exit;*/
				}
			endwhile;
			wp_reset_postdata();
		}
	}
}

if(!function_exists('yeagar_pagination')){
	function yeagar_pagination($pages = '', $range = 2)
	{		
		$output = '';	
		 $showitems = ($range * 2)+1;	
		 global $paged;
		 if(empty($paged)) $paged = 1;	
		 if($pages == '')
		 {
			 global $wp_query;
			 $pages = $wp_query->max_num_pages;
			 if(!$pages)
			 {
				 $pages = 1;
			 }
		 }	
		 if(1 != $pages)
		 {
			 $output .= "<div class='pagination loop-pagination clearfix'>";
			 if($paged > 1) $output .= "<a class='prev page-numbers' href='".get_pagenum_link($paged - 1)."'><span class='page-prev'></span>".__('Previous', 'RMTheme')."</a>";
	
			 for ($i=1; $i <= $pages; $i++)
			 {
				 if (1 != $pages &&( !($i >= $paged+$range+1 || $i <= $paged-$range-1) || $pages <= $showitems ))
				 {
					 $output .= ($paged == $i)? "<span class='page-numbers current'>".$i."</span>":"<a href='".get_pagenum_link($i)."' class='inactive' >".$i."</a>";
				 }
			 }	
			 if ($paged < $pages) $output .= "<a class='next page-numbers' href='".get_pagenum_link($paged + 1)."'>".__('Next', 'RMTheme')."<span class='page-next'></span></a>";
			 $output .= "</div>\n";
		 }
		 
		 return $output;
	}
}

function product_checkout_expire() {
	$args_post = array(
		'post_type' => 'product',
		'posts_per_page' => -1,
		'meta_key'     => '_yl_available',
		'meta_value'   => 'Pending',
		'meta_compare' => '='
	);
		
	$products = new WP_Query( $args_post );
	if ( $products->have_posts() ) {
		global $post;
		while ( $products->have_posts() ) : $products->the_post();
			$add_to_cart_time = get_post_meta($post->ID, '_add_to_cart_time', true);
			$expire_time = ($add_to_cart_time + (60 * 60)); // +1 hour
			if( time() > $expire_time ) {
				// do your staff here
				$subscription_price = get_post_meta($post->ID, '_actual_subscription_price', true);
				update_post_meta($post->ID, '_subscription_price', $subscription_price);
				update_post_meta($post->ID, '_yl_available', 'Yes');
				update_post_meta($post->ID, '_add_to_cart_time', '');
			}
		endwhile;
		wp_reset_postdata();
	}
}

//add_action( 'wp', 'yl_setup_checkout_expire' );
/**
 * On an early action hook, check if the hook is scheduled - if not, schedule it.
 */
function yl_setup_checkout_expire() {
	if ( ! wp_next_scheduled( 'yl_hourly_checkout_expire' ) ) {
		wp_schedule_event( time(), 'hourly', 'yl_hourly_checkout_expire');
	}
}

add_action( 'yl_hourly_checkout_expire', 'yl_do_this_hourly_check' );
/**
 * On the scheduled action hook, run a function.
 */
function yl_do_this_hourly_check() {
	// do something every hour
	product_checkout_expire();
}



function my_account_page_scripts() {
	$vacate_notice_page_url  = get_permalink(get_option('yl_vacate_notice_page'));
?>
	<script type="text/javascript">
		jQuery( document ).ready(function() {
			jQuery(".my_account_subscriptions .button.view").text("Move Out");
			jQuery(".my_account_subscriptions .button.view").attr("href", "javascript:void(0)");
			
			jQuery( ".my_account_subscriptions .button.view" ).on( "click", function() {
				var subscription_id = jQuery(this).parent().siblings('.subscription-id').children("a").text();
				//var subscription_id = jQuery(this).closest(".subscription-id").children("a").text();
				var move_out = confirm("You are about to give notice to move out of "+subscription_id+".  Are you sure you want to do this?");
				if(move_out == true) {
					window.location = "<?php echo $vacate_notice_page_url; ?>?subid="+subscription_id;
				}
			});
		});
	</script>
	
	<style type="text/css">
		.woocommerce_account_subscriptions h2 { display: none; }
	</style>
<?php
}
add_action('wp_head', 'my_account_page_scripts');



function send_vacate_notification_to_bm($lease_id, $subs_id, $suite_id){

 	$email_subject = get_option('bm_va_email_subject');
	$email_message = get_option('bm_va_email_message');	
	
	//$user_email = 'anasbinmukim@gmail.com';
	//$first_name = 'Anas';
	$user_email = get_post_meta($lease_id, '_yl_author_email', true);
	$first_name = get_post_meta($lease_id, '_yl_author_name', true);
	
	$vacate_notice_page_url  = get_permalink(get_option('yl_vacate_notice_page'));
	$vacate_notice_default_page_url  =  add_query_arg( 'subid', $subs_id, $vacate_notice_page_url );
	$step2_redirect_1  =  add_query_arg( 'step2', 'yes', $vacate_notice_default_page_url );
	$step2_redirect_2  =  add_query_arg( 'suite_id', $suite_id, $step2_redirect_1 );
	$step2_redirect  =  add_query_arg( 'lease_id', $lease_id, $step2_redirect_2 );		
			
	$search = array();
	$replace = array();	
	
	$search[] = '%%name%%';
	$replace[] = $first_name;
	
	
	$search[] = '%%vacate-sign-url%%';
	$replace[] = $step2_redirect;
	
	
	$get_message = str_replace($search, $replace, $email_message);	
	$get_message = 	stripslashes($get_message);
	$get_message = nl2br($get_message);  
	
	$headers  = 'MIME-Version: 1.0' . "\r\n";
	$headers .= 'Content-type: text/plain; charset=iso-8859-1' . "\r\n";
	add_filter('wp_mail_content_type',create_function('', 'return "text/html"; '));		
	
	@wp_mail( $user_email, $email_subject, $get_message, $headers );	

}



function send_vacate_confirmation_notification_to_bm($product_id){

 	$email_subject = get_option('bm_va_con_email_subject');
	$email_message = get_option('bm_va_con_email_message');	
	
	/*$user_email = 'anasbinmukim@gmail.com';
	$first_name = 'Anas';
	$suite_name = 'Suite 201';*/
	$args_post = array(
		'post_type' => 'lease',
		'posts_per_page' => 1,
		'orderby' => 'date',
		'order' => 'DESC',
		'post_status' => array( 'publish', 'draft' ),
		'meta_key'     => '_yl_product_id',
		'meta_value'   => $product_id,
		'meta_compare' => '='
	);
		
	$lease_post = new WP_Query( $args_post );
	if ( $lease_post->have_posts() ) {
		global $post;
		while ( $lease_post->have_posts() ) : $lease_post->the_post();
			//$author_id = $post->post_author;
			$lease_id = $post->ID;
		endwhile;
		wp_reset_postdata();
	}

	$user_email = array();
	$user_email[] = get_post_meta($lease_id, '_yl_author_email', true);
	$user_email[] = get_option('yl_accountant_email');

	$first_name = get_post_meta($lease_id, '_yl_author_name', true);
	$suite_name = get_the_title($product_id);
			
	$search = array();
	$replace = array();	
	
	$search[] = '%%name%%';
	$replace[] = $first_name;
	
	
	$search[] = '%%suite-name%%';
	$replace[] = $suite_name;
	
	
	$get_message = str_replace($search, $replace, $email_message);	
	$get_message = 	stripslashes($get_message);
	$get_message = nl2br($get_message);  
	
	$headers  = 'MIME-Version: 1.0' . "\r\n";
	$headers .= 'Content-type: text/plain; charset=iso-8859-1' . "\r\n";
	add_filter('wp_mail_content_type',create_function('', 'return "text/html"; '));			
	@wp_mail( $user_email, $email_subject, $get_message, $headers );	
	

}



function yl_seo_friendly_filename($string){
    $string = str_replace(array('[\', \']'), '', $string);
    $string = preg_replace('/\[.*\]/U', '', $string);
    $string = preg_replace('/&(amp;)?#?[a-z0-9]+;/i', '-', $string);
    $string = htmlentities($string, ENT_COMPAT, 'utf-8');
    $string = preg_replace('/&([a-z])(acute|uml|circ|grave|ring|cedil|slash|tilde|caron|lig|quot|rsquo);/i', '\\1', $string );
    $string = preg_replace(array('/[^a-z0-9]/i', '/[-]+/') , '-', $string);
    return strtolower(trim($string, '-'));
}


function get_yl_lease_id_by_client_id($client_id) {
	$args_post = array(
		'post_type'  => 'lease',
		'posts_per_page' => 1,
		'meta_query' => array(
			array(
				'key'     => '_yl_lease_user',
				'value'   => $client_id,
				'compare' => '=',
			),
		),
	);	
	
	$lease_id = 0;
	wp_reset_query();
		
	$lease_post = new WP_Query( $args_post );
	if ( $lease_post->have_posts() ) {
		global $post;
		while ( $lease_post->have_posts() ) : $lease_post->the_post();
			$lease_id = $post->ID;
		endwhile;
		wp_reset_query();
	}
	
	return $lease_id;
}

add_filter( 'si_mngt_payments_columns', 'add_payment_screen_column' );
function add_payment_screen_column( $columns ) {
	$columns['suite'] = __( 'Suite Number', 'sprout-invoices' );
	$columns['company'] = __( 'Company Name', 'sprout-invoices' );
	$columns['client'] = __( 'Client', 'sprout-invoices' );
	return $columns;
}


add_filter( 'si_mngt_payments_column_suite', 'add_column_suite' );
function add_column_suite( $item ) {
	$payment_id = $item->ID;
	$payment = SI_Payment::get_instance( $payment_id );
	$detail = '';

	$invoice_id = $payment->get_invoice_id();
	$invoice = SI_Invoice::get_instance($invoice_id);
	$invoice_id = $invoice->get_invoice_id();
	
	$client_id = get_post_meta($invoice_id, '_user_id', true);
	$lease_id = get_yl_lease_id_by_client_id($client_id);
	
	$suite_number = get_post_meta($lease_id, '_yl_suite_number', true);
	if($suite_number == -1)
		$detail .= 'Y-Membership';
	else 
		$detail .= $suite_number;	
		
	//update_post_meta($payment_id, '_yls_suite_number', $detail);
	//update_post_meta($invoice_id, '_yls_suite_number', $detail);
	
	return $detail;
}

add_filter( 'si_mngt_payments_column_company', 'add_column_company' );
function add_column_company( $item ) {
	$payment_id = $item->ID;
	$payment = SI_Payment::get_instance( $payment_id );
	$detail = '';

	$invoice_id = $payment->get_invoice_id();
	$invoice = SI_Invoice::get_instance($invoice_id);
	$invoice_id = $invoice->get_invoice_id();
	
	$client_id = get_post_meta($invoice_id, '_user_id', true);
	$lease_id = get_yl_lease_id_by_client_id($client_id);
	
	$company_id = get_post_meta($lease_id, '_yl_company_id', true);
	
	$detail .= get_the_title( $company_id );
	
	//update_post_meta($payment_id, '_yls_company_name', $detail);
	//update_post_meta($invoice_id, '_yls_company_name', $detail);	
	
	return $detail;
}

add_filter( 'si_mngt_payments_column_client', 'add_column_client' );
function add_column_client( $item ) {
	$payment_id = $item->ID;
	$payment = SI_Payment::get_instance( $item->ID );	
	$invoice_id = $payment->get_invoice_id();
	$invoice = SI_Invoice::get_instance($invoice_id);
	$client = $payment->get_client();
	$detail = '';

	if ( is_a( $client, 'SI_Client' ) ) { // Check if purchase wasn't deleted
		$detail .= get_the_title( $client->get_ID() );
	} 
	
	//update_post_meta($payment_id, '_yls_client_name', $detail);	
	//update_post_meta($invoice_id, '_yls_client_name', $detail);	

	return $detail;
}


///add_action('admin_init', 'yl_invoice_search_helper_meta_data');
function yl_invoice_search_helper_meta_data( $query ) {
	$blog_id = get_current_blog_id();
	if($blog_id > 1) {
		$postmeta_table = 'wp_'.$blog_id.'_postmeta';
		$posts_table = 'wp_'.$blog_id.'_posts';
	} else {
		$postmeta_table = $wpdb->postmeta;
		$posts_table = $wpdb->posts;
	}
    global $pagenow, $wpdb;
    if ( is_admin() && $pagenow=='edit.php' && $_GET['post_type']=='sa_invoice') {
	//if(isset($_GET['track']) && ($_GET['track'] == 'yes')){
		//'post_type' => array( 'sa_payment', 'sa_invoice' ),
		
//		$args_post = array(
//			'post_type' => array( 'sa_invoice' ),
//			'posts_per_page' => -1,
//			'meta_key'     => '_yls_filter_ready',
//			'meta_value'   => 'yes',
//			'meta_compare' => 'NOT EXISTS'
//		);
		
		$args_post = array(
			'post_type' => array( 'sa_payment', 'sa_invoice' ),
			'posts_per_page' => -1
		);		
			
		$invoices = new WP_Query( $args_post );
		if ( $invoices->have_posts() ) {
			global $post;
			while ( $invoices->have_posts() ) : $invoices->the_post();
				
				if($post->post_type == 'sa_invoice')	
					$invoice_id = get_post_meta($post->ID, '_invoice_id', true);
					
				if($post->post_type == 'sa_payment')	
					$invoice_id = get_post_meta($post->ID, '_payment_invoice', true);					
			
				
				$client_id = get_post_meta($invoice_id, '_user_id', true);				
				$lease_id = get_yl_lease_id_by_client_id($client_id);			
				
			
				$suite_number = get_post_meta($lease_id, '_yl_suite_number', true);
				$company_id = get_post_meta($lease_id, '_yl_company_id', true);
				$company_name = get_the_title( $company_id );
				$client_id = get_post_meta($invoice_id, '_client_id', true);
				$client_name = get_the_title( $client_id );					
				
				update_post_meta($post->ID, '_yls_suite_number', $suite_number);
				update_post_meta($post->ID, '_yls_company_name', $company_name);
				update_post_meta($post->ID, '_yls_client_name', $client_name);
				update_post_meta($post->ID, '_yls_filter_ready', 'yes');



			endwhile;
			wp_reset_postdata();
		}	
	//}//eof track
	}
}

//add_filter('posts_join', 'sa_invoice_search_join' );
function sa_invoice_search_join ($join){
    global $pagenow, $wpdb;
	$blog_id = get_current_blog_id();
	if($blog_id > 1) {
		$postmeta_table = 'wp_'.$blog_id.'_postmeta';
		$posts_table = 'wp_'.$blog_id.'_posts';
	} else {
		$postmeta_table = $wpdb->postmeta;
		$posts_table = $wpdb->posts;
	}
    // I want the filter only when performing a search on edit page of Custom Post Type named "sa_invoice"
	/* && ($_GET['page']=='sprout-apps/invoice_payments') */
    if ( is_admin() && $pagenow=='edit.php' && ($_GET['post_type']=='sa_invoice' || $_GET['post_type']=='lease') && $_GET['s'] != '') {    
        $join .='LEFT JOIN '.$postmeta_table. ' ON '. $posts_table . '.ID = ' . $postmeta_table . '.post_id ';
    }
    return $join;
}

//add_filter( 'posts_where', 'sa_invoice_search_where' );
function sa_invoice_search_where( $where ){
    global $pagenow, $wpdb;
	$blog_id = get_current_blog_id();
	if($blog_id > 1) {
		$postmeta_table = 'wp_'.$blog_id.'_postmeta';
		$posts_table = 'wp_'.$blog_id.'_posts';
	} else {
		$postmeta_table = $wpdb->postmeta;
		$posts_table = $wpdb->posts;
	}
    // I want the filter only when performing a search on edit page of Custom Post Type named "sa_invoice"
	/* && ($_GET['page']=='sprout-apps/invoice_payments') */
    if ( is_admin() && $pagenow=='edit.php' && ($_GET['post_type']=='sa_invoice' || $_GET['post_type']=='lease') && $_GET['s'] != '') {
        $where = preg_replace(
       "/\(\s*".$posts_table.".post_title\s+LIKE\s*(\'[^\']+\')\s*\)/",
       "(".$posts_table.".post_title LIKE $1) OR (".$postmeta_table.".meta_value LIKE $1)", $where );

		//print_r($where);
    }
	
    return $where;
}

//add_filter( 'posts_groupby', 'sa_invoice_post_limits' );
function sa_invoice_post_limits($groupby) {
    global $pagenow, $wpdb;
	$blog_id = get_current_blog_id();
	if($blog_id > 1) {
		$posts_table = 'wp_'.$blog_id.'_posts';
	} else {
		$posts_table = $wpdb->posts;
	}
    if ( is_admin() && $pagenow == 'edit.php' && ($_GET['post_type']=='sa_invoice' || $_GET['post_type']=='lease') && $_GET['s'] != '' ) {
        $groupby = "$posts_table.ID";
    }
    return $groupby;
}

add_filter( 'manage_sa_invoice_posts_columns', 'add_custom_column_to_invoices' );
function add_custom_column_to_invoices($columns) {
	unset($columns);
	unset( $columns['title'] );

	$columns['suite'] = __( 'Suite(s) #', 'sprout-invoices' );
	$columns['company'] = __( 'Company Name', 'sprout-invoices' );

	$columns['title2'] = __( 'Invoice', 'sprout-invoices' );
	$columns['status'] = __( 'Status', 'sprout-invoices' );
	$columns['total'] = __( 'Paid', 'sprout-invoices' );
	$columns['client'] = __( 'Client', 'sprout-invoices' );
	$columns['doc_link'] = '<div class="dashicons icon-sproutapps-estimates"></div>';

	$columns['due'] = __( 'Issue/Due', 'sprout-invoices' );
	$columns['creator'] = __('Generated', 'sprout-invoices' );
	return $columns;
}

add_action( 'manage_sa_invoice_posts_custom_column' , 'custom_sa_invoice_column', 10, 2 );
function custom_sa_invoice_column( $column, $post_id ) {
    switch ( $column ) {
    	case 'title' :
	    	break;

    	case 'title2' :
    		echo '<a href="'.get_edit_post_link($post_id).'"><strong>'.get_the_title($post_id).'<strong></a>';
    		echo '<div class="row-actions">
    			<span class="edit">
    				<a href="'.get_edit_post_link($post_id).'" aria-label="">Edit</a> | </span><span class="view"><a href="'.get_permalink($post_id).'" rel="permalink" aria-label="">View</a>
    			</span></div>';
    		break;

        case 'suite' :
			//echo $post_id;
			$lease_id = get_post_meta($post_id, '_yl_lease_id', true);			
			$client_id = get_post_meta($post_id, '_user_id', true);			
			$suite_number = get_post_meta($lease_id, '_yl_suite_number', true);			
			$invoice_due_date = get_post_meta($post_id, '_due_date', true);

			//echo '<pre>';
			$rent_acc_cat_id = get_option('yl_category_id_rent');
			$line_items = get_post_meta($post_id, '_doc_line_items', true);

			$line_suites = array();
			if(is_array($line_items) && count($line_items) > 0){
				foreach ($line_items as $line) {
					if (isset($line['accounting_cat']) && ($line['accounting_cat'] == $rent_acc_cat_id)) {
						$line_suites[] = str_replace("Monthly Rent for ", "", $line['desc']);
					}
				}
			}
			//print_r($line_items);
			//echo '</pre>';
			
			if($suite_number == -1)
				echo 'Y-Membership<br />';
			else 
				if (count($line_suites) > 1) {
					echo implode('<br>', $line_suites)."<br>";
				}
				else {
					echo $suite_number."<br />";
				}
			if(isset($invoice_due_date) && ($invoice_due_date != ''))	
				echo "<small>Due: ". date("Y-m-d", $invoice_due_date) .'</small>';
            break;

        case 'company' :
			$lease_id = get_post_meta($post_id, '_yl_lease_id', true);
			$client_id = get_post_meta($post_id, '_user_id', true);			
			//$lease_id = get_yl_lease_id_by_client_id($client_id);			
			$company_id = get_post_meta($lease_id, '_yl_company_id', true);
			echo $company_name = get_the_title( $company_id );
            break;

        case 'due' :
	        $invoice_due_date = get_post_meta($post_id, '_due_date', true);
	        $invoice_issued_date = strtotime(get_the_date('', $post_id));
        	echo 'Issued: '.date('Y-m-d', $invoice_issued_date).'<br>';
			if(!empty($invoice_due_date)){
        		
				echo 'Due: '.date('Y-m-d', $invoice_due_date);
			}
        	if ($invoice_issued_date > $invoice_due_date) {
        		echo '<br><br><strong>BUGGED!</strong>';
        	}
        	break;

       	case 'creator' :
       		$meta = get_post_meta($post_id, '_yl_generated_in_batch_of', true);
       		if ($meta) {
       			echo 'x';
       		}
       		break;

    }
}

//add_action('admin_init', 'yl_lease_search_helper_meta_data');
function yl_lease_search_helper_meta_data() {
    global $pagenow;
    if ( is_admin() && $pagenow=='edit.php' && $_GET['post_type']=='lease') {
		$args_post = array(
			'post_type' => 'lease',
			'posts_per_page' => -1,
			'meta_key'     => '_yls_filter_ready',
			'meta_value'   => 'yes',
			'meta_compare' => 'NOT EXISTS'
		);
			
		$leases = new WP_Query( $args_post );
		if ( $leases->have_posts() ) {
			global $post;
			while ( $leases->have_posts() ) : $leases->the_post();
				$company_id = get_post_meta($post->ID, '_yl_company_id', true);
				$company_name = get_the_title( $company_id );
				
				update_post_meta($post->ID, '_yls_company_name', $company_name);
				update_post_meta($post->ID, '_yls_filter_ready', 'yes');

			endwhile;
			wp_reset_postdata();
		}	
	}
}

function yl_lease_company_edit_callback(){
	$leas_id = $_POST['leas_id'];
	$tinfo_copy_machine = esc_html($_POST['tinfo_copy_machine']);
	$yl_tinfo_user_name = esc_html($_POST['yl_tinfo_user_name']);	
	$yl_tinfo_password = esc_html($_POST['yl_tinfo_password']);
	$yl_tinfo_postage_password = esc_html($_POST['yl_tinfo_postage_password']);
	$yl_tinfo_account_number = esc_html($_POST['yl_tinfo_account_number']);
	$yl_tinfo_fob_1_name = esc_html($_POST['yl_tinfo_fob_1_name']);
	$yl_tinfo_fob_1_no = esc_html($_POST['yl_tinfo_fob_1_no']);
	$yl_tinfo_fob_2_name = esc_html($_POST['yl_tinfo_fob_2_name']);
	$yl_tinfo_fob_2_no = esc_html($_POST['yl_tinfo_fob_2_no']);
	$yl_tinfo_fob_3_name = esc_html($_POST['yl_tinfo_fob_3_name']);
	$yl_tinfo_fob_3_no = esc_html($_POST['yl_tinfo_fob_3_no']);
	$yl_tinfo_name_as_you_wish = esc_html($_POST['yl_tinfo_name_as_you_wish']);
	
	update_post_meta($leas_id, '_yl_tinfo_copy_machine', $tinfo_copy_machine);
	update_post_meta($leas_id, '_yl_tinfo_user_name', $yl_tinfo_user_name);
	update_post_meta($leas_id, '_yl_tinfo_password', $yl_tinfo_password);
	update_post_meta($leas_id, '_yl_tinfo_postage_password', $yl_tinfo_postage_password);
	update_post_meta($leas_id, '_yl_tinfo_account_number', $yl_tinfo_account_number);
	update_post_meta($leas_id, '_yl_tinfo_fob_1_name', $yl_tinfo_fob_1_name);
	update_post_meta($leas_id, '_yl_tinfo_fob_1_no', $yl_tinfo_fob_1_no);
	update_post_meta($leas_id, '_yl_tinfo_fob_2_name', $yl_tinfo_fob_2_name);
	update_post_meta($leas_id, '_yl_tinfo_fob_2_no', $yl_tinfo_fob_2_no);
	update_post_meta($leas_id, '_yl_tinfo_fob_3_name', $yl_tinfo_fob_3_name);
	update_post_meta($leas_id, '_yl_tinfo_fob_3_no', $yl_tinfo_fob_3_no);
	update_post_meta($leas_id, '_yl_tinfo_name_as_you_wish', $yl_tinfo_name_as_you_wish);
	
	echo json_encode(array("msg" => "Updated!"));
	exit;	

}

add_action( 'wp_ajax_yl_lease_company_edit', 'yl_lease_company_edit_callback' );
add_action( 'wp_ajax_nopriv_yl_lease_company_edit', 'yl_lease_company_edit_callback' );


global $yl_active_lease_for_suite, $yl_latest_active_leases, $yl_active_lease_in_suite;

add_action('init', 'yl_active_lease_for_suite_build');
function yl_active_lease_for_suite_build(){
	global $yl_latest_active_leases, $yl_active_lease_in_suite;
	$yl_latest_active_leases = array();

	$args_lease = array(
		'post_type' 		=> 'lease',
		'posts_per_page'	=> -1,
		'orderby'   		=> 'post_date',
		'order'     		=> 'DESC',
		'post_status'		=> 'publish'
	);
		
	$today_date = date("Y-m-d");
	$active_lease_id = 0;			
	$active_lease_ids = array();
	$active_suite_lease_ids = array();
	$lease_ids = array();
	$lease_suites_ids = array();
	$lease_vacate_info = array();
	$lease_post = new WP_Query( $args_lease );
	if ( $lease_post->have_posts() ) {
		while ( $lease_post->have_posts() ) : $lease_post->the_post();
			//get lease id
			$lease_id = get_the_ID();
			//get lease ids array
			$lease_ids[] = get_the_ID();
			//get suite ids
			if(get_post_meta($lease_id, '_yl_suite_number', true) == -1){			
				$lease_suites_ids[$lease_id] = get_post_meta($lease_id, '_yl_suite_number', true);
				//get lease suite id
				$suite_id = get_post_meta($lease_id, '_yl_suite_number', true);				
			}else{
				$lease_suites_ids[$lease_id] = get_post_meta($lease_id, '_yl_product_id', true);
				//get lease suite id
				$suite_id = get_post_meta($lease_id, '_yl_product_id', true);				
			}
			//get_vacate_info
			$lease_vacate_info[$lease_id] = get_post_meta($lease_id, '_yl_ninty_day_vacate_date', true);
			//get lease start date
			$lease_start_date = get_post_meta($lease_id, '_yl_lease_start_date', true);
			//get lease start date
			$lease_vacate_date = get_post_meta($lease_id, '_yl_ninty_day_vacate_date', true);
						
			
			//check if already have a lease for suite
			if($suite_id == -1){
				//lease for Y-Membership
				if($lease_vacate_date == ""){
					//$active_lease_ids[] = $lease_id;
					$active_suite_lease_ids[$lease_id] = $suite_id;
				}elseif(($lease_vacate_date != '') && ($lease_vacate_date > $today_date)){
					//$active_lease_ids[] = $lease_id;
					$active_suite_lease_ids[$lease_id] = $suite_id;
				}	
			}elseif($suite_id > 0){
				
				if($lease_vacate_date == ""){
					//$active_lease_ids[] = $lease_id;
					$active_suite_lease_ids[$lease_id] = $suite_id;
				}elseif(($lease_vacate_date != '') && ($lease_vacate_date > $today_date)){
					//$active_lease_ids[] = $lease_id;	
					$active_suite_lease_ids[$lease_id] = $suite_id;			
				}	
				
			}
		endwhile;
		wp_reset_query();
	}
	
	foreach($active_suite_lease_ids as $q_lease_id => $q_suite_id){
		if($q_suite_id == -1){
			//Y-Membership
			$active_lease_ids[] = $q_lease_id;
			$yl_active_lease_in_suite[$q_lease_id] = $q_suite_id;
		}else{
			//find highest lease
			$highest_lease = array();
			$highest_lease_id = 0;
			foreach($active_suite_lease_ids as $qq_lease_id => $qq_suite_id){
				if($q_suite_id == $qq_suite_id){
					$highest_lease[] = $qq_lease_id;
				}
			}
			$highest_lease_id = max($highest_lease);
			
			if (! in_array($highest_lease_id, $active_lease_ids)) {
				$active_lease_ids[] = $highest_lease_id;
				$height_lease_q_suite_id = get_post_meta($highest_lease_id, '_yl_product_id', true);
				$yl_active_lease_in_suite[$highest_lease_id] = $height_lease_q_suite_id;
			}			
		}
	}


	$yl_latest_active_leases = $active_lease_ids;
}





add_action( 'wp_ajax_admin_set_new_rate', 'yl_admin_set_new_rate_ajax_call' );

function yl_admin_set_new_rate_ajax_call() {
	global $wpdb;

	$new_rate_perc = $_POST['rate_increase'];

	// Get all suites (active and inactive) and set the new rate.
	// Also get all active leases and set the new rate
	if ((!$new_rate_perc) || ($new_rate_perc == 0)) {
		echo 'New rate % should be higher than 0';
		wp_die();
	}

	// SUITES
	$args = array(
		'post_type' 	=> 'suites',
		'orderby' 		=> 'post_title',
		'order'			=> 'ASC',
		'numberposts'	=> -1,
		'posts_per_page' => -1,
		'post_status' => array('publish', 'pending', 'draft', 'auto-draft', 'future', 'private', 'inherit', 'trash')
	);
	$query = new WP_Query( $args );

	foreach ($query->posts as $post) {
		$rate = get_post_meta($post->ID, '_yl_rent_rate', true);
		$new_rate = floor($rate+($rate/100*$new_rate_perc));
		update_post_meta($post->ID, '_yl_new_rent_rate', $new_rate);
	}
	echo 'New rate was set for all suites.<br>';

	// LEASES
	// NOTE: In the future we might want to change this so only 'active' leases are searched for.
	//       As of right now, i didn't see this to be necessary.
	$args = array(
		'post_type' 	=> 'lease',
		'orderby' 		=> 'post_title',
		'order'			=> 'ASC',
		'numberposts'	=> -1,
		'posts_per_page' => -1,
		'post_status' => array('publish', 'pending', 'draft', 'auto-draft', 'future', 'private', 'inherit', 'trash')    
	);
	$query = new WP_Query( $args );

	foreach ($query->posts as $post) {
		$rate = get_post_meta($post->ID, '_yl_monthly_rent', true);
		$new_rate = floor($rate+($rate/100*$new_rate_perc));
		update_post_meta($post->ID, '_yl_new_monthly_rent', $new_rate);
		//echo $rate.' - '.$new_rate;
		//echo '<br><br>';
	}
	echo 'New monthly rate was set for all leases.<br>';

	// Let's update the option
	update_option( 'yl_new_monthly_rate_perc_value', $_POST['rate_increase'] );	

	wp_die(); // this is required to terminate immediately and return a proper response
}



add_action( 'wp_ajax_admin_roll_out_new_rate', 'yl_admin_roll_out_new_rate_ajax_call' );

function yl_admin_roll_out_new_rate_ajax_call() {
	global $wpdb;

	// SUITES
	$args = array(
		'post_type' 	=> 'suites',
		'orderby' 		=> 'post_title',
		'order'			=> 'ASC',
		'numberposts'	=> -1,
		'posts_per_page' => -1,
		'post_status' => array('publish', 'pending', 'draft', 'auto-draft', 'future', 'private', 'inherit', 'trash')
	);
	$query = new WP_Query( $args );

	foreach ($query->posts as $post) {
		$new_rate = get_post_meta($post->ID, '_yl_new_rent_rate', true);
		if (($new_rate) && ($new_rate > 0)) {
			update_post_meta($post->ID, '_yl_rent_rate', $new_rate);
			delete_post_meta($post->ID, '_yl_new_rent_rate');
		}
	}

	
	// LEASES
	// NOTE: In the future we might want to change this so only 'active' leases are searched for.
	//       As of right now, i didn't see this to be necessary.
	$args = array(
		'post_type' 	=> 'lease',
		'orderby' 		=> 'post_title',
		'order'			=> 'ASC',
		'numberposts'	=> -1,
		'posts_per_page' => -1,
		'post_status' => array('publish', 'pending', 'draft', 'auto-draft', 'future', 'private', 'inherit', 'trash')    
	);
	$query = new WP_Query( $args );

	foreach ($query->posts as $post) {
		$new_rate = get_post_meta($post->ID, '_yl_new_monthly_rent', true);
		if (($new_rate) && ($new_rate > 0)) {
			update_post_meta($post->ID, '_yl_monthly_rent', $new_rate);
			delete_post_meta($post->ID, '_yl_new_monthly_rent');
		}
	}

	echo 'ok';
	delete_option( 'yl_new_monthly_rate_perc_value' );
	wp_die(); // this is required to terminate immediately and return a proper response
}


function get_yl_my_company_info($current_user_id) {
    $args = array(
        'post_type' => 'lease',
		'posts_per_page' => 1,
        'meta_query' => array(
            array(
                'key' => '_yl_lease_user',
                'value' => $current_user_id,
                'compare' => '='
            ),
        ),
    );
    $query = new WP_Query($args);

    $lease_company = array();
    $lease_id = '';
    if ($query->have_posts()) {
        global $post;
        while ($query->have_posts()) {
            $query->the_post();
			$lease_company_id = get_post_meta($post->ID, '_yl_company_id', true);
			$lease_company['ID'] = $lease_company_id;
			$lease_company['company_name'] = get_the_title($lease_company_id);
			$lease_company['amc_credits'] = get_post_meta($lease_company_id, '_yl_amc_credits', true);
			$lease_company['pc_credits'] = get_post_meta($lease_company_id, '_yl_pc_credits', true);
        }
        wp_reset_query();
    }

    return $lease_company;
}


function count_leases_no_storage_or_ymembership($leases) {
	$to_return = array();
    foreach ($leases as $lease_id) {
        $_yl_is_storage = get_post_meta($lease_id, '_yl_is_storage', true);
        $_yl_suite_number = get_post_meta($lease_id, '_yl_suite_number', true);
        if ($_yl_is_storage == 1 || strpos($_yl_suite_number, 'torage') != false || $_yl_suite_number == 'Y-Membership') {
            
        }
        else {
        	$to_return[] = $lease_id;
        }
    }
    return count($to_return);
}

function yl_is_autopay_setup($client_id){
	if (is_plugin_active('sprout-invoices-addon-auto-billing/auto-billing.php')) {
		$payment_profiles = Sprout_Billings_Profiles::get_client_payment_profiles( $client_id );
		if ( ! empty( $payment_profiles ) ){
				echo "<i title=\"Auto pay enabled\" class=\"fa fa-usd icon_has_autopay\" aria-hidden=\"true\"></i>";
		}
	}
}

class Add_emails_for_staging {

	/**
	 * Class constructor
	 */
	public function __construct() {
		add_action( 'admin_init' , array( $this , 'register_fields' ) );
	}

	/**
	 * Add new fields to wp-admin/options-general.php page
	 */
	public function register_fields() {
		register_setting( 'general', 'add_emails_for_staging', 'esc_attr' );
		add_settings_field(
			'add_emails_for_staging',
			'<label for="add_emails_for_staging">' . 'Enter comma separated emails to recieve all emails'  . '</label>',
			array( $this, 'add_emails_for_staging_callback' ),
			'general'
		);
	}

	/**
	 * HTML for extra settings
	 */
	public function add_emails_for_staging_callback() {
		$value = get_option( 'add_emails_for_staging', '' );
		echo '<input type="text"  class="regular-text" id="add_emails_for_staging" name="add_emails_for_staging" value="' . esc_attr( $value ) . '" />';
	}

}
new Add_emails_for_staging();

add_filter( 'wp_mail', 'restrict_emails_user' );
//echo "<pre>";print_r($_SERVER);

function restrict_emails_user( $args ) {
    $ip = array(
    '127.0.0.1',
    '::1'
    );
	if(strpos(home_url(),'localhost') || strpos(home_url(),'staging') || in_array($_SERVER['REMOTE_ADDR'], $ip) || is_wpe_snapshot() ){
        $to = get_option('add_emails_for_staging');
        $new_wp_mail = array(
            'to'          =>  $to,
            'subject'     => $args['subject'],
            'message'     => $args['message'],
            'headers'     => $args['headers'],
            'attachments' => $args['attachments'],
        );
    }
	 
	return $new_wp_mail;
}

add_action('init', 'set_sprout_username_to_test_on_staging');
function set_sprout_username_to_test_on_staging(){
     $ip = array(
    '127.0.0.1',
    '::1'
    );
    if(strpos(home_url(),'localhost') || strpos(home_url(),'staging') || in_array($_SERVER['REMOTE_ADDR'], $ip) || is_wpe_snapshot() ){
        //echo "h1";die;
        global $wpdb;
        $users = get_user_by('login','cedaradmin');
        $staging_updated = get_user_meta($users->ID,'update_staging', true);
        if(empty($staging_updated)){
            $blog_id = get_current_blog_id();
            $wpdb->query('UPDATE wp_users SET user_email = concat(user_email,"1")');
            $sites = wp_get_sites();
            $all_blogs_id=array();
            $removed_ids=array(1,20,19,6);
            foreach ($sites as $key => $current_blog) {

                if(!in_array($current_blog['blog_id'], $removed_ids))
                {
                array_push($all_blogs_id, $current_blog['blog_id']);
                }
            }
          
            foreach($all_blogs_id as $blog){
                switch_to_blog($blog);
                $wpdb->query("UPDATE ".$wpdb->prefix."postmeta SET meta_value=concat(meta_value,'_test') WHERE meta_key='si_authnet_cim_profile_id_v92'");
                update_option('si_nmi_username','Yeagertest');
                update_option('si_nmi_password','Yeager23');
            }
            switch_to_blog($blog_id);
            update_user_meta($users->ID,'update_staging',1);
        }
       /* $user_name = get_option('si_nmi_username');
        if(strpos($user_name,'test')<1){
            update_option('si_nmi_username','Yeagertest');
            update_option('si_nmi_password','Yeager23');
        }*/
        
        
        
    }
}
function yl_print_r($data){
	echo '<pre>';
	print_r($data);
	echo '</pre>';
}
if(!empty($_REQUEST['update_calender_credits'])){
	add_action('wp','yl_update_calender_credits_for_all_clients');
}
function yl_update_calender_credits_for_all_clients(){
    $all_blogs_id=array();
    $sites = wp_get_sites();
    $removed_ids=array(1,20,19,6);
    foreach ($sites as $key => $current_blog) {
        if(!in_array($current_blog['blog_id'], $removed_ids)) {
            yl_update_calendar_credit_for_single_blog($current_blog['blog_id'], 0);
        }
    }
	echo 'credits successfully updated for '.date('M-Y'); exit;
	
}
function yl_update_calendar_credit_for_single_blog($blog_id = 0, $lease_id=0 ){
	if(!empty($blog_id)){
		switch_to_blog($blog_id);
	}
    $last_month = str_pad((int)date('m') -1, 2, "0", STR_PAD_LEFT);;
    $last_year = date('Y');
	$args = array(
        'post_type' => 'lease',
        'posts_per_page' => -1,
		//'post_type' => array(),
        'orderby' => 'id',
        'order' => 'ASC',
		'calendar_credits' => 1,
        'meta_query' => array(
            array(
                'key' => '%_yl_mk_i_'.$last_year.'_'.$last_month.'%',
                'value' => 1,
                'compare' => '='
            )
        )
    );
	if(!empty($lease_id)){
		$args['post__in'] = $lease_id;
	}
	$leases = new WP_Query($args);
	echo $leases->request;
	if($leases->have_posts()){
		global $post;
		$allot_credits = array();
		while($leases->have_posts()){ $leases->the_post();
			$client_id = get_post_meta($post->ID, '_yl_company_id', true);
			$allot_credits[$client_id][] = get_post_meta($post->ID, '_yl_suite_number', true);
			//echo '<pre>'; print_r(get_post_meta($post->ID)); exit;
		}
	}
	$final_credit_score = array();
	$y_membership_points = (int)get_option('yl_y_membership_credits');
	$first_suite_points = (int)get_option('yl_first_suite_credits');
	$after_first_suite_points = (int)get_option('yl_subsequent_suite_credits');
	if(!empty($allot_credits)){
		foreach($allot_credits as $client_id => $company){
			$suite_count  = 0;
			foreach($company as $lease){
				if($lease == 'Y-Membership' || 	$lease == -1){
					$final_credit_score[$client_id] += $y_membership_points;
				}else{
					if($suite_count > 0){
						$final_credit_score[$client_id] += $after_first_suite_points;
					}else{
						$final_credit_score[$client_id] += $first_suite_points;
						$suite_count++;
					}
				}
			}
		}
		foreach($final_credit_score as $client_id => $credits){
			//echo $client_id;
			//yl_print_r(get_post_meta($client_id)); exit;
			$current_credits = (int)get_post_meta($client_id,'_yl_amc_credits',true);
			$current_credits += (int)$credits;
			update_post_meta($client_id,'_yl_amc_credits',$current_credits);
			//exit;
		}
	}
}
add_filter( 'posts_where', function ( $where,  \WP_Query $q )
{ 
    if ( empty($q->query_vars['calendar_credits']) )
        return $where;
    $where = str_replace( 'meta_key =', 'meta_key LIKE', $where );
    return $where;
}, 10, 2 );

function yl_add_calendar_menu() {
    add_menu_page(
        __( 'Calendar Credits', 'sprout-invoices' ),
        'Calender Credits',
        'manage_options',
        'calendar-credits',
        'yl_add_calendar_menu_callback',
        '',
        6
    );
}
add_action( 'admin_menu', 'yl_add_calendar_menu' );

function yl_add_calendar_menu_callback(){
	if(!empty($_POST['calendar-credits-submit'])){
		$nonce = $_REQUEST['_wpnonce'];	
		if(wp_verify_nonce($nonce, 'calendar-credits-form')){
			update_option('yl_first_suite_credits', esc_attr($_POST['yl_first_suite_credits']));
			update_option('yl_subsequent_suite_credits', esc_attr($_POST['yl_subsequent_suite_credits']));
			update_option('yl_y_membership_credits', esc_attr($_POST['yl_y_membership_credits']));
		}
	}
?>
	<h1>Calendar Credits Settings</h1>
    <form method="post">
    <table class="form-table">
        <tbody>
        <tr><th scope="row"><label for="yl_site_class">First suite Credits</label></th><td><input type="text" id="yl_first_suite_credits" name="yl_first_suite_credits" value="<?php echo get_option('yl_first_suite_credits');?>"></td></tr>
        <tr><th scope="row"><label for="yl_site_class">Subsequent suite Credits</label></th><td><input type="text" id="yl_subsequent_suite_credits" name="yl_subsequent_suite_credits" value="<?php echo get_option('yl_subsequent_suite_credits');?>"></td></tr>
        <tr><th scope="row"><label for="yl_site_class">Y-membership Credits</label></th><td><input type="text" id="yl_y_membership_credits" name="yl_y_membership_credits" value="<?php echo get_option('yl_y_membership_credits');?>"></td></tr>
        </tbody>
    </table>
    <?php 
	wp_nonce_field('calendar-credits-form');
	submit_button('','','calendar-credits-submit'); 
	?>
    </form>
<?php }
