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
if (class_exists('bbitRichSnippets') != true) {
    class bbitRichSnippets
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
		private $module = '';
		
		protected $settings = array();
		
		static protected $_instance;
		
		protected $shortcode = null;
		protected $shortcodeCfg = array();


        /*
        * Required __construct() function that initalizes the Bbit Framework
        */
        public function __construct()
        {
        	global $bbit;

        	$this->the_plugin = $bbit;
			$this->module_folder = $this->the_plugin->cfg['paths']['plugin_dir_url'] . 'modules/rich_snippets/';
			$this->module_folder_path = $this->the_plugin->cfg['paths']['plugin_dir_path'] . 'modules/rich_snippets/';
			$this->module = $this->the_plugin->cfg['modules']['rich_snippets'];
			
			$this->settings = $this->the_plugin->getAllSettings( 'array', 'rich_snippets' );

			if ( !$this->the_plugin->verify_module_status( 'rich_snippets' ) ) ; //module is inactive
			else {
				$this->init();
			}
        }
        
        
        public function init() {
        	$this->the_plugin->loadRichSnippets('init');
        }
        
        protected function shortcode_cfg( $shortcode = null, $cfg = array() ) {
        	
        	$this->shortcode = $shortcode;
        	$this->shortcodeCfg  = $cfg;
        }

        public function shortcode_execute( $html = array(), $atts = array(), $content = null ) {

        	$ret = array();
        	if ( ( $header = $this->shortcode_header( $this->shortcodeCfg['execute'] ) ) != '' ) $ret[] = $header;

        	$ret[] = implode(PHP_EOL, $html);
        	
			if ( ( $footer = $this->shortcode_footer( $this->shortcodeCfg['execute'] ) ) != '' ) $ret[] = $footer;
			return implode("\n", $ret);
        }
        
        protected function shortcode_header( $execute = true ) {

			if( !wp_style_is('bbit_'.$this->shortcode.'_css') ) {
				wp_enqueue_style( 'bbit_'.$this->shortcode.'_css' , $this->module_folder . 'app.css' );
			}

        	if ( $execute !== true ) return '';

        	$ret = array();
        	
			$ret[] = '
				<!--begin bbit rich snippets shortcode : ' . ($this->shortcode) . '-->
				<div class="schema_block schema_'.$this->shortcodeCfg['type'].'">
			';

			$ret = implode('', $ret);
        	return $ret;
        }
        
        protected function shortcode_footer( $execute = true ) {

        	if ( $execute !== true ) return '';

        	$ret = array();
        	
			$ret[] = 	'
				</div>
				<!--end bbit rich snippets shortcode : ' . ($this->shortcode) . '-->
			';
			$ret = implode('', $ret);
        	return $ret;
        }
        
        protected function shortcode_atts( $atts = array(), $content = null ) {

        	$defaults = array();

        	$module_config = $this->module_folder_path . 'options.php';

        	if( $this->the_plugin->verifyFileExists( $module_config ) ) {

        		// Turn on output buffering
        		ob_start();

        		require( $module_config  );

        		$options = ob_get_clean(); //copy current buffer contents into $message variable and delete current output buffer

        		if(trim($options) != "") {
        			$options = json_decode($options, true);

        			if ( is_array($options) && !empty($options) > 0 ) {
        				$options = $options[0];
        				$options = reset($options);
        				$option = $options['elements'];
        				
        				if ( count($option) > 0 ) {
        					foreach ( $option as $key => $val ) {
        						//$defaults[ "$key" ] = $val['std'];
        						$defaults[ "$key" ] = '';
        					}
        				}
        			}
        		}
        	}
        	return $this->safeBoolean( shortcode_atts( $defaults, $atts ) );
        }

		protected function safeBoolean( $atts = array() ) {
			
			if ( !is_array($atts) || empty($atts) ) return array();

			foreach ( $atts as $key => $value ) {
				
				if ( preg_match('/^show_/i', $key) > 0 ) {

					$atts[ "$key" ] = (bool) $value;
					if ( $value === true || $value === 'true' )
						$atts[ "$key" ] = true;
					if ( $value === false || $value === 'false' )
						$atts[ "$key" ] = false;
				}
			}
			return $atts;
		}
		
		protected function getMultipleValues( $att='' ) {
			
			if ( empty($att) ) return array();
			$arr = array();
			
			$__tmp = explode(';;', $att);
			foreach ( $__tmp as $key => $value ) {
				if ( !empty($value) ) $arr[ $key ] = $value;
			}
			return $arr;
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

// Initialize the bbitRichSnippets class
//$bbitRichSnippets = new bbitRichSnippets();
$bbitRichSnippets = bbitRichSnippets::getInstance();