let backbutton = $(".backbutton");
let title = $("header h1");
let backupUploadArea = $(".dropzone");
let backupUploadPassword = $(".backupPassword");
let backupInfos = $(".backupInfos");
let backupContinueButton = $(".backupContinueButton");
let backupData = null;

// =========================
// PAGES STUFF
// =========================
$('.link').on('click', function(){
  linkMgr.open('section[data-page='+$(this).data('url')+']');
});

backbutton.on('click', function(e){
  e.preventDefault();
  linkMgr.back();
});


// =========================
// BACKUP
// =========================
Dropzone.autoDiscover = false;
var dropzone = new Dropzone(backupUploadArea.get(0), { url: "php/index.php?action=uploadBackup", maxFiles:1 });
dropzone.on("dragenter", function() { backupUploadArea.addClass('dragover'); });
dropzone.on("dragleave", function() { backupUploadArea.removeClass('dragover'); });
dropzone.on("sending", function(file, xhr, formData){
  formData.append("password", backupUploadPassword.val());
});
dropzone.on("complete", function(file) {
  let response = file.xhr.response;
  if(file.status == "error"){
    dropzone.removeFile(file);
    alert('unknown error!');
  }else{
    let data = JSON.parse(response);
    if(data.error){
      dropzone.removeFile(file);
      alert(data.message);
    }else{
      // SHOW INFO FOR FURTHER STEPS
      data = data.message;
      backupData = data;
      dropzone.removeFile(file);
      let cancelButton = $('<button class="link backupContinueButton" type="button">Upload other Backup-Container</button>')
      cancelButton.on('click', function(){
        backupData = null;
        backupInfos.fadeOut(100, function(){
          backupUploadArea.fadeIn(100);
        });
      });

      backupInfos.html(`
        <table>
          <tr>
            <td>Created</td>
            <td>${(data.info) ? data.info.created : 'Unknown'}</td>
          </tr>
          <tr>
            <td>Config</td>
            <td>${data.hasConfig}</td>
          </tr>
          <tr>
            <td>Database Backup</td>
            <td>${data.hasDatabase}</td>
          </tr>
        </table>
      `);
      backupInfos.append(cancelButton);
      backupUploadPassword.fadeOut(100);
      backupUploadArea.fadeOut(100, function(){
        backupInfos.fadeIn(100);
      });
      backupContinueButton.removeAttr('disabled')
    }
  }
});

backupContinueButton.on('click', function(){
  if(!backupData) return;
  if(!backupData.hasConfig) linkMgr.open($('section[data-page=setup-backup-database]'));
  else linkMgr.open($('section[data-page=install-backup]'));
})

// =========================
// LINKMGR
// =========================
let linkMgr = {
  'history': [],

  'open': function(page, history = true){
    let currentPage = this.currentPage();
    if(currentPage){
      currentPage.fadeOut(400, function(){
        open(page, history);
      });
    }else{
      open(page, history)
    }

    function open(page, history){
      page = $(page);
      page.fadeIn(200);
      title.html(page.data('title'));
      if(history) linkMgr.history.push(page);
      if(!linkMgr.cangoback()) backbutton.css('display', 'none');
      else backbutton.css('display', 'block');
    }
  },

  'currentPage': function(){
    return (this.history.length > 0) ? this.history[this.history.length -1] : null;
  },

  'cangoback': function(){
    return (this.history.length > 1);
  },

  'back': function(){
    if(this.cangoback()){
      linkMgr.open(this.history[this.history.length -2], false);
      this.history.pop();
    }
  }

};



linkMgr.open('section[data-page=start]');
