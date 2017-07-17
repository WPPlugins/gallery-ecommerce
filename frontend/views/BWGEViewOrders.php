<?php

class BWGEViewOrders extends BWGEViewFrontend{

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
		
		$orders = $this->model->get_orders();
		$options = $this->model->get_options();	

	 ?>
	<div class="bwge_main_container">
        <?php if($orders) {
        ?>
		<div class="bwge_orders">	
			<form method="post" id="bwge_orders_form">
				<div class="bwge_orders_wrap" action="">
					<?php 
						
						foreach ($orders  as $order){
							$total = 0;	
							if(empty($order->order_images) == false){	
						?>
						<div class="bwge_product_row_main_container">			
						<?php
							$i = 0;
                            $files = $order->files;
                            
							foreach($order->order_images as $order_product_row){									
						?>
							<div class="bwge_product_row_container_wrapper">
                                <div class="bwge_product_row_container">
                                    <div class="bwge_image_container_ecommerce">
                                        <img src="<?php echo $order_product_row->thumb_url;?>" alt="<?php echo $order_product_row->alt;?>" class="bwge_product_image">
                                        <?php 
                                            if($options->show_file_in_orders == 1 && empty($order->files) === false && 
                                             $order->status == "confirmed"){
                                               
                                               
                                                if(isset($files[$order_product_row->order_image_id])){
                                                    echo "<div class='bwge_files bwge_clear'>";
                                                    echo "<div class='bwge_download_files'><div><a href='#' data-o-i='".$order_product_row->order_image_id."' onclick='bwgeDownloadFile(this);return false;' >". $files[$order_product_row->order_image_id]."</a></div></div>";                  
                                                    echo "</div>";	
                                                }                                                
                                            }
                        
                                        ?>
                                    </div>
                        
                                    <div class="bwge_product_name_container">
                                        <p class="bwge_product_name"><?php echo $order_product_row->image_name;?></p>
                                        <p class="bwge_pricelist_name"><?php echo $order_product_row->product_name;?></p>
                                        <?php 
                                        if($order_product_row->pricelist_download_item_id != 0){
                                        ?>
                                            <p class="bwge_product_longest_dimension"><?php echo $order_product_row->item_longest_dimension."px";?></p>
                                        <?php
                                        }
                                        ?>
                                        <p class="bwge_product_checkout_date"><?php echo date("M d, Y",strtotime($order->checkout_date)); ?>	</p>

                                    </div>
                                    <div class="bwge_product_price_container">
                                        <p class="bwge_product_price"><?php  echo __('Price', 'bwge');?>: <?php echo $order_product_row->final_price_text;?></p>
                                        <?php	if($order_product_row->pricelist_download_item_id == 0 || ( $order_product_row->pricelist_download_item_id  && $options->show_digital_items_count == 1 ) ){
                                        ?>
                                        <p class="bwge_product_count"><?php echo __('Count', 'bwge').": ".$order_product_row->products_count;?></p>	
                                        <?php 
                                        }
                       
                                        if($order_product_row->tax_rate){
                                        ?>	
                                        <p class="bwge_product_tax"><?php  echo __('Tax', 'bwge');?>: <?php echo $order_product_row->tax_rate ."%";?>  </p>			
                                        <?php 
                                        }
                                        $total += $order_product_row->subtotal;
                                        ?>									
                                        <p class="bwge_product_price_subtotal"><b><?php echo __('Subotal', 'bwge') .": ".$order_product_row->subtotal_text;?></b></p>
                                        <?php 
                                        $i++;
                                        if($i< count($order->order_images)){
                                            echo '<div class="bwge_divider"></div>';
                                        }
                                        
                                        ?>
                                    </div>
                               </div>
                                

							</div>
						<?php				
						}
						?>	
							<div class="bwge_divider"></div>
							<div class="bwge_total_container">
                                <?php
                                $shipping = $this->model->get_order_shipping($order->id);    
                                $total = $total + $shipping;
                                if( $shipping && $options->enable_shipping == 1){
                                ?>
                                    <p class="bwge_product_shipping"><?php  echo __('Shipping', 'bwge');?>: <?php echo  $order_product_row->currency_sign.number_format( $shipping,2) ;?>  </p> 
                                                               			
                                <?php
                                    
                                }
                                ?>
								<h4 class="bwge_product_price_total"><?php echo __('Total', 'bwge') .": ".$order_product_row->currency_sign.number_format($total,2);?></h4>
							</div>						
                            <div class="bwge_divider"></div>	
							<div class="bwge_view_item" data-id="<?php echo $order_product_row->order_image_id; ?>">
								<a href="<?php echo add_query_arg( array("order_id"=>$order->id,"task"=>"display_order" ),get_permalink($options->orders_page));?>" ><?php echo __('View details', 'bwge');?></a>
							</div>								
						</div>

					<?php
					}
					}
                    
                    
					?>
				</div>		
				<input type="hidden" name="task" >
				<input type="hidden" name="current_id" >
			</form>
		</div>
        <?php
        }
        else{
          echo "<h2>". __("No orders","bwge")."</h2>";
        }
        ?>
    </div>
    <script>
       var ajaxurl = '<?php echo add_query_arg(array('action' => 'bwge_download_file','controller' => 'orders','task' => 'download_file') , admin_url('admin-ajax.php')); ?>';
    </script>
	 <?php
	}
	
	public function display_order(){
		global $WD_BWGE_UPLOAD_DIR;
		$order = $this->model->get_orders($_GET["order_id"]);
		$options = $this->model->get_options();	
		$total = 0;
		$i = 0;		
		$shipping = $this->model->get_order_shipping($_GET["order_id"]); 
	?>	
		<div class="bwge_main_container">
			<div class="bwge_order">	
                <?php if($order) {
                ?>
				<form method="post" id="bwge_orders_form">
                    <p><a href="<?php echo get_permalink($options->orders_page);?>"><?php echo   __('Back to orders', 'bwge');?></a></p>
					<div class="bwge_order_wrap" >
						<div class="bwge_product_row_main_container">	
							<?php foreach($order->order_images as $order_product_row){					
							?>														
								<div class="bwge_product_row_container">
									<div class="bwge_image_container_ecommerce">
										<img src="<?php echo $order_product_row->thumb_url;?>" alt="<?php echo $order_product_row->alt;?>" class="bwge_product_image">
									</div>
                                    <div class="bwge_image_details">
                                        <div class="bwge_product_name_container">
                                            <div class="bwge_image_title">
                                                <p><strong><?php echo   __('Image details', 'bwge');?></strong></p>
                                                <p class="bwge_product_name"><?php echo   __('Image', 'bwge').": ".$order_product_row->image_name;?></p>
                                                <p class="bwge_pricelist_name"><?php echo  __('Pricelist', 'bwge').": ".$order_product_row->product_name;?></p>
                                                <p class="bwge_product_checkout_date"><?php echo __('Date', 'bwge').": ".date("M d, Y",strtotime($order->checkout_date)); ?>	</p>

                                            </div>
                                            <div class="bwge_parameters">
                                                <p><strong><?php echo   __('Image parameters', 'bwge');?></strong></p>
                                                <?php 
                                                if($order_product_row->pricelist_download_item_id != 0){
                                                ?>
                                                    <p class="bwge_product_longest_dimension"><?php echo  __('Size', 'bwge').": ".$order_product_row->item_longest_dimension."px";?></p>
                                                <?php
                                                }
                                                ?>
                                                <?php  if($order_product_row->pricelist_download_item_id == 0){
                                                ?>
                                                    <p class="bwge_product_parameters"><?php echo $order_product_row->selected_parameters_string; ?></p>
                                                <?php
                                                }
                                                ?>
                                            </div>

                                        </div>
                                        <div class="bwge_product_price_container_table">
                                            <table>
                                                <tr>
                                                    <td><?php  echo __('Price', 'bwge');?>: </td>
                                                    <td><p class="bwge_product_price"><?php echo $order_product_row->final_price_text;?></p></td>
                                                </tr>                                                        
                                                <?php	
                                                if($order_product_row->pricelist_download_item_id == 0 || ( $order_product_row->pricelist_download_item_id  && $options->show_digital_items_count == 1 ) ){
                                                ?>
                                                    <tr>
                                                        <td><?php  echo __('Count', 'bwge');?>: </td>
                                                        <td><p class="bwge_product_count"><?php echo $order_product_row->products_count;?></p></td>
                                                    </tr>                                                 
                            
                                                <?php 
                                                }
             
                                                if($order_product_row->tax_rate){
                                                ?>
                                                    <tr>
                                                        <td><?php  echo __('Tax', 'bwge');?>: </td>
                                                        <td><p class="bwge_product_shipping"><?php echo $order_product_row->tax_rate."%";?></p></td>
                                                    </tr>                                                   
                                                <?php
                                                }
                                                ?>
                                                <tr>
                                                    <td><?php  echo __('Subotal', 'bwge');?>: </td>
                                                    <td><p class="bwge_product_price_subtotal"><?php echo $order_product_row->subtotal_text;?></p></td>
                                                </tr>                                                
                                            </table>
                                            <?php                        
                                            $total += $order_product_row->subtotal;                                  
                                            $i++;
                                            ?>
                                        </div>
                                        
                                    </div>
								</div>
							<?php				
							}
							?>	
						</div>
						
						<?php if($options->show_file_in_orders == 1 && empty($order->files) === false && 
						 $order->status == "confirmed"){
                            echo "<div class='bwge_files'>";
							echo "<div class='bwge_download_files'><strong>".__('Download files', 'bwge')."</strong></div>";
							foreach($order->files as $order_image_id => $file){
								echo "<div>".$file." - <a href='#' data-o-i='".$order_image_id."' onclick='bwgeDownloadFile(this);return false;' >". __('Download file', 'bwge')."</a></div>";
							}
                            echo "</div>";							
						}
		
						?>
						
						<div class="billing_shipping_info">
							<div class="billing_info">
								<h4><?php echo __('Billing info', 'bwge');?></h4>
								<p><?php echo __('Name', 'bwge') .": ".$order->billing_data_name;?></p>
								<p><?php echo __('Email', 'bwge') .": ".$order->billing_data_email;?></p>
								<p><?php echo __('Country', 'bwge') .": ".$order->billing_data_country;?></p>
								<p><?php echo __('City', 'bwge') .": ".$order->billing_data_city;?></p>
								<p><?php echo __('Address', 'bwge') .": ".$order->billing_data_address;?></p>
								<p><?php echo __('Zip code', 'bwge') .": ".$order->billing_data_zip_code;?></p>
							</div>
							
							<div class="shipping_info">
								<h4><?php echo __('Shipping info', 'bwge');?></h4>
								<p><?php echo __('Name', 'bwge') .": ".$order->shipping_data_name;?></p>
								<p><?php echo __('Country', 'bwge') .": ".$order->shipping_data_country;?></p>
								<p><?php echo __('City', 'bwge') .": ".$order->shipping_data_city;?></p>
								<p><?php echo __('Address', 'bwge') .": ".$order->shipping_data_address;?></p>
								<p><?php echo __('Zip code', 'bwge') .": ".$order->shipping_data_zip_code;?></p>				
							</div>				
						</div>	
						
						<div class="bwge_total_container">
                            <?php
                                $total = $total + $shipping;
                                if($shipping && $options->enable_shipping == 1){
                            ?> 
                                <p class="bwge_product_shipping" style="margin-bottom:4px;"> <?php  echo __('Shipping:', 'bwge'). $order->currency_sign.number_format($shipping,2); ?></p>                               
                             <?php
                            }
                            ?> 
							<h4 class="bwge_product_price_total"><?php echo __('Total', 'bwge') .": ".$order->currency_sign.number_format($total,2);?></h4>
						</div>
							
							
					</div>		
					<input type="hidden" name="task" >
					<input type="hidden" name="current_id" >
				</form>
                <?php
                }
                else{
                  echo "<h2>". __("Not found","bwge")."</h2>";;
                }
                ?>
            </div>
		</div>
		<script>
       var ajaxurl = '<?php echo add_query_arg(array('action' => 'bwge_download_file','controller' => 'orders','task' => 'download_file') , admin_url('admin-ajax.php')); ?>';
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