<?php


define( 'SIG_VERSION', '1.0.0' );
define( 'SIG_FILE', __FILE__ );
define( 'SIG_ROOT', dirname( __FILE__ ) );
define( 'SIG_ROOT_URI', plugins_url( '', __FILE__ ) );
define( 'SIG_ASSET_URI', SIG_ROOT_URI . '/assets' );

class Signature {

  private static $_instance;

   function __construct() {

    add_action( 'wp_enqueue_scripts', array($this, 'enqueue_signature_scripts') );

    add_action( 'wp_head', array( $this, 'add_custom_js' ) );

    //add_shortcode('custom-signature', array($this, 'custom_signature') );

    register_activation_hook(__FILE__, array($this, 'sig_activation') );

    register_deactivation_hook(__FILE__, array($this, 'sig_deactivation') );

    add_action('admin_notices', array($this, 'sig_shortcode_notice') );

   }

   function sig_activation() {
   	$notices = get_option('sig_shortcode_publish', array());
   	$notices[] = "Signature Plugins recommends the following Shortcode <strong>[custom-signature]</strong>";
   	update_option('sig_shortcode_publish', $notices);
   }

   function sig_shortcode_notice() {
   	if($notices = get_option('sig_shortcode_publish')) {
   		foreach($notices as $notice) {
   			echo "<div class='updated settings-error notice is-dismissible'><p>$notice</p></div>";
   		}
   		delete_option('sig_shortcode_publish');
   	}
   }

   function sig_deactivation() {
   	delete_option('sig_shortcode_publish');
   }

   function enqueue_signature_scripts() {
     
   // enqueue js  
    wp_enqueue_script( 'wpuf-subscriptions', SIG_ASSET_URI . '/js/jquery.signaturepad.min.js', array('jquery'), false, true );
    wp_enqueue_script( 'sig-json', SIG_ASSET_URI . '/js/json2.min.js', array('jquery'), false, true );

    // enqueue css
   		wp_enqueue_style( 'sig-css', SIG_ASSET_URI . '/css/jquery.signaturepad.css' );
   }

   public static function init() {

   	if( !self::$_instance ) {
   		self::$_instance = new Signature();
   	}
   	return self::$_instance;
   }

   function add_custom_js() {
   	?>
			  <script>
					(function($) {
					  var sig;
					  var sig2;
					  var sig3;
					  var sig4;
					  $(document).ready(function() {
					 
					    sig = $('.sigPad').signaturePad({drawOnly:true, validateFields: false });
						sig2 = $('.sign_fields').signaturePad({drawOnly:true, validateFields: false });
						sig3 = $('.sign_fields_pw').signaturePad({drawOnly:true, validateFields: false });
						sig4 = $('.sign_fields_pw_2').signaturePad({drawOnly:true, validateFields: false });
                
				        $('.sigPad').submit(function(evt) {
				           $('.imgOutput').val( sig.getSignatureImage() );
				        });  
				        $('.sigPad2').submit(function(evt) {
						   $('.imgOutput2').val( sig2.getSignatureImage() );						   
						});
				
						$('.sigPad3').submit(function(evt) {
							$('.imgOutput3').val( sig3.getSignatureImage() );
							$('.imgOutput4').val( sig4.getSignatureImage() );						   						   
						});
						
						$('.sigPadClient').submit(function(evt) {
						   	$('.imgOutput3').val( sig3.getSignatureImage() );					   
						});
						
						$('.sigPadBM').submit(function(evt) {
							$('.imgOutput4').val( sig4.getSignatureImage() );				   
						});								
                


					  });

					})(jQuery);
			 </script>
	<?php
   }

  // text to image convert
   function text_to_PNG_file($text, $font, $font_color, $background_color, $font_size, $filename) { 

    if (!function_exists('get_dip')) {
      function get_dip($font,$size) 
      { 
          $test_chars = 'abcdefghijklmnopqrstuvwxyz' . 
                        'ABCDEFGHIJKLMNOPQRSTUVWXYZ' . 
                        '1234567890' . 
                        '!@#$%^&*()\'"\\/;.,`~<>[]{}-+_-=' ; 
          $box = @ImageTTFBBox($size,0,$font,$test_chars) ; 
          return $box[3] ; 
      } 
    }
     
    if (!function_exists('hex_to_rgb')) {
      function hex_to_rgb($hex) 
        { 
          // remove '#' 
          if(substr($hex,0,1) == '#') 
              $hex = substr($hex,1) ; 
       
          // expand short form ('fff') color 
          if(strlen($hex) == 3) 
          { 
              $hex = substr($hex,0,1) . substr($hex,0,1) . 
                     substr($hex,1,1) . substr($hex,1,1) . 
                     substr($hex,2,1) . substr($hex,2,1) ; 
          } 
       
          if(strlen($hex) != 6) return FALSE; 
           
          // convert 
          $rgb['red'] = hexdec(substr($hex,0,2)) ; 
          $rgb['green'] = hexdec(substr($hex,2,2)) ; 
          $rgb['blue'] = hexdec(substr($hex,4,2)) ; 
       
          return $rgb ; 
      } 
    }

    $font_directory = ''; 
    $font_file  = $font_directory . $font . '.ttf' ; 
    $transparent_background  = true ; 
    $mime_type = 'image/png' ; 
    $send_buffer_size = 4096 ; 
     
    // check for GD support 
    if(!function_exists('ImageCreate')) return FALSE; 
     
    // clean up text 
    if(empty($text)) return FALSE; 
     
     
    // check font availability 
    if(!$font_found = is_readable($font_file)) return FALSE;  
     
    // create image 
    $background_rgb = hex_to_rgb($background_color) ; 
    $font_rgb = hex_to_rgb($font_color); 
    $dip = get_dip($font_file,$font_size) ; 
    $box = @ImageTTFBBox($font_size,0,$font_file,$text) ; 
    $image = @ImageCreate(abs($box[2]-$box[0]),abs($box[5]-$dip)) ; 
    if(!$image || !$box) return FALSE; 
     
     
    // allocate colors and draw text 
    $background_color = @ImageColorAllocate($image,$background_rgb['red'], 
        $background_rgb['green'],$background_rgb['blue']) ; 
    $font_color = ImageColorAllocate($image,$font_rgb['red'], 
        $font_rgb['green'],$font_rgb['blue']) ;    
    ImageTTFText($image,$font_size,0,-$box[0],abs($box[5]-$box[3])-$box[1], 
        $font_color,$font_file,$text) ; 
     
    // set transparency 
    if($transparent_background) 
        ImageColorTransparent($image,$background_color) ; 
     
    ob_start(); 
    ImagePNG($image); 
    if($fp = fopen($filename, "w") and fwrite($fp, ob_get_clean()) and fclose($fp)){ 
        return TRUE; 
    } 
    return FALSE;      
} 



   function custom_signature() {
   	ob_start();

		if(isset($_POST['submit'])) {

		  $dir = "/signatures";

		  $data = $_POST['imgOutput'];

		  if (is_string($data) && strrpos($data, "data:image/png;base64", -strlen($data)) !== FALSE){
		      $data_pieces = explode(",", $data);
		      $encoded_image = $data_pieces[1];
		      $decoded_image = base64_decode($encoded_image);
		      $upload_dir = wp_upload_dir();
		      $signature_dir = $upload_dir['basedir'].$dir;
		      $signature_dir_url = $upload_dir['baseurl'].$dir;
		      if( ! file_exists( $signature_dir ) ){
		      wp_mkdir_p( $signature_dir );
		     }
		     $filename = $key."-".time().".png";
		     $filepath = $signature_dir."/".$filename;

		    file_put_contents( $filepath,$decoded_image);

		    if (file_exists($filepath)){
		      // File created : changing posted data to the URL instead of base64 encoded image data
		      $fileurl = $signature_dir_url."/".$filename;
		      echo "Signature Successfully Uploaded";
		     
		    } else { 
		        error_log("Cannot create signature file in directory ".$filepath);
		    }
		  }
		}
		?>
		<form method="post" action="" class="sigPad">
		  <p class="drawItDesc">Draw your signature</p>
		  <ul class="sigNav">

		    <li class="drawIt"><a href="#draw-it">Draw It</a></li>
		    <li class="clearButton"><a href="#clear">Clear</a></li>
		  </ul>
		  <div class="sig sigWrapper">
		    <canvas class="pad" width="198" height="55"></canvas>

		    <input type="hidden" name="imgOutput" class="imgOutput">
		  </div>
		  <button name="submit" type="submit">Submit</button>

		</form>

		<?php
			$content = ob_get_clean();
			return $content;
   }

}

function sig() {
	return Signature::init();
}

// kickoff the plugin
sig();

?>