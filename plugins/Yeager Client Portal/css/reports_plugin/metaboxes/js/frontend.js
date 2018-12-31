jQuery(document).ready(function($) {

	$("#apply_coupon").click(function(event) {

	});

	$("body").on('click', '#apply_coupon', function(event) {
		event.preventDefault();
		var prval=$("#promotional_code").val();

		var data = {
			'action': 'apply_coupon',
			'prval': prval,
		};

		jQuery.post(ajaxurl, data, function(response) {
			if(response == "") {
				$("#mk_aux_promo").val('');
				$("#promotional_code").removeClass('success_aux');
				$("#promotional_code").addClass('error_aux');
			}
			else{
				$("#mk_aux_promo").val(response);	
				$("#promotional_code").removeClass('error_aux');
				$("#promotional_code").addClass('success_aux');
			}	

		});
	});


  $("body").on('keydown', function(event) {

  // $("body").keydown(function(event){
    if(event.keyCode == 13) {
      event.preventDefault();
      return false;
    }
  });


 //  	$('#mkk_aux').bind("keyup keypress", function(e) {
	//   var code = e.keyCode || e.which;
	//   if (code == 13) {
	//     e.preventDefault();
	//     return false;
	//   }
	// });
  // .on('click', '.selector', function(event) {
  // 	event.preventDefault();
  // 	/* Act on the event */
  // });
	// $('.meta_box_repeatable .regular-text').keyup(function(e){
		// $('input[name=myInput]').change(function() { 
// 			$( ".meta_box_repeatable .regular-text" ).change(function() {
//   alert( "Handler for .change() called." );
// });

		$("body").on('change', ".meta_box_repeatable input",function (e) {
		var str =$(this).val();
		// console.log(str+"mkk");
		if (str.indexOf("$") >= 0)
		{
		console.log("$");
		}
		else{
		$(this).val('$' + $(this).val());
//		console.log("$");
		}
		});


// $(window).keydown(function(event){
// 	alert("hey");

//     if(event.keyCode == 13) {
//       event.preventDefault();
//       return false;
//     }
//   });


//   $('#mkk_aux').on('keyup keypress', function(e) {
// 	alert("hey");
//   var keyCode = e.keyCode || e.which;
//   if (keyCode === 13) { 
//     e.preventDefault();
//     return false;
//   }
// });

// $('#mkk_aux').on('submit', function(event) {
// 	alert("hi");
// 	event.preventDefault();
// 	  var keyCode = e.keyCode || e.which;
//   if (keyCode === 13) { 
//     e.preventDefault();
//     return false;
//   }
// 	/* Act on the event */
// });

	// $('#mkk_aux').bind("keyup keypress", function(e) {
	// // alert("hi");
		

	//   var code = e.keyCode || e.which;
	//   if (code == 13) {
	//     e.preventDefault();
	//     return false;
	//   }
	// });





		$("body").on('change', "#mk_auxiliary_date_startdate,#mk_auxiliary_date_enddate",function (e) {
// console.log($(this).val());
var startdate=$("#mk_auxiliary_date_startdate").val();
var enddate=$("#mk_auxiliary_date_enddate").val();

if(startdate!="" && enddate!="" )
{

			$(".mk_table_area").html("Processing.....");


// var date=$(this).val();
var postid=$('.mk_table_area').attr('id');
// $(this).val();
console.log(postid);
console.log("mkk");
		var data = {
			'action': 'my_action',
			'startdate': startdate,
			'enddate': enddate,
			'postid':postid
		};

		// since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
		jQuery.post(ajaxurl, data, function(response) {
			// console.log('Got this from the server: ' + response);
			$(".save_post_auxi").removeAttr('disabled');
			$(".mk_table_area").empty();

			$(".mk_table_area").html(response);
			 //  $(".mk_table_area #table_id").DataTable({
				// "paging":   false,
				// "info":     false,
				// "filter":false,	
				// }
  		// 		);
		});


}

			});



						$("body").on('keyup', "#table_id .regular-text",function (e) {
// console.log("abcd1");

		e.preventDefault();
		var str =$(this).val();
		console.log(str+"mkk");
		if (str.indexOf("$") >= 0)
		{
	//	console.log("$");
		}
		else{
		$(this).val('$' + $(this).val());
		// console.log("$");
		}


	});


								$("body").on('keyup', "#table_id input",function (e) {
// console.log("abcd1");

		e.preventDefault();
		  if(e.keyCode == 13)
    {
  // var index = $(this).parent().index();
  var td = $(this).parent().index();
  var tr = $(this).parent().parent().index()+1;
    // var row = $(this).parentNode.rowIndex;
  // console.log(td);
  // console.log(tr);
  $("#table_id  tr:eq(0) td:eq(2) input").val("mkkkkkkkkkkkkk");
   // $(this).parent().parent().parent().find('tr:eq('+tr+')').find('td:eq('+td+') input').val("mkkkk");
  // $("#table_id  tr:eq(0) td:eq(2)").text("mkkkkkkkkkkkkk");
  $(this).parent().parent().parent().find('tr:eq('+tr+')').find('td:eq('+td+')').find('input').focus();
  // $("#table_id tr:eq("+tr+") td:eq("+td+") input").focus();
  // console.log(row);
    

	  var code = e.keyCode || e.which;
	  if (code == 13) {
	    e.preventDefault();
	    return false;
	  }

    }

	});


		$("body").on('keyup', ".meta_box_repeatable .regular-text",function (e) {
// console.log("abcd1");

		e.preventDefault();
		var str =$(this).val();
	//	console.log(str+"mkk");
		if (str.indexOf("$") >= 0)
		{
	//	console.log("$");
		}
		else{
		$(this).val('$' + $(this).val());
		// console.log("$");
		}

    if(e.keyCode == 13)
    {
			// console.log("abcd2");

					// e.preventDefault();
			// console.log("abcd");
					// clone
		var row = $(this).closest('.meta_box_repeatable').find('tbody tr:last-child');
		console.log(row);
		var clone = row.clone();
		clone.find('select.chosen').removeAttr('style', '').removeAttr('id', '').removeClass('chzn-done').data('chosen', null).next().remove();
		clone.find('input.regular-text, textarea, select').val('');
		clone.find('input[type=checkbox], input[type=radio]').attr('checked', false);
		row.after(clone);
		// increment name and id
		clone.find('input, textarea, select')
			.attr('name', function(index, name) {
				// return name.replace(/(\d+)/, function(fullMatch, n) {
				// 	return Number(n) + 1;
				// });
			
		return name.replace(/[0-9]+(?!.*[0-9])/, function(match) {
        return parseInt(match, 10)+1;
    });

			});
		var arr = [];
		$('input.repeatable_id:text').each(function(){ arr.push($(this).val()); }); 
		clone.find('input.repeatable_id')
			.val(Number(Math.max.apply( Math, arr )) + 1);
		if (!!$.prototype.chosen) {
			clone.find('select.chosen')
				.chosen({allow_single_deselect: true});
		}
		//
		// $(this).closest('.meta_box_repeatable').find('tbody tr:last-child input').focus();
		return false;
	
		// console.log("abc");
		// return false;

   //do stuff here


        $(this).trigger("enterKey");
    }


});







});