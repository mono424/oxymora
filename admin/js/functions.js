
// =================================================
//  INTERFACE - GLOBAL
// =================================================

function toggleMenu(speed){
	speed = (speed === null) ? 500 : speed;
	menuToggle.toggleClass('open');
	if(menuToggle.hasClass("open")){
		sidemenu.animate({"left": 0}, speed);
		header.animate({"width": (header.width() - sidemenu.width())}, speed);
		content.animate({"width": (content.outerWidth() - sidemenu.width())}, speed);
		lightbox.animate({"width": (lightbox.outerWidth() - sidemenu.width())}, speed);
	}else{
		sidemenu.animate({"left": (-sidemenu.width())}, speed);
		header.animate({"width": (header.width() + sidemenu.width())}, speed);
		content.animate({"width": (content.outerWidth() + sidemenu.width())}, speed);
		lightbox.animate({"width": (lightbox.outerWidth() + sidemenu.width())}, speed);
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
	tabControlUpdateHeight();
}


function loadPage(page){
	content.load('pages/'+page+".php", function(){
		markNavItem(page, false)
		initNavItem();
		initPageItem();
		initTabcontrols(".tabContainer");
	});
}

function loadAddonPage(addon){
	content.load('pages/addon.php?addon='+addon, function(){
		markNavItem(addon, true)
		initNavItem();
		initPageItem();
		initTabcontrols(".tabContainer");
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
			var html = lightboxQuestion("Wirklich löschen?");
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
//  INTERFACE - PAGES
// =================================================

function initPageItem(){
	addPageItemHandler($(".pageitem"));
	$("#addPageButton").on('click', pageItemAddButtonClick);
	checkPageItemInNav();
}

function addPageItemHandler(item){
	item.on('click', pageItemClick);
}

function checkPageItemInNav(){
	$(".pageitem").each(function(){
		let btn = $(this).find('.navPageButton');
		if(navItemForPageExists($(this).data('page'))){
			btn.addClass('active');
		}else{
			btn.removeClass('active');
		}
	});
}

function navItemForPageExists(page){
	let res = false;
	$(".navitem").each(function(){
		if($(this).find('.url').html() == "/"+page){
			res = true;
			return false; // SICK FEATURE :D
		}
	});
	return res;
}

function navItemForPage(page){
	let res = false;
	$(".navitem").each(function(){
		if($(this).find('.url').html() == "/"+page){
			res = $(this);
			return false; // SICK FEATURE :D
		}
	});
	return res;
}

function pageItemClick(e){
	let page = $(this);
	if($(e.target).hasClass("deletePageButton") || $(e.target).parent().hasClass("deletePageButton")){
		let html = lightboxQuestion("Wirklich löschen?");
		showLightbox(html,function(res, lbdata){
			if(res){
				$.get('php/ajax_pages.php?action=remove&url='+page.data("page"), function(data){
					data = JSON.parse(data);
					if(data.type == "success"){
						page.remove();
					}else{
						// todo: error handling
					}
				});
			}
		});
	}else if($(e.target).hasClass("navPageButton") || $(e.target).parent().hasClass("navPageButton")){
		let action = ($(e.target).hasClass("active") || $(e.target).parent().hasClass("active")) ? "remove" : "add";
		if(action === "add"){
			let html = lightboxInput("title", "text", "Title", page.data('page').split('.')[0].ucfirst());
			showLightbox(html,function(res, lbdata){
				if(res){
					addNavItem(lbdata['title'], "/"+page.data('page'));
				}
			});
		}else{
			let html = lightboxQuestion("Wirklich aus der Navigation entfernen?");
			showLightbox(html,function(res, lbdata){
				if(res){
					navDoRequest(navItemForPage(page.data('page')), "remove");
				}
			});
		}
	}else{
		showPageEditor(page.data('page'),function(){
			pageEditor.init();
		},function(save, data){
			if(save){
				// SAVE NEW STUFF FROM PAGE EDITOR
				// DATA.previewWindow IS IFRAME
				pageEditor.save(function(success, errormsg){
					if(!success){alert(errormsg);}
				});
			}
		});
	}

}

function pageItemAddButtonClick(){
	var html = lightboxInput("filename", "text", "Filename (e.g Photobook)", "");
	showLightbox(html,function(res, lbdata){
		if(res){
			$.get('php/ajax_pages.php?action=add&filename='+encodeURIComponent(lbdata['filename']), function(data){
				var data = JSON.parse(data);
				if(data.type === "success"){
					html = $(data.message);
					$("#pageContainer").append(html);
					addPageItemHandler(html);
					checkPageItemInNav();
				}
			});
		}
	});
}

function showPageEditor(page, onload_callback, onexit_callback){
	var html	 = '<div class="preview"></div>';
	html			+= '<div class="menu"></div>';



	showLightbox(html, onexit_callback, function(){
		// lightboxDialog.find('.preview').html('<object id="pageEditorPreview" class="lightboxobject" data-name="previewWindow" type="text/html" data="php/ajax_preview.php?page='+page+'" ></object>');
		lightboxDialog.find('.preview').html('<iframe id="pageEditorPreview" data-url="'+page+'" class="lightboxobject" data-name="previewWindow" frameborder="0" src="php/ajax_preview.php?page='+page+'" ></iframe>');
		onload_callback();
	}, "Save & Close", "Cancel", "pageGenerator");
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

function showLightbox(html, callback, visibleCallback, ok_button, cancel_button, customClass){
	if (ok_button == null){ok_button = "Okay";}
	if (cancel_button == null){cancel_button = "Cancel";}
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
		content.css("filter", "none");
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
//  ADDONS
// =================================================

let addonManager = {
	url: "php/ajax_addonManager.php",
	dragObj: null,
	dragActive: false,

	downloadAddon(sender, addon){
		var html = '<iframe style="display:none;" src="php/downloadAddon.php?addon='+addon+'"></iframe>';
		$('body').append(html);
	},

	buttonHandler(sender, addon, action){
		if(!buttonManager.buttonActiv(sender, false)){return;}
		buttonManager.loading(sender);
		switch (action) {
			case 'install':
			result = addonManager.installAddon(addon);
			buttonText = "Deaktivieren";
			sender.dataset.action = "disable";
			buttonEnable = true;
			break;
			case 'enable':
			result =  addonManager.enableAddon(addon);
			buttonText = "Deaktivieren";
			sender.dataset.action = "disable";
			buttonEnable = true;
			break;
			case 'disable':
			result =  addonManager.disableAddon(addon);
			buttonText = "Aktivieren";
			buttonEnable = true;
			sender.dataset.action = "enable";
			break;
			default:
			result =  null;
		}
		buttonManager.finished(sender,buttonText,buttonEnable);
		return result;
	},

	installAddon(addon){
		$.get(addonManager.url + "?a=install&addon="+addon, function(data){
			data = JSON.parse(data);
			addonMenu.loadMenuItems();
		});
	},

	enableAddon(addon){
		$.get(addonManager.url + "?a=enable&addon="+addon, function(data){
			data = JSON.parse(data);
			addonMenu.loadMenuItems();
		});
	},

	disableAddon(addon){
		$.get(addonManager.url + "?a=disable&addon="+addon, function(data){
			data = JSON.parse(data);
			addonMenu.loadMenuItems();
		});
	},

	dragUploadAddon(files){
		if($(addonManager.dragObj).hasClass('upload')){return;}
		$(addonManager.dragObj).addClass('upload');
		let ajaxData = new FormData();
		if(files){
			$.each(files, function(i, file) {
				ajaxData.append(i, file);
			});

			$.ajax({
				url: addonManager.url + "?a=upload",
				type: 'POST',
				data: ajaxData,
				dataType: 'json',
				cache: false,
				contentType: false,
				processData: false,
				complete: function() {
					$(addonManager.dragObj).removeClass('upload');
				},
				success: function(data) {
					$('#pageContainer').append(data.data);
					addonMenu.loadMenuItems();
					if(data.error){
						data.error.forEach(function(err){
							alert(err);
						});
					}
				},
				error: function() {
					alert('Upload failed! Unknown error!');
				}
			});
		}else{
			$(addonManager.dragObj).removeClass('upload');
		}
	},

	fileDragInit(obj){
		obj.addEventListener("dragover", addonManager.fileDragHover, false);
		obj.addEventListener("dragleave", addonManager.fileDragHover, false);
		obj.addEventListener("drop", addonManager.fileSelectHandler, false);
		addonManager.dragObj = obj;
	},

	fileDragHover(e) {
		e.stopPropagation();
		e.preventDefault();
		if(e.type == "dragover" && $(addonManager.dragObj).hasClass('active') == false){
			addonManager.dragActive = true;
			$(addonManager.dragObj).addClass('active');
		}else if(e.type == "dragleave"){
			addonManager.dragActive = false;
			setTimeout(function(){
				if(addonManager.dragActive == false){
					$(addonManager.dragObj).removeClass('active');
				}
			}, 500);
		}
	},

	fileSelectHandler(e) {
		addonManager.fileDragHover(e);
		$(addonManager.dragObj).removeClass('active');
		var files = e.target.files || e.dataTransfer.files;
		for (var i = 0, f; f = files[i]; i++) {
			if(f.name.endsWith('.oxa') || f.name.endsWith('.zip')){
				addonManager.dragUploadAddon(files);
			}else{
				alert('Please drop oxymora addons only!');
			}
		}
	}

}


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
