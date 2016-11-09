let memberManager = {
  'element':null,
  'groups':[],

  //  ============================================
  //  SETUP
  //  ============================================
  init(){
    initControls();

    let colors = [
      {'value':'rgb(101, 191, 129)','text':'green'},
      {'value':'rgb(237, 165, 43)','text':'orange'},
      {'value':'rgb(226, 93, 161)','text':'purple'},
      {'value':'rgb(77, 186, 193)','text':'blue'},
      {'value':'rgb(191, 127, 80)','text':'brown'}
    ];

    function initControls(){
      $('#addUserButton').on('click', function(){
        showAddUserDialog();
      });

      $('#addGroupButton').on('click', function(){
        showAddGroupDialog();
      });

      $('#userContainer').on('click', '.user-item', function(){
        console.log(this)
      });

      $('#userContainer').on('mouseenter', '.user-item', function(){
        showUserButtons(this);
      });

      $('#userContainer').on('mouseleave', '.user-item', function(){
        hideUserButtons(this);
      });

      $('#groupContainer').on('click', '.group-item button', function(){
        let id = $(this).parent().parent().data('groupid');
        let action = $(this).data('action');
        groupButtonHandler(id,action);
      });
    }

    var tweens = [];
    function showUserButtons(useritem){
      finishAllTweens(useritem);
      let items = [].slice.call(useritem.querySelectorAll('.info .animate-wrapper button')).reverse();
      tweens.push(TweenMax.staggerFromTo(items, 1, {x: '50px'}, {x: '135px', ease:Elastic.easeOut}, 0.35, function(){}));
    }

    function hideUserButtons(useritem){
      finishAllTweens(useritem);
      let items = [].slice.call(useritem.querySelectorAll('.info .animate-wrapper button'));
      tweens.push(TweenMax.staggerFromTo(items, 0.8, {x: '135px'}, {x: '50px', ease:Elastic.easeIn}, 0.35, function(){}));
    }

    function finishAllTweens(useritem){
      TweenMax.killChildTweensOf(useritem);
    }

    function showAddUserDialog(){
      let groups = [];
      memberManager.groups.forEach(function(group){
        groups.push({'value':group.id,'text':group.name});
      });

      let html  = lightboxQuestion('Add new User');
      html += lightboxInput('username', 'text', 'Username');
      html += lightboxInput('email', 'email', 'E-Mail');
      html += lightboxInput('image', 'file', 'Image');
      html += lightboxInput('password', 'password', 'Password');
      html += lightboxInput('password_repeat', 'password', 'Password repeat');
      html += lightboxSelect('groupid', groups, 'Group');

      showLightbox(html, function(res, lbdata){
        if(res){
          memberManager.addUser(lbdata['username'], lbdata['password'], lbdata['email'], lbdata['image'], lbdata['groupid'], function(success, message){
            if(!success){
              notify(NOTIFY_ERROR, message);
              return;
            }
            $('#userContainer').append(message);
          });
        }
      }, null, "Add", "Cancel");
    }

    function showAddGroupDialog(){
      let html  = lightboxQuestion('Add new Group');
      html += lightboxInput('name', 'text', 'Name');
      html += lightboxSelect('color', colors, 'Color');
      showLightbox(html, function(res, lbdata){
        if(res){
          memberManager.addGroup(lbdata['name'], lbdata['color'], function(success, message){
            if(!success){
              notify(NOTIFY_ERROR, message);
              return;
            }
            $('#groupContainer').append(message);
          });
        }
      }, null, "Add", "Cancel");
    }

    function groupButtonHandler(id, action){
      switch (action) {
        case 'delete':
          let html  = lightboxQuestion('Delete Group?');
          showLightbox(html, function(res, lbdata){
            if(res){
              memberManager.removeGroup(id, function(success, message){
                if(!success){
                  notify(NOTIFY_ERROR, message);
                  return;
                }
                $(".group-item[data-groupid='"+id+"']").remove();
              });
            }
          }, null, "Delete", "Cancel");
        break;

        case 'edit':

        break;
      }
    }

    memberManager.element = $("#memberEditor");
    memberManager.refreshGroups();
  },

  addUser(username, password, email, image, groupid, cb){
    $.post('php/ajax_memberManager.php', {'a':'addMember', 'username':username, 'password':password, 'email':email, 'groupid':groupid}, function(data){
      let dataobj = JSON.parse(data);
      if(dataobj.error){if(cb){cb(false, dataobj.data);}return;}
      if(cb){cb(true, dataobj.data);}
    });
  },

  refreshGroups(cb){
    $.get('php/ajax_memberManager.php', {'a':'getGroups'}, function(data){
      let dataobj = JSON.parse(data);
      if(dataobj.error){notify(NOTIFY_ERROR, dataobj.data);if(cb){cb(false);}return;}
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
