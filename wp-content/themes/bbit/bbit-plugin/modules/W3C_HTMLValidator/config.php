<?php
/**
 * W3C_HTMLValidator Config file, return as json_encode
 * http://bbit.vn
 * @author		Pham Quang Bao
 * @version		1.0
 */
 echo json_encode(
	array(
		'W3C_HTMLValidator' => array(
			'version' => '1.0',
			'menu' => array(
				'order' => 97,
				'show_in_menu' => false,
				'title' => __('W3C Validator', $bbit->localizationName)
				,'icon' => 'assets/menu_icon.png'
			),
			'in_dashboard' => array(
				'icon' 	=> 'assets/32.png',
				'url'	=> admin_url('admin.php?page=' . $bbit->alias . "_HTMLValidator")
			),
			'description' => __('Kiểm tra lỗi W3C cho từng bài viết, trang...', $bbit->localizationName),
			'module_init' => 'init.php',
			'load_in' => array(
				'backend' => array(
					'admin.php?page=bbit_HTMLValidator',
					'admin-ajax.php'
				),
				'frontend' => false
			),
			'javascript' => array(
				'admin',
				'hashchange',
				'tipsy'
			),
			'css' => array(
				'admin'
			)
		)
	)
 );