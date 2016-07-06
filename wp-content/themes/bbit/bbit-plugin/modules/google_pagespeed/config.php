<?php
/**
 * Config file, return as json_encode
 * http://bbit.vn
 * @author		Pham Quang Bao
 * @version		1.0
 */
 echo json_encode(
	array(
		'google_pagespeed' => array(
			'version' => '1.0',
			'menu' => array(
				'order' => 94,
				'show_in_menu' => false,
				'title' => __('PageSpeed Insights', $bbit->localizationName),
				'icon' => 'assets/16_pagespeed.png'
			),
			'in_dashboard' => array(
				'icon' 	=> 'assets/32.png',
				'url'	=> admin_url('admin.php?page=' . $bbit->alias . "_PageSpeedInsights")
			),
			'description' => __('Tối ưu hóa tốc độ với PageSpeed Insights từ Google', $bbit->localizationName),
			'module_init' => 'init.php',
			'load_in' => array(
				'backend' => array(
					'admin.php?page=bbit_PageSpeedInsights',
					'admin-ajax.php'
				),
				'frontend' => false
			),
			'javascript' => array(
				'admin',
				'hashchange',
				'tipsy',
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
					You configured PageSpeed Service incorrectly. See 
					' . ( $bbit->convert_to_button ( array(
						'color' => 'white_blue bbit-show-docs-shortcut',
						'url' => 'javascript: void(0)',
						'title' => 'here'
					) ) ) . ' for more details on fixing it. <br />
					Module Google Pagespeed verification section: click Verify button and read status 
					' . ( $bbit->convert_to_button ( array(
						'color' => 'white_blue',
						'url' => admin_url( 'admin.php?page=bbit_server_status#sect-google_pagespeed' ),
						'title' => 'here',
						'target' => '_blank'
					) ) ) . '<br />
					Setup the PageSpeed module 
					' . ( $bbit->convert_to_button ( array(
						'color' => 'white_blue',
						'url' => admin_url( 'admin.php?page=bbit#google_pagespeed' ),
						'title' => 'here'
					) ) ) . '
					', $bbit->localizationName),
			)
		)
	)
 );