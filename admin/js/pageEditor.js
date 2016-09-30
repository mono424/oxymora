
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
          html += '<div draggable="true" class="plugin"><div class="name">' + plugin.name + '</div>';
          if(plugin.thumb == true){
            html += '<div class="thumb" style="background-image:url(../' + plugin.thumbUrl + ')">&nbsp;</div>';
          }
          html += '</div>';
        });

      }


      html += '</div>';
      pageEditorSidePage.html(html);
      pageEditor_addMenuPluginHandler();
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
    pageEditor_addIframeHandler();
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

function pageEditor_addMenuPluginHandler(){
    pageEditorSidePage.find('.plugin').on('dragstart', pageEditor_menu_plugin_dragstartHandler);
    pageEditorSidePage.find('.plugin').on('dragend', pageEditor_menu_plugin_dragendHandler);
}

function pageEditor_menu_plugin_dragstartHandler(){
  $(this).css("border-color","rgb(255, 0, 168)");
  $(this).find('.name').css("color","rgb(255, 140, 240)");
}

function pageEditor_menu_plugin_dragendHandler(){
  $(this).css("border-color","rgb(11, 118, 224)");
  $(this).find('.name').css("color","white");
}



//  ============================================
//  IFRAME HANDLER
//  ============================================

var dropTarget = null;
function pageEditor_addIframeHandler(){
  // IFrame Handler
  pageEditorPreview.contents().find('html').on('drop', function(e){
    e.preventDefault()
    pageEditor_iframe_dropHandler(this, e);
  });

  // Area Handler
  pageEditorAreas.each(function(){
    $(this).on('dragleave', function(e){
      e.preventDefault()
      if(e.target === this){
        pageEditor_iframe_area_dragleaveHandler(this, e);
      }
    }).on('dragover', function(e){
      e.preventDefault()
      if(e.target === this){
        pageEditor_iframe_area_dragoverHandler(this, e);
      }
    }).on('dragenter', function(e){
      e.preventDefault()
      if(e.target === this){
        pageEditor_iframe_area_dragenterHandler(this, e);
      }
    });
  });

  // Plugin Handler
  pageEditorPlugins.each(function(){
    $(this).find('.oxymora-plugin-edit').on('click', pageEditor_iframe_plugin_editHandler);
    $(this).find('.oxymora-plugin-delete').on('click', pageEditor_iframe_plugin_deleteHandler);
    $(this).on('dragover', function(e){
      e.preventDefault()
      pageEditor_iframe_plugin_dragoverHandler(this, e);
    }).on('dragenter', function(e){
      e.preventDefault()
      pageEditor_iframe_plugin_dragenterHandler(this, e);
    });
  });
}

function pageEditor_iframe_plugin_editHandler(){
  // todo: plugin edit handler
  console.log(this);
}

function pageEditor_iframe_plugin_deleteHandler(){
  // todo: nicer Confirm..
  if(confirm("Sicher löschen?")){
    deletePlugin($(this).parent().parent());
  }
}

function pageEditor_iframe_plugin_dragoverHandler(plugin, e) {
}

function pageEditor_iframe_plugin_dragenterHandler(plugin, e){
  pageEditorPreview.contents().find('.oxymora-drop-marker').remove();
  dropTarget = $(plugin);
  $(plugin).append(dropMarker());
}




function pageEditor_iframe_dropHandler(item, e) {
  pageEditorPreview.contents().find('.oxymora-drop-marker').remove();
  var target = dropTarget;
  dropTarget = null;
  console.log(target);
}

function pageEditor_iframe_dragleaveHandler(plugin, e) {
  pageEditorPreview.contents().find('.oxymora-drop-marker').remove();
}




function pageEditor_iframe_area_dragenterHandler(area, e) {
  pageEditorPreview.contents().find('.oxymora-drop-marker').remove();
  dropTarget = $(area);
  $(area).append(dropMarker());
}

function pageEditor_iframe_area_dragoverHandler(area, e) {
}

function pageEditor_iframe_area_dragleaveHandler(area, e) {
  pageEditorPreview.contents().find('.oxymora-drop-marker').remove();
  dropTarget = null;
}

//  ============================================
//  PLUIGIN FUNCTIONS
//  ============================================

function dropMarker(){
  return "<div class='oxymora-drop-marker'>insert here!</div>";
}

function deletePlugin(plugin){
    plugin.data('action', 'deleted');
    plugin.css('display', 'none');
}
