<?php

global $bbit;

require($bbit->cfg['paths']['plugin_dir_path'] . 'modules/rich_snippets/' . 'lists.inc.php');

echo json_encode(
	array(
		array(

			/* event shortcode */
			'bbit_rs_event' => array(
				'title' 	=> __('Insert Event Shortcode', $bbit->localizationName),
				'icon' 		=> '{plugin_folder_uri}assets/menu_icon.png',
				'size' 		=> 'grid_4', // grid_1|grid_2|grid_3|grid_4
				'header' 	=> true, // true|false
				'toggler' 	=> false, // true|false
				'buttons' 	=> false, // true|false
				'style' 	=> 'panel', // panel|panel-widget
				
				'exclude_empty_fields'	=> true,
				'shortcode'	=> '[bbit_rs_event {atts}]',

				// create the box elements array
				'elements'	=> array(
				
					'eventtype' => array(
						'type' 		=> 'select',
						'std' 		=> 'Event',
						'size' 		=> 'large',
						'force_width'=> '200',
						'title' 	=> __('Event Type:', $bbit->localizationName),
						'desc' 		=> 'select event type',
						'options'	=> $bbit_event_type
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
						'title' 	=> 'Event Image',
						'value' 	=> 'Upload image',
						'thumbSize' => array(
							'w' => '100',
							'h' => '100',
							'zc' => '2',
						),
						'desc' 		=> 'select event image'
					)
					,'description' 	=> array(
						'type' 		=> 'textarea',
						'std' 		=> '',
						'size' 		=> 'large',
						'force_width'=> '400',
						'title' 	=> __('Description:', $bbit->localizationName),
						'desc' 		=> __('enter description', $bbit->localizationName)
					)
					,'startdate' => array(
						'type' 		=> 'date',
						'std' 		=> '',
						'size' 		=> 'large',
						'force_width'=> '120',
						'title' 	=> __('Start Date:', $bbit->localizationName),
						'desc' 		=> __('enter start date', $bbit->localizationName)
					)
					,'starttime' => array(
						'type' 		=> 'time',
						'std' 		=> '',
						'size' 		=> 'large',
						'force_width'=> '120',
						'title' 	=> __('Start Time:', $bbit->localizationName),
						'desc' 		=> __('enter start time', $bbit->localizationName),
						
						'ampm'				=> true
					)
					,'enddate' => array(
						'type' 		=> 'date',
						'std' 		=> '',
						'size' 		=> 'large',
						'force_width'=> '120',
						'title' 	=> __('End Date:', $bbit->localizationName),
						'desc' 		=> __('enter end date', $bbit->localizationName)
					)
					,'duration' => array(
						'type' 		=> 'time',
						'std' 		=> '',
						'size' 		=> 'large',
						'force_width'=> '120',
						'title' 	=> __('Duration:', $bbit->localizationName),
						'desc' 		=> __('enter duration', $bbit->localizationName)
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

				)
			) // end shortcode
			
		)
	)
);

?>