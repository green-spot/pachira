<?php

use Pachira\Pachira;

Pachira::init(["view_dir" => __DIR__ . "/views/"]);
require_once __DIR__ . "/functions.php";
require_once __DIR__ . "/route.php";

Pachira::run();
