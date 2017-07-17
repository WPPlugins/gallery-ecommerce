<?php

class BWGEViewEcommerceoptions_bwge extends BWGEView{

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

	public function display(){
		wp_print_scripts('jquery');	
		$options = $this->model->get_options();
		$lists = $this->model->get_lists();

	?>
		<div class="bwge ">	
          <div style="font-size: 14px; font-weight: bold;">
            <?php echo __('This section allows you to manage ecommerce settings.', 'bwge_back'); ?>
            <a style="color: #00A0D2; text-decoration: none;" target="_blank" href="https://galleryecommerce.com/gallery-ecommerce-set-up/ecommerce-options/"><?php echo __('Read More in User Manual', 'bwge_back'); ?></a>
          </div><br>           
		<form method="post" action="" id="adminForm">
            <?php wp_nonce_field('nonce_bwge', 'nonce_bwge'); ?>
			<div id="option_tabs">	
                
				<ul class=" option_main_tabs wd-clear">
					<li>
						<span>
						  <a id="genereal_options_a" href="#genereal_options_panel" class="<?php if(BWGEHelper::get("active_tab") == "#genereal_options_panel" || BWGEHelper::get("active_tab") == "") echo 'bwge_options_active_tab genereal_options_a_active';?>"><?php _e("General options","bwge_back"); ?></a>
						</span>
					</li>
					<li>
						<span>
						  <a id="email_options_a" href="#email_options_panel" <?php if(BWGEHelper::get("active_tab") == "#email_options_panel" ) echo 'class="bwge_options_active_tab email_options_a_active"';?>><?php _e("Email options","bwge_back"); ?></a>
						</span>
					</li>	
					<li>
						<span>
						  <a id="checkout_options_a" href="#checkout_options_panel" <?php if(BWGEHelper::get("active_tab") == "#checkout_options_panel" ) echo 'class="bwge_options_active_tab checkout_options_a_active"';?>><?php _e("Checkout options","bwge_back"); ?></a>
						</span>
					</li>						
				</ul>
				<div id="bwge_option_tab_container">	
					
					<div id="genereal_options_panel" <?php if(BWGEHelper::get("active_tab") == "#genereal_options_panel" || BWGEHelper::get("active_tab") == "") echo 'style="display: block;"'; else echo 'style="display: none;"'; ?> >
									
						<table class="general bwge_edit_table">
							<tbody>	
	
								<tr>	
									<td class="label_column"><label for="currency"><?php _e("Currency:","bwge_back"); ?></label></td>
									<td>									
										<select name="currency" id="currency" style="width: 25em;">
											<?php 
												foreach($lists["currencies"] as $key=>$currency ){
													$selected = ($key == $options->currency) ? "selected" : "";
													echo '<option value="'.$key.'" '.$selected.'>'.$currency.'</option>';
												}
											?>
										</select>
									</td>									
								</tr>
								<tr>	
									<td class="label_column"><label for="currency_sign"><?php _e("Currency sign:","bwge_back"); ?></label></td>
									<td>									
										<input name="currency_sign" type="text" id="currency_sign" style="width: 25em;" value="<?php echo $options->currency_sign; ?>">				
									</td>									
								</tr>								
								<tr>	
									<td class="label_column"><label for="thank_you_page"><?php _e("Thank-you page:","bwge_back"); ?></label></td>
									<td>									
										<select name="thank_you_page" id="thank_you_page" style="width: 25em;">
											<option value="thank_you_page"><?php _e("Create new","bwge_back"); ?></option>
											<?php 
												foreach($lists["pages"] as $key => $page ){
													$selected = ($key == $options->thank_you_page) ? "selected" : "";
													echo '<option value="'.$key.'" '.$selected.'>'.$page.'</option>';
												}
											?>
										</select>
									</td>									
								</tr>	
								<tr>	
									<td class="label_column"><label for="cancel_page" ><?php _e("Generate pages:","bwge_back"); ?> </label></td>
									<td>									
										<input type="button" value="<?php _e("Generate","bwge_back"); ?>" class="wd-btn wd-btn-primary" onclick="bwgeFormSubmit('generate_pages')" />
										(<?php _e("Checkout, Orders, Cancel pages","bwge_back"); ?>)
									</td>									
								</tr>	
									
							</tbody>
						</table>												
					</div>
					<div id="email_options_panel" <?php if(BWGEHelper::get("active_tab") == "#email_options_panel" ) echo ' style="display: block;"'; else echo 'style="display: none;"'; ?> class="sub_tab_container" data-type="email">
						  <a href="#email_general" class="wd-btn wd-btn-secondary sub_tabs <?php if(BWGEHelper::get("email_active_tab") == "#email_general" || BWGEHelper::get("email_active_tab") == "" ) echo 'active_sub_tab';?>" ><?php _e("Email general options","bwge_back"); ?></a>
						  <a href="#email_admin" class="wd-btn wd-btn-secondary sub_tabs <?php if(BWGEHelper::get("email_active_tab") == "#email_admin" ) echo 'active_sub_tab';?>" ><?php _e("Email admin options","bwge_back"); ?></a>
						  <a href="#email_user" class="wd-btn wd-btn-secondary sub_tabs  <?php if(BWGEHelper::get("email_active_tab") == "#email_user" ) echo 'active_sub_tab';?>"><?php _e("Email user options","bwge_back"); ?></a>

						<div class="sections_tabs_container">
							<div id="email_general" <?php  if(BWGEHelper::get("email_active_tab") == "#email_general" || BWGEHelper::get("email_active_tab") == "" ) echo 'style="display: block;"'; else echo 'style="display: none;"'; ?>>
								<table class="bwge_edit_table">
									<tr>
										<td class="label_column"><label for="email_header_logo"><?php _e("E-mail header logo:","bwge_back"); ?></label></td>
										<td>									
											<button class="wd-btn wd-btn-primary" onclick="openMediaUploader(event);return false;"><?php _e("Upload image","bwge_back"); ?></button>
											<input type="hidden" name="email_header_logo" id="email_header_logo" value="<?php echo $options->email_header_logo; ?>">
											
											<div class="email_logo_view">
												<div class="email_logo_view_btns ">
													<div class="email_logo_view_delete" onclick="jQuery('#email_header_logo').val('');jQuery('.email_logo_wrapper').html('');"></div>
													<div class="email_logo_view_edit" onclick="openMediaUploader(event);return false;"></div>
												</div>
                                                <div class="email_logo_wrapper">                                                
                                                    <?php if($options->email_header_logo){
                                                        echo '<img src="'.$options->email_header_logo.'" width="100">';
                                                    }
                                                    ?>
                                                </div>
											</div>
											
											
										</td>								
									</tr>
									<tr>
										<td class="label_column"><label for="email_footer_text"><?php _e("E-mail footer text:","bwge_back"); ?></label></td>
										<td>									
                                            <?php wp_editor($options->email_footer_text, 'email_footer_text', array('teeny' => FALSE, 'textarea_name' => 'email_footer_text', 'media_buttons' => FALSE, 'textarea_rows' => 6)); ?>		
										</td>								
									</tr>
									<tr>
										<td class="label_column"><label for="email_header_background_color"><?php _e("E-mail header background color","bwge_back"); ?>:</label></td>
										<td>									
											<input type="text" class="color" name="email_header_background_color" id="email_header_background_color" value="<?php echo $options->email_header_background_color; ?>" >
										</td>								
									</tr>
									<tr>
										<td class="label_column"><label for="email_header_background_color"><?php _e("E-mail header  color","bwge_back"); ?>:</label></td>
										<td>									
											<input type="text" class="color" name="email_header_color" id="email_header_color" value="<?php echo $options->email_header_color; ?>" >
										</td>								
									</tr>										
								</table>
							</div>
							<div id="email_admin" <?php  if(BWGEHelper::get("email_active_tab") == "#email_admin" ) echo 'style="display: block;"'; else echo 'style="display: none;"'; ?>>
								<table class="email_options_admin bwge_edit_table">
									<tbody>
										<tr>
											<td class="label_column"><label for="enable_email_admin"><?php _e("Send e-mail ?","bwge_back"); ?></label></td>
											<td>
												<input type="radio" class="inputbox" id="enable_email_admin1" name="enable_email_admin" <?php echo (($options->enable_email_admin) ? 'checked="checked"' : ''); ?> value="1" >
												<label for="enable_email_admin1"><?php _e("Yes","bwge_back"); ?></label>                                            
												<input type="radio" class="inputbox" id="enable_email_admin0" name="enable_email_admin" <?php echo (($options->enable_email_admin) ? '' : 'checked="checked"'); ?> value="0" >
												<label for="enable_email_admin0"><?php _e("No","bwge_back"); ?></label>									
											</td>								
										</tr>	
										<tr>
											<td class="label_column"><label for="email_recipient_admin"><?php _e("E-mail to:","bwge_back"); ?></label></td>
											<td>									
												<input type="text" name="email_recipient_admin" id="email_recipient_admin" value="<?php echo $options->email_recipient_admin; ?>" size="65">
											</td>								
										</tr>
										<tr>
											<td class="label_column"><label for="email_from_admin"><?php _e("E-mail from:","bwge_back"); ?></label></td>
											<td>
												<div>
													<label for="use_user_email_from"><?php _e("Use user email from:","bwge_back"); ?></label>
													<input type="radio" class="inputbox" id="use_user_email_from1" name="use_user_email_from" onchange="showOptionsFromEmailField(this);" <?php echo (($options->use_user_email_from) ? 'checked="checked"' : ''); ?> value="1" >
													<label for="use_user_email_from1"><?php _e("Yes","bwge_back"); ?></label>                                                    
													<input type="radio" class="inputbox" id="use_user_email_from0" name="use_user_email_from" onchange="showOptionsFromEmailField(this);" <?php echo (($options->use_user_email_from) ? '' : 'checked="checked"'); ?> value="0" >
													<label for="use_user_email_from0"><?php _e("No","bwge_back"); ?></label>
									
												</div>
												<div class="email_from_admin_field <?php if($options->use_user_email_from == 1) echo "hide";?>">	
													<input type="text" name="email_from_admin" id="email_from_admin" value="<?php echo $options->email_from_admin; ?>" size="65">
												</div>
											</td>								
										</tr>
										<tr>
											<td class="label_column"><label for="email_from_name_admin"><?php _e("E-mail from name:","bwge_back"); ?></label></td>
											<td>									
												<input type="text" name="email_from_name_admin" id="email_from_name_admin" value="<?php echo $options->email_from_name_admin; ?>" size="65">
											</td>								
										</tr>								
										<tr>
											<td class="label_column"><label for="email_cc_admin"><?php _e("E-mail CC:","bwge_back"); ?></label></td>
											<td>									
												<input type="text" name="email_cc_admin" id="email_cc_admin" value="<?php echo $options->email_cc_admin; ?>" size="65">
											</td>								
										</tr>
										<tr>
											<td class="label_column"><label for="email_bcc_admin"><?php _e("E-mail BCC:","bwge_back"); ?></label></td>
											<td>									
												<input type="text" name="email_bcc_admin" id="email_bcc_admin" value="<?php echo $options->email_bcc_admin; ?>" size="65">
											</td>								
										</tr>
										<tr>
											<td class="label_column"><label for="email_subject_admin"><?php _e("E-mail subject:","bwge_back"); ?></label></td>
											<td>									
												<input type="text" name="email_subject_admin" id="email_subject_admin" value="<?php echo $options->email_subject_admin; ?>" size="65">
											</td>								
										</tr>

										<tr>
											<td class="label_column"><label for="email_body_admin"><?php _e("Order details e-mail body:","bwge_back"); ?></label></td>
											<td>
												<?php wp_editor($options->email_body_admin, 'email_body_admin', array('teeny' => FALSE, 'textarea_name' => 'email_body_admin', 'media_buttons' => FALSE, 'textarea_rows' => 6)); ?>										
												<div class="placeholders" data-editor="email_body_admin">
													<span class="placeholder" data-placeholder="customer_name" onclick="bwgeInsertPlaceholder(this);"><?php _e("Customer name","bwge_back"); ?></span>
													<span class="placeholder" data-placeholder="total_amount" onclick="bwgeInsertPlaceholder(this);"><?php _e("Total amount","bwge_back"); ?></span>
													<span class="placeholder" data-placeholder="order_details_page" onclick="bwgeInsertPlaceholder(this);"><?php _e("Order details page","bwge_back"); ?></span>
												</div>								
											</td>								
										</tr>
										<tr>
											<td class="label_column"><label for="email_mode_admin"><?php _e("Mode","bwge_back"); ?>:</label></td>
											<td>
												<input type="radio" class="inputbox" id="email_mode_admin0" name="email_mode_admin" <?php echo (($options->email_mode_admin) ? '' : 'checked="checked"'); ?> value="0" >
												<label for="email_mode_admin0"><?php _e("Text","bwge_back"); ?></label>
												<input type="radio" class="inputbox" id="email_mode_admin1" name="email_mode_admin" <?php echo (($options->email_mode_admin) ? 'checked="checked"' : ''); ?> value="1" >
												<label for="email_mode_admin1">HTML</label>									
											</td>								
										</tr>							
									</tbody>
								</table>							
							</div>
							<div id="email_user" <?php  if(BWGEHelper::get("email_active_tab") == "#email_user" ) echo 'style="display: block;"'; else echo 'style="display: none;"'; ?>>
								<table class="email_options_user bwge_edit_table" >
									<tbody>
										<tr>
											<td class="label_column"><label for="enable_email_user"><?php _e("Send e-mail ?","bwge_back"); ?></label></td>
											<td>
												<input type="radio" class="inputbox" id="enable_email_user1" name="enable_email_user" <?php echo (($options->enable_email_user) ? 'checked="checked"' : ''); ?> value="1" >
												<label for="enable_email_user1"><?php _e("Yes","bwge_back"); ?></label>                                            
												<input type="radio" class="inputbox" id="enable_email_user0" name="enable_email_user" <?php echo (($options->enable_email_user) ? '' : 'checked="checked"'); ?> value="0" >
												<label for="enable_email_user0"><?php _e("No","bwge_back"); ?></label>
									
											</td>								
										</tr>	
				
										<tr>
											<td class="label_column"><label for="email_from_user"><?php _e("E-mail from:","bwge_back"); ?></label></td>
											<td>									
												<input type="text" name="email_from_user" id="email_from_user" value="<?php echo $options->email_from_user; ?>" size="65">
											</td>								
										</tr>
										<tr>
											<td class="label_column"><label for="email_from_name_user"><?php _e("E-mail from name:","bwge_back"); ?></label></td>
											<td>									
												<input type="text" name="email_from_name_user" id="email_from_name_user" value="<?php echo $options->email_from_name_user; ?>" size="65">
											</td>								
										</tr>								
										<tr>
											<td class="label_column"><label for="email_cc_user"><?php _e("E-mail CC:","bwge_back"); ?></label></td>
											<td>									
												<input type="text" name="email_cc_user" id="email_cc_user" value="<?php echo $options->email_cc_user; ?>" size="65">
											</td>								
										</tr>
										<tr>
											<td class="label_column"><label for="email_bcc_user"><?php _e("E-mail BCC:","bwge_back"); ?></label></td>
											<td>									
												<input type="text" name="email_bcc_user" id="email_bcc_user" value="<?php echo $options->email_bcc_user; ?>" size="65">
											</td>								
										</tr>
										<tr>
											<td class="label_column"><label for="email_subject1_user"><?php _e("Order notification e-mail subject:","bwge_back"); ?></label></td>
											<td>									
												<input type="text" name="email_subject1_user" id="email_subject1_user" value="<?php echo $options->email_subject1_user; ?>" size="65">
											</td>								
										</tr>
										
										<tr>
											<td class="label_column"><label for="email_body1_user"><?php _e("Order notification e-mail body:","bwge_back"); ?></label></td>
											<td>
												<?php wp_editor($options->email_body1_user, 'email_body1_user', array('teeny' => FALSE, 'textarea_name' => 'email_body1_user', 'media_buttons' => FALSE, 'textarea_rows' => 6)); ?>																			
												<div class="placeholders"  data-editor="email_body1_user"> 
													<span class="placeholder" data-placeholder="customer_name" onclick="bwgeInsertPlaceholder(this);"><?php _e("Customer name","bwge_back"); ?></span>
													<span class="placeholder" data-placeholder="item_count" onclick="bwgeInsertPlaceholder(this);"><?php _e("Item count","bwge_back"); ?></span>
													<span class="placeholder" data-placeholder="total_amount" onclick="bwgeInsertPlaceholder(this);"><?php _e("Total amount","bwge_back"); ?></span>

													<span class="placeholder" data-placeholder="site_url" onclick="bwgeInsertPlaceholder(this);"><?php _e("Site url","bwge_back"); ?></span>
												</div>
											</td>								
										</tr>
										<tr>
											<td class="label_column"><label for="email_subject_user"><?php _e("Order details e-mail subject:","bwge_back"); ?></label></td>
											<td>									
												<input type="text" name="email_subject_user" id="email_subject_user" value="<?php echo $options->email_subject_user; ?>" size="65">
											</td>								
										</tr>							
										<tr>
											<td class="label_column"><label for="email_body_user"><?php _e("Order details e-mail body:","bwge_back"); ?></label></td>
											<td>
												<?php wp_editor($options->email_body_user, 'email_body_user', array('teeny' => FALSE, 'textarea_name' => 'email_body_user', 'media_buttons' => FALSE, 'textarea_rows' => 6)); ?>																			
												<div class="placeholders"  data-editor="email_body_user"> 
													<span class="placeholder" data-placeholder="customer_name" onclick="bwgeInsertPlaceholder(this);"><?php _e("Customer name","bwge_back"); ?></span>
													<span class="placeholder" data-placeholder="item_count" onclick="bwgeInsertPlaceholder(this);"><?php _e("Item count","bwge_back"); ?></span>
													<span class="placeholder" data-placeholder="total_amount" onclick="bwgeInsertPlaceholder(this);"><?php _e("Total amount","bwge_back"); ?></span>
													<span class="placeholder" data-placeholder="order_details_page" onclick="bwgeInsertPlaceholder(this);"><?php _e("Order details page","bwge_back"); ?></span>
													<span class="placeholder" data-placeholder="site_url" onclick="bwgeInsertPlaceholder(this);"><?php _e("Site url","bwge_back"); ?></span>
													<span class="placeholder" data-placeholder="order_details_table" onclick="bwgeInsertPlaceholder(this);"><?php _e("Order details table","bwge_back"); ?></span>
													<span class="placeholder" data-placeholder="shipping_info" onclick="bwgeInsertPlaceholder(this);"><?php _e("Shipping info","bwge_back"); ?></span>
													<span class="placeholder" data-placeholder="billing_info" onclick="bwgeInsertPlaceholder(this);"><?php _e("Billing info","bwge_back"); ?></span>											
												</div>
											</td>								
										</tr>
										<tr>
											<td class="label_column"><label for="email_subject2_user"><?php _e("Order failed e-mail subject:","bwge_back"); ?></label></td>
											<td>									
												<input type="text" name="email_subject2_user" id="email_subject2_user" value="<?php echo $options->email_subject2_user; ?>" size="65">
											</td>								
										</tr>							
										<tr>
											<td class="label_column"><label for="email_body2_user"><?php _e("Order failed e-mail body:","bwge_back"); ?></label></td>
											<td>
												<?php wp_editor($options->email_body2_user, 'email_body2_user', array('teeny' => FALSE, 'textarea_name' => 'email_body2_user', 'media_buttons' => FALSE, 'textarea_rows' => 6)); ?>																			
												<div class="placeholders"  data-editor="email_body3_user"> 
													<span class="placeholder" data-placeholder="customer_name" onclick="bwgeInsertPlaceholder(this);"><?php _e("Customer name","bwge_back"); ?></span>								
													<span class="placeholder" data-placeholder="site_url" onclick="bwgeInsertPlaceholder(this);"><?php _e("Site url","bwge_back"); ?></span>
												</div>
											</td>								
										</tr>
										<tr>
											<td class="label_column"><label for="email_mode_user"><?php _e("Mode","bwge_back"); ?>:</label></td>
											<td>
												<input type="radio" class="inputbox" id="email_mode_user0" name="email_mode_user" <?php echo (($options->email_mode_user) ? '' : 'checked="checked"'); ?> value="0" >
												<label for="email_mode_user0"><?php _e("Text","bwge_back"); ?></label>
												<input type="radio" class="inputbox" id="email_mode_user1" name="email_mode_user" <?php echo (($options->email_mode_user) ? 'checked="checked"' : ''); ?> value="1" >
												<label for="email_mode_user1">HTML</label>									
											</td>								
										</tr>							
									</tbody>
								</table>	
							</div>								
						</div>		
					</div>	
					<div id="checkout_options_panel" <?php if(BWGEHelper::get("active_tab") == "#checkout_options_panel" ) echo 'style="display: block;"'; else echo 'style="display: none;"' ;?>>
								
						<table class="checkout bwge_edit_table">
							<tbody>	
								<tr>
									<td class="label_column"><label for="country"><?php _e("Allow guest checkout:","bwge_back"); ?></label></td>
									<td>
										<input type="radio" class="inputbox" id="enable_guest_checkout1" name="enable_guest_checkout" <?php echo (($options->enable_guest_checkout) ? 'checked="checked"' : ''); ?> value="1" >
										<label for="enable_guest_checkout1"><?php _e("Yes","bwge_back"); ?></label>										
										<input type="radio" class="inputbox" id="enable_guest_checkout0" name="enable_guest_checkout" <?php echo (($options->enable_guest_checkout) ? '' : 'checked="checked"'); ?> value="0" >
										<label for="enable_guest_checkout0"><?php _e("No","bwge_back"); ?></label>

									</td>
								</tr>
								<tr>
									<td class="label_column"><label for="country"><?php _e("Enable shipping:","bwge_back"); ?></label></td>
									<td>
										<input type="radio" class="inputbox" id="enable_shipping1" name="enable_shipping" <?php echo (($options->enable_shipping) ? 'checked="checked"' : ''); ?> value="1" >
										<label for="enable_shipping1"><?php _e("Yes","bwge_back"); ?></label>									
										<input type="radio" class="inputbox" id="enable_shipping0" name="enable_shipping" <?php echo (($options->enable_shipping) ? '' : 'checked="checked"'); ?> value="0" >
										<label for="enable_shipping0"><?php _e("No","bwge_back"); ?></label>
	
									</td>
								</tr>

								<tr>
									<td class="label_column"><label for="country"><?php _e("Show digital download in orders:","bwge_back"); ?></label></td>
									<td>
										<input type="radio" class="inputbox" id="show_file_in_orders1" name="show_file_in_orders" <?php echo (($options->show_file_in_orders) ? 'checked="checked"' : ''); ?> value="1" >
										<label for="show_file_in_orders1"><?php _e("Yes","bwge_back"); ?></label>										
										<input type="radio" class="inputbox" id="show_file_in_orders0" name="show_file_in_orders" <?php echo (($options->show_file_in_orders) ? '' : 'checked="checked"'); ?> value="0" >
										<label for="show_file_in_orders0"><?php _e("No","bwge_back"); ?></label>

									</td>
								</tr>	
								<tr>
									<td class="label_column"><label for="digital_download_expiry_days"><?php _e("Digital download link expiry days for guests:","bwge_back"); ?></label></td>
									<td>									
										<input type="text" class="inputbox" id="digital_download_expiry_days" name="digital_download_expiry_days" value="<?php echo $options->digital_download_expiry_days; ?>" >
				
									</td>
								</tr>							
								<tr>
									<td class="label_column"><label for="country"><?php _e("Quantity option for digital items:","bwge_back"); ?></label></td>
									<td>
										<input type="radio" class="inputbox" id="show_digital_items_count1" name="show_digital_items_count" <?php echo (($options->show_digital_items_count) ? 'checked="checked"' : ''); ?> value="1" >
										<label for="show_digital_items_count1"><?php _e("Yes","bwge_back"); ?></label>										
										<input type="radio" class="inputbox" id="show_digital_items_count0" name="show_digital_items_count" <?php echo (($options->show_digital_items_count) ? '' : 'checked="checked"'); ?> value="0" >
										<label for="show_digital_items_count0"><?php _e("No","bwge_back"); ?></label>

									</td>
								</tr>
								<tr>
									<td class="label_column"><label for="shipping_billing"><?php _e("Show shipping/billing fields:","bwge_back"); ?></label></td>
									<td>
										<input type="radio" class="inputbox" id="show_shipping_billing1" name="show_shipping_billing" <?php echo (($options->show_shipping_billing) ? 'checked="checked"' : ''); ?> value="1" >
										<label for="show_shipping_billing1"><?php _e("Yes","bwge_back"); ?></label>										
										<input type="radio" class="inputbox" id="show_shipping_billing0" name="show_shipping_billing" <?php echo (($options->show_shipping_billing) ? '' : 'checked="checked"'); ?> value="0" >
										<label for="show_shipping_billing0"><?php _e("No","bwge_back"); ?></label>

									</td>
								</tr>								
											
							</tbody>
						</table>												
					
                        <div class="sub_tab_container" data-type="payment">
                              <a href="#without_online" class="wd-btn wd-btn-secondary sub_tabs <?php if(BWGEHelper::get("payment_active_tab") == "#without_online" || BWGEHelper::get("payment_active_tab") == "" ) echo 'active_sub_tab';?>" ><?php _e("Without online payment","bwge_back"); ?></a>
                              <a href="#paypalstandart" class="wd-btn wd-btn-secondary sub_tabs <?php if(BWGEHelper::get("payment_active_tab") == "#paypalstandart" ) echo 'active_sub_tab';?>" ><?php _e("PayPal Standard","bwge_back"); ?></a>                              
                              <a href="#paypalexpress" class="wd-btn wd-btn-secondary sub_tabs <?php if(BWGEHelper::get("payment_active_tab") == "#paypalexpress" ) echo 'active_sub_tab';?>" ><?php _e("PayPal Express","bwge_back"); ?></a>
                              <a href="#stripe" class="wd-btn wd-btn-secondary sub_tabs  <?php if(BWGEHelper::get("payment_active_tab") == "#stripe" ) echo 'active_sub_tab';?>"><?php _e("Stripe","bwge_back"); ?></a>
                            
                            <div class="sections_tabs_container">  
                                <div id="without_online" <?php  if(BWGEHelper::get("payment_active_tab") == "#without_online" || BWGEHelper::get("payment_active_tab") == "" ) echo 'style="display: block;"'; else echo 'style="display: none;"'; ?>>
                                    <?php 
                                        $payment_row_without_online = $this->model->get_payments_row("without_online_payment");
                                        $this->without_online_payment($payment_row_without_online);
                                    ?>
                                </div>
                                <div id="paypalstandart" <?php  if(BWGEHelper::get("payment_active_tab") == "#paypalstandart" ) echo 'style="display: block;"'; else echo 'style="display: none;"'; ?>>
                                    <?php 
                                        $payment_row_paypal_standart = $this->model->get_payments_row("paypalstandart");
                                        $this->paypal_standart($payment_row_paypal_standart);
                                    ?>
                                </div>                                 
                                <div id="paypalexpress" <?php  if(BWGEHelper::get("payment_active_tab") == "#paypalexpress" ) echo 'style="display: block;"'; else echo 'style="display: none;"'; ?>>
                                    <div class="bwge_pro_option"><?php _e("Paypal Express is disabled in free version.","bwge");?></div>
                                    <?php     
                                        $this->paypal_express(false);
                                    ?>
                                </div> 
                                <div id="stripe" <?php  if(BWGEHelper::get("payment_active_tab") == "#stripe" ) echo 'style="display: block;"'; else echo 'style="display: none;"'; ?>>
                                    <div class="bwge_pro_option"><?php _e("Stripe is disabled in free version.","bwge");?></div>
                                    <?php 
                                        $this->stripe(false);
                                    ?>
                                </div>                                  
                            </div>
                        </div>                        
                    
                    </div>
					<p>
						<input type="button" name="btn_apply" value="<?php _e("Apply","bwge_back"); ?>" class="wd-btn wd-btn-primary wd-btn-icon wd-btn-apply" onclick="bwgeFormSubmit('apply');">
					</p>	
				</div>
			</div>

			

			<input id="page" name="page" type="hidden" value="<?php echo BWGEHelper::get('page');?>" />	
			<input id="task" name="task" type="hidden" value="" />	
			<input id="active_tab" name="active_tab" type="hidden" value="<?php echo BWGEHelper::get('active_tab');?>" />	
			<input id="email_active_tab" name="email_active_tab" type="hidden" value="<?php echo BWGEHelper::get('email_active_tab');?>" />	
			<input id="payment_active_tab" name="payment_active_tab" type="hidden" value="<?php echo BWGEHelper::get('payment_active_tab');?>" />	
			<input id="checkout_page" name="checkout_page" type="hidden" value="<?php echo $options->checkout_page;?>" />	
			<input id="cancel_page" name="cancel_page" type="hidden" value="<?php echo  $options->cancel_page;?>" />	
			<input id="orders_page" name="orders_page" type="hidden" value="<?php echo  $options->orders_page;?>" />
			<input type="hidden" name="options_stripe" />		                   
			<input type="hidden" name="options_paypalexpress" />		                   	                   
			<input type="hidden" name="options_paypalstandart" />		                   	                   
		</form>
		</div>	
		<script>			
            var _page = "<?php echo BWGEHelper::get('page') ? BWGEHelper::get('page') : "ecommerceoptions_bwge"; ?>";
            
            var _fieldsPS = '<?php echo htmlspecialchars_decode($payment_row_paypal_standart->options); ?>';


		</script>		
	<?php
	 
	}
	////////////////////////////////////////////////////////////////////////////////////////
	// Getters & Setters                                                                  //
	////////////////////////////////////////////////////////////////////////////////////////
	////////////////////////////////////////////////////////////////////////////////////////
	// Private Methods                                                                    //
	////////////////////////////////////////////////////////////////////////////////////////
	private function without_online_payment($row){
	?>
	
		<table class="bwge_edit_table bwge_without_online">
			<tr>
				<td><label for="title_w"><?php _e("Title:","bwge_back"); ?></label></td>
				<td>
					<input type="text"  autocomplete="off" id="title_w" value="<?php echo $row->name;?>" size="30" class="wd-required" onkeypress="removeRedBorder(this)" name="name_w">
				</td>
			</tr>
			<tr>
				<td><label for="published1_w"><?php _e("Published:","bwge_back"); ?></label></td>
				<td>
				  <input type="radio" class="inputbox" id="published1_w" name="published_w" <?php echo (($row->published) ? 'checked="checked"' : ''); ?> value="1" >
				  <label for="published1_w"><?php _e("Yes","bwge_back"); ?></label>                
				  <input type="radio" class="inputbox" id="published0_w" name="published_w" <?php echo (($row->published) ? '' : 'checked="checked"'); ?> value="0" >
				  <label for="published0_w"><?php _e("No","bwge_back"); ?></label>

				</td>
			</tr>			
		</table>
        <input type="hidden" name="id_w" value="<?php echo $row->id;?>">   
	<?php
	
	}
	
    private function paypal_standart($row){
	
	?>	
		<table class="bwge_edit_table paypal_standart">
			<tr>
				<td><label for="title_ps"><?php _e("Title:","bwge_back"); ?></label></td>
				<td>
					<input type="text"  autocomplete="off" id="title_ps" value="<?php echo $row->name;?>" size="30" name="name_ps" class="wd-required" onkeypress="removeRedBorder(this)">
				</td>
			</tr>
			<?php
				$this->api_settings($row,"ps");			
			?>
			<tr>
				<td><label for="published1_ps"><?php _e("Published:","bwge_back"); ?></label></td>
				<td>
				  <input type="radio" class="inputbox" id="published1_ps" name="published_ps" <?php echo (($row->published) ? 'checked="checked"' : ''); ?> value="1" >
				  <label for="published1_ps"><?php _e("Yes","bwge_back"); ?></label>                
				  <input type="radio" class="inputbox" id="published0_ps" name="published_ps" <?php echo (($row->published) ? '' : 'checked="checked"'); ?> value="0" >
				  <label for="published0_ps"><?php _e("No","bwge_back"); ?></label>

				</td>
			</tr>			
		</table>
        <input type="hidden" name="id_ps" value="<?php echo $row->id;?>">        
	<?php	
	}
	private function paypal_express($row){
		
	?>	
		<table class="bwge_edit_table paypal_express">
			<tr>
				<td><label for="title_p" class="bwge_disabled_label" ><?php _e("Title:","bwge_back"); ?></label></td>
				<td>
					<input type="text"  autocomplete="off" id="title_p" value="Paypal" size="30" name="name_p"  class="bwge_disabled_field" disabled readonly>                    
				</td>
			</tr>
                <tr>
					<td class="col_key">
						<label for="mode_p" class="bwge_disabled_label">
							<?php _e("Checkout mode","bwge_back"); ?>:
						</label>
					</td>

					<td class="col_value">
						<input type="radio" class="bwge_disabled_field" disabled readonly id="mode0_p" name="mode_p" checked="" value="0">
                          <label for="mode0_p" class="bwge_disabled_label"><?php _e("Sandbox","bwge_back"); ?></label>&nbsp;<input type="radio" class="bwge_disabled_field" disabled readonly id="mode1_p" name="mode_p" value="1">
                          <label for="mode1_p" class="bwge_disabled_label"><?php _e("Production","bwge_back"); ?></label>&nbsp;
               
					</td>
				</tr>
								
				<tr>
					<td class="col_key">
						<label for="paypal_user_p" class="bwge_disabled_label"><?php _e("User","bwge_back"); ?>:</label>
					</td>
					<td class="col_value">
						<input type="text" name="paypal_user_p" value="" id="paypal_user_p" class="bwge_disabled_field" disabled readonly>                      
					</td>
				</tr>
								
				<tr>
					<td class="col_key">
						<label for="paypal_password_p" class="bwge_disabled_label"><?php _e("Password","bwge_back"); ?>:</label>
					</td>
					<td class="col_value">
						<input type="text" name="paypal_password_p" value="" id="paypal_password_p" class="bwge_disabled_field" disabled readonly>                      
					</td>
				</tr>
								
				<tr>
					<td class="col_key">
						<label for="paypal_signature_p" class="bwge_disabled_label"><?php _e("Signature","bwge_back"); ?>:</label>
					</td>
					<td class="col_value">
						<input type="text" name="paypal_signature_p" value="" id="paypal_signature_p" class="bwge_disabled_field" disabled readonly>
					</td>
				</tr>
		
	
			<tr>
				<td><label for="published1_p" class="bwge_disabled_label"><?php _e("Published:","bwge_back"); ?></label></td>
				<td>
				  <input type="radio" class="bwge_disabled_field" disabled readonly id="published1_p" name="published_p" checked="checked" value="1" >
				  <label for="published1_p" class="bwge_disabled_label"><?php _e("Yes","bwge_back"); ?></label>                
				  <input type="radio" class="bwge_disabled_field" disabled readonly id="published0_p" name="published_p"  value="0" >
				  <label for="published0_p" class="bwge_disabled_label"><?php _e("No","bwge_back"); ?></label>
				</td>
			</tr>			
		</table>
        <input type="hidden" name="id_p" value="">        
	<?php	
	}
	
	private function stripe($row){
		$cc_fields = array('NAME' =>0,'CARD_NUMBER'=>1, 'CVC'=>1, 'EXPIRATION_MONTH'=>1, 'EXPIRATION_YEAR'=>1,'ADDRESS_LINE_1' =>0,'ADDRESS_LINE_2' =>0,'CITY' =>0,'STATE' =>0, 'ZIP_CODE' =>0 ,'ADDRESS_COUNTRY' =>0); ;
	?>
        <table class="bwge_edit_table stripe">
            <tr>
                <td><label for="title_s" class="bwge_disabled_label"><?php _e("Title:","bwge_back"); ?></label></td>
                <td>
                    <input type="text"  autocomplete="off" id="title_s" value="Stripe" size="30" name="name_s"   class="bwge_disabled_field" disabled readonly>                   
                </td>
            </tr>
            <tr>
                <td class="col_key">
                    <label for="mode_s" class="bwge_disabled_label">
                        <?php _e("Checkout mode","bwge");?>:
                    </label>
                </td>

                <td class="col_value">
                    <input type="radio" class="bwge_disabled_field" disabled readonly id="mode0_s" name="mode_s" checked="" value="0" onclick="showField(this);">
                      <label for="mode0_s" class="bwge_disabled_label"><?php _e("Test","bwge");?></label>&nbsp;<input type="radio" class="bwge_disabled_field" disabled readonly id="mode1_s" name="mode_s" value="1" onclick="showField(this);">
                      <label for="mode1_s" class="bwge_disabled_label"><?php _e("Live","bwge");?></label>&nbsp;
                            
                </td>
			</tr>
            <tr>
                <td class="col_key" class="bwge_disabled_label">
                    <label for="test_secret_key_s" class="bwge_disabled_label"><?php _e("Test secret key","bwge");?>:</label>
                </td>
                <td class="col_value">
                    <input type="text" name="test_secret_key_s"  id="test_secret_key_s" class="bwge_disabled_field" disabled readonly>
                    
                </td>
            </tr> 
            <tr>
                <td class="col_key" class="bwge_disabled_label">
                    <label for="test_secret_key_s" class="bwge_disabled_label"><?php _e("Test publishable key","bwge");?>:</label>
                </td>
                <td class="col_value">
                    <input type="text" name="test_publishable_key_s"  id="test_publishable_key_s" class="bwge_disabled_field" disabled readonly>
                    
                </td>
            </tr>              
            <?php 
            //$this->api_settings($row, "s");
            foreach( $cc_fields as $key => $field){ 
                    
                    if($field == 1)	{
                        $list = "This field is required";
                    }	
                    else{
                        $list = "";
                        $r_options = array(array('value'=>0, 'text'=> 'Hide'), array('value'=>1, 'text' => 'Show'),array('value'=>2, 'text' => 'Show and require'));
                        $i = 0;
                        
                        foreach($r_options as $r_option){
                                $checked = ($r_option["value"] == 1) ? "checked" : "";
                                $list.= '<input type="radio" id="'.$key.$i.'" name="'.$key.'" '.$checked.' value="'.$r_option["value"].'" class="bwge_disabled_field" disabled readonly >
                                      <label for="'.$key.$i.'" class="bwge_disabled_label">'.$r_option["text"].'</label>&nbsp;';
                                $i++;	  
                            }
                    }
                ?>
                    <tr>
                        <td class="col_key">
                            <label for="<?php echo $key; ?>" class="bwge_disabled_label">
                                <?php echo ucfirst(strtolower(str_replace("_"," ",$key) )) ; ?>:
                            </label>
                        </td>

                        <td class="col_value">
                            <?php echo $list ?>
                        </td>
                    </tr>
                <?php 
                }
                ?>
            <tr>
                <td><label for="published1_s" class="bwge_disabled_label"><?php _e("Published:","bwge_back"); ?></label></td>
                <td>
                  <input type="radio"  id="published1_s" name="published_s" checked="checked" value="1" class="bwge_disabled_field" disabled readonly>
                  <label for="published1_s" class="bwge_disabled_label"><?php _e("Yes","bwge_back"); ?></label>
                  
                  <input type="radio"  id="published0_s" name="published_s"  value="0" class="bwge_disabled_field" disabled readonly >
                  <label for="published0_s" class="bwge_disabled_label"><?php _e("No","bwge_back"); ?></label>
                </td>
            </tr>	
        </table>
        <input type="hidden" name="id_s" value="">
	<?php	
	}
	
	private function api_settings($row, $type){
		$lists =  $this->model->get_payments_lists($row) ;
		$class_name = $row->class_name;
		$row_fields = $row->fields ;
		$fields = $row->field_types ;
        $disabled = ($type == "p" || $type == "s" ) ? " disabled readonly" : "";
        $disabled_label_class = ($type == "p" || $type == "s" ) ? ' class="bwge_disabled_label" ' : "";	
        $disabled_field_class = ($type == "p" || $type == "s" ) ? ' class="bwge_disabled_field"' : "";
		foreach( $fields as $key => $field){ 
			if($field['type'] == 'radio'){
		?>
				<tr>
					<td class="col_key">
						<label for="<?php echo $key."_".$type; ?>" <?php echo $disabled_label_class ;?>>
							<?php echo $field['text']; ?>:
						</label>
					</td>

					<td class="col_value">
						<?php 
							$i = 0;
							foreach($lists['radio'][$key] as $radio){
								$checked = ($radio["value"] == $row_fields->$key) ? "checked" : "";
								echo '<input type="radio" '.$disabled_field_class.' id="'.$key.$i.'_'.$type.'" name="'.$key.'_'.$type.'" '.$checked.$disabled.' value="'.$radio["value"].'" '.$field['attributes'].'>
									  <label for="'.$key.$i.'_'.$type.'" '.$disabled_label_class.'>'.$radio["text"].'</label>&nbsp;';
								$i++;	  
							}
						?>
                        
                        <?php if($type == "p" || $type == "s" ){
                            echo '<div class="bwge_pro_option"><small>'. __("This option is disabled in free version.","bwge").'</small></div>';
                        }
                        ?>

					</td>
				</tr>
			<?php 
			}
			elseif($field['type'] == 'select'){
			?>
				<tr>
					<td class="col_key">
						<label for="<?php echo $key.'_'.$type; ?>" <?php echo $disabled_label_class ;?>>
							<?php echo $field['text']; ?>:
						</label>
					</td>

					<td class="col_value">	
						<select name="<?php echo $key.'_'.$type;?>" <?php echo $disabled." ".$disabled_field_class; ?>>
						<?php 								
							foreach($field['options'] as $option){				
								$selected = ($option["value"] == $row_fields->$key) ? "selected" : "";
								echo '<option value="'.$option["value"].'" '.$selected.'>'.$option["text"].'</option>';
							}
						
						?>
						</select>
                        <?php if($type == "p" || $type == "s" ){
                            echo '<div class="bwge_pro_option"><small>'. __("This option is disabled in free version.","bwge").'</small></div>';
                        }
                        ?>
					</td>
				</tr>
			<?php 
			}
			else{
			?>					
				<tr>
					<td class="col_key">
						<label for="<?php echo $key.'_'.$type; ?>" <?php echo $disabled_label_class ;?>><?php echo $field['text']; ?>:</label>
					</td>
					<td class="col_value">
						<input type="text" name="<?php echo $key.'_'.$type; ?>" value="<?php echo $row_fields->$key;?>" id="<?php echo $key.'_'.$type; ?>" <?php echo $field['attributes'];?> <?php echo $disabled." ".$disabled_field_class; ?> />
                        <?php if($type == "p" || $type == "s" ){
                            echo '<div class="bwge_pro_option"><small>'. __("This option is disabled in free version.","bwge").'</small></div>';
                        }
                        ?>
					</td>
				</tr>
			<?php
			}
		}
	}    
	////////////////////////////////////////////////////////////////////////////////////////
	// Listeners                                                                          //
	////////////////////////////////////////////////////////////////////////////////////////
}