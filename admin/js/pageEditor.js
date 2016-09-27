
function initPageEditor(){
  pageEditorPreview = $("#pageEditorPreview");
  pageEditorPreview.on('load', function(){
    pageEditor_findElements();
    pageEditor_addIframeHandler();
    pageEditor_addPluginHandler();
  });
}


function pageEditor_findElements(){
   pageEditorAreas = pageEditorPreview.contents().find('.oxymora-area');
   pageEditorPlugins = pageEditorPreview.contents().find('.oxymora-plugin');
}

function pageEditor_addIframeHandler(){
  // pageEditorPreview[0].contentWindow.onbeforeunload = pageEditor_iframe_beforeunloadHandler;
}

function pageEditor_addPluginHandler(){
  pageEditorPlugins.each(function(){
    $(this).find('.oxymora-plugin-edit').on('click', pageEditor_plugin_editHandler);
    $(this).find('.oxymora-plugin-delete').on('click', pageEditor_plugin_deleteHandler);
  });
}

function pageEditor_iframe_beforeunloadHandler(){
  return "Do not Navigate away!";
}

function pageEditor_plugin_editHandler(){
  console.log(this);
}

function pageEditor_plugin_deleteHandler(){
  console.log(this);
}
