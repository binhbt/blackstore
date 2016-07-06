<?php
/**
 * Config file, return as json_encode
 * http://bbit.vn
 * @author		Pham Quang Bao
 * @version		1.0
 */
 global $bbit;
echo json_encode(
	array(
		'server_status' => array(
			'version' => '1.0',
			'menu' => array(
				'order' => 4,
				'show_in_menu' => false,
				'title' => __('Trạng Thái Máy Chủ', $bbit->localizationName),
				'icon' => 'assets/16_serversts.png'
			),
			'in_dashboard' => array(
				'icon' 	=> 'assets/32_serverstatus.png',
				'url'	=> admin_url("admin.php?page=bbit_server_status")
			),
			'description' => __('Kiểm tra trạng thái máy chủ, cấu hình máy chủ, wordpress', $bbit->localizationName),
			'module_init' => 'init.php',
			'load_in' => array(
				'backend' => array(
					'admin.php?page=bbit_server_status',
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