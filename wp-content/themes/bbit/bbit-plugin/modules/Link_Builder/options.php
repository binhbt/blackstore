<?php
/**
 * module return as json_encode
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
			'Link_Builder' => array(
				'title' 	=> __('Link Builder', $bbit->localizationName),
				'icon' 		=> '{plugin_folder_uri}assets/menu_icon.png',
				'size' 		=> 'grid_4', // grid_1|grid_2|grid_3|grid_4
				'header' 	=> true, // true|false
				'toggler' 	=> false, // true|false
				'buttons' 	=> true, // true|false
				'style' 	=> 'panel', // panel|panel-widget

				// create the box elements array
				'elements'	=> array(
					array(
						'type' 		=> 'message',
						
						'html' 		=> __('
							<h2>Link Builder</h2>
							<ul>
								<li>Link Builder can automatically link chosen phrases in your posts and pages. You can include comments also.</li>
							</ul>', $bbit->localizationName)
					),
					
					/*'max_replacements' => array(
						'type' 		=> 'select',
						'std' 		=> '10',
						'size' 		=> 'large',
						'force_width'=> '120',
						'title' 	=> __('Max replacements:', $bbit->localizationName),
						'desc' 		=> __('Default maximum allowed replacement of phrase in content. If > 10 you\'re content is penalized.', $bbit->localizationName),
						'options'	=> $bbit->doRange( range(1, 10, 1) )
					),*/
						
					'case_sensitive' => array(
						'type' 		=> 'select',
						'std' 		=> 'no',
						'size' 		=> 'large',
						'force_width'=> '120',
						'title' 	=> __('Use case sensitive:', $bbit->localizationName),
						'desc' 		=> __('If you choose YES, the phrase will be searched as case sensitive, otherwise the default is case insensitive.', $bbit->localizationName),
						'options'	=> array(
							'yes' => 'YES',
							'no' => 'NO'
						)
						
					),
					
					'is_comment' => array(
						'type' 		=> 'select',
						'std' 		=> 'no',
						'size' 		=> 'large',
						'force_width'=> '120',
						'title' 	=> __('Replace in comments:', $bbit->localizationName),
						'desc' 		=> __('Replace phrase in comments also.', $bbit->localizationName),
						'options'	=> array(
							'yes' => 'YES',
							'no' => 'NO'
						)
					)
				)
			)
		)
	)
);