<?php
/**
 * Config file, return as json_encode
 * http://bbit.vn
 * @author		Pham Quang Bao
 * @version		1.0
 */
 echo json_encode(
	array(
		'on_page_optimization' => array(
			'version' => '1.0',
			'menu' => array(
				'order' => 93,
				'show_in_menu' => false,
				'title' => __('Mass Optimization', $bbit->localizationName),
				'icon' => 'assets/menu_icon.png',
			),
			'in_dashboard' => array(
				'icon' 	=> 'assets/32.png',
				'url'	=> admin_url('admin.php?page=' . $bbit->alias . "_massOptimization")
			),
			'description' => __('Tự động tối ưu tất cả trong 1 click! Chức năng độc quyền bởi Bbit.vn', $bbit->localizationName),
			'module_init' => 'init.php',
			'load_in' => array(
				'backend' => array(
					'admin.php?page=bbit_massOptimization',
					'admin-ajax.php',
					'edit.php',
					'post.php',
					'post-new.php',
					'edit-tags.php'
				),
				'frontend' => false
			),
			'javascript' => array(
				'admin',
				'hashchange',
				'tipsy',
				'ajaxupload',
				'jquery-ui-core',
				'jquery-ui-autocomplete'
			),
			'css' => array(
				'admin'
			)
		)
	)
 );