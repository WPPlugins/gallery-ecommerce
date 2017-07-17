<?php

class BWGEViewOrders_bwge extends BWGEView{

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
	////////////////////////////////////////////////////////////////////////////////////////
	// Public Methods                                                                     //
	////////////////////////////////////////////////////////////////////////////////////////
	public function display() {
		$rows = $this->model->get_rows();
		$page_nav = $this->model->page_nav();
		$search_email = ((isset($_POST['search_email'])) ? esc_html(stripslashes($_POST['search_email'])) : '');
		$search_status = ((isset($_POST['search_status'])) ? esc_html(stripslashes($_POST['search_status'])) : '');
		$start_date = ((isset($_POST['start_date'])) ? esc_html(stripslashes($_POST['start_date'])) : '');
		$end_date = ((isset($_POST['end_date'])) ? esc_html(stripslashes($_POST['end_date'])) : '');
		$asc_or_desc = ((isset($_POST['asc_or_desc'])) ? esc_html(stripslashes($_POST['asc_or_desc'])) : 'asc');
		$order_by = (isset($_POST['order_by']) ? esc_html(stripslashes($_POST['order_by'])) : 'id');
		$order_class = 'manage-column column-title sorted ' . $asc_or_desc;
		
		$per_page = $this->model->per_page();
		$pager = 0;	
        $statuses = array(""=>"Select","pending"=>"Pending","confirmed"=>"Confirmed","cancelled"=>"Cancelled","refunded"=>"Refunded");
        $options = $this->model->get_options();	
    ?>
        <script src="<?php echo WD_BWGE_URL?>/js/ecommerce/calendar.js"></script>
		<script src="<?php echo WD_BWGE_URL?>/js/ecommerce/calendar_function.js"></script>
        <link rel="stylesheet" type="text/css" href="<?php echo WD_BWGE_URL?>/css/calendar-jos.css">
		<form method="post" action="" id="adminForm">
            <?php wp_nonce_field('nonce_bwge', 'nonce_bwge'); ?>
			<div class="bwge">
              <div style="font-size: 14px; font-weight: bold;">
                <?php echo __('This section allows you to manage orders.', 'bwge_back'); ?>
                <a style="color: #00A0D2; text-decoration: none;" target="_blank" href="https://galleryecommerce.com/gallery-ecommerce-set-up/orders/"><?php echo __('Read More in User Manual', 'bwge_back'); ?></a>
              </div>              
				<h2>
					<span><?php _e("Orders","bwge_back"); ?></span>
				</h2>
				<div class="wd-clear">
					<div class="wd-left">						
                        <div class="alignleft actions" style="clear:both;">
                          <div class="alignleft" style="">
                                <label for="search_status" style="font-size:14px;  display:inline-block;"><?php _e("Status","bwge_back") ; ?>:</label>                           
                                <select name="search_status" id="search_status" style="vertical-align: bottom;width: 161px;" onchange="this.form.submit();">
                                    <?php
                                        foreach($statuses as $status_value => $status_name){
                                            $selected = $search_status == $status_value ? "selected" : "";
                                            echo '<option value="'.$status_value.'" '. $selected.'>'.$status_name.'</option>';
                                        }
                                    ?>
                                    
                                </select>
             
                                <label for="start_date"><?php _e("From","bwge_back"); ?>:</label>
                                <input id="start_date"  type="text" value="<?php echo $start_date; ?>" name="start_date" />
                                <input class="calendar_button" type="reset" onclick="return showCalendar('start_date','%Y-%m-%d');" value="" />
                                <label for="end_date"><?php _e("To","bwge_back"); ?>:</label>
                                <input id="end_date"  type="text" value="<?php echo $end_date; ?>" name="end_date" />
                                <input class="calendar_button" type="reset" onclick="return showCalendar('end_date','%Y-%m-%d');" value="" />
                                <label for="search_email" style="font-size:14px;  display:inline-block;"><?php _e("Email","bwge_back") ; ?>:</label>
                                <input type="text" id="search_email" name="search_email"  onkeypress="return bwgeCheckSearchKey(event, this);" value="<?php echo esc_html($search_email); ?>" style="width: 200px;" />
                          </div>
                          <div class="alignleft actions wd-clear">
                            <input type="button" value="" onclick="bwgeSearch()" class="wd-search-btn">
                            <input type="button" value="" onclick="document.getElementById('search_email').value='';document.getElementById('search_status').value=''; document.getElementById('start_date').value=''; document.getElementById('end_date').value='';this.form.submit();" class="wd-reset-btn">
                          </div>
                        </div>					
					</div>
					<div class="buttons_div wd-right" style="text-align:right;margin-bottom:15px ;">
			
						<input class="wd-btn wd-btn-primary-red wd-btn-icon wd-btn-delete" type="submit" onclick="if (confirm('<?php _e("Do you want to delete selected items?","bwge_back"); ?>')) {
																	   bwgeFormInputSet('task', 'remove');
																	 } else {
																   return false;
																 }" value="<?php _e("Delete","bwge_back"); ?>" />
					</div>
				</div>
				<div class="tablenav top">
					<?php						
						BWGEHelper::html_page_nav($page_nav['total'], $pager++, $page_nav['limit'], 'adminForm', $per_page);
					?>
				</div>					
				<table class="wp-list-table widefat fixed posts bwge_list_table">
					<thead>
						<tr class="bwge_alternate">
							<th scope="col" id="cb" class="manage-column column-cb check-column" style="" >
								<label class="screen-reader-text" for="cb-select-all-1"><?php _e("Select All","bwge_back"); ?></label>
								<input id="cb-select-all-1" type="checkbox">
							</th>

							<th class="col <?php if ($order_by == 'id') {echo $order_class;} ?>" width="5%">
								<a onclick="bwgeFormInputSet('order_by', 'id');
											bwgeFormInputSet('asc_or_desc', '<?php echo ((isset($_POST['asc_or_desc']) && isset($_POST['order_by']) && (esc_html(stripslashes($_POST['order_by'])) == 'id') && esc_html(stripslashes($_POST['asc_or_desc'])) == 'asc') ? 'desc' : 'asc'); ?>');
											document.getElementById('adminForm').submit();return false;" href="">
								  <span>ID</span><span class="sorting-indicator"></span>
								</a>
							</th>
							<th  class="col <?php if ($order_by == 'billing_data_email') {echo $order_class;} ?>"  >
								<a onclick="bwgeFormInputSet('order_by', 'billing_data_email');
											bwgeFormInputSet('asc_or_desc', '<?php echo ((isset($_POST['asc_or_desc']) && isset($_POST['order_by']) && (esc_html(stripslashes($_POST['order_by'])) == 'billing_data_email') && esc_html(stripslashes($_POST['asc_or_desc'])) == 'asc') ? 'desc' : 'asc'); ?>');
											document.getElementById('adminForm').submit();return false;" href="">
								  <span><?php _e("Email","bwge_back"); ?></span><span class="sorting-indicator"></span>
								</a>
							</th>							
							<th class="col" >	
								  <span><?php _e("Name","bwge_back"); ?></span><span class="sorting-indicator"></span>								
							</th>	
							<th class="col" >	
								  <span><?php _e("Pricelist","bwge_back"); ?></span><span class="sorting-indicator"></span>								
							</th>								
							<th class="col <?php if ($order_by == 'billing_data_name') {echo $order_class;} ?>">
								<a onclick="bwgeFormInputSet('order_by', 'billing_data_name');
											bwgeFormInputSet('asc_or_desc', '<?php echo ((isset($_POST['asc_or_desc']) && isset($_POST['order_by']) && (esc_html(stripslashes($_POST['order_by'])) == 'billing_data_name') && esc_html(stripslashes($_POST['asc_or_desc'])) == 'asc') ? 'desc' : 'asc'); ?>');
											document.getElementById('adminForm').submit();return false;" href="">
								  <span><?php _e("User","bwge_back"); ?></span><span class="sorting-indicator"></span>
								</a>
							</th>							

							<th class="col <?php if ($order_by == 'checkout_date') {echo $order_class;} ?>">
								<a onclick="bwgeFormInputSet('order_by', 'checkout_date');
											bwgeFormInputSet('asc_or_desc', '<?php echo ((isset($_POST['asc_or_desc']) && isset($_POST['order_by']) && (esc_html(stripslashes($_POST['order_by'])) == 'checkout_date') && esc_html(stripslashes($_POST['asc_or_desc'])) == 'asc') ? 'desc' : 'asc'); ?>');
											document.getElementById('adminForm').submit();return false;" href="">
								  <span><?php _e("Date","bwge_back"); ?></span><span class="sorting-indicator"></span>
								</a>
							</th>							
			
							<th class="col <?php if ($order_by == 'payment_method') {echo $order_class;} ?>">
								<a onclick="bwgeFormInputSet('order_by', 'payment_method');
											bwgeFormInputSet('asc_or_desc', '<?php echo ((isset($_POST['asc_or_desc']) && isset($_POST['order_by']) && (esc_html(stripslashes($_POST['order_by'])) == 'payment_method') && esc_html(stripslashes($_POST['asc_or_desc'])) == 'asc') ? 'desc' : 'asc'); ?>');
											document.getElementById('adminForm').submit();return false;" href="">
								  <span><?php _e("Payment method","bwge_back"); ?></span><span class="sorting-indicator"></span>
								</a>
							</th>								
							<th class="col <?php if ($order_by == 'status') {echo $order_class;} ?>">
								<a onclick="bwgeFormInputSet('order_by', 'status');
											bwgeFormInputSet('asc_or_desc', '<?php echo ((isset($_POST['asc_or_desc']) && isset($_POST['order_by']) && (esc_html(stripslashes($_POST['order_by'])) == 'status') && esc_html(stripslashes($_POST['asc_or_desc'])) == 'asc') ? 'desc' : 'asc'); ?>');
											document.getElementById('adminForm').submit();return false;" href="">
								  <span><?php _e("Status","bwge_back"); ?></span><span class="sorting-indicator"></span>
								</a>
							</th>	

							<th class="col <?php if ($order_by == 'total') {echo $order_class;} ?>">
								<a onclick="bwgeFormInputSet('order_by', 'total');
											bwgeFormInputSet('asc_or_desc', '<?php echo ((isset($_POST['asc_or_desc']) && isset($_POST['order_by']) && (esc_html(stripslashes($_POST['order_by'])) == 'total') && esc_html(stripslashes($_POST['asc_or_desc'])) == 'asc') ? 'desc' : 'asc'); ?>');
											document.getElementById('adminForm').submit();return false;" href="">
								  <span><?php _e("Price","bwge_back"); ?></span><span class="sorting-indicator"></span>
								</a>
							</th>								
						</tr>
					</thead>

					<tbody id="the-list" >
						<?php 
                            $total = 0;                      
							if(empty($rows ) == false){
								$iterator = 0;
								foreach($rows as $row){
									$alternate = $iterator%2 == 0 ? '' : 'class="bwge_alternate"';									
						?>
									<tr id="tr_<?php echo $iterator; ?>" <?php echo $alternate; ?>>
										<th scope="row" class="check-column">
											<input type="checkbox" name="ids[]" value="<?php echo $row->id;?>">
										</th>
										<td class="title column-id">
											<?php echo $row->id;?>
										</td>
										<td class="title column-id">
											<a href="<?php echo "admin.php?page=orders_bwge&task=edit&id=".$row->id ;?>">
												<?php echo $row->billing_data_email;?>
											</a>
										</td>
										
										<td class="title column-id">
											<a href="<?php echo "admin.php?page=orders_bwge&task=edit&id=".$row->id ;?>">
											<?php
												foreach($row->order_images as $order_image){
													echo "<div>". $order_image->image_name ."</div>";
												}
											?>
											</a>
										</td>
										<td class="title column-id">
											<?php echo $row->pricelist_name;?>
										</td>
								
										<td class="title column-id">
											<a href="<?php echo "admin.php?page=orders_bwge&task=edit&id=".$row->id ;?>"><?php echo $row->billing_data_name;?></a>
										</td>										
										<td class="title column-date">
											<?php echo $row->checkout_date;?>
										</td>
										<td class="title column-payment_method">
											<?php echo $row->payment_method;?>
										</td>
										<td class="title column-status">
											<?php echo ucfirst($row->status);?>
										</td>	
										<td class="title column-total">
											<?php echo $row->total_text;?>
										</td>											
									</tr>
						<?php
									$iterator++;
                                    $total+=$row->total;
								}
							}	
						?>
					</tbody>
                    <tfoot>
                        <tr>
                            <td colspan="10" align="right"><strong><?php echo __("Total","bwge_back").": ".$options->currency_sign.number_format( $total,2);?> </strong></td>
                        </tr>
                    </tfoot>
				</table>
				<div class="tablenav bottom">
					<?php
					BWGEHelper::html_page_nav($page_nav['total'],$pager++, $page_nav['limit'], 'adminForm', $per_page);
					?>
				</div>				
			</div>
			<input id="asc_or_desc" name="asc_or_desc" type="hidden" value="asc" />
			<input id="order_by" name="order_by" type="hidden" value="<?php echo $order_by; ?>" />
			<input id="page" name="page" type="hidden" value="<?php echo BWGEHelper::get('page');?>" />				
			<input id="task" name="task" type="hidden" value="" />
			<input id="current_id" name="current_id" type="hidden" value="" />
			<input id="publish_unpublish" name="publish_unpublish" type="hidden" value="" />

		</form>
	<?php  
	}


	public function edit($id){
		wp_print_scripts('jquery');
		
		$options = $this->model->get_options();		
		$row = $this->model->get_rows($id);
		$total = 0;	
		$i = 0;	
        $shipping = $this->model->get_order_shipping($row->id);
	?>
       
		<div class="bwge">
              <div style="font-size: 14px; font-weight: bold;">
                <?php echo __('This section allows you to manage orders.', 'bwge_back'); ?>
                <a style="color: #00A0D2; text-decoration: none;" target="_blank" href="https://galleryecommerce.com/gallery-ecommerce-set-up/orders/"><?php echo __('Read More in User Manual', 'bwge_back'); ?></a>
              </div>          
			<h2><?php _e("Order","bwge_back"); ?></h2>
			<form method="post" action="" id="adminForm">
                <?php wp_nonce_field('nonce_bwge', 'nonce_bwge'); ?>
				<p>
					<input type="button" name="btn_save" value="<?php _e("Save","bwge_back"); ?>" class="wd-btn wd-btn-primary wd-btn-icon wd-btn-save" onclick="bwgeFormSubmit('save');">
					<input type="button" name="btn_apply" value="<?php _e("Apply","bwge_back"); ?>" class="wd-btn wd-btn-primary wd-btn-icon wd-btn-apply" onclick="bwgeFormSubmit('apply');">
					<input type="button" name="btn_cancel" value="<?php _e("Cancel","bwge_back"); ?>" class="wd-btn wd-btn-primary wd-btn-icon wd-btn-cancel" onclick="bwgeFormSubmit('cancel');">
				</p>
                <?php if($row){ 
                ?>
				<div class="">
					<div>
						<table class="bwge_edit_table" width="80%">
							<tr>
								<td class="label_column" width="25%"><label for="type"><?php _e("Order ID","bwge_back"); ?>:</label></td>
								<td>
									<?php echo $row->id;?>
								</td>
							</tr>				
							<tr>
								<td class="label_column"><label for="type"><?php _e("User:","bwge_back"); ?></label></td>
								<td>
									<?php echo $row->billing_data_name;?>
								</td>
							</tr>
							<tr>
								<td class="label_column"><label for="type"><?php _e("Payment method:","bwge_back"); ?></label></td>
								<td>
									<?php echo $row->payment_method;?>
								</td>
							</tr>
							<?php 
								if($row->payment_data){
							?>
							<tr>
								<td class="label_column"><label for="type"><?php _e("Payment data:","bwge_back"); ?></label></td>
								<td>							
									<a href="#" onclick="jQuery('#payment_data').toggle(); return false"><?php _e("Details","bwge_back"); ?></a>						
									<div style="width:500px; word-break: break-all; display:none" id="payment_data">									
										<?php 
											if($row->payment_data){
												$payment_data = json_decode(htmlspecialchars_decode($row->payment_data));												
												foreach($payment_data as $key => $value){
													echo $key . ": " . $value ."<br>";
												}
											}
										?>									
									</div>
								</td>
							</tr>
							<?php 
								}
							?>
							<tr>
								<td class="label_column"><label for="type"><?php _e("Checkout date:","bwge_back"); ?></label></td>
								<td>
									<?php echo $row->checkout_date;?>
								</td>
							</tr>
							<tr>
								<td class="label_column"><label for="status"><?php _e("Status:","bwge_back"); ?></label></td>
								<td>
									<select name="status" id="status">
										<?php foreach($row->statuses as $key => $status){
											$selected = $row->status == $key ? "selected" : "";
											echo '<option value="'.$key.'" '.$selected.'>'.$status.'</option>';
										}
										?>							
									</select>
								</td>
							</tr>
							<tr>
								<td class="label_column"><label for="type"><?php _e("Resend email","bwge_back"); ?>:</label></td>
								<td>
									<input type="button" name="btn_resend_email" value="Resend order details email" class="wd-btn wd-btn-primary" onclick="bwgeFormSubmit('resend_email');">
								</td>
							</tr>							
						</table>
					</div>
					<div class="wd_divider"></div>
					<div>	
						<?php foreach($row->order_images as $order_product_row){ ?>														
							<table class="bwge_edit_table" width="50%">
								<tr>
									<td width="40%" class="wd-cell-valign-top">
										<img src="<?php echo $order_product_row->thumb_url;?>" alt="<?php echo $order_product_row->alt;?>" class="bwge_product_image" width="200">
									</td>
									<td class="wd-cell-valign-top">
										<div>											
											<p class="bwge_product_name"><?php _e("Image name:","bwge_back"); ?> <?php echo $order_product_row->image_name;?></p>
											<p class="bwge_pricelist_name"><?php _e("Priselist name:","bwge_back"); ?> <?php echo $order_product_row->product_name;?></p>
											<?php 
											if($order_product_row->pricelist_download_item_id != 0){
											?>
												<p class="bwge_product_longest_dimension"><?php _e("Dimension:","bwge_back"); ?> <?php echo $order_product_row->item_longest_dimension."px";?></p>
											<?php
											}
											?>								
										</div>
										<div>
											<p class="bwge_product_price"><?php _e("Price:","bwge_back"); ?> <?php echo $order_product_row->final_price_text;?></p>
											<?php	if($order_product_row->pricelist_download_item_id == 0 || ( $order_product_row->pricelist_download_item_id  && $options->show_digital_items_count == 1 ) ){
											?>
											<p class="bwge_product_count"><?php echo __("Count: ","bwge_back").$order_product_row->products_count;?></p>	
											<?php 
											}
											if($order_product_row->pricelist_download_item_id == 0 ){
											?>
												<p class="bwge_product_parameters"><?php _e("Parameters:","bwge_back"); ?> <br> <?php echo  $order_product_row->selected_parameters_string;?>  </p>	
																											
											<?php
												
											}
											if($order_product_row->tax_rate){
											?>	
											<p class="bwge_product_tax"><?php _e("Tax:","bwge_back"); ?> <?php echo $order_product_row->tax_rate ."%";?>  </p>			
											<?php 
											}
											$total += $order_product_row->subtotal;
											?>									
											<p class="bwge_product_price_subtotal"><b><?php echo __("Subotal: ","bwge_back").$order_product_row->subtotal_text;?></b></p>
											<?php 
											$i++;
											if($i< count($row->order_images)){
												echo '<div class="wd_divider"></div>';
											}
											
											?>
											</div>
										</td>
									</tr>
								</table>
						<?php				
						}
						?>	
					</div>
					
					<?php if( empty($row->files) === false){
						echo '<div class="wd_divider"></div>';
						echo "<h4 class='download_files'>".__('Download files',"bwge_back")."</h4>";
						foreach($row->files as $order_image_id => $file){
							//echo "<div><a href='#' data-order-image-id='".$order_image_id."' onclick='downloadFile(this);' >". $file."</a></div>";
							echo "<div>". $file."</div>";
						}
						echo "<a href='".site_url()."?page_id=".$options->orders_page."&task=download_file_from_url&order_id=".$row->id."&key=".md5('268413990'.$row->id.$row->rand_id)."' target='_blank' class='download_link'>".__('Download link',"bwge_back")."</a>";	
					}
					?>
					<div class="wd_divider"></div>
					<table width="80%" class="bwge_edit_table">
						<tr class="billing_shipping_info" style="vertical-align:top;">
							<td class="billing_info">
								<h4><?php _e("Billing info","bwge_back"); ?></h4>
								
								<p><?php echo __("Name:","bwge_back")." ".$row->billing_data_name;?></p>
								<p><?php echo __("Email: ","bwge_back")." ".$row->billing_data_email;?></p>
								<p><?php echo __("Country: ","bwge_back")." ".$row->billing_data_country;?></p>
								<p><?php echo __("City: ","bwge_back")." ".$row->billing_data_city;?></p>
								<p><?php echo __("Address: ","bwge_back")." ".$row->billing_data_address;?></p>
								<p><?php echo __("Zip code: ","bwge_back")." ".$row->billing_data_zip_code;?></p>
							</td>
							
							<td class="shipping_info" style="vertical-align:top;">
								<h4><?php _e("Shipping info","bwge_back"); ?></h4>
								
								<p><?php echo __("Name: ","bwge_back")." ".$row->shipping_data_name;?></p>
								<p><?php echo __("Country: ","bwge_back")." ".$row->shipping_data_country;?></p>
								<p><?php echo __("City: ","bwge_back")." ".$row->shipping_data_city;?></p>
								<p><?php echo __("Address: ","bwge_back")." ".$row->shipping_data_address;?></p>
								<p><?php echo __("Zip code: ","bwge_back")." ".$row->shipping_data_zip_code;?></p>				
							</td>				
						</tr>
					</table>					
					<div class="wd_divider"></div>
                    <?php if($shipping){
                    ?>
                     <p class="pge_product_shipping"><?php _e("Shipping:","bwg_back"); ?> <?php echo $row->currency_sign.number_format(($shipping),2);?>  </p>
                     <?php
                     }
                     ?>
					<div class="bwge_total_container">
						<h4 class="bwge_product_price_total"><?php echo __("Total: ","bwge_back").$row->currency_sign.number_format($total+$shipping,2);?></h4>
					</div>
	
				</div>
                 <?php
                }                
                else{
                    _e("No results ","bwge_back");
                }
                ?>
				<input id="page" name="page" type="hidden" value="<?php echo BWGEHelper::get('page');?>" />	
				<input id="task" name="task" type="hidden" value="" />	
				<input id="id" name="id" type="hidden" value="<?php echo $row->id;?>" />	
				<input id="id" name="is_email_sent" type="hidden" value="<?php echo $row->is_email_sent;?>" />	
			</form>
		</div>
		<script>	
		  var _page = "<?php echo BWGEHelper::get('page') ? BWGEHelper::get('page') : "options_bwge"; ?>";
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