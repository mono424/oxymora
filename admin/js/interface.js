


function toggleMenu(){
	menuToggle.toggleClass('open');
	if(menuToggle.hasClass("open")){
		sidemenu.animate({"left": 0}, 500);
		header.animate({"width": (header.width() - sidemenu.width())}, 500);
		content.animate({"width": (content.outerWidth() - sidemenu.width())}, 500);
	}else{
		sidemenu.animate({"left": (-sidemenu.width())}, 500);
		header.animate({"width": (header.width() + sidemenu.width())}, 500);
		content.animate({"width": (content.outerWidth() + sidemenu.width())}, 500);
	}
}


function calcSize(){
	var sidemenuWidth = (sidemenu.position().left + sidemenu.width());
	header.css('width', ($(window).width() - sidemenuWidth));
  content.css('height', ($(window).height() - header.height() - 20));
  content.css('width', ($(window).width() - 20 - sidemenuWidth));
  content.css('margin-top', (header.height() + 10));
}
