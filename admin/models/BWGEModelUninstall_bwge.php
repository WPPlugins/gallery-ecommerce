<?php

class BWGEModelUninstall_bwge {
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
  public function delete_db_tables() {
    global $wpdb;
    $wpdb->query("DROP TABLE IF EXISTS " . $wpdb->prefix . "bwge_album");
    $wpdb->query("DROP TABLE IF EXISTS " . $wpdb->prefix . "bwge_album_gallery");
    $wpdb->query("DROP TABLE IF EXISTS " . $wpdb->prefix . "bwge_gallery");
    $wpdb->query("DROP TABLE IF EXISTS " . $wpdb->prefix . "bwge_image");
    $wpdb->query("DROP TABLE IF EXISTS " . $wpdb->prefix . "bwge_image_comment");
    $wpdb->query("DROP TABLE IF EXISTS " . $wpdb->prefix . "bwge_image_rate");
    $wpdb->query("DROP TABLE IF EXISTS " . $wpdb->prefix . "bwge_image_tag");
    $wpdb->query("DROP TABLE IF EXISTS " . $wpdb->prefix . "bwge_option");
    $wpdb->query("DROP TABLE IF EXISTS " . $wpdb->prefix . "bwge_theme");
    $wpdb->query("DROP TABLE IF EXISTS " . $wpdb->prefix . "bwge_shortcode");
    $wpdb->query("DROP TABLE IF EXISTS " . $wpdb->prefix . "bwge_ecommerceoptions");
    $wpdb->query("DROP TABLE IF EXISTS " . $wpdb->prefix . "bwge_orders");
    $wpdb->query("DROP TABLE IF EXISTS " . $wpdb->prefix . "bwge_order_images");
    $wpdb->query("DROP TABLE IF EXISTS " . $wpdb->prefix . "bwge_parameters");
    $wpdb->query("DROP TABLE IF EXISTS " . $wpdb->prefix . "bwge_payment_systems");
    $wpdb->query("DROP TABLE IF EXISTS " . $wpdb->prefix . "bwge_pricelists");
    $wpdb->query("DROP TABLE IF EXISTS " . $wpdb->prefix . "bwge_pricelist_items");
    $wpdb->query("DROP TABLE IF EXISTS " . $wpdb->prefix . "bwge_pricelist_parameters");    
    delete_option("wd_bwge_version");
    if (isset($_COOKIE['bwge_image_asc_or_desc'])) {
      $_COOKIE['bwge_image_asc_or_desc'] = '';
    }
    if (isset($_COOKIE['bwge_image_order_by'])) {
      $_COOKIE['bwge_image_order_by'] = '';
    }
    // Delete terms.
    $terms = get_terms('bwge_tag', array('orderby' => 'count', 'hide_empty' => 0));
    foreach ($terms as $term) {
      wp_delete_term($term->term_id, 'bwge_tag');
    }
    // Delete custom pages for galleries.
    $posts = get_posts(array('posts_per_page' => -1, 'post_type' => 'bwge_gallery'));
    foreach ($posts as $post) {
      wp_delete_post($post->ID, TRUE);
    }
    // Delete custom pages for albums.
    $posts = get_posts(array('posts_per_page' => -1, 'post_type' => 'bwge_album'));
    foreach ($posts as $post) {
      wp_delete_post($post->ID, TRUE);
    }
    // Delete custom pages for tags.
    $posts = get_posts(array('posts_per_page' => -1, 'post_type' => 'bwge_tag'));
    foreach ($posts as $post) {
      wp_delete_post($post->ID, TRUE);
    }
    // Delete custom pages for share.
    $posts = get_posts(array('posts_per_page' => -1, 'post_type' => 'bwge_share'));
    foreach ($posts as $post) {
      wp_delete_post($post->ID, TRUE);
    }
    
    // Delete custom pages for ecommerce.
    $pge_pages = get_posts(array('post_type' => 'bwge_ecommerce_page'));
		foreach ($pge_pages as $pge_page) {
			wp_delete_post($pge_page->ID, TRUE);
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