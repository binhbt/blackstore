/*
Document   :  Backlink Builder
Author     :  Andrei Dinca, Bbit http://bbit.vn
*/

// Initialization and events code for the app
bbitBacklinkBuilder = (function ($) {
    "use strict";

    // public
    var debug = 1;
    var maincontainer = null;
    var mainloading = null;
    var lightboxloading = null;
    var lightbox = null;
    var last_submit_id = 0;
    var loaded_page = 0;

	// init function, autoload
	(function init() {
		// load the triggers
		$(document).ready(function(){
			maincontainer = $("#bbit-wrapper");
			mainloading = maincontainer.find("#bbit-main-loading");
			
			lightbox = $("#bbit-lightbox-overlay");
			lightboxloading = maincontainer.find("#bbit-lightbox-loading");
			
			triggers();
		});
	})();

	function changeSubmitStatus( id, new_status )
	{
		mainloading.fadeIn('fast');
		
		// since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
		jQuery.post(ajaxurl, {
			'action' 		: 'bbitPageBuilderRequest',
			'id'			: id,
			'sub_action'	: 'changeStatus',
			'new_status'	: new_status,
			'debug'			: debug
		}, function(response) {
			window.location.reload();
			
			return false;
		}, 'json');
	}
	
	function submitWebsite( id )
	{
		mainloading.fadeIn('fast');
		lightboxloading.show();
		
		// since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
		jQuery.post(ajaxurl, {
			'action' 		: 'bbitPageBuilderRequest',
			'id'			: id,
			'sub_action'	: 'changeStatus',
			'new_status'	: 'in_progress',
			'debug'			: debug
		}, function(response) {
			if( response.status == 'valid' ){

				var row = $('#bbit-list-table-posts .bbit-table').find('tr[data-itemid="'+id+'"]').eq(0),
				__msg = row.find('.bbit-message');
				__msg.removeClass('bbit-error bbit-success').addClass('bbit-info')
					.css({'background-image' : 'none'})
					.text( $('#bbit-submit-status-values .submit_inprogress').text() );

				mainloading.fadeOut('fast');
				
				var containerSize = {
					'width': 340,
					'height': 60
				}
				
				lightbox.find("#bbit-lightbox-container").css( {
					'width': parseInt( containerSize.width * 0.8 ) + "px",
					'height': parseInt( containerSize.height * 0.8 ) + "px",
					'margin-left': "-" + ( parseInt( (containerSize.width * 0.8) / 2  )) + "px",
				});
				
				lightbox.find(".bbit-lightbox-headline").width( parseInt( containerSize.width * 0.8 ) - 2 );
				
				lightbox.find('#bbit-lightbox-backlink-builder-response').html( response.html );
				
				
				lightbox.fadeIn('fast');
				
				lightbox.find("a.bbit-close-btn").click(function(e){
					e.preventDefault();
					lightbox.fadeOut('fast');
				});
			}
			
			mainloading.fadeOut('fast');
			return false;

		}, 'json');
	}
	
	
	function delete_rows() {
		var ids = [], __ck = $('.bbit-form .bbit-table input.bbit-item-checkbox:checked');
		__ck.each(function (k, v) {
			ids[k] = $(this).attr('name').replace('bbit-item-checkbox-', '');
		});
		ids = ids.join(',');
		if (ids.length<=0) {
			alert('You didn\'t select any rows!');
			return false;
		}
		
		mainloading.fadeIn('fast');

		jQuery.post(ajaxurl, {
			'action' 		: 'bbitGetPageBuilderRequest',
			'sub_action'	: 'removeDirectories',
			'id'			: ids,
			'debug'	: debug
		}, function(response) {
			if( response.status == 'valid' ){
				mainloading.fadeOut('fast');				
				//refresh page!
				window.location.reload();
				return false;
			}
			mainloading.fadeOut('fast');
			alert('Problems occured while trying to delete the selected rows!');
		}, 'json');
	}
	
	function import_directory_rows() {
		mainloading.fadeIn('fast');
    
		// since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
		jQuery.post(ajaxurl, {
			'action' 		: 'bbitPageBuilderRequest',
			'sub_action'	: 'import_directory_rows',
			'debug'			: debug
		}, function(response) {

			mainloading.fadeOut('fast');

			if ( response.status == 'valid' ) {
				if (confirm( response.html ))
					window.location.reload();
				return true;
			}
			
			alert( response.html );
			return false;
		}, 'json');
	}

	function triggers()
	{
		maincontainer.on('click', 'a.bbit-btn-submit-website', function(e){
			//e.preventDefault();
			var that 	= $(this),
				itemID	= that.data('itemid');
			
			last_submit_id = itemID;
			submitWebsite( itemID );
		});
		
		maincontainer.on('click', 'a.bbit-submit-status', function(e){
			e.preventDefault();

			changeSubmitStatus( last_submit_id, $(this).data('status') );
		});
		
		// delete bulk rows
		maincontainer.on('click', '#bbit-do_bulk_delete_directory_rows', function(e){
			e.preventDefault();

			if (confirm('Are you sure you want to delete the selected rows?'))
				delete_rows();
		});
		
		// import directory rows
		maincontainer.on('click', '#bbit-import_directory_rows', function(e){
			e.preventDefault();

			//if (confirm('Are you sure you want to import directory rows?'))
				import_directory_rows();
		});
		
		// all checkboxes are checked by default!
		$('.bbit-form .bbit-table input.bbit-item-checkbox').attr('checked', 'checked');
	}

	// external usage
	return {
    }
})(jQuery);
