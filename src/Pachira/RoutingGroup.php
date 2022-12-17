<?php // -*- mode: php; -*-

namespace Pachira;

class RoutingGroup {
  public function __construct($router, $prefix, $middlewares=[]){
    $this->router = $router;
    $this->prefix = $prefix;
    $this->middlewares = $middlewares;
  }

  public function group($prefix, $middlewares, $fn){
    $fn(new RoutingGroup($this->router, "{$this->prefix}{$prefix}", array_merge($this->middlewares, $middlewares)));
  }

  public function get($re, $fn){
    return $this->router->get("{$this->prefix}{$re}", $fn)->use($this->middlewares);
  }

  public function post($re, $fn){
    return $this->router->post("{$this->prefix}{$re}", $fn)->use($this->middlewares);
  }

  public function request($re, $fn){
    return $this->router->request("{$this->prefix}{$re}", $fn)->use($this->middlewares);
  }

  public function redirect($path, $code=302){
    redirect($this->prefix . $path, $code);
  }
}
