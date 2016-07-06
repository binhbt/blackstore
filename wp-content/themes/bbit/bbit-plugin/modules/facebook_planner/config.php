<?php
/**
 * W3C_HTMLValidator Config file, return as json_encode
 * http://bbit.vn
 * @author		Pham Quang Bao
 * @version		1.0
 */
 echo json_encode(
	array(
		'facebook_planner' => array(
			'version' => '1.0',
			'menu' => array(
				'order' => 96,
				'show_in_menu' => false,
				'title' => __('Facebook Planner', $bbit->localizationName)
				,'icon' => 'assets/menu_icon.png'
			),
			'in_dashboard' => array(
				'icon' 	=> 'assets/32.png',
				'url'	=> admin_url("admin.php?page=bbit#facebook_planner")
			),
			'description' => __('Cho phép bạn đăng bài viết lên Facebook tự động', $bbit->localizationName),
			'module_init' => 'init.php',
			'load_in' => array(
				'backend' => array(
					'admin.php?page=bbit_facebook_planner',
					'admin-ajax.php',
					'edit.php',
					'post.php',
					'post-new.php'
				),
				'frontend' => false
			),
			'javascript' => array(
				'admin',
				'hashchange',
				'tipsy',
				'jquery-ui-core',
				'jquery-ui-datepicker',
				'jquery-ui-slider',
				'jquery-timepicker'
			),
			'css' => array(
				'admin'
			),
			'errors' => array(
				1 => __('
					You configured Facebook Planner Service incorrectly. See 
					' . ( $bbit->convert_to_button ( array(
						'color' => 'white_blue bbit-show-docs-shortcut',
						'url' => 'javascript: void(0)',
						'title' => 'here'
					) ) ) . ' for more details on fixing it. <br />
					Module Facebook Planner verification section: click Verify button and read status 
					' . ( $bbit->convert_to_button ( array(
						'color' => 'white_blue',
						'url' => admin_url( 'admin.php?page=bbit_server_status#sect-facebook_planner' ),
						'title' => 'here',
						'target' => '_blank'
					) ) ) . '<br />
					Setup the Facebook Planner module 
					' . ( $bbit->convert_to_button ( array(
						'color' => 'white_blue',
						'url' => admin_url( 'admin.php?page=bbit#facebook_planner' ),
						'title' => 'here'
					) ) ) . '
					', $bbit->localizationName),
				2 => __('
					You don\'t have the cURL library installed! Please activate it!
					', $bbit->localizationName)
			)
		)
	)
 );