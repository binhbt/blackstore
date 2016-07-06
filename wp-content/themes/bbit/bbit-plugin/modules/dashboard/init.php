<?php
/*
* Define class bbitDashboard
* Make sure you skip down to the end of this file, as there are a few
* lines of code that are very important.
*/
if ( !defined( 'DB_NAME' ) ) {
	header( 'HTTP/1.0 403 Forbidden' );
	die;
}
if (class_exists('bbitDashboard') != true) {
    class bbitDashboard
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
		
		public $ga = null;
		public $ga_params = array();
		
		public $boxes = array();

		static protected $_instance;

        /*
        * Required __construct() function that initalizes the Bbit Framework
        */
        public function __construct()
        {
        	global $bbit;
        	
        	$this->the_plugin = $bbit;
			$this->module_folder = $this->the_plugin->cfg['paths']['plugin_dir_url'] . 'modules/dashboard/';
			//$this->module = $this->the_plugin->cfg['modules']['dashboard'];
			
			if (is_admin()) {
	            add_action( "admin_enqueue_scripts", array( &$this, 'admin_print_styles') );
				add_action( "admin_print_scripts", array( &$this, 'admin_load_scripts') );
			}
			   
			// load the ajax helper
			if ( $this->the_plugin->is_admin === true ) {
				require_once( $this->the_plugin->cfg['paths']['plugin_dir_path'] . 'modules/dashboard/ajax.php' );
				new bbitDashboardAjax( $this->the_plugin );
			}
			
			if ( $this->the_plugin->is_admin === true ) {

			// add the boxes
			/*$this->addBox( 'website_preview', '', $this->website_preview(), array(
				'size' => 'grid_4'
			) );*/
			
			$this->addBox( 'dashboard_links', '', $this->links(), array(
				'size' => 'grid_4'
			) );
			
			$this->addBox( 'social', 'Social Statistics', $this->social(), array(
				'size' => 'grid_4'
			) );
			
			$this->addBox( 'audience_overview', 'Audience Overview', $this->audience_overview(), array(
				'size' => 'grid_4'
			) );
			
			$this->addBox( 'support', 'Bạn cần trợ giúp?', $this->support() );
			
			}
        }

		/**
	    * Singleton pattern
	    *
	    * @return bbitDashboard Singleton instance
	    */
	    static public function getInstance()
	    {
	        if (!self::$_instance) {
	            self::$_instance = new self;
	        }

	        return self::$_instance;
	    }
	    
		public function admin_print_styles()
		{
			wp_register_style( 'bbit-DashboardBoxes', $this->module_folder . 'app.css', false, '1.0' );
        	wp_enqueue_style( 'bbit-DashboardBoxes' );
		}
		
		public function admin_load_scripts()
		{
			wp_enqueue_script( 'bbit-DashboardBoxes', $this->module_folder . 'app.class.js', array(), '1.0', true );
		}
		
		public function getBoxes()
		{
			$ret_boxes = array();
			if( count($this->boxes) > 0 ){
				foreach ($this->boxes as $key => $value) { 
					$ret_boxes[$key] = $value;
				}
			}
 
			return $ret_boxes;
		}
		
		private function formatAsFreamworkBox( $html_content='', $atts=array() )
		{
			return array(
				'size' 		=> isset($atts['size']) ? $atts['size'] : 'grid_4', // grid_1|grid_2|grid_3|grid_4
	            'header' 	=> isset($atts['header']) ? $atts['header'] : false, // true|false
	            'toggler' 	=> false, // true|false
	            'buttons' 	=> isset($atts['buttons']) ? $atts['buttons'] : false, // true|false
	            'style' 	=> isset($atts['style']) ? $atts['style'] : 'panel-widget', // panel|panel-widget
	            
	            // create the box elements array
	            'elements' => array(
	                array(
	                    'type' => 'html',
	                    'html' => $html_content
	                )
	            )
			);
		}
		
		private function addBox( $id='', $title='', $html='', $atts=array() )
		{ 
			// check if this box is not already in the list
			if( isset($id) && trim($id) != "" && !isset($this->boxes[$id]) ){
				
				$box = array();
				
				$box[] = '<div class="bbit-dashboard-status-box">';
				if( isset($title) && trim($title) != "" ){
					$box[] = 	'<h1>' . ( $title ) . '</h1>';
				}
				$box[] = 	$html;
				$box[] = '</div>';
				
				$this->boxes[$id] = $this->formatAsFreamworkBox( implode("\n", $box), $atts );
				
			}
		}
		
		public function formatRow( $content=array() )
		{
			$html = array();
			
			$html[] = '<div class="bbit-dashboard-status-box-row">';
			if( isset($content['title']) && trim($content['title']) != "" ){
				$html[] = 	'<h2>' . ( isset($content['title']) ? $content['title'] : 'Untitled' ) . '</h2>';
			}
			if( isset($content['ajax_content']) && $content['ajax_content'] == true ){
				$html[] = '<div class="bbit-dashboard-status-box-content is_ajax_content">';
				$html[] = 	'{' . ( isset($content['id']) ? $content['id'] : 'error_id_missing' ) . '}';
				$html[] = '</div>';
			}
			else{
				$html[] = '<div class="bbit-dashboard-status-box-content is_ajax_content">';
				$html[] = 	( isset($content['html']) && trim($content['html']) != "" ? $content['html'] : '!!! error_content_missing' );
				$html[] = '</div>';
			}
			$html[] = '</div>';
			
			return implode("\n", $html);
		}
		
		public function support()
		{
			$html = array();
			$html[] = '<a href="https://www.facebook.com/bbitcorporation" target="_blank"><img src="' . ( $this->module_folder ) . 'assets/support_banner.jpg"></a>';
			
			return implode("\n", $html);
		}
		public function social()
		{
			$html = array();
			$html[] = $this->formatRow( array( 
				'id' 			=> 'social_impact',
				'title' 		=> '',
				'html'			=> '',
				'ajax_content' 	=> true
			) );
 
			return implode("\n", $html);
		}
		
		public function audience_overview()
		{
			$html = array();
			$html[] = '<div class="bbit-audience-graph" id="bbit-audience-visits-graph" data-fromdate="' . ( date('Y-m-d', strtotime("-1 week")) ) . '" data-todate="' . ( date('Y-m-d') ) . '"></div>';

			return  implode("\n", $html);
		}
		
		public function links()
		{
			$html = array();
			$html[] = '<ul class="bbit-summary-links">';
			
			/*ob_start();
			var_dump('<pre>',array_keys($this->the_plugin->cfg['modules']),'</pre>');
			$__x = ob_get_contents();
			ob_end_clean();
			$html[]  = '<li>' . $__x .'</li>';*/
			
			// get all active modules
			foreach ($this->the_plugin->cfg['modules'] as $key => $value) {
 
				if( !in_array( $key, array_keys($this->the_plugin->cfg['activate_modules'])) ) continue;
				
				$module = $key;
				if ( //!in_array($module, $this->the_plugin->cfg['core-modules']) &&
				!$this->the_plugin->capabilities_user_has_module($module) ) {
					continue 1;
				}
				 
				$in_dashboard = isset($value[$key]['in_dashboard']) ? $value[$key]['in_dashboard'] : array();
				//var_dump('<pre>',$value[$key]['in_dashboard'], $key,'</pre>');  
				if( count($in_dashboard) > 0 ){
			
					$html[] = '
						<li>
							<a href="' . ( $in_dashboard['url'] ) . '">
								<img src="' . ( $value['folder_uri']  . $in_dashboard['icon'] ) . '">
								<span class="text">' . ( $value[$key]['menu']['title'] ) . '</span>
							</a>
						</li>';
				}
			}
			
			$html[] = '</ul>';
			
			return implode("\n", $html);
		}
		
		public function technologies()
		{
			$html = array();
			$html[] = $this->formatRow( array( 
				'id' 			=> 'server_ip',
				'title' 		=> 'Server IP',
				'html'			=> '',
				'ajax_content' 	=> true
			) );
			
			$html[] = $this->formatRow( array( 
				'id' 			=> 'technologies',
				'title' 		=> 'Technologies',
				'html'			=> '',
				'ajax_content' 	=> true
			) );
			
			$html[] = $this->formatRow( array( 
				'id' 			=> 'charset',
				'title' 		=> 'Charset',
				'html'			=> '',
				'ajax_content' 	=> true
			) );
			 
 
			return implode("\n", $html);
		}
		
    }
}

// Initialize the bbitDashboard class
$bbitDashboard = new bbitDashboard();
//$bbitDashboard = bbitDashboard::getInstance();