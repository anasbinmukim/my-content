<div class="wrap">
<?php
if(isset($_POST['qe_export_submit'])) {

	$exit_xls_file = QE_ROOT .'/include/qe-settings.xlsx';
	if( file_exists( $exit_xls_file ) ) {
		unlink($exit_xls_file);
	}

	/** Include PHPExcel */
	require_once dirname(__FILE__) . '/../assets/Classes/PHPExcel.php';

	// Create new PHPExcel object
	$objPHPExcel = new PHPExcel();

	// Set document properties
	$objPHPExcel->getProperties()->setCreator("CedarWaters")
								 ->setLastModifiedBy("CedarWaters")
								 ->setTitle("Office 2007 XLSX Quotes Document")
								 ->setSubject("Office 2007 XLSX Quotes Document")
								 ->setDescription("Quotes document for Office 2007 XLSX, generated using PHP classes.")
								 ->setKeywords("office 2007 openxml php")
								 ->setCategory("Quotes file");

	// Set default font
	$objPHPExcel->getDefaultStyle()->getFont()->setName('Arial')
											  ->setSize(10);

	// Add some data, resembling some different data types

	// ->setCellValue('D1', 'Last Ran Date')
	// ->setCellValue('E1', 'Sent to Email')
	// ->setCellValue('G1', 'Tag')
	// ->setCellValue('H1', 'Book Credit')

	$objPHPExcel->getActiveSheet()->setCellValue('A1', 'Title')
								  ->setCellValue('B1', 'Content')
								  ->setCellValue('C1', 'Thought Behind Quote')
									->setCellValue('D1', 'Author')
									->setCellValue('E1', 'URL')
									->setCellValue('F1', 'Tag');

if(isset($_POST['search_quotes'])){
	$search_string = $_POST['search_quotes'];
}else {
	$search_string = '';
}



	global $post;
	$all_quote_posts = array();
	$all_sorted_quote_posts = array();

	$search_string_arr = explode(',', $search_string);
	foreach($search_string_arr as $search_string_key){
			$quote_posts = array();
			$args = array(
				'post_status' => 'any',
				'post_type'   => 'quote',
				'orderby'   => 'title',
				'order'   => 'ASC',
				's'   => $search_string_key,
				'posts_per_page' => -1
			);
			$quote_posts = get_posts( $args );

			if($quote_posts)
        $all_quote_posts = array_merge($all_quote_posts, $quote_posts);

			wp_reset_postdata();
	}
	
	//Sort all quote post data
	if ( $all_quote_posts ) {
		$all_quotes_sort = array();
		foreach ( $all_quote_posts as $post ) {
			$post_id = $post->ID;
			$post_title = $post->post_title;
			$all_quotes_sort[$post->ID] = $post->post_title;
		}
		asort($all_quotes_sort);
		foreach ($all_quotes_sort as $key => $val) {
			foreach ( $all_quote_posts as $post ) {
				$post_id = $post->ID;
				if($key == $post_id){
					$all_sorted_quote_posts[] = $post;
				}
			}
		}
	}

	if ( $all_sorted_quote_posts ) {
		$cell_counter = 2;
		/*$thouth_behind_the_quote = get_post_meta($quote_id, '_cmb_thought_behind_quote', true);
		$thouth_behind_the_quote = apply_filters('the_content', $thouth_behind_the_quote);
		$thouth_behind_the_quote = str_replace(']]>', ']]&gt;', $thouth_behind_the_quote);*/
		foreach ( $all_sorted_quote_posts as $post ) {
			setup_postdata( $post );

			$post_id = get_the_ID();

			$perma_url = get_permalink();

			$author_list = wp_get_post_terms($post_id, 'quoteauthor', array("fields" => "all"));
			$authors = array();
			foreach($author_list as $author) {
				$authors[] = $author->slug;
			}
			$author = join(",", $authors);

			$tag_list = wp_get_post_terms($post_id, 'quotetag', array("fields" => "all"));
			$tags = array();
			foreach($tag_list as $tag) {
				$tags[] = $tag->slug;
			}
			$tag = join(",", $tags);

			$book_credits = wp_get_post_terms($post_id, 'quotebookcredits', array("fields" => "all"));
			$credits = array();
			foreach($book_credits as $book_credit) {
				$credits[] = $book_credit->slug;
			}
			$book_credit = join(",", $credits);

			// ->setCellValue('D'.$cell_counter, get_post_meta($post_id, '_cmb_last_ran_date', true))
			// ->setCellValue('E'.$cell_counter, get_post_meta($post_id, '_rmt_quote_email', true))
			// ->setCellValue('G'.$cell_counter, $tag)
			// ->setCellValue('H'.$cell_counter, $book_credit)

			$objPHPExcel->getActiveSheet()->setCellValue('A'.$cell_counter, get_the_title())
										  ->setCellValue('B'.$cell_counter, get_the_content())
										  ->setCellValue('C'.$cell_counter, get_post_meta($post_id, '_cmb_thought_behind_quote', true))
											->setCellValue('D'.$cell_counter, $author)
											->setCellValue('E'.$cell_counter, $perma_url)
											->setCellValue('F'.$cell_counter, $tag);
			$cell_counter++;
		}
	}else{
		echo "Not found";
		exit;
	}
	/* Restore original Post Data */
	wp_reset_postdata();

	/*$objPHPExcel->getActiveSheet()->setCellValue('A3', 'String')
								  ->setCellValue('B3', 'UTF-8')
								  ->setCellValue('C3', 'Создать MS Excel Книги из PHP скриптов');

	$objPHPExcel->getActiveSheet()->setCellValue('A4', 'Number')
								  ->setCellValue('B4', 'Integer')
								  ->setCellValue('C4', 12);

	$objPHPExcel->getActiveSheet()->setCellValue('A5', 'Number')
								  ->setCellValue('B5', 'Float')
								  ->setCellValue('C5', 34.56);

	$objPHPExcel->getActiveSheet()->setCellValue('A6', 'Number')
								  ->setCellValue('B6', 'Negative')
								  ->setCellValue('C6', -7.89);

	$objPHPExcel->getActiveSheet()->setCellValue('A7', 'Boolean')
								  ->setCellValue('B7', 'True')
								  ->setCellValue('C7', true);

	$objPHPExcel->getActiveSheet()->setCellValue('A8', 'Boolean')
								  ->setCellValue('B8', 'False')
								  ->setCellValue('C8', false);

	$dateTimeNow = time();
	$objPHPExcel->getActiveSheet()->setCellValue('A9', 'Date/Time')
								  ->setCellValue('B9', 'Date')
								  ->setCellValue('C9', PHPExcel_Shared_Date::PHPToExcel( $dateTimeNow ));
	$objPHPExcel->getActiveSheet()->getStyle('C9')->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_DATE_YYYYMMDD2);

	$objPHPExcel->getActiveSheet()->setCellValue('A10', 'Date/Time')
								  ->setCellValue('B10', 'Time')
								  ->setCellValue('C10', PHPExcel_Shared_Date::PHPToExcel( $dateTimeNow ));
	$objPHPExcel->getActiveSheet()->getStyle('C10')->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_DATE_TIME4);

	$objPHPExcel->getActiveSheet()->setCellValue('A11', 'Date/Time')
								  ->setCellValue('B11', 'Date and Time')
								  ->setCellValue('C11', PHPExcel_Shared_Date::PHPToExcel( $dateTimeNow ));
	$objPHPExcel->getActiveSheet()->getStyle('C11')->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_DATE_DATETIME);

	$objPHPExcel->getActiveSheet()->setCellValue('A12', 'NULL')
								  ->setCellValue('C12', NULL);*/



	/*$objRichText = new PHPExcel_RichText();
	$objRichText->createText('你好 ');

	$objPayable = $objRichText->createTextRun('你 好 吗？');
	$objPayable->getFont()->setBold(true);
	$objPayable->getFont()->setItalic(true);
	$objPayable->getFont()->setColor( new PHPExcel_Style_Color( PHPExcel_Style_Color::COLOR_DARKGREEN ) );

	$objRichText->createText(', unless specified otherwise on the invoice.');

	$objPHPExcel->getActiveSheet()->setCellValue('A13', 'Rich Text')
								  ->setCellValue('C13', $objRichText);

	$objRichText2 = new PHPExcel_RichText();
	$objRichText2->createText("black text\n");

	$objRed = $objRichText2->createTextRun("red text");
	$objRed->getFont()->setColor( new PHPExcel_Style_Color(PHPExcel_Style_Color::COLOR_RED  ) );

	$objPHPExcel->getActiveSheet()->getCell("C14")->setValue($objRichText2);
	$objPHPExcel->getActiveSheet()->getStyle("C14")->getAlignment()->setWrapText(true);*/


	$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(50);
	$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(50);
	$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
	$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
	$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
	// $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);
	// $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setAutoSize(true);

	// Rename worksheet
	$objPHPExcel->getActiveSheet()->setTitle('Datatypes');


	// Set active sheet index to the first sheet, so Excel opens this as the first sheet
	$objPHPExcel->setActiveSheetIndex(0);

	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
	//$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');

	// We'll be outputting an excel file
	/*header('Content-type: application/vnd.ms-excel');
	header('Content-Disposition: attachment; filename="quotes.xls"');
	$objWriter->save('php://output');*/
	$current_file = 'qe-settings.php';
	$export_file = 'qu_export_'.time().'_quotes.xlsx';
	$objWriter->save(str_replace('.php', '.xlsx', __FILE__));
	$objWriter->save(str_replace($current_file, $export_file, __FILE__));

?>
	If not download automatically please <a href="<?php echo QE_URL; ?>include/<?php echo $export_file; ?>" target="_blank">click here</a> to download manually.
<?php

}
?>
<h2 style="margin-bottom:15px;"><?php echo __('Export'); ?></h2>
<form name="it_settings" method="post" action="edit.php?post_type=quote&page=qe-export">
	<p>Search and Export: <input type="text" name="search_quotes" id="search_quotes" value="" /></p>
	<p class="submit">
		<input type="submit" name="qe_export_submit" class="button-primary" value="<?php _e('Export') ?>" />
	</p>
</form>
</div>
