<?php 

function optionsframework_admin_init() 
{
	// Rev up the Options Machine
	global $of_options, $options_machine, $bbit_data, $bbit_details;
	if (!isset($options_machine))
		$options_machine = new Options_Machine($of_options);

	do_action('optionsframework_admin_init_before', array(
			'of_options'		=> $of_options,
			'options_machine'	=> $options_machine,
			'bbit_data'			=> $bbit_data
		));
	
	if (empty($bbit_data['bbit_init'])) { // Let's set the values if the theme's already been active
		of_save_options($options_machine->Defaults);
		of_save_options(date('r'), 'bbit_init');
		$bbit_data = of_get_options();
		$options_machine = new Options_Machine($of_options);
	}

	do_action('optionsframework_admin_init_after', array(
			'of_options'		=> $of_options,
			'options_machine'	=> $options_machine,
			'bbit_data'			=> $bbit_data
		));

}

function optionsframework_add_admin() {
	
    $of_page = add_theme_page( THEMENAME, 'Cài đặt Bione', 'edit_theme_options', 'bbit_framework', 'optionsframework_options_page');

	// Add framework functionaily to the head individually
	add_action("admin_print_scripts-$of_page", 'of_load_only');
	add_action("admin_print_styles-$of_page",'of_style_only');
	
}

function optionsframework_options_page(){
	
	global $options_machine;
	
	include_once( ADMIN_PATH . 'front-end/options.php' );

}

function of_style_only(){
	wp_enqueue_style('admin-style', ADMIN_DIR . 'assets/css/admin-style.css');
	wp_enqueue_style('jquery-ui-custom-admin', ADMIN_DIR .'assets/css/jquery-ui-custom.css');

	if ( !wp_style_is( 'wp-color-picker','registered' ) ) {
		wp_register_style( 'wp-color-picker', ADMIN_DIR . 'assets/css/color-picker.min.css' );
	}
	wp_enqueue_style( 'wp-color-picker' );
	do_action('of_style_only_after');
}	

function of_load_only() 
{
	
	wp_enqueue_script('jquery-ui-core');
	wp_enqueue_script('jquery-ui-sortable');
	wp_enqueue_script('jquery-ui-slider');
	wp_enqueue_script('jquery-input-mask', ADMIN_DIR .'assets/js/jquery.maskedinput-1.2.2.js', array( 'jquery' ));
	wp_enqueue_script('tipsy', ADMIN_DIR .'assets/js/jquery.tipsy.js', array( 'jquery' ));
	wp_enqueue_script('cookie', ADMIN_DIR . 'assets/js/cookie.js', 'jquery');
	wp_enqueue_script('bbit', ADMIN_DIR .'assets/js/bbit.js', array( 'jquery' ));

	if ( !wp_script_is( 'wp-color-picker', 'registered' ) ) {
		wp_register_script( 'iris', ADMIN_DIR .'assets/js/iris.min.js', array( 'jquery-ui-draggable', 'jquery-ui-slider', 'jquery-touch-punch' ), false, 1 );
		wp_register_script( 'wp-color-picker', ADMIN_DIR .'assets/js/color-picker.min.js', array( 'jquery', 'iris' ) );
	}
	wp_enqueue_script( 'wp-color-picker' );
	
	if ( function_exists( 'wp_enqueue_media' ) )
		wp_enqueue_media();

	do_action('of_load_only_after');

}

function of_ajax_callback() 
{
	global $options_machine, $of_options;

	$nonce=$_POST['security'];
	
	if (! wp_verify_nonce($nonce, 'of_ajax_nonce') ) die('-1'); 
			
	$all = of_get_options();
	
	$save_type = $_POST['type'];
	
	if($save_type == 'upload')
	{
		
		$clickedID = $_POST['data']; // Acts as the name
		$filename = $_FILES[$clickedID];
       	$filename['name'] = preg_replace('/[^a-zA-Z0-9._\-]/', '', $filename['name']); 
		
		$override['test_form'] = false;
		$override['action'] = 'wp_handle_upload';    
		$uploaded_file = wp_handle_upload($filename,$override);
		 
			$upload_tracking[] = $clickedID;
		  
			$upload_image = $all;
			
			$upload_image[$clickedID] = $uploaded_file['url'];
			
			of_save_options($upload_image);
		
				
		 if(!empty($uploaded_file['error'])) {echo 'Upload Error: ' . $uploaded_file['error']; }	
		 else { echo $uploaded_file['url']; }
		 
	}
	elseif($save_type == 'image_reset')
	{
			
			$id = $_POST['data']; // Acts as the name
			
			$delete_image = $all; //preserve rest of data
			$delete_image[$id] = ''; //update array key with empty value	 
			of_save_options($delete_image ) ;
	
	}
	elseif($save_type == 'backup_options')
	{
			
		$backup = $all;
		$backup['backup_log'] = date('r');
		
		of_save_options($backup, BACKUPS) ;
			
		die('1'); 
	}
	elseif($save_type == 'restore_options')
	{
			
		$bbit_data = of_get_options(BACKUPS);

		of_save_options($bbit_data);
		
		die('1'); 
	}
	elseif($save_type == 'import_options'){


		$bbit_data = unserialize(base64_decode($_POST['data'])); //100% safe - ignore theme check nag
		of_save_options($bbit_data);

		
		die('1'); 
	}
	elseif ($save_type == 'save')
	{

		wp_parse_str(stripslashes($_POST['data']), $bbit_data);
		unset($bbit_data['security']);
		unset($bbit_data['of_save']);
		of_save_options($bbit_data);
		
		
		die('1');
	}
	elseif ($save_type == 'reset')
	{
		of_save_options($options_machine->Defaults);
		
        die('1'); //options reset
	}

  	die();
}
