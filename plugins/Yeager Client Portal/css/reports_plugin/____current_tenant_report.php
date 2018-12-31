<?php
/************* Anysoft: Current Lease Report ***************/


?>
<h2>Current Tenant Report</h2>
<?php

$building = $_POST['_accounting_building_id'];
$sites = wp_get_sites();

$blog_ids = array();
if($building == 'all' || $building == '0'){
	foreach ($sites as $key => $blog){
		array_push($blog_ids, $blog['blog_id']);
	}			
} else {
	array_push($blog_ids, $building);
}

$data = array();

foreach($blog_ids as $key => $id){
	switch_to_blog($id);
	$args = array('post_type' => 'lease',
			 'post_status' => 'published',
			 'numberposts' => -1
			 );
	$results = get_posts($args);
	
	foreach($results as $key => $result){
		$id = $result->ID;
		$start_date = strtotime(get_post_meta($id, '_yl_lease_start_date', true));
		$vacate_date = get_post_meta($id, '_yl_ninty_day_vacate_date', true);
		$current_date = time();
		
		$before_vacate_date = false;
		if($vacate_date == '90 days notice' || $vacate_date == '' || strtotime($vacate_date) >= $current_date){
			$before_vacate_date = true;
		}
	
		$ste = get_post_meta($id, '_yl_suite_number', true);
		
		if($start_date <= $current_date && $before_vacate_date && preg_match('/Suite #(\d+)/', $ste, $match)){
			$n = get_post_meta($id, '_yl_l_first_name', true);
		    $middle = get_post_meta($id, '_yl_l_middle_name', true);
			if(strlen($middle) > 0){
				$n .= ' ' . $middle;
			}
			$n .= ' ' . get_post_meta($id, '_yl_l_last_name', true);
			
			$company_id = get_post_meta($id, '_yl_company_name', true);
			$a = array($match[1], $n, get_the_title($company_id));
			array_push($data, $a); 
		}	
	}
}
?>
	<table>
		<thead>
			<tr>
				<th>Suite Number</th>
				<th>Name</th>
				<th>Business</th>
			</tr>
		</thead>
		<tbody>
<?php
			usort($data, function($a, $b) {
				return $a[0] - $b[0];
			});
	
			foreach($data as $key => $x){
				echo '<tr>';
					echo '<td>' . $x[0] . '</td>';
					echo '<td>' . trim($x[1]) . '</td>';
					echo '<td>' . $x[2] . '</td>';
				echo '</tr>';
			}
?>
		</tbody>
	</table>
	
<?php	
	/*************** End Anysoft: Active Lease Report *******************/	