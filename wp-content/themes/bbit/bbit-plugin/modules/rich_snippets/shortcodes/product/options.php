<?php

global $bbit;

require($bbit->cfg['paths']['plugin_dir_path'] . 'modules/rich_snippets/' . 'lists.inc.php');

echo json_encode(
	array(
		array(

			/* product shortcode */
			'bbit_rs_product' => array(
				'title' 	=> __('Insert Product Shortcode', $bbit->localizationName),
				'icon' 		=> '{plugin_folder_uri}assets/menu_icon.png',
				'size' 		=> 'grid_4', // grid_1|grid_2|grid_3|grid_4
				'header' 	=> true, // true|false
				'toggler' 	=> false, // true|false
				'buttons' 	=> false, // true|false
				'style' 	=> 'panel', // panel|panel-widget
				
				'exclude_empty_fields'	=> true,
				'shortcode'	=> '[bbit_rs_product {atts}]',

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
						'title' 	=> 'Product Image',
						'value' 	=> 'Upload image',
						'thumbSize' => array(
							'w' => '100',
							'h' => '100',
							'zc' => '2',
						),
						'desc' 		=> 'select product image'
					)
					,'description' 	=> array(
						'type' 		=> 'textarea',
						'std' 		=> '',
						'size' 		=> 'large',
						'force_width'=> '400',
						'title' 	=> __('Description:', $bbit->localizationName),
						'desc' 		=> __('enter description', $bbit->localizationName)
					)
					,'brand' 	=> array(
						'type' 		=> 'text',
						'std' 		=> '',
						'size' 		=> 'large',
						'force_width'=> '400',
						'title' 	=> __('Brand:', $bbit->localizationName),
						'desc' 		=> __('enter brand', $bbit->localizationName)
					)
					,'manufacturer' 	=> array(
						'type' 		=> 'text',
						'std' 		=> '',
						'size' 		=> 'large',
						'force_width'=> '400',
						'title' 	=> __('Manufacturer:', $bbit->localizationName),
						'desc' 		=> __('enter manufacturer', $bbit->localizationName)
					)
					,'model' 	=> array(
						'type' 		=> 'text',
						'std' 		=> '',
						'size' 		=> 'large',
						'force_width'=> '400',
						'title' 	=> __('Model:', $bbit->localizationName),
						'desc' 		=> __('enter model', $bbit->localizationName)
					)
					,'prod_id' 	=> array(
						'type' 		=> 'text',
						'std' 		=> '',
						'size' 		=> 'large',
						'force_width'=> '400',
						'title' 	=> __('Product ID:', $bbit->localizationName),
						'desc' 		=> __('enter product id', $bbit->localizationName)
					)
					,'price' 	=> array(
						'type' 		=> 'text',
						'std' 		=> '',
						'size' 		=> 'large',
						'force_width'=> '400',
						'title' 	=> __('Price:', $bbit->localizationName),
						'desc' 		=> __('enter price', $bbit->localizationName)
					)
					,'currency' 	=> array(
						'type' 		=> 'text',
						'std' 		=> '',
						'size' 		=> 'large',
						'force_width'=> '400',
						'title' 	=> __('Currency:', $bbit->localizationName),
						'desc' 		=> __('ex: USD, CAD, GBP (full list is on <a href="http://en.wikipedia.org/wiki/ISO_4217" target="_blank">Wikipedia</a>', $bbit->localizationName)
					)
					,'item_name' 	=> array(
						'type' 		=> 'text',
						'std' 		=> '',
						'size' 		=> 'large',
						'force_width'=> '400',
						'title' 	=> __('Item Name:', $bbit->localizationName),
						'desc' 		=> __('enter item name', $bbit->localizationName)
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
					,'avg_rating' 	=> array(
						'type' 		=> 'text',
						'std' 		=> '',
						'size' 		=> 'large',
						'force_width'=> '400',
						'title' 	=> __('Average Rating:', $bbit->localizationName),
						'desc' 		=> __('The count of total number of ratings.', $bbit->localizationName)
					)
					,'nb_reviews' 	=> array(
						'type' 		=> 'text',
						'std' 		=> '',
						'size' 		=> 'large',
						'force_width'=> '400',
						'title' 	=> __('Number of Reviews:', $bbit->localizationName),
						'desc' 		=> __('The count of total number of reviews.', $bbit->localizationName)
					)
					,'condition' => array(
						'type' 		=> 'select',
						'std' 		=> '',
						'size' 		=> 'large',
						'force_width'=> '200',
						'title' 	=> __('Condition:', $bbit->localizationName),
						'desc' 		=> 'select condition',
						'options'	=> array_merge( array('none' => __('Select condition', $bbit->localizationName)), $bbit_product_condition )
					)
					,'availability' => array(
						'type' 		=> 'select',
						'std' 		=> '',
						'size' 		=> 'large',
						'force_width'=> '200',
						'title' 	=> __('Availability:', $bbit->localizationName),
						'desc' 		=> 'select availability',
						'options'	=> array_merge( array('none' => __('Select availability', $bbit->localizationName)), $bbit_product_availability )
					)

				)
			) // end shortcode
			
		)
	)
);

?>