<?php
//------------------------------ Anysoft: Total Security Deposits --------------------
?>
<?php
	include( plugin_dir_path( __FILE__ ) . 'report_utilities.php');
	
	$building = $_REQUEST['_accounting_building_id'];
	$report_start_date = strtotime($_REQUEST['_accounting_start_date']);
	$report_end_date = strtotime($_REQUEST['_accounting_end_date']);
	
	$buildings = getBuildings($building);
	
	$data = array();
	$report_leases = array();
	$total_security_deposits = 0;
	
	foreach($buildings as $key => $b){
		switch_to_blog($key);
		$data[$b] = array();
	
		$leases = getLeases();
		foreach($leases as $lease){
			$id = $lease->ID;
			$lease_start_date = strtotime(get_post_meta($id, '_yl_lease_start_date', true));
			$lease_end_date = strtotime(get_post_meta($id, '_yl_ninty_day_vacate_date', true));
			
			if(!$report_start_date && !$report_end_date){
				array_push($report_leases, $lease);
				continue;
			} elseif($report_start_date && !$report_end_date){
				// If there is a report start date, and no report, so it is a 1 date report. 
				if($report_start_date >= $lease_start_date && (!$lease_end_date || $report_start_date <= $lease_end_date)){
					array_push($report_leases, $lease);
					continue;
				}
			} else {
				if($lease_start_date <= $report_end_date && (!$lease_end_date || $lease_end_date >= $report_start_date)){
					array_push($report_leases, $lease);
					continue;
				}	
			}	
		}
	
		foreach($report_leases as $report_lease){
			$lease_id = $report_lease->ID;
			$security_deposit = (float) get_post_meta($lease_id, "_yl_security_deposit", true);
			if($security_deposit){
				$total_security_deposits += $security_deposit;
				
				$ste_num = get_post_meta($lease_id, "_yl_suite_number", true);
				preg_match('/Suite #(\d+)/', $ste_num, $match);
				$ste = $match[1];
			
				if($ste_num == '-1'){
					$ste_num = 'Y-Membership';
				}
			
				$company = getCompanyName($lease_id);
				
				$a = array($ste, $ste_num, $company, $security_deposit, $b);
				array_push($data[$b], $a);				
			}
		}
	}
	
	$csv_data = array();
	foreach($data as $building_name => $building_data){
		usort($building_data, function($a, $b) {
			return $a[0] - $b[0];
		});	
		foreach($building_data as $row){
			array_push($csv_data, array($row[1], $row[2], $row[3], $row[4]));
		}
	}
	
	csvWriterButton($csv_data, ['Suite', 'Company', 'Deposit', 'Building'],
							   ['Total', null, $total_security_deposits, null],
								"Total Security Deposits for " . $building_name);
	
	foreach($data as $building_name => $building_data){			
		if(count($building_data) > 0){
			$building_total = 0;
			$csv_data = array();
			foreach($building_data as $row){
				$building_total += $row[3];
			}
	
			usort($building_data, function($a, $b) {
				return $a[0] - $b[0];
			});		

			echo '<h2>Total Security Deposits for ' . $building_name . '</h2>';

?>	
			<table>
				<thead>
					<tr>
						<th>Suite</th>
						<th>Company</th>
						<th>Deposit</th>
					</tr>
				</thead>
				<tbody>
<?php	
				foreach($building_data as $row){
					echo '<tr>';
						echo '<td>' . $row[1] . '</td>';
						echo '<td>' . $row[2] . '</td>';
						echo '<td>' . number_format($row[3], 2) . '</td>';
					echo '</tr>';
				}
			
				echo '<tr>';
					echo '<td>' . 'Totals:' . '</td>';
					echo '<td></td>';
					echo '<td>' . number_format($building_total, 2) . '</td>';
				echo '</tr>';
?>		
				</tbody>
			</table>
<?php 	
		} 
	}
?>