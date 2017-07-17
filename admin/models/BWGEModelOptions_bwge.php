<?php

class BWGEModelOptions_bwge {
  ////////////////////////////////////////////////////////////////////////////////////////
  // Events                                                                             //
  ////////////////////////////////////////////////////////////////////////////////////////
  ////////////////////////////////////////////////////////////////////////////////////////
  // Constants                                                                          //
  ////////////////////////////////////////////////////////////////////////////////////////
  ////////////////////////////////////////////////////////////////////////////////////////
  // Variables                                                                          //
  ////////////////////////////////////////////////////////////////////////////////////////
  private $facebook_sdk;
  private $app_id;
  private $app_secret;
  private $access_token;
  ////////////////////////////////////////////////////////////////////////////////////////
  // Constructor & Destructor                                                           //
  ////////////////////////////////////////////////////////////////////////////////////////
  public function __construct() {
  }
  ////////////////////////////////////////////////////////////////////////////////////////
  // Public Methods                                                                     //
  ////////////////////////////////////////////////////////////////////////////////////////

  public function get_facebook_data($facebook_app_id, $facebook_app_secret) {
    global $wd_bwge_fb;
    if ($wd_bwge_fb && !class_exists('Facebook') && file_exists(WD_bwge_FB_DIR . "/facebook-sdk/facebook.php")) {
      require_once WD_bwge_FB_DIR . "/facebook-sdk/facebook.php";
    }
    else {
      return;
    }
    $this->app_id = $facebook_app_id;
    $this->app_secret = $facebook_app_secret;
    $this->access_token = '';
    $this->facebook_sdk = new Facebook(array(
      'appId'  => $this->app_id,
      'secret' => $this->app_secret,
    ));	  
    if (isset($_POST['app_log_out'])) {
      //setcookie('fbs_'.$this->facebook_sdk->getAppId(), '', time()-100, '/', 'http://localhost/wordpress_rfe/');
      session_destroy();
      //although you need reload the page for loging out
      //so we destroy user access token stored in session var
    }
    if ($this->facebook_sdk->getUser()) {
      try {
      }
      catch (FacebookApiException $e) {
        echo "<!--DEBUG: " . $e . " :END-->";
        error_log($e);
      }
    }
    //echo $this->facebook_sdk->getAccessToken();
    return $this->facebook_sdk->getUser();
  }

  public function log_in_log_out() {
    $user = $this->facebook_sdk->getUser();
    if ($user) {
      try {
      $old_access_token = $this->access_token;
      // Proceed knowing you have a logged in user who's authenticated.
        $user_profile = $this->facebook_sdk->api('/me');
        $this->facebook_sdk->setExtendedAccessToken();
        $access_token = $this->facebook_sdk->getAccessToken();
      } catch (FacebookApiException $e) {
      echo '<div class="error"><p><strong>OAuth Error</strong>Error added to error_log: '.$e.'</p></div>';
      error_log($e);
      $user = null;
      }
    }
    // Login or logout url will be needed depending on current user state.
    $app_link_text = $app_link_url = null;
    if ($user && !isset($_POST['app_log_out'])) {
      $app_link_url = $this->facebook_sdk->getLogoutUrl(array('next' => admin_url() . 'admin.php?page=options_bwge'));
      $app_link_text = __("Logout of your app", 'facebook-albums');
    } else {
      $app_link_url = $this->facebook_sdk->getLoginUrl(array('scope' => 'user_photos,user_videos,user_posts'));
        $app_link_text = __('Log into Facebook with your app', 'facebook-albums');
    } ?>
    <input type="hidden" name="facebookalbum[app_id]" value="<?php echo $this->app_id; ?>" />
    <input type="hidden" name="facebookalbum[app_secret]" value="<?php echo $this->app_secret; ?>" />
    <input type="hidden" name="facebookalbum[access_token]" value="<?php echo $this->access_token; ?>" />
    <?php if($user && !isset($_POST['app_log_out'])) : ?>
    <div style="float: right;"><span style="margin: 0 10px;"><?php echo $user_profile['name']; ?></span><img src="https://graph.facebook.com/<?php echo $user_profile['id']; ?>/picture?type=square" style="vertical-align: middle"/></div>
      <ul style="margin:0px;list-style-type:none">
      <li><a href="https://developers.facebook.com/apps/<?php echo $this->app_id; ?>" target="_blank"><?php _e("View your application's settings.", 'facebook-albums'); ?></a></li>
        <input class="wd-btn wd-btn-primary" type="submit" name="app_log_out" value="Log out from app" />
    </ul>
    <?php else :  ?>
      <a href="<?php echo $app_link_url; ?>"><?php echo $app_link_text; ?></a>	   
    <?php endif; ?>
    <div style="clear: both;">&nbsp;</div>	  
    <?php
    /*<!-- <p><?php printf(__('Having issues once logged in? Try <a href="?page=facebook-album&amp;reset_application=%s">resetting application data.</a> <em>warning: removes App ID and App Secret</em>'), wp_create_nonce($current_user->data->user_email)); ?></p>
    <p><strong>Notice!</strong> Your extended access token will only last about 2 months. So visit this page every month or so to keep the access token fresh.</p> -->*/
  }
  
  public function get_row_data($reset) {
    global $wpdb;
    $row = $wpdb->get_row($wpdb->prepare('SELECT * FROM ' . $wpdb->prefix . 'bwge_option WHERE id="%d"', 1));
    $row->old_images_directory = $row->images_directory;
    if ($reset) {
      $upload_dir = wp_upload_dir();
      if (!is_dir($upload_dir['basedir'] . '/gallery-ecommerce')) {
        mkdir($upload_dir['basedir'] . '/gallery-ecommerce', 0777);
      }
      $row->images_directory = str_replace(ABSPATH, '', $upload_dir['basedir']);
      $row->masonry = 'vertical';
      $row->mosaic = 'vertical';
      $row->resizable_mosaic = 0;
      $row->mosaic_total_width = 100;
      $row->image_column_number = 5;
      $row->images_per_page = 30;
      $row->thumb_width = 180;
      $row->thumb_height = 90;
      $row->upload_thumb_width = 300;
      $row->upload_thumb_height = 300;
      $row->upload_img_width = 1200; 
      $row->upload_img_height = 1200;
      $row->image_enable_page = 1;
      $row->image_title_show_hover = 'none';

      $row->album_column_number = 5;
      $row->albums_per_page = 30;
      $row->album_title_show_hover = 'hover';
      $row->album_thumb_width = 120;
      $row->album_thumb_height = 90;
      $row->album_enable_page = 1;
      $row->extended_album_height = 150;
      $row->extended_album_description_enable = 1;

      $row->image_browser_width = 800;
      $row->image_browser_title_enable = 1;
      $row->image_browser_description_enable = 1;

      $row->blog_style_width = 800;
      $row->blog_style_title_enable = 1;
      $row->blog_style_images_per_page = 5;
      $row->blog_style_enable_page = 1;

      $row->slideshow_type = 'fade';
      $row->slideshow_interval = 5;
      $row->slideshow_width = 800;
      $row->slideshow_height = 500;
      $row->slideshow_enable_autoplay = 0;
      $row->slideshow_enable_shuffle = 0;
      $row->slideshow_enable_ctrl = 1;
      $row->slideshow_enable_filmstrip = 1;
      $row->slideshow_filmstrip_height = 90;
      $row->slideshow_ecommerce_icon = 1;
      $row->slideshow_enable_title = 0;
      $row->slideshow_title_position = 'top-right';
      $row->slideshow_title_full_width = 0;
      $row->slideshow_enable_description = 0;
      $row->slideshow_description_position = 'bottom-right';
      $row->slideshow_enable_music = 0;
      $row->slideshow_audio_url = '';

      $row->popup_width = 800;
      $row->popup_height = 500;
      $row->popup_type = 'fade';
      $row->popup_interval = 5;
      $row->popup_enable_filmstrip = 1;
      $row->popup_filmstrip_height = 70;
      $row->popup_enable_ctrl_btn = 1;
      $row->popup_enable_fullscreen = 1;
      $row->popup_enable_comment = 1;
      $row->popup_enable_email = 0;
      $row->popup_enable_captcha = 0;
      $row->popup_enable_download = 0;
      $row->popup_enable_fullsize_image = 0;
      $row->popup_enable_facebook = 1;
      $row->popup_enable_twitter = 1;
      $row->popup_enable_google = 1;
      $row->popup_enable_pinterest = 0;
      $row->popup_enable_tumblr = 0;

      $row->watermark_type = 'none';
      $row->watermark_position = 'bottom-left';
      $row->watermark_width = 90;
      $row->watermark_height = 90;
      $row->watermark_url = WD_BWGE_URL . '/images/watermark.png';
      $row->watermark_text = 'web-dorado.com';
      $row->watermark_link = 'https://web-dorado.com';
      $row->watermark_font_size = 20;
      $row->watermark_font = 'arial';
      $row->watermark_color = 'FFFFFF';
      $row->watermark_opacity = 30;

      $row->built_in_watermark_type = 'none';
      $row->built_in_watermark_position = 'middle-center';
      $row->built_in_watermark_size = 15;
      $row->built_in_watermark_url = WD_BWGE_URL . '/images/watermark.png';
      $row->built_in_watermark_text = 'web-dorado.com';
      $row->built_in_watermark_font_size = 20;
      $row->built_in_watermark_font = 'arial';
      $row->built_in_watermark_color = 'FFFFFF';
      $row->built_in_watermark_opacity = 30;

      $row->image_right_click = 0;
      $row->popup_fullscreen = 0;
      $row->gallery_role = 0;
      $row->album_role = 0;
      $row->image_role = 0;
      $row->popup_autoplay = 0;
      $row->album_view_type = 'thumbnail';
      $row->show_search_box = 0;
      $row->search_box_width = 180;
      $row->preload_images = 1;
      $row->preload_images_count = 10;
      $row->popup_enable_info = 1;
      $row->popup_info_always_show = 0;
      $row->popup_enable_rate = 0;
      $row->thumb_click_action = 'open_lightbox';
      $row->thumb_link_target = 1;
      $row->comment_moderation = 0;
      $row->popup_hit_counter = 0;
      $row->enable_ML_import = 0;
      $row->autoupdate_interval = 30;
      $row->instagram_access_token = '';
      $row->showthumbs_name = 0;
      $row->show_album_name = 0;
      $row->show_image_counts = 0;
      $row->play_icon = 1;
      $row->show_masonry_thumb_description = 0;
      $row->popup_info_full_width = 0;
        $row->show_sort_images = 0;
        $row->enable_seo = 1;
        $row->autohide_lightbox_navigation = 1;
      $row->autohide_slideshow_navigation = 1;
      $row->read_metadata = 0;
      $row->enable_loop = 1;
      $row->enable_addthis = 0;
      $row->addthis_profile_id = '';

      $row->carousel_interval = 5;
      $row->carousel_width = 300;
      $row->carousel_height = 300;
      $row->carousel_image_column_number = 5;
      $row->carousel_image_par = 0.75;
      $row->carousel_enable_autoplay = 0;
      $row->carousel_enable_title = 0;
      $row->carousel_ecommerce_icon = 1;
      $row->carousel_r_width = 800;
      $row->carousel_fit_containerWidth = 1;
      $row->carousel_prev_next_butt = 1;
      $row->carousel_play_pause_butt = 1;
      $row->permissions = 'manage_options';
      $row->facebook_app_id = '';
      $row->facebook_app_secret = '';
      $row->show_tag_box = 0;
      $row->show_hide_custom_post = 0;
      $row->show_hide_post_meta = 0;
      $row->placeholder = '';
      $row->ecommerce_icon_show_hover = 'show';
      $row->popup_enable_ecommerce = 1;
    }
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