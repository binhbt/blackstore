<?php
/**
 * Config file, return as json_encode
 * http://bbit.vn
 * @author		Pham Quang Bao
 * @version		1.0
 */
echo json_encode(
	array(
		'frontend' => array(
			'version' => '1.0',
			'menu' => array(
				'show_in_menu' => false,
				'order' => 1,
				'title' => __('Frontend', $bbit->localizationName)
				,'icon' => 'assets/menu_icon.png'
			),
			'description' => __("", $bbit->localizationName),
			'module_init' => 'init.php',
			'load_in' => array(
				'backend'	=> false,
				'frontend' 	=> true
			),
		)
	)
 );