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
		'Social_Stats' => array(
			'version' => '1.0',
			'menu' => array(
				'order' => 18,
				'title' => __('Social Stats', $bbit->localizationName)
				,'icon' => 'assets/menu_icon.png'
			),
			'in_dashboard' => array(
				'icon' 	=> 'assets/32.png',
				'url'	=> admin_url('admin.php?page=' . $bbit->alias . "_Social_Stats")
			),
			'description' => __('Giúp thống kê số lượng tương tác mạng xã hội. Thành công trong Marketing Online! ', $bbit->localizationName),
			'module_init' => 'init.php',
			'load_in' => array(
				'backend' => array(
					'admin.php?page=bbit_Social_Stats',
					'admin-ajax.php'
				),
				'frontend' => true
			),
			'javascript' => array(
				'admin',
				'hashchange',
				'tipsy',
				'ajaxupload',
				'jquery-ui-core',
				//'jquery-ui-widget',
				//'jquery-ui-mouse',
				//'jquery-ui-accordion',
				//'jquery-ui-autocomplete',
				//'jquery-ui-slider',
				//'jquery-ui-tabs',
				'jquery-ui-sortable',
				//'jquery-ui-draggable',
				//'jquery-ui-droppable',
				//'jquery-ui-datepicker',
				//'jquery-ui-resize',
				//'jquery-ui-dialog',
				//'jquery-ui-button'
			),
			'css' => array(
				'admin'
			)
		)
	)
 );