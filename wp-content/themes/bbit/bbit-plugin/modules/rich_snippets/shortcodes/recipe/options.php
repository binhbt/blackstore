<?php

global $bbit;

require($bbit->cfg['paths']['plugin_dir_path'] . 'modules/rich_snippets/' . 'lists.inc.php');

echo json_encode(
	array(
		array(

			/* recipe shortcode */
			'bbit_rs_recipe' => array(
				'title' 	=> __('Insert Recipe Shortcode', $bbit->localizationName),
				'icon' 		=> '{plugin_folder_uri}assets/menu_icon.png',
				'size' 		=> 'grid_4', // grid_1|grid_2|grid_3|grid_4
				'header' 	=> true, // true|false
				'toggler' 	=> false, // true|false
				'buttons' 	=> false, // true|false
				'style' 	=> 'panel', // panel|panel-widget
				
				'exclude_empty_fields'	=> true,
				'shortcode'	=> '[bbit_rs_recipe {atts}]',

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
					,'image' => array(
						'type' 		=> 'upload_image',
						'size' 		=> 'large',
						'title' 	=> 'Recipe Image',
						'value' 	=> 'Upload image',
						'thumbSize' => array(
							'w' => '100',
							'h' => '100',
							'zc' => '2',
						),
						'desc' 		=> 'select recipe image'
					)
					,'description' 	=> array(
						'type' 		=> 'textarea',
						'std' 		=> '',
						'size' 		=> 'large',
						'force_width'=> '400',
						'title' 	=> __('Description:', $bbit->localizationName),
						'desc' 		=> __('enter description', $bbit->localizationName)
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
					,'prephours' 	=> array(
						'type' 		=> 'text',
						'std' 		=> '',
						'size' 		=> 'large',
						'force_width'=> '400',
						'title' 	=> __('Preparation hours:', $bbit->localizationName),
						'desc' 		=> __('enter preparation duration - hours', $bbit->localizationName)
					)
					,'prepmins' 	=> array(
						'type' 		=> 'text',
						'std' 		=> '',
						'size' 		=> 'large',
						'force_width'=> '400',
						'title' 	=> __('Preparation mins:', $bbit->localizationName),
						'desc' 		=> __('enter preparation duration - mins', $bbit->localizationName)
					)
					,'cookhours' 	=> array(
						'type' 		=> 'text',
						'std' 		=> '',
						'size' 		=> 'large',
						'force_width'=> '400',
						'title' 	=> __('Cook hours:', $bbit->localizationName),
						'desc' 		=> __('enter cook duration - hours', $bbit->localizationName)
					)
					,'cookmins' 	=> array(
						'type' 		=> 'text',
						'std' 		=> '',
						'size' 		=> 'large',
						'force_width'=> '400',
						'title' 	=> __('Cook mins:', $bbit->localizationName),
						'desc' 		=> __('enter cook duration - mins', $bbit->localizationName)
					)
					
					,'yield' 	=> array(
						'type' 		=> 'text',
						'std' 		=> '',
						'size' 		=> 'large',
						'force_width'=> '400',
						'title' 	=> __('Recipe Yield:', $bbit->localizationName),
						'desc' 		=> __('The quantity produced by the recipe (for example, number of people served, number of servings, etc)', $bbit->localizationName)
					)
					,'calories' 	=> array(
						'type' 		=> 'text',
						'std' 		=> '',
						'size' 		=> 'large',
						'force_width'=> '400',
						'title' 	=> __('Calories:', $bbit->localizationName),
						'desc' 		=> __('The number of calories', $bbit->localizationName)
					)
					,'fatcount' 	=> array(
						'type' 		=> 'text',
						'std' 		=> '',
						'size' 		=> 'large',
						'force_width'=> '400',
						'title' 	=> __('Fat count:', $bbit->localizationName),
						'desc' 		=> __('The number of grams of fat', $bbit->localizationName)
					)
					,'sugarcount' 	=> array(
						'type' 		=> 'text',
						'std' 		=> '',
						'size' 		=> 'large',
						'force_width'=> '400',
						'title' 	=> __('Sugar count:', $bbit->localizationName),
						'desc' 		=> __('The number of grams of sugar', $bbit->localizationName)
					)
					,'saltcount' 	=> array(
						'type' 		=> 'text',
						'std' 		=> '',
						'size' 		=> 'large',
						'force_width'=> '400',
						'title' 	=> __('Salt count:', $bbit->localizationName),
						'desc' 		=> __('The number of milligrams of sodium', $bbit->localizationName)
					)
					,'instructions' 	=> array(
						'type' 		=> 'textarea',
						'std' 		=> '',
						'size' 		=> 'large',
						'force_width'=> '400',
						'title' 	=> __('Instructions:', $bbit->localizationName),
						'desc' 		=> __('The steps to make the dish', $bbit->localizationName)
					)

				)
			) // end shortcode
			
		)
	)
);

?>