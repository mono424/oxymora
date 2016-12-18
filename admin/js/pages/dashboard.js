let dashboard = {
  '_widgetContainer': null,
  'dashboardwidgets': null,
  'widgets': null,

  'init': function(widgetContainer){
    let me = this;
    me._widgetContainer = $(widgetContainer);
    me._getAllWidgets(function(success, data){
      if(!success){alert('Error while loading Widgets!'); return;}
      me.widgets = data.map(function(item){return new RootWidget(item);});
      me.updateWidgets(function(){
        me._updateDOM();
      });
    });
  },

  'updateWidgets': function(cb){
    let me = this;
    me._getDashboardWidgets(function(success, data){
      if(!success){alert('Error while loading Widgets!'); return;}
      me.dashboardwidgets = data.map(function(item){return new Widget(item);});
      if(cb) cb();
    });
  },

  'addWidget': function(widget, cb){
    let me = this;
    $.get('php/ajax_widgets.php', {'action':'add', 'widget':widget}, function(data){
      let dataobj = JSON.parse(data);
      if(dataobj.error){if(cb){cb(false, dataobj.data);}return;}

      let newWidget = new Widget(dataobj.data);
      me.dashboardwidgets.push(newWidget);
      newWidget.html().insertBefore(me._widgetContainer.find('.widget').last());

      if(cb){cb(true, dataobj.data);}
    });
  },

  'deleteWidget': function(widgetObj, cb){
    let me = this;
    $.get('php/ajax_widgets.php', {'action':'delete', 'widget':widgetObj.id}, function(data){
      let dataobj = JSON.parse(data);
      if(dataobj.error){if(cb){cb(false, dataobj.data);}return;}

      me.dashboardwidgets = me.dashboardwidgets.filter(function(item){
        return item.obj.id != widgetObj.id;
      });

      if(cb){cb(true, dataobj.data);}
    });
  },

  '_getDashboardWidgets': function(cb){
    $.get('php/ajax_widgets.php', {'action':'getDashboard'}, function(data){
      let dataobj = JSON.parse(data);
      if(dataobj.error){if(cb){cb(false, dataobj.data);}return;}
      if(cb){cb(true, dataobj.data);}
    });
  },

  '_getAllWidgets': function(cb){
    $.get('php/ajax_widgets.php', {'action':'get'}, function(data){
      let dataobj = JSON.parse(data);
      if(dataobj.error){if(cb){cb(false, dataobj.data);}return;}
      if(cb){cb(true, dataobj.data);}
    });
  },

  '_updateDOM': function(){
    let me = this;
    me._widgetContainer.html('');
    if(me.dashboardwidgets){
      me.dashboardwidgets.forEach(function(item){
        me._widgetContainer.append(item.html());
      });
    }
    me._widgetContainer.append(this._clearAddon());
  },

  '_clearAddon': function(){
    let me = this;
    let widgets = $("<ul/>");
    let backbutton = $('<li><i class="fa fa-chevron-circle-left" aria-hidden="true"></i><span> Back</span></li>').on('click', function(){
      $(this).parent().parent().parent().find('.widget-placeholder').fadeIn(200);
    });
    widgets.append(backbutton);
    if(me.widgets){
      me.widgets.forEach(function(w){
        let item = w.listHtml().on('click', function(){
          let element = this;
          me.addWidget(w.obj.name, function(){
            $(element).parent().parent().parent().find('.widget-placeholder').fadeIn(200);
          });
        });
        widgets.append(item);
      });
    }
    let clWidget = $(`
      <div class="widget">
      <div class="widget-placeholder">Click to choose a Widget</div>
      <div class="widget-select"></div>
      </div>`);

      clWidget.find('.widget-select').append(widgets);
      clWidget.find('.widget-placeholder').on('click', function(){
        $(this).fadeOut(200);
      });

      return clWidget;
    }

  };

  let Widget = function(obj){
    this.obj = obj;
    this.html = function(){
      let html = $(`
        <div class="widget">
        <iframe class="widgetIframe" frameborder="0" src="addon/${this.obj.widget}/index.php"></iframe>
        <a href="#">Remove</a>
        </div>
        `);
      html.find('a').on('click', function(){
        dashboard.deleteWidget(obj, function(success){
          if(success) html.remove();
        });
      });
      return html;
      }
    };

    let RootWidget = function(obj){
      this.obj = obj;
      this.listHtml = function(){
        let img = (this.obj.icon) ? this.obj.iconUrl : "img/coffee.svg";
        return $(`
          <li>
          <img src="${img}" />
          <span>${this.obj.config.menuentry.displayname}</span>
          </li>
          `);
        }
      };
