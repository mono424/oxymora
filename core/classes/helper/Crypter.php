<?php namespace KFall\oxymora\helper;
// Inspirations for Crypt:
// https://github.com/rmmoul/php-aes-class/blob/master/aes.php
// https://licson.net/post/encrypt-files-in-php/


class Crypter{
  private static $method = 'AES-256-CBC';

  public static function encryptFile($file, $passphrase, $output = ""){
    // Output
    $_output = $output ? $output : $file.".crypted";

    // Keys
    $iv = substr(md5("\x1B\x3C\x58".$passphrase, true), 0, 16);
    $key = substr(md5("\x2D\xFC\xD8".$passphrase, true).md5("\x2D\xFC\xD9".$passphrase, true), 0, 24);

    // Open the files
    $finput = fopen($file, 'r');
    $foutput = fopen($_output, 'wb');

    if(!$finput){throw new Exception("Could not open input file");}
    if(!$foutput){throw new Exception("Could not open output file");}

    while(!feof($finput)){
      $buffer = base64_encode(fread($finput, 4096));
      $encrypted = self::encrypt($buffer, $key, $iv);
      // echo $encrypted."<br>";
      fwrite($foutput, $encrypted);
    }

    // Close the file
    fclose($finput);
    fclose($foutput);

    // Overwrite if output nothing
    if(!$output) rename($_output, $file);

    return true;
  }

  public static function decryptFile($file, $passphrase, $output = ""){
    // Output
    $_output = $output ? $output : $file.".crypted";

    // Keys
    $iv = substr(md5("\x1B\x3C\x58".$passphrase, true), 0, 16);
    $key = substr(md5("\x2D\xFC\xD8".$passphrase, true).md5("\x2D\xFC\xD9".$passphrase, true), 0, 24);

    // Open the files
    $finput = @fopen($file, "r");
    $foutput = @fopen($_output, 'wb');

    if(!$finput){ throw new Exception("Could not open input file"); }
    if(!$foutput){ throw new Exception("Could not open output file"); }

    while(!feof($finput)){
      //4096 bytes plaintext become 9728 bytes of encrypted base64 text
      $buffer = fread($finput, 9728);
      // echo $buffer."<br>";
      $decrypted = base64_decode(self::decrypt($buffer, $key, $iv));
      fwrite($foutput, $decrypted);
    }
    fclose($finput);
    fclose($foutput);

    // Overwrite if output nothing
    if(!$output) rename($_output, $file);

    return true;
  }

  private static function encrypt($plaintext, $key, $iv){
    $ciphertext = openssl_encrypt($plaintext, self::$method, $key, 0, $iv);
    return base64_encode($ciphertext);
  }

  private static function decrypt($ciphertext, $key, $iv){
    $ciphertext = base64_decode($ciphertext);
    $plaintext = openssl_decrypt($ciphertext, self::$method, $key, 0, $iv);
    return rtrim($plaintext, "\0");
  }

}
