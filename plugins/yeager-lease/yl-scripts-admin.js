jQuery(document).ready(function($) {
	jQuery('.wp-admin.post-type-cw_product').find('[class$="non-tenant-cost cmb-repeat-group-field"]').hide();


	jQuery('.wp-admin.post-type-cw_product').on('change', '[id$="avail-non-tenant"]', function() {
		var _t = jQuery(this);
		var _p = _t.closest('.cmb-repeatable-grouping');
		var _target = _p.find('[class$="non-tenant-cost cmb-repeat-group-field"]');

		if (_t.val() == 1) {
			_target.show();
		}
		else {
			_target.hide();
		}
	}).find('[id$="avail-non-tenant"]').change();
	
});