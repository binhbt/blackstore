<?php
/**
 * Title & Meta Format Config file, return as json_encode
 * http://bbit.vn
 *
 * @author		Pham Quang Bao
 * @version		1.0
 */
 echo json_encode(
	array(
		'title_meta_format' => array(
			'version' => '1.0',
			'menu' => array(
				'order' => 10,
				'title' => __('Title & Meta Format', $bbit->localizationName)
				,'icon' => 'assets/menu_icon.png'
			),
			'in_dashboard' => array(
				'icon' 	=> 'assets/32.png',
				'url'	=> admin_url("admin.php?page=bbit#title_meta_format")
			),
			'description' => "Tiêu đề, mô tả là không thể thiếu trong SEO",
			'module_init' => 'init.php',
			'load_in' => array(
				'backend' => array(
					'admin-ajax.php'
				),
				'frontend' => true
			),
			'javascript' => array(
				'admin',
				'hashchange',
				'tipsy',
				'ajaxupload'
			),
			'css' => array(
				'admin'
			)
		)
	)
 );