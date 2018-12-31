<?php
//------------ Anysoft Early Vacate Credits Report -------------
?>
<?php
	include( plugin_dir_path( __FILE__ ) . 'report_utilities.php');

	$building = $_REQUEST['_accounting_building_id'];
	$report_start_date = strtotime($_REQUEST['_accounting_start_date']);
	$report_end_date = strtotime($_REQUEST['_accounting_end_date']);

	$buildings = getBuildings($building);

	$data = array();
  $total_credits = 0;

	foreach($buildings as $key => $b){
		switch_to_blog($key);
    $records = getEarlyVacateCredits();

    foreach($records as $record){
      $content = json_decode($record->post_content);
      $title = $record->post_title;

      $date = $content->date;

      if($report_start_date <= $date || !$report_start_date){
        if($report_end_date >= $date || !$report_end_date){
          $client = get_the_title($content->client_id);

					// This is ONLY to get $suite_num. Records are filtered in getEarlyVacateCredits(),
					// which is in report_utilities.php
          preg_match('/Early Vacate Credit from Suite #?([\w-]+)/i', $title, $match);
          $suite_num = $match[1];

          $amount = $content->credit_val;
          $total_credits += $amount;

          $a = array($client, $suite_num, date('m-d-Y', $date), $amount,
                     $record->guid, $content->client_id);
          array_push($data, $a);
        }
      }
    }
	} // foreach Building
	// usort($data, function($a, $b) {
	// 	return $b[0] - $a[0];
	// });

	// $csv_data = array();
	// foreach($data as $x){
	// 	$final_date = $x[0] ? date('m-d-Y', $x[0]) : '';
	// 	array_push($csv_data, array($final_date, $x[1], $x[2], $x[3], $x[4], $x[5], $x[6]));
	// }
	// csvWriterButton($csv_data, ['Date', 'Company', 'Client', 'Check #', 'Amount', 'Notes', 'Building'],
	// 					   ['Total', null, null, null, $payments_total, null, null],
	// 					   'AdminPayments');
?>
	<h2>Early Vacate Credits Report for <?php echo $b ?></h2>
	<table>
		<thead>
			<tr>
				<th>Client</th>
        <th>Suite</th>
        <th>Date</th>
				<th>Amount</th>
    	</tr>
		</thead>
		<tbody>
<?php
		foreach($data as $row){
      $show_url = $row[4];
      preg_match('/(.+?)\/(sa_record|\?post_type=sa_record)/', $show_url, $match);
      $edit_client_link = $match[1] . '/wp-admin/post.php?post=' . $row[5] . '&action=edit';

      echo '<tr>';
				echo '<td><a href=' . $edit_client_link . ' target="_blank">' . $row[0] . '</a></td>';
				echo '<td>' . $row[1] . '</td>';
				echo '<td>' . $row[2] . '</td>';
				echo '<td>' . $row[3] . '</td>';
			echo '</tr>';
		}

		echo '<tr>';
			echo '<td>' . 'Total' . '</td>';
			echo '<td>' . '</td>';
			echo '<td>' . '</td>';
			echo '<td>' . $total_credits . '</td>';
		echo '</tr>';
?>
		</tbody>
	</table>
