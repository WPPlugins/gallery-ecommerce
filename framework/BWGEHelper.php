<?php

class BWGEHelper {
  ////////////////////////////////////////////////////////////////////////////////////////
  // Events                                                                             //
  ////////////////////////////////////////////////////////////////////////////////////////
  ////////////////////////////////////////////////////////////////////////////////////////
  // Constants                                                                          //
  ////////////////////////////////////////////////////////////////////////////////////////
  ////////////////////////////////////////////////////////////////////////////////////////
  // Variables                                                                          //
  ////////////////////////////////////////////////////////////////////////////////////////
  ////////////////////////////////////////////////////////////////////////////////////////
  // Constructor & Destructor                                                           //
  ////////////////////////////////////////////////////////////////////////////////////////
  public function __construct() {
  }


  ////////////////////////////////////////////////////////////////////////////////////////
  // Public Methods                                                                     //
  ////////////////////////////////////////////////////////////////////////////////////////
  ////////////////////////////////////////////////////////////////////////////////////////
  // Getters & Setters                                                                  //
  ////////////////////////////////////////////////////////////////////////////////////////
  public static function get($key, $default_value = '') {

	if (isset($_POST[$key])) {
	  $value = $_POST[$key];
	}
	elseif (isset($_GET[$key])) {
	  $value = $_GET[$key];
	}	
	else {
	  $value = '';
	}
	if (!$value) {
	  $value = $default_value;
	}
	return esc_html($value);
  }
  
  public static function get_model($model_name = "", $frontend = false, $params = false){
	
	if($model_name == ""){	
		$model_name = BWGEHelper::get('page');
	}
	if($frontend == false){	
        require_once(WD_BWGE_DIR . '/admin/models/BWGEModel.php');
		require_once(WD_BWGE_DIR . '/admin/models/BWGEModel' . ucfirst(strtolower($model_name)) . '_bwge.php');
		$model_class = 'BWGEModel' . ucfirst(strtolower($model_name)). '_bwge';
		$model = new $model_class();		
	}
	else{
		require_once(WD_BWGE_DIR . '/frontend/models/BWGEModelFrontend.php');
		require_once(WD_BWGE_DIR . '/frontend/models/BWGEModel' . ucfirst(strtolower($model_name)) . '.php');
		$model_class = 'BWGEModel' . ucfirst(strtolower($model_name));	
		$model = new $model_class($params);		
	}
	
	return $model;
  }
  
  public static function search($search_by, $search_value, $form_id) {
    ?>
    <div class="alignleft actions" style="clear:both;">
      <div class="alignleft" style="">
        <label for="search_value" style="font-size:14px;  display:inline-block;"><?php echo $search_by; ?>:</label>
        <input type="text" id="search_value" name="search_value"  onkeypress="return bwgeCheckSearchKey(event, this);" value="<?php echo esc_html($search_value); ?>" style="width: 287px;" />
      </div>
      <div class="alignleft actions wd-clear">
        <input type="button" value="" onclick="bwgeSearch()" class="wd-search-btn">
        <input type="button" value="" onclick="bwgeReset()" class="wd-reset-btn">
      </div>
    </div>
    <?php
  }
  
  
  public static function html_page_nav($count_items, $pager, $page_number, $form_id, $items_per_page = 20) {
    $limit = $items_per_page;
    if ($count_items) {
      if ($count_items % $limit) {
        $items_county = ($count_items - $count_items % $limit) / $limit + 1;
      }
      else {
        $items_county = ($count_items - $count_items % $limit) / $limit;
      }
    }
    else {
      $items_county = 1;
    }
    if (!$pager) {
    ?>
    <script type="text/javascript">
      var items_county = <?php echo $items_county; ?>;
      function bwge_spider_page(x, y) {       
        switch (y) {
          case 1:
            if (x >= items_county) {
              document.getElementById('page_number').value = items_county;
            }
            else {
              document.getElementById('page_number').value = x + 1;
            }
            break;
          case 2:
            document.getElementById('page_number').value = items_county;
            break;
          case -1:
            if (x == 1) {
              document.getElementById('page_number').value = 1;
            }
            else {
              document.getElementById('page_number').value = x - 1;
            }
            break;
          case -2:
            document.getElementById('page_number').value = 1;
            break;
          default:
            document.getElementById('page_number').value = 1;
        }
        document.getElementById('<?php echo $form_id; ?>').submit();
      }
      function check_enter_key(e, that) {
        var key_code = (e.keyCode ? e.keyCode : e.which);
        if (key_code == 13) { /*Enter keycode*/
          if (jQuery(that).val() >= items_county) {
           document.getElementById('page_number').value = items_county;
          }
          else {
           document.getElementById('page_number').value = jQuery(that).val();
          }
          document.getElementById('<?php echo $form_id; ?>').submit();
        }
        return true;
      }
    </script>
    <?php } ?>
    <div class="tablenav-pages">
      <span class="displaying-num">
        <?php
        if ($count_items != 0) {
          echo $count_items; ?> item<?php echo (($count_items == 1) ? '' : 's');
        }
        ?>
      </span>
      <?php
      if ($count_items > $items_per_page) {
        $first_page = "first-page";
        $prev_page = "prev-page";
        $next_page = "next-page";
        $last_page = "last-page";
        if ($page_number == 1) {
          $first_page = "first-page disabled";
          $prev_page = "prev-page disabled";
          $next_page = "next-page";
          $last_page = "last-page";
        }
        if ($page_number >= $items_county) {
          $first_page = "first-page ";
          $prev_page = "prev-page";
          $next_page = "next-page disabled";
          $last_page = "last-page disabled";
        }
      ?>
      <span class="pagination-links">
        <a class="<?php echo $first_page; ?>" title="Go to the first page" href="javascript:bwge_spider_page(<?php echo $page_number; ?>,-2);">«</a>
        <a class="<?php echo $prev_page; ?>" title="Go to the previous page" href="javascript:bwge_spider_page(<?php echo $page_number; ?>,-1);">‹</a>
        <span class="paging-input">
          <span class="total-pages">
          <input class="current_page" id="current_page" name="current_page" value="<?php echo $page_number; ?>" onkeypress="return check_enter_key(event, this)" title="Go to the page" type="text" size="1" />
        </span> of 
        <span class="total-pages">
            <?php echo $items_county; ?>
          </span>
        </span>
        <a class="<?php echo $next_page ?>" title="Go to the next page" href="javascript:bwge_spider_page(<?php echo $page_number; ?>,1);">›</a>
        <a class="<?php echo $last_page ?>" title="Go to the last page" href="javascript:bwge_spider_page(<?php echo $page_number; ?>,2);">»</a>
        <?php
      }
      ?>
      </span>
    </div>
    <?php if (!$pager) { ?>
    <input type="hidden" id="page_number"  name="page_number" value="<?php echo ((isset($_POST['page_number'])) ? (int) $_POST['page_number'] : 1); ?>" />
    <input type="hidden" id="search_or_not" name="search_or_not" value="<?php echo ((isset($_POST['search_or_not'])) ? esc_html($_POST['search_or_not']) : ''); ?>"/>
    <?php
    }
  }
  
  public static function bwge_redirect($url){
	?>
		<script>
			window.location = "<?php echo $url; ?>";
		</script>	
	<?php	
	exit;
  }
  
  public static function message($message, $type) {
    echo '<div style="width:99%"><div class="' . $type . '"><p><strong>' . $message . '</strong></p></div></div>';
  }
  
 public static function get_pages_by_title($page_title){
		global $wpdb;
		$query = $wpdb->prepare( "
            SELECT ID
            FROM $wpdb->posts
            WHERE post_title = %s
            AND post_type = 'bwge_ecommerce_page' AND post_status = 'publish'
        ", $page_title );
		
		$pages = $wpdb->get_col( $query );
		
		return $pages;
		
	}
   
 	public static function get_pages(){
		global $wpdb;
		$query = "SELECT * FROM $wpdb->posts WHERE  (post_type = 'bwge_ecommerce_page' OR post_type = 'page') AND post_status = 'publish'  ORDER BY id";

		$pages = $wpdb->get_results( $query );
		
		return $pages;
		
	} 

    public static function upgrade_pro($text = false){
    ?>
        <div class="bwge_upgrade wd-clear" >
            <div class="wd-right">
                <a href="https://galleryecommerce.com/plans-and-pricing/" target="_blank">
                    <div class="wd-table">
                        <div class="wd-cell wd-cell-valign-middle">
                            <?php _e("Upgrade to paid version", "bwge"); ?>
                        </div>
                         
                        <div class="wd-cell wd-cell-valign-middle">
                            <img src="<?php echo WD_BWGE_URL; ?>/images/web-dorado.png" >
                        </div>
                    </div>     
                </a>                  
            </div>
        </div>
        <?php if($text){
        ?>
            <div class="wd-text-right wd-row" style="color: #15699F; font-size: 20px; margin-top:10px; padding:0px 15px;">
                <?php echo sprintf(__("This is FREE version, Customizing %s is available only in the PAID version.","bwge"), $text);?>
            </div>
        <?php
        }

    }    
 
  ////////////////////////////////////////////////////////////////////////////////////////
  // Private Methods                                                                    //
  ////////////////////////////////////////////////////////////////////////////////////////
  ////////////////////////////////////////////////////////////////////////////////////////
  // Listeners                                                                          //
  ////////////////////////////////////////////////////////////////////////////////////////
}