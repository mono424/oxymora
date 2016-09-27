
// =================================================
//  INTERFACE - GLOBAL
// =================================================

function toggleMenu(){
	menuToggle.toggleClass('open');
	if(menuToggle.hasClass("open")){
		sidemenu.animate({"left": 0}, 500);
		header.animate({"width": (header.width() - sidemenu.width())}, 500);
		content.animate({"width": (content.outerWidth() - sidemenu.width())}, 500);
		lightbox.animate({"width": (lightbox.outerWidth() - sidemenu.width())}, 500);
	}else{
		sidemenu.animate({"left": (-sidemenu.width())}, 500);
		header.animate({"width": (header.width() + sidemenu.width())}, 500);
		content.animate({"width": (content.outerWidth() + sidemenu.width())}, 500);
		lightbox.animate({"width": (lightbox.outerWidth() + sidemenu.width())}, 500);
	}
}


function calcSize(){
	var sidemenuWidth = (sidemenu.position().left + sidemenu.width());
	header.css('width', ($(window).width() - sidemenuWidth));
	content.css('height', ($(window).height() - header.height() - 20));
	content.css('width', ($(window).width() - 20 - sidemenuWidth));
	content.css('margin-top', (header.height() + 10));
	lightbox.css('height', ($(window).height() - header.height()));
	lightbox.css('width', ($(window).width() - sidemenuWidth));
	lightbox.css('margin-top', (header.height()));
}


function loadPage(page){
	content.load('pages/'+page+".php", function(){
		initTabcontrols(".tabContainer");
		initNavItem();
		initPageItem();
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
			var html = lightboxQuestion("Wirklich löschen?");
			showLightbox(html,function(res, lbdata){
				if(res){doRequest();}
			});
		}else{
			doRequest();
		}
	}


	function doRequest(){
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
					sortNavItems();
				}
			}
		});
	}
}



// =================================================
//  INTERFACE - PAGES
// =================================================

function initPageItem(){
	$(".pageitem").on('click', pageitemClick);
}

function pageitemClick(){
	var page = $(this);
	showPageGenerator(page.data('page'),function(){
		initPageEditor();
	});
}

function showPageGenerator(page, callback){
	var html	 = '<div class="preview"></div>';
	html			+= '<div class="plugins"></div>';



	showLightbox(html, callback, function(){
		lightboxDialog.find('.preview').html('<object id="pageEditorPreview" type="text/html" data="php/ajax_preview.php?page='+page+'" ></object>');
		callback();
	}, "pageGenerator");
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

function showLightbox(html, callback, visibleCallback, customClass){
	lightboxDialogContent.html(html);
	lightbox.css("display", "block");
	lightboxDialog.attr('class', 'dialog');
	if (customClass != null){lightboxDialog.addClass(customClass);}
	lightboxDialog.css("margin-top", -lightboxDialog.height() - 50);
	lightboxDialog.css("height", lightboxDialogContent.height() + lightboxOkBtn.height() + 30);
	content.css("filter", "blur(5px)");
	lightboxDialog.animate({"margin-top": "2px"}, 500, function(){
		if (visibleCallback != null){visibleCallback();}
	});
	lightboxOkBtn.unbind();
	lightboxCancelBtn.unbind();
	lightboxOkBtn.on("click", function(){
		var data = [];
		lightboxDialogContent.find('.lightboxinput').each(function(index){
			var input = $(this);
			data[input.data('name')] = input.val();
		});
		hideLightbox();
		if (callback != null){callback(true, data);}
	});
	lightboxCancelBtn.on("click", function(){
		hideLightbox();
		if (callback != null){callback(false, null);}
	});
}

function hideLightbox(){
	lightboxDialog.animate({"margin-top": -lightboxDialog.height() - 50},500,function(){
		content.css("filter", "none");
		lightbox.css("display", 'none');
	});
}


















// =================================================
