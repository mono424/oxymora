let fileSelector = {

  init(input){
    (function(_input){
      let input = $(_input);
      let element = $('<button/>').text('Select a File').addClass('fileSelector');
      input.after(element);
      input.hide();








    })(input);
  }

}
