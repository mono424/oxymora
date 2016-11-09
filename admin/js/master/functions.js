
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
	if(isSmallScreen && menuVisible) atoggleMenu();
	preloadManager.show(function(){
		content.load('pages/'+page+".php", function(){
			preloadManager.hide(function(){});
			markNavItem(page, false)
			initTabcontrols(".tabContainer");
		});
	});
}

function loadAddonPage(addon){
	if(isSmallScreen && menuVisible)toggleMenu();
	preloadManager.show(function(){
		content.load('pages/addon.php?addon='+addon, function(){
			preloadManager.hide(function(){
				initTabcontrols(".tabContainer");
			});
			markNavItem(addon, true)
		});
	});
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


// =================================================
//  INTERFACE - PRELOADER
// =================================================

let preloadManager = {
	show(cb){
		// cb();return;
		content.fadeOut(300, function(){
			preloader.fadeIn(200, function(){
				if(cb){cb();}
			});
		});
	},
	hide(cb){
		// cb();return;
		preloader.fadeOut(500, function(){
			content.css('display','block');
			if(cb){cb();}
		});
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
			html = $(data.message);
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
				$.get('php/ajax_navigation.php?id='+item.data("id")+'&action='+action+'&title='+encodeURIComponent(lbdata['title'])+'&url='+encodeURIComponent(lbdata['url']), function(data){
					var data = JSON.parse(data);
					if(data.type === "success"){
						title.html(lbdata['title']);
						url.html(lbdata['url']);
					}
				});
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




// =================================================
//  INTERFACE - LIGHTBOX
// =================================================

function lightboxQuestion(text){
	return '<p class="lightboxquestion">'+text+"</p>";
}

function lightboxInput(name, type, placeholder, value){
	if (placeholder == null){placeholder = "";}
	if (value == null){value = "";}
	return '<input class="lightboxinput" value="'+value+'" placeholder="'+placeholder+'" data-name="'+name+'" type="'+type+'">';
}

function lightboxSelect(name, options, placeholder){
	let html = '<select class="lightboxinput" data-name="'+name+'">';
	if(placeholder !== null){html += '<option value="" selected disabled>'+placeholder+'</option>';}
	options.forEach(function(item){
		html += '<option value="'+item.value+'"'+(item.selected===true ? "selected" : "")+'>'+item.text+'</option>';
	});
	html += '</select>';
	return html;
}

function showLightbox(html, callback, visibleCallback, ok_button, cancel_button, customClass){
	if (ok_button == null){ok_button = "Ok";}
	if (cancel_button == null){cancel_button = "Cancel";}
	lightboxDialogContent.html(html);
	lightbox.css("display", "block");
	lightboxDialog.attr('class', 'dialog');
	if (customClass != null){lightboxDialog.addClass(customClass);}
	lightboxDialog.css("margin-top", -lightboxDialog.height() - 50);
	lightboxDialog.css("height", lightboxDialogContent.height() + lightboxOkBtn.height() + 30);
	wrapper.css("filter", "blur(5px)");
	lightboxDialog.animate({"margin-top": "0"}, 500, function(){
		if (visibleCallback != null){visibleCallback();}
	});

	lightboxOkBtn.unbind();
	if(ok_button == false){
		lightboxOkBtn.css('display','none');
	}else{
		lightboxOkBtn.css('display','inline-block');
		lightboxOkBtn.text(ok_button);
		lightboxOkBtn.on("click", function(){
			var data = [];
			lightboxDialogContent.find('.lightboxinput').each(function(index){
				var input = $(this);
				data[input.data('name')] = input.val();
			});
			lightboxDialogContent.find('.lightboxobject').each(function(index){
				var object = $(this);
				data[object.data('name')] = object;
			});
			hideLightbox();
			if (callback != null){callback(true, data);}
		});
	}

	lightboxCancelBtn.unbind();
	if(cancel_button == false){
		lightboxCancelBtn.css('display','none');
	}else{
		lightboxCancelBtn.css('display','inline-block');
		lightboxCancelBtn.text(cancel_button);
		lightboxCancelBtn.on("click", function(){
			hideLightbox();
			if (callback != null){callback(false, null);}
		});
	}
}

function hideLightbox(){
	lightboxDialog.animate({"margin-top": -lightboxDialog.height() - 50},500,function(){
		wrapper.css("filter", "none");
		lightbox.css("display", 'none');
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
		return '<li class="addon-menu"><a class="nav" onclick="loadAddonPage(\''+name+'\')"   href="#addon-'+name+'"><i class="fa '+icon+'" aria-hidden="true"></i> '+displayname+'</a></li>';
	}

};





// =================================================
//  BUTTON LOADING
// =================================================

let buttonManager = {

	loading(button, loadingText, finishedText){
		button.dataset.status = "loading";
		button.dataset.finishedText = (finishedText) ? finishedText : button.innerHTML;
		button.innerHTML = (loadingText) ? loadingText : "Bitte warten...";
	},

	finished(button, finishedText, enabelAgain){
		button.dataset.status = (enabelAgain) ? "ready" : "finished";
		button.innerHTML = (finishedText) ? finishedText : button.dataset.finishedText;
	},

	buttonActiv(button, finishedIsActive){
		if(button.dataset.status == "loading" || (!finishedIsActive && button.dataset.status == "finished")){
			return false;
		}else{
			return true;
		}
	}

}

// =================================================
//  NOTIFICATIONS
// =================================================
let NOTIFY_SUCCESS = 1;
let NOTIFY_WARNING = 2;
let NOTIFY_ERROR = 3;
let NOTIFY_INFO = 4;
function notify(type, text, time, left){
  var id = Math.floor(Math.random() * (999999999 - 111111111)) + 111111111;
  notifyBox[0].dataset.notifyid = id;
  notifyBox[0].className = "notify";
  if(left){notifyBox[0].className += " notify-left";}
  if(type == NOTIFY_SUCCESS){  // success
    notifyBox[0].className += " notify-success";
  }else if(type == NOTIFY_WARNING){  // warning
    notifyBox[0].className += " notify-warning";
  }else if(type == NOTIFY_ERROR){  // error
    notifyBox[0].className += " notify-error";
  }else if(type == NOTIFY_INFO){  // info
    notifyBox[0].className += " notify-info";
  }
  notifyBox[0].innerHTML = text;
  notifyBox.fadeIn(200);
  time = (time) ? time * 1000 : 3000;
	setTimeout(function(){
		notify_destroy(id);
	}, time);
}

function notify_destroy(id){
  if(notifyBox[0].dataset.notifyid == id){
    notifyBox.fadeOut(400);
  }
}


// =================================================
