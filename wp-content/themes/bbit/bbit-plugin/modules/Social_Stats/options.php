<?php
/**
 * module return as json_encode
 * http://bbit.vn
 * @author		Pham Quang Bao
 * @version		1.0
 */

function bbit_social_sharing_html() {
	global $bbit;
	
	ob_start();
	
	$frm_folder = $bbit->cfg['paths']['freamwork_dir_url'];
	$module_folder = $bbit->cfg['paths']['plugin_dir_url'] . 'modules/Social_Stats/';
	
	$__toolbarTypes = array(
		'floating'			=> __('Floating Toolbar', $bbit->localizationName),
		'content_horizontal'		=> __('Content Top / Bottom Toolbar', $bbit->localizationName),
		'content_vertical'		=> __('Content Left / Right Toolbar', $bbit->localizationName)
	);

	$social_sharing_opt = $bbit->get_theoption( $bbit->alias . '_socialsharing' );
	$__enabledHtml = array();
	foreach ($__toolbarTypes as $k=>$v) {
		$__key = $k . '-enabled';
		$__key2 = 'tab-item-' . $k;
		
		$isEnabled = false;
		if ( is_array($social_sharing_opt) && isset($social_sharing_opt[$__key]) && $social_sharing_opt[$__key]=='yes') $isEnabled = true;
		$__enabledHtml[] = '<li class="tab-item"><input type="checkbox" name="'.$__key2.'" id="'.$__key2.'" disabled ' . ($isEnabled ? 'checked' : '') . '/><span class="text">' . $v . '</span></li>';
	}
?>
	<ul class="bbit-socialshare-tbl-tabs">
		<?php echo implode('', $__enabledHtml); ?>
	</ul>

	<div class="bbit-form-row" id="<?php echo 'bbit-socialsharing-ajax'; ?>" style="position:relative;"></div>
	
	<!-- color picker -->
	<link rel='stylesheet' href='<?php echo $frm_folder; ?>js/colorpicker/colorpicker.css' type='text/css' media='all' />
	<script type="text/javascript" src="<?php echo $frm_folder; ?>js/colorpicker/colorpicker.js"></script>

	<!-- admin css/js -->
	<link rel='stylesheet' href='<?php echo $module_folder; ?>app.css' type='text/css' media='all' />
	<script type="text/javascript" src="<?php echo $module_folder; ?>social_sharing.admin.js"></script>
<?php
	$output = ob_get_contents();
	ob_end_clean();
	return $output;
}

function bbit_social_sharing_toolbar_enabled_html() {
	global $bbit;
	
	ob_start();
	
	$module_folder = $bbit->cfg['paths']['plugin_dir_url'] . 'modules/Social_Stats/';
	
	$__toolbarTypes = array(
		'floating'			=> __('Floating Toolbar', $bbit->localizationName),
		'content_horizontal'		=> __('Content Top / Bottom Toolbar', $bbit->localizationName),
		'content_vertical'		=> __('Content Left / Right Toolbar', $bbit->localizationName)
	);

	$social_sharing_opt = $bbit->get_theoption( $bbit->alias . '_socialsharing' );
	$__enabledHtml = array();
	foreach ($__toolbarTypes as $k=>$v) {
		$__key = $k . '-enabled';
		if ( is_array($social_sharing_opt) && isset($social_sharing_opt[$__key]) && $social_sharing_opt[$__key]=='yes') {
			$__enabledHtml[] = '<li data-tbtype="' . $k . '">' . $v . '</li>';
		}
	}
?>
<div class="bbit-form-row" style="padding-top: 0; padding-bottom: 0;">
	<label style="margin-top: 7px;">Enabled Toolbars: </label>
	<div class="bbit-form-item large">
		<ul class="toolbars-enabled">
			<?php echo implode('', $__enabledHtml); ?>
		</ul>
	</div>
</div>
<?php
	$output = ob_get_contents();
	ob_end_clean();
	return $output;
}

global $bbit;
echo json_encode(
	array(
		$tryed_module['db_alias'] => array(
			/* define the form_messages box */
			'social' => array(
				'title' 	=> __('Social Services', $bbit->localizationName),
				'icon' 		=> '{plugin_folder_uri}assets/menu_icon.png',
				'size' 		=> 'grid_4', // grid_1|grid_2|grid_3|grid_4
				'header' 	=> true, // true|false
				'toggler' 	=> false, // true|false
				'buttons' 	=> true, // true|false
				'style' 	=> 'panel', // panel|panel-widget

				// create the box elements array
				'elements'	=> array(
					'services' 	=> array(
						'type' 		=> 'multiselect',
						'std' 		=> array('facebook', 'twitter', 'google', 'stumbleupon', 'digg', 'linkedin'),
						'size' 		=> 'small',
						'force_width'=> '250',
						'title' 	=> __('Check on:', $bbit->localizationName),
						'desc' 		=> __('Show status on this social services.', $bbit->localizationName),
						'options' 	=> array(
							'facebook' 		=> 'Facebook',
							'pinterest' 	=> 'Pinterest',
							'twitter' 		=> 'Twitter',
							'google' 		=> 'Google +1',
							'stumbleupon' 	=> 'Stumbleupon',
							'digg' 			=> 'Digg',
							'linkedin' 		=> 'LinkedIn'
						)
					)
					
				)
			)
			
			/* define the form_messages box */
			,'socialsharing' => array(
				'title' 	=> __('Social Sharing', $bbit->localizationName),
				'icon' 		=> '{plugin_folder_uri}assets/menu_icon.png',
				'size' 		=> 'grid_4', // grid_1|grid_2|grid_3|grid_4
				'header' 	=> true, // true|false
				'toggler' 	=> false, // true|false
				'buttons' 	=> true, // true|false
				'style' 	=> 'panel', // panel|panel-widget

				// create the box elements array
				'elements'	=> array(
					'text_email' 	=> array(
						'type' 		=> 'text',
						'std' 		=> __('Email', $bbit->localizationName),
						'size' 		=> 'large',
						'force_width'=> '400',
						'title' 		=> __('Email text:', $bbit->localizationName),
						'desc' 		=> __('email text', $bbit->localizationName)
					),
					'text_print' 	=> array(
						'type' 		=> 'text',
						'std' 		=> __('Print', $bbit->localizationName),
						'size' 		=> 'large',
						'force_width'=> '400',
						'title' 		=> __('Print text:', $bbit->localizationName),
						'desc' 		=> __('print text', $bbit->localizationName)
					),
					'text_more' 	=> array(
						'type' 		=> 'text',
						'std' 		=> __('More', $bbit->localizationName),
						'size' 		=> 'large',
						'force_width'=> '400',
						'title' 		=> __('More text:', $bbit->localizationName),
						'desc' 		=> __('more text', $bbit->localizationName)
					),
					'email' 	=> array(
						'type' 		=> 'text',
						'std' 		=> '',
						'size' 		=> 'large',
						'force_width'=> '400',
						'title' 		=> __('Email address:', $bbit->localizationName),
						'desc' 		=> __('email address', $bbit->localizationName)
					),
					'twitter_id' 	=> array(
						'type' 		=> 'text',
						'std' 		=> '',
						'size' 		=> 'large',
						'force_width'=> '400',
						'title' 		=> __('Twitter Account ID:', $bbit->localizationName),
						'desc' 		=> __('Twitter Account ID', $bbit->localizationName)
					),

					/*'toolbar_enabled_html' => array(
						'type' 		=> 'html',
						'html' 		=> bbit_social_sharing_toolbar_enabled_html()
					),
					
					'toolbar' => array(
						'type' 		=> 'select',
						'std' 		=> 'floating',
						'size' 		=> 'large',
						'force_width'=> '200',
						'title' 	=> __('Social Sharing Toolbar:', $bbit->localizationName),
						'desc' 		=> '&nbsp;',
						'options'	=> array(
							//'none'				=> __('None', $bbit->localizationName),
							'floating'			=> __('Floating Toolbar', $bbit->localizationName),
							'content_horizontal'		=> __('Content Top / Bottom Toolbar', $bbit->localizationName),
							'content_vertical'		=> __('Content Left / Right Toolbar', $bbit->localizationName)
						)
					),*/

					'toolbar_html' => array(
						'type' 		=> 'html',
						'html' 		=> bbit_social_sharing_html()
					)
				)
			)
		)
	)
);