<?php
//************************************ Anysoft: CSV Writer ********************************
?>

<input type="button" id="write_csv" value="Write CSV" onclick="writeCSV()" />

<script>
	function decodeEntities(string){
		if(typeof string === 'string'){
			string = string.replace("&#8217;", "'");
			string = string.replace('&#038;', "&");
			string = string.replace('&#8211;', '-');
		}
		return string;
	}

	function writeCSV(){
		var csv_data = <?php echo json_encode($csv_data) ?>;
		var csv = "data:text/csv;charset=utf-8,";	
		
		<?php if($csv_header){ ?>
			var csv_header = <?php echo json_encode($csv_header) ?>;
			csv += csv_header.join(',') + "\n";
		<?php } ?>
		
		for(var i = 0; i < csv_data.length; i++){
			var row = '';
			for(var j = 0; j < csv_data[i].length; j++){
				row += '"' + decodeEntities(csv_data[i][j]) + '",';
			}
			csv += row + "\n";
		}
		
		<?php if($csv_footer){ ?>
			var csv_footer = <?php echo json_encode($csv_footer) ?>;
			csv += csv_footer.join(',');
		<?php } ?>
		
		var uri = encodeURI(csv.trim());

		var csv_link = document.createElement('a');
		csv_link.setAttribute('href', uri);		
		
		<?php if ($csv_file_name){ ?>
			var file_name = '<?php echo $csv_file_name ?>';
		<?php } else { ?>
			var file_name = "Report";	
		<?php } ?>
		
		
		file_name = file_name + '_' + new Date().getTime();
		
		csv_link.setAttribute('download', file_name + '.csv');
		
		document.body.appendChild(csv_link);
		csv_link.click();
	}
</script>