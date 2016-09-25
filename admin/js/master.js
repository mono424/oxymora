// Menu Toggle Handler
menuToggle.click(toggleMenu);

// Widow resize Handler
$( window ).resize(function() {
  calcSize();
});

// Calulate Size
calcSize();

// LOAD FIRST PAGE
if(window.location.hash) {
     var hash = window.location.hash.substring(1);
     loadPage(hash);
 } else {
     loadPage('dashboard');
 }

function loadPage(page){
  content.load('pages/'+page+".php");
}
