<?php
/*
* Define class bbitRichSnippets
* Make sure you skip down to the end of this file, as there are a few
* lines of code that are very important.
*/
if ( !defined( 'DB_NAME' ) ) {
	header( 'HTTP/1.0 403 Forbidden' );
	die;
}
if (class_exists('bbitSnippet_movie') != true) {
    class bbitSnippet_movie extends bbitRichSnippets
    {
        /*
        * Some required plugin information
        */
        const VERSION = '1.0';

        /*
        * Store some helpers config
        */
		public $the_plugin = null;

		protected $module_folder = '';
		protected $module_folder_path = '';
		
		static protected $_instance;


        /*
        * Required __construct() function that initalizes the Bbit Framework
        */
        public function __construct( $shortcode=null )
        {
        	global $bbit;

        	// access parent class!
        	$this->shortcode_cfg( $shortcode, array(
        		'type'			=> $shortcode,
        		'execute'		=> true
        	) );
        	
        	$this->the_plugin = $bbit;
			$this->module_folder = $this->the_plugin->cfg['paths']['plugin_dir_url'] . 'modules/rich_snippets/shortcodes/'.$this->shortcode.'/';
			$this->module_folder_path = $this->the_plugin->cfg['paths']['plugin_dir_path'] . 'modules/rich_snippets/shortcodes/'.$this->shortcode.'/';
			
			$this->init();
        }
        
        
        public function init() {
        	
        	$shortcode = $this->the_plugin->alias . '_rs_' . $this->shortcode;
        	add_shortcode( $shortcode, array( $this, 'gethtml') );
        }
        
        public function gethtml( $atts = array(), $content = null ) {
        	$ret = array();

        	// the attributes
        	extract( $this->shortcode_atts($atts, $content) );

        	// html MOVIE
        	$type   = ( !empty($eventtype) ? $eventtype : ucfirst( $this->shortcodeCfg['type'] ) );
        	//$ret[]	= '<div itemscope itemtype="http://schema.org/' . $type .'">';
        	$ret[]	= '<div itemscope itemtype="http://schema.org/Movie">';

        	if ( !empty($image) ) {

        		$imgalt = isset($name) ? $name : __($type.' Image', $this->the_plugin->localizationName);
        		$ret[]	= '<img class="schema_image" itemprop="image" src="' . esc_url($image) . '" alt="' . $imgalt . '" />';
        	}

        	if ( !empty($name) && !empty($url) ) {

        		$ret[]	= '<a class="schema_url" target="_blank" itemprop="url" href="' . esc_url($url) . '">';
        		$ret[]	= 	'<div class="schema_name" itemprop="name">' . $name . '</div>';
        		$ret[]	= '</a>';
        	} else if ( !empty($name) && empty($url) ) {

        		$ret[]	= '<div class="schema_name" itemprop="name">' . $name . '</div>';
        	}

        	if ( !empty($description) ) {
        		$ret[]	= '<div class="schema_description" itemprop="description">' . esc_attr($description) . '</div>';
        	}

        	if ( !empty($director) ) {
        		$ret[]	= '<div itemprop="director" itemscope itemtype="http://schema.org/Person">' . __('Directed by:', $this->the_plugin->localizationName) . ' <span itemprop="name">' . $director . '</span></div>';
        	}

        	if ( !empty($producer) ) {
        		$ret[]	= '<div itemprop="producer" itemscope itemtype="http://schema.org/Person">' . __('Produced by:', $this->the_plugin->localizationName) . ' <span itemprop="name">' . $producer . '</span></div>';
        	}

        	if ( !empty($actor) ) {
        		$ret[]	= '<div itemprop="director" itemscope itemtype="http://schema.org/Person">' . __('Starring:', $this->the_plugin->localizationName) . ' <span itemprop="name">' . $actor . '</span></div>';
        	}

        	$ret[]	= '</div>';

			// build Full html!
        	return $this->shortcode_execute( $ret, $atts, $content );
        }
        
		
		/**
	    * Singleton pattern
	    *
	    * @return bbitSnippet_event Singleton instance
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

// Initialize the bbitSnippet_movie class
$bbitSnippet_movie = new bbitSnippet_movie('movie');