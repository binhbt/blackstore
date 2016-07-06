<?php
if ( !defined( 'DB_NAME' ) ) {
	header( 'HTTP/1.0 403 Forbidden' );
	die;
}

/* -------------------------------Add Theme Support -------------------------------- */
add_theme_support('post-formats', array('video', 'audio', 'image'));
add_theme_support( 'post-thumbnails' );

register_nav_menu('menu', __('Danh mục'));

register_sidebar(array(
	'name' => 'Home',
	'before_widget' => '',
	'after_widget' => '',
	'before_title' => '<span class="title">',
	'after_title' => '</span>',
));

/* ----------------------------------- Post View ---------------------------------- */
function bbit_views($postID) {
    $count_key = 'post_views_count';
    $count = get_post_meta($postID, $count_key, true);
    if($count==''){
        $count = 0;
        delete_post_meta($postID, $count_key);
        add_post_meta($postID, $count_key, '0');
    }else{
        $count++;
        update_post_meta($postID, $count_key, $count);
    }
}

function get_bbit_views($postID){
    $count_key = 'post_views_count';
    $count = get_post_meta($postID, $count_key, true);
    if($count==''){
        delete_post_meta($postID, $count_key);
        add_post_meta($postID, $count_key, '0');
        return "0";
    }
    return $count;
}

function post_column_views($newcolumn){
    $newcolumn['post_views'] = __('Lượt xem');
    return $newcolumn;
}

function post_custom_column_views($column_name, $id){

    if($column_name === 'post_views'){
        echo get_bbit_views(get_the_ID());
    }
}

add_filter('manage_posts_columns', 'post_column_views');
add_action('manage_posts_custom_column', 'post_custom_column_views',10,2);

/* ------------------------------------ Pagenavi ----------------------------------- */
function bbit_pagination($pages = '', $range = 2)
{
    $showitems = ($range * 2)+1;  

    global $paged;
    if(empty($paged)) $paged = 1;

    if($pages == '')
    {
        global $wp_query;
        $pages = $wp_query->max_num_pages;
        if(!$pages)
		{ 
		$pages = 1; 
		}
    }

    if(1 != $pages)
    {
        echo "<div id='wp_page' class='center-title'>";
        if($paged > 2 && $paged > $range+1 && $showitems < $pages) echo "<a href='".get_pagenum_link(1)."'> <<</a>";
        if($paged > 1 && $showitems < $pages) echo "<a href='".get_pagenum_link($paged - 1)."' rel='prev'> <</a>";

        for ($i=1; $i <= $pages; $i++)
        {
            if (1 != $pages &&( !($i >= $paged+$range+1 || $i <= $paged-$range-1) || $pages <= $showitems ))
            {
            echo ($paged == $i)? "<span class='current'>".$i."</span>":"<a href='".get_pagenum_link($i)."'>".$i."</a>";
            }
        }

        if ($paged < $pages && $showitems < $pages) echo "<a href='".get_pagenum_link($paged + 1)."' rel='next'>> </a>";  
        if ($paged < $pages-1 &&  $paged+$range-1 < $pages && $showitems < $pages) echo "<a href='".get_pagenum_link($pages)."'>>> </a>";
        echo "</div>\n";
    }
}

/* -------------------------------- Post Page Link --------------------------------- */
function bbit_post_page( $args = '' ) {

	$defaults = array(
		'next_or_number'   => 'next',
		'pagelink'         => '%'
	);

	$r = wp_parse_args( $args, $defaults );
	$r = apply_filters( 'wp_link_pages_args', $r );
	extract( $r, EXTR_SKIP );

	global $page, $numpages, $multipage, $more;

	if ( $multipage ) {
		if ( 'number' == $next_or_number ) {
			$output .= 'Trang';
			for ( $i = 1; $i <= $numpages; $i++ ) {
				$link = str_replace( '%', $i, $pagelink ) ;
				if ( $i != $page || ! $more && 1 == $page )
					$link = _wp_link_page( $i ) . $link . '</a>';
				$link = apply_filters( 'wp_link_pages_link', $link, $i );
				$output .= $link;
			}
		}

		elseif ( $more ) {
			$i = $page - 1;
			if ( $i ) {
				$link = _wp_link_page( $i ) . '&larr; Trang trước' . '</a>';
				$link = apply_filters( 'wp_link_pages_link', $link, $i );
				$output .= $separator . $link;
			}
			$output .= '<span class="current">'.$page.'</span>';
			$i = $page + 1;
			if ( $i <= $numpages ) {
				$link = _wp_link_page( $i ) . 'Trang sau &rarr;' . '</a>';
				$link = apply_filters( 'wp_link_pages_link', $link, $i );
				$output .= $link;
			}
		}
	}

	$output = apply_filters( 'bbit_post_page', $output, $args );

	echo $output;

	return $output;
}


/**
 * Name: Bbit Breadcrumbs
 * Mô tả: Trả về Breadcrumbs
 */

function bbit_breadcrumbs() {

  $text['category'] = '%s';
  $text['tag']   = ' %s';  
  $text['author']  = 'Tác giả %s'; 
  
  $show_current  = 1;
  $show_home_link = 1;
  $show_title   = 1;
  $delimiter   = ' &rsaquo; ';
  $before     = '<span typeof="v:Breadcrumb"><span class="breadcrumb_last" property="v:title">';
  $after     = '</span></span>';  

  global $post;  
  $home_link  = home_url('/');  
  $link_before = '<span typeof="v:Breadcrumb">';  
  $link_after  = '</span>';  
  $link_attr  = ' rel="v:url" property="v:title"';  
  $link     = $link_before . '<a' . $link_attr . ' href="%1$s">%2$s</a>' . $link_after;  
  $parent_id  = $parent_id_2 = $post->post_parent;  
  $frontpage_id = get_option('page_on_front');  

  if (is_home() || is_front_page()) {

  } else {

    echo '<div class="breadcrumb breadcrumbs" prefix="v: http://rdf.data-vocabulary.org/#">';   
      echo '<span typeof="v:Breadcrumb"><a href="' . $home_link . '" rel="v:url" property="v:title">Trang chủ</a></span>';  
      if ($frontpage_id == 0 || $parent_id != $frontpage_id) echo $delimiter;  

    if ( is_category() ) {  
      $this_cat = get_category(get_query_var('cat'), false);  
      if ($this_cat->parent != 0) {  
        $cats = get_category_parents($this_cat->parent, TRUE, $delimiter);  
        if ($show_current == 0) $cats = preg_replace("#^(.+)$delimiter$#", "$1", $cats);  
        $cats = str_replace('<a', $link_before . '<a' . $link_attr, $cats);  
        $cats = str_replace('</a>', '</a>' . $link_after, $cats);  
        if ($show_title == 0) $cats = preg_replace('/ title="(.*?)"/', '', $cats);  
        echo $cats;  
      }  
      if ($show_current == 1) echo $before . sprintf($text['category'], single_cat_title('', false)) . $after;  

    } elseif ( is_single() && !is_attachment() ) {  
      if ( get_post_type() != 'post' ) {  
        $post_type = get_post_type_object(get_post_type());  
        $slug = $post_type->rewrite;  
        printf($link, $home_link . '/' . $slug['slug'] . '/', $post_type->labels->singular_name);  
        if ($show_current == 1) echo $delimiter . $before . get_the_title() . $after;  
      } else {  
        $cat = get_the_category(); $cat = $cat[0];  
        $cats = get_category_parents($cat, TRUE, $delimiter);  
        if ($show_current == 0) $cats = preg_replace("#^(.+)$delimiter$#", "$1", $cats);  
        $cats = str_replace('<a', $link_before . '<a' . $link_attr, $cats);  
        $cats = str_replace('</a>', '</a>' . $link_after, $cats);  
        if ($show_title == 0) $cats = preg_replace('/ title="(.*?)"/', '', $cats);  
        echo $cats;  
        if ($show_current == 1) echo $before . get_the_title() . $after;  
      }  

    } elseif ( !is_single() && !is_page() && get_post_type() != 'post' && !is_404() ) {  
      $post_type = get_post_type_object(get_post_type());  
      echo $before . $post_type->labels->singular_name . $after;  

    } elseif ( is_attachment() ) {  
      $parent = get_post($parent_id);  
      $cat = get_the_category($parent->ID); $cat = $cat[0];  
      $cats = get_category_parents($cat, TRUE, $delimiter);  
      $cats = str_replace('<a', $link_before . '<a' . $link_attr, $cats);  
      $cats = str_replace('</a>', '</a>' . $link_after, $cats);  
      if ($show_title == 0) $cats = preg_replace('/ title="(.*?)"/', '', $cats);  
      echo $cats;  
      printf($link, get_permalink($parent), $parent->post_title);  
      if ($show_current == 1) echo $delimiter . $before . get_the_title() . $after;  

    } elseif ( is_page() && !$parent_id ) {  
      if ($show_current == 1) echo $before . get_the_title() . $after;  

    } elseif ( is_page() && $parent_id ) {  
      if ($parent_id != $frontpage_id) {  
        $breadcrumbs = array();  
        while ($parent_id) {  
          $page = get_page($parent_id);  
          if ($parent_id != $frontpage_id) {  
            $breadcrumbs[] = sprintf($link, get_permalink($page->ID), get_the_title($page->ID));  
          }  
          $parent_id = $page->post_parent;  
        }  
        $breadcrumbs = array_reverse($breadcrumbs);  
        for ($i = 0; $i < count($breadcrumbs); $i++) {  
          echo $breadcrumbs[$i];  
          if ($i != count($breadcrumbs)-1) echo $delimiter;  
        }  
      }  

        if ($show_home_link == 1 || ($parent_id_2 != 0 && $parent_id_2 != $frontpage_id)) echo $delimiter;  
        echo $before . get_the_title() . $after;   

    } elseif ( is_tag() ) {  
      echo $before . get_tag_meta('wpseo_tag_type'). sprintf($text['tag'], single_tag_title('', false)) . $after;  

    } elseif ( is_author() ) {  
      global $author;  
      $userdata = get_userdata($author);  
      echo $before . sprintf($text['author'], $userdata->display_name) . $after;  

    } 

    if ( get_query_var('paged') ) {  
      if ( is_category() || is_search() || is_tag() || is_author() ) echo ' (';  
      echo __('Trang') . ' ' . get_query_var('paged');  
      if ( is_category() || is_search() || is_tag() || is_author() ) echo ')';  
    }  

    echo '</div>';  

  }  
}

/* ---------------------------------- Limited Text --------------------------------- */
function bbit_string_limit($string, $word_limit)
{
    $words = explode(' ', $string, ($word_limit + 1));
    if(count($words) > $word_limit) {
        array_pop($words);
    }
    return implode(' ', $words);
}

/* -------------------------- Get tag && category metadata ------------------------- */
function get_tag_meta($key) {
    $taxonomy = get_queried_object()->taxonomy;
    $term_id = get_queried_object()->term_id;
    $meta   = get_option( 'wpseo_taxonomy_meta' );
    $title  = $meta[$taxonomy][$term_id][$key];
	return $title;
}

/* ------------------------------------ Search URL --------------------------------- */
function search_url($str) {
    $str = str_replace(" ", "+", $str);
	$str = str_replace(".", " ", $str);
	$str = str_replace("?", "", $str);
	$str = str_replace("!", "", $str);
    return $str;
}

/**
 * Name: Get Firrst Image
 * Mô tả: Tự động lấy hình ảnh đầu tiên của bài viết. Dùng Echo để gọi hàm first_image
 */
 
function first_image() {
  global $post, $posts;
  $first_img = '';
  ob_start();
  ob_end_clean();
  $output = preg_match_all('/<img.+src=[\'"]([^\'"]+)[\'"].*>/i', $post->post_content, $matches);
  $first_img = $matches [1] [0];
  return $first_img;
}

/* ----------------------------- Get Parent Category ID ---------------------------- */
function get_parent_category_id () {
	$q_cat = get_query_var('cat');
    $cat = get_category( $q_cat );
    $parent_category_id = $cat->category_parent; 
	return $parent_category_id;
}

/* -------------------------- Check if category has children ----------------------- */
function category_has_children() {
global $wpdb;
$term = get_queried_object();
$check = $wpdb->get_results(" SELECT * FROM wp_term_taxonomy WHERE parent = '$term->term_id' ");
     if ($check) {
          return true;
     } else {
          return false;
     }
}

/* ------------------------- Check if category has parent -------------------------- */
function category_has_parent($catid){
    $category = get_category($catid);
    if ($category->category_parent > 0){
        return true;
    }
    return false;
}