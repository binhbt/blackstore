<?php
/**
 * Dummy module return as json_encode
 * http://bbit.vn
 * =======================
 *
 * @author		Pham Quang Bao
 * @version		1.0
 */

global $bbit;

echo json_encode(

	array(

		$tryed_module['db_alias'] => array(

			/* define the form_messages box */

			'module_box' => ($bbit->get_plugin_status() != 'valid_hash' ? array(

					'title' 	=> __('Unlock - Bbit Plugin', $bbit->localizationName),
					'icon' 		=> '{plugin_folder_uri}assets/validation_icon.png',
					'size' 		=> 'grid_4', // grid_1|grid_2|grid_3|grid_4
					'header' 	=> true, // true|false
					'toggler' 	=> false, // true|false
					'buttons' 	=> false, // true|false
					'style' 	=> 'panel', // panel|panel-widget

					// create the box elements array

					'elements'	=> array(

						array(
							'type' 		=> 'message',
							'status' 	=> 'info',
							'html' 		=> __('Truy cập diễn đàn forum.bbit.vn để lấy license', $bbit->localizationName),
						)

						,'productKey' => array(
							'type' 		=> 'text',
							'std' 		=> '',
							'size' 		=> 'small',
							'title' 	=> __('License', $bbit->localizationName),
							'desc' 		=> __('Truy cập diễn đàn forum.bbit.vn để lấy license', $bbit->localizationName)
						)

						,'yourEmail' => array(
							'type' 		=> 'text',
							'std' 		=> get_option('admin_email'),
							'size' 		=> 'small',
							'title' 	=> __('Email của bạn', $bbit->localizationName),
							'desc' 		=> __('Nhận thông báo update qua email này.', $bbit->localizationName)
						)

						

						,'sendActions' => array(

							'type' 		=> 'buttons',

							'options'	=> array(

								array(

									'action' 	=> 'bbit_activate_product',
									'width'		=> '100px',
									'type'		=> 'submit',
									'color'		=> 'green',
									'pos'		=> 'left',
									'value'		=> 'Kích Hoạt'
								)
							)
						)
					)
				) 

				: array(

					'title' 	=> __('Quản Lí Module', $bbit->localizationName),

					'icon' 		=> '{plugin_folder_uri}assets/menu_icon.png',

					'size' 		=> 'grid_4', // grid_1|grid_2|grid_3|grid_4

					'header' 	=> true, // true|false

					'toggler' 	=> false, // true|false

					'buttons' 	=> false, // true|false

					'style' 	=> 'panel', // panel|panel-widget

					

					// create the box elements array

					'elements'	=> array(

						array(

							'type' 		=> 'app',

							'path' 		=> '{plugin_folder_path}lists.php',

						)

					)

				)

			)

		)

	)

);