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
jQuery( document ).ready(function() {

	jQuery("#option_tabs ul.option_main_tabs li a").click(function(){
		jQuery("#bwge_option_tab_container > div").hide();
		jQuery("#option_tabs ul.option_main_tabs li a").attr("class","");
		jQuery(jQuery(this).attr("href")).show();
		jQuery(this).addClass("bwge_options_active_tab");
		jQuery(this).addClass(jQuery(this).attr("id") + "_active");
		jQuery("#active_tab").val(jQuery(this).attr("href"));
		return false;
	});
	
	jQuery(".sub_tabs").click(function(){
        var type = jQuery(this).closest(".sub_tab_container").attr("data-type");
		jQuery(this).closest(".sub_tab_container").find(".sections_tabs_container > div").hide();
		jQuery(this).closest(".sub_tab_container").find(".sub_tabs").removeClass("active_sub_tab");
		jQuery(jQuery(this).attr("href")).show();
		jQuery(this).addClass("active_sub_tab");

		jQuery("#" + type + "_active_tab").val(jQuery(this).attr("href"));
		return false;
	});
    
    showField(jQuery("[name=mode_s]:checked"));
});

function openMediaUploader(e){
    e.preventDefault();
	var custom_uploader = wp.media({
        title: 'Upload logo',
        button: {
            text: 'Upload logo'
        },
        multiple: false  
    })
    .on('select', function() {
       var attachment = custom_uploader.state().get('selection').first().toJSON();
	   jQuery(".email_logo_wrapper").html("<img src='"+attachment.url+"' width='100'>");
       jQuery('#email_header_logo').val(attachment.url);

    })
    .open();
	
	return false;

}
function showOptionsFromEmailField(obj){
	if(jQuery(obj).val() == 0){
		jQuery(".email_from_admin_field").removeClass("hide");
	}
	else{
		jQuery(".email_from_admin_field").addClass("hide");
	}
}

function bwgeInsertPlaceholder(obj){
	var editorId = jQuery(obj).parent().attr("data-editor");
	placeholder = "%%" + jQuery(obj).attr("data-placeholder") + "%%";
	var is_tinymce_active = (typeof tinyMCE != "undefined") && tinyMCE.EditorManager.get(editorId) && !tinyMCE.EditorManager.get(editorId).isHidden();
	
	if(is_tinymce_active){
		window.tinyMCE.EditorManager.get(editorId).execCommand('mceInsertContent', false, placeholder);	
	}	
	else{
		
		//IE support
		if (document.selection) {
			document.getElementById(editorId).focus();
			sel = document.selection.createRange();
			sel.text = placeholder;
		}
		//MOZILLA and others
		else if (document.getElementById(editorId).selectionStart || document.getElementById(editorId).selectionStart == '0') {
			var startPos = document.getElementById(editorId).selectionStart;
			var endPos = document.getElementById(editorId).selectionEnd;
			document.getElementById(editorId).value = document.getElementById(editorId).value.substring(0, startPos)
				+ placeholder
				+ document.getElementById(editorId).value.substring(endPos, document.getElementById(editorId).value.length);
		} else {
			document.getElementById(editorId).value += placeholder;
		}
	}
}
function fillInputPaypalStandartOption(){
    var valueOption;
    elements = {};
    var fields = JSON.parse(_fieldsPS);
    
    for(var field in fields){			
        var value;
        if(jQuery(".paypal_standart [name="+field+"]").prop("tagName") == 'select')
            value = jQuery(".paypal_standart [name="+field+"_ps]:selected").val();
        else if(jQuery(".paypal_standart [name="+field+"_ps]").attr("type") == 'radio' || jQuery(".paypal_standart [name="+field+"_ps]").attr("type") == 'checkbox' )
            value = jQuery(".paypal_standart [name="+field+"_ps]:checked").val();
        else
            value = jQuery(".paypal_standart [name="+field+"_ps]").val().trim();
        elements[field] = value;
    }				
        
    valueOption = JSON.stringify(elements);
    bwgeFormInputSet("options_paypalstandart", valueOption);
}


function showField(obj){
	
	if( jQuery(obj).val() == 0){		
		jQuery('.test').parent().parent().show();
		jQuery('.live').parent().parent().hide();		
	}
	else{
		jQuery('.test').parent().parent().hide();
		jQuery('.live').parent().parent().show();	
	}

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