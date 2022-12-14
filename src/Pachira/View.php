<?php

namespace Pachira;

class View {
  private static $view_dir = null;
  private static $view_vars = [];

  public static function view_dir($dir){
    self::$view_dir = $dir;
  }

  public static function view($name, $vars=[]){
    if(is_null(self::$view_dir)){
      throw new \Exception('Not defined View Directory.');
    }

    $path = self::$view_dir . "{$name}";
    if(!is_file($path)) $path .= ".php";

    if(is_file($path)){
      extract(array_merge(self::$view_vars, $vars));
      include $path;
      return true;
    }
    return false;
  }

  public static function set_view_var($var, $val){
    self::$view_vars[$var] = $val;
  }
}
