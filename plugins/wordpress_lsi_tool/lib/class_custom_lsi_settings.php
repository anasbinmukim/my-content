<?php
class custom_wp_lsi_posts_settings_fns
{
    public function __construct()
	{
	    /*****Declaration of action hooks*****/
        add_action( 'init', array(&$this,'register_lsi_custom_post_type_fn'));
		add_action('add_meta_boxes', array(&$this, 'lsi_custom_post_type_extra_metaboxes'));
		add_action('add_meta_boxes', array(&$this, 'lsi_custom_post_type_shortcode_display'));
		add_action( 'save_post', array( &$this, 'lsi_custom_post_type_save_vals' ), 10, 2 );
		add_shortcode('lsi-sidebar', array( &$this, 'lsi_sidebar_shortcode_listing_posts') );
		add_filter( 'page_template', array(&$this,'wplsi23423_page_template') );
		add_filter('single_template', array(&$this, 'wplsi_invd234562_template'));
		add_filter( 'archive_template', array(&$this, 'wplsi_get_custom_post_lsi_archive') );
		add_filter( 'ms_view_membership_edit_to_html', array(&$this, 'filter_ms_view_membership_edit_to_html_by_lsi'),10,3);
		add_filter( 'ms_controller_membership_ajax_action_update_membership', array(&$this, 'filter_ms_view_details_save_membership'),10,3);
		add_action('transition_post_status', array(&$this, 'send_lsi_email_notification_on_new_event'), 10, 3 );
		if ( is_multisite() ) {
            add_action('signup_extra_fields', array(&$this, 'add_custom_extra_fields_in_registration_form'), 10, 1);
		} else {
			add_action('register_form', array(&$this, 'add_custom_extra_fields_in_registration_form'), 10, 1);
		}

		add_action('ms_controller_frontend_register_user_complete', array(&$this, 'save_custom_extra_fields_of_reg_form'), 10, 3);
		add_action( 'show_user_profile', array(&$this, 'show_lsi_extra_custom_users_profile_fields'));
        add_action( 'edit_user_profile', array(&$this, 'show_lsi_extra_custom_users_profile_fields'));
		add_action( 'personal_options_update', array(&$this, 'lsi_save_extra_custom_users_profile_fields'));
        add_action( 'edit_user_profile_update', array(&$this, 'lsi_save_extra_custom_users_profile_fields'));
        add_action( 'ms_view_profile_fields', array(&$this, 'lsi_show_frontend_extra_custom_users_profile_fields_new'), 10, 2);
        add_action( 'ms_model_member_update_user', array(&$this, 'update_lsi_show_frontend_extra_custom_users_profile_fields'), 10, 1);
        add_action( 'ms_view_account_profile_before_card', array(&$this, 'show_lsi_address_fields_on_account_section'), 10, 1);
	}

	function send_lsi_email_notification_on_new_event($new_status, $old_status, $post)
	{
		if ( 'publish' !== $new_status or 'publish' === $old_status or 'lsi_posts' !== get_post_type( $post ) )
        return;

		if($post->post_type=='lsi_posts' && $post->post_status=='publish')
		{
			$getallmembersgip=MS_Model_Membership::get_memberships();
			if(!empty($getallmembersgip))
			{
				foreach($getallmembersgip as $singlemembership)
				{
					$membership_id=$singlemembership->id;
					$singlemembershipmembers='';
					$singlemembershipmembers=$singlemembership->get_members();
					$issentnotifyemail=get_option("sent_notify_email_on_lsi_inserted_".$membership_id);
					$notificationemailcontent=get_option("notification_email_content_on_lsi_addition_".$membership_id);

					if(!empty($singlemembershipmembers))
					{
						foreach($singlemembershipmembers as $key=>$memberval)
						{
							$memebrid=$key;
							$getuser_info = get_userdata($memebrid);
							$getusername=$getuser_info->display_name;
							$getuseremaill=$getuser_info->user_email;

							$to=$getuseremaill;
							$subject = "New LSI posted";

							$lsiexpldshrtdes=explode("\n",$notificationemailcontent);

							if(empty($lsiexpldshrtdes))
							{
								$emailcontenttext=$notificationemailcontent;
							}else{
								$emailcontenttext='<p>'.implode("</p><p>",$lsiexpldshrtdes).'</p>';
							}

							$ctmessage = "Hi ".$getusername." \r\n \r\n";
							$ctmessage .= $emailcontenttext;

							$headers = "MIME-Version: 1.0" . "\r\n";
							$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

							$headers .= 'From: Keith Craft <testak@example.com>' . "\r\n";

							if($issentnotifyemail==1)
							{
								wp_mail($to,$subject,$ctmessage,$headers);
							}
						}
					}
				}
			}
		}
	}

	function filter_ms_view_details_save_membership($msg, $memberthis)
	{
		//echo "<pre>";print_r($_POST);
		$fieldname=$_POST['field'];
		$fieldvalue=$_POST['value'];
		if($fieldvalue=='true')
		{
			$fieldvalue=1;
		}elseif($fieldvalue=='false'){
			$fieldvalue=0;
		}
		$fldmembership_id=$_POST['membership_id'];
		update_option($fieldname."_".$fldmembership_id,$fieldvalue);
		return $msg;
	}

	function filter_ms_view_membership_edit_to_html_by_lsi( $html, $field, $membership )
	{
		global $wpdb;
		ob_start();
		$membershipids=$membership->id;
		$enableproshortpaid=get_option("enable_protection_shortcode_val_".$membershipids);
		$lsinumberdownloads=get_option("lsi_number_of_audion_downloads_".$membershipids);
		$issentnotifyemail=get_option("sent_notify_email_on_lsi_inserted_".$membershipids);
		$notificationemailcontent=get_option("notification_email_content_on_lsi_addition_".$membershipids);
		?>
		    <div>
				<form class="ms-form wpmui-ajax-update ms-edit-membership" data-wpmui-ajax="<?php echo esc_attr( 'save' ); ?>">
					<div class="ms-form wpmui-form wpmui-grid-8">
						<div class="col-5">
							<?php
							MS_Helper_Html::html_element( $field['name'] );
							if ( ! $membership->is_system() ) {
								MS_Helper_Html::html_element( $field['description'] );
							}
							?>
						</div>
						<div class="col-3">
							<?php
							MS_Helper_Html::html_element( $field['active'] );
							if ( ! $membership->is_system() ) {
								MS_Helper_Html::html_element( $field['public'] );
								MS_Helper_Html::html_element( $field['paid'] );
							}
							?>
						</div>
					</div>
					<div class="ms-form wpmui-form wpmui-grid-8">
						<div class="col-8">
						<?php
						if ( ! $membership->is_system() ) {
							MS_Helper_Html::html_element( $field['priority'] );
						}
						?>
						    <div class="custom_settings_fields_by_lsi">
							    <h2>LSI Membership Settings</h2>
							    <?php
								    $action = MS_Controller_Membership::AJAX_ACTION_UPDATE_MEMBERSHIP;
		                            $nonce = wp_create_nonce( $action );
                                    $ajaxdata=array('field'=>'enable_protection_shortcode_val','_wpnonce'=>$nonce,'action'=>$action,'membership_id'=>$membership->id);

								    $customfield1=array(
										'id' => 'enable_protection_shortcode_val',
										'name' => 'enable_protection_shortcode_val',
										'type' => MS_Helper_Html::INPUT_TYPE_RADIO_SLIDER,
										'title' => __( 'Enable Protection Shortcode', 'membership2' ),
										'before' => __( 'No', 'membership2' ),
										'after' => __( 'Yes', 'membership2' ),
										'class' => 'ms-protection-code',
										'value' => $enableproshortpaid,
										'ajax_data' => $ajaxdata,
									);
								    MS_Helper_Html::html_element( $customfield1 );
								?>
								<br/>
								<?php
                                    $ajaxdata1=array('field'=>'lsi_number_of_audion_downloads','_wpnonce'=>$nonce,'action'=>$action,'membership_id'=>$membership->id);

								    if($lsinumberdownloads===false)
									{
										$noofsownlaodss=2;
									}else{
										$noofsownlaodss=$lsinumberdownloads;
									}
								    $customfield2=array(
										'id' => 'lsi_number_of_audion_downloads',
										'name' => 'lsi_number_of_audion_downloads',
										'type' => MS_Helper_Html::INPUT_TYPE_NUMBER,
										'title' => __( 'How many downloads can a member do each month?', 'membership2' ),
										'before' => __( '', 'membership2' ),
										'after' => __( '', 'membership2' ),
										'class' => 'ms-audio-downloads-number',
										'value' => $noofsownlaodss,
										'ajax_data' => $ajaxdata1,
									);
								    MS_Helper_Html::html_element( $customfield2 );
								?>
								<br/>
								<?php
								    $ajaxdata2=array('field'=>'sent_notify_email_on_lsi_inserted','_wpnonce'=>$nonce,'action'=>$action,'membership_id'=>$membership->id);

								    $customfield3=array(
										'id' => 'sent_notify_email_on_lsi_inserted',
										'name' => 'sent_notify_email_on_lsi_inserted',
										'type' => MS_Helper_Html::INPUT_TYPE_RADIO_SLIDER,
										'title' => __('Sent notification email to all members when new LSI added. ', 'membership2' ),
										'before' => __( 'No', 'membership2' ),
										'after' => __( 'Yes', 'membership2' ),
										'class' => 'ms-new_lsi_email',
										'value' => $issentnotifyemail,
										'ajax_data' => $ajaxdata2,
									);
								    MS_Helper_Html::html_element( $customfield3 );
								?>
								<br/>
								<?php
                                    $ajaxdata3=array('field'=>'notification_email_content_on_lsi_addition','_wpnonce'=>$nonce,'action'=>$action,'membership_id'=>$membership->id);

									if($notificationemailcontent===false)
									{
										$notifyemailct="Thank you so much for being an LSI member!

We've posted new LSI content on our site and you can view it here: http://keithcraft.org/lsi

Don't forget to use your 2 free downloads and browse our archive of content at any time.

-Keith Craft
Transforming Leaders From the Inside Out";
									}else{
										$notifyemailct=$notificationemailcontent;
									}
								    $customfield4=array(
										'id' => 'notification_email_content_on_lsi_addition',
										'name' => 'notification_email_content_on_lsi_addition',
										'type' => MS_Helper_Html::INPUT_TYPE_TEXT_AREA,
										'title' => __('Notification email sent below content when new LSI added.', 'membership2' ),
										'class' => 'ms-new_lsi_email_content_modifications',
										'value' => $notifyemailct,
										'ajax_data' => $ajaxdata3,
									);
								    MS_Helper_Html::html_element( $customfield4 );
								?>
							</div>
						</div>
					</div>
				</form>
		    </div>
		<?php
		$html = ob_get_clean();
		return $html;
	}
	function register_lsi_custom_post_type_fn()
	{
		$labels = array(
			'name' => _x('LSI', 'LSI name', 'RMTheme'),
			'singular_name' => _x('LSI', 'LSI type singular name', 'RMTheme'),
			'add_new' => _x('Add New', 'Report', 'RMTheme'),
			'add_new_item' => __('Add New LSI posts', 'RMTheme'),
			'edit_item' => __('Edit LSI posts', 'RMTheme'),
			'new_item' => __('New LSI posts', 'RMTheme'),
			'view_item' => __('View LSI posts', 'RMTheme'),
			'search_items' => __('Search LSI posts', 'RMTheme'),
			'not_found' => __('No LSI posts Found', 'RMTheme'),
			'not_found_in_trash' => __('No LSI posts Found in Trash', 'RMTheme'),
			'parent_item_colon' => ''
		);

		register_post_type('lsi_posts', array('labels' => $labels,
				'public' => true,//true
				'show_ui' => true,
				'show_in_menu' => true,
				'map_meta_cap' => true,
				'hierarchical' => true,
				'publicly_queryable' => true,//true
				'query_var' => true,
				'exclude_from_search' => false,
				//'rewrite' => array('slug' => 'lsi_posts'),
				'show_in_nav_menus' => true,
				'supports' => array('title')
			)
		);

		$labels = array(
			'name'              => _x( 'LSI Tags', 'taxonomy general name' ),
			'singular_name'     => _x( 'LSI Tag', 'taxonomy singular name' ),
			'search_items'      => __( 'Search LSI Tags' ),
			'all_items'         => __( 'All LSI Tags' ),
			'parent_item'       => __( 'Parent LSI Tags' ),
			'parent_item_colon' => __( 'Parent LSI Tags:' ),
			'edit_item'         => __( 'Edit LSI Tag' ),
			'update_item'       => __( 'Update LSI Tag' ),
			'add_new_item'      => __( 'Add New LSI Tag' ),
			'new_item_name'     => __( 'New LSI Tag' ),
			'menu_name'         => __( 'LSI Tags' ),
		);

		$args = array(
			'hierarchical'      => false,
			'labels'            => $labels,
			'show_ui'           => true,
			'show_admin_column' => true,
			'query_var'         => true,
			//'rewrite'           => array( 'slug' => 'lsi-tags' ),
		);

		register_taxonomy( 'lsi_tags', array( 'lsi_posts' ), $args );
	}

	function lsi_custom_post_type_extra_metaboxes()
	{
		add_meta_box('lsi_post_type_tab_extra_fields', __('LSI Extra Fields', 'cedarwaters'), array(&$this, 'lsi_custom_post_type_ext_metaboxes'), 'lsi_posts', 'advanced', 'high');
	}

	function lsi_custom_post_type_ext_metaboxes()
	{
		global $wpdb;
		global $post;

		$post_id=$post->ID;
		$lsi_short_desc=get_post_meta($post_id,'lsi_short_description',true);
		$lsi_filename=get_post_meta($post_id,'lsi_filename',true);
		$lsi_complurlpath=get_post_meta($post_id,'lsi_complete_url_path',true);
		?>
		<div class="lsi_fields_container">
			<div class="lsi_extra_fields">
				<label class="lsi_fields_labels"><b>File Name</b></label>
				<input type="text" name="lsi_filename" id="lsi_filename" class="lsi_inner_fields" value="<?php echo $lsi_filename; ?>" />
			</div>
			<div class="lsi_extra_fields">
				<label class="lsi_fields_labels"><b>LSI Short Description</b></label>
				<textarea name="lsi_short_description" class="lsi_inner_fields" rows="8" ><?php echo $lsi_short_desc; ?></textarea>
			</div>
			<div class="lsi_extra_fields">
				<label class="lsi_fields_labels"><b>LSI Complete URL</b></label>
				<input type="text" name="lsi_full_url_path" id="lsi_full_url_path" class="lsi_inner_fields" value="<?php echo $lsi_complurlpath; ?>" />
			</div>
		</div>
		<?php
	}

	function lsi_custom_post_type_shortcode_display()
	{
		add_meta_box('lsi_post_type_tab_shortcode_val', __('LSI Shortcode', 'cedarwaters'), array(&$this, 'lsi_custom_post_type_shortcode_fn'), 'lsi_posts', 'side', 'low');
	}

	function lsi_custom_post_type_shortcode_fn()
	{
		global $wpdb;
		global $post;

		$post_id=$post->ID;
		$lsi_filename=get_post_meta($post_id,'lsi_filename',true);
		$shortcdetext='[s3mediastream type="streamingaudio" file="'.$lsi_filename.'" expires="1200" /]';
		?>
		<div class="lsi_fields_container">
			<div class="lsi_extra_fields">
				<textarea name="lsi_invt_shortcode" id="lsiinvshortcode" class="lsi_inner_fields" rows="8" readonly ><?php echo $shortcdetext; ?></textarea>
			</div>
		</div>
		<?php
	}

	function lsi_custom_post_type_save_vals($post_id, $post)
	{
		global $wpdb;

		if(isset($_POST['lsi_short_description']))
		{
		    $lsi_short_desc=get_post_meta($post_id,'lsi_short_description',true);
			if($lsi_short_desc===false)
			{
				add_post_meta($post_id, 'lsi_short_description', $_POST['lsi_short_description']);
			}else{
				update_post_meta($post_id, 'lsi_short_description', $_POST['lsi_short_description']);
			}
		}
		if(isset($_POST['lsi_filename']))
		{
		    $lsi_short_desc=get_post_meta($post_id,'lsi_filename',true);
			if($lsi_short_desc===false)
			{
				add_post_meta($post_id, 'lsi_filename', $_POST['lsi_filename']);
			}else{
				update_post_meta($post_id, 'lsi_filename', $_POST['lsi_filename']);
			}
		}

		if(isset($_POST['lsi_full_url_path']))
		{
		    $lsi_short_complteurl=get_post_meta($post_id,'lsi_complete_url_path',true);
			if($lsi_short_complteurl===false)
			{
				add_post_meta($post_id, 'lsi_complete_url_path', $_POST['lsi_full_url_path']);
			}else{
				update_post_meta($post_id, 'lsi_complete_url_path', $_POST['lsi_full_url_path']);
			}
		}
	}

	function lsi_sidebar_shortcode_listing_posts($atts)
	{
		ob_start();
	    global $wpdb;

		$atts = shortcode_atts( array(
			'posts' => 1,
      'id' => '',
			'readmore' => 'yes',
			'recent_listing_title' => 'Recent LSI Recordings'
		), $atts, 'lsi-sidebar' );

		$postsval=$atts['posts'];
    $lsi_post_id = $atts['id'];
		$readmoreval=strtolower($atts['readmore']);
		$recent_listing_title=$atts['recent_listing_title'];

		$lsitoolscustompg=get_option("wp_lsi_tools_custom_listing_page");
	    ?>
		    <div class="lsi_recent_recording_container">
			  <h1><?php echo $recent_listing_title; ?></h1>
				<?php
				$lsicount=0;
				    $args = array(
							'posts_per_page'   => $postsval,
              'p'   => $lsi_post_id,
							'orderby'          => 'date',
							'order'            => 'DESC',
							'post_type'        => 'lsi_posts',
							'post_status'      => 'publish',
						);
					$getlatestlsirecds = get_posts( $args );
					if(!empty($getlatestlsirecds))
					{
						$lsicount=count($getlatestlsirecds);
						foreach($getlatestlsirecds as $getlsirecord)
						{
							$lsirecrd_title=$getlsirecord->post_title;
							$lsirecrd_date=$getlsirecord->post_date;
							$lsisingledate=date("M d, Y",strtotime($lsirecrd_date));
							$lsi_filename=get_post_meta($getlsirecord->ID,'lsi_filename',true);
		                    $strshortcdetext='[s3mediastream type="streamingaudio" file="'.$lsi_filename.'" expires="1200" /]';

							$lsi_term_tag = '';
							$lsi_term_tag = get_the_term_list( $getlsirecord->ID, 'lsi_tags', '', ', ' );
				?>
						<div class="single_recording_mdl">
							<h4><a href="<?php echo get_permalink($getlsirecord->ID); ?>"><?php echo $lsirecrd_title; ?></a></h4><p class="lsi_line_space_tag"><?php echo $lsisingledate; ?>
							<?php if($lsi_term_tag){ echo '| '.$lsi_term_tag; } ?>
							</p>
							<div class="audiolsi_disply"><?php echo do_shortcode($strshortcdetext); ?></div>
						</div>
				<?php } } ?>
				<?php
				if($readmoreval=='yes'){ if($lsicount>0){ ?>
				<div class="lsi_load_more_recds"><a class="morequotes" href="<?php echo get_permalink($lsitoolscustompg); ?>">See More</a></div>
	            <?php } } ?>
				<?php
				//$getpostsdata=$wpdb->get_results("select * from ".$wpdb->prefix."postmeta where post_id=7988");
				//echo "<pre>";print_r($getpostsdata);
				?>
			</div>
		<?php
		$lsisidebarhtml = ob_get_clean();
	    return $lsisidebarhtml;
	}

	function wplsi23423_page_template($page_template)
	{
		$lsitoolscustompg=get_option("wp_lsi_tools_custom_listing_page");
		if ( is_page($lsitoolscustompg) ) {
			$page_template = dirname( __FILE__ ) . '/lsi_archive_listing.php';
		}
		return $page_template;
	}

	function wplsi_invd234562_template($single)
	{
		global $wp_query, $post;

		if ($post->post_type == "lsi_posts")
		{
			if(file_exists(dirname( __FILE__ ) . '/lsi_single_post.php'))
			{
				$single = dirname( __FILE__ ) . '/lsi_single_post.php';
			}
		}
		return $single;
	}

	function wplsi_get_custom_post_lsi_archive($archive_template)
	{
		global $wp_query, $post;
		if(is_tax('lsi_tags'))
		{
            if(file_exists(dirname( __FILE__ ) . '/lsi_listing_by_tags.php'))
			{
				$archive_template = dirname( __FILE__ ) . '/lsi_listing_by_tags.php';
			}
        }
		return $archive_template;
	}

	function add_custom_extra_fields_in_registration_form($errors)
	{
		global $wpdb;
		?>
		<div class="ms-form-element ms-form-element-address1">
		    <?php
			    $customfield1=array(
					'id' => 'regsiter_usr_lsi_address_line1',
					'name' => 'regsiter_usr_lsi_address_line1',
					'type' => MS_Helper_Html::INPUT_TYPE_TEXT,
					'title' => __( 'Address 1', 'membership2' ),
					'before' => __( '', 'membership2' ),
					'after' => __( '', 'membership2' ),
					'class' => 'ms-address_field1-code required',
					'value' => "",
				);
				MS_Helper_Html::html_element( $customfield1 );
			?>
		</div>
		<div class="ms-form-element ms-form-element-address2">
		    <?php
			    $customfield2=array(
					'id' => 'regsiter_usr_lsi_address_line2',
					'name' => 'regsiter_usr_lsi_address_line2',
					'type' => MS_Helper_Html::INPUT_TYPE_TEXT,
					'title' => __( 'Address 2', 'membership2' ),
					'before' => __( '', 'membership2' ),
					'after' => __( '', 'membership2' ),
					'class' => 'ms-address_field2-code',
					'value' => "",
				);
				MS_Helper_Html::html_element( $customfield2 );
			?>
		</div>
		<div class="ms-form-element ms-form-element-city">
		    <?php
			    $customfield3=array(
					'id' => 'regsiter_usr_lsi_city',
					'name' => 'regsiter_usr_lsi_city',
					'type' => MS_Helper_Html::INPUT_TYPE_TEXT,
					'title' => __( 'City', 'membership2' ),
					'before' => __( '', 'membership2' ),
					'after' => __( '', 'membership2' ),
					'class' => 'ms-lsi-city-code required',
					'value' => "",
				);
				MS_Helper_Html::html_element( $customfield3 );
			?>
		</div>
		<div class="ms-form-element ms-form-element-city">
		    <?php
			    $statesarrcustom = array (
				        "" => "---Select---",
						'AL'=>'Alabama',
						'AK'=>'Alaska',
						'AZ'=>'Arizona',
						'AR'=>'Arkansas',
						'CA'=>'California',
						'CO'=>'Colorado',
						'CT'=>'Connecticut',
						'DE'=>'Delaware',
						'DC'=>'District Of Columbia',
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
						'WY'=>'Wyoming',
				);
			    $customfield4=array(
					'id' => 'regsiter_usr_lsi_state',
					'name' => 'regsiter_usr_lsi_state',
					'type' => MS_Helper_Html::INPUT_TYPE_SELECT,
					'title' => __( 'State', 'membership2' ),
					'before' => __( '', 'membership2' ),
					'after' => __( '', 'membership2' ),
					'field_options' => $statesarrcustom,
					'class' => 'ms-lsi-state-code required',
					'value' => "",
				);
				MS_Helper_Html::html_element( $customfield4 );
			?>
		</div>
		<div class="ms-form-element ms-form-element-city">
		    <?php
			    $customfield5=array(
					'id' => 'regsiter_usr_lsi_postal_code',
					'name' => 'regsiter_usr_lsi_postal_code',
					'type' => MS_Helper_Html::INPUT_TYPE_TEXT,
					'title' => __( 'Zip', 'membership2' ),
					'before' => __( '', 'membership2' ),
					'after' => __( '', 'membership2' ),
					'class' => 'ms-lsi-zipcode-code required',
					'value' => "",
				);
				MS_Helper_Html::html_element( $customfield5 );
			?>
		</div>
		<?php
	}

	function save_custom_extra_fields_of_reg_form($user,$requestarr,$userthis)
	{
		global $wpdb;
		$registereduserid=$user->id;

		add_user_meta($registereduserid, "ms_reg_lsi_address_line1", $requestarr["regsiter_usr_lsi_address_line1"]);
		add_user_meta($registereduserid, "ms_reg_lsi_address_line2", $requestarr["regsiter_usr_lsi_address_line2"]);
		add_user_meta($registereduserid, "ms_reg_lsi_city_txt", $requestarr["regsiter_usr_lsi_city"]);
		add_user_meta($registereduserid, "ms_reg_lsi_state_txt", $requestarr["regsiter_usr_lsi_state"]);
		add_user_meta($registereduserid, "ms_reg_lsi_postal_code", $requestarr["regsiter_usr_lsi_postal_code"]);
	}

	function show_lsi_extra_custom_users_profile_fields($user)
	{
		?>
		<h3>User's address information</h3>
		<table class="form-table">
			<tr>
				<th><label for="user_lsi_address_line1">Address 1</label></th>
				<td>
					<input type="text" name="user_lsi_address_line1" id="user_lsi_address_line1" value="<?php echo esc_attr( get_the_author_meta( 'ms_reg_lsi_address_line1', $user->ID ) ); ?>" class="regular-text" /><br />
					<span class="description">Please enter your Address 1.</span>
				</td>
			</tr>
			<tr>
				<th><label for="user_lsi_address_line2">Address 2</label></th>
				<td>
					<input type="text" name="user_lsi_address_line2" id="user_lsi_address_line2" value="<?php echo esc_attr( get_the_author_meta( 'ms_reg_lsi_address_line2', $user->ID ) ); ?>" class="regular-text" /><br />
					<span class="description">Please enter your Address 2.</span>
				</td>
			</tr>
			<tr>
				<th><label for="user_lsi_city_name">City</label></th>
				<td>
					<input type="text" name="user_lsi_city_name" id="user_lsi_city_name" value="<?php echo esc_attr( get_the_author_meta( 'ms_reg_lsi_city_txt', $user->ID ) ); ?>" class="regular-text" /><br />
					<span class="description">Please enter your city.</span>
				</td>
			</tr>
			<tr>
				<th><label for="user_lsi_state_name">State</label></th>
				<td>
				    <?php
					    $statesarrcustom = array (
								'AL'=>'Alabama',
								'AK'=>'Alaska',
								'AZ'=>'Arizona',
								'AR'=>'Arkansas',
								'CA'=>'California',
								'CO'=>'Colorado',
								'CT'=>'Connecticut',
								'DE'=>'Delaware',
								'DC'=>'District Of Columbia',
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
								'WY'=>'Wyoming',
						);
					?>
					<select name="user_lsi_state_name" id="user_lsi_state_name">
					    <option value="">---Select-----</option>
					    <?php foreach($statesarrcustom as $k=>$v){ ?>
						    <option value="<?php echo $k; ?>" <?php if(get_the_author_meta( 'ms_reg_lsi_state_txt', $user->ID )==$k){ echo 'selected="selected"'; } ?>><?php echo $v; ?></option>
						<?php } ?>
					</select>
					<br />
					<span class="description">Please enter your state.</span>
				</td>
			</tr>
			<tr>
				<th><label for="user_lsi_postal_code">Zip Code</label></th>
				<td>
					<input type="text" name="user_lsi_postal_code" id="user_lsi_postal_code" value="<?php echo esc_attr( get_the_author_meta( 'ms_reg_lsi_postal_code', $user->ID ) ); ?>" class="regular-text" /><br />
					<span class="description">Please enter your zip code.</span>
				</td>
			</tr>
		</table>
		<?php
	}

	function lsi_save_extra_custom_users_profile_fields( $user_id )
	{
		if ( !current_user_can( 'edit_user', $user_id ) )
		return false;

	    $requestarr=$_REQUEST;
	    $registereduserid=$user_id;

	    $getusraddress1=get_user_meta($user_id,"ms_reg_lsi_address_line1",true);
		if($getusraddress1===false)
		{
			add_user_meta($registereduserid, "ms_reg_lsi_address_line1", $requestarr["user_lsi_address_line1"]);
		}else{
			update_user_meta($registereduserid, "ms_reg_lsi_address_line1", $requestarr["user_lsi_address_line1"]);
		}

		$getusraddress2=get_user_meta($user_id,"ms_reg_lsi_address_line2",true);
		if($getusraddress2===false)
		{
			add_user_meta($registereduserid, "ms_reg_lsi_address_line2", $requestarr["user_lsi_address_line2"]);
		}else{
			update_user_meta($registereduserid, "ms_reg_lsi_address_line2", $requestarr["user_lsi_address_line2"]);
		}

		$getusrcityname=get_user_meta($user_id,"ms_reg_lsi_city_txt",true);
		if($getusrcityname===false)
		{
			add_user_meta($registereduserid, "ms_reg_lsi_city_txt", $requestarr["user_lsi_city_name"]);
		}else{
			update_user_meta($registereduserid, "ms_reg_lsi_city_txt", $requestarr["user_lsi_city_name"]);
		}

		$getusrstatename=get_user_meta($user_id,"ms_reg_lsi_state_txt",true);
		if($getusrstatename===false)
		{
			add_user_meta($registereduserid, "ms_reg_lsi_state_txt", $requestarr["user_lsi_state_name"]);
		}else{
			update_user_meta($registereduserid, "ms_reg_lsi_state_txt", $requestarr["user_lsi_state_name"]);
		}

		$getusrpostalcode=get_user_meta($user_id,"ms_reg_lsi_postal_code",true);
		if($getusrpostalcode===false)
		{
			add_user_meta($registereduserid, "ms_reg_lsi_postal_code", $requestarr["user_lsi_postal_code"]);
		}else{
			update_user_meta($registereduserid, "ms_reg_lsi_postal_code", $requestarr["user_lsi_postal_code"]);
		}
	}

	function lsi_show_frontend_extra_custom_users_profile_fields_new($fields, $instance)
	{
		global $wpdb;
		//echo "<pre>";print_r($fields);
		$userid=get_current_user_id();
		$getusraddress1=get_user_meta($userid,"ms_reg_lsi_address_line1",true);
		$getusraddress2=get_user_meta($userid,"ms_reg_lsi_address_line2",true);
        $getusrcityname=get_user_meta($userid,"ms_reg_lsi_city_txt",true);
        $getusrstatename=get_user_meta($userid,"ms_reg_lsi_state_txt",true);
        $getusrpostalcode=get_user_meta($userid,"ms_reg_lsi_postal_code",true);

		$mycustomarr['regsiter_usr_lsi_address_line1']= array(
					'id' => 'regsiter_usr_lsi_address_line1',
					'name' => 'regsiter_usr_lsi_address_line1',
					'type' => MS_Helper_Html::INPUT_TYPE_TEXT,
					'title' => __( 'Address 1', 'membership2' ),
					'before' => __( '', 'membership2' ),
					'after' => __( '', 'membership2' ),
					'class' => 'ms-address_field1-code required',
					'value' => $getusraddress1,
				);
		$mycustomarr['regsiter_usr_lsi_address_line2']=array(
				'id' => 'regsiter_usr_lsi_address_line2',
				'name' => 'regsiter_usr_lsi_address_line2',
				'type' => MS_Helper_Html::INPUT_TYPE_TEXT,
				'title' => __( 'Address 2', 'membership2' ),
				'before' => __( '', 'membership2' ),
				'after' => __( '', 'membership2' ),
				'class' => 'ms-address_field2-code',
				'value' => $getusraddress2,
			);

		$mycustomarr['regsiter_usr_lsi_city']=array(
				'id' => 'regsiter_usr_lsi_city',
				'name' => 'regsiter_usr_lsi_city',
				'type' => MS_Helper_Html::INPUT_TYPE_TEXT,
				'title' => __( 'City', 'membership2' ),
				'before' => __( '', 'membership2' ),
				'after' => __( '', 'membership2' ),
				'class' => 'ms-lsi-city-code required',
				'value' => $getusrcityname,
			);

			$statesarrcustom = array (
			        "" =>"---Select---",
					'AL'=>'Alabama',
					'AK'=>'Alaska',
					'AZ'=>'Arizona',
					'AR'=>'Arkansas',
					'CA'=>'California',
					'CO'=>'Colorado',
					'CT'=>'Connecticut',
					'DE'=>'Delaware',
					'DC'=>'District Of Columbia',
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
					'WY'=>'Wyoming',
			);
		$mycustomarr['regsiter_usr_lsi_state']=array(
				'id' => 'regsiter_usr_lsi_state',
				'name' => 'regsiter_usr_lsi_state',
				'type' => MS_Helper_Html::INPUT_TYPE_SELECT,
				'title' => __( 'State', 'membership2' ),
				'before' => __( '', 'membership2' ),
				'after' => __( '', 'membership2' ),
				'field_options' => $statesarrcustom,
				'class' => 'ms-lsi-state-code required',
				'value' => $getusrstatename,
			);

		$mycustomarr['regsiter_usr_lsi_postal_code']=array(
				'id' => 'regsiter_usr_lsi_postal_code',
				'name' => 'regsiter_usr_lsi_postal_code',
				'type' => MS_Helper_Html::INPUT_TYPE_TEXT,
				'title' => __( 'Zip', 'membership2' ),
				'before' => __( '', 'membership2' ),
				'after' => __( '', 'membership2' ),
				'class' => 'ms-lsi-zipcode-code required',
				'value' => $getusrpostalcode,
			);

		$fieldsfinalarr = array_slice($fields, 0, 5, true) + $mycustomarr + array_slice($fields, 5, count($fields) - 1, true) ;

		return $fieldsfinalarr;
	}

	function update_lsi_show_frontend_extra_custom_users_profile_fields($memberres)
	{
		global $wpdb;
		$crnruserid=$memberres->id;
		$requestarr=$_POST;
		$getusraddress1=get_user_meta($crnruserid,"ms_reg_lsi_address_line1",true);
		if($getusraddress1===false)
		{
			add_user_meta($crnruserid, "ms_reg_lsi_address_line1", $requestarr["regsiter_usr_lsi_address_line1"]);
		}else{
			update_user_meta($crnruserid, "ms_reg_lsi_address_line1", $requestarr["regsiter_usr_lsi_address_line1"]);
		}

		$getusraddress2=get_user_meta($crnruserid,"ms_reg_lsi_address_line2",true);
		if($getusraddress2===false)
		{
			add_user_meta($crnruserid, "ms_reg_lsi_address_line2", $requestarr["regsiter_usr_lsi_address_line2"]);
		}else{
			update_user_meta($crnruserid, "ms_reg_lsi_address_line2", $requestarr["regsiter_usr_lsi_address_line2"]);
		}

		$getusrcityname=get_user_meta($crnruserid,"ms_reg_lsi_city_txt",true);
		if($getusrcityname===false)
		{
			add_user_meta($crnruserid, "ms_reg_lsi_city_txt", $requestarr["regsiter_usr_lsi_city"]);
		}else{
			update_user_meta($crnruserid, "ms_reg_lsi_city_txt", $requestarr["regsiter_usr_lsi_city"]);
		}

		$getusrstatename=get_user_meta($crnruserid,"ms_reg_lsi_state_txt",true);
		if($getusrstatename===false)
		{
			add_user_meta($crnruserid, "ms_reg_lsi_state_txt", $requestarr["regsiter_usr_lsi_state"]);
		}else{
			update_user_meta($crnruserid, "ms_reg_lsi_state_txt", $requestarr["regsiter_usr_lsi_state"]);
		}

		$getusrpostalcode=get_user_meta($crnruserid,"ms_reg_lsi_postal_code",true);
		if($getusrpostalcode===false)
		{
			add_user_meta($crnruserid, "ms_reg_lsi_postal_code", $requestarr["regsiter_usr_lsi_postal_code"]);
		}else{
			update_user_meta($crnruserid, "ms_reg_lsi_postal_code", $requestarr["regsiter_usr_lsi_postal_code"]);
		}
	}

	function show_lsi_address_fields_on_account_section($memobj)
	{
		$crnruserid=$memobj->id;
		$getusraddress1=get_user_meta($crnruserid,"ms_reg_lsi_address_line1",true);
		$getusraddress2=get_user_meta($crnruserid,"ms_reg_lsi_address_line2",true);
		$getusrcityname=get_user_meta($crnruserid,"ms_reg_lsi_city_txt",true);
		$getusrstatename=get_user_meta($crnruserid,"ms_reg_lsi_state_txt",true);
		$getusrpostalcode=get_user_meta($crnruserid,"ms_reg_lsi_postal_code",true);
		?>
		<div class="show_users_address_details_lsi">
		    <table>
			    <tbody>
				    <tr>
					    <th>Address:</th>
					    <td>
							<p>
								<?php echo $getusraddress1; ?>, <?php echo $getusraddress2; ?> <br/>
								<?php echo $getusrcityname; ?>, <br/>
								<?php echo $getusrstatename; ?>, <?php echo $getusrpostalcode; ?><br/>
							</p>
					  </td>
					</tr>
				</tbody>
			</table>
		</div>
		<?php
	}
}
?>
