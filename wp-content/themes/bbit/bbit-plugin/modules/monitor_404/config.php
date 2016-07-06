<?php
/**
 * Config file, return as json_encode
 * http://bbit.vn
 * @author		Pham Quang Bao
 * @version		1.0
 */
 echo json_encode(
	array(
		'monitor_404' => array(
			'version' => '1.0',
			'menu' => array(
				'order' => 92,
				'show_in_menu' => false,
				'title' => __('Monitor 404', $bbit->localizationName)
				,'icon' => 'assets/menu_icon.png'
			),
			'in_dashboard' => array(
				'icon' 	=> 'assets/32.png',
				'url'	=> admin_url('admin.php?page=' . $bbit->alias . "_mass404Monitor")
			),
			'description' => __('Kiểm tra lỗi 404', $bbit->localizationName),
			'module_init' => 'init.php',
			'load_in' => array(
				'backend' => array(
					'admin.php?page=bbit_mass404Monitor',
					'admin-ajax.php'
				),
				'frontend' => true
			),
			'javascript' => array(
				'admin',
				'hashchange',
				'tipsy'
			),
			'css' => array(
				'admin'
			),
			'shortcodes_btn' => array(
				'icon' 	=> 'assets/menu_icon.png',
				'title'	=> __('Insert Monitor 404 sh', $bbit->localizationName)
			)
		)
	)
 );