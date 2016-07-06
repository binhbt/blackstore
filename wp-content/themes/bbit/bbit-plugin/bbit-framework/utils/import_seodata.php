<?php
/*
* Define class bbitImportSeoData
* Make sure you skip down to the end of this file, as there are a few
* lines of code that are very important.
*/
if ( !defined( 'DB_NAME' ) ) {
	header( 'HTTP/1.0 403 Forbidden' );
	die;
}
if (class_exists('bbitImportSeoData') != true) {
    class bbitImportSeoData
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
			add_action('wp_ajax_bbitimportSEOData', array( $this, 'import_seo_data' ));
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
	    
	    
		public function import_seo_data() {
			global $wpdb;
			
			$__importSEOFields = array(
				'WooThemes SEO Framework' => array(
					'meta title' 			=> 'seo_title',
					'meta description' 		=> 'seo_description',
					'meta keywords' 		=> 'seo_keywords'
				),
				'All-in-One SEO Pack - old version' => array(
					'meta title' 			=> 'title',
					'meta description' 		=> 'description',
					'meta keywords' 		=> 'keywords'
				),
				'All-in-One SEO Pack' => array(
					'meta title' 			=> '_aioseop_title',
					'meta description' 		=> '_aioseop_description',
					'meta keywords' 		=> '_aioseop_keywords'
				),
				'SEO Ultimate' => array(
					'meta title' 			=> '_su_title',
					'meta description' 		=> '_su_description',
					'meta keywords' 		=> '_su_keywords',
					'noindex' 				=> '_su_meta_robots_noindex',
					'nofollow' 				=> '_su_meta_robots_nofollow'
				),
				'Yoast WordPress SEO' => array(
					'meta title' 			=> '_yoast_wpseo_title',
					'meta description' 		=> '_yoast_wpseo_metadesc',
					'meta keywords' 		=> '_yoast_wpseo_metakeywords',
					'noindex' 				=> '_yoast_wpseo_meta-robots-noindex',
					'nofollow' 				=> '_yoast_wpseo_meta-robots-nofollow',
					'canonical url' 		=> '_yoast_wpseo_canonical',
					'focus keyword'			=> '_yoast_wpseo_focuskw',
					'sitemap include'		=> '_yoast_wpseo_sitemap-include',
					'sitemap priority'		=> '_yoast_wpseo_sitemap-prio',
					'facebook description'	=> '_yoast_wpseo_opengraph-description'
				)
			);
			
			$__bbitSEOFields = array(
				'meta title' 			=> array( array( 'title', 'bbit_meta' ) ),
				'meta description' 		=> array( array( 'description', 'bbit_meta' ) ),
				'meta keywords' 		=> array( array( 'keywords', 'bbit_meta' ) ),
				'noindex' 				=> array( array( 'robots_index', 'bbit_meta' ) ),
				'nofollow' 				=> array( array( 'robots_follow', 'bbit_meta' ) ),
				'canonical url' 		=> array( array( 'canonical', 'bbit_meta' ) ),
				'focus keyword' 		=> array( array( 'focus_keyword', 'bbit_meta' ), array( 'bbit_kw' ) ),
				'sitemap include' 		=> array( array( 'bbit_sitemap_isincluded' ) ),
				'sitemap priority' 		=> array( array( 'priority', 'bbit_meta' ) ),
				'facebook description'	=> array( array( 'facebook_desc', 'bbit_meta' ) )
			);
			
			$__convertValues = array(
				'noindex' => array(
					0		=> 'default',
					1		=> 'noindex',
					2		=> 'index'
				),
				'nofollow' => array(
					0		=> 'follow',
					1		=> 'nofollow'
				),
				'sitemap include' => array(
					'-'			=> 'default',
					'always'	=> 'always_include',
					'never'		=> 'never_include'
				)
			);

			$ret = array(
				'status'		=> 'invalid',
				'html'			=> 'No updates made.',
				'dbg'			=> ''
			);

			// import meta data!
			$pluginFrom = isset($_REQUEST['from']) ? str_replace('+', ' ', trim($_REQUEST['from'])) : '';
			$subaction = isset($_REQUEST['subaction']) ? $_REQUEST['subaction'] : '';
			$rowsperstep = isset($_REQUEST['rowsperstep']) ? $_REQUEST['rowsperstep'] : 10;
			$step = isset($_REQUEST['step']) ? $_REQUEST['step'] : 0;

			if ( empty($pluginFrom) ) // validate selection!
				return die(json_encode($ret));

			// execute import!
			$pluginFrom = $__importSEOFields[ "$pluginFrom" ];
			$fromMetaKeys = array_values($pluginFrom);
			
			if ( !empty($subaction) && $subaction == 'nbres' ) {
				// number of rows: get all post Ids which have metas from old plugin!
				$sql_nb = "select count(a.post_id) as nb from $wpdb->postmeta as a where 1=1 and a.meta_key regexp '^(" . implode('|', $fromMetaKeys) . ")';";
				$res_nb = $wpdb->get_var( $sql_nb );
				
				return die(json_encode(array_merge($ret, array(
					'status'		=> 'valid',
					'nbrows'		=> $res_nb,
					'html'			=> sprintf( __('Total rows: %s.', $this->the_plugin->localizationName), $res_nb )
				))));
			}

			// get all post Ids which have metas from old plugin!
			$sql = "select a.post_id, a.meta_key, a.meta_value from $wpdb->postmeta as a where 1=1 and a.meta_key regexp '^(" . implode('|', $fromMetaKeys) . ")' order by a.post_id asc, a.meta_key asc limit $step, $rowsperstep;";
			$res = $wpdb->get_results( $sql );
			$ret['dbg'] = $res;
			if ( is_null($res) || empty($res) )
				return die(json_encode($ret));

			// statistics array!
			$nbPostsUpdated = 0; $nbPostsOptimized = 0;

			$current_post_id = reset( $res );
			$current_post_id = $current_post_id->post_id;

			$bbitMetaValues = array();
			$i = 0; $resFound = count($res);
			foreach ( $res as $__k => $meta ) {

				$i++;
				if ( $current_post_id != $meta->post_id || $i == $resFound ) { // next post Id meta rows

					if ( !empty($bbitMetaValues) && is_array($bbitMetaValues) ) {

						$bbitUpd = 0;
						foreach ( $bbitMetaValues as $bbit_mk => $bbit_mv) { // update metas for current post Id
							
							$bbit_current = get_post_meta( $current_post_id, $bbit_mk, true);
							if ( empty($bbit_current) ) { // update empty meta values!
								
								$updStat = update_post_meta( $current_post_id, $bbit_mk, $bbit_mv );
	
								if ( $updStat === true || (int) $updStat > 0 ) $bbitUpd++;
							} else {
	
								if ( is_array($bbit_current) ) { // update only array serialized meta values!
	
									$bbit_mv = array_merge( (array) $bbit_mv, (array) $bbit_current);
									$bbitMetaValues[ "$bbit_mk" ] = $bbit_mv;
									update_post_meta( $current_post_id, $bbit_mk, $bbit_mv );
	
									if ( $updStat === true || (int) $updStat > 0 ) $bbitUpd++;
								}
							}
						}

						if ( $bbitUpd ) $nbPostsUpdated++;
						
						// bbit specific meta!
						if ( $this->import_seo_data_bbitExtra( $current_post_id, $bbitMetaValues ) )
							$nbPostsOptimized++;

					}
					//var_dump('<pre>',$current_post_id, $bbitMetaValues ,'</pre>'); 

					$current_post_id = $meta->post_id;
					$bbitMetaValues = array(); // reset metas to be used by next post Id

				}

				// current post Id meta rows
				$alias = array_search( $meta->meta_key, $pluginFrom );
				$bbitMetaKey = $__bbitSEOFields[ "$alias" ];

				if ( !is_array($bbitMetaKey) || count($bbitMetaKey) < 1 ) continue 1;

				foreach ( $bbitMetaKey as $bbit_ka => $bbit_kb ) {

					if ( isset($__convertValues[ "$alias" ])
						&& isset($__convertValues[ "$alias" ][ "{$meta->meta_value}"]) )
						$meta->meta_value = $__convertValues[ "$alias" ][ "{$meta->meta_value}"];

					if ( count($bbit_kb) == 2 )
						$bbitMetaValues[ "{$bbit_kb[1]}" ][ "{$bbit_kb[0]}" ] = $meta->meta_value;
					else
						$bbitMetaValues[ "{$bbit_kb[0]}" ] = $meta->meta_value;
				}
			}

			$msg = array();
			$msg[] = sprintf( __('Rows: <strong>%s - %s</strong>.', $this->the_plugin->localizationName), $step, ( $step + $rowsperstep - 1) );
			$msg[] = sprintf( __('Total number of posts updated: <strong>%s</strong>.', $this->the_plugin->localizationName), $nbPostsUpdated );
			$msg[] = sprintf( __('Total number of posts optimized: <strong>%s</strong>.', $this->the_plugin->localizationName), $nbPostsOptimized );

			return die(json_encode(array_merge($ret, array(
				'status'		=> 'valid',
				'html'			=> implode('<br />', $msg)
			))));
		}
		
		private function import_seo_data_bbitExtra( $post_id = 0, $meta = array() ) {
			
			if ( $post_id <= 0 ) return false;
			if ( empty($meta) ) return false;
			
			$post_metas = get_post_meta( $post_id, 'bbit_meta', true);

			$post_metas = array_merge(array(
				'title'				=> '',
				'description'		=> '',
				'keywords'			=> '',
				'focus_keyword'		=> '',
	
				'facebook_isactive' => '',
				'facebook_titlu'	=> '',
				'facebook_desc'		=> '',
				'facebook_image'	=> '',
				'facebook_opengraph_type'	=> '',
				
				'robots_index'		=> '',
				'robots_follow'		=> '',
	
				'priority'			=> '',
				'canonical'			=> ''
			), $post_metas);

			// include on page optimization module!
			require_once( $this->the_plugin->cfg['paths']['plugin_dir_path'] . 'modules/on_page_optimization/init.php');
			$bbitOnPageOptimization = new bbitOnPageOptimization();

			$_REQUEST = array(
				'bbit-field-title'					=> $post_metas['title'],
				'bbit-field-metadesc'				=> $post_metas['description'],
				'bbit-field-metakewords'				=> $post_metas['keywords'],
				'bbit-field-focuskw'					=> $post_metas['focus_keyword'],
	
				'bbit-field-facebook-isactive'		=> $post_metas['facebook_isactive'],
				'bbit-field-facebook-titlu'			=> $post_metas['facebook_titlu'],
				'bbit-field-facebook-desc'			=> $post_metas['facebook_desc'],
				'bbit-field-facebook-image'			=> $post_metas['facebook_image'],
				'bbit-field-facebook-opengraph-type'	=> $post_metas['facebook_opengraph_type'],
	
				'bbit-field-meta_robots_index'		=> $post_metas['robots_index'],
				'bbit-field-meta_robots_follow'		=> $post_metas['robots_follow'],
	
				'bbit-field-priority-sitemap'		=> $post_metas['priority'],
				'bbit-field-canonical'				=> $post_metas['canonical']
			);
			$bbitOnPageOptimization->optimize_page( $post_id );
			
			return true;
		}
    }
}

// Initialize the bbitImportSeoData class
//$bbitImportSeoData = new bbitImportSeoData();
