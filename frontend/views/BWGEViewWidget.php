<?php

class BWGEViewWidgetFrontEnd {
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
  public function view_tags($params) {
    global $wp;
    $current_url = (is_ssl() ? 'https://' : 'http://') . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
    global $WD_BWGE_UPLOAD_DIR;
    global $bwge;

    $type = isset($params["type"]) ? $params["type"] : 'text';
    $show_name = isset($params["show_name"]) ? $params["show_name"] : 0;
    $open_option = isset($params["open_option"]) ? $params["open_option"] : 'page';
    $count = isset($params["count"]) ? $params["count"] : 0;
    $width = isset($params["width"]) ? $params["width"] : 250;
    $height = isset($params["height"]) ? $params["height"] : 250;
    $background_transparent = isset($params["background_transparent"]) ? $params["background_transparent"] : 1;
    $background_color = isset($params["background_color"]) ? $params["background_color"] : "000000";
    $text_color = isset($params["text_color"]) ? $params["text_color"] : "ffffff";
    $theme_id = isset($params["theme_id"]) ? $params["theme_id"] : 0;

    $tags = $this->model->get_tags_data($count);
    $theme_row = $this->model->get_theme_row_data($theme_id);
    $options_row = $this->model->get_options_row_data();
    ?>
    <style type="text/css" media="screen">
      @media screen and (max-width: <?php echo $width ?>px) {
        #bwge_container1_<?php echo $bwge; ?> #bwge_container2_<?php echo $bwge; ?> #tags_cloud_item_<?php echo $bwge; ?> { 
          display: none;
        }
      }
			#bwge_container1_<?php echo $bwge; ?> #bwge_container2_<?php echo $bwge; ?> #tags_cloud_item_<?php echo $bwge; ?> {
				width:<?php echo $width ?>px;
				height:<?php echo $height ?>px;
				margin:0 auto;
				overflow: hidden;
				position: relative;
        background-color: <?php echo $background_transparent ? 'transparent' : '#' . $background_color ?>;
        color: #<?php echo $text_color ?> !important;
        max-width: 100%;
			}
			#bwge_container1_<?php echo $bwge; ?> #bwge_container2_<?php echo $bwge; ?> #tags_cloud_item_<?php echo $bwge; ?> ul {
				list-style-type: none;
			}
      #bwge_container1_<?php echo $bwge; ?> #bwge_container2_<?php echo $bwge; ?> #tags_cloud_item_<?php echo $bwge; ?> .bwge_link_widget {
        text-decoration: none;
        color: #<?php echo $text_color ?> !important;
        cursor: pointer;
        font-size: inherit !important;
      }
      #bwge_container1_<?php echo $bwge; ?> #bwge_container2_<?php echo $bwge; ?> #bwge_spider_popup_overlay_<?php echo $bwge; ?> {
        background-color: #<?php echo $theme_row->lightbox_overlay_bg_color; ?>;
        opacity: <?php echo number_format($theme_row->lightbox_overlay_bg_transparent / 100, 2, ".", ""); ?>;
        filter: Alpha(opacity=<?php echo $theme_row->lightbox_overlay_bg_transparent; ?>);
      }
		</style>

    <script type="text/javascript">
      jQuery(document).ready(function() {
        var container = jQuery("#tags_cloud_item_<?php echo $bwge; ?>")
        var camera = new Camera3D();
        camera.init(0, 0, 0, (container.width() + container.height()) / 2);
        var tags_cloud_item = new Object3D(container);
        radius = (container.height() > container.width() ? container.width() : container.height());
        tags_cloud_item.addChild(new Sphere(radius * 0.35, <?php echo sqrt(count($tags)) ?>, <?php echo count($tags) ?>));
        var scene = new Scene3D();
        scene.addToScene(tags_cloud_item);        
        var mouseX = 20;
        var mouseY = 30;
        var offsetX = container.offset().left;
        var offsetY = container.offset().top;
        var speed = 6000;
        container.mousemove(function(e){
         offsetX = container.offset().left;
         offsetY = container.offset().top;
         mouseX = (e.clientX + jQuery(window).scrollLeft() - offsetX - (container.width() / 2)) % container.width();
         mouseY = (e.clientY + jQuery(window).scrollTop() - offsetY - (container.height() / 2)) % container.height();
       });


        var animateIt = function(){
          if (mouseX != undefined){
            axisRotation.y += (mouseX) / speed;
          }
          if (mouseY != undefined){
            axisRotation.x -= mouseY / speed;
          }
          scene.renderCamera(camera);          
        };
        setInterval(animateIt, 60/*was 20*/);
      });
      jQuery(window).load(function () {
        jQuery("#tags_cloud_item_<?php echo $bwge; ?>").attr("style", "visibility: visible;");
      });
    </script>
    <div id="bwge_container1_<?php echo $bwge; ?>">
      <div id="bwge_container2_<?php echo $bwge; ?>">
        <div id="tags_cloud_item_<?php echo $bwge; ?>" style="visibility: hidden;">
          <ul>
          <?php
            foreach ($tags as $tag) {
              if ($open_option == 'lightbox') {
                $params_array = array(
                  'tag_id' => $tag->term_id,
                  'action' => 'GalleryBox_bwge',
                  'open_with_fullscreen' => $options_row->popup_fullscreen,
                  'open_with_autoplay' => $options_row->popup_autoplay,
                  'current_view' => $bwge,
                  'image_id' => $tag->image_id,
                  'gallery_id' => 1,
                  'theme_id' => $theme_id,
                  'thumb_width' => $options_row->thumb_width,
                  'thumb_height' => $options_row->thumb_height,
                  'image_width' => $options_row->popup_width,
                  'image_height' => $options_row->popup_height,
                  'image_effect' => $options_row->popup_type,
                  'wd_sor' => 'order',
                  'enable_image_filmstrip' => $options_row->popup_enable_filmstrip,
                  'image_filmstrip_height' => $options_row->popup_filmstrip_height,
                  'enable_image_ctrl_btn' => $options_row->popup_enable_ctrl_btn,
                  'enable_image_fullscreen' => $options_row->popup_enable_fullscreen,
                  'popup_enable_info' => $options_row->popup_enable_info,
                  'popup_info_always_show' => $options_row->popup_info_always_show,
                  'popup_hit_counter' => $options_row->popup_hit_counter,
                  'popup_enable_rate' => $options_row->popup_enable_rate,
                  'thumb_click_action' => $options_row->thumb_click_action,
                  'slideshow_interval' => $options_row->popup_interval,
                  'enable_comment_social' => $options_row->popup_enable_comment,
                  'enable_image_facebook' => $options_row->popup_enable_facebook,
                  'enable_image_twitter' => $options_row->popup_enable_twitter,
                  'enable_image_google' => $options_row->popup_enable_google,
                  'enable_image_pinterest' => $options_row->popup_enable_pinterest,
                  'enable_image_tumblr' => $options_row->popup_enable_tumblr,
                  'watermark_type' => $options_row->watermark_type
                );
                if ($params_array['watermark_type'] != 'none') {
                  $params_array['watermark_link'] = $options_row->watermark_link;
                  $params_array['watermark_opacity'] = $options_row->watermark_opacity;
                  $params_array['watermark_position'] = $options_row->watermark_position;
                }
                if ($params_array['watermark_type'] == 'text') {
                  $params_array['watermark_text'] = $options_row->watermark_text;
                  $params_array['watermark_font_size'] = $options_row->watermark_font_size;
                  $params_array['watermark_font'] = $options_row->watermark_font;
                  $params_array['watermark_color'] = $options_row->watermark_color;
                }
                elseif ($params_array['watermark_type'] == 'image') {
                  $params_array['watermark_url'] = $options_row->watermark_url;
                  $params_array['watermark_width'] = $options_row->watermark_width;
                  $params_array['watermark_height'] = $options_row->watermark_height;
                }
                $params_array['current_url'] = $current_url;
                if ($type == 'text') {
                  ?>
                  <li><a class="bwge_link_widget" onclick="bwge_spider_createpopup('<?php echo addslashes(add_query_arg($params_array, admin_url('admin-ajax.php'))); ?>', '<?php echo $bwge; ?>', '800', '600', 1, 'testpopup', 5, '<?php echo $theme_row->lightbox_ctrl_btn_pos ;?>'); return false;"><?php echo $tag->name; ?></a></li>
                  <?php
                }
                else {
                  
                  $is_embed = preg_match('/EMBED/',$tag->filetype)==1 ? true :false;

                  ?>
                  <li style="text-align: center;">
                    <a class="bwge_link_widget" onclick="bwge_spider_createpopup('<?php echo addslashes(add_query_arg($params_array, admin_url('admin-ajax.php'))); ?>', '<?php echo $bwge; ?>', '800', '600', 1, 'testpopup', 5, '<?php echo $theme_row->lightbox_ctrl_btn_pos ;?>'); return false;">
                      <img id="imgg" src="<?php echo ( $is_embed ? "" : site_url() . '/' . $WD_BWGE_UPLOAD_DIR) . $tag->thumb_url;?>" alt="<?php echo $tag->name ?>" title="<?php echo $show_name ? '' : $tag->name; ?>" /><?php echo $show_name ? '<br />' . $tag->name : ''; ?>
                  </a></li>
                  <?php
                }
              }
              else {
                if ($type == 'text') {
                  ?>
                  <li><a class="tag_cloud_link" href="<?php echo $tag->permalink; ?>"><?php echo $tag->name; ?></a></li>
                  <?php
                }
                else {
                  $is_embed = preg_match('/EMBED/', $tag->filetype) == 1 ? true : false;
                  ?>
                  <li style="text-align: center;">
                    <a class="bwge_link_widget" href="<?php echo $tag->permalink; ?>">
                      <img id="imgg" src="<?php echo ( $is_embed ? "" : site_url() . '/' . $WD_BWGE_UPLOAD_DIR) . $tag->thumb_url;?>" alt="<?php echo $tag->name; ?>" title="<?php echo $show_name ? '' : $tag->name; ?>" /><?php echo $show_name ? '<br />' . $tag->name : ''; ?>
                  </a></li>
                  <?php
                }
              }
            }
          ?>
          </ul>
        </div>
        <div id="bwge_spider_popup_loading_<?php echo $bwge; ?>" class="bwge_spider_popup_loading"></div>
        <div id="bwge_spider_popup_overlay_<?php echo $bwge; ?>" class="bwge_spider_popup_overlay" onclick="bwge_spider_destroypopup(1000)"></div>
      </div>
    </div>
    <?php
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