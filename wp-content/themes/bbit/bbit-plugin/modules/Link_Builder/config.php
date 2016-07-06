<?php
/**
 * Social_Stats Config file, return as json_encode
 * http://bbit.vn
 * ======================
 *
 * @author		Pham Quang Bao
 * @version		1.0
 */
 echo json_encode(
	array(
		'Link_Builder' => array(
			'version' => '1.0',
			'menu' => array(
				'order' => 23,
				'title' => __('Link Builder', $bbit->localizationName)
				,'icon' => 'assets/menu_icon.png'
			),
			'in_dashboard' => array(
				'icon' 	=> 'assets/32.png',
				'url'	=> admin_url('admin.php?page=' . $bbit->alias . "_Link_Builder")
			),
			'description' => "Bạn có thể tạo một danh sách từ khóa và URl, và chúng sẽ tự động được xây dựng",
			'module_init' => 'init.php',
			'load_in' => array(
				'backend' => array(
					'admin.php?page=bbit_Link_Builder',
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