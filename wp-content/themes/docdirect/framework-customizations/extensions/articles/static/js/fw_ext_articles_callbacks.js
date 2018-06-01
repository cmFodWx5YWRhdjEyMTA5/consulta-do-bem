//"use strict";
jQuery(document).on('ready', function () {
    var loader_html = '<div class="docdirect-site-wrap"><div class="docdirect-loader"><div class="bounce1"></div><div class="bounce2"></div><div class="bounce3"></div></div></div>';
    var delete_article_title = fw_ext_articles_scripts_vars.delete_article_title;
    var delete_article_msg = fw_ext_articles_scripts_vars.delete_article_msg;
	var docdirect_featured_nounce	= fw_ext_articles_scripts_vars.docdirect_featured_nounce;
	var file_upload_title	= fw_ext_articles_scripts_vars.file_upload_title;
	
    /***********************************************
     * Add/Edit new Article
     **********************************************/
    jQuery(document).on('click', '.process-article', function (e) {
        e.preventDefault();
        if( typeof tinyMCE === 'object' ) {
		  tinyMCE.triggerSave();
		}
		
        var _this = jQuery(this);
        var _type = _this.data('type');
        var serialize_data = jQuery('.tg-addarticle').serialize();
        var dataString = 'type=' + _type + '&' + serialize_data + '&action=fw_ext_docdirect_process_articles';
        jQuery('body').append(loader_html);
        jQuery.ajax({
            type: "POST",
            url: fw_ext_articles_scripts_vars.ajaxurl,
            data: dataString,
            dataType: "json",
            success: function (response) {
                jQuery('body').find('.docdirect-site-wrap').remove();
                if (response.type == 'error') {
                    jQuery.sticky(response.message, {classList: 'important', speed: 200, autoclose: 5000});
                } else {                 
                    jQuery.sticky(response.message, {classList: 'success', speed: 200, autoclose: 5000, position: 'top-right', });
					if (response.return_url) {                      
						window.location.replace(response.return_url);                  
					}
                }
            }
        });
        return false;
    });
    /***********************************************
     * Delete Article
     **********************************************/
    jQuery(document).on('click', '.btn-article-del', function (event) {
        event.preventDefault();
        var _this = jQuery(this);
        var _id = _this.data('key');
        jQuery.confirm({
            'title': delete_article_title,
            'message': delete_article_msg,
            'buttons': {
                'Yes': {
                    'class': 'blue',
                    'action': function () {
                        jQuery('body').append(loader_html);
                        jQuery.ajax({
                            type: "POST",
                            url: fw_ext_articles_scripts_vars.ajaxurl,
                            data: 'id=' + _id + '&action=fw_ext_docdirect_delete_articles',
                            dataType: "json",
                            success: function (response) {
                                jQuery('body').find('.docdirect-site-wrap').remove();
                                if (response.type == 'success') {
                                    jQuery.sticky(response.message, {classList: 'success', speed: 200, autoclose: 5000, position: 'top-right', });
                                    _this.parents('tr').remove();
                                } else {
                                    jQuery.sticky(response.message, {classList: 'important', speed: 200, autoclose: 5000});
                                }
                            }
                        });
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

	//Uploader Handler
	var uploader = new plupload.Uploader({
		runtimes : 'html5,flash,silverlight,html4',
		browse_button: 'upload-featured-image',          // this can be an id of a DOM element or the DOM element itself
		file_data_name: 'docdirect_uploader',
		container: 'plupload-container',
		multi_selection : false,
		flash_swf_url : fw_ext_articles_scripts_vars.theme_path_uri+'/images/plupload/Moxie.swf',
		silverlight_xap_url : fw_ext_articles_scripts_vars.theme_path_uri+'/images/plupload/Moxie.xap',
		multipart_params : {
			"type" : "featured_image",
		},
		url: fw_ext_articles_scripts_vars.ajaxurl + "?action=docdirect_featured_image_uploader&nonce=" + docdirect_featured_nounce,
		filters: {
			mime_types : [
				{ title : file_upload_title, extensions : "jpg,jpeg,gif,png" }
			],
			prevent_duplicates: true
		}

	});

	 uploader.init();

	 // Process Duraing Upload
	 uploader.bind('FilesAdded', function(up, files) {
		var html = '';
		var profileThumb = "";
		plupload.each(files, function(file) {
			//Do something duraing upload
		});
		up.refresh();
		uploader.start();
	});

	/* File percentage */
	uploader.bind('UploadProgress', function(up, file) {
		jQuery('.tg-box .tg-galleryimg-item').find('.avatar-percentage').remove();
		jQuery('.tg-galleryimg-item figure').append('<span class="avatar-percentage">'+file.percent+"%</span>");
	});

	/* In case of error */
	uploader.bind('Error', function( up, err ) {
		//jQuery('#errors-log').html(err.message);
		jQuery.sticky(err.message, {classList: 'important', speed: 200, autoclose: 5000});
	});

	//On Complete Uplaod
	uploader.bind('FileUploaded', function ( up, file, ajax_response ) {
		var response = $.parseJSON( ajax_response.response );
		jQuery('.tg-box .tg-galleryimg-item').find('.avatar-percentage').remove();
		if ( response.success ) {
			jQuery('.tg-box .tg-galleryimg-item').find('.attachment_src').attr('src', response.url);
			jQuery('.tg-box .tg-galleryimg-item').find('.attachment_id').val(response.attachment_id);
		} else {
			jQuery.sticky(response.message, {classList: 'important', speed: 200, autoclose: 5000});
		}
	});
	
	//Remove thumbnail
	jQuery(document).on('click', '.del-featured-image', function (event) {
		var _this	= jQuery(this);
        _this.parents('figure').find('.attachment_id').val('');
		_this.parents('figure').find('.attachment_src').attr('src', _this.data('placeholder'));
    });
	
    /************************************************
     * Sort Articles
     **********************************************/
    jQuery(document).on('change', '.sort_by, .order_by', function (event) {
        jQuery(".form-sort-articles").submit();
    });

    /*****************************************
     * Add Article Tags
     ***************************************/
    jQuery(document).on('click', '.add-article-tags', function (e) {
        e.preventDefault();
        var _this 		= jQuery(this);
        var _input 		= jQuery('.input-feature');
		var _inputval 		= jQuery('.input-feature').val();
		if( _inputval ){
			var load_tags = wp.template('load-article-tags');
			var load_tags = load_tags(_inputval);
			_this.parents('.tg-addallowances').find('.sp-feature-wrap').append(load_tags);
			_input.val('');
		}
        
    });

    /************************************************
     * Delete Article Tags
     **********************************************/
    jQuery(document).on('click', '.delete_article_tags', function (e) {
        e.preventDefault();
        var _this = jQuery(this);
        _this.parents('li').remove();
    });

});