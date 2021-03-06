/*
Document   :  Social Stats
Author     :  Andrei Dinca, Bbit http://codecanyon.net/user/Bbit
*/
// Initialization and events code for the app
bbitSocialStats = (function ($) {
    "use strict";

    // public
    var debug_level = 0;
    var maincontainer = null;
    var loading = null;
    var IDs = [];
    var loaded_page = 0;

	// init function, autoload
	(function init() {
		// load the triggers
		$(document).ready(function(){
			maincontainer = $("#bbit-wrapper");
			loading = maincontainer.find("#main-loading");

			triggers();
		});
	})();

	function verifyPage( id, row, callback )
	{
		// since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
		jQuery.post(ajaxurl, {
			'action' 		: 'bbitHtmlValidate',
			'id'			: id,
			'debug_level'	: debug_level
		}, function(response) {

			if( response.status == 'valid' ){
				row.find('strong.status').text( response.arr.status );
				if( response.arr.status == 'Invalid' ){
					row.find('strong.status').css('color', 'red');
				}else if( response.arr.status == 'Valid' ){
					row.find('strong.status').css('color', 'green');
				}
				row.find('i.nr_of_errors').text( response.arr.nr_of_errors );
				row.find('i.nr_of_warning').text( response.arr.nr_of_warning );
				row.find('i.last_check_at').text( response.arr.last_check_at );
			}

			row_loading(row, 'hide');

			if( typeof callback == "function" ){
				callback();
			}
		}, 'json');
	}

	function verifyAllPages()
	{
		// get all pages IDs
		var allPages = $(".bbit-table tbody tr");
		if( allPages.size() > 0 ){
			allPages.each(function(key, value) {
				IDs.push( $(value).data('itemid'));
			});
		}

		if( IDs.length > 0 ){
			tailPageVerify(0);
		}
	}

	function tailPageVerify( verify_step )
	{
		var page_id = IDs[verify_step],
			row 	= $("tr[data-itemid='" + page_id + "']");

		row_loading(row, 'show');

		// increse the loaded products marker
		++loaded_page;

		verifyPage( page_id, row, function(){
			// continue insert the rest of page_id
			if( IDs.length > verify_step ) {
				tailPageVerify( ++verify_step );
			}
		} );

	}

	function row_loading( row, status )
	{
		if( status == 'show' ){
			if( row.size() > 0 ){
				if( row.find('.bbit-row-loading-marker').size() == 0 ){
					var row_loading_box = $('<div class="bbit-row-loading-marker"><div class="bbit-row-loading"><div class="bbit-meter bbit-animate" style="width:30%; margin: 10px 0px 0px 30%;"><span style="width:100%"></span></div></div></div>')
					row_loading_box.find('div.bbit-row-loading').css({
						'width': row.width(),
						'height': row.height()
					});

					row.find('td').eq(0).append(row_loading_box);
				}
				row.find('.bbit-row-loading-marker').fadeIn('fast');
			}
		}else{
			row.find('.bbit-row-loading-marker').fadeOut('fast');
		}
	}

	function getSeoReport( id, kw, row )
	{
		var lightbox = $("#bbit-lightbox-overlay");

		// since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
		jQuery.post(ajaxurl, {
			'action' 		: 'bbitGetSeoReport',
			'id'			: id,
			'kw'			: kw,
			'debug_level'	: debug_level
		}, function(response) {

			if( response.status == 'valid' ){
				lightbox.find(".bbit-lightbox-headline i").text( response.post_id );
				lightbox.find("#bbit-lightbox-seo-report-response").html( response.html );
				lightbox.fadeIn('fast');
			}

			row_loading(row, 'hide');

		}, 'json');


		lightbox.find("a.bbit-close-btn").click(function(e){
			e.preventDefault();
			lightbox.fadeOut('fast');
		});
	}

	function triggers()
	{
		maincontainer.on('click', 'input.bbit-do_item_html_validation', function(e){
			e.preventDefault();

			var that 	= $(this),
				row 	= that.parents("tr").eq(0),
				itemID	= row.data('itemid'),
				field 	= row.find('input.bbit-text-field-kw'),
				title   = row.find('input#bbit-item-title-' + itemID);

			row_loading(row, 'show');

			if( $.trim(title.val()) == "" ){

				row_loading(row, 'hide');
				alert('Your post don\' have Focus Keyword.'); return false;
			}

			verifyPage(itemID, row);
		});

		maincontainer.on('click', '#bbit-do_bulk_html_validation', function(){
			var that 	= $(this);

			verifyAllPages();
		});
	}

	// external usage
	return {
		"verifyPage": verifyPage
    }
})(jQuery);
