<?php
/**
* Config file, return as json_encode
* http://bbit.vn
* =======================
*
* @author		Pham Quang Bao
* @version		1.0
*/
echo json_encode(array(
    'depedencies' => array(
        'version' => '1.0',
        'menu' => array(
            'order' => 1,
            'title' => 'Plugin Depedencies'
            ,'icon' => 'assets/menu_icon.png'
        ),
        'description' => "Plugin Depedencies",
        'module_init' => 'init.php',
        'load_in' => array(
			'backend' => array(
				'admin-ajax.php'
			),
			'frontend' => false
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
));