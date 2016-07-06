/*
Document   :  Social Stats
Author     :  Andrei Dinca, Bbit http://bbit.vn
*/
// Initialization and events code for the app
bbitLinkBuilder = (function ($) {
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
	
	function setFlagAdd(val) {
		localStorage.setItem('add_flag', val);
	}
	function getFlagAdd() {
		var myValue = localStorage.getItem( 'add_flag' );
    	if (myValue)
        	return myValue;
        return 0;
	}
	
	function showAddNewLink()
	{
		$('#bbit-lightbox-overlay').find('#bbit-lightbox-seo-report-response-details, #link-title-details')
			.css({'display': 'none'});
		$('#bbit-lightbox-overlay').find('#bbit-lightbox-seo-report-response2, #link-title-upd')
			.css({'display': 'none'});
		$('#bbit-lightbox-overlay').find('#bbit-lightbox-seo-report-response, #link-title-add')
			.css({'display': 'table'});

		lightbox.fadeIn('fast');
		
		lightbox.find("a.bbit-close-btn").click(function(e){
			e.preventDefault();
			lightbox.fadeOut('fast');
		});
	}
	
	function showUpdateLink()
	{
		$('#bbit-lightbox-overlay').find('#bbit-lightbox-seo-report-response-details, #link-title-details')
			.css({'display': 'none'});
		$('#bbit-lightbox-overlay').find('#bbit-lightbox-seo-report-response2, #link-title-upd')
			.css({'display': 'table'});
		$('#bbit-lightbox-overlay').find('#bbit-lightbox-seo-report-response, #link-title-add')
			.css({'display': 'none'});

		lightbox.fadeIn('fast');
		
		lightbox.find("a.bbit-close-btn").click(function(e){
			e.preventDefault();
			lightbox.fadeOut('fast');
		});
	}
	
	function showDetails()
	{
		$('#bbit-lightbox-overlay').find('#bbit-lightbox-seo-report-response-details, #link-title-details')
			.css({'display': 'table'});
		$('#bbit-lightbox-overlay').find('#bbit-lightbox-seo-report-response2, #link-title-upd')
			.css({'display': 'none'});
		$('#bbit-lightbox-overlay').find('#bbit-lightbox-seo-report-response, #link-title-add')
			.css({'display': 'none'});

		lightbox.fadeIn('fast');
		
		lightbox.find("a.bbit-close-btn").click(function(e){
			e.preventDefault();
			lightbox.fadeOut('fast');
		});
	}
	
	function getDetails( itemid )
	{
		mainloading.fadeIn('fast');
			
		// since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
		jQuery.post(ajaxurl, {
			'action' 		: 'bbitGetUpdateDataBuilder',
			'itemid'		: itemid,
			'debug_level'	: debug_level
		}, function(response) {
			if( response.status == 'valid' ){
				mainloading.fadeOut('fast');
				
				var r = response.data, $details = $('#bbit-lightbox-seo-report-response-details');

				$details.find('#details_url').text( r.url );
				$details.find('#details_text').text( r.phrase );
				$details.find('#details_title').text( r.title );
				$details.find('#details_rel').text( r.rel );
				$details.find('#details_target').text( r.target );
				$details.find('#details_max_replacements').text( r.max_replacements );

				showDetails();
			}
			mainloading.fadeOut('fast');
			return false;

		}, 'json');
	}
	
	function addToBuilder( $form )
	{
		lightbox.fadeOut('fast');
		mainloading.fadeIn('fast');
		
		var url = $form.find('#new_url'), url_val = url.val();
		if (!url_val.match("^http?://")) url.val("http://" + url_val);

		var data_save = $form.serializeArray();
    	data_save.push({ name: "action", value: "bbitAddToBuilder" });
    	data_save.push({ name: "debug_level", value: debug_level });
    	data_save.push({ name: "itemid", value: 0 });

		jQuery.post(ajaxurl, data_save, function(response) {
			if( response.status == 'valid' ) {
				setFlagAdd(1);
				mainloading.fadeOut('fast');
				window.location.reload();
			}
			mainloading.fadeOut('fast');
			return false;
		}, 'json');
	}
	
	function getUpdateData( itemid ) {
		mainloading.fadeIn('fast');

		jQuery.post(ajaxurl, {
			'action' 		: 'bbitGetUpdateDataBuilder',
			'itemid'		: itemid,
			'debug_level'	: debug_level
		}, function(response) {
			if( response.status == 'valid' ){
				mainloading.fadeOut('fast');

				setUpdateForm( response.data );
				showUpdateLink();
			}
			mainloading.fadeOut('fast');
			return false;
		}, 'json');
	}

	function setUpdateForm( data ) {
		var $form = $('.bbit-update-link-form'),
		itemid = data.id, phrase = data.phrase, url = data.url, title = data.title,
		rel = data.rel, target = data.target, max_replacements = data.max_replacements;

		$form.find('input#upd-itemid').val( itemid ); //hidden field to indentify used row for update!
		$form.find('input#new_text2').val( phrase );
		$form.find('input#new_url2').val( url );
		$form.find('input#new_title2').val( title );
		$form.find('select#rel2').val( rel );
		$form.find('select#target2').val( target );
		$form.find('select#max_replacements2').val( max_replacements );
	}
	
	function updateToBuilder( itemid, subaction )
	{
		subaction = subaction || '';
		
		var $form = $('.bbit-update-link-form');
		
		var data_save = $form.serializeArray();
    	data_save.push({ name: "action", value: "bbitUpdateToBuilder" });
    	data_save.push({ name: "subaction", value: subaction });
    	data_save.push({ name: "debug_level", value: debug_level });
    	data_save.push({ name: "itemid", value: itemid });
			
		lightbox.fadeOut('fast');
		mainloading.fadeIn('fast');
		
		jQuery.post(ajaxurl, data_save, function(response) {
			if( response.status == 'valid' ){
				setFlagAdd(1);
				
				if ( subaction == 'publish' ) ;
				else
					mainloading.fadeOut('fast');

				window.location.reload();
			}
			mainloading.fadeOut('fast');
			return false;
		}, 'json');
	}
	
	function deleteFromBuilder( itemid )
	{
		lightbox.fadeOut('fast');
		mainloading.fadeIn('fast');
		
		jQuery.post(ajaxurl, {
			'action' 		: 'bbitRemoveFromBuilder',
			'itemid'		: itemid,
			'debug_level'	: debug_level
		}, function(response) {
			if( response.status == 'valid' ){
				setFlagAdd(1);
				mainloading.fadeOut('fast');
				window.location.reload();
			}
			mainloading.fadeOut('fast');
			return false;
		}, 'json');
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
		
		mainloading.fadeIn('fast');

		jQuery.post(ajaxurl, {
			'action' 		: 'bbitLinkBuilder_do_bulk_delete_rows',
			'id'			: ids,
			'debug_level'	: debug_level
		}, function(response) {
			if( response.status == 'valid' ){
				mainloading.fadeOut('fast');
				setFlagAdd(1);
				//refresh page!
				window.location.reload();
				return false;
			}
			mainloading.fadeOut('fast');
			alert('Problems occured while trying to delete the selected rows!');
		}, 'json');
	}
	
	function triggers()
	{
		// add form lightbox
		if (getFlagAdd()==0) ;//showAddNewLink();
		setFlagAdd(0);

		maincontainer.on("click", '#bbit-do_add_new_link', function(e){
			e.preventDefault();
			showAddNewLink();
		});
		
		maincontainer.on('click', 'a.bbit-btn-url-attributes-lightbox', function(e){
			e.preventDefault();

			var that 	= $(this),
				itemID	= that.data('itemid');

			//getDetails( itemID, that.attr('href').replace("#", '') );
			getDetails( itemID );
		});
		
		// add row
		$('body').on('click', ".bbit-add-link-form input#bbit-submit-to-builder", function(e){
			e.preventDefault();
			
			var $form = $('.bbit-add-link-form'),
			phrase = $form.find('#new_text').val(),
			url = $form.find('#new_url').val(),
			title = $form.find('#new_title').val(),
			rel = $form.find('#rel').val(),
			target = $form.find('#target').val(),
			max_replacements = $form.find('#max_replacements').val();
			
			//maybe some validation!
			if ($.trim(phrase)=='' || $.trim(url)=='' || $.trim(title)=='') {
				alert('You didn\'t complete the necessary fields!');
				return false;
			}
			
			//verify founds!
			jQuery.post(ajaxurl, {
				'action' 		: 'bbitGetHitsByPhrase',
				'phrase'		: $form.find('#new_text').val(),
				'debug_level'	: debug_level
			}, function(response) {
					if( response.status == 'valid' ){
						var $hitsText = $('.bbit-add-link-form #bbit-builder-text-hits');
						$hitsText.find('span').text( response.data );
						$hitsText.css( {'display': 'inline'} );

						var $new_hits = $form.find('#new_hits');
						$new_hits.val( response.data );
						if ($new_hits.val()<=0) {
							alert('No possible occurences for the text you\'ve entered!');
							return false;
						}
						
						addToBuilder( $form );
					}
					return false;
			}, 'json');
		});
		$('body').on('click', ".bbit-add-link-form input#bbit-builder-verify-hits", function(e){
			e.preventDefault();
			
			//verify founds!
			var $form = $('.bbit-add-link-form');
			
			jQuery.post(ajaxurl, {
				'action' 		: 'bbitGetHitsByPhrase',
				'phrase'		: $form.find('#new_text').val(),
				'debug_level'	: debug_level
			}, function(response) {
					if( response.status == 'valid' ){
						var $hitsText = $('.bbit-add-link-form #bbit-builder-text-hits');
						$hitsText.find('span').text( response.data );
						$hitsText.css( {'display': 'inline'} );
					}
					return false;
			}, 'json');
		});
		
		// delete row		
		$('body').on('click', ".bbit-do_item_delete", function(e){
			e.preventDefault();
			var that = $(this),
				row = that.parents('tr').eq(0),
				id	= row.data('itemid'),
				key = row.find('td').eq(3).find('input').val(),
				url = row.find('td').eq(4).find('input').val();

			//row.find('code').eq(0).text()
			if(confirm('Delete (' + key + ', ' + url  + ') pair from builder? This action can\t be rollback!' )){
				deleteFromBuilder( id );
			}
		});
		
		// update row info
		$('body').on('click', ".bbit-do_item_update", function(e){
			e.preventDefault();

			var that = $(this),
				row = that.parents('tr').eq(0),
				id	= row.data('itemid');

			getUpdateData( id );
		});
		$('body').on('click', ".bbit-update-link-form input#bbit-submit-to-builder2", function(e){
			e.preventDefault();

			var $form = $('.bbit-update-link-form'),
			itemid = $form.find('input#upd-itemid').val(),
			title = $form.find('input#new_title2').val();
	
			//maybe some validation!
			if ($.trim(title)=='') {
				alert('You didn\'t complete the necessary fields!');
				return false;
			}
			updateToBuilder( itemid );
		});
		
		// publish / unpublish row
		$('body').on('click', ".bbit-do_item_publish", function(e){
			e.preventDefault();
			var that = $(this),
				row = that.parents('tr').eq(0),
				id	= row.data('itemid');
				
			updateToBuilder( id, 'publish' );
		});
		
		maincontainer.on('click', '#bbit-do_bulk_delete_rows', function(e){
			e.preventDefault();

			if (confirm('Are you sure you want to delete the selected rows?'))
				delete_bulk_rows();
		});
		
		//all checkboxes are checked by default!
		$('.bbit-form .bbit-table input.bbit-item-checkbox').attr('checked', 'checked');
		
	}

	// external usage
	return {
    }
})(jQuery);
