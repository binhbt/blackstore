/*
Document   :  404 Monitor
*/

// Initialization and events code for the app
bbitRemoteSupport = (function ($) {
    "use strict";

    // public
    var debug_level = 0;
    var maincontainer = null;
    var loading = null;
    var loaded_page = 0;
    var token = null;

	// init function, autoload
	(function init() {
		// load the triggers
		$(document).ready(function(){
			maincontainer = $("#bbit-wrapper");
			loading = maincontainer.find("#bbit-main-loading");

			triggers();
		});
	})();
	
	function remote_register_and_login( that )
	{
		loading.show();
		
		jQuery.post(ajaxurl, {
			'action' 		: 'bbitRemoteSupportRequest',
			'sub_actions'	: 'remote_register_and_login',
			'params'		: that.serialize(),
			'debug_level'	: debug_level
		}, function(response) {
			
			if( response.status == 'valid' ){
				token = response.token;
				$("#bbit-token").val(token);
				$("#bbit-boxid-login").fadeOut(100);
				$("#bbit-boxid-register").fadeOut(100);
				
				var box_info_message = $("#bbit-boxid-logininfo .bbit-message");
				box_info_message.removeClass("bbit-info");
				box_info_message.addClass("bbit-success");
				
				box_info_message.html("You have successfully login into http://bbit.vn . Now you can open a ticket for our Bbit support team.");
				
				$("#bbit-boxid-ticket").fadeIn(100);
			}else{
				var status_block = that.find(".bbit-message");
				status_block.html( "<strong>" + ( response.error_code ) + ": </strong>" + response.msg );
				
				status_block.fadeIn('fast'); 
			}
			
			loading.hide();
		}, 'json'); 
	}
	
	function remote_login( that )
	{
		loading.show();
		
		jQuery.post(ajaxurl, {
			'action' 		: 'bbitRemoteSupportRequest',
			'sub_actions'	: 'remote_login',
			'params'		: that.serialize(),
			'debug_level'	: debug_level
		}, function(response) {
			
			if( response.status == 'valid' ){
				token = response.token;
				$("#bbit-token").val(token);
				$("#bbit-boxid-login").fadeOut(100);
				$("#bbit-boxid-register").fadeOut(100);
				
				var box_info_message = $("#bbit-boxid-logininfo .bbit-message");
				box_info_message.removeClass("bbit-info");
				box_info_message.addClass("bbit-success");
				
				box_info_message.html("You have successfully login into http://bbit.vn . Now you can open a ticket for our Bbit support team.");
				
				$("#bbit-boxid-ticket").fadeIn(100);
			}else{
				var status_block = that.find(".bbit-message");
				status_block.html( "<strong>" + ( response.error_code ) + ": </strong>" + response.msg );
				
				status_block.fadeIn('fast'); 
			}
			
			loading.hide();
		}, 'json'); 
	}
	
	function open_ticket( that )
	{
		loading.show();
		
		$("#bbit-wp_password").val( $("#bbit-password").val() );
		$("#bbit-access_key").val( $("#bbit-key").val() );
		
		jQuery.post(ajaxurl, {
			'action' 		: 'bbitRemoteSupportRequest',
			'sub_actions'	: 'open_ticket',
			'params'		: that.serialize(),
			'token'			: $("#bbit-token").val(),
			'debug_level'	: debug_level
		}, function(response) {
			
			if( response.status == 'valid' ){
				that.find(".bbit-message").html( "The ticket has been open. New ticket ID: <strong>" + response.new_ticket_id + "</strong>" );
				that.find(".bbit-message").show();
			}
			 
			loading.hide();
			
		}, 'json'); 
	}
	
	function access_details( that )
	{
		loading.show();
		
		jQuery.post(ajaxurl, {
			'action' 		: 'bbitRemoteSupportRequest',
			'sub_actions'	: 'access_details',
			'params'		: that.serialize(),
			'debug_level'	: debug_level
		}, function(response) {
			
			loading.hide();
		}, 'json'); 
	}
	
	function checkAuth( token )
	{
		var loading = $("#bbit-main-loading");
		loading.show(); 
		
		jQuery.post(ajaxurl, {
			'action' 		: 'bbitRemoteSupportRequest',
			'sub_actions'	: 'check_auth',
			'params'		: {
				'token': token
			},
			'debug_level'	: debug_level
		}, function(response) {
			// if has a valid token
			if( response.status == 'valid' ){
				$("#bbit-boxid-ticket").show();
				$("#bbit-boxid-logininfo").hide();
			}
			
			// show the auth box
			else{
				$("#bbit-boxid-ticket").hide();
				$("#bbit-boxid-logininfo .bbit-message").html( 'In order to contact Bbit support team you need to login into bbit.vn' );
				$("#bbit-boxid-login").show();
				$("#bbit-boxid-register").show();
			}
			loading.hide();
		}, 'json'); 
	}

	function triggers()
	{
		maincontainer.on('submit', '#bbit-form-login', function(e){
			e.preventDefault();

			remote_login( $(this) );
		});
		
		maincontainer.on('submit', '#bbit-form-register', function(e){
			e.preventDefault();

			remote_register_and_login( $(this) );
		});
		
		maincontainer.on('submit', '#bbit_access_details', function(e){
			e.preventDefault();

			access_details( $(this) );
		});
		
		maincontainer.on('change', '#bbit-create_wp_credential', function(e){
			e.preventDefault();

			var that = $(this);
			
			if( that.val() == 'yes' ){
				$(".bbit-wp-credential").show();
			}else{
				$(".bbit-wp-credential").hide();
			}
		});
		
		maincontainer.on('change', '#bbit-allow_file_remote', function(e){
			e.preventDefault();

			var that = $(this);
			
			if( that.val() == 'yes' ){
				$(".bbit-file-access-credential").show();
			}else{
				$(".bbit-file-access-credential").hide();
			}
		});
		
		maincontainer.on('submit', '#bbit_add_ticket', function(e){
			e.preventDefault();

			open_ticket( $(this) );
		});
	}

	// external usage
	return {
		'checkAuth': checkAuth,
		'token' : token
    }
})(jQuery);
