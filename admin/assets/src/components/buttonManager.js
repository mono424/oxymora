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
