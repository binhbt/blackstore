<?php
/**
 * module return as json_encode
 * http://bbit.vn
 *
 * @author		Pham Quang Bao
 * @version		1.0
 */

function __bbitNotifyEngine_localseo( $engine='google', $action='default' ) {
	global $bbit;
	
	$req['action'] = $action;
	
	if ( $req['action'] == 'getStatus' ) {
		$notifyStatus = $bbit->get_theoption('bbit_localseo_engine_notify');
		if ( $notifyStatus === false || !isset($notifyStatus["$engine"]) )
			return '';
		return $notifyStatus["$engine"]["msg_html"];
	}

	$html = array();
	
	$html[] = '<div class="bbit-form-row bbit-notify-engine-ping bbit-notify-' . $engine . '">';

	if ( $engine == 'google' ) {
		$html[] = '<div class="">' . sprintf( __('Thông báo với Google: Bạn có thể check số liệu thống kê trên <a href="%s" target="_blank">Google Webmaster Tools</a>', $bbit->localizationName), 'http://www.google.com/webmasters/tools/' ). '</div>';
	} else if ( $engine == 'bing' ) {
		$html[] = '<div class="">' . sprintf( __('Thông báo với Bing: Bạn có thể check số liệu thống kê trên <a href="%s" target="_blank">Bing Webmaster Tools</a>', $bbit->localizationName), 'http://www.bing.com/toolbox/webmaster' ). '</div>';
	}

	$html[] = '<input type="button" class="bbit-button blue" style="width: 160px;" id="bbit-notify-' . $engine . '" value="' . ( __('Notify '.ucfirst($engine), $bbit->localizationName) ) . '">
	<span style="margin:0px 0px 0px 10px" class="response">' . __bbitNotifyEngine_localseo( $engine, 'getStatus' ) . '</span>';

	$html[] = '</div>';

	// view page button
	ob_start();
?>
	<script>
	(function($) {
		var ajaxurl = '<?php echo admin_url('admin-ajax.php');?>',
		engine = '<?php echo $engine; ?>';

		$("body").on("click", "#bbit-notify-"+engine, function(){

			$.post(ajaxurl, {
				'action' 		: 'bbitAdminAjax',
				'sub_action'	: 'localseo_notify',
				'sitemap_type'	: 'xml',
				'engine'		: engine
			}, function(response) {
console.log( response  );
				var $box = $('.bbit-notify-'+engine), $res = $box.find('.response');
				$res.html( response.msg_html );
				if ( response.status == 'valid' )
					return true;
				return false;
			}, 'json');
		});
   	})(jQuery);
	</script>
<?php
	$__js = ob_get_contents();
	ob_end_clean();
	$html[] = $__js;

	return implode( "\n", $html );
}
global $bbit;
echo json_encode(
	array(
		$tryed_module['db_alias'] => array(
			/* define the form_messages box */
			'local_seo' => array(
				'title' 	=> __('Local SEO', $bbit->localizationName),
				'icon' 		=> '{plugin_folder_uri}assets/menu_icon.png',
				'size' 		=> 'grid_4', // grid_1|grid_2|grid_3|grid_4
				'header' 	=> true, // true|false
				'toggler' 	=> false, // true|false
				'buttons' 	=> true, // true|false
				'style' 	=> 'panel', // panel|panel-widget

				// create the box elements array
				'elements'	=> array(
					'xmlsitemap_html' => array(
						'type' 		=> 'html',
						'html' 		=> 
							'<div class="bbit-form-row">
								<label for="site-items">' . __('Local SEO - locations', $bbit->localizationName) . '</label>
						   		<div class="bbit-form-item large">
									<a id="site-items" target="_blank" href="' . ( home_url('/sitemap-locations.xml') ) . '" style="position: relative;bottom: -6px;">' . ( home_url('/sitemap-locations.xml') ) . '</a>
								</div>
						   		<!--<div class="bbit-form-item large">
									<a id="site-items" target="_blank" href="' . ( home_url('/sitemap-locations.kml') ) . '" style="position: relative;bottom: -6px;">' . ( home_url('/sitemap-locations.kml') ) . '</a>
								</div>-->
								
								<label for="site-items">' . __('Validators', $bbit->localizationName) . '</label>
								<div class="bbit-form-item large">
									<a id="site-items" target="_blank" href="http://www.google.com/webmasters/tools/richsnippets" style="position: relative;bottom: -6px;">Google Rich Snippets Testing Tool</a>
								</div>
								<div class="bbit-form-item large">
									<a id="site-items" target="_blank" href="http://www.ebusiness-unibw.org/tools/goodrelations-validator/" style="position: relative;bottom: -6px;">GoodRelations Validator</a>
								</div>
							</div>'
					)

					/*'google_map_api_key' 	=> array(
						'type' 		=> 'text',
						'std' 		=> '',
						'size' 		=> 'large',
						'force_width'=> '350',
						'title' 	=> __('Google Maps API Key:', $bbit->localizationName),
						'desc' 		=> __('Here you can enter Google Maps API Console Key, recommended by <a href="https://developers.google.com/maps/documentation/javascript/tutorial?hl=en#api_key" target="_blank">Google Tutorial</a>', $bbit->localizationName)
					)*/
					,'slug' 	=> array(
						'type' 		=> 'text',
						'std' 		=> 'bbitlocation',
						'size' 		=> 'small',
						'force_width'=> '350',
						'title' 	=> __('Slug: ', $bbit->localizationName),
						'desc' 		=> __('Custom Slug for your Locations', $bbit->localizationName)
					)
					
					,'notify_google' => array(
						'type' => 'html',
						'html' => __bbitNotifyEngine_localseo( 'google' )
					)

					,'address_format' 	=> array(
						'type' 		=> 'text',
						'std' 		=> '{street} {city}, {state} {zipcode} {country}',
						'size' 		=> 'large',
						'force_width'=> '350',
						'title' 	=> __('Address Format: ', $bbit->localizationName),
						'desc' 		=> __('You can use the following tags: {street} {city}, {state} {zipcode} {country}. This format is used for kml sitemap generation and for address shortcode. <!--Also {street} is included first by default and {country} is included last by default in this format and you must not include them.-->', $bbit->localizationName)
					)
				)
			)
			
		)
	)
);