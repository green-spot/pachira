<?php

namespace Pachira;

class Router {
  private static $singleton;

  public static function getInstance(){
    if(!isset(self::$singleton)) self::$singleton = new self();
    return self::$singleton;
  }

  private function __construct(){
    $this->map_get = [];
    $this->map_post = [];
    $this->map_request = [];
    $this->middlewares = [];
  }

  public function route($path){
    $failed = true;

    switch(el($_SERVER, "REQUEST_METHOD", null)){
      case "GET": $map = $this->map_get; break;
      case "POST": $map = $this->map_post; break;
      default: $map = $this->map_request;
    }

    foreach($map as $routing){
      if(preg_match("/^".str_replace("/", "\\/", $routing->re)."$/", $path, $args)){
        array_shift($args);
        $args = array_map(function($arg){
          return urldecode($arg);
        }, $args);

        try{
          if(empty($routing->middleware_names)){
            call_user_func_array($routing->fn, $args);
          }else{
            $this->apply_middlewares($routing->fn, $args, $routing->middleware_names);
          }

          $failed = false;
          break;

        }catch(NextRoute $e){
        }
      }
    }

    if($failed) not_found();
  }

  private function apply_middlewares($fn, $args, $middleware_names=[]){
    $that = $this;

    if(empty($middleware_names)){
      call_user_func_array($fn, $args);

    }else{
      $middleware_name = array_shift($middleware_names);
      $this->middlewares[$middleware_name](function()use($that, $fn, $args, $middleware_names){
        $that->apply_middlewares($fn, $args, $middleware_names);
      });
    }
  }

  public function get($re, $fn){
    $routing = new Routing($re, $fn, "get", $this);
    $this->map_get[] = $routing;
    return $routing;
  }

  public function post($re, $fn){
    $routing = new Routing($re, $fn, "post", $this);
    $this->map_post[] = $routing;
    return $routing;
  }

  public function request($re, $fn){
    $routing = new Routing($re, $fn, "request", $this);
    $this->map_get[] = $routing;
    $this->map_post[] = $routing;
    $this->map_request[] = $routing;
    return $routing;
  }

  public function add_middleware($name, $fn){
    $this->middlewares[$name] = $fn;
  }
}
