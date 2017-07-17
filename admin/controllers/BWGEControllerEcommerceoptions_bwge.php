<?php

class BWGEControllerEcommerceoptions_bwge extends BWGEController{
	////////////////////////////////////////////////////////////////////////////////////////
	// Events                                                                             //
	////////////////////////////////////////////////////////////////////////////////////////
	////////////////////////////////////////////////////////////////////////////////////////
	// Constants                                                                          //
	////////////////////////////////////////////////////////////////////////////////////////
	////////////////////////////////////////////////////////////////////////////////////////
	// Variables                                                                          //
	////////////////////////////////////////////////////////////////////////////////////////
	////////////////////////////////////////////////////////////////////////////////////////
	// Constructor & Destructor                                                           //
	////////////////////////////////////////////////////////////////////////////////////////
	////////////////////////////////////////////////////////////////////////////////////////
	// Public Methods                                                                     //
	////////////////////////////////////////////////////////////////////////////////////////
	public function apply(){
		global $wpdb;
		$query = "SELECT name FROM ". $wpdb->prefix . "bwge_ecommerceoptions";
        // get option names
		
        $names =  $wpdb->get_col( $query , 0 );		
		$thank_you_page_id = -1;
		$pages_bwge = $this->get_pages();

		if(BWGEHelper::get("thank_you_page") == "thank_you_page"){
			$thank_you_page = BWGEHelper::get_pages_by_title('Thank you');

			if(!$thank_you_page || ($thank_you_page && !in_array($pages_bwge["thank_you_page"],$thank_you_page))){
				$page = array(
				 'post_title'    => __('Thank you',"bwge_back"),
				 'post_name'     => 'bwge Thank you',
				 'post_content'  => '[Gallery_Ecommerce page=thank_you ]',
				 'post_status'   => 'publish',
				 'post_author'   => 1,
				 'post_type'     => 'bwge_ecommerce_page',
                 'comment_status' => 'closed',
				);
				$thank_you_page_id = wp_insert_post( $page );
			}
			else{
				$thank_you_page_id = $thank_you_page[0];
			}
		}

        // check supported currency
        $payment_methods = array();
        if(BWGEHelper::get("published_ps") == 1){
            $payment_methods[] = "paypal";
        }
              
        $msg = $this->check_currency(BWGEHelper::get("currency"),$payment_methods);
        
        // update options
		for ($i = 0; $i < count($names); $i++) {
			$name = $names[$i];
			$value = BWGEHelper::get($name, null);
		
			if($name  == "thank_you_page" && $thank_you_page_id != -1){
				$value = $thank_you_page_id ;
			}

			if ($value !== null  ) {
				$data = array();
				$data["value"] = $value;
				$where = array("name"=>$name);
				$where_format = $format = array('%s');
				$wpdb->update( $wpdb->prefix . "bwge_ecommerceoptions", $data, $where, $format, $where_format );
			}
		}
		
        $this->store_payment_data();
		
		BWGEHelper::message(__("Options Succesfully Saved.","bwge_back"),'updated');
        if($msg != ""){
            BWGEHelper::message( $msg,'error');      
        }
		$this->display();
		

	}
	
	public function generate_pages(){
		global $wpdb;

		$pages_bwge = $this->get_pages();

		$pages = array();
		
		$checkout_page = BWGEHelper::get_pages_by_title('Checkout');

		if(!$checkout_page || ($checkout_page && !in_array($pages_bwge["checkout_page"],$checkout_page))){
			$page = array(
			 'post_title'    => __('Checkout',"bwge_back"),
			 'post_name'     => 'bwge Checkout',
			 'post_content'  => '[Gallery_Ecommerce page=checkout ]',
			 'post_status'   => 'publish',
			 'post_type'     => 'bwge_ecommerce_page',
       'comment_status' => 'closed',
       'post_author'   => 1,
             
			);
			$checkout_page_id = wp_insert_post( $page, true  );
			$pages["checkout_page"] = $checkout_page_id;
		}
       
		$cancel_page = BWGEHelper::get_pages_by_title('Cancel') ;
		if(!$cancel_page || ($cancel_page && !in_array($pages_bwge["cancel_page"],$cancel_page))){
			$page = array(
			 'post_title'    => __('Cancel',"bwge_back"),
			 'post_name'     => 'bwge Cancel',
			 'post_content'  => '[Gallery_Ecommerce page=cancel ]',
			 'post_status'   => 'publish',
			 'post_type'     => 'bwge_ecommerce_page',
       'comment_status' => 'closed',
       'ping_status' => 'closed',
       'post_author'   => 1,
			);
			$cancel_page_id = wp_insert_post( $page , true );
			$pages["cancel_page"] = $cancel_page_id;
		}
			

		$orders_page = BWGEHelper::get_pages_by_title('Orders') ;
		if(!$orders_page || ($orders_page && !in_array($pages_bwge["orders_page"],$orders_page))){
			$page = array(
			 'post_title'    => __('Orders',"bwge_back"),
			 'post_name'     => 'bwge Orders',
			 'post_content'  => '[Gallery_Ecommerce page=orders ]',
			 'post_status'   => 'publish',
			 'post_type'     => 'bwge_ecommerce_page',
       'comment_status' => 'closed',
       'post_author'   => 1,
			);
			$orders_page_id = wp_insert_post( $page, true  );
			$pages["orders_page"] = $orders_page_id;
		}
 
		if(empty($pages) === false){
	
			foreach ($pages as $page_name => $page_id) {
				$data = array();
				$data["value"] = $page_id;
				$where = array("name"=>$page_name);
				$where_format = $format = array('%s');

				$wpdb->update( $wpdb->prefix . "bwge_ecommerceoptions", $data, $where, $format, $where_format );
				
			}
			$msg = __("Pages Succesfully Generated.","bwge_back");
		}
		else{
			$msg = __("Pages Already exist.","bwge_back");
		}
		
		BWGEHelper::message($msg, 'updated');
		$this->display();
	
	}
	
	////////////////////////////////////////////////////////////////////////////////////////
	// Getters & Setters                                                                  //
	////////////////////////////////////////////////////////////////////////////////////////
	////////////////////////////////////////////////////////////////////////////////////////
	// Private Methods                                                                    //
	////////////////////////////////////////////////////////////////////////////////////////
    private function store_payment_data(){
		global $wpdb;
        $format = array('%s','%s','%d');
        $where_format = array('%d');
        
        // without online 
		$data = array();       
		$data["name"] = esc_html(BWGEHelper::get("name_w"));		
		$data["options"] = "";			
		$data["published"] = esc_html(BWGEHelper::get("published_w"));
		$where = array("id"=>BWGEHelper::get("id_w"));		
		$wpdb->update( $wpdb->prefix . "bwge_payment_systems", $data, $where, $format, $where_format );
        
        // paypal atandart
        $data = array();       
		$data["name"] = esc_html(BWGEHelper::get("name_ps"));		
		$data["options"] = esc_html(stripslashes(BWGEHelper::get("options_paypalstandart")));			
		$data["published"] = esc_html(BWGEHelper::get("published_ps"));
		$where = array("id"=>BWGEHelper::get("id_ps"));		
		$wpdb->update( $wpdb->prefix . "bwge_payment_systems", $data, $where, $format, $where_format );
        
			
	}
    
	private function get_pages(){
		global $wpdb;
		$bwge_pages_array = array();
		$bwge_pages = $wpdb->get_results('SELECT value, name FROM ' . $wpdb->prefix . 'bwge_ecommerceoptions WHERE name="checkout_page" OR name="thank_you_page" OR name="orders_page" OR name="cancel_page"');
		foreach ($bwge_pages as $bwge_page) {
			$bwge_pages_array[$bwge_page->name] = $bwge_page->value;
		}		
		return $bwge_pages_array;
	}
    
    private function check_currency($currency,$payment_methods){
        $not_supported = array(); 
		$stripe = array( "AED","AFN","ALL","AMD","ANG","AOA","ARS","AUD","AWG","AZN","BAM","BBD","BDT","BGN","BIF","BMD","BND","BOB","BRL","BSD","BWP","BZD","CAD","CDF","CHF","CLP","CNY","COP","CRC","CVE","CZK","DJF","DKK","DOP","DZD","EEK","EGP","ETB","EUR","FJD","FKP","GBP","GEL","GIP","GMD","GNF","GTQ","GYD","HKD","HNL","HRK","HTG","HUF","IDR","ILS","INR","ISK","JMD","JPY", "KES","KGS","KHR","KMF","KRW","KYD","KZT","LAK","LBP","LKR","LRD","LSL","LTL","LVL","MAD","MDL","MGA","MKD","MNT","MOP","MRO","MUR","MVR","MWK","MXN","MYR","MZN","NAD","NGN","NIO","NOK","NPR","NZD","PAB","PEN","PGK","PHP","PKR","PLN","PYG","QAR", "RON","RSD","RUB","RWF","SAR","SBD","SCR","SEK", "SGD","SHP","SLL","SOS","SRD","STD","SVC","SZL","THB","TJS","TOP","TRY", "TTD","TZS","UAH","UGX","USD","UYU","UZS","VEF","VND","VUV","WST","XAF","XCD","XOF","XPF","YER","ZAR","ZMW" );

		$paypal = array("AUD","BRL","CAD","AUD","BRL","CAD","CZK","DKK","EUR","HKD","HUF","ILS","JPY","MYR","MXN","NOK","NZD","PHP","PLN","GBP","RUB","SGD","SEK","CHF","TWD","THB","TRY","USD");

        if(in_array("paypal",$payment_methods) && !in_array($currency,$paypal)){         
            $not_supported[] = "Paypal" ;           
        }
        if(in_array("stripe",$payment_methods) && !in_array($currency,$stripe)){         
            $not_supported[] = __("Stripe doesn't support ".$currency,"bwge_back");           
        }
	
        if(empty($not_supported) == false){
            $doesnt = count($not_supported)>1 ? " don't" : " doesn't";
            $not_supported = implode(", ",$not_supported);
             
            return __( sprintf("%s %s support %s.",$not_supported,$doesnt,$currency ),"bwge_back");
        }
        return "";
	}

	////////////////////////////////////////////////////////////////////////////////////////
	// Listeners                                                                          //
	////////////////////////////////////////////////////////////////////////////////////////
}