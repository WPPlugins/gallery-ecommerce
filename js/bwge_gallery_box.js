var isPopUpOpened = false;

function bwge_spider_createpopup(url, current_view, width, height, duration, description, lifetime, lightbox_ctrl_btn_pos) {
  url = url.replace(/&#038;/g, '&');
  if (isPopUpOpened) { return };
  isPopUpOpened = true;
  if (bwge_spider_hasalreadyreceivedpopup(description) || bwge_spider_isunsupporteduseragent()) {
    return;
  }
  jQuery("html").attr("style", "overflow:hidden !important;");
  jQuery("#bwge_spider_popup_loading_" + current_view).css({display: "block"});
  jQuery("#bwge_spider_popup_overlay_" + current_view).css({display: "block"});

  jQuery.get(url, function(data) {
		var popup = jQuery(
    '<div id="bwge_spider_popup_wrap" class="bwge_spider_popup_wrap" style="' + 
          ' width:' + width + 'px;' +
          ' height:' + height + 'px;' + 
          ' margin-top:-' + height / 2 + 'px;' + 
          ' margin-left: -' + width / 2 + 'px; ">' +    
    data + 
    '</div>')
			.hide()
			.appendTo("body");
		bwge_spider_showpopup(description, lifetime, popup, duration, lightbox_ctrl_btn_pos);
	}).success(function(jqXHR, textStatus, errorThrown) {
    jQuery("#bwge_spider_popup_loading_" + current_view).css({display: "none !important;"});
  });
}

function bwge_spider_showpopup(description, lifetime, popup, duration, lightbox_ctrl_btn_pos) {
  isPopUpOpened = true;
  popup.show();
	bwge_spider_receivedpopup(description, lifetime, lightbox_ctrl_btn_pos);
}

function bwge_spider_hasalreadyreceivedpopup(description) {
  if (document.cookie.indexOf(description) > -1) {
    delete document.cookie[document.cookie.indexOf(description)];
  }
	return false; 
}

function bwge_spider_receivedpopup(description, lifetime, lightbox_ctrl_btn_pos) { 
	var date = new Date(); 
	date.setDate(date.getDate() + lifetime);
	document.cookie = description + "=true;expires=" + date.toUTCString() + ";path=/"; 
  if (lightbox_ctrl_btn_pos == 'bottom') {
    jQuery(".bwge_toggle_container").css("bottom", jQuery(".bwge_ctrl_btn_container").height() + "px");
  }
  else if (lightbox_ctrl_btn_pos == 'top') {
    jQuery(".bwge_toggle_container").css("top", jQuery(".bwge_ctrl_btn_container").height() + "px");
  }
}

function bwge_spider_isunsupporteduseragent() {
	return (!window.XMLHttpRequest);
}

function bwge_spider_destroypopup(duration) {
  if (document.getElementById("bwge_spider_popup_wrap") != null) {
    if (typeof jQuery().fullscreen !== 'undefined' && jQuery.isFunction(jQuery().fullscreen)) {
      if (jQuery.fullscreen.isFullScreen()) {
        jQuery.fullscreen.exit();
      }
    }
    if (typeof enable_addthis != "undefined" && enable_addthis) {
      jQuery(".at4-share-outer").hide();
    }
    setTimeout(function () {
      jQuery(".bwge_spider_popup_wrap").remove();
      jQuery(".bwge_spider_popup_loading").css({display: "none"});
      jQuery(".bwge_spider_popup_overlay").css({display: "none"});
      jQuery(document).off("keydown");
      jQuery("html").attr("style", "overflow:auto !important");
    }, 20);
  }
  isPopUpOpened = false;
  var isMobile = (/android|webos|iphone|ipad|ipod|blackberry|iemobile|opera mini/i.test(navigator.userAgent.toLowerCase()));
  var viewportmeta = document.querySelector('meta[name="viewport"]');
  if (isMobile && viewportmeta) {
    viewportmeta.content = 'width=device-width, initial-scale=1';
  }
  var scrrr = jQuery(document).scrollTop();
  window.location.hash = "";
  jQuery(document).scrollTop(scrrr);
  clearInterval(bwge_playInterval);
}
function bwge_get_ajax_pricelist(){
  var post_data = {};
  jQuery(".add_to_cart_msg").html("");
  post_data["ajax_task"] = "display";
  post_data["image_id"] = jQuery('#bwge_popup_image').attr('image_id');	

  // Loading.
  jQuery("#bwge_ecommerce_ajax_loading").css('height', jQuery(".bwge_ecommerce_panel").css('height'));
  jQuery("#bwge_ecommerce_opacity_div").css('width', jQuery(".bwge_ecommerce_panel").css('width'));
  jQuery("#bwge_ecommerce_opacity_div").css('height', jQuery(".bwge_ecommerce_panel").css('height'));
  jQuery("#bwge_ecommerce_loading_div").css('width', jQuery(".bwge_ecommerce_panel").css('width'));
  jQuery("#bwge_ecommerce_loading_div").css('height', jQuery(".bwge_ecommerce_panel").css('height'));

  jQuery("#bwge_ecommerce_opacity_div").css('display','');
  jQuery("#bwge_ecommerce_loading_div").css('display','table-cell');

  jQuery.post(
    jQuery('#bwge_ecommerce_form').attr('action'),
    post_data,

    function (data) {

      var bwge_tabs = jQuery(data).find('.bwge_tabs').html();
      jQuery('.bwge_tabs').html(bwge_tabs);
      
        jQuery(".bwge_tabs li a").click(function(){
            jQuery(".bwge_tabs_container > div").hide();
            jQuery(".bwge_tabs li").removeClass("bwge_active");
            jQuery(jQuery(this).attr("href")).show();
            jQuery(this).closest("li").addClass("bwge_active");
            jQuery("[name=type]").val(jQuery(this).attr("href").substr(1));	
            return false;
        });
      var manual = jQuery(data).find('.manual').html();
      jQuery('.manual').html(manual);
	  
      var downloads = jQuery(data).find('.downloads').html();
      jQuery('.downloads').html(downloads);	
	  
      var bwge_options = jQuery(data).find('.bwge_options').html();
      jQuery('.bwge_options').html(bwge_options);	

      var bwge_add_to_cart = jQuery(data).find('.bwge_add_to_cart').html();
      jQuery('.bwge_add_to_cart').html(bwge_add_to_cart);		  
	  jQuery("#bwge_ecommerce_form [type=text], #bwge_ecommerce_form [type=number]").keypress(function(event){
		event = event || window.event;

		if(event.keyCode == 13){
			return false;
		}
	  });   
	  	  
    }
  ).success(function(jqXHR, textStatus, errorThrown) {
      jQuery("#bwge_ecommerce_opacity_div").css('display','none');
      jQuery("#bwge_ecommerce_loading_div").css('display','none');
 
    // Update scrollbar.
    //jQuery(".bwge_ecommece_panel").mCustomScrollbar({scrollInertia: 150 });
	//jQuery(".bwge_ecommerce_close_btn").click(bwge_ecommerce);

  });
    return false;
}


// Submit popup.
function bwge_spider_ajax_save(form_id) {
  var post_data = {};
  post_data["bwge_name"] = jQuery("#bwge_name").val();
  post_data["bwge_comment"] = jQuery("#bwge_comment").val();
  post_data["bwge_email"] = jQuery("#bwge_email").val();
  post_data["bwge_captcha_input"] = jQuery("#bwge_captcha_input").val();
  post_data["ajax_task"] = jQuery("#ajax_task").val();
  post_data["image_id"] = jQuery("#image_id").val();
  post_data["comment_id"] = jQuery("#comment_id").val();

  // Loading.
  jQuery("#ajax_loading").css('height', jQuery(".bwge_comments").css('height'));
  jQuery("#opacity_div").css('width', jQuery(".bwge_comments").css('width'));
  jQuery("#opacity_div").css('height', jQuery(".bwge_comments").css('height'));
  jQuery("#loading_div").css('width', jQuery(".bwge_comments").css('width'));
  jQuery("#loading_div").css('height', jQuery(".bwge_comments").css('height'));
  document.getElementById("opacity_div").style.display = '';
  document.getElementById("loading_div").style.display = 'table-cell';
  jQuery.post(
    jQuery('#' + form_id).attr('action'),
    post_data,

    function (data) {
      var str = jQuery(data).find('.bwge_comments').html();
      jQuery('.bwge_comments').html(str);
    }
  ).success(function(jqXHR, textStatus, errorThrown) {
    document.getElementById("opacity_div").style.display = 'none';
    document.getElementById("loading_div").style.display = 'none';
    // Update scrollbar.
    jQuery(".bwge_comments").mCustomScrollbar({scrollInertia: 150});
    // Bind comment container close function to close button.
    jQuery(".bwge_comments_close_btn").click(bwge_comment);
  });

  // if (event.preventDefault) {
    // event.preventDefault();
  // }
  // else {
    // event.returnValue = false;
  // }
  return false;
}

// Submit rating.
function bwge_spider_rate_ajax_save(form_id) {
  var post_data = {};
  post_data["image_id"] = jQuery("#" + form_id + " input[name='image_id']").val();
  post_data["rate"] = jQuery("#" + form_id + " input[name='score']").val();
  post_data["ajax_task"] = jQuery("#rate_ajax_task").val();
  jQuery.post(
    jQuery('#' + form_id).attr('action'),
    post_data,

    function (data) {
      var str = jQuery(data).find('#' + form_id).html();
      jQuery('#' + form_id).html(str);
    }
  ).success(function(jqXHR, textStatus, errorThrown) {
  });
  // if (event.preventDefault) {
    // event.preventDefault();
  // }
  // else {
    // event.returnValue = false;
  // }
  return false;
}

// Set value by ID.
function bwge_spider_set_input_value(input_id, input_value) {
  if (document.getElementById(input_id)) {
    document.getElementById(input_id).value = input_value;
  }
}

// Submit form by ID.
function bwge_spider_form_submit(event, form_id) {
  if (document.getElementById(form_id)) {
    document.getElementById(form_id).submit();
  }
  if (event.preventDefault) {
    event.preventDefault();
  }
  else {
    event.returnValue = false;
  }
}

// Check if required field is empty.
function bwge_spider_check_required(id, name) {
  if (jQuery('#' + id).val() == '') {
    alert(name + '* ' + bwge_objectL10n.bwge_field_required);
    jQuery('#' + id).attr('style', 'border-color: #FF0000;');
    jQuery('#' + id).focus();
    return true;
  }
  else {
    return false;
  }
}

// Check Email.
function bwge_spider_check_email(id) {
  if (jQuery('#' + id).val() != '') {
    var email = jQuery('#' + id).val().replace(/^\s+|\s+$/g, '');
    if (email.search(/^\w+((-\w+)|(\.\w+))*\@[A-Za-z0-9]+((\.|-)[A-Za-z0-9]+)*\.[A-Za-z0-9]+$/) == -1) {
      alert(bwge_objectL10n.bwge_mail_validation);
      return true;
    }
    return false;
  }
}

// Refresh captcha.
function bwge_captcha_refresh(id) {
	if (document.getElementById(id + "_img") && document.getElementById(id + "_input")) {
		srcArr = document.getElementById(id + "_img").src.split("&r=");
		document.getElementById(id + "_img").src = srcArr[0] + '&r=' + Math.floor(Math.random() * 100);
		document.getElementById(id + "_img").style.display = "inline-block";
		document.getElementById(id + "_input").value = "";
	}
}

function bwge_play_instagram_video(obj,bwge) {
  jQuery(obj).parent().find("video").each(function () {
    if (jQuery(this).get(0).paused) {
      jQuery(this).get(0).play();
      jQuery(obj).children().hide();
    }
    else {
      jQuery(this).get(0).pause();
      jQuery(obj).children().show();
    }
  })
}
