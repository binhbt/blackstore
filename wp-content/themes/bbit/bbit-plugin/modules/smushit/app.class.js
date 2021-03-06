/*
Document   :  Smushit
Author     :  Andrei Dinca, Bbit http://codecanyon.net/user/Bbit
*/
// Initialization and events code for the app
bbitSmushit = (function ($) {
    "use strict";

    // public
    var debug_level = 0;
    var maincontainer = null;
    var loading = null;
    var selected_element = [];
    var inAction = 0;

	// init function, autoload
	(function init() {
		// load the triggers
		$(document).ready(function(){
			maincontainer = $("#bbit-wrapper");
			loading = maincontainer.find("#bbit-main-loading");

			triggers();
		});
	})();
	
	function row_loading( row, status )
	{
		/*if( status == 'show' ){
			row.find('span.bbit-smushit-loading').show();
		} else {
			row.find('span.bbit-smushit-loading').hide();
		}
		return true;*/

		if( status == 'show' ){
			if( row.size() > 0 ){
				
				if( row.find('.bbit-row-loading-marker').size() == 0 ){
					var row_loading_box = $('<div class="bbit-row-loading-marker" style="top: -' + ( parseInt(row.height()/2 - 3) ) + 'px;"><div class="bbit-row-loading"><div class="bbit-meter bbit-animate" style="width:30%; margin: 10px 0px 0px 30%;"><span style="width:100%"></span></div></div></div>')
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

	function smushit( that, callback ) {
		
		var row = that.parents("tr").eq(0),
			id 	= row.data('itemid');
		row_loading(row, 'show');

		// since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
		jQuery.post(ajaxurl, {
			'action' 		: 'bbit_smushit',
			'id'			: id,
			'debug_level'	: debug_level
		}, function(response) {

			var respEl = $('#bbit-smushit-resp-'+id);
			respEl.html( response.data );

			/*inAction = 0;
			$('a.bbit-smushit-action').unbind('click', false).removeClass('disabled');*/

			if( response.status == 'valid' ) {
				respEl.removeClass('info error').addClass('success');
			} else {
				respEl.removeClass('info success').addClass('error');
			}

			row_loading(row, 'hide');
			if( typeof callback == 'function' ){
				callback();
			}

		}, 'json');
	}
	
	function tailCheckPages()
	{
		if( selected_element.length > 0 ){
			var curr_element = selected_element[0];
			smushit( curr_element.find('.bbit-do_item_smushit'), function(){
				selected_element.splice(0, 1);
				
				tailCheckPages();
			});
		}
	}
	
	function massSmushit()
	{
		// reset this array for be sure
		selected_element = [];
		// find all selected items 
		maincontainer.find('.bbit-item-checkbox:checked').each(function(){
			var that = $(this),
				row = that.parents('tr').eq(0);
			selected_element.push( row );
		});
		
		tailCheckPages();
	}

	function triggers()
	{
		// smushit action - per row
		/*$("a.bbit-smushit-action").click(function (e) {
			e.preventDefault();

			if ( inAction == 1 ) return false;
			$('a.bbit-smushit-action').bind('click', false).addClass('disabled');
			inAction = 1;

			var that 	= $(this), row = that.parent(),
			itemid	= that.data('itemid');

			smushit( row );
		});*/

		maincontainer.on('click', '.bbit-do_item_smushit', function(e){
			e.preventDefault();

			smushit( $(this) );
		});

		maincontainer.on('click', '#bbit-do_mass_smushit', function(e){
			e.preventDefault();
			
			massSmushit( $(this) );
		});
		
		// smushit bulk action
		//$('select[name^="action"] option:last-child').before('<option value="bbit_smushit_bulk">PSP Smushit bulk</option>');
	}

	// external usage
	return {
    }
})(jQuery);