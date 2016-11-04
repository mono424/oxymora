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
    switch (action) {
      case 'install':
      result = addonManager.installAddon(addon);
      buttonText = "Disable";
      sender.dataset.action = "disable";
      buttonEnable = true;
      break;
      case 'enable':
      result =  addonManager.enableAddon(addon);
      buttonText = "Disable";
      sender.dataset.action = "disable";
      buttonEnable = true;
      break;
      case 'disable':
      result =  addonManager.disableAddon(addon);
      buttonText = "Enable";
      buttonEnable = true;
      sender.dataset.action = "enable";
      break;
      default:
      result =  null;
    }
    buttonManager.finished(sender,buttonText,buttonEnable);
    return result;
  },

  installAddon(addon){
    $.get(addonManager.url + "?a=install&addon="+addon, function(data){
      data = JSON.parse(data);
      addonMenu.loadMenuItems();
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
            data.error.forEach(function(err){
              alert(err);
            });
          }
        },
        error: function() {
          alert('Upload failed! Unknown error!');
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
        alert('Please drop oxymora addons only!');
      }
    }
  }

}
