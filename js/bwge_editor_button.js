jQuery(window).load(function(){
  window.bwgDocumentReady = true;
  if (window.bwgTinymceRendered) {
    jQuery(document).trigger("bwgeonUploadImg");
  }

  jQuery('.bwge_add_short_gall').css({'marginLeft': -50});

});

(function () {
  tinymce.create('tinymce.plugins.bwge_mce', {
    init:function (ed, url) {
      var c = this;
      c.url = url;
      c.editor = ed;
      ed.addCommand('mcebwge_mce', function () {
        ed.windowManager.open({
          file:bwge_admin_ajax,
          width:1100 + ed.getLang('bwge_mce.delta_width', 0),
          height:550 + ed.getLang('bwge_mce.delta_height', 0),
          inline:1
        }, {
          plugin_url:url
        });
        var e = ed.selection.getNode(), d = wp.media.gallery, f;
        if (typeof wp === "undefined" || !wp.media || !wp.media.gallery) {
          return
        }
        if (e.nodeName != "IMG" || ed.dom.getAttrib(e, "class").indexOf("bwge_shortcode") == -1) {
          return
        }
        f = d.edit("[" + ed.dom.getAttrib(e, "title") + "]");
      });
      ed.addButton('bwge_mce', {
        id:'mceu_bwge_shorcode',
        title:'Insert Gallery Ecommerce',
        cmd:'mcebwge_mce',
        image: url + '/images/bwge_edit_but.png'
      });
      ed.onPostRender.add(function(ed, cm) {
         window.bwgTinymceRendered = true;
         if ( window.bwgDocumentReady ) {
            jQuery(document).trigger("onUploadImg");
         }
      });      
      ed.onMouseDown.add(function (d, f) {
        if (f.target.nodeName == "IMG" && d.dom.hasClass(f.target, "bwge_shortcode")) {
          var g = tinymce.activeEditor;
          g.wpGalleryBookmark = g.selection.getBookmark("simple");
          g.execCommand("mcebwge_mce");
        }
      });
      ed.onBeforeSetContent.add(function (d, e) {
        e.content = c._do_bwge(e.content)
      });
      ed.onPostProcess.add(function (d, e) {
        if (e.get) {
          e.content = c._get_bwge(e.content)
        }
      })
    },
    _do_bwge:function (ed) {
      return ed.replace(/\[BWGE_Gallery_Ecommerce([^\]]*)\]/g, function (d, c) {
        return '<img src="' + bwge_plugin_url + '/images/bwge_shortcode.png" class="bwge_shortcode mceItem" title="BWGE_Gallery_Ecommerce' + tinymce.DOM.encode(c) + '" />';
      })
    },
    _get_bwge:function (b) {
      function ed(c, d) {
        d = new RegExp(d + '="([^"]+)"', "g").exec(c);
        return d ? tinymce.DOM.decode(d[1]) : "";
      }

      return b.replace(/(?:<p[^>]*>)*(<img[^>]+>)(?:<\/p>)*/g, function (e, d) {
        var c = ed(d, "class");
        if (c.indexOf("bwge_shortcode") != -1) {
          return "<p>[" + tinymce.trim(ed(d, "title")) + "]</p>"
        }
        return e
      })
    }
  });
  tinymce.PluginManager.add('bwge_mce', tinymce.plugins.bwge_mce);
})();