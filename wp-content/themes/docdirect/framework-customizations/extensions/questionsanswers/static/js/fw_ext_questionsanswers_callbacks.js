//"use strict";
jQuery(document).on('ready', function () {
	var loader_html	= '<div class="docdirect-site-wrap"><div class="docdirect-loader"><div class="bounce1"></div><div class="bounce2"></div><div class="bounce3"></div></div></div>';
	
	jQuery(document).on("click","#ask_btn",function(){
		var get_question = jQuery('#ask_search_question').val();
		jQuery('.question_title').val(get_question);
	});
	
    /*************************************
     * Save Question Ajax Request
     ************************************/
    jQuery(document).on('click', '.fw_ext_question_save_btn', function (e) {
        e.preventDefault();
        if (typeof tinyMCE === 'object') {
            tinyMCE.triggerSave();
        }

        var _this = jQuery(this);
		var _type = _this.data('type');
		
        var serialize_data = jQuery('.fw_ext_questions_form').serialize();
        var dataString = serialize_data + '&type='+_type+'&action=fw_ext_docdirect_process_questions';
        jQuery('body').append(loader_html);
        jQuery.ajax({
            type: "POST",
            url: fw_ext_questionsanswers_scripts_vars.ajaxurl,
            data: dataString,
            dataType: "json",
            success: function (response) {
                if (response.type == 'error') {
					jQuery('body').find('.docdirect-site-wrap').remove();
                    jQuery.sticky(response.message, {classList: 'important', speed: 200, autoclose: 5000});
                } else {
                    jQuery.sticky(response.message, {classList: 'success', speed: 200, autoclose: 5000, position: 'top-right', });
					window.location.reload();
                }
            }
        });
        return false;
    });
    /*************************************
     * Save Question Answer Ajax Request
     ************************************/
    jQuery(document).on('click', '.answer_save_btn', function (e) {
        e.preventDefault();
        if (typeof tinyMCE === 'object') {
            tinyMCE.triggerSave();
        }

        var _this = jQuery(this);
        var serialize_data = jQuery('.docdirect_answer_form').serialize();
        var dataString = serialize_data + '&action=fw_ext_docdirect_process_answers';
        jQuery('body').append(loader_html);
        jQuery.ajax({
            type: "POST",
            url: fw_ext_questionsanswers_scripts_vars.ajaxurl,
            data: dataString,
            dataType: "json",
            success: function (response) {
                if (response.type == 'error') {
					jQuery('body').find('.docdirect-site-wrap').remove();
                    jQuery.sticky(response.message, {classList: 'important', speed: 200, autoclose: 5000});
                } else {
                    jQuery.sticky(response.message, {classList: 'success', speed: 200, autoclose: 5000, position: 'top-right', });
					window.location.reload();
                }
            }
        });
        return false;
    });
	
	/*************************************
     * update votes
     ************************************/
    jQuery(document).on('click', '.updatevote', function (e) {
        e.preventDefault();
        var is_loggedin = fw_ext_questionsanswers_scripts_vars.is_loggedin;
		if( is_loggedin === 'false' ){
			jQuery.sticky(fw_ext_questionsanswers_scripts_vars.login_beofer_vote, {classList: 'success', speed: 200, autoclose: 5000});
		}
		
        var _this = jQuery(this);
		var _key	= _this.data('key');
		var _id	= _this.data('id');
        var dataString = 'key='+ _key + '&id='+ _id + '&action=fw_ext_docdirect_update_likes';
        jQuery('body').append(loader_html);
        jQuery.ajax({
            type: "POST",
            url: fw_ext_questionsanswers_scripts_vars.ajaxurl,
            data: dataString,
            dataType: "json",
            success: function (response) {
                jQuery('body').find('.docdirect-site-wrap').remove();
                _this.parents('.tg-question').find('.votes_wrap').html(response.vote);
				jQuery.sticky(response.message, {classList: 'success', speed: 200, autoclose: 5000});
            }
        });
        return false;
    });
	
	//load more questions
	var q_page = 2;
	jQuery(document).on('click', '.loadmore_q', function (e) {
        e.preventDefault();
        var is_loggedin = fw_ext_questionsanswers_scripts_vars.is_loggedin;
		if( is_loggedin === 'false' ){
			jQuery.sticky(fw_ext_questionsanswers_scripts_vars.login_beofer_vote, {classList: 'success', speed: 200, autoclose: 5000});
		}
		
        var _this = jQuery(this);
		var parent_id	= _this.parents('.sp-provider-articles').data('parent_id');
        var dataString = 'page='+ q_page + '&parent_id='+ parent_id + '&action=fw_ext_docdirect_laodmore_questions';
        jQuery('body').append(loader_html);
        jQuery.ajax({
            type: "POST",
            url: fw_ext_questionsanswers_scripts_vars.ajaxurl,
            data: dataString,
            dataType: "html",
            success: function (response) {
                jQuery('body').find('.docdirect-site-wrap').remove();
				if (jQuery.trim(response)){ 
                	jQuery('.questions-area').append(response);
					q_page++;
				} else{
					jQuery('.loadmore-wrap').html('');
					jQuery('.loadmore-wrap').append(fw_ext_questionsanswers_scripts_vars.no_more);
				}
            }
        });
        return false;
    });
	
	//load more questions
	var a_page = 2;
	jQuery(document).on('click', '.loadmore_a', function (e) {
        e.preventDefault();
        var is_loggedin = fw_ext_questionsanswers_scripts_vars.is_loggedin;
		if( is_loggedin === 'false' ){
			jQuery.sticky(fw_ext_questionsanswers_scripts_vars.login_beofer_vote, {classList: 'success', speed: 200, autoclose: 5000});
		}
		
        var _this = jQuery(this);
		var q_id	= _this.parents('.tg-answers').data('q_id');
        var dataString = 'page='+ a_page + '&q_id='+ q_id + '&action=fw_ext_docdirect_laodmore_answers';
        jQuery('body').append(loader_html);
        jQuery.ajax({
            type: "POST",
            url: fw_ext_questionsanswers_scripts_vars.ajaxurl,
            data: dataString,
            dataType: "html",
            success: function (response) {
                jQuery('body').find('.docdirect-site-wrap').remove();
				if (jQuery.trim(response)){ 
                	jQuery('.questions-area').append(response);
					a_page++;
				} else{
					jQuery('.loadmore-wrap').html('');
					jQuery('.loadmore-wrap').append(fw_ext_questionsanswers_scripts_vars.no_more);
				}
            }
        });
        return false;
    });

});