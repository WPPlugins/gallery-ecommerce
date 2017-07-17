<?php

/**
 * Plugin Name: Gallery Ecommerce
 * Plugin URI: https://codecanyon.net/item/wp-grid-gallery-i-wordpress-gallery-plugin/19334904
 * Description: Gallery Ecommerce is an advanced and user-friendly plugin for creating galleries and selling both digital (e.g. photos) and tangible products.
 * Version: 1.0.9
 * Author: WebDorado
 * Author URI: https://codecanyon.net/item/wp-grid-gallery-i-wordpress-gallery-plugin/19334904
 * License: GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */

define('WD_BWGE_DIR', WP_PLUGIN_DIR . "/" . plugin_basename(dirname(__FILE__)));
define('WD_BWGE_URL', plugins_url(plugin_basename(dirname(__FILE__))));
define('WD_BWGE_NAME',plugin_basename(dirname(__FILE__)));

define('WD_BWGE_PRO', false);
// Photo Gallery Facebook
$wd_bwge_fb = FALSE;

if (session_id() == '') {
  @session_start();
}

function bwge_use_home_url() {
  $home_url = str_replace("http://", "", home_url());
  $home_url = str_replace("https://", "", $home_url);
  $pos = strpos($home_url, "/");
  if ($pos) {
    $home_url = substr($home_url, 0, $pos);
  }
  $site_url = str_replace("http://", "", WD_BWGE_URL);
  $site_url = str_replace("https://", "", $site_url);
  $pos = strpos($site_url, "/");
  if ($pos) {
    $site_url = substr($site_url, 0, $pos);
  }
  return $site_url != $home_url;
}

if (bwge_use_home_url()) {
  define('WD_BWGE_FRONT_URL', home_url("wp-content/plugins/" . plugin_basename(dirname(__FILE__))));
}
else {
  define('WD_BWGE_FRONT_URL', WD_BWGE_URL);
}

global $wpdb;
if ($wpdb->query("SHOW TABLES LIKE '" . $wpdb->prefix . "bwge_option'")) {
  $bwge_options_row = $wpdb->get_row($wpdb->prepare('SELECT images_directory,permissions FROM ' . $wpdb->prefix . 'bwge_option WHERE id="%d"', 1));
  $WD_BWGE_UPLOAD_DIR = $bwge_options_row->images_directory . '/gallery-ecommerce';
  $bwge_permissions = $bwge_options_row->permissions;
  if ($bwge_permissions != 'moderate_comments' && $bwge_permissions != 'publish_posts' && $bwge_permissions != 'edit_posts') {
    $bwge_permissions = 'manage_options';
  }
}
else {
  $upload_dir = wp_upload_dir();
  $WD_BWGE_UPLOAD_DIR = str_replace(ABSPATH, '', $upload_dir['basedir']) . '/gallery-ecommerce';
  $bwge_permissions = 'manage_options';
}

// Plugin menu.
function bwge_options_panel() {
  global $bwge_permissions;
  $galleries_page = add_menu_page('Gallery Ecommerce', 'Gallery', $bwge_permissions, 'galleries_bwge', 'bwge_gallery', WD_BWGE_URL . '/images/best-wordpress-gallery.png', '27,48');

  $galleries_page = add_submenu_page('galleries_bwge', __('Add Galleries/Images','bwge_back'), __('Add Galleries/Images','bwge_back'), $bwge_permissions, 'galleries_bwge', 'bwge_gallery');
  add_action('admin_print_styles-' . $galleries_page, 'bwge_styles');
  add_action('admin_print_scripts-' . $galleries_page, 'bwge_scripts');
  add_action('load-' . $galleries_page, 'bwge_add_galleries_per_page_option');

  $albums_page = add_submenu_page('galleries_bwge', __('Albums','bwge_back'), __('Albums','bwge_back'), $bwge_permissions, 'albums_bwge', 'bwge_gallery');
  add_action('admin_print_styles-' . $albums_page, 'bwge_styles');
  add_action('admin_print_scripts-' . $albums_page, 'bwge_scripts');
  add_action('load-' . $albums_page, 'bwge_add_albums_per_page_option');

  $tags_page = add_submenu_page('galleries_bwge', __('Tags','bwge_back'), 'Tags', $bwge_permissions, 'tags_bwge', 'bwge_gallery');
  add_action('admin_print_styles-' . $tags_page, 'bwge_styles');
  add_action('admin_print_scripts-' . $tags_page, 'bwge_scripts');
  add_action('load-' . $tags_page, 'bwge_add_tags_per_page_option');

  $options_page = add_submenu_page('galleries_bwge', __('Options','bwge_back'), __('Options','bwge_back'), 'manage_options', 'options_bwge', 'bwge_gallery');
  add_action('admin_print_styles-' . $options_page, 'bwge_styles');
  add_action('admin_print_scripts-' . $options_page, 'bwge_options_scripts');

  $themes_page = add_submenu_page('galleries_bwge', __('Themes','bwge_back'), __('Themes','bwge_back'), 'manage_options', 'themes_bwge', 'bwge_gallery');
  add_action('admin_print_styles-' . $themes_page, 'bwge_styles');
  add_action('admin_print_scripts-' . $themes_page, 'bwge_options_scripts');


  $comments_page = add_submenu_page('galleries_bwge', __('Comments','bwge_back'), __('Comments','bwge_back'), 'manage_options', 'comments_bwge', 'bwge_gallery');
  add_action('admin_print_styles-' . $comments_page, 'bwge_styles');
  add_action('admin_print_scripts-' . $comments_page, 'bwge_options_scripts');
  add_action('load-' . $comments_page, 'bwge_add_comments_per_page_option');

  $rates_page = add_submenu_page('galleries_bwge', __('Ratings','bwge_back'), __('Ratings','bwge_back'), 'manage_options', 'rates_bwge', 'bwge_gallery');
  add_action('admin_print_styles-' . $rates_page, 'bwge_styles');
  add_action('admin_print_scripts-' . $rates_page, 'bwge_options_scripts');
  add_action('load-' . $rates_page, 'bwge_add_rates_per_page_option');

  add_submenu_page('galleries_bwge', __('Generate Shortcode','bwge_back'), __('Generate Shortcode','bwge_back'), $bwge_permissions, 'BWGEShortcode_bwge', 'bwge_gallery');

  add_submenu_page('galleries_bwge', __('Featured Plugins','bwge_back'), __('Featured Plugins','bwge_back'), 'manage_options', 'featured_plugins_bwge', 'bwge_featured');
  add_submenu_page('galleries_bwge', __('Featured Themes','bwge_back'), __('Featured Themes','bwge_back'), 'manage_options', 'featured_themes_bwge', 'bwge_featured_themes');

  $uninstall_page = add_submenu_page('galleries_bwge', __('Uninstall','bwge_back'), __('Uninstall','bwge_back'), 'manage_options', 'uninstall_bwge', 'bwge_gallery');
  add_action('admin_print_styles-' . $uninstall_page, 'bwge_styles');
  add_action('admin_print_scripts-' . $uninstall_page, 'bwge_options_scripts');


  $wdpg_ecommerce_page = add_menu_page('Gallery Ecommerce', 'Gallery Ecommerce', 'manage_options', 'pricelists_bwge', 'bwge_gallery', WD_BWGE_URL . '/images/icon-ecommerce.png', '27,49');


  $price_lists_page = add_submenu_page('pricelists_bwge', 'Pricelists', 'Pricelists', 'manage_options', 'pricelists_bwge', 'bwge_gallery');
  add_action('admin_print_styles-' . $price_lists_page, 'bwge_styles');
  add_action('admin_print_scripts-' . $price_lists_page, 'bwge_ecommerce_scripts');
  add_action('load-' . $price_lists_page, 'wdbwge_pricelists_per_page_option');

  $parameters_page = add_submenu_page('pricelists_bwge', 'Parameters', 'Parameters', 'manage_options', 'parameters_bwge', 'bwge_gallery');
  add_action('admin_print_styles-' . $parameters_page, 'bwge_styles');
  add_action('admin_print_scripts-' . $parameters_page, 'bwge_ecommerce_scripts');
  add_action('load-' . $parameters_page, 'wdbwge_parameters_per_page_option');

  $orders_page = add_submenu_page('pricelists_bwge', 'Orders', 'Orders', 'manage_options', 'orders_bwge', 'bwge_gallery');
  add_action('admin_print_styles-' . $orders_page, 'bwge_styles');
  add_action('admin_print_scripts-' . $orders_page, 'bwge_ecommerce_scripts');
  add_action('load-' . $orders_page, 'wdbwge_orders_per_page_option');


  $reports_page = add_submenu_page('pricelists_bwge', 'Reports', 'Reports', 'manage_options', 'reports_bwge', 'bwge_gallery');
  add_action('admin_print_styles-' . $reports_page, 'bwge_styles');
  add_action('admin_print_scripts-' . $reports_page, 'bwge_ecommerce_scripts');

  $wdpg_ecommerce_page = add_submenu_page('pricelists_bwge', 'Ecommerce Options', 'Ecommerce Options', 'manage_options', 'ecommerceoptions_bwge', 'bwge_gallery');
  add_action('admin_print_styles-' . $wdpg_ecommerce_page, 'bwge_styles');
  add_action('admin_print_scripts-' . $wdpg_ecommerce_page, 'bwge_ecommerce_scripts');

  $iustructions_page = add_submenu_page('pricelists_bwge', 'Instructions', 'Instructions', 'manage_options', 'instructions_bwge', 'bwge_gallery');
  add_action('admin_print_styles-' . $iustructions_page, 'bwge_styles');
  add_action('admin_print_scripts-' . $iustructions_page, 'bwge_ecommerce_scripts');



}
add_action('admin_menu', 'bwge_options_panel');

//require_once(WD_BWGE_DIR . '/update/update.php');
//$bwge_update = new WDW_bwge_Update();
//add_action('admin_menu', array($bwge_update, 'check_for_update'), 25);

function bwge_gallery() {
  global $wpdb;
  require_once(WD_BWGE_DIR . '/framework/BWGELibrary.php');
  require_once(WD_BWGE_DIR . '/framework/BWGEHelper.php');
  require_once(WD_BWGE_DIR . '/framework/BWGECheckoutEmail.php');
  require_once(WD_BWGE_DIR . '/framework/BWGEPaypalstandart.php');
  require_once(WD_BWGE_DIR . '/admin/controllers/BWGEController.php');
  require_once(WD_BWGE_DIR . '/admin/models/BWGEModel.php');
  require_once(WD_BWGE_DIR . '/admin/views/BWGEView.php');
  $page = BWGELibrary::get('page');
  if (($page != '') && (($page == 'galleries_bwge') || ($page == 'albums_bwge') || ($page == 'tags_bwge') || ($page == 'options_bwge') || ($page == 'comments_bwge') || ($page == 'rates_bwge') || ($page == 'themes_bwge') || ($page == 'uninstall_bwge') || ($page == 'BWGEShortcode_bwge') || ($page == 'pricelists_bwge' ) || ($page == 'parameters_bwge') || ($page == 'orders_bwge') || ($page == 'reports_bwge') || ($page == 'instructions_bwge') || ($page == 'ecommerceoptions_bwge'))) {
    if(BWGELibrary::get('task') != "explore"){
      BWGEHelper::upgrade_pro();
    }
    require_once(WD_BWGE_DIR . '/admin/controllers/BWGEController' . (($page == 'BWGEShortcode_bwge') ? $page : ucfirst(strtolower($page))) . '.php');
    $controller_class = 'BWGEController' . ucfirst(strtolower($page));
    $controller = new $controller_class();
    $controller->execute();
  }
}

function bwge_gallery_ajax(){
    check_ajax_referer('nonce_bwge', 'nonce_bwge');
    global $wpdb;
    require_once(WD_BWGE_DIR . '/framework/BWGELibrary.php');
    require_once(WD_BWGE_DIR . '/framework/BWGEHelper.php');
    require_once(WD_BWGE_DIR . '/framework/BWGECheckoutEmail.php');
    require_once(WD_BWGE_DIR . '/framework/BWGEPaypalstandart.php');
    require_once(WD_BWGE_DIR . '/admin/controllers/BWGEController.php');
    require_once(WD_BWGE_DIR . '/admin/models/BWGEModel.php');
    require_once(WD_BWGE_DIR . '/admin/views/BWGEView.php');
  if (($page != '') && ( ($page == 'pricelists_bwge') || ($page == 'parameters_bwge') )) {
    require_once(WD_BWGE_DIR . '/admin/controllers/BWGEController' .  ucfirst(strtolower($page)) . '.php');
    $controller_class = 'BWGEController' . ucfirst(strtolower($page));
    $controller = new $controller_class();
    $controller->execute();
  }
}

function bwge_featured() {
  if (function_exists('current_user_can')) {
    if (!current_user_can('manage_options')) {
      die('Access Denied');
    }
  }
  else {
    die('Access Denied');
  }
  require_once(WD_BWGE_DIR . '/featured/featured.php');
  wp_register_style('bwge_featured', WD_BWGE_URL . '/featured/style.css', array(), wd_bwge_version());
  wp_print_styles('bwge_featured');
  bwge_bwge_spider_featured('gallery-ecommerce');
}

function bwge_featured_themes() {
  if (function_exists('current_user_can')) {
    if (!current_user_can('manage_options')) {
      die('Access Denied');
    }
  }
  else {
    die('Access Denied');
  }
  require_once(WD_BWGE_DIR . '/featured/featured_themes.php');
  wp_register_style('bwge_featured_themes', WD_BWGE_URL . '/featured/themes_style.css', array(), wd_bwge_version());
  wp_print_styles('bwge_featured_themes');
  bwge_bwge_spider_featured_themes();
}



function bwge_ajax_frontend() {
  require_once(WD_BWGE_DIR . '/framework/BWGELibrary.php');
  require_once(WD_BWGE_DIR . '/framework/BWGEHelper.php');
  $page = BWGELibrary::get('action');
  $controller = BWGEHelper::get("controller");
  if (($page != '') && (($page == 'GalleryBox_bwge') || ($page == 'Share_bwge'))) {
    require_once(WD_BWGE_DIR . '/frontend/controllers/BWGEController' . ucfirst($page) . '.php');
    $controller_class = 'BWGEController' . ucfirst($page);
    $controller = new $controller_class();
    $controller->execute();
  }
  elseif($controller){
    require_once(WD_BWGE_DIR . '/framework/BWGEPaypalstandart.php');
    require_once(WD_BWGE_DIR . '/frontend/controllers/BWGEControllerFrontend.php');
    require_once(WD_BWGE_DIR . '/frontend/models/BWGEModelFrontend.php');
    require_once(WD_BWGE_DIR . '/frontend/views/BWGEViewFrontend.php');
    require_once(WD_BWGE_DIR . '/frontend/controllers/BWGEController' . ucfirst($controller) . '.php');

    $controller_class = 'BWGEController' . ucfirst($controller) . '';
    $controller = new $controller_class();
    $controller->execute();
  }
}
add_action('wp_ajax_bwge_add_cart', 'bwge_ajax_frontend');
add_action('wp_ajax_nopriv_bwge_add_cart', 'bwge_ajax_frontend');
add_action('wp_ajax_bwge_update_cart', 'bwge_ajax_frontend');
add_action('wp_ajax_nopriv_bwge_update_cart', 'bwge_ajax_frontend');
add_action('wp_ajax_bwge_download_file', 'bwge_ajax_frontend');
add_action('wp_ajax_nopriv_bwge_download_file', 'bwge_ajax_frontend');
add_action('wp_ajax_bwge_display_checkout_form', 'bwge_ajax_frontend');
add_action('wp_ajax_nopriv_bwge_display_checkout_form', 'bwge_ajax_frontend');

add_action('wp_ajax_bwge_add_parameters', 'bwge_gallery');
add_action('wp_ajax_bwge_add_pricelist', 'bwge_gallery');
add_action('wp_ajax_bwge_UploadHandler', 'bwge_UploadHandler');
add_action('wp_ajax_addAlbumsGalleries_bwge', 'bwge_ajax');
add_action('wp_ajax_bwge_addImages', 'bwge_filemanager_ajax');
add_action('wp_ajax_bwge_addMusic', 'bwge_filemanager_ajax');
add_action('wp_ajax_bwge_addEmbed', 'bwge_add_embed_ajax');
add_action('wp_ajax_bwge_addInstagramGallery', 'bwge_add_embed_ajax');
add_action('wp_ajax_bwge_addFacebookGallery', 'bwge_add_embed_ajax');
add_action('wp_ajax_editThumb_bwge', 'bwge_ajax');
add_action('wp_ajax_addTags_bwge', 'bwge_ajax');
add_action('wp_ajax_bwge_edit_tag', 'bwge_edit_tag');
add_action('wp_ajax_GalleryBox_bwge', 'bwge_ajax_frontend');
add_action('wp_ajax_bwge_captcha', 'bwge_captcha');
add_action('wp_ajax_Share_bwge', 'bwge_ajax_frontend');

add_action('wp_ajax_nopriv_GalleryBox_bwge', 'bwge_ajax_frontend');
add_action('wp_ajax_nopriv_bwge_captcha', 'bwge_captcha');
add_action('wp_ajax_nopriv_Share_bwge', 'bwge_ajax_frontend');
// For facebook embed post.
add_action('wp_ajax_nopriv_view_facebook_post_bwge', 'bwge_add_embed_ajax');
add_action('wp_ajax_view_facebook_post_bwge', 'bwge_add_embed_ajax');

add_action('wp_ajax_show_add_to_cart', 'bwge_ajax_frontend');
add_action('wp_ajax_nopriv_show_add_to_cart', 'bwge_ajax_frontend');

// Upload.
function bwge_UploadHandler() {
  require_once(WD_BWGE_DIR . '/framework/BWGELibrary.php');
  if(!BWGELibrary::verify_nonce('bwge_UploadHandler')){
      die('Sorry, your nonce did not verify.');
  }
  require_once(WD_BWGE_DIR . '/filemanager/UploadHandler.php');
}

function bwge_filemanager_ajax() {
  global $bwge_permissions;
  if (function_exists('current_user_can')) {
    if (!current_user_can($bwge_permissions)) {
      die('Access Denied');
    }
  }
  else {
    die('Access Denied');
  }
  global $wpdb;
  require_once(WD_BWGE_DIR . '/framework/BWGELibrary.php');
  $page = BWGELibrary::get('action');
  if (($page != '') && (($page == 'bwge_addImages') || ($page == 'bwge_addMusic'))) {

    if(!BWGELibrary::verify_nonce($page)){
      die('Sorry, your nonce did not verify.');
    }
    require_once(WD_BWGE_DIR . '/filemanager/controller.php');
    $controller_class = 'BWGEFilemanagerController';
    $controller = new $controller_class();
    $controller->execute();
  }
}
function bwge_add_embed_ajax() {
  global $bwge_permissions;
  if (function_exists('current_user_can')) {
    if (!current_user_can($bwge_permissions)) {
      die('Access Denied');
    }
  }
  else {
    die('Access Denied');
  }
  require_once(WD_BWGE_DIR . '/framework/BWGELibrary.php');
  if (!BWGELibrary::verify_nonce('')) {
    die(BWGELibrary::delimit_wd_output(json_encode(array("error", "Sorry, your nonce did not verify."))));
  }

  require_once(WD_BWGE_DIR . '/framework/BWGELibraryEmbed.php');
  $embed_action = BWGELibrary::get('action');

  switch($embed_action) {
    case 'bwge_addEmbed':
      $url_to_embed = BWGELibrary::get('URL_to_embed');
      $data = BWGELibraryEmbed::add_embed($url_to_embed);
      echo BWGELibrary::delimit_wd_output($data);
      wp_die();
    break;
    case 'bwge_addInstagramGallery':
      $instagram_user = BWGELibrary::get('instagram_user');
      $instagram_access_token = BWGELibrary::get('instagram_access_token');
      $autogallery_image_number = BWGELibrary::get('autogallery_image_number');
      $whole_post = BWGELibrary::get('whole_post');
      $data = BWGELibraryEmbed::add_instagram_gallery($instagram_user, $instagram_access_token, $whole_post, $autogallery_image_number);

      if(!$data){
        echo BWGELibrary::delimit_wd_output(json_encode(array("error", "Cannot get instagram data")));
      }
      if($data){
        $images_new = json_decode($data, true);
        if(empty($images_new)){
        echo BWGELibrary::delimit_wd_output(json_encode(array("error", "Cannot get instagram data")));
        }
        else{
        echo BWGELibrary::delimit_wd_output($data);
        }
      }
      wp_die();
    break;
    case 'bwge_addFacebookGallery':
      $album_url = BWGELibrary::get('album_url');
      $album_limit = BWGELibrary::get('album_limit');
      $content_type = BWGELibrary::get('content_type');

      $album_data = BWGELibraryEmbed::get_facebook_album_data($album_url, $album_limit);
      $files_valid = BWGELibraryEmbed::get_facebook_valid_data_for_album($album_data, $content_type);
      echo json_encode($files_valid);
      wp_die();
    break;
    default:
      die('Nothing to add');
    break;
  }
}

function bwge_edit_tag() {
  global $bwge_permissions;
  if (function_exists('current_user_can')) {
    if (!current_user_can($bwge_permissions)) {
      die('Access Denied');
    }
  }
  else {
    die('Access Denied');
  }
  require_once(WD_BWGE_DIR . '/framework/BWGELibrary.php');
  if (!BWGELibrary::verify_nonce('')) {
    die('Sorry, your nonce did not verify.');
  }
  require_once(WD_BWGE_DIR . '/admin/controllers/BWGEControllerTags_bwge.php');
  $controller_class = 'BWGEControllerTags_bwge';
  $controller = new $controller_class();
  $controller->edit_tag();
}

function bwge_ajax() {
  global $bwge_permissions;
  require_once(WD_BWGE_DIR . '/framework/BWGELibrary.php');
  require_once(WD_BWGE_DIR . '/framework/BWGEHelper.php');
	require_once(WD_BWGE_DIR . '/admin/controllers/BWGEController.php');
	require_once(WD_BWGE_DIR . '/admin/models/BWGEModel.php');
	require_once(WD_BWGE_DIR . '/admin/views/BWGEView.php');
  $page = BWGELibrary::get('action');
  if (function_exists('current_user_can')) {
    if (!current_user_can($bwge_permissions)) {
      die('Access Denied');
    }
  }
  else {
    die('Access Denied');
  }
  if ($page != '' && (($page == 'BWGEShortcode_bwge') || ($page == 'addAlbumsGalleries_bwge') || ($page == 'editThumb_bwge') || ($page == 'addTags_bwge'))) {
    if (!BWGELibrary::verify_nonce($page) && ($page != 'BWGEShortcode_bwge')) {
      die('Sorry, your nonce did not verify.');
    }

    require_once(WD_BWGE_DIR . '/admin/controllers/BWGEController' . ucfirst($page) . '.php');
    $controller_class = 'BWGEController' . ucfirst($page);
    $controller = new $controller_class();
    $controller->execute();
  }
}

function bwge_create_taxonomy() {
  register_taxonomy('bwge_tag', 'post', array(
    'public' => TRUE,
    'show_ui' => FALSE,
    'show_in_nav_menus' => FALSE,
    'show_tagcloud' => TRUE,
    'hierarchical' => FALSE,
    'label' => 'Photo Gallery',
    'query_var' => TRUE,
    'rewrite' => TRUE));
}
add_action('init', 'bwge_create_taxonomy', 0);


// shortcode for ecommerce
function bwge_shortcode_ecommerce($params) {
	shortcode_atts(array(
		'page' => __("checkout","bwge_back"),
	 ), $params,'Gallery_Ecommerce');

	ob_start();
	bwge_front_end_ecommerce($params);
	return str_replace(array("\r\n", "\n", "\r"), '', ob_get_clean());
}
add_shortcode('Gallery_Ecommerce', 'bwge_shortcode_ecommerce');


// gallery shortcode
function gallery_ecommerce($id) {
  echo bwge_shortcode(array('id' => $id));
}

function bwge_shortcode($params) {
  if (isset($params['id'])) {
    global $wpdb;
    $shortcode = $wpdb->get_var($wpdb->prepare("SELECT tagtext FROM " . $wpdb->prefix . "bwge_shortcode WHERE id='%d'", $params['id']));
    if ($shortcode) {
      $shortcode_params = explode('" ', $shortcode);
      foreach ($shortcode_params as $shortcode_param) {
        $shortcode_param = str_replace('"', '', $shortcode_param);
        $shortcode_elem = explode('=', $shortcode_param);
        $params[str_replace(' ', '', $shortcode_elem[0])] = $shortcode_elem[1];
      }
    }
    else {
      die();
    }
  }
  shortcode_atts(array(
    'gallery_type' => 'thumbnails',
    'theme_id' => 1,
  ), $params);

  switch ($params['gallery_type']) {
    case 'thumbnails': {
      shortcode_atts(array(
        'gallery_id' => 1,
        'sort_by' => 'order',
        'order_by' => 'asc',
        'show_search_box' => 0,
        'search_box_width' => 180,
        'image_column_number' => 3,
        'images_per_page' => 15,
        'image_title' => 'none',
        'ecommerce_icon' => 'show',
        'image_enable_page' => 1,
        'thumb_width' => 120,
        'thumb_height' => 90,
        'image_width' => 800,
        'image_height' => 600,
        'image_effect' => 'fade',
        'enable_image_filmstrip' => 0,
        'image_filmstrip_height' => 50,
        'enable_image_ctrl_btn' => 1,
        'enable_image_fullscreen' => 1,
        'enable_comment_social' => 1,
        'enable_image_facebook' => 1,
        'enable_image_twitter' => 1,
        'enable_image_google' => 1,
        'enable_image_ecommerce' => 1,
        'watermark_type' => 'none',
        'load_more_image_count' => 15,
        'show_tag_box' => 0
      ), $params);
      break;

    }
    case 'slideshow': {
      shortcode_atts(array(
        'gallery_id' => 1,
        'sort_by' => 'order',
        'order_by' => 'asc',
        'slideshow_effect' => 'fade',
        'slideshow_interval' => 5,
        'slideshow_width' => 800,
        'slideshow_height' => 600,
        'enable_slideshow_autoplay' => 0,
        'enable_slideshow_shuffle' => 0,
        'enable_slideshow_ctrl' => 1,
        'enable_slideshow_filmstrip' => 1,
        'slideshow_filmstrip_height' => 70,
        'slideshow_ecommerce_icon' => 1,
        'slideshow_enable_title' => 0,
        'slideshow_title_full_width' => 0,
        'slideshow_title_position' => 'top-right',
        'slideshow_enable_description' => 0,
        'slideshow_description_position' => 'bottom-right',
        'enable_slideshow_music' => 0,
        'slideshow_music_url' => '',

      ), $params);
      break;

    }

    case 'image_browser': {
      shortcode_atts(array(
        'gallery_id' => 1,
        'sort_by' => 'order',
        'order_by' => 'asc',
        'show_search_box' => 0,
        'search_box_width' => 180,
        'image_browser_width' => 800,
        'image_browser_title_enable' => 1,
        'image_browser_description_enable' => 1,
        'watermark_type' => 'none'
      ), $params);
      break;

    }
    case 'album_compact_preview': {
      shortcode_atts(array(
        'album_id' => 1,
        'sort_by' => 'order',
        'show_search_box' => 0,
        'search_box_width' => 180,
        'compuct_album_column_number' => 3,
        'compuct_albums_per_page' => 15,
        'compuct_album_title' => 'hover',
        'compuct_album_view_type' => 'thumbnail',
        'compuct_album_thumb_width' => 120,
        'compuct_album_thumb_height' => 90,
        'compuct_album_image_column_number' => 3,
        'compuct_album_images_per_page' => 15,
        'compuct_album_image_title' => 'none',
        'compuct_album_image_thumb_width' => 120,
        'compuct_album_image_thumb_height' => 120,
        'compuct_album_enable_page' => 1,
        'watermark_type' => 'none',
        'compuct_album_load_more_image_count' => 15,
        'compuct_albums_per_page_load_more' => 15
      ), $params);
      break;

    }
    case 'album_extended_preview': {
      shortcode_atts(array(
        'album_id' => 1,
        'sort_by' => 'order',
        'show_search_box' => 0,
        'search_box_width' => 180,
        'extended_albums_per_page' => 15,
        'extended_album_height' => 150,
        'extended_album_description_enable' => 1,
        'extended_album_view_type' => 'thumbnail',
        'extended_album_thumb_width' => 120,
        'extended_album_thumb_height' => 90,
        'extended_album_image_column_number' => 3,
        'extended_album_images_per_page' => 15,
        'extended_album_image_title' => 'none',
        'extended_album_image_thumb_width' => 120,
        'extended_album_image_thumb_height' => 90,
        'extended_album_enable_page' => 1,
        'watermark_type' => 'none',
        'extended_album_load_more_image_count' => 15,
        'extended_albums_per_page_load_more' => 15
      ), $params);
      break;

    }

    default: {
      die();
    }
  }

  if ($params['gallery_type'] != 'slideshow') {
    shortcode_atts(array(
        'popup_fullscreen' => 0,
        'popup_autoplay' => 0,
        'popup_width' => 800,
        'popup_height' => 600,
        'popup_effect' => 'fade',
        'popup_interval' => 5,
        'popup_enable_filmstrip' => 0,
        'popup_filmstrip_height' => 70,
        'popup_enable_ctrl_btn' => 1,
        'popup_enable_fullscreen' => 1,
        'popup_enable_info' => 1,
        'popup_info_full_width' => 0,
        'popup_info_always_show' => 0,
        'popup_hit_counter' => 0,
        'popup_enable_rate' => 0,
        'popup_enable_comment' => 1,
        'popup_enable_facebook' => 1,
        'popup_enable_twitter' => 1,
        'popup_enable_google' => 1,
        'popup_enable_pinterest' => 0,
        'popup_enable_tumblr' => 0,
	'popup_enable_ecommerce' => 1,
        'watermark_type' => 'none'
      ), $params);
  }

  switch ($params['watermark_type']) {
    case 'text': {
      shortcode_atts(array(
        'watermark_link' => '',
        'watermark_text' => '',
        'watermark_font_size' => 12,
        'watermark_font' => 'Arial',
        'watermark_color' => 'FFFFFF',
        'watermark_opacity' => 30,
        'watermark_position' => 'bottom-right',
      ), $params);
      break;

    }
    case 'image': {
      shortcode_atts(array(
        'watermark_link' => '',
        'watermark_url' => '',
        'watermark_width' => 120,
        'watermark_height' => 90,
        'watermark_opacity' => 30,
        'watermark_position' => 'bottom-right',
      ), $params);
      break;

    }
    default: {
      $params['watermark_type'] = 'none';
      break;
    }
  }
  foreach ($params as $key => $param) {
    if (empty($param[$key]) == FALSE) {
      $param[$key] = esc_html(addslashes($param[$key]));
    }
  }
  ob_start();
  bwge_front_end($params);
  return str_replace(array("\r\n", "\n", "\r"), '', ob_get_clean());
  // return ob_get_clean();
}
add_shortcode('BWGE_Gallery_Ecommerce', 'bwge_shortcode');

$bwge = 0;
function bwge_front_end($params) {
  if(file_exists(WD_BWGE_DIR . '/frontend/controllers/BWGEController' . ucfirst($params['gallery_type']) . '.php')){
      require_once(WD_BWGE_DIR . '/frontend/controllers/BWGEController' . ucfirst($params['gallery_type']) . '.php');
      $controller_class = 'BWGEController' . ucfirst($params['gallery_type']) . '';
      $controller = new $controller_class();
      global $bwge;
      $controller->execute($params, 1, $bwge);
      $bwge++;
      return;
  }
}
function bwge_front_end_ecommerce($params) {
	require_once(WD_BWGE_DIR . '/framework/BWGEHelper.php');
	require_once(WD_BWGE_DIR . '/framework/BWGECheckoutEmail.php');
	require_once(WD_BWGE_DIR . '/framework/BWGEPaypalstandart.php');
	require_once(WD_BWGE_DIR . '/frontend/controllers/BWGEControllerFrontend.php');
	require_once(WD_BWGE_DIR . '/frontend/models/BWGEModelFrontend.php');
	require_once(WD_BWGE_DIR . '/frontend/views/BWGEViewFrontend.php');
	require_once(WD_BWGE_DIR . '/frontend/controllers/BWGEController' . ucfirst($params['page']) . '.php');

	$controller_class = 'BWGEController' . ucfirst($params['page']) . '';
	$controller = new $controller_class($params);
	$controller->execute();
	return;
}

// Add the Photo Gallery button.
function bwge_add_button($buttons) {
  array_push($buttons, "bwge_mce");
  return $buttons;
}

// Register Photo Gallery button.
function bwge_register($plugin_array) {
  $url = WD_BWGE_URL . '/js/bwge_editor_button.js';
  $plugin_array["bwge_mce"] = $url;
  return $plugin_array;
}

function bwge_admin_ajax() {
  ?>
  <script>
    var bwge_admin_ajax = '<?php echo add_query_arg(array('action' => 'BWGEShortcode_bwge'), admin_url('admin-ajax.php')); ?>';
    var bwge_plugin_url = '<?php echo WD_BWGE_URL; ?>';
  </script>
  <?php
}
add_action('admin_head', 'bwge_admin_ajax');

// Add the Photo Gallery button to editor.
add_action('wp_ajax_BWGEShortcode_bwge', 'bwge_ajax');
add_filter('mce_external_plugins', 'bwge_register');
add_filter('mce_buttons', 'bwge_add_button', 0);



// Photo Gallery Facebook
define('WD_bwge_FB_DIR', WP_PLUGIN_DIR . "/photo-gallery-facebook");
add_action('admin_init', 'bwge_check_photo_gallery_facebook');

function bwge_pointer_init() {
    include_once (WD_BWGE_DIR .'/includes/bwge_pointers.php');
    new BWGE_pointers();
}
add_action('admin_init', 'bwge_pointer_init');

function bwge_check_photo_gallery_facebook() {
  global $wd_bwge_fb;
  if (file_exists(WD_BWGE_DIR . "/photo-gallery-facebook.php")) {
    if (is_plugin_active('photo-gallery-facebook/photo-gallery-facebook.php')) {
      $wd_bwge_fb = TRUE;
    }
  }
}

// Activate plugin.
function bwge_activate() {
  delete_transient('bwge_update_check');
  global $wpdb;

  $bwge_shortcode = "CREATE TABLE IF NOT EXISTS `" . $wpdb->prefix . "bwge_shortcode` (
    `id` bigint(20) NOT NULL,
    `tagtext` mediumtext NOT NULL,
    PRIMARY KEY (`id`)
  ) DEFAULT CHARSET=utf8;";
  $wpdb->query($bwge_shortcode);
  $bwge_gallery = "CREATE TABLE IF NOT EXISTS `" . $wpdb->prefix . "bwge_gallery` (
    `id` bigint(20) NOT NULL AUTO_INCREMENT,
    `name` varchar(255) NOT NULL,
    `slug` varchar(255) NOT NULL,
    `description` mediumtext NOT NULL,
    `page_link` mediumtext NOT NULL,
    `preview_image` mediumtext NOT NULL,
    `random_preview_image` mediumtext NOT NULL,
    `order` bigint(20) NOT NULL,
    `author` bigint(20) NOT NULL,
    `published` tinyint(1) NOT NULL,
    `gallery_type` varchar(32) NOT NULL,
    `gallery_source` varchar(256) NOT NULL,
    `autogallery_image_number` int(4) NOT NULL,
    `update_flag` varchar(32) NOT NULL,
    PRIMARY KEY (`id`)
  ) DEFAULT CHARSET=utf8;";
  $wpdb->query($bwge_gallery);
  $bwge_album = "CREATE TABLE IF NOT EXISTS `" . $wpdb->prefix . "bwge_album` (
    `id` bigint(20) NOT NULL AUTO_INCREMENT,
    `name` varchar(255) NOT NULL,
    `slug` varchar(255) NOT NULL,
    `description` mediumtext NOT NULL,
    `preview_image` mediumtext NOT NULL,
    `random_preview_image` mediumtext NOT NULL,
    `order` bigint(20) NOT NULL,
    `author` bigint(20) NOT NULL,
    `published` tinyint(1) NOT NULL,
    PRIMARY KEY (`id`)
  ) DEFAULT CHARSET=utf8;";
  $wpdb->query($bwge_album);
  $bwge_album_gallery = "CREATE TABLE IF NOT EXISTS `" . $wpdb->prefix . "bwge_album_gallery` (
    `id` bigint(20) NOT NULL AUTO_INCREMENT,
    `album_id` bigint(20) NOT NULL,
    `is_album` tinyint(1) NOT NULL,
    `alb_gal_id` bigint(20) NOT NULL,
    `order` bigint(20) NOT NULL,
    PRIMARY KEY (`id`)
  ) DEFAULT CHARSET=utf8;";
  $wpdb->query($bwge_album_gallery);
  $bwge_image = "CREATE TABLE IF NOT EXISTS `" . $wpdb->prefix . "bwge_image` (
    `id` bigint(20) NOT NULL AUTO_INCREMENT,
    `gallery_id` bigint(20) NOT NULL,
    `slug` longtext NOT NULL,
    `filename` varchar(255) NOT NULL,
    `image_url` mediumtext NOT NULL,
    `thumb_url` mediumtext NOT NULL,
    `description` mediumtext NOT NULL,
    `alt` mediumtext NOT NULL,
    `date` varchar(128) NOT NULL,
    `size` varchar(128) NOT NULL,
    `filetype` varchar(128) NOT NULL,
    `resolution` varchar(128) NOT NULL,
    `author` bigint(20) NOT NULL,
    `order` bigint(20) NOT NULL,
    `published` tinyint(1) NOT NULL,
    `comment_count` bigint(20) NOT NULL,
    `avg_rating` float(20) NOT NULL,
    `rate_count` bigint(20) NOT NULL,
    `hit_count` bigint(20) NOT NULL,
    `redirect_url` varchar(255) NOT NULL,
    `pricelist_id` bigint(20) NOT NULL,

    PRIMARY KEY (`id`)
  ) DEFAULT CHARSET=utf8;";
  $wpdb->query($bwge_image);
  $bwge_image_tag = "CREATE TABLE IF NOT EXISTS `" . $wpdb->prefix . "bwge_image_tag` (
    `id` bigint(20) NOT NULL AUTO_INCREMENT,
    `tag_id` bigint(20) NOT NULL,
    `image_id` bigint(20) NOT NULL,
    `gallery_id` bigint(20) NOT NULL,
    PRIMARY KEY (`id`)
  ) DEFAULT CHARSET=utf8;";
  $wpdb->query($bwge_image_tag);
  $bwge_option = "CREATE TABLE IF NOT EXISTS `" . $wpdb->prefix . "bwge_option` (
    `id` bigint(20) NOT NULL AUTO_INCREMENT,
    `images_directory` mediumtext NOT NULL,

    `masonry` varchar(255) NOT NULL,

    `mosaic` varchar(255) NOT NULL,
    `resizable_mosaic` tinyint(1) NOT NULL,
    `mosaic_total_width` int(4) NOT NULL,
    `image_column_number` int(4) NOT NULL,
    `images_per_page` int(4) NOT NULL,
    `thumb_width` int(4) NOT NULL,
    `thumb_height` int(4) NOT NULL,
    `upload_thumb_width` int(4) NOT NULL,
    `upload_thumb_height` int(4) NOT NULL,
    `image_enable_page` tinyint(1) NOT NULL,
    `image_title_show_hover` varchar(20) NOT NULL,
    `ecommerce_icon_show_hover` varchar(20) NOT NULL,

    `album_column_number` int(4) NOT NULL,
    `albums_per_page` int(4) NOT NULL,
    `album_title_show_hover` varchar(8) NOT NULL,
    `album_thumb_width` int(4) NOT NULL,
    `album_thumb_height` int(4) NOT NULL,
    `album_enable_page` tinyint(1) NOT NULL,
    `extended_album_height` int(4) NOT NULL,
    `extended_album_description_enable` tinyint(1) NOT NULL,

    `image_browser_width` int(4) NOT NULL,
    `image_browser_title_enable` tinyint(1) NOT NULL,
    `image_browser_description_enable` tinyint(1) NOT NULL,

    `blog_style_width` int(4) NOT NULL,
    `blog_style_title_enable` tinyint(1) NOT NULL,
    `blog_style_images_per_page` int(4) NOT NULL,
    `blog_style_enable_page` tinyint(1) NOT NULL,

    `slideshow_type` varchar(16) NOT NULL,
    `slideshow_interval` int(4) NOT NULL,
    `slideshow_width` int(4) NOT NULL,
    `slideshow_height` int(4) NOT NULL,
    `slideshow_enable_autoplay` tinyint(1) NOT NULL,
    `slideshow_enable_shuffle` tinyint(1) NOT NULL,
    `slideshow_enable_ctrl` tinyint(1) NOT NULL,
    `slideshow_enable_filmstrip` tinyint(1) NOT NULL,
    `slideshow_filmstrip_height` int(4) NOT NULL,
    `slideshow_enable_title` tinyint(1) NOT NULL,
    `slideshow_title_position` varchar(16) NOT NULL,
    `slideshow_enable_description` tinyint(1) NOT NULL,
    `slideshow_description_position` varchar(16) NOT NULL,
    `slideshow_enable_music` tinyint(1) NOT NULL,
    `slideshow_ecommerce_icon` tinyint(1) NOT NULL,
    `slideshow_audio_url` varchar(255) NOT NULL,

    `popup_width` int(4) NOT NULL,
    `popup_height` int(4) NOT NULL,
    `popup_type` varchar(16) NOT NULL,
    `popup_interval` int(4) NOT NULL,
    `popup_enable_filmstrip` tinyint(1) NOT NULL,
    `popup_filmstrip_height` int(4) NOT NULL,
    `popup_enable_ctrl_btn` tinyint(1) NOT NULL,
    `popup_enable_fullscreen` tinyint(1) NOT NULL,
    `popup_enable_info` tinyint(1) NOT NULL,
    `popup_info_always_show` tinyint(1) NOT NULL,
    `popup_enable_rate` tinyint(1) NOT NULL,
    `popup_enable_comment` tinyint(1) NOT NULL,
    `popup_enable_email` tinyint(1) NOT NULL,
    `popup_enable_captcha` tinyint(1) NOT NULL,
    `popup_enable_download` tinyint(1) NOT NULL,
    `popup_enable_fullsize_image` tinyint(1) NOT NULL,
    `popup_enable_facebook` tinyint(1) NOT NULL,
    `popup_enable_twitter` tinyint(1) NOT NULL,
    `popup_enable_google` tinyint(1) NOT NULL,
    `popup_enable_ecommerce` tinyint(1) NOT NULL,

    `watermark_type` varchar(8) NOT NULL,
    `watermark_position` varchar(16) NOT NULL,
    `watermark_width` int(4) NOT NULL,
    `watermark_height` int(4) NOT NULL,
    `watermark_url` mediumtext NOT NULL,
    `watermark_text` mediumtext NOT NULL,
    `watermark_link` mediumtext NOT NULL,
    `watermark_font_size` int(4) NOT NULL,
    `watermark_font` varchar(16) NOT NULL,
    `watermark_color` varchar(8) NOT NULL,
    `watermark_opacity` int(4) NOT NULL,

    `built_in_watermark_type` varchar(16) NOT NULL,
    `built_in_watermark_position` varchar(16) NOT NULL,
    `built_in_watermark_size` int(4) NOT NULL,
    `built_in_watermark_url` mediumtext NOT NULL,
    `built_in_watermark_text` mediumtext NOT NULL,
    `built_in_watermark_font_size` int(4) NOT NULL,
    `built_in_watermark_font` varchar(16) NOT NULL,
    `built_in_watermark_color` varchar(8) NOT NULL,
    `built_in_watermark_opacity` int(4) NOT NULL,

    `image_right_click` tinyint(1) NOT NULL,
    `popup_fullscreen` tinyint(1) NOT NULL,
    `gallery_role` tinyint(1) NOT NULL,
    `album_role` tinyint(1) NOT NULL,
    `image_role` tinyint(1) NOT NULL,
    `popup_autoplay` tinyint(1) NOT NULL,
    `album_view_type` varchar(16) NOT NULL,
    `popup_enable_pinterest` tinyint(1) NOT NULL,
    `popup_enable_tumblr` tinyint(1) NOT NULL,
    `show_search_box` tinyint(1) NOT NULL,
    `search_box_width` int(4) NOT NULL,
    `preload_images` tinyint(1) NOT NULL,
    `preload_images_count` int(4) NOT NULL,
    `thumb_click_action` varchar(16) NOT NULL,
    `thumb_link_target` tinyint(1) NOT NULL,
    `comment_moderation` tinyint(1) NOT NULL,
    `popup_hit_counter` tinyint(1) NOT NULL,
    `enable_ML_import` tinyint(1) NOT NULL,
    `showthumbs_name` tinyint(1) NOT NULL,
    `show_album_name` tinyint(1) NOT NULL,
    `show_image_counts` tinyint(1) NOT NULL,
    `upload_img_width` int(4) NOT NULL,
    `upload_img_height` int(4) NOT NULL,
    `play_icon` tinyint(1) NOT NULL,
    `show_masonry_thumb_description` tinyint(1) NOT NULL,
    `slideshow_title_full_width` tinyint(1) NOT NULL,
    `popup_info_full_width` tinyint(1) NOT NULL,
    `show_sort_images` tinyint(1) NOT NULL,
    `autoupdate_interval` int(4) NOT NULL,
    `instagram_access_token` varchar(128) NOT NULL,
    `description_tb` tinyint(1) NOT NULL,
    `enable_seo` tinyint(1) NOT NULL,
    `autohide_lightbox_navigation` tinyint(1) NOT NULL,
    `autohide_slideshow_navigation` tinyint(1) NOT NULL,
    `read_metadata` tinyint(1) NOT NULL,
    `enable_loop` tinyint(1) NOT NULL,
    `enable_addthis` tinyint(1) NOT NULL,
    `addthis_profile_id` varchar(66) NOT NULL,
    `carousel_interval` int(4) NOT NULL,
    `carousel_width` int(4) NOT NULL,
    `carousel_height` int(4) NOT NULL,
    `carousel_image_column_number` int(4) NOT NULL,
    `carousel_image_par` varchar(32) NOT NULL,
    `carousel_enable_title` tinyint(1) NOT NULL,
    `carousel_ecommerce_icon` tinyint(1) NOT NULL,
    `carousel_enable_autoplay` tinyint(1) NOT NULL,
    `carousel_r_width` int(4) NOT NULL,
    `carousel_fit_containerWidth` tinyint(1) NOT NULL,
    `carousel_prev_next_butt` tinyint(1) NOT NULL,
    `carousel_play_pause_butt` tinyint(1) NOT NULL,
    `permissions` varchar(20) NOT NULL,
    `facebook_app_id` varchar(64) NOT NULL,
    `facebook_app_secret` varchar(64) NOT NULL,
    `show_tag_box` tinyint(1) NOT NULL,
    `show_hide_custom_post` tinyint(1) NOT NULL,
    `show_hide_post_meta` tinyint(1) NOT NULL,
    `placeholder` varchar(32) NOT NULL,
    PRIMARY KEY (`id`)
  ) DEFAULT CHARSET=utf8;";
  $wpdb->query($bwge_option);
  $bwge_theme = "CREATE TABLE IF NOT EXISTS `" . $wpdb->prefix . "bwge_theme` (
    `id` bigint(20) NOT NULL AUTO_INCREMENT,
    `name` varchar(255) NOT NULL,
    `options` longtext NOT NULL,
    `default_theme` tinyint(1) NOT NULL,
    PRIMARY KEY (`id`)
  ) DEFAULT CHARSET=utf8;";
  $wpdb->query($bwge_theme);
  $bwge_image_comment = "CREATE TABLE IF NOT EXISTS `" . $wpdb->prefix . "bwge_image_comment` (
    `id` bigint(20) NOT NULL AUTO_INCREMENT,
    `image_id` bigint(20) NOT NULL,
    `name` varchar(255) NOT NULL,
    `date` varchar(64) NOT NULL,
    `comment` mediumtext NOT NULL,
    `url` mediumtext NOT NULL,
    `mail` mediumtext NOT NULL,
    `published` tinyint(1) NOT NULL,
    PRIMARY KEY (`id`)
  ) DEFAULT CHARSET=utf8;";
  $wpdb->query($bwge_image_comment);

  $bwge_image_rate = "CREATE TABLE IF NOT EXISTS `" . $wpdb->prefix . "bwge_image_rate` (
    `id` bigint(20) NOT NULL AUTO_INCREMENT,
    `image_id` bigint(20) NOT NULL,
    `rate` float(16) NOT NULL,
    `ip` varchar(64) NOT NULL,
    `date` varchar(64) NOT NULL,
    PRIMARY KEY (`id`)
  ) DEFAULT CHARSET=utf8;";
  $wpdb->query($bwge_image_rate);

  $upload_dir = wp_upload_dir();
  if (!is_dir($upload_dir['basedir'] . '/' . plugin_basename(dirname(__FILE__)))) {
    mkdir($upload_dir['basedir'] . '/' . plugin_basename(dirname(__FILE__)), 0777);
  }
  $exists_default = $wpdb->get_var('SELECT count(id) FROM ' . $wpdb->prefix . 'bwge_option');
  if (!$exists_default) {
    $save = $wpdb->insert($wpdb->prefix . 'bwge_option', array(
      'id' => 1,
      'images_directory' => str_replace(ABSPATH, '', $upload_dir['basedir']),

      'masonry' => 'vertical',
      'mosaic' => 'vertical',
      'resizable_mosaic' => 0,
      'mosaic_total_width'=> 100,
      'image_column_number' => 5,
      'images_per_page' => 30,
      'thumb_width' => 180,
      'thumb_height' => 90,
      'upload_thumb_width' => 300,
      'upload_thumb_height' => 300,
      'image_enable_page' => 1,
      'image_title_show_hover' => 'none',
      'ecommerce_icon_show_hover' => 'show',

      'album_column_number' => 5,
      'albums_per_page' => 30,
      'album_title_show_hover' => 'hover',
      'album_thumb_width' => 120,
      'album_thumb_height' => 90,
      'album_enable_page' => 1,
      'extended_album_height' => 150,
      'extended_album_description_enable' => 1,

      'image_browser_width' => 800,
      'image_browser_title_enable' => 1,
      'image_browser_description_enable' => 1,

      'blog_style_width' => 800,
      'blog_style_title_enable' => 1,
      'blog_style_images_per_page' => 5,
      'blog_style_enable_page' => 1,

      'slideshow_type' => 'fade',
      'slideshow_interval' => 5,
      'slideshow_width' => 800,
      'slideshow_height' => 500,
      'slideshow_enable_autoplay' => 0,
      'slideshow_enable_shuffle' => 0,
      'slideshow_enable_ctrl' => 1,
      'slideshow_enable_filmstrip' => 1,
      'slideshow_filmstrip_height' => 90,
      'slideshow_enable_title' => 0,
      'slideshow_title_position' => 'top-right',
      'slideshow_enable_description' => 0,
      'slideshow_description_position' => 'bottom-right',
      'slideshow_enable_music' => 0,
      'slideshow_audio_url' => '',
      'slideshow_ecommerce_icon' => 1,

      'popup_width' => 800,
      'popup_height' => 500,
      'popup_type' => 'fade',
      'popup_interval' => 5,
      'popup_enable_filmstrip' => 1,
      'popup_filmstrip_height' => 70,
      'popup_enable_ctrl_btn' => 1,
      'popup_enable_fullscreen' => 1,
      'popup_enable_comment' => 1,
      'popup_enable_email' => 0,
      'popup_enable_captcha' => 0,
      'popup_enable_download' => 0,
      'popup_enable_fullsize_image' => 0,
      'popup_enable_facebook' => 1,
      'popup_enable_twitter' => 1,
      'popup_enable_google' => 1,
      'popup_enable_ecommerce' => 1,

      'watermark_type' => 'none',
      'watermark_position' => 'bottom-left',
      'watermark_width' => 90,
      'watermark_height' => 90,
      'watermark_url' => WD_BWGE_URL . '/images/watermark.png',
      'watermark_text' => 'web-dorado.com',
      'watermark_link' => 'https://web-dorado.com',
      'watermark_font_size' => 20,
      'watermark_font' => 'arial',
      'watermark_color' => 'FFFFFF',
      'watermark_opacity' => 30,

      'built_in_watermark_type' => 'none',
      'built_in_watermark_position' => 'middle-center',
      'built_in_watermark_size' => 15,
      'built_in_watermark_url' => WD_BWGE_URL . '/images/watermark.png',
      'built_in_watermark_text' => 'web-dorado.com',
      'built_in_watermark_font_size' => 20,
      'built_in_watermark_font' => 'arial',
      'built_in_watermark_color' => 'FFFFFF',
      'built_in_watermark_opacity' => 30,

      'image_right_click' => 0,
      'popup_fullscreen' => 0,
      'gallery_role' => 0,
      'album_role' => 0,
      'image_role' => 0,
      'popup_autoplay' => 0,
      'album_view_type' => 'thumbnail',
      'popup_enable_pinterest' => 0,
      'popup_enable_tumblr' => 0,
      'show_search_box' => 0,
      'search_box_width' => 180,
      'preload_images' => 0,
      'preload_images_count' => 10,
      'popup_enable_info' => 1,
      'popup_enable_rate' => 0,
      'thumb_click_action' => 'open_lightbox',
      'thumb_link_target' => 1,
      'comment_moderation' => 0,
      'popup_info_always_show' => 0,
      'popup_hit_counter' => 0,
      'enable_ML_import' => 0,
      'showthumbs_name'=> 0,
      'show_album_name'=> 0,
      'show_image_counts'=> 0,
      'upload_img_width' => 1200,
      'upload_img_height' => 1200,
      'play_icon'=> 1,
      'show_masonry_thumb_description' => 0,
      'slideshow_title_full_width' => 0,
      'popup_info_full_width' => 0,
      'show_sort_images' => 0,
      'autoupdate_interval' => 30,
      'instagram_access_token' => '',
      'description_tb' => 0,
      'enable_seo' => 1,
      'autohide_lightbox_navigation' => 1,
      'autohide_slideshow_navigation' => 1,
      'read_metadata' => 1,
      'enable_loop'=> 1,
      'enable_addthis'=> 0,
      'addthis_profile_id'=> '',
      'carousel_interval' => 5,
      'carousel_width' => 300,
      'carousel_height' => 300,
      'carousel_image_column_number' => 5,
      'carousel_image_par' => '0.75',
      'carousel_enable_title' => 0,
      'carousel_enable_autoplay' => 0,
      'carousel_r_width' => 800,
      'carousel_fit_containerWidth' => 1,
      'carousel_prev_next_butt' => 1,
      'carousel_play_pause_butt' => 1,
      'carousel_ecommerce_icon' => 1,
      'permissions' => 'manage_options',
      'facebook_app_id' => '',
      'facebook_app_secret' => '',
      'show_tag_box' => 0,
      'show_hide_custom_post' => 0,
      'show_hide_post_meta' => 0,
      'placeholder' => '',
    ));
  }
  $exists_default = $wpdb->get_var('SELECT count(id) FROM ' . $wpdb->prefix . 'bwge_theme');
  $theme1 = array(
      'thumb_margin' => 4,
      'thumb_padding' => 0,
      'thumb_border_radius' => '0',
      'thumb_border_width' => 0,
      'thumb_border_style' => 'none',
      'thumb_border_color' => 'CCCCCC',
      'thumb_bg_color' => 'FFFFFF',
      'thumbs_bg_color' => 'FFFFFF',
      'thumb_bg_transparent' => 0,
      'thumb_box_shadow' => '0px 0px 0px #888888',
      'thumb_transparent' => 100,
      'thumb_align' => 'center',
      'thumb_hover_effect' => 'scale',
      'thumb_hover_effect_value' => '1.1',
      'thumb_transition' => 1,
      'thumb_title_font_color' => 'CCCCCC',
      'thumb_title_font_style' => 'segoe ui',
      'thumb_title_pos' => 'bottom',
      'thumb_title_font_size' => 16,
      'thumb_title_font_weight' => 'bold',
      'thumb_title_margin' => '2px',
      'thumb_title_shadow' => '0px 0px 0px #888888',

      'page_nav_position' => 'bottom',
      'page_nav_align' => 'center',
      'page_nav_number' => 0,
      'page_nav_font_size' => 12,
      'page_nav_font_style' => 'segoe ui',
      'page_nav_font_color' => '666666',
      'page_nav_font_weight' => 'bold',
      'page_nav_border_width' => 1,
      'page_nav_border_style' => 'solid',
      'page_nav_border_color' => 'E3E3E3',
      'page_nav_border_radius' => '0',
      'page_nav_margin' => '0',
      'page_nav_padding' => '3px 6px',
      'page_nav_button_bg_color' => 'FFFFFF',
      'page_nav_button_bg_transparent' => 100,
      'page_nav_box_shadow' => '0',
      'page_nav_button_transition' => 1,
      'page_nav_button_text' => 0,

      'lightbox_overlay_bg_color' => '000000',
      'lightbox_overlay_bg_transparent' => 70,
      'lightbox_bg_color' => '000000',
      'lightbox_ctrl_btn_pos' => 'bottom',
      'lightbox_ctrl_btn_align' => 'center',
      'lightbox_ctrl_btn_height' => 20,
      'lightbox_ctrl_btn_margin_top' => 10,
      'lightbox_ctrl_btn_margin_left' => 7,
      'lightbox_ctrl_btn_transparent' => 100,
      'lightbox_ctrl_btn_color' => 'FFFFFF',
      'lightbox_toggle_btn_height' => 14,
      'lightbox_toggle_btn_width' => 100,
      'lightbox_ctrl_cont_bg_color' => '000000',
      'lightbox_ctrl_cont_transparent' => 65,
      'lightbox_ctrl_cont_border_radius' => 4,
      'lightbox_close_btn_transparent' => 100,
      'lightbox_close_btn_bg_color' => '000000',
      'lightbox_close_btn_border_width' => 2,
      'lightbox_close_btn_border_radius' => '16px',
      'lightbox_close_btn_border_style' => 'none',
      'lightbox_close_btn_border_color' => 'FFFFFF',
      'lightbox_close_btn_box_shadow' => '0',
      'lightbox_close_btn_color' => 'FFFFFF',
      'lightbox_close_btn_size' => 10,
      'lightbox_close_btn_width' => 20,
      'lightbox_close_btn_height' => 20,
      'lightbox_close_btn_top' => '-10',
      'lightbox_close_btn_right' => '-10',
      'lightbox_close_btn_full_color' => 'FFFFFF',
      'lightbox_rl_btn_bg_color' => '000000',
      'lightbox_rl_btn_border_radius' => '20px',
      'lightbox_rl_btn_border_width' => 0,
      'lightbox_rl_btn_border_style' => 'none',
      'lightbox_rl_btn_border_color' => 'FFFFFF',
      'lightbox_rl_btn_box_shadow' => '',
      'lightbox_rl_btn_color' => 'FFFFFF',
      'lightbox_rl_btn_height' => 40,
      'lightbox_rl_btn_width' => 40,
      'lightbox_rl_btn_size' => 20,
      'lightbox_close_rl_btn_hover_color' => 'CCCCCC',
      'lightbox_comment_pos' => 'left',
      'lightbox_comment_width' => 400,
      'lightbox_comment_bg_color' => '000000',
      'lightbox_comment_font_color' => 'CCCCCC',
      'lightbox_comment_font_style' => 'segoe ui',
      'lightbox_comment_font_size' => 12,
      'lightbox_comment_button_bg_color' => '616161',
      'lightbox_comment_button_border_color' => '666666',
      'lightbox_comment_button_border_width' => 1,
      'lightbox_comment_button_border_style' => 'none',
      'lightbox_comment_button_border_radius' => '3px',
      'lightbox_comment_button_padding' => '3px 10px',
      'lightbox_comment_input_bg_color' => '333333',
      'lightbox_comment_input_border_color' => '666666',
      'lightbox_comment_input_border_width' => 1,
      'lightbox_comment_input_border_style' => 'none',
      'lightbox_comment_input_border_radius' => '0',
      'lightbox_comment_input_padding' => '2px',
      'lightbox_comment_separator_width' => 1,
      'lightbox_comment_separator_style' => 'solid',
      'lightbox_comment_separator_color' => '383838',
      'lightbox_comment_author_font_size' => 14,
      'lightbox_comment_date_font_size' => 10,
      'lightbox_comment_body_font_size' => 12,
      'lightbox_comment_share_button_color' => 'CCCCCC',
      'lightbox_filmstrip_pos' => 'top',
      'lightbox_filmstrip_rl_bg_color' => '3B3B3B',
      'lightbox_filmstrip_rl_btn_size' => 20,
      'lightbox_filmstrip_rl_btn_color' => 'FFFFFF',
      'lightbox_filmstrip_thumb_margin' => '0 1px',
      'lightbox_filmstrip_thumb_border_width' => 1,
      'lightbox_filmstrip_thumb_border_style' => 'solid',
      'lightbox_filmstrip_thumb_border_color' => '000000',
      'lightbox_filmstrip_thumb_border_radius' => '0',
      'lightbox_filmstrip_thumb_deactive_transparent' => 80,
      'lightbox_filmstrip_thumb_active_border_width' => 0,
      'lightbox_filmstrip_thumb_active_border_color' => 'FFFFFF',
      'lightbox_rl_btn_style' => 'fa-chevron',
      'lightbox_rl_btn_transparent' => 80,

      'album_compact_back_font_color' => '000000',
      'album_compact_back_font_style' => 'segoe ui',
      'album_compact_back_font_size' => 16,
      'album_compact_back_font_weight' => 'bold',
      'album_compact_back_padding' => '0',
      'album_compact_title_font_color' => 'CCCCCC',
      'album_compact_title_font_style' => 'segoe ui',
      'album_compact_thumb_title_pos' => 'bottom',
      'album_compact_title_font_size' => 16,
      'album_compact_title_font_weight' => 'bold',
      'album_compact_title_margin' => '2px',
      'album_compact_title_shadow' => '0px 0px 0px #888888',
      'album_compact_thumb_margin' => 4,
      'album_compact_thumb_padding' => 0,
      'album_compact_thumb_border_radius' => '0',
      'album_compact_thumb_border_width' => 0,
      'album_compact_thumb_border_style' => 'none',
      'album_compact_thumb_border_color' => 'CCCCCC',
      'album_compact_thumb_bg_color' => 'FFFFFF',
      'album_compact_thumbs_bg_color' => 'FFFFFF',
      'album_compact_thumb_bg_transparent' => 0,
      'album_compact_thumb_box_shadow' => '0px 0px 0px #888888',
      'album_compact_thumb_transparent' => 100,
      'album_compact_thumb_align' => 'center',
      'album_compact_thumb_hover_effect' => 'scale',
      'album_compact_thumb_hover_effect_value' => '1.1',
      'album_compact_thumb_transition' => 0,

      'album_extended_thumb_margin' => 2,
      'album_extended_thumb_padding' => 0,
      'album_extended_thumb_border_radius' => '0',
      'album_extended_thumb_border_width' => 0,
      'album_extended_thumb_border_style' => 'none',
      'album_extended_thumb_border_color' => 'CCCCCC',
      'album_extended_thumb_bg_color' => 'FFFFFF',
      'album_extended_thumbs_bg_color' => 'FFFFFF',
      'album_extended_thumb_bg_transparent' => 0,
      'album_extended_thumb_box_shadow' => '',
      'album_extended_thumb_transparent' => 100,
      'album_extended_thumb_align' => 'left',
      'album_extended_thumb_hover_effect' => 'scale',
      'album_extended_thumb_hover_effect_value' => '1.1',
      'album_extended_thumb_transition' => 0,
      'album_extended_back_font_color' => '000000',
      'album_extended_back_font_style' => 'segoe ui',
      'album_extended_back_font_size' => 20,
      'album_extended_back_font_weight' => 'bold',
      'album_extended_back_padding' => '0',
      'album_extended_div_bg_color' => 'FFFFFF',
      'album_extended_div_bg_transparent' => 0,
      'album_extended_div_border_radius' => '0 0 0 0',
      'album_extended_div_margin' => '0 0 5px 0',
      'album_extended_div_padding' => 10,
      'album_extended_div_separator_width' => 1,
      'album_extended_div_separator_style' => 'solid',
      'album_extended_div_separator_color' => 'E0E0E0',
      'album_extended_thumb_div_bg_color' => 'FFFFFF',
      'album_extended_thumb_div_border_radius' => '0',
      'album_extended_thumb_div_border_width' => 1,
      'album_extended_thumb_div_border_style' => 'solid',
      'album_extended_thumb_div_border_color' => 'E8E8E8',
      'album_extended_thumb_div_padding' => '5px',
      'album_extended_text_div_bg_color' => 'FFFFFF',
      'album_extended_text_div_border_radius' => '0',
      'album_extended_text_div_border_width' => 1,
      'album_extended_text_div_border_style' => 'solid',
      'album_extended_text_div_border_color' => 'E8E8E8',
      'album_extended_text_div_padding' => '5px',
      'album_extended_title_span_border_width' => 1,
      'album_extended_title_span_border_style' => 'none',
      'album_extended_title_span_border_color' => 'CCCCCC',
      'album_extended_title_font_color' => '000000',
      'album_extended_title_font_style' => 'segoe ui',
      'album_extended_title_font_size' => 16,
      'album_extended_title_font_weight' => 'bold',
      'album_extended_title_margin_bottom' => 2,
      'album_extended_title_padding' => '2px',
      'album_extended_desc_span_border_width' => 1,
      'album_extended_desc_span_border_style' => 'none',
      'album_extended_desc_span_border_color' => 'CCCCCC',
      'album_extended_desc_font_color' => '000000',
      'album_extended_desc_font_style' => 'segoe ui',
      'album_extended_desc_font_size' => 14,
      'album_extended_desc_font_weight' => 'normal',
      'album_extended_desc_padding' => '2px',
      'album_extended_desc_more_color' => 'F2D22E',
      'album_extended_desc_more_size' => 12,

      'masonry_thumb_padding' => 4,
      'masonry_thumb_border_radius' => '0',
      'masonry_thumb_border_width' => 0,
      'masonry_thumb_border_style' => 'none',
      'masonry_thumb_border_color' => 'CCCCCC',
      'masonry_thumbs_bg_color' => 'FFFFFF',
      'masonry_thumb_bg_transparent' => 0,
      'masonry_thumb_transparent' => 100,
      'masonry_thumb_align' => 'center',
      'masonry_thumb_hover_effect' => 'scale',
      'masonry_thumb_hover_effect_value' => '1.1',
      'masonry_thumb_transition' => 0,

      'slideshow_cont_bg_color' => '000000',
      'slideshow_ecommerce_icon_color' => '363434',
      'slideshow_ecommerce_icon_size' => '30',
      'slideshow_ecommerce_icon_pos' => 'top_left',
      'slideshow_close_btn_transparent' => 100,
      'slideshow_rl_btn_bg_color' => '000000',
      'slideshow_rl_btn_border_radius' => '20px',
      'slideshow_rl_btn_border_width' => 0,
      'slideshow_rl_btn_border_style' => 'none',
      'slideshow_rl_btn_border_color' => 'FFFFFF',
      'slideshow_rl_btn_box_shadow' => '0px 0px 0px #000000',
      'slideshow_rl_btn_color' => 'FFFFFF',
      'slideshow_rl_btn_height' => 40,
      'slideshow_rl_btn_size' => 20,
      'slideshow_rl_btn_width' => 40,
      'slideshow_close_rl_btn_hover_color' => 'CCCCCC',
      'slideshow_filmstrip_pos' => 'top',
      'slideshow_filmstrip_thumb_border_width' => 1,
      'slideshow_filmstrip_thumb_border_style' => 'solid',
      'slideshow_filmstrip_thumb_border_color' =>  '000000',
      'slideshow_filmstrip_thumb_border_radius' => '0',
      'slideshow_filmstrip_thumb_margin' =>  '0 1px',
      'slideshow_filmstrip_thumb_active_border_width' => 0,
      'slideshow_filmstrip_thumb_active_border_color' => 'FFFFFF',
      'slideshow_filmstrip_thumb_deactive_transparent' => 80,
      'slideshow_filmstrip_rl_bg_color' => '3B3B3B',
      'slideshow_filmstrip_rl_btn_color' => 'FFFFFF',
      'slideshow_filmstrip_rl_btn_size' => 20,
      'slideshow_title_font_size' => 16,
      'slideshow_title_font' => 'segoe ui',
      'slideshow_title_color' => 'FFFFFF',
      'slideshow_title_opacity' => 70,
      'slideshow_title_border_radius' => '5px',
      'slideshow_title_background_color' => '000000',
      'slideshow_title_padding' => '0 0 0 0',
      'slideshow_description_font_size' => 14,
      'slideshow_description_font' => 'segoe ui',
      'slideshow_description_color' => 'FFFFFF',
      'slideshow_description_opacity' => 70,
      'slideshow_description_border_radius' => '0',
      'slideshow_description_background_color' => '000000',
      'slideshow_description_padding' => '5px 10px 5px 10px',
      'slideshow_dots_width' => 12,
      'slideshow_dots_height' => 12,
      'slideshow_dots_border_radius' => '5px',
      'slideshow_dots_background_color' => 'F2D22E',
      'slideshow_dots_margin' => 3,
      'slideshow_dots_active_background_color' => 'FFFFFF',
      'slideshow_dots_active_border_width' => 1,
      'slideshow_dots_active_border_color' => '000000',
      'slideshow_play_pause_btn_size' => 60,
      'slideshow_rl_btn_style' => 'fa-chevron',

      'blog_style_margin' => '2px',
      'blog_style_padding' => '0',
      'blog_style_border_radius' => '0',
      'blog_style_border_width' => 1,
      'blog_style_border_style' => 'solid',
      'blog_style_border_color' => 'F5F5F5',
      'blog_style_bg_color' => 'FFFFFF',
      'blog_style_transparent' => 80,
      'blog_style_box_shadow' => '',
      'blog_style_align' => 'center',
      'blog_style_share_buttons_margin' => '5px auto 10px auto',
      'blog_style_share_buttons_border_radius' => '0',
      'blog_style_share_buttons_border_width' => 0,
      'blog_style_share_buttons_border_style' => 'none',
      'blog_style_share_buttons_border_color' => '000000',
      'blog_style_share_buttons_bg_color' => 'FFFFFF',
      'blog_style_share_buttons_align' => 'right',
      'blog_style_img_font_size' => 16,
      'blog_style_img_font_family' => 'segoe ui',
      'blog_style_img_font_color' => '000000',
      'blog_style_share_buttons_color' => 'B3AFAF',
      'blog_style_share_buttons_bg_transparent' => 0,
      'blog_style_share_buttons_font_size' => 20,

      'image_browser_margin' =>  '2px auto',
      'image_browser_padding' =>  '4px',
      'image_browser_border_radius'=>  '0',
      'image_browser_border_width' =>  1,
      'image_browser_border_style' => 'none',
      'image_browser_border_color' => 'F5F5F5',
      'image_browser_bg_color' => 'EBEBEB',
      'image_browser_box_shadow' => '',
      'image_browser_transparent' => 80,
      'image_browser_align' => 'center',
      'image_browser_image_description_margin' => '0px 5px 0px 5px',
      'image_browser_image_description_padding' => '8px 8px 8px 8px',
      'image_browser_image_description_border_radius' => '0',
      'image_browser_image_description_border_width' => 1,
      'image_browser_image_description_border_style' => 'none',
      'image_browser_image_description_border_color' => 'FFFFFF',
      'image_browser_image_description_bg_color' => 'EBEBEB',
      'image_browser_image_description_align' => 'center',
      'image_browser_img_font_size' => 15,
      'image_browser_img_font_family' => 'segoe ui',
      'image_browser_img_font_color' => '000000',
      'image_browser_full_padding' => '4px',
      'image_browser_full_border_radius' => '0',
      'image_browser_full_border_width' => 2,
      'image_browser_full_border_style' => 'none',
      'image_browser_full_border_color' => 'F7F7F7',
      'image_browser_full_bg_color' => 'F5F5F5',
      'image_browser_full_transparent' => 90,

      'lightbox_info_pos' => 'top',
      'lightbox_info_align' => 'right',
      'lightbox_info_bg_color' => '000000',
      'lightbox_info_bg_transparent' => 70,
      'lightbox_info_border_width' => 1,
      'lightbox_info_border_style' => 'none',
      'lightbox_info_border_color' => '000000',
      'lightbox_info_border_radius' => '5px',
      'lightbox_info_padding' => '5px',
      'lightbox_info_margin' => '15px',
      'lightbox_title_color' => 'FFFFFF',
      'lightbox_title_font_style' => 'segoe ui',
      'lightbox_title_font_weight' => 'bold',
      'lightbox_title_font_size' => 18,
      'lightbox_description_color' => 'FFFFFF',
      'lightbox_description_font_style' => 'segoe ui',
      'lightbox_description_font_weight' => 'normal',
      'lightbox_description_font_size' => 14,

      'lightbox_rate_pos' => 'bottom',
      'lightbox_rate_align' => 'right',
      'lightbox_rate_icon' => 'star',
      'lightbox_rate_color' => 'F9D062',
      'lightbox_rate_size' => 20,
      'lightbox_rate_stars_count' => 5,
      'lightbox_rate_padding' => '15px',
      'lightbox_rate_hover_color' => 'F7B50E',

      'lightbox_hit_pos' => 'bottom',
      'lightbox_hit_align' => 'left',
      'lightbox_hit_bg_color' => '000000',
      'lightbox_hit_bg_transparent' => 70,
      'lightbox_hit_border_width' => 1,
      'lightbox_hit_border_style' => 'none',
      'lightbox_hit_border_color' => '000000',
      'lightbox_hit_border_radius' => '5px',
      'lightbox_hit_padding' => '5px',
      'lightbox_hit_margin' => '0 5px',
      'lightbox_hit_color' => 'FFFFFF',
      'lightbox_hit_font_style' => 'segoe ui',
      'lightbox_hit_font_weight' => 'normal',
      'lightbox_hit_font_size' => 14,
      'masonry_description_font_size' => 12,
			'masonry_description_color' => 'CCCCCC',
			'masonry_description_font_style' => 'segoe ui',

			'album_masonry_back_font_color' => '000000',
      'album_masonry_back_font_style' => 'segoe ui',
      'album_masonry_back_font_size' => 16,
      'album_masonry_back_font_weight' => 'bold',
      'album_masonry_back_padding' => '0',
      'album_masonry_title_font_color' => 'CCCCCC',
      'album_masonry_title_font_style' => 'segoe ui',
      'album_masonry_thumb_title_pos' => 'bottom',
      'album_masonry_title_font_size' => 16,
      'album_masonry_title_font_weight' => 'bold',
      'album_masonry_title_margin' => '2px',
      'album_masonry_title_shadow' => '0px 0px 0px #888888',
      'album_masonry_thumb_margin' => 4,
      'album_masonry_thumb_padding' => 0,
      'album_masonry_thumb_border_radius' => '0',
      'album_masonry_thumb_border_width' => 0,
      'album_masonry_thumb_border_style' => 'none',
      'album_masonry_thumb_border_color' => 'CCCCCC',
      'album_masonry_thumb_bg_color' => 'FFFFFF',
      'album_masonry_thumbs_bg_color' => 'FFFFFF',
      'album_masonry_thumb_bg_transparent' => 0,
      'album_masonry_thumb_box_shadow' => '0px 0px 0px #888888',
      'album_masonry_thumb_transparent' => 100,
      'album_masonry_thumb_align' => 'center',
      'album_masonry_thumb_hover_effect' => 'scale',
      'album_masonry_thumb_hover_effect_value' => '1.1',
      'album_masonry_thumb_transition' => 0,

      'mosaic_thumb_padding' => 4,
      'mosaic_thumb_border_radius' => '0',
      'mosaic_thumb_border_width' => 0,
      'mosaic_thumb_border_style' => 'none',
      'mosaic_thumb_border_color' => 'CCCCCC',
      'mosaic_thumbs_bg_color' => 'FFFFFF',
      'mosaic_thumb_bg_transparent' => 0,
      'mosaic_thumb_transparent' => 100,
      'mosaic_thumb_align' => 'center',
      'mosaic_thumb_hover_effect' => 'scale',
      'mosaic_thumb_hover_effect_value' => '1.1',
      'mosaic_thumb_title_font_color' => 'CCCCCC',
      'mosaic_thumb_title_font_style' => 'segoe ui',
      'mosaic_thumb_title_font_weight' => 'bold',
      'mosaic_thumb_title_margin' => '2px',
      'mosaic_thumb_title_shadow' => '0px 0px 0px #888888',
      'mosaic_thumb_title_font_size' => 16,

      'carousel_cont_bg_color' => '000000',
      'carousel_ecommerce_icon_color' => '363434',
      'carousel_ecommerce_icon_size' => '30',
      'carousel_ecommerce_icon_pos' => 'top_left',
      'carousel_cont_btn_transparent' =>  0,
      'carousel_close_btn_transparent' =>  100,
      'carousel_rl_btn_bg_color' => '000000',
      'carousel_rl_btn_border_radius' => '20px',
      'carousel_rl_btn_border_width' =>  0,
      'carousel_rl_btn_border_style' => 'none',
      'carousel_rl_btn_border_color' => 'FFFFFF',
      'carousel_rl_btn_color' => 'FFFFFF',
      'carousel_rl_btn_height' => 40,
      'carousel_rl_btn_size' => 20,
      'carousel_play_pause_btn_size' => 20,
      'carousel_rl_btn_width' => 40,
      'carousel_close_rl_btn_hover_color' => 'CCCCCC',
      'carousel_rl_btn_style' => 'fa-chevron',
      'carousel_mergin_bottom' => '0.5',
      'carousel_font_family' => 'Arial',
      'carousel_feature_border_width' => 2,
      'carousel_feature_border_style' => 'solid',
      'carousel_feature_border_color' => '5D204F',
      'carousel_caption_background_color' => '000000',
      'carousel_caption_bottom' => 0,
      'carousel_caption_p_mergin' => 0,
      'carousel_caption_p_pedding' => 5,
      'carousel_caption_p_font_weight' => 'bold',
      'carousel_caption_p_font_size' => 14,
      'carousel_caption_p_color' => 'white',
      'carousel_title_opacity' => 100,
      'carousel_title_border_radius' => '5px',
      'mosaic_thumb_transition' => 1,
    );

    $theme1 = json_encode($theme1);

  if (!$exists_default) {
    $wpdb->insert($wpdb->prefix . 'bwge_theme', array(
      'id' => 1,
      'name' => 'Theme 1',
      'options' => $theme1,
      'default_theme' => 1
    ));

  }

  // eommerce tables
  $bwge_pricelists = "CREATE TABLE IF NOT EXISTS `" . $wpdb->prefix . "bwge_pricelists` (
	`id`                            INT(20) 	 NOT NULL AUTO_INCREMENT,
	`title`                         VARCHAR(200) NOT NULL,
	`sections`                      VARCHAR(50)  NOT NULL,
	`manual_description`            LONGTEXT NOT NULL,
	`manual_title`                  VARCHAR(200) NOT NULL,
	`price`                         VARCHAR(200) NOT NULL,
	`shipping_price`                VARCHAR(200) NOT NULL,
	`shipping_type`                 VARCHAR(200) NOT NULL,
	`enable_international_shipping` TINYINT(1)   NOT NULL,
	`international_shipping_price`  VARCHAR(200) NOT NULL,
	`international_shipping_type`   VARCHAR(200) NOT NULL,
	`tax_rate`                      VARCHAR(200) NOT NULL,
	`display_license`               TINYINT(1)   NOT NULL,
	`license_id`          		      INT(20)      NOT NULL,
	`published`      				        TINYINT(1)   NOT NULL DEFAULT '1',

    PRIMARY KEY (`id`)
	) DEFAULT CHARSET=utf8;";
	$wpdb->query($bwge_pricelists);

	$bwge_pricelist_items = "CREATE TABLE IF NOT EXISTS `" . $wpdb->prefix . "bwge_pricelist_items` (
	`id`                     INT(20)      NOT NULL AUTO_INCREMENT,
	`pricelist_id`           INT(20)      NOT NULL,
	`item_name`              VARCHAR(200) NOT NULL,
	`item_price`             VARCHAR(200) NOT NULL,
	`item_longest_dimension` INT(20)      NOT NULL,

    PRIMARY KEY (`id`)
	) DEFAULT CHARSET=utf8;";
	$wpdb->query($bwge_pricelist_items);


	$bwge_parameters = "CREATE TABLE IF NOT EXISTS `" . $wpdb->prefix . "bwge_parameters` (
	`id`             INT(20)      NOT NULL AUTO_INCREMENT,
	`title`          VARCHAR(200) NOT NULL,
	`type`           VARCHAR(200) NOT NULL,
	`default_values` LONGTEXT     NOT NULL,
	`published`      TINYINT(1)   NOT NULL DEFAULT '1',


    PRIMARY KEY (`id`)
	) DEFAULT CHARSET=utf8;";
	$wpdb->query($bwge_parameters);

	$bwge_pricelist_parameters = "CREATE TABLE IF NOT EXISTS `" . $wpdb->prefix . "bwge_pricelist_parameters` (
	`id`                         INT(20)      NOT NULL AUTO_INCREMENT,
	`pricelist_id`               INT(20)      NOT NULL,
	`parameter_id`               INT(20)      NOT NULL,
	`parameter_value`     		 VARCHAR(200) NOT NULL,
	`parameter_value_price`      VARCHAR(200) NOT NULL,
	`parameter_value_price_sign` VARCHAR(1)   NOT NULL,

    PRIMARY KEY (`id`)
	) DEFAULT CHARSET=utf8;";
	$wpdb->query($bwge_pricelist_parameters);


	$bwge_payment_systems = "CREATE TABLE IF NOT EXISTS `" . $wpdb->prefix . "bwge_payment_systems` (
	`id`             INT(20)      NOT NULL AUTO_INCREMENT,
	`name`           VARCHAR(200) NOT NULL,
	`short_name`     VARCHAR(200) NOT NULL,
	`options`        LONGTEXT     NOT NULL,
	`published`      TINYINT(1)   NOT NULL DEFAULT '1',

    PRIMARY KEY (`id`)
	) DEFAULT CHARSET=utf8;";
	$wpdb->query($bwge_payment_systems);

	$bwge_orders = "CREATE TABLE IF NOT EXISTS `" . $wpdb->prefix . "bwge_orders` (
	`id`                        INT(20)      NOT NULL AUTO_INCREMENT,
	`rand_id`                   INT(20)      NOT NULL,
	`checkout_date`             DATETIME     NOT NULL,
	`user_id`                   INT(20)      NOT NULL,
	`status`                    VARCHAR(200) NOT NULL,
	`payment_method`            VARCHAR(200) NOT NULL,
	`currency`                  VARCHAR(200) NOT NULL,
	`currency_sign`             VARCHAR(200) NOT NULL,
	`payment_data`              LONGTEXT     NOT NULL,
	`billing_data_name`         VARCHAR(256) NOT NULL,
	`billing_data_email`        VARCHAR(256) NOT NULL,
	`billing_data_country`      VARCHAR(256) NOT NULL,
	`billing_data_city`         VARCHAR(256) NOT NULL,
	`billing_data_address`      VARCHAR(256) NOT NULL,
	`billing_data_zip_code`     VARCHAR(256) NOT NULL,
	`shipping_data_name`        VARCHAR(256) NOT NULL,
  `shipping_data_country`     VARCHAR(256) NOT NULL,
	`shipping_data_city`        VARCHAR(256) NOT NULL,
	`shipping_data_address`     VARCHAR(256) NOT NULL,
	`shipping_data_zip_code`    VARCHAR(256) NOT NULL,
	`is_email_sent`             TINYINT(1)   NOT NULL,

    PRIMARY KEY (`id`)
	) DEFAULT CHARSET=utf8;";
	$wpdb->query($bwge_orders);


	$bwge_order_images = "CREATE TABLE IF NOT EXISTS `" . $wpdb->prefix . "bwge_order_images` (
	`id`              			 INT(16) UNSIGNED NOT NULL AUTO_INCREMENT,
	`rand_id`         			 INT(16) UNSIGNED NOT NULL,
	`order_id`        			 INT(16) UNSIGNED NOT NULL,
	`user_id`         			 INT(16) UNSIGNED NOT NULL,
	`user_ip_address` 			 VARCHAR(256)     NOT NULL,
	`image_id`       			 INT(16) UNSIGNED NOT NULL,
	`image_name`       			 VARCHAR(256)     NOT NULL,
	`pricelist_id`       		 INT(16) UNSIGNED NOT NULL,
	`pricelist_name`       		 VARCHAR(256)     NOT NULL,
	`price`       			     VARCHAR(256)     NOT NULL,
	`paramemeters_price`       	 VARCHAR(256)     NOT NULL,
	`filename`       			 VARCHAR(256)     NOT NULL,
	`attachement_name`       	 VARCHAR(256)     NOT NULL,
	`products_count`    	     INT(16) UNSIGNED NOT NULL,
	`tax_rate`        			 VARCHAR(256)     NOT NULL,
	`shipping_price`  			 VARCHAR(256)     NOT NULL,
	`shipping_type`  			 VARCHAR(256)     NOT NULL,
	`currency`       			 VARCHAR(200)     NOT NULL,
	`currency_sign`       		 VARCHAR(200)     NOT NULL,
	`parameters`                 VARCHAR(1024)    NOT NULL,
	`pricelist_download_item_id` INT(16) UNSIGNED NOT NULL,
	`item_longest_dimension`     VARCHAR(256)     NOT NULL,


	PRIMARY KEY (`id`)
	) DEFAULT CHARSET=utf8;";
	$wpdb->query($bwge_order_images);

	$bwge_ecommerceoptions = "CREATE TABLE IF NOT EXISTS `" . $wpdb->prefix . "bwge_ecommerceoptions` (
	`id`     INT(16) UNSIGNED NOT NULL AUTO_INCREMENT,
	`name`   VARCHAR(256) NOT NULL,
	`value`  LONGTEXT NOT NULL,

	PRIMARY KEY (`id`)
	) DEFAULT CHARSET=utf8;";
	$wpdb->query($bwge_ecommerceoptions);

	$exist_payment_systems = $wpdb->get_var('SELECT COUNT(id) FROM ' . $wpdb->prefix . 'bwge_payment_systems');
	if(!$exist_payment_systems){
		// insert data
		$bwge_payment_systems_insert = "INSERT INTO  `" . $wpdb->prefix . "bwge_payment_systems` (`id`,  `name`, `options`, `short_name` , `published`) VALUES
		('', 'Without Online Payment', '', 'without_online_payment', 1),
		('', 'Paypal Standard Checkout', '{\"mode\":\"\",\"paypal_email\":\"\"}', 'paypalstandart', 0),
		('', 'Paypal Express Checkout', '{\"mode\":\"\",\"paypal_user\":\"\",\"paypal_password\":\"\",\"paypal_signature\":\"\",\"paypal_skip_final_review\":\"0\",\"paypal_skip_form\":\"0\"}', 'paypalexpress', 0),
		('', 'Stripe', '{\"mode\":\"0\",\"test_publishable_key\":\"\",\"live_publishable_key\":\"\",\"test_secret_key\":\"\",\"live_secret_key\":\"\",\"options\":{\"NAME\":\"1\",\"ADDRESS_LINE_1\":\"1\",\"ADDRESS_LINE_2\":\"1\",\"CITY\":\"1\",\"STATE\":\"1\",\"ADDRESS_COUNTRY\":\"1\",\"ZIP_CODE\":\"1\",\"COUNTRY\":\"1\"}}', 'stripe', 0)";
		$wpdb->query($bwge_payment_systems_insert);
	}

	$exist_options = $wpdb->get_var('SELECT COUNT(id) FROM ' . $wpdb->prefix . 'bwge_ecommerceoptions');
	if(!$exist_options){
		$bwge_ecommerceoptions_insert = "INSERT INTO  `" . $wpdb->prefix . "bwge_ecommerceoptions` (`id`,  `name`, `value`) VALUES
		('', 'country', '' ),
		('', 'currency', 'USD' ),
		('', 'currency_sign', '$' ),
		('', 'checkout_page', '' ),
		('', 'thank_you_page', '' ),
		('', 'cancel_page', '' ),
		('', 'orders_page', '' ),
		('', 'orders_page', '' ),
		('', 'email_header_logo', '' ),
		('', 'email_footer_text', '".get_option( "blogname", "" )."  powered by Gallery Ecommerce' ),
		('', 'email_header_background_color', '#00A0D2' ),
		('', 'email_header_color', '#fff' ),
		('', 'email_recipient_admin','". get_option( "admin_email", "" )."' ),
		('', 'use_user_email_from', '' ),
		('', 'enable_email_admin', '1' ),
		('', 'email_from_admin', '' ),
		('', 'email_from_name_admin', '' ),
		('', 'email_cc_admin', '' ),
		('', 'email_bcc_admin', '' ),
		('', 'email_subject_admin', 'Order details' ),
		('', 'email_mode_admin', '1' ),
		('', 'email_body_admin', 'You received a payment of %%total_amount%% from %%customer_name%%. For more details, visit: %%order_details_page%%' ),

		('', 'enable_email_user', '1' ),
		('', 'email_from_user', '' ),
		('', 'email_from_name_user', '' ),
		('', 'email_cc_user', '' ),
		('', 'email_bcc_user', '' ),
		('', 'email_subject_user', 'Order details' ),
		('', 'email_subject1_user', 'Order notification' ),
		('', 'email_subject2_user', 'Order failed' ),
		('', 'email_mode_user', '1' ),
		('', 'email_body1_user', 'Dear %%customer_name%%, your order has been received. Thank you for your purchase!
                                 You will receive an order confirmation by email.
								 Thanks for shopping at %%site_url%%!' ),
		('', 'email_body_user', 'Dear %%customer_name%%, thank you for your order.
								%%order_details_table%%
								To review your order, please go to %%order_details_page%%.
								Thanks for shopping from %%site_url%%!
								%%shipping_info%%  %%billing_info%%' ),
		('', 'email_body2_user', 'Dear %%customer_name%%.
								 Your payment failed.
								 This could be for a variety of reasons.' ),

		('', 'enable_guest_checkout', '1' ),
		('', 'enable_shipping', '1' ),
		('', 'show_file_in_orders', '1' ),
		('', 'show_digital_items_count', '1' ),
		('', 'digital_download_expiry_days', '' ),
		('', 'show_shipping_billing', '1' )
		";
		$wpdb->query($bwge_ecommerceoptions_insert);


		// create checkout page
		$checkout_page = array(
		 'post_title'    => 'Checkout',
		 'post_name'     => 'bwge Checkout',
		 'post_content'  => '[Gallery_Ecommerce page=checkout ]',
		 'post_status'   => 'publish',
		 'post_author'   => 1,
		 'post_type'     => 'bwge_ecommerce_page',
     'comment_status' => 'closed',
		);
		$checkout_page_id = wp_insert_post( $checkout_page);

		// create thank you page
		$thank_you_page = array(
		 'post_title'    => 'Thank you',
		 'post_name'     => 'bwge Thank you',
		 'post_content'  => '[Gallery_Ecommerce page=thank_you ]',
		 'post_status'   => 'publish',
		 'post_author'   => 1,
		 'post_type'     => 'bwge_ecommerce_page',
     'comment_status' => 'closed',
		);
		$thank_you_page_id = wp_insert_post( $thank_you_page);


		// create cancel page
		$cancel_page = array(
		 'post_title'    => 'Cancel',
		 'post_name'     => 'bwge Cancel',
		 'post_content'  => '[Gallery_Ecommerce page=cancel ]',
		 'post_status'   => 'publish',
		 'post_author'   => 1,
		 'post_type'     => 'bwge_ecommerce_page',
     'comment_status' => 'closed',
		);
		$cancel_page_id = wp_insert_post( $cancel_page );

		// create orders page
		$orders_page = array(
		 'post_title'    => 'Orders',
		 'post_name'     => 'bwge Orders',
		 'post_content'  => '[Gallery_Ecommerce page=orders ]',
		 'post_status'   => 'publish',
		 'post_author'   => 1,
		 'post_type'     => 'bwge_ecommerce_page',
     'comment_status' => 'closed',
		);
		$orders_page_id = wp_insert_post( $orders_page );

		$updated_pages = array($checkout_page_id => "checkout_page", $thank_you_page_id => "thank_you_page", $cancel_page_id => "cancel_page", $orders_page_id => "orders_page");

		foreach($updated_pages as $page_id => $updated_page){
			$data = array();
			$data["value"] = $page_id;
			$where = array("name"=>$updated_page);
			$where_format = $format = array('%s');
			$wpdb->update( $wpdb->prefix . "bwge_ecommerceoptions", $data, $where, $format, $where_format );
		}

	}

  wp_schedule_event(time(), 'bwge_autoupdate_interval', 'bwge_schedule_event_hook');
  $version = get_option("wd_bwge_version");
  $new_version = '1.0.0';
  if ($version && version_compare($version, $new_version, '<')) {
    require_once WD_BWGE_DIR . "/update/bwge_update.php";
    bwge_update($version);
    update_option("wd_bwge_version", $new_version);
    delete_user_meta(get_current_user_id(), 'bwge_galery_ecommerce');
  }
   elseif (!$version) {
    update_user_meta(get_current_user_id(),'bwge_galery_ecommerce', '1');
    add_option("wd_bwge_version", $new_version, '', 'no');
  }
  else {
    add_option("wd_bwge_version", $new_version, '', 'no');
  }
  bwge_create_post_type();
  flush_rewrite_rules();
}

function bwge_global_activate($networkwide) {
  if (function_exists('is_multisite') && is_multisite()) {
    // Check if it is a network activation - if so, run the activation function for each blog id.
    if ($networkwide) {
      global $wpdb;
      $old_blog = $wpdb->blogid;
      // Get all blog ids.
      $blogids = $wpdb->get_col("SELECT blog_id FROM $wpdb->blogs");
      foreach ($blogids as $blog_id) {
        switch_to_blog($blog_id);
        bwge_activate();
      }
      switch_to_blog($old_blog);
      return;
    }
  }
  bwge_activate();
}
register_activation_hook(__FILE__, 'bwge_global_activate');

function bwge_new_blog_added($blog_id, $user_id, $domain, $path, $site_id, $meta ) {
  if (is_plugin_active_for_network('gallery-ecommerce/gallery-ecommerce.php')) {
    global $wpdb;
    $old_blog = $wpdb->blogid;
    switch_to_blog($blog_id);
    bwge_activate();
    switch_to_blog($old_blog);
  }
}
add_action('wpmu_new_blog', 'bwge_new_blog_added', 10, 6);

/*there is no instagram provider for https*/
wp_oembed_add_provider( '#https://instagr(\.am|am\.com)/p/.*#i', 'https://api.instagram.com/oembed', true );

/* On deactivation, remove all functions from the scheduled action hook.*/
function bwge_deactivate() {
  wp_clear_scheduled_hook( 'bwge_schedule_event_hook' );
  flush_rewrite_rules();
}

function bwge_global_deactivate($networkwide) {
  if (function_exists('is_multisite') && is_multisite()) {
    if ($networkwide) {
      global $wpdb;
      // Check if it is a network activation - if so, run the activation function for each blog id.
      $old_blog = $wpdb->blogid;
      // Get all blog ids.
      $blogids = $wpdb->get_col("SELECT blog_id FROM $wpdb->blogs");
      foreach ($blogids as $blog_id) {
        switch_to_blog($blog_id);
        bwge_deactivate();
      }
      switch_to_blog($old_blog);
      return;
    }
  }
  bwge_deactivate();
}
register_deactivation_hook( __FILE__, 'bwge_global_deactivate' );

function bwge_update_hook() {
  $version = get_option("wd_bwge_version");
  $new_version = '1.0.0';
  if ($version && version_compare($version, $new_version, '<')) {
    require_once WD_BWGE_DIR . "/update/bwge_update.php";
    bwge_update($version);
    update_option("wd_bwge_version", $new_version);
  }

}

function bwge_global_update() {
  if (function_exists('is_multisite') && is_multisite()) {
    global $wpdb;
    $old_blog = $wpdb->blogid;
    // Get all blog ids.
    $blogids = $wpdb->get_col("SELECT blog_id FROM $wpdb->blogs");
    foreach ($blogids as $blog_id) {
      switch_to_blog($blog_id);
      bwge_update_hook();
    }
    switch_to_blog($old_blog);
    return;
  }
  bwge_update_hook();
}

if (!isset($_GET['action']) || $_GET['action'] != 'deactivate') {
  add_action('admin_init', 'bwge_global_update');
}

// Plugin styles.
function bwge_styles() {
  wp_admin_css('thickbox');
  wp_enqueue_style('bwge_tables', WD_BWGE_URL . '/css/bwge_tables.css', array(), wd_bwge_version());
  wp_enqueue_style('bwge_admin_main', WD_BWGE_URL . '/css/bwge_ecommerce.css', array(), wd_bwge_version());
}

// Plugin scripts.
function bwge_scripts() {
  wp_enqueue_script('thickbox');
  wp_enqueue_script('bwge_admin', WD_BWGE_URL . '/js/bwge.js', array(), wd_bwge_version());
  wp_enqueue_script( 'bwge_admin_main-js', WD_BWGE_URL . '/js/ecommerce/admin_main.js', array(), wd_bwge_version() );
  wp_localize_script('bwge_admin', 'bwge_objectL10B', array(
    'bwge_field_required'  => __('field is required.', 'bwge_back'),
    'bwge_select_image'  => __('You must select an image file.', 'bwge_back'),
    'bwge_select_audio'  => __('You must select an audio file.', 'bwge_back'),
    'bwge_access_token'  => __('You do not have Instagram access token. Sign in with Instagram in Options->Social options. ', 'bwge_back'),
    'bwge_post_number'  => __('Instagram recent post number must be between 1 and 33.', 'bwge_back'),
    'bwge_not_empty'  => __('The gallery is not empty. Please delete all the images first.', 'bwge_back'),
    'bwge_enter_url'  => __('Please enter url to embed.', 'bwge_back'),
    'bwge_cannot_response'  => __('Error: cannot get response from the server.', 'bwge_back'),
    'bwge_something_wrong'  => __('Error: something wrong happened at the server.', 'bwge_back'),
    'bwge_error'  => __('Error', 'bwge_back'),
    'bwge_show_order'  => __('Show order column', 'bwge_back'),
    'bwge_hide_order'  => __('Hide order column', 'bwge_back'),
    'selected'  => __('Selected', 'bwge_back'),
    'item'  => __('item', 'bwge_back'),
    'saved'  => __('Items Succesfully Saved.', 'bwge_back'),
    'recovered'  => __('Item Succesfully Recovered.', 'bwge_back'),
    'published'  => __('Item Succesfully Published.', 'bwge_back'),
    'unpublished'  => __('Item Succesfully Unpublished.', 'bwge_back'),
    'deleted'  => __('Item Succesfully Deleted.', 'bwge_back'),
    'one_item'  => __('You must select at least one item.', 'bwge_back'),
    'resized'  => __('Items Succesfully resized.', 'bwge_back'),
    'watermark_set'  => __('Watermarks Succesfully Set.', 'bwge_back'),
    'reset'  => __('Items Succesfully Reset.', 'bwge_back'),
  ));

  global $wp_scripts;
  if (isset($wp_scripts->registered['jquery'])) {
    $jquery = $wp_scripts->registered['jquery'];
    if (!isset($jquery->ver) OR version_compare($jquery->ver, '1.8.2', '<')) {
      wp_deregister_script('jquery');
      wp_register_script('jquery', FALSE, array('jquery-core', 'jquery-migrate'), '1.10.2' );
    }
  }

  wp_enqueue_script('jquery-ui-sortable');
  wp_enqueue_script( 'color-js', WD_BWGE_URL . '/js/ecommerce/jscolor/jscolor.js', array(), wd_bwge_version(), true );


}

function bwge_ecommerce_scripts() {
	$page = isset($_GET['page']) ? $_GET['page'] : "ecommerceoptions_bwge" ;
	wp_enqueue_script('thickbox');


	wp_enqueue_script( 'bwge_admin_main-js', WD_BWGE_URL . '/js/ecommerce/admin_main.js', array(), wd_bwge_version(), true );
	wp_enqueue_script( 'bwge_color-js', WD_BWGE_URL . '/js/ecommerce/jscolor/jscolor.js', array(), '', true );

	global $wp_scripts;
	if (isset($wp_scripts->registered['jquery'])) {
		$jquery = $wp_scripts->registered['jquery'];
		if (!isset($jquery->ver) OR version_compare($jquery->ver, '1.8.2', '<')) {
		  wp_deregister_script('jquery');
		  wp_register_script('jquery', FALSE, array('jquery-core', 'jquery-migrate'), '1.10.2' );
		}
	}
	wp_enqueue_script('jquery');
	wp_enqueue_script('jquery-ui-sortable');
	wp_enqueue_media();
    if($page == "reports_bwge" || $page == "pricelists_bwge" || $page == "paymentsystems_bwge" || $page == "parameters_bwge" || $page == "ecommerceoptions_bwge"){
		wp_enqueue_script( $page.'-js', WD_BWGE_URL . '/js/ecommerce/'.$page.'.js', array(), wd_bwge_version(), true );
	}

    wp_localize_script('bwge_admin_main-js', 'bwge_objectL10B', array(
    'bwge_field_required'  => __('field is required.', 'bwge_back'),

  ));
}

/* Add pagination to gallery admin pages.*/
function bwge_add_galleries_per_page_option(){
  $option = 'per_page';
  $args_galleries = array(
    'label' => 'Items',
    'default' => 20,
    'option' => 'bwge_galleries_per_page'
  );
    add_screen_option( $option, $args_galleries );
}
function bwge_add_albums_per_page_option(){
  $option = 'per_page';
  $args_albums = array(
    'label' => 'Items',
    'default' => 20,
    'option' => 'bwge_albums_per_page'
  );
    add_screen_option( $option, $args_albums );
}
function bwge_add_tags_per_page_option(){
  $option = 'per_page';
  $args_tags = array(
    'label' => 'Tags',
    'default' => 20,
    'option' => 'bwge_tags_per_page'
  );
    add_screen_option( $option, $args_tags );
}

function bwge_add_comments_per_page_option(){
  $option = 'per_page';
  $args_comments = array(
    'label' => 'Comments',
    'default' => 20,
    'option' => 'bwge_comments_per_page'
  );
    add_screen_option( $option, $args_comments );
}
function bwge_add_rates_per_page_option(){
  $option = 'per_page';
  $args_rates = array(
    'label' => 'Ratings',
    'default' => 20,
    'option' => 'bwge_rates_per_page'
  );
    add_screen_option( $option, $args_rates );
}

function wdbwge_pricelists_per_page_option(){
	$option = 'per_page';
	$args_pricelists = array(
		'label' => __('Pricelists',"bwge_back"),
		'default' => 20,
		'option' => 'wdbwge_pricelists_per_page'
	);
	add_screen_option( $option, $args_pricelists );
}

function wdbwge_orders_per_page_option(){
	$option = 'per_page';
	$args_orders = array(
		'label' => __('Orders',"bwge_back"),
		'default' => 20,
		'option' => 'wdbwge_orders_per_page'
	);
	add_screen_option( $option, $args_orders );

}
function wdbwge_parameters_per_page_option(){
	$option = 'per_page';
	$args_parameters = array(
		'label' => __('Parameters',"bwge_back"),
		'default' => 20,
		'option' => 'wdbwge_parameters_per_page'
	);
	add_screen_option( $option, $args_parameters );

}
add_filter('set-screen-option', 'bwge_set_option_galleries', 10, 3);
add_filter('set-screen-option', 'bwge_set_option_albums', 10, 3);
add_filter('set-screen-option', 'bwge_set_option_tags', 10, 3);

add_filter('set-screen-option', 'bwge_set_option_comments', 10, 3);
add_filter('set-screen-option', 'bwge_set_option_rates', 10, 3);

add_filter('set-screen-option', 'wdbwge_set_option_pricelists', 10, 3);
add_filter('set-screen-option', 'wdbwge_set_option_orders', 10, 3);
add_filter('set-screen-option', 'wdbwge_set_option_parameters', 10, 3);


function bwge_set_option_galleries($status, $option, $value) {
    if ( 'bwge_galleries_per_page' == $option ) return $value;
    return $status;
}
function bwge_set_option_albums($status, $option, $value) {
    if ( 'bwge_albums_per_page' == $option ) return $value;
    return $status;
}
function bwge_set_option_tags($status, $option, $value) {
    if ( 'bwge_tags_per_page' == $option ) return $value;
    return $status;
}

function bwge_set_option_comments($status, $option, $value) {
    if ( 'bwge_comments_per_page' == $option ) return $value;
    return $status;
}
function bwge_set_option_rates($status, $option, $value) {
    if ( 'bwge_rates_per_page' == $option ) return $value;
    return $status;
}

function wdbwge_set_option_pricelists($status, $option, $value) {
    if ( 'wdbwge_pricelists_per_page' == $option ) return $value;
    return $status;
}
function wdbwge_set_option_orders($status, $option, $value) {
    if ( 'wdbwge_orders_per_page' == $option ) return $value;
    return $status;
}
function wdbwge_set_option_parameters($status, $option, $value) {
    if ( 'wdbwge_parameters_per_page' == $option ) return $value;
    return $status;
}


function wdbwge_set_option_payment_systems($status, $option, $value) {
    if ( 'wdbwge_payment_systems_per_page' == $option ) return $value;
    return $status;
}

function bwge_options_scripts() {
  wp_enqueue_script('thickbox');
  wp_enqueue_script('bwge_admin', WD_BWGE_URL . '/js/bwge.js', array(), wd_bwge_version());
  global $wp_scripts;
  if (isset($wp_scripts->registered['jquery'])) {
    $jquery = $wp_scripts->registered['jquery'];
    if (!isset($jquery->ver) OR version_compare($jquery->ver, '1.8.2', '<')) {
      wp_deregister_script('jquery');
      wp_register_script('jquery', FALSE, array('jquery-core', 'jquery-migrate'), '1.10.2' );
    }
  }
  wp_enqueue_script('jquery');
  wp_enqueue_script('jscolor', WD_BWGE_URL . '/js/jscolor/jscolor.js', array(), '1.3.9');
}

function bwge_front_end_scripts() {
  $version = wd_bwge_version();
  global $wp_scripts;
  if (isset($wp_scripts->registered['jquery'])) {
    $jquery = $wp_scripts->registered['jquery'];
    if (!isset($jquery->ver) OR version_compare($jquery->ver, '1.8.2', '<')) {
      wp_deregister_script('jquery');
      wp_register_script('jquery', FALSE, array('jquery-core', 'jquery-migrate'), '1.10.2' );
    }
  }
  wp_enqueue_script('jquery');
  /*wp_enqueue_style('jquery-ui', WD_BWGE_FRONT_URL . '/css/jquery-ui-1.10.3.custom.css', array(), $version);*/

  wp_enqueue_script('bwge_frontend', WD_BWGE_FRONT_URL . '/js/bwge_frontend.js', array(), $version);
  wp_enqueue_style('bwge_frontend', WD_BWGE_FRONT_URL . '/css/bwge_frontend.css', array(), $version);
  wp_enqueue_script('bwge_sumoselect', WD_BWGE_FRONT_URL . '/js/jquery.sumoselect.min.js', array(), $version);
  wp_enqueue_style('bwge_sumoselect', WD_BWGE_FRONT_URL . '/css/sumoselect.css', array(), $version);
  // Styles/Scripts for popup.
  wp_enqueue_style('bwge_font-awesome', WD_BWGE_FRONT_URL . '/css/font-awesome/font-awesome.css', array(), '4.2.0');
  wp_enqueue_script('bwge_jquery_mobile', WD_BWGE_FRONT_URL . '/js/jquery.mobile.js', array(), $version);
  wp_enqueue_script('bwge_mCustomScrollbar', WD_BWGE_FRONT_URL . '/js/jquery.mCustomScrollbar.concat.min.js', array(), $version);
  wp_enqueue_style('bwge_mCustomScrollbar', WD_BWGE_FRONT_URL . '/css/jquery.mCustomScrollbar.css', array(), $version);
  wp_enqueue_script('jquery-fullscreen', WD_BWGE_FRONT_URL . '/js/jquery.fullscreen-0.4.1.js', array(), '0.4.1');
  wp_enqueue_script('bwge_gallery_box', WD_BWGE_FRONT_URL . '/js/bwge_gallery_box.js', array(), $version);
  wp_enqueue_script('bwge_raty', WD_BWGE_FRONT_URL . '/js/jquery.raty.js', array(), '2.5.2');
  wp_enqueue_script('bwge_featureCarousel', WD_BWGE_URL . '/js/jquery.featureCarousel.js', array(), $version);
  wp_localize_script('bwge_gallery_box', 'bwge_objectL10n', array(
    'bwge_field_required'  => __('field is required.', 'bwge'),
    'bwge_mail_validation' => __('This is not a valid email address.', 'bwge'),
    'bwge_search_result' => __('There are no images matching your search.', 'bwge'),
  ));
  wp_localize_script('bwge_sumoselect', 'bwge_objectsL10n', array(
    'bwge_select_tag'  => __('Select Tag.', 'bwge'),
  ));

  //3D Tag Cloud.
  wp_enqueue_script('bwge_3DEngine', WD_BWGE_FRONT_URL . '/js/3DEngine/3DEngine.js', array(), $version);
  wp_enqueue_script('bwge_Sphere', WD_BWGE_FRONT_URL . '/js/3DEngine/Sphere.js', array(), $version);

  wp_enqueue_script('bwge_Sphere', WD_BWGE_FRONT_URL . '/js/3DEngine/Sphere.js', array(), $version);

  wp_enqueue_script('bwge_frontent_ecommerce', WD_BWGE_FRONT_URL . '/js/ecommerce/frontend.js', array(), $version);

  wp_enqueue_style('bwge_frontent_ecommerce',  WD_BWGE_FRONT_URL . '/css/bwge_frontent_ecommerce.css', array(), $version);
}
add_action('wp_enqueue_scripts', 'bwge_front_end_scripts');

/* Add bwge scheduled event for autoupdatable galleries.*/

add_filter( 'cron_schedules', 'bwge_add_autoupdate_interval' );

function bwge_add_autoupdate_interval( $schedules ) {

  require_once(WD_BWGE_DIR . '/framework/BWGELibraryEmbed.php');
  $autoupdate_interval = BWGELibraryEmbed::get_autoupdate_interval();

  $schedules['bwge_autoupdate_interval'] = array(
    'interval' => 60*$autoupdate_interval,
    'display' => __( 'Photo gallery plugin autoupdate interval.','bwge_back')
  );
  return $schedules;
}

add_action( 'bwge_schedule_event_hook', 'bwge_social_galleries' );

function bwge_social_galleries() {
  bwge_instagram_galleries();
  bwge_facebook_galleries();
  wp_die();
}

function bwge_facebook_galleries() {
  global $wpdb;
  require_once(WD_BWGE_DIR . '/framework/BWGELibraryEmbed.php');
  /* Array of IDs of facebook galleries.*/
  $response = array();
  /*Check facebook gallery*/
  $facebook_galleries = BWGELibraryEmbed::check_facebook_galleries();
  if (!empty($facebook_galleries[0])) {
    global $wd_bwge_fb;
    //Set true because can't check facebook add-on exists or not.(is_plugin_active function not defined at this part of code)
    $wd_bwge_fb = true;
    foreach ($facebook_galleries as $gallery) {
      //var_dump($gallery);
	  array_push($response, BWGELibraryEmbed::refresh_social_gallery($gallery));
    }
  }
}

function bwge_instagram_galleries() {
  /* Check if instagram galleries exist and refresh them every hour.*/

  require_once(WD_BWGE_DIR . '/framework/BWGELibraryEmbed.php');
  /* Array of IDs of instagram galleries.*/
  $response = array();
  $instagram_galleries = BWGELibraryEmbed::check_instagram_galleries();

  if(!empty($instagram_galleries[0]))
  {
    foreach ($instagram_galleries as $gallery) {
      array_push($response, BWGELibraryEmbed::refresh_social_gallery($gallery));
    }
  }
  wp_die();
}

// Languages localization.
function bwge_language_load() {
  load_plugin_textdomain('bwge', FALSE, basename(dirname(__FILE__)) . '/languages');
  load_plugin_textdomain('bwge_back', FALSE, basename(dirname(__FILE__)) . '/languages/backend');
}
add_action('init', 'bwge_language_load');

function bwge_create_post_type() {
  global $wpdb;
  $row = $wpdb->get_row($wpdb->prepare('SELECT * FROM ' . $wpdb->prefix . 'bwge_option WHERE id="%d"', 1));

  if ($row->show_hide_post_meta == 1) {
     $show_hide_post_meta = array('editor', 'comments');
  }
  else {
     $show_hide_post_meta = array();
  }
  if ($row->show_hide_custom_post == 0) {
     $show_hide_custom_post = false;
  }
  else {
     $show_hide_custom_post = true;
  }
  $args = array(
    'public' => TRUE,
    'exclude_from_search' => TRUE,
    'publicly_queryable' => TRUE,
    'show_ui' => $show_hide_custom_post,
    'show_in_menu' => TRUE,
    'show_in_nav_menus' => FALSE,
    'permalink_epmask' => TRUE,
    'rewrite' => TRUE,
    'label'  => 'bwge_gallery',
    'supports' => $show_hide_post_meta
  );
  register_post_type( 'bwge_gallery', $args );

  $args = array(
    'public' => TRUE,
    'exclude_from_search' => TRUE,
    'publicly_queryable' => TRUE,
    'show_ui' => $show_hide_custom_post,
    'show_in_menu' => TRUE,
    'show_in_nav_menus' => FALSE,
    'permalink_epmask' => TRUE,
    'rewrite' => TRUE,
    'label'  => 'bwge_album',
    'supports' => $show_hide_post_meta
  );
  register_post_type( 'bwge_album', $args );

  $args = array(
    'public' => TRUE,
    'exclude_from_search' => TRUE,
    'publicly_queryable' => TRUE,
    'show_ui' => $show_hide_custom_post,
    'show_in_menu' => TRUE,
    'show_in_nav_menus' => FALSE,
    'permalink_epmask' => TRUE,
    'rewrite' => TRUE,
    'label'  => 'bwge_tag',
    'supports' => $show_hide_post_meta
  );
  register_post_type( 'bwge_tag', $args );

  $args = array(
		'public'             => false,
		'publicly_queryable' => true,
		/*'query_var'          => 'share',
		'rewrite'            => array('slug' => 'share'),*/
  );
  register_post_type('bwge_share', $args);

  $labels = array(
      'name'               => _x( 'Gallery Ecommerce Page', 'post type general name', 'bwge' ),
      'singular_name'      => _x( 'Gallery Ecommerce Page', 'post type singular name', 'bwge' ),
      'menu_name'          => _x( 'Gallery Ecommerce Pages', 'admin menu', 'bwge' ),
      'name_admin_bar'     => _x( 'bwge', 'add new on admin bar', 'bwge' ),
      'add_new'            => _x( 'Add New', 'book', 'bwge' ),
      'add_new_item'       => __( 'Add New Ecommerce Page', 'bwge' ),
      'new_item'           => __( 'New Gallery Ecommerce Page', 'bwge' ),
      'edit_item'          => __( 'Edit Gallery Ecommerce Page', 'bwge' ),
      'view_item'          => __( 'View Gallery Ecommerce Page', 'bwge' ),
      'all_items'          => __( 'All Gallery Ecommerce Pages', 'bwge' ),
      'search_items'       => __( 'Search Gallery Ecommerce Pages', 'bwge' ),
      'parent_item_colon'  => __( 'Parent Gallery Ecommerce Pages:', 'bwge' ),
      'not_found'          => __( 'No Gallery Ecommerce Pages found.', 'bwge' ),
      'not_found_in_trash' => __( 'No Gallery Ecommerce Pages found in Trash.', 'bwge' )
  );
  $args = array(
      'labels'             => $labels,
      'public'             => true,
      'publicly_queryable' => true,
      'show_ui'            => true,
      'show_in_nav_menus'  => true,
      'query_var'          => true,
      'rewrite'            => true,
      'has_archive'        => true,
      'hierarchical'       => false,
      'show_in_menu'       => true,
      'menu_position'      => '27,50'
  );
  register_post_type( 'bwge_ecommerce_page', $args );
  flush_rewrite_rules();

}
add_action( 'init', 'bwge_create_post_type' );

// Create custom pages templates.
function bwge_custom_post_bwge_typetemplate($single_template) {
  global $post;
  if (isset($post) && isset($post->post_type)) {
    if ($post->post_type == 'bwge_share') {
      $single_template = WD_BWGE_DIR . '/framework/BWGEShare.php';
    }
  }
  return $single_template;
}
add_filter('single_template', 'bwge_custom_post_bwge_typetemplate');

function bwge_widget_tag_cloud_args($args) {
  if ($args['taxonomy'] == 'bwge_tag') {
    require_once WD_BWGE_DIR . "/frontend/models/BWGEModelWidget.php";
    $model = new BWGEModelWidgetFrontEnd();
    $tags = $model->get_tags_data(0);
  }
  return $args;
}
add_filter('widget_tag_cloud_args', 'bwge_widget_tag_cloud_args');

// Captcha.
function bwge_captcha() {
  if (isset($_GET['action']) && esc_html($_GET['action']) == 'bwge_captcha') {
    $i = (isset($_GET["i"]) ? esc_html($_GET["i"]) : '');
    $r2 = (isset($_GET["r2"]) ? (int) $_GET["r2"] : 0);
    $rrr = (isset($_GET["rrr"]) ? (int) $_GET["rrr"] : 0);
    $randNum = 0 + $r2 + $rrr;
    $digit = (isset($_GET["digit"]) ? (int) $_GET["digit"] : 0);
    $cap_width = $digit * 10 + 15;
    $cap_height = 26;
    $cap_quality = 100;
    $cap_length_min = $digit;
    $cap_length_max = $digit;
    $cap_digital = 1;
    $cap_latin_char = 1;
    function code_generic($_length, $_digital = 1, $_latin_char = 1) {
      $dig = array(0, 1, 2, 3, 4, 5, 6, 7, 8, 9);
      $lat = array('a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z');
      $main = array();
      if ($_digital) {
        $main = array_merge($main, $dig);
      }
      if ($_latin_char) {
        $main = array_merge($main, $lat);
      }
      shuffle($main);
      $pass = substr(implode('', $main), 0, $_length);
      return $pass;
    }
    $l = rand($cap_length_min, $cap_length_max);
    $code = code_generic($l, $cap_digital, $cap_latin_char);
    @session_start();
    $_SESSION['bwge_captcha_code'] = $code;
    $canvas = imagecreatetruecolor($cap_width, $cap_height);
    $c = imagecolorallocate($canvas, rand(150, 255), rand(150, 255), rand(150, 255));
    imagefilledrectangle($canvas, 0, 0, $cap_width, $cap_height, $c);
    $count = strlen($code);
    $color_text = imagecolorallocate($canvas, 0, 0, 0);
    for ($it = 0; $it < $count; $it++) {
      $letter = $code[$it];
      imagestring($canvas, 6, (10 * $it + 10), $cap_height / 4, $letter, $color_text);
    }
    for ($c = 0; $c < 150; $c++) {
      $x = rand(0, $cap_width - 1);
      $y = rand(0, 29);
      $col = '0x' . rand(0, 9) . '0' . rand(0, 9) . '0' . rand(0, 9) . '0';
      imagesetpixel($canvas, $x, $y, $col);
    }
    header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
    header('Cache-Control: no-store, no-cache, must-revalidate');
    header('Cache-Control: post-check=0, pre-check=0', FALSE);
    header('Pragma: no-cache');
    header('Content-Type: image/jpeg');
    imagejpeg($canvas, NULL, $cap_quality);
    die('');
  }
}

function wd_bwge_version() {
  $version = get_option("wd_bwge_version");
  if ($version) {
    if (WD_BWGE_PRO) {
      $version = substr_replace($version, '2', 0, 1);
    }
  }
  else{
    $version = '';
  }
  return $version;
}

if (is_admin() && (!defined('DOING_AJAX') || !DOING_AJAX)) {
	include_once(WD_BWGE_DIR . '/gallery-ecommerce-notices.php');
  new bwge_Notices();
}
?>
