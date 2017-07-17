<?php

class BWGECheckoutEmail {
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
  	public static function send_checkout_email($options, $products_data, $order_row,  $user, $type, $total_shipping, $send_to_admin = true ){
		global $wpdb;

		$total_amount = 0;
		$total_tax = 0;
		$items_count = 0;

		$is_downloadable = false;
		$shipping = false;
		foreach( $products_data as  $product_data){
			$total_amount +=  $product_data->subtotal;
			$total_tax +=  $product_data->tax_price*$product_data->products_count;
			$items_count +=  $product_data->products_count;
			if($product_data->pricelist_download_item_id != 0)	{
				$is_downloadable = true;
			}
            if($product_data->pricelist_download_item_id == 0 && $options->enable_shipping == 1)	{
				$shipping = true;
			}
		}

        $total_amount = $total_amount + $total_shipping;
        $total_shipping = $total_shipping ? $options->currency_sign. floatval($total_shipping) : "";
        $total_tax = $total_tax ? $options->currency_sign. floatval($total_tax) : "";

        // get Payment method
        $payment_method = $wpdb->get_var( "SELECT name FROM ".$wpdb->prefix . "bwge_payment_systems  WHERE short_name='".$order_row->payment_method."'" );

		$order_table = self::email_table($products_data,  $payment_method, $options->currency_sign. floatval($total_amount), $total_tax, $total_shipping);

		if($shipping == true){
            $shipping_info = self::email_shipping($order_row, $options->email_header_background_color);
            $br = "";
        }
        else{
            $shipping_info = "";
             $br = "<br>";
        }

		$billing_info = $br.self::email_billing($order_row, $options->email_header_background_color);

		$logo = $options->email_header_logo ? "<img src='".$options->email_header_logo."' style='width: 45px; vertical-align: middle;  margin-right: 12px;'>" : "";

		if($options->enable_email_admin == 1 && $type == 0 && $send_to_admin == true){
			$to = $options->email_recipient_admin;
			$subject = $options->email_subject_admin;
			$headers = array();

			$email_from_admin = $options->use_user_email_from == 0 && $options->email_from_admin ? $options->email_from_admin : $order_row->billing_data_email;

			$headers[] = $options->email_from_name_admin ? 'From: '.$options->email_from_name_admin . ' <'.$email_from_admin.'>' :'From: '.$email_from_admin  ;
			if($options->email_cc_admin){
				$headers[] = 'Cc: '.$options->email_cc_admin;
			}
			if($options->email_bcc_admin){
				$headers[] = 'Bcc: '.$options->email_bcc_admin;
			}
			$headers[] = $options->email_mode_admin == 1 ? 'Content-Type: text/html; charset=UTF-8' : 'Content-Type: text/plain; charset=UTF-8';

			$order_details_page =  '<a href="'.get_site_url()."/wp-admin/admin.php?page=orders_bwge&task=edit&id=".$order_row->id.'">Orders Link</a>' ;

            $message_to_admin = str_replace(array("%%customer_name%%","%%total_amount%%","%%order_details_page%%"),
            array($order_row->billing_data_name, $options->currency_sign.$total_amount, $order_details_page), $options->email_body_admin );

            $message_to_admin =  $message_to_admin."<br>" .$order_table;

			$body = '<div style="width:800px; border: 1px solid #ddd;font-family: \'Open Sans\', sans-serif" class="bwge">
						<div style="background:#'.$options->email_header_background_color.'; padding:30px; color:#'.$options->email_header_color.'; font-size: 23px">'.$logo. $subject.'</div>
						<div style="background:#fff; padding:30px;line-height: 26px; font-size: 14px;">'.$message_to_admin.'</div>';


			if($options->email_footer_text){
				$body .= "<div style='font-size:13px; padding: 0px 30px 30px;'>".$options->email_footer_text."</div>";
			}
			$body .= '</div>';
			$body .= '<style>.bwge a {color:#'.$options->email_header_background_color.'!important;}</style>';

			if($options->email_mode_admin == 0){
				$body = strip_tags($body);
			}

			wp_mail( $to, $subject, $body, $headers );
		}

		if($options->enable_email_user == 1){
			$to = $order_row->billing_data_email;
			$headers = array();
			if($options->email_from_name_user && $options->email_from_user){
				$headers[] = 'From: '.$options->email_from_name_user.' <'.$options->email_from_user.'>' ;
			}
			if($options->email_cc_user){
				$headers[] = 'Cc: '.$options->email_cc_user;
			}
			if($options->email_bcc_user){
				$headers[] = 'Bcc: '.$options->email_bcc_user;
			}
			$headers[] = $options->email_mode_user == 1 ? 'Content-Type: text/html; charset=UTF-8' : 'Content-Type: text/plain; charset=UTF-8';
			$attachments  = array();

			switch($type){
				case 0 :
					$subject = $options->email_subject_user;

					$oredr_details_page = $user  ? add_query_arg(array("task"=>'display_order', 'order_id'=>$order_row->id), get_permalink($options->orders_page)) : ( $is_downloadable ?  add_query_arg(array("task"=>'download_file_from_url', 'order_id'=>$order_row->id, 'key'=>md5('268413990'.$order_row->id.$order_row->rand_id) ), get_permalink($options->orders_page)) : "");

                    $oredr_details_page = '<a href="'.$oredr_details_page.'">Orders Link</a>';

                    $email_body = str_replace(array("%%customer_name%%","%%item_count%%","%%total_amount%%","%%order_details_page%%","%%site_url%%", "%%order_details_table%%"),
					array($order_row->billing_data_name,$items_count, $options->currency_sign.$total_amount, $oredr_details_page, get_site_url(),$order_table), $options->email_body_user );

                    if(strpos($email_body, "%%shipping_info%%") !== false && strpos($email_body, "%%billing_info%%") !== false && $shipping == true){

                        $shipping_billing_info = strpos($email_body, "%%shipping_info%%")< strpos($email_body, "%%billing_info%%") ? self::email_billing_shipping($order_row,$options->email_header_background_color, "shipping") : self::email_billing_shipping($order_row,$options->email_header_background_color, "billing");

                        $email_body = str_replace(array("%%shipping_info%%","%%billing_info%%"), array($shipping_billing_info,""), $email_body);
                    }
                    else{
                        $email_body = str_replace(array("%%shipping_info%%","%%billing_info%%"), array($shipping_info, $billing_info), $email_body);
                    }

					break;
				case 1 :
					$subject = $options->email_subject1_user;
					$email_body = str_replace(array("%%customer_name%%","%%item_count%%","%%total_amount%%","%%site_url%%"),
					array($order_row->billing_data_name,$items_count, $options->currency_sign.$total_amount, get_site_url()), $options->email_body1_user);
					break;
				case 2 :
					$subject = $options->email_subject2_user;
					$email_body = str_replace(array("%%customer_name%%","%%site_url%%"),
					array($order_row->billing_data_name, get_site_url()), $options->email_body2_user);
					break;
			}

			$body = '<div style="width:800px; border: 1px solid #ddd;font-family: \'Open Sans\', sans-serif" class="bwge">
						<div style="background:#'.$options->email_header_background_color.'; padding:30px; color:#'.$options->email_header_color.'; font-size: 23px">'.$logo. $subject.'</div>
						<div style="background:#fff; padding:30px;line-height: 26px; font-size: 14px;">'.$email_body.'</div>';


			if($options->email_footer_text){
				$body .= "<div style='font-size:13px; padding: 0px 30px 30px;'>".$options->email_footer_text."</div>";
			}
			$body .= '</div>';
			$body .= '<style> .bwge a {color:#'.$options->email_header_background_color.'!important;}</style>';
			if($options->email_mode_user == 0){
				$body = strip_tags($body);
			}

			$is_email_sent = wp_mail( $to, $subject, $body, $headers, $attachments );

            global $wpdb;
            $data["is_email_sent"] = $is_email_sent;
            $where = array("id"=>$order_row->id);
            $where_format = $format = array('%d');
            $wpdb->update( $wpdb->prefix . "bwge_orders", $data, $where, $format, $where_format );
		}

	}
    ////////////////////////////////////////////////////////////////////////////////////////
    // Getters & Setters                                                                  //
    ////////////////////////////////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////////////////////////////
    // Private Methods                                                                    //
    ////////////////////////////////////////////////////////////////////////////////////////
    private static function email_table($products_data, $payment_method, $total, $total_tax, $total_shipping ){
		$table = "<table style='width:100%; border-collapse:collapse;margin:15px 0px;'>";
		$table .= "<tr>";
		$table .= "<td style='padding:8px; border:1px solid #ddd; width:46%;'><b>".__("Pricelist","bwge")." / ". __("Image","bwge")."</b></td>";
		$table .= "<td style='padding:8px; border:1px solid #ddd; width:21%;'><b>".__("Count","bwge")."</b></td>";
		$table .= "<td style='padding:8px; border:1px solid #ddd;'><b>".__("Price","bwge")."</b></td>";
		$table .= "</tr>";
		foreach($products_data as $product_data){
			$table .= "<tr>";
			$table .= "<td style='padding:8px; border:1px solid #ddd;'>".$product_data->product_name." / ".($product_data->alt ? $product_data->alt : $product_data->image_name)."</td>";

			$table .= "<td style='padding:8px; border:1px solid #ddd;'>".$product_data->products_count."</td>";
			$table .= "<td style='padding:8px; border:1px solid #ddd;'>".$product_data->final_price_text."</td>";
			$table .= "</tr>";
		}
		$table .= "<tr>";
		$table .= "<td colspan='2' style='padding:8px; border:1px solid #ddd;'>".__("Payment method","bwge")."</td>";
		$table .= "<td style='padding:8px; border:1px solid #ddd;'>".$payment_method."</td>";
		$table .= "</tr>";

        if($total_tax){
            $table .= "<tr>";
            $table .= "<td colspan='2' style='padding:8px; border:1px solid #ddd;'>".__("Tax","bwge")."</td>";
            $table .= "<td style='padding:8px; border:1px solid #ddd;'>".$total_tax."</td>";
            $table .= "</tr>";
        }

        if($total_shipping){
            $table .= "<tr>";
            $table .= "<td colspan='2' style='padding:8px; border:1px solid #ddd;'>".__("Shipping","bwge")."</td>";
            $table .= "<td style='padding:8px; border:1px solid #ddd;'>".$total_shipping."</td>";
            $table .= "</tr>";
        }

		$table .= "<tr>";
		$table .= "<td colspan='2' style='padding:8px; border:1px solid #ddd;'><b>".__("Total","bwge")."</b></td>";
		$table .= "<td style='padding:8px; border:1px solid #ddd;'><b>".$total."</b></td>";
		$table .= "</tr>";

		$table .= "</table>";

		return $table;
	}

	private static function email_billing($order_row, $color){

		$table = "<table style='display:inline-table;margin-top:15px;margin-right:20px'>";
		$table .= "<tr>";
		$table .= "<td colspan='2'><b>".__("Billing data","bwge")."</b></td>";
		$table .= "</tr>";
		if($order_row->billing_data_name){
			$table .= "<tr>";
			$table .= "<td>".__("Name","bwge").":</td>";
			$table .= "<td><span >".$order_row->billing_data_name."</span></td>";
			$table .= "</tr>";
		}
		if($order_row->billing_data_email){
			$table .= "<tr>";
			$table .= "<td>".__("Email","bwge").":</td>";
			$table .= "<td><span >".$order_row->billing_data_email."</span></td>";
			$table .= "</tr>";
		}
		if($order_row->billing_data_country){
			$table .= "<tr>";
			$table .= "<td>".__("Country","bwge").":</td>";
			$table .= "<td><span >".$order_row->billing_data_country."</span></td>";
			$table .= "</tr>";
		}
		if($order_row->billing_data_city){
			$table .= "<tr>";
			$table .= "<td>".__("City","bwge").":</td>";
			$table .= "<td><span >".$order_row->billing_data_city."</span></td>";
			$table .= "</tr>";
		}
		if($order_row->billing_data_address){
			$table .= "<tr>";
			$table .= "<td>".__("Address","bwge").":</td>";
			$table .= "<td><span >".$order_row->billing_data_address."</span></td>";
			$table .= "</tr>";
		}
		if($order_row->billing_data_zip_code){
			$table .= "<tr>";
			$table .= "<td>".__("Zip code","bwge").":</td>";
			$table .= "<td><span >".$order_row->billing_data_zip_code."</span></td>";
			$table .= "</tr>";
		}
		$table .= "</table>";
		return $table;
	}

	private static function email_shipping($order_row, $color){

		$table = "<table style='display:inline-table;margin-top:15px;margin-right:20px'>";
		$table .= "<tr>";
		$table .= "<td colspan='2'><b>".__("Shipping data","bwge")."</b></td>";
		$table .= "</tr>";
		if($order_row->shipping_data_name){
			$table .= "<tr>";
			$table .= "<td>".__("Name","bwge").":</td>";
			$table .= "<td><span >".$order_row->shipping_data_name."</span></td>";
			$table .= "</tr>";
		}

		if($order_row->shipping_data_country){
			$table .= "<tr>";
			$table .= "<td>".__("Country","bwge").":</td>";
			$table .= "<td><span >".$order_row->shipping_data_country."</span></td>";
			$table .= "</tr>";
		}
		if($order_row->shipping_data_city){
			$table .= "<tr>";
			$table .= "<td>".__("City","bwge").":</td>";
			$table .= "<td><span >".$order_row->shipping_data_city."</span></td>";
			$table .= "</tr>";
		}
		if($order_row->shipping_data_address){
			$table .= "<tr>";
			$table .= "<td>".__("Address","bwge").":</td>";
			$table .= "<td><span >".$order_row->shipping_data_address."</span></td>";
			$table .= "</tr>";
		}
		if($order_row->shipping_data_zip_code){
			$table .= "<tr>";
			$table .= "<td>".__("Zip code","bwge").":</td>";
			$table .= "<td><span >".$order_row->shipping_data_zip_code."</span></td>";
			$table .= "</tr>";
		}
		$table .= "</table>";
		return $table;
	}

    private static function email_billing_shipping($order_row, $color, $order){
        $email_billing = self::email_billing($order_row, $color);
        $email_shipping = self::email_shipping($order_row, $color);

        $table = "<table>";
        if($order == "shipping"){
            $table = "<tr><td style='vertical-align:top;'>".$email_shipping ."</td><td style='vertical-align:top;'>".$email_billing."</td></tr>";
        }
        else{
            $table = "<tr><td style='vertical-align:top;'>".$email_billing ."</td><td style='vertical-align:top;'>".$email_shipping."</td></tr>";
        }
        $table .= "</table>";
        return $table;
    }

    ////////////////////////////////////////////////////////////////////////////////////////
    // Listeners                                                                          //
    ////////////////////////////////////////////////////////////////////////////////////////
}
