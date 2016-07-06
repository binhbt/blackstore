<?php
/**
 * Smushit Config file, return as json_encode
 * http://bbit.vn
 *
 * @author		Pham Quang Bao
 * @version		1.0
 */
 echo json_encode(
	array(
		'smushit' => array(
			'version' => '1.0',
			'menu' => array(
				'order' => 13,
				'title' => __('Smushit', $bbit->localizationName)
				,'icon' => 'assets/menu_icon.png'
			),
			'in_dashboard' => array(
				'icon' 	=> 'assets/32.png',
				'url'	=> admin_url('admin.php?page=' . $bbit->alias . "_smushit")
			),
			'description' => __('Nén hình ảnh không làm giảm chất lượng thông qua công cụ Smush', $bbit->localizationName),
			'module_init' => 'init.php',
			'load_in' => array(
				'backend' => array(
					'admin.php?page=bbit_smushit',
					'admin-ajax.php',
					//'upload.php',
					'media-new.php'
				),
				'frontend' => false
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