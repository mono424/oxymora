let fileManager = {
  'element': null,
  'url': 'php/ajax_fileManager.php',
  'path': null,
  'lastSearch':null,
  'searchDelay':500,

  //  ============================================
  //  SETUP
  //  ============================================
  init(){
    fileManager.element = $("#fileManager");
    fileManager.element.on('click', fileManager.fileMangerClickHandler);

    // HANDLER DIR-PATH-ANCHORS
    fileManager.element.on('dragstart', '.path a', fileManager.dirAnchor_dragStart);
    fileManager.element.on('dragenter', '.path a', fileManager.dirAnchor_dragEnter);
    fileManager.element.on('dragover', '.path a', fileManager.dirAnchor_dragOver);
    fileManager.element.on('dragleave', '.path a', fileManager.dirAnchor_dragLeave);
    fileManager.element.on('drop', '.path a', fileManager.dirAnchor_drop);

    // HANDLER FOR DIR-ITEM
    fileManager.element.on('click', '.dirs .dir', function() {
      fileManager.dirClickHandler(this);
    });

    fileManager.element.on('dblclick', '.dirs .dir', function() {
      fileManager.loadDir($(this).data('path'), "", function(success, error){
        console.log(success);
        console.log(error);
      });
    });

    fileManager.element.on('dragstart', '.dirs .dir', fileManager.dir_dragStart);
    fileManager.element.on('dragenter', '.dirs .dir', fileManager.dir_dragEnter);
    fileManager.element.on('dragover', '.dirs .dir', fileManager.dir_dragOver);
    fileManager.element.on('dragleave', '.dirs .dir', fileManager.dir_dragLeave);
    fileManager.element.on('drop', '.dirs .dir', fileManager.dir_drop);

    // HANDLER FOR FILE-ITEM
    fileManager.element.on('click', '.files .file', function() {
      fileManager.fileClickHandler(this);
    });

    fileManager.element.on('dblclick', '.files .file', function() {
      console.log(this);
    });

    fileManager.element.on('dragstart', '.files .file', fileManager.file_dragStart);

    // HANDLER FOR PATH ANCHOR
    fileManager.element.on('click', '.path a', function(e) {
      e.preventDefault();
      fileManager.pathClickHandler(this);
    });

    // HANDLER FOR SEARCH BOX
    fileManager.element.find('.search input').on('input', function() {
      fileManager.searchChangeHandler(this);
    });

    // load root folder
    fileManager.loadDir("", "", function(success, error){

    });
  },

  //  ============================================
  //  FUNCTIONS
  //  ============================================
  loadDir(dir, search, callback){
    let searchUrl = (search) ? ("&s="+encodeURIComponent(search)) : "";
    fileManager.element.find('.search input').val(search);
    $.ajax({
      dataType: "json",
      url: fileManager.url+"?a=index&dir="+encodeURIComponent(dir)+searchUrl,
      success: function(data){
        if(data.error){
          if(callback){callback(false, data.data);}
        }else{
          if(data.data.dirs.length > 0 || data.data.files.length > 0){
            fileManager.addDirsToDOM(data.data.dirs);
            fileManager.addFilesToDOM(data.data.files);
          }else{
            fileManager.addNothingMessageToDOM();
          }
          fileManager.createPathAnchors(dir);
          fileManager.path = dir;
          if(callback){callback(true, null);}
        }
      },
      error: function(){
        if(callback){callback(false, null);}
      }
    });
  },

  addDirsToDOM(dirs){
    let el = fileManager.element.find('.dirs');
    el.html('');
    dirs.forEach(function(dir){
      el.append(fileManager.htmlDir(dir));
    });
  },
  addFilesToDOM(files){
    let el = fileManager.element.find('.files');
    el.html('');
    files.forEach(function(file){
      el.append(fileManager.htmlFile(file));
      fileManager.loadPreview(file.fullpath);
    });
  },
  addNothingMessageToDOM(){
    fileManager.element.find('.dirs').html('');
    fileManager.element.find('.files').html(fileManager.htmlNoFiles());
  },





  //  ============================================
  //  Selection System
  //  ============================================
  unselectAll(){
    fileManager.element.find('.files .file').each(function(){
      $(this).removeClass('active');
    });
    fileManager.element.find('.dirs .dir').each(function(){
      $(this).removeClass('active');
    });
  },
  selectItem(item){
    fileManager.unselectAll();
    $(item).addClass('active');
  },


  //  ============================================
  //  Handler
  //  ============================================
  fileMangerClickHandler(e){
    if(e.target.className == "dirs" || e.target.className == "files"){
      fileManager.unselectAll();
    }
  },

  dirClickHandler(me){
    fileManager.selectItem(me);
  },

  fileClickHandler(me){
    fileManager.selectItem(me);
  },

  pathClickHandler(me){
    fileManager.loadDir($(me).data('path'), "", function(success, error){});
  },

  searchChangeHandler(me){
    let search = $(me).val();
    fileManager.lastSearch = search;
    setTimeout(function(){
      if(fileManager.lastSearch != search){return;}
      fileManager.loadDir(fileManager.path, search, function(success, error){});
    }, fileManager.searchDelay);
  },



  //  ============================================
  //  Onclick Handler
  //  ============================================
  createPathAnchors(path){
    let folder = path.split('/');
    fileManager.pathAnchorsFromArray(folder);
  },

  pathAnchorsFromArray(arr){
    let ul = fileManager.element.find('.path ul');
    ul.html('<li><a data-path="" href="#">Meine Dateien</a></li>');
    let fullPath = "";
    arr.forEach(function(dir){
      if(dir==""){return;}
      fullPath = (!fullPath) ? dir : fullPath + "/" + dir;
      ul.append('><li><a data-path="'+fullPath+'" href="#">'+dir+'</a></li>');
    });
  },

  //  ============================================
  //  Drag and Drop Dir
  //  ============================================
  dirAnchor_dragStart(e){
    fileManager.selectItem(this);
    e.originalEvent.dataTransfer.setDragImage(this, 0, 0);
  },
  dirAnchor_dragEnter(e){
      fileManager.element.find(this).addClass('dragover');
  },
  dirAnchor_dragOver(e){
      e.originalEvent.preventDefault();
      e.originalEvent.dataTransfer.dropEffect = 'copy';
  },
  dirAnchor_dragLeave(e){
    fileManager.element.find(this).removeClass('dragover')
  },
  dirAnchor_drop(e){
    fileManager.element.find(this).removeClass('dragover')
    let data = e.originalEvent.dataTransfer.getData("text");
    let folder = this.dataset.path;
    fileManager.moveFile(data, folder);
  },

  //  ============================================
  //  Drag and Drop Dir
  //  ============================================
  dir_dragStart(e){
    fileManager.selectItem(this);
    e.originalEvent.dataTransfer.setDragImage(this, 0, 0);
  },
  dir_dragEnter(e){
      fileManager.element.find(this).addClass('dragover');
  },
  dir_dragOver(e){
      e.originalEvent.preventDefault();
      e.originalEvent.dataTransfer.dropEffect = 'copy';
  },
  dir_dragLeave(e){
    fileManager.element.find(this).removeClass('dragover')
  },
  dir_drop(e){
    fileManager.element.find(this).removeClass('dragover')
    let data = e.originalEvent.dataTransfer.getData("text");
    let folder = this.dataset.path;
    fileManager.moveFile(data, folder);
  },



  //  ============================================
  //  Drag and Drop File
  //  ============================================
  file_dragStart(e){
    e.originalEvent.dataTransfer.effectAllowed = "copyMove";
    e.originalEvent.dataTransfer.setData("text/plain", this.dataset.path);
    fileManager.selectItem(this);
    e.originalEvent.dataTransfer.setDragImage($(this).find('h3')[0], 0, 0);
  },


  //  ============================================
  //  File/Folder Functions
  //  ============================================
  moveFile(file, output, callback){
    $.ajax({
      dataType: "json",
      url: fileManager.url+"?a=move&file="+encodeURIComponent(file)+"&output="+encodeURIComponent(output),
      success: function(data){
        if(data.error){
          if(callback){callback(false, data.data);}
        }else{
          fileManager.loadDir(fileManager.path);
          if(callback){callback(true, null);}
        }
      },
      error: function(){
        if(callback){callback(false, null);}
      }
    });
  },



  //  ============================================
  //  CANVAS PREVIEW
  //  ============================================
  loadPreview(path){
    var type = fileManager.getFiletype(path);
    if(type == "image"){
      fileManager.generateFilePreview(path, type);
      fileManager.loadImagePreview(path);
    }else{
      fileManager.generateFilePreview(path, type);
    }
  },

  generateFilePreview(path, type){
    let preview = fileManager.element.find('*[data-path="'+path+'"] .preview');
    // This two lines fixes the canvas :)
    preview[0].width = preview.width();
    preview[0].height = preview.height();

    let ctx = preview[0].getContext("2d");
    ctx.save();
    ctx.fillStyle = "rgba(241, 75, 59, 0.6)";
    fileManager.roundRect(ctx, 30, 50, preview.width() - 60, preview.height() - 100, 3, true, false);

    ctx.font="65px Arial";
    ctx.textAlign="center";
    ctx.fillStyle = 'white';
    ctx.fillText(type,preview.width() / 2, preview.height() / 2 + 25);
    ctx.restore();
  },

  loadImagePreview(path){
    let preview = fileManager.element.find('*[data-path="'+path+'"] .preview');

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

    imageObj.src = fileManager.url+"?a=preview&file="+encodeURIComponent(path)+"&w="+preview.width()+"&h="+preview.height();
  },

  //  ============================================
  //  FILETYPES
  //  ============================================
  getFiletype(filename){
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
  },
  getIcon(filetype){
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
  },



  //  ============================================
  //  HTML MARKUP
  //  ============================================
  htmlDir(dir){
    let html = '<div draggable="true" data-path="'+dir.fullpath+'" class="dir"><i class="fa fa-folder" aria-hidden="true"></i></i><h3>'+dir.filename+'</h3></div>';
    return html;
  },
  htmlFile(file){
    let filetype = fileManager.getFiletype(file.filename);
    let icon = fileManager.getIcon(filetype);
    let html = '<div draggable="true" data-path="'+file.fullpath+'" class="file"><canvas class="preview"></canvas><h3>'+icon+' '+file.filename+'</h3></div>';
    return html;
  },
  htmlNoFiles(){
    let html = '<h3>No files uploaded yet.</h3>';
    return html;
  },


  //  ============================================
  //  ROUNDED RECTANGLE
  //  ============================================
  // NOTICE: FROM http://stackoverflow.com/questions/1255512/how-to-draw-a-rounded-rectangle-on-html-canvas

  roundRect(ctx, x, y, width, height, radius, fill, stroke) {
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
  }



}
