<?php

class BWGEControllerPricelists_bwge extends BWGEController {
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
	////////////////////////////////////////////////////////////////////////////////////////
	// Getters & Setters                                                                  //
	////////////////////////////////////////////////////////////////////////////////////////
	////////////////////////////////////////////////////////////////////////////////////////
	// Private Methods                                                                    //
	////////////////////////////////////////////////////////////////////////////////////////
	protected function save(){	

		$this->remove_pricelist_items(BWGEHelper::get("id"));
		$this->remove_pricelist_parameters(BWGEHelper::get("id"));
		$id = $this->store_pricelist_data();
		$this->store_pricelist_items_data($id);
		$this->store_pricelist_parameters_data($id);
		//BWGEHelper::bwge_redirect( "admin.php?page=".$this->page);
		BWGEHelper::message(__("Item Succesfully Saved.","bwge_back"),'updated');
		$this->display();		
		
	}

	protected function apply(){
		$this->remove_pricelist_items(BWGEHelper::get("id"));
		$this->remove_pricelist_parameters(BWGEHelper::get("id"));
		$id = $this->store_pricelist_data();
		$this->store_pricelist_items_data($id);
		$this->store_pricelist_parameters_data($id);
		//BWGEHelper::bwge_redirect( "admin.php?page=".$this->page.'&task=edit&id='.$id);
		BWGEHelper::message(__("Item Succesfully Saved.","bwge_back"),'updated');
		
		$this->view->edit($id);			
	}

	protected function remove(){
		global $wpdb;
		$ids = isset($_POST["ids"]) ? $_POST["ids"] : array();
		if(empty($ids) === true){
			BWGEHelper::message(__("You must select at least one item.","bwge_back"),'error');
			$this->display();	
		}
		else{
			$this->remove_pricelist_items($ids);
			$this->remove_pricelist_parameters($ids);
			parent::remove();			
		}
		//BWGEHelper::bwge_redirect( "admin.php?page=".$this->page);
	
	}	

	private function store_pricelist_data(){
		$sections = isset($_POST["sections"]) ? implode(",", $_POST["sections"]) : "";
		global $wpdb;
		$data = array();
		$data["id"] = BWGEHelper::get("id");
		$data["title"] = sanitize_text_field(stripslashes(BWGEHelper::get("title")));
		$data["sections"] = esc_html($sections );
		$data["manual_description"] = sanitize_text_field(stripslashes(BWGEHelper::get("manual_description")));
		$data["price"] = esc_html(BWGEHelper::get("price"));
		$data["manual_title"] = sanitize_text_field(stripslashes(BWGEHelper::get("manual_title")));
		$data["shipping_price"] = esc_html(BWGEHelper::get("shipping_price"));
		$data["shipping_type"] = esc_html(BWGEHelper::get("shipping_type"));
		$data["enable_international_shipping"] = esc_html(BWGEHelper::get("enable_international_shipping"));
		$data["international_shipping_price"] = esc_html(BWGEHelper::get("international_shipping_price"));
		$data["international_shipping_type"] = esc_html(BWGEHelper::get("international_shipping_type"));
		$data["tax_rate"] = esc_html(BWGEHelper::get("tax_rate"));	
		$data["display_license"] = esc_html(BWGEHelper::get("display_license"));	
		$data["license_id"] = esc_html(BWGEHelper::get("license_id"));	
		
		$format = array('%d','%s','%s','%s','%s','%s', '%s','%s','%s','%s','%s','%s','%d','%d','%d');
		
		if( BWGEHelper::get("id") == NULL){
			$data["published"] = 1;			
			$wpdb->insert( $wpdb->prefix . "bwge_pricelists", $data, $format );
			return $wpdb->get_var("SELECT MAX(id) FROM ". $wpdb->prefix . "bwge_pricelists");
		}
		else{
			$data["published"] = esc_html(BWGEHelper::get("published"));	
			$where = array("id"=>BWGEHelper::get("id"));
			$where_format = array('%d');
			$wpdb->update( $wpdb->prefix . "bwge_pricelists", $data, $where, $format, $where_format );
			return BWGEHelper::get("id");
		}

	}
	private function store_pricelist_items_data($pricelist_id = 0){
		global $wpdb;

		$download_items = htmlspecialchars_decode(stripslashes(BWGEHelper::get("download_items")));	
		$download_items = json_decode($download_items);

		foreach($download_items as $download_item){
			$data = array();
			$data["id"] = "";
			$data["pricelist_id"] = esc_html(BWGEHelper::get("id",$pricelist_id));
			$data["item_name"] = sanitize_text_field(stripslashes($download_item->name));
			$data["item_price"] = esc_html($download_item->price);
			$data["item_longest_dimension"] = esc_html($download_item->longest_dimension);
			
			$format = array('%d','%d','%s','%s','%s');
			$wpdb->insert( $wpdb->prefix . "bwge_pricelist_items", $data, $format );
		}		

	}
	
	private function store_pricelist_parameters_data($pricelist_id = 0){
		global $wpdb;
		$parameters = htmlspecialchars_decode(stripslashes(BWGEHelper::get("parameters")));	
		$parameters = json_decode($parameters);
		
		foreach($parameters as $parameter){
			$data = array();
			$data["id"] = "";
			$data["pricelist_id"] = esc_html($pricelist_id);
			$data["parameter_id"] = esc_html($parameter->id);
			$data["parameter_value"] = esc_html($parameter->value);
			$data["parameter_value_price"] = esc_html($parameter->price) ? esc_html($parameter->price) : 0;
			$data["parameter_value_price_sign"] = esc_html($parameter->price_sign);

			$format = array('%d','%d','%d','%s','%s','%s');
			$wpdb->insert( $wpdb->prefix . "bwge_pricelist_parameters", $data, $format );
		}	
	}
	
	private function remove_pricelist_items($ids){
		global $wpdb;
		if(is_array($ids) !== true){
			$ids = array($ids);
		}
		foreach($ids as $id){
			$where = array("pricelist_id"=>$id);
			$where_format = array('%d');
			$wpdb->delete(  $wpdb->prefix . "bwge_pricelist_items", $where, $where_format);
		}
	}
	
	private function remove_pricelist_parameters($ids){
		global $wpdb;
		if(is_array($ids) !== true){
			$ids = array($ids);
		}
		foreach($ids as $id){
			$where = array("pricelist_id"=>$id);
			$where_format = array('%d');
			$wpdb->delete(  $wpdb->prefix . "bwge_pricelist_parameters", $where, $where_format);
		}
	}	
	////////////////////////////////////////////////////////////////////////////////////////
	// Listeners                                                                          //
	////////////////////////////////////////////////////////////////////////////////////////
}