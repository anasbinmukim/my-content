<?php
function yl_aasort (&$array, $key) {
    $sorter=array();
    $ret=array();
    reset($array);
    foreach ($array as $ii => $va) {
        $sorter[$ii]=$va[$key];
    }
    asort($sorter);
    foreach ($sorter as $ii => $va) {
        $ret[$ii]=$array[$ii];
    }
    $array=$ret;
}

function yl_lease_directory_name($lease_id){
	if(get_post_meta($lease_id, '_yl_company_directory_name', true)){
		$company_directory_name = get_post_meta($lease_id, '_yl_company_directory_name', true);
	}else{
		$company_directory_name = get_the_title(get_post_meta($lease_id, '_yl_company_name', true));
	}

	return $company_directory_name;
}

function yl_lease_automated_directory_shortcode($atts, $content = null) {
	extract(shortcode_atts(array(
		'floor' => '',
		'search' => '',
		'table' => '',
    'display_half' => '',
		'dpage' => '',
	), $atts));

		ob_start();
    echo "<div id='directory_wrapper'>";
		if($floor != ''){
			$args_suites = array(
				'post_type'  => 'suites',
				'posts_per_page' => -1,
				'post_status' => array( 'publish' ),
				'meta_query' => array(
					array(
						'key'     => '_yl_floor_level',
						'value'   => $floor,
						'compare' => 'LIKE',
					),
				),
			);
		}else{
			$args_suites = array(
				'post_type'  => 'suites',
				'posts_per_page' => -1,
				'post_status' => array( 'publish' )
			);
		}


		$loop = new WP_Query($args_suites);
		$directory_arr = array();
		$directory_company_arr = array();
		$directory_generate_data = array();
		global $yl_active_lease_in_suite;
		//print_r($yl_active_lease_in_suite);
		while ( $loop->have_posts() ) : $loop->the_post();
			$suite_cpt_id = get_the_id();
			$suite_number = get_post_meta( $suite_cpt_id, "_yl_room_number", true );
			$suite_floor = get_post_meta( $suite_cpt_id, "_yl_floor_level", true );

			$latest_active_lease_id = array_search($suite_cpt_id, $yl_active_lease_in_suite);

			$company_id = get_post_meta($latest_active_lease_id, '_yl_company_name', true);

			$com_public = get_post_meta($company_id, '_yl_com_public', true);

			//$company_name = get_the_title($company_id);

			//Company directory name from leafse
			$company_name = yl_lease_directory_name($latest_active_lease_id);

			//if active lease and company id
			if(($latest_active_lease_id > 0) && ($company_id > 0) && ($com_public != 'No') ){
				$directory_company_arr[] = $company_id;
				$directory_arr[] = array(
					'company_name' => $company_name,
					'company_id' => $company_id,
					'suite_number' => $suite_number,
					'suite_floor' => $suite_floor,
					'lease_id' => $latest_active_lease_id
				);
			}
		endwhile;

    $directoryCompanySortArr = array();
		$directory_company_arr = array_unique($directory_company_arr);
    foreach($directory_company_arr as $uq_company_id){
      $company_name = get_the_title($uq_company_id);
      $directoryCompanySortArr[] = array(
        'company_name' => $company_name,
        'company_id' => $uq_company_id
      );
    }

		$directory_result_suite = array();
		$directory_result_floor = array();

		yl_aasort($directory_arr, 'company_name');
    yl_aasort($directoryCompanySortArr, 'company_name');

    $directory_company_arr = array();
    foreach($directoryCompanySortArr as $directoryCompanySortResult){
      $directory_company_arr[] = $directoryCompanySortResult['company_id'];
    }

		// echo '<pre>';
		// print_r($directoryCompanySortArr);
		// echo '</pre>';


		foreach($directory_company_arr as $uq_company_id){
			$output_suite = array();
			$output_floor = array();
			foreach($directory_arr as $directory_r){
				if($uq_company_id == $directory_r['company_id']){
					$output_suite[] = '#'.$directory_r['suite_number'];
					$output_floor[] = $directory_r['suite_floor'];
				}
			}
			$output_floor = array_unique($output_floor);
			$directory_result_suite[$uq_company_id] = $output_suite;
			$directory_result_floor[$uq_company_id] = $output_floor;
		}

		//print_r($directory_result_suite);
		//print_r($directory_result_floor);

		$directory_company_1 = array();
		$directory_company_2 = array();

    $directory_arr_1 = array();
    $directory_arr_2 = array();

		list($directory_company_1, $directory_company_2) = array_chunk($directory_company_arr, ceil(count($directory_company_arr) / 2));
		list($directory_arr_1, $directory_arr_2) = array_chunk($directory_arr, ceil(count($directory_arr) / 2));

    //divide half list into two list
    if(($display_half != '') && ($display_half == 1)){
      $directory_company_arr = array();
      $directory_company_arr = $directory_company_1;
      $directory_company_1 = array();
  		$directory_company_2 = array();
  		list($directory_company_1, $directory_company_2) = array_chunk($directory_company_arr, ceil(count($directory_company_arr) / 2));

      //without group suite number on same row
      $directory_company_arr = array();
      $directory_company_arr = $directory_arr_1;
      $directory_arr_1 = array();
  		$directory_arr_2 = array();
  		list($directory_arr_1, $directory_arr_2) = array_chunk($directory_company_arr, ceil(count($directory_company_arr) / 2));
    }

    //divide half list into two list
    if(($display_half != '') && ($display_half == 2)){
      $directory_company_arr = array();
      $directory_company_arr = $directory_company_2;
      $directory_company_1 = array();
  		$directory_company_2 = array();
  		list($directory_company_1, $directory_company_2) = array_chunk($directory_company_arr, ceil(count($directory_company_arr) / 2));

      //without group suite number on same row
      $directory_company_arr = array();
      $directory_company_arr = $directory_arr_2;
      $directory_arr_1 = array();
  		$directory_arr_2 = array();
  		list($directory_arr_1, $directory_arr_2) = array_chunk($directory_company_arr, ceil(count($directory_company_arr) / 2));

    }



		//print_r($directory_company_arr);
		//print_r($directory_company_1);
		//print_r($directory_company_2);

		if($search == 'yes'){	?>
        <!-- Default table search enable -->
		<?php }else{ ?>
      <style type="text/css">
			.dataTables_wrapper .dataTables_filter{ display:none;}
      table thead{ display:none; }
			</style>
    <?php } ?>

		<div class="row">
			<div class="col-md-12 form-group bm_lease_list_filter_container">
			</div>
		</div>
		<div class="row">

		<?php if($table == 'double-column'){ ?>

			<div class="col-md-6">
				<table class="lease_list lease_list_table table table-striped" data-page-length="50" data-order="[[ 0, &quot;asc&quot; ]]">
          <thead>
            <tr>
              <th></th>
              <th></th>
            </tr>
          </thead>
          <tbody>
			<?php
			foreach($directory_company_1 as $uq_company_id){
				$company_name = get_the_title($uq_company_id);
				$suite_number = implode(', ', $directory_result_suite[$uq_company_id]);
				$suite_floor = implode(', ', $directory_result_floor[$uq_company_id]);
			?>
			<!-- <tr>
				<td><?php echo $company_name; ?></td>
				<td><?php echo 'Suite '. $suite_number; ?></td>
			</tr> -->

			<?php
			}
			?>


			<?php
				foreach($directory_arr_1 as $directory_result){
				//$company_name = get_the_title($directory_result['company_id']);
				$company_name = yl_lease_directory_name($directory_result['lease_id']);
				$suite_number = $directory_result['suite_number'];
				$suite_floor = $directory_result['suite_floor'];
			?>

			<tr>
				<td><?php echo $company_name; ?></td>
				<td><?php echo 'Suite '. $suite_number; ?></td>
			</tr>

			<?php
			}
			?>


					</tbody>
				</table>
			</div>

			<div class="col-md-6">
				<table class="lease_list lease_list_table table table-striped" data-page-length="50" data-order="[[ 0, &quot;asc&quot; ]]">
          <thead>
            <tr>
              <th></th>
              <th></th>
            </tr>
          </thead>
          <tbody>
			<?php
			foreach($directory_company_2 as $uq_company_id){
				$company_name = get_the_title($uq_company_id);
				$suite_number = implode(', ', $directory_result_suite[$uq_company_id]);
				$suite_floor = implode(', ', $directory_result_floor[$uq_company_id]);
			?>
			<!-- <tr>
				<td><?php echo $company_name; ?></td>
				<td><?php echo 'Suite '. $suite_number; ?></td>
			</tr> -->

			<?php
			}
			?>


			<?php
				foreach($directory_arr_2 as $directory_result){
				//$company_name = get_the_title($directory_result['company_id']);
				$company_name = yl_lease_directory_name($directory_result['lease_id']);
				$suite_number = $directory_result['suite_number'];
				$suite_floor = $directory_result['suite_floor'];
			?>

			<tr>
				<td><?php echo $company_name; ?></td>
				<td><?php echo 'Suite '. $suite_number; ?></td>
			</tr>

			<?php
			}
			?>




					</tbody>
				</table>
			</div>

		<?php }else{ ?>


			<?php if($dpage == 1){ ?>
			<div class="col-md-12">
				<table class="lease_list lease_list_table table table-striped" data-page-length="50" data-order="[[ 0, &quot;asc&quot; ]]">
          <thead>
            <tr>
              <th></th>
              <th></th>
            </tr>
          </thead>
          <tbody>

					<?php
						foreach($directory_arr_1 as $directory_result){
						//$company_name = get_the_title($directory_result['company_id']);
						$company_name = yl_lease_directory_name($directory_result['lease_id']);
						$suite_number = $directory_result['suite_number'];
						$suite_floor = $directory_result['suite_floor'];
					?>

					<tr>
						<td><?php echo $company_name; ?></td>
						<td><?php echo 'Suite '. $suite_number; ?></td>
						<td><?php echo $suite_floor; ?></td>
					</tr>

					<?php } ?>


					</tbody>
				</table>
			</div>
			<?php }elseif($dpage == 2){ ?>
				<div class="col-md-12">
					<table class="lease_list lease_list_table table table-striped" data-page-length="50" data-order="[[ 0, &quot;asc&quot; ]]">
            <thead>
              <tr>
                <th></th>
                <th></th>
              </tr>
            </thead>
            <tbody>

						<?php
							foreach($directory_arr_2 as $directory_result){
							//$company_name = get_the_title($directory_result['company_id']);
							$company_name = yl_lease_directory_name($directory_result['lease_id']);
							$suite_number = $directory_result['suite_number'];
							$suite_floor = $directory_result['suite_floor'];
						?>

						<tr>
							<td><?php echo $company_name; ?></td>
							<td><?php echo 'Suite '. $suite_number; ?></td>
							<td><?php echo $suite_floor; ?></td>
						</tr>

						<?php } ?>

						</tbody>
					</table>
				</div>
			<?php }else{ ?>

			<div class="col-md-12">
				<table class="lease_list lease_list_table table table-striped" data-page-length="50" data-order="[[ 0, &quot;asc&quot; ]]">
          <thead>
            <tr>
              <th></th>
              <th></th>
            </tr>
          </thead>
          <tbody>
			<?php
			foreach($directory_company_arr as $uq_company_id){
				$company_name = get_the_title($uq_company_id);
				$suite_number = implode(', ', $directory_result_suite[$uq_company_id]);
				$suite_floor = implode(', ', $directory_result_floor[$uq_company_id]);
			?>
			<tr>
				<td><?php echo $company_name; ?></td>
				<td><?php echo 'Suite '. $suite_number; ?></td>
				<td><?php echo $suite_floor; ?></td>
			</tr>

			<?php
			}
			?>

			<?php
				foreach($directory_arr as $directory_result){
				//$company_name = get_the_title($directory_result['company_id']);
				$company_name = yl_lease_directory_name($directory_result['lease_id']);
				$suite_number = $directory_result['suite_number'];
				$suite_floor = $directory_result['suite_floor'];
			?>

			<!-- <tr>
				<td><?php echo $company_name; ?></td>
				<td><?php echo 'Suite '. $suite_number; ?></td>
				<td><?php echo $suite_floor; ?></td>
			</tr> -->

			<?php
			}
			?>



					</tbody>
				</table>
			</div>

			<?php }//eof single column default ?>



		<?php } ?>

    </div>
    <!-- directory_wrapper -->
    </div>

    <script type="text/javascript">
      //jQuery( document ).ready(function() {
      jQuery(window).load(function(){
        var height_window = jQuery(window).height();
        //alert(height_window);
        var height_directory_wrapper = jQuery('#directory_wrapper').height();
        //alert(height_directory_wrapper);
        if(height_window > height_directory_wrapper){
          var height_diff = height_window - height_directory_wrapper;
          //alert(height_diff);
          var equal_height = height_diff / 2 - 100;
          ///alert(equal_height);
          var equal_heightpx = equal_height+'px';
          //alert(equal_heightpx);
          jQuery('#directory_wrapper').css( "padding-top", equal_heightpx );

          //alert(equal_height);
        }
      });
    </script>


		<?php
		//endif;
		$yl_content = ob_get_contents();
		ob_end_clean();

	return $yl_content;
}
add_shortcode('yeager-directory', 'yl_lease_automated_directory_shortcode');
