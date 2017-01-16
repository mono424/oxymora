function FileManager(selector = "#fileManager"){
    let myself = this;
    this.element = null;
    this.url = 'php/ajax_fileManager.php',
    this.path =  null;
    this.lastSearch = null;
    this.searchDelay = 500;
    this.isMoveFile = false;

    //  ============================================
    //  EVENTS
    //  ============================================
    this.onDirLoaded = null;
    this.onFileDblClick = null;


    //  ============================================
    //  SETUP
    //  ============================================
    this.init = function(){
      myself.element = $(selector);
      myself.element.on('click', myself.fileMangerClickHandler);

      // HANDLER TRASH
      myself.element.on('dragstart', '.trash', myself.dir_dragStart);
      myself.element.on('dragenter', '.trash', myself.dir_dragEnter);
      myself.element.on('dragover', '.trash', myself.dir_dragOver);
      myself.element.on('dragleave', '.trash', myself.dir_dragLeave);
      myself.element.on('drop', '.trash', myself.dir_drop);

      // HANDLER DIR-CUSTOM-ANCHORS
      myself.element.on('dragstart', '.customDir', myself.dir_dragStart);
      myself.element.on('dragenter', '.customDir', myself.dir_dragEnter);
      myself.element.on('dragover', '.customDir', myself.dir_dragOver);
      myself.element.on('dragleave', '.customDir', myself.dir_dragLeave);
      myself.element.on('drop', '.customDir', myself.dir_drop);

      // HANDLER DIR-PATH-ANCHORS
      myself.element.on('dragstart', '.path a', myself.dir_dragStart);
      myself.element.on('dragenter', '.path a', myself.dir_dragEnter);
      myself.element.on('dragover', '.path a', myself.dir_dragOver);
      myself.element.on('dragleave', '.path a', myself.dir_dragLeave);
      myself.element.on('drop', '.path a', myself.dir_drop);

      // HANDLER FOR DIR-ITEM
      myself.element.on('click', '.dirs .dir', function() {
        myself.dirClickHandler(this);
      });

      myself.element.on('dblclick', '.dirs .dir', function() {
        myself.loadDir($(this).data('path'), "");
      });

      myself.element.on('dragenter', '.files', myself.files_dragEnter);
      myself.element.on('dragover', '.files', myself.files_dragOver);
      myself.element.on('dragleave', '.files', myself.files_dragLeave);
      myself.element.on('drop', '.files', myself.files_drop);

      myself.element.on('dragstart', '.dirs .dir', myself.dir_dragStart);
      myself.element.on('dragenter', '.dirs .dir', myself.dir_dragEnter);
      myself.element.on('dragover', '.dirs .dir', myself.dir_dragOver);
      myself.element.on('dragleave', '.dirs .dir', myself.dir_dragLeave);
      myself.element.on('dragend', '.dirs .dir', myself.dir_dragEnd);
      myself.element.on('drop', '.dirs .dir', myself.dir_drop);

      // HANDLER FOR FILE-ITEM
      myself.element.on('click', '.files .file', function() {
        myself.fileClickHandler(this);
      });

      myself.element.on('dblclick', '.files .file', function() {
        if(myself.onFileDblClick) myself.onFileDblClick($(this));
      });

      myself.element.on('dragstart', '.files .file', myself.file_dragStart);
      myself.element.on('dragend', '.files .file', myself.file_dragEnd);

      // HANDLER FOR PATH ANCHOR
      myself.element.on('click', '.path a', function(e) {
        e.preventDefault();
        myself.pathClickHandler(this);
      });

      // HANDLER FOR SEARCH BOX
      myself.element.find('.search input').on('input', function() {
        myself.searchChangeHandler(this);
      });

      // CONTEXT HANDLER
      let contextItems = [
        new ContextMenuItem('Open', function(){

        }),
        new ContextMenuItem('Rename', function(){


        }),
        new ContextMenuItem('Delete', function(){


        })
      ];
      let context = new ContextMenu(selector, contextItems, '.files .file');

      // load root folder
      myself.loadDir("", "", function(success, error){

      });
    };

    //  ============================================
    //  FUNCTIONS
    //  ============================================
    this.loadDir = function(dir, search, callback){
      let searchUrl = (search) ? ("&s="+encodeURIComponent(search)) : "";
      myself.element.find('.search input').val(search);
      $.ajax({
        dataType: "json",
        url: myself.url+"?a=index&dir="+encodeURIComponent(dir)+searchUrl,
        success: function(data){
          if(data.error){
            if(callback){callback(false, data.data);}
          }else{
            if(data.data.dirs.length > 0 || data.data.files.length > 0){
              myself.addDirsToDOM(data.data.dirs);
              myself.addFilesToDOM(data.data.files);
            }else{
              myself.addNothingMessageToDOM();
            }
            myself.createPathAnchors(dir);
            myself.path = dir;
            if(callback){callback(true, null);}
            if(myself.onDirLoaded){myself.onDirLoaded(true, null);}
          }
        },
        error: function(){
          if(callback){callback(false, null);}
          if(myself.onDirLoaded){onDirLoaded(false, null);}
        }
      });
    };

    this.addDirsToDOM = function(dirs){
      let el = myself.element.find('.dirs');
      el.html('');
      dirs.forEach(function(dir){
        el.append(myself.htmlDir(dir));
      });
    };
    this.addFilesToDOM = function(files){
      let el = myself.element.find('.files');
      el.html('');
      files.forEach(function(file){
        el.append(myself.htmlFile(file));
        myself.loadPreview(file.fullpath);
      });
    };
    this.addNothingMessageToDOM = function(){
      myself.element.find('.dirs').html('');
      myself.element.find('.files').html(myself.htmlNoFiles());
    };





    //  ============================================
    //  Selection System
    //  ============================================
    this.unselectAll = function(){
      myself.element.find('.files .file').each(function(){
        $(this).removeClass('active');
      });
      myself.element.find('.dirs .dir').each(function(){
        $(this).removeClass('active');
      });
    };
    this.selectItem = function(item){
      myself.unselectAll();
      $(item).addClass('active');
    };


    //  ============================================
    //  Handler
    //  ============================================
    this.fileMangerClickHandler = function(e){
      if(e.target.className == "dirs" || e.target.className == "files"){
        myself.unselectAll();
      }
    };

    this.dirClickHandler = function(me){
      myself.selectItem(me);
    };

    this.fileClickHandler = function(me){
      myself.selectItem(me);
    };

    this.pathClickHandler = function(me){
      myself.loadDir($(me).data('path'), "", function(success, error){});
    };

    this.searchChangeHandler = function(me){
      let search = $(me).val();
      myself.lastSearch = search;
      setTimeout(function(){
        if(myself.lastSearch != search){return;}
        myself.loadDir(myself.path, search, function(success, error){});
      }, myself.searchDelay);
    };



    //  ============================================
    //  Onclick Handler
    //  ============================================
    this.createPathAnchors = function(path){
      let folder = path.split('/');
      myself.pathAnchorsFromArray(folder);
    };

    this.pathAnchorsFromArray = function(arr){
      let ul = myself.element.find('.path ul');
      ul.html('<li><a data-path="" href="#">Meine Dateien</a></li>');
      let fullPath = "";
      arr.forEach(function(dir){
        if(dir==""){return;}
        fullPath = (!fullPath) ? dir : fullPath + "/" + dir;
        ul.append('><li><a data-path="'+fullPath+'" href="#">'+dir+'</a></li>');
      });
    };


    //  ============================================
    //  Drag and Drop Files-Container
    //  ============================================
    this.files_dragEnter = function(e){
      myself.element.find(this).addClass('dragover');
    };
    this.files_dragOver = function(e){
      if(myself.eventContainsFiles(e.originalEvent)){
        e.originalEvent.preventDefault();
        e.originalEvent.dataTransfer.dropEffect = 'copy';
      }
    };
    this.files_dragLeave = function(e){
      myself.element.find(this).removeClass('dragover');
    };
    this.files_drop = function(e){
      e.originalEvent.preventDefault();
      myself.element.find(this).removeClass('dragover');
      if(e.originalEvent.dataTransfer.files.length > 0){
        let folder = myself.path;
        var files = e.originalEvent.dataTransfer.files;
        for (var i = 0, f; f = files[i]; i++) {
          myself.uploadFile(f, folder);
        }
      }
    };






    //  ============================================
    //  Drag and Drop Dir
    //  ============================================
    this.dir_dragStart = function(e){
      myself.isMoveFile = true;
      myself.selectItem(this);
      e.originalEvent.dataTransfer.setDragImage(this, 0, 0);
      e.originalEvent.dataTransfer.setData("text/plain", this.dataset.path);
    };
    this.dir_dragEnter = function(e){
      if(($(this).data('role')!=='trash' && myself.eventContainsFiles(e.originalEvent)) || myself.isMoveFile){
        myself.element.find(this).addClass('dragover');
      }
    };
    this.dir_dragOver = function(e){
      if(($(this).data('role')!=='trash' && myself.eventContainsFiles(e.originalEvent)) || myself.isMoveFile){
        e.originalEvent.preventDefault();
        e.originalEvent.dataTransfer.dropEffect = 'copy';
      }
    };
    this.dir_dragLeave = function(e){
      myself.element.find(this).removeClass('dragover')
    };
    this.dir_drop = function(e){
      e.originalEvent.preventDefault();
      myself.element.find(this).removeClass('dragover')
      if($(this).data('role')!=='trash' && e.originalEvent.dataTransfer.files.length > 0){
        // Upload Files
        let folder = this.dataset.path;
        var files = e.originalEvent.dataTransfer.files;
        for (var i = 0, f; f = files[i]; i++) {
          myself.uploadFile(f, folder);
        }
      }else if(myself.isMoveFile){
        // Move File
        myself.isMoveFile = false;
        let data = e.originalEvent.dataTransfer.getData("text");
        var filename = data.split("/").pop(); // Only for LighboxQuestion
        let folder = this.dataset.path;
        if($(this).data('role')==='trash'){
          showLightbox(lightboxQuestion('Delete \''+filename+'\' ?!'), function(success){
            if(success) myself.trashFile(data);
          }, null, "Delete", "Cancel");
        }else{
          myself.moveFile(data, folder);
        }
      }
    };
    this.dir_dragEnd = function(e){
      myself.isMoveFile = false;
    };


    //  ============================================
    //  Drag and Drop File
    //  ============================================
    this.file_dragStart = function(e){
      myself.isMoveFile = true;
      e.originalEvent.dataTransfer.effectAllowed = "copyMove";
      e.originalEvent.dataTransfer.setData("text/plain", this.dataset.path);
      myself.selectItem(this);
      e.originalEvent.dataTransfer.setDragImage($(this).find('h3')[0], 0, 0);
    };
    this.file_dragEnd = function(e){
      myself.isMoveFile = false;
    };


    //  ============================================
    //  Drag and Drop Functions
    //  ============================================

    this.eventContainsFiles = function(e) {
      if (e.dataTransfer.types) {
        for (var i = 0; i < e.dataTransfer.types.length; i++) {
          if (e.dataTransfer.types[i] == "Files") {
            return true;
          }
        }
      }
      return false;
    };


    //  ============================================
    //  File/Folder Functions
    //  ============================================
    this.trashFile = function(file, callback){
      $.ajax({
        dataType: "json",
        url: myself.url+"?a=moveToTrash&file="+encodeURIComponent(file),
        success: function(data){console.log(data);
          if(data.error){
            if(callback){callback(false, data.data);}
          }else{
            myself.loadDir(myself.path, myself.lastSearch);
            if(callback){callback(true, null);}
          }
        },
        error: function(){
          if(callback){callback(false, null);}
        }
      });
    };

    this.moveFile = function(file, output, callback){
      $.ajax({
        dataType: "json",
        url: myself.url+"?a=move&file="+encodeURIComponent(file)+"&output="+encodeURIComponent(output),
        success: function(data){
          if(data.error){
            if(callback){callback(false, data.data);}
          }else{
            myself.loadDir(myself.path, myself.lastSearch);
            if(callback){callback(true, null);}
          }
        },
        error: function(){
          if(callback){callback(false, null);}
        }
      });
    };

    this.uploadFile = function(file, output){
      let ajaxData = new FormData();
      if(file){
        ajaxData.append('file', file);

        $.ajax({
          url: myself.url + "?a=uploadFiles&output="+encodeURIComponent(output),
          type: 'POST',
          data: ajaxData,
          dataType: 'json',
          cache: false,
          contentType: false,
          processData: false,
          complete: function() {
            // completed
          },
          success: function(data) {
            $('#pageContainer').append(data.data);
            myself.loadDir(myself.path);
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
      }
    };


    //  ============================================
    //  CANVAS PREVIEW
    //  ============================================
    this.loadPreview = function(path){
      var type = myself.getFiletype(path);
      if(type == "image"){
        myself.generateFilePreview(path, type);
        myself.loadImagePreview(path);
      }else{
        myself.generateFilePreview(path, type);
      }
    };

    this.generateFilePreview = function(path, type){
      let preview = myself.element.find('*[data-path="'+path+'"] .preview');
      // This two lines fixes the canvas :)
      preview[0].width = preview.width();
      preview[0].height = preview.height();

      let ctx = preview[0].getContext("2d");
      ctx.save();
      ctx.fillStyle = "rgba(241, 75, 59, 0.6)";
      myself.roundRect(ctx, 30, 50, preview.width() - 60, preview.height() - 100, 3, true, false);

      ctx.font="65px Arial";
      ctx.textAlign="center";
      ctx.fillStyle = 'white';
      ctx.fillText(type,preview.width() / 2, preview.height() / 2 + 25);
      ctx.restore();
    };

    this.loadImagePreview = function(path){
      let preview = myself.element.find('*[data-path="'+path+'"] .preview');

      var imageObj = new Image();
      imageObj.onload = function() {
        // This two lines fixes the canvas :)
        preview[0].width = preview.width();
        preview[0].height = preview.height();
        let ctx = preview[0].getContext("2d");
        let locX = (this.width - preview.width()) * -1 / 2;
        let locY = (this.height - preview.height()) * -1 / 2;

        ctx.drawImage(this, 0, 0, preview.width(), preview.height(), locX, locY, preview.width(), preview.height());
      };

      imageObj.src = myself.url+"?a=preview&file="+encodeURIComponent(path)+"&w="+preview.width()+"&h="+preview.height();
    };

    //  ============================================
    //  FILETYPES
    //  ============================================
    this.getFiletype = function(filename){
      let extension = filename.split('.').pop().toLowerCase();
      switch (extension) {
        case 'jpeg':
        case 'jpg':
        case 'png':
        case 'gif':
        case 'svg':
        case 'raw':
        return 'image';
        break;

        case 'wmv':
        case 'mpg':
        case 'mpeg':
        case 'mp4':
        case 'avi':
        case 'ogg':
        case 'ogv':
        case 'webm':
        return 'video';
        break;

        case 'wav':
        case 'aac':
        case 'mp3':
        case 'wma':
        case 'ogg':
        case 'oga':
        case 'flac':
        return 'audio';
        break;

        case 'zip':
        case 'rar':
        case '7zip':
        return 'archive';
        break;

        case 'pdf':
        return 'pdf';
        break;

        case 'csv':
        case 'xls':
        case 'xlsx':
        return 'excel';
        break;

        case 'doc':
        case 'docx':
        case 'xlsx':
        return 'word';
        break;

        case 'ppt':
        case 'pptx':
        return 'powerpoint';
        break;

        case 'txt':
        return 'text';
        break;

        case 'php':
        case 'js':
        case 'html':
        case 'css':
        case 'sql':
        return 'code';
        break;

        default:
        return 'unknown';
      }
    };
    this.getIcon = function(filetype){
      switch (filetype) {
        case 'image':
        return '<i class="fa fa-file-image-o" aria-hidden="true"></i>';
        break;

        case 'video':
        return '<i class="fa fa-file-video-o" aria-hidden="true"></i>';
        break;

        case 'audio':
        return '<i class="fa fa-file-audio-o" aria-hidden="true"></i>';
        break;

        case 'archive':
        return '<i class="fa fa-file-audio-o" aria-hidden="true"></i>';
        break;

        case 'pdf':
        return '<i class="fa fa-file-pdf-o" aria-hidden="true"></i>';
        break;

        case 'excel':
        return '<i class="fa fa-file-excel-o" aria-hidden="true"></i>';
        break;

        case 'word':
        return '<i class="fa fa-file-word-o" aria-hidden="true"></i>';
        break;

        case 'powerpoint':
        return '<i class="fa fa-file-powerpoint-o" aria-hidden="true"></i>';
        break;

        case 'text':
        return '<i class="fa fa-file-text-o" aria-hidden="true"></i>';
        break;

        case 'code':
        return '<i class="fa fa-file-code-o" aria-hidden="true"></i>';
        break;

        default:
        return '<i class="fa fa-file-o" aria-hidden="true"></i>';
      }
    };



    //  ============================================
    //  HTML MARKUP
    //  ============================================
    this.htmlDir = function(dir){
      let html = '<div draggable="true" data-path="'+dir.fullpath+'" class="dir"><i class="fa fa-folder" aria-hidden="true"></i></i><h3>'+dir.filename+'</h3></div>';
      return html;
    };
    this.htmlFile = function(file){
      let filetype = myself.getFiletype(file.filename);
      let icon = myself.getIcon(filetype);
      let html = '<div draggable="true" data-path="'+file.fullpath+'" class="file"><canvas class="preview"></canvas><h3>'+icon+' '+file.filename+'</h3></div>';
      return html;
    };
    this.htmlNoFiles = function(){
      let html = '<h3>No files uploaded yet.</h3>';
      return html;
    };


    //  ============================================
    //  ROUNDED RECTANGLE
    //  ============================================
    // NOTICE: FROM http://stackoverflow.com/questions/1255512/how-to-draw-a-rounded-rectangle-on-html-canvas

    this.roundRect = function(ctx, x, y, width, height, radius, fill, stroke) {
      if (typeof stroke == 'undefined') {
        stroke = true;
      }
      if (typeof radius === 'undefined') {
        radius = 5;
      }
      if (typeof radius === 'number') {
        radius = {tl: radius, tr: radius, br: radius, bl: radius};
      } else {
        var defaultRadius = {tl: 0, tr: 0, br: 0, bl: 0};
        for (var side in defaultRadius) {
          radius[side] = radius[side] || defaultRadius[side];
        }
      }
      ctx.beginPath();
      ctx.moveTo(x + radius.tl, y);
      ctx.lineTo(x + width - radius.tr, y);
      ctx.quadraticCurveTo(x + width, y, x + width, y + radius.tr);
      ctx.lineTo(x + width, y + height - radius.br);
      ctx.quadraticCurveTo(x + width, y + height, x + width - radius.br, y + height);
      ctx.lineTo(x + radius.bl, y + height);
      ctx.quadraticCurveTo(x, y + height, x, y + height - radius.bl);
      ctx.lineTo(x, y + radius.tl);
      ctx.quadraticCurveTo(x, y, x + radius.tl, y);
      ctx.closePath();
      if (fill) {
        ctx.fill();
      }
      if (stroke) {
        ctx.stroke();
      }
    };

  };
