/*
Document   :  404 Monitor
Author     :  Andrei Dinca, Bbit http://codecanyon.net/user/Bbit
*/

// Initialization and events code for the app
bbitFileEdit = (function ($) {
    "use strict";

    // public
    var debug_level = 0;
    var maincontainer = null;
    var mainloading = null;

	// init function, autoload
	(function init() {
		// load the triggers
		$(document).ready(function(){
			maincontainer = $("#bbit-wrapper");
			if($('#robotstxt').length > 0) maincontainer.find('.bbit-fe-create-robots-txt').hide();
			mainloading = maincontainer.find("#bbit-main-loading");
			triggers();
		});
	})();
	
	function saveChanges() {
		mainloading.fadeIn('fast');
		
		var rtVal = '', htVal = '',
		$__rt = $('#bbit-wrapper #frm-save-changes #robotstxt'),
		$__ht = $('#bbit-wrapper #frm-save-changes #htaccess');

		if ($__rt.length>0)
			rtVal = $__rt.is(':disabled') ? '' : $__rt.val();
		if ($__ht.length>0)
			htVal = $__ht.is(':disabled') ? '' : $__ht.val();
		
		jQuery.post(ajaxurl, {
			'action' 		: 'bbitFileEdit',
			'ht'			: htVal,
			'rt'			: rtVal,
			'debug_level'	: debug_level
		}, function(response) {
			if( response.status == 'valid' ){
				mainloading.fadeOut('fast');
			}
		}, 'json');
	}
	
	function createRobotsTxt() {
		mainloading.fadeIn('fast');
		var rtCreateVal = 'yes';
		jQuery.post(ajaxurl, {
			'action' 		: 'bbitFileEdit',
			'rtCreate'			: rtCreateVal,
			'debug_level'	: debug_level
		}, function(response) {
			if( response.status == 'valid' ){
				mainloading.fadeOut('fast');
				location.reload();
			}
		}, 'json');
	}
	
	function triggers()
	{
		//default message!
		if ( $('#bbit-fe-ht-wrap').find('.bbit-fe-err, .bbit-fe-msg').length>0 ) { //.htaccess
			$('#bbit-fe-ht-wrap').css({'display': 'table'});
		}
		if ( $('#bbit-fe-rt-wrap').find('.bbit-fe-err, .bbit-fe-msg').length>0 ) { //robots.txt
			$('#bbit-fe-rt-wrap').css({'display': 'table'});
		}
		
		//save changes
		maincontainer.find('.bbit-fe-save').click(function(e) {
			e.preventDefault();
			//saveChanges();
			
			var rtVal = '', htVal = '',
			$__rt = $('#bbit-wrapper #frm-save-changes #robotstxt'),
			$__ht = $('#bbit-wrapper #frm-save-changes #htaccess');
	
			rtVal = $__rt.is(':disabled') ? '' : $.trim( $__rt.val() );
			htVal = $__ht.is(':disabled') ? '' : $.trim( $__ht.val() );

			if (rtVal=='' && htVal=='') {
				if ( confirm('Both robots.txt and .htaccess files are empty. Are you sure you wanna update their content?') )
					$('#bbit-wrapper #frm-save-changes').submit();
			} else {
				$('#bbit-wrapper #frm-save-changes').submit();
			}
		});
		
		//create robots.txt
		maincontainer.find('.bbit-fe-create-robots-txt').click(function(e) {
			e.preventDefault();
			createRobotsTxt();
		});
	}

	// external usage
	return {
    }
})(jQuery);
