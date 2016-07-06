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
if (class_exists('bbitRemoteSupportAjax') != true) {
    class bbitRemoteSupportAjax extends bbitRemoteSupport
    {
    	public $the_plugin = null;
		private $module_folder = null;
		private $the_api_url = 'http://bbit.vn/endpoint.php?';
		
		/*
        * Required __construct() function that initalizes the Bbit Framework
        */
        public function __construct( $the_plugin=array() )
        {
        	$this->the_plugin = $the_plugin;
			$this->module_folder = $this->the_plugin->cfg['paths']['plugin_dir_url'] . 'modules/remote_support/';
			
			// ajax  helper
			add_action('wp_ajax_bbitRemoteSupportRequest', array( &$this, 'ajax_request' ));
		}
		
		/*
		* ajax_request, method
		* --------------------
		*
		* this will create requests to 404 table
		*/
		public function ajax_request()
		{
			$return = array();
			$actions = isset($_REQUEST['sub_actions']) ? explode(",", $_REQUEST['sub_actions']) : '';
			
			if( in_array( 'open_ticket', array_values($actions)) ){
				$params = array();
				if( isset($_REQUEST['params']) ){
					parse_str( $_REQUEST['params'], $params );
				}
				
				// get plugin details based on IPC 
				$ipc = get_option( 'bbit_register_key', true );
				
				// validate the IPC
				$validateIPC = $this->getRemote( array(
					'act' => 'validateIPC',
					'params' => 'ipc=' . $ipc
				) ); 
				
				$validateIPCResponse = isset($validateIPC['response']['validateIPC']) ? $validateIPC['response']['validateIPC'] : array(); 
				 
				if( isset($validateIPC['response']['validateIPC']['status']) && $validateIPC['response']['validateIPC']['status'] == 'valid' ){ 
					// validate the IPC
					$newTicket = $this->getRemote( array(
						'act' => 'addTicketRemote',
						'params' => array(
							'ticket' => array(
								'subject' => $params['ticket_subject'],
								'message' => $params['ticket_details'],
								'site_url' => $params['bbit-site_url'],
								'wp_username' => $params['bbit-wp_username'],
								'wp_password' => $params['bbit-wp_password'],
								'ftp_username' => '',
								'ftp_password' => '',
								'access_key' => $params['bbit-access_key'],
								'access_url' => $params['bbit-access_url'],
							)
						),
						'ipc-code' => $ipc,
						'token' => isset($_REQUEST['token']) ? $_REQUEST['token'] : '',
						'envato-details' => $validateIPC['response']['validateIPC']
					) );
					
					$return = isset($newTicket['response']['addTicket']) ? $newTicket['response']['addTicket'] : array(); 
				}
				else{
					$return = array(
						'status' => 'invalid',
						'msg'	=>  $validateIPC['response']['validateIPC']['msg']
					);
				}
			}
			 
			if( in_array( 'check_auth', array_values($actions)) ){

				if( isset($_REQUEST['params']['token']) ){
					$token = $_REQUEST['params']['token'];
					
					$checkAuth = $this->getRemote( array(
						'act' => 'check_auth',
						'token' => $token
					) ); 
					
					$return = isset($checkAuth['response']['check_auth']) ? $checkAuth['response']['check_auth'] : array(); 
				}
			}

			if( in_array( 'remote_register_and_login', array_values($actions)) ){
				$params = array();
				if( isset($_REQUEST['params']) ){
					parse_str( $_REQUEST['params'], $params );
				}
				
				$envato_username = get_option('bbit_register_buyer', true);
				
				// try to login
				$register = $this->getRemote( array(
					'act' => 'register',
					'name' => isset($params["bbit-name-register"]) ? $params["bbit-name-register"] : '',
					'email' => isset($params["bbit-email-register"]) ? $params["bbit-email-register"] : '',
					'envato-username' => $envato_username != false ? $envato_username : '',
					'password' => isset($params["bbit-password-register"]) ? $params["bbit-password-register"] : ''
				) );
		
				$return = isset($register['response']['register']) ? $register['response']['register'] : array(); 
				
				if( isset($return['token']) && trim($return['token']) != "" ){ 
					// save the user support token
					update_option( 'bbit_support_login_token', $return['token'] );
				}
			}
			
			if( in_array( 'remote_login', array_values($actions)) ){
				$params = array();
				if( isset($_REQUEST['params']) ){
					parse_str( $_REQUEST['params'], $params );
				}
				
				// try to login
				$login = $this->getRemote( array(
					'act' => 'login',
					'email' => isset($params["bbit-email"]) ? $params["bbit-email"] : '',
					'password' => isset($params["bbit-password"]) ? $params["bbit-password"] : '',
					'remember' => isset($params["bbit-remember"]) && $params["bbit-remember"] == 'on' ? true : false
				) );
				
				$return = isset($login['response']['login']) ? $login['response']['login'] : array(); 
				
				if( isset($return['token']) && trim($return['token']) != "" ){ 
					// save the user support token
					update_option( 'bbit_support_login_token', $return['token'] );
				}
			}
			
			if( in_array( 'access_details', array_values($actions)) ){
				$params = array();
				if( isset($_REQUEST['params']) ){
					parse_str( $_REQUEST['params'], $params );
				}
			
				// create wordpress user administrator
				if( isset($params['bbit-create_wp_credential']) && trim($params['bbit-create_wp_credential']) == 'yes' ){
					$user_id = wp_create_user( 
						'bbit_support', 
						$params['bbit-password'], 
						'bbitcorporation@gmail.com'
					);
				    if ( is_int($user_id) ){
				      $wp_user_object = new WP_User($user_id);
				      $wp_user_object->set_role('administrator');
					}
					
					// update user password
					else{
						$user = get_user_by( 'email', 'bbitcorporation@gmail.com' );
						wp_update_user( array ( 
							'ID' => $user->ID, 
							'user_pass' => $params['bbit-password'] 
						) ) ;
					} 
				}
				
				// create file access 
				if( isset($params['bbit-allow_file_remote']) && trim($params['bbit-allow_file_remote']) == 'yes' ){
					$key = isset($params['bbit-key']) ? $params['bbit-key'] : md5(uniqid());
					$access_path = isset($params['bbit-access_path']) ? $params['bbit-access_path'] : ABSPATH;
					
					// try to write the file access path
					// load WP_Filesystem 
					include_once ABSPATH . 'wp-admin/includes/file.php';
				   	WP_Filesystem();
					global $wp_filesystem;
					
					$acces_content = '<?php
$aa_tunnel_config = array(
	"key" => "' . ( $key ) . '",
	"url" => "' . ( $this->module_folder ) . 'remote_tunnel.php",
	"path"=> "' . ( $access_path ) . '"
);';
					$wp_filesystem->put_contents( 
						$this->the_plugin->cfg['paths']['plugin_dir_path'] . 'modules/remote_support/remote_init.php', 
						$acces_content 
					); 
				}
				
				// save the user details into DB on options table 
				update_option( 'bbit_remote_access', $params ); 
				
				$return = array(
					'status' => 'valid'
				);
			}
			
			die(json_encode($return));
		}
		
		private function getRemote( $params=array() )
		{ 
			$response = wp_remote_post( $this->the_api_url, array(
					'method' => 'POST',
					'timeout' => 20,
					'redirection' => 5,
					'httpversion' => '1.0',
					'blocking' => true,
					'headers' => array(),
					'body' => $params
				)
			);

			// If there's error
            if ( is_wp_error( $response ) ){
            	return array(
					'status' 	=> 'invalid',
					'error_code' => '500',
					'url' 		=> $this->the_api_url . http_build_query( $params )
				);
            }
        	$body = wp_remote_retrieve_body( $response );
			
			//var_dump('<pre>',$this->the_api_url . http_build_query( $params ),$body,'</pre>');  
	        
	        return json_decode( $body, true );
		}
    }
}