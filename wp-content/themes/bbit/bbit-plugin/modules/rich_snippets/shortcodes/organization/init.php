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
if (class_exists('bbitSnippet_organization') != true) {
    class bbitSnippet_organization extends bbitRichSnippets
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

        	// html ORGANIZATION
        	$type   = ( !empty($orgtype) ? $orgtype : ucfirst( $this->shortcodeCfg['type'] ) );
        	$ret[]	= '<div itemscope itemtype="http://schema.org/' . $type .'">';

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

        	// POSTAL ADDRESS
        	if ( !empty($street) || !empty($pobox) || !empty($city) || !empty($state)
        		|| !empty($postalcode) || !empty($country) ) {
        		
        			$ret[]	= '<div itemprop="address" itemscope itemtype="http://schema.org/PostalAddress">';
        	}

        	if ( !empty($street) ) {
        		$ret[]	= '<div class="street" itemprop="streetAddress">' . $street . '</div>';
        	}

        	if ( !empty($pobox) ) {
        		$ret[]	= '<div class="pobox">' . __('P.O. Box:', $this->the_plugin->localizationName) . ' <span itemprop="postOfficeBoxNumber">' . $pobox . '</span></div>';
        	}

        	if ( !empty($city) && !empty($state) ) {

	        	$ret[]	= '<div class="city_state">';
	        	$ret[]	= 	'<span class="locale" itemprop="addressLocality">' . $city . '</span>,';
	        	$ret[]	= 	'<span class="region" itemprop="addressRegion">' . $state . '</span>';
	        	$ret[]	= '</div>';
        	} else if ( empty($state) && !empty($city) ) {

	        	$ret[]	= '<div class="city_state"><span class="locale" itemprop="addressLocality">' . $city . '</span></div>';
        	} else if ( empty($city) && !empty($state) ) {

    	    	$ret[]	= '<div class="city_state"><span class="region" itemprop="addressRegion">' . $state . '</span></div>';
        	}

        	if ( !empty($postalcode) ) {
        		$ret[]	= '<div class="postalcode" itemprop="postalCode">' . $postalcode . '</div>';
        	}

        	if ( !empty($country) ) {
        		$ret[]	= '<div class="country" itemprop="addressCountry">' . $country . '</div>';
        	}

        	if ( !empty($street) || !empty($pobox) || !empty($city) || !empty($state)
        		|| !empty($postalcode) || !empty($country) ) {
        		
        			$ret[]	= '</div>';
        	}
        	// end POSTAL ADDRESS
        	
        	// geo location
        	$ret[] = 		'<div itemscope itemtype="http://schema.org/Place">';
        	if ( $map_latitude!='' && $map_longitude!='' ) {

        		$ret[] = 		'<div itemprop="geo" itemscope itemtype="http://schema.org/GeoCoordinates">';
        		if ( isset($map_latitude) && !empty($map_latitude) )
        			$ret[] = 		'<meta itemprop="latitude" content="' . $map_latitude . '" />';
        		if ( isset($map_longitude) && !empty($map_longitude) )
        			$ret[] = 		'<meta itemprop="longitude" content="' . $map_longitude . '" />';
        		$ret[] = 		'</div>';
        	}
        	$ret[] = 		'</div>'; // end Place

        	if ( !empty($email) ) {
        		$ret[]	= '<div class="email" itemprop="email">' . antispambot($email) . '</div>';
        	}

        	if ( !empty($phone) ) {
        		$ret[]	= '<div class="phone" itemprop="telephone">' . __('Phone:', $this->the_plugin->localizationName) . ' ' . $phone . '</div>';
        	}

        	if ( !empty($fax) ) {
        		$ret[]	= '<div class="fax" itemprop="faxNumber">' . __('Fax:', $this->the_plugin->localizationName) . ' ' . $fax . '</div>';
        	}

        	// close it up
        	$sc_build .= '</div>';

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

// Initialize the bbitSnippet_organization class
$bbitSnippet_organization = new bbitSnippet_organization('organization');