
sidemenu = $('#sidemenu')
menuToggle = $('#menuToggle');
header = $('#header');
content = $('#content');
calcSize();

// LOAD FIRST PAGE
if(window.location.hash) {
     var hash = window.location.hash.substring(1);
     loadPage(hash);
 } else {
     loadPage('dashboard');
 }


$( window ).resize(function() {
  calcSize();
});


function calcSize(){
  content.css('height', ($(window).height() - header.height() - 20));
  content.css('width', ($(window).width() - 20));
  content.css('margin-top', (header.height() + 10));
}

function loadPage(page){
  content.load('pages/'+page+".php");
}
