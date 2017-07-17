<?php

class BWGEModelReports_bwge extends BWGEModel{
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
	public function get_report_view_data(){

		$date_range = $this->get_date_range();	
		$row_report_data = $this->get_report_row($date_range->start_date, $date_range->end_date);
		
		$row_report_data->start_date = $date_range->start_date;
		$row_report_data->end_date = $date_range->end_date;
		$row_report_data->json_data = $this->get_reports_json_data();
		$row_report_data->currency = $this->get_currency($date_range->start_date, $date_range->end_date);
		
		return $row_report_data;
	}
	
	public function get_reports_data_array(){
	
		$date_range = $this->get_date_range();	
		$current_date = strtotime($date_range->start_date);
		
		$end_date = strtotime($date_range->end_date);
		$counter = 'day';
		$monthly = false;
		$date_format = "Y-m-d";
		
		if($date_range->number_of_months >12 || $date_range->tab_index == 'year'){
			$counter = 'month';
			$monthly = true;
			$date_format = "Y-m";
		}
		
		$row_end_date = $current_date;		
		if($monthly == true){
			$row_end_date = strtotime(date("Y-m-d",$current_date)." +1 month");
		}

		$reports_data_array = array();
		while($current_date <= $end_date){			
			$report_data = $this->get_report_row(date("Y-m-d",$current_date),date("Y-m-d",$row_end_date));
			$report_data->date = date($date_format,$current_date);
			$reports_data_array[] = $report_data;
			$current_date = strtotime(date("Y-m-d",$current_date). " +1 ".$counter);
			$row_end_date = strtotime(date("Y-m-d",$row_end_date). " +1 ".$counter);
		}
		
		return $reports_data_array;
	}

	public function get_date_range(){	
		$type = BWGEHelper::get('tab_index');
		switch($type){
			case "year":
				$start_date = date('Y-01-01');
				$end_date = date('Y-m-d');
			break;
			case "last_month":
				$start_date = date("Y-m-01",strtotime("-1 month"));
				$end_date = date("Y-m-t",strtotime("-1 month"));
			break;
			case "this_month":
				$start_date = date("Y-m-01");
				$end_date = date("Y-m-d");
			break;
			case "last_week":
				$start_date = date("Y-m-d",strtotime("-7 days"));
				$end_date = date("Y-m-d");
			break;
			case "custom":
				$start_date = BWGEHelper::get("start_date") ? BWGEHelper::get("start_date") : date('Y-m-d');
				$end_date = BWGEHelper::get("end_date") ? BWGEHelper::get("end_date") : date('Y-m-d');
			break;
			default:
				$start_date = date('Y-01-01');
				$end_date = date('Y-m-d');
			break;			
		}
		$number_of_days = strtotime($end_date) - strtotime($start_date);
		$number_of_days = ceil($number_of_days/(60*60*24));
		$number_of_months = ceil($number_of_days/30);
		
		$date_range = new Stdclass();
		$date_range->start_date = $start_date; 
		$date_range->end_date = $end_date; 
		$date_range->number_of_days = $number_of_days; 
		$date_range->number_of_months = $number_of_months; 
		$date_range->tab_index = $type == "" ? "year" : $type;
		
		return $date_range;

	}
	
	public function get_report_row($start_date, $end_date){
		global $wpdb;
		$where = array();

		if(isset($_POST["sold_products"]) && $_POST["sold_products"]=="prints_and_products"){

			$where[] = " pricelist_download_item_id = 0 ";
		}
		elseif(isset($_POST["sold_products"]) && $_POST["sold_products"]=="downloads") {

			$where[] = " pricelist_download_item_id !=0 ";

		}
		if(isset($_POST["image_id"]) && $_POST["image_id"]){
			
			$where[] = " image_id =".$_POST["image_id"];
		} 
				

		$where = count($where) ? "WHERE ". implode("AND", $where) : "";
		$row = $wpdb->get_row("SELECT SUM(T_ORDER_PRODUCTS.total_price) AS total_seals, SUM(T_ORDER_PRODUCTS.total_shipping_price) AS total_shipping_seals, SUM(T_ORDER_PRODUCTS.order_product_count) AS items_count, COUNT(*) AS orders_count FROM " . $wpdb->prefix . "bwge_orders  AS T_ORDERS
		LEFT JOIN ( SELECT order_id, SUM(price*products_count) AS total_price,SUM(shipping_price*products_count) AS total_shipping_price, SUM(products_count) AS order_product_count FROM " . $wpdb->prefix . "bwge_order_images ".$where."   GROUP BY order_id) AS T_ORDER_PRODUCTS ON T_ORDER_PRODUCTS.order_id = T_ORDERS.id WHERE DATE_FORMAT(T_ORDERS.checkout_date,'%Y-%m-%d')<='".$end_date."' AND DATE_FORMAT(T_ORDERS.checkout_date,'%Y-%m-%d')>='".$start_date."' ") ;

		//$row = $wpdb->get_row($query); 
		$row->items_count = $row->items_count ? $row->items_count : 0;
		$type = BWGEHelper::get('tab_index');	
		$date_range = $this->get_date_range();
		
		$number_of_days = $date_range->number_of_days; 
		$number_of_months = $date_range->number_of_months; 
		switch($type){
			case "year":
				$row->average_sales = ($number_of_months != 0) ? $row->total_seals/$number_of_months : $row->total_seals;
				$row->average_type = "monthly";
			break;
			case "last_month":
			case "last_week":	
			case "this_month":			
				$row->average_sales = ($number_of_days != 0) ? $row->total_seals/$number_of_days :$row->total_seals ;
				$row->average_type = "daily";
			break;		
			case "custom":				
				if($number_of_months > 12){
					$row->average_sales = $row->total_seals/$number_of_months;
					$row->average_type = "monthly";
				}
				else{
					$row->average_sales = ($number_of_days != 0) ? $row->total_seals/$number_of_days : $row->total_seals;
					$row->average_type = "daily";
			
				}		
			break;
			default:													
				$row->average_sales = ($number_of_months != 0) ? $row->total_seals/$number_of_months : $row->total_seals;
				$row->average_type = "monthly";
			break;			
		}
		return $row;
	
	}
	public function get_images(){
		global $wpdb;
		$images = $wpdb->get_results("SELECT * FROM " . $wpdb->prefix . "bwge_image");
		return $images;

	}
	
	 public function get_currency($start_date, $end_date){
		global $wpdb;
		$options = $this->get_options();
		$currencies= $wpdb->get_results("SELECT currency, COUNT(*) FROM " . $wpdb->prefix . "bwge_orders  WHERE DATE_FORMAT(checkout_date,'%Y-%m-%d')<='".$end_date."' AND DATE_FORMAT(checkout_date,'%Y-%m-%d')>='".$start_date."' GROUP BY currency ");
		

		if(count($currencies) > 1){
			$currency_code = NUll;
		}
		else{		
			$currency_code = $options->currency;			 
		}
		
		return $currency_code;
		
	} 	
    ////////////////////////////////////////////////////////////////////////////////////////
    // Getters & Setters                                                                  //
    ////////////////////////////////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////////////////////////////
    // Private Methods                                                                    //
    ////////////////////////////////////////////////////////////////////////////////////////
	private function get_reports_json_data(){

		$reports_data_array = $this->get_reports_data_array();
		$date_range = $this->get_date_range();

		$row = $this->get_report_row($date_range->start_date, $date_range->end_date);

		$reports_chart_data = array();

		$reports_chart_data['total_seals'] = array();
		$reports_chart_data['total_shipping_seals'] = array();
		$reports_chart_data['orders_count'] = array();
		$reports_chart_data['items_count'] = array();
		foreach($reports_data_array as $key => $report_data){
			$reports_chart_data['total_seals'][] = array(strtotime( date( 'Ymd', strtotime( $report_data->date ) ) ) . '000',$report_data->total_seals ? (float)$report_data->total_seals : 0);
			$reports_chart_data['total_shipping_seals'][] = array(strtotime( date( 'Ymd', strtotime( $report_data->date ) ) ) . '000',$report_data->total_shipping_seals ? (float) $report_data->total_shipping_seals : 0);
			$reports_chart_data['orders_count'][] = array(strtotime( date( 'Ymd', strtotime( $report_data->date ) ) ) . '000',$report_data->orders_count ? (int)$report_data->orders_count : 0);
			$reports_chart_data['items_count'][] = array(strtotime( date( 'Ymd', strtotime( $report_data->date ) ) ) . '000',$report_data->items_count ? (int)$report_data->items_count : 0);
		}
		
		$reports_chart_data['start_date'] = strtotime( date( 'Ymd', strtotime( $date_range->start_date ) ) ) . '000';
		$reports_chart_data['end_date'] = strtotime( date( 'Ymd', strtotime( $date_range->end_date ) ) ) . '000';
		$reports_chart_data['average_sales'] = (float)$row->average_sales;
		
		if($row->average_type == "monthly"){		
			$reports_chart_data['barwidth'] = 60 * 60 * 24 * 7 * 4 * 1000;
		}
		else{			
			$reports_chart_data['barwidth'] = 60 * 60 * 24 * 1000;
		}

		
		$reports_chart_data_json = json_encode($reports_chart_data);
		//var_dump($reports_chart_data_json);
		return $reports_chart_data_json;
	}
	

	////////////////////////////////////////////////////////////////////////////////////////
	// Listeners                                                                          //
	////////////////////////////////////////////////////////////////////////////////////////
}