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
		'rich_snippets' => array(
			'version' => '1.0',
			'menu' => array(
				'order' => 23,
				'title' => __('Rich Snippets', $bbit->localizationName)
				,'icon' => 'assets/menu_icon.png'
			),
			'description' => __('Rich Snippets - Schema.org', $bbit->localizationName),
			'module_init' => 'init.php',
			'load_in' => array(
				'backend' => array(
					'admin-ajax.php',
					'post.php',
					'post-new.php'
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
				'title'	=> __('Insert Rich Snippets Shortcodes', $bbit->localizationName)
			)
		)
	)
 );