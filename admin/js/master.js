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

// GET ADDON MENU ITEMS
addonMenu.loadMenuItems();

// Toggle Menu
 toggleMenu(0);






// SOME PROTOTYPE STUFF
String.prototype.ucfirst = function() {
    return this.charAt(0).toUpperCase() + this.slice(1);
}
