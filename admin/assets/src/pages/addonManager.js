let addonManager = {
  url: "php/ajax_addonManager.php",
  dragObj: null,
  dragActive: false,

  downloadAddon(sender, addon){
    var html = '<iframe style="display:none;" src="php/downloadAddon.php?addon='+addon+'"></iframe>';
    $('body').append(html);
  },

  buttonHandler(sender, addon, action){
    if(!buttonManager.buttonActiv(sender, false)){return;}
    buttonManager.loading(sender);
    let buttonText,buttonEnable;
    switch (action) {
      case 'install':
      result = addonManager.installAddon(addon,function(data){console.log(data);
        if(data.error){
          notify(NOTIFY_ERROR, data.data);
          buttonText = "Install";
          sender.dataset.action = "install";
          buttonEnable = true;
        }else{
          buttonText = "Disable";
          sender.dataset.action = "disable";
          buttonEnable = true;
        }
        buttonManager.finished(sender,buttonText,buttonEnable);
      });
      break;
      case 'enable':
      result =  addonManager.enableAddon(addon);
      buttonText = "Disable";
      sender.dataset.action = "disable";
      buttonEnable = true;
      buttonManager.finished(sender,buttonText,buttonEnable);
      break;
      case 'disable':
      result =  addonManager.disableAddon(addon);
      buttonText = "Enable";
      buttonEnable = true;
      sender.dataset.action = "enable";
      buttonManager.finished(sender,buttonText,buttonEnable);
      break;
      default:
      result =  null;
      buttonManager.finished(sender,buttonText,buttonEnable);
    }

    return result;
  },

  installAddon(addon, cb){
    $.get(addonManager.url + "?a=install&addon="+addon, function(data){
      data = JSON.parse(data);
      addonMenu.loadMenuItems();
      if(cb) cb(data);
    });
  },

  enableAddon(addon){
    $.get(addonManager.url + "?a=enable&addon="+addon, function(data){
      data = JSON.parse(data);
      addonMenu.loadMenuItems();
    });
  },

  disableAddon(addon){
    $.get(addonManager.url + "?a=disable&addon="+addon, function(data){
      data = JSON.parse(data);
      addonMenu.loadMenuItems();
    });
  },

  dragUploadAddon(files){
    if($(addonManager.dragObj).hasClass('upload')){return;}
    $(addonManager.dragObj).addClass('upload');
    let ajaxData = new FormData();
    if(files){
      $.each(files, function(i, file) {
        ajaxData.append(i, file);
      });

      $.ajax({
        url: addonManager.url + "?a=upload",
        type: 'POST',
        data: ajaxData,
        dataType: 'json',
        cache: false,
        contentType: false,
        processData: false,
        complete: function() {
          $(addonManager.dragObj).removeClass('upload');
        },
        success: function(data) {
          $('#pageContainer').append(data.data);
          addonMenu.loadMenuItems();
          if(data.error){
            data.error.forEach(function(err, index){
              setTimeout(function(){notify(NOTIFY_ERROR, err);}, 1.5 * index);
            });
          }
        },
        error: function() {
          notify(NOTIFY_ERROR, 'Upload failed! Unknown error!');
        }
      });
    }else{
      $(addonManager.dragObj).removeClass('upload');
    }
  },

  fileDragInit(obj){
    obj.addEventListener("dragover", addonManager.fileDragHover, false);
    obj.addEventListener("dragleave", addonManager.fileDragHover, false);
    obj.addEventListener("drop", addonManager.fileSelectHandler, false);
    addonManager.dragObj = obj;
  },

  fileDragHover(e) {
    e.stopPropagation();
    e.preventDefault();
    if(e.type == "dragover" && $(addonManager.dragObj).hasClass('active') == false){
      addonManager.dragActive = true;
      $(addonManager.dragObj).addClass('active');
    }else if(e.type == "dragleave"){
      addonManager.dragActive = false;
      setTimeout(function(){
        if(addonManager.dragActive == false){
          $(addonManager.dragObj).removeClass('active');
        }
      }, 500);
    }
  },

  fileSelectHandler(e) {
    addonManager.fileDragHover(e);
    $(addonManager.dragObj).removeClass('active');
    var files = e.target.files || e.dataTransfer.files;
    for (var i = 0, f; f = files[i]; i++) {
      if(f.name.endsWith('.oxa') || f.name.endsWith('.zip')){
        addonManager.dragUploadAddon(files);
      }else{
        notify(NOTIFY_ERROR,'Please drop oxymora addons only!');
      }
    }
  }

}
