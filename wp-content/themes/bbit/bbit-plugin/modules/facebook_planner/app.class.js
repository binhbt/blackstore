/*
Document   :  On Page Optimization
Author     :  Andrei Dinca, Bbit http://bbit.vn
*/
// Initialization and events code for the app
bbitFacebookPage = (function ($) {
    "use strict";

    // public
    var debug_level = 0;
    var maincontainer = null;
    var loading = null;
    var IDs = [];
    var loaded_page = 0;
    
    var maincontainer_tasks = null;
    var mainloading_tasks = null;
    
    var langmsg = {};

	// init function, autoload
	(function init() {
		// load the triggers
		$(document).ready(function(){
			maincontainer = $("#bbit-wrapper #bbit_facebook_share-options");
			loading = maincontainer.find("#main-loading");
			
			maincontainer_tasks = $("#bbit-wrapper");
			mainloading_tasks = maincontainer_tasks.find("#bbit-main-loading");

			triggers();
		});
	})();

	function fixMetaBoxLayout()
	{
		//meta boxes
		var meta_box 		= $(".bbit-meta-box-container .bbit-seo-status-container"),
			meta_box_width 	= $(".bbit-meta-box-container").width() - 100,
			row				= meta_box.find(".bbit-seo-rule-row");

		row.width(meta_box_width - 40);
		row.find(".right-col").width( meta_box_width - 180 );
		row.find(".message-box").width(meta_box_width - 45);
		row.find(".right-col .message-box").width( meta_box_width - 180 );


		$("#bbit_facebook_share-options #bbit-meta-box-preload").hide();
		$("#bbit_facebook_share-options .bbit-meta-box-container").fadeIn('fast');

		$("#bbit_facebook_share-options").on('click', '.bbit-tab-menu a', function(e){
			e.preventDefault();

			var that 	= $(this),
				open 	= $("#bbit_facebook_share-options .bbit-tab-menu a.open"),
				href 	= that.attr('href').replace('#', '');

			$("#bbit_facebook_share-options .bbit-meta-box-container").hide();

			$("#bbit_facebook_share-options #bbit-tab-div-id-" + href ).show();

			// close current opened tab
			var rel_open = open.attr('href').replace('#', '');

			$("#bbit_facebook_share-options #bbit-tab-div-id-" + rel_open ).hide();

			$("#bbit_facebook_share-options #bbit-meta-box-preload").show();

			$("#bbit_facebook_share-options #bbit-meta-box-preload").hide();
			$("#bbit_facebook_share-options .bbit-meta-box-container").fadeIn('fast');

			open.removeClass('open');
			that.addClass('open');
		});
	}
	
	function fb_planner_post(atts) {

		var fb_planner_post = {

			init: function(atts) {
				this.atts = $.extend(this.atts, atts);
				this.trigger();
			},

			autocomplete_fields: function() {
				var self = this;

				var titleValue = jQuery('#titlewrap').find('input#title').val(),
				imageValue = jQuery('#bbit_wplannerfb_image').val(),
				featuredImg = jQuery('a#set-post-thumbnail').find('img.attachment-post-thumbnail').attr('src');

				if(tinymce.activeEditor) {
					if(!tinymce.activeEditor.isHidden()) {
						tinymce.activeEditor.save();
					}
				}

				var descValue = jQuery('#content').val();
				descValue = descValue.replace(/(<([^>]+)>)/ig,""); // remove <> codes
				descValue = descValue.replace(/(\[([^\]]+)\])/ig,""); // remove [] shortcodes
				descValue = descValue.replace(/(\s\s+)/ig,""); // remove multiple spaces
				descValue = descValue.substr(0, 10000);

				//if( titleValue != jQuery('#bbit_wplannerfb_title').val() ) {
				if( jQuery.trim( jQuery('#bbit_wplannerfb_title').val() ) == '' )
					jQuery('#bbit_wplannerfb_title').val( titleValue );
				//if( jQuery.trim( jQuery('#bbit_wplannerfb_caption').val() ) == '' )
				//	jQuery('#bbit_wplannerfb_caption').val( titleValue );				

				//if( descValue != jQuery('#bbit_wplannerfb_description').val() ) {
				if( jQuery.trim( jQuery('#bbit_wplannerfb_description').val() ) == '' )
					jQuery('#bbit_wplannerfb_description').val( descValue );
			},

			defaultValues: function() {
				if ( jQuery('#bbit_wplannerfb_permalink_value').val() != '' ) {
					jQuery('#bbit_wplannerfb_permalink_value').show();
				} else {
					jQuery('#bbit_wplannerfb_permalink_value').hide();
				}
			},

			trigger: function() {
				var self = this;
				jQuery('#bbit-wplannerfb-auto-complete').click(function() {
					self.autocomplete_fields();
				});
				self.defaultValues();
			}
		};
		
		fb_planner_post.init(atts);

		atts.action = atts.action || '';
		if ( atts.action != '' ) {
			if ( atts.action == 'autocomplete' )
				fb_planner_post.autocomplete_fields();
		}
	}
	
	function fb_postnow(atts) {
		
		var atts = atts;

		var postNowBtn = jQuery('#bbit_post_planner_postNowFBbtn');
		postNowBtn.click(function() {
			// Auto-Complete fields with data from above (title, permalink, content) if empty
			if( jQuery('#bbit_wplannerfb_title').val() == '' ||
				//jQuery('#bbit_wplannerfb_permalink').val() == '' ||
				jQuery('#bbit_wplannerfb_description').val() == ''
			) {
				var c = confirm(langmsg.mandatory);

				if(c == true) {
					fb_planner_post({'action': 'autocomplete'});
				}else{
					alert(langmsg.publish_cancel);
					return false;
				}
			}


			var postTo = '',
			postMe = jQuery('#bbit_wplannerfb_now_post_to_me'),
			postPageGroup = jQuery('#bbit_wplannerfb_now_post_to_page'),
			postTOFbNow = jQuery('#bbit_postTOFbNow');

			postTOFbNow.show();
			postNowBtn.hide();

			var postToProfile = '';
			var postToPageGroup = '';
			if( postMe.attr('checked') == 'checked' ) {
				postToProfile = 'on';
			}
			if( postPageGroup.attr('checked') == 'checked' ) {
				postToPageGroup = jQuery('#bbit_wplannerfb_now_post_to_page_group').val();
			}

			var data = {
				action: 'bbit_publish_fb_now',
				postId: atts.post_id,
				postTo: {'profile' : postToProfile, 'page_group' : postToPageGroup},
				privacy: jQuery('#bbit_wplannerfb_now_post_privacy').val(),
				bbit_wplannerfb_message: jQuery('#bbit_wplannerfb_message').val(),
				bbit_wplannerfb_title: jQuery('#bbit_wplannerfb_title').val(),
				bbit_wplannerfb_permalink: jQuery("input[name=bbit_wplannerfb_permalink]:checked").val(),
				bbit_wplannerfb_permalink_value: jQuery('#bbit_wplannerfb_permalink_value').val(),
				bbit_wplannerfb_caption: jQuery('#bbit_wplannerfb_caption').val(),
				bbit_wplannerfb_description: jQuery('#bbit_wplannerfb_description').val(),
				bbit_wplannerfb_image: jQuery('input[name=bbit_wplannerfb_image]').val(),
				bbit_wplannerfb_useimage: jQuery('select[name=bbit_wplannerfb_useimage]').val()
			};

			// since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
			jQuery.post(ajaxurl, data, function(response) {
				if(jQuery.trim(response) == 'OK'){
					postTOFbNow.hide();
					alert( langmsg.publish_success );
					postNowBtn.show();
				}else{
					alert( langmsg.publish_error );
					postNowBtn.show();
				}
			});
			return false;
		});
	}
	
	function fb_scheduler( atts ) {
		
		var atts = atts;
		
		// Check for mandatory empty fields AND Auto-Complete fields with data from post/page (title, permalink, content) if empty
		jQuery('body').on('click', '#bbit_wplannerfb_date_hour', function() {

			if( jQuery('#bbit_wplannerfb_title').val() == '' ||
			//jQuery('#bbit_wplannerfb_permalink').val() == '' ||
			jQuery('#bbit_wplannerfb_description').val() == '')
			{
				fb_planner_post({'action': 'autocomplete'});
				alert(langmsg.mandatory2);
			}
		});

		// Auto-Check repeat interval input
		var $repeating_interval = jQuery('#bbit_wplannerfb_repeating_interval');
		$repeating_interval.keyup(function(){
			$t = jQuery(this),
			val = $t.val();

			if(val != parseInt(val) || parseInt(val) < 1){
				$t.val(parseInt(val));
			}
		})
		
	}
	
	function delete_bulk_rows() {
		var ids = [], __ck = $('.bbit-form .bbit-table input.bbit-item-checkbox:checked');
		__ck.each(function (k, v) {
			ids[k] = $(this).attr('name').replace('bbit-item-checkbox-', '');
		});
		ids = ids.join(',');
		if (ids.length<=0) {
			alert('You didn\'t select any rows!');
			return false;
		}
		
		mainloading_tasks.fadeIn('fast');

		jQuery.post(ajaxurl, {
			'action' 		: 'bbit_do_bulk_delete_rows',
			'id'			: ids,
			'debug_level'	: debug_level
		}, function(response) {
			if( response.status == 'valid' ){
				mainloading_tasks.fadeOut('fast');				
				//refresh page!
				window.location.reload();
				return false;
			}
			mainloading_tasks.fadeOut('fast');
			alert('Problems occured while trying to delete the selected rows!');
		}, 'json');
	}
	
	function triggers()
	{
		fixMetaBoxLayout();
		
		maincontainer_tasks.on('click', '#bbit-do_bulk_delete_facebook_planner_rows', function(e){
			e.preventDefault();

			if (confirm('Are you sure you want to delete the selected rows?'))
				delete_bulk_rows();
		});
	}
	
	function setLangMsg( atts ) {
		langmsg = $.extend(langmsg, atts);
	}
	
	// external usage
	return {
		'setLangMsg'		: setLangMsg,
		'fb_planner_post'	: fb_planner_post,
		'fb_scheduler'		: fb_scheduler,
		'fb_postnow'		: fb_postnow

    }
})(jQuery);