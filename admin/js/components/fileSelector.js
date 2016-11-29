let fileSelector = {

  init(input){
    (function(_input){
      let input = $(_input);
      if(input.hasClass('fileSelector')) return;
      input.addClass('fileSelector');
      let button = $('<button/>').text('Select a File').addClass('fileSelector');
      let div = $("<div/>").css('position', 'relative').html(button);
      input.after(div);
      button.on('click', function(){fileSelector.openDialog(button, input);});
      input.hide();
    })(input);
  },

  openDialog(element, input){
    let container = fileSelector._container();
    element.before(container);
    container.fadeIn(200);
  },


  _container(width, height){
    // Mein Container
    let container = $('<div/>').addClass('fileSelectorBox');
    // Headbar
    let head = $('<header/>');
    head.append($('<span/>').text('Select a file ...'));
    head.append($('<button/>').html('<i class="fa fa-times" aria-hidden="true"></i>').addClass('close'));
    // Searchbar
    let search = $('<div/>').addClass('search-container');
    search.append($('<input/>').attr('placeholder', 'Search'));
    // File-Container
    let subcontainer = $('<div/>').addClass('container');

    let dircontainer = $('<div/>').addClass('dir-container');
    dircontainer.append(fileSelector._folder('back'));
    let filecontainer = $('<div/>').addClass('file-container');
    filecontainer.append(fileSelector._file('test.jpg'));
    filecontainer.append(fileSelector._file('test.jpg'));
    filecontainer.append(fileSelector._file('test.jpg'));
    filecontainer.append(fileSelector._file('test.jpg'));

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
    let folder = $('<div/>').addClass('file').html('<i class="fa fa-file-image-o" aria-hidden="true"></i><h3>'+name+"</h3>");
    return folder;
  }



}
