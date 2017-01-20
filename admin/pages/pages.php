<?php
// core stuff
use KFall\oxymora\database\modals\DBContent;
use KFall\oxymora\database\modals\DBNavigation;
use KFall\oxymora\memberSystem\MemberSystem;
use KFall\oxymora\addons\AddonManager;
use KFall\oxymora\permissions\UserPermissionSystem;
require_once '../php/admin.php';
require_once '../php/htmlComponents.php';

// Check Login
loginCheck();

// Check Permissions
if(!UserPermissionSystem::checkPermission("oxymora_pages")) die(html_error("You do not have the required rights to continue!"));

// Tab Change event
AddonManager::triggerEvent(ADDON_EVENT_TABCHANGE, 'pages');
?>

<!-- <div class="headerbox flat-box">
<h1>Pages n' Navigation</h1>
<h3>Actually this is what your website is made of.</h3>
</div> -->

<div class="tabContainer light">
  <ul>
    <li><a data-tab="pages">Pages</a></li>
    <!-- Ugly Onclick sort fix.. but its working.. so whaat !!? -->
    <li><a data-tab="navigation" onclick="sortNavItems();">Navigation</a></li>
  </ul>
  <div class="tabContent">

    <div class="tab" data-tab="pages">
      <div class="dataContainer" id="pageContainer">
        <?php
        $pages = DBContent::getPages();
        foreach($pages as $page){
          echo html_pageItem($page['url']);
        } ?>
        <div class="clear:both"></div>

      </div>
      <button id="addPageButton" class="oxbutton-float" type="button"><i class="fa fa-plus" aria-hidden="true"></i></button>
    </div>


    <div class="tab" data-tab="navigation">
      <div class="dataContainer" id="navContainer">
        <?php
        $navItems = DBNavigation::getItems();
        foreach($navItems as $navItem){
          echo html_navItem($navItem->display, $navItem->id, $navItem->title, $navItem->url);
        }
        ?>
      </div>
      <button id="addNavButton" class="oxbutton-float" type="button"><i class="fa fa-plus" aria-hidden="true"></i></button>
    </div>


  </div>
</div>


<script type="text/javascript">
initNavItem();
initPageItem();

function initPageItem(){
  addPageItemHandler($(".pageitem"));
  $("#addPageButton").on('click', pageItemAddButtonClick);
  checkPageItemInNav();
}

function addPageItemHandler(item){
  item.on('click', pageItemClick);
}

function checkPageItemInNav(){
  $(".pageitem").each(function(){
    let btn = $(this).find('.navPageButton');
    if(navItemForPageExists($(this).data('page'))){
      btn.addClass('active');
    }else{
      btn.removeClass('active');
    }
  });
}

function navItemForPageExists(page){
  let res = false;
  $(".navitem").each(function(){
    if($(this).find('.url').html() == "/"+page){
      res = true;
      return false; // SICK FEATURE :D
    }
  });
  return res;
}

function navItemForPage(page){
  let res = false;
  $(".navitem").each(function(){
    if($(this).find('.url').html() == "/"+page){
      res = $(this);
      return false; // SICK FEATURE :D
    }
  });
  return res;
}

function pageItemClick(e){
  let page = $(this);
  if($(e.target).hasClass("deletePageButton") || $(e.target).parent().hasClass("deletePageButton")){
    let html = lightboxQuestion("Sure you want to delete?");
    showLightbox(html,function(res, lbdata){
      if(res){
        $.get('php/ajax_pages.php?action=remove&url='+page.data("page"), function(data){
          data = JSON.parse(data);
          if(data.type == "success"){
            page.remove();
          }else{
            // todo: error handling
          }
        });
      }
    });
  }else if($(e.target).hasClass("navPageButton") || $(e.target).parent().hasClass("navPageButton")){
    let action = ($(e.target).hasClass("active") || $(e.target).parent().hasClass("active")) ? "remove" : "add";
    if(action === "add"){
      let html = lightboxInput("title", "text", "Title", page.data('page').split('.')[0].ucfirst());
      showLightbox(html,function(res, lbdata){
        if(res){
          addNavItem(lbdata['title'], "/"+page.data('page'));
        }
      });
    }else{
      let html = lightboxQuestion("Wirklich aus der Navigation entfernen?");
      showLightbox(html,function(res, lbdata){
        if(res){
          navDoRequest(navItemForPage(page.data('page')), "remove");
        }
      });
    }
  }else if($(e.target).hasClass("renamePageButton") || $(e.target).parent().hasClass("renamePageButton")){
    let oldFilename = page.data('page').replace(/\.html$/,'');
    let html = lightboxQuestion('Rename Page')+lightboxInput("filename", "text", "New Filename (e.g Photobook)", oldFilename);
    showLightbox(html,function(res, lbdata){
      if(res){
        $.get('php/ajax_pages.php?action=rename&filename='+encodeURIComponent(oldFilename)+'&newfilename='+encodeURIComponent(lbdata['filename']), function(data){
          var data = JSON.parse(data);
          if(data.type === "success"){
            // Page DOM
            let newPage = $(data.message);
            page.after(newPage);
            page.remove();
            addPageItemHandler(newPage);
            // Nav Item
            let navItem = navItemForPage(page.data('page'));
            if(navItem) navDoEdit(navItem, null, "/"+lbdata['filename']+".html", function(data){
              // Update Nav
              checkPageItemInNav();
            });
          }
        });
      }
    });
  }else if($(e.target).hasClass("openPageButton") || $(e.target).parent().hasClass("openPageButton")){
    let url = window.location.href;
    url = url.split('/').slice(0,-2).join('/') + "/" + page.data('page');
    window.open(url, '_blank').focus();
  }else{
    showPageEditor(page.data('page'),function(){
      pageEditor.init();
    },function(save, data){
      if(save){
        // SAVE NEW STUFF FROM PAGE EDITOR
        // DATA.previewWindow IS IFRAME
        pageEditor.save(function(success, errormsg){
          if(typeof(pageEditorWindow) != "undefined" && pageEditorWindow) pageEditorWindow.close();
          if(!success){notify(NOTIFY_ERROR, errormsg);}
        });
      }
    });
  }

}

function pageItemAddButtonClick(){
  var html = lightboxInput("filename", "text", "Filename (e.g Photobook)", "");
  showLightbox(html,function(res, lbdata){
    if(res){
      $.get('php/ajax_pages.php?action=add&filename='+encodeURIComponent(lbdata['filename']), function(data){
        var data = JSON.parse(data);
        if(data.type === "success"){
          html = $(data.message);
          $("#pageContainer").append(html);
          addPageItemHandler(html);
          checkPageItemInNav();
        }
      });
    }
  });
}

function showPageEditor(page, onload_callback, onexit_callback){
  let currHref = $(location).attr('href').replace(/[^\/]*$/, '');
  let html	 = $(`
    <div class="preview">
    <div class="previewMenu">
    <button class="externalWindow"><i class="fa fa-external-link-square" aria-hidden="true"></i> Open in new Window</button>
    </div>
    </div>
    <div class="menu"></div>`);
    html.find('.externalWindow').on('click', function(){pageEditor.openWindowPreview();});


    showLightbox(html, onexit_callback, function(){
      // lightboxDialog.find('.preview').html('<object id="pageEditorPreview" class="lightboxobject" data-name="previewWindow" type="text/html" data="php/ajax_preview.php?page='+page+'" ></object>');
      lightboxDialog.find('.preview').append('<iframe id="pageEditorPreview" data-url="'+page+'" class="lightboxobject" data-name="previewWindow" frameborder="0" src="'+currHref+'php/ajax_preview.php?page='+page+'" ></iframe>');
      onload_callback();
    }, "Save & Close", "Cancel", "pageGenerator");
  }

  </script>
