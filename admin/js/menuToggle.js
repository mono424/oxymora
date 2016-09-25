menuToggle.click(function(){
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
});
