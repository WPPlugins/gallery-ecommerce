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
function addParameter(type){
	jQuery(".default_values .input_parameter").addClass("hide");
	jQuery(".default_values .textarea_parameter").addClass("hide");
	jQuery(".default_values .multi_select_parameter").addClass("hide");	
	
	if(type == 0 ){
		jQuery(".parameters_container").addClass("hide");	
	}
	else{
		jQuery(".parameters_container").removeClass("hide");
		if(type == 2 || type == 1){
			if(jQuery(".default_values .input_parameter").length > 0){
				jQuery(".default_values .input_parameter").removeClass("hide");
			}
			else{
				cloneParameterTemplate(jQuery(".input_parameter"));
			}
		}
		else if(type == 3){
			if(jQuery(".default_values .textarea_parameter").length > 0){
				jQuery(".default_values .textarea_parameter").removeClass("hide");
			}
			else{	
				cloneParameterTemplate(jQuery(".textarea_parameter"));
			}
		}
		else{						
			if(jQuery(".default_values .multi_select_parameter").length > 0){
				jQuery(".default_values .multi_select_parameter").removeClass("hide");
			}
			else{						
				cloneParameterTemplate(jQuery(".multi_select_parameter"));
			}
		}		
	}

}
function cloneParameterTemplate(obj){
	var parameterContainer = obj.clone();
	parameterContainer.removeClass("template");
	jQuery(".default_values").append(parameterContainer);
}

function bwgeAddMultiSelectParameter(obj){
	var multiSelectParameterRow = jQuery(".default_values .multi_select_parameter_container .template").clone();
	multiSelectParameterRow.removeClass("template");
	jQuery(".default_values .multi_select_parameter_container").append(multiSelectParameterRow);
}

function bwgeRemoveMultiSelectParameter(obj){
	jQuery(obj).parent().parent().remove();
}

function fillInputParameterDefaultValues(){
	var parameterDefaultValues = [];
	jQuery(".default_values .parameters_values:not(.hide) .parameter_default_value").each(function(){
		if(jQuery(this).val() != ""){
			parameterDefaultValues.push(jQuery(this).val());
		}	
	});
	bwgeFormInputSet("default_values",JSON.stringify(parameterDefaultValues));
}

function bwgeSelectParameters(){
	var parameters = [];
	jQuery(".cid:checked").each(function(){
		var row = jQuery(this).closest("tr");
		var parameter = {};
		parameter.id = row.attr("data-id");
		parameter.title = row.attr("data-title");
		parameter.type = row.attr("data-type");
		parameter.default_values = row.attr("data-default-values");	
		
		parameters.push(parameter);
	
	});
	
	window.parent.addParameter(parameters);	
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

function showParameterDefaultValues(obj){
	var type = jQuery(obj).val();
	addParameter(type);
}

function bwgeSelectAllClick(){
	bwgeSelectParameters();
}

function bwgeSelectClick(obj){
	jQuery(".cid").prop("checked", false);
    jQuery(obj).closest("tr").find(".cid").prop("checked", true);
	bwgeSelectParameters();
}