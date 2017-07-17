<?php

class BWGEControllerComments_bwge {
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
      if(!BWGELibrary::verify_nonce('comments_bwge')){
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
    require_once WD_BWGE_DIR . "/admin/models/BWGEModelComments_bwge.php";
    $model = new BWGEModelComments_bwge();

    require_once WD_BWGE_DIR . "/admin/views/BWGEViewComments_bwge.php";
    $view = new BWGEViewComments_bwge($model);
    $view->display();
  }

  public function delete($id) {
    global $wpdb;
    $query = $wpdb->prepare('DELETE FROM ' . $wpdb->prefix . 'bwge_image_comment WHERE id="%d"', $id);
       
    if ($wpdb->query($query)) {
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
    $tag_ids_col = $wpdb->get_col('SELECT id FROM ' . $wpdb->prefix . 'bwge_image_comment');
    foreach ($tag_ids_col as $tag_id) {
      if (isset($_POST['check_' . $tag_id])) {      
        $flag = TRUE;
        $query = $wpdb->prepare('DELETE FROM ' . $wpdb->prefix . 'bwge_image_comment WHERE id="%d"', $tag_id);
        $wpdb->query($query);
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

  public function publish($id) {
    global $wpdb;
    $save = $wpdb->update($wpdb->prefix . 'bwge_image_comment', array('published' => 1), array('id' => $id));
    if ($save !== FALSE) {
      echo BWGELibrary::message('Item Succesfully Published.', 'updated');
    }
    else {
      echo BWGELibrary::message('Error. Please install plugin again.', 'error');
    }
    $this->display();
  }
  
  public function publish_all() {
    global $wpdb;
    $flag = FALSE;
    $tag_ids_col = $wpdb->get_col('SELECT id FROM ' . $wpdb->prefix . 'bwge_image_comment');
    foreach ($tag_ids_col as $tag_id) {
      if (isset($_POST['check_' . $tag_id])) {
        $flag = TRUE;
        $wpdb->update($wpdb->prefix . 'bwge_image_comment', array('published' => 1), array('id' => $tag_id));
      }
    }
    if ($flag) {
      echo BWGELibrary::message(__('Items Succesfully Published.', 'bwge_back'), 'updated');
    }
    else {
      echo BWGELibrary::message(__('You must select at least one item.', 'bwge_back'), 'error');
    }
    $this->display();
  }

  public function unpublish($id) {
    global $wpdb;
    $save = $wpdb->update($wpdb->prefix . 'bwge_image_comment', array('published' => 0), array('id' => $id));
    if ($save !== FALSE) {
      echo BWGELibrary::message('Item Succesfully Unpublished.', 'updated');
    }
    else {
      echo BWGELibrary::message('Error. Please install plugin again.', 'error');
    }
    $this->display();
  }
  
  public function unpublish_all() {
    global $wpdb;
    $flag = FALSE;
    $tag_ids_col = $wpdb->get_col('SELECT id FROM ' . $wpdb->prefix . 'bwge_image_comment');
    foreach ($tag_ids_col as $tag_id) {
      if (isset($_POST['check_' . $tag_id])) {
        $flag = TRUE;
        $wpdb->update($wpdb->prefix . 'bwge_image_comment', array('published' => 0), array('id' => $tag_id));
      }
    }
    if ($flag) {
      echo BWGELibrary::message(__('Items Succesfully Unpublished.', 'bwge_back'), 'updated');
    }
    else {
      echo BWGELibrary::message(__('You must select at least one item.', 'bwge_back'), 'error');
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