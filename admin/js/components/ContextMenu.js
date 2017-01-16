function ContextMenu(selector, items, magickSelect = null){
  this.selector = selector;
  this.items = items;
  this.magickSelect = magickSelect;
  this.class = 'contextMenu';
  this.currentElement = null;

  this.setup = function(){
    this._setHandler();
  };

  this._setHandler = function(){
    let me = this;
    // For Showing
    $(this.selector).off('contextmenu.ContextMenu').on('contextmenu.ContextMenu', magickSelect, function(e){
      e.preventDefault();
      me.show(e.pageY + "px", e.pageX + "px")
    });
    // For Hiding
    $(document).off('click.ContextMenu').on('click.ContextMenu', function(e){
      if($(e.target).parents('.'+this.class).length <= 0){
        me.hide();
      }
    });
  };

  this.hide = function(){
    let me = this;
    me.currentElement.fadeOut(100, function(){
      me._removeFromDOM();
    });
  };

  this.show = function(top, left){
    this._addToDom();
    this.currentElement.css('top', top);
    this.currentElement.css('left', left);
    this.currentElement.fadeIn(100);
  };

  this._genHtml = function(){
    let me = this;
    let ul = $('<ul>');
    let html = $('<div>').addClass(this.class).append(ul);
    items.forEach(function(item){
      item._contextClickHandler = function(){me.hide();};
      ul.append(item.getElement());
    });
    return html;
  };

  this._addToDom = function(html){
    this._removeFromDOM();
    this.currentElement = this._genHtml();
    $('body').append(this.currentElement);
  };

  this._removeFromDOM = function(html){
    $('.'+this.class).each(function(){
      $(this).remove();
    });
  };

  this.setup();

}

function ContextMenuItem(html, callback){
  let me = this;
  this.html = html;
  this.callback = callback;
  this.getElement = function(){
    let $element = $('<li>').html(html);
    $element.on('click', function(){
      if(me._contextClickHandler) me._contextClickHandler();
      if(me.callback) me.callback();
    });
    return $element;
  };
}
