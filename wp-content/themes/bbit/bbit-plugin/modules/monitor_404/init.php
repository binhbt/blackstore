<?php
/*
* Define class bbit404Monitor
* Make sure you skip down to the end of this file, as there are a few
* lines of code that are very important.
*/
if ( !defined( 'DB_NAME' ) ) {
	header( 'HTTP/1.0 403 Forbidden' );
	die;
}
if (class_exists('bbit404Monitor') != true) {
    class bbit404Monitor
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
			$this->module_folder = $this->the_plugin->cfg['paths']['plugin_dir_url'] . 'modules/monitor_404/';
			$this->module = $this->the_plugin->cfg['modules']['monitor_404'];

			if (is_admin()) {
	            add_action('admin_menu', array( &$this, 'adminMenu' ));
			}

			if ( !$this->the_plugin->verify_module_status( 'monitor_404' ) ) ; //module is inactive
			else {
				if ( $this->the_plugin->is_admin !== true )
					add_action("wp_head", array( &$this, 'store_new_404_log' ));
			}
			
			// ajax  helper
			if ( $this->the_plugin->is_admin === true ) {
				add_action('wp_ajax_bbitGet404MonitorRequest', array( &$this, 'ajax_request' ));
				add_action('wp_ajax_bbit404MonitorToRedirect', array( &$this, 'add404MonitorToRedirect' ));
			
				//delete bulk rows!
				add_action('wp_ajax_bbit_do_bulk_delete_404_rows', array( &$this, 'delete_404_rows' ));
			}
			
			// init module!
			$this->init();
        }
        
		private function init() {
			//$this->createTable();
		}

		/**
	    * Singleton pattern
	    *
	    * @return bbit404Monitor Singleton instance
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
    		if ( $this->the_plugin->capabilities_user_has_module('monitor_404') ) {
	    		add_submenu_page(
	    			$this->the_plugin->alias,
	    			$this->the_plugin->alias . " " . __('Monitor Page Not Found errors', $this->the_plugin->localizationName),
		            __('Monitor 404 errors', $this->the_plugin->localizationName),
		            'read',
		            $this->the_plugin->alias . "_mass404Monitor",
		            array($this, 'display_index_page')
		        );
    		}

			return $this;
		}

		public function display_index_page()
		{
			$this->printBaseInterface();
		}
		
		/**
		 * backend methods: build the admin interface
		 *
		 */
		private function createTable() {
			global $wpdb;
			
			// check if table exist, if not create table
			$table_name = $wpdb->prefix . "bbit_monitor_404";
			if ($wpdb->get_var( "show tables like '$table_name'" ) != $table_name) {

		            $sql = "CREATE TABLE IF NOT EXISTS " . $table_name . " (
						`id` INT(10) NOT NULL AUTO_INCREMENT,
						`hits` INT(10) NULL DEFAULT '1',
						`url` VARCHAR(200) NULL DEFAULT NULL,
						`referrers` TEXT NULL DEFAULT NULL,
						`user_agents` TEXT NULL DEFAULT NULL,
						`data` TIMESTAMP NOT NULL DEFAULT current_timestamp
						PRIMARY KEY (`id`),
						UNIQUE INDEX `uniq_urls` (`url`)
					);";
		            //`deleted` SMALLINT(1) NULL DEFAULT '0',

		            require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

		            dbDelta($sql);
			}
		}


	    /**
	    * Store new 404 error log
	    */
		public function store_new_404_log()
		{
			if(is_404()) {
				global $wpdb, $_path, $bbit; // this is how you get access to the database

				// collect data for insert into DB
				# Request URI
				$visitor_request_uri = ($_SERVER['HTTPS'] == 'on') ? 'https://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'] :  'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
		        # Referer
		        $visitor_referer = $_SERVER['HTTP_REFERER'];
				# user agent
				$user_agent = $_SERVER['HTTP_USER_AGENT'];
				
				//doing_wp_cron
				if( preg_match('/doing_wp_cron/i', $visitor_request_uri) == false ){
					// escape mysql injections
					$visitor_request_uri = mysql_real_escape_string($visitor_request_uri);
					$visitor_referer = mysql_real_escape_string($visitor_referer);
					$user_agent = mysql_real_escape_string($user_agent);
	
					$table_name = $wpdb->prefix . "bbit_monitor_404";
	
					// create insert or update
					$query = "INSERT IGNORE INTO " . ($table_name) . "
					(
						url,
						referrers,
						user_agents
					)
					VALUES (
						'$visitor_request_uri',
						'$visitor_referer',
						'$user_agent'
					)";
					if ($wpdb->query($query) == 0) {
						// record already exist, update hits
						$query_update = "UPDATE " . ($table_name) . " set
							hits=hits+1,
							referrers=CONCAT(referrers, '\n$visitor_referer'),
							user_agents=CONCAT(user_agents, '\n$user_agent')
							where url='$visitor_request_uri'";
						$wpdb->query($query_update);
					}
				}
			}
		}
		
		/**
		 * delete Bulk 404 rows!
		 */
		public function delete_404_rows() {
			global $wpdb; // this is how you get access to the database
			
			$request = array(
				'id' 			=> isset($_REQUEST['id']) && !empty($_REQUEST['id']) ? trim($_REQUEST['id']) : 0
			);
			if ($request['id']!=0) {
				$__rq2 = array();
				$__rq = explode(',', $request['id']);
				if (is_array($__rq) && count($__rq)>0) {
					foreach ($__rq as $k=>$v) {
						$__rq2[] = (int) $v;
					}
				} else {
					$__rq2[] = $__rq;
				}
				$request['id'] = implode(',', $__rq2);
			}
				
			$table_name = $wpdb->prefix . "bbit_monitor_404";
			if ($wpdb->get_var("show tables like '$table_name'") == $table_name) {

				// delete record
				$query_delete = "DELETE FROM " . ($table_name) . " where 1=1 and id in (" . ($request['id']) . ");";
				$__stat = $wpdb->query($query_delete);
				
				/*$query_update = "UPDATE " . ($table_name) . " set
						deleted=1
						where id in (" . ($request['id']) . ");";
				$__stat = $wpdb->query($query_update);*/
				
				if ($__stat!== false) {
					//keep page number & items number per page
					$_SESSION['bbitListTable']['keepvar'] = array('posts_per_page'=>true);

					die( json_encode(array(
						'status' => 'valid',
						'msg'	 => ''
					)) );
				}
			}
			
			die( json_encode(array(
				'status' => 'invalid',
				'msg'	 => ''
			)) );
		}
		
		public function add404MonitorToRedirect() {
			global $wpdb;
			
			$request = array(
				'itemid' 		=> isset($_REQUEST['itemid']) && !empty($_REQUEST['itemid']) ? trim($_REQUEST['itemid']) : 0,
				'subaction' 	=> isset($_REQUEST['subaction']) ? trim($_REQUEST['subaction']) : '',
				'url_redirect'	=> isset($_REQUEST['new_url_redirect2']) ? trim($_REQUEST['new_url_redirect2']) : ''
			);
			
			$request['id'] = $request['itemid'];

			if ($request['id']!=0) {
				$__rq2 = array();
				$__rq = explode(',', $request['id']);
				if (is_array($__rq) && count($__rq)>0) {
					foreach ($__rq as $k=>$v) {
						$__rq2[] = (int) $v;
					}
				} else {
					$__rq2[] = $__rq;
				}
				$request['id'] = implode(',', $__rq2);
			}
			
			$sql = "
				INSERT INTO " . ( $wpdb->prefix ) . "bbit_link_redirect (url, url_redirect)
				 SELECT url, %s FROM " . ( $wpdb->prefix ) . "bbit_monitor_404 AS a
				 WHERE 1=1 AND a.id IN (" . $request['id'] . ");
			";
			$sql = $wpdb->prepare( $sql, $request['url_redirect'] );
			$__stat = $wpdb->query( $sql );
			
			if ($__stat!== false) {
				//keep page number & items number per page
				$_SESSION['bbitListTable']['keepvar'] = array('paged'=>true,'posts_per_page'=>true);
					
				die( json_encode(array(
					'status' => 'valid',
					'msg'	 => '',
					'nbrows' => $__stat
				)) );
			}
					
			die( json_encode(array(
				'status' => 'invalid',
				'msg'	 => ''
			)) );
		}


		/*
		* printBaseInterface, method
		* --------------------------
		*
		* this will add the base DOM code for you options interface
		*/
		private function printBaseInterface()
		{
			global $wpdb;
?>
		<script type="text/javascript" src="<?php echo $this->module_folder;?>app.class.js" ></script>
		<div id="bbit-wrapper" class="fluid wrapper-bbit">
			<?php
			// show the top menu
			bbitAdminMenu::getInstance()->make_active('monitoring|monitor_404')->show_menu();
			?>
			
			<div id="bbit-lightbox-overlay">
				<div id="bbit-lightbox-container">
					<h1 class="bbit-lightbox-headline">
						<img class="bbit-lightbox-icon" src="<?php echo $this->the_plugin->cfg['paths']['freamwork_dir_url'];?>images/light-bulb.png">
						<span id="link-details">Details:</span>
						<span id="link-add-redirect">Add to Link Redirect:</span>
						<a href="#" class="bbit-close-btn" title="Close Lightbox"></a>
					</h1>

					<div class="bbit-seo-status-container">
						<div id="bbit-lightbox-seo-report-response"></div>
						
						<div id="bbit-lightbox-seo-report-response2">
							<form class="bbit-update-link-form">
								<input type="hidden" id="upd-itemid" name="upd-itemid" value="" />
								<table width="100%">
									<tr>
										<td width="120"><label>URL:</label></td>
										<td><span id="old_url_list"></span></td>
									</tr>
									<tr>
										<td><label>URL Redirect:</label></td>
										<td><input type="text" id="new_url_redirect2" name="new_url_redirect2" value="" class="bbit-add-link-field" /></td>
									</tr>
									<tr>
										<td></td>
										<td>
											<input type="button" class="bbit-button green" value="Add to Link Redirect" id="bbit-submit-to-builder2">
										</td>
									</tr>
								</table>
								
							</form>
						</div>
						<div style="clear:both"></div>
					</div>
				</div>
			</div>

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
					<?php echo $this->module['monitor_404']['menu']['title'];?>
					<span class="bbit-section-info"><?php echo $this->module['monitor_404']['description'];?></span>
					<?php
					$has_help = isset($this->module['monitor_404']['help']) ? true : false;
					if( $has_help === true ){
						
						$help_type = isset($this->module['monitor_404']['help']['type']) && $this->module['monitor_404']['help']['type'] ? 'remote' : 'local';
						if( $help_type == 'remote' ){
							echo '<a href="#load_docs" class="bbit-show-docs" data-helptype="' . ( $help_type ) . '" data-url="' . ( $this->module['monitor_404']['help']['url'] ) . '">HELP</a>';
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
											<?php /*<img src="<?php echo $this->the_plugin->cfg['paths']['plugin_dir_url'];?>/modules/Social_Stats/assets/menu_icon.png">*/ ?>
											Monitor Page Not Found Errors
										</span>
									</div>
									<div class="bbit-panel-content">
										<form class="bbit-form" id="1" action="#save_with_ajax">
											<div class="bbit-form-row bbit-table-ajax-list" id="bbit-table-ajax-response">
											<?php
											bbitAjaxListTable::getInstance( $this->the_plugin )
												->setup(array(
													'id' 				=> 'bbitMonitor404',
													'custom_table'		=> "bbit_monitor_404",
													'custom_table_force_action' => true,
													//'deleted_field'		=> true,
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
															'td'	=> '%id%',
															'width' => '40'
														),

														'hits'		=> array(
															'th'	=> __('Hits', $this->the_plugin->localizationName),
															'td'	=> '%hits%',
															'width' => '40'
														),

														'bad_url'		=> array(
															'th'	=> __('Bad URL', $this->the_plugin->localizationName),
															'td'	=> '%bad_url%',
															'align' => 'left'
														),

														'referrers'		=> array(
															'th'	=> __('Referrers', $this->the_plugin->localizationName),
															'td'	=> '%referrers%',
															'align' => 'center',
															'width' => '80'
														),

														'user_agents'	=> array(
															'th'	=> __('User Agents', $this->the_plugin->localizationName),
															'td'	=> '%user_agents%',
															'align' => 'center',
															'width' => '80'
														),

														'last_date'		=> array(
															'th'	=> __('Last Log Date', $this->the_plugin->localizationName),
															'td'	=> '%last_date%',
															'width' => '120'
														)
													),
													'mass_actions' 	=> array(
														'add_new_link' => array(
															'value' => __('Add to Link Redirect', $this->the_plugin->localizationName),
															'action' => 'do_add_new_link',
															'color' => 'blue'
														),
														'delete_404_rows' => array(
															'value' => __('Xóa mục đã chọn', $this->the_plugin->localizationName),
															'action' => 'do_bulk_delete_404_rows',
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
		* ajax_request, method
		* --------------------
		*
		* this will create requests to 404 table
		*/
		public function ajax_request()
		{
			global $wpdb;
			$request = array(
				'id' 			=> isset($_REQUEST['id']) ? (int)$_REQUEST['id'] : 0,
				'sub_action' 	=> isset($_REQUEST['sub_action']) ? strtolower($_REQUEST['sub_action']) : ''
			);

			$res = $wpdb->get_var( "SELECT " . ( $request['sub_action'] ) . " from " . $wpdb->prefix . "bbit_monitor_404 WHERE 1=1 and id=" . ( $request['id'] ) . ";" );
			
			die( json_encode(array(
				'status' => 'valid',
				'data'	=> implode( '<br />', explode( PHP_EOL, $res ) )
				//'data'	=> $wpdb->get_var( "SELECT " . ( $request['sub_action'] ) . " from " . $wpdb->prefix . "bbit_monitor_404 WHERE 1=1 and deleted=0 and id=" . ( $request['id'] ) . ";" )
			)) );
		}
    }
}

// Initialize the bbit404Monitor class
//$bbit404Monitor = new bbit404Monitor($this->cfg, ( isset($module) ? $module : array()) );
$bbit404Monitor = bbit404Monitor::getInstance( $this->cfg, ( isset($module) ? $module : array()) );