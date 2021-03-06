<?php
/*
* Define class bbitActionAdminAjax
* Make sure you skip down to the end of this file, as there are a few
* lines of code that are very important.
*/
if ( !defined( 'DB_NAME' ) ) {
	header( 'HTTP/1.0 403 Forbidden' );
	die;
}
if (class_exists('bbitActionAdminAjax') != true) {
    class bbitActionAdminAjax
    {
        /*
        * Some required plugin information
        */
        const VERSION = '1.0';

        /*
        * Store some helpers config
        */
		public $the_plugin = null;

		static protected $_instance;
		
	
		/*
        * Required __construct() function that initalizes the Bbit Framework
        */
        public function __construct( $parent )
        {
			$this->the_plugin = $parent;

			add_action('wp_ajax_bbitAdminAjax', array( $this, 'admin_ajax' ));
			add_action('wp_ajax_bbitTwitterCards', array( $this, 'twitter_cards' ));
			add_action('wp_ajax_bbitSocialSharing', array( $this, 'social_sharing' ));
			
			add_action('wp_ajax_bbitSocialSharingFrontend', array( $this, 'social_sharing_frontend' ));
			add_action('wp_ajax_nopriv_bbitSocialSharingFrontend', array( $this, 'social_sharing_frontend' ));
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
	     * Sitemap
	     *
	     */
		public function admin_ajax() {
			$action = isset($_REQUEST['sub_action']) ? $_REQUEST['sub_action'] : '';
			$engine = isset($_REQUEST['engine']) ? strtolower($_REQUEST['engine']) : '';
			$sitemap_type = isset($_REQUEST['sitemap_type']) ? $_REQUEST['sitemap_type'] : 'sitemap';

			$sitemapList = array('sitemap' => 'Sitemap.xml', 'sitemap_images' => 'Sitemap-Images.xml', 'sitemap_videos' => 'Sitemap-Videos.xml');
			$sitemapCurrent = $sitemapList[ "$sitemap_type" ];

			$ret = array(
				'status'			=> 'invalid',
				'start_date'		=> date('Y-m-d H:i:s'),
				'start_time'		=> 0,
				'end_time'			=> 0,
				'duration'			=> 0,
				'msg'				=> '',
				'msg_html'			=> ''
			);

			if ( $action == 'getStatus') {

				$notifyStatus = $this->the_plugin->get_theoption('bbit_sitemap_engine_notify');
				if ( $notifyStatus === false || !isset($notifyStatus["$engine"]) || !isset($notifyStatus["$engine"]["$sitemap_type"]) ) ;
				else {
					$ret['status'] = 'valid';
					$ret['msg_html'] = $notifyStatus["$engine"]["$sitemap_type"]["msg_html"];
				}
				
				die(json_encode($ret));
			}
			
			$sitemapUrl =  home_url('/sitemap.xml');
			switch ($sitemap_type) {
				case 'sitemap_images':
					$sitemapUrl = home_url('/sitemap-images.xml');
					break;
				case 'sitemap_videos':
					$sitemapUrl = home_url('/sitemap-videos.xml');
					break;
				default:
					break;
			}
			if ( $action == 'localseo_notify' ) {

				if ( $sitemap_type == 'kml' )
					$sitemapUrl =  home_url('/sitemap-locations.kml');
				else
					$sitemapUrl =  home_url('/sitemap-locations.xml');
			}

			if ( in_array($action, array('notify', 'localseo_notify')) && $engine == 'google' ) {
				$engineTitle = __('Google', $this->the_plugin->localizationName);
				$pingUrl = "http://www.google.com/webmasters/sitemaps/ping?sitemap=";
				$pingUrl .= urlencode( $sitemapUrl );
			}
			else if ( in_array($action, array('notify', 'localseo_notify')) && $engine == 'bing' ) {
				$engineTitle = __('Bing', $this->the_plugin->localizationName);
				$pingUrl = "http://www.bing.com/webmaster/ping.aspx?siteMap=";
				$pingUrl .= urlencode( $sitemapUrl );
			}

			if ( in_array($action, array('notify', 'localseo_notify')) && in_array($engine, array('google', 'bing')) ) ;
			else {
				$ret['msg_html'] = 'unknown request';
				die(json_encode($ret));
			}

			if ( $action == 'localseo_notify' ) {
				$notifyStatus = $this->the_plugin->get_theoption('bbit_localseo_engine_notify');
			} else {
				$notifyStatus = $this->the_plugin->get_theoption('bbit_sitemap_engine_notify');
			}

			$ret['start_time'] = $this->the_plugin->microtime_float();

			$response = wp_remote_get( $pingUrl, array('timeout' => 10) );
			if ( is_wp_error( $response ) ) { // If there's error
				$ret = array_merge($ret, array(
					'end_time'		=> $this->the_plugin->microtime_float(),
					'msg'			=> htmlspecialchars( implode(';', $response->get_error_messages()) ),
					'msg_html'		=> '<span class="error">' . ($engine . ' / ' . $sitemapCurrent) . __(' couldn\'t be notified!', $this->the_plugin->localizationName) . '</span>'
				));
				$ret['duration'] = number_format( ($ret['end_time'] - $ret['start_time']), 2 );

				$notifyStatus["$engine"]["$sitemap_type"] = $ret;
				if ( $action == 'localseo_notify' ) {
					$this->the_plugin->save_theoption('bbit_localseo_engine_notify', $notifyStatus);
				} else {
					$this->the_plugin->save_theoption('bbit_sitemap_engine_notify', $notifyStatus);
				}
				die(json_encode($ret));
			}

			$body = wp_remote_retrieve_body( $response );

			$ret = array_merge($ret, array(
				'end_time'		=> $this->the_plugin->microtime_float(),
				'msg'			=> $body,
				'msg_html'		=> '<span class="error">' . ($engine . ' / ' . $sitemapCurrent) . __(' couldn\'t be notified | invalid response received!', $this->the_plugin->localizationName) . '</span>'
			));
			$ret['duration'] = number_format( ($ret['end_time'] - $ret['start_time']), 2 );

			if ( is_null( $body ) || $body === false ) ;
			else {
				$ret['status'] 		= 'valid';
				$ret['msg_html']	= '<span class="success">' . ($engine . ' / ' . $sitemapCurrent) . sprintf( __(' was notified successfully on %s | ping duration: %s seconds.', $this->the_plugin->localizationName), $ret['start_date'], $ret['duration'] ) . '</span>';
			}
			
			$notifyStatus["$engine"]["$sitemap_type"] = $ret;
			if ( $action == 'localseo_notify' ) {
				$this->the_plugin->save_theoption('bbit_localseo_engine_notify', $notifyStatus);
			} else {
				$this->the_plugin->save_theoption('bbit_sitemap_engine_notify', $notifyStatus);
			}
			die(json_encode($ret));
		}
		
		/**
		 * Twitter Cards
		 *
		 */
		public function twitter_cards() {
			$action = isset($_REQUEST['sub_action']) ? $_REQUEST['sub_action'] : '';
			$card_type = isset($_REQUEST['card_type']) ? strtolower($_REQUEST['card_type']) : '';
			$page = isset($_REQUEST['page']) ? strtolower($_REQUEST['page']) : '';
			$post_id = isset($_REQUEST['post_id']) ? (int) $_REQUEST['post_id'] : 0;

			$ret = array(
				'status'		=> 'invalid',
				'html'			=> ''
			);
			
			// twitter cards module
			require_once( 'twitter_cards.php' );
			$twc = new bbitTwitterCards( $this->the_plugin );
			
			if ( $action == 'getCardTypeOptions') {

				$ret['status'] = 'valid';
				$ret['html'] = $twc->build_options(array('card_type' => $card_type, 'page' => $page, 'post_id' => $post_id));
			}
			die(json_encode($ret));
		}
		
		/**
		 * Social Sharing
		 */
		public function social_sharing() {
			$action = isset($_REQUEST['sub_action']) ? $_REQUEST['sub_action'] : '';
			$toolbar = isset($_REQUEST['toolbar']) ? strtolower($_REQUEST['toolbar']) : '';

			$ret = array(
				'status'		=> 'invalid',
				'html'			=> ''
			);

			if ( in_array($action, array('getToolbarOptions')) ) {			
				// social sharing module
				require_once( 'social_sharing.php' );
				$ssh = new bbitSocialSharing( $this->the_plugin );
			}
			
			if ( $action == 'getToolbarOptions' ) {

				$ret['status'] = 'valid';
				$ret['html'] = $ssh->build_toolbar_options(array('toolbar' => $toolbar));
			}
			die(json_encode($ret));
		}
		
		public function social_sharing_frontend() {
			$action = isset($_REQUEST['sub_action']) ? $_REQUEST['sub_action'] : '';
			$buttons = isset($_REQUEST['buttons']) ? strtolower($_REQUEST['buttons']) : '';
			$urls = isset($_REQUEST['urls']) ? $_REQUEST['urls'] : '';
  
			if ( empty($buttons) || empty($urls) || !is_array($urls) ) {
				$ret = array(
					'status'		=> 'invalid',
					'html'			=> 'is invalid'
				);
			}

			if ( in_array($action, array('getCount')) ) {
				// social sharing module
				require_once( 'social_sharing.php' );
				$ssh = new bbitSocialSharing( $this->the_plugin );
			}

			$results = array();
			if ( $action == 'getCount' ) {
				
				$buttons = explode(',', $buttons);

				$c = 0;
				foreach ($urls as $key => $val) {
					$countStat = $ssh->getSocialsData( $val['url'], $val['id'] );
					foreach ($buttons as $key2 => $network) {
						$results[$val['id']][$network] = $ssh->formatCount( $countStat["$network"] );
					}
					$c++;
				}  
  
				$ret['status'] = 'valid';
				$ret['html'] = 'buttons: ' . implode(',', $buttons);
				$ret['results'] = $results;
			}
			die(json_encode($ret));
		}
    }
}

// Initialize the bbitActionAdminAjax class
//$bbitActionAdminAjax = new bbitActionAdminAjax();
