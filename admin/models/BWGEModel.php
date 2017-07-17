<?php

class BWGEModel {
	////////////////////////////////////////////////////////////////////////////////////////
	// Events                                                                             //
	////////////////////////////////////////////////////////////////////////////////////////
	////////////////////////////////////////////////////////////////////////////////////////
	// Constants                                                                          //
	////////////////////////////////////////////////////////////////////////////////////////
	////////////////////////////////////////////////////////////////////////////////////////
	// Variables                                                                          //
	////////////////////////////////////////////////////////////////////////////////////////
	protected $per_page = 10;

	////////////////////////////////////////////////////////////////////////////////////////
	// Constructor & Destructor                                                           //
	////////////////////////////////////////////////////////////////////////////////////////
	public function __construct() {
		$user = get_current_user_id();
		$screen = get_current_screen();
		if($screen){
			$option = $screen->get_option('per_page', 'option');
			
			$this->per_page = get_user_meta($user, $option, true);
			
			if ( empty ( $this->per_page) || $this->per_page < 1 ) {
			  $this->per_page = $screen->get_option( 'per_page', 'default' );

			}
		}
		
	}   
	////////////////////////////////////////////////////////////////////////////////////////
	// Public Methods                                                                     //
	////////////////////////////////////////////////////////////////////////////////////////
	public function per_page(){
		return $this->per_page;

	}
	
	public function get_row_by_id($id , $table_name = ""){
		global $wpdb;
		if($table_name == ""){
			$page = BWGEHelper::get('page') ? BWGEHelper::get('page') : "ecommerceoptions_bwge";
			
			switch($page){
				case "options_bwge" :
					$table_name = $wpdb->prefix . "bwge_ecommerceoptions";
					break;
				case "pricelists_bwge" :
					$table_name = $wpdb->prefix . "bwge_pricelists";
					break;	
				case "paymentsystems_bwge" :
					$table_name = $wpdb->prefix . "bwge_payment_systems";
					break;	
				case "orders_bwge" :
					$table_name = $wpdb->prefix . "bwge_orders";
					break;
				case "parameters_bwge" :
					$table_name = $wpdb->prefix . "bwge_parameters";
					break;
				case "licenses_bwge" :
					$table_name = $wpdb->prefix . "wdpg_ecommerce_licenses";
					break;					
			}
		}

		if($id){
			$query = "SELECT * FROM " . $table_name ." WHERE id='".$id."'";			
			$row = $wpdb->get_row($query);
		}
		else{					
			$columns = $this->get_columns($table_name);			
			$row = new stdClass();
			foreach($columns as $column){
				$row->$column = "";
			}
			
		}

		return $row;
	}
	
	public function get_columns($table_name){
		global $wpdb;
		$query = "SHOW COLUMNS  FROM " . $table_name ;			
		$columns = $wpdb->get_col( $query , 0 );
		return 	$columns;	
	}
	
	public function  get_options(){
		global $wpdb;
		//options	
		$options_rows = $wpdb->get_results('SELECT * FROM ' . $wpdb->prefix . 'bwge_ecommerceoptions ');
		$options = new stdClass();
		foreach ($options_rows as $row) {
			$name = $row->name;
			$value = $row->value;
			$options->$name = $value;
		}

		return 	$options;
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