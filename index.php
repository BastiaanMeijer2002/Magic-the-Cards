<?php

use Controller\KaasController;
use Core\Application;
use Core\Request;
use Controller\TestController;

require "vendor/autoloader.php";

$app = new Application();

$request = new Request();
$response = $app->router->route($request);

if($response){
    $response->send();
} else {
    echo 404;
}