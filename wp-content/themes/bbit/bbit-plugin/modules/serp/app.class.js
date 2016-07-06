/*
Document   :  SERP
Author     :  Andrei Dinca, Bbit http://bbit.vn
*/
// Initialization and events code for the app
bbitSERP = (function ($) {
    "use strict";

    // public
    var debug_level = 0;
    var maincontainer = null;
    var mainloading = null;
    var lightbox = null;
    var engine_access_time = null;
    var engine_wait = 5; //in seconds;

	// init function, autoload
	(function init() {
		// load the triggers
		$(document).ready(function(){
			maincontainer = $("#bbit-serp-container");
			mainloading = $("#bbit-main-loading");
			lightbox = $("#bbit-lightbox-overlay");
			triggers();
		});
	})();
	
	function wait_time() {
		jQuery.post(ajaxurl, {
			'action' 		: 'bbitGetEngineAccessTime'
		}, function(response) {
			if( response.status == 'valid' ){

				var $boxMsg = $('.bbit-list-table-left-col').eq(0);
				if ( $.trim( response.last_msg ) != '' )
					$boxMsg.html( response.last_msg + ' ' + response.nb_req );
				
				/*engine_access_time = parseInt( response.data );
				if (engine_access_time<=0) return true;
				
				$('.bbit-list-table-left-col').html( $boxMsg.html() + '<span id="engine-time-to-wait"></span>' );
				var $wrapClock = $('#engine-time-to-wait');

				var	lastTime = parseInt( engine_access_time + engine_wait * 1000 ),
				waitClock = setInterval(function () {

					var currentTime = new Date().getTime(), text = '';
					if ( lastTime <= currentTime) {
						clearInterval( waitClock );
						waitClock = null;
						
						engine_btn_status( 'active' );
						text = '';
					} else {
						engine_btn_status( 'disable' );
						text = 'google access: wait ' + parseInt( (lastTime - currentTime) / 1000 ) + ' seconds!';
					}
					$wrapClock.html( '<strong>' + text + '</strong>' );
					if ( text == '' ) {
						$wrapClock.html( '' );
						$wrapClock.fadeOut('slow');
					}

				}, 1000);*/
				return true;
			}
			return false;
		}, 'json');
	}
	
	function engine_btn_status( status ) {
		var $btn = $('#bbit-submit-to-reporter, .bbit-do_item_update');
		
		switch (status) {
			case 'disable':
				$btn.removeClass('blue').addClass('gray').attr('disabled', true);
				break;
			case 'active':
			default:
				$btn.removeClass('gray').addClass('blue').removeAttr('disabled');
				break;
		}
	}
	
	function refreshGraph()
	{
		mainloading.fadeIn('fast');
		var keys = [], urls = [],
		__ck = $('.bbit-panel .bbit-serp-filter-keyurl-content .bbit-table input.bbit-item-checkbox-key:checked'),
		__ck2 = $('.bbit-panel .bbit-serp-filter-keyurl-content .bbit-table input.bbit-item-checkbox-url:checked');

		__ck.each(function (k, v) {
			keys[k] = $(this).attr('value');
		});
		keys = keys.join(',');

		__ck2.each(function (k, v) {
			urls[k] = $(this).attr('value');
		});
		urls = urls.join(',');
		
		// float jQuery
		jQuery.post(ajaxurl, {
			'action' 		: 'bbitGetSERPGraphData',
			'engine'		: $("#select-engine").val(),
			'from_date'		: $("#bbit-filter-by-date-from").val(),
			'to_date'		: $("#bbit-filter-by-date-to").val(),
			'keys'			: keys,
			'urls'			: urls,
			'debug_level'	: debug_level
		}, function(response) {
			//data not received!
			if (response.status == 'invalid') {
				$("#bbit-serp-graph").fadeOut('fast');
				mainloading.fadeOut('fast');
				return false;
			}
			
			if( response.status == 'valid' ){
				$("#bbit-serp-graph").fadeIn('fast');
				var plot = $.plot("#bbit-serp-graph", response.data, {
					series: {
						lines: {
							show: true
						},
						points: {
							show: true
						}
					},
					grid: {
						hoverable: true,
						clickable: true
					},
					tooltip: true,
					tooltipOpts: {
						defaultTheme: true,
						content: "%x <br /> Rank: %y"
					},
					xaxis: {
						mode: "time",
						timeformat: "%d/%m/%y",
						minTickSize: [1, "day"]
					},
					yaxes: [ { 
						min: 1,
						tickFormatter: (function formatter(val, axis) { 
							return val;
						}),
						minTickSize: 1 
					} ],
				});
				
				//default graph!
				var defaultKeywords = response.def_key,
				__kw2 = $('.bbit-panel .bbit-serp-filter-keyurl-content .bbit-table input.bbit-item-checkbox-key');
				__kw2.each(function (k, v) {
					var __val = $(this).attr('value');
					if ($.inArray(__val, defaultKeywords)!=-1) {
						$(this).attr('checked', 'checked');
					}
				});
				
				mainloading.fadeOut('fast');
			}
		}, 'json');
	}
	
	function SERPInterface()
	{
		if ( $('#bbit-wrapper').find('.bbit-error-using-module').length > 0 ) {
			mainloading.fadeOut('fast');
			return false;
		}

		// Datepicker (range)
		$( "#bbit-filter-by-date-from" ).datepicker({
			changeMonth: true,
			numberOfMonths: 1,
			dateFormat: "yy-mm-dd",
			onClose: function( selectedDate ) {
				$( "#bbit-filter-by-date-to" ).datepicker( "option", "minDate", selectedDate );
			}
		});
		
		$( "#bbit-filter-by-date-to" ).datepicker({
			changeMonth: true,
			numberOfMonths: 1,
			dateFormat: "yy-mm-dd",
			onClose: function( selectedDate ) {
				$( "#bbit-filter-by-date-from" ).datepicker( "option", "maxDate", selectedDate );
			}
		});
		
		refreshGraph();
	}
	
	function showFocusKW()
	{
		mainloading.fadeIn('fast');
		
		jQuery.post(ajaxurl, {
			'action' 		: 'bbitGetFocusKW',
			'debug_level'	: debug_level
		}, function(response) {
			if( response.status == 'valid' ){
				$("#bbit-lightbox-seo-report-response").html( response.html );
				lightbox.fadeIn('fast', function(){
					mainloading.fadeOut('fast');
				});
			}
		}, 'json');

		lightbox.find("a.bbit-close-btn").click(function(e){
			e.preventDefault();
			lightbox.fadeOut('fast');
		});
	}
	
	function addToReporter( keyword, link, itemid )
	{
		lightbox.fadeOut('fast');
		mainloading.fadeIn('fast');

		jQuery.post(ajaxurl, {
			'action' 		: 'bbitAddToReporter',
			'keyword'		: keyword,
			'link'			: link,
			'itemid'		: itemid,
			'debug_level'	: debug_level,
			'wait_time'		: new Date().getTime()
		}, function(response) {

			if( response.status == 'valid' ){
				mainloading.fadeOut('fast');
				window.location.reload();
			}
			mainloading.fadeOut('fast');
			return false;
		}, 'json');
	}
	
	function updateToReporter( itemid, subaction )
	{
		subaction = subaction || '';
		
		lightbox.fadeOut('fast');
		mainloading.fadeIn('fast');
		
		jQuery.post(ajaxurl, {
			'action' 		: 'bbitUpdateToReporter',
			'subaction'		: subaction,
			'itemid'		: itemid,
			'debug_level'	: debug_level,
			'wait_time'		: new Date().getTime()
		}, function(response) {
 
			if( response.status == 'valid' ){
				if ( subaction == 'publish' ) ;
				else
					mainloading.fadeOut('fast');

				window.location.reload();
			}
			mainloading.fadeOut('fast');
			return false;
		}, 'json');
	}
	
	function deleteFromReporter( itemid )
	{
		lightbox.fadeOut('fast');
		mainloading.fadeIn('fast');
		
		jQuery.post(ajaxurl, {
			'action' 		: 'bbitRemoveFromReporter',
			'itemid'		: itemid,
			'debug_level'	: debug_level
		}, function(response) {
			if( response.status == 'valid' ){
				mainloading.fadeOut('fast');
				window.location.reload();
			}
			mainloading.fadeOut('fast');
			return false;
		}, 'json');
	}
	
	function showAddNewProxy()
	{
		lightbox.fadeIn('fast');
		
		lightbox.find("a.bbit-close-btn").click(function(e){
			e.preventDefault();
			lightbox.fadeOut('fast');
		});
	}
	
	function triggers()
	{
		wait_time();
		
		$('body').on('click', "input#bbit-select-fw", function(e){
			e.preventDefault();
			showFocusKW();
		});
		
		$('body').on('click', "input#bbit-submit-to-reporter", function(e){
			e.preventDefault();
			
			var keyword = $("#bbit-new-keyword").val(),
				link = $("#bbit-new-keyword-link").val();
			
			if (!link.match("^https?:\/\/")) link = "http://" + link;
    		 	
			addToReporter( keyword, link );
		});
		
		$('body').on('click', "input.bbit-this-select-fw", function(e){
			e.preventDefault();
			var that = $(this),
				keyword = that.data("keyword"),
				link = that.data("permalink"),
				itemid = that.data("itemid");
				
			if (!link.match("^https?:\/\/")) link = "http://" + link;

			addToReporter( keyword, link, itemid );
		});
		
		$('body').on('click', ".bbit-do_item_delete", function(e){
			e.preventDefault();
			var that = $(this),
				row = that.parents('tr').eq(0),
				id	= row.data('itemid'),
				key = row.find('td').eq(0).find('input').val(),
				url = row.find('td').eq(1).find('input').val();

			//row.find('code').eq(0).text()
			if(confirm('Delete (' + key + ', ' + url + ') pair from reporter? This action can\t be rollback!' )){
				deleteFromReporter( id );
			}
		});
		
		$('body').on('click', ".bbit-do_item_update", function(e){
			e.preventDefault();
			var that = $(this),
				row = that.parents('tr').eq(0),
				id	= row.data('itemid');
				
			updateToReporter( id );
		});
		
		// publish / unpublish row
		$('body').on('click', ".bbit-do_item_publish", function(e){
			e.preventDefault();
			var that = $(this),
				row = that.parents('tr').eq(0),
				id	= row.data('itemid');
				
			updateToReporter( id, 'publish' );
		});
		
		/*$('body').on('click', '#bbit-cron-ckeck', function(e) {
			e.preventDefault();

			mainloading.fadeIn('fast');

			jQuery.post(ajaxurl, {
				'action' 		: 'bbitCronCheck',
				'debug_level'	: debug_level
			}, function(response) {
				if( response.status == 'valid' ){
					mainloading.fadeOut('fast');
					window.location.reload();
				}
				mainloading.fadeOut('fast');
				return false;
			}, 'json');
		});*/
		
		$('body').on('change', '#select-engine', function(e) {
			e.preventDefault();

			jQuery.post(ajaxurl, {
				'action' 		: 'bbitSetSearchEngine',
				'search_engine' : $('#select-engine').val(),
				'debug_level'	: debug_level
			}, function(response) {
				if( response.status == 'valid' ){
					mainloading.fadeOut('fast');
					window.location.reload();
				}
				mainloading.fadeOut('fast');
				return false;
			}, 'json');
		});
		
		// filter by keywords, urls!
		$('body').on('click', 'input#bbit-item-check-all-key', function(){
			var that = $(this),
			checkboxes = $('.bbit-serp-filter-keyurl-content input.bbit-item-checkbox-key');

			if( that.is(':checked') ){
				checkboxes.prop('checked', true);
			}
			else{
				checkboxes.prop('checked', false);
			}
		});
		$('body').on('click', 'input#bbit-item-check-all-url', function(){
			var that = $(this),
			checkboxes = $('.bbit-serp-filter-keyurl-content input.bbit-item-checkbox-url');

			if( that.is(':checked') ){
				checkboxes.prop('checked', true);
			}
			else{
				checkboxes.prop('checked', false);
			}
		});
		
		$('body').on('click', "#bbit-filter-graph-data", function(e){
			e.preventDefault();
			refreshGraph();
		});
		
		$('body').on('click', '#bbit-toggle-ku', function(e) {
			e.preventDefault();
			$('#bbit-serp-filter-keyurl').toggle();
		});

		SERPInterface();
	}
	
	function in_array(needle, haystack) {
		for(var key in haystack) {
			if(needle === haystack[key]) {
				return true;
			}
		}
		return false;
	}

	// external usage
	return {
		"SERPInterface"		: SERPInterface,
		"wait_time"			: wait_time
    }
})(jQuery);

if (typeof String.prototype.startsWith != 'function') {
  // see below for better implementation!
  String.prototype.startsWith = function (str){
    return this.indexOf(str) == 0;
  };
}