<?php

class BWGEController {
	////////////////////////////////////////////////////////////////////////////////////////
	// Events                                                                             //
	////////////////////////////////////////////////////////////////////////////////////////
	////////////////////////////////////////////////////////////////////////////////////////
	// Constants                                                                          //
	////////////////////////////////////////////////////////////////////////////////////////
	////////////////////////////////////////////////////////////////////////////////////////
	// Variables                                                                          //
	////////////////////////////////////////////////////////////////////////////////////////
	public $page;
	public $task;
	public $model;
	public $view;
	////////////////////////////////////////////////////////////////////////////////////////
	// Constructor & Destructor                                                           //
	////////////////////////////////////////////////////////////////////////////////////////
	public function __construct() {

		$page = BWGEHelper::get('page') ? BWGEHelper::get('page') : "options_bwge";
		
		$this->page = $page;

		$task = BWGEHelper::get('task') ? BWGEHelper::get('task') : "display";
		$this->task = $task; 

		$model_class = 'BWGEModel' . ucfirst($this->page);
		$view_class = 'BWGEView' . ucfirst($this->page);

		require_once WD_BWGE_DIR . "/admin/models/".$model_class.".php";
		$this->model = new $model_class();

		require_once WD_BWGE_DIR . "/admin/views/".$view_class.".php";
		$this->view = new $view_class($this->model);

	}
	////////////////////////////////////////////////////////////////////////////////////////
	// Public Methods                                                                     //
	////////////////////////////////////////////////////////////////////////////////////////
	public function execute() {
        
		$task = $this->task; 
        if(method_exists($this,$task)){
            if($task != "display" && $task != "edit" ){                          
                check_admin_referer('nonce_bwge', 'nonce_bwge');
            }
            $this->$task();
        }
        else{
            _e("Not found","bwge_back");
        }
	}

	////////////////////////////////////////////////////////////////////////////////////////
	// Getters & Setters                                                                  //
	////////////////////////////////////////////////////////////////////////////////////////
	////////////////////////////////////////////////////////////////////////////////////////
	// Private Methods                                                                    //
	////////////////////////////////////////////////////////////////////////////////////////
	protected function display() {
		$view = $this->view;
		$view->display();	
	}

	protected function edit(){
		$view = $this->view;
		$id = BWGEHelper::get('id');
		$view->edit($id); 
	}
	protected function explore(){
		$view = $this->view;
		$view->explore(); 
	}	

	protected function save(){
		
	}
	
	protected function apply(){

	}
	

	protected function remove(){
		global $wpdb;
		$ids = isset($_POST["ids"]) ? $_POST["ids"] : array();
	
		if(empty($ids) === false){
			switch($this->page){
				case "pricelists_bwge" :
					$table_name = $wpdb->prefix . "bwge_pricelists";
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
			foreach($ids as $id){	
				$where = array("id" => $id);
				$where_format = array('%d');
				$wpdb->delete(  $table_name, $where, $where_format);
			}
			BWGEHelper::message(__("Item(s) Succesfully Removed.","bwge_back"),'updated');			
		}
		else{
			BWGEHelper::message(__("You must select at least one item.","bwge_back"),'error');
		}
		$this->display();		

	}
	
	
	protected function cancel(){
		BWGEHelper::bwge_redirect("admin.php?page=".$this->page);		
	}
	
	protected function publish(){
		global $wpdb;
		if(isset($_POST["ids"])){
			$ids = $_POST["ids"] ;			
		}
		elseif(isset($_POST["current_id"])){
			$ids = array($_POST["current_id"]) ;
		}
		else{
			$ids = array();
		}
		if(empty($ids) === false && isset($_POST["publish_unpublish"])){
			$data = array("published" => $_POST["publish_unpublish"]);
			$where_format = array('%d');
			$format = array('%d');
									
			switch($this->page){
				case "pricelists_bwge" :
					$table_name = $wpdb->prefix . "bwge_pricelists";
					break;	
				case "paymentsystems_bwge" :
					$table_name = $wpdb->prefix . "bwge_payment_systems";
					break;	
					
				case "parameters_bwge" :
					$table_name = $wpdb->prefix . "bwge_parameters";
					break;	
					
				case "licenses_bwge" :
					$table_name = $wpdb->prefix . "wdpg_ecommerce_licenses";
					break;						
			}
			
			foreach ($ids as $id){
				$where = array("id"=>$id);			
				$wpdb->update($table_name, $data, $where, $format, $where_format );
				
			}
		}
		//BWGEHelper::bwge_redirect("admin.php?page=".$this->page);
		$publish_unpublish = $_POST["publish_unpublish"] == 1 ? __("Published","bwge_back") : __("Unpublished","bwge_back");
		BWGEHelper::message(__("Item(s) Succesfully ","bwge_back").$publish_unpublish.".",'updated');
		$this->display();		
	}

	
	////////////////////////////////////////////////////////////////////////////////////////
	// Listeners                                                                          //
	////////////////////////////////////////////////////////////////////////////////////////
}