let memberManager = {
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

      $('#userContainer').on('click', '.user-item .delete', function(){
        showDeleteUserDialog($(this).parent().parent());
      });

      $('#groupContainer').on('click', '.group-item button', function(){
        let item = $(this).parent().parent();
        let id = item.data('groupid');
        let action = $(this).data('action');
        groupButtonHandler(id,action,item);
      });
    }

    function showDeleteUserDialog(item){
      let html  = lightboxQuestion('Delete User');
      showLightbox(html, function(res, lbdata){
        if(res){
          memberManager.removeUser(item.data('id'), function(res){
            if(res) item.remove();
          });
        }
      }, null, "Delete");
    }

    function showAddUserDialog(){
      let groups = [];console.log(memberManager.groups);
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

    function groupButtonHandler(id, action, item){
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

        case 'premission':
        let yhtml = "";
        let lastPrefix = null;
        memberManager.findGroup(id).permissions.filter(function(a,b){
          if(a.key < b.key) return -1;
          if(a.key > b.key) return 1;
          return 0;
        });
        memberManager.findGroup(id).permissions.forEach(function(permission){
          let prefix = permission.key.split('_')[0];
          if(prefix !== lastPrefix){
            yhtml += lightboxQuestion(prefix.ucfirst());
            lastPrefix = prefix;
          }
          yhtml += lightboxCheckbox(permission.key, permission.title, permission.active);
        });
        showLightbox(yhtml, function(res, lbdata){
          if(res){
            let activePermissions = [];
            for (key in lbdata) {
              if (lbdata.hasOwnProperty(key) && lbdata[key] === true) {
                activePermissions.push(key);
              }
            }console.log(activePermissions);
            memberManager.groupSavePermission(id, activePermissions, function(data){
              if(data.error){
                notify(NOTIFY_ERROR, data.data);
                return;
              }else{
                memberManager.refreshGroups(function(){
                  notify(NOTIFY_SUCCESS, "Successful saved!");
                });
              }
            });
          }
        }, null, "Save", "Cancel");
        break;

        case 'edit':
        let groupName = memberManager.getGroupName(item);
        let groupColor = memberManager.getGroupColor(item);
        let _colors = colors.map(function(item){
          if(item.value == groupColor) item.selected = true;
          return item;
        });

        let xhtml  = lightboxQuestion('Edit Group');
        xhtml += lightboxInput('name', 'text', 'Name', groupName);
        xhtml += lightboxSelect('color', colors, 'Color', _colors);
        showLightbox(xhtml, function(res, lbdata){
          if(res){
            memberManager.editGroup(id, lbdata['name'], lbdata['color'], function(success, message){
              if(!success){
                notify(NOTIFY_ERROR, message);
                return;
              }
              memberManager.updateUserColors(id, lbdata['color']);
              $(".group-item[data-groupid='"+id+"']").after(message).remove();
            });
          }
        }, null, "Edit", "Cancel");
        break;
      }
    }

    memberManager.refreshGroups();
  },

  updateUserColors(groupid, newcolor){
    $('#userContainer').find('.user-item[data-group=\''+groupid+'\']').each(function(){
      $(this).find('.info h3').css('background', newcolor);
    });
  },

  addUser(username, password, email, image, groupid, cb){
    let formData = new FormData();
    formData.append("a", 'addMember');
    formData.append("username", username);
    formData.append("password", password);
    formData.append("email", email);
    formData.append("groupid", groupid);
    if(image){
      formData.append("image", image);
    }
    $.ajax({
      url: 'php/ajax_memberManager.php',
      type: 'post',
      success: function(data){
        let dataobj = JSON.parse(data);
        if(dataobj.error){if(cb){cb(false, dataobj.data);}return;}
        if(cb){cb(true, dataobj.data);}
      },
      error: errorHandler = function() {
        alert("Something went horribly wrong!");
      },
      data: formData,
      mimeTypes:"multipart/form-data",
      cache: false,
      contentType: false,
      processData: false
    }, 'json');
  },

  removeUser(id, cb){
    $.get('php/ajax_memberManager.php', {'a':'removeMember', 'id':id}, function(data){
      let dataobj = JSON.parse(data);
      if(dataobj.error){if(cb){cb(false, dataobj.data);}return;}
      if(cb){cb(true, dataobj.data);}
    });
  },

  groupSavePermission(id, permissions, cb){
    $.get('php/ajax_memberManager.php', {'a':'savePermissions', 'id':id, 'permissions': permissions}, function(data){
      let dataobj = JSON.parse(data);
      if(dataobj.error){if(cb){cb(false, dataobj.data);}return;}
      if(cb){cb(true, dataobj.data);}
    });
  },

  getGroupColor(groupElement){
    return groupElement.find('.info i').css('background-color');
  },
  getGroupName(groupElement){
    return groupElement.find('.info span').text();
  },

  refreshGroups(cb){
    $.get('php/ajax_memberManager.php', {'a':'getGroups'}, function(data){
      let dataobj = JSON.parse(data);
      if(dataobj.error){notify(NOTIFY_ERROR, dataobj.data);if(cb){cb(false);}return;}
      memberManager.groups = dataobj.data;
      if(cb){cb(true);}
    });
  },

  findGroup(id){
    let group = null;
    memberManager.groups.forEach(function(item){
      if(item.id == id) group = item;
    });
    return group;
  },

  addGroup(name, color, cb){
    $.get('php/ajax_memberManager.php', {'a':'addGroup', 'name':name, 'color':color}, function(data){
      let dataobj = JSON.parse(data);
      if(dataobj.error){if(cb){cb(false, dataobj.data);}return;}
      memberManager.refreshGroups(function(){
        if(cb){cb(true, dataobj.data);}
      });
    });
  },

  removeGroup(id, cb){
    $.get('php/ajax_memberManager.php', {'a':'removeGroup', 'id':id}, function(data){
      let dataobj = JSON.parse(data);
      if(dataobj.error){if(cb){cb(false, dataobj.data);}return;}
      memberManager.refreshGroups(function(){
        if(cb){cb(true, dataobj.data);}
      });
      if(cb){cb(true, dataobj.data);}
    });
  },

  editGroup(id, name=null, color=null, cb=null){
    $.get('php/ajax_memberManager.php', {'a':'editGroup', 'id':id, 'name':name, 'color':color}, function(data){
      let dataobj = JSON.parse(data);
      if(dataobj.error){if(cb){cb(false, dataobj.data);}return;}
      memberManager.refreshGroups(function(){
        if(cb){cb(true, dataobj.data);}
      });
      if(cb){cb(true, dataobj.data);}
    });
  }

};
