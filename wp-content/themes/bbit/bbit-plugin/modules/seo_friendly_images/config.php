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
		'seo_friendly_images' => array(
			'version' => '1.0',
			'menu' => array(
				'order' => 12,
				'title' => __('SEO Friendly Images', $bbit->localizationName)
				,'icon' => 'assets/menu_icon.png'
			),
			'in_dashboard' => array(
				'icon' 	=> 'assets/32.png',
				'url'	=> admin_url("admin.php?page=bbit#seo_friendly_images")
			),
			'description' => __("Tự động thêm các thẻ title và alt vào những hình ảnh mà bạn chưa thiết lập chúng, rất tốt trong SEO hình ảnh ", $bbit->localizationName),
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
				'tipsy'
			),
			'css' => array(
				'admin'
			)
		)
	)
 );