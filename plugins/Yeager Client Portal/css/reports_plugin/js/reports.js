jQuery(document).ready(function($) {
	console.log("added reports js");
	$("#_accounting_report_id").live('change', function() {
		console.log("changed");
    if ($(this).val() == 'Open Invoice Report'){
        // DoSomething();
        $("._accounting_end_date,._accounting_start_date").attr('disabled', 'disabled');
    } else {
        // DoSomethingElse();
        $("._accounting_end_date,._accounting_start_date").removeAttr('disabled');

    }
});
});