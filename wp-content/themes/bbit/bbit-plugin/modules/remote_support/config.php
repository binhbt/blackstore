<?php
/**
 * Config file, return as json_encode
 * http://bbit.vn
 * @author		Pham Quang Bao
 * @version		1.0
 */
echo json_encode(
	array(
		'remote_support' => array(
			'version' => '1.0',
			'menu' => array(
				'order' => 4,
				'show_in_menu' => false,
				'title' => 'Hỗ Trợ',
				'icon' => 'assets/16_support.png'
			),
			'in_dashboard' => array(
				'icon' 	=> 'assets/support.png',
				'url'	=> admin_url("admin.php?page=bbit_remote_support")
			),
			'description' => __("Sử dụng chức năng này để nhận được sự giúp đỡ trực tiếp từ Bbit khi bạn gặp sự cố với Bione", $bbit->localizationName),
			'module_init' => 'init.php',
			'load_in' => array(
				'backend' => array(
					'admin.php?page=bbit_remote_support',
					'admin-ajax.php'
				),
				'frontend' => false
			),
		)
	)
);