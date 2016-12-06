let fileSelector = {

  init(input, ok, abort){
    (function(_input, _ok, _abort){
      let input = $(_input);
      if(input.hasClass('fileSelector')) return;
      input.addClass('fileSelector');
      let button = $('<button/>').text('Select a File').addClass('fileSelector');
      let div = $("<div/>").css('position', 'relative').html(button);
      input.after(div);
      button.on('click', function(){openDialog();});
      input.hide();

      let container = fileSelector._container();
      button.before(container);
      let backButton = container.find('.back').on('click', backButtonClick);
      let closeButton = container.find('.close').on('click', abortDialog);

      // FileManager Magic !! <3    // Even has Handlers holy fu*k!  by the way hearing Eminem - Stan atm ^^
      let fileManager = new FileManager(container);
      fileManager.onDirLoaded = function(){
        refreshBackButtonVisibility();
      }
      fileManager.onFileDblClick = function(file){
        okDialog(file);
      }


      function refreshBackButtonVisibility(){
        if(fileManager.path !== ""){
          let path = fileManager.path.split("/");
          path.pop();
          path = path.join("/");
          backButton[0].dataset.path = path;
          backButton.show();
        }else{
          backButton.hide();
        }
      }

      function backButtonClick(){
        fileManager.loadDir(backButton.data('path'));
      }

      function openDialog(element, input){
        container.fadeIn(200, function(){
          fileManager.init();
        });
      }

      function okDialog(file){
        let path = file.data('path');
        let splitted = path.split('/');
        let name = splitted[splitted.length -1];
        let res = {
          name,
          path
        }

        button.text(name).addClass('isSelected');
        input.val(path);
        closeDialog(function(){
          if(ok) ok(res);
        });
      }

      function abortDialog(){
        closeDialog(function(){
          if(abort) abort();
        });
      }

      function closeDialog(cb){
        container.fadeOut(400, function(){
          fileManager.loadDir("");
          if(cb) cb();
        });
      }

    })(input, ok, abort);
  },


  _container(width, height){
    // Mein Container
    let container = $('<div/>').addClass('fileSelectorBox');
    // Headbar
    let head = $('<header/>');
    head.append($('<button/>').html('<i class="fa fa-chevron-left" aria-hidden="true"></i>').addClass('back').addClass('customDir'));
    head.append($('<span/>').text('Select a file ...'));
    head.append($('<button/>').html('<i class="fa fa-times" aria-hidden="true"></i>').addClass('close'));
    // Searchbar
    let search = $('<div/>').addClass('search-container');
    search.append($('<input/>').attr('placeholder', 'Search'));
    // File-Container
    let subcontainer = $('<div/>').addClass('filesystem-container');

    let dircontainer = $('<div/>').addClass('dirs');
    let filecontainer = $('<div/>').addClass('files');

    subcontainer.append(dircontainer);
    subcontainer.append(filecontainer);


    // Add elements
    container.append(head);
    container.append(search);
    container.append(subcontainer);
    return container;
  },

  _folder(name){
    let folder = $('<div/>').addClass('dir').html('<i class="fa fa-folder" aria-hidden="true"></i><h3>'+name+"</h3>");
    return folder;
  },

  _file(name){
    let folder = $('<div/>').addClass('file').html(`
      <canvas class="preview"></canvas>
      <footer>
      <i class="fa fa-file-image-o" aria-hidden="true"></i>
      <h3>${name}</h3>
      </footer>
      `);
      return folder;
    }



  }
