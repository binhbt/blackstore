/*
Document   :  On Page Optimization
Author     :  Andrei Dinca, Bbit http://codecanyon.net/user/Bbit
*/
// Initialization and events code for the app
bbitOnPageOptimization = (function ($) {
    "use strict";

    // public
    var debug_level = 0;
    var maincontainer = null;
    var loading = null;
    var IDs = [];
    var loaded_page = 0;
    var selected_element = [];

	// init function, autoload
	(function init() {
		// load the triggers
		$(document).ready(function(){
			maincontainer = $("#bbit-wrapper");
			loading = maincontainer.find("#main-loading");

			triggers();
		});
	})();
	
	function tailCheckPages()
	{
		if( selected_element.length > 0 ){
			var curr_element = selected_element[0];
			optimizePage( curr_element.find('.bbit-do_item_optimize'), function(){
				selected_element.splice(0, 1);
				
				tailCheckPages();
			});
		}
	}
	
	function massOptimize()
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

	function optimizePage( that, callback )
	{
		var row = that.parents("tr").eq(0),
			id 	= row.data('itemid'),
			kw = row.find('input.bbit-text-field-kw').val();
		row_loading(row, 'show');
		
		var itemid = id, $box = row.parent().find('#bbit-inline-edit-post-'+itemid);
		if ( $box.length > 0 ) { //item current opened box => action is Quick Save & Optimize in callback!
			row_actions.saveBox( $box, row, [row_actions.optimizeBox, id, kw, row], callback );
			return false;
		} else { //other box => action is Optimize
			row_actions.optimizeBox( id, kw, row, callback );
		}
	}

	function do_progress_bar( row, score ) {
		score = score || 0;

		var progress_bar = row.find(".bbit-progress-bar");
		//progress_bar.attr('class', 'bbit-progress-bar');

		//var width = 100; //width = progress_bar.width();
		//width = parseFloat( parseFloat( parseFloat( score / 100 ).toFixed(2) ) * width ).toFixed(1);

		var size_class = 'size_';

		if ( score >= 20 && score < 40 ){
			size_class += '20_40';
		}
		else if ( score >= 40 && score < 60 ){
			size_class += '40_60';
		}
		else if( score >= 60 && score < 80 ){
			size_class += '60_80';
		}
		else if( score >= 80 && score <= 100 ){
			size_class += '80_100';
		}
		else{
			size_class += '0_20';
		}

		progress_bar
		.addClass( size_class )
		.width( score + '%' );

		row.find('.bbit-progress').find(".bbit-progress-score").text( score + "%" );
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
				do_progress_bar(row, response.score);

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

	function fixMetaBoxLayout()
	{
		//meta boxes
		var meta_box 		= $(".bbit-meta-box-container .bbit-dashboard-box-content.bbit-seo-status-container"),
			meta_box_width 	= $(".bbit-meta-box-container").width() - 100,
			row				= meta_box.find(".bbit-seo-rule-row");
 
		row.width(meta_box_width - 40);
		row.find(".right-col").width( meta_box_width - 180 );
		row.find(".message-box").width(meta_box_width - 45);
		row.find(".right-col .message-box").width( meta_box_width - 180 );


		$("#bbit_onpage_optimize_meta_box #bbit-meta-box-preload").hide();
		$("#bbit_onpage_optimize_meta_box .bbit-meta-box-container").fadeIn('fast');

		$("#bbit_onpage_optimize_meta_box").on('click', '.bbit-tab-menu a', function(e){
			e.preventDefault();

			var that 	= $(this),
				open 	= $("#bbit_onpage_optimize_meta_box .bbit-tab-menu a.open"),
				href 	= that.attr('href').replace('#', '');

			$("#bbit_onpage_optimize_meta_box .bbit-meta-box-container").hide();

			$("#bbit_onpage_optimize_meta_box #bbit-tab-div-id-" + href ).show();

			// close current opened tab
			var rel_open = open.attr('href').replace('#', '');

			$("#bbit_onpage_optimize_meta_box #bbit-tab-div-id-" + rel_open ).hide();

			$("#bbit_onpage_optimize_meta_box #bbit-meta-box-preload").show();

			$("#bbit_onpage_optimize_meta_box #bbit-meta-box-preload").hide();
			$("#bbit_onpage_optimize_meta_box .bbit-meta-box-container").fadeIn('fast');

			open.removeClass('open');
			that.addClass('open');
		});
		
		$(".bbit-dashboard-box").on('click', '#bbit-edit-focus-keywords', function(e){
			e.preventDefault();
			
			$(".bbit-tab-menu a[href='#page_meta']").click();
			$("#bbit-field-focuskw").focus();
		});
		
		$(".bbit-dashboard-box").on('click', '#bbit-btn-metabox-autofocus2', function(e){
			e.preventDefault();
			
			$(".bbit-tab-menu a[href='#page_meta']").click();
			metaboxAutofocus();
		});
		$("#bbit-tab-div-id-page_meta").on('click', '#bbit-btn-metabox-autofocus', function(e){
			e.preventDefault();
			
			metaboxAutofocus();
		});
	}
	
	function snippetPreview()
	{
		var focus_kw 	= $("#bbit-field-focuskw").val(),
			title 		= $("#bbit-field-title").val(),
			title_fb 	= $("#bbit-field-facebook-titlu").val(),
			desc 		= $("#bbit-field-metadesc").val(),
			link		= $("#sample-permalink").text(),
			prev_box 	= $(".bbit-prev-box"),
			post_title	= title;
			
		var $title = $("input[name='post_title']"), $titleTax = $(".form-table").find("input[name='name']");

		if ( $title.length > 0 )
			post_title = $title.val();
		else if ( $titleTax.length >0 )
			post_title = $titleTax.val();
		
		if( $.trim(post_title) == 'Auto Draft' ){
			post_title = '';
		}
		
		/*if ( $.trim( focus_kw ) == '' )
			$("#bbit-field-focuskw").val( post_title );
		if ( $.trim( title ) == '' )
			$("#bbit-field-title").val( post_title );
		if ( $.trim( title_fb ) == '' )
			$("#bbit-field-facebook-titlu").val( post_title );*/

		prev_box.find(".bbit-prev-focuskw").text( $("#bbit-field-focuskw").val() );
		prev_box.find(".bbit-prev-title").text( $("#bbit-field-title").val() );
		prev_box.find(".bbit-prev-desc").text( desc );
		prev_box.find(".bbit-prev-url").text( link );
		
		$("#bbit-field-title").bbitLimitChars( $("#bbit-field-title-length") );
	}
	
	function metaboxAutofocus() {
		var $box = $('.bbit-meta-box-container .bbit-tab-container'), $boxData = $('.bbit-meta-box-container #bbit-inline-row-data');

		var postData = {};
		postData.title 			= $boxData.find('.bbit-post-title').text();
		postData.gen_desc		= $boxData.find('.bbit-post-gen-desc').text();
		postData.gen_kw			= $boxData.find('.bbit-post-gen-keywords').text();
		postData.meta_title 	= $boxData.find('.bbit-post-meta-title').text();
		postData.meta_desc 		= $boxData.find('.bbit-post-meta-description').text();
		postData.meta_kw 		= $boxData.find('.bbit-post-meta-keywords').text();
		postData.focus_kw 		= $boxData.find('.bbit-post-meta-focus-kw').text();
 
		if ( $.trim( $box.find('input[name="bbit-field-focuskw"]').val() ) == '' )
		$box.find('input[name="bbit-field-focuskw"]').val( postData.title );

		if ( $.trim( $box.find('input[name="bbit-field-title"]').val() ) == '' )
		$box.find('input[name="bbit-field-title"]').val( postData.title );

		if ( $.trim( $box.find('textarea[name="bbit-field-metadesc"]').val() ) == '' )
		$box.find('textarea[name="bbit-field-metadesc"]').val( postData.gen_desc );

		if ( $.trim( $box.find('textarea[name="bbit-field-metakewords"]').val() ) == '' ) {
			var __keywords = [];
			if ( $.trim( $box.find('input[name="bbit-field-focuskw"]').val() ) != '' )
				__keywords.push( $box.find('input[name="bbit-field-focuskw"]').val() );
			if ( $.trim( postData.gen_kw ) != '' )
				__keywords.push( postData.gen_kw );
			__keywords = __keywords.join(', ');
			$box.find('textarea[name="bbit-field-metakewords"]').val( __keywords );
		}
		
		//facebook
		/*if ( $.trim( $box.find('input[name="bbit-field-facebook-titlu"]').val() ) == '' )
		$box.find('input[name="bbit-field-facebook-titlu"]').val( postData.title );

		if ( $.trim( $box.find('textarea[name="bbit-field-facebook-desc"]').val() ) == '' )
		$box.find('textarea[name="bbit-field-facebook-desc"]').val( postData.gen_desc );*/
	}
	
	function charsLeft() {

		$("#bbit-field-metadesc").bbitLimitChars( $("#bbit-field-metadesc-length") );
		$("#bbit-field-metakeywords").bbitLimitChars( $("#bbit-field-metakeywords-length") );
		$("#bbit-field-title").bbitLimitChars( $("#bbit-field-title-length") );
	}
	
	function triggers()
	{
		// metaboxAutofocus();
		
		snippetPreview();
		setInterval(function(){
			snippetPreview();
		}, 2000);
		
		// init google suggest
		$('input.bbit-text-field-kw').googleSuggest({
			service: 'web'
		});
		
		// init google suggest
		$('input#bbit-field-focuskw').googleSuggest({
			service: 'web'
		});
		
		$(".bbit-dashboard-box").each(function(){
			var that = $(this),
				rel = that.attr('rel');
			if( rel != "" ){
				var rel_elm = $("#" + rel);
				if( rel_elm.size() > 0 ){
					var elmHeight = that.height();
					var relHeight = rel_elm.height();

					if( elmHeight > relHeight ){
						rel_elm.height( elmHeight );
					}else if ( relHeight > elmHeight ) {
						that.height( relHeight );
					}
				}
			}
		});

		maincontainer.on('click', 'a.bbit-seo-report-btn', function(e){
			e.preventDefault();

			var that 	= $(this),
				row 	= that.parents("tr").eq(0),
				field 	= row.find('input.bbit-text-field-kw'),
				itemID	= that.data('itemid');

			row_loading(row, 'show');

			getSeoReport( itemID, field.val(), row );
		});

		/*maincontainer.on('click', 'input.bbit-do_item_optimize', function(e){
			e.preventDefault();

			var that 	= $(this),
				row 	= that.parents("tr").eq(0),
				itemID	= row.data('itemid'),
				field 	= row.find('input.bbit-text-field-kw'),
				title   = row.find('input#bbit-item-title-' + itemID);

			row_loading(row, 'show');

			//if( $.trim(title.val()) == "" ){
			//	row_loading(row, 'hide');
			//	alert('Your post don\' have Focus Keyword.'); return false;
			//}
			optimizePage(itemID, field.val(), row);
		});
		maincontainer.on('click', '#bbit-all-optimize', function(){
			var that 	= $(this);
			$('#bbit-list-table-posts input.bbit-item-checkbox:checked').each(function(){
				var that2 	= $(this),
					row 	= that2.parents("tr").eq(0),
					itemID	= row.data('itemid'),
					field 	= row.find('input.bbit-text-field-kw'),
					title   = row.find('input#bbit-item-title-' + itemID);;

				row_loading(row, 'show');

				//if( $.trim(field.val()) == "" ){
				//	row_loading(row, 'hide');
				//	alert('Your post don\' have Focus Keyword.'); return false;
				//}
				optimizePage(itemID, field.val(), row);
			});
		});*/

		maincontainer.on('click', 'input.bbit-do_item_optimize', function(e){
			e.preventDefault();

			optimizePage( $(this) );
		});
		maincontainer.on('click', '#bbit-all-optimize', function(e){
			e.preventDefault();
			
			massOptimize( $(this) );
		});
		
		//autodetect
		maincontainer.on('click', 'input.bbit-auto-detect-kw-btn', function(e){
			e.preventDefault();

			var that 	= $(this),
				row 	= that.parents("tr").eq(0),
				itemID	= row.data('itemid'),
				field 	= row.find('input.bbit-text-field-kw'),
				title   = row.find('input#bbit-item-title-' + itemID);

			/* edit post inline */
			row_actions.itemid = itemID;
			row_actions.itemPrev = row_actions.itemCurrent;
			row_actions.itemCurrent = itemID;
			
			row_actions.autoFocus( row, itemID );

			/*row_loading(row, 'show');
			
			if( $.trim(field.val()) == "" ){
				if( $.trim(title.val()) == "" ){

					row_loading(row, 'hide');
					alert('Your post don\' have any title.'); return false;
				}

				field.val( title.val() );
				row_loading(row, 'hide');
			}
			else{
				row_loading(row, 'hide');
			}*/
		});

		maincontainer.on('click', '#bbit-all-auto-detect-kw', function(){
			var that 	= $(this);
			var rowLast = null;

			$('#bbit-list-table-posts input.bbit-item-checkbox:checked').each(function(){
				var that2 	= $(this),
					row 	= that2.parents("tr").eq(0),
					itemID	= row.data('itemid'),
					field 	= row.find('input.bbit-text-field-kw'),
					title   = row.find('input#bbit-item-title-' + itemID);

				/* edit post inline */
				row_actions.itemid = itemID;
				row_actions.itemPrev = row_actions.itemCurrent;
				row_actions.itemCurrent = itemID;
				
				row_actions.autoFocus( row, itemID );
				
				rowLast = row;
				
				/*row_loading(row, 'show');

				if( $.trim(field.val()) == "" ){
					if( $.trim(title.val()) == "" ){

						row_loading(row, 'hide');
						alert('Your post don\' have any title.'); return false;
					}

					field.val( title.val() );
					row_loading(row, 'hide');
				}
				else{
					row_loading(row, 'hide');
				}*/
			});
			
			//special case: close last box
			var $box = rowLast.parent().find('#bbit-inline-edit-post-'+row_actions.itemCurrent);
			row_actions.closeBox( $box, rowLast );
		});

		fixMetaBoxLayout();
		
		row_actions.init();
		
		charsLeft();
		
		// twitter cards
		twitter_cards.init();
	}
	
	// twitter cards
	var twitter_cards = {
		init: function() {
			var self = this;

			// load the triggers
			$(document).ready(function(){
				self.triggers();
			});
		},
		
		get_options: function(type) {
			var __type = type || '';
			if ( $.trim(__type)=='' ) return false;
			
			$("#bbit_onpage_optimize_meta_box #bbit-meta-box-preload").show();
			$("#bbit_onpage_optimize_meta_box .bbit-meta-box-container").hide();

			var $boxData = $('.bbit-meta-box-container #bbit-inline-row-data')
			var theTrigger = ( __type=='post' ? $('#bbit_twc_post_cardtype') : $('#bbit_twc_app_isactive') ), theTriggerVal = theTrigger.val();
			var theResp = ( __type=='post' ? $('#bbit-twittercards-post-response') : $('#bbit-twittercards-app-response') );

			if ( $.inArray(theTriggerVal, ['none', 'no']) > -1 ) {
				theResp.html('').hide();
				$("#bbit_onpage_optimize_meta_box #bbit-meta-box-preload").hide();
				$("#bbit_onpage_optimize_meta_box .bbit-meta-box-container").show();
				return false;
			}
			if ( __type=='app' && theTriggerVal=='default' ) {
				theResp.html('').hide();
				return false;
			}

			$.post(ajaxurl, {
				'action' 		: 'bbitTwitterCards',
				'sub_action'		: 'getCardTypeOptions',
				'card_type'		: __type=='post' ? $('#bbit_twc_post_cardtype').val() : 'app',
				'page'			: __type=='post' ? 'post' : 'post-app',
				'post_id'		: parseInt( $boxData.find('.bbit-post-postId').text() )
			}, function(response) {

				$("#bbit_onpage_optimize_meta_box #bbit-meta-box-preload").hide();
				$("#bbit_onpage_optimize_meta_box .bbit-meta-box-container").show();

				var theResp = ( __type=='post' ? $('#bbit-twittercards-post-response') : $('#bbit-twittercards-app-response') );
				if ( response.status == 'valid' ) {
					theResp.html( response.html ).show();
					return true;
				}
				return false;
			}, 'json');
		},
		
		triggers: function() {
			var self = this;
			
			self.get_options( 'post' );
			self.get_options( 'app' );
	
			$('#bbit-tab-div-id-twitter_cards #bbit_twc_post_cardtype').on('change', function (e) {
				e.preventDefault();

				self.get_options( 'post' );
			});
			$('#bbit-tab-div-id-twitter_cards #bbit_twc_app_isactive').on('change', function (e) {
				e.preventDefault();

				self.get_options( 'app' );
			});
		}
	}
	
	var row_actions = {
		itemid		: null, //current itemid
		
		//current & previous item box opened for inline edit!
		itemCurrent	: null,
		itemPrev	: null,
		opened		: null,
		
		init: function() {
			var self = this;

			self.triggers();
		},
		
		triggers: function() {
			var self = this;
			
			var tableSelector = '.bbit-table-ajax-list table.bbit-table';
			
			// show | hide row actions on hover over table tr!
			/*maincontainer.on({
				mouseenter: function () {
					//current item box opened!
					if ( self.opened === true && self.itemCurrent == $(this).data('itemid') )
						return false;
					$(this).find('td span.bbit-inline-row-actions').removeClass('hide').addClass('show');
				},
				mouseleave: function () {
					//current item box opened!
					if ( self.opened === true && self.itemCurrent == $(this).data('itemid') )
						return false;
					$(this).find('td span.bbit-inline-row-actions').removeClass('show').addClass('hide');
				}
			}, tableSelector+' tr');*/

			maincontainer.on('click', tableSelector+' tr td span.bbit-inline-row-actions .editinline',
			function (e) {
				e.preventDefault();
				
				var row = $(this).parents('tr').eq(0),
				itemID	= row.data('itemid');
				
				row_loading(row, 'show');

				self.itemid = itemID;

				self.itemPrev = self.itemCurrent;
				self.itemCurrent = itemID;
				
				if ( $('#bbit-inline-edit-post-'+self.itemCurrent).length > 0 ) { //current item box is already opened
					
					// populate box!
					self.boxPopulate( row, self.itemid );

					return false;
				}

				//remove previous item box & row actions view!
				$('#bbit-inline-edit-post-'+self.itemPrev).remove();
				//row.parent().find('td span#bbit-inline-row-actions-'+self.itemPrev).removeClass('show').addClass('hide');
				
				//build item edit box
				self.buildBox( row, self.itemid );
				
			});
		},
		
		autoFocus: function( row, itemid ) {
			var self = this;
			
			row_loading(row, 'show');
			
			if ( $('#bbit-inline-edit-post-'+self.itemCurrent).length > 0 ) { //current item box is already opened
				
				// populate box!
				self.boxPopulate( row, itemid, true );
				
				return false;
			}

			//remove previous item box & row actions view!
			$('#bbit-inline-edit-post-'+self.itemPrev).remove();
			//row.parent().find('td span#bbit-inline-row-actions-'+self.itemPrev).removeClass('show').addClass('hide');

			//build item edit box
			self.buildBox( row, itemid, true );
		},
		
		buildBox: function( row, itemid, autofocus ) {
			var self = this;
			self.opened = true;

			// create box html!
			self.boxHtml( row, itemid );
			
			// populate box!
			self.boxPopulate( row, itemid, autofocus );
		},
		
		boxHtml: function( row, itemid ) {
			var self = this;

			var	table = row.parent(), __boxhtml = $('#bbit-inline-editpost-boxtpl').html();
			__boxhtml = '<form class="bbit-form form-inline-editpost" action="#save_with_ajax">'
				+ __boxhtml + '</form>';
					
			row.after(
				$( '<tr id="bbit-inline-edit-post-'+itemid+'" data-itemid="'+itemid+'"></tr>' )
					.append( $('<td colspan=10></td></tr>' ).html( __boxhtml ) )
					.hide()
			);
			
			// retrieve box element!
			var $box = table.find('#bbit-inline-edit-post-'+itemid);
			
			// box buttons handlers
			$box.find('input#bbit-inline-btn-cancel').bind('click', function (e) {
				self.closeBox( $box, row );
			});
			$box.find('input#bbit-inline-btn-save').bind('click', function (e) {
				self.saveBox( $box, row );
			});
		},
		
		boxPopulate: function( row, itemid, autofocus ) {
			var self = this;
			
			var autofocus = autofocus || false;
			
			// retrieve box element!
			var	table = row.parent(), $box = table.find('#bbit-inline-edit-post-'+itemid),
			$boxData = row.find('#bbit-inline-row-data-'+itemid);
			
			var postData = {};
			postData.title 			= $boxData.find('.bbit-post-title').text();
			postData.gen_desc		= $boxData.find('.bbit-post-gen-desc').text();
			postData.gen_kw			= $boxData.find('.bbit-post-gen-keywords').text();
			postData.meta_title 	= $boxData.find('.bbit-post-meta-title').text();
			postData.meta_desc 		= $boxData.find('.bbit-post-meta-description').text();
			postData.meta_kw 		= $boxData.find('.bbit-post-meta-keywords').text();
			postData.focus_kw 		= $boxData.find('.bbit-post-meta-focus-kw').text();
			postData.canonical		= $boxData.find('.bbit-post-meta-canonical').text();
			postData.rindex 		= $boxData.find('.bbit-post-meta-robots-index').text();
			postData.rfollow 		= $boxData.find('.bbit-post-meta-robots-follow').text();

			//$box.find('input[name="bbit-editpost-meta-focus-kw"]').val( postData.focus_kw );
			row.find('input.bbit-text-field-kw').val( postData.focus_kw )
			$box.find('input[name="bbit-editpost-meta-title"]').val( postData.meta_title );
			$box.find('textarea[name="bbit-editpost-meta-description"]').val( postData.meta_desc );
			$box.find('textarea[name="bbit-editpost-meta-keywords"]').val( postData.meta_kw );
			$box.find('input[name="bbit-editpost-meta-canonical"]').val( postData.canonical );
			$box.find('select[name="bbit-editpost-meta-robots-index"]').val( postData.rindex );
			$box.find('select[name="bbit-editpost-meta-robots-follow"]').val( postData.rfollow );

			if ( autofocus ) {
				//if ( $.trim( $box.find('input[name="bbit-editpost-meta-focus-kw"]').val() ) == '' )
				//	$box.find('input[name="bbit-editpost-meta-focus-kw"]').val( postData.title );
				
				if ( $.trim( row.find('input.bbit-text-field-kw').val() ) == '' )
					row.find('input.bbit-text-field-kw').val( postData.title );
					
				if ( $.trim( $box.find('input[name="bbit-editpost-meta-title"]').val() ) == '' )
					$box.find('input[name="bbit-editpost-meta-title"]').val( postData.title );
					
				if ( $.trim( $box.find('textarea[name="bbit-editpost-meta-description"]').val() ) == '' )
					$box.find('textarea[name="bbit-editpost-meta-description"]').val( postData.gen_desc );
					
				if ( $.trim( $box.find('textarea[name="bbit-editpost-meta-keywords"]').val() ) == '' ) {
					var __keywords = [];
					if ( $.trim( row.find('input.bbit-text-field-kw').val() ) != '' )
						__keywords.push( row.find('input.bbit-text-field-kw').val() );
					if ( $.trim( postData.gen_kw ) != '' )
						__keywords.push( postData.gen_kw );
					__keywords = __keywords.join(', ');
					$box.find('textarea[name="bbit-editpost-meta-keywords"]').val( __keywords );
				}
			}
			
			$box.show();
			
			row_loading(row, 'hide');
		},
		
		closeBox: function( $box, row ) {
			var self = this;
			$box.remove();
			//row.parent().find('td span#bbit-inline-row-actions-'+self.itemCurrent).removeClass('show').addClass('hide');
			self.opened = false;
		},
		
		saveBox: function( $box, row, callback, callback2 ) {
			var self = this;
			
			var doOptimize = doOptimize || false;
			
			row_loading(row, 'show');

			var $form = $box.find('.form-inline-editpost'),
			data_save = $form.serializeArray(),
			kw = row.find('input.bbit-text-field-kw').val();

	    	data_save.push({ name: "action", value: "bbitQuickEdit" });
	    	data_save.push({ name: "debug_level", value: debug_level });
	    	data_save.push({ name: "id", value: self.itemid });
	    	data_save.push({ name: "kw", value: kw });

			// since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
			jQuery.post(ajaxurl, data_save, function(response) {

				var id = response.post_id, new_inline = response.edit_inline_new;

				$('#bbit-inline-row-data-'+id).html( new_inline ); //refresh post info!
				row.find('input.bbit-text-field-kw').val( kw ) //refresh focus keyword main table input!

				do_progress_bar(row, response.score); //refresh score!

				row_loading(row, 'hide');

				self.closeBox( $box, row );
				
				if ( $.isArray( callback ) && $.isFunction( callback[0] ) ) {
					callback[0]( callback[1], callback[2], callback[3], callback2 );
				}

			}, 'json');
		},
		
		optimizeBox: function( id, kw, row, callback ) {
			// since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
			jQuery.post(ajaxurl, {
				'action' 		: 'bbitOptimizePage',
				'kw'			: kw,
				'id'			: id,
				'debug_level'	: debug_level
			}, function(response) {

				if( response.status == 'valid' ){
					var id = response.post_id, new_inline = response.edit_inline_new;
					$('#bbit-inline-row-data-'+id).html( new_inline ); //refresh post info!
					row.find('input.bbit-text-field-kw').val( response.kw ) //refresh focus keyword main table input!
	
					do_progress_bar(row, response.score);
				}
	
				row_loading(row, 'hide');
				if( typeof callback == 'function' ){
					callback();
				}
			}, 'json');
		}
		
	}
	

	// external usage
	return {
		"optimizePage": optimizePage
    }
})(jQuery);

(function ($) {
	$.fn.googleSuggest = function (opts) {
	    opts = $.extend({
	        service: 'web',
	        secure: false
	    }, opts);

	    var services = {
	        youtube: {
	            client: 'youtube',
	            ds: 'yt'
	        },
	        books: {
	            client: 'books',
	            ds: 'bo'
	        },
	        products: {
	            client: 'products-cc',
	            ds: 'sh'
	        },
	        news: {
	            client: 'news-cc',
	            ds: 'n'
	        },
	        images: {
	            client: 'img',
	            ds: 'i'
	        },
	        web: {
	            client: 'psy',
	            ds: ''
	        },
	        recipes: {
	            client: 'psy',
	            ds: 'r'
	        }
	    }, service = services[opts.service];

	    opts.source = function (request, response) {
	        $.ajax({
	            url: 'http' + (opts.secure ? 's' : '') + '://www.google.com/complete/search',
	            dataType: 'jsonp',
	            data: {
	                q: request.term,
	                nolabels: 't',
	                client: service.client,
	                ds: service.ds
	            },
	            success: function (data) {
	                response($.map(data[1], function (item) {
	                    return {
	                        value: $("<span>")
	                            .html(item[0])
	                            .text()
	                    };
	                }));
	            }
	        });
	    };

	    return this.each(function () {
	        $(this)
	            .autocomplete(opts);
	    });
	}
})(jQuery);

(function($) {
	$.fn.extend( {
		bbitLimitChars: function(charsLeftElement, maxLimit) {
			$(this).on("keyup focus", function() {
				countChars($(this), charsLeftElement);
			});
			function countChars(element, charsLeftElement) {
				if ( typeof element == 'undefined' || typeof element.val() == 'undefined' ) return false;

				maxLimit = maxLimit || parseInt( element.attr('maxlength') );
				var currentChars = element.val().length;
				if ( currentChars > maxLimit ) {
					//element.value = element.val( substr(0, maxLimit) );
					element.value = bbitFreamwork.substr_utf8_bytes(element.val(), 0, maxLimit);
					currentChars = maxLimit;
				}
				charsLeftElement.html( maxLimit - currentChars );
			}
			countChars($(this), charsLeftElement);
		}
	} );
})(jQuery);