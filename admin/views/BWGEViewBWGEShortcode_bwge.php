<?php

class BWGEViewBWGEShortcode_bwge {
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
    $gallery_rows = $this->model->get_gallery_rows_data();
    $album_rows = $this->model->get_album_rows_data();
    $option_row = $this->model->get_option_row_data();

    $theme_rows = $this->model->get_theme_rows_data();
    $from_menu = ((isset($_GET['page']) && (esc_html($_GET['page']) == 'BWGEShortcode_bwge')) ? TRUE : FALSE);
    $shortcodes = $this->model->get_shortcode_data();
    $shortcode_max_id = $this->model->get_shortcode_max_id();
    $effects = array(
      'none' => __('None','bwge_back'),
      'cubeH' => __('Cube Horizontal','bwge_back'),
      'cubeV' => __('Cube Vertical','bwge_back'),
      'fade' => __('Fade','bwge_back'),
      'sliceH' => __('Slice Horizontal','bwge_back'),
      'sliceV' => __('Slice Vertical','bwge_back'),
      'slideH' => __('Slide Horizontal','bwge_back'),
      'slideV' => __('Slide Vertical','bwge_back'),
      'scaleOut' => __('Scale Out','bwge_back'),
      'scaleIn' => __('Scale In','bwge_back'),
      'blockScale' => __('Block Scale','bwge_back'),
      'kaleidoscope' => __('Kaleidoscope','bwge_back'),
      'fan' => __('Fan','bwge_back'),
      'blindH' => __('Blind Horizontal','bwge_back'),
      'blindV' => __('Blind Vertical','bwge_back'),
      'random' => __('Random','bwge_back'),
    );
    $watermark_fonts = array(
      'arial' => 'Arial',
      'Lucida grande' => 'Lucida grande',
      'segoe ui' => 'Segoe ui',
      'tahoma' => 'Tahoma',
      'trebuchet ms' => 'Trebuchet ms',
      'verdana' => 'Verdana',
      'cursive' =>'Cursive',
      'fantasy' => 'Fantasy',
      'monospace' => 'Monospace',
      'serif' => 'Serif',
    );
    if (!$from_menu) {
    ?>
    <html xmlns="http://www.w3.org/1999/xhtml">
      <head>
        <title><?php echo __('Gallery Ecommerce', 'bwge_back'); ?></title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <script language="javascript" type="text/javascript" src="<?php echo site_url(); ?>/wp-includes/js/tinymce/tiny_mce_popup.js"></script>
        <script language="javascript" type="text/javascript" src="<?php echo site_url(); ?>/wp-includes/js/tinymce/utils/mctabs.js"></script>
        <script language="javascript" type="text/javascript" src="<?php echo site_url(); ?>/wp-includes/js/tinymce/utils/form_utils.js"></script>
      <?php
      wp_print_scripts('jquery');
    }
    wp_print_scripts('jquery-ui-core');
    wp_print_scripts('jquery-ui-widget');
    wp_print_scripts('jquery-ui-position');
    wp_print_scripts('jquery-ui-tooltip');
    wp_print_scripts('wp-pointer');
    ?>
        <link media="all" type="text/css" href="<?php echo get_admin_url(); ?>load-styles.php?c=1&amp;dir=ltr&amp;load=dashicons,wp-pointer" rel="stylesheet">
        <link rel="stylesheet" href="<?php echo WD_BWGE_URL . '/css/bwge_shortcode.css?ver='.wd_bwge_version(); ?>">
        <link rel="stylesheet" href="<?php echo WD_BWGE_URL . '/css/bwge_ecommerce.css?ver='.wd_bwge_version(); ?>">
        <link rel="stylesheet" href="<?php echo WD_BWGE_URL . '/css/jquery-ui-1.10.3.custom.css'; ?>">
        <script language="javascript" type="text/javascript" src="<?php echo WD_BWGE_URL . '/js/bwge_shortcode.js?ver='.wd_bwge_version(); ?>"></script>
        <script language="javascript" type="text/javascript" src="<?php echo WD_BWGE_URL . '/js/jscolor/jscolor.js?ver='.wd_bwge_version(); ?>"></script>
        <?php
        if (!$from_menu) {
        ?>
        <base target="_self">
      </head>
      <body id="link" onLoad="tinyMCEPopup.executeOnLoad('init();');document.body.style.display='';" dir="ltr" class="forceColors">
        <?php if (isset($_POST['tagtext'])) { echo '<script>tinyMCEPopup.close();</script></body></html>'; die(); } ?>
        <div>
        <form method="post" action="#" id="bwge_shortcode_form">
          <?php wp_nonce_field( 'BWGEShortcode_bwge', 'bwge_nonce' ); ?>
          <div class="tabs" role="tablist" tabindex="-1" style="margin:0;">
            <ul>
              <li id="display_tab" class="current" role="tab" tabindex="0">
                <span>
                  <a href="javascript:mcTabs.displayTab('display_tab','display_panel');" onMouseDown="return false;" tabindex="-1"><?php echo __('Display', 'bwge_back'); ?></a>
                </span>
              </li>
            </ul>
          </div>
          <div class="panel_wrapper">
            <div id="display_panel" class="panel current">
        <?php
        }
        else {
        ?>
        <div class="bwge wd-clear">
        <form method="post" action="#" id="bwge_shortcode_form">
          <?php wp_nonce_field( 'BWGEShortcode_bwge', 'bwge_nonce' ); ?>
          <div id="display_panel" style="width: 99%; margin-top: 30px;">
        <?php
        }
        ?>
              <div style="text-align:center; <?php if(!$from_menu) echo "height:85px;";?>">
                <span class="gallery_type" onClick="bwge_gallery_type('thumbnails')">
                  <div style="text-align: center;"><input type="radio" id="thumbnails" name="gallery_type" value="thumbnails" /><label for="thumbnails"><?php echo __('Thumbnails', 'bwge_back'); ?></label></div>
                  <label for="thumbnails"><img src="<?php echo WD_BWGE_URL . '/images/thumbnails.jpg'; ?>" id="display_thumb"/></label>
                </span>
                <span class="gallery_type" title="<?php echo __('This option is disabled in free version.', 'bwge_back'); ?>">
                  <div style="text-align: center;"><input type="radio" id="thumbnails_masonry" name="gallery_type" value="thumbnails_masonry" disabled readonly /><label for="thumbnails_masonry"><?php echo __('Masonry', 'bwge_back'); ?></label></div>
                  <label for="thumbnails_masonry"><img src="<?php echo WD_BWGE_URL . '/images/thumbnails_masonry.jpg'; ?>" style="opacity:0.5; filter: Alpha(opacity=50);" /></label>
                </span>
                <span class="gallery_type" title="<?php echo __('This option is disabled in free version.', 'bwge_back'); ?>">
                  <div style="text-align: center;"><input type="radio" id="thumbnails_mosaic" name="gallery_type" value="thumbnails_mosaic" disabled readonly /><label for="thumbnails_mosaic"><?php echo __('Mosaic', 'bwge_back'); ?></label></div>
                  <label for="thumbnails_mosaic"><img src="<?php echo WD_BWGE_URL . '/images/thumbnails_mosaic.jpg'; ?>" style="opacity:0.5; filter: Alpha(opacity=50);" /></label>
                </span>
                <span class="gallery_type" onClick="bwge_gallery_type('slideshow')">
                  <div style="text-align: center;"><input type="radio" id="slideshow" name="gallery_type" value="slideshow" /><label for="slideshow"><?php echo __('Slideshow', 'bwge_back'); ?></label></div>
                  <label for="slideshow"><img src="<?php echo WD_BWGE_URL . '/images/slideshow.jpg'; ?>" /></label>
                </span>
                <span class="gallery_type" onClick="bwge_gallery_type('image_browser')">
                  <div style="text-align: center;"><input type="radio" id="image_browser" name="gallery_type" value="image_browser" /><label for="image_browser"><?php echo __('Image Browser', 'bwge_back'); ?></label></div>
                  <label for="image_browser"><img src="<?php echo WD_BWGE_URL . '/images/image_browser.jpg'; ?>" /></label>
                </span>
                <span class="gallery_type" onClick="bwge_gallery_type('album_compact_preview')">
                  <div style="text-align: center;"><input type="radio" id="album_compact_preview" name="gallery_type" value="album_compact_preview" /><label for="album_compact_preview"><?php echo __('Compact Album', 'bwge_back'); ?></label></div>
                  <label for="album_compact_preview"><img src="<?php echo WD_BWGE_URL . '/images/album_compact_preview.jpg'; ?>" /></label>
                </span>
                <span class="gallery_type" title="<?php echo __('This option is disabled in free version.', 'bwge_back'); ?>" >
                  <div style="text-align: center;"><input type="radio" id="album_masonry_preview" name="gallery_type" value="album_masonry_preview" disabled readonly /><label for="album_masonry_preview"><?php echo __('Masonry Album', 'bwge_back'); ?></label></div>
                  <label for="album_masonry_preview"><img src="<?php echo WD_BWGE_URL . '/images/thumbnails_masonry.jpg'; ?>" style="opacity:0.5; filter: Alpha(opacity=50);" /></label>
                </span>
                <span class="gallery_type" onClick="bwge_gallery_type('album_extended_preview')">
                  <div style="text-align: center;"><input type="radio" id="album_extended_preview" name="gallery_type" value="album_extended_preview" /><label for="album_extended_preview"><?php echo __('Extended Album', 'bwge_back'); ?></label></div>
                  <label for="album_extended_preview"><img src="<?php echo WD_BWGE_URL . '/images/album_extended_preview.jpg'; ?>" /></label>
                </span>
                <span class="gallery_type" title="<?php echo __('This option is disabled in free version.', 'bwge_back'); ?>" >
                  <div style="text-align: center;"><input type="radio" id="blog_style" name="gallery_type" value="blog_style" disabled readonly /><label for="blog_style"><?php echo __('Blog Style', 'bwge_back'); ?></label></div>
                  <label for="blog_style"><img src="<?php echo WD_BWGE_URL . '/images/blog_style.jpg'; ?>" style="opacity:0.5; filter: Alpha(opacity=50);" /></label>
                </span>			
			   <span class="gallery_type" title="<?php echo __('This option is disabled in free version.', 'bwge_back'); ?>">
                  <div style="text-align: center;"><input type="radio" id="carousel" name="gallery_type" value="carousel" disabled readonly /><label for="carousel"><?php echo __('Carousel', 'bwge_back'); ?></label></div>
                  <label for="carousel"><img src="<?php echo WD_BWGE_URL . '/images/Carousel.png'; ?>" style="opacity:0.5; filter: Alpha(opacity=50);" /></label>
                </span>
              </div>
              <hr />
              <div class="bwge_short_div" style="border-right: 1px solid #ccc; <?php if(!$from_menu) echo "height:630px;";?>">
              <table>
                <tbody>
                  <tr id="tr_theme">
                    <td class="bwge_spider_label" title="<?php echo __('This option is disabled in free version.', 'bwge_back'); ?>"><label for="theme" class="bwge_disabled_label"><?php echo __('Theme:', 'bwge_back'); ?> </label></td>
                    <td>
                      <select name="theme" id="theme" style="width:150px;" class="bwge_disabled_field" disabled readonly>
                        <option value="0" selected="selected"><?php echo __('Select Theme', 'bwge_back'); ?></option>
                      </select>
                      
                    </td>
                  </tr>
                  <tr id="tr_gallery">
                    <td class="bwge_spider_label"><label for="gallery"><?php echo __('Gallery:', 'bwge_back'); ?> </label></td>
                    <td>
                      <select name="gallery" id="gallery" style="width:150px;">
                        <option value="0" selected="selected"><?php echo __('Select Gallery', 'bwge_back'); ?></option>
                        <?php
                        foreach ($gallery_rows as $gallery_row) {
                          ?>
                          <option value="<?php echo $gallery_row->id; ?>"><?php echo $gallery_row->name; ?></option>
                          <?php
                        }
                        ?>
                      </select>
                    </td>
                  </tr>
                  <tr id="tr_album">
                    <td title="<?php echo __('The selected album expanded content will be displayed.', 'bwge_back'); ?>" class="bwge_spider_label"><label for="album"><?php echo __('Album:', 'bwge_back'); ?> </label></td>
                    <td>
                      <select name="album" id="album" style="width:150px;">
                        <option value="0" selected="selected"><?php echo __('Select Album', 'bwge_back'); ?></option>
                        <?php
                        foreach ($album_rows as $album_row) {
                          ?>
                          <option value="<?php echo $album_row->id; ?>"><?php echo $album_row->name; ?></option>
                          <?php
                        }
                        ?>
                      </select>
                    </td>
                  </tr>
                  <tr id="tr_sort_by">
                    <td class="bwge_spider_label"><label for="sort_by"><?php echo __('Sort images by:', 'bwge_back'); ?> </label></td>
                    <td>
                      <select name="sort_by" id="sort_by" style="width:150px;">
                        <option value="order" selected="selected"><?php echo __('Order', 'bwge_back'); ?></option>
                        <option value="alt"><?php echo __('Title', 'bwge_back'); ?></option>
                        <option value="date"><?php echo __('Date', 'bwge_back'); ?></option>
                        <option value="filename"><?php echo __('Filename', 'bwge_back'); ?></option>
                        <option value="size"><?php echo __('Size', 'bwge_back'); ?></option>
                        <option value="filetype"><?php echo __('Type', 'bwge_back'); ?></option>
                        <option value="resolution"><?php echo __('Resolution', 'bwge_back'); ?></option>
                        <option value="random"><?php echo __('Random', 'bwge_back'); ?></option>
                      </select>
                    </td>
                  </tr>
                  <tr id="tr_order_by">
                    <td class="bwge_spider_label"><label><?php echo __('Order images:', 'bwge_back'); ?> </label></td>
                    <td>
                      <input type="radio" name="order_by" id="order_by_1" value="asc" checked="checked" /><label for="order_by_1"><?php echo __('Ascending', 'bwge_back'); ?></label>
                      <input type="radio" name="order_by" id="order_by_0" value="desc" /><label for="order_by_0"><?php echo __('Descending', 'bwge_back'); ?></label>
                    </td>
                  </tr>
                  <tr id="tr_show_search_box">
                    <td class="bwge_spider_label"><label><?php echo __('Show search box:', 'bwge_back'); ?> </label></td>
                    <td>
                      <input type="radio" name="show_search_box" id="show_search_box_1" value="1" <?php if ($option_row->show_search_box) echo 'checked="checked"'; ?> onchange="bwge_show_search_box()" /><label for="show_search_box_1"><?php echo __('Yes', 'bwge_back'); ?></label>
                      <input type="radio" name="show_search_box" id="show_search_box_0" value="0" <?php if (!$option_row->show_search_box) echo 'checked="checked"'; ?> onchange="bwge_show_search_box()" /><label for="show_search_box_0"><?php echo __('No', 'bwge_back'); ?></label>
                    </td>
                  </tr>
                  <tr id="tr_search_box_width">
                    <td class="bwge_spider_label"><label for="search_box_width"><?php echo __('Search box width:', 'bwge_back'); ?> </label></td>
                    <td><input type="text" name="search_box_width" id="search_box_width" value="<?php echo $option_row->search_box_width; ?>" class="bwge_spider_int_input" /> px</td>
                  </tr>
                  <tr id="tr_show_tag_box">
                    <td class="bwge_spider_label"><label><?php echo __('Show tag box:', 'bwge_back'); ?> </label></td>
                    <td>
                      <input type="radio" name="show_tag_box" id="show_tag_box_1" value="1" <?php if ($option_row->show_tag_box) echo 'checked="checked"'; ?> /><label for="show_tag_box_1"><?php echo __('Yes', 'bwge_back'); ?></label>
                      <input type="radio" name="show_tag_box" id="show_tag_box_0" value="0" <?php if (!$option_row->show_tag_box) echo 'checked="checked"'; ?> /><label for="show_search_box_0"><?php echo __('No', 'bwge_back'); ?></label>
                    </td>
                  </tr>
									<tr id="tr_show_sort_images">
                    <td class="bwge_spider_label"><label><?php echo __('Show "Order by" dropdown list:', 'bwge_back'); ?> </label></td>
                    <td>
                      <input type="radio" name="show_sort_images" id="show_sort_images_1" value="1" <?php if ($option_row->show_sort_images) echo 'checked="checked"'; ?> onchange="" /><label for="show_sort_images_1"><?php echo __('Yes', 'bwge_back'); ?></label>
                      <input type="radio" name="show_sort_images" id="show_sort_images_0" value="0" <?php if (!$option_row->show_sort_images) echo 'checked="checked"'; ?> onchange="" /><label for="show_sort_images_0"><?php echo __('No', 'bwge_back'); ?></label>
                    </td>
                  </tr>
                  <!--Thumbnails, Masonry viewies-->
                  <tr id="tr_masonry_hor_ver">
                    <td class="bwge_spider_label"><label><?php echo __('Masonry:', 'bwge_back'); ?> </label></td>
                    <td>
                      <input type="radio" name="masonry_hor_ver" id="masonry_ver" value="vertical" onclick="bwge_change_label('image_column_number_label', 'Number of image rows: ');
                                                                                                            bwge_change_label('thumb_width_height_label', '<?php echo addslashes(__('Image thumbnail width:', 'bwge_back')); ?> ');
                                                                                                            jQuery('#thumb_width').show();
                                                                                                            jQuery('#thumb_height').hide();
                                                                                                            jQuery('#thumb_width_height_separator').hide();" <?php echo ($option_row->masonry == 'vertical') ? 'checked' : ''; ?> /><label for="masonry_ver"><?php echo __('Vertical', 'bwge_back'); ?></label>
                      <input type="radio" name="masonry_hor_ver" id="masonry_hor" value="horizontal" onclick="bwge_change_label('image_column_number_label', '<?php echo addslashes(__('Max. number of image columns: ', 'bwge_back')); ?>');
                                                                                                              bwge_change_label('thumb_width_height_label', 'Image Thumbnail Height: ');
                                                                                                              jQuery('#thumb_width').hide();
                                                                                                              jQuery('#thumb_height').show();
                                                                                                              jQuery('#thumb_width_height_separator').hide();" <?php echo ($option_row->masonry == 'horizontal') ? 'checked' : ''; ?> /><label for="masonry_hor"><?php echo __('Horizontal', 'bwge_back'); ?></label>
                    </td>
                  </tr>
                  <!--Thumbnails, Mosaic viewies-->
                  <tr id="tr_mosaic_hor_ver">
                    <td class="bwge_spider_label"><label><?php echo __('Mosaic:', 'bwge_back'); ?> </label></td>
                    <td>
                      <input type="radio" name="mosaic_hor_ver" id="mosaic_ver" value="vertical" onclick="bwge_change_label('image_column_number_label', 'Number of image rows: ');
                                                                                                            bwge_change_label('thumb_width_height_label', '<?php echo addslashes(__('Image thumbnail width:', 'bwge_back')); ?> ');
                                                                                                            jQuery('#thumb_width').show();
                                                                                                            jQuery('#thumb_height').hide();
                                                                                                            jQuery('#thumb_width_height_separator').hide();" <?php echo ($option_row->mosaic == 'vertical') ? 'checked' : ''; ?> /><label for="mosaic_ver"><?php echo __('Vertical', 'bwge_back'); ?></label>
                      <input type="radio" name="mosaic_hor_ver" id="mosaic_hor" value="horizontal" onclick="bwge_change_label('image_column_number_label', '<?php echo addslashes(__('Max. number of image columns: ', 'bwge_back')); ?>');
                                                                                                              bwge_change_label('thumb_width_height_label', 'Image Thumbnail Height: ');
                                                                                                              jQuery('#thumb_width').hide();
                                                                                                              jQuery('#thumb_height').show();
                                                                                                              jQuery('#thumb_width_height_separator').hide();" <?php echo ($option_row->mosaic == 'horizontal') ? 'checked' : ''; ?> /><label for="mosaic_hor"><?php echo __('Horizontal', 'bwge_back'); ?></label>
                    
                  
                    </td>
                  </tr>
                  <tr id="tr_resizable_mosaic">
                    <td title="<?php echo __('Mosaic thumbnails do not have fixed size, but are proportional to the width of the parent container. This option keeps thumbs to look nice when viewed with very large or very small screen. Prevents zooming of thumbs.', 'bwge_back'); ?>" class="bwge_spider_label"><label for="resizable_mosaic"><?php echo __('Resizable mosaic', 'bwge_back'); ?></label></td>
                    <td>
                      <input type="radio" name="resizable_mosaic" id="resizable_mosaic_1" value="1" <?php echo ($option_row->resizable_mosaic == 1) ? 'checked' : ''; ?> /><label for="resizable_mosaic_1"><?php echo __('Yes', 'bwge_back'); ?></label>
                      <input type="radio" name="resizable_mosaic" id="resizable_mosaic_0" value="0" <?php echo ($option_row->resizable_mosaic == 0) ? 'checked' : ''; ?> /><label for="resizable_mosaic_0"><?php echo __('No', 'bwge_back'); ?></label>
                      <br />
                    </td>
                  </tr>
                  <tr id="tr_mosaic_total_width">
                    <td title="<?php echo __("Percentage of container's width", 'bwge_back'); ?>" class="bwge_spider_label"><label for="mosaic_total_width"><?php echo __('Total width of mosaic:', 'bwge_back'); ?> </label></td>
                    <td><input type="text" name="mosaic_total_width" id="mosaic_total_width" value="<?php echo $option_row->mosaic_total_width; ?>" class="bwge_spider_int_input" /> %</td>
                  </tr>
                 <!--Thumbnails, Masonry and Mosaic viewies-->
                  <tr id="tr_image_column_number">
                    <td class="bwge_spider_label"><label id="image_column_number_label" for="image_column_number"><?php echo __('Max. number of image columns:', 'bwge_back'); ?> </label></td>
                    <td><input type="text" name="image_column_number" id="image_column_number" value="<?php echo $option_row->image_column_number; ?>" class="bwge_spider_int_input" /></td>
                  </tr>
                  <tr id="tr_images_per_page">
                    <td title="<?php echo __('If you want to display all images you should leave it blank or insert 0.', 'bwge_back'); ?>" class="bwge_spider_label"><label for="images_per_page"><?php echo __('Images per page:', 'bwge_back'); ?> </label></td>
                    <td><input type="text" name="images_per_page" id="images_per_page" value="<?php echo $option_row->images_per_page; ?>" class="bwge_spider_int_input" /></td>
                  </tr>
    
                  <tr id="tr_ecommerce_icon_hover">
                    <td class="bwge_spider_label"><label>Ecommerce icon: </label></td>
                    <td>
                      <input type="radio" name="ecommerce_icon" id="ecommerce_icon_hover" value="hover" <?php echo ($option_row->ecommerce_icon_show_hover == 'hover') ? 'checked' : ''; ?> /><label for="ecommerce_icon_hover">Show on hover</label><br />
                      <span class="ecommerce_icon_show">
                      <input type="radio" name="ecommerce_icon" id="ecommerce_icon_show" value="show" <?php echo ($option_row->ecommerce_icon_show_hover == 'show') ? 'checked' : ''; ?> /><label for="ecommerce_icon_show">Always show</label><br />
                      </span>
                      <input type="radio" name="ecommerce_icon" id="ecommerce_icon_none" value="none" <?php echo ($option_row->ecommerce_icon_show_hover == 'none') ? 'checked' : ''; ?> /><label for="ecommerce_icon_none">Don't show</label>
                    </td>
                  </tr> 

                         
                  <tr id="tr_image_title_hover">
                    <td class="bwge_spider_label"><label><?php echo __('Image title:', 'bwge_back'); ?> </label></td>
                    <td>
                      <input type="radio" name="image_title" id="image_title_hover" value="hover" <?php echo ($option_row->image_title_show_hover == 'hover') ? 'checked' : ''; ?> /><label for="image_title_hover"><?php echo __('Show on hover', 'bwge_back'); ?></label><br />
                      <input type="radio" name="image_title" id="image_title_show" value="show" <?php echo ($option_row->image_title_show_hover == 'show') ? 'checked' : ''; ?> /><label for="image_title_show"><?php echo __('Always show', 'bwge_back'); ?></label><br />
                      <input type="radio" name="image_title" id="image_title_none" value="none" <?php echo ($option_row->image_title_show_hover == 'none') ? 'checked' : ''; ?> /><label for="image_title_none"><?php echo __("Don't show", 'bwge_back'); ?></label>
                    </td>
                  </tr>
                  <tr id="tr_image_enable_page">
                    <td class="bwge_spider_label"><label><?php echo __('Enable pagination:', 'bwge_back'); ?> </label></td>
                    <td>
                      <input type="radio" name="image_enable_page" class="hide_load_count" id="image_page_yes" value="1" <?php echo ($option_row->image_enable_page == '1') ? 'checked' : ''; ?> onchange="bwge_loadmore()"/><label for="image_page_yes"><?php echo __('Yes', 'bwge_back'); ?></label>
                      <input type="radio" name="image_enable_page" class="hide_load_count" id="image_page_no" value="0" <?php echo ($option_row->image_enable_page == '0') ? 'checked' : ''; ?> onchange="bwge_loadmore()"/><label for="image_page_no"><?php echo __('No', 'bwge_back'); ?></label>
                      <input type="radio" name="image_enable_page" id="image_page_loadmore" value="2" <?php echo ($option_row->image_enable_page == '2') ? 'checked' : ''; ?> onchange="bwge_loadmore()"/><label for="image_page_loadmore"><?php echo __('Load More', 'bwge_back'); ?></label>
                      <input type="radio" name="image_enable_page" id="image_page_scrol_load" value="3" <?php echo ($option_row->image_enable_page == '3') ? 'checked' : ''; ?> onchange="bwge_loadmore()"/><label for="image_page_scrol_load"><?php echo __('Scroll Load', 'bwge_back'); ?></label>
                    </td>
                  </tr>
                  <tr id="tr_load_more_image_count">
                    <td class="bwge_spider_label"><label for="load_more_image_count"><?php echo __('Images per load:', 'bwge_back'); ?> </label></td>
                    <td><input type="text" name="load_more_image_count" id="load_more_image_count" value="<?php echo $option_row->images_per_page; ?>" class="bwge_spider_int_input" /></td>
                  </tr> 
                  <tr id="tr_thumb_width_height">
                    <td title="<?php echo __('Maximum values for thumbnail dimension.', 'bwge_back'); ?>" class="bwge_spider_label"><label id="thumb_width_height_label" for="thumb_width"><?php echo __('Image Thumbnail dimensions:', 'bwge_back'); ?> </label></td>
                    <td>
                      <input type="text" name="thumb_width" id="thumb_width" value="<?php echo $option_row->thumb_width; ?>" class="bwge_spider_int_input" /><span id="thumb_width_height_separator"> x </span>
                      <input type="text" name="thumb_height" id="thumb_height" value="<?php echo $option_row->thumb_height; ?>" class="bwge_spider_int_input" /> px
                    </td>
                  </tr>

                  <!--Compact Album view-->
                  <tr id="tr_compuct_album_column_number">
                    <td class="bwge_spider_label"><label for="compuct_album_column_number"><?php echo __('Max. number of album columns:', 'bwge_back'); ?> </label></td>
                    <td><input type="text" name="compuct_album_column_number" id="compuct_album_column_number" value="<?php echo $option_row->album_column_number; ?>" class="bwge_spider_int_input" /></td>
                  </tr>
                  <tr id="tr_compuct_albums_per_page">
                    <td title="<?php echo __('If you want to display all albums you should leave it blank or insert 0.', 'bwge_back'); ?>" class="bwge_spider_label"><label for="compuct_albums_per_page"><?php echo __('Albums per page:', 'bwge_back'); ?> </label></td>
                    <td><input type="text" name="compuct_albums_per_page" id="compuct_albums_per_page" value="<?php echo $option_row->albums_per_page; ?>" class="bwge_spider_int_input" /></td>
                  </tr>
                  <tr id="tr_compuct_album_title_hover">
                    <td class="bwge_spider_label"><label><?php echo __('Album title:', 'bwge_back'); ?> </label></td>
                    <td>
                      <input type="radio" name="compuct_album_title" id="compuct_album_title_hover" value="hover" <?php echo ($option_row->album_title_show_hover == 'hover') ? 'checked' : ''; ?> /><label for="compuct_album_title_hover"><?php echo __('Show on hover', 'bwge_back'); ?></label><br />
                      <input type="radio" name="compuct_album_title" id="compuct_album_title_show" value="show" <?php echo ($option_row->album_title_show_hover == 'show') ? 'checked' : ''; ?> /><label for="compuct_album_title_show"><?php echo __('Always show', 'bwge_back'); ?></label><br />
                      <input type="radio" name="compuct_album_title" id="compuct_album_title_none" value="none" <?php echo ($option_row->album_title_show_hover == 'none') ? 'checked' : ''; ?> /><label for="compuct_album_title_none"><?php echo __("Don't show", 'bwge_back'); ?></label>
                    </td>
                  </tr>
                  <tr id="tr_compuct_album_thumb_width_height">
                    <td title="<?php echo __('Maximum values for album thumb width and height.', 'bwge_back'); ?>" class="bwge_spider_label"><label for="compuct_album_thumb_width"><?php echo __('Album Thumbnail dimensions:', 'bwge_back'); ?> </label></td>
                    <td>
                      <input type="text" name="compuct_album_thumb_width" id="compuct_album_thumb_width" value="<?php echo $option_row->album_thumb_width; ?>" class="bwge_spider_int_input" /> x 
                      <input type="text" name="compuct_album_thumb_height" id="compuct_album_thumb_height" value="<?php echo $option_row->album_thumb_height; ?>" class="bwge_spider_int_input" /> px
                    </td>
                  </tr>
                  <tr id="tr_compuct_album_view_type">
                    <td title="<?php echo __('This option is disabled in free version.', 'bwge_back'); ?>" class="bwge_spider_label"><label class="bwge_disabled_label"><?php echo __('Album view type:', 'bwge_back'); ?> </label></td>
                    <td>
                      <input type="radio" name="compuct_album_view_type" id="compuct_album_view_bwge_type1" value="thumbnail" <?php if ($option_row->album_view_type == "thumbnail") echo 'checked="checked"'; ?> class="bwge_disabled_field" disabled readonly /><label for="compuct_album_view_bwge_type1" class="bwge_disabled_label"><?php echo __('Thumbnail', 'bwge_back'); ?></label>
                      <input type="radio" name="compuct_album_view_type" id="compuct_album_view_bwge_type0" value="masonry" <?php if ($option_row->album_view_type == "masonry") echo 'checked="checked"'; ?> class="bwge_disabled_field" disabled readonly /><label for="compuct_album_view_bwge_type0" class="bwge_disabled_label"><?php echo __('Masonry', 'bwge_back'); ?></label>
                      <input type="radio" name="compuct_album_view_type" id="compuct_album_view_bwge_type2" value="mosaic" <?php if ($option_row->album_view_type == "mosaic") echo 'checked="checked"'; ?> class="bwge_disabled_field" disabled readonly /><label for="compuct_album_view_bwge_type2" class="bwge_disabled_label"><?php echo __('Mosaic', 'bwge_back'); ?></label>
                     
                    </td>
                  </tr>
                  <tr id="tr_compuct_album_mosaic_hor_ver">
                    <td class="bwge_spider_label"><label><?php echo __('Mosaic:', 'bwge_back'); ?> </label></td>
                    <td>
                      <input type="radio" name="compuct_album_mosaic_hor_ver" id="compuct_album_mosaic_ver" value="vertical" onclick="bwge_change_label('compuct_album_image_column_number', 'Number of image rows: ');
                                                                                                            bwge_change_label('compuct_album_image_thumb_dimensions', '<?php echo __('Image thumbnail width:', 'bwge_back'); ?> ');
                                                                                                            jQuery('#compuct_album_image_thumb_width').show();
                                                                                                            jQuery('#compuct_album_image_thumb_height').hide();
                                                                                                            jQuery('#compuct_album_image_thumb_dimensions_x').hide();" <?php echo ($option_row->mosaic == 'vertical') ? 'checked' : ''; ?> /><label for="compuct_album_mosaic_ver"><?php echo __('Vertical', 'bwge_back'); ?></label>
                      <input type="radio" name="compuct_album_mosaic_hor_ver" id="compuct_album_mosaic_hor" value="horizontal" onclick="bwge_change_label('compuct_album_image_column_number', '<?php echo __('Max. number of image columns: ', 'bwge_back'); ?>');
                                                                                                              bwge_change_label('compuct_album_image_thumb_dimensions', 'Image thumbnail height: ');
                                                                                                              jQuery('#compuct_album_image_thumb_width').hide();
                                                                                                              jQuery('#compuct_album_image_thumb_height').show();
                                                                                                              jQuery('#compuct_album_image_thumb_dimensions_x').hide();" <?php echo ($option_row->mosaic == 'horizontal') ? 'checked' : ''; ?> /><label for="compuct_album_mosaic_hor"><?php echo __('Horizontal', 'bwge_back'); ?></label>
                    
                  
                    </td>
                  </tr>
                  <tr id="tr_compuct_album_resizable_mosaic">
                    <td title="<?php echo __('Mosaic thumbnails do not have fixed size, but are proportional to the width of the parent container. This option keeps thumbs to look nice when viewed with very large or very small screen. Prevents zooming of thumbs.', 'bwge_back'); ?>" class="bwge_spider_label"><label for="compuct_album_resizable_mosaic"><?php echo __('Resizable mosaic', 'bwge_back'); ?></label></td>
                    <td>
                      <input type="radio" name="compuct_album_resizable_mosaic" id="compuct_album_resizable_mosaic_1" value="1" <?php echo ($option_row->resizable_mosaic == 1) ? 'checked' : ''; ?> /><label for="compuct_album_resizable_mosaic_1"><?php echo __('Yes', 'bwge_back'); ?></label>
                      <input type="radio" name="compuct_album_resizable_mosaic" id="compuct_album_resizable_mosaic_0" value="0" <?php echo ($option_row->resizable_mosaic == 0) ? 'checked' : ''; ?> /><label for="compuct_album_resizable_mosaic_0"><?php echo __('No', 'bwge_back'); ?></label>
                      <br />
                    </td>
                  </tr>
                  <tr id="tr_compuct_album_mosaic_total_width">
                    <td title="<?php echo __("Percentage of container's width", 'bwge_back'); ?>" class="bwge_spider_label"><label for="compuct_album_mosaic_total_width">Total width of mosaic: </label></td>
                    <td><input type="text" name="compuct_album_mosaic_total_width" id="compuct_album_mosaic_total_width" value="<?php echo $option_row->mosaic_total_width; ?>" class="bwge_spider_int_input" /> <?php echo __('percent', 'bwge_back'); ?></td>
                  </tr>
                  <tr id="tr_compuct_album_image_column_number">
                    <td class="bwge_spider_label"><label for="compuct_album_image_column_number"><?php echo __('Max. number of image columns:', 'bwge_back'); ?> </label></td>
                    <td><input type="text" name="compuct_album_image_column_number" id="compuct_album_image_column_number" value="<?php echo $option_row->image_column_number; ?>" class="bwge_spider_int_input" /></td>
                  </tr>
                  <tr id="tr_compuct_album_images_per_page">
                    <td title="<?php echo __("If you want to display all images you should leave it blank or insert 0.", 'bwge_back'); ?>" class="bwge_spider_label"><label for="compuct_album_images_per_page"><?php echo __('Images per page:', 'bwge_back'); ?> </label></td>
                    <td><input type="text" name="compuct_album_images_per_page" id="compuct_album_images_per_page" value="<?php echo $option_row->images_per_page; ?>" class="bwge_spider_int_input" /></td>
                  </tr>
                  <tr id="tr_compuct_album_image_title">
                    <td class="bwge_spider_label"><label><?php echo __('Image title:', 'bwge_back'); ?> </label></td>
                    <td>
                      <input type="radio" name="compuct_album_image_title" id="compuct_album_image_title_hover" value="hover" <?php echo ($option_row->image_title_show_hover == 'hover') ? 'checked' : ''; ?> /><label for="compuct_album_image_title_hover"><?php echo __('Show on hover', 'bwge_back'); ?></label><br />
                      <input type="radio" name="compuct_album_image_title" id="compuct_album_image_title_show" value="show" <?php echo ($option_row->image_title_show_hover == 'show') ? 'checked' : ''; ?> /><label for="compuct_album_image_title_show"><?php echo __('Always show', 'bwge_back'); ?></label><br />
                      <input type="radio" name="compuct_album_image_title" id="compuct_album_image_title_none" value="none" <?php echo ($option_row->image_title_show_hover == 'none') ? 'checked' : ''; ?> /><label for="compuct_album_image_title_none"><?php echo __("Don't show", 'bwge_back'); ?></label>
                    </td>
                  </tr>
                  <tr id="tr_compuct_album_image_thumb_width_height">
                    <td title="<?php echo __("Maximum values for thumbnail width and height.", 'bwge_back'); ?>" class="bwge_spider_label"><label for="compuct_album_image_thumb_width" id="compuct_album_image_thumb_dimensions"><?php echo __('Image thumbnail dimensions:', 'bwge_back'); ?> </label></td>
                    <td>
                      <input type="text" name="compuct_album_image_thumb_width" id="compuct_album_image_thumb_width" value="<?php echo $option_row->thumb_width; ?>" class="bwge_spider_int_input" /><span id="compuct_album_image_thumb_dimensions_x" > x </span>
                      <input type="text" name="compuct_album_image_thumb_height" id="compuct_album_image_thumb_height" value="<?php echo $option_row->thumb_height; ?>" class="bwge_spider_int_input" /> px
                    </td>
                  </tr>
                  <tr id="tr_compuct_album_enable_page">
                    <td class="bwge_spider_label"><label><?php echo __('Enable pagination:', 'bwge_back'); ?> </label></td>
                    <td>
                      <input type="radio" name="compuct_album_enable_page" class="hide_load_count" id="compuct_album_page_yes" value="1" <?php echo ($option_row->album_enable_page == '1') ? 'checked' : ''; ?> onchange="bwge_loadmore()"/><label for="compuct_album_page_yes"><?php echo __('Yes', 'bwge_back'); ?></label>
                      <input type="radio" name="compuct_album_enable_page"  class="hide_load_count"  id="compuct_album_page_no" value="0" <?php echo ($option_row->album_enable_page == '0') ? 'checked' : ''; ?> onchange="bwge_loadmore()" /><label for="compuct_album_page_no"><?php echo __('No', 'bwge_back'); ?></label>  
                      <input type="radio" name="compuct_album_enable_page" id="compuct_album_page_loadmore" value="2" <?php echo ($option_row->album_enable_page == '2') ? 'checked' : ''; ?> onchange="bwge_loadmore()" /><label for="compuct_album_page_loadmore"><?php echo __('Load More', 'bwge_back'); ?></label>
                       <input type="radio" name="compuct_album_enable_page" id="compuct_album_page_scrol_load" value="3" <?php echo ($option_row->album_enable_page == '3') ? 'checked' : ''; ?> onchange="bwge_loadmore()" /><label for="compuct_album_page_scrol_load"><?php echo __('Scroll Load', 'bwge_back'); ?></label>
                    </td>
                  </tr>
                   <tr id="tr_compuct_albums_per_page_load_more">
                    <td title="<?php echo __("If you want to display all albums you should leave it blank or insert 0.", 'bwge_back'); ?>" class="bwge_spider_label"><label for="compuct_albums_per_page_load_more"><?php echo __('Albums per load:', 'bwge_back'); ?> </label></td>
                    <td><input type="text" name="compuct_albums_per_page_load_more" id="compuct_albums_per_page_load_more" value="<?php echo $option_row->albums_per_page; ?>" class="bwge_spider_int_input" /></td>
                  </tr>
                   <tr id="tr_compuct_album_load_more_image_count">
                    <td class="bwge_spider_label"><label for="compuct_album_load_more_image_count"><?php echo __('Images per load:', 'bwge_back'); ?> </label></td>
                    <td><input type="text" name="compuct_album_load_more_image_count" id="compuct_album_load_more_image_count" value="<?php echo $option_row->images_per_page; ?>" class="bwge_spider_int_input" /></td>
                  </tr>

                  <!-- Masonry Album view -->									
									<tr id="tr_masonry_album_column_number">
                    <td class="bwge_spider_label"><label for="masonry_album_column_number"><?php echo __('Max. number of album columns:', 'bwge_back'); ?> </label></td>
                    <td><input type="text" name="masonry_album_column_number" id="masonry_album_column_number" value="<?php echo $option_row->album_column_number; ?>" class="bwge_spider_int_input" /></td>
                  </tr>
                  <tr id="tr_masonry_albums_per_page">
                    <td title="<?php echo __("If you want to display all albums you should leave it blank or insert 0.", 'bwge_back'); ?>" class="bwge_spider_label"><label for="masonry_albums_per_page"><?php echo __('Albums per page:', 'bwge_back'); ?> </label></td>
                    <td><input type="text" name="masonry_albums_per_page" id="masonry_albums_per_page" value="<?php echo $option_row->albums_per_page; ?>" class="bwge_spider_int_input" /></td>
                  </tr>							
                  <tr id="tr_masonry_album_thumb_width_height">
                    <td title="<?php echo __("Maximum value for album thumbnail width.", 'bwge_back'); ?>" class="bwge_spider_label"><label for="masonry_album_thumb_width"><?php echo __('Album Thumbnail width:', 'bwge_back'); ?> </label></td>
                    <td><input type="text" name="masonry_album_thumb_width" id="masonry_album_thumb_width" value="<?php echo $option_row->album_thumb_width; ?>" class="bwge_spider_int_input" /> px</td>                    
                  </tr>
                  <tr id="tr_masonry_album_image_column_number">
                    <td class="bwge_spider_label"><label for="masonry_album_image_column_number"><?php echo __('Max. number of image columns:', 'bwge_back'); ?> </label></td>
                    <td><input type="text" name="masonry_album_image_column_number" id="masonry_album_image_column_number" value="<?php echo $option_row->image_column_number; ?>" class="bwge_spider_int_input" /></td>
                  </tr>
                  <tr id="tr_masonry_album_images_per_page">
                    <td title="<?php echo __("If you want to display all images you should leave it blank or insert 0.", 'bwge_back'); ?>" class="bwge_spider_label"><label for="masonry_album_images_per_page"><?php echo __('Images per page:', 'bwge_back'); ?> </label></td>
                    <td><input type="text" name="masonry_album_images_per_page" id="masonry_album_images_per_page" value="<?php echo $option_row->images_per_page; ?>" class="bwge_spider_int_input" /></td>
                  </tr>
									
                  <tr id="tr_masonry_album_image_thumb_width_height">
										<td title="<?php echo __("Maximum value for thumbnail width.", 'bwge_back'); ?>" class="bwge_spider_label"><label for="masonry_album_image_thumb_width" id="masonry_album_image_thumb_dimensions"><?php echo __('Image thumbnail width:', 'bwge_back'); ?> </label></td>
										<td><input type="text" name="masonry_album_image_thumb_width" id="masonry_album_image_thumb_width" value="<?php echo $option_row->thumb_width; ?>" class="bwge_spider_int_input" /> px</td>										
                  </tr>
                  <tr id="tr_masonry_album_enable_page">
                    <td class="bwge_spider_label"><label><?php echo __('Enable pagination:', 'bwge_back'); ?> </label></td>
                    <td>
                      <input type="radio" name="masonry_album_enable_page" id="masonry_album_page_yes" value="1" <?php echo ($option_row->album_enable_page == '1') ? 'checked' : ''; ?> onchange="bwge_loadmore()" /><label for="masonry_album_page_yes"><?php echo __('Yes', 'bwge_back'); ?></label>
                      <input type="radio" name="masonry_album_enable_page" id="masonry_album_page_no" value="0" <?php echo ($option_row->album_enable_page == '0') ? 'checked' : ''; ?> onchange="bwge_loadmore()" /><label for="masonry_album_page_no"><?php echo __('No', 'bwge_back'); ?></label>
                      <input type="radio" name="masonry_album_enable_page" id="masonry_album_page_loadmore" value="2" <?php echo ($option_row->album_enable_page == '2') ? 'checked' : ''; ?> onchange="bwge_loadmore()" /><label for="masonry_album_page_loadmore"><?php echo __('Load More', 'bwge_back'); ?></label>
                      <input type="radio" name="masonry_album_enable_page" id="masonry_album_page_scrol_load" value="3" <?php echo ($option_row->album_enable_page == '3') ? 'checked' : ''; ?> onchange="bwge_loadmore()" /><label for="masonry_album_page_scrol_load"><?php echo __('Scroll Load', 'bwge_back'); ?></label>
                    </td>
                  </tr>
                  <tr id="tr_masonry_albums_per_page_load_more">
                    <td title="<?php echo __("If you want to display all albums you should leave it blank or insert 0.", 'bwge_back'); ?>" class="bwge_spider_label"><label for="masonry_albums_per_page_load_more"><?php echo __('Albums per load:', 'bwge_back'); ?> </label></td>
                    <td><input type="text" name="masonry_albums_per_page_load_more" id="masonry_albums_per_page_load_more" value="<?php echo $option_row->albums_per_page; ?>" class="bwge_spider_int_input" /></td>
                  </tr>
                   <tr id="tr_masonry_album_load_more_image_count">
                    <td class="bwge_spider_label"><label for="masonry_album_load_more_image_count"><?php echo __('Images per load:', 'bwge_back'); ?> </label></td>
                    <td><input type="text" name="masonry_album_load_more_image_count" id="masonry_album_load_more_image_count" value="<?php echo $option_row->images_per_page; ?>" class="bwge_spider_int_input" /></td>
                  </tr>

                  <!--Extended Album view-->
                  <tr id="tr_extended_albums_per_page">
                    <td title="<?php echo __("If you want to display all albums you should leave it blank or insert 0.", 'bwge_back'); ?>" class="bwge_spider_label"><label for="extended_albums_per_page"><?php echo __('Albums per page:', 'bwge_back'); ?> </label></td>
                    <td><input type="text" name="extended_albums_per_page" id="extended_albums_per_page" value="<?php echo $option_row->albums_per_page; ?>" class="bwge_spider_int_input" /></td>
                  </tr>
                  <tr id="tr_extended_album_height">
                    <td class="bwge_spider_label"><label for="extended_album_height"><?php echo __('Album row height:', 'bwge_back'); ?> </label></td>
                    <td><input type="text" name="extended_album_height" id="extended_album_height" value="<?php echo $option_row->extended_album_height; ?>" class="bwge_spider_int_input" /> px</td>
                  </tr>
                  <tr id="tr_extended_album_description_enable">
                    <td title="<?php echo __("If you disable description only the title of the album will be displayed.", 'bwge_back'); ?>" class="bwge_spider_label"><label><?php echo __('Enable album description:', 'bwge_back'); ?> </label></td>
                    <td>
                      <input type="radio" name="extended_album_description_enable" id="extended_album_description_yes" value="1" <?php echo ($option_row->extended_album_description_enable) ? 'checked' : ''; ?> /><label for="extended_album_description_yes"><?php echo __('Yes', 'bwge_back'); ?></label>
                      <input type="radio" name="extended_album_description_enable" id="extended_album_description_no" value="0" <?php echo ($option_row->extended_album_description_enable) ? '' : 'checked'; ?> /><label for="extended_album_description_no"><?php echo __('No', 'bwge_back'); ?></label>
                    </td>
                  </tr>
                  <tr id="tr_extended_album_thumb_width_height">
                    <td title="<?php echo __("Maximum values for album thumb width and height.", 'bwge_back'); ?>" class="bwge_spider_label"><label for="extended_album_thumb_width"><?php echo __('Album thumbnail dimensions:', 'bwge_back'); ?> </label></td>
                    <td>
                      <input type="text" name="extended_album_thumb_width" id="extended_album_thumb_width" value="<?php echo $option_row->album_thumb_width; ?>" class="bwge_spider_int_input" /> x 
                      <input type="text" name="extended_album_thumb_height" id="extended_album_thumb_height" value="<?php echo $option_row->album_thumb_height; ?>" class="bwge_spider_int_input" /> px
                    </td>
                  </tr>
                  <tr id="tr_extended_album_view_type">
                    <td title="<?php echo __('This option is disabled in free version.', 'bwge_back'); ?>" class="bwge_spider_label"><label class="bwge_disabled_label"><?php echo __('Album view type:', 'bwge_back'); ?> </label></td>
                    <td>
                      <input type="radio" name="extended_album_view_type" id="extended_album_view_bwge_type1" class="bwge_disabled_field" disabled readonly value="thumbnail" <?php if ($option_row->album_view_type == "thumbnail") echo 'checked="checked"'; ?> onchange="bwge_change_extended_album_view_type()" /><label for="extended_album_view_bwge_type1" class="bwge_disabled_label"><?php echo __('Thumbnail', 'bwge_back'); ?></label>
                      <input type="radio" name="extended_album_view_type" id="extended_album_view_bwge_type0" class="bwge_disabled_field" disabled readonly value="masonry" <?php if ($option_row->album_view_type == "masonry") echo 'checked="checked"'; ?> onchange="bwge_change_extended_album_view_type()" /><label for="extended_album_view_bwge_type0" class="bwge_disabled_label"><?php echo __('Masonry', 'bwge_back'); ?></label>
                      <input type="radio" name="extended_album_view_type" id="extended_album_view_bwge_type2" value="mosaic" <?php if ($option_row->album_view_type == "mosaic") echo 'checked="checked"'; ?> onchange="bwge_change_extended_album_view_type()" /><label for="extended_album_view_bwge_type2" ><?php echo __('Mosaic', 'bwge_back'); ?></label>
                    </td>
                  </tr>				  
                  <tr id="tr_extended_album_mosaic_hor_ver">
                    <td class="bwge_spider_label"><label><?php echo __('Mosaic:', 'bwge_back'); ?> </label></td>
                    <td>
                      <input type="radio" name="extended_album_mosaic_hor_ver" id="extended_album_mosaic_ver" value="vertical" onclick="bwge_change_label('extended_album_image_column_number', 'Number of image rows: ');
                                                                                                            bwge_change_label('extended_album_image_thumb_dimensions', '<?php echo __('Image thumbnail width: ', 'bwge_back'); ?>');
                                                                                                            jQuery('#extended_album_image_thumb_width').show();
                                                                                                            jQuery('#extended_album_image_thumb_height').hide();
                                                                                                            jQuery('#extended_album_image_thumb_dimensions_x').hide();" <?php echo ($option_row->mosaic == 'vertical') ? 'checked' : ''; ?> /><label for="extended_album_mosaic_ver"><?php echo __('Vertical', 'bwge_back'); ?></label>
                      <input type="radio" name="extended_album_mosaic_hor_ver" id="extended_album_mosaic_hor" value="horizontal" onclick="bwge_change_label('extended_album_image_column_number', '<?php echo __('Max. number of image columns:', 'bwge_back'); ?> ');
                                                                                                              bwge_change_label('extended_album_image_thumb_dimensions', 'Image thumbnail height: ');
                                                                                                              jQuery('#extended_album_image_thumb_width').hide();
                                                                                                              jQuery('#extended_album_image_thumb_height').show();
                                                                                                              jQuery('#extended_album_image_thumb_dimensions_x').hide();" <?php echo ($option_row->mosaic == 'horizontal') ? 'checked' : ''; ?> /><label for="extended_album_mosaic_hor"><?php echo __('Horizontal', 'bwge_back'); ?></label>
                    
                  
                    </td>
                  </tr>
                  <tr id="tr_extended_album_resizable_mosaic">
                    <td title="<?php echo __("Mosaic thumbnails do not have fixed size, but are proportional to the width of the parent container. This option keeps thumbs to look nice when viewed with very large or very small screen. Prevents zooming of thumbs.", 'bwge_back'); ?>" class="bwge_spider_label"><label for="extended_album_resizable_mosaic"><?php echo __('Resizable mosaic:', 'bwge_back'); ?> </label></td>
                    <td>
                      <input type="radio" name="extended_album_resizable_mosaic" id="extended_album_resizable_mosaic_1" value="1" <?php echo ($option_row->resizable_mosaic == 1) ? 'checked' : ''; ?> /><label for="extended_album_resizable_mosaic_1"><?php echo __('Yes', 'bwge_back'); ?></label>
                      <input type="radio" name="extended_album_resizable_mosaic" id="extended_album_resizable_mosaic_0" value="0" <?php echo ($option_row->resizable_mosaic == 0) ? 'checked' : ''; ?> /><label for="extended_album_resizable_mosaic_0"><?php echo __('No', 'bwge_back'); ?></label>
                      <br />
                    </td>
                  </tr>
                  
                  <tr id="tr_extended_album_mosaic_total_width">
                    <td title="<?php echo __("Percentage of container's width", 'bwge_back'); ?>" class="bwge_spider_label"><label for="extended_album_mosaic_total_width"><?php echo __('Total width of mosaic:', 'bwge_back'); ?> </label></td>
                    <td><input type="text" name="extended_album_mosaic_total_width" id="extended_album_mosaic_total_width" value="<?php echo $option_row->mosaic_total_width; ?>" class="bwge_spider_int_input" /> <?php echo __('percent', 'bwge_back'); ?></td>
                  </tr>
                  <tr id="tr_extended_album_image_column_number">
                    <td class="bwge_spider_label"><label for="extended_album_image_column_number"><?php echo __('Max. number of image columns:', 'bwge_back'); ?> </label></td>
                    <td><input type="text" name="extended_album_image_column_number" id="extended_album_image_column_number" value="<?php echo $option_row->image_column_number; ?>" class="bwge_spider_int_input" /></td>
                  </tr>
                  <tr id="tr_extended_album_images_per_page">
                    <td title="<?php echo __("If you want to display all images you should leave it blank or insert 0.", 'bwge_back'); ?>" class="bwge_spider_label"><label for="extended_album_images_per_page"><?php echo __('Images per page:', 'bwge_back'); ?> </label></td>
                    <td><input type="text" name="extended_album_images_per_page" id="extended_album_images_per_page" value="<?php echo $option_row->images_per_page; ?>" class="bwge_spider_int_input" /></td>
                  </tr>
                  <tr id="tr_extended_album_image_title">
                    <td class="bwge_spider_label"><label><?php echo __('Image title:', 'bwge_back'); ?> </label></td>
                    <td>
                      <input type="radio" name="extended_album_image_title" id="extended_album_image_title_hover" value="hover" <?php echo ($option_row->image_title_show_hover == 'hover') ? 'checked' : ''; ?> /><label for="extended_album_image_title_hover"><?php echo __('Show on hover', 'bwge_back'); ?></label><br />
                      <input type="radio" name="extended_album_image_title" id="extended_album_image_title_show" value="show" <?php echo ($option_row->image_title_show_hover == 'show') ? 'checked' : ''; ?> /><label for="extended_album_image_title_show"><?php echo __('Always show', 'bwge_back'); ?></label><br />
                      <input type="radio" name="extended_album_image_title" id="extended_album_image_title_none" value="none" <?php echo ($option_row->image_title_show_hover == 'none') ? 'checked' : ''; ?> /><label for="extended_album_image_title_none"><?php echo __("Don't show", 'bwge_back'); ?></label>
                    </td>
                  </tr>
                  <tr id="tr_extended_album_image_thumb_width_height">
                    <td title="<?php echo __("Maximum values for thumbnail width and height.", 'bwge_back'); ?>" class="bwge_spider_label"><label for="extended_album_image_thumb_width" id="extended_album_image_thumb_dimensions"><?php echo __('Image Thumbnail dimensions:', 'bwge_back'); ?> </label></td>
                    <td>
                      <input type="text" name="extended_album_image_thumb_width" id="extended_album_image_thumb_width" value="<?php echo $option_row->thumb_width; ?>" class="bwge_spider_int_input" /><span id="extended_album_image_thumb_dimensions_x" > x </span>
                      <input type="text" name="extended_album_image_thumb_height" id="extended_album_image_thumb_height" value="<?php echo $option_row->thumb_height; ?>" class="bwge_spider_int_input" /> px
                    </td>
                  </tr>
                  <tr id="tr_extended_album_enable_page">
                    <td class="bwge_spider_label"><label><?php echo __('Enable pagination:', 'bwge_back'); ?> </label></td>
                    <td>
                      <input type="radio" name="extended_album_enable_page" id="extended_album_page_yes" value="1" <?php echo ($option_row->album_enable_page == '1') ? 'checked' : ''; ?> onchange="bwge_loadmore()"/><label for="extended_album_page_yes"><?php echo __('Yes', 'bwge_back'); ?></label>
                      <input type="radio" name="extended_album_enable_page" id="extended_album_page_no" value="0" <?php echo ($option_row->album_enable_page == '0') ? 'checked' : ''; ?> onchange="bwge_loadmore()"/><label for="extended_album_page_no"><?php echo __('No', 'bwge_back'); ?></label>
                      <input type="radio" name="extended_album_enable_page" id="extended_album_page_loadmore" value="2" <?php echo ($option_row->album_enable_page == '2') ? 'checked' : ''; ?> onchange="bwge_loadmore()"/><label for="extended_album_page_loadmore"><?php echo __('Load More', 'bwge_back'); ?></label>
                      <input type="radio" name="extended_album_enable_page" id="extended_album_page_scrol_load" value="3" <?php  echo ($option_row->album_enable_page == '3') ? 'checked' : ''; ?> onchange="bwge_loadmore()"/><label for="extended_album_page_scrol_load"><?php echo __('Scroll Load', 'bwge_back'); ?> </label>
                    </td>
                  </tr>
                   <tr id="tr_extended_albums_per_page_load_more">
                    <td title="<?php echo __("If you want to display all albums you should leave it blank or insert 0.", 'bwge_back'); ?>" class="bwge_spider_label"><label for="extended_albums_per_page_load_more"><?php echo __('Albums per load:', 'bwge_back'); ?> </label></td>
                    <td><input type="text" name="extended_albums_per_page_load_more" id="extended_albums_per_page_load_more" value="<?php echo $option_row->albums_per_page; ?>" class="bwge_spider_int_input" /></td>
                  </tr>
                   <tr id="tr_extended_album_load_more_image_count">
                    <td class="bwge_spider_label"><label for="extended_album_load_more_image_count"><?php echo __('Images per load:', 'bwge_back'); ?> </label></td>
                    <td><input type="text" name="extended_album_load_more_image_count" id="extended_album_load_more_image_count" value="<?php echo $option_row->images_per_page; ?>" class="bwge_spider_int_input" /></td>
                  </tr>

                  <!--Image Browser view-->
                  <tr id="tr_image_browser_width_height">
                    <td title="<?php echo __("Maximum value for image width.", 'bwge_back'); ?>" class="bwge_spider_label"><label for="image_browser_width"><?php echo __('Image width:', 'bwge_back'); ?> </label></td>
                    <td>
                      <input type="text" name="image_browser_width" id="image_browser_width" value="<?php echo $option_row->image_browser_width; ?>" class="bwge_spider_int_input" /> px
                    </td>
                  </tr>
                  <tr id="tr_image_browser_title_enable">
                    <td class="bwge_spider_label"><label><?php echo __('Enable image title:', 'bwge_back'); ?> </label></td>
                    <td>
                      <input type="radio" name="image_browser_title_enable" id="image_browser_title_yes" value="1" <?php echo ($option_row->image_browser_title_enable) ? 'checked' : ''; ?> /><label for="image_browser_title_es"><?php echo __('Yes', 'bwge_back'); ?></label>
                      <input type="radio" name="image_browser_title_enable" id="image_browser_title_no" value="0" <?php echo ($option_row->image_browser_title_enable) ? '' : 'checked'; ?> /><label for="image_browser_title_no"><?php echo __('No', 'bwge_back'); ?></label>
                    </td>
                  </tr>
                  <tr id="tr_image_browser_description_enable">
                    <td class="bwge_spider_label"><label><?php echo __('Enable image description:', 'bwge_back'); ?> </label></td>
                    <td>
                      <input type="radio" name="image_browser_description_enable" id="image_browser_description_yes" value="1" <?php echo ($option_row->image_browser_description_enable) ? 'checked' : ''; ?> /><label for="image_browser_description_yes"><?php echo __('Yes', 'bwge_back'); ?></label>
                      <input type="radio" name="image_browser_description_enable" id="image_browser_description_no" value="0" <?php echo ($option_row->image_browser_description_enable) ? '' : 'checked'; ?> /><label for="image_browser_description_no"><?php echo __('No', 'bwge_back'); ?></label>
                    </td>
                  </tr>

                  <!--Blog Style view-->
                  <tr id="tr_blog_style_width_height">
                    <td title="<?php echo __("Maximum value for image width.", 'bwge_back'); ?>" class="bwge_spider_label"><label for="blog_style_width"><?php echo __('Image width:', 'bwge_back'); ?> </label></td>
                    <td>
                      <input type="text" name="blog_style_width" id="blog_style_width" value="<?php echo $option_row->blog_style_width; ?>" class="bwge_spider_int_input" /> px
                    </td>
                  </tr>
                  <tr id="tr_blog_style_title_enable">
                    <td class="bwge_spider_label"><label><?php echo __('Enable image title:', 'bwge_back'); ?> </label></td>
                    <td>
                      <input type="radio" name="blog_style_title_enable" id="blog_style_title_yes" value="1" <?php echo ($option_row->blog_style_title_enable) ? 'checked' : ''; ?> /><label for="blog_style_title_es"><?php echo __('Yes', 'bwge_back'); ?></label>
                      <input type="radio" name="blog_style_title_enable" id="blog_style_title_no" value="0" <?php echo ($option_row->blog_style_title_enable) ? '' : 'checked'; ?> /><label for="blog_style_title_no"><?php echo __('No', 'bwge_back'); ?></label>
                    </td>
                  </tr>
                  <tr id="tr_blog_style_images_per_page">
                    <td title="<?php echo __("If you want to display all images you should leave it blank or insert 0.", 'bwge_back'); ?>" class="bwge_spider_label"><label for="blog_style_images_per_page"><?php echo __('Images per page:', 'bwge_back'); ?> </label></td>
                    <td><input type="text" name="blog_style_images_per_page" id="blog_style_images_per_page" value="<?php echo $option_row->blog_style_images_per_page; ?>" class="bwge_spider_int_input" /></td>
                  </tr>
                  <tr id="tr_blog_style_enable_page">
                    <td class="bwge_spider_label"><label><?php echo __('Enable pagination:', 'bwge_back'); ?> </label></td>
                    <td>
                      <input type="radio" name="blog_style_enable_page" id="blog_style_page_yes" value="1" <?php echo ($option_row->blog_style_enable_page == '1') ? 'checked' : ''; ?> onchange="bwge_loadmore()" /><label for="blog_style_page_yes"><?php echo __('Yes', 'bwge_back'); ?></label>
                      <input type="radio" name="blog_style_enable_page" id="blog_style_page_no" value="0" <?php echo ($option_row->blog_style_enable_page == '0') ? 'checked' : ''; ?> onchange="bwge_loadmore()" /><label for="blog_style_page_no"><?php echo __('No', 'bwge_back'); ?></label>
                      <input type="radio" name="blog_style_enable_page" id="blog_style_page_loadmore" value="2" <?php echo ($option_row->blog_style_enable_page == '2') ? 'checked' : ''; ?> onchange="bwge_loadmore()" /><label for="blog_style_page_loadmore"><?php echo __('Load more', 'bwge_back'); ?></label>
                      <input type="radio" name="blog_style_enable_page" id="blog_style_page_scrol_load" value="3" <?php echo ($option_row->blog_style_enable_page == '3') ? 'checked' : ''; ?> onchange="bwge_loadmore()" /><label for="blog_style_page_scrol_load"><?php echo __('Scroll Load', 'bwge_back'); ?> </label>
                    </td>
                  </tr>
                  <tr id="tr_blog_style_load_more_image_count">
                    <td class="bwge_spider_label"><label for="blog_style_load_more_image_count"><?php echo __('Images per load:', 'bwge_back'); ?> </label></td>
                    <td><input type="text" name="blog_style_load_more_image_count" id="blog_style_load_more_image_count" value="<?php echo $option_row->blog_style_images_per_page; ?>" class="bwge_spider_int_input" /></td>
                  </tr>

                  <!--Slideshow view-->
                  <tr id="tr_slideshow_effect">
                    <td class="bwge_spider_label"><label for="slideshow_effect"><?php echo __('Slideshow Effect:', 'bwge_back'); ?> </label></td>
                    <td>
                      <select name="slideshow_effect" id="slideshow_effect" style="width:150px;">
                        <?php
                        foreach ($effects as $key => $effect) {
                          ?>
                          <option value="<?php echo $key; ?>" <?php echo ($option_row->slideshow_type == $key) ? 'selected' : ''; ?>><?php echo $effect; ?></option>
                          <?php
                        }
                        ?>
                      </select>
                    </td>
                  </tr>
                  <tr id="tr_slideshow_interval">
                    <td title="<?php echo __("Interval between two images.", 'bwge_back'); ?>" class="bwge_spider_label"><label for="slideshow_interval"><?php echo __('Time interval:', 'bwge_back'); ?> </label></td>
                    <td><input type="text" name="slideshow_interval" id="slideshow_interval" value="<?php echo $option_row->slideshow_interval; ?>" class="bwge_spider_int_input" /> sec.</td>
                  </tr>
                  <tr id="tr_slideshow_width_height">
                    <td title="<?php echo __("Maximum values for slideshow width and height.", 'bwge_back'); ?>" class="bwge_spider_label"><label for="slideshow_width"><?php echo __('Slideshow dimensions: ', 'bwge_back'); ?></label></td>
                    <td>
                      <input type="text" name="slideshow_width" id="slideshow_width" value="<?php echo $option_row->slideshow_width; ?>" class="bwge_spider_int_input" /> x 
                      <input type="text" name="slideshow_height" id="slideshow_height" value="<?php echo $option_row->slideshow_height; ?>" class="bwge_spider_int_input" /> px
                    </td>
                  </tr>
                  <tr id="tr_enable_slideshow_autoplay">
                    <td class="bwge_spider_label"><label><?php echo __('Enable Autoplay:', 'bwge_back'); ?> </label></td>
                    <td>
                      <input type="radio" name="enable_slideshow_autoplay" id="slideshow_autoplay_yes" value="1" <?php echo ($option_row->slideshow_enable_autoplay) ? 'checked' : ''; ?> /><label for="slideshow_autoplay_yes"><?php echo __('Yes', 'bwge_back'); ?></label>
                      <input type="radio" name="enable_slideshow_autoplay" id="slideshow_autoplay_no" value="0" <?php echo ($option_row->slideshow_enable_autoplay) ? '' : 'checked'; ?> /><label for="slideshow_autoplay_no"><?php echo __('No', 'bwge_back'); ?></label>
                    </td>
                  </tr>
                  <tr id="tr_enable_slideshow_shuffle">
                    <td class="bwge_spider_label"><label><?php echo __('Enable Shuffle:', 'bwge_back'); ?> </label></td>
                    <td>
                      <input type="radio" name="enable_slideshow_shuffle" id="slideshow_shuffle_yes" value="1" <?php echo ($option_row->slideshow_enable_shuffle) ? 'checked' : ''; ?> /><label for="slideshow_shuffle_yes"><?php echo __('Yes', 'bwge_back'); ?></label>
                      <input type="radio" name="enable_slideshow_shuffle" id="slideshow_shuffle_no" value="0" <?php echo ($option_row->slideshow_enable_shuffle) ? '' : 'checked'; ?> /><label for="slideshow_shuffle_no"><?php echo __('No', 'bwge_back'); ?></label>
                    </td>
                  </tr>
                  <tr id="tr_enable_slideshow_ctrl">
                    <td class="bwge_spider_label"><label><?php echo __('Enable control buttons:', 'bwge_back'); ?> </label></td>
                    <td>
                      <input type="radio" name="enable_slideshow_ctrl" id="slideshow_ctrl_yes" value="1" <?php echo ($option_row->slideshow_enable_ctrl) ? 'checked' : ''; ?> /><label for="slideshow_ctrl_yes"><?php echo __('Yes', 'bwge_back'); ?></label>
                      <input type="radio" name="enable_slideshow_ctrl" id="slideshow_ctrl_no" value="0" <?php echo ($option_row->slideshow_enable_ctrl) ? '' : 'checked'; ?> /><label for="slideshow_ctrl_no"><?php echo __('No', 'bwge_back'); ?></label>
                    </td>
                  </tr>
                  <tr id="tr_enable_slideshow_filmstrip">
                    <td title="<?php echo __('This option is disabled in free version.', 'bwge_back'); ?>" class="bwge_spider_label"><label class="bwge_disabled_label"><?php echo __('Enable slideshow filmstrip:', 'bwge_back'); ?> </label></td>
                    <td>
                      <input type="radio" name="enable_slideshow_filmstrip" id="slideshow_filmstrip_yes" value="1" onClick="bwge_enable_disable('', 'tr_slideshow_filmstrip_height', 'slideshow_filmstrip_yes')" <?php echo ($option_row->slideshow_enable_filmstrip) ? 'checked' : ''; ?> class="bwge_disabled_field" disabled readonly /><label for="slideshow_filmstrip_yes" class="bwge_disabled_label"><?php echo __('Yes', 'bwge_back'); ?></label>
                      <input type="radio" name="enable_slideshow_filmstrip" id="slideshow_filmstrip_no" value="0" onClick="bwge_enable_disable('none', 'tr_slideshow_filmstrip_height', 'slideshow_filmstrip_no')" <?php echo ($option_row->slideshow_enable_filmstrip) ? '' : 'checked'; ?> class="bwge_disabled_field" disabled readonly /><label for="slideshow_filmstrip_no" class="bwge_disabled_label"><?php echo __('No', 'bwge_back'); ?></label>
                  
                    </td>
                  </tr>
                  <tr id="tr_slideshow_filmstrip_height">
                    <td class="bwge_spider_label" title="<?php echo __('This option is disabled in free version.', 'bwge_back'); ?>"><label for="slideshow_filmstrip_height" class="bwge_disabled_label"><?php echo __('Slideshow Filmstrip size:', 'bwge_back'); ?> </label></td>
                    <td><input type="text" name="slideshow_filmstrip_height" id="slideshow_filmstrip_height" value="<?php echo $option_row->slideshow_filmstrip_height; ?>" class="bwge_spider_int_input" class="bwge_disabled_field" disabled readonly /> px
                   </td>
                  </tr>
				            <!-- Carousel-->
                  <tr id="tr_carousel_image_column_number">
                    <td class="bwge_spider_label"><label for="carousel_image_column_number"> <?php echo __('Max. number of carousel images:', 'bwge_back'); ?> </label></td>
                    <td><input type="text" name="carousel_image_column_number" id="carousel_image_column_number" value="<?php echo $option_row->carousel_image_column_number; ?>" class="bwge_spider_int_input" /></td>
                  </tr>
                </tbody>
              </table>
              </div>
              <div class="bwge_short_div" style="border-right: 1px solid #ccc;<?php if(!$from_menu) echo "height:630px;";?>" >
              <table>
                <!--Slideshow view-->
                <tbody id="tbody_slideshow">
                  <tr id="tr_slideshow_ecommerce_icon">
                    <td class="bwge_spider_label"><label><?php echo __('Show Ecommerce icon:', 'bwge_back'); ?> </label></td>
                    <td>
                      <input type="radio" name="slideshow_ecommerce_icon" id="slideshow_ecommerce_icon_yes" value="1" <?php echo ($option_row->slideshow_ecommerce_icon) ? 'checked' : ''; ?> /><label for="slideshow_ecommerce_icon_yes"><?php echo __('Yes', 'bwge_back'); ?></label>
                      <input type="radio" name="slideshow_ecommerce_icon" id="slideshow_ecommerce_icon_no" value="0" <?php echo ($option_row->slideshow_ecommerce_icon) ? '' : 'checked'; ?> /><label for="slideshow_ecommerce_icon_no"><?php echo __('No', 'bwge_back'); ?></label>
                    </td>
                  </tr>                
                  <tr id="tr_slideshow_enable_title">
                    <td class="bwge_spider_label"><label><?php echo __('Enable Image Title:', 'bwge_back'); ?> </label></td>
                    <td>
                      <input type="radio" name="slideshow_enable_title" id="slideshow_title_yes" value="1" <?php echo ($option_row->slideshow_enable_title) ? 'checked' : ''; ?> onClick="bwge_enable_disable('', 'tr_slideshow_title_position', 'slideshow_title_yes')" /><label for="slideshow_title_yes"><?php echo __('Yes', 'bwge_back'); ?></label>
                      <input type="radio" name="slideshow_enable_title" id="slideshow_title_no" value="0" <?php echo ($option_row->slideshow_enable_title) ? '' : 'checked'; ?> onClick="bwge_enable_disable('none', 'tr_slideshow_title_position', 'slideshow_title_no')" /><label for="slideshow_title_no"><?php echo __('No', 'bwge_back'); ?></label>
                    </td>
                  </tr>
                  <tr id="tr_slideshow_title_position">
                    <td title="<?php echo __("Image title position on slideshow", 'bwge_back'); ?>" class="bwge_spider_label"><label><?php echo __('Title Position:', 'bwge_back'); ?> </label></td>
                    <td>
                      <table class="bws_position_table">
                        <tbody>
                          <tr>
                            <td><input type="radio" value="top-left" id="slideshow_title_top-left" name="slideshow_title_position" <?php echo ($option_row->slideshow_title_position == 'top-left') ? 'checked' : ''; ?>></td>
                            <td><input type="radio" value="top-center" id="slideshow_title_top-center" name="slideshow_title_position" <?php echo ($option_row->slideshow_title_position == 'top-center') ? 'checked' : ''; ?>></td>
                            <td><input type="radio" value="top-right" id="slideshow_title_top-right" name="slideshow_title_position" <?php echo ($option_row->slideshow_title_position == 'top-right') ? 'checked' : ''; ?>></td>
                          </tr>
                          <tr>
                            <td><input type="radio" value="middle-left" id="slideshow_title_middle-left" name="slideshow_title_position" <?php echo ($option_row->slideshow_title_position == 'middle-left') ? 'checked' : ''; ?>></td>
                            <td><input type="radio" value="middle-center" id="slideshow_title_middle-center" name="slideshow_title_position" <?php echo ($option_row->slideshow_title_position == 'middle-center') ? 'checked' : ''; ?>></td>
                            <td><input type="radio" value="middle-right" id="slideshow_title_middle-right" name="slideshow_title_position" <?php echo ($option_row->slideshow_title_position == 'middle-right') ? 'checked' : ''; ?>></td>
                          </tr>
                          <tr>
                            <td><input type="radio" value="bottom-left" id="slideshow_title_bottom-left" name="slideshow_title_position" <?php echo ($option_row->slideshow_title_position == 'bottom-left') ? 'checked' : ''; ?>></td>
                            <td><input type="radio" value="bottom-center" id="slideshow_title_bottom-center" name="slideshow_title_position" <?php echo ($option_row->slideshow_title_position == 'bottom-center') ? 'checked' : ''; ?>></td>
                            <td><input type="radio" value="bottom-right" id="slideshow_title_bottom-right" name="slideshow_title_position" <?php echo ($option_row->slideshow_title_position == 'bottom-right') ? 'checked' : ''; ?>></td>
                          </tr>
                        </tbody>
                      </table>
                    </td>
                  </tr>
                  <tr id="tr_slideshow_full_width_title">
                    <td title="<?php echo __("Display image title based on the slideshow dimensions.", 'bwge_back'); ?>" class="bwge_spider_label">
                      <label><?php echo __('Full width title:', 'bwge_back'); ?></label>
                    </td>
                    <td>
                      <input type="radio" name="slideshow_title_full_width" id="slideshow_title_full_width_1" value="1" <?php if ($option_row->slideshow_title_full_width) echo 'checked="checked"'; ?>  /><label for="slideshow_title_full_width_1"><?php echo __('Yes', 'bwge_back'); ?></label>
                      <input type="radio" name="slideshow_title_full_width" id="slideshow_title_full_width_0" value="0" <?php if (!$option_row->slideshow_title_full_width) echo 'checked="checked"'; ?>  /><label for="slideshow_title_full_width_0"><?php echo __('No', 'bwge_back'); ?></label>
                    </td>
                  </tr>
                  <tr id="tr_slideshow_enable_description">
                    <td class="bwge_spider_label"><label><?php echo __('Enable Image Description:', 'bwge_back'); ?> </label></td>
                    <td>
                      <input type="radio" name="slideshow_enable_description" id="slideshow_description_yes" value="1" <?php echo ($option_row->slideshow_enable_description) ? 'checked' : ''; ?> onClick="bwge_enable_disable('', 'tr_slideshow_description_position', 'slideshow_description_yes')" /><label for="slideshow_description_yes"><?php echo __('Yes', 'bwge_back'); ?></label>
                      <input type="radio" name="slideshow_enable_description" id="slideshow_description_no" value="0" <?php echo ($option_row->slideshow_enable_description) ? '' : 'checked'; ?> onClick="bwge_enable_disable('none', 'tr_slideshow_description_position', 'slideshow_description_no')" /><label for="slideshow_description_no"><?php echo __('No', 'bwge_back'); ?></label>
                    </td>
                  </tr>
                  <tr id="tr_slideshow_description_position">
                    <td title="<?php echo __("Image description position on slideshow", 'bwge_back'); ?>" class="bwge_spider_label"><label><?php echo __('Description Position:', 'bwge_back'); ?> </label></td>
                    <td>
                      <table class="bws_position_table">
                        <tbody>
                          <tr>
                            <td><input type="radio" value="top-left" id="slideshow_description_top-left" name="slideshow_description_position" <?php echo ($option_row->slideshow_description_position == 'top-left') ? 'checked' : ''; ?>></td>
                            <td><input type="radio" value="top-center" id="slideshow_description_top-center" name="slideshow_description_position" <?php echo ($option_row->slideshow_description_position == 'top-center') ? 'checked' : ''; ?>></td>
                            <td><input type="radio" value="top-right" id="slideshow_description_top-right" name="slideshow_description_position" <?php echo ($option_row->slideshow_description_position == 'top-right') ? 'checked' : ''; ?>></td>
                          </tr>
                          <tr>
                            <td><input type="radio" value="middle-left" id="slideshow_description_middle-left" name="slideshow_description_position" <?php echo ($option_row->slideshow_description_position == 'middle-left') ? 'checked' : ''; ?>></td>
                            <td><input type="radio" value="middle-center" id="slideshow_description_middle-center" name="slideshow_description_position" <?php echo ($option_row->slideshow_description_position == 'middle-center') ? 'checked' : ''; ?>></td>
                            <td><input type="radio" value="middle-right" id="slideshow_description_middle-right" name="slideshow_description_position" <?php echo ($option_row->slideshow_description_position == 'middle-right') ? 'checked' : ''; ?>></td>
                          </tr>
                          <tr>
                            <td><input type="radio" value="bottom-left" id="slideshow_description_bottm-Left" name="slideshow_description_position" <?php echo ($option_row->slideshow_description_position == 'bottom-left') ? 'checked' : ''; ?>></td>
                            <td><input type="radio" value="bottom-center" id="slideshow_description_bottom-center" name="slideshow_description_position" <?php echo ($option_row->slideshow_description_position == 'bottom-center') ? 'checked' : ''; ?>></td>
                            <td><input type="radio" value="bottom-right" id="slideshow_description_bottm-right" name="slideshow_description_position" <?php echo ($option_row->slideshow_description_position == 'bottom-right') ? 'checked' : ''; ?>></td>
                          </tr>
                        </tbody>
                      </table>
                    </td>
                  </tr>
                  <tr id="tr_enable_slideshow_music">
                    <td class="bwge_spider_label"><label><?php echo __('Enable Slideshow Music:', 'bwge_back'); ?> </label></td>
                    <td>
                      <input type="radio" name="enable_slideshow_music" id="slideshow_music_yes" value="1" onClick="bwge_enable_disable('', 'tr_slideshow_music_url', 'slideshow_music_yes')" <?php echo ($option_row->slideshow_enable_music) ? 'checked' : ''; ?> /><label for="slideshow_music_yes"><?php echo __('Yes', 'bwge_back'); ?></label>
                      <input type="radio" name="enable_slideshow_music" id="slideshow_music_no" value="0" onClick="bwge_enable_disable('none', 'tr_slideshow_music_url', 'slideshow_music_no')" <?php echo ($option_row->slideshow_enable_music) ? '' : 'checked'; ?> /><label for="slideshow_music_no"><?php echo __('No', 'bwge_back'); ?></label>
                    </td>
                  </tr>
                  <tr id="tr_slideshow_music_url">
                    <td title="<?php echo __("Enter absolute audio file url or add file from Options page.", 'bwge_back'); ?>" class="bwge_spider_label">
                      <label for="slideshow_music_url"><?php echo __('Music url:', 'bwge_back'); ?> </label>
                    </td>
                    <td>
                      <input type="text" id="slideshow_music_url" name="slideshow_music_url" value="<?php echo $option_row->slideshow_audio_url; ?>" style="display:inline-block;" />
                    </td>
                  </tr>
                </tbody>
                  <tr id="tr_carousel_ecommerce_icon">
                    <td class="bwge_spider_label"><label><?php echo __('Show Ecommerce icon:', 'bwge_back'); ?> </label></td>
                    <td>
                      <input type="radio" name="carousel_ecommerce_icon" id="carousel_ecommerce_icon_yes" value="1" <?php echo ($option_row->carousel_ecommerce_icon) ? 'checked' : ''; ?> /><label for="carousel_ecommerce_icon_yes"><?php echo __('Yes', 'bwge_back'); ?></label>
                      <input type="radio" name="carousel_ecommerce_icon" id="carousel_ecommerce_icon_no" value="0" <?php echo ($option_row->carousel_ecommerce_icon) ? '' : 'checked'; ?> /><label for="carousel_ecommerce_icon_no"><?php echo __('No', 'bwge_back'); ?></label>
                    </td>
                  </tr>                   
                <tr id="tr_carousel_width_height">
                    <td title="<?php echo __("Maximum values for carousel width and height.", 'bwge_back'); ?>" class="bwge_spider_label"><label for="carousel_width"><?php echo __('Carousel dimensions:', 'bwge_back'); ?> </label></td>
                    <td>
                      <input type="text" name="carousel_width" id="carousel_width" value="<?php echo $option_row->carousel_width; ?>" class="bwge_spider_int_input" /> x 
                      <input type="text" name="carousel_height" id="carousel_height" value="<?php echo $option_row->carousel_height; ?>" class="bwge_spider_int_input" /> px
                    </td>
                  </tr>
                  <tr id="tr_carousel_image_par">
                    <td class="bwge_spider_label"><label for="carousel_image_par"><?php echo __('Carousel image ratio:', 'bwge_back'); ?></label></td>
                    <td><input type="text" name="carousel_image_par" id="carousel_image_par" value="<?php echo $option_row->carousel_image_par; ?>"  /></td>
                  </tr> 
				          <tr id="tr_enable_carousel_autoplay">
                    <td class="bwge_spider_label"><label><?php echo __('Enable Autoplay:', 'bwge_back'); ?> </label></td>
                    <td>
                      <input type="radio" name="enable_carousel_autoplay" id="carousel_autoplay_yes" value="1" <?php echo ($option_row->carousel_enable_autoplay) ? 'checked' : ''; ?> /><label for="carousel_autoplay_yes"><?php echo __('Yes', 'bwge_back'); ?></label>
                      <input type="radio" name="enable_carousel_autoplay" id="carousel_autoplay_no" value="0" <?php echo ($option_row->carousel_enable_autoplay) ? '' : 'checked'; ?> /><label for="carousel_autoplay_no"><?php echo __('No', 'bwge_back'); ?></label>
                    </td>
                  </tr>
                  <tr id="tr_carousel_interval">
                    <td title="<?php echo __("Interval between two images.", 'bwge_back'); ?>" class="bwge_spider_label"><label for="carousel_interval"><?php echo __('Time interval:', 'bwge_back'); ?> </label></td>
                    <td><input type="text" name="carousel_interval" id="carousel_interval" value="<?php echo $option_row->carousel_interval; ?>" class="bwge_spider_int_input" /> sec.</td>
                  </tr>
               
                  <tr id="tr_enable_carousel_title">
                    <td class="bwge_spider_label"><label><?php echo __('Enable Image Title:', 'bwge_back'); ?> </label></td>
                    <td>
                      <input type="radio" name="enable_carousel_title" id="carousel_title_yes" value="1" <?php echo ($option_row->carousel_enable_title) ? 'checked' : ''; ?> onClick="bwge_enable_disable('', 'tr_carousel_title_position', 'carousel_title_yes')" /><label for="carousel_title_yes"><?php echo __('Yes', 'bwge_back'); ?></label>
                      <input type="radio" name="enable_carousel_title" id="carousel_title_no" value="0" <?php echo ($option_row->carousel_enable_title) ? '' : 'checked'; ?> onClick="bwge_enable_disable('none', 'tr_carousel_title_position', 'carousel_title_no')" /><label for="carousel_title_no"><?php echo __('No', 'bwge_back'); ?></label>
                    </td>
                  </tr>
                  <tr id="tr_carousel_r_width">
                    <td title="<?php echo __("Maximum values for carousel width and height.", 'bwge_back'); ?>" class="bwge_spider_label"><label for="carousel_r_width"><?php echo __('Fixed width:', 'bwge_back'); ?></label></td>
                    <td>
                      <input type="text" name="carousel_r_width" id="carousel_r_width" value="<?php echo $option_row->carousel_r_width; ?>" class="bwge_spider_int_input" /> px 
                     
                    </td>
                  </tr>
				          <tr id="tr_carousel_fit_containerWidth">
                    <td class="bwge_spider_label"><label><?php echo __('Container fit:', 'bwge_back'); ?> </label></td>
                    <td>
                      <input type="radio" name="carousel_fit_containerWidth" id="carousel_fit_containerWidth_yes" value="1" <?php echo ($option_row->carousel_fit_containerWidth) ? 'checked' : ''; ?> /><label for="carousel_fit_containerWidth_yes"><?php echo __('Yes', 'bwge_back'); ?></label>
                      <input type="radio" name="carousel_fit_containerWidth" id="carousel_fit_containerWidth_no" value="0" <?php echo ($option_row->carousel_fit_containerWidth) ? '' : 'checked'; ?> /><label for="carousel_fit_containerWidth_no"><?php echo __('No', 'bwge_back'); ?></label>
                    </td>
                  </tr> 
                  <tr id="tr_carousel_prev_next_butt">
                    <td class="bwge_spider_label"><label><?php echo __('Next/Previous buttons:', 'bwge_back'); ?> </label></td>
                    <td>
                      <input type="radio" name="carousel_prev_next_butt" id="carousel_prev_next_butt_yes" value="1" <?php echo ($option_row->carousel_prev_next_butt) ? 'checked' : ''; ?> /><label for="carousel_prev_next_butt_yes"><?php echo __('Yes', 'bwge_back'); ?></label>
                      <input type="radio" name="carousel_prev_next_butt" id="carousel_prev_next_butt_no" value="0" <?php echo ($option_row->carousel_prev_next_butt) ? '' : 'checked'; ?> /><label for="carousel_prev_next_butt_no"><?php echo __('No', 'bwge_back'); ?></label>
                    </td>
                  </tr>
                  <tr id="tr_carousel_play_pause_butt">
                    <td class="bwge_spider_label"><label><?php echo __('Play/Pause button:', 'bwge_back'); ?> </label></td>
                    <td>
                      <input type="radio" name="carousel_play_pause_butt" id="carousel_play_pause_butt_yes" value="1" <?php echo ($option_row->carousel_play_pause_butt) ? 'checked' : ''; ?> /><label for="carousel_play_pause_butt_yes"><?php echo __('Yes', 'bwge_back'); ?></label>
                      <input type="radio" name="carousel_play_pause_butt" id="carousel_play_pause_butt_no" value="0" <?php echo ($option_row->carousel_play_pause_butt) ? '' : 'checked'; ?> /><label for="carousel_play_pause_butt_no"><?php echo __('No', 'bwge_back'); ?></label>
                    </td>
                  </tr>
                <!--Lightbox view-->
                <tbody id="tbody_popup_other">
                  <tr id="tr_thumb_click_action">
                    <td class="bwge_spider_label"><label><?php echo __('Thumb click action:', 'bwge_back'); ?> </label></td>
                    <td>
                      <input type="radio" name="thumb_click_action" id="thumb_click_action_1" value="open_lightbox" <?php if ($option_row->thumb_click_action == 'open_lightbox') echo 'checked="checked"'; ?> onchange="bwge_thumb_click_action()" /><label for="thumb_click_action_1"><?php echo __('Open lightbox', 'bwge_back'); ?></label><br />
                      <input type="radio" name="thumb_click_action" id="thumb_click_action_2" value="redirect_to_url" <?php if ($option_row->thumb_click_action == 'redirect_to_url') echo 'checked="checked"'; ?> onchange="bwge_thumb_click_action()" /><label for="thumb_click_action_2"><?php echo __('Redirect to url', 'bwge_back'); ?></label><br />
                      <input type="radio" name="thumb_click_action" id="thumb_click_action_3" value="do_nothing" <?php if ($option_row->thumb_click_action == 'do_nothing') echo 'checked="checked"'; ?> onchange="bwge_thumb_click_action()" /><label for="thumb_click_action_3"><?php echo __('Do Nothing', 'bwge_back'); ?></label>
                    </td>
                  </tr>
                  <tr id="tr_thumb_link_target">
                    <td title="<?php echo __("Open new window when redirecting.", 'bwge_back'); ?>" class="bwge_spider_label"><label><?php echo __('Open in new window:', 'bwge_back'); ?> </label></td>
                    <td>
                      <input type="radio" name="thumb_link_target" id="thumb_link_target_yes" value="1" <?php if ($option_row->thumb_link_target) echo 'checked="checked"'; ?> /><label for="thumb_link_target_yes"><?php echo __('Yes', 'bwge_back'); ?></label>
                      <input type="radio" name="thumb_link_target" id="thumb_link_target_no" value="0" <?php if (!$option_row->thumb_link_target) echo 'checked="checked"'; ?> /><label for="thumb_link_target_no"><?php echo __('No', 'bwge_back'); ?></label>
                    </td>
                  </tr>
                </tbody>
                <tbody id="tbody_popup">
                  <tr id="tr_popup_fullscreen">
                    <td title="<?php echo __("Enable full width feature for the lightbox.", 'bwge_back'); ?>" class="bwge_spider_label">
                      <label><?php echo __('Full width lightbox:', 'bwge_back'); ?></label>
                    </td>
                    <td>
                      <input type="radio" name="popup_fullscreen" id="popup_fullscreen_1" value="1" <?php if ($option_row->popup_fullscreen) echo 'checked="checked"'; ?> onchange="bwge_popup_fullscreen()" /><label for="popup_fullscreen_1"><?php echo __('Yes', 'bwge_back'); ?></label>
                      <input type="radio" name="popup_fullscreen" id="popup_fullscreen_0" value="0" <?php if (!$option_row->popup_fullscreen) echo 'checked="checked"'; ?> onchange="bwge_popup_fullscreen()" /><label for="popup_fullscreen_0"><?php echo __('No', 'bwge_back'); ?></label>
                    </td>
                  </tr>	
                  <tr id="tr_popup_width_height">
                    <td title="<?php echo __("Maximum values for lightbox width and height.", 'bwge_back'); ?>" class="bwge_spider_label"><label for="popup_width"><?php echo __('Lightbox dimensions:', 'bwge_back'); ?> </label></td>
                    <td>
                      <input type="text" name="popup_width" id="popup_width" value="<?php echo $option_row->popup_width; ?>" class="bwge_spider_int_input" /> x 
                      <input type="text" name="popup_height" id="popup_height" value="<?php echo $option_row->popup_height; ?>" class="bwge_spider_int_input" /> px
                    </td>
                  </tr>
                  <tr id="tr_popup_effect">
                    <td title="<?php echo __("Lightbox slideshow effect.", 'bwge_back'); ?>" class="bwge_spider_label"><label for="popup_effect"><?php echo __('Lightbox effect:', 'bwge_back'); ?> </label></td>
                    <td>
                      <select name="popup_effect" id="popup_effect" style="width:150px;">
                        <?php
                        foreach ($effects as $key => $effect) {
                          ?>
                          <option value="<?php echo $key; ?>" <?php echo ($option_row->popup_type == $key) ? 'selected' : ''; ?>><?php echo $effect; ?></option>
                          <?php
                        }
                        ?>
                      </select>
                    </td>
                  </tr>
                  <tr id="tr_popup_autoplay">
                    <td class="bwge_spider_label">
                      <label><?php echo __('Lightbox autoplay:', 'bwge_back'); ?> </label>
                    </td>
                    <td>
                      <input type="radio" name="popup_autoplay" id="popup_autoplay_1" value="1" <?php if ($option_row->popup_autoplay) echo 'checked="checked"'; ?>  /><label for="popup_autoplay_1"><?php echo __('Yes', 'bwge_back'); ?></label>
                      <input type="radio" name="popup_autoplay" id="popup_autoplay_0" value="0" <?php if (!$option_row->popup_autoplay) echo 'checked="checked"'; ?>  /><label for="popup_autoplay_0"><?php echo __('No', 'bwge_back'); ?></label>
                    </td>
                  </tr>
                  <tr id="tr_popup_interval">
                    <td title="<?php echo __("Interval between two images.", 'bwge_back'); ?>" class="bwge_spider_label"><label for="popup_interval"><?php echo __('Time interval:', 'bwge_back'); ?> </label></td>
                    <td><input type="text" name="popup_interval" id="popup_interval" value="<?php echo $option_row->popup_interval; ?>" class="bwge_spider_int_input" /> sec.</td>
                  </tr>
                  <tr id="tr_popup_enable_filmstrip">
                    <td title="<?php echo __('This option is disabled in free version.', 'bwge_back'); ?>" class="bwge_spider_label"><label class="bwge_disabled_label"><?php echo __('Enable filmstrip in lightbox:', 'bwge_back'); ?> </label></td>
                    <td>
                      <input type="radio" name="popup_enable_filmstrip" id="popup_filmstrip_yes" value="1" o <?php echo ($option_row->popup_enable_filmstrip) ? 'checked' : ''; ?> class="bwge_disabled_field" disabled readonly /><label for="popup_filmstrip_yes" class="bwge_disabled_label"><?php echo __('Yes', 'bwge_back'); ?></label>
                      <input type="radio" name="popup_enable_filmstrip" id="popup_filmstrip_no" value="0" class="bwge_disabled_field" disabled readonly <?php echo ($option_row->popup_enable_filmstrip) ? '' : 'checked'; ?> /><label for="popup_filmstrip_no" class="bwge_disabled_label"><?php echo __('No', 'bwge_back'); ?></label>
                    
                    </td>
                  </tr>
                  <tr id="tr_popup_filmstrip_height">
                    <td class="bwge_spider_label" title="<?php echo __('This option is disabled in free version.', 'bwge_back'); ?>"><label for="popup_filmstrip_height" class="bwge_disabled_label"><?php echo __('Filmstrip size:', 'bwge_back'); ?> </label></td>
                    <td>
                        <input type="text" name="popup_filmstrip_height" id="popup_filmstrip_height" value="<?php echo $option_row->popup_filmstrip_height; ?>" class="bwge_spider_int_input bwge_disabled_field" disabled readonly /> px
                        
                    </td>
                  </tr>
                  <tr id="tr_popup_hit_counter">
                    <td class="bwge_spider_label" title="<?php echo __('This option is disabled in free version.', 'bwge_back'); ?>"><label class="bwge_disabled_label"><?php echo __('Display hit counter:', 'bwge_back'); ?> </label></td>
                    <td>
                      <input type="radio" name="popup_hit_counter" id="popup_hit_counter_yes" value="1" <?php echo ($option_row->popup_hit_counter) ? 'checked' : ''; ?>  class="bwge_disabled_field" disabled readonly /><label for="popup_hit_counter_yes" class="bwge_disabled_label"><?php echo __('Yes', 'bwge_back'); ?></label>
                      <input type="radio" name="popup_hit_counter" id="popup_hit_counter_no" value="0" <?php echo ($option_row->popup_hit_counter) ? '' : 'checked'; ?> class="bwge_disabled_field" disabled readonly /><label for="popup_hit_counter_no" class="bwge_disabled_label"><?php echo __('No', 'bwge_back'); ?></label>
                      
                    </td>
                  </tr>
                  <tr id="tr_popup_enable_ctrl_btn">
                    <td title="<?php echo __("Enable control buttons in lightbox", 'bwge_back'); ?>" class="bwge_spider_label"><label><?php echo __('Enable control buttons:', 'bwge_back'); ?> </label></td>
                    <td>
                      <input type="radio" name="popup_enable_ctrl_btn" id="popup_ctrl_btn_yes" value="1" onClick="bwge_enable_disable('', 'tbody_popup_ctrl_btn', 'popup_ctrl_btn_yes');" <?php echo ($option_row->popup_enable_ctrl_btn) ? 'checked' : ''; ?> /><label for="popup_ctrl_btn_yes"><?php echo __('Yes', 'bwge_back'); ?></label>
                      <input type="radio" name="popup_enable_ctrl_btn" id="popup_ctrl_btn_no" value="0" onClick="bwge_enable_disable('none', 'tbody_popup_ctrl_btn', 'popup_ctrl_btn_no');" <?php echo ($option_row->popup_enable_ctrl_btn) ? '' : 'checked'; ?> /><label for="popup_ctrl_btn_no"><?php echo __('No', 'bwge_back'); ?></label>
                    </td>
                  </tr>
                </tbody>
                <tbody id="tbody_popup_ctrl_btn">
                  <tr id="tr_popup_enable_fullscreen">
                    <td title="<?php echo __("Enable fullscreen view for images", 'bwge_back'); ?>" class="bwge_spider_label"><label><?php echo __('Enable fullscreen:', 'bwge_back'); ?> </label></td>
                    <td>
                      <input type="radio" name="popup_enable_fullscreen" id="popup_fullscreen_yes" value="1" <?php echo ($option_row->popup_enable_fullscreen) ? 'checked' : ''; ?> /><label for="popup_fullscreen_yes"><?php echo __('Yes', 'bwge_back'); ?></label>
                      <input type="radio" name="popup_enable_fullscreen" id="popup_fullscreen_no" value="0" <?php echo ($option_row->popup_enable_fullscreen) ? '' : 'checked'; ?> /><label for="popup_fullscreen_no"><?php echo __('No', 'bwge_back'); ?></label>
                    </td>
                  </tr>
                  <tr id="tr_popup_enable_info">
                    <td title="<?php echo __("Enable title, description for images", 'bwge_back'); ?>" class="bwge_spider_label"><label><?php echo __('Enable info:', 'bwge_back'); ?> </label></td>
                    <td>
                      <input type="radio" name="popup_enable_info" id="popup_info_yes" value="1" <?php echo ($option_row->popup_enable_info) ? 'checked="checked"' : ''; ?> /><label for="popup_info_yes"><?php echo __('Yes', 'bwge_back'); ?></label>
                      <input type="radio" name="popup_enable_info" id="popup_info_no" value="0" <?php echo ($option_row->popup_enable_info) ? '' : 'checked="checked"'; ?> /><label for="popup_info_no"><?php echo __('No', 'bwge_back'); ?></label>
                    </td>
                  </tr>
                  <tr id="tr_popup_info_always_show">
                    <td class="bwge_spider_label"><label><?php echo __('Display info by default:', 'bwge_back'); ?> </label></td>
                    <td>
                      <input type="radio" name="popup_info_always_show" id="popup_info_always_show_yes" value="1" <?php echo ($option_row->popup_info_always_show) ? 'checked="checked"' : ''; ?> /><label for="popup_info_always_show_yes"><?php echo __('Yes', 'bwge_back'); ?></label>
                      <input type="radio" name="popup_info_always_show" id="popup_info_always_show_no" value="0" <?php echo ($option_row->popup_info_always_show) ? '' : 'checked="checked"'; ?> /><label for="popup_info_always_show_no"><?php echo __('No', 'bwge_back'); ?></label>
                    </td>
                  </tr>
                  <tr id="tr_popup_info_full_width">
                    <td title="<?php echo __("Display image information based on the lightbox dimensions.", 'bwge_back'); ?>" class="bwge_spider_label"><label><?php echo __('Full width info:', 'bwge_back'); ?></label></td>
                    <td>
                      <input type="radio" name="popup_info_full_width" id="popup_info_full_width_1" value="1" <?php if ($option_row->popup_info_full_width) echo 'checked="checked"'; ?>  /><label for="popup_info_full_width_1"><?php echo __('Yes', 'bwge_back'); ?></label>
                      <input type="radio" name="popup_info_full_width" id="popup_info_full_width_0" value="0" <?php if (!$option_row->popup_info_full_width) echo 'checked="checked"'; ?>  /><label for="popup_info_full_width_0"><?php echo __('No', 'bwge_back'); ?></label>
                    </td>
                  </tr>
                  <tr id="tr_popup_enable_ecommerce">
                    <td title="Enable Ecommerce for images" class="bwge_spider_label"><label>Enable Ecommerce button: </label></td>
                    <td>
                      <input type="radio" name="popup_enable_ecommerce" id="popup_ecommerce_yes" value="1" <?php echo ($option_row->popup_enable_ecommerce) ? 'checked' : ''; ?> /><label for="popup_ecommerce_yes">Yes</label>
                      <input type="radio" name="popup_enable_ecommerce" id="popup_ecommerce_no" value="0" <?php echo ($option_row->popup_enable_ecommerce) ? '' : 'checked'; ?> /><label for="popup_ecommerce_no">No</label>
                    </td>
                  </tr>	                  
                  <tr id="tr_popup_enable_rate">
                    <td title="<?php echo __('This option is disabled in free version.', 'bwge_back'); ?>" class="bwge_spider_label"><label class="bwge_disabled_label"><?php echo __('Enable rating:', 'bwge_back'); ?> </label></td>
                    <td>
                      <input type="radio" name="popup_enable_rate" id="popup_rate_yes" value="1" <?php echo ($option_row->popup_enable_rate) ? 'checked="checked"' : ''; ?> class="bwge_disabled_field" disabled readonly /><label for="popup_rate_yes" class="bwge_disabled_label"><?php echo __('Yes', 'bwge_back'); ?></label>
                      <input type="radio" name="popup_enable_rate" id="popup_rate_no" value="0" <?php echo ($option_row->popup_enable_rate) ? '' : 'checked="checked"'; ?> class="bwge_disabled_field" disabled readonly /><label for="popup_rate_no" class="bwge_disabled_label"><?php echo __('No', 'bwge_back'); ?></label>
                      
                    </td>
                  </tr>
                  <tr id="tr_popup_enable_comment">
                    <td title="<?php echo __('This option is disabled in free version.', 'bwge_back'); ?>" class="bwge_spider_label"><label class="bwge_disabled_label"><?php echo __('Enable comments:', 'bwge_back'); ?> </label></td>
                    <td>
                      <input type="radio" name="popup_enable_comment" id="popup_comment_yes" value="1" <?php echo ($option_row->popup_enable_comment) ? 'checked' : ''; ?> class="bwge_disabled_field" disabled readonly /><label for="popup_comment_yes" class="bwge_disabled_label"><?php echo __('Yes', 'bwge_back'); ?></label>
                      <input type="radio" name="popup_enable_comment" id="popup_comment_no" value="0" <?php echo ($option_row->popup_enable_comment) ? '' : 'checked'; ?> class="bwge_disabled_field" disabled readonly /><label for="popup_comment_no" class="bwge_disabled_label"><?php echo __('No', 'bwge_back'); ?></label>
                      
                    </td>
                  </tr>
                  <tr id="tr_popup_enable_facebook">
                    <td title="<?php echo __('This option is disabled in free version.', 'bwge_back'); ?>" class="bwge_spider_label"><label class="bwge_disabled_label"><?php echo __('Enable Facebook button:', 'bwge_back'); ?> </label></td>
                    <td>
                      <input type="radio" name="popup_enable_facebook" id="popup_facebook_yes" value="1" <?php echo ($option_row->popup_enable_facebook) ? 'checked' : ''; ?> class="bwge_disabled_field" disabled readonly /><label for="popup_facebook_yes" class="bwge_disabled_label"><?php echo __('Yes', 'bwge_back'); ?></label>
                      <input type="radio" name="popup_enable_facebook" id="popup_facebook_no" value="0" <?php echo ($option_row->popup_enable_facebook) ? '' : 'checked'; ?> class="bwge_disabled_field" disabled readonly /><label for="popup_facebook_no" class="bwge_disabled_label"><?php echo __('No', 'bwge_back'); ?></label>
                                           
                    </td>
                  </tr>
                  <tr id="tr_popup_enable_twitter">
                    <td title="<?php echo __('This option is disabled in free version.', 'bwge_back'); ?>" class="bwge_spider_label"><label class="bwge_disabled_label"><?php echo __('Enable Twitter button:', 'bwge_back'); ?> </label></td>
                    <td>
                      <input type="radio" name="popup_enable_twitter" id="popup_twitter_yes" value="1" <?php echo ($option_row->popup_enable_twitter) ? 'checked' : ''; ?> class="bwge_disabled_field" disabled readonly /><label for="popup_twitter_yes" class="bwge_disabled_label"><?php echo __('Yes', 'bwge_back'); ?></label>
                      <input type="radio" name="popup_enable_twitter" id="popup_twitter_no" value="0" <?php echo ($option_row->popup_enable_twitter) ? '' : 'checked'; ?> class="bwge_disabled_field" disabled readonly /><label for="popup_twitter_no" class="bwge_disabled_label"><?php echo __('No', 'bwge_back'); ?></label>
                                              
                    </td>
                  </tr>
                  <tr id="tr_popup_enable_google">
                    <td title="<?php echo __('This option is disabled in free version.', 'bwge_back'); ?>" class="bwge_spider_label"><label class="bwge_disabled_label"><?php echo __('Enable Google+ button:', 'bwge_back'); ?> </label></td>
                    <td>
                      <input type="radio" name="popup_enable_google" id="popup_google_yes" value="1" <?php echo ($option_row->popup_enable_google) ? 'checked' : ''; ?> class="bwge_disabled_field" disabled readonly /><label for="popup_google_yes" class="bwge_disabled_label"><?php echo __('Yes', 'bwge_back'); ?></label>
                      <input type="radio" name="popup_enable_google" id="popup_google_no" value="0" <?php echo ($option_row->popup_enable_google) ? '' : 'checked'; ?> class="bwge_disabled_field" disabled readonly /><label for="popup_google_no" class="bwge_disabled_label"><?php echo __('No', 'bwge_back'); ?></label>
                                              
                    </td>
                  </tr>
                  <tr id="tr_popup_enable_pinterest">
                    <td title="<?php echo __('This option is disabled in free version.', 'bwge_back'); ?>" class="bwge_spider_label"><label class="bwge_disabled_label"><?php echo __('Enable Pinterest button:', 'bwge_back'); ?> </label></td>
                    <td>
                      <input type="radio" name="popup_enable_pinterest" id="popup_pinterest_yes" value="1" <?php echo ($option_row->popup_enable_pinterest) ? 'checked' : ''; ?> class="bwge_disabled_field" disabled readonly /><label for="popup_pinterest_yes" class="bwge_disabled_label"><?php echo __('Yes', 'bwge_back'); ?></label>
                      <input type="radio" name="popup_enable_pinterest" id="popup_pinterest_no" value="0" <?php echo ($option_row->popup_enable_pinterest) ? '' : 'checked'; ?> class="bwge_disabled_field" disabled readonly  /><label for="popup_pinterest_no" class="bwge_disabled_label"><?php echo __('No', 'bwge_back'); ?></label>
                                             
                    </td>
                  </tr>
                  <tr id="tr_popup_enable_tumblr">
                    <td title="<?php echo __('This option is disabled in free version.', 'bwge_back'); ?>" class="bwge_spider_label"><label class="bwge_disabled_label"><?php echo __('Enable Tumblr button:', 'bwge_back'); ?> </label></td>
                    <td>
                      <input type="radio" name="popup_enable_tumblr" id="popup_tumblr_yes" value="1" <?php echo ($option_row->popup_enable_tumblr) ? 'checked' : ''; ?> class="bwge_disabled_field" disabled readonly  /><label for="popup_tumblr_yes" class="bwge_disabled_label"><?php echo __('Yes', 'bwge_back'); ?></label>
                      <input type="radio" name="popup_enable_tumblr" id="popup_tumblr_no" value="0" <?php echo ($option_row->popup_enable_tumblr) ? '' : 'checked'; ?> class="bwge_disabled_field" disabled readonly  /><label for="popup_tumblr_no" class="bwge_disabled_label"><?php echo __('No', 'bwge_back'); ?></label>
                                             
                    </td>
                  </tr>
	

				
                </tbody>
              </table>
              </div>
              <div class="bwge_short_div"  style="<?php if(!$from_menu) echo "height:630px;";?>">
              <table>
                <tbody>
                  <tr id="tr_watermark_type">
                    <td class="bwge_spider_label"><label><?php echo __('Advertisement Type:', 'bwge_back'); ?> </label></td>
                    <td>
                      <input type="radio" name="watermark_type" id="watermark_bwge_typenone" value="none" onClick="bwge_watermark('watermark_bwge_typenone')" <?php echo ($option_row->watermark_type == 'none') ? 'checked' : ''; ?> /><label for="watermark_bwge_typenone"><?php echo __('None', 'bwge_back'); ?></label>
                      <input type="radio" name="watermark_type" id="watermark_bwge_typetext" value="text" onClick="bwge_watermark('watermark_bwge_typetext')" <?php echo ($option_row->watermark_type == 'text') ? 'checked' : ''; ?> /><label for="watermark_bwge_typetext"><?php echo __('Text', 'bwge_back'); ?></label>
                      <input type="radio" name="watermark_type" id="watermark_bwge_typeimage" value="image" onClick="bwge_watermark('watermark_bwge_typeimage')" <?php echo ($option_row->watermark_type == 'image') ? 'checked' : ''; ?> /><label for="watermark_bwge_typeimage"><?php echo __('Image', 'bwge_back'); ?></label>
                    </td>
                  </tr>
                  <tr id="tr_watermark_link">
                    <td title="<?php echo __("Enter absolute url", 'bwge_back'); ?>, e.g. http://www.example.com" class="bwge_spider_label">
                      <label for="watermark_link"><?php echo __('Advertisement link:', 'bwge_back'); ?> </label>
                    </td>
                    <td>
                      <input type="text" id="watermark_link" name="watermark_link" value="<?php echo $option_row->watermark_link; ?>" style="display:inline-block;" />
                    </td>
                  </tr>
                  <tr id="tr_watermark_url">
                    <td title="<?php echo __("Enter absolute image file url or add file from Options page.", 'bwge_back'); ?>" class="bwge_spider_label">
                      <label for="watermark_url"><?php echo __('Advertisement url:', 'bwge_back'); ?> </label>
                    </td>
                    <td>
                      <input type="text" id="watermark_url" name="watermark_url" value="<?php echo $option_row->watermark_url; ?>" style="display:inline-block;" />
                    </td>
                  </tr>
                  <tr id="tr_watermark_width_height">
                    <td title="<?php echo __("Maximum values for watermark image width and height.", 'bwge_back'); ?>" class="bwge_spider_label"><label for="watermark_width"><?php echo __('Advertisement dimensions:', 'bwge_back'); ?> </label></td>
                    <td>
                      <input type="text" name="watermark_width" id="watermark_width" value="<?php echo $option_row->watermark_width; ?>" class="bwge_spider_int_input" /> x 
                      <input type="text" name="watermark_height" id="watermark_height" value="<?php echo $option_row->watermark_height; ?>" class="bwge_spider_int_input" /> px
                    </td>
                  </tr>
                  <tr id="tr_watermark_text">
                    <td class="bwge_spider_label"><label for="watermark_text"><?php echo __('Advertisement text:', 'bwge_back'); ?> </label></td>
                    <td>
                      <input type="text" name="watermark_text" id="watermark_text" value="<?php echo $option_row->watermark_text; ?>" />
                    </td>
                  </tr>
                  <tr id="tr_watermark_font_size">
                    <td class="bwge_spider_label"><label for="watermark_font_size"><?php echo __('Advertisement font size:', 'bwge_back'); ?> </label></td>
                    <td>
                      <input type="text" name="watermark_font_size" id="watermark_font_size" value="<?php echo $option_row->watermark_font_size; ?>" class="bwge_spider_int_input" /> px
                    </td>
                  </tr>
                  <tr id="tr_watermark_font">
                    <td class="bwge_spider_label"><label for="watermark_font"><?php echo __('Advertisement font style:', 'bwge_back'); ?> </label></td>
                    <td>
                      <select name="watermark_font" id="watermark_font" style="width:150px;">
                        <?php
                        foreach ($watermark_fonts as $watermark_font) {
                          ?>
                          <option value="<?php echo $watermark_font; ?>" <?php echo ($option_row->watermark_font == $watermark_font) ? 'selected' : ''; ?>><?php echo $watermark_font; ?></option>
                          <?php
                        }
                        ?>
                      </select>
                    </td>
                  </tr>
                  <tr id="tr_watermark_color">
                    <td class="bwge_spider_label"><label for="watermark_color"><?php echo __('Advertisement color:', 'bwge_back'); ?> </label></td>
                    <td>
                      <input type="text" name="watermark_color" id="watermark_color" value="<?php echo $option_row->watermark_color; ?>" class="color" />
                    </td>
                  </tr>
                  <tr id="tr_watermark_opacity">
                    <td title="<?php echo __("Value must be between 0 to 100.", 'bwge_back'); ?>" class="bwge_spider_label"><label for="watermark_opacity"><?php echo __('Advertisement opacity: ', 'bwge_back'); ?></label></td>
                    <td>
                      <input type="text" name="watermark_opacity" id="watermark_opacity" value="<?php echo $option_row->watermark_opacity; ?>" class="bwge_spider_int_input" /> %
                    </td>
                  </tr>
                  <tr id="tr_watermark_position">
                    <td class="bwge_spider_label"><label><?php echo __('Advertisement Position: ', 'bwge_back'); ?></label></td>
                    <td>
                      <table class="bws_position_table">
                        <tbody>
                          <tr>
                            <td><input type="radio" value="top-left" id="watermark_top-left" name="watermark_position" <?php echo ($option_row->watermark_position == 'top-left') ? 'checked' : ''; ?>></td>
                            <td><input type="radio" value="top-center" id="watermark_top-center" name="watermark_position" <?php echo ($option_row->watermark_position == 'top-center') ? 'checked' : ''; ?>></td>
                            <td><input type="radio" value="top-right" id="watermark_top-right" name="watermark_position" <?php echo ($option_row->watermark_position == 'top-right') ? 'checked' : ''; ?>></td>
                          </tr>
                          <tr>
                            <td><input type="radio" value="middle-left" id="watermark_middle-left" name="watermark_position" <?php echo ($option_row->watermark_position == 'middle-left') ? 'checked' : ''; ?>></td>
                            <td><input type="radio" value="middle-center" id="watermark_middle-center" name="watermark_position" <?php echo ($option_row->watermark_position == 'middle-center') ? 'checked' : ''; ?>></td>
                            <td><input type="radio" value="middle-right" id="watermark_middle-right" name="watermark_position" <?php echo ($option_row->watermark_position == 'middle-right') ? 'checked' : ''; ?>></td>
                          </tr>
                          <tr>
                            <td><input type="radio" value="bottom-left" id="watermark_bottom-left" name="watermark_position" <?php echo ($option_row->watermark_position == 'bottom-left') ? 'checked' : ''; ?>></td>
                            <td><input type="radio" value="bottom-center" id="watermark_bottom-center" name="watermark_position" <?php echo ($option_row->watermark_position == 'bottom-center') ? 'checked' : ''; ?>></td>
                            <td><input type="radio" value="bottom-right" id="watermark_bottom-right" name="watermark_position" <?php echo ($option_row->watermark_position == 'bottom-right') ? 'checked' : ''; ?>></td>
                          </tr>
                        </tbody>
                      </table>
                    </td>
                  </tr>
                </tbody>
              </table>
              </div>
          <?php
          if (!$from_menu) {
          ?>
            </div>
          </div>
          <div class="mceActionPanel">
            <div style="float:left;">
              <input type="button" id="cancel" name="cancel" value="Cancel" onClick="tinyMCEPopup.close();" />
            </div>
            <div style="float:right;">
              <input type="button" id="insert" name="insert" value="Insert" onClick="bwge_insert_shortcode('');" />
            </div>
          </div>
          <?php
          }
          else {
            $tagtext = '';
            $tagfunction = '';
            if (isset($_POST['currrent_id'])) {
              $currrent_id = stripslashes($_POST['currrent_id']);
              $title = ((isset($_POST['title'])) ? stripslashes($_POST['title']) : '');
              $tagtext = '[BWGE_Gallery_Ecommerce id="' . $currrent_id . '"' . $title . ']';
              $tagfunction = "<?php echo gallery_ecommerce(" . $currrent_id . "); ?>";
            }
            ?>
            <hr style="float: left; width: 100%;" />
            <span style="float: left; width: 100%;">
              <input type="button" class="wd-btn wd-btn-primary" id="insert" name="insert" value="<?php echo __('Generate', 'bwge_back'); ?>" onclick="bwge_insert_shortcode('');" />
              <input type="button" class="wd-btn wd-btn-secondary " id="import" name="import" value="<?php echo __('Import', 'bwge_back'); ?>" onclick="bwge_update_shortcode()" />
              <div>
                <input type="text" size="55" id="bwge_shortcode" name="bwge_shortcode" value='<?php echo $tagtext; ?>' onclick="bwge_onKeyDown(event)" />
                <b><?php echo __('Shortcode', 'bwge_back'); ?></b>
              </div>
              <div>
                <input type="text" size="55" id="bwge_function" name="bwge_function" value="<?php echo $tagfunction; ?>" onclick="bwge_spider_select_value(this)" readonly="readonly" />
                <b><?php echo __('PHP function', 'bwge_back'); ?></b>
              </div>
            </span>
            </div>
            <?php
          }
          ?>
          <input type="hidden" id="tagtext" name="tagtext" value="" />
          <input type="hidden" id="currrent_id" name="currrent_id" value="" />
          <input type="hidden" id="title" name="title" value="" />
          <input type="hidden" id="bwge_insert" name="bwge_insert" value="" />
          <input type="hidden" id="task" name="task" value="" />
        </form>
        </div>
        <script type="text/javascript">
          var ajaxurl = "<?php echo admin_url('admin-ajax.php'); ?>";
          var shortcodes = [];
          var shortcode_id = 1;
          <?php
          foreach ($shortcodes as $shortcode) {
            ?>
            shortcodes[<?php echo $shortcode->id; ?>] = '<?php echo addslashes($shortcode->tagtext); ?>';
            <?php
          }
          ?>
          shortcode_id = <?php echo $shortcode_max_id + 1; ?>;
          window.onload = bwge_shortcode_load;
          var params = get_params("BWGE_Gallery_Ecommerce");
          var bwge_insert = 1;
          bwge_update_shortcode();
          <?php if (!$from_menu) { ?>
          var content = tinyMCE.activeEditor.selection.getContent();
          <?php } else { ?>
          var content = jQuery("#bwge_shortcode").val();
          <?php } ?>
          function bwge_update_shortcode() {
            params = get_params("BWGE_Gallery_Ecommerce");
            if (!params) { // Insert.
            <?php if (!$from_menu) { ?>
              jQuery('#insert').val('Insert');
            <?php } ?>
              bwge_gallery_type('thumbnails');
            }
            else { // Update.
              if (params['id']) {
                shortcode_id = params['id'];
                if(typeof shortcodes[shortcode_id] === 'undefined'){
                  alert("<?php echo addslashes(__('There is no shortcode with such ID!', 'bwge_back')); ?>");
                  bwge_gallery_type('thumbnails');
                  return 0;
                }
                var short_code = get_short_params(shortcodes[shortcode_id]);
                bwge_insert = 0;
              }
              else {
                var short_code = get_params("BWGE_Gallery_Ecommerce");
              }
            <?php if (!$from_menu) { ?>
              jQuery('#insert').val('Update');
            <?php } else { ?>
              content = jQuery("#bwge_shortcode").val();
            <?php } ?>
              jQuery('#insert').attr('onclick', "bwge_insert_shortcode(content)");
              jQuery("select[id=theme] option[value='" + short_code['theme_id'] + "']").attr('selected', 'selected');
              switch (short_code['gallery_type']) {
                case 'thumbnails': {
                  jQuery("select[id=gallery] option[value='" + short_code['gallery_id'] + "']").attr('selected', 'selected');
                  jQuery("select[id=sort_by] option[value='" + short_code['sort_by'] + "']").attr('selected', 'selected');
                  if (short_code['order_by'] == 'asc') {
                    jQuery("#order_by_1").attr('checked', 'checked');
                  }
                  else {
                    jQuery("#order_by_0").attr('checked', 'checked');
                  }
                  if (short_code['show_search_box'] == 1) {
                    jQuery("#show_search_box_1").attr('checked', 'checked');
                    jQuery("#tr_search_box_width").css('display', '');
                  }
                  else {
                    jQuery("#show_search_box_0").attr('checked', 'checked');
                    jQuery("#tr_search_box_width").css('display', 'none');
                  }
									if (short_code['show_sort_images'] == 1) {
                    jQuery("#show_sort_images_1").attr('checked', 'checked');
                  }
                  else {
                    jQuery("#show_sort_images_0").attr('checked', 'checked');
                  }
                  if (short_code['show_tag_box'] == 1) {
                    jQuery("#show_tag_box_1").attr('checked', 'checked');
                  }
                  else {
                    jQuery("#show_tag_box_0").attr('checked', 'checked');
                  }
                  if (short_code['search_box_width']) {
                    jQuery("#search_box_width").val(short_code['search_box_width']);
                  }
                  jQuery("#image_column_number").val(short_code['image_column_number']);
                  jQuery("#images_per_page").val(short_code['images_per_page']);
                  jQuery("#load_more_image_count").val(short_code['load_more_image_count']);
                  jQuery("#image_title_" + short_code['image_title']).attr('checked', 'checked');
                  jQuery("#ecommerce_icon_" + short_code['ecommerce_icon']).attr('checked', 'checked');
                  if (short_code['image_enable_page'] == 1) {
                    jQuery("#image_page_yes").attr('checked', 'checked');
                  }
                  else if (short_code['image_enable_page'] == 0) {
                    jQuery("#image_page_no").attr('checked', 'checked');
                  }
                  else if (short_code['image_enable_page'] == 2) {
                    jQuery("#image_page_loadmore").attr('checked', 'checked');
                  }
                   else if (short_code['image_enable_page'] == 3) {
                    jQuery("#image_page_scrol_load").attr('checked', 'checked');
                  }
                  jQuery("#thumb_width").val(short_code['thumb_width']);
                  jQuery("#thumb_height").val(short_code['thumb_height']);
                  break;

                }
                case 'thumbnails_masonry': {
                  jQuery("select[id=gallery] option[value='" + short_code['gallery_id'] + "']").attr('selected', 'selected');
                  jQuery("select[id=sort_by] option[value='" + short_code['sort_by'] + "']").attr('selected', 'selected');
                  if (short_code['order_by'] == 'asc') {
                    jQuery("#order_by_1").attr('checked', 'checked');
                  }
                  else {
                    jQuery("#order_by_0").attr('checked', 'checked');
                  }
                  if (short_code['show_search_box'] == 1) {
                    jQuery("#show_search_box_1").attr('checked', 'checked');
                    jQuery("#tr_search_box_width").css('display', '');
                  }
                  else {
                    jQuery("#show_search_box_0").attr('checked', 'checked');
                    jQuery("#tr_search_box_width").css('display', 'none');
                  }
                  if (short_code['show_sort_images'] == 1) {
                    jQuery("#show_sort_images_1").attr('checked', 'checked');
                  }
                  else {
                    jQuery("#show_sort_images_0").attr('checked', 'checked');
                  }
                  if (short_code['show_tag_box'] == 1) {
                    jQuery("#show_tag_box_1").attr('checked', 'checked');
                  }
                  else {
                    jQuery("#show_tag_box_0").attr('checked', 'checked');
                  }
                  if (short_code['search_box_width']) {
                    jQuery("#search_box_width").val(short_code['search_box_width']);
                  }
                  if (short_code['masonry_hor_ver'] == 'horizontal') {
                    jQuery("#masonry_hor").attr('checked', 'checked');
                  }
                  else {
                    jQuery("#masonry_ver").attr('checked', 'checked');
                  }
                  jQuery("#image_column_number").val(short_code['image_column_number']);
                  jQuery("#images_per_page").val(short_code['images_per_page']);
                  jQuery("#load_more_image_count").val(short_code['load_more_image_count']);
                  if (short_code['image_enable_page'] == 1) {
                    jQuery("#image_page_yes").attr('checked', 'checked');
                  }
                  else if (short_code['image_enable_page'] == 0) {
                    jQuery("#image_page_no").attr('checked', 'checked');
                  }
                 else if (short_code['image_enable_page'] == 2) {
                    jQuery("#image_page_loadmore").attr('checked', 'checked');
                  }
                   else if (short_code['image_enable_page'] == 3) {
                    jQuery("#image_page_scrol_load").attr('checked', 'checked');
                  }
                  jQuery("#thumb_width").val(short_code['thumb_width']);
                  jQuery("#thumb_height").val(short_code['thumb_height']);
                  
                  jQuery("[name=ecommerce_icon]").removeAttr('checked');
                  
                  if(short_code['ecommerce_icon'] == "show"){
                    jQuery("#ecommerce_icon_hover").attr('checked', 'checked');
                   }
                   else{
                    jQuery("#ecommerce_icon_" + short_code['ecommerce_icon']).attr('checked', 'checked');
                   }
                  break;

                }
                case 'thumbnails_mosaic': {
                  jQuery("select[id=gallery] option[value='" + short_code['gallery_id'] + "']").attr('selected', 'selected');
                  jQuery("select[id=sort_by] option[value='" + short_code['sort_by'] + "']").attr('selected', 'selected');
                  if (short_code['order_by'] == 'asc') {
                    jQuery("#order_by_1").attr('checked', 'checked');
                  }
                  else {
                    jQuery("#order_by_0").attr('checked', 'checked');
                  }
                  if (short_code['show_search_box'] == 1) {
                    jQuery("#show_search_box_1").attr('checked', 'checked');
                    jQuery("#tr_search_box_width").css('display', '');
                  }
                  else {
                    jQuery("#show_search_box_0").attr('checked', 'checked');
                    jQuery("#tr_search_box_width").css('display', 'none');
                  }
                  if (short_code['search_box_width']) {
                    jQuery("#search_box_width").val(short_code['search_box_width']);
                  }
                  if (short_code['show_sort_images'] == 1) {
                    jQuery("#show_sort_images_1").attr('checked', 'checked');
                  }
                  else {
                    jQuery("#show_sort_images_0").attr('checked', 'checked');
                  }
                  if (short_code['show_tag_box'] == 1) {
                    jQuery("#show_tag_box_1").attr('checked', 'checked');
                  }
                  else {
                    jQuery("#show_tag_box_0").attr('checked', 'checked');
                  }
                  if (short_code['mosaic_hor_ver'] == 'horizontal') {
                    jQuery("#mosaic_hor").attr('checked', 'checked');
                  }
                  else {
                    jQuery("#mosaic_ver").attr('checked', 'checked');
                  }
                  if (short_code['resizable_mosaic'] == 1) {
                    jQuery("#resizable_mosaic_1").attr('checked', 'checked');
                  }
                  else {
                    jQuery("#resizable_mosaic_0").attr('checked', 'checked');
                  }
                  jQuery("#mosaic_total_width").val(short_code['mosaic_total_width']);
                  jQuery("#image_title_" + short_code['image_title']).attr('checked', 'checked');
                  jQuery("#images_per_page").val(short_code['images_per_page']);
                  jQuery("#load_more_image_count").val(short_code['load_more_image_count']);
                  if (short_code['image_enable_page'] == 1) {
                    jQuery("#image_page_yes").attr('checked', 'checked');
                  }
                  else if (short_code['image_enable_page'] == 0) {
                    jQuery("#image_page_no").attr('checked', 'checked');
                  }
                  else if (short_code['image_enable_page'] == 2){
                    jQuery("#image_page_loadmore").attr('checked', 'checked');
                  }
                  else if (short_code['image_enable_page'] == 3) {
                    jQuery("#image_page_scrol_load").attr('checked', 'checked');
                  }
                  jQuery("#thumb_width").val(short_code['thumb_width']);
                  jQuery("#thumb_height").val(short_code['thumb_height']);
                  
                   jQuery("[name=ecommerce_icon]").removeAttr('checked');
                  
                  if(short_code['ecommerce_icon'] == "show"){
                    jQuery("#ecommerce_icon_hover").attr('checked', 'checked');
                   }
                   else{
                    jQuery("#ecommerce_icon_" + short_code['ecommerce_icon']).attr('checked', 'checked');
                   }
                  break;

                }
                case 'slideshow': {
                  jQuery("select[id=gallery] option[value='" + short_code['gallery_id'] + "']").attr('selected', 'selected');
                  jQuery("select[id=sort_by] option[value='" + short_code['sort_by'] + "']").attr('selected', 'selected');
                  if (short_code['order_by'] == 'asc') {
                    jQuery("#order_by_1").attr('checked', 'checked');
                  }
                  else {
                    jQuery("#order_by_0").attr('checked', 'checked');
                  }
                  jQuery("select[id=slideshow_effect] option[value='" + short_code['slideshow_effect'] + "']").attr('selected', 'selected');
                  jQuery("#slideshow_interval").val(short_code['slideshow_interval']);
                  jQuery("#slideshow_width").val(short_code['slideshow_width']);
                  jQuery("#slideshow_height").val(short_code['slideshow_height']);
                  if (short_code['enable_slideshow_autoplay'] == 1) {
                    jQuery("#slideshow_autoplay_yes").attr('checked', 'checked');
                  }
                  else {
                    jQuery("#slideshow_autoplay_no").attr('checked', 'checked');
                  }
                  if (short_code['enable_slideshow_shuffle'] == 1) {
                    jQuery("#slideshow_shuffle_yes").attr('checked', 'checked');
                  }
                  else {
                    jQuery("#slideshow_shuffle_no").attr('checked', 'checked');
                  }
                  if (short_code['enable_slideshow_ctrl'] == 1) {
                    jQuery("#slideshow_ctrl_yes").attr('checked', 'checked');
                  }
                  else {
                    jQuery("#slideshow_ctrl_no").attr('checked', 'checked');
                  }
                  if (short_code['enable_slideshow_filmstrip'] == 1) {
                    jQuery("#slideshow_filmstrip_yes").attr('checked', 'checked');
                    jQuery("#slideshow_filmstrip_height").val(short_code['slideshow_filmstrip_height']);
                    bwge_enable_disable('', 'tr_slideshow_filmstrip_height', 'slideshow_filmstrip_yes');
                  }
                  else {
                    jQuery("#slideshow_filmstrip_no").attr('checked', 'checked');
                  }
                  if (short_code['slideshow_enable_title'] == 1) {
                    jQuery("#slideshow_title_yes").attr('checked', 'checked');
                    jQuery("#slideshow_title_" + short_code['slideshow_title_position']).attr('checked', 'checked');
                    bwge_enable_disable('', 'tr_slideshow_title_position', 'slideshow_title_yes');
                  }
                  else {
                    jQuery("#slideshow_title_no").attr('checked', 'checked');
                  }
                  if (short_code['slideshow_ecommerce_icon'] == 1) {
                    jQuery("#slideshow_ecommerce_icon_yes").attr('checked', 'checked');   
                  }
                  else {
                    jQuery("#slideshow_ecommerce_icon_no").attr('checked', 'checked');
                  }                  
                  if (short_code['slideshow_enable_description'] == 1) {
                    jQuery("#slideshow_description_yes").attr('checked', 'checked');
                    jQuery("#slideshow_description_" + short_code['slideshow_description_position']).attr('checked', 'checked');
                    bwge_enable_disable('', 'tr_slideshow_description_position', 'slideshow_description_yes');
                  }
                  else {
                    jQuery("#slideshow_description_no").attr('checked', 'checked');
                  }
                  if (short_code['enable_slideshow_music'] == 1) {
                    jQuery("#slideshow_music_yes").attr('checked', 'checked');
                    jQuery("#slideshow_music_url").val(short_code['slideshow_music_url']);
                    bwge_enable_disable('', 'tr_slideshow_music_url', 'slideshow_music_yes');
                  }
                  else {
                    jQuery("#slideshow_music_no").attr('checked', 'checked');
                  }
                  break;

                }
                case 'image_browser': {
                  jQuery("select[id=gallery] option[value='" + short_code['gallery_id'] + "']").attr('selected', 'selected');
                  jQuery("select[id=sort_by] option[value='" + short_code['sort_by'] + "']").attr('selected', 'selected');
                  if (short_code['order_by'] == 'asc') {
                    jQuery("#order_by_1").attr('checked', 'checked');
                  }
                  else {
                    jQuery("#order_by_0").attr('checked', 'checked');
                  }
                  if (short_code['show_search_box'] == 1) {
                    jQuery("#show_search_box_1").attr('checked', 'checked');
                    jQuery("#tr_search_box_width").css('display', '');
                  }
                  else {
                    jQuery("#show_search_box_0").attr('checked', 'checked');
                    jQuery("#tr_search_box_width").css('display', 'none');
                  }
                  if (short_code['search_box_width']) {
                    jQuery("#search_box_width").val(short_code['search_box_width']);
                  }
                  jQuery("#image_browser_width").val(short_code['image_browser_width']);
                  if (short_code['image_browser_title_enable'] == 1) {
                    jQuery("#image_browser_title_yes").attr('checked', 'checked');
                  }
                  else {
                    jQuery("#image_browser_title_no").attr('checked', 'checked');
                  }
                  if (short_code['image_browser_description_enable'] == 1) {
                    jQuery("#image_browser_description_yes").attr('checked', 'checked');
                  }
                  else {
                    jQuery("#image_browser_description_no").attr('checked', 'checked');
                  }
                  break;

                }
                case 'album_compact_preview': {
                  jQuery("select[id=album] option[value='" + short_code['album_id'] + "']").attr('selected', 'selected');
                  jQuery("select[id=sort_by] option[value='" + short_code['sort_by'] + "']").attr('selected', 'selected');
                  if (short_code['order_by'] == 'asc') {
                    jQuery("#order_by_1").attr('checked', 'checked');
                  }
                  else {
                    jQuery("#order_by_0").attr('checked', 'checked');
                  }
                  if (short_code['show_search_box'] == 1) {
                    jQuery("#show_search_box_1").attr('checked', 'checked');
                    jQuery("#tr_search_box_width").css('display', '');
                  }
                  else {
                    jQuery("#show_search_box_0").attr('checked', 'checked');
                    jQuery("#tr_search_box_width").css('display', 'none');
                  }
									if (short_code['show_sort_images'] == 1) {
                    jQuery("#show_sort_images_1").attr('checked', 'checked');
                  }
                  else {
                    jQuery("#show_sort_images_0").attr('checked', 'checked');
                  }
                  if (short_code['search_box_width']) {
                    jQuery("#search_box_width").val(short_code['search_box_width']);
                  }
                  jQuery("#compuct_album_column_number").val(short_code['compuct_album_column_number']);
                  jQuery("#compuct_albums_per_page").val(short_code['compuct_albums_per_page']);
                  jQuery("#compuct_album_title_" + short_code['compuct_album_title']).attr('checked', 'checked');
                  jQuery("#compuct_album_thumb_width").val(short_code['compuct_album_thumb_width']);
                  jQuery("#compuct_album_thumb_height").val(short_code['compuct_album_thumb_height']);
                  jQuery("#compuct_album_image_column_number").val(short_code['compuct_album_image_column_number']);
                  jQuery("#compuct_album_images_per_page").val(short_code['compuct_album_images_per_page']);
                  jQuery("#compuct_album_image_title_" + short_code['compuct_album_image_title']).attr('checked', 'checked');
                  jQuery("#compuct_album_image_thumb_width").val(short_code['compuct_album_image_thumb_width']);
                  jQuery("#compuct_album_image_thumb_height").val(short_code['compuct_album_image_thumb_height']);
                  jQuery("#compuct_album_load_more_image_count").val(short_code['compuct_album_load_more_image_count']);
                  jQuery("#compuct_albums_per_page_load_more").val(short_code['compuct_albums_per_page_load_more']);
                  if (short_code['compuct_album_enable_page'] == 1) {
                    jQuery("#compuct_album_page_yes").attr('checked', 'checked');
                  }
                  else if (short_code['compuct_album_enable_page'] == 0) {
                    jQuery("#compuct_album_page_no").attr('checked', 'checked');
                  }
                  else if (short_code['compuct_album_enable_page'] == 2) {
                    jQuery("#compuct_album_page_loadmore").attr('checked', 'checked');
                  }
                  else if (short_code['compuct_album_enable_page'] == 3) {
                    jQuery("#compuct_album_page_scrol_load").attr('checked', 'checked');
                  }
                  if (short_code['compuct_album_view_type'] == 'thumbnail') {				  
                    jQuery("#compuct_album_view_bwge_type1").attr('checked', 'checked');								  
                  }
                  else if (short_code['compuct_album_view_type'] == 'masonry'){ 
                    jQuery("#compuct_album_view_bwge_type0").attr('checked', 'checked');
                  }
                  else{
                    jQuery("#compuct_album_view_bwge_type2").attr('checked', 'checked');
                  }
                  if (short_code['compuct_album_mosaic_hor_ver'] == "vertical") {
                    jQuery("#compuct_album_mosaic_ver").attr('checked', 'checked');
                  }
                  else {
                    jQuery("#compuct_album_mosaic_hor").attr('checked', 'checked');
                  }
                  if (short_code['compuct_album_resizable_mosaic'] == 1) {
                    jQuery("#compuct_album_resizable_mosaic_1").attr('checked', 'checked');
                  }
                  else {
                    jQuery("#compuct_album_resizable_mosaic_0").attr('checked', 'checked');
                  }
                  if (short_code['show_tag_box'] == 1) {
                    jQuery("#show_tag_box_1").attr('checked', 'checked');
                  }
                  else {
                    jQuery("#show_tag_box_0").attr('checked', 'checked');
                  }
                  jQuery("#compuct_album_mosaic_total_width").val(short_code['compuct_album_mosaic_total_width']);
                  jQuery("#ecommerce_icon_" + short_code['ecommerce_icon']).attr('checked', 'checked');
                  break;

                }
                case 'album_extended_preview': {
                  jQuery("select[id=album] option[value='" + short_code['album_id'] + "']").attr('selected', 'selected');
                  jQuery("select[id=sort_by] option[value='" + short_code['sort_by'] + "']").attr('selected', 'selected');
                  if (short_code['order_by'] == 'asc') {
                    jQuery("#order_by_1").attr('checked', 'checked');
                  }
                  else {
                    jQuery("#order_by_0").attr('checked', 'checked');
                  }
                  if (short_code['show_search_box'] == 1) {
                    jQuery("#show_search_box_1").attr('checked', 'checked');
                    jQuery("#tr_search_box_width").css('display', '');
                  }
                  else {
                    jQuery("#show_search_box_0").attr('checked', 'checked');
                    jQuery("#tr_search_box_width").css('display', 'none');
                  }
									if (short_code['show_sort_images'] == 1) {
                    jQuery("#show_sort_images_1").attr('checked', 'checked');
                  }
                  else {
                    jQuery("#show_sort_images_0").attr('checked', 'checked');
                  }
                  if (short_code['search_box_width']) {
                    jQuery("#search_box_width").val(short_code['search_box_width']);
                  }
                  jQuery("#extended_albums_per_page").val(short_code['extended_albums_per_page']);
                  jQuery("#extended_album_height").val(short_code['extended_album_height']);
                  if (short_code['extended_album_description_enable'] == 1) {
                    jQuery("#extended_album_description_yes").attr('checked', 'checked');
                  }
                  else {
                    jQuery("#extended_album_description_no").attr('checked', 'checked');
                  }
                  jQuery("#extended_album_thumb_width").val(short_code['extended_album_thumb_width']);
                  jQuery("#extended_album_thumb_height").val(short_code['extended_album_thumb_height']);
                  jQuery("#extended_album_image_column_number").val(short_code['extended_album_image_column_number']);
                  jQuery("#extended_album_images_per_page").val(short_code['extended_album_images_per_page']);
                  jQuery("#extended_album_image_title_" + short_code['extended_album_image_title']).attr('checked', 'checked');
                  jQuery("#extended_album_image_thumb_width").val(short_code['extended_album_image_thumb_width']);
                  jQuery("#extended_album_image_thumb_height").val(short_code['extended_album_image_thumb_height']);
                  jQuery("#extended_albums_per_page_load_more").val(short_code['extended_albums_per_page_load_more']);
                  jQuery("#extended_album_load_more_image_count").val(short_code['extended_album_load_more_image_count']);
                  if (short_code['extended_album_enable_page'] == 1) {
                    jQuery("#extended_album_page_yes").attr('checked', 'checked');
                  }
                  else if (short_code['extended_album_enable_page'] == 0) {
                    jQuery("#extended_album_page_no").attr('checked', 'checked');
                  }
                   else if (short_code['extended_album_enable_page'] == 2) {
                    jQuery("#extended_album_page_loadmore").attr('checked', 'checked');
                  }
                  else if (short_code['extended_album_enable_page'] == 3){
                    jQuery("#extended_album_page_scrol_load").attr('checked', 'checked');
                  }
                  if (short_code['extended_album_view_type'] == 'thumbnail') {  
                    jQuery("#extended_album_view_bwge_type1").attr('checked', 'checked');
                  }
                  else if(short_code['extended_album_view_type'] == 'masonry'){
                    jQuery("#extended_album_view_bwge_type0").attr('checked', 'checked');
                  }
                  else{
                    jQuery("#extended_album_view_bwge_type2").attr('checked', 'checked');
                  }
                  if (short_code['extended_album_mosaic_hor_ver'] == "vertical") {
                    jQuery("#extended_album_mosaic_ver").attr('checked', 'checked');
                  }
                  else {
                    jQuery("#extended_album_mosaic_hor").attr('checked', 'checked');
                  }
                  if (short_code['extended_album_resizable_mosaic'] == 1) {
                    jQuery("#extended_album_resizable_mosaic_1").attr('checked', 'checked');
                  }
                  else {
                    jQuery("#extended_album_resizable_mosaic_0").attr('checked', 'checked');
                  }
                  if (short_code['show_tag_box'] == 1) {
                    jQuery("#show_tag_box_1").attr('checked', 'checked');
                  }
                  else {
                    jQuery("#show_tag_box_0").attr('checked', 'checked');
                  }
                  jQuery("#extended_album_mosaic_total_width").val(short_code['extended_album_mosaic_total_width']);
                  jQuery("#ecommerce_icon_" + short_code['ecommerce_icon']).attr('checked', 'checked');
                  break;

                }								
                case 'album_masonry_preview': {
                  jQuery("select[id=album] option[value='" + short_code['album_id'] + "']").attr('selected', 'selected');
                  jQuery("select[id=sort_by] option[value='" + short_code['sort_by'] + "']").attr('selected', 'selected');
                  if (short_code['order_by'] == 'asc') {
                    jQuery("#order_by_1").attr('checked', 'checked');
                  }
                  else {
                    jQuery("#order_by_0").attr('checked', 'checked');
                  }
                  if (short_code['show_search_box'] == 1) {
                    jQuery("#show_search_box_1").attr('checked', 'checked');
                    jQuery("#tr_search_box_width").css('display', '');
                  }
                  else {
                    jQuery("#show_search_box_0").attr('checked', 'checked');
                    jQuery("#tr_search_box_width").css('display', 'none');
                  }
									if (short_code['show_sort_images'] == 1) {
                    jQuery("#show_sort_images_1").attr('checked', 'checked');
                  }
                  else {
                    jQuery("#show_sort_images_0").attr('checked', 'checked');
                  }
                  if (short_code['search_box_width']) {
                    jQuery("#search_box_width").val(short_code['search_box_width']);
                  }
                  jQuery("#masonry_album_column_number").val(short_code['masonry_album_column_number']);
                  jQuery("#masonry_albums_per_page").val(short_code['masonry_albums_per_page']);
                  jQuery("#masonry_album_title_" + short_code['masonry_album_title']).attr('checked', 'checked');
                  jQuery("#masonry_album_thumb_width").val(short_code['masonry_album_thumb_width']);
                  jQuery("#masonry_album_thumb_height").val(short_code['masonry_album_thumb_height']);
                  jQuery("#masonry_album_image_column_number").val(short_code['masonry_album_image_column_number']);
                  jQuery("#masonry_album_images_per_page").val(short_code['masonry_album_images_per_page']);
                  jQuery("#masonry_album_image_title_" + short_code['masonry_album_image_title']).attr('checked', 'checked');
                  jQuery("#masonry_album_image_thumb_width").val(short_code['masonry_album_image_thumb_width']);
                  jQuery("#masonry_album_image_thumb_height").val(short_code['masonry_album_image_thumb_height']);
                  jQuery("#masonry_album_load_more_image_count").val(short_code['masonry_album_load_more_image_count']);
                  jQuery("#masonry_albums_per_page_load_more").val(short_code['masonry_albums_per_page_load_more']);
                  
                  jQuery("[name=ecommerce_icon]").removeAttr('checked');                  
                  if(short_code['ecommerce_icon'] == "show"){
                    jQuery("#ecommerce_icon_hover").attr('checked', 'checked');
                   }
                   else{
                    jQuery("#ecommerce_icon_" + short_code['ecommerce_icon']).attr('checked', 'checked');
                   }
                  if (short_code['masonry_album_enable_page'] == 1) {
                    jQuery("#masonry_album_page_yes").attr('checked', 'checked');
                  }
                  else if (short_code['masonry_album_enable_page'] == 0) {
                    jQuery("#masonry_album_page_no").attr('checked', 'checked');
                  }
                  else if (short_code['masonry_album_enable_page'] == 2) {
                    jQuery("#masonry_album_page_loadmore").attr('checked', 'checked');
                  }
                   else if (short_code['masonry_album_enable_page'] == 3) {
                    jQuery("#masonry_album_page_scrol_load").attr('checked', 'checked');
                  }
                  if (short_code['show_tag_box'] == 1) {
                    jQuery("#show_tag_box_1").attr('checked', 'checked');
                  }
                  else {
                    jQuery("#show_tag_box_0").attr('checked', 'checked');
                  }
                  break;

                }
                case 'blog_style': {
                  jQuery("select[id=gallery] option[value='" + short_code['gallery_id'] + "']").attr('selected', 'selected');
                  jQuery("select[id=sort_by] option[value='" + short_code['sort_by'] + "']").attr('selected', 'selected');
                  if (short_code['order_by'] == 'asc') {
                    jQuery("#order_by_1").attr('checked', 'checked');
                  }
                  else {
                    jQuery("#order_by_0").attr('checked', 'checked');
                  }
                  if (short_code['show_search_box'] == 1) {
                    jQuery("#show_search_box_1").attr('checked', 'checked');
                    jQuery("#tr_search_box_width").css('display', '');
                  }
                  else {
                    jQuery("#show_search_box_0").attr('checked', 'checked');
                    jQuery("#tr_search_box_width").css('display', 'none');
                  }
									if (short_code['show_sort_images'] == 1) {
                    jQuery("#show_sort_images_1").attr('checked', 'checked');
                  }
                  else {
                    jQuery("#show_sort_images_0").attr('checked', 'checked');
                  }
                  if (short_code['search_box_width']) {
                    jQuery("#search_box_width").val(short_code['search_box_width']);
                  }
                  jQuery("#blog_style_width").val(short_code['blog_style_width']);
                  if (short_code['blog_style_title_enable'] == 1) {
                    jQuery("#blog_style_title_yes").attr('checked', 'checked');
                  }
                  else {
                    jQuery("#blog_style_title_no").attr('checked', 'checked');
                  }
                  jQuery("#blog_style_images_per_page").val(short_code['blog_style_images_per_page']);
                  jQuery("#blog_style_load_more_image_count").val(short_code['blog_style_load_more_image_count']);
                  if (short_code['blog_style_enable_page'] == 1) {
                    jQuery("#blog_style_page_yes").attr('checked', 'checked');
                  }
                  else if (short_code['blog_style_enable_page'] == 0) {
                    jQuery("#blog_style_page_no").attr('checked', 'checked');
                  }
                  else if (short_code['blog_style_enable_page'] == 2) {
                    jQuery("#blog_style_page_loadmore").attr('checked', 'checked');
                  }
                  else if (short_code['blog_style_enable_page'] == 3) {
                    jQuery("#blog_style_page_scrol_load").attr('checked', 'checked');
                  }
                  if (short_code['show_tag_box'] == 1) {
                    jQuery("#show_tag_box_1").attr('checked', 'checked');
                  }
                  else {
                    jQuery("#show_tag_box_0").attr('checked', 'checked');
                  }
                  break;
                }
                case 'carousel': {
                  jQuery("select[id=gallery] option[value='" + short_code['gallery_id'] + "']").attr('selected', 'selected');
                  jQuery("select[id=sort_by] option[value='" + short_code['sort_by'] + "']").attr('selected', 'selected');
                  if (short_code['order_by'] == 'asc') {
                    jQuery("#order_by_1").attr('checked', 'checked');
                  }
                  else {
                    jQuery("#order_by_0").attr('checked', 'checked');
                  }       
                  jQuery("#carousel_interval").val(short_code['carousel_interval']);
                  jQuery("#carousel_width").val(short_code['carousel_width']);
                  jQuery("#carousel_height").val(short_code['carousel_height']);
				          jQuery("#carousel_image_angle").val(short_code['carousel_image_angle']);
				          jQuery("#carousel_image_column_number").val(short_code['carousel_image_column_number']);
                  jQuery("#carousel_image_par").val(short_code['carousel_image_par']);
                  
                  if (short_code['enable_carousel_autoplay'] == 1) {
                    jQuery("#carousel_autoplay_yes").attr('checked', 'checked');
                  }
                  else {
                    jQuery("#carousel_autoplay_no").attr('checked', 'checked');
                  }  
                  if (short_code['enable_carousel_title'] == 1) {
                    jQuery("#carousel_title_yes").attr('checked', 'checked');
                  }
                  else {
                    jQuery("#carousel_title_no").attr('checked', 'checked');
                  }
                  if (short_code['carousel_ecommerce_icon'] == 1) {
                    jQuery("#carousel_ecommerce_icon_yes").attr('checked', 'checked');
                  }
                  else {
                    jQuery("#carousel_ecommerce_icon_no").attr('checked', 'checked');
                  }                  
                  jQuery("#carousel_r_width").val(short_code['carousel_r_width']);
                  if (short_code['carousel_fit_containerWidth'] == 1) {
                    jQuery("#carousel_fit_containerWidth").attr('checked', 'checked');
                  }
                  else {
                    jQuery("#carousel_fit_containerWidth_no").attr('checked', 'checked');
                  }
                  if (short_code['carousel_prev_next_butt'] == 1) {
                    jQuery("#carousel_prev_next_butt_yes").attr('checked', 'checked');
                  }
                  else {
                    jQuery("#carousel_prev_next_butt_no").attr('checked', 'checked');
                  }  
                  if (short_code['carousel_play_pause_butt'] == 1) {
                    jQuery("#carousel_play_pause_butt_yes").attr('checked', 'checked');
                  }
                  else {
                    jQuery("#carousel_play_pause_butt_no").attr('checked', 'checked');
                  }  
                  break;
                }
              }
              // Lightbox.
              if (short_code['gallery_type'] != 'slideshow' && short_code['gallery_type'] != 'carousel') {
                jQuery("#popup_width").val(short_code['popup_width']);
                jQuery("#popup_height").val(short_code['popup_height']);
                jQuery("select[id=popup_effect] option[value='" + short_code['popup_effect'] + "']").attr('selected', 'selected');
                jQuery("#popup_interval").val(short_code['popup_interval']);
                if (short_code['popup_fullscreen'] == 1) {
                  jQuery("#popup_fullscreen_1").attr('checked', 'checked'); 
                  jQuery("#tr_popup_width_height").css('display', 'none');
                }
                else {
                  jQuery("#popup_fullscreen_0").attr('checked', 'checked');
                  jQuery("#tr_popup_width_height").css('display', '');
                }
                if (short_code['popup_autoplay'] == 1) {
                  jQuery("#popup_autoplay_1").attr('checked', 'checked'); 
                }
                else {
                  jQuery("#popup_autoplay_0").attr('checked', 'checked');
                }
                if (short_code['popup_enable_filmstrip'] == 1) {
                  jQuery("#popup_filmstrip_yes").attr('checked', 'checked');
                  jQuery("#popup_filmstrip_height").val(short_code['popup_filmstrip_height']);
                  bwge_enable_disable('', 'tr_popup_filmstrip_height', 'popup_filmstrip_yes');
                }
                else {
                  jQuery("#popup_filmstrip_no").attr('checked', 'checked');
                }
                if (short_code['popup_enable_ctrl_btn'] == 1) {
                  jQuery("#popup_ctrl_btn_yes").attr('checked', 'checked');
                  bwge_enable_disable('', 'tbody_popup_ctrl_btn', 'popup_ctrl_btn_yes');
                  if (short_code['popup_enable_fullscreen'] == 1) {
                    jQuery("#popup_fullscreen_yes").attr('checked', 'checked');
                  }
                  else {
                    jQuery("#popup_fullscreen_no").attr('checked', 'checked');
                  }
                  if (short_code['popup_enable_info'] == 1 || !short_code['popup_enable_info']) {
                    jQuery("#popup_info_yes").attr('checked', 'checked');
                  }
                  else {
                    jQuery("#popup_info_no").attr('checked', 'checked');
                  }
                  if (short_code['show_tag_box'] == 0 || !short_code['show_tag_box']) {
                    jQuery("#show_tag_box_0").attr('checked', 'checked');
                  }
                  else {
                    jQuery("#show_tag_box_1").attr('checked', 'checked');
                  }
                  if (short_code['popup_info_full_width'] == 1) {
                    jQuery("#popup_info_full_width_1").attr('checked', 'checked');
                  }
                  else {
                    jQuery("#popup_info_full_width_0").attr('checked', 'checked');
                  }
                  if (short_code['popup_info_always_show'] == 1 && short_code['popup_info_always_show']) {
                    jQuery("#popup_info_always_show_yes").attr('checked', 'checked');
                  }
                  else {
                    jQuery("#popup_info_always_show_no").attr('checked', 'checked');
                  }
                  if (short_code['popup_enable_rate'] == 1 && short_code['popup_enable_rate']) {
                    jQuery("#popup_rate_yes").attr('checked', 'checked');
                  }
                  else {
                    jQuery("#popup_rate_no").attr('checked', 'checked');
                  }
                  if (short_code['popup_enable_comment'] == 1) {
                    jQuery("#popup_comment_yes").attr('checked', 'checked');
                  }
                  else {
                    jQuery("#popup_comment_no").attr('checked', 'checked');
                  }
                  if (short_code['popup_hit_counter'] == 1 && short_code['popup_hit_counter']) {
                    jQuery("#popup_hit_counter_yes").attr('checked', 'checked');
                  }
                  else {
                    jQuery("#popup_hit_counter_no").attr('checked', 'checked');
                  }
                  if (short_code['popup_enable_facebook'] == 1) {
                    jQuery("#popup_facebook_yes").attr('checked', 'checked');
                  }
                  else {
                    jQuery("#popup_facebook_no").attr('checked', 'checked');
                  }
                  if (short_code['popup_enable_twitter'] == 1) {
                    jQuery("#popup_twitter_yes").attr('checked', 'checked');
                  }
                  else {
                    jQuery("#popup_twitter_no").attr('checked', 'checked');
                  }
                  if (short_code['popup_enable_google'] == 1) {
                    jQuery("#popup_google_yes").attr('checked', 'checked');
                  }
                  else {
                    jQuery("#popup_google_no").attr('checked', 'checked');
                  }
                  if (short_code['popup_enable_ecommerce'] == 1) {
                    jQuery("#popup_ecommerce_yes").attr('checked', 'checked');
                  }
                  else {
                    jQuery("#popup_ecommerce_no").attr('checked', 'checked');
                  }
                  if (short_code['popup_enable_pinterest'] == 1) {
                    jQuery("#popup_pinterest_yes").attr('checked', 'checked');
                  }
                  else {
                    jQuery("#popup_pinterest_no").attr('checked', 'checked');
                  }
                  if (short_code['popup_enable_tumblr'] == 1) {
                    jQuery("#popup_tumblr_yes").attr('checked', 'checked');
                  }
                  else {
                    jQuery("#popup_tumblr_no").attr('checked', 'checked');
                  }
                }
                else {
                  jQuery("#popup_ctrl_btn_no").attr('checked', 'checked');
                }
                if (!short_code['thumb_click_action'] || short_code['thumb_click_action'] == 'undefined' || short_code['thumb_click_action'] == 'open_lightbox') {
                  jQuery("#thumb_click_action_1").attr('checked', 'checked'); 
                }
                else if (short_code['thumb_click_action'] == 'redirect_to_url') {
                  jQuery("#thumb_click_action_2").attr('checked', 'checked');
                }
                else if (short_code['thumb_click_action'] == 'do_nothing') {
                  jQuery("#thumb_click_action_3").attr('checked', 'checked');
                }
                if (short_code['thumb_link_target'] == 1 || !short_code['thumb_link_target'] || short_code['thumb_link_target'] == 'undefined') {
                  jQuery("#thumb_link_target_yes").attr('checked', 'checked'); 
                }
                else {
                  jQuery("#thumb_link_target_no").attr('checked', 'checked');
                }
                bwge_thumb_click_action();
              }
              // Watermark.
              if (short_code['watermark_type'] == 'text') {
                jQuery("#watermark_bwge_typetext").attr('checked', 'checked');
                jQuery("#watermark_link").val(decodeURIComponent(short_code['watermark_link']));
                jQuery("#watermark_text").val(short_code['watermark_text']);
                jQuery("#watermark_font_size").val(short_code['watermark_font_size']);
                jQuery("select[id=watermark_font] option[value='" + short_code['watermark_font'] + "']").attr('selected', 'selected');
                jQuery("#watermark_color").val(short_code['watermark_color']);
                jQuery("#watermark_opacity").val(short_code['watermark_opacity']);
                jQuery("#watermark_bwge_typetext").attr('checked', 'checked');
                jQuery("#watermark_" + short_code['watermark_position']).attr('checked', 'checked');
              }
              else if (short_code['watermark_type'] == 'image') {
                jQuery("#watermark_bwge_typeimage").attr('checked', 'checked');
                jQuery("#watermark_link").val(decodeURIComponent(short_code['watermark_link']));
                jQuery("#watermark_url").val(short_code['watermark_url']);
                jQuery("#watermark_width").val(short_code['watermark_width']);
                jQuery("#watermark_height").val(short_code['watermark_height']);
                jQuery("#watermark_opacity").val(short_code['watermark_opacity']);
                jQuery("#watermark_bwge_typeimage").attr('checked', 'checked');
                jQuery("#watermark_" + short_code['watermark_position']).attr('checked', 'checked');
              }
              else {
                jQuery("#watermark_bwge_typenone").attr('checked', 'checked');
              }
              bwge_watermark('watermark_bwge_type' + short_code['watermark_type']);
              bwge_gallery_type(short_code['gallery_type']);
            }
          }
          // Get shortcodes attributes.
          function get_params(module_name) {
            <?php if (!$from_menu) { ?>
            var selected_text = tinyMCE.activeEditor.selection.getContent();
            <?php } else { ?>
            var selected_text = jQuery("#bwge_shortcode").val();
            <?php } ?>
            var module_start_index = selected_text.indexOf("[" + module_name);
            var module_end_index = selected_text.indexOf("]", module_start_index);
            var module_str = "";
            if ((module_start_index >= 0) && (module_end_index >= 0)) {
              module_str = selected_text.substring(module_start_index + 1, module_end_index);
            }
            else {
              return false;
            }
            var params_str = module_str.substring(module_str.indexOf(" ") + 1);
            var key_values = params_str.split('" ');
            var short_code_attr = new Array();
            for (var key in key_values) {
              var short_code_index = key_values[key].split('=')[0];
              var short_code_value = key_values[key].split('=')[1];
              short_code_value = short_code_value.replace(/\"/g, '');
              short_code_attr[short_code_index] = short_code_value;
            }
            return short_code_attr;
          }
          function get_short_params(tagtext) {
            var params_str = tagtext.substring(tagtext.indexOf(" ") + 1);
            var key_values = params_str.split('" ');
            var short_code_attr = new Array();
            for (var key in key_values) {
              var short_code_index = key_values[key].split('=')[0];
              var short_code_value = key_values[key].split('=')[1];
              short_code_value = short_code_value.replace(/\"/g, '');
              short_code_attr[short_code_index] = short_code_value;
            }
            return short_code_attr;
          }
          function bwge_insert_shortcode(content) {
            window.parent.window.jQuery(window.parent.document).trigger("onOpenShortcode");
            var gallery_type = jQuery("input[name=gallery_type]:checked").val();
            var theme = jQuery("#theme").val();
            var title = "";
            var short_code = '[BWGE_Gallery_Ecommerce';
            var tagtext = ' gallery_type="' + gallery_type + '" theme_id="' + theme + '"';
            switch (gallery_type) {
              case 'thumbnails': {
                tagtext += ' gallery_id="' + jQuery("#gallery").val() + '"';
                tagtext += ' sort_by="' + jQuery("#sort_by").val() + '"';
                tagtext += ' order_by="' + jQuery("input[name=order_by]:checked").val() + '"';
                tagtext += ' show_search_box="' + jQuery("input[name=show_search_box]:checked").val() + '"';
                tagtext += ' show_sort_images="' + jQuery("input[name=show_sort_images]:checked").val() + '"';
                tagtext += ' search_box_width="' + jQuery("#search_box_width").val() + '"';
                tagtext += ' image_column_number="' + jQuery("#image_column_number").val() + '"';
                tagtext += ' images_per_page="' + jQuery("#images_per_page").val() + '"';
                tagtext += ' image_title="' + jQuery("input[name=image_title]:checked").val() + '"';
                tagtext += ' ecommerce_icon="' + jQuery("input[name=ecommerce_icon]:checked").val() + '"';
                tagtext += ' image_enable_page="' + jQuery("input[name=image_enable_page]:checked").val() + '"';
                tagtext += ' thumb_width="' + jQuery("#thumb_width").val() + '"';
                tagtext += ' thumb_height="' + jQuery("#thumb_height").val() + '"';
                title = ' gal_title="' + jQuery.trim(jQuery('#gallery option:selected').text().replace("'", "").replace('"', '')) + '"';
                tagtext += ' load_more_image_count="' + jQuery("#load_more_image_count").val() + '"';
                tagtext += ' show_tag_box="' + jQuery("input[name=show_tag_box]:checked").val() + '"';
                break;

              }
              case 'thumbnails_masonry': {
                tagtext += ' gallery_id="' + jQuery("#gallery").val() + '"';
                tagtext += ' sort_by="' + jQuery("#sort_by").val() + '"';
                tagtext += ' order_by="' + jQuery("input[name=order_by]:checked").val() + '"';
                tagtext += ' show_search_box="' + jQuery("input[name=show_search_box]:checked").val() + '"';
								tagtext += ' show_sort_images="' + jQuery("input[name=show_sort_images]:checked").val() + '"';
                tagtext += ' search_box_width="' + jQuery("#search_box_width").val() + '"';
                tagtext += ' masonry_hor_ver="' + jQuery("input[name=masonry_hor_ver]:checked").val() + '"';
                tagtext += ' image_column_number="' + jQuery("#image_column_number").val() + '"';
                tagtext += ' images_per_page="' + jQuery("#images_per_page").val() + '"';
                tagtext += ' image_enable_page="' + jQuery("input[name=image_enable_page]:checked").val() + '"';
                tagtext += ' thumb_width="' + jQuery("#thumb_width").val() + '"';
                tagtext += ' thumb_height="' + jQuery("#thumb_height").val() + '"';
								title = ' gal_title="' + jQuery.trim(jQuery('#gallery option:selected').text().replace("'", "").replace('"', '')) + '"';
                tagtext += ' load_more_image_count="' + jQuery("#load_more_image_count").val() + '"';
                tagtext += ' show_tag_box="' + jQuery("input[name=show_tag_box]:checked").val() + '"';
                tagtext += ' ecommerce_icon="' + jQuery("input[name=ecommerce_icon]:checked").val() + '"';
                break;

              }
              case 'thumbnails_mosaic': {
                tagtext += ' gallery_id="' + jQuery("#gallery").val() + '"';
                tagtext += ' sort_by="' + jQuery("#sort_by").val() + '"';
                tagtext += ' order_by="' + jQuery("input[name=order_by]:checked").val() + '"';
                tagtext += ' show_search_box="' + jQuery("input[name=show_search_box]:checked").val() + '"';
                tagtext += ' show_sort_images="' + jQuery("input[name=show_sort_images]:checked").val() + '"';
                tagtext += ' search_box_width="' + jQuery("#search_box_width").val() + '"';
                tagtext += ' mosaic_hor_ver="' + jQuery("input[name=mosaic_hor_ver]:checked").val() + '"';
                tagtext += ' resizable_mosaic="' + jQuery("input[name=resizable_mosaic]:checked").val() + '"';
                tagtext += ' mosaic_total_width="' + jQuery("#mosaic_total_width").val() + '"';
                tagtext += ' image_title="' + jQuery("input[name=image_title]:checked").val() + '"';
                tagtext += ' ecommerce_icon="' + jQuery("input[name=ecommerce_icon]:checked").val() + '"';
                tagtext += ' images_per_page="' + jQuery("#images_per_page").val() + '"';
                tagtext += ' image_enable_page="' + jQuery("input[name=image_enable_page]:checked").val() + '"';
                tagtext += ' thumb_width="' + jQuery("#thumb_width").val() + '"';
                tagtext += ' thumb_height="' + jQuery("#thumb_height").val() + '"';
                title = ' gal_title="' + jQuery.trim(jQuery('#gallery option:selected').text().replace("'", "").replace('"', '')) + '"';
                tagtext += ' load_more_image_count="' + jQuery("#load_more_image_count").val() + '"';
                tagtext += ' show_tag_box="' + jQuery("input[name=show_tag_box]:checked").val() + '"';
                break;

              }
              case 'slideshow': {
                tagtext += ' gallery_id="' + jQuery("#gallery").val() + '"';
                tagtext += ' sort_by="' + jQuery("#sort_by").val() + '"';
                tagtext += ' order_by="' + jQuery("input[name=order_by]:checked").val() + '"';
                tagtext += ' slideshow_effect="' + jQuery("#slideshow_effect").val() + '"';
                tagtext += ' slideshow_interval="' + jQuery("#slideshow_interval").val() + '"';
                tagtext += ' slideshow_width="' + jQuery("#slideshow_width").val() + '"';
                tagtext += ' slideshow_height="' + jQuery("#slideshow_height").val() + '"';
                tagtext += ' enable_slideshow_autoplay="' + jQuery("input[name=enable_slideshow_autoplay]:checked").val() + '"';
                tagtext += ' enable_slideshow_shuffle="' + jQuery("input[name=enable_slideshow_shuffle]:checked").val() + '"';
                tagtext += ' enable_slideshow_ctrl="' + jQuery("input[name=enable_slideshow_ctrl]:checked").val() + '"';
                tagtext += ' enable_slideshow_filmstrip="' + jQuery("input[name=enable_slideshow_filmstrip]:checked").val() + '"';
                tagtext += ' slideshow_filmstrip_height="' + jQuery("#slideshow_filmstrip_height").val() + '"';
                tagtext += ' slideshow_enable_title="' + jQuery("input[name=slideshow_enable_title]:checked").val() + '"';
                tagtext += ' slideshow_ecommerce_icon="' + jQuery("input[name=slideshow_ecommerce_icon]:checked").val() + '"';
                tagtext += ' slideshow_title_position="' + jQuery("input[name=slideshow_title_position]:checked").val() + '"';
                tagtext += ' slideshow_title_full_width="' + jQuery("input[name=slideshow_title_full_width]:checked").val() + '"';  				
                tagtext += ' slideshow_enable_description="' + jQuery("input[name=slideshow_enable_description]:checked").val() + '"';
                tagtext += ' slideshow_description_position="' + jQuery("input[name=slideshow_description_position]:checked").val() + '"';
                tagtext += ' enable_slideshow_music="' + jQuery("input[name=enable_slideshow_music]:checked").val() + '"';
                tagtext += ' slideshow_music_url="' + jQuery("#slideshow_music_url").val() + '"';
								title = ' gal_title="' + jQuery.trim(jQuery('#gallery option:selected').text().replace("'", "").replace('"', '')) + '"';
                break;

              }
              case 'image_browser': {
                tagtext += ' gallery_id="' + jQuery("#gallery").val() + '"';
                tagtext += ' sort_by="' + jQuery("#sort_by").val() + '"';
                tagtext += ' order_by="' + jQuery("input[name=order_by]:checked").val() + '"';
                tagtext += ' show_search_box="' + jQuery("input[name=show_search_box]:checked").val() + '"';
                tagtext += ' search_box_width="' + jQuery("#search_box_width").val() + '"';
                tagtext += ' image_browser_width="' + jQuery("#image_browser_width").val() + '"';
                tagtext += ' image_browser_title_enable="' + jQuery("input[name=image_browser_title_enable]:checked").val() + '"';
                tagtext += ' image_browser_description_enable="' + jQuery("input[name=image_browser_description_enable]:checked").val() + '"';
								title = ' gal_title="' + jQuery.trim(jQuery('#gallery option:selected').text().replace("'", "").replace('"', '')) + '"';
                break;

              }
              case 'album_compact_preview': {
                tagtext += ' album_id="' + jQuery("#album").val() + '"';
                tagtext += ' sort_by="' + jQuery("#sort_by").val() + '"';
                tagtext += ' order_by="' + jQuery("input[name=order_by]:checked").val() + '"';
                tagtext += ' show_search_box="' + jQuery("input[name=show_search_box]:checked").val() + '"';
								tagtext += ' show_sort_images="' + jQuery("input[name=show_sort_images]:checked").val() + '"';
                tagtext += ' search_box_width="' + jQuery("#search_box_width").val() + '"';
                tagtext += ' compuct_album_column_number="' + jQuery("#compuct_album_column_number").val() + '"';
                tagtext += ' compuct_albums_per_page="' + jQuery("#compuct_albums_per_page").val() + '"';
                tagtext += ' compuct_album_title="' + jQuery("input[name=compuct_album_title]:checked").val() + '"';
                tagtext += ' compuct_album_view_type="' + jQuery("input[name=compuct_album_view_type]:checked").val() + '"';
                tagtext += ' compuct_album_mosaic_hor_ver="' + jQuery("input[name=compuct_album_mosaic_hor_ver]:checked").val() + '"';
                tagtext += ' compuct_album_resizable_mosaic="' + jQuery("input[name=compuct_album_resizable_mosaic]:checked").val() + '"';
                tagtext += ' compuct_album_mosaic_total_width="' + jQuery("#compuct_album_mosaic_total_width").val() + '"';
                tagtext += ' compuct_album_thumb_width="' + jQuery("#compuct_album_thumb_width").val() + '"';
                tagtext += ' compuct_album_thumb_height="' + jQuery("#compuct_album_thumb_height").val() + '"';
                tagtext += ' compuct_album_image_column_number="' + jQuery("#compuct_album_image_column_number").val() + '"';
                tagtext += ' compuct_album_images_per_page="' + jQuery("#compuct_album_images_per_page").val() + '"';
                tagtext += ' compuct_album_image_title="' + jQuery("input[name=compuct_album_image_title]:checked").val() + '"';
                tagtext += ' compuct_album_image_thumb_width="' + jQuery("#compuct_album_image_thumb_width").val() + '"';
                tagtext += ' compuct_album_image_thumb_height="' + jQuery("#compuct_album_image_thumb_height").val() + '"';
                tagtext += ' compuct_album_enable_page="' + jQuery("input[name=compuct_album_enable_page]:checked").val() + '"';
                tagtext += ' compuct_album_load_more_image_count="' + jQuery("#compuct_album_load_more_image_count").val() + '"';
                tagtext += ' compuct_albums_per_page_load_more="' + jQuery("#compuct_albums_per_page_load_more").val() + '"';
                tagtext += ' show_tag_box="' + jQuery("input[name=show_tag_box]:checked").val() + '"';
								title = ' gal_title="' + jQuery.trim(jQuery('#album option:selected').text().replace("'", "").replace('"', '')) + '"';
                tagtext += ' ecommerce_icon="' + jQuery("input[name=ecommerce_icon]:checked").val() + '"';                
                break;

              }
              case 'album_extended_preview': {
                tagtext += ' album_id="' + jQuery("#album").val() + '"';
                tagtext += ' sort_by="' + jQuery("#sort_by").val() + '"';
                tagtext += ' order_by="' + jQuery("input[name=order_by]:checked").val() + '"';
                tagtext += ' show_search_box="' + jQuery("input[name=show_search_box]:checked").val() + '"';
								tagtext += ' show_sort_images="' + jQuery("input[name=show_sort_images]:checked").val() + '"';
                tagtext += ' search_box_width="' + jQuery("#search_box_width").val() + '"';
                tagtext += ' extended_albums_per_page="' + jQuery("#extended_albums_per_page").val() + '"';
                tagtext += ' extended_album_height="' + jQuery("#extended_album_height").val() + '"';
                tagtext += ' extended_album_description_enable="' + jQuery("input[name=extended_album_description_enable]:checked").val() + '"';
                tagtext += ' extended_album_view_type="' + jQuery("input[name=extended_album_view_type]:checked").val() + '"';
                tagtext += ' extended_album_mosaic_hor_ver="' + jQuery("input[name=extended_album_mosaic_hor_ver]:checked").val() + '"';
                tagtext += ' extended_album_resizable_mosaic="' + jQuery("input[name=extended_album_resizable_mosaic]:checked").val() + '"';
                tagtext += ' extended_album_mosaic_total_width="' + jQuery("#extended_album_mosaic_total_width").val() + '"';
                tagtext += ' extended_album_thumb_width="' + jQuery("#extended_album_thumb_width").val() + '"';
                tagtext += ' extended_album_thumb_height="' + jQuery("#extended_album_thumb_height").val() + '"';
                tagtext += ' extended_album_image_column_number="' + jQuery("#extended_album_image_column_number").val() + '"';
                tagtext += ' extended_album_images_per_page="' + jQuery("#extended_album_images_per_page").val() + '"';
                tagtext += ' extended_album_image_title="' + jQuery("input[name=extended_album_image_title]:checked").val() + '"';
                tagtext += ' extended_album_image_thumb_width="' + jQuery("#extended_album_image_thumb_width").val() + '"';
                tagtext += ' extended_album_image_thumb_height="' + jQuery("#extended_album_image_thumb_height").val() + '"';
                tagtext += ' extended_album_enable_page="' + jQuery("input[name=extended_album_enable_page]:checked").val() + '"';
                tagtext += ' extended_album_load_more_image_count="' + jQuery("#extended_album_load_more_image_count").val() + '"';
                tagtext += ' extended_albums_per_page_load_more="' + jQuery("#extended_albums_per_page_load_more").val() + '"';
                tagtext += ' show_tag_box="' + jQuery("input[name=show_tag_box]:checked").val() + '"';
								title = ' gal_title="' + jQuery.trim(jQuery('#album option:selected').text().replace("'", "").replace('"', '')) + '"';
                tagtext += ' ecommerce_icon="' + jQuery("input[name=ecommerce_icon]:checked").val() + '"';                
                 
                break;

              }
							case 'album_masonry_preview' : {
								tagtext += ' album_id="' + jQuery("#album").val() + '"';
                tagtext += ' sort_by="' + jQuery("#sort_by").val() + '"';
                tagtext += ' order_by="' + jQuery("input[name=order_by]:checked").val() + '"';
                tagtext += ' show_search_box="' + jQuery("input[name=show_search_box]:checked").val() + '"';
								tagtext += ' show_sort_images="' + jQuery("input[name=show_sort_images]:checked").val() + '"';
                tagtext += ' search_box_width="' + jQuery("#search_box_width").val() + '"';
                tagtext += ' masonry_album_column_number="' + jQuery("#masonry_album_column_number").val() + '"';
                tagtext += ' masonry_albums_per_page="' + jQuery("#masonry_albums_per_page").val() + '"';
                tagtext += ' masonry_album_title="' + jQuery("input[name=masonry_album_title]:checked").val() + '"';
                tagtext += ' masonry_album_thumb_width="' + jQuery("#masonry_album_thumb_width").val() + '"';
                tagtext += ' masonry_album_thumb_height="' + jQuery("#masonry_album_thumb_height").val() + '"';
                tagtext += ' masonry_album_image_column_number="' + jQuery("#masonry_album_image_column_number").val() + '"';
                tagtext += ' masonry_album_images_per_page="' + jQuery("#masonry_album_images_per_page").val() + '"';
                tagtext += ' masonry_album_image_title="' + jQuery("input[name=masonry_album_image_title]:checked").val() + '"';
                tagtext += ' masonry_album_image_thumb_width="' + jQuery("#masonry_album_image_thumb_width").val() + '"';
                tagtext += ' masonry_album_image_thumb_height="' + jQuery("#masonry_album_image_thumb_height").val() + '"';
                tagtext += ' masonry_album_enable_page="' + jQuery("input[name=masonry_album_enable_page]:checked").val() + '"';
                tagtext += ' masonry_album_load_more_image_count="' + jQuery("#masonry_album_load_more_image_count").val() + '"';
                tagtext += ' masonry_albums_per_page_load_more="' + jQuery("#masonry_albums_per_page_load_more").val() + '"';
                tagtext += ' show_tag_box="' + jQuery("input[name=show_tag_box]:checked").val() + '"';
								title = ' gal_title="' + jQuery.trim(jQuery('#album option:selected').text().replace("'", "").replace('"', '')) + '"';
                tagtext += ' ecommerce_icon="' + jQuery("input[name=ecommerce_icon]:checked").val() + '"';                
                 
                break;
							}
              case 'blog_style': {
                tagtext += ' gallery_id="' + jQuery("#gallery").val() + '"';
                tagtext += ' sort_by="' + jQuery("#sort_by").val() + '"';
                tagtext += ' order_by="' + jQuery("input[name=order_by]:checked").val() + '"';
                tagtext += ' show_search_box="' + jQuery("input[name=show_search_box]:checked").val() + '"';
								tagtext += ' show_sort_images="' + jQuery("input[name=show_sort_images]:checked").val() + '"';
                tagtext += ' search_box_width="' + jQuery("#search_box_width").val() + '"';
                tagtext += ' blog_style_width="' + jQuery("#blog_style_width").val() + '"';
                tagtext += ' blog_style_title_enable="' + jQuery("input[name=blog_style_title_enable]:checked").val() + '"';
                tagtext += ' blog_style_images_per_page="' + jQuery("#blog_style_images_per_page").val() + '"';
                tagtext += ' blog_style_enable_page="' + jQuery("input[name=blog_style_enable_page]:checked").val() + '"';
                tagtext += ' blog_style_load_more_image_count="' + jQuery("#blog_style_load_more_image_count").val() + '"';
                tagtext += ' show_tag_box="' + jQuery("input[name=show_tag_box]:checked").val() + '"';
								title = ' gal_title="' + jQuery.trim(jQuery('#gallery option:selected').text().replace("'", "").replace('"', '')) + '"';
                break;
              }
              case 'carousel': {
                tagtext += ' gallery_id="' + jQuery("#gallery").val() + '"';
                tagtext += ' sort_by="' + jQuery("#sort_by").val() + '"';
                tagtext += ' order_by="' + jQuery("input[name=order_by]:checked").val() + '"';              
                tagtext += ' carousel_interval="' + jQuery("#carousel_interval").val() + '"';
                tagtext += ' carousel_width="' + jQuery("#carousel_width").val() + '"';
                tagtext += ' carousel_height="' + jQuery("#carousel_height").val() + '"';
                tagtext += ' carousel_image_par="' + jQuery("#carousel_image_par").val() + '"';  
                tagtext += ' enable_carousel_autoplay="' + jQuery("input[name=enable_carousel_autoplay]:checked").val() + '"';
                tagtext += ' enable_carousel_title="' + jQuery("input[name=enable_carousel_title]:checked").val() + '"'; 
                tagtext += ' carousel_ecommerce_icon="' + jQuery("input[name=carousel_ecommerce_icon]:checked").val() + '"'; 
                tagtext += ' carousel_r_width="' + jQuery("#carousel_r_width").val() + '"';
                tagtext += ' carousel_image_column_number="' + jQuery("#carousel_image_column_number").val() + '"';
                tagtext += ' carousel_fit_containerWidth="' + jQuery("input[name=carousel_fit_containerWidth]:checked").val() + '"';
                tagtext += ' carousel_prev_next_butt="' + jQuery("input[name=carousel_prev_next_butt]:checked").val() + '"';
                tagtext += ' carousel_play_pause_butt="' + jQuery("input[name=carousel_play_pause_butt]:checked").val() + '"';
								title = ' gal_title="' + jQuery.trim(jQuery('#gallery option:selected').text().replace("'", "").replace('"', '')) + '"';
                break;

              }
			  
            }
            // Lightbox paramteres.
            if (gallery_type != 'slideshow' && gallery_type != 'carousel') {
              tagtext += ' thumb_click_action="' + jQuery("input[name=thumb_click_action]:checked").val() + '"';
              tagtext += ' thumb_link_target="' + jQuery("input[name=thumb_link_target]:checked").val() + '"';
              tagtext += ' popup_fullscreen="' + jQuery("input[name=popup_fullscreen]:checked").val() + '"';
              tagtext += ' popup_autoplay="' + jQuery("input[name=popup_autoplay]:checked").val() + '"';			  
              tagtext += ' popup_width="' + jQuery("#popup_width").val() + '"';
              tagtext += ' popup_height="' + jQuery("#popup_height").val() + '"';
              tagtext += ' popup_effect="' + jQuery("#popup_effect").val() + '"';
              tagtext += ' popup_interval="' + jQuery("#popup_interval").val() + '"';
              tagtext += ' popup_enable_filmstrip="' + jQuery("input[name=popup_enable_filmstrip]:checked").val() + '"';
              tagtext += ' popup_filmstrip_height="' + jQuery("#popup_filmstrip_height").val() + '"';
              tagtext += ' popup_enable_ctrl_btn="' + jQuery("input[name=popup_enable_ctrl_btn]:checked").val() + '"';
              tagtext += ' popup_enable_fullscreen="' + jQuery("input[name=popup_enable_fullscreen]:checked").val() + '"';
              tagtext += ' popup_enable_info="' + jQuery("input[name=popup_enable_info]:checked").val() + '"';
              tagtext += ' popup_info_always_show="' + jQuery("input[name=popup_info_always_show]:checked").val() + '"';
              tagtext += ' popup_info_full_width="' + jQuery("input[name=popup_info_full_width]:checked").val() + '"';
              tagtext += ' popup_enable_rate="' + jQuery("input[name=popup_enable_rate]:checked").val() + '"';
              tagtext += ' popup_enable_comment="' + jQuery("input[name=popup_enable_comment]:checked").val() + '"';
              tagtext += ' popup_hit_counter="' + jQuery("input[name=popup_hit_counter]:checked").val() + '"';
              tagtext += ' popup_enable_facebook="' + jQuery("input[name=popup_enable_facebook]:checked").val() + '"';
              tagtext += ' popup_enable_twitter="' + jQuery("input[name=popup_enable_twitter]:checked").val() + '"';
              tagtext += ' popup_enable_google="' + jQuery("input[name=popup_enable_google]:checked").val() + '"';
	          tagtext += ' popup_enable_ecommerce="' + jQuery("input[name=popup_enable_ecommerce]:checked").val() + '"';
			 
              tagtext += ' popup_enable_pinterest="' + jQuery("input[name=popup_enable_pinterest]:checked").val() + '"';
              tagtext += ' popup_enable_tumblr="' + jQuery("input[name=popup_enable_tumblr]:checked").val() + '"';
              tagtext += ' show_tag_box="' + jQuery("input[name=show_tag_box]:checked").val() + '"';
            }
            // Watermark parameters.
            tagtext += ' watermark_type="' + jQuery("input[name=watermark_type]:checked").val() + '"';
            tagtext += ' watermark_link="' + (jQuery("#watermark_link").val()) + '"';
            if (jQuery("input[name=watermark_type]:checked").val() == 'text') {
              tagtext += ' watermark_text="' + jQuery("#watermark_text").val() + '"';
              tagtext += ' watermark_font_size="' + jQuery("#watermark_font_size").val() + '"';
              tagtext += ' watermark_font="' + jQuery("#watermark_font").val() + '"';
              tagtext += ' watermark_color="' + jQuery("#watermark_color").val() + '"';
              tagtext += ' watermark_opacity="' + jQuery("#watermark_opacity").val() + '"';
              tagtext += ' watermark_position="' + jQuery("input[name=watermark_position]:checked").val() + '"';
            }
            else if (jQuery("input[name=watermark_type]:checked").val() == 'image') {
              tagtext += ' watermark_url="' + jQuery("#watermark_url").val() + '"';
              tagtext += ' watermark_width="' + jQuery("#watermark_width").val() + '"';
              tagtext += ' watermark_height="' + jQuery("#watermark_height").val() + '"';
              tagtext += ' watermark_opacity="' + jQuery("#watermark_opacity").val() + '"';
              tagtext += ' watermark_position="' + jQuery("input[name=watermark_position]:checked").val() + '"';
            }
            short_code += ' id="' + shortcode_id + '"' + title + ']';
            var short_id = ' id="' + shortcode_id + '"' + title;
            short_code = short_code.replace(/\[BWGE_Gallery_Ecommerce([^\]]*)\]/g, function(d, c) {
              return "<img src='<?php echo WD_BWGE_URL; ?>/images/bwge_shortcode.png' class='bwge_shortcode mceItem' title='BWGE_Gallery_Ecommerce" + short_id + "' />";
            });
            jQuery("#task").val("save");
            jQuery("#tagtext").val(tagtext);
            jQuery("#currrent_id").val(shortcode_id);
            jQuery("#title").val(title);
            jQuery("#bwge_insert").val((content && !bwge_insert) ? 0 : 1);
            jQuery("#bwge_shortcode_form").submit();
            <?php if (!$from_menu) { ?>
            if (window.tinymce.isIE && content) {
              // IE and Update.
              var all_content = tinyMCE.activeEditor.getContent();
              all_content = all_content.replace('<p></p><p>[BWGE_Gallery_Ecommerce', '<p>[BWGE_Gallery_Ecommerce');
              tinyMCE.activeEditor.setContent(all_content.replace(content, '[BWGE_Gallery_Ecommerce id="' + shortcode_id + '"' + title + ']'));
            }
            else {
              window.tinyMCE.execCommand('mceInsertContent', false, short_code);
            }
            tinyMCEPopup.editor.execCommand('mceRepaint');
            <?php } else { ?>
            jQuery("#bwge_shortcode").val('[BWGE_Gallery_Ecommerce id="' + shortcode_id + '"' + title + ']');
            <?php } ?>
          }
          jQuery(document).ready(function () {
            bwge_loadmore();
          });
          var bwge_image_thumb = '<?php echo addslashes(__('Image thumbnail dimensions:', 'bwge_back')); ?>';
          var bwge_image_thumb_width = '<?php echo addslashes(__('Image thumbnail width: ', 'bwge_back')); ?>';
          var bwge_max_column = '<?php echo addslashes(__('Max. number of image columns:', 'bwge_back')); ?>';
          var bwge_image_thumb_height = '<?php echo addslashes(__('Image thumbnail height:', 'bwge_back')); ?>';
          var bwge_number_of_image_rows = '<?php echo addslashes(__('Number of image rows:', 'bwge_back')); ?>';
        </script>
      </body>
    </html>
    <?php
    include_once (WD_BWGE_DIR .'/includes/bwge_pointers.php');
    new BWGE_pointers();   
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