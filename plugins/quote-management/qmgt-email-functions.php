<?php
define( 'MC4WP_VERSION_CRAFT', '4.2.4' );
//Add custom capibility for quote_manager
function leadership_email_manager_caps() {

		// Add the roles you'd like to administer the custom post types
		$roles = array('quote_manager','editor','administrator');

		// Loop through each role and assign capabilities
		foreach($roles as $the_role) {

		     $role = get_role($the_role);

	             $role->add_cap( 'read' );
	             $role->add_cap( 'read_email_post');
	             $role->add_cap( 'read_private_email_posts' );
	             $role->add_cap( 'edit_email_post' );
	             $role->add_cap( 'edit_email_posts' );
	             $role->add_cap( 'edit_others_email_posts' );
	             $role->add_cap( 'edit_published_email_posts' );
	             $role->add_cap( 'publish_email_posts' );
	             $role->add_cap( 'delete_others_email_posts' );
	             $role->add_cap( 'delete_private_email_posts' );
	             $role->add_cap( 'delete_published_email_posts' );
							 //Add quote post features
							 $role->add_cap( 'read_quote_post');
							 $role->add_cap( 'read_private_quote_posts' );
							 $role->add_cap( 'edit_quote_post' );
							 $role->add_cap( 'edit_quote_posts' );
							 $role->add_cap( 'edit_others_quote_posts' );
							 $role->add_cap( 'edit_published_quote_posts' );
							 $role->add_cap( 'publish_quote_posts' );
							 $role->add_cap( 'delete_others_quote_posts' );
							 $role->add_cap( 'delete_private_quote_posts' );
							 $role->add_cap( 'delete_published_quote_posts' );
	}
}
add_action( 'admin_init', 'leadership_email_manager_caps');


add_action('admin_menu' , 'daily_email_settings_page');
function daily_email_settings_page() {
	add_submenu_page( 'edit.php?post_type=emails', 'Daily Email Settings', 'Settings', 'read', basename(__FILE__), 'daily_email_settings');
	add_submenu_page( 'edit.php?post_type=emails', 'Generate Email', 'Generate Email', 'read', 'generate-email', 'func_generate_email_manually');
	add_submenu_page( 'edit.php?post_type=emails', 'Mailchimp Settings', 'Mailchimp Settings', 'read', 'mc_settings', 'mailchimp_settings_page');
}


function func_generate_email_manually(){
if(isset($_POST['generate_email_manually_submit'])){
	if(isset($_POST['generate_email_date'])){
		$generate_email_date = $_POST['generate_email_date'];
		$email_date = explode('-', $generate_email_date);
		$month = $email_date[0];
		$day   = $email_date[1];
		$year  = $email_date[2];
		$generate_email_date = $day.'-'.$month.'-'.$year;
		$generate_email_date = date("Y-m-d 07:01:00", strtotime($generate_email_date));
		$email_quote_id = $_POST['email_quote_id'];
		$email_post_id = generate_daily_email($generate_email_date, $email_quote_id, 'yes');

		$email_post_edit_link = get_edit_post_link( $email_post_id );
		echo "<div class='updated'><p>Successfully Generated! <a href=".$email_post_edit_link.">Click Here To Edit Email</a></p></div>";
	}else{
		echo "<div class='updated error'><p>Failed!</p></div>";
	}

}

?>

<div class="wrap">
	<h2 style="padding-bottom: 25px;"><?php echo __('Generate Email Manually'); ?></h2>
	<?php
		//echo date("Y-m-d 07:01:00");
		$search_string_q = '';
		if(isset($_POST['search_quote_to_select'])){
				$search_string_q = $_POST['search_quote_to_select'];
		}
	?>
	<form name="form_search_quotes_manually" method="post" action="">
		<table class="form-table" style="margin-top:0;">
			<tr valign="top">
				<td style="padding-top:0;">
					<input type="text" name="search_quote_to_select" value="<?php echo $search_string_q; ?>" placeholder="Type Quote" /> &nbsp;
					<input type="submit" name="search_quote_manually_submit" class="button-primary" value="<?php _e('Search') ?>" />
				</td>
			</tr>
		</table>
	</form>


			<?php if(isset($_POST['search_quote_to_select'])){ ?>
				<br /><br />
					<form name="generate_email_manually" method="post" action="">
						<table class="form-table" style="margin-top:0;">
						<tr valign="top">
							<td style="padding-top:0;" colspan="2">
								<h4><?php echo __('Select Quote'); ?></h4>
							<?php
								$search_string = $_POST['search_quote_to_select'];
								$args_quote_search = array(
									'post_status' => 'publish',
									'post_type'   => 'quote',
									'orderby'   => 'title',
									'order'   => 'ASC',
									's'   => $search_string,
									'posts_per_page' => -1
								);
								$the_query_quoteselect = new WP_Query( $args_quote_search );

								if ( $the_query_quoteselect->have_posts() ) {
									echo "<table class='wp-list-table widefat fixed striped posts'><thead><tr><th style='width:20px;'>&nbsp;</th><th style='width:50px;'>Title</th><th>1st paragraph of Quote</th></tr></thead><tbody>";
									while ( $the_query_quoteselect->have_posts() ) {
										$the_query_quoteselect->the_post();
										$quote_id = get_the_ID();
										$quote_title = strip_tags(get_the_title());
										$quote_content = strip_tags(get_the_content());
										$quote_content = substr(get_the_content(), 0, 30);
										$quote_content .= '...';

										$quote_str = get_the_content();
										$quote_str = apply_filters('the_content', $quote_str);
										preg_match('/<p>(.*?)<\/p>/i', $quote_str, $paragraphs);
										//echo $paragraphs[0]; // Paragraph 1

										echo '<tr><td width="20"><input type="radio" name="email_quote_id" value="'.$quote_id.'"></td><td>'.$quote_title.'</td><td>'.$paragraphs[0].'</td></tr>';
									}
									echo '</tbody></table>';
									/* Restore original Post Data */
									wp_reset_postdata();
								}
							?>
							</td>
						</tr>


			<tr valign="top">
				<th scope="row" style="padding-top:0;"><?php echo __('Enter Date:'); ?></th>
				<td style="padding-top:0;">
					<input type="text" name="generate_email_date" id="generate_email_date" value="" placeholder="MM-DD-YYYY" />
				</td>
			</tr>
		</table>
		<p class="submit">
			<input type="submit" name="generate_email_manually_submit" class="button-primary" value="<?php _e('Generate') ?>" />
		</p>
		</form>
		<?php } ?>


	<script>
			jQuery(document).ready(function ($) {
				jQuery( "#generate_email_date" ).datepicker({ minDate: 0, dateFormat: 'mm-dd-yy' });
			});
	</script>
</div>
<?php
}

function daily_email_admin_styles() {
  wp_enqueue_style( 'jquery-ui-datepicker-style' , '//ajax.googleapis.com/ajax/libs/jqueryui/1.10.4/themes/smoothness/jquery-ui.css');
}
add_action('admin_print_styles', 'daily_email_admin_styles');
function daily_email_admin_scripts() {
  wp_enqueue_script( 'jquery-ui-datepicker' );
}
add_action('admin_enqueue_scripts', 'daily_email_admin_scripts');


function daily_email_settings() {
	if(isset($_POST['email_options_submit'])){

		update_option( 'notified_emails', $_POST['notified_emails'] );
		update_option( 'notify_time_advance', $_POST['notify_time_advance'] );

		update_option('notification_email_subject', stripslashes_deep($_POST['notification_email_subject']));
		update_option('notification_email_message', stripslashes_deep($_POST['notification_email_message']));

		update_option('email_template', stripslashes_deep($_POST['email_template']));

		echo "<div class='updated'><p>Successfully Updated</p></div>";
	}
?>
<div class="wrap">
	<h2 style="padding-bottom: 25px;"><?php echo __('Daily Email Settings'); ?></h2>
	<form name="daily_email_settings" method="post" action="">
		<table class="form-table" style="margin-top:0;">
			<tr valign="top">
				<th scope="row" style="padding-top:0;"><?php echo __('Who should get notified in advance of emails?'); ?></th>
				<td style="padding-top:0;">
					<input type="text" name="notified_emails" value="<?php echo get_option('notified_emails'); ?>" />
				</td>
			</tr>

			<tr valign="top">
				<th scope="row"><?php echo __('How long should we notify them in advance of it being sent?'); ?></th>
				<td>
					<input type="text" name="notify_time_advance" value="<?php echo get_option('notify_time_advance'); ?>" /> hours
				</td>
			</tr>

			<tr valign="top">
				<th scope="row"><label for="notification_email_subject">Notification Subject</label></th>
				<td><input type="text" name="notification_email_subject" id="notification_email_subject" value="<?php echo get_option('notification_email_subject'); ?>" class="regular-text" /><p class="description">Supported tags:<br />
					%%email-subject%% = (Generated Email Subject)<br />
					</p></td>
			</tr>
			<tr valign="top">
				<th scope="row"><label for="notification_email_message">Notification Message</label></th>
				<td><textarea name="notification_email_message" id="notification_email_message" class="large-text" rows="5"><?php echo stripslashes(get_option('notification_email_message')); ?></textarea><p class="description">Supported tags:<br />
					%%quote-email-subject%% = (Generated Email Subject From Quote)<br />
					%%approval-link%% = (Link Approve This Message )<br />
					%%rejects-link%% = (Link Rejects This Message )<br />
					%%edit-link%% = (Link Edit This Email)<br />
					%%quote-content%% = (Generated Quote Content)<br />
					%%sendout-date%% = (Send out date when quote email will be sent)<br />
					%%last-run-date%% = (Last Run date when quote was sent)
					</p></td>
			</tr>

			<tr valign="top">
				<th scope="row"><label for="email_template">Email Template</label></th>
				<td><textarea name="email_template" id="email_template" class="large-text" rows="20"><?php echo stripslashes_deep(get_option('email_template')); ?></textarea><p class="description">Supported tags:<br />
					%%content%% = (Auto generated text)<br />
					%%admin_email%% = ( Site admin email )<br />
					%%date%% = (Current Date)<br />
					</p></td>
			</tr>
		</table>

		<input type="hidden" name="action" value="update" />
		<input type="hidden" name="page_options" value="notify_in_advance,how_long_should_notify,email_template" />

		<p class="submit">
			<input type="submit" name="email_options_submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
		</p>
	</form>
</div>

<?php
}

function mailchimp_settings_page() {
?>
<div class="wrap">
	<h2 style="padding-bottom: 25px;"><?php echo __('Mailchimp Settings'); ?></h2>
	<?php
		if(isset($_POST['mc_options_submit'])){
			update_option( 'test_email_address', $_POST['test_email_addr'] );
			update_option( 'mc_api_key', $_POST['mc_api_key'] );
			//update_option( 'cc_secret_key', $_POST['cc_secret_key'] );
			//update_option( 'cc_access_token', $_POST['cc_access_token'] );
			update_option( 'mc_contact_list_id', $_POST['mc_contact_list_id'] );
			update_option( 'mc_from_email', $_POST['mc_from_email'] );
			//update_option( 'mc_reply_to_email', $_POST['mc_reply_to_email'] );
			update_option( 'mc_reply_to_name', $_POST['mc_reply_to_name'] );
			update_option( 'mc_from_name', $_POST['mc_from_name'] );

			update_option('mc_email_template', stripslashes_deep($_POST['email_template']));
			echo "<div class='updated'><p>Successfully Updated</p></div>";
		}

		if(isset($_POST['mc_test_email_submit'])){
			if(isset($_POST['test_email_addr'])){
				$email_address = $_POST['test_email_addr'];
				$email_post_id = $_POST['email_post'];
				send_mc_email_template($email_address, $email_post_id);
				echo "<div class='updated'><p>Email Sent! Please also check spam</p></div>";
			}
		}
	?>
	<form name="mc_email_testing" method="post" action="">
		<table class="form-table" style="margin-top:0;">

			<tr valign="top">
				<th scope="row"><label for="test_email_addr">Send Test Email To: </label></th>
				<td><input type="text" name="test_email_addr" id="test_email_addr" value="" class="regular-text" placeholder="info@youremail.com" /></td>
			</tr>
			<tr valign="top">
				<th scope="row"><label for="mc_select_email">Select Email: </label></th>
				<td>
				<?php
				$args = array(
					'post_type'  => 'emails',
					'post_status' => 'any',
					'orderby' => 'post_date',
					'order' => 'ASC',
					'date_query' => array(
						array(
							'after'     => date("F j, Y, g:i a"),
							'inclusive' => true,
						),
					),
					'posts_per_page' => -1,
				);
				$email_query = new WP_Query( $args );
				if ( $email_query->have_posts() ) {
					echo '<select name="email_post" id="email_post">';
					while ( $email_query->have_posts() ) {
						$email_query->the_post();
						echo '<option value="'.get_the_ID().'">' . get_the_title() . '</option>';
					}
					echo '</select>';
				}
				wp_reset_postdata();
				?>

				</td>
			</tr>
			<tr valign="top">
				<th scope="row"></th>
				<td><input type="submit" name="mc_test_email_submit" class="button-primary" value="<?php _e('Send Email Above Address!') ?>" /></td>
			</tr>
		</table>
	</form>

	<br /><br /><br />

	<form name="mc_email_settings" method="post" action="">
		<table class="form-table" style="margin-top:0;">
			<tr valign="top">
				<th scope="row" style="padding-top:0;"><?php echo __('API KEY: '); ?></th>
				<td style="padding-top:0;">
					<input type="text" name="mc_api_key" value="<?php echo get_option('mc_api_key'); ?>" class="regular-text" />
				</td>
			</tr>
			<tr valign="top">
				<th scope="row"><label for="mc_contact_list_id">Contact List: </label></th>
				<?php
				$mc_contact_list_id = get_option('mc_contact_list_id');
				try {
					$mc_api_key = get_option( 'mc_api_key' );
					$mcAPI3 = new MC4WP_API_v3($mc_api_key);
					$mc_lists = $mcAPI3->get_lists();
				}catch( MC4WP_API_Resource_Not_Found_Exception $e ) {
				   echo "Unable to Connnect!";
				}catch( MC4WP_API_Exception $e ) {
				   // other errors.
				   echo "Unable to Connect!";
				}
				?>
				<td>
					<select id="mc_contact_list_id" name="mc_contact_list_id">
						<?php
						if(isset($mc_lists)){
								foreach($mc_lists as $list){
									//print_r($list);
									echo '<option ';
									if($list->id == $mc_contact_list_id) echo ' selected="selected" ';
									echo 'value="'.$list->id.'">';
									echo $list->name;
									echo '</option>';
								}
						}
						?>
					</select>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row"><label for="mc_from_email">From Email: </label></th>
				<td><input type="text" name="mc_from_email" id="mc_from_email" value="<?php echo get_option('mc_from_email'); ?>" class="regular-text" /><br /><small>This must be a <strong>verified email address</strong> associated with the account</small></td>
			</tr>

			<tr valign="top">
				<th scope="row"><label for="mc_from_name">From Name: </label></th>
				<td><input type="text" name="mc_from_name" id="mc_from_name" value="<?php echo get_option('mc_from_name'); ?>" class="regular-text" /></td>
			</tr>

			<!--<tr valign="top">
				<th scope="row"><label for="mc_reply_to_email">Reply to Email: </label></th>
				<td><input type="text" name="mc_reply_to_email" id="mc_reply_to_email" value="<?php //echo get_option('mc_reply_to_email'); ?>" class="regular-text" /><br /><small>This must be a <strong>verified email address</strong> associated with the account</small></td>
			</tr>-->
			<tr valign="top">
				<th scope="row"><label for="mc_reply_to_name">Reply to Name: </label></th>
				<td><input type="text" name="mc_reply_to_name" id="mc_reply_to_name" value="<?php echo get_option('mc_reply_to_name'); ?>" class="regular-text" /></td>
			</tr>
			<tr valign="top">
				<th scope="row"><label for="email_template">Email Template</label></th>
				<td><textarea name="email_template" id="email_template" class="large-text" rows="20"><?php echo stripslashes_deep(get_option('mc_email_template')); ?></textarea><p class="description">Supported tags:<br />
					%%logo%% = ( Generate website logo )<br />
					%%author_photo%% = ( Quote author photo )<br />
					%%QUOTETEXT%% = ( Generate the Quote )<br />
					%%QUOTEAUTHOR%% = ( Quote author name )<br />
					%%social_share%% = ( Share Quote in Social Media )<br />
					%%THOUGHTBEHIENDTHEQUOTE%% = ( Author's thought behind the quote )<br />
					</p></td>
			</tr>
		</table>

		<p class="submit">
			<input type="submit" name="mc_options_submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
		</p>
	</form>
</div>
<?php
}

add_action( 'init', 'call_generate_daily_email');
function call_generate_daily_email(){
	if(isset($_GET['generate_email']) && ($_GET['generate_email'] == 'yes')){
		generate_daily_email();
	}

	//run by hosting corn automatically in every night 2am
	if(isset($_GET['corn']) && ($_GET['corn'] == 'dodaily')){
		$to = 'anasbinmukim@gmail.com';
		$subject = 'Sample wp email testing from leadershipology';
		add_filter('wp_mail_content_type',create_function('', 'return "text/html"; '));
		add_filter( 'wp_mail_from_name', function( $name ) { return get_option('quote_email_from_name'); });
		add_filter( 'wp_mail_from', function( $email ) { return get_option('quote_email_from_email'); });
		$message .= 'HTML messege<br/>';
		wp_mail( $to, $subject, $message );
	}
}

//Return quote ID with making sure same title quote was not sent yesterday.
function qmgt_select_no_duplicate_quote_for_email(){
		$email_quote = qmgt_pick_available_quote_for_email();
		$quote_id = '';
		$quote_title = '';
		if(isset($email_quote['quote_id']) && isset($email_quote['quote_title'])){
			$quote_id = $email_quote['quote_id'];
			$quote_title = $email_quote['quote_title'];
		}

		$latest_campaign_title = get_option('rmt_latest_campaign_title');

		//Reqursive call without match condition.
		if(($latest_campaign_title !== $quote_title) && ($quote_title != '')){
				return $quote_id;
		}else{
				qmgt_select_no_duplicate_quote_for_email();
		}

}


function qmgt_pick_available_quote_for_email(){
		$args = array(
					'post_type' => 'quote',
					'posts_per_page' => 1,
					'post_status' => 'publish',
					'meta_key' => '_rmt_quote_email',
					'meta_value' => 'yes',
					'meta_compare' => 'NOT EXISTS', // if the meta field does not exists or if the meta field not set yet
					'orderby' => 'rand'
				);
		$quote_data = array();
		$quote_posts = new WP_Query( $args );
		if($quote_posts->have_posts()) {
				global $post;
				while($quote_posts->have_posts()): $quote_posts->the_post();
					$quote_data['quote_id'] = get_the_ID();
					$quote_data['quote_title'] = get_the_title();
				endwhile;
				wp_reset_postdata();
		}

		return $quote_data;

}

function generate_daily_email($email_date = '', $quote_id = '', $manual_generate = 'no'){
		date_default_timezone_set("America/Detroit");

		//check if alrady generated
		$existing_email_post_id = check_already_generated_email($email_date);
		if($existing_email_post_id){
			return $existing_email_post_id;
		}

		$advance_hours = get_option('notify_time_advance');

		if($quote_id > 0 ){
			$args = array(
				'post_status' => 'publish',
				'post_type'   => 'quote',
				'p'   => $quote_id,
				'posts_per_page' => -1
			);
		}else{
			$args = array(
						'post_type' => 'quote',
						'posts_per_page' => 1,
						'post_status' => 'publish',
						'meta_key' => '_rmt_quote_email',
						'meta_value' => 'yes',
						'meta_compare' => 'NOT EXISTS', // if the meta field does not exists or if the meta field not set yet
						'orderby' => 'rand'
					);
		}
		$quote_posts = new WP_Query( $args );
		if($quote_posts->have_posts()) {
			global $post;
			while($quote_posts->have_posts()): $quote_posts->the_post();
				$user_id = get_current_user_id();
				/***Quote title for this generated email****/
				$email_quote_title = get_the_title();

				/***Quote ID for this generated email****/
				$email_quote_id = $post->ID;

				$email_content = apply_filters('the_content', get_post_field('post_content', $post->ID));
				$email_content = str_replace(array("\n", "\r"), '', $email_content);

				if($email_date)
					$post_title = date("l, F j, Y", strtotime($email_date));
				else
					$post_title = date("l, F j, Y", strtotime('+'.$advance_hours.' hours'));

				if($email_date)
					$email_scheduled_date = date("Y-m-d 07:01:00", strtotime($email_date));
				else
					$email_scheduled_date = date("Y-m-d 07:01:00", strtotime('+'.$advance_hours.' hours'));

				$email_status = 'draft';
				if($manual_generate == 'yes')
					$email_status = 'future';

				$defaults = array(
							  'post_type'      => 'emails',
							  'post_title'     => $post_title,
							  //'post_content'   => mysql_real_escape_string($email_content),
							  'post_status'    => $email_status,
							  'post_date'    => $email_scheduled_date,
							  'post_date_gmt'    => $email_scheduled_date,
							  'post_author'    => $user_id
							);
				if($post_id = wp_insert_post( $defaults )) {
					// add post meta data
					update_post_meta($post_id, '_rmt_quote_id', $post->ID);

					update_post_meta($post_id, '_rmt_quote_title', $email_quote_title);
					update_option('rmt_latest_campaign_title', $email_quote_title);

					$quote_author = rm_get_single_tax_term($post->ID, 'quoteauthor');
					update_post_meta($post_id, '_rmt_quote_author', $quote_author);
					//add_post_meta($post->ID, '_rmt_quote_email', 'yes');
					update_post_meta($post->ID, '_rmt_quote_email', 'yes');
					// add email id to the quote
					update_post_meta($post->ID, '_rmt_email_id', $post_id);
					// set the date for last ran
					//update_post_meta($post->ID, '_cmb_last_ran_date', $email_scheduled_date);
					// add thought behind quote meta to email post
					/*if( get_post_meta($post->ID, '_cmb_thought_behind_quote', true) ) {
						$th_bq = get_post_meta($post->ID, '_cmb_thought_behind_quote', true);
						update_post_meta($post_id, '_cmb_email_thought_behind_quote', $th_bq);
					}*/

					$encryptedKey = substr( md5( $post_id ), 0, 20 );
					update_post_meta($post_id, '_rmt_email_secret_key', $encryptedKey);



					// create a new constant contact campaign
					if($manual_generate == 'yes') {
						//create_new_cc_campaign($post->ID);
						create_mc_campaign($post->ID, $email_scheduled_date);
					}

					if($manual_generate == 'no')
						rm_admin_notification($post_id);

				}
			endwhile;
			wp_reset_postdata();
		}

		return $post_id;

}

function create_mc_campaign($quote_id, $schedule_date = '') {
	$mc_api_key = get_option('mc_api_key');
	$mc_from_email = get_option('mc_from_email');
	$mc_from_name = get_option('mc_from_name');
	//$mc_reply_to_email = get_option('mc_reply_to_email');
	$mc_reply_to_name = get_option('mc_reply_to_name');
	$mc_contact_list_id = get_option('mc_contact_list_id');

	$current_email_id = get_post_meta($quote_id, '_rmt_email_id', true);
	$email_message_template = get_option('mc_email_template');

	$current_quote_title = get_the_title( $quote_id );
	/*$search_title = array('&#8211;', '&#46;', '&#8230;');
	$replace_title = array('-', '.', '...');
	$current_quote_title = str_replace($search_title, $replace_title, $current_quote_title);*/
	$current_quote_title = html_entity_decode(htmlspecialchars_decode($current_quote_title));

	$search = array();
	$replace = array();

	$search[] = '%%logo%%';
	$replace[] = '<a style="border: none;" href="'.home_url().'" class="buttonlink"><img width="400" height="160" border="0" src="'.EMAIL_FOLDER_URL.'images/leadershipology-logo.jpg"></a>';

	if(get_option('quote_photo_url') != ''){
		$email_author_photo = '<img src="'.get_option('quote_photo_url').'" alt="" />';
	}else{
		$email_author_photo = '<img src="'.EMAIL_FOLDER_URL.'images/200x160_PK.jpg" alt="" />';
	}

	$search[] = '%%author_photo%%';
	$replace[] = $email_author_photo;

	$search[] = '%%quote_title%%';
	$replace[] = get_the_title( $quote_id );

	$search[] = '%%social_share%%';
	$replace[] = '<a href="http://www.facebook.com/sharer.php?u=' . urlencode( get_permalink( $quote_id ) ) . '&amp;t='. rawurlencode( get_the_title( $quote_id ) ).'" target="_blank" style="margin-right: 5px; text-decoration: none;">Share on Facebook</a> | <a href="https://twitter.com/intent/tweet?text='. rawurlencode( get_the_title( $quote_id ) ).'&amp;url=' . urlencode( get_permalink( $quote_id ) ) . '" target="_blank" style="margin-left: 5px; text-decoration: none;">Share on Twitter</a>';

	$search[] = '%%QUOTETEXT%%';
	$content_quote = get_post($quote_id);
	$content_quote_text = $content_quote->post_content;
	$content_quote_text = apply_filters('the_content', $content_quote_text);
	//$content_quote_text = str_replace(array('<p>', '</p>'), '', $content_quote_text);
	$replace[] = $content_quote_text;
	//$replace[] = htmlspecialchars($content_quote_text, ENT_QUOTES, 'UTF-8',false);

	$search[] = '%%QUOTEAUTHOR%%';
	$update_quote_author = get_post_meta($current_email_id, '_rmt_quote_author', true);
	if($update_quote_author == ''){
			$update_quote_author = 'Unknown';
	}
	$replace[] = '&ndash; '. $update_quote_author;

	$search[] = '%%THOUGHTBEHIENDTHEQUOTE%%';
	if( get_post_meta($quote_id, '_cmb_thought_behind_quote', true) ) {

	$thouth_behind_the_quote = get_post_meta($quote_id, '_cmb_thought_behind_quote', true);
	$thouth_behind_the_quote = apply_filters('the_content', $thouth_behind_the_quote);
	$thouth_behind_the_quote = str_replace(']]>', ']]&gt;', $thouth_behind_the_quote);

	$replace[] = '<tr>
					<td valign="top" style="padding-bottom: 14px;">
					<p style="text-align:left; margin-bottom:0; padding-bottom:0;"><span style="font-family:Helvetica, Arial, sans-serif; font-size:14px; line-height:20px; font-weight: lighter;"><strong>Thought Behind the Quote:</strong></span></p>

					<span style="font-family:Helvetica, Arial, sans-serif; font-size:14px; line-height:20px;"><span style="font-family:Helvetica, Arial, sans-serif; font-size:14px; line-height:20px;"><span style="font-family:Helvetica, Arial, sans-serif; font-size:14px; line-height:20px;">'.$thouth_behind_the_quote.'</span></span></span>
					</td>
				</tr>';
	} else
		$replace[] = '';

	$email_template = str_replace($search, $replace, $email_message_template);
	$email_template = stripslashes($email_template);


	//$mc = new MCAPI($mc_api_key);
	$mcAPI3 = new MC4WP_API_v3($mc_api_key);

	//$camp_options = array('list_id' => $mc_contact_list_id, 'subject' => $current_quote_title, 'from_email' => $mc_from_email, 'from_name' => $mc_from_name, 'to_name' => $mc_reply_to_name, 'title' => $current_quote_title);

	$camp_options = array(
			'type' => 'regular',
			'recipients' => array('list_id' => $mc_contact_list_id),
			'settings' => array(
				'subject_line' => $current_quote_title,
				'title' => $current_quote_title,
				'from_name' => $mc_from_name,
				'reply_to' => $mc_from_email,
				'to_name' => $mc_reply_to_name
			),
	);

	$camp_contents = array('html' => $email_template);

	try {
	  $campaign_new = $mcAPI3->add_campaign($camp_options);
		if(isset($campaign_new->id)){
			$campaign_id = $campaign_new->id;
			update_post_meta($quote_id, '_rmt_campaign_id', $campaign_id);
			$mcAPI3->update_campaign_content( $campaign_id, $camp_contents );

			//schedule campaign
			if($schedule_date != ''){
				$email_schedule_date = date('Y-m-d 07:00', strtotime($schedule_date));
			}else{
				$email_schedule_date = get_the_date('Y-m-d 07:00', $current_email_id);
			}
			$schedule_data = array(
				'schedule_time' => $email_schedule_date,
			);
			$mcAPI3->campaign_action( $campaign_id, 'schedule', $schedule_data );

		}

	}catch( MC4WP_API_Resource_Not_Found_Exception $e ) {
	   echo "Unable to Create New Campaign!";
	}catch( MC4WP_API_Exception $e ) {
	   // other errors.
	   echo "Unable to Create New Campaign!";
	}

	//$campaign_id = $mc->campaignCreate("regular", $camp_options, $camp_contents);

	// if ($mc->errorCode) {
	// 	echo "Unable to Create New Campaign!";
	// 	echo "<br />Code=".$mc->errorCode;
	// 	echo "<br />Msg=".$mc->errorMessage."\n";
	// } else {
	// 	//echo 'Campaign Created';
	// 	update_post_meta($quote_id, '_rmt_campaign_id', $campaign_id);
	//
	// 	if($schedule_date != ''){
	// 		$email_schedule_date = $schedule_date;
	// 	}else{
	// 		$email_schedule_date = get_the_date('Y-m-d 07:01:00', $current_email_id);
	// 	}
	//
	// 	//$schedule_for = '2018-04-01 09:05:21';
	// 	$mc->campaignSchedule($campaign_id, $email_schedule_date);
	// }
}

function rm_get_single_tax_term($post_id, $taxonomy) {
    $terms = wp_get_object_terms($post_id, $taxonomy);
    if(!is_wp_error($terms)) {
        return $terms[0]->name;
    }
}


//Return email post id if exist FALSE otherwise
function check_already_generated_email($query_date = ''){
	$today = array();

	if($query_date){
		$time  = strtotime($query_date);
		$today['mday']   = date('d',$time);
		$today['mon'] = date('m',$time);
		$today['year']  = date('Y',$time);
	}else{
		$advance_hours = get_option('notify_time_advance');
		$email_scheduled_date = date("Y-m-d H:i:s", strtotime('+'.$advance_hours.' hours'));
		$time  = strtotime($email_scheduled_date);
		$today['mday']   = date('d',$time);
		$today['mon'] = date('m',$time);
		$today['year']  = date('Y',$time);

		//$today = getdate();
	}

	$args = array(
		'post_type'  => 'emails',
		'post_status' => 'any',
		'orderby' => 'post_date',
		'order' => 'ASC',
		'date_query' => array(
			array(
				'year'  => $today['year'],
				'month' => $today['mon'],
				'day'   => $today['mday'],
			),
		),
		'posts_per_page' => 1,
	);

	$post_id = '';

	$email_query = new WP_Query( $args );
	if ( $email_query->have_posts() ) {
		while ( $email_query->have_posts() ) {
			$email_query->the_post();
			$post_id = get_the_ID();
		}
	}
	wp_reset_postdata();

	return $post_id;

}


add_action( 'wp', 'leadershipology_setup_schedule_quote' );
/**
 * On an early action hook, check if the hook is scheduled - if not, schedule it.
 */
function leadershipology_setup_schedule_quote() {
	if ( ! wp_next_scheduled( 'leadershipology_daily_quote_generate036' ) ) {
		wp_schedule_event( time(), 'daily', 'leadershipology_daily_quote_generate036');
	}
}


//add_action( 'leadershipology_daily_quote_generate235', 'leadershipology_do_this_daily_generate' );
add_action( 'leadershipology_daily_quote_generate036', 'leadershipology_do_this_daily_generate' );
/**
 * On the scheduled action hook, run a function.
 */
function leadershipology_do_this_daily_generate() {
	// do something everyday
	$selected_quote_id = qmgt_select_no_duplicate_quote_for_email();
	generate_daily_email('', $selected_quote_id);
}

// Delete email campaign on email deletion
add_action( 'admin_init', 'leader_mc_email_init' );
function leader_mc_email_init() {
    if ( current_user_can( 'delete_posts' ) || current_user_can( 'delete_published_email_posts' ) )
        add_action( 'wp_trash_post', 'delete_mc_campaign', 10 );

	//$screen = get_current_screen();
	if ( current_user_can('edit_posts') || current_user_can('edit_email_posts') ) {
		add_action( 'save_post', 'update_mc_campaign', 10 ); // post_updated
	}

	if(isset($_GET['post']) && isset($_GET['quote_email_id']) && ($_GET['action'] == 'edit')){
		$up_quote_id = intval($_GET['post']);
		$set_email_id = intval($_GET['quote_email_id']);
		update_post_meta($up_quote_id, '_rmt_email_id', $set_email_id);
	}

}

function delete_mc_campaign( $post_id ) {
	if('emails' == get_post_type( $post_id )):
		$mc_api_key = get_option('mc_api_key');
		$mcAPI3 = new MC4WP_API_v3($mc_api_key);
		$quote_id = get_post_meta($post_id, '_rmt_quote_id', true);
		$campaign_id = get_post_meta($quote_id, '_rmt_campaign_id', true);
		if($campaign_id) {
			if( check_mc_campaign_exist( $campaign_id ) ) {
				try {
					$mcAPI3->delete_campaign( $campaign_id );
				}catch( MC4WP_API_Resource_Not_Found_Exception $e ) {
					 echo "Unable to Delete Campaign!";
				}catch( MC4WP_API_Exception $e ) {
					 // other errors.
					 echo "Unable to Delete Campaign!";
				}
			}
		}
	endif;
}

function update_mc_campaign( $post_id ) {
	//if( ('quote' == get_post_type( $post_id )) && (get_post_meta($post_id, '_rmt_quote_email', true) == 'yes') && ($_POST['post_type'] == 'quote') ) {
	if( ('quote' == get_post_type( $post_id )) && ($_POST['post_type'] == 'quote') ) {
		// do nothing, just update the post
	//} else {
		if(('quote' == get_post_type( $post_id )) && ($_POST['post_type'] == 'quote')):
			$campaign_id = get_post_meta($post_id, '_rmt_campaign_id', true);
			if($campaign_id) {
				if( check_mc_campaign_exist( $campaign_id ) ) {
					update_mc_campaign_content( $post_id, $campaign_id );
				}
			}
		endif;

		if( ('emails' == get_post_type( $post_id )) && ($_POST['post_type'] == 'emails')):
			$quote_id = get_post_meta($post_id, '_rmt_quote_id', true);
			$campaign_id = get_post_meta($quote_id, '_rmt_campaign_id', true);
			if($campaign_id) {
				if( check_mc_campaign_exist( $campaign_id ) ) {
					update_mc_campaign_content( $quote_id, $campaign_id );
				} else {
					// create a new campaign
					create_mc_campaign( $quote_id );
				}
			}
		endif;
	}
}

function update_mc_campaign_content( $post_id, $campaign_id ) { //$post_id = Quote ID
	$mc_api_key = get_option('mc_api_key');
	$mcAPI3 = new MC4WP_API_v3($mc_api_key);

	$mc_from_email = get_option('mc_from_email');
	$mc_from_name = get_option('mc_from_name');
	$mc_reply_to_name = get_option('mc_reply_to_name');
	$mc_contact_list_id = get_option('mc_contact_list_id');

	$current_email_id = get_post_meta($post_id, '_rmt_email_id', true);
	$email_message_template = get_option('mc_email_template');

	$current_quote_title = get_the_title( $post_id );
	$current_quote_title = html_entity_decode(htmlspecialchars_decode($current_quote_title));

	$search = array();
	$replace = array();

	$search[] = '%%logo%%';
	$replace[] = '<a style="border: none;" href="'.home_url().'" class="buttonlink"><img width="400" height="160" border="0" src="'.EMAIL_FOLDER_URL.'images/leadershipology-logo.jpg"></a>';

	if(get_option('quote_photo_url') != ''){
		$email_author_photo = '<img src="'.get_option('quote_photo_url').'" alt="" />';
	}else{
		$email_author_photo = '<img src="'.EMAIL_FOLDER_URL.'images/200x160_PK.jpg" alt="" />';
	}

	$search[] = '%%author_photo%%';
	$replace[] = $email_author_photo;

	$search[] = '%%quote_title%%';
	$replace[] = get_the_title( $post_id );

	$search[] = '%%social_share%%';
	$replace[] = '<a href="http://www.facebook.com/sharer.php?u=' . urlencode( get_permalink( $post_id ) ) . '&amp;t='. rawurlencode( get_the_title( $post_id ) ).'" target="_blank" style="margin-right: 5px; text-decoration: none;">Share on Facebook</a> | <a href="https://twitter.com/intent/tweet?text='. rawurlencode( get_the_title( $post_id ) ).'&amp;url=' . urlencode( get_permalink( $post_id ) ) . '" target="_blank" style="margin-left: 5px; text-decoration: none;">Share on Twitter</a>';

	$search[] = '%%QUOTETEXT%%';
	$content_quote = get_post($post_id);
	$content_quote_text = $content_quote->post_content;
	$content_quote_text = apply_filters('the_content', $content_quote_text);
	//$content_quote_text = str_replace(array('<p>', '</p>'), '', $content_quote_text);
	$replace[] = $content_quote_text;
	//$replace[] = htmlspecialchars($content_quote_text, ENT_QUOTES, 'UTF-8',false);

	$search[] = '%%QUOTEAUTHOR%%';
	$quote_author = rm_get_single_tax_term($post_id, 'quoteauthor');
	update_post_meta($current_email_id, '_rmt_quote_author', $quote_author);
	$update_quote_author = get_post_meta($current_email_id, '_rmt_quote_author', true);
	if($update_quote_author == ''){
			$update_quote_author = 'Unknown';
	}
	$replace[] = '&ndash; '. $update_quote_author;

	$search[] = '%%THOUGHTBEHIENDTHEQUOTE%%';
	if( get_post_meta($post_id, '_cmb_thought_behind_quote', true) ) {

	$thouth_behind_the_quote = get_post_meta($post_id, '_cmb_thought_behind_quote', true);
	$thouth_behind_the_quote = apply_filters('the_content', $thouth_behind_the_quote);
	$thouth_behind_the_quote = str_replace(']]>', ']]&gt;', $thouth_behind_the_quote);

	$replace[] = '<tr>
					<td valign="top" style="padding-bottom: 14px;">
					<p style="text-align:left; margin-bottom:0; padding-bottom:0;"><span style="font-family:Helvetica, Arial, sans-serif; font-size:14px; line-height:20px; font-weight: lighter;"><strong>Thought Behind the Quote:</strong></span></p>

					<span style="font-family:Helvetica, Arial, sans-serif; font-size:14px; line-height:20px;"><span style="font-family:Helvetica, Arial, sans-serif; font-size:14px; line-height:20px;"><span style="font-family:Helvetica, Arial, sans-serif; font-size:14px; line-height:20px;">'.$thouth_behind_the_quote.'</span></span></span>
					</td>
				</tr>';
	} else {
		//$author_tbq = update_post_meta($post_id, '_cmb_email_thought_behind_quote', $_POST['_cmb_email_thought_behind_quote']);
		//$author_tbq = mysql_real_escape_string( $_POST['_cmb_email_thought_behind_quote'] );
		$author_tbq = $_POST['_cmb_thought_behind_quote'];
		if( $author_tbq != '' && !empty($author_tbq) ) {
			$replace[] = '<tr>
						<td valign="top" style="padding-bottom: 14px;">
						<p style="text-align:left; margin-bottom:0; padding-bottom:0;"><span style="font-family:Helvetica, Arial, sans-serif; font-size:14px; line-height:20px; font-weight: lighter;"><strong>Thought Behind the Quote:</strong></span></p>

						<span style="font-family:Helvetica, Arial, sans-serif; font-size:14px; line-height:20px;"><span style="font-family:Helvetica, Arial, sans-serif; font-size:14px; line-height:20px;"><span style="font-family:Helvetica, Arial, sans-serif; font-size:14px; line-height:20px;">'.$author_tbq.'</span></span></span>
						</td>
					</tr>';
		} else {
			$replace[] = '';
		}
	}

	$email_template = str_replace($search, $replace, $email_message_template);
	$email_template = stripslashes($email_template);

	//$mc = new MCAPI($mc_api_key);

	//$field = array("name" => "options");
	$camp_contents = array('html' => $email_template);

	//$mc->campaignUnschedule($campaign_id);
	$camp_options = array(
			'type' => 'regular',
			'recipients' => array('list_id' => $mc_contact_list_id),
			'settings' => array(
				'subject_line' => $current_quote_title,
				'title' => $current_quote_title,
				'from_name' => $mc_from_name,
				'reply_to' => $mc_from_email,
				'to_name' => $mc_reply_to_name
			),
	);

	//update campaign settings
	try {
	  $mcAPI3->update_campaign( $campaign_id, $camp_options );
	}catch( MC4WP_API_Resource_Not_Found_Exception $e ) {
	   echo "Unable to Update Campaign Settings!";
	}catch( MC4WP_API_Exception $e ) {
	   // other errors.
	   echo "Unable to Update Campaign Settings!";
	}

	//update campaign content
	try {
	  $mcAPI3->update_campaign_content( $campaign_id, $camp_contents );
	}catch( MC4WP_API_Resource_Not_Found_Exception $e ) {
	   echo "Unable to Update Campaign Content!";
	}catch( MC4WP_API_Exception $e ) {
	   // other errors.
	   echo "Unable to Update Campaign Content!";
	}



	//schedule campaign
	try {
		date_default_timezone_set("America/Detroit");
		$email_schedule_date = get_the_date('Y-m-d 07:00', $current_email_id);
		$schedule_data = array(
			'schedule_time' => $email_schedule_date,
		);
		$mcAPI3->campaign_action( $campaign_id, 'schedule', $schedule_data );
	}catch( MC4WP_API_Resource_Not_Found_Exception $e ) {
	   echo "Unable to Update Campaign Sechedule!";
	}catch( MC4WP_API_Exception $e ) {
	   // other errors.
	   echo "Unable to Update Campaign Sechedule!";
	}






/***********
	$update_camp = $mc->campaignUpdate($campaign_id, "content", $camp_contents);
	//$update_camp = $mc->campaignUpdate($campaign_id, $field, $camp_values);
	$update_camp = $mc->campaignUpdate($campaign_id, "title", $current_quote_title);
	$update_camp = $mc->campaignUpdate($campaign_id, "subject", $current_quote_title);

	if ($mc->errorCode){
		echo "Unable to Update Campaign!";
		echo "<br />Code=".$mc->errorCode;
		echo "<br />Msg=".$mc->errorMessage."\n";
	} else {
		//echo "Updated Successfully! \n";
		date_default_timezone_set("America/Detroit");
		$email_schedule_date = get_the_date('Y-m-d 07:01:00', $current_email_id);
		$mc->campaignSchedule($campaign_id, $email_schedule_date);
	}
***********/


}

function check_mc_campaign_exist($campaign_id) {
	$mc_api_key = get_option('mc_api_key');
	// $mc = new MCAPI($mc_api_key);
	$mcAPI3 = new MC4WP_API_v3($mc_api_key);

	$campaigns_options = array('fields' => array('id', 'create_time'));

	try {
	  $campaign_details = $mcAPI3->get_campaign($campaign_id, $campaigns_options);
	  //echo $campaign_details->id;
		return true;
	}catch( MC4WP_API_Resource_Not_Found_Exception $e ) {
	   return FALSE;
	}catch( MC4WP_API_Exception $e ) {
	   return FALSE;
	}

}

function check_mc_campaign_status($campaign_id) {
	$mc_api_key = get_option('mc_api_key');
	$mcAPI3 = new MC4WP_API_v3($mc_api_key);

	$campaigns_options = array('fields' => array('id', 'create_time', 'status'));

	try {
	  $campaign_details = $mcAPI3->get_campaign($campaign_id, $campaigns_options);
		if(isset($campaign_details->status)){
				return $campaign_details->status;
		}
	}catch( MC4WP_API_Resource_Not_Found_Exception $e ) {
	   return 'Error!!!';
	}catch( MC4WP_API_Exception $e ) {
	   return 'Error!!!';
	}

}

//approve email post
function approve_email_post() {
	if( isset($_GET['approve_post_id']) && $_GET['approve_post_id'] != '' ) {
		$secret_key = $_GET['secret_key'];
		if(!empty($secret_key)) {
			$email_secret_key = get_post_meta($_GET['approve_post_id'], '_rmt_email_secret_key', true);
			if( $secret_key == $email_secret_key ) {
				// Update post_status of the post bearing id $_GET['approve_post_id']
				$args = array(
				  'ID' => $_GET['approve_post_id'],
				  'post_status' => 'future'
				);
				wp_update_post( $args );

				// create a new campaign
				$email_id = $_GET['approve_post_id'];
				$quote_id = get_post_meta($email_id, '_rmt_quote_id', true);
				//Reset MC campaign
				update_post_meta($quote_id, '_rmt_campaign_id', '');
				//Create MC Campaign
				create_mc_campaign( $quote_id );
		?>
				<script type="text/javascript">
					window.location = "<?php echo home_url( '/' ) . '?app_status=success'; ?>";
				</script>
		<?php
  			} else {
		?>
				<script type="text/javascript">
					window.location = "<?php echo home_url( '/' ) . '?app_status=failure'; ?>";
				</script>
		<?php
			}
		}
	}

	//Reject email processing
	if( isset($_GET['rejects_email_post_id']) && $_GET['rejects_email_post_id'] != '' ) {
		$secret_key = $_GET['secret_key'];
		if(!empty($secret_key)) {
			$email_secret_key = get_post_meta($_GET['rejects_email_post_id'], '_rmt_email_secret_key', true);
			if( $secret_key == $email_secret_key ) {
				// create a new campaign
				$rejects_email_id = $_GET['rejects_email_post_id'];

				$email_schedule_date = get_the_date( 'Y-m-d', $rejects_email_id );
				$selected_quote_id = qmgt_select_no_duplicate_quote_for_email();

				//delete email and it's quote
				wp_delete_post( $rejects_email_id );
				$deleted_quote_id = get_post_meta($rejects_email_id, '_rmt_quote_id', true);
				wp_delete_post( $deleted_quote_id );

				// Generate new email campaign for this date automatically
				generate_daily_email($email_schedule_date, $selected_quote_id);

				?>
				<script type="text/javascript">
					window.location = "<?php echo home_url( '/' ) . '?app_status=success_rejects'; ?>";
				</script>
				<?php
		  	} else {
				?>
				<script type="text/javascript">
					window.location = "<?php echo home_url( '/' ) . '?app_status=failure'; ?>";
				</script>
		<?php
			}
		}
	}

}
add_action('init', 'approve_email_post');

/*
* do something on post status transition, from future to publish
*/
function qmgt_on_draft_to_schedule_post( $post ) {
	if( isset($_GET['approve_post_id']) && $_GET['approve_post_id'] != '' ) {
		//Nothing here for this case
		return;
	}
    // A function to perform when a scheduled post is published
	if( 'emails' == get_post_type( $post->ID ) ) {
		//Create a campaign to mailchimp account
//		$args = array(
//		  'ID' => $_GET['approve_post_id'],
//		  'post_status' => 'future'
//		);
//		wp_update_post( $args );

		// create a new campaign
		$email_id = $post->ID;
		$quote_id = get_post_meta($email_id, '_rmt_quote_id', true);
		create_mc_campaign( $quote_id );

	}
}
add_action( 'draft_to_future',  'qmgt_on_draft_to_schedule_post', 10, 1 );

/*
 * Trigger when email post published.
 * update quote last ran date and email sent status.
 */
function qmgt_emails_published_action( $ID, $post ){
	$email_id = $ID;
	$email_published_date = get_the_date( 'Y-m-d', $email_id );
	$email_published_date = date("Y-m-d 07:01:00", strtotime($email_published_date));

	//Get quote ID for this email
	$quote_id = get_post_meta($email_id, '_rmt_quote_id', true);

	//update quote email sending status
	update_post_meta($quote_id, '_rmt_quote_email', 'yes');

	//update quote last ran date
	update_post_meta($quote_id, '_cmb_last_ran_date', $email_published_date);


}
add_action( 'publish_emails', 'qmgt_emails_published_action', 10, 2 );


//admin notification
function rm_admin_notification($email_post_id){
	$email_subject = get_option('notification_email_subject');
	$email_message = get_option('notification_email_message');

	$quote_id = get_post_meta($email_post_id, '_rmt_quote_id', true);
	$quote_title = get_the_title( $quote_id );

	$subject_search = array();
	$subject_replace = array();

	$subject_search[] = '%%email-subject%%';
	$subject_replace[] = get_the_title( $quote_id );
	$email_subject = str_replace($subject_search, $subject_replace, $email_subject);

	$search = array();
	$replace = array();

	$search[] = '%%quote-email-subject%%';
	$replace[] = get_the_title( $quote_id );

	$search[] = '%%sendout-date%%';
	$replace[] = get_the_time('l, F j, Y', $email_post_id);

	$search[] = '%%last-run-date%%';
	$replace[] = get_post_meta($quote_id, '_cmb_last_ran_date', true);

	$search[] = '%%approval-link%%';
	$secret_key = get_post_meta($email_post_id, '_rmt_email_secret_key', true);
	$replace[] = home_url().'?approve_post_id='.$email_post_id.'&secret_key='.$secret_key;

	$search[] = '%%rejects-link%%';
	$secret_key = get_post_meta($email_post_id, '_rmt_email_secret_key', true);
	$replace[] = home_url().'?rejects_email_post_id='.$email_post_id.'&secret_key='.$secret_key;


	$search[] = '%%edit-link%%';
	$replace[] = admin_url( 'post.php?post='.$email_post_id.'&action=edit');

	$search[] = '%%quote-content%%';
	$email_quote_content = apply_filters('the_content', get_post_field('post_content', $quote_id));


	$quote_author = rm_get_single_tax_term($quote_id, 'quoteauthor');
	update_post_meta($email_post_id, '_rmt_quote_author', $quote_author);
	$rmt_quote_author = get_post_meta($email_post_id, '_rmt_quote_author', true);
	if(get_post_meta($email_post_id, '_rmt_quote_author', true) == ''){
		$rmt_quote_author = 'Unknown';
	}
	$email_quote_author = '&ndash; '. $rmt_quote_author;

	if($email_quote_author){
		$email_quote_content .= '<p style="text-align:right; margin-bottom:0; padding-bottom:0;"><span style="font-family:Helvetica, Arial, sans-serif; font-size:12px; line-height:20px; font-weight: lighter;"><strong>'.$email_quote_author.'</strong></span></p>';
	}

	if( get_post_meta($quote_id, '_cmb_thought_behind_quote', true) ) {
	$email_quote_content .= '<p style="text-align:left; margin-bottom:0; padding-bottom:0;"><span style="font-family:Helvetica, Arial, sans-serif; font-size:14px; line-height:20px; font-weight: lighter;"><strong>Thought Behind the Quote:</strong></span></p>

					<p><span style="font-family:Helvetica, Arial, sans-serif; font-size:14px; line-height:20px;"><span style="font-family:Helvetica, Arial, sans-serif; font-size:14px; line-height:20px;"><span style="font-family:Helvetica, Arial, sans-serif; font-size:14px; line-height:20px;">'.get_post_meta($quote_id, '_cmb_thought_behind_quote', true).'</span></span></span></p>';
	}


	$replace[] = $email_quote_content;


	$get_message = str_replace($search, $replace, $email_message);
	$get_message = 	stripslashes($get_message);
	$get_message = str_replace(array("\n", "\r"), '', $get_message);
	//$get_message = nl2br($get_message);

	$admin_emails = get_option('notified_emails');
	$admin_email_arr = explode(',', $admin_emails);
	foreach($admin_email_arr as $user_email){
		rm_notification(trim($user_email), $email_subject, $get_message);
	}
}






//Send email with custom email template
function rm_notification($user_email, $email_subject, $message){
	//process email template
	$email_message_template = get_option('email_template');
	$search = array();
	$replace = array();
	$search[] = '%%content%%';
	$replace[] = $message;
	$search[] = '%%date%%';
	$replace[] = date("F j, Y, g:i a");
	$search[] = '%%admin_email%%';
	$replace[] = get_option('admin_email');
	$message_send = str_replace($search, $replace, $email_message_template);
	$message_send = stripslashes($message_send);
	$headers  = 'MIME-Version: 1.0' . "\r\n";
	$headers .= 'Content-type: text/html; charset=UTF-8' . "\r\n";
	add_filter('wp_mail_content_type',create_function('', 'return "text/html"; '));
	@wp_mail( $user_email, $email_subject, $message_send, $headers );
}


function send_mc_email_template($email_address, $email_post_id) {
	$current_quote_id = get_post_meta($email_post_id, '_rmt_quote_id', true);
	//process email template
	$email_message_template = get_option('mc_email_template');

	$current_quote_title = get_the_title( $current_quote_id );
	/*$search_title = array('&#8211;', '&#46;', '&#8230;');
	$replace_title = array('-', '.', '...');
	$current_quote_title = str_replace($search_title, $replace_title, $current_quote_title);*/
	$current_quote_title = htmlspecialchars_decode($current_quote_title);
	$email_subject = 'Leadershipology Quote: '. html_entity_decode($current_quote_title);

	$search = array();
	$replace = array();

	$search[] = '%%logo%%';
	$replace[] = '<a style="border: none;" href="'.home_url().'" class="buttonlink"><img width="400" height="160" border="0" src="'.EMAIL_FOLDER_URL.'images/leadershipology-logo.jpg"></a>';

	if(get_option('quote_photo_url') != ''){
		$email_author_photo = '<img src="'.get_option('quote_photo_url').'" alt="" />';
	}else{
		$email_author_photo = '<img src="'.EMAIL_FOLDER_URL.'images/200x160_PK.jpg" alt="" />';
	}

	$search[] = '%%author_photo%%';
	$replace[] = $email_author_photo;

	$search[] = '%%quote_title%%';
	$replace[] = get_the_title( $current_quote_id );

	$search[] = '%%social_share%%';
	$replace[] = '<a href="https://www.facebook.com/sharer.php?u=' . urlencode( get_permalink( $current_quote_id ) ) . '&amp;t='. rawurlencode( get_the_title( $current_quote_id ) ).'" target="_blank" style="margin-right: 5px; text-decoration: none;">Share on Facebook</a> | <a href="https://twitter.com/intent/tweet?text='. rawurlencode( get_the_title( $current_quote_id ) ).'&amp;url=' . urlencode( get_permalink( $current_quote_id ) ) . '" target="_blank" style="margin-left: 5px; text-decoration: none;">Share on Twitter</a>';

	$search[] = '%%QUOTETEXT%%';
	$content_quote = get_post($current_quote_id);
	$content_quote_text = $content_quote->post_content;
	$content_quote_text = apply_filters('the_content', $content_quote_text);
	//$content_quote_text = str_replace(array('<p>', '</p>'), '', $content_quote_text);
	$replace[] = $content_quote_text;
	//$replace[] = htmlspecialchars($content_quote_text, ENT_QUOTES, 'UTF-8',false);

	$search[] = '%%QUOTEAUTHOR%%';
	$replace[] = '&ndash; '. rm_get_single_term($current_quote_id, 'quoteauthor');

	$search[] = '%%THOUGHTBEHIENDTHEQUOTE%%';
	if( get_post_meta($current_quote_id, '_cmb_thought_behind_quote', true) ) {
	$replace[] = '<tr>
					<td valign="top" style="padding-bottom: 14px;">
					<p style="text-align:left; margin-bottom:0; padding-bottom:0;"><span style="font-family:Helvetica, Arial, sans-serif; font-size:14px; line-height:20px; font-weight: lighter;"><strong>Thought Behind the Quote:</strong></span></p>

					<p><span style="font-family:Helvetica, Arial, sans-serif; font-size:14px; line-height:20px;"><span style="font-family:Helvetica, Arial, sans-serif; font-size:14px; line-height:20px;"><span style="font-family:Helvetica, Arial, sans-serif; font-size:14px; line-height:20px;">'.get_post_meta($current_quote_id, '_cmb_thought_behind_quote', true).'</span></span></span></p>
					</td>
				</tr>';
	} else
		$replace[] = '';

	$message_send = str_replace($search, $replace, $email_message_template);
	$message_send = stripslashes($message_send);
	$headers  = 'MIME-Version: 1.0' . "\r\n";
	$headers .= 'Content-type: text/html; charset=UTF-8' . "\r\n";
	add_filter('wp_mail_content_type',create_function('', 'return "text/html"; '));

	@wp_mail( $email_address, $email_subject, $message_send, $headers );
}


function rm_get_single_term($post_id, $taxonomy) {
	global $wpdb;
    $terms = wp_get_object_terms($post_id, $taxonomy);
    if(!is_wp_error($terms)) {
        return $terms[0]->name;
    }
}


//developer, tech_team, designer, reviewer, client, manager
//Return array of user email.
function rm_get_email_list($role = 'customer'){
	$blogusers = get_users( 'orderby=nicename&role='.$role );
	$result = array();
	// Array of WP_User objects.
	foreach ( $blogusers as $user ) {
		$result[] = esc_html( $user->user_email );
	}

	return $result;
}



function display_approval_notice_messsage( $content ) {
	if( is_front_page() ) {
		if( isset($_GET['app_status']) && $_GET['app_status'] == 'success_rejects' ) {
			$output = '<div class="message-box success">
				<div class="contents">
					<p style="font-size:20px; text-align:center; padding:10px 0; color:green;">Removed and auto generated done.</p>
				</div>
			</div>';
			$content = $output . $content;
		}elseif( isset($_GET['app_status']) && $_GET['app_status'] == 'success' ) {
			$output = '<div class="message-box success">
				<div class="contents">
					<p style="font-size:20px; text-align:center; padding:10px 0; color:green;">Email is scheduled</p>
				</div>
			</div>';
			$content = $output . $content;
		}elseif( isset($_GET['app_status']) && $_GET['app_status'] == 'failure' ) {
			$output = '<div class="message-box error">
				<div class="contents">
					<p style="font-size:20px; text-align:center; padding:10px 0; color:red;">Email scheduling failed</p>
				</div>
			</div>';
			$content = $output . $content;
		}
	}
	return $content;
}
add_filter( 'the_content', 'display_approval_notice_messsage' );
