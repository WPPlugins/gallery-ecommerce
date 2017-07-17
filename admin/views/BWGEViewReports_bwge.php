<?php

class BWGEViewReports_bwge extends BWGEView{

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
		add_action('wp_ajax_search'	, 'search');

		$report_data  = $this->model->get_report_view_data();
		
		$decimals = 2;
		$options = $this->model->get_options();
		$images = $this->model->get_images();
		$currency = $report_data->currency;
		global $WD_BWGE_UPLOAD_DIR;
		?>
	
		<script src="<?php echo WD_BWGE_URL?>/js/ecommerce/reports_bwge.js"></script>
		<script src="<?php echo WD_BWGE_URL?>/js/ecommerce/calendar.js"></script>
		<script src="<?php echo WD_BWGE_URL?>/js/ecommerce/calendar_function.js"></script>
		<script src="<?php echo WD_BWGE_URL?>/js/ecommerce/src/jquery.flot.min.js"></script>
		<script src="<?php echo WD_BWGE_URL?>/js/ecommerce/src/jquery.flot.pie.min.js"></script>
		<script src="<?php echo WD_BWGE_URL?>/js/ecommerce/src/jquery.flot.resize.min.js"></script>
		<script src="<?php echo WD_BWGE_URL?>/js/ecommerce/src/jquery.flot.stack.min.js"></script>
		<script src="<?php echo WD_BWGE_URL?>/js/ecommerce/src/jquery.flot.time.min.js"></script>
		<script src="<?php echo WD_BWGE_URL?>/js/ecommerce/select.js"></script>

		<link rel="stylesheet" type="text/css" href="<?php echo WD_BWGE_URL?>/css/jquery.jqplot.css">		
		<link rel="stylesheet" type="text/css" href="<?php echo WD_BWGE_URL?>/css/calendar-jos.css">



		<div class="bwge">
          <div style="font-size: 14px; font-weight: bold;">
            <?php echo __('This section allows you to manage reports.', 'bwge_back'); ?>
            <a style="color: #00A0D2; text-decoration: none;" target="_blank" href="https://galleryecommerce.com/gallery-ecommerce-set-up/reports/"><?php echo __('Read More in User Manual', 'bwge_back'); ?></a>
          </div>           
		<form name="adminForm" id="adminForm" action="" method="post">
         <?php wp_nonce_field('nonce_bwge', 'nonce_bwge'); ?>   
		<div class="tabs" id="tab_group_reports">
			<button class="year wd-btn  <?php if(BWGEHelper::get('tab_index') == 'year' || BWGEHelper::get('tab_index') == '') echo 'wd-btn-secondary'; else echo 'wd-btn-primary'; ?>" onclick="onTabActivated('year')"><?php _e("Year","bwge_back"); ?></button>
			<button class="last_month wd-btn  <?php if(BWGEHelper::get('tab_index') == 'last_month') echo 'wd-btn-secondary'; else echo 'wd-btn-primary'; ?>" onclick="onTabActivated('last_month')"><?php _e("Last month","bwge_back"); ?></button>
			<button class="this_month wd-btn <?php if(BWGEHelper::get('tab_index') == 'this_month') echo 'wd-btn-secondary'; else echo 'wd-btn-primary'; ?>" onclick="onTabActivated('this_month')"><?php _e("This month","bwge_back"); ?></button>
			<button class="last_week wd-btn <?php if(BWGEHelper::get('tab_index') == 'last_week') echo 'wd-btn-secondary'; else echo 'wd-btn-primary'; ?>" onclick="onTabActivated('last_week')"><?php _e("Last week","bwge_back"); ?></button>
			<button class="custom wd-btn <?php if(BWGEHelper::get('tab_index') == 'custom') echo 'wd-btn-secondary'; else echo 'wd-btn-primary'; ?>" onclick="onTabActivated('custom')"><?php _e("Custom","bwge_back"); ?></button>
		</div>
		<?php

			if(BWGEHelper::get('tab_index') == "custom"){?>
				<div class="date-range">
					<table class="adminlist table-striped search_table" width="50%">
						<tbody>
							<tr>
								<td><label for="start_date"><?php _e("Date from","bwge_back"); ?></label></td>
								<td>
									<input id="start_date"  type="text" value="<?php echo BWGEHelper::get('start_date') ? BWGEHelper::get('start_date') : $report_data->start_date; ?>" name="start_date" />
									<input class="calendar_button" type="reset" onclick="return showCalendar('start_date','%Y-%m-%d');" value="" />
								</td>
								<td><label for="start_date"><?php _e("Date to","bwge_back"); ?></label></td>
								<td>
									<input id="end_date"  type="text" value="<?php echo BWGEHelper::get('end_date') ? BWGEHelper::get('end_date') : $report_data->end_date ; ?>" name="end_date" />
									<input class="calendar_button" type="reset" onclick="return showCalendar('end_date','%Y-%m-%d');" value="" />
								</td>
								<td>
									<a href="#" class="wd-search-btn" onclick="onTabActivated('custom');return false;" style="height:18px"><span></span></a>
									<a href="#"  class="wd-reset-btn" onclick="document.getElementById('start_date').value='';document.getElementById('end_date').value='';onTabActivated('custom');return false;" style="height:18px"><span></span></a>
								</td>
							</tr>
						</tbody>
					</table>
				</div>
				<?php 
				}
				if($currency !== NULL){
				?>
				<div class="report-wrapper current">
					<div class="reports">
						<?php if( $report_data->start_date || $report_data->end_date ){	?>

						<table class="adminlist table report" >
							<tbody>
								<tr class="wd_reports_row">
									<td class="col_value" colspan="3">
										<select name="sold_products" id="sold_products" onchange="this.form.submit()">
											<option value=""><?php _e("All","bwge_back"); ?></option>
											<option value="downloads"<?php if(isset($_POST["sold_products"]) && $_POST["sold_products"]=="downloads") echo "selected='selected'" ?>><?php _e("Downloads","bwge_back"); ?></option>
											<option value="prints_and_products" <?php if(isset($_POST["sold_products"]) && $_POST["sold_products"]=="prints_and_products") echo "selected='selected'" ?>><?php _e("Prints and products","bwge_back"); ?></option>
										</select>
									</td>	
									<td class="col_value">
										<select id="images" >											
											<option value=""><?php _e("All","bwge_back"); ?></option>					
										</select>
										
										<script>
										(function ($) {
										var images = '<?php echo addslashes(json_encode($images));?>';
										images = JSON.parse(images);
										ddData = [];
										var filterImageId = "<?php echo isset($_POST["image_id"]) ? $_POST["image_id"] : "";?>";
							
										for(var i = 0; i< images.length; i++){
											var data = {};
											var image = images[i];
						
											var selected = (filterImageId == image.id) ? true : false;
											
											data.text = image.filename.length > 25 ? image.filename.substr(0,25) + "..." : image.filename;
											data.value = image.id;
											data.selected = selected;
											data.imageSrc = image.filetype.indexOf('EMBED') != -1 ? image.thumb_url : "<?php echo site_url() . '/' . $WD_BWGE_UPLOAD_DIR; ?>" + image.thumb_url;
											ddData.push(data);
											
										}
					
										jQuery('#images').ddslick({
											data: ddData,
											width: 274,
											imagePosition: "left",
											selectText: "All",
											onSelected: function (data) {
												
											}
										});
										jQuery('.filter_images').keyup(function () {

											var valthis = jQuery(this).val().toLowerCase();
											var num = 0;
											jQuery('#images .dd-option-text').each(function () {
												var text = jQuery(this).text().toLowerCase();
												if(text.indexOf(valthis) != -1) { 
													jQuery(this).closest("li").show();
												} 
											    else{ 
													jQuery(this).closest("li").hide();
												}

											});

										});
										})(jQuery);
										</script>
									</td>
                                     <td rowspan="6"> 
                                        <div id="placeholder_wrapper">
                                            <div id="placeholder" class="chart-placeholder main" style="width:650px; height:500px;"></div>
                                        </div>                                     
                                     </td>   
												
								</tr>		
								<!-- sales in this period -->
								<tr class="wd_reports_row">
									<td width="1%" class="type type-color-sales">
									</td>
									<td  width="2%">
										<input type="checkbox" checked="checked" id="total_seals" class="wd-chart" value="total_seals" onclick="wd_ShobwgetCharts();" />
									</td>
									<td class="col_key">
										<label  for="total_seals">
											<?php echo __('Sales in this period',"bwge_back"); ?>:
										</label>
									</td>
									<td class="col_value">
										<?php echo number_format($report_data->total_seals, $decimals)." ".$currency; ?>
									</td>

								</tr>
								<!-- average monthly sales -->
								<tr class="wd_reports_row">
									<td width="1%" class="type type-color-average">
									</td>					
									<td  width="2%">
										<input type="checkbox" checked="checked" id="average_sales" class="wd-chart" value="average_sales" onclick="wd_ShobwgetCharts();" />
									</td>					
									<td class="col_key">
										<label for="average_sales">
											<?php echo $report_data->average_type == "monthly" ? __('AVERAGE MONTHLY SALES',"bwge_back") : __('Average daily sales',"bwge_back"); ?>:
										</label>
									</td>
									<td class="col_value">
										<?php echo number_format($report_data->average_sales, $decimals)." ".$currency; ?>
									</td>					
								</tr>
								<!-- orders placed -->
								<tr class="wd_reports_row">
									<td width="1%" class="type type-color-orders">
									</td>					
									<td  width="2%">
										<input type="checkbox" checked="checked" id="orders_count" class="wd-chart" value="orders_count" onclick="wd_ShobwgetCharts();" />
									</td>					
									<td class="col_key">
										<label for="orders_count">
											<?php echo __('Orders placed',"bwge_back"); ?>:
										</label>
									</td>
									<td class="col_value">
										<?php echo $report_data->orders_count; ?>
									</td>
								</tr>	
								<!-- items purchased -->
								<tr class="wd_reports_row">
									<td width="1%" class="type type-color-items">
									</td>					
									<td  width="2%">
										<input type="checkbox" checked="checked" class="wd-chart" id="items_count" value="items_count" onclick="wd_ShobwgetCharts();" />
									</td>					
									<td class="col_key">
										<label for="items_count" >
											<?php echo __('Items purchased',"bwge_back"); ?>:
										</label>
									</td>
									<td class="col_value">
										<?php echo $report_data->items_count; ?>
									</td>					
								</tr>
								<!-- charged for shipping -->
								<tr class="wd_reports_row">
									<td width="1%" class="type type-color-shipping">
									</td>						
									<td  width="2%">
										<input type="checkbox" checked="checked" class="wd-chart" id="total_shipping_seals" value="total_shipping_seals" onclick="wd_ShobwgetCharts();" />
									</td>					
									<td class="col_key">
										<label for="total_shipping_seals">
											<?php echo __('Charged for shipping',"bwge_back"); ?>:
										</label>
									</td>
									<td class="col_value">
										<?php echo number_format($report_data->total_shipping_seals, $decimals)." ".$currency; ?>
									</td>				
								</tr>
							</tbody>
						</table>
					<?php 
						}
					?>
					</div>

				</div>

				<script type="text/javascript">
					var report_data = JSON.parse('<?php echo $report_data->json_data;?>') ;
					var default_currency_code = '<?php echo $currency;?>';

					var wdShop_totalSales = <?php echo (float)$report_data->total_seals ? $report_data->total_seals : 0; ?>;
					var wdShop_itemsCount = <?php echo (int)$report_data->items_count ? $report_data->items_count : 0; ?>;
					
					wd_Shop_drawReportChart();
				</script>
			
			<?php
			}
			else{
				echo  "<div class='error'><p>". __('The selected range contains more than one currency. Thus it is not possible to generate a report.','bwge_back'). "</p></div>";
			}
					
			?>
	
			<input id="page" name="page" type="hidden" value="<?php echo BWGEHelper::get('page');?>" />	
			<input type="hidden" name="task" value=""/>
			<input type="hidden" name="tab_index" value="<?php echo BWGEHelper::get('tab_index'); ?>"/>
		</form>
		</div>
<?php
	}
	
	
	
	
	
	
	
	
	
	

}?>