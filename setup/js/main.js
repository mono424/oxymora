let backbutton = $(".backbutton");
let header = $('.wrapper header')
let title = $("header h1");
let backupUploadArea = $(".dropzone");
let backupUploadPassword = $(".backupPassword");
let backupInfos = $(".backupInfos");
let backupContinueButton = $(".backupContinueButton");
let useBackupConfigCheckbox = $("#useBackupConfig");
let backupConfigOverwrite = $(".backupConfigOverwrite");
let backupData = null;

// =========================
// PAGES STUFF
// =========================
$('.link').on('click', function(){
  let sender = $(this);
  if(sender.data('condition')){
    Conditions.run(sender.data('condition'),function(){
      $('.error').fadeOut(400, function(){$('.error').remove();});
      linkMgr.open('section[data-page='+sender.data('url')+']');
    }, function(error){
      displayError(error);
    });
  }else{
    linkMgr.open('section[data-page='+sender.data('url')+']');
  }
});

backbutton.on('click', function(e){
  e.preventDefault();
  linkMgr.back();
});


// =========================
// ERROR
// =========================

function displayError(msg){
  let error = $(`<div class="error">${msg}</div>`);
  error.on('click', function(){let me = $(this);me.fadeOut(400,function(){me.remove();});});
  header.after(error);
}


// =========================
// CONDITIONS
// =========================

var Conditions = {
  'conds':{},
  'run':function(cond, succ, err){
    this.conds[cond](succ,err);
  },
  'push':function(key, func){
    this.conds[key] = func;
  }
};

Conditions.push('setupDatabaseCheck', function(succ, err){
  let form = $('#setup_db');
  $.post('php/index.php?action=checkDB', form.serialize(), function(res){
    res = JSON.parse(res);
    if(res.error) err(res.message);
    else succ(res.message);
  }).fail(function() {
    err('Unknown Error');
  });
});

Conditions.push('setupAccountCheck', function(succ, err){
  let form = $('#setup_account');
  if(form.find('input[name=pass]').val() != form.find('input[name=cpass]').val()){
    err('Password does not match with the confirm password!');
    return;
  }
  if(!form.find('input[name=user]').val().match(/[a-zA-Z0-9\_]{3,}/)){
    err('Your username is too short(min 3 chars) or contain illigal charackters.');
    return;
  }
  if(!form.find('input[name=pass]').val().match(/.{3,}/)){
    err('Your Password is too short(min 3 chars).');
    return;
  }
  succ();
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
          backupUploadPassword.fadeIn(100);
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
    let page = $('section[data-page=setup-backup-database]');
    if(!backupData) return;
    if(!backupData.hasConfig){
      useBackupConfigCheckbox.removeAttr('checked');
      useBackupConfigCheckbox.attr('disabled', 'disabled');
    }else{
      useBackupConfigCheckbox.removeAttr('disabled');
      useBackupConfigCheckbox.attr('checked', 'checked');
    }
    useBackupConfigCheckbox.trigger('change');
    linkMgr.open(page);
  });

  useBackupConfigCheckbox.on('change', function(){
    if(useBackupConfigCheckbox.get(0).checked){
      backupConfigOverwrite.find('input').each(function(){
        $(this).attr('disabled', 'disabled');
      });
    }else{
      backupConfigOverwrite.find('input').each(function(){
        $(this).removeAttr('disabled');
      });
    }
  });


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
