"use strict";

// =================================================
//  BUTTON LOADING
// =================================================

var buttonManager = {
  loading: function loading(button, loadingText, finishedText) {
    button.dataset.status = "loading";
    button.dataset.finishedText = finishedText ? finishedText : button.innerHTML;
    button.innerHTML = loadingText ? loadingText : "Bitte warten...";
  },
  finished: function finished(button, finishedText, enabelAgain) {
    button.dataset.status = enabelAgain ? "ready" : "finished";
    button.innerHTML = finishedText ? finishedText : button.dataset.finishedText;
  },
  buttonActiv: function buttonActiv(button, finishedIsActive) {
    if (button.dataset.status == "loading" || !finishedIsActive && button.dataset.status == "finished") {
      return false;
    } else {
      return true;
    }
  }
};

function ContextMenu(selector, items) {
  var magickSelect = arguments.length > 2 && arguments[2] !== undefined ? arguments[2] : null;

  this.selector = selector;
  this.items = items;
  this.magickSelect = magickSelect;
  this.class = 'contextMenu';
  this.currentElement = null;
  this.trigger = null;
  this.id = (0 | Math.random() * 9e6).toString(36);
  console.log(this.id);

  this.setup = function () {
    this._setHandler();
  };

  this._setHandler = function () {
    var me = this;
    // For Showing
    $(this.selector).off('contextmenu.' + me.id).on('contextmenu.' + me.id, magickSelect, function (e) {
      e.preventDefault();
      e.stopPropagation();
      me.trigger = e.currentTarget;
      me.show(e.pageY + "px", e.pageX + "px");
    });
    // For Hiding
    $(document).off('click.' + me.id).on('click.' + me.id, function (e) {
      if (!me.currentElement) return;
      if ($(e.target).parents('.' + this.class).length <= 0) {
        me.hide();
      }
    });
  };

  this.hide = function () {
    var me = this;
    me.currentElement.fadeOut(100, function () {
      me._removeFromDOM();
    });
  };

  this.show = function (top, left) {
    this._addToDom();
    this.currentElement.css('top', top);
    this.currentElement.css('left', left);
    this.currentElement.fadeIn(100);
  };

  this._genHtml = function () {
    var me = this;
    var ul = $('<ul>');
    var html = $('<div>').addClass(this.class).append(ul);
    items.forEach(function (item) {
      item.ContextMenu = me;
      item._contextClickHandler = function () {
        me.hide();
      };
      ul.append(item.getElement());
    });
    return html;
  };

  this._addToDom = function (html) {
    this._removeFromDOM();
    this.currentElement = this._genHtml();
    $('body').append(this.currentElement);
  };

  this._removeFromDOM = function (html) {
    $('.' + this.class).each(function () {
      $(this).remove();
    });
    this.currentElement = null;
  };

  this.setup();
}

function ContextMenuItem(html, callback) {
  var me = this;
  this.html = html;
  this.callback = callback;
  this.ContextMenu = null;
  this.getElement = function () {
    var $element = $('<li>').html(html);
    $element.on('click', function (e) {
      if (me._contextClickHandler) me._contextClickHandler();
      if (me.callback) me.callback.call(me.ContextMenu.trigger);
    });
    return $element;
  };
}

function FileManager() {
  var selector = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : "#fileManager";

  var myself = this;
  this.cutLenght = 12;
  this.element = null;
  this.url = 'php/ajax_fileManager.php', this.path = null;
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
  this.init = function () {
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
    myself.element.on('click', '.dirs .dir', function () {
      myself.dirClickHandler(this);
    });

    myself.element.on('dblclick', '.dirs .dir', function () {
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
    myself.element.on('click', '.files .file', function () {
      myself.fileClickHandler(this);
    });

    myself.element.on('dblclick', '.files .file', function () {
      if (myself.onFileDblClick) myself.onFileDblClick($(this));
    });

    myself.element.on('dragstart', '.files .file', myself.file_dragStart);
    myself.element.on('dragend', '.files .file', myself.file_dragEnd);

    // HANDLER FOR PATH ANCHOR
    myself.element.on('click', '.path a', function (e) {
      e.preventDefault();
      myself.pathClickHandler(this);
    });

    // HANDLER FOR SEARCH BOX
    myself.element.find('.search input').on('input', function () {
      myself.searchChangeHandler(this);
    });

    // CONTEXT HANDLER
    var contextItems = [new ContextMenuItem('New Folder', function () {
      var folder = myself.path;
      var html = lightboxQuestion('New Folder');
      html += lightboxInput('name', 'text', 'Name');
      showLightbox(html, function (res, lbdata) {
        if (res) {
          myself.createDir(folder + "/" + lbdata['name'], function (success, message) {
            if (!success) {
              notify(NOTIFY_ERROR, message);
              return;
            }
          });
        }
      }, null, "Create", "Cancel");
    })];
    var dirContextItems = [new ContextMenuItem('Open', function () {
      myself.loadDir($(this).data('path'), "");
    }), new ContextMenuItem('New Folder', function () {
      var folder = myself.path;
      var html = lightboxQuestion('New Folder');
      html += lightboxInput('name', 'text', 'Name');
      showLightbox(html, function (res, lbdata) {
        if (res) {
          myself.createDir(folder + "/" + lbdata['name'], function (success, message) {
            if (!success) {
              notify(NOTIFY_ERROR, message);
              return;
            }
          });
        }
      }, null, "Create", "Cancel");
    }), new ContextMenuItem('Rename', function () {
      var item = $(this);
      var html = lightboxQuestion('Rename File');
      html += lightboxInput('name', 'text', 'Name', item.data('path'));
      showLightbox(html, function (res, lbdata) {
        if (res) {
          myself.renameFile(item.data('path'), lbdata['name'], function (success, message) {
            if (!success) {
              notify(NOTIFY_ERROR, message);
              return;
            }
          });
        }
      }, null, "Rename", "Cancel");
    }), new ContextMenuItem('Delete', function () {
      var file = $(this).data('path');
      var filename = file.split("/").pop();
      showLightbox(lightboxQuestion('Delete \'' + filename + '\' ?!'), function (success) {
        if (success) myself.trashFile(file);
      }, null, "Delete", "Cancel");
    })];
    var fileContextItems = [new ContextMenuItem('Open', function () {
      window.open(myself.getLink($(this).data('path')), '_blank');
    }), new ContextMenuItem('Rename', function () {
      var item = $(this);
      var html = lightboxQuestion('Rename File');
      html += lightboxInput('name', 'text', 'Name', item.data('path'));
      showLightbox(html, function (res, lbdata) {
        if (res) {
          myself.renameFile(item.data('path'), lbdata['name'], function (success, message) {
            if (!success) {
              notify(NOTIFY_ERROR, message);
              return;
            }
          });
        }
      }, null, "Rename", "Cancel");
    }), new ContextMenuItem('Delete', function () {
      var file = $(this).data('path');
      var filename = file.split("/").pop();
      showLightbox(lightboxQuestion('Delete \'' + filename + '\' ?!'), function (success) {
        if (success) myself.trashFile(file);
      }, null, "Delete", "Cancel");
    })];
    var context = new ContextMenu(selector, contextItems);
    var fileContext = new ContextMenu('.files', fileContextItems, '.file');
    var dirContext = new ContextMenu('.dirs', dirContextItems, '.dir');

    // load root folder
    myself.loadDir("", "", function (success, error) {});
  };

  //  ============================================
  //  FUNCTIONS
  //  ============================================
  this.loadDir = function (dir, search, callback) {
    var searchUrl = search ? "&s=" + encodeURIComponent(search) : "";
    myself.element.find('.search input').val(search);
    $.ajax({
      dataType: "json",
      url: myself.url + "?a=index&dir=" + encodeURIComponent(dir) + searchUrl,
      success: function success(data) {
        if (data.error) {
          if (callback) {
            callback(false, data.data);
          }
        } else {
          if (data.data.dirs.length > 0 || data.data.files.length > 0) {
            myself.addDirsToDOM(data.data.dirs);
            myself.addFilesToDOM(data.data.files);
          } else {
            myself.addNothingMessageToDOM();
          }
          myself.createPathAnchors(dir);
          myself.path = dir;
          if (callback) {
            callback(true, null);
          }
          if (myself.onDirLoaded) {
            myself.onDirLoaded(true, null);
          }
        }
      },
      error: function error() {
        if (callback) {
          callback(false, null);
        }
        if (myself.onDirLoaded) {
          onDirLoaded(false, null);
        }
      }
    });
  };

  this.addDirsToDOM = function (dirs) {
    var el = myself.element.find('.dirs');
    el.html('');
    dirs.forEach(function (dir) {
      el.append(myself.htmlDir(dir));
    });
  };
  this.addFilesToDOM = function (files) {
    var el = myself.element.find('.files');
    el.html('');
    files.forEach(function (file) {
      el.append(myself.htmlFile(file));
      myself.loadPreview(file.fullpath);
    });
  };
  this.addNothingMessageToDOM = function () {
    myself.element.find('.dirs').html('');
    myself.element.find('.files').html(myself.htmlNoFiles());
  };

  //  ============================================
  //  Selection System
  //  ============================================
  this.unselectAll = function () {
    myself.element.find('.files .file').each(function () {
      $(this).removeClass('active');
    });
    myself.element.find('.dirs .dir').each(function () {
      $(this).removeClass('active');
    });
  };
  this.selectItem = function (item) {
    myself.unselectAll();
    $(item).addClass('active');
  };

  //  ============================================
  //  Handler
  //  ============================================
  this.fileMangerClickHandler = function (e) {
    if (e.target.className == "dirs" || e.target.className == "files") {
      myself.unselectAll();
    }
  };

  this.dirClickHandler = function (me) {
    myself.selectItem(me);
  };

  this.fileClickHandler = function (me) {
    myself.selectItem(me);
  };

  this.pathClickHandler = function (me) {
    myself.loadDir($(me).data('path'), "", function (success, error) {});
  };

  this.searchChangeHandler = function (me) {
    var search = $(me).val();
    myself.lastSearch = search;
    setTimeout(function () {
      if (myself.lastSearch != search) {
        return;
      }
      myself.loadDir(myself.path, search, function (success, error) {});
    }, myself.searchDelay);
  };

  //  ============================================
  //  Onclick Handler
  //  ============================================
  this.createPathAnchors = function (path) {
    var folder = path.split('/');
    myself.pathAnchorsFromArray(folder);
  };

  this.pathAnchorsFromArray = function (arr) {
    var ul = myself.element.find('.path ul');
    ul.html('<li><a data-path="" href="#">Meine Dateien</a></li>');
    var fullPath = "";
    arr.forEach(function (dir) {
      if (dir == "") {
        return;
      }
      fullPath = !fullPath ? dir : fullPath + "/" + dir;
      ul.append('><li><a data-path="' + fullPath + '" href="#">' + dir + '</a></li>');
    });
  };

  //  ============================================
  //  Drag and Drop Files-Container
  //  ============================================
  this.files_dragEnter = function (e) {
    myself.element.find(this).addClass('dragover');
  };
  this.files_dragOver = function (e) {
    if (myself.eventContainsFiles(e.originalEvent)) {
      e.originalEvent.preventDefault();
      e.originalEvent.dataTransfer.dropEffect = 'copy';
    }
  };
  this.files_dragLeave = function (e) {
    myself.element.find(this).removeClass('dragover');
  };
  this.files_drop = function (e) {
    e.originalEvent.preventDefault();
    myself.element.find(this).removeClass('dragover');
    if (e.originalEvent.dataTransfer.files.length > 0) {
      var folder = myself.path;
      var files = e.originalEvent.dataTransfer.files;
      for (var i = 0, f; f = files[i]; i++) {
        myself.uploadFile(f, folder);
      }
    }
  };

  //  ============================================
  //  Drag and Drop Dir
  //  ============================================
  this.dir_dragStart = function (e) {
    myself.isMoveFile = true;
    myself.selectItem(this);
    e.originalEvent.dataTransfer.setDragImage(this, 0, 0);
    e.originalEvent.dataTransfer.setData("text/plain", this.dataset.path);
  };
  this.dir_dragEnter = function (e) {
    if ($(this).data('role') !== 'trash' && myself.eventContainsFiles(e.originalEvent) || myself.isMoveFile) {
      myself.element.find(this).addClass('dragover');
    }
  };
  this.dir_dragOver = function (e) {
    if ($(this).data('role') !== 'trash' && myself.eventContainsFiles(e.originalEvent) || myself.isMoveFile) {
      e.originalEvent.preventDefault();
      e.originalEvent.dataTransfer.dropEffect = 'copy';
    }
  };
  this.dir_dragLeave = function (e) {
    myself.element.find(this).removeClass('dragover');
  };
  this.dir_drop = function (e) {
    var _this = this;

    e.originalEvent.preventDefault();
    myself.element.find(this).removeClass('dragover');
    if ($(this).data('role') !== 'trash' && e.originalEvent.dataTransfer.files.length > 0) {
      // Upload Files
      var folder = this.dataset.path;
      var files = e.originalEvent.dataTransfer.files;
      for (var i = 0, f; f = files[i]; i++) {
        myself.uploadFile(f, folder);
      }
    } else if (myself.isMoveFile) {
      var filename;

      (function () {
        // Move File
        myself.isMoveFile = false;
        var data = e.originalEvent.dataTransfer.getData("text");
        filename = data.split("/").pop(); // Only for LighboxQuestion

        var folder = _this.dataset.path;
        if ($(_this).data('role') === 'trash') {
          showLightbox(lightboxQuestion('Delete \'' + filename + '\' ?!'), function (success) {
            if (success) myself.trashFile(data);
          }, null, "Delete", "Cancel");
        } else {
          myself.moveFile(data, folder);
        }
      })();
    }
  };
  this.dir_dragEnd = function (e) {
    myself.isMoveFile = false;
  };

  //  ============================================
  //  Drag and Drop File
  //  ============================================
  this.file_dragStart = function (e) {
    myself.isMoveFile = true;
    e.originalEvent.dataTransfer.effectAllowed = "copyMove";
    e.originalEvent.dataTransfer.setData("text/plain", this.dataset.path);
    myself.selectItem(this);
    e.originalEvent.dataTransfer.setDragImage($(this).find('h3')[0], 0, 0);
  };
  this.file_dragEnd = function (e) {
    myself.isMoveFile = false;
  };

  //  ============================================
  //  Drag and Drop Functions
  //  ============================================

  this.eventContainsFiles = function (e) {
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
  this.trashFile = function (file, callback) {
    $.ajax({
      dataType: "json",
      url: myself.url + "?a=moveToTrash&file=" + encodeURIComponent(file),
      success: function success(data) {
        console.log(data);
        if (data.error) {
          if (callback) {
            callback(false, data.data);
          }
        } else {
          myself.loadDir(myself.path, myself.lastSearch);
          if (callback) {
            callback(true, null);
          }
        }
      },
      error: function error() {
        if (callback) {
          callback(false, null);
        }
      }
    });
  };

  this.getLink = function (file) {
    var arr = window.location.href.split('/');
    arr.pop();
    arr.pop();
    return arr.join('/') + "/file" + file;
  };

  this.renameFile = function (file, newfile, callback) {
    $.ajax({
      dataType: "json",
      url: myself.url + "?a=rename&file=" + encodeURIComponent(file) + "&newfile=" + encodeURIComponent(newfile),
      success: function success(data) {
        if (data.error) {
          if (callback) {
            callback(false, data.data);
          }
        } else {
          myself.loadDir(myself.path, myself.lastSearch);
          if (callback) {
            callback(true, null);
          }
        }
      },
      error: function error() {
        if (callback) {
          callback(false, null);
        }
      }
    });
  };

  this.createDir = function (dir, callback) {
    $.ajax({
      dataType: "json",
      url: myself.url + "?a=createdir&path=" + encodeURIComponent(dir),
      success: function success(data) {
        if (data.error) {
          if (callback) {
            callback(false, data.data);
          }
        } else {
          myself.loadDir(myself.path, myself.lastSearch);
          if (callback) {
            callback(true, null);
          }
        }
      },
      error: function error() {
        if (callback) {
          callback(false, null);
        }
      }
    });
  };

  this.moveFile = function (file, output, callback) {
    $.ajax({
      dataType: "json",
      url: myself.url + "?a=move&file=" + encodeURIComponent(file) + "&output=" + encodeURIComponent(output),
      success: function success(data) {
        if (data.error) {
          if (callback) {
            callback(false, data.data);
          }
        } else {
          myself.loadDir(myself.path, myself.lastSearch);
          if (callback) {
            callback(true, null);
          }
        }
      },
      error: function error() {
        if (callback) {
          callback(false, null);
        }
      }
    });
  };

  this.uploadFile = function (file, output) {
    var ajaxData = new FormData();
    if (file) {
      ajaxData.append('file', file);

      $.ajax({
        url: myself.url + "?a=uploadFiles&output=" + encodeURIComponent(output),
        type: 'POST',
        data: ajaxData,
        dataType: 'json',
        cache: false,
        contentType: false,
        processData: false,
        complete: function complete() {
          // completed
        },
        success: function success(data) {
          $('#pageContainer').append(data.data);
          myself.loadDir(myself.path);
          if (data.error) {
            data.error.forEach(function (err, index) {
              setTimeout(function () {
                notify(NOTIFY_ERROR, err);
              }, 1.5 * index);
            });
          }
        },
        error: function error() {
          notify(NOTIFY_ERROR, 'Upload failed! Unknown error!');
        }
      });
    }
  };

  //  ============================================
  //  CANVAS PREVIEW
  //  ============================================
  this.loadPreview = function (path) {
    var type = myself.getFiletype(path);
    if (type == "image") {
      myself.generateFilePreview(path, type);
      myself.loadImagePreview(path);
    } else {
      myself.generateFilePreview(path, type);
    }
  };

  this.generateFilePreview = function (path, type) {
    var preview = myself.element.find('*[data-path="' + path + '"] .preview');
    // This two lines fixes the canvas :)
    preview[0].width = preview.width();
    preview[0].height = preview.height();

    var ctx = preview[0].getContext("2d");
    ctx.save();
    ctx.fillStyle = "rgba(241, 75, 59, 0.6)";
    myself.roundRect(ctx, 30, 50, preview.width() - 60, preview.height() - 100, 3, true, false);

    ctx.font = "65px Arial";
    ctx.textAlign = "center";
    ctx.fillStyle = 'white';
    ctx.fillText(type, preview.width() / 2, preview.height() / 2 + 25);
    ctx.restore();
  };

  this.loadImagePreview = function (path) {
    var preview = myself.element.find('*[data-path="' + path + '"] .preview');

    var imageObj = new Image();
    imageObj.onload = function () {
      // This two lines fixes the canvas :)
      preview[0].width = preview.width();
      preview[0].height = preview.height();
      var ctx = preview[0].getContext("2d");
      var locX = (this.width - preview.width()) * -1 / 2;
      var locY = (this.height - preview.height()) * -1 / 2;

      ctx.drawImage(this, 0, 0, preview.width(), preview.height(), locX, locY, preview.width(), preview.height());
    };

    imageObj.src = myself.url + "?a=preview&file=" + encodeURIComponent(path) + "&w=" + preview.width() + "&h=" + preview.height();
  };

  //  ============================================
  //  FILETYPES
  //  ============================================
  this.getFiletype = function (filename) {
    var extension = filename.split('.').pop().toLowerCase();
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
  this.getIcon = function (filetype) {
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
  this.htmlDir = function (dir) {
    var filename = dir.filename.length > this.cutLenght ? dir.filename.substr(0, this.cutLenght - 3) + " ..." : dir.filename;
    var html = '<div draggable="true" data-path="' + dir.fullpath + '" class="dir" title="' + dir.filename + '"><i class="fa fa-folder" aria-hidden="true"></i></i><h3>' + filename + '</h3></div>';
    return html;
  };
  this.htmlFile = function (file) {
    var filetype = myself.getFiletype(file.filename);
    var icon = myself.getIcon(filetype);
    var filename = file.filename.length > this.cutLenght ? file.filename.substr(0, this.cutLenght - 3) + " ..." : file.filename;
    var html = '<div draggable="true" data-path="' + file.fullpath + '" class="file" title="' + file.filename + '"><canvas class="preview"></canvas><h3>' + icon + ' ' + filename + '</h3></div>';
    return html;
  };
  this.htmlNoFiles = function () {
    var html = '<h3>No files uploaded yet.</h3>';
    return html;
  };

  //  ============================================
  //  ROUNDED RECTANGLE
  //  ============================================
  // NOTICE: FROM http://stackoverflow.com/questions/1255512/how-to-draw-a-rounded-rectangle-on-html-canvas

  this.roundRect = function (ctx, x, y, width, height, radius, fill, stroke) {
    if (typeof stroke == 'undefined') {
      stroke = true;
    }
    if (typeof radius === 'undefined') {
      radius = 5;
    }
    if (typeof radius === 'number') {
      radius = { tl: radius, tr: radius, br: radius, bl: radius };
    } else {
      var defaultRadius = { tl: 0, tr: 0, br: 0, bl: 0 };
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

var fileSelector = {
  init: function init(input, ok, abort) {
    (function (_input, _ok, _abort) {
      var input = $(_input);
      if (input.hasClass('fileSelector')) return;
      input.addClass('fileSelector');
      var button = $('<button/>').text('Select a File').addClass('fileSelector');
      var div = $("<div/>").css('position', 'relative').html(button);
      input.after(div);
      button.on('click', function () {
        openDialog();
      });
      input.hide();

      // If input has already Value
      if (input.val() !== "") setButtonText(getFileName(input.val()));

      var container = fileSelector._container();
      button.before(container);
      var backButton = container.find('.back').on('click', backButtonClick);
      var closeButton = container.find('.close').on('click', abortDialog);

      // FileManager Magic !! <3    // Even has Handlers holy fu*k!  by the way hearing Eminem - Stan atm ^^
      var fileManager = new FileManager(container);
      fileManager.onDirLoaded = function () {
        refreshBackButtonVisibility();
      };
      fileManager.onFileDblClick = function (file) {
        okDialog(file);
      };

      function refreshBackButtonVisibility() {
        if (fileManager.path !== "") {
          var path = fileManager.path.split("/");
          path.pop();
          path = path.join("/");
          backButton[0].dataset.path = path;
          backButton.show();
        } else {
          backButton.hide();
        }
      }

      function backButtonClick() {
        fileManager.loadDir(backButton.data('path'));
      }

      function openDialog(element, input) {
        container.fadeIn(200, function () {
          fileManager.init();
        });
      }

      function okDialog(file) {
        var path = file.data('path');
        var name = getFileName(path);
        input.val(path);
        var res = {
          name: name,
          path: path
        };
        setButtonText(name);
        closeDialog(function () {
          if (ok) ok(res);
        });
      }

      function abortDialog() {
        closeDialog(function () {
          if (abort) abort();
        });
      }

      function closeDialog(cb) {
        container.fadeOut(400, function () {
          fileManager.loadDir("");
          if (cb) cb();
        });
      }

      function setButtonText(name) {
        button.text(name).addClass('isSelected');
      }

      function getFileName(path) {
        var splitted = path.split('/');
        return splitted[splitted.length - 1];
      }
    })(input, ok, abort);
  },
  _container: function _container(width, height) {
    // Mein Container
    var container = $('<div/>').addClass('fileSelectorBox');
    // Headbar
    var head = $('<header/>');
    head.append($('<button/>').html('<i class="fa fa-chevron-left" aria-hidden="true"></i>').addClass('back').addClass('customDir'));
    head.append($('<span/>').text('Select a file ...'));
    head.append($('<button/>').html('<i class="fa fa-times" aria-hidden="true"></i>').addClass('close'));
    // Searchbar
    var search = $('<div/>').addClass('search-container').addClass('search');
    search.append($('<input/>').attr('placeholder', 'Search'));
    // File-Container
    var subcontainer = $('<div/>').addClass('filesystem-container');

    var dircontainer = $('<div/>').addClass('dirs');
    var filecontainer = $('<div/>').addClass('files');

    subcontainer.append(dircontainer);
    subcontainer.append(filecontainer);

    // Add elements
    container.append(head);
    container.append(search);
    container.append(subcontainer);
    return container;
  },
  _folder: function _folder(name) {
    var folder = $('<div/>').addClass('dir').html('<i class="fa fa-folder" aria-hidden="true"></i><h3>' + name + "</h3>");
    return folder;
  },
  _file: function _file(name) {
    var folder = $('<div/>').addClass('file').html("\n      <canvas class=\"preview\"></canvas>\n      <footer>\n      <i class=\"fa fa-file-image-o\" aria-hidden=\"true\"></i>\n      <h3>" + name + "</h3>\n      </footer>\n      ");
    return folder;
  }
};

// =================================================
//  INTERFACE - LIGHTBOX
// =================================================

function lightboxQuestion(text) {
  return '<p class="lightboxquestion">' + text + "</p>";
}

function lightboxInput(name, type, placeholder, value) {
  if (placeholder == null) {
    placeholder = "";
  }
  if (value == null) {
    value = "";
  }
  var html = "";
  return '<input class="lightboxinput" value="' + value + '" placeholder="' + placeholder + '" data-name="' + name + '" type="' + type + '">';
}

function lightboxCheckbox(name, text, value) {
  value = value ? " checked" : "";
  var html = '<input class="lightboxinput" data-name="' + name + '" type="checkbox"' + value + '>' + text + "<br>";
  return html;
}

function lightboxSelect(name, options, placeholder) {
  var html = '<select class="lightboxinput" data-name="' + name + '">';
  if (placeholder !== null) {
    html += '<option value="" selected disabled>' + placeholder + '</option>';
  }
  options.forEach(function (item) {
    html += '<option value="' + item.value + '"' + (item.selected === true ? "selected" : "") + '>' + item.text + '</option>';
  });
  html += '</select>';
  return html;
}

function showLightbox(html, callback, visibleCallback, ok_button, cancel_button, customClass) {
  if (ok_button == null) {
    ok_button = "Ok";
  }
  if (cancel_button == null) {
    cancel_button = "Cancel";
  }
  lightboxDialogContent.html(html);
  lightbox.css("display", "block");
  lightboxDialog.attr('class', 'dialog');
  if (customClass != null) {
    lightboxDialog.addClass(customClass);
  }
  lightboxDialog.css("margin-top", -lightboxDialog.height() - 50);
  lightboxDialog.css("height", lightboxDialogContent.height() + lightboxOkBtn.height() + 30);
  wrapper.css("filter", "blur(5px)");
  lightboxDialog.animate({ "margin-top": "0" }, 500, function () {
    if (visibleCallback != null) {
      visibleCallback();
    }
  });

  lightboxOkBtn.unbind();
  if (ok_button == false) {
    lightboxOkBtn.css('display', 'none');
  } else {
    lightboxOkBtn.css('display', 'inline-block');
    lightboxOkBtn.text(ok_button);
    lightboxOkBtn.on("click", function () {
      var data = { length: 0 };
      lightboxDialogContent.find('.lightboxinput').each(function (index) {
        var input = $(this);
        var val = "";
        if (input.attr('type') == "file") {
          val = input[0].files ? input[0].files[0] : null;
        } else if (input.attr('type') == "checkbox") {
          val = input[0].checked;
        } else {
          val = input.val();
        }

        data[input.data('name')] = val;
        data['length'] += 1;
      });
      lightboxDialogContent.find('.lightboxobject').each(function (index) {
        var object = $(this);
        data[object.data('name')] = object;
      });
      hideLightbox();
      if (callback != null) {
        callback(true, data);
      }
    });
  }

  lightboxCancelBtn.unbind();
  if (cancel_button == false) {
    lightboxCancelBtn.css('display', 'none');
  } else {
    lightboxCancelBtn.css('display', 'inline-block');
    lightboxCancelBtn.text(cancel_button);
    lightboxCancelBtn.on("click", function () {
      hideLightbox();
      if (callback != null) {
        callback(false, null);
      }
    });
  }
}

function hideLightbox() {
  lightboxDialog.animate({ "margin-top": -lightboxDialog.height() - 50 }, 500, function () {
    wrapper.css("filter", "none");
    lightbox.css("display", 'none');
  });
}

// =================================================
//  NOTIFICATIONS
// =================================================
var NOTIFY_SUCCESS = 1;
var NOTIFY_WARNING = 2;
var NOTIFY_ERROR = 3;
var NOTIFY_INFO = 4;
function notify(type, text, time, left) {
  var id = Math.floor(Math.random() * (999999999 - 111111111)) + 111111111;
  notifyBox[0].dataset.notifyid = id;
  notifyBox[0].className = "notify";
  if (left) {
    notifyBox[0].className += " notify-left";
  }
  if (type == NOTIFY_SUCCESS) {
    // success
    notifyBox[0].className += " notify-success";
  } else if (type == NOTIFY_WARNING) {
    // warning
    notifyBox[0].className += " notify-warning";
  } else if (type == NOTIFY_ERROR) {
    // error
    notifyBox[0].className += " notify-error";
  } else if (type == NOTIFY_INFO) {
    // info
    notifyBox[0].className += " notify-info";
  }
  notifyBox[0].innerHTML = text;
  notifyBox.fadeIn(200);
  time = time ? time * 1000 : 3000;
  setTimeout(function () {
    notify_destroy(id);
  }, time);
}

function notify_destroy(id) {
  if (notifyBox[0].dataset.notifyid == id) {
    notifyBox.fadeOut(400);
  }
}

var sidemenu = $('#sidemenu');
var menuToggle = $('#menuToggle');
var header = $('#header');
var content = $('#content');
var preloader = $('#preloader');
var wrapper = $('#wrapper');
var lightbox = $('#lightbox');
var lightboxDialog = $('#lightbox .dialog');
var lightboxDialogContent = $('#lightbox .dialog .content');
var lightboxCancelBtn = $('#lightbox .dialog .cancel');
var lightboxOkBtn = $('#lightbox .dialog .success');
var addonTopic = $('#addonTopic');
var notifyBox = $("#notify");
var defaultMenuWidth = sidemenu.width();

notifyBox[0].addEventListener('click', function () {
  notify_destroy(this.dataset.notifyid);
});

String.prototype.htmlEncode = function () {
  return $('<div/>').text(this).html();
};

String.prototype.escapeHtml = function () {
  var map = {
    '&': '&amp;',
    '<': '&lt;',
    '>': '&gt;',
    '"': '&quot;',
    "'": '&#039;'
  };
  return this.replace(/[&<>"']/g, function (m) {
    return map[m];
  });
};

String.prototype.htmlDecode = function () {
  return $('<div/>').html(this).text();
};

// =================================================
//  INTERFACE - GLOBAL
// =================================================

var menuVisible = false;
function toggleMenu(speed) {
  speed = speed === null ? 500 : speed;
  menuToggle.toggleClass('open');
  if (menuToggle.hasClass("open")) {
    if (isSmallScreen) {
      sidemenu.css('width', $(window).width());
      sidemenu.animate({ "left": 0 }, speed);
    } else {
      sidemenu.css('width', defaultMenuWidth);
      sidemenu.animate({ "left": 0 }, speed);
    }
    header.animate({ "width": header.width() - sidemenu.width() }, speed);
    wrapper.animate({ "width": wrapper.outerWidth() - sidemenu.width() }, speed);
    lightbox.animate({ "width": lightbox.outerWidth() - sidemenu.width() }, speed);
    menuVisible = true;
  } else {
    if (isSmallScreen) {
      sidemenu.width($(window).width());
      sidemenu.animate({ "left": -$(window).width() }, speed);
    } else {
      sidemenu.css('width', defaultMenuWidth);
      sidemenu.animate({ "left": -defaultMenuWidth }, speed);
    }
    header.animate({ "width": header.width() + sidemenu.width() }, speed);
    wrapper.animate({ "width": wrapper.outerWidth() + sidemenu.width() }, speed);
    lightbox.animate({ "width": lightbox.outerWidth() + sidemenu.width() }, speed);
    menuVisible = false;
  }
}

var isSmallScreen = null;
function calcSize() {
  var oldSmallScreenValue = isSmallScreen;
  isSmallScreen = $(window).width() < 750;

  if (isSmallScreen) {
    sidemenu.css('width', $(window).width());
    if (!menuVisible) {
      sidemenu.css('left', -sidemenu.width());
    }
  }

  var sidemenuWidth = menuVisible ? sidemenu.position().left + sidemenu.width() : 0;
  header.css('width', $(window).width() - sidemenuWidth);
  wrapper.css('height', $(window).height() - header.height() - 20);
  wrapper.css('width', $(window).width() - 20 - sidemenuWidth);
  wrapper.css('margin-top', header.height() + 10);
  lightbox.css('height', $(window).height() - header.height());
  lightbox.css('width', $(window).width() - sidemenuWidth);
  lightbox.css('margin-top', header.height());
  tabControlUpdateHeight();
  if (menuVisible && isSmallScreen && oldSmallScreenValue === false) {
    toggleMenu(0);
  }
}

function loadPage(page) {
  if (isSmallScreen && menuVisible) toggleMenu();
  setPageUrl(page);
  preloadManager.show(function () {
    content.load('pages/' + page + ".php", function () {
      preloadManager.hide(function () {});
      markNavItem(page, false);
      initTabcontrols(".tabContainer");
    });
  });
}

function loadAddonPage(addon) {
  if (isSmallScreen && menuVisible) toggleMenu();
  setPageUrl("addon-" + addon);
  preloadManager.show(function () {
    content.load('pages/addon.php?addon=' + addon, function () {
      preloadManager.hide(function () {
        initTabcontrols(".tabContainer");
      });
      markNavItem(addon, true);
    });
  });
}

function setPageUrl(page) {
  var url = "/admin/" + page + ".html";
  var title = "Oxymora | " + ucfirst(page);
  document.title = title;
  window.history.pushState({ "html": $('body').html(), "pageTitle": title }, "", url);
}

function markNavItem(page, PageIsAddon) {
  $('.nav').each(function () {
    if (!PageIsAddon && $(this).attr('href') == "#" + page || PageIsAddon && $(this).attr('href') == "#addon-" + page) {
      $(this).addClass('active');
    } else {
      $(this).removeClass('active');
    }
  });
}

function ucfirst(string) {
  return string.charAt(0).toUpperCase() + string.slice(1);
}

// =================================================
//  INTERFACE - TABCONTROL
// =================================================
var tabControlActiveTab = null;

function initTabcontrols(selector) {
  $(selector).find("ul li a").on('click', tabcontrolAnchorClick);

  $(selector).each(function (index) {
    tabcontrolSelectTab($(this), 0);
  });
}

function tabcontrolAnchorClick(e) {
  tabcontrolSelectTab($(this).parent().parent().parent(), this.dataset.tab);
}

function tabcontrolSelectTab(tabcontrol, tab) {

  // SELECT THE MENUITEM
  var menuItems = tabcontrol.find('ul li a');
  for (var i = 0; i < menuItems.length; i++) {
    if (menuItems[i].dataset.tab === tab || i === tab) {
      $(menuItems[i]).addClass("active");
    } else {
      $(menuItems[i]).removeClass("active");
    }
  }

  // SHOW THE DIV
  var divs = tabcontrol.find('.tabContent .tab');
  for (var i = 0; i < divs.length; i++) {
    if (divs[i].dataset.tab === tab || i === tab) {
      tabControlActiveTab = divs[i];
      $(divs[i]).css("opacity", "1");
      $(divs[i]).css("z-index", "1");
      tabControlUpdateHeight();
    } else {
      $(divs[i]).css("opacity", "0");
      $(divs[i]).css("z-index", "-1");
    }
  }
}

function tabControlUpdateHeight() {
  $(tabControlActiveTab).parent().css("height", $(tabControlActiveTab).find('.dataContainer').outerHeight() + 30);
}

// =================================================
//  INTERFACE - SPINNER FOR BUTTONS OR OTHER STUFF
// =================================================

function spinner() {
  return "<div class=\"spinner\">\n  <div class=\"rect1\"></div>\n  <div class=\"rect2\"></div>\n  <div class=\"rect3\"></div>\n  <div class=\"rect4\"></div>\n  <div class=\"rect5\"></div>\n</div>";
}

// =================================================
//  INTERFACE - PRELOADER
// =================================================

var preloadManager = {
  show: function show(cb) {
    // TweenMax.fromTo(content, 0.5, {y: '0px '}, {y: '-'+content.outerWidth()+'px', ease: Power2.easeOut});
    content.fadeOut(200);
    setTimeout(function () {
      calcSize();if (cb) {
        cb();
      }
    }, 500);
    // preloader.fadeIn(200, function(){
    // 	if(cb){cb();}
    // });
  },
  hide: function hide(cb) {
    if (cb) {
      cb();
    }
    // TweenMax.fromTo(content, 0.75, {y: '-'+content.outerWidth()+'px', opacity: 0}, {y: '0px', opacity: 1, ease: Power2.easeIn});
    content.fadeIn(500);
    calcSize();
    if (cb) {
      setTimeout(function () {
        cb();
      }, 750);
    }
    // preloader.fadeOut(500, function(){
    // 	if(cb){cb();}
    // });
  }
};

// =================================================
//  INTERFACE - NAVIGATION
// =================================================

function initNavItem() {
  sortNavItems();
  setNavItemButtonHandler($(".navitem"));
  $("#addNavButton").on('click', navItemAddButtonClick);
}

function setNavItemButtonHandler(item) {
  item.find('.buttonbar button').on('click', navItemButtonClick);
}

function sortNavItems() {
  $(".navitem").each(function (index) {
    var item = $(this);
    var display = item.data('display');
    item.css("top", display * (item.outerHeight() + 10));
  });
}

function getPrevNavItem(item) {
  var res = false;
  $(".navitem").each(function (index) {
    var pitem = $(this);
    if (item.data("display") - 1 === pitem.data("display")) {
      res = pitem;
    }
  });
  return res;
}

function getNextNavItem(item) {
  var res = false;
  $(".navitem").each(function (index) {
    var pitem = $(this);
    if (item.data("display") + 1 === pitem.data("display")) {
      res = pitem;
    }
  });
  return res;
}

function getAllNextNavItem(item) {
  var res = [];
  $(".navitem").each(function (index) {
    var pitem = $(this);
    if (item.data("display") < pitem.data("display")) {
      res.push(pitem);
    }
  });
  return res;
}

function navItemAddButtonClick() {
  var html = lightboxInput("title", "text", "Title", "") + lightboxInput("url", "text", "Url", "");
  showLightbox(html, function (res, lbdata) {
    if (res) {
      addNavItem(lbdata['title'], lbdata['url']);
    }
  });
}

function addNavItem(title, url, callback) {
  $.get('php/ajax_navigation.php?action=add&title=' + encodeURIComponent(title) + '&url=' + encodeURIComponent(url), function (data) {
    var data = JSON.parse(data);
    if (data.type === "success") {
      html = $(data.message);
      setNavItemButtonHandler(html);
      $("#navContainer").append(html);
      sortNavItems();
    }
    checkPageItemInNav();
    if (callback) {
      callback(data.type);
    }
  });
}

function navItemButtonClick() {
  var itemButton = $(this);
  var item = itemButton.parent().parent();
  var action = itemButton.data("action");
  if (action === "edit") {
    var title = item.find(".title");
    var url = item.find(".url");
    var html = lightboxInput("title", "text", "", title.html()) + lightboxInput("url", "text", "", url.html());
    showLightbox(html, function (res, lbdata) {
      if (res) {
        navDoEdit(item, lbdata['title'], lbdata['url']);
      }
    });
  } else {
    if (action === "remove") {
      var html = lightboxQuestion("Sure you want to delete?");
      showLightbox(html, function (res, lbdata) {
        if (res) {
          navDoRequest(item, action);
        }
      });
    } else {
      navDoRequest(item, action);
    }
  }
}

function navDoRequest(item, action) {
  $.get('php/ajax_navigation.php?id=' + item.data("id") + '&action=' + action, function (data) {
    var data = JSON.parse(data);
    if (data.type === "success") {
      if (action === "displayUp") {
        var prev = getPrevNavItem(item);
        item.data("display", item.data("display") - 1);
        prev.data("display", prev.data("display") + 1);
        sortNavItems();
      }
      if (action === "displayDown") {
        var next = getNextNavItem(item);
        item.data("display", item.data("display") + 1);
        next.data("display", next.data("display") - 1);
        sortNavItems();
      }
      if (action === "remove") {
        var nextItems = getAllNextNavItem(item);
        for (var i = 0; i < nextItems.length; i++) {
          nextItems[i].data("display", nextItems[i].data("display") - 1);
        }
        item.remove();
        checkPageItemInNav();
        sortNavItems();
      }
    }
  });
}

function navDoEdit(item, title, url, cb) {
  var _title = item.find('.title');
  var _url = item.find('.url');
  if (title === null) title = _title.text();
  if (url === null) url = _url.text();
  $.get('php/ajax_navigation.php?id=' + item.data("id") + '&action=edit&title=' + encodeURIComponent(title) + '&url=' + encodeURIComponent(url), function (data) {
    var data = JSON.parse(data);
    if (data.type === "success") {
      _title.html(title);
      _url.html(url);
    }
    if (cb) cb(data.type === "success");
  });
}

// =================================================
//  INTERFACE - DYNAMIC ADDON MENU
// =================================================

var addonMenu = {
  url: "php/ajax_addonMenu.php",

  loadMenuItems: function loadMenuItems() {
    $.get(addonMenu.url, function (data) {
      $('.addon-menu').remove();
      data = JSON.parse(data);
      if (data.data.length > 0) {
        addonMenu.visible(true);
        data.data.reverse();
        data.data.forEach(function (item) {
          addonTopic.after(addonMenu.createMenuItem(item.name, item.config.menuentry.displayname, item.config.menuentry.menuicon));
        });
      } else {
        addonMenu.visible(false);
      }
    });
  },
  visible: function visible(state) {
    addonTopic.css('display', state ? "block" : "none");
  },
  createMenuItem: function createMenuItem(name, displayname, icon) {
    return '<li class="addon-menu"><a class="nav" onclick="event.preventDefault();loadAddonPage(\'' + name + '\')"   href="#"><i class="fa ' + icon + '" aria-hidden="true"></i> ' + displayname + '</a></li>';
  }
};

// =================================================

// Menu Toggle Handler
menuToggle.click(toggleMenu);

// Widow resize Handler
$(window).resize(function () {
  calcSize();
});

// Calulate Size
calcSize();

// HIDE MENU
if (!isSmallScreen) toggleMenu(0);

// LOAD FIRST PAGE
if (typeof START_PAGE !== 'undefined') {
  if (START_PAGE.startsWith('addon-')) {
    loadAddonPage(START_PAGE.substring('addon-'.length));
  } else {
    loadPage(START_PAGE);
  }
} else {
  loadPage('dashboard');
}

// PRELOADER
// preloaderInit();

// GET ADDON MENU ITEMS
addonMenu.loadMenuItems();

function test() {
  // console.log(lib);
  // console.log(images);
  // console.log(createjs);
  // console.log(ss);
  ss.stop();
}

// SOME PROTOTYPE STUFF
String.prototype.ucfirst = function () {
  return this.charAt(0).toUpperCase() + this.slice(1);
};

var addonManager = {
  url: "php/ajax_addonManager.php",
  dragObj: null,
  dragActive: false,

  downloadAddon: function downloadAddon(sender, addon) {
    var html = '<iframe style="display:none;" src="php/downloadAddon.php?addon=' + addon + '"></iframe>';
    $('body').append(html);
  },
  buttonHandler: function buttonHandler(sender, addon, action) {
    if (!buttonManager.buttonActiv(sender, false)) {
      return;
    }
    buttonManager.loading(sender);
    var buttonText = void 0,
        buttonEnable = void 0,
        result = void 0;
    switch (action) {
      case 'install':
        result = addonManager.installAddon(addon, function (data) {
          console.log(data);
          if (data.error) {
            notify(NOTIFY_ERROR, data.data);
            buttonText = "Install";
            sender.dataset.action = "install";
            buttonEnable = true;
          } else {
            buttonText = "Disable";
            sender.dataset.action = "disable";
            buttonEnable = true;
          }
          buttonManager.finished(sender, buttonText, buttonEnable);
        });
        break;
      case 'enable':
        result = addonManager.enableAddon(addon);
        buttonText = "Disable";
        sender.dataset.action = "disable";
        buttonEnable = true;
        buttonManager.finished(sender, buttonText, buttonEnable);
        break;
      case 'disable':
        result = addonManager.disableAddon(addon);
        buttonText = "Enable";
        buttonEnable = true;
        sender.dataset.action = "enable";
        buttonManager.finished(sender, buttonText, buttonEnable);
        break;
      default:
        result = null;
        buttonManager.finished(sender, buttonText, buttonEnable);
    }

    return result;
  },
  installAddon: function installAddon(addon, cb) {
    $.get(addonManager.url + "?a=install&addon=" + addon, function (data) {
      data = JSON.parse(data);
      addonMenu.loadMenuItems();
      if (cb) cb(data);
    });
  },
  enableAddon: function enableAddon(addon) {
    $.get(addonManager.url + "?a=enable&addon=" + addon, function (data) {
      data = JSON.parse(data);
      addonMenu.loadMenuItems();
    });
  },
  disableAddon: function disableAddon(addon) {
    $.get(addonManager.url + "?a=disable&addon=" + addon, function (data) {
      data = JSON.parse(data);
      addonMenu.loadMenuItems();
    });
  },
  dragUploadAddon: function dragUploadAddon(files) {
    if ($(addonManager.dragObj).hasClass('upload')) {
      return;
    }
    $(addonManager.dragObj).addClass('upload');
    var ajaxData = new FormData();
    if (files) {
      $.each(files, function (i, file) {
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
        complete: function complete() {
          $(addonManager.dragObj).removeClass('upload');
        },
        success: function success(data) {
          $('#pageContainer').append(data.data);
          addonMenu.loadMenuItems();
          if (data.error) {
            data.error.forEach(function (err, index) {
              setTimeout(function () {
                notify(NOTIFY_ERROR, err);
              }, 1.5 * index);
            });
          }
        },
        error: function error() {
          notify(NOTIFY_ERROR, 'Upload failed! Unknown error!');
        }
      });
    } else {
      $(addonManager.dragObj).removeClass('upload');
    }
  },
  fileDragInit: function fileDragInit(obj) {
    obj.addEventListener("dragover", addonManager.fileDragHover, false);
    obj.addEventListener("dragleave", addonManager.fileDragHover, false);
    obj.addEventListener("drop", addonManager.fileSelectHandler, false);
    addonManager.dragObj = obj;
  },
  fileDragHover: function fileDragHover(e) {
    e.stopPropagation();
    e.preventDefault();
    if (e.type == "dragover" && $(addonManager.dragObj).hasClass('active') == false) {
      addonManager.dragActive = true;
      $(addonManager.dragObj).addClass('active');
    } else if (e.type == "dragleave") {
      addonManager.dragActive = false;
      setTimeout(function () {
        if (addonManager.dragActive == false) {
          $(addonManager.dragObj).removeClass('active');
        }
      }, 500);
    }
  },
  fileSelectHandler: function fileSelectHandler(e) {
    addonManager.fileDragHover(e);
    $(addonManager.dragObj).removeClass('active');
    var files = e.target.files || e.dataTransfer.files;
    for (var i = 0, f; f = files[i]; i++) {
      if (f.name.endsWith('.oxa') || f.name.endsWith('.zip')) {
        addonManager.dragUploadAddon(files);
      } else {
        notify(NOTIFY_ERROR, 'Please drop oxymora addons only!');
      }
    }
  }
};

var dashboard = {
  '_widgetContainer': null,
  'dashboardwidgets': null,
  'widgets': null,

  'init': function init(widgetContainer) {
    var me = this;
    me._widgetContainer = $(widgetContainer);
    me._getAllWidgets(function (success, data) {
      if (!success) {
        alert('Error while loading Widgets!');return;
      }
      me.widgets = data.map(function (item) {
        return new RootWidget(item);
      });
      me.updateWidgets(function () {
        me._updateDOM();
      });
    });
  },

  'updateWidgets': function updateWidgets(cb) {
    var me = this;
    me._getDashboardWidgets(function (success, data) {
      if (!success) {
        alert('Error while loading Widgets!');return;
      }
      me.dashboardwidgets = data.map(function (item) {
        return new Widget(item);
      });
      if (cb) cb();
    });
  },

  'addWidget': function addWidget(widget, cb) {
    var me = this;
    $.get('php/ajax_widgets.php', { 'action': 'add', 'widget': widget }, function (data) {
      var dataobj = JSON.parse(data);
      if (dataobj.error) {
        if (cb) {
          cb(false, dataobj.data);
        }return;
      }

      var newWidget = new Widget(dataobj.data);
      me.dashboardwidgets.push(newWidget);
      newWidget.html().insertBefore(me._widgetContainer.find('.widget').last());

      if (cb) {
        cb(true, dataobj.data);
      }
    });
  },

  'deleteWidget': function deleteWidget(widgetObj, cb) {
    var me = this;
    $.get('php/ajax_widgets.php', { 'action': 'delete', 'widget': widgetObj.id }, function (data) {
      var dataobj = JSON.parse(data);
      if (dataobj.error) {
        if (cb) {
          cb(false, dataobj.data);
        }return;
      }

      me.dashboardwidgets = me.dashboardwidgets.filter(function (item) {
        return item.obj.id != widgetObj.id;
      });

      if (cb) {
        cb(true, dataobj.data);
      }
    });
  },

  'moveWidgetUp': function moveWidgetUp(widgetObj, cb) {
    var me = this;
    $.get('php/ajax_widgets.php', { 'action': 'up', 'widget': widgetObj.id }, function (data) {
      var dataobj = JSON.parse(data);
      if (dataobj.error) {
        if (cb) {
          cb(false, dataobj.data);
        }return;
      }
      if (cb) {
        cb(true, dataobj.data);
      }
    });
  },

  'moveWidgetDown': function moveWidgetDown(widgetObj, cb) {
    var me = this;
    $.get('php/ajax_widgets.php', { 'action': 'down', 'widget': widgetObj.id }, function (data) {
      var dataobj = JSON.parse(data);
      if (dataobj.error) {
        if (cb) {
          cb(false, dataobj.data);
        }return;
      }
      if (cb) {
        cb(true, dataobj.data);
      }
    });
  },

  '_getDashboardWidgets': function _getDashboardWidgets(cb) {
    $.get('php/ajax_widgets.php', { 'action': 'getDashboard' }, function (data) {
      var dataobj = JSON.parse(data);
      if (dataobj.error) {
        if (cb) {
          cb(false, dataobj.data);
        }return;
      }
      if (cb) {
        cb(true, dataobj.data);
      }
    });
  },

  '_getAllWidgets': function _getAllWidgets(cb) {
    $.get('php/ajax_widgets.php', { 'action': 'get' }, function (data) {
      var dataobj = JSON.parse(data);
      if (dataobj.error) {
        if (cb) {
          cb(false, dataobj.data);
        }return;
      }
      if (cb) {
        cb(true, dataobj.data);
      }
    });
  },

  '_updateDOM': function _updateDOM() {
    var me = this;
    me._widgetContainer.html('');
    if (me.dashboardwidgets) {
      me.dashboardwidgets.forEach(function (item) {
        me._widgetContainer.append(item.html());
      });
    }
    me._widgetContainer.append(this._clearAddon());
  },

  '_clearAddon': function _clearAddon() {
    var me = this;
    var widgets = $("<ul/>");
    var backbutton = $('<li><i class="fa fa-chevron-circle-left" aria-hidden="true"></i><span> Back</span></li>').on('click', function () {
      $(this).parent().parent().parent().find('.widget-placeholder').fadeIn(200);
    });
    widgets.append(backbutton);
    if (me.widgets) {
      me.widgets.forEach(function (w) {
        var item = w.listHtml().on('click', function () {
          var element = this;
          me.addWidget(w.obj.name, function () {
            $(element).parent().parent().parent().find('.widget-placeholder').fadeIn(200);
          });
        });
        widgets.append(item);
      });
    }
    var clWidget = $("\n      <div class=\"widget\">\n      <div class=\"widget-placeholder\">Click to choose a Widget</div>\n      <div class=\"widget-select\"></div>\n      </div>");

    clWidget.find('.widget-select').append(widgets);
    clWidget.find('.widget-placeholder').on('click', function () {
      $(this).fadeOut(200);
    });

    return clWidget;
  }

};

var Widget = function Widget(obj) {
  this.obj = obj;
  this.html = function () {
    var html = $("\n        <div class=\"widget\">\n        <iframe scrolling=\"no\" class=\"widgetIframe\" frameborder=\"0\" src=\"addon/" + this.obj.widget + "/index.php\"></iframe>\n        <a class=\"delete\" href=\"#\">Remove</a>\n        <a class=\"up\" href=\"#\"><i class=\"fa fa-chevron-up\" aria-hidden=\"true\"></i></a>\n        <a class=\"down\" href=\"#\"><i class=\"fa fa-chevron-down\" aria-hidden=\"true\"></i></a>\n        </div>\n        ");
    html.find('.up').on('click', function () {
      var prevItem = html.prev();
      if (prevItem.length) {
        prevItem.before(html.detach());
        dashboard.moveWidgetUp(obj, function (success) {});
      }
    });
    html.find('.down').on('click', function () {
      var nextItem = html.next();
      if (nextItem.length && !$(nextItem[0]).find('.widget-placeholder').length) {
        nextItem.after(html.detach());
        dashboard.moveWidgetDown(obj, function (success) {});
      }
    });
    html.find('.delete').on('click', function () {
      html.remove();
      dashboard.deleteWidget(obj, function (success) {});
    });
    return html;
  };
};

var RootWidget = function RootWidget(obj) {
  this.obj = obj;
  this.listHtml = function () {
    var img = this.obj.icon ? this.obj.iconUrl : "img/coffee.svg";
    return $("\n          <li>\n          <img src=\"" + img + "\" />\n          <span>" + this.obj.config.menuentry.displayname + "</span>\n          </li>\n          ");
  };
};

var memberManager = {
  'groups': [],

  //  ============================================
  //  SETUP
  //  ============================================
  init: function init() {
    initControls();

    var colors = [{ 'value': 'rgb(101, 191, 129)', 'text': 'green' }, { 'value': 'rgb(237, 165, 43)', 'text': 'orange' }, { 'value': 'rgb(226, 93, 161)', 'text': 'purple' }, { 'value': 'rgb(77, 186, 193)', 'text': 'blue' }, { 'value': 'rgb(191, 127, 80)', 'text': 'brown' }];

    function initControls() {
      $('#addUserButton').on('click', function () {
        showAddUserDialog();
      });

      $('#addGroupButton').on('click', function () {
        showAddGroupDialog();
      });

      $('#userContainer').on('click', '.user-item .delete', function () {
        showDeleteUserDialog($(this).parent().parent());
      });

      $('#userContainer').on('click', '.user-item .edit', function () {
        showEditUserDialog($(this).parent().parent());
      });

      $('#groupContainer').on('click', '.group-item button', function () {
        var item = $(this).parent().parent();
        var id = item.data('groupid');
        var action = $(this).data('action');
        groupButtonHandler(id, action, item);
      });
    }

    function showDeleteUserDialog(item) {
      var html = lightboxQuestion('Delete User');
      showLightbox(html, function (res, lbdata) {
        if (res) {
          memberManager.removeUser(item.data('id'), function (res) {
            if (res) item.remove();
          });
        }
      }, null, "Delete");
    }

    function showEditUserDialog(item) {
      var groups = [];console.log(memberManager.groups);
      memberManager.groups.forEach(function (group) {
        groups.push({ 'value': group.id, 'text': group.name, 'selected': group.id == item.data('group') });
      });

      var html = lightboxQuestion('Edit User');
      html += lightboxInput('username', 'text', 'Username', item.data('username'));
      html += lightboxInput('email', 'email', 'E-Mail', item.data('email'));
      html += lightboxInput('image', 'file', 'Image');
      html += lightboxInput('password', 'password', 'New Password');
      // html += lightboxInput('password_repeat', 'password', 'New Password repeat');
      html += lightboxSelect('groupid', groups, 'Group', item.data('group'));

      showLightbox(html, function (res, lbdata) {
        if (res) {
          memberManager.editUser(item.data('id'), lbdata['username'], lbdata['password'], lbdata['email'], lbdata['image'], lbdata['groupid'], function (success, message) {
            if (!success) {
              notify(NOTIFY_ERROR, message);
              return;
            }
            item.before(message);
            item.remove();
          });
        }
      }, null, "Save", "Cancel");
    }

    function showAddUserDialog() {
      var groups = [];console.log(memberManager.groups);
      memberManager.groups.forEach(function (group) {
        groups.push({ 'value': group.id, 'text': group.name });
      });

      var html = lightboxQuestion('Add new User');
      html += lightboxInput('username', 'text', 'Username');
      html += lightboxInput('email', 'email', 'E-Mail');
      html += lightboxInput('image', 'file', 'Image');
      html += lightboxInput('password', 'password', 'Password');
      // html += lightboxInput('password_repeat', 'password', 'Password repeat');
      html += lightboxSelect('groupid', groups, 'Group');

      showLightbox(html, function (res, lbdata) {
        if (res) {
          memberManager.addUser(lbdata['username'], lbdata['password'], lbdata['email'], lbdata['image'], lbdata['groupid'], function (success, message) {
            if (!success) {
              notify(NOTIFY_ERROR, message);
              return;
            }
            $('#userContainer').append(message);
          });
        }
      }, null, "Add", "Cancel");
    }

    function showAddGroupDialog() {
      var html = lightboxQuestion('Add new Group');
      html += lightboxInput('name', 'text', 'Name');
      html += lightboxSelect('color', colors, 'Color');
      showLightbox(html, function (res, lbdata) {
        if (res) {
          memberManager.addGroup(lbdata['name'], lbdata['color'], function (success, message) {
            if (!success) {
              notify(NOTIFY_ERROR, message);
              return;
            }
            $('#groupContainer').append(message);
          });
        }
      }, null, "Add", "Cancel");
    }

    function groupButtonHandler(id, action, item) {
      (function () {
        switch (action) {
          case 'delete':
            var html = lightboxQuestion('Delete Group?');
            showLightbox(html, function (res, lbdata) {
              if (res) {
                memberManager.removeGroup(id, function (success, message) {
                  if (!success) {
                    notify(NOTIFY_ERROR, message);
                    return;
                  }
                  $(".group-item[data-groupid='" + id + "']").remove();
                });
              }
            }, null, "Delete", "Cancel");
            break;

          case 'premission':
            var yhtml = "";
            var lastPrefix = null;
            memberManager.findGroup(id).permissions.filter(function (a, b) {
              if (a.key < b.key) return -1;
              if (a.key > b.key) return 1;
              return 0;
            });
            memberManager.findGroup(id).permissions.forEach(function (permission) {
              var prefix = permission.key.split('_')[0];
              if (prefix !== lastPrefix) {
                yhtml += lightboxQuestion(prefix.ucfirst());
                lastPrefix = prefix;
              }
              yhtml += lightboxCheckbox(permission.key, permission.title, permission.active);
            });
            showLightbox(yhtml, function (res, lbdata) {
              if (res) {
                var activePermissions = [];
                for (key in lbdata) {
                  if (lbdata.hasOwnProperty(key) && lbdata[key] === true) {
                    activePermissions.push(key);
                  }
                }console.log(activePermissions);
                memberManager.groupSavePermission(id, activePermissions, function (data) {
                  if (data.error) {
                    notify(NOTIFY_ERROR, data.data);
                    return;
                  } else {
                    memberManager.refreshGroups(function () {
                      notify(NOTIFY_SUCCESS, "Successful saved!");
                    });
                  }
                });
              }
            }, null, "Save", "Cancel");
            break;

          case 'edit':
            var groupName = memberManager.getGroupName(item);
            var groupColor = memberManager.getGroupColor(item);
            var _colors = colors.map(function (item) {
              if (item.value == groupColor) item.selected = true;
              return item;
            });

            var xhtml = lightboxQuestion('Edit Group');
            xhtml += lightboxInput('name', 'text', 'Name', groupName);
            xhtml += lightboxSelect('color', colors, 'Color', _colors);
            showLightbox(xhtml, function (res, lbdata) {
              if (res) {
                memberManager.editGroup(id, lbdata['name'], lbdata['color'], function (success, message) {
                  if (!success) {
                    notify(NOTIFY_ERROR, message);
                    return;
                  }
                  memberManager.updateUserColors(id, lbdata['color']);
                  $(".group-item[data-groupid='" + id + "']").after(message).remove();
                });
              }
            }, null, "Edit", "Cancel");
            break;
        }
      })();
    }

    memberManager.refreshGroups();
  },
  updateUserColors: function updateUserColors(groupid, newcolor) {
    $('#userContainer').find('.user-item[data-group=\'' + groupid + '\']').each(function () {
      $(this).find('.info h3').css('background', newcolor);
    });
  },
  addUser: function addUser(username, password, email, image, groupid, cb) {
    var formData = new FormData();
    formData.append("a", 'addMember');
    formData.append("username", username);
    formData.append("password", password);
    formData.append("email", email);
    formData.append("groupid", groupid);
    if (image) {
      formData.append("image", image);
    }
    $.ajax({
      url: 'php/ajax_memberManager.php',
      type: 'post',
      success: function success(data) {
        var dataobj = JSON.parse(data);
        if (dataobj.error) {
          if (cb) {
            cb(false, dataobj.data);
          }return;
        }
        if (cb) {
          cb(true, dataobj.data);
        }
      },
      error: function error() {
        alert("Something went horribly wrong!");
      },
      data: formData,
      mimeTypes: "multipart/form-data",
      cache: false,
      contentType: false,
      processData: false
    }, 'json');
  },
  editUser: function editUser(id, username, password, email, image, groupid, cb) {
    var formData = new FormData();
    formData.append("a", 'editMember');
    formData.append("id", id);
    formData.append("username", username);
    formData.append("password", password);
    formData.append("email", email);
    formData.append("groupid", groupid);
    if (image) {
      formData.append("image", image);
    }
    $.ajax({
      url: 'php/ajax_memberManager.php',
      type: 'post',
      success: function success(data) {
        var dataobj = JSON.parse(data);
        if (dataobj.error) {
          if (cb) {
            cb(false, dataobj.data);
          }return;
        }
        if (cb) {
          cb(true, dataobj.data);
        }
      },
      error: function error() {
        alert("Something went horribly wrong!");
      },
      data: formData,
      mimeTypes: "multipart/form-data",
      cache: false,
      contentType: false,
      processData: false
    }, 'json');
  },
  removeUser: function removeUser(id, cb) {
    $.get('php/ajax_memberManager.php', { 'a': 'removeMember', 'id': id }, function (data) {
      var dataobj = JSON.parse(data);
      if (dataobj.error) {
        if (cb) {
          cb(false, dataobj.data);
        }return;
      }
      if (cb) {
        cb(true, dataobj.data);
      }
    });
  },
  groupSavePermission: function groupSavePermission(id, permissions, cb) {
    $.get('php/ajax_memberManager.php', { 'a': 'savePermissions', 'id': id, 'permissions': permissions }, function (data) {
      var dataobj = JSON.parse(data);
      if (dataobj.error) {
        if (cb) {
          cb(false, dataobj.data);
        }return;
      }
      if (cb) {
        cb(true, dataobj.data);
      }
    });
  },
  getGroupColor: function getGroupColor(groupElement) {
    return groupElement.find('.info i').css('background-color');
  },
  getGroupName: function getGroupName(groupElement) {
    return groupElement.find('.info span').text();
  },
  refreshGroups: function refreshGroups(cb) {
    $.get('php/ajax_memberManager.php', { 'a': 'getGroups' }, function (data) {
      var dataobj = JSON.parse(data);
      if (dataobj.error) {
        notify(NOTIFY_ERROR, dataobj.data);if (cb) {
          cb(false);
        }return;
      }
      memberManager.groups = dataobj.data;
      if (cb) {
        cb(true);
      }
    });
  },
  findGroup: function findGroup(id) {
    var group = null;
    memberManager.groups.forEach(function (item) {
      if (item.id == id) group = item;
    });
    return group;
  },
  addGroup: function addGroup(name, color, cb) {
    $.get('php/ajax_memberManager.php', { 'a': 'addGroup', 'name': name, 'color': color }, function (data) {
      var dataobj = JSON.parse(data);
      if (dataobj.error) {
        if (cb) {
          cb(false, dataobj.data);
        }return;
      }
      memberManager.refreshGroups(function () {
        if (cb) {
          cb(true, dataobj.data);
        }
      });
    });
  },
  removeGroup: function removeGroup(id, cb) {
    $.get('php/ajax_memberManager.php', { 'a': 'removeGroup', 'id': id }, function (data) {
      var dataobj = JSON.parse(data);
      if (dataobj.error) {
        if (cb) {
          cb(false, dataobj.data);
        }return;
      }
      memberManager.refreshGroups(function () {
        if (cb) {
          cb(true, dataobj.data);
        }
      });
      if (cb) {
        cb(true, dataobj.data);
      }
    });
  },
  editGroup: function editGroup(id) {
    var name = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : null;
    var color = arguments.length > 2 && arguments[2] !== undefined ? arguments[2] : null;
    var cb = arguments.length > 3 && arguments[3] !== undefined ? arguments[3] : null;

    $.get('php/ajax_memberManager.php', { 'a': 'editGroup', 'id': id, 'name': name, 'color': color }, function (data) {
      var dataobj = JSON.parse(data);
      if (dataobj.error) {
        if (cb) {
          cb(false, dataobj.data);
        }return;
      }
      memberManager.refreshGroups(function () {
        if (cb) {
          cb(true, dataobj.data);
        }
      });
      if (cb) {
        cb(true, dataobj.data);
      }
    });
  }
};

var pageEditor = {

  //  ============================================
  //  SETUP
  //  ============================================
  "currHref": '',
  "pageEditorPreview": '',
  init: function init() {
    pageEditor.currHref = $(location).attr('href').replace(/[^\/]*$/, '');
    pageEditor.pageEditorPreview = $("#pageEditorPreview");
    pageEditor.pageEditorPreview.on('load', function () {
      pageEditor.findElements();
      pageEditor.addIframeHandler();
      pageEditor.page_plugins();
    });
  },


  //  ============================================
  //  Open in new Window
  //  ============================================

  openWindowPreview: function openWindowPreview() {
    var insideContainer = $('.pageGenerator .preview');
    var menuContainer = $('.pageGenerator .menu');
    var pageEditorWindow = $(window.open("", "MsgWindow", "width=1200,height=800"));
    var head = $(pageEditorWindow[0].document.head);
    head.append("<link rel='stylesheet' href='" + pageEditor.currHref + "css/content.css' type='text/css' media='screen'>");
    var body = $(pageEditorWindow[0].document.body);
    body.css('overflow', 'hidden');
    body.css('margin', 0);
    body.css('padding', 0);
    body.append(pageEditor.pageEditorPreview);
    insideContainer.fadeOut(200, function () {
      menuContainer.css('transition', "ease-in 0.2s");
      menuContainer.css('width', "100%");
    });
    $(pageEditorWindow).on('unload', function () {
      insideContainer.append(pageEditor.pageEditorPreview);
      menuContainer.css('transition', "ease-in 0.2s");
      menuContainer.css('width', "30%");
      setTimeout(function () {
        insideContainer.fadeIn(200);
      }, 300);
    });
  },


  //  ============================================
  //  PageEditor Save
  //  ============================================

  save: function save(callback) {
    pageEditor.findIframeElements();

    var plugins = [];
    $(pageEditor.pageEditorPlugins).each(function () {
      var pluginInfo = {};
      pluginInfo['id'] = $(this).data('id');
      pluginInfo['plugin'] = $(this).data('plugin');
      pluginInfo['area'] = pageEditor.getPluginArea(this);
      pluginInfo['settings'] = pageEditor.getPluginSettings(this);
      plugins.push(pluginInfo);
    });

    var data = {
      "url": pageEditor.getUrl(),
      "plugins": plugins
    };

    $.ajax({
      dataType: "json",
      method: "POST",
      url: 'php/ajax_pageEditor.php?a=save',
      data: data,
      success: function success(data) {
        if (data.error) {
          callback(false, data.data);
        } else {
          callback(true, null);
        }
      },
      error: function error() {
        callback(false, null);
      }
    });
  },


  //  ============================================
  //  SIDEPAGE
  //  ============================================

  page_settings: function page_settings(plugin, pluginid, callback, settings) {
    var currSettings = settings == null ? [] : settings;
    pageEditor.pageEditorSidePage.animate({ 'opacity': 0 }, 500, function () {
      var html = "";
      $.post('php/ajax_pageEditor.php?a=pluginSettings', { 'plugin': encodeURIComponent(plugin), 'id': encodeURIComponent(pluginid) }, function (data) {
        if (data.error == false) {

          // Add all the Settings Input fields and handle if there are no settings
          if (data.data != null && data.data.length > 0) {
            data.data.forEach(function (setting) {
              var value = pageEditor.getSettingsValue(currSettings, setting.key);
              html += pageEditor.addSettingInput(setting, value);
            });
          } else {
            callback(true, []);
            return;
          }

          // Create Submit and Cancel Button
          html += '<button class="oxbutton settings-save">Save</button>';
          html += '<button class="oxbutton settings-cancel">Cancel</button>';
        }

        //  ADD HTML
        pageEditor.pageEditorSidePage.html(html);

        // ADD HANDLER
        pageEditor.page_addSettingHandler(pageEditor.pageEditorSidePage);
        pageEditor.pageEditorSidePage.find('.addListItem').on('click', function () {
          var parent = $(this).parent();
          var key = parent.data('key');
          var type = parent.data('type');
          var html = pageEditor.createItemList(key, pageEditor.getItemListNr(parent), type);
          var element = $(html).insertBefore($(this));
          pageEditor.page_addSettingHandler(element);
        });
        pageEditor.pageEditorSidePage.find('.settings-save').on('click', function () {
          callback(true, pageEditor.getSettingData());
        });
        pageEditor.pageEditorSidePage.find('.settings-cancel').on('click', function () {
          callback(false, null);
        });

        pageEditor.pageEditorSidePage.animate({ 'opacity': 1 }, 500, function () {
          // loaded
        });
      }, "json");
    });
  },
  page_addSettingHandler: function page_addSettingHandler(item) {
    item.find("input[data-oxytype='bool']").each(function () {
      $(this).on('change', function () {
        var val = $(this).prop("checked") ? 1 : 0;
        $(this).val(val);
      });
    });
    item.find("input[data-oxytype='file']").each(function () {
      fileSelector.init(this);
    });
    item.find("button.deleteItem").click(function () {
      this.parentElement.remove();
    });
  },
  page_plugins: function page_plugins() {
    pageEditor.pageEditorSidePage.animate({ 'opacity': 0 }, 500, function () {
      var html = '<div class="plugins">';
      $.getJSON("php/ajax_pageEditor.php?a=getPlugins", function (data) {
        if (data.error == false) {

          // list all plugins
          data.data.forEach(function (plugin) {
            html += '<div data-name="' + plugin.name + '" draggable="true" class="plugin"><div class="name">' + plugin.config.displayname + '</div>';
            if (plugin.thumb == true) {
              html += '<div class="thumb" style="background-image:url(../' + plugin.thumbUrl + ')">&nbsp;</div>';
            }
            html += '</div>';
          });
        }

        html += '</div>';
        pageEditor.pageEditorSidePage.html(html);
        pageEditor.addMenuPluginHandler();
        pageEditor.pageEditorSidePage.animate({ 'opacity': 1 }, 500, function () {
          // loaded
        });
      });
    });
  },
  getItemListNr: function getItemListNr(list) {
    var id = 0;
    var items = list.find('.setting');
    var freeFound = false;
    while (!freeFound) {
      freeFound = true;
      items.each(function () {
        if (id == $(this).data('listitemid')) freeFound = false;
      });
      if (!freeFound) id++;
    }
    return id;
  },
  addSettingInput: function addSettingInput(setting, value, list, countingListItemId) {
    value = value == null ? "" : value;
    list = list == null ? "" : list;
    countingListItemId = countingListItemId == null ? "" : countingListItemId;

    var isList = Object.prototype.toString.call(setting.type) === '[object Array]';
    var addClass = isList ? " list" : "";

    var html = '<div class="setting' + addClass + '" data-listitemid="' + countingListItemId + '" data-list="' + list + '" data-key="' + setting.key + '" data-type="' + (isList ? JSON.stringify(setting.type).escapeHtml() : setting.type) + '">';
    html += '<h2 class="oxlabel' + addClass + '">' + setting.displayname + '</h2>';
    html += '<p class="oxdescription' + addClass + '">' + setting.description + '</p>';

    // IF LIST
    if (isList) {

      value = Object.prototype.toString.call(value) === '[object Array]' ? value : [];
      var listNr = 0;
      value.forEach(function (val) {
        html += pageEditor.createItemList(setting.key, listNr++, setting.type, val);
      });

      html += '<button class="oxbutton rightBlock addListItem">Add</button>';
    } else {

      value = $("<div>").text(value).html();
      value = value.replace(/["']/g, "&quot;");
      switch (setting.type) {
        case 'textarea':
          // escape value
          html += '<textarea class="settingbox oxinput">' + value + '</textarea>';
          break;
        case 'file':
          // escape value
          html += '<input class="settingbox oxinput" data-oxytype="file" type="text" value="' + value + '"></input>';
          break;
        case 'bool':
          // escape value
          html += '<input class="settingbox oxinput" data-oxytype="bool" type="checkbox"' + (value == 1 ? " checked" : "") + ' value="' + value + '"></input>';
          break;
        case 'text':
        default:
          // escape value
          html += '<input class="settingbox oxinput" type="text" value="' + value + '"></input>';
      }
    }

    html += "</div><br>";
    return html;
  },
  createItemList: function createItemList(listkey, listNr, items, values) {
    values = values ? values : [];
    var html = "";
    html += '<div class="itemlist">';
    html += '<button class="deleteItem"><i class="fa fa-times" aria-hidden="true"></i></button>';
    items.forEach(function (input) {
      var val = pageEditor.getSettingsValue(values, input.key);
      html += pageEditor.addSettingInput(input, val, listkey, listNr);
    });
    html += '</div>';
    return html;
  },
  getSettingData: function getSettingData() {
    var settings = [];
    pageEditor.pageEditorSidePage.find('.setting').each(function (index) {
      var setting = $(this);
      var keyValueObject = {
        "settingkey": setting.data('key'),
        "settingtype": setting.data('type'),
        "settingvalue": null
      };
      // If list than type list
      keyValueObject.settingtype = Object.prototype.toString.call(keyValueObject.settingtype) === '[object Array]' ? "list" : keyValueObject.settingtype;

      switch (setting.data('type')) {
        case 'textarea':
          keyValueObject.settingvalue = setting.find('.settingbox').val();
          break;
        case 'text':
        default:
          keyValueObject.settingvalue = setting.find('.settingbox').val();
      }

      if (setting.data('list') != "") {
        settings = pageEditor.getSettingDataPushToList(settings, setting.data('list'), setting.data('listitemid'), keyValueObject);
      } else {
        settings.push(keyValueObject);
      }
    });

    return settings;
  },
  getSettingDataPushToList: function getSettingDataPushToList(haystack, list, listitemid, valuePair) {
    var found = false;
    haystack.map(function (item) {
      if (item.settingkey === list) {
        if (Object.prototype.toString.call(item.settingvalue) !== '[object Array]') {
          item.settingvalue = [];
        }
        found = true;
      }
      return item;
    });
    if (!found) {
      haystack.push({
        "settingkey": list,
        "settingtype": 'list', // <= added this line, not tested.. :)
        "settingvalue": []
      });
    }
    haystack.map(function (item) {
      if (item.settingkey === list) {
        if (!item.settingvalue[listitemid]) item.settingvalue[listitemid] = [];
        item.settingvalue[listitemid].push(valuePair);
      }
      return item;
    });
    return haystack;
  },
  findElements: function findElements() {
    // PREVIEW IFRAME STUFF
    pageEditor.findIframeElements();
    // LIGHTBOX STUFF
    pageEditor.pageEditorSidePage = lightboxDialog.contents().find('.menu');
  },


  'pageEditorAreas': null,
  'pageEditorPlugins': null,
  findIframeElements: function findIframeElements() {
    pageEditor.pageEditorAreas = pageEditor.pageEditorPreview.contents().find('.oxymora-area');
    pageEditor.pageEditorPlugins = pageEditor.pageEditorPreview.contents().find(".oxymora-plugin[data-deleted!=true]");
  },


  //  ============================================
  //  HANDLER
  //  ============================================
  lastDraggedPlugin: null,

  addMenuPluginHandler: function addMenuPluginHandler() {
    pageEditor.pageEditorSidePage.find('.plugin').on('dragstart', pageEditor.menu_plugin_dragstartHandler);
    pageEditor.pageEditorSidePage.find('.plugin').on('dragend', pageEditor.menu_plugin_dragendHandler);
  },
  menu_plugin_dragstartHandler: function menu_plugin_dragstartHandler() {
    pageEditor.lastDraggedPlugin = $(this);
    $(this).css("border-color", "rgb(255, 0, 168)");
    $(this).find('.name').css("color", "rgb(255, 140, 240)");
  },
  menu_plugin_dragendHandler: function menu_plugin_dragendHandler() {
    $(this).css("border-color", "rgb(11, 118, 224)");
    $(this).find('.name').css("color", "white");
  },


  //  ============================================
  //  IFRAME HANDLER
  //  ============================================
  dropTarget: null,
  dropIsActive: null,
  addIframeHandler: function addIframeHandler() {
    // IFrame Handler
    pageEditor.pageEditorPreview.contents().find('html').on('drop', pageEditor.iframe_dropHandler);

    // Area Handler
    pageEditor.pageEditorAreas.each(function () {
      $(this).on('dragleave', function (e) {
        e.preventDefault();
        if (e.target === this) {
          pageEditor.iframe_area_dragleaveHandler(this, e);
        }
      }).on('dragover', function (e) {
        e.preventDefault();
      }).on('dragenter', function (e) {
        e.preventDefault();
        if (e.target === this) {
          pageEditor.iframe_area_dragenterHandler(this, e);
        }
      });
    });

    // Plugin Handler
    pageEditor.pageEditorPlugins.each(function () {
      pageEditor.addPluginHandler($(this));
    });
  },


  // ----------------------
  //  Plugin Handler
  // ----------------------
  iframe_plugin_editHandler: function iframe_plugin_editHandler() {
    // todo: plugin edit handler
    var plugin = $(this).parent().parent();
    var pluginId = plugin.data('id');
    var pluginName = plugin.data('plugin');
    var settings = pageEditor.getPluginSettings(plugin);
    pageEditor.page_settings(pluginName, pluginId, function (success, settings) {
      if (success) {
        pageEditor.addPluginPreview(pluginName, pluginId, settings, plugin, function () {
          plugin.remove();
          pageEditor.page_plugins();
        });
      } else {
        pageEditor.page_plugins();
      }
    }, settings);
  },
  iframe_plugin_deleteHandler: function iframe_plugin_deleteHandler() {
    // todo: nicer Confirm..
    if (confirm("Sure you want to delete?")) {
      pageEditor.deletePlugin($(this).parent().parent());
    }
  },
  iframe_plugin_dragoverHandler: function iframe_plugin_dragoverHandler(plugin, e) {
    if (!pageEditor.isDropMarker()) pageEditor.dropMarker(plugin);
  },
  iframe_plugin_dragenterHandler: function iframe_plugin_dragenterHandler(plugin, e) {
    pageEditor.dropMarker(plugin);
  },


  // ----------------------
  //  Iframe "html" handler
  // ----------------------
  iframe_dropHandler: function iframe_dropHandler(e) {
    pageEditor.removeDropMarker();
    var target = pageEditor.dropTarget;
    pageEditor.dropTarget = null;
    var pluginName = pageEditor.lastDraggedPlugin.data('name');

    // Show Settings Page and wait for Callback
    pageEditor.page_settings(pluginName, null, function (success, settings) {
      // console.log("Add Plugin Settings:",settings);
      //  If success add the Preview Plugin, if not just back to plugin page
      if (success) {
        pageEditor.addPluginPreview(pluginName, "", settings, target, function (success, errormsg) {
          console.log("Add Plugin Success:" + success);
          console.log("Add Plugin Error:" + errormsg);
          pageEditor.page_plugins();
        });
      } else {
        pageEditor.page_plugins();
      }
    });
  },
  iframe_dragleaveHandler: function iframe_dragleaveHandler(plugin, e) {
    pageEditor.removeDropMarker();
  },


  // ----------------------
  //  Area handler
  // ----------------------
  iframe_area_dragenterHandler: function iframe_area_dragenterHandler(area, e) {
    $(area).addClass('dragOver');
    pageEditor.dropMarker(area, true, true);
  },
  iframe_area_dragleaveHandler: function iframe_area_dragleaveHandler(area, e) {
    $(area).removeClass('dragOver');
    pageEditor.removeDropMarker();
    pageEditor.dropTarget = null;
  },


  //  ============================================
  //  PLUGIN FUNCTIONS
  //  ============================================
  getPluginSettings: function getPluginSettings(plugin) {
    return $(plugin).data('settings');
  },
  getPluginArea: function getPluginArea(plugin) {
    return $(plugin).parent().data('name');
  },
  getSettingsValue: function getSettingsValue(settings, key) {
    var returnValue = null;
    if (Array.isArray(settings)) {
      settings.forEach(function (element, index) {
        if (element.settingkey === key) {
          returnValue = element.settingvalue;
          // there is no break option, wtf !??
        }
      });
    }
    return returnValue;
  },
  addPluginHandler: function addPluginHandler(plugin) {
    plugin.find('.oxymora-plugin-edit').on('click', pageEditor.iframe_plugin_editHandler);
    plugin.find('.oxymora-plugin-delete').on('click', pageEditor.iframe_plugin_deleteHandler);
    plugin.on('dragover', function (e) {
      e.preventDefault();
      pageEditor.iframe_plugin_dragoverHandler(this, e);
    }).on('dragenter', function (e) {
      e.preventDefault();
      pageEditor.iframe_plugin_dragenterHandler(plugin, e);
    });
  },
  addPluginPreview: function addPluginPreview(plugin, id, settings, target, callback) {
    var data = {
      "id": id,
      "plugin": plugin,
      "settings": settings
    };
    $.ajax({
      dataType: "json",
      method: "POST",
      url: 'php/ajax_pageEditor.php?a=renderPluginPreview',
      data: data,
      success: function success(data) {
        var plugin = $(data.data);
        pageEditor.addPluginHandler(plugin);
        if (target.hasClass('oxymora-area')) {
          target.prepend(plugin);
          callback(true, null);
        } else if (target.hasClass('oxymora-plugin')) {
          plugin.insertAfter(target);
          callback(true, null);
        } else {
          callback(false, "Invalid Target!");
        }
      },
      error: function error() {
        callback(false, null);
      }
    });
  },
  dropMarker: function dropMarker(element, prepend) {
    var area = arguments.length > 2 && arguments[2] !== undefined ? arguments[2] : false;

    pageEditor.removeDropMarker(); // WHY DO U FUCK ME?
    pageEditor.dropTarget = $(element);
    pageEditor.dropIsActive = true;
    var display = area ? ' style="display:block;"' : ' style="display:block;"';
    var html = "<div" + display + " class='oxymora-drop-marker'>insert here</div>";
    if (prepend != null && prepend != false) {
      pageEditor.dropTarget.prepend(html);
    } else {
      pageEditor.dropTarget.append(html);
    }
  },
  isDropMarker: function isDropMarker() {
    return pageEditor.dropIsActive;
  },
  removeDropMarker: function removeDropMarker() {
    pageEditor.pageEditorPreview.contents().find('.oxymora-drop-marker').remove();
    pageEditor.dropIsActive = false;
  },
  deletePlugin: function deletePlugin(plugin) {
    plugin[0].dataset.deleted = true;
    plugin.css('display', 'none');
  },


  //  ============================================
  //  FUNCTIONS
  //  ============================================

  getUrl: function getUrl() {
    return $("#pageEditorPreview").data('url');
  }
};

var ua = window.navigator.userAgent;
var msie = ua.indexOf("MSIE ");

if (msie > 0 || !!navigator.userAgent.match(/Trident.*rv\:11\./)) {
  document.getElementById('fallback').style.display = "block";
}