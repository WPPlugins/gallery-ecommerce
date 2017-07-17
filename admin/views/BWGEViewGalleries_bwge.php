<?php

class BWGEViewGalleries_bwge {
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

    $rows_data = $this->model->get_rows_data();
    $page_nav = $this->model->page_nav();
    $search_value = ((isset($_POST['search_value'])) ? esc_html(stripslashes($_POST['search_value'])) : '');
    $search_select_value = ((isset($_POST['search_select_value'])) ? (int) $_POST['search_select_value'] : 0);
    $asc_or_desc = ((isset($_POST['asc_or_desc'])) ? esc_html(stripslashes($_POST['asc_or_desc'])) : 'asc');
    $order_by = (isset($_POST['order_by']) ? esc_html(stripslashes($_POST['order_by'])) : 'order');
    $order_class = 'manage-column column-title sorted ' . $asc_or_desc;
    $ids_string = '';
    $per_page = $this->model->per_page();
    $pager = 0;
    ?>

    <div id="draganddrop" class="updated" style="display:none;"><strong><p><?php echo __('Changes made in this table should be saved.', 'bwge_back'); ?></p></strong></div>
    <div class="bwge">
      <div style="font-size: 14px; font-weight: bold;">
        <?php echo __('This section allows you to create, edit and delete galleries.', 'bwge_back'); ?>
        <a style="color: #00A0D2; text-decoration: none;" target="_blank" href="https://galleryecommerce.com/gallery-set-up/creating-editing-galleries/"><?php echo __('Read More in User Manual', 'bwge_back'); ?></a>
      </div>
      <form class="bwge_form" id="galleries_form" method="post" action="admin.php?page=galleries_bwge" style="width:99%;">
      <?php wp_nonce_field( 'galleries_bwge', 'bwge_nonce' ); ?>
        <!--<span class="gallery-icon"></span>-->
        <h2>
          <?php echo __('Galleries', 'bwge_back'); ?>
          <a id="galleries_id" href="" class="wd-btn wd-btn-primary wd-btn-icon wd-btn-add" onclick="bwge_spider_set_input_value('task', 'add');
                                                 bwge_spider_form_submit(event, 'galleries_form')"><?php echo __('Add new', 'bwge_back'); ?></a>
        </h2>

        <div class="wd-clear">
					<div class="wd-left">
						<?php
                BWGELibrary::search(__('Name','bwge_back'), $search_value, 'galleries_form');
						?>
					</div>
          <div class="wd-right" style="text-align:right;margin-bottom:15px ;">
            <span class="wd-btn wd-btn-primary bwge_non_selectable" onclick="bwge_spider_check_all_items()">
              <input type="checkbox" id="check_all_items" name="check_all_items" onclick="bwge_spider_check_all_items_checkbox()" style="margin: 0; vertical-align: middle;" />
              <span style="vertical-align: middle;"><?php echo __('Select All', 'bwge_back'); ?></span>
            </span>
            <input id="show_hide_weights"  class="wd-btn wd-btn-primary" type="button" onclick="bwge_spider_show_hide_weights();return false;" value="<?php echo __('Hide order column', 'bwge_back'); ?>" />
            <input class="wd-btn wd-btn-primary" type="submit" onclick="bwge_spider_set_input_value('task', 'save_order')" value="<?php echo __('Save Order', 'bwge_back'); ?>" />
            <input class="wd-btn wd-btn-primary wd-btn-icon wd-btn-publish" type="submit" onclick="bwge_spider_set_input_value('task', 'publish_all')" value="<?php echo __('Publish', 'bwge_back'); ?>" />
            <input class="wd-btn wd-btn-primary wd-btn-icon wd-btn-unpublish" type="submit" onclick="bwge_spider_set_input_value('task', 'unpublish_all')" value="<?php echo __('Unpublish', 'bwge_back'); ?>" />
            <input class="wd-btn wd-btn-primary-red wd-btn-icon wd-btn-delete" type="submit" onclick="if (confirm('<?php echo addslashes(__('Do you want to delete selected items?', 'bwge_back')); ?>')) {
                                                           bwge_spider_set_input_value('task', 'delete_all');
                                                         } else {
                                                           return false;
                                                         }" value="<?php echo __('Delete', 'bwge_back'); ?>" />
          </div>
        </div>
        <div class="tablenav top">
          <?php
          BWGELibrary::html_page_nav($page_nav['total'], $pager++, $page_nav['limit'], 'galleries_form', $per_page);
          ?>
        </div>
        <table class="wp-list-table widefat fixed pages bwge_list_table">
          <thead>
            <tr class="bwge_alternate">
              <th class="table_small_col"></th>
              <th class="manage-column column-cb check-column table_small_col"><input id="check_all" type="checkbox" onclick="bwge_spider_check_all(this)" style="margin:0;" /></th>
              <th class="table_small_col <?php if ($order_by == 'id') {echo $order_class;} ?>">
                <a onclick="bwge_spider_set_input_value('task', '');
                            bwge_spider_set_input_value('order_by', 'id');
                            bwge_spider_set_input_value('asc_or_desc', '<?php echo ((isset($_POST['asc_or_desc']) && isset($_POST['order_by']) && (esc_html(stripslashes($_POST['order_by'])) == 'id') && esc_html(stripslashes($_POST['asc_or_desc'])) == 'asc') ? 'desc' : 'asc'); ?>');
                            bwge_spider_form_submit(event, 'galleries_form')" href="">
                  <span>ID</span><span class="sorting-indicator"></span>
                </a>
              </th>
              <th class="table_extra_large_col"><?php echo __('Thumbnail', 'bwge_back'); ?></th>
              <th class="<?php if ($order_by == 'name') {echo $order_class;} ?>">
                <a onclick="bwge_spider_set_input_value('task', '');
                            bwge_spider_set_input_value('order_by', 'name');
                            bwge_spider_set_input_value('asc_or_desc', '<?php echo ((isset($_POST['asc_or_desc']) && isset($_POST['order_by']) && (esc_html(stripslashes($_POST['order_by'])) == 'name') && esc_html(stripslashes($_POST['asc_or_desc'])) == 'asc') ? 'desc' : 'asc'); ?>');
                            bwge_spider_form_submit(event, 'galleries_form')" href="">
                  <span><?php echo __('Name', 'bwge_back'); ?></span><span class="sorting-indicator"></span>
                </a>
              </th>
              <th class="<?php if ($order_by == 'slug') {echo $order_class;} ?>">
                <a onclick="bwge_spider_set_input_value('task', '');
                            bwge_spider_set_input_value('order_by', 'slug');
                            bwge_spider_set_input_value('asc_or_desc', '<?php echo ((isset($_POST['asc_or_desc']) && isset($_POST['order_by']) && (esc_html(stripslashes($_POST['order_by'])) == 'slug') && esc_html(stripslashes($_POST['asc_or_desc'])) == 'asc') ? 'desc' : 'asc'); ?>');
                            bwge_spider_form_submit(event, 'galleries_form')" href="">
                  <span><?php echo __('Slug', 'bwge_back'); ?></span><span class="sorting-indicator"></span>
                </a>
              </th>
              <th class="<?php if ($order_by == 'display_name') {echo $order_class;} ?>">
                <a onclick="bwge_spider_set_input_value('task', '');
                            bwge_spider_set_input_value('order_by', 'display_name');
                            bwge_spider_set_input_value('asc_or_desc', '<?php echo ((isset($_POST['asc_or_desc']) && isset($_POST['order_by']) && (esc_html(stripslashes($_POST['order_by'])) == 'display_name') && esc_html(stripslashes($_POST['asc_or_desc'])) == 'asc') ? 'desc' : 'asc'); ?>');
                            bwge_spider_form_submit(event, 'galleries_form')" href="">
                  <span><?php echo __('Author', 'bwge_back'); ?></span><span class="sorting-indicator"></span>
                </a>
              </th>
              <th class="table_large_col"><?php echo __('Images count', 'bwge_back'); ?></th>
              <th id="th_order" class="table_medium_col <?php if ($order_by == 'order') {echo $order_class;} ?>">
                <a onclick="bwge_spider_set_input_value('task', '');
                            bwge_spider_set_input_value('order_by', 'order');
                            bwge_spider_set_input_value('asc_or_desc', '<?php echo ((isset($_POST['asc_or_desc']) && isset($_POST['order_by']) && (esc_html(stripslashes($_POST['order_by'])) == 'order') && esc_html(stripslashes($_POST['asc_or_desc'])) == 'asc') ? 'desc' : 'asc'); ?>');
                            bwge_spider_form_submit(event, 'galleries_form')" href="">
                  <span><?php echo __('Order', 'bwge_back'); ?></span><span class="sorting-indicator"></span>
                </a>
              </th>
              <th class="table_big_col <?php if ($order_by == 'published') {echo $order_class;} ?>">
                <a onclick="bwge_spider_set_input_value('task', '');
                            bwge_spider_set_input_value('order_by', 'published');
                            bwge_spider_set_input_value('asc_or_desc', '<?php echo ((isset($_POST['asc_or_desc']) && isset($_POST['order_by']) && (esc_html(stripslashes($_POST['order_by'])) == 'published') && esc_html(stripslashes($_POST['asc_or_desc'])) == 'asc') ? 'desc' : 'asc'); ?>');
                            bwge_spider_form_submit(event, 'galleries_form')" href="">
                  <span><?php echo __('Published', 'bwge_back'); ?></span><span class="sorting-indicator"></span>
                </a>
              </th>
              <th class="table_big_col"><?php echo __('Edit', 'bwge_back'); ?></th>
              <th class="table_big_col"><?php echo __('Delete', 'bwge_back'); ?></th>
            </tr>
          </thead>
          <tbody id="tbody_arr">
            <?php
            if ($rows_data) {
              $iterator = 0;
              foreach ($rows_data as $row_data) {

                $published = (($row_data->published) ? 'unpublish' : 'publish');
                $images_count = $this->model->get_images_count($row_data->id);
                $alternate = $iterator%2 == 0 ? '' : 'class="bwge_alternate"';
                $published_image = (($row_data->published) ? 'publish-blue' : 'unpublish-blue');
                
                if ($row_data->preview_image == '') {
                  $preview_image = WD_BWGE_URL . '/images/no-image.png';
                }
                else {
                  $preview_image = site_url() . '/' . $WD_BWGE_UPLOAD_DIR . $row_data->preview_image;
                }
                ?>
                <tr id="tr_<?php echo $row_data->id; ?>" <?php echo $alternate; ?>>
                  <td class="connectedSortable table_small_col"><div title="Drag to re-order"class="handle" style="margin:5px auto 0 auto;"></div></td>
                  <td class="table_small_col check-column"><input id="check_<?php echo $row_data->id; ?>" name="check_<?php echo $row_data->id; ?>" onclick="bwge_spider_check_all(this)" type="checkbox" /></td>
                  <td class="table_small_col"><?php echo $row_data->id; ?></td>
                  <td class="table_extra_large_col">
                    <img title="<?php echo $row_data->name; ?>" style="border: 1px solid #CCCCCC; max-width:60px; max-height:60px;" src="<?php echo $preview_image . '?date=' . date('Y-m-y H:i:s'); ?>">
                  </td>
                  <td><a onclick="bwge_spider_set_input_value('task', 'edit');
                                  bwge_spider_set_input_value('page_number', '1');
                                  bwge_spider_set_input_value('search_value', '');
                                  bwge_spider_set_input_value('search_or_not', '');
                                  bwge_spider_set_input_value('asc_or_desc', 'asc');
                                  bwge_spider_set_input_value('order_by', 'order');
                                  bwge_spider_set_input_value('current_id', '<?php echo $row_data->id; ?>');
                                  bwge_spider_form_submit(event, 'galleries_form')" href="" title="Edit"><?php echo $row_data->name; ?></a></td>
                  <td><?php echo $row_data->slug; ?></td>
                  <td><?php echo get_userdata($row_data->author)->display_name; ?></td>
                  <td class="table_large_col"><?php echo $images_count; ?></td>
                  <td class="bwge_spider_order table_medium_col"><input id="order_input_<?php echo $row_data->id; ?>" name="order_input_<?php echo $row_data->id; ?>" type="text" style="width: 49px;" value="<?php echo $row_data->order; ?>" /></td>
                  <td class="table_big_col"><a onclick="bwge_spider_set_input_value('task', '<?php echo $published; ?>');bwge_spider_set_input_value('current_id', '<?php echo $row_data->id; ?>');bwge_spider_form_submit(event, 'galleries_form')" href=""><img src="<?php echo WD_BWGE_URL . '/images/css/' . $published_image . '.png'; ?>"></img></a></td>
                  <td class="table_big_col"><a onclick="bwge_spider_set_input_value('task', 'edit');
                                                        bwge_spider_set_input_value('page_number', '1');
                                                        bwge_spider_set_input_value('search_value', '');
                                                        bwge_spider_set_input_value('search_or_not', '');
                                                        bwge_spider_set_input_value('asc_or_desc', 'asc');
                                                        bwge_spider_set_input_value('order_by', 'order');
                                                        bwge_spider_set_input_value('current_id', '<?php echo $row_data->id; ?>');
                                                        bwge_spider_form_submit(event, 'galleries_form')" href=""><?php echo __('Edit', 'bwge_back'); ?></a></td>
                  <td class="table_big_col"><a onclick = "if (confirm('<?php echo addslashes(__('Do you want to delete selected 	items?', 'bwge_back')); ?>')) {
                                                          bwge_spider_set_input_value('task', 'delete');
                                                        bwge_spider_set_input_value('current_id', '<?php echo $row_data->id; ?>');
                                                        bwge_spider_form_submit(event, 'galleries_form')
                                                         } else {
                                                           return false;
                                                         }"
				   href=""><?php echo __('Delete', 'bwge_back'); ?></a></td>
                </tr>
                <?php
                $ids_string .= $row_data->id . ',';
                $iterator++;
              }
            }
            ?>
          </tbody>
        </table>
        <div class="tablenav bottom">
          <?php
          BWGELibrary::html_page_nav($page_nav['total'], $pager++, $page_nav['limit'], 'galleries_form', $per_page);		
          ?>
        </div>
        <input id="task" name="task" type="hidden" value="" />
        <input id="current_id" name="current_id" type="hidden" value="" />
        <input id="ids_string" name="ids_string" type="hidden" value="<?php echo $ids_string; ?>" />
        <input id="asc_or_desc" name="asc_or_desc" type="hidden" value="asc" />
        <input id="order_by" name="order_by" type="hidden" value="<?php echo $order_by; ?>" />
        <script>
          window.onload = bwge_spider_show_hide_weights;
        </script>
      </form>
    </div>
    <?php
  }

  public function edit($id) {
    global $WD_BWGE_UPLOAD_DIR;
    global $wd_bwge_fb;
    $row = $this->model->get_row_data($id);
    $option_row = $this->model->get_option_row_data();
    $page_title = (($id != 0) ? __('Edit gallery','bwge_back') ." ". $row->name : __('Create new gallery','bwge_back'));
    $per_page = $this->model->per_page();
    $instagram_post_gallery = $row->gallery_type == 'instagram_post' ? true :false;
    $facebook_post_gallery = (!$instagram_post_gallery) ? ($row->gallery_type == 'facebook_post' ? true :false) : false;
    $gallery_type = ($row->gallery_type == 'instagram' || $row->gallery_type == 'instagram_post') ? 'instagram' : (($row->gallery_type == 'facebook_post' || $row->gallery_type == 'facebook') ? 'facebook' : '');
    $gallery_source = $row->gallery_source;
    $update_flag = $row->update_flag;
    $autogallery_image_number = $row->autogallery_image_number;
    $images_count = $this->model->get_images_count($id);

    ?>
    <div id="message_div" class="updated" style="display: none;"></div>

    <script>
      function bwge_spider_set_href(a, number, type) {
        var image_url = document.getElementById("image_url_" + number).value;
        var thumb_url = document.getElementById("thumb_url_" + number).value;
        <?php 

        $query_url = wp_nonce_url( admin_url('admin-ajax.php'), 'editThumb_bwge', 'bwge_nonce' );
         ?>
        a.href='<?php echo add_query_arg(array('action' => 'editThumb_bwge', 'width' => '800', 'height' => '500'), $query_url); ?>&type=' + type + '&image_id=' + number + '&image_url=' + image_url + '&thumb_url=' + thumb_url + '&TB_iframe=1';
      }
      function bwge_add_preview_image(files) {
        document.getElementById("preview_image").value = files[0]['thumb_url'];
        document.getElementById("button_preview_image").style.display = "none";
        document.getElementById("delete_preview_image").style.display = "inline-block";
        if (document.getElementById("img_preview_image")) {
          document.getElementById("img_preview_image").src = files[0]['reliative_url'];
          document.getElementById("img_preview_image").style.display = "inline-block";
        }
      }
      var j_int = 0;
      var bwge_j = 'pr_' + j_int;
      function bwge_add_image(files) {
        jQuery(document).trigger("bwgeImagesAdded");
        var tbody = document.getElementById('tbody_arr');
        for (var i in files) {
          
          var is_direct_url = files[i]['filetype'].indexOf("DIRECT_URL_") > -1 ? true : false;
          var is_embed = files[i]['filetype'].indexOf("EMBED_") > -1 ? true : false;
          //add query args to thickbox url if embed is facebook post 
          var is_facebook_post = files[i]['filetype'].indexOf("_FACEBOOK_POST") > -1 ? 1 : 0;
          var fb_post_url = (is_facebook_post) ? files[i]['filename'] : '';
          var tr = document.createElement('tr');
          tr.setAttribute('id', "tr_" + bwge_j);
          if (tbody.firstChild) {
            tbody.insertBefore(tr, tbody.firstChild);
          }
          else {
            tbody.appendChild(tr);
          }
          // Handle TD.
          var td_handle = document.createElement('td');
          td_handle.setAttribute('class', "connectedSortable table_small_col");
          td_handle.setAttribute('title', "Drag to re-order");
          tr.appendChild(td_handle);
          var div_handle = document.createElement('div');
          div_handle.setAttribute('class', "handle connectedSortable");
          div_handle.setAttribute('style', "margin: 5px auto 0px;");
          td_handle.appendChild(div_handle);
          // Checkbox TD.
          var td_checkbox = document.createElement('td');
          td_checkbox.setAttribute('class', "table_small_col check-column");
          td_checkbox.setAttribute('onclick', "bwge_spider_check_all(this)");
          tr.appendChild(td_checkbox);
          var input_checkbox = document.createElement('input');
          input_checkbox.setAttribute('id', "check_" + bwge_j);
          input_checkbox.setAttribute('name', "check_" + bwge_j);
          input_checkbox.setAttribute('type', "checkbox");
          td_checkbox.appendChild(input_checkbox);
          // Numbering TD.
          var td_numbering = document.createElement('td');
          td_numbering.setAttribute('class', "table_small_col");
          td_numbering.innerHTML = "";
          tr.appendChild(td_numbering);
          // Thumb TD.
          var td_thumb = document.createElement('td');
          td_thumb.setAttribute('class', "table_extra_large_col");
          tr.appendChild(td_thumb);
          var a_thumb = document.createElement('a');
          a_thumb.setAttribute('class', "thickbox thickbox-preview");
          <?php 

          $query_url = wp_nonce_url( admin_url('admin-ajax.php'), 'editThumb_bwge', 'bwge_nonce' );
          ?>
          a_thumb.setAttribute('href', "<?php echo add_query_arg(array('action' => 'editThumb_bwge', 'type' => 'display'/*thumb_display*/, 'width' => '650', 'height' => '500'), $query_url); ?>&FACEBOOK_POST="+is_facebook_post+"&fb_post_url="+fb_post_url+"&image_id=" + bwge_j + "&TB_iframe=1");
          a_thumb.setAttribute('title', files[i]['name']);
          td_thumb.appendChild(a_thumb);
          var img_thumb = document.createElement('img');
          img_thumb.setAttribute('id', "image_thumb_" + bwge_j);
          img_thumb.setAttribute('class', "thumb");
          img_thumb.setAttribute('src', files[i]['thumb']);
          a_thumb.appendChild(img_thumb);
          // Filename TD.
          var td_filename = document.createElement('td');
          td_filename.setAttribute('class', "table_extra_large_col");
          tr.appendChild(td_filename);
          var div_filename = document.createElement('div');
          div_filename.setAttribute('class', "filename");
          div_filename.setAttribute('id', "filename_" + bwge_j);
          td_filename.appendChild(div_filename);
          var strong_filename = document.createElement('strong');
          div_filename.appendChild(strong_filename);
          var a_filename = document.createElement('a');
          <?php 

          $query_url = wp_nonce_url( admin_url('admin-ajax.php'), 'editThumb_bwge', 'bwge_nonce' );
          ?>
          a_filename.setAttribute('href', "<?php echo add_query_arg(array('action' => 'editThumb_bwge', 'type' => 'display', 'width' => '800', 'height' => '500'), $query_url); ?>&FACEBOOK_POST="+is_facebook_post+"&fb_post_url="+fb_post_url+"&image_id=" + bwge_j + "&TB_iframe=1");
          a_filename.setAttribute('class', "bwge_spider_word_wrap thickbox thickbox-preview");
          a_filename.setAttribute('title', files[i]['filename']);
          a_filename.innerHTML = files[i]['filename'];
          strong_filename.appendChild(a_filename);
          var div_date_modified = document.createElement('div');
          div_date_modified.setAttribute('class', "fileDescription");
          div_date_modified.setAttribute('title', "Date modified");
          div_date_modified.setAttribute('id', "date_modified_" + bwge_j);
          div_date_modified.innerHTML = files[i]['date_modified'];
          td_filename.appendChild(div_date_modified);
          var div_fileresolution = document.createElement('div');
          div_fileresolution.setAttribute('class', "fileDescription");
          div_fileresolution.setAttribute('title', "Image Resolution");
          div_fileresolution.setAttribute('id', "fileresolution" + bwge_j);
          div_fileresolution.innerHTML = files[i]['resolution'];
          td_filename.appendChild(div_fileresolution);
          var div_filesize = document.createElement('div');
          div_filesize.setAttribute('class', "fileDescription");
          div_filesize.setAttribute('title', "Image size");
                    
          div_filesize.setAttribute('id', "filesize" + bwge_j);
          div_filesize.innerHTML = files[i]['size'];
          td_filename.appendChild(div_filesize);
          var div_filetype = document.createElement('div');
          div_filetype.setAttribute('class', "fileDescription");
          div_filetype.setAttribute('title', "Type");
          div_filetype.setAttribute('id', "filetype" + bwge_j);
          div_filetype.innerHTML = files[i]['filetype'];
          td_filename.appendChild(div_filetype);
          if ( !is_embed ) {
            var div_edit = document.createElement('div');
            td_filename.appendChild(div_edit);
            var span_edit_crop = document.createElement('span');
            span_edit_crop.setAttribute('class', "edit_thumb");
            div_edit.appendChild(span_edit_crop);
            var a_crop = document.createElement('a');
            a_crop.setAttribute('class', "thickbox thickbox-preview");
            a_crop.setAttribute('onclick', "bwge_spider_set_href(this, '" + bwge_j + "', 'crop');");
            a_crop.innerHTML = "<?php echo __('Crop', 'bwge_back'); ?>";
            span_edit_crop.appendChild(a_crop);
            div_edit.innerHTML += " | ";
            var span_edit_rotate = document.createElement('span');
            span_edit_rotate.setAttribute('class', "edit_thumb");
            div_edit.appendChild(span_edit_rotate);
            var a_rotate = document.createElement('a');
            a_rotate.setAttribute('class', "thickbox thickbox-preview");
            a_rotate.setAttribute('onclick', "bwge_spider_set_href(this, '" + bwge_j + "', 'rotate');");
            a_rotate.innerHTML = "<?php echo __('Edit', 'bwge_back'); ?>";
            span_edit_rotate.appendChild(a_rotate);
            div_edit.innerHTML += " | "
            var span_edit_recover = document.createElement('span');
            span_edit_recover.setAttribute('class', "edit_thumb");
            div_edit.appendChild(span_edit_recover);
            var a_recover = document.createElement('a');
            a_recover.setAttribute('onclick', 'if (confirm("<?php echo addslashes(__('Do you want to reset the image?', 'bwge_back')); ?>")) { bwge_spider_set_input_value("ajax_task", "recover"); bwge_spider_set_input_value("image_current_id", "' + bwge_j + '"); bwge_spider_ajax_save("galleries_form");} return false;');
            a_recover.innerHTML = "<?php echo __('Reset', 'bwge_back'); ?>";
            span_edit_recover.appendChild(a_recover);
          }
          var input_image_url = document.createElement('input');
          input_image_url.setAttribute('id', "image_url_" + bwge_j);
          input_image_url.setAttribute('name', "image_url_" + bwge_j);
          input_image_url.setAttribute('type', "hidden");
          input_image_url.setAttribute('value', files[i]['url']);
          td_filename.appendChild(input_image_url);
          var input_thumb_url = document.createElement('input');
          input_thumb_url.setAttribute('id', "thumb_url_" + bwge_j);
          input_thumb_url.setAttribute('name', "thumb_url_" + bwge_j);
          input_thumb_url.setAttribute('type', "hidden");
          input_thumb_url.setAttribute('value', files[i]['thumb_url']);
          td_filename.appendChild(input_thumb_url);
          var input_filename = document.createElement('input');
          input_filename.setAttribute('id', "input_filename_" + bwge_j);
          input_filename.setAttribute('name', "input_filename_" + bwge_j);
          input_filename.setAttribute('type', "hidden");
          input_filename.setAttribute('value', files[i]['filename']);
          td_filename.appendChild(input_filename);
          var input_date_modified = document.createElement('input');
          input_date_modified.setAttribute('id', "input_date_modified_" + bwge_j);
          input_date_modified.setAttribute('name', "input_date_modified_" + bwge_j);
          input_date_modified.setAttribute('type', "hidden");
          input_date_modified.setAttribute('value', files[i]['date_modified']);
          td_filename.appendChild(input_date_modified);
          var input_resolution = document.createElement('input');
          input_resolution.setAttribute('id', "input_resolution_" + bwge_j);
          input_resolution.setAttribute('name', "input_resolution_" + bwge_j);
          input_resolution.setAttribute('type', "hidden");
          input_resolution.setAttribute('value', files[i]['resolution']);
          td_filename.appendChild(input_resolution);
          var input_size = document.createElement('input');
          input_size.setAttribute('id', "input_size_" + bwge_j);
          input_size.setAttribute('name', "input_size_" + bwge_j);
          input_size.setAttribute('type', "hidden");
          input_size.setAttribute('value', files[i]['size']);
          td_filename.appendChild(input_size);
          var input_filetype = document.createElement('input');
          input_filetype.setAttribute('id', "input_filebwge_type" + bwge_j);
          input_filetype.setAttribute('name', "input_filebwge_type" + bwge_j);
          input_filetype.setAttribute('type', "hidden");
          input_filetype.setAttribute('value', files[i]['filetype']);
          td_filename.appendChild(input_filetype);
          // Alt/Title TD.
          var td_alt = document.createElement('td');
          td_alt.setAttribute('class', "table_extra_large_col");
          tr.appendChild(td_alt);
          var input_alt = document.createElement('input');
          input_alt.setAttribute('id', "image_alt_text_" + bwge_j);
          input_alt.setAttribute('name', "image_alt_text_" + bwge_j);
          input_alt.setAttribute('type', "text");
          input_alt.setAttribute('style', "width:150px;");
          if (is_embed && !is_direct_url) {
            input_alt.setAttribute('value', files[i]['name']);
          }
          else {/*uploaded images and direct URLs of images only*/
            input_alt.setAttribute('value', files[i]['filename']);
          }
          td_alt.appendChild(input_alt);

          <?php if ($option_row->thumb_click_action != 'open_lightbox') { ?>
          //Redirect url
          input_alt = document.createElement('input');
          input_alt.setAttribute('id', "redirect_url_" + bwge_j);
          input_alt.setAttribute('name', "redirect_url_" + bwge_j);
          input_alt.setAttribute('type', "text");
          input_alt.setAttribute('style', "width:150px;");
          td_alt.appendChild(input_alt);
          <?php } ?>
          // Description TD.
          var td_desc = document.createElement('td');
          td_desc.setAttribute('class', "table_extra_large_col");
          tr.appendChild(td_desc);
          var textarea_desc = document.createElement('textarea');
          textarea_desc.setAttribute('id', "image_description_" + bwge_j);
          textarea_desc.setAttribute('name', "image_description_" + bwge_j);
          textarea_desc.setAttribute('rows', "2");
          textarea_desc.setAttribute('cols', "20");
          textarea_desc.setAttribute('style', "resize:vertical;width:150px;");
          if (is_embed && !is_direct_url) {
            textarea_desc.innerHTML = files[i]['description'];
          }
          else if (<?php echo $option_row->read_metadata; ?>) {            
            textarea_desc.innerHTML = files[i]['credit'] ? 'Author: ' + files[i]['credit'] + '\n' : '';
            textarea_desc.innerHTML += ((files[i]['aperture'] != 0 && files[i]['aperture'] != '') ? 'Aperture: ' + files[i]['aperture'] + '\n' : '');
            textarea_desc.innerHTML += ((files[i]['camera'] != 0 && files[i]['camera'] != '') ? 'Camera: ' + files[i]['camera'] + '\n' : '');
            textarea_desc.innerHTML += ((files[i]['caption'] != 0 && files[i]['caption'] != '') ? 'Caption: ' + files[i]['caption'] + '\n' : '');
            textarea_desc.innerHTML += ((files[i]['iso'] != 0 && files[i]['iso'] != '') ? 'Iso: ' + files[i]['iso'] + '\n' : '');
            textarea_desc.innerHTML += ((files[i]['copyright'] != 0 && files[i]['copyright'] != '') ? 'Copyright: ' + files[i]['copyright'] + '\n' : '');
            textarea_desc.innerHTML += ((files[i]['orientation'] != 0 && files[i]['orientation'] != '') ? 'Orientation: ' + files[i]['orientation'] + '\n' : '');
          }
          td_desc.appendChild(textarea_desc);
          // Tag TD.
          var td_tag = document.createElement('td');
          td_tag.setAttribute('class', "table_extra_large_col");
          tr.appendChild(td_tag);
          var a_tag = document.createElement('a');
          a_tag.setAttribute('class', "wd-btn wd-btn-primary wd-btn-small thickbox thickbox-preview");
          <?php 
          $query_url = wp_nonce_url( admin_url('admin-ajax.php'), 'addTags_bwge', 'bwge_nonce' );
          ?>
          a_tag.setAttribute('href', "<?php echo add_query_arg(array('action' => 'addTags_bwge', 'width' => '650', 'height' => '500', 'bwge_items_per_page' => $per_page), $query_url); ?>&image_id=" + bwge_j + "&TB_iframe=1");
          a_tag.innerHTML = 'Add tag';
          td_tag.appendChild(a_tag);
          var div_tag = document.createElement('div');
          div_tag.setAttribute('class', "tags_div");
          div_tag.setAttribute('id', "tags_div_" + bwge_j);
          td_tag.appendChild(div_tag);
          var hidden_tag = document.createElement('input');
          hidden_tag.setAttribute('type', "hidden");
          hidden_tag.setAttribute('id', "tags_" + bwge_j);
          hidden_tag.setAttribute('name', "tags_" + bwge_j);
          hidden_tag.setAttribute('value', "");
          td_tag.appendChild(hidden_tag);
          // Order TD.
          var td_order = document.createElement('td');
          td_order.setAttribute('class', "bwge_spider_order table_medium_col");
          td_order.setAttribute('style', "display: none;");
          tr.appendChild(td_order);
          var input_order = document.createElement('input');
          input_order.setAttribute('id', "order_input_" + bwge_j);
          input_order.setAttribute('name', "order_input_" + bwge_j);
          input_order.setAttribute('type', "text");
          input_order.setAttribute('value', 0 - j_int);
          input_order.setAttribute('style', "width: 49px;");
          td_order.appendChild(input_order);
          // Publish TD.
          var td_publish = document.createElement('td');
          td_publish.setAttribute('class', "table_big_col");
          tr.appendChild(td_publish);
          var a_publish = document.createElement('a');
          a_publish.setAttribute('onclick', "bwge_spider_set_input_value('ajax_task', 'image_unpublish');bwge_spider_set_input_value('image_current_id', '" + bwge_j + "');bwge_spider_ajax_save('galleries_form');");
          td_publish.appendChild(a_publish);
          var img_publish = document.createElement('img');
          img_publish.setAttribute('src', "<?php echo WD_BWGE_URL . '/images/css/publish-blue.png'; ?>");
          a_publish.appendChild(img_publish);
          // Delete TD.
          var td_delete = document.createElement('td');
          td_delete.setAttribute('class', "table_big_col bwge_spider_delete_button");
          tr.appendChild(td_delete);
          var a_delete = document.createElement('a');
          a_delete.setAttribute('onclick', "bwge_spider_set_input_value('ajax_task', 'image_delete');bwge_spider_set_input_value('image_current_id', '" + bwge_j + "');bwge_spider_ajax_save('galleries_form');");
          a_delete.innerHTML = 'Delete';
          td_delete.appendChild(a_delete);
          document.getElementById("ids_string").value += bwge_j + ',';
          j_int++;
          bwge_j = 'pr_' + j_int;
        }
        jQuery("#show_hide_weights").val("Hide order column");
        bwge_spider_show_hide_weights();
      }
    </script>
    <script language="javascript" type="text/javascript" src="<?php echo WD_BWGE_URL . '/js/bwge_embed.js?ver='; ?><?php echo wd_bwge_version(); ?>"></script>
    <div class="bwge">
     <div style="font-size: 14px; font-weight: bold;">
        <?php echo __('This section allows you to add/edit gallery.', 'bwge_back'); ?>
        <a style="color: #00A0D2; text-decoration: none;" target="_blank" href="https://galleryecommerce.com/gallery-set-up/creating-editing-galleries/"><?php echo __('Read More in User Manual', 'bwge_back'); ?></a>
      </div>
      <form class="bwge_form" method="post" id="galleries_form" action="admin.php?page=galleries_bwge" style="width:99%;">
      <?php wp_nonce_field( 'galleries_bwge', 'bwge_nonce' ); ?>
        <!--<span class="gallery-icon"></span>-->
        <h2><?php echo $page_title; ?></h2>
        <p>
          <input  id="save_gall" class="wd-btn wd-btn-primary wd-btn-icon wd-btn-save" type="button" onclick="if (bwge_spider_check_required('name', 'Name') || bwge_check_instagram_gallery_input('<?php echo $option_row->instagram_access_token ?>') ) {return false;};
                                                       bwge_spider_set_input_value('page_number', '1');
                                                       bwge_spider_set_input_value('ajax_task', 'ajax_save');
                                                       bwge_spider_ajax_save('galleries_form');
                                                       bwge_spider_set_input_value('task', 'save');" value="<?php echo __('Save', 'bwge_back'); ?>" />
          <input class="wd-btn wd-btn-primary wd-btn-icon wd-btn-apply" type="button" onclick="if (bwge_spider_check_required('name', 'Name') || bwge_check_instagram_gallery_input('<?php echo $option_row->instagram_access_token ?>') ) {return false;};
                                                       bwge_spider_set_input_value('ajax_task', 'ajax_apply');
                                                       bwge_spider_ajax_save('galleries_form');" value="<?php echo __('Apply', 'bwge_back'); ?>" />
          <input class="wd-btn wd-btn-primary wd-btn-icon wd-btn-cancel" type="submit" onclick="bwge_spider_set_input_value('page_number', '1');
                                                       bwge_spider_set_input_value('task', 'cancel')" value="<?php echo __('Cancel', 'bwge_back'); ?>" />
        </p>
        <table style="clear:both;" class="bwge_edit_table">
          <tbody>
            <tr>
              <td class="bwge_spider_label_galleries"><label for="name"><?php echo __('Name:', 'bwge_back'); ?> <span style="color:#FF0000;">*</span> </label></td>
              <td><input type="text" id="name" name="name" value="<?php echo $row->name; ?>" size="39" /></td>
            </tr>
            <tr>
              <td class="bwge_spider_label_galleries"><label for="slug"><?php echo __('Slug:', 'bwge_back'); ?> </label></td>
              <td><input type="text" id="slug" name="slug" value="<?php echo $row->slug; ?>" size="39" /></td>
            </tr>

          </tbody>

          <tbody>      
            <tr>
              <td class="bwge_spider_label_galleries"><label for="description"><?php echo __('Description: ', 'bwge_back'); ?></label></td>
              <td>
                <div style="width:500px;">
                <?php
                if (user_can_richedit()) {
                  wp_editor($row->description, 'description', array('teeny' => FALSE, 'textarea_name' => 'description', 'media_buttons' => FALSE, 'textarea_rows' => 5));
                }
                else {
                ?>
                <textarea cols="36" rows="5" id="description" name="description" style="resize:vertical">
                  <?php echo $row->description; ?>
                </textarea>
                <?php
                }
                ?>
                </div>
              </td>
            </tr>
            <tr>
              <td class="bwge_spider_label_galleries"><label><?php echo __('Author:', 'bwge_back'); ?> </label></td>
              <td><?php echo get_userdata($row->author)->display_name; ?></td>
            </tr>
            <tr>
              <td class="bwge_spider_label_galleries"><label><?php echo __('Published: ', 'bwge_back'); ?></label></td>
              <td>
                <input type="radio" class="inputbox" id="published0" name="published" <?php echo (($row->published) ? '' : 'checked="checked"'); ?> value="0" >
                <label for="published0"><?php echo __('No', 'bwge_back'); ?></label>
                <input type="radio" class="inputbox" id="published1" name="published" <?php echo (($row->published) ? 'checked="checked"' : ''); ?> value="1" >
                <label for="published1"><?php echo __('Yes', 'bwge_back'); ?></label>
              </td>
            </tr>
            <tr>
              <td class="bwge_spider_label_galleries"><label for="url"><?php echo __('Preview image:', 'bwge_back'); ?></label></td>
              <td>
              <?php 
              $query_url = add_query_arg(array('action' => 'bwge_addImages', 'width' => '700', 'height' => '550', 'extensions' => 'jpg,jpeg,png,gif', 'callback' => 'bwge_add_preview_image'), admin_url('admin-ajax.php'));
              $query_url = wp_nonce_url( $query_url, 'bwge_addImages', 'bwge_nonce' );
              $query_url = add_query_arg(array( 'TB_iframe' => '1'), $query_url);
              ?>

                <a href="<?php echo $query_url; ?>"
                   id="button_preview_image"
                   class="wd-btn wd-btn-primary thickbox thickbox-preview"
                   title="Add Preview Image"
                   onclick="return false;"
                   style="margin-bottom:5px; display:none;">
                  <?php echo __('Add Preview Image', 'bwge_back'); ?>
                </a>
                <input type="hidden" id="preview_image" name="preview_image" value="<?php echo $row->preview_image; ?>" style="display:inline-block;"/>
                <img id="img_preview_image"
                     style="max-height:90px; max-width:120px; vertical-align:middle;"
                     src="<?php echo site_url() . '/' . $WD_BWGE_UPLOAD_DIR . $row->preview_image; ?>">
                <span id="delete_preview_image" class="bwge_spider_delete_img"
                      onclick="bwge_spider_remove_url('button_preview_image', 'preview_image', 'delete_preview_image', 'img_preview_image')"></span>
              </td>
            </tr>
          </tbody>
        </table>
		<?php echo $this->image_display($id); ?>
        <input id="task" name="task" type="hidden" value="" />
        <input id="current_id" name="current_id" type="hidden" value="<?php echo $row->id; ?>" />
        <script>
          <?php
          if ($row->preview_image == '') {
            ?>
            bwge_spider_remove_url('button_preview_image', 'preview_image', 'delete_preview_image', 'img_preview_image');
            <?php
          }
          ?>
        </script>
        <div id="opacity_div" style="display: none; background-color: rgba(0, 0, 0, 0.2); position: fixed; top: 0; left: 0; width: 100%; height: 100%; z-index: 99998;"></div>
        <div id="loading_div" style="display:none; text-align: center; position: fixed; top: 0; left: 0; width: 100%; height: 100%; z-index: 99999;">
          <img src="<?php echo WD_BWGE_URL . '/images/ajax_loader.png'; ?>" class="bwge_spider_ajax_loading" style="margin-top: 200px; width:50px;">
        </div>
      </form>
    </div>
    <?php
  }
  
  public function image_display($id) {
    global $WD_BWGE_UPLOAD_DIR;
    global $wd_bwge_fb;
    $rows_data = $this->model->get_image_rows_data($id);
    $page_nav = $this->model->image_page_nav($id);
    $option_row = $this->model->get_option_row_data();
    $search_value = ((isset($_POST['search_value'])) ? esc_html(stripslashes($_POST['search_value'])) : '');
    $image_asc_or_desc = ((isset($_POST['image_asc_or_desc'])) ? esc_html(stripslashes($_POST['image_asc_or_desc'])) : ((isset($_COOKIE['bwge_image_asc_or_desc'])) ? esc_html(stripslashes($_COOKIE['bwge_image_asc_or_desc'])) : 'asc'));
    $image_order_by = ((isset($_POST['image_order_by'])) ? esc_html(stripslashes($_POST['image_order_by'])) : ((isset($_COOKIE['bwge_image_order_by'])) ? esc_html(stripslashes($_COOKIE['bwge_image_order_by'])) : 'order'));
    $order_class = 'manage-column column-title sorted ' . $image_asc_or_desc;
    $page_number = (isset($_POST['page_number']) ? esc_html(stripslashes($_POST['page_number'])) : 1);
    $ids_string = '';
    $per_page = $this->model->per_page();
    $pager = 0;

    $gallery_row = $this->model->get_row_data($id);
    $instagram_post_gallery = $gallery_row->gallery_type == 'instagram_post' ? true :false;
    $facebook_post_gallery = (!$instagram_post_gallery) ? ($gallery_row->gallery_type == 'facebook_post' ? true :false) : false;
    $gallery_type = ($gallery_row->gallery_type == 'instagram' || $gallery_row->gallery_type == 'instagram_post') ? 'instagram' : (($gallery_row->gallery_type == 'facebook_post' || $gallery_row->gallery_type == 'facebook') ? 'facebook' : '');
    $update_flag = $gallery_row->update_flag;
    ?>
      <div class="wd_divider"></div>
      <div>
      <div class="wd-clear wd-row">
        <div class="wd-left">
          <?php
          $query_url = add_query_arg(array('action' => 'bwge_addImages', 'width' => '700', 'height' => '550', 'extensions' => 'jpg,jpeg,png,gif', 'callback' => 'bwge_add_image'), admin_url('admin-ajax.php'));
          $query_url = wp_nonce_url($query_url, 'bwge_addImages', 'bwge_nonce');
          $query_url = add_query_arg(array('TB_iframe' => '1'), $query_url);
          ?>
          <a href="<?php echo  $query_url;  ?>" class="wd-btn wd-btn-primary thickbox thickbox-preview add_image_bwge"  title="<?php echo __("Add Images", 'bwge_back'); ?>" onclick="return false;" style="margin-bottom:5px; <?php if($gallery_type !='') {echo 'display:none';} ?>" >
            <?php echo __('Add Images', 'bwge_back'); ?>
          </a>
          <?php
          $query_url = wp_nonce_url(admin_url('admin-ajax.php'), '', 'bwge_nonce');
          /*(re?)define ajax_url to add nonce only in admin*/
          ?>
          <script>
            var ajax_url = "<?php echo $query_url; ?>"
          </script>

        </div>
        <div class="buttons_div_right wd-right" style="margin:0;">
         <?php 
          
            $query_url =  admin_url('admin-ajax.php');
            $query_url = add_query_arg(array('action' => 'bwge_add_pricelist', 'page' => 'pricelists_bwge', 'task' => 'explore', 'width' => '650', 'height' => '500','nonce_bwge' => wp_create_nonce('nonce_bwge') , 'TB_iframe' => '1' ), $query_url);
          ?>
          <a id="_add_pricelist" onclick="return bwge_check_checkboxes(1);" href="<?php echo $query_url;?>" class="wd-btn wd-btn-primary wd-btn-icon wd-btn-add"><?php echo __('Add pricelist', 'bwge_back'); ?></a>
          <input type="button" value="Remove pricelist" class="wd-btn wd-btn-primary-red wd-btn-icon wd-btn-delete" onclick="if(!bwge_check_checkboxes(1) ){ return false;}
          else if(confirm('Do you want to remove pricelist from selected items?') ) {
                                                         bwge_spider_set_input_value('ajax_task', 'remove_pricelist_all');
                                                         bwge_spider_ajax_save('galleries_form');
                                                         return false;
                                                       } else {
                                                         return false;
                                                       }">
          <input type="hidden" name="image_pricelist_id" id="image_pricelist_id" >	
        </div>
      </div>
      <div class="wd-clear">
        <div class="wd-left">
          <?php  BWGELibrary::ajax_search(__('Filename','bwge_back'), $search_value, 'galleries_form', false);?>
        </div>
        <div class="wd-right">
          <span class="wd-btn wd-btn-secondary bwge_non_selectable bwge_non_selectable" onclick="bwge_spider_check_all_items()">
            <input type="checkbox" id="check_all_items" name="check_all_items" onclick="bwge_spider_check_all_items_checkbox()" style="margin: 0; vertical-align: middle;" />
            <span style="vertical-align: middle;"><?php echo __('Select All', 'bwge_back'); ?></span>
          </span>
           
          <input id="show_hide_weights"  class="wd-btn wd-btn-secondary" type="button" onclick="bwge_spider_show_hide_weights();return false;" value="<?php echo __('Hide order column', 'bwge_back'); ?>" />
          <input class="wd-btn wd-btn-primary" id='bwge_spider_setwatermark_button' style="<?php if($gallery_type !='') {echo 'display:none';} ?>" type="submit" onclick="bwge_spider_set_input_value('ajax_task', 'image_set_watermark');
                                                               bwge_spider_ajax_save('galleries_form');
                                                               return false;" value="<?php echo __('Set Watermark', 'bwge_back'); ?>" />
          <input class="wd-btn wd-btn-secondary" id='bwge_spider_resize_button' style="<?php if($gallery_type !='') {echo 'display:none';} ?>" type="submit" onclick="jQuery('.opacity_resize_image').show(); return false;" value="<?php echo __('Resize', 'bwge_back'); ?>" />
          <input class="wd-btn wd-btn-secondary" type="submit" onclick="bwge_spider_set_input_value('ajax_task', 'resize_image_thumb'); bwge_spider_ajax_save('galleries_form');return false;" value="<?php echo __('Recreate Thumbnail', 'bwge_back'); ?>" />
          <input class="wd-btn wd-btn-secondary" id='bwge_spider_reset_button' style="<?php if($gallery_type !='') {echo 'display:none';} ?>" type="submit" onclick="bwge_spider_set_input_value('ajax_task', 'image_recover_all');
                                                               bwge_spider_ajax_save('galleries_form');
                                                               return false;" value="<?php echo __('Reset', 'bwge_back'); ?>" />
          <?php
          $query_url = wp_nonce_url( admin_url('admin-ajax.php'), 'addTags_bwge', 'bwge_nonce' );
          $query_url = add_query_arg(array('action' => 'addTags_bwge', 'width' => '650', 'height' => '500', 'bwge_items_per_page' => $per_page ), $query_url);
          ?>                                                             
          <a onclick="return bwge_check_checkboxes();" href="<?php echo $query_url; ?>&TB_iframe=1" class="wd-btn wd-btn-primary thickbox thickbox-preview"><?php echo __('Add tag', 'bwge_back'); ?></a>
          <input class="wd-btn wd-btn-secondary" type="submit" onclick="bwge_spider_set_input_value('ajax_task', 'image_publish_all');
                                                       bwge_spider_ajax_save('galleries_form');
                                                       return false;" value="<?php echo __('Publish', 'bwge_back'); ?>" />
          <input class="wd-btn wd-btn-secondary" type="submit" onclick="bwge_spider_set_input_value('ajax_task', 'image_unpublish_all');
                                                       bwge_spider_ajax_save('galleries_form');
                                                       return false;" value="<?php echo __('Unpublish', 'bwge_back'); ?>" />
          <input class="wd-btn wd-btn-secondary bwge_spider_delete_button" style="<?php if($gallery_type != '' && $update_flag != '') {echo 'display:none';} ?>" type="submit" onclick="if (confirm('<?php echo addslashes(__('Do you want to delete selected items?', 'bwge_back')); ?>')) {
                                                         bwge_spider_set_input_value('ajax_task', 'image_delete_all');
                                                         bwge_spider_ajax_save('galleries_form');
                                                         return false;
                                                       } else {
                                                         return false;
                                                       }" value="<?php echo __('Delete', 'bwge_back'); ?>" />        
        </div>        
      </div>     
      </div>
    <div class="opacity_resize_image opacity_add_embed opacity_bulk_embed bwge_opacity_media" onclick="jQuery('.opacity_add_embed').hide(); jQuery('.opacity_bulk_embed').hide(); jQuery('.opacity_resize_image').hide();"></div>
      
      <div id="add_embed" class="opacity_add_embed bwge_add_embed">
        <input type="text" id="embed_url" name="embed_url" value="" />
        <input class="wd-btn wd-btn-primary" type="button" onclick="if (bwge_get_embed_info('embed_url')) {jQuery('.opacity_add_embed').hide();} return false;" value="<?php echo __('Add to gallery', 'bwge_back'); ?>" />
        <input class="wd-btn wd-btn-secondary " type="button" onclick="jQuery('.opacity_add_embed').hide(); return false;" value="<?php echo __('Cancel', 'bwge_back'); ?>" />
        <div class="bwge_spider_description">
        <p><?php echo __('Enter YouTube, Vimeo, Instagram, Facebook, Flickr or Dailymotion URL here.', 'bwge_back'); ?> <a onclick="jQuery('#add_embed_help').show();" style='text-decoration: underline; color:00A0D2; cursor: pointer;'><?php echo __('Help', 'bwge_back'); ?></a></p>
        </div>
        <div id='add_embed_help' class= "opacity_add_embed bwge_add_embed" style="display:none;">
            <p style="text-align:right; margin-top:0px;"><a onclick="jQuery('#add_embed_help').hide();" style="text-decoration: underline; color:#00A0D2; cursor: pointer; "><?php echo __('Close', 'bwge_back'); ?></a></p>
            <p><b>Youtube</b> URL <?php echo __('example:', 'bwge_back'); ?> <i style="">https://www.youtube.com/watch?v=fa4RLjE-yM8</i></p>
            <p><b>Vimeo</b> URL <?php echo __('example:', 'bwge_back'); ?> <i style="">http://vimeo.com/8110647</i></p>
            <p><b>Instagram</b> URL <?php echo __('example:', 'bwge_back'); ?> <i style="">http://instagram.com/p/ykvv0puS4u</i>. <?php echo __('Add', 'bwge_back'); ?> "<i style="text-decoration:underline;"><?php echo __('post', 'bwge_back'); ?></i>"<?php echo __('to the end of URL if you want to embed the whole Instagram post, not only its content.', 'bwge_back'); ?></p>
			<p><b>Facebook</b> </br>         
			  <?php echo __('Photo URL example:', 'bwge_back'); ?> <i style="">https://www.facebook.com/WebDorado/photos/pb.436551809728904.-2207520000.1442409849./<i style="text-decoration:underline;">1007024672681612</i></i>.</br>
        <?php echo __('Video URL example:', 'bwge_back'); ?> <i style="">https://www.facebook.com/WebDorado/videos/vb.436551809728904/<i style="text-decoration:underline;">1013555838695162</i></i>.</br>
			  <!-- Add fbid's value fbid="<i style="text-decoration:underline;">1614282122189006 </i>" or the last number of video's url to the end for Post url. </br>
			  Post URL example:  <i style="">https://www.facebook.com/elen.eghiazaryan/posts/<i style="text-decoration:underline;">1614282122189006</i></i>.</br>
			  Note that post must have public privacy. -->
			  <?php echo __('Note that media must have public privacy.', 'bwge_back'); ?>
			</p>	
            <p><b>Flickr</b> URL <?php echo __('example:', 'bwge_back'); ?> <i style="">https://www.flickr.com/photos/sui-fong/15250186998/in/gallery-flickr-72157648726328108/</i></p>
            <p><b>Dailymotion</b> <?php echo __('URL example:', 'bwge_back'); ?> <i style="">http://www.dailymotion.com/video/xexaq0_frank-sinatra-strangers-in-the-nigh_music</i></p>
          </div>
      </div>
      <div id="bulk_embed" class="opacity_bulk_embed bwge_bulk_embed">
        <input class="wd-btn wd-btn-secondary " type="button" onclick="jQuery('.opacity_bulk_embed').hide(); jQuery('#opacity_div').hide(); jQuery('#loading_div').hide(); return false;" value="Cancel" style="float:right; margin-left:5px;"/>
        <input class="wd-btn wd-btn-primary" type="button" onclick="bwge_bulk_embed('instagram', '<?php echo $option_row->instagram_access_token ?>');" value="Add to gallery" style="float:right; margin-left:5px;"/>
        <div class="bwge_spider_description"></div>
    		<table>
    	    <?php if($wd_bwge_fb): ?>
            <thead>
              <tr>
                <td class="bwge_spider_label_galleries"><label for="bulk_embed_from"><?php echo __('Bulk embed from:', 'bwge_back'); ?> </label></td>
                <td>
                  <input type="radio" class="inputbox" id="bulk_embed_from_instagram" name="bulk_embed_from" onclick="jQuery('#facebook_bulk_params').hide();jQuery('#instagram_bulk_params').show();jQuery('#bulk_embed').find('.wd-btn wd-btn-primary').attr('onclick', 'bwge_bulk_embed(\'instagram\', \'<?php echo $option_row->instagram_access_token ?>\')')" checked="checked" value="instagram" >
                  <label for="bulk_embed_from_instagram">Instagram</label>&nbsp; 
                  <input type="radio" class="inputbox" id="bulk_embed_from_facebook" name="bulk_embed_from" onclick="jQuery('#instagram_bulk_params').hide();jQuery('#facebook_bulk_params').show();jQuery('#bulk_embed').find('.wd-btn wd-btn-primary').attr('onclick', 'bwge_bulk_embed(\'facebook\', \'\')')"  value="facebook" >
                  <label for="bulk_embed_from_facebook">Facebook</label>&nbsp;
                </td>
              </tr>
            </thead>
          <?php endif; ?>
          <tbody id="instagram_bulk_params">
            <tr id='popup_tr_instagram_gallery_source' style='display:table-row'>
              <td class="bwge_spider_label_galleries"><label for="popup_instagram_gallery_source">Instagram <?php echo __('username:', 'bwge_back'); ?> </label></td>
              <td><input type="text" id="popup_instagram_gallery_source" name="popup_instagram_gallery_source" value="" size="64" /></td>
            </tr>
            <tr id='popup_tr_instagram_image_number' style='display:table-row'>
              <td class="bwge_spider_label_galleries"><label for="popup_instagram_image_number"><?php echo __('Number of Instagram recent posts to add to gallery:', 'bwge_back'); ?> </label></td>
              <td><input type="number" id="popup_instagram_image_number" name="popup_instagram_image_number" value="12" /></td>
            </tr>
            <tr id='popup_tr_instagram_post_gallery' style='display:table-row'>
              <td class="bwge_spider_label_galleries"><label>Instagram <?php echo __('embed type:', 'bwge_back'); ?> </label></td>
              <td>
                <input type="radio" class="inputbox" id="popup_instagram_post_gallery_0" name="popup_instagram_post_gallery" checked="checked" value="0" >
                <label for="popup_instagram_post_gallery_0"><?php echo __('Content', 'bwge_back'); ?></label>&nbsp;
                <input type="radio" class="inputbox" id="popup_instagram_post_gallery_1" name="popup_instagram_post_gallery" value="1" >
                <label for="popup_instagram_post_gallery_1"><?php echo __('Whole post', 'bwge_back'); ?></label>
              </td>
            </tr>
    		  </tbody>
          <?php if($wd_bwge_fb): ?>
            <!-- Facebook part  -->
            <tfoot id="facebook_bulk_params" style="display:none">
              <tr id='popup_tr_facebook_gallery_album_url' style='display:table-row'>
                <td class="bwge_spider_label_galleries"><label for="popup_facebook_gallery_album_url">Facebook <?php echo __('album url:', 'bwge_back'); ?> </label></td>
                <td><input type="text" id="popup_facebook_gallery_album_url" name="popup_facebook_gallery_album_url" value="" size="64" /></td>
              </tr>
              <tr id='popup_tr_facebook_gallery_album_limit' style='display:table-row'>
                <td class="bwge_spider_label_galleries"><label for="popup_facebook_gallery_album_limit"><?php echo __('Images limit:', 'bwge_back'); ?> </label></td>
                <td><input type="number" id="popup_facebook_gallery_album_limit" name="popup_facebook_gallery_album_limit" value="10" size="19" /></td>
              </tr>
              <!-- 
              <tr id='popup_tr_facebook_gallery_album_content_type' style='display:table-row'>
                <td class="bwge_spider_label_galleries"><label for="popup_facebook_gallery_album_content_type">Content type: </label></td>
                <td>
                  <input type="radio" id="popup_facebook_gallery_album_content_bwge_type0" checked="checked" name="popup_facebook_gallery_album_content_type" value="regular" />
                <label for="popup_facebook_gallery_album_content_bwge_type0">Regular</label>&nbsp;
                  <input type="radio" id="popup_facebook_gallery_album_content_bwge_type1" name="popup_facebook_gallery_album_content_type" value="post" />
                <label for="popup_facebook_gallery_album_content_bwge_type1">Post</label>&nbsp;
                </td>
               </tr> -->
             </tfoot> 
           <?php endif; ?>
    		</table>

      </div>
      <div id="" class="opacity_resize_image bwge_resize_image">
        <?php echo __('Resize images to: ', 'bwge_back'); ?>
        <input type="text" name="image_width" id="image_width" value="1600" style="width:150px"/> x 
        <input type="text" name="image_height" id="image_height" value="1200" style="width:150px" /> px
        <input class="wd-btn wd-btn-primary" type="button" onclick="bwge_spider_set_input_value('ajax_task', 'image_resize');
                                                             bwge_spider_ajax_save('galleries_form');
                                                             jQuery('.opacity_resize_image').hide();
                                                             return false;" value="Resize" />
        <input class="wd-btn wd-btn-secondary " type="button" onclick="jQuery('.opacity_resize_image').hide(); return false;" value="Cancel" />
        <div class="bwge_spider_description"><?php echo __('The maximum size of resized image.', 'bwge_back'); ?></div>
      </div>
      <div id="draganddrop" class="updated" style="display:none;"><strong><p>Changes made in this table should be saved.</p></strong></div>
      <div class="tablenav top" id="tablenav">
        <?php
        BWGELibrary::ajax_html_page_nav($page_nav['total'], $page_nav['limit'], 'galleries_form', $per_page, $pager++);
        ?>
      </div>

      <table id="images_table" class="wp-list-table widefat fixed pages bwge_list_table">
        <thead>
          <tr class="bwge_alternate">
            <th class="check-column table_small_col"></th>
            <th class="manage-column column-cb check-column table_small_col"><input id="check_all" type="checkbox" onclick="bwge_spider_check_all(this)" style="margin:0;" /></th>
            <th class="table_small_col">#</th>
            <th class="table_extra_large_col"><?php echo __('Thumbnail', 'bwge_back'); ?></th>
            <th class="table_extra_large_col <?php if ($image_order_by == 'filename') {echo $order_class;} ?>">
              <a onclick="bwge_spider_set_input_value('task', '');
                          bwge_spider_set_input_value('image_order_by', 'filename');
                          bwge_spider_set_input_value('image_asc_or_desc', '<?php echo ($image_order_by == 'filename' && $image_asc_or_desc == 'asc') ? 'desc' : 'asc'; ?>');
                          bwge_spider_ajax_save('galleries_form');">
                <span><?php echo __('Filename', 'bwge_back'); ?></span><span class="sorting-indicator"></span>
              </a>
            </th>
            <th class="table_extra_large_col <?php if ($image_order_by == 'alt') {echo $order_class;} ?>">
              <a onclick="bwge_spider_set_input_value('task', '');
                          bwge_spider_set_input_value('image_order_by', 'alt');
                          bwge_spider_set_input_value('image_asc_or_desc', '<?php echo ($image_order_by == 'alt' && $image_asc_or_desc == 'asc') ? 'desc' : 'asc'; ?>');
                          bwge_spider_ajax_save('galleries_form');">
                <span><?php echo __('Alt/Title', 'bwge_back'); ?><?php if ($option_row->thumb_click_action != 'open_lightbox') { ?><br /><?php echo __('Redirect', 'bwge_back'); ?> URL<?php } ?></span><span class="sorting-indicator"></span>
              </a>
            </th>
            <th class="table_extra_large_col <?php if ($image_order_by == 'description') {echo $order_class;} ?>">
              <a onclick="bwge_spider_set_input_value('task', '');
                          bwge_spider_set_input_value('image_order_by', 'description');
                          bwge_spider_set_input_value('image_asc_or_desc', '<?php echo ($image_order_by == 'description' && $image_asc_or_desc == 'asc') ? 'desc' : 'asc'; ?>');
                          bwge_spider_ajax_save('galleries_form');">
                <span><?php echo __('Description', 'bwge_back'); ?></span><span class="sorting-indicator"></span>
              </a>
            </th>
            <th class="table_extra_large_col"><?php echo __('Tags', 'bwge_back'); ?></th>
            <th id="th_order" class="table_medium_col <?php if ($image_order_by == 'order') {echo $order_class;} ?>">
              <a onclick="bwge_spider_set_input_value('task', '');
                          bwge_spider_set_input_value('image_order_by', 'order');
                          bwge_spider_set_input_value('image_asc_or_desc', '<?php echo ($image_order_by == 'order' && $image_asc_or_desc == 'asc') ? 'desc' : 'asc'; ?>');
                          bwge_spider_ajax_save('galleries_form');">
                <span><?php echo __('Order', 'bwge_back'); ?></span><span class="sorting-indicator"></span>
              </a>
            </th>
            <th class="table_big_col <?php if ($image_order_by == 'published') {echo $order_class;} ?>">
              <a onclick="bwge_spider_set_input_value('task', '');
                          bwge_spider_set_input_value('image_order_by', 'published');
                          bwge_spider_set_input_value('image_asc_or_desc', '<?php echo ($image_order_by == 'published' && $image_asc_or_desc == 'asc') ? 'desc' : 'asc'; ?>');
                          bwge_spider_ajax_save('galleries_form');">
                <span><?php echo __('Published', 'bwge_back'); ?></span><span class="sorting-indicator"></span>
              </a>
            </th>
            <th class="table_big_col"><?php echo __('Delete', 'bwge_back'); ?></th>
          </tr>
        </thead>
        <tbody id="tbody_arr">
          <?php
          $i = ($page_number - 1) * $per_page;
          $iterator = 0;
          if ($rows_data) {
            foreach ($rows_data as $row_data) {
              
              $is_embed = preg_match('/EMBED/',$row_data->filetype)==1 ? true :false;
              $alternate = $iterator%2 == 0 ? '' : 'class="bwge_alternate"';
              $rows_tag_data = $this->model->get_tag_rows_data($row_data->id);
              $published_image = (($row_data->published) ? 'publish-blue' : 'unpublish-blue');
              $published = (($row_data->published) ? 'unpublish' : 'publish');
              ?>
              <tr id="tr_<?php echo $row_data->id; ?>" <?php echo $alternate; ?>>
                <td class="connectedSortable table_small_col"><div title="Drag to re-order" class="handle" style="margin:5px auto 0 auto;"></div></td>
                <td class="table_small_col check-column"><input id="check_<?php echo $row_data->id; ?>" name="check_<?php echo $row_data->id; ?>" onclick="bwge_spider_check_all(this)" type="checkbox" /></td>
                <td class="table_small_col"><?php echo ++$i; ?></td>
                <td class="table_extra_large_col">
                <?php
                $is_facebook_post = ($row_data->filetype == 'EMBED_OEMBED_FACEBOOK_POST') ? true : false;
                $fb_post_url = ($is_facebook_post) ? $row_data->filename : '';
                $query_url = add_query_arg(array('action' => 'editThumb_bwge', 'type' => 'display'/*thumb_display*/, 'image_id' => $row_data->id, 'width' => '800', 'height' => '500','FACEBOOK_POST' => $is_facebook_post, 'fb_post_url' => $fb_post_url), admin_url('admin-ajax.php'));
                $query_url = wp_nonce_url( $query_url, 'editThumb_bwge', 'bwge_nonce' );
                $query_url = add_query_arg(array('TB_iframe' => '1'), $query_url);
                ?>
                  <a class="thickbox thickbox-preview" title="<?php echo $row_data->alt; ?>" href="<?php echo $query_url; ?>">
                    <img id="image_thumb_<?php echo $row_data->id; ?>" class="thumb" src="<?php echo (!$is_embed ? site_url() . '/' . $WD_BWGE_UPLOAD_DIR : "") . $row_data->thumb_url . (($is_embed) ? '' : '?date=' . date('Y-m-y H:i:s')); ?>">
                  </a>
                </td>
                <td class="table_extra_large_col">
                  <div class="filename" id="filename_<?php echo $row_data->id; ?>">
                    <strong><a title="<?php echo $row_data->alt; ?>" class="bwge_spider_word_wrap thickbox thickbox-preview" href="<?php echo $query_url ; ?>"><?php echo $row_data->filename; ?></a></strong>
                  </div>
                  <div class="fileDescription" title="Date modified" id="date_modified_<?php echo $row_data->id; ?>"><?php echo date("d F Y, H:i", strtotime($row_data->date)); ?></div>
                  <div class="fileDescription" title="Image Resolution" id="fileresolution_<?php echo $row_data->id; ?>"><?php echo $row_data->resolution; ?></div>
                  <div class="fileDescription" title="Image size" id="filesize_<?php echo $row_data->id; ?>"><?php echo $row_data->size; ?></div>
                  <div class="fileDescription" title="Type" id="filebwge_type<?php echo $row_data->id; ?>"><?php echo $row_data->filetype; ?></div>
                  <?php
                   if (!$is_embed && ($gallery_type =='')) {
                    ?>
                  <div>
                  <?php
                  $query_url = add_query_arg(array('action' => 'editThumb_bwge', 'type' => 'crop', 'image_id' => $row_data->id, 'width' => '800', 'height' => '500'), admin_url('admin-ajax.php'));
                  $query_url = wp_nonce_url( $query_url, 'editThumb_bwge', 'bwge_nonce' );
                  $query_url = add_query_arg(array('TB_iframe' => '1'), $query_url);

                  ?>

                    <span class="edit_thumb"><a class="thickbox thickbox-preview" href="<?php echo $query_url; ?>"><?php echo __('Crop', 'bwge_back'); ?></a></span> | 
                    <?php 
                    $query_url = add_query_arg(array('action' => 'editThumb_bwge', 'type' => 'rotate', 'image_id' => $row_data->id, 'width' => '800', 'height' => '500'), admin_url('admin-ajax.php'));
                    $query_url = wp_nonce_url( $query_url, 'editThumb_bwge', 'bwge_nonce' );
                    $query_url = add_query_arg(array('TB_iframe' => '1'), $query_url);

                    ?>

                    <span class="edit_thumb"><a class="thickbox thickbox-preview" href="<?php echo $query_url; ?>"><?php echo __('Edit', 'bwge_back'); ?></a></span> | 
                    <span class="edit_thumb"><a onclick="if (confirm('<?php echo addslashes(__('Do you want to reset the image?', 'bwge_back')); ?>')) {
                                                          bwge_spider_set_input_value('ajax_task', 'recover');
                                                          bwge_spider_set_input_value('image_current_id', '<?php echo $row_data->id; ?>');
                                                          bwge_spider_ajax_save('galleries_form');
                                                         }
                                                         return false;"><?php echo __('Reset', 'bwge_back'); ?></a></span>
                  </div>
                  <?php } ?>
                  <input type="hidden" id="image_url_<?php echo $row_data->id; ?>" name="image_url_<?php echo $row_data->id; ?>" value="<?php echo $row_data->image_url; ?>" />
                  <input type="hidden" id="thumb_url_<?php echo $row_data->id; ?>" name="thumb_url_<?php echo $row_data->id; ?>" value="<?php echo $row_data->thumb_url; ?>" />
                  <input type="hidden" id="input_filename_<?php echo $row_data->id; ?>" name="input_filename_<?php echo $row_data->id; ?>" value="<?php echo $row_data->filename; ?>" />
                  <input type="hidden" id="input_date_modified_<?php echo $row_data->id; ?>" name="input_date_modified_<?php echo $row_data->id; ?>" value="<?php echo $row_data->date; ?>" />
                  <input type="hidden" id="input_resolution_<?php echo $row_data->id; ?>" name="input_resolution_<?php echo $row_data->id; ?>" value="<?php echo $row_data->resolution; ?>" />
                  <input type="hidden" id="input_size_<?php echo $row_data->id; ?>" name="input_size_<?php echo $row_data->id; ?>" value="<?php echo $row_data->size; ?>" />
                  <input type="hidden" id="input_filebwge_type<?php echo $row_data->id; ?>" name="input_filebwge_type<?php echo $row_data->id; ?>" value="<?php echo $row_data->filetype; ?>" />
                </td>
                <td class="table_extra_large_col">
                  <input style="width:150px;" type="text" id="image_alt_text_<?php echo $row_data->id; ?>" name="image_alt_text_<?php echo $row_data->id; ?>" value="<?php echo $row_data->alt; ?> " <?php /* if($update_flag != '') {echo ' readonly="readonly" ';} */ ?> />
                  <?php if ($option_row->thumb_click_action != 'open_lightbox') { ?>
                  <input style="width:150px;" type="text" id="redirect_url_<?php echo $row_data->id; ?>" name="redirect_url_<?php echo $row_data->id; ?>" value="<?php echo $row_data->redirect_url; ?>" <?php /* if($update_flag != '') {echo ' readonly="readonly" ';} */?> />
                  <?php } ?>
                </td>
                <td class="table_extra_large_col">
                  <textarea rows="2" id="image_description_<?php echo $row_data->id; ?>" name="image_description_<?php echo $row_data->id; ?>" style="resize:vertical;width:150px;" <?php /* if($update_flag != '') {echo ' readonly="readonly" ';} */?>><?php echo $row_data->description; ?></textarea>
                 <?php 
              
                  $priselist_name = $row_data->priselist_name ? "Pricelist: ".$row_data->priselist_name : "Not for sale";
                  
                  $unset = $priselist_name == "Not for sale" ? "" : " <span onclick='bwge_remove_pricelist(this);' data-image-id= '".$row_data->id."' data-pricelist-id='".$row_data->pricelist_id."' class ='bwge_spider_delete_img_small' style='margin-top: -2px;margin-left: 3px;'></span>";
                          
                  echo "<div><strong>".$priselist_name." </strong>".$unset."</div>";
                          $not_set_text = $row_data->not_set_items == 1 ? __('Selected pricelist item longest dimension greater than some original images dimensions.', 'bwge_back') : ""; 
                  echo "<small id='priselist_set_error".$row_data->id."' style='color:#B41111;' >".$not_set_text."</small>";
                        echo "<input type='hidden' id='pricelist_id_".$row_data->id."' value='".$row_data->pricelist_id."'>";

                
                  ?>
                </td>
                <td class="table_extra_large_col">

                <?php
                
                $query_url = wp_nonce_url( admin_url('admin-ajax.php'), 'addTags_bwge', 'bwge_nonce' );
                $query_url = add_query_arg(array('action' => 'addTags_bwge', 'image_id' => $row_data->id, 'width' => '650', 'height' => '500', 'bwge_items_per_page' => $per_page, 'TB_iframe' => '1'), $query_url);
                ?>
                  <a href="<?php echo $query_url; ?>" class="wd-btn wd-btn-primary wd-btn-small thickbox thickbox-preview"><?php echo __('Add tag', 'bwge_back'); ?></a>
                  <div class="tags_div" id="tags_div_<?php echo $row_data->id; ?>">
                  <?php
                  $tags_id_string = '';
                  if ($rows_tag_data) {
                    foreach($rows_tag_data as $row_tag_data) {
                      ?>
                      <div class="tag_div" id="<?php echo $row_data->id; ?>_tag_<?php echo $row_tag_data->term_id; ?>">
                        <span class="tag_name"><?php echo $row_tag_data->name; ?></span>
                        <span style="float:right;" class="bwge_spider_delete_img_small" onclick="bwge_remove_tag('<?php echo $row_tag_data->term_id; ?>', '<?php echo $row_data->id; ?>')" />
                      </div>
                      <?php
                      $tags_id_string .= $row_tag_data->term_id . ',';
                    }
                  }
                  ?>
                  </div>
                  <input type="hidden" value="<?php echo $tags_id_string; ?>" id="tags_<?php echo $row_data->id; ?>" name="tags_<?php echo $row_data->id; ?>"/>
                </td>
                <td class="bwge_spider_order table_medium_col"><input id="order_input_<?php echo $row_data->id; ?>" name="order_input_<?php echo $row_data->id; ?>" type="text" style="width: 49px;" value="<?php echo $row_data->order; ?>" /></td>
                <td class="table_big_col"><a onclick="bwge_spider_set_input_value('ajax_task', 'image_<?php echo $published; ?>');
                                                      bwge_spider_set_input_value('image_current_id', '<?php echo $row_data->id; ?>');
                                                      bwge_spider_ajax_save('galleries_form');"><img src="<?php echo WD_BWGE_URL . '/images/css/' . $published_image . '.png'; ?>"></img></a></td>
                <td class="table_big_col bwge_spider_delete_button" style="<?php if($gallery_type != '' && $update_flag != '') {echo 'display:none';} ?>"><a onclick="bwge_spider_set_input_value('ajax_task', 'image_delete');
                                                      bwge_spider_set_input_value('image_current_id', '<?php echo $row_data->id; ?>');
                                                      bwge_spider_ajax_save('galleries_form');"><?php echo __('Delete', 'bwge_back'); ?></a></td>
              </tr>
              <?php
              $ids_string .= $row_data->id . ',';
              $iterator++;
            }
          }
          ?>
          <input id="ids_string" name="ids_string" type="hidden" value="<?php echo $ids_string; ?>" />
          <input id="asc_or_desc" name="asc_or_desc" type="hidden" value="asc" />
          <input id="image_asc_or_desc" name="image_asc_or_desc" type="hidden" value="asc" />
          <input id="image_order_by" name="image_order_by" type="hidden" value="<?php echo $image_order_by; ?>" />
          <input id="ajax_task" name="ajax_task" type="hidden" value="" />
          <input id="image_current_id" name="image_current_id" type="hidden" value="" />
          <input id="added_tags_select_all" name="added_tags_select_all" type="hidden" value="" />
	      <input type="hidden" id="remove_pricelist" value="">
          
        </tbody>
      </table>
      <div class="tablenav bottom">
        <?php
        BWGELibrary::ajax_html_page_nav($page_nav['total'], $page_nav['limit'], 'galleries_form', $per_page, $pager++);
        ?>
      </div>
      <script>
        window.onload = bwge_spider_show_hide_weights;
      </script>
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