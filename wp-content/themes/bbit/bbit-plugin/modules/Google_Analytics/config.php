<?php
/**
 * Social_Stats Config file, return as json_encode
 * http://bbit.vn
 *
 * @author		Pham Quang Bao
 * @version		1.0
 */
global $bbit;
 echo json_encode(
	array(
		'Google_Analytics' => array(
			'version' => '1.0',
			'menu' => array(
				'order' => 11,
				'title' => __('Google Analytics', $bbit->localizationName)
				,'icon' => 'assets/menu_icon.png'
			),
			'in_dashboard' => array(
				'icon' 	=> 'assets/32.png',
				'url'	=> admin_url('admin.php?page=' . $bbit->alias . "_Google_Analytics")
			),
			'description' => "Tính năng tự động lấy dữ liệu từ Google Analytics và biến nó thành một bảng điều khiển dễ hiểu giúp bạn theo dõi số liệu dễ dàng.",
			'module_init' => 'init.php',
			'load_in' => array(
				'backend' => array(
					'admin.php?page=bbit_Google_Analytics',
					'admin-ajax.php'
				),
				'frontend' => true
			),
			'javascript' => array(
				'admin',
				'hashchange',
				'tipsy',
				'jquery-ui-core',
				'jquery-ui-datepicker',
				'percentageloader-0.1',
				'flot-2.0',
				'flot-tooltip',
				'flot-stack',
				'flot-pie',
				'flot-time',
				'flot-resize'
			),
			'css' => array(
				'admin'
			),
			'errors' => array(
				1 => __('
					You configured Google Analytics Service incorrectly. See 
					' . ( $bbit->convert_to_button ( array(
						'color' => 'white_blue bbit-show-docs-shortcut',
						'url' => 'javascript: void(0)',
						'title' => 'here'
					) ) ) . ' for more details on fixing it. <br />
					Module Google Analytics verification section: click Verify button and read status 
					' . ( $bbit->convert_to_button ( array(
						'color' => 'white_blue',
						'url' => admin_url( 'admin.php?page=bbit_server_status#sect-google_analytics' ),
						'title' => 'here',
						'target' => '_blank'
					) ) ) . '<br />
					Setup the Google Analytics module 
					' . ( $bbit->convert_to_button ( array(
						'color' => 'white_blue',
						'url' => admin_url( 'admin.php?page=bbit#Google_Analytics' ),
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