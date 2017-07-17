function bwge_spider_frontend_ajax(form_id, current_view, id, album_gallery_id, cur_album_id, type, srch_btn, title, sortByParam, load_more) {
  var masonry_already_loaded = jQuery(".bwge_masonry_thumb_spun_" + current_view + " img").length;
  var mosaic_already_loaded = jQuery(".bwge_mosaic_thumb_spun_" + current_view + " img").length;
  if (typeof load_more == "undefined") {
    var load_more = false;
  }
  var page_number = jQuery("#bwge_page_number_" + current_view).val();
  var bwge_load_more = jQuery("#bwge_load_more_" + current_view).val();
  var bwge_previous_album_ids = jQuery('#bwge_previous_album_id_' + current_view).val();
  var bwge_previous_album_page_numbers = jQuery('#bwge_previous_album_bwge_page_number_' + current_view).val();
  var post_data = {};
  if (album_gallery_id == 'back') { // Back from album.
    var bwge_previous_album_id = bwge_previous_album_ids.split(",");
    album_gallery_id = bwge_previous_album_id[1];
    jQuery('#bwge_previous_album_id_' + current_view).val(bwge_previous_album_ids.replace(bwge_previous_album_id[0] + ',', ''));
    var bwge_previous_album_page_number = bwge_previous_album_page_numbers.split(",");
    page_number = bwge_previous_album_page_number[0];
    jQuery('#bwge_previous_album_bwge_page_number_' + current_view).val(bwge_previous_album_page_numbers.replace(bwge_previous_album_page_number[0] + ',', ''));
  }
  else if (cur_album_id != '') { // Enter album (not change the page).
    jQuery('#bwge_previous_album_id_' + current_view).val(album_gallery_id + ',' + bwge_previous_album_ids);
    if (page_number) {
      jQuery('#bwge_previous_album_bwge_page_number_' + current_view).val(page_number + ',' + bwge_previous_album_page_numbers);
    }
    page_number = 1;
  }
  if (srch_btn) { // Start search.
    page_number = 1; 
  }
  if (typeof title == "undefined" || title == '') {
    var title = "";
  }
  if (typeof sortByParam == "undefined" || sortByParam == '') {
    var sortByParam = jQuery(".bwge_order_" + current_view).val();
  }
  
  post_data["bwge_page_number_" + current_view] = page_number;
  post_data["bwge_load_more_" + current_view] = bwge_load_more;
  post_data["bwge_album_gallery_id_" + current_view] = album_gallery_id;
  post_data["bwge_previous_album_id_" + current_view] = jQuery('#bwge_previous_album_id_' + current_view).val();
  post_data["bwge_previous_album_bwge_page_number_" + current_view] = jQuery('#bwge_previous_album_bwge_page_number_' + current_view).val();
  post_data["bwge_type" + current_view] = type;
  post_data["bwge_title_" + current_view] = title;
  post_data["bwge_sortImagesByValue_" + current_view] = sortByParam;
  if (jQuery("#bwge_search_input_" + current_view).length > 0) { // Search box exists.
    post_data["bwge_search_" + current_view] = jQuery("#bwge_search_input_" + current_view).val();
  }

  post_data["bwge_tag_id_" + id] = typeof jQuery("#bwge_tag_id_" + id).val() != 'undefined' ? jQuery("#bwge_tag_id_" + id).val() : '';
 
  // Loading.
  jQuery("#ajax_loading_" + current_view).css('display', '');
  jQuery.post(
    window.location,
    post_data,
    function (data) {      
      if (load_more) {
        var strr = jQuery(data).find('#' + id).html();
        jQuery('#' + id).append(strr);
        var str = jQuery(data).find('.bwge_nav_cont_'+ current_view).html();
        jQuery('.bwge_nav_cont_'+ current_view).html(str);
      }
      else {
        var str = jQuery(data).find('#' + form_id).html();
        jQuery('#' + form_id).html(str);
      }
      // There are no images.
      if (jQuery("#bwge_search_input_" + current_view).length > 0 && album_gallery_id == 0) { // Search box exists and not album view.
        var bwge_images_count = jQuery('#bwge_images_count_' + current_view).val();

        
        if (bwge_images_count == 0) {
          var cont = jQuery("#" + id).parent().html();
          var error_msg = '<div style="width:95%"><div class="error"><p><strong>' + bwge_objectL10n.bwge_search_result + '</strong></p></div></div>';
          jQuery("#" + id).parent().html(error_msg + cont)
        }
      }
    }
  ).success(function(jqXHR, textStatus, errorThrown) {
      jQuery(".blog_style_image_buttons_conteiner_" + current_view).find(jQuery(".bwge_blog_style_img_" + current_view)).load(function(){
        jQuery(".bwge_blog_style_img_" + current_view).closest(jQuery(".blog_style_image_buttons_conteiner_" + current_view)).show();
      })
    jQuery("#ajax_loading_" + current_view).css('display', 'none');
    jQuery("#bwge_tags_id_" + id).val(jQuery("#bwge_tag_id_" + id).val());

    if (jQuery(".pagination-links_" + current_view).length) {
      jQuery("html, body").animate({scrollTop: jQuery('#' + form_id).offset().top - 150}, 500);
    }
    /* For all*/
    window["bwge_document_ready_" + current_view]();
    /* For masonry view.*/
    if (id == "bwge_masonry_thumbnails_" + current_view || id == "bwge_album_masonry_" + current_view) {
      window["bwge_masonry_ajax_"+ current_view](masonry_already_loaded);
    }
    /* For mosaic view.*/
    if (id == "bwge_mosaic_thumbnails_" + current_view) {
      window["bwge_mosaic_ajax_" + current_view](mosaic_already_loaded);
    }
    /* For Blog style view.*/
    jQuery(".blog_style_images_conteiner_" + current_view + " .bwge_embed_frame_16x9_" + current_view).each(function (e) {
      jQuery(this).width(jQuery(this).parent().width());
      jQuery(this).height(jQuery(this).width() * 0.5625);
    });
    jQuery(".blog_style_images_conteiner_" + current_view + " .bwge_embed_frame_instapost_" + current_view).each(function (e) {
      jQuery(this).width(jQuery(this).parent().width());
      jQuery(this).height(jQuery(this).width() +88);
    });
    /* For Image browser view.*/
    jQuery('#bwge_embed_frame_16x9_'+current_view).width(jQuery('#bwge_embed_frame_16x9_'+current_view).parent().width());
    jQuery('#bwge_embed_frame_16x9_'+current_view).height(jQuery('#bwge_embed_frame_16x9_'+current_view).width() * 0.5625);
    jQuery('#bwge_embed_frame_instapost_'+current_view).width(jQuery('#bwge_embed_frame_16x9_'+current_view).parent().width());
    jQuery('#bwge_embed_frame_instapost_'+current_view).height(jQuery('#bwge_embed_frame_instapost_'+current_view).width() +88);
  });
  // if (event.preventDefault) {
    // event.preventDefault();
  // }
  // else {
    // event.returnValue = false;
  // }
  return false;
}
