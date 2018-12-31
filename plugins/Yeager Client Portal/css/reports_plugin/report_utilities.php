<?php
	function getBuildings($building){
		$blog_ids = array();
		if($building == 'all' || $building == '0'){
			$sites = wp_get_sites();
			foreach ($sites as $key => $blog){
				$blog_ids[$blog['blog_id']] = get_blog_details($blog)->blogname;
			}
		} else {
			$blog_ids[$building] = get_blog_details($building)->blogname;
		}
		return $blog_ids;
	}

	function getLeases(){
		$args = array('post_type' => 'lease', 'numberposts' => -1, 'post_status' => 'publish');
		return get_posts($args);
	}

	function getSaRecords(){
		$args = array('post_type' => 'sa_record', 'numberposts' => -1, 'post_status' => 'any');
		return get_posts($args);
	}

	function getEarlyVacateCredits(){
		$data = array();
		$records = getSaRecords();

		foreach($records as $record){
		  $title = $record->post_title;
		  if(preg_match('/Early Vacate Credit from Suite #?([\w-]+)/i', $title, $match)){
		    array_push($data, $record);
		  }
	  }

		return $data;
	}

	function getCreditPaymentsAppliedToInvoices(){
		$data = array();
		$records = getSaRecords();

		foreach($records as $record){
			$title = $record->post_title;
			if(preg_match('/Credit Payment Applied to Invoice/i', $title, $match)){
				array_push($data, $record);
			}
		}

		return $data;
	}

	function getInvoices($offset = 0){
		// $args = array('post_type' => 'sa_invoice', 'posts_per_page' => 500, 'offset' => $offset);
		$args = array('post_type' => 'sa_invoice', 'posts_per_page' => 500, 'post_status' => 'any', 'offset' => $offset);
		return get_posts($args);
	}

	function getSuites($post_status = 'publish'){
		$args = array('post_type' => 'suites', 'numberposts' => -1, 'post_status' => $post_status);
		return get_posts($args);
	}

	function csvWriterButton($data, $header = null, $footer = null, $file_name = null){
		// $data is a 2-dimensional array.
		$csv_data = $data;
		$csv_header = $header;
		$csv_footer = $footer;
		$csv_file_name = $file_name;
		include( plugin_dir_path( __FILE__ ) . 'csv_writer.php');
	}

	function getCompanyName($parent_id){
		$company = get_post_meta($parent_id, '_yls_company_name', true);

		if(!$company){
			$company_id = get_post_meta($parent_id, '_yl_company_id', true);
			$company = get_the_title($company_id);
		}

		if(!$company){
			$company_id = get_post_meta($parent_id, '_yl_company_name', true);
			$company = get_the_title($company_id);
		}

		return $company;
	}

	function extractSuiteNum($suite_string){
		preg_match('/Suite #? ?(\d+)/', $suite_string, $match);
		return $match[1];
	}
?>
