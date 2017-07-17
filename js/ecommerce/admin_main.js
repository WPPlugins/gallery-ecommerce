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
function bwgeFormSubmit(task){
	if(typeof task == "undefinded"){
		task == "";
	}
	var adminForm = jQuery("#adminForm");	
	if(task != ""){
		bwgeFormInputSet("task",task);
		switch(task){
			case "save" :
			case "apply" :
				  fillInputs();
				  if (jQuery(".wd-required").val() == ""){
					jQuery(".wd-required[value='']").addClass("wd-required-active");
					return false;
				  }
				break;
		
		}
	} 
	adminForm.submit();

}

function removeRedBorder(obj){
	jQuery(obj).removeClass("wd-required-active");
}

function fillInputs(){
	switch(_page){
		case "ecommerceoptions_bwge" :
            fillInputPaypalStandartOption();
			break;	    
		case "pricelists_bwge" :
			fillInputPricelistItems();
			fillInputParameters();
			break;
		case "parameters_bwge" :
			fillInputParameterDefaultValues();	
			break;	
            
		
	}
}

function bwgeSearch(formId) {
    if(typeof formId == "undefined"){
        formId = "adminForm";
    }
    document.getElementById("page_number").value = "1";
    document.getElementById("search_or_not").value = "search";
    
    document.getElementById(formId).submit();


    
}
function bwgeCheckSearchKey(e, that) {
    var key_code = (e.keyCode ? e.keyCode : e.which);
    if (key_code == 13) { /*Enter keycode*/
    bwgeSearch();
        return false;
    }
    return true;
}

function bwgeReset(formId) {
    if(typeof formId == "undefined"){
        formId = "adminForm";
    }
    if (document.getElementById("search_value")) {
        document.getElementById("search_value").value = "";
    }
    if (document.getElementById("search_select_value")) {
        document.getElementById("search_select_value").value = 0;
    }
    document.getElementById(formId).submit();
}

function bwge_spider_check_required(id, name) {
  if (jQuery('#' + id).val() == '') {
    alert(name + '* ' + bwge_objectL10B.bwge_field_required);
    jQuery('#' + id).attr('style', 'border-color: #FF0000!important;');
    jQuery('#' + id).focus();
    jQuery('html, body').animate({
      scrollTop:jQuery('#' + id).offset().top - 200
    }, 500);
    return true;
  }
  else {
    return false;
  }
}
////////////////////////////////////////////////////////////////////////////////////////
// Getters & Setters                                                                  //
////////////////////////////////////////////////////////////////////////////////////////
function bwgeFormInputSet(name, value){
	jQuery("[name=" + name + "]").val(value);
}
////////////////////////////////////////////////////////////////////////////////////////
// Private Methods                                                                    //
////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////
// Listeners                                                                          //
////////////////////////////////////////////////////////////////////////////////////////