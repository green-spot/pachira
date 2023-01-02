<?php

require_once __DIR__ . "/functions.php";
require_once __DIR__ . "/route.php";

Pachira::run([
  "view" => [
    "directory" => __DIR__ . "/views/"
  ],
  "autoload" => [
    "App\Model" => __DIR__ . "/models/"
  ]
]);
