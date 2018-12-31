<?php
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
// $bank="Busey Bank";


					


					?>
					<div class="wrap">
					<h2 class="mkbis2">Deposit Journal Entries</h2>
					<?php

				
				$sites = wp_get_sites();

				switch_to_blog($crid);

	$field_class_name="report_".$crid."_class";
	$field_bank_name="report_".$crid."_bank";
	$class=get_option($field_class_name);
	$bank=get_option($field_bank_name);

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
				$final_total_without_fees=$final_data_table['total']-$final_data_table['fees'];
				$csv_data_array[0]['Accounts']='Accounts Receivable';
				$csv_data_array[0]['Debit']=" ";
				$csv_data_array[0]['Credit']='$' . number_format($final_total_without_fees,2);
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
		


				?>
				<div class="wrap">
				<h2 class="mkbis2">Deposit Journal Entries</h2>
				<?php
				$building=$_POST['_accounting_building_id'];


			
			$sites = wp_get_sites();

			switch_to_blog($building);

	$field_class_name="report_".$building."_class";
	$field_bank_name="report_".$building."_bank";
	$class=get_option($field_class_name);
	$bank=get_option($field_bank_name);
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
				$final_total_without_fees=$final_data_table['total']-$final_data_table['fees'];

				$csv_data_array[0]['Accounts']='Accounts Receivable';
				$csv_data_array[0]['Debit']=" ";
				$csv_data_array[0]['Credit']='$' . number_format($final_total_without_fees,2);
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