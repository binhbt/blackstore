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
		'Backlink_Builder' => array(
			'version' => '1.0',
			'menu' => array(
				'order' => 99,
				'show_in_menu' => false,
				'title' => __('Backlink Builder'),
				'icon' => 'assets/menu_icon.png'
			),
			'in_dashboard' => array(
				'icon' 	=> 'assets/32.png',
				'url'	=> admin_url('admin.php?page=' . $bbit->alias . "_Backlink_Builder")
			),
			'description' => __("Tính năng tự động xây dựng backlink trong vài phút, hỗ trợ SEO mạnh mẽ"),
			'module_init' => 'init.php',
			'load_in' => array(
				'backend' => array(
					'admin.php?page=bbit_Backlink_Builder',
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
				'admin',
				'tipsy'
			)
		)
	)
);