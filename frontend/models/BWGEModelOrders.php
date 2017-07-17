<?php

class BWGEModelOrders extends BWGEModelFrontend {
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
	public function get_orders($id = false){
		global $wpdb;
		global $WD_BWGE_UPLOAD_DIR;		
		$user = get_current_user_id();	
		$options = $this->get_options();
		
		if($id === false){		
			// get user orders 
			$orders = $wpdb->get_results($wpdb->prepare('SELECT * FROM ' . $wpdb->prefix . 'bwge_orders 
			WHERE user_id="%d" ORDER BY checkout_date DESC ',$user));
			
		}
		else{
            $id = (int)$id;
            $id = $id == 0 ? -1 : $id;
			$orders = $wpdb->get_results($wpdb->prepare('SELECT * FROM ' . $wpdb->prefix . 'bwge_orders 
			WHERE user_id="%d" AND id="%d" ',$user,$id));				
		}
        if(empty($orders) === false){
            foreach($orders as $order){ 
                $order->order_images = $this->get_order_product_rows($order->id);
                $files = array();
                foreach($order->order_images as $order_row){
                    if($order_row->pricelist_download_item_id != 0 ){
                        $image_name = $wpdb->get_var($wpdb->prepare("SELECT filename FROM ". $wpdb->prefix . "bwge_image WHERE id='%d'", $order_row->image_id));
                        $files[$order_row->order_image_id] = $image_name;
                    }
                    
                }
                $order->files = $files;

            }
            
            return ($id == 0) ? $orders : $orders[0];
        }
       
       return false; 

	}
    public function get_order_shipping($order_id){
        global $wpdb;
        $order_id = (int)$order_id;
        $pricelist_shippings = array(); 
        if($order_id){
            $order_rows = $wpdb->get_results($wpdb->prepare('SELECT * FROM ' . $wpdb->prefix . 'bwge_order_images  WHERE  order_id="%d"',$order_id));

            foreach($order_rows as  $order_row) {
                if($order_row->pricelist_download_item_id == 0){
                    if($order_row->shipping_type != "flat"){
                        $pricelist_shippings[] = $order_row->shipping_price*$order_row->products_count;
                    }
                    else{
                        $pricelist_shippings["pr".$order_row->pricelist_id] = $order_row->shipping_price;
                    }               
                }            
            } 
        }
        return array_sum($pricelist_shippings);    
    }    
	////////////////////////////////////////////////////////////////////////////////////////
	// Getters & Setters                                                                  //
	////////////////////////////////////////////////////////////////////////////////////////
	////////////////////////////////////////////////////////////////////////////////////////
	// Private Methods                                                                    //
	////////////////////////////////////////////////////////////////////////////////////////
	private function get_order_product_rows($order_id){
		global $wpdb;
		global $WD_BWGE_UPLOAD_DIR;			
		$order_rows = array();
		$options = $this->get_options();
		$model_checkout = BWGEHelper::get_model("checkout",true,$this->params) ;
		
		// get image order rows
		$order_rows = $wpdb->get_results($wpdb->prepare('SELECT  T_IMAGE.thumb_url, T_IMAGE.alt, T_IMAGE.filetype, T_ORDER_IMAGES.id AS order_image_id,  T_ORDER_IMAGES.*, T_ORDER_IMAGES.price AS order_image_price FROM ' . $wpdb->prefix . 'bwge_order_images AS T_ORDER_IMAGES
		LEFT JOIN ' . $wpdb->prefix . 'bwge_image AS T_IMAGE ON  T_ORDER_IMAGES.image_id = T_IMAGE.id  WHERE  T_ORDER_IMAGES.order_id="%d" ',$order_id));			

		foreach($order_rows as $order_row){
			
			$order_row->product_name = $order_row->pricelist_name;
			$order_row->product_description = $order_row->pricelist_download_item_id != 0 ? $order_row->item_longest_dimension : "";
			$order_row->product_price =  floatval($order_row->order_image_price);
			$order_row->product_longest_dimension = $order_row->pricelist_download_item_id != 0 ? $order_row->item_longest_dimension : "";
			$order_row->product_price_text =  $order_row->currency_sign.number_format($order_row->product_price,2) ;

			if($order_row->pricelist_download_item_id != 0 ){
				$order_row->subtotal = floatval(($order_row->product_price * (1 + $order_row->tax_rate / 100))*$order_row->products_count);
				$order_row->final_price = floatval($order_row->product_price);
				$order_row->shipping = 0;				
				$order_row->international_shipping = 0;				
			}	
			else{	
				$order_row->parameters = json_decode(htmlspecialchars_decode($order_row->parameters));
				
				$parameters_price = 0;
				$selected_parameters = array();
				foreach($order_row->parameters as $parameter_id => $parameter){
					$parameter_row = $this->get_row_by_id($parameter_id , 'parameters');
					if($parameter_row && $parameter_row->type !=1 && $parameter_row->type !=2 && $parameter_row->type !=3){
						if(is_array($parameter) == true){
							$selected_parameter = array();
							foreach($parameter as $item){
								$selected_parameter[] = $item;
								$item = explode("*",$item);
								$parameters_price = ($item[0] == "+") ? ($parameters_price + $item[1]) : ($parameters_price - $item[1]);								
							}
							$selected_parameters[$parameter_id] = $selected_parameter;
						}
						else{
							$selected_parameters[$parameter_id] = $parameter;
							$parameter = explode("*",$parameter);
							$parameters_price = ($parameter[0] == "+") ? ($parameters_price + $parameter[1]) : ($parameters_price - $parameter[1]);							
						}
					}
					else {
						$selected_parameters[$parameter_id] = $parameter;						
					}
				}
				$order_row->selected_parameters = $selected_parameters;
				$order_row->selected_parameters_string = $this->get_selected_parameter_string($selected_parameters, $order_row->currency_sign, $order_row->pricelist_id );
				
				$order_row->paramemeters_price = floatval($parameters_price);
				$order_row->final_price = floatval(($order_row->product_price + $parameters_price));
				
				$order_row->subtotal = floatval($order_row->final_price * (1 + $order_row->tax_rate / 100)) ;
               // $order_row->shipping = $order_row->shipping_price ;					
						
				$order_row->subtotal = $order_row->products_count * $order_row->subtotal;
                //$order_row->subtotal = $order_row->subtotal + $order_row->shipping_price ;
			}
            
			$order_row->tax_price = floatval(($order_row->final_price *  $order_row->tax_rate) / 100);
			//$order_row->shipping_text = $order_row->currency_sign . number_format($order_row->shipping,2) ;
		 			
			$order_row->final_price_text = $order_row->currency_sign . number_format($order_row->final_price,2) ;
			$order_row->subtotal_text = $order_row->currency_sign.number_format($order_row->subtotal,2);
			
			// get parameters
			$order_row->selectable_parameters = $model_checkout->get_parameters($order_row->pricelist_id);
			
			// thumb url 
		
			if(strpos($order_row->filetype, 'EMBED') === false ){
				$order_row->thumb_url = site_url()."/".$WD_BWGE_UPLOAD_DIR.$order_row->thumb_url;
			}
		}	
		
		return 	$order_rows;
		
	}	
	private function get_selected_parameter_string($selected_parameters, $currency, $pricelist_id){
		global $wpdb;
		$selected_parameter_array = array();

		foreach($selected_parameters as $selected_parameter_id => $selected_parameter_value){
			$parameter_row = $wpdb->get_row($wpdb->prepare('SELECT *  FROM ' . $wpdb->prefix . 'bwge_parameters
			WHERE id = "%d"',$selected_parameter_id));
            if($parameter_row){
                if($parameter_row->type == 4 || $parameter_row->type == 5 || $parameter_row->type == 6){
                    
                    if(is_array($selected_parameter_value)){
                        foreach($selected_parameter_value as $parameter_value){
                            $value = explode("*",$parameter_value);        
                            $price_addon = $value[1] == "0" ? "" :  " (".$value[0]." ".$value[1].$currency.")";
                            $selected_parameter_array[] = $parameter_row->title.": ".$value[2].$price_addon;
                        }
                    }	
                    else{
                        
                        $value = explode("*",$selected_parameter_value);         
                        $parameter_name = isset($value[2]) ? $value[2] : "";
                        if($parameter_name == ""){
                            continue;
                        }
                        $price_addon = $value[1] == "0" ? "" :  " (".$value[0]." ".$value[1].$currency.")";
                        $selected_parameter_array[] = $parameter_row->title.": ".$parameter_name. $price_addon;
                    }
                }
                elseif($parameter_row->type == 2 || $parameter_row->type == 3  ){
                    if($selected_parameter_value){
                        $value = ": ".$selected_parameter_value;
                    }
                    $selected_parameter_array[] = $parameter_row->title .$value;
                }
                else{
                    $single_parameter_value = $wpdb->get_var($wpdb->prepare('SELECT parameter_value  FROM ' . $wpdb->prefix . 'bwge_pricelist_parameters WHERE parameter_id = "%d" AND pricelist_id="%d"',$selected_parameter_id, $pricelist_id));
                    $selected_parameter_array[] = $parameter_row->title.": ". $single_parameter_value ;
                }
            }
		
		}
		
		$selected_parameter_str = implode("<br>",$selected_parameter_array );
		return $selected_parameter_str;
	
	}	
	////////////////////////////////////////////////////////////////////////////////////////
	// Listeners                                                                          //
	////////////////////////////////////////////////////////////////////////////////////////
}