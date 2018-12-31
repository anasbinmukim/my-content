jQuery(document).ready(function($) {

	jQuery('.yl-datepicker').datetimepicker();
	jQuery('.yl-manage-invoices-table, .lease_list_table').DataTable({
		"paging":   false,
		//"bFilter": false,
        "bInfo": false
	});
	jQuery('.manage_invoices_search_field').keyup(function(e) {
		var code = e.which; // recommended to use e.which, it's normalized across browsers
	    if(code==13) {
	    	jQuery('.manage_invoices_form_submit').click();
	    }
	});

	jQuery('.primary_button.payment_option.cc_processor.nmmi').click(function(e) {
		e.preventDefault();
		var _url = jQuery(this).attr('href');

		if (jQuery('#_yl_checkout_redirect').length) {
			_url += '&yl_redirect='+jQuery('#_yl_checkout_redirect').val()+'&yl_redirect_lid='+jQuery('#_yl_checkout_redirect_lid').val();
		}
		//alert('A 2.95% convenience fee will be assessed on all card payments.');
		window.location = _url;
	});

	// BM Lease Resend LS email
	jQuery('.btn-resend-client-ls-sign-email').click(function() {

		var _t = jQuery(this);
		var _lid = _t.attr('data-lease-id');
		_t.html('<i class="fa fa-spinner fa-spin" aria-hidden="true"></i>').removeClass('btn-primary').addClass('btn-default');

		var data = {
			'action': 'yl_lease_list_resend_ls_email',
			'lid': _lid

		};
		// We can also pass the url value separately from ajaxurl for front end AJAX implementations
		jQuery.post(ajaxurl, data, function(response) {
			console.log(response);
			_t.html('<i class="fa fa-thumbs-up" aria-hidden="true"></i> Sent').removeClass('btn-default').addClass('btn-success');
		});

	});

	// BM Lease Resend Lease email
	jQuery('.btn-resend-client-l-sign-email').click(function() {

		var _t = jQuery(this);
		var _lid = _t.attr('data-lease-id');
		_t.html('<i class="fa fa-spinner fa-spin" aria-hidden="true"></i>').removeClass('btn-primary').addClass('btn-default');

		var data = {
			'action': 'yl_lease_list_resend_l_email',
			'lid': _lid

		};
		// We can also pass the url value separately from ajaxurl for front end AJAX implementations
		jQuery.post(ajaxurl, data, function(response) {
			console.log(response);
			_t.html('<i class="fa fa-thumbs-up" aria-hidden="true"></i> Sent').removeClass('btn-default').addClass('btn-success');
		});

	});

	// BM Lease List Filter
	jQuery('.bm_lease_list_filter').stop(true, true).keyup(function() {
		var _t = jQuery(this);

		console.log(_t.val());
		if (!_t.val()) {
			jQuery('.lease_list.table tbody tr').show();
		}
		else {
			jQuery('.lease_list.table tbody tr').hide();
			jQuery('.lease_list.table tbody tr:has(td:contains("'+_t.val().toLowerCase()+'"))').each(function() {
				// ('tr:has(td:contains("Gun"))')
				jQuery(this).show();
			});
		}
	});

	// Front end admin payment
	jQuery('.add-payment-frontend-submit').click(function() {
		var _invoice_id = jQuery(this).attr('data-invoice-id');
		var _nonce = jQuery('.add-payment-frontend-nonce').val();
		var _amount = jQuery('.add-payment-frontend-amount').val();
		var _id = jQuery('.add-payment-frontend-id').val();
		var _date = jQuery('.add-payment-frontend-date').val();
		var _note = jQuery('.add-payment-frontend-note').val();

		var data = {
			'action': 'sa_admin_payment',
			'serialized_fields[0][name]': 'sa_metabox_payment_amount',
			'serialized_fields[0][value]': _amount,
			'serialized_fields[1][name]': 'sa_metabox_payment_transaction_id',
			'serialized_fields[1][value]': _id,
			'serialized_fields[2][name]': 'sa_metabox_payment_date',
			'serialized_fields[2][value]': _date,
			'serialized_fields[3][name]': 'sa_metabox_payment_notes',
			'serialized_fields[3][value]': _note,
			'serialized_fields[4][name]': 'sa_metabox_invoice_id',
			'serialized_fields[4][value]': _invoice_id,
			'serialized_fields[5][name]': 'sa_metabox_payments_nonce',
			'serialized_fields[5][value]': _nonce,

		};
		// We can also pass the url value separately from ajaxurl for front end AJAX implementations
		jQuery.post(ajaxurl, data, function(response) {
			if (response.response == 'Payment Added') {
				alert("Payment Added");
				jQuery('.add-payment-frontend-amount').val('');	
				jQuery('.add-payment-frontend-id').val('');
				jQuery('.add-payment-frontend-date').val('');
				jQuery('.add-payment-frontend-note').val('');
			}
		});

	});
	
	// Front end delete invoice client
	jQuery('.remove-invoice-frontend-admin').click(function(e) {
		e.preventDefault();
		var _invoice_id = jQuery(this).attr('data-invoice-id');
		var _user_id = jQuery(this).attr('data-user-id');
		var _client_name = jQuery(this).attr('data-client-name');
		var _invoice_amount = jQuery(this).attr('data-invoice-amount');
		var _nonce = jQuery(this).attr('data-user-nonce');
		
		var delete_flag = confirm("Are you sure you would like to delete the invoice for " + _client_name + " for $" + _invoice_amount + "?");
		if (delete_flag == true) {
			var _t = jQuery(this);
			var row_current = jQuery(this).closest("tr");
			_t.html('<i class="fa fa-spinner fa-spin" aria-hidden="true"></i>');
	
			var data = {
				'action': 'remove_invoice_from_frontend_admin',
				'invoice_id': _invoice_id,
				'_nonce': _nonce,
	
			};
			// We can also pass the url value separately from ajaxurl for front end AJAX implementations
			jQuery.post(ajaxurl, data, function(response) {
				alert("Successfully Trashed!");
				row_current.hide('slow');
			});			
		}else{
			return;
		}

	});	

	// Front end resend invoice to client
	jQuery('.resend-invoice-frontend-submit').click(function(e) {
		e.preventDefault();
		var _invoice_id = jQuery(this).attr('data-invoice-id');
		var _user_id = jQuery(this).attr('data-user-id');
		var _nonce = jQuery(this).attr('data-user-nonce');

		var _t = jQuery(this);
		_t.html('<i class="fa fa-spinner fa-spin" aria-hidden="true"></i>');

		var data = {
			'action': 'sa_send_est_notification',
			'serialized_fields[0][name]': 'sa_send_metabox_send_as',
			'serialized_fields[0][value]': ajax_invoice_send_from,
			'serialized_fields[1][name]': 'sa_metabox_recipients[]',
			'serialized_fields[1][value]': _user_id,
			'serialized_fields[2][name]': 'sa_metabox_custom_recipient',
			'serialized_fields[2][value]': '',
			'serialized_fields[3][name]': 'sa_send_metabox_sender_note',
			'serialized_fields[3][value]': '',
			'serialized_fields[4][name]': 'sa_send_metabox_doc_id',
			'serialized_fields[4][value]': _invoice_id,
			'serialized_fields[5][name]': 'sa_send_metabox_notification_nonce',
			'serialized_fields[5][value]': _nonce,

		};
		// We can also pass the url value separately from ajaxurl for front end AJAX implementations
		jQuery.post(ajaxurl, data, function(response) {
			if (response.response == 'Notification Queued') {
				alert("Notification Sent");
				_t.html('<i class="fa fa-envelope-o" aria-hidden="true"></i> <i class="fa fa-share" aria-hidden="true"></i>');
			}
			else {
				_t.html('error');
			}
		});

	});


	// Stand Alone invoices
	jQuery('.standalone-type-select').change(function() {
		var _t = jQuery(this);
		var _val = _t.val();

		if (_val == 'key_r') {
			jQuery('.key-r-block').show();
			jQuery('.nsf-block').hide();
			jQuery('.building-m-block').hide();
			jQuery('.fines-block').hide();
		}else if (_val == 'nsf_fees') {			
			jQuery('.nsf-block').show();
			jQuery('.key-r-block').hide();
			jQuery('.building-m-block').hide();
			jQuery('.fines-block').hide();
		}else if (_val == 'fees_fines') {			
			jQuery('.fines-block').show();
			jQuery('.key-r-block').hide();
			jQuery('.building-m-block').hide();
			jQuery('.nsf-block').hide();
		} else {
			jQuery('.key-r-block').hide();
			jQuery('.nsf-block').hide();
			jQuery('.building-m-block').show();
		}
	}).change();

	jQuery( "#product_list_wrap" ).on( "click", ".choose_suite > a", function(event) {
		event.preventDefault();
		var _t = jQuery(this);
		var _suite = _t.closest('.sigle_product_container');

		if (_suite.hasClass('move-in-not-possible')) {
			alert('Please choose a suite which is available within your move in date');
			return;
		}

		var suiteId = _suite.attr('data-suite');
		jQuery('#suite_id').val(suiteId);

		var firstMonth = _suite.attr('data-first-month');
		jQuery('#first_month').val(firstMonth);

		var startDeposit = _suite.attr('data-deposit');
		jQuery('#rent_rate').val(startDeposit);

		var monthRate = _suite.attr('data-rate');
		jQuery('#deposit').val(monthRate);
		
		var moveInDate = _suite.attr('data-move-in-date');
		jQuery('#move_in_date').val(moveInDate);
		

		var suiteDtl = jQuery('.sigle_product_container[data-suite="'+suiteId+'"]').html();
		//jQuery('.lease_steps #lease_step_2').empty();
		jQuery('.lease_steps #lease_step_1').hide();
		jQuery('#product_list_wrap').hide();
		
		jQuery('.lease_steps #lease_step_2 .lease_info_left').prepend('<h2 class="lease_info">Lease Information</h2>'+suiteDtl);

		if (jQuery('.sigle_product_container[data-suite="'+suiteId+'"]').hasClass('is_hold')) {
			jQuery('input#holdLease').hide();
			jQuery('.lease_steps #lease_step_2 .lease_info_left').append('<br><br><h2 class="lease_info">Hold Information</h2>');
			jQuery('.lease_steps #lease_step_2 .lease_info_left').append('<p>Lessee:  <span class="hold_client_name">'+jQuery('.sigle_product_container[data-suite="'+suiteId+'"]').attr('data-hold-client-email')+'</span></p>');
			jQuery('.lease_steps #lease_step_2 .lease_info_left').append('<p>Company:  <span class="hold_client_name">'+jQuery('.sigle_product_container[data-suite="'+suiteId+'"]').attr('data-hold-company-name')+'</span></p>');

			jQuery('#save_client_lease_company_form #submitLease').unbind('click').click(function(e) {
				e.preventDefault();

				if (jQuery('#lessee_user').val() != jQuery('.sigle_product_container[data-suite="'+suiteId+'"]').attr('data-hold-client-id')) {
					alert('This suite is on hold. You can only continue with the holder lessee and company');
					return false;
				}
				if (jQuery('#company_id').val() != jQuery('.sigle_product_container[data-suite="'+suiteId+'"]').attr('data-hold-company-id')) {
					alert('This suite is on hold. You can only continue with the holder lessee and company');
					return false;
				}

				jQuery('#save_client_lease_company_form').submit();
			});
		}

		jQuery('.lease_steps #lease_step_2').fadeIn();


		jQuery(this).closest('article').addClass('lease_information');
		//jQuery(this).closest('article').removeClass('search_results');
		jQuery('#lease_step_2 .suite_details').append('<p>Move in Date: <span class="move-in-date">'+jQuery('#MoveinDate').val()+'</span></p>');
		jQuery('#lease_step_2 #submitLease').attr('data-suite', suiteId);
		jQuery('.lease_steps #lease_step_2 .choose_suite').remove();

		jQuery('.yl_timeline_line').addClass('step_2');
		jQuery('.step_client_info').addClass('active');

		if (suiteId == -1) {
			jQuery('#holdLease').hide();
		}
		else {
			jQuery('#holdLease').show();
		}
	});

	jQuery( "#lease_step_2 #is_lessee_guarantor" ).on( "change", function() {
	  if( jQuery(this).val() == 'No' ) {
		  jQuery( "#lease_step_2 #guarantor_info" ).show();
		  jQuery('.lease_info_right .lessee_info').hide();
		  jQuery('.lease_info_right .lessee_info_2').show();
	  } else {
		  jQuery( "#lease_step_2 #guarantor_info" ).hide();
		  jQuery('.lease_info_right .lessee_info').show();
		  jQuery('.lease_info_right .lessee_info_2').hide();
	  }
	});

	jQuery('#lease_step_2').on('change', '#lessee_user', function() {
		var _t = jQuery(this);

		if (_t.val()) {
			jQuery('#lease_step_2 .new_lessee_block').hide();
		}
		else {
			jQuery('#lease_step_2 .new_lessee_block').show();
		}
	});

	jQuery('#lease_step_2').on('change', '#company_id', function() {
		var _t = jQuery(this);

		if (_t.val()) {
			jQuery('#lease_step_2 .new_company_block').hide();
		}
		else {
			jQuery('#lease_step_2 .new_company_block').show();
		}
	});


	// Lease (available suites) form validation
	jQuery('#save_client_lease_company_form #submitLease').click(function (e) {
		e.preventDefault();

		if (!jQuery('#lessee_user').val()) {
			if (!jQuery('#lessee_first_name').val()) {
				alert('Please make sure you fill all the required fields'); return;
			}
			if (!jQuery('#lessee_last_name').val()) {
				alert('Please make sure you fill all the required fields'); return;
			}
			if (!jQuery('#lessee_phone').val()) {
				alert('Please make sure you fill all the required fields'); return;
			}
			if (!jQuery('#lessee_email').val()) {
				alert('Please make sure you fill all the required fields'); return;
			}
			if (!jQuery('#lessee_street_address').val()) {
				alert('Please make sure you fill all the required fields'); return;
			}
			if (!jQuery('#lessee_city').val()) {
				alert('Please make sure you fill all the required fields'); return;
			}
			if (!jQuery('#lessee_state').val()) {
				alert('Please make sure you fill all the required fields'); return;
			}
			if (!jQuery('#lessee_zip_code').val()) {
				alert('Please make sure you fill all the required fields'); return;
			}
		}

		if (jQuery('#is_lessee_guarantor').val() == 'No') {
			if (!jQuery('#guarantor_first_name').val()) {
				alert('Please make sure you fill all the required fields'); return;
			}
			if (!jQuery('#guarantor_last_name').val()) {
				alert('Please make sure you fill all the required fields'); return;
			}
			if (!jQuery('#guarantor_phone').val()) {
				alert('Please make sure you fill all the required fields'); return;
			}
			if (!jQuery('#guarantor_email').val()) {
				alert('Please make sure you fill all the required fields'); return;
			}
			if (!jQuery('#guarantor_street_address').val()) {
				alert('Please make sure you fill all the required fields'); return;
			}
			if (!jQuery('#guarantor_city').val()) {
				alert('Please make sure you fill all the required fields'); return;
			}
			if (!jQuery('#guarantor_state').val()) {
				alert('Please make sure you fill all the required fields'); return;
			}
			if (!jQuery('#guarantor_zip_code').val()) {
				alert('Please make sure you fill all the required fields'); return;
			}
		}

		jQuery('#save_client_lease_company_form').submit();
	});
});