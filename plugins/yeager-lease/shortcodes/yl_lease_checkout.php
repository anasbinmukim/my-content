<?php

	$Path=$_SERVER['REQUEST_URI'];
	 $path;

if($path == '/checkout/' || $path == '/invoice-checkout/' )
{
if(isset($_GET['iid']))
{		
$invoice_id = $_GET['iid'].'<br>'; 
$slug= basename(get_the_permalink($invoice_id));
$siteurl=get_site_url();


header("Location: ".$siteurl."/sprout-invoice/".$slug);
exit;
}
}


function yl_lease_checkout_function($atts, $content = null) {
	ob_start();

	//echo 'lease_id: '.$_GET['lid'];
	//echo '<br>invoice_id: '.get_post_meta($_GET['lid'], '_yl_invoice_id', true);
  if ($_GET['redirect']) {
    echo '<input type="hidden" id="_yl_checkout_redirect" value="'.$_GET['redirect'].'">';
    echo '<input type="hidden" id="_yl_checkout_redirect_lid" value="'.$_GET['lid'].'">';
  }

  if ($_GET['lid']) {
  	?>

  	<div class="yl_timeline_container">
        <div class="yl_timeline_line step_6">
          <span>
          </span>
        </div>

        <div class="yl_top_section">
          <div class="yl_bm_top_line">
            <div>
              <span>BM</span>

              <div class="yl_unit_block yl_one_third active">
                <div class="yl_circle">
                </div>
                <div class="yl_desc">
                  Search
                </div>
              </div>

              <div class="yl_unit_block yl_one_third step_client_info active">
                <div class="yl_circle">
                </div>
                <div class="yl_desc">
                  Client<br>Info
                </div>
              </div>

              <div class="yl_unit_block yl_one_third active">
                <div class="yl_circle">
                </div>
                <div class="yl_desc">
                  Lease<br>Summary
                </div>
              </div>
            </div>
          </div>
          <div class="yl_client_top_line">
            <div>
              <span>Client</span>

              <div class="yl_unit_block yl_one_third active">
                <div class="yl_circle">
                </div>
                <div class="yl_desc">
                  Lease Summary<br>Client
                </div>
              </div>

              <div class="yl_unit_block yl_one_third active">
                <div class="yl_circle">
                </div>
                <div class="yl_desc">
                  Client Review<br>&amp; Sign Lease
                </div>
              </div>

              <div class="yl_unit_block yl_one_third active">
                <div class="yl_circle">
                </div>
                <div class="yl_desc">
                  Client<br>Payment
                </div>
              </div>
            </div>
          </div>
          <div class="yl_bm_top_line yl_bm_last_step">
        <div>
              <span>BM</span>

              <div class="yl_unit_block yl_full_width">
                <div class="yl_circle">
                </div>
                <div class="yl_desc">
                  BM<br>Finalize
                </div>
              </div>
            </div>
          </div>
        </div>

      </div>

  	<?php
    $invoice_id = get_post_meta($_GET['lid'], '_yl_invoice_id', true);
  }
  else if ($_GET['iid']) {
    $invoice_id = $_GET['iid'];
  }

  $convenience_fee_charge_percentage = 2.95;
  ?>
  <div class="row yl_checkout_sc_block">
<?php
$title= get_the_title($invoice_id);
?>

    <div class="col-md-6">
		
      <h3><?php echo $title; ?></h3>
      
      
      <h5>Date: <?php echo date('Y-m-d', (int)get_post_meta($invoice_id, '_due_date', true)); ?></h5>
      <?php echo do_shortcode('[sprout_invoice id="'.$invoice_id.'"]'); ?>
      <?php
	  	echo checkout_payment_data($invoice_id);
	  ?>
    </div>

    <div class="col-md-6 text-center yl_checkout_red_highlight">
      <h5 class="text-center">Please select your payment method</h5>
      <input type="hidden" name="invoice_url" class="invoice_url" value="<?php echo get_permalink($invoice_id); ?>">
      <select class="yl_payment_method_select">
        <option>Choose Payment Method...</option>
        <option value="cc">Credit/Debit Card or ACH</option>
        <option value="check">Pay with Check</option>
      </select>

      <div class="yl_payment_cc_proceed_container">
        <!--<div class="alert alert-warning">-->
          <!--<p><strong>NOTE:</strong> There is a <?php echo $convenience_fee_charge_percentage ?>% convenience fee when using a Credit/Debit Card.<br>
            <br>
            Convenience Fee: $<span class="fee"></span><br>
            Total Charged: $<span class="total"></span>
          </p>-->
        <!--</div>-->
        <span class="yl_payment_cc_proceed btn btn-primary">Proceed to Payment</span>
      </div>
      <div class="yl_payment_check_disclaimer_container alert alert-info">
        <p>Please give a check made out for $<span></span> to your Building Manager and they will process this payment for you.</p>
      </div>

      <?php
        if(current_user_can( 'building_manager' )){ 
          ?>
          <br><br><br><br>
          <a href="<?php echo $_GET['redirect'];?>&amp;check_payment=1" class="btn btn-danger">CLIENT IS PAYING BY CHECK</a>
          <br><br>
          <?php
        }
      ?>

    </div>

  </div>

  <div class="modal fade" id="yl_payment_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-body">
          <iframe src="" style="zoom:0.60" width="99.6%" height="850" frameborder="0"></iframe>
        </div>
      </div>
    </div>
  </div>

  <script>
  var frameSrc = "<?php echo get_permalink($invoice_id).'?open_form=1'; ?><?php echo (($_GET['redirect']) ? '&redirect='.$_GET['redirect'] : ''); ?><?php echo (($_GET['bmprocess']) ? '&bmprocess=1' : ''); ?>";

  jQuery('.yl_payment_method_select').change(function() {
    var _t = jQuery(this);
    if (_t.val() == 'cc') {
      jQuery('.yl_payment_cc_proceed_container').show();
      jQuery('#line_items_totals #line_service_fee').hide();
      jQuery('#line_items_totals #line_total').show();
      jQuery('.yl_payment_check_disclaimer_container').hide();

      var _subtotal; //= parseFloat(jQuery('#line_items_totals #line_balance .money_amount').html().replace('$', '').replace(',', ''));
      if(jQuery('#line_items_totals #line_balance .money_amount').length ) 
          var _subtotal = parseFloat(jQuery('#line_items_totals #line_balance .money_amount').html().replace('$', '').replace(',', ''));
      else
          var _subtotal = parseFloat(jQuery('#line_items_totals #line_total .money_amount').html().replace('$', '').replace(',', ''));
      
      var _fee = parseFloat((_subtotal/100)*<?php echo $convenience_fee_charge_percentage ?>);
      jQuery('.yl_payment_cc_proceed_container .fee').html(_fee.toFixed(2));
      jQuery('.yl_payment_cc_proceed_container .total').html((_subtotal+_fee).toFixed(2));

      //jQuery('#line_items_totals #line_total .money_amount').html((_subtotal+_fee).toFixed(2));
    }
    else {
      jQuery('.yl_payment_cc_proceed_container').hide();
      jQuery('#line_items_totals #line_service_fee').hide();
      jQuery('#line_items_totals #line_total').show();
      jQuery('.yl_payment_check_disclaimer_container').show();

      var _subtotal; 
      if(jQuery('#line_items_totals #line_balance .money_amount').length ) 
          var _subtotal = parseFloat(jQuery('#line_items_totals #line_balance .money_amount').html().replace('$', '').replace(',', ''));
      else
          var _subtotal = parseFloat(jQuery('#line_items_totals #line_total .money_amount').html().replace('$', '').replace(',', ''));
      //var _subtotal = parseFloat(jQuery('#line_items_totals #line_subtotal .money_amount').html().replace('$', '').replace(',', ''));
      jQuery('.yl_payment_check_disclaimer_container span').html(_subtotal.toFixed(2));

      //jQuery('#line_items_totals #line_total .money_amount').html((_subtotal).toFixed(2));
    }
  });

  jQuery('.yl_payment_cc_proceed').click(function() {
  	var IS_IPAD = navigator.userAgent.match(/iPad/i) != null;
	if (IS_IPAD) {
		window.open(frameSrc, '_blank');
	}  
    jQuery('#yl_payment_modal iframe').attr("src", frameSrc);
    jQuery('#yl_payment_modal').modal({show:true})
  })

  function redirectToForm(url) {
    //jQuery('#yl_payment_modal').modal('hide');

    //setTimeout(function() {
      location.href = url;
    //}, 1000);
  }
  </script>
  <?php

	return ob_get_clean();
}
add_shortcode('yl_lease_checkout','yl_lease_checkout_function');

function checkout_payment_data($invoice_id) {
	//$invoice_id = get_post_meta($lease_id, '_yl_invoice_id', true);
	//$invoice_id = get_post_meta($payment_id, '_payment_invoice', true);
	$args = array(
		'post_type' => 'sa_payment',
		'post_status' => 'any',
		'posts_per_page' => -1,
		'meta_query' => array(
			array(
				'key'     => '_payment_invoice',
				'value'   => $invoice_id,
				'compare' => '=',
			),
		),
	);
	$payment_id = 0;
	$query = new WP_Query($args);
	if($query->have_posts()){
		global $post;
		while($query->have_posts()){
			$query->the_post();
			$payment_id = $post->ID;
		}
	}
	
	//$payment_id = $item->ID;
	if( $payment_id > 0 ) {
		$payment = SI_Payment::get_instance( $payment_id );
		$method = $payment->get_payment_method();
		$data = $payment->get_data();
		$detail = '';
		if ( is_array( $data ) ) {
			//print_r($data);
			foreach ( $data as $key => $value ) {
				if ( is_array( $value ) ) {
					$value = sprintf( '<pre id="payment_detail_%s" style="width="500px"; white-space:pre-wrap; text-align: left; font: normal normal 11px/1.4 menlo, monaco, monospaced; padding: 5px;">%s</pre>', $payment_id, print_r( $value, true ) );
				}
				if ( is_string( $value ) ) {
					if( $key == 'date' ) {
						$detail .= '<dl>
							<dt><b>'.ucfirst(str_replace( '_', ' ', $key )).'</b></dt>
							<dd>'.date("Y-m-d", $value).'</dd>
						</dl>';
					} else {
						$detail .= '<dl>
							<dt><b>'.ucfirst(str_replace( '_', ' ', $key )).'</b></dt>
							<dd>'.$value.'</dd>
						</dl>';
					}
				}
				
			}
		}
			
		$output = '<div id="total_detail_info">';
		if($method == 'Credit (NMI)') {
			$output .= '<p>Online Payment</p>';
		} elseif($method == 'Admin Payment') {
			$output .= '<div class="admin-payment-details">'.esc_html($detail).'</div>';
		}
		$output .= '</div>';
	
		return $output;
	}
}

function admin_payment_info_script() {
?>
	<script type="text/javascript">
		jQuery( document ).ready(function() {
			jQuery(".yl_checkout_sc_block #total_detail_info").hide();
			jQuery("#line_total .money_amount").css('cursor', 'pointer');
			jQuery("#line_total .money_amount").css('color', '#d9534f');
			var admin_payment = jQuery(".yl_checkout_sc_block #total_detail_info").html();
			if( jQuery( "#total_detail_info" ).length ) {
				jQuery("#line_total .money_amount").append('<div id="total_detail_info">'+admin_payment+'</div>');
			}
			jQuery("#line_total .money_amount #total_detail_info").hide();
		});
	</script>
    <style type="text/css">
		#line_total .money_amount:hover #total_detail_info { background: rgba(0, 0, 0, 0.85); border-radius: 5px; bottom: 0; color: #e6e6e6; display: block !important; font-size: 80%; padding: 10px; position: absolute; right: 0; }
	</style>
<?php
}
add_action('wp_head', 'admin_payment_info_script');


