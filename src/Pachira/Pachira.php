<?php

require_once __DIR__ . "/functions.php";
require_once __DIR__ . "/Router.php";
require_once __DIR__ . "/Routing.php";
require_once __DIR__ . "/RoutingGroup.php";
require_once __DIR__ . "/NextRoute.php";
require_once __DIR__ . "/View.php";

class Pachira {
  public static function run($options=[]){
    $path = el($options, "path", el($_SERVER, "PATH_INFO", "/"));
    Pachira\View::view_dir(el($options, "view_dir", null));
    Pachira\Router::getInstance()->route($path);
  }
}
