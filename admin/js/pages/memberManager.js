let memberManager = {
  'element':null,
  'groups':[],

  //  ============================================
  //  SETUP
  //  ============================================
  init(){
    memberManager.element = $("#memberEditor");
    memberManager.refreshGroups();
  },

  refreshGroups(cb){
    $.get('php/ajax_memberManager.php', {'a':'getGroups'}, function(data){
      let dataobj = JSON.parse(data);
      if(dataobj.error){alert(dataobj.data);if(cb){cb(false);}return;}
      memberManager.groups = dataobj.data;
      if(cb){cb(true);}
    });
  },

  addGroup(name, color, cb){
    $.get('php/ajax_memberManager.php', {'a':'addGroup', 'name':name, 'color':color}, function(data){
      let dataobj = JSON.parse(data);
      if(dataobj.error){if(cb){cb(false, dataobj.data);}return;}
      memberManager.groups = dataobj.data;
      if(cb){cb(true, dataobj.data);}
    });
  },

  removeGroup(id, cb){
    $.get('php/ajax_memberManager.php', {'a':'removeGroup', 'id':id}, function(data){
      let dataobj = JSON.parse(data);
      if(dataobj.error){if(cb){cb(false, dataobj.data);}return;}
      memberManager.groups = dataobj.data;
      if(cb){cb(true, dataobj.data);}
    });
  }

};
