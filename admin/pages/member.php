<?php
use KFall\oxymora\database\modals\DBGroups;
use KFall\oxymora\memberSystem\MemberSystem;
use KFall\oxymora\addons\AddonManager;
use KFall\oxymora\database\modals\DBMember;
require_once '../php/admin.php';
require_once '../php/htmlComponents.php';
loginCheck();
AddonManager::triggerEvent(ADDON_EVENT_TABCHANGE, 'member');
?>
<!-- <div class="headerbox flat-box">
<h1>Member</h1>
<h3>Be a real admin! Manage user!</h3>
</div> -->

<div class="tabContainer light">
  <ul>
    <li><a data-tab="user">User</a></li>
    <li><a data-tab="groups">Groups</a></li>
  </ul>
  <div class="tabContent">

    <div class="tab" data-tab="user">
      <div class="dataContainer" id="userContainer">
        <?php
        $member = DBMember::getList();
        foreach($member as $m){
          echo html_userItem($m['username'], $m['image'], $m['groupcolor']);
        }
        ?>
      </div>
      <button id="addUserButton" class="oxbutton-float" type="button"><i class="fa fa-plus" aria-hidden="true"></i></button>
    </div>


    <div class="tab" data-tab="groups">
      <div class="dataContainer" id="groupContainer">
        <?php
        $groups = DBGroups::listGroups();
        foreach($groups as $g){
          echo html_groupItem($g['id'], $g['name'], $g['color']);
        }
        ?>
      </div>
      <button id="addGroupButton" class="oxbutton-float" type="button"><i class="fa fa-plus" aria-hidden="true"></i></button>
    </div>


  </div>
</div>



<script type="text/javascript">
(function(){
initControls();


let colors = [
  {'value':'rgb(101, 191, 129)','text':'green'},
  {'value':'rgb(237, 165, 43)','text':'orange'},
  {'value':'rgb(226, 93, 161)','text':'purple'},
  {'value':'rgb(77, 186, 193)','text':'blue'},
  {'value':'rgb(191, 127, 80)','text':'brown'}
]


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

  $('#groupContainer').on('click', '.group-item button', function(){
    let id = $(this).parent().parent().data('groupid');
    let action = $(this).data('action');
    groupButtonHandler(id,action);
  });
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
          alert(message);
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
          alert(message);
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
              alert(message);
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


memberManager.init();
})();

</script>
