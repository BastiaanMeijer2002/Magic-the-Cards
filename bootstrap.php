<?php

use Core\DependencyContainer;
use Service\RequestHandlerService;
use Service\RouteRepositoryService;
use Service\RoutingService;

$container = new DependencyContainer();

$router = (function () {
    $routeRepository = new RouteRepositoryService();
    $requestHandler = new RequestHandlerService($routeRepository);
    return new RoutingService($routeRepository, $requestHandler);
})();

$container->set('router', $router);

return $container;