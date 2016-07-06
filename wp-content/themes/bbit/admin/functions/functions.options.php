<?php

add_action('init','of_options');

if (!function_exists('of_options'))
{
	function of_options()
	{
		//Access the WordPress Categories via an Array
		$of_categories 		= array();  
		$of_categories_obj 	= get_categories('hide_empty=0');
		foreach ($of_categories_obj as $of_cat) {
		    $of_categories[$of_cat->cat_ID] = $of_cat->cat_name;}
		$categories_tmp 	= array_unshift($of_categories, "Select a category:");    
	       
		//Access the WordPress Pages via an Array
		$of_pages 			= array();
		$of_pages_obj 		= get_pages('sort_column=post_parent,menu_order');    
		foreach ($of_pages_obj as $of_page) {
		    $of_pages[$of_page->ID] = $of_page->post_name; }
		$of_pages_tmp 		= array_unshift($of_pages, "Select a page:");  
		
		// Post Format
		$bbit_post_format = array(
		"audio" => "Music",
		"image" => "Image",
		"video" => "Video",
		"story" => "Story",
		"aside" => "Aside",
		"gallery" => "Gallery",
		"link" => "Link",
		"quote" => "Quote",
		"status" => "Status",
		"chat" => "Chat" );	
		// Clean the <head>
		$bbit_clean_head = array(
		"rsd_link" => "RSD Link",
		"wlwmanifest_link" => "Wlwmanifest Link",
		"shortlink" => "Short URL",
		"wp_generator" => "WP Generator",
		"feed_link" => "Feed Link"		
		);
		//Background Images Reader
		$bg_images_path = get_stylesheet_directory(). '/assets/images/bg/'; // change this to where you store your bg images
		$bg_images_url = get_template_directory_uri().'/assets/images/bg/'; // change this to where you store your bg images
		$bg_images = array();
		
		if ( is_dir($bg_images_path) ) {
		    if ($bg_images_dir = opendir($bg_images_path) ) { 
		        while ( ($bg_images_file = readdir($bg_images_dir)) !== false ) {
		            if(stristr($bg_images_file, ".png") !== false || stristr($bg_images_file, ".jpg") !== false) {
		            	natsort($bg_images); //Sorts the array into a natural order
		                $bg_images[] = $bg_images_url . $bg_images_file;
		            }
		        }    
		    }
		}
		
		/*-----------------------------------------------------------------------------------*/
		/* TO DO: Add options/functions that use these */
		/*-----------------------------------------------------------------------------------*/
		
		//More Options
		$uploads_arr 		= wp_upload_dir();
		$all_uploads_path 	= $uploads_arr['path'];
		$all_uploads 		= get_option('of_uploads');
		$other_entries 		= array("Select a number:","1","2","3","4","5","6","7","8","9","10","11","12","13","14","15","16","17","18","19");
		$body_repeat 		= array("no-repeat","repeat-x","repeat-y","repeat");
		$body_pos 			= array("top left","top center","top right","center left","center center","center right","bottom left","bottom center","bottom right");
		
		// Image Alignment radio box
		$of_options_thumb_align = array("alignleft" => "Left","alignright" => "Right","aligncenter" => "Center"); 
		
		// Image Links to Options
		$of_options_image_link_to = array("image" => "The Image","post" => "The Post"); 

		
/*-----------------------------------------------------------------------------------*/
/* OPTION IN HERE
/*-----------------------------------------------------------------------------------*/

/*-----------------------------------------------------------------------------------*/
/* GENERAL
/*-----------------------------------------------------------------------------------*/
global $of_options;
$of_options = array();
$of_options[] = array( 	"name" 		=> "General",
						"type" 		=> "heading"
				);
				
$of_options[] = array( 	"name" 		=> "Hello there!",
						"desc" 		=> "",
						"id" 		=> "introduction",
						"std" 		=> "<h3 style=\"margin: 0 0 10px;\">Chào mừng đến với Bbit Option Framework.</h3>Đang cập nhật...",
						"icon" 		=> true,
						"type" 		=> "info"
				);

/*-----------------------------------------------------------------------------------*/
/* HEADER
/*-----------------------------------------------------------------------------------*/
$of_options[] = array( 	"name" 		=> "Header",
						"type" 		=> "heading"
				);
				
$img_url = get_template_directory_uri() .'/assets/images';
$of_options[] = array(	"name"		=> "Favicon",
						"desc"		=> "Upload ảnh hoặc điền đường dẫn đến ảnh thay thế cho favicon mặc định." ,
						"id" 		=> "bbit_favicon",
						"std"		=> $img_url . "/favicon.ico",
						"type"		=> "upload"
                );
				
$of_options[] = array(	"name"		=> "Apple Touch",
						"desc"		=> "Upload ảnh hoặc điền đường dẫn đến ảnh thay thế cho Apple Touch mặc định." ,
						"id" 		=> "bbit_apple_touch",
						"std"		=> $img_url . "/apple-touch-icon.png",
						"type"		=> "upload"
                );
				
$of_options[] = array(	"name"		=> "Logo",
						"desc"		=> "Upload ảnh hoặc điền đường dẫn đến ảnh thay thế cho logo mặc định." ,
						"id" 		=> "bbit_logo",
						"std"		=> $img_url . "/logo.png",
						"type"		=> "upload"
                );
/*-----------------------------------------------------------------------------------*/
/* FOOTER
/*-----------------------------------------------------------------------------------*/
$of_options[] = array( 	"name" 		=> "Footer",
						"type" 		=> "heading"
				);
				
$of_options[] = array( 	"name" 		=> "Footer Text",
						"desc" 		=> "Bạn có thể sử dụng shortcodes trong footer text: [wp-link] [theme-link] [loginout-link] [blog-title] [blog-link] [the-year]",
						"id" 		=> "bbit_footer_text",
						"std" 		=> "Liên kết ở đây",
						"type" 		=> "textarea"
				);	
/*-----------------------------------------------------------------------------------*/
/* HOME
/*-----------------------------------------------------------------------------------*/			
$of_options[] = array( 	"name" 		=> "Home",
						"type" 		=> "heading"
				);
				
$of_options[] = array( 	"name" 		=> "Post",
						"type" 		=> "heading"
				);
				
$of_options[] = array( 	"name" 		=> "Bình luận",
						"desc" 		=> "Bật/tắt chức năng bình luận cho bài đăng.",
						"id" 		=> "bbit_show_comments",
						"std" 		=> 1,
						"on" 		=> "Bật",
						"off" 		=> "Tắt",
						"type" 		=> "switch"
				);

/*-----------------------------------------------------------------------------------*/
/* STYLE
/*-----------------------------------------------------------------------------------*/					
$of_options[] = array( 	"name" 		=> "Style",
						"type" 		=> "heading"
				);
				
$of_options[] = array( 	"name" 		=> "Background",
						"desc" 		=> "Chọn ảnh nền.",
						"id" 		=> "bbit_custom_bg",
						"std" 		=> $bg_images_url."bg.png",
						"type" 		=> "tiles",
						"options" 	=> $bg_images,
				);

$of_options[] = array( 	"name" 		=> "",
						"desc" 		=> "Chọn màu cho nền trang (mặc định: #f9f9f9).",
						"id" 		=> "bbit_background",
						"std" 		=> "#f9f9f9",
						"type" 		=> "color"
				);
$of_options[] = array( 	"name" 		=> "Site Max with",
						"desc" 		=> "Chiều rộng tối đa cho Site Body. Nhỏ nhất 200px, rộng nhất 1350px.",
						"id" 		=> "bbit_body_width",
						"std" 		=> "650",
						"min" 		=> "200",
						"step"		=> "5",
						"max" 		=> "1350",
						"type" 		=> "sliderui" 
				);

$of_options[] = array( 	"name" 		=> "Tùy chỉnh CSS",
						"desc" 		=> "Bạn có thể tùy chỉnh giao diện bằng cách thêm mã css vào ô dưới đây.",
						"id" 		=> "bbit_custom_css",
						"std" 		=> "",
						"type" 		=> "textarea"
				);
				
//Advanced Settings
$of_options[] = array( 	"name" 		=> "Advanced",
						"type" 		=> "heading"
				);
				
$of_options[] = array( 	"name" 		=> "Nén HTML",
						"desc" 		=> "Nén HTML để tăng tốc độ tải trang, không dùng khi development theme.",
						"id" 		=> "bbit_compress_html",
						"std" 		=> 0,
						"on" 		=> "Bật",
						"off" 		=> "Tắt",
						"type" 		=> "switch"
				);	

$of_options[] = array( 	"name" 		=> "Copy Protected",
						"desc" 		=> "Chống sao chép nội dung dưới mọi hình thức, bảo vệ bản quyền.",
						"id" 		=> "bbit_protected",
						"std" 		=> 0,
						"on" 		=> "Bật",
						"off" 		=> "Tắt",
						"type" 		=> "switch"
				);	
			
/*-----------------------------------------------------------------------------------*/
/* SEO and ADS
/*-----------------------------------------------------------------------------------*/
$of_options[] = array( 	"name" 		=> "SEO and Ads",
						"type" 		=> "heading"
				);
				
$of_options[] = array( 	"name" 		=> "Tracking Code",
						"desc" 		=> "Mã theo dõi Google Analytics (hoặc khác) ở đây. Nó sẽ tự động được thêm vào theme.",
						"id" 		=> "bbit_google_analytics",
						"std" 		=> "",
						"type" 		=> "textarea"
				);
				
$of_options[] = array( 	"name" 		=> "Header Banner",
						"desc" 		=> "Mã quảng cáo đầu trang (phía trên widgets)",
						"id" 		=> "bbit_header_banner",
						"std" 		=> "",
						"type" 		=> "textarea"
				);		

//End oftion				
	}
}
?>
