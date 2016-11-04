let memberEditor = {

  //  ============================================
  //  SETUP
  //  ============================================
  init(){
    pageEditorPreview = $("#pageEditorPreview");
    pageEditorPreview.on('load', function(){
      pageEditor.findElements();
      pageEditor.addIframeHandler();
      pageEditor.page_plugins();
    });
  },

  //  ============================================
  //  PageEditor Save
  //  ============================================

  save(callback){
    pageEditor.findIframeElements();

    var plugins = [];
    $(pageEditorPlugins).each(function(){
      var pluginInfo = {}
      pluginInfo['id'] = $(this).data('id');
      pluginInfo['plugin'] = $(this).data('plugin');
      pluginInfo['area'] = pageEditor.getPluginArea(this);
      pluginInfo['settings'] = pageEditor.getPluginSettings(this);
      plugins.push(pluginInfo);
    });console.log(plugins);

    var data = {
      "a": "save",
      "url": pageEditor.getUrl(),
      "plugins":plugins
    };

    $.ajax({
      dataType: "json",
      url: 'php/ajax_pageEditor.php',
      data: data,
      success: function(data){
        if(data.error){
          callback(false, data.data);
        }else{
          callback(true, null);
        }
      },
      error: function(){
        callback(false, null);
      }
    });
  },

  //  ============================================
  //  SIDEPAGE
  //  ============================================

  page_settings(plugin, pluginid, callback, settings){
    var currSettings = (settings == null) ? [] : settings;
    pageEditorSidePage.animate({'opacity':0}, 500, function(){
      var html = "";
      $.getJSON("php/ajax_pageEditor.php?a=pluginSettings&plugin="+encodeURIComponent(plugin)+"&id="+encodeURIComponent(pluginid), function(data){console.log(data);
        if(data.error == false){

          // Add all the Settings Input fields and handle if there are no settings
          if(data.data != null && data.data.length > 0){
            data.data.forEach(function(setting){
              var value = pageEditor.getSettingsValue(currSettings,setting.key);
              html += pageEditor.addSettingInput(setting,value);
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
          callback(true, pageEditor.getSettingData());
        });
        pageEditorSidePage.find('.settings-cancel').on('click', function(){
          callback(false, null);
        });

        pageEditorSidePage.animate({'opacity':1}, 500,function(){
          // loaded
        });
      });
    });
  },

  page_plugins(){
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
        pageEditor.addMenuPluginHandler();
        pageEditorSidePage.animate({'opacity':1}, 500,function(){
          // loaded
        });
      });
    });
  },

  addSettingInput(setting, value){
    value = (value == null) ? "" : value;
    var html = '<div class="setting" data-key="'+setting.key+'" data-type="'+setting.type+'">';
    html += '<h2 class="oxlabel">'+setting.displayname+'</h2>';
    html += '<p class="oxdescription">'+setting.description+'</p>';
    value = $("<div>").text(value).html();
    value = value.replace(/["']/g, "&quot;");
    switch(setting.type) {
      case 'textarea':
      // escape value
      html += '<textarea class="settingbox oxinput">'+value+'</textarea>';
      break;
      case 'text':
      default:
      // escape value
      html += '<input class="settingbox oxinput" type="text" value="'+value+'"></input>';
    }
    html += "</div>";
    return html;
  },

  getSettingData(){
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
  },


  findElements(){
    // PREVIEW IFRAME STUFF
    pageEditor.findIframeElements();
    // LIGHTBOX STUFF
    pageEditorSidePage = lightboxDialog.contents().find('.menu');
  },

  findIframeElements(){
    pageEditorAreas = pageEditorPreview.contents().find('.oxymora-area');
    pageEditorPlugins = pageEditorPreview.contents().find('.oxymora-plugin');
  },


  //  ============================================
  //  HANDLER
  //  ============================================
  lastDraggedPlugin: null,

  addMenuPluginHandler(){
    pageEditorSidePage.find('.plugin').on('dragstart', pageEditor.menu_plugin_dragstartHandler);
    pageEditorSidePage.find('.plugin').on('dragend', pageEditor.menu_plugin_dragendHandler);
  },

  menu_plugin_dragstartHandler(){
    pageEditor.lastDraggedPlugin = $(this);
    $(this).css("border-color","rgb(255, 0, 168)");
    $(this).find('.name').css("color","rgb(255, 140, 240)");
  },

  menu_plugin_dragendHandler(){
    $(this).css("border-color","rgb(11, 118, 224)");
    $(this).find('.name').css("color","white");
  },


  //  ============================================
  //  IFRAME HANDLER
  //  ============================================
  dropTarget: null,
  addIframeHandler(){
    // IFrame Handler
    pageEditorPreview.contents().find('html').on('drop', pageEditor.iframe_dropHandler);

    // Area Handler
    pageEditorAreas.each(function(){
      $(this).on('dragleave', function(e){
        e.preventDefault()
        if(e.target === this){
          pageEditor.iframe_area_dragleaveHandler(this, e);
        }
      }).on('dragover', function(e){
        e.preventDefault()
      }).on('dragenter', function(e){
        e.preventDefault()
        if(e.target === this){
          pageEditor.iframe_area_dragenterHandler(this, e);
        }
      });
    });

    // Plugin Handler
    pageEditorPlugins.each(function(){
      pageEditor.addPluginHandler($(this));
    });
  },

  // ----------------------
  //  Plugin Handler
  // ----------------------
  iframe_plugin_editHandler(){
    // todo: plugin edit handler
    var plugin = $(this).parent().parent();
    var pluginId = plugin.data('id');
    var pluginName = plugin.data('plugin');
    var settings = pageEditor.getPluginSettings(plugin);
    pageEditor.page_settings(pluginName, pluginId, function(success, settings){
      pageEditor.addPluginPreview(pluginName, pluginId, settings, plugin, function(){
        plugin.remove();
        pageEditor.page_plugins();
      });
    }, settings);
  },

  iframe_plugin_deleteHandler(){
    // todo: nicer Confirm..
    if(confirm("Sure you want to delete?")){
      pageEditor.deletePlugin($(this).parent().parent());
    }
  },

  iframe_plugin_dragenterHandler(plugin, e){
    pageEditor.dropMarker(plugin);
  },

  // ----------------------
  //  Iframe "html" handler
  // ----------------------
  iframe_dropHandler(e) {
      pageEditorPreview.contents().find('.oxymora-drop-marker').remove();
      var target = pageEditor.dropTarget;
      pageEditor.dropTarget = null;
      var pluginName = pageEditor.lastDraggedPlugin.data('name');

      // Show Settings Page and wait for Callback
      pageEditor.page_settings(pluginName,null,function(success, settings){console.log("Add Plugin Settings:"+settings);
      //  If success add the Preview Plugin, if not just back to plugin page
      if(success){
        pageEditor.addPluginPreview(pluginName, "", settings, target, function(success, errormsg){
          console.log("Add Plugin Success:" + success);
          console.log("Add Plugin Error:" + errormsg);
          pageEditor.page_plugins();
        });
      }else{
        pageEditor.page_plugins();
      }
    }
  );
  },

  iframe_dragleaveHandler(plugin, e) {
    pageEditorPreview.contents().find('.oxymora-drop-marker').remove();
  },

  // ----------------------
  //  Area handler
  // ----------------------
  iframe_area_dragenterHandler(area, e) {
    pageEditor.dropMarker(area, true);
  },

  iframe_area_dragleaveHandler(area, e) {
    pageEditorPreview.contents().find('.oxymora-drop-marker').remove();
    pageEditor.dropTarget = null;
  },


  //  ============================================
  //  PLUGIN FUNCTIONS
  //  ============================================
  getPluginSettings(plugin){
    return $(plugin).data('settings');
  },

  getPluginArea(plugin){
    return $(plugin).parent().data('name');
  },

  getSettingsValue(settings, key){
    var returnValue = null;
    settings.forEach(function(element, index){
      if(element.settingkey === key){
        returnValue = element.settingvalue;
        // there is no break option, wtf !??
      }
    });
    return returnValue;
  },

  addPluginHandler(plugin){
    plugin.find('.oxymora-plugin-edit').on('click', pageEditor.iframe_plugin_editHandler);
    plugin.find('.oxymora-plugin-delete').on('click', pageEditor.iframe_plugin_deleteHandler);
    plugin.on('dragover', function(e){
      e.preventDefault()
    }).on('dragenter', function(e){
      e.preventDefault()
      pageEditor.iframe_plugin_dragenterHandler(plugin, e);
    });
  },

  addPluginPreview(plugin, id, settings, target, callback){
    var data = {
      "a": "renderPluginPreview",
      "id": id,
      "plugin": plugin,
      "settings": settings
    };
    $.ajax({
      dataType: "json",
      url: 'php/ajax_pageEditor.php',
      data: data,
      success: function(data){
        var plugin = $(data.data);
        pageEditor.addPluginHandler(plugin);
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
  },

  dropMarker(element, prepend){
    pageEditorPreview.contents().find('.oxymora-drop-marker').remove();
    pageEditor.dropTarget = $(element);
    html = "<div class='oxymora-drop-marker'>insert here</div>";
    if(prepend != null && prepend != false){
      pageEditor.dropTarget.prepend(html);
    }else{
      pageEditor.dropTarget.append(html);
    }
  },

  deletePlugin(plugin){
    plugin.data('action', 'deleted');
    plugin.css('display', 'none');
  },


  //  ============================================
  //  FUNCTIONS
  //  ============================================

  getUrl(){
    return $("#pageEditorPreview").data('url');
  }

}
