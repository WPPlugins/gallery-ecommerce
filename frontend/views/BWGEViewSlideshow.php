<?php

class BWGEViewSlideshow {
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
    require_once(WD_BWGE_DIR . '/framework/BWGELibrary.php');
    require_once(WD_BWGE_DIR . '/framework/BWGELibraryEmbed.php');

    global $WD_BWGE_UPLOAD_DIR;
    $from = (isset($params['from']) ? esc_html($params['from']) : 0);
    $options_row = $this->model->get_options_row_data();
    if (!isset($params['order_by'])) {
      $order_by = 'asc'; 
    }
    else {
      $order_by = $params['order_by'];
    }
    if (!isset($params['slideshow_title_full_width'])) {
      $params['slideshow_title_full_width'] = 0;
    }
    $image_right_click = $options_row->image_right_click;
    $filmstrip_direction = 'horizontal';
    if (!$from) {
      $theme_id = (isset($params['theme_id']) ? esc_html($params['theme_id']) : 1);
      $theme_row = $this->model->get_theme_row_data($theme_id);
      if (!$theme_row) {
        echo BWGELibrary::message(__('There is no theme selected or the theme was deleted.', 'bwge'), 'error');
        return;
      }
      if ($theme_row->slideshow_filmstrip_pos == 'right' || $theme_row->slideshow_filmstrip_pos == 'left') {
        $filmstrip_direction = 'vertical';        
      }
      $gallery_id = (isset($params['gallery_id']) ? esc_html($params['gallery_id']) : 0);
      $sort_by = (isset($params['sort_by']) ? esc_html($params['sort_by']) : 'order');
      $slideshow_effect = (isset($params['slideshow_effect']) ? esc_html($params['slideshow_effect']) : 'fade');
      $enable_slideshow_autoplay = (isset($params['enable_slideshow_autoplay']) ? esc_html($params['enable_slideshow_autoplay']) : 0);
      $enable_slideshow_shuffle = (isset($params['enable_slideshow_shuffle']) ? esc_html($params['enable_slideshow_shuffle']) : 0);
      $enable_slideshow_ctrl = (isset($params['enable_slideshow_ctrl']) ? esc_html($params['enable_slideshow_ctrl']) : 0);
      $enable_slideshow_filmstrip = (isset($params['enable_slideshow_filmstrip']) ? esc_html($params['enable_slideshow_filmstrip']) : 0);

      $slideshow_filmstrip_height = 0;
      $slideshow_filmstrip_width = 0;
      

      $slideshow_ecommerce_icon = (isset($params['slideshow_ecommerce_icon']) ? esc_html($params['slideshow_ecommerce_icon']) : 1);
      $enable_image_title = (isset($params['slideshow_enable_title']) ? esc_html($params['slideshow_enable_title']) : 0);
      $slideshow_title_position = explode('-', (isset($params['slideshow_title_position']) ? esc_html($params['slideshow_title_position']) : 'bottom-right'));
      $enable_image_description = (isset($params['slideshow_enable_description']) ? esc_html($params['slideshow_enable_description']) : 0);
      $slideshow_description_position = explode('-', (isset($params['slideshow_description_position']) ? esc_html($params['slideshow_description_position']) : 'bottom-right'));
      $enable_slideshow_music = (isset($params['enable_slideshow_music']) ? esc_html($params['enable_slideshow_music']) : 0);
      $slideshow_music_url = (isset($params['slideshow_music_url']) ? esc_html($params['slideshow_music_url']) : '');

      $image_width = (isset($params['slideshow_width']) ? esc_html($params['slideshow_width']) : '800');
      $image_height = (isset($params['slideshow_height']) ? esc_html($params['slideshow_height']) : '600');
      $slideshow_interval = (isset($params['slideshow_interval']) ? esc_html($params['slideshow_interval']) : 5);

      $watermark_type = (isset($params['watermark_type']) ? esc_html($params['watermark_type']) : 'none');
      $watermark_text = (isset($params['watermark_text']) ? esc_html($params['watermark_text']) : '');
      $watermark_font_size = (isset($params['watermark_font_size']) ? esc_html($params['watermark_font_size']) : 12);
      $watermark_font = (isset($params['watermark_font']) ? esc_html($params['watermark_font']) : 'Arial');
      $watermark_color = (isset($params['watermark_color']) ? esc_html($params['watermark_color']) : 'FFFFFF');
      $watermark_opacity = (isset($params['watermark_opacity']) ? esc_html($params['watermark_opacity']) : 30);
      $watermark_position = explode('-', (isset($params['watermark_position']) ? esc_html($params['watermark_position']) : 'bottom-right'));
      $watermark_link = (isset($params['watermark_link']) ? esc_html($params['watermark_link']) : '');
      $watermark_url = (isset($params['watermark_url']) ? esc_html($params['watermark_url']) : '');
      $watermark_width = (isset($params['watermark_width']) ? esc_html($params['watermark_width']) : 90);
      $watermark_height = (isset($params['watermark_height']) ? esc_html($params['watermark_height']) : 90);
    }
    else {      
      $theme_id = (isset($params['theme_id']) ? esc_html($params['theme_id']) : 0);
      $theme_row = $this->model->get_theme_row_data($theme_id);
      if (!$theme_row) {
        echo BWGELibrary::message(__('There is no theme selected or the theme was deleted.', 'bwge'), 'error');
        return;
      }
      if ($theme_row->slideshow_filmstrip_pos == 'right' || $theme_row->slideshow_filmstrip_pos == 'left') {
        $filmstrip_direction = 'vertical';        
      }
      $gallery_id = (isset($params['gallery_id']) ? esc_html($params['gallery_id']) : 0);
      $sort_by = 'order';
      $slideshow_effect = (isset($params['effect']) ? esc_html($params['effect']) : 'fade');
      $enable_slideshow_autoplay = $options_row->slideshow_enable_autoplay;
      $enable_slideshow_shuffle = (isset($params['shuffle']) ? esc_html($params['shuffle']) : 0);
      $enable_slideshow_ctrl = $options_row->slideshow_enable_ctrl;
      $enable_slideshow_filmstrip = $options_row->slideshow_enable_filmstrip;

      $enable_image_title = $options_row->slideshow_enable_title;
      $slideshow_title_position = explode('-', $options_row->slideshow_title_position);
      $enable_image_description = $options_row->slideshow_enable_description;
      $slideshow_description_position = explode('-', $options_row->slideshow_description_position);
      $enable_slideshow_music = $options_row->slideshow_enable_music;
      $slideshow_music_url = $options_row->slideshow_audio_url;

      $image_width = (isset($params['width']) ? esc_html($params['width']) : '800');
      $image_height = (isset($params['height']) ? esc_html($params['height']) : '600');
      $slideshow_interval = (isset($params['interval']) ? esc_html($params['interval']) : 5);

      $watermark_type = $options_row->watermark_type;
      $watermark_text = $options_row->watermark_text;
      $watermark_font_size = $options_row->watermark_font_size;
      $watermark_font = $options_row->watermark_font;
      $watermark_color = $options_row->watermark_color;
      $watermark_opacity = $options_row->watermark_opacity;
      $watermark_position = explode('-', $options_row->watermark_position);
      $watermark_link = urlencode($options_row->watermark_link);
      $watermark_url = urlencode($options_row->watermark_url);
      $watermark_width = $options_row->watermark_width;
      $watermark_height = $options_row->watermark_height;
    }
    $gallery_row = $this->model->get_gallery_row_data($gallery_id);
    if (!$gallery_row) {
      echo BWGELibrary::message(__('There is no gallery selected or the gallery was deleted.', 'bwge'), 'error');
      return;
    }
    $image_rows = $this->model->get_image_rows_data($gallery_id, $sort_by, $order_by, $bwge);
    if (!$image_rows) {
      echo BWGELibrary::message(__('There are no images in this gallery.', 'bwge'), 'error');
    }
    $current_image_id = ($image_rows ? $image_rows[0]->id : 0);
    $play_pause_button_display = 'undefined';
    $filmstrip_thumb_margin = $theme_row->slideshow_filmstrip_thumb_margin;
    $margins_split = explode(" ", $filmstrip_thumb_margin);
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
    if (!$enable_slideshow_filmstrip) {
      if ($theme_row->slideshow_filmstrip_pos == 'left') {
        $theme_row->slideshow_filmstrip_pos = 'top';
      }
      if ($theme_row->slideshow_filmstrip_pos == 'right') {
        $theme_row->slideshow_filmstrip_pos = 'bottom';
      }
    }
    $left_or_top = 'left';
    $width_or_height = 'width';
    $outerWidth_or_outerHeight = 'outerWidth';
    if (!($filmstrip_direction == 'horizontal')) {
      $left_or_top = 'top';
      $width_or_height = 'height';
      $outerWidth_or_outerHeight = 'outerHeight';
    }

    ?>
    <style>
      #bwge_container1_<?php echo $bwge; ?> #bwge_container2_<?php echo $bwge; ?> #bwge_spider_popup_overlay_<?php echo $bwge; ?> {
        background-color: #<?php echo $theme_row->lightbox_overlay_bg_color; ?>;
        opacity: <?php echo number_format($theme_row->lightbox_overlay_bg_transparent / 100, 2, ".", ""); ?>;
        filter: Alpha(opacity=<?php echo $theme_row->lightbox_overlay_bg_transparent; ?>);
      }

      #bwge_container1_<?php echo $bwge; ?> {
        visibility: hidden;
      }
      #bwge_container1_<?php echo $bwge; ?> * {
        -moz-user-select: none;
        -khtml-user-select: none;
        -webkit-user-select: none;
        -ms-user-select: none;
        user-select: none;
      }
      #bwge_container1_<?php echo $bwge; ?> #bwge_container2_<?php echo $bwge; ?> .bwge_slideshow_image_wrap_<?php echo $bwge; ?> * {
        box-sizing: border-box;
        -moz-box-sizing: border-box;
        -webkit-box-sizing: border-box;
        /*backface-visibility: hidden;
        -webkit-backface-visibility: hidden;
        -moz-backface-visibility: hidden;
        -ms-backface-visibility: hidden;*/
      }
      #bwge_container1_<?php echo $bwge; ?> #bwge_container2_<?php echo $bwge; ?> .bwge_slideshow_image_wrap_<?php echo $bwge; ?> {
        background-color: #<?php echo $theme_row->slideshow_cont_bg_color; ?>;
        border-collapse: collapse;
        display: table;
        position: relative;
        text-align: center;
        width: <?php echo $image_width; ?>px;
        height: <?php echo $image_height; ?>px;
      }
      #bwge_container1_<?php echo $bwge; ?> #bwge_container2_<?php echo $bwge; ?> .bwge_slideshow_image_<?php echo $bwge; ?> {
        padding: 0 !important;
        margin: 0 !important;
        float: none !important;
        max-width: <?php echo $image_width - ($filmstrip_direction == 'vertical' ? $slideshow_filmstrip_width : 0); ?>px;
        max-height: <?php echo $image_height - ($filmstrip_direction == 'horizontal' ? $slideshow_filmstrip_height : 0); ?>px;
        vertical-align: middle;
      }
      #bwge_container1_<?php echo $bwge; ?> #bwge_container2_<?php echo $bwge; ?> .bwge_slideshow_embed_<?php echo $bwge; ?> {
        padding: 0 !important;
        margin: 0 !important;
        float: none !important;
        width: <?php echo $image_width - ($filmstrip_direction == 'vertical' ? $slideshow_filmstrip_width : 0); ?>px;
        height: <?php echo $image_height - ($filmstrip_direction == 'horizontal' ? $slideshow_filmstrip_height : 0); ?>px;
        vertical-align: middle;
        display: inline-block;
        text-align: center;
      }
      #bwge_container1_<?php echo $bwge; ?> #bwge_container2_<?php echo $bwge; ?> .bwge_slideshow_watermark_<?php echo $bwge; ?> {
        position: relative;
        z-index: 15;
      }
      #bwge_container1_<?php echo $bwge; ?> #bwge_container2_<?php echo $bwge; ?> #bwge_slideshow_play_pause_<?php echo $bwge; ?> {
        background: transparent url("<?php echo WD_BWGE_URL . '/images/blank.gif'; ?>") repeat scroll 0 0;
        bottom: 0;
        cursor: pointer;
        display: table;
        height: inherit;
        outline: medium none;
        position: absolute;
        width: 30%;
        left: 35%;
        z-index: 13;
      }
      #bwge_container1_<?php echo $bwge; ?> #bwge_container2_<?php echo $bwge; ?> #bwge_slideshow_play_pause_<?php echo $bwge; ?>:hover #bwge_slideshow_play_pause-ico_<?php echo $bwge; ?> {
        display: inline-block !important;
      }
      #bwge_container1_<?php echo $bwge; ?> #bwge_container2_<?php echo $bwge; ?> #bwge_slideshow_play_pause_<?php echo $bwge; ?>:hover span {
        position: relative;
        z-index: 13;
      }
      #bwge_container1_<?php echo $bwge; ?> #bwge_container2_<?php echo $bwge; ?> #bwge_slideshow_play_pause_<?php echo $bwge; ?> span {
        display: table-cell;
        text-align: center;
        vertical-align: middle;
      }
      #bwge_container1_<?php echo $bwge; ?> #bwge_container2_<?php echo $bwge; ?> #bwge_slideshow_play_pause-ico_<?php echo $bwge; ?> {  
        display: none !important;
        color: #<?php echo $theme_row->slideshow_rl_btn_color; ?>;        
        font-size: <?php echo $theme_row->slideshow_play_pause_btn_size; ?>px;
        cursor: pointer;
        position: relative;
        z-index: 13;
      }
      #bwge_container1_<?php echo $bwge; ?> #bwge_container2_<?php echo $bwge; ?> #bwge_slideshow_play_pause-ico_<?php echo $bwge; ?>:hover {  
        color: #<?php echo $theme_row->slideshow_close_rl_btn_hover_color; ?>;
        display: inline-block;
        position: relative;
        z-index: 13;
      }
      #bwge_container1_<?php echo $bwge; ?> #bwge_container2_<?php echo $bwge; ?> #bwge_spider_slideshow_left_<?php echo $bwge; ?>,
      #bwge_container1_<?php echo $bwge; ?> #bwge_container2_<?php echo $bwge; ?> #bwge_spider_slideshow_right_<?php echo $bwge; ?> {
        background: transparent url("<?php echo WD_BWGE_URL . '/images/blank.gif'; ?>") repeat scroll 0 0;
        bottom: 35%;
        cursor: pointer;
        display: inline;
        height: 30%;
        outline: medium none;
        position: absolute;
        width: 35%;
        /*z-index: 10130;*/
        z-index: 13;
      }
      #bwge_container1_<?php echo $bwge; ?> #bwge_container2_<?php echo $bwge; ?> #bwge_spider_slideshow_left_<?php echo $bwge; ?> {
        left: 0;
      }
      #bwge_container1_<?php echo $bwge; ?> #bwge_container2_<?php echo $bwge; ?> #bwge_spider_slideshow_right_<?php echo $bwge; ?> {
        right: 0;
      }
      #bwge_container1_<?php echo $bwge; ?> #bwge_container2_<?php echo $bwge; ?> #bwge_spider_slideshow_left_<?php echo $bwge; ?>:hover,
      #bwge_container1_<?php echo $bwge; ?> #bwge_container2_<?php echo $bwge; ?> #bwge_spider_slideshow_right_<?php echo $bwge; ?>:hover {
        visibility: visible;
      }
      #bwge_container1_<?php echo $bwge; ?> #bwge_container2_<?php echo $bwge; ?> #bwge_spider_slideshow_left_<?php echo $bwge; ?>:hover span {
        left: 20px;
      }
      #bwge_container1_<?php echo $bwge; ?> #bwge_container2_<?php echo $bwge; ?> #bwge_spider_slideshow_right_<?php echo $bwge; ?>:hover span {
        left: auto;
        right: 20px;
      }
      #bwge_container1_<?php echo $bwge; ?> #bwge_container2_<?php echo $bwge; ?> #bwge_spider_slideshow_left-ico_<?php echo $bwge; ?> span,
      #bwge_container1_<?php echo $bwge; ?> #bwge_container2_<?php echo $bwge; ?> #bwge_spider_slideshow_right-ico_<?php echo $bwge; ?> span {
        display: table-cell;
        text-align: center;
        vertical-align: middle;
        z-index: 13;
      }
      #bwge_container1_<?php echo $bwge; ?> #bwge_container2_<?php echo $bwge; ?> #bwge_spider_slideshow_left-ico_<?php echo $bwge; ?>,
      #bwge_container1_<?php echo $bwge; ?> #bwge_container2_<?php echo $bwge; ?> #bwge_spider_slideshow_right-ico_<?php echo $bwge; ?> {
        background-color: #<?php echo $theme_row->slideshow_rl_btn_bg_color; ?>;
        border-radius: <?php echo $theme_row->slideshow_rl_btn_border_radius; ?>;
        border: <?php echo $theme_row->slideshow_rl_btn_border_width; ?>px <?php echo $theme_row->slideshow_rl_btn_border_style; ?> #<?php echo $theme_row->slideshow_rl_btn_border_color; ?>;
        box-shadow: <?php echo $theme_row->slideshow_rl_btn_box_shadow; ?>;
        color: #<?php echo $theme_row->slideshow_rl_btn_color; ?>;
        height: <?php echo $theme_row->slideshow_rl_btn_height; ?>px;
        font-size: <?php echo $theme_row->slideshow_rl_btn_size; ?>px;
        width: <?php echo $theme_row->slideshow_rl_btn_width; ?>px;
        z-index: 13;
        -moz-box-sizing: content-box;
        box-sizing: content-box;
        cursor: pointer;
        display: table;
        line-height: 0;
        margin-top: -15px;
        position: absolute;
        top: 50%;
        /*z-index: 10135;*/
        opacity: <?php echo number_format($theme_row->slideshow_close_btn_transparent / 100, 2, ".", ""); ?>;
        filter: Alpha(opacity=<?php echo $theme_row->slideshow_close_btn_transparent; ?>);
      }
      #bwge_container1_<?php echo $bwge; ?> #bwge_container2_<?php echo $bwge; ?> #bwge_spider_slideshow_left-ico_<?php echo $bwge; ?>:hover,
      #bwge_container1_<?php echo $bwge; ?> #bwge_container2_<?php echo $bwge; ?> #bwge_spider_slideshow_right-ico_<?php echo $bwge; ?>:hover {
        color: #<?php echo $theme_row->slideshow_close_rl_btn_hover_color; ?>;
        cursor: pointer;
      }
      <?php
	  if($options_row->autohide_slideshow_navigation){?>
	  #bwge_spider_slideshow_left-ico_<?php echo $bwge; ?>{
		   left: -9999px;
	  }
	#bwge_spider_slideshow_right-ico_<?php echo $bwge; ?>{
		left: -9999px;
	 }
		
 <?php }
       else{ ?>
	    #bwge_spider_slideshow_left-ico_<?php echo $bwge; ?>{
		   left: 20px;
		}
	   #bwge_spider_slideshow_right-ico_<?php echo $bwge; ?>{
		   left: auto;
           right: 20px;
		}
	  <?php } ?>
      #bwge_container1_<?php echo $bwge; ?> #bwge_container2_<?php echo $bwge; ?> .bwge_slideshow_image_container_<?php echo $bwge; ?> {
        display: table;
        position: absolute;
        text-align: center;
        <?php echo $theme_row->slideshow_filmstrip_pos; ?>: <?php echo ($filmstrip_direction == 'horizontal' ? $slideshow_filmstrip_height : $slideshow_filmstrip_width); ?>px;
        vertical-align: middle;
        width: <?php echo $image_width; ?>px;
        height: <?php echo $image_height; ?>px;
      }
      #bwge_container1_<?php echo $bwge; ?> #bwge_container2_<?php echo $bwge; ?> .bwge_slideshow_filmstrip_container_<?php echo $bwge; ?> {
        display: <?php echo ($filmstrip_direction == 'horizontal'? 'table' : 'block'); ?>;
        height: <?php echo ($filmstrip_direction == 'horizontal'? $slideshow_filmstrip_height : $image_height); ?>px;
        position: absolute;
        width: <?php echo ($filmstrip_direction == 'horizontal' ? $image_width : $slideshow_filmstrip_width); ?>px;
        /*z-index: 10105;*/
        <?php echo $theme_row->slideshow_filmstrip_pos; ?>: 0;
      }
      #bwge_container1_<?php echo $bwge; ?> #bwge_container2_<?php echo $bwge; ?> .bwge_slideshow_filmstrip_<?php echo $bwge; ?> {
        <?php echo $left_or_top; ?>: 20px;
        overflow: hidden;
        position: absolute;
        <?php echo $width_or_height; ?>: <?php echo ($filmstrip_direction == 'horizontal' ? $image_width - 40 : $image_height - 40); ?>px;
        /*z-index: 10106;*/
      }
      #bwge_container1_<?php echo $bwge; ?> #bwge_container2_<?php echo $bwge; ?> .bwge_slideshow_filmstrip_thumbnails_<?php echo $bwge; ?> {
        height: <?php echo ($filmstrip_direction == 'horizontal' ? $slideshow_filmstrip_height : ($slideshow_filmstrip_height + $filmstrip_thumb_margin_hor) * count($image_rows)); ?>px;
        <?php echo $left_or_top; ?>: 0px;
        margin: 0 auto;
        overflow: hidden;
        position: relative;
        width: <?php echo ($filmstrip_direction == 'horizontal' ? ($slideshow_filmstrip_width + $filmstrip_thumb_margin_hor) * count($image_rows) : $slideshow_filmstrip_width); ?>px;
      }
      #bwge_container1_<?php echo $bwge; ?> #bwge_container2_<?php echo $bwge; ?> .bwge_slideshow_filmstrip_thumbnail_<?php echo $bwge; ?> {
        position: relative;
        background: none;
        border: <?php echo $theme_row->slideshow_filmstrip_thumb_border_width; ?>px <?php echo $theme_row->slideshow_filmstrip_thumb_border_style; ?> #<?php echo $theme_row->slideshow_filmstrip_thumb_border_color; ?>;
        border-radius: <?php echo $theme_row->slideshow_filmstrip_thumb_border_radius; ?>;
        cursor: pointer;
        float: left;
        height: <?php echo $slideshow_filmstrip_height; ?>px;
        margin: <?php echo $theme_row->slideshow_filmstrip_thumb_margin; ?>;
        width: <?php echo $slideshow_filmstrip_width; ?>px;
        overflow: hidden;
      }
      #bwge_container1_<?php echo $bwge; ?> #bwge_container2_<?php echo $bwge; ?> .bwge_slideshow_thumb_active_<?php echo $bwge; ?> {
        opacity: 1;
        filter: Alpha(opacity=100);
        border: <?php echo $theme_row->slideshow_filmstrip_thumb_active_border_width; ?>px solid #<?php echo $theme_row->slideshow_filmstrip_thumb_active_border_color; ?>;
      }
      #bwge_container1_<?php echo $bwge; ?> #bwge_container2_<?php echo $bwge; ?> .bwge_slideshow_thumb_deactive_<?php echo $bwge; ?> {
        opacity: <?php echo number_format($theme_row->slideshow_filmstrip_thumb_deactive_transparent / 100, 2, ".", ""); ?>;
        filter: Alpha(opacity=<?php echo $theme_row->slideshow_filmstrip_thumb_deactive_transparent; ?>);
      }
      #bwge_container1_<?php echo $bwge; ?> #bwge_container2_<?php echo $bwge; ?> .bwge_slideshow_filmstrip_thumbnail_img_<?php echo $bwge; ?> {
        display: block;
        opacity: 1;
        filter: Alpha(opacity=100);
        padding: 0 !important;
      }
      #bwge_container1_<?php echo $bwge; ?> #bwge_container2_<?php echo $bwge; ?> .bwge_slideshow_filmstrip_left_<?php echo $bwge; ?> {
        background-color: #<?php echo $theme_row->slideshow_filmstrip_rl_bg_color; ?>;
        cursor: pointer;
        display: <?php echo ($filmstrip_direction == 'horizontal' ? 'table-cell' : 'block') ?>;
        vertical-align: middle;
        <?php echo $width_or_height; ?>: 20px;
        /*z-index: 10106;*/
        <?php echo $left_or_top; ?>: 0;
        <?php echo ($filmstrip_direction == 'horizontal' ? '' : 'position: absolute;') ?>
        <?php echo ($filmstrip_direction == 'horizontal' ? '' : 'width: 100%;') ?> 
      }
      #bwge_container1_<?php echo $bwge; ?> #bwge_container2_<?php echo $bwge; ?> .bwge_slideshow_filmstrip_right_<?php echo $bwge; ?> {
        background-color: #<?php echo $theme_row->slideshow_filmstrip_rl_bg_color; ?>;
        cursor: pointer;
        <?php echo($filmstrip_direction == 'horizontal' ? 'right' : 'bottom') ?>: 0;
        <?php echo $width_or_height; ?>: 20px;
        display: <?php echo ($filmstrip_direction == 'horizontal' ? 'table-cell' : 'block') ?>;
        vertical-align: middle;
        /*z-index: 10106;*/
        <?php echo ($filmstrip_direction == 'horizontal' ? '' : 'position: absolute;') ?>
        <?php echo ($filmstrip_direction == 'horizontal' ? '' : 'width: 100%;') ?>
      }
      #bwge_container1_<?php echo $bwge; ?> #bwge_container2_<?php echo $bwge; ?> .bwge_slideshow_filmstrip_left_<?php echo $bwge; ?> i,
      #bwge_container1_<?php echo $bwge; ?> #bwge_container2_<?php echo $bwge; ?> .bwge_slideshow_filmstrip_right_<?php echo $bwge; ?> i {
        color: #<?php echo $theme_row->slideshow_filmstrip_rl_btn_color; ?>;
        font-size: <?php echo $theme_row->slideshow_filmstrip_rl_btn_size; ?>px;
      }
      #bwge_container1_<?php echo $bwge; ?> #bwge_container2_<?php echo $bwge; ?> .bwge_none_selectable_<?php echo $bwge; ?> {
        -webkit-touch-callout: none;
        -webkit-user-select: none;
        -khtml-user-select: none;
        -moz-user-select: none;
        -ms-user-select: none;
        user-select: none;
      }
      #bwge_container1_<?php echo $bwge; ?> #bwge_container2_<?php echo $bwge; ?> .bwge_slideshow_watermark_container_<?php echo $bwge; ?> {
        display: table-cell;
        margin: 0 auto;
        position: relative;
        vertical-align: middle;
      }
      #bwge_container1_<?php echo $bwge; ?> #bwge_container2_<?php echo $bwge; ?> .bwge_slideshow_watermark_spun_<?php echo $bwge; ?> {
        display: table-cell;
        overflow: hidden;
        position: relative;
        text-align: <?php echo $watermark_position[1]; ?>;
        vertical-align: <?php echo $watermark_position[0]; ?>;
      }
      #bwge_container1_<?php echo $bwge; ?> #bwge_container2_<?php echo $bwge; ?> .bwge_slideshow_title_spun_<?php echo $bwge; ?> {
        display: table-cell;
        overflow: hidden;
        position: relative;
        text-align: <?php echo $slideshow_title_position[1]; ?>;
        vertical-align: <?php echo $slideshow_title_position[0]; ?>;
      }
      #bwge_container1_<?php echo $bwge; ?> #bwge_container2_<?php echo $bwge; ?> .bwge_slideshow_description_spun_<?php echo $bwge; ?> {
        display: table-cell;
        overflow: hidden;
        position: relative;
        text-align: <?php echo $slideshow_description_position[1]; ?>;
        vertical-align: <?php echo $slideshow_description_position[0]; ?>;
      }
      #bwge_container1_<?php echo $bwge; ?> #bwge_container2_<?php echo $bwge; ?> .bwge_slideshow_watermark_image_<?php echo $bwge; ?> {
        padding: 0 !important;
        float: none !important;
        margin: 4px !important;
        max-height: <?php echo $watermark_height; ?>px;
        max-width: <?php echo $watermark_width; ?>px;
        opacity: <?php echo number_format($watermark_opacity / 100, 2, ".", ""); ?>;
        filter: Alpha(opacity=<?php echo $watermark_opacity; ?>);
        position: relative;
        z-index: 15;
      }
      #bwge_container1_<?php echo $bwge; ?> #bwge_container2_<?php echo $bwge; ?> .bwge_slideshow_watermark_text_<?php echo $bwge; ?>,
      #bwge_container1_<?php echo $bwge; ?> #bwge_container2_<?php echo $bwge; ?> .bwge_slideshow_watermark_text_<?php echo $bwge; ?>:hover {
        text-decoration: none;
        margin: 4px;
        font-size: <?php echo $watermark_font_size; ?>px;
        font-family: <?php echo $watermark_font; ?>;
        color: #<?php echo $watermark_color; ?> !important;
        opacity: <?php echo number_format($watermark_opacity / 100, 2, ".", ""); ?>;
        filter: Alpha(opacity=<?php echo $watermark_opacity; ?>);
        position: relative;
        z-index: 15;
      }
      #bwge_container1_<?php echo $bwge; ?> #bwge_container2_<?php echo $bwge; ?> .bwge_slideshow_title_text_<?php echo $bwge; ?> {
        text-decoration: none;
        font-size: <?php echo $theme_row->slideshow_title_font_size; ?>px;
        font-family: <?php echo $theme_row->slideshow_title_font; ?>;
        color: #<?php echo $theme_row->slideshow_title_color; ?> !important;
        opacity: <?php echo number_format($theme_row->slideshow_title_opacity / 100, 2, ".", ""); ?>;
        filter: Alpha(opacity=<?php echo $theme_row->slideshow_title_opacity; ?>);
        position: relative;
        z-index: 11;
        border-radius: <?php echo $theme_row->slideshow_title_border_radius; ?>;
        background-color: #<?php echo $theme_row->slideshow_title_background_color; ?>;
        padding: <?php echo $theme_row->slideshow_title_padding; ?>;
        <?php if($params['slideshow_title_full_width']) { ?>
        width: 100%;
        <?php } else { ?>
        margin: 5px;
        <?php } ?>
        display: inline-block;
        word-wrap: break-word;
        word-break: break-word;
        <?php if (!$enable_slideshow_filmstrip && $slideshow_title_position[0] == $theme_row->slideshow_filmstrip_pos) echo $theme_row->slideshow_filmstrip_pos . ':' . ($theme_row->slideshow_dots_height + 4) . 'px;'; ?>
      }
      #bwge_container1_<?php echo $bwge; ?> #bwge_container2_<?php echo $bwge; ?> .bwge_slideshow_description_text_<?php echo $bwge; ?> {
        text-decoration: none;
        font-size: <?php echo $theme_row->slideshow_description_font_size; ?>px;
        font-family: <?php echo $theme_row->slideshow_description_font; ?>;
        color: #<?php echo $theme_row->slideshow_description_color; ?> !important;
        opacity: <?php echo number_format($theme_row->slideshow_description_opacity / 100, 2, ".", ""); ?>;
        filter: Alpha(opacity=<?php echo $theme_row->slideshow_description_opacity; ?>);
        position: relative;
        z-index: 15;
        border-radius: <?php echo $theme_row->slideshow_description_border_radius; ?>;
        background-color: #<?php echo $theme_row->slideshow_description_background_color; ?>;
        padding: <?php echo $theme_row->slideshow_description_padding; ?>;
        margin: 5px;
        display: inline-block;
        word-wrap: break-word;
        word-break: break-word;
        <?php if (!$enable_slideshow_filmstrip && $slideshow_description_position[0] == $theme_row->slideshow_filmstrip_pos) echo $theme_row->slideshow_filmstrip_pos . ':' . ($theme_row->slideshow_dots_height + 4) . 'px;'; ?>        
      }
      #bwge_container1_<?php echo $bwge; ?> #bwge_container2_<?php echo $bwge; ?> .bwge_slideshow_description_text_<?php echo $bwge; ?> * {
        text-decoration: none;
        color: #<?php echo $theme_row->slideshow_description_color; ?> !important;                
      }
      #bwge_container1_<?php echo $bwge; ?> #bwge_container2_<?php echo $bwge; ?> .bwge_slide_container_<?php echo $bwge; ?> {
        display: table-cell;
        margin: 0 auto;
        position: absolute;
        vertical-align: middle;
        width: 100%;
        height: 100%;
      }
      #bwge_container1_<?php echo $bwge; ?> #bwge_container2_<?php echo $bwge; ?> .bwge_slide_bg_<?php echo $bwge; ?> {
        margin: 0 auto;
        width: inherit;
        height: inherit;
      }
      #bwge_container1_<?php echo $bwge; ?> #bwge_container2_<?php echo $bwge; ?> .bwge_slider_<?php echo $bwge; ?> {
        height: inherit;
        width: inherit;
      }
      #bwge_container1_<?php echo $bwge; ?> #bwge_container2_<?php echo $bwge; ?> .bwge_slideshow_image_spun_<?php echo $bwge; ?> {
        width: inherit;
        height: inherit;
        display: table-cell;
        filter: Alpha(opacity=100);
        opacity: 1;
        position: absolute;
        vertical-align: middle;
        z-index: 2;
      }
      #bwge_container1_<?php echo $bwge; ?> #bwge_container2_<?php echo $bwge; ?> .bwge_slideshow_image_second_spun_<?php echo $bwge; ?> {
        width: inherit;
        height: inherit;
        display: table-cell;
        filter: Alpha(opacity=0);
        opacity: 0;
        position: absolute;
        vertical-align: middle;
        z-index: 1;
      }
      #bwge_container1_<?php echo $bwge; ?> #bwge_container2_<?php echo $bwge; ?> .bwge_grid_<?php echo $bwge; ?> {
        display: none;
        height: 100%;
        overflow: hidden;
        position: absolute;
        width: 100%;
      }
      #bwge_container1_<?php echo $bwge; ?> #bwge_container2_<?php echo $bwge; ?> .bwge_gridlet_<?php echo $bwge; ?> {
        opacity: 1;
        filter: Alpha(opacity=100);
        position: absolute;
      }
      #bwge_container1_<?php echo $bwge; ?> #bwge_container2_<?php echo $bwge; ?> .bwge_slideshow_dots_<?php echo $bwge; ?> {
        display: inline-block;
        position: relative;
        width: <?php echo $theme_row->slideshow_dots_width; ?>px;
        height: <?php echo $theme_row->slideshow_dots_height; ?>px;
        border-radius: <?php echo $theme_row->slideshow_dots_border_radius; ?>;
        background: #<?php echo $theme_row->slideshow_dots_background_color; ?>;
        margin: <?php echo $theme_row->slideshow_dots_margin; ?>px;
        cursor: pointer;
        overflow: hidden;
        z-index: 17;
      }
      #bwge_container1_<?php echo $bwge; ?> #bwge_container2_<?php echo $bwge; ?> .bwge_slideshow_dots_container_<?php echo $bwge; ?> {
        display: block;
        overflow: hidden;
        position: absolute;
        width: <?php echo $image_width; ?>px;
        <?php echo $theme_row->slideshow_filmstrip_pos; ?>: 0;
        z-index: 17;
      }
      #bwge_container1_<?php echo $bwge; ?> #bwge_container2_<?php echo $bwge; ?> .bwge_slideshow_dots_thumbnails_<?php echo $bwge; ?> {
        left: 0px;
        font-size: 0;
        margin: 0 auto;
        overflow: hidden;
        position: relative;
        height: <?php echo ($theme_row->slideshow_dots_height + $theme_row->slideshow_dots_margin * 2); ?>px;
        width: <?php echo ($theme_row->slideshow_dots_width + $theme_row->slideshow_dots_margin * 2) * count($image_rows); ?>px;
      }
      #bwge_container1_<?php echo $bwge; ?> #bwge_container2_<?php echo $bwge; ?> .bwge_slideshow_dots_active_<?php echo $bwge; ?> {
        background: #<?php echo $theme_row->slideshow_dots_active_background_color; ?>;
        opacity: 1;
        filter: Alpha(opacity=100);
        border: <?php echo $theme_row->slideshow_dots_active_border_width; ?>px solid #<?php echo $theme_row->slideshow_dots_active_border_color; ?>;
      }
      #bwge_container1_<?php echo $bwge; ?> #bwge_container2_<?php echo $bwge; ?> .bwge_slideshow_dots_deactive_<?php echo $bwge; ?> {
      }
      #bwge_container1_<?php echo $bwge; ?> #bwge_container2_<?php echo $bwge; ?> .bwge_slideshow_image_spun1_<?php echo $bwge; ?> {
        display: table; 
        width: inherit; 
        height: inherit;
      }
      #bwge_container1_<?php echo $bwge; ?> #bwge_container2_<?php echo $bwge; ?> .bwge_slideshow_image_spun2_<?php echo $bwge; ?> {
        display: table-cell; 
        vertical-align: middle; 
        text-align: center;
      }
      .bwge_ecommerce_slideshow<?php echo $bwge; ?>{
        position: absolute;
        <?php if($theme_row->slideshow_ecommerce_icon_pos == "top_left"){
        ?>
            top: 12px;
            left: 20px;
        <?php
        }
        else if($theme_row->slideshow_ecommerce_icon_pos == "top_right"){
        ?>
            top: 12px;
            right: 20px;
        <?php
        } 
        else if($theme_row->slideshow_ecommerce_icon_pos == "bottom_left"){ 
        ?>
            bottom: 12px;
            left: 20px;
        <?php
        }
        else if($theme_row->slideshow_ecommerce_icon_pos == "bottom_right"){   
        ?>
            bottom: 12px;
            right: 20px;
        <?php
        }
        ?>        
        font-size: <?php echo $theme_row->slideshow_ecommerce_icon_size; ?>px;
        z-index: 9999;       
        cursor:pointer;
        color:#<?php echo $theme_row->slideshow_ecommerce_icon_color; ?>;
      }
    </style>
    <script>
      var bwgedata_<?php echo $bwge; ?> = [];
      var event_stack_<?php echo $bwge; ?> = [];
      <?php
      foreach ($image_rows as $key => $image_row) {
        if ($image_row->id == $current_image_id) {
          $current_image_alt = $image_row->alt;
          $current_image_description = str_replace(array("\r\n", "\n", "\r"), esc_html('<br />'), $image_row->description);
        }
        ?>
        bwgedata_<?php echo $bwge; ?>["<?php echo $key; ?>"] = [];
        bwgedata_<?php echo $bwge; ?>["<?php echo $key; ?>"]["id"] = "<?php echo $image_row->id; ?>";
        bwgedata_<?php echo $bwge; ?>["<?php echo $key; ?>"]["alt"] = "<?php echo $image_row->alt; ?>";
        bwgedata_<?php echo $bwge; ?>["<?php echo $key; ?>"]["description"] = "<?php echo str_replace(array("\r\n", "\n", "\r"), esc_html('<br />'), $image_row->description); ?>";
        bwgedata_<?php echo $bwge; ?>["<?php echo $key; ?>"]["image_url"] = "<?php echo $image_row->image_url; ?>";
        bwgedata_<?php echo $bwge; ?>["<?php echo $key; ?>"]["thumb_url"] = "<?php echo $image_row->thumb_url; ?>";
        bwgedata_<?php echo $bwge; ?>["<?php echo $key; ?>"]["date"] = "<?php echo $image_row->date; ?>";
        bwgedata_<?php echo $bwge; ?>["<?php echo $key; ?>"]["is_embed"] = "<?php echo (preg_match('/EMBED/',$image_row->filetype)==1 ? true :false); ?>";
        bwgedata_<?php echo $bwge; ?>["<?php echo $key; ?>"]["is_embed_video"] = "<?php echo (((preg_match('/EMBED/',$image_row->filetype)==1) && (preg_match('/_VIDEO/',$image_row->filetype)==1)) ? true :false); ?>";
        <?php
      }
      ?>    
    </script>
    <div id="bwge_container1_<?php echo $bwge; ?>">
      <div id="bwge_container2_<?php echo $bwge; ?>">
        <div class="bwge_slideshow_image_wrap_<?php echo $bwge; ?>">
          <?php
          $current_pos = 0;

            ?>
            <div class="bwge_slideshow_dots_container_<?php echo $bwge; ?>">
              <div class="bwge_slideshow_dots_thumbnails_<?php echo $bwge; ?>">
                <?php
                foreach ($image_rows as $key => $image_row) {
                  if ($image_row->id == $current_image_id) {
                    $current_pos = $key * ($slideshow_filmstrip_width + 2);
                    $current_key = $key;
                  }
                ?>
                <span id="bwge_dots_<?php echo $key; ?>_<?php echo $bwge; ?>" class="bwge_slideshow_dots_<?php echo $bwge; ?> <?php echo (($image_row->id == $current_image_id) ? 'bwge_slideshow_dots_active_' . $bwge : 'bwge_slideshow_dots_deactive_' . $bwge); ?>" onclick="bwge_change_image_<?php echo $bwge; ?>(parseInt(jQuery('#bwge_current_image_key_<?php echo $bwge; ?>').val()), '<?php echo $key; ?>', bwgedata_<?php echo $bwge; ?>)" image_id="<?php echo $image_row->id; ?>" image_key="<?php echo $key; ?>"></span>
                <?php
                }
                ?>
              </div>
            </div>

          <div id="bwge_slideshow_image_container_<?php echo $bwge; ?>" class="bwge_slideshow_image_container_<?php echo $bwge; ?>">        
            <div class="bwge_slide_container_<?php echo $bwge; ?>">
              <div class="bwge_slide_bg_<?php echo $bwge; ?>">
                <div class="bwge_slider_<?php echo $bwge; ?>">
                <?php
                foreach ($image_rows as $key => $image_row) {
                  
                  $is_embed = preg_match('/EMBED/',$image_row->filetype)==1 ? true :false;
                  $is_embed_instagram_post = preg_match('/INSTAGRAM_POST/',$image_row->filetype)==1 ? true :false;
                  if ($image_row->id == $current_image_id) {
                    $current_key = $key;
                    ?>
                    <span class="bwge_slideshow_image_spun_<?php echo $bwge; ?>" id="image_id_<?php echo $bwge; ?>_<?php echo $image_row->id; ?>">
                      <span class="bwge_slideshow_image_spun1_<?php echo $bwge; ?>">
                        <span class="bwge_slideshow_image_spun2_<?php echo $bwge; ?>">
                          <?php 
                            if (!$is_embed) {
                            ?>
                             <?php if($slideshow_ecommerce_icon == 1 && $image_row->pricelist_id){ ?>
                                <i title="<?php echo __('Ecommerce', 'bwge'); ?>" class="bwge_ctrl_btn bwge_ecommerce_slideshow<?php echo $bwge; ?> fa fa-shopping-cart" ></i>
                            <?php 
                            }
                            ?>
                            <img id="bwge_slideshow_image_<?php echo $bwge; ?>" class="bwge_slideshow_image_<?php echo $bwge; ?>" src="<?php echo site_url() . '/' . $WD_BWGE_UPLOAD_DIR . $image_row->image_url; ?>" image_id="<?php echo $image_row->id; ?>" alt="<?php echo $image_row->alt; ?>"/>
                            <?php 
                            }
                            else{  /*$is_embed*/?>
                            <span id="bwge_slideshow_image_<?php echo $bwge; ?>" class="bwge_slideshow_embed_<?php echo $bwge; ?>" image_id="<?php echo $image_row->id; ?>">
                            <?php
                              if($is_embed_instagram_post){
                                $post_width = $image_width - ($filmstrip_direction == 'vertical' ? $slideshow_filmstrip_width : 0);
                                $post_height = $image_height - ($filmstrip_direction == 'horizontal' ? $slideshow_filmstrip_height : 0);
                                if($post_height <$post_width +88 ){
                                  $post_width =$post_height -88; 
                                }
                                else{
                                 $post_height =$post_width +88;  
                                }
                                BWGELibraryEmbed::display_embed($image_row->filetype, $image_row->image_url,  $image_row->filename, array('class'=>"bwge_embed_frame_".$bwge, 'frameborder'=>"0", 'style'=>"width:".$post_width."px; height:".$post_height."px; vertical-align:middle; display:inline-block; position:relative;"));
                              }
                              else{
                              BWGELibraryEmbed::display_embed($image_row->filetype, $image_row->image_url, $image_row->filename, array('class'=>"bwge_embed_frame_".$bwge, 'frameborder'=>"0", 'allowfullscreen'=>"allowfullscreen", 'style'=>"width:inherit; height:inherit; vertical-align:middle; display:table-cell;"));
                              }
                              ?>         
                            </span>
                            <?php
                            }
                          ?>
                        </span>
                      </span>
                    </span>
                    <input type="hidden" id="bwge_current_image_key_<?php echo $bwge; ?>" value="<?php echo $key; ?>" />
                    <?php
                  }
                  else {
                    ?>
                    <span class="bwge_slideshow_image_second_spun_<?php echo $bwge; ?>" id="image_id_<?php echo $bwge; ?>_<?php echo $image_row->id; ?>">
                      <span class="bwge_slideshow_image_spun1_<?php echo $bwge; ?>">
                        <span class="bwge_slideshow_image_spun2_<?php echo $bwge; ?>">
                          <?php 
                            if (! $is_embed) {
                            ?>
                             <?php if($slideshow_ecommerce_icon == 1 && $image_row->pricelist_id){ ?>
                                <i title="<?php echo __('Ecommerce', 'bwge'); ?>" class="bwge_ctrl_btn bwge_ecommerce_slideshow<?php echo $bwge; ?> fa fa-shopping-cart" ></i>
                            <?php 
                            }
                            ?>
                            <img class="bwge_slideshow_image_<?php echo $bwge; ?>" src="<?php echo site_url() . '/' . $WD_BWGE_UPLOAD_DIR . $image_row->image_url; ?>" alt="<?php echo $image_row->alt; ?>"/>
                          <?php 
                            }
                            else {   /*$is_embed*/ ?>
                            <span class="bwge_slideshow_embed_<?php echo $bwge; ?>">
                                <?php
                              if($is_embed_instagram_post){
                                $post_width = $image_width - ($filmstrip_direction == 'vertical' ? $slideshow_filmstrip_width : 0);
                                $post_height = $image_height - ($filmstrip_direction == 'horizontal' ? $slideshow_filmstrip_height : 0);
                                if($post_height < $post_width +88 ){
                                  $post_width = $post_height - 88; 
                                }
                                else{
                                 $post_height =$post_width +88;  
                                }
                                BWGELibraryEmbed::display_embed($image_row->filetype, $image_row->image_url, $image_row->filename, array('class'=>"bwge_embed_frame_".$bwge, 'frameborder'=>"0", 'style'=>"width:".$post_width."px; height:".$post_height."px; vertical-align:middle; display:inline-block; position:relative;"));
                              }
                              else{
                              BWGELibraryEmbed::display_embed($image_row->filetype, $image_row->image_url, $image_row->filename, array('class'=>"bwge_embed_frame_".$bwge, 'frameborder'=>"0", 'allowfullscreen'=>"allowfullscreen", 'style'=>"width:inherit; height:inherit; vertical-align:middle; display:table-cell;"));
                              }
                              ?>                        
                            </span>
                            <?php 
                            }
                          ?>
                        </span>
                      </span>
                    </span>
                    <?php
                  }
                }
                ?>
                </div>
              </div>
            </div>
            <?php
              if ($enable_slideshow_ctrl) {
                ?>
              <a id="bwge_spider_slideshow_left_<?php echo $bwge; ?>" onclick="bwge_change_image_<?php echo $bwge; ?>(parseInt(jQuery('#bwge_current_image_key_<?php echo $bwge; ?>').val()), (parseInt(jQuery('#bwge_current_image_key_<?php echo $bwge; ?>').val()) + bwgedata_<?php echo $bwge; ?>.length - iterator_<?php echo $bwge; ?>()) % bwgedata_<?php echo $bwge; ?>.length, bwgedata_<?php echo $bwge; ?>); return false;"><span id="bwge_spider_slideshow_left-ico_<?php echo $bwge; ?>"><span><i class="bwge_slideshow_prev_btn_<?php echo $bwge; ?> fa <?php echo $theme_row->slideshow_rl_btn_style; ?>-left"></i></span></span></a>
              <span id="bwge_slideshow_play_pause_<?php echo $bwge; ?>" style="display: <?php echo $play_pause_button_display; ?>;"><span><span id="bwge_slideshow_play_pause-ico_<?php echo $bwge; ?>"><i class="bwge_ctrl_btn_<?php echo $bwge; ?> bwge_slideshow_play_pause_<?php echo $bwge; ?> fa fa-play"></i></span></span></span>
              <a id="bwge_spider_slideshow_right_<?php echo $bwge; ?>" onclick="bwge_change_image_<?php echo $bwge; ?>(parseInt(jQuery('#bwge_current_image_key_<?php echo $bwge; ?>').val()), (parseInt(jQuery('#bwge_current_image_key_<?php echo $bwge; ?>').val()) + iterator_<?php echo $bwge; ?>()) % bwgedata_<?php echo $bwge; ?>.length, bwgedata_<?php echo $bwge; ?>); return false;"><span id="bwge_spider_slideshow_right-ico_<?php echo $bwge; ?>"><span><i class="bwge_slideshow_next_btn_<?php echo $bwge; ?> fa <?php echo $theme_row->slideshow_rl_btn_style; ?>-right"></i></span></span></a>
              <?php
              }
            ?>
          </div>
          <?php
          if ($watermark_type != 'none') {
          ?>
          <div class="bwge_slideshow_image_container_<?php echo $bwge; ?>" style="position: absolute;">
            <div class="bwge_slideshow_watermark_container_<?php echo $bwge; ?>">
              <div style="display:table; margin:0 auto;">
                <span class="bwge_slideshow_watermark_spun_<?php echo $bwge; ?>" id="bwge_slideshow_watermark_container_<?php echo $bwge; ?>">
                  <?php
                  if ($watermark_type == 'image') {
                  ?>
                  <a href="<?php echo urldecode($watermark_link); ?>" target="_blank">
                    <img class="bwge_slideshow_watermark_image_<?php echo $bwge; ?> bwge_slideshow_watermark_<?php echo $bwge; ?>" src="<?php echo $watermark_url; ?>" />
                  </a>
                  <?php
                  }
                  elseif ($watermark_type == 'text') {
                  ?>
                  <a class="bwge_none_selectable_<?php echo $bwge; ?> bwge_slideshow_watermark_text_<?php echo $bwge; ?> bwge_slideshow_watermark_<?php echo $bwge; ?>" target="_blank" href="<?php echo $watermark_link; ?>"><?php echo $watermark_text; ?></a>
                  <?php
                  }
                  ?>
                </span>
              </div>
            </div>
          </div>      
          <?php
          }
          if ($enable_image_title) {
          ?>
          <div class="bwge_slideshow_image_container_<?php echo $bwge; ?>" style="position: absolute;">
            <div class="bwge_slideshow_watermark_container_<?php echo $bwge; ?>">
              <div style="display:table; margin:0 auto;">
                <span class="bwge_slideshow_title_spun_<?php echo $bwge; ?>">
                  <div class="bwge_slideshow_title_text_<?php echo $bwge; ?>" style="<?php if (!$current_image_alt) echo 'display:none;'; ?>">
                    <?php echo html_entity_decode($current_image_alt); ?>
                  </div>
                </span>
              </div>
            </div>
          </div>
          <?php 
          }
          if ($enable_image_description) {
          ?>
          <div class="bwge_slideshow_image_container_<?php echo $bwge; ?>" style="position: absolute;">
            <div class="bwge_slideshow_watermark_container_<?php echo $bwge; ?>">
              <div style="display:table; margin:0 auto;">
                <span class="bwge_slideshow_description_spun_<?php echo $bwge; ?>">
                  <div class="bwge_slideshow_description_text_<?php echo $bwge; ?>" style="<?php if (!$current_image_description) echo 'display:none;'; ?>">
                    <?php echo html_entity_decode(str_replace("\r\n", esc_html('<br />'), $current_image_description)); ?>
                  </div>
                </span>
              </div>
            </div>
          </div>
          <?php 
          }
          if ($enable_slideshow_music) {
            ?>
            <audio id="bwge_audio_<?php echo $bwge; ?>" src="<?php echo site_url() . '/' . $WD_BWGE_UPLOAD_DIR . $slideshow_music_url ?>" loop volume="1.0"></audio>
            <?php 
          }
          ?>
        </div>
        <div id="bwge_spider_popup_loading_<?php echo $bwge; ?>" class="bwge_spider_popup_loading"></div>
        <div id="bwge_spider_popup_overlay_<?php echo $bwge; ?>" class="bwge_spider_popup_overlay" onclick=""></div>
        
      </div>
    </div>
    <div class="bwge_pricelist_container bwge_pricelist_container<?php echo $bwge; ?>" style="display:none;">
    </div>
 
    <script>
      var bwge_trans_in_progress_<?php echo $bwge; ?> = false;
      var bwge_transition_duration_<?php echo $bwge; ?> = <?php echo (($slideshow_interval < 4) && ($slideshow_interval != 0)) ? ($slideshow_interval * 1000) / 4 : 800; ?>;
      var bwge_playInterval_<?php echo $bwge; ?>;
      /* Stop autoplay.*/
      window.clearInterval(bwge_playInterval_<?php echo $bwge; ?>);
      /* Set watermark container size.*/
      function bwge_change_watermark_container_<?php echo $bwge; ?>() {
        jQuery(".bwge_slider_<?php echo $bwge; ?>").children().each(function() {
          if (jQuery(this).css("zIndex") == 2) {
            var bwge_current_image_span = jQuery(this).find("img");
            if (!bwge_current_image_span.length) {
              bwge_current_image_span = jQuery(this).find("iframe");
            }
            if (!bwge_current_image_span.length) {
              bwge_current_image_span = jQuery(this).find("video");
            }
            var width = bwge_current_image_span.width();
            var height = bwge_current_image_span.height();
            jQuery(".bwge_slideshow_watermark_spun_<?php echo $bwge; ?>").width(width);
            jQuery(".bwge_slideshow_watermark_spun_<?php echo $bwge; ?>").height(height);
            jQuery(".bwge_slideshow_title_spun_<?php echo $bwge; ?>").width(width);
            jQuery(".bwge_slideshow_title_spun_<?php echo $bwge; ?>").height(height);
            jQuery(".bwge_slideshow_description_spun_<?php echo $bwge; ?>").width(width);
            jQuery(".bwge_slideshow_description_spun_<?php echo $bwge; ?>").height(height);
            jQuery(".bwge_slideshow_watermark_<?php echo $bwge; ?>").css({display: ''});
            if (jQuery.trim(jQuery(".bwge_slideshow_title_text_<?php echo $bwge; ?>").text())) {
              jQuery(".bwge_slideshow_title_text_<?php echo $bwge; ?>").css({display: ''});
            }
            if (jQuery.trim(jQuery(".bwge_slideshow_description_text_<?php echo $bwge; ?>").text())) {
              jQuery(".bwge_slideshow_description_text_<?php echo $bwge; ?>").css({display: ''});
            }
          }
        });
      }
      var bwge_current_key_<?php echo $bwge; ?> = '<?php echo (isset($current_key) ? $current_key : ''); ?>';
      var bwge_current_filmstrip_pos_<?php echo $bwge; ?> = <?php echo $current_pos; ?>;
      /* Set filmstrip initial position.*/
      function bwge_set_filmstrip_pos_<?php echo $bwge; ?>(filmStripWidth) {
        var selectedImagePos = -bwge_current_filmstrip_pos_<?php echo $bwge; ?> - (jQuery(".bwge_slideshow_filmstrip_thumbnail_<?php echo $bwge; ?>").<?php echo $width_or_height; ?>() + <?php echo $filmstrip_thumb_margin_hor; ?>) / 2;
        var imagesContainerLeft = Math.min(0, Math.max(filmStripWidth - jQuery(".bwge_slideshow_filmstrip_thumbnails_<?php echo $bwge; ?>").<?php echo $width_or_height; ?>(), selectedImagePos + filmStripWidth / 2));
        jQuery(".bwge_slideshow_filmstrip_thumbnails_<?php echo $bwge; ?>").animate({
            <?php echo $left_or_top; ?>: imagesContainerLeft
          }, {
            duration: 500,
            complete: function () { bwge_filmstrip_arrows_<?php echo $bwge; ?>(); }
          });
      }
      function bwge_move_filmstrip_<?php echo $bwge; ?>() {
        var image_left = jQuery(".bwge_slideshow_thumb_active_<?php echo $bwge; ?>").position().<?php echo $left_or_top; ?>;
        var image_right = jQuery(".bwge_slideshow_thumb_active_<?php echo $bwge; ?>").position().<?php echo $left_or_top; ?> + jQuery(".bwge_slideshow_thumb_active_<?php echo $bwge; ?>").<?php echo $outerWidth_or_outerHeight; ?>(true);
        var bwge_filmstrip_width = jQuery(".bwge_slideshow_filmstrip_<?php echo $bwge; ?>").<?php echo $outerWidth_or_outerHeight; ?>(true);
        var bwge_filmstrip_thumbnails_width = jQuery(".bwge_slideshow_filmstrip_thumbnails_<?php echo $bwge; ?>").<?php echo $outerWidth_or_outerHeight; ?>(true);
        var long_filmstrip_cont_left = jQuery(".bwge_slideshow_filmstrip_thumbnails_<?php echo $bwge; ?>").position().<?php echo $left_or_top; ?>;
        var long_filmstrip_cont_right = Math.abs(jQuery(".bwge_slideshow_filmstrip_thumbnails_<?php echo $bwge; ?>").position().<?php echo $left_or_top; ?>) + bwge_filmstrip_width;
        if (bwge_filmstrip_width > bwge_filmstrip_thumbnails_width) {
          return;
        }
        if (image_left < Math.abs(long_filmstrip_cont_left)) {
          jQuery(".bwge_slideshow_filmstrip_thumbnails_<?php echo $bwge; ?>").animate({
            <?php echo $left_or_top; ?>: -image_left
          }, {
            duration: 500,
            complete: function () { bwge_filmstrip_arrows_<?php echo $bwge; ?>(); }
          });
        }
        else if (image_right > long_filmstrip_cont_right) {
          jQuery(".bwge_slideshow_filmstrip_thumbnails_<?php echo $bwge; ?>").animate({
            <?php echo $left_or_top; ?>: -(image_right - bwge_filmstrip_width)
          }, {
            duration: 500,
            complete: function () { bwge_filmstrip_arrows_<?php echo $bwge; ?>(); }
          });
        }
      }
      function bwge_move_dots_<?php echo $bwge; ?>() {
        var image_left = jQuery(".bwge_slideshow_dots_active_<?php echo $bwge; ?>").position().left;
        var image_right = jQuery(".bwge_slideshow_dots_active_<?php echo $bwge; ?>").position().left + jQuery(".bwge_slideshow_dots_active_<?php echo $bwge; ?>").outerWidth(true);
        var bwge_dots_width = jQuery(".bwge_slideshow_dots_container_<?php echo $bwge; ?>").outerWidth(true);
        var bwge_dots_thumbnails_width = jQuery(".bwge_slideshow_dots_thumbnails_<?php echo $bwge; ?>").outerWidth(false);
        var long_filmstrip_cont_left = jQuery(".bwge_slideshow_dots_thumbnails_<?php echo $bwge; ?>").position().left;
        var long_filmstrip_cont_right = Math.abs(jQuery(".bwge_slideshow_dots_thumbnails_<?php echo $bwge; ?>").position().left) + bwge_dots_width;
        if (bwge_dots_width > bwge_dots_thumbnails_width) {
          return;
        }
        if (image_left < Math.abs(long_filmstrip_cont_left)) {
          jQuery(".bwge_slideshow_dots_thumbnails_<?php echo $bwge; ?>").animate({
            left: -image_left
          }, {
            duration: 500,
            complete: function () {  }
          });
        }
        else if (image_right > long_filmstrip_cont_right) {
          jQuery(".bwge_slideshow_dots_thumbnails_<?php echo $bwge; ?>").animate({
            left: -(image_right - bwge_dots_width)
          }, {
            duration: 500,
            complete: function () {  }
          });
        }
      }
      /* Show/hide filmstrip arrows.*/
      function bwge_filmstrip_arrows_<?php echo $bwge; ?>() {
        if (jQuery(".bwge_slideshow_filmstrip_thumbnails_<?php echo $bwge; ?>").<?php echo $width_or_height; ?>() < jQuery(".bwge_slideshow_filmstrip_<?php echo $bwge; ?>").<?php echo $width_or_height; ?>()) {
          jQuery(".bwge_slideshow_filmstrip_left_<?php echo $bwge; ?>").hide();
          jQuery(".bwge_slideshow_filmstrip_right_<?php echo $bwge; ?>").hide();
        }
        else {
          jQuery(".bwge_slideshow_filmstrip_left_<?php echo $bwge; ?>").show();
          jQuery(".bwge_slideshow_filmstrip_right_<?php echo $bwge; ?>").show();
        }
      }
      function bwge_testBrowser_cssTransitions_<?php echo $bwge; ?>() {
        return bwge_testDom_<?php echo $bwge; ?>('Transition');
      }
      function bwge_testBrowser_cssTransforms3d_<?php echo $bwge; ?>() {
        return bwge_testDom_<?php echo $bwge; ?>('Perspective');
      }
      function bwge_testDom_<?php echo $bwge; ?>(prop) {
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
      function bwge_cube_<?php echo $bwge; ?>(tz, ntx, nty, nrx, nry, wrx, wry, current_image_class, next_image_class, direction) {
        /* If browser does not support 3d transforms/CSS transitions.*/
        if (!bwge_testBrowser_cssTransitions_<?php echo $bwge; ?>()) {
          return bwge_fallback_<?php echo $bwge; ?>(current_image_class, next_image_class, direction);
        }
        if (!bwge_testBrowser_cssTransforms3d_<?php echo $bwge; ?>()) {
          return bwge_fallback3d_<?php echo $bwge; ?>(current_image_class, next_image_class, direction);
        }
        bwge_trans_in_progress_<?php echo $bwge; ?> = true;
        /* Set active thumbnail.*/
        jQuery(".bwge_slideshow_filmstrip_thumbnail_<?php echo $bwge; ?>").removeClass("bwge_slideshow_thumb_active_<?php echo $bwge; ?>").addClass("bwge_slideshow_thumb_deactive_<?php echo $bwge; ?>");
        jQuery("#bwge_filmstrip_thumbnail_" + bwge_current_key_<?php echo $bwge; ?> + "_<?php echo $bwge; ?>").removeClass("bwge_slideshow_thumb_deactive_<?php echo $bwge; ?>").addClass("bwge_slideshow_thumb_active_<?php echo $bwge; ?>");
        jQuery(".bwge_slideshow_dots_<?php echo $bwge; ?>").removeClass("bwge_slideshow_dots_active_<?php echo $bwge; ?>").addClass("bwge_slideshow_dots_deactive_<?php echo $bwge; ?>");
        jQuery("#bwge_dots_" + bwge_current_key_<?php echo $bwge; ?> + "_<?php echo $bwge; ?>").removeClass("bwge_slideshow_dots_deactive_<?php echo $bwge; ?>").addClass("bwge_slideshow_dots_active_<?php echo $bwge; ?>");
        jQuery(".bwge_slide_bg_<?php echo $bwge; ?>").css('perspective', 1000);
        jQuery(current_image_class).css({
          transform : 'translateZ(' + tz + 'px)',
          backfaceVisibility : 'hidden'
        });
        jQuery(next_image_class).css({
          opacity : 1,
          filter: 'Alpha(opacity=100)',
          zIndex: 2,
          backfaceVisibility : 'hidden',
          transform : 'translateY(' + nty + 'px) translateX(' + ntx + 'px) rotateY('+ nry +'deg) rotateX('+ nrx +'deg)'
        });
        jQuery(".bwge_slider_<?php echo $bwge; ?>").css({
          transform: 'translateZ(-' + tz + 'px)',
          transformStyle: 'preserve-3d'
        });
        /* Execution steps.*/
        setTimeout(function () {
          jQuery(".bwge_slider_<?php echo $bwge; ?>").css({
            transition: 'all ' + bwge_transition_duration_<?php echo $bwge; ?> + 'ms ease-in-out',
            transform: 'translateZ(-' + tz + 'px) rotateX('+ wrx +'deg) rotateY('+ wry +'deg)'
          });
        }, 20);
        /* After transition.*/
        jQuery(".bwge_slider_<?php echo $bwge; ?>").one('webkitTransitionEnd transitionend otransitionend oTransitionEnd mstransitionend', jQuery.proxy(bwge_after_trans));
        function bwge_after_trans() {
          /*if (bwge_from_focus_<?php echo $bwge; ?>) {
            bwge_from_focus_<?php echo $bwge; ?> = false;
            return;
          }*/
          jQuery(current_image_class).removeAttr('style');
          jQuery(next_image_class).removeAttr('style');
          jQuery(".bwge_slider_<?php echo $bwge; ?>").removeAttr('style');
          jQuery(current_image_class).css({'opacity' : 0, filter: 'Alpha(opacity=0)', 'z-index': 1});
          jQuery(next_image_class).css({'opacity' : 1, filter: 'Alpha(opacity=100)', 'z-index' : 2});
          bwge_change_watermark_container_<?php echo $bwge; ?>();
          bwge_trans_in_progress_<?php echo $bwge; ?> = false;
          if (typeof event_stack_<?php echo $bwge; ?> !== 'undefined') {
            if (event_stack_<?php echo $bwge; ?>.length > 0) {
              key = event_stack_<?php echo $bwge; ?>[0].split("-");
              event_stack_<?php echo $bwge; ?>.shift();
              bwge_change_image_<?php echo $bwge; ?>(key[0], key[1], bwgedata_<?php echo $bwge; ?>, true);
            }
          }
        }
      }
      function bwge_cubeH_<?php echo $bwge; ?>(current_image_class, next_image_class, direction) {
        /* Set to half of image width.*/
        var dimension = jQuery(current_image_class).width() / 2;
        if (direction == 'right') {
          bwge_cube_<?php echo $bwge; ?>(dimension, dimension, 0, 0, 90, 0, -90, current_image_class, next_image_class, direction);
        }
        else if (direction == 'left') {
          bwge_cube_<?php echo $bwge; ?>(dimension, -dimension, 0, 0, -90, 0, 90, current_image_class, next_image_class, direction);
        }
      }
      function bwge_cubeV_<?php echo $bwge; ?>(current_image_class, next_image_class, direction) {
        /* Set to half of image height.*/
        var dimension = jQuery(current_image_class).height() / 2;
        /* If next slide.*/
        if (direction == 'right') {
          bwge_cube_<?php echo $bwge; ?>(dimension, 0, -dimension, 90, 0, -90, 0, current_image_class, next_image_class, direction);
        }
        else if (direction == 'left') {
          bwge_cube_<?php echo $bwge; ?>(dimension, 0, dimension, -90, 0, 90, 0, current_image_class, next_image_class, direction);
        }
      }
      /* For browsers that does not support transitions.*/
      function bwge_fallback_<?php echo $bwge; ?>(current_image_class, next_image_class, direction) {
        bwge_fade_<?php echo $bwge; ?>(current_image_class, next_image_class, direction);
      }
      /* For browsers that support transitions, but not 3d transforms (only used if primary transition makes use of 3d-transforms).*/
      function bwge_fallback3d_<?php echo $bwge; ?>(current_image_class, next_image_class, direction) {
        bwge_sliceV_<?php echo $bwge; ?>(current_image_class, next_image_class, direction);
      }
      function bwge_none_<?php echo $bwge; ?>(current_image_class, next_image_class, direction) {
        jQuery(current_image_class).css({'opacity' : 0, 'z-index': 1});
        jQuery(next_image_class).css({'opacity' : 1, 'z-index' : 2});
        bwge_change_watermark_container_<?php echo $bwge; ?>();
        /* Set active thumbnail.*/
        jQuery(".bwge_slideshow_filmstrip_thumbnail_<?php echo $bwge; ?>").removeClass("bwge_slideshow_thumb_active_<?php echo $bwge; ?>").addClass("bwge_slideshow_thumb_deactive_<?php echo $bwge; ?>");
        jQuery("#bwge_filmstrip_thumbnail_" + bwge_current_key_<?php echo $bwge; ?> + "_<?php echo $bwge; ?>").removeClass("bwge_slideshow_thumb_deactive_<?php echo $bwge; ?>").addClass("bwge_slideshow_thumb_active_<?php echo $bwge; ?>");
        jQuery(".bwge_slideshow_dots_<?php echo $bwge; ?>").removeClass("bwge_slideshow_dots_active_<?php echo $bwge; ?>").addClass("bwge_slideshow_dots_deactive_<?php echo $bwge; ?>");
        jQuery("#bwge_dots_" + bwge_current_key_<?php echo $bwge; ?> + "_<?php echo $bwge; ?>").removeClass("bwge_slideshow_dots_deactive_<?php echo $bwge; ?>").addClass("bwge_slideshow_dots_active_<?php echo $bwge; ?>");
      }
      function bwge_fade_<?php echo $bwge; ?>(current_image_class, next_image_class, direction) {
        /* Set active thumbnail.*/
        jQuery(".bwge_slideshow_filmstrip_thumbnail_<?php echo $bwge; ?>").removeClass("bwge_slideshow_thumb_active_<?php echo $bwge; ?>").addClass("bwge_slideshow_thumb_deactive_<?php echo $bwge; ?>");
        jQuery("#bwge_filmstrip_thumbnail_" + bwge_current_key_<?php echo $bwge; ?> + "_<?php echo $bwge; ?>").removeClass("bwge_slideshow_thumb_deactive_<?php echo $bwge; ?>").addClass("bwge_slideshow_thumb_active_<?php echo $bwge; ?>");
        jQuery(".bwge_slideshow_dots_<?php echo $bwge; ?>").removeClass("bwge_slideshow_dots_active_<?php echo $bwge; ?>").addClass("bwge_slideshow_dots_deactive_<?php echo $bwge; ?>");
        jQuery("#bwge_dots_" + bwge_current_key_<?php echo $bwge; ?> + "_<?php echo $bwge; ?>").removeClass("bwge_slideshow_dots_deactive_<?php echo $bwge; ?>").addClass("bwge_slideshow_dots_active_<?php echo $bwge; ?>");
        if (bwge_testBrowser_cssTransitions_<?php echo $bwge; ?>()) {
          jQuery(next_image_class).css('transition', 'opacity ' + bwge_transition_duration_<?php echo $bwge; ?> + 'ms linear');
          jQuery(current_image_class).css({'opacity' : 0, 'z-index': 1});
          jQuery(next_image_class).css({'opacity' : 1, 'z-index' : 2});
          bwge_change_watermark_container_<?php echo $bwge; ?>();
        }
        else {
          jQuery(current_image_class).animate({'opacity' : 0, 'z-index' : 1}, bwge_transition_duration_<?php echo $bwge; ?>);
          jQuery(next_image_class).animate({
              'opacity' : 1,
              'z-index': 2
            }, {
              duration: bwge_transition_duration_<?php echo $bwge; ?>,
              complete: function () { bwge_change_watermark_container_<?php echo $bwge; ?>(); }
            });
          /* For IE.*/
          jQuery(current_image_class).fadeTo(bwge_transition_duration_<?php echo $bwge; ?>, 0);
          jQuery(next_image_class).fadeTo(bwge_transition_duration_<?php echo $bwge; ?>, 1);
        }
      }
      function bwge_grid_<?php echo $bwge; ?>(cols, rows, ro, tx, ty, sc, op, current_image_class, next_image_class, direction) {
        /* If browser does not support CSS transitions.*/
        if (!bwge_testBrowser_cssTransitions_<?php echo $bwge; ?>()) {
          return bwge_fallback_<?php echo $bwge; ?>(current_image_class, next_image_class, direction);
        }
        bwge_trans_in_progress_<?php echo $bwge; ?> = true;
        /* Set active thumbnail.*/
        jQuery(".bwge_slideshow_filmstrip_thumbnail_<?php echo $bwge; ?>").removeClass("bwge_slideshow_thumb_active_<?php echo $bwge; ?>").addClass("bwge_slideshow_thumb_deactive_<?php echo $bwge; ?>");
        jQuery("#bwge_filmstrip_thumbnail_" + bwge_current_key_<?php echo $bwge; ?> + "_<?php echo $bwge; ?>").removeClass("bwge_slideshow_thumb_deactive_<?php echo $bwge; ?>").addClass("bwge_slideshow_thumb_active_<?php echo $bwge; ?>");
        jQuery(".bwge_slideshow_dots_<?php echo $bwge; ?>").removeClass("bwge_slideshow_dots_active_<?php echo $bwge; ?>").addClass("bwge_slideshow_dots_deactive_<?php echo $bwge; ?>");
        jQuery("#bwge_dots_" + bwge_current_key_<?php echo $bwge; ?> + "_<?php echo $bwge; ?>").removeClass("bwge_slideshow_dots_deactive_<?php echo $bwge; ?>").addClass("bwge_slideshow_dots_active_<?php echo $bwge; ?>");
        /* The time (in ms) added to/subtracted from the delay total for each new gridlet.*/
        var count = (bwge_transition_duration_<?php echo $bwge; ?>) / (cols + rows);
        /* Gridlet creator (divisions of the image grid, positioned with background-images to replicate the look of an entire slide image when assembled)*/
        function bwge_gridlet(width, height, top, img_top, left, img_left, src, imgWidth, imgHeight, c, r) {
          var delay = (c + r) * count;
          /* Return a gridlet elem with styles for specific transition.*/
          return jQuery('<span class="bwge_gridlet_<?php echo $bwge; ?>" />').css({
            display : "block",
            width : width,
            height : height,
            top : top,
            left : left,
            backgroundImage : 'url("' + src + '")',
            backgroundColor: jQuery(".bwge_slideshow_image_wrap_<?php echo $bwge; ?>").css("background-color"),
            /*backgroundColor: rgba(0, 0, 0, 0),*/
            backgroundRepeat: 'no-repeat',
            backgroundPosition : img_left + 'px ' + img_top + 'px',
            backgroundSize : imgWidth + 'px ' + imgHeight + 'px',
            transition : 'all ' + bwge_transition_duration_<?php echo $bwge; ?> + 'ms ease-in-out ' + delay + 'ms',
            transform : 'none'
          });
        }
        /* Get the current slide's image.*/
        var cur_img = jQuery(current_image_class).find('img');
        /* Create a grid to hold the gridlets.*/
        var grid = jQuery('<span style="display: block;" />').addClass('bwge_grid_<?php echo $bwge; ?>');
        /* Prepend the grid to the next slide (i.e. so it's above the slide image).*/
        jQuery(current_image_class).prepend(grid);
        /* vars to calculate positioning/size of gridlets*/
        var cont = jQuery(".bwge_slide_bg_<?php echo $bwge; ?>");
        var imgWidth = cur_img.width();
        var imgHeight = cur_img.height();
        var contWidth = cont.width(),
            contHeight = cont.height(),
            imgSrc = cur_img.attr('src'),/*.replace('/thumb', ''),*/
            colWidth = Math.floor(contWidth / cols),
            rowHeight = Math.floor(contHeight / rows),
            colRemainder = contWidth - (cols * colWidth),
            colAdd = Math.ceil(colRemainder / cols),
            rowRemainder = contHeight - (rows * rowHeight),
            rowAdd = Math.ceil(rowRemainder / rows),
            leftDist = 0,
            img_leftDist = (jQuery(".bwge_slide_bg_<?php echo $bwge; ?>").width() - cur_img.width()) / 2;
        /* tx/ty args can be passed as 'auto'/'min-auto' (meaning use slide width/height or negative slide width/height).*/
        tx = tx === 'auto' ? contWidth : tx;
        tx = tx === 'min-auto' ? - contWidth : tx;
        ty = ty === 'auto' ? contHeight : ty;
        ty = ty === 'min-auto' ? - contHeight : ty;
        /* Loop through cols*/
        for (var i = 0; i < cols; i++) {
          var topDist = 0,
              img_topDst = (jQuery(".bwge_slide_bg_<?php echo $bwge; ?>").height() - cur_img.height()) / 2,
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
          /*if (bwge_from_focus_<?php echo $bwge; ?>) {
            bwge_from_focus_<?php echo $bwge; ?> = false;
            return;
          }*/
          jQuery(current_image_class).css({'opacity' : 0, 'z-index': 1});
          jQuery(next_image_class).css({'opacity' : 1, 'z-index' : 2});
          cur_img.css('opacity', 1);
          bwge_change_watermark_container_<?php echo $bwge; ?>();
          grid.remove();
          bwge_trans_in_progress_<?php echo $bwge; ?> = false;
          if (typeof event_stack_<?php echo $bwge; ?> !== 'undefined') {
            if (event_stack_<?php echo $bwge; ?>.length > 0) {
              key = event_stack_<?php echo $bwge; ?>[0].split("-");
              event_stack_<?php echo $bwge; ?>.shift();
              bwge_change_image_<?php echo $bwge; ?>(key[0], key[1], bwgedata_<?php echo $bwge; ?>, true);
            }
          }
        }
      }
      function bwge_sliceH_<?php echo $bwge; ?>(current_image_class, next_image_class, direction) {
        if (direction == 'right') {
          var translateX = 'min-auto';
        }
        else if (direction == 'left') {
          var translateX = 'auto';
        }
        bwge_grid_<?php echo $bwge; ?>(1, 8, 0, translateX, 0, 1, 0, current_image_class, next_image_class, direction);
      }
      function bwge_sliceV_<?php echo $bwge; ?>(current_image_class, next_image_class, direction) {
        if (direction == 'right') {
          var translateY = 'min-auto';
        }
        else if (direction == 'left') {
          var translateY = 'auto';
        }
        bwge_grid_<?php echo $bwge; ?>(10, 1, 0, 0, translateY, 1, 0, current_image_class, next_image_class, direction);
      }
      function bwge_slideV_<?php echo $bwge; ?>(current_image_class, next_image_class, direction) {
        if (direction == 'right') {
          var translateY = 'auto';
        }
        else if (direction == 'left') {
          var translateY = 'min-auto';
        }
        bwge_grid_<?php echo $bwge; ?>(1, 1, 0, 0, translateY, 1, 1, current_image_class, next_image_class, direction);
      }
      function bwge_slideH_<?php echo $bwge; ?>(current_image_class, next_image_class, direction) {
        if (direction == 'right') {
          var translateX = 'min-auto';
        }
        else if (direction == 'left') {
          var translateX = 'auto';
        }
        bwge_grid_<?php echo $bwge; ?>(1, 1, 0, translateX, 0, 1, 1, current_image_class, next_image_class, direction);
      }
      function bwge_scaleOut_<?php echo $bwge; ?>(current_image_class, next_image_class, direction) {
        bwge_grid_<?php echo $bwge; ?>(1, 1, 0, 0, 0, 1.5, 0, current_image_class, next_image_class, direction);
      }
      function bwge_scaleIn_<?php echo $bwge; ?>(current_image_class, next_image_class, direction) {
        bwge_grid_<?php echo $bwge; ?>(1, 1, 0, 0, 0, 0.5, 0, current_image_class, next_image_class, direction);
      }
      function bwge_blockScale_<?php echo $bwge; ?>(current_image_class, next_image_class, direction) {
        bwge_grid_<?php echo $bwge; ?>(8, 6, 0, 0, 0, .6, 0, current_image_class, next_image_class, direction);
      }
      function bwge_kaleidoscope_<?php echo $bwge; ?>(current_image_class, next_image_class, direction) {
        bwge_grid_<?php echo $bwge; ?>(10, 8, 0, 0, 0, 1, 0, current_image_class, next_image_class, direction);
      }
      function bwge_fan_<?php echo $bwge; ?>(current_image_class, next_image_class, direction) {
        if (direction == 'right') {
          var rotate = 45;
          var translateX = 100;
        }
        else if (direction == 'left') {
          var rotate = -45;
          var translateX = -100;
        }
        bwge_grid_<?php echo $bwge; ?>(1, 10, rotate, translateX, 0, 1, 0, current_image_class, next_image_class, direction);
      }
      function bwge_blindV_<?php echo $bwge; ?>(current_image_class, next_image_class, direction) {
        bwge_grid_<?php echo $bwge; ?>(1, 8, 0, 0, 0, .7, 0, current_image_class, next_image_class);
      }
      function bwge_blindH_<?php echo $bwge; ?>(current_image_class, next_image_class, direction) {
        bwge_grid_<?php echo $bwge; ?>(10, 1, 0, 0, 0, .7, 0, current_image_class, next_image_class);
      }
      function bwge_random_<?php echo $bwge; ?>(current_image_class, next_image_class, direction) {
        var anims = ['sliceH', 'sliceV', 'slideH', 'slideV', 'scaleOut', 'scaleIn', 'blockScale', 'kaleidoscope', 'fan', 'blindH', 'blindV'];
        /* Pick a random transition from the anims array.*/
        this["bwge_" + anims[Math.floor(Math.random() * anims.length)] + "_<?php echo $bwge; ?>"](current_image_class, next_image_class, direction);
      }
      function iterator_<?php echo $bwge; ?>() {
        var iterator = 1;
        if (<?php echo $enable_slideshow_shuffle; ?>) {
          iterator = Math.floor((bwgedata_<?php echo $bwge; ?>.length - 1) * Math.random() + 1);
        }
        return iterator;
      }
      function bwge_change_image_<?php echo $bwge; ?>(current_key, key, bwgedata_<?php echo $bwge; ?>, from_effect) {

        /* Pause videos.*/
        jQuery("#bwge_slideshow_image_container_<?php echo $bwge; ?>").find("iframe").each(function () {
          jQuery(this)[0].contentWindow.postMessage('{"event":"command","func":"pauseVideo","args":""}', '*');
          jQuery(this)[0].contentWindow.postMessage('{ "method": "pause" }', "*");
          jQuery(this)[0].contentWindow.postMessage('pause', '*');
        });
               
        /* Pause videos facebook video.*/
        jQuery('#image_id_<?php echo $bwge; ?>_' + bwgedata_<?php echo $bwge; ?>[current_key]["id"]).find('.bwge_fb_video').each(function () {
          jQuery(this).attr('src', jQuery(this).attr('src'));
        });
        
        if (bwgedata_<?php echo $bwge; ?>[key]) {
          if (jQuery('.bwge_ctrl_btn_<?php echo $bwge; ?>').hasClass('fa-pause')) {
            play_<?php echo $bwge; ?>();
          }
          if (!from_effect) {
            /* Change image key.*/
            jQuery("#bwge_current_image_key_<?php echo $bwge; ?>").val(key);
            if (current_key == '-1') { /* Filmstrip.*/
              current_key = jQuery(".bwge_slideshow_thumb_active_<?php echo $bwge; ?>").children("img").attr("image_key");
            }
            else if (current_key == '-2') { /* Dots.*/
              current_key = jQuery(".bwge_slideshow_dots_active_<?php echo $bwge; ?>").attr("image_key");
            }
          }
          if (bwge_trans_in_progress_<?php echo $bwge; ?>) {
            event_stack_<?php echo $bwge; ?>.push(current_key + '-' + key);
            return;
          }
          var direction = 'right';
          if (bwge_current_key_<?php echo $bwge; ?> > key) {
            var direction = 'left';
          }
          else if (bwge_current_key_<?php echo $bwge; ?> == key) {
            return;
          }
          jQuery(".bwge_slideshow_watermark_<?php echo $bwge; ?>").css({display: 'none'});
          jQuery(".bwge_slideshow_title_text_<?php echo $bwge; ?>").css({display: 'none'});
          jQuery(".bwge_slideshow_description_text_<?php echo $bwge; ?>").css({display: 'none'});
          /* Set active thumbnail position.*/
          bwge_current_filmstrip_pos_<?php echo $bwge; ?> = key * (jQuery(".bwge_slideshow_filmstrip_thumbnail_<?php echo $bwge; ?>").<?php echo $width_or_height; ?>() + 2 + 2 * <?php echo $theme_row->lightbox_filmstrip_thumb_border_width; ?>);
          bwge_current_key_<?php echo $bwge; ?> = key;
          /* Change image id, title, description.*/
          jQuery("#bwge_slideshow_image_<?php echo $bwge; ?>").attr('image_id', bwgedata_<?php echo $bwge; ?>[key]["id"]);
          jQuery(".bwge_slideshow_title_text_<?php echo $bwge; ?>").html(jQuery('<span style="display: block;" />').html(bwgedata_<?php echo $bwge; ?>[key]["alt"]).text());
          jQuery(".bwge_slideshow_description_text_<?php echo $bwge; ?>").html(jQuery('<span style="display: block;" />').html(bwgedata_<?php echo $bwge; ?>[key]["description"]).text());
          var current_image_class = "#image_id_<?php echo $bwge; ?>_" + bwgedata_<?php echo $bwge; ?>[current_key]["id"];
          var next_image_class = "#image_id_<?php echo $bwge; ?>_" + bwgedata_<?php echo $bwge; ?>[key]["id"];
          bwge_<?php echo $slideshow_effect; ?>_<?php echo $bwge; ?>(current_image_class, next_image_class, direction);
          <?php
          if ($enable_slideshow_filmstrip) {
            ?>
            bwge_move_filmstrip_<?php echo $bwge; ?>();
            <?php
          }
          else {            
            ?>
            bwge_move_dots_<?php echo $bwge; ?>();
            <?php
          }
          ?>
          if (bwgedata_<?php echo $bwge; ?>[key]["is_embed_video"]) {
            jQuery("#bwge_slideshow_play_pause_<?php echo $bwge; ?>").css({display: 'none'});
          }
          else {
            jQuery("#bwge_slideshow_play_pause_<?php echo $bwge; ?>").css({display: ''});            
          }
        }
 
      }
      function bwge_popup_resize_<?php echo $bwge; ?>() {
        var parent_width = jQuery(".bwge_slideshow_image_wrap_<?php echo $bwge; ?>").parent().width();
        if (parent_width >= <?php echo $image_width; ?>) {
          jQuery(".bwge_slideshow_image_wrap_<?php echo $bwge; ?>").css({width: <?php echo $image_width; ?>});
          jQuery(".bwge_slideshow_image_wrap_<?php echo $bwge; ?>").css({height: <?php echo $image_height; ?>});
          jQuery(".bwge_slideshow_image_container_<?php echo $bwge; ?>").css({width: <?php echo ($filmstrip_direction == 'horizontal' ? $image_width : $image_width - $slideshow_filmstrip_width); ?>});
          jQuery(".bwge_slideshow_image_container_<?php echo $bwge; ?>").css({height: (<?php echo ($filmstrip_direction == 'horizontal' ? $image_height - $slideshow_filmstrip_height : $image_height); ?>)});
          jQuery(".bwge_slideshow_image_<?php echo $bwge; ?>").css({
            cssText: "max-width: <?php echo ($filmstrip_direction == 'horizontal' ? $image_width : $image_width - $slideshow_filmstrip_width); ?>px !important; max-height: <?php echo ($filmstrip_direction == 'horizontal' ? $image_height - $slideshow_filmstrip_height : $image_height); ?>px !important;"
          });
          jQuery(".bwge_slideshow_embed_<?php echo $bwge; ?>").css({
            cssText: "width: <?php echo ($filmstrip_direction == 'horizontal' ? $image_width : $image_width - $slideshow_filmstrip_width); ?>px !important; height: <?php echo ($filmstrip_direction == 'horizontal' ? $image_height - $slideshow_filmstrip_height : $image_height); ?>px !important;"
          });
          bwge_resize_instagram_post_<?php echo $bwge?>();
          /* Set watermark container size.*/
          bwge_change_watermark_container_<?php echo $bwge; ?>();
          jQuery(".bwge_slideshow_filmstrip_container_<?php echo $bwge; ?>").css({<?php echo ($filmstrip_direction == 'horizontal' ? 'width: ' . $image_width : 'height: ' . $image_height); ?>});
          jQuery(".bwge_slideshow_filmstrip_<?php echo $bwge; ?>").css({<?php echo ($filmstrip_direction == 'horizontal' ? 'width: ' . ($image_width - 40) : 'height: ' . ($image_height - 40)); ?>});
          jQuery(".bwge_slideshow_dots_container_<?php echo $bwge; ?>").css({width: <?php echo $image_width; ?>});
          jQuery("#bwge_slideshow_play_pause-ico_<?php echo $bwge; ?>").css({fontSize: (<?php echo $theme_row->slideshow_play_pause_btn_size; ?>)});
          jQuery(".bwge_slideshow_watermark_image_<?php echo $bwge; ?>").css({maxWidth: <?php echo $watermark_width; ?>, maxHeight: <?php echo $watermark_height; ?>});
          jQuery(".bwge_slideshow_watermark_text_<?php echo $bwge; ?>, .bwge_slideshow_watermark_text_<?php echo $bwge; ?>:hover").css({fontSize: (<?php echo $watermark_font_size; ?>)});
          jQuery(".bwge_slideshow_title_text_<?php echo $bwge; ?>").css({fontSize: (<?php echo $theme_row->slideshow_title_font_size * 2; ?>)});
          jQuery(".bwge_slideshow_description_text_<?php echo $bwge; ?>").css({fontSize: (<?php echo $theme_row->slideshow_description_font_size * 2; ?>)});
        }
        else {
          jQuery(".bwge_slideshow_image_wrap_<?php echo $bwge; ?>").css({width: (parent_width)});
          jQuery(".bwge_slideshow_image_wrap_<?php echo $bwge; ?>").css({height: ((parent_width) * <?php echo $image_height / $image_width ?>)});
          jQuery(".bwge_slideshow_image_container_<?php echo $bwge; ?>").css({width: (parent_width - <?php echo ($filmstrip_direction == 'horizontal' ? 0 : $slideshow_filmstrip_width); ?>)});
          jQuery(".bwge_slideshow_image_container_<?php echo $bwge; ?>").css({height: ((parent_width) * <?php echo $image_height / $image_width ?> - <?php echo ($filmstrip_direction == 'horizontal' ? $slideshow_filmstrip_height : 0); ?>)});
          jQuery(".bwge_slideshow_image_<?php echo $bwge; ?>").css({
            cssText: "max-width: " + (parent_width - <?php echo ($filmstrip_direction == 'horizontal' ? 0 : $slideshow_filmstrip_width) ?>) + "px !important; max-height: " + (parent_width * (<?php echo $image_height / $image_width ?>) - <?php echo ($filmstrip_direction == 'horizontal' ? $slideshow_filmstrip_height : 0); ?> - 1) + "px !important;"
          });
          jQuery(".bwge_slideshow_embed_<?php echo $bwge; ?>").css({
            cssText: "width: " + (parent_width - <?php echo ($filmstrip_direction == 'horizontal' ? 0 : $slideshow_filmstrip_width) ?>) + "px !important; height: " + (parent_width * (<?php echo $image_height / $image_width ?>) - <?php echo ($filmstrip_direction == 'horizontal' ? $slideshow_filmstrip_height : 0); ?> - 1) + "px !important;"
          });
          bwge_resize_instagram_post_<?php echo $bwge?>();
          /* Set watermark container size.*/
          bwge_change_watermark_container_<?php echo $bwge; ?>();
          <?php if ($filmstrip_direction == 'horizontal') { ?>
          jQuery(".bwge_slideshow_filmstrip_container_<?php echo $bwge; ?>").css({width: (parent_width)});
          jQuery(".bwge_slideshow_filmstrip_<?php echo $bwge; ?>").css({width: (parent_width - 40)});
          <?php }
          else {
          ?>
          jQuery(".bwge_slideshow_filmstrip_container_<?php echo $bwge; ?>").css({height: (parent_width * <?php echo $image_height / $image_width ?>)});          
          jQuery(".bwge_slideshow_filmstrip_<?php echo $bwge; ?>").css({height: (parent_width * <?php echo $image_height / $image_width ?> - 40)});
          <?php
          }
          ?>
          jQuery(".bwge_slideshow_dots_container_<?php echo $bwge; ?>").css({width: (parent_width)});
          jQuery("#bwge_slideshow_play_pause-ico_<?php echo $bwge; ?>").css({fontSize: ((parent_width) * <?php echo $theme_row->slideshow_play_pause_btn_size / $image_width; ?>)});
          jQuery(".bwge_slideshow_watermark_image_<?php echo $bwge; ?>").css({maxWidth: ((parent_width) * <?php echo $watermark_width / $image_width; ?>), maxHeight: ((parent_width) * <?php echo $watermark_height / $image_width; ?>)});
          jQuery(".bwge_slideshow_watermark_text_<?php echo $bwge; ?>, .bwge_slideshow_watermark_text_<?php echo $bwge; ?>:hover").css({fontSize: ((parent_width) * <?php echo $watermark_font_size / $image_width; ?>)});
          jQuery(".bwge_slideshow_title_text_<?php echo $bwge; ?>").css({fontSize: ((parent_width) * <?php echo 2 * $theme_row->slideshow_title_font_size / $image_width; ?>)});
          jQuery(".bwge_slideshow_description_text_<?php echo $bwge; ?>").css({fontSize: ((parent_width) * <?php echo 2 * $theme_row->slideshow_description_font_size / $image_width; ?>)});
        }
      }
      function bwge_show_add_to_cart_<?php echo $bwge; ?>(image_id){
          ajaxurl = "<?php echo admin_url('admin-ajax.php'); ?>";
          data = {};
          data.action = 'show_add_to_cart';
          data.controller = 'checkout';
          data.task = 'show_add_to_cart';
          data.image_id = image_id ;
          data.current_view = '<?php echo $bwge; ?>';
          
          jQuery("html").attr("style", "overflow:hidden !important;");
          jQuery("#bwge_spider_popup_loading_" + <?php echo $bwge; ?>).show();
          jQuery("#bwge_spider_popup_overlay_" + <?php echo $bwge; ?>).show();

          jQuery.post(ajaxurl, data, function(response) {
            jQuery("#bwge_spider_popup_loading_" +  <?php echo $bwge; ?>).hide();
            jQuery(".bwge_pricelist_container" + <?php echo $bwge; ?>).html(response);           
            jQuery(".bwge_pricelist_container" + <?php echo $bwge; ?>).slideToggle();
          });

      }
      
      jQuery(window).resize(function() {
        bwge_popup_resize_<?php echo $bwge; ?>();
      });
      jQuery(window).load(function () {
        var image_postion = jQuery("#bwge_slideshow_image_<?php echo $bwge; ?>").position();

      	<?php
        if ($image_right_click) {
          ?>
          /* Disable right click.*/
          jQuery('div[id^="bwge_container"]').bind("contextmenu", function () {
            return false;
          });
          jQuery('div[id^="bwge_container"]').css('webkitTouchCallout','none');
          <?php
        }
        ?>
        if (typeof jQuery().swiperight !== 'undefined') {
          if (jQuery.isFunction(jQuery().swiperight)) {
            jQuery('#bwge_container1_<?php echo $bwge; ?>').swiperight(function () {
              bwge_change_image_<?php echo $bwge; ?>(parseInt(jQuery('#bwge_current_image_key_<?php echo $bwge; ?>').val()), (parseInt(jQuery('#bwge_current_image_key_<?php echo $bwge; ?>').val()) - iterator_<?php echo $bwge; ?>()) >= 0 ? (parseInt(jQuery('#bwge_current_image_key_<?php echo $bwge; ?>').val()) - iterator_<?php echo $bwge; ?>()) % bwgedata_<?php echo $bwge; ?>.length : bwgedata_<?php echo $bwge; ?>.length - 1, bwgedata_<?php echo $bwge; ?>);
              return false;
            });
          }
        }
        if (typeof jQuery().swipeleft !== 'undefined') {
          if (jQuery.isFunction(jQuery().swipeleft)) {
            jQuery('#bwge_container1_<?php echo $bwge; ?>').swipeleft(function () {
              bwge_change_image_<?php echo $bwge; ?>(parseInt(jQuery('#bwge_current_image_key_<?php echo $bwge; ?>').val()), (parseInt(jQuery('#bwge_current_image_key_<?php echo $bwge; ?>').val()) + iterator_<?php echo $bwge; ?>()) % bwgedata_<?php echo $bwge; ?>.length, bwgedata_<?php echo $bwge; ?>);
              return false;
            });
          }
        }

        var isMobile = (/android|webos|iphone|ipad|ipod|blackberry|iemobile|opera mini/i.test(navigator.userAgent.toLowerCase()));
        var bwge_click = isMobile ? 'touchend' : 'click';
        
        jQuery(".bwge_ecommerce_slideshow<?php echo $bwge; ?>").on(bwge_click, function (event) {         
          bwge_show_add_to_cart_<?php echo $bwge; ?>(bwgedata_<?php echo $bwge; ?>[parseInt(jQuery('#bwge_current_image_key_<?php echo $bwge; ?>').val())]["id"]);          
        }); 
        bwge_popup_resize_<?php echo $bwge; ?>();
        jQuery("#bwge_container1_<?php echo $bwge; ?>").css({visibility: 'visible'});
        jQuery(".bwge_slideshow_watermark_<?php echo $bwge; ?>").css({display: 'none'});
        jQuery(".bwge_slideshow_title_text_<?php echo $bwge; ?>").css({display: 'none'});
        jQuery(".bwge_slideshow_description_text_<?php echo $bwge; ?>").css({display: 'none'});
        setTimeout(function () {
          bwge_change_watermark_container_<?php echo $bwge; ?>();
        }, 500);
        /* Set image container height.*/
        <?php if ($filmstrip_direction == 'horizontal') { ?>
        jQuery(".bwge_slideshow_image_container_<?php echo $bwge; ?>").height(jQuery(".bwge_slideshow_image_wrap_<?php echo $bwge; ?>").height() - <?php echo $slideshow_filmstrip_height; ?>);
          <?php }
        else {
          ?>
          jQuery(".bwge_slideshow_image_container_<?php echo $bwge; ?>").width(jQuery(".bwge_slideshow_image_wrap_<?php echo $bwge; ?>").width() - <?php echo $slideshow_filmstrip_width; ?>);
          <?php
        } ?>
        var mousewheelevt = (/Firefox/i.test(navigator.userAgent)) ? "DOMMouseScroll" : "mousewheel"; /* FF doesn't recognize mousewheel as of FF3.x */
        jQuery('.bwge_slideshow_filmstrip_<?php echo $bwge; ?>').bind(mousewheelevt, function(e) {
          var evt = window.event || e; /* Equalize event object.*/
          evt = evt.originalEvent ? evt.originalEvent : evt; /* Convert to originalEvent if possible.*/
          var delta = evt.detail ? evt.detail*(-40) : evt.wheelDelta; /* Check for detail first, because it is used by Opera and FF.*/
          if (delta > 0) {
            /* Scroll up.*/
            jQuery(".bwge_slideshow_filmstrip_left_<?php echo $bwge; ?>").trigger("click");
          }
          else {
            /* Scroll down.*/
            jQuery(".bwge_slideshow_filmstrip_right_<?php echo $bwge; ?>").trigger("click");
          }
          return false;
        });
        jQuery(".bwge_slideshow_filmstrip_right_<?php echo $bwge; ?>").on(bwge_click, function () {
          jQuery( ".bwge_slideshow_filmstrip_thumbnails_<?php echo $bwge; ?>" ).stop(true, false);
          if (jQuery(".bwge_slideshow_filmstrip_thumbnails_<?php echo $bwge; ?>").position().<?php echo $left_or_top; ?> >= -(jQuery(".bwge_slideshow_filmstrip_thumbnails_<?php echo $bwge; ?>").<?php echo $width_or_height; ?>() - jQuery(".bwge_slideshow_filmstrip_<?php echo $bwge; ?>").<?php echo $width_or_height; ?>())) {
            jQuery(".bwge_slideshow_filmstrip_left_<?php echo $bwge; ?>").css({opacity: 1, filter: "Alpha(opacity=100)"});
            if (jQuery(".bwge_slideshow_filmstrip_thumbnails_<?php echo $bwge; ?>").position().<?php echo $left_or_top; ?> < -(jQuery(".bwge_slideshow_filmstrip_thumbnails_<?php echo $bwge; ?>").<?php echo $width_or_height; ?>() - jQuery(".bwge_slideshow_filmstrip_<?php echo $bwge; ?>").<?php echo $width_or_height; ?>() - <?php echo $filmstrip_thumb_margin_hor + $slideshow_filmstrip_width; ?>)) {
              jQuery(".bwge_slideshow_filmstrip_thumbnails_<?php echo $bwge; ?>").animate({<?php echo $left_or_top; ?>: -(jQuery(".bwge_slideshow_filmstrip_thumbnails_<?php echo $bwge; ?>").<?php echo $width_or_height; ?>() - jQuery(".bwge_slideshow_filmstrip_<?php echo $bwge; ?>").<?php echo $width_or_height; ?>())}, 500, 'linear');
            }
            else {
              jQuery(".bwge_slideshow_filmstrip_thumbnails_<?php echo $bwge; ?>").animate({<?php echo $left_or_top; ?>: (jQuery(".bwge_slideshow_filmstrip_thumbnails_<?php echo $bwge; ?>").position().<?php echo $left_or_top; ?> - <?php echo $filmstrip_thumb_margin_hor + $slideshow_filmstrip_width; ?>)}, 500, 'linear');
            }
          }
          /* Disable right arrow.*/
          window.setTimeout(function(){
            if (jQuery(".bwge_slideshow_filmstrip_thumbnails_<?php echo $bwge; ?>").position().<?php echo $left_or_top; ?> == -(jQuery(".bwge_slideshow_filmstrip_thumbnails_<?php echo $bwge; ?>").<?php echo $width_or_height; ?>() - jQuery(".bwge_slideshow_filmstrip_<?php echo $bwge; ?>").<?php echo $width_or_height; ?>())) {
              jQuery(".bwge_slideshow_filmstrip_right_<?php echo $bwge; ?>").css({opacity: 0.3, filter: "Alpha(opacity=30)"});
            }
          }, 500);
        });
        jQuery(".bwge_slideshow_filmstrip_left_<?php echo $bwge; ?>").on(bwge_click, function () {
          jQuery( ".bwge_slideshow_filmstrip_thumbnails_<?php echo $bwge; ?>" ).stop(true, false);
          if (jQuery(".bwge_slideshow_filmstrip_thumbnails_<?php echo $bwge; ?>").position().<?php echo $left_or_top; ?> < 0) {
            jQuery(".bwge_slideshow_filmstrip_right_<?php echo $bwge; ?>").css({opacity: 1, filter: "Alpha(opacity=100)"});
            if (jQuery(".bwge_slideshow_filmstrip_thumbnails_<?php echo $bwge; ?>").position().<?php echo $left_or_top; ?> > - <?php echo $filmstrip_thumb_margin_hor + $slideshow_filmstrip_width; ?>) {
              jQuery(".bwge_slideshow_filmstrip_thumbnails_<?php echo $bwge; ?>").animate({<?php echo $left_or_top; ?>: 0}, 500, 'linear');
            }
            else {
              jQuery(".bwge_slideshow_filmstrip_thumbnails_<?php echo $bwge; ?>").animate({<?php echo $left_or_top; ?>: (jQuery(".bwge_slideshow_filmstrip_thumbnails_<?php echo $bwge; ?>").position().<?php echo $left_or_top; ?> + <?php echo $filmstrip_thumb_margin_hor + $slideshow_filmstrip_width; ?>)}, 500, 'linear');
            }
          }
          /* Disable left arrow.*/
          window.setTimeout(function(){
            if (jQuery(".bwge_slideshow_filmstrip_thumbnails_<?php echo $bwge; ?>").position().<?php echo $left_or_top; ?> == 0) {
              jQuery(".bwge_slideshow_filmstrip_left_<?php echo $bwge; ?>").css({opacity: 0.3, filter: "Alpha(opacity=30)"});
            }
          }, 500);
        });
        /* Set filmstrip initial position.*/
        bwge_set_filmstrip_pos_<?php echo $bwge; ?>(jQuery(".bwge_slideshow_filmstrip_<?php echo $bwge; ?>").<?php echo $width_or_height; ?>());
        /* Play/pause.*/
        jQuery("#bwge_slideshow_play_pause_<?php echo $bwge; ?>").on(bwge_click, function () {
          if (jQuery(".bwge_ctrl_btn_<?php echo $bwge; ?>").hasClass("fa-play")) {
            play_<?php echo $bwge; ?>();
            jQuery(".bwge_slideshow_play_pause_<?php echo $bwge; ?>").attr("title", "<?php echo __('Pause', 'bwge'); ?>");
            jQuery(".bwge_slideshow_play_pause_<?php echo $bwge; ?>").attr("class", "bwge_ctrl_btn_<?php echo $bwge; ?> bwge_slideshow_play_pause_<?php echo $bwge; ?> fa fa-pause");
            if (<?php echo $enable_slideshow_music ?>) {
              document.getElementById("bwge_audio_<?php echo $bwge; ?>").play();
            }
          }
          else {
            /* Pause.*/
            window.clearInterval(bwge_playInterval_<?php echo $bwge; ?>);
            jQuery(".bwge_slideshow_play_pause_<?php echo $bwge; ?>").attr("title", "<?php echo __('Play', 'bwge'); ?>");
            jQuery(".bwge_slideshow_play_pause_<?php echo $bwge; ?>").attr("class", "bwge_ctrl_btn_<?php echo $bwge; ?> bwge_slideshow_play_pause_<?php echo $bwge; ?> fa fa-play");
            if (<?php echo $enable_slideshow_music ?>) {
              document.getElementById("bwge_audio_<?php echo $bwge; ?>").pause();
            }
          }
        });
        if (<?php echo $enable_slideshow_autoplay; ?>) {
          play_<?php echo $bwge; ?>();
          jQuery(".bwge_slideshow_play_pause_<?php echo $bwge; ?>").attr("title", "<?php echo __('Pause', 'bwge'); ?>");
          jQuery(".bwge_slideshow_play_pause_<?php echo $bwge; ?>").attr("class", "bwge_ctrl_btn_<?php echo $bwge; ?> bwge_slideshow_play_pause_<?php echo $bwge; ?> fa fa-pause");
          if (<?php echo $enable_slideshow_music ?>) {
            document.getElementById("bwge_audio_<?php echo $bwge; ?>").play();
          }
        }
        jQuery(".bwge_slideshow_image_<?php echo $bwge; ?>").removeAttr("width");
        jQuery(".bwge_slideshow_image_<?php echo $bwge; ?>").removeAttr("height");
      });
      function bwge_resize_instagram_post_<?php echo $bwge?>(){
        
        /*jQuery.fn.exists = function(){return this.length>0;};*/
        if (jQuery('.inner_instagram_iframe_bwge_embed_frame_<?php echo $bwge?>').length) {
          
          var w = jQuery('.bwge_slideshow_embed_<?php echo $bwge?>').width();
          var h = jQuery('.bwge_slideshow_embed_<?php echo $bwge?>').height();
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
           jQuery('.inner_instagram_iframe_bwge_embed_frame_<?php echo $bwge?>').each(function(){
          post_height = post_height;
          post_width = post_width;
          var top_pos = (0.5 *( h-post_height));
          jQuery(this).parent().parent().css({
              height: post_height,
              width: post_width,
              top:  top_pos
            });
            jQuery(this).parent().css({
              height: post_height,
              width: post_width,
              top:  top_pos
            });
          });
        }
        bwge_change_watermark_container_<?php echo $bwge; ?>();
      }
      function play_<?php echo $bwge; ?>() {
        window.clearInterval(bwge_playInterval_<?php echo $bwge; ?>);
        /* Play.*/
        bwge_playInterval_<?php echo $bwge; ?> = setInterval(function () {
          var iterator = 1;
          if (<?php echo $enable_slideshow_shuffle; ?>) {
            iterator = Math.floor((bwgedata_<?php echo $bwge; ?>.length - 1) * Math.random() + 1);
          }
          bwge_change_image_<?php echo $bwge; ?>(parseInt(jQuery('#bwge_current_image_key_<?php echo $bwge; ?>').val()), (parseInt(jQuery('#bwge_current_image_key_<?php echo $bwge; ?>').val()) + iterator) % bwgedata_<?php echo $bwge; ?>.length, bwgedata_<?php echo $bwge; ?>)
        }, '<?php echo $slideshow_interval * 1000; ?>');
      }
      jQuery(window).focus(function() {
        /* event_stack_<?php echo $bwge; ?> = [];*/
        if (!jQuery(".bwge_ctrl_btn_<?php echo $bwge; ?>").hasClass("fa-play")) {
          play_<?php echo $bwge; ?>();
        }
        var i_<?php echo $bwge; ?> = 0;
        jQuery(".bwge_slider_<?php echo $bwge; ?>").children("span").each(function () {
          if (jQuery(this).css('opacity') == 1) {
            jQuery("#bwge_current_image_key_<?php echo $bwge; ?>").val(i_<?php echo $bwge; ?>);
          }
          i_<?php echo $bwge; ?>++;
        });
      });
      jQuery(window).blur(function() {
        event_stack_<?php echo $bwge; ?> = [];
        window.clearInterval(bwge_playInterval_<?php echo $bwge; ?>);
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