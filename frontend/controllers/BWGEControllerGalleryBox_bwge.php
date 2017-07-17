<?php

class BWGEControllerGalleryBox_bwge {
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
    $ajax_task = (isset($_POST['ajax_task']) ? esc_html($_POST['ajax_task']) : '');
    if (method_exists($this, $ajax_task)) {
      $this->$ajax_task();
    }
    else {
      $this->display();
    }
  }

  public function display() {
    require_once WD_BWGE_DIR . "/frontend/models/BWGEModelGalleryBox_bwge.php";
    $model = new BWGEModelGalleryBox_bwge();

    require_once WD_BWGE_DIR . "/frontend/views/BWGEViewGalleryBox_bwge.php";
    $view = new BWGEViewGalleryBox_bwge($model);

    $view->display();
  }

  public function save() {
    require_once WD_BWGE_DIR . "/frontend/models/BWGEModelGalleryBox_bwge.php";
    $model = new BWGEModelGalleryBox_bwge();
    $option_row = $model->get_option_row_data();
    if ($option_row->popup_enable_email) {
      // Email validation.
      $email = (isset($_POST['bwge_email']) ? is_email(stripslashes($_POST['bwge_email'])) : FALSE);
    }
    else {
      $email = TRUE;
    }
    if ($option_row->popup_enable_captcha) {
      $bwge_captcha_input = (isset($_POST['bwge_captcha_input']) ? esc_html(stripslashes($_POST['bwge_captcha_input'])) : '');
      @session_start();
      $bwge_captcha_code = (isset($_SESSION['bwge_captcha_code']) ? esc_html(stripslashes($_SESSION['bwge_captcha_code'])) : '');
      if ($bwge_captcha_input === $bwge_captcha_code) {
        $captcha = TRUE;
      }
      else {
        $captcha = FALSE;
      }
    }
    else {
      $captcha = TRUE;
    }

    if ($email && $captcha) {
      global $wpdb;
      $image_id = (isset($_POST['image_id']) ? (int) $_POST['image_id'] : 0);
      $name = (isset($_POST['bwge_name']) ? esc_html(stripslashes($_POST['bwge_name'])) : '');
      $bwge_comment = (isset($_POST['bwge_comment']) ? esc_html(stripslashes($_POST['bwge_comment'])) : '');
      $bwge_email = (isset($_POST['bwge_email']) ? esc_html(stripslashes($_POST['bwge_email'])) : '');
      $published = (current_user_can('manage_options') || !$option_row->comment_moderation) ? 1 : 0;
      $save = $wpdb->insert($wpdb->prefix . 'bwge_image_comment', array(
        'image_id' => $image_id,
        'name' => $name,
        'date' => date('Y-m-d H:i'),
        'comment' => $bwge_comment,
        'url' => '',
        'mail' => $bwge_email,
        'published' => $published,
      ), array(
        '%d',
        '%s',
        '%s',
        '%s',
        '%s',
        '%s',
        '%d',
      ));
      $wpdb->query($wpdb->prepare('UPDATE ' . $wpdb->prefix . 'bwge_image SET comment_count=comment_count+1 WHERE id="%d"', $image_id));
    }
    $this->display();
  }

  public function save_rate() {
    global $wpdb;
    $image_id = (isset($_POST['image_id']) ? esc_html(stripslashes($_POST['image_id'])) : 0);
    $rate = (isset($_POST['rate']) ? esc_html(stripslashes($_POST['rate'])) : '');
    if (!$wpdb->get_var($wpdb->prepare('SELECT image_id FROM ' . $wpdb->prefix . 'bwge_image_rate WHERE ip="%s" AND image_id="%d"', $_SERVER['REMOTE_ADDR'], $image_id))) {
      $wpdb->insert($wpdb->prefix . 'bwge_image_rate', array(
        'image_id' => $image_id,
        'rate' => $rate,
        'ip' => $_SERVER['REMOTE_ADDR'],
        'date' => date('Y-m-d H:i:s'),
      ), array(
        '%d',
        '%f',
        '%s',
        '%s',
      ));
      $rates = $wpdb->get_row($wpdb->prepare('SELECT AVG(`rate`) as `average`, COUNT(`rate`) as `rate_count` FROM ' . $wpdb->prefix . 'bwge_image_rate WHERE image_id="%d"', $image_id));
      $wpdb->update($wpdb->prefix . 'bwge_image', array('avg_rating' => $rates->average, 'rate_count' => $rates->rate_count), array('id' => $image_id));
    }
    $this->display();
  }

  public function save_hit_count() {
    global $wpdb;
    $image_id = (isset($_POST['image_id']) ? esc_html(stripslashes($_POST['image_id'])) : 0);
    $wpdb->query($wpdb->prepare('UPDATE ' . $wpdb->prefix . 'bwge_image SET hit_count = hit_count + 1 WHERE id="%d"', $image_id));
  }

  public function delete() {
    global $wpdb;
    $comment_id = (isset($_POST['comment_id']) ? (int) $_POST['comment_id'] : 0);
    $image_id = (isset($_POST['image_id']) ? (int) $_POST['image_id'] : 0);
    $wpdb->query($wpdb->prepare('DELETE FROM ' . $wpdb->prefix . 'bwge_image_comment WHERE id="%d"', $comment_id));
    $wpdb->query($wpdb->prepare('UPDATE ' . $wpdb->prefix . 'bwge_image SET comment_count=comment_count-1 WHERE id="%d"', $image_id));
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