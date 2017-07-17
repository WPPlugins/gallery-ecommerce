<?php

class BWGEModelParameters_bwge extends BWGEModel{
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
		$row->default_values_array = $row->default_values ? json_decode(htmlspecialchars_decode(stripslashes($row->default_values))) : array();
		if(!$id){
			$row->published = 1;
		}

		return $row;
	}

	public function get_rows($publshed = 0) {
		global $wpdb;
		$where = array();
		if(isset($_POST['search_value']) && esc_html(stripslashes($_POST['search_value'])) != '') {
			$where[] = ' title LIKE "%' . esc_html(stripslashes($_POST['search_value'])) . '%"' ;
		}
		if($publshed == 1){
			$where[] = " published = '1'";
		}

		$where = count($where) > 0 ? " WHERE ". implode("AND", $where) : "";
	
		$asc_or_desc = ((isset($_POST['asc_or_desc'])) ? esc_html(stripslashes($_POST['asc_or_desc'])) : 'asc');
		$asc_or_desc = ($asc_or_desc != 'asc') ? 'desc' : 'asc';
		$order_by = ' ORDER BY ' . ((isset($_POST['order_by']) && esc_html(stripslashes($_POST['order_by'])) != '') ? esc_html(stripslashes($_POST['order_by'])) : 'id') . ' ' . $asc_or_desc;
		if (isset($_POST['page_number']) && $_POST['page_number']) {
		  $limit = ((int) $_POST['page_number'] - 1) * $this->per_page;
		}
		else {
		  $limit = 0;
		}
		$query = "SELECT * FROM " . $wpdb->prefix . "bwge_parameters " . $where . $order_by . " LIMIT " . $limit . ",".$this->per_page;	;		
		$rows = $wpdb->get_results($query);
		return $rows;	
	}
	
	public function page_nav() {
		global $wpdb;
		$where = ((isset($_POST['search_value']) && (esc_html(stripslashes($_POST['search_value'])) != '')) ? 'WHERE title LIKE "%' . esc_html(stripslashes($_POST['search_value'])) . '%"'  : '');
		$query = "SELECT COUNT(*) FROM " . $wpdb->prefix . "bwge_parameters " . $where;
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
	public function get_lists(){
		$list = array();
		$parameter_types = array();
		$parameter_types[0] = "-Select-";
		$parameter_types[1] = "Single value";
		$parameter_types[2] = "Input";
		$parameter_types[3] = "Textarea";
		$parameter_types[4] = "Select";
		$parameter_types[5] = "Radio";
		$parameter_types[6] = "Checkbox";
		
		$list["parameter_types"] = $parameter_types;
		
		return $list;
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