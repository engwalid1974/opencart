// copy to
// admin/view/javascript/common.js
// catalog/view/javascript/common.js

function sort_custom_fields(custom_fields) {
	$('.custom-field-area .custom-field').hide();
	$('.custom-field-area .custom-field').addClass('hidden-custom-field');
	$('.custom-field-area .custom-field').removeClass('required');

	custom_fields.forEach(function(custom_field) {
		$('.custom-field-area .custom-field' + custom_field['custom_field_id']).show();
		$('.custom-field-area .custom-field' + custom_field['custom_field_id']).removeClass('hidden-custom-field');

		if (custom_field['required']) {
			$('.custom-field-area .custom-field' + custom_field['custom_field_id']).addClass('required');
		}
	});

	custom_fields.forEach(function(custom_field) {
		if (custom_field['sort_order'] < 0) {
			num_rows = $('.custom-field-area .custom-field' + custom_field['custom_field_id']).first().parent().find('.form-group').not('.hidden-custom-field').length;

			custom_field['sort_order'] = parseInt(custom_field['sort_order']) + num_rows;
		}
	});

	custom_fields.sort(function(a, b) {
		if (a.sort_order <= 0 && b.sort_order <= 0) {
			return b.sort_order - a.sort_order;
		}

    	return a.sort_order - b.sort_order;
	});

	$('.custom-field-area .custom-field').each(function() {
		$(this).parent().find('.form-group').last().after(this);
	});

	custom_fields.forEach(function(custom_field) {
		$('.custom-field-area .custom-field' + custom_field['custom_field_id']).each(function() {
			if (custom_field['sort_order'] < 0) {
				$(this).parent().find('.form-group').first().before(this);
			} else if (custom_field['sort_order'] >= 0 && custom_field['sort_order'] < $(this).parent().find('.form-group').length) {
				$(this).parent().find('.form-group').eq(custom_field['sort_order']).before(this);
			} else {
				$(this).parent().find('.form-group').last().after(this);
			} 
		});
	});

	//Start - debug code
	//$('.custom-field-area').addClass("border border-primary");
	//$('.custom-field-area .custom-field').removeClass('text-success').addClass('text-danger');
	//$('.custom-field-area .custom-field').not('.hidden-custom-field').removeClass('text-danger').addClass('text-success');
	//$('.custom-field-area .custom-field').show();
	//End - debug code
}
