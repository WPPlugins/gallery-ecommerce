<?php

class BWGEViewUninstall_bwge {
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
    global $wpdb;
    $prefix = $wpdb->prefix;
    ?>
    <div class="bwge">
      <form class="bwge_form" method="post" action="admin.php?page=uninstall_bwge" style="width:99%;">
        <?php wp_nonce_field( 'uninstall_bwge', 'bwge_nonce' ); ?>
        <div class="wrap">
          <!--<span class="uninstall_icon"></span>-->
          <h2><?php echo __('Uninstall Gallery Ecommerce', 'bwge_back'); ?></h2>
          <p>
            <?php echo __('Deactivating Gallery Ecommerce plugin does not remove any data that may have been created. To completely remove this plugin, you can uninstall it here.', 'bwge_back'); ?>
          </p>
          <p style="color: red;">
            <strong><?php echo __('WARNING:', 'bwge_back'); ?></strong>
            <?php echo __("Once uninstalled, this can't be undone. You should use a Database Backup plugin of WordPress to back up all the data first.", 'bwge_back'); ?>
          </p>
          <p style="color: red">
            <strong><?php echo __('The following Database Tables will be deleted:', 'bwge_back'); ?></strong>
          </p>
          <table class="widefat">
            <thead>
              <tr>
                <th><?php echo __('Database Tables', 'bwge_back'); ?></th>
              </tr>
            </thead>
            <tr>
              <td valign="top">
                <ol>
                    <li><?php echo $prefix; ?>bwge_album</li>
                    <li><?php echo $prefix; ?>bwge_album_gallery</li>
                    <li><?php echo $prefix; ?>bwge_ecommerceoptions</li>
                    <li><?php echo $prefix; ?>bwge_gallery</li>
                    <li><?php echo $prefix; ?>bwge_image</li>
                    <li><?php echo $prefix; ?>bwge_image_comment</li>
                    <li><?php echo $prefix; ?>bwge_image_rate</li>
                    <li><?php echo $prefix; ?>bwge_image_tag</li>
                    <li><?php echo $prefix; ?>bwge_option</li>
                    <li><?php echo $prefix; ?>bwge_orders</li>
                    <li><?php echo $prefix; ?>bwge_order_images</li>
                    <li><?php echo $prefix; ?>bwge_parameters</li>
                    <li><?php echo $prefix; ?>bwge_payment_systems</li>
                    <li><?php echo $prefix; ?>bwge_pricelists</li>
                    <li><?php echo $prefix; ?>bwge_pricelist_items</li>
                    <li><?php echo $prefix; ?>bwge_pricelist_parameters</li>
                    <li><?php echo $prefix; ?>bwge_theme</li>
                    <li><?php echo $prefix; ?>bwge_shortcode</li>
                </ol>
              </td>
            </tr>
            <tfoot>
              <tr>
                <th>
                  <input type="checkbox" name="bwge_delete_files" id="bwge_delete_files" style="vertical-align: middle;" />
                  <label for="bwge_delete_files">&nbsp;<?php echo __('Delete the folder containing uploaded images.', 'bwge_back'); ?></label>
                </th>
              </tr>
            </tfoot>
          </table>
          <p style="text-align: center;">
            <?php echo __('Do you really want to uninstall Gallery Ecommerce?', 'bwge_back'); ?>
          </p>
          <p style="text-align: center;">
            <input type="checkbox" name="Photo Gallery" id="check_yes" value="yes" />&nbsp;<label for="check_yes"><?php echo __('Yes', 'bwge_back'); ?></label>
          </p>
          <p style="text-align: center;">
            <input type="submit" value="UNINSTALL" class="wd-btn wd-btn-primary" onclick="if (check_yes.checked) { 
                                                                                      if (confirm('<?php echo addslashes(__('You are About to Uninstall Gallery Ecommerce from WordPress. This Action Is Not Reversible.', 'bwge_back')); ?>')) {
                                                                                          bwge_spider_set_input_value('task', 'uninstall');
                                                                                      } else {
                                                                                          return false;
                                                                                      }
                                                                                    }
                                                                                    else {
                                                                                      return false;
                                                                                    }" />
          </p>
        </div>
        <input id="task" name="task" type="hidden" value="" />
      </form>
    </div>
  <?php
  }

  public function uninstall() {
    $flag = TRUE;
    if (isset($_POST['bwge_delete_files'])) {
      function delfiles($del_file) {
        if (is_dir($del_file)) {
          $del_folder = scandir($del_file);
          foreach ($del_folder as $file) {
            if ($file != '.' and $file != '..') {
              delfiles($del_file . '/' . $file);
            }
          }
          if (!rmdir($del_file)) {
            $flag = FALSE;
          }
        }
        else {
          if (!unlink($del_file)) {
            $flag = FALSE;
          }
        }
      }
      global $WD_BWGE_UPLOAD_DIR;
      if ($WD_BWGE_UPLOAD_DIR) {
        if (is_dir(ABSPATH . $WD_BWGE_UPLOAD_DIR)) {
          delfiles(ABSPATH . $WD_BWGE_UPLOAD_DIR);
        }
      }
    }
    global $wpdb;
    $this->model->delete_db_tables();
    $prefix = $wpdb->prefix;
    $deactivate_url = wp_nonce_url('plugins.php?action=deactivate&amp;plugin='.WD_BWGE_NAME.'/gallery-ecommerce.php', 'deactivate-plugin_'.WD_BWGE_NAME.'/gallery-ecommerce.php');
    ?>
    <div class="bwge">
      <div id="message" class="updated fade">
        <p><?php echo __('The following Database Tables successfully deleted:', 'bwge_back'); ?></p>
        <p><?php echo $prefix; ?>bwge_album,</p>
        <p><?php echo $prefix; ?>bwge_album_gallery,</p>
        <p><?php echo $prefix; ?>bwge_ecommerceoptions</p>      
        <p><?php echo $prefix; ?>bwge_gallery,</p>
        <p><?php echo $prefix; ?>bwge_image,</p>
        <p><?php echo $prefix; ?>bwge_image_comment,</p>
        <p><?php echo $prefix; ?>bwge_image_rate,</p>
        <p><?php echo $prefix; ?>bwge_image_tag,</p>
        <p><?php echo $prefix; ?>bwge_option,</p>
        <p><?php echo $prefix; ?>bwge_orders</p>
        <p><?php echo $prefix; ?>bwge_order_images</p>
        <p><?php echo $prefix; ?>bwge_parameters</p>
        <p><?php echo $prefix; ?>bwge_payment_systems</p>
        <p><?php echo $prefix; ?>bwge_pricelists</p>
        <p><?php echo $prefix; ?>bwge_pricelist_items</p>
        <p><?php echo $prefix; ?>bwge_pricelist_parameters</p>
        <p><?php echo $prefix; ?>bwge_theme</p>
        <p><?php echo $prefix; ?>bwge_shortcode</p>      
        <p><?php echo $prefix; ?>bwge_theme,</p>
        <p><?php echo $prefix; ?>bwge_shortcode.</p>
      </div>
      <?php
      if (isset($_POST['bwge_delete_files'])) {
        ?>
      <div class="<?php echo ($flag) ? 'updated' : 'error'?>">
        <p><?php echo ($flag) ? 'The folder was successfully deleted.' : 'An error occurred when deleting the folder.'?></p>
      </div>
        <?php
      }
      ?>
      <div class="wrap">
        <h2><?php echo __('Uninstall Gallery Ecommerce', 'bwge_back'); ?></h2>
        <p><strong><a href="<?php echo $deactivate_url; ?>"><?php echo __('Click Here', 'bwge_back'); ?></a> <?php echo __('To Finish the Uninstallation and Gallery Ecommerce will be Deactivated Automatically.', 'bwge_back'); ?></strong></p>
        <input id="task" name="task" type="hidden" value="" />
      </div>
    </div>
  <?php
   delete_user_meta(get_current_user_id(), 'bwge_galery_ecommerce');
   wp_die();
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