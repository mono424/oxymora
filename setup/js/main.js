let backbutton = $(".backbutton");
let header = $('.wrapper header')
let title = $("header h1");
let backupUploadArea = $(".dropzone");
let backupUploadPassword = $(".backupPassword");
let backupInfos = $(".backupInfos");
let backupContinueButton = $(".backupContinueButton");
let useBackupConfigCheckbox = $("#useBackupConfig");
let backupConfigOverwrite = $(".backupConfigOverwrite");
let setupDBForm = $('#setup_db');
let setupAccountForm = $('#setup_account');
let templateSelect = $('#template');
let backupData = null;

// =========================
// PAGES STUFF
// =========================
$('.link').on('click', function(){
  let sender = $(this);
  sender.attr('disabled', 'disabled');
  if(sender.data('condition')){
    Conditions.run(sender.data('condition'),function(){
      $('.error').fadeOut(400, function(){$('.error').remove();});
      linkMgr.open('section[data-page='+sender.data('url')+']');
      sender.removeAttr('disabled');
    }, function(error){
      displayError(error);
      sender.removeAttr('disabled');
    });
  }else{
    linkMgr.open('section[data-page='+sender.data('url')+']');
    sender.removeAttr('disabled');
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
  window.scrollTo(0, 0);
}


// =========================
// TEMPLATE
// =========================

templateSelect.on('change', function(e){
  setTemplate()
});

function setTemplate(){
  let option = templateSelect.find('option[value="'+templateSelect.val()+'"]');
  $('.template-info .thumb').attr('src', option.data('thumb'));
  $('.template-info .version').text(option.data('version'));
  $('.template-info .developer').text(option.data('developer'));
  $('.template-info .website').html($('<a>').attr('target', '_blank').attr('href', option.data('website')).text(option.data('website')));
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
  if(!setupDBForm.find('input[name=host]').val().match(/.+/)){
    err('Please set your host!');
    return;
  }
  if(!setupDBForm.find('input[name=user]').val().match(/.+/)){
    err('Please set your user!');
    return;
  }
  if(!setupDBForm.find('input[name=db]').val().match(/.+/)){
    err('Please set your Database!');
    return;
  }
  $.post('php/index.php?action=checkDB', setupDBForm.serialize(), function(res){
    try {
      res = JSON.parse(res);
    }catch(exception){
      err('Unknown Error');
      return;
    }
    if(res.error) err(res.message);
    else succ(res.message);
  }).fail(function() {
    err('Unknown Error');
  });
});

Conditions.push('setupAccountCheck', function(succ, err){
  if(setupAccountForm.find('input[name=pass]').val() != setupAccountForm.find('input[name=cpass]').val()){
    err('Password does not match with the confirm password!');
    return;
  }
  if(!setupAccountForm.find('input[name=user]').val().match(/[a-zA-Z0-9\_]{3,}/)){
    err('Your username is too short(min 3 chars) or contain illigal charackters.');
    return;
  }
  if(!setupAccountForm.find('input[name=pass]').val().match(/.{3,}/)){
    err('Your Password is too short(min 3 chars).');
    return;
  }
  succ();
});

Conditions.push('backupDatabaseCheck', function(succ, err){
  if(useBackupConfigCheckbox.get(0).checked){
    $.post('php/index.php?action=checkBackupDB', function(res){
      try {
        res = JSON.parse(res);
      }catch(exception){
        err('Unknown Error');
        return;
      }
      if(res.error) err(res.message);
      else succ(res.message);
    }).fail(function() {
      err('Unknown Error');
    });
  }else{
    if(!backupConfigOverwrite.find('input[name=host]').val().match(/.+/)){
      err('Please set your host!');
      return;
    }
    if(!backupConfigOverwrite.find('input[name=user]').val().match(/.+/)){
      err('Please set your user!');
      return;
    }
    if(!backupConfigOverwrite.find('input[name=db]').val().match(/.+/)){
      err('Please set your Database!');
      return;
    }
    $.post('php/index.php?action=checkDB', backupConfigOverwrite.serialize(), function(res){
      try {
        res = JSON.parse(res);
      }catch(exception){
        err('Unknown Error');
        return;
      }
      if(res.error) err(res.message);
      else succ(res.message);
    }).fail(function() {
      err('Unknown Error');
    });
  }
});

Conditions.push('backupInstall', function(succ, err){
  setBackupInstallStatus('createConfig', '');
  setBackupInstallStatus('setupDB', '');
  setBackupInstallStatus('installAddons', '');
  setBackupInstallStatus('restoreBackup', '');
  let backupConfig = useBackupConfigCheckbox.get(0).checked ? "1" : "0";

  // Create Config
  if(backupConfig){
    setBackupInstallStatus('createConfig', 'running');
    $.post('php/index.php?action=restore&step=createConfig', backupConfigOverwrite.serialize(), function(res){
      try {
        res = JSON.parse(res);
      }catch(exception){
        setBackupInstallStatus('createConfig', 'failed');
        err('Unknown Error');
        return;
      }
      if(res.error){
        setBackupInstallStatus('createConfig', 'failed');
        err(res.message);
        return;
      }
      setBackupInstallStatus('createConfig', 'success');

      // SUCCESS
      doSetupDB();

    }).fail(function() {
      setBackupInstallStatus('createConfig', 'failed');
      err('Unknown Error');
    });
  }else{
    setBackupInstallStatus('createConfig', 'running');
    $.post('php/index.php?action=restore&step=createConfig', {backup:1}, function(res){
      try {
        res = JSON.parse(res);
      }catch(exception){
        setBackupInstallStatus('createConfig', 'failed');
        err('Unknown Error');
        return;
      }
      if(res.error){
        setBackupInstallStatus('createConfig', 'failed');
        err(res.message);
        return;
      }
      setBackupInstallStatus('createConfig', 'success');

      // SUCCESS
      doSetupDB();

    }).fail(function() {
      setBackupInstallStatus('createConfig', 'failed');
      err('Unknown Error');
    });
  }

  function doSetupDB(){
    setBackupInstallStatus('setupDB', 'running');
    $.post('php/index.php?action=restore&step=setupDB', function(res){
      try {
        res = JSON.parse(res);
      }catch(exception){
        setBackupInstallStatus('setupDB', 'failed');
        err('Unknown Error');
        return;
      }
      if(res.error){
        setBackupInstallStatus('setupDB', 'failed');
        err(res.message);
        return;
      }
      setBackupInstallStatus('setupDB', 'success');

      // SUCCESS
      doInstallAddons();

    }).fail(function() {
      setBackupInstallStatus('setupDB', 'failed');
      err('Unknown Error');
    });
  }


  function doInstallAddons(){
    setBackupInstallStatus('installAddons', 'running');
    $.post('php/index.php?action=restore&step=installAddons', function(res){
      try {
        res = JSON.parse(res);
      }catch(exception){
        setBackupInstallStatus('installAddons', 'failed');
        err('Unknown Error');
        return;
      }
      if(res.error){
        setBackupInstallStatus('installAddons', 'failed');
        err(res.message);
        return;
      }
      setBackupInstallStatus('installAddons', 'success');

      // SUCCESS
      doRestore();

    }).fail(function() {
      setBackupInstallStatus('installAddons', 'failed');
      err('Unknown Error');
    });
  }


  function doRestore(){
    setBackupInstallStatus('restoreBackup', 'running');
    $.post('php/index.php?action=restore&step=restoreBackup', {backupConfig}, function(res){
      try {
        res = JSON.parse(res);
      }catch(exception){
        setBackupInstallStatus('restoreBackup', 'failed');
        err('Unknown Error');
        return;
      }
      if(res.error){
        setBackupInstallStatus('restoreBackup', 'failed');
        err(res.message);
        return;
      }
      setBackupInstallStatus('restoreBackup', 'success');

      // SUCCESS
      succ();

    }).fail(function() {
      setBackupInstallStatus('restoreBackup', 'failed');
      err('Unknown Error');
    });
  }


});


Conditions.push('setupInstall', function(succ, err){
  setSetupInstallStatus('createConfig', '');
  setSetupInstallStatus('setupDB', '');
  setSetupInstallStatus('registerPermissions', '');
  setSetupInstallStatus('registerUser', '');
  setSetupInstallStatus('installAddons', '');

  // CREATE CONFIG
  setSetupInstallStatus('createConfig', '');
  var postdata = setupDBForm.serializeArray();
  postdata.push({name: "template", value: templateSelect.val()});
  $.post('php/index.php?action=setup&step=createConfig', postdata, function(res){
    try {
      res = JSON.parse(res);
    }catch(exception){
      setSetupInstallStatus('createConfig', 'failed');
      err('Unknown Error');
      return;
    }
    if(res.error){
      setSetupInstallStatus('createConfig', 'failed');
      err(res.message);
      return;
    }
    setSetupInstallStatus('createConfig', 'success');


    // SETUP DB
    setSetupInstallStatus('setupDB', 'running');
    $.post('php/index.php?action=setup&step=setupDB', function(res){
      try {
        res = JSON.parse(res);
      }catch(exception){
        setSetupInstallStatus('setupDB', 'failed');
        err('Unknown Error');
        return;
      }
      if(res.error){
        setSetupInstallStatus('setupDB', 'failed');
        err(res.message);
        return;
      }
      setSetupInstallStatus('setupDB', 'success');



      // REGISTER PERMISSIONS
      setSetupInstallStatus('registerPermissions', 'running');
      $.post('php/index.php?action=setup&step=registerPermissions', function(res){
        try {
          res = JSON.parse(res);
        }catch(exception){
          setSetupInstallStatus('registerPermissions', 'failed');
          err('Unknown Error');
          return;
        }
        if(res.error){
          setSetupInstallStatus('registerPermissions', 'failed');
          err(res.message);
          return;
        }
        setSetupInstallStatus('registerPermissions', 'success');




        // REGISTER USER
        setSetupInstallStatus('registerUser', 'running');
        $.post('php/index.php?action=setup&step=registerUser', setupAccountForm.serialize(), function(res){
          try {
            res = JSON.parse(res);
          }catch(exception){
            setSetupInstallStatus('registerUser', 'failed');
            err('Unknown Error');
            return;
          }
          if(res.error){
            setSetupInstallStatus('registerUser', 'failed');
            err(res.message);
            return;
          }
          setSetupInstallStatus('registerUser', 'success');




          // INSTALL ADDONS
          setSetupInstallStatus('installAddons', 'running');
          $.post('php/index.php?action=setup&step=installAddons', setupAccountForm.serialize(), function(res){
            try {
              res = JSON.parse(res);
            }catch(exception){
              setSetupInstallStatus('installAddons', 'failed');
              err('Unknown Error');
              return;
            }
            if(res.error){
              setSetupInstallStatus('installAddons', 'failed');
              err(res.message);
              return;
            }
            setSetupInstallStatus('installAddons', 'success');

            // SUCCESS
            succ();

          }).fail(function() {
            setSetupInstallStatus('installAddons', 'failed');
            err('Unknown Error');
          });

        }).fail(function() {
          setSetupInstallStatus('registerUser', 'failed');
          err('Unknown Error');
        });

      }).fail(function() {
        setSetupInstallStatus('registerPermissions', 'failed');
        err('Unknown Error');
      });

    }).fail(function() {
      setSetupInstallStatus('setupDB', 'failed');
      err('Unknown Error');
    });

  }).fail(function() {
    setSetupInstallStatus('createConfig', 'failed');
    err('Unknown Error');
  });
});

function setSetupInstallStatus(step, state){
  $('#setup_indicator_'+step).get(0).className = state;
}
function setBackupInstallStatus(step, state){
  $('#backup_indicator_'+step).get(0).className = state;
}



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
    displayError('unknown error!');
  }else{
    let data = JSON.parse(response);
    if(data.error){
      dropzone.removeFile(file);
      displayError(data.message);
    }else{
      // SHOW INFO FOR FURTHER STEPS
      data = data.message;
      backupData = data;
      dropzone.removeFile(file);
      let cancelButton = $('<button type="button">Upload other Backup</button>')
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
    let page = $('section[data-page=backup-database]');
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
  setTemplate();
