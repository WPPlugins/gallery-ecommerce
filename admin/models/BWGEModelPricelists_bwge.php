<?php

class BWGEModelPricelists_bwge extends BWGEModel{
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

	public function get_row($id){
		global $wpdb;
		$id = (int)$id;
		$row = parent::get_row_by_id($id);
		
		if($id == 0){
			$row->sections  = array();
			$row->digital_itmes = array();
			$row->parameters = array();
			$row->published = 1;
		}
		else{
			// get sections
			$row->sections = $row->sections ? explode(",", $row->sections) : array();
		
			// get digital items
			$query = "SELECT * FROM " . $wpdb->prefix . "bwge_pricelist_items WHERE pricelist_id='".$id."'";				
			$row->digital_itmes = $wpdb->get_results($query);

			// get parameters
			
			$query = "SELECT T_PRICELIST_PARAMETERS.*, T_PARAMETERS.title,  T_PARAMETERS.type  FROM " . $wpdb->prefix . "bwge_pricelist_parameters AS T_PRICELIST_PARAMETERS LEFT JOIN " . $wpdb->prefix . "bwge_parameters
			AS T_PARAMETERS ON T_PRICELIST_PARAMETERS.parameter_id = T_PARAMETERS.id WHERE pricelist_id='".$id."' ORDER BY T_PRICELIST_PARAMETERS.id";				
			$parameter_rows = $wpdb->get_results($query);
			
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
			
			$row->parameters = $parameters_map;
				
		}
		//pages
		$args = array(
		'sort_order' => 'ASC',
		'sort_column' => 'post_title',
		'hierarchical' => 1,
		'exclude' => '',
		'include' => '',
		'meta_key' => '',
		'meta_value' => '',
		'authors' => '',
		'child_of' => 0,
		'parent' => -1,
		'exclude_tree' => '',
		'number' => '',
		'offset' => 0,
		'post_type' => 'page',
		'post_status' => 'publish'
		);
		$pages = array();
		$pages_array_of_objects = get_pages($args); 
		
		foreach($pages_array_of_objects as $page){
			$pages[$page->ID] = $page->post_title;
		}
				
		$row->licenses = $pages;
		return $row;
	}

	public function get_rows() {		
		global $wpdb;
		
		$where = ((isset($_POST['search_value']) && (esc_html(stripslashes($_POST['search_value'])) != '')) ? 'WHERE title LIKE "%' . esc_html(stripslashes($_POST['search_value'])) . '%"'  : '');
		$asc_or_desc = ((isset($_POST['asc_or_desc'])) ? esc_html(stripslashes($_POST['asc_or_desc'])) : 'asc');
		$asc_or_desc = ($asc_or_desc != 'asc') ? 'desc' : 'asc';
		$order_by = ' ORDER BY ' . ((isset($_POST['order_by']) && esc_html(stripslashes($_POST['order_by'])) != '') ? esc_html(stripslashes($_POST['order_by'])) : 'id') . ' ' . $asc_or_desc;
		if (isset($_POST['page_number']) && $_POST['page_number']) {
		  $limit = ((int) $_POST['page_number'] - 1) * $this->per_page;
		}
		else {
		  $limit = 0;
		}
		
		$model_options = BWGEHelper::get_model("ecommerceoptions");
		$options = $model_options->get_options();
		$query = "SELECT * FROM " . $wpdb->prefix . "bwge_pricelists ". $where . $order_by . " LIMIT " . $limit . ",".$this->per_page ;		
		$rows = $wpdb->get_results($query);
		
		foreach($rows as $row){
			$row->price_text = $row->price ? $options->currency_sign.number_format($row->price,2) : "";
		}
		return $rows;	
	}
	public function page_nav() {
		global $wpdb;
		$where = ((isset($_POST['search_value']) && (esc_html(stripslashes($_POST['search_value'])) != '')) ? 'WHERE title LIKE "%' . esc_html(stripslashes($_POST['search_value'])) . '%"'  : '');
		$query = "SELECT COUNT(*) FROM " . $wpdb->prefix . "bwge_pricelists " . $where;
		$total = $wpdb->get_var($query);
		$page_nav['total'] = $total;
		if (isset($_POST['page_number']) && $_POST['page_number']) {
			$limit = ((int) $_POST['page_number'] - 1) * $this->per_page;
		}
		else {
			$limit = 0;
		}
		$page_nav['limit'] = (int) ($limit / $this->per_page + 1);
		return $page_nav;
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