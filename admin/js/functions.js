
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
		initNavItem();
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
	var divs = tabcontrol.find('.tabContent .tab');
	for(var i = 0; i < divs.length; i++){
		if(divs[i].dataset.tab === tab || i === tab){
			$(divs[i]).css("display", "block");
		}else{
			$(divs[i]).css("display", "none");
		}
	}
}





// =================================================
//  INTERFACE - NAVIGATION
// =================================================

function initNavItem(){
	sortNavItems();
	$(".navitem .buttonbar button").on('click', navItemButtonClick);
}

function sortNavItems(){
	$(".navitem").each(function(index){
		var item = $(this);
		var display = item.data('display');
		item.css("top", display * (item.outerHeight() + 10));
	});
}

function getPrevNavItem(item){
	var res = false;
	$(".navitem").each(function(index){
		var pitem = $(this);
		if((item.data("display")-1) === pitem.data("display")){
			res = pitem;
		}
	});
	return res;
}

function getNextNavItem(item){
	var res = false;
	$(".navitem").each(function(index){
		var pitem = $(this);
		if((item.data("display")+1) === pitem.data("display")){
			res = pitem;
		}
	});
	return res;
}

function navItemButtonClick(){
	var itemButton = $(this);
	var item = itemButton.parent().parent();
	var action = itemButton.data("action");
	$.get('php/ajax_navigation.php?id='+item.data("id")+'&action='+action, function(data){
		var data = JSON.parse(data);
		if(data.type === "success"){
			if(action === "displayUp"){
				var prev = getPrevNavItem(item);
				item.data("display", item.data("display") - 1);
				prev.data("display", prev.data("display") + 1);
				sortNavItems();
			}
			if(action === "displayDown"){
				var next = getNextNavItem(item);
				item.data("display", item.data("display") + 1);
				next.data("display", next.data("display") - 1);
				sortNavItems();
			}
		}
	});
}
