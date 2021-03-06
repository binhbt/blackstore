/*
Document   :  Social Stats
Author     :  Andrei Dinca, Bbit http://bbit.vn
*/
// Initialization and events code for the app
bbitGoogleAnalytics = (function ($) {
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
			mainloading = $("#bbit-main-loading");

			triggers();
		});
	})();
	
	function loadAudience()
	{
		if ( $('#bbit-wrapper').find('.bbit-error-using-module').length > 0 ) {
			mainloading.fadeOut('fast');
			return false;
		}
		jQuery.post(ajaxurl, {
			'action' 		: 'bbitGoogleAPIRequest',
			'sub_action' 	: 'getAudience,getAudienceDemographics,getAudienceSystem',
			'from_date'		: $("#bbit-filter-by-date-from").val(),
			'to_date'		: $("#bbit-filter-by-date-to").val(),
			'debug_level'	: debug_level
		}, function(response) {
			//data not received!
			if (response.__access.status == 'invalid') {
				//$(".bbit-panel, .bbit-grid_1_3").css({'display': 'none'}); //hide info panels!
				mainloading.fadeOut('fast');
				if ( response.__access.isalert == 'yes' )
					alert(response.__access.msg);
				return false;
			}
			
			//$(".bbit-panel, .bbit-grid_1_3").css({'display': 'block'}); //restore info panels!
			
			//getAudience
			if( response.getAudience.status == 'valid' ){
				make_getAudience( response.getAudience.data );
			}
			
			//getAudienceDemographics
			if( response.getAudienceDemographics.status == 'valid' ){  
				make_getAudienceDemographics( response.getAudienceDemographics.data );
			}
			
			mainloading.fadeOut('fast');
		}, 'json');
	}
	
	function make_getAudience(response) {
		// create some alias
		var profileInfo = response.profileInfo;
		var data = response.rows;
		maincontainer.find('#bbit-gdata-profile').html( profileInfo.profileName + " <i>(" + profileInfo.webPropertyId + ")</i>" );

		var opts = {
			series: {
				lines: { show: true },
				points: { show: true }
			},
			tooltip: true,
			tooltipOpts: {
				defaultTheme: true,
				content: "%x<br />%s: %y",
				xDateFormat: "%d/%m/%y"
			},
			xaxis: {
				mode: "time",
				timeformat: "%d/%m/%y"
			},
			grid: {
				hoverable: true,
				clickable: true,
				borderWidth: null
			}
		};

		var datasets = [
		{ data: data.newVisits, label: "% New Visits", color: "#E15656" },
		{ data: data.visits, label: "Visits", color: "#61A5E4" },
		{ data: data.avgTimeOnPage, label: "Avg. Visit Duration", color: "#37aa37" },
		{ data: data.visitBounceRate, label: "Bounce Rate", color: "#A6D037" },
		{ data: data.pageviewsPerVisit, label: "Pages / Visit", color: "#6d9cd6" },
		{ data: data.pageviews, label: "Pageviews", color: "#ad6dd6"},
		{ data: data.uniquePageviews, label: "Unique Visitors", color: "#a91c83" }
		];

		var choiceContainer = $("#audience-choose-container");
		choiceContainer.html('');
		$.each(datasets, function(key, val) {
			choiceContainer.append("<div><input type='checkbox' name='" + key + "' checked='checked' id='id" + key + "'></input>" +
			"<label for='id" + key + "'>"
			+ val.label + "</label></div>");
		});

		var plot = $.plot($("#bbit-audience-visits-graph"), datasets, opts);

		choiceContainer.find("input").click(function(){
			plotAccordingToChoices( choiceContainer, datasets, $("#bbit-audience-visits-graph"), opts );
		});
		plotAccordingToChoices( choiceContainer, datasets, $("#bbit-audience-visits-graph"), opts );

		// update summeryText
		var summeryText = $(".bbit-ga-summery-title");
		var totalsForAllResults = response.totalsForAllResults;

		summeryText.find('#ga-data-newVisits').text( totalsForAllResults['ga:newVisits'] );
		summeryText.find('#ga-data-visits').text( totalsForAllResults['ga:visits'] );
		summeryText.find('#ga-data-avgTimeOnPage').text( parseFloat(totalsForAllResults['ga:avgTimeOnPage']).toFixed(2) );
		summeryText.find('#ga-data-visitBounceRate').text( parseFloat(totalsForAllResults['ga:visitBounceRate']).toFixed(2) );
		summeryText.find('#ga-data-pageviewsPerVisit').text( parseFloat(totalsForAllResults['ga:pageviewsPerVisit']).toFixed(2) );
		summeryText.find('#ga-data-pageviews').text( totalsForAllResults['ga:pageviews'] );
		summeryText.find('#ga-data-uniquePageviews').text( totalsForAllResults['ga:uniquePageviews'] );
		
		// remove the loading
		$("#bbit-audience-visits-graph").css('background-image', 'none');
	}
	
	function make_getAudienceDemographics(response) {
		// create some alias
		var __groups = ['demographics', 'system', 'mobile'],
		__subgroups = ['country', 'language', 'city', 'browser', 'operatingSystem', 'networkDomain', 'mob_operatingSystem', 'mob_networkDomain', 'mob_screenResolution'],
		html = response.html;
	
		$.each(__groups, function(key, val) {
			$(".bbit-"+val+"-container").html(html[val]); //apply data!
		});

		//default & onchange selection!
		$.each(__groups, function(key, val) {
			//default selection!			
			var __curent = [];
			__curent[key] = $("#bbit-"+val+"-select").find("option:selected").attr('value');
			$("#bbit-statistics-table-"+__curent[key]).css({'display': 'table'});
			
			//selection change!
			$("#bbit-"+val+"-select").change(function () {
				$("#bbit-statistics-table-"+__curent[key]).css({'display': 'none'}); //reset old selection!
				
				__curent[key] = $(this).find("option:selected").attr('value');
				$("#bbit-statistics-table-"+__curent[key]).css({'display': 'table'}); //apply new selection!
			});
		});
	}
	
	function plotAccordingToChoices( choiceContainer, datasets, plot_elm, opts ) 
	{	
		var data = [];
		$("#audience-choose-container").find("input:checked").each(function () {
			var key = $(this).attr("name");
			if (key && datasets[key]) {
				data.push(datasets[key]);
			}
		});

		if (data.length > 0) {
			$.plot( plot_elm, data, opts);
		}
	}


	function createInterface()
	{
		mainloading.fadeIn('fast');
		
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
		
		loadAudience();
	}

	function triggers()
	{
		createInterface();
		
		$("#bbit-filter-graph-data").click(function () {
			mainloading.fadeIn('fast');
			
			loadAudience();
		});
	}

	// external usage
	return {
    }
})(jQuery);
