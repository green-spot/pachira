<?php

namespace App;

middleware("layout", function($run){
  view("layout", [
    "content" => capture($run)
  ]);
});

middleware("login_required", function($run){
  if(!get_login_user()) redirect("/login");
  $run();
});

group("", ["layout"], function($g){
  $g->get("/", function(){
    view("top");
  });

  $g->get("/login", function(){
    view("login");
  });

  $g->group("/member", ["login_required"], function($g){
    $g->get("/", function(){
      view("member-top", ["user_name" => get_login_user()]);
    });

    $g->get("/(.+?)", function($page){
      echo "{$page} page";
    });
  });
});
