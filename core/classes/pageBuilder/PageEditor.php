<?php namespace KFall\oxymora\pageBuilder;

class PageEditor extends PageBuilder{

  public static function getEditorHtml(){
    $html = self::$htmlSkeleton;

    // Replace Placeholder except area
    $html = self::replaceAllPlaceholder($html, ['area']);

    // Replace Areas now
    $html = self::editorReplaceAreas($html);

    // Replace all Paths
    $html = self::replaceAllPaths($html);

    // Add Editor Stylesheet // Important, Run after replaceAllPaths-function!
    $html = self::editorAddStylesheet($html);

    return $html;
  }

  private static function editorReplaceAreas($html){
    $areaPlaceholder = self::getPlaceholder($html, "area");
    foreach($areaPlaceholder as $placeholder){
      $name = self::getPlaceholderValue($placeholder);
      $value = self::editorArea($name);
      $html = str_replace($placeholder,$value,$html);
    }
    return $html;
  }

  private static function editorReplaceAreaPlugins($html){
    $areaPlaceholder = self::getPlaceholder($html, "plugin");
    foreach($areaPlaceholder as $placeholder){
      $pluginInfo = self::getPlaceholderValue($placeholder);
      $pluginName = $pluginInfo[0];
      $pluginId = $pluginInfo[1];
      $value = self::editorPlugin($pluginName, $pluginId, self::getPlaceholderPlugin($placeholder));
      $html = str_replace($placeholder,$value,$html);
    }
    return $html;
  }

  private static function editorArea($areaName){
    $html = '<div class="oxymora-area" data-name="'.$areaName.'"><div class="oxymora-area-name">'.$areaName."</div>".self::editorGenerateAreaContent($areaName)."</div>";
    return $html;
  }

  public static function editorPlugin($name, $id, $html){
    $html = '<div class="oxymora-plugin" data-plugin="'.$name.'" data-id="'.$id.'">
              <div class="oxymora-plugin-topbar">
              <div class="oxymora-plugin-name">'.$name.'</div>
              <button class="oxymora-plugin-delete">Delete</button>
              <button class="oxymora-plugin-edit">Edit</button>
              </div>'.$html.'</div>';
    return $html;
  }

  private static function editorAddStylesheet($html){
    $value = '    <link rel="stylesheet" href="../css/pageEditor.css">'. "\n</head>";
    $html = str_replace("</head>",$value,$html);
    return $html;
  }

  protected function editorGenerateAreaContent($area){
    // todo: load different areas
    $html = self::$currentPageAreas[$area]['content'];

    // Replace Placeholder
    $html = self::replaceAllPlaceholder($html, ['plugin']);

    // Replace Areas
    $html = self::editorReplaceAreaPlugins($html);

    return $html;
  }

}
