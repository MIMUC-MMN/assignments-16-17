<?php

/**
 * Created by PhpStorm.
 * User: li
 * Date: 10.11.2016
 * Time: 13:55
 */
class Utils
{

  static function start_session_onlyif_no_session() {
    if (session_status() === PHP_SESSION_NONE) {
      session_start();
    }
  }
  static function redirect($url, $statusCode = 303)
  {
    header('Location: ' . $url, true, $statusCode);
    die();
  }
  static  function contains($needle, $haystack){
    return strpos($haystack, $needle) !== false;
  }

  static  function empty_some(){
    return array_reduce(func_get_args(), function ($empty, $param){
      return $empty || empty($param) || ctype_space($param);
    });
  }

}