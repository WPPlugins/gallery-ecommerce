<?php

class BWGEControllerBWGEShortcode_bwge {
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
    $task = BWGELibrary::get('task');
    
    $from_menu = ((isset($_GET['page']) && (esc_html($_GET['page']) == 'BWGEShortcode_bwge')) ? TRUE : FALSE);
    
    if($task != '' && $from_menu){
      if(!BWGELibrary::verify_nonce('BWGEShortcode_bwge')){
        die('Sorry, your nonce did not verify.');
      }
    }

    
    if (method_exists($this, $task)) {
      $this->$task();
    }
    $this->display();
  }

  public function display() {
    require_once WD_BWGE_DIR . "/admin/models/BWGEModelBWGEShortcode_bwge.php";
    $model = new BWGEModelBWGEShortcode_bwge();

    require_once WD_BWGE_DIR . "/admin/views/BWGEViewBWGEShortcode_bwge.php";
    $view = new BWGEViewBWGEShortcode_bwge($model);
    $view->display();
  }

  public function save() {
    global $wpdb;
    $tagtext = ((isset($_POST['tagtext'])) ? stripslashes($_POST['tagtext']) : '');
    if ($tagtext) {
      $id = ((isset($_POST['currrent_id'])) ? (int) esc_html(stripslashes($_POST['currrent_id'])) : 0);
      $insert = ((isset($_POST['bwge_insert'])) ? (int) esc_html(stripslashes($_POST['bwge_insert'])) : 0);
      if (!$insert) {
        $save = $wpdb->update($wpdb->prefix . 'bwge_shortcode', array(
        'tagtext' => $tagtext
        ), array('id' => $id));
      }
      else {
        $save = $wpdb->insert($wpdb->prefix . 'bwge_shortcode', array(
          'id' => $id,
          'tagtext' => $tagtext
        ), array(
          '%d',
          '%s'
        ));
      }
    }
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