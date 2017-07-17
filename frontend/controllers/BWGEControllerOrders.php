<?php

class BWGEControllerOrders extends BWGEControllerFrontend{
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
	public function __construct($params = ""){
		parent::__construct($params);
		$this->check_if_file_exists();

	}
	////////////////////////////////////////////////////////////////////////////////////////
	// Public Methods                                                                     //
	////////////////////////////////////////////////////////////////////////////////////////
	public function display_order(){

		$view = $this->view;
		$view->display_order();
	}
	public function download_file_from_url(){
		global $wpdb;

		$order_id = (int)BWGEHelper::get("order_id");
        if(!$order_id ) {
            echo "<h2>". __("Invalid request","bwge")."</h2>";
        }
        else{
            $order = $wpdb->get_row($wpdb->prepare('SELECT checkout_date, status, payment_method,rand_id FROM ' . $wpdb->prefix . 'bwge_orders WHERE id="%d" ',$order_id));
            if($order){
                $today = strtotime(date("Y-m-d H:i:s"));
                $order_checkout_date = strtotime($order->checkout_date);
                $day_diff = ($today - $order_checkout_date)/(3600*24);
                $model_orders = BWGEHelper::get_model("orders",true,$this->params) ;
                $options = $model_orders->get_options();

                if(BWGEHelper::get("key") != md5('268413990'.$order_id.$order->rand_id)) {
                    echo "<h2>". __("Invalid request","bwge")."</h2>";
                }
                elseif($options->digital_download_expiry_days != "" && ($day_diff > $options->digital_download_expiry_days && !is_super_admin())){
                    echo "<h2>". __("Download link expired ","bwge")."</h2>";
                }
                elseif(!is_super_admin() && $order->status != "confirmed" ){
                        _e("Order status is not confirmed.","bwge");
                }
                else{
                    $image_order_rows = $wpdb->get_results($wpdb->prepare('SELECT id , image_name, item_longest_dimension FROM ' . $wpdb->prefix . 'bwge_order_images WHERE order_id="%d" ',$order_id));

                    echo "<h2 class='bwge_download_files'><strong>".__('Download files', 'bwge')."</strong></h2>";
                    foreach($image_order_rows  as $row ){
                        if($row->item_longest_dimension){
                            echo '<a href="'.add_query_arg(array('action' => 'bwge_download_file','controller' => 'orders','task' => 'download_file', 'order_image_id' =>$row->id ) , admin_url('admin-ajax.php')).'">'.$row->image_name." (".$row->item_longest_dimension."px)".'</a><br>';
                        }
                    }
                }
            }
            else{
                echo "<h2>". __("Invalid request","bwge")."</h2>";
            }
        }
	}
	public function download_file(){
		global $wpdb;
		$order_image_id = (int)BWGEHelper::get("order_image_id");

        if(!$order_image_id){
            $errors = "error";
            echo  $errors;
            die();
        }
        $user = get_current_user_id();


        $order_image_row = $wpdb->get_row($wpdb->prepare('SELECT * FROM ' . $wpdb->prefix . 'bwge_order_images WHERE id="%d" ',$order_image_id));
        if($order_image_row && ($user == $order_image_row->user_id || is_super_admin()) && $order_image_row->filename && file_exists(WD_BWGE_DIR."/files/".$order_image_row->filename)){

            $image_name = $wpdb->get_var($wpdb->prepare("SELECT image_url FROM ". $wpdb->prefix . "bwge_image WHERE id='%d'", $order_image_row->image_id));

            $file_path = WD_BWGE_DIR."/files/".$order_image_row->filename;

            $handle = fopen($file_path, "r");
            $contents = fread($handle, filesize($file_path));
            fclose($handle);


            header("Content-type: application/octet-stream");
            header('Content-Disposition: attachment; filename="'.$order_image_row->item_longest_dimension.'px'. preg_replace('/^.+[\\\\\\/]/', '',$image_name).'"');
            header('Content-Transfer-Encoding: binary');
            header('Expires: 0');
            header("Cache-Control: public");
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . filesize($file_path));

            echo $contents;
            die();
        }
        else{
            $errors = "error";
            echo  $errors;
            die();
        }

	}
	////////////////////////////////////////////////////////////////////////////////////////
	// Getters & Setters                                                                  //
	////////////////////////////////////////////////////////////////////////////////////////
	////////////////////////////////////////////////////////////////////////////////////////
	// Private Methods                                                                    //
	////////////////////////////////////////////////////////////////////////////////////////
	private function check_if_file_exists(){
		global $wpdb, $WD_BWGE_UPLOAD_DIR;
		$model_orders = BWGEHelper::get_model("orders",true,$this->params) ;
		$model_checkout = BWGEHelper::get_model("checkout",true,$this->params) ;
		$orders = $model_orders->get_orders();
   
		foreach($orders as $order){
           
			foreach($order->order_images as $order_row){
				if(!file_exists( WD_BWGE_DIR."/files/".$order_row->filename) && $order_row->pricelist_download_item_id != 0){
					$thumb_url = $wpdb->get_var($wpdb->prepare("SELECT thumb_url FROM ". $wpdb->prefix . "bwge_image WHERE id='%d'", $order_row->image_id));
                    
					$thumb_url = site_url()."/".$WD_BWGE_UPLOAD_DIR.$thumb_url;
					$filename = $model_checkout->create_downloadable_items($order_row->image_id, $thumb_url, $order_row->item_longest_dimension);
                    $data = array();
                    $data_format = array("%s");
                    $where = array("id"=>$order_row->id);
                    $where_format = array('%d');
                    $data["filename"] = $filename["file_hush_name"];

                    $wpdb->update( $wpdb->prefix . "bwge_order_images", $data, $where, $data_format, $where_format );
				}
			}
		}
	}
	protected function display() {
		$view = $this->view;
		$user = get_current_user_id();
		if($user == 0){
			echo  __('Login please ', 'bwge');
		}
		else{
			$view->display();
		}
	}
	////////////////////////////////////////////////////////////////////////////////////////
	// Listeners                                                                          //
	////////////////////////////////////////////////////////////////////////////////////////
}
