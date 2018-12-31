<?php
//------------ Anysoft Credit Payment Applied to Invoice Report -------------
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
    $records = getCreditPaymentsAppliedToInvoices();

    foreach($records as $record){
      $content = json_decode($record->post_content);
      $title = $record->post_title;

      $date = $content->date;

      if($report_start_date <= $date || !$report_start_date){
        if($report_end_date >= $date || !$report_end_date){
          $client = get_the_title($content->client_id);

          // Get $suite_num
          $note = $content->note;
          preg_match('/post\.php\?post=(\d+)/', $note, $match);
          $invoice_id = $match[1];

          $suite_num = get_post_meta($invoice_id, '_yl_room_number', true);

          if(!$suite_num){
            $lease_id = get_post_meta($invoice_id, '_yl_lease_id', true);
            $suite_id = get_post_meta($lease_id, '_yl_product_id', true);

            $suite = get_post($suite_id);
            preg_match('/Suite #(\d+)/', $suite->post_title, $match);
            $suite_num = $match[1];

            $amount = $content->credit_val;
            $total_credits += $amount;

            $a = array($client, $suite_num, date('m-d-Y', $date), $amount,
                      $record->guid, $content->client_id);
            array_push($data, $a);
          }
        }
      }
    }
	} // foreach Building
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
