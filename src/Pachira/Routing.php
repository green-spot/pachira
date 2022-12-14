<?php

namespace Pachira;

class Routing {
  public function __construct($re, $fn, $method, $router){
    $this->re = $re;
    $this->fn = $fn;
    $this->method = $method;
    $this->router = $router;
    $this->middleware_names = [];
  }

  public function use($middleware_names){
    if(is_array($middleware_names)){
      $this->middleware_names = array_merge($this->middleware_names, $middleware_names);
    }else if(is_string($middleware_names)){
      $this->middleware_names[] = $middleware_names;
    }
    return $this;
  }

  public function trailing_slash(){
    if(substr($this->re, -1) === "/"){
      $re = $this->re;
      $this->router->{$this->method}(substr($re, 0, -1), function()use($re){
        redirect($re, 301);
      });
    }

    return $this;
  }
}
