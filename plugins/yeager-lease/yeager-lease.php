<?php
/**
 * Plugin Name: Yeager Lease
 * Plugin URI: http://cedarwaters.com/
 * Version: 1.2
 * Description: Yeager Lease Process
 * Author: Cedarwaters
 * Author URI: http://cedarwaters.com/
**/


/**
 * Define Constants 
 *
 */
date_default_timezone_set("US/Central");

define('YL_ROOT', dirname(__FILE__));
define('YL_URL', plugins_url('/', __FILE__));
define('YL_HOME', home_url('/'));

/**
 * Includes
 *
 */
require_once( YL_ROOT . '/includes.php');


/**
 * YeagerLease Class
 *
 */
class YeagerLease {	
    /**
     * Constructor 
     */
    public function __construct() {    		
        /* Init Custom Post and Taxonomy Types */
        add_action( 'init', array(&$this, 'yl_register_custom_post') );
		
        /* Init Custom Post and Taxonomy Types */
        add_action( 'init', array(&$this, 'yl_cmb_initialize_meta_boxes'), 9999 );

        /* Proper way to enqueue scripts and styles. */
        add_action( 'wp_enqueue_scripts', array(&$this, 'yl_enqueue_scripts') );
        add_action( 'admin_enqueue_scripts', array(&$this, 'yl_enqueue_admin_scripts') );

        /* If user roles don't exist, create them */
        add_action( 'init', array(&$this, 'yl_add_user_role_yeager_lease') );

        /* Templates */
        add_filter('single_template', array(&$this, 'yl_get_post_type_template') );
    }


    /**
     * Load default custom post type for osky community band member info
     */
    public function yl_register_custom_post() {
		require_once( YL_ROOT . '/yl-custom-post-type.php');
	}
	
	/**
	 * Initialize the metabox class.
	 */
	public function yl_cmb_initialize_meta_boxes() {
		if ( ! class_exists( 'cmb_Meta_Box' ) ) {
			require_once( YL_ROOT . '/CMB/init.php');
		}
	}

	/**
	 * Enqueue all scripts and styles for the front-end
	 */
	function yl_enqueue_scripts() {
		wp_enqueue_script( 'yl-script', YL_URL . 'yl-scripts.js', array('jquery'), time());
		wp_localize_script( 'yl-script', 'leaseObj', array( 
			'lease_summary_url' => get_permalink(get_option('yl_lease_summary_page')) 
		) );

		
		wp_enqueue_script( 'yl-bootstrap-js', plugin_dir_url( __FILE__ ) . 'bootstrap/js/bootstrap.min.js', array('jquery'), time());
		wp_enqueue_script( 'yl-moment-js', plugin_dir_url( __FILE__ ) . 'bootstrap-datetimepicker/moment.js', array('jquery'), time());
		wp_enqueue_script( 'yl-bootstrap-datetimepicker-js', plugin_dir_url( __FILE__ ) . 'bootstrap-datetimepicker/build/js/bootstrap-datetimepicker.min.js', array('yl-bootstrap-js'), time());
		wp_enqueue_script( 'yl-datatables-js', plugin_dir_url( __FILE__ ) . 'jquery.dataTables.min.js', array('jquery'), time());

	    wp_enqueue_style( 'yl-bootstrap', plugin_dir_url( __FILE__ ) . 'bootstrap/css/bootstrap.min.css' );
	    wp_enqueue_style( 'yl-font-awesome', YL_URL . '/font-awesome/css/font-awesome.min.css');
	    wp_enqueue_style( 'yl-bootstrap-datetimepicker-css', YL_URL . '/bootstrap-datetimepicker/build/css/bootstrap-datetimepicker.min.css');
	    wp_enqueue_style( 'yl-datatables-css', YL_URL . 'jquery.dataTables.min.css', array(), time() );
	    wp_enqueue_style( 'yl-styles', YL_URL . 'yl-style.css', array(), time() );

	    
	}

	function yl_enqueue_admin_scripts() {
		wp_enqueue_script( 'yl-script', YL_URL . 'yl-scripts-admin.js', array('jquery'), time());
		wp_enqueue_style('yl-styles', YL_URL . '/yl-style-admin.css');
	}

	/**
	 * Add user roles if they are necessary
	 */
	function yl_add_user_role_yeager_lease(){
		add_role( 'building_manager', 'Building Manager', array( 'read' => true, 'edit_posts' => true, 'level_2' => true ) );
		add_role( 'lease_client', 'Client', array( 'read' => true, 'level_1' => true ) );
	}

	function yl_get_post_type_template($single_template) {
		global $post;

		/* company post details page */
		if ($post->post_type == 'company') {
			$single_template = YL_ROOT . '/templates/single-company.php';
		}
		if ($post->post_type == 'lease') {
			$single_template = YL_ROOT . '/templates/single-lease.php';
		}

		return $single_template;
	}
	
}
// eof class

global $YeagerLease;
$YeagerLease = new YeagerLease();



//
$yl_new_general_setting = new yl_new_general_setting();

class yl_new_general_setting {
    function yl_new_general_setting( ) {
        add_filter( 'admin_init' , array( &$this , 'yl_register_gs_fields' ) );
    }
    function yl_register_gs_fields() {
    	if (isset($_POST['yl_site_class'])) {
    		update_option('yl_site_class', $_POST['yl_site_class']);
    	}
        register_setting( 'general', 'favorite_color', 'esc_attr' );
        add_settings_field('fav_color', '<label for="yl_site_class">'.__('Building CLASS' , 'YL' ).'</label>' , array(&$this, 'yl_gs_fields_html') , 'general' );
    }
    function yl_gs_fields_html() {
        $value = get_option( 'yl_site_class' );
        echo '<input type="text" id="yl_site_class" name="yl_site_class" value="' . $value . '" />';
    }
}


add_action('wp_head','yl_ajaxurl');
function yl_ajaxurl() {
?>
    <link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
    <script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
    <script type="text/javascript">
    	var ajaxurl = '<?php echo admin_url('admin-ajax.php'); ?>';
    	var ajax_invoice_send_from = '<?php echo get_bloginfo( 'name' ); ?> <<?php echo get_bloginfo( 'admin_email' ); ?>>';
		jQuery(function() {
			/*jQuery( "#suiteType" ).hide();
			jQuery( "#MoveinDate" ).datepicker({
			  onClose: function(selectedDate) {
				if(selectedDate != "")
					jQuery( "#suiteType" ).fadeIn();
			  }}
			);*/
			jQuery( "#MoveinDate" ).datepicker({ minDate: 0, dateFormat: 'yy-mm-dd' });
			jQuery( ".leasedatepicker" ).datepicker({ dateFormat: 'yy-mm-dd' });
		});
    </script>
<?php
}




// Let's detect an admin payment and if balance is negative, add that extra
// credit to the client.
function yl_new_payment_backend_detected_func($payment){
	$invoice_id = $payment->get_invoice_id();
	$invoice = SI_Invoice::get_instance( $invoice_id );
	$balance = $invoice->get_balance();
	$client_id = $invoice->get_client_id();
	
	if ($balance < 0) {
		$new_credit_data = array(
			'client_id'			=> (int) $client_id,
			'credit_type_id'	=> (int) 0,
			'credit_val'		=> (float) si_get_number_format( abs($balance) ),
			'note'				=> 'Overpayment from invoice '.$invoice_id,
			'date'				=> (int) current_time( 'timestamp' ),
			'user_id'			=> get_current_user_id()
		);

		$new_credit_id = SI_Account_Credits_Clients::create_associated_credit( $client_id, $new_credit_data );
		do_action( 'si_credit_created', $new_credit_id );
	}
}
add_action( 'si_new_payment',  'yl_new_payment_backend_detected_func' );

//detect when a payment was done
function yl_payment_detected_func($payment){

	$invoice_id = $payment->get_invoice_id();

	if (get_post_meta($invoice_id, '_yl_hold', true)) {
		// HOLD Payment
		$bm_id 		= 	get_post_meta($invoice_id, '_yl_hold_bm', true);
		$product_id = 	get_post_meta($invoice_id, '_yl_hold_suite', true);
		$user_id = 		get_post_meta($invoice_id, '_yl_hold_client', true);
		$company_id = 	get_post_meta($invoice_id, '_yl_hold_company', true);

		update_post_meta($product_id, '_yl_hold', true);
		update_post_meta($product_id, '_yl_hold_start', time());
		update_post_meta($product_id, '_yl_hold_expiration', time()+(60*60*48));
		//update_post_meta($product_id, '_yl_hold_expiration', time()+(60*2)); // 2 minute expiration, just for testing
		update_post_meta($product_id, '_yl_hold_client', $user_id);
		update_post_meta($product_id, '_yl_hold_bm', $bm_id);
		update_post_meta($product_id, '_yl_hold_company', $company_id);

		/*
		if (isset($_GET['yl_redirect'])) {
			?>
			<script>
				setTimeout(function() { 
					window.location.href = '<?php $_GET['yl_redirect']; ?>?lid=<?php echo $_GET['yl_redirect_lid']; ?>';
				}, 1000);
			</script>
			<?php
			wp_redirect($_GET['yl_redirect'].'?lid='.$_GET['yl_redirect_lid']);
			wp_die();
		}
		*/

	}
	else {
		// Lease Payment
		$lease_id 	= yl_get_lease_id_by_invoice_id($invoice_id);
		$bm_id 		= yl_get_lm_id_by_lease_id($lease_id);
		$product_id = yl_get_suite_id_by_lease_id($lease_id);

		$user = get_user_by( 'id', $bm_id );
		$user_email = $user->user_email;

		$email_subject = get_option('bm_email_subject');
		$email_message = get_option('bm_email_message');			
		$search = array();
		$replace = array();	

		$search[] = '%%name%%';
		$replace[] = $user->first_name;
		
		$search[] = '%%lease-sign-url%%';
		$replace[] = '<a href="'.get_permalink(get_option('yl_bm_sign_page')).'?lid='.$lease_id.'">'.get_the_title($lease_id).'</a>';

		$message = str_replace($search, $replace, $email_message);	
		$message = 	stripslashes($message);
		$message = nl2br($message);  
		
		$headers  = 'MIME-Version: 1.0' . "\r\n";
		$headers = array('Content-Type: text/html; charset=UTF-8');
		if( wp_mail( $user_email, $email_subject, $message, $headers ) ) {
			update_post_meta($product_id, '_yl_available', 'No');

			/*
			if (isset($_GET['yl_redirect'])) {
				?>
				<script>
					setTimeout(function() { 
						window.location.href = '<?php $_GET['yl_redirect']; ?>?lid=<?php echo $_GET['yl_redirect_lid']; ?>';
					}, 1000);
				</script>
				<?php 
				wp_redirect($_GET['yl_redirect'].'?lid='.$_GET['yl_redirect_lid']);
				wp_die();
			}
			*/

		}
	}

	// Stand alone invoice payment notification
	if ($emails = get_post_meta($invoice_id, '_yl_notify_emails', true)) {
		$invoice_meta = get_post_meta($invoice_id);
        $client_id = $invoice_meta['_client_id'][0];
        $client_data = get_post($client_id);
        $suite_id = $invoice_meta['_yl_suite_id'][0];
        $total = $invoice_meta['_total'][0];
        $work_order = $invoice_meta['_yl_work_order_number'][0];
        $suite_meta = get_post_meta($suite_id);
        $suite_number = $suite_meta['_yl_room_number'][0];

        $email_subject = 'Work Order #'.$work_order.' Payment';
		$email_message = 'Client '.$client_data->post_title.' in Suite #'.$suite_number.' has paid $'.round($total, 2).' on invoice #'.$invoice_id.' for work order #'.$work_order.'.';	
		$headers  = 'MIME-Version: 1.0' . "\r\n";
		$headers = array('Content-Type: text/html; charset=UTF-8');
		wp_mail( $emails, $email_subject, $email_message, $headers ) ;
	}

	/*
	if (isset($_GET['yl_redirect'])) {
		wp_redirect($_GET['yl_redirect']);
	}
	*/

	
}
add_action( 'payment_complete', 'yl_payment_detected_func' );

if(!empty($_REQUEST['update_company_credits'])){
	add_action('init','yeager_update_company_credits');
}

function yeager_update_company_credits(){
	
}
