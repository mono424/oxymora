let backbutton = $(".backbutton");
let title = $("header h1");

$('.link').on('click', function(){
  linkMgr.open('section[data-page='+$(this).data('url')+']');
});

$('.backbutton').on('click', function(e){
  e.preventDefault();
  linkMgr.back();
});

let linkMgr = {
  'history': [],

  'open': function(page, history = true){
    let currentPage = this.currentPage();
    if(currentPage){
      currentPage.fadeOut(400, function(){
        open(page, history);
      });
    }else{
      open(page, history)
    }

    function open(page, history){
      page = $(page);
      page.fadeIn(200);
      title.html(page.data('title'));
      if(history) linkMgr.history.push(page);
    }
  },

  'currentPage': function(){
    return (this.history.length > 0) ? this.history[this.history.length -1] : null;
  },

  'back': function(){
    if(this.history.length > 1){
      linkMgr.open(this.history[this.history.length -2], false);
      this.history.pop();
    }
  }

};



linkMgr.open('section[data-page=start]');
