<?php
/*
* Define class bbitLinkRedirect
* Make sure you skip down to the end of this file, as there are a few
* lines of code that are very important.
*/
if ( !defined( 'DB_NAME' ) ) {
	header( 'HTTP/1.0 403 Forbidden' );
	die;
}
if (class_exists('bbitLinkRedirect') != true) {
    class bbitLinkRedirect
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

		static protected $_instance;
		
		/*
        * Required __construct() function that initalizes the Bbit Framework
        */
        public function __construct()
        {
        	global $bbit;
        	
        	$this->the_plugin = $bbit;
			$this->module_folder = $this->the_plugin->cfg['paths']['plugin_dir_url'] . 'modules/Link_Redirect/';
			$this->module = $this->the_plugin->cfg['modules']['Link_Redirect'];

			if ( $this->the_plugin->is_admin === true ) {
	            add_action('admin_menu', array( &$this, 'adminMenu' ));
				
				$this->settings = $this->the_plugin->getAllSettings( 'array', 'Link_Redirect' );

				// ajax handler
				add_action('wp_ajax_bbitGetUpdateDataRedirect', array( &$this, 'ajax_request' ));
				add_action('wp_ajax_bbitAddToRedirect', array( &$this, 'addToRedirect' ));
				add_action('wp_ajax_bbitRemoveFromRedirect', array( &$this, 'removeFromRedirect' ));
				add_action('wp_ajax_bbitUpdateToRedirect', array( &$this, 'updateToRedirect' ));
				
				//delete bulk rows!
				add_action('wp_ajax_bbitLinkRedirect_do_bulk_delete_rows', array( &$this, 'delete_rows' ));
			}
			
			// init module!
			if ( $this->the_plugin->is_admin !== true ) {
				$this->init();
			}
        }
        
		private function init() {
			if ( !$this->the_plugin->verify_module_status( 'Link_Redirect' ) ) ; //module is inactive
			else {
				//if ( $this->the_plugin->capabilities_user_has_module('Link_Redirect') ) {
					$this->addFrontFilters();
				//}
			}
			//$this->createTable();
		}

		/**
	    * Singleton pattern
	    *
	    * @return bbitLinkRedirect Singleton instance
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
    		if ( $this->the_plugin->capabilities_user_has_module('Link_Redirect') ) {
	    		add_submenu_page(
	    			$this->the_plugin->alias,
	    			$this->the_plugin->alias . " " . __('301 Link Redirect', $this->the_plugin->localizationName),
		            __('301 Link Redirect', $this->the_plugin->localizationName),
		            'read',
		            $this->the_plugin->alias . "_Link_Redirect",
		            array($this, 'display_index_page')
		        );
    		}

			return $this;
		}

		public function display_meta_box()
		{
			if ( $this->the_plugin->capabilities_user_has_module('Link_Redirect') ) {
				$this->printBoxInterface();
			}
		}

		public function display_index_page()
		{
			$this->printBaseInterface();
		}
		
		
		/**
		 * frontend methods: replace phrase with link!
		 *
		 */
		public function addFrontFilters() {
			add_action('wp', array( &$this, 'redirect_header' ), 0);
		}
		
		public function redirect_header(){
			global $wpdb, $wp;

			$currentUri = home_url(add_query_arg(array(), $wp->request));

			if ( !is_admin() ) {

				// get url redirect for current URI
				$__redirect = $this->getUrlRedirect( $currentUri );
				if ($__redirect===false || is_null($__redirect)) return true;
				
				// update hits!
				$this->updateUrlHits( $__redirect['id'] );
				
				$__redirect = $__redirect['url_redirect'];
				if ( preg_match('/^http|https:\/\//i', $__redirect) > 0 ) ;
				else 
					$__redirect = 'http://' . $__redirect;

				wp_redirect( $__redirect, 301 );
				exit();
			}
		}
		
		private function getUrlRedirect( $url='' ) {
			global $wpdb;
			
			if (trim($url)=='') return false;

			//$sql = "SELECT a.id, a.url_redirect from " . $wpdb->prefix . "bbit_link_redirect as a WHERE 1=1 and a.url=%s;";
			//$sql = $wpdb->prepare( $sql, $url );
			$sql = "SELECT a.id, a.url_redirect from " . $wpdb->prefix . "bbit_link_redirect as a WHERE 1=1 and a.url regexp '^".$url."/?$';";
			$res = $wpdb->get_row( $sql, ARRAY_A );
			return $res;
		}
		
		private function updateUrlHits( $id=0 ) {
			global $wpdb;
			
			$table_name = $wpdb->prefix . "bbit_link_redirect";
			$query_update = "UPDATE " . ($table_name) . " set
						hits=hits+1
						where id='$id'";
			$wpdb->query($query_update);
		}
		
		
		/**
		 * backend methods: build the admin interface
		 *
		 */
		private function createTable() {
			global $wpdb;
			
			// check if table exist, if not create table
			$table_name = $wpdb->prefix . "bbit_link_redirect";
			if ($wpdb->get_var( "show tables like '$table_name'" ) != $table_name) {

				$sql = "
					CREATE TABLE IF NOT EXISTS " . $table_name . " (
					  `id` int(10) NOT NULL AUTO_INCREMENT,
					  `hits` int(10) DEFAULT '0',
					  `url` varchar(150) DEFAULT NULL,
					  `url_redirect` varchar(150) DEFAULT NULL,
					  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
					  PRIMARY KEY (`id`),
					  UNIQUE INDEX `unique` (`url`,`url_redirect`)
					);
					";

				require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

				dbDelta($sql);
			}
		}
		
		/*
		* addToRedirect, method
		* ---------------------
		*
		* add new row into link redirect table 
		*/
		public function addToRedirect( $info=array() )
		{
			global $wpdb;
			$request = array(
				'url' 			=> isset($_REQUEST['new_url']) ? trim($_REQUEST['new_url']) : '',
				'url_redirect'	=> isset($_REQUEST['new_url_redirect']) ? trim($_REQUEST['new_url_redirect']) : '',
				'hits' 			=> isset($_REQUEST['new_hits']) ? trim($_REQUEST['new_hits']) : '0',
				
				'itemid' 		=> isset($_REQUEST['itemid']) ? trim($_REQUEST['itemid']) : $itemid
			);

			if ($request['url']=='' || $request['url_redirect']=='') {
					die(json_encode(array(
						'status' => 'invalid',
						'data' => ''
					)));
			}

				$wpdb->insert( 
					$wpdb->prefix . "bbit_link_redirect", 
					array( 
						'url' 			=> $request['url'],
						'url_redirect' 	=> $request['url_redirect'],
						'hits'			=> $request['hits']
					), 
					array( 
						'%s',
						'%s',
						'%d'
					)
				);
				$insert_id = $wpdb->insert_id;
				if ($insert_id<=0) {
					die(json_encode(array(
						'status' => 'invalid',
						'data' => $wpdb->last_query
					)));
				}
				
			//keep page number & items number per page
			$_SESSION['bbitListTable']['keepvar'] = array('posts_per_page'=>true);

			// return for ajax
			die(json_encode( array(
				'status' => 'valid',
				'data' => $wpdb->last_query
			)));
		}
		
		/*
		* updateToRedirect, method
		* --------------------------
		*
		* update row from link redirect table
		*/
		public function updateToRedirect()
		{
			global $wpdb;
			
			$request = array(
				'itemid' 		=> isset($_REQUEST['itemid']) ? (int)$_REQUEST['itemid'] : 0,
				'subaction' 	=> isset($_REQUEST['subaction']) ? trim($_REQUEST['subaction']) : '',
				'url_redirect'	=> isset($_REQUEST['new_url_redirect2']) ? trim($_REQUEST['new_url_redirect2']) : ''
			);
			
			if( $request['itemid'] > 0 ) {
				$row = $wpdb->get_row( "SELECT * FROM " . ( $wpdb->prefix ) . "bbit_link_redirect WHERE id = '" . ( $request['itemid'] ) . "'", ARRAY_A );
				
				$row_id = (int)$row['id'];

				if ($row_id>0) {
				
						// update row info!
						$wpdb->update( 
							$wpdb->prefix . "bbit_link_redirect", 
							array( 
								'url_redirect'		=> $request['url_redirect']
							), 
							array( 'id' => $row_id ), 
							array( 
								'%s'
							), 
							array( '%d' ) 
						);
						
						//keep page number & items number per page
						$_SESSION['bbitListTable']['keepvar'] = array('paged'=>true,'posts_per_page'=>true);
					
						die(json_encode(array(
							'status' => 'valid'
						)));
				
				}

			}
			
			die(json_encode(array(
				'status' => 'invalid'
			)));
		}
		
		/*
		* removeFromTable method
		* --------------------------
		*
		* remove (url,phrase) pair from table!
		*/
		public function removeFromRedirect()
		{
			global $wpdb;
			
			$request = array(
				'itemid' 	=> isset($_REQUEST['itemid']) ? (int)$_REQUEST['itemid'] : 0
			);
			
			if( $request['itemid'] > 0 ) {
				$wpdb->delete( 
					$wpdb->prefix . "bbit_link_redirect", 
					array( 'id' => $request['itemid'] ) 
				);
				
				//keep page number & items number per page
				$_SESSION['bbitListTable']['keepvar'] = array('posts_per_page'=>true);
				
				die(json_encode(array(
					'status' => 'valid'
				)));
			}
			
			die(json_encode(array(
				'status' => 'invalid'
			)));
		}
		
		/**
		 * delete Bulk rows!
		 */
		public function delete_rows() {
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

			$table_name = $wpdb->prefix . "bbit_link_redirect";
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
						'msg'	 => '' //$query_delete
					)) );
				}
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
?>
		<script type="text/javascript" src="<?php echo $this->module_folder;?>app.class.js" ></script>
		<link rel='stylesheet' href='<?php echo $this->module_folder;?>app.css' type='text/css' media='all' />
		<div id="bbit-wrapper" class="fluid wrapper-bbit">
			<?php
			// show the top menu
			bbitAdminMenu::getInstance()->make_active('off_page_optimization|Link_Redirect')->show_menu();
			?>
			
			<div id="bbit-lightbox-overlay">
				<div id="bbit-lightbox-container">
					<h1 class="bbit-lightbox-headline">
						<img class="bbit-lightbox-icon" src="<?php echo $this->the_plugin->cfg['paths']['freamwork_dir_url'];?>images/light-bulb.png">
						<span id="link-title-add">Thêm link mới:</span>
						<span id="link-title-upd">Update link:</span>
						<a href="#" class="bbit-close-btn" title="Close Lightbox"></a>
					</h1>

					<div class="bbit-seo-status-container">
						<div id="bbit-lightbox-seo-report-response">
							<form class="bbit-add-link-form">
								<table width="100%">
									<tr>
										<td width="80"><label>URL:</label></td>
										<td><input type="text" id="new_url" name="new_url" value="" class="bbit-add-link-field" /></td>
									</tr>
									<tr>
										<td><label>URL Redirect:</label></td>
										<td><input type="text" id="new_url_redirect" name="new_url_redirect" value="" class="bbit-add-link-field" /></td>
									</tr>
									<tr>
										<td></td>
										<td>
											<input type="button" class="bbit-button green" value="Thêm link" id="bbit-submit-to-builder">
										</td>
									</tr>
								</table>
								
							</form>
						</div>
						
						<div id="bbit-lightbox-seo-report-response2">
							<form class="bbit-update-link-form">
								<input type="hidden" id="upd-itemid" name="upd-itemid" value="" />
								<table width="100%">
									<tr>
										<td width="80"><label>URL:</label></td>
										<td><input type="text" id="new_url2" name="new_url2" value="" class="bbit-add-link-field" readonly disabled="disabled" /></td>
									</tr>
									<tr>
										<td><label>URL Redirect:</label></td>
										<td><input type="text" id="new_url_redirect2" name="new_url_redirect2" value="" class="bbit-add-link-field" /></td>
									</tr>
									<tr>
										<td></td>
										<td>
											<input type="button" class="bbit-button green" value="Update link info" id="bbit-submit-to-builder2">
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
					<?php echo $this->module['Link_Redirect']['menu']['title'];?>
					<span class="bbit-section-info"><?php echo $this->module['Link_Redirect']['description'];?></span>
					<?php
					$has_help = isset($this->module['Link_Redirect']['help']) ? true : false;
					if( $has_help === true ){
						
						$help_type = isset($this->module['Link_Redirect']['help']['type']) && $this->module['Link_Redirect']['help']['type'] ? 'remote' : 'local';
						if( $help_type == 'remote' ){
							echo '<a href="#load_docs" class="bbit-show-docs" data-helptype="' . ( $help_type ) . '" data-url="' . ( $this->module['Link_Redirect']['help']['url'] ) . '">HELP</a>';
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
											301 Link Redirect
										</span>
									</div>
									<div class="bbit-panel-content">
										<form class="bbit-form" id="1" action="#save_with_ajax">
											<div class="bbit-form-row bbit-table-ajax-list" id="bbit-table-ajax-response">
											<?php
											bbitAjaxListTable::getInstance( $this->the_plugin )
												->setup(array(
													'id' 				=> 'bbitLinkRedirect',
													'custom_table'		=> "bbit_link_redirect",
													'custom_table_force_action' => true,
													//'deleted_field'		=> true,
													'force_publish_field'=> false,
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
															'width' => '20'
														),

														'hits'		=> array(
															'th'	=> __('Hits', $this->the_plugin->localizationName),
															'td'	=> '%hits%',
															'width' => '15'
														),

														'url'		=> array(
															'th'	=> __('URL', $this->the_plugin->localizationName),
															'td'	=> '%linkred_url%',
															'align' => 'left'
														),

														'url_redirect'		=> array(
															'th'	=> __('URL Redirect', $this->the_plugin->localizationName),
															'td'	=> '%linkred_url_redirect%',
															'align' => 'left'
														),
														
														'created'		=> array(
															'th'	=> __('Creation Date', $this->the_plugin->localizationName),
															'td'	=> '%created%',
															'width' => '115'
														),
														
																'update_btn' => array(
																	'th'	=> __('Update', $this->the_plugin->localizationName),
																	'td'	=> '%button%',
																	'option' => array(
																		'value' => __('Update', $this->the_plugin->localizationName),
																		'action' => 'do_item_update',
																		'color'	=> 'blue',
																	),
																	'width' => '30'
																),
					
																'delete_btn' => array(
																	'th'	=> __('Delete', $this->the_plugin->localizationName),
																	'td'	=> '%button%',
																	'option' => array(
																		'value' => __('Delete', $this->the_plugin->localizationName),
																		'action' => 'do_item_delete',
																		'color'	=> 'red',
																	),
																	'width' => '30'
																)
													),
													'mass_actions' 	=> array(
														'add_new_link' => array(
															'value' => __('Thêm link mới', $this->the_plugin->localizationName),
															'action' => 'do_add_new_link',
															'color' => 'blue'
														),
														'delete_all_rows' => array(
															'value' => __('Xóa mục đã chọn', $this->the_plugin->localizationName),
															'action' => 'do_bulk_delete_rows',
															'color' => 'red'
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
		
		public function ajax_request()
		{
			global $wpdb;

			$request = array(
				'itemid' 		=> isset($_REQUEST['itemid']) ? (int)$_REQUEST['itemid'] : 0
			);
			
			die( json_encode(array(
				'status' => 'valid',
				'data'	=> $wpdb->get_row( "SELECT * from " . $wpdb->prefix . "bbit_link_redirect WHERE 1=1 and id=" . ( $request['itemid'] ) . ";" )
			)) );
		}
		
		private function prepareForInList($v) {
			return "'".$v."'";
		}

    }
}

// Initialize the bbitLinkRedirect class
$bbitLinkRedirect = bbitLinkRedirect::getInstance();