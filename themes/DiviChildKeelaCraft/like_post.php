<?php
/******
 * @package WordPress
 * @subpackage RMTheme
 * @since version 5.0
 * @author RM Web Lab
 *****/

/*******************************
 * make sure ajax is working otherwise the like button won't work
*******************************/
function rm_like_add_ajax_url() {
    echo '<script type="text/javascript">var ajaxurl = "' . admin_url('admin-ajax.php') . '";</script>';
		?>
		<script type="text/javascript">
		jQuery(document).ready(function() {
				jQuery(".rm_like").stop().click(function(){
					var rel = jQuery(this).attr("data-rel");
					var data = {
						data: rel,
						action: 'rm_like_callback'
					}
					jQuery.ajax({
						action: "rm_like_callback",
						type: "GET",
						dataType: "json",
						url: ajaxurl,
						data: data,
						success: function(data){
							console.log(data.likes);
							console.log(data.status);
							if(data.status == true){
								jQuery(".rm_like[data-rel="+rel+"]").html("<i class='fa fa-heart'></i> " + data.likes).addClass("liked");
							}else{
								jQuery(".rm_like[data-rel="+rel+"]").html("<i class='fa fa-heart-o'></i> " + data.likes).removeClass("liked");
							}
						}
					});
				});
		});
		</script>
		<?php
}
// Add hook for admin <head></head>
add_action('wp_head', 'rm_like_add_ajax_url');

/*******************************
 * likeCount:
 * Get current like count, this is used to show the amount of likes to the user
*******************************/
function likeCount($id){
   $likes = get_post_meta( $id, '_likers', true );
   if(!empty($likes)){
      return count(explode(', ', $likes));
   }else{
      return '';
   }
}

/*******************************
 * like_callback:
 * add or remove likes from the Wordpress metabox field
*******************************/
add_action('wp_ajax_rm_like_callback', 'rm_like_callback');
add_action('wp_ajax_nopriv_rm_like_callback', 'rm_like_callback');

function rm_like_callback() {
   $id = json_decode($_GET['data']); // Get the ajax call
   $feedback = array("likes" => "");
   // Get metabox values
   $currentvalue = get_post_meta( $id, '_likers', true );
   $likes = intval(get_post_meta( $id, '_likes_count', true ));
   // Convert likers string to an array
   $likesarray = explode(', ', $currentvalue);
   // Check if the likers metabox already has a value to determine if the new entry has to be prefixed with a comma or not
   if(!empty($currentvalue)){
      $newvalue = $currentvalue .', '. $_SERVER['REMOTE_ADDR'];
   }else{
      $newvalue = $_SERVER['REMOTE_ADDR'];
   }
   // Check if the IP address is already present, if not, add it
   if(strpos($currentvalue, $_SERVER['REMOTE_ADDR']) === false){
      $nlikes = $likes + 1;
      if(update_post_meta($id, '_likers', $newvalue, $currentvalue) && update_post_meta($id, '_likes_count', $nlikes, $likes)){
         $feedback = array("likes" => likeCount($id), "status" => true);
      }
   }else{
      $key = array_search($_SERVER['REMOTE_ADDR'], $likesarray);
      unset($likesarray[$key]);
      $nlikes = $likes - 1;
      if(update_post_meta($id, '_likers', implode(", ", $likesarray), $currentvalue) && update_post_meta($id, '_likes_count', $nlikes, $likes)){
         $feedback = array("likes" => likeCount($id), "status" => false);
      }
   }
   echo json_encode($feedback);
   die(); // A kitten gif will be removed from the interwebs if you delete this line

}
