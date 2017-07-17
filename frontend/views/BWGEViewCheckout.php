<?php

class BWGEViewCheckout extends BWGEViewFrontend{

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
	public function display_main_container(){
		$order_product_rows = $this->model->get_order_product_rows();
		$options = $this->model->get_options();

		$total = 0;
		$i = 0;

		foreach ($order_product_rows  as $order_product_row){
	?>
		<div class="bwge_img"></div>
		<div class="bwge_product_row_main_container checkout_main_container" data-iamge-order-id="<?php echo $order_product_row->order_image_id;?>" data-image-order-price="<?php echo $order_product_row->product_price;?>">

			<div class="bwge_image_container_ecommerce">
				<img src="<?php echo $order_product_row->thumb_url;?>" alt="<?php echo $order_product_row->alt;?>" class="bwge_product_image">

			</div>
			<div class="bwge_product_name_container">
				<p class="bwge_product_name"><?php echo preg_replace('/^.+[\\\\\\/]/', '',($order_product_row->alt ? $order_product_row->alt : $order_product_row->image_name) );?></p>
				<p class="bwge_pricelist_name"><?php echo $order_product_row->product_name;?></p>
				<?php
				if($order_product_row->pricelist_download_item_id != 0){
				?>
					<p class="bwge_product_longest_dimension"><?php echo $order_product_row->item_longest_dimension."px";?></p>
				<?php
				}
				?>

				<?php if($order_product_row->pricelist_download_item_id == 0 && empty($order_product_row->selectable_parameters) === false){
				?>
					<div class="image_parameters">
						<h6><?php echo __('Parameters', 'bwge'); ?></h6>
						<?php

							$selected_parameters = $order_product_row->selected_parameters;

							foreach($order_product_row->selectable_parameters as $parameter_id => $parameter){

								echo '<div class="parameter_row">';
								switch($parameter["type"]){
									case "1" :
										echo '<span>'.$parameter["title"].': '.$parameter["values"][0]["parameter_value"].'</span>';

										break;
									case "2" :

										echo '<div class="image_selected_parameter" data-parameter-id="'.$parameter_id.'" data-parameter-type = "'.$parameter["type"].'">';
										echo '<label for="parameter_input'.$i.'" style="display:block;">'.$parameter["title"].' </label>';
										echo '<input type="text" name="parameter_input'.$parameter_id.$order_product_row->id.$i.'" id="parameter_input'.$i.'"  value="'. $selected_parameters[$parameter_id] .'" onblur="updateImageOrderRow(this)" data-type="parameters">';
										echo '</div>';
										break;
									case "3" :
										echo '<div class="image_selected_parameter" data-parameter-id="'.$parameter_id.'" data-parameter-type = "'.$parameter["type"].'">';
										echo '<label for="parameter_textarea'.$i.'" style="display:block;">'.$parameter["title"].' </label>';
										echo '<textarea  name="parameter_textarea'.$parameter_id.$order_product_row->id.$i.'" id="parameter_textarea'.$i.'" onblur="updateImageOrderRow(this)" data-type="parameters">'. $selected_parameters[$parameter_id] .'</textarea>';
										echo '</div>';
										break;
									case "4" :
										echo '<div class="image_selected_parameter" data-parameter-id="'.$parameter_id.'" data-parameter-type = "'.$parameter["type"].'">';
										echo '<label for="parameter_select'.$parameter_id.$order_product_row->id.'" style="display:block;">'.$parameter["title"].' </label>';
										echo '<select name="parameter_select'.$parameter_id.$order_product_row->id.'" id="parameter_select'.$parameter_id.$order_product_row->id.$i.'"  onchange="updateImageOrderRow(this)" data-type="parameters">';
										echo '<option value="+*0">-Select-</option>';
										$already_existed_values = 0;

										foreach($parameter["values"] as $values){

											$selected = isset($selected_parameters[$parameter_id]) && $selected_parameters[$parameter_id] == html_entity_decode($values["parameter_value_price_sign"].'*'.$values["parameter_value_price"].'*'.$values["parameter_value"]) ? "selected" : "";
											if($selected == "selected"){
												$already_existed_values += $values["parameter_value_price_sign"].$values["parameter_value_price"];
											}
                                            $price_addon = $values["parameter_value_price"] == "0" ? "" : ' ('.$values["parameter_value_price_sign"].$options->currency_sign.number_format($values["parameter_value_price"],2).')';

											echo '<option value="'.$values["parameter_value_price_sign"].'*'.$values["parameter_value_price"].'*'.$values["parameter_value"].'" '.$selected.'>'.$values["parameter_value"].$price_addon.'</option>';
										}
										echo '</select>';
										echo '<input type="hidden" class="already_selected_values" value="'.$already_existed_values.'">';
										echo '</div>';
										break;
									case "5" :
										echo '<div class="image_selected_parameter" data-parameter-id="'.$parameter_id.'" data-parameter-type = "'.$parameter["type"].'">';
										echo '<label>'.$parameter["title"].'</label>';
										$already_existed_values = 0;
										$j = 0;
										foreach($parameter["values"] as $values){

											$checked = isset($selected_parameters[$parameter_id]) && $selected_parameters[$parameter_id] == html_entity_decode($values["parameter_value_price_sign"].'*'.$values["parameter_value_price"].'*'.$values["parameter_value"]) ? "checked" : "";

											if($checked == "checked"){
												$already_existed_values += $values["parameter_value_price_sign"].$values["parameter_value_price"];
											}
                                            $price_addon = $values["parameter_value_price"] == "0" ? "" : ' ('.$values["parameter_value_price_sign"].$options->currency_sign.number_format($values["parameter_value_price"],2).')';
											echo '<div>';
											echo '<input type="radio" name="parameter_radio'.$parameter_id.$order_product_row->id.$i.'"  id="parameter_radio'.$parameter_id.$order_product_row->id.$i.$j.'" value="'.$values["parameter_value_price_sign"].'*'.$values["parameter_value_price"].'*'.$values["parameter_value"].'"  onchange="updateImageOrderRow(this)" '.$checked.' data-type="parameters">';
											echo '<label for="parameter_radio'.$parameter_id.$order_product_row->id.$i.$j.'"> '.$values["parameter_value"].$price_addon.'</label>';
											echo '</div>';
											$j++;
										}
										echo '<input type="hidden" class="already_selected_values" value="'.$already_existed_values.'">';
										echo '</div>';
										break;
									case "6" :
										echo '<div class="image_selected_parameter" data-parameter-id="'.$parameter_id.'" data-parameter-type = "'.$parameter["type"].'">';
										echo '<label>'.$parameter["title"].'</label>';
										$already_existed_values = 0;
										$j = 0;
										foreach($parameter["values"] as $values){
											$checked = isset($selected_parameters[$parameter_id]) && in_array(html_entity_decode($values["parameter_value_price_sign"].'*'.$values["parameter_value_price"].'*'.$values["parameter_value"]),$selected_parameters[$parameter_id])  ? "checked" : "";

											if($checked == "checked"){
												$already_existed_values += $values["parameter_value_price_sign"].$values["parameter_value_price"];
											}
                                            $price_addon = $values["parameter_value_price"] == "0" ? "" : ' ('.$values["parameter_value_price_sign"].$options->currency_sign.number_format($values["parameter_value_price"],2).')';
											echo '<div>';
											echo '<input type="checkbox" name="parameter_checkbox'.$parameter_id.$order_product_row->id.$i.'" id="parameter_checkbox'.$parameter_id.$order_product_row->id.$i.$j.'" value="'.$values["parameter_value_price_sign"].'*'.$values["parameter_value_price"].'*'.$values["parameter_value"].'"  onchange="updateImageOrderRow(this)" '.$checked.' data-type="parameters">';
											echo '<label for="parameter_checkbox'.$parameter_id.$order_product_row->id.$i.$j.'"> '.$values["parameter_value"].$price_addon.'</label>';
											echo '</div>';
											$j++;
										}
										echo '<input type="hidden" class="already_selected_values" value="'.$already_existed_values.'">';
										echo '</div>';
										break;
									default:
										break;
								}
								echo '</div>';
							}
						?>

					</div>
				<?php
				}
				?>
			</div>
			<div class="bwge_product_price_container">
				<p class="bwge_product_price "><?php echo $order_product_row->final_price_text;?></p>
				<input type="hidden" name="bwge_product_price" value="<?php echo $order_product_row->final_price;?>">
				<?php
				if($order_product_row->pricelist_download_item_id == 0 || ( $order_product_row->pricelist_download_item_id  && $options->show_digital_items_count == 1 ) ){
				?>
					<p class="bwge_product_count"><input type="number" min="1" value="<?php echo $order_product_row->products_count;?>" name="product_counts" onchange="updateImageOrderRow(this);" onkeypress="return updateImageOrderRowKeyPress(this, event)" data-type="count"></p>
				<?php
				}
				else{
				?>
					<p class="bwge_product_count"><?php  echo __('Count', 'bwge').": ". $order_product_row->products_count;?></p>
				<?php
				}

				if($order_product_row->tax_rate){
				?>
				<p class="bwge_product_tax"><?php  echo __('Tax', 'bwge');?>: <?php echo $order_product_row->tax_rate ."%";?>  </p>
				<?php
				}
				?>
				<div class="bwge_divider"></div>
				<p class="bwge_product_price_subtotal"><?php echo __('Subtotal', 'bwge') .": ".$order_product_row->subtotal_text;?></p>

				<div class="bwge_remove_item" data-id="<?php echo $order_product_row->order_image_id; ?>">
					<input type="button" name="bwge_remove_item"  value="<?php echo __('Remove', 'bwge');?>" onclick="bwgeRemoveItem(this);">
				</div>
			</div>

		</div>
		<?php
			$total += $order_product_row->subtotal;
            $i++;
		}
		?>
		<div class="bwge_divider"></div>
		<div class="bwge_total_container">
            <?php
                if($this->model->order_shipping != 0 && $options->enable_shipping == 1){
            ?>
                <p class="bwge_product_shipping"><?php  echo __('Shipping', 'bwg');?>: <?php echo  $options->currency_sign.number_format($this->model->order_shipping,2);?>  </p>

            <?php
                $total += $this->model->order_shipping;
                }
            ?>
			<h4 class="bwge_product_price_total"><?php echo __('Total', 'bwge') .": ".$options->currency_sign.number_format($total,2);?></h4>
		</div>
		<div class="bwge_divider"></div>

<?php
	}

	public function display_checkout_form(){
		$order_product_rows = $this->model->get_order_product_rows();
		$options = $this->model->get_options();

		$payment_method = $_POST["payment_method"];
		$licensing = array();
		$is_downloadable = true;

		foreach($order_product_rows as $order_product_row){
			if($order_product_row->display_license == 1 && $order_product_row->pricelist_download_item_id != 0){
				$licensing[] = $order_product_row->license_id;
			}
			if($order_product_row->pricelist_download_item_id == 0){
				$is_downloadable = false;
			}
		}
		$licensing = array_unique($licensing);
		$user_id = get_current_user_id();
		$user_info = get_userdata($user_id);
		$email = $user_info ?  $user_info->user_email : "";
        $user_first_name =  get_user_meta ( $user_id ,"first_name", true);
        $user_last_name =  get_user_meta ( $user_id ,"last_name", true);
		$name = trim($user_first_name." ".$user_last_name);

	?>
		<div class="bwge_icon_btns">
			 <i title="<?php echo __('Prevous', 'bwge'); ?>" class="fa fa-arrow-circle-left bwge_prevoius" style="display:none;"></i>
			 <i title="<?php echo __('Close', 'bwge'); ?>" class="fa fa-close bwge_close"></i>
		</div>
		<div class="bwge_divider"></div>
		<p><?php echo __('Photo Gallery Ecommerce', 'bwge');?></p>
		<div  class="credit_cart_form">
			<div class="bwge_shipping_billing_info bwge_checkout_step" data-step="bwge_shipping_billing_info">
				<div class="form_row">
					<input type="text" name="billing_data_email"  data-bwge-required value="<?php echo $email;?>">
                    <div class="bwge_label"><?php echo __('Email', 'bwge');?></div>
				</div>
				<?php
					if($options->show_shipping_billing == 1){
						if($options->enable_shipping == 1 && $is_downloadable == false){
				?>
						<div class="form_row billing_shipping_btns">
							<input type="button" name="billing_info" value="<?php echo __('Billing info', 'bwge');?>" class="bsactive">
							<input type="button" name="shipping_info" value="<?php echo __('Shipping info', 'bwge');?>">
						</div>

						<div class="form_row">
							<input type="checkbox" name="same_billing_shipping" id="same_billing_shipping" value="1" >
							<label for="same_billing_shipping"><?php echo __('Same shipping and billing info', 'bwge');?></label>
						</div>
					<?php
					}
					?>
					<div class="bwge_billing_info">
						<div class="form_row">
							<input type="text" name="billing_data_name"  value="<?php echo $name;?>">
							<div class="bwge_label"><?php echo __('Name', 'bwge');?></div>
						</div>
						<div class="form_row">
							<input type="text" name="billing_data_address" >
							<div class="bwge_label"><?php echo __('Address', 'bwge');?></div>
						</div>
						<div class="form_row">
							<input type="text" name="billing_data_zip_code" >
							<div class="bwge_label"><?php echo __('Zip code', 'bwge');?></div>
						</div>
						<div class="form_row">
							<input type="text" name="billing_data_city" >
							<div class="bwge_label"><?php echo __('City', 'bwge');?></div>
						</div>
						<div class="form_row">
							<input type="text" name="billing_data_country" >
							<div class="bwge_label"><?php echo __('Country', 'bwge');?></div>
						</div>
					</div>
					<?php if($options->enable_shipping == 1 && $is_downloadable == false){
					?>
						<div class="bwge_shipping_info " style="display:none">
							<div class="form_row">
								<input type="text" name="shipping_data_name"  value="<?php echo $name;?>">
								<div class="bwge_label"><?php echo __('Name', 'bwge');?></div>
							</div>
							<div class="form_row">
								<input type="text" name="shipping_data_address" >
								<div class="bwge_label"><?php echo __('Address', 'bwge');?></div>
							</div>
							<div class="form_row">
								<input type="text" name="shipping_data_zip_code" >
								<div class="bwge_label"><?php echo __('Zip code', 'bwge');?></div>
							</div>
							<div class="form_row">
								<input type="text" name="shipping_data_city" >
								<div class="bwge_label"><?php echo __('City', 'bwge');?></div>
							</div>
							<div class="form_row">
								<input type="text" name="shipping_data_country" >
								<div class="bwge_label"><?php echo __('Country', 'bwge');?></div>
							</div>
						</div>
					<?php
						}
					}
					if($payment_method == "stripe"){

						echo '<input type="button"  value="'. __('Credit card details', 'bwge').'" class="bwge_checkout_btns">';
					}
					else{
						if(empty($licensing) == false){
						?>
							<div class="form_row">
								<input type="checkbox" value="1" name="accept_terms" id="accept_terms" data-bwge-required>
								<label for="accept_terms"><?php echo __('I accept', 'bwge');?></label>
								<?php
									foreach($licensing as $license){
								?>
									<div><a href="<?php echo get_site_url()."?page_id=".$license; ?>" target="_blank"><?php echo __('License page', 'bwge');?></a></div>
								<?php
									}
								?>
							</div>
						<?php
						}
						echo '<input type="button" name="checkout" value="'. __('Checkout', 'bwge').'" onclick="submitCheckoutForm(this, event);" >';

					}
				?>

			</div>

			<div class="bwge_checkout_alert_incorrect_data"></div>

		</div>
		<script>
		jQuery(document).ready(function($) {

			if(jQuery(window).height() < jQuery(".bwge_checkout_form_wrap").height()){
				jQuery(".bwge_checkout_form_wrap").addClass("bwge_checkout_form_wrap_scroll");
			}
			else{
				jQuery(".bwge_checkout_form_wrap").removeClass("bwge_checkout_form_wrap_scroll");
			}

			jQuery("#same_billing_shipping").change(function(){
                sameShippingBilling();
			});

			jQuery("[name=shipping_info]").click(function(){
				jQuery("[name=billing_info]").removeClass("bsactive");
				jQuery(this).addClass("bsactive");
				jQuery(".bwge_billing_info").hide();
				jQuery(".bwge_shipping_info").show();
                sameShippingBilling();
			});
			jQuery("[name=billing_info]").click(function(){
				jQuery("[name=shipping_info]").removeClass("bsactive");
				jQuery(this).addClass("bsactive");
				jQuery(".bwge_shipping_info").hide();
				jQuery(".bwge_billing_info").show();
                //sameShippingBilling();
			});

			jQuery(".bwge_checkout_btns").click(function(){

				var currentStep = jQuery(this).closest(".bwge_checkout_step");
				var flag = checkFormData(currentStep.attr("data-step"));
				if(flag == true){
					currentStep.hide();
					jQuery(".bwge_prevoius").show();
					currentStep.next(".bwge_checkout_step").show();
				}

				return false;
			});

			jQuery(".bwge_prevoius").click(function(){
				var currentStep = jQuery(".bwge_checkout_step:visible");
				currentStep.hide();
				if(currentStep.prev(".bwge_checkout_step").index() == 0){
					jQuery(".bwge_prevoius").hide();
				}
				currentStep.prev(".bwge_checkout_step").show();

				return false;
			});

			jQuery(".bwge_close").click(function(){
				jQuery(".bwge_checkout_form_wrap").hide();
				jQuery(".bwge_checkout_form_wrap").html("");
				jQuery(".bwge_checkout_form_wrap_opacity").hide();
			});
		});

        function sameShippingBilling(){
            if(jQuery("#same_billing_shipping:checked").length >0){
                jQuery("[name=shipping_data_name]").val(jQuery("[name=billing_data_name]").val());
                jQuery("[name=shipping_data_address]").val(jQuery("[name=billing_data_address]").val());
                jQuery("[name=shipping_data_zip_code]").val(jQuery("[name=billing_data_zip_code]").val());
                jQuery("[name=shipping_data_city]").val(jQuery("[name=billing_data_city]").val());
                jQuery("[name=shipping_data_country]").val(jQuery("[name=billing_data_country]").val());
            }
        }
		</script>

	<?php
		exit;
	}


	public function display(){
		$order_product_rows = $this->model->get_order_product_rows();
		$options = $this->model->get_options();
		$paypal_standart_api_options = $this->model->get_payments_api_options("paypalstandart");
		$without_online_api_options = $this->model->get_payments_api_options("without_online_payment");

		?>

		<script src="<?php echo WD_BWGE_URL . '/js/ecommerce/checkoutform.js';?>"></script>
		<div class="bwge_main_container">
			<form method="post" id="bwge_order_form">
				<div class="bwge_shopping_cart_wrap">
					<div class="bwge_remove_checkout_btns">
						<input type="button" name="bwge_remove_all" value="<?php echo __('Empty cart', 'bwge');?>" onclick="bwgeRemoveAll();">
					</div>
					<div class="bwge_checkout_form_container">
						<?php $this->display_main_container() ;?>
					</div>
				</div>
				<div class="bwge_payment_btns">
					<?php

					if((get_current_user_id() || (get_current_user_id() == 0 && $options->enable_guest_checkout == 1)) && count($order_product_rows)>0){
                        if($paypal_standart_api_options->published == 1 && ($paypal_standart_api_options->paypal_email != "" )){
						?>
							<input type="button" name="bwge_paypalstandart" class="bwge_payment_btn" value="<?php echo $paypal_standart_api_options->payment_name;?>"  data-payment-method="paypalstandart">
						<?php
						}

						if($without_online_api_options->published == 1){
						?>
							<input type="button" name="bwge_without_online_payment" class="bwge_payment_btn" value="<?php echo  $without_online_api_options->payment_name;?>"  data-payment-method="without_online_payment">
						<?php
						}
					}
					?>
				</div>
				<input type="hidden" name="task" value="checkout" >
				<input type="hidden" name="current_id" >
				<div class = "bwge_checkout_form_wrap_opacity"></div>
				<div class = "bwge_checkout_form_wrap">

				</div>
				<input type="hidden" name = "payment_method" >
			</form>

		</div>
		<script>
		var ajaxURL = '<?php echo admin_url('admin-ajax.php'); ?>';
		function bwgeRemoveAll(){
			jQuery("[name=task]").val("remove_all")	;
			jQuery("#bwge_order_form").submit();
		}

		function bwgeRemoveItem(obj){
			jQuery("[name=task]").val("remove_item");

			jQuery("[name=current_id]").val(jQuery(obj).parent().attr("data-id"));
			jQuery("#bwge_order_form").submit();
		}


		function updateImageOrderRowKeyPress(obj,event){
			event = event || window.event;
			
			if(event.keyCode == 13){
				updateImageOrderRow(obj);
				return false;
			}			
		}
		function updateImageOrderRow(obj){

			var type = jQuery(obj).attr("data-type");
			var orderRow = {};
			var imageOrderRow = jQuery(obj).closest(".bwge_product_row_main_container");
			var imageOrderId = imageOrderRow.attr("data-iamge-order-id");
			orderRow.id = imageOrderId;
			orderRow.type = type;

			if(type == "count")	{
				orderRow.productCounts = imageOrderRow.find("[name=product_counts]").val();
			}
			else if(type == "parameters"){
				var parameters = {};

				imageOrderRow.find(".image_selected_parameter").each(function () {
					var parameterId = jQuery(this).attr("data-parameter-id");
					var parameterTypeId = jQuery(this).attr("data-parameter-type");
					var parameterValue = "";

					switch(parameterTypeId) {
						case '2' :
							parameterValue = jQuery(this).find("input").val();
							break;
						case '3' :
							parameterValue = jQuery(this).find("textarea").html();
							break;
						case '4' :
							parameterValue = jQuery(this).find('select :selected').val();
							break;
						case '5' :
							parameterValue = jQuery(this).find('[type=radio]:checked').val();

							break;
						case '6' :
							var checkbox_parameter_values = [];
							jQuery(this).find("[type=checkbox]:checked").each(function () {
								checkbox_parameter_values.push(jQuery(this).val());
							});
							parameterValue = checkbox_parameter_values;
							break;
					}

					parameters[parameterId] = parameterValue;
				});
				orderRow.parameters = JSON.stringify(parameters);
			}

			orderRow.action = 'bwge_update_cart';
			orderRow.controller = 'checkout';
			orderRow.task = 'update_cart';
			var ajaxurl = '<?php echo admin_url('admin-ajax.php'); ?>';
			jQuery.post(ajaxurl, orderRow, function(response) {
				jQuery('.bwge_checkout_form_container').html(response);

			}).success(function(jqXHR, textStatus, errorThrown) {

			});


		}

		</script>

		<?php

	}

    public function thank_you(){
	?>
		<div class="bwge_main_container">
			<div class="success_wrap">
				<h2 class="success_msg_header"><?php echo __('Success', 'bwge'); ?></h2>
				<div class="bwge_divider"></div>
				<p class="success_msg_body"><?php echo __('Your order has been received. Thank you for your purchase! You will receive an order confirmation by email.', 'bwge'); ?></p>
			</div>
		</div>
	<?php

    }
  public function show_add_to_cart($image_id){
    if(!$image_id){
        echo __('Not found', 'bwge');
        return false;
    }
    $theme_id = (isset($_GET['theme_id']) ? esc_html($_GET['theme_id']) : 1);
    $theme_row = $this->model->get_theme_row_data($theme_id);
    $pricelist_id = $this->model->get_image_pricelist($image_id) ?  $this->model->get_image_pricelist($image_id) : 0;
    $pricelist_data = $this->model->get_image_pricelists($pricelist_id);
    $pricelist = $pricelist_data["pricelist"];
    $download_items = $pricelist_data["download_items"];
    $parameters = $pricelist_data["parameters"];
    $options = $pricelist_data["options"];
    $products_in_cart = $pricelist_data["products_in_cart"];
    $pricelist_sections = $pricelist->sections ? explode("," , $pricelist->sections) : array();

?>
<style>
  .bwge_ecommerce_wrap_ {
        bottom: 0;
        left: 0;
        overflow: hidden;
        height:400px;
      }
    .bwge_ecommerce_container_ {
        -moz-box-sizing: border-box;
        background-color: #<?php echo $theme_row->lightbox_comment_bg_color; ?>;
        color: #<?php echo $theme_row->lightbox_comment_font_color; ?>;
        font-size: <?php echo $theme_row->lightbox_comment_font_size; ?>px;
        font-family: <?php echo $theme_row->lightbox_comment_font_style; ?>;
        height: 100%;
        overflow: hidden;
        width: <?php echo $theme_row->lightbox_comment_width; ?>px;
      }
      #bwge_ecommerce{
          padding:10px;
        }
    .bwge_ecommerce_body {
          background: none !important;
          border: none !important;
          color: #fff !important;
     }

        .bwge_tabs{
          list-style-type:none;
          margin: 0px;
          padding:0;
          background: none !important;
        }
        .bwge_tabs li{
          float:left;
          border-top: 1px solid #<?php echo $theme_row->lightbox_bg_color; ?>!important;
          border-left: 1px solid #<?php echo $theme_row->lightbox_bg_color; ?>!important;
          border-right: 1px solid #<?php echo $theme_row->lightbox_bg_color; ?>!important;
          margin-right: 1px !important;
          border-radius: <?php echo $theme_row->lightbox_comment_button_border_radius; ?> <?php echo $theme_row->lightbox_comment_button_border_radius; ?> 0 0;
          position:relative;
        }
       .bwge_tabs li:hover  , .bwge_tabs li.bwge_active {
          border-top: 1px solid #<?php echo $theme_row->lightbox_comment_font_color; ?>!important;
          border-left: 1px solid #<?php echo $theme_row->lightbox_comment_font_color; ?>!important;
          border-right: 1px solid #<?php echo $theme_row->lightbox_comment_font_color; ?>!important;
          border-bottom:none!important;
          bottom:-1px;
        }

       .bwge_tabs li a, .bwge_tabs li a:hover, .bwge_tabs li.bwge_active a{
         text-decoration:none;
         display:block;
         width:100%;
         outline:0 !important;
         padding:8px 5px !important;
         font-weight: bold;
         font-size: 13px;
       }
       .bwge_tabs li a{
          color:#<?php echo $theme_row->lightbox_comment_bg_color; ?>!important;
          background:#808080!important;
          border-radius: <?php echo $theme_row->lightbox_comment_button_border_radius; ?>;
        }
       .bwge_tabs li:hover a , .bwge_tabs li.bwge_active a{
          color:#<?php echo $theme_row->lightbox_comment_font_color; ?>!important;
          background:#<?php echo $theme_row->lightbox_bg_color; ?>!important;
          border-radius:0!important;
        }
       .bwge_tabs_container{
          border:1px solid #<?php echo $theme_row->lightbox_comment_font_color; ?>;
          border-radius: 0 0 <?php echo $theme_row->lightbox_comment_button_border_radius; ?> <?php echo $theme_row->lightbox_comment_button_border_radius; ?>;
       }

      .bwge_pricelist {
        padding:0 !important;
        color:#<?php echo $theme_row->lightbox_comment_font_color; ?>!important;
      }
      .bwge_add_to_cart{
         margin: 5px 0px 15px;
      }

      .bwge_add_to_cart a{
        border: 1px solid #<?php echo $theme_row->lightbox_comment_font_color; ?>!important;
        padding: 5px 10px;
        color:#<?php echo $theme_row->lightbox_comment_font_color; ?>!important;
        border-radius: <?php echo $theme_row->lightbox_comment_button_border_radius; ?>;
        text-decoration:none !important;
        display:block;
      }
      .bwge_add_to_cart_title{
        font-size:17px;
      }
      .bwge_add_to_cart div:first-child{
        float:left;
      }
      .bwge_add_to_cart div:last-child{
        float:right;
        margin-top: 4px;
      }
      .bwge_tabs:after,  .bwge_add_to_cart:after{
        clear:both;
        content:"";
        display:table;
       }
      #downloads table tr td,   #downloads table tr th{
        padding: 6px 10px !important;
        text-transform:none !important;
      }
      .bwge_ecommerce_panel_{
        bottom: 0;
        font-size: <?php echo $theme_row->lightbox_comment_font_size; ?>px;
        font-family: <?php echo $theme_row->lightbox_comment_font_style; ?>;
        height: 100%;
        left: 0;
        overflow-x: hidden;
        overflow-y: auto;
        position: absolute;
        top: 0;
        width: 100%;
        z-index: 10101;
      }


      .bwge_ecommerce_panel_ p{
        padding: 5px !important;
        text-align: left;
        word-wrap: break-word;
        word-break: break-word;
        margin:0 !important;
      }
      .bwge_ecommerce_panel_ input[type="button"] {
        background: none repeat scroll 0 0 #<?php echo $theme_row->lightbox_comment_button_bg_color; ?>;
        border: <?php echo $theme_row->lightbox_comment_button_border_width; ?>px <?php echo $theme_row->lightbox_comment_button_border_style; ?> #<?php echo $theme_row->lightbox_comment_button_border_color; ?>;
        border-radius: <?php echo $theme_row->lightbox_comment_button_border_radius; ?>;
        color: #<?php echo $theme_row->lightbox_comment_font_color; ?>;
        cursor: pointer;
        padding: <?php echo $theme_row->lightbox_comment_button_padding; ?>;
      }
      .bwge_ecommerce_panel_ input[type="text"],
      .bwge_ecommerce_panel_ input[type="number"],
      .bwge_ecommerce_panel_ textarea , .bwge_ecommerce_panel_ select {
        background: none repeat scroll 0 0 #<?php echo $theme_row->lightbox_comment_input_bg_color; ?>;
        border: <?php echo $theme_row->lightbox_comment_input_border_width; ?>px <?php echo $theme_row->lightbox_comment_input_border_style; ?> #<?php echo $theme_row->lightbox_comment_input_border_color; ?>;
        border-radius: <?php echo $theme_row->lightbox_comment_input_border_radius; ?>;
        color: #<?php echo $theme_row->lightbox_comment_font_color; ?>;
        padding: <?php echo $theme_row->lightbox_comment_input_padding; ?>;
        width: 100%;
      }

    .bwge_close_btn_ {
        opacity: <?php echo number_format($theme_row->lightbox_close_btn_transparent / 100, 2, ".", ""); ?>;
        filter: Alpha(opacity=<?php echo $theme_row->lightbox_close_btn_transparent; ?>);
      }
      .bwge_spider_popup_close {
        background-color: #<?php echo $theme_row->lightbox_close_btn_bg_color; ?>;
        border-radius: <?php echo $theme_row->lightbox_close_btn_border_radius; ?>;
        border: <?php echo $theme_row->lightbox_close_btn_border_width; ?>px <?php echo $theme_row->lightbox_close_btn_border_style; ?> #<?php echo $theme_row->lightbox_close_btn_border_color; ?>;
        box-shadow: <?php echo $theme_row->lightbox_close_btn_box_shadow ? $theme_row->lightbox_close_btn_box_shadow : "none"; ?>!important;
        color: #<?php echo $theme_row->lightbox_close_btn_color; ?>;
        height: <?php echo $theme_row->lightbox_close_btn_height; ?>px;
        font-size: <?php echo $theme_row->lightbox_close_btn_size; ?>px;
        right: <?php echo $theme_row->lightbox_close_btn_right; ?>px;
        top: <?php echo $theme_row->lightbox_close_btn_top; ?>px;
        width: <?php echo $theme_row->lightbox_close_btn_width; ?>px;
      }
      .bwge_spider_popup_close_fullscreen {
        color: #<?php echo $theme_row->lightbox_close_btn_full_color; ?>;
        font-size: <?php echo $theme_row->lightbox_close_btn_size; ?>px;
        right: 15px;
        position:satatic;
      }

    .bwge_spider_popup_close span {
        display: table-cell;
        text-align: center;
        vertical-align: middle;
      }

      .bwge_spider_popup_close:hover,
      .bwge_spider_popup_close_fullscreen:hover {
        color: #<?php echo $theme_row->lightbox_close_rl_btn_hover_color; ?>;
        cursor: pointer;
      }
     </style>
     <div>
        <a class="bwge_spider_popup_close" onclick="bwgeDestroyAddToCartPopup('<?php echo $_POST["current_view"]; ?>');return false;" ontouchend="bwgeDestroyAddToCartPopup('<?php echo $_POST["current_view"]; ?>');return false;"><span><i class="bwge_close_btn_ fa fa-times"></i></span></a>
    </div>
    <div class="bwge_ecommerce_wrap_ bwge_popup_sidebar_wrap" id="bwge_ecommerce_wrap_">
        <div class="bwge_ecommerce_container_ bwge_popup_sidebar_container bwge_close">
            <div class="bwge_ecommerce_panel_ bwge_popup_sidebar_panel bwge_popup_sidebar" style="text-align:left;">
                <div id="bwge_ecommerce">
                    <form id="bwge_ecommerce_form" method="post" action="">
                        <div class="bwge_add_to_cart">
                            <div>
                                <img src="<?php echo WD_BWGE_URL ?>/images/add-to-cart-icon.png" style="vertical-align:bottom;">&nbsp;
                                <span class="bwge_add_to_cart_title"><?php echo (__('Add to cart', 'bwge')); ?></span>
                            </div>
                            <div>
                                <a href="<?php echo get_permalink($options->checkout_page);?>"><?php echo "<span class='products_in_cart'>".$products_in_cart ."</span> ". __('items', 'bwge'); ?></a>
                            </div>

                        </div>

                        <div class="bwge_ecommerce_body">
                            <ul class="bwge_tabs" <?php if(count($pricelist_sections)<=1) echo "style='display:none;'"; ?>>
                                <li id="manual_li" <?php if(!in_array("manual",$pricelist_sections)) echo "style='display:none;'"; ?> class="bwge_active">
                                    <a href= "#manual">
                                        <span class="manualh4" >
                                            <?php echo __('Prints and products', 'bwge'); ?>
                                        </span>
                                    </a>
                                </li>
                                <li id="downloads_li" <?php if(!in_array("downloads",$pricelist_sections)) echo "style='display:none;'"; ?>>
                                    <a href= "#downloads">
                                    <span class="downloadsh4" >
                                        <?php echo __('Downloads', 'bwge'); ?>
                                    </span>
                                    </a>
                                </li>
                            </ul>
                            <div class="bwge_tabs_container" >
                            <!-- manual -->
                            <div class="manual bwge_pricelist" id="manual" <?php if( count($pricelist_sections) == 2  || (count($pricelist_sections) == 1 && end($pricelist_sections) == "manual")) echo 'style="display: block;"'; else echo 'style="display: none;"'; ?>  >
                                <div>

                                    <div class="product_manual_price_div">
                                        <p><?php echo $pricelist->manual_title ? __('Name', 'bwge').': '.$pricelist->manual_title : "";?></p>
                                        <p>
                                            <span><?php echo __('Price', 'bwge').': '.$options->currency_sign;?></span>
                                            <span class="_product_manual_price"><?php echo number_format((float)$pricelist->price,2)?></span>
                                        </p>
                                    </div>
                                  <?php if($pricelist->manual_description){
                                  ?>
                                    <div class="product_manual_desc_div">
                                        <p>
                                            <span><?php echo __('Description', 'bwge');?>:</span>
                                            <span class="product_manual_desc"><?php echo $pricelist->manual_description;?></span>
                                        </p>
                                    </div>
                                    <?php
                                      }
                                      ?>
                                    <div class="image_count_div">
                                        <p>
                                            <?php echo __('Count', 'bwge').': ';?>
                                            <input type="number" min="1" class="image_count" value="1" onchange="changeMenualTotal(this);">
                                        </p>
                                    </div>
                                    <?php if(empty($parameters) == false){?>
                                    <div class="image_parameters">
                                        <p><?php //echo __('Parameters', 'bwge'); ?></p>
                                        <?php
                                            $i = 0;
                                            foreach($parameters as $parameter_id => $parameter){
                                                echo '<div class="parameter_row">';
                                                switch($parameter["type"]){
                                                    case "1" :
                                                        echo '<div class="image_selected_parameter" data-parameter-id="'.$parameter_id.'" data-parameter-type = "'.$parameter["type"].'">';
                                                        echo $parameter["title"].": <span class='parameter_single'>". $parameter["values"][0]["parameter_value"]."</span>";
                                                        echo '</div>';
                                                        break;
                                                    case "2" :
                                                        echo '<div class="image_selected_parameter" data-parameter-id="'.$parameter_id.'" data-parameter-type = "'.$parameter["type"].'">';
                                                        echo '<label for="parameter_input">'.$parameter["title"].'</label>';
                                                        echo '<input type="text" name="parameter_input'.$parameter_id.'" id="parameter_input"  value="'. $parameter["values"][0]["parameter_value"] .'">';
                                                        echo '</div>';
                                                        break;
                                                    case "3" :
                                                        echo '<div class="image_selected_parameter" data-parameter-id="'.$parameter_id.'" data-parameter-type = "'.$parameter["type"].'">';
                                                        echo '<label for="parameter_textarea">'.$parameter["title"].'</label>';
                                                        echo '<textarea  name="parameter_textarea'.$parameter_id.'" id="parameter_textarea"  >'. $parameter["values"][0]["parameter_value"] .'</textarea>';
                                                        echo '</div>';
                                                        break;
                                                    case "4" :
                                                        echo '<div class="image_selected_parameter" data-parameter-id="'.$parameter_id.'" data-parameter-type = "'.$parameter["type"].'">';
                                                        echo '<label for="parameter_select">'.$parameter["title"].'</label>';
                                                        echo '<select name="parameter_select'.$parameter_id.'" id="parameter_select"  onchange="onSelectableParametersChange(this)">';
                                                        echo '<option value="+*0*">-Select-</option>';
                                                        foreach($parameter["values"] as $values){
                                                            $price_addon = $values["parameter_value_price"] == "0" ? "" : ' ('.$values["parameter_value_price_sign"].$options->currency_sign.number_format((float)$values["parameter_value_price"],2).')';
                                                            echo '<option value="'.$values["parameter_value_price_sign"].'*'.$values["parameter_value_price"].'*'.$values["parameter_value"].'">'.$values["parameter_value"].$price_addon.'</option>';
                                                        }
                                                        echo '</select>';
                                                        echo '<input type="hidden" class="already_selected_values">';
                                                        echo '</div>';
                                                        break;
                                                    case "5" :
                                                        echo '<div class="image_selected_parameter" data-parameter-id="'.$parameter_id.'" data-parameter-type = "'.$parameter["type"].'">';
                                                        echo '<label>'.$parameter["title"].'</label>';
                                                        foreach($parameter["values"] as $values){
                                                            $price_addon = $values["parameter_value_price"] == "0"	? "" : 	' ('.$values["parameter_value_price_sign"].$options->currency_sign.number_format((float)$values["parameter_value_price"],2).')';
                                                            echo '<div>';
                                                            echo '<input type="radio" name="parameter_radio'.$parameter_id.'"  id="parameter_radio'.$i.'" value="'.$values["parameter_value_price_sign"].'*'.$values["parameter_value_price"].'*'.$values["parameter_value"].'"  onchange="onSelectableParametersChange(this)">';
                                                            echo '<label for="parameter_radio'.$i.'">'.$values["parameter_value"].$price_addon.'</label>';
                                                            echo '</div>';
                                                            $i++;
                                                        }
                                                        echo '<input type="hidden" class="already_selected_values">';
                                                        echo '</div>';
                                                        break;
                                                    case "6" :
                                                        echo '<div class="image_selected_parameter" data-parameter-id="'.$parameter_id.'" data-parameter-type = "'.$parameter["type"].'">';
                                                        echo '<label>'.$parameter["title"].'</label>';
                                                        foreach($parameter["values"] as $values){
                                                            $price_addon = $values["parameter_value_price"] == "0" ? "" : ' ('.$values["parameter_value_price_sign"].$options->currency_sign.number_format((float)$values["parameter_value_price"],2).')';
                                                            echo '<div>';
                                                            echo '<input type="checkbox" name="parameter_checkbox'.$parameter_id.'" id="parameter_checkbox'.$i.'" value="'.$values["parameter_value_price_sign"].'*'.$values["parameter_value_price"].'*'.$values["parameter_value"].'"  onchange="onSelectableParametersChange(this)">';
                                                            echo '<label for="parameter_checkbox'.$i.'">'.$values["parameter_value"].$price_addon.'</label>';
                                                            echo '</div>';
                                                            $i++;
                                                        }
                                                        echo '<input type="hidden" class="already_selected_values">';
                                                        echo '</div>';
                                                        break;
                                                    default:
                                                        break;
                                                }
                                                echo '</div>';
                                            }
                                        ?>

                                    </div>
                                    <?php } ?>
                                    <p>
                                        <span><b><?php echo __('Total', 'bwge').': '.$options->currency_sign;?></b></span>
                                        <b><span class="product_manual_price" data-price="<?php echo $pricelist->price; ?>"><?php echo number_format((float)$pricelist->price,2)?></span></b>
                                    </p>
                                </div>

                            </div>
                            <!-- downloads -->

                            <div class="downloads bwge_pricelist" id="downloads" <?php if( (count($pricelist_sections) == 1 && end($pricelist_sections) == "downloads")) echo 'style="display: block;"'; else echo 'style="display: none;"'; ?> >

                                <table>
                                    <thead>
                                        <tr>
                                            <th><?php echo __('Choose', 'bwge'); ?></th>
                                            <th><?php echo __('Name', 'bwge'); ?></th>
                                            <th><?php echo __('Dimensions', 'bwge'); ?></th>
                                            <th><?php echo __('Price', 'bwge'); ?></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                            if(empty($download_items) === false){
                                                foreach($download_items as $download_item){
                                                ?>
                                                    <tr data-price="<?php echo $download_item->item_price; ?>" data-id="<?php echo $download_item->id; ?>">
                                                        <?php if($options->show_digital_items_count == 0){
                                                        ?>
                                                            <td><input type="checkbox"  name="selected_download_item" value="<?php echo $download_item->id; ?>" onchange="changeDownloadsTotal(this);"></td>
                                                        <?php
                                                        }
                                                        else{
                                                        ?>
                                                            <td><input type="number" min="0" class="digital_image_count" value="0" onchange="changeDownloadsTotal(this);"></td>
                                                        <?php
                                                        }
                                                        ?>
                                                        <td><?php echo $download_item->item_name; ?></td>
                                                        <td><?php echo $download_item->item_longest_dimension.'px'; ?></td>
                                                        <td class="item_price"><?php echo $options->currency_sign. number_format((float)$download_item->item_price, 2); ?></td>
                                                    </tr>
                                                <?php
                                                }
                                            }
                                        ?>
                                    </tbody>
                                </table>
                                <p>
                                    <span><b><?php echo __('Total', 'bwge').': '.$options->currency_sign;?></b></span>
                                    <b><span class="product_downloads_price">0</span></b>
                                </p>
                            </div>
                            </div>

                        </div>

                        <div style="margin-top:10px;">
                            <input type="button" class="bwge_submit" value="<?php echo __('Add to cart', 'bwge'); ?>" onclick="onBtnClickAddToCart();">
                            <input type="button" class="bwge_submit" value="<?php echo __('View cart', 'bwge'); ?>" onclick="onBtnViewCart()">
                            &nbsp;<span class="add_to_cart_msg"></span>
                        </div>

                        <input id="ajax_task" name="ajax_task" type="hidden" value="" />
                        <input id="type" name="type" type="hidden" value="<?php echo isset($pricelist_sections[0]) ? $pricelist_sections[0] : ""  ?>" />
                        <input id="image_id" name="image_id" type="hidden" value="<?php echo $image_id; ?>" />
                        <div class="bwge_options">
                            <input type="hidden" name="option_checkout_page" value="<?php  echo get_permalink($options->checkout_page);?>">
                            <input type="hidden" name="option_show_digital_items_count" value="<?php echo $options->show_digital_items_count;?>">
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        jQuery(document).ready(function () {
            jQuery(".bwge_tabs li a").click(function(){
                jQuery(".bwge_tabs_container > div").hide();
                jQuery(".bwge_tabs li").removeClass("bwge_active");
                jQuery(jQuery(this).attr("href")).show();
                jQuery(this).closest("li").addClass("bwge_active");
                jQuery("[name=type]").val(jQuery(this).attr("href").substr(1));
                return false;
            });
            if (jQuery(window).width() < jQuery(".bwge_pricelist_container<?php echo $_POST["current_view"]; ?>").width()) {
               jQuery(".bwge_pricelist_container<?php echo $_POST["current_view"]; ?>").width(jQuery(window).width());
               jQuery(".bwge_spider_popup_close").attr("class", "bwge_spider_popup_close_fullscreen");
            }
            jQuery(window).resize(function() {
              if (jQuery(window).width() < jQuery(".bwge_pricelist_container<?php echo $_POST["current_view"]; ?>").width()) {
                   jQuery(".bwge_pricelist_container<?php echo $_POST["current_view"]; ?>").width(jQuery(window).width());
                   jQuery(".bwge_spider_popup_close").attr("class", "bwge_spider_popup_close_fullscreen");
                }
            });
            jQuery("#bwge_spider_popup_overlay_<?php echo $_POST["current_view"]; ?>").click(function(){
                bwgeDestroyAddToCartPopup("<?php echo $_POST["current_view"]; ?>");
            });
            if (typeof jQuery().mCustomScrollbar !== 'undefined' && jQuery.isFunction(jQuery().mCustomScrollbar)) {
              jQuery(".bwge_ecommerce_panel_").mCustomScrollbar({
                    scrollInertia: 150,
                    advanced:{
                      updateOnContentResize: true
                    }
                });

            }

        });

        function changeDownloadsTotal(obj){
            var totalPrice = 0;
            var showdigitalItemsCount = jQuery("[name=option_show_digital_items_count]").val();
            if( showdigitalItemsCount == 0 ){
                jQuery("[name=selected_download_item]:checked").each(function(){
                    totalPrice += Number(jQuery(this).closest("tr").attr("data-price"));

                });
            }
            else{
                jQuery(".digital_image_count").each(function(){
                    if(Number(jQuery(this).val()) != 0){
                        totalPrice += Number(jQuery(this).closest("tr").attr("data-price")) * Number(jQuery(this).val());
                    }
                });
            }
            totalPrice = totalPrice.toFixed(2).toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,");
            jQuery(".product_downloads_price").html(totalPrice);

        }

        function changeMenualTotal(obj){
            if(Number(jQuery(obj).val()) <= 0){
                jQuery(obj).val("1");
            }
            var count =  Number(jQuery(obj).val());
            var totalPrice = Number(jQuery(".product_manual_price").attr("data-price"));
            totalPrice = count*totalPrice;

            totalPrice = totalPrice.toFixed(2).toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,");
            jQuery(".product_manual_price").html(totalPrice);
        }

        function onSelectableParametersChange(obj){
            var parametersPrise = 0;

            var productPrice = "<?php echo $pricelist->price; ?>" ? "<?php echo $pricelist->price; ?>" : "0";
            productPrice = parseFloat(productPrice.replace(",",""));

            var type = jQuery(obj).closest('.image_selected_parameter').attr("data-parameter-type");
            var priceInfo = jQuery(obj).val();
            priceInfo = priceInfo.split("*");
            var priceValue = priceInfo[1];
            var sign = priceInfo[0];

            var alreadySelectedValues = Number(jQuery(obj).closest('.image_selected_parameter').find(".already_selected_values").val());

            if(type == "4" || type == "5")	{
                var newPriceVlaueSelectRadio =  parseFloat(eval(sign + '1*' + priceValue));

                jQuery(obj).closest('.image_selected_parameter').find(".already_selected_values").val(newPriceVlaueSelectRadio);
            }

            else if (type == "6"){
                if(jQuery(obj).is(":checked") == false){
                    var  newPriceVlaueCheckbox = parseFloat(eval(alreadySelectedValues + "- "  + sign + priceValue));
                }
                else{
                     var newPriceVlaueCheckbox = parseFloat(eval(alreadySelectedValues + sign + priceValue));
                }
                jQuery(obj).closest('.image_selected_parameter').find(".already_selected_values").val(newPriceVlaueCheckbox);
            }


            jQuery(".already_selected_values").each(function(){
                parametersPrise += Number(jQuery(this).val());
            });

            productPrice =   productPrice + parametersPrise;
            jQuery(".product_manual_price").attr("data-price",productPrice);
            var count = Number(jQuery(".image_count").val()) <= 0 ? 1 : Number(jQuery(".image_count").val());
            productPrice = count * productPrice;
            productPrice = productPrice.toFixed(2).toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,");;
            jQuery(".product_manual_price").html(productPrice);


        }

        function onBtnClickAddToCart(){

            var type = jQuery("[name=type]").val();
            if(type != ""){
                var data = {};
                if(type == "manual"){
                    var count = jQuery(".image_count").val();
                    var parameters = {};

                    jQuery(".manual").find(".image_selected_parameter").each(function () {
                        var parameterId = jQuery(this).attr("data-parameter-id");
                        var parameterTypeId = jQuery(this).attr("data-parameter-type");
                        var parameterValue = "";
                        switch (parameterTypeId) {

                            // input
                            case '2':
                                parameterValue = jQuery(this).find("input").val();
                                break;
                            case '3':
                                parameterValue = jQuery(this).find("textarea").val();
                                break;
                            // Select
                            case '4':
                                parameterValue = jQuery(this).find('select :selected').val();
                                break;
                            // Radio
                            case '5':
                                parameterValue = jQuery(this).find('[type=radio]:checked').val();
                                break;
                            // Checkbox
                            case '6':
                                var checkbox_parameter_values = [];;
                                jQuery(this).find("[type=checkbox]:checked").each(function () {
                                    checkbox_parameter_values.push(jQuery(this).val());
                                });
                                parameterValue = checkbox_parameter_values;
                                break;
                        }

                        parameters[parameterId] = parameterValue;
                    });
                    data.count = count;
                    data.parameters = parameters;
                    data.price = jQuery(".product_manual_price").html().replace(",","");
                }
                else{
                    var downloadItems = [];
                    var showdigitalItemsCount = jQuery("[name=option_show_digital_items_count]").val();
                    if( showdigitalItemsCount == 0 ){
                        if(jQuery("[name=selected_download_item]:checked").length == 0){
                            jQuery(".add_to_cart_msg").html("You must select at least one item.");
                            return;
                        }
                        jQuery("[name=selected_download_item]:checked").each(function () {
                            var downloadItem = {};
                            downloadItem.id = jQuery(this).val();
                            downloadItem.count = 1;
                            downloadItem.price = jQuery(this).closest("tr").attr("data-price");
                            downloadItems.push(downloadItem);
                        });
                    }
                    else{
                        jQuery(".digital_image_count").each(function () {
                            var downloadItem = {};
                            if(jQuery(this).val() > 0){
                                downloadItem.id = jQuery(this).closest("tr").attr("data-id");
                                downloadItem.price = jQuery(this).closest("tr").attr("data-price");
                                downloadItem.count = jQuery(this).val();
                                downloadItems.push(downloadItem);
                            }

                        });
                    }
                    data.downloadItems = downloadItems;
                    if(downloadItems.length == 0)	{
                        jQuery(".add_to_cart_msg").html("<?php echo __("Please select at least one item", 'bwge');?>");
                        return ;
                    }

                }

                var requestData = {
                    'action': 'bwge_add_cart',
                    'task': 'add_cart',
                    'controller': 'checkout',
                    "image_id": <?php echo $image_id;?>,
                    "type": type,
                    "data": JSON.stringify(data)
                };

                var ajaxurl = '<?php echo admin_url('admin-ajax.php'); ?>';
                jQuery.post(ajaxurl, requestData, function(response) {
                    //console.log(response);
                    responseData = JSON.parse(response);
                    jQuery(".add_to_cart_msg").html(responseData["msg"]);
                    jQuery(".products_in_cart").html(responseData["products_in_cart"]);
                    if(responseData["redirect"] == 1){
                        window.location.href = "<?php echo get_permalink($options->checkout_page);?>";
                    }

                });
            }
            else{
                jQuery(".add_to_cart_msg").html("<?php echo __("Please select Prints and products or Downloads", 'bwge');?>");
            }

        }

        function onBtnViewCart(){
            var checkoutPage = jQuery("[name=option_checkout_page]").val();
            jQuery("#bwge_ecommerce_form").attr("action",checkoutPage)
            jQuery("#bwge_ecommerce_form").submit();
        }

    </script>

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
