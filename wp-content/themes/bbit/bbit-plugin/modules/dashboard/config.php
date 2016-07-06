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
    'dashboard' => array(
        'version' => '1.0',
        'menu' => array(
            'order' => 1,
            'title' => 'Dashboard'
            ,'icon' => 'assets/menu_icon.png'
        ),
        'description' => "Bảng Điều Khiển Bbit - Nơi bạn có thể làm mọi thứ!",
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
			'tipsy',
			//'percentageloader-0.1',
			'flot-2.0',
			'flot-tooltip',
			'flot-stack',
			'flot-pie',
			'flot-time',
			'flot-resize'
		),
		'css' => array(
			'admin'
		)
    )
));