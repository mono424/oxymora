
// =================================================
//  INTERFACE - GLOBAL
// =================================================

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


function loadPage(page){
	content.load('pages/'+page+".php", function(){
		initTabcontrols(".tabContainer");
	});
}


// =================================================
//  INTERFACE - TABCONTROL
// =================================================

function initTabcontrols(selector){
	$(selector + " ul li a").on('click', tabcontrolAnchorClick);

	$(selector).each(function(index){
		tabcontrolSelectTab($(this), 0);
	});
}

function tabcontrolAnchorClick(e){
	tabcontrolSelectTab($(this).parent().parent().parent(), this.dataset.tab);
}

function tabcontrolSelectTab(tabcontrol, tab){

	// SELECT THE MENUITEM
	var menuItems = tabcontrol.find('ul li a');
	for(var i = 0; i < menuItems.length; i++){
		if(menuItems[i].dataset.tab === tab || i === tab){
			$(menuItems[i]).addClass("active");
		}else{
			$(menuItems[i]).removeClass("active");
		}
	}


	// SHOW THE DIV
	var divs = tabcontrol.find('.tabContent div');
	for(var i = 0; i < divs.length; i++){
		if(divs[i].dataset.tab === tab || i === tab){
			$(divs[i]).css("display", "block");
		}else{
			$(divs[i]).css("display", "none");
		}
	}
}
