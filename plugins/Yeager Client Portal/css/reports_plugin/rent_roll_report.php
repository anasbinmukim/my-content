<?php
//***************************** Anysoft Rent Roll Report **************************
?>

<?php
	include( plugin_dir_path( __FILE__ ) . 'report_utilities.php');
	include( plugin_dir_path(__FILE__) . 'rent_roll_report_utilities.php');

	$building = $_REQUEST['_accounting_building_id'];
	$report_start_date = strtotime($_REQUEST['_accounting_start_date']);
	$report_end_date = strtotime($_REQUEST['_accounting_end_date']);

	$buildings = getBuildings($building);
	$data = fillBuildingDataHash($buildings, $report_start_date, $report_end_date);

	// CSV not currently implemented
	$csv_data = array();

	foreach($data as $building_name => $building_data){
		if(count($building_data) > 0){
			$rent_total = 0;
			$aux_total = 0;

?>
			<table>
				<h2>Rent Roll for <?php echo $building_name ?></h2>

				<thead>
					<tr>
						<th>Suite</th>
						<th>Rent</th>
						<th>Aux</th>
					</tr>
				</thead>
				<tbody>
<?php
			ksort($building_data);
			foreach($building_data as $ste_num => $line_items){
				$rent_items = array();
				$aux_items = array();
				$ste_rent_total = 0;
				$ste_aux_total = 0;

				foreach($line_items as $line_item){
					if($line_item){
					// $line_item = array($desc, $total, $invoice_id, $lease_id, $rent_item, $company, $guid)
						$invoice_id = $line_item[2];
						$lease_id = $line_item[3];
						$company_name = $line_item[5];


						array_push($line_item, $company_name);

						$total = (float) $line_item[1];

						if($line_item[4]){
							//echo $line_item[0] . ' : ' . $line_item[1] . '<br>';
							$ste_rent_total += $total;
							$rent_total += $total;
							array_push($rent_items, $line_item);
						} else {
							$ste_aux_total += $total;
							$aux_total += $total;
							array_push($aux_items, $line_item);
						}
					}
				}

				// Collect data for CSV
				array_push($csv_data, array($ste_num, $ste_rent_total, $ste_aux_total, $building_name));

				echo '<tr>';
					echo '<td>' . $ste_num . '</td>';

					if(count($rent_items) > 0){
						echo '<td class="accounting_entry"><a href="rent_summary" class="rent_summary_link admin_popup_link">' . number_format($ste_rent_total, 2) . '</a>';
?>
							<div class="rent_summary admin_popup closer_box">
								<div class="admin_popup_closer">x</div>
								<h3>Rent Summary for Suite #<?php echo $ste_num ?></h3>
								<table>
									<thead>
										<tr>
											<th>Suite</th>
											<th>Company</th>
											<th>Item</th>
											<th>Amount</th>
											<th>Invoice Id</th>
										</tr>
									</thead>
									<tbody>
<?php
										foreach($rent_items as $rent_item){
											echo '<tr>';
												echo '<td>' . $ste_num . '</td>';
												echo '<td>' . $rent_item[5] . '</td>';
												echo '<td>' . $rent_item[0] . '</td>';
												echo '<td class="accounting_entry">' . number_format($rent_item[1], 2) . '</td>';
												echo '<td><a href="' . $rent_item[6] . '" target="_blank">' . $rent_item[2] . '</a></td>';
											echo '</tr>';
										}
?>
									</tbody>
								</table>
							</div> <!-- end rent_summary -->
						</td>
<?php
					} else{
						echo '<td>0</td>';
					}

					if(count($aux_items) > 0){

						echo '<td class="accounting_entry"><a href="aux_summary" class="aux_summary_link admin_popup_link">' . number_format($ste_aux_total, 2) . '</a>';
?>
							<div class="aux_summary admin_popup">
								<div class="admin_popup_closer">x</div>
								<h3>Aux Summary for Suite #<?php echo $ste_num ?></h3>
								<table>
									<thead>
										<tr>
											<th>Suite</th>
											<th>Company</th>
											<th>Item</th>
											<th>Amount</th>
											<th>Invoice Id</th>
										</tr>
									</thead>
									<tbody>
<?php
										foreach($aux_items as $aux_item){
											echo '<tr>';
												echo '<td>' . $ste_num . '</td>';
												echo '<td>' . $aux_item[5] . '</td>';
												echo '<td>' . $aux_item[0];
												echo '<td class="accounting_entry">' . number_format($aux_item[1], 2) . '</td>';
												echo '<td><a href="' . $aux_item[6] . '" target="_blank">' . $aux_item[2] . '</a></td>';
											echo '</tr>';
										}
?>
									</tbody>
								</table>
							</div> <!-- end aux_summary -->
						</td>
<?php
				} else {
					echo '<td>0</td>';
				}
?>
				</tr>
<?php

		}
			echo '<tr>';
				echo '<td>' . 'Totals:' . '</td>';
				echo '<td class="accounting_entry">' . number_format($rent_total, 2) . '</td>';
				echo '<td class="accounting_entry">' . number_format($aux_total, 2) . '</td>';
			echo '</tr>';
?>
			</tbody>
		</table>
<?php
		}
	}

	// Write CSV data
	$csv_rent_total = 0;
	$csv_aux_total = 0;
	foreach($csv_data as $x){
		$csv_rent_total += $x[1];
		$csv_aux_total += $x[2];
	}

	csvWriterButton($csv_data, ['Suite', 'Rent', 'Aux', 'Building'],
							   ['TOTALS:', $csv_rent_total, $csv_aux_total],
								"Rent Roll for " . $building_name);
?>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>

<script>
	function makePopupsCloseable(){
  	$('.admin_popup_closer').click(function(){
    	$(this).closest('.admin_popup').hide(150, function(){
      	$(this).detach();
    	});
  	});
	}

	function hideShowAdminPopups(){
  	$('.admin_popup_link').click(function(e){
    	e.preventDefault();

    	var row = $(this).closest('tr')
    	var cell = $(this).closest('td');
    	var popup = $(cell).find('.admin_popup');
    	var clone = $(popup).clone().addClass('admin_popup_clone');
    	$(clone).insertAfter(row).show(250);
    	makePopupsCloseable();
  	});
	}

	function colorNegativeNumbers(selector){
		var entry_containers = $(selector);
		for(var i = 0; i < entry_containers.length; i++){
			var container = $(entry_containers[i]);
			var amount = parseFloat($(container).text());

			if(parseFloat($(container).text()) < 0){
				$(container).addClass('negative');
			}
		}
	}

	function prepareRentRollReport(){
  	$('table').animate({
    	opacity: 1.0
  	}, 250);

  	$('.admin_popup').hide();
  	hideShowAdminPopups();
		colorNegativeNumbers('.accounting_entry');

		// Move CSV button to top
		$('#write_csv').prependTo('#account_section_report .inside');
	}

	$(document).ready(function(){
  	prepareRentRollReport();
	});
</script>
