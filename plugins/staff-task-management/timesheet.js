jQuery(document).ready(function() {

	$j = jQuery.noConflict();

	$j(".update_timesheet_row").click(function() {
		var theRow = "#tr_" + $j(this).attr("id");

		if($j("#request_employee_timesheet_id").val() != $j("#assignee_"+$j(this).attr("id")).val()) {
			//$j(theRow).next().hide();
			//$j(theRow).hide();
		}
	});
});