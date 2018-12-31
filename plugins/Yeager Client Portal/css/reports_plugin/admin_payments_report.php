<?php
//------------------------------ Anysoft: Admin Payments Report --------------------
?>
<?php
	include( plugin_dir_path( __FILE__ ) . 'report_utilities.php');

	$building = $_REQUEST['_accounting_building_id'];
	$report_start_date = strtotime($_REQUEST['_accounting_start_date']);
	$report_end_date = strtotime($_REQUEST['_accounting_end_date']);

	$buildings = getBuildings($building);

	$data = array();
	$payments_total = 0;

	foreach($buildings as $key => $b){
		switch_to_blog($key);

		$args = array('post_type' => 'sa_payment', 'numberposts' => -1, 'meta_query' => array(
									 array('key' => '_payment_method', 'value' => 'Admin Payment', 'compare' => '=')));
		$payments = get_posts($args);

		foreach($payments as $payment){
			$payment_id = $payment->ID;
			$pay_data = get_post_meta($payment_id, '_payment_data', true);
			$check_num = $pay_data['check_number'];

			if($check_num){
				$payment_date = $pay_data['date'];
				if(!$report_start_date || $report_start_date <= $payment_date){
					if(!$report_end_date || $report_end_date >= $payment_date){
						$amount = (float) $pay_data['amount'];
						$notes = $pay_data['notes'];

						// Company and Client are available through the invoice -> lease -> company/client
						$invoice_id = get_post_meta($payment_id, '_payment_invoice', true);
						$lease_id = get_post_meta($invoice_id, '_yl_lease_id', true);
						$company_id = get_post_meta($lease_id, '_yl_company_name', true);
						$company = get_the_title($company_id);

						if(!$company){
							$company = get_post_meta($invoice_id, '_yls_company_name', true);
						}

						if(!$company){
							$company = get_the_title($invoice_id);
						}

						$client_id = get_post_meta($invoice_id, '_client_id', true);
						$client = get_the_title($client_id);

						$a = array($payment_date, $company, $client, $check_num, $amount, $notes, $b);
						array_push($data, $a);
						$payments_total += $amount;
					}
				}
			}

		}
	}
	usort($data, function($a, $b) {
		return $b[0] - $a[0];
	});

	$csv_data = array();
	foreach($data as $x){
		$final_date = $x[0] ? date('m-d-Y', $x[0]) : '';
		array_push($csv_data, array($final_date, $x[1], $x[2], $x[3], $x[4], $x[5], $x[6]));
	}
	csvWriterButton($csv_data, ['Date', 'Company', 'Client', 'Check #', 'Amount', 'Notes', 'Building'],
						   ['Total', null, null, null, $payments_total, null, null],
						   'AdminPayments');
?>
	<h2>Admin Payments Report</h2>
	<table>
		<thead>
			<tr>
				<th>Date</th>
				<th>Company</th>
				<th>Client</th>
				<th>Check #</th>
				<th>Amount</th>
				<th>Notes</th>
				<th>Building</th>
			</tr>
		</thead>
		<tbody>
<?php
		foreach($data as $key => $row){
			echo '<tr>';
				$final_date = $row[0] ? date('m-d-Y', $row[0]) : 'Blank';
				echo '<td>' . $final_date . '</td>';
				echo '<td>' . $row[1] . '</td>';
				echo '<td>' . $row[2] . '</td>';
				echo '<td>' . $row[3] . '</td>';
				echo '<td>' . number_format($row[4], 2) . '</td>';
				echo '<td>' . $row[5] . '</td>';
				echo '<td>' . $row[6] . '</td>';
			echo '</tr>';
		}

		echo '<tr>';
			echo '<td>' . 'Total' . '</td>';
			echo '<td>' . '</td>';
			echo '<td>' . '</td>';
			echo '<td>' . '</td>';
			echo '<td>' . number_format($payments_total, 2) . '</td>';
			echo '<td>' . '</td>';
		echo '</tr>';
?>
		</tbody>
	</table>
