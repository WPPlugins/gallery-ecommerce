
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
// Constructor                                                                        //
////////////////////////////////////////////////////////////////////////////////////////


function showErrorMsg(msg){
	var jq_alert = jQuery(".bwge_checkout_alert_incorrect_data");
	if (jq_alert.is(":visible") == false) {
		jq_alert
			.show()
			.slideUp(0)
			.slideDown(250);
	} else {
		jq_alert
			.fadeOut(100)
			.fadeIn(100);
	}
	jQuery(".bwge_checkout_alert_incorrect_data").html(msg);
}

function checkFormData(step){
	jQuery(".form_row").removeClass("has-error");
	jQuery(".form_row").find("input").removeClass("has-error");
	jQuery(".bwge_checkout_alert_incorrect_data").hide();
	
	switch(step){
		case "bwge_shipping_billing_info":
			if(validateEmail(jQuery("[name=billing_data_email]").val()) == false){
				showErrorMsg("Invalid email address.");
				jQuery("[name=billing_data_email]").addClass("has-error");
				return false;			
			}
			break;
	
	}
	var flag = true;
	jQuery(".bwge_shipping_billing_info [data-bwge-required]").each(function(){
		if(jQuery(this).val() == ""){
			showErrorMsg("Please fill required fields.");
			jQuery(this).addClass("has-error");
			flag = false;
			return;
		}
	});

	return flag;

}



function submitCheckoutForm(obj,event){
	var paymetMethod = jQuery("[name=payment_method]").val();
	var step = jQuery(obj).closest(".bwge_checkout_step").attr("data-step");
    
    jQuery(".form_row").removeClass("has-error");
    jQuery(".form_row").find("input").removeClass("has-error");
    if( jQuery("[name=accept_terms]").length>0 && jQuery("[name=accept_terms]").prop("checked") == false){
        showErrorMsg("Please fill required fields.");
        jQuery("[name=accept_terms]").closest(".form_row").addClass("has-error");
        return false;		
    }

    var flag = checkFormData(step);
    if(flag == false ){
        return false;	
    }
    jQuery("#bwge_order_form").submit();						
	



}

jQuery(document).ready(function($) {
	
	jQuery(".bwge_payment_btn").click(function(){
		if(Number(jQuery("[name=bwge_product_price]").val()) < 0){
			jQuery(".bwge_img").html("Invalid price.");
			return false;
		}
		var paymetMethod = jQuery(this).attr("data-payment-method");		
		jQuery("[name=payment_method]").val(paymetMethod);
		jQuery(".bwge_checkout_form_wrap_opacity").show();		
		var data = {
			'action': 'bwge_display_checkout_form',
			'task': 'display_checkout_form',
			'controller': 'checkout',
			"payment_method": paymetMethod
			
		};		
		jQuery.post(ajaxURL, data, function(response) {
			
			jQuery('.bwge_checkout_form_wrap').html(response);			
			jQuery(".bwge_checkout_form_wrap").show();
		
		});
	});	
	
	
	
});

function validateEmail(email) {
    var re = /^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$/i;
    return re.test(email);
}


////////////////////////////////////////////////////////////////////////////////////////
// Public Methods                                                                     //
////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////
// Getters & Setters                                                                  //
////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////
// Private Methods                                                                    //
////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////
// Listeners                                                                          //
////////////////////////////////////////////////////////////////////////////////////////
