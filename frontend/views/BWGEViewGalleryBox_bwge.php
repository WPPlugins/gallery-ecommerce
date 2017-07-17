<?php

class BWGEViewGalleryBox_bwge {
  ////////////////////////////////////////////////////////////////////////////////////////
  // Events                                                                             //
  ////////////////////////////////////////////////////////////////////////////////////////
  ////////////////////////////////////////////////////////////////////////////////////////
  // Constants                                                                          //
  ////////////////////////////////////////////////////////////////////////////////////////
  ////////////////////////////////////////////////////////////////////////////////////////
  // Variables                                                                          //
  ////////////////////////////////////////////////////////////////////////////////////////
  private $model;
  ////////////////////////////////////////////////////////////////////////////////////////
  // Constructor & Destructor                                                           //
  ////////////////////////////////////////////////////////////////////////////////////////
  public function __construct($model) {
    $this->model = $model;
  }
  ////////////////////////////////////////////////////////////////////////////////////////
  // Public Methods                                                                     //
  ////////////////////////////////////////////////////////////////////////////////////////
  public function display() {
    global $WD_BWGE_UPLOAD_DIR;
    global $wp;
    require_once(WD_BWGE_DIR . '/framework/BWGELibraryEmbed.php');
    $current_url = isset($_GET['current_url']) ? esc_html($_GET['current_url']) : '';
  
    $tag_id = (isset($_GET['tag_id']) ? esc_html($_GET['tag_id']) : 0);
    $gallery_id =  BWGELibrary::esc_script('get', 'gallery_id', 0, 'int');
    $bwge = (isset($_GET['current_view']) ? esc_html($_GET['current_view']) : 0);
    $current_image_id = BWGELibrary::esc_script('get', 'image_id', 0, 'int');
    $theme_id = (isset($_GET['theme_id']) ? esc_html($_GET['theme_id']) : 1);
    $thumb_width = (isset($_GET['thumb_width']) ? esc_html($_GET['thumb_width']) : 120);
    $thumb_height = (isset($_GET['thumb_height']) ? esc_html($_GET['thumb_height']) : 90);
    $open_with_fullscreen =  BWGELibrary::esc_script('get', 'open_with_fullscreen', 0, 'int');
    $open_with_autoplay =  BWGELibrary::esc_script('get', 'open_with_autoplay', 0, 'int');
    $image_width =  BWGELibrary::esc_script('get', 'image_width', 800, 'int');
    $image_height = BWGELibrary::esc_script('get', 'image_height', 500, 'int');
    $image_effect = BWGELibrary::esc_script('get', 'image_effect', 'fade');
    $sort_by = (isset($_GET['wd_sor']) ? esc_html($_GET['wd_sor']) : 'order');
    $order_by = (isset($_GET['wd_ord']) ? esc_html($_GET['wd_ord']) : 'asc');
    $enable_image_filmstrip = BWGELibrary::esc_script('get', 'enable_image_filmstrip', 0, 'int');
    $enable_image_fullscreen = (isset($_GET['enable_image_fullscreen']) ? esc_html($_GET['enable_image_fullscreen']) : 0);
    $popup_enable_info = (isset($_GET['popup_enable_info']) ? esc_html($_GET['popup_enable_info']) : 1);
    $popup_info_always_show = (isset($_GET['popup_info_always_show']) ? esc_html($_GET['popup_info_always_show']) : 0);
    $popup_info_full_width = (isset($_GET['popup_info_full_width']) ? esc_html($_GET['popup_info_full_width']) : 0);
    $popup_enable_rate = BWGELibrary::esc_script('get', 'popup_enable_rate', 0, 'int');
    $popup_hit_counter = (isset($_GET['popup_hit_counter']) ? esc_html($_GET['popup_hit_counter']) : 0);
    $show_tag_box = (isset($_GET['show_tag_box']) ? esc_html($_GET['show_tag_box']) : 0);
    
    $open_ecommerce = BWGELibrary::esc_script('get', 'open_ecommerce', 0, 'int');


    $image_filmstrip_height = 0;
    $image_filmstrip_width = 0;
  

    $slideshow_interval = (isset($_GET['slideshow_interval']) ? (int) $_GET['slideshow_interval'] : 5);
    $enable_image_ctrl_btn = (isset($_GET['enable_image_ctrl_btn']) ? esc_html($_GET['enable_image_ctrl_btn']) : 0);
    $enable_comment_social = (isset($_GET['enable_comment_social']) ? esc_html($_GET['enable_comment_social']) : 0);
    $enable_image_facebook = (isset($_GET['enable_image_facebook']) ? esc_html($_GET['enable_image_facebook']) : 0);
    $enable_image_twitter = (isset($_GET['enable_image_twitter']) ? esc_html($_GET['enable_image_twitter']) : 0);
    $enable_image_google = (isset($_GET['enable_image_google']) ? esc_html($_GET['enable_image_google']) : 0);
    $enable_image_ecommerce = BWGELibrary::esc_script('get', 'enable_image_ecommerce', 0, 'int');
    $enable_image_pinterest = (isset($_GET['enable_image_pinterest']) ? esc_html($_GET['enable_image_pinterest']) : 0);
    $enable_image_tumblr = (isset($_GET['enable_image_tumblr']) ? esc_html($_GET['enable_image_tumblr']) : 0);

    $watermark_type = (isset($_GET['watermark_type']) ? esc_html($_GET['watermark_type']) : 'none');
    $watermark_text = (isset($_GET['watermark_text']) ? esc_html($_GET['watermark_text']) : '');
    $watermark_font_size = (isset($_GET['watermark_font_size']) ? esc_html($_GET['watermark_font_size']) : 12);
    $watermark_font = (isset($_GET['watermark_font']) ? esc_html($_GET['watermark_font']) : 'Arial');
    $watermark_color = (isset($_GET['watermark_color']) ? esc_html($_GET['watermark_color']) : 'FFFFFF');
    $watermark_opacity = (isset($_GET['watermark_opacity']) ? esc_html($_GET['watermark_opacity']) : 30);
    $watermark_position = explode('-', (isset($_GET['watermark_position']) ? esc_html($_GET['watermark_position']) : 'bottom-right'));
    $watermark_link = (isset($_GET['watermark_link']) ? esc_html($_GET['watermark_link']) : '');
    $watermark_url = (isset($_GET['watermark_url']) ? esc_html($_GET['watermark_url']) : '');
    $watermark_width = (isset($_GET['watermark_width']) ? esc_html($_GET['watermark_width']) : 90);
    $watermark_height = (isset($_GET['watermark_height']) ? esc_html($_GET['watermark_height']) : 90);

    $theme_row = $this->model->get_theme_row_data($theme_id);
    $option_row = $this->model->get_option_row_data();
    $image_right_click = $option_row->image_right_click;
    $comment_moderation = $option_row->comment_moderation;

    $filmstrip_direction = 'horizontal';
    if ($theme_row->lightbox_filmstrip_pos == 'right' || $theme_row->lightbox_filmstrip_pos == 'left') {
      $filmstrip_direction = 'vertical';        
    }

    $image_filmstrip_height = 0;
    $image_filmstrip_width = 0;
    
    if ($tag_id != 0) {
      $image_rows = $this->model->get_image_rows_data_tag($tag_id, $sort_by, $order_by);
    }
    else {
      $image_rows = $this->model->get_image_rows_data($gallery_id, $bwge, $sort_by, $order_by);
    }
    $image_id = (isset($_POST['image_id']) ? (int) $_POST['image_id'] : $current_image_id);
    $comment_rows = $this->model->get_comment_rows_data($image_id);

    $pricelist_id = $this->model->get_image_pricelist($image_id) ?  $this->model->get_image_pricelist($image_id) : 0;
	
    $pricelist_data = $this->model->get_image_pricelists($pricelist_id);

    $params_array = array(
      'action' => 'GalleryBox_bwge',
      'image_id' => $current_image_id,
      'gallery_id' => $gallery_id,
      'theme_id' => $theme_id,
      'thumb_width' => $thumb_width,
      'thumb_height' => $thumb_height,
      'open_with_fullscreen' => $open_with_fullscreen,
      'image_width' => $image_width,
      'image_height' => $image_height,
      'image_effect' => $image_effect,
      'wd_sor' => $sort_by,
      'wd_ord' => $order_by,
      'enable_image_filmstrip' => $enable_image_filmstrip,
      'image_filmstrip_height' => $image_filmstrip_height,
      'enable_image_ctrl_btn' => $enable_image_ctrl_btn,
      'enable_image_fullscreen' => $enable_image_fullscreen,
      'popup_enable_info' => $popup_enable_info,
      'popup_info_always_show' => $popup_info_always_show,
      'popup_info_full_width' => $popup_info_full_width,
      'popup_hit_counter' => $popup_hit_counter,
      'popup_enable_rate' => $popup_enable_rate,
      'slideshow_interval' => $slideshow_interval,
      'enable_comment_social' => $enable_comment_social,
      'enable_image_facebook' => $enable_image_facebook,
      'enable_image_twitter' => $enable_image_twitter,
      'enable_image_google' => $enable_image_google,
      'enable_image_ecommerce' => $enable_image_ecommerce,
      'enable_image_pinterest' => $enable_image_pinterest,
      'enable_image_tumblr' => $enable_image_tumblr,
      'watermark_type' => $watermark_type,
      'current_url' => $current_url
    );
    if ($watermark_type != 'none') {
      $params_array['watermark_link'] = $watermark_link;
      $params_array['watermark_opacity'] = $watermark_opacity;
      $params_array['watermark_position'] = $watermark_position;
    }
    if ($watermark_type == 'text') {
      $params_array['watermark_text'] = $watermark_text;
      $params_array['watermark_font_size'] = $watermark_font_size;
      $params_array['watermark_font'] = $watermark_font;
      $params_array['watermark_color'] = $watermark_color;
    }
    elseif ($watermark_type == 'image') {
      $params_array['watermark_url'] = $watermark_url;
      $params_array['watermark_width'] = $watermark_width;
      $params_array['watermark_height'] = $watermark_height;
    }
    $popup_url = add_query_arg(array($params_array), admin_url('admin-ajax.php'));
    $filmstrip_thumb_margin = $theme_row->lightbox_filmstrip_thumb_margin;
    $margins_split = explode(" ", $filmstrip_thumb_margin);
    $filmstrip_thumb_margin_right = 0;
    $filmstrip_thumb_margin_left = 0;
    $temp_iterator = ($filmstrip_direction == 'horizontal' ? 1 : 0);
    if (isset($margins_split[$temp_iterator])) {
      $filmstrip_thumb_margin_right = (int) $margins_split[$temp_iterator];
      if (isset($margins_split[$temp_iterator + 2])) {
        $filmstrip_thumb_margin_left = (int) $margins_split[$temp_iterator + 2];
      }
      else {
        $filmstrip_thumb_margin_left = $filmstrip_thumb_margin_right;
      }
    }
    elseif (isset($margins_split[0])) {
      $filmstrip_thumb_margin_right = (int) $margins_split[0];
      $filmstrip_thumb_margin_left = $filmstrip_thumb_margin_right;
    }
    $filmstrip_thumb_margin_hor = $filmstrip_thumb_margin_right + $filmstrip_thumb_margin_left;
    $rgb_bwge_image_info_bg_color = BWGELibrary::bwge_spider_hex2rgb($theme_row->lightbox_info_bg_color);
    $rgb_bwge_image_hit_bg_color = BWGELibrary::bwge_spider_hex2rgb($theme_row->lightbox_hit_bg_color);
    $rgb_lightbox_ctrl_cont_bg_color = BWGELibrary::bwge_spider_hex2rgb($theme_row->lightbox_ctrl_cont_bg_color);
    if (!$enable_image_filmstrip) {
      if ($theme_row->lightbox_filmstrip_pos == 'left') {
        $theme_row->lightbox_filmstrip_pos = 'top';
      }
      if ($theme_row->lightbox_filmstrip_pos == 'right') {
        $theme_row->lightbox_filmstrip_pos = 'bottom';
      }
    }
    $left_or_top = 'left';
    $width_or_height= 'width';
    $outerWidth_or_outerHeight = 'outerWidth';
    if (!($filmstrip_direction == 'horizontal')) {
      $left_or_top = 'top';
      $width_or_height = 'height';
      $outerWidth_or_outerHeight = 'outerHeight';
    }

    $current_filename = '';

    if ($option_row->enable_addthis && $option_row->addthis_profile_id) {
      ?>
      <script type="text/javascript" src="//s7.addthis.com/js/300/addthis_widget.js#pubid=<?php echo $option_row->addthis_profile_id; ?>" async="async"></script>
      <?php
    }
    ?>
    <style>
      .bwge_inst_play_btn_cont {
        width: 100%; 
        height: 100%; 
        position: absolute; 
        z-index: 1; 
        cursor: pointer;
        top: 0;
      }
      .bwge_inst_play {
        position: absolute; 
        width: 50px;
        height: 50px;
        background-image: url('<?php echo WD_BWGE_URL . '/images/play.png'; ?>');
        background-position: center center;
        background-repeat: no-repeat;
        background-size: cover;
        transition: background-image 0.2s ease-out;
        -ms-transition: background-image 0.2s ease-out;
        -moz-transition: background-image 0.2s ease-out;
        -webkit-transition: background-image 0.2s ease-out;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        margin: auto;
      }
      .bwge_inst_play:hover {
          background: url(<?php echo WD_BWGE_URL . '/images/play_hover.png'; ?>) no-repeat;
          background-position: center center;
          background-repeat: no-repeat;
          background-size: cover;
      }

      .bwge_spider_popup_wrap * {
        -moz-box-sizing: border-box;
        -webkit-box-sizing: border-box;
        box-sizing: border-box;
      }
      .bwge_spider_popup_wrap {
        background-color: #<?php echo $theme_row->lightbox_bg_color; ?>;
        display: inline-block;
        left: 50%;
        outline: medium none;
        position: fixed;
        text-align: center;
        top: 50%;
        z-index: 100000;
      }
      .bwge_popup_image {
        max-width: <?php echo $image_width - ($filmstrip_direction == 'vertical' ? $image_filmstrip_width : 0); ?>px;
        max-height: <?php echo $image_height - ($filmstrip_direction == 'horizontal' ? $image_filmstrip_height : 0); ?>px;
        vertical-align: middle;
        display: inline-block;
      }
      .bwge_popup_embed {
        /*width: <?php echo $image_width - ($filmstrip_direction == 'vertical' ? $image_filmstrip_width : 0); ?>px;
        height: <?php echo $image_height - ($filmstrip_direction == 'horizontal' ? $image_filmstrip_height : 0); ?>px;*/
        width: 100%;
        height: 100%;
        vertical-align: middle;
        text-align: center;
        display: table;
      }
      .bwge_ctrl_btn {
        color: #<?php echo $theme_row->lightbox_ctrl_btn_color; ?>;
        font-size: <?php echo $theme_row->lightbox_ctrl_btn_height; ?>px;
        margin: <?php echo $theme_row->lightbox_ctrl_btn_margin_top; ?>px <?php echo $theme_row->lightbox_ctrl_btn_margin_left; ?>px;
        opacity: <?php echo number_format($theme_row->lightbox_ctrl_btn_transparent / 100, 2, ".", ""); ?>;
        filter: Alpha(opacity=<?php echo $theme_row->lightbox_ctrl_btn_transparent; ?>);
      }
      .bwge_toggle_btn {
        color: #<?php echo $theme_row->lightbox_ctrl_btn_color; ?>;
        font-size: <?php echo $theme_row->lightbox_toggle_btn_height; ?>px;
        margin: 0;
        opacity: <?php echo number_format($theme_row->lightbox_ctrl_btn_transparent / 100, 2, ".", ""); ?>;
        filter: Alpha(opacity=<?php echo $theme_row->lightbox_ctrl_btn_transparent; ?>);
        padding: 0;
      }
      .bwge_btn_container {
        bottom: 0;
        left: 0;
        overflow: hidden;
        position: absolute;
        right: 0;
        top: 0;
      }
      .bwge_ctrl_btn_container {
        background-color: rgba(<?php echo $rgb_lightbox_ctrl_cont_bg_color['red']; ?>, <?php echo $rgb_lightbox_ctrl_cont_bg_color['green']; ?>, <?php echo $rgb_lightbox_ctrl_cont_bg_color['blue']; ?>, <?php echo number_format($theme_row->lightbox_ctrl_cont_transparent / 100, 2, ".", ""); ?>);
        /*background: none repeat scroll 0 0 #<?php echo $theme_row->lightbox_ctrl_cont_bg_color; ?>;*/
        <?php
        if ($theme_row->lightbox_ctrl_btn_pos == 'top') {
          ?>
          border-bottom-left-radius: <?php echo $theme_row->lightbox_ctrl_cont_border_radius; ?>px;
          border-bottom-right-radius: <?php echo $theme_row->lightbox_ctrl_cont_border_radius; ?>px;
          <?php
        }
        else {
          ?>
          bottom: 0;
          border-top-left-radius: <?php echo $theme_row->lightbox_ctrl_cont_border_radius; ?>px;
          border-top-right-radius: <?php echo $theme_row->lightbox_ctrl_cont_border_radius; ?>px;
          <?php
        }?>
        height: <?php echo $theme_row->lightbox_ctrl_btn_height + 2 * $theme_row->lightbox_ctrl_btn_margin_top; ?>px;
        /*opacity: <?php echo number_format($theme_row->lightbox_ctrl_cont_transparent / 100, 2, ".", ""); ?>;
        filter: Alpha(opacity=<?php echo $theme_row->lightbox_ctrl_cont_transparent; ?>);*/
        position: absolute;
        text-align: <?php echo $theme_row->lightbox_ctrl_btn_align; ?>;
        width: 100%;
        z-index: 10150;
      }
      .bwge_toggle_container {
        background: none repeat scroll 0 0 #<?php echo $theme_row->lightbox_ctrl_cont_bg_color; ?>;
        <?php
        if ($theme_row->lightbox_ctrl_btn_pos == 'top') {
          ?>
          border-bottom-left-radius: <?php echo $theme_row->lightbox_ctrl_cont_border_radius; ?>px;
          border-bottom-right-radius: <?php echo $theme_row->lightbox_ctrl_cont_border_radius; ?>px;
          /*top: <?php echo $theme_row->lightbox_ctrl_btn_height + 2 * $theme_row->lightbox_ctrl_btn_margin_top; ?>px;*/
          <?php
        }
        else {
          ?>
          border-top-left-radius: <?php echo $theme_row->lightbox_ctrl_cont_border_radius; ?>px;
          border-top-right-radius: <?php echo $theme_row->lightbox_ctrl_cont_border_radius; ?>px;
          /*bottom: <?php echo $theme_row->lightbox_ctrl_btn_height + 2 * $theme_row->lightbox_ctrl_btn_margin_top; ?>px;*/
          <?php
        }?>
        cursor: pointer;
        left: 50%;
        line-height: 0;
        margin-left: -<?php echo $theme_row->lightbox_toggle_btn_width / 2; ?>px;
        opacity: <?php echo number_format($theme_row->lightbox_ctrl_cont_transparent / 100, 2, ".", ""); ?>;
        filter: Alpha(opacity=<?php echo $theme_row->lightbox_ctrl_cont_transparent; ?>);
        position: absolute;
        text-align: center;
        width: <?php echo $theme_row->lightbox_toggle_btn_width; ?>px;
        z-index: 10150;
      }
      .bwge_close_btn {
        opacity: <?php echo number_format($theme_row->lightbox_close_btn_transparent / 100, 2, ".", ""); ?>;
        filter: Alpha(opacity=<?php echo $theme_row->lightbox_close_btn_transparent; ?>);
      }
      .bwge_spider_popup_close {
        background-color: #<?php echo $theme_row->lightbox_close_btn_bg_color; ?>;
        border-radius: <?php echo $theme_row->lightbox_close_btn_border_radius; ?>;
        border: <?php echo $theme_row->lightbox_close_btn_border_width; ?>px <?php echo $theme_row->lightbox_close_btn_border_style; ?> #<?php echo $theme_row->lightbox_close_btn_border_color; ?>;
        box-shadow: <?php echo $theme_row->lightbox_close_btn_box_shadow; ?>;
        color: #<?php echo $theme_row->lightbox_close_btn_color; ?>;
        height: <?php echo $theme_row->lightbox_close_btn_height; ?>px;
        font-size: <?php echo $theme_row->lightbox_close_btn_size; ?>px;
        right: <?php echo $theme_row->lightbox_close_btn_right; ?>px;
        top: <?php echo $theme_row->lightbox_close_btn_top; ?>px;
        width: <?php echo $theme_row->lightbox_close_btn_width; ?>px;
      }
      .bwge_spider_popup_close_fullscreen {
        color: #<?php echo $theme_row->lightbox_close_btn_full_color; ?>;
        font-size: <?php echo $theme_row->lightbox_close_btn_size; ?>px;
        right: 15px;
      }
      .bwge_spider_popup_close span,
      #bwge_spider_popup_left-ico span,
      #bwge_spider_popup_right-ico span {
        display: table-cell;
        text-align: center;
        vertical-align: middle;
      }
      #bwge_spider_popup_left-ico,
      #bwge_spider_popup_right-ico {
        background-color: #<?php echo $theme_row->lightbox_rl_btn_bg_color; ?>;
        border-radius: <?php echo $theme_row->lightbox_rl_btn_border_radius; ?>;
        border: <?php echo $theme_row->lightbox_rl_btn_border_width; ?>px <?php echo $theme_row->lightbox_rl_btn_border_style; ?> #<?php echo $theme_row->lightbox_rl_btn_border_color; ?>;
        box-shadow: <?php echo $theme_row->lightbox_rl_btn_box_shadow; ?>;
        color: #<?php echo $theme_row->lightbox_rl_btn_color; ?>;
        height: <?php echo $theme_row->lightbox_rl_btn_height; ?>px;
        font-size: <?php echo $theme_row->lightbox_rl_btn_size; ?>px;
        width: <?php echo $theme_row->lightbox_rl_btn_width; ?>px;
        opacity: <?php echo number_format($theme_row->lightbox_rl_btn_transparent / 100, 2, ".", ""); ?>;
        filter: Alpha(opacity=<?php echo $theme_row->lightbox_rl_btn_transparent; ?>);
      }
      <?php
      if($option_row->autohide_lightbox_navigation){?>
      #bwge_spider_popup_left-ico{
        left: -9999px;
      }
      #bwge_spider_popup_right-ico{
        left: -9999px;
      }      
      <?php }
      else { ?>
        #bwge_spider_popup_left-ico {
        left: 20px;
        }
        #bwge_spider_popup_right-ico {
          left: auto;
          right: 20px;
        }
      <?php } ?>
      .bwge_ctrl_btn:hover,
      .bwge_toggle_btn:hover,
      .bwge_spider_popup_close:hover,
      .bwge_spider_popup_close_fullscreen:hover,
      #bwge_spider_popup_left-ico:hover,
      #bwge_spider_popup_right-ico:hover {
        color: #<?php echo $theme_row->lightbox_close_rl_btn_hover_color; ?>;
        cursor: pointer;
      }
      .bwge_image_wrap {
        height: inherit;
        display: table;
        position: absolute;
        text-align: center;
        width: inherit;
      }
      .bwge_image_wrap * {
        -moz-user-select: none;
        -khtml-user-select: none;
        -webkit-user-select: none;
        -ms-user-select: none;
        user-select: none;
      }
      .bwge_comment_wrap, .bwge_ecommerce_wrap {
        bottom: 0;
        left: 0;
        overflow: hidden;
        position: absolute;
        right: 0;
        top: 0;
        z-index: -1;
      }
      .bwge_comment_container,  .bwge_ecommerce_container {
        -moz-box-sizing: border-box;
        background-color: #<?php echo $theme_row->lightbox_comment_bg_color; ?>;
        color: #<?php echo $theme_row->lightbox_comment_font_color; ?>;
        font-size: <?php echo $theme_row->lightbox_comment_font_size; ?>px;
        font-family: <?php echo $theme_row->lightbox_comment_font_style; ?>;
        height: 100%;
        overflow: hidden;
        position: absolute;
        <?php echo $theme_row->lightbox_comment_pos; ?>: -<?php echo $theme_row->lightbox_comment_width; ?>px;
        top: 0;
        width: <?php echo $theme_row->lightbox_comment_width; ?>px;
        z-index: 10103;
      }
      #bwge_ecommerce{
          padding:10px;
        }
        .bwge_ecommerce_body {
          background: none !important;
          border: none !important;
        }
        .bwge_ecommerce_body  p, .bwge_ecommerce_body span, .bwge_ecommerce_body div {
          color:#<?php echo $theme_row->lightbox_comment_font_color; ?>!important;
        } 	  																
        .bwge_tabs{
          list-style-type:none;
          margin: 0px;
          padding:0;
          background: none !important;
        }
        .bwge_tabs li{
          float:left;
          border-top: 1px solid #<?php echo $theme_row->lightbox_bg_color; ?>!important;
          border-left: 1px solid #<?php echo $theme_row->lightbox_bg_color; ?>!important;
          border-right: 1px solid #<?php echo $theme_row->lightbox_bg_color; ?>!important;
          margin-right: 1px !important;
          border-radius: <?php echo $theme_row->lightbox_comment_button_border_radius; ?> <?php echo $theme_row->lightbox_comment_button_border_radius; ?> 0 0;
          position:relative;
        }
       .bwge_tabs li:hover  , .bwge_tabs li.bwge_active {
          border-top: 1px solid #<?php echo $theme_row->lightbox_comment_font_color; ?>!important;
          border-left: 1px solid #<?php echo $theme_row->lightbox_comment_font_color; ?>!important;
          border-right: 1px solid #<?php echo $theme_row->lightbox_comment_font_color; ?>!important;
          border-bottom:none!important;
          bottom:-1px;
        }

       .bwge_tabs li a, .bwge_tabs li a:hover, .bwge_tabs li.bwge_active a{		
         text-decoration:none;
         display:block;
         width:100%;
         outline:0 !important;
         padding:8px 5px !important;
         font-weight: bold;
         font-size: 13px;
       }       
       .bwge_tabs li a{
          color:#<?php echo $theme_row->lightbox_comment_bg_color; ?>!important;
          background:#808080!important;
          border-radius: <?php echo $theme_row->lightbox_comment_button_border_radius; ?>;
        }
       .bwge_tabs li:hover a , .bwge_tabs li.bwge_active a{
          color:#<?php echo $theme_row->lightbox_comment_font_color; ?>!important;
          background:#<?php echo $theme_row->lightbox_bg_color; ?>!important;
          border-radius:0!important;
        }	  
       .bwge_tabs_container{
          border:1px solid #<?php echo $theme_row->lightbox_comment_font_color; ?>;
          border-radius: 0 0 <?php echo $theme_row->lightbox_comment_button_border_radius; ?> <?php echo $theme_row->lightbox_comment_button_border_radius; ?>;
       }	  

      .bwge_pricelist {
        padding:0 !important;
        color:#<?php echo $theme_row->lightbox_comment_font_color; ?>!important;
      }
      .bwge_add_to_cart{
         margin: 5px 0px 15px;
      }
      
      .bwge_add_to_cart a{
        border: 1px solid #<?php echo $theme_row->lightbox_comment_font_color; ?>!important;
        padding: 5px 10px;
        color:#<?php echo $theme_row->lightbox_comment_font_color; ?>!important;
        border-radius: <?php echo $theme_row->lightbox_comment_button_border_radius; ?>;
        text-decoration:none !important; 
        display:block;
      }
      .bwge_add_to_cart_title{
        font-size:17px;
      }
      .bwge_add_to_cart div:first-child{
        float:left;
      }
      .bwge_add_to_cart div:last-child{
        float:right;
        margin-top: 4px;
      }
      .bwge_tabs:after,  .bwge_add_to_cart:after{
        clear:both;
        content:"";
        display:table;
       }
      #downloads table tr td,   #downloads table tr th{
        padding: 6px 10px !important;
        text-transform:none !important;
      }
      .bwge_comments , .bwge_ecommerce_panel{
        bottom: 0;
        font-size: <?php echo $theme_row->lightbox_comment_font_size; ?>px;
        font-family: <?php echo $theme_row->lightbox_comment_font_style; ?>;
        height: 100%;
        left: 0;
        overflow-x: hidden;
        overflow-y: auto;
        position: absolute;
        top: 0;
        width: 100%;
        z-index: 10101;
      }
      .bwge_comments {
        height: 100%;
      }
      .bwge_comments p,
      .bwge_comment_body_p {
        margin: 5px !important;
        text-align: left;
        word-wrap: break-word;
        word-break: break-word;
      }
      .bwge_ecommerce_panel p{
        padding: 5px !important;
        text-align: left;
        word-wrap: break-word;
        word-break: break-word;
        margin:0 !important;
      }
      .bwge_comments input[type="submit"], .bwge_ecommerce_panel input[type="button"] {
        background: none repeat scroll 0 0 #<?php echo $theme_row->lightbox_comment_button_bg_color; ?>;
        border: <?php echo $theme_row->lightbox_comment_button_border_width; ?>px <?php echo $theme_row->lightbox_comment_button_border_style; ?> #<?php echo $theme_row->lightbox_comment_button_border_color; ?>;
        border-radius: <?php echo $theme_row->lightbox_comment_button_border_radius; ?>;
        color: #<?php echo $theme_row->lightbox_comment_font_color; ?>;
        cursor: pointer;
        padding: <?php echo $theme_row->lightbox_comment_button_padding; ?>;
      }
      .bwge_comments input[type="text"],
      .bwge_comments textarea,
      .bwge_ecommerce_panel input[type="text"],
      .bwge_ecommerce_panel input[type="number"],
      .bwge_ecommerce_panel textarea , .bwge_ecommerce_panel select {
        background: none repeat scroll 0 0 #<?php echo $theme_row->lightbox_comment_input_bg_color; ?>;
        border: <?php echo $theme_row->lightbox_comment_input_border_width; ?>px <?php echo $theme_row->lightbox_comment_input_border_style; ?> #<?php echo $theme_row->lightbox_comment_input_border_color; ?>;
        border-radius: <?php echo $theme_row->lightbox_comment_input_border_radius; ?>;
        color: #<?php echo $theme_row->lightbox_comment_font_color; ?>;
        padding: <?php echo $theme_row->lightbox_comment_input_padding; ?>;
        width: 100%;
      }
      .bwge_comments textarea {
        resize: vertical;
      }
      .bwge_comment_header_p {
        border-top: <?php echo $theme_row->lightbox_comment_separator_width; ?>px <?php echo $theme_row->lightbox_comment_separator_style; ?> #<?php echo $theme_row->lightbox_comment_separator_color; ?>;
      }
      .bwge_comment_header {
        color: #<?php echo $theme_row->lightbox_comment_font_color; ?>;
        font-size: <?php echo $theme_row->lightbox_comment_author_font_size; ?>px;
      }
      .bwge_comment_date {
        color: #<?php echo $theme_row->lightbox_comment_font_color; ?>;
        float: right;
        font-size: <?php echo $theme_row->lightbox_comment_date_font_size; ?>px;
      }
      .bwge_comment_body {
        color: #<?php echo $theme_row->lightbox_comment_font_color; ?>;
        font-size: <?php echo $theme_row->lightbox_comment_body_font_size; ?>px;
      }
      .bwge_comment_delete_btn {
        color: #FFFFFF;
        cursor: pointer;
        float: right;
        font-size: 14px;
        margin: 2px;
      }
      .bwge_comments_close , .bwge_ecommerce_close{
        cursor: pointer;
        line-height: 0;
        position: relative;
        font-size: 13px;
        text-align: <?php echo (($theme_row->lightbox_comment_pos == 'left') ? 'right' : 'left'); ?>!important;
        margin: 5px;
        z-index: 10150;
      }
      .bwge_ecommerce_panel a:hover{
        text-decoration:underline;
      }
      .bwge_comment_textarea::-webkit-scrollbar {
        width: 4px;
      }
      .bwge_comment_textarea::-webkit-scrollbar-track {
      }
      .bwge_comment_textarea::-webkit-scrollbar-thumb {
        background-color: rgba(255, 255, 255, 0.55);
        border-radius: 2px;
      }  
      .bwge_comment_textarea::-webkit-scrollbar-thumb:hover {
        background-color: #D9D9D9;
      }
      .bwge_ctrl_btn_container a,
      .bwge_ctrl_btn_container a:hover {
        text-decoration: none;
      }


      .bwge_image_container {
        display: table;
        position: absolute;
        text-align: center;
        <?php echo $theme_row->lightbox_filmstrip_pos; ?>: <?php echo ($filmstrip_direction == 'horizontal' ? $image_filmstrip_height : $image_filmstrip_width); ?>px;
        vertical-align: middle;
        width: 100%;
      }      
      .bwge_filmstrip_container {
        display: <?php echo ($filmstrip_direction == 'horizontal'? 'table' : 'block'); ?>;
        height: <?php echo ($filmstrip_direction == 'horizontal'? $image_filmstrip_height : $image_height); ?>px;
        position: absolute;
        width: <?php echo ($filmstrip_direction == 'horizontal' ? $image_width : $image_filmstrip_width); ?>px;
        z-index: 10150;
        <?php echo $theme_row->lightbox_filmstrip_pos; ?>: 0;
      }
      .bwge_filmstrip {
        <?php echo $left_or_top; ?>: 20px;
        overflow: hidden;
        position: absolute;
        <?php echo $width_or_height; ?>: <?php echo ($filmstrip_direction == 'horizontal' ? $image_width - 40 : $image_height - 40); ?>px;
        z-index: 10106;
      }
      .bwge_filmstrip_thumbnails {
        height: <?php echo ($filmstrip_direction == 'horizontal' ? $image_filmstrip_height : ($image_filmstrip_height + $filmstrip_thumb_margin_hor) * count($image_rows)); ?>px;
        <?php echo $left_or_top; ?>: 0px;
        margin: 0 auto;
        overflow: hidden;
        position: relative;
        width: <?php echo ($filmstrip_direction == 'horizontal' ? ($image_filmstrip_width + $filmstrip_thumb_margin_hor) * count($image_rows) : $image_filmstrip_width); ?>px;
      }
      .bwge_filmstrip_thumbnail {
        position: relative;
        background: none;
        border: <?php echo $theme_row->lightbox_filmstrip_thumb_border_width; ?>px <?php echo $theme_row->lightbox_filmstrip_thumb_border_style; ?> #<?php echo $theme_row->lightbox_filmstrip_thumb_border_color; ?>;
        border-radius: <?php echo $theme_row->lightbox_filmstrip_thumb_border_radius; ?>;
        cursor: pointer;
        float: left;
        height: <?php echo $image_filmstrip_height; ?>px;
        margin: <?php echo $theme_row->lightbox_filmstrip_thumb_margin; ?>;
        width: <?php echo $image_filmstrip_width; ?>px;
        overflow: hidden;
      }
      .bwge_thumb_active {
        opacity: 1;
        filter: Alpha(opacity=100);
        border: <?php echo $theme_row->lightbox_filmstrip_thumb_active_border_width; ?>px solid #<?php echo $theme_row->lightbox_filmstrip_thumb_active_border_color; ?>;
      }
      .bwge_thumb_deactive {
        opacity: <?php echo number_format($theme_row->lightbox_filmstrip_thumb_deactive_transparent / 100, 2, ".", ""); ?>;
        filter: Alpha(opacity=<?php echo $theme_row->lightbox_filmstrip_thumb_deactive_transparent; ?>);
      }
      .bwge_filmstrip_thumbnail_img {
        display: block;
        opacity: 1;
        filter: Alpha(opacity=100);
      }
      .bwge_filmstrip_left {
        background-color: #<?php echo $theme_row->lightbox_filmstrip_rl_bg_color; ?>;
        cursor: pointer;
        display: <?php echo ($filmstrip_direction == 'horizontal' ? 'table-cell' : 'block') ?>;
        vertical-align: middle;
        <?php echo $width_or_height; ?>: 20px;
        z-index: 10106;
        <?php echo $left_or_top; ?>: 0;
        <?php echo ($filmstrip_direction == 'horizontal' ? '' : 'position: absolute;') ?>
        <?php echo ($filmstrip_direction == 'horizontal' ? '' : 'width: 100%;') ?> 
      }
      .bwge_filmstrip_right {
        background-color: #<?php echo $theme_row->lightbox_filmstrip_rl_bg_color; ?>;
        cursor: pointer;
        <?php echo($filmstrip_direction == 'horizontal' ? 'right' : 'bottom') ?>: 0;
        <?php echo $width_or_height; ?>: 20px;
        display: <?php echo ($filmstrip_direction == 'horizontal' ? 'table-cell' : 'block') ?>;
        vertical-align: middle;
        z-index: 10106;
        <?php echo ($filmstrip_direction == 'horizontal' ? '' : 'position: absolute;') ?>
        <?php echo ($filmstrip_direction == 'horizontal' ? '' : 'width: 100%;') ?>
      }
      .bwge_filmstrip_left i,
      .bwge_filmstrip_right i {
        color: #<?php echo $theme_row->lightbox_filmstrip_rl_btn_color; ?>;
        font-size: <?php echo $theme_row->lightbox_filmstrip_rl_btn_size; ?>px;
      }
      .bwge_none_selectable {
        -webkit-touch-callout: none;
        -webkit-user-select: none;
        -khtml-user-select: none;
        -moz-user-select: none;
        -ms-user-select: none;
        user-select: none;
      }
      .bwge_watermark_container {
        display: table-cell;
        margin: 0 auto;
        position: relative;
        vertical-align: middle;
      }
      .bwge_watermark_spun {
        display: table-cell;
        overflow: hidden;
        position: relative;
        text-align: <?php echo $watermark_position[1]; ?>;
        vertical-align: <?php echo $watermark_position[0]; ?>;
        /*z-index: 10140;*/
      }
      .bwge_watermark_image {
        margin: 4px;
        max-height: <?php echo $watermark_height; ?>px;
        max-width: <?php echo $watermark_width; ?>px;
        opacity: <?php echo number_format($watermark_opacity / 100, 2, ".", ""); ?>;
        filter: Alpha(opacity=<?php echo $watermark_opacity; ?>);
        position: relative;
        z-index: 10141;
      }
      .bwge_watermark_text,
      .bwge_watermark_text:hover {
        text-decoration: none;
        margin: 4px;
        font-size: <?php echo $watermark_font_size; ?>px;
        font-family: <?php echo $watermark_font; ?>;
        color: #<?php echo $watermark_color; ?> !important;
        opacity: <?php echo number_format($watermark_opacity / 100, 2, ".", ""); ?>;
        filter: Alpha(opacity=<?php echo $watermark_opacity; ?>);
        position: relative;
        z-index: 10141;
      }
      .bwge_slide_container {
        display: table-cell;
        position: absolute;
        vertical-align: middle;
        width: 100%;
        height: 100%;
      }
      .bwge_slide_bg {
        margin: 0 auto;
        width: inherit;
        height: inherit;
      }
      .bwge_slider {
        height: inherit;
        width: inherit;
      }
      .bwge_popup_image_spun {
        height: inherit;
        display: table-cell;
        filter: Alpha(opacity=100);
        opacity: 1;
        position: absolute;
        vertical-align: middle;
        width: inherit;
        z-index: 2;
      }
      .bwge_popup_image_second_spun {
        width: inherit;
        height: inherit;
        display: table-cell;
        filter: Alpha(opacity=0);
        opacity: 0;
        position: absolute;
        vertical-align: middle;
        z-index: 1;
      }
      .bwge_grid {
        display: none;
        height: 100%;
        overflow: hidden;
        position: absolute;
        width: 100%;
      }
      .bwge_gridlet {
        opacity: 1;
        filter: Alpha(opacity=100);
        position: absolute;
      }
      .bwge_image_info_container1 {
        display: <?php echo $popup_info_always_show ? 'table-cell' : 'none'; ?>;
      }

      .bwge_image_info_spun {
        text-align: <?php echo $theme_row->lightbox_info_align; ?>;
        vertical-align: <?php echo $theme_row->lightbox_info_pos; ?>;
      }
      .bwge_image_hit_spun {
        text-align: <?php echo $theme_row->lightbox_hit_align; ?>;
        vertical-align: <?php echo $theme_row->lightbox_hit_pos; ?>;
      }
      .bwge_image_hit {
        background: rgba(<?php echo $rgb_bwge_image_hit_bg_color['red']; ?>, <?php echo $rgb_bwge_image_hit_bg_color['green']; ?>, <?php echo $rgb_bwge_image_hit_bg_color['blue']; ?>, <?php echo number_format($theme_row->lightbox_hit_bg_transparent / 100, 2, ".", ""); ?>);
        border: <?php echo $theme_row->lightbox_hit_border_width; ?>px <?php echo $theme_row->lightbox_hit_border_style; ?> #<?php echo $theme_row->lightbox_hit_border_color; ?>;
        border-radius: <?php echo $theme_row->lightbox_info_border_radius; ?>;
        <?php echo ((!$enable_image_filmstrip || $theme_row->lightbox_filmstrip_pos != 'bottom') && $theme_row->lightbox_ctrl_btn_pos == 'bottom' && $theme_row->lightbox_hit_pos == 'bottom') ? 'bottom: ' . ($theme_row->lightbox_ctrl_btn_height + 2 * $theme_row->lightbox_ctrl_btn_margin_top) . 'px;' : '' ?>
        margin: <?php echo $theme_row->lightbox_hit_margin; ?>;
        padding: <?php echo $theme_row->lightbox_hit_padding; ?>;
        <?php echo ((!$enable_image_filmstrip || $theme_row->lightbox_filmstrip_pos != 'top') && $theme_row->lightbox_ctrl_btn_pos == 'top' && $theme_row->lightbox_hit_pos == 'top') ? 'top: ' . ($theme_row->lightbox_ctrl_btn_height + 2 * $theme_row->lightbox_ctrl_btn_margin_top) . 'px;' : '' ?>
      }
      .bwge_image_hits,
      .bwge_image_hits * {
        color: #<?php echo $theme_row->lightbox_hit_color; ?> !important;
        font-family: <?php echo $theme_row->lightbox_hit_font_style; ?>;
        font-size: <?php echo $theme_row->lightbox_hit_font_size; ?>px;
        font-weight: <?php echo $theme_row->lightbox_hit_font_weight; ?>;
      }
      .bwge_image_info {
        background: rgba(<?php echo $rgb_bwge_image_info_bg_color['red']; ?>, <?php echo $rgb_bwge_image_info_bg_color['green']; ?>, <?php echo $rgb_bwge_image_info_bg_color['blue']; ?>, <?php echo number_format($theme_row->lightbox_info_bg_transparent / 100, 2, ".", ""); ?>);
        border: <?php echo $theme_row->lightbox_info_border_width; ?>px <?php echo $theme_row->lightbox_info_border_style; ?> #<?php echo $theme_row->lightbox_info_border_color; ?>;
        border-radius: <?php echo $theme_row->lightbox_info_border_radius; ?>;
        <?php echo ((!$enable_image_filmstrip || $theme_row->lightbox_filmstrip_pos != 'bottom') && $theme_row->lightbox_ctrl_btn_pos == 'bottom' && $theme_row->lightbox_info_pos == 'bottom') ? 'bottom: ' . ($theme_row->lightbox_ctrl_btn_height + 2 * $theme_row->lightbox_ctrl_btn_margin_top) . 'px;' : '' ?>
        <?php if($params_array['popup_info_full_width']) { ?>
        width: 100%;
        <?php } else { ?>
        width: 33%;
        margin: <?php echo $theme_row->lightbox_info_margin; ?>;
        <?php } ?>
        padding: <?php echo $theme_row->lightbox_info_padding; ?>;
        <?php echo ((!$enable_image_filmstrip || $theme_row->lightbox_filmstrip_pos != 'top') && $theme_row->lightbox_ctrl_btn_pos == 'top' && $theme_row->lightbox_info_pos == 'top') ? 'top: ' . ($theme_row->lightbox_ctrl_btn_height + 2 * $theme_row->lightbox_ctrl_btn_margin_top) . 'px;' : '' ?>
      }
      .bwge_image_title,
      .bwge_image_title * {
        color: #<?php echo $theme_row->lightbox_title_color; ?> !important;
        font-family: <?php echo $theme_row->lightbox_title_font_style; ?>;
        font-size: <?php echo $theme_row->lightbox_title_font_size; ?>px;
        font-weight: <?php echo $theme_row->lightbox_title_font_weight; ?>;
      }
      .bwge_image_description,
      .bwge_image_description * {
        color: #<?php echo $theme_row->lightbox_description_color; ?> !important;
        font-family: <?php echo $theme_row->lightbox_description_font_style; ?>;
        font-size: <?php echo $theme_row->lightbox_description_font_size; ?>px;
        font-weight: <?php echo $theme_row->lightbox_description_font_weight; ?>;
      }



			@media (max-width: 480px) {
				.bwge_image_count_container {
					display: none;
				}
        .bwge_image_title,
        .bwge_image_title * {
					font-size: 12px;
				}
        .bwge_image_description,
        .bwge_image_description * {
					font-size: 10px;
				}
			}
      .bwge_image_count_container {
        left: 0;
        line-height: 1;
        position: absolute;
        vertical-align: middle;
      }

    </style>
    <script>
      var data = [];
      var event_stack = [];
      <?php
      $image_id_exist = FALSE;
      foreach ($image_rows as $key => $image_row) {
        if ($image_row->id == $image_id) {
          $current_avg_rating = $image_row->avg_rating;
          $current_rate = $image_row->rate;
          $current_rate_count = $image_row->rate_count;
          $current_image_key = $key;
        }
        if ($image_row->id == $current_image_id) {
          $current_image_alt = $image_row->alt;
          $current_image_hit_count = $image_row->hit_count;
          $current_image_description = str_replace(array("\r\n", "\n", "\r"), esc_html('<br />'), $image_row->description);
          $current_image_url = $image_row->image_url;
          $current_thumb_url = $image_row->thumb_url;
          $current_filetype = $image_row->filetype;
          $current_filename = $image_row->filename;
          $image_id_exist = TRUE;
        }
		
	$current_pricelist_id = $this->model->get_image_pricelist($image_row->id) ?  $this->model->get_image_pricelist($image_row->id) : 0;
	$_pricelist_data = $this->model->get_image_pricelists($current_pricelist_id);
	$_pricelist = $_pricelist_data["pricelist"];
        ?>
        data["<?php echo $key; ?>"] = [];
        data["<?php echo $key; ?>"]["number"] = <?php echo $key + 1; ?>;
        data["<?php echo $key; ?>"]["id"] = "<?php echo $image_row->id; ?>";
        data["<?php echo $key; ?>"]["alt"] = "<?php echo str_replace(array("\r\n", "\n", "\r"), esc_html('<br />'), $image_row->alt); ?>";
        data["<?php echo $key; ?>"]["description"] = "<?php echo str_replace(array("\r\n", "\n", "\r"), esc_html('<br />'), $image_row->description); ?>";
        data["<?php echo $key; ?>"]["image_url"] = "<?php echo $image_row->image_url; ?>";
        data["<?php echo $key; ?>"]["thumb_url"] = "<?php echo $image_row->thumb_url; ?>";
        data["<?php echo $key; ?>"]["date"] = "<?php echo $image_row->date; ?>";
        data["<?php echo $key; ?>"]["comment_count"] = "<?php echo $image_row->comment_count; ?>";
        data["<?php echo $key; ?>"]["filetype"] = "<?php echo $image_row->filetype; ?>";
        data["<?php echo $key; ?>"]["filename"] = "<?php echo $image_row->filename; ?>";
        data["<?php echo $key; ?>"]["avg_rating"] = "<?php echo $image_row->avg_rating; ?>";
        data["<?php echo $key; ?>"]["rate"] = "<?php echo $image_row->rate; ?>";
        data["<?php echo $key; ?>"]["rate_count"] = "<?php echo $image_row->rate_count; ?>";
        data["<?php echo $key; ?>"]["hit_count"] = "<?php echo $image_row->hit_count; ?>";
        data["<?php echo $key; ?>"]["pricelist"] = "<?php echo $current_pricelist_id ? $current_pricelist_id : 0; ?>";
        data["<?php echo $key; ?>"]["pricelist_manual_price"] = "<?php echo isset($_pricelist->price) ? $_pricelist->price : 0; ?>";
        data["<?php echo $key; ?>"]["pricelist_sections"] = "<?php echo isset($_pricelist->sections) ? $_pricelist->sections : ""; ?>";
        <?php
      }
      ?>
    </script>
    <?php
    if (!$image_id_exist) {
      echo BWGELibrary::message(__('The image has been deleted.', 'bwge'), 'error');
      die();
    }
    ?>
    <div class="bwge_image_wrap">
      <?php
      if ($enable_image_ctrl_btn) {
      ?>
      <div class="bwge_btn_container">
        <div class="bwge_ctrl_btn_container">
					<?php
          if ($option_row->show_image_counts) {
            ?>
            <span class="bwge_image_count_container bwge_ctrl_btn">
              <span class="bwge_image_count"><?php echo $current_image_key + 1; ?></span> / 
              <span><?php echo count($image_rows); ?></span>
            </span>
            <?php
          }
					?>
          <i title="<?php echo __('Play', 'bwge'); ?>" class="bwge_ctrl_btn bwge_play_pause fa fa-play"></i>
          <?php if ($enable_image_fullscreen) {
                  if (!$open_with_fullscreen) {
          ?>
          <i title="<?php echo __('Maximize', 'bwge'); ?>" class="bwge_ctrl_btn bwge_resize-full fa fa-resize-full "></i>
          <?php
          }
          ?>
          <i title="<?php echo __('Fullscreen', 'bwge'); ?>" class="bwge_ctrl_btn bwge_fullscreen fa fa-fullscreen"></i>
          <?php } if ($popup_enable_info) { ?>
          <i title="<?php echo __('Show info', 'bwge'); ?>" class="bwge_ctrl_btn bwge_info fa fa-info"></i>
          <?php } 
          $is_embed = preg_match('/EMBED/', $current_filetype) == 1 ? TRUE : FALSE;

          if ($option_row->popup_enable_fullsize_image) {
            ?>
            <a id="bwge_fullsize_image" href="<?php echo !$is_embed ? site_url() . '/' . $WD_BWGE_UPLOAD_DIR . $current_image_url : $current_image_url; ?>" target="_blank">
              <i title="<?php echo __('Open image in original size.', 'bwge'); ?>" class="bwge_ctrl_btn fa fa-external-link"></i>
            </a>
            <?php
          }
          if ($option_row->popup_enable_download) {
            $style = 'none';
            $current_image_arr = explode('/', $current_image_url);
            if (!$is_embed) {
              $download_href = site_url() . '/' . $WD_BWGE_UPLOAD_DIR . $current_image_url;
              $style = 'inline-block';
            }
            elseif (preg_match('/FLICKR/', $current_filetype) == 1) {
              $download_href = $current_filename;
              $style = 'inline-block';
            }
            elseif (preg_match('/INSTAGRAM/', $current_filetype) == 1) {
              $download_href = substr_replace($current_thumb_url, 'l', -1);
              $style = 'inline-block';
            }
            ?>
            <a id="bwge_download" href="<?php echo $download_href; ?>" target="_blank" download="<?php echo end($current_image_arr); ?>" style="display: <?php echo $style; ?>;">
              <i title="<?php echo __('Download original image', 'bwge'); ?>" class="bwge_ctrl_btn fa fa-download"></i>
            </a>
            <?php
          }
		 if( $enable_image_ecommerce == 1  ){
		 
		   ?>				
				<i title="<?php echo __('Ecommerce', 'bwge'); ?>" style="<?php echo $pricelist_id == 0 ? "display:none;": "";?>" class="bwge_ctrl_btn bwge_ecommerce fa fa-shopping-cart" ></i>
		   <?php
		  } 
          ?>
        </div>
        <div class="bwge_toggle_container">
          <i class="bwge_toggle_btn fa <?php echo (($theme_row->lightbox_ctrl_btn_pos == 'top') ? 'fa-angle-up' : 'fa-angle-down'); ?>"></i>
        </div>
      </div>
      <?php
      }
      $current_pos = 0;

      if ($watermark_type != 'none') {
      ?>
      <div class="bwge_image_container">
        <div class="bwge_watermark_container">
          <div style="display:table; margin:0 auto;">
            <span class="bwge_watermark_spun" id="bwge_watermark_container">
              <?php
              if ($watermark_type == 'image') {
              ?>
              <a href="<?php echo urldecode($watermark_link); ?>" target="_blank">
                <img class="bwge_watermark_image bwge_watermark" src="<?php echo $watermark_url; ?>" />
              </a>
              <?php
              }
              elseif ($watermark_type == 'text') {
              ?>
              <a class="bwge_none_selectable bwge_watermark_text bwge_watermark" target="_blank" href="<?php echo $watermark_link; ?>"><?php echo stripslashes($watermark_text); ?></a>
              <?php
              }
              ?>
            </span>
          </div>
        </div>
      </div>
      <?php
      }
      ?>
      <div id="bwge_image_container" class="bwge_image_container">
        <div class="bwge_image_info_container1">
          <div class="bwge_image_info_container2">
            <span class="bwge_image_info_spun">
              <div class="bwge_image_info" <?php if(trim($current_image_alt) == '' && trim($current_image_description) == '') { echo 'style="background:none;"'; } ?>>
                <div class="bwge_image_title"><?php echo html_entity_decode($current_image_alt); ?></div>
                <div class="bwge_image_description"><?php echo html_entity_decode($current_image_description); ?></div>
              </div>
            </span>
          </div>
        </div>

        <div class="bwge_slide_container">
          <div class="bwge_slide_bg">
            <div class="bwge_slider">
          <?php
          $current_key = -6;
          foreach ($image_rows as $key => $image_row) {
            
            $is_embed = preg_match('/EMBED/',$image_row->filetype)==1 ? true :false;
            $is_embed_instagram_post = preg_match('/INSTAGRAM_POST/',$image_row->filetype)==1 ? true :false;
            $is_embed_instagram_video = preg_match('/INSTAGRAM_VIDEO/', $image_row->filetype) == 1 ? true :false;
            if ($image_row->id == $current_image_id) {
              $current_key = $key;
              ?>
              <span class="bwge_popup_image_spun" id="bwge_popup_image" image_id="<?php echo $image_row->id; ?>">
                <span class="bwge_popup_image_spun1" style="display: <?php echo (!$is_embed ? 'table' : 'block'); ?>; width: inherit; height: inherit;">
                  <span class="bwge_popup_image_spun2" style="display: <?php echo (!$is_embed ? 'table-cell' : 'block'); ?>; vertical-align: middle; text-align: center; height: 100%;">
                    <?php 
                      if (!$is_embed) {
                      ?>
                      <img class="bwge_popup_image bwge_popup_watermark" src="<?php echo site_url() . '/' . $WD_BWGE_UPLOAD_DIR . $image_row->image_url; ?>" alt="<?php echo $image_row->alt; ?>" />
                      <?php 
                      }
                      else { /*$is_embed*/ ?>
                        <span id="embed_conteiner"  class="bwge_popup_embed bwge_popup_watermark" style="display: block; table-layout: fixed; height: 100%;">
                        <?php echo $is_embed_instagram_video ? '<div class="bwge_inst_play_btn_cont" onclick="bwge_play_instagram_video(this)" ><div class="bwge_inst_play"></div></div>' : '';
                        if($is_embed_instagram_post){
                          $post_width = $image_width - ($filmstrip_direction == 'vertical' ? $image_filmstrip_width : 0);
                          $post_height = $image_height - ($filmstrip_direction == 'horizontal' ? $image_filmstrip_height : 0);
                          if($post_height <$post_width +88 ){
                            $post_width =$post_height -88; 
                          }
                          else{
                           $post_height =$post_width +88;  
                          }
                          BWGELibraryEmbed::display_embed($image_row->filetype, $image_row->image_url, $image_row->filename, array('class'=>"bwge_embed_frame", 'frameborder'=>"0", 'style'=>"width:".$post_width."px; height:".$post_height."px; vertical-align:middle; display:inline-block; position:relative;"));
                        }
                        else{
                          BWGELibraryEmbed::display_embed($image_row->filetype, $image_row->image_url, $image_row->filename, array('class'=>"bwge_embed_frame", 'frameborder'=>"0", 'allowfullscreen'=>"allowfullscreen", 'style'=>"width:inherit; height:inherit; vertical-align:middle; display:block;"));
                        }
                        ?>
                      </span>
                      <?php
                      }
                    ?>                    
                  </span>
                </span>
              </span>
              <span class="bwge_popup_image_second_spun">                
              </span>
              <input type="hidden" id="bwge_current_image_key" value="<?php echo $key; ?>" />
              <?php
              break;
            }
          }
          ?>
            </div>
          </div>
        </div>
        <a id="bwge_spider_popup_left" <?php echo ($option_row->enable_loop == 0 && $current_key == 0) ? 'style="display: none;"' : ''; ?>><span id="bwge_spider_popup_left-ico"><span><i class="bwge_prev_btn fa <?php echo $theme_row->lightbox_rl_btn_style; ?>-left"></i></span></span></a>
        <a id="bwge_spider_popup_right" <?php echo ($option_row->enable_loop == 0 && $current_key == count($image_rows) - 1) ? 'style="display: none;"' : ''; ?>><span id="bwge_spider_popup_right-ico"><span><i class="bwge_next_btn fa <?php echo $theme_row->lightbox_rl_btn_style; ?>-right"></i></span></span></a>
      </div>
    </div>
    <?php
			
			$pricelist = $pricelist_data["pricelist"]; 
			$download_items = $pricelist_data["download_items"]; 
			$parameters = $pricelist_data["parameters"]; 
			$options = $pricelist_data["options"]; 
			$products_in_cart = $pricelist_data["products_in_cart"]; 
			$pricelist_sections = $pricelist->sections ? explode("," , $pricelist->sections) : array();
	
	?>
			<div class="bwge_ecommerce_wrap bwge_popup_sidebar_wrap" id="bwge_ecommerce_wrap">
				<div class="bwge_ecommerce_container bwge_popup_sidebar_container bwge_close">
					<div id="bwge_ecommerce_ajax_loading" style="position:absolute;">
						<div id="bwge_ecommerce_opacity_div" style="display:none; background-color:rgba(255, 255, 255, 0.2); position:absolute; z-index:10150;"></div>
						<span id="bwge_ecommerce_loading_div" style="display:none; text-align:center; position:relative; vertical-align:middle; z-index:10170; background-image:url(<?php echo WD_BWGE_URL . '/images/ajax_loader.png'; ?>); float: none; width:50px;height:50px;background-size:50px 50px; background-repeat: no-repeat; background-position: 50% 50%;">
		
						</span>
					</div>
					
					<div class="bwge_ecommerce_panel bwge_popup_sidebar_panel bwge_popup_sidebar" style="text-align:left;">
						<div id="bwge_ecommerce">
							<p title="<?php echo __('Hide Ecommerce', 'bwge'); ?>" class="bwge_ecommerce_close bwge_popup_sidebar_close" >
								<i class="bwge_ecommerce_close_btn bwge_popup_sidebar_close_btn  fa fa-arrow-<?php echo $theme_row->lightbox_comment_pos; ?>" ></i>
							</p>
							<form id="bwge_ecommerce_form" method="post" action="<?php echo $popup_url; ?>">
								<div class="bwge_add_to_cart">
									<div>
                                        <img src="<?php echo WD_BWGE_URL ?>/images/add-to-cart-icon.png" style="vertical-align:bottom;">&nbsp;
                                        <span class="bwge_add_to_cart_title"><?php echo (__('Add to cart', 'bwge')); ?></span>
                                      </div>
                                    <div>
                                        <a href="<?php echo get_permalink($options->checkout_page);?>"><?php echo "<span class='products_in_cart'>".$products_in_cart ."</span> ". __('items', 'bwge'); ?></a> 
                                      </div>
                                                    
								</div>

								<div class="bwge_ecommerce_body">
									<ul class="bwge_tabs" <?php if(count($pricelist_sections)<=1) echo "style='display:none;'"; ?>>
										<li id="manual_li" <?php if(!in_array("manual",$pricelist_sections)) echo "style='display:none;'"; ?> class="bwge_active">										
											<a href= "#manual">
												<span class="manualh4" >	
													<?php echo __('Prints and products', 'bwge'); ?>
												</span>
											</a>											
										</li>
										<li id="downloads_li" <?php if(!in_array("downloads",$pricelist_sections)) echo "style='display:none;'"; ?>>																					
											<a href= "#downloads">
											<span class="downloadsh4" >	
												<?php echo __('Downloads', 'bwge'); ?>
											</span>
											</a>											
										</li>
									</ul>
									<div class="bwge_tabs_container" >
									<!-- manual -->
									<div class="manual bwge_pricelist" id="manual" <?php if( count($pricelist_sections) == 2  || (count($pricelist_sections) == 1 && end($pricelist_sections) == "manual")) echo 'style="display: block;"'; else echo 'style="display: none;"'; ?>  >																	
										<div>

											<div class="product_manual_price_div">
												<p><?php echo $pricelist->manual_title ? __('Name', 'bwge').': '.$pricelist->manual_title : "";?></p>
                                                <?php if($pricelist->price){
                                                 ?>
												<p>												
													<span><?php echo __('Price', 'bwge').': '.$options->currency_sign;?></span>
													<span class="_product_manual_price"><?php echo number_format((float)$pricelist->price,2)?></span>
												</p>
                                            <?php
                                              }
                                              ?>
											</div>
                                          <?php if($pricelist->manual_description){
                                          ?>
											<div class="product_manual_desc_div">
												<div>
													<span><?php echo __('Description', 'bwge');?>:</span>
													<span class="product_manual_desc"><?php echo htmlspecialchars_decode($pricelist->manual_description);?></span>
												</div>
											</div>
											<?php
                                              }
                                              ?>
											<div class="image_count_div">
												<p>
													<?php echo __('Count', 'bwge').': ';?>
													<input type="number" min="1" class="image_count" value="1" onchange="changeMenualTotal(this);">
												</p>
											</div>
											<?php if(empty($parameters) == false){?>
											<div class="image_parameters">
												<p><?php //echo __('Parameters', 'bwge'); ?></p>
												<?php
													$i = 0;
													foreach($parameters as $parameter_id => $parameter){	
														echo '<div class="parameter_row">';
														switch($parameter["type"]){
															case "1" :
																echo '<div class="image_selected_parameter" data-parameter-id="'.$parameter_id.'" data-parameter-type = "'.$parameter["type"].'">';
																echo $parameter["title"].": <span class='parameter_single'>". $parameter["values"][0]["parameter_value"]."</span>";
																echo '</div>';
																break;
															case "2" :
																echo '<div class="image_selected_parameter" data-parameter-id="'.$parameter_id.'" data-parameter-type = "'.$parameter["type"].'">';															
																echo '<label for="parameter_input">'.$parameter["title"].'</label>';
																echo '<input type="text" name="parameter_input'.$parameter_id.'" id="parameter_input"  value="'. $parameter["values"][0]["parameter_value"] .'">';																	
																echo '</div>';
																break;															
															case "3" :
																echo '<div class="image_selected_parameter" data-parameter-id="'.$parameter_id.'" data-parameter-type = "'.$parameter["type"].'">';															
																echo '<label for="parameter_textarea">'.$parameter["title"].'</label>';															
																echo '<textarea  name="parameter_textarea'.$parameter_id.'" id="parameter_textarea"  >'. $parameter["values"][0]["parameter_value"] .'</textarea>';																	
																echo '</div>';
																break;																
															case "4" :
																echo '<div class="image_selected_parameter" data-parameter-id="'.$parameter_id.'" data-parameter-type = "'.$parameter["type"].'">';											
																echo '<label for="parameter_select">'.$parameter["title"].'</label>';		
																echo '<select name="parameter_select'.$parameter_id.'" id="parameter_select"  onchange="onSelectableParametersChange(this)">';
																echo '<option value="+*0*">-Select-</option>';
																foreach($parameter["values"] as $values){
                                                                    $price_addon = $values["parameter_value_price"] == "0" ? "" : ' ('.$values["parameter_value_price_sign"].$options->currency_sign.number_format((float)$values["parameter_value_price"],2).')';
																	echo '<option value="'.$values["parameter_value_price_sign"].'*'.$values["parameter_value_price"].'*'.$values["parameter_value"].'">'.$values["parameter_value"].$price_addon.'</option>';	
																}
																echo '</select>';
																echo '<input type="hidden" class="already_selected_values">';
																echo '</div>';
																break;	
															case "5" :
																echo '<div class="image_selected_parameter" data-parameter-id="'.$parameter_id.'" data-parameter-type = "'.$parameter["type"].'">';															
																echo '<label>'.$parameter["title"].'</label>';
																foreach($parameter["values"] as $values){	
                                                                    $price_addon = $values["parameter_value_price"] == "0"	? "" : 	' ('.$values["parameter_value_price_sign"].$options->currency_sign.number_format((float)$values["parameter_value_price"],2).')';													
																	echo '<div>';
																	echo '<input type="radio" name="parameter_radio'.$parameter_id.'"  id="parameter_radio'.$i.'" value="'.$values["parameter_value_price_sign"].'*'.$values["parameter_value_price"].'*'.$values["parameter_value"].'"  onchange="onSelectableParametersChange(this)">';	
																	echo '<label for="parameter_radio'.$i.'">'.$values["parameter_value"].$price_addon.'</label>';
																	echo '</div>';
																	$i++;
																}
																echo '<input type="hidden" class="already_selected_values">';
																echo '</div>';
																break;	
															case "6" :	
																echo '<div class="image_selected_parameter" data-parameter-id="'.$parameter_id.'" data-parameter-type = "'.$parameter["type"].'">';															
																echo '<label>'.$parameter["title"].'</label>';
																foreach($parameter["values"] as $values){
                                                                    $price_addon = $values["parameter_value_price"] == "0" ? "" : ' ('.$values["parameter_value_price_sign"].$options->currency_sign.number_format((float)$values["parameter_value_price"],2).')';
																	echo '<div>';
																	echo '<input type="checkbox" name="parameter_checkbox'.$parameter_id.'" id="parameter_checkbox'.$i.'" value="'.$values["parameter_value_price_sign"].'*'.$values["parameter_value_price"].'*'.$values["parameter_value"].'"  onchange="onSelectableParametersChange(this)">';	
																	echo '<label for="parameter_checkbox'.$i.'">'.$values["parameter_value"].$price_addon.'</label>';
																	echo '</div>';
																	$i++;
																}
																echo '<input type="hidden" class="already_selected_values">';
																echo '</div>';
																break;	
															default:
																break;
														}
														echo '</div>';					
													}
												?>
												
											</div>
											<?php } ?>
											<p>
												<span><b><?php echo __('Total', 'bwge').': '.$options->currency_sign;?></b></span>
												<b><span class="product_manual_price" data-price="<?php echo $pricelist->price; ?>" data-actual-price="<?php echo $pricelist->price; ?>"><?php echo number_format((float)$pricelist->price,2)?></span></b>
											</p>
										</div>							
																	
									</div>
									<!-- downloads -->
				
									<div class="downloads bwge_pricelist" id="downloads" <?php if( (count($pricelist_sections) == 1 && end($pricelist_sections) == "downloads")) echo 'style="display: block;"'; else echo 'style="display: none;"'; ?> >
								
										<table>
											<thead>
												<tr>
													<th><?php echo __('Choose', 'bwge'); ?></th>
													<th><?php echo __('Name', 'bwge'); ?></th>
													<th><?php echo __('Dimensions', 'bwge'); ?></th>
													<th><?php echo __('Price', 'bwge'); ?></th>												
												</tr>
											</thead>
											<tbody>
												<?php	
													if(empty($download_items) === false){
														foreach($download_items as $download_item){
														?>
															<tr data-price="<?php echo $download_item->item_price; ?>" data-id="<?php echo $download_item->id; ?>">
																<?php if($options->show_digital_items_count == 0){
																?>
																	<td><input type="checkbox"  name="selected_download_item" value="<?php echo $download_item->id; ?>" onchange="changeDownloadsTotal(this);"></td>
																<?php
																}
																else{
																?>
																	<td><input type="number" min="0" class="digital_image_count" value="0" onchange="changeDownloadsTotal(this);"></td>																
																<?php
																}
																?>
																<td><?php echo $download_item->item_name; ?></td>
																<td><?php echo $download_item->item_longest_dimension.'px'; ?></td>
																<td class="item_price"><?php echo $options->currency_sign. number_format((float)$download_item->item_price, 2); ?></td>
															</tr>																
														<?php
														}
													}													
												?>
											</tbody>
										</table>	
										<p>
											<span><b><?php echo __('Total', 'bwge').': '.$options->currency_sign;?></b></span>
											<b><span class="product_downloads_price">0</span></b>
										</p>										
									</div>									
									</div>
								
								</div>
		
								<div style="margin-top:10px;">	
									<input type="button" class="bwge_submit" value="<?php echo __('Add to cart', 'bwge'); ?>" onclick="onBtnClickAddToCart();">
									<input type="button" class="bwge_submit" value="<?php echo __('View cart', 'bwge'); ?>" onclick="onBtnViewCart()">
									&nbsp;<span class="add_to_cart_msg"></span>
								</div>
								
								<input id="ajax_task" name="ajax_task" type="hidden" value="" />
								<input id="type" name="type" type="hidden" value="<?php echo isset($pricelist_sections[0]) ? $pricelist_sections[0] : ""  ?>" />
								<input id="image_id" name="image_id" type="hidden" value="<?php echo $image_id; ?>" />
								<div class="bwge_options">
									<input type="hidden" name="option_checkout_page" value="<?php  echo get_permalink($options->checkout_page);?>">
									<input type="hidden" name="option_show_digital_items_count" value="<?php echo $options->show_digital_items_count;?>">								
								</div>
							
							</form>	
						</div>
					</div>
				</div>
			</div>

			<script>
				jQuery(document).ready(function () {
					jQuery(".bwge_tabs li a").click(function(){
						jQuery(".bwge_tabs_container > div").hide();
						jQuery(".bwge_tabs li").removeClass("bwge_active");
						jQuery(jQuery(this).attr("href")).show();
						jQuery(this).closest("li").addClass("bwge_active");
						jQuery("[name=type]").val(jQuery(this).attr("href").substr(1));	
						return false;
					});

				});		  
	  
				function changeDownloadsTotal(obj){
					var totalPrice = 0;
					var showdigitalItemsCount = jQuery("[name=option_show_digital_items_count]").val();
					if( showdigitalItemsCount == 0 ){
						jQuery("[name=selected_download_item]:checked").each(function(){
							totalPrice += Number(jQuery(this).closest("tr").attr("data-price"));
						
						});
					}
					else{
						jQuery(".digital_image_count").each(function(){
							if(Number(jQuery(this).val()) != 0){
								totalPrice += Number(jQuery(this).closest("tr").attr("data-price")) * Number(jQuery(this).val());
							}						
						});					
					}
					totalPrice = totalPrice.toFixed(2).toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,");
					jQuery(".product_downloads_price").html(totalPrice);
				
				}
				
				function changeMenualTotal(obj){
					if(Number(jQuery(obj).val()) <= 0){
						jQuery(obj).val("1");
					}
					var count =  Number(jQuery(obj).val());
					var totalPrice = Number(jQuery(".product_manual_price").attr("data-actual-price"));
					totalPrice = count*totalPrice;
				
					totalPrice = totalPrice.toFixed(2).toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,");
					jQuery(".product_manual_price").html(totalPrice);
				}

				function onSelectableParametersChange(obj){
					var parametersPrise = 0;
					
					var productPrice = data[jQuery('#bwge_current_image_key').val()]["pricelist_manual_price"] ? data[jQuery('#bwge_current_image_key').val()]["pricelist_manual_price"] : "0";
					productPrice = parseFloat(productPrice.replace(",",""));
					
					var type = jQuery(obj).closest('.image_selected_parameter').attr("data-parameter-type");
					var priceInfo = jQuery(obj).val();
					priceInfo = priceInfo.split("*");
					var priceValue = priceInfo[1];
					var sign = priceInfo[0];
					
					var alreadySelectedValues = Number(jQuery(obj).closest('.image_selected_parameter').find(".already_selected_values").val());
				
					if(type == "4" || type == "5")	{ 
						var newPriceVlaueSelectRadio =  parseFloat(eval(sign + '1*' + priceValue));	
																							
						jQuery(obj).closest('.image_selected_parameter').find(".already_selected_values").val(newPriceVlaueSelectRadio);
					}

					else if (type == "6"){ 		
						if(jQuery(obj).is(":checked") == false){							
							var  newPriceVlaueCheckbox = parseFloat(eval(alreadySelectedValues + "- "  + sign + priceValue));							
						}
						else{							
							 var newPriceVlaueCheckbox = parseFloat(eval(alreadySelectedValues + sign + priceValue));
						}
						jQuery(obj).closest('.image_selected_parameter').find(".already_selected_values").val(newPriceVlaueCheckbox);
					}

					
					jQuery(".already_selected_values").each(function(){
						parametersPrise += Number(jQuery(this).val());					
					});
					
					productPrice =   productPrice + parametersPrise;
					jQuery(".product_manual_price").attr("data-actual-price",productPrice);
					var count = Number(jQuery(".image_count").val()) <= 0 ? 1 : Number(jQuery(".image_count").val());
					productPrice = count * productPrice;
					productPrice = productPrice.toFixed(2).toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,");;
					jQuery(".product_manual_price").html(productPrice);
					
					  
				}

				function onBtnClickAddToCart(){
				
					var type = jQuery("[name=type]").val();
					if(type != ""){
						var data = {};
						if(type == "manual"){
							var count = jQuery(".image_count").val();
							var parameters = {};
							
							jQuery(".manual").find(".image_selected_parameter").each(function () {
								var parameterId = jQuery(this).attr("data-parameter-id");
								var parameterTypeId = jQuery(this).attr("data-parameter-type");
								var parameterValue = "";
								switch (parameterTypeId) {
                                  
									// input
									case '2':
										parameterValue = jQuery(this).find("input").val();
										break;
									case '3':
										parameterValue = jQuery(this).find("textarea").val();
										break;
									// Select
									case '4':						
										parameterValue = jQuery(this).find('select :selected').val();
										break;
									// Radio
									case '5':
										parameterValue = jQuery(this).find('[type=radio]:checked').val();
										break;
									// Checkbox
									case '6':				
										var checkbox_parameter_values = [];;
										jQuery(this).find("[type=checkbox]:checked").each(function () {
											checkbox_parameter_values.push(jQuery(this).val());
										});
										parameterValue = checkbox_parameter_values;
										break;
								}

								parameters[parameterId] = parameterValue;
							});							
							data.count = count;					
							data.parameters = parameters;					
							data.price = jQuery(".product_manual_price").attr("data-price").replace(",","");					
						}
						else{
							var downloadItems = [];
							var showdigitalItemsCount = jQuery("[name=option_show_digital_items_count]").val();
							if( showdigitalItemsCount == 0 ){
								if(jQuery("[name=selected_download_item]:checked").length == 0){
									jQuery(".add_to_cart_msg").html("You must select at least one item.");
									return;
								}
								jQuery("[name=selected_download_item]:checked").each(function () {
									var downloadItem = {};
									downloadItem.id = jQuery(this).val();
									downloadItem.count = 1;
									downloadItem.price = jQuery(this).closest("tr").attr("data-price");
									downloadItems.push(downloadItem);		
								});	
							}
							else{							
								jQuery(".digital_image_count").each(function () {
									var downloadItem = {};
									if(jQuery(this).val() > 0){
										downloadItem.id = jQuery(this).closest("tr").attr("data-id");
										downloadItem.price = jQuery(this).closest("tr").attr("data-price");
										downloadItem.count = jQuery(this).val();
										downloadItems.push(downloadItem);	
									}
																
								});	
							}
							data.downloadItems = downloadItems;	
							if(downloadItems.length == 0)	{
								jQuery(".add_to_cart_msg").html("<?php echo __("Please select at least one item", 'bwge');?>");
								return ;
							}
								
						}
						
						var requestData = {
							'action': 'bwge_add_cart',
							'task': 'add_cart',
							'controller': 'checkout',
							"image_id": jQuery('#bwge_popup_image').attr('image_id'),
							"type": type,						
							"data": JSON.stringify(data)
						};
					
						var ajaxurl = '<?php echo admin_url('admin-ajax.php'); ?>';						
						jQuery.post(ajaxurl, requestData, function(response) {		
							//console.log(response);
							responseData = JSON.parse(response);	
							jQuery(".add_to_cart_msg").html(responseData["msg"]);
							jQuery(".products_in_cart").html(responseData["products_in_cart"]);
							if(responseData["redirect"] == 1){
								window.location.href = "<?php echo get_permalink($options->checkout_page);?>";			
							}
							
						});
					}
					else{
						jQuery(".add_to_cart_msg").html("<?php echo __("Please select Prints and products or Downloads", 'bwge');?>");
					}

				}	

				function onBtnViewCart(){
					var checkoutPage = jQuery("[name=option_checkout_page]").val();
					jQuery("#bwge_ecommerce_form").attr("action",checkoutPage)
					jQuery("#bwge_ecommerce_form").submit();
				}

			</script>	
			
    <a class="bwge_spider_popup_close" onclick="bwge_spider_destroypopup(1000); return false;" ontouchend="bwge_spider_destroypopup(1000); return false;"><span><i class="bwge_close_btn fa fa-times"></i></span></a>

    <script language="javascript" type="text/javascript" src="<?php echo WD_BWGE_URL . '/js/bwge_embed.js?ver=' . wd_bwge_version(); ?>"></script>
    <script>

      var bwge_trans_in_progress = false;
      var bwge_transition_duration = <?php echo (($slideshow_interval < 4) && ($slideshow_interval != 0)) ? ($slideshow_interval * 1000) / 4 : 800; ?>;
      var bwge_playInterval;
      if ((jQuery("#bwge_spider_popup_wrap").width() >= jQuery(window).width()) || (jQuery("#bwge_spider_popup_wrap").height() >= jQuery(window).height())) {
        jQuery(".bwge_spider_popup_close").attr("class", "bwge_ctrl_btn bwge_spider_popup_close_fullscreen");
      }
      /* Stop autoplay.*/
      window.clearInterval(bwge_playInterval);
      /* Set watermark container size.*/
      function bwge_change_watermark_container() {
        jQuery(".bwge_slider").children().each(function() {
          if (jQuery(this).css("zIndex") == 2) {
            /* This may be neither img nor iframe.*/
            var bwge_current_image_span = jQuery(this).find("img");
            if (!bwge_current_image_span.length) {
              bwge_current_image_span = jQuery(this).find("iframe");
            }
            if (!bwge_current_image_span.length) {
              bwge_current_image_span = jQuery(this).find("video");
            }
            /*set timeout for video to get size according to style, and then put watermark*/
            setTimeout(function () {
              var width = bwge_current_image_span.width();
              var height = bwge_current_image_span.height();
          

              jQuery(".bwge_watermark_spun").width(width);
              jQuery(".bwge_watermark_spun").height(height);
              jQuery(".bwge_watermark").css({display: ''});
              /* Set watermark image size.*/
              var comment_container_width = 0;
              if (jQuery(".bwge_comment_container").hasClass("bwge_open") || jQuery(".bwge_ecommerce_container").hasClass("bwge_open") ) {
                comment_container_width = <?php echo $theme_row->lightbox_comment_width; ?>;
               }
              if (width <= (jQuery(window).width() - comment_container_width)) {
                jQuery(".bwge_watermark_image").css({
                  width: ((jQuery(".bwge_spider_popup_wrap").width() - comment_container_width) * <?php echo $watermark_width / $image_width; ?>)
                });
                jQuery(".bwge_watermark_text, .bwge_watermark_text:hover").css({
                  fontSize: ((jQuery(".bwge_spider_popup_wrap").width() - comment_container_width) * <?php echo $watermark_font_size / $image_width; ?>)
                });
              }
            }, 800);
          }
        });
      }
      var bwge_current_key = '<?php echo $current_key; ?>';
      var bwge_current_filmstrip_pos = <?php echo $current_pos; ?>;
      /* Set filmstrip initial position.*/
      function bwge_set_filmstrip_pos(filmStripWidth) {
        var selectedImagePos = -bwge_current_filmstrip_pos - (jQuery(".bwge_filmstrip_thumbnail").<?php echo $outerWidth_or_outerHeight; ?>(true)) / 2;
        var imagesContainerLeft = Math.min(0, Math.max(filmStripWidth - jQuery(".bwge_filmstrip_thumbnails").<?php echo $width_or_height; ?>(), selectedImagePos + filmStripWidth / 2));
        jQuery(".bwge_filmstrip_thumbnails").animate({
            <?php echo $left_or_top; ?>: imagesContainerLeft
          }, {
            duration: 500,
            complete: function () { bwge_filmstrip_arrows(); }
          });
      }
      function bwge_move_filmstrip() {
        var image_left = jQuery(".bwge_thumb_active").position().<?php echo $left_or_top; ?>;
        var image_right = jQuery(".bwge_thumb_active").position().<?php echo $left_or_top; ?> + jQuery(".bwge_thumb_active").<?php echo $outerWidth_or_outerHeight; ?>(true);
        var bwge_filmstrip_width = jQuery(".bwge_filmstrip").<?php echo $outerWidth_or_outerHeight; ?>(true);
        var bwge_filmstrip_thumbnails_width = jQuery(".bwge_filmstrip_thumbnails").<?php echo $outerWidth_or_outerHeight; ?>(true);
        var long_filmstrip_cont_left = jQuery(".bwge_filmstrip_thumbnails").position().<?php echo $left_or_top; ?>;
        var long_filmstrip_cont_right = Math.abs(jQuery(".bwge_filmstrip_thumbnails").position().<?php echo $left_or_top; ?>) + bwge_filmstrip_width;
        if (bwge_filmstrip_width > bwge_filmstrip_thumbnails_width) {
          return;
        }
        if (image_left < Math.abs(long_filmstrip_cont_left)) {
          jQuery(".bwge_filmstrip_thumbnails").animate({
            <?php echo $left_or_top; ?>: -image_left
          }, {
            duration: 500,
            complete: function () { bwge_filmstrip_arrows(); }
          });
        }
        else if (image_right > long_filmstrip_cont_right) {
          jQuery(".bwge_filmstrip_thumbnails").animate({
            <?php echo $left_or_top; ?>: -(image_right - bwge_filmstrip_width)
          }, {
            duration: 500,
            complete: function () { bwge_filmstrip_arrows(); }
          });
        }
      }
      /* Show/hide filmstrip arrows.*/
      function bwge_filmstrip_arrows() {
        if (jQuery(".bwge_filmstrip_thumbnails").<?php echo $width_or_height; ?>() < jQuery(".bwge_filmstrip").<?php echo $width_or_height; ?>()) {
          jQuery(".bwge_filmstrip_left").hide();
          jQuery(".bwge_filmstrip_right").hide();
        }
        else {
          jQuery(".bwge_filmstrip_left").show();
          jQuery(".bwge_filmstrip_right").show();
        }
      }
      function bwge_testBrowser_cssTransitions() {
        return bwge_testDom('Transition');
      }
      function bwge_testBrowser_cssTransforms3d() {
        return bwge_testDom('Perspective');
      }
      function bwge_testDom(prop) {
        /* Browser vendor CSS prefixes.*/
        var browserVendors = ['', '-webkit-', '-moz-', '-ms-', '-o-', '-khtml-'];
        /* Browser vendor DOM prefixes.*/
        var domPrefixes = ['', 'Webkit', 'Moz', 'ms', 'O', 'Khtml'];
        var i = domPrefixes.length;
        while (i--) {
          if (typeof document.body.style[domPrefixes[i] + prop] !== 'undefined') {
            return true;
          }
        }
        return false;
      }
      function bwge_cube(tz, ntx, nty, nrx, nry, wrx, wry, current_image_class, next_image_class, direction) {
        /* If browser does not support 3d transforms/CSS transitions.*/
        if (!bwge_testBrowser_cssTransitions()) {
          return bwge_fallback(current_image_class, next_image_class, direction);
        }
        if (!bwge_testBrowser_cssTransforms3d()) {
          return bwge_fallback3d(current_image_class, next_image_class, direction);
        }
        bwge_trans_in_progress = true;
        /* Set active thumbnail.*/
        jQuery(".bwge_filmstrip_thumbnail").removeClass("bwge_thumb_active").addClass("bwge_thumb_deactive");
        jQuery("#bwge_filmstrip_thumbnail_" + bwge_current_key).removeClass("bwge_thumb_deactive").addClass("bwge_thumb_active");
        jQuery(".bwge_slide_bg").css('perspective', 1000);
        jQuery(current_image_class).css({
          transform : 'translateZ(' + tz + 'px)',
          backfaceVisibility : 'hidden'
        });
        jQuery(next_image_class).css({
          opacity : 1,
          filter: 'Alpha(opacity=100)',
          backfaceVisibility : 'hidden',
          transform : 'translateY(' + nty + 'px) translateX(' + ntx + 'px) rotateY('+ nry +'deg) rotateX('+ nrx +'deg)'
        });
        jQuery(".bwge_slider").css({
          transform: 'translateZ(-' + tz + 'px)',
          transformStyle: 'preserve-3d'
        });
        /* Execution steps.*/
        setTimeout(function () {
          jQuery(".bwge_slider").css({
            transition: 'all ' + bwge_transition_duration + 'ms ease-in-out',
            transform: 'translateZ(-' + tz + 'px) rotateX('+ wrx +'deg) rotateY('+ wry +'deg)'
          });
        }, 20);
        /* After transition.*/
        jQuery(".bwge_slider").one('webkitTransitionEnd transitionend otransitionend oTransitionEnd mstransitionend', jQuery.proxy(bwge_after_trans));
        function bwge_after_trans() {
          jQuery(current_image_class).removeAttr('style');
          jQuery(next_image_class).removeAttr('style');
          jQuery(".bwge_slider").removeAttr('style');
          jQuery(current_image_class).css({'opacity' : 0, filter: 'Alpha(opacity=0)', 'z-index': 1});
          jQuery(next_image_class).css({'opacity' : 1, filter: 'Alpha(opacity=100)', 'z-index' : 2});
          
          bwge_trans_in_progress = false;
          jQuery(current_image_class).html('');
          if (typeof event_stack !== 'undefined') {
            if (event_stack.length > 0) {
              key = event_stack[0].split("-");
              event_stack.shift();
              bwge_change_image(key[0], key[1], data, true);
            }
          }
          bwge_change_watermark_container();
        }
      }
      function bwge_cubeH(current_image_class, next_image_class, direction) {
        /* Set to half of image width.*/
        var dimension = jQuery(current_image_class).width() / 2;
        if (direction == 'right') {
          bwge_cube(dimension, dimension, 0, 0, 90, 0, -90, current_image_class, next_image_class, direction);
        }
        else if (direction == 'left') {
          bwge_cube(dimension, -dimension, 0, 0, -90, 0, 90, current_image_class, next_image_class, direction);
        }
      }
      function bwge_cubeV(current_image_class, next_image_class, direction) {
        /* Set to half of image height.*/
        var dimension = jQuery(current_image_class).height() / 2;
        /* If next slide.*/
        if (direction == 'right') {
          bwge_cube(dimension, 0, -dimension, 90, 0, -90, 0, current_image_class, next_image_class, direction);
        }
        else if (direction == 'left') {
          bwge_cube(dimension, 0, dimension, -90, 0, 90, 0, current_image_class, next_image_class, direction);
        }
      }
      /* For browsers that does not support transitions.*/
      function bwge_fallback(current_image_class, next_image_class, direction) {
        bwge_fade(current_image_class, next_image_class, direction);
      }
      /* For browsers that support transitions, but not 3d transforms (only used if primary transition makes use of 3d-transforms).*/
      function bwge_fallback3d(current_image_class, next_image_class, direction) {
        bwge_sliceV(current_image_class, next_image_class, direction);
      }
      function bwge_none(current_image_class, next_image_class, direction) {
        jQuery(current_image_class).css({'opacity' : 0, 'z-index': 1});
        jQuery(next_image_class).css({'opacity' : 1, 'z-index' : 2});
        /* Set active thumbnail.*/
        jQuery(".bwge_filmstrip_thumbnail").removeClass("bwge_thumb_active").addClass("bwge_thumb_deactive");
        jQuery("#bwge_filmstrip_thumbnail_" + bwge_current_key).removeClass("bwge_thumb_deactive").addClass("bwge_thumb_active");
        bwge_trans_in_progress = false; 
        jQuery(current_image_class).html('');
        bwge_change_watermark_container();
      }
      function bwge_fade(current_image_class, next_image_class, direction) {
        /* Set active thumbnail.*/
        jQuery(".bwge_filmstrip_thumbnail").removeClass("bwge_thumb_active").addClass("bwge_thumb_deactive");
        jQuery("#bwge_filmstrip_thumbnail_" + bwge_current_key).removeClass("bwge_thumb_deactive").addClass("bwge_thumb_active");
        if (bwge_testBrowser_cssTransitions()) {
          jQuery(next_image_class).css('transition', 'opacity ' + bwge_transition_duration + 'ms linear');
          jQuery(current_image_class).css({'opacity' : 0, 'z-index': 1});
          jQuery(next_image_class).css({'opacity' : 1, 'z-index' : 2});
          bwge_change_watermark_container();
        }
        else {
          jQuery(current_image_class).animate({'opacity' : 0, 'z-index' : 1}, bwge_transition_duration);
          jQuery(next_image_class).animate({
              'opacity' : 1,
              'z-index': 2
            }, {
              duration: bwge_transition_duration,
              complete: function () { 

                bwge_trans_in_progress = false;  
                jQuery(current_image_class).html('');
                bwge_change_watermark_container(); }
            });
          /* For IE.*/
          jQuery(current_image_class).fadeTo(bwge_transition_duration, 0);
          jQuery(next_image_class).fadeTo(bwge_transition_duration, 1);
        }
      }
      function bwge_grid(cols, rows, ro, tx, ty, sc, op, current_image_class, next_image_class, direction) {
        /* If browser does not support CSS transitions.*/
        if (!bwge_testBrowser_cssTransitions()) {
          return bwge_fallback(current_image_class, next_image_class, direction);
        }
        bwge_trans_in_progress = true;
        /* Set active thumbnail.*/
        jQuery(".bwge_filmstrip_thumbnail").removeClass("bwge_thumb_active").addClass("bwge_thumb_deactive");
        jQuery("#bwge_filmstrip_thumbnail_" + bwge_current_key).removeClass("bwge_thumb_deactive").addClass("bwge_thumb_active");
        /* The time (in ms) added to/subtracted from the delay total for each new gridlet.*/
        var count = (bwge_transition_duration) / (cols + rows);
        /* Gridlet creator (divisions of the image grid, positioned with background-images to replicate the look of an entire slide image when assembled)*/
        function bwge_gridlet(width, height, top, img_top, left, img_left, src, imgWidth, imgHeight, c, r) {
          var delay = (c + r) * count;
          /* Return a gridlet elem with styles for specific transition.*/
          return jQuery('<span class="bwge_gridlet" />').css({
            display : "block",
            width : width,
            height : height,
            top : top,
            left : left,
            backgroundImage : 'url("' + src + '")',
            backgroundColor: jQuery(".bwge_spider_popup_wrap").css("background-color"),
            /*backgroundColor: 'rgba(0, 0, 0, 0)',*/
            backgroundRepeat: 'no-repeat',
            backgroundPosition : img_left + 'px ' + img_top + 'px',
            backgroundSize : imgWidth + 'px ' + imgHeight + 'px',
            transition : 'all ' + bwge_transition_duration + 'ms ease-in-out ' + delay + 'ms',
            transform : 'none'
          });
        }
        /* Get the current slide's image.*/
        var cur_img = jQuery(current_image_class).find('img');
        /* Create a grid to hold the gridlets.*/
        var grid = jQuery('<span style="display: block;" />').addClass('bwge_grid');
        /* Prepend the grid to the next slide (i.e. so it's above the slide image).*/
        jQuery(current_image_class).prepend(grid);
        /* Vars to calculate positioning/size of gridlets.*/
        var cont = jQuery(".bwge_slide_bg");
        var imgWidth = cur_img.width();
        var imgHeight = cur_img.height();
        var contWidth = cont.width(),
            contHeight = cont.height(),
            colWidth = Math.floor(contWidth / cols),
            rowHeight = Math.floor(contHeight / rows),
            colRemainder = contWidth - (cols * colWidth),
            colAdd = Math.ceil(colRemainder / cols),
            rowRemainder = contHeight - (rows * rowHeight),
            rowAdd = Math.ceil(rowRemainder / rows),
            leftDist = 0,
            img_leftDist = Math.ceil((jQuery(".bwge_slide_bg").width() - cur_img.width()) / 2);
	var imgSrc = typeof cur_img.attr('src')=='undefined' ? '' :cur_img.attr('src');
        /* tx/ty args can be passed as 'auto'/'min-auto' (meaning use slide width/height or negative slide width/height).*/
        tx = tx === 'auto' ? contWidth : tx;
        tx = tx === 'min-auto' ? - contWidth : tx;
        ty = ty === 'auto' ? contHeight : ty;
        ty = ty === 'min-auto' ? - contHeight : ty;
        /* Loop through cols.*/
        for (var i = 0; i < cols; i++) {
          var topDist = 0,
              img_topDst = Math.floor((jQuery(".bwge_slide_bg").height() - cur_img.height()) / 2),
              newColWidth = colWidth;
          /* If imgWidth (px) does not divide cleanly into the specified number of cols, adjust individual col widths to create correct total.*/
          if (colRemainder > 0) {
            var add = colRemainder >= colAdd ? colAdd : colRemainder;
            newColWidth += add;
            colRemainder -= add;
          }
          /* Nested loop to create row gridlets for each col.*/
          for (var j = 0; j < rows; j++)  {
            var newRowHeight = rowHeight,
                newRowRemainder = rowRemainder;
            /* If contHeight (px) does not divide cleanly into the specified number of rows, adjust individual row heights to create correct total.*/
            if (newRowRemainder > 0) {
              add = newRowRemainder >= rowAdd ? rowAdd : rowRemainder;
              newRowHeight += add;
              newRowRemainder -= add;
            }
            /* Create & append gridlet to grid.*/
            grid.append(bwge_gridlet(newColWidth, newRowHeight, topDist, img_topDst, leftDist, img_leftDist, imgSrc, imgWidth, imgHeight, i, j));
            topDist += newRowHeight;
            img_topDst -= newRowHeight;
          }
          img_leftDist -= newColWidth;
          leftDist += newColWidth;
        }
        /* Set event listener on last gridlet to finish transitioning.*/
        var last_gridlet = grid.children().last();
        /* Show grid & hide the image it replaces.*/
        grid.show();
        cur_img.css('opacity', 0);
        /* Add identifying classes to corner gridlets (useful if applying border radius).*/
        grid.children().first().addClass('rs-top-left');
        grid.children().last().addClass('rs-bottom-right');
        grid.children().eq(rows - 1).addClass('rs-bottom-left');
        grid.children().eq(- rows).addClass('rs-top-right');
        /* Execution steps.*/
        setTimeout(function () {
          grid.children().css({
            opacity: op,
            transform: 'rotate('+ ro +'deg) translateX('+ tx +'px) translateY('+ ty +'px) scale('+ sc +')'
          });
        }, 1);
        jQuery(next_image_class).css('opacity', 1);
        /* After transition.*/
        jQuery(last_gridlet).one('webkitTransitionEnd transitionend otransitionend oTransitionEnd mstransitionend', jQuery.proxy(bwge_after_trans));
        function bwge_after_trans() {
          jQuery(current_image_class).css({'opacity' : 0, 'z-index': 1});
          jQuery(next_image_class).css({'opacity' : 1, 'z-index' : 2});
          cur_img.css('opacity', 1);
          grid.remove();
          bwge_trans_in_progress = false;
          jQuery(current_image_class).html('');
          if (typeof event_stack !== 'undefined') {
            if (event_stack.length > 0) {
              key = event_stack[0].split("-");
              event_stack.shift();
              bwge_change_image(key[0], key[1], data, true);
            }
          }
          bwge_change_watermark_container();
        }
      }
      function bwge_sliceH(current_image_class, next_image_class, direction) {
        if (direction == 'right') {
          var translateX = 'min-auto';
        }
        else if (direction == 'left') {
          var translateX = 'auto';
        }
        bwge_grid(1, 8, 0, translateX, 0, 1, 0, current_image_class, next_image_class, direction);
      }
      function bwge_sliceV(current_image_class, next_image_class, direction) {
        if (direction == 'right') {
          var translateY = 'min-auto';
        }
        else if (direction == 'left') {
          var translateY = 'auto';
        }
        bwge_grid(10, 1, 0, 0, translateY, 1, 0, current_image_class, next_image_class, direction);
      }
      function bwge_slideV(current_image_class, next_image_class, direction) {
        if (direction == 'right') {
          var translateY = 'auto';
        }
        else if (direction == 'left') {
          var translateY = 'min-auto';
        }
        bwge_grid(1, 1, 0, 0, translateY, 1, 1, current_image_class, next_image_class, direction);
      }
      function bwge_slideH(current_image_class, next_image_class, direction) {
        if (direction == 'right') {
          var translateX = 'min-auto';
        }
        else if (direction == 'left') {
          var translateX = 'auto';
        }
        bwge_grid(1, 1, 0, translateX, 0, 1, 1, current_image_class, next_image_class, direction);
      }
      function bwge_scaleOut(current_image_class, next_image_class, direction) {
        bwge_grid(1, 1, 0, 0, 0, 1.5, 0, current_image_class, next_image_class, direction);
      }
      function bwge_scaleIn(current_image_class, next_image_class, direction) {
        bwge_grid(1, 1, 0, 0, 0, 0.5, 0, current_image_class, next_image_class, direction);
      }
      function bwge_blockScale(current_image_class, next_image_class, direction) {
        bwge_grid(8, 6, 0, 0, 0, .6, 0, current_image_class, next_image_class, direction);
      }
      function bwge_kaleidoscope(current_image_class, next_image_class, direction) {
        bwge_grid(10, 8, 0, 0, 0, 1, 0, current_image_class, next_image_class, direction);
      }
      function bwge_fan(current_image_class, next_image_class, direction) {
        if (direction == 'right') {
          var rotate = 45;
          var translateX = 100;
        }
        else if (direction == 'left') {
          var rotate = -45;
          var translateX = -100;
        }
        bwge_grid(1, 10, rotate, translateX, 0, 1, 0, current_image_class, next_image_class, direction);
      }
      function bwge_blindV(current_image_class, next_image_class, direction) {
        bwge_grid(1, 8, 0, 0, 0, .7, 0, current_image_class, next_image_class);
      }
      function bwge_blindH(current_image_class, next_image_class, direction) {
        bwge_grid(10, 1, 0, 0, 0, .7, 0, current_image_class, next_image_class);
      }
      function bwge_random(current_image_class, next_image_class, direction) {
        var anims = ['sliceH', 'sliceV', 'slideH', 'slideV', 'scaleOut', 'scaleIn', 'blockScale', 'kaleidoscope', 'fan', 'blindH', 'blindV'];
        /* Pick a random transition from the anims array.*/
        this["bwge_" + anims[Math.floor(Math.random() * anims.length)]](current_image_class, next_image_class, direction);
      }
      function bwge_change_image(current_key, key, data, from_effect) {
        jQuery("#bwge_spider_popup_left").show();
        jQuery("#bwge_spider_popup_right").show();
        if (<?php echo $option_row->enable_loop; ?> == 0) {
          if (key == (parseInt(data.length) - 1)) {
            jQuery("#bwge_spider_popup_right").hide();
          }
          if (key == 0) {
            jQuery("#bwge_spider_popup_left").hide();
          }
        }
		
        

		if(<?php echo $enable_image_ecommerce;?> == 1){	
			if( data[key]["pricelist"] == 0){
				jQuery(".bwge_ecommerce").hide();
			}
			else{
				jQuery(".bwge_ecommerce").show();
				
				jQuery(".bwge_tabs li").hide();
				jQuery("#downloads").hide();
				jQuery("#manual").hide();
				var pricelistSections = data[key]["pricelist_sections"].split(",");
				
				if(pricelistSections){
					jQuery("#" + pricelistSections[0]).show();
					jQuery("[name=type]").val(pricelistSections[0]);
					if(pricelistSections.length > 1){
						jQuery(".bwge_tabs").show();
						for( k=0 ; k<pricelistSections.length; k++ ){						
							jQuery("#" + pricelistSections[k] + "_li").show();					
						}
					}
					else{
						jQuery(".bwge_tabs").hide();
					}					
				}
				else{
					jQuery("[name=type]").val("");
				}
				
			}		
		}
        /* Pause videos.*/
        jQuery("#bwge_image_container").find("iframe").each(function () {
          jQuery(this)[0].contentWindow.postMessage('{"event":"command","func":"pauseVideo","args":""}', '*');
          jQuery(this)[0].contentWindow.postMessage('{ "method": "pause" }', "*");
          jQuery(this)[0].contentWindow.postMessage('pause', '*');
        });
        jQuery("#bwge_image_container<?php echo $bwge; ?>").find("video").each(function () {
          jQuery(this).trigger('pause');
        });
        if (typeof data[key] != 'undefined') {
          if (typeof data[current_key] != 'undefined') {
            if (jQuery(".bwge_play_pause") && !jQuery(".bwge_play_pause").hasClass("fa-play")) {
              bwge_play();
            }
            if (!from_effect) {
              /* Change image key.*/
              jQuery("#bwge_current_image_key").val(key);
              /*if (current_key == '-1') {
                current_key = jQuery(".bwge_thumb_active").children("img").attr("image_key");
              }*/
            }
            if (bwge_trans_in_progress) {
              event_stack.push(current_key + '-' + key);
              return;
            }
            var direction = 'right';
            if (bwge_current_key > key) {
              var direction = 'left';
            }
            else if (bwge_current_key == key) {
              return;
            }
            /*jQuery("#bwge_spider_popup_left").hover().css({"display": "inline"});
            jQuery("#bwge_spider_popup_right").hover().css({"display": "inline"});*/
            jQuery(".bwge_image_count").html(data[key]["number"]);
            /* Set filmstrip initial position.*/
            jQuery(".bwge_watermark").css({display: 'none'});
            /* Set active thumbnail position.*/
            bwge_current_filmstrip_pos = key * (jQuery(".bwge_filmstrip_thumbnail").<?php echo $width_or_height; ?>() + 2 + 2 * <?php echo $theme_row->lightbox_filmstrip_thumb_border_width; ?>);
            bwge_current_key = key;
            /* Change hash.*/
            window.location.hash = "bwge<?php echo $gallery_id; ?>/" + data[key]["id"];
       

            /* Increase image hit counter.*/
            bwge_spider_set_input_value('rate_ajax_task', 'save_hit_count');
            
            jQuery(".bwge_image_hits span").html(++data[key]["hit_count"]);
            /* Change image id.*/
            jQuery("#bwge_popup_image").attr('image_id', data[key]["id"]);
            /* Change image title, description.*/
            jQuery(".bwge_image_title").html(jQuery('<span style="display: block;" />').html(data[key]["alt"]).text());
            jQuery(".bwge_image_description").html(jQuery('<span style="display: block;" />').html(data[key]["description"]).text());
            jQuery(".bwge_image_info").removeAttr("style");
            if (data[key]["alt"].trim() == "") {
              if (data[key]["description"].trim() == "") {
                      jQuery(".bwge_image_info").css("background", "none");
              }
            }
            if (jQuery(".bwge_image_info_container1").css("display") != 'none') {
              jQuery(".bwge_image_info_container1").css("display", "table-cell");
            }
            else {
              jQuery(".bwge_image_info_container1").css("display", "none");
            }
            /* Change image rating.*/
            if (jQuery(".bwge_image_rate_container1").css("display") != 'none') {
              jQuery(".bwge_image_rate_container1").css("display", "table-cell");
            }
            else {
              jQuery(".bwge_image_rate_container1").css("display", "none");
            }
            var current_image_class = jQuery(".bwge_popup_image_spun").css("zIndex") == 2 ? ".bwge_popup_image_spun" : ".bwge_popup_image_second_spun";
            var next_image_class = current_image_class == ".bwge_popup_image_second_spun" ? ".bwge_popup_image_spun" : ".bwge_popup_image_second_spun";
            
            var is_embed = data[key]['filetype'].indexOf("EMBED_") > -1 ? true : false;
            var is_embed_instagram_post = data[key]['filetype'].indexOf('INSTAGRAM_POST') > -1 ? true : false;
            var is_embed_instagram_video = data[key]['filetype'].indexOf('INSTAGRAM_VIDEO') > -1 ? true : false;
            var cur_height = jQuery(current_image_class).height();
            var cur_width = jQuery(current_image_class).width();
            var innhtml = '<span class="bwge_popup_image_spun1" style="display:  ' + (!is_embed ? 'table' : 'block') + ' ;width: inherit; height: inherit;"><span class="bwge_popup_image_spun2" style="display: ' + (!is_embed ? 'table-cell' : 'block') + '; vertical-align: middle; text-align: center; height: 100%;">';
            if (!is_embed) {
              innhtml += '<img style="max-height: ' + cur_height + 'px; max-width: ' + cur_width + 'px;" class="bwge_popup_image bwge_popup_watermark" src="<?php echo site_url() . '/' . $WD_BWGE_UPLOAD_DIR; ?>' + jQuery('<span style="display: block;" />').html(data[key]["image_url"]).text() + '" alt="' + data[key]["alt"] + '" />';
            }
            else { /*is_embed*/

              /*innhtml += '<span style="height: ' + cur_height + 'px; width: ' + cur_width + 'px;" class="bwge_popup_embed bwge_popup_watermark">';*/
             innhtml += '<span class="bwge_popup_embed bwge_popup_watermark" style="display: block; table-layout: fixed; height: 100%;">' + (is_embed_instagram_video ? '<div class="bwge_inst_play_btn_cont" onclick="bwge_play_instagram_video(this)" ><div class="bwge_inst_play"></div></div>' : ' '); 
              if(is_embed_instagram_post){
                var post_width = 0;
                var post_height = 0;
                if(cur_height <cur_width +88 ){
                  post_height = cur_height;
                  post_width = post_height -88; 
                }
                else{
                  post_width = cur_width;
                  post_height = post_width +88 ;  
                }
                innhtml += bwge_spider_display_embed(data[key]['filetype'], data[key]['image_url'], data[key]['filename'], {class:"bwge_embed_frame",  frameborder:"0", allowfullscreen:"allowfullscreen", style:"width:"+post_width+"px; height:"+post_height+"px; vertical-align:middle; display:inline-block; position:relative; top: "+0.5*(cur_height-post_height)+ "px; " });
              }
              else{
                innhtml += bwge_spider_display_embed(data[key]['filetype'],data[key]['image_url'], data[key]['filename'], {class:"bwge_embed_frame", frameborder:"0", allowfullscreen:"allowfullscreen", style:"width:inherit; height:inherit; vertical-align:middle; display:block;" });
              }
              innhtml += "</span>";
            }
            innhtml += '</span></span>';
            jQuery(next_image_class).html(innhtml);
            jQuery(".bwge_popup_embed > .bwge_embed_frame > img, .bwge_popup_embed > .bwge_embed_frame > video").css({
              maxWidth: cur_width,
              maxHeight: cur_height,
              height: 'auto',
            });
            function bwge_afterload() {
              <?php
              if ($option_row->preload_images) {
                echo 'bwge_preload_images(key);';
              }
              ?>
              bwge_<?php echo $image_effect; ?>(current_image_class, next_image_class, direction);
              
              /* Pause videos facebook video.*/
              jQuery(current_image_class).find('.bwge_fb_video').each(function () {
                jQuery(this).attr('src', '');
              });
              
              jQuery("#bwge_download").show();
              if (!is_embed) {
                jQuery("#bwge_fullsize_image").attr("href", "<?php echo site_url() . '/' . $WD_BWGE_UPLOAD_DIR; ?>" + data[key]['image_url']);
                jQuery("#bwge_download").attr("href", "<?php echo site_url() . '/' . $WD_BWGE_UPLOAD_DIR; ?>" + data[key]['image_url']);
              }
              else {
                jQuery("#bwge_fullsize_image").attr("href", data[key]['image_url']);
                if (data[key]['filetype'].indexOf("FLICKR_") > -1) {
                  jQuery("#bwge_download").attr("href", data[key]['filename']);
                }
                else if (data[key]['filetype'].indexOf("INSTAGRAM_") > -1) {
                  jQuery("#bwge_download").attr("href", data[key]['thumb_url'].substring(0, data[key]['thumb_url'].length - 1) + 'l');
                }
                else {
                 jQuery("#bwge_download").hide();
                }
              }
              var image_arr = data[key]['image_url'].split("/");
              jQuery("#bwge_download").attr("download", image_arr[image_arr.length - 1]);
              /* Change image social networks urls.*/
              
			if (jQuery(".bwge_ecommerce_container").hasClass("bwge_open")) {
			  /* Pricelist */	
			  if(data[key]["pricelist"] == 0){
			    /* Close ecommerce.*/
			    bwge_popup_sidebar_close(jQuery(".bwge_ecommerce_container"));
			    bwge_animate_image_box_for_hide_sidebar();
			 
			    jQuery(".bwge_ecommerce_container").attr("class", "bwge_ecommerce_container bwge_close");
			    jQuery(".bwge_ecommerce").attr("title", "<?php echo __('Show Ecommerce', 'bwge'); ?>");
			    jQuery(".bwge_spider_popup_close_fullscreen").show();				
			  }
			  else{			  
                bwge_get_ajax_pricelist();				
			  }
			}


            }
            if (!is_embed) {
              var cur_img = jQuery(next_image_class).find('img');
              cur_img.one('load', function() {
                bwge_afterload();
              }).each(function() {
                if(this.complete) jQuery(this).load();
              });
            }
            else {
              bwge_afterload();
            }
          }
        }
      }
      jQuery(document).on('keydown', function (e) {
        if (jQuery("#bwge_name").is(":focus") || jQuery("#bwge_email").is(":focus") || jQuery("#bwge_comment").is(":focus") || jQuery("#bwge_captcha_input").is(":focus")) {
          return;
        }
        if (e.keyCode === 39) { /* Right arrow.*/
          bwge_change_image(parseInt(jQuery('#bwge_current_image_key').val()), parseInt(jQuery('#bwge_current_image_key').val()) + 1, data)
        }
        else if (e.keyCode === 37) { /* Left arrow.*/
          bwge_change_image(parseInt(jQuery('#bwge_current_image_key').val()), parseInt(jQuery('#bwge_current_image_key').val()) - 1, data)
        }
        else if (e.keyCode === 27) { /* Esc.*/
          bwge_spider_destroypopup(1000);
        }
        else if (e.keyCode === 32) { /* Space.*/
          jQuery(".bwge_play_pause").trigger('click');
        }
      });
      function bwge_preload_images(key) {
        count = <?php echo (int) $option_row->preload_images_count / 2; ?>;
        var count_all = data.length;
        if (count_all < <?php echo $option_row->preload_images_count; ?>) {
          count = 0;
        }
        if (count != 0) {
          for (var i = key - count; i < key + count; i++) {
            var index = parseInt((i + count_all) % count_all);
            var is_embed = data[index]['filetype'].indexOf("EMBED_") > -1 ? true : false;
            if (typeof data[index] != "undefined") {
              if (!is_embed) {
                jQuery("<img/>").attr("src", '<?php echo site_url() . '/' . $WD_BWGE_UPLOAD_DIR; ?>' + jQuery('<span style="display: block;" />').html(data[index]["image_url"]).text());
              }
            }
          }
        }
        else {
          for (var i = 0; i < data.length; i++) {
            var is_embed = data[i]['filetype'].indexOf("EMBED_") > -1 ? true : false;
            if (typeof data[index] != "undefined") {
              if (!is_embed) {
                jQuery("<img/>").attr("src", '<?php echo site_url() . '/' . $WD_BWGE_UPLOAD_DIR; ?>' + jQuery('<span style="display: block;" />').html(data[i]["image_url"]).text());
              }
            }
          }
        }
      }
      function bwge_popup_resize() {
        if (typeof jQuery().fullscreen !== 'undefined') {
          if (jQuery.isFunction(jQuery().fullscreen)) {
            if (!jQuery.fullscreen.isFullScreen()) {
              jQuery(".bwge_resize-full").show();
              jQuery(".bwge_resize-full").attr("class", "bwge_ctrl_btn bwge_resize-full fa fa-resize-full");
              jQuery(".bwge_resize-full").attr("title", "<?php echo __('Maximize', 'bwge'); ?>");
              jQuery(".bwge_fullscreen").attr("class", "bwge_ctrl_btn bwge_fullscreen fa fa-fullscreen");
              jQuery(".bwge_fullscreen").attr("title", "<?php echo __('Fullscreen', 'bwge'); ?>");
            }
          }
        }
        var comment_container_width = 0;
        if (jQuery(".bwge_comment_container").hasClass("bwge_open") || jQuery(".bwge_ecommerce_container").hasClass("bwge_open")) {
          comment_container_width = <?php echo $theme_row->lightbox_comment_width; ?>;
        }
        if (comment_container_width > jQuery(window).width()) {
          comment_container_width = jQuery(window).width();
          jQuery(".bwge_comment_container").css({
            width: comment_container_width
          });
		  jQuery(".bwge_ecommerce_container").css({
            width: comment_container_width
          });
          jQuery(".bwge_spider_popup_close_fullscreen").hide();
        }
        else {
          jQuery(".bwge_spider_popup_close_fullscreen").show();
        }
        if (!(!(jQuery(window).height() > <?php echo $image_height; ?>) || !(<?php echo $open_with_fullscreen; ?> != 1))) {
          jQuery("#bwge_spider_popup_wrap").css({
            height: <?php echo $image_height; ?>,
            top: '50%',
            marginTop: -<?php echo $image_height / 2; ?>,
            zIndex: 100000
          });
          jQuery(".bwge_image_container").css({height: (<?php echo $image_height - ($filmstrip_direction == 'horizontal' ? $image_filmstrip_height : 0); ?>)});
          jQuery(".bwge_popup_image").css({
            maxHeight: <?php echo $image_height - ($filmstrip_direction == 'horizontal' ? $image_filmstrip_height : 0); ?>
          });
          jQuery(".bwge_popup_embed > .bwge_embed_frame > img, .bwge_popup_embed > .bwge_embed_frame > video").css({
            maxHeight: <?php echo $image_height - ($filmstrip_direction == 'horizontal' ? $image_filmstrip_height : 0); ?>
          });
          bwge_resize_instagram_post();
          <?php if ($filmstrip_direction == 'vertical') { ?>
          jQuery(".bwge_filmstrip_container").css({height: <?php echo $image_height; ?>});
          jQuery(".bwge_filmstrip").css({height: (<?php echo $image_height; ?> - 40)});
          <?php } ?>
          bwge_popup_current_height = <?php echo $image_height; ?>;
        }
        else {
          jQuery("#bwge_spider_popup_wrap").css({
            height: jQuery(window).height(),
            top: 0,
            marginTop: 0,
            zIndex: 100000
          });
          jQuery(".bwge_image_container").css({height: (jQuery(window).height() - <?php echo ($filmstrip_direction == 'horizontal' ? $image_filmstrip_height : 0); ?>)});
          jQuery(".bwge_popup_image").css({
            maxHeight: jQuery(window).height() - <?php echo ($filmstrip_direction == 'horizontal' ? $image_filmstrip_height : 0); ?>
          });
          jQuery(".bwge_popup_embed > .bwge_embed_frame > img, .bwge_popup_embed > .bwge_embed_frame > video").css({
            maxHeight: jQuery(window).height() - <?php echo ($filmstrip_direction == 'horizontal' ? $image_filmstrip_height : 0); ?>
          });
          bwge_resize_instagram_post();
          <?php if ($filmstrip_direction == 'vertical') { ?>
          jQuery(".bwge_filmstrip_container").css({height: (jQuery(window).height())});
          jQuery(".bwge_filmstrip").css({height: (jQuery(window).height() - 40)});
          <?php } ?>
          bwge_popup_current_height = jQuery(window).height();
        }
        if (!(!(jQuery(window).width() >= <?php echo $image_width; ?>) || !(<?php echo $open_with_fullscreen; ?> != 1))) {
          jQuery("#bwge_spider_popup_wrap").css({
            width: <?php echo $image_width; ?>,
            left: '50%',
            marginLeft: -<?php echo $image_width / 2; ?>,
            zIndex: 100000
          });
          jQuery(".bwge_image_wrap").css({width: <?php echo $image_width; ?> - comment_container_width});
          jQuery(".bwge_image_container").css({width: (<?php echo $image_width - ($filmstrip_direction == 'vertical' ? $image_filmstrip_width : 0); ?> - comment_container_width)});
          jQuery(".bwge_popup_image").css({
            maxWidth: <?php echo $image_width - ($filmstrip_direction == 'vertical' ? $image_filmstrip_width : 0); ?> - comment_container_width
          });
          jQuery(".bwge_popup_embed > .bwge_embed_frame > img, .bwge_popup_embed > .bwge_embed_frame > video").css({
            maxWidth: <?php echo $image_width - ($filmstrip_direction == 'vertical' ? $image_filmstrip_width : 0); ?> - comment_container_width
          });
          bwge_resize_instagram_post();
          <?php if ($filmstrip_direction == 'horizontal') { ?>
          jQuery(".bwge_filmstrip_container").css({width: <?php echo $image_width; ?> - comment_container_width});
          jQuery(".bwge_filmstrip").css({width: (<?php echo $image_width; ?>  - comment_container_width- 40)});
          <?php } ?>
          bwge_popup_current_width = <?php echo $image_width; ?>;
        }
        else {
          jQuery("#bwge_spider_popup_wrap").css({
            width: jQuery(window).width(),
            left: 0,
            marginLeft: 0,
            zIndex: 100000
          });
          jQuery(".bwge_image_wrap").css({width: (jQuery(window).width() - comment_container_width)});
          jQuery(".bwge_image_container").css({width: (jQuery(window).width() - <?php echo ($filmstrip_direction == 'vertical' ? $image_filmstrip_width : 0); ?> - comment_container_width)});
          jQuery(".bwge_popup_image").css({
            maxWidth: jQuery(window).width() - <?php echo ($filmstrip_direction == 'vertical' ? $image_filmstrip_width : 0); ?> - comment_container_width
          });
          jQuery(".bwge_popup_embed > .bwge_embed_frame > img, .bwge_popup_embed > .bwge_embed_frame > video").css({
            maxWidth: jQuery(window).width() - <?php echo ($filmstrip_direction == 'vertical' ? $image_filmstrip_width : 0); ?> - comment_container_width
          });
          bwge_resize_instagram_post();
          <?php if ($filmstrip_direction == 'horizontal') { ?>
          jQuery(".bwge_filmstrip_container").css({width: (jQuery(window).width() - comment_container_width)});
          jQuery(".bwge_filmstrip").css({width: (jQuery(window).width() - comment_container_width - 40)});
          <?php } ?>
          bwge_popup_current_width = jQuery(window).width();
        }
        /* Set watermark container size.*/
        bwge_change_watermark_container();
        if (!(!(jQuery(window).height() > <?php echo $image_height - 2 * $theme_row->lightbox_close_btn_top; ?>) || !(jQuery(window).width() >= <?php echo $image_width - 2 * $theme_row->lightbox_close_btn_right; ?>) || !(<?php echo $open_with_fullscreen; ?> != 1))) {
          jQuery(".bwge_spider_popup_close_fullscreen").attr("class", "bwge_spider_popup_close");
        }
        else {
          if (!(!(jQuery("#bwge_spider_popup_wrap").width() < jQuery(window).width()) || !(jQuery("#bwge_spider_popup_wrap").height() < jQuery(window).height()))) {
            jQuery(".bwge_spider_popup_close").attr("class", "bwge_ctrl_btn bwge_spider_popup_close_fullscreen");
          }
        }
        if ( "<?php echo $theme_row->lightbox_ctrl_btn_pos ;?>" == 'bottom') {
          jQuery(".bwge_toggle_container").css("bottom", jQuery(".bwge_ctrl_btn_container").height() + "px");
        }
        if ( "<?php echo $theme_row->lightbox_ctrl_btn_pos ;?>" == 'top') {
          jQuery(".bwge_toggle_container").css("top", jQuery(".bwge_ctrl_btn_container").height() + "px");
        }
      }
      jQuery(window).resize(function() {
        if (typeof jQuery().fullscreen !== 'undefined') {
          if (jQuery.isFunction(jQuery().fullscreen)) {
            if (!jQuery.fullscreen.isFullScreen()) {
              bwge_popup_resize();
            }
          }
        }
      });
      /* Popup current width/height.*/
      var bwge_popup_current_width = <?php echo $image_width; ?>;
      var bwge_popup_current_height = <?php echo $image_height; ?>;
 	  // open  popup sidebar
	 function bwge_popup_sidebar_open(obj){
          var comment_container_width = <?php echo $theme_row->lightbox_comment_width; ?>;
          if (comment_container_width > jQuery(window).width()) {
            comment_container_width = jQuery(window).width();
            obj.css({
              width: comment_container_width
            });
            jQuery(".bwge_spider_popup_close_fullscreen").hide();
            jQuery(".bwge_spider_popup_close").hide();
            if (jQuery(".bwge_ctrl_btn").hasClass("fa-pause")) {
              var isMobile = (/android|webos|iphone|ipad|ipod|blackberry|iemobile|opera mini/i.test(navigator.userAgent.toLowerCase()));
              jQuery(".bwge_play_pause").trigger(isMobile ? 'touchend' : 'click');
            }
          }
          else {
            jQuery(".bwge_spider_popup_close_fullscreen").show();
          }
		
          obj.animate({<?php echo $theme_row->lightbox_comment_pos; ?>: 0}, 500);	  
		  
	  }
	 function bwge_popup_sidebar_close(obj){
        var border_width = parseInt(obj.css('borderRightWidth'));
        if (!border_width) {
           border_width = 0;
        }
        obj.animate({<?php echo $theme_row->lightbox_comment_pos; ?>: -obj.width() - border_width}, 500);
	 }
	  function bwge_animate_image_box_for_hide_sidebar(){
		 jQuery(".bwge_image_wrap").animate({
		   <?php echo $theme_row->lightbox_comment_pos; ?>: 0,
		   width: jQuery("#bwge_spider_popup_wrap").width()
		 }, 500);
		 jQuery(".bwge_image_container").animate({
		   width: jQuery("#bwge_spider_popup_wrap").width() - <?php echo ($filmstrip_direction == 'vertical' ? $image_filmstrip_width : 0); ?>}, 500);
		 jQuery(".bwge_popup_image").animate({
			maxWidth: jQuery("#bwge_spider_popup_wrap").width() - <?php echo ($filmstrip_direction == 'vertical' ? $image_filmstrip_width : 0); ?>
		 }, {
		   duration: 500,
		   complete: function () { bwge_change_watermark_container(); }
		 });
		 jQuery(".bwge_popup_embed").animate({
			width: jQuery("#bwge_spider_popup_wrap").width() - <?php echo ($filmstrip_direction == 'vertical' ? $image_filmstrip_width : 0); ?>
		 }, {
		   duration: 500,
		   complete: function () { 
			bwge_resize_instagram_post();
			bwge_change_watermark_container(); }
		});
		 jQuery(".bwge_filmstrip_container").animate({<?php echo $width_or_height; ?>: jQuery(".bwge_spider_popup_wrap").<?php echo $width_or_height; ?>()}, 500);
		 jQuery(".bwge_filmstrip").animate({<?php echo $width_or_height; ?>: jQuery(".bwge_spider_popup_wrap").<?php echo $width_or_height; ?>() - 40}, 500);
		 /* Set filmstrip initial position.*/
		 bwge_set_filmstrip_pos(jQuery(".bwge_spider_popup_wrap").width() - 40);	  
		 jQuery(".bwge_spider_popup_close_fullscreen").show(100);		 
	 }
	 
	 function bwge_animate_image_box_for_show_sidebar(){
		var bwge_comment_container = jQuery(".bwge_comment_container").width() || jQuery(".bwge_ecommerce_container").width();
          jQuery(".bwge_image_wrap").animate({
            <?php echo $theme_row->lightbox_comment_pos; ?>: bwge_comment_container,
            width: jQuery("#bwge_spider_popup_wrap").width() - bwge_comment_container}, 500);
          jQuery(".bwge_image_container").animate({
            width: jQuery("#bwge_spider_popup_wrap").width() - <?php echo ($filmstrip_direction == 'vertical' ? $image_filmstrip_width : 0); ?> - bwge_comment_container}, 500);
          jQuery(".bwge_popup_image").animate({
              maxWidth: jQuery("#bwge_spider_popup_wrap").width() - bwge_comment_container - <?php echo ($filmstrip_direction == 'vertical' ? $image_filmstrip_width : 0); ?>
            }, {
              duration: 500,
              complete: function () { bwge_change_watermark_container(); }
            });
          jQuery(".bwge_popup_embed > .bwge_embed_frame > img, .bwge_popup_embed > .bwge_embed_frame > video").animate({
              maxWidth: jQuery("#bwge_spider_popup_wrap").width() - bwge_comment_container - <?php echo ($filmstrip_direction == 'vertical' ? $image_filmstrip_width : 0); ?>
            }, {
              duration: 500,
              complete: function () { 
                bwge_resize_instagram_post();
                bwge_change_watermark_container(); }
            });
          jQuery(".bwge_filmstrip_container").css({<?php echo $width_or_height; ?>: jQuery("#bwge_spider_popup_wrap").<?php echo $width_or_height; ?>() - <?php echo ($filmstrip_direction == 'vertical' ? 0: 'bwge_comment_container'); ?>});
          jQuery(".bwge_filmstrip").animate({<?php echo $width_or_height; ?>: jQuery(".bwge_filmstrip_container").<?php echo $width_or_height; ?>() - 40}, 500);
          /* Set filmstrip initial position.*/
          bwge_set_filmstrip_pos(jQuery(".bwge_filmstrip_container").<?php echo $width_or_height; ?>() - 40);
          		 
	 }
      /* Open/close comments.*/
     function bwge_comment() {		
        jQuery(".bwge_watermark").css({display: 'none'});
        jQuery(".bwge_ecommerce_wrap").css("z-index","-1");		  
        jQuery(".bwge_comment_wrap").css("z-index","25");			
		if(jQuery(".bwge_ecommerce_container").hasClass("bwge_open") ){
		  bwge_popup_sidebar_close(jQuery(".bwge_ecommerce_container"));
		  jQuery(".bwge_ecommerce_container").attr("class", "bwge_ecommerce_container bwge_close");
          jQuery(".bwge_ecommerce").attr("title", "<?php echo __('Show Ecommerce', 'bwge'); ?>");
         
		}		
        if (jQuery(".bwge_comment_container").hasClass("bwge_open") ) {
          /* Close comment.*/
		  bwge_popup_sidebar_close(jQuery(".bwge_comment_container"));
		  bwge_animate_image_box_for_hide_sidebar();		  
          jQuery(".bwge_comment_container").attr("class", "bwge_comment_container bwge_close");
          jQuery(".bwge_comment").attr("title", "<?php echo __('Show Comments', 'bwge'); ?>");
          jQuery(".bwge_spider_popup_close_fullscreen").show();
        }
        else {

          /* Open comment.*/
		  bwge_popup_sidebar_open(jQuery(".bwge_comment_container"));
		  bwge_animate_image_box_for_show_sidebar();         
          jQuery(".bwge_comment_container").attr("class", "bwge_comment_container bwge_open");
          jQuery(".bwge_comment").attr("title", "<?php echo __('Hide Comments', 'bwge'); ?>");
          /* Load comments.*/
          var cur_image_key = parseInt(jQuery("#bwge_current_image_key").val());
          if (data[cur_image_key]["comment_count"] != 0) {
            jQuery("#bwge_added_comments").show();
            bwge_spider_set_input_value('ajax_task', 'display');
            bwge_spider_set_input_value('image_id', jQuery('#bwge_popup_image').attr('image_id'));
            bwge_spider_ajax_save('bwge_comment_form');
          }
        }
      }
      


	/* Open/close ecommerce.*/
      function bwge_ecommerce() {

        jQuery(".bwge_watermark").css({display: 'none'});
        jQuery(".bwge_ecommerce_wrap").css("z-index","25");		  
        jQuery(".bwge_comment_wrap").css("z-index","-1");			
		if(jQuery(".bwge_comment_container").hasClass("bwge_open") ){
		  bwge_popup_sidebar_close(jQuery(".bwge_comment_container"));
		  jQuery(".bwge_comment_container").attr("class", "bwge_comment_container bwge_close");
          jQuery(".bwge_comment").attr("title", "<?php echo __('Show Comments', 'bwge'); ?>"); 
	  
		}	
		
        if (jQuery(".bwge_ecommerce_container").hasClass("bwge_open")) {
          /* Close ecommerce.*/
		  bwge_popup_sidebar_close(jQuery(".bwge_ecommerce_container"));
		  bwge_animate_image_box_for_hide_sidebar();
		 
          jQuery(".bwge_ecommerce_container").attr("class", "bwge_ecommerce_container bwge_close");
          jQuery(".bwge_ecommerce").attr("title", "<?php echo __('Show Ecommerce', 'bwge'); ?>");
         // jQuery(".bwge_spider_popup_close_fullscreen").show();
        }
        else {
          /* Open ecommerce.*/
		  bwge_popup_sidebar_open(jQuery(".bwge_ecommerce_container"));
          bwge_animate_image_box_for_show_sidebar();
          jQuery(".bwge_ecommerce_container").attr("class", "bwge_ecommerce_container bwge_open");
          jQuery(".bwge_ecommerce").attr("title", "<?php echo __('Hide Ecommerce', 'bwge'); ?>");
	
		   bwge_get_ajax_pricelist();	
    
        }
      }
	

	 function bwge_reset_zoom() {
        var isMobile = (/android|webos|iphone|ipad|ipod|blackberry|iemobile|opera mini/i.test(navigator.userAgent.toLowerCase()));
        var viewportmeta = document.querySelector('meta[name="viewport"]');
        if (isMobile) {
          if (viewportmeta) {
            viewportmeta.content = 'width=device-width, initial-scale=1, maximum-scale=1, minimum-scale=1, user-scalable=0';
          }
        }
      }
      jQuery(document).ready(function () {

        var bwge_hash = window.location.hash;
        if (!bwge_hash || bwge_hash.indexOf("bwge") == "-1") {
          window.location.hash = "bwge<?php echo $gallery_id; ?>/<?php echo $current_image_id; ?>";
        }
      	<?php
        if ($image_right_click) {
          ?>
          /* Disable right click.*/
          jQuery(".bwge_image_wrap").bind("contextmenu", function (e) {
            return false;
          });
           jQuery(".bwge_image_wrap").css('webkitTouchCallout','none');
          <?php
        }
        ?>
        if (typeof jQuery().swiperight !== 'undefined') {
          if (jQuery.isFunction(jQuery().swiperight)) {
            jQuery('#bwge_image_wrap').swiperight(function () {
            bwge_change_image(parseInt(jQuery('#bwge_current_image_key').val()), (parseInt(jQuery('#bwge_current_image_key').val()) + data.length - 1) % data.length, data);
              return false;
            });
          }
        }
        if (typeof jQuery().swipeleft !== 'undefined') {
          if (jQuery.isFunction(jQuery().swipeleft)) {
            jQuery('#bwge_image_wrap').swipeleft(function () {
            bwge_change_image(parseInt(jQuery('#bwge_current_image_key').val()), (parseInt(jQuery('#bwge_current_image_key').val()) + 1) % data.length, data);
              return false;
            });
          }
        }

        bwge_reset_zoom();
        var isMobile = (/android|webos|iphone|ipad|ipod|blackberry|iemobile|opera mini/i.test(navigator.userAgent.toLowerCase()));
        var bwge_click = isMobile ? 'touchend' : 'click';
        

        jQuery("#bwge_spider_popup_left").on(bwge_click, function () {
          bwge_change_image(parseInt(jQuery('#bwge_current_image_key').val()), (parseInt(jQuery('#bwge_current_image_key').val()) + data.length - 1) % data.length, data);
          return false;
        });
        jQuery("#bwge_spider_popup_right").on(bwge_click, function () {
          bwge_change_image(parseInt(jQuery('#bwge_current_image_key').val()), (parseInt(jQuery('#bwge_current_image_key').val()) + 1) % data.length, data);
          return false;
        });
        if (navigator.appVersion.indexOf("MSIE 10") != -1 || navigator.appVersion.indexOf("MSIE 9") != -1) {
          setTimeout(function () {
            bwge_popup_resize();
          }, 1);
        }
        else {
          bwge_popup_resize();
        }
        jQuery(".bwge_watermark").css({display: 'none'});
        setTimeout(function () {
          bwge_change_watermark_container();
        }, 500);
        /* If browser doesn't support Fullscreen API.*/
        if (typeof jQuery().fullscreen !== 'undefined') {
          if (jQuery.isFunction(jQuery().fullscreen)) {
            if (!jQuery.fullscreen.isNativelySupported()) {
              jQuery(".bwge_fullscreen").hide();
            }
          }
        }
        /* Set image container height.*/
        <?php if ($filmstrip_direction == 'horizontal') { ?>
          jQuery(".bwge_image_container").height(jQuery(".bwge_image_wrap").height() - <?php echo $image_filmstrip_height; ?>);
          jQuery(".bwge_image_container").width(jQuery(".bwge_image_wrap").width());
          <?php }
        else {
          ?>
          jQuery(".bwge_image_container").height(jQuery(".bwge_image_wrap").height());
          jQuery(".bwge_image_container").width(jQuery(".bwge_image_wrap").width() - <?php echo $image_filmstrip_width; ?>);
          <?php
        } ?>
        /* Change default scrollbar in comments, ecommerce.*/
        if (typeof jQuery().mCustomScrollbar !== 'undefined' && jQuery.isFunction(jQuery().mCustomScrollbar)) {
          jQuery(".bwge_comments").mCustomScrollbar({scrollInertia: 150});
          jQuery(".bwge_ecommerce_panel").mCustomScrollbar({
				scrollInertia: 150,    
				advanced:{
                  updateOnContentResize: true
                }
			});
		
        }
        var mousewheelevt = (/Firefox/i.test(navigator.userAgent)) ? "DOMMouseScroll" : "mousewheel" /*FF doesn't recognize mousewheel as of FF3.x*/
        jQuery('.bwge_filmstrip').on(mousewheelevt, function(e) {
          var evt = window.event || e; /* Equalize event object.*/
          evt = evt.originalEvent ? evt.originalEvent : evt; /* Convert to originalEvent if possible.*/
          var delta = evt.detail ? evt.detail*(-40) : evt.wheelDelta; /* Check for detail first, because it is used by Opera and FF.*/
          var isMobile = (/android|webos|iphone|ipad|ipod|blackberry|iemobile|opera mini/i.test(navigator.userAgent.toLowerCase()));
          if (delta > 0) {
            /* Scroll up.*/
            jQuery(".bwge_filmstrip_left").trigger(isMobile ? 'touchend' : 'click');
          }
          else {
            /* Scroll down.*/
            jQuery(".bwge_filmstrip_right").trigger(isMobile ? 'touchend' : 'click');
          }
        });
        jQuery(".bwge_filmstrip_right").on(bwge_click, function () {
          jQuery( ".bwge_filmstrip_thumbnails" ).stop(true, false);
          if (jQuery(".bwge_filmstrip_thumbnails").position().<?php echo $left_or_top; ?> >= -(jQuery(".bwge_filmstrip_thumbnails").<?php echo $width_or_height; ?>() - jQuery(".bwge_filmstrip").<?php echo $width_or_height; ?>())) {
            jQuery(".bwge_filmstrip_left").css({opacity: 1, filter: "Alpha(opacity=100)"});
            if (jQuery(".bwge_filmstrip_thumbnails").position().<?php echo $left_or_top; ?> < -(jQuery(".bwge_filmstrip_thumbnails").<?php echo $width_or_height; ?>() - jQuery(".bwge_filmstrip").<?php echo $width_or_height; ?>() - <?php echo $filmstrip_thumb_margin_hor + $image_filmstrip_width; ?>)) {
              jQuery(".bwge_filmstrip_thumbnails").animate({<?php echo $left_or_top; ?>: -(jQuery(".bwge_filmstrip_thumbnails").<?php echo $width_or_height; ?>() - jQuery(".bwge_filmstrip").<?php echo $width_or_height; ?>())}, 500, 'linear');
            }
            else {
              jQuery(".bwge_filmstrip_thumbnails").animate({<?php echo $left_or_top; ?>: (jQuery(".bwge_filmstrip_thumbnails").position().<?php echo $left_or_top; ?> - <?php echo $filmstrip_thumb_margin_hor + $image_filmstrip_width; ?>)}, 500, 'linear');
            }
          }
          /* Disable right arrow.*/
          window.setTimeout(function(){
            if (jQuery(".bwge_filmstrip_thumbnails").position().<?php echo $left_or_top; ?> == -(jQuery(".bwge_filmstrip_thumbnails").<?php echo $width_or_height; ?>() - jQuery(".bwge_filmstrip").<?php echo $width_or_height; ?>())) {
              jQuery(".bwge_filmstrip_right").css({opacity: 0.3, filter: "Alpha(opacity=30)"});
            }
          }, 500);
        });
        jQuery(".bwge_filmstrip_left").on(bwge_click, function () {
          jQuery( ".bwge_filmstrip_thumbnails" ).stop(true, false);
          if (jQuery(".bwge_filmstrip_thumbnails").position().<?php echo $left_or_top; ?> < 0) {
            jQuery(".bwge_filmstrip_right").css({opacity: 1, filter: "Alpha(opacity=100)"});
            if (jQuery(".bwge_filmstrip_thumbnails").position().<?php echo $left_or_top; ?> > - <?php echo $filmstrip_thumb_margin_hor + $image_filmstrip_width; ?>) {
              jQuery(".bwge_filmstrip_thumbnails").animate({<?php echo $left_or_top; ?>: 0}, 500, 'linear');
            }
            else {
              jQuery(".bwge_filmstrip_thumbnails").animate({<?php echo $left_or_top; ?>: (jQuery(".bwge_filmstrip_thumbnails").position().<?php echo $left_or_top; ?> + <?php echo $image_filmstrip_width + $filmstrip_thumb_margin_hor; ?>)}, 500, 'linear');
            }
          }
          /* Disable left arrow.*/
          window.setTimeout(function(){
            if (jQuery(".bwge_filmstrip_thumbnails").position().<?php echo $left_or_top; ?> == 0) {
              jQuery(".bwge_filmstrip_left").css({opacity: 0.3, filter: "Alpha(opacity=30)"});
            }
          }, 500);
        });
        /* Set filmstrip initial position.*/
        bwge_set_filmstrip_pos(jQuery(".bwge_filmstrip").<?php echo $width_or_height; ?>());
        /* Show/hide image title/description.*/
        jQuery(".bwge_info").on(bwge_click, function() {
          if (jQuery(".bwge_image_info_container1").css("display") == 'none') {
            jQuery(".bwge_image_info_container1").css("display", "table-cell");
            jQuery(".bwge_info").attr("title", "<?php echo __('Hide info', 'bwge'); ?>");
          }
          else {
            jQuery(".bwge_image_info_container1").css("display", "none");
            jQuery(".bwge_info").attr("title", "<?php echo __('Show info', 'bwge'); ?>");
          }
        });

        /* Open/close comments.*/
        jQuery(".bwge_comment, .bwge_comments_close_btn").on(bwge_click, function() { bwge_comment()});

		/* Open/close ecommerce.*/
        jQuery(".bwge_ecommerce, .bwge_ecommerce_close_btn").on(bwge_click, function() { bwge_ecommerce()});

        /* Open/close control buttons.*/
        jQuery(".bwge_toggle_container").on(bwge_click, function () {
          var bwge_open_toggle_btn_class = "<?php echo ($theme_row->lightbox_ctrl_btn_pos == 'top') ? 'fa-angle-up' : 'fa-angle-down'; ?>";
          var bwge_close_toggle_btn_class = "<?php echo ($theme_row->lightbox_ctrl_btn_pos == 'top') ? 'fa-angle-down' : 'fa-angle-up'; ?>";
          if (jQuery(".bwge_toggle_container i").hasClass(bwge_open_toggle_btn_class)) {
            /* Close controll buttons.*/
            <?php
              if ((!$enable_image_filmstrip || $theme_row->lightbox_filmstrip_pos != 'bottom') && $theme_row->lightbox_ctrl_btn_pos == 'bottom' && $theme_row->lightbox_info_pos == 'bottom') {
                ?>
                jQuery(".bwge_image_info").animate({bottom: 0}, 500);
                <?php
              }
              elseif ((!$enable_image_filmstrip || $theme_row->lightbox_filmstrip_pos != 'top') && $theme_row->lightbox_ctrl_btn_pos == 'top' && $theme_row->lightbox_info_pos == 'top') {
                ?>
                jQuery(".bwge_image_info").animate({top: 0}, 500);
                <?php
              }

            ?>
            jQuery(".bwge_ctrl_btn_container").animate({<?php echo $theme_row->lightbox_ctrl_btn_pos; ?>: '-' + jQuery(".bwge_ctrl_btn_container").height()}, 500);
            jQuery(".bwge_toggle_container").animate({
                <?php echo $theme_row->lightbox_ctrl_btn_pos; ?>: 0
              }, {
                duration: 500,
                complete: function () { jQuery(".bwge_toggle_container i").attr("class", "bwge_toggle_btn fa " + bwge_close_toggle_btn_class) }
              });
          }
          else {
            /* Open controll buttons.*/
            <?php
              if ((!$enable_image_filmstrip || $theme_row->lightbox_filmstrip_pos != 'bottom') && $theme_row->lightbox_ctrl_btn_pos == 'bottom' && $theme_row->lightbox_info_pos == 'bottom') {
                ?>
                jQuery(".bwge_image_info").animate({bottom: jQuery(".bwge_ctrl_btn_container").height()}, 500);
                <?php
              }
              elseif ((!$enable_image_filmstrip || $theme_row->lightbox_filmstrip_pos != 'top') && $theme_row->lightbox_ctrl_btn_pos == 'top' && $theme_row->lightbox_info_pos == 'top') {
                ?>
                jQuery(".bwge_image_info").animate({top: jQuery(".bwge_ctrl_btn_container").height()}, 500);
                <?php
              }
              if ((!$enable_image_filmstrip || $theme_row->lightbox_filmstrip_pos != 'bottom') && $theme_row->lightbox_ctrl_btn_pos == 'bottom' && $theme_row->lightbox_rate_pos == 'bottom') {
                ?>
                jQuery(".bwge_image_rate").animate({bottom: jQuery(".bwge_ctrl_btn_container").height()}, 500);
                <?php
              }
              elseif ((!$enable_image_filmstrip || $theme_row->lightbox_filmstrip_pos != 'top') && $theme_row->lightbox_ctrl_btn_pos == 'top' && $theme_row->lightbox_rate_pos == 'top') {
                ?>
                jQuery(".bwge_image_rate").animate({top: jQuery(".bwge_ctrl_btn_container").height()}, 500);
                <?php
              }
              if ((!$enable_image_filmstrip || $theme_row->lightbox_filmstrip_pos != 'bottom') && $theme_row->lightbox_ctrl_btn_pos == 'bottom' && $theme_row->lightbox_hit_pos == 'bottom') {
                ?>
                jQuery(".bwge_image_hit").animate({bottom: jQuery(".bwge_ctrl_btn_container").height()}, 500);
                <?php
              }
              elseif ((!$enable_image_filmstrip || $theme_row->lightbox_filmstrip_pos != 'top') && $theme_row->lightbox_ctrl_btn_pos == 'top' && $theme_row->lightbox_hit_pos == 'top') {
                ?>
                jQuery(".bwge_image_hit").animate({top: jQuery(".bwge_ctrl_btn_container").height()}, 500);
                <?php
              }
            ?>
            jQuery(".bwge_ctrl_btn_container").animate({<?php echo $theme_row->lightbox_ctrl_btn_pos; ?>: 0}, 500);
            jQuery(".bwge_toggle_container").animate({
                <?php echo $theme_row->lightbox_ctrl_btn_pos; ?>: jQuery(".bwge_ctrl_btn_container").height()
              }, {
                duration: 500,
                complete: function () { jQuery(".bwge_toggle_container i").attr("class", "bwge_toggle_btn fa " + bwge_open_toggle_btn_class) }
              });
          }
        });
        /* Maximize/minimize.*/
        jQuery(".bwge_resize-full").on(bwge_click, function () {
          jQuery(".bwge_watermark").css({display: 'none'});
          var comment_container_width = 0;
          if (jQuery(".bwge_comment_container").hasClass("bwge_open") || jQuery(".bwge_ecommerce_container").hasClass("bwge_open") ) {
            comment_container_width = jQuery(".bwge_comment_container").width() || jQuery(".bwge_ecommerce_container").width();
          }
          if (jQuery(".bwge_resize-full").hasClass("fa-resize-small")) {
            if (jQuery(window).width() > <?php echo $image_width; ?>) {
              bwge_popup_current_width = <?php echo $image_width; ?>;
            }
            if (jQuery(window).height() > <?php echo $image_height; ?>) {
              bwge_popup_current_height = <?php echo $image_height; ?>;
            }
            /* Minimize.*/
            jQuery("#bwge_spider_popup_wrap").animate({
              width: bwge_popup_current_width,
              height: bwge_popup_current_height,
              left: '50%',
              top: '50%',
              marginLeft: -bwge_popup_current_width / 2,
              marginTop: -bwge_popup_current_height / 2,
              zIndex: 100000
            }, 500);
            jQuery(".bwge_image_wrap").animate({width: bwge_popup_current_width - comment_container_width}, 500);
            jQuery(".bwge_image_container").animate({height: bwge_popup_current_height - <?php echo ($filmstrip_direction == 'horizontal' ? $image_filmstrip_height : 0); ?>, width: bwge_popup_current_width - comment_container_width - <?php echo ($filmstrip_direction == 'vertical' ? $image_filmstrip_width : 0); ?>}, 500);
            jQuery(".bwge_popup_image").animate({
                maxWidth: bwge_popup_current_width - comment_container_width - <?php echo ($filmstrip_direction == 'vertical' ? $image_filmstrip_width : 0); ?>,
                maxHeight: bwge_popup_current_height - <?php echo ($filmstrip_direction == 'horizontal' ? $image_filmstrip_height : 0); ?>
              }, {
                duration: 500,
                complete: function () {
                  bwge_change_watermark_container();
                  if ((jQuery("#bwge_spider_popup_wrap").width() < jQuery(window).width())) {
                    if (jQuery("#bwge_spider_popup_wrap").height() < jQuery(window).height()) {
                      jQuery(".bwge_spider_popup_close_fullscreen").attr("class", "bwge_spider_popup_close");
                    }
                  }
                }
              });
            jQuery(".bwge_popup_embed > .bwge_embed_frame > img, .bwge_popup_embed > .bwge_embed_frame > video").animate({
                maxWidth: bwge_popup_current_width - comment_container_width - <?php echo ($filmstrip_direction == 'vertical' ? $image_filmstrip_width : 0); ?>,
                maxHeight: bwge_popup_current_height - <?php echo ($filmstrip_direction == 'horizontal' ? $image_filmstrip_height : 0); ?>
              }, {
                duration: 500,
                complete: function () {
                  bwge_resize_instagram_post();
                  bwge_change_watermark_container();
                  if (jQuery("#bwge_spider_popup_wrap").width() < jQuery(window).width()) {
                    if (jQuery("#bwge_spider_popup_wrap").height() < jQuery(window).height()) {
                      jQuery(".bwge_spider_popup_close_fullscreen").attr("class", "bwge_spider_popup_close");
                    }
                  }
                }
              });
            jQuery(".bwge_filmstrip_container").animate({<?php echo $width_or_height; ?>: bwge_popup_current_<?php echo $width_or_height; ?> - <?php echo ($filmstrip_direction == 'horizontal' ? 'comment_container_width' : 0); ?>}, 500);
            jQuery(".bwge_filmstrip").animate({<?php echo $width_or_height; ?>: bwge_popup_current_<?php echo $width_or_height; ?> - <?php echo ($filmstrip_direction == 'horizontal' ? 'comment_container_width' : 0); ?> - 40}, 500);
            /* Set filmstrip initial position.*/
            bwge_set_filmstrip_pos(bwge_popup_current_<?php echo $width_or_height; ?> - 40);
            jQuery(".bwge_resize-full").attr("class", "bwge_ctrl_btn bwge_resize-full fa fa-resize-full");
            jQuery(".bwge_resize-full").attr("title", "<?php echo __('Maximize', 'bwge'); ?>");
          }
          else {
            bwge_popup_current_width = jQuery(window).width();
            bwge_popup_current_height = jQuery(window).height();
            /* Maximize.*/
            jQuery("#bwge_spider_popup_wrap").animate({
              width: jQuery(window).width(),
              height: jQuery(window).height(),
              left: 0,
              top: 0,
              margin: 0,
              zIndex: 100000
            }, 500);
            jQuery(".bwge_image_wrap").animate({width: (jQuery(window).width() - comment_container_width)}, 500);
            jQuery(".bwge_image_container").animate({height: (bwge_popup_current_height - <?php echo ($filmstrip_direction == 'horizontal' ? $image_filmstrip_height : 0); ?>), width: bwge_popup_current_width - comment_container_width - <?php echo ($filmstrip_direction == 'vertical' ? $image_filmstrip_width : 0); ?>}, 500);
            jQuery(".bwge_popup_image").animate({
                maxWidth: jQuery(window).width() - comment_container_width - <?php echo ($filmstrip_direction == 'vertical' ? $image_filmstrip_width : 0); ?>,
                maxHeight: jQuery(window).height() - <?php echo ($filmstrip_direction == 'horizontal' ? $image_filmstrip_height : 0); ?>
              }, {
                duration: 500,
                complete: function () { bwge_change_watermark_container(); }
              });
            jQuery(".bwge_popup_embed > .bwge_embed_frame > img, .bwge_popup_embed > .bwge_embed_frame > video").animate({
                maxWidth: jQuery(window).width() - comment_container_width - <?php echo ($filmstrip_direction == 'vertical' ? $image_filmstrip_width : 0); ?>,
                maxHeight: jQuery(window).height() - <?php echo ($filmstrip_direction == 'horizontal' ? $image_filmstrip_height : 0); ?>
              }, {
                duration: 500,
                complete: function () { 
                  bwge_resize_instagram_post();
                  bwge_change_watermark_container(); }
              });
            jQuery(".bwge_filmstrip_container").animate({<?php echo $width_or_height; ?>: jQuery(window).<?php echo $width_or_height; ?>() - <?php echo ($filmstrip_direction == 'horizontal' ? 'comment_container_width' : 0); ?>}, 500);
            jQuery(".bwge_filmstrip").animate({<?php echo $width_or_height; ?>: jQuery(window).<?php echo $width_or_height; ?>() - <?php echo ($filmstrip_direction == 'horizontal' ? 'comment_container_width' : 0); ?> - 40}, 500);
            /* Set filmstrip initial position.*/
            bwge_set_filmstrip_pos(jQuery(window).<?php echo $width_or_height; ?>() - <?php echo ($filmstrip_direction == 'horizontal' ? 'comment_container_width' : 0); ?> - 40);
            jQuery(".bwge_resize-full").attr("class", "bwge_ctrl_btn bwge_resize-full fa fa-resize-small");
            jQuery(".bwge_resize-full").attr("title", "<?php echo __('Restore', 'bwge'); ?>");
            jQuery(".bwge_spider_popup_close").attr("class", "bwge_ctrl_btn bwge_spider_popup_close_fullscreen");
          }
        });
        /* Fullscreen.*/
        /*Toggle with mouse click*/
        jQuery(".bwge_fullscreen").on(bwge_click, function () {
          jQuery(".bwge_watermark").css({display: 'none'});
          var comment_container_width = 0;
          if (jQuery(".bwge_comment_container").hasClass("bwge_open") || jQuery(".bwge_ecommerce_container").hasClass("bwge_open")) {
            comment_container_width = jQuery(".bwge_comment_container").width() || jQuery(".bwge_ecommerce_container").width();
          }
          function bwge_exit_fullscreen() {
            if (jQuery(window).width() > <?php echo $image_width; ?>) {
              bwge_popup_current_width = <?php echo $image_width; ?>;
            }
            if (jQuery(window).height() > <?php echo $image_height; ?>) {
              bwge_popup_current_height = <?php echo $image_height; ?>;
            }
            <?php
            /* "Full width lightbox" sets yes.*/
            if ($open_with_fullscreen) {
              ?>
            bwge_popup_current_width = jQuery(window).width();
            bwge_popup_current_height = jQuery(window).height();
              <?php
            }
            ?>
            jQuery("#bwge_spider_popup_wrap").on("fscreenclose", function() {
              jQuery("#bwge_spider_popup_wrap").css({
                width: bwge_popup_current_width,
                height: bwge_popup_current_height,
                left: '50%',
                top: '50%',
                marginLeft: -bwge_popup_current_width / 2,
                marginTop: -bwge_popup_current_height / 2,
                zIndex: 100000
              });
              jQuery(".bwge_image_wrap").css({width: bwge_popup_current_width - comment_container_width});
              jQuery(".bwge_image_container").css({height: bwge_popup_current_height - <?php echo ($filmstrip_direction == 'horizontal' ? $image_filmstrip_height : 0); ?>, width: bwge_popup_current_width - comment_container_width - <?php echo ($filmstrip_direction == 'vertical' ? $image_filmstrip_width : 0); ?>});
              /*jQuery(".bwge_slide_bg").css({height: bwge_popup_current_height - <?php echo $image_filmstrip_height; ?>});
              jQuery(".bwge_popup_image_spun1").css({height: bwge_popup_current_height - <?php echo $image_filmstrip_height; ?>});*/
              jQuery(".bwge_popup_image").css({
                maxWidth: bwge_popup_current_width - comment_container_width - <?php echo ($filmstrip_direction == 'vertical' ? $image_filmstrip_width : 0); ?>,
                maxHeight: bwge_popup_current_height - <?php echo ($filmstrip_direction == 'horizontal' ? $image_filmstrip_height : 0); ?>
              });
              jQuery(".bwge_popup_embed > .bwge_embed_frame > img, .bwge_popup_embed > .bwge_embed_frame > video").css({
                maxWidth: bwge_popup_current_width - comment_container_width - <?php echo ($filmstrip_direction == 'vertical' ? $image_filmstrip_width : 0); ?>,
                maxHeight: bwge_popup_current_height - <?php echo ($filmstrip_direction == 'horizontal' ? $image_filmstrip_height : 0); ?>
              });
              bwge_resize_instagram_post();
              /* Set watermark container size.*/
              bwge_change_watermark_container();
              jQuery(".bwge_filmstrip_container").css({<?php echo $width_or_height; ?>: bwge_popup_current_<?php echo $width_or_height; ?> - <?php echo ($filmstrip_direction == 'horizontal' ? 'comment_container_width' : 0); ?>});
              jQuery(".bwge_filmstrip").css({<?php echo $width_or_height; ?>: bwge_popup_current_<?php echo $width_or_height; ?> - <?php echo ($filmstrip_direction == 'horizontal' ? 'comment_container_width' : 0); ?>- 40});
              /* Set filmstrip initial position.*/
              bwge_set_filmstrip_pos(bwge_popup_current_<?php echo $width_or_height; ?> - 40);
              jQuery(".bwge_resize-full").show();
              jQuery(".bwge_resize-full").attr("class", "bwge_ctrl_btn bwge_resize-full fa fa-resize-full");
              jQuery(".bwge_resize-full").attr("title", "<?php echo __('Maximize', 'bwge'); ?>");
              jQuery(".bwge_fullscreen").attr("class", "bwge_ctrl_btn bwge_fullscreen fa fa-fullscreen");
              jQuery(".bwge_fullscreen").attr("title", "<?php echo __('Fullscreen', 'bwge'); ?>");
              if (jQuery("#bwge_spider_popup_wrap").width() < jQuery(window).width()) {
                if (jQuery("#bwge_spider_popup_wrap").height() < jQuery(window).height()) {
                  jQuery(".bwge_spider_popup_close_fullscreen").attr("class", "bwge_spider_popup_close");
                }
              }
            });
          }
          if (typeof jQuery().fullscreen !== 'undefined') {
            if (jQuery.isFunction(jQuery().fullscreen)) {
              if (jQuery.fullscreen.isFullScreen()) {
                /* Exit Fullscreen.*/
                jQuery.fullscreen.exit();
                bwge_exit_fullscreen();
              }
              else {
                /* Fullscreen.*/
                jQuery("#bwge_spider_popup_wrap").fullscreen();
                /*jQuery("#bwge_spider_popup_wrap").on("fscreenopen", function() {
                if (jQuery.fullscreen.isFullScreen()) {*/
                  var screen_width = screen.width;
                  var screen_height = screen.height;
                  jQuery("#bwge_spider_popup_wrap").css({
                    width: screen_width,
                    height: screen_height,
                    left: 0,
                    top: 0,
                    margin: 0,
                    zIndex: 100000
                  });
                  jQuery(".bwge_image_wrap").css({width: screen_width - comment_container_width});
                  jQuery(".bwge_image_container").css({height: (screen_height - <?php echo ($filmstrip_direction == 'horizontal' ? $image_filmstrip_height : 0); ?>), width: screen_width - comment_container_width - <?php echo ($filmstrip_direction == 'vertical' ? $image_filmstrip_width : 0); ?>});
                  /* jQuery(".bwge_slide_bg").css({height: screen_height - <?php echo $image_filmstrip_height; ?>});*/
                  jQuery(".bwge_popup_image").css({
                    maxWidth: (screen_width - comment_container_width - <?php echo ($filmstrip_direction == 'vertical' ? $image_filmstrip_width : 0); ?>),
                    maxHeight: (screen_height - <?php echo ($filmstrip_direction == 'horizontal' ? $image_filmstrip_height : 0); ?>)
                  });
                  
                  jQuery(".bwge_popup_embed > .bwge_embed_frame > img, .bwge_popup_embed > .bwge_embed_frame > video").css({
                    maxWidth: (screen_width - comment_container_width - <?php echo ($filmstrip_direction == 'vertical' ? $image_filmstrip_width : 0); ?>),
                    maxHeight: (screen_height - <?php echo ($filmstrip_direction == 'horizontal' ? $image_filmstrip_height : 0); ?>)
                  });
                  
                  bwge_resize_instagram_post();
                  
                  /* Set watermark container size.*/
                  bwge_change_watermark_container();
                  jQuery(".bwge_filmstrip_container").css({<?php echo $width_or_height; ?>: (screen_<?php echo $width_or_height; ?> - <?php echo ($filmstrip_direction == 'horizontal' ? 'comment_container_width' : 0); ?>)});
                  jQuery(".bwge_filmstrip").css({<?php echo $width_or_height; ?>: (screen_<?php echo $width_or_height; ?> - <?php echo ($filmstrip_direction == 'horizontal' ? 'comment_container_width' : 0); ?> - 40)});
                  /* Set filmstrip initial position.*/
                  bwge_set_filmstrip_pos(screen_<?php echo $width_or_height; ?> - <?php echo ($filmstrip_direction == 'horizontal' ? 'comment_container_width' : 0); ?> - 40);
                  jQuery(".bwge_resize-full").hide();
                  jQuery(".bwge_fullscreen").attr("class", "bwge_ctrl_btn bwge_fullscreen fa fa-resize-small");
                  jQuery(".bwge_fullscreen").attr("title", "<?php echo __('Exit Fullscreen', 'bwge'); ?>");
                  jQuery(".bwge_spider_popup_close").attr("class", "bwge_ctrl_btn bwge_spider_popup_close_fullscreen");
                /*});
                }*/
              }
            }
          }
          return false;
        });
        /* Play/pause.*/
        jQuery(".bwge_play_pause, .bwge_popup_image").on(bwge_click, function () {
          if (jQuery(".bwge_play_pause") && jQuery(".bwge_play_pause").hasClass("fa-play")) {
            /* PLay.*/
            bwge_play();
            jQuery(".bwge_play_pause").attr("title", "<?php echo __('Pause', 'bwge'); ?>");
            jQuery(".bwge_play_pause").attr("class", "bwge_ctrl_btn bwge_play_pause fa fa-pause");
          }
          else {
            /* Pause.*/
            window.clearInterval(bwge_playInterval);
            jQuery(".bwge_play_pause").attr("title", "<?php echo __('Play', 'bwge'); ?>");
            jQuery(".bwge_play_pause").attr("class", "bwge_ctrl_btn bwge_play_pause fa fa-play");
          }
        });
        /* Open with autoplay.*/
        <?php
        if ($open_with_autoplay) {
          ?>
          bwge_play();
          jQuery(".bwge_play_pause").attr("title", "<?php echo __('Pause', 'bwge'); ?>");
          jQuery(".bwge_play_pause").attr("class", "bwge_ctrl_btn bwge_play_pause fa fa-pause");
          <?php
        }
        ?>
        /* Open with fullscreen.*/
        <?php
        if ($open_with_fullscreen) {
          ?>
          bwge_open_with_fullscreen();
          <?php
        }
        ?>
        <?php
        if ($option_row->preload_images) {
          echo "bwge_preload_images(parseInt(jQuery('#bwge_current_image_key').val()));";
        }
        ?>
        jQuery(".bwge_popup_image").removeAttr("width");
        jQuery(".bwge_popup_image").removeAttr("height");
      });
      /* Open with fullscreen.*/
      function bwge_open_with_fullscreen() {
        jQuery(".bwge_watermark").css({display: 'none'});
        var comment_container_width = 0;
        if (jQuery(".bwge_comment_container").hasClass("bwge_open") || jQuery(".bwge_ecommerce_container").hasClass("bwge_open")) {
          comment_container_width = jQuery(".bwge_comment_container").width() || jQuery(".bwge_ecommerce_container").width();
        }
        bwge_popup_current_width = jQuery(window).width();
        bwge_popup_current_height = jQuery(window).height();
        jQuery("#bwge_spider_popup_wrap").css({
          width: jQuery(window).width(),
          height: jQuery(window).height(),
          left: 0,
          top: 0,
          margin: 0,
          zIndex: 100000
        });
        jQuery(".bwge_image_wrap").css({width: (jQuery(window).width() - comment_container_width)});
        jQuery(".bwge_image_container").css({height: (bwge_popup_current_height - <?php echo ($filmstrip_direction == 'horizontal' ? $image_filmstrip_height : 0); ?>), width: bwge_popup_current_width - comment_container_width - <?php echo ($filmstrip_direction == 'vertical' ? $image_filmstrip_width : 0); ?>});
        jQuery(".bwge_popup_image").css({
         maxWidth: jQuery(window).width() - comment_container_width - <?php echo ($filmstrip_direction == 'vertical' ? $image_filmstrip_width : 0); ?>,
         maxHeight: jQuery(window).height() - <?php echo ($filmstrip_direction == 'horizontal' ? $image_filmstrip_height : 0); ?>
        },  {
          complete: function () { bwge_change_watermark_container(); }
         });
        jQuery(".bwge_popup_video").css({
         width: jQuery(window).width() - comment_container_width - <?php echo ($filmstrip_direction == 'vertical' ? $image_filmstrip_width : 0); ?>,
         height: jQuery(window).height() - <?php echo ($filmstrip_direction == 'horizontal' ? $image_filmstrip_height : 0); ?>
        },  {
          complete: function () { bwge_change_watermark_container(); }
         });
        jQuery(".bwge_popup_embed > .bwge_embed_frame > img, .bwge_popup_embed > .bwge_embed_frame > video").css({
         maxWidth: jQuery(window).width() - comment_container_width - <?php echo ($filmstrip_direction == 'vertical' ? $image_filmstrip_width : 0); ?>,
         maxHeight: jQuery(window).height() - <?php echo ($filmstrip_direction == 'horizontal' ? $image_filmstrip_height : 0); ?>
        },  {
          complete: function () { 
            bwge_resize_instagram_post();
            bwge_change_watermark_container(); }
         });
        jQuery(".bwge_filmstrip_container").css({<?php echo $width_or_height; ?>: jQuery(window).<?php echo $width_or_height; ?>() - <?php echo ($filmstrip_direction == 'horizontal' ? 'comment_container_width' : 0); ?>});
        jQuery(".bwge_filmstrip").css({<?php echo $width_or_height; ?>: jQuery(window).<?php echo $width_or_height; ?>() - <?php echo ($filmstrip_direction == 'horizontal' ? 'comment_container_width' : 0); ?> - 40});        
        /* Set filmstrip initial position.*/
        bwge_set_filmstrip_pos(jQuery(window).<?php echo $width_or_height; ?>() - <?php echo ($filmstrip_direction == 'horizontal' ? 'comment_container_width' : 0); ?> - 40);

        jQuery(".bwge_resize-full").attr("class", "bwge_ctrl_btn bwge_resize-full fa fa-resize-small");
        jQuery(".bwge_resize-full").attr("title", "<?php echo __('Restore', 'bwge'); ?>");
        jQuery(".bwge_spider_popup_close").attr("class", "bwge_ctrl_btn bwge_spider_popup_close_fullscreen");         
      }

      function bwge_resize_instagram_post(){
        if (jQuery('.inner_instagram_iframe_bwge_embed_frame').length) {
          var cont_span = jQuery('.inner_instagram_iframe_bwge_embed_frame').parent().parent().parent().parent().parent();
          /*for not screw up not instagram cont bwge_embed_frame div*/
          
          if(cont_span.css('z-index') == '1')
            return;
          jQuery('.bwge_embed_frame').css({'width':'inherit', 'height':'inherit', 'vertical-align':'middle', 'display':'table-cell'});
          
          var w = jQuery(".bwge_image_container").width();
          var h = jQuery(".bwge_image_container").height();
          var post_width = 0;
          var post_height = 0;

          if(h <w +88 ){
            post_height = h;
            post_width = h -88; 
          }
          else{
            post_width = w;
            post_height = w +88 ;  
          }
          
          jQuery('.inner_instagram_iframe_bwge_embed_frame').each(function(){
          
          post_height = post_height;
          post_width = post_width;
          var top_pos = (0.5 *( h-post_height));
            jQuery(this).parent().css({
              height: post_height,
              width: post_width,
              top:  top_pos
            });
          });


          bwge_change_watermark_container();
        }
      }

      function bwge_play() {
        window.clearInterval(bwge_playInterval);
        bwge_playInterval = setInterval(function () {
          if (!data[parseInt(jQuery('#bwge_current_image_key').val()) + 1]) {
            if (<?php echo $option_row->enable_loop; ?> == 1) {
              /* Wrap around.*/
              bwge_change_image(parseInt(jQuery('#bwge_current_image_key').val()), 0, data);
            }
            return;
          }
          bwge_change_image(parseInt(jQuery('#bwge_current_image_key').val()), parseInt(jQuery('#bwge_current_image_key').val()) + 1, data)
        }, '<?php echo $slideshow_interval * 1000; ?>');
      }
      jQuery(window).focus(function() {
        /* event_stack = [];*/
          if (jQuery(".bwge_play_pause") && !jQuery(".bwge_play_pause").hasClass("fa-play")) {
          bwge_play();
        }
        /*var i = 0;
        jQuery(".bwge_slider").children("span").each(function () {
          if (jQuery(this).css('opacity') == 1) {
            jQuery("#bwge_current_image_key").val(i);
          }
          i++;
        });*/
      });
      jQuery(window).blur(function() {
        event_stack = [];
        window.clearInterval(bwge_playInterval);
      });
      var lightbox_ctrl_btn_pos = "<?php echo $theme_row->lightbox_ctrl_btn_pos ;?>";  
	  if(<?php echo $open_ecommerce;?> == 1){    
		setTimeout(function(){ bwge_ecommerce();  }, 400);
	  }
    </script>
    <?php
    die();
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