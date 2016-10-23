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

    // HANDLER FOR FILE-ITEM
    fileManager.element.on('click', '.files .file', function() {
      fileManager.fileClickHandler(this);
    });

    fileManager.element.on('dblclick', '.files .file', function() {
      console.log(this);
    });

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
  //  Filetype stuff
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
    let html = '<div data-path="'+dir.fullpath+'" class="dir"><i class="fa fa-folder" aria-hidden="true"></i></i><h3>'+dir.filename+'</h3></div>';
    return html;
  },
  htmlFile(file){
    let filetype = fileManager.getFiletype(file.filename);
    let icon = fileManager.getIcon(filetype);
    let html = '<div data-path="'+file.fullpath+'" class="file"><canvas class="preview"></canvas><h3>'+icon+' '+file.filename+'</h3></div>';
    return html;
  },
  htmlNoFiles(){
    let html = '<h3>No files uploaded yet.</h3>';
    return html;
  }
}