<?php
/**
 * module return as json_encode
 * http://bbit.vn
 * @author		Pham Quang Bao
 * @version		1.0
 */
global $bbit;
echo json_encode(
	array(
		$tryed_module['db_alias'] => array(
			/* define the form_messages box */
			'google_analytics' => array(
				'title' 	=> __('Google Analytics', $bbit->localizationName),
				'icon' 		=> '{plugin_folder_uri}assets/menu_icon.png',
				'size' 		=> 'grid_4', // grid_1|grid_2|grid_3|grid_4
				'header' 	=> true, // true|false
				'toggler' 	=> false, // true|false
				'buttons' 	=> array(
					'save' => array(
						'value' => __('Save settings', $bbit->localizationName),
						'color' => 'green',
						'action'=> 'bbit-saveOptions'
					)
				), // true|false
				'style' 	=> 'panel', // panel|panel-widget

				// create the box elements array
				'elements'	=> array(
					array(
						'type' 		=> 'message',
						
						'html' 		=> __('
							<h2>Thiết Lập Cơ Bản</h2>
							<ul>
								<li>Create a Project in the Google APIs Console: <a href="https://console.developers.google.com" target="_blank">https://console.developers.google.com</a></li>
								<li>Enable the Analytics API under APIs & auth ->APIs </li>
								<li>Under APIs & auth -> Credentials -> Create Client ID</li>
								<li>On Application type, choose Web application </li>
								<li>On Authorized redirect URI make sure you add the link from the Premium Seo Google Settings</li>
							</ul>', $bbit->localizationName),
					),
						
					'client_id' 	=> array(
						'type' 		=> 'text',
						'std' 		=> '',
						'size' 		=> 'small',
						'force_width'=> '300',
						'title' 	=> __('Your client id:', $bbit->localizationName),
						'desc' 		=> __('From the APIs console.', $bbit->localizationName)
					),
					
					'client_secret' 	=> array(
						'type' 		=> 'text',
						'std' 		=> '',
						'size' 		=> 'small',
						'force_width'=> '200',
						'title' 	=> __('Your client secret:', $bbit->localizationName),
						'desc' 		=> __('From the APIs console.', $bbit->localizationName)
					),
					
					'redirect_uri' 	=> array(
						'type' 		=> 'text',
						'std' 		=> home_url( '/bbit_seo_oauth' ),
						'size' 		=> 'normal',
						'readonly'	=> true,
						'title' 	=> __('Redirect URI:', $bbit->localizationName),
						'desc' 		=> __('Url to your app, must match one in the APIs console.', $bbit->localizationName)
					),
					
					'profile_id' 	=> array(
						'type' 		=> 'select',
						'size' 		=> 'large',
						'title' 	=> __('Profile ID:', $bbit->localizationName),
						'force_width'=> '200',
						'desc' 		=> __('Select your website profile from list. If list is empty please authorize first the app.', $bbit->localizationName),
						'options'	=> apply_filters('bbit_google_analytics_get_profiles', '')
					),
					
					'authorize' => array(
						'type' => 'buttons',
						'options' => array(
							'authorize_app' => array(
								'value' => __('Authorize the app', $bbit->localizationName),
								'color' => 'blue',
								'action'=> 'bbit-google-authorize-app',
								'width' => '120px'
							)
						)
					),
					
					'last_status' 	=> array(
						'type' 		=> 'textarea-array',
						'std' 		=> '',
						'size' 		=> 'large',
						'force_width'=> '400',
						'title' 	=> __('Authorize Last Status:', $bbit->localizationName),
						'desc' 		=> __('Last Status retrieved from Google, for the Authorize operation', $bbit->localizationName)
					),
					
					'profile_last_status' 	=> array(
						'type' 		=> 'textarea-array',
						'std' 		=> '',
						'size' 		=> 'large',
						'force_width'=> '400',
						'title' 	=> __('Get Profile ID Last Status:', $bbit->localizationName),
						'desc' 		=> __('Last Status retrieved from Google, for the Get Profile ID operation', $bbit->localizationName)
					),
					
					array(
						'type' 		=> 'message',
						
						'html' 		=> __('
							Add <a href="http://www.google.com/analytics/" target="_blank">Google Analytics</a> javascript code on all pages.
						', $bbit->localizationName),
					),
					
					'google_analytics_id' 	=> array(
						'type' 		=> 'text',
						'std' 		=> '',
						'size' 		=> 'small',
						'force_width'=> '300',
						'title' 	=> __('Google Analytics ID:', $bbit->localizationName),
						'desc' 		=> __('Your Google Analytics ID to be used in tracking script', $bbit->localizationName)
					),
					
					'google_verify' 	=> array(
						'type' 		=> 'text',
						'std' 		=> '',
						'size' 		=> 'small',
						'force_width'=> '500',
						'title' 	=> __('Google Webmaster Tools:', $bbit->localizationName),
						'desc' 		=> __('&lt;meta name="google-site-verification" content="<u>content entered in Google Webmaster Tools box</u>" /&gt;', $bbit->localizationName)
					)

				)
			)
		)
	)
);