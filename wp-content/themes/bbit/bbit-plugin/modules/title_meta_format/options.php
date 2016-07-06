<?php
/**
 * module return as json_encode
 * http://bbit.vn
 *
 * @author		Pham Quang Bao
 * @version		1.0
 */

function __metaRobotsList() {
	return array(
		'noindex'	=> 'noindex', //support by: Google, Yahoo!, MSN / Live, Ask
		'nofollow'	=> 'nofollow', //support by: Google, Yahoo!, MSN / Live, Ask
		'noarchive'	=> 'noarchive', //support by: Google, Yahoo!, MSN / Live, Ask
		'noodp'		=> 'noodp' //support by: Google, Yahoo!, MSN / Live
	);
}
$__metaRobotsList = __metaRobotsList();


function bbit_OpenGraphTypes( $istab = '' ) {
	global $bbit;
	
	ob_start();

	$post_types = get_post_types(array(
		'public'   => true
	));
	//$post_types['attachment'] = __('Images', $bbit->localizationName);
	//unset media - images | videos are treated as belonging to post, pages, custom post types
	unset($post_types['attachment'], $post_types['revision']);
	
	$options = $bbit->get_theoption('bbit_title_meta_format');
?>
<div class="bbit-form-row<?php echo ($istab!='' ? ' '.$istab : ''); ?>">
	<label>Default OpenGraph Type:	</label>
	<div class="bbit-form-item large">
	<span class="formNote">&nbsp;</span>
	<?php
	foreach ($post_types as $key => $value){
		
		$val = '';
		if( isset($options['social_opengraph_default']) && isset($options['social_opengraph_default'][$key]) ){
			$val = $options['social_opengraph_default'][$key];
		}
		?>
		<label for="social_opengraph_default[<?php echo $key;?>]" style="display:inline;float:none;"><?php echo ucfirst(str_replace('_', ' ', $value));?>:</label>
		&nbsp;
		<select id="social_opengraph_default[<?php echo $key;?>]" name="social_opengraph_default[<?php echo $key;?>]" style="width:120px;">
			<option value="none">None</option>
			<?php
			$opengraph_defaults = array(
				'Internet' 	=> array(
					'article'				=> __('article', $bbit->localizationName),
					'blog'					=> __('Blog', $bbit->localizationName),
					'profile'				=> __('Profile', $bbit->localizationName),
					'website'				=> __('Website', $bbit->localizationName)
				),
				'Products' 	=> array(
					'book'					=> __('Book', $bbit->localizationName)
				),
				'Music' 	=> array(
					'music.album'			=> __('Album', $bbit->localizationName),
					'music.playlist'		=> __('Playlist', $bbit->localizationName),
					'music.radio_station'	=> __('Radio Station', $bbit->localizationName),
					'music.song'			=> __('Song', $bbit->localizationName)
				),
				'Videos' => array(
					'video.movie'			=> __('Movie', $bbit->localizationName),
					'video.episode'			=> __('TV Episode', $bbit->localizationName),
					'video.tv_show'			=> __('TV Show', $bbit->localizationName),
					'video.other'			=> __('Video', $bbit->localizationName)
				),
			);
			foreach ($opengraph_defaults as $k => $v){
				echo '<optgroup label="' . $k . '">';
				foreach ($v as $kk => $vv){
					echo 	'<option value="' . ( $kk ) . '" ' . ( $val == $kk ? 'selected="true"' : '' ) . '>' . ( $vv ) . '</option>';
				}
				echo '</optgroup>';
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

function bbit_TwitterCardsTypes( $istab = '' ) {
	global $bbit;
	
	ob_start();

	$post_types = get_post_types(array(
		'public'   => true
	));
	//$post_types['attachment'] = __('Images', $bbit->localizationName);
	//unset media - images | videos are treated as belonging to post, pages, custom post types
	unset($post_types['attachment'], $post_types['revision']);
	
	$options = $bbit->get_theoption('bbit_title_meta_format');
?>
<div class="bbit-form-row<?php echo ($istab!='' ? ' '.$istab : ''); ?>">
	<label>Default Twitter Cards Type:	</label>
	<div class="bbit-form-item large">
	<span class="formNote">&nbsp;</span>
	<?php
	foreach ($post_types as $key => $value){
		
		$val = '';
		if( isset($options['bbit_twc_cardstype_default']) && isset($options['bbit_twc_cardstype_default'][$key]) ){
			$val = $options['bbit_twc_cardstype_default'][$key];
		}
		?>
		<label for="bbit_twc_cardstype_default[<?php echo $key;?>]" style="display:inline;float:none;"><?php echo ucfirst(str_replace('_', ' ', $value));?>:</label>
		&nbsp;
		<select id="bbit_twc_cardstype_default[<?php echo $key;?>]" name="bbit_twc_cardstype_default[<?php echo $key;?>]" style="width:200px;">
			<option value="none">None</option>
			<?php
			$opengraph_defaults = array(
				'summary'				=> __('Summary Card', $bbit->localizationName),
				'summary_large_image'		=> __('Summary Card with Large Image', $bbit->localizationName),
				'photo'					=> __('Photo Card', $bbit->localizationName),
				'gallery'				=> __('Gallery Card', $bbit->localizationName),
				'player'				=> __('Player Card', $bbit->localizationName),
				'product'				=> __('Product Card', $bbit->localizationName)
			);
			foreach ($opengraph_defaults as $k => $v){
				echo 	'<option value="' . ( $k ) . '" ' . ( $val == $k ? 'selected="true"' : '' ) . '>' . ( $v ) . '</option>';
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

function bbit_TwitterCardsOptions( $istab = '', $type='' ) {
	global $bbit;
	
	$options = $bbit->get_theoption('bbit_title_meta_format');

	ob_start();
?>
	<div class="bbit-form-row<?php echo ($istab!='' ? ' '.$istab : ''); ?>" id="<?php echo $type=='home' ? 'bbit-twittercards-home-response' : 'bbit-twittercards-app-response'; ?>" style="position:relative;"></div>
	<script>
// Initialization and events code for the app
bbitTwitterCards_modoptions = (function ($) {
	"use strict";
	
	// public
	var debug_level = 0;
	var maincontainer = null;
	var loading = null;
	
	var ajaxurl = '<?php echo admin_url('admin-ajax.php');?>',
	type = '<?php echo $type; ?>';
	
	var ajaxBox = ( type=='home' ? $('#bbit-twittercards-home-response') : $('#bbit-twittercards-app-response') );
	
	// init function, autoload
	(function init() {
		// load the triggers
		$(document).ready(function(){
			maincontainer = $("#bbit-wrapper");
			loading = maincontainer.find("#main-loading");
	
			triggers();
		});
	})();
	
	function ajaxLoading()
	{
		var loading = $('<div id="bbit-ajaxLoadingBox" class="bbit-panel-widget">loading</div>');
		// append loading
		ajaxBox.html(loading);
	}
	
	function get_options( type ) {
			var __type = type || '';
			if ( $.trim(__type)=='' ) return false;
			
			ajaxLoading();

			var theTrigger = ( __type=='home' ? $('#bbit_twc_home_type') : $('#bbit_twc_site_app') ), theTriggerVal = theTrigger.val();
			var theResp = ajaxBox;

			if ( $.inArray(theTriggerVal, ['none', 'no']) > -1 ) {
				theResp.html('').hide();
				return false;
			}

			$.post(ajaxurl, {
				'action' 		: 'bbitTwitterCards',
				'sub_action'		: 'getCardTypeOptions',
				'card_type'		: __type=='home' ? $('#bbit_twc_home_type').val() : 'app',
				'page'			: __type=='home' ? 'home' : 'app'
			}, function(response) {

				if ( response.status == 'valid' ) {
					theResp.html( response.html ).show();
					bbitFreamwork.makeTabs();
					
					$('#bbit-twittercards-app-response').find('input#box_id, input#box_nonce').remove();
					$('#bbit-twittercards-home-response').find('input#box_id, input#box_nonce').remove();
					return true;
				}
				return false;
			}, 'json');		
	}
	
	// triggers
	function triggers()
	{
		get_options( type );

		if ( type=='home' ) {
			$('#bbit_twc_home_type').on('change', function (e) {
				e.preventDefault();
	
				get_options( type );
			});
		} else {
			$('#bbit_twc_site_app').on('change', function (e) {
				e.preventDefault();
	
				get_options( type );
			});
		}
	}
	
	// external usage
	return {
	}
})(jQuery);
	</script>
<?php
	$output = ob_get_contents();
	ob_end_clean();
	return $output;
}

function bbit_TwitterCardsImageFind($istab = '') {
	global $bbit;
	
	ob_start();

	$options = $bbit->get_theoption('bbit_title_meta_format');
	$val = '';
	if ( isset($options['bbit_twc_image_find']) ) {
		$val = $options['bbit_twc_image_find'];
	}
?>
<div class="bbit-form-row<?php echo ($istab!='' ? ' '.$istab : ''); ?>">
	<label>How to choose Image:</label>
	<div class="bbit-form-item large">
	<span class="formNote">&nbsp;</span>
		<select id="bbit_twc_image_find" name="bbit_twc_image_find" style="width:350px;">
			<?php
			$image_find = array(
				'content'				=> __('Choose first image from the post | page content', $bbit->localizationName),
				'featured'				=> __('Featured image for the post | page', $bbit->localizationName),
				'customfield'				=> __('Choose a custom field for image', $bbit->localizationName)
			);
			foreach ($image_find as $k => $v){
				echo 	'<option value="' . ( $k ) . '" ' . ( $val == $k ? 'selected="true"' : '' ) . '>' . ( $v ) . '</option>';
			}
			?>
		</select>&nbsp;&nbsp;&nbsp;&nbsp;
	</div>
	<div class="bbit-form-item small" style="margin-top:5px;">
		<span class="">Choose custom field:</span>&nbsp;
		<input id="bbit_twc_image_customfield" type="text" value="" name="bbit_twc_image_customfield">
	</div>
</div>
	<script>
// Initialization and events code for the app
bbitTwitterCards_image_find = (function ($) {
	"use strict";
	
	// init function, autoload
	(function init() {
		// load the triggers
		$(document).ready(function(){
			triggers();
		});
	})();
	
	function custom_field(val) {
		var cf = $('#bbit_twc_image_customfield'), cfp = cf.parent();
		
		if ( val =='customfield' ) {
			cfp.show();
		} else {
			cfp.hide();
		}
	}
	
	// triggers
	function triggers()
	{
		custom_field( $('#bbit_twc_image_find').val() );
		
		$('#bbit_twc_image_find').on('change', function (e) {
			e.preventDefault();
	
			custom_field( $(this).val() );
		});
	}
	
	// external usage
	return {
	}
})(jQuery);
	</script>
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
			'title_meta_format' => array(
				'title' 	=> __('Title & Meta Formats', $bbit->localizationName),
				'icon' 		=> '{plugin_folder_uri}assets/menu_icon.png',
				'size' 		=> 'grid_4', // grid_1|grid_2|grid_3|grid_4
				'header' 	=> false, // true|false
				'toggler' 	=> false, // true|false
				'buttons' 	=> true, // true|false
				'style' 	=> 'panel', // panel|panel-widget

				// tabs
				'tabs'	=> array(
					'__tab1'	=> array(__('Format Tags List', $bbit->localizationName), 'help_format_tags'),
					'__tab2'	=> array(__('Title Format', $bbit->localizationName), 'home_title,post_title,page_title,category_title,tag_title,taxonomy_title,archive_title,author_title,search_title,404_title,pagination_title,use_pagination_title'),
					'__tab3'	=> array(__('Meta Description', $bbit->localizationName), 'home_desc,post_desc,page_desc,category_desc,tag_desc,taxonomy_desc,archive_desc,author_desc,pagination_desc,use_pagination_desc'),
					'__tab4'	=> array(__('Meta Keywords', $bbit->localizationName), 'home_kw,post_kw,page_kw,category_kw,tag_kw,taxonomy_kw,archive_kw,author_kw,pagination_kw,use_pagination_kw'),
					'__tab5'	=> array(__('Meta Robots', $bbit->localizationName), 'home_robots,post_robots,page_robots,category_robots,tag_robots,taxonomy_robots,archive_robots,author_robots,search_robots,404_robots,pagination_robots,use_pagination_robots'),
					'__tab6'	=> array(__('Social Meta', $bbit->localizationName), 'social_use_meta,social_include_extra,social_validation_type,social_site_title,social_default_img,social_home_title,social_home_desc,social_home_img,social_home_type,social_opengraph_default'),
					'__tab7'	=> array(__('Twitter Cards', $bbit->localizationName), 'bbit_twc_use_meta,bbit_twc_website_account,bbit_twc_website_account_id,bbit_twc_creator_account,bbit_twc_creator_account_id,bbit_twc_default_img,bbit_twc_cardstype_default,bbit_twc_home_app,bbit_twc_home_type,bbit_twc_site_app,help_bbit_twc_post,help_bbit_twc_home,help_bbit_twc_app,bbit_twc_image_find,bbit_twc_thumb_sizes,bbit_twc_thumb_crop')
				),
				
				// create the box elements array
				'elements'	=> array(

					//=============================================================
					//== help
					'help_format_tags' => array(
						'type' 		=> 'message',
						
						'html' 		=> __('
							<h2>Thiết Lập Cơ Bản</h2>
							<p>You can set the custom page title using defined formats tags.</p>
							<h3>Available Format Tags</h3>
							<ul>
								<li><code>{site_title}</code> : the website\'s title (global availability)</li>
								<li><code>{site_description}</code> : the website\'s description (global availability)</li>
								<li><code>{current_date}</code> : current date (global availability)</li>
								<li><code>{current_time}</code> : current time (global availability)</li>
								<li><code>{current_day}</code> : current day (global availability)</li>
								<li><code>{current_year}</code> : current year (global availability)</li>
								<li><code>{current_month}</code> : current month (global availability)</li>
								<li><code>{current_week_day}</code> : current day of the week (global availability)</li>
								
								
								<li><code>{title}</code> : the page|post title (global availability)</li>
								<li><code>{id}</code> : the page|post id (specific availability)</li>
								<li><code>{date}</code> : the page|post date (specific availability)</li>
								<li><code>{description}</code> - the page|post full description (specific availability)</li>
								<li><code>{short_description}</code> - the page|post excerpt or if excerpt does not exist, 200 character maximum are retrieved from description (specific availability)</li>
								<li><code>{parent}</code> - the page|post parent title (specific availability)</li>
								<li><code>{author}</code> - the page|post author name (specific availability)</li>
								<li><code>{author_username}</code> - the page|post author username (specific availability)</li>
								<li><code>{author_nickname}</code> - the page|post author nickname (specific availability)</li>
								<li><code>{author_description}</code> - the page|post author biographical Info (specific availability)</li>
								<li><code>{categories}</code> : the post categories names list separated by comma (specific availability)</li>
								<li><code>{tags}</code> : the post tags names list separated by comma (specific availability)</li>
								<li><code>{terms}</code> : the post custom taxonomies terms names list separated by comma (specific availability)</li>
								<li><code>{category}</code> - the category name or the post first found category name (specific availability)</li>
								<li><code>{category_description}</code> - the category description or the post first found category description (specific availability)</li>
								<li><code>{tag}</code> - the tag name or the post first found tag name (specific availability)</li>
								<li><code>{tag_description}</code> - the tag description or the post first found tag description (specific availability)</li>
								<li><code>{term}</code> - the term name or the post first found custom taxonomy term name (specific availability)</li>
								<li><code>{term_description}</code> - the term description or the post first found custom taxonomy term description (specific availability)</li>
								<li><code>{search_keyword}</code> : the word(s) used for search (specific availability)</li>
								<li><code>{keywords}</code> : the post|page keywords already defined (specific availability)</li>
								<li><code>{focus_keywords}</code> : the post|page focus keywords already defined (specific availability)</li>
								<li><code>{totalpages}</code> - the total number of pages (if pagination is used), default value is 1 (specific availability)</li>
								<li><code>{pagenumber}</code> - the page number (if pagination is used), default value is 1 (specific availability)</li>
							</ul><br />
							<p>Info: when use {keywords}, if for a specific post|page {focus_keywords} is found then it is used, otherwise {keywords} remains active</p>
							', $bbit->localizationName)
					),

					//=============================================================
					//== title format
					'home_title' 	=> array(
						'type' 		=> 'text',
						'std' 		=> '{site_title}',
						'size' 		=> 'large',
						'force_width'=> '400',
						'title' 	=> __('Trang Chủ', $bbit->localizationName),
						'desc' 		=> __('Các thẻ có sẵn: (global availability) tags', $bbit->localizationName)
					),
					'post_title'			=> array(
						'type' 		=> 'text',
						'std' 		=> '{title} | {site_title}',
						'size' 		=> 'large',
						'force_width'=> '400',
						'title' 	=> __('Bài Viết', $bbit->localizationName),
						'desc' 		=> __('Các thẻ có sẵn: (global availability) tags; (specific availability) tags:', $bbit->localizationName) . ' {id} {date} {description} {short_description} {parent} {author} {author_username} {author_nickname} {categories} {tags} {terms} {category} {category_description} {tag} {tag_description} {term} {term_description} {keywords} {focus_keywords}'
					),
					'page_title'	=> array(
						'type' 		=> 'text',
						'std' 		=> '{title} | {site_title}',
						'size' 		=> 'large',
						'force_width'=> '400',
						'title' 	=> __('Trang Tĩnh', $bbit->localizationName),
						'desc' 		=> __('Các thẻ có sẵn: (global availability) tags; (specific availability) tags:', $bbit->localizationName) . ' {id} {date} {description} {short_description} {parent} {author} {author_username} {author_nickname} {categories} {tags} {terms} {category} {category_description} {tag} {tag_description} {term} {term_description} {keywords} {focus_keywords}'
					),
					'category_title'=> array(
						'type' 		=> 'text',
						'std' 		=> '{title} | {site_title}',
						'size' 		=> 'large',
						'force_width'=> '400',
						'title' 	=> __('Danh Mục', $bbit->localizationName),
						'desc' 		=> __('Các thẻ có sẵn: (global availability) tags; (specific availability) tags:', $bbit->localizationName) . ' {category} {category_description}'
					),
					'tag_title'=> array(
						'type' 		=> 'text',
						'std' 		=> '{title} | {site_title}',
						'size' 		=> 'large',
						'force_width'=> '400',
						'title' 	=> __('Tag', $bbit->localizationName),
						'desc' 		=> __('Các thẻ có sẵn: (global availability) tags; (specific availability) tags:', $bbit->localizationName) . ' {tag} {tag_description}'
					),
					'taxonomy_title'=> array(
						'type' 		=> 'text',
						'std' 		=> '{title} | {site_title}',
						'size' 		=> 'large',
						'force_width'=> '400',
						'title' 	=> __('Custom Taxonomy', $bbit->localizationName),
						'desc' 		=> __('Các thẻ có sẵn: (global availability) tags; (specific availability) tags:', $bbit->localizationName) . ' {term} {term_description}'
					),
					'archive_title'=> array(
						'type' 		=> 'text',
						'std' 		=> '{title} ' . __('Archives', $bbit->localizationName) . ' | {site_title}',
						'size' 		=> 'large',
						'force_width'=> '400',
						'title' 	=> __('Archives', $bbit->localizationName),
						'desc' 		=> __('Các thẻ có sẵn: (global availability) tags; (specific availability) tags:', $bbit->localizationName) . ' {date} ' . __('- is based on archive type: per year or per month,year or per day,month,year', $bbit->localizationName)
					),
					'author_title'	=> array(
						'type' 		=> 'text',
						'std' 		=> '{title} | {site_title}',
						'size' 		=> 'large',
						'force_width'=> '400',
						'title' 	=> __('Trang Tác Giả', $bbit->localizationName),
						'desc' 		=> __('Các thẻ có sẵn: (global availability) tags; (specific availability) tags:', $bbit->localizationName) . ' {author} {author_username} {author_nickname}'
					),
					'search_title'	=> array(
						'type' 		=> 'text',
						'std' 		=> __('Search for ', $bbit->localizationName) . '{search_keyword} | {site_title}',
						'size' 		=> 'large',
						'force_width'=> '400',
						'title' 	=> __('Tìm Kiếm', $bbit->localizationName),
						'desc' 		=> __('Các thẻ có sẵn: (global availability) tags; (specific availability) tags:', $bbit->localizationName) . ' {search_keyword}'
					),
					'404_title'		=> array(
						'type' 		=> 'text',
						'std' 		=> __('404 Page Not Found |', $bbit->localizationName) . ' {site_title}',
						'size' 		=> 'large',
						'force_width'=> '400',
						'title' 	=> __('404 Page Not Found', $bbit->localizationName),
						'desc' 		=> __('Các thẻ có sẵn: (global availability) tags', $bbit->localizationName)
					),
					'pagination_title'=> array(
						'type' 		=> 'text',
						'std' 		=> '{title} ' . __('- Page', $bbit->localizationName) . ' {pagenumber} ' . __('of', $bbit->localizationName) . ' {totalpages} | {site_title}',
						'size' 		=> 'large',
						'force_width'=> '400',
						'title' 	=> __('Phân Trang', $bbit->localizationName),
						'desc' 		=> __('Các thẻ có sẵn: (global availability) tags; (specific availability) tags:', $bbit->localizationName) . ' {totalpages} {pagenumber}'
					),
					'use_pagination_title' => array(
						'type' 		=> 'select',
						'std' 		=> 'no',
						'size' 		=> 'large',
						'force_width'=> '120',
						'title' 	=> __('Sử dụng Phân Trang trong tiêu đề', $bbit->localizationName),
						'desc' 		=> __('Choose Yes if you want to use Pagination Title Format in pages where it can be applied!', $bbit->localizationName),
						'options'	=> array(
							'yes' 	=> __('YES', $bbit->localizationName),
							'no' 	=> __('NO', $bbit->localizationName)
						)
					),
					
					//=============================================================
					//== meta description
					'home_desc' 	=> array(
						'type' 		=> 'textarea',
						'std' 		=> '{site_description}',
						'size' 		=> 'large',
						'force_width'=> '400',
						'title' 	=> __('Trang chủ Description:', $bbit->localizationName),
						'desc' 		=> __('Các thẻ có sẵn: (global availability) tags', $bbit->localizationName)
					),
					'post_desc'			=> array(
						'type' 		=> 'textarea',
						'std' 		=> '{description} | {site_description}',
						'size' 		=> 'large',
						'force_width'=> '400',
						'title' 	=> __('Bài Viết Description:', $bbit->localizationName),
						'desc' 		=> __('Các thẻ có sẵn: (global availability) tags; (specific availability) tags:', $bbit->localizationName) . ' {id} {date} {description} {short_description} {parent} {author} {author_username} {author_nickname} {categories} {tags} {terms} {category} {category_description} {tag} {tag_description} {term} {term_description} {keywords} {focus_keywords}'
					),
					'page_desc'	=> array(
						'type' 		=> 'textarea',
						'std' 		=> '{description} | {site_description}',
						'size' 		=> 'large',
						'force_width'=> '400',
						'title' 	=> __('Trang Description:', $bbit->localizationName),
						'desc' 		=> __('Các thẻ có sẵn: (global availability) tags; (specific availability) tags:', $bbit->localizationName) . ' {id} {date} {description} {short_description} {parent} {author} {author_username} {author_nickname} {categories} {tags} {terms} {category} {category_description} {tag} {tag_description} {term} {term_description} {keywords} {focus_keywords}'
					),
					'category_desc'=> array(
						'type' 		=> 'textarea',
						'std' 		=> '{category_description}',
						'size' 		=> 'large',
						'force_width'=> '400',
						'title' 	=> __('Danh Mục Description:', $bbit->localizationName),
						'desc' 		=> __('Các thẻ có sẵn: (global availability) tags; (specific availability) tags:', $bbit->localizationName) . ' {category} {category_description}'
					),
					'tag_desc'=> array(
						'type' 		=> 'textarea',
						'std' 		=> '{tag_description}',
						'size' 		=> 'large',
						'force_width'=> '400',
						'title' 	=> __('Tag Description:', $bbit->localizationName),
						'desc' 		=> __('Các thẻ có sẵn: (global availability) tags; (specific availability) tags:', $bbit->localizationName) . ' {tag} {tag_description}'
					),
					'taxonomy_desc'=> array(
						'type' 		=> 'textarea',
						'std' 		=> '{term_description}',
						'size' 		=> 'large',
						'force_width'=> '400',
						'title' 	=> __('Custom Taxonomy Description:', $bbit->localizationName),
						'desc' 		=> __('Các thẻ có sẵn: (global availability) tags; (specific availability) tags:', $bbit->localizationName) . ' {term} {term_description}'
					),
					'archive_desc'=> array(
						'type' 		=> 'textarea',
						'std' 		=> '',
						'size' 		=> 'large',
						'force_width'=> '400',
						'title' 	=> __('Archives Description:', $bbit->localizationName),
						'desc' 		=> __('Các thẻ có sẵn: (global availability) tags; (specific availability) tags:', $bbit->localizationName) . ' {date} ' . __('- is based on archive type: per year or per month,year or per day,month,year', $bbit->localizationName)
					),
					'author_desc'	=> array(
						'type' 		=> 'textarea',
						'std' 		=> '',
						'size' 		=> 'large',
						'force_width'=> '400',
						'title' 	=> __('Tác Giả Description:', $bbit->localizationName),
						'desc' 		=> __('Các thẻ có sẵn: (global availability) tags; (specific availability) tags:', $bbit->localizationName) . ' {author} {author_username} {author_nickname} {author_description}'
					),
					'pagination_desc'=> array(
						'type' 		=> 'textarea',
						'std' 		=> __('Page {pagenumber}', $bbit->localizationName),
						'size' 		=> 'large',
						'force_width'=> '400',
						'title' 	=> __('Phân Trang Description:', $bbit->localizationName),
						'desc' 		=> __('Các thẻ có sẵn: (global availability) tags; (specific availability) tags:', $bbit->localizationName) . ' {totalpages} {pagenumber}'
					),
					'use_pagination_desc' => array(
						'type' 		=> 'select',
						'std' 		=> 'no',
						'size' 		=> 'large',
						'force_width'=> '120',
						'title' 	=> __('Sử dụng Phân Trang trong tiêu đề', $bbit->localizationName),
						'desc' 		=> __('Choose Yes if you want to use Pagination Meta Description in pages where it can be applied!', $bbit->localizationName),
						'options'	=> array(
							'yes' 	=> __('YES', $bbit->localizationName),
							'no' 	=> __('NO', $bbit->localizationName)
						)
					),
					
					//=============================================================
					//== meta keywords
					'home_kw' 	=> array(
						'type' 		=> 'text',
						'std' 		=> '{keywords}',
						'size' 		=> 'large',
						'force_width'=> '400',
						'title' 	=> __('Homepage Meta Keywords:', $bbit->localizationName),
						'desc' 		=> __('Các thẻ có sẵn: (global availability) tags', $bbit->localizationName)
					),
					'post_kw'			=> array(
						'type' 		=> 'text',
						'std' 		=> '{keywords}',
						'size' 		=> 'large',
						'force_width'=> '400',
						'title' 	=> __('Post Meta Keywords:', $bbit->localizationName),
						'desc' 		=> __('Các thẻ có sẵn: (global availability) tags; (specific availability) tags:', $bbit->localizationName) . ' {id} {date} {description} {short_description} {parent} {author} {author_username} {author_nickname} {categories} {tags} {terms} {category} {category_description} {tag} {tag_description} {term} {term_description} {keywords} {focus_keywords}'
					),
					'page_kw'	=> array(
						'type' 		=> 'text',
						'std' 		=> '{keywords}',
						'size' 		=> 'large',
						'force_width'=> '400',
						'title' 	=> __('Page Meta Keywords:', $bbit->localizationName),
						'desc' 		=> __('Các thẻ có sẵn: (global availability) tags; (specific availability) tags:', $bbit->localizationName) . ' {id} {date} {description} {short_description} {parent} {author} {author_username} {author_nickname} {categories} {tags} {terms} {category} {category_description} {tag} {tag_description} {term} {term_description} {keywords} {focus_keywords}'
					),
					'category_kw'=> array(
						'type' 		=> 'text',
						'std' 		=> '{keywords}',
						'size' 		=> 'large',
						'force_width'=> '400',
						'title' 	=> __('Category Meta Keywords:', $bbit->localizationName),
						'desc' 		=> __('Các thẻ có sẵn: (global availability) tags; (specific availability) tags:', $bbit->localizationName) . ' {category} {category_description}'
					),
					'tag_kw'=> array(
						'type' 		=> 'text',
						'std' 		=> '{keywords}',
						'size' 		=> 'large',
						'force_width'=> '400',
						'title' 	=> __('Tag Meta Keywords:', $bbit->localizationName),
						'desc' 		=> __('Các thẻ có sẵn: (global availability) tags; (specific availability) tags:', $bbit->localizationName) . ' {tag} {tag_description}'
					),
					'taxonomy_kw'=> array(
						'type' 		=> 'text',
						'std' 		=> '{keywords}',
						'size' 		=> 'large',
						'force_width'=> '400',
						'title' 	=> __('Custom Taxonomy Meta Keywords:', $bbit->localizationName),
						'desc' 		=> __('Các thẻ có sẵn: (global availability) tags; (specific availability) tags:', $bbit->localizationName) . ' {term} {term_description}'
					),
					'archive_kw'=> array(
						'type' 		=> 'text',
						'std' 		=> '',
						'size' 		=> 'large',
						'force_width'=> '400',
						'title' 	=> __('Archives Meta Keywords:', $bbit->localizationName),
						'desc' 		=> __('Các thẻ có sẵn: (global availability) tags; (specific availability) tags:', $bbit->localizationName) . ' {date} ' . __('- is based on archive type: per year or per month,year or per day,month,year', $bbit->localizationName)
					),
					'author_kw'	=> array(
						'type' 		=> 'text',
						'std' 		=> '',
						'size' 		=> 'large',
						'force_width'=> '400',
						'title' 	=> __('Author Meta Keywords:', $bbit->localizationName),
						'desc' 		=> __('Các thẻ có sẵn: (global availability) tags; (specific availability) tags:', $bbit->localizationName) . ' {author} {author_username} {author_nickname}'
					),
					'pagination_kw'=> array(
						'type' 		=> 'text',
						'std' 		=> '',
						'size' 		=> 'large',
						'force_width'=> '400',
						'title' 	=> __('Pagination Meta Keywords:', $bbit->localizationName),
						'desc' 		=> __('Các thẻ có sẵn: (global availability) tags; (specific availability) tags:', $bbit->localizationName) . ' {totalpages} {pagenumber}'
					),
					'use_pagination_kw' => array(
						'type' 		=> 'select',
						'std' 		=> 'no',
						'size' 		=> 'large',
						'force_width'=> '120',
						'title' 	=> __('Sử dụng Phân Trang trong tiêu đề', $bbit->localizationName),
						'desc' 		=> __('Choose Yes if you want to use Pagination Meta Keywords in pages where it can be applied!', $bbit->localizationName),
						'options'	=> array(
							'yes' 	=> __('YES', $bbit->localizationName),
							'no' 	=> __('NO', $bbit->localizationName)
						)
					),
					
					//=============================================================
					//== meta robots
					'home_robots' 	=> array(
						'type' 		=> 'multiselect',
						'std' 		=> array(),
						'size' 		=> 'small',
						'force_width'=> '400',
						'title' 	=> __('Homepage Meta Robots:', $bbit->localizationName),
						'desc' 		=> __('if you do not select "noindex" => "index" is by default active; if you do not select "nofollow" => "follow" is by default active', $bbit->localizationName),
						'options'	=> $__metaRobotsList
					),
					'post_robots'	=> array(
						'type' 		=> 'multiselect',
						'std' 		=> array(),
						'size' 		=> 'small',
						'force_width'=> '400',
						'title' 	=> __('Post Meta Robots:', $bbit->localizationName),
						'desc' 		=> __('if you do not select "noindex" => "index" is by default active; if you do not select "nofollow" => "follow" is by default active', $bbit->localizationName),
						'options'	=> $__metaRobotsList
					),
					'page_robots'	=> array(
						'type' 		=> 'multiselect',
						'std' 		=> array(),
						'size' 		=> 'small',
						'force_width'=> '400',
						'title' 	=> __('Page Meta Robots:', $bbit->localizationName),
						'desc' 		=> __('if you do not select "noindex" => "index" is by default active; if you do not select "nofollow" => "follow" is by default active', $bbit->localizationName),
						'options'	=> $__metaRobotsList
					),
					'category_robots'=> array(
						'type' 		=> 'multiselect',
						'std' 		=> array(),
						'size' 		=> 'small',
						'force_width'=> '400',
						'title' 	=> __('Category Meta Robots:', $bbit->localizationName),
						'desc' 		=> __('if you do not select "noindex" => "index" is by default active; if you do not select "nofollow" => "follow" is by default active', $bbit->localizationName),
						'options'	=> $__metaRobotsList
					),
					'tag_robots'=> array(
						'type' 		=> 'multiselect',
						'std' 		=> array(),
						'size' 		=> 'small',
						'force_width'=> '400',
						'title' 	=> __('Tag Meta Robots:', $bbit->localizationName),
						'desc' 		=> __('if you do not select "noindex" => "index" is by default active; if you do not select "nofollow" => "follow" is by default active', $bbit->localizationName),
						'options'	=> $__metaRobotsList
					),
					'taxonomy_robots'=> array(
						'type' 		=> 'multiselect',
						'std' 		=> array(),
						'size' 		=> 'small',
						'force_width'=> '400',
						'title' 	=> __('custom Taxonomy Meta Robots:', $bbit->localizationName),
						'desc' 		=> __('if you do not select "noindex" => "index" is by default active; if you do not select "nofollow" => "follow" is by default active', $bbit->localizationName),
						'options'	=> $__metaRobotsList
					),
					'archive_robots'=> array(
						'type' 		=> 'multiselect',
						'std' 		=> array('noindex','nofollow','noarchive','noodp'),
						'size' 		=> 'small',
						'force_width'=> '400',
						'title' 	=> __('Archives Meta Robots:', $bbit->localizationName),
						'desc' 		=> __('if you do not select "noindex" => "index" is by default active; if you do not select "nofollow" => "follow" is by default active', $bbit->localizationName),
						'options'	=> $__metaRobotsList
					),
					'author_robots'	=> array(
						'type' 		=> 'multiselect',
						'std' 		=> array('noindex','nofollow','noarchive','noodp'),
						'size' 		=> 'small',
						'force_width'=> '400',
						'title' 	=> __('Author Meta Robots:', $bbit->localizationName),
						'desc' 		=> __('if you do not select "noindex" => "index" is by default active; if you do not select "nofollow" => "follow" is by default active', $bbit->localizationName),
						'options'	=> $__metaRobotsList
					),
					'search_robots'	=> array(
						'type' 		=> 'multiselect',
						'std' 		=> array('noindex','nofollow','noarchive','noodp'),
						'size' 		=> 'small',
						'force_width'=> '400',
						'title' 	=> __('Search Robots:', $bbit->localizationName),
						'desc' 		=> __('if you do not select "noindex" => "index" is by default active; if you do not select "nofollow" => "follow" is by default active', $bbit->localizationName),
						'options'	=> $__metaRobotsList
					),
					'404_robots'		=> array(
						'type' 		=> 'multiselect',
						'std' 		=> array('noindex','nofollow','noarchive','noodp'),
						'size' 		=> 'small',
						'force_width'=> '400',
						'title' 	=> __('404 Page Not Found Robots:', $bbit->localizationName),
						'desc' 		=> __('if you do not select "noindex" => "index" is by default active; if you do not select "nofollow" => "follow" is by default active', $bbit->localizationName),
						'options'	=> $__metaRobotsList
					),
					'pagination_robots'=> array(
						'type' 		=> 'multiselect',
						'std' 		=> array('noindex','nofollow','noarchive','noodp'),
						'size' 		=> 'small',
						'force_width'=> '400',
						'title' 	=> __('Pagination Meta Robots:', $bbit->localizationName),
						'desc' 		=> __('if you do not select "noindex" => "index" is by default active; if you do not select "nofollow" => "follow" is by default active', $bbit->localizationName),
						'options'	=> $__metaRobotsList
					),
					'use_pagination_robots' => array(
						'type' 		=> 'select',
						'std' 		=> 'no',
						'size' 		=> 'large',
						'force_width'=> '120',
						'title' 	=> __('Sử dụng Phân Trang trong tiêu đề', $bbit->localizationName),
						'desc' 		=> __('Choose Yes if you want to use Pagination Meta Robots in pages where it can be applied!', $bbit->localizationName),
						'options'	=> array(
							'yes' 	=> __('YES', $bbit->localizationName),
							'no' 	=> __('NO', $bbit->localizationName)
						)
					),
					
					//=============================================================
					//== social tags
					
					'social_use_meta' => array(
						'type' 		=> 'select',
						'std' 		=> 'no',
						'size' 		=> 'large',
						'force_width'=> '120',
						'title' 	=> __('Use Social Meta Tags:', $bbit->localizationName),
						'desc' 		=> __('Choose Yes if you want to use Facebook Open Graph Social Meta Tags in all your pages! If you choose No, you can still activate tags for a post or page in it\'s meta box.', $bbit->localizationName),
						'options'	=> array(
							'yes' 	=> __('YES', $bbit->localizationName),
							'no' 	=> __('NO', $bbit->localizationName)
						)
					),
					'social_include_extra' => array(
						'type' 		=> 'select',
						'std' 		=> 'no',
						'size' 		=> 'large',
						'force_width'=> '120',
						'title' 	=> __('Include extra tags:', $bbit->localizationName),
						'desc' 		=> __('Choose Yes if you want to include the following &lt;article:published_time&gt;, &lt;article:modified_time&gt;, &lt;article:author&gt; tags for your posts.', $bbit->localizationName),
						'options'	=> array(
							'yes' 	=> __('YES', $bbit->localizationName),
							'no' 	=> __('NO', $bbit->localizationName)
						)
					),
					'social_validation_type' => array(
						'type' 		=> 'select',
						'std' 		=> 'no',
						'size' 		=> 'large',
						'force_width'=> '120',
						'title' 	=> __('Code Validation Type:', $bbit->localizationName),
						'desc' 		=> '',
						'options'	=> array(
							'opengraph' 	=> 'opengraph',
							'xhtml' 		=> 'xhtml',
							'html5'			=> 'html5'
						)
					),
					'social_site_title' => array(
						'type' 		=> 'text',
						'std' 		=> get_bloginfo('name'),
						'size' 		=> 'large',
						'force_width'=> '400',
						'title' 	=> __('Site Name:', $bbit->localizationName),
						'desc' 		=> __('&nbsp;', $bbit->localizationName)
					),
					'social_default_img' => array(
						'type' 		=> 'upload_image',
						'size' 		=> 'large',
						'title' 	=> __('Default Image:', $bbit->localizationName),
						'value' 	=> __('Upload image', $bbit->localizationName),
						'thumbSize' => array(
							'w' => '100',
							'h' => '100',
							'zc' => '2',
						),
						'desc' 		=> __('Here you can specify an image URL or an image from your media library to use as a default image in the event that there is no image otherwise specified for a given webpage on your site.', $bbit->localizationName),
					),
					
					'social_home_title' 	=> array(
						'type' 		=> 'text',
						'std' 		=> get_bloginfo('name'),
						'size' 		=> 'large',
						'force_width'=> '400',
						'title' 	=> __('Homepage Title:', $bbit->localizationName),
						'desc' 		=> '&nbsp;'
					),
					'social_home_desc' 	=> array(
						'type' 		=> 'textarea',
						'std' 		=> get_bloginfo('description'),
						'size' 		=> 'small',
						'force_width'=> '400',
						'title' 	=> __('Homepage Description:', $bbit->localizationName),
						'desc' 		=> '&nbsp;'
					),
					'social_home_img' => array(
						'type' 		=> 'upload_image',
						'size' 		=> 'large',
						'title' 	=> __('Homepage Image:', $bbit->localizationName),
						'value' 	=> __('Upload image', $bbit->localizationName),
						'thumbSize' => array(
							'w' => '100',
							'h' => '100',
							'zc' => '2',
						),
						'desc' 		=> '&nbsp;',
					),
					'social_home_type' => array(
						'type' 		=> 'select',
						'std' 		=> 'no',
						'size' 		=> 'large',
						'force_width'=> '120',
						'title' 	=> __('Homepage OpenGraph Type:', $bbit->localizationName),
						'desc' 		=> '&nbsp;',
						'options'	=> array(
							'blog'					=> __('Blog', $bbit->localizationName),
							'profile'				=> __('Profile', $bbit->localizationName),
							'website'				=> __('Website', $bbit->localizationName)
						)
					),
					
					'social_opengraph_default' => array(
						'type' 		=> 'html',
						'html' 		=> bbit_OpenGraphTypes( '__tab6' )
					),
					
					//=============================================================
					//== twitter cards
					
					'bbit_twc_use_meta' => array(
						'type' 		=> 'select',
						'std' 		=> 'no',
						'size' 		=> 'large',
						'force_width'=> '120',
						'title' 	=> __('Use Twitter Cards Meta Tags:', $bbit->localizationName),
						'desc' 		=> __('Choose Yes if you want to use Twitter Cards Meta Tags in all your pages! If you choose No, you can still activate tags for a post or page in it\'s meta box.', $bbit->localizationName),
						'options'	=> array(
							'yes' 	=> __('YES', $bbit->localizationName),
							'no' 	=> __('NO', $bbit->localizationName)
						)
					),

					'bbit_twc_website_account' 	=> array(
						'type' 		=> 'text',
						'std' 		=> '',
						'size' 		=> 'large',
						'force_width'=> '400',
						'title' 	=> __('Website Twitter Account:', $bbit->localizationName),
						'desc' 		=> '(optional) <twitter:site> @username for the website used in the card footer.'
					),
					
					'bbit_twc_website_account_id' 	=> array(
						'type' 		=> 'text',
						'std' 		=> '',
						'size' 		=> 'large',
						'force_width'=> '400',
						'title' 	=> __('Website Twitter Account Id:', $bbit->localizationName),
						'desc' 		=> '(optional) <twitter:site:id> the website\'s Twitter user ID instead of @username. Note that user ids never change, while @usernames can be changed by the user.'
					),
					
					'bbit_twc_creator_account' 	=> array(
						'type' 		=> 'text',
						'std' 		=> '',
						'size' 		=> 'large',
						'force_width'=> '400',
						'title' 	=> __('Content Creator Twitter Account:', $bbit->localizationName),
						'desc' 		=> '(optional) <twitter:creator> @username for the content creator / author.'
					),
					
					'bbit_twc_creator_account_id' 	=> array(
						'type' 		=> 'text',
						'std' 		=> '',
						'size' 		=> 'large',
						'force_width'=> '400',
						'title' 	=> __('Content Creator Twitter Account Id:', $bbit->localizationName),
						'desc' 		=> '(optional) <twitter:creator:id> the Twitter user\'s ID for the content creator / author instead of @username.'
					),
					
					'bbit_twc_default_img' => array(
						'type' 		=> 'upload_image',
						'size' 		=> 'large',
						'title' 	=> __('Default Image:', $bbit->localizationName),
						'value' 	=> __('Upload image', $bbit->localizationName),
						'thumbSize' => array(
							'w' => '100',
							'h' => '100',
							'zc' => '2',
						),
						'desc' 		=> __('Here you can specify an image URL or an image from your media library to use as a default image in the event that there is no image otherwise specified for a given webpage on your site.', $bbit->localizationName),
					),
					
					'help_bbit_twc_post' => array(
						'type' 		=> 'message',
						
						'html' 		=> __('
							<h2>Posts | Page info - section</h2>
							<ul>
								<li>- For the following twitter card types (<strong>Summary Card, Summary Card with Large Image, Photo Card</strong>), if you don\'t complete the Title, Description, Image fields from the Post | Page Seo Setting box / Twitter Cards tab, they will be auto filled with information from the post or page title, content, image.</li>
								<li>- For the following twitter card types (<strong>Gallery Card, Player Card, Product Card</strong>), you need to complete the mandatory fields for the card type per every post or page which you want to be relationated with twitter - these fields cannot be auto filled.</li>
							</ul><br />
							', $bbit->localizationName)
					),
					
					'bbit_twc_cardstype_default' => array(
						'type' 		=> 'html',
						'html' 		=> bbit_TwitterCardsTypes( '__tab7' )
					),
					
					'bbit_twc_image_find' => array(
						'type' 		=> 'html',
						'html' 		=> bbit_TwitterCardsImageFind( '__tab7' )
					),
					
					'bbit_twc_thumb_sizes' => array(
						'type' 		=> 'select',
						'std' 		=> '120x120',
						'size' 		=> 'large',
						'force_width'  => '450',
						'title' 		=> __('Image Thumbnails sizes:', $bbit->localizationName),
						'desc' 		=> '&nbsp;',
						'options'	=> array(
							'none'		=> __('Don\'t make a thumbnail from the image', $bbit->localizationName),
							'435x375' => __('Web: height is 375px, width is 435px', $bbit->localizationName),
							'280x375' => __('Mobile (non-retina displays): height is 375px, width is 280px', $bbit->localizationName),
							'560x750' => __('Mobile (retina displays): height is 750px, width is 560px', $bbit->localizationName),
							'280x150' => __('Small: height is 150px, width is 280px', $bbit->localizationName),
							'120x120' => __('Smallest: height is 120px, width is 120px', $bbit->localizationName)
						)
					),
					
					'bbit_twc_thumb_crop' => array(
						'type' 		=> 'select',
						'std' 		=> 'no',
						'size' 		=> 'large',
						'force_width'	=> '120',
						'title' 	=> __('Force Crop on card type Image?', $bbit->localizationName),
						'desc' 		=> __('Choose Yes if you want to force crop on your twitter card type chosen image.', $bbit->localizationName),
						'options'	=> array(
							'yes' 	=> __('YES', $bbit->localizationName),
							'no' 	=> __('NO', $bbit->localizationName)
						)
					),
					
					'help_bbit_twc_app' => array(
						'type' 		=> 'message',
						
						'html' 		=> __('
							<h2>Website Generic App Twitter Card Type - section</h2>
							<ul>
								<li>Choose if you want to add an app twitter card type to your website.</li>
							</ul><br />
							', $bbit->localizationName)
					),
					
					'bbit_twc_site_app' => array(
						'type' 		=> 'select',
						'std' 		=> 'no',
						'size' 		=> 'large',
						'force_width'=> '120',
						'title' 	=> __('Add Twitter App Card Type: ', $bbit->localizationName),
						'desc' 		=> __('Add Twitter App Card Type', $bbit->localizationName),
						'options'	=> array(
							'yes' 	=> __('YES', $bbit->localizationName),
							'no' 	=> __('NO', $bbit->localizationName)
						)
					),
					
					'bbit_twc_site_app_options' => array(
						'type' 		=> 'html',
						'html' 		=> bbit_TwitterCardsOptions( '__tab7', 'app' )
					),
					
					'help_bbit_twc_home' => array(
						'type' 		=> 'message',
						
						'html' 		=> __('
							<h2>Homepage - section</h2>
							<ul>
								<li>- Choose the twitter card type for your homepage.</li>
								<li>- Also choose if you want to also add an app twitter card type for the homepage (the options from the above App Twitter Card Type section will be used)</li>
							</ul><br />
							', $bbit->localizationName)
					),
					
					'bbit_twc_home_app' => array(
						'type' 		=> 'select',
						'std' 		=> 'no',
						'size' 		=> 'large',
						'force_width'=> '120',
						'title' 	=> __('Homepage Add Twitter App Card Type: ', $bbit->localizationName),
						'desc' 		=> __('Add Twitter App Card Type to Homepage', $bbit->localizationName),
						'options'	=> array(
							'yes' 	=> __('YES', $bbit->localizationName),
							'no' 	=> __('NO', $bbit->localizationName)
						)
					),
					
					'bbit_twc_home_type' => array(
						'type' 		=> 'select',
						'std' 		=> 'none',
						'size' 		=> 'large',
						'force_width'=> '200',
						'title' 	=> __('Homepage Twitter Card Type:', $bbit->localizationName),
						'desc' 		=> '&nbsp;',
						'options'	=> array(
							'none'					=> __('None', $bbit->localizationName),
							'summary'				=> __('Summary Card', $bbit->localizationName),
							'summary_large_image'		=> __('Summary Card with Large Image', $bbit->localizationName),
							'photo'					=> __('Photo Card', $bbit->localizationName),
							'gallery'				=> __('Gallery Card', $bbit->localizationName),
							'player'				=> __('Player Card', $bbit->localizationName),
							'product'				=> __('Product Card', $bbit->localizationName)
						)
					),
					
					'bbit_twc_home_options' => array(
						'type' 		=> 'html',
						'html' 		=> bbit_TwitterCardsOptions( '__tab7', 'home' )
					)
					
					/*'bbit_twc_home_title' 	=> array(
						'type' 		=> 'text',
						'std' 		=> get_bloginfo('name'),
						'size' 		=> 'large',
						'force_width'=> '400',
						'title' 	=> __('Homepage Title:', $bbit->localizationName),
						'desc' 		=> 'Title should be concise and will be truncated at 70 characters.'
					),
					'bbit_twc_home_desc' 	=> array(
						'type' 		=> 'textarea',
						'std' 		=> get_bloginfo('description'),
						'size' 		=> 'large',
						'force_width'=> '400',
						'title' 	=> __('Homepage Description:', $bbit->localizationName),
						'desc' 		=> 'A description that concisely summarizes the content of the page, as appropriate for presentation within a Tweet. Do not re-use the title text as the description, or use this field to describe the general services provided by the website. Description text will be truncated at the word to 200 characters.'
					),
					'bbit_twc_home_img' => array(
						'type' 		=> 'upload_image',
						'size' 		=> 'large',
						'title' 	=> __('Homepage Image:', $bbit->localizationName),
						'value' 	=> __('Upload image', $bbit->localizationName),
						'thumbSize' => array(
							'w' => '100',
							'h' => '100',
							'zc' => '2',
						),
						'desc' 		=> 'Image must be less than 1MB in size.',
					),*/
					
				)
			)
		)
	)
);