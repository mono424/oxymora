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
