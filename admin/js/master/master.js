// small Screen
let IS_MOBILE = ($(window).width() < 1000);

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

// PRELOADER
preloaderInit();

// GET ADDON MENU ITEMS
addonMenu.loadMenuItems();

// Toggle Menu
if(!IS_MOBILE)toggleMenu(0);



function test(){
  // console.log(lib);
  // console.log(images);
  // console.log(createjs);
  // console.log(ss);
  ss.stop();
}




// SOME PROTOTYPE STUFF
String.prototype.ucfirst = function() {
    return this.charAt(0).toUpperCase() + this.slice(1);
}
