<?php
/*
* Define class bbitSocialStats
* Make sure you skip down to the end of this file, as there are a few
* lines of code that are very important.
*/
if ( !defined( 'DB_NAME' ) ) {
	header( 'HTTP/1.0 403 Forbidden' );
	die;
}

if (class_exists('bbitSocialStats') != true) {
    class bbitSocialStats
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
			$this->module_folder = $this->the_plugin->cfg['paths']['plugin_dir_url'] . 'modules/Social_Stats/';
			$this->module = $this->the_plugin->cfg['modules']['Social_Stats'];
			
			$this->plugin_settings = $this->the_plugin->get_theoption( $this->the_plugin->alias . '_social' );
			
			if (is_admin()) {
	            add_action('admin_menu', array( &$this, 'adminMenu' ));
			}
			
			$this->init();
			
			// social sharing
			if ( $this->the_plugin->is_admin !== true )
				$this->init_social_sharing();
        }
        

        /**
         * Head Filters & Init!
         *
         */
		public function init() {
		}
		
		/**
		 * Social Sharing
		 *
		 */
		public function init_social_sharing() {

			// social sharing module
			require_once( $this->the_plugin->cfg['paths']['plugin_dir_path'] . 'bbit-framework/utils/social_sharing.php' );
			$ssh = new bbitSocialSharing( $this->the_plugin );
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
    		if ( $this->the_plugin->capabilities_user_has_module('Social_Stats') ) {
	    		add_submenu_page(
	    			$this->the_plugin->alias,
	    			$this->the_plugin->alias . " " . __('Social Stats', $this->the_plugin->localizationName),
		            __('Social Stats', $this->the_plugin->localizationName),
		            'read',
		           	$this->the_plugin->alias . "_Social_Stats",
		            array($this, 'display_index_page')
		        );
    		}

			return $this;
		}
		
		public function socialstats_scripts( $socialServices=array() )
		{
			if( count($socialServices) > 220 ){
				foreach ($socialServices as $key => $value){
					if( $value == 'twitter' ){
						echo '<script type="text/javascript" src="http://platform.twitter.com/widgets.js?' . ( time() ) . '"></script>';
					}
					elseif( $value == 'google' ){
						echo '<script type="text/javascript" src="http://apis.google.com/js/plusone.js?' . ( time() ) . '"></script>';
					}
					elseif( $value == 'digg' ){
					?>
						<script type="text/javascript">
							(function() {
							  var s = document.createElement('SCRIPT'), s1 = document.getElementsByTagName('SCRIPT')[0];
							  s.type = 'text/javascript';
							  s.async = true;
							  s.src = 'http://widgets.digg.com/buttons.js';
							  s1.parentNode.insertBefore(s, s1);
							})();
						</script>
					<?php
					}
					elseif( $value == 'linkedin' ){
						echo '<script type="text/javascript" src="http://platform.linkedin.com/in.js?' . ( time() ) . '"></script>';
					}

					elseif( $value == 'stumbleupon' ){
					?>
						<script type="text/javascript">
						  (function() {
						    var li = document.createElement('script'); li.type = 'text/javascript'; li.async = true;
						    li.src = ('https:' == document.location.protocol ? 'https:' : 'http:') + '//platform.stumbleupon.com/1/widgets.js';
						    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(li, s);
						  })();
						</script>

					<?php
					}
				}
			}
			?>

		<?php
		}

		public function display_meta_box()
		{
			$this->printBoxInterface();
		}

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
			$socialServices = $this->the_plugin->get_theoption( $this->the_plugin->alias . '_social', true ); 
			
			if( isset($socialServices['services']) ) {
				$socialServices = $socialServices['services'];
			}

			//if( count($socialServices) > 0 ) $this->socialstats_scripts($socialServices);
?>
		<script type="text/javascript" src="<?php echo $this->module_folder;?>app.class.js" ></script>
		<link rel='stylesheet' href='<?php echo $this->module_folder;?>app.css' type='text/css' media='all' />
		<div id="bbit-wrapper" class="fluid wrapper-bbit">
			<?php
			// show the top menu
			bbitAdminMenu::getInstance()->make_active('off_page_optimization|Social_Stats')->show_menu();
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
					<?php echo $this->module['Social_Stats']['menu']['title'];?>
					<span class="bbit-section-info"><?php echo $this->module['Social_Stats']['description'];?></span>
					<?php
					$has_help = isset($this->module['Social_Stats']['help']) ? true : false;
					if( $has_help === true ){
						
						$help_type = isset($this->module['Social_Stats']['help']['type']) && $this->module['Social_Stats']['help']['type'] ? 'remote' : 'local';
						if( $help_type == 'remote' ){
							echo '<a href="#load_docs" class="bbit-show-docs" data-helptype="' . ( $help_type ) . '" data-url="' . ( $this->module['Social_Stats']['help']['url'] ) . '">HELP</a>';
						} 
					} 
					?>
				</h1>

				<!-- Container -->
				<div class="bbit-container clearfix">

					<!-- Main Content Wrapper -->
					<div id="bbit-content-wrap" class="clearfix" style="padding-top: 20px;">

						<!-- Content Area -->
						<div id="bbit-content-area">
							<div class="bbit-grid_4">
	                        	<div class="bbit-panel">
	                        		<div class="bbit-panel-header">
										<span class="bbit-panel-title">
											Social Stats of your pages
										</span>
									</div>
									<div class="bbit-panel-content">
										<form class="bbit-form" id="1" action="#save_with_ajax">
											<div class="bbit-form-row bbit-table-ajax-list" id="bbit-table-ajax-response">
											<?php
											$columns = array(
												'id'		=> array(
													'th'	=> __('ID', $this->the_plugin->localizationName),
													'td'	=> '%ID%',
													'width' => '40'
												),
	
												'title'		=> array(
													'th'	=> __('Title', $this->the_plugin->localizationName),
													'td'	=> '%title%',
													'align' => 'left'
												)
											);
											
											if( count($socialServices) > 0 ){
												foreach ($socialServices as $key => $value){
													if( $value == 'facebook' ){
														$columns['ss_facebook'] = array(
															'th'	=> __('Facebook', $this->the_plugin->localizationName),
															'td'	=> '%ss_facebook%',
															'width' => '80'
														);
													}
													
													if( $value == 'twitter' ){
														$columns['ss_twitter'] = array(
															'th'	=> __('Twitter', $this->the_plugin->localizationName),
															'td'	=> '%ss_twitter%',
															'width' => '80'
														);
													}
													
													if( $value == 'google' ){
														$columns['ss_google'] = array(
															'th'	=> __('Google +1', $this->the_plugin->localizationName),
															'td'	=> '%ss_google%',
															'width' => '80'
														);
													}
													
													if( $value == 'pinterest' ){
														$columns['ss_pinterest'] = array(
															'th'	=> __('Pinterest', $this->the_plugin->localizationName),
															'td'	=> '%ss_pinterest%',
															'width' => '80'
														);
													}
													
													if( $value == 'stumbleupon' ){
														$columns['ss_stumbleupon'] = array(
															'th'	=> __('Stumbleupon', $this->the_plugin->localizationName),
															'td'	=> '%ss_stumbleupon%',
															'width' => '80'
														);
													}
													
													if( $value == 'digg' ){
														$columns['ss_digg'] = array(
															'th'	=> __('Digg', $this->the_plugin->localizationName),
															'td'	=> '%ss_digg%',
															'width' => '80'
														);
													}
													
													if( $value == 'linkedin' ){
														$columns['ss_linkedin'] = array(
															'th'	=> __('Linkedin', $this->the_plugin->localizationName),
															'td'	=> '%ss_linkedin%',
															'width' => '80'
														);
													}
												}
											}
											
											$columns['date'] = array(
												'th'	=> __('Date', $this->the_plugin->localizationName),
												'td'	=> '%date%',
												'width' => '120'
											);
											
											bbitAjaxListTable::getInstance( $this->the_plugin )
												->setup(array(
													'id' 				=> 'bbitSocialStats',
													'show_header' 		=> true,
													'show_footer' 		=> false,
													'items_per_page' 	=> '10',
													'post_statuses' 	=> 'all',
													'columns'			=> $columns,
													'mass_actions'		=> false
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
		
		/**
	    * Singleton pattern
	    *
	    * @return bbitSocialStats Singleton instance
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

// Initialize the bbitSocialStats class
//$bbitSocialStats = new bbitSocialStats();
$bbitSocialStats = bbitSocialStats::getInstance();