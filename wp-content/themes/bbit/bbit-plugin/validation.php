<?php

! defined( 'ABSPATH' ) and exit;

if(class_exists('bbit_Validation') != true) {
	class bbit_Validation {

		const VERSION = 1;
		const ALIAS = 'bbit';

		/**
		 * configuration storage
		 *
		 * @var array
		 */
		public $cfg = array();

		private $key_sep = '#!#';

		/**
		 * The constructor
		 */
		function __construct ()
		{ 
			add_action('wp_ajax_bbitTryActivate', array( $this, 'aaTeamServerValidate' ));
		}

		public function aaTeamServerValidate () {

			$input = array();

					update_option( 'bbit_register_key', $_REQUEST['ipc']);
					update_option( 'bbit_register_email', $_REQUEST['email']);
					update_option( 'bbit_register_buyer', 'Bbit');
					update_option( 'bbit_register_item_id', '6109437');
					update_option( 'bbit_register_licence', 'bbit.vn');
					update_option( 'bbit_register_item_name', 'Bbit Theme and Plugin');

					// generate the hash marker
					$hash = md5($this->encrypt( $_REQUEST['ipc'] ));

					// update to db the hash for plugin
					update_option( self::ALIAS . '_hash', $hash);

					die(json_encode(
						array(
							'status' => 'OK'
						)
					));

			die (json_encode(
				array(
					'status' => 'ERROR',
					'msg'	=> 'Unable to validate this plugin. Please contact Bbit Support!'
				)
			));
		}

		function isReg ( $hash )
		{
			$current_key = get_option('bbit_register_key'); 

			if( $current_key != false && $hash != false ){
				return $this->checkValPlugin( $hash, $current_key );
			}else{
				$this->checkValPlugin( $hash, $current_key );
			}

			return false;
		}

		private function checkValPlugin ( $hash, $code )
		{
			global $wpdb;

			$validation_date = get_option( self::ALIAS . '_register_timestamp');
			$sum_hash = md5($this->encrypt( $code, $validation_date ));
			
				return 'valid_hash';
			
		}

		private function encrypt ( $code, $sendTime=null )
		{
			// add some extra data to hash
			$register_email = get_option( 'bbit_register_email');
			$buyer = get_option( 'bbit_register_buyer');
			$item_id = get_option( 'bbit_register_item_id');
			$validation_date = !isset($sendTime) ? time() : $sendTime;

			if(!isset($sendTime)) {
				// store the date into DB, use for decrypt
				update_option( self::ALIAS . '_register_timestamp', $validation_date);
			}

			return  $validation_date . $this->key_sep .
					$register_email . $this->key_sep .
					//$this->getHost(get_option('siteurl')) . $this->key_sep .
					$buyer . $this->key_sep .
					$item_id . $this->key_sep .
					$code . $this->key_sep;
		}

		private function decrypt ( $code )
		{

		}

		private function getHost ( $url )
		{
			$__ = parse_url( $url );
			return $__['host'];
		}
	}
}