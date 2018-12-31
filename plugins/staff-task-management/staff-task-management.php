<?php
/**
 * Plugin Name: Staff Task Management
 * Plugin URI: http://oskyblue.com
 * Version: 1.0
 * Description: Staff Task Management
 * Author: Osky Blue
 * Author URI: http://www.oskyblue.com
**/

// Define contants
date_default_timezone_set("US/Central");

define('STM_ROOT', dirname(__FILE__));
define('STM_URL', plugins_url('/', __FILE__));
define('STM_HOME', home_url('/'));

require_once( STM_ROOT . '/pluggable.php');
require_once( STM_ROOT . '/fpdf/fpdf.php');
require_once( STM_ROOT . '/tcpdf_min/tcpdf.php');
require_once( STM_ROOT . '/stm-functions.php');
require_once( STM_ROOT . '/stm-timesheet-data-save.php');
require_once( STM_ROOT . '/stm-meta-box-config.php');
require_once( STM_ROOT . '/shortcodes/shortcodes.php');
require_once( STM_ROOT . '/timesheet/timesheet.php');
require_once( STM_ROOT . '/stm-availability-overview.php');
require_once( STM_ROOT . '/stm-schedule-print.php');
//require_once( STM_ROOT . '/stm-task-admin.php');
//require_once( STM_ROOT . '/stm-email-reminder.php');



class StaffTaskManagement{
    /**
     * Constructor
     */
    public function __construct() {

        /* Init Custom Post and Taxonomy Types */
        add_action('init', array(&$this, 'register_stm_custom_post'));

        /* Init Custom Post and Taxonomy Types */
        add_action('init', array(&$this, 'cmb_initialize_stm_meta_boxes'), 9999);

		/* Add admin menu */
		//add_action('admin_menu', array(&$this, 'ct_settings_page'));

    }

    /**
     * Plugins settings page
     */
//    public function ct_settings_page() {
//		//add_submenu_page( 'edit.php?post_type=ocbmembers', 'Import', 'Import', 'manage_options', 'ocb-import', array(&$this, 'ocb_import_plug_page'));
//		//add_options_page( 'Contran Intranet','Contran Intranet','manage_options','contran-intranet-options', array( $this, 'contran_intranet_options_settings_page' ) );
//	}


    /**
     * Load default custom post type for osky community band member info
     */
//    public function contran_intranet_options_settings_page() {
//		require_once( STM_ROOT . '/ct-settings.php');
//	}

    /**
     * Load default custom post type for osky community band member info
     */
    public function register_stm_custom_post() {
		require_once( STM_ROOT . '/stm-custom-post-type.php');
	}

	/**
	 * Initialize the metabox class.
	 */
	public function cmb_initialize_stm_meta_boxes() {
		if ( ! class_exists( 'cmb_Meta_Box' ) )
			require_once( STM_ROOT . '/CMB/init.php');
	}



}
// eof class

global $StaffTaskManagement;
$StaffTaskManagement = new StaffTaskManagement();


//No need anymore, already created..
function add_user_role_stm(){
	if(isset($_GET['addrole']) && ($_GET['addrole'] == 'do')){
		//add_role( 'employee', 'Employee', array( 'read' => true, 'level_1' => true ) );


		//remove_role( 'employee' );
		//add_role( 'team_leader', 'Team Leader', array( 'read' => true, 'edit_posts' => true, 'level_1' => true ) );
	}
	add_role( 'team_leader', 'Team Leader', array( 'read' => true, 'edit_posts' => true, 'level_1' => true ) );
	add_role( 'project_manager', 'Project Manager', array( 'read' => true, 'edit_posts' => true, 'level_1' => true ) );
	add_role( 'contractor', 'Contractor', array( 'read' => true, 'level_1' => true ) );
}
add_action( 'init', 'add_user_role_stm' );


add_action('admin_menu', 'register_email_submenu_page');
function register_email_submenu_page() {
	add_submenu_page( 'edit.php?post_type=timesheet', 'Email', 'Email', 'edit_posts', 'email', 'email_submenu_page_callback' );
	add_submenu_page( 'edit.php?post_type=timesheet', 'Settings', 'Settings', 'edit_posts', 'settings_entosapp', 'settings_submenu_page_callback' );
}

function email_submenu_page_callback() {

	echo '<div class="wrap">';
		echo '<h2>Email</h2>';
		$employees = get_employees();
		//print_r($employees); exit;
		if($_GET['empsent'] == "yes") {
			echo '<div class="alert alert-success" role="alert">The emails have successfully been sent to following addresses.</div>';
			foreach($employees as $employee_id) {
				$employee_user_info = get_userdata($employee_id);
				echo $employee_user_info->user_email. '<br />';
			}

		}
      $team_emails_count = count($employees);
		?>
        <div><h3 style="display:inline-block; margin-right:20px;">Work Week</h3> From Date: <input class="datepicker" data-link="email_from_date" type="text" name="supper_admin_email_from_date" value="<?php echo @$_REQUEST['supper_admin_email_from_date'];?>" /> Till Date: <input class="datepicker" data-link="email_till_date" type="text" name="supper_admin_email_till_date" value="<?php echo @$_REQUEST['supper_admin_email_till_date']; ?>" /></div>
        <?php
		//echo '<p>Click <strong>"Submit"</strong> to send out Work Schedules to all Employees.</p>';
		echo '<p>Click <strong>"Submit"</strong> to send out individual Work Schedules.</p>';

		echo '<form action="" method="post">';
		echo '<div class="alert alert-success" role="alert">The emails have successfully been sent to following addresses.<div></div><br><br></div>';
		echo '<input type="hidden" class="sub_action" name="emp_email_pdf" value="do" />';
		echo '<input type="button" name="emp_email_submit" value="Submit" class="button button-primary generate-single-worksheet" />';
		echo '<input type="hidden" class="email_from_date" name="supper_admin_email_from_date" value="'.@$_REQUEST['supper_admin_email_from_date'].'" />';
		echo '<input type="hidden" class="email_till_date" name="supper_admin_email_till_date" value="'.@$_REQUEST['supper_admin_email_till_date'].'" />';
        echo '<input type="hidden" class="emails_count" value="'.$team_emails_count.'" />';
		foreach($employees as $employee_id) {
			echo '<input type="hidden" class="emp-id" value="'.$employee_id.'">';
		}
		echo '</form>';

		echo '<br /><br /><br />';

		$team_leaders_team = get_team_leaders_team();
		//echo '<pre>'; print_r($team_leaders_team);
		if($_GET['teamsent'] == "yes") {
			echo '<div class="alert alert-success" role="alert">The emails have successfully been sent to following addresses.</div>';
			foreach($team_leaders_team as $user_id => $team) {
				$team_leader_info = get_userdata($user_id);
				echo $team_leader_info->user_email. '<br />';
			}

		}
            $employees = array();
			$blogteamusers = get_users( array( 'role__in' => array('team_leader','project_manager'), 'orderby' => 'nicename', 'order' => 'ASC' ) );
            foreach ( $blogteamusers as $user ) {
                $employees['team_'.$user->ID] = $user->display_name;
            }
        $team_emails_count = count($employees);
		//echo '<p>Click <strong>"Submit"</strong> to send out Work Schedules to all Team Leaders.</p>';
		echo '<p>Click <strong>"Submit"</strong> to send out Work Schedules to all Team Leads with their employees schedules.</p>';
		echo '<form action="" method="post">';
		echo '<input type="hidden" class="sub_action" name="team_email_pdf" value="do" />';
		echo '<div class="alert alert-success" role="alert">The emails have successfully been sent to following addresses.<div></div><br><br></div>';
		echo '<input type="button" name="team_email_submit" value="Submit" class="button button-primary generate-single-worksheet" />';
		echo '<input type="hidden" class="email_from_date" name="supper_admin_email_from_date" value="'.@$_REQUEST['supper_admin_email_from_date'].'" />';
		echo '<input type="hidden" class="email_till_date" name="supper_admin_email_till_date" value="'.@$_REQUEST['supper_admin_email_till_date'].'" />';
        echo '<input type="hidden" class="emails_count" value="'.$team_emails_count.'" />';
		foreach($team_leaders_team as $user_id => $team) {
			echo '<input type="hidden" class="emp-id" value="'.$user_id.'">';
		}
		echo '</form>';

		echo '<br /><br /><br />';

//		$all_emp_team = get_employees_and_team();
//		foreach($all_emp_team as $member_key => $member_name){
//			$member_type_ar = explode('_', $member_key);
//			if($member_type_ar[0] == 'team'){
//				echo $member_name.' - Team ID:'.$member_type_ar[1].'<br />';
//			}
//			if($member_type_ar[0] == 'emp'){
//				echo $member_name.' - Emplyee ID:'.$member_type_ar[1].'<br />';
//			}
//		}
		//print_r($all_emp_team);


		if($_GET['adminsent'] == "yes") {
			echo '<div class="alert alert-success" role="alert">The emails have successfully been sent.</div>';
		}
		//echo '<p>Click <strong>"Submit"</strong> to send out All Work Schedules to Administrators.</p>';
		echo '<p>Click <strong>"Submit"</strong> to send out ALL Work Schedules to the following email addresses.</p>';
		echo '<form action="" method="post">';
		echo 'Test Email: <input type="text" name="supper_admin_email_pdf_test" value="" />';
		echo '<input type="hidden" class="email_from_date" name="supper_admin_email_from_date" value="'.@$_REQUEST['supper_admin_email_from_date'].'" />';
		echo '<input type="hidden" class="email_till_date" name="supper_admin_email_till_date" value="'.@$_REQUEST['supper_admin_email_till_date'].'" />';
		echo '<br />';
		echo 'Admin Emails:<br />';
		echo "bbarnes@entosdesign.com<br />bmaners@entosdesign.com<br />kcostigan@entosdesign.com<br />lwinkler@entosdesign.com<br />";	
		echo '<input type="radio" name="include_all_admin" id="include_all_admin" value="all" /><label for="include_all_admin">To All Admin Emails</label><br />';
		echo '<input type="hidden" name="supper_admin_email_pdf" value="do" /><br />';
		echo '<input type="submit" name="admin_email_submit" value="Submit" class="button button-primary" />';
		echo '</form>';

	echo '</div>';?>
    	<script>
			jQuery('.alert-success').hide();
			jQuery(document).ready(function(){
				jQuery('.datepicker').datepicker({ dateFormat: 'mm/dd/yy' });
				jQuery('.datepicker').change(function(){
					jQuery('.'+jQuery(this).data('link')).val(jQuery(this).val());
				});
				jQuery('.generate-single-worksheet').click(function(){
					var form = jQuery(this).parents('form');
					var start_date = form.find('.email_from_date').val();
					var end_date = form.find('.email_till_date').val();
					var sub_action = form.find('.sub_action').attr('name');
					form.find('.alert-success').show();
					var email_length = form.find('.emails_count').val();
                    var counter = 0;
                    form.find('.emp-id').each(function(){
						console.log(jQuery(this).val());
						var single_id = jQuery(this).val();
						jQuery.post({
							url: '<?php echo admin_url( 'admin-ajax.php' );?>',
							data: {
								'action' : 'send_schedule_email',
								'supper_admin_email_from_date' : start_date,
								'supper_admin_email_till_date' : end_date,
								'sub_action' : sub_action,
								'single_id' :single_id
							},
							success:function(data) {
								// This outputs the result of the ajax request
								console.log(data);
                                var emails = jQuery.parseJSON(data);
                                var email_text = '';
                                for(var i = 0; i < emails.length; i++) {
                                   email_text = email_text + emails[i]+'<br>';
                                }
                                counter = counter + emails.length;
								form.find('.alert-success div').append('<br>'+email_text+counter+' out of '+email_length+' emails sent<br>' );

							},
							error: function(errorThrown){
								console.log(errorThrown);
							}
						});
					});
				});

			});
		</script>
	<?php

}
function wpse_enqueue_datepicker() {
    // Load the datepicker script (pre-registered in WordPress).
    wp_enqueue_script( 'jquery-ui-datepicker' );

    // You need styling for the datepicker. For simplicity I've linked to Google's hosted jQuery UI CSS.
    wp_register_style( 'jquery-ui', 'http://code.jquery.com/ui/1.11.2/themes/smoothness/jquery-ui.css' );
    wp_enqueue_style( 'jquery-ui' );
}
add_action( 'admin_enqueue_scripts', 'wpse_enqueue_datepicker' );
function settings_submenu_page_callback() {

	echo '<div class="wrap">';
		echo '<h2>Settings</h2>';
		if(isset($_POST['clear_all_timesheet']) && $_POST['clear_all_timesheet'] == 'do'){
			clear_all_timesheet_of_a_week();
			echo '<div class="alert alert-success" role="alert">All tasks have been successfully removed.</div>';
		}
		echo '<p>Click <strong>"Clear Timesheet"</strong> to Clear All Work Schedules.</p>';
		echo '<form action="" method="post">';
		echo '<input type="hidden" name="clear_all_timesheet" value="do" />';
		echo '<input type="submit" name="clear_submit" value="Clear Timesheet" onclick="return confirm(\'Are you sure?\')" class="button button-primary" />';
		echo '</form>';
	echo '</div>';

}



add_filter('get_user_option_screen_layout_timesheet', function() {
   return 2;
} );


function stm_timesheet_db_install() {
    global $wpdb;
    $table_timesheet = $wpdb->base_prefix . "stm_timesheet";
    $structure_timesheet = "CREATE TABLE IF NOT EXISTS $table_timesheet (
      ID BIGINT(20) NOT NULL AUTO_INCREMENT,
	  client_name VARCHAR(128) NOT NULL,
	  my_task longtext NOT NULL,
	  estimate_hour DECIMAL(4,1) NOT NULL,
	  due_date VARCHAR(32) NOT NULL,
	  assignee BIGINT(20) NOT NULL,
	  create_date_time DATETIME NOT NULL,
	  created_by BIGINT(20) NOT NULL,
	  assigned_by BIGINT(20) NOT NULL,
	  timesheet_user_id BIGINT(20) NOT NULL,
	  assigned_date DATETIME NOT NULL,
	  assigned_day VARCHAR(10) NOT NULL,
	  status VARCHAR(16) NOT NULL,
	  section VARCHAR(32) NOT NULL,
	  task_day VARCHAR(32) NOT NULL,
	  PRIMARY KEY (ID)
	)";
    $wpdb->query($structure_timesheet);

    $table_timesheet_member = $wpdb->base_prefix . "stm_timesheet_member";
    $structure_timesheet_member = "CREATE TABLE IF NOT EXISTS $table_timesheet_member (
      ID BIGINT(20) NOT NULL AUTO_INCREMENT,
	  employee_id  BIGINT(20) NOT NULL,
	  timesheet_id  BIGINT(20) NOT NULL,
	  team VARCHAR(32) NOT NULL,
	  monday_hour INT(8) NOT NULL,
	  tuesday_hour INT(8) NOT NULL,
	  wednesday_hour INT(8) NOT NULL,
	  thursday_hour INT(8) NOT NULL,
	  friday_hour INT(8) NOT NULL,
	  skills longtext NOT NULL,
	  PRIMARY KEY (ID)
	)";
    $wpdb->query($structure_timesheet_member);

    $table_timesheet_relation = $wpdb->base_prefix . "stm_timesheet_relation";
    $structure_timesheet_relation = "CREATE TABLE IF NOT EXISTS $table_timesheet_relation (
      ID BIGINT(20) NOT NULL AUTO_INCREMENT,
	  timesheet_id  BIGINT(20) NOT NULL,
	  assignee BIGINT(20) NOT NULL,
	  PRIMARY KEY (ID)
	)";
    $wpdb->query($structure_timesheet_relation);


    $table_timesheet_assign_log = $wpdb->base_prefix . "stm_timesheet_assign_log";
    $structure_timesheet_assign_log = "CREATE TABLE IF NOT EXISTS $table_timesheet_assign_log (
      ID BIGINT(20) NOT NULL AUTO_INCREMENT,
	  timesheet_id  BIGINT(20) NOT NULL,
	  employee_id  BIGINT(20) NOT NULL,
	  assigned_by BIGINT(20) NOT NULL,
	  update_date_time DATETIME NOT NULL,
	  PRIMARY KEY (ID)
	)";
    $wpdb->query($structure_timesheet_assign_log);

}

function stm_timesheet_db_uninstall() {
    global $wpdb;
    $table_stm_timesheet = $wpdb->base_prefix. "stm_timesheet";
    $structure_stm_timesheet = "DROP TABLE IF EXISTS $table_stm_timesheet";
    $wpdb->query($structure_stm_timesheet);

    $table_stm_timesheet_member = $wpdb->base_prefix. "stm_timesheet_member";
    $structure_stm_timesheet_member = "DROP TABLE IF EXISTS $table_stm_timesheet_member";
    $wpdb->query($structure_stm_timesheet_member);

    $table_stm_timesheet_relation = $wpdb->base_prefix. "stm_timesheet_relation";
    $structure_stm_timesheet_relation = "DROP TABLE IF EXISTS $table_stm_timesheet_relation";
    $wpdb->query($structure_stm_timesheet_relation);

    $table_stm_timesheet_assign_log = $wpdb->base_prefix. "stm_timesheet_assign_log";
    $structure_stm_timesheet_assign_log = "DROP TABLE IF EXISTS $table_stm_timesheet_assign_log";
    $wpdb->query($structure_stm_timesheet_assign_log);
}
// Register the activate and deactivate hooks
register_activation_hook(__FILE__, 'stm_timesheet_db_install');
//register_deactivation_hook(__FILE__, 'stm_timesheet_db_uninstall');



/**
 * The following will load the custom timesheet.js file on the edit.php file within the admin.
 */
function enqueue_admin_edit_scripts($hook) {
    wp_enqueue_script( 'my_custom_script', plugin_dir_url( __FILE__ ) . 'timesheet.js' );
}
add_action( 'admin_enqueue_scripts', 'enqueue_admin_edit_scripts' );


function set_timesheet_post_order_in_admin( $wp_query ) {
global $pagenow;
  if ( is_admin() && 'edit.php' == $pagenow && !isset($_GET['orderby'])) {
    $wp_query->set( 'orderby', 'title' );
    $wp_query->set( 'order', 'ASC' );
  }
}
add_filter('pre_get_posts', 'set_timesheet_post_order_in_admin' );
