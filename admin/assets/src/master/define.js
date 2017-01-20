let sidemenu = $('#sidemenu')
let menuToggle = $('#menuToggle');
let header = $('#header');
let content = $('#content');
let preloader = $('#preloader');
let wrapper = $('#wrapper');
let lightbox = $('#lightbox');
let lightboxDialog = $('#lightbox .dialog');
let lightboxDialogContent = $('#lightbox .dialog .content');
let lightboxCancelBtn = $('#lightbox .dialog .cancel');
let lightboxOkBtn = $('#lightbox .dialog .success');
let addonTopic = $('#addonTopic');
let notifyBox = $("#notify");
let	defaultMenuWidth = sidemenu.width();




notifyBox[0].addEventListener('click', function(){notify_destroy(this.dataset.notifyid);});



String.prototype.htmlEncode = function(){
  return $('<div/>').text(this).html();
}

String.prototype.escapeHtml = function(){
  var map = {
    '&': '&amp;',
    '<': '&lt;',
    '>': '&gt;',
    '"': '&quot;',
    "'": '&#039;'
  };
  return this.replace(/[&<>"']/g, function(m) { return map[m]; });
}

String.prototype.htmlDecode = function(){
  return $('<div/>').html(this).text();
}
