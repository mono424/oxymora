let dashboard = {
  '_widgetContainer': null,
  'widgets': null,

  'init': function(widgetContainer){
    let me = this;
    me._widgetContainer = $(widgetContainer);
    me.updateWidgets(function(){
      me._updateDOM();
    });
  },

  'updateWidgets': function(cb){
    let me = this;
    this._getWidgets(function(success, data){
      if(!success){alert('Error while loading Widgets!'); return;}
      me.widgets = data.map(function(item){return new Widget(item);});
      if(cb) cb();
    });
  },

  '_getWidgets': function(cb){
    $.get('php/ajax_widgets.php', {'action':'get'}, function(data){
      let dataobj = JSON.parse(data);
      if(dataobj.error){if(cb){cb(false, dataobj.data);}return;}
      if(cb){cb(true, dataobj.data);}
    });
  },

  '_updateDOM': function(){
    let me = this;
    me._widgetContainer.html('');
    if(me.widgets){
      me.widgets.forEach(function(item){
        me._widgetContainer.append(item.html());
      });
    }
    me._widgetContainer.append(this._clearAddon());
  },

  '_clearAddon': function(){
    return $(`
    <div class="widget">
      <div class="widget-placeholder">Click to choose a Widget</div>
    </div>
    `);
  }

};

let Widget = function(obj){
  this.obj = obj;
  this.html = function(){
    return `
    <div class="widget">
      <iframe class="widgetIframe" frameborder="0" src="addon/${this.obj.widget}/index.php"></iframe>
    </div>
    `
  }
}
