// =================================================
//  INTERFACE - LIGHTBOX
// =================================================

function lightboxQuestion(text){
	return '<p class="lightboxquestion">'+text+"</p>";
}

function lightboxInput(name, type, placeholder, value){
	if (placeholder == null){placeholder = "";}
	if (value == null){value = "";}
	let html = "";
	return '<input class="lightboxinput" value="'+value+'" placeholder="'+placeholder+'" data-name="'+name+'" type="'+type+'">';
}

function lightboxCheckbox(name, text, value){
	value = (value) ? " checked" : "";
	let html = '<input class="lightboxinput" data-name="'+name+'" type="checkbox"'+value+'>' + text + "<br>";
	return html;
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
			var data = {length:0};
			lightboxDialogContent.find('.lightboxinput').each(function(index){
				let input = $(this);
				let val = "";
				if(input.attr('type') == "file"){
					val = (input[0].files) ? input[0].files[0] : null;
				}else if(input.attr('type') == "checkbox"){
					val = input[0].checked;
				}else{
					val = input.val()
				}

				data[input.data('name')] = val;
				data['length'] += 1;
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
