<?php

class BWGEViewPricelists_bwge extends BWGEView{

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
		$search_value = ((isset($_POST['search_value'])) ? esc_html(stripslashes($_POST['search_value'])) : '');
		$asc_or_desc = ((isset($_POST['asc_or_desc'])) ? esc_html(stripslashes($_POST['asc_or_desc'])) : 'asc');
		$order_by = (isset($_POST['order_by']) ? esc_html(stripslashes($_POST['order_by'])) : 'id');
		$order_class = 'manage-column column-title sorted ' . $asc_or_desc;
		
		$per_page = $this->model->per_page();
		$pager = 0;
	
		wp_print_scripts('jquery');	
	?>	

		<form method="post" action="" id="adminForm">
            <?php wp_nonce_field('nonce_bwge', 'nonce_bwge'); ?> 
			<div class="bwge">
              <div style="font-size: 14px; font-weight: bold;">
                <?php echo __('This section allows you to create, edit and delete pricelists.', 'bwge_back'); ?>
                <a style="color: #00A0D2; text-decoration: none;" target="_blank" href="https://galleryecommerce.com/gallery-ecommerce-set-up/adding-pricelist/"><?php echo __('Read More in User Manual', 'bwge_back'); ?></a>
              </div>             
				<h2 id="pricelist_id">
					<span><?php _e("Pricelists","bwge_back"); ?>&nbsp;</span>
					<a href="#" onclick="bwgeFormSubmit('edit');return false;" class="wd-btn wd-btn-primary wd-btn-icon wd-btn-add"><?php _e("Add New","bwge_back"); ?></a>
				</h2>
				<div class="wd-clear">
					<div class="wd-left">
						<?php
							BWGEHelper::search('Title', $search_value, 'adminForm');
						?>
					</div>
					<div class="wd-right" style="text-align:right;margin-bottom:15px ;">
			
						<input class="wd-btn wd-btn-primary wd-btn-icon wd-btn-publish" type="submit" onclick="bwgeFormInputSet('task', 'publish');bwgeFormInputSet('publish_unpublish', '1')" value="<?php _e("Publish","bwge_back"); ?>" />
						<input class="wd-btn wd-btn-primary wd-btn-icon wd-btn-unpublish" type="submit" onclick="bwgeFormInputSet('task', 'publish');bwgeFormInputSet('publish_unpublish', '0')" value="<?php _e("Unpublish","bwge_back"); ?>" />
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
				<table class="wp-list-table widefat bwge_list_table">
					<thead>
						<tr class="bwge_alternate">
							<th scope="col" id="cb" class="manage-column column-cb check-column" style="">
								<label class="screen-reader-text" for="cb-select-all-1"><?php _e("Select All","bwge_back"); ?></label>
								<input id="cb-select-all-1" type="checkbox">
							</th>
							<th class="col <?php if ($order_by == 'id') {echo $order_class;} ?>" width="10%">
								<a onclick="bwgeFormInputSet('order_by', 'id');
											bwgeFormInputSet('asc_or_desc', '<?php echo ((isset($_POST['asc_or_desc']) && isset($_POST['order_by']) && (esc_html(stripslashes($_POST['order_by'])) == 'id') && esc_html(stripslashes($_POST['asc_or_desc'])) == 'asc') ? 'desc' : 'asc'); ?>');
											document.getElementById('adminForm').submit();return false;" href="">
								  <span>ID</span><span class="sorting-indicator"></span>
								</a>
							</th>							

							<th class="col <?php if ($order_by == 'title') {echo $order_class;} ?>">
								<a onclick="bwgeFormInputSet('order_by', 'title');
											bwgeFormInputSet('asc_or_desc', '<?php echo ((isset($_POST['asc_or_desc']) && isset($_POST['order_by']) && (esc_html(stripslashes($_POST['order_by'])) == 'title') && esc_html(stripslashes($_POST['asc_or_desc'])) == 'asc') ? 'desc' : 'asc'); ?>');
											document.getElementById('adminForm').submit();return false;" href="">
								  <span><?php _e("Title","bwge_back"); ?></span><span class="sorting-indicator"></span>
								</a>
							</th>								
							<th class="col <?php if ($order_by == 'price') {echo $order_class;} ?>">
								<a onclick="bwgeFormInputSet('order_by', 'price');
											bwgeFormInputSet('asc_or_desc', '<?php echo ((isset($_POST['asc_or_desc']) && isset($_POST['order_by']) && (esc_html(stripslashes($_POST['order_by'])) == 'price') && esc_html(stripslashes($_POST['asc_or_desc'])) == 'asc') ? 'desc' : 'asc'); ?>');
											document.getElementById('adminForm').submit();return false;" href="">
								  <span><?php _e("Price","bwge_back"); ?></span><span class="sorting-indicator"></span>
								</a>
							</th>							

							<th class="col <?php if ($order_by == 'published') {echo $order_class;} ?>" width="10%">
								<a onclick="bwgeFormInputSet('order_by', 'published');
											bwgeFormInputSet('asc_or_desc', '<?php echo ((isset($_POST['asc_or_desc']) && isset($_POST['order_by']) && (esc_html(stripslashes($_POST['order_by'])) == 'published') && esc_html(stripslashes($_POST['asc_or_desc'])) == 'asc') ? 'desc' : 'asc'); ?>');
											document.getElementById('adminForm').submit();return false;" href="">
								  <span><?php _e("Published","bwge_back"); ?></span><span class="sorting-indicator"></span>
								</a>
							</th>								
						</tr>
					</thead>
					<tbody id="the-list" >
						<?php 
							if(empty($rows ) == false){
								$iterator = 0;
								foreach($rows as $row){
									$alternate = $iterator%2 == 0 ? '' : 'class="bwge_alternate"';
									$published_image = (($row->published) ? 'publish-blue' : 'unpublish-blue');
									$published = (($row->published) ? 0 : 1);
						?>
									<tr id="tr_<?php echo $iterator; ?>" <?php echo $alternate; ?>>
										<th scope="row" class="check-column">
											<input type="checkbox" name="ids[]" value="<?php echo $row->id; ?>">
										</th>
										<td class="id column-id">
											<?php echo $row->id;?>
										</td>
										<td class="title column-title">
											<a href="admin.php?page=pricelists_bwge&task=edit&id=<?php echo $row->id;?>">
												<?php echo $row->title;?>
											</a>
										</td>
										<td class="price column-price">
											<?php echo $row->price_text;?>
										</td>										
								
										<td class="table_big_col">
											<a onclick="bwgeFormInputSet('task', 'publish');bwgeFormInputSet('publish_unpublish', '<?php echo $published ; ?>');bwgeFormInputSet('current_id', '<?php echo $row->id; ?>');document.getElementById('adminForm').submit();return false;" href="">
												<img src="<?php echo WD_BWGE_URL . '/images/css/' . $published_image . '.png'; ?>"></img>
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
		$row = $this->model->get_row($id);
		$parameter_types = array("","Single value","Input","Textarea","Select","Radio","Checkbox");
	?>
		<div class="bwge">
              <div style="font-size: 14px; font-weight: bold;">
                <?php echo __('This section allows you to create, edit  pricelists.', 'bwge_back'); ?>
                <a style="color: #00A0D2; text-decoration: none;" target="_blank" href="https://galleryecommerce.com/gallery-ecommerce-set-up/adding-pricelist/"><?php echo __('Read More in User Manual', 'bwge_back'); ?></a>
              </div>         
			<h2><?php _e("Create new pricelist","bwge_back"); ?></h2>								
			<form method="post" action="" id="adminForm">
                <?php wp_nonce_field('nonce_bwge', 'nonce_bwge'); ?>
				<p>
					<input type="button" name="btn_save" value="<?php _e("Save","bwge_back"); ?>" class="wd-btn wd-btn-primary wd-btn-icon wd-btn-save" id="pricelist_save" onclick="if (bwge_spider_check_required('title', 'Title')) {return false;}; bwgeFormSubmit('save');">
					<input type="button" name="btn_apply" value="<?php _e("Apply","bwge_back"); ?>" class="wd-btn wd-btn-primary wd-btn-icon wd-btn-apply" onclick="if (bwge_spider_check_required('title', 'Title')) {return false;}; bwgeFormSubmit('apply');">
					<input type="button" name="btn_cancel" value="<?php _e("Cancel","bwge_back"); ?>" class="wd-btn wd-btn-primary wd-btn-icon wd-btn-cancel" onclick="bwgeFormSubmit('cancel');">
				</p>
				
				<table class="bwge_edit_table">
					<tr>
						<td class="label_column"><label for="title"><?php _e("Title:","bwge_back"); ?><span style="color:#FF0000;">*</span></label></td>
						<td><input type="text" autocomplete="off" id="title" value="<?php echo $row->title;?>" size="30" name="title" class="wd-required" onkeypress="removeRedBorder(this)"></td>
					</tr>				
					<tr>
						<td class="label_column"><label for="tax_rate"><?php _e("Tax rate:","bwge_back"); ?></label></td>
						<td><input type="text" name="tax_rate" id="tax_rate" size="8" value="<?php echo $row->tax_rate;?>" > %</td>
					</tr>
					<tr>
						<td class="label_column"><label for="tax_rate"><?php _e("Sections:","bwge_back"); ?></label></td>
						<td class="section_checkboxes" id="section_checkboxes">
							<input type="checkbox" value="manual" id="manual_section" class="bwge_sections" name="sections[]" <?php if(in_array("manual",$row->sections)) echo "checked"; ?> onchange="onChangePricelistSection(this);" >
							<label for="manual_section"><?php _e("Prints and products","bwge_back"); ?></label>
							<input type="checkbox" value="downloads" id="downloads_section" name="sections[]" class="bwge_sections" <?php if(in_array("downloads",$row->sections)) echo "checked"; ?> onchange="onChangePricelistSection(this);">
							<label for="downloads_section"><?php _e("Downloads","bwge_back"); ?>	</label>						
						</td>
					</tr>					
				</table>
				
				<div id="tabs_wrapper" <?php if(empty($row->sections) == true) echo "style='display:none;'"; ?>>
					<div class="section_title" style="<?php if(count($row->sections)> 1) echo 'display:none;';?>"> <?php $sections = $row->sections; if(count($row->sections) == 1) echo ucfirst($sections[0]); ?></div>	
					<div id="sections_tabs" >
						<ul <?php if(count($row->sections) <= 1) echo "style='display:none;'"; ?> class="wd-clear">
							<li id="manual_li" <?php if(!in_array("manual",$row->sections)) echo "style='display:none;'"; ?> >
								<span>
								  <a href="#manual" <?php if(BWGEHelper::get("active_tab") == "#manual" || BWGEHelper::get("active_tab") == "") echo 'class="sections_tab_active"';?>><?php _e("Manual","bwge_back"); ?></a>
								</span>
							</li>
							<li id="downloads_li" <?php if(!in_array("downloads",$row->sections)) echo "style='display:none;'"; ?>>
								<span>
								  <a href="#downloads" <?php if(BWGEHelper::get("active_tab") == "#downloads" ) echo 'class="sections_tab_active"';?>><?php _e("Downloads","bwge_back"); ?></a>
								</span>
							</li>					  
						</ul>
					
						<div id="sections_tabs_container">
							<!-- manual -->		
							<div id="manual" class="manual" <?php if((count($row->sections) == 2 && (BWGEHelper::get("active_tab") == "" || BWGEHelper::get("active_tab") == "#manual") ) || (count($row->sections) == 1 && end($row->sections) == "manual")) echo 'style="display: block;"'; else echo 'style="display: none;"'; ?>>
								<table class="general bwge_edit_table">
									<tbody>	
										<tr>
											<td class="label_column"><label for="price"><?php _e("Name:","bwge_back"); ?></label></td>
											<td>
												<input type="text" name="manual_title" id="manual_title" value="<?php echo $row->manual_title;?>">									
											</td>
										</tr>
										<tr>
											<td class="label_column"><label for="manual_description"><?php _e("Description:","bwge_back"); ?></label></td>
											<td><textarea  name="manual_description" id="manual_description" cols="60" rows="5"><?php echo $row->manual_description;?></textarea></td>
										</tr>								
										<tr>
											<td class="label_column"><label for="price"><?php _e("Price:","bwge_back"); ?></label></td>
											<td>
												<input type="text" name="price" id="price" value="<?php echo $row->price;?>">									
											</td>
										</tr>							
										<tr>
											<td class="label_column"><label for="shipping_price"><?php _e("Shipping rate:","bwge_back"); ?></label></td>
											<td>
												<select name="shipping_type" id="shipping_type">
													<option value="flat" <?php if($row->shipping_type == "flat") echo "selected";?>><?php _e("Flat rate","bwge_back"); ?></option>
													<option value="percentage" <?php if($row->shipping_type == "percentage") echo "selected";?>><?php _e("Percentage","bwge_back"); ?></option>
												</select>
												<input type="text" name="shipping_price" id="shipping_price" value="<?php echo $row->shipping_price;?>">									
											</td>
										</tr>
										<!--
										<tr>
											<td class="label_column"><label for="enable_international_shipping">Enable international shipping rate?</label></td>
											<td><input type="checkbox" name="enable_international_shipping" id="enable_international_shipping" onchange="showInternationalShipping(this);" <?php if($row->enable_international_shipping == "1") echo "checked";?> value="1" ></td>
										</tr>
										
										<tr class="international_shipping <?php if($row->enable_international_shipping == 0) echo "hide";?>">
											<td class="label_column"><label for="international_shipping_price">International shipping rate:</label></td>
											<td>
												<select name="international_shipping_type" id="shipping_type">
													<option value="flat" <?php if($row->international_shipping_type == "flat") echo "selected";?>>Flat rate</option>
													<option value="percentage" <?php if($row->international_shipping_type == "percentage") echo "selected";?>>Percentage</option>
												</select>
												<input type="text" name="international_shipping_price" id="international_shipping_price" value="<?php echo $row->international_shipping_price;?>">									
											</td>
										</tr>								
										-->
										<tr>
											<td class="label_column"><label for="add_parameters"><?php _e("Add parameters:","bwge_back"); ?></label></td>
											<td>
												<?php
												$query_url =  admin_url('admin-ajax.php');
												$query_url = add_query_arg(array('action' => 'bwge_add_parameters', 'page' => 'parameters_bwge', 'task' => 'explore', 'width' => '800', 'height' => '600', 'callback' => 'addParameter','nonce_bwge' => wp_create_nonce('nonce_bwge') ,'TB_iframe' => '1' ), $query_url);
												?>
												<a href="<?php echo $query_url; ?>" class="wd-btn wd-btn-primary thickbox thickbox-preview"><?php _e("Add parameters","bwge_back"); ?></a>									
											</td>
										</tr>
									</tbody>
								</table>
								<div class="wd_divider"></div>
								<div class="parameters_container">
									<h3 style="color:#00A0D2"><?php _e("Parameters","bwge_back"); ?></h3>
									<table class="bwge_edit_table">
										<thead>
											<tr>
												<th width="8%"><?php _e("Order","bwge_back"); ?></th>
												<th width="25%"><?php _e("Title","bwge_back"); ?></th>
												<th width="10%"><?php _e("Type","bwge_back"); ?></th>
												<th><?php _e("Values/Prices","bwge_back"); ?></th>
												<th width="5%"><?php _e("Remove","bwge_back"); ?></th>
											</tr>									
										</thead>
										<tbody class="wd_bwge_parameters">
											<tr class="template">
												<td class="col_ordering icon-drag-1">
													<img src="<?php echo WD_BWGE_URL . '/images/draggable.png';?>" >
												</td>
												<td class="col_title"></td>
												<td class="col_type"></td>
												<td class="col_values">
													<div class="parameter_values_container">
														<div class="template">
															<div class="input_parameter parameter_value_container">
																<input type="text" class="parameter_value" size="65">
															</div>
															<div class="textarea_parameter parameter_value_container">
																<textarea class="parameter_value" cols="65" rows="4"></textarea>
															</div>													
															<div class="multi_select_parameter parameter_value_container">
																<div class="multi_select_parameter_container">
																	<div class="template">
																		<img src="<?php echo WD_BWGE_URL . '/images/draggable.png';?>" class="parameter_value_order icon-drag-2">
																		<input type="text" class="parameter_value" size="40">							
																		<select class="parameter_price_sign">
																			<option value="+">+</option>
																			<option value="-">-</option>
																		</select>
																		<input type="number" class="parameter_price" size="6" placeholder="<?php _e("Price","bwge_back"); ?>" min="0">
																		<img src="<?php echo WD_BWGE_URL . '/images/delete.png';?>" onclick="bwgeRemoveParameterValue(this);" class="pointer-cursor">													
																	</div>
																</div>
																<input type="button" value="<?php _e("Add","bwge_back"); ?>" onclick="addParameterValue(this);" class="wd-btn wd-btn-primary" style="padding: 8px 45px;margin-left: 22px; margin-top: 5px;">	
															</div>
														</div>
														<div class="parameter_values">
														</div>
													</div>
												</td>
												<td class="col_remove">
													<img src="<?php echo WD_BWGE_URL . '/images/delete-red.png';?>" onclick="bwgeRemovePricelistItem(this);" title="<?php _e("Remove","bwge_back"); ?>" class="pointer-cursor">													
												</td>
											</tr>	
											<?php 
												if(empty($row->parameters) == false){
													foreach($row->parameters as $parameter_id => $parameter){
											?>
													<tr data-id="<?php echo $parameter_id ;?>"  data-type="<?php echo $parameter["type"] ;?>">
														<td class="col_ordering icon-drag-1">
															<img src="<?php echo WD_BWGE_URL . '/images/draggable.png';?>" >
														</td>
														<td class="col_title"><?php echo $parameter["title"]; ?></td>
														<td class="col_type"><?php echo $parameter_types[$parameter["type"]]; ?></td>
														<td class="col_values">
															<div class="parameter_values_container">
																<div class="parameter_values">
																	<?php
																		if($parameter["type"] == 2 || $parameter["type"] == 1){
																	?>
																		<div class="input_parameter parameter_value_container ">
																			<input type="text" class="parameter_value" size="65" value="<?php echo $parameter["values"][0]["parameter_value"]?>">
																		</div>
																	<?php
																		}
																		else if($parameter["type"] == 3){
																	?>
																		<div class="textarea_parameter parameter_value_container">
																			<textarea class="parameter_value" cols="65" rows="4"><?php echo $parameter["values"][0]["parameter_value"]?></textarea>
																		</div>															
																	<?php
																		}
																		else if($parameter["type"] == 4  || $parameter["type"] == 5 || $parameter["type"] == 6){
																	?>
																			<div class="multi_select_parameter parameter_value_container">
																				<div class="multi_select_parameter_container wd_bwge_parameters_values">
																					<?php foreach($parameter["values"] as $parameter_value){
																					?>	
																						<div>
																							<img src="<?php echo WD_BWGE_URL . '/images/draggable.png';?>" class="parameter_value_order icon-drag-2">
																							<input type="text" class="parameter_value" size="40" value="<?php echo $parameter_value["parameter_value"]?>">							
																							<select class="parameter_price_sign">
																								<option value="+" <?php  echo $parameter_value["parameter_value_price_sign"] == "+" ? "selected" : ""; ?>>+</option>
																								<option value="-" <?php  echo $parameter_value["parameter_value_price_sign"] == "-" ? "selected" : ""; ?>>-</option>
																							</select>
																							<input type="number" class="parameter_price" size="6" placeholder="<?php _e("Price","bwge_back"); ?>" value="<?php echo $parameter_value["parameter_value_price"]?>" min="0">
																							<img src="<?php echo WD_BWGE_URL . '/images/delete.png';?>" onclick="bwgeRemoveParameterValue(this);" class="pointer-cursor">													
																						</div>
																					<?php
																					}
																					?>
																				</div>
																				<input type="button" value="<?php _e("Add","bwge_back"); ?>" onclick="addParameterValue(this);" class="wd-btn wd-btn-primary" style="padding: 8px 45px;margin-left: 22px; margin-top: 5px;">	
																			</div>															
																	<?php
																		}
																	?>
																</div>
															</div>
														</td>
														<td class="col_remove">
															<img src="<?php echo WD_BWGE_URL . '/images/delete-red.png';?>" onclick="bwgeRemovePricelistItem(this);" title="<?php _e("Remove","bwge_back"); ?>" class="pointer-cursor">
														</td>
													</tr>										
											<?php
												}
											}
											?>
										</tbody>								
									</table>
								</div>							
							</div>	
							
							<!-- downloads -->	
							<div id="downloads" class="downloads" <?php if((count($row->sections) == 2 && BWGEHelper::get("active_tab") == "#downloads") || (count($row->sections) == 1 && end($row->sections) == "downloads")) echo 'style="display: block;"'; else echo 'style="display: none;"'; ?>>
								<table class="bwge_edit_table">							
									<tr>
										<td class="label_column"><label for="display_license"><?php _e("Display license ?","bwge_back"); ?></label></td>
										<td>
											<input type="checkbox" name="display_license" id="display_license" value="1" onchange="showPagesForLicense(this);" <?php echo $row->display_license == 1 ? "checked" : "";?>>									
										</td>
									</tr>
									<tr class= "license_id <?php if($row->display_license == 0) echo "hide";?>">
										<td class="label_column"><label for="license_id"><?php _e("Select license page:","bwge_back"); ?></label></td>
										<td>
											<select name="license_id" id="license_id">
												<?php 
													foreach($row->licenses as $license_id => $license ){
														$selected = ($license_id == $row->license_id) ? "selected" : "";
														echo '<option value="'.$license_id.'" '.$selected.'>'.$license.'</option>';
													}
												?>
											</select>
										</td>
									</tr>
								</table>
												
								<table class="itmes bwge_edit_table">
									<thead>
										<tr>
											<td><?php _e("Item name:","bwge_back"); ?></td>
											<td><?php _e("Item price:","bwge_back"); ?></td>
											<td><?php _e("Item longest dimension:","bwge_back"); ?></td>
											<td></td>
										</tr>								
									</thead>
									<tbody class="itmes-body">
										<tr class="item-row template">
											<td><input type="text"  class="item_name"></td>
											<td><input type="text"  class="item_price"></td>
											<td><input type="text"  class="item_longest_dimension"> px</td>
											<td><img src="<?php echo WD_BWGE_URL . '/images/delete.png';?>" onclick="bwgeRemovePricelistItem(this);" class="pointer-cursor"></td>
										</tr>									
										<?php 
											if(empty($row->digital_itmes) === false){
												foreach($row->digital_itmes as $digital_item) {
										?>
													<tr class="item-row">
														<td><input type="text"  class="item_name" value="<?php echo $digital_item->item_name;?>"></td>
														<td><input type="text"  class="item_price" value="<?php echo $digital_item->item_price;?>"></td>
														<td><input type="text"  class="item_longest_dimension" value="<?php echo $digital_item->item_longest_dimension;?>"> px</td>
														<td><img src="<?php echo WD_BWGE_URL . '/images/delete.png';?>" onclick="bwgeRemovePricelistItem(this);" class="pointer-cursor"></td>								
													</tr>								
										<?php
												}
											}
										?>
										<tr class="item-row">
											<td><input type="text"  class="item_name"></td>
											<td><input type="text"  class="item_price"></td>
											<td><input type="text"  class="item_longest_dimension"> px</td>
											<td><img src="<?php echo WD_BWGE_URL . '/images/delete.png';?>" onclick="bwgeRemovePricelistItem(this);" class="pointer-cursor"></td>								
										</tr>								
									</tbody>
									<tfoot>
										<td colspan="4"><button class="wd-btn wd-btn-primary " onclick="bwgeAddPricelistItem(this); return false;" data-type="downloads" ><?php _e("Add item","bwge_back"); ?></button></td>
									</tfoot>
								</table>							
							</div>					
							
						</div>
					</div>
				</div>
				
				<table class="bwge_edit_table">
					<tr>
						<td><label for="published1"><?php _e("Published:","bwge_back"); ?></label></td>
						<td>
						  <input type="radio" class="inputbox" id="published1" name="published" <?php echo (($row->published) ? 'checked="checked"' : ''); ?> value="1" >
						  <label for="published1"><?php _e("Yes","bwge_back"); ?></label>                        
						  <input type="radio" class="inputbox" id="published0" name="published" <?php echo (($row->published) ? '' : 'checked="checked"'); ?> value="0" >
						  <label for="published0"><?php _e("No","bwge_back"); ?></label>

						</td>
					</tr>
				</table>	
				<input id="page" name="page" type="hidden" value="<?php echo BWGEHelper::get('page');?>" />	
				<input id="task" name="task" type="hidden" value="" />	
				<input id="id" name="id" type="hidden" value="<?php echo $row->id;?>" />	
	
				<input id="download_items" name="download_items" type="hidden" value="<?php echo json_encode($row->digital_itmes);?>" />	
				<input id="parameters" name="parameters" type="hidden" value="<?php  ;?>" />
				<input id="active_tab" name="active_tab" type="hidden" value="<?php echo BWGEHelper::get('active_tab');?>" />					
			</form>
		</div>

		<script>		
		  var _page = "<?php echo BWGEHelper::get('page') ? BWGEHelper::get('page') : "options_bwge"; ?>";
		</script>		
	<?php
	 
	}
	
	public function explore(){
		$rows = $this->model->get_rows();
		
		wp_print_scripts('jquery');
		$page_nav = $this->model->page_nav();
		$search_value = ((isset($_POST['search_value'])) ? esc_html(stripslashes($_POST['search_value'])) : '');
		$asc_or_desc = ((isset($_POST['asc_or_desc'])) ? esc_html(stripslashes($_POST['asc_or_desc'])) : 'asc');
		$order_by = (isset($_POST['order_by']) ? esc_html(stripslashes($_POST['order_by'])) : 'id');
		$order_class = 'manage-column column-title sorted ' . $asc_or_desc;
		
		$per_page = $this->model->per_page();
		$pager = 0;	
              
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
        <link media="all" type="text/css" href="<?php echo WD_BWGE_URL . '/css/bwge_ecommerce.css?ver='.wd_bwge_version(); ?>" id="bwge_tables-css" rel="stylesheet">
	
		<form method="post" action="" id="adminForm" class="wrap wp-core-ui" style="width:99%; margin: 0 auto;">
			<div class="bwge" >
				<h2>
					<span id="kkk">Pricelists</span>				
				</h2>

				<div class="tablenav top wd-row">
					<?php
						BWGEHelper::search('Title', $search_value, 'adminForm');	
						BWGEHelper::html_page_nav($page_nav['total'],$pager++, $page_nav['limit'], 'adminForm', $per_page);						
					?>
				</div>	
				<br>
				<table class="wp-list-table widefat fixed pages bwge_list_table" width="100%">
					<thead>
						<tr class="bwge_alternate">
							<th class="col <?php if ($order_by == 'id') {echo $order_class;} ?>" width="10%">
								<a onclick="bwgeFormInputSet('order_by', 'id');
											bwgeFormInputSet('asc_or_desc', '<?php echo ((isset($_POST['asc_or_desc']) && isset($_POST['order_by']) && (esc_html(stripslashes($_POST['order_by'])) == 'id') && esc_html(stripslashes($_POST['asc_or_desc'])) == 'asc') ? 'desc' : 'asc'); ?>');
											document.getElementById('adminForm').submit();return false;" href="">
								  <span>ID</span><span class="sorting-indicator"></span>
								</a>
							</th>							

							<th class="col <?php if ($order_by == 'title') {echo $order_class;} ?>">
								<a onclick="bwgeFormInputSet('order_by', 'title');
											bwgeFormInputSet('asc_or_desc', '<?php echo ((isset($_POST['asc_or_desc']) && isset($_POST['order_by']) && (esc_html(stripslashes($_POST['order_by'])) == 'title') && esc_html(stripslashes($_POST['asc_or_desc'])) == 'asc') ? 'desc' : 'asc'); ?>');
											document.getElementById('adminForm').submit();return false;" href="">
								  <span>Title</span><span class="sorting-indicator"></span>
								</a>
							</th>								
							<th class="col <?php if ($order_by == 'price') {echo $order_class;} ?>">
								<a onclick="bwgeFormInputSet('order_by', 'price');
											bwgeFormInputSet('asc_or_desc', '<?php echo ((isset($_POST['asc_or_desc']) && isset($_POST['order_by']) && (esc_html(stripslashes($_POST['order_by'])) == 'price') && esc_html(stripslashes($_POST['asc_or_desc'])) == 'asc') ? 'desc' : 'asc'); ?>');
											document.getElementById('adminForm').submit();return false;" href="">
								  <span>Price</span><span class="sorting-indicator"></span>
								</a>
							</th>													
						</tr>
					</thead>

					<tbody id="the-list" class="explore_pricelist_tbody" >

						<?php 
							if(empty($rows ) == false){
								$iterator = 0;
								foreach($rows as $row){
									$alternate = $iterator%2 == 0 ? '' : 'class="bwge_alternate"';									
						?>
									<tr id="tr_<?php echo $iterator; ?>" <?php echo $alternate; ?> data-id="<?php echo $row->id;?>" data-title="<?php echo $row->title;?>" >
			
										<td class="id column-id">
											<?php echo $row->id;?>
										</td>
										<td class="title column-title">
											<a href="#" onclick="bwgeSelectClick(this); ">
												<?php echo $row->title;?>
											</a>
										</td>
										<td class="type column-type">
											<?php echo $row->price_text;?>
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
					BWGEHelper::html_page_nav($page_nav['total'],$pager++, $page_nav['limit'], 'adminForm', $per_page);
					?>
				</div>
				
			</div>
			<input id="asc_or_desc" name="asc_or_desc" type="hidden" value="asc" />
			<input id="order_by" name="order_by" type="hidden" value="<?php echo $order_by; ?>" />
		</form>
		<script src="<?php echo get_admin_url(); ?>load-scripts.php?c=1&load%5B%5D=common,admin-bar" type="text/javascript"></script>
		<script>
			function bwgeSelectClick(obj){                
				var row = jQuery(obj).closest("tr");
				var pricelist = {};
				pricelist.id = row.attr("data-id");
				pricelist.title = row.attr("data-title");
				
				window.parent.addPricelist(pricelist);	
			}
		</script>
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