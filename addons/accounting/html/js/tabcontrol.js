// =================================================
//  INTERFACE - TABCONTROL
// =================================================
let tabControlActiveTab = null;

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
			tabControlActiveTab = divs[i];
			$(divs[i]).css("opacity", "1");
			$(divs[i]).css("z-index", "1");
			tabControlUpdateHeight();
		}else{
			$(divs[i]).css("opacity", "0");
			$(divs[i]).css("z-index", "-1");
		}
	}
}

function tabControlUpdateHeight(){
	$(tabControlActiveTab).parent().css("height", $(tabControlActiveTab).find('.dataContainer').outerHeight() + 30);
}


initTabcontrols('.tabContainer');
