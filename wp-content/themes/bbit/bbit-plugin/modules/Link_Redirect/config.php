<?php
/**
 * Social_Stats Config file, return as json_encode
 * http://bbit.vn
 *
 * @author		Pham Quang Bao
 * @version		1.0
 */
 echo json_encode(
	array(
		'Link_Redirect' => array(
			'version' => '1.0',
			'menu' => array(
				'order' => 98,
				'show_in_menu' => false,
				'title' => __('301 Link Redirect', $bbit->localizationName)
				,'icon' => 'assets/menu_icon.png'
			),
			'in_dashboard' => array(
				'icon' 	=> 'assets/32.png',
				'url'	=> admin_url('admin.php?page=' . $bbit->alias . "_Link_Redirect")
			),
			'description' => "Rất có ích trong việc chuyển hướng liên kết, khắc phục lỗi 404 khi thay url hoặc xóa bài viết.
",
			'module_init' => 'init.php',
	        'load_in' => array(
				'backend' => array(
					'admin.php?page=bbit_Link_Redirect',
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
			)
		)
	)
 );