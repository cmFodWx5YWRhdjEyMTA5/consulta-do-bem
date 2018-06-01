"use strict";
jQuery(document).ready(function (e) {
	
	//Get specialities
	jQuery(document).on('change', '.ajax-specialities', function() {
        var id = jQuery(this).val();
		var current_user_id = jQuery('.current_user_id').val();
        var dataString = 'id=' + id + '&user_id=' + current_user_id + '&action=docdirect_get_specialities_ajax';
        var _this = jQuery(this);
		_this.parent('.user_type').append("<i class='fa fa-spinner fa-spin'></i>");
		
		jQuery.ajax({
            type: "POST",
            url: ajaxurl,
            data: dataString,
			dataType:"json",
            success: function(response) {
				_this.parent('.user_type').find('.fa-spin').remove();
				if( response.type == 'error' ) {
					jQuery.sticky(response.message, {classList: 'important', speed: 200, autoclose: 5000});
				} else{
					jQuery('.specialities-list ul').html(response.data);
				}
				
            }
        });

        return false;
    });
	
	jQuery(document).on('click', '.add_more_services', function (event) {
		//alert('asd');
		var widget	= jQuery(this).data('widget');
		var $html	= '';
		$html	= '<div class="data-services"><p class="data-day"><label for="">Day</label><input type="text" id="day" name="widget-opening_hours['+widget+'][day][]" value="" class="widefat" /></p><p class="data-hours"><label for="">Time</label><input type="text" id="time" name="widget-opening_hours['+widget+'][time][]" value="" class="widefat" /></p><p class="data-closed"><input type="hidden" id="closed" name="widget-opening_hours['+widget+'][closed][]" value="off" class="widefat" /><input type="checkbox" id="closed" name="widget-opening_hours['+widget+'][closed][]" value="" class="widefat" /><label for="">Closed?</label></p><p class="data-del"><a href="javascript:;" class="delete-me"><i class="fa fa-times"></i></a></p>';
		
		jQuery('.accordion_html').append($html);
	});
	
	jQuery(document).on('click','.delete-me', function (e) {
		jQuery(this).parents('.data-services').remove();
	});
	
	//Accordion
	jQuery(document).on('click', '.add_more_accordion', function (event) {
		//alert('asd');
		var widget	= jQuery(this).data('widget');
		var $html	= '';
		$html	= '<div class="data-services"><p><label for="">Heading</label><input type="text" id="heading" name="widget-tg_accordion['+widget+'][heading][]" value="" class="widefat" /></p> <p><label for="description">Description</label><textarea id="description"  rows="8" cols="10" name="widget-tg_accordion['+widget+'][description][]" class="widefat"></textarea></p><p class="data-del"><a href="javascript:;" class="delete-me"><i class="fa fa-times"></i></a></p></div>';
		
		jQuery('.accordion_html').append($html);
	});
	
	jQuery(document).on('click','.delete-accordion', function (e) {
		jQuery(this).parents('.data-services').remove();
	});
	
	jQuery(document).on('click','#closed', function (e) {
		var $this	= jQuery(this);
		if ( jQuery(this).is(':checked' )) {
			jQuery(this).val('on');
		} else{
			jQuery(this).val('off');
		}
	});
	/* -------------------------------------
     Upload Avatar
     -------------------------------------- */
	jQuery('#upload-user-avatar').on('click', function () {
		"use strict";
		var $ = jQuery;
		var $this = jQuery(this);
		var custom_uploader = wp.media({
			title: 'Select File',
			button: {
				text: 'Add File'
			},
			multiple: false
		})
				.on('select', function () {
					var attachment = custom_uploader.state().get('selection').first().toJSON();
					jQuery('#userprofile_media').val(attachment.id);
					jQuery('#avatar-src').attr('src', attachment.url);
					jQuery('#avatar-wrap').show();
					$this.parents('tr').next('tr').find('.backgroud-image').show();
					$this.parents('tr').next('tr').attr('class','');
				}).open();

	});
	
	
	
	jQuery(document).on('click','.delete-auhtor-media', function (e) {
		jQuery(this).parents('.backgroud-image').find('img').attr('src', '');
		jQuery(this).parents('tr').prev('tr').find('.media-image').val('');
		jQuery(this).parents('.backgroud-image').hide();
	});
	
	/* -------------------------------------
     Upload Avatar
     -------------------------------------- */
	jQuery('#upload-user-banner').on('click', function () {
		"use strict";
		var $ = jQuery;
		var $this = jQuery(this);
		var custom_uploader = wp.media({
			title: 'Select File',
			button: {
				text: 'Add File'
			},
			multiple: false
		})
		.on('select', function () {
			var attachment = custom_uploader.state().get('selection').first().toJSON();
			jQuery('#userprofile_banner').val(attachment.id);
			jQuery('#banner-src').attr('src', attachment.url);
			jQuery('#banner-wrap').show();
			$this.parents('tr').next('tr').find('.backgroud-image').show();
			$this.parents('tr').next('tr').attr('class','');
		}).open();

	});
	
	jQuery(document).on('click','.delete-auhtor-banner', function (e) {
		jQuery(this).parents('.backgroud-image').find('img').attr('src', '');
		jQuery(this).parents('tr').prev('tr').find('.media-image').val('');
		jQuery(this).parents('.backgroud-image').hide();
	});
	
	
	/* -------------------------------------
     Upload user insurance
     -------------------------------------- */
	jQuery('#upload-insurance').on('click', function () {
		"use strict";
		var $ = jQuery;
		var $this = jQuery(this);
		var custom_uploader = wp.media({
			title: 'Select File',
			button: {
				text: 'Add File'
			},
			multiple: false
		})
		.on('select', function () {
			var attachment = custom_uploader.state().get('selection').first().toJSON();
			jQuery('#insurance_logo').val(attachment.url);
			jQuery('#insurance-src').attr('src', attachment.url);
			jQuery('#insurance-wrap').show();
			$this.parents('tr').next('tr').find('.backgroud-image').show();
			$this.parents('tr').next('tr').attr('class','');
		}).open();

	});
	
	jQuery(document).on('click','.delete-insurance', function (e) {
		jQuery(this).parents('.backgroud-image').find('img').attr('src', '');
		jQuery(this).parents('tr').prev('tr').find('.media-image').val('');
		jQuery(this).parents('.backgroud-image').hide();
	});
	
	jQuery(document).on('change', '.currency_symbol', function() {
        var code = jQuery(this).val();
        var dataString = 'code=' + code + '&action=docdirect_get_currency_symbol';
        var $this = jQuery(this);
        jQuery($this).parent('.fw-inner-option').append("<i class='fa fa-refresh fa-spin'></i>");
        jQuery.ajax({
            type: "POST",
            url: ajaxurl,
            data: dataString,
            success: function(response) {
                jQuery("#fw-option-currency_sign").val(response);
                jQuery($this).parent('.fw-inner-option').find('i').remove();
            }
        });

        return false;
    });
	
	
	//Time Pciker
	jQuery('.schedule-pickr').datetimepicker({
	  datepicker:false,
	  format:'H:i'
	});
	
	//Add Awards
	jQuery(document).on('click','.add-new-awards',function(){
		var load_awards = wp.template( 'load-awards' );
		var counter	= jQuery( '.awards_wrap > tbody' ).length;
		var load_awards	= load_awards(counter);
		jQuery( '.awards_wrap' ).append(load_awards);
		
		//init date
		jQuery('.award_datepicker').datetimepicker({
		  format:localize_vars.calendar_format,
		  timepicker:false
		});
	});;
	
	//init date
	jQuery('.award_datepicker').datetimepicker({
	  format:localize_vars.calendar_format,
	  timepicker:false
	});
		 
	//Delete Awards
	jQuery(document).on('click','.award-action .delete-me',function(){
		var _this	= jQuery(this);
		jQuery.confirm({
			'title': 'Delete Award',
			'message': 'Are you sure, you want to delete this Award?',
			'buttons': {
				'Yes': {
					'class': 'blue',
					'action': function () {
						_this.parents('.awards_item').remove();
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
	
	//Edit Awards
	jQuery(document).on('click','.award-action .edit-me',function(){
		jQuery('.award-data').hide();
		jQuery(this).parents('.awards_item').find('.award-data').toggle();
	});
	
	
	//Add Educations
	jQuery(document).on('click','.add-new-educations',function(){
		var load_educations = wp.template( 'load-educations' );
		var counter	= jQuery( '.educations_wrap > tbody' ).length;
		var load_educations	= load_educations(counter);
		jQuery( '.educations_wrap' ).append(load_educations);
		
		jQuery('.edu_start_date_'+counter).datetimepicker({
		  format:localize_vars.calendar_format,
		  onShow:function( ct ){
		   this.setOptions({
			maxDate:jQuery('.edu_end_date_'+counter).val()? _change_date_format( jQuery('.edu_end_date_'+counter).val() ):false
		   })
		  },
		  timepicker:false
		 });
		 jQuery('.edu_end_date_'+counter).datetimepicker({
		  format:localize_vars.calendar_format,
		  onShow:function( ct ){
		   this.setOptions({
			minDate:jQuery('.edu_start_date_'+counter).val()? _change_date_format( jQuery('.edu_start_date_'+counter).val() ):false
		   })
		  },
		  timepicker:false
		 });
	
	});
	
	//Add Experience
	jQuery(document).on('click','.add-new-experiences',function(){
		var load_educations = wp.template( 'load-experiences' );
		var counter	= jQuery( '.experiences_wrap > tbody' ).length;
		var load_educations	= load_educations(counter);
		jQuery( '.experiences_wrap' ).append(load_educations);
		
		jQuery('.exp_start_date_'+counter).datetimepicker({
		   format:localize_vars.calendar_format,
		  onShow:function( ct ){
		   this.setOptions({
			maxDate:jQuery('.exp_end_date_'+counter).val()? _change_date_format( jQuery('.exp_end_date_'+counter).val() ):false
		   })
		  },
		  timepicker:false
		 });
		 jQuery('.exp_end_date_'+counter).datetimepicker({
		  format:localize_vars.calendar_format,
		  onShow:function( ct ){
		   this.setOptions({
			minDate:jQuery('.exp_start_date_'+counter).val()? _change_date_format( jQuery('.exp_start_date_'+counter).val() ):false
		   })
		  },
		  timepicker:false
		 });
	
	});
	
	//Add Price/Service list
	jQuery(document).on('click','.add-new-prices',function(){
		var load_prices_tpl = wp.template( 'load-prices' );
		var counter	= jQuery( '.prices_wrap > tbody' ).length;
		var list	= load_prices_tpl(counter);
		jQuery( '.prices_wrap' ).append(list);
		
	});
	
	//Delete Awards
	jQuery(document).on('click','.education-action .delete-me',function(){
		var _this	= jQuery(this);
		jQuery.confirm({
			'title': 'Delete Education',
			'message': 'Are you sure, you want to delete this Education?',
			'buttons': {
				'Yes': {
					'class': 'blue',
					'action': function () {
						_this.parents('.educations_item').remove();
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
	
	//Delete Experience
	jQuery(document).on('click','.experience-action .delete-me',function(){
		var _this	= jQuery(this);
		jQuery.confirm({
			'title': 'Delete Experience',
			'message': 'Are you sure, you want to delete this Experience?',
			'buttons': {
				'Yes': {
					'class': 'blue',
					'action': function () {
						_this.parents('.experiences_item').remove();
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
	
	//Delete Prices
	jQuery(document).on('click','.prices-action .delete-me',function(){
		var _this	= jQuery(this);
		jQuery.confirm({
			'title': 'Delete Price/Service?',
			'message': 'Are you sure, you want to delete this price/service list?',
			'buttons': {
				'Yes': {
					'class': 'blue',
					'action': function () {
						_this.parents('.prices_item').remove();
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
	
	//Awards Sortable
	jQuery( ".awards_wrap" ).sortable({
		cursor: "move"
	});
	
	//Education Sortable
	jQuery( ".educations_wrap" ).sortable({
		cursor: "move"
	});
	
	//Experience Sortable
	jQuery( ".experiences_wrap" ).sortable({
		cursor: "move"
	});
	
	//Prices Sortable
	jQuery( ".prices_wrap" ).sortable({
		cursor: "move"
	});
	
	//Edit Awards
	jQuery(document).on('click','.education-action .edit-me',function(){
		jQuery('.education-data').hide();
		jQuery(this).parents('tr').next('tr').find('.education-data').toggle();
	});
	
	//Edit Experience
	jQuery(document).on('click','.experience-action .edit-me',function(){
		jQuery('.experience-data').hide();
		jQuery(this).parents('tr').next('tr').find('.experience-data').toggle();
	});
	
	//Edit Prices
	jQuery(document).on('click','.prices-action .edit-me',function(){
		jQuery('.prices-data').hide();
		jQuery(this).parents('tr').next('tr').find('.prices-data').toggle();
	});
	
	/* ---------------------------------------
    Language Choosen
     --------------------------------------- */
	var config = {
	  '.chosen-select'           : {placeholder_text_multiple:localize_vars.select_options},
      '.chosen-select-deselect'  : {allow_single_deselect:false},
      '.chosen-select-no-single' : {disable_search_threshold:10},
      '.chosen-select-no-results': {no_results_text:localize_vars.no_results_text},
      '.chosen-select-width'     : {width:"95%"}
    }
	
    for (var selector in config) {
      jQuery(selector).chosen(config[selector]);
    }
	
	/* ---------------------------------------
    	Location Choosen
     --------------------------------------- */
	 var config = {
      '.locations-select'         : {placeholder_text_multiple:localize_vars.select_options},
      '.chosen-select-deselect'  : {allow_single_deselect:false},
      '.chosen-select-no-single' : {disable_search_threshold:10},
      '.chosen-select-no-results': {no_results_text:localize_vars.no_results_text},
      '.chosen-select-width'     : {width:"95%"}
    }
    
	for (var selector in config) {
      jQuery(selector).chosen(config[selector]);
    }

	/*---------------------------------------------------------------------
	 * Z Multi Uploader
	 *---------------------------------------------------------------------*/
	jQuery('.gallery-list').sortable();
	var gallery_container	= jQuery('.gallery-container');
	var gallery_frame;
	var gallery_ids 	 = jQuery('#gallery_ids');
	var reset_gallery   = jQuery('#reset_gallery');
	var gallery_images  = jQuery('ul.gallery-list');
	jQuery('.multi_open').on('click', function(event) {
		
		var $el = jQuery(this);
		event.preventDefault();
		if ( gallery_frame ) {
			gallery_frame.open();
			return;
		}
		
		// Create the media frame.
		gallery_frame = wp.media.frames.gallery = wp.media({
			title	: $el.data('choose'),
			library  : { type : 'image'},
			button   : {
				text : $el.data('update'),
			},
			states   : [
				new wp.media.controller.Library({
					title		: $el.data('choose'),
					 filterable: 'image',
					multiple	 : true,
				})
			]
		});

		// When an image is selected, run a callback.
		gallery_frame.on( 'select', function() {
			var selection = gallery_frame.state().get('selection');
			selection.map( function( attachment ) {
				attachment = attachment.toJSON();
				//console.log(attachment);
				if ( attachment.id ) {
					gallery_container.show();
					reset_gallery.show();
					
					var image_url	= attachment.url;
					if(typeof(attachment.sizes.thumbnail.url) != "undefined" && attachment.sizes.thumbnail.url !== null) {
						image_url	= attachment.sizes.thumbnail.url;
					}
					
					gallery_images.append('\
							<li class="image" data-attachment_id="' + attachment.id + '">\
								<input type="hidden" value="' + attachment.id + '" name="user_gallery[' + attachment.id + '][attachment_id]">\
								<input type="hidden" value="' + image_url + '" name="user_gallery[' + attachment.id + '][url]">\
								<img src="' + image_url + '" />\
								<a href="javascript:;" class="del-node" title="Delete"><i class="fa fa-times"></i></a>\
							</li>');
					}
				});

			});
			// Finally, open the modal.
			gallery_frame.open();

		});
	
	/*---------------------------------------------------------------------
	  * Z Delete gallery Node
	  *---------------------------------------------------------------------*/
	 jQuery( '.gallery-list' ).delegate( "a", "click", function() { 
		jQuery(this).parent().remove();
	 });
	 
	 /*---------------------------------------------------------------------
	  * Z Gallery
	  *---------------------------------------------------------------------*/
	 jQuery( '.zaraar-buttons' ).delegate( "#reset_gallery", "click", function() { 
		jQuery('.gallery-list').html('');
		jQuery(this).hide();
	 });
	
	 //Validate Days
	 jQuery('input[name="featured_days"]').on('keyup', function() {
        validateAmount();
     });
	 
	 //Validate Days
	 jQuery(document).on('click','.remove_featured', function() {
       var _this	= jQuery(this);
	   jQuery.confirm({
			'title': 'Exclude from featured',
			'message': 'Are you sure, you want to remove user from featured list?',
			'buttons': {
				'Yes': {
					'class': 'blue',
					'action': function () {
						jQuery('.feature_time_stamp').val('');
						_this.parents('.featured_detail_wrap').remove();
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



/*Validation for Donation Amount */
function validateAmount() {
	if (isNaN(jQuery.trim(jQuery('input[name=featured_days]').val()))) {
		jQuery('input[name=featured_days]').val("");

	} else {
		var amt = jQuery('input[name=featured_days]').val();
		if (amt != '') {
			if (amt.length > 16) {
				amt = amt.substr(0, 16);
				jQuery(".featured_days").val(amt);
			}
			//amount = amt;
			//jQuery("#amountToShow").html(jQuery.trim(amount));
			return true;
		} else {
			//amount = gloAmount;
			//jQuery("#amountToShow").html("--");
			return true;
		}
	}
}

//Convert date formate for min and max date
function _change_date_format(dateStr) {
    var calendar_format	= localize_vars.calendar_format;
	if( calendar_format === 'd-m-Y' ){
		var parts = dateStr.split("-");
		var _date	= parts[2]+'-'+parts[1]+'-'+parts[0];
		return _date;
	} else {
		return dateStr;
	}
}
	
/* ---------------------------------------
 Confirm Box
 --------------------------------------- */
(function ($) {

		jQuery.confirm = function (params) {
	
			if (jQuery('#confirmOverlay').length) {
				// A confirm is already shown on the page:
				return false;
			}
	
			var buttonHTML = '';
			jQuery.each(params.buttons, function (name, obj) {
	
				// Generating the markup for the buttons:
	
				buttonHTML += '<a href="#" class="button ' + obj['class'] + '">' + name + '<span></span></a>';
	
				if (!obj.action) {
					obj.action = function () {
					};
				}
			});
	
			var markup = [
				'<div id="confirmOverlay">',
				'<div id="confirmBox">',
				'<h1>', params.title, '</h1>',
				'<p>', params.message, '</p>',
				'<div id="confirmButtons">',
				buttonHTML,
				'</div></div></div>'
			].join('');
	
			jQuery(markup).hide().appendTo('body').fadeIn();
	
			var buttons = jQuery('#confirmBox .button'),
					i = 0;
	
			jQuery.each(params.buttons, function (name, obj) {
				buttons.eq(i++).click(function () {
	
					// Calling the action attribute when a
					// click occurs, and hiding the confirm.
	
					obj.action();
					jQuery.confirm.hide();
					return false;
				});
			});
		}
	
		jQuery.confirm.hide = function () {
			jQuery('#confirmOverlay').fadeOut(function () {
				jQuery(this).remove();
			});
		}
	
})(jQuery);

/*
	Sticky v2.1.2 by Andy Matthews
	http://twitter.com/commadelimited

	forked from Sticky by Daniel Raftery
	http://twitter.com/ThrivingKings
*/
(function ($) {

	jQuery.sticky = jQuery.fn.sticky = function (note, options, callback) {

		// allow options to be ignored, and callback to be second argument
		if (typeof options === 'function') callback = options;

		// generate unique ID based on the hash of the note.
		var hashCode = function(str){
				
				var hash = 0,
					i = 0,
					c = '',
					len = str.length;
				if (len === 0) return hash;
				for (i = 0; i < len; i++) {
					c = str.charCodeAt(i);
					hash = ((hash<<5)-hash) + c;
					hash &= hash;
				}
				return 's'+Math.abs(hash);
			},
			o = {
				position: 'top-right', // top-left, top-right, bottom-left, or bottom-right
				speed: 'fast', // animations: fast, slow, or integer
				allowdupes: true, // true or false
				autoclose: 5000,  // delay in milliseconds. Set to 0 to remain open.
				classList: '' // arbitrary list of classes. Suggestions: success, warning, important, or info. Defaults to ''.
			},
			uniqID = hashCode(note), // a relatively unique ID
			display = true,
			duplicate = false,
			tmpl = '<div class="sticky border-POS CLASSLIST" id="ID"><span class="sticky-close"></span><p class="sticky-note">NOTE</p></div>',
			positions = ['top-right', 'top-center', 'top-left', 'bottom-right', 'bottom-center', 'bottom-left'];

		// merge default and incoming options
		if (options) jQuery.extend(o, options);

		// Handling duplicate notes and IDs
		jQuery('.sticky').each(function () {
			if (jQuery(this).attr('id') === hashCode(note)) {
				duplicate = true;
				if (!o.allowdupes) display = false;
			}
			if (jQuery(this).attr('id') === uniqID) uniqID = hashCode(note);
		});

		// Make sure the sticky queue exists
		if (!jQuery('.sticky-queue').length) {
			jQuery('body').append('<div class="sticky-queue ' + o.position + '">');
		} else {
			// if it exists already, but the position param is different,
			// then allow it to be overridden
			jQuery('.sticky-queue').removeClass( positions.join(' ') ).addClass(o.position);
		}

		// Can it be displayed?
		if (display) {
			// Building and inserting sticky note
			jQuery('.sticky-queue').prepend(
				tmpl
					.replace('POS', o.position)
					.replace('ID', uniqID)
					.replace('NOTE', note)
					.replace('CLASSLIST', o.classList)
			).find('#' + uniqID)
			.slideDown(o.speed, function(){
				display = true;
				// Callback function?
				if (callback && typeof callback === 'function') {
					callback({
						'id': uniqID,
						'duplicate': duplicate,
						'displayed': display
					});
				}
			});

		}

		// Listeners
		jQuery('.sticky').ready(function () {
			// If 'autoclose' is enabled, set a timer to close the sticky
			if (o.autoclose) {
				jQuery('#' + uniqID).delay(o.autoclose).fadeOut(o.speed, function(){
					// remove element from DOM
					jQuery(this).remove();
				});
			}
		});

		// Closing a sticky
		jQuery('.sticky-close').on('click', function () {
			jQuery('#' + jQuery(this).parent().attr('id')).dequeue().fadeOut(o.speed, function(){
				// remove element from DOM
				jQuery(this).remove();
			});
		});

	};
})(jQuery);