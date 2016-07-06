/*
Document   :  Google Authorship
Author     :  Andrei Dinca, Bbit http://codecanyon.net/user/Bbit
*/
// Initialization and events code for the app
bbitGoogleAuthorship = (function ($) {
	"use strict";
	
	// public
	var debug_level = 0;
	var maincontainer = null;
	var mainloading = null;
	var lightbox = null;

	// init function, autoload
	(function init() {
		// load the triggers
		$(document).ready(function(){
			maincontainer = $("#bbit-wrapper");
			mainloading = $("#bbit-main-loading");
			lightbox = $("#bbit-lightbox-overlay");

			triggers();
		});
	})();
	
	function fixMetaBoxLayout()
	{
		//meta boxes
		var meta_box 		= $(".bbit-meta-box-container"),
			meta_box_width 	= $(".bbit-meta-box-container").width() - 100;
  
		$("#profile-page #bbit-meta-box-preload").hide();
		$("#profile-page .bbit-meta-box-container").fadeIn('fast');

		/*$("#profile-page").on('click', '.bbit-tab-menu a', function(e){
			e.preventDefault();

			var that 	= $(this),
				open 	= $("#profile-page .bbit-tab-menu a.open"),
				href 	= that.attr('href').replace('#', '');

			$("#profile-page .bbit-meta-box-container").hide();

			$("#profile-page #bbit-tab-div-id-" + href ).show();

			// close current opened tab
			var rel_open = open.attr('href').replace('#', '');

			$("#profile-page #bbit-tab-div-id-" + rel_open ).hide();

			$("#profile-page #bbit-meta-box-preload").show();

			$("#profile-page #bbit-meta-box-preload").hide();
			$("#profile-page .bbit-meta-box-container").fadeIn('fast');

			open.removeClass('open');
			that.addClass('open');
		});*/
	}
	
	function triggers()
	{
		fixMetaBoxLayout();
	}

	// external usage
	return {
    	}
})(jQuery);
