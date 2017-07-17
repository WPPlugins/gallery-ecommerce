<?php

class BWGEViewAlbums_bwge {
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
        <?php echo __('This section allows you to create, edit and delete albums.', 'bwge_back'); ?>
        <a style="color: #00A0D2; text-decoration: none;" target="_blank" href="https://galleryecommerce.com/gallery-set-up/creating-editing-galleries/"><?php echo __('Read More in User Manual', 'bwge_back'); ?></a>
      </div>
      <form class="bwge_form" id="albums_form" method="post" action="admin.php?page=albums_bwge" style="width:99%;">
      <?php wp_nonce_field( 'albums_bwge', 'bwge_nonce' ); ?>
        <!--<span class="album-icon"></span>-->
        <h2 id="add_album">
          <?php echo __('Albums', 'bwge_back'); ?>
          <a href="" class="wd-btn wd-btn-primary wd-btn-icon wd-btn-add" onclick="bwge_spider_set_input_value('task', 'add');
                                                 bwge_spider_form_submit(event, 'albums_form')"><?php echo __('Add new', 'bwge_back'); ?></a>
        </h2>
        <div class="wd-clear">
					<div class="wd-left">
						<?php
                BWGELibrary::search(__('Name','bwge_back'), $search_value, 'albums_form');
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
            <input class="wd-btn wd-btn-primary wd-btn-icon wd-btn-delete" type="submit" onclick="if (confirm('<?php echo addslashes(__('Do you want to delete selected items?', 'bwge_back')); ?>')) {
                                                           bwge_spider_set_input_value('task', 'delete_all');
                                                         } else {
                                                           return false;
                                                         }" value="<?php echo __('Delete', 'bwge_back'); ?>" />
          </div>
        </div>
        <div class="tablenav top">
          <?php         
          BWGELibrary::html_page_nav($page_nav['total'],$pager++, $page_nav['limit'], 'albums_form', $per_page);
          ?>
        </div>
        <table class="wp-list-table widefat fixed pages bwge_list_table">
          <thead>
            <tr class="bwge_alternate">
              <th class="table_small_col"></th>
              <th class="manage-column column-cb check-column table_small_col"><input id="check_all" onclick="bwge_spider_check_all(this)" type="checkbox" style="margin:0;" /></th>
              <th class="table_small_col <?php if ($order_by == 'id') {echo $order_class;} ?>">
                <a onclick="bwge_spider_set_input_value('task', '');
                            bwge_spider_set_input_value('order_by', 'id');
                            bwge_spider_set_input_value('asc_or_desc', '<?php echo ((isset($_POST['asc_or_desc']) && isset($_POST['order_by']) && (esc_html(stripslashes($_POST['order_by'])) == 'id') && esc_html(stripslashes($_POST['asc_or_desc'])) == 'asc') ? 'desc' : 'asc'); ?>');
                            bwge_spider_form_submit(event, 'albums_form')" href="">
                  <span>ID</span><span class="sorting-indicator"></span>
                </a>
              </th>          
              <th class="<?php if ($order_by == 'name') {echo $order_class;} ?>">
                <a onclick="bwge_spider_set_input_value('task', '');
                            bwge_spider_set_input_value('order_by', 'name');
                            bwge_spider_set_input_value('asc_or_desc', '<?php echo ((isset($_POST['asc_or_desc']) && isset($_POST['order_by']) && (esc_html(stripslashes($_POST['order_by'])) == 'name') && esc_html(stripslashes($_POST['asc_or_desc'])) == 'asc') ? 'desc' : 'asc'); ?>');
                            bwge_spider_form_submit(event, 'albums_form')" href="">
                  <span><?php echo __('Name', 'bwge_back'); ?></span><span class="sorting-indicator"></span>
                </a>
              </th>
              <th class="<?php if ($order_by == 'slug') {echo $order_class;} ?>">
                <a onclick="bwge_spider_set_input_value('task', '');
                            bwge_spider_set_input_value('order_by', 'slug');
                            bwge_spider_set_input_value('asc_or_desc', '<?php echo ((isset($_POST['asc_or_desc']) && isset($_POST['order_by']) && (esc_html(stripslashes($_POST['order_by'])) == 'slug') && esc_html(stripslashes($_POST['asc_or_desc'])) == 'asc') ? 'desc' : 'asc'); ?>');
                            bwge_spider_form_submit(event, 'albums_form')" href="">
                  <span><?php echo __('Slug', 'bwge_back'); ?></span><span class="sorting-indicator"></span>
                </a>
              </th>
              <th class="table_extra_large_col"><?php echo __('Thumbnail', 'bwge_back'); ?></th>
              <th id="th_order" class="table_medium_col <?php if ($order_by == 'order') {echo $order_class;} ?>">
                <a onclick="bwge_spider_set_input_value('task', '');
                            bwge_spider_set_input_value('order_by', 'order');
                            bwge_spider_set_input_value('asc_or_desc', '<?php echo ((isset($_POST['asc_or_desc']) && isset($_POST['order_by']) && (esc_html(stripslashes($_POST['order_by'])) == 'order') && esc_html(stripslashes($_POST['asc_or_desc'])) == 'asc') ? 'desc' : 'asc'); ?>');
                            bwge_spider_form_submit(event, 'albums_form')" href="">
                  <span><?php echo __('Order', 'bwge_back'); ?></span><span class="sorting-indicator"></span>
                </a>
              </th>
              <th class="<?php if ($order_by == 'display_name') {echo $order_class;} ?>">
                <a onclick="bwge_spider_set_input_value('task', '');
                            bwge_spider_set_input_value('order_by', 'display_name');
                            bwge_spider_set_input_value('asc_or_desc', '<?php echo ((isset($_POST['asc_or_desc']) && isset($_POST['order_by']) && (esc_html(stripslashes($_POST['order_by'])) == 'display_name') && esc_html(stripslashes($_POST['asc_or_desc'])) == 'asc') ? 'desc' : 'asc'); ?>');
                            bwge_spider_form_submit(event, 'albums_form')" href="">
                  <span><?php echo __('Author', 'bwge_back'); ?></span><span class="sorting-indicator"></span>
                </a>
              </th>
              <th class="table_big_col <?php if ($order_by == 'published') {echo $order_class;} ?>">
                <a onclick="bwge_spider_set_input_value('task', '');
                            bwge_spider_set_input_value('order_by', 'published');
                            bwge_spider_set_input_value('asc_or_desc', '<?php echo ((isset($_POST['asc_or_desc']) && isset($_POST['order_by']) && (esc_html(stripslashes($_POST['order_by'])) == 'published') && esc_html(stripslashes($_POST['asc_or_desc'])) == 'asc') ? 'desc' : 'asc'); ?>');
                            bwge_spider_form_submit(event, 'albums_form')" href="">
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
                $alternate = $iterator%2 == 0 ? '' : 'class="bwge_alternate"';
                $published_image = (($row_data->published) ? 'publish-blue' : 'unpublish-blue');
                $published = (($row_data->published) ? 'unpublish' : 'publish');
                if ($row_data->preview_image == '') {
                  $preview_image = WD_BWGE_URL . '/images/no-image.png';
                }
                else {
                  $preview_image = site_url() . '/' . $WD_BWGE_UPLOAD_DIR . $row_data->preview_image;
                }
                ?>
                <tr id="tr_<?php echo $row_data->id; ?>" <?php echo $alternate; ?>>
                  <td class="connectedSortable table_small_col"><div class="handle" style="margin:5px auto 0 auto;" title="Drag to re-order"></div></td>
                  <td class="table_small_col check-column"><input id="check_<?php echo $row_data->id; ?>" name="check_<?php echo $row_data->id; ?>" onclick="bwge_spider_check_all(this)" type="checkbox" /></td>
                  <td class="table_small_col"><?php echo $row_data->id; ?></td>                
                  <td><a onclick="bwge_spider_set_input_value('task', 'edit');
                                  bwge_spider_set_input_value('current_id', '<?php echo $row_data->id; ?>');
                                  bwge_spider_form_submit(event, 'albums_form')" href="" title="<?php echo __('Edit', 'bwge_back'); ?>"><?php echo $row_data->name; ?></a></td>
                  <td><?php echo $row_data->slug; ?></td>                
                  <td class="table_extra_large_col">
                    <img title="<?php echo $row_data->name; ?>" style="border: 1px solid #CCCCCC; max-width:60px; max-height:60px;" src="<?php echo $preview_image; ?>">
                  </td>
                  <td class="bwge_spider_order table_medium_col"><input id="order_input_<?php echo $row_data->id; ?>" name="order_input_<?php echo $row_data->id; ?>" type="text" size="1" value="<?php echo $row_data->order; ?>" style="width: 49px;" /></td>
                  <td><?php echo get_userdata($row_data->author)->display_name; ?></td>
                  <td class="table_big_col"><a onclick="bwge_spider_set_input_value('task', '<?php echo $published; ?>');bwge_spider_set_input_value('current_id', '<?php echo $row_data->id; ?>');bwge_spider_form_submit(event, 'albums_form')" href=""><img src="<?php echo WD_BWGE_URL . '/images/css/' . $published_image . '.png'; ?>"></img></a></td>
                  <td class="table_big_col"><a onclick="bwge_spider_set_input_value('task', 'edit');
                                                        bwge_spider_set_input_value('current_id', '<?php echo $row_data->id; ?>');
                                                        bwge_spider_form_submit(event, 'albums_form')" href=""><?php echo __('Edit', 'bwge_back'); ?></a></td>
                  <td class="table_big_col"><a onclick="bwge_spider_set_input_value('task', 'delete');
                                                        bwge_spider_set_input_value('current_id', '<?php echo $row_data->id; ?>');
                                                        bwge_spider_form_submit(event, 'albums_form')" href=""><?php echo __('Delete', 'bwge_back'); ?></a></td>
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
          BWGELibrary::html_page_nav($page_nav['total'],$pager++, $page_nav['limit'], 'albums_form', $per_page);
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
    $row = $this->model->get_row_data($id);
    $page_title = (($id != 0) ? 'Edit album ' . $row->name : 'Create new album');
    $per_page = $this->model->per_page();
    ?>

    <script>
      function bwge_add_preview_image(files) {
        document.getElementById("preview_image").value = files[0]['thumb_url'];
        document.getElementById("button_preview_image").style.display = "none";
        document.getElementById("delete_preview_image").style.display = "inline-block";
        if (document.getElementById("img_preview_image")) {
          document.getElementById("img_preview_image").src = files[0]['reliative_url'];
          document.getElementById("img_preview_image").style.display = "inline-block";
        }
      }

      function bwge_add_items(trackIds, titles, types) {
        jQuery(document).trigger("onAddAlbum");
        var tbody = document.getElementById('tbody_albums_galleries');
        var counter = 0;
        for(i = 0; i < trackIds.length; i++) {          
          tr = document.createElement('tr');
          tr.setAttribute('id', "tr_0:" + types[i] + ":" + trackIds[i]);
          tr.setAttribute('style', 'height:35px');
          
          var td_drag = document.createElement('td');
          td_drag.setAttribute('class','connectedSortable table_small_col');
          td_drag.setAttribute('title','Drag to re-order');
          
          var div_drag = document.createElement('div');
          div_drag.setAttribute('class', 'handle');
          
          td_drag.appendChild(div_drag);
          tr.appendChild(td_drag);          
          
          var td_title = document.createElement('td');
          td_title.setAttribute('style', 'max-width:420px;min-width:400px;');
          td_title.innerHTML = (types[i] == '1' ? 'Album: ' : 'Gallery: ') + titles[i];
          
          tr.appendChild(td_title);
          
          var td_delete = document.createElement('td');
          td_delete.setAttribute('class', 'table_small_col');
          
          var span_del = document.createElement('span');
          span_del.setAttribute('class', 'bwge_spider_delete_img');
          span_del.setAttribute('onclick', 'bwge_spider_remove_row("tbody_albums_galleries", event, this);');
          
          td_delete.appendChild(span_del);
          tr.appendChild(td_delete);
          
          tbody.appendChild(tr);
          counter++;
        }
        if (counter) {
          document.getElementById("table_albums_galleries").style.display = "block";
        }
        bwge_spider_sortt('tbody_albums_galleries');
        tb_remove();
      }
    </script>
   <div class="bwge"> 
    <div style="font-size: 14px; font-weight: bold;">
      <?php //echo __('This section allows you to add/edit album.', 'bwge_back'); ?>
     <a style="color: #00A0D2; text-decoration: none;" target="_blank" href="https://galleryecommerce.com/gallery-set-up/creating-editing-galleries/">Read More in User <?php echo __('Manual', 'bwge_back'); ?></a>
    </div> 
     <form class="bwge_form" method="post" action="admin.php?page=albums_bwge" style="width:99%;">
        <?php wp_nonce_field( 'albums_bwge', 'bwge_nonce' ); ?>
        <!--<span class="album-icon"></span>-->
        <h2><?php echo $page_title; ?></h2>
        <div >
          <input class="wd-btn wd-btn-primary wd-btn-icon wd-btn-save " type="submit" onclick="if(bwge_spider_check_required('name', 'Name')){return false;};bwge_spider_set_input_value('task', 'save')" value="<?php echo __('Save', 'bwge_back'); ?>" id="save_albums" />
          <input class="wd-btn wd-btn-primary wd-btn-icon wd-btn-apply" type="submit" onclick="if(bwge_spider_check_required('name', 'Name')){return false;};bwge_spider_set_input_value('task', 'apply')" value="<?php echo __('Apply', 'bwge_back'); ?>" />
          <input class="wd-btn wd-btn-primary wd-btn-icon wd-btn-cancel" type="submit" onclick="bwge_spider_set_input_value('task', 'cancel')" value="<?php echo __('Cancel', 'bwge_back'); ?>" />
        </div>
        <table style="clear:both;" class="bwge_edit_table">
          <tbody>
            <tr>
              <td class="bwge_spider_label"><label for="name"><?php echo __('Name:', 'bwge_back'); ?> <span style="color:#FF0000;">*</span> </label></td>
              <td><input type="text" id="name" name="name" value="<?php echo $row->name; ?>" size="39" /></td>
            </tr>
            <tr>
              <td class="bwge_spider_label"><label for="slug"><?php echo __('Slug:', 'bwge_back'); ?> </label></td>
              <td><input type="text" id="slug" name="slug" value="<?php echo $row->slug; ?>" size="39" /></td>
            </tr>
            <tr>
              <td class="bwge_spider_label"><label for="description"><?php echo __('Description:', 'bwge_back'); ?> </label></td>
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
              <td class="bwge_spider_label"><label><?php echo __('Author:', 'bwge_back'); ?> </label></td>
              <td><?php echo get_userdata($row->author)->display_name; ?></td>
            </tr>
            <tr>
              <td class="bwge_spider_label"><label for="published1"><?php echo __('Published:', 'bwge_back'); ?> </label></td>
              <td>
                <input type="radio" class="inputbox" id="published0" name="published" <?php echo (($row->published) ? '' : 'checked="checked"'); ?> value="0" >
                <label for="published0"><?php echo __('No', 'bwge_back'); ?></label>
                <input type="radio" class="inputbox" id="published1" name="published" <?php echo (($row->published) ? 'checked="checked"' : ''); ?> value="1" >
                <label for="published1"><?php echo __('Yes', 'bwge_back'); ?></label>
              </td>
            </tr>
            <tr>
              <td class="bwge_spider_label"><label for="url"><?php echo __('Preview image:', 'bwge_back'); ?> </label></td>
              <td>
              <?php 
              $query_url =  add_query_arg(array('action' => 'bwge_addImages', 'width' => '700', 'height' => '550', 'extensions' => 'jpg,jpeg,png,gif', 'callback' => 'bwge_add_preview_image'), admin_url('admin-ajax.php'));
              $query_url = wp_nonce_url( $query_url, 'bwge_addImages', 'bwge_nonce' );
              $query_url =  add_query_arg(array('TB_iframe' => '1'), $query_url );
              

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
            <tr>
              <td class="bwge_spider_label"><label for="content-add_media"><?php echo __('Albums And Galleries:', 'bwge_back'); ?> </label></td>
              <td>
              <?php 
                $query_url = add_query_arg(array('action' => 'addAlbumsGalleries_bwge', 'album_id' => $id, 'width' => '700', 'height' => '550', 'bwge_items_per_page'=>$per_page ), admin_url('admin-ajax.php'));
                $query_url = wp_nonce_url( $query_url, 'addAlbumsGalleries_bwge', 'bwge_nonce' );
                $query_url = add_query_arg(array('TB_iframe' => '1'), $query_url);

                
              ?>
                <a href="<?php echo $query_url; ?>" class="wd-btn wd-btn-primary thickbox thickbox-preview" id="content-add_media" title="<?php echo __("Add Images", 'bwge_back'); ?>" onclick="return false;" style="margin-bottom:5px;">
                  <?php echo __('Add Albums/Galleries', 'bwge_back'); ?>
                </a>              
                <?php $albums_galleries = $this->model->get_albums_galleries_rows_data($id) ?>
                <table id="table_albums_galleries" class="widefat bwge_spider_table" <?php echo (($albums_galleries) ? '' : 'style="display:none;"'); ?>>          
                  <tbody id="tbody_albums_galleries">
                    <?php
                    if ($albums_galleries) {
                      $hidden = "";
                      foreach($albums_galleries as $alb_gal) {
                        if ($alb_gal) {
                          ?>
                          <tr id="tr_<?php echo $alb_gal->id . ":" . $alb_gal->is_album . ":" . $alb_gal->alb_gal_id ?>" style="height:35px;">
                            <td class="connectedSortable table_small_col" title="Drag to re-order"><div class="handle"></div></td>
                            <td style="max-width:420px; min-width:400px;"><?php echo ($alb_gal->is_album ? 'Album: ' : 'Gallery: ') . $alb_gal->name; ?></td>
                            <td class="table_small_col">
                              <span class="bwge_spider_delete_img" onclick="bwge_spider_remove_row('tbody_albums_galleries', event, this)"/>
                            </td>
                          </tr>
                          <?php
                          $hidden .= $alb_gal->id . ":" . $alb_gal->is_album . ":" . $alb_gal->alb_gal_id . ",";
                        }
                      }
                    }
                    ?>
                  </tbody>
                </table>
                <input type="hidden" value="<?php echo isset($hidden) ? $hidden : ''; ?>" id="albums_galleries" name="albums_galleries"/>
              </td>
            </tr>          
          </tbody>
        </table>
        <input id="task" name="task" type="hidden" value="" />
        <input id="current_id" name="current_id" type="hidden" value="<?php echo $row->id; ?>" />
        <script>
          jQuery(window).load(function() {
            bwge_spider_reorder_items('tbody_albums_galleries');
          });
          <?php
          if ($row->preview_image == '') {
            ?>
            bwge_spider_remove_url('button_preview_image', 'preview_image', 'delete_preview_image', 'img_preview_image');
            <?php
          }
          ?>
        </script>
      </form>
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