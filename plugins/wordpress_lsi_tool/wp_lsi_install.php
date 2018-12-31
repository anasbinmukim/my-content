<?php
/**
* Plugin Name: Wordpress LSI TOOL
* Version: 1.11
* Description: LSI
* Author: Cedarwaters
**/

//Define contants
ob_start();
define('CedarWaterLSITOOL_ROOT', dirname(__FILE__));
define('CedarWaterLSITOOL_URL', plugins_url('/', __FILE__));
define('CedarWaterLSITOOL_NETWORK_ADMIN', admin_url( 'admin.php?page=', 'http' ));
define('CedarWaterLSITOOL_HOME', home_url('/'));
define('CedarWaterLSITOOL_IMAGES_PATH',CedarWaterLSITOOL_URL.'images');
define('CedarWaterLSITOOL_CSS_PATH',CedarWaterLSITOOL_URL.'css');
define('CedarWaterLSITOOL_JS_PATH',CedarWaterLSITOOL_URL.'js');
define('CedarWaterLSITOOL_plugin_dirpath', plugin_dir_path( __FILE__ ) );

class cedarwater_wp_lsi_tools
{
    /**
     * Initialized in the constructor
     * @access private
     * @var array
     */
    private $sidebar_status = array();
    /**
     * Constructor
     */
    public function __construct()
	{
		/*Custom Hooks for style and js files*/
		add_action( 'admin_enqueue_scripts', array(&$this, 'register_cedarwater_wp_lsitools_enqueue_scripts') );
		add_action( 'wp_enqueue_scripts', array(&$this, 'register_lsi_custom_front_end_style') );
		/* Custom Hooks for style and js files*/
		register_activation_hook( __FILE__, array(&$this,'activate_wp_LSITOOL_plugin') );
		register_deactivation_hook( __FILE__, array(&$this,'deactive_wp_LSITOOL_plugin'));

		/* Call Classes  */
		$this->internal_lsitoolclasses_features();
		$wplsitoolinnerinfo=new custom_wp_lsi_posts_settings_fns();
    }
    /*Include JS and CSS in Admin Panel*/
	public function register_cedarwater_wp_lsitools_enqueue_scripts($hook)
	{
		wp_register_style( 'wp_custom_lsi_style', plugins_url( 'css/wp_custom_LSI_tools_style.css', __FILE__ ));
	    wp_enqueue_style( 'wp_custom_lsi_style' );

		wp_register_script( 'wp_custom_lsi_tools_js', plugins_url( 'js/wp_custom_lsi_toolsjs.js', __FILE__ ), array( 'jquery' ),"3.2.6");
	    wp_enqueue_script( 'wp_custom_lsi_tools_js' );
	}
	/*Include JS and CSS in frontend Panel*/
	public function register_lsi_custom_front_end_style()
	{
		wp_register_style( 'lsi_custom_tools_front_style', plugins_url( 'css/wp_lsi_custom_frontend_style.css', __FILE__ ));
	    wp_enqueue_style( 'lsi_custom_tools_front_style' );
	}
	/*Plugin activation LSI hook*/
	function activate_wp_LSITOOL_plugin()
	{
		global $wpdb;
		$lsiarchiveofrargs = array(
			'post_title'    => 'LSI Archives',
			'post_status'   => 'publish',
			'post_type'   => 'page',
		);
		$lsiarchiveofrid=wp_insert_post( $lsiarchiveofrargs );
		add_post_meta($lsiarchiveofrid,"_et_pb_page_layout","et_full_width_page");
		add_option("wp_lsi_tools_custom_listing_page",$lsiarchiveofrid);
	}
	/*Plugin Deactivation LSI hook*/
	function deactive_wp_LSITOOL_plugin()
	{
	    global $wpdb;
		$archivelsipage1=get_option("wp_lsi_tools_custom_listing_page");
		wp_delete_post($archivelsipage1,true);
	}
	/*Include Classes*/
	public function internal_lsitoolclasses_features()
	{
		include( 'lib/class_custom_lsi_settings.php' );
	}
}
global $cedarwater_wp_lsi_info;
$cedarwater_wp_lsi_info = new cedarwater_wp_lsi_tools();

include( 'lib/class_lsi_labels.php' );
