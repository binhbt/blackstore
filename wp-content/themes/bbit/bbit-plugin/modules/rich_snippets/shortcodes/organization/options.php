<?php

global $bbit;

require($bbit->cfg['paths']['plugin_dir_path'] . 'modules/rich_snippets/' . 'lists.inc.php');

echo json_encode(
	array(
		array(

			/* organization shortcode */
			'bbit_rs_organization' => array(
				'title' 	=> __('Insert Organization Shortcode', $bbit->localizationName),
				'icon' 		=> '{plugin_folder_uri}assets/menu_icon.png',
				'size' 		=> 'grid_4', // grid_1|grid_2|grid_3|grid_4
				'header' 	=> true, // true|false
				'toggler' 	=> false, // true|false
				'buttons' 	=> false, // true|false
				'style' 	=> 'panel', // panel|panel-widget
				
				'exclude_empty_fields'	=> true,
				'shortcode'	=> '[bbit_rs_organization {atts}]',

				// create the box elements array
				'elements'	=> array(
				
					'orgtype' => array(
						'type' 		=> 'select',
						'std' 		=> 'Organization',
						'size' 		=> 'large',
						'force_width'=> '200',
						'title' 	=> __('Organization Type:', $bbit->localizationName),
						'desc' 		=> 'select organization type',
						'options'	=> $bbit_organization_type
					)
					,'name' 	=> array(
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
						'title' 	=> 'Organization Image',
						'value' 	=> 'Upload image',
						'thumbSize' => array(
							'w' => '100',
							'h' => '100',
							'zc' => '2',
						),
						'desc' 		=> 'select organization image'
					)
					,'description' 	=> array(
						'type' 		=> 'textarea',
						'std' 		=> '',
						'size' 		=> 'large',
						'force_width'=> '400',
						'title' 	=> __('Description:', $bbit->localizationName),
						'desc' 		=> __('enter description', $bbit->localizationName)
					)
					,'street' 	=> array(
						'type' 		=> 'text',
						'std' 		=> '',
						'size' 		=> 'large',
						'force_width'=> '400',
						'title' 	=> __('Street Address:', $bbit->localizationName),
						'desc' 		=> __('enter street address', $bbit->localizationName)
					)
					,'pobox' 	=> array(
						'type' 		=> 'text',
						'std' 		=> '',
						'size' 		=> 'large',
						'force_width'=> '400',
						'title' 	=> __('P.O. Box:', $bbit->localizationName),
						'desc' 		=> __('enter p.o. box', $bbit->localizationName)
					)
					,'city' 	=> array(
						'type' 		=> 'text',
						'std' 		=> '',
						'size' 		=> 'large',
						'force_width'=> '400',
						'title' 	=> __('City:', $bbit->localizationName),
						'desc' 		=> __('enter city', $bbit->localizationName)
					)
					,'state' 	=> array(
						'type' 		=> 'text',
						'std' 		=> '',
						'size' 		=> 'large',
						'force_width'=> '400',
						'title' 	=> __('State or Region:', $bbit->localizationName),
						'desc' 		=> __('enter state or region', $bbit->localizationName)
					)
					,'postalcode' 	=> array(
						'type' 		=> 'text',
						'std' 		=> '',
						'size' 		=> 'large',
						'force_width'=> '400',
						'title' 	=> __('Postal code or Zipcode:', $bbit->localizationName),
						'desc' 		=> __('enter postal code or zipcode', $bbit->localizationName)
					)
					,'country' => array(
						'type' 		=> 'select',
						'std' 		=> '',
						'size' 		=> 'large',
						'force_width'=> '200',
						'title' 	=> __('Country:', $bbit->localizationName),
						'desc' 		=> 'select country',
						'options'	=> array_merge( array('none' => __('Select country', $bbit->localizationName)), $bbit_countries_list )
					)
					,'map_latitude' 	=> array(
						'type' 		=> 'text',
						'std' 		=> '',
						'size' 		=> 'large',
						'force_width'=> '400',
						'title' 	=> __('Latitude:', $bbit->localizationName),
						'desc' 		=> __('enter latitude', $bbit->localizationName)
					)
					,'map_longitude' 	=> array(
						'type' 		=> 'text',
						'std' 		=> '',
						'size' 		=> 'large',
						'force_width'=> '400',
						'title' 	=> __('Longitude:', $bbit->localizationName),
						'desc' 		=> __('enter longitude', $bbit->localizationName)
					)
					,'email' 	=> array(
						'type' 		=> 'text',
						'std' 		=> '',
						'size' 		=> 'large',
						'force_width'=> '400',
						'title' 	=> __('Email:', $bbit->localizationName),
						'desc' 		=> __('enter email', $bbit->localizationName)
					)
					,'phone' 	=> array(
						'type' 		=> 'text',
						'std' 		=> '',
						'size' 		=> 'large',
						'force_width'=> '400',
						'title' 	=> __('Phone:', $bbit->localizationName),
						'desc' 		=> __('enter phone', $bbit->localizationName)
					)
					,'fax' 	=> array(
						'type' 		=> 'text',
						'std' 		=> '',
						'size' 		=> 'large',
						'force_width'=> '400',
						'title' 	=> __('Fax:', $bbit->localizationName),
						'desc' 		=> __('enter fax', $bbit->localizationName)
					)

				)
			) // end shortcode
			
		)
	)
);

?>