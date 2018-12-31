<?php 
/*Plugin Name: Reports Network
Description: This plugin create auxiliary custom post type.
Version: 1.0
License: GPLv2
*/
/***************Custom Dashboard****************/
$dir = plugin_dir_url( __FILE__ );
define( 'REPORTSPATH', $dir . 'js' );
// define( 'MK_MAIN_DIR', $dir  );

add_action( 'admin_enqueue_scripts', 'network_enqueue_scripts2' );

	function network_enqueue_scripts2()
	{
		wp_enqueue_script( 'reports_js', REPORTSPATH . '/reports.js', array( 'jquery' ));
	}

// wp_enqueue_script( 'script', get_template_directory_uri() . '/metaboxes/js/script.js', array ( 'jquery' ), 1.1, true);
function add_e2_date_picker(){
//jQuery UI date picker file
wp_enqueue_script('jquery-ui-datepicker');
wp_enqueue_script('jquery-ui-datepicker');


//jQuery UI theme css file
wp_enqueue_style('e2b-admin-ui-css','http://ajax.googleapis.com/ajax/libs/jqueryui/1.9.0/themes/base/jquery-ui.css',false,"1.9.0",false);
}
add_action('admin_enqueue_scripts', 'add_e2_date_picker'); 

add_action('wp_network_dashboard_setup', 'my_custom_dashboard_widgets');

add_action('network_admin_menu', 'function_name');
	function function_name()
	{
	// add_menu_page("page_title","menu_title",'capability','menu_slug','function_name');	
	// add_submenu_page( 'edit.php?post_type=lease', 'Invoice Settings', 'Invoice Settings', 'edit_posts', 'invoice-settings2', 'nvoice_settings_submenu_page_callback2' );
		add_dashboard_page('Accounting Reports', 'Accounting Reports', 'edit_posts', 'mk_reports', 'nvoice_settings_submenu_page_accounting');
				// add_dashboard_page('Accounting Reports Testing', 'Accounting Reports Testing', 'edit_posts', 'mk_reports_testing', 'testing_income');

	}

 
function my_custom_dashboard_widgets() {
global $wp_meta_boxes;

// wp_add_dashboard_widget('custom_help_widget', 'Theme Support', 'custom_dashboard_help');
wp_add_dashboard_widget('account_section_report', 'Accounting Reports', 'nvoice_settings_submenu_page_accounting');
}

// function custom_dashboard_help() {
// echo '<p>Welcome to Custom Blog Theme! Need help? Contact the developer <a href="mailto:yourusername@gmail.com">here</a>. For WordPress Tutorials visit: <a href="http://www.wpbeginner.com" target="_blank">WPBeginner</a></p>';
// }

// add_action('admin_menu','register_invoice_submenu_auxil');
// add_action('admin_menu','register_invoice_submenu_auxil');
//     function register_invoice_submenu_auxil() {
// 	// add_submenu_page( 'edit.php?post_type=auxiliary_charges', 'Accounting Section', 'Accounting Section', 'edit_posts', 'account-section2', 'nvoice_settings_submenu_page_accounting' );
// 	add_menu_page ( 'Accounting Section', 'Accounting Section', 'manage_options', 'account-section2', 'nvoice_settings_submenu_page_accounting');
// 	}



	function nvoice_settings_submenu_page_accounting()
	{
		?>

		<script src="http://code.jquery.com/ui/1.11.1/jquery-ui.min.js"></script>
<style type="text/css" media="screen">
	a.boxclose{
    float:right;
    margin-top:-30px;
    margin-right:-30px;
    cursor:pointer;
    color: #fff;
    border: 1px solid #AEAEAE;
    border-radius: 30px;
    background: #605F61;
    font-size: 31px;
    font-weight: bold;
    display: inline-block;
    line-height: 0px;
    padding: 11px 3px;       
}

.boxclose:before {
    content: "×";
}
</style>
<link rel="stylesheet" href="https://code.jquery.com/ui/1.11.1/themes/smoothness/jquery-ui.css" />

<script  type="text/javascript" charset="utf-8" >

function showyourPopup() {
    jQuery("#yourPopup").dialog({
        autoOpen: true,
        resizable: false,
        height: 'auto',
        width: 'auto',
        modal: true,
        //show: { effect: "puff", duration: 300 }, 
        draggable: true
    });

   jQuery(".ui-widget-header").css({"display":"none"}); 
}

function showyourPopup2() {
    jQuery("#yourPopupauxrent").dialog({
        autoOpen: true,
        resizable: false,
        height: 'auto',
        width: 'auto',
        modal: true,
        //show: { effect: "puff", duration: 300 }, 
        draggable: true
    });

   jQuery(".ui-widget-header").css({"display":"none"}); 
}

function closeyourPopup() { jQuery("#yourPopup").dialog('close'); }
function closeyourPopup2() { jQuery("#yourPopupauxrent").dialog('close'); }

/* Submit Resources Popup */

function submitResources_phone(startdate,enddate,building,check){




	    jQuery('#yourPopup2').empty(); 
	
	    showyourPopup();
    	var data = {
			'action': 'get_client_allinvoices_phone',
			/*'suite_id': id,*/
			'building': building,
			'startdate': startdate,
			'enddate': enddate,
			'check': check,
		};
		var ajaxurl = '<?php echo admin_url('admin-ajax.php'); ?>';
		// since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
		jQuery.post(ajaxurl, data, function(response) {
			// alert('Got this from the server: ' + response);

	    // jQuery('#yourPopup2').html(response); 
	    jQuery('#yourPopup2').html(response).promise().done(function(){
		    //your callback logic / code here
		    // alert(response);
	    jQuery("#yourPopup").dialog('open');
		});
		});

}

function submitResources(id,startdate,enddate,building){


	    jQuery('#yourPopup2').empty(); 
	
	    showyourPopup();
    	var data = {
			'action': 'get_client_allinvoices',
			'suite_id': id,
			'building': building,
			'startdate': startdate,
			'enddate': enddate,
		};
		var ajaxurl = '<?php echo admin_url('admin-ajax.php'); ?>';
		// since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
		jQuery.post(ajaxurl, data, function(response) {
			// alert('Got this from the server: ' + response);

	    // jQuery('#yourPopup2').html(response); 
	    jQuery('#yourPopup2').html(response).promise().done(function(){
		    //your callback logic / code here
	    jQuery("#yourPopup").dialog('open');
		});
		});

}


function submitymember(id,startdate,enddate,building){


	    jQuery('#yourPopup2').empty(); 
	
	    showyourPopup();
    	var data = {
			'action': 'get_ymember',
			'client_id': id,
			'building': building,
			'startdate': startdate,
			'enddate': enddate,

		};
		var ajaxurl = '<?php echo admin_url('admin-ajax.php'); ?>';
		// since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
		jQuery.post(ajaxurl, data, function(response) {
			// alert('Got this from the server: ' + response);

	    // jQuery('#yourPopup2').html(response); 
	    jQuery('#yourPopup2').html(response).promise().done(function(){
		    //your callback logic / code here
	    jQuery("#yourPopup").dialog('open');
		});
		});

}






function submitResources_multisuite(suiteid,invoiceid,startdate,enddate,building){


	    jQuery('#yourPopup2').empty(); 
	
	    showyourPopup();
    	var data = {
			'action': 'get_client_allinvoices_multi',
			'suite_id': suiteid,
			'invoice_id': invoiceid,
			'building': building,
			'startdate': startdate,
			'enddate': enddate,
		};
		var ajaxurl = '<?php echo admin_url('admin-ajax.php'); ?>';
		// since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
		jQuery.post(ajaxurl, data, function(response) {
			// alert('Got this from the server: ' + response);

	    // jQuery('#yourPopup2').html(response); 
	    jQuery('#yourPopup2').html(response).promise().done(function(){
		    //your callback logic / code here
	    jQuery("#yourPopup").dialog('open');
		});
		});

}


/* Submit Resources Popup */
function submitymemberaux(id,startdate,enddate,building){


	    jQuery('#yourPopupauxrent2').empty(); 
	
	    showyourPopup2();
    	var data = {
			'action': 'get_ymemberaux',
			'client_id': id,
			'building': building,
			'startdate': startdate,
			'enddate': enddate,
		};
		var ajaxurl = '<?php echo admin_url('admin-ajax.php'); ?>';
		// since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
		jQuery.post(ajaxurl, data, function(response) {
			// alert('Got this from the server: ' + response);

	    // jQuery('#yourPopup2').html(response); 
	    jQuery('#yourPopupauxrent2').html(response).promise().done(function(){
		    //your callback logic / code here
	    jQuery("#yourPopupauxrent").dialog('open');
		});
		});

}




function submitResourcesauxrent(id,startdate,enddate,building){


	    jQuery('#yourPopupauxrent2').empty(); 
	
	    showyourPopup2();
    	var data = {
			'action': 'get_client_allauxrent',
			'suite_id': id,
			'building': building,
			'startdate': startdate,
			'enddate': enddate,
		};
		var ajaxurl = '<?php echo admin_url('admin-ajax.php'); ?>';
		// since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
		jQuery.post(ajaxurl, data, function(response) {
			// alert('Got this from the server: ' + response);

	    // jQuery('#yourPopup2').html(response); 
	    jQuery('#yourPopupauxrent2').html(response).promise().done(function(){
		    //your callback logic / code here
	    jQuery("#yourPopupauxrent").dialog('open');
		});
		});

}


function submitResourcesauxrent_multisite(suiteid,invoiceid,startdate,enddate,building,auxshow){


	    jQuery('#yourPopupauxrent2').empty(); 
	
	    showyourPopup2();
    	var data = {
			'action': 'get_client_allauxrent_multisuite',
			'suite_id':suiteid,
			'invoice_id': invoiceid,
			'auxshow': auxshow,
			'building': building,
			'startdate': startdate,
			'enddate': enddate,
		};
		var ajaxurl = '<?php echo admin_url('admin-ajax.php'); ?>';
		// since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
		jQuery.post(ajaxurl, data, function(response) {
			// alert('Got this from the server: ' + response);

	    // jQuery('#yourPopup2').html(response); 
	    jQuery('#yourPopupauxrent2').html(response).promise().done(function(){
		    //your callback logic / code here
	    jQuery("#yourPopupauxrent").dialog('open');
		});
		});

}







		jQuery(document).ready(function($) {
				$(".datepicker").datepicker({
				dateFormat: "yy-mm-dd"
				});
		});

		</script>
<div id="yourPopup" style="padding:0; margin:0; display:none;">
<a href="javascript:void(0)" onclick="closeyourPopup();" title="" style="float:right;"><img src="<?php echo plugin_dir_url( __FILE__ ) . 'images/cross_button.png'; ?>" alt=""></a>
<div id="yourPopup2">
</div>

</div>

<div id="yourPopupauxrent" style="padding:0; margin:0; display:none;">
<a href="javascript:void(0)" onclick="closeyourPopup2();" title="" style="float:right;"><img src="<?php echo plugin_dir_url( __FILE__ ) . 'images/cross_button.png'; ?>" alt=""></a>
<div id="yourPopupauxrent2">
hiii hello
</div>

</div>


<?php 
		


		if(isset($_POST['accountingmk_submit']))
		{


			if($_POST['_accounting_report_id']=="Deposit ACH")
{

				$building=$_POST['_accounting_building_id'];

			if($building=="all")
			{

				$sites = wp_get_sites();


				$all_blogs_id=array();
				$removed_ids=array(1,20,19,6);
				foreach ($sites as $key => $current_blog) {

					if(!in_array($current_blog['blog_id'], $removed_ids))
					{
					array_push($all_blogs_id, $current_blog['blog_id']);
					}
				}
						$csv_data_array=array();
				$i=1;

				foreach ($all_blogs_id as $crid) {
			
// Fort Harrison – 
// Frisco – 
// McKinney – 
// // All others – Busey Bank
// Plano – will be Texas Capital Bank
$bank="Busey Bank";
					switch ($crid) {
					case '4':
					$class="DEV";
					break;
					case '9':
					$class="MCK";
					$bank="Texas Capital";

					break;
					case '10':
					$class="FR";
					$bank="Texas Capital Bank";

					break;
					case '11':
					$class="FHRA";
					$bank="Star Bank";
					break;	
					case '12':
					$class="C1";
					break;
					case '13':
					$class="F1";
					break;
					case '14':
					$class="F2";
					break;
					case '15':
					$class="GW";
					break;
					case '16':
					$class="N1";
					break;
					case '17':
					$class="N2";
					break;
					case '18':
					$class="OSW";
					break;										
					default:
					$class="DEV";
						break;
				}



					?>
					<div class="wrap">
					<h2 class="mkbis2">Deposit Journal Entries</h2>
					<?php

				
				$sites = wp_get_sites();

				switch_to_blog($crid);


				global $wpdb;
				global $post;
				$start_account=$_POST['_accounting_start_date'];
				$start_acc=explode("-", $start_account);
				$str_yr=$start_acc[0];
				$str_mn=$start_acc[1];
				$str_dy=$start_acc[2];
				$end_account=$_POST['_accounting_end_date'];
				$end_acc=explode("-", $end_account);
				$end_yr=$end_acc[0];
				$end_mn=$end_acc[1];
				$end_dy=$end_acc[2];
				$args = array(
					'post_type' => 'sa_payment',
					'post_status' => 'complete',
						'date_query' => array(
							array(
								'column' => 'post_modified',
								'after'     => array(
									'year'  => $str_yr,
									'month' => $str_mn,
									'day'   => $str_dy,
								),
								'before'    => array(
									'year'  => $end_yr,
									'month' => $end_mn,
									'day'   => $end_dy,
								),
								'inclusive' => true,
							),
						),
					'posts_per_page' => -1,
					);



					$results = get_posts($args);

				$all_week_data=array();
				$all_week_data2=array();
				foreach ($results as $key => $result) {
				$paymentdatefull=explode(" ", $result->post_modified);		
				$paymentdate=$paymentdatefull[0];		

				$paymentid=$result->ID;	

				$recme=get_post_meta($paymentid,NULL,true);
				$metttadata1=array();
				$metttadata1['post_date']=$paymentdate;
				foreach ($recme as $m1 => $v1) {
					// echo $m1;
					// echo $v1[0];
					# code...
					$metttadata1[$m1]=$v1[0];
							if( $m1=="_payment_invoice")
					{
					$metttadata1['line_items']=get_post_meta( $v1[0], '_doc_line_items', true );
					}

					
				}
				// print_r($metttadata1);
				$credit=get_post_meta($paymentid,'_payment_method',true);
				$invoice_id=get_post_meta($paymentid,'_payment_invoice',true);
				if($credit=="Credit (NMI)" && get_post_status( $invoice_id ) =="complete")
				{

				$all_week_data2[$paymentid]=$metttadata1;
				}
				}
			
// 				foreach ($all_week_data2 as  $i=>$data) {
// 					# code...
					
				
// 				$invoice_id= $data['_payment_invoice'];
// 				$argspayment = array(
//     'meta_key' => '_payment_invoice',
//     'meta_value' => $invoice_id,
//     'post_type' => 'sa_payment',
//     'post_status' => 'any',
//     'posts_per_page' => -1
// );
// $paymentid = get_posts($argspayment);
// // echo "<pre>";
// // print_r($paymentid);
// // echo "</pre>";

// $credit=get_post_meta( $paymentid[0]->ID, "_payment_method", true );
// 		// var_dump($credit);
// 				if(get_post_status( $invoice_id ) !="complete" || $credit!="Credit (NMI)")
// 				{
// 						unset($all_week_data2[$i]);
// 					}
// 					// echo get_post_status( $data['_payment_invoice'] );
// 					// unset($array[$i]);
// 				}
				    // print_r($results3);
				// echo "<pre>";
				// print_r($all_week_data);
				// echo "</pre>";
			$final_data_table=array();
			$final_data_table['total']="";
			$final_data_table['fees']="";
			$final_data_table['all_dates']=array();
			foreach ($all_week_data2 as $key => $value) {
				$percentage=(float) (100+get_post_meta( $value['_payment_invoice'], '_doc_tax2', true )); 
				$singlepercentage=number_format($value['_amount']/$percentage,2);
				$total_value_added= number_format($singlepercentage*get_post_meta( $value['_payment_invoice'], '_doc_tax2', true ),2);//$value['_amount']/
				// number_format($final_data_table['total'],2)
				$subtotal=$value['_amount']-$total_value_added;
				$final_data_table['total']=(float) ($final_data_table['total']+$subtotal);
				$final_data_table['fees']=(float) ($final_data_table['fees']+$total_value_added);
				if($final_data_table['all_dates'][$value['post_date']]!=NULL && $final_data_table['all_dates'][$value['post_date']]!="")
				{
       				$amount_arr=$final_data_table['all_dates'][$value['post_date']];
					$final_data_table['all_dates'][$value['post_date']]=(float) ($amount_arr+$value['_amount']);
				}
				else{
				$final_data_table['all_dates'][$value['post_date']]= $value['_amount'];

				}
			}
			// echo "<pre>";
			// print_r($final_data_table);
			?>
			<style type="text/css" media="screen">
					.mk_account_reports table{
							border-collapse: collapse;
							margin: 10px 0;
						}
					.mk_account_reports	th{
							font-weight: normal;
							border: 1px solid #D4D4D4;
							padding: 3px;
							text-align: left;
						}
					.mk_account_reports	td{
							border: 1px solid #D4D4D4;
							}
			</style>
			<div class="mk_account_reports">
				
			<table cellpadding="0" cellspacing="0" border="0" width="100%">
			<thead>
				<tr>
					<th>Accounts Receivable</th>
					<th></th>
					<th style="text-align: right;"><?php echo "$".$final_data_table['total']; ?></th>
					<th>Week of <?php echo $end_account; ?></th>
					<th></th>
					<th></th>
					<th>Class</th>

				</tr>
			</thead>
			<tbody>
			<?php
			$csv_data_array=array();
			$i=2;
			foreach ($final_data_table['all_dates'] as $key => $value) {
				?>
			<tr>
			<td><?php echo $bank; ?></td>
			<td>$<?php echo $value;?></td>
			<td></td>
			<td><?php echo $key; ?></td>
			<td></td>
			<td></td>
			<td><?php echo $class; ?></td>
			
			</tr>
				<?php
				if($i==2)
				{

				$csv_data_array[0]['Accounts']='Accounts Receivable';
				$csv_data_array[0]['Debit']=" ";
				$csv_data_array[0]['Credit']='$' . number_format($final_data_table['total'],2);
				$csv_data_array[0]["MEMO"]="Week of ".$end_account;
				$csv_data_array[1]['Accounts']='Fees';
				$csv_data_array[1]['Debit']=" ";
				$csv_data_array[1]['Credit']='$' . number_format($final_data_table['fees'],2);
				$csv_data_array[1]["MEMO"]="Week of ".$end_account;
				}
				// Account, Debit, Credit, Memo
				$csv_data_array[$i]['Accounts']=$bank;
				$csv_data_array[$i]['Debit']='$' . number_format($value,2);
				$csv_data_array[$i]['Credit']="";
				$csv_data_array[$i]["MEMO"]=$key;
			$i++;
			}
			?>
				</tbody>
				</table>
				</div>
				</div>
				<?php
				restore_current_blog();


				}
				reporst_csv("Deposit journal invoice.csv",$csv_data_array,"rental_report");



				}
				else{
					echo $building;
			$bank="Busey Bank";
					switch ($building) {
					case '4':
					$class="DEV";
					break;
					case '9':
					$class="MCK";
					$bank="Texas Capital";

					break;
					case '10':
					$class="FR";
					$bank="Texas Capital Bank";

					break;
					case '11':
					$class="FHRA";
					$bank="Star Bank";
					break;	
					case '12':
					$class="C1";
					break;
					case '13':
					$class="F1";
					break;
					case '14':
					$class="F2";
					break;
					case '15':
					$class="GW";
					break;
					case '16':
					$class="N1";
					break;
					case '17':
					$class="N2";
					break;
					case '18':
					$class="OSW";
					break;										
					default:
					$class="DEV";
						break;
				}

				?>
				<div class="wrap">
				<h2 class="mkbis2">Deposit Journal Entries</h2>
				<?php
				$building=$_POST['_accounting_building_id'];


			
			$sites = wp_get_sites();

			switch_to_blog($building);


			global $wpdb;
			global $post;


			$start_account=$_POST['_accounting_start_date'];
			$start_acc=explode("-", $start_account);
			$str_yr=$start_acc[0];
			$str_mn=$start_acc[1];
			$str_dy=$start_acc[2];
			$end_account=$_POST['_accounting_end_date'];
			$end_acc=explode("-", $end_account);
			$end_yr=$end_acc[0];
			$end_mn=$end_acc[1];
			$end_dy=$end_acc[2];
			$args = array(
				'post_type' => 'sa_payment',
				'post_status' => 'complete',
					'date_query' => array(
						array(
							'column' => 'post_modified',
							'after'     => array(
								'year'  => $str_yr,
								'month' => $str_mn,
								'day'   => $str_dy,
							),
							'before'    => array(
								'year'  => $end_yr,
								'month' => $end_mn,
								'day'   => $end_dy,
							),
							'inclusive' => true,
						),
					),
				'posts_per_page' => -1,
				);


				$results = get_posts($args);

			$all_week_data=array();
			$all_week_data2=array();
			// foreach ($results as $key => $result) {
			// $paymentdatefull=explode(" ", $result->post_modified);		
			// $paymentdate=$paymentdatefull[0];		

			// $paymentid=$result->ID;	

			// $recme=get_post_meta($paymentid,NULL,true);
			// $metttadata1=array();
			// $metttadata1['post_date']=$paymentdate;
			// foreach ($recme as $m1 => $v1) {

			// 	$metttadata1[$m1]=$v1[0];
			// 			if( $m1=="_payment_invoice")
			// 	{
			// 	$metttadata1['line_items']=get_post_meta( $v1[0], '_doc_line_items', true );
			// 	}

				
			// }
			// $all_week_data2[$paymentid]=$metttadata1;



			// }
				foreach ($results as $key => $result) {
				$paymentdatefull=explode(" ", $result->post_modified);		
				$paymentdate=$paymentdatefull[0];		

				$paymentid=$result->ID;	

				$recme=get_post_meta($paymentid,NULL,true);
				$metttadata1=array();
				$metttadata1['post_date']=$paymentdate;
				foreach ($recme as $m1 => $v1) {
					// echo $m1;
					// echo $v1[0];
					# code...
					$metttadata1[$m1]=$v1[0];
							if( $m1=="_payment_invoice")
					{
					$metttadata1['line_items']=get_post_meta( $v1[0], '_doc_line_items', true );
					}

					
				}
				// print_r($metttadata1);
				$credit=get_post_meta($paymentid,'_payment_method',true);
				$invoice_id=get_post_meta($paymentid,'_payment_invoice',true);
				$percentage=get_post_meta( $invoice_id, '_doc_tax2', true );
				if($credit=="Bank (NMI)" && get_post_status( $invoice_id ) =="complete")
				{
					
				$all_week_data2[$paymentid]=$metttadata1;
				}
				}

			
			foreach ($all_week_data2 as  $i=>$data) {
				# code...
				
				$invoice_id= $data['_payment_invoice'];
				$percentage=get_post_meta( $invoice_id, '_doc_tax2', true );

				$argspayment = array(
			    'meta_key' => '_payment_invoice',
			    'meta_value' => $invoice_id,
			    'post_type' => 'sa_payment',
			    'post_status' => 'any',
			    'posts_per_page' => -1
			);
			$paymentid = get_posts($argspayment);

		

			$credit=get_post_meta( $paymentid[0]->ID, "_payment_method", true );
					// var_dump($credit);
							if(get_post_status( $invoice_id ) !="complete" && $credit!="Credit (NMI)")
							{
								unset($all_week_data2[$i]);
							}

			}
			$final_data_table=array();
			$final_data_table['total']="";
			$final_data_table['fees']="";
			$final_data_table['all_dates']=array();
			foreach ($all_week_data2 as $key => $value) {
				$percentage=100+get_post_meta( $value['_payment_invoice'], '_doc_tax2', true ); 
				$singlepercentage=$value['_amount']/$percentage;
				$total_value_added= number_format($singlepercentage*get_post_meta( $value['_payment_invoice'], '_doc_tax2', true ),2);//$value['_amount']/
				// number_format($final_data_table['total'],2)
				$subtotal=$value['_amount']-$total_value_added;
				$final_data_table['total']=$final_data_table['total']+$subtotal;
				$final_data_table['fees']=$final_data_table['fees']+$total_value_added;

				$invvv=maybe_unserialize(get_post_meta($value['_payment_invoice'], '_fees', false ));
				$fees=$invvv[0]['cc_service_fee']['total'];
				$final_data_table['fees']=$final_data_table['fees']+$fees;
		// echo "Fees added = ";
		// echo  $fees;
		// echo "<br>";



			// 	echo "prencentage=".$percentage;
			// echo "<br>";
			// 	echo "singlepercentage=".$singlepercentage;
			// echo "<br>";
			// 	echo "total_value_added=".$total_value_added;
			// echo "<br>";
			// echo "Total value added=".$total_value_added;
			// echo "<br>";
			// echo "Subtotal=".$subtotal;
			// echo "Final total=".$final_data_table['total'];
			// echo "Final fee =".$final_data_table['fees'];
			// echo "<br>";

				if($final_data_table['all_dates'][$value['post_date']]!=NULL && $final_data_table['all_dates'][$value['post_date']]!="")
				{
       				$amount_arr=$final_data_table['all_dates'][$value['post_date']];
					$final_data_table['all_dates'][$value['post_date']]=(float) ($amount_arr+$value['_amount']);
				}
				else{
				$final_data_table['all_dates'][$value['post_date']]= $value['_amount'];

				}
			}
			if($final_data_table['fees']=="")
			{
				$final_data_table['fees']=0;
			}
			// echo "<pre>";
			// print_r($final_data_table);
			?>
			<style type="text/css" media="screen">
					.mk_account_reports table{
							border-collapse: collapse;
							margin: 10px 0;
						}
					.mk_account_reports	th{
							font-weight: normal;
							border: 1px solid #D4D4D4;
							padding: 3px;
							text-align: left;
						}
					.mk_account_reports	td{
							border: 1px solid #D4D4D4;
							}
			</style>
			<div class="mk_account_reports">
				
			<table cellpadding="0" cellspacing="0" border="0" width="100%">
			<thead>
				<tr>
					<th>Accounts Receivable</th>
					<th>Debit</th>
					<th style="text-align: right;">Credit</th>
					<th>MEMO</th>
					<th></th>
					<th></th>
					<th>Class</th>

				</tr>





				
			</thead>
			<tbody>
			<?php
			$csv_data_array=array();
			$i=2;
			foreach ($final_data_table['all_dates'] as $key => $value) {
				?>
			<tr>
			<td><?php echo $bank; ?></td>
			<td>$<?php echo $value;?></td>
			<td></td>
			<td><?php echo $key; ?></td>
			<td></td>
			<td></td>
			<td><?php echo $class; ?></td>
			
			</tr>
				<?php
				if($i==2)
				{

				$csv_data_array[0]['Accounts']='Accounts Receivable';
				$csv_data_array[0]['Debit']=" ";
				$csv_data_array[0]['Credit']='$' . number_format($final_data_table['total'],2);
				$csv_data_array[0]["MEMO"]="Week of ".$end_account;
				// 	$csv_data_array[1]['Accounts']='Fees';
				// $csv_data_array[1]['Debit']=" ";
				// $csv_data_array[1]['Credit']='$' . number_format($final_data_table['fees'],2);
				// $csv_data_array[1]["MEMO"]="Week of ".$end_account;
				}
				// Account, Debit, Credit, Memo
				$csv_data_array[$i]['Accounts']=$bank;
				$csv_data_array[$i]['Debit']='$' . number_format($value,2);
				$csv_data_array[$i]['Credit']="";
				$csv_data_array[$i]["MEMO"]=$key;
			$i++;
			}
			?>
			</tbody>
			</table>
			</div>
			</div>
			<?php

			reporst_csv("Deposit journal invoice (ASH).csv",$csv_data_array,"rental_report");




			restore_current_blog();

	
				}




			

}







			if($_POST['_accounting_report_id']=="Deposit Journal Entries")
			{
				$building=$_POST['_accounting_building_id'];

			if($building=="all")
			{

				$sites = wp_get_sites();


				$all_blogs_id=array();
				$removed_ids=array(1,20,19,6);
				foreach ($sites as $key => $current_blog) {

					if(!in_array($current_blog['blog_id'], $removed_ids))
					{
					array_push($all_blogs_id, $current_blog['blog_id']);
					}
				}
						$csv_data_array=array();
				$i=1;

				foreach ($all_blogs_id as $crid) {
			
// Fort Harrison – 
// Frisco – 
// McKinney – 
// // All others – Busey Bank
// Plano – will be Texas Capital Bank
$bank="Busey Bank";
					switch ($crid) {
					case '4':
					$class="DEV";
					break;
					case '9':
					$class="MCK";
					$bank="Texas Capital";

					break;
					case '10':
					$class="FR";
					$bank="Texas Capital Bank";

					break;
					case '11':
					$class="FHRA";
					$bank="Star Bank";
					break;	
					case '12':
					$class="C1";
					break;
					case '13':
					$class="F1";
					break;
					case '14':
					$class="F2";
					break;
					case '15':
					$class="GW";
					break;
					case '16':
					$class="N1";
					break;
					case '17':
					$class="N2";
					break;
					case '18':
					$class="OSW";
					break;										
					default:
					$class="DEV";
						break;
				}



					?>
					<div class="wrap">
					<h2 class="mkbis2">Deposit Journal Entries</h2>
					<?php

				
				$sites = wp_get_sites();

				switch_to_blog($crid);


				global $wpdb;
				global $post;
				$start_account=$_POST['_accounting_start_date'];
				$start_acc=explode("-", $start_account);
				$str_yr=$start_acc[0];
				$str_mn=$start_acc[1];
				$str_dy=$start_acc[2];
				$end_account=$_POST['_accounting_end_date'];
				$end_acc=explode("-", $end_account);
				$end_yr=$end_acc[0];
				$end_mn=$end_acc[1];
				$end_dy=$end_acc[2];
				$args = array(
					'post_type' => 'sa_payment',
					'post_status' => 'complete',
						'date_query' => array(
							array(
								'column' => 'post_modified',
								'after'     => array(
									'year'  => $str_yr,
									'month' => $str_mn,
									'day'   => $str_dy,
								),
								'before'    => array(
									'year'  => $end_yr,
									'month' => $end_mn,
									'day'   => $end_dy,
								),
								'inclusive' => true,
							),
						),
					'posts_per_page' => -1,
					);



					$results = get_posts($args);

				$all_week_data=array();
				$all_week_data2=array();
				foreach ($results as $key => $result) {
				$paymentdatefull=explode(" ", $result->post_modified);		
				$paymentdate=$paymentdatefull[0];		

				$paymentid=$result->ID;	

				$recme=get_post_meta($paymentid,NULL,true);
				$metttadata1=array();
				$metttadata1['post_date']=$paymentdate;
				foreach ($recme as $m1 => $v1) {
					// echo $m1;
					// echo $v1[0];
					# code...
					$metttadata1[$m1]=$v1[0];
							if( $m1=="_payment_invoice")
					{
					$metttadata1['line_items']=get_post_meta( $v1[0], '_doc_line_items', true );
					}

					
				}
				// print_r($metttadata1);
				$credit=get_post_meta($paymentid,'_payment_method',true);
				$invoice_id=get_post_meta($paymentid,'_payment_invoice',true);
				if($credit=="Credit (NMI)" && get_post_status( $invoice_id ) =="complete")
				{

				$all_week_data2[$paymentid]=$metttadata1;
				}
				}
			
// 				foreach ($all_week_data2 as  $i=>$data) {
// 					# code...
					
				
// 				$invoice_id= $data['_payment_invoice'];
// 				$argspayment = array(
//     'meta_key' => '_payment_invoice',
//     'meta_value' => $invoice_id,
//     'post_type' => 'sa_payment',
//     'post_status' => 'any',
//     'posts_per_page' => -1
// );
// $paymentid = get_posts($argspayment);
// // echo "<pre>";
// // print_r($paymentid);
// // echo "</pre>";

// $credit=get_post_meta( $paymentid[0]->ID, "_payment_method", true );
// 		// var_dump($credit);
// 				if(get_post_status( $invoice_id ) !="complete" || $credit!="Credit (NMI)")
// 				{
// 						unset($all_week_data2[$i]);
// 					}
// 					// echo get_post_status( $data['_payment_invoice'] );
// 					// unset($array[$i]);
// 				}
				    // print_r($results3);
				// echo "<pre>";
				// print_r($all_week_data);
				// echo "</pre>";
			$final_data_table=array();
			$final_data_table['total']="";
			$final_data_table['fees']="";
			$final_data_table['all_dates']=array();
			foreach ($all_week_data2 as $key => $value) {
				$percentage=(float) (100+get_post_meta( $value['_payment_invoice'], '_doc_tax2', true )); 
				$singlepercentage=number_format($value['_amount']/$percentage,2);
				$total_value_added= number_format($singlepercentage*get_post_meta( $value['_payment_invoice'], '_doc_tax2', true ),2);//$value['_amount']/
				// number_format($final_data_table['total'],2)
				$subtotal=$value['_amount']-$total_value_added;
				$final_data_table['total']=(float) ($final_data_table['total']+$subtotal);
				$final_data_table['fees']=(float) ($final_data_table['fees']+$total_value_added);
				if($final_data_table['all_dates'][$value['post_date']]!=NULL && $final_data_table['all_dates'][$value['post_date']]!="")
				{
       				$amount_arr=$final_data_table['all_dates'][$value['post_date']];
					$final_data_table['all_dates'][$value['post_date']]=(float) ($amount_arr+$value['_amount']);
				}
				else{
				$final_data_table['all_dates'][$value['post_date']]= $value['_amount'];

				}
			}
			// echo "<pre>";
			// print_r($final_data_table);
			?>
			<style type="text/css" media="screen">
					.mk_account_reports table{
							border-collapse: collapse;
							margin: 10px 0;
						}
					.mk_account_reports	th{
							font-weight: normal;
							border: 1px solid #D4D4D4;
							padding: 3px;
							text-align: left;
						}
					.mk_account_reports	td{
							border: 1px solid #D4D4D4;
							}
			</style>
			<div class="mk_account_reports">
				
			<table cellpadding="0" cellspacing="0" border="0" width="100%">
			<thead>
				<tr>
					<th>Accounts Receivable</th>
					<th></th>
					<th style="text-align: right;"><?php echo "$".$final_data_table['total']; ?></th>
					<th>Week of <?php echo $end_account; ?></th>
					<th></th>
					<th></th>
					<th>Class</th>

				</tr>
			</thead>
			<tbody>
			<?php
			$csv_data_array=array();
			$i=2;
			foreach ($final_data_table['all_dates'] as $key => $value) {
				?>
			<tr>
			<td><?php echo $bank; ?></td>
			<td>$<?php echo $value;?></td>
			<td></td>
			<td><?php echo $key; ?></td>
			<td></td>
			<td></td>
			<td><?php echo $class; ?></td>
			
			</tr>
				<?php
				if($i==2)
				{

				$csv_data_array[0]['Accounts']='Accounts Receivable';
				$csv_data_array[0]['Debit']=" ";
				$csv_data_array[0]['Credit']='$' . number_format($final_data_table['total'],2);
				$csv_data_array[0]["MEMO"]="Week of ".$end_account;
					$csv_data_array[1]['Accounts']='Fees';
				$csv_data_array[1]['Debit']=" ";
				$csv_data_array[1]['Credit']='$' . number_format($final_data_table['fees'],2);
				$csv_data_array[1]["MEMO"]="Week of ".$end_account;
				}
				// Account, Debit, Credit, Memo
				$csv_data_array[$i]['Accounts']=$bank;
				$csv_data_array[$i]['Debit']='$' . number_format($value,2);
				$csv_data_array[$i]['Credit']="";
				$csv_data_array[$i]["MEMO"]=$key;
			$i++;
			}
			?>
				</tbody>
				</table>
				</div>
				</div>
				<?php
				restore_current_blog();


				}
				reporst_csv("Deposit journal invoice.csv",$csv_data_array,"rental_report");



				}
				else{

					echo $building;
			$bank="Busey Bank";
					switch ($building) {
					case '4':
					$class="DEV";
					break;
					case '9':
					$class="MCK";
					$bank="Texas Capital";

					break;
					case '10':
					$class="FR";
					$bank="Texas Capital Bank";

					break;
					case '11':
					$class="FHRA";
					$bank="Star Bank";
					break;	
					case '12':
					$class="C1";
					break;
					case '13':
					$class="F1";
					break;
					case '14':
					$class="F2";
					break;
					case '15':
					$class="GW";
					break;
					case '16':
					$class="N1";
					break;
					case '17':
					$class="N2";
					break;
					case '18':
					$class="OSW";
					break;										
					default:
					$class="DEV";
						break;
				}

				?>
				<div class="wrap">
				<h2 class="mkbis2">Deposit Journal Entries</h2>
				<?php
				$building=$_POST['_accounting_building_id'];


			
			$sites = wp_get_sites();

			switch_to_blog($building);


			global $wpdb;
			global $post;


			$start_account=$_POST['_accounting_start_date'];
			$start_acc=explode("-", $start_account);
			$str_yr=$start_acc[0];
			$str_mn=$start_acc[1];
			$str_dy=$start_acc[2];
			$end_account=$_POST['_accounting_end_date'];
			$end_acc=explode("-", $end_account);
			$end_yr=$end_acc[0];
			$end_mn=$end_acc[1];
			$end_dy=$end_acc[2];
			$args = array(
				'post_type' => 'sa_payment',
				'post_status' => 'complete',
					'date_query' => array(
						array(
							'column' => 'post_modified',
							'after'     => array(
								'year'  => $str_yr,
								'month' => $str_mn,
								'day'   => $str_dy,
							),
							'before'    => array(
								'year'  => $end_yr,
								'month' => $end_mn,
								'day'   => $end_dy,
							),
							'inclusive' => true,
						),
					),
				'posts_per_page' => -1,
				);


				$results = get_posts($args);

			$all_week_data=array();
			$all_week_data2=array();
			// foreach ($results as $key => $result) {
			// $paymentdatefull=explode(" ", $result->post_modified);		
			// $paymentdate=$paymentdatefull[0];		

			// $paymentid=$result->ID;	

			// $recme=get_post_meta($paymentid,NULL,true);
			// $metttadata1=array();
			// $metttadata1['post_date']=$paymentdate;
			// foreach ($recme as $m1 => $v1) {

			// 	$metttadata1[$m1]=$v1[0];
			// 			if( $m1=="_payment_invoice")
			// 	{
			// 	$metttadata1['line_items']=get_post_meta( $v1[0], '_doc_line_items', true );
			// 	}

				
			// }
			// $all_week_data2[$paymentid]=$metttadata1;



			// }
				foreach ($results as $key => $result) {
				$paymentdatefull=explode(" ", $result->post_modified);		
				$paymentdate=$paymentdatefull[0];		

				$paymentid=$result->ID;	

				$recme=get_post_meta($paymentid,NULL,true);
				$metttadata1=array();
				$metttadata1['post_date']=$paymentdate;
				foreach ($recme as $m1 => $v1) {
					// echo $m1;
					// echo $v1[0];
					# code...
					$metttadata1[$m1]=$v1[0];
							if( $m1=="_payment_invoice")
					{
					$metttadata1['line_items']=get_post_meta( $v1[0], '_doc_line_items', true );
					}

					
				}
				// print_r($metttadata1);
				$credit=get_post_meta($paymentid,'_payment_method',true);
				$invoice_id=get_post_meta($paymentid,'_payment_invoice',true);
				$percentage=get_post_meta( $invoice_id, '_doc_tax2', true );
				if($credit=="Credit (NMI)" && get_post_status( $invoice_id ) =="complete")
				{
					
				$all_week_data2[$paymentid]=$metttadata1;
				}
				}

			
			foreach ($all_week_data2 as  $i=>$data) {
				# code...
				
				$invoice_id= $data['_payment_invoice'];
				$percentage=get_post_meta( $invoice_id, '_doc_tax2', true );

				$argspayment = array(
			    'meta_key' => '_payment_invoice',
			    'meta_value' => $invoice_id,
			    'post_type' => 'sa_payment',
			    'post_status' => 'any',
			    'posts_per_page' => -1
			);
			$paymentid = get_posts($argspayment);

		

			$credit=get_post_meta( $paymentid[0]->ID, "_payment_method", true );
					// var_dump($credit);
							if(get_post_status( $invoice_id ) !="complete" && $credit!="Credit (NMI)")
							{
								unset($all_week_data2[$i]);
							}

			}
			$final_data_table=array();
			$final_data_table['total']="";
			$final_data_table['fees']="";
			$final_data_table['all_dates']=array();
			foreach ($all_week_data2 as $key => $value) {
				$percentage=100+get_post_meta( $value['_payment_invoice'], '_doc_tax2', true ); 
				$singlepercentage=$value['_amount']/$percentage;
				$total_value_added= number_format($singlepercentage*get_post_meta( $value['_payment_invoice'], '_doc_tax2', true ),2);//$value['_amount']/
				// number_format($final_data_table['total'],2)
				$subtotal=$value['_amount']-$total_value_added;
				$final_data_table['total']=$final_data_table['total']+$subtotal;
				$final_data_table['fees']=$final_data_table['fees']+$total_value_added;

				$invvv=maybe_unserialize(get_post_meta($value['_payment_invoice'], '_fees', false ));
				$fees=$invvv[0]['cc_service_fee']['total'];
				$final_data_table['fees']=$final_data_table['fees']+$fees;
		// echo "Fees added = ";
		// echo  $fees;
		// echo "<br>";



			// 	echo "prencentage=".$percentage;
			// echo "<br>";
			// 	echo "singlepercentage=".$singlepercentage;
			// echo "<br>";
			// 	echo "total_value_added=".$total_value_added;
			// echo "<br>";
			// echo "Total value added=".$total_value_added;
			// echo "<br>";
			// echo "Subtotal=".$subtotal;
			// echo "Final total=".$final_data_table['total'];
			// echo "Final fee =".$final_data_table['fees'];
			// echo "<br>";

				if($final_data_table['all_dates'][$value['post_date']]!=NULL && $final_data_table['all_dates'][$value['post_date']]!="")
				{
       				$amount_arr=$final_data_table['all_dates'][$value['post_date']];
					$final_data_table['all_dates'][$value['post_date']]=(float) ($amount_arr+$value['_amount']);
				}
				else{
				$final_data_table['all_dates'][$value['post_date']]= $value['_amount'];

				}
			}
			if($final_data_table['fees']=="")
			{
				$final_data_table['fees']=0;
			}
			// echo "<pre>";
			// print_r($final_data_table);
			?>
			<style type="text/css" media="screen">
					.mk_account_reports table{
							border-collapse: collapse;
							margin: 10px 0;
						}
					.mk_account_reports	th{
							font-weight: normal;
							border: 1px solid #D4D4D4;
							padding: 3px;
							text-align: left;
						}
					.mk_account_reports	td{
							border: 1px solid #D4D4D4;
							}
			</style>
			<div class="mk_account_reports">
				
			<table cellpadding="0" cellspacing="0" border="0" width="100%">
			<thead>
				<tr>
					<th>Accounts Receivable</th>
					<th>Debit</th>
					<th style="text-align: right;">Credit</th>
					<th>MEMO</th>
					<th></th>
					<th></th>
					<th>Class</th>

				</tr>
				<tr>
					<th>Accounts Receivable</th>
					<th></th>
					<th style="text-align: right;"><?php echo "$".$final_data_table['total']; ?></th>
					<th>Week of <?php echo $end_account; ?></th>
					<th></th>
					<th></th>
					<th>Class</th>

				</tr>

				<tr>
					<th>Fees</th>
					<th></th>
					<th style="text-align: right;"><?php
// var_dump($final_data_table['fees']);
					 echo '$' . number_format($final_data_table['fees'],2); ?></th>
					<th>Week of <?php echo $end_account; ?></th>
					<th></th>
					<th></th>
					<th>Class</th>

				</tr>


				
			</thead>
			<tbody>
			<?php
			$csv_data_array=array();
			$i=2;
			foreach ($final_data_table['all_dates'] as $key => $value) {
				?>
			<tr>
			<td><?php echo $bank; ?></td>
			<td>$<?php echo $value;?></td>
			<td></td>
			<td><?php echo $key; ?></td>
			<td></td>
			<td></td>
			<td><?php echo $class; ?></td>
			
			</tr>
				<?php
				if($i==2)
				{

				$csv_data_array[0]['Accounts']='Accounts Receivable';
				$csv_data_array[0]['Debit']=" ";
				$csv_data_array[0]['Credit']='$' . number_format($final_data_table['total'],2);
				$csv_data_array[0]["MEMO"]="Week of ".$end_account;
					$csv_data_array[1]['Accounts']='Fees';
				$csv_data_array[1]['Debit']=" ";
				$csv_data_array[1]['Credit']='$' . number_format($final_data_table['fees'],2);
				$csv_data_array[1]["MEMO"]="Week of ".$end_account;
				}
				// Account, Debit, Credit, Memo
				$csv_data_array[$i]['Accounts']=$bank;
				$csv_data_array[$i]['Debit']='$' . number_format($value,2);
				$csv_data_array[$i]['Credit']="";
				$csv_data_array[$i]["MEMO"]=$key;
			$i++;
			}
			?>
			</tbody>
			</table>
			</div>
			</div>
			<?php

			reporst_csv("Deposit journal invoice.csv",$csv_data_array,"rental_report");




			restore_current_blog();

	
				

				}




			}////deposit close

	/*********************Open Inovoice report*************************/
			if($_POST['_accounting_report_id']=="Open Invoice Report")
			{
	include( plugin_dir_path( __FILE__ ) . 'open_invoice_report.php');


			}////openinvoice close


/********Rent Roll Report********/
	if($_POST['_accounting_report_id']=="Rent Roll Report")
			{

	include( plugin_dir_path( __FILE__ ) . 'rent_roll_report.php');

			}

/*********Rent Roll Report Close**************/



if($_POST['_accounting_report_id']=="Invoice Journal Entries")
{

	include( plugin_dir_path( __FILE__ ) . 'invoice_journal_entry.php');
}










/**************security deposit*******************/
if($_POST['_accounting_report_id']=="Security Deposit Report")
{

	include( plugin_dir_path( __FILE__ ) . 'security_deposit_report.php');

}

/**************Current tenant report*******************/
if($_POST['_accounting_report_id']=="Current Tenant Report")
{
	include( plugin_dir_path( __FILE__ ) . 'current_tenant_report.php');
}
		}

if(!isset($_REQUEST['accountingmk_submit']))
{

// }

		 ?>













		<div class="wrap">
		<h2 class="mkbis2">Accounting Reports</h2>
		<form action="" method="post" >
		<style type="text/css" media="screen">
			.mk_account_report_left label ,.mk_account_report_right label{
																			float: left;
																		    min-width: 100px;
																		}
			.mk_account_report_left select ,.mk_account_report_right select ,.mk_account_report_left input ,.mk_account_report_right input
			{
				min-width: 180px;
			}
			.mk_account_report_left input[type="submit"]{
				min-width: 100px;

			}
		</style>
		<div class="mk_account_report_left">
			
		<p>
			
		<label for="_accounting_report_id">Report</label>
		<select name="_accounting_report_id" id="_accounting_report_id">
			<option value="0">Select</option>
					<option value="Invoice Journal Entries">Income Journal Entries</option>
					<option value="Deposit Journal Entries">Deposit Journal Entries</option>
					<option value="Deposit ACH">Deposit Journal Entries(ACH)</option>


					<option value="Security Deposit Report">Security Deposit Report</option>
					<option value="Open Invoice Report">Open Invoice Report</option>
					<option value="Rent Roll Report">Rent Roll Report</option>
					<option value="Current Tenant Report">Current Tenant Report</option>
	
		</select>
		</p>
		<p>
			
		<label for="_accounting_building_id">Building</label>
		<select name="_accounting_building_id" id="_accounting_building_id">
			<option value="0">Select</option>

					<!-- <option value="1">yeagercommunity.com</option> -->
					<!-- <option value="6">template</option> -->
					<option value="all">All</option>
					<option value="9">Mckinney</option>
					<option value="10">Frisco</option>
					<option value="11">Ft. Harrison</option>
					<option value="12">Carmel</option>
					<option value="13">Fishers</option>
					<option value="14">II Fishers</option>
					<option value="15">Greenwood</option>
					<option value="16">Noblesville</option>
					<option value="17">Noblesville-Shoppes</option>
					<option value="18">Plainfield</option>
					<option value="24">Plano</option>

					<option value="4">Devsite</option>
					<!-- <option value="19">internal</option> -->
					<!-- <option value="20">help</option> -->
<!-- 				<option value="Fort Harrison">Fort Harrison</option>
					<option value="McKinney">McKinney</option>
					<option value="Frisco">Frisco</option>
					<option value="Carmel">Carmel</option>
					<option value="Fishers 1">Fishers 1</option>
					<option value="II Fishers">II Fishers</option>
					<option value="Greenwood">Greenwood</option>
					<option value="Noblesville 1">Noblesville 1</option>
					<option value="Noblesville 1">Noblesville 1</option>
					<option value="Plainfield">Plainfield</option> -->
	
		</select>
		</p>
		</div>
		<div class="mk_account_report_right">
		<p>
			

		<label for="_accounting_start_date">Start Date</label>
			
		<input type="text" name="_accounting_start_date" value="" class="datepicker _accounting_start_date" />
		</p>
		<p>
			

		<label for="_accounting_end_date">End Date</label>
			
		<input type="text" name="_accounting_end_date" value="" class="datepicker _accounting_end_date" />

		<input type="submit" name="accountingmk_submit" value="Run"/>
		</p>
		</div>
			
		</form>
</div>
<?php 

}
 ?>
		<?php

	}
add_action( 'wp_ajax_get_client_allinvoices', 'get_client_allinvoices_callback' );
add_action( 'wp_ajax_nopriv_get_client_allinvoices', 'get_client_allinvoices_callback' );




function get_client_allinvoices_callback()
{


			switch_to_blog($_REQUEST['building']);

 
			 $suite_id_mk= $_REQUEST['suite_id'];
			 $start_account= $_REQUEST['startdate'];
			 $end_account= $_REQUEST['enddate'];
				global $wpdb;
				global $post;

				$start_Account2=strtotime($start_account);
			 $end_Account2=strtotime($end_account);
					
			$all_data=array();
			// $paymentdatefull=explode(" ", $result->post_modified);		
			// $paymentdate=$paymentdatefull[0];	
			$suite_id=$suite_id_mk;
							 $yl_ms_args = array(
										        'post_type'   => 'lease',
										        'post_status'   => 'publish',
										        'numberposts'  => -1,
										        'meta_query' => array(
										          array(
										            'key' => '_yl_product_id',
										            'value'   => $suite_id,
										            'compare' => '='
										          )
										        )
										      );

							$lease_results = get_posts($yl_ms_args);
							$leases_ids=array();

							foreach ($lease_results as  $lease_result) {
								$in_ms_args = array(
										        'post_type'   => 'sa_invoice',
										        'post_status'   => 'any',
										        'numberposts'  => -1,
										        'meta_query' => array(
										          array(
										            'key' => '_yl_lease_id',
										            'value'   => $lease_result->ID,
										            'compare' => '='
										          )
										        )
										      );	
								array_push($leases_ids, $lease_result->ID);

								$invoice_results = get_posts($in_ms_args);
								$invoice_ids=array();
								if($invoice_results!="")
								{	
								foreach ($invoice_results as  $invoice_result) {
									$due_date=get_post_meta( $invoice_result->ID, '_due_date', true );
									if($due_date>=$start_Account2 && $due_date<=$end_Account2 )
									{
										/***Invoice Data*****/
													$invoice_id=$invoice_result->ID;
													$client_name=get_the_title(get_post_meta( $invoice_id, '_client_id', true ));
													// $company_id=yl_get_company_id_by_invoice_id($invoice_id);
													$leasd_id =	get_post_meta($invoice_id, '_yl_lease_id',true);
													$company_id=	get_post_meta($leasd_id, '_yl_company_name', true);
													$company_name=get_the_title( $company_id );
													$total=get_post_meta( $invoice_id, '_total', true );
													$client_id=get_post_meta( $invoice_id, '_client_id', true);
													$client_name=get_the_title(get_post_meta( $invoice_id, '_client_id', true ));
													$suite_number="";
													$leasd_id= get_post_meta( $invoice_id, '_yl_lease_id', true );
													$suite_number=get_post_meta($leasd_id, '_yl_suite_number', true );
													if($suite_number=='-1')
													{
													$suite_number="Y-Memberships";
													}
													$invoice_num='=HYPERLINK("'.get_permalink($invoice_id).'", "'.$invoice_id.'")';

													$invoice_ids[$invoice_id]['client_name']=$client_name;
													$invoice_ids[$invoice_id]['company_name']=$company_name;
													$invoice_ids[$invoice_id]['invoice_num']=$invoice_num;
													$invoice_ids[$invoice_id]['suite_number']=$suite_number;
													$invoice_ids[$invoice_id]['num']=$invoice_id;
													$invoice_ids[$invoice_id]['totalmk']=$total;
													$invoice_ids[$invoice_id]['date']=$paymentdate;
													$invoice_ids[$invoice_id]['line_items']=maybe_unserialize(get_post_meta($invoice_id, '_doc_line_items', true ));
													$full_total=(float) ($full_total+$total);

									}
										/********************/
									// echo "In range".$invoice_result->ID;
									// array_push($invoice_ids, $invoice_result->ID);
									# code...
									}
								}

							}
							// echo "<pre>";
							// print_r($lease_results);
							// echo "<pre>";
							$all_data[$suite_id]['leases']=$leases_ids;
							$all_data[$suite_id]['invoices']=$invoice_ids;

							$data='			<style type="text/css" media="screen">
							.mk_account_reports table{
							border-collapse: collapse;
							margin: 10px 0;
							}
							.mk_account_reports	th{
							font-weight: normal;
							border: 1px solid #D4D4D4;
							padding: 3px;
							text-align: center;
							}
							.mk_account_reports	td{
							border: 1px solid #D4D4D4;
							}
							</style>
							<div class="wrap">
							<h2 class="mkbis2">Rent Roll Reports</h2>
							<div class="mk_account_reports">

							<table cellpadding="0" cellspacing="0" border="0" width="100%">
							<thead>
							<tr>
							<th>  Suite Number     </th>
							<th>Tennant Name</th>
							<th>Rent</th>
							<th>Invoice Id</th>
							</tr>
							</thead> 
							<tbody>';

							foreach ($all_data as $suite_id => $main) {

							$leasd_id =	$main['leases'][0];
							$invoices_ids= $main['invoices'];
							if(empty($invoice_ids))
							{

							$data.='<tr>

							<td>'.get_the_title( $suite_id ).'</td>
							<td>0</td>
							<td>0</td>


							</tr>';

							}
							else
							{
							$tenament_name="";
							$aux_charges=0;
							$rent_charges=0;
							$monthlyrent=0;
							$suite_name_mk=get_the_title( $suite_id );
							foreach ($invoices_ids as $key => $value) {
							$tenament_name=$value['company_name'];;

							foreach ($value['line_items'] as $line => $lineitem) {
							if (strpos($lineitem['desc'], 'Rent') !== false ) {
							$rent_charges=(float) ($lineitem['total']);
							}
							else{
							$aux_charges=(float) ($aux_charges+$lineitem['total']);
							}
							}

							$data.='<tr>

							<td>'.get_the_title( $suite_id ).'</td>
							<td>'. $tenament_name.'</td>
							<td><a href="'.get_post_permalink($key).'">$'.$rent_charges.'</a></td>
							<td><a target="_blank" href="'.get_post_permalink($key).'">'.$key.'</a></td>
							</tr>';

							# code...
							}

							?>

							<?php

							}
							// echo "<pre>";
							// print_r($invoices_ids);
							// echo "</pre>";
							// $key=  														 
							// $suite_id=	get_post_meta($leasd_id, '_yl_product_id',true);
							// echo "<pre>";
							// print_r($value);
							// echo "</pre>";

							}	



							$data.='<style type="text/css" media="screen">
							.mk_account_reports table{
							border-collapse: collapse;
							margin: 10px 0;
							}
							.mk_account_reports	th{
							font-weight: normal;
							border: 1px solid #D4D4D4;
							padding: 3px;
							text-align: left;
							}
							.mk_account_reports	td{
							border: 1px solid #D4D4D4;
							}
							</style>


							</tbody>
							</table>
							</div>
							</div>';




			restore_current_blog();


			

echo $data;



wp_die();


	// wp_die();
}

add_action( 'wp_ajax_get_client_allinvoices_phone', 'get_client_allinvoices_phone_callback' );
add_action( 'wp_ajax_nopriv_get_client_phone_allinvoices', 'get_client_allinvoices_phone_callback' );

function get_client_allinvoices_phone_callback(){

	switch_to_blog($_REQUEST['building']);


			// global $wpdb;
			// global $post;
			// $start_account=$_POST['startdate'];
			// $start_acc=explode("-", $start_account);
			// $str_yr=$start_acc[0];
			// $str_mn=$start_acc[1];
			// $str_dy=$start_acc[2];
			// $end_account=$_POST['enddate'];
			// $end_acc=explode("-", $end_account);
			// $end_yr=$end_acc[0];
			// $end_mn=$end_acc[1];
			// $end_dy=$end_acc[2];

			$sites = wp_get_sites();

			switch_to_blog($building);


			global $wpdb;
			global $post;
			$start_account=$_REQUEST['startdate'];
			$start_acc=explode("-", $start_account);
			$str_yr=$start_acc[0];
			$str_mn=$start_acc[1];
			$str_dy=$start_acc[2];
			$end_account=$_REQUEST['enddate'];
			// echo "enddate";
			// echo "<br>";
			$end_acc=explode("-", $end_account);
			$end_yr=$end_acc[0];
			$end_mn=$end_acc[1];
			$end_dy=$end_acc[2];
			// $args = array(
			// 	'post_type' => 'sa_invoice',
			// 	'post_status' => array('publish,partial,complete,write-off'),
			// 		'date_query' => array(
			// 			array(
			// 				'column' => 'post_modified',
			// 				'after'     => array(
			// 					'year'  => $str_yr,
			// 					'month' => $str_mn,
			// 					'day'   => $str_dy,
			// 				),
			// 				'before'    => array(
			// 					'year'  => $end_yr,
			// 					'month' => $end_mn,
			// 					'day'   => $end_dy,
			// 				),
			// 				'inclusive' => true,
			// 			),
			// 		),
			// 	'posts_per_page' => -1,
			// 	);

														// 				$args = array(
														//     'post_type' => 'sa_invoice',
														//      'posts_per_page' => -1,
														//     'post_status' =>  array('complete','publish','partial'),
														//      // 'orderby' => 'meta_value',
														//  	'order' => 'ASC',
														//     'meta_query' => array(
														// 				'relation' => 'AND',
														// 		    	    array(
														//     	    			    'key' => '_due_date',
														// 				            'value' => $start_account,
														// 			                'compare' => '>=',
														// 						    'type'    => 'Date'
														// 			  			 ),
														// 		    	    	array(
														//     	    			    'key' => '_due_date',
														// 				            'value' => $end_account,
														// 			                'compare' => '<=',
														// 						    'type'    => 'Date'
														// 			  			 ),
												
														// 					 )
														// );


// 																			          		$args = array(
// 						    'post_type' => 'sa_invoice',
// 						    'posts_per_page' => -1,
// //						    'post_status' =>  array('draft', 'publish'),
// 						    'post_status' => "any",
// 						    'orderby' => 'meta_value',
// 						    'order' => 'ASC',
// 						    'meta_query' => array(


// 						 				'relation' => 'AND',
// 						    	    array(

// 									            'key' => '_due_date',
// 									            'value' => "2016-05-31",
// 									            'compare' => '>=',
// 									            'type'    => 'Date'
// 									            ),


// 						    	     	 array(
// 						     				    'key' => '_due_date',
// 									            'value' => "2016-06-30",
// 									            'compare' => '<=',
// 									            'type'    => 'Date'

// 						    	    	),
// 						    	     	 ),
						    	     	     	 
								       
// 						    // )
// 								);	
			$start_Account2=strtotime($start_account);
		$end_Account2=strtotime($end_account);
	$args = array(
    'post_type' => 'sa_invoice',
    'post_status' => 'any',
    'posts_per_page' => -1,
    'orderby' => 'meta_value',
    'order' => 'ASC',
    'meta_key' => '_due_date',
    'meta_query' => array(
        array(
            'key' => '_due_date',
            'value' => array($start_Account2, $end_Account2),
            'compare' => 'BETWEEN',
            'type' => 'Numeric'
        )
    )
);
														// $invoice_id=array();
							// 							$loop = new WP_Query($args);

							// while ( $loop->have_posts() ) : $loop->the_post();
							// 							// echo "checkpoint3";
							// 			the_title();


														// endwhile;
				// $results = get_posts($args);



$results = get_posts($args);

// echo "<pre>";
// print_r($results);
// echo "</pre>";




foreach ($results as $key => $result) {
			$paymentdatefull=explode(" ", $result->post_modified);		
			$paymentdate=$paymentdatefull[0];	
			$invoice_id=$result->ID;



			// $invoice_id_mk=$result->ID;
// $all_data="";


			$all_data[$invoice_id]['date']=$paymentdate;
			$all_data[$invoice_id]['line_items']=maybe_unserialize(get_post_meta($invoice_id, '_doc_line_items', true ));
		/*	echo "<pre>";
print_r($all_data );
echo "</pre>";*/
			$total=get_post_meta($invoice_id, '_total', true );
			$credit_fees==get_post_meta($invoice_id, '_doc_tax2', true );
			if($credit_fees==""){ $credit_fees=0; }

			$total_credit_fee=($total*$credit_fees)/100;
			$full_total=(float) ($full_total+$total);


		

			//$invoice_id_mk=$result->ID;

}


						$data='			<style type="text/css" media="screen">
							.mk_account_reports table{
							border-collapse: collapse;
							margin: 10px 0;
							}
							.mk_account_reports	th{
							font-weight: normal;
							border: 1px solid #D4D4D4;
							padding: 3px;
							text-align: center;
							}
							.mk_account_reports	td{
							border: 1px solid #D4D4D4;
							}
							</style>
							<div class="wrap">
							<h2 class="mkbis2">Rent Roll Reports</h2>
							<div class="mk_account_reports">

							<table cellpadding="0" cellspacing="0" border="0" width="100%">
							<thead>
							<tr>
							<th>Tenant Name</th>
							<th>Description</th>
							<th>Value</th>
							<th>Invoice Id</th>
							</tr>
							</thead> 
							<tbody>';

	foreach ($all_data as $key => $value) {
				$mainurl=home_url();
$credit_check=1;
               foreach ($value['line_items'] as  $lineitem) {

    
            $client_name=get_the_title(get_post_meta($key, '_client_id', true ));
			


         if($_REQUEST['check'] =="Phone")
{      	

	
			
                  if (strpos($lineitem['desc'], 'IP Static') !== false) {	
					$data .= "<tr><td>".$client_name."</td><td>".$lineitem['desc']."</td><td>$".number_format($lineitem['total'],2)."</td><td><a target='_blank' href='".get_post_permalink($key)."'>".$key."</a></td></tr>";								
				}
                elseif (strpos($lineitem['desc'], 'Phone') !== false) {					
					$data .= "<tr><td>".$client_name."</td><td>".$lineitem['desc']."</td><td>$".number_format($lineitem['total'],2)."</td><td><a target='_blank' href='".get_post_permalink($key)."'>".$key."</a></td></tr>";		
                }
                elseif (strpos($lineitem['desc'], 'Cable')!== false) {					
				$data .= "<tr><td>".$client_name."</td><td>".$lineitem['desc']."</td><td>$".number_format($lineitem['total'],2)."</td><td><a target='_blank' href='".get_post_permalink($key)."'>".$key."</a></td></tr>";
				}

				elseif (strpos($lineitem['desc'], 'Credit Card Line') !== false) {
		     	$data .= "<tr><td>".$client_name."</td><td>".$lineitem['desc']."</td><td>$".number_format($lineitem['total'],2)."</td><td><a target='_blank' href='".get_post_permalink($key)."'>".$key."</a></td></tr>";				
					
				}
				elseif (strpos($lineitem['desc'], 'Ip Service')  !== false) {					
				$data .= "<tr><td>".$client_name."</td><td>".$lineitem['desc']."</td><td>$".number_format($lineitem['total'],2)."</td><td><a target='_blank' href='".get_post_permalink($key)."'>".$key."</a></td></tr>";	

                 }
	            elseif (strpos($lineitem['desc'], 'Fax Service') !== false) {					
				$data .= "<tr><td>".$client_name."</td><td>".$lineitem['desc']."</td><td>$".number_format($lineitem['total'],2)."</td><td><a target='_blank' href='".get_post_permalink($key)."'>".$key."</a></td></tr>";
			   }

 

}


		
         if($_REQUEST['check'] =="Tenant Improvement")
{    

					if (strpos($lineitem['desc'], 'Workorder') !== false) {
					$data .= "<tr><td>".$client_name."</td><td>".$lineitem['desc']."</td><td>$".number_format($lineitem['total'],2)."</td><td><a target='_blank' href='".get_post_permalink($key)."'>".$key."</a></td></tr>";

					
					}

					elseif (strpos($lineitem['desc'], 'Work Order') !== false) {
				$data .= "<tr><td>".$client_name."</td><td>".$lineitem['desc']."</td><td>$".number_format($lineitem['total'],2)."</td><td><a target='_blank' href='".get_post_permalink($key)."'>".$key."</a></td></tr>";

					
					}

					elseif (strpos($lineitem['desc'], 'Plumbing Work Order') !== false) {
					$data .= "<tr><td>".$client_name."</td><td>".$lineitem['desc']."</td><td>$".number_format($lineitem['total'],2)."</td><td><a target='_blank' href='".get_post_permalink($key)."'>".$key."</a></td></tr>";

					
					}
										elseif (strpos($lineitem['desc'], 'Tenant Improvements') !== false) {
					$data .= "<tr><td>".$client_name."</td><td>".$lineitem['desc']."</td><td>$".number_format($lineitem['total'],2)."</td><td><a target='_blank' href='".get_post_permalink($key)."'>".$key."</a></td></tr>";

					
					}


										elseif (strpos($lineitem['desc'], 'Overpayment from') !== false) {
					$data .= "<tr><td>".$client_name."</td><td>".$lineitem['desc']."</td><td>$".number_format($lineitem['total'],2)."</td><td><a target='_blank' href='".get_post_permalink($key)."'>".$key."</a></td></tr>";

					
					}
										elseif (strpos($lineitem['desc'], 'Refreshing Fee') !== false) {
					$data .= "<tr><td>".$client_name."</td><td>".$lineitem['desc']."</td><td>$".number_format($lineitem['total'],2)."</td><td><a target='_blank' href='".get_post_permalink($key)."'>".$key."</a></td></tr>";

					
					}

										elseif (strpos($lineitem['desc'], 'Keys') !== false) {
					$data .= "<tr><td>".$client_name."</td><td>".$lineitem['desc']."</td><td>$".number_format($lineitem['total'],2)."</td><td><a target='_blank' href='".get_post_permalink($key)."'>".$key."</a></td></tr>";

					
					}
					elseif (strpos($lineitem['desc'], 'Key') !== false) {
					// $final_data_table['all_dates']['Tenant Improvement']=$final_data_table['all_dates']['Tenant Improvement']+$lineitem['total'];
					// $final_total3=$final_total3+$lineitem['total'];
					$data .= "<tr><td>".$client_name."</td><td>".$lineitem['desc']."</td><td>$".number_format($lineitem['total'],2)."</td><td><a target='_blank' href='".get_post_permalink($key)."'>".$key."</a></td></tr>";
					
					}
					}








         if($_REQUEST['check'] =="Long Distance")
{      	
		



		if (strpos($lineitem['desc'], 'Long Distance') !== false) {
					$data .= "<tr><td>".$client_name."</td><td>".$lineitem['desc']."</td><td>$".number_format($lineitem['total'],2)."</td><td><a target='_blank' href='".get_post_permalink($key)."'>".$key."</a></td></tr>";
					// $final_data_table['all_dates']['Long Distance']=$final_data_table['all_dates']['Long Distance']+$lineitem['total'];
					// $final_total3=$final_total3+$lineitem['total'];
					
					}
}

if($_REQUEST['check'] =="Discounts")
{

if (strpos($lineitem['desc'], 'Discount') !== false) {

						$data .= "<tr><td>".$client_name."</td><td>".$lineitem['desc']."</td><td>$".number_format($lineitem['total'],2)."</td><td><a target='_blank' href='".get_post_permalink($key)."'>".$key."</a></td></tr>";
					
	}

}

if($_REQUEST['check'] =="Postage")
{

	 if (strpos($lineitem['desc'], 'Postage') !== false) {
					$data .= "<tr><td>".$client_name."</td><td>".$lineitem['desc']."</td><td>$".number_format($lineitem['total'],2)."</td><td><a target='_blank' href='".get_post_permalink($key)."'>".$key."</a></td></tr>";
					
					}


}
if($_REQUEST['check'] =="Fax")
{
  if (strpos($lineitem['desc'], 'Fax Fees') !== false) {
						$data .= "<tr><td>".$client_name."</td><td>".$lineitem['desc']."</td><td>$".number_format($lineitem['total'],2)."</td><td><a target='_blank' href='".get_post_permalink($key)."'>".$key."</a></td></tr>";
					// $final_data_table['all_dates']['Fax']=$final_data_table['all_dates']['Fax']+$lineitem['total'];	
					// $final_total3=$final_total3+$lineitem['total'];
					
					}
}
if($_REQUEST['check'] =="Rent")
{



	 if (strpos($lineitem['desc'], 'Rent') !== false) {
				$data .= "<tr><td>".$client_name."</td><td>".$lineitem['desc']."</td><td>$".number_format($lineitem['total'],2)."</td><td><a target='_blank' href='".get_post_permalink($key)."'>".$key."</a></td></tr>";
		}
		if (strpos($lineitem['desc'], 'prorated moving') !== false) {
					$data .= "<tr><td>".$client_name."</td><td>".$lineitem['desc']."</td><td>$".number_format($lineitem['total'],2)."</td><td><a target='_blank' href='".get_post_permalink($key)."'>".$key."</a></td></tr>";
			}
 }
if($_REQUEST['check'] =="Sec. Deposit")
{
	 if (strpos($lineitem['desc'], 'Security Deposit') !== false) {
        	$data .= "<tr><td>".$client_name."</td><td>".$lineitem['desc']."</td><td>$".number_format($lineitem['total'],2)."</td><td><a target='_blank' href='".get_post_permalink($key)."'>".$key."</a></td></tr>";		
		}
}
if($_REQUEST['check'] =="Fees")
{
// 			$total=get_post_meta($key, '_total', true );
// 			$credit_fees==get_post_meta($key, '_doc_tax2', true );
// 			if($credit_fees==""){ $credit_fees=0; }
// 			$total_credit_fee=($total*$credit_fees)/100;
// 			if ( $credit_check == 1 ) {

// 					  $data .= "<tr><td>Total Credit fees</td><td>".$total_credit_fee."</td><td><a target='_blank' href='".get_post_permalink($key)."'>".$key."</a></td></tr>";		
// $credit_check++;
// }
					if (strpos($lineitem['desc'], 'NSF Fee') !== false) {
					// $final_data_table['all_dates']['Fees']=$final_data_table['all_dates']['Fees']+$lineitem['total'];		
					// $final_total3=$final_total3+$lineitem['total'];
					  	$data .= "<tr><td>".$client_name."</td><td>".$lineitem['desc']."</td><td>$".number_format($lineitem['total'],2)."</td><td><a target='_blank' href='".get_post_permalink($key)."'>".$key."</a></td></tr>";	

					
					}
					// elseif (strpos($lineitem['desc'], 'Fees') !== false) {
					// // $final_data_table['all_dates']['Fees']=$final_data_table['all_dates']['Fees']+$lineitem['total'];		
					// // $final_total3=$final_total3+$lineitem['total'];
					//   $data .= "<tr><td>".$lineitem['desc']."</td><td>".$lineitem['total']."</td><td><a target='_blank' href='".get_post_permalink($key)."'>".$key."</a></td></tr>";		
					
					// }

					elseif (strpos($lineitem['desc'], 'Convenience Fee') !== false) {
					// $final_data_table['all_dates']['Fees']=$final_data_table['all_dates']['Fees']+$lineitem['total'];		
					// $final_total3=$final_total3+$lineitem['total'];
					  	$data .= "<tr><td>".$client_name."</td><td>".$lineitem['desc']."</td><td>$".number_format($lineitem['total'],2)."</td><td><a target='_blank' href='".get_post_permalink($key)."'>".$key."</a></td></tr>";		
					
					}


					elseif (strpos($lineitem['desc'], 'April Payment Convienence Fee') !== false) {
					// $final_data_table['all_dates']['Fees']=$final_data_table['all_dates']['Fees']+$lineitem['total'];		
					// $final_total3=$final_total3+$lineitem['total'];
					  	$data .= "<tr><td>".$client_name."</td><td>".$lineitem['desc']."</td><td>$".number_format($lineitem['total'],2)."</td><td><a target='_blank' href='".get_post_permalink($key)."'>".$key."</a></td></tr>";	
					
					}


					elseif (strpos($lineitem['desc'], 'Late Fee') !== false) {
					// $final_data_table['all_dates']['Fees']=$final_data_table['all_dates']['Fees']+$lineitem['total'];
					// $final_total3=$final_total3+$lineitem['total'];
					 	$data .= "<tr><td>".$client_name."</td><td>".$lineitem['desc']."</td><td>$".number_format($lineitem['total'],2)."</td><td><a target='_blank' href='".get_post_permalink($key)."'>".$key."</a></td></tr>";
					} 

					elseif (strpos($lineitem['desc'], 'Ancillaries') !== false) {
					// $final_data_table['all_dates']['Fees']=$final_data_table['all_dates']['Fees']+$lineitem['total'];
					// $final_total3=$final_total3+$lineitem['total'];
					 	$data .= "<tr><td>".$client_name."</td><td>".$lineitem['desc']."</td><td>$".number_format($lineitem['total'],2)."</td><td><a target='_blank' href='".get_post_permalink($key)."'>".$key."</a></td></tr>";	
					
					} 


					elseif (strpos($lineitem['desc'], 'Cleaning Service Fee') !== false) {
					// $final_data_table['all_dates']['Fees']=$final_data_table['all_dates']['Fees']+$lineitem['total'];
					// $final_total3=$final_total3+$lineitem['total'];
					  	$data .= "<tr><td>".$client_name."</td><td>".$lineitem['desc']."</td><td>$".number_format($lineitem['total'],2)."</td><td><a target='_blank' href='".get_post_permalink($key)."'>".$key."</a></td></tr>";	
					
					} 


				// if (strpos($lineitem['desc'], 'Late Fee') !== false) {
				// 	  $data .= "<tr><td>".$lineitem['desc']."</td><td>".$lineitem['total']."</td><td><a target='_blank' href='".get_post_permalink($key)."'>".$key."</a></td></tr>";		
					
				// 	} 

				// 	elseif (strpos($lineitem['desc'], 'Ancillaries') !== false) {
				// 	  $data .= "<tr><td>".$lineitem['desc']."</td><td>".$lineitem['total']."</td><td><a target='_blank' href='".get_post_permalink($key)."'>".$key."</a></td></tr>";		
					
				// 	} 


				// 	elseif (strpos($lineitem['desc'], 'Cleaning Service Fee') !== false) {
				//   $data .= "<tr><td>".$lineitem['desc']."</td><td>".$lineitem['total']."</td><td><a target='_blank' href='".get_post_permalink($key)."'>".$key."</a></td></tr>";		
					
				// 	} 
				//     elseif (strpos($lineitem['desc'], 'IP Service Fees') !== false) {
				// 	  $data .= "<tr><td>".$lineitem['desc']."</td><td>".$lineitem['total']."</td><td><a target='_blank' href='".get_post_permalink($key)."'>".$key."</a></td></tr>";		
					
				// 	}


				//     elseif (strpos($lineitem['desc'], 'Service Fees') !== false) {
				//   $data .= "<tr><td>".$lineitem['desc']."</td><td>".$lineitem['total']."</td><td><a target='_blank' href='".get_post_permalink($key)."'>".$key."</a></td></tr>";		
					
				// 	}
				//     elseif (strpos($lineitem['desc'], 'Fax Fees') !== false) {
				//   $data .= "<tr><td>".$lineitem['desc']."</td><td>".$lineitem['total']."</td><td><a target='_blank' href='".get_post_permalink($key)."'>".$key."</a></td></tr>";
				//   }						
}
if($_REQUEST['check'] =="Copies")
{
		if (strpos($lineitem['desc'], 'Copier') !== false) {
						$data .= "<tr><td>".$client_name."</td><td>".$lineitem['desc']."</td><td>$".number_format($lineitem['total'],2)."</td><td><a target='_blank' href='".get_post_permalink($key)."'>".$key."</a></td></tr>";

					}
					elseif (strpos($lineitem['desc'], 'Copies') !== false) {
						$data .= "<tr><td>".$client_name."</td><td>".$lineitem['desc']."</td><td>$".number_format($lineitem['total'],2)."</td><td><a target='_blank' href='".get_post_permalink($key)."'>".$key."</a></td></tr>";

					}
}
if($_REQUEST['check'] =="Tenant Improvement")
{
		if (strpos($lineitem['desc'], 'Keys') !== false) {
        	$data .= "<tr><td>".$client_name."</td><td>".$lineitem['desc']."</td><td>$".number_format($lineitem['total'],2)."</td><td><a target='_blank' href='".get_post_permalink($key)."'>".$key."</a></td></tr>";	
					
					}
					elseif (strpos($lineitem['desc'], 'Key') !== false) {
				        	$data .= "<tr><td>".$client_name."</td><td>".$lineitem['desc']."</td><td>$".number_format($lineitem['total'],2)."</td><td><a target='_blank' href='".get_post_permalink($key)."'>".$key."</a></td></tr>";		
					
					}
					elseif (strpos($lineitem['desc'], 'Refreshing Fee') !== false) {
					        	$data .= "<tr><td>".$client_name."</td><td>".$lineitem['desc']."</td><td>$".number_format($lineitem['total'],2)."</td><td><a target='_blank' href='".get_post_permalink($key)."'>".$key."</a></td></tr>";		
					
					}
					elseif (strpos($lineitem['desc'], 'Workorder') !== false) {
					         	$data .= "<tr><td>".$client_name."</td><td>".$lineitem['desc']."</td><td>$".number_format($lineitem['total'],2)."</td><td><a target='_blank' href='".get_post_permalink($key)."'>".$key."</a></td></tr>";		
					
					}

					elseif (strpos($lineitem['desc'], 'Work Order') !== false) {
					        	$data .= "<tr><td>".$client_name."</td><td>".$lineitem['desc']."</td><td>$".number_format($lineitem['total'],2)."</td><td><a target='_blank' href='".get_post_permalink($key)."'>".$key."</a></td></tr>";	
					
					}

					elseif (strpos($lineitem['desc'], 'Plumbing Work Order') !== false) {
				     	$data .= "<tr><td>".$client_name."</td><td>".$lineitem['desc']."</td><td>$".number_format($lineitem['total'],2)."</td><td><a target='_blank' href='".get_post_permalink($key)."'>".$key."</a></td></tr>";	
					}					
}

if($_REQUEST['check'] =="Utilities")
{
				    if (strpos($lineitem['desc'], 'Service Fees') !== false) {
						$data .= "<tr><td>".$client_name."</td><td>".$lineitem['desc']."</td><td>$".number_format($lineitem['total'],2)."</td><td><a target='_blank' href='".get_post_permalink($key)."'>".$key."</a></td></tr>";	
					
					}


					 elseif (strpos($lineitem['desc'], 'Utility Service') !== false) {
						$data .= "<tr><td>".$client_name."</td><td>".$lineitem['desc']."</td><td>$".number_format($lineitem['total'],2)."</td><td><a target='_blank' href='".get_post_permalink($key)."'>".$key."</a></td></tr>";
					
					}
					elseif (strpos($lineitem['desc'], 'Utilities') !== false) {
					$data .= "<tr><td>".$client_name."</td><td>".$lineitem['desc']."</td><td>$".number_format($lineitem['total'],2)."</td><td><a target='_blank' href='".get_post_permalink($key)."'>".$key."</a></td></tr>";
					
					}

					elseif (strpos($lineitem['desc'], 'Water Utliity Fee') !== false) {
						$data .= "<tr><td>".$client_name."</td><td>".$lineitem['desc']."</td><td>$".number_format($lineitem['total'],2)."</td><td><a target='_blank' href='".get_post_permalink($key)."'>".$key."</a></td></tr>";
					
					}

					elseif (strpos($lineitem['desc'], 'Overpayment from') !== false) {
						$data .= "<tr><td>".$client_name."</td><td>".$lineitem['desc']."</td><td>$".number_format($lineitem['total'],2)."</td><td><a target='_blank' href='".get_post_permalink($key)."'>".$key."</a></td></tr>";	
					
					}
}


}
}


echo $data .= "</tbody></table>";



wp_die( );
}
add_action( 'wp_ajax_get_client_allinvoices_multi', 'get_client_allinvoices_multi_callback' );
add_action( 'wp_ajax_nopriv_get_client_allinvoices_multi', 'get_client_allinvoices_multi_callback' );







function get_client_allinvoices_multi_callback()
{
			switch_to_blog($_REQUEST['building']);

			 $suite_id_mk= $_REQUEST['suite_id'];
			 $title=get_the_title( $suite_id_mk );
			 $invoice_id_mk= $_REQUEST['invoice_id'];
			 $start_account= $_REQUEST['startdate'];
			 $end_account= $_REQUEST['enddate'];
				global $wpdb;
				global $post;

				$start_Account2=strtotime($start_account);
			 $end_Account2=strtotime($end_account);
					
			$all_data=array();


								// echo $value_m->ID;
								if(get_post_meta( $invoice_id_mk, '_yl_lease_id', true )=="")
								{
									array_push($multisuites_ids, $invoice_id_mk);
									$line_items=maybe_unserialize(get_post_meta($invoice_id_mk, '_doc_line_items', true ));
									// echo "<pre>";
									// print_r($line_items);
									// echo "</pre>";
									$multisuite_rent=0;
									$multisuite_aux=0;
									$count=1;
									$count_lineitem=count($line_items);
									foreach ($line_items as $l_key => $l_value) {

										if (strpos($l_value['desc'], $title) !== false ) {
										$multisuite_alldata[$suite_id_mk]['rent']=$l_value['total'];
										$multisuite_alldata[$suite_id_mk]['aux']=$multisuite_aux;
										$multisuite_alldata[$suite_id_mk]['invoice_id']=$invoice_id_mk;
										$client_id=get_post_meta( $invoice_id_mk, '_client_id', true);
										$client_name=get_the_title(get_post_meta( $invoice_id_mk, '_client_id', true ));
										$multisuite_alldata[$suite_id_mk]['client_name']=$client_name;
										$multisuite_aux=0;
										// echo "<br>";
										}
										else{
										$multisuite_aux=(float) ($multisuite_aux+$l_value['total']);
										}


										// if($count==$count_lineitem)
										// {
										// 	// echo $count;
										// 	// echo "<br>";
										// 	// echo $count_lineitem;
										// 	end($m_suite_ids_array);         // move the internal pointer to the end of the array
										// 	$key = key($m_suite_ids_array);
										// 	$last_suite_id=$m_suite_ids_array[$key];
										// 	//echo "Key";
										// 	//echo $key;
										// 	$multisuite_alldata[$last_suite_id]['aux']=(float) ($multisuite_alldata[$last_suite_id]['aux']+$multisuite_aux);
										// }

										// $count++;
									}

									// echo "<br>";
								}


							$data='			<style type="text/css" media="screen">
							.mk_account_reports table{
							border-collapse: collapse;
							margin: 10px 0;
							}
							.mk_account_reports	th{
							font-weight: normal;
							border: 1px solid #D4D4D4;
							padding: 3px;
							text-align: center;
							}
							.mk_account_reports	td{
							border: 1px solid #D4D4D4;
							}
							</style>
							<div class="wrap">
							<h2 class="mkbis2">Rent Roll Reports</h2>
							<div class="mk_account_reports">

							<table cellpadding="0" cellspacing="0" border="0" width="100%">
							<thead>
							<tr>
							<th>  Suite Number     </th>
							<th>Tennant Name</th>
							<th>Rent</th>
							<th>Invoice Id</th>
							</tr>
							</thead> 
							<tbody>';

							$data.='<tr>

							<td>'.get_the_title( $suite_id_mk ).'</td>
							<td>'. $multisuite_alldata[$suite_id_mk]["client_name"].'</td>
							<td><a href="'.get_post_permalink($invoice_id_mk).'">$'.$multisuite_alldata[$suite_id_mk]["rent"].'</a></td>
							<td><a target="_blank" href="'.get_post_permalink($invoice_id_mk).'">'.$invoice_id_mk.'</a></td>
							</tr>';
							echo $data;

							wp_die();

							# code...
							}



add_action( 'wp_ajax_get_client_allauxrent_multisuite', 'get_client_allauxrent_multisuite_callback' );
add_action( 'wp_ajax_nopriv_get_client_allauxrent_multisuite', 'get_client_allauxrent_multisuite_callback' );






function get_client_allauxrent_multisuite_callback()
{
			switch_to_blog($_REQUEST['building']);

		     $suite_id_mk= $_REQUEST['suite_id'];
			 $title=get_the_title( $suite_id_mk );
			 $invoice_id_mk= $_REQUEST['invoice_id'];
			 $start_account= $_REQUEST['startdate'];
			 $end_account= $_REQUEST['enddate'];
			 $auxshow= $_REQUEST['auxshow'];
     		 global $wpdb;
			 global $post;
			 $start_Account2=strtotime($start_account);
			 $end_Account2=strtotime($end_account);
					
				$all_data=array();
				$aux_array=array();

							$m_invoice_ids=array();
							$m_suite_ids_array=array();
							$m_suite_ids_array_chunk=array();
							$multisuite_alldata=array();
							$multisuite_alldata_chunk=array();
								// echo $value_m->ID;
								// if(get_post_meta( $invoice_id_mk, '_yl_lease_id', true )=="")
								// {


									array_push($multisuites_ids, $invoice_iD_mk);
									$line_items=maybe_unserialize(get_post_meta($invoice_id_mk, '_doc_line_items', true ));
									// echo "<pre>";
									// print_r($line_items);
									// echo "</pre>";
									$multisuite_rent=0;
									$multisuite_aux=0;
									$count=1;
									$count_lineitem=count($line_items);
									 $client_id=get_post_meta( $invoice_id_mk, '_client_id', true);
									$client_name=get_the_title($client_id);
									$multisuite_alldata_chunk[$suite_id_mk]['client_name']=$client_name;

									$line_item_array_chunks=array();
									$aux_changer=array();
									foreach ($line_items as $l_key => $l_value) {
										if (strpos($l_value['desc'], 'Monthly Rent for ') !== false ) {
										$month_explode=explode('Monthly Rent for ', strip_tags($l_value['desc']));
										// echo "Suite title=".$month_explode[1];
										// echo "<br>";
										// var_dump($month_explode[1]);
										$m_suite_idarr=get_page_by_title($month_explode[1], 'ARRAY_A', 'suites' );	
										$m_suite_id=$m_suite_idarr['ID'];
										
										// echo "Multisuite id=".$m_suite_id;
										// echo "<br>";
									// echo '<a href="'.get_post_permalink( $invoice_iD_mk ).'" title="">'.$month_explode[1].'---->'.$invoice_iD_mk.'</a>' ;
									// echo "<br>";
										array_push($m_suite_ids_array_chunk, $m_suite_id);
													// echo "<pre>";
													// echo "Suites Array";
													// print_r($m_suite_ids_array);
													// echo "<pre>";
										// array_push(array, var)
										$multisuite_alldata_chunk[$m_suite_id]['rent']=$l_value['total'];
										// $line_item_array_chunks[$m_suite_id]['aux']=$aux_changer;
										$multisuite_alldata_chunk[$m_suite_id]['invoice_id']=$invoice_iD_mk;
										// $multisuite_aux=0;
										// echo "<br>";
										}
										elseif (strpos($l_value['desc'], 'Multi Suite Discount') !== false ) {
											// $aux_changer=$aux_changer+$l_value['total'];
											$aux_changer[$count]['desc']=$l_value['desc'];
											$aux_changer[$count]['total']=$l_value['total'];

											end($m_suite_ids_array_chunk);         // move the internal pointer to the end of the array
											$key = key($m_suite_ids_array_chunk);
											$last_suite_id=$m_suite_ids_array_chunk[$key];
										$multisuite_alldata_chunk[$last_suite_id]['aux']=$aux_changer;

										$aux_changer=array();

										}

										elseif($count==$count_lineitem)
										{
											// echo $count;
											// echo "<br>";
											// echo $count_lineitem;
											end($m_suite_ids_array_chunk);         // move the internal pointer to the end of the array
											$key = key($m_suite_ids_array_chunk);
											$last_suite_id=$m_suite_ids_array_chunk[$key];

										$aux_changer[$count]['desc']=$l_value['desc'];
										$aux_changer[$count]['total']=$l_value['total'];
											// $aux_changer
											//echo "Key";
											//echo $key;
											$multisuite_alldata_chunk[$last_suite_id]['aux']=$aux_changer;
										}
										else{
										$aux_changer[$count]['desc']=$l_value['desc'];
										$aux_changer[$count]['total']=$l_value['total'];
										}


										



										$count++;
									}

									// echo "<pre>";
									// print_r($line_item_array_chunks);
									// echo "</pre>";

									// echo "<br>";
								



								// }
// echo "<pre>";
// print_r($aux_array);
// echo "</pre>";
// exit();
							$data='<style type="text/css" media="screen">
							.mk_account_reports table{
							border-collapse: collapse;
							margin: 10px 0;
							}
							.mk_account_reports	th{
							font-weight: normal;
							border: 1px solid #D4D4D4;
							padding: 3px;
							text-align: center;
							}
							.mk_account_reports	td{
							border: 1px solid #D4D4D4;
							}
							</style>
							<div class="wrap">
							<h2 class="mkbis2">Rent Roll Reports</h2>
							<div class="mk_account_reports">

							<table cellpadding="0" cellspacing="0" border="0" width="100%">
							<thead>
							<tr>
							<th>  Suite Number     </th>
							<th>Tennant Name</th>
							<th>Description</th>
							<th>Ammount</th>
							<th>Invoice Id</th>
							</tr>
							</thead> 
							<tbody>';
							// if ($auxshow=="show") {
								# code...

							foreach ($multisuite_alldata_chunk as $suite_id => $value1) {

							if($suite_id==$suite_id_mk)
							{
								foreach ($value1['aux'] as $aaa => $auxar) {
									// print_r($auxar);
									# code...
								$data.='<tr>

								<td>'.get_the_title( $suite_id_mk ).'</td>
								<td>'. $multisuite_alldata_chunk[$suite_id_mk]["client_name"].'</td>
								<td>'.$auxar["desc"].'</td>
								<td>'.$auxar["total"].'</td>
								<td><a target="_blank" href="'.get_post_permalink($invoice_id_mk).'">'.$invoice_id_mk.'</a></td>
								</tr>';
								}
							# code...
							}
							

							}
					
							// }
							// echo "<pre>";
							// print_r($multisuite_alldata_chunk);
							// echo "</pre>";
							echo $data;

							wp_die();

							# code...
							}













add_action( 'wp_ajax_get_client_allauxrent', 'get_client_allauxrent_callback' );
add_action( 'wp_ajax_nopriv_get_client_allauxrent', 'get_client_allauxrent_callback' );


function get_client_allauxrent_callback()
{


			switch_to_blog($_REQUEST['building']);

 
			 $suite_id_mk= $_REQUEST['suite_id'];
			 $start_account= $_REQUEST['startdate'];
			 $end_account= $_REQUEST['enddate'];
				global $wpdb;
				global $post;

			$start_Account2=strtotime($start_account);
			$end_Account2=strtotime($end_account);
					
			$all_data=array();
	
			$suite_id=$suite_id_mk;
							 $yl_ms_args = array(
										        'post_type'   => 'lease',
										        'post_status'   => 'publish',
										        'numberposts'  => -1,
										        'meta_query' => array(
										          array(
										            'key' => '_yl_product_id',
										            'value'   => $suite_id,
										            'compare' => '='
										          )
										        )
										      );

							$lease_results = get_posts($yl_ms_args);
							$leases_ids=array();

							foreach ($lease_results as  $lease_result) {
								$in_ms_args = array(
										        'post_type'   => 'sa_invoice',
										        'post_status'   => 'any',
										        'numberposts'  => -1,
										        'meta_query' => array(
										          array(
										            'key' => '_yl_lease_id',
										            'value'   => $lease_result->ID,
										            'compare' => '='
										          )
										        )
										      );	
								array_push($leases_ids, $lease_result->ID);

								$invoice_results = get_posts($in_ms_args);
								$invoice_ids=array();
								if($invoice_results!="")
								{	
								foreach ($invoice_results as  $invoice_result) {
									$due_date=get_post_meta( $invoice_result->ID, '_due_date', true );
									if($due_date>=$start_Account2 && $due_date<=$end_Account2 )
									{
										/***Invoice Data*****/
													$invoice_id=$invoice_result->ID;
													$client_name=get_the_title(get_post_meta( $invoice_id, '_client_id', true ));
													// $company_id=yl_get_company_id_by_invoice_id($invoice_id);
													$leasd_id =	get_post_meta($invoice_id, '_yl_lease_id',true);
													$company_id=	get_post_meta($leasd_id, '_yl_company_name', true);
													$company_name=get_the_title( $company_id );
													$total=get_post_meta( $invoice_id, '_total', true );
													$client_id=get_post_meta( $invoice_id, '_client_id', true);
													$client_name=get_the_title(get_post_meta( $invoice_id, '_client_id', true ));
													$suite_number="";
													$leasd_id= get_post_meta( $invoice_id, '_yl_lease_id', true );
													$suite_number=get_post_meta($leasd_id, '_yl_suite_number', true );
													if($suite_number=='-1')
													{
													$suite_number="Y-Memberships";
													}
													$invoice_num='=HYPERLINK("'.get_permalink($invoice_id).'", "'.$invoice_id.'")';

													$invoice_ids[$invoice_id]['client_name']=$client_name;
													$invoice_ids[$invoice_id]['company_name']=$company_name;
													$invoice_ids[$invoice_id]['invoice_num']=$invoice_num;
													$invoice_ids[$invoice_id]['suite_number']=$suite_number;
													$invoice_ids[$invoice_id]['num']=$invoice_id;
													$invoice_ids[$invoice_id]['totalmk']=$total;
													$invoice_ids[$invoice_id]['date']=$paymentdate;
													$invoice_ids[$invoice_id]['line_items']=maybe_unserialize(get_post_meta($invoice_id, '_doc_line_items', true ));
													$full_total=(float) ($full_total+$total);

									}
										/********************/
									// echo "In range".$invoice_result->ID;
									// array_push($invoice_ids, $invoice_result->ID);
									# code...
									}
								}

							}
							// echo "<pre>";
							// print_r($lease_results);
							// echo "<pre>";
							$all_data[$suite_id]['leases']=$leases_ids;
							$all_data[$suite_id]['invoices']=$invoice_ids;

							$data='			<style type="text/css" media="screen">
							.mk_account_reports table{
							border-collapse: collapse;
							margin: 10px 0;
							}
							.mk_account_reports	th{
							font-weight: normal;
							border: 1px solid #D4D4D4;
							padding: 3px;
							text-align: center;
							}
							.mk_account_reports	td{
							border: 1px solid #D4D4D4;
							}
							</style>
							<div class="wrap">
							<h2 class="mkbis2">Rent Roll Reports</h2>
							<div class="mk_account_reports">

							<table cellpadding="0" cellspacing="0" border="0" width="100%">
							<thead>
							<tr>
							<th>  Suite Number     </th>
							<th>Tennant Name</th>
							<th>Desc</th>
							<th>Aux value</th>
							<th>Invoice Id</th>
							</tr>
							</thead> 
							<tbody>';

							foreach ($all_data as $suite_id => $main) {

							$leasd_id =	$main['leases'][0];
							$invoices_ids= $main['invoices'];
							if(empty($invoice_ids))
							{

							$data.='<tr>

							<td>'.get_the_title( $suite_id ).'</td>
							<td>0</td>
							<td>0</td>
							<td>0</td>

							</tr>';

							}
							else
							{
							$tenament_name="";
							$aux_charges=0;
							$rent_charges=0;
							$monthlyrent=0;
							$suite_name_mk=get_the_title( $suite_id );
							foreach ($invoices_ids as $key => $value) {
							$tenament_name=$value['company_name'];;

							foreach ($value['line_items'] as $line => $lineitem) {
							if (strpos($lineitem['desc'], 'Rent') !== false ) {
							$rent_charges=(float) ($rent_charges+$lineitem['total']);
							}
							else{
							$aux_charges=(float) ($lineitem['total']);
							$desc=$lineitem['desc'];
							$data.='<tr>

							<td>'.get_the_title( $suite_id ).'</td>
							<td>'. $tenament_name.'</td>
							<td>'.$desc.'</td>
							<td>$'.$aux_charges.'</td>
							<td><a target="_blank" href="'.get_post_permalink($key).'">'.$key.'</a></td>
							</tr>';
							}
							}


							# code...
							}

							?>

							<?php

							}
							// echo "<pre>";
							// print_r($invoices_ids);
							// echo "</pre>";
							// $key=  														 
							// $suite_id=	get_post_meta($leasd_id, '_yl_product_id',true);
							// echo "<pre>";
							// print_r($value);
							// echo "</pre>";

							}	



							$data.='<style type="text/css" media="screen">
							.mk_account_reports table{
							border-collapse: collapse;
							margin: 10px 0;
							}
							.mk_account_reports	th{
							font-weight: normal;
							border: 1px solid #D4D4D4;
							padding: 3px;
							text-align: left;
							}
							.mk_account_reports	td{
							border: 1px solid #D4D4D4;
							}
							</style>


							</tbody>
							</table>
							</div>
							</div>';




			restore_current_blog();


			

echo $data;



wp_die();


	// wp_die();
}




add_action( 'wp_ajax_get_ymember', 'get_ymember_callback' );
add_action( 'wp_ajax_nopriv_get_ymember', 'get_ymember_callback' );


function get_ymember_callback()
{

			switch_to_blog($_REQUEST['building']);
			 $_REQUEST['building'];
    		 $client_id_mk= $_REQUEST['client_id'];
			 // $start_account= $_REQUEST['startdate'];
			 $start_account= $_REQUEST['startdate'];
			 $end_account= $_REQUEST['enddate'];
				global $wpdb;
				global $post;
							$data='			<style type="text/css" media="screen">
							.mk_account_reports table{
							border-collapse: collapse;
							margin: 10px 0;
							}
							.mk_account_reports	th{
							font-weight: normal;
							border: 1px solid #D4D4D4;
							padding: 3px;
							text-align: center;
							}
							.mk_account_reports	td{
							border: 1px solid #D4D4D4;
							}
							</style>
							<div class="wrap">
							<h2 class="mkbis2">Rent Roll Reports</h2>
							<div class="mk_account_reports">

							<table cellpadding="0" cellspacing="0" border="0" width="100%">
							<thead>
							<tr>
							<th>  Invoice Name     </th>
							<th>Tennant Name</th>
							<th>Desc</th>
							<th>Aux value</th>
							<th>Invoice Id</th>
							</tr>
							</thead> 
							<tbody>';
			$start_Account2=strtotime($start_account);
			$end_Account2=strtotime($end_account);
							$args = array(
							'post_type' => 'sa_invoice',
						    'post_status' => 'any',

							'posts_per_page' => -1,
							'orderby' => 'meta_value',
							'order' => 'ASC',
							'meta_key' => '_due_date',
							'meta_query' => array(
							array(
							'key' => '_due_date',
							'value' => array($start_Account2, $end_Account2),
							'compare' => 'BETWEEN',
							'type' => 'Numeric'
							)
							)
							);
							$multisuite_invoices_loop = get_posts($args);
							$y_membership_items=array();
							$y_membership_clients=array();


							// echo "<pre>";
							// // print_r($multisuite_invoices_loop);

							// echo "</pre>";
							foreach ($multisuite_invoices_loop as $key_m => $value_m) {

								 $yl_lease_id=get_post_meta( $value_m->ID, '_yl_lease_id', true );
								 $yl_suite_number=get_post_meta( $yl_lease_id, '_yls_suite_number', true );

								$client_id=get_post_meta($value_m->ID, '_client_id', true );

								$client_name=get_the_title( $client_id );
								if($yl_suite_number == -1)
								{

									$line_items=maybe_unserialize(get_post_meta($value_m->ID, '_doc_line_items', true ));
									// print_r($line_items);
									foreach ($line_items as $l_key => $l_value) {

									if($client_id==$client_id_mk)
									{



																		if (strpos($l_value['desc'], 'Monthly Rent for') !== false ) {

									$data.='<tr>

									<td>'.get_the_title( $value_m->ID ).'</td>
									<td>'. $client_name.'</td>
									<td>'.$l_value["desc"].'</td>
									<td>$'.$l_value["total"].'</td>
									<td><a target="_blank" href="'.get_post_permalink( $value_m->ID).'">'. $value_m->ID.'</a></td>
									</tr>';

								}
									}






							}
							}
						}


								 // $yl_lease_id=get_post_meta( $invoice_id_mk, '_yl_lease_id', true );
								 // $yl_suite_number=get_post_meta( $yl_lease_id, '_yls_suite_number', true );



							// 	if($yl_suite_number == -1)
							// 	{
							// 		$line_items=maybe_unserialize(get_post_meta($invoice_id_mk, '_doc_line_items', true ));
							// 		foreach ($line_items as $l_key => $l_value) {
							// 			// echo "<pre>";
							// 			// print_r($l_value);
							// 			// echo "</pre>";

							// 		if (strpos($l_value['desc'], 'Monthly Rent for') !== false ) {
							// 		// $y_membership_items[$value_m->ID]['rent']=$l_value['total'];
							// 		$client_name=get_the_title(get_post_meta( $invoice_id_mk, '_client_id', true ));
							// 	if (strpos($l_value['desc'], 'Monthly Rent for') !== false ) {

							// 		$data.='<tr>

							// 		<td>'.get_the_title( $invoice_id_mk ).'</td>
							// 		<td>'. $client_name.'</td>
							// 		<td>'.$l_value["desc"].'</td>
							// 		<td>$'.$l_value["total"].'</td>
							// 		<td><a target="_blank" href="'.get_post_permalink($invoice_id_mk).'">'.$invoice_id_mk.'</a></td>
							// 		</tr>';

							// 	}
							// 		}

							// 	}

							// }



							$data.='<style type="text/css" media="screen">
							.mk_account_reports table{
							border-collapse: collapse;
							margin: 10px 0;
							}
							.mk_account_reports	th{
							font-weight: normal;
							border: 1px solid #D4D4D4;
							padding: 3px;
							text-align: left;
							}
							.mk_account_reports	td{
							border: 1px solid #D4D4D4;
							}
							</style>


							</tbody>
							</table>
							</div>
							</div>';




			restore_current_blog();


			

echo $data;



wp_die();


}


add_action( 'wp_ajax_get_ymemberaux', 'get_ymemberaux_callback' );
add_action( 'wp_ajax_nopriv_get_ymemberaux', 'get_ymemberaux_callback' );


function get_ymemberaux_callback()
{

			switch_to_blog($_REQUEST['building']);
			 $_REQUEST['building'];
    		 $client_id_mk= $_REQUEST['client_id'];
			 // $start_account= $_REQUEST['startdate'];
			 $start_account= $_REQUEST['startdate'];
			 $end_account= $_REQUEST['enddate'];
				global $wpdb;
				global $post;
							$data='			<style type="text/css" media="screen">
							.mk_account_reports table{
							border-collapse: collapse;
							margin: 10px 0;
							}
							.mk_account_reports	th{
							font-weight: normal;
							border: 1px solid #D4D4D4;
							padding: 3px;
							text-align: center;
							}
							.mk_account_reports	td{
							border: 1px solid #D4D4D4;
							}
							</style>
							<div class="wrap">
							<h2 class="mkbis2">Rent Roll Reports</h2>
							<div class="mk_account_reports">

							<table cellpadding="0" cellspacing="0" border="0" width="100%">
							<thead>
							<tr>
							<th>  Invoice Name     </th>
							<th>Tennant Name</th>
							<th>Desc</th>
							<th>Aux value</th>
							<th>Invoice Id</th>
							</tr>
							</thead> 
							<tbody>';
			$start_Account2=strtotime($start_account);
			$end_Account2=strtotime($end_account);
							$args = array(
							'post_type' => 'sa_invoice',
						    'post_status' => 'any',

							'posts_per_page' => -1,
							'orderby' => 'meta_value',
							'order' => 'ASC',
							'meta_key' => '_due_date',
							'meta_query' => array(
							array(
							'key' => '_due_date',
							'value' => array($start_Account2, $end_Account2),
							'compare' => 'BETWEEN',
							'type' => 'Numeric'
							)
							)
							);
							$multisuite_invoices_loop = get_posts($args);
							$y_membership_items=array();
							$y_membership_clients=array();


							// echo "<pre>";
							// // print_r($multisuite_invoices_loop);

							// echo "</pre>";
							foreach ($multisuite_invoices_loop as $key_m => $value_m) {

								 $yl_lease_id=get_post_meta( $value_m->ID, '_yl_lease_id', true );
								 $yl_suite_number=get_post_meta( $yl_lease_id, '_yls_suite_number', true );

								$client_id=get_post_meta($value_m->ID, '_client_id', true );

								$client_name=get_the_title( $client_id );
								if($yl_suite_number == -1)
								{

									$line_items=maybe_unserialize(get_post_meta($value_m->ID, '_doc_line_items', true ));
									// print_r($line_items);
									foreach ($line_items as $l_key => $l_value) {

									if($client_id==$client_id_mk)
									{



																		if (strpos($l_value['desc'], 'Monthly Rent for') !== false ) {

									// $data.='<tr>

									// <td>'.get_the_title( $value_m->ID ).'</td>
									// <td>'. $client_name.'</td>
									// <td>'.$l_value["desc"].'</td>
									// <td>$'.$l_value["total"].'</td>
									// <td><a target="_blank" href="'.get_post_permalink( $value_m->ID).'">'. $value_m->ID.'</a></td>
									// </tr>';

								}
								else{

									$data.='<tr>

									<td>'.get_the_title( $value_m->ID ).'</td>
									<td>'. $client_name.'</td>
									<td>'.$l_value["desc"].'</td>
									<td>$'.$l_value["total"].'</td>
									<td><a target="_blank" href="'.get_post_permalink( $value_m->ID).'">'. $value_m->ID.'</a></td>
									</tr>';

									
									}
								}






							}
							}
						}


								 // $yl_lease_id=get_post_meta( $invoice_id_mk, '_yl_lease_id', true );
								 // $yl_suite_number=get_post_meta( $yl_lease_id, '_yls_suite_number', true );



							// 	if($yl_suite_number == -1)
							// 	{
							// 		$line_items=maybe_unserialize(get_post_meta($invoice_id_mk, '_doc_line_items', true ));
							// 		foreach ($line_items as $l_key => $l_value) {
							// 			// echo "<pre>";
							// 			// print_r($l_value);
							// 			// echo "</pre>";

							// 		if (strpos($l_value['desc'], 'Monthly Rent for') !== false ) {
							// 		// $y_membership_items[$value_m->ID]['rent']=$l_value['total'];
							// 		$client_name=get_the_title(get_post_meta( $invoice_id_mk, '_client_id', true ));
							// 	if (strpos($l_value['desc'], 'Monthly Rent for') !== false ) {

							// 		$data.='<tr>

							// 		<td>'.get_the_title( $invoice_id_mk ).'</td>
							// 		<td>'. $client_name.'</td>
							// 		<td>'.$l_value["desc"].'</td>
							// 		<td>$'.$l_value["total"].'</td>
							// 		<td><a target="_blank" href="'.get_post_permalink($invoice_id_mk).'">'.$invoice_id_mk.'</a></td>
							// 		</tr>';

							// 	}
							// 		}

							// 	}

							// }



							$data.='<style type="text/css" media="screen">
							.mk_account_reports table{
							border-collapse: collapse;
							margin: 10px 0;
							}
							.mk_account_reports	th{
							font-weight: normal;
							border: 1px solid #D4D4D4;
							padding: 3px;
							text-align: left;
							}
							.mk_account_reports	td{
							border: 1px solid #D4D4D4;
							}
							</style>


							</tbody>
							</table>
							</div>
							</div>';




			restore_current_blog();


			

echo $data;



wp_die();


}




	function testing_income()
	{


		?>
		<script src="http://code.jquery.com/ui/1.11.1/jquery-ui.min.js"></script>
<style type="text/css" media="screen">
	a.boxclose{
    float:right;
    margin-top:-30px;
    margin-right:-30px;
    cursor:pointer;
    color: #fff;
    border: 1px solid #AEAEAE;
    border-radius: 30px;
    background: #605F61;
    font-size: 31px;
    font-weight: bold;
    display: inline-block;
    line-height: 0px;
    padding: 11px 3px;       
}

.boxclose:before {
    content: "×";
}
</style>
<link rel="stylesheet" href="https://code.jquery.com/ui/1.11.1/themes/smoothness/jquery-ui.css" />

<script  type="text/javascript" charset="utf-8" >

function showyourPopup() {
    jQuery("#yourPopup").dialog({
        autoOpen: true,
        resizable: false,
        height: 'auto',
        width: 'auto',
        modal: true,
        //show: { effect: "puff", duration: 300 }, 
        draggable: true
    });

   jQuery(".ui-widget-header").css({"display":"none"}); 
}

function showyourPopup2() {
    jQuery("#yourPopupauxrent").dialog({
        autoOpen: true,
        resizable: false,
        height: 'auto',
        width: 'auto',
        modal: true,
        //show: { effect: "puff", duration: 300 }, 
        draggable: true
    });

   jQuery(".ui-widget-header").css({"display":"none"}); 
}

function closeyourPopup() { jQuery("#yourPopup").dialog('close'); }
function closeyourPopup2() { jQuery("#yourPopupauxrent").dialog('close'); }

/* Submit Resources Popup */

function submitResources_phone(startdate,enddate,building,check){




	    jQuery('#yourPopup2').empty(); 
	
	    showyourPopup();
    	var data = {
			'action': 'get_client_allinvoices_phone',
			/*'suite_id': id,*/
			'building': building,
			'startdate': startdate,
			'enddate': enddate,
			'check': check,
		};
		var ajaxurl = '<?php echo admin_url('admin-ajax.php'); ?>';
		// since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
		jQuery.post(ajaxurl, data, function(response) {
			// alert('Got this from the server: ' + response);

	    // jQuery('#yourPopup2').html(response); 
	    jQuery('#yourPopup2').html(response).promise().done(function(){
		    //your callback logic / code here
		    // alert(response);
	    jQuery("#yourPopup").dialog('open');
		});
		});

}

function submitResources(id,startdate,enddate,building){


	    jQuery('#yourPopup2').empty(); 
	
	    showyourPopup();
    	var data = {
			'action': 'get_client_allinvoices',
			'suite_id': id,
			'building': building,
			'startdate': startdate,
			'enddate': enddate,
		};
		var ajaxurl = '<?php echo admin_url('admin-ajax.php'); ?>';
		// since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
		jQuery.post(ajaxurl, data, function(response) {
			// alert('Got this from the server: ' + response);

	    // jQuery('#yourPopup2').html(response); 
	    jQuery('#yourPopup2').html(response).promise().done(function(){
		    //your callback logic / code here
	    jQuery("#yourPopup").dialog('open');
		});
		});

}


function submitymember(id,startdate,enddate,building){


	    jQuery('#yourPopup2').empty(); 
	
	    showyourPopup();
    	var data = {
			'action': 'get_ymember',
			'client_id': id,
			'building': building,
			'startdate': startdate,
			'enddate': enddate,

		};
		var ajaxurl = '<?php echo admin_url('admin-ajax.php'); ?>';
		// since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
		jQuery.post(ajaxurl, data, function(response) {
			// alert('Got this from the server: ' + response);

	    // jQuery('#yourPopup2').html(response); 
	    jQuery('#yourPopup2').html(response).promise().done(function(){
		    //your callback logic / code here
	    jQuery("#yourPopup").dialog('open');
		});
		});

}






function submitResources_multisuite(suiteid,invoiceid,startdate,enddate,building){


	    jQuery('#yourPopup2').empty(); 
	
	    showyourPopup();
    	var data = {
			'action': 'get_client_allinvoices_multi',
			'suite_id': suiteid,
			'invoice_id': invoiceid,
			'building': building,
			'startdate': startdate,
			'enddate': enddate,
		};
		var ajaxurl = '<?php echo admin_url('admin-ajax.php'); ?>';
		// since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
		jQuery.post(ajaxurl, data, function(response) {
			// alert('Got this from the server: ' + response);

	    // jQuery('#yourPopup2').html(response); 
	    jQuery('#yourPopup2').html(response).promise().done(function(){
		    //your callback logic / code here
	    jQuery("#yourPopup").dialog('open');
		});
		});

}


/* Submit Resources Popup */
function submitymemberaux(id,startdate,enddate,building){


	    jQuery('#yourPopupauxrent2').empty(); 
	
	    showyourPopup2();
    	var data = {
			'action': 'get_ymemberaux',
			'client_id': id,
			'building': building,
			'startdate': startdate,
			'enddate': enddate,
		};
		var ajaxurl = '<?php echo admin_url('admin-ajax.php'); ?>';
		// since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
		jQuery.post(ajaxurl, data, function(response) {
			// alert('Got this from the server: ' + response);

	    // jQuery('#yourPopup2').html(response); 
	    jQuery('#yourPopupauxrent2').html(response).promise().done(function(){
		    //your callback logic / code here
	    jQuery("#yourPopupauxrent").dialog('open');
		});
		});

}




function submitResourcesauxrent(id,startdate,enddate,building){


	    jQuery('#yourPopupauxrent2').empty(); 
	
	    showyourPopup2();
    	var data = {
			'action': 'get_client_allauxrent',
			'suite_id': id,
			'building': building,
			'startdate': startdate,
			'enddate': enddate,
		};
		var ajaxurl = '<?php echo admin_url('admin-ajax.php'); ?>';
		// since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
		jQuery.post(ajaxurl, data, function(response) {
			// alert('Got this from the server: ' + response);

	    // jQuery('#yourPopup2').html(response); 
	    jQuery('#yourPopupauxrent2').html(response).promise().done(function(){
		    //your callback logic / code here
	    jQuery("#yourPopupauxrent").dialog('open');
		});
		});

}


function submitResourcesauxrent_multisite(suiteid,invoiceid,startdate,enddate,building,auxshow){


	    jQuery('#yourPopupauxrent2').empty(); 
	
	    showyourPopup2();
    	var data = {
			'action': 'get_client_allauxrent_multisuite',
			'suite_id':suiteid,
			'invoice_id': invoiceid,
			'auxshow': auxshow,
			'building': building,
			'startdate': startdate,
			'enddate': enddate,
		};
		var ajaxurl = '<?php echo admin_url('admin-ajax.php'); ?>';
		// since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
		jQuery.post(ajaxurl, data, function(response) {
			// alert('Got this from the server: ' + response);

	    // jQuery('#yourPopup2').html(response); 
	    jQuery('#yourPopupauxrent2').html(response).promise().done(function(){
		    //your callback logic / code here
	    jQuery("#yourPopupauxrent").dialog('open');
		});
		});

}







		jQuery(document).ready(function($) {
				$(".datepicker").datepicker({
				dateFormat: "yy-mm-dd"
				});
		});

		</script>
<div id="yourPopup" style="padding:0; margin:0; display:none;">
<a href="javascript:void(0)" onclick="closeyourPopup();" title="" style="float:right;"><img src="<?php echo plugin_dir_url( __FILE__ ) . 'images/cross_button.png'; ?>" alt=""></a>
<div id="yourPopup2">
</div>

</div>

<div id="yourPopupauxrent" style="padding:0; margin:0; display:none;">
<a href="javascript:void(0)" onclick="closeyourPopup2();" title="" style="float:right;"><img src="<?php echo plugin_dir_url( __FILE__ ) . 'images/cross_button.png'; ?>" alt=""></a>
<div id="yourPopupauxrent2">
<!-- hiii hello -->
</div>

</div>

<?php 
		


		if(isset($_POST['accountingmk_submit']))
		{


			if($_POST['_accounting_report_id']=="Deposit Journal Entries")
			{
				$building=$_POST['_accounting_building_id'];

			if($building=="all")
			{

				$sites = wp_get_sites();


				$all_blogs_id=array();
				$removed_ids=array(1,20,19,6);
				foreach ($sites as $key => $current_blog) {

					if(!in_array($current_blog['blog_id'], $removed_ids))
					{
					array_push($all_blogs_id, $current_blog['blog_id']);
					}
				}
						$csv_data_array=array();
				$i=1;

				foreach ($all_blogs_id as $crid) {
			
// Fort Harrison – 
// Frisco – 
// McKinney – 
// // All others – Busey Bank
// Plano – will be Texas Capital Bank
$bank="Busey Bank";
					switch ($crid) {
					case '4':
					$class="DEV";
					break;
					case '9':
					$class="MCK";
					$bank="Texas Capital";

					break;
					case '10':
					$class="FR";
					$bank="Texas Capital Bank";

					break;
					case '11':
					$class="FHRA";
					$bank="Star Bank";
					break;	
					case '12':
					$class="C1";
					break;
					case '13':
					$class="F1";
					break;
					case '14':
					$class="F2";
					break;
					case '15':
					$class="GW";
					break;
					case '16':
					$class="N1";
					break;
					case '17':
					$class="N2";
					break;
					case '18':
					$class="OSW";
					break;										
					default:
					$class="DEV";
						break;
				}



					?>
					<div class="wrap">
					<h2 class="mkbis2">Deposit Journal Entries</h2>
					<?php

				
				$sites = wp_get_sites();

				switch_to_blog($crid);


				global $wpdb;
				global $post;
				$start_account=$_POST['_accounting_start_date'];
				$start_acc=explode("-", $start_account);
				$str_yr=$start_acc[0];
				$str_mn=$start_acc[1];
				$str_dy=$start_acc[2];
				$end_account=$_POST['_accounting_end_date'];
				$end_acc=explode("-", $end_account);
				$end_yr=$end_acc[0];
				$end_mn=$end_acc[1];
				$end_dy=$end_acc[2];
				$args = array(
					'post_type' => 'sa_payment',
					'post_status' => 'complete',
						'date_query' => array(
							array(
								'column' => 'post_modified',
								'after'     => array(
									'year'  => $str_yr,
									'month' => $str_mn,
									'day'   => $str_dy,
								),
								'before'    => array(
									'year'  => $end_yr,
									'month' => $end_mn,
									'day'   => $end_dy,
								),
								'inclusive' => true,
							),
						),
					'posts_per_page' => -1,
					);



					$results = get_posts($args);

				$all_week_data=array();
				$all_week_data2=array();
				foreach ($results as $key => $result) {
				$paymentdatefull=explode(" ", $result->post_modified);		
				$paymentdate=$paymentdatefull[0];		

				$paymentid=$result->ID;	

				$recme=get_post_meta($paymentid,NULL,true);
				$metttadata1=array();
				$metttadata1['post_date']=$paymentdate;
				foreach ($recme as $m1 => $v1) {
					// echo $m1;
					// echo $v1[0];
					# code...
					$metttadata1[$m1]=$v1[0];
							if( $m1=="_payment_invoice")
					{
					$metttadata1['line_items']=get_post_meta( $v1[0], '_doc_line_items', true );
					}

					
				}
				// print_r($metttadata1);
				$credit=get_post_meta($paymentid,'_payment_method',true);
				$invoice_id=get_post_meta($paymentid,'_payment_invoice',true);
				if($credit=="Credit (NMI)" && get_post_status( $invoice_id ) =="complete")
				{

				$all_week_data2[$paymentid]=$metttadata1;
				}
				}
			
// 				foreach ($all_week_data2 as  $i=>$data) {
// 					# code...
					
				
// 				$invoice_id= $data['_payment_invoice'];
// 				$argspayment = array(
//     'meta_key' => '_payment_invoice',
//     'meta_value' => $invoice_id,
//     'post_type' => 'sa_payment',
//     'post_status' => 'any',
//     'posts_per_page' => -1
// );
// $paymentid = get_posts($argspayment);
// // echo "<pre>";
// // print_r($paymentid);
// // echo "</pre>";

// $credit=get_post_meta( $paymentid[0]->ID, "_payment_method", true );
// 		// var_dump($credit);
// 				if(get_post_status( $invoice_id ) !="complete" || $credit!="Credit (NMI)")
// 				{
// 						unset($all_week_data2[$i]);
// 					}
// 					// echo get_post_status( $data['_payment_invoice'] );
// 					// unset($array[$i]);
// 				}
				    // print_r($results3);
				// echo "<pre>";
				// print_r($all_week_data);
				// echo "</pre>";
			$final_data_table=array();
			$final_data_table['total']="";
			$final_data_table['fees']="";
			$final_data_table['all_dates']=array();
			foreach ($all_week_data2 as $key => $value) {
				$percentage=(float) (100+get_post_meta( $value['_payment_invoice'], '_doc_tax2', true )); 
				$singlepercentage=number_format($value['_amount']/$percentage,2);
				$total_value_added= number_format($singlepercentage*get_post_meta( $value['_payment_invoice'], '_doc_tax2', true ),2);//$value['_amount']/
				// number_format($final_data_table['total'],2)
				$subtotal=$value['_amount']-$total_value_added;
				$final_data_table['total']=(float) ($final_data_table['total']+$subtotal);
				$final_data_table['fees']=(float) ($final_data_table['fees']+$total_value_added);
				if($final_data_table['all_dates'][$value['post_date']]!=NULL && $final_data_table['all_dates'][$value['post_date']]!="")
				{
       				$amount_arr=$final_data_table['all_dates'][$value['post_date']];
					$final_data_table['all_dates'][$value['post_date']]=(float) ($amount_arr+$value['_amount']);
				}
				else{
				$final_data_table['all_dates'][$value['post_date']]= $value['_amount'];

				}
			}
			// echo "<pre>";
			// print_r($final_data_table);
			?>
			<style type="text/css" media="screen">
					.mk_account_reports table{
							border-collapse: collapse;
							margin: 10px 0;
						}
					.mk_account_reports	th{
							font-weight: normal;
							border: 1px solid #D4D4D4;
							padding: 3px;
							text-align: left;
						}
					.mk_account_reports	td{
							border: 1px solid #D4D4D4;
							}
			</style>
			<div class="mk_account_reports">
				
			<table cellpadding="0" cellspacing="0" border="0" width="100%">
			<thead>
				<tr>
					<th>Accounts Receivable</th>
					<th></th>
					<th style="text-align: right;"><?php echo "$".$final_data_table['total']; ?></th>
					<th>Week of <?php echo $end_account; ?></th>
					<th></th>
					<th></th>
					<th>Class</th>

				</tr>
			</thead>
			<tbody>
			<?php
			$csv_data_array=array();
			$i=2;
			foreach ($final_data_table['all_dates'] as $key => $value) {
				?>
			<tr>
			<td><?php echo $bank; ?></td>
			<td>$<?php echo $value;?></td>
			<td></td>
			<td><?php echo $key; ?></td>
			<td></td>
			<td></td>
			<td><?php echo $class; ?></td>
			
			</tr>
				<?php
				if($i==2)
				{

				$csv_data_array[0]['Accounts']='Accounts Receivable';
				$csv_data_array[0]['Debit']=" ";
				$csv_data_array[0]['Credit']='$' . number_format($final_data_table['total'],2);
				$csv_data_array[0]["MEMO"]="Week of ".$end_account;
					$csv_data_array[1]['Accounts']='Fees';
				$csv_data_array[1]['Debit']=" ";
				$csv_data_array[1]['Credit']='$' . number_format($final_data_table['fees'],2);
				$csv_data_array[1]["MEMO"]="Week of ".$end_account;
				}
				// Account, Debit, Credit, Memo
				$csv_data_array[$i]['Accounts']=$bank;
				$csv_data_array[$i]['Debit']='$' . number_format($value,2);
				$csv_data_array[$i]['Credit']="";
				$csv_data_array[$i]["MEMO"]=$key;
			$i++;
			}
			?>
				</tbody>
				</table>
				</div>
				</div>
				<?php
				restore_current_blog();


				}
				reporst_csv("Deposit journal invoice.csv",$csv_data_array,"rental_report");



				}
				else{
					echo $building;
			$bank="Busey Bank";
					switch ($building) {
					case '4':
					$class="DEV";
					break;
					case '9':
					$class="MCK";
					$bank="Texas Capital";

					break;
					case '10':
					$class="FR";
					$bank="Texas Capital Bank";

					break;
					case '11':
					$class="FHRA";
					$bank="Star Bank";
					break;	
					case '12':
					$class="C1";
					break;
					case '13':
					$class="F1";
					break;
					case '14':
					$class="F2";
					break;
					case '15':
					$class="GW";
					break;
					case '16':
					$class="N1";
					break;
					case '17':
					$class="N2";
					break;
					case '18':
					$class="OSW";
					break;										
					default:
					$class="DEV";
						break;
				}

				?>
				<div class="wrap">
				<h2 class="mkbis2">Deposit Journal Entries</h2>
				<?php
				$building=$_POST['_accounting_building_id'];


			
			$sites = wp_get_sites();

			switch_to_blog($building);


			global $wpdb;
			global $post;
			$start_account=$_POST['_accounting_start_date'];
			$start_acc=explode("-", $start_account);
			$str_yr=$start_acc[0];
			$str_mn=$start_acc[1];
			$str_dy=$start_acc[2];
			$end_account=$_POST['_accounting_end_date'];
			$end_acc=explode("-", $end_account);
			$end_yr=$end_acc[0];
			$end_mn=$end_acc[1];
			$end_dy=$end_acc[2];
			$args = array(
				'post_type' => 'sa_payment',
				'post_status' => 'complete',
					'date_query' => array(
						array(
							'column' => 'post_modified',
							'after'     => array(
								'year'  => $str_yr,
								'month' => $str_mn,
								'day'   => $str_dy,
							),
							'before'    => array(
								'year'  => $end_yr,
								'month' => $end_mn,
								'day'   => $end_dy,
							),
							'inclusive' => true,
						),
					),
				'posts_per_page' => -1,
				);


				$results = get_posts($args);

			$all_week_data=array();
			$all_week_data2=array();
			// foreach ($results as $key => $result) {
			// $paymentdatefull=explode(" ", $result->post_modified);		
			// $paymentdate=$paymentdatefull[0];		

			// $paymentid=$result->ID;	

			// $recme=get_post_meta($paymentid,NULL,true);
			// $metttadata1=array();
			// $metttadata1['post_date']=$paymentdate;
			// foreach ($recme as $m1 => $v1) {

			// 	$metttadata1[$m1]=$v1[0];
			// 			if( $m1=="_payment_invoice")
			// 	{
			// 	$metttadata1['line_items']=get_post_meta( $v1[0], '_doc_line_items', true );
			// 	}

				
			// }
			// $all_week_data2[$paymentid]=$metttadata1;



			// }
				foreach ($results as $key => $result) {
				$paymentdatefull=explode(" ", $result->post_modified);		
				$paymentdate=$paymentdatefull[0];		

				$paymentid=$result->ID;	

				$recme=get_post_meta($paymentid,NULL,true);
				$metttadata1=array();
				$metttadata1['post_date']=$paymentdate;
				foreach ($recme as $m1 => $v1) {
					// echo $m1;
					// echo $v1[0];
					# code...
					$metttadata1[$m1]=$v1[0];
							if( $m1=="_payment_invoice")
					{
					$metttadata1['line_items']=get_post_meta( $v1[0], '_doc_line_items', true );
					}

					
				}
				// print_r($metttadata1);
				$credit=get_post_meta($paymentid,'_payment_method',true);
				$invoice_id=get_post_meta($paymentid,'_payment_invoice',true);
				$percentage=get_post_meta( $invoice_id, '_doc_tax2', true );
				if($credit=="Credit (NMI)" && get_post_status( $invoice_id ) =="complete")
				{
					
				$all_week_data2[$paymentid]=$metttadata1;
				}
				}

			
			foreach ($all_week_data2 as  $i=>$data) {
				# code...
				
				$invoice_id= $data['_payment_invoice'];
				$percentage=get_post_meta( $invoice_id, '_doc_tax2', true );

				$argspayment = array(
			    'meta_key' => '_payment_invoice',
			    'meta_value' => $invoice_id,
			    'post_type' => 'sa_payment',
			    'post_status' => 'any',
			    'posts_per_page' => -1
			);
			$paymentid = get_posts($argspayment);
			// echo "<pre>";
			// print_r($paymentid);
			// echo "</pre>";

			$credit=get_post_meta( $paymentid[0]->ID, "_payment_method", true );
					// var_dump($credit);
							if(get_post_status( $invoice_id ) !="complete" && $credit!="Credit (NMI)")
							{
								unset($all_week_data2[$i]);
							}

			}
			$final_data_table=array();
			$final_data_table['total']="";
			$final_data_table['fees']="";
			$final_data_table['all_dates']=array();
			foreach ($all_week_data2 as $key => $value) {
				$percentage=100+get_post_meta( $value['_payment_invoice'], '_doc_tax2', true ); 
				$singlepercentage=$value['_amount']/$percentage;
				$total_value_added= number_format($singlepercentage*get_post_meta( $value['_payment_invoice'], '_doc_tax2', true ),2);//$value['_amount']/
				// number_format($final_data_table['total'],2)
				$subtotal=$value['_amount']-$total_value_added;
				$final_data_table['total']=$final_data_table['total']+$subtotal;
				$final_data_table['fees']=$final_data_table['fees']+$total_value_added;
				echo "prencentage=".$percentage;
			echo "<br>";
				echo "singlepercentage=".$singlepercentage;
			echo "<br>";
				echo "total_value_added=".$total_value_added;
			echo "<br>";
			echo "Total value added=".$total_value_added;
			echo "<br>";
			echo "Subtotal=".$subtotal;
			echo "Final total=".$final_data_table['total'];
			echo "Final fee =".$final_data_table['fees'];
			echo "<br>";

				if($final_data_table['all_dates'][$value['post_date']]!=NULL && $final_data_table['all_dates'][$value['post_date']]!="")
				{
       				$amount_arr=$final_data_table['all_dates'][$value['post_date']];
					$final_data_table['all_dates'][$value['post_date']]=(float) ($amount_arr+$value['_amount']);
				}
				else{
				$final_data_table['all_dates'][$value['post_date']]= $value['_amount'];

				}
			}
			// echo "<pre>";
			// print_r($final_data_table);
			?>
			<style type="text/css" media="screen">
					.mk_account_reports table{
							border-collapse: collapse;
							margin: 10px 0;
						}
					.mk_account_reports	th{
							font-weight: normal;
							border: 1px solid #D4D4D4;
							padding: 3px;
							text-align: left;
						}
					.mk_account_reports	td{
							border: 1px solid #D4D4D4;
							}
			</style>
			<div class="mk_account_reports">
				
			<table cellpadding="0" cellspacing="0" border="0" width="100%">
			<thead>
				<tr>
					<th>Accounts Receivable</th>
					<th></th>
					<th style="text-align: right;"><?php echo "$".$final_data_table['total']; ?></th>
					<th>Week of <?php echo $end_account; ?></th>
					<th></th>
					<th></th>
					<th>Class</th>

				</tr>
			</thead>
			<tbody>
			<?php
			$csv_data_array=array();
			$i=2;
			foreach ($final_data_table['all_dates'] as $key => $value) {
				?>
			<tr>
			<td><?php echo $bank; ?></td>
			<td>$<?php echo $value;?></td>
			<td></td>
			<td><?php echo $key; ?></td>
			<td></td>
			<td></td>
			<td><?php echo $class; ?></td>
			
			</tr>
				<?php
				if($i==2)
				{

				$csv_data_array[0]['Accounts']='Accounts Receivable';
				$csv_data_array[0]['Debit']=" ";
				$csv_data_array[0]['Credit']='$' . number_format($final_data_table['total'],2);
				$csv_data_array[0]["MEMO"]="Week of ".$end_account;
					$csv_data_array[1]['Accounts']='Fees';
				$csv_data_array[1]['Debit']=" ";
				$csv_data_array[1]['Credit']='$' . number_format($final_data_table['fees'],2);
				$csv_data_array[1]["MEMO"]="Week of ".$end_account;
				}
				// Account, Debit, Credit, Memo
				$csv_data_array[$i]['Accounts']=$bank;
				$csv_data_array[$i]['Debit']='$' . number_format($value,2);
				$csv_data_array[$i]['Credit']="";
				$csv_data_array[$i]["MEMO"]=$key;
			$i++;
			}
			?>
			</tbody>
			</table>
			</div>
			</div>
			<?php

			reporst_csv("Deposit journal invoice.csv",$csv_data_array,"rental_report");




			restore_current_blog();

	
				}




			}////deposit close

	/*********************Open Inovoice report*************************/
			if($_POST['_accounting_report_id']=="Open Invoice Report")
			{

				?>
				<div class="wrap">
				<h2 class="mkbis2">Open Invoice Report</h2>
				<?php
				echo $building=$_POST['_accounting_building_id'];
				echo "*****************";
			$sites = wp_get_sites();
			$all_blogs_id=array();
			$removed_ids=array(1,20,19,6);
			foreach ($sites as $key => $current_blog) {

				if(!in_array($current_blog['blog_id'], $removed_ids))
				{
				array_push($all_blogs_id, $current_blog['blog_id']);
				}
			}

			if($building=="all")
			{

				echo "22222222222*****************";

				// echo "functionality for all";
			$all_data_total=array();
			foreach ($all_blogs_id as $crid) {

			switch_to_blog($crid);
			global $wpdb;
			global $post;
			$start_account=$_POST['_accounting_start_date'];
			$start_acc=explode("-", $start_account);
			$str_yr=$start_acc[0];
			$str_mn=$start_acc[1];
			$str_dy=$start_acc[2];
			$end_account=$_POST['_accounting_end_date'];
			$end_acc=explode("-", $end_account);
			$end_yr=$end_acc[0];
			$end_mn=$end_acc[1];
			$end_dy=$end_acc[2];
			$args = array(
				'post_type' => 'sa_invoice',
				'post_status' => 'partial,publish',
				// 'post_status' => array('publish,partial'),
					// 'date_query' => array(
					// 	array(
					// 		'column' => 'post_modified',
					// 		'after'     => array(
					// 			'year'  => $str_yr,
					// 			'month' => $str_mn,
					// 			'day'   => $str_dy,
					// 		),
					// 		'before'    => array(
					// 			'year'  => $end_yr,
					// 			'month' => $end_mn,
					// 			'day'   => $end_dy,
					// 		),
					// 		'inclusive' => true,
					// 	),
					// ),
				'posts_per_page' => -1,
				);


				$results = get_posts($args);

			$all_data=array();

			$full_total=0;
			foreach ($results as $key => $result) {
	if($result->post_status=="partial" || $result->post_status=="publish")
				{
			$paymentdatefull=explode(" ", $result->post_modified);		
			$paymentdate=$paymentdatefull[0];	
			$invoice_id=$result->ID;
			$client_name=get_the_title(get_post_meta( $invoice_id, '_client_id', true ));
			$total=get_post_meta( $invoice_id, '_total', true );

			$client_id=get_post_meta( $invoice_id, '_client_id', true);
			$user_id=get_post_meta( $client_id, '_associated_users', true);
			$yl_ms_args = array(
						        'post_type'   => 'lease',
						        'post_status'   => 'publish',
						        'numberposts'  => -1,
						        'meta_query' => array(
						          array(
						            'key' => '_yl_lease_user',
						            'value'   => $user_id,
						            'compare' => '='
						          )
						        )
						      );
			 $suite_number="";
		      $posts = get_posts($yl_ms_args);
		      foreach ($posts as $key2 => $value) {
		      	# code...
		      	$lid=$value->ID;
		      	if(get_post_meta($lid, '_yl_suite_number',true)=='-1')
		      	{
		      		$sutname="Y-Memberships";
		      	}
		      	else{
		      		$sutname=get_post_meta($lid, '_yl_suite_number',true);

		      	}
		      	
		      	$suite_number.="(".$sutname.")";

		      }
			$all_data[$invoice_id]['client_name']=$client_name;
			$all_data[$invoice_id]['suite_number']=$suite_number;
			$all_data[$invoice_id]['num']=$invoice_id;
			$all_data[$invoice_id]['total']='$' . number_format($total);
			$all_data[$invoice_id]['date']=$paymentdate;

			$all_data_total[$invoice_id]['client_name']=$client_name;
			$all_data_total[$invoice_id]['suite_number']=$suite_number;
			$all_data_total[$invoice_id]['num']=$invoice_id;
			$all_data_total[$invoice_id]['total']='$' . number_format($total);
			$all_data_total[$invoice_id]['date']=$paymentdate;

			
			$full_total=(float) ($full_total+$total);
				# code...
			}

			}
			?>
			<style type="text/css" media="screen">
					.mk_account_reports table{
							border-collapse: collapse;
							margin: 10px 0;
						}
					.mk_account_reports	th{
							font-weight: normal;
							border: 1px solid #D4D4D4;
							padding: 3px;
							text-align: left;
						}
					.mk_account_reports	td{
							border: 1px solid #D4D4D4;
							}
			</style>
			<div class="mk_account_reports">
				
			<table cellpadding="0" cellspacing="0" border="0" width="100%">
			<thead>
				<tr>
					<th></th>
					<th>Type</th>
					<th>Date</th>
					<th>Num</th>
					<th>Class</th>
					<th>Open Balance</th>
					<!-- <th></th> -->

				</tr>
			</thead>
			<tbody>
			<?php
			foreach ($all_data as $key => $value) {
				?>
			<tr>
			<td><?php echo $value['client_name'] ?></td>
			<td></td>
			<td><?php echo $value['date'] ?></td>
			<td><?php echo $key ?></td>
			<td><?php echo $value['suite_number'] ?></td>
			<td><?php $value['total']; ?></td>
			
			</tr>
				<?php
			}
			?>
			<tr>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td>Total</td>
			<td><?php echo $full_total ?></td>
			
			</tr>
			</tbody>
			</table>
			</div>
			</div>
			<?php


			restore_current_blog();
		



}


reporst_csv("Open invoice.csv",$all_data,'open_invoice');
}
else{
				// echo "3333333333333333333*****************";

// echo $building;
			$sites = wp_get_sites();

// }
			switch_to_blog($building);


			global $wpdb;
			global $post;
			$start_account=$_POST['_accounting_start_date'];
			$start_acc=explode("-", $start_account);
			$str_yr=$start_acc[0];
			$str_mn=$start_acc[1];
			$str_dy=$start_acc[2];
			$end_account=$_POST['_accounting_end_date'];
			$end_acc=explode("-", $end_account);
			$end_yr=$end_acc[0];
			$end_mn=$end_acc[1];
			$end_dy=$end_acc[2];
			$args = array(
				'post_type' => 'sa_invoice',
				'post_status' => 'any',
				// 'post_status' => array('partial'),
				'posts_per_page' => -1,
				);

				// $args = array(
				// 	'date_query' => array(
				// 		array(
				// 			'after'     => 'January 1st, 2013',
				// 			'before'    => array(
				// 				'year'  => 2013,
				// 				'month' => 2,
				// 				'day'   => 28,
				// 			),
				// 			'inclusive' => true,
				// 		),
				// 	),
				// 	'posts_per_page' => -1,
				// );

				$results = get_posts($args);
				// print_r($results);

			$all_data=array();

			$full_total=0;
			foreach ($results as $key => $result) {
				if($result->post_status=="partial" || $result->post_status=="publish")
				{
				// echo "<pre>";
				// print_r($result);
				// echo "</pre>";
				// exit();
			$paymentdatefull=explode(" ", $result->post_modified);		
			$paymentdate=$paymentdatefull[0];	
			 $invoice_id=$result->ID;
			 $client_name=get_the_title(get_post_meta( $invoice_id, '_client_id', true ));
				$total=get_post_meta( $invoice_id, '_total', true );

			 $client_id=get_post_meta( $invoice_id, '_client_id', true);
				$user_id=get_post_meta( $client_id, '_associated_users', true);
			 $yl_ms_args = array(
						        'post_type'   => 'lease',
						        'post_status'   => 'publish',
						        'numberposts'  => -1,
						        'meta_query' => array(
						          array(
						            'key' => '_yl_lease_user',
						            'value'   => $user_id,
						            'compare' => '='
						          )
						        )
						      );
			 $suite_number="";
		      $posts = get_posts($yl_ms_args);
		      foreach ($posts as $key2 => $value) {
		      	# code...
		      	$lid=$value->ID;
		      	if(get_post_meta($lid, '_yl_suite_number',true)=='-1')
		      	{
		      		$sutname="Y-Memberships";
		      	}
		      	else{
		      		$sutname=get_post_meta($lid, '_yl_suite_number',true);

		      	}
		      	
		      	$suite_number.="(".$sutname.")";

		      }
		      // $invoice_num='"=HYPERLINK(""'.get_permalink($invoice_id).'"",)"';
		      $invoice_num='=HYPERLINK("'.get_permalink($invoice_id).'", "'.$invoice_id.'")';


			 // $leasd_id= get_post_meta( $invoice_id, '_yl_lease_id', true );
			 // $suite_number=get_post_meta($leasd_id, '_yl_suite_number', true );
			$all_data[$invoice_id]['client_name']=$client_name;
			$all_data[$invoice_id]['suite_number']=$suite_number;
			$all_data[$invoice_id]['num']=$invoice_num;
			$all_data[$invoice_id]['total']='$' . number_format($total);
			$all_data[$invoice_id]['date']=$paymentdate;
			$full_total=(float) ($full_total+$total);
				# code...
			}
		}
			// $all_week_data2=array();
// print_r($all_data);
			// print_r($final_data_table);
			?>
			<style type="text/css" media="screen">
					.mk_account_reports table{
							border-collapse: collapse;
							margin: 10px 0;
						}
					.mk_account_reports	th{
							font-weight: normal;
							border: 1px solid #D4D4D4;
							padding: 3px;
							text-align: left;
						}
					.mk_account_reports	td{
							border: 1px solid #D4D4D4;
							}
			</style>
			<div class="mk_account_reports">
				
			<table cellpadding="0" cellspacing="0" border="0" width="100%">
			<thead>
				<tr>
					<th></th>
					<th>Type</th>
					<th>Date</th>
					<th>Num</th>
					<th>Class</th>
					<th>Open Balance</th>
					<!-- <th></th> -->

				</tr>
			</thead>
			<tbody>
			<?php
			foreach ($all_data as $key => $value) {
				?>
			<tr>
			<td><?php echo $value['client_name'] ?></td>
			<td></td>
			<td><?php echo $value['date'] ?></td>
			<td><?php echo $key ?></td>
			<td><?php echo $value['suite_number'] ?></td>
			<td><?php $value['total']; ?></td>
			<!-- <td></td> -->
			
			</tr>
				<?php
				# code...
			}
			?>
			<tr>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td>Total</td>
			<td><?php echo $full_total ?></td>
			<!-- <td></td> -->
			
			</tr>
			</tbody>
			</table>
			</div>
			</div>
			<?php
			// echo $final_data_table['all_dates']['2016-04-26'];
			// echo "</pre>";
			// echo "<pre>";

														// 				global $wpdb;
														// 	$query = array(
														//     'post_type' => 'sa_invoice',
														//      'posts_per_page' => -1,
														//     'post_status' =>  array('complete'),
														//      // 'orderby' => 'meta_value',
														//  	'order' => 'ASC',
														//     'meta_query' => array(
														// 				// 'relation' => 'AND',
														// 		    	    array(
														//     	    			    'key' => '_due_date',
														// 				            'value' => '2016-04-28',
														// 			                'compare' => '<=',
														// 						    'type'    => 'Date'
														// 			  			 ),
														// 		    	  //   array(
														//     	    // 			    'key' => '_client_id',
														// 				     //        'value' => in_array( $client_id, '_client_id') ,
														// 			      //           // 'compare' => '=',
														// 						   //  // 'type'    => 'Date'
														// 			  			 // ),
														// 		    	  //   )
														// 					 );	   
														// );
														// $invoice_id=array();
														// $loopre = new WP_Query($query);
														// // echo "checkpoint1";

														// while ( $loopre->have_posts() ) : $loopre->the_post();
														// 	the_id();

														// endwhile;



			reporst_csv("Open invoice.csv",$all_data,'open_invoice');



			restore_current_blog();
		}

	}////openinvoice close


/********Rent Roll Report Working********/
	if($_POST['_accounting_report_id']=="Rent Roll Report")
			{


		// echo "still in process";



			$building=$_POST['_accounting_building_id'];

			
			$sites = wp_get_sites();


			$all_blogs_id=array();
			$removed_ids=array(1,20,19,6);
			foreach ($sites as $key => $current_blog) {

				if(!in_array($current_blog['blog_id'], $removed_ids))
				{
				array_push($all_blogs_id, $current_blog['blog_id']);
				}
			}
				$csv_data_array=array();

			if($building=="all")
			{
				$i=1;
				foreach ($all_blogs_id as  $crid) {


			switch_to_blog($crid);


			global $wpdb;
			global $post;
			$start_account=$_POST['_accounting_start_date'];
			$start_acc=explode("-", $start_account);
			$str_yr=$start_acc[0];
			$str_mn=$start_acc[1];
			$str_dy=$start_acc[2];
			$end_account=$_POST['_accounting_end_date'];
			$end_acc=explode("-", $end_account);
			$end_yr=$end_acc[0];
			$end_mn=$end_acc[1];
			$end_dy=$end_acc[2];

							$start_Account2=strtotime($start_account);
							$end_Account2=strtotime($end_account);
							$args = array(
						    'post_type' => 'sa_invoice',
						    'posts_per_page' => -1,
						    'orderby' => 'meta_value',
						    'order' => 'ASC',
						    'meta_key' => '_due_date',
						    'meta_query' => array(
						        array(
						            'key' => '_due_date',
						            'value' => array($start_Account2, $end_Account2),
						            'compare' => 'BETWEEN',
						            'type' => 'Numeric'
						        )
						    )
						);
			$results = get_posts($args);
			$all_data=array();

			$full_total=0;


		
			foreach ($results as $key => $result) {
			$paymentdatefull=explode(" ", $result->post_modified);		
			$paymentdate=$paymentdatefull[0];	
			$invoice_id=$result->ID;
			$client_name=get_the_title(get_post_meta( $invoice_id, '_client_id', true ));
			$total=get_post_meta( $invoice_id, '_total', true );

			 $client_id=get_post_meta( $invoice_id, '_client_id', true);
			 $user_id=get_post_meta( $client_id, '_associated_users', true);
			 $yl_ms_args = array(
						        'post_type'   => 'lease',
						        'post_status'   => 'publish',
						        'numberposts'  => -1,
						        'meta_query' => array(
						          array(
						            'key' => '_yl_lease_user',
						            'value'   => $user_id,
						            'compare' => '='
						          )
						        )
						      );
			 $suite_number="";
		      $posts = get_posts($yl_ms_args);
		      foreach ($posts as $key2 => $value2) {
		      	# code...
		      	$lid=$value2->ID;
		      		      	if(get_post_meta($lid, '_yl_suite_number',true)=='-1')
		      	{
		      		$sutname="Y-Memberships";
		      	}
		      	else{
		      		$sutname=get_post_meta($lid, '_yl_suite_number',true);
		      		
		      	}
		      	
		      	$suite_number.="(".$sutname.")";

		      }
			 // $leasd_id= get_post_meta( $invoice_id, '_yl_lease_id', true );
			 // $suite_number=get_post_meta($leasd_id, '_yl_suite_number', true );
			$all_data[$invoice_id]['client_name']=$client_name;
			$all_data[$invoice_id]['suite_number']=$suite_number;
			$all_data[$invoice_id]['num']=$invoice_id;
			$all_data[$invoice_id]['totalmk']=$total;
			$all_data[$invoice_id]['date']=$paymentdate;
			$all_data[$invoice_id]['line_items']=maybe_unserialize(get_post_meta($invoice_id, '_doc_line_items', true ));
			$full_total=(float) ($full_total+$total);
				# code...
			// print_r($all_data[$invoice_id]['line_items']);
			// exit();

	
			}
			


			?>
						<style type="text/css" media="screen">
					.mk_account_reports table{
							border-collapse: collapse;
							margin: 10px 0;
						}
					.mk_account_reports	th{
							font-weight: normal;
							border: 1px solid #D4D4D4;
							padding: 3px;
							text-align: center;
						}
					.mk_account_reports	td{
							border: 1px solid #D4D4D4;
							}
			</style>
				<div class="wrap">
		<h2 class="mkbis2">Rent Roll Reports</h2>
		<div class="mk_account_reports">
				
			<table cellpadding="0" cellspacing="0" border="0" width="100%">
			<thead>
				<tr>
					<th>       </th>
					<th>Cable</th>
					<th>Copies</th>
					<th>Fax Line</th>
					<th>Fees</th>
					<th>Fax Service</th>
					<!-- <th>Fax Service</th> -->
					<th>Long Distance</th>
					<th>Phone Line</th>
					<!-- <th>Tenant Improvement</th> -->
					<th>Postage</th>
					<th>Rent</th>
					<!-- <th>Postage (Postage)</th> -->
					<th>Storage Unit </th>
					<th>Tenant Improvement</th>
					<th>Total Service</th>
					<!-- <th>Total Other Charges</th> -->
					<th>Fin Chg </th>
					<th>NSF Fee </th>
					<th>Total Other Charges</th>
					<th>TOTAL</th>

				</tr>
			</thead> 
			<tbody>
			<?php

			foreach ($all_data as $key => $value) {
				// echo $key;
				// echo "<br>";
				$cable=0;
				$Copies=0;
				$FaxLine=0;
				$Fees=0;
				$FaxService=0;
				// $FaxService=0;
				$longsitancs=0;
				$phoneline=0;
				$postage=0;
				$rent=0;
				$stroage=0;
				$tenanet=0;
				$totalservice=0;
				$fin=0;
				$nsf=0;
				$totalother=0;
				$Total=$value['totalmk'];
				foreach ($value['line_items'] as $line => $lineitem) {
					// $Total=$Total+$lineitem['total']


					if (strpos($lineitem['desc'], 'Cable') !== false) {
					$cable=(float) ($cable+$lineitem['total']);
					$totalservice=(float) ($totalservice+$lineitem['total']);

					}
					elseif (strpos($lineitem['desc'], 'Copies') !== false) {
					$Copies=(float) ($Copies+$lineitem['total']);
					$totalservice=(float) ($totalservice+$lineitem['total']);

					}
					elseif (strpos($lineitem['desc'], 'Fax Line') !== false) {
					$FaxLine=(float) ($FaxLine+$lineitem['total']);
					$totalservice=(float) ($totalservice+$lineitem['total']);

					}
					elseif (strpos($lineitem['desc'], 'Late Fees') !== false  || strpos($lineitem['desc'], 'merchant fees') !== false) {
					$Fees=(float) ($Fees+$lineitem['total']);
					$totalservice=(float) ($totalservice+$lineitem['total']);

					}
					elseif (strpos($lineitem['desc'], 'Fax Service') !== false) {
					$FaxService=(float) ($FaxService+$lineitem['total']);
					$totalservice=(float) ($totalservice+$lineitem['total']);

					}
					elseif (strpos($lineitem['desc'], 'Long Distance') !== false) {
					$longsitancs=(float) ($longsitancs+$lineitem['total']);
					$totalservice=(float) ($totalservice+$lineitem['total']);

					}
					elseif (strpos($lineitem['desc'], 'Phone Line') !== false) {
					$phoneline=(float) ($phoneline+$lineitem['total']);
					$totalservice=(float) ($totalservice+$lineitem['total']);

					}
					elseif (strpos($lineitem['desc'], 'Postage') !== false) {
					$postage=(float) ($postage+$lineitem['total']);
					$totalservice=(float) ($totalservice+$lineitem['total']);

					}
					elseif (strpos($lineitem['desc'], 'Rent') !== false) {
					$rent=(float) ($rent+$lineitem['total']);
					$totalservice=(float) ($totalservice+$lineitem['total']);

					}
					elseif (strpos($lineitem['desc'], 'Storage Unit') !== false) {
					$stroage=(float) ($stroage+$lineitem['total']);
					$totalservice=(float) ($totalservice+$lineitem['total']);

					}
					elseif (strpos($lineitem['desc'], 'Tenant Improvement') !== false) {
					$tenanet=(float) ($tenanet+$lineitem['total']);
					$totalservice=(float) ($totalservice+$lineitem['total']);

					}
					elseif (strpos($lineitem['desc'], 'Fin Chg') !== false) {
					$fin=(float) ($fin+$lineitem['total']);
					$totalother=(float) ($totalother+$lineitem['total']);

					}
					elseif (strpos($lineitem['desc'], 'NSF Fee') !== false) {
					$nsf=(float) ($nsf+$lineitem['total']);
					$totalother=(float) ($totalother+$lineitem['total']);

					}
					// else{
					// }
					$Total=(float) ($totalother+$totalservice);
					
			}?>
				<tr>

						<td><?php echo $value['suite_number']?></td>
						<td><?php echo $cable ?></td>
						<td><?php echo $Copies ?></td>
						<td><?php echo $FaxLine ?></td>
						<td><?php echo $Fees ?></td>
						<td><?php echo $FaxService ?></td>
						<td><?php echo $longsitancs ?></td>
						<td><?php echo $phoneline ?></td>
						<td><?php echo $postage ?></td>
						<td><?php echo $rent ?></td>
						<td><?php echo $stroage ?></td>
						<td><?php echo $tenanet ?></td>
						<td><?php echo $totalservice ?></td>
						<td><?php echo $fin ?></td>
						<td><?php echo $nsf ?></td>
						<td><?php echo $totalother ?></td>
						<td><?php echo $Total ?></td>

					</tr>


					<?php
				// }
					$csv_data_array[$i]['Suite Number']=$value['suite_number'];
					$csv_data_array[$i]['Cable']='$' . number_format($cable);
					$csv_data_array[$i]['Copies']='$' . number_format($Copies);
					$csv_data_array[$i]['Fax Line']='$' . number_format($FaxLine);
					$csv_data_array[$i]['Fees']='$' . number_format($Fees);
					$csv_data_array[$i]['Fax Service']='$' . number_format($FaxService);
					$csv_data_array[$i]['Long Distance']='$' . number_format($longsitancs);
					$csv_data_array[$i]['Phone Line']='$' . number_format($phoneline);
					$csv_data_array[$i]['Postage']='$' . number_format($Postage);
					$csv_data_array[$i]['Rent']='$' . number_format($rent);
					$csv_data_array[$i]['Storage Unit']='$' . number_format($stroage);
					$csv_data_array[$i]['Tenant Improvement']='$' . number_format($tenanet);
					$csv_data_array[$i]['Total Service']='$' . number_format($totalservice);
					$csv_data_array[$i]['Fin Chg ']='$' . number_format($fin);
					$csv_data_array[$i]['NSF Fee']='$' . number_format($nsf);
					$csv_data_array[$i]['Total Other Charges']='$' . number_format($totalother);
					$csv_data_array[$i]['TOTAL']='$' . number_format($Total);

					$i++;
		}
	

			// echo "<pre>";
			// print_r($final_data_table);
			?>
					<style type="text/css" media="screen">
							.mk_account_reports table{
									border-collapse: collapse;
									margin: 10px 0;
								}
							.mk_account_reports	th{
									font-weight: normal;
									border: 1px solid #D4D4D4;
									padding: 3px;
									text-align: left;
								}
							.mk_account_reports	td{
									border: 1px solid #D4D4D4;
									}
					</style>
					

					</tbody>
					</table>
					</div>
					</div>
			<?php


				}
				reporst_csv("Rent report invoice.csv",$csv_data_array,"rental_report");


			}
			

			else{



			switch_to_blog($building);


			global $wpdb;
			global $post;
			echo  $start_account=$_POST['_accounting_start_date'];echo "<br>";
			$start_acc=explode("-", $start_account);
			$str_yr=$start_acc[0];
			$str_mn=$start_acc[1];
			$str_dy=$start_acc[2];
			echo  $end_account=$_POST['_accounting_end_date'];echo "<br>";
			$end_acc=explode("-", $end_account);
			$end_yr=$end_acc[0];
			$end_mn=$end_acc[1];
			$end_dy=$end_acc[2];
			// $args = array(
			// 	'post_type' => 'sa_invoice',
			// 	// 'post_status' => 'publish',
			// 		'date_query' => array(
			// 			array(
			// 				'column' => 'post_modified',
			// 				'after'     => array(
			// 					'year'  => $str_yr,
			// 					'month' => $str_mn,
			// 					'day'   => $str_dy,
			// 				),
			// 				'before'    => array(
			// 					'year'  => $end_yr,
			// 					'month' => $end_mn,
			// 					'day'   => $end_dy,
			// 				),
			// 				'inclusive' => true,
			// 			),
			// 		),
			// 	'posts_per_page' => -1,
			// 	);


							$start_Account2=strtotime($start_account);
							$end_Account2=strtotime($end_account);
							$args = array(
						    'post_type' => 'sa_invoice',
						    'post_status' => 'any',
						    'posts_per_page' => -1,
						    'orderby' => 'meta_value',
						    'order' => 'ASC',
						    'meta_key' => '_due_date',
						    'meta_query' => array(
						        array(
						            'key' => '_due_date',
						            'value' => array($start_Account2, $end_Account2),
						            'compare' => 'BETWEEN',
						            'type' => 'Numeric'
						        )
						    )
						);

					

				// $args = array(
				// 	'date_query' => array(
				// 		array(
				// 			'after'     => 'January 1st, 2013',
				// 			'before'    => array(
				// 				'year'  => 2013,
				// 				'month' => 2,
				// 				'day'   => 28,
				// 			),
				// 			'inclusive' => true,
				// 		),
				// 	),
				// 	'posts_per_page' => -1,
				// );

			$results = get_posts($args);

 /* echo "<pre>";
  		print_r($results);
  	 echo "</pre>";	*/
			
			$all_data=array();

			$full_total=0;
	 
			foreach ($results as $key => $result) {
				// print_r($result);
				// exit();
			$paymentdatefull=explode(" ", $result->post_modified);		
			$paymentdate=$paymentdatefull[0];	
			$invoice_id=$result->ID;
			$client_name=get_the_title(get_post_meta( $invoice_id, '_client_id', true ));
			// $company_id=yl_get_company_id_by_invoice_id($invoice_id);
			$leasd_id =	get_post_meta($invoice_id, '_yl_lease_id',true);
			$company_id=	get_post_meta($leasd_id, '_yl_company_name', true);
			$company_name=get_the_title( $company_id );
			$total=get_post_meta( $invoice_id, '_total', true );

			 $client_id=get_post_meta( $invoice_id, '_client_id', true);
			 // echo "<br>";

			 $user_id=get_post_meta( $client_id, '_associated_users', true);
			 $yl_ms_args = array(
						        'post_type'   => 'lease',
						        'post_status'   => 'publish',
						        'numberposts'  => -1,
						        'meta_query' => array(
						          array(
						            'key' => '_yl_lease_user',
						            'value'   => $user_id,
						            'compare' => '='
						          )
						        )
						      );
			 $suite_number="";
		      // $posts = get_posts($yl_ms_args);
		      // foreach ($posts as $key2 => $value2) {
		      // 	# code...
		      // 	$lid=$value2->ID;
		      // 		      	if(get_post_meta($lid, '_yl_suite_number',true)=='-1')
		      // 	{
		      // 		$sutname="Y-Memberships";
		      // 	}
		      // 	else{
		      // 		$sutname=get_post_meta($lid, '_yl_suite_number',true);
		      		
		      // 	}
		      	
		      // 	$suite_number.="(".$sutname.")";

		      // }
			 $leasd_id= get_post_meta( $invoice_id, '_yl_lease_id', true );
			 $suite_number=get_post_meta($leasd_id, '_yl_suite_number', true );
   		      	if($suite_number=='-1')
		      	{
		      		$suite_number="Y-Memberships";
		      	}
			      $invoice_num='=HYPERLINK("'.get_permalink($invoice_id).'", "'.$invoice_id.'")';

			$all_data[$invoice_id]['client_name']=$client_name;
			$all_data[$invoice_id]['company_name']=$company_name;
			$all_data[$invoice_id]['invoice_num']=$invoice_num;
			$all_data[$invoice_id]['suite_number']=$suite_number;
			$all_data[$invoice_id]['num']=$invoice_id;
			$all_data[$invoice_id]['totalmk']=$total;
			$all_data[$invoice_id]['date']=$paymentdate;
			$all_data[$invoice_id]['line_items']=maybe_unserialize(get_post_meta($invoice_id, '_doc_line_items', true ));
			$full_total=(float) ($full_total+$total);
				# code...
			// print_r($all_data[$invoice_id]['line_items']);
			// exit();

	
			}

// 			echo "<pre>";
// print_r($all_data);
// echo "</pre>";


			?>
						<style type="text/css" media="screen">
					.mk_account_reports table{
							border-collapse: collapse;
							margin: 10px 0;
						}
					.mk_account_reports	th{
							font-weight: normal;
							border: 1px solid #D4D4D4;
							padding: 3px;
							text-align: center;
						}
					.mk_account_reports	td{
							border: 1px solid #D4D4D4;
							}
			</style>
				<div class="wrap">
		<h2 class="mkbis2">Rent Roll Reports</h2>
		<div class="mk_account_reports">
				
			<table cellpadding="0" cellspacing="0" border="0" width="100%">
			<thead>
				<tr>
					<th>  Suite Number     </th>
					<th>Tennant Name</th>
					<th>Rent</th>
					<th>Aux Charges</th>
					<!-- <th>TOTAL</th> -->
<!-- 					<th>Fax Service</th>
					<th>Long Distance</th>
					<th>Phone Line</th>
					<th>Postage</th>
					<th>Rent</th>
					<th>Postage (Postage)</th>
					<th>Storage Unit </th>
					<th>Tenant Improvement</th>
					<th>Total Service</th>
					<th>Fin Chg </th>
					<th>NSF Fee </th>
					<th>Total Other Charges</th>
					<th>TOTAL</th> -->
<!-- 					['Suite Number']
['Tennant Name']
['Rent']='$' . n
['Aux Charges']=
['TOTAL']='$' .  -->

				</tr>
			</thead> 
			<tbody>
			<?php
				$csv_data_array=array();
				$csv_data_array2=array();
				$i=1;
				$suite_array=array();
				$suite_names=array();
				$the_total=0;
				// echo "<pre>";
				// print_r($all_data);
				// echo "<pre>";
			foreach ($all_data as $key => $value) {
				// echo "<pre>";
				// print_r($key);
				// echo "</pre>";
				 // $client_id =	get_post_meta($key, '_client_id',true);
				 $leasd_id =	get_post_meta($key, '_yl_lease_id',true);  														 
				 $suite_id=	get_post_meta($leasd_id, '_yl_product_id',true);
				 // echo "Lease ID".$leasd_id;
				 // echo "<br>";
				 // echo "Suite id".$suite_id;
				 // // $existing_invoice = SI_Invoice::get_instance($key);
					// // echo $existing_invoice->get_project_id(  );

				 
				 // echo "<br>";
			// echo "string";

				// echo $key;
				// echo "<br>";
				$cable=0;
				$Copies=0;
				$FaxLine=0;
				$Fees=0;
				$FaxService=0;
				// $FaxService=0;
				$longsitancs=0;
				$phoneline=0;
				$postage=0;
				$rent=0;
				$stroage=0;
				$tenanet=0;
				$totalservice=0;
				$fin=0;
				$nsf=0;
				$totalother=0;
				$Total=$value['totalmk'];
				$Total3=0;
				$Total2=$value['totalmk'];
				$invoice_num=$value['invoice_num'];
				$monthlyrent=0;
				$auxrent=0;
				$total_rent_new=0;

				foreach ($value['line_items'] as $line => $lineitem) {
					// $Total=$Total+$lineitem['total']
$the_total=$the_total+$lineitem['total'];
echo "********".$key."**** ";
echo $the_total;

echo "<br>";

				// echo $lineitem['desc']."=".$lineitem['total'];
					// echo "</br>";

					if (strpos($lineitem['desc'], 'Monthly Rent') !== false) {
					$monthlyrent=(float) ($monthlyrent+$lineitem['total']);
					$suiter_name=explode("for ",$lineitem['desc'] );
					$suite_name_mk=$suiter_name[1];
					array_push($suite_names, $suite_name_mk);
					$suite_array[$suite_name_mk]['month_rent']=$lineitem['total'];
					$suite_array[$suite_name_mk]['total_rent']=$lineitem['total'];
					$suite_array[$suite_name_mk]['client_name']=$value['client_name'];
					$suite_array[$suite_name_mk]['company_name']=$value['company_name'];
					$suite_array[$suite_name_mk]['suite_id']=$suite_id;


					


					// $client_name
					// $totalservice=(float) ($totalservice+$lineitem['total']);

					}
					else{
					// end($suite_array);         // move the internal pointer to the end of the array
					// echo $suite_name_mk = key($suite_array); 
					// echo "==";
					 $auxrent=(float) ($auxrent+$lineitem['total']);
					// echo "==";
					 $total_rent_new=(float) ($total_rent_new+$lineitem['total']);
					// echo "<br>";
					// $suite_array[$suite_name_mk]['aux_rent']=(float) ($suite_array[$suite_name_mk]['aux_rent']+$lineitem['total']);
					// $suite_array[$suite_name_mk]['total_rent']=(float) ($suite_array[$suite_name_mk]['total_rent']+$lineitem['total']);


					}

					$Total3=(float) ($Total3+$lineitem['total']);
					
			}

					end($suite_array);         // move the internal pointer to the end of the array
					$suite_name_mk = key($suite_array); 
					// $auxrent=(float) ($auxrent+$lineitem['total']);
					// $total_rent_new=(float) ($total_rent_new+$lineitem['total']);
					$suite_array[$suite_name_mk]['aux_rent']=$auxrent;
					$suite_array[$suite_name_mk]['total_rent']=(float) ($suite_array[$suite_name_mk]['total_rent']+$total_rent_new);








					$i++;
		}
// 		echo "<pre>";
// print_r($suite_array);
// echo "<pre>";
$x=1;
$total1=0;
$total2=0;
foreach ($suite_array as $suitename => $sut) {
	$replace = array("</p>", "lease");
$with   = array("");
					$csv_data_array2[$x]['Suite Number']=str_replace($replace, $with, $suitename);
					$csv_data_array2[$x]['Tennant Name']=$sut['company_name'];
					$csv_data_array2[$x]['Rent']='$' . number_format($sut['month_rent'],2);
					$csv_data_array2[$x]['Aux Charges']='$' . number_format($sut['aux_rent'],2);

					$csv_data_array2[$x]['TOTAL']='$' . number_format($sut['total_rent'],2);
					$suite_id=$sut['suite_id'];

					$total1+=number_format($sut["total_rent"],2);
					$total2+=number_format($sut['aux_rent'],2);
					?>

					<tr>



						<td><?php echo str_replace($replace, $with, $suitename); ?></td>
						<td><?php echo $sut['company_name']; ?></td>
						<td><a onclick="submitResources(<?php echo $suite_id;  ?>,'<?php echo $start_account; ?>','<?php echo $end_account; ?>',<?php  echo $building; ?>	);" href="javascript:void(0);" title="#">$<?php echo number_format($sut["total_rent"],2); ?></a></td>
						<td><a onclick="submitResourcesauxrent(<?php echo $suite_id;  ?>,'<?php echo $start_account; ?>','<?php echo $end_account; ?>',<?php  echo $building; ?>	);" href="javascript:void(0);" title="#"><?php echo '$' . number_format($sut['aux_rent'],2); ?></a></td>
						<!-- <td></td> -->
					</tr>


					<?php
					// $csv_data_array[$i]['Invoice Link']=$invoice_num;
	# code...
$x++;
}


echo $total1."=======".$total2; echo "<br>";
echo $total=$total1+$total2;
echo "<br>";
echo "the total ".$the_total;
// echo "Total Rent roll reports =".$x;

		// echo "<pre>";
		// print_r($csv_data_array);
		// echo "</pre>";
			// reporst_csv("Rent report invoice.csv",$csv_data_array2,"rental_report");

			// echo "<pre>";
			// print_r($final_data_table);
			?>
					<style type="text/css" media="screen">
							.mk_account_reports table{
									border-collapse: collapse;
									margin: 10px 0;
								}
							.mk_account_reports	th{
									font-weight: normal;
									border: 1px solid #D4D4D4;
									padding: 3px;
									text-align: left;
								}
							.mk_account_reports	td{
									border: 1px solid #D4D4D4;
									}
					</style>
					

					</tbody>
					</table>
					</div>
					</div>
			<!-- 		<div class="export_data">
					<a onclick="submitResources('1','2','3');" href="javascript:void(0);" title="#">Export Csv</a>
						
					</div> -->
			<?php
			// echo $final_data_table['all_dates']['2016-04-26'];
			// echo "</pre>";
			// echo "<pre>";

														// 				global $wpdb;
														// 	$query = array(
														//     'post_type' => 'sa_invoice',
														//      'posts_per_page' => -1,
														//     'post_status' =>  array('complete'),
														//      // 'orderby' => 'meta_value',
														//  	'order' => 'ASC',
														//     'meta_query' => array(
														// 				// 'relation' => 'AND',
														// 		    	    array(
														//     	    			    'key' => '_due_date',
														// 				            'value' => '2016-04-28',
														// 			                'compare' => '<=',
														// 						    'type'    => 'Date'
														// 			  			 ),
														// 		    	  //   array(
														//     	    // 			    'key' => '_client_id',
														// 				     //        'value' => in_array( $client_id, '_client_id') ,
														// 			      //           // 'compare' => '=',
														// 						   //  // 'type'    => 'Date'
														// 			  			 // ),
														// 		    	  //   )
														// 					 );	   
														// );
														// $invoice_id=array();
														// $loopre = new WP_Query($query);
														// // echo "checkpoint1";

														// while ( $loopre->have_posts() ) : $loopre->the_post();
														// 	the_id();

														// endwhile;








	













			}






			restore_current_blog();

	

				
			}

	if($_POST['_accounting_report_id']=="Rent Roll Report New")
			{
			// echo "New Rent Report";
			$building=$_POST['_accounting_building_id'];

			switch_to_blog($building);


			global $wpdb;
			global $post;
		 	$start_account=$_POST['_accounting_start_date'];
			$start_acc=explode("-", $start_account);
			$str_yr=$start_acc[0];
			$str_mn=$start_acc[1];
			$str_dy=$start_acc[2];
			$end_account=$_POST['_accounting_end_date'];
			$end_acc=explode("-", $end_account);
			$end_yr=$end_acc[0];
			$end_mn=$end_acc[1];
			$end_dy=$end_acc[2];
							$start_Account2=strtotime($start_account);
							$end_Account2=strtotime($end_account);
							$args = array(
						    'post_type' => 'suites',
						    'post_status' => 'publish',
						    'posts_per_page' => -1,
						    'order' => 'ASC',
						);
			$results = get_posts($args);
			/*echo "<pre>";
			print_r($results);
			echo "<pre>";*/
			$all_invoice_id_checker=array();
			$all_invoice_id_checker22=array();
			$mkk_total22=0;
			$all_data=array();
			$the_total=0;
			$the_real_total=0;
			$multichecker_real_total=0;
			$multichecker_real_total2=0;
			$temp_total_multi=0;

			$full_total=0;
			$k=0;
			foreach ($results as $key => $result) {
			$paymentdatefull=explode(" ", $result->post_modified);		
			$paymentdate=$paymentdatefull[0];	
			$suite_id=$result->ID;
			$suite_number=get_the_title( $suite_id );
							 $yl_ms_args = array(
										        'post_type'   => 'lease',
										        'post_status'   => 'publish',
										        'numberposts'  => -1,
										        'order' => 'ASC',
										        'meta_query' => array(
										          array(
										            'key' => '_yl_suite_number',
										            'value'   => $suite_number,
										            'compare' => '='
										          )
										        )
										      );

							$lease_results = get_posts($yl_ms_args);
							/*echo "<pre>";
							print_r($lease_results);
							echo "</pre>";*/
							$leases_ids=array();
								$invoice_ids=array();

							foreach ($lease_results as  $lease_result) {
								$in_ms_args = array(
										        'post_type'   => 'sa_invoice',
										        'post_status'   => 'any',
										        'posts_per_page'  => -1,
										        'order' => 'ASC',
										        'meta_query' => array(
										          array(
										            'key' => '_yl_lease_id',
										            'value'   => $lease_result->ID,
										            'compare' => '='
										          )
										        )
										      );	
								array_push($leases_ids, $lease_result->ID);

								$invoice_results = get_posts($in_ms_args);
								/*echo "<pre>";
								print_r($invoice_results);
								echo "</pre>";*/

							// exit();
								if($invoice_results!="")
								{	


									$k=0;
								foreach ($invoice_results as  $invoice_result) {
									// echo $invoice_result->ID;
									$due_date=get_post_meta( $invoice_result->ID, '_due_date', true );
									// echo "Due date".$due_date;
									// echo "start date".$start_Account2;
									// echo "end date".$end_Account2;

									if($due_date>=$start_Account2 && $due_date<=$end_Account2 )
									{
										if(!in_array($invoice_result->ID, $all_invoice_id_checker))
										{
											array_push($all_invoice_id_checker, $invoice_result->ID);
										}
										// echo "reached";
										// echo "string";
										// exit();
										/***Invoice Data*****/
													$invoice_id=$invoice_result->ID;
													$client_name=get_the_title(get_post_meta( $invoice_id, '_client_id', true ));
													// $company_id=yl_get_company_id_by_invoice_id($invoice_id);
													$leasd_id =	get_post_meta($invoice_id, '_yl_lease_id',true);
													$company_id=	get_post_meta($leasd_id, '_yl_company_name', true);
													$company_name=get_the_title( $company_id );
													$total=get_post_meta( $invoice_id, '_total', true );
													$client_id=get_post_meta( $invoice_id, '_client_id', true);
													$client_name=get_the_title(get_post_meta( $invoice_id, '_client_id', true ));
													$suite_number="";
													$leasd_id= get_post_meta( $invoice_id, '_yl_lease_id', true );
													$suite_number=get_post_meta($leasd_id, '_yl_suite_number', true );
													if($suite_number=='-1')
													{
													$suite_number="Y-Memberships";
													}
													$invoice_num='=HYPERLINK("'.get_permalink($invoice_id).'", "'.$invoice_id.'")';

													$invoice_ids[$invoice_id]['client_name']=$client_name;
													$invoice_ids[$invoice_id]['company_name']=$company_name;
													$invoice_ids[$invoice_id]['invoice_num']=$invoice_num;
													$invoice_ids[$invoice_id]['suite_number']=$suite_number;
													$invoice_ids[$invoice_id]['num']=$invoice_id;
													$invoice_ids[$invoice_id]['totalmk']=$total;
													$invoice_ids[$invoice_id]['date']=$paymentdate;
													$invoice_ids[$invoice_id]['line_items']=maybe_unserialize(get_post_meta($invoice_id, '_doc_line_items', true ));

													
												//	$full_total=(float) ($full_total+$total);


									}

								
										/********************/
									// echo "In range".$invoice_result->ID;
									// array_push($invoice_ids, $invoice_result->ID);
									# code...

									}
// 										echo "<pre>";
// print_r($invoice_ids);
// echo "</pre>";
								
								}

							}




							// echo "<pre>";
							// print_r($lease_results);
							// echo "<pre>";
							$all_data[$suite_id]['leases']=$leases_ids;
							$all_data[$suite_id]['invoices']=$invoice_ids;
/*echo "<pre>";
print_r($all_data[$suite_id]['invoices']);
echo "</pre>";*/


           

			}



// echo "<pre>";
// print_r($all_data);
// echo "</pre>";
				/****Foreachmain*****/

			//Multisuite issue
							$start_Account2=strtotime($start_account);
							$end_Account2=strtotime($end_account);
							$args = array(
							'post_type' => 'sa_invoice',
						    'post_status' => 'any',

							'posts_per_page' => -1,
							'orderby' => 'meta_value',
							'order' => 'ASC',
							'meta_key' => '_due_date',
							'meta_query' => array(
							array(
							'key' => '_due_date',
							'value' => array($start_Account2, $end_Account2),
							'compare' => 'BETWEEN',
							'type' => 'Numeric'
							)
							)
							);

							$multisuite_invoices_loop = get_posts($args);

foreach ($multisuite_invoices_loop as $aa => $bb) {

	# code...
	array_push($all_invoice_id_checker22, 	$bb->ID);
$line_itemsmkkk=maybe_unserialize(get_post_meta($bb->ID, '_doc_line_items', true ));
		foreach ($line_itemsmkkk as $line_itemsmkkk_key => $line_itemsmkkk_value) {

$mkk_total22=$mkk_total22+$line_itemsmkkk_value['total'];

									}

// $mkk_total=get_post_meta( $bb->ID, "_total", true );
// $mkk_total22=$mkk_total22+$mkk_total;	
	
}
echo "mk total== ".$mkk_total22;
     /*                      echo "<pre>";
print_r($multisuite_invoices_loop);
echo "</pre>";*/

							$multisuites_ids=array();
							$m_invoice_ids=array();
							$m_suite_ids_array=array();
							$m_suite_ids_array_chunk=array();
							$multisuite_alldata=array();
							$multisuite_alldata_chunk=array();
							$multisuite_ymember=array();

							$y_membership_items=array();

							$y_membership_clients=array();

						

							foreach ($multisuite_invoices_loop as $key_m => $value_m) {

								
								  // $value_m->ID;
								if(get_post_meta( $value_m->ID, '_yl_lease_id', true )=="")
								{
									echo "<br>";
									echo "Invoice ID ".$value_m->ID;
									echo "</br>";
									if($value_m->ID==2961)
									{
										echo "this is id 2961";
										var_dump(get_post_meta( $value_m->ID, '_yl_lease_id', true ));
									}

									if(!in_array($value_m->ID, $all_invoice_id_checker))
										{
											array_push($all_invoice_id_checker, $value_m->ID);
										}
									array_push($multisuites_ids, $value_m->ID);
									$line_items=maybe_unserialize(get_post_meta($value_m->ID, '_doc_line_items', true ));
									// echo "<pre>";
									// print_r($line_items);
									// echo "</pre>";
									$multisuite_rent=0;
									$multisuite_aux=0;
									$count=1;
									$count_lineitem=count($line_items);

									$line_item_array_chunks=array();
									$aux_changer=0;
									$Differenece1=0;
									$Differenece2=0;
									foreach ($line_items as $l_key => $l_value) {
									$the_real_total=$the_real_total+$l_value['total'];
									echo "<br>";
									echo "==============";
									echo $l_value['total'];
									echo "<br>";

									$multichecker_real_total=$multichecker_real_total+$l_value['total'];
									$Differenece1=$Differenece1+$l_value['total'];
                                   // echo  $value_m->ID;echo "<br>";
										// echo "<br>";
										// echo $l_value['total'];
										// echo "<br>";
										if (strpos($l_value['desc'], 'Monthly Rent for ') !== false ) {
										$month_explode=explode('Monthly Rent for ', strip_tags($l_value['desc']));
										$multichecker_real_total2=$multichecker_real_total2+$l_value['total'];
										// ;
										// echo "Suite title=".$month_explode[1];
										// echo "<br>";
										// var_dump($month_explode[1]);
										echo "here is title *******";
										echo $title=str_replace(" Lease", "",$month_explode[1]);
										var_dump($title);
										$m_suite_idarr=get_page_by_title($title, 'ARRAY_A', 'suites' );	
										// echo "title=".$title;
										echo "msuite id".$m_suite_id=$m_suite_idarr['ID'];

										// echo "Multisuite id=".$m_suite_id;
										// echo "<br>";
									// echo '<a href="'.get_post_permalink( $value_m->ID ).'" title="">'.$month_explode[1].'---->'.$value_m->ID.'</a>' ;
									// echo "<br>";
										array_push($m_suite_ids_array_chunk, $m_suite_id);
													// echo "<pre>";
													// echo "Suites Array";
													// print_r($m_suite_ids_array);
													// echo "<pre>";
										// array_push(array, var)
										$multisuite_alldata_chunk[$m_suite_id]['rent']=$l_value['total'];
										// $multisuite_alldata_chunk[$m_suite_id]['aux']=$aux_changer;
										$multisuite_alldata_chunk[$m_suite_id]['invoice_id']=$value_m->ID;
										$Differenece2=$Differenece2+$l_value['total'];
										if($count==$count_lineitem)
										{
											$multisuite_alldata_chunk[$m_suite_id]['aux']=$multisuite_alldata_chunk[$m_suite_id]['aux']+$aux_changer;
										}

										// 			if($m_suite_id==729)
										// {
										// 	echo "<pre>";

										// 	print_r($multisuite_alldata_chunk);
										// 	echo "<pre>";
										// 	echo "total = ".$multichecker_real_total;
										// }
										// $multisuite_aux=0;
										// echo "<br>";
										}
										elseif (strpos($l_value['desc'], 'Multi Suite Discount') !== false ) {
											$multichecker_real_total2=$multichecker_real_total2+$l_value['total'];
										
											$aux_changer=$aux_changer+$l_value['total'];
											end($m_suite_ids_array_chunk);         // move the internal pointer to the end of the array
											$key = key($m_suite_ids_array_chunk);
											$last_suite_id=$m_suite_ids_array_chunk[$key];
											echo "<br>";
											echo "Last suite id ".$last_suite_id;
											echo "<br>";
											echo "Aux charger beforen Disccount ".$aux_changer;
											echo "<br>";
											$multisuite_alldata_chunk[$last_suite_id]['aux']=$multisuite_alldata_chunk[$last_suite_id]['aux']+$aux_changer;
											$Differenece2=$Differenece2+$multisuite_alldata[$last_suite_id]['aux']+$aux_changer;
											// echo "Aux charger beforen Disccount ".$aux_changer;
										// $Differenece2=
										$aux_changer=0;

										}

										elseif($count==$count_lineitem)
										{
											echo $count." count value =".$l_value['total'];
											$multichecker_real_total2=$multichecker_real_total2+$l_value['total'];

											// echo $count;
											// echo "<br>";
											// echo $count_lineitem;
											end($m_suite_ids_array_chunk);         // move the internal pointer to the end of the array
											$key = key($m_suite_ids_array_chunk);
											$last_suite_id=$m_suite_ids_array_chunk[$key];
											echo "Last suite id ".$last_suite_id;
											$aux_changer=$aux_changer+$l_value['total'];

											//echo "Key";
											//echo $key;
											$multisuite_alldata_chunk[$last_suite_id]['aux']=$multisuite_alldata_chunk[$last_suite_id]['aux']+$aux_changer;
										
											$Differenece2=$Differenece2+$multisuite_alldata[$last_suite_id]['aux']+$aux_changer;

										}
										else{
										$multichecker_real_total2=$multichecker_real_total2+$l_value['total'];

										$aux_changer=$aux_changer+$l_value['total'];
										}


										


// 										if (strpos($l_value['desc'], 'Monthly Rent for ') !== false ) {
// 										$month_explode=explode('Monthly Rent for ', strip_tags($l_value['desc']));
// 										// echo "Suite title=".$month_explode[1];
// 										// echo "<br>";
// 										// var_dump($month_explode[1]);
// 										$m_suite_idarr=get_page_by_title($month_explode[1], 'ARRAY_A', 'suites' );	
// 										$m_suite_id=$m_suite_idarr['ID'];
										
// 										// echo "Multisuite id=".$m_suite_id;
// 										// echo "<br>";
// 									// echo '<a href="'.get_post_permalink( $value_m->ID ).'" title="">'.$month_explode[1].'---->'.$value_m->ID.'</a>' ;
// 									// echo "<br>";
// 										array_push($m_suite_ids_array, $m_suite_id);
// 													/*echo "<pre>";
// 													echo "Suites Array";
// 													print_r($m_suite_ids_array);
// 													echo "<pre>";
// 										// array_push(array, var)*/
// 										$multisuite_alldata[$m_suite_id]['rent']=$l_value['total'];
// 										$multisuite_alldata[$m_suite_id]['aux']=$multisuite_aux;
// 										$multisuite_alldata[$m_suite_id]['invoice_id']=$value_m->ID;
// 										$multisuite_aux=0;
// 										// echo "<br>";
// 										}
// 										else{
// 										$multisuite_aux=(float) ($multisuite_aux+$l_value['total']);
// 										}


// 										if($count==$count_lineitem)
// 										{
// 											// echo $count;
// 											// echo "<br>";
// 											// echo $count_lineitem;
// 											end($m_suite_ids_array);         // move the internal pointer to the end of the array
// 											$key = key($m_suite_ids_array);
// 											$last_suite_id=$m_suite_ids_array[$key];
// 											//echo "Key";
// 											//echo $key;
// 											$multisuite_alldata[$last_suite_id]['aux']=(float) ($multisuite_alldata[$last_suite_id]['aux']+$multisuite_aux);
// // 							echo "<pre>";			
// // print_r($multisuite_alldata);
// // 							echo "</pre>";	
									
// 										}
											// echo $aux_changer;
											echo "Aux changer = ".$aux_changer;
										$count++;
									}
									echo "<br>";

									echo "Real Difference ".$Differenece1;
									echo "<br>";
									echo "Fake Difference ".$Differenece2;
									if($Differenece1!=$Differenece2)
									{
										echo "Error is here !";
										echo "<br>";
										echo $Differenece1-$Differenece2;
									}
									echo "*************************";
// echo $multichecker_real_total2;
									echo "<pre>";
									print_r($multisuite_alldata_chunk);
									echo "</pre>";
									// exit();

									// echo "<br>";
								}

								$yl_lease_id=get_post_meta( $value_m->ID, '_yl_lease_id', true );
							
								$yl_suite_number=get_post_meta( $yl_lease_id, '_yl_suite_number', true );
								if(  $value_m->ID==2076)
								{
									echo "2961";
									echo "2076";
									echo "	";
									echo "vardump";
									var_dump($yl_suite_number);
								}

								 // $yl_lease_id."suite id".$yl_suite_number;echo "<br>";

								$client_id=get_post_meta($value_m->ID, '_client_id', true );

                              

								if($yl_suite_number == -1 || $yl_suite_number == 'Y-Membership')
								{

									// echo "web".$value_m->ID;echo "<br>";
									array_push($y_membership_clients,$client_id);
									// echo get_the_title($yl_lease_id)."======".$yl_suite_number;

									$line_items=maybe_unserialize(get_post_meta($value_m->ID, '_doc_line_items', true ));
									// echo "<pre>";
									// echo "<pre>";
									$y_membership_items[$client_id]['aux']=0;
									$y_membership_items[$client_id]['invoice_id']=$value_m->ID;

									foreach ($line_items as $l_key => $l_value) {
									$the_real_total=$the_real_total+$l_value['total'];

									if (in_array($client_id, $y_membership_clients)) {
										# code...
													if (strpos($l_value['desc'], 'Monthly Rent for') !== false ) {
													// echo $l_value['total'];
													// echo $value_m->ID;
													$y_membership_items[$client_id]['rent']=(float) ($l_value['total']+$y_membership_items[$client_id]['rent']);
													}
													else{
													$y_membership_items[$client_id]['aux']=(float) ($y_membership_items[$client_id]['aux']+$l_value['total']);

													}

									}
									else{

													if (strpos($l_value['desc'], 'Monthly Rent for') !== false ) {
														// echo $l_value['total'];
														// echo $value_m->ID;
													$y_membership_items[$client_id]['rent']=$l_value['total'];
													}
													else{
													$y_membership_items[$client_id]['aux']=(float) ($y_membership_items[$client_id]['aux']+$l_value['total']);

													}
									}
								




								}
									// echo "<pre>";
									// print_r($line_items);
									// echo "<pre>";


								}



































								// echo "<pre>";
								// echo "Multisuite";
								// print_r($multisuite_alldata);
								// echo "<pre>";
								# code...
							}

							// echo $multichecker_real_total2;
							// 		echo "<pre>";
							// 		print_r($multisuite_alldata);
							// 		echo "</pre>";
									// exit();

							// echo "<pre>";
							// // echo "Suites Array";
							// print_r($y_membership_items);
							// echo "<pre>";

							// echo "<pre>";
							// print_r($all_data);
							// echo "<pre>";
			/***all data start**/
				 ?>
					<style type="text/css" media="screen">
					.mk_account_reports table{
					border-collapse: collapse;
					margin: 10px 0;
					}
					.mk_account_reports	th{
					font-weight: normal;
					border: 1px solid #D4D4D4;
					padding: 3px;
					text-align: center;
					}
					.mk_account_reports	td{
					border: 1px solid #D4D4D4;
					}
					</style>
					<div class="wrap">
					<h2 class="mkbis2">Rent Roll Reports</h2>
					<div class="mk_account_reports">

					<table cellpadding="0" cellspacing="0" border="0" width="100%">
					<thead>
					<tr>
					<th>  Suite Number     </th>
					<!-- <th>Tennant Name</th> -->
					<th>Rent</th>
					<th>Aux Charges</th>

					</tr>
					</thead> 
					<tbody>
				 <?php


/*
echo "<pre>";
print_r($all_data);
echo "</pre>";
*/
			foreach ($y_membership_items as $ikkey => $lankk) {

				 $the_total=$the_total+$lankk['rent'];
				 $the_total=$the_total+$lankk['aux'];
				 $linketotal=$lankk['rent']+$lankk['aux'];
				echo "*********".$lankk['invoice_id']."**************".$linketotal;
				echo "<br>";
				echo $the_total;
				echo "<br>";
									if(!in_array($lankk['invoice_id'], $all_invoice_id_checker))
										{
											array_push($all_invoice_id_checker, $lankk['invoice_id']);
										}
// echo "********".$ikkey."**** ".$lankk['rent']."****er******".$lankk['aux'];echo "<br>";
// echo $the_total;

// echo "<br>";
				# code...
					?>
					<tr>

					<td>Y Membership <?php echo $ikkey; ?></td>
					<!-- <td><?php echo $tenament_name; ?></td> -->
					<td><a onclick="submitymember(<?php echo $ikkey;  ?>,'<?php echo $start_account; ?>','<?php echo $end_account; ?>',<?php  echo $building; ?>	);" href="javascript:void(0);" title="#">$<?php echo $lankk['rent']; ?></a></td>
					<td><a onclick="submitymemberaux(<?php echo $ikkey;  ?>,'<?php echo $start_account; ?>','<?php echo $end_account; ?>',<?php  echo $building; ?>	);" href="javascript:void(0);" title="#"><?php echo '$' . $lankk['aux']; ?></a></td>
					<!-- <td></td> -->
					</tr>

					<?php

			}

			foreach ($all_data as $suite_id => $main) {
               



				$leasd_id =	$main['leases'][0];				
				$invoices_ids= $main['invoices'];
				// echo "lease id =".$leasd_id;
				// echo "<br>";
				// echo "lease id =".$leasd_id;
				// echo "<pre>";
				// print_r($invoices_ids);
				// echo "<pre>";
				// exit();
				 if(!is_array($invoice_ids))
				 {
				 	// echo "empty";
				?>
				<tr>

				<td><?php echo get_the_title( $suite_id ); ?></td>
				<td>0</td>
				<td>0</td>
				<td>0</td>
				<!-- <td></td> -->
				</tr>
				<?php
				 }
				 else
				 {
				 	$tenament_name="";
				 	$aux_charges=0;
				 	$rent_charges=0;
					$monthlyrent=0;
				 	$suite_name_mk=get_the_title( $suite_id );



				 	foreach ($invoices_ids as $key => $value) {
				 			$tenament_name=$value['company_name'];;

							foreach ($value['line_items'] as $line => $lineitem) {
$the_real_total=$the_real_total+$lineitem['total'];

echo "*********".$key."**************".$lineitem['total'];
echo "<br>";
echo								$the_total=$the_total+$lineitem['total'];
echo "<br>";
// echo "********".$key."**** ";
// echo "rate".$lineitem['total'];echo "<br>";
// echo  $the_total;

// echo "<br>";

							if (strpos($lineitem['desc'], 'Rent') !== false ) {
							$rent_charges=(float) ($rent_charges+$lineitem['total']);
							}
							else{
							$aux_charges=(float) ($aux_charges+$lineitem['total']);
							}


							}


				 		# code...
				 	}

				 	// echo $suite_id;
				 	// echo $suite_id;
				 	// echo "<br>";
				 	// if($suite_id==784 || $suite_id==772)
				 	// {
				 	// 	echo "**************************************reached".$suite_id;
				 	// }
				 	
				 

					?>
					<tr>

					<td><?php echo get_the_title( $suite_id ); ?></td>
					<!-- <td><?php echo $tenament_name; ?></td> -->
					<td><a onclick="submitResources(<?php echo $suite_id;  ?>,'<?php echo $start_account; ?>','<?php echo $end_account; ?>',<?php  echo $building; ?>	);" href="javascript:void(0);" title="#">$<?php echo $rent_charges; ?></a></td>
					<td><a onclick="submitResourcesauxrent(<?php echo $suite_id;  ?>,'<?php echo $start_account; ?>','<?php echo $end_account; ?>',<?php  echo $building; ?>	);" href="javascript:void(0);" title="#"><?php echo '$' . $aux_charges; ?></a></td>
					<!-- <td></td> -->
					</tr>

					<?php
				 	

				 }
				 // exit();
				 // echo "<pre>";
				 // print_r($invoices_ids);
				 // echo "</pre>";
				 // $key=  														 
				 // $suite_id=	get_post_meta($leasd_id, '_yl_product_id',true);
				// echo "<pre>";
				// print_r($value);
				// echo "</pre>";

			}	

$abc=0;
			foreach ($multisuite_alldata_chunk as $mulkey => $mulvalue) {
				$mm=$multisuite_alldata_chunk[$mulkey]['rent']+$multisuite_alldata_chunk[$mulkey]['aux'];
				echo "*********".$multisuite_alldata_chunk[$mulkey]['invoice_id']."**************".$mm;
echo "<br>";
echo								$the_total=$the_total+$mm;
$abc=$abc+$mm;
					?>
					<tr>

					<td><?php echo get_the_title( $mulkey ); ?></td>
					<!-- <td><?php echo $tenament_name; ?></td> -->
					<td><a class="multisuiteinvoicemk" onclick="submitResources_multisuite(<?php echo $mulkey;  ?>,<?php echo $multisuite_alldata_chunk[$mulkey]['invoice_id'];  ?>,'<?php echo $start_account; ?>','<?php echo $end_account; ?>',<?php  echo $building; ?>	);" href="javascript:void(0);" title="#">$<?php echo $multisuite_alldata_chunk[$mulkey]['rent']; ?></a></td>
					<td><a onclick="submitResourcesauxrent_multisite(<?php echo $mulkey;  ?>,<?php echo $multisuite_alldata_chunk[$mulkey]['invoice_id'];  ?>,'<?php echo $start_account; ?>','<?php echo $end_account; ?>',<?php  echo $building; ?>,'<?php echo $aux_show; ?>');" href="javascript:void(0);" title="#"><?php echo '$' . $multisuite_alldata_chunk[$mulkey]['aux']; ?></a></td>
					<!-- <td></td> -->
					</tr>
					<?php
			}

			echo "Multichekerrrrrrrrr".$abc;
			
// echo "<pre>";

// echo "this is total of multisuite".$temp_total_multi;
//            print_r($multisuite_alldata);  

// echo "</pre>";

// echo "<br>";echo "<br>";echo "<br>";echo "<br>";echo "<br>";

         
// echo "<pre>";
// print_r($y_membership_items);

				 	// echo "<pre>";
				 	// print_r( $multisuite_alldata);
				 	// echo "<pre>";


				?>
				<style type="text/css" media="screen">
				.mk_account_reports table{
				border-collapse: collapse;
				margin: 10px 0;
				}
				.mk_account_reports	th{
				font-weight: normal;
				border: 1px solid #D4D4D4;
				padding: 3px;
				text-align: left;
				}
				.mk_account_reports	td{
				border: 1px solid #D4D4D4;
				}
				</style>


				</tbody>
				</table>
				</div>
				</div>

				<?php

				echo "Mk real total is ".$the_real_total;
				echo "<br>";
echo "Unrealmultichechker ".$multichecker_real_total2;
				echo "<br>";
echo "Multichechker ".$multichecker_real_total;
				
				echo "<pre>";
print_r($all_invoice_id_checker);
echo "</pre>";

				echo "<pre>";
print_r($all_invoice_id_checker22);
echo "</pre>";
$result13 = array_diff($all_invoice_id_checker22,$all_invoice_id_checker);
echo "Differenece";
				echo "<pre>";

print_r($result13);
				echo "</pre>";
			restore_current_blog();

			}





/*********Rent Roll Report Close**************/



if($_POST['_accounting_report_id']=="Invoice Journal Entries")
{

	// echo "webutopian";

$building=$_POST['_accounting_building_id'];
if($building=="all")
{

			// $csv_data_array=array();



			
			$sites = wp_get_sites();


			$all_blogs_id=array();
			$removed_ids=array(1,20,19,6);
			foreach ($sites as $key => $current_blog) {

				if(!in_array($current_blog['blog_id'], $removed_ids))
				{
				array_push($all_blogs_id, $current_blog['blog_id']);
				}
			}
				$csv_data_array=array();
			$i=0;

foreach ($all_blogs_id as $crid) {
	# code...
		// $sites = wp_get_sites();

			switch_to_blog($crid);
// }

switch ($crid) {
	case '4':
	$class="DEV";
	break;
	case '9':
	$class="MCK";
	break;
	case '10':
	$class="FR";
	break;
	case '11':
	$class="FHRA";
	break;	
	case '12':
	$class="C1";
	break;
	case '13':
	$class="F1";
	break;
	case '14':
	$class="F2";
	break;
	case '15':
	$class="GW";
	break;
	case '16':
	$class="N1";
	break;
	case '17':
	$class="N2";
	break;
	case '18':
	$class="OSW";
	break;										
	default:
	$class="DEV";
		break;
}

				?>
				<div class="wrap">
				<h2 class="mkbis2">Open Invoice Report</h2>
				<?php
				// $building=$_POST['_accounting_building_id'];


			
		


			global $wpdb;
			global $post;
			$start_account=$_POST['_accounting_start_date'];
			$start_acc=explode("-", $start_account);
			$str_yr=$start_acc[0];
			$str_mn=$start_acc[1];
			$str_dy=$start_acc[2];
			$end_account=$_POST['_accounting_end_date'];
			$end_acc=explode("-", $end_account);
			$end_yr=$end_acc[0];
			$end_mn=$end_acc[1];
			$end_dy=$end_acc[2];
			// $args = array(
			// 	'post_type' => 'sa_invoice',
			// 	'post_status' => array('publish,partial,complete,write-off'),
			// 		'date_query' => array(
			// 			array(
			// 				'column' => 'post_modified',
			// 				'after'     => array(
			// 					'year'  => $str_yr,
			// 					'month' => $str_mn,
			// 					'day'   => $str_dy,
			// 				),
			// 				'before'    => array(
			// 					'year'  => $end_yr,
			// 					'month' => $end_mn,
			// 					'day'   => $end_dy,
			// 				),
			// 				'inclusive' => true,
			// 			),
			// 		),
			// 	'posts_per_page' => -1,
			// 	);

			$start_Account2=strtotime($start_account);
		$end_Account2=strtotime($end_account);
	$args = array(
    'post_type' => 'sa_invoice',
    'posts_per_page' => -1,
    'orderby' => 'meta_value',
    'order' => 'ASC',
    'meta_key' => '_due_date',
    'meta_query' => array(
        array(
            'key' => '_due_date',
            'value' => array($start_Account2, $end_Account2),
            'compare' => 'BETWEEN',
            'type' => 'Numeric'
        )
    )
);

				$results = get_posts($args);

			foreach ($results as $key => $result) {
			$paymentdatefull=explode(" ", $result->post_modified);		
			$paymentdate=$paymentdatefull[0];	
			$invoice_id=$result->ID;
			$all_data[$invoice_id]['date']=$paymentdate;
			$all_data[$invoice_id]['line_items']=maybe_unserialize(get_post_meta($invoice_id, '_doc_line_items', true ));
			$total=get_post_meta($invoice_id, '_total', true );
			$credit_fees==get_post_meta($invoice_id, '_doc_tax2', true );
			if($credit_fees==""){ $credit_fees=0; }
			$total_credit_fee=($total*$credit_fees)/100;
			$full_total=(float) ($full_total+$total);
			}

			$final_data_table=array();
			$final_data_table['total']="";
			$final_data_table['all_dates']=array();
			$final_data_table['all_dates']['Bad Debt']=0;
			$final_data_table['all_dates']['Phone']=0;
			$final_data_table['all_dates']['Tenant Improvement']=0;
			$final_data_table['all_dates']['Copies']=0;
			$final_data_table['all_dates']['Fees']=$total_credit_fee;
			$final_data_table['all_dates']['Discounts']=0;
			$final_data_table['all_dates']['Phone']=0;
			$final_data_table['all_dates']['Fax']=0;
			$final_data_table['all_dates']['Fees']=0;
			$final_data_table['all_dates']['Phone']=0;
			$final_data_table['all_dates']['Tenant Improvement']=0;
			$final_data_table['all_dates']['Long Distance']=0;
			$final_data_table['all_dates']['Postage']=0;
			$final_data_table['all_dates']['Rent']=0;
			$final_data_table['all_dates']['Sec. Deposit']=0;
			$final_data_table['all_dates']['Utilities']=0;


			foreach ($all_data as $key => $value) {


				$mainurl=home_url();
				
// http://mckinney.yeagercommunity.com/wp-admin/post.php?post=6853&action=edit

$invediturl=$mainurl."/wp-admin/post.php?post=".$key."&action=edit";
				// echo "<br>";
				// echo "<a target='_blank' href='".$invediturl."'>Invoice id ".$key."</a>";
				// echo "<br>";
$final_total2=0;
$final_total3=0;

					foreach ($value['line_items'] as  $lineitem) {



					echo $lineitem['desc']."===".$lineitem['total'];
					echo "<br>";
					if (strpos($lineitem['desc'], 'Discount') !== false) {
					$final_data_table['total']=$final_data_table['total']+$lineitem['total'];
					$final_total2=$final_total2+$lineitem['total'];
					// $final_total3=$final_total3-$lineitem['total'];
					}
					else{
					$final_data_table['total']=$final_data_table['total']+$lineitem['total'];
					$final_total2=$final_total2+$lineitem['total'];
					// $final_total3=$final_total3+$lineitem['total'];

					}

					if (strpos($lineitem['desc'], 'IP Static') !== false) {
					$final_data_table['all_dates']['Phone']=$final_data_table['all_dates']['Phone']+$lineitem['total'];
					$final_total3=$final_total3+$lineitem['total'];

					}

					elseif (strpos($lineitem['desc'], 'Bad Debt') !== false) {
					$final_data_table['all_dates']['Bad Debt']=$final_data_table['all_dates']['Bad Debt']+$lineitem['total'];
					$final_total3=$final_total3+$lineitem['total'];

					}
					elseif (strpos($lineitem['desc'], 'Copier') !== false) {
					$final_data_table['all_dates']['Copies']=$final_data_table['all_dates']['Copies']+$lineitem['total'];
					$final_total3=$final_total3+$lineitem['total'];

					}
										elseif (strpos($lineitem['desc'], 'Copies') !== false) {
					$final_data_table['all_dates']['Copies']=$final_data_table['all_dates']['Copies']+$lineitem['total'];
					$final_total3=$final_total3+$lineitem['total'];

					}

					elseif (strpos($lineitem['desc'], 'Keys') !== false) {
					$final_data_table['all_dates']['Tenant Improvement']=$final_data_table['all_dates']['Tenant Improvement']+$lineitem['total'];
					$final_total3=$final_total3+$lineitem['total'];
					
					}
					elseif (strpos($lineitem['desc'], 'Key') !== false) {
					$final_data_table['all_dates']['Tenant Improvement']=$final_data_table['all_dates']['Tenant Improvement']+$lineitem['total'];
					$final_total3=$final_total3+$lineitem['total'];
					
					}
					elseif (strpos($lineitem['desc'], 'Late Fee') !== false) {
					$final_data_table['all_dates']['Fees']=$final_data_table['all_dates']['Fees']+$lineitem['total'];
					$final_total3=$final_total3+$lineitem['total'];
					
					} 

					elseif (strpos($lineitem['desc'], 'Ancillaries') !== false) {
					$final_data_table['all_dates']['Fees']=$final_data_table['all_dates']['Fees']+$lineitem['total'];
					$final_total3=$final_total3+$lineitem['total'];
					
					} 


					elseif (strpos($lineitem['desc'], 'Cleaning Service Fee') !== false) {
					$final_data_table['all_dates']['Fees']=$final_data_table['all_dates']['Fees']+$lineitem['total'];
					$final_total3=$final_total3+$lineitem['total'];
					
					} 




					elseif (strpos($lineitem['desc'], 'Long Distance') !== false) {
					$final_data_table['all_dates']['Long Distance']=$final_data_table['all_dates']['Long Distance']+$lineitem['total'];
					$final_total3=$final_total3+$lineitem['total'];
					
					}
					 elseif (strpos($lineitem['desc'], 'Phone Line') !== false) {
					$final_data_table['all_dates']['Phone']=$final_data_table['all_dates']['Phone']+$lineitem['total'];
					$final_total3=$final_total3+$lineitem['total'];
					
					}

					 elseif (strpos($lineitem['desc'], 'Phone') !== false) {
					$final_data_table['all_dates']['Phone']=$final_data_table['all_dates']['Phone']+$lineitem['total'];
					$final_total3=$final_total3+$lineitem['total'];
					
					}


					 elseif (strpos($lineitem['desc'], 'IP Service Fee') !== false) {
					$final_data_table['all_dates']['Phone']=$final_data_table['all_dates']['Phone']+$lineitem['total'];
					$final_total3=$final_total3+$lineitem['total'];
					
					}

								 elseif (strpos($lineitem['desc'], 'Ip Service') !== false) {
					$final_data_table['all_dates']['Phone']=$final_data_table['all_dates']['Phone']+$lineitem['total'];
					$final_total3=$final_total3+$lineitem['total'];
					
					}


								 elseif (strpos($lineitem['desc'], 'Cable') !== false) {
					$final_data_table['all_dates']['Phone']=$final_data_table['all_dates']['Phone']+$lineitem['total'];
					$final_total3=$final_total3+$lineitem['total'];
					
					}


								 elseif (strpos($lineitem['desc'], 'Credit Card Line') !== false) {
					$final_data_table['all_dates']['Phone']=$final_data_table['all_dates']['Phone']+$lineitem['total'];
					$final_total3=$final_total3+$lineitem['total'];
					
					}






					 elseif (strpos($lineitem['desc'], 'Postage') !== false) {
					$final_data_table['all_dates']['Postage']=$final_data_table['all_dates']['Postage']+$lineitem['total'];	
					$final_total3=$final_total3+$lineitem['total'];
					
					}


				    elseif (strpos($lineitem['desc'], 'IP Service Fees') !== false) {
					$final_data_table['all_dates']['Phone']=$final_data_table['all_dates']['Phone']+$lineitem['total'];	
					$final_total3=$final_total3+$lineitem['total'];
					
					}


				    elseif (strpos($lineitem['desc'], 'Service Fees') !== false) {
					$final_data_table['all_dates']['Utilities']=$final_data_table['all_dates']['Utilities']+$lineitem['total'];	
					$final_total3=$final_total3+$lineitem['total'];
					
					}


									    elseif (strpos($lineitem['desc'], 'Utility Service') !== false) {
					$final_data_table['all_dates']['Utilities']=$final_data_table['all_dates']['Utilities']+$lineitem['total'];	
					$final_total3=$final_total3+$lineitem['total'];
					
					}

					
				    elseif (strpos($lineitem['desc'], 'Fax Fees') !== false) {
					$final_data_table['all_dates']['Fax']=$final_data_table['all_dates']['Fax']+$lineitem['total'];	
					$final_total3=$final_total3+$lineitem['total'];
					
					}
					 elseif (strpos($lineitem['desc'], 'Fax Service') !== false) {
					$final_data_table['all_dates']['Phone']=$final_data_table['all_dates']['Phone']+$lineitem['total'];	
					$final_total3=$final_total3+$lineitem['total'];
					
					}
					
					 // elseif (strpos($lineitem['desc'], 'Suite Discount') !== false) {
					elseif (strpos($lineitem['desc'], 'Discount') !== false) {

					$final_data_table['all_dates']['Discounts']=$final_data_table['all_dates']['Discounts']+$lineitem['total'];
					$final_total3=$final_total3+$lineitem['total'];
					
					}


					elseif (strpos($lineitem['desc'], 'Refreshing Fee') !== false) {
					$final_data_table['all_dates']['Tenant Improvement']=$final_data_table['all_dates']['Tenant Improvement']+$lineitem['total'];		
					$final_total3=$final_total3+$lineitem['total'];
					
					}

					 elseif (strpos($lineitem['desc'], 'Rent') !== false) {
					$final_data_table['all_dates']['Rent']=$final_data_table['all_dates']['Rent']+$lineitem['total'];	
					$final_total3=$final_total3+$lineitem['total'];
					
					}



					 elseif (strpos($lineitem['desc'], 'prorated moving') !== false) {
					$final_data_table['all_dates']['Rent']=$final_data_table['all_dates']['Rent']+$lineitem['total'];	
					$final_total3=$final_total3+$lineitem['total'];
					
					}




					elseif (strpos($lineitem['desc'], 'Security Deposit') !== false) {
					$final_data_table['all_dates']['Sec. Deposit']=$final_data_table['all_dates']['Sec. Deposit']+$lineitem['total'];	
					$final_total3=$final_total3+$lineitem['total'];
					
					}

					elseif (strpos($lineitem['desc'], 'Tenant Improvements') !== false) {
					$final_data_table['all_dates']['Tenant Improvement']=$final_data_table['all_dates']['Tenant Improvement']+$lineitem['total'];	
					$final_total3=$final_total3+$lineitem['total'];
					
					}


										elseif (strpos($lineitem['desc'], 'Overpayment from') !== false) {
					$final_data_table['all_dates']['Tenant Improvement']=$final_data_table['all_dates']['Tenant Improvement']+$lineitem['total'];	
					$final_total3=$final_total3+$lineitem['total'];
					
					}

					elseif (strpos($lineitem['desc'], 'Utilities') !== false) {
					$final_data_table['all_dates']['Utilities']=$final_data_table['all_dates']['Utilities']+$lineitem['total'];	
					$final_total3=$final_total3+$lineitem['total'];
					
					}

					elseif (strpos($lineitem['desc'], 'Water Utliity Fee') !== false) {
					$final_data_table['all_dates']['Utilities']=$final_data_table['all_dates']['Utilities']+$lineitem['total'];	
					$final_total3=$final_total3+$lineitem['total'];
					
					}

										elseif (strpos($lineitem['desc'], 'Overpayment from') !== false) {
					$final_data_table['all_dates']['Utilities']=$final_data_table['all_dates']['Utilities']+$lineitem['total'];	
					$final_total3=$final_total3+$lineitem['total'];
					
					}


					 

					elseif (strpos($lineitem['desc'], 'Work Orders') !== false) {
					$final_data_table['all_dates']['Work Orders']=$final_data_table['all_dates']['Work Orders']+$lineitem['total'];
					$final_total3=$final_total3+$lineitem['total'];
					
					}

					elseif (strpos($lineitem['desc'], 'NSF Fee') !== false) {
					$final_data_table['all_dates']['Fees']=$final_data_table['all_dates']['Fees']+$lineitem['total'];		
					$final_total3=$final_total3+$lineitem['total'];
					
					}
					elseif (strpos($lineitem['desc'], 'Fees') !== false) {
					$final_data_table['all_dates']['Fees']=$final_data_table['all_dates']['Fees']+$lineitem['total'];		
					$final_total3=$final_total3+$lineitem['total'];
					
					}

					elseif (strpos($lineitem['desc'], 'Convenience Fee') !== false) {
					$final_data_table['all_dates']['Fees']=$final_data_table['all_dates']['Fees']+$lineitem['total'];		
					$final_total3=$final_total3+$lineitem['total'];
					
					}


					elseif (strpos($lineitem['desc'], 'April Payment Convienence Fee') !== false) {
					$final_data_table['all_dates']['Fees']=$final_data_table['all_dates']['Fees']+$lineitem['total'];		
					$final_total3=$final_total3+$lineitem['total'];
					
					}


					elseif (strpos($lineitem['desc'], 'Workorder') !== false) {
					$final_data_table['all_dates']['Tenant Improvement']=$final_data_table['all_dates']['Tenant Improvement']+$lineitem['total'];		
					$final_total3=$final_total3+$lineitem['total'];
					
					}

					elseif (strpos($lineitem['desc'], 'Work Order') !== false) {
					$final_data_table['all_dates']['Tenant Improvement']=$final_data_table['all_dates']['Tenant Improvement']+$lineitem['total'];		
					$final_total3=$final_total3+$lineitem['total'];
					
					}

					elseif (strpos($lineitem['desc'], 'Plumbing Work Order') !== false) {
					$final_data_table['all_dates']['Tenant Improvement']=$final_data_table['all_dates']['Tenant Improvement']+$lineitem['total'];		
					$final_total3=$final_total3+$lineitem['total'];
					
					}

					


					# code...
				}

			// 	echo "<br>";
			// 	echo "Line total ALL items =".$final_total2;
			// 	echo "<br>";
			// 	echo "Line total our items =".$final_total3;
			// 	if($final_total2!=$final_total3)
			// 	{
			// 		echo "Error is here !";
			// 	}
			// 	echo "<pre>";
			// 	print_r($final_data_table['all_dates']);

			// 	echo "<br>";
			// 	echo "Final Total = ".$final_data_table['total'];

			// 	echo "</pre>";
			// 	# code...
			// echo "***********************************************************************************";
			$mk_count++;
			

			}
			?>

			<style type="text/css" media="screen">
					.mk_account_reports table{
							border-collapse: collapse;
							margin: 10px 0;
						}
					.mk_account_reports	th{
							font-weight: normal;
							border: 1px solid #D4D4D4;
							padding: 3px;
							text-align: left;
						}
					.mk_account_reports	td{
							border: 1px solid #D4D4D4;
							}
			</style>
			<div class="mk_account_reports">
				
			<table cellpadding="0" cellspacing="0" border="0" width="100%">
			<thead>
				<tr>
					<th>Account</th>
					<th>Debit</th>
					<th>Credit</th>
					<th>Memo</th>
					<th>Name</th>
					<th>Billable</th>
					<th>Class</th>

				</tr>
			</thead> 
			<tbody>
			<?php

			foreach ($final_data_table['all_dates'] as $key => $value) {
				if($value!=0)
				{
				?>
			<tr>
			<td><?php echo $key;?></td>
			<td>  </td>
			<td>$<?php echo $value; ?></td>
			<td>Week of <?php echo $end_account; ?></td>
			<td> </td>
			<td> </td>
			<td><?php echo $class; ?> </td>
			
			</tr>
				<?php
			}
			$csv_data_array[$i]['Account']=$key;
			$csv_data_array[$i]['Debit']=" ";
			$csv_data_array[$i]['Credit']='$' . number_format($value);
			$csv_data_array[$i]['Memo']="Week of ". $end_account;
			$csv_data_array[$i]['Name']=" ";
			$csv_data_array[$i]['Billable']=" ";
			$csv_data_array[$i]['Class']=$class;
				
			$i++;
			}
			$csv_data_array[$i]['Account']="Accounts Receivable";
			$csv_data_array[$i]['Debit']="######";
			$csv_data_array[$i]['Credit']="";
			$csv_data_array[$i]['Memo']="";
			$csv_data_array[$i]['Name']=" ";
			$csv_data_array[$i]['Billable']=" ";
			$csv_data_array[$i]['Class']=$class;

			?>
			<tr>
			<td>Accounts Receivable</td>
			<td>#######</td>
			<td></td>
			<td> </td>
			<td> </td>
			<td> </td>
			<td> </td>
			
			</tr>
			</tbody>
			</table>
			</div>
			<?php
			restore_current_blog();

	





}








			
// }



			reporst_csv("Income journal list.csv",$csv_data_array,"rental_report");







}
else{



switch ($_POST['_accounting_building_id']) {
	case '4':
	$class="DEV";
	break;
	case '9':
	$class="MCK";
	break;
	case '10':
	$class="FR";
	break;
	case '11':
	$class="FHRA";
	break;	
	case '12':
	$class="C1";
	break;
	case '13':
	$class="F1";
	break;
	case '14':
	$class="F2";
	break;
	case '15':
	$class="GW";
	break;
	case '16':
	$class="N1";
	break;
	case '17':
	$class="N2";
	break;
	case '18':
	$class="OSW";
	break;										
	default:
	$class="DEV";
		break;
}

				?>
				<div class="wrap">
				<h2 class="mkbis2">Open Invoice Report</h2>
				<?php
				$building=$_POST['_accounting_building_id'];


			
			$sites = wp_get_sites();

			switch_to_blog($building);


			global $wpdb;
			global $post;
			$start_account=$_POST['_accounting_start_date'];
			$start_acc=explode("-", $start_account);
			$str_yr=$start_acc[0];
			$str_mn=$start_acc[1];
			$str_dy=$start_acc[2];
			$end_account=$_POST['_accounting_end_date'];
			$end_acc=explode("-", $end_account);
			$end_yr=$end_acc[0];
			$end_mn=$end_acc[1];
			$end_dy=$end_acc[2];
			$the_total=0;
			// $args = array(
			// 	'post_type' => 'sa_invoice',
			// 	'post_status' => array('publish,partial,complete,write-off'),
			// 		'date_query' => array(
			// 			array(
			// 				'column' => 'post_modified',
			// 				'after'     => array(
			// 					'year'  => $str_yr,
			// 					'month' => $str_mn,
			// 					'day'   => $str_dy,
			// 				),
			// 				'before'    => array(
			// 					'year'  => $end_yr,
			// 					'month' => $end_mn,
			// 					'day'   => $end_dy,
			// 				),
			// 				'inclusive' => true,
			// 			),
			// 		),
			// 	'posts_per_page' => -1,
			// 	);

														// 				$args = array(
														//     'post_type' => 'sa_invoice',
														//      'posts_per_page' => -1,
														//     'post_status' =>  array('complete','publish','partial'),
														//      // 'orderby' => 'meta_value',
														//  	'order' => 'ASC',
														//     'meta_query' => array(
														// 				'relation' => 'AND',
														// 		    	    array(
														//     	    			    'key' => '_due_date',
														// 				            'value' => $start_account,
														// 			                'compare' => '>=',
														// 						    'type'    => 'Date'
														// 			  			 ),
														// 		    	    	array(
														//     	    			    'key' => '_due_date',
														// 				            'value' => $end_account,
														// 			                'compare' => '<=',
														// 						    'type'    => 'Date'
														// 			  			 ),
												
														// 					 )
														// );


// 																			          		$args = array(
// 						    'post_type' => 'sa_invoice',
// 						    'posts_per_page' => -1,
// //						    'post_status' =>  array('draft', 'publish'),
// 						    'post_status' => "any",
// 						    'orderby' => 'meta_value',
// 						    'order' => 'ASC',
// 						    'meta_query' => array(


// 						 				'relation' => 'AND',
// 						    	    array(

// 									            'key' => '_due_date',
// 									            'value' => "2016-05-31",
// 									            'compare' => '>=',
// 									            'type'    => 'Date'
// 									            ),


// 						    	     	 array(
// 						     				    'key' => '_due_date',
// 									            'value' => "2016-06-30",
// 									            'compare' => '<=',
// 									            'type'    => 'Date'

// 						    	    	),
// 						    	     	 ),
						    	     	     	 
								       
// 						    // )
// 								);	
								$start_Account2=strtotime($start_account);
								$end_Account2=strtotime($end_account);
								$args = array(
							    'post_type' => 'sa_invoice',
							    'post_status' => 'any',
							    'posts_per_page' => -1,
							    'orderby' => 'meta_value',
							    'order' => 'ASC',
							    'meta_key' => '_due_date',
							    'meta_query' => array(
							        array(
							            'key' => '_due_date',
							            'value' => array($start_Account2, $end_Account2),
							            'compare' => 'BETWEEN',
							            'type' => 'Numeric'
							        )
							    )
							);
														// $invoice_id=array();
							// 							$loop = new WP_Query($args);

							// while ( $loop->have_posts() ) : $loop->the_post();
							// 							// echo "checkpoint3";
							// 			the_title();


														// endwhile;
				$results = get_posts($args);
/*echo "<pre>";
print_r($results);
echo "</pre>";*/

             $k=0;
			foreach ($results as $key => $result) {
			$paymentdatefull=explode(" ", $result->post_modified);		
			$paymentdate=$paymentdatefull[0];	
			$invoice_id=$result->ID;
			$all_data[$invoice_id]['date']=$paymentdate;
			$all_data[$invoice_id]['line_items']=maybe_unserialize(get_post_meta($invoice_id, '_doc_line_items', true ));
			$total=get_post_meta($invoice_id, '_total', true );
			$credit_fees==get_post_meta($invoice_id, '_doc_tax2', true );
			if($credit_fees==""){ $credit_fees=0; }
			$total_credit_fee=($total*$credit_fees)/100;
			$full_total=(float) ($full_total+$total);


			$k++;
			}
// echo "Total Run".$k.
			$final_data_table=array();
			$final_data_table['total']="";
			$final_data_table['all_dates']=array();
			$final_data_table['all_dates']['Bad Debt']=0;
			$final_data_table['all_dates']['Phone']=0;
			$final_data_table['all_dates']['Tenant Improvement']=0;
			$final_data_table['all_dates']['Copies']=0;
			$final_data_table['all_dates']['Fees']=$total_credit_fee;
			$final_data_table['all_dates']['Discounts']=0;
			$final_data_table['all_dates']['Phone']=0;
			$final_data_table['all_dates']['Fax']=0;
			$final_data_table['all_dates']['Fees']=0;
			$final_data_table['all_dates']['Phone']=0;
			// $final_data_table['all_dates']['Tenant Improvement']=0;
			$final_data_table['all_dates']['Long Distance']=0;
			$final_data_table['all_dates']['Postage']=0;
			$final_data_table['all_dates']['Rent']=0;
			$final_data_table['all_dates']['Sec. Deposit']=0;
			$final_data_table['all_dates']['Utilities']=0;
			$mk_count=1;

			foreach ($all_data as $key => $value) {
				$mainurl=home_url();
					
// http://mckinney.yeagercommunity.com/wp-admin/post.php?post=6853&action=edit

				$invediturl=$mainurl."/wp-admin/post.php?post=".$key."&action=edit";
				// echo "<br>";
				// echo "<a target='_blank' href='".$invediturl."'>Invoice id ".$key."</a>";
				// echo "<br>";
				$final_total2=0;
				$final_total3=0;

					foreach ($value['line_items'] as  $lineitem) {
							$the_total=$the_total+$lineitem['total'];
				// $the_total=$the_total+$lankk['aux'];
// echo "********".$key."**** ".$lineitem['total'];echo "<br>";
// echo $the_total;

// echo "<br>";
				//	echo $lineitem['desc']."===".$lineitem['total'];
					//echo "<br>";
					if (strpos($lineitem['desc'], 'Discount') !== false) {
					$final_data_table['total']=$final_data_table['total']+$lineitem['total'];
					$final_total2=$final_total2+$lineitem['total'];
					// $final_total3=$final_total3-$lineitem['total'];
					}
					else{
					$final_data_table['total']=$final_data_table['total']+$lineitem['total'];
					$final_total2=$final_total2+$lineitem['total'];
					// $final_total3=$final_total3+$lineitem['total'];

					}

					if (strpos($lineitem['desc'], 'IP Static') !== false) {
					$final_data_table['all_dates']['Phone']=$final_data_table['all_dates']['Phone']+$lineitem['total'];
					$final_total3=$final_total3+$lineitem['total'];

					}

					elseif (strpos($lineitem['desc'], 'Bad Debt') !== false) {
					$final_data_table['all_dates']['Bad Debt']=$final_data_table['all_dates']['Bad Debt']+$lineitem['total'];
					$final_total3=$final_total3+$lineitem['total'];

					}
					elseif (strpos($lineitem['desc'], 'Copier') !== false) {
					$final_data_table['all_dates']['Copies']=$final_data_table['all_dates']['Copies']+$lineitem['total'];
					$final_total3=$final_total3+$lineitem['total'];

					}
										elseif (strpos($lineitem['desc'], 'Copies') !== false) {
					$final_data_table['all_dates']['Copies']=$final_data_table['all_dates']['Copies']+$lineitem['total'];
					$final_total3=$final_total3+$lineitem['total'];

					}

					elseif (strpos($lineitem['desc'], 'Keys') !== false) {
					$final_data_table['all_dates']['Tenant Improvement']=$final_data_table['all_dates']['Tenant Improvement']+$lineitem['total'];
					$final_total3=$final_total3+$lineitem['total'];
					
					}
					elseif (strpos($lineitem['desc'], 'Key') !== false) {
					$final_data_table['all_dates']['Tenant Improvement']=$final_data_table['all_dates']['Tenant Improvement']+$lineitem['total'];
					$final_total3=$final_total3+$lineitem['total'];
					
					}
					elseif (strpos($lineitem['desc'], 'Late Fee') !== false) {
					$final_data_table['all_dates']['Fees']=$final_data_table['all_dates']['Fees']+$lineitem['total'];
					$final_total3=$final_total3+$lineitem['total'];
					
					} 

					elseif (strpos($lineitem['desc'], 'Ancillaries') !== false) {
					$final_data_table['all_dates']['Fees']=$final_data_table['all_dates']['Fees']+$lineitem['total'];
					$final_total3=$final_total3+$lineitem['total'];
					
					} 


					elseif (strpos($lineitem['desc'], 'Cleaning Service Fee') !== false) {
					$final_data_table['all_dates']['Fees']=$final_data_table['all_dates']['Fees']+$lineitem['total'];
					$final_total3=$final_total3+$lineitem['total'];
					
					} 




					elseif (strpos($lineitem['desc'], 'Long Distance') !== false) {
					$final_data_table['all_dates']['Long Distance']=$final_data_table['all_dates']['Long Distance']+$lineitem['total'];
					$final_total3=$final_total3+$lineitem['total'];
					
					}
					//  elseif (strpos($lineitem['desc'], 'Phone Line') !== false) {
					// $final_data_table['all_dates']['Phone']=$final_data_table['all_dates']['Phone']+$lineitem['total'];
					// $final_total3=$final_total3+$lineitem['total'];
					
					// }

					 elseif (strpos($lineitem['desc'], 'Phone') !== false) {
					$final_data_table['all_dates']['Phone']=$final_data_table['all_dates']['Phone']+$lineitem['total'];
					$final_total3=$final_total3+$lineitem['total'];
					
					}


					//  elseif (strpos($lineitem['desc'], 'IP Service Fee') !== false) {
					// $final_data_table['all_dates']['Phone']=$final_data_table['all_dates']['Phone']+$lineitem['total'];
					// $final_total3=$final_total3+$lineitem['total'];
					
					// }

								 elseif (strpos($lineitem['desc'], 'Ip Service') !== false) {
					$final_data_table['all_dates']['Phone']=$final_data_table['all_dates']['Phone']+$lineitem['total'];
					$final_total3=$final_total3+$lineitem['total'];
					
					}


								 elseif (strpos($lineitem['desc'], 'Cable') !== false) {
					$final_data_table['all_dates']['Phone']=$final_data_table['all_dates']['Phone']+$lineitem['total'];
					$final_total3=$final_total3+$lineitem['total'];
					
					}


								 elseif (strpos($lineitem['desc'], 'Credit Card Line') !== false) {
					$final_data_table['all_dates']['Phone']=$final_data_table['all_dates']['Phone']+$lineitem['total'];
					$final_total3=$final_total3+$lineitem['total'];
					
					}






					 elseif (strpos($lineitem['desc'], 'Postage') !== false) {
					$final_data_table['all_dates']['Postage']=$final_data_table['all_dates']['Postage']+$lineitem['total'];	
					$final_total3=$final_total3+$lineitem['total'];
					
					}


				 //    elseif (strpos($lineitem['desc'], 'IP Service Fees') !== false) {
					// $final_data_table['all_dates']['Phone']=$final_data_table['all_dates']['Phone']+$lineitem['total'];	
					// $final_total3=$final_total3+$lineitem['total'];
					
					// }


				    elseif (strpos($lineitem['desc'], 'Service Fees') !== false) {
					$final_data_table['all_dates']['Utilities']=$final_data_table['all_dates']['Utilities']+$lineitem['total'];	
					$final_total3=$final_total3+$lineitem['total'];
					
					}


									    elseif (strpos($lineitem['desc'], 'Utility Service') !== false) {
					$final_data_table['all_dates']['Utilities']=$final_data_table['all_dates']['Utilities']+$lineitem['total'];	
					$final_total3=$final_total3+$lineitem['total'];
					
					}

					
				    elseif (strpos($lineitem['desc'], 'Fax Fees') !== false) {
					$final_data_table['all_dates']['Fax']=$final_data_table['all_dates']['Fax']+$lineitem['total'];	
					$final_total3=$final_total3+$lineitem['total'];
					
					}
					 elseif (strpos($lineitem['desc'], 'Fax Service') !== false) {
					$final_data_table['all_dates']['Phone']=$final_data_table['all_dates']['Phone']+$lineitem['total'];	
					$final_total3=$final_total3+$lineitem['total'];
					
					}
					
					 // elseif (strpos($lineitem['desc'], 'Suite Discount') !== false) {
					elseif (strpos($lineitem['desc'], 'Discount') !== false) {

					$final_data_table['all_dates']['Discounts']=$final_data_table['all_dates']['Discounts']+$lineitem['total'];
					$final_total3=$final_total3+$lineitem['total'];
					
					}


					elseif (strpos($lineitem['desc'], 'Refreshing Fee') !== false) {
					$final_data_table['all_dates']['Tenant Improvement']=$final_data_table['all_dates']['Tenant Improvement']+$lineitem['total'];		
					$final_total3=$final_total3+$lineitem['total'];
					
					}

					 elseif (strpos($lineitem['desc'], 'Rent') !== false) {
					$final_data_table['all_dates']['Rent']=$final_data_table['all_dates']['Rent']+$lineitem['total'];	
					$final_total3=$final_total3+$lineitem['total'];
					
					}



					 elseif (strpos($lineitem['desc'], 'prorated moving') !== false) {
					$final_data_table['all_dates']['Rent']=$final_data_table['all_dates']['Rent']+$lineitem['total'];	
					$final_total3=$final_total3+$lineitem['total'];
					
					}




					elseif (strpos($lineitem['desc'], 'Security Deposit') !== false) {
					$final_data_table['all_dates']['Sec. Deposit']=$final_data_table['all_dates']['Sec. Deposit']+$lineitem['total'];	
					$final_total3=$final_total3+$lineitem['total'];
					
					}

					elseif (strpos($lineitem['desc'], 'Tenant Improvements') !== false) {
					$final_data_table['all_dates']['Tenant Improvement']=$final_data_table['all_dates']['Tenant Improvement']+$lineitem['total'];	
					$final_total3=$final_total3+$lineitem['total'];
					
					}


										elseif (strpos($lineitem['desc'], 'Overpayment from') !== false) {
					$final_data_table['all_dates']['Tenant Improvement']=$final_data_table['all_dates']['Tenant Improvement']+$lineitem['total'];	
					$final_total3=$final_total3+$lineitem['total'];
					
					}

					elseif (strpos($lineitem['desc'], 'Utilities') !== false) {
					$final_data_table['all_dates']['Utilities']=$final_data_table['all_dates']['Utilities']+$lineitem['total'];	
					$final_total3=$final_total3+$lineitem['total'];
					
					}

					elseif (strpos($lineitem['desc'], 'Water Utliity Fee') !== false) {
					$final_data_table['all_dates']['Utilities']=$final_data_table['all_dates']['Utilities']+$lineitem['total'];	
					$final_total3=$final_total3+$lineitem['total'];
					
					}

										elseif (strpos($lineitem['desc'], 'Overpayment from') !== false) {
					$final_data_table['all_dates']['Utilities']=$final_data_table['all_dates']['Utilities']+$lineitem['total'];	
					$final_total3=$final_total3+$lineitem['total'];
					
					}


					 

					elseif (strpos($lineitem['desc'], 'Work Orders') !== false) {
					$final_data_table['all_dates']['Work Orders']=$final_data_table['all_dates']['Work Orders']+$lineitem['total'];
					$final_total3=$final_total3+$lineitem['total'];
					
					}

					elseif (strpos($lineitem['desc'], 'NSF Fee') !== false) {
					$final_data_table['all_dates']['Fees']=$final_data_table['all_dates']['Fees']+$lineitem['total'];		
					$final_total3=$final_total3+$lineitem['total'];
					
					}
					elseif (strpos($lineitem['desc'], 'Fees') !== false) {
					$final_data_table['all_dates']['Fees']=$final_data_table['all_dates']['Fees']+$lineitem['total'];		
					$final_total3=$final_total3+$lineitem['total'];
					
					}

					elseif (strpos($lineitem['desc'], 'Convenience Fee') !== false) {
					$final_data_table['all_dates']['Fees']=$final_data_table['all_dates']['Fees']+$lineitem['total'];		
					$final_total3=$final_total3+$lineitem['total'];
					
					}


					elseif (strpos($lineitem['desc'], 'April Payment Convienence Fee') !== false) {
					$final_data_table['all_dates']['Fees']=$final_data_table['all_dates']['Fees']+$lineitem['total'];		
					$final_total3=$final_total3+$lineitem['total'];
					
					}


					elseif (strpos($lineitem['desc'], 'Workorder') !== false) {
					$final_data_table['all_dates']['Tenant Improvement']=$final_data_table['all_dates']['Tenant Improvement']+$lineitem['total'];		
					$final_total3=$final_total3+$lineitem['total'];
					
					}

					elseif (strpos($lineitem['desc'], 'Work Order') !== false) {
					$final_data_table['all_dates']['Tenant Improvement']=$final_data_table['all_dates']['Tenant Improvement']+$lineitem['total'];		
					$final_total3=$final_total3+$lineitem['total'];
					
					}

					elseif (strpos($lineitem['desc'], 'Plumbing Work Order') !== false) {
					$final_data_table['all_dates']['Tenant Improvement']=$final_data_table['all_dates']['Tenant Improvement']+$lineitem['total'];		
					$final_total3=$final_total3+$lineitem['total'];
					
					}

					


					# code...
				}

				// echo "<br>";
				// echo "Line total ALL items =".$final_total2;
				// echo "<br>";
				// echo "Line total our items =".$final_total3;
				// if($final_total2!=$final_total3)
				// {
				// 	echo "Error is here !";
				// }
				// echo "<pre>";
				// print_r($final_data_table['all_dates']);

				// echo "<br>";
				// echo "Final Total = ".$final_data_table['total'];

				// echo "</pre>";
				// # code...
				// 			echo "***********************************************************************************";
							$mk_count++;
							}


							// echo "Total invoice looped".$mk_count;
							?>

							<style type="text/css" media="screen">
									.mk_account_reports table{
											border-collapse: collapse;
											margin: 10px 0;
										}
									.mk_account_reports	th{
											font-weight: normal;
											border: 1px solid #D4D4D4;
											padding: 3px;
											text-align: left;
										}
									.mk_account_reports	td{
											border: 1px solid #D4D4D4;
											}
							</style>
							<div class="mk_account_reports">
								
							<table cellpadding="0" cellspacing="0" border="0" width="100%">
							<thead>
								<tr>
									<th>Account</th>
									<th>Debit</th>
									<th>Credit</th>
									<th>Memo</th>
									<th>Name</th>
									<th>Billable</th>
									<th>Class</th>

								</tr>
							</thead> 
							<tbody>
							<?php
							$csv_data_array=array();
							$i=0;
							foreach ($final_data_table['all_dates'] as $key => $value) {
								if($value!=0)
								{
								?>
							<tr>
							<td><?php echo $key;?></td>
							<td>  </td>


							   <td><a onclick="submitResources_phone('<?php echo $start_account; ?>','<?php echo $end_account; ?>',<?php  echo $building; ?>,'<?php echo $key; ?>');" href="javascript:void(0);" title="#">$<?php echo $value; ?></a></td>
							<td>Week of <?php echo $end_account; ?></td>
							<td> </td>
							<td> </td>
							<td><?php echo $class; ?> </td>
							
							</tr>
								<?php
							}
							$csv_data_array[$i]['Account']=$key;
							$csv_data_array[$i]['Debit']=" ";
							$csv_data_array[$i]['Credit']='$' . number_format($value,2);
							$csv_data_array[$i]['Memo']="Week of ". $end_account;
							$csv_data_array[$i]['Name']=" ";
							$csv_data_array[$i]['Billable']=" ";
							$csv_data_array[$i]['Class']=$class;
								
							$i++;
							}
							$csv_data_array[$i]['Account']="Accounts Receivable";
							$csv_data_array[$i]['Debit']=$final_data_table['total'];
							$csv_data_array[$i]['Credit']="";
							$csv_data_array[$i]['Memo']="";
							$csv_data_array[$i]['Name']=" ";
							$csv_data_array[$i]['Billable']=" ";
							$csv_data_array[$i]['Class']=$class;
							// reporst_csv("Income journal list.csv",$csv_data_array,"rental_report");	

							?>
							<tr>
							<td>Accounts Receivable</td>
							<td><?php echo $final_data_table['total']; ?></td>
							<td></td>
							<td> </td>
							<td> </td>
							<td> </td>
							<td> </td>
							
							</tr>
							</tbody>
							</table>
							</div>
							<?php
							restore_current_blog();

					














			
}


			}

/****************Current Tenament ******************/





if($_POST['_accounting_report_id']=="Current Tenant")
{







		// echo "still in process";



			$building=$_POST['_accounting_building_id'];

			
			$sites = wp_get_sites();


			$all_blogs_id=array();
			$removed_ids=array(1,20,19,6);
			foreach ($sites as $key => $current_blog) {

				if(!in_array($current_blog['blog_id'], $removed_ids))
				{
				array_push($all_blogs_id, $current_blog['blog_id']);
				}
			}
				$csv_data_array=array();

			if($building=="all")
			{

			}
			

			else{



			switch_to_blog($building);


			global $wpdb;
			global $post;
			$start_account=$_POST['_accounting_start_date'];
			$start_acc=explode("-", $start_account);
			$str_yr=$start_acc[0];
			$str_mn=$start_acc[1];
			$str_dy=$start_acc[2];
			$end_account=$_POST['_accounting_end_date'];
			$end_acc=explode("-", $end_account);
			$end_yr=$end_acc[0];
			$end_mn=$end_acc[1];
			$end_dy=$end_acc[2];

							$start_Account2=strtotime($start_account);
							$end_Account2=strtotime($end_account);
							$args = array(
						    'post_type' => 'sa_invoice',
						    'post_status' => 'any',
						    'posts_per_page' => -1,
						    'orderby' => 'meta_value',
						    'order' => 'ASC',
						    'meta_key' => '_due_date',
						    'meta_query' => array(
						        array(
						            'key' => '_due_date',
						            'value' => array($start_Account2, $end_Account2),
						            'compare' => 'BETWEEN',
						            'type' => 'Numeric'
						        )
						    )
						);



			$results = get_posts($args);
			$all_data=array();

			$full_total=0;
			foreach ($results as $key => $result) {
			$paymentdatefull=explode(" ", $result->post_modified);		
			$paymentdate=$paymentdatefull[0];	
			$invoice_id=$result->ID;
			$client_name=get_the_title(get_post_meta( $invoice_id, '_client_id', true ));
			$leasd_id =	get_post_meta($invoice_id, '_yl_lease_id',true);
			$company_id=	get_post_meta($leasd_id, '_yl_company_name', true);
			$company_name=get_the_title( $company_id );
			$total=get_post_meta( $invoice_id, '_total', true );
			$client_id=get_post_meta( $invoice_id, '_client_id', true);
			$user_id=get_post_meta( $client_id, '_associated_users', true);
			 $yl_ms_args = array(
						        'post_type'   => 'lease',
						        'post_status'   => 'publish',
						        'numberposts'  => -1,
						        'meta_query' => array(
						          array(
						            'key' => '_yl_lease_user',
						            'value'   => $user_id,
						            'compare' => '='
						          )
						        )
						      );
			 $suite_number="";
			 $leasd_id= get_post_meta( $invoice_id, '_yl_lease_id', true );
			 $suite_number=get_post_meta($leasd_id, '_yl_suite_number', true );
   		      	if($suite_number=='-1')
		      	{
		      		$suite_number="Y-Memberships";
		      	}
			      $invoice_num='=HYPERLINK("'.get_permalink($invoice_id).'", "'.$invoice_id.'")';

			$all_data[$invoice_id]['client_name']=$client_name;
			$all_data[$invoice_id]['company_name']=$company_name;
			$all_data[$invoice_id]['invoice_num']=$invoice_num;
			$all_data[$invoice_id]['suite_number']=$suite_number;
			$all_data[$invoice_id]['num']=$invoice_id;
			$all_data[$invoice_id]['totalmk']=$total;
			$all_data[$invoice_id]['date']=$paymentdate;
			$all_data[$invoice_id]['line_items']=maybe_unserialize(get_post_meta($invoice_id, '_doc_line_items', true ));
			$full_total=(float) ($full_total+$total);
			}
			?>
						<style type="text/css" media="screen">
					.mk_account_reports table{
							border-collapse: collapse;
							margin: 10px 0;
						}
					.mk_account_reports	th{
							font-weight: normal;
							border: 1px solid #D4D4D4;
							padding: 3px;
							text-align: center;
						}
					.mk_account_reports	td{
							border: 1px solid #D4D4D4;
							}
			</style>
				<div class="wrap">
		<h2 class="mkbis2">Current Tenant</h2>
		<div class="mk_account_reports">
				
			<table cellpadding="0" cellspacing="0" border="0" width="100%">
			<thead>
				<tr>
					<th>  Suite Number     </th>
					<th>Tennant Name</th>
				</tr>
			</thead> 
			<tbody>
			<?php
				$csv_data_array=array();
				$csv_data_array2=array();
				$i=1;
				$suite_array=array();
				$suite_names=array();
			foreach ($all_data as $key => $value) {
				 $leasd_id =	get_post_meta($key, '_yl_lease_id',true);  														 
				 $suite_id=	get_post_meta($leasd_id, '_yl_product_id',true);
				$cable=0;
				$Copies=0;
				$FaxLine=0;
				$Fees=0;
				$FaxService=0;
				// $FaxService=0;
				$longsitancs=0;
				$phoneline=0;
				$postage=0;
				$rent=0;
				$stroage=0;
				$tenanet=0;
				$totalservice=0;
				$fin=0;
				$nsf=0;
				$totalother=0;
				$Total=$value['totalmk'];
				$Total3=0;
				$Total2=$value['totalmk'];
				$invoice_num=$value['invoice_num'];
				$monthlyrent=0;
				$auxrent=0;
				$total_rent_new=0;
				foreach ($value['line_items'] as $line => $lineitem) {
					// $Total=$Total+$lineitem['total']

				// echo $lineitem['desc']."=".$lineitem['total'];
					// echo "</br>";

					if (strpos($lineitem['desc'], 'Monthly Rent') !== false) {
					$monthlyrent=(float) ($monthlyrent+$lineitem['total']);
					$suiter_name=explode("for ",$lineitem['desc'] );
					$suite_name_mk=$suiter_name[1];
					array_push($suite_names, $suite_name_mk);
					$suite_array[$suite_name_mk]['month_rent']=$lineitem['total'];
					$suite_array[$suite_name_mk]['total_rent']=$lineitem['total'];
					$suite_array[$suite_name_mk]['client_name']=$value['client_name'];
					$suite_array[$suite_name_mk]['company_name']=$value['company_name'];
					$suite_array[$suite_name_mk]['suite_id']=$suite_id;


					


					// $client_name
					// $totalservice=(float) ($totalservice+$lineitem['total']);

					}
					else{
					// end($suite_array);         // move the internal pointer to the end of the array
					// echo $suite_name_mk = key($suite_array); 
					// echo "==";
					 $auxrent=(float) ($auxrent+$lineitem['total']);
					// echo "==";
					 $total_rent_new=(float) ($total_rent_new+$lineitem['total']);
					// echo "<br>";
					// $suite_array[$suite_name_mk]['aux_rent']=(float) ($suite_array[$suite_name_mk]['aux_rent']+$lineitem['total']);
					// $suite_array[$suite_name_mk]['total_rent']=(float) ($suite_array[$suite_name_mk]['total_rent']+$lineitem['total']);


					}

					$Total3=(float) ($Total3+$lineitem['total']);
					
			}

					end($suite_array);         // move the internal pointer to the end of the array
					$suite_name_mk = key($suite_array); 
					// $auxrent=(float) ($auxrent+$lineitem['total']);
					// $total_rent_new=(float) ($total_rent_new+$lineitem['total']);
					$suite_array[$suite_name_mk]['aux_rent']=$auxrent;
					$suite_array[$suite_name_mk]['total_rent']=(float) ($suite_array[$suite_name_mk]['total_rent']+$total_rent_new);








					$i++;
		}
// 		echo "<pre>";
// print_r($suite_array);
// echo "<pre>";
$x=1;
foreach ($suite_array as $suitename => $sut) {
	$replace = array("</p>", "lease");
$with   = array("");
					$csv_data_array2[$x]['Suite Number']=str_replace($replace, $with, $suitename);
					$csv_data_array2[$x]['Tennant Name']=$sut['company_name'];

					$suite_id=$sut['suite_id'];
					?>

					<tr>

						<td><?php echo str_replace($replace, $with, $suitename); ?></td>
						<td><?php echo $sut['company_name']; ?></td>
					</tr>


					<?php
					// $csv_data_array[$i]['Invoice Link']=$invoice_num;
	# code...
$x++;
}

			?>
					<style type="text/css" media="screen">
							.mk_account_reports table{
									border-collapse: collapse;
									margin: 10px 0;
								}
							.mk_account_reports	th{
									font-weight: normal;
									border: 1px solid #D4D4D4;
									padding: 3px;
									text-align: left;
								}
							.mk_account_reports	td{
									border: 1px solid #D4D4D4;
									}
					</style>
					

					</tbody>
					</table>
					</div>
					</div>
			<?php
			reporst_csv("Current Tenant.csv",$csv_data_array2,"rental_report");	


			}






			restore_current_blog();

	

				
			









}












/**************Current Tenament closed *********************************





/**************security deposit*******************/
if($_POST['_accounting_report_id']=="Security Deposit Report")
			{
			$building=$_POST['_accounting_building_id'];

				if($building=="all")
				{
			$sites = wp_get_sites();


			$all_blogs_id=array();
			$removed_ids=array(1,20,19,6);
			foreach ($sites as $key => $current_blog) {

				if(!in_array($current_blog['blog_id'], $removed_ids))
				{
				array_push($all_blogs_id, $current_blog['blog_id']);
				}
			}
				$csv_data_array=array();
			$i=0;

foreach ($all_blogs_id as  $crid) {
	# code...

		// echo "still in process";
switch ($crid) {
	case '4':
	$class="DEV";
	break;
	case '9':
	$class="MCK";
	break;
	case '10':
	$class="FR";
	break;
	case '11':
	$class="FHRA";
	break;	
	case '12':
	$class="C1";
	break;
	case '13':
	$class="F1";
	break;
	case '14':
	$class="F2";
	break;
	case '15':
	$class="GW";
	break;
	case '16':
	$class="N1";
	break;
	case '17':
	$class="N2";
	break;
	case '18':
	$class="OSW";
	break;										
	default:
	$class="DEV";
		break;
}


			// $building=$_POST['_accounting_building_id'];

			
			// $sites = wp_get_sites();

			switch_to_blog($crid);


			global $wpdb;
			global $post;
			$start_account=$_POST['_accounting_start_date'];
			$start_acc=explode("-", $start_account);
			$str_yr=$start_acc[0];
			$str_mn=$start_acc[1];
			$str_dy=$start_acc[2];
			$end_account=$_POST['_accounting_end_date'];
			$end_acc=explode("-", $end_account);
			$end_yr=$end_acc[0];
			$end_mn=$end_acc[1];
			$end_dy=$end_acc[2];
			$args = array(
				'post_type' => 'lease',
				// 'post_status' => 'publish',
				    'meta_key' => '_yl_suite_number',
				    'orderby' => 'meta_value',
				    'order' =>'ASC',
					'date_query' => array(
						array(
							'column' => 'post_modified',
							'after'     => array(
								'year'  => $str_yr,
								'month' => $str_mn,
								'day'   => $str_dy,
							),
							'before'    => array(
								'year'  => $end_yr,
								'month' => $end_mn,
								'day'   => $end_dy,
							),
							'inclusive' => true,
						),
					),
				'posts_per_page' => -1,
				);

			$results = get_posts($args);
			$alldates=array();
			foreach ($results as $lease) {
				$lease_id=$lease->ID;
				$modified=explode(" ", $lease->post_modified);
				$date_sec=$modified[0];

				if(get_post_meta($lease_id, '_yl_suite_number',true)=='-1')
		      	{
		      		$sutname="Y-Memberships";
		      	}
		      	else{
		      		$sutname=get_post_meta($lease_id, '_yl_suite_number',true);
		      		
		      	}
		      	
		      	$suite_number="(".$sutname.")";
				// $suite_number=get_post_meta( $lease_id, '_yl_suite_number', true );
				// $alldates['suite_number']=
				$alldates[$suite_number]['customer']=get_post_meta( $lease_id, '_yl_l_first_name', true )." ".get_post_meta( $lease_id, '_yl_l_last_name', true );
				
				// if(array_key_exists($suite_number, $alldates))
				// {
				// 	array_push($alldates[$date_sec]['customer']['security'], )
				// 	$alldates[$date_sec]['customer']=(float) ($alldates[$date_sec]['security']+get_post_meta( $lease_id, '_yl_security_deposit', true ));
				// }
				// else{
					$alldates[$suite_number]['security']=(float) (get_post_meta( $lease_id, '_yl_security_deposit', true ));

				// }
				// print_r($lease);
				// echo $date_sec;
				// echo "<br>";
				// echo get_post_meta( $lease_id, '_yl_security_deposit', true );
				// echo "<br>";
				// exit();
				# code...
			}
			// $alldates[$date_sec];
			// print_r($alldates);
			?>
			<style type="text/css" media="screen">
					.mk_account_reports table{
							border-collapse: collapse;
							margin: 10px 0;
						}
					.mk_account_reports	th{
							font-weight: normal;
							border: 1px solid #D4D4D4;
							padding: 3px;
							text-align: left;
						}
					.mk_account_reports	td{
							border: 1px solid #D4D4D4;
							}
			</style>
			<div class="mk_account_reports">
				
			<table cellpadding="0" cellspacing="0" border="0" width="100%">
			<thead>
				<tr>
					<th>Customer</th>
					<th>Class</th>
					<th>Security Deposit</th>
			<!-- 		<th>Memo</th>
					<th>Name</th>
					<th>Billable</th>
					<th>Class</th> -->

				</tr>
			</thead> 
			<tbody>
			<?php
			// $csv_data_array=array();
			// $i=1;
			foreach ($alldates as $key => $value) {
				if($value!=0)
				{
				?>
			<tr>
			<td><?php echo $value['customer']; ?></td>
			<td><?php echo $key;?></td>
			<!-- <td></td> -->
			<!-- <td>Week of <?php echo $end_account; ?></td> -->
			<td> $<?php echo $value['security']; ?></td>
			<!-- <td> </td>
			<td> </td> -->
			
			</tr>
				<?php
			}
				# code...
			$csv_data_array[$i]['Customer']=$value['customer'];
			$csv_data_array[$i]['Class']=$class." ".$key;
			$csv_data_array[$i]['Security Deposit']='$' . number_format($value['security']);
			$i++;
			}
			?>
		<!-- 	<tr>
			<td>Accounts Receivable</td>
			<td><?php echo "$".$final_data_table['total']; ?></td>
			<td></td>
			<td> </td>
			<td> </td>
			<td> </td>
			<td> </td>
			
			</tr> -->
			</tbody>
			</table>
			</div>
			<?php






	









			restore_current_blog();

	

	
}
reporst_csv("Security deposit report.csv",$csv_data_array,"rental_report");



				}
				else{


		// echo "still in process";
switch ($_POST['_accounting_building_id']) {
	case '4':
	$class="DEV";
	break;
	case '9':
	$class="MCK";
	break;
	case '10':
	$class="FR";
	break;
	case '11':
	$class="FHRA";
	break;	
	case '12':
	$class="C1";
	break;
	case '13':
	$class="F1";
	break;
	case '14':
	$class="F2";
	break;
	case '15':
	$class="GW";
	break;
	case '16':
	$class="N1";
	break;
	case '17':
	$class="N2";
	break;
	case '18':
	$class="OSW";
	break;										
	default:
	$class="DEV";
		break;
}


			$building=$_POST['_accounting_building_id'];

			
			$sites = wp_get_sites();

			switch_to_blog($building);


			global $wpdb;
			global $post;
			$start_account=$_POST['_accounting_start_date'];
			$start_acc=explode("-", $start_account);
			$str_yr=$start_acc[0];
			$str_mn=$start_acc[1];
			$str_dy=$start_acc[2];
			$end_account=$_POST['_accounting_end_date'];
			$end_acc=explode("-", $end_account);
			$end_yr=$end_acc[0];
			$end_mn=$end_acc[1];
			$end_dy=$end_acc[2];
			$args = array(
				'post_type' => 'lease',
				// 'post_status' => 'publish',
				    'meta_key' => '_yl_suite_number',
				    'orderby' => 'meta_value',
				    'order' =>'ASC',
					'date_query' => array(
						array(
							'column' => 'post_modified',
							'after'     => array(
								'year'  => $str_yr,
								'month' => $str_mn,
								'day'   => $str_dy,
							),
							'before'    => array(
								'year'  => $end_yr,
								'month' => $end_mn,
								'day'   => $end_dy,
							),
							'inclusive' => true,
						),
					),
				'posts_per_page' => -1,
				);

			$results = get_posts($args);
			$alldates=array();
			foreach ($results as $lease) {
				$lease_id=$lease->ID;
				$modified=explode(" ", $lease->post_modified);
				$date_sec=$modified[0];

				if(get_post_meta($lease_id, '_yl_suite_number',true)=='-1')
		      	{
		      		$sutname="Y-Memberships";
		      	}
		      	else{
		      		$sutname=get_post_meta($lease_id, '_yl_suite_number',true);
		      		
		      	}
		      	
		      	$suite_number="(".$sutname.")";
				// $suite_number=get_post_meta( $lease_id, '_yl_suite_number', true );
				// $alldates['suite_number']=
				$alldates[$suite_number]['customer']=get_post_meta( $lease_id, '_yl_l_first_name', true )." ".get_post_meta( $lease_id, '_yl_l_last_name', true );
				
				// if(array_key_exists($suite_number, $alldates))
				// {
				// 	array_push($alldates[$date_sec]['customer']['security'], )
				// 	$alldates[$date_sec]['customer']=(float) ($alldates[$date_sec]['security']+get_post_meta( $lease_id, '_yl_security_deposit', true ));
				// }
				// else{
					$alldates[$suite_number]['security']=(float) (get_post_meta( $lease_id, '_yl_security_deposit', true ));

				// }
				// print_r($lease);
				// echo $date_sec;
				// echo "<br>";
				// echo get_post_meta( $lease_id, '_yl_security_deposit', true );
				// echo "<br>";
				// exit();
				# code...
			}
			// $alldates[$date_sec];
			// print_r($alldates);
			?>
			<style type="text/css" media="screen">
					.mk_account_reports table{
							border-collapse: collapse;
							margin: 10px 0;
						}
					.mk_account_reports	th{
							font-weight: normal;
							border: 1px solid #D4D4D4;
							padding: 3px;
							text-align: left;
						}
					.mk_account_reports	td{
							border: 1px solid #D4D4D4;
							}
			</style>
			<div class="mk_account_reports">
				
			<table cellpadding="0" cellspacing="0" border="0" width="100%">
			<thead>
				<tr>
					<th>Customer</th>
					<th>Class</th>
					<th>Security Deposit</th>
			<!-- 		<th>Memo</th>
					<th>Name</th>
					<th>Billable</th>
					<th>Class</th> -->

				</tr>
			</thead> 
			<tbody>
			<?php
			$csv_data_array=array();
			$i=1;
			foreach ($alldates as $key => $value) {
				if($value!=0)
				{
				?>
			<tr>
			<td><?php echo $value['customer']; ?></td>
			<td><?php echo $key;?></td>
			<!-- <td></td> -->
			<!-- <td>Week of <?php echo $end_account; ?></td> -->
			<td> $<?php echo $value['security']; ?></td>
			<!-- <td> </td>
			<td> </td> -->
			
			</tr>
				<?php
			}
				# code...
			$csv_data_array[$i]['Customer']=$value['customer'];
			$csv_data_array[$i]['Class']=$class." ".$key;
			$csv_data_array[$i]['Security Deposit']='$' . number_format($value['security']);
			$i++;
			}
			?>
		<!-- 	<tr>
			<td>Accounts Receivable</td>
			<td><?php echo "$".$final_data_table['total']; ?></td>
			<td></td>
			<td> </td>
			<td> </td>
			<td> </td>
			<td> </td>
			
			</tr> -->
			</tbody>
			</table>
			</div>
			<?php






	

reporst_csv("Security deposit report.csv",$csv_data_array,"rental_report");








			restore_current_blog();

	





				}





			}




		}




		 ?>













		<div class="wrap">
		<h2 class="mkbis2">Accounting Reports</h2>
		<form action="" method="post" >
		<style type="text/css" media="screen">
			.mk_account_report_left label ,.mk_account_report_right label{
																			float: left;
																		    min-width: 100px;
																		}
			.mk_account_report_left select ,.mk_account_report_right select ,.mk_account_report_left input ,.mk_account_report_right input
			{
				min-width: 180px;
			}
			.mk_account_report_left input[type="submit"]{
				min-width: 100px;

			}
		</style>
		<div class="mk_account_report_left">
			
		<p>
			
		<label for="_accounting_report_id">Report</label>
		<select name="_accounting_report_id" id="_accounting_report_id">
			<option value="0">Select</option>
					<option value="Invoice Journal Entries">Income Journal Entries</option>
					<option value="Deposit Journal Entries">Deposit Journal Entries</option>
					<option value="Security Deposit Report">Security Deposit Report</option>
					<option value="Open Invoice Report">Open Invoice Report</option>
					<option value="Rent Roll Report">Rent Roll Report</option>
					<option value="Rent Roll Report New">Rent Roll Report New</option>
					<option value="Current Tenant">Current Tenant</option>


	
		</select>
		</p>
		<p>
			
		<label for="_accounting_building_id">Building</label>
		<select name="_accounting_building_id" id="_accounting_building_id">
			<option value="0">Select</option>

					<!-- <option value="1">yeagercommunity.com</option> -->
					<!-- <option value="6">template</option> -->
					<option value="all">All</option>
					<option value="9">Mckinney</option>
					<option value="10">Frisco</option>
					<option value="11">Ft. Harrison</option>
					<option value="12">Carmel</option>
					<option value="13">Fishers</option>
					<option value="14">II Fishers</option>
					<option value="15">Greenwood</option>
					<option value="16">Noblesville</option>
					<option value="17">Noblesville-Shoppes</option>
					<option value="18">Plainfield</option>
					<option value="24">Plano</option>

					<option value="4">Devsite</option>
					<!-- <option value="19">internal</option> -->
					<!-- <option value="20">help</option> -->
<!-- 				<option value="Fort Harrison">Fort Harrison</option>
					<option value="McKinney">McKinney</option>
					<option value="Frisco">Frisco</option>
					<option value="Carmel">Carmel</option>
					<option value="Fishers 1">Fishers 1</option>
					<option value="II Fishers">II Fishers</option>
					<option value="Greenwood">Greenwood</option>
					<option value="Noblesville 1">Noblesville 1</option>
					<option value="Noblesville 1">Noblesville 1</option>
					<option value="Plainfield">Plainfield</option> -->
	
		</select>
		</p>
		</div>
		<div class="mk_account_report_right">
		<p>
			

		<label for="_accounting_start_date">Start Date</label>
			
		<input type="text" name="_accounting_start_date" value="" class="datepicker _accounting_start_date" />
		</p>
		<p>
			

		<label for="_accounting_end_date">End Date</label>
			
		<input type="text" name="_accounting_end_date" value="" class="datepicker _accounting_end_date" />

		<input type="submit" name="accountingmk_submit" value="Run"/>
		</p>
		</div>
			
		</form>
</div>
		<?php

	





	}


function reporst_csv($name,$mk,$type){
//include MK_MAIN_DIR."reportscsv.php";
	if($type=="open_invoice")
	{
	require( plugin_dir_path( __FILE__ ) . 'reports/openinvoice.php');
		
	}	
	if($type=="rental_report")
	{
	require( plugin_dir_path( __FILE__ ) . 'reports/rentalreport.php');
		
	}	
	echo MK_MAIN_DIR."reportscsv.php";
// require(MK_MAIN_DIR."reportscsv.php");
}