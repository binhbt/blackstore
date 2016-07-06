<?php
/*
* Define class bbitRemoteSupport
* Make sure you skip down to the end of this file, as there are a few
* lines of code that are very important.
*/
if ( !defined( 'DB_NAME' ) ) {
	header( 'HTTP/1.0 403 Forbidden' );
	die;
}

if (class_exists('bbitRemoteSupport') != true) {
    class bbitRemoteSupport
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
			$this->module_folder = $this->the_plugin->cfg['paths']['plugin_dir_url'] . 'modules/remote_support/';
			$this->module = $this->the_plugin->cfg['modules']['remote_support'];

			if (is_admin()) {
	            add_action('admin_menu', array( &$this, 'adminMenu' ));
			}

			// load the ajax helper
			require_once( $this->the_plugin->cfg['paths']['plugin_dir_path'] . 'modules/remote_support/ajax.php' );
			new bbitRemoteSupportAjax( $this->the_plugin );
        }

		/**
	    * Singleton pattern
	    *
	    * @return bbitRemoteSupport Singleton instance
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
    		if ( $this->the_plugin->capabilities_user_has_module('remote_support') ) {
	    		add_submenu_page(
	    			$this->the_plugin->alias,
	    			$this->the_plugin->alias . " " . __('Bbit Hỗ trợ trực tiếp', $this->the_plugin->localizationName),
		            __('Hỗ Trợ', $this->the_plugin->localizationName),
		            'read',
		            $this->the_plugin->alias . "_remote_support",
		            array($this, 'display_index_page')
		        );
    		}

			return $this;
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
			global $wpdb;
			
			$remote_access = get_option( 'bbit_remote_access', true );
			$login_token = get_option( 'bbit_support_login_token', true );
?>
		<script type="text/javascript" src="<?php echo $this->module_folder;?>app.class.js" ></script>
		<div id="bbit-wrapper" class="fluid wrapper-bbit">
		
			<?php
			// show the top menu
			bbitAdminMenu::getInstance()->make_active('general|remote_support')->show_menu();
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
					<?php echo $this->module['remote_support']['menu']['title'];?>
					<span class="bbit-section-info"><?php echo $this->module['remote_support']['description'];?></span>
					<?php
					$has_help = isset($this->module['remote_support']['help']) ? true : false;
					if( $has_help === true ){
						
						$help_type = isset($this->module['remote_support']['help']['type']) && $this->module['remote_support']['help']['type'] ? 'remote' : 'local';
						if( $help_type == 'remote' ){
							echo '<a href="#load_docs" class="bbit-show-docs" data-helptype="' . ( $help_type ) . '" data-url="' . ( $this->module['remote_support']['help']['url'] ) . '">HELP</a>';
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
							
							<div class="bbit-grid_4" id="bbit-boxid-access">
							    <div class="bbit-panel">
							        <div class="bbit-panel-header">
							            <span class="bbit-panel-title">
											Hỗ Trợ
										</span>
							        </div>
							        <div class="bbit-panel-content">
							            <form id="bbit_access_details" class="bbit-form">
							                <div class="bbit-form-row">
							                    <label for="protocol">Tạo mã hỗ trợ</label>
							                    <div class="bbit-form-item large">
							                        <span class="formNote">Chọn YES, sau đó chọn Lưu Hỗ Trợ để Support Team có thể hỗ trợ cho bạn</span>
							                        
							                        <?php 
							                        $selected = 'yes';
													if( 
														!isset($remote_access['bbit-create_wp_credential']) ||
														$remote_access['bbit-create_wp_credential'] == 'no'
													){
														$selected = 'no';
													}
							                        ?>
							                        <select id="bbit-create_wp_credential" name="bbit-create_wp_credential" style="width:80px;">
							                            <option value="yes" <?php echo ($selected == 'yes' ? 'selected="selected"' : '');?>>Yes</option>
							                            <option value="no" <?php echo ($selected == 'no' ? 'selected="selected"' : '');?>>NO</option>
							                        </select>
							                        
							                        <div class="bbit-wp-credential" <?php echo ( isset($remote_access['bbit-create_wp_credential']) && trim($remote_access['bbit-create_wp_credential']) == 'yes' ? 'style="display:block"' : 'style="display:none"' );?>>
							                        	<table class="bbit-table" style="border-collapse: collapse;">
							                        		<!--<tr>
							                        			<td width="160">Admin username:</td>
							                        			<td>bbit_support</td>
							                        		</tr>-->
															<br>
							                        		<tr>
							                        			<td>Mã hỗ trợ:</td>
							                        			<td>
								                        			<?php  
									                        			$admin_password = isset($remote_access['bbit-password']) ? $remote_access['bbit-password'] : $this->generateRandomString(10);
								                        			?>
								                        			<input type="text" name="bbit-password" id="bbit-password" value="<?php echo $admin_password;?>" />
							                        			</td>
							                        		</tr>
							                        	</table>
							                        	<div class="bbit-message bbit-info"><i>Chọn Lưu Hỗ Trợ, sau đó gửi mã hỗ trợ cho Support Team</i></div>
							                        </div>
							                    </div>
							                </div>
							                <!--<div class="bbit-form-row">
							                    <label for="onsite_cart">File remote access</label>
							                    <div class="bbit-form-item large">
							                        <span class="formNote">Điều này sẽ tự động cung cấp cho truy cập cho nhóm hỗ trợ Bbit vào đường dẫn máy chủ lựa chọn của bạn</span>
							                        
							                        <?php 
							                        $selected = 'yes';
													if( 
														!isset($remote_access['bbit-allow_file_remote']) ||
														$remote_access['bbit-allow_file_remote'] == 'no'
													){
														$selected = 'no';
													}
							                        ?>
							                        <select id="bbit-allow_file_remote" name="bbit-allow_file_remote" style="width:80px;">
							                            <option value="yes" <?php echo ($selected == 'yes' ? 'selected="selected"' : '');?>>Yes</option>
							                            <option value="no" <?php echo ($selected == 'no' ? 'selected="selected"' : '');?>>NO</option>
							                        </select>
							                        
							                        <div class="bbit-file-access-credential" <?php echo ( isset($remote_access['bbit-allow_file_remote']) && trim($remote_access['bbit-allow_file_remote']) == 'yes' ? 'style="display:block"' : 'style="display:none"' );?>>
							                        	<table class="bbit-table" style="border-collapse: collapse;">
							                        		<tr>
							                        			<td width="120">Access key:</td>
							                        			<td>
							                        				<?php 
									                        			$access_key = isset($remote_access['bbit-key']) ? $remote_access['bbit-key'] : md5( $this->generateRandomString(12) );
								                        			?>
							                        				<input type="text" name="bbit-key" id="bbit-key" value="<?php echo $access_key;?>" />
							                        			</td>
							                        		</tr>
							                        		<tr>
							                        			<td width="120">Access path:</td>
							                        			<td>
							                        				<input type="text" name="bbit-access_path" id="bbit-access_path" value="<?php echo isset($remote_access['bbit-access_path']) ? $remote_access['bbit-access_path'] : ABSPATH;?>" />
							                        			</td>
							                        		</tr>
							                        	</table>
							                        	<div class="bbit-message bbit-info"><i>Chọn Lưu Hỗ Trợ, sau đó gửi mã hỗ trợ cho Support Team</i> </div>
							                        </div>
							                    </div>
							                </div>-->
							                <div style="display:none;" id="bbit-status-box" class="bbit-message"></div>
							                <div class="bbit-button-row">
							                    <input type="submit" class="bbit-button blue" value="Lưu Hỗ Trợ" style="float: left;" />
							                </div>
							            </form>
							        </div>
							    </div>
							</div>
							
							<!--<div class="bbit-grid_4" id="bbit-boxid-logininfo">
	                        	<div class="bbit-panel">
									<div class="bbit-panel-content">
										<div class="bbit-message bbit-info">
											
											<?php
											if( !isset($login_token) || trim($login_token) == "" ){
											?>
												In order to contact Bbit support team you need to login into bbit.vn
											<?php 
											}
											
											else{
											?>
												Test your token is still valid on Bbit support website ...
												<script>
													bbitRemoteSupport.checkAuth( '<?php echo $login_token;?>' );
												</script>
											<?php
											}
											?>
										</div>
				            		</div>
								</div>
							</div>
							
							<div class="bbit-grid_2" id="bbit-boxid-login" style="display:none">
	                        	<div class="bbit-panel">
	                        		<div class="bbit-panel-header">
										<span class="bbit-panel-title">
											Login
										</span>
									</div>
									<div class="bbit-panel-content">
										<form class="bbit-form" id="bbit-form-login">
											<div class="bbit-form-row">
												<label class="bbit-form-label" for="email">Email <span class="required">*</span></label>
												<div class="bbit-form-item large">
													<input type="text" id="bbit-email" name="bbit-email" class="span12">
												</div>
											</div>
											<div class="bbit-form-row">
												<label class="bbit-form-label" for="password">Password <span class="required">*</span></label>
												<div class="bbit-form-item large">
													<input type="password" id="bbit-password" name="bbit-password" class="span12">
												</div>
											</div>
											
											<div class="bbit-form-row" style="height: 79px;">
												<input type="checkbox" id="bbit-remember" name="bbit-remember" style="float: left; position: relative; bottom: -12px;">
												<label for="bbit-remember" class="bbit-form-label" style="width: 120px;">&nbsp;Remember me</label>
											</div>
											
											<div class="bbit-message bbit-error" style="display:none;"></div>
	
											<div class="bbit-button-row">
												<input type="submit" class="bbit-button blue" value="Login" style="float: left;" />
											</div>
										</form>
				            		</div>
								</div>
							</div>
							
							<div class="bbit-grid_2" id="bbit-boxid-register" style="display:none">
	                        	<div class="bbit-panel">
	                        		<div class="bbit-panel-header">
										<span class="bbit-panel-title">
											Register
										</span>
									</div>
									<div class="bbit-panel-content">
										<form class="bbit-form" id="bbit-form-register">
											<div class="bbit-message error" style="display:none;"></div>
											
											<div class="bbit-form-row">
												<label class="bbit-form-label">Your name <span class="required">*</span></label>
												<div class="bbit-form-item large">
													<input type="text" id="bbit-name-register" name="bbit-name-register" class="span12">
												</div>
											</div>
											
											<div class="bbit-form-row">
												<label class="bbit-form-label">Your email <span class="required">*</span></label>
												<div class="bbit-form-item large">
													<input type="text" id="bbit-email-register" name="bbit-email-register" class="span12">
												</div>
											</div>
											
											<div class="bbit-form-row">
												<label class="bbit-form-label">Create a password <span class="required">*</span></label>
												<div class="bbit-form-item large">
													<input type="password" id="bbit-password-register" name="bbit-password-register" class="span6">
												</div>
											</div>
											
											<div class="bbit-button-row">
												<input type="submit" class="bbit-button blue" value="Register and login" style="float: left;" />
											</div>
										</form>
				            		</div>
								</div>
							</div>-->
							
							<div class="bbit-grid_4" style="display: none;" id="bbit-boxid-ticket">
							    <div class="bbit-panel">
							        <div class="bbit-panel-header">
							            <span class="bbit-panel-title">
											Details about problem:
										</span>
							        </div>
							        <div class="bbit-panel-content">
							            <form id="bbit_add_ticket" class="bbit-form">
							            	<input type="hidden" name="bbit-token" id="bbit-token" value="<?php echo $login_token;?>" />
							            	<input type="hidden" name="bbit-site_url" id="bbit-site_url" value="<?php echo admin_url();?>" />
							            	<input type="hidden" name="bbit-wp_username" id="bbit-wp_username" value="bbit_support" />
							            	<input type="hidden" name="bbit-wp_password" id="bbit-wp_password" value="" />
							            	
							            	<input type="hidden" name="bbit-access_key" id="bbit-access_key" value="" />
							            	<input type="hidden" name="bbit-access_url" id="bbit-access_url" value="<?php echo urlencode( str_replace("http://", "", $this->module_folder) . 'remote_tunnel.php');?>" />
							            	
							                
							                <div class="bbit-form-row">
												<label class="bbit-form-label">Ticket Subject<span class="required">*</span></label>
												<div class="bbit-form-item large">
													<input type="text" id="ticket_subject" name="ticket_subject" class="span6">
												</div>
											</div>
											
							                <div class="bbit-form-row">
						                        <?php
												wp_editor( 
													'', 
													'ticket_details', 
													array( 
														'media_buttons' => false,
														'textarea_rows' => 40,	
													) 
												); 
						                        ?>
							                </div>
							                <div style="display:none;" id="bbit-status-box" class="bbit-message bbit-success"></div>
							                <div class="bbit-button-row">
							                    <input type="submit" class="bbit-button green" value="Open ticket on forum.bbit.vn" style="float: left;" />
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

		private function generateRandomString($length = 6) 
		{
		    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ@#$%^*()';
		    $randomString = '';
		    for ($i = 0; $i < $length; $i++) {
		        $randomString .= $characters[rand(0, strlen($characters) - 1)];
		    }
		    return $randomString;
		}
    }
}

// Initialize the bbitRemoteSupport class
//$bbitRemoteSupport = new bbitRemoteSupport();
$bbitRemoteSupport = bbitRemoteSupport::getInstance();