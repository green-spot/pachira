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
    $this->router->get("{$this->prefix}{$re}", $fn)->use($this->middlewares);
  }

  public function post($re, $fn){
    $this->router->post("{$this->prefix}{$re}", $fn)->use($this->middlewares);
  }

  public function request($re, $fn){
    $this->router->request("{$this->prefix}{$re}", $fn)->use($this->middlewares);
  }
}
