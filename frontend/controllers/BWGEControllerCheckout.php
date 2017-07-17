<?php

class BWGEControllerCheckout extends BWGEControllerFrontend{
	////////////////////////////////////////////////////////////////////////////////////////
	// Events                                                                             //
	////////////////////////////////////////////////////////////////////////////////////////
	////////////////////////////////////////////////////////////////////////////////////////
	// Constants                                                                          //
	////////////////////////////////////////////////////////////////////////////////////////
	////////////////////////////////////////////////////////////////////////////////////////
	// Variables                                                                          //
	////////////////////////////////////////////////////////////////////////////////////////
	private $response = "";
	private $status = "";
    private $thank_you_page;
    private $order_shipping = 0;
    private $order_rand_ids = false;
	////////////////////////////////////////////////////////////////////////////////////////
	// Constructor & Destructor                                                           //
	////////////////////////////////////////////////////////////////////////////////////////
    public function __construct($params = ""){

        parent::__construct($params);
        $model_checkout = BWGEHelper::get_model("checkout",true,$this->params) ;
        $options = $model_checkout->get_options();
        $thank_you_page_status = get_post_status($options->thank_you_page);
        if($thank_you_page_status == "publish"){
            $this->thank_you_page = get_permalink($options->thank_you_page);
        }
        else{
            $this->thank_you_page = add_query_arg(array("task"=>"thank_you"),get_permalink($options->checkout_page));
        }
    }
	////////////////////////////////////////////////////////////////////////////////////////
	// Public Methods                                                                     //
	////////////////////////////////////////////////////////////////////////////////////////
	public function display_main_container(){
		$view = $this->view;
		$view->display_main_container();
	}

	public function display_checkout_form(){
		$view = $this->view;
		$view->display_checkout_form();
	}

      public function show_add_to_cart(){
        $image_id = (int)$_POST["image_id"];
        $view = $this->view;
        $view->show_add_to_cart($image_id);
      }

	public function add_cart(){
		global $wpdb;
		$response = array();
		$data = BWGEHelper::get("data");
		$data = json_decode(htmlspecialchars_decode(stripslashes($data)));
		$image_id = (int)BWGEHelper::get("image_id");
		$type = BWGEHelper::get("type");
		$model_checkout = BWGEHelper::get_model("checkout",true,$this->params) ;
		$pricelist_id = $wpdb->get_var($wpdb->prepare('SELECT pricelist_id FROM ' . $wpdb->prefix . 'bwge_image WHERE id="%d" ',$image_id));
		$redirect = 0;
		if($type == ""){
			$msg = __("Please select Prints or Downloads", 'bwge') ;
			$products_in_cart = $model_checkout->get_cart_products_count();
		}
		else if(!$data ){
			$msg = "";
			$products_in_cart = $model_checkout->get_cart_products_count();
		}
		else if($pricelist_id == 0){
			$msg = "";
			$products_in_cart = $model_checkout->get_cart_products_count();
		}
		else{

			$options = $model_checkout->get_options();
			$user = get_current_user_id();
			if($user == 0 && $options->enable_guest_checkout == 0){
				$msg = __('Login please ', 'bwge');
				$products_in_cart = 0;
				$redirect = 1;
			}

			else{

				$order_rand_ids = isset($_COOKIE["order_rand_ids"]) ? explode("," , $_COOKIE["order_rand_ids"]) : array();
                if(empty($order_rand_ids) == false){
                    array_walk($order_rand_ids, create_function('&$value', '$value = (int)$value;'));
                }
				$ip_address = 'unknown';
				if (isset($_SERVER['HTTP_CLIENT_IP'])) {
					$ip_address = $_SERVER['HTTP_CLIENT_IP'];
				} else if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
					$ip_address = $_SERVER['HTTP_X_FORWARDED_FOR'];
				} else if (isset($_SERVER['HTTP_X_FORWARDED'])) {
					$ip_address = $_SERVER['HTTP_X_FORWARDED'];
				} else if (isset($_SERVER['HTTP_FORWARDED_FOR'])) {
					$ip_address = $_SERVER['HTTP_FORWARDED_FOR'];
				} else if (isset($_SERVER['HTTP_FORWARDED'])) {
					$ip_address = $_SERVER['HTTP_FORWARDED'];
				} else if (isset($_SERVER['REMOTE_ADDR'])) {
					$ip_address = $_SERVER['REMOTE_ADDR'];
				}

				if($type == "manual"){
					if($user == 0){
						// get rand id
						$existing_rand_ids = $wpdb->get_col('SELECT rand_id FROM ' . $wpdb->prefix . 'bwge_order_images ');
						do {
							$rand_id = rand(10000000, 99999999);
						} while (in_array($rand_id, $existing_rand_ids) == true);

					}
					$image_order_rows = array();
					if($user == 0 && empty($order_rand_ids) === false){
						// get image order rows
						foreach($order_rand_ids as $order_rand_id){
							$image_order_row = $wpdb->get_row($wpdb->prepare('SELECT * FROM ' . $wpdb->prefix . 'bwge_order_images
							WHERE user_id="%d" AND rand_id = "%d" AND image_id="%d" AND order_id="%d" AND pricelist_download_item_id="%d"', $user, $order_rand_id, $image_id, 0, 0));
							if($image_order_row){
								if($image_order_row != NULL){
									$image_order_rows[] = $image_order_row;
								}
							}
						}
					}
					elseif($user != 0 ){
						// get image order rows
						$image_order_rows = $wpdb->get_results($wpdb->prepare('SELECT * FROM ' . $wpdb->prefix . 'bwge_order_images
						WHERE user_id="%d" AND image_id="%d" AND order_id="%d" AND pricelist_download_item_id="%d"',$user,$image_id,0,0));
					}
					$insert = true;
					$parameters = $data->parameters;
					$count = $data->count;
					$price = $data->price;
					if(empty($image_order_rows) === false){
						foreach($image_order_rows as $image_order_row){

							if($image_order_row->parameters != '[]'){
								$order_row_parameters = json_decode(htmlspecialchars_decode(stripslashes($image_order_row->parameters)));

								if(count((array)$order_row_parameters) == count((array)$parameters)){
									// compare 	$order_row_parameters and $parameters
									$array_compare = array();
									foreach($order_row_parameters as $key => $order_row_parameter){
										if(!isset($parameters->$key)){
											$array_compare[] = 0;
											break;
										}

										if(is_array($order_row_parameter) === true){
											$array_compare[] = array_diff($parameters->$key,$order_row_parameter) == array() ? 1 : 0;
										}
										else{
											$array_compare[] = ($parameters->$key == $order_row_parameter) ? 1 : 0;
										}
									}

									if(count($array_compare) == array_sum($array_compare) ){
										$order_image_row_id = $image_order_row->id;
										$count +=  $image_order_row->products_count;
										$insert = false;
										break;
									}
								}
							}

						}
					}
					if($insert == true){
						$data = array();
						if($user == 0){
							$data["rand_id"] = $rand_id;
						}
						else{
							$data["user_id"] = $user;
						}

						$data["order_id"] = 0;
						$data["image_id"] = $image_id;
						$data["products_count"] = $count;
						$data["parameters"] = empty($parameters) === false ? json_encode($parameters) : '[]';
						$data["user_ip_address"] = $ip_address;
						$data["pricelist_id"] = $pricelist_id ;
						$data["price"] = (float)$price ;
						$data["currency"] = $options->currency ;
						$data["currency_sign"] = $options->currency_sign ;

						$format = array('%d','%d','%d', '%d','%s','%s','%d','%s','%s','%s');
						$wpdb->insert( $wpdb->prefix . "bwge_order_images", $data, $format );

					}
					else{
						$data = array("products_count"=>$count);
						$where = array("id"=>$order_image_row_id);
						$format = array('%d');
						$where_format = array('%d');
						$wpdb->update( $wpdb->prefix . "bwge_order_images", $data, $where, $format, $where_format );

					}
					if($user == 0 && $insert == true){
						$order_rand_ids[] = $rand_id;
						setcookie("order_rand_ids", implode(",",$order_rand_ids),  time() + (10 * 365 * 24 * 60 * 60),  "/");
					}

				}
				else{
					$pricelist_download_items = $data->downloadItems;
					for( $i=0; $i<count($pricelist_download_items); $i++ ){
						if($user == 0){
							// get rand id
							$existing_rand_ids = $wpdb->get_col('SELECT rand_id FROM ' . $wpdb->prefix . 'bwge_order_images ');
							do {
								$rand_id = rand(10000000, 99999999);
							} while (in_array($rand_id, $existing_rand_ids) == true);

						}
						$pricelist_download_item = $pricelist_download_items[$i];
						$pricelist_download_item_id	= (int)$pricelist_download_item->id;
						$pricelist_download_item_count	= $pricelist_download_item->count;
						$pricelist_download_item_price	= $pricelist_download_item->price;
						$image_order_rows_for_download_items = array();

						if($user == 0 && empty($order_rand_ids ) === false){

                            $image_order_rows_for_download_items = $wpdb->get_row('SELECT * FROM ' . $wpdb->prefix . 'bwge_order_images
                            WHERE user_id="0" AND rand_id IN ('.implode(",",$order_rand_ids).')  AND image_id="'.$image_id.'" AND order_id="0" AND pricelist_download_item_id="'.$pricelist_download_item_id.'"');

						}
						elseif($user != 0){
							$image_order_rows_for_download_items = $wpdb->get_row($wpdb->prepare('SELECT * FROM ' . $wpdb->prefix . 'bwge_order_images
							WHERE user_id="%d" AND image_id="%d" AND order_id="%d" AND pricelist_download_item_id="%d"',$user,$image_id,0,$pricelist_download_item_id));
						}

						if(empty($image_order_rows_for_download_items) === false ){

                            $count = $image_order_rows_for_download_items->products_count + $pricelist_download_item_count;
                            $data = array("products_count"=>$count);
                            $where = array("id"=>$image_order_rows_for_download_items->id);
                            $format = array('%d');
                            $where_format = array('%d');
                            $wpdb->update( $wpdb->prefix . "bwge_order_images", $data, $where, $format, $where_format );

                            $insert = false;
						}
						else{
							$data = array();
							$data["order_id"] = 0;
							if($user == 0){
								$data["rand_id"] = $rand_id;
							}
							else{
								$data["user_id"] = $user;
							}
							$data["image_id"] = $image_id;
							$data["products_count"] = 1;
							$data["user_ip_address"] = $ip_address;
							$data["pricelist_id"] = $pricelist_id ;
							$data["price"] = (float)$pricelist_download_item_price ;
							$data["pricelist_download_item_id"] = $pricelist_download_item_id;
							$data["products_count"] = $pricelist_download_item_count;
							$data["currency"] = $options->currency ;
							$data["currency_sign"] = $options->currency_sign ;

							$format = array('%d','%d', '%d','%d','%s','%d','%s','%d','%d','%s','%s');

							$wpdb->insert( $wpdb->prefix . "bwge_order_images", $data, $format );
							$insert = true;
						}
						if($user == 0 && $insert == true){
							$order_rand_ids[] = $rand_id;
							setcookie("order_rand_ids", implode(",",$order_rand_ids),  time() + (10 * 365 * 24 * 60 * 60),  "/");
						}

					}

				}

				$products_in_cart = $model_checkout->get_cart_products_count($order_rand_ids);
				$msg =  __("Successfully added!", 'bwge');

			}
		}
		$response["msg"] = $msg;
		$response["products_in_cart"] = $products_in_cart;
		$response["redirect"] = $redirect;
		echo json_encode($response);
		exit;
	}

	public function update_cart(){
		global $wpdb;
		$id = BWGEHelper::get("id");
		if($id){
			$type = BWGEHelper::get("type");

			if($type == "count"){
				$count = BWGEHelper::get("productCounts");
				if($count <= 0){
					$count = 1;
				}
				$data = array("products_count" => $count);
				$data_format = array('%d');

			}
			if($type == "parameters"){
				$parameters = BWGEHelper::get("parameters") ;

				$data = array("parameters" => stripslashes($parameters));
				$data_format = array('%s');
			}

			$where = array("id"=>$id);
			$where_format = array('%d');

			$wpdb->update( $wpdb->prefix . "bwge_order_images", $data, $where, $data_format, $where_format );
		}

		$this->display_main_container();
		die();
	}

	public function init_checkout(){
		if (!session_id()){
			session_start();
		}
		$session_data = array();
		$session_data["payment_method"] = BWGEHelper::get("payment_method");
		$session_data["billing_data_name"] = BWGEHelper::get("billing_data_name");
		$session_data["billing_data_email"] = BWGEHelper::get("billing_data_email");
		$session_data["billing_data_country"] = BWGEHelper::get("billing_data_country");
		$session_data["billing_data_city"] = BWGEHelper::get("billing_data_city");
		$session_data["billing_data_address"] = BWGEHelper::get("billing_data_address");
		$session_data["billing_data_zip_code"] = BWGEHelper::get("billing_data_zip_code");
		$session_data["shipping_data_name"] = BWGEHelper::get("same_billing_shipping") == 1 ? BWGEHelper::get("billing_data_name") : BWGEHelper::get("shipping_data_name");
		$session_data["shipping_data_country"] = BWGEHelper::get("same_billing_shipping") == 1 ? BWGEHelper::get("billing_data_country") : BWGEHelper::get("shipping_data_country");
		$session_data["shipping_data_city"] = BWGEHelper::get("same_billing_shipping") == 1 ? BWGEHelper::get("billing_data_city") :BWGEHelper::get("shipping_data_city");
		$session_data["shipping_data_address"] = BWGEHelper::get("same_billing_shipping") == 1 ? BWGEHelper::get("billing_data_address") :BWGEHelper::get("shipping_data_address");
		$session_data["shipping_data_zip_code"] = BWGEHelper::get("same_billing_shipping") == 1 ? BWGEHelper::get("billing_data_zip_code") :BWGEHelper::get("shipping_data_zip_code");
		$_SESSION["bwge_payment_data"] = $session_data;
	}

	public function get_session_data(){

		if (!session_id()){
			session_start();
		}

		return $_SESSION["bwge_payment_data"];
	}

	public function checkout(){
		$payment_method = BWGEHelper::get("payment_method") ;
		$this->init_checkout();
		if($payment_method){
			$model_checkout = BWGEHelper::get_model("checkout",true,$this->params) ;
			$products_data = $model_checkout->get_order_product_rows() ;
            $this->order_shipping = $model_checkout->order_shipping;
			switch($payment_method){
 				case "paypalstandart":
					$this->pay_with_paypal_standart($products_data);
					break;
				case "without_online_payment":
					$this->pay_without_online($products_data);
					break;
			}
		}

	}
	public function remove_all(){
		global $wpdb;
		$model_checkout = BWGEHelper::get_model("checkout",true,$this->params) ;
		$options = $model_checkout->get_options();
		$order_rand_ids = isset($_COOKIE["order_rand_ids"]) ? explode("," , $_COOKIE["order_rand_ids"]) : array();
        if(empty($order_rand_ids) == false){
            array_walk($order_rand_ids, create_function('&$value', '$value = (int)$value;'));
        }
		$user = get_current_user_id();
		$order_rows = array();
		$where = array();
		$where["order_id"] = 0;

		if($user == 0 && empty($order_rand_ids ) === false){
			$where["user_id"] = 0;
			$where_format = array("%d","%d","%d");

			foreach($order_rand_ids as $order_rand_id){
				$where["rand_id"] = $order_rand_id;
				$wpdb->delete( $wpdb->prefix . 'bwge_order_images', $where, $where_format);
			}
			setcookie("order_rand_ids", NULL , 0,  "/");

		}
		elseif($user != 0 ){

			$where["user_id"] = $user;
			$where_format = array("%d","%d");
			$wpdb->delete( $wpdb->prefix . 'bwge_order_images', $where, $where_format);
		}

		BWGEHelper::bwge_redirect( get_permalink($options->checkout_page));

	}

	public function remove_item(){
		global $wpdb;
		$model_checkout = BWGEHelper::get_model("checkout",true,$this->params) ;
		$options = $model_checkout->get_options();
		$user = get_current_user_id();
		if($user == 0){
			$order_rand_ids = isset($_COOKIE["order_rand_ids"]) ? explode("," , $_COOKIE["order_rand_ids"]) : array();
            if(empty($order_rand_ids) == false){
                array_walk($order_rand_ids, create_function('&$value', '$value = (int)$value;'));
            }
			$rand_id = $wpdb->get_var($wpdb->prepare('SELECT rand_id FROM ' . $wpdb->prefix . 'bwge_order_images
			WHERE  id="%d" ',BWGEHelper::get("current_id")));
			$rand_id_index = array_search($rand_id,$order_rand_ids);
			unset($order_rand_ids[$rand_id_index]);
			array_values($order_rand_ids);
			setcookie("order_rand_ids", implode(",",$order_rand_ids),  time() + (10 * 365 * 24 * 60 * 60),  "/");
		}
		$where["id"] = BWGEHelper::get("current_id");
		$where_format = array("%d");
		$wpdb->delete( $wpdb->prefix . 'bwge_order_images', $where, $where_format);

		BWGEHelper::bwge_redirect( get_permalink($options->checkout_page));


	}

	public function pay_with_paypal_standart($products_data){
        global $wpdb;
        $model_checkout = BWGEHelper::get_model("checkout",true,$this->params) ;
        $api_options = $model_checkout->get_payments_api_options("paypalstandart");
        $options = $model_checkout->get_options();

        $this->status = "pending";
        $order_id = $this->finish_checkout($products_data);

        $user = get_current_user_id();
		// set paypal checkout mode
		$is_production = $api_options->mode == 1 ? true : false;
        BWGEPaypalstandart::set_production_mode($is_production);

        // set paypal email or id
        BWGEPaypalstandart::set_paypal_id($api_options->paypal_email);

        $params = array();

        $params["currency_code"] = $options->currency;
        $items = array();
        $i = 1;
        $count_of_items = count($products_data);
        foreach ($products_data as $product_data) {

            $items["item_name_" . $i] = $product_data->product_name;
            $items["amount_" . $i] = $product_data->final_price;
            $items["tax_" . $i] = $product_data->tax_price;
            $items["quantity_" . $i] = $product_data->products_count;
            $items["description_" . $i] = substr(strip_tags($product_data->product_description), 0, 50).'...';
            $items["shipping_" . $i] = $count_of_items ? $this->order_shipping/$count_of_items : 0;

            $i++;
        }
        $order_rand_ids = isset($_COOKIE["order_rand_ids"]) ? explode("," , $_COOKIE["order_rand_ids"]) : array();
        if(empty($order_rand_ids) == false){
            array_walk($order_rand_ids, create_function('&$value', '$value = (int)$value;'));
        }
        $order_rand_ids = implode(",", $order_rand_ids);
        $params["notify_url"] = add_query_arg(array("task"=>'handle_paypal_checkout_notify', 'order_id'=>$order_id, 'user_id'=> $user , 'payment_method' => 'paypal_standart','order_rand_ids' => $order_rand_ids), get_permalink($options->checkout_page));

        $params["cancel_url"] = add_query_arg(array('task' => 'paypal_standart_checkout_cancel', 'order_id'=>$order_id), get_permalink($options->checkout_page));
        $params["return"] = add_query_arg(array('task' => 'paypal_standart_checkout_return', 'order_id'=>$order_id), get_permalink($options->checkout_page));


        BWGEPaypalstandart::request($params, $items);
    }
    public function paypal_standart_checkout_return() {
        $order_id = BWGEHelper::get("order_id");
        $this->send_checkout_email($order_id, 1 );
        BWGEHelper::bwge_redirect($this->thank_you_page);
    }

    public function paypal_standart_checkout_cancel() {
        global $wpdb;
        $order_id = BWGEHelper::get("order_id");
        $data["status"] = "cancelled";
        $where = array("id" => $order_id);
		$where_format = array('%d');
		$format = array('%s');
		$wpdb->update( $wpdb->prefix . "bwge_orders", $data, $where, $format, $where_format );

        BWGEHelper::bwge_redirect($this->cancel_page);
    }


	public function handle_paypal_checkout_notify(){
		global $wpdb;

	    $order_id = $_GET['order_id'];
        if ($order_id == 0) {
            return false;
        }

		$this->user = $_GET['user_id'];
		$order_rand_ids = isset($_GET['order_rand_ids']) ? explode(",", $_GET['order_rand_ids']) : array();
		$this->order_rand_ids = $order_rand_ids;		
		$model_checkout = BWGEHelper::get_model("checkout",true,$this->params) ;
		
        if(isset($_GET['payment_method']) && $_GET['payment_method'] == "paypal_standart"){
            $api_options = $model_checkout->get_payments_api_options("paypalstandart");
            $is_production = $api_options->mode == 1 ? true : false;
            BWGEPaypalstandart::set_production_mode($is_production);
            // validate ipn
            $ipn_data = BWGEPaypalstandart::validate_ipn();
        }
        else{
            $api_options = $model_checkout->get_payments_api_options("paypalexpress");

            $is_production = $api_options->mode == 1 ? true : false;
            BWGEPaypalexpress::set_production_mode($is_production);

            // validate ipn
            $ipn_data = BWGEPaypalexpress::validate_ipn();
        }

        if (is_array($ipn_data) == false || empty($ipn_data) == true) {
            return false;
        }

		$payment_status = $ipn_data['payment_status'];

		$data["status"] = "pending";
		if($payment_status == "Completed"){
			$this->send_checkout_email($order_id);
			$data["status"] = "confirmed";
		}
		else if($payment_status == 'Failed' || $payment_status == 'Denied'){
			$this->send_checkout_email($order_id, 2);
			$data["status"] = "cancelled";
		}
		elseif($payment_status == 'Pending'){
			$data["status"] = "pending";
		}
		elseif($payment_status == 'Refunded'){
			$data["status"] = "refunded";
		}

		$where = array("id"=>$order_id);
		$where_format = array('%d');
		$format = array('%s');
		$wpdb->update( $wpdb->prefix . "bwge_orders", $data, $where, $format, $where_format );

		$log_content =json_encode($ipn_data);
        //file_put_contents(WD_BWGE_DIR."/log/".$order_id . '_' . date("Y_m_d_H_i_s") . '.txt', $log_content);

	}


	public function pay_without_online($products_data){
		$model_checkout = BWGEHelper::get_model("checkout",true,$this->params);
		$options = $model_checkout->get_options();
		$this->status = "pending";
		$order_id = $this->finish_checkout($products_data);
        //pending email
		$this->send_checkout_email($order_id, 1);
        //confirmed email
        $this->send_checkout_email($order_id);
		BWGEHelper::bwge_redirect($this->thank_you_page);
	}


	////////////////////////////////////////////////////////////////////////////////////////
	// Getters & Setters                                                                  //
	////////////////////////////////////////////////////////////////////////////////////////
	////////////////////////////////////////////////////////////////////////////////////////
	// Private Methods                                                                    //
	////////////////////////////////////////////////////////////////////////////////////////
	protected function display() {
		$view = $this->view;
		$model_checkout = BWGEHelper::get_model("checkout",true,$this->params);
		$options = $model_checkout->get_options();
		$user = get_current_user_id();

		if($options->enable_guest_checkout == 0 && $user == 0){
			echo  __('Login please', 'bwge');
		}
		else{
			$view->display();
		}
	}
    protected function thank_you() {
        $view = $this->view;
		$view->thank_you();
    }

	private function finish_checkout($products_data){
		global $wpdb;
		$session_data = $this->get_session_data();
		$model_checkout = BWGEHelper::get_model("checkout",true,$this->params) ;
		$options = $model_checkout->get_options();
        $user_id = get_current_user_id();
		$user = get_userdata($user_id);
        $user_first_name =  get_user_meta ($user_id ,"first_name", true);
        $user_last_name =  get_user_meta ( $user_id,"last_name", true);
		$user_name = $user_first_name." ".$user_last_name;
		// insert order row
		$data_order_row = array();
		$data_order_row["rand_id"] =  "";
		$timezone = get_option('timezone_string');
		if($timezone){
			date_default_timezone_set($timezone);
		}

		$data_order_row["checkout_date"] =  date("Y-m-d H:i:s");
		$data_order_row["user_id"] = get_current_user_id();
		$data_order_row["status"] =  $this->status;
		$data_order_row["payment_method"] = $session_data["payment_method"] ;
		$data_order_row["currency"] = $options->currency ;
		$data_order_row["payment_data"] = esc_html(stripslashes($this->response)) ;
		$data_order_row["billing_data_name"] =  $session_data['billing_data_name'] != "" ?  $session_data['billing_data_name'] : ( $options->show_shipping_billing == 1 ? $user_name : "");
		$data_order_row["billing_data_email"] = $session_data['billing_data_email'] != "" ?  $session_data['billing_data_email'] : (isset($user->user_email) ? $user->user_email : "") ;
		$data_order_row["billing_data_country"] =  $session_data['billing_data_country'] != "" ?  $session_data['billing_data_country'] : "";
		$data_order_row["billing_data_city"] =  $session_data['billing_data_city'] != "" ?  $session_data['billing_data_city'] : "";
		$data_order_row["billing_data_address"] =  $session_data['billing_data_address'] != "" ?  $session_data['billing_data_address'] : "";
		$data_order_row["billing_data_zip_code"] =  $session_data['billing_data_zip_code'] != "" ?  $session_data['billing_data_zip_code'] : "";
		$data_order_row["shipping_data_name"] =  $session_data['shipping_data_name'] != "" ?  $session_data['shipping_data_name'] : ( $options->show_shipping_billing == 1 &&  $options->enable_shipping ? $user_name : "");
		$data_order_row["shipping_data_country"] =  $session_data['shipping_data_country'] != "" ?  $session_data['shipping_data_country'] : "";
		$data_order_row["shipping_data_city"] =  $session_data['shipping_data_city'] != "" ?  $session_data['shipping_data_city'] : "";
		$data_order_row["shipping_data_address"] =  $session_data['shipping_data_address'] != "" ?  $session_data['shipping_data_address'] : "";
		$data_order_row["shipping_data_zip_code"] =  $session_data['shipping_data_zip_code'] != "" ?  $session_data['shipping_data_zip_code'] : "";
		$data_order_row["currency_sign"] = $options->currency_sign ;


		$data_order_row_format = array("%d","%s","%d","%s","%s","%s","%s","%s","%s","%s","%s","%s","%s","%s","%s","%s","%s","%s","%s");

		$wpdb->insert( $wpdb->prefix . "bwge_orders", $data_order_row, $data_order_row_format );
		$order_id = $wpdb->get_var("SELECT MAX(id) FROM ". $wpdb->prefix . "bwge_orders");

		$order_rand_ids = isset($_COOKIE["order_rand_ids"]) ? explode("," , $_COOKIE["order_rand_ids"]) : array();
        if(empty($order_rand_ids) == false){
            array_walk($order_rand_ids, create_function('&$value', '$value = (int)$value;'));
        }
		// update order images rows
		$rand_ids = array();
		foreach($products_data as $product_data){
			$data_order_images_row = array();
			$data_order_images_row["order_id"] = $order_id;

			//$image_name = $wpdb->get_var($wpdb->prepare("SELECT thumb_url FROM ". $wpdb->prefix . "bwge_image WHERE id='%d'", $product_data->image_id));

			//$data_order_images_row["filename"] = ($product_data->pricelist_download_item_id != 0) ? md5($product_data->product_longest_dimension.$product_data->image_id.basename($image_name)) : "";
			$product_longest_dimension = $product_data->product_longest_dimension;
		    $file_path = str_replace("thumb",".original", htmlspecialchars_decode( $product_data->thumb_url, ENT_COMPAT | ENT_QUOTES));

			list($img_width) = @getimagesize(htmlspecialchars_decode($file_path, ENT_COMPAT | ENT_QUOTES));
			if($product_data->product_longest_dimension > $img_width){
				$product_longest_dimension = $img_width;
			}

			// create file
			$file = ($product_data->pricelist_download_item_id != 0) ? $model_checkout->create_downloadable_items($product_data->image_id, $product_data->thumb_url,$product_longest_dimension) : array("file_hush_name"=>"", "file_name"=>"");

			$data_order_images_row["filename"] = ($product_data->pricelist_download_item_id != 0) ? $file["file_hush_name"] : "";
			$data_order_images_row["attachement_name"] = ($product_data->pricelist_download_item_id != 0) ? $file["file_name"] : "";

			$data_order_images_row["tax_rate"] = $product_data->tax_rate;
			$data_order_images_row["shipping_price"] =  ($product_data->pricelist_download_item_id == 0) ? $product_data->shipping : 0;
			$data_order_images_row["currency"] = $options->currency;
			$data_order_images_row["currency_sign"] = $options->currency_sign;
			$data_order_images_row["paramemeters_price"] = $product_data->paramemeters_price;
			$data_order_images_row["item_longest_dimension"] = $product_data->item_longest_dimension ;
			$data_order_images_row["pricelist_name"] = $product_data->product_name ;
			$data_order_images_row["image_name"] = ($product_data->alt ? $product_data->alt : $product_data->image_name) ;
			$data_order_images_row["shipping_type"] = $product_data->shipping_type ;

			$data_order_images_row_format = array("%d","%s","%s","%s","%s","%s","%s","%s","%s","%s","%s","%s");

			$where = array("id"=>$product_data->order_image_id);
			$where_format = array('%d');

			$wpdb->update( $wpdb->prefix . "bwge_order_images", $data_order_images_row, $where, $data_order_images_row_format, $where_format );
			if($product_data->rand_id){
				$rand_ids[] = $product_data->rand_id;
			}				
		}
		if(count($rand_ids)>0){
			$wpdb->update( $wpdb->prefix . "bwge_orders", array("rand_id" => implode(",",$rand_ids)), array("id"=>$order_id), array("%s"), array("%d") );
		}
		return $order_id;

	}

	private function send_checkout_email($order_id, $type = 0){
		global $wpdb;
		$model_checkout = BWGEHelper::get_model("checkout",true,$this->params) ;
		$options = $model_checkout->get_options();
		$products_data = $model_checkout->get_order_product_rows($order_id, $this->user, $this->order_rand_ids);
        $shipping = $model_checkout->order_shipping;
        $order_row =  $wpdb->get_row($wpdb->prepare('SELECT * FROM ' . $wpdb->prefix . 'bwge_orders WHERE id="%d" ',$order_id));
		BWGECheckoutEmail::send_checkout_email($options, $products_data, $order_row, $this->user, $type, $shipping);
	}





	////////////////////////////////////////////////////////////////////////////////////////
	// Listeners                                                                          //
	////////////////////////////////////////////////////////////////////////////////////////
}
