
//  ============================================
//  SIDEPAGE
//  ============================================

function pageEditor_page_settings(){
  var html = "";


  pageEditorSidePage.animate({'opacity':0}, 500, function(){
    pageEditorSidePage.html(html);
    pageEditorSidePage.animate({'opacity':1}, 500,function(){
      // loaded
    });
  });
}

function pageEditor_page_plugins(){
  pageEditorSidePage.animate({'opacity':0}, 500,function(){
    var html = '<div class="plugins">';
    $.getJSON("php/ajax_pageEditor.php?a=getPlugins", function(data){console.log(data);
      if(data.error == false){
        data.data.forEach(function(plugin){
          html += '<div class="plugin"><div class="name">' + plugin.name + '</div>';
          if(plugin.thumb == true){
            html += '<div class="thumb" style="background-image:url(../' + plugin.thumbUrl + ')">&nbsp;</div>';
          }
          html += '</div>';
        });

      }


      html += '</div>';
      pageEditorSidePage.html(html);
      pageEditorSidePage.animate({'opacity':1}, 500,function(){
        // loaded
      });
    });
  });
}



//  ============================================
//  SETUP
//  ============================================

function initPageEditor(){
  pageEditorPreview = $("#pageEditorPreview");
  pageEditorPreview.on('load', function(){
    pageEditor_findElements();
    pageEditor_addPluginHandler();
    pageEditor_page_plugins();
  });
}

function pageEditor_findElements(){
  // PREVIEW IFRAME STUFF
   pageEditorAreas = pageEditorPreview.contents().find('.oxymora-area');
   pageEditorPlugins = pageEditorPreview.contents().find('.oxymora-plugin');
   // LIGHTBOX STUFF
   pageEditorSidePage = lightboxDialog.contents().find('.menu');
}

//  ============================================
//  HANDLER
//  ============================================

function pageEditor_addPluginHandler(){
  pageEditorPlugins.each(function(){
    $(this).find('.oxymora-plugin-edit').on('click', pageEditor_plugin_editHandler);
    $(this).find('.oxymora-plugin-delete').on('click', pageEditor_plugin_deleteHandler);
  });
}

function pageEditor_plugin_editHandler(){
  console.log(this);
}

function pageEditor_plugin_deleteHandler(){
  // todo: nicer Confirm..
  if(confirm("Sicher löschen?")){
    deletePlugin($(this).parent().parent());
  }
}

//  ============================================
//  PLUIGIN FUNCTIONS
//  ============================================

function deletePlugin(plugin){
    plugin.data('action', 'deleted');
    plugin.css('display', 'none');
}
