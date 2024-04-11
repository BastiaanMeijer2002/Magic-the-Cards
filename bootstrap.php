<?php

use Controller\HomeController;
use Controller\LoginController;
use Controller\RegisterController;
use Core\DependencyContainer;
use Service\AuthService;
use Service\CardService;
use Service\DatabaseService;
use Service\EntityManagerService;
use Service\RepositoryService;
use Service\RequestHandlerService;
use Service\RouteRepositoryService;
use Service\RoutingService;

$container = new DependencyContainer();

$database = new DatabaseService();

$repository = (function ($database) {
    return new RepositoryService($database);
})($database);

$container->set("repository", $repository);

$entityManager = (function ($database, $repository) {
    return new EntityManagerService($database, $repository);
})($database, $repository);

$container->set("entityManager", $entityManager);

$cardService = (function ($entityManager, $repository) {
    return new CardService($entityManager, $repository);
})($entityManager, $repository);

$container->set("cardService", $cardService);

$homeController = (function ($cardService) {
    return new HomeController($cardService);
})($cardService);

$container->set("homeController", $homeController);

$registerController = (function($repository, $entityManager) {
    return new RegisterController($repository, $entityManager);
})($repository, $entityManager);

$container->set("registerController", $registerController);

$authService = (function ($database, $repository) {
    return new AuthService($database, $repository);
})($database, $repository);

$loginController = (function($repository, $authService) {
    return new LoginController($repository, $authService);
})($repository, $authService);

$container->set("loginController", $loginController);

$router = (function ($container) {
    $routeRepository = new RouteRepositoryService($container);
    $requestHandler = new RequestHandlerService($routeRepository);
    return new RoutingService($routeRepository, $requestHandler);
})($container);

$container->set('router', $router);

return $container;