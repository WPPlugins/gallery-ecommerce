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
jQuery(document).ready(function () {
	jQuery("#sections_tabs ul li a").click(function(){
		jQuery("#manual, #downloads").hide();
		jQuery("#sections_tabs ul li a").removeClass("sections_tab_active");
		jQuery(jQuery(this).attr("href")).show();
		jQuery(this).addClass("sections_tab_active");
		
		jQuery("#active_tab").val(jQuery(this).attr("href"));
		return false;
	});

	orderParameters();
});
////////////////////////////////////////////////////////////////////////////////////////
// Public Methods                                                                     //
////////////////////////////////////////////////////////////////////////////////////////
function onChangePricelistSection(obj){
        jQuery(document).trigger("onCheckSection");
		var _this = jQuery(obj);
		jQuery("#tabs_wrapper").hide();
		jQuery("#sections_tabs ul").css("display", "none");
		jQuery("#sections_tabs ul li").hide();
		jQuery("#downloads").hide();
		jQuery("#manual").hide();
		jQuery(".section_title").hide();
		pricelistSections = [];
		jQuery(".bwge_sections:checked").each(function(){
			pricelistSections.push(jQuery(this).val());
		});

		if(pricelistSections.length > 0){
			jQuery("#tabs_wrapper").show();
			jQuery("#" + pricelistSections[0]).show();			
			if(pricelistSections.length > 1){
				jQuery("#sections_tabs ul").css("display", "block");	
				for( k=0 ; k<pricelistSections.length; k++ ){						
					jQuery("#" + pricelistSections[k] + "_li").show();					
				}			
				jQuery("#sections_tabs ul li a").removeClass("sections_tab_active");
				jQuery("#sections_tabs ul #" + _this.val() + "_li a").addClass("sections_tab_active");
				jQuery("#manual, #downloads").hide();
				jQuery("#" + _this.val()).show();
				jQuery("#active_tab").val("#" + _this.val());
			}
			else{
				jQuery(".section_title").html(pricelistSections[0].charAt(0).toUpperCase() + pricelistSections[0].slice(1));
				jQuery(".section_title").show();
			}
					
		}
}
function bwgeRemovePricelistItem(obj){
	jQuery(obj).closest("tr").remove();
	
}

function bwgeRemoveParameterValue(obj){
	jQuery(obj).parent().remove();	
}

function bwgeAddPricelistItem(obj){
	var type = jQuery(obj).attr("data-type");
	var itemsContainer = jQuery("." + type + " .itmes-body");
	var template = itemsContainer.find(".template");
	var newItemRow = template.clone();
	newItemRow.removeClass("template");
	newItemRow.appendTo(itemsContainer);
}

function fillInputPricelistItems(){	
	var downloadItems = [];
	jQuery(".downloads .itmes-body .item-row:not(.template)").each(function(){		
		if(jQuery(this).find(".item_name").val() && jQuery(this).find(".item_price").val() ){
			var downloadItem = { "name" : jQuery(this).find(".item_name").val(), "price" : jQuery(this).find(".item_price").val(), "longest_dimension" : jQuery(this).find(".item_longest_dimension").val() };
			downloadItems.push(downloadItem);
		}	
	});
	bwgeFormInputSet("download_items", JSON.stringify(downloadItems));
}

function orderParameters(){
	jQuery( ".wd_bwge_parameters" ).sortable({	
		axis: 'y',
		opacity: 0.8,
		cursor: 'move',	
		handle: ".icon-drag-1"
	});	
	jQuery( ".wd_bwge_parameters_values" ).sortable({	
		axis: 'y',
		opacity: 0.8,
		cursor: 'move',	
		handle: ".icon-drag-2"
	});		
	
	
}

function fillInputParameters(){
	var parameters = [];
	
	jQuery(".parameters_container tbody tr:not(.template)").each(function(){
		var parameter = {};
		var parametrId = jQuery(this).attr("data-id");
		var parametrType = jQuery(this).attr("data-type");
        if(parametrType == 2 || parametrType == 3 || parametrType == 1){
			if(jQuery(this).find(".parameter_values_container .parameter_values .parameter_value").val() != ""){
				parameter.id = parametrId;
				parameter.type = parametrType;
				parameter.value = jQuery(this).find(".parameter_values_container .parameter_values .parameter_value").val();
				parameter.price = "";
				parameter.price_sign = "";
				parameters.push(parameter);
			}
		}
		else if(parametrType == 4 || parametrType == 5 || parametrType == 6 ){			
			jQuery(this).find(".parameter_values_container .parameter_values .multi_select_parameter_container div:not(.template)").each(function(){
				if(jQuery(this).find(".parameter_value").val() != ""){
					var parameter = {};
					parameter.id = parametrId;
					parameter.type = parametrType;
					parameter.value = jQuery(this).find(".parameter_value").val();
					parameter.price = jQuery(this).find(".parameter_price").val();
					parameter.price_sign = jQuery(this).find(".parameter_price_sign").val();				
					parameters.push(parameter);
				}				
			});		
		}
		
		
	});
	
	bwgeFormInputSet("parameters", JSON.stringify(parameters));

}

function showInternationalShipping(obj){

	if(jQuery("#enable_international_shipping:checked").length > 0){
		jQuery(".international_shipping").removeClass("hide");
	}
	else{
		jQuery(".international_shipping").addClass("hide");
	}

}

function showPagesForLicense(obj){
	if(jQuery("#display_license:checked").length > 0){
		jQuery(".license_id").removeClass("hide");
	}
	else{
		jQuery(".license_id").addClass("hide");
	}
}


function addParameter(parameters){
	tb_remove();
	var parameterTypes = ["","Single value","Input","Textarea","Select","Radio","Checkbox"];

	for(var i=0; i<parameters.length; i++){
		var parameter = parameters[i];
		if(jQuery(".parameters_container tbody tr[data-id=" + parameter.id + "]").length>0){
			continue;
		}
		var parameterRow = jQuery(".parameters_container tbody tr.template").clone();
		parameterRow.removeClass("template");
		parameterRow.attr("data-id" , parameter.id);
		parameterRow.attr("data-type" , parameter.type);
		
		parameterRow.find(".col_title").html(parameter.title);
		parameterRow.find(".col_type").html(parameterTypes[parameter.type]);
		var values = JSON.parse(parameter.default_values);
		parameterValuesDiv = "";
		if(parameter.type == 2 || parameter.type == 1 ){
			var parameterValuesDiv = parameterRow.find(".parameter_values_container > .template .input_parameter").clone();
			parameterValuesDiv.find("input").val(values[0]);
		}
		else if(parameter.type == 3){
			var parameterValuesDiv = parameterRow.find(".parameter_values_container >  .template .textarea_parameter").clone();
			parameterValuesDiv.find("textarea").html(values[0]);		
		}
		else if(parameter.type == 4 || parameter.type == 5 || parameter.type == 6){
			var parameterValuesDiv = parameterRow.find(" .parameter_values_container >  .template .multi_select_parameter").clone();
			for(var j=0; j<values.length; j++){			
				var parameterSingleValueDiv = parameterValuesDiv.find(".template").clone();
				parameterSingleValueDiv.removeClass("template");
				parameterSingleValueDiv.find(".parameter_value").val(values[j]);
				parameterValuesDiv.find(".multi_select_parameter_container").append(parameterSingleValueDiv);
			}			
		}
		parameterRow.find(".parameter_values_container .parameter_values").append(parameterValuesDiv);		
		jQuery(".parameters_container tbody").append(parameterRow);
	}
		
	jQuery(".parameters_container").removeClass("hide");
	
}

function addParameterValue(obj){
	var newValue = jQuery(".parameters_container tr.template .parameter_values_container > .template  .multi_select_parameter .template").clone();
	newValue.removeClass("template");
	jQuery(obj).closest(".parameter_values").find(".multi_select_parameter .multi_select_parameter_container").append(newValue);
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