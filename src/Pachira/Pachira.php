<?php

require_once __DIR__ . "/functions.php";
require_once __DIR__ . "/Router.php";
require_once __DIR__ . "/Routing.php";
require_once __DIR__ . "/RoutingGroup.php";
require_once __DIR__ . "/NextRoute.php";
require_once __DIR__ . "/View.php";

class Pachira {
  private static $plugins = [];
  public static $store = [];

  public static function run($options=[]){
    foreach(self::$plugins as $name => $initializer){
      $option = el($options, $name);
      if($option) $initializer($option);
    }

    $path = el($options, "path", el($_SERVER, "PATH_INFO", "/"));
    Pachira\Router::getInstance()->route($path);
  }

  public static function addPlugin($name, $initializer){
    self::$plugins[$name] = $initializer;
  }
}

Pachira::addPlugin("view", function($options){
  Pachira\View::view_dir(el($options, "directory"));

  function view($name, $vars=[]){
    Pachira\View::view($name, $vars);
  }

  function capture_view($name, $vars=[]){
    return capture(function()use($name, $vars){view($name, $vars);});
  }

  function view_var($var, $val){
    Pachira\View::set_view_var($var, $val);
  }
});

Pachira::addPlugin("autoload", function ($options) {
  spl_autoload_register(function($className)use($options){
    $className = ltrim($className, '\\');

    foreach($options as $namespace => $dir){
      if(strpos($className, $namespace) !== FALSE){
        $rpath = ltrim(str_replace($namespace, "", $className), "\\");
        $path = rtrim($dir, "/") . "/" . str_replace("\\", "/", $rpath) . ".php";

        if(is_file($path)){
          require_once $path;
          return true;
        }
      }
    }

    return false;
  });
});
