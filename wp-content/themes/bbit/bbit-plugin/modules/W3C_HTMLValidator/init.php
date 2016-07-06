<?php
/*
* Define class bbitW3C_HTMLValidator
* Make sure you skip down to the end of this file, as there are a few
* lines of code that are very important.
*/
if ( !defined( 'DB_NAME' ) ) {
	header( 'HTTP/1.0 403 Forbidden' );
	die;
}
if (class_exists('bbitW3C_HTMLValidator') != true) {
    class bbitW3C_HTMLValidator
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

		static protected $_instance;

        /*
        * Required __construct() function that initalizes the Bbit Framework
        */
        public function __construct()
        {
        	global $bbit;

        	$this->the_plugin = $bbit;
			$this->module_folder = $this->the_plugin->cfg['paths']['plugin_dir_url'] . 'modules/W3C_HTMLValidator/';
			$this->module = $this->the_plugin->cfg['modules']['W3C_HTMLValidator'];

			if (is_admin()) {
	            add_action('admin_menu', array( &$this, 'adminMenu' ));
			}

			// ajax optimize helper
			if ( $this->the_plugin->is_admin === true )
				add_action('wp_ajax_bbitHtmlValidate', array( &$this, 'validate_page' ));
        }

		/**
	    * Singleton pattern
	    *
	    * @return bbitW3C_HTMLValidator Singleton instance
	    */
	    static public function getInstance()
	    {
	        if (!self::$_instance) {
	            self::$_instance = new self;
	        }

	        return self::$_instance;
	    }

		/**
	    * Hooks
	    */
	    static public function adminMenu()
	    {
	       self::getInstance()
	    		->_registerAdminPages();
	    }

	    /**
	    * Register plug-in module admin pages and menus
	    */
		protected function _registerAdminPages()
    	{
    		if ( $this->the_plugin->capabilities_user_has_module('W3C_HTMLValidator') ) {
	    		add_submenu_page(
	    			$this->the_plugin->alias,
	    			$this->the_plugin->alias . " " . __('HTML Validator', $this->the_plugin->localizationName),
		            __('HTML Validator', $this->the_plugin->localizationName),
		            'read',
		            $this->the_plugin->alias . "_HTMLValidator",
		            array($this, 'display_index_page')
		        );
    		}

			return $this;
		}

		/*public function auto_optimize_on_save()
		{
			global $post;
			$postID = isset($post->ID) && (int) $post->ID > 0 ? $post->ID : 0;
			if( $postID > 0 ){
				$focus_kw = isset($_REQUEST['bbit-field-focuskw']) ? $_REQUEST['bbit-field-focuskw'] : '';
				$this->optimize_page( $postID, $focus_kw );
			}
		}*/

		public function display_index_page()
		{
			$this->printBaseInterface();
		}


		/*
		* printBaseInterface, method
		* --------------------------
		*
		* this will add the base DOM code for you options interface
		*/
		private function printBaseInterface()
		{
?>
		<script type="text/javascript" src="<?php echo $this->module_folder;?>app.class.js" ></script>
		<div id="bbit-wrapper" class="fluid wrapper-bbit">
			<?php
			// show the top menu
			bbitAdminMenu::getInstance()->make_active('advanced_setup|W3C_HTMLValidator')->show_menu();
			?>
			
			<!-- Main loading box -->
			<div id="bbit-main-loading">
				<div id="bbit-loading-overlay"></div>
				<div id="bbit-loading-box">
					<div class="bbit-loading-text">Loading</div>
					<div class="bbit-meter bbit-animate" style="width:86%; margin: 34px 0px 0px 7%;"><span style="width:100%"></span></div>
				</div>
			</div>

			<!-- Content -->
			<div id="bbit-content">
				
				<h1 class="bbit-section-headline">
					<?php echo $this->module['W3C_HTMLValidator']['menu']['title'];?>
					<span class="bbit-section-info"><?php echo $this->module['W3C_HTMLValidator']['description'];?></span>
					<?php
					$has_help = isset($this->module['W3C_HTMLValidator']['help']) ? true : false;
					if( $has_help === true ){
						
						$help_type = isset($this->module['W3C_HTMLValidator']['help']['type']) && $this->module['W3C_HTMLValidator']['help']['type'] ? 'remote' : 'local';
						if( $help_type == 'remote' ){
							echo '<a href="#load_docs" class="bbit-show-docs" data-helptype="' . ( $help_type ) . '" data-url="' . ( $this->module['W3C_HTMLValidator']['help']['url'] ) . '">HELP</a>';
						} 
					} 
					?>
				</h1>

				<!-- Container -->
				<div class="bbit-container clearfix">
					
					<!-- Main Content Wrapper -->
					<div id="bbit-content-wrap" class="clearfix">

						<!-- Content Area -->
						<div id="bbit-content-area">
							<div class="bbit-grid_4">
	                        	<div class="bbit-panel">
	                        		<div class="bbit-panel-header">
										<span class="bbit-panel-title">
											<?php /*<img src="<?php echo $this->module_folder;?>assets/w3-icon.png">*/ ?>
											Mass Check the markup (HTML, XHTML, …) of your pages
										</span>
									</div>
									<div class="bbit-panel-content">
										<form class="bbit-form" id="1" action="#save_with_ajax">
											<div class="bbit-form-row bbit-table-ajax-list" id="bbit-table-ajax-response">
											<?php
											bbitAjaxListTable::getInstance( $this->the_plugin )
												->setup(array(
													'id' 				=> 'bbitPageHTMLValidation',
													'show_header' 		=> true,
													'items_per_page' 	=> '10',
													'post_statuses' 	=> 'all',
													'columns'			=> array(
														'checkbox'	=> array(
															'th'	=>  'checkbox',
															'td'	=>  'checkbox',
														),

														'id'		=> array(
															'th'	=> __('ID', $this->the_plugin->localizationName),
															'td'	=> '%ID%',
															'width' => '40'
														),

														'title'		=> array(
															'th'	=> __('Title', $this->the_plugin->localizationName),
															'td'	=> '%title%',
															'align' => 'left'
														),

														'status'		=> array(
															'th'	=> __('Status', $this->the_plugin->localizationName),
															'td'	=> '%status%',
															'def'	=> '-',
															'align' => 'center',
															'width' => '40'
														),

														'nr_of_errors'		=> array(
															'th'	=> __('# of Errors:', $this->the_plugin->localizationName),
															'td'	=> '%nr_of_errors%',
															'def'	=> '-',
															'align' => 'center',
															'width' => '80'
														),

														'nr_of_warning'		=> array(
															'th'	=> __('# of Warning', $this->the_plugin->localizationName),
															'td'	=> '%nr_of_warning%',
															'def'	=> '-',
															'align' => 'center',
															'width' => '80'
														),

														'last_check_at'		=> array(
															'th'	=> __('Last check at', $this->the_plugin->localizationName),
															'td'	=> '%last_check_at%',
															'def'	=> __('Never Checked', $this->the_plugin->localizationName),
															'align' => 'center',
															'width' => '120'
														),

														'view_full_report' => array(
															'th'	=> __('View full report', $this->the_plugin->localizationName),
															'td'	=> '%view_full_report%',
															'align' => 'center',
															'width' => '120'
														),

														'date'		=> array(
															'th'	=> __('Date', $this->the_plugin->localizationName),
															'td'	=> '%date%',
															'width' => '120'
														),

														'optimize_btn' => array(
															'th'	=> __('Action', $this->the_plugin->localizationName),
															'td'	=> '%button%',
															'option' => array(
																'value' => __('Verify page', $this->the_plugin->localizationName),
																'action' => 'do_item_html_validation'
															),
															'width' => '80'
														),
													),
													'mass_actions' 	=> array(
														'html_validation' => array(
															'value' => __('Verify all selected pages', $this->the_plugin->localizationName),
															'action' => 'do_bulk_html_validation',
															'color' => 'blue'
														)
													)
												))
												->print_html();
								            ?>
								            </div>
							            </form>
				            		</div>
								</div>
							</div>
							<div class="clear"></div>
						</div>
					</div>
				</div>
			</div>
		</div>

<?php
		}

		/*
		* validate_page, method
		* ---------------------
		*
		* this will validate your page html code
		*/
		public function validate_page( $id=0 )
		{
			$html = array();
			$summary = array();
			$score = 0;
			$id = isset($_REQUEST['id']) ? (int)$_REQUEST['id'] : (int)$id;

			sleep(2);

			$checkUrl = 'http://validator.w3.org/check?uri=' . get_permalink($id);
			$browserRequest = wp_remote_get( $checkUrl );
			if ( is_wp_error( $browserRequest ) ) { // If there's error
				$body = false;
				$err = htmlspecialchars( implode(';', $browserRequest->get_error_messages()) );

				die(json_encode(array(
					'status' => 'invalid',
					'msg'	 => $err
				)));
			}
			else {
				$body = wp_remote_retrieve_body( $browserRequest );
			}

			$status = array(
				'status' => isset($browserRequest['headers']["x-w3c-validator-status"]) ? $browserRequest['headers']["x-w3c-validator-status"] : '',
				'nr_of_errors' => isset($browserRequest['headers']["x-w3c-validator-errors"]) ? $browserRequest['headers']["x-w3c-validator-errors"] : '',
				'nr_of_warning' => isset($browserRequest['headers']["x-w3c-validator-warnings"]) ? $browserRequest['headers']["x-w3c-validator-warnings"] : '',
				'recursion' => isset($browserRequest['headers']["x-w3c-validator-recursion"]) ? $browserRequest['headers']["x-w3c-validator-recursion"] : ''
			);

			if( isset($status) && count($status) != "" ){
				$status['last_check_at'] = date('Y-m-d H:i:s');
				update_post_meta($id, 'bbit_w3c_validation', $status);

				if ( $status['status'] == "" && $status['recursion'] == "" ){
					die(json_encode(array(
						'status' => 'invalid',
						'msg'	 => $body
					)));
				}

				die(json_encode(array(
					'status' => 'valid',
					'arr'	 => $status
				)));
			}

			die(json_encode(array(
				'status' => 'invalid',
				'url'	 => $checkUrl
			)));
		}
    }
}

// Initialize the bbitW3C_HTMLValidator class
//$bbitW3C_HTMLValidator = new bbitW3C_HTMLValidator($this->cfg, ( isset($module) ? $module : array()) );
$bbitW3C_HTMLValidator = bbitW3C_HTMLValidator::getInstance( $this->cfg, ( isset($module) ? $module : array()) );