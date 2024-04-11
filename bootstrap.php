<?php

use Core\DependencyContainer;
use Service\DatabaseService;
use Service\EntityManagerService;
use Service\RepositoryService;
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

$database = new DatabaseService();

$repository = (function ($database) {
    return new RepositoryService($database);
})($database);

$container->set("repository", $repository);

$entityManager = (function ($database, $repository) {
    $repository = new RepositoryService($database);
    return new EntityManagerService($database, $repository);
})($database, $repository);

$container->set("entityManager", $entityManager);

return $container;