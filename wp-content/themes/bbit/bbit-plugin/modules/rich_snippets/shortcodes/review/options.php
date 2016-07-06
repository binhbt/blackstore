<?php

global $bbit;

require($bbit->cfg['paths']['plugin_dir_path'] . 'modules/rich_snippets/' . 'lists.inc.php');

echo json_encode(
	array(
		array(

			/* review shortcode */
			'bbit_rs_review' => array(
				'title' 	=> __('Insert Review Shortcode', $bbit->localizationName),
				'icon' 		=> '{plugin_folder_uri}assets/menu_icon.png',
				'size' 		=> 'grid_4', // grid_1|grid_2|grid_3|grid_4
				'header' 	=> true, // true|false
				'toggler' 	=> false, // true|false
				'buttons' 	=> false, // true|false
				'style' 	=> 'panel', // panel|panel-widget
				
				'exclude_empty_fields'	=> true,
				'shortcode'	=> '[bbit_rs_review {atts}]',

				// create the box elements array
				'elements'	=> array(
				
					'name' 	=> array(
						'type' 		=> 'text',
						'std' 		=> '',
						'size' 		=> 'large',
						'force_width'=> '400',
						'title' 	=> __('Name:', $bbit->localizationName),
						'desc' 		=> __('enter name', $bbit->localizationName)
					)
					,'url' 	=> array(
						'type' 		=> 'text',
						'std' 		=> '',
						'size' 		=> 'large',
						'force_width'=> '400',
						'title' 	=> __('Website URL:', $bbit->localizationName),
						'desc' 		=> __('enter website url', $bbit->localizationName)
					)
					,'image' => array(
						'type' 		=> 'upload_image',
						'size' 		=> 'large',
						'title' 	=> 'Review Image',
						'value' 	=> 'Upload image',
						'thumbSize' => array(
							'w' => '100',
							'h' => '100',
							'zc' => '2',
						),
						'desc' 		=> 'select review image'
					)
					,'description' 	=> array(
						'type' 		=> 'textarea',
						'std' 		=> '',
						'size' 		=> 'large',
						'force_width'=> '400',
						'title' 	=> __('Description:', $bbit->localizationName),
						'desc' 		=> __('enter description', $bbit->localizationName)
					)
					,'best_rating' => array(
						'type' 		=> 'ratestar',
						'std' 		=> '',
						'size' 		=> 'large',
						'force_width'=> '120',
						'title' 	=> __('Best Rating:', $bbit->localizationName),
						'desc' 		=> __('select best rating', $bbit->localizationName),
						'nbstars'	=> 5
					)
					,'worst_rating' => array(
						'type' 		=> 'ratestar',
						'std' 		=> '',
						'size' 		=> 'large',
						'force_width'=> '120',
						'title' 	=> __('Worst Rating:', $bbit->localizationName),
						'desc' 		=> __('select worst rating', $bbit->localizationName),
						'nbstars'	=> 5
					)
					,'current_rating' => array(
						'type' 		=> 'ratestar',
						'std' 		=> '',
						'size' 		=> 'large',
						'force_width'=> '120',
						'title' 	=> __('Current Rating:', $bbit->localizationName),
						'desc' 		=> __('select current rating', $bbit->localizationName),
						'nbstars'	=> 5
					)
					,'item_name' 	=> array(
						'type' 		=> 'text',
						'std' 		=> '',
						'size' 		=> 'large',
						'force_width'=> '400',
						'title' 	=> __('Item Name:', $bbit->localizationName),
						'desc' 		=> __('enter item name', $bbit->localizationName)
					)
					,'review' 	=> array(
						'type' 		=> 'textarea',
						'std' 		=> '',
						'size' 		=> 'large',
						'force_width'=> '400',
						'title' 	=> __('Review:', $bbit->localizationName),
						'desc' 		=> __('enter review body', $bbit->localizationName)
					)
					,'author' 	=> array(
						'type' 		=> 'text',
						'std' 		=> '',
						'size' 		=> 'large',
						'force_width'=> '400',
						'title' 	=> __('Author:', $bbit->localizationName),
						'desc' 		=> __('enter author', $bbit->localizationName)
					)
					,'pubdate' => array(
						'type' 		=> 'date',
						'std' 		=> '',
						'size' 		=> 'large',
						'force_width'=> '120',
						'title' 	=> __('Published Date:', $bbit->localizationName),
						'desc' 		=> __('enter published date', $bbit->localizationName)
					)

				)
			) // end shortcode
			
		)
	)
);

?>