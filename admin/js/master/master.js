// Menu Toggle Handler
menuToggle.click(toggleMenu);

// Widow resize Handler
$( window ).resize(function() {
  calcSize();
});

// Calulate Size
calcSize();

// HIDE MENU
if(!isSmallScreen) toggleMenu(0);

// LOAD FIRST PAGE
if(START_PAGE) {
     if(START_PAGE.startsWith('addon-')){
       loadAddonPage(START_PAGE.substring('addon-'.length));
     }else{
       loadPage(START_PAGE);
     }
 } else {
     loadPage('dashboard');
 }

// PRELOADER
preloaderInit();

// GET ADDON MENU ITEMS
addonMenu.loadMenuItems();



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
