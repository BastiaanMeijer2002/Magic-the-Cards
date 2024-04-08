<?php

use Core\Request;

require "vendor/autoload.php";

$container = require_once "bootstrap.php";

$router = $container->get("router");

$request = new Request();
$response = $router->route($request);

if($response){
    $response->send();
} else {
    echo 404;
}