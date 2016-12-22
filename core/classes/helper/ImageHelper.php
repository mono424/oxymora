<?php namespace KFall\oxymora\helper;

class ImageHelper{

  public static function easyImageCrop($imagepath, $output, $thumb_width, $thumb_height){
    $image = imagecreatefromjpeg($imagepath);
    $width = imagesx($image);
    $height = imagesy($image);
    $original_aspect = $width / $height;
    $thumb_aspect = $thumb_width / $thumb_height;
    if($original_aspect >= $thumb_aspect){
      $new_height = $thumb_height;
      $new_width = $width / ($height / $thumb_height);
    }else{
      $new_width = $thumb_width;
      $new_height = $height / ($width / $thumb_width);
    }
    $thumb = imagecreatetruecolor( $thumb_width, $thumb_height );
    // Resize and crop
    imagecopyresampled($thumb,$image,0 - ($new_width - $thumb_width) / 2,0 - ($new_height - $thumb_height) / 2,0, 0,  $new_width, $new_height,$width, $height);
    if($output){imagejpeg($thumb, $output, 80);return true;}else{return $thumb;}
  }

}
