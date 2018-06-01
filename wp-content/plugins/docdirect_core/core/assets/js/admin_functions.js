"use strict";
jQuery(document).ready(function (e) {
	
	/* ---------------------------------------
      Modal box Window
     --------------------------------------- */
	jQuery('.order-edit-wrap').on('click',".cus-open-modal", function(event){
		event.preventDefault();
		var _this	= jQuery(this);
		jQuery(_this.data("target")).show();
		jQuery(_this.data("target")).addClass('in');
		jQuery('body').addClass('cus-modal-open');
	});
	
	jQuery('.order-edit-wrap').on('click',".cus-close-modal", function(event){
		event.preventDefault();
		var _this	= jQuery(this);
		
		jQuery(_this.data("target")).removeClass('in');
		jQuery(_this.data("target")).hide();
		jQuery('body').removeClass('cus-modal-open');
	});
	
	//Save settings
	jQuery(document).on('click', '.save-data-settings', function(event) {
		event.preventDefault();
		var serialize_data = jQuery('.save-settings-form').serialize();
		var dataString = serialize_data + '&action=docdirect_save_theme_settings';
		
		var _this = jQuery(this);
		jQuery('.tg-pluginslider').append('<div class="inportusers">'+localize_vars.spinner+'</div>');
		jQuery.ajax({
			type: "POST",
			url: ajaxurl,
			dataType:"json",
			data: dataString,
			success: function(response) {
				jQuery('.tg-pluginslider').find('.inportusers').remove();
				jQuery.sticky(response.message, {classList: 'success', speed: 200, autoclose: 5000});
				window.location.reload();
			}
		});

    });
	
	/* -------------------------------------
     Upload Avatar
     -------------------------------------- */
	jQuery('#upload_dapartment_image').on('click', function () {
		"use strict";
		var $ = jQuery;
		var custom_uploader = wp.media({
			title: 'Select File',
			button: {
				text: 'Add File'
			},
			multiple: false
		})
				.on('select', function () {
					var attachment = custom_uploader.state().get('selection').first().toJSON();
					jQuery('#dapartment_image').val(attachment.url);
					jQuery('#department-src').attr('src', attachment.url);
					jQuery('#department-wrap').show();
				}).open();

	});
	
	/*---------------------------------------------------------------------
	  * Import Users
	  *---------------------------------------------------------------------*/
	 jQuery(document).on('click', '.doc-import-users', function() {
		 jQuery.confirm({
			'title': 'Import Users',
			'message': 'Are you sure, you want to import users?',
			'buttons': {
				'Yes': {
					'class': 'blue',
					'action': function () {
						var dataString = 'action=docdirect_do_import_users';
						var $this = jQuery(this);
						jQuery('#import-users').append('<div class="inportusers">'+localize_vars.spinner+'</div>');
						jQuery.ajax({
							type: "POST",
							url: ajaxurl,
							dataType:"json",
							data: dataString,
							success: function(response) {
								jQuery('#import-users').find('.inportusers').remove();
								jQuery.sticky(response.message, {classList: 'success', speed: 200, autoclose: 5000});
							}
						});
				
						return false;
					}
				},
				'No': {
					'class': 'gray',
					'action': function () {
						return false;
					}	// Nothing to do in this case. You can as well omit the action property.
				}
			}
		});
    });
	
	/*---------------------------------------------------------------------
	  * Packge type
	  *---------------------------------------------------------------------*/
	 jQuery(document).on('click', '.make-it-default', function() {
		 var _this   = jQuery(this);
		 jQuery.confirm({
			'title': 'Default Package Type?',
			'message': 'Are you sure, you want to set it default?',
			'buttons': {
				'Yes': {
					'class': 'blue',
					'action': function () {
						var _type	= _this.data('key');
						var dataString = 'type='+_type+'&action=docdirect_set_packages_default_settings';
						
						jQuery('#import-users').append('<div class="inportusers"><i class="fa fa-cog fa-3x fa-spin"></i></div>');
						jQuery.ajax({
							type: "POST",
							url: ajaxurl,
							dataType:"json",
							data: dataString,
							success: function(response) {
								jQuery('.pack-column').find('.make-it-default').removeClass('current-active');
								_this.addClass('current-active');
								jQuery('#import-users').find('.inportusers').remove();
								jQuery.sticky(response.message, {classList: 'success', speed: 200, autoclose: 5000});
							}
						});
				
						return false;
					}
				},
				'No': {
					'class': 'gray',
					'action': function () {
						return false;
					}	// Nothing to do in this case. You can as well omit the action property.
				}
			}
		});
    });
	
});