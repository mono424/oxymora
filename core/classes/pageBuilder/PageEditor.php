<?php namespace KFall\oxymora\pageBuilder;
use KFall\oxymora\database\modals\DBElementSettings;

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
    $areaPlaceholder = self::getPlaceholder($html, PLACEHOLDER_INDENT_AREA);
    foreach($areaPlaceholder as $placeholder){
      $name = self::getPlaceholderValue($placeholder);
      $value = self::editorArea($name);
      $html = str_replace($placeholder,$value,$html);
    }
    return $html;
  }

  private static function editorReplaceAreaElements($html){
    $areaPlaceholder = self::getPlaceholder($html, PLACEHOLDER_INDENT_ELEMENT);
    foreach($areaPlaceholder as $placeholder){
      $elementInfo = self::getPlaceholderValue($placeholder);
      $elementName = $elementInfo[0];
      $elementId = $elementInfo[1];
      $settings = ($elementId === false || $elementId === "") ? "" : DBElementSettings::getSettings($elementId);
      $value = self::editorElement($elementName, $elementId, self::getPlaceholderElement($placeholder, $settings), $settings);
      $html = str_replace($placeholder,$value,$html);
    }
    return $html;
  }

  private static function editorArea($areaName){
    $html = '<div class="oxymora-area" data-name="'.$areaName.'"><div class="oxymora-area-name">'.$areaName."</div>".self::editorGenerateAreaContent($areaName)."</div>";
    return $html;
  }

  public static function editorElement($name, $id, $html, $settings){
    $html = '<div class="oxymora-element" data-element="'.$name.'" data-id="'.$id.'" data-settings="'.htmlspecialchars(json_encode($settings), ENT_QUOTES, 'UTF-8').'">
              <div class="oxymora-element-topbar">
              <div class="oxymora-element-name">'.$name.'</div>
              <button class="oxymora-element-delete">Delete</button>
              <button class="oxymora-element-edit">Edit</button>
              </div>
              '.$html.'</div>';
    return $html;
  }

  private static function editorAddStylesheet($html){
    $value = '    <link rel="stylesheet" href="../assets/dist/css/pageeditor.min.css">'. "\n</head>";
    $html = str_replace("</head>",$value,$html);
    return $html;
  }

  protected static function editorGenerateAreaContent($area){
    // todo: load different areas
    $html = self::$currentPageAreas[$area]['content'];

    // Replace Placeholder
    $html = self::replaceAllPlaceholder($html, ['element']);

    // Replace Areas
    $html = self::editorReplaceAreaElements($html);

    return $html;
  }

}
