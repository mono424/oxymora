
//  ============================================
//  SIDEPAGE
//  ============================================

function pageEditor_page_settings(plugin, pluginid, callback){
  pageEditorSidePage.animate({'opacity':0}, 500, function(){
    var html = "";
    $.getJSON("php/ajax_pageEditor.php?a=pluginSettings&plugin="+encodeURIComponent(plugin)+"&id="+encodeURIComponent(pluginid), function(data){console.log(data);
      if(data.error == false){

        // Add all the Settings Input fields and handle if there are no settings
        if(data.data != null && data.data.length > 0){
          data.data.forEach(function(setting){
            html += addSettingInput(setting);
          });
        }else{
          callback(true, []);
          return;
        }

        // Create Submit and Cancel Button
        html += '<button class="oxbutton settings-save">Save</button>';
        html += '<button class="oxbutton settings-cancel">Cancel</button>';

      }

      //  ADD HTML
      pageEditorSidePage.html(html);

      // ADD HANDLER
      pageEditorSidePage.find('.settings-save').on('click', function(){console.log(123);
        callback(true, getSettingData());
      });
      pageEditorSidePage.find('.settings-cancel').on('click', function(){
        callback(false, null);
      });

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

        // list all plugins
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

function addSettingInput(setting){
  var html = '<div class="setting" data-key="'+setting.key+'" data-type="'+setting.type+'">';
  html += '<h2 class="oxlabel">'+setting.displayname+'</h2>';
  html += '<p class="oxdescription">'+setting.description+'</p>';
  switch(setting.type) {
    case 'textarea':
    html += '<textarea class="settingbox oxinput"></textarea>';
    break;
    case 'text':
    default:
    html += '<input class="settingbox oxinput" type="text"></input>';
  }
  html += "</div>";
  return html;
}

function getSettingData(){
  var settings = [];
  pageEditorSidePage.find('.setting').each(function(index){
    setting = $(this);
    var keyValueObject = {
      "settingkey":setting.data('key'),
      "settingvalue":null
    };
    switch(setting.data('type')) {
      case 'textarea':
      keyValueObject.settingvalue = setting.find('.settingbox').html();
      case 'text':
      default:
      keyValueObject.settingvalue = setting.find('.settingbox').val();
    }
    settings.push(keyValueObject);
  });
  return settings;
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
  var plugin = $(this).parent().parent();
  var pluginId = plugin.data('id');
  var pluginName = plugin.data('plugin');
  pageEditor_page_settings(pluginName, pluginId, function(success, settings){
      pageEditor_page_plugins();
      console.log(settings);
  });
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

  // Show Settings Page and wait for Callback
  pageEditor_page_settings(pluginName,null,function(success, settings){console.log("Add Plugin Settings:"+settings);
    //  If success add the Preview Plugin, if not just back to plugin page
    if(success){
      addPluginPreview(pluginName, settings, target, function(success, errormsg){
        console.log("Add Plugin Success:" + success);
        console.log("Add Plugin Error:" + errormsg);
        pageEditor_page_plugins();
      });
    }else{
      pageEditor_page_plugins();
    }
  });
}

function pageEditor_iframe_dragleaveHandler(plugin, e) {
  pageEditorPreview.contents().find('.oxymora-drop-marker').remove();
}


// ----------------------
//  Area handler
// ----------------------

function pageEditor_iframe_area_dragenterHandler(area, e) {
  dropMarker(area, true);
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
        target.prepend(plugin);
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

function dropMarker(element, prepend){
  pageEditorPreview.contents().find('.oxymora-drop-marker').remove();
  dropTarget = $(element);
  html = "<div class='oxymora-drop-marker'>insert here</div>";
  if(prepend != null && prepend != false){
    dropTarget.prepend(html);
  }else{
    dropTarget.append(html);
  }
}

function deletePlugin(plugin){
  plugin.data('action', 'deleted');
  plugin.css('display', 'none');
}
