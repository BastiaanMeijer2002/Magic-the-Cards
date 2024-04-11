<?php

use Core\Request;
use Service\CardService;

require "vendor/autoload.php";

session_start();

$container = require_once "bootstrap.php";

$router = $container->get("router");

try {
    $cardService = $container->get("cardService");
    $cardService->retrieveCards();
} catch (ReflectionException $e) {
    var_dump($e->getMessage());
    var_dump($e->getTraceAsString());
    echo 500;
}

$request = new Request(
    $_SERVER["REQUEST_METHOD"],
    $_SERVER['PATH_INFO'] ?? '/',
    $_POST,
    $_SERVER["QUERY_STRING"] ?? null
);
$response = $router->route($request);

if($response){
    $response->send();
} else {
    echo 404;
}