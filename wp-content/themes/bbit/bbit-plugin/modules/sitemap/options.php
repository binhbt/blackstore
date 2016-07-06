<?php
/**
 * Dummy module return as json_encode
 * http://bbit.vn
 * @author		Pham Quang Bao
 * @version		1.0
 */

$__bbit_video_include = array(
	'localhost'			=> 'Self Hosted'
	,'blip'				=> 'Blip.tv'
	,'dailymotion'		=> 'Dailymotion.com'
	,'dotsub'			=> 'Dotsub.com'
	,'flickr'			=> 'Flickr.com'
	,'metacafe'			=> 'Metacafe.com'
	,'screenr'			=> 'Screenr.com'
	,'veoh'				=> 'Veoh.com'
	,'viddler'			=> 'Viddler.com'
	,'vimeo'			=> 'Vimeo.com'
	,'vzaar'			=> 'Vzaar.com'
	,'youtube'			=> 'Youtube.com'
	,'wistia'			=> 'Wistia.com'
);

function bbit_postTypes_priority() {
	global $bbit;

	ob_start();
	
	$post_types = get_post_types(array(
		'public'   => true
	));
	//$post_types['attachment'] = __('Images', $this->the_plugin->localizationName);
	//unset media - images | videos are treated as belonging to post, pages, custom post types
	unset($post_types['attachment'], $post_types['revision']);
	
	$options = $bbit->get_theoption('bbit_sitemap');
?>
<div class="bbit-form-row">
	<label>Posts</label>
	<div class="bbit-form-item large">
	<span class="formNote">&nbsp;</span>
	<?php
	foreach ($post_types as $key => $value){
		$val = '';
		if( isset($options['priority']) && isset($options['priority'][$key]) ){
			$val = $options['priority'][$key];
		}
		?>
		<label for="priority[<?php echo $key;?>]" style="display:inline;float:none;"><?php echo ucfirst(str_replace('_', ' ', $value));?>:</label>
		&nbsp;
		<select id="priority[<?php echo $key;?>]" name="priority[<?php echo $key;?>]" style="width:60px;">
			<?php
			foreach (range(0, 1, 0.1) as $kk => $vv){
				echo '<option value="' . ( $vv ) . '" ' . ( $val == $vv ? 'selected="true"' : '' ) . '>' . ( $vv ) . '</option>';
			} 
			?>
		</select>&nbsp;&nbsp;&nbsp;&nbsp;
		<?php
	} 
	?>
	</div>
</div>
<?php
	$output = ob_get_contents();
	ob_end_clean();
	return $output;
} 

function bbit_postTypes_changefreq() {
	global $bbit;

	ob_start();
	
	$post_types = get_post_types(array(
		'public'   => true
	));
	//$post_types['attachment'] = __('Images', $this->the_plugin->localizationName);
	//unset media - images | videos are treated as belonging to post, pages, custom post types
	unset($post_types['attachment'], $post_types['revision']);
	
	$options = $bbit->get_theoption('bbit_sitemap');
?>
<div class="bbit-form-row">
	<label>Posts</label>
	<div class="bbit-form-item large">
	<span class="formNote">&nbsp;</span>
	<?php
	foreach ($post_types as $key => $value){
		
		$val = '';
		if( isset($options['changefreq']) && isset($options['changefreq'][$key]) ){
			$val = $options['changefreq'][$key];
		}
		?>
		<label for="changefreq[<?php echo $key;?>]" style="display:inline;float:none;"><?php echo ucfirst(str_replace('_', ' ', $value));?>:</label>
		&nbsp;
		<select id="changefreq[<?php echo $key;?>]" name="changefreq[<?php echo $key;?>]" style="width:120px;">
			<?php
			foreach (array('always', 'hourly', 'daily', 'weekly', 'monthly', 'yearly', 'never') as $kk => $vv){
				echo '<option value="' . ( $vv ) . '" ' . ( $val == $vv ? 'selected="true"' : '' ) . '>' . ( $vv ) . '</option>';
			} 
			?>
		</select>&nbsp;&nbsp;&nbsp;&nbsp;
		<?php
	} 
	?>
	</div>
</div>
<?php
	$output = ob_get_contents();
	ob_end_clean();
	return $output;
}

function bbit_postTypes_get() {
	global $bbit;

	$post_types = get_post_types(array(
		'public'   => true
	));
	//unset media - images | videos are treated as belonging to post, pages, custom post types
	unset($post_types['attachment'], $post_types['revision']);
	return $post_types;
}

function __bbitNotifyEngine( $engine='google', $action='default' ) {
	global $bbit;
	
	$req['action'] = $action;
	
	if ( $req['action'] == 'getStatus' ) {
		$notifyStatus = $bbit->get_theoption('bbit_sitemap_engine_notify');
		if ( $notifyStatus === false || !isset($notifyStatus["$engine"]) || !isset($notifyStatus["$engine"]["sitemap"]) )
			return '';
		return $notifyStatus["$engine"]["sitemap"]["msg_html"];
	}

	$html = array();
	
	$html[] = '<div class="bbit-form-row bbit-notify-engine-ping bbit-notify-' . $engine . '">';

	if ( $engine == 'google' ) {
		$html[] = '<div class="">' . sprintf( __('Thông báo với Google: Bạn có thể check số liệu thống kê trên <a href="%s" target="_blank">Google Webmaster Tools</a>', $bbit->localizationName), 'http://www.google.com/webmasters/tools/' ). '</div>';
	} else if ( $engine == 'bing' ) {
		$html[] = '<div class="">' . sprintf( __('Thông báo với Bing: Bạn có thể check số liệu thống kê trên <a href="%s" target="_blank">Bing Webmaster Tools</a>', $bbit->localizationName), 'http://www.bing.com/toolbox/webmaster' ). '</div>';
	}
	
	ob_start();
?>
		<label for="sitemap_type<?php echo '_'.$engine; ?>" style="display:inline;float:none;">Chọn sitemap:</label>
		&nbsp;
		<select id="sitemap_type<?php echo '_'.$engine; ?>" name="sitemap_type" style="width:160px;">
			<?php
			foreach (array('sitemap' => 'Sitemap.xml', 'sitemap_images' => 'Sitemap-Images.xml', 'sitemap_videos' => 'Sitemap-Videos.xml') as $kk => $vv){
				echo '<option value="' . ( $kk ) . '" ' . ( 0 ? 'selected="true"' : '' ) . '>' . ( $vv ) . '</option>';
			} 
			?>
		</select>&nbsp;&nbsp;
<?php
	$selectSitemap = ob_get_contents();
	ob_end_clean();
	$html[] = $selectSitemap;
	
	$html[] = '<input type="button" class="bbit-button blue" style="width: 160px;" id="bbit-notify-' . $engine . '" value="' . ( __('Notify '.ucfirst($engine), $bbit->localizationName) ) . '">
	<span style="margin:0px 0px 0px 10px" class="response">' . __bbitNotifyEngine( $engine, 'getStatus' ) . '</span>';

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
				'sub_action'	: 'notify',
				'engine'		: engine,
				'sitemap_type'	: $('#sitemap_type_'+engine).val()
			}, function(response) {

				var $box = $('.bbit-notify-'+engine), $res = $box.find('.response');
				$res.html( response.msg_html );
				if ( response.status == 'valid' )
					return true;
				return false;
			}, 'json');
		});
		
		$('#sitemap_type_'+engine).on('change', function (e) {
			e.preventDefault();

			$.post(ajaxurl, {
				'action' 		: 'bbitAdminAjax',
				'sub_action'	: 'getStatus',
				'engine'		: engine,
				'sitemap_type'	: $('#sitemap_type_'+engine).val()
			}, function(response) {

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
			'sitemap' => array(
				'title' 	=> __('Sitemap settings', $bbit->localizationName),
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
								<label for="site-items">' . __('Sitemap của bạn:', $bbit->localizationName) . '</label>
						   		<div class="bbit-form-item large">
									<a id="site-items" target="_blank" href="' . ( home_url('/sitemap.xml') ) . '" style="position: relative;bottom: -6px;">' . ( home_url('/sitemap.xml') ) . '</a>
								</div>
								
								<label for="site-items">' . __('Images sitemap:', $bbit->localizationName) . '</label>
						   		<div class="bbit-form-item large">
									<a id="site-items" target="_blank" href="' . ( home_url('/sitemap-images.xml') ) . '" style="position: relative;bottom: -6px;">' . ( home_url('/sitemap-images.xml') ) . '</a>
								</div>
								
								<label for="site-items">' . __('Videos sitemap:', $bbit->localizationName) . '</label>
						   		<div class="bbit-form-item large">
									<a id="site-items" target="_blank" href="' . ( home_url('/sitemap-videos.xml') ) . '" style="position: relative;bottom: -6px;">' . ( home_url('/sitemap-videos.xml') ) . '</a>
								</div>
							</div>'
					),
					
					/*
					'logo' => array(
						'type' 			=> 'upload_image_wp',
						'size' 			=> 'large',
						'force_width'	=> '80',
						'preview_size'	=> 'large',	
						'value' 		=> __('Upload Image', $bbit->localizationName),
						'title' 		=> __('Logo', $bbit->localizationName),
						'desc' 			=> __('Upload your Logo using the native media uploader', $bbit->localizationName),
					),*/
					
					/*'xmlsitemap_html' => array(
						'type' 		=> 'html',
						'html' 		=> 
							'<div class="bbit-form-row">
						   		<label for="items_per_page">Items per page</label>
						   		<div class="bbit-form-item large">
									<span class="formNote">Number of items per page:</span>
									<a href="' . ( home_url('/sitemap.xml') ) . '" style="position: relative;bottom: -6px;">' . ( home_url('/sitemap.xml') ) . '</a>
								</div>
							</div>'
					),*/
					/*'items_per_page' => array(
						'type' 		=> 'text',
						'std' 		=> '100',
						'size' 		=> 'large',
						'force_width'=> '120',
						'title' 	=> 'Items per page',
						'desc' 		=> 'Number of items per page:',
					),*/
					
					/*'stylesheet' 	=> array(
						'type' 		=> 'select',
						'std' 		=> 'no',
						'size' 		=> 'large',
						'force_width'=> '70',
						'title' 	=> 'Disable Stylesheet',
						'desc' 		=> '&nbsp;',
						'options' 	=> array(
							'yes' 	=> 'YES', 
							'no' 	=>'NO'
						)
					),*/
					
					'notify' => array(
						'type' 		=> 'html',
						'html' 		=> __(
							'<div class="bbit-form-row">
								<div>Nếu bạn sử dụng 1 file robots.txt, bạn nên thêm sitemap vào file robot của bạn
									<ul style="margin-left: 20px;">
										<li><i>'. home_url('/sitemap.xml'). '</i></li>
										<li><i>'. home_url('/sitemap-images.xml'). '</i></li>
										<li><i>'. home_url('/sitemap-videos.xml'). '</i></li>
									</ul>
								</div>
								<div>Nếu bạn sử dụng Wordpress robots.txt mặc định, sử dụng thiết lập sau để thêm</div>
							</div>', $bbit->localizationName)
					),
					
					'notify_virtual_robots' => array(
						'type' 		=> 'select',
						'std' 		=> 'no',
						'size' 		=> 'large',
						'force_width'=> '120',
						'title' 	=> __('Thêm sitemap vào robots.txt ', $bbit->localizationName),
						'desc' 		=> __('Add to Wordpress virtual robots.txt', $bbit->localizationName),
						'options'	=> array(
							'yes' 	=> __('YES', $bbit->localizationName),
							'no' 	=> __('NO', $bbit->localizationName)
						)
					),
					
					'notify_google' => array(
						'type' => 'html',
						'html' => __bbitNotifyEngine( 'google' )
					),
					
					'notify_bing' => array(
						'type' => 'html',
						'html' => __bbitNotifyEngine( 'bing' )
					),
					
					'post_types' 	=> array(
						'type' 		=> 'multiselect',
						'std' 		=> array('post','page'),
						'size' 		=> 'small',
						'force_width'=> '300',
						'title' 	=> __('Post Types:', $bbit->localizationName),
						'desc' 		=> __('Select post types which you want to include in your sitemap xml file.', $bbit->localizationName),
						'options' 	=> bbit_postTypes_get()
					),
					
					'include_img' => array(
						'type' 		=> 'select',
						'std' 		=> 'no',
						'size' 		=> 'large',
						'force_width'=> '120',
						'title' 	=> __('Include Images:', $bbit->localizationName),
						'desc' 		=> __('Website posts, pages sitemap.xml file will include images', $bbit->localizationName),
						'options'	=> array(
							'yes' 	=> __('YES', $bbit->localizationName),
							'no' 	=> __('NO', $bbit->localizationName)
						)
					),
					
					/*'include_video' => array(
						'type' 		=> 'select',
						'std' 		=> 'no',
						'size' 		=> 'large',
						'force_width'=> '120',
						'title' 	=> 'Include Videos:',
						'desc' 		=> 'Sitemap file will include videos',
						'options'	=> array(
							'yes' 	=> 'YES',
							'no' 	=> 'NO'
						)
					),*/
					
					array(
						'type' 		=> 'message',
						'status' 	=> 'info',
						'html' 		=> __('
							<h3 style="margin: 0px 0px 5px 0px;">Priorities:</h3>
							<p>Because this value is relative to other pages on your site, assigning a high priority (or specifying the same priority for all URLs) will not help your site\'s search ranking. In addition, setting all pages to the same priority will have no effect.</p>
						', $bbit->localizationName)
					),
					
					'priority' => array(
						'type' 		=> 'html',
						'html' 		=> bbit_postTypes_priority()
					),
					
					array(
						'type' 		=> 'message',
						'status' 	=> 'info',
						'html' 		=> __('
							<h3 style="margin: 0px 0px 5px 0px;">Changes frequencies:</h3>
							<p>Provides a hint about how frequently the page is likely to change.</p>
						', $bbit->localizationName)
					),
					
					'changefreq' => array(
						'type' 		=> 'html',
						'html' 		=> bbit_postTypes_changefreq()
					),
					
					/* Video Xml Sitemap */
					'video_html' => array(
						'type' 		=> 'html',
						'html' 		=> 
							'<div class="bbit-form-row">
								<span><strong>' . __('Video sitemap settings:', $bbit->localizationName) . '</strong></span>
							</div>'
					),
					
					'video_title_prefix' 	=> array(
						'type' 		=> 'text',
						'std' 		=> 'Video',
						'size' 		=> 'large',
						'force_width'=> '150',
						'title' 	=> __('Video Title Before Text: ', $bbit->localizationName),
						'desc' 		=> __('this text will be showed as prefix for video title in the schema.org content snippet (only if the text doesn\'t already exist in the title).', $bbit->localizationName)
					),
					
					'video_social_force' => array(
						'type' 		=> 'select',
						'std' 		=> 'no',
						'size' 		=> 'large',
						'force_width'=> '120',
						'title' 	=> __('Social Tags Rewrite: ', $bbit->localizationName),
						'desc' 		=> __('rewrite the social meta tags (facebook) with the information from the video; if you have multiple videos in the post or page content, will use the first video found by our search algorithm and it may not be the first video in your post or page content', $bbit->localizationName),
						'options'	=> array(
							'yes' 	=> __('YES', $bbit->localizationName),
							'no' 	=> __('NO', $bbit->localizationName)
						)
					),
					
					'thumb_default' => array(
						'type' 			=> 'upload_image',
						'size' 			=> 'large',
						'force_width'	=> '80',
						'preview_size'	=> 'large',	
						'value' 		=> __('Upload Image', $bbit->localizationName),
						'title' 		=> __('Video Default Thumb', $bbit->localizationName),
						'desc' 			=> __('Upload your Video Default Thumb using the native media uploader', $bbit->localizationName),
						'thumbSize' => array(
							'w' => '100',
							'h' => '100',
							'zc' => '2',
						)
					),
					
					'video_include' 	=> array(
						'type' 		=> 'multiselect',
						'std' 		=> array_keys($__bbit_video_include),
						'size' 		=> 'large',
						'force_width'=> '300',
						'title' 	=> __('Select Video Providers:', $bbit->localizationName),
						'desc' 		=> __('select the video providers to include in your sitemap-videos.xml', $bbit->localizationName),
						'options' 	=> $__bbit_video_include
					),
					
					'vzaar_domain' 	=> array(
						'type' 		=> 'text',
						'std' 		=> 'vzaar.com/videos',
						'size' 		=> 'large',
						'force_width'=> '150',
						'title' 	=> __('Vzaar domain: ', $bbit->localizationName),
						'desc' 		=> __('enter vzaar domain.', $bbit->localizationName)
					),
					
					'viddler_key' 	=> array(
						'type' 		=> 'text',
						'std' 		=> '',
						'size' 		=> 'large',
						'force_width'=> '150',
						'title' 	=> __('Viddler key: ', $bbit->localizationName),
						'desc' 		=> __('enter viddler key.', $bbit->localizationName)
					),
					
					'flickr_key' 	=> array(
						'type' 		=> 'text',
						'std' 		=> '',
						'size' 		=> 'large',
						'force_width'=> '150',
						'title' 	=> __('Flickr key: ', $bbit->localizationName),
						'desc' 		=> __('enter flickr key.', $bbit->localizationName)
					),
				)
			)
		)
	)
);