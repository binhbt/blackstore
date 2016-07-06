<?php

if ( !function_exists('bbit_getLocationsList') ) { function bbit_getLocationsList() {
	global $bbit;
	global $wpdb;

	ob_start();
	
	$sqlClause = '';

	$sql = "SELECT a.ID
	            FROM " . $wpdb->prefix . "posts as a
	            LEFT JOIN " . $wpdb->prefix . "postmeta as b
	            ON b.post_id = a.ID
	            WHERE 1=1 " . $sqlClause . " AND a.post_status = 'publish' AND a.post_password = ''
	            AND a.post_type = 'bbit_locations'
	            AND (b.meta_key = 'bbit_locations_meta' AND !ISNULL(b.meta_value) AND b.meta_value != '')
	            ORDER BY a.post_title ASC
	            LIMIT 1000;";

	$res = $wpdb->get_col( $sql );
?>
<div class="bbit-form-row">
	<label>Select location:</label>
	<div class="bbit-form-item large">
	<span class="formNote">&nbsp;</span>

	<select id="bbit-location-id" name="location_id" style="width:120px;">
		<option value="all">All locations</option>
	<?php
	foreach ($res as $key => $value) {
		$val = '';
		echo '<option value="' . ( $value ) . '" ' . ( $val == $value ? 'selected="true"' : '' ) . '>' . ( $value ) . '</option>';
	}
	?>
	</select>&nbsp;&nbsp;&nbsp;&nbsp;

	</div>
</div>
<?php
	$output = ob_get_contents();
	ob_end_clean();
	return $output;
} }
global $bbit;
echo json_encode(
	array(
		array(

			/* business shortcode */
			// [bbit_business id=all show_name=true show_desc=true show_img_logo=true show_img_building=true]
			'bbit_business' => array(
				'title' 	=> __('Insert Business Shortcode', $bbit->localizationName),
				'icon' 		=> '{plugin_folder_uri}assets/menu_icon.png',
				'size' 		=> 'grid_4', // grid_1|grid_2|grid_3|grid_4
				'header' 	=> true, // true|false
				'toggler' 	=> false, // true|false
				'buttons' 	=> false, // true|false
				'style' 	=> 'panel', // panel|panel-widget
				
				'shortcode'	=> '[bbit_business id={location_id} show_name={show_name} show_desc={show_desc} show_img_logo={show_img_logo} show_img_building={show_img_building}]',

				// create the box elements array
				'elements'	=> array(
				
					'location_id' => array(
						'type' 		=> 'html',
						'html' 		=> bbit_getLocationsList()
					),

					'show_name' => array(
						'type' 		=> 'select',
						'std' 		=> 'no',
						'size' 		=> 'large',
						'force_width'=> '120',
						'title' 	=> __('Show Name:', $bbit->localizationName),
						'desc' 		=> __('show business name', $bbit->localizationName),
						'options'	=> array(
							'true' 		=> __('YES', $bbit->localizationName),
							'false' 	=> __('NO', $bbit->localizationName)
						)
					),
					'show_desc' => array(
						'type' 		=> 'select',
						'std' 		=> 'no',
						'size' 		=> 'large',
						'force_width'=> '120',
						'title' 	=> __('Show Description:', $bbit->localizationName),
						'desc' 		=> __('show business description', $bbit->localizationName),
						'options'	=> array(
							'true' 		=> __('YES', $bbit->localizationName),
							'false' 	=> __('NO', $bbit->localizationName)
						)
					),
					'show_img_logo' => array(
						'type' 		=> 'select',
						'std' 		=> 'no',
						'size' 		=> 'large',
						'force_width'=> '120',
						'title' 	=> __('Show Business Logo:', $bbit->localizationName),
						'desc' 		=> __('show business logo image', $bbit->localizationName),
						'options'	=> array(
							'true' 		=> __('YES', $bbit->localizationName),
							'false' 	=> __('NO', $bbit->localizationName)
						)
					),
					'show_img_building' => array(
						'type' 		=> 'select',
						'std' 		=> 'no',
						'size' 		=> 'large',
						'force_width'=> '120',
						'title' 	=> __('Show Business Building:', $bbit->localizationName),
						'desc' 		=> __('show business building image', $bbit->localizationName),
						'options'	=> array(
							'true' 		=> __('YES', $bbit->localizationName),
							'false' 	=> __('NO', $bbit->localizationName)
						)
					)

				)
			) // end shortcode
			
			/* address shortcode */
			// [bbit_address id=all show_street=true show_city=true show_state=true show_zipcode=true show_country=true]
			,'bbit_address' => array(
				'title' 	=> __('Insert Address Shortcode', $bbit->localizationName),
				'icon' 		=> '{plugin_folder_uri}assets/menu_icon.png',
				'size' 		=> 'grid_4', // grid_1|grid_2|grid_3|grid_4
				'header' 	=> true, // true|false
				'toggler' 	=> false, // true|false
				'buttons' 	=> false, // true|false
				'style' 	=> 'panel', // panel|panel-widget
				
				'shortcode'	=> '[bbit_address id={location_id} show_street={show_street} show_city={show_city} show_state={show_state} show_zipcode={show_zipcode} show_country={show_country}]',

				// create the box elements array
				'elements'	=> array(
				
					'location_id' => array(
						'type' 		=> 'html',
						'html' 		=> bbit_getLocationsList()
					),

					'show_street' => array(
						'type' 		=> 'select',
						'std' 		=> 'no',
						'size' 		=> 'large',
						'force_width'=> '120',
						'title' 	=> __('Show Street:', $bbit->localizationName),
						'desc' 		=> __('show street address', $bbit->localizationName),
						'options'	=> array(
							'true' 		=> __('YES', $bbit->localizationName),
							'false' 	=> __('NO', $bbit->localizationName)
						)
					),
					'show_city' => array(
						'type' 		=> 'select',
						'std' 		=> 'no',
						'size' 		=> 'large',
						'force_width'=> '120',
						'title' 	=> __('Show City:', $bbit->localizationName),
						'desc' 		=> __('show address city', $bbit->localizationName),
						'options'	=> array(
							'true' 		=> __('YES', $bbit->localizationName),
							'false' 	=> __('NO', $bbit->localizationName)
						)
					),
					'show_state' => array(
						'type' 		=> 'select',
						'std' 		=> 'no',
						'size' 		=> 'large',
						'force_width'=> '120',
						'title' 	=> __('Show State:', $bbit->localizationName),
						'desc' 		=> __('show address state', $bbit->localizationName),
						'options'	=> array(
							'true' 		=> __('YES', $bbit->localizationName),
							'false' 	=> __('NO', $bbit->localizationName)
						)
					),
					'show_zipcode' => array(
						'type' 		=> 'select',
						'std' 		=> 'no',
						'size' 		=> 'large',
						'force_width'=> '120',
						'title' 	=> __('Show Zipcode:', $bbit->localizationName),
						'desc' 		=> __('show address zipcode', $bbit->localizationName),
						'options'	=> array(
							'true' 		=> __('YES', $bbit->localizationName),
							'false' 	=> __('NO', $bbit->localizationName)
						)
					),
					'show_country' => array(
						'type' 		=> 'select',
						'std' 		=> 'no',
						'size' 		=> 'large',
						'force_width'=> '120',
						'title' 	=> __('Show Country:', $bbit->localizationName),
						'desc' 		=> __('show address country', $bbit->localizationName),
						'options'	=> array(
							'true' 		=> __('YES', $bbit->localizationName),
							'false' 	=> __('NO', $bbit->localizationName)
						)
					)

				)
			) // end shortcode
			
			/* contact shortcode */
			// [bbit_contact id=all show_phone=true show_altphone=true show_fax=true show_email=true]
			,'bbit_contact' => array(
				'title' 	=> __('Insert Contact Shortcode', $bbit->localizationName),
				'icon' 		=> '{plugin_folder_uri}assets/menu_icon.png',
				'size' 		=> 'grid_4', // grid_1|grid_2|grid_3|grid_4
				'header' 	=> true, // true|false
				'toggler' 	=> false, // true|false
				'buttons' 	=> false, // true|false
				'style' 	=> 'panel', // panel|panel-widget
				
				'shortcode'	=> '[bbit_contact id={location_id} show_phone={show_phone} show_altphone={show_altphone} show_fax={show_fax} show_email={show_email}]',

				// create the box elements array
				'elements'	=> array(
				
					'location_id' => array(
						'type' 		=> 'html',
						'html' 		=> bbit_getLocationsList()
					),

					'show_phone' => array(
						'type' 		=> 'select',
						'std' 		=> 'no',
						'size' 		=> 'large',
						'force_width'=> '120',
						'title' 	=> __('Show Phone:', $bbit->localizationName),
						'desc' 		=> __('show phone contact', $bbit->localizationName),
						'options'	=> array(
							'true' 		=> __('YES', $bbit->localizationName),
							'false' 	=> __('NO', $bbit->localizationName)
						)
					),
					'show_altphone' => array(
						'type' 		=> 'select',
						'std' 		=> 'no',
						'size' 		=> 'large',
						'force_width'=> '120',
						'title' 	=> __('Show Alt. Phone:', $bbit->localizationName),
						'desc' 		=> __('show alternative phone contact', $bbit->localizationName),
						'options'	=> array(
							'true' 		=> __('YES', $bbit->localizationName),
							'false' 	=> __('NO', $bbit->localizationName)
						)
					),
					'show_fax' => array(
						'type' 		=> 'select',
						'std' 		=> 'no',
						'size' 		=> 'large',
						'force_width'=> '120',
						'title' 	=> __('Show Fax:', $bbit->localizationName),
						'desc' 		=> __('show fax contact', $bbit->localizationName),
						'options'	=> array(
							'true' 		=> __('YES', $bbit->localizationName),
							'false' 	=> __('NO', $bbit->localizationName)
						)
					),
					'show_email' => array(
						'type' 		=> 'select',
						'std' 		=> 'no',
						'size' 		=> 'large',
						'force_width'=> '120',
						'title' 	=> __('Show Email:', $bbit->localizationName),
						'desc' 		=> __('show email contact', $bbit->localizationName),
						'options'	=> array(
							'true' 		=> __('YES', $bbit->localizationName),
							'false' 	=> __('NO', $bbit->localizationName)
						)
					)

				)
			) // end shortcode
			
			/* payment shortcode */
			// [bbit_payment id=all show_payment=true show_currencies=true show_pricerange=true]
			,'bbit_payment' => array(
				'title' 	=> __('Insert Payment Shortcode', $bbit->localizationName),
				'icon' 		=> '{plugin_folder_uri}assets/menu_icon.png',
				'size' 		=> 'grid_4', // grid_1|grid_2|grid_3|grid_4
				'header' 	=> true, // true|false
				'toggler' 	=> false, // true|false
				'buttons' 	=> false, // true|false
				'style' 	=> 'panel', // panel|panel-widget
				
				'shortcode'	=> '[bbit_payment id={location_id} show_payment={show_payment} show_currencies={show_currencies} show_pricerange={show_pricerange}]',

				// create the box elements array
				'elements'	=> array(
				
					'location_id' => array(
						'type' 		=> 'html',
						'html' 		=> bbit_getLocationsList()
					),

					'show_payment' => array(
						'type' 		=> 'select',
						'std' 		=> 'no',
						'size' 		=> 'large',
						'force_width'=> '120',
						'title' 	=> __('Show Payment:', $bbit->localizationName),
						'desc' 		=> __('show payment', $bbit->localizationName),
						'options'	=> array(
							'true' 		=> __('YES', $bbit->localizationName),
							'false' 	=> __('NO', $bbit->localizationName)
						)
					),
					'show_currencies' => array(
						'type' 		=> 'select',
						'std' 		=> 'no',
						'size' 		=> 'large',
						'force_width'=> '120',
						'title' 	=> __('Show Currencies:', $bbit->localizationName),
						'desc' 		=> __('show currencies', $bbit->localizationName),
						'options'	=> array(
							'true' 		=> __('YES', $bbit->localizationName),
							'false' 	=> __('NO', $bbit->localizationName)
						)
					),
					'show_pricerange' => array(
						'type' 		=> 'select',
						'std' 		=> 'no',
						'size' 		=> 'large',
						'force_width'=> '120',
						'title' 	=> __('Show Price Range:', $bbit->localizationName),
						'desc' 		=> __('show price range', $bbit->localizationName),
						'options'	=> array(
							'true' 		=> __('YES', $bbit->localizationName),
							'false' 	=> __('NO', $bbit->localizationName)
						)
					)

				)
			) // end shortcode
			
			/* opening hours shortcode */
			// [bbit_opening_hours id=all show_head=true]
			,'bbit_opening_hours' => array(
				'title' 	=> __('Insert Opening Hours Shortcode', $bbit->localizationName),
				'icon' 		=> '{plugin_folder_uri}assets/menu_icon.png',
				'size' 		=> 'grid_4', // grid_1|grid_2|grid_3|grid_4
				'header' 	=> true, // true|false
				'toggler' 	=> false, // true|false
				'buttons' 	=> false, // true|false
				'style' 	=> 'panel', // panel|panel-widget
				
				'shortcode'	=> '[bbit_opening_hours id={location_id} show_head={show_head}]',

				// create the box elements array
				'elements'	=> array(
				
					'location_id' => array(
						'type' 		=> 'html',
						'html' 		=> bbit_getLocationsList()
					),

					'show_head' => array(
						'type' 		=> 'select',
						'std' 		=> 'no',
						'size' 		=> 'large',
						'force_width'=> '120',
						'title' 	=> __('Show Heading:', $bbit->localizationName),
						'desc' 		=> __('show heading', $bbit->localizationName),
						'options'	=> array(
							'true' 		=> __('YES', $bbit->localizationName),
							'false' 	=> __('NO', $bbit->localizationName)
						)
					)

				)
			) // end shortcode
			
			/* full shortcode */
			// [bbit_full id=all show_business=true show_address=true show_contact=true show_opening_hours=true show_payment=true show_gmap=true]
			,'bbit_full' => array(
				'title' 	=> __('Insert Full Shortcode', $bbit->localizationName),
				'icon' 		=> '{plugin_folder_uri}assets/menu_icon.png',
				'size' 		=> 'grid_4', // grid_1|grid_2|grid_3|grid_4
				'header' 	=> true, // true|false
				'toggler' 	=> false, // true|false
				'buttons' 	=> false, // true|false
				'style' 	=> 'panel', // panel|panel-widget
				
				'shortcode'	=> '[bbit_full id={location_id} show_business={show_business} show_address={show_address} show_contact={show_contact} show_opening_hours={show_opening_hours} show_payment={show_payment} show_gmap={show_gmap}]',

				// create the box elements array
				'elements'	=> array(
				
					'location_id' => array(
						'type' 		=> 'html',
						'html' 		=> bbit_getLocationsList()
					),

					'show_business' => array(
						'type' 		=> 'select',
						'std' 		=> 'no',
						'size' 		=> 'large',
						'force_width'=> '120',
						'title' 	=> __('Show Business:', $bbit->localizationName),
						'desc' 		=> __('show business details', $bbit->localizationName),
						'options'	=> array(
							'true' 		=> __('YES', $bbit->localizationName),
							'false' 	=> __('NO', $bbit->localizationName)
						)
					),
					'show_address' => array(
						'type' 		=> 'select',
						'std' 		=> 'no',
						'size' 		=> 'large',
						'force_width'=> '120',
						'title' 	=> __('Show Address:', $bbit->localizationName),
						'desc' 		=> __('show address details', $bbit->localizationName),
						'options'	=> array(
							'true' 		=> __('YES', $bbit->localizationName),
							'false' 	=> __('NO', $bbit->localizationName)
						)
					),
					'show_contact' => array(
						'type' 		=> 'select',
						'std' 		=> 'no',
						'size' 		=> 'large',
						'force_width'=> '120',
						'title' 	=> __('Show Contact:', $bbit->localizationName),
						'desc' 		=> __('show contact details', $bbit->localizationName),
						'options'	=> array(
							'true' 		=> __('YES', $bbit->localizationName),
							'false' 	=> __('NO', $bbit->localizationName)
						)
					),
					'show_opening_hours' => array(
						'type' 		=> 'select',
						'std' 		=> 'no',
						'size' 		=> 'large',
						'force_width'=> '120',
						'title' 	=> __('Show Opening Hours:', $bbit->localizationName),
						'desc' 		=> __('show opening hours details', $bbit->localizationName),
						'options'	=> array(
							'true' 		=> __('YES', $bbit->localizationName),
							'false' 	=> __('NO', $bbit->localizationName)
						)
					),
					'show_payment' => array(
						'type' 		=> 'select',
						'std' 		=> 'no',
						'size' 		=> 'large',
						'force_width'=> '120',
						'title' 	=> __('Show Payment:', $bbit->localizationName),
						'desc' 		=> __('show payment details', $bbit->localizationName),
						'options'	=> array(
							'true' 		=> __('YES', $bbit->localizationName),
							'false' 	=> __('NO', $bbit->localizationName)
						)
					),
					'show_gmap' => array(
						'type' 		=> 'select',
						'std' 		=> 'no',
						'size' 		=> 'large',
						'force_width'=> '120',
						'title' 	=> __('Show Google Map:', $bbit->localizationName),
						'desc' 		=> __('show google map details', $bbit->localizationName),
						'options'	=> array(
							'true' 		=> __('YES', $bbit->localizationName),
							'false' 	=> __('NO', $bbit->localizationName)
						)
					)

				)
			) // end shortcode
			
			/* google map shortcode */
			// [bbit_gmap id=all width=320 height=240 zoom=12 maptype="roadmap" type="static"]
			,'bbit_gmap' => array(
				'title' 	=> __('Insert Google Map', $bbit->localizationName),
				'icon' 		=> '{plugin_folder_uri}assets/menu_icon.png',
				'size' 		=> 'grid_4', // grid_1|grid_2|grid_3|grid_4
				'header' 	=> true, // true|false
				'toggler' 	=> false, // true|false
				'buttons' 	=> false, // true|false
				'style' 	=> 'panel', // panel|panel-widget
				
				'shortcode'	=> '[bbit_gmap id={location_id} width={width} height={height} zoom={zoom} maptype="{maptype}" type="{type}"]',

				// create the box elements array
				'elements'	=> array(
				
					'location_id' => array(
						'type' 		=> 'html',
						'html' 		=> bbit_getLocationsList()
					),

					'width' 	=> array(
						'type' 		=> 'text',
						'std' 		=> '320',
						'size' 		=> 'large',
						'force_width'=> '150',
						'title' 	=> __('Width: ', $bbit->localizationName),
						'desc' 		=> __('google map width', $bbit->localizationName)
					),
					'height' 	=> array(
						'type' 		=> 'text',
						'std' 		=> '240',
						'size' 		=> 'large',
						'force_width'=> '150',
						'title' 	=> __('Height: ', $bbit->localizationName),
						'desc' 		=> __('google map height', $bbit->localizationName)
					),
					'zoom' 	=> array(
						'type' 		=> 'text',
						'std' 		=> '12',
						'size' 		=> 'large',
						'force_width'=> '150',
						'title' 	=> __('Zoom: ', $bbit->localizationName),
						'desc' 		=> __('google map zoom (recommended values: 1-20)', $bbit->localizationName)
					),
					'maptype' => array(
						'type' 		=> 'select',
						'std' 		=> 'no',
						'size' 		=> 'large',
						'force_width'=> '120',
						'title' 	=> __('Map type:', $bbit->localizationName),
						'desc' 		=> __('google map type', $bbit->localizationName),
						'options'	=> array(
							'roadmap' 		=> __('Roadmap', $bbit->localizationName),
							'satellite' 	=> __('Satellite', $bbit->localizationName),
							'terrain' 		=> __('Terrain', $bbit->localizationName),
							'hybrid' 		=> __('Hybrid', $bbit->localizationName)
						)
					),
					'type' => array(
						'type' 		=> 'select',
						'std' 		=> 'no',
						'size' 		=> 'large',
						'force_width'=> '120',
						'title' 	=> __('What map do you want to display? ', $bbit->localizationName),
						'desc' 		=> __('static: image map; dynamic: javascript map', $bbit->localizationName),
						'options'	=> array(
							'static' 		=> __('Static', $bbit->localizationName),
							'dynamic' 		=> __('Dynamic', $bbit->localizationName)
						)
					)

				)
			) // end shortcode
			
		)
	)
);

?>