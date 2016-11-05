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
  }

};
