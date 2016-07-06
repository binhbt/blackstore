<?php
if ( !defined( 'DB_NAME' ) ) {
	header( 'HTTP/1.0 403 Forbidden' );
	die;
}

// Raw Shortcode
function my_formatter($content) {
	$new_content = '';
	$pattern_full = '{(\[raw\].*?\[/raw\])}is';
	$pattern_contents = '{\[raw\](.*?)\[/raw\]}is';
	$pieces = preg_split($pattern_full, $content, -1, PREG_SPLIT_DELIM_CAPTURE);

	foreach ($pieces as $piece) {
		if (preg_match($pattern_contents, $piece, $matches)) {
			$new_content .= $matches[1];
		} else {
			$new_content .= wptexturize(wpautop($piece));
		}
	}

	return $new_content;
}

remove_filter('the_content', 'wpautop');
remove_filter('the_content', 'wptexturize');

add_filter('the_content', 'my_formatter', 99);
// Tabs shortcode
add_shortcode('tabs', 'shortcode_tabs');
	function shortcode_tabs( $atts, $content = null ) {
	extract(shortcode_atts(array(
    ), $atts));

	$out .= '[raw]<div class="nav_menu multi-download">[/raw]';

	$out .= '<ul><li class="green-title">Tải về máy</li>';
	$out .= do_shortcode($content) .'[raw]</ul></div>[/raw]';

	return $out;
}

add_shortcode('tab', 'shortcode_tab');
	function shortcode_tab( $atts, $content = null ) {
	extract(shortcode_atts(array(
    ), $atts));

	$out .= '[raw]<li>[/raw]' . do_shortcode($content) .'</li>';

	return $out;
}

// Youtube
add_shortcode('youtube', 'shortcode_youtube');
	function shortcode_youtube($atts) {
		$atts = shortcode_atts(
			array(
				'id' => '',
			), $atts);
		
			return '<div id="video-player"><iframe width="500" height="281" " src="http://www.youtube.com/embed/' . $atts['id'] . '?feature=oembed" frameborder="0" allowfullscreen></iframe></div>';
	}

// Download button
add_shortcode('download', 'shortcode_button');
	function shortcode_button($atts, $content = null) {
		$atts = shortcode_atts(
			array(
				'color' => 'black',
				'link' => '#',
				'target' => '',
			), $atts);
		
			return '[raw]<a rel="nofollow" target="_blank" class="download" href="' . $atts['link'] . '">' .do_shortcode($content). '</a>[/raw]';
	}	
// Add buttons to tinyMCE
add_action('init', 'add_button');

function add_button() {
   if ( current_user_can('edit_posts') &&  current_user_can('edit_pages') )
   {
     add_filter('mce_external_plugins', 'add_plugin');
     add_filter('mce_buttons_3', 'register_button');
   }
}

function register_button($buttons) {
   array_push($buttons, "tabs", "youtube", "download");
   return $buttons;
}

function add_plugin($plugin_array) {
   $plugin_array['tabs'] = get_template_directory_uri().'/includes/shortcodes/customcodes.js';
   $plugin_array['youtube'] = get_template_directory_uri().'/includes/shortcodes/customcodes.js';
   $plugin_array['download'] = get_template_directory_uri().'/includes/shortcodes/customcodes.js';   
   return $plugin_array;
}