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
    let container = $('<div/>').addClass('fileSelectorBox');
    return container;
  }



}
