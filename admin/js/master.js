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
     if(hash.startsWith('addon-')){
       loadAddonPage(hash.substring('addon-'.length));
     }else{
       loadPage(hash);
     }
 } else {
     loadPage('dashboard');
 }

 toggleMenu(0);
