<?php

/* Routing Functions */

function get($re, $fn){
  return Pachira\Router::getInstance()->get($re, $fn);
}

function post($re, $fn){
  return Pachira\Router::getInstance()->post($re, $fn);
}

function request($re, $fn){
  return Pachira\Router::getInstance()->request($re, $fn);
}

function middleware($name, $fn){
  Pachira\Router::getInstance()->add_middleware($name, $fn);
}

function group($prefix, $middleware, $fn){
  $fn(new Pachira\RoutingGroup(Pachira\Router::getInstance(), $prefix, $middleware));
}

function pass(){
  throw new Pachira\NextRoute();
}

function not_found(){
  header("Status: 404 Not Found");
}


/* Utilities */

function h($str){
  return htmlspecialchars($str);
}

function el($array, $key, $default=null){
  if(is_array($array)){
    return isset($array[$key]) ? $array[$key] : $default;
  }else if(is_object($array)){
    return isset($array->$key) ? $array->$key : $default;
  }else{
    return false;
  }
}

function redirect($url, $code=302){
  switch($code){
  case 301: header("HTTP/1.1 301 Moved Permanently"); break;
  case 302: header("HTTP/1.1 302 Found"); break;
  case 303: header("HTTP/1.1 303 See Other"); break;
  case 307: header("HTTP/1.1 307 Temporary Redirect"); break;
  case "js": echo "<script>location.href='{$url}';</script>"; exit;
  }
  header("Location: {$url}");
  exit;
}

function capture($fn){
  ob_start();
  $fn();
  $output = ob_get_contents();
  ob_end_clean();
  return $output;
}
