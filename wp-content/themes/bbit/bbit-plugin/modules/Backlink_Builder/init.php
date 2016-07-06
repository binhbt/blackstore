<?php
/*
* Define class pssBacklinkBuilder
* Make sure you skip down to the end of this file, as there are a few
* lines of code that are very important.
*/
if ( !defined( 'DB_NAME' ) ) {
	header( 'HTTP/1.0 403 Forbidden' );
	die;
}
if (class_exists('pssBacklinkBuilder') != true) {
    class pssBacklinkBuilder
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
		
		static private $importDirectoryRowsUrl = 'http://sv.bbit.vn/bbit-api/backlinkbuilder.json';

        /*
        * Required __construct() function that initalizes the Bbit Framework
        */
        public function __construct()
        {
        	global $bbit;

        	$this->the_plugin = $bbit;
			$this->module_folder = $this->the_plugin->cfg['paths']['plugin_dir_url'] . 'modules/Backlink_Builder/';
			$this->module = $this->the_plugin->cfg['modules']['Backlink_Builder'];

			if (is_admin()) {
	            add_action('admin_menu', array( &$this, 'adminMenu' ));
			}
			
			// ajax helper
			if ( $this->the_plugin->is_admin === true ) {
				add_action('wp_ajax_bbitPageBuilderRequest', array( $this, 'ajax_request' ));
			}
        }
        

		/**
	    * Singleton pattern
	    *
	    * @return pssBacklinkBuilder Singleton instance
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
    		if ( $this->the_plugin->capabilities_user_has_module('Backlink_Builder') ) {
	    		add_submenu_page(
	    			$this->the_plugin->alias,
	    			$this->the_plugin->alias . " " . __('Backlink Builder', $this->the_plugin->localizationName),
		            __('Backlink Builder', $this->the_plugin->localizationName),
		            'read',
		            $this->the_plugin->alias . "_Backlink_Builder",
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
				
			$table_name = $wpdb->prefix . "bbit_web_directories";
			if ($wpdb->get_var("show tables like '$table_name'") == $table_name) {

				// delete record
				$query_delete = "DELETE FROM " . ($table_name) . " where 1=1 and id in (" . ($request['id']) . ");";
				$__stat = $wpdb->query($query_delete);
				
				
				if ($__stat!== false)
					die( json_encode(array(
						'status' => 'valid',
						'msg'	 => ''
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
			bbitAdminMenu::getInstance()->make_active('off_page_optimization|Backlink_Builder')->show_menu();
			?>
			<div id="bbit-lightbox-overlay">
				<div id="bbit-lightbox-container">
					<h1 class="bbit-lightbox-headline">
						<span style="left: 10px;">Bạn đã submit?:</span>
						<a href="#" class="bbit-close-btn" title="Close Lightbox"></a>
					</h1>

					<div class="bbit-seo-status-container" style="margin: 30px 0 0;">
			
						<div id="bbit-lightbox-backlink-builder-response" style="text-align: center;">
							<br /><br />
							<a href="#" data-status="success" class="bbit-button green bbit-submit-status">Submit thành công</a>&nbsp;
							<a href="#" data-status="error" class="bbit-button red bbit-submit-status">Bị lỗi</a>
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
					<?php echo $this->module['Backlink_Builder']['menu']['title'];?>
					<span class="bbit-section-info"><?php echo $this->module['Backlink_Builder']['description'];?></span>
					<?php
					$has_help = isset($this->module['Backlink_Builder']['help']) ? true : false;
					if( $has_help === true ){
						
						$help_type = isset($this->module['Backlink_Builder']['help']['type']) && $this->module['Backlink_Builder']['help']['type'] ? 'remote' : 'local';
						if( $help_type == 'remote' ){
							echo '<a href="#load_docs" class="bbit-show-docs" data-helptype="' . ( $help_type ) . '" data-url="' . ( $this->module['Backlink_Builder']['help']['url'] ) . '">HELP</a>';
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
											<img src="<?php echo $this->module_folder;?>assets/link.png">
											Tự động xây dựng backlink
										</span>
									</div>
									<div class="bbit-panel-content">
										<div style="display: none;" id="bbit-submit-status-values">
											<div class="submit_never">Chưa submit bao giờ</div>
											<div class="submit_inprogress">Đang submit</div>
											<div class="submit_error">Lỗi</div>
											<div class="submit_success">Submit thành công</div>
											
											
										</div>
										<form class="bbit-form" id="1" action="#save_with_ajax">
											<div class="bbit-form-row bbit-table-ajax-list" id="bbit-table-ajax-response">
											<?php
											bbitAjaxListTable::getInstance( $this->the_plugin )
												->setup(array(
													'id' 				=> 'bbitWebDirectories',
													'custom_table'		=> "bbit_web_directories",
													'custom_table_force_action' => true,
													//'deleted_field'		=> true,
													'show_header' 		=> true,
													'items_per_page' 	=> '10',
													'post_statuses' 	=> 'all',
													'notices'			=> array(
														'default_clause'	=> 'empty',
														'default'			=> '<span class="bbit-message bbit-warning" style="display: block;">' . __('Click nút Import để load danh sách các trang có thể build backlinks!', $this->the_plugin->localizationName) . '</span>'
													),
													'columns'			=> array(
														'checkbox'	=> array(
															'th'	=>  'checkbox',
															'td'	=>  'checkbox',
														),
														
														'submit_btn'		=> array(
															'th'	=> __('Submit', $this->the_plugin->localizationName),
															'td'	=> '%submit_btn%',
															'align' => 'center',
															'width' => '120'
														),
														
														
														'submit_status'		=> array(
															'th'	=> __('Trạng thái', $this->the_plugin->localizationName),
															'td'	=> '%submit_status%',
															'align' => 'center',
															'width' => '120'
														),

														'directory_name'		=> array(
															'th'	=> __('Trang web', $this->the_plugin->localizationName),
															'td'	=> '%directory_name%',
															'align' => 'left'
														),

														'pagerank'		=> array(
															'th'	=> '<img src="' . ( $this->module_folder ) . 'assets/google.png" style="position: relative;bottom: -3px; left: -2px;"> ' . __('Pagerank', $this->the_plugin->localizationName),
															'td'	=> '%pagerank%',
															'align' => 'center',
															'width' => '80'
														),
														
														'alexa'		=> array(
															'th'	=> '<img src="' . ( $this->module_folder ) . 'assets/alexa.png" style="position: relative;bottom: -3px; left: -2px"> ' . __('Alexa', $this->the_plugin->localizationName),
															'td'	=> '%alexa%',
															'align' => 'center',
															'width' => '70'
														)
													),
													'mass_actions' 	=> array(
														
														'import' => array(
															'value' => __('Import danh sách site', $this->the_plugin->localizationName),
															'action' => 'import_directory_rows',
															'color' => 'blue'
														),
														'delete_directory' => array(
															'value' => __('Xóa mục đã chọn', $this->the_plugin->localizationName),
															'action' => 'do_bulk_delete_directory_rows',
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
							
							<?php
							$website_profile = get_option( 'bbit_website_profile', true );
							$website_profile = maybe_unserialize( $website_profile );

							if( $website_profile === true || count($website_profile) == 0 ){
								global $current_user;
	      						get_currentuserinfo();
								$page_details = $this->the_plugin->get_page_meta( home_url() );
								$website_profile_values = array(
									'page_title' => $page_details['page_title'],
									'page_meta_description' => $page_details['page_meta_description'],
									'page_meta_keywords' => $page_details['page_meta_keywords'],
									'author_name' => $current_user->user_firstname . " " . $current_user->user_lastname,
									'author_email' => $current_user->user_email
								);
							}else{
								
								$website_profile_values = array(
									'page_title' => $website_profile['website_title'],
									'page_meta_description' => $website_profile['website_meta_description'],
									'page_meta_keywords' => $website_profile['website_meta_keywords'],
									'author_name' => $website_profile['website_author_name'],
									'author_email' => $website_profile['website_author_email']
								);
							}
							?>
							<div class="bbit-grid_4">
								<div class="bbit-panel">
									<div class="bbit-panel-header">
										<span class="bbit-panel-title"> 
											<img src="<?php echo $this->module_folder;?>assets/website.png">
											Autofill
										</span>
									</div>
									<div class="bbit-panel-content">
										<form action="#save_with_ajax" id="bbit_website_profile" class="bbit-form">
											
											<div class="bbit-message" style="padding-left: 10px;">
												Kéo nút này vào bookmark:
												<a class="bbit-button orange" style="display:inline-block; margin: 0px 0px 0px 10px; position: relative; bottom: -6px;" href="javascript:(function(){document.body.appendChild(document.createElement('script')).src='<?php echo $this->module_folder;?>/backlink.php';})();">Autofill <?php echo get_bloginfo();?> Metas</a><br>
											</div>
											
											<input type="hidden" value="bbit_website_profile" name="box_id" id="box_id">
											<input type="hidden" id="box_nonce" name="box_nonce" value="<?php echo wp_create_nonce( 'bbit_website_profile-nonce');?>" />
											
											<div class="bbit-form-row">
												<label for="services">Tên:</label>
												<div class="bbit-form-item large">
													<span class="formNote"><span style="color:red">*</span> Bắt buộc.</span>
													<input type="text" value="<?php echo $website_profile_values['author_name'];?>" name="website_author_name" id="website_author_name" style="width:30%">
												</div>
											</div>
											
											<div class="bbit-form-row">
												<label for="services">Email:</label>
												<div class="bbit-form-item large">
													<span class="formNote"><span style="color:red">*</span> Bắt buộc.</span>
													<input type="text" value="<?php echo $website_profile_values['author_email'];?>" name="website_author_email" id="website_author_email" style="width:35%">
												</div>
											</div>
											
											<div class="bbit-form-row">
												<label for="services">Tiêu đề:</label>
												<div class="bbit-form-item large">
													<span class="formNote"><span style="color:red">*</span> Bắt buộc.</span>
													<input type="text" value="<?php echo $website_profile_values['page_title'];?>" name="website_title" id="website_title" style="width:40%">
												</div>
											</div>
											<div class="bbit-form-row">
												<label for="services">URL:</label>
												<div class="bbit-form-item large">
													<span class="formNote"><span style="color:red">*</span> Bắt buộc.</span>
													<input type="text" readonly value="<?php echo home_url();?>" name="website_url" id="website_url" style="width:60%">
												</div>
											</div>
											<div class="bbit-form-row">
												<label for="services">Description:</label>
												<div class="bbit-form-item large">
													<span class="formNote">This field is not required.</span>
													<textarea name="website_meta_description" id="website_meta_description" style="width:40%"><?php echo $website_profile_values['page_meta_description'];?></textarea>
												</div>
											</div>
											<div class="bbit-form-row">
												<label for="services">Từ khóa:</label>
												<div class="bbit-form-item large">
													<span class="formNote">This field is not required.</span>
													<input type="text" value="<?php echo $website_profile_values['page_meta_keywords'];?>" name="website_meta_keywords" id="website_meta_keywords">
												</div>
											</div>
											<div style="display:none;" id="bbit-status-box" class="bbit-message"></div>
											<div class="bbit-button-row">
												<input type="submit" class="bbit-button green bbit-saveOptions" value="Lưu thiết lập">
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
				'sub_action' 	=> isset($_REQUEST['sub_action']) ? ($_REQUEST['sub_action']) : ''
			);

			if( $request['sub_action'] == 'changeStatus' ){
				$request['new_status'] = isset($_REQUEST['new_status']) ? $_REQUEST['new_status'] : '';
				if( $request['new_status'] == 'in_progress' ){
					$request['new_status'] = 2;
				}
				
				if( $request['new_status'] == 'success' ){
					$request['new_status'] = 1;
				}
				
				if( $request['new_status'] == 'error' ){
					$request['new_status'] = 3;
				}
  
				if( (int)$request['new_status'] > 0 ){
					
					//keep page number & items number per page
					$_SESSION['bbitListTable']['keepvar'] = array('paged'=>true,'posts_per_page'=>true);
						
					$wpdb->update( 
						$wpdb->prefix . "bbit_web_directories", 
						array( 
							'status' => $request['new_status']
						), 
						array( 'ID' => $request['id'] ), 
						array( 
							'%d'
						), 
						array( '%d' ) 
					);
					
					die( json_encode(array(
						'status' => 'valid'
					)) );
				}
			}

			if( $request['sub_action'] == 'getLightbox' ){
				$html = array();
				$row = $wpdb->get_row( "SELECT * from " . $wpdb->prefix . "bbit_web_directories WHERE 1=1 and id=" . ( $request['id'] ) . ";", ARRAY_A );
				if( $row != false && isset($row['id']) && $row['id'] == $request['id'] ){
					
					//$html[] = '<iframe id="website_frame" src="' .  home_url('?bbitGetRemoteWebsite&url=') . ( $row['submit_url'] ) . '" frameborder="0" width="100%"></iframe>';
					$html[] = '<iframe id="website_frame" src="' .  ( $row['submit_url'] ) . '" frameborder="0" width="100%"></iframe>';
					die( json_encode(array(
						'status' => 'valid',
						'data' => $row,
						'html'	=> implode( "\n", $html )
					)) );
				}
			}
			
			if( $request['sub_action'] == 'removeDirectories' ){
				$this->delete_rows();
			}
			
			if ( $request['sub_action'] == 'import_directory_rows' ) {
				$response = $this->import_directory_rows();

				die( json_encode($response) );
			}
			
			die( json_encode(array(
				'status' => 'invalid'
			)) );
		}

		public function import_directory_rows() {
			$file_url = self::$importDirectoryRowsUrl;

			$ret = array(
				'status'		=> 'invalid',
				'html'			=> ''
			);

			$response = $this->the_plugin->remote_get( $file_url, 'noproxy' );
			if ( $response['status'] != 'valid' ) {
				return array_merge($ret, array('html' => $response['msg']));
			}
  
			// valid file request
			$file_content = $response['body'];
			$rows = json_decode($file_content);
			if ( !is_array($rows) || empty($rows) ) {
				return array_merge($ret, array('html' => __('invalid rows in json file!', $this->the_plugin->localizationName)));
			}
			
			// valid file content
			global $wpdb;
			$table_name = $wpdb->prefix . "bbit_web_directories";

			$total = count($rows); $c = 0;
			foreach ($rows as $k => $v) {
				$q = "insert ignore into `$table_name` (`id`, `directory_name`, `submit_url`, `pagerank`, `alexa`, `status`) values (%s, %s, %s, %s, %s, %s);";
				$res = $wpdb->query( $wpdb->prepare($q, $v->id, $v->directory_name, $v->submit_url, $v->pagerank, $v->alexa, $v->status) );
				if ( $res!==false && $res > 0 ) { // success
					$c++;
				}
			}

			return array_merge($ret, array('status' => 'valid', 'html' => sprintf( __('total rows in remote file: %s; inserted rows: %s. Reload page?', $this->the_plugin->localizationName), $total, $c)));
		}
		
		public function get_remote_website_content()
		{
			if( isset($_REQUEST['bbitGetRemoteWebsite']) ){
				die;
				$url = isset($_REQUEST['url']) && trim($_REQUEST['url']) != "" ? $_REQUEST['url'] : '';
				// !! the best way is to made a duble check if the url is in you web directories DB
				
				
				$response = wp_remote_get( $url, array( 'timeout' => 15 ) ); 
				$html_data = wp_remote_retrieve_body( $response );
				
				require_once( $this->the_plugin->cfg['paths']['scripts_dir_path'] . '/php-query/php-query.php' );
				if ( !empty($this->the_plugin->charset) )
					$doc = bbitphpQuery::newDocument( $html_data, $this->the_plugin->charset );
				else
					$doc = bbitphpQuery::newDocument( $html_data );
				
				$the_url = parse_url($url);
				$doc->find('head')->prepend( '<base href="' . ( $the_url['scheme'] . '://' . $the_url['host'] ) . '">' );
				
				// try to find the main submit form
				$submit_form = $doc->find('form[method="post"]');
				if( $submit_form->attr('action') == "" ){
					$submit_form->attr( 'action', $url );
				}
				
				$submit_form->attr( 'target', "_blank" );
				
				die( $doc->html() );
			}
		}
    }
}

// Initialize the pssBacklinkBuilder class
//$pssBacklinkBuilder = new pssBacklinkBuilder();
$pssBacklinkBuilder = pssBacklinkBuilder::getInstance();