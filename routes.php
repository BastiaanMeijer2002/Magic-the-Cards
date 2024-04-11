<?php

use Controller\HomeController;
use Controller\TestController;

$routes = [
    ["method" => "GET", "uri" => "/", "controller" => "homeController", "function" => "index", "middleware" => []],
];
