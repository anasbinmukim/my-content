<?php
add_action('admin_menu' , 'auto_quote_twitter_settings_page');
function auto_quote_twitter_settings_page(){
	add_submenu_page( 'edit.php?post_type=emails', 'Twitter Settings', 'Twitter Settings', 'manage_options', 'aq_twitter_settings', 'aq_twitter_settings_callback');
}
function aq_twitter_settings_callback(){
	if(isset($_POST['aq_settings_submit'])){
		
		update_option( 'aq_consumer_key', $_POST['aq_consumer_key'] );
		update_option( 'aq_consumer_secret', $_POST['aq_consumer_secret'] );
		update_option( 'aq_access_token', $_POST['aq_access_token'] );
		update_option( 'aq_access_token_secret', $_POST['aq_access_token_secret'] );
		update_option( 'enable_tweets', $_POST['enable_tweets'] );	
				
		echo "<div class='updated'><p>Successfully Updated</p></div>";
	}
	
	
//	if(isset($_GET['shorturl'])){
//		echo "Hello<br />";
//		$googl = new Googl('AIzaSyBnD5KvsRe1Tm9l1J8o7xvconpGacM9j14');
//		echo $googl->shorten('http://www.google.ch');
//	}
	
	
?>
<div class="wrap">
	<h2 style="padding-bottom: 25px;"><?php echo __('Twitter Settings'); ?></h2>
	<form name="autoquote_twitter_settings" method="post" action="">
		<table class="form-table" style="margin-top:0;">
			<tr valign="top">
				<th scope="row" style="padding-top:0;"><?php echo __('Enable Tweets'); ?></th>
				<td style="padding-top:0;">					
					<input type="checkbox" name="enable_tweets" value="yes" <?php if(get_option('enable_tweets') == 'yes'){ ?> checked="checked" <?php } ?> />
				</td>
			</tr>
			
			<tr valign="top">
				<th scope="row" style="padding-top:0;"><?php echo __('Consumer Key (API Key)'); ?></th>
				<td style="padding-top:0;">
					<input type="text" name="aq_consumer_key" value="<?php echo get_option('aq_consumer_key'); ?>" />
				</td>
			</tr>
			<tr valign="top">
				<th scope="row" style="padding-top:0;"><?php echo __('Consumer Secret (API Secret)'); ?></th>
				<td style="padding-top:0;">
					<input type="text" name="aq_consumer_secret" value="<?php echo get_option('aq_consumer_secret'); ?>" />
				</td>
			</tr>
			<tr valign="top">
				<th scope="row" style="padding-top:0;"><?php echo __('Access Token'); ?></th>
				<td style="padding-top:0;">
					<input type="text" name="aq_access_token" value="<?php echo get_option('aq_access_token'); ?>" />
				</td>
			</tr>
			<tr valign="top">
				<th scope="row" style="padding-top:0;"><?php echo __('Access Token Secret'); ?></th>
				<td style="padding-top:0;">
					<input type="text" name="aq_access_token_secret" value="<?php echo get_option('aq_access_token_secret'); ?>" />
				</td>
			</tr>

			
		</table>
		
		<p class="submit">
			<input type="submit" name="aq_settings_submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
		</p>
	</form>
</div>

<?php

}


if (!function_exists('auto_twite_truncate_string')) {
    /* Original PHP code by Chirp Internet: www.chirp.com.au
    http://www.the-art-of-web.com/php/truncate/ */
    function auto_twite_truncate_string($string, $limit, $strip_tags = true, $strip_shortcodes = true, $break = " ", $pad = "...") {
        if ($strip_shortcodes)
            $string = strip_shortcodes($string);
        if ($strip_tags)
            $string = strip_tags($string, '<p>'); // retain the p tag for formatting
        // return with no change if string is shorter than $limit
        if (strlen($string) <= $limit)
            return $string;
        elseif ($limit === 0 || $limit == '0')
            return '';
        // is $break present between $limit and the end of the string?
        if (false !== ($breakpoint = strpos($string, $break, $limit))) {
            if ($breakpoint < strlen($string) - 1) {
                $string = substr($string, 0, $breakpoint) . $pad;
            }
        }
        return $string;
    }
}

function post_published_post_to_twitter( $ID, $post ) {
	$consumerKey = get_option('aq_consumer_key');
	$consumerSecret = get_option('aq_consumer_secret');
	$accessToken = get_option('aq_access_token');
	$accessTokenSecret = get_option('aq_access_token_secret');	
	//https://github.com/dg/twitter-php
	$twitter = new Twitter($consumerKey, $consumerSecret, $accessToken, $accessTokenSecret);
	if (($twitter->authenticate()) && (get_option('enable_tweets') == 'yes')) {
		$email_id = $ID;
		if( get_post_meta($email_id, '_rmt_quote_id', true)) {
			$generated_quote_id = get_post_meta($email_id, '_rmt_quote_id', true);
		}
		
		$quote_link_url = get_permalink($generated_quote_id);
		$content_quote = get_post($generated_quote_id);
		$content_quote_text = $content_quote->post_content;
		//$content_quote_text = apply_filters('the_content', $content_quote_text);		
		$content_quote_text = strip_tags($content_quote_text);
		//$content_quote_text = htmlspecialchars($content_quote_text, ENT_QUOTES, 'UTF-8', false);
		//$content_quote_text = "Don't sabotage good relationships because of past hurtful relationships. The things that hurt you the worst can help you the";

		if(strlen($content_quote_text) > 100){
			$content_quote_text = auto_twite_truncate_string($content_quote_text, 100);
			$googl = new Googl('AIzaSyBnD5KvsRe1Tm9l1J8o7xvconpGacM9j14');
			$short_url = $googl->shorten($quote_link_url);
			$content_quote_text .= '  '.$short_url;	
		}
		
		//send quote to twitter.
		try {
			$twitter->send($content_quote_text);
		} catch (TwitterException $e) {
			echo "Error: ", $e->getMessage();
		}
		
	}
}

add_action( 'publish_emails', 'post_published_post_to_twitter', 10, 2 );