<?php

class BWGEModelImage_browser {
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
  public function get_theme_row_data($id) {
    global $wpdb;
    if ($id) {
      $row = $wpdb->get_row($wpdb->prepare('SELECT * FROM ' . $wpdb->prefix . 'bwge_theme WHERE id="%d"', $id));
    }
    else {      
      $row = $wpdb->get_row('SELECT * FROM ' . $wpdb->prefix . 'bwge_theme WHERE default_theme=1');
    }
    if (isset($row->options)) {
      $row = (object) array_merge((array) $row, (array) json_decode($row->options));
    }
    return $row;
  }

  public function get_gallery_row_data($id) {
    global $wpdb;
    $row = $wpdb->get_row($wpdb->prepare('SELECT * FROM ' . $wpdb->prefix . 'bwge_gallery WHERE published=1 AND id="%d"', $id));
    return $row;
  }

  public function get_image_rows_data($id, $images_per_page, $sort_by, $order_by = 'asc', $bwge) {
    global $wpdb;
    $bwge_search = ((isset($_POST['bwge_search_' . $bwge]) && esc_html($_POST['bwge_search_' . $bwge]) != '') ? esc_html($_POST['bwge_search_' . $bwge]) : '');
    if ($bwge_search != '') {
      $where = 'AND alt LIKE "%%' . $bwge_search . '%%"';
    }
    else {
      $where = '';
    }
    if ($sort_by == 'size' || $sort_by == 'resolution') {
      $sort_by = ' CAST(' . $sort_by . ' AS SIGNED) ';
    }
    elseif ($sort_by == 'random') {
      $sort_by = 'RAND()';
    }
    elseif (($sort_by != 'alt') && ($sort_by != 'date') && ($sort_by != 'filetype') && ($sort_by != 'filename')) {
      $sort_by = '`order`';
    }
    if (isset($_REQUEST['bwge_page_number_' . $bwge]) && $_REQUEST['bwge_page_number_' . $bwge]) {
      $limit = ((int) $_REQUEST['bwge_page_number_' . $bwge] - 1) * $images_per_page;
    }
    else {
      $limit = 0;
    }
    if ($images_per_page) {
      $limit_str = 'LIMIT ' . $limit . ',' . $images_per_page;
    }
    else {
      $limit_str = '';
    }
    $row = $wpdb->get_results($wpdb->prepare('SELECT * FROM ' . $wpdb->prefix . 'bwge_image WHERE published=1 ' . $where . ' AND gallery_id="%d" ORDER BY ' . $sort_by . ' ' . $order_by . ' ' . $limit_str, $id));
    return $row;
  }

  public function get_option_row_data() {
    global $wpdb;
    $row = $wpdb->get_row($wpdb->prepare('SELECT * FROM ' . $wpdb->prefix . 'bwge_option WHERE id="%d"', 1));
    return $row;
  }

  public function page_nav($id, $images_per_page, $bwge) {
    global $wpdb;
    $bwge_search = ((isset($_POST['bwge_search_' . $bwge]) && esc_html($_POST['bwge_search_' . $bwge]) != '') ? esc_html($_POST['bwge_search_' . $bwge]) : '');
    if ($bwge_search != '') {
      $where = 'AND alt LIKE "%%' . $bwge_search . '%%"';
    }
    else {
      $where = '';
    }
    $total = $wpdb->get_var($wpdb->prepare('SELECT COUNT(*) FROM ' . $wpdb->prefix . 'bwge_image WHERE published=1 ' . $where . ' AND gallery_id="%d"', $id));
    $page_nav['total'] = $total;
    if (isset($_REQUEST['bwge_page_number_' . $bwge]) && $_REQUEST['bwge_page_number_' . $bwge]) {
      $limit = ((int) $_REQUEST['bwge_page_number_' . $bwge] - 1) * $images_per_page;
    }
    else {
      $limit = 0;
    }
    $page_nav['limit'] = (int) ($limit / $images_per_page + 1);
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