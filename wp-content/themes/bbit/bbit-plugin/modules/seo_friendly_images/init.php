<?php
/*
* Define class bbitSEOImages
* Make sure you skip down to the end of this file, as there are a few
* lines of code that are very important.
*/
if ( !defined( 'DB_NAME' ) ) {
	header( 'HTTP/1.0 403 Forbidden' );
	die;
}
if (class_exists('bbitSEOImages') != true) {
    class bbitSEOImages
    {
        /*
        * Some required plugin information
        */
        const VERSION = '1.0';

        /*
        * Store some helpers config
        */
		public $the_plugin = null;

		private $module_folder = '';
		private $module = '';
		
		private $settings = array();
		private $special_tags = array(
			'{focus_keyword}',
			'{title}',
			'{image_name}',
			'{nice_image_name}',
			'{category}'
		);

		static protected $_instance;

        /*
        * Required __construct() function that initalizes the Bbit Framework
        */
        public function __construct()
        {
        	global $bbit;
			
        	$this->the_plugin = $bbit;
			$this->module_folder = $this->the_plugin->cfg['paths']['plugin_dir_url'] . 'modules/seo_friendly_images/';
			$this->module = $this->the_plugin->cfg['modules']['seo_friendly_images'];
			
			$this->settings = $this->the_plugin->getAllSettings( 'array', 'seo_friendly_images' );
			
			if ( !$this->the_plugin->verify_module_status( 'seo_friendly_images' ) ) ; //module is inactive
			else {
				if ( $this->the_plugin->is_admin !== true )
					add_filter('the_content', array( $this, 'add_images_tags'));
			}
        }
		
		public function add_images_tags( $the_content )
		{
			global $post;

			// php query class
			require_once( $this->the_plugin->cfg['paths']['scripts_dir_path'] . '/php-query/php-query.php' );  
			
			$bbit_meta = get_post_meta( $post->ID, 'bbit_meta', true );
			
			if( trim($the_content) != "" ){
				if ( !empty($this->the_plugin->charset) )
					$doc = bbitphpQuery::newDocument( $the_content, $this->the_plugin->charset );
				else
					$doc = bbitphpQuery::newDocument( $the_content );
				
				foreach( bbitPQ('img') as $img ) {
					// cache the img object
					$img = bbitPQ($img); 
					
			    	$url = $img->attr('src');
					$image_name = '';
					if( trim($url) != "" ){
						$image_name = explode( '.', end( explode( '/', $url ) ) );
						$image_name = $image_name[0]; 
					}
					
			    	$alt = $img->attr('alt');
					
			    	$title = $img->attr('title');
					
					// setup the default settings
					$new_alt = isset($this->settings["image_alt"]) ? $this->settings["image_alt"] : '';
					$new_title = isset($this->settings["image_title"]) ? $this->settings["image_title"] : '';
					
					if( isset($this->settings['keep_default_alt']) && trim($this->settings['keep_default_alt']) != "" ){
						$new_alt = $alt . ' ' . $new_alt;
					}
					if( isset($this->settings['keep_default_title']) && trim($this->settings['keep_default_title']) != "" ){
						$new_title = $title . ' ' . $new_title;
					}
						
					// make the replacements 
					foreach ($this->special_tags as $tag) { 
						if( $tag == '{title}' ) {  
							if( preg_match("/$tag/iu", $this->settings["image_alt"]) ) {
								$new_alt = str_replace( $tag, $post->post_title, $new_alt ); 
							}
							
							if( preg_match("/$tag/iu", $this->settings["image_title"]) )
								$new_title = str_replace( $tag, $post->post_title, $new_title );
						}
						
						elseif( $tag == '{image_name}' ) {
							if( preg_match("/$tag/iu", $this->settings["image_alt"]) )
								$new_alt = str_replace( $tag, $image_name, $new_alt );
							
							if( preg_match("/$tag/iu", $this->settings["image_title"]) )
								$new_title = str_replace( $tag, $image_name, $new_title );
						}
						
						elseif( $tag == '{focus_keyword}' && isset($bbit_meta['focus_keyword']) && trim($bbit_meta['focus_keyword']) != "" ) {
							if( preg_match("/$tag/iu", $this->settings["image_alt"]) )
								$new_alt = str_replace( $tag, $bbit_meta['focus_keyword'], $new_alt );
							
							if( preg_match("/$tag/iu", $this->settings["image_title"]) )
								$new_title = str_replace( $tag, $bbit_meta['focus_keyword'], $new_title );
						}
						
						elseif( $tag == '{nice_image_name}' ) {  
							$image_name = preg_replace("/[^a-zA-Z0-9\s]/", " ", $image_name);
							$image_name = preg_replace('/\d{1,4}x\d{1,4}/i',  '', $image_name);

							if( preg_match("/$tag/iu", $this->settings["image_alt"]) )
								$new_alt = str_replace( $tag, $image_name, $new_alt );
							
							if( preg_match("/$tag/iu", $this->settings["image_title"]) )
								$new_title = str_replace( $tag, $image_name, $new_title );
						}
					}
					
					// if the alt / title was changed
					if( $new_alt != $alt )
						$img->attr( 'alt', trim($new_alt) );
					
					if( $new_title != $title )
						$img->attr( 'title', trim($new_title) );
			    }
					
				return do_shortcode($doc->html());
				
			}else{
				return do_shortcode($the_content);
			}
		}


		/**
	    * Singleton pattern
	    *
	    * @return bbitSEOImages Singleton instance
	    */
	    static public function getInstance()
	    {
	        if (!self::$_instance) {
	            self::$_instance = new self;
	        }

	        return self::$_instance;
	    }
    }
}

// Initialize the bbitSEOImages class
//$bbitSEOImages = new bbitSEOImages();
$bbitSEOImages = bbitSEOImages::getInstance();