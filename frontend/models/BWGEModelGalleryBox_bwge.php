<?php

class BWGEModelGalleryBox_bwge {
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
     
    $row = $wpdb->get_row('SELECT * FROM ' . $wpdb->prefix . 'bwge_theme WHERE default_theme=1');
    
    if (isset($row->options)) {
      $row = (object) array_merge((array) $row, (array) json_decode($row->options));
    }
    return $row;
  }

  public function get_option_row_data() {
    global $wpdb;
    $row = $wpdb->get_row($wpdb->prepare('SELECT * FROM ' . $wpdb->prefix . 'bwge_option WHERE id="%d"', 1));
    return $row;
  }

  public function get_comment_rows_data($image_id) {
    global $wpdb;
    $row = $wpdb->get_results($wpdb->prepare('SELECT * FROM ' . $wpdb->prefix . 'bwge_image_comment WHERE image_id="%d" AND published=1 ORDER BY `id` DESC', $image_id));
    return $row;
  }

  public function get_image_rows_data($gallery_id, $bwge, $sort_by, $order_by = 'asc') {
    global $wpdb;
    if ($sort_by == 'size' || $sort_by == 'resolution') {
      $sort_by = ' CAST(t1.' . $sort_by . ' AS SIGNED) ';
    }
    elseif (($sort_by != 'alt') && ($sort_by != 'date') && ($sort_by != 'filetype') && ($sort_by != 'filename')) {
      $sort_by = 't1.`order`';
    }
    if (preg_replace('/\s+/', '', $order_by) != 'asc') {
      $order_by = 'desc';
    }

    $filter_tags = (isset($_REQUEST['filter_tag_'. $bwge]) && $_REQUEST['filter_tag_'. $bwge]  ) ? explode(",",$_REQUEST['filter_tag_'. $bwge]) : array();
    $filter_search_name = (isset($_REQUEST['filter_search_name_'. $bwge])) ? esc_html($_REQUEST['filter_search_name_'. $bwge]) : '';
    if ($filter_search_name != '') {
      $where = ' AND t1.alt LIKE "%%' . $filter_search_name . '%%"'; 
    }
    else {
      $where = '';
    }
    if ($filter_tags) {
      $row = $wpdb->get_results($wpdb->prepare('SELECT t1.*,t2.rate FROM ' . $wpdb->prefix . 'bwge_image as t1 LEFT JOIN (SELECT rate, image_id FROM ' . $wpdb->prefix . 'bwge_image_rate WHERE ip="%s") as t2 ON t1.id=t2.image_id INNER JOIN (SELECT GROUP_CONCAT( tag_id SEPARATOR ",") AS tags, image_id FROM  ' . $wpdb->prefix . 'bwge_image_tag WHERE gallery_id="' . $gallery_id . '" GROUP BY image_id) AS tag ON t1.id=tag.image_id WHERE t1.published=1 AND CONCAT(",", tag.tags, ",") REGEXP ",('.implode("|",$filter_tags).')," AND t1.gallery_id="%d"' . $where . ' ORDER BY ' . $sort_by . ' ' . $order_by, $_SERVER['REMOTE_ADDR'], $gallery_id));
    }
    else {
      $row = $wpdb->get_results($wpdb->prepare('SELECT t1.*,t2.rate FROM ' . $wpdb->prefix . 'bwge_image as t1 LEFT JOIN (SELECT rate, image_id FROM ' . $wpdb->prefix . 'bwge_image_rate WHERE ip="%s") as t2 ON t1.id=t2.image_id WHERE t1.published=1 AND t1.gallery_id="%d"' . $where . ' ORDER BY ' . $sort_by . ' ' . $order_by, $_SERVER['REMOTE_ADDR'], $gallery_id));
    }
    return $row;
  }

  public function get_image_rows_data_tag($tag_id, $sort_by, $order_by = 'asc') {
    global $wpdb;
    if ($sort_by == 'size' || $sort_by == 'resolution') {
      $sort_by = ' CAST(' . $sort_by . ' AS SIGNED) ';
    }
    elseif (($sort_by != 'alt') && ($sort_by != 'date') && ($sort_by != 'filetype')) {
      $sort_by = '`order`';
    }
    if (preg_replace('/\s+/', '', $order_by) != 'asc') {
      $order_by = 'desc';
    }
    $row = $wpdb->get_results($wpdb->prepare('SELECT t1.*,t2.rate FROM (SELECT image.* FROM ' . $wpdb->prefix . 'bwge_image as image INNER JOIN ' . $wpdb->prefix . 'bwge_image_tag as tag ON image.id=tag.image_id WHERE image.published=1 AND tag.tag_id="%d" ORDER BY  ' . $sort_by . ' ' . $order_by. ') as t1 LEFT JOIN (SELECT rate, image_id FROM ' . $wpdb->prefix . 'bwge_image_rate WHERE ip="%s") as t2 ON t1.id=t2.image_id ', $tag_id, $_SERVER['REMOTE_ADDR']));
    return $row;
  }
  
  public function get_image_pricelists($pricelist_id){
	$pricelist_data = array();
	if(!$pricelist_id){
		$pricelist = new StdClass();
		$pricelist->price = NULL;
		$pricelist->manual_description = NULL;
		$pricelist->manual_title = NULL;
		$pricelist->sections = NULL;
		
		$pricelist_data["pricelist"] = $pricelist;
		$pricelist_data["download_items"] = "";
		$pricelist_data["parameters"] = "";
		$options = new StdClass();
		$options->show_digital_items_count = NULL;
		$options->checkout_page = NULL;
		$options->currency_sign = NULL;
		$options->checkout_page = NULL;
		$pricelist_data["options"] = $options;
		$pricelist_data["products_in_cart"] =  0;
		
		return $pricelist_data;
	}	
	  
	global $wpdb;

	// pricelist
	$pricelist= $wpdb->get_row($wpdb->prepare('SELECT * FROM ' . $wpdb->prefix . 'bwge_pricelists WHERE id="%d" ',$pricelist_id));
	
	// download items
	$download_items= $wpdb->get_results($wpdb->prepare('SELECT * FROM ' . $wpdb->prefix . 'bwge_pricelist_items 
	WHERE pricelist_id="%d" ',$pricelist_id));
	
	// parameters	
	$parameter_rows= $wpdb->get_results($wpdb->prepare('SELECT T_PRICELIST_PARAMETERS.*, T_PARAMETERS.title,  T_PARAMETERS.type  FROM ' . $wpdb->prefix . 'bwge_pricelist_parameters AS T_PRICELIST_PARAMETERS LEFT JOIN ' . $wpdb->prefix . 'bwge_parameters
	AS T_PARAMETERS ON T_PRICELIST_PARAMETERS.parameter_id = T_PARAMETERS.id WHERE pricelist_id="%d" AND T_PARAMETERS.published="%d" ORDER BY T_PRICELIST_PARAMETERS.id',$pricelist_id,1));

	$parameters_map = array();
	foreach ($parameter_rows as $parameter_row) {
		$parameter_id = $parameter_row->parameter_id;
		$param_value = array();
		$param_value['parameter_value'] = $parameter_row->parameter_value;
		$param_value['parameter_value_price'] = $parameter_row->parameter_value_price;
		$param_value['parameter_value_price_sign'] = $parameter_row->parameter_value_price_sign;							
		$parameters_map[$parameter_id]['title'] = $parameter_row->title;
		$parameters_map[$parameter_id]['type'] = $parameter_row->type;
		$parameters_map[$parameter_id]["values"][] = $param_value;
		
	}
	
	//options	
	$options_rows = $wpdb->get_results('SELECT * FROM ' . $wpdb->prefix . 'bwge_ecommerceoptions ');
	$options = new stdClass();
	foreach ($options_rows as $row) {
		$name = $row->name;
		$value = $row->value;
		$options->$name = $value;
	}
	
	// shopping cart options
	$products_in_cart = 0;
    $order_rand_ids = isset($_COOKIE["order_rand_ids"]) ? explode("," , $_COOKIE["order_rand_ids"]) : array();

	$user = get_current_user_id();
	if($user == 0 && empty($order_rand_ids ) === false){
        array_walk($order_rand_ids, create_function('&$value', '$value = (int)$value;'));	
		// get image order rows
		foreach($order_rand_ids as $order_rand_id){
			$product_in_cart = $wpdb->get_var($wpdb->prepare('SELECT products_count FROM ' . $wpdb->prefix . 'bwge_order_images 
			WHERE user_id="%d" AND rand_id = "%d"  AND order_id="%d" ',$user,$order_rand_id,0));	
			$products_in_cart += $product_in_cart;
		}				
					
	}
	elseif($user != 0 ){
		// get image order rows
		$products_in_cart = $wpdb->get_var($wpdb->prepare('SELECT SUM(products_count) FROM ' . $wpdb->prefix . 'bwge_order_images 
		WHERE user_id="%d" AND order_id="%d" ',$user,0));		
	} 

	$pricelist_data["pricelist"] = $pricelist;
	$pricelist_data["download_items"] = $download_items;
	$pricelist_data["parameters"] = $parameters_map;
	$pricelist_data["options"] = $options;
	$pricelist_data["products_in_cart"] = $products_in_cart ? $products_in_cart : 0;
	
	return $pricelist_data;
  }
  

  public function get_image_pricelist($image_id){
    global $wpdb;
    $image_pricelist = $wpdb->get_var($wpdb->prepare('SELECT pricelist_id FROM ' . $wpdb->prefix . 'bwge_image WHERE id="%d" ', $image_id));
    return $image_pricelist;	  
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