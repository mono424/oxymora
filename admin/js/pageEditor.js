
//  ============================================
//  SIDEPAGE
//  ============================================

function pageEditor_page_settings(plugin, id, callback){
  pageEditorSidePage.animate({'opacity':0}, 500, function(){
    var html = "";
    $.getJSON("php/ajax_pageEditor.php?a=pluginSettings&plugin="+encodeURIComponent(plugin)+"&id="+encodeURIComponent(id), function(data){console.log(data);
      if(data.error == false){
        data.data.forEach(function(setting){
          html += addSettingInput(setting);
        });
      }
      pageEditorSidePage.html(html);
      pageEditorSidePage.animate({'opacity':1}, 500,function(){
        // loaded
      });
    });
  });
}

function pageEditor_page_plugins(){
  pageEditorSidePage.animate({'opacity':0}, 500,function(){
    var html = '<div class="plugins">';
    $.getJSON("php/ajax_pageEditor.php?a=getPlugins", function(data){
      if(data.error == false){
        data.data.forEach(function(plugin){
          html += '<div data-name="'+plugin.name+'" draggable="true" class="plugin"><div class="name">' + plugin.config.displayname + '</div>';
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

var lastDraggedPlugin = null;
function pageEditor_addMenuPluginHandler(){
  pageEditorSidePage.find('.plugin').on('dragstart', pageEditor_menu_plugin_dragstartHandler);
  pageEditorSidePage.find('.plugin').on('dragend', pageEditor_menu_plugin_dragendHandler);
}

function pageEditor_menu_plugin_dragstartHandler(){
  lastDraggedPlugin = $(this);
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
  pageEditorPreview.contents().find('html').on('drop', pageEditor_iframe_dropHandler);

  // Area Handler
  pageEditorAreas.each(function(){
    $(this).on('dragleave', function(e){
      e.preventDefault()
      if(e.target === this){
        pageEditor_iframe_area_dragleaveHandler(this, e);
      }
    }).on('dragover', function(e){
      e.preventDefault()
    }).on('dragenter', function(e){
      e.preventDefault()
      if(e.target === this){
        pageEditor_iframe_area_dragenterHandler(this, e);
      }
    });
  });

  // Plugin Handler
  pageEditorPlugins.each(function(){
    addPluginHandler($(this));
  });
}


// ----------------------
//  Plugin Handler
// ----------------------

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

function pageEditor_iframe_plugin_dragenterHandler(plugin, e){
  dropMarker(plugin);
}


// ----------------------
//  Iframe "html" handler
// ----------------------

function pageEditor_iframe_dropHandler(e) {
  pageEditorPreview.contents().find('.oxymora-drop-marker').remove();
  var target = dropTarget;
  dropTarget = null;
  var pluginName = lastDraggedPlugin.data('name');
  pageEditor_page_settings(pluginName,null,function(success, settings){

    addPluginPreview(pluginName, [
      {
        "settingkey":"title",
        "settingvalue":"Das ist ein Test Title :D"
      },
      {
        "settingkey":"content",
        "settingvalue":"Das ist ein test junge<br>Zweizeilig :P"
      }
    ], target, function(success, errormsg){
      console.log(success);
      console.log(errormsg);
    });

  });
}

function pageEditor_iframe_dragleaveHandler(plugin, e) {
  pageEditorPreview.contents().find('.oxymora-drop-marker').remove();
}


// ----------------------
//  Area handler
// ----------------------

function pageEditor_iframe_area_dragenterHandler(area, e) {
  dropMarker(area);
}

function pageEditor_iframe_area_dragleaveHandler(area, e) {
  pageEditorPreview.contents().find('.oxymora-drop-marker').remove();
  dropTarget = null;
}




//  ============================================
//  PLUGIN FUNCTIONS
//  ============================================

function addPluginHandler(plugin){
  plugin.find('.oxymora-plugin-edit').on('click', pageEditor_iframe_plugin_editHandler);
  plugin.find('.oxymora-plugin-delete').on('click', pageEditor_iframe_plugin_deleteHandler);
  plugin.on('dragover', function(e){
    e.preventDefault()
  }).on('dragenter', function(e){
    e.preventDefault()
    pageEditor_iframe_plugin_dragenterHandler(plugin, e);
  });
}

function addPluginPreview(plugin, settings, target, callback){
  var data = {
    "a": "renderPluginPreview",
    "plugin": plugin,
    "settings": settings
  };
  $.ajax({
    dataType: "json",
    url: 'php/ajax_pageEditor.php',
    data: data,
    success: function(data){
      var plugin = $(data.data);
      addPluginHandler(plugin);
      if(target.hasClass('oxymora-area')){
        target.append(plugin);
        callback(true, null);
      }else if(target.hasClass('oxymora-plugin')){
        plugin.insertAfter(target);
        callback(true, null);
      }else{
        callback(false, "Invalid Target!");
      }

    },
    error: function(){
      callback(false, null);
    }
  });
}

function dropMarker(element){
  pageEditorPreview.contents().find('.oxymora-drop-marker').remove();
  dropTarget = $(element);
  dropTarget.append("<div class='oxymora-drop-marker'>insert here</div>");
}

function deletePlugin(plugin){
  plugin.data('action', 'deleted');
  plugin.css('display', 'none');
}
