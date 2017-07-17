<?php

class BWGEViewAddAlbumsGalleries_bwge {
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
    $album_id = ((isset($_GET['album_id'])) ? esc_html(stripslashes($_GET['album_id'])) : ((isset($_POST['album_id'])) ? esc_html(stripslashes($_POST['album_id'])) : ''));
    $rows_data = $this->model->get_rows_data($album_id);
    $page_nav = $this->model->page_nav($album_id);
    $search_value = ((isset($_POST['search_value'])) ? esc_html(stripslashes($_POST['search_value'])) : '');
    $asc_or_desc = ((isset($_POST['asc_or_desc'])) ? esc_html(stripslashes($_POST['asc_or_desc'])) : 'asc');
    $order_by = (isset($_POST['order_by']) ? esc_html(stripslashes($_POST['order_by'])) : 'name');
    $order_class = 'manage-column column-title sorted ' . $asc_or_desc;
    $per_page = $this->model->per_page();
	$pager = 0;
    wp_print_scripts('jquery');
    wp_print_scripts('wp-pointer');
    ?>
    <?php if (get_bloginfo('version') >= '4.5') { ?>
    <link media="all" type="text/css" href="<?php echo get_admin_url(); ?>load-styles.php?c=1&dir=ltr&load%5B%5D=dashicons,admin-bar,common,forms,admin-menu,dashboard,list-tables,edit,revisions,media,themes,about,nav-menus,widgets,site-icon,&load%5B%5D=l10n,buttons,wp-auth-check,media-views,wp-pointer" rel="stylesheet">
    <?php } 
    else{
    ?>
    <link media="all" type="text/css" href="<?php echo get_admin_url(); ?>load-styles.php?c=1&amp;dir=ltr&amp;load=admin-bar,wp-admin,dashicons,buttons,wp-auth-check,wp-pointer" rel="stylesheet">
    <?php 
    }
    if (get_bloginfo('version') < '3.9') { ?>
    <link media="all" type="text/css" href="<?php echo get_admin_url(); ?>css/colors<?php echo ((get_bloginfo('version') < '3.8') ? '-fresh' : ''); ?>.min.css" id="colors-css" rel="stylesheet">
    <?php } ?>
    <link media="all" type="text/css" href="<?php echo WD_BWGE_URL . '/css/bwge_tables.css?ver='.wd_bwge_version(); ?>" id="bwge_spider_audio_player_tables-css" rel="stylesheet">
    <link media="all" type="text/css" href="<?php echo WD_BWGE_URL . '/css/bwge_ecommerce.css?ver='.wd_bwge_version(); ?>" id="bwge_tables-css" rel="stylesheet">
    <script src="<?php echo WD_BWGE_URL . '/js/bwge.js?ver='.wd_bwge_version(); ?>" type="text/javascript"></script>
   <div class="bwge">
     <form class="wrap wp-core-ui bwge_form" id="albums_galleries_form" method="post" action="<?php echo add_query_arg(array('action' => 'addAlbumsGalleries_bwge', 'width' => '700', 'height' => '550', 'callback' => 'bwge_add_items', 'bwge_items_per_page'=>$per_page , 'TB_iframe' => '1'), admin_url('admin-ajax.php')); ?>" style="width:99%; margin: 0 auto;">
        <?php wp_nonce_field( 'addAlbumsGalleries_bwge', 'bwge_nonce' ); ?>
        <h2>
          <span><?php echo __('Albums/Galleries', 'bwge_back'); ?></span>
          <a href="" class="wd-btn wd-btn-primary wd-btn-icon wd-btn-add thickbox thickbox-preview add_albums" id="content-add_media" title="Add Album/Gallery" onclick="bwge_spider_get_items(event);" >
            <?php echo __('Add', 'bwge_back'); ?>
          </a>        
        </h2>

        <div class="tablenav top wd-row">
          <?php
          BWGELibrary::search(__('Name','bwge_back'), $search_value, 'albums_galleries_form');
          BWGELibrary::html_page_nav($page_nav['total'],$pager++, $page_nav['limit'], 'albums_galleries_form', $per_page);
          ?>
        </div>
        <table class="wp-list-table widefat fixed pages bwge_list_table">
          <thead>
            <tr class="bwge_alternate">
              <th class="manage-column column-cb check-column table_small_col"><input id="check_all" type="checkbox" style="margin:0;" /></th>
              <th class="table_small_col <?php if ($order_by == 'id') {echo $order_class;} ?>">
                <a onclick="bwge_spider_set_input_value('order_by', 'id');
                            bwge_spider_set_input_value('asc_or_desc', '<?php echo ((isset($_POST['asc_or_desc']) && isset($_POST['order_by']) && (esc_html(stripslashes($_POST['order_by'])) == 'id') && esc_html(stripslashes($_POST['asc_or_desc'])) == 'asc') ? 'desc' : 'asc'); ?>');
                            bwge_spider_form_submit(event, 'albums_galleries_form')" href="">
                  <span>ID</span><span class="sorting-indicator"></span>
                </a>
              </th>
              <th class="table_medium_col_uncenter <?php if ($order_by == 'is_album') {echo $order_class;} ?>">
                <a onclick="bwge_spider_set_input_value('task', '');
                            bwge_spider_set_input_value('order_by', 'is_album');
                            bwge_spider_set_input_value('asc_or_desc', '<?php echo ((isset($_POST['asc_or_desc']) && isset($_POST['order_by']) && (esc_html(stripslashes($_POST['order_by'])) == 'is_album') && esc_html(stripslashes($_POST['asc_or_desc'])) == 'asc') ? 'desc' : 'asc'); ?>');
                            bwge_spider_form_submit(event, 'albums_galleries_form')" href="">
                  <span><?php echo __('Type', 'bwge_back'); ?></span><span class="sorting-indicator"></span>
                </a>
              </th>
              <th class="<?php if ($order_by == 'name') {echo $order_class;} ?>">
                <a onclick="bwge_spider_set_input_value('order_by', 'name');
                            bwge_spider_set_input_value('asc_or_desc', '<?php echo ((isset($_POST['asc_or_desc']) && isset($_POST['order_by']) && (esc_html(stripslashes($_POST['order_by'])) == 'name') && esc_html(stripslashes($_POST['asc_or_desc'])) == 'asc') ? 'desc' : 'asc'); ?>');
                            bwge_spider_form_submit(event, 'albums_galleries_form')" href="">
                  <span><?php echo __('Name', 'bwge_back'); ?></span><span class="sorting-indicator"></span>
                </a>
              </th> 
            </tr>
          </thead>
          <tbody id="tbody_albums_galleries">
            <?php
            if ($rows_data) {
              $iterator = 0;
              foreach ($rows_data as $row_data) {
                $alternate = $iterator%2 == 0 ? '' : 'class="bwge_alternate"';
                ?>
                <tr id="tr_<?php echo $iterator; ?>" <?php echo $alternate; ?>>
                  <td class="table_small_col check-column"><input id="check_<?php echo $iterator; ?>" name="check_<?php echo $iterator; ?>" type="checkbox" /></td>
                  <td id="id_<?php echo $iterator; ?>" class="table_small_col"><?php echo $row_data->id; ?></td>
                  <td id="url_<?php echo $iterator; ?>" class="table_medium_col_uncenter"><?php echo ($row_data->is_album ? "Album" : "Gallery") ; ?></td>
                  <td>
                    <a onclick="window.parent.bwge_add_items(['<?php echo $row_data->id?>'],['<?php echo htmlspecialchars(addslashes($row_data->name))?>'], ['<?php echo htmlspecialchars(addslashes($row_data->is_album))?>'])" id="a_<?php echo $iterator; ?>" style="cursor:pointer;">
                      <?php echo $row_data->name?>
                    </a>
                  </td>
                </tr>
                <?php
                $iterator++;
              }
            }
            ?>
          </tbody>
        </table>
        <div class="tablenav bottom">
          <?php
          BWGELibrary::html_page_nav($page_nav['total'],$pager++, $page_nav['limit'], 'albums_galleries_form', $per_page);
          ?>
        </div>
        <input id="asc_or_desc" name="asc_or_desc" type="hidden" value="asc" />
        <input id="order_by" name="order_by" type="hidden" value="<?php echo $order_by; ?>" />
        <input id="album_id" name="album_id" type="hidden" value="<?php echo $album_id; ?>" />
      </form>
    </div>
    <script src="<?php echo get_admin_url(); ?>load-scripts.php?c=1&load%5B%5D=common,admin-bar" type="text/javascript"></script>
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