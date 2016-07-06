<?php
/**
 * Config file, return as json_encode
 * http://bbit.vn
 * @author		Pham Quang Bao
 * @version		1.0
 */
 echo json_encode(
	array(
		'setup_backup' => array(
			'version' => '1.0',
			'menu' => array(
				'order' => 31,
				'title' => __('Cài Đặt / Sao Lưu', $bbit->localizationName)
				,'icon' => 'assets/menu_icon.png'
			),
			'description' => __("Cài đặt mặc định cho Bbit Theme, sao lưu dữ liệu", $bbit->localizationName),
			'load_in' => array(
				'frontend' => false
			),
			'load_in' => array(
				'backend' => array(
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