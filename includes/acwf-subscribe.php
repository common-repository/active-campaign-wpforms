<?php
if (!defined('ABSPATH'))
    exit;
if(!class_exists('Acwf_Subscribe')){

	class Acwf_Subscribe{

		public function __construct(){
 
			add_action('wpforms_process',array($this,'acwf_sends_data_to_ac'),10,3);
		}

		public function acwf_sends_data_to_ac($fields,$entry,$form_data){
            
            //Global Settings
			$AC_URL = wpforms_setting('ac-url');
			$API_KEY = wpforms_setting('ac-apikey');
			$list_ID = wpforms_setting('ac-listid');
            
            $entry_id = $form_data['id'];
            $acintegration = $form_data['settings']['ac-integration'];
			$acfield = $form_data['settings']['assign-acfields'];

			$ac_ebable = $acintegration['enable-ac'];
			$AC_URL = isset($acintegration['ac-url']) ? $acintegration['ac-url'] : $AC_URL;
			$API_KEY = isset($acintegration['ac-apikey']) ? $acintegration['ac-apikey'] : $API_KEY;
			$list_ID = isset($acintegration['list-id']) ? $acintegration['list-id'] : $list_ID;

            $email = $this->process_tag( $acfield['email'], $form_data,$fields,$entry_id );
            $fname = $this->process_tag( $acfield['first-name'], $form_data,$fields,$entry_id);
            $lname = $this->process_tag( $acfield['last-name'], $form_data,$fields,$entry_id );
            $phone = $this->process_tag( $acfield['phone'], $form_data,$fields,$entry_id );
            $org = $this->process_tag( $acfield['organization'], $form_data,$fields,$entry_id );
            
			//Active Campaign starts
			if($ac_ebable == '1' && !empty($email)){

				if(!empty($AC_URL) && !empty($API_KEY) && !empty($list_ID)){

					$body = array(
						'email'                    => $email,
						'p['.$list_ID.']'          => $list_ID, 
						'status['.$list_ID.']'     => 1,
						'instantresponders['.$list_ID.']' => 0,
					);

					if(!empty($fname)){
						$body['first_name'] = $fname;
					}
					if(!empty($lname)){
						$body['last_name'] = $lname;
					}
					if(!empty($phone)){
						$body['phone'] = $phone;
					}
					if(!empty($org)){
						$body['orgname'] = $org;
					}
					//print_r($body);
					//die();

					$args = array(
                		'method' => 'POST',
                		'timeout'     => 15,
                		'redirection' => 15,
                		'headers'     => "Content-Type: application/x-www-form-urlencoded",
                		'body' => $body,
                	);
                	
                	$api_url = $AC_URL . "/admin/api.php?api_action=contact_sync&api_output=json&api_key=".$API_KEY;
                	$response = wp_remote_request( $api_url, $args);
                
                	if( is_wp_error( $response ) ) {
                		wpforms()->process->errors[ $form_data['id'] ]['header'] = esc_html__( 'Error in saving Data to Active Campaign', 'active-campaign-wpforms' );
                	}
				}else{
					wpforms()->process->errors[ $form_data['id'] ]['header'] = esc_html__( 'You have enabled Active Campaign but have not add API Credentials and List ID.', 'active-campaign-wpforms' );
				}
			}

		}

		public function process_tag( $form_data, $fields, $entry_id, $string = '' ) {

			$tag = apply_filters( 'wpforms_process_smart_tags', $string, $form_data, $fields, $entry_id );

			$tag = wpforms_decode_string( $tag );
			$tag = sanitize_text_field( $tag );

			return $tag;
		}	

	}
	new Acwf_Subscribe();
}