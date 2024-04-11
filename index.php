<?php

use Core\Request;
use Service\CardService;

require "vendor/autoload.php";

$container = require_once "bootstrap.php";

$router = $container->get("router");

try {
    CardService::retrieveCards($container->get("entityManager"), $container->get("repository"));
} catch (ReflectionException $e) {
    var_dump($e->getMessage());
    var_dump($e->getTraceAsString());
    echo 500;
}

$request = new Request();
$response = $router->route($request);

if($response){
    $response->send();
} else {
    echo 404;
}