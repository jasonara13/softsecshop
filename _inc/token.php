<?php

class Token {
  public static function generateToken(){
    return $_SESSION['token'] = Salt() . RandomToken() . Salt();
  }

  public static function generateCartToken(){
    return $_SESSION['cart-token'] = Salt() . RandomToken() . Salt();
  }
  
  public static function generateCheckoutToken(){
    return $_SESSION['cart-checkout'] = Salt() . RandomToken() . Salt();
  }

  public static function tokenValidity($token){
    if(isset($_SESSION['token']) && $token === $_SESSION['token']){
      unset($_SESSION['token']);
      return true;
    }
    return false;
  }

  public static function cartTokenValidity($token){
    if(isset($_SESSION['cart-token']) && $token === $_SESSION['cart-token']){
      return true;
    }
    return false;
  }
  
  public static function checkoutTokenValidity($token){
    if(isset($_SESSION['cart-checkout']) && $token === $_SESSION['cart-checkout']){
      unset($_SESSION['cart-checkout']);
      return true;
    }
    return false;
  }

}

function RandomToken($length = 32){
    if(!isset($length) || intval($length) <= 8 ){
      $length = 32;
    }
    if (function_exists('random_bytes')) {
        return bin2hex(random_bytes($length));
    }
    if (function_exists('mcrypt_create_iv')) {
        return bin2hex(mcrypt_create_iv($length, MCRYPT_DEV_URANDOM));
    }
    if (function_exists('openssl_random_pseudo_bytes')) {
        return bin2hex(openssl_random_pseudo_bytes($length));
    }
}

function Salt(){
    return substr(strtr(base64_encode(hex2bin(RandomToken(32))), '+', '.'), 0, 44);
}

 ?>
