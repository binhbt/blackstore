<?php
/**
 * Dummy module return as json_encode
 * http://bbit.vn
 * @author		Pham Quang Bao
 * @version		1.0
 */
global $bbit;
echo json_encode(
	array(
		$tryed_module['db_alias'] => array(
			/* define the form_messages box */
			'setup_box' => array(
				'title' 	=> __('Install plugin settings', $bbit->localizationName),
				'icon' 		=> '{plugin_folder_uri}assets/menu_icon.png',
				'size' 		=> 'grid_4', // grid_1|grid_2|grid_3|grid_4
				'header' 	=> true, // true|false
				'toggler' 	=> false, // true|false
				'buttons' 	=> array(
					'install_btn' => array(
						'type' => 'submit',
						'value' => __('Install settings', $bbit->localizationName),
						'color' => 'blue',
						'action' => 'bbit-installDefaultOptions',
					)
				), // true|false|array
				'style' 	=> 'panel', // panel|panel-widget
				
				// create the box elements array
				'elements'	=> array(
					'install_box' => array(
						'type' 		=> 'textarea',
						'std' 		=> file_get_contents( $tryed_module["folder_path"] . 'default-setup.json' ),
						'size' 		=> 'large',
						'cols' 		=> '130',
						'title' 	=> __('Paste settings here', $bbit->localizationName),
						'desc' 		=> __('Default settings configuration loaded here.', $bbit->localizationName),
					)
				)
			)
			
			/* define the form_messages box */
			, 'import_seo_other_plugins' => array(
				'title' 	=> __('Import settings từ SEO plugins khác', $bbit->localizationName),
				'icon' 		=> '{plugin_folder_uri}assets/menu_icon.png',
				'size' 		=> 'grid_4', // grid_1|grid_2|grid_3|grid_4
				'header' 	=> true, // true|false
				'toggler' 	=> false, // true|false
				'buttons' 	=> array(
					'install_btn' => array(
						'type' => 'submit',
						'value' => __('Import SEO', $bbit->localizationName),
						'color' => 'green',
						'action' => 'bbit-ImportSEO',
					)
				), // true|false|array
				'style' 	=> 'panel', // panel|panel-widget
				
				// create the box elements array
				'elements'	=> array(
					'from' => array(
						'type' 		=> 'select',
						'std' 		=> 'yoast',
						'size' 		=> 'normal',
						'force_width' => '190',
						'title' 	=> __('Import từ', $bbit->localizationName),
						'desc' 		=> __('Chọn plugin bạn muốn import data', $bbit->localizationName),
						'options'	=> array(
							'Yoast WordPress SEO' 				=> 'Yoast WordPress SEO',
							'SEO Ultimate' 						=> 'SEO Ultimate',
							'All-in-One SEO Pack - old version' => 'All-in-One SEO Pack - old version',
							'All-in-One SEO Pack' 				=> 'All-in-One SEO Pack',
							'WooThemes SEO Framework' 			=> 'WooThemes SEO Framework'
						)
					)
				)
			)
			
			/* define the form_messages box */
			, 'backup_box' => array(
				'title' 	=> __('Sao lưu Plugin Setting', $bbit->localizationName),
				'icon' 		=> '{plugin_folder_uri}assets/menu_icon.png',
				'size' 		=> 'grid_4', // grid_1|grid_2|grid_3|grid_4
				'header' 	=> true, // true|false
				'toggler' 	=> false, // true|false
				'buttons' 	=> false, // true|false
				'style' 	=> 'panel', // panel|panel-widget
				
				// create the box elements array
				'elements'	=> array(
					'backup_box' => array(
						'type' 		=> 'textarea',
						'std' 		=> $this->getAllSettings('json'),
						'size' 		=> 'large',
						'cols' 		=> '130',
						'title' 	=> __('Thiết lập hiện tại ', $bbit->localizationName),
						'desc' 		=> __('Copy / Paste nếu bạn muốn backup toàn bộ giữ liệu của mình', $bbit->localizationName)
					)
				)
			)
			
		)
	)
);