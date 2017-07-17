<?php

class BWGEControllerRates_bwge {
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
  public function __construct() {
  }
  ////////////////////////////////////////////////////////////////////////////////////////
  // Public Methods                                                                     //
  ////////////////////////////////////////////////////////////////////////////////////////
  public function execute() {
    $task = ((isset($_POST['task'])) ? esc_html($_POST['task']) : '');
    $id = ((isset($_POST['current_id'])) ? esc_html($_POST['current_id']) : 0);


    if($task != ''){
      if(!BWGELibrary::verify_nonce('rates_bwge')){
        die('Sorry, your nonce did not verify.');
      }
    }

    if (method_exists($this, $task)) {
      $this->$task($id);
    }
    else {
      $this->display();
    }
  }

  public function display() {
    require_once WD_BWGE_DIR . "/admin/models/BWGEModelRates_bwge.php";
    $model = new BWGEModelRates_bwge();

    require_once WD_BWGE_DIR . "/admin/views/BWGEViewRates_bwge.php";
    $view = new BWGEViewRates_bwge($model);
    $view->display();
  }

  public function delete($id) {
    global $wpdb;
    $image_id = $wpdb->get_var($wpdb->prepare('SELECT image_id FROM ' . $wpdb->prefix . 'bwge_image_rate WHERE id="%d"', $id));
    $query = $wpdb->prepare('DELETE FROM ' . $wpdb->prefix . 'bwge_image_rate WHERE id="%d"', $id);
    if ($wpdb->query($query)) {
      $rates = $wpdb->get_row($wpdb->prepare('SELECT AVG(`rate`) as `average`, COUNT(`rate`) as `rate_count` FROM ' . $wpdb->prefix . 'bwge_image_rate WHERE image_id="%d"', $image_id));
      $wpdb->update($wpdb->prefix . 'bwge_image', array('avg_rating' => $rates->average, 'rate_count' => $rates->rate_count), array('id' => $image_id));
      echo BWGELibrary::message('Item Succesfully Deleted.', 'updated');
    }
    else {
      echo BWGELibrary::message('Error. Please install plugin again.', 'error');
    }
    $this->display();
  }
  
  public function delete_all() {
    global $wpdb;
    $flag = FALSE;
    $ids_col = $wpdb->get_col('SELECT id FROM ' . $wpdb->prefix . 'bwge_image_rate');
    foreach ($ids_col as $id) {
      if (isset($_POST['check_' . $id])) {      
        $flag = TRUE;
        $image_id = $wpdb->get_var($wpdb->prepare('SELECT image_id FROM ' . $wpdb->prefix . 'bwge_image_rate WHERE id="%d"', $id));
        $wpdb->query($wpdb->prepare('DELETE FROM ' . $wpdb->prefix . 'bwge_image_rate WHERE id="%d"', $id));
        $rates = $wpdb->get_row($wpdb->prepare('SELECT AVG(`rate`) as `average`, COUNT(`rate`) as `rate_count` FROM ' . $wpdb->prefix . 'bwge_image_rate WHERE image_id="%d"', $image_id));
        $wpdb->update($wpdb->prefix . 'bwge_image', array('avg_rating' => $rates->average, 'rate_count' => $rates->rate_count), array('id' => $image_id));
      }
    }
    if ($flag) {
      echo BWGELibrary::message('Items Succesfully Deleted.', 'updated');
    }
    else {
      echo BWGELibrary::message('You must select at least one item.', 'error');
    }
    $this->display();
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