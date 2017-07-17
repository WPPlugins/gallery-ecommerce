<?php

class BWGEViewParameters_bwge extends BWGEView{

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
		$list = $this->model->get_lists();
		$parameter_types = $list["parameter_types"];
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
                <?php echo __('This section allows you to create, edit and delete pricelist parameters.', 'bwge_back'); ?>
                <a style="color: #00A0D2; text-decoration: none;" target="_blank" href="https://galleryecommerce.com/gallery-ecommerce-set-up/parameters/"><?php echo __('Read More in User Manual', 'bwge_back'); ?></a>
              </div>             
				<h2>
					<span><?php _e("Parameters","bwge_back"); ?></span>
					<a href="#" onclick="bwgeFormSubmit('edit');return false;" class="wd-btn wd-btn-primary wd-btn-icon wd-btn-add"><?php _e("Add New","bwge_back"); ?></a>
				</h2>
				<div class="wd-clear">
					<div class="wd-left">
						<?php
							BWGEHelper::search('Title', $search_value, 'adminForm');
						?>
					</div>
					<div class="buttons_div wd-right" style="text-align:right;margin-bottom:15px ;">
			
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
				<table class="wp-list-table widefat fixed posts bwge_list_table">
					<thead>
						<tr class="bwge_alternate">
							<th scope="col" id="cb" class="manage-column column-cb check-column" style="">
								<label class="screen-reader-text" for="cb-select-all-1"><?php _e("Select All","bwge_back"); ?></label>
								<input id="cb-select-all-1" type="checkbox">
							</th>

							<th class="col <?php if ($order_by == 'id') {echo $order_class;} ?>">
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

							<th class="col <?php if ($order_by == 'type') {echo $order_class;} ?>">
								<a onclick="bwgeFormInputSet('order_by', 'type');
											bwgeFormInputSet('asc_or_desc', '<?php echo ((isset($_POST['asc_or_desc']) && isset($_POST['order_by']) && (esc_html(stripslashes($_POST['order_by'])) == 'type') && esc_html(stripslashes($_POST['asc_or_desc'])) == 'asc') ? 'desc' : 'asc'); ?>');
											document.getElementById('adminForm').submit();return false;" href="">
								  <span><?php _e("Type","bwge_back"); ?></span><span class="sorting-indicator"></span>
								</a>
							</th>
							<th class="col <?php if ($order_by == 'published') {echo $order_class;} ?>">
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
											<input type="checkbox" name="ids[]" value="<?php echo $row->id;?>">
										</th>
										<td class="id column-id">
											<?php echo $row->id;?>
										</td>
										<td class="title column-title">
											<a href="admin.php?page=parameters_bwge&task=edit&id=<?php echo $row->id;?>">
												<?php echo $row->title;?>
											</a>
										</td>
										<td class="type column-type">
											<?php echo $parameter_types[(int)$row->type];?>
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
		$list = $this->model->get_lists();
		$parameter_types = $list["parameter_types"];
	?>
		
		<div class="bwge">
              <div style="font-size: 14px; font-weight: bold;">
                <?php echo __('This section allows you to create, edit  pricelist parameters.', 'bwge_back'); ?>
                <a style="color: #00A0D2; text-decoration: none;" target="_blank" href="https://galleryecommerce.com/gallery-ecommerce-set-up/parameters/"><?php echo __('Read More in User Manual', 'bwge_back'); ?></a>
              </div>         
			<h2><?php _e("Create new parameter","bwge_back"); ?></h2>					
			<form method="post" action="" id="adminForm">
                <?php wp_nonce_field('nonce_bwge', 'nonce_bwge'); ?>
				<p>
					<input type="button" name="btn_save" value="<?php _e("Save","bwge_back"); ?>" class="wd-btn wd-btn-primary wd-btn-icon wd-btn-save" onclick="if (bwge_spider_check_required('title', 'Title')) {return false;};bwgeFormSubmit('save');">
					<input type="button" name="btn_apply" value="<?php _e("Apply","bwge_back"); ?>" class="wd-btn wd-btn-primary wd-btn-icon wd-btn-apply" onclick="if (bwge_spider_check_required('title', 'Title')) {return false;};bwgeFormSubmit('apply');">
					<input type="button" name="btn_cancel" value="<?php _e("Cancel","bwge_back"); ?>" class="wd-btn wd-btn-primary wd-btn-icon wd-btn-cancel" onclick="bwgeFormSubmit('cancel');">
				</p>
               
				<table class="bwge_edit_table">
					<tbody>
						<tr>
							<td class="label_column" width="25%"><label for="title"><?php _e("Title","bwge_back"); ?>:<span style="color:#FF0000;">*</span></label></td>
							<td>
								<input type="text"  autocomplete="off" id="title" value="<?php echo $row->title;?>" size="30" name="title" class="wd-required" onkeypress="removeRedBorder(this)">
							</td>
						</tr>						
						<tr>
							<td class="label_column" width="25%"><label for="type"><?php _e("Type","bwge_back"); ?>:</label></td>
							<td>
								<select name="type" id="type" onchange="showParameterDefaultValues(this);" >
								<?php 
									for($i=1; $i<count($parameter_types); $i++){
										$selected = $row->type == $i ? "selected" : "";
										echo '<option value="'.$i.'" '.$selected.'>'.$parameter_types[$i].'</option>';
									}
								?>
								</select>
							</td>
						</tr>
						<tr>
							<td colspan="2"> 
								<div class="parameters_container <?php echo $row->type == 0  ? "hide" : "" ; ?>">	
									<p><?php _e("Default values:","bwge_back"); ?></p>	
									<div class="input_parameter parameters_values template">
										<input type="text" class="parameter_default_value" size="65">
									</div>
									<div class="textarea_parameter parameters_values template">
										<textarea class="parameter_default_value" cols="65" rows="4"></textarea>
									</div>								
									<div class="multi_select_parameter parameters_values template">
										<table class="multi_select_parameter_container">
											<tbody>
												<tr class="multi_select_parameter_row template">
													<td><input type="text" class="parameter_default_value"></td>															
													<td><img src="<?php echo WD_BWGE_URL . '/images/delete.png';?>" onclick="bwgeRemoveMultiSelectParameter(this);" class="pointer-cursor"></td>														
												</tr>														
												<tr class="multi_select_parameter_row">
													<td><input type="text" class="parameter_default_value"></td>	
													<td><img src="<?php echo WD_BWGE_URL . '/images/delete.png';?>" onclick="bwgeRemoveMultiSelectParameter(this);" class="pointer-cursor"></td>	
												</tr>
											</tbody>	
											<tfoot>
												<tr>
													<td colspan="2"><input type="button" value="<?php _e("Add value","bwge_back"); ?>" onclick="bwgeAddMultiSelectParameter(this);" class="wd-btn wd-btn-primary"></td>
												</tr>
											</tfoot>										
										</table>																								
									</div>								
									<div class="default_values">
										<?php
											$default_values = $row->default_values_array;
											
											if($row->type == 2 || $row->type == 1 ){ 								
											?>
												<div class="input_parameter parameters_values">
													<input type="text" class="parameter_default_value" size="65" value="<?php echo isset($default_values[0]) ? $default_values[0] : "";?>">
												</div>
											<?php	
											}
											elseif($row->type == 3){
											?>
												<div class="textarea_parameter parameters_values">
													<textarea class="parameter_default_value" cols="65" rows="4"><?php echo isset($default_values[0]) ? $default_values[0] : "";?></textarea>
												</div>	
											<?php	
											}
											elseif($row->type == 4 || $row->type == 5 || $row->type == 6){
											?>
												<div class="multi_select_parameter parameters_values">
													<table class="multi_select_parameter_container">
														<tbody>	
															<tr class="multi_select_parameter_row template">
																<td><input type="text" class="parameter_default_value"></td>															
																<td><img src="<?php echo WD_BWGE_URL . '/images/delete.png';?>" onclick="bwgeRemoveMultiSelectParameter(this);" class="pointer-cursor"></td>														
															</tr>											
															<?php foreach($default_values as $default_value){
															?>
															<tr class="multi_select_parameter_row">
																<td><input type="text" class="parameter_default_value" value="<?php echo $default_value;?>"></td>	
																<td><img src="<?php echo WD_BWGE_URL . '/images/delete.png';?>" onclick="bwgeRemoveMultiSelectParameter(this);" class="pointer-cursor"></td>	
															</tr>
															<?php
															}
															?>
														</tbody>	
														<tfoot>
															<tr>
																<td colspan="2"><input type="button" value="<?php _e("Add value","bwge_back"); ?>" onclick="bwgeAddMultiSelectParameter(this);" class="wd-btn wd-btn-primary"></td>
															</tr>
														</tfoot>										
													</table>																								
												</div>								
											<?php
											}	
										?>
									</div>
								</div>							
							</td>
						</tr>	
					
						<tr>
							<td><label for="published1"><?php _e("Published:","bwge_back"); ?></label></td>
							<td>
							  <input type="radio" class="inputbox" id="published1" name="published" <?php echo (($row->published) ? 'checked="checked"' : ''); ?> value="1" >
							  <label for="published1"><?php _e("Yes","bwge_back"); ?></label>                            
							  <input type="radio" class="inputbox" id="published0" name="published" <?php echo (($row->published) ? '' : 'checked="checked"'); ?> value="0" >
							  <label for="published0"><?php _e("No","bwge_back"); ?></label>

							</td>
						</tr>
					</tbody>
				</table>				

				<input id="page" name="page" type="hidden" value="<?php echo BWGEHelper::get('page');?>" />	
				<input id="task" name="task" type="hidden" value="" />	
				<input id="id" name="id" type="hidden" value="<?php echo $row->id;?>" />	
				<input id="default_values" name="default_values" type="hidden" value="" />	
			</form>
		</div>
		
		<script>			
		  var _page = "<?php echo BWGEHelper::get('page') ? BWGEHelper::get('page') : "options_bwge"; ?>";
		  var deleteImageUrl = "<?php echo WD_BWGE_URL . '/images/delete.png';?>";
		  var addImageUrl = "<?php echo WD_BWGE_URL . '/images/add.png';?>";
		</script>		
	<?php
	 
	}
	
	public function explore(){
		$rows = $this->model->get_rows(1);
		$list = $this->model->get_lists();
		$parameter_types = $list["parameter_types"];		
		wp_print_scripts('jquery');
		$page_nav = $this->model->page_nav();
		$search_value = ((isset($_POST['search_value'])) ? esc_html(stripslashes($_POST['search_value'])) : '');
		$asc_or_desc = ((isset($_POST['asc_or_desc'])) ? esc_html(stripslashes($_POST['asc_or_desc'])) : 'asc');
		$order_by = (isset($_POST['order_by']) ? esc_html(stripslashes($_POST['order_by'])) : 'id');
		$order_class = 'manage-column column-title sorted ' . $asc_or_desc;
		
		$per_page = $this->model->per_page();
		$pager = 0;		
		
	?>	
		
        <?php if (get_bloginfo('version') >= '4.5') { ?>
        <link media="all" type="text/css" href="<?php echo get_admin_url(); ?>load-styles.php?c=1&dir=ltr&load%5B%5D=dashicons,admin-bar,common,forms,admin-menu,dashboard,list-tables,edit,revisions,media,themes,about,nav-menus,widgets,site-icon,&load%5B%5D=l10n,buttons,wp-auth-check,media-views" rel="stylesheet">
        <?php } 
        else{
        ?>
        <link media="all" type="text/css" href="<?php echo get_admin_url(); ?>load-styles.php?c=1&amp;dir=ltr&amp;load=admin-bar,wp-admin,dashicons,buttons,wp-auth-check" rel="stylesheet">
        <?php 
        }
        if (get_bloginfo('version') < '3.9') { ?>
        <link media="all" type="text/css" href="<?php echo get_admin_url(); ?>css/colors<?php echo ((get_bloginfo('version') < '3.8') ? '-fresh' : ''); ?>.min.css" id="colors-css" rel="stylesheet">
        <?php } ?>
		<link rel="stylesheet" href="<?php echo WD_BWGE_URL . '/css/bwge_ecommerce.css';?>">
		<script src="<?php echo WD_BWGE_URL . '/js/ecommerce/parameters_bwge.js'; ?>" type="text/javascript"></script>
		<script src="<?php echo WD_BWGE_URL . '/js/ecommerce/admin_main.js'; ?>" type="text/javascript"></script>
		
		<form method="post" action="" id="adminForm" class="wrap wp-core-ui" style="width:99%; margin: 0 auto;">
			<div class="bwge">
				<h2>
					<span><?php _e("Parameters","bwge_back"); ?></span>				
				</h2>
				<div style="text-align:right;"><input type="button" value="Add" class="wd-btn wd-btn-primary " onclick="bwgeSelectAllClick();" style=" width:120px;">	</div>
				<div class="tablenav top">
					<?php
						BWGEHelper::search('Title', $search_value, 'adminForm');	
						BWGEHelper::html_page_nav($page_nav['total'],$pager++, $page_nav['limit'], 'adminForm', $per_page);						
					?>
				</div>	
				<table class="wp-list-table widefat fixed pages bwge_list_table">
					<thead>
						<tr>
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

							<th class="col <?php if ($order_by == 'type') {echo $order_class;} ?>">
								<a onclick="bwgeFormInputSet('order_by', 'type');
											bwgeFormInputSet('asc_or_desc', '<?php echo ((isset($_POST['asc_or_desc']) && isset($_POST['order_by']) && (esc_html(stripslashes($_POST['order_by'])) == 'type') && esc_html(stripslashes($_POST['asc_or_desc'])) == 'asc') ? 'desc' : 'asc'); ?>');
											document.getElementById('adminForm').submit();return false;" href="">
								  <span><?php _e("Type","bwge_back"); ?></span><span class="sorting-indicator"></span>
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
									
						?>
									<tr id="tr_<?php echo $iterator; ?>" <?php echo $alternate; ?> data-id="<?php echo $row->id;?>" data-title="<?php echo $row->title;?>" data-type="<?php echo $row->type;?>" data-default-values = '<?php echo stripslashes($row->default_values);?>'>
										<th scope="row" class="check-column">
											<input type="checkbox" name="ids[]" value="<?php echo $row->id;?>" class="cid" >
										</th>
										<td class="id column-id">
											<?php echo $row->id;?>
										</td>
										<td class="title column-title">
											<a href="#" onclick="bwgeSelectClick(this);">
												<?php echo $row->title;?>
											</a>
										</td>
										<td class="type column-type">
											<?php echo $parameter_types[(int)$row->type];?>
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
	<?php  	
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