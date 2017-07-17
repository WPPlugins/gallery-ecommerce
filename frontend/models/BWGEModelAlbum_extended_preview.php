<?php

class BWGEModelAlbum_extended_preview {
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

  public function get_alb_gals_row($id, $albums_per_page, $sort_by, $bwge, $sort_direction = ' ASC ') {
    global $wpdb;
    if (isset($_REQUEST['bwge_page_number_' . $bwge]) && $_REQUEST['bwge_page_number_' . $bwge]) {
      $limit = ((int) $_REQUEST['bwge_page_number_' . $bwge] - 1) * $albums_per_page;
    }
    else {
      $limit = 0;
    }
    if ($albums_per_page) {
      $limit_str = 'LIMIT ' . $limit . ',' . $albums_per_page;
    }
    else {
      $limit_str = '';
    }
    $row = $wpdb->get_results($wpdb->prepare('SELECT * FROM ' . $wpdb->prefix . 'bwge_album_gallery WHERE album_id="%d" ORDER BY `' . $sort_by . '` ' . $sort_direction . $limit_str, $id));
    return $row;
  }

  public function get_album_row_data($id) {
    global $wpdb;
    $row = $wpdb->get_row($wpdb->prepare('SELECT * FROM ' . $wpdb->prefix . 'bwge_album WHERE published=1 AND id="%d"', $id));
    return $row;
  }

  public function get_gallery_row_data($id) {
    global $wpdb;
    $row = $wpdb->get_row($wpdb->prepare('SELECT * FROM ' . $wpdb->prefix . 'bwge_gallery WHERE published=1 AND id="%d"', $id));
    return $row;
  }

  public function get_image_rows_data($id, $images_per_page, $sort_by, $bwge, $sort_direction = ' ASC ') {
    global $wpdb;
    $bwge_search = ((isset($_POST['bwge_search_' . $bwge]) && esc_html($_POST['bwge_search_' . $bwge]) != '') ? esc_html($_POST['bwge_search_' . $bwge]) : '');
    if ($bwge_search != '') {
      $where = 'AND alt LIKE "%%' . $bwge_search . '%%"';
    }
    else {
      $where = '';
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
    if ($sort_by == 'random') {
      $sort_by = 'RAND()';
    }
    else {
      $sort_by = 'image.`' . $sort_by . '`';
    }
    if (isset($_REQUEST['bwge_tag_id_bwge_album_extended_' . $bwge]) && $_REQUEST['bwge_tag_id_bwge_album_extended_' . $bwge]) {
	    $row = $wpdb->get_results($wpdb->prepare('SELECT image.* FROM ' . $wpdb->prefix . 'bwge_image AS image INNER JOIN 
	   (SELECT GROUP_CONCAT( tag_id SEPARATOR ",") AS tags, image_id FROM  ' . $wpdb->prefix . 'bwge_image_tag WHERE gallery_id="' . $id . '"  GROUP BY image_id) AS tag ON image.id=tag.image_id WHERE image.published=1 ' . $where . ' AND CONCAT(",", tag.tags, ",") REGEXP ",('.implode("|",$_REQUEST['bwge_tag_id_bwge_album_extended_' . $bwge]).')," AND image.gallery_id="%d" ORDER BY ' . $sort_by . ' ' . $sort_direction . ' ' . $limit_str, $id));
    }
    else {
      $row = $wpdb->get_results($wpdb->prepare('SELECT * FROM ' . $wpdb->prefix . 'bwge_image AS image WHERE published=1 ' . $where . ' AND gallery_id="%d" ORDER BY ' . $sort_by . ' ' . $sort_direction . $limit_str, $id));
    }
    return $row;
  }

  public function gallery_page_nav($id, $bwge) {
    global $wpdb;
    $bwge_search = ((isset($_POST['bwge_search_' . $bwge]) && esc_html($_POST['bwge_search_' . $bwge]) != '') ? esc_html($_POST['bwge_search_' . $bwge]) : '');
    if ($bwge_search != '') {
      $where = 'AND alt LIKE "%%' . $bwge_search . '%%"';
    }
    else {
      $where = '';
    }
    if (isset($_REQUEST['bwge_tag_id_bwge_album_extended_' . $bwge]) && $_REQUEST['bwge_tag_id_bwge_album_extended_' . $bwge]) {
      $total = $wpdb->get_var('SELECT COUNT(*) FROM ' . $wpdb->prefix . 'bwge_image as image INNER JOIN 	(SELECT GROUP_CONCAT( tag_id SEPARATOR ",") AS tags, image_id FROM  ' . $wpdb->prefix . 'bwge_image_tag WHERE gallery_id="' . $id . '" GROUP BY image_id) AS tag ON image.id=tag.image_id  WHERE image.published=1 ' . $where . ' AND  CONCAT(",", tag.tags, ",") REGEXP ",('.implode("|",$_REQUEST['bwge_tag_id_bwge_album_extended_' . $bwge]).')," ');
    }
    else {
      $total = $wpdb->get_var($wpdb->prepare('SELECT COUNT(*) FROM ' . $wpdb->prefix . 'bwge_image WHERE published=1 ' . $where . ' AND gallery_id="%d"', $id));
    }
    $page_nav['total'] = $total;
    if (isset($_REQUEST['bwge_page_number_' . $bwge]) && $_REQUEST['bwge_page_number_' . $bwge]) {
      $page_nav['limit'] = (int) $_REQUEST['bwge_page_number_' . $bwge];
    }
    else {
      $page_nav['limit'] = 1;
    }
    return $page_nav;
  }

  public function album_page_nav($id, $bwge) {
    global $wpdb;
    $total = $wpdb->get_var($wpdb->prepare('SELECT COUNT(*) FROM ' . $wpdb->prefix . 'bwge_album_gallery WHERE album_id="%d"', $id));
    $page_nav['total'] = $total;
    if (isset($_REQUEST['bwge_page_number_' . $bwge]) && $_REQUEST['bwge_page_number_' . $bwge]) {
      $page_nav['limit'] = (int) $_REQUEST['bwge_page_number_' . $bwge];
    }
    else {
      $page_nav['limit'] = 1;
    }
    return $page_nav;
  }
  public function get_options_row_data() {
    global $wpdb;
    $row = $wpdb->get_row($wpdb->prepare('SELECT * FROM ' . $wpdb->prefix . 'bwge_option WHERE id="%d"', 1));
    return $row;
  }

  public function get_tags_rows_data($gallery_id) {
    global $wpdb;
    $row = $wpdb->get_results('Select t1.* FROM ' . $wpdb->prefix . 'terms AS t1 LEFT JOIN ' . $wpdb->prefix . 'term_taxonomy AS t2 ON t1.term_id = t2.term_id LEFT JOIN ( SELECT DISTINCT tag_id , gallery_id  FROM ' . $wpdb->prefix . 'bwge_image_tag) AS t3 ON  t1.term_id=t3.tag_id WHERE taxonomy = "bwge_tag" AND t3.gallery_id="' . $gallery_id . '"');
    return $row;
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