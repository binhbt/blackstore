<?php
/*
* Define class bbitServerStatus
* Make sure you skip down to the end of this file, as there are a few
* lines of code that are very important.
*/
if ( !defined( 'DB_NAME' ) ) {
	header( 'HTTP/1.0 403 Forbidden' );
	die;
}

if (class_exists('bbitServerStatus') != true) {
    class bbitServerStatus
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
			$this->module_folder = $this->the_plugin->cfg['paths']['plugin_dir_url'] . 'modules/server_status/';
			$this->module = $this->the_plugin->cfg['modules']['server_status'];

			if (is_admin()) {
	            add_action('admin_menu', array( &$this, 'adminMenu' ));
			}

			// load the ajax helper
			require_once( $this->the_plugin->cfg['paths']['plugin_dir_path'] . 'modules/server_status/ajax.php' );
			new bbitServerStatusAjax( $this->the_plugin );
        }

		/**
	    * Singleton pattern
	    *
	    * @return bbitServerStatus Singleton instance
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
    		add_submenu_page(
    			$this->the_plugin->alias,
    			$this->the_plugin->alias . " " . __('Check System status', $this->the_plugin->localizationName),
	            __('Trạng Thái Máy Chủ', $this->the_plugin->localizationName),
	            'manage_options',
	            $this->the_plugin->alias . "_server_status",
	            array($this, 'display_index_page')
	        );

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
			
			// Google Analytics
			if ( $this->the_plugin->verify_module_status( 'Google_Analytics' ) ) { //module is inactive
			$analytics_settings = $this->the_plugin->get_theoption( $this->the_plugin->alias . '_google_analytics' );
			$analytics_mandatoryFields = array(
				'client_id'			=> false,
				'client_secret'		=> false,
				'redirect_uri'		=> false
			);
    
			// get the module init file
			require_once( $this->the_plugin->cfg['paths']['plugin_dir_path'] . 'modules/Google_Analytics/init.php' );
			// Initialize the bbitGoogleAnalytics class
			$bbitGoogleAnalytics = new bbitGoogleAnalytics();
			}
			
			// Google SERP
			$serp_settings = $this->the_plugin->get_theoption( $this->the_plugin->alias . '_serp' );
			$serp_mandatoryFields = array(
				'developer_key'			=> false,
				'custom_search_id'		=> false,
				'google_country'		=> false
			);
			
			// get the module init file
			// require_once( $this->the_plugin->cfg['paths']['plugin_dir_path'] . 'modules/google_pagespeed/init.php' );
			// Initialize the bbitSERP class
			// $bbitSERP = new bbitSERP($this->cfg, ( isset($module) ? $module : array()) );
			
			// Google Pagespeed
			$pagespeed_settings = $this->the_plugin->get_theoption( $this->the_plugin->alias . '_pagespeed' );
			$pagespeed_mandatoryFields = array(
				'developer_key'			=> false,
				'google_language'		=> false
			);
			
			// get the module init file
			// require_once( $this->the_plugin->cfg['paths']['plugin_dir_path'] . 'modules/google_pagespeed/ajax.php' );
			// Initialize the bbitPageSpeedInsightsAjax class
			// $bbitPagespeed = new bbitPageSpeedInsightsAjax($this->the_plugin);
			
			// Facebook
			if ( $this->the_plugin->verify_module_status( 'facebook_planner' ) ) { //module is inactive
			$facebook_settings = $this->the_plugin->get_theoption( $this->the_plugin->alias . '_facebook_planner' );
			$facebook_mandatoryFields = array(
				'app_id'			=> false,
				'app_secret'		=> false,
				'language'			=> false,
				'redirect_uri'		=> false
			);
			
			// get the module init file
			require_once( $this->the_plugin->cfg['paths']['plugin_dir_path'] . 'modules/facebook_planner/init.php' );
			// Initialize the bbitFacebook_Planner class
			$bbitFacebook_Planner = new bbitFacebook_Planner();
			}

			$plugin_data = get_plugin_data( $this->the_plugin->cfg['paths']['plugin_dir_path'] . 'plugin.php' );  
?>
		<link rel='stylesheet' href='<?php echo $this->module_folder;?>app.css' type='text/css' media='all' />
		<script type="text/javascript" src="<?php echo $this->module_folder;?>app.class.js" ></script>
		<div id="bbit-wrapper" class="fluid wrapper-bbit">
			
			<?php
			// show the top menu
			bbitAdminMenu::getInstance()->make_active('general|server_status')->show_menu();
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
					<?php echo $this->module['server_status']['menu']['title'];?>
					<span class="bbit-section-info"><?php echo $this->module['server_status']['description'];?></span>
					<?php
					$has_help = isset($this->module['server_status']['help']) ? true : false;
					if( $has_help === true ){
						
						$help_type = isset($this->module['server_status']['help']['type']) && $this->module['server_status']['help']['type'] ? 'remote' : 'local';
						if( $help_type == 'remote' ){
							echo '<a href="#load_docs" class="bbit-show-docs" data-helptype="' . ( $help_type ) . '" data-url="' . ( $this->module['server_status']['help']['url'] ) . '">HELP</a>';
						} 
					} 
					?>
				</h1>
				
				<!-- Container -->
				<div class="bbit-container clearfix">

					<!-- Main Content Wrapper -->
					<div id="bbit-content-wrap" class="clearfix" style="padding-top: 5px;">

						<!-- Content Area -->
						<div id="bbit-content-area">
							<div class="bbit-grid_4">
	                        	<div class="bbit-panel">
									<div class="bbit-panel-content">
										<table class="bbit-table" cellspacing="0">
											
											<thead>
												<tr>
													<th colspan="2"><?php _e( 'Modules' ); ?></th>
												</tr>
											</thead>
									
											<tbody>
									         	<tr>
									         		<td><?php _e( 'Module đã kích hoạt' ); ?>:</td>
									         		<td><div class="bbit-loading-ajax-details" data-action="active_modules"></div></td>
									         	</tr>
											</tbody>
											
<?php
// Google Analytics module
if ( $this->the_plugin->verify_module_status( 'Google_Analytics' ) ) { //module is inactive
?>
				
											<thead>
												<tr>
													<th colspan="2"><a id="sect-google_analytics" name="sect-google_analytics"></a><?php _e( 'Module Google Analytics:', $this->the_plugin->localizationName ); ?></th>
												</tr>
											</thead>
									
											<tbody>
												<tr>
									                <td width="190"><?php _e( 'Your client id',$this->the_plugin->localizationName ); ?>:</td>
									                <td>
<?php
if ( isset($analytics_settings['client_id']) && !empty($analytics_settings['client_id']) ) {
	$analytics_mandatoryFields['client_id'] = true;
	echo $analytics_settings['client_id'];
} else {
?>
<div class="bbit-begin-test-container">
	<a href="<?php echo admin_url("admin.php?page=bbit#Google_Analytics"); ?>" class="bbit-button blue bbitStressTest">Update module settings</a>
</div>
<?php
	}
?>
													</td>
									            </tr>
												<tr>
									                <td width="190"><?php _e( 'Your client secret',$this->the_plugin->localizationName ); ?>:</td>
									                <td>
<?php 
if ( isset($analytics_settings['client_secret']) && !empty($analytics_settings['client_secret']) ) {
	$analytics_mandatoryFields['client_secret'] = true;
	echo $analytics_settings['client_secret'];
} else {
?>
<div class="bbit-begin-test-container">
	<a href="<?php echo admin_url("admin.php?page=bbit#Google_Analytics"); ?>" class="bbit-button blue bbitStressTest">Update module settings</a>
</div>
<?php
	}
?>
									                </td>
									            </tr>
												<tr>
									                <td width="190"><?php _e( 'Redirect URI',$this->the_plugin->localizationName ); ?>:</td>
									                <td>
<?php 
if ( isset($analytics_settings['redirect_uri']) && !empty($analytics_settings['redirect_uri']) ) {
	$analytics_mandatoryFields['redirect_uri'] = true;
	echo $analytics_settings['redirect_uri'];
} else {
?>
<div class="bbit-begin-test-container">
	<a href="<?php echo admin_url("admin.php?page=bbit#Google_Analytics"); ?>" class="bbit-button blue bbitStressTest">Update module settings</a>
</div>
<?php
	}
?>
									                </td>
									            </tr>
												<tr>
									                <td width="190"><?php _e( 'Profile ID',$this->the_plugin->localizationName ); ?>:</td>
									                <td>
<?php 
if ( isset($analytics_settings['profile_id']) && !empty($analytics_settings['profile_id']) ) {
	echo $analytics_settings['profile_id'];
} else {
?>
<div class="bbit-begin-test-container">
	<a href="<?php echo admin_url("admin.php?page=bbit#Google_Analytics"); ?>" class="bbit-button blue bbitStressTest">Update module settings</a>
</div>
<?php
	}
?>
									                </td>
									            </tr>
												<tr>
									                <td width="190"><?php _e( 'Authorize',$this->the_plugin->localizationName ); ?>:</td>
									                <td>
<div class="bbit-begin-test-container noheight">
<?php
$mandatoryValid = true;
foreach ($analytics_mandatoryFields as $k=>$v) {
	if ( !$v ) {
		$mandatoryValid = false;
		break;
	}
}
if ( $mandatoryValid ) {
	if ( $bbitGoogleAnalytics->makeoAuthLogin() ) {
?>
	<div class="bbit-message bbit-success">
		<a href="#google-analytics/authorize" class="bbit-button blue bbitStressTest inline bbit-google-authorize-app" data-saveform="no">Re-Authorize app</a>
	&nbsp;(<?php _e( 'app is authorized',$this->the_plugin->localizationName ); ?>)
	</div>
<?php
	} else {
?>
	<div class="bbit-message bbit-info">
		<a href="#google-analytics/authorize" class="bbit-button blue bbitStressTest inline bbit-google-authorize-app" data-saveform="no">Authorize app</a>
		<span style="margin-left: 10px;">(<?php _e( 'app is not authorized yet',$this->the_plugin->localizationName ); ?>)</span>
	</div>
<?php
	}
} else {
?>
	<div class="bbit-message bbit-error">
		<?php _e( 'some mandatory module settings are missing or not valid, so first fill them and then you can authorize the app!',$this->the_plugin->localizationName ); ?>
	</div>
<?php
}
?>
</div>
									                </td>
									            </tr>
															
									            <tr>
									            	<td style="vertical-align: middle;">Verify:</td>
									                <td>
														<div class="bbit-verify-products-test">
															<div class="bbit-test-timeline">
																<div class="bbit-one_step stepid-step1 nbsteps4">
																	<div class="bbit-step-status bbit-loading-inprogress"></div>
																	<span class="bbit-step-name">Step 1</span>
																</div>
																<div class="bbit-one_step stepid-step2 nbsteps4">
																	<div class="bbit-step-status"></div>
																	<span class="bbit-step-name">Step 2</span>
																</div>
																<div class="bbit-one_step stepid-step3 nbsteps4">
																	<div class="bbit-step-status"></div>
																	<span class="bbit-step-name">Step 3</span>
																</div>
																<div class="bbit-one_step stepid-step4 nbsteps4">
																	<div class="bbit-step-status"></div>
																	<span class="bbit-step-name">Step 4</span>
																</div>
																<div style="clear:both;"></div>
															</div>
															<table class="bbit-table bbit-logs" cellspacing="0">
																<tr class="logbox-step1">
																	<td width="50">Step 1:</td>
																	<td>
																		<div class="bbit-log-title">
																			<?php _e( 'Set mandatory fields: client id, client secret, redirect uri', $this->the_plugin->localizationName ); ?>
																			<a href="#" class="bbit-button gray"><?php _e( 'View details +', $this->the_plugin->localizationName ); ?></a>
																		</div>
																		
																		<textarea class="bbit-log-details"></textarea>
																	</td>
																</tr>
																<tr class="logbox-step2">
																	<td width="50">Step 2:</td>
																	<td>
																		<div class="bbit-log-title">
																			<?php _e( 'Authorize app on Google APIs Console:', $this->the_plugin->localizationName ); ?>
																			<a target="_blank" href="https://code.google.com/apis/console/">https://code.google.com/apis/console/</a>
																			<a href="#" class="bbit-button gray"><?php _e( 'View details +', $this->the_plugin->localizationName ); ?></a>
																		</div>
																		
																		<textarea class="bbit-log-details"></textarea>
																	</td>
																</tr>
																<tr class="logbox-step3">
																	<td width="50">Step 3:</td>
																	<td>
																		<div class="bbit-log-title">
																			<?php _e( 'Get profile ID', $this->the_plugin->localizationName ); ?>
																			<a href="#" class="bbit-button gray"><?php _e( 'View details +', $this->the_plugin->localizationName ); ?></a>
																		</div>
																		
																		<textarea class="bbit-log-details"></textarea>
																	</td>
																</tr>
																<tr class="logbox-step4">
																	<td width="50">Step 4:</td>
																	<td>
																		<div class="bbit-log-title">
																			<?php _e( 'Make a test request from Google Analytics', $this->the_plugin->localizationName ); ?>
																			<?php
$today = date( 'Y-m-d' );
$from_date 	= date( 'Y-m-d', strtotime( "-1 week", strtotime( $today ) ) );
$to_date 	= date( 'Y-m-d', strtotime( $today ) );
echo " (from $from_date to $to_date)";
																			?>
																			<a href="#" class="bbit-button gray"><?php _e( 'View details +', $this->the_plugin->localizationName ); ?></a>
																		</div>
																		
																		<textarea class="bbit-log-details"></textarea>
																	</td>
																</tr>
															</table>
															<div class="bbit-begin-test-container">
																<a href="#google-analytics/verify" class="bbit-button blue bbitStressTest verify" data-module="google_analytics">Verify</a>
															</div>
														</div>
													</td>
									            </tr>
											</tbody>
<?php
} // end Google Analytics module!
?>

<?php
// Google SERP module
if ( $this->the_plugin->verify_module_status( 'serp' ) ) { //module is inactive
?>
				
											<thead>
												<tr>
													<th colspan="2"><a id="sect-google_serp" name="sect-google_serp"></a><?php _e( 'Module Google SERP:', $this->the_plugin->localizationName ); ?></th>
												</tr>
											</thead>
									
											<tbody>
												<tr>
									                <td width="190"><?php _e( 'Google Developer Key',$this->the_plugin->localizationName ); ?>:</td>
									                <td>
<?php
if ( isset($serp_settings['developer_key']) && !empty($serp_settings['developer_key']) ) {
	$serp_mandatoryFields['developer_key'] = true;
	echo $serp_settings['developer_key'];
} else {
?>
<div class="bbit-begin-test-container">
	<a href="<?php echo admin_url("admin.php?page=bbit#serp"); ?>" class="bbit-button blue bbitStressTest">Update module settings</a>
</div>
<?php
	}
?>
													</td>
									            </tr>
												<tr>
									                <td width="190"><?php _e( 'Custom Search Engine ID',$this->the_plugin->localizationName ); ?>:</td>
									                <td>
<?php 
if ( isset($serp_settings['custom_search_id']) && !empty($serp_settings['custom_search_id']) ) {
	$serp_mandatoryFields['custom_search_id'] = true;
	echo $serp_settings['custom_search_id'];
} else {
?>
<div class="bbit-begin-test-container">
	<a href="<?php echo admin_url("admin.php?page=bbit#serp"); ?>" class="bbit-button blue bbitStressTest">Update module settings</a>
</div>
<?php
	}
?>
									                </td>
									            </tr>
												<tr>
									                <td width="190"><?php _e( 'Google location',$this->the_plugin->localizationName ); ?>:</td>
									                <td>
<?php 
if ( isset($serp_settings['google_country']) && !empty($serp_settings['google_country']) ) {
	$serp_mandatoryFields['google_country'] = true;
	echo 'google.'.$serp_settings['google_country'];
} else {
?>
<div class="bbit-begin-test-container">
	<a href="<?php echo admin_url("admin.php?page=bbit#serp"); ?>" class="bbit-button blue bbitStressTest">Update module settings</a>
</div>
<?php
	}
?>
									                </td>
									            </tr>
												<tr>
									                <td width="190"><?php _e( 'Status',$this->the_plugin->localizationName ); ?>:</td>
									                <td>
<div class="bbit-begin-test-container noheight">
<?php
$mandatoryValid = true;
foreach ($serp_mandatoryFields as $k=>$v) {
	if ( !$v ) {
		$mandatoryValid = false;
		break;
	}
}
if ( $mandatoryValid ) {
?>
	<div class="bbit-message bbit-success">
		<?php _e( 'all mandatory module settings are set!',$this->the_plugin->localizationName ); ?>
	</div>
<?php
} else {
?>
	<div class="bbit-message bbit-error">
		<?php _e( 'some mandatory module settings are missing or not valid, so first fill them and then you can make a serp request!',$this->the_plugin->localizationName ); ?>
	</div>
<?php
}
?>
</div>
									                </td>
									            </tr>
															
									            <tr>
									            	<td style="vertical-align: middle;">Verify:</td>
									                <td>
														<div class="bbit-verify-products-test">
															<div class="bbit-test-timeline">
																<div class="bbit-one_step stepid-step1 nbsteps2">
																	<div class="bbit-step-status bbit-loading-inprogress"></div>
																	<span class="bbit-step-name">Step 1</span>
																</div>
																<div class="bbit-one_step stepid-step2 nbsteps2">
																	<div class="bbit-step-status"></div>
																	<span class="bbit-step-name">Step 2</span>
																</div>
																<div style="clear:both;"></div>
															</div>
															<table class="bbit-table bbit-logs" cellspacing="0">
																<tr class="logbox-step1">
																	<td width="50">Step 1:</td>
																	<td>
																		<div class="bbit-log-title">
																			<?php _e( 'Set mandatory fields: google developer key, custom search engine id, google location', $this->the_plugin->localizationName ); ?>
																			<a href="#" class="bbit-button gray"><?php _e( 'View details +', $this->the_plugin->localizationName ); ?></a>
																		</div>
																		
																		<textarea class="bbit-log-details"></textarea>
																	</td>
																</tr>
																<tr class="logbox-step2">
																	<td width="50">Step 2:</td>
																	<td>
																		<div class="bbit-log-title">
																			<?php _e( 'Make a test request from Google SERP', $this->the_plugin->localizationName ); ?>
																			<?php
$serp_keyword 	= 'test';
$serp_link		= 'www.test.com';
echo " (keyword: $serp_keyword , url: $serp_link)";
																			?>
																			<a href="#" class="bbit-button gray"><?php _e( 'View details +', $this->the_plugin->localizationName ); ?></a>
																		</div>
																		
																		<textarea class="bbit-log-details"></textarea>
																	</td>
																</tr>
															</table>
															<div class="bbit-begin-test-container">
																<a href="#google-serp/verify" class="bbit-button blue bbitStressTest verify" data-module="serp">Verify</a>
															</div>
														</div>
													</td>
									            </tr>
											</tbody>
<?php
} // end Google SERP module!
?>

<?php
// Google Pagespeed module
if ( $this->the_plugin->verify_module_status( 'google_pagespeed' ) ) { //module is inactive
?>
				
											<thead>
												<tr>
													<th colspan="2">											<a id="sect-google_pagespeed" name="sect-google_pagespeed"></a><?php _e( 'Module Google Pagespeed:', $this->the_plugin->localizationName ); ?></th>
												</tr>
											</thead>
									
											<tbody>
												<tr>
									                <td width="190"><?php _e( 'Google Developer Key',$this->the_plugin->localizationName ); ?>:</td>
									                <td>
<?php
if ( isset($pagespeed_settings['developer_key']) && !empty($pagespeed_settings['developer_key']) ) {
	$pagespeed_mandatoryFields['developer_key'] = true;
	echo $pagespeed_settings['developer_key'];
} else {
?>
<div class="bbit-begin-test-container">
	<a href="<?php echo admin_url("admin.php?page=bbit#google_pagespeed"); ?>" class="bbit-button blue bbitStressTest">Update module settings</a>
</div>
<?php
	}
?>
													</td>
									            </tr>
												<tr>
									                <td width="190"><?php _e( 'Google language',$this->the_plugin->localizationName ); ?>:</td>
									                <td>
<?php 
if ( isset($pagespeed_settings['google_language']) && !empty($pagespeed_settings['google_language']) ) {
	$pagespeed_mandatoryFields['google_language'] = true;
	echo $pagespeed_settings['google_language'];
} else {
?>
<div class="bbit-begin-test-container">
	<a href="<?php echo admin_url("admin.php?page=bbit#google_pagespeed"); ?>" class="bbit-button blue bbitStressTest">Update module settings</a>
</div>
<?php
	}
?>
									                </td>
									            </tr>
												<tr>
									                <td width="190"><?php _e( 'Status',$this->the_plugin->localizationName ); ?>:</td>
									                <td>
<div class="bbit-begin-test-container noheight">
<?php
$mandatoryValid = true;
foreach ($pagespeed_mandatoryFields as $k=>$v) {
	if ( !$v ) {
		$mandatoryValid = false;
		break;
	}
}
if ( $mandatoryValid ) {
?>
	<div class="bbit-message bbit-success">
		<?php _e( 'all mandatory module settings are set!', $this->the_plugin->localizationName ); ?>
	</div>
<?php
} else {
?>
	<div class="bbit-message bbit-error">
		<?php _e( 'some mandatory module settings are missing or not valid, so first fill them and then you can make a serp request!',$this->the_plugin->localizationName ); ?>
	</div>
<?php
}
?>
</div>
									                </td>
									            </tr>
															
									            <tr>
									            	<td style="vertical-align: middle;">Verify:</td>
									                <td>
														<div class="bbit-verify-products-test">
															<div class="bbit-test-timeline">
																<div class="bbit-one_step stepid-step1 nbsteps2">
																	<div class="bbit-step-status bbit-loading-inprogress"></div>
																	<span class="bbit-step-name">Step 1</span>
																</div>
																<div class="bbit-one_step stepid-step2 nbsteps2">
																	<div class="bbit-step-status"></div>
																	<span class="bbit-step-name">Step 2</span>
																</div>
																<div style="clear:both;"></div>
															</div>
															<table class="bbit-table bbit-logs" cellspacing="0">
																<tr class="logbox-step1">
																	<td width="50">Step 1:</td>
																	<td>
																		<div class="bbit-log-title">
																			<?php _e( 'Set mandatory fields: google developer key, google language', $this->the_plugin->localizationName ); ?>
																			<a href="#" class="bbit-button gray"><?php _e( 'View details +', $this->the_plugin->localizationName ); ?></a>
																		</div>
																		
																		<textarea class="bbit-log-details"></textarea>
																	</td>
																</tr>
																<tr class="logbox-step2">
																	<td width="50">Step 2:</td>
																	<td>
																		<div class="bbit-log-title">
																			<?php _e( 'Make a test request from Google Pagespeed', $this->the_plugin->localizationName ); ?>
																			<?php
$serp_link		= 'www.test.com';
echo " (url: $serp_link)";
																			?>
																			<a href="#" class="bbit-button gray"><?php _e( 'View details +', $this->the_plugin->localizationName ); ?></a>
																		</div>
																		
																		<textarea class="bbit-log-details"></textarea>
																	</td>
																</tr>
															</table>
															<div class="bbit-begin-test-container">
																<a href="#google-pagespeed/verify" class="bbit-button blue bbitStressTest verify" data-module="pagespeed">Verify</a>
															</div>
														</div>
													</td>
									            </tr>
											</tbody>
<?php
} // end Google Pagespeed module!
?>

<?php
// Facebook Planner module
if ( $this->the_plugin->verify_module_status( 'facebook_planner' ) ) { //module is inactive
?>
				
											<thead>
												<tr>
													<th colspan="2"><a id="sect-facebook_planner" name="sect-facebook_planner"></a><?php _e( 'Module Facebook:', $this->the_plugin->localizationName ); ?></th>
												</tr>
											</thead>
									
											<tbody>
												<tr>
									                <td width="190"><?php _e( 'Facebook App ID',$this->the_plugin->localizationName ); ?>:</td>
									                <td>
<?php
if ( isset($facebook_settings['app_id']) && !empty($facebook_settings['app_id']) ) {
	$facebook_mandatoryFields['app_id'] = true;
	echo $facebook_settings['app_id'];
} else {
?>
<div class="bbit-begin-test-container">
	<a href="<?php echo admin_url("admin.php?page=bbit#facebook_planner"); ?>" class="bbit-button blue bbitStressTest">Update module settings</a>
</div>
<?php
	}
?>
													</td>
									            </tr>
												<tr>
									                <td width="190"><?php _e( 'Facebook App Secret',$this->the_plugin->localizationName ); ?>:</td>
									                <td>
<?php 
if ( isset($facebook_settings['app_secret']) && !empty($facebook_settings['app_secret']) ) {
	$facebook_mandatoryFields['app_secret'] = true;
	echo $facebook_settings['app_secret'];
} else {
?>
<div class="bbit-begin-test-container">
	<a href="<?php echo admin_url("admin.php?page=bbit#facebook_planner"); ?>" class="bbit-button blue bbitStressTest">Update module settings</a>
</div>
<?php
	}
?>
									                </td>
									            </tr>
												<tr>
									                <td width="190"><?php _e( 'Redirect URI',$this->the_plugin->localizationName ); ?>:</td>
									                <td>
<?php 
if ( isset($facebook_settings['redirect_uri']) && !empty($facebook_settings['redirect_uri']) ) {
	$facebook_mandatoryFields['redirect_uri'] = true;
	echo $facebook_settings['redirect_uri'];
} else {
?>
<div class="bbit-begin-test-container">
	<a href="<?php echo admin_url("admin.php?page=bbit#facebook_planner"); ?>" class="bbit-button blue bbitStressTest">Update module settings</a>
</div>
<?php
	}
?>
									                </td>
									            </tr>
												<tr>
									                <td width="190"><?php _e( 'Facebook Language',$this->the_plugin->localizationName ); ?>:</td>
									                <td>
<?php 
if ( isset($facebook_settings['language']) && !empty($facebook_settings['language']) ) {
	$facebook_mandatoryFields['language'] = true;
	echo $facebook_settings['language'];
} else {
?>
<div class="bbit-begin-test-container">
	<a href="<?php echo admin_url("admin.php?page=bbit#facebook_planner"); ?>" class="bbit-button blue bbitStressTest">Update module settings</a>
</div>
<?php
	}
?>
									                </td>
									            </tr>
												<tr>
									                <td width="190"><?php _e( 'Authorize',$this->the_plugin->localizationName ); ?>:</td>
									                <td>
<div class="bbit-begin-test-container noheight">
<?php
$mandatoryValid = true;
foreach ($facebook_mandatoryFields as $k=>$v) {
	if ( !$v ) {
		$mandatoryValid = false;
		break;
	}
}
if ( $mandatoryValid ) {
	if ( $bbitFacebook_Planner->makeoAuthLogin() ) {
?>
	<div class="bbit-message bbit-success">
		<a href="#facebook-planner/authorize" class="bbit-button blue bbitStressTest inline bbit-facebook-authorize-app" data-saveform="no">Re-Authorize app</a>
	&nbsp;(<?php _e( 'app is authorized for: ',$this->the_plugin->localizationName ); ?><a target="_blank" href="<?php echo $facebook_settings['auth_foruser_link']; ?>"><?php echo $facebook_settings['auth_foruser_name']; ?></a>)
	</div>
<?php
	} else {
?>
	<div class="bbit-message bbit-info">
		<a href="#facebook-planner/authorize" class="bbit-button blue bbitStressTest inline bbit-facebook-authorize-app" data-saveform="no">Authorize app</a>
		<span style="margin-left: 10px;">(<?php _e( 'app is not authorized yet',$this->the_plugin->localizationName ); ?>)</span>
	</div>
<?php
	}
} else {
?>
	<div class="bbit-message bbit-error">
		<?php _e( 'some mandatory module settings are missing or not valid, so first fill them and then you can authorize the app!',$this->the_plugin->localizationName ); ?>
	</div>
<?php
}
?>
</div>
									                </td>
									            </tr>
															
									            <tr>
									            	<td style="vertical-align: middle;">Verify:</td>
									                <td>
														<div class="bbit-verify-products-test">
															<div class="bbit-test-timeline">
																<div class="bbit-one_step stepid-step1 nbsteps2">
																	<div class="bbit-step-status bbit-loading-inprogress"></div>
																	<span class="bbit-step-name">Step 1</span>
																</div>
																<div class="bbit-one_step stepid-step2 nbsteps2">
																	<div class="bbit-step-status"></div>
																	<span class="bbit-step-name">Step 2</span>
																</div>
<?php
/*
?>
																<!--<div class="bbit-one_step stepid-step3 nbsteps3">
																	<div class="bbit-step-status"></div>
																	<span class="bbit-step-name">Step 3</span>
																</div>-->
<?php
*/
?>
																<div style="clear:both;"></div>
															</div>
															<table class="bbit-table bbit-logs" cellspacing="0">
																<tr class="logbox-step1">
																	<td width="50">Step 1:</td>
																	<td>
																		<div class="bbit-log-title">
																			<?php _e( 'Set mandatory fields: app id, app secret, redirect uri, language', $this->the_plugin->localizationName ); ?>
																			<a href="#" class="bbit-button gray"><?php _e( 'View details +', $this->the_plugin->localizationName ); ?></a>
																		</div>
																		
																		<textarea class="bbit-log-details"></textarea>
																	</td>
																</tr>
																<tr class="logbox-step2">
																	<td width="50">Step 2:</td>
																	<td>
																		<div class="bbit-log-title">
																			<?php _e( 'Authorize app on Facebook Developers:', $this->the_plugin->localizationName ); ?>
																			<a target="_blank" href="http://developers.facebook.com/">http://developers.facebook.com/</a>
																			<a href="#" class="bbit-button gray"><?php _e( 'View details +', $this->the_plugin->localizationName ); ?></a>
																		</div>
																		
																		<textarea class="bbit-log-details"></textarea>
																	</td>
																</tr>
<?php
/*
?>
																<!--<tr class="logbox-step3">
																	<td width="50">Step 3:</td>
																	<td>
																		<div class="bbit-log-title">
																			<?php _e( 'Get profile ID', $this->the_plugin->localizationName ); ?>
																			<a href="#" class="bbit-button gray"><?php _e( 'View details +', $this->the_plugin->localizationName ); ?></a>
																		</div>
																		
																		<textarea class="bbit-log-details"></textarea>
																	</td>
																</tr>-->
<?php
*/
?>
															</table>
															<div class="bbit-begin-test-container">
																<a href="#facebook-planner/verify" class="bbit-button blue bbitStressTest verify" data-module="facebook_planner">Verify</a>
															</div>
														</div>
													</td>
									            </tr>
											</tbody>
<?php
} // end Facebook Planner module!
?>
											
											<thead>
												<tr>
													<th colspan="2"><?php _e( 'Hệ thống', $this->the_plugin->localizationName ); ?></th>
												</tr>
											</thead>
									
											<tbody>
												<tr>
									                <td width="190"><?php _e( 'Home URL',$this->the_plugin->localizationName ); ?>:</td>
									                <td><?php echo home_url(); ?></td>
									            </tr>
									            <tr>
									                <td><?php _e( 'bbit Version',$this->the_plugin->localizationName ); ?>:</td>
									                <td><?php echo $plugin_data['Version'];?></td>
									            </tr>
									            <tr>
									                <td><?php _e( 'WP Version',$this->the_plugin->localizationName ); ?>:</td>
									                <td><?php if ( is_multisite() ) echo 'WPMU'; else echo 'WP'; ?> <?php bloginfo('version'); ?></td>
									            </tr>
									            <tr>
									                <td><?php _e( 'Web Server Info',$this->the_plugin->localizationName ); ?>:</td>
									                <td><?php echo esc_html( $_SERVER['SERVER_SOFTWARE'] );  ?></td>
									            </tr>
									            <tr>
									                <td><?php _e( 'PHP Version',$this->the_plugin->localizationName ); ?>:</td>
									                <td><?php if ( function_exists( 'phpversion' ) ) echo esc_html( phpversion() ); ?></td>
									            </tr>
									            <tr>
									                <td><?php _e( 'MySQL Version',$this->the_plugin->localizationName ); ?>:</td>
									                <td><?php if ( function_exists( 'mysql_get_server_info' ) ) echo esc_html( mysql_get_server_info() ); ?></td>
									            </tr>
									            <tr>
									                <td><?php _e( 'WP Memory Limit',$this->the_plugin->localizationName ); ?>:</td>
									                <td><div class="bbit-loading-ajax-details" data-action="check_memory_limit"></div></td>
									            </tr>
									            <tr>
									                <td><?php _e( 'WP Debug Mode',$this->the_plugin->localizationName ); ?>:</td>
									                <td><?php if ( defined('WP_DEBUG') && WP_DEBUG ) echo __( 'Yes', $this->the_plugin->localizationName ); else echo __( 'No', $this->the_plugin->localizationName ); ?></td>
									            </tr>
									            <tr>
									                <td><?php _e( 'WP Max Upload Size',$this->the_plugin->localizationName ); ?>:</td>
									                <td><?php echo size_format( wp_max_upload_size() ); ?></td>
									            </tr>
									            <tr>
									                <td>PHP Post Max Size:</td>
									                <td><?php if ( function_exists( 'ini_get' ) ) echo size_format( $this->woocommerce_let_to_num( ini_get('post_max_size') ) ); ?></td>
									            </tr>
									            <tr>
									                <td>PHP Time Limit:</td>
									                <td><?php if ( function_exists( 'ini_get' ) ) echo ini_get('max_execution_time'); ?></td>
									            </tr>
									            <tr>
									                <td>WP Remote GET:</td>
									                <td><div class="bbit-loading-ajax-details" data-action="remote_get"></div></td>
									            </tr>
									            <tr>
									                <td>SOAP Client:</td>
									                <td><div class="bbit-loading-ajax-details" data-action="check_soap"></div></td>
									            </tr>
											</tbody>
									
											<thead>
												<tr>
													<th colspan="2"><?php _e( 'Plugins', $this->the_plugin->localizationName ); ?></th>
												</tr>
											</thead>
									
											<tbody>
									         	<tr>
									         		<td><?php _e( 'Plugin đã cài đặt',$this->the_plugin->localizationName ); ?>:</td>
									         		<td><div class="bbit-loading-ajax-details" data-action="active_plugins"></div></td>
									         	</tr>
											</tbody>
									
											<thead>
												<tr>
													<th colspan="2"><?php _e( 'Settings', $this->the_plugin->localizationName ); ?></th>
												</tr>
											</thead>
									
											<tbody>
									
									            <tr>
									                <td><?php _e( 'Force SSL',$this->the_plugin->localizationName ); ?>:</td>
													<td><?php echo get_option( 'woocommerce_force_ssl_checkout' ) === 'yes' ? __( 'Yes', $this->the_plugin->localizationName ) : __( 'No', $this->the_plugin->localizationName ); ?></td>
									            </tr>
											</tbody>
											
											
											<!--tfoot>
												<tr>
													<th colspan="2">
														<a href="#" class="bbit-button blue bbit-export-logs">Export status log as file</a>
													</th>
												</tr>
											</tfoot-->
										</table>
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
		* this will create requesto to 404 table
		*/
		public function ajax_request()
		{
			global $wpdb;
			$request = array(
				'id' 			=> isset($_REQUEST['id']) ? (int)$_REQUEST['id'] : 0
			);
			
			$asin = get_post_meta($request['id'], '_amzASIN', true);
			
			$sync = new wwcAmazonSyncronize( $this->the_plugin );
			$sync->updateTheProduct( $asin, $request['id'] );
		}
		
		
		/**
		 * UTILS
		 */
		function woocommerce_let_to_num($size) {
			if ( function_exists('woocomerce_let_to_num') )
				return function_exists('woocomerce_let_to_num');

			$l = substr($size, -1);
			$ret = substr($size, 0, -1);
			switch( strtoupper( $l ) ) {
				case 'P' :
					$ret *= 1024;
				case 'T' :
					$ret *= 1024;
				case 'G' :
					$ret *= 1024;
				case 'M' :
					$ret *= 1024;
				case 'K' :
					$ret *= 1024;
			}
			return $ret;
		}
	}
}
// Initialize the bbitServerStatus class
//$bbitServerStatus = new bbitServerStatus();
$bbitServerStatus = bbitServerStatus::getInstance();