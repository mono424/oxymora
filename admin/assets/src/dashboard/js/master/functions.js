
// =================================================
//  INTERFACE - GLOBAL
// =================================================

let menuVisible = false;
function toggleMenu(speed){
	speed = (speed === null) ? 500 : speed;
	menuToggle.toggleClass('open');
	if(menuToggle.hasClass("open")){
		if(isSmallScreen){
			sidemenu.css('width', $(window).width());
			sidemenu.animate({"left": 0}, speed);
		}else{
			sidemenu.css('width',defaultMenuWidth);
			sidemenu.animate({"left": 0}, speed);
		}
		header.animate({"width": (header.width() - sidemenu.width())}, speed);
		wrapper.animate({"width": (wrapper.outerWidth() - sidemenu.width())}, speed);
		lightbox.animate({"width": (lightbox.outerWidth() - sidemenu.width())}, speed);
		menuVisible = true;
	}else{
		if(isSmallScreen){
			sidemenu.width($(window).width());
			sidemenu.animate({"left": (-$(window).width())}, speed);
		}else{
			sidemenu.css('width',defaultMenuWidth);
			sidemenu.animate({"left": (-defaultMenuWidth)}, speed);
		}
		header.animate({"width": (header.width() + sidemenu.width())}, speed);
		wrapper.animate({"width": (wrapper.outerWidth() + sidemenu.width())}, speed);
		lightbox.animate({"width": (lightbox.outerWidth() + sidemenu.width())}, speed);
		menuVisible = false;
	}
}

let isSmallScreen = null;
function calcSize(){
	let oldSmallScreenValue = isSmallScreen;
	isSmallScreen = ($(window).width() < 750);

	if(isSmallScreen){
		sidemenu.css('width', $(window).width());
		if(!menuVisible){sidemenu.css('left', -sidemenu.width());}
	}

	let sidemenuWidth = (menuVisible) ? (sidemenu.position().left + sidemenu.width()) : 0;
	header.css('width', ($(window).width() - sidemenuWidth));
	wrapper.css('height', ($(window).height() - header.height() - 20));
	wrapper.css('width', ($(window).width() - 20 - sidemenuWidth));
	wrapper.css('margin-top', (header.height() + 10));
	lightbox.css('height', ($(window).height() - header.height()));
	lightbox.css('width', ($(window).width() - sidemenuWidth));
	lightbox.css('margin-top', (header.height()));
	tabControlUpdateHeight();
	if(menuVisible && isSmallScreen && oldSmallScreenValue===false){
		toggleMenu(0);
	}
}


function loadPage(page){
	if(isSmallScreen && menuVisible) toggleMenu();
	setPageUrl(page);
	preloadManager.show(function(){
		content.load('pages/'+page+".php", function(){
			preloadManager.hide(function(){});
			markNavItem(page, false)
			addHammerTime.call(content.get(0));
			content.children().each(addHammerTime);
			initTabcontrols(".tabContainer");
		});
	});
}

function loadAddonPage(addon){
	if(isSmallScreen && menuVisible)toggleMenu();
	setPageUrl("addon-"+addon);
	preloadManager.show(function(){
		content.load('pages/addon.php?addon='+addon, function(){
			preloadManager.hide(function(){
				initTabcontrols(".tabContainer");
			});
			markNavItem(addon, true)
		});
	});
}

function setPageUrl(page){
	var url = ROOT_DIR+"/"+page+".html";
	var title = "Oxymora | "+ucfirst(page);
	document.title = title;
	window.history.pushState({"html":$('body').html(),"pageTitle":title},"", url);
}

function markNavItem(page, PageIsAddon){
	$('.nav').each(function(){
		if((!PageIsAddon && $(this).attr('href') == "#"+page) || (PageIsAddon && $(this).attr('href') == "#addon-"+page)){
			$(this).addClass('active');
		}else{
			$(this).removeClass('active');
		}
	});
}

function ucfirst(string) {
	return string.charAt(0).toUpperCase() + string.slice(1);
}


// =================================================
//  INTERFACE - TABCONTROL
// =================================================
let tabControlActiveTab = null;

function initTabcontrols(selector){
	$(selector).find("ul li a").on('click', tabcontrolAnchorClick);

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


// =================================================
//  INTERFACE - SPINNER FOR BUTTONS OR OTHER STUFF
// =================================================

function spinner(){
	return `<div class="spinner">
	<div class="rect1"></div>
	<div class="rect2"></div>
	<div class="rect3"></div>
	<div class="rect4"></div>
	<div class="rect5"></div>
	</div>`;
}


// =================================================
//  INTERFACE - PRELOADER
// =================================================

let preloadManager = {
	show(cb){
		// TweenMax.fromTo(content, 0.5, {y: '0px '}, {y: '-'+content.outerWidth()+'px', ease: Power2.easeOut});
		content.fadeOut(200);
		setTimeout(function(){calcSize();if(cb){cb();}}, 500);
		// preloader.fadeIn(200, function(){
		// 	if(cb){cb();}
		// });
	},
	hide(cb){if(cb){cb();}
	// TweenMax.fromTo(content, 0.75, {y: '-'+content.outerWidth()+'px', opacity: 0}, {y: '0px', opacity: 1, ease: Power2.easeIn});
	content.fadeIn(500);
	calcSize();
	if(cb){setTimeout(function(){cb();}, 750);}
	// preloader.fadeOut(500, function(){
	// 	if(cb){cb();}
	// });
}
}


// =================================================
//  INTERFACE - NAVIGATION
// =================================================

function initNavItem(){
	sortNavItems();
	setNavItemButtonHandler($(".navitem"));
	$("#addNavButton").on('click', navItemAddButtonClick);
}

function setNavItemButtonHandler(item){
	item.find('.buttonbar button').on('click', navItemButtonClick);
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

function getAllNextNavItem(item){
	var res = [];
	$(".navitem").each(function(index){
		var pitem = $(this);
		if(item.data("display") < pitem.data("display")){
			res.push(pitem);
		}
	});
	return res;
}

function navItemAddButtonClick(){
	var html = lightboxInput("title", "text", "Title", "") + lightboxInput("url", "text", "Url", "");
	showLightbox(html,function(res, lbdata){
		if(res){
			addNavItem(lbdata['title'],lbdata['url']);
		}
	});
}

function addNavItem(title, url, callback){
	$.get('php/ajax_navigation.php?action=add&title='+encodeURIComponent(title)+'&url='+encodeURIComponent(url), function(data){
		var data = JSON.parse(data);
		if(data.type === "success"){
			let html = $(data.message);
			setNavItemButtonHandler(html);
			$("#navContainer").append(html);
			sortNavItems();
		}
		checkPageItemInNav();
		if(callback){callback(data.type);}
	});
}

function navItemButtonClick(){
	var itemButton = $(this);
	var item = itemButton.parent().parent();
	var action = itemButton.data("action");
	if(action === "edit"){
		var title = item.find(".title");
		var url = item.find(".url");
		var html = lightboxInput("title", "text", "", title.html()) + lightboxInput("url", "text", "", url.html());
		showLightbox(html,function(res, lbdata){
			if(res){
				navDoEdit(item, lbdata['title'], lbdata['url']);
			}
		});
	}else{
		if(action === "remove"){
			var html = lightboxQuestion("Sure you want to delete?");
			showLightbox(html,function(res, lbdata){
				if(res){navDoRequest(item, action);}
			});
		}else{
			navDoRequest(item, action);
		}
	}
}


function navDoRequest(item, action){
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
			if(action === "remove"){
				var nextItems = getAllNextNavItem(item);
				for(var i = 0; i < nextItems.length; i++){
					nextItems[i].data("display", nextItems[i].data("display") - 1);
				}
				item.remove();
				checkPageItemInNav();
				sortNavItems();
			}
		}
	});
}

function navDoEdit(item, title, url, cb){
	let _title = item.find('.title');
	let _url = item.find('.url');
	if(title === null) title = _title.text();
	if(url === null) url = _url.text();
	$.get('php/ajax_navigation.php?id='+item.data("id")+'&action=edit&title='+encodeURIComponent(title)+'&url='+encodeURIComponent(url), function(data){
		var data = JSON.parse(data);
		if(data.type === "success"){
			_title.html(title);
			_url.html(url);
		}
		if(cb) cb(data.type === "success");
	});
}



// =================================================
//  INTERFACE - DYNAMIC ADDON MENU
// =================================================

let addonMenu = {
	url: "php/ajax_addonMenu.php",

	loadMenuItems(){
		$.get(addonMenu.url, function(data){
			$('.addon-menu').remove();
			data = JSON.parse(data);
			if(data.data.length > 0){
				addonMenu.visible(true);
				data.data.reverse();
				data.data.forEach(function(item){
					addonTopic.after(addonMenu.createMenuItem(item.name, item.config.menuentry.displayname, item.config.menuentry.menuicon));
				});
			}else{
				addonMenu.visible(false);
			}
		});
	},

	visible(state){
		addonTopic.css('display', ((state) ? "block" : "none"));
	},

	createMenuItem(name, displayname, icon){
		return '<li class="addon-menu"><a class="nav" onclick="event.preventDefault();loadAddonPage(\''+name+'\')"   href="#"><i class="fa '+icon+'" aria-hidden="true"></i> '+displayname+'</a></li>';
	}

};


// =================================================



// =================================================
//  INTERFACE - DYNAMIC ADDON MENU
// =================================================


function addHammerTime(){
	if(isSmallScreen) return;
	var hammertime = new Hammer(this);
	hammertime.on('swipe', function(ev) {
		if(ev.offsetDirection == "4"){
			if(!menuVisible) toggleMenu();
		}else if(ev.offsetDirection == "2"){
			if(menuVisible) toggleMenu();
		}
	});
	hammertime.get('swipe').set({ direction: Hammer.DIRECTION_HORIZONTAL });
}







// =================================================
