<?php

class BWGEPaypalstandart {
    ////////////////////////////////////////////////////////////////////////////////////////
    // Events                                                                             //
    ////////////////////////////////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////////////////////////////
    // Constants                                                                          //
    ////////////////////////////////////////////////////////////////////////////////////////
    /**
     * API Version
     * @var string
     */
    public static $currencies_without_decimal_support = array('HUF', 'TWD', 'JPY');
	
    /**
     * Editable field types
     * 
     */
	 

	public static $field_types = array('mode'             => array('type'=>'radio','options'=>array('Sandbox','Production'),'text'=>'Checkout mode','attributes'=>''),	
									   'paypal_email'      => array('type'=>'text','text'=>'Paypal email','attributes'=>''));

    ////////////////////////////////////////////////////////////////////////////////////////
    // Variables                                                                          //
    ////////////////////////////////////////////////////////////////////////////////////////
    /**
     * API Version
     * @var string
     */
    private static $version = '74.0';

    /**
     * production or sandbox(testing)
     * @var boolean
     */
    private static $is_production = false;
    
    private static $paypal_id = false;

    /**
     * error message(s)
     * @var array
     */
    private static $errors = array();


    ////////////////////////////////////////////////////////////////////////////////////////
    // Constructor & Destructor                                                           //
    ////////////////////////////////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////////////////////////////
    // Public Methods                                                                     //
    ////////////////////////////////////////////////////////////////////////////////////////
    /**
     * set checkout mode. production if true, sandbox if false
     *
     * @param array $credentials seller credentials (USER, PWD, SIGNATURE)
     */
    public static function set_production_mode($is_production) {
        self::$is_production = $is_production;
    }
    
      /**
     * set paypal email or bussines id
     *
     * @paypal_email string
     */
    public static function set_paypal_id($paypal_id) {
        self::$paypal_id = $paypal_id;
    }

    /**
     * Make API request
     *
     * @param string $method string API method to request
     * @param array $params Additional request parameters
     * @return array / boolean Response array / boolean false on failure
     */
    public static function request($params, $items) {
       
        $paypal_action = self::$is_production == true ? 'https://www.paypal.com/cgi-bin/webscr?' : 'https://www.sandbox.paypal.com/cgi-bin/webscr?';
        
        $paypal_params = array("cmd" => "_cart", "business" => self::$paypal_id, "upload" => "1");

        $str_request = http_build_query($paypal_params + $params + $items);

        BWGEHelper::bwge_redirect($paypal_action.$str_request);	
    }


    /**
     * checks is payment notification valid and returns it
     *
     * @return array ipn data / boolean false if notification is invalid
     */
	public static function validate_ipn() {
        $paypal_action = self::$is_production == true ? 'https://www.paypal.com/cgi-bin/webscr' : 'https://www.sandbox.paypal.com/cgi-bin/webscr';

        $ipn_data = array();
        foreach ($_POST as $key => $value) {
          $ipn_data[$key] = $value;
        }

        $request_data = array('cmd' => '_notify-validate') + $ipn_data;
        $request = http_build_query($request_data);

        $curl_handle = curl_init();
        curl_setopt_array($curl_handle, array(
          CURLOPT_URL => $paypal_action,
          CURLOPT_HEADER => 0,
          CURLOPT_POST => 1,
          CURLOPT_POSTFIELDS => $request,
          CURLOPT_SSL_VERIFYPEER => true,
          CURLOPT_SSLVERSION => 1,
          CURLOPT_RETURNTRANSFER => 1));
        $response = curl_exec($curl_handle);
        curl_close($curl_handle);
        

        return $ipn_data;
    }

    /**
     * get errors
     *
     * @return array array of error msgs
     */
    public static function get_errors() {
        return self::$errors;
    }
	

    ////////////////////////////////////////////////////////////////////////////////////////
    // Getters & Setters                                                                  //
    ////////////////////////////////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////////////////////////////
    // Private Methods                                                                    //
    ////////////////////////////////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////////////////////////////
    // Listeners                                                                          //
    ////////////////////////////////////////////////////////////////////////////////////////
	

}
