<?php

class BWGEViewAlbum_compact_preview {
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
  public function display($params, $from_shortcode = 0, $bwge = 0) {
    global $wp;

    $current_url = (is_ssl() ? 'https://' : 'http://') . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
    global $WD_BWGE_UPLOAD_DIR;
    require_once(WD_BWGE_DIR . '/framework/BWGELibrary.php');
    
    if (!isset($params['ecommerce_icon'])) {
      $params['ecommerce_icon'] = 'none';
    }
    if (!isset($params['popup_enable_ecommerce'])) {
      $params['popup_enable_ecommerce'] = 0;
    }
  
    if (!isset($params['compuct_album_image_title'])) {
      $params['compuct_album_image_title'] = 'none';
    }
    $album_view_type = 'thumbnail';
    if (!isset($params['compuct_album_mosaic_hor_ver'])) {
      $params['compuct_album_mosaic_hor_ver'] = 'vertical';
    }
    if (!isset($params['compuct_album_resizable_mosaic'])) {
      $params['compuct_album_resizable_mosaic'] = 0;
    }
    if (!isset($params['compuct_album_mosaic_total_width'])) {
      $params['compuct_album_mosaic_total_width'] = 100;
    }
    if (!isset($params['popup_fullscreen'])) {
      $params['popup_fullscreen'] = 0;
    }
    if (!isset($params['popup_autoplay'])) {
      $params['popup_autoplay'] = 0;
    }
    if (!isset($params['popup_enable_pinterest'])) {
      $params['popup_enable_pinterest'] = 0;
    }
    if (!isset($params['popup_enable_tumblr'])) {
      $params['popup_enable_tumblr'] = 0;
    }
    if (!isset($params['show_search_box'])) {
      $params['show_search_box'] = 0;
    }
    if (!isset($params['search_box_width'])) {
      $params['search_box_width'] = 180;
    }
    if (!isset($params['popup_enable_info'])) {
      $params['popup_enable_info'] = 1;
    }
    if (!isset($params['popup_info_always_show'])) {
      $params['popup_info_always_show'] = 0;
    }
    if (!isset($params['popup_info_full_width'])) {
      $params['popup_info_full_width'] = 0;
    }
    if (!isset($params['popup_enable_rate'])) {
      $params['popup_enable_rate'] = 0;
    }
    if (!isset($params['thumb_click_action']) || $params['thumb_click_action'] == 'undefined') {
      $params['thumb_click_action'] = 'open_lightbox';
    }
    if (!isset($params['thumb_link_target'])) {
      $params['thumb_link_target'] = 1;
    }
    if (!isset($params['popup_hit_counter'])) {
      $params['popup_hit_counter'] = 0;
    }
    if (!isset($params['order_by'])) {
      $params['order_by'] = ' ASC ';
    }
    if (!isset($params['show_sort_images'])) {
      $params['show_sort_images'] = 0;
    }
    if (!isset($params['compuct_album_enable_page'])) {
      $params['compuct_album_enable_page'] = 1;
    }
    if (!isset($params['show_tag_box'])) {
      $params['show_tag_box'] = 0;
    }
    $options_row = $this->model->get_options_row_data();
    $placeholder = isset($options_row->placeholder) ? $options_row->placeholder : '';
    if (!isset($params['show_album_name'])) {
      $params['show_album_name'] = $options_row->show_album_name;
    }
    $from = (isset($params['from']) ? esc_html($params['from']) : 0);
    $type = (isset($_REQUEST['bwge_type' . $bwge]) ? esc_html($_REQUEST['bwge_type' . $bwge]) : (isset($params['type']) ? $params['type'] : 'album'));
    $bwge_search = ((isset($_POST['bwge_search_' . $bwge]) && esc_html($_POST['bwge_search_' . $bwge]) != '') ? esc_html($_POST['bwge_search_' . $bwge]) : '');
    $sort_direction = ' ' . $params['order_by'] . ' ';
    
    $play_icon = $options_row->play_icon;
    if ($from === "widget") {
      $params['album_id'] = $params['id'];
      $params['sort_by'] = $params['show'] == 'random' ? 'RAND()' : 'order';
      if ($params['show'] == 'last') {
        $sort_direction = ' DESC ';
      }
      $params['compuct_albums_per_page'] = $params['count'];
      $params['compuct_album_column_number'] = $options_row->album_column_number;
      $params['compuct_album_image_column_number'] = $options_row->image_column_number;
      $params['compuct_album_thumb_width'] = $params['width'];
      $params['compuct_album_thumb_height'] = $params['height'];
      $params['compuct_album_image_thumb_width'] = $params['width'];
      $params['compuct_album_image_thumb_height'] = $params['height'];
      $params['compuct_album_title'] = $options_row->album_title_show_hover;
      $params['compuct_album_enable_page'] = 0;  
      $params['compuct_album_image_title'] = $options_row->image_title_show_hover;
      $params['popup_width'] = $options_row->popup_width;
      $params['popup_height'] = $options_row->popup_height;
      $params['popup_effect'] = $options_row->popup_type;
      $params['popup_enable_filmstrip'] = $options_row->popup_enable_filmstrip;
      $params['popup_filmstrip_height'] = $options_row->popup_filmstrip_height;
      $params['popup_enable_ctrl_btn'] = $options_row->popup_enable_ctrl_btn;
      $params['popup_enable_fullscreen'] = $options_row->popup_enable_fullscreen;
      $params['popup_enable_info'] = $options_row->popup_enable_info;
      $params['popup_info_always_show'] = $options_row->popup_info_always_show;
      $params['popup_info_full_width'] = $options_row->popup_info_full_width;
      $params['popup_hit_counter'] = $options_row->popup_hit_counter;
      $params['popup_enable_rate'] = $options_row->popup_enable_rate;
      $params['popup_interval'] = $options_row->popup_interval;
      $params['popup_enable_comment'] = $options_row->popup_enable_comment;
      $params['popup_enable_facebook'] = $options_row->popup_enable_facebook;
      $params['popup_enable_twitter'] = $options_row->popup_enable_twitter;
      $params['popup_enable_google'] = $options_row->popup_enable_google;
      $params['popup_enable_ecommerce'] = $options_row->popup_enable_ecommerce;
      $params['popup_enable_pinterest'] = $options_row->popup_enable_pinterest;
      $params['popup_enable_tumblr'] = $options_row->popup_enable_tumblr;
      $params['watermark_type'] = $options_row->watermark_type;
      $params['watermark_link'] = urlencode($options_row->watermark_link);
      $params['watermark_opacity'] = $options_row->watermark_opacity;
      $params['watermark_position'] = $options_row->watermark_position;
      $params['watermark_text'] = $options_row->watermark_text;
      $params['watermark_font_size'] = $options_row->watermark_font_size;
      $params['watermark_font'] = $options_row->watermark_font;
      $params['watermark_color'] = $options_row->watermark_color;
      $params['watermark_url'] = urlencode($options_row->watermark_url);
      $params['watermark_width'] = $options_row->watermark_width;
      $params['watermark_height'] = $options_row->watermark_height;
      $params['ecommerce_icon'] = $options_row->ecommerce_icon_show_hover;
    }

    $theme_row = $this->model->get_theme_row_data($params['theme_id']);
    if (!$theme_row) {
      echo BWGELibrary::message(__('There is no theme selected or the theme was deleted.', 'bwge'), 'error');
      return;
    }
    $album_gallery_id = (isset($_REQUEST['bwge_album_gallery_id_' . $bwge]) ? esc_html($_REQUEST['bwge_album_gallery_id_' . $bwge]) : $params['album_id']);
    $album_row_data = $this->model->get_album_row_data($album_gallery_id);

    if (!$album_gallery_id || ($type == 'album' && !$album_row_data)) {
      echo BWGELibrary::message(__('There is no album selected or the album was deleted.', 'bwge'), 'error');
      return;
    }
    if ($type == 'gallery') {
      $items_per_page = $params['compuct_album_images_per_page'];
      $items_per_page_arr = array('images_per_page' => $params['compuct_album_images_per_page'], 'load_more_image_count' => $params['compuct_album_images_per_page']);
      $items_col_num = $params['compuct_album_image_column_number'];
      if (isset($_POST['bwge_sortImagesByValue_' . $bwge])) {
        $sort_by = esc_html($_POST['bwge_sortImagesByValue_' . $bwge]);
        if ($sort_by == 'random') {
          $params['sort_by'] = 'RAND()';
        }
        else if ($sort_by == 'default')  {
          $params['sort_by'] = $params['sort_by'];
        }
        else {
          $params['sort_by'] = $sort_by;
        }
      }
      $image_rows = $this->model->get_image_rows_data($album_gallery_id, $items_per_page, $params['sort_by'], $bwge, $sort_direction);
      $images_count = count($image_rows);
      if (!$image_rows) {
        echo BWGELibrary::message(__('There are no images in this gallery.', 'bwge'), 'error');
      }
      $page_nav = $this->model->gallery_page_nav($album_gallery_id, $bwge);
      $album_gallery_div_id = 'bwge_album_compact_' . $bwge;
      $album_gallery_div_class = 'bwge_standart_thumbnails_' . $bwge;
    }
    else {
      $items_per_page = $params['compuct_albums_per_page'];
      $items_per_page_arr = array('images_per_page' => $params['compuct_albums_per_page'], 'load_more_image_count' => $params['compuct_albums_per_page']);
      $items_col_num = $params['compuct_album_column_number'];
      $album_galleries_row = $this->model->get_alb_gals_row($album_gallery_id, $items_per_page, 'order', $bwge, ' ASC ');
      if (!$album_galleries_row) {
        echo BWGELibrary::message(__('There is no album selected or the album was deleted.', 'bwge'), 'error');
        return;
      }
      $page_nav = $this->model->album_page_nav($album_gallery_id, $bwge);
      $album_gallery_div_id = 'bwge_album_compact_' . $bwge;
      $album_gallery_div_class = 'bwge_album_thumbnails_' . $bwge;
    }
	
    if ($type == 'gallery' ) {

        $form_child_div_style = 'background-color:rgba(0, 0, 0, 0); position:relative; text-align:' . $theme_row->thumb_align . '; width:100%;';
      $form_child_div_id = '';
      
    }
    else {
      $form_child_div_id = '';
      $form_child_div_style = 'background-color:rgba(0, 0, 0, 0); position:relative; text-align:' . $theme_row->album_compact_thumb_align . '; width:100%;';
    }

    $bwge_previous_album_id = (isset($_REQUEST['bwge_previous_album_id_' . $bwge]) ? esc_html($_REQUEST['bwge_previous_album_id_' . $bwge]) : $params['album_id']);
    $bwge_previous_album_page_number = (isset($_REQUEST['bwge_previous_album_bwge_page_number_' . $bwge]) ? esc_html($_REQUEST['bwge_previous_album_bwge_page_number_' . $bwge]) : 0);

    $rgb_page_nav_font_color = BWGELibrary::bwge_spider_hex2rgb($theme_row->page_nav_font_color);
    $rgb_album_compact_thumbs_bg_color = BWGELibrary::bwge_spider_hex2rgb($theme_row->album_compact_thumbs_bg_color);
    $rgb_thumbs_bg_color = BWGELibrary::bwge_spider_hex2rgb($theme_row->thumbs_bg_color);

    $params_array = array(
      'action' => 'GalleryBox_bwge',
      'current_view' => $bwge,
      'theme_id' => $params['theme_id'],
      'thumb_width' => $params['compuct_album_image_thumb_width'],
      'thumb_height' => $params['compuct_album_image_thumb_height'],
      'open_with_fullscreen' => $params['popup_fullscreen'],
      'open_with_autoplay' => $params['popup_autoplay'],
      'image_width' => $params['popup_width'],
      'image_height' => $params['popup_height'],
      'image_effect' => $params['popup_effect'],
      'wd_sor' => $params['sort_by'],
      'wd_ord' => $params['order_by'],
      'enable_image_filmstrip' => $params['popup_enable_filmstrip'],
      'image_filmstrip_height' => $params['popup_filmstrip_height'],
      'enable_image_ctrl_btn' => $params['popup_enable_ctrl_btn'],
      'enable_image_fullscreen' => $params['popup_enable_fullscreen'],
      'popup_enable_info' => $params['popup_enable_info'],
      'popup_info_always_show' => $params['popup_info_always_show'],
      'popup_info_full_width' => $params['popup_info_full_width'],
      'popup_hit_counter' => $params['popup_hit_counter'],
      'popup_enable_rate' => $params['popup_enable_rate'],
      'slideshow_interval' => $params['popup_interval'],
      'enable_comment_social' => $params['popup_enable_comment'],
      'enable_image_facebook' => $params['popup_enable_facebook'],
      'enable_image_twitter' => $params['popup_enable_twitter'],
      'enable_image_google' => $params['popup_enable_google'],
      'enable_image_ecommerce' => $params['popup_enable_ecommerce'],
      'enable_image_pinterest' => $params['popup_enable_pinterest'],
      'enable_image_tumblr' => $params['popup_enable_tumblr'],
      'watermark_type' => $params['watermark_type'],
      'current_url' => $current_url
    );
    if ($params['watermark_type'] != 'none') {
      $params_array['watermark_link'] = urlencode($params['watermark_link']);
      $params_array['watermark_opacity'] = $params['watermark_opacity'];
      $params_array['watermark_position'] = $params['watermark_position'];
    }
    if ($params['watermark_type'] == 'text') {
      $params_array['watermark_text'] = $params['watermark_text'];
      $params_array['watermark_font_size'] = $params['watermark_font_size'];
      $params_array['watermark_font'] = $params['watermark_font'];
      $params_array['watermark_color'] = $params['watermark_color'];
    }
    elseif ($params['watermark_type'] == 'image') {
      $params_array['watermark_url'] = urlencode($params['watermark_url']);
      $params_array['watermark_width'] = $params['watermark_width'];
      $params_array['watermark_height'] = $params['watermark_height'];
    }
    $params_array_hash = $params_array;
    $tags_rows = $this->model->get_tags_rows_data($album_gallery_id);
    ?>	
    <style>
      /* Style for masonry view.*/
      #bwge_container1_<?php echo $bwge; ?> #bwge_container2_<?php echo $bwge; ?> .bwge_masonry_thumbnails_<?php echo $bwge; ?> * {
        -moz-box-sizing: border-box;
        -webkit-box-sizing: border-box;
        box-sizing: border-box;
      }
      #bwge_container1_<?php echo $bwge; ?> #bwge_container2_<?php echo $bwge; ?> .bwge_masonry_thumb_<?php echo $bwge; ?> {
        visibility: hidden;
        text-align: center;
        display: inline-block;
        vertical-align: middle;     
        width: <?php echo $params['compuct_album_image_thumb_width']; ?>px !important;
        border-radius: <?php echo $theme_row->masonry_thumb_border_radius; ?>;
        border: <?php echo $theme_row->masonry_thumb_border_width; ?>px <?php echo $theme_row->masonry_thumb_border_style; ?> #<?php echo $theme_row->masonry_thumb_border_color; ?>;
        background-color: #<?php echo $theme_row->thumb_bg_color; ?>;
        margin: 0;
        padding: <?php echo $theme_row->masonry_thumb_padding; ?>px !important;
        opacity: <?php echo number_format($theme_row->masonry_thumb_transparent / 100, 2, ".", ""); ?>;
        filter: Alpha(opacity=<?php echo $theme_row->masonry_thumb_transparent; ?>);
        <?php echo ($theme_row->masonry_thumb_transition) ? 'transition: all 0.3s ease 0s;-webkit-transition: all 0.3s ease 0s;' : ''; ?>
        z-index: 100;
      }
      #bwge_container1_<?php echo $bwge; ?> #bwge_container2_<?php echo $bwge; ?> .bwge_masonry_thumb_<?php echo $bwge; ?>:hover {
        opacity: 1;
        filter: Alpha(opacity=100);
        transform: <?php echo $theme_row->masonry_thumb_hover_effect; ?>(<?php echo $theme_row->masonry_thumb_hover_effect_value; ?>);
        -ms-transform: <?php echo $theme_row->masonry_thumb_hover_effect; ?>(<?php echo $theme_row->masonry_thumb_hover_effect_value; ?>);
        -webkit-transform: <?php echo $theme_row->masonry_thumb_hover_effect; ?>(<?php echo $theme_row->masonry_thumb_hover_effect_value; ?>);
        backface-visibility: hidden;
        -webkit-backface-visibility: hidden;
        -moz-backface-visibility: hidden;
        -ms-backface-visibility: hidden;
        z-index: 102;
        position: absolute;
      }
      #bwge_container1_<?php echo $bwge; ?> #bwge_container2_<?php echo $bwge; ?> .bwge_masonry_thumbnails_<?php echo $bwge; ?> {
        -moz-box-sizing: border-box;
        background-color: rgba(<?php echo $rgb_thumbs_bg_color['red']; ?>, <?php echo $rgb_thumbs_bg_color['green']; ?>, <?php echo $rgb_thumbs_bg_color['blue']; ?>, <?php echo number_format($theme_row->masonry_thumb_bg_transparent / 100, 2, ".", ""); ?>);
        box-sizing: border-box;
        display: inline-block;
        font-size: 0;
        /*width: <?php echo $params['compuct_album_image_column_number'] * ($params['compuct_album_image_thumb_width'] + 2 * ($theme_row->masonry_thumb_padding + $theme_row->masonry_thumb_border_width)); ?>px;*/
        width: 100%;
        position: relative;
        text-align: <?php echo $theme_row->masonry_thumb_align; ?>;
      }
      @media only screen and (max-width : <?php echo $params['compuct_album_image_column_number'] * ($params['compuct_album_image_thumb_width'] + 2 * ($theme_row->masonry_thumb_padding + $theme_row->masonry_thumb_border_width)); ?>px) {
        #bwge_container1_<?php echo $bwge; ?> #bwge_container2_<?php echo $bwge; ?> .bwge_masonry_thumbnails_<?php echo $bwge; ?> {
          width: inherit;
        }
      }	  	  
      #bwge_container1_<?php echo $bwge; ?> #bwge_container2_<?php echo $bwge; ?> #bwge_spider_popup_overlay_<?php echo $bwge; ?> {
        background-color: #<?php echo $theme_row->lightbox_overlay_bg_color; ?>;
        opacity: <?php echo number_format($theme_row->lightbox_overlay_bg_transparent / 100, 2, ".", ""); ?>;
        filter: Alpha(opacity=<?php echo $theme_row->lightbox_overlay_bg_transparent; ?>);
      }
      #bwge_container1_<?php echo $bwge; ?> #bwge_container2_<?php echo $bwge; ?> .bwge_masonry_thumb_spun_<?php echo $bwge; ?> {
        position: absolute;
      }
      /* Style for thumbnail view.*/
      #bwge_container1_<?php echo $bwge; ?> #bwge_container2_<?php echo $bwge; ?> .bwge_album_thumb_<?php echo $bwge; ?> {
        display: inline-block;
        text-align: center;
      }
      #bwge_container1_<?php echo $bwge; ?> #bwge_container2_<?php echo $bwge; ?> .<?php echo $album_gallery_div_class; ?> * {
        -moz-box-sizing: content-box;
        box-sizing: content-box;
      }
      #bwge_container1_<?php echo $bwge; ?> #bwge_container2_<?php echo $bwge; ?> .bwge_album_thumb_spun1_<?php echo $bwge; ?> {
        background-color: #<?php echo $theme_row->album_compact_thumb_bg_color; ?>;
        display: inline-block;
        height: <?php echo $params['compuct_album_thumb_height']; ?>px;
        margin: <?php echo $theme_row->album_compact_thumb_margin; ?>px;
        opacity: <?php echo number_format($theme_row->album_compact_thumb_transparent / 100, 2, ".", ""); ?>;
        filter: Alpha(opacity=<?php echo $theme_row->album_compact_thumb_transparent; ?>);
        <?php echo ($theme_row->album_compact_thumb_transition) ? 'transition: all 0.3s ease 0s;-webkit-transition: all 0.3s ease 0s;' : ''; ?>
        padding: <?php echo $theme_row->album_compact_thumb_padding; ?>px;
        text-align: center;
        vertical-align: middle;
        width: <?php echo $params['compuct_album_thumb_width']; ?>px;
        z-index: 100;
        -webkit-backface-visibility: visible;
        -ms-backface-visibility: visible;
      }
      #bwge_container1_<?php echo $bwge; ?> #bwge_container2_<?php echo $bwge; ?> .bwge_album_thumb_spun1_<?php echo $bwge; ?>:hover {
        opacity: 1;
        filter: Alpha(opacity=100);
        transform: <?php echo $theme_row->album_compact_thumb_hover_effect; ?>(<?php echo $theme_row->album_compact_thumb_hover_effect_value; ?>);
        -ms-transform: <?php echo $theme_row->album_compact_thumb_hover_effect; ?>(<?php echo $theme_row->album_compact_thumb_hover_effect_value; ?>);
        -webkit-transform: <?php echo $theme_row->album_compact_thumb_hover_effect; ?>(<?php echo $theme_row->album_compact_thumb_hover_effect_value; ?>);
        backface-visibility: hidden;
        -webkit-backface-visibility: hidden;
        -moz-backface-visibility: hidden;
        -ms-backface-visibility: hidden;
        z-index: 102;
      }
      #bwge_container1_<?php echo $bwge; ?> #bwge_container2_<?php echo $bwge; ?> .bwge_album_thumb_spun2_<?php echo $bwge; ?> {
        border-radius: <?php echo $theme_row->album_compact_thumb_border_radius; ?>;
        border: <?php echo $theme_row->album_compact_thumb_border_width; ?>px <?php echo $theme_row->album_compact_thumb_border_style; ?> #<?php echo $theme_row->album_compact_thumb_border_color; ?>;
        box-shadow: <?php echo $theme_row->album_compact_thumb_box_shadow; ?>;
        display: inline-block;
        height: <?php echo $params['compuct_album_thumb_height']; ?>px;
        overflow: hidden;
        width: <?php echo $params['compuct_album_thumb_width']; ?>px;
      }
      #bwge_container1_<?php echo $bwge; ?> #bwge_container2_<?php echo $bwge; ?> .bwge_album_thumbnails_<?php echo $bwge; ?> {
        display: inline-block;
        -moz-box-sizing: border-box;
        box-sizing: border-box;
        background-color: rgba(<?php echo $rgb_album_compact_thumbs_bg_color['red']; ?>, <?php echo $rgb_album_compact_thumbs_bg_color['green']; ?>, <?php echo $rgb_album_compact_thumbs_bg_color['blue']; ?>, <?php echo number_format($theme_row->album_compact_thumb_bg_transparent / 100, 2, ".", ""); ?>);
        font-size: 0;
        text-align: <?php echo $theme_row->album_compact_thumb_align; ?>;
        max-width: <?php echo $items_col_num * ($params['compuct_album_thumb_width'] + 2 * (2 + $theme_row->album_compact_thumb_margin + $theme_row->album_compact_thumb_padding + $theme_row->album_compact_thumb_border_width)); ?>px;
      }
      <?php
      if ($params['compuct_album_title'] == 'show') { /* Show album/gallery title at the bottom.*/
        ?>
        #bwge_container1_<?php echo $bwge; ?> #bwge_container2_<?php echo $bwge; ?> .bwge_title_spun1_<?php echo $bwge; ?> {
          display: block;
          opacity: 1;
          filter: Alpha(opacity=100);
          text-align: center;
          width: <?php echo $params['compuct_album_thumb_width']; ?>px;
        }
        <?php
      }
      elseif ($params['compuct_album_title'] == 'hover') { /* Show album/gallery title on hover.*/
        ?>
        #bwge_container1_<?php echo $bwge; ?> #bwge_container2_<?php echo $bwge; ?> .bwge_title_spun1_<?php echo $bwge; ?> {
          display: table;
          height: inherit;
          left: -3000px;
          opacity: 0;
          filter: Alpha(opacity=0);
          position: absolute;
          top: 0px;
          width: inherit;
        }
        <?php
      }
      ?>
      #bwge_container1_<?php echo $bwge; ?> #bwge_container2_<?php echo $bwge; ?> .bwge_album_thumb_spun1_<?php echo $bwge; ?>:hover .bwge_title_spun1_<?php echo $bwge; ?> {
        left: <?php echo $theme_row->album_compact_thumb_padding; ?>px;
        top: <?php echo $theme_row->album_compact_thumb_padding; ?>px;
        opacity: 1;
        filter: Alpha(opacity=100);
      }
      #bwge_container1_<?php echo $bwge; ?> #bwge_container2_<?php echo $bwge; ?> .bwge_title_spun2_<?php echo $bwge; ?> {
        color: #<?php echo $theme_row->album_compact_title_font_color; ?>;
        display: table-cell;
        font-family: <?php echo $theme_row->album_compact_title_font_style; ?>;
        font-size: <?php echo $theme_row->album_compact_title_font_size; ?>px;
        font-weight: <?php echo $theme_row->album_compact_title_font_weight; ?>;
        height: inherit;
        padding: <?php echo $theme_row->album_compact_title_margin; ?>;
        text-shadow: <?php echo $theme_row->album_compact_title_shadow; ?>;
        vertical-align: middle;
        width: inherit;
      }
      #bwge_container1_<?php echo $bwge; ?> #bwge_container2_<?php echo $bwge; ?> .bwge_album_thumbnails_<?php echo $bwge; ?> {
        display: inline-block;
        -moz-box-sizing: border-box;
        box-sizing: border-box;
        background-color: rgba(<?php echo $rgb_album_compact_thumbs_bg_color['red']; ?>, <?php echo $rgb_album_compact_thumbs_bg_color['green']; ?>, <?php echo $rgb_album_compact_thumbs_bg_color['blue']; ?>, <?php echo number_format($theme_row->album_compact_thumb_bg_transparent / 100, 2, ".", ""); ?>);
        font-size: 0;
        text-align: <?php echo $theme_row->album_compact_thumb_align; ?>;
        max-width: <?php echo $items_col_num * ($params['compuct_album_thumb_width'] + 2 * (2 + $theme_row->album_compact_thumb_margin + $theme_row->album_compact_thumb_padding + $theme_row->album_compact_thumb_border_width)); ?>px;
      }

      <?php if($album_view_type != 'mosaic'): ?>
      /* Style for masonry view.*/
      #bwge_container1_<?php echo $bwge; ?> #bwge_container2_<?php echo $bwge; ?> .bwge_masonry_thumbnails_<?php echo $bwge; ?> * {
        -moz-box-sizing: border-box;
        -webkit-box-sizing: border-box;
        box-sizing: border-box;
      }
      #bwge_container1_<?php echo $bwge; ?> #bwge_container2_<?php echo $bwge; ?> .bwge_masonry_thumb_<?php echo $bwge; ?> {
        visibility: hidden;
        text-align: center;
        display: inline-block;
        vertical-align: middle;     
        width: <?php echo $params['compuct_album_image_thumb_width']; ?>px !important;
        border-radius: <?php echo $theme_row->masonry_thumb_border_radius; ?>;
        border: <?php echo $theme_row->masonry_thumb_border_width; ?>px <?php echo $theme_row->masonry_thumb_border_style; ?> #<?php echo $theme_row->masonry_thumb_border_color; ?>;
        background-color: #<?php echo $theme_row->thumb_bg_color; ?>;
        margin: 0;
        padding: <?php echo $theme_row->masonry_thumb_padding; ?>px !important;
        opacity: <?php echo number_format($theme_row->masonry_thumb_transparent / 100, 2, ".", ""); ?>;
        filter: Alpha(opacity=<?php echo $theme_row->masonry_thumb_transparent; ?>);
        <?php echo ($theme_row->masonry_thumb_transition) ? 'transition: all 0.3s ease 0s;-webkit-transition: all 0.3s ease 0s;' : ''; ?>
        z-index: 100;
      }
      #bwge_container1_<?php echo $bwge; ?> #bwge_container2_<?php echo $bwge; ?> .bwge_masonry_thumb_<?php echo $bwge; ?>:hover {
        opacity: 1;
        filter: Alpha(opacity=100);
        transform: <?php echo $theme_row->masonry_thumb_hover_effect; ?>(<?php echo $theme_row->masonry_thumb_hover_effect_value; ?>);
        -ms-transform: <?php echo $theme_row->masonry_thumb_hover_effect; ?>(<?php echo $theme_row->masonry_thumb_hover_effect_value; ?>);
        -webkit-transform: <?php echo $theme_row->masonry_thumb_hover_effect; ?>(<?php echo $theme_row->masonry_thumb_hover_effect_value; ?>);
        backface-visibility: hidden;
        -webkit-backface-visibility: hidden;
        -moz-backface-visibility: hidden;
        -ms-backface-visibility: hidden;
        z-index: 102;
        position: absolute;
      }
      #bwge_container1_<?php echo $bwge; ?> #bwge_container2_<?php echo $bwge; ?> .bwge_masonry_thumbnails_<?php echo $bwge; ?> {
        -moz-box-sizing: border-box;
        background-color: rgba(<?php echo $rgb_thumbs_bg_color['red']; ?>, <?php echo $rgb_thumbs_bg_color['green']; ?>, <?php echo $rgb_thumbs_bg_color['blue']; ?>, <?php echo number_format($theme_row->masonry_thumb_bg_transparent / 100, 2, ".", ""); ?>);
        box-sizing: border-box;
        display: inline-block;
        font-size: 0;
        /*width: <?php echo $params['compuct_album_image_column_number'] * ($params['compuct_album_image_thumb_width'] + 2 * ($theme_row->masonry_thumb_padding + $theme_row->masonry_thumb_border_width)); ?>px;*/
        width: 100%;
        position: relative;
        text-align: <?php echo $theme_row->masonry_thumb_align; ?>;
      }
      @media only screen and (max-width : <?php echo $params['compuct_album_image_column_number'] * ($params['compuct_album_image_thumb_width'] + 2 * ($theme_row->masonry_thumb_padding + $theme_row->masonry_thumb_border_width)); ?>px) {
        #bwge_container1_<?php echo $bwge; ?> #bwge_container2_<?php echo $bwge; ?> .bwge_masonry_thumbnails_<?php echo $bwge; ?> {
          width: inherit;
        }
      }	  	  
      #bwge_container1_<?php echo $bwge; ?> #bwge_container2_<?php echo $bwge; ?> #bwge_spider_popup_overlay_<?php echo $bwge; ?> {
        background-color: #<?php echo $theme_row->lightbox_overlay_bg_color; ?>;
        opacity: <?php echo number_format($theme_row->lightbox_overlay_bg_transparent / 100, 2, ".", ""); ?>;
        filter: Alpha(opacity=<?php echo $theme_row->lightbox_overlay_bg_transparent; ?>);
      }
      #bwge_container1_<?php echo $bwge; ?> #bwge_container2_<?php echo $bwge; ?> .bwge_masonry_thumb_spun_<?php echo $bwge; ?> {
        position: absolute;
      }
      /*Image thumbs styles.*/
      #bwge_container1_<?php echo $bwge; ?> #bwge_container2_<?php echo $bwge; ?> .bwge_standart_thumb_spun1_<?php echo $bwge; ?> {
        background-color: #<?php echo $theme_row->thumb_bg_color; ?>;
        display: inline-block;
        height: <?php echo $params['compuct_album_image_thumb_height']; ?>px;
        margin: <?php echo $theme_row->thumb_margin; ?>px;
        opacity: <?php echo number_format($theme_row->thumb_transparent / 100, 2, ".", ""); ?>;
        filter: Alpha(opacity=<?php echo $theme_row->thumb_transparent; ?>);
        <?php echo ($theme_row->thumb_transition) ? 'transition: all 0.3s ease 0s;-webkit-transition: all 0.3s ease 0s;' : ''; ?>
        padding: <?php echo $theme_row->thumb_padding; ?>px;
        text-align: center;
        vertical-align: middle;
        width: <?php echo $params['compuct_album_image_thumb_width']; ?>px;
        z-index: 100;
      }
      #bwge_container1_<?php echo $bwge; ?> #bwge_container2_<?php echo $bwge; ?> .bwge_standart_thumb_spun1_<?php echo $bwge; ?>:hover {
        -ms-transform: <?php echo $theme_row->thumb_hover_effect; ?>(<?php echo $theme_row->thumb_hover_effect_value; ?>);
        -webkit-transform: <?php echo $theme_row->thumb_hover_effect; ?>(<?php echo $theme_row->thumb_hover_effect_value; ?>);
        backface-visibility: hidden;
        -webkit-backface-visibility: hidden;
        -moz-backface-visibility: hidden;
        -ms-backface-visibility: hidden;
        opacity: 1;
        filter: Alpha(opacity=100);
        transform: <?php echo $theme_row->thumb_hover_effect; ?>(<?php echo $theme_row->thumb_hover_effect_value; ?>);
        z-index: 102;
        position: relative;
      }
      #bwge_container1_<?php echo $bwge; ?> #bwge_container2_<?php echo $bwge; ?> .bwge_standart_thumb_spun2_<?php echo $bwge; ?> {
        border-radius: <?php echo $theme_row->thumb_border_radius; ?>;
        border: <?php echo $theme_row->thumb_border_width; ?>px <?php echo $theme_row->thumb_border_style; ?> #<?php echo $theme_row->thumb_border_color; ?>;
        box-shadow: <?php echo $theme_row->thumb_box_shadow; ?>;
        display: inline-block;
        height: <?php echo $params['compuct_album_image_thumb_height']; ?>px;
        overflow: hidden;
        width: <?php echo $params['compuct_album_image_thumb_width']; ?>px;
      }
      #bwge_container1_<?php echo $bwge; ?> #bwge_container2_<?php echo $bwge; ?> .bwge_standart_thumbnails_<?php echo $bwge; ?> {
        -moz-box-sizing: border-box;
        display: inline-block;
        background-color: rgba(<?php echo $rgb_thumbs_bg_color['red']; ?>, <?php echo $rgb_thumbs_bg_color['green']; ?>, <?php echo $rgb_thumbs_bg_color['blue']; ?>, <?php echo number_format($theme_row->thumb_bg_transparent / 100, 2, ".", ""); ?>);
        box-sizing: border-box;
        font-size: 0;
        max-width: <?php echo $params['compuct_album_image_column_number'] * ($params['compuct_album_image_thumb_width'] + 2 * (2 + $theme_row->thumb_margin + $theme_row->thumb_padding + $theme_row->thumb_border_width)); ?>px;
        text-align: <?php echo $theme_row->thumb_align; ?>;
      }
      #bwge_container1_<?php echo $bwge; ?> #bwge_container2_<?php echo $bwge; ?> .bwge_standart_thumb_<?php echo $bwge; ?> {
        display: inline-block;
        text-align: center;
      }
      <?php
        if( $params['ecommerce_icon'] == 'show' ){
        ?>
        #bwge_container1_<?php echo $bwge; ?> #bwge_container2_<?php echo $bwge; ?> .bwge_ecommerce_spun1_<?php echo $bwge; ?>{
          display: block;
          margin: 0 auto;
          opacity: 1;
          filter: Alpha(opacity=100);
          text-align: right;
          width: <?php echo $params['compuct_album_image_thumb_width']; ?>px;    
        }
        <?php
        }
        elseif ($params['ecommerce_icon'] == 'hover') { /* Show ecommerce icon on hover.*/
        ?>
        #bwge_container1_<?php echo $bwge; ?> #bwge_container2_<?php echo $bwge; ?> .bwge_ecommerce_spun1_<?php echo $bwge; ?> {
          display: table;
          height: inherit;
          left: -3000px;
          opacity: 0;
          filter: Alpha(opacity=0);
          position: absolute;
          top: 0px;
          width: inherit;
        }
        #bwge_container1_<?php echo $bwge; ?> #bwge_container2_<?php echo $bwge; ?> .bwge_standart_thumb_spun1_<?php echo $bwge; ?>:hover img{
            opacity:0.5;
        }
        #bwge_container1_<?php echo $bwge; ?> #bwge_container2_<?php echo $bwge; ?> .bwge_standart_thumb_spun1_<?php echo $bwge; ?>:hover{
            background:#000;
        }
        #bwge_container1_<?php echo $bwge; ?> #bwge_container2_<?php echo $bwge; ?> .bwge_ecommerce_spun1_<?php echo $bwge; ?>, #bwge_container1_<?php echo $bwge; ?> #bwge_container2_<?php echo $bwge; ?> .bwge_ecommerce_spun2_<?php echo $bwge; ?>, #bwge_container1_<?php echo $bwge; ?> #bwge_container2_<?php echo $bwge; ?> .bwge_ecommerce_spun2_<?php echo $bwge; ?> i{
            opacity:1 !important;
            font-size:20px !important;
            z-index: 45;
        }
        <?php
        } 
      if ($params['compuct_album_image_title'] == 'show') { /* Show image title at the bottom.*/
        ?>
        #bwge_container1_<?php echo $bwge; ?> #bwge_container2_<?php echo $bwge; ?> .bwge_image_title_spun1_<?php echo $bwge; ?> {
          display: block;
          margin: 0 auto;
          opacity: 1;
          filter: Alpha(opacity=100);
          text-align: center;
          width: <?php echo $params['compuct_album_thumb_width']; ?>px;
        }
        <?php
      }
      elseif ($params['compuct_album_image_title'] == 'hover') { /* Show image title on hover.*/
        ?>
        #bwge_container1_<?php echo $bwge; ?> #bwge_container2_<?php echo $bwge; ?> .bwge_image_title_spun1_<?php echo $bwge; ?> {
          display: table;
          height: inherit;
          left: -3000px;
          opacity: 0;
          filter: Alpha(opacity=0);
          position: absolute;
          top: 0px;
          width: inherit;
        }
        <?php
      }
      ?>
      #bwge_container1_<?php echo $bwge; ?> #bwge_container2_<?php echo $bwge; ?> .bwge_standart_thumb_spun1_<?php echo $bwge; ?>:hover .bwge_image_title_spun1_<?php echo $bwge; ?>, #bwge_container1_<?php echo $bwge; ?> #bwge_container2_<?php echo $bwge; ?> .bwge_standart_thumb_spun1_<?php echo $bwge; ?>:hover .bwge_ecommerce_spun1_<?php echo $bwge; ?> {
        left: <?php echo $theme_row->thumb_padding; ?>px;
        top: <?php echo $theme_row->thumb_padding; ?>px;
        opacity: 1;
        filter: Alpha(opacity=100);
      }
      #bwge_container1_<?php echo $bwge; ?> #bwge_container2_<?php echo $bwge; ?> .bwge_image_title_spun2_<?php echo $bwge; ?>,  #bwge_container1_<?php echo $bwge; ?> #bwge_container2_<?php echo $bwge; ?> .bwge_ecommerce_spun2_<?php echo $bwge; ?>{
        color: #<?php echo $theme_row->thumb_title_font_color; ?>;
        display: table-cell;
        font-family: <?php echo $theme_row->thumb_title_font_style; ?>;
        font-size: <?php echo $theme_row->thumb_title_font_size; ?>px;
        font-weight: <?php echo $theme_row->thumb_title_font_weight; ?>;
        height: inherit;
        margin: <?php echo $theme_row->thumb_title_margin; ?>;
        text-shadow: <?php echo $theme_row->thumb_title_shadow; ?>;
        vertical-align: middle;
        width: inherit;
        word-wrap: break-word;
      }
      /*Pagination styles.*/
      #bwge_container1_<?php echo $bwge; ?> #bwge_container2_<?php echo $bwge; ?> .tablenav-pages_<?php echo $bwge; ?> {
        text-align: <?php echo $theme_row->page_nav_align; ?>;
        font-size: <?php echo $theme_row->page_nav_font_size; ?>px;
        font-family: <?php echo $theme_row->page_nav_font_style; ?>;
        font-weight: <?php echo $theme_row->page_nav_font_weight; ?>;
        color: #<?php echo $theme_row->page_nav_font_color; ?>;
        margin: 6px 0 4px;
        display: block;
        height: 30px;
        line-height: 30px;
      }
      @media only screen and (max-width : 320px) {
        #bwge_container1_<?php echo $bwge; ?> #bwge_container2_<?php echo $bwge; ?> .displaying-num_<?php echo $bwge; ?> {
          display: none;
        }
      }
      #bwge_container1_<?php echo $bwge; ?> #bwge_container2_<?php echo $bwge; ?> .displaying-num_<?php echo $bwge; ?> {
        font-size: <?php echo $theme_row->page_nav_font_size; ?>px;
        font-family: <?php echo $theme_row->page_nav_font_style; ?>;
        font-weight: <?php echo $theme_row->page_nav_font_weight; ?>;
        color: #<?php echo $theme_row->page_nav_font_color; ?>;
        margin-right: 10px;
        vertical-align: middle;
      }
      #bwge_container1_<?php echo $bwge; ?> #bwge_container2_<?php echo $bwge; ?> .paging-input_<?php echo $bwge; ?> {
        font-size: <?php echo $theme_row->page_nav_font_size; ?>px;
        font-family: <?php echo $theme_row->page_nav_font_style; ?>;
        font-weight: <?php echo $theme_row->page_nav_font_weight; ?>;
        color: #<?php echo $theme_row->page_nav_font_color; ?>;
        vertical-align: middle;
      }
      #bwge_container1_<?php echo $bwge; ?> #bwge_container2_<?php echo $bwge; ?> .tablenav-pages_<?php echo $bwge; ?> a.disabled,
      #bwge_container1_<?php echo $bwge; ?> #bwge_container2_<?php echo $bwge; ?> .tablenav-pages_<?php echo $bwge; ?> a.disabled:hover,
      #bwge_container1_<?php echo $bwge; ?> #bwge_container2_<?php echo $bwge; ?> .tablenav-pages_<?php echo $bwge; ?> a.disabled:focus {
        cursor: default;
        color: rgba(<?php echo $rgb_page_nav_font_color['red']; ?>, <?php echo $rgb_page_nav_font_color['green']; ?>, <?php echo $rgb_page_nav_font_color['blue']; ?>, 0.5);
      }
      #bwge_container1_<?php echo $bwge; ?> #bwge_container2_<?php echo $bwge; ?> .tablenav-pages_<?php echo $bwge; ?> a {
        cursor: pointer;
        font-size: <?php echo $theme_row->page_nav_font_size; ?>px;
        font-family: <?php echo $theme_row->page_nav_font_style; ?>;
        font-weight: <?php echo $theme_row->page_nav_font_weight; ?>;
        color: #<?php echo $theme_row->page_nav_font_color; ?>;
        text-decoration: none;
        padding: <?php echo $theme_row->page_nav_padding; ?>;
        margin: <?php echo $theme_row->page_nav_margin; ?>;
        border-radius: <?php echo $theme_row->page_nav_border_radius; ?>;
        border-style: <?php echo $theme_row->page_nav_border_style; ?>;
        border-width: <?php echo $theme_row->page_nav_border_width; ?>px;
        border-color: #<?php echo $theme_row->page_nav_border_color; ?>;
        background-color: #<?php echo $theme_row->page_nav_button_bg_color; ?>;
        opacity: <?php echo number_format($theme_row->page_nav_button_bg_transparent / 100, 2, ".", ""); ?>;
        filter: Alpha(opacity=<?php echo $theme_row->page_nav_button_bg_transparent; ?>);
        box-shadow: <?php echo $theme_row->page_nav_box_shadow; ?>;
        <?php echo ($theme_row->page_nav_button_transition ) ? 'transition: all 0.3s ease 0s;-webkit-transition: all 0.3s ease 0s;' : ''; ?>
      }
      #bwge_container1_<?php echo $bwge; ?> #bwge_container2_<?php echo $bwge; ?> .bwge_back_<?php echo $bwge; ?> {
        background-color: rgba(0, 0, 0, 0);
        color: #<?php echo $theme_row->album_compact_back_font_color; ?> !important;
        cursor: pointer;
        display: block;
        font-family: <?php echo $theme_row->album_compact_back_font_style; ?>;
        font-size: <?php echo $theme_row->album_compact_back_font_size; ?>px;
        font-weight: <?php echo $theme_row->album_compact_back_font_weight; ?>;
        text-decoration: none;
        padding: <?php echo $theme_row->album_compact_back_padding; ?>;
      }
      #bwge_container1_<?php echo $bwge; ?> #bwge_container2_<?php echo $bwge; ?> #bwge_spider_popup_overlay_<?php echo $bwge; ?> {
        background-color: #<?php echo $theme_row->lightbox_overlay_bg_color; ?>;
        opacity: <?php echo number_format($theme_row->lightbox_overlay_bg_transparent / 100, 2, ".", ""); ?>;
        filter: Alpha(opacity=<?php echo $theme_row->lightbox_overlay_bg_transparent; ?>);
      }
      .bwge_play_icon_spun_<?php echo $bwge; ?>	 {
        width: inherit;
        height: inherit;
        display: table;
        position: absolute;
      }	 
     .bwge_play_icon_<?php echo $bwge; ?> {
        color: #<?php echo $theme_row->thumb_title_font_color; ?>;
        font-size: <?php echo 2 * $theme_row->thumb_title_font_size; ?>px;
        vertical-align: middle;
        display: table-cell !important;
        z-index: 1;
        text-align: center;
        margin: 0 auto;
      }
    <?php
    /*for mosaic view of images*/
    else :
    ?>
      #bwge_container1_<?php echo $bwge; ?> #bwge_container2_<?php echo $bwge; ?> .bwge_mosaic_thumb_<?php echo $bwge; ?> {
        visibility: hidden;
        display: block;
        -moz-box-sizing: content-box !important;
        -webkit-box-sizing: content-box !important;
        box-sizing: content-box !important;                
        border-radius: <?php echo $theme_row->mosaic_thumb_border_radius; ?>;
        border: <?php echo $theme_row->mosaic_thumb_border_width; ?>px <?php echo $theme_row->mosaic_thumb_border_style; ?> #<?php echo $theme_row->mosaic_thumb_border_color; ?>;
        background-color: #<?php echo $theme_row->thumb_bg_color; ?>;
        margin: 0;
        padding: <?php echo $theme_row->mosaic_thumb_padding; ?>px !important;
        opacity: <?php echo number_format($theme_row->mosaic_thumb_transparent / 100, 2, ".", ""); ?>;
        filter: Alpha(opacity=<?php echo $theme_row->mosaic_thumb_transparent; ?>);
        z-index: 100;
      }
      #bwge_container1_<?php echo $bwge; ?> #bwge_container2_<?php echo $bwge; ?> .bwge_mosaic_thumb_spun_<?php echo $bwge; ?>:hover {
        opacity: 1;
        filter: Alpha(opacity=100);
        transform: <?php echo $theme_row->mosaic_thumb_hover_effect; ?>(<?php echo $theme_row->mosaic_thumb_hover_effect_value; ?>);
        -ms-transform: <?php echo $theme_row->mosaic_thumb_hover_effect; ?>(<?php echo $theme_row->mosaic_thumb_hover_effect_value; ?>);
        -webkit-transform: <?php echo $theme_row->mosaic_thumb_hover_effect; ?>(<?php echo $theme_row->mosaic_thumb_hover_effect_value; ?>);
        backface-visibility: hidden;
        -webkit-backface-visibility: hidden;
        -moz-backface-visibility: hidden;
        -ms-backface-visibility: hidden;
        z-index: 102;
        position: absolute;
      }
      #bwge_container1_<?php echo $bwge; ?> #bwge_container2_<?php echo $bwge; ?> .bwge_mosaic_thumbnails_<?php echo $bwge; ?> {
        background-color: rgba(<?php echo $rgb_thumbs_bg_color['red']; ?>, <?php echo $rgb_thumbs_bg_color['green']; ?>, <?php echo $rgb_thumbs_bg_color['blue']; ?>, <?php echo number_format($theme_row->mosaic_thumb_bg_transparent / 100, 2, ".", ""); ?>);
        font-size: 0;
        position: relative;
        text-align: <?php echo $theme_row->mosaic_thumb_align; ?>;
        display: inline-block;
      }
      #bwge_container1_<?php echo $bwge; ?> #bwge_container2_<?php echo $bwge; ?> .bwge_mosaic_thumb_spun_<?php echo $bwge; ?> {
        display:block;
        position: absolute;
        -moz-box-sizing: content-box !important;
        -webkit-box-sizing: content-box !important;
        box-sizing: content-box !important;
      }
      /*image title styles*/
      #bwge_container1_<?php echo $bwge; ?> #bwge_container2_<?php echo $bwge; ?> .bwge_mosaic_title_spun1_<?php echo $bwge; ?> {
        position: absolute;
        display:block;
        opacity: 0;
        filter: Alpha(opacity=0);
        left: -10000px;
        top: 0px;
        box-sizing:border-box;
        text-align: center;
      }
      #bwge_container1_<?php echo $bwge; ?> #bwge_container2_<?php echo $bwge; ?> .bwge_mosaic_title_spun2_<?php echo $bwge; ?> {
        color: #<?php echo $theme_row->mosaic_thumb_title_font_color; ?>;
        font-family: <?php echo $theme_row->mosaic_thumb_title_font_style; ?>;
        font-size: <?php echo $theme_row->mosaic_thumb_title_font_size; ?>px;
        font-weight: <?php echo $theme_row->mosaic_thumb_title_font_weight; ?>;        
        text-shadow: <?php echo $theme_row->mosaic_thumb_title_shadow; ?>;
        vertical-align: middle;
        word-wrap: break-word;

      }
      .bwge_mosaic_play_icon_spun_<?php echo $bwge; ?>  {
        display: table;
        position: absolute;
        left: -10000px;
        top: 0px;
        opacity: 0;
        filter: Alpha(opacity=0);
      }
      .bwge_mosaic_play_icon_<?php echo $bwge; ?> {
        color: #<?php echo $theme_row->mosaic_thumb_title_font_color; ?>;
        font-size: <?php echo 2 * $theme_row->mosaic_thumb_title_font_size; ?>px;
        vertical-align: middle;
        display: table-cell !important;
        z-index: 1;
        text-align: center;
        margin: 0 auto;
      }

      /*pagination styles*/
      #bwge_container1_<?php echo $bwge; ?> #bwge_container2_<?php echo $bwge; ?> .tablenav-pages_<?php echo $bwge; ?> {
        text-align: <?php echo $theme_row->page_nav_align; ?>;
        font-size: <?php echo $theme_row->page_nav_font_size; ?>px;
        font-family: <?php echo $theme_row->page_nav_font_style; ?>;
        font-weight: <?php echo $theme_row->page_nav_font_weight; ?>;
        color: #<?php echo $theme_row->page_nav_font_color; ?>;
        margin: 6px 0 4px;
        display: block;
        height: 30px;
        line-height: 30px;
      }
          
      #bwge_container1_<?php echo $bwge; ?> #bwge_container2_<?php echo $bwge; ?> .displaying-num_<?php echo $bwge; ?> {
        font-size: <?php echo $theme_row->page_nav_font_size; ?>px;
        font-family: <?php echo $theme_row->page_nav_font_style; ?>;
        font-weight: <?php echo $theme_row->page_nav_font_weight; ?>;
        color: #<?php echo $theme_row->page_nav_font_color; ?>;
        margin-right: 10px;
        vertical-align: middle;
      }
      #bwge_container1_<?php echo $bwge; ?> #bwge_container2_<?php echo $bwge; ?> .paging-input_<?php echo $bwge; ?> {
        font-size: <?php echo $theme_row->page_nav_font_size; ?>px;
        font-family: <?php echo $theme_row->page_nav_font_style; ?>;
        font-weight: <?php echo $theme_row->page_nav_font_weight; ?>;
        color: #<?php echo $theme_row->page_nav_font_color; ?>;
        vertical-align: middle;
      }
      #bwge_container1_<?php echo $bwge; ?> #bwge_container2_<?php echo $bwge; ?> .tablenav-pages_<?php echo $bwge; ?> a.disabled,
      #bwge_container1_<?php echo $bwge; ?> #bwge_container2_<?php echo $bwge; ?> .tablenav-pages_<?php echo $bwge; ?> a.disabled:hover,
      #bwge_container1_<?php echo $bwge; ?> #bwge_container2_<?php echo $bwge; ?> .tablenav-pages_<?php echo $bwge; ?> a.disabled:focus {
        cursor: default;
        color: rgba(<?php echo $rgb_page_nav_font_color['red']; ?>, <?php echo $rgb_page_nav_font_color['green']; ?>, <?php echo $rgb_page_nav_font_color['blue']; ?>, 0.5);
      }
      #bwge_container1_<?php echo $bwge; ?> #bwge_container2_<?php echo $bwge; ?> .tablenav-pages_<?php echo $bwge; ?> a {
        cursor: pointer;
        font-size: <?php echo $theme_row->page_nav_font_size; ?>px;
        font-family: <?php echo $theme_row->page_nav_font_style; ?>;
        font-weight: <?php echo $theme_row->page_nav_font_weight; ?>;
        color: #<?php echo $theme_row->page_nav_font_color; ?>;
        text-decoration: none;
        padding: <?php echo $theme_row->page_nav_padding; ?>;
        margin: <?php echo $theme_row->page_nav_margin; ?>;
        border-radius: <?php echo $theme_row->page_nav_border_radius; ?>;
        border-style: <?php echo $theme_row->page_nav_border_style; ?>;
        border-width: <?php echo $theme_row->page_nav_border_width; ?>px;
        border-color: #<?php echo $theme_row->page_nav_border_color; ?>;
        background-color: #<?php echo $theme_row->page_nav_button_bg_color; ?>;
        opacity: <?php echo number_format($theme_row->page_nav_button_bg_transparent / 100, 2, ".", ""); ?>;
        filter: Alpha(opacity=<?php echo $theme_row->page_nav_button_bg_transparent; ?>);
        box-shadow: <?php echo $theme_row->page_nav_box_shadow; ?>;
        <?php echo ($theme_row->page_nav_button_transition ) ? 'transition: all 0.3s ease 0s;-webkit-transition: all 0.3s ease 0s;' : ''; ?>
      }
      #bwge_container1_<?php echo $bwge; ?> #bwge_container2_<?php echo $bwge; ?> .bwge_back_<?php echo $bwge; ?> {
        background-color: rgba(0, 0, 0, 0);
        color: #<?php echo $theme_row->album_compact_back_font_color; ?> !important;
        cursor: pointer;
        display: block;
        font-family: <?php echo $theme_row->album_compact_back_font_style; ?>;
        font-size: <?php echo $theme_row->album_compact_back_font_size; ?>px;
        font-weight: <?php echo $theme_row->album_compact_back_font_weight; ?>;
        text-decoration: none;
        padding: <?php echo $theme_row->album_compact_back_padding; ?>;
      }
      #bwge_container1_<?php echo $bwge; ?> #bwge_container2_<?php echo $bwge; ?> #bwge_spider_popup_overlay_<?php echo $bwge; ?> {
        background-color: #<?php echo $theme_row->lightbox_overlay_bg_color; ?>;
        opacity: <?php echo number_format($theme_row->lightbox_overlay_bg_transparent / 100, 2, ".", ""); ?>;
        filter: Alpha(opacity=<?php echo $theme_row->lightbox_overlay_bg_transparent; ?>);
      }
    <?php 
  endif;/*for mosaic view or (thumbnail or masonry) view*/
  ?>
    </style>
		<?php
			$album_row = $this->model->get_album_row_data($album_gallery_id);
		?>
    <div id="bwge_container1_<?php echo $bwge; ?>">
      <div id="bwge_container2_<?php echo $bwge; ?>">
        <form id="bwge_gal_front_form_<?php echo $bwge; ?>" method="post" action="#">
          <?php
          if ($params['show_search_box'] && $type == 'gallery') {
            BWGELibrary::ajax_html_frontend_search_box('bwge_gal_front_form_' . $bwge, $bwge, $album_gallery_div_id, $images_count, $params['search_box_width'], $placeholder);
          }
          if (isset($params['show_sort_images']) && $params['show_sort_images'] && $type == 'gallery') {
            BWGELibrary::ajax_html_frontend_sort_box('bwge_gal_front_form_' . $bwge, $bwge, $album_gallery_div_id, $params['sort_by'], $params['search_box_width']);
          }
          if (isset($params['show_tag_box']) && $params['show_tag_box'] && $type == 'gallery') {
            BWGELibrary::ajax_html_frontend_search_tags('bwge_gal_front_form_' . $bwge, $bwge, $album_gallery_div_id, $images_count, $tags_rows);
          }
          ?>
          <div id="<?php echo $form_child_div_id; ?>" style="<?php echo $form_child_div_style; ?>">
            <div id="ajax_loading_<?php echo $bwge; ?>" style="position:absolute;width: 100%; z-index: 115; text-align: center; height: 100%; vertical-align: middle; display: none;">
              <div style="display: table; vertical-align: middle; width: 100%; height: 100%; background-color: #FFFFFF; opacity: 0.7; filter: Alpha(opacity=70);">
                <div style="display: table-cell; text-align: center; position: relative; vertical-align: middle;" >
                  <div id="loading_div_<?php echo $bwge; ?>" class="bwge_spider_ajax_loading" style="display: inline-block; text-align:center; position:relative; vertical-align:middle; background-image:url(<?php echo WD_BWGE_URL . '/images/ajax_loader.png'; ?>); float: none; width:50px;height:50px;background-size:50px 50px;">
                  </div>
                </div>
              </div>
            </div>
            <?php
            if ($params['compuct_album_enable_page'] && $items_per_page && ($theme_row->page_nav_position == 'top') && $page_nav['total']) {
              BWGELibrary::ajax_html_frontend_page_nav($theme_row, $page_nav['total'], $page_nav['limit'], 'bwge_gal_front_form_' . $bwge, $items_per_page_arr, $bwge, $album_gallery_div_id, $params['album_id'], $type, $options_row->enable_seo, $params['compuct_album_enable_page']);
            }
            if ($bwge_previous_album_id != $params['album_id']) {
              ?>
              <a class="bwge_back_<?php echo $bwge; ?>" onclick="bwge_spider_frontend_ajax('bwge_gal_front_form_<?php echo $bwge; ?>', '<?php echo $bwge; ?>', '<?php echo $album_gallery_div_id; ?>', 'back', '', 'album')"><?php echo __('Back', 'bwge'); ?></a>
              <?php
            }
            if ($options_row->show_album_name) {
              if ($type == 'gallery') {
                    ?>
                    <div class="bwge_back_<?php echo $bwge; ?>" ><?php echo isset($_POST['bwge_title_' . $bwge]) ? esc_html($_POST['bwge_title_' . $bwge]) : ''; ?></div>
                    <?php
              }
              else {
                ?>
              <div class="bwge_back_<?php echo $bwge; ?>"><?php	echo $album_row->name; ?></div>
                <?php
              }
            }
            ?>
            <div id="<?php echo $album_gallery_div_id; ?>" class="<?php echo $album_gallery_div_class; ?>" >
              <input type="hidden" id="bwge_previous_album_id_<?php echo $bwge; ?>" name="bwge_previous_album_id_<?php echo $bwge; ?>" value="<?php echo $bwge_previous_album_id; ?>" />
              <input type="hidden" id="bwge_previous_album_bwge_page_number_<?php echo $bwge; ?>" name="bwge_previous_album_bwge_page_number_<?php echo $bwge; ?>" value="<?php echo $bwge_previous_album_page_number; ?>" />
              <?php
              if ($type != 'gallery') {
                if (!$page_nav['total']) {
                  ?>
                  <span class="bwge_back_<?php echo $bwge; ?>"><?php echo __('Album is empty.', 'bwge'); ?></span>
                  <?php
                }
                foreach ($album_galleries_row as $album_galallery_row) {
                  if ($album_galallery_row->is_album) {
                    $album_row = $this->model->get_album_row_data($album_galallery_row->alb_gal_id);
                    if (!$album_row) {
                      continue;
                    }
                    $preview_image = $album_row->preview_image;
                    if (!$preview_image) {
                      $preview_image = $album_row->random_preview_image;
                    }
                    $def_type = 'album';
                    $title = $album_row->name;
                    $permalink = $album_row->permalink;
                  }
                  else {
                    $gallery_row = $this->model->get_gallery_row_data($album_galallery_row->alb_gal_id);
                    if (!$gallery_row) {
                      continue;
                    }
                    $preview_image = $gallery_row->preview_image;
                    if (!$preview_image) {
                      $preview_image = $gallery_row->random_preview_image;
                    }
                    $def_type = 'gallery';
                    $title = $gallery_row->name;
                    $permalink = $gallery_row->permalink;
                  }

                  $local_preview_image = true;
                  $parsed_prev_url = parse_url($preview_image, PHP_URL_SCHEME);
                  
                  if($parsed_prev_url =='http' || $parsed_prev_url =='https'){
                    $local_preview_image = false;
                  }

                  if (!$preview_image) {
                    $preview_url = WD_BWGE_URL . '/images/no-image.png';
                    $preview_path = WD_BWGE_DIR . '/images/no-image.png';
                  }
                  else {
                    if($local_preview_image){
                      $preview_url = site_url() . '/' . $WD_BWGE_UPLOAD_DIR . $preview_image;
                      $preview_path = ABSPATH . $WD_BWGE_UPLOAD_DIR . $preview_image;
                    }
                    else{
                      $preview_url = $preview_image;
                      $preview_path = $preview_image;
                    }
                  }
                  if($local_preview_image){
                    list($image_thumb_width, $image_thumb_height) = getimagesize(htmlspecialchars_decode($preview_path, ENT_COMPAT | ENT_QUOTES));
                    $scale = max($params['compuct_album_thumb_width'] / $image_thumb_width, $params['compuct_album_thumb_height'] / $image_thumb_height);
                    $image_thumb_width *= $scale;
                    $image_thumb_height *= $scale;
                    $thumb_left = ($params['compuct_album_thumb_width'] - $image_thumb_width) / 2;
                    $thumb_top = ($params['compuct_album_thumb_height'] - $image_thumb_height) / 2;
                  }
                  else{
                    $image_thumb_width = $params['compuct_album_thumb_width'];
                    $image_thumb_height = $params['compuct_album_thumb_height'];
                    $thumb_left = 0;
                    $thumb_top = 0;
                  }
                  if ($type != 'gallery') {
                    ?>
                    <a class="bwge_album_<?php echo $bwge; ?>" <?php echo ($from !== "widget" ? ($options_row->enable_seo ? "href='" . add_query_arg(array("bwge_type" . $bwge => $def_type, "bwge_album_gallery_id_" . $bwge => $album_galallery_row->alb_gal_id, "bwge_previous_album_id_" . $bwge => $album_gallery_id . ',' . $bwge_previous_album_id , "bwge_previous_album_bwge_page_number_" . $bwge => (isset($_REQUEST['bwge_page_number_' . $bwge]) ? esc_html($_REQUEST['bwge_page_number_' . $bwge]) : 0) . ',' . $bwge_previous_album_page_number), $_SERVER['REQUEST_URI']) . "'" : "") . " data-alb_gal_id=\"" . $album_galallery_row->alb_gal_id . "\" data-def_type=\"" . $def_type . "\" data-title=\"" . htmlspecialchars(addslashes($title)) . "\"" : "href='" . $permalink . "'") ?>>
                      <span class="bwge_album_thumb_<?php echo $bwge; ?>">
                        <?php
                        if ($params['compuct_album_title'] == 'show' && $theme_row->album_compact_thumb_title_pos == 'top') {
                          ?>
                          <span class="bwge_title_spun1_<?php echo $bwge; ?>">
                            <span class="bwge_title_spun2_<?php echo $bwge; ?>">
                              <?php echo $title; ?>
                            </span>
                          </span>
                          <?php
                        }
                        ?>
                        <span class="bwge_album_thumb_spun1_<?php echo $bwge; ?>">
                          <span class="bwge_album_thumb_spun2_<?php echo $bwge; ?>">
                            <img class="bwge_img_clear bwge_img_custom" style="width: <?php echo $image_thumb_width; ?>px; height:<?php echo $image_thumb_height; ?>px; margin-left: <?php echo $thumb_left; ?>px; margin-top: <?php echo $thumb_top; ?>px;" src="<?php echo $preview_url; ?>" alt="<?php echo $title; ?>" />
                            <?php
                            if ($params['compuct_album_title'] == 'hover') {
                              ?>
                              <span class="bwge_title_spun1_<?php echo $bwge; ?>">
                                <span class="bwge_title_spun2_<?php echo $bwge; ?>">
                                  <?php echo $title; ?>
                                </span>
                              </span>
                              <?php
                            }
                            ?>
                          </span>
                        </span>
                        <?php
                        if ($params['compuct_album_title'] == 'show' && $theme_row->album_compact_thumb_title_pos == 'bottom') {
                          ?>
                          <span class="bwge_title_spun1_<?php echo $bwge; ?>">
                            <span class="bwge_title_spun2_<?php echo $bwge; ?>">
                              <?php echo $title; ?>
                            </span>
                          </span>
                          <?php
                        }
                        ?>
                      </span>
                    </a>
                    <?php
                  }
                }
              }
              elseif ($type == 'gallery') {
   
                if (!$page_nav['total']) {
                  if ($bwge_search != '') {
                    ?>
                    <span class="bwge_back_<?php echo $bwge; ?>"><?php echo __('There are no images matching your search.', 'bwge'); ?></span>
                    <?php
                  }
                  else {
                    ?>
                    <span class="bwge_back_<?php echo $bwge; ?>"><?php echo __('Gallery is empty.', 'bwge'); ?></span>
                    <?php
                  }
                }
                
                foreach ($image_rows as $image_row) {
                  $is_embed = preg_match('/EMBED/',$image_row->filetype)==1 ? true :false;
                  $is_embed_video = preg_match('/VIDEO/',$image_row->filetype)==1 ? true :false;
                  if (!$is_embed) {
                    list($image_thumb_width, $image_thumb_height) = getimagesize(htmlspecialchars_decode(ABSPATH . $WD_BWGE_UPLOAD_DIR . $image_row->thumb_url, ENT_COMPAT | ENT_QUOTES));
                  }
                  else {
                    if ($image_row->resolution != '') {
                      $resolution_arr = explode(" ",$image_row->resolution);
                      $resolution_w = intval($resolution_arr[0]);
                      $resolution_h = intval($resolution_arr[2]);
                      if($resolution_w != 0 && $resolution_h != 0){
                        $scale = $scale = max($params['compuct_album_image_thumb_width'] / $resolution_w, $params['compuct_album_image_thumb_height'] / $resolution_h);
                        $image_thumb_width = $resolution_w * $scale;
                        $image_thumb_height = $resolution_h * $scale;
                      }
                      else{
                      $image_thumb_width = $params['compuct_album_image_thumb_width'];
                      $image_thumb_height = $params['compuct_album_image_thumb_height'];
                      }
                    }
                    else{
                      $image_thumb_width = $params['compuct_album_image_thumb_width'];
                      $image_thumb_height = $params['compuct_album_image_thumb_height'];
                    }               
                  }
                  $scale = max($params['compuct_album_image_thumb_width'] / $image_thumb_width, $params['compuct_album_image_thumb_height'] / $image_thumb_height);
                  $image_thumb_width *= $scale;
                  $image_thumb_height *= $scale;
                  $thumb_left = ($params['compuct_album_image_thumb_width'] - $image_thumb_width) / 2;
                  $thumb_top = ($params['compuct_album_image_thumb_height'] - $image_thumb_height) / 2;
                  if ($album_view_type == 'thumbnail') {
                    ?>
                    <a <?php echo ($params['thumb_click_action'] == 'open_lightbox' ? (' class="bwge_lightbox_' . $bwge . '"' . ($options_row->enable_seo ? ' href="' . ($is_embed ? $image_row->thumb_url : site_url() . '/' . $WD_BWGE_UPLOAD_DIR . $image_row->image_url) . '"' : '') . ' data-image-id="' . $image_row->id . '" data-gallery-id="' . $album_gallery_id . '"') : ($image_row->redirect_url ? 'href="' . $image_row->redirect_url . '" target="' .  ($params['thumb_link_target'] ? '_blank' : '')  . '"' : '')) ?>>
                    <span class="bwge_standart_thumb_<?php echo $bwge; ?>">
                      <?php
                      if ($params['compuct_album_image_title'] == 'show' && $theme_row->album_compact_thumb_title_pos == 'top') {
                        ?>
                        <span class="bwge_image_title_spun1_<?php echo $bwge; ?>">
                          <span class="bwge_image_title_spun2_<?php echo $bwge; ?>">
                            <?php echo $image_row->alt; ?>
                          </span>
                        </span>
                        <?php
                      }
                      ?>
                      <span class="bwge_standart_thumb_spun1_<?php echo $bwge; ?>">
                        <span class="bwge_standart_thumb_spun2_<?php echo $bwge; ?>">
                          <?php
                          if ($play_icon && $is_embed_video) {
                            ?>
                          <span class="bwge_play_icon_spun_<?php echo $bwge; ?>">
                             <i title="<?php echo __('Play', 'bwge'); ?>"  class="fa fa-play bwge_play_icon_<?php echo $bwge; ?>"></i>
                          </span>
                            <?php
                          }
                          if ($params['compuct_album_image_title'] == 'hover') {
                            ?>
                            <span class="bwge_image_title_spun1_<?php echo $bwge; ?>">
                              <span class="bwge_image_title_spun2_<?php echo $bwge; ?>">
                                <?php echo $image_row->alt; ?>
                              </span>
                            </span>
                            <?php
                          }
                          
                          if( $params['ecommerce_icon'] == 'hover' && $image_row->pricelist_id){		 
                            ?>	
                          <span class="bwge_ecommerce_spun1_<?php echo $bwge; ?>">
                            <span class="bwge_ecommerce_spun2_<?php echo $bwge; ?>">
                              <i title="<?php echo __('Open', 'bwge'); ?>" class="bwge_ctrl_btn bwge_open fa fa-share-square" ></i>
                              <i title="<?php echo __('Ecommerce', 'bwge'); ?>" class="bwge_ctrl_btn bwge_ecommerce fa fa-shopping-cart" ></i>                               
                            </span>
                          </span>                               
                       <?php
                          }                              
                      ?>
                          <img class="bwge_img_clear bwge_img_custom" style="width:<?php echo $image_thumb_width; ?>px; height:<?php echo $image_thumb_height; ?>px; margin-left: <?php echo $thumb_left; ?>px; margin-top: <?php echo $thumb_top; ?>px;" id="<?php echo $image_row->id; ?>" src="<?php echo ( $is_embed ? "" : site_url() . '/' . $WD_BWGE_UPLOAD_DIR) . $image_row->thumb_url; ?>" alt="<?php echo $image_row->alt; ?>" />
                        </span>
                      </span>
                      <?php
                      if ($params['compuct_album_image_title'] == 'show' && $theme_row->album_compact_thumb_title_pos == 'bottom') {
                        ?>
                        <span class="bwge_image_title_spun1_<?php echo $bwge; ?>">
                          <span class="bwge_image_title_spun2_<?php echo $bwge; ?>">
                            <?php echo $image_row->alt; ?>
                          </span>
                        </span>
                        <?php
                      }
                      ?>
                    <?php
                      if ($params['ecommerce_icon'] == 'show' && $image_row->pricelist_id) {
                        ?>
                        <span class="bwge_ecommerce_spun1_<?php echo $bwge; ?>">
                            <span class="bwge_ecommerce_spun2_<?php echo $bwge; ?>">
                              <i title="<?php echo __('Open', 'bwge'); ?>" class="bwge_ctrl_btn bwge_open fa fa-share-square" ></i>
                              <i title="<?php echo __('Ecommerce', 'bwge'); ?>" class="bwge_ctrl_btn bwge_ecommerce fa fa-shopping-cart" ></i>                               
                            </span>
                          </span> 
                        <?php
                      }
                      ?>
                    </span>
                  </a>
                    <?php
                  }

		  
                }
              }
              ?>
            </div>

            <?php
            if ($params['compuct_album_enable_page'] && $items_per_page && ($theme_row->page_nav_position == 'bottom') && $page_nav['total']) {
              BWGELibrary::ajax_html_frontend_page_nav($theme_row, $page_nav['total'], $page_nav['limit'], 'bwge_gal_front_form_' . $bwge, $items_per_page_arr, $bwge, $album_gallery_div_id, $params['album_id'], $type, $options_row->enable_seo, $params['compuct_album_enable_page']);
            }
            ?>
          </div>
        </form>
        <div id="bwge_spider_popup_loading_<?php echo $bwge; ?>" class="bwge_spider_popup_loading"></div>
        <div id="bwge_spider_popup_overlay_<?php echo $bwge; ?>" class="bwge_spider_popup_overlay" onclick="bwge_spider_destroypopup(1000)"></div>
      </div>
    </div>
    <script>
      function bwge_gallery_box_<?php echo $bwge; ?>(gallery_id, image_id,openEcommerce) {
		if(typeof openEcommerce == undefined){
          openEcommerce = false;
        }
		var ecommerce = openEcommerce == true ? "&open_ecommerce=1" : "";			  
        var filterTags = jQuery("#bwge_tags_id_bwge_album_compact_<?php echo $bwge; ?>" ).val() ? jQuery("#bwge_tags_id_bwge_album_compact_<?php echo $bwge; ?>" ).val() : 0;
        var filtersearchname = jQuery("#bwge_search_input_<?php echo $bwge; ?>" ).val() ? jQuery("#bwge_search_input_<?php echo $bwge; ?>" ).val() : '';
        bwge_spider_createpopup('<?php echo addslashes(add_query_arg($params_array, admin_url('admin-ajax.php'))); ?>&gallery_id=' + gallery_id + '&image_id=' + image_id + "&filter_tag_<?php echo $bwge; ?>=" +  filterTags + ecommerce + "&filter_search_name_<?php echo $bwge; ?>=" +  filtersearchname, '<?php echo $bwge; ?>', '<?php echo $bwge; ?>', '<?php echo $params['popup_width']; ?>', '<?php echo $params['popup_height']; ?>', 1, 'testpopup', 5, "<?php echo $theme_row->lightbox_ctrl_btn_pos ;?>");
      }
      var bwge_hash = window.location.hash.substring(1);
      if (bwge_hash) {
        if (bwge_hash.indexOf("bwge") != "-1") {
          bwge_hash_array = bwge_hash.replace("bwge", "").split("/");
          bwge_gallery_box_<?php echo $bwge; ?>(bwge_hash_array[0], bwge_hash_array[1]);
        }
      }
      function bwge_document_ready_<?php echo $bwge; ?>() {
        var bwge_touch_flag = false;
        jQuery(".bwge_lightbox_<?php echo $bwge; ?>").on("click", function () {
          if (!bwge_touch_flag) {
            bwge_touch_flag = true;
            setTimeout(function(){ bwge_touch_flag = false; }, 100);
            bwge_gallery_box_<?php echo $bwge; ?>(jQuery(this).attr("data-gallery-id"), jQuery(this).attr("data-image-id"));
            return false;
          }
        });
		jQuery(".bwge_lightbox_<?php echo $bwge; ?> .bwge_ecommerce").on("click", function (event) {
          event.stopPropagation();
          if (!bwge_touch_flag) {
            bwge_touch_flag = true;
            setTimeout(function(){ bwge_touch_flag = false; }, 100);
			var image_id = jQuery(this).closest(".bwge_lightbox_<?php echo $bwge; ?>").attr("data-image-id");			
			var gallery_id = jQuery(this).closest(".bwge_lightbox_<?php echo $bwge; ?>").attr("data-gallery-id");			
            bwge_gallery_box_<?php echo $bwge; ?>(gallery_id,image_id, true);
            return false;
          }
        });
        <?php if ($from !== "widget") { ?>
        jQuery(".bwge_album_<?php echo $bwge; ?>").on("click", function () {
        
          if (!bwge_touch_flag) {
            bwge_touch_flag = true;
            setTimeout(function(){ bwge_touch_flag = false; }, 100);
            bwge_spider_frontend_ajax('bwge_gal_front_form_<?php echo $bwge; ?>', '<?php echo $bwge; ?>', 'bwge_album_compact_<?php echo $bwge; ?>', jQuery(this).attr("data-alb_gal_id"), '<?php echo $album_gallery_id; ?>', jQuery(this).attr("data-def_type"), '', jQuery(this).attr("data-title"), 'default'); 
            return false;
          }
        });
        <?php } ?>
      }
      jQuery(document).ready(function () {
        bwge_document_ready_<?php echo $bwge; ?>();
      });
    </script>
    <?php
    if ($from_shortcode) {
      return;
    }
    else {
      die();
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