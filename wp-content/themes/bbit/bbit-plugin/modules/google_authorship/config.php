<?php
/**
 * Config file, return as json_encode
 * http://bbit.vn
 *
 * @author		Pham Quang Bao
 * @version		1.0
 */
 echo json_encode(
	array(
		'google_authorship' => array(
			'version' => '1.0',
			'menu' => array(
				'order' => 30,
				'title' => __('Google Authorship', $bbit->localizationName)
				,'icon' => 'assets/menu_icon.png'
			),
			'in_dashboard' => array(
				'icon' 	=> 'assets/32.png',
				'url'	=> admin_url('admin.php?page=bbit#google_authorship')
			),
			'description' => __("Google Publisher & Authorship module.", $bbit->localizationName),
			'module_init' => 'init.php',
			'load_in' => array(
				'backend' => array(
					'admin.php?page=bbit_google_authorship',
					'admin-ajax.php',
					'user-edit.php',
					'user-new.php',
					'profile.php'
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