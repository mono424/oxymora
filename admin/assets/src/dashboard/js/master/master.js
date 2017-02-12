// Menu Toggle Handler
menuToggle.click(toggleMenu);

// Widow resize Handler
$( window ).resize(function() {
  calcSize();
});

// Calulate Size
calcSize();

// HIDE MENU
if(!isSmallScreen){
  // Show Menu
  toggleMenu(0);
}else{
  // Hammerjs for gestures
  addHammerTime.call($('body').get(0));
  $('.side-container').children().each(addHammerTime);
}

// LOAD FIRST PAGE
if(typeof START_PAGE !== 'undefined') {
  if(START_PAGE.startsWith('addon-')){
    loadAddonPage(START_PAGE.substring('addon-'.length));
  }else{
    loadPage(START_PAGE);
  }
} else {
  loadPage('dashboard');
}

// PRELOADER
// preloaderInit();

// GET ADDON MENU ITEMS
addonMenu.loadMenuItems();

// SOME PROTOTYPE STUFF
String.prototype.ucfirst = function() {
  return this.charAt(0).toUpperCase() + this.slice(1);
}
