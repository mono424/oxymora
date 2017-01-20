<?php namespace KFall\oxymora\helper;

class Validator{

  public static function validateUsername($in){
    return preg_match('/^[a-z0-9\_]{2,50}$/i', $in);
  }
  public static function validateGroupname($in){
    return preg_match('/^[a-z0-9\_]{2,50}$/i', $in);
  }
  public static function validateSex($in){
    return ($in === 'female' || $in === 'male');
  }
  public static function validateTitle($in){
    return preg_match('/^[a-zA-Z\.\s]*?$/', $in);
  }
  public static function validateFirstname($in){
    return preg_match('/^[a-z\ä\ö\ü\ß]{2,50}$/i', $in);
  }
  public static function validateLastname($in){
    return preg_match('/^[a-z\ä\ö\ü\ß]{2,50}$/i', $in);
  }
  public static function validatePhone($in){
    return preg_match("/^00[0-9]{3,}$/", $in);
  }
  public static function validateEmail($in){
    return filter_var($in, FILTER_VALIDATE_EMAIL);
  }
  public static function validateStreet($in){
    return preg_match("/^[a-z\ä\ö\ü\ß\-\s]{3,}$/i", $in);
  }
  public static function validateStreetNr($in){
    return preg_match("/^[0-9]{1,}$/i", $in);
  }
  public static function validatePLZ($in){
    return preg_match("/^[0-9]{3,}$/", $in);
  }
  public static function validateCity($in){
    return preg_match("/^[a-z\ä\ö\ü\ß\-]{3,}$/i", $in);
  }
  public static function validateCountry($in){
    return preg_match("/^[a-z\ä\ö\ü\ß\-]{3,}$/i", $in);
  }
  public static function validatePassword($in, $passwordWdh = null){
    if(strlen($in) < 6) return false;
    if(!is_null($passwordWdh) && $in !== $passwordWdh) return false;
    return true;
  }

}
