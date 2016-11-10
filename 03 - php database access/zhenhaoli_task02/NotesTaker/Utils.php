<?php

/**
 * Created by PhpStorm.
 * User: li
 * Date: 10.11.2016
 * Time: 13:55
 */
class Utils
{

  static  function contains($needle, $haystack){
    return strpos($haystack, $needle) !== false;
  }

  static  function empty_some(){
    return array_reduce(func_get_args(), function ($empty, $param){
      return $empty || empty($param) || ctype_space($param);
    });
  }

}