<?php
/*
* Define class bbitFileEdit
* Make sure you skip down to the end of this file, as there are a few
* lines of code that are very important.
*/
if ( !defined( 'DB_NAME' ) ) {
	header( 'HTTP/1.0 403 Forbidden' );
	die;
}
if (class_exists('bbitFileEdit') != true) {
    class bbitFileEdit
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
		
		private $settings = array();
		private $settings_orig = array();

        /*
        * Required __construct() function that initalizes the Bbit Framework
        */
        public function __construct()
        {
        	global $bbit;
        	
        	$this->the_plugin = $bbit;
			$this->module_folder = $this->the_plugin->cfg['paths']['plugin_dir_url'] . 'modules/file_edit/';
			$this->module = $this->the_plugin->cfg['modules']['file_edit'];

			if (is_admin()) {
	            add_action('admin_menu', array( &$this, 'adminMenu' ));
	            
				//notice on the Settings / Reading / Search Engine Visibility
				//add_action('admin_notices', array( &$this, 'robotstxt_notice' ));
			}
			
			// ajax  helper
			add_action('wp_ajax_bbitFileEdit', array( &$this, 'ajax_request' ));
			
			$this->settings = $this->the_plugin->get_theoption( 'bbit_file_edit' );
			$this->settings_orig = $this->the_plugin->get_theoption( 'bbit_file_edit_orig' );
        }
        
		/**
	    * Singleton pattern
	    *
	    * @return bbitFileEdit Singleton instance
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
    		if ( $this->the_plugin->capabilities_user_has_module('file_edit') ) {
	    		add_submenu_page(
	    			$this->the_plugin->alias,
	    			$this->the_plugin->alias . " " . __('Files Edit', $this->the_plugin->localizationName),
		            __('Files Edit', $this->the_plugin->localizationName),
		            'read',
		            $this->the_plugin->alias . "_massFileEdit",
		            array($this, 'display_index_page')
		        );
    		}

			return $this;
		}

		public function display_index_page()
		{
			$this->printBaseInterface();
		}
		
		public function robotstxt_notice() {
			global $pagenow;
			if ( $pagenow == 'options-reading.php' ) {
				_e('<div class="updated">Notice: Because you\'re using a custom robots.txt file, the "Discourage search engines from indexing this site" setting won\'t have any effect.</div>', $this->the_plugin->localizationName);
			}
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
		<link rel='stylesheet' href='<?php echo $this->module_folder;?>app.css' type='text/css' media='screen' />
		<script type="text/javascript" src="<?php echo $this->module_folder;?>app.class.js" ></script>
		<div id="bbit-wrapper" class="fluid wrapper-bbit">
			<?php
			// show the top menu
			bbitAdminMenu::getInstance()->make_active('advanced_setup|file_edit')->show_menu();
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
					<?php echo $this->module['file_edit']['menu']['title'];?>
					<span class="bbit-section-info"><?php echo $this->module['file_edit']['description'];?></span>
					<?php
					$has_help = isset($this->module['file_edit']['help']) ? true : false;
					if( $has_help === true ){
						
						$help_type = isset($this->module['file_edit']['help']['type']) && $this->module['file_edit']['help']['type'] ? 'remote' : 'local';
						if( $help_type == 'remote' ){
							echo '<a href="#load_docs" class="bbit-show-docs" data-helptype="' . ( $help_type ) . '" data-url="' . ( $this->module['file_edit']['help']['url'] ) . '">HELP</a>';
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
											Files Edit
										</span>
									</div>
									<div class="bbit-panel-content">
										<form class="bbit-form" id="frm-save-changes" action="#save_with_ajax" method="post">
											<?php if (function_exists('wp_nonce_field')) { wp_nonce_field('bbit-file-edit-changes'); } ?>
											<input type="hidden" name="savechanges" value="ok">
											<div class="bbit-form-row bbit-table-ajax-list" id="bbit-table-ajax-response" style="padding: 0px 0px 0px 0px;">

<?php
	//save changes on form submit!
	$__saveRes = $this->saveChanges();

	$__result = array(
		'robotstxt'	=> false,
		'htaccess'	=> false
	);
	$__result['robotstxt'] = $this->getFile('robots.txt');
				
	if ( $this->verify_htaccess() )
		$__result['htaccess'] =  $this->getFile('.htaccess');
	else
		$__result['htaccess']['msg'] = __('Bạn không sử dụng Apache', $this->the_plugin->localizationName);
	
	//make short aliases
	$rt = $__result['robotstxt'];
	$ht = $__result['htaccess'];
	$showBtnSave = (bool) ($rt['status']=='active' || $ht['status']=='active');
	
	$__msg = array('rt' => array(), 'ht' => array());
	//msg: get files
	$rt!==false ? $__msg['rt'][] = $rt['msg'] : '';
	$ht!==false ? $__msg['ht'][] = $ht['msg'] : '';
	//msg: save changes!
	$__saveRes['robotstxt']!==false ? $__msg['rt'][] = $__saveRes['robotstxt']['msg'] : '';
	$__saveRes['htaccess']!==false ? $__msg['ht'][] = $__saveRes['htaccess']['msg'] : '';

	if ( !empty($__saveRes['msg']) ) {
		$__msg['rt'][] = $__saveRes['msg']['rt'];
		$__msg['ht'][] = $__saveRes['msg']['ht'];
	}
	$__msg = array_filter($__msg, array( $this, 'removeEmptyItems')); //filter empty messages!
?>

												<table class="bbit-table" style="border: none;border-bottom: 1px solid #dadada;width:100%;border-spacing:0; border-collapse:collapse;">
													<thead>
														<tr>
															<th colspan="2" align="left">
															<ul>
																<li>Bạn có thể edit 2 file này tại đây.</li>
																<li>Tìm hiểu <a href="http://www.robotstxt.org/robotstxt.html" target="_blank">robots.txt file help</a></li>
																<li>Tìm hiểu <a href="http://httpd.apache.org/docs/2.4/howto/htaccess.html" target="_blank">.htaccess file help</a></li>
															</u></th>
														</tr>
														<?php if ($showBtnSave) { ?>
														<tr>
															<td colspan="2" align="left"><input type="button" class="bbit-button blue bbit-fe-save" value="Lưu lại"><input type="button" class="bbit-button red bbit-fe-create-robots-txt" style="margin-left: 10px;" value="Create Robots.txt file"></td>
														</tr>
														<?php } ?>
														<tr>
															<td width="50%">
																<span>robots.txt file</span><br />
																<?php 
																	if ($rt!==false) { 
																		if ( $rt['status'] != 'hidden' ) {
																?>
																<textarea <?php echo $rt['status']=='disabled' ? 'disabled="disabled"' : ''; ?> style="height:300px;" rows="40" name="robotstxt" id="robotstxt"><?php echo $rt['content']; ?></textarea>
																<?php
																		}
																	}
																?>
																<span id="bbit-fe-rt-wrap"><?php echo implode('<br />', $__msg['rt']); ?></span>
															</td>
															<td width="50%">
																<span>.htaccess file</span><br />
																<?php 
																	if ($ht!==false) {
																		if ( $ht['status'] != 'hidden' ) {
																?>
																<textarea <?php echo $ht['status']=='disabled' ? 'disabled="disabled"' : ''; ?> style="height:300px;" rows="40" name="htaccess" id="htaccess"><?php echo $ht['content']; ?></textarea>
																<?php
																		}
																	}
																?>
																<span id="bbit-fe-ht-wrap"><?php echo implode('<br />', $__msg['ht']); ?></span>
															</td>
														</tr>
														<?php if ($showBtnSave) { ?>
														<tr>
															<td colspan="2" align="left"><input type="button" class="bbit-button blue bbit-fe-save" value="Lưu lại"><input type="button" class="bbit-button red bbit-fe-create-robots-txt" style="margin-left: 10px;" value="Tạo Robots.txt file"></td>
														</tr>
														<?php } ?>
													</thead>
															
													<tbody>
															
													</tbody>
												</table>
											
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
		
		private function saveChanges() {
			$__ret = array(
				'robotstxt'	=> false,
				'htaccess'	=> false,
				'msg' 		=> array()
			);
			
			$__defaults = array(
				'robotstxt'				=> null,
				'robotstxt_saved_time'	=> null,
				'htaccess'				=> null,
				'htaccess_saved_time'	=> null
			);
			$saveDb = $__defaults; $saveDb_orig = $__defaults;
			if ( isset($this->settings) && !empty($this->settings) )
				$saveDb = array_merge($__defaults, $this->settings);
			if ( isset($this->settings_orig) && !empty($this->settings_orig) )
				$saveDb_orig = array_merge($__defaults, $this->settings_orig);

			//form submited!
			if ( isset($_POST['savechanges']) && $_POST['savechanges']=='ok' ) {
				// $is_mange_options = function_exists('current_user_can') && current_user_can( 'manage_options' );
				$is_mange_options = $this->the_plugin->capabilities_user_has_module('file_edit');

				//have rights!
				if (!$is_mange_options) {
					if ( isset($_POST['robotstxt']) )
						$__ret['msg']['rt'] = '<span class="bbit-fe-err">' . sprintf( __('Không đủ quyền để cập nhật %s!', $this->the_plugin->localizationName), 'robots.txt' ) . '</span>';
					if ( isset($_POST['htaccess']) )
						$__ret['msg']['ht'] = '<span class="bbit-fe-err">' . sprintf( __('Không đủ quyền để cập nhật %s!', $this->the_plugin->localizationName), '.htaccess' ) . '</span>';
					return $__ret;
				}
				check_admin_referer('bbit-file-edit-changes');

				$__current_time = time();
				if ( isset($_POST['robotstxt']) ) {
					$__rt = $this->saveFile('robots.txt', stripslashes($_POST['robotstxt']));

					if ( $__rt ) {
						$saveDb['robotstxt'] = stripslashes($_POST['robotstxt']);
						$saveDb['robotstxt_saved_time'] = $__current_time;
						if ( is_null($saveDb_orig['robotstxt']) ) {
							$saveDb_orig['robotstxt'] = stripslashes($_POST['robotstxt']);
							$saveDb_orig['robotstxt_saved_time'] = $__current_time;
						}
					}
				}
					
				if ( isset($_POST['htaccess']) ) {
					$__ht = $this->saveFile('.htaccess', stripslashes($_POST['htaccess']));

					if ( $__ht ) {
						$saveDb['htaccess'] = stripslashes($_POST['htaccess']);
						$saveDb['htaccess_saved_time'] = $__current_time;
						if ( is_null($saveDb_orig['htaccess']) ) {
							$saveDb_orig['htaccess'] = stripslashes($_POST['htaccess']);
							$saveDb_orig['htaccess_saved_time'] = $__current_time;
						}
					}
				}
				
				$this->the_plugin->save_theoption( 'bbit_file_edit', $saveDb );
				$this->the_plugin->save_theoption( 'bbit_file_edit_orig', $saveDb_orig );
			}
			$__ret = array_merge(array(
				'robotstxt'	=> isset($__rt) ? $__rt : false,
				'htaccess'	=> isset($__ht) ? $__ht : false
			));
			return $__ret;
		}
		private function createRobotsTxt() {
			$__ret = array(
				'status'	=> false,
				'msg'		=> ''
			);
			$__fileFullPath = get_home_path() . 'robots.txt';
					$__fileHandler = fopen($__fileFullPath, 'w+b'); //open with binary safe
					$content = 'User-Agent: *
Disallow: /wp-content/plugins/';
					$__fileContent = fwrite($__fileHandler, $content);
					fclose($__fileHandler);
					
					$__ret = array_merge($__ret, array(
						'status'	=> true,
						'msg'		=> '<span class="bbit-fe-msg">' . sprintf( __('%s đã cập nhật thành công!', $this->the_plugin->localizationName), $file ) . '</span>'
					));
				return $__ret;
		}
		
		private function saveFile($file, $content) {
			$__ret = array(
				'status'	=> false,
				'msg'		=> ''
			);
			$__fileFullPath = get_home_path() . $file;

			//verify file existance!
			if ($this->verifyFileExists($__fileFullPath)) {
				//verify file is writable!
				clearstatcache();
				if (is_writable($__fileFullPath)) {
					$__fileHandler = fopen($__fileFullPath, 'w+b'); //open with binary safe
					$__fileContent = fwrite($__fileHandler, $content);
					fclose($__fileHandler);
					
					$__ret = array_merge($__ret, array(
						'status'	=> true,
						'msg'		=> '<span class="bbit-fe-msg">' . sprintf( __('%s đã cập nhật thành công!', $this->the_plugin->localizationName), $file ) . '</span>'
					));
				} else {
					$__ret['msg'] = '<span class="bbit-fe-err">' . sprintf( __('Không thể ghi file %s ', $this->the_plugin->localizationName), $file ) . '</span>';
				}
				return $__ret;
			}
			$__ret['msg'] = '<span class="bbit-fe-err">' . sprintf( __('%s does not exist or it\'s unreadable!', $this->the_plugin->localizationName), $file ) . '</span>';
			return $__ret;
		}
		
		private function verify_htaccess() {
			global $is_apache;
			if ($is_apache) {
				return $this->getFile('.htaccess');
			}
			return false;
		}
		
		private function getFile($file) {
			$__ret = array(
				'status'	=> 'hidden',
				'content'	=> '',
				'msg'		=> ''
			);
			$__fileFullPath = get_home_path() . $file;
  
			//verify file existance!
			if ($this->verifyFileExists($__fileFullPath)) {
				$__fileSize = @filesize($__fileFullPath);

				$__fileContent = '';
				$__ret['status'] = 'disabled';
				if ($__fileSize>0) {
					$__fileHandler = fopen($__fileFullPath, 'rb'); //open with binary safe
					$__fileContent = fread($__fileHandler, $__fileSize);
					fclose($__fileHandler);
					$__fileContent = esc_textarea($__fileContent);
					
					$__ret['content'] = $__fileContent;
				}
			} else {
				$__ret['msg'] = '<span class="bbit-fe-err">' . sprintf( __('The file %s does not exist or it\'s unreadable!', $this->the_plugin->localizationName), $file ) . '</span>';
				return $__ret;
			}

			//verify file is writable!
			clearstatcache();
			if (is_writable($__fileFullPath)) {
				$__ret['status'] = 'active';
			}
			else {
				$__ret['msg'] = '<span class="bbit-fe-err">' . sprintf( __('Không thể ghi file %s!', $this->the_plugin->localizationName), $file ) . '</span>';
			}
			return $__ret;
		}
		
		//verify if file exists!
		private function verifyFileExists($file, $type='file') {
			clearstatcache();
			if ($type=='file') {
				if (!file_exists($file) || !is_file($file) || !is_readable($file)) {
					return false;
				}
				return true;
			} else if ($type=='folder') {
				if (!is_dir($file) || !is_readable($file)) {
					return false;
				}
				return true;
			}
			// invalid type
			return 0;
		}
		
		//remove empty entries of an array recursively
		private function removeEmptyItems(&$item) {
			if (is_array($item) && $item) {
				$item = array_filter( $item, array( $this, 'removeEmptyItems' ));
			}
			return !!$item;
		}

		/*
		* ajax_request, method
		* --------------------
		*
		* this will create requests to 404 table
		*/
		public function ajax_request()
		{
			//echo __FILE__ . ":" . __LINE__;die . PHP_EOL;   
			global $wpdb;
			$request = array(
				'rt' 			=> isset($_REQUEST['rt']) ? trim( $_REQUEST['rt'] ) : '',
				'ht' 			=> isset($_REQUEST['ht']) ? trim( $_REQUEST['ht'] ) : '',
				'rtCreate' 		=> isset($_REQUEST['rtCreate']) ? $this->createRobotsTxt() : '',
				
			);
			
			die( json_encode(array(
				'status' => 'valid',
				'data'	=> $request
			)) );
		}
		
		private function get_home_path() {
			$home = get_option( 'home' );
			$siteurl = get_option( 'siteurl' );
			
			$home = preg_replace('/^.*?:\/\//','',get_option( 'home' ));
			$siteurl = preg_replace('/^.*?:\/\//','',get_option( 'siteurl' ));
			if ( ! empty( $home ) && 0 !== strcasecmp( $home, $siteurl ) ) {
				$wp_path_rel_to_home = str_ireplace( $home, '', $siteurl ); /* $siteurl - $home */
				$pos = strripos( str_replace( '\\', '/', $_SERVER['SCRIPT_FILENAME'] ), trailingslashit( $wp_path_rel_to_home ) );
				$home_path = substr( $_SERVER['SCRIPT_FILENAME'], 0, $pos );
				$home_path = trailingslashit( $home_path );
			} else {
				$home_path = ABSPATH;
			}
			return str_replace( '\\', '/', $home_path );
		}
    }
}

// Initialize the bbitFileEdit class
//$bbitFileEdit = new bbitFileEdit();
$bbitFileEdit = bbitFileEdit::getInstance();