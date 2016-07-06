<?php
/**
 * Local SEO Config file, return as json_encode
 * http://bbit.vn
 *
 * @author		Pham Quang Bao
 * @version		1.0
 */
 echo json_encode(
	array(
		'local_seo' => array(
			'version' => '1.0',
			'menu' => array(
				'order' => 13,
				'title' => __('Local SEO', $bbit->localizationName),
				'icon' => 'assets/menu_icon.png'
			),
			'in_dashboard' => array(
				'icon' 	=> 'assets/32.png',
				'url'	=> admin_url("admin.php?page=bbit#local_seo")
			),
			'description' => __('Local SEO', $bbit->localizationName),
			'module_init' => 'init.php',
	        'load_in' => array(
				'backend' => array(
					'@all'
				),
				'frontend' => true
			),
			'javascript' => array(
				'admin',
				'hashchange',
				'tipsy',
				'ajaxupload',
				'jquery-rateit-js'
			),
			'css' => array(
				'admin'
			),
			'shortcodes_btn' => array(
				'icon' 	=> 'assets/20-icon.png',
				'title'	=> __('Insert Local SEO Shortcodes', $bbit->localizationName)
			)
		)
	)
 );