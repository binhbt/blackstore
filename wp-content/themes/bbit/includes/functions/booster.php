<?php
if ( !defined( 'DB_NAME' ) ) {
	header( 'HTTP/1.0 403 Forbidden' );
	die;
}

/**
 * Name: Auto Remove Empty Meta Value
 * Mô tả: Lọc ra những thẻ meta trống khi đăng bài và xóa nó trước khi nó được 
 * lưu vào database bởi nó hoàn toàn vô dụng và gây lãng phí tài nguyên.
 */

add_action('save_post','bbit_auto_remove_meta');
function bbit_auto_remove_meta($post_id) { 

    if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ) return;
    if (!current_user_can('edit_post', $post_id)) return;
    $custom_fields = get_post_custom($post_id);
    if(!$custom_fields) return;

    foreach($custom_fields as $key=>$custom_field):

        $values = array_filter($custom_field);

        if(empty($values)):
            delete_post_meta($post_id,$key);
        endif;
		
    endforeach; 
    return;
}

/**
 * Name: Auto Remove Query String
 * Mô tả: Tự động gỡ bỏ Query String có dạng ?ver= trong url của js và css có  
 * lợi cho hiệu suất và tốc độ tải của website
 */

function _remove_query_strings_1( $src ){
	$rqs = explode( '?ver', $src );
        return $rqs[0];
}
function _remove_query_strings_2( $src ){
	$rqs = explode( '&ver', $src );
        return $rqs[0];
}
add_filter( 'script_loader_src', '_remove_query_strings_1', 15, 1 );
add_filter( 'style_loader_src', '_remove_query_strings_1', 15, 1 );
add_filter( 'script_loader_src', '_remove_query_strings_2', 15, 1 );
add_filter( 'style_loader_src', '_remove_query_strings_2', 15, 1 );

/**
 * Name: Clean up the <head>
 * Mô tả: Làm sạch wp_head khỏi những thẻ không cần thiết, tối ưu cho tốc độ  
 */

//remove_action( 'wp_head', 'wp_generator' );
remove_action( 'wp_head', 'rsd_link' );
remove_action( 'wp_head', 'wlwmanifest_link' );
remove_action( 'wp_head', 'wp_shortlink_wp_head' );
remove_action( 'template_redirect', 'wp_shortlink_header' );
remove_action( 'wp_head', 'feed_links', 2 );
remove_action( 'wp_head', 'feed_links_extra', 3 );

/* ------------------------------- Modun Not Outlink ------------------------------- */
/**
 * Name: Module Not Outlink
 * Mô tả: Tự động thêm rel nofollow vào những liên kết ra ngoài để báo cho bot
 * không theo liên kết đó giúp hạn chế nguy cơ giảm Pagerank.
 */
add_filter( 'the_content', 'bbit_nofollow');
function bbit_nofollow( $content ) {

	$regexp = "<a\s[^>]*href=(\"??)([^\" >]*?)\\1[^>]*>";
	
	if(preg_match_all("/$regexp/siU", $content, $matches, PREG_SET_ORDER)) {
		if( !empty($matches) ) {
			$srcUrl = get_option('siteurl');
			for ($i=0; $i < count($matches); $i++)
			{
				$tag = $matches[$i][0];
				$tag2 = $matches[$i][0];
				$url = $matches[$i][0];
				$noFollow = '';
				$pattern = '/rel\s*=\s*"\s*[n|d]ofollow\s*"/';
				preg_match($pattern, $tag2, $match, PREG_OFFSET_CAPTURE);
				if( count($match) < 1 )
					$noFollow .= ' rel="nofollow" ';
				$pos = strpos($url,$srcUrl);
				if ($pos === false) {
					$tag = rtrim ($tag,'>');
					$tag .= $noFollow.'>';
					$content = str_replace($tag2,$tag,$content);
				}
			}
		}
	}
	
	$content = str_replace(']]>', ']]&gt;', $content);
	return $content;
}