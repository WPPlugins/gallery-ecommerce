<?php

class BWGEModelCheckout extends BWGEModelFrontend {
	////////////////////////////////////////////////////////////////////////////////////////
	// Events                                                                             //
	////////////////////////////////////////////////////////////////////////////////////////
	////////////////////////////////////////////////////////////////////////////////////////
	// Constants                                                                          //
	////////////////////////////////////////////////////////////////////////////////////////
	////////////////////////////////////////////////////////////////////////////////////////
	// Variables                                                                          //
	////////////////////////////////////////////////////////////////////////////////////////
    public $order_shipping = 0;
	////////////////////////////////////////////////////////////////////////////////////////
	// Constructor & Destructor                                                           //
	////////////////////////////////////////////////////////////////////////////////////////
	////////////////////////////////////////////////////////////////////////////////////////
	// Public Methods                                                                     //
	////////////////////////////////////////////////////////////////////////////////////////
	public function get_order_product_rows($order_id = 0, $user = false, $order_rand_ids = false){
		global $wpdb;
		global $WD_BWGE_UPLOAD_DIR;

        if($order_rand_ids === false){
            $order_rand_ids = isset($_COOKIE["order_rand_ids"]) ? explode("," , $_COOKIE["order_rand_ids"]) : array();
            if(empty($order_rand_ids) == false){
                array_walk($order_rand_ids, create_function('&$value', '$value = (int)$value;'));
            }
        }
		if($user === false){
			$user = get_current_user_id();
		}
		$order_rows = array();
		$options = $this->get_options();

		if($user == 0 && empty($order_rand_ids) === false){
			foreach($order_rand_ids as $order_rand_id){
				// get image order rows
				$order_row = $wpdb->get_row($wpdb->prepare('SELECT T_ORDER_IMAGES.*,T_ORDER_IMAGES.id AS order_image_id, T_IMAGE.thumb_url, T_IMAGE.alt, T_IMAGE.filetype, T_IMAGE.filename AS image_name, T_PRICELISTS.*, T_PRICELIST_ITEMS.item_price, T_PRICELIST_ITEMS.item_name, T_PRICELIST_ITEMS.item_longest_dimension
				FROM ' . $wpdb->prefix . 'bwge_order_images AS T_ORDER_IMAGES
				LEFT JOIN ' . $wpdb->prefix . 'bwge_image AS T_IMAGE ON  T_ORDER_IMAGES.image_id = T_IMAGE.id LEFT JOIN ' . $wpdb->prefix . 'bwge_pricelists AS T_PRICELISTS
				ON T_ORDER_IMAGES.pricelist_id = T_PRICELISTS.id LEFT JOIN ' . $wpdb->prefix . 'bwge_pricelist_items AS T_PRICELIST_ITEMS ON T_ORDER_IMAGES.pricelist_download_item_id = T_PRICELIST_ITEMS.id
				WHERE T_ORDER_IMAGES.user_id="%d" AND T_ORDER_IMAGES.rand_id = "%d"
				AND T_ORDER_IMAGES.order_id="%d" AND T_IMAGE.published="%d" ',$user,$order_rand_id,$order_id,1));
				if($order_row != NULL){
					$order_rows[] = $order_row;
				}
			}

		}
		elseif($user != 0){
			// get image order rows
			$order_rows = $wpdb->get_results($wpdb->prepare('SELECT T_ORDER_IMAGES.*,T_ORDER_IMAGES.id AS order_image_id, T_IMAGE.thumb_url, T_IMAGE.alt, T_IMAGE.filetype, T_IMAGE.filename AS image_name, T_PRICELISTS.*, T_PRICELIST_ITEMS.item_price, T_PRICELIST_ITEMS.item_name, T_PRICELIST_ITEMS.item_longest_dimension
			FROM ' . $wpdb->prefix . 'bwge_order_images AS T_ORDER_IMAGES
			LEFT JOIN ' . $wpdb->prefix . 'bwge_image AS T_IMAGE ON  T_ORDER_IMAGES.image_id = T_IMAGE.id LEFT JOIN ' . $wpdb->prefix . 'bwge_pricelists AS T_PRICELISTS
			ON T_ORDER_IMAGES.pricelist_id = T_PRICELISTS.id LEFT JOIN ' . $wpdb->prefix . 'bwge_pricelist_items AS T_PRICELIST_ITEMS ON T_ORDER_IMAGES.pricelist_download_item_id = T_PRICELIST_ITEMS.id
			WHERE T_ORDER_IMAGES.user_id="%d" AND T_ORDER_IMAGES.order_id="%d" AND T_IMAGE.published="%d"  ',$user,$order_id,1));

		}
		$pricelist_shippings = array();
		foreach($order_rows as $order_row){
			$order_row->item_price = $order_row->item_price ? $order_row->item_price : 0.00;
			$order_row->price = $order_row->price ? $order_row->price : 0.00;

			$order_row->product_name = $order_row->pricelist_download_item_id != 0 ? $order_row->item_name : $order_row->manual_title;
			$order_row->product_description = $order_row->pricelist_download_item_id != 0 ? $order_row->item_longest_dimension : $order_row->manual_description;
			$order_row->product_price = $order_row->pricelist_download_item_id != 0 ? $order_row->item_price : $order_row->price;
			$order_row->product_longest_dimension = $order_row->pricelist_download_item_id != 0 ? $order_row->item_longest_dimension : "";
			$order_row->product_price_text = $order_row->pricelist_download_item_id != 0 ? $order_row->currency_sign.number_format($order_row->item_price,2) : $order_row->currency_sign.number_format($order_row->price,2);
			if(strpos($order_row->filetype, 'EMBED') === false ){
				$order_row->thumb_url =  site_url()."/".$WD_BWGE_UPLOAD_DIR.$order_row->thumb_url;
			}

			if($order_row->pricelist_download_item_id != 0 ){
				$order_row->subtotal = ($order_row->product_price * (1 + $order_row->tax_rate / 100))*$order_row->products_count;
				$order_row->final_price = $order_row->product_price;
				$order_row->shipping = 0;
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
					else{
						$selected_parameters[$parameter_id] = $parameter;
					}
				}
				$order_row->selected_parameters = $selected_parameters;

				$order_row->paramemeters_price = floatval($parameters_price);
				$order_row->final_price = ($order_row->product_price + $parameters_price);

				$order_row->subtotal = $order_row->final_price * (1 + $order_row->tax_rate / 100) ;

				$order_row->shipping = floatval(($order_row->shipping_type == "flat") ? $order_row->shipping_price : ($order_row->final_price*$order_row->shipping_price)/100);

                //$order_row->subtotal = $order_row->subtotal + $order_row->shipping ;
				$order_row->subtotal = $order_row->products_count * $order_row->subtotal;
                if($order_row->shipping_type != "flat"){
                    $pricelist_shippings[] = $order_row->shipping*$order_row->products_count;
                }
                else{
                    $pricelist_shippings["pr".$order_row->pricelist_id] = $order_row->shipping;
                }

			}
            $order_row->tax_price = ($order_row->final_price *  $order_row->tax_rate) / 100;

			$order_row->tax_price_text = $order_row->currency_sign . number_format($order_row->tax_price,2) ;
			$order_row->shipping_text = $order_row->currency_sign . number_format($order_row->shipping,2) ;

			$order_row->final_price_text = $order_row->currency_sign . number_format($order_row->final_price,2) ;
			$order_row->subtotal_text = $order_row->currency_sign.number_format($order_row->subtotal,2);

			// get parameters
			$order_row->selectable_parameters = $this->get_parameters($order_row->pricelist_id);

		}

        $this->order_shipping = array_sum($pricelist_shippings);


		return 	$order_rows;

	}

	public function get_payments_api_options($shortname){
		global $wpdb;

		$api_options_row = $wpdb->get_row($wpdb->prepare('SELECT options,published, name AS payment_name FROM ' . $wpdb->prefix . 'bwge_payment_systems WHERE short_name="%s"',$shortname));
		$api_options = json_decode(htmlspecialchars_decode($api_options_row->options));
		$api_options = $api_options ? $api_options : new stdClass();
		$api_options->published = $api_options_row->published;
		$api_options->payment_name = $api_options_row->payment_name;
		return $api_options;
	}

	public function get_cart_products_count($order_rand_ids = array()){
		global $wpdb;
		$user = get_current_user_id();
		$products_in_cart = 0;
		if($user == 0 && empty($order_rand_ids ) === false){
			// get image order rows
            $products_in_cart = $wpdb->get_var('SELECT SUM(products_count) FROM ' . $wpdb->prefix . 'bwge_order_images
            WHERE user_id="0" AND  rand_id IN ('.implode(",",$order_rand_ids).')  AND order_id="0" ');
		}
		elseif($user != 0 ){
			// get image order rows
			$products_in_cart = $wpdb->get_var($wpdb->prepare('SELECT SUM(products_count) FROM ' . $wpdb->prefix . 'bwge_order_images
			WHERE user_id="%d" AND order_id="%d" ',$user,0));
		}

		return 	$products_in_cart;

	}
	public function get_parameters($pricelist_id){
		global $wpdb;
		// parameters
		$parameter_rows= $wpdb->get_results($wpdb->prepare('SELECT T_PRICELIST_PARAMETERS.*, T_PARAMETERS.title,  T_PARAMETERS.type  FROM ' . $wpdb->prefix . 'bwge_pricelist_parameters AS T_PRICELIST_PARAMETERS LEFT JOIN ' . $wpdb->prefix . 'bwge_parameters
		AS T_PARAMETERS ON T_PRICELIST_PARAMETERS.parameter_id = T_PARAMETERS.id WHERE pricelist_id="%d" AND T_PARAMETERS.published="%d" ORDER BY T_PRICELIST_PARAMETERS.id',$pricelist_id,1));

		$parameters_map = array();
		foreach ($parameter_rows as $parameter_row) {
			$parameter_id = $parameter_row->parameter_id;
			$param_value = array();
			$param_value['parameter_value'] = $parameter_row->parameter_value;
			$param_value['parameter_value_price'] = $parameter_row->parameter_value_price;
			$param_value['parameter_value_price_sign'] = $parameter_row->parameter_value_price_sign;
			$parameters_map[$parameter_id]['title'] = $parameter_row->title;
			$parameters_map[$parameter_id]['type'] = $parameter_row->type;
			$parameters_map[$parameter_id]["values"][] = $param_value;

		}

		return $parameters_map;

	}
  public function get_image_pricelists($pricelist_id){
        $pricelist_data = array();
        if(!$pricelist_id){
            $pricelist = new StdClass();
            $pricelist->price = NULL;
            $pricelist->manual_description = NULL;
            $pricelist->manual_title = NULL;
            $pricelist->sections = NULL;

            $pricelist_data["pricelist"] = $pricelist;
            $pricelist_data["download_items"] = "";
            $pricelist_data["parameters"] = "";
            $options = new StdClass();
            $options->show_digital_items_count = NULL;
            $options->checkout_page = NULL;
            $options->currency_sign = NULL;
            $options->checkout_page = NULL;
            $pricelist_data["options"] = $options;
            $pricelist_data["products_in_cart"] =  0;

            return $pricelist_data;
        }

        global $wpdb;

        // pricelist
        $pricelist= $wpdb->get_row($wpdb->prepare('SELECT * FROM ' . $wpdb->prefix . 'bwge_pricelists WHERE id="%d" ',$pricelist_id));

        // download items
        $download_items= $wpdb->get_results($wpdb->prepare('SELECT * FROM ' . $wpdb->prefix . 'bwge_pricelist_items
        WHERE pricelist_id="%d" ',$pricelist_id));

        // parameters
        $parameter_rows= $wpdb->get_results($wpdb->prepare('SELECT T_PRICELIST_PARAMETERS.*, T_PARAMETERS.title,  T_PARAMETERS.type  FROM ' . $wpdb->prefix . 'bwge_pricelist_parameters AS T_PRICELIST_PARAMETERS LEFT JOIN ' . $wpdb->prefix . 'bwge_parameters
        AS T_PARAMETERS ON T_PRICELIST_PARAMETERS.parameter_id = T_PARAMETERS.id WHERE pricelist_id="%d" AND T_PARAMETERS.published="%d" ORDER BY T_PRICELIST_PARAMETERS.id',$pricelist_id,1));

        $parameters_map = array();
        foreach ($parameter_rows as $parameter_row) {
            $parameter_id = $parameter_row->parameter_id;
            $param_value = array();
            $param_value['parameter_value'] = $parameter_row->parameter_value;
            $param_value['parameter_value_price'] = $parameter_row->parameter_value_price;
            $param_value['parameter_value_price_sign'] = $parameter_row->parameter_value_price_sign;
            $parameters_map[$parameter_id]['title'] = $parameter_row->title;
            $parameters_map[$parameter_id]['type'] = $parameter_row->type;
            $parameters_map[$parameter_id]["values"][] = $param_value;

        }

        //options
        $options_rows = $wpdb->get_results('SELECT * FROM ' . $wpdb->prefix . 'bwge_ecommerceoptions ');
        $options = new stdClass();
        foreach ($options_rows as $row) {
            $name = $row->name;
            $value = $row->value;
            $options->$name = $value;
        }

        // shopping cart options
        $products_in_cart = 0;
        $order_rand_ids = isset($_COOKIE["order_rand_ids"]) ? explode("," , $_COOKIE["order_rand_ids"]) : array();

        $user = get_current_user_id();
        if($user == 0 && empty($order_rand_ids ) === false){
            array_walk($order_rand_ids, create_function('&$value', '$value = (int)$value;'));
            // get image order rows
            foreach($order_rand_ids as $order_rand_id){
                $product_in_cart = $wpdb->get_var($wpdb->prepare('SELECT products_count FROM ' . $wpdb->prefix . 'bwge_order_images
                WHERE user_id="%d" AND rand_id = "%d"  AND order_id="%d" ',$user,$order_rand_id,0));
                $products_in_cart += $product_in_cart;
            }

        }
        elseif($user != 0 ){
            // get image order rows
            $products_in_cart = $wpdb->get_var($wpdb->prepare('SELECT SUM(products_count) FROM ' . $wpdb->prefix . 'bwge_order_images
            WHERE user_id="%d" AND order_id="%d" ',$user,0));
        }

        $pricelist_data["pricelist"] = $pricelist;
        $pricelist_data["download_items"] = $download_items;
        $pricelist_data["parameters"] = $parameters_map;
        $pricelist_data["options"] = $options;
        $pricelist_data["products_in_cart"] = $products_in_cart ? $products_in_cart : 0;

        return $pricelist_data;
  }
  public function get_image_pricelist($image_id){
    global $wpdb;
    $image_pricelist = $wpdb->get_var($wpdb->prepare('SELECT pricelist_id FROM ' . $wpdb->prefix . 'bwge_image WHERE id="%d" ', $image_id));
    return $image_pricelist;
  }
  public function get_theme_row_data($id) {
    global $wpdb;
    if ($id) {
      $row = $wpdb->get_row($wpdb->prepare('SELECT * FROM ' . $wpdb->prefix . 'bwge_theme WHERE id="%d"', $id));
    }
    else {
      $row = $wpdb->get_row('SELECT * FROM ' . $wpdb->prefix . 'bwge_theme WHERE default_theme=1');
    }
    if (isset($row->options)) {
      $row = (object) array_merge((array) $row, (array) json_decode($row->options));
    }
    return $row;
  }
  public function create_downloadable_items( $image_id, $thumb_url, $item_longest_dimension){

      global $wpdb;
      global $WD_BWGE_UPLOAD_DIR;
      $file_path = str_replace("thumb",".original", htmlspecialchars_decode(  $thumb_url, ENT_COMPAT | ENT_QUOTES));

      $options =  $wpdb->get_col('SELECT * FROM ' . $wpdb->prefix . 'bwge_option');

      $model_checkout = BWGEHelper::get_model("checkout",true,$this->params) ;
      $ecommerce_options = $model_checkout->get_options();

      list($img_width, $img_height, $type) = @getimagesize(htmlspecialchars_decode($file_path, ENT_COMPAT | ENT_QUOTES));

      if (!$img_width || !$img_height) {
          return false;
      }
      else if($item_longest_dimension == $img_width){
          switch($type){
              case 2:
                  $extension = ".jpeg";
                  break;

              case 1:
                  $extension = ".gif";
                  break;

              case 3:
                  $extension = ".png";
                  break;

              default:
                  $src_img = null;
          }
          $new_file_path = WD_BWGE_DIR."/files/".md5($item_longest_dimension.$image_id.$extension);
          copy($file_path,$new_file_path);
      }
      else{
          $ratio = $img_width/$img_height;
          $max_width =  $item_longest_dimension;
          $max_height =  $item_longest_dimension/$ratio;

          if (!function_exists('imagecreatetruecolor')) {
              error_log('Function not found: imagecreatetruecolor');
              return false;
          }

          ini_set('memory_limit', '-1');

          if (($img_width / $img_height) >= ($max_width / $max_height)) {
              $new_width = $img_width / ($img_height / $max_height);
              $new_height = $max_height;
          }
          else {
              $new_width = $max_width;
              $new_height = $img_height / ($img_width / $max_width);
          }

          $dst_x = 0 - ($new_width - $max_width) / 2;
          $dst_y = 0 - ($new_height - $max_height) / 2;
          $new_img = @imagecreatetruecolor($max_width, $max_height);

          switch ($type) {
              case 2:
                  $src_img = @imagecreatefromjpeg($file_path);
                  $write_image = 'imagejpeg';
                  $image_quality = isset($options->jpeg_quality) ? $options->jpeg_quality : 75;
                  $extension = ".jpeg";
                  break;

              case 1:
                  @imagecolortransparent($new_img, @imagecolorallocate($new_img, 0, 0, 0));
                  $src_img = @imagecreatefromgif($file_path);
                  $write_image = 'imagegif';
                  $image_quality = null;
                  $extension = ".gif";
                  break;

              case 3:
                  @imagecolortransparent($new_img, @imagecolorallocate($new_img, 0, 0, 0));
                  @imagealphablending($new_img, false);
                  @imagesavealpha($new_img, true);
                  $src_img = @imagecreatefrompng($file_path);
                  $write_image = 'imagepng';
                  $image_quality = isset($options->png_quality) ? $options->png_quality : 9;
                  $extension = ".png";
                  break;

              default:
                  $src_img = null;

          }
          $new_file_path = WD_BWGE_DIR."/files/".md5($item_longest_dimension.$image_id.$extension);
          if(!file_exists(WD_BWGE_DIR."/files/".md5($item_longest_dimension.$image_id.$extension))){

              $success = $src_img && @imagecopyresampled($new_img, $src_img, $dst_x, $dst_y, 0, 0, $new_width, $new_height, $img_width,$img_height) && $write_image($new_img, $new_file_path, $image_quality);
              // Free up memory (imagedestroy does not delete files):
              @imagedestroy($src_img);
              @imagedestroy($new_img);
              ini_restore('memory_limit');
          }

      }

      /*
      if($ecommerce_options->attach_file_to_email == 1 ){
          $new_file_path_for_attachement = WD_BWGE_DIR."/files/attachments/".$item_longest_dimension.$image_id.$extension;
          // create file for attachement
          copy($new_file_path,$new_file_path_for_attachement);
      }
      */

      $file = array();
      $file["file_hush_name"] =  md5($item_longest_dimension.$image_id.$extension) ;
      $file["file_name"] =  $item_longest_dimension.$image_id.$extension ;

      return $file;

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
