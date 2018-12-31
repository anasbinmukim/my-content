jQuery(function($) {
	
	// the upload image button, saves the id and outputs a preview of the image
	var imageFrame;
	$('.meta_box_upload_image_button').click(function(event) {
		event.preventDefault();
		
		var options, attachment;
		
		$self = $(event.target);
		$div = $self.closest('div.meta_box_image');
		
		// if the frame already exists, open it
		if ( imageFrame ) {
			imageFrame.open();
			return;
		}
		
		// set our settings
		imageFrame = wp.media({
			title: 'Choose Image',
			multiple: false,
			library: {
		 		type: 'image'
			},
			button: {
		  		text: 'Use This Image'
			}
		});
		
		// set up our select handler
		imageFrame.on( 'select', function() {
			selection = imageFrame.state().get('selection');
			
			if ( ! selection )
			return;
			
			// loop through the selected files
			selection.each( function( attachment ) {
				console.log(attachment);
				var src = attachment.attributes.sizes.full.url;
				var id = attachment.id;
				
				$div.find('.meta_box_preview_image').attr('src', src);
				$div.find('.meta_box_upload_image').val(id);
			} );
		});
		
		// open the frame
		imageFrame.open();
	});
	
	// the remove image link, removes the image id from the hidden field and replaces the image preview
	$('.meta_box_clear_image_button').click(function() {
		var defaultImage = $(this).parent().siblings('.meta_box_default_image').text();
		$(this).parent().siblings('.meta_box_upload_image').val('');
		$(this).parent().siblings('.meta_box_preview_image').attr('src', defaultImage);
		return false;
	});
	
	// the file image button, saves the id and outputs the file name
	var fileFrame;
	$('.meta_box_upload_file_button').click(function(e) {
		e.preventDefault();
		
		var options, attachment;
		
		$self = $(event.target);
		$div = $self.closest('div.meta_box_file_stuff');
		
		// if the frame already exists, open it
		if ( fileFrame ) {
			fileFrame.open();
			return;
		}
		
		// set our settings
		fileFrame = wp.media({
			title: 'Choose File',
			multiple: false,
			library: {
		 		type: 'file'
			},
			button: {
		  		text: 'Use This File'
			}
		});
		
		// set up our select handler
		fileFrame.on( 'select', function() {
			selection = fileFrame.state().get('selection');
			
			if ( ! selection )
			return;
			
			// loop through the selected files
			selection.each( function( attachment ) {
				console.log(attachment);
				var src = attachment.attributes.url;
				var id = attachment.id;
				
				$div.find('.meta_box_filename').text(src);
				$div.find('.meta_box_upload_file').val(src);
				$div.find('.meta_box_file').addClass('checked');
			} );
		});
		
		// open the frame
		fileFrame.open();
	});
	
	// the remove image link, removes the image id from the hidden field and replaces the image preview
	$('.meta_box_clear_file_button').click(function() {
		$(this).parent().siblings('.meta_box_upload_file').val('');
		$(this).parent().siblings('.meta_box_filename').text('');
		$(this).parent().siblings('.meta_box_file').removeClass('checked');
		return false;
	});
	
	// function to create an array of input values
	function ids(inputs) {
		var a = [];
		for (var i = 0; i < inputs.length; i++) {
			a.push(inputs[i].val);
		}
		//$("span").text(a.join(" "));
    }
	// repeatable fields
	$('.meta_box_repeatable_add').live('click', function() {
		// clone
		var row = $(this).closest('.meta_box_repeatable').find('tbody tr:last-child');
		var clone = row.clone();
		clone.find('select.chosen').removeAttr('style', '').removeAttr('id', '').removeClass('chzn-done').data('chosen', null).next().remove();
		clone.find('input.regular-text, textarea, select').val('');
		clone.find('input[type=checkbox], input[type=radio]').attr('checked', false);
		row.after(clone);
		// increment name and id
		clone.find('input, textarea, select')
			.attr('name', function(index, name) {
				return name.replace(/(\d+)/, function(fullMatch, n) {
					return Number(n);
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
		return false;
	});
	
	$('.meta_box_repeatable_remove').live('click', function(){
		$(this).closest('tr').remove();
		return false;
	});
		
	// $('.meta_box_repeatable tbody').sortable({
	// 	opacity: 0.6,
	// 	revert: true,
	// 	cursor: 'move',
	// 	handle: '.hndle'
	// });
	
	// // post_drop_sort	
	// $('.sort_list').sortable({
	// 	connectWith: '.sort_list',
	// 	opacity: 0.6,
	// 	revert: true,
	// 	cursor: 'move',
	// 	cancel: '.post_drop_sort_area_name',
	// 	items: 'li:not(.post_drop_sort_area_name)',
 //        update: function(event, ui) {
	// 		var result = $(this).sortable('toArray');
	// 		var thisID = $(this).attr('id');
	// 		$('.store-' + thisID).val(result) 
	// 	}
 //    });

	$('.sort_list').disableSelection();

	// turn select boxes into something magical
	if (!!$.prototype.chosen)
		$('.chosen').chosen({ allow_single_deselect: true });


// 	$('.meta_box_repeatable .regular-text').bind("enterKey",function(e){
// 		e.preventDefault();
// console.log("abcd");
// 		// clone
// 		var row = $(this).closest('.meta_box_repeatable').find('tbody tr:last-child');
// 		console.log(row);
// 		var clone = row.clone();
// 		clone.find('select.chosen').removeAttr('style', '').removeAttr('id', '').removeClass('chzn-done').data('chosen', null).next().remove();
// 		clone.find('input.regular-text, textarea, select').val('');
// 		clone.find('input[type=checkbox], input[type=radio]').attr('checked', false);
// 		row.after(clone);
// 		// increment name and id
// 		clone.find('input, textarea, select')
// 			.attr('name', function(index, name) {
// 				return name.replace(/(\d+)/, function(fullMatch, n) {
// 					return Number(n) + 1;
// 				});
// 			});
// 		var arr = [];
// 		$('input.repeatable_id:text').each(function(){ arr.push($(this).val()); }); 
// 		clone.find('input.repeatable_id')
// 			.val(Number(Math.max.apply( Math, arr )) + 1);
// 		if (!!$.prototype.chosen) {
// 			clone.find('select.chosen')
// 				.chosen({allow_single_deselect: true});
// 		}
// 		//
// 		return false;
	
// 		// console.log("abc");
// 		// return false;

//    //do stuff here
// });

	$('#post').bind("keyup keypress", function(e) {
	  var code = e.keyCode || e.which;
	  if (code == 13) {
	    e.preventDefault();
	    return false;
	  }
	});
	// $('.meta_box_repeatable .regular-text').keyup(function(e){
		// $('input[name=myInput]').change(function() { 
// 			$( ".meta_box_repeatable .regular-text" ).change(function() {
//   alert( "Handler for .change() called." );
// });

		$("#normal-sortables").on('change', ".meta_box_repeatable input",function (e) {
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



		$("#normal-sortables").on('change', "#mk_auxiliary_date_startdate,#mk_auxiliary_date_enddate",function (e) {
// console.log($(this).val());
var startdate=$("#mk_auxiliary_date_startdate").val();
var enddate=$("#mk_auxiliary_date_enddate").val();
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
			console.log('Got this from the server: ' + response);
			$(".mk_table_area").empty();

			$(".mk_table_area").html(response);
			  $(".mk_table_area #table_id").DataTable({
				"paging":   false,
				"info":     false,
				"filter":false,	
				}
  				);
		});
			});

				$("#normal-sortables").on('keyup', "#table_id .regular-text",function (e) {
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
								$("#normal-sortables").on('keyup', "#table_id input",function (e) {
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
    }

	});
		$("#normal-sortables").on('keyup', ".meta_box_repeatable .regular-text",function (e) {
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