<?php
class BWGEControllerOrders_bwge extends BWGEController{
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
    public function resend_email(){
        $this->send_order_details_email();
        BWGEHelper::message(__("Email Succesfully Sent.","bwge_back"),'updated');
		$this->view->edit(BWGEHelper::get('id'));		
    }
	////////////////////////////////////////////////////////////////////////////////////////
	// Getters & Setters                                                                  //
	////////////////////////////////////////////////////////////////////////////////////////
	////////////////////////////////////////////////////////////////////////////////////////
	// Private Methods                                                                    //
	////////////////////////////////////////////////////////////////////////////////////////
	protected function save(){
		$this->store_data();
		
		BWGEHelper::message(__("Item Succesfully Saved.","bwge_back"),'updated');
		$this->display();			
	}

	protected function apply(){
		$id = $this->store_data();
		//BWGEHelper::bwge_redirect("admin.php?page=".$this->page.'&task=edit&id='. BWGEHelper::get("id"));	
		BWGEHelper::message(__("Item Succesfully Saved.","bwge_back"),'updated');
		$this->view->edit($id);			
	}
    protected function remove(){     
    	global $wpdb;
		$ids = isset($_POST["ids"]) ? $_POST["ids"] : array();
        foreach($ids as $id){	
            $where = array("order_id" => $id);
            $where_format = array('%d');
            $wpdb->delete(  $wpdb->prefix . "bwge_order_images", $where, $where_format);
        }
        parent::remove();
    }
	private function store_data(){
		global $wpdb;
		$data = array();
		$data["status"] = BWGEHelper::get("status");
		$format = array('%s');
		
		$where = array("id"=>BWGEHelper::get("id"));
		$where_format = array('%d');
		
		$wpdb->update( $wpdb->prefix . "bwge_orders", $data, $where, $format, $where_format );
        
        if(BWGEHelper::get("is_email_sent") == 0){
            $this->send_order_details_email();
        }
		return BWGEHelper::get("id");
						
	}

    private function send_order_details_email(){
        global $wpdb;
        $order_id = BWGEHelper::get('id');
        
		$model = BWGEHelper::get_model("orders");
		$options = $model->get_options();
        $order_row =  $wpdb->get_row($wpdb->prepare('SELECT * FROM ' . $wpdb->prefix . 'bwge_orders WHERE id="%d" ',$order_id));
        
        $model_checkout = BWGEHelper::get_model("checkout", true);
		$rand_ids = $order_row->rand_id ? array($order_row->rand_id) : false;
        $products_data = $model_checkout->get_order_product_rows($order_id, $order_row->user_id, $rand_ids);  
        $model_order = BWGEHelper::get_model("orders", true);        
        $shipping = $model_order->get_order_shipping($order_id); 
		BWGECheckoutEmail::send_checkout_email($options, $products_data, $order_row, $order_row->user_id, 0, $shipping, false);   
    
    }

	////////////////////////////////////////////////////////////////////////////////////////
	// Listeners                                                                          //
	////////////////////////////////////////////////////////////////////////////////////////
}

?>