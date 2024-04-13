<?php

use Controller\AdminDashboardController;
use Controller\CardController;
use Controller\DeckController;
use Controller\HomeController;
use Controller\LoginController;
use Controller\RegisterController;
use Core\DependencyContainer;
use Middleware\AdminAccessMiddleware;
use Middleware\AuthMiddleware;
use Middleware\PremiumAccessMiddleware;
use Service\AuthService;
use Service\CardService;
use Service\DatabaseService;
use Service\DeckService;
use Service\EntityManagerService;
use Service\EnvironmentService;
use Service\FileService;
use Service\PermissionService;
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

$environmentService = (function () {
    return new EnvironmentService();
})();

try {
    $environmentService->loadVariables();
} catch (Exception $e) {
    var_dump($e);
}

$fileService = (function () {
    return new FileService();
})();

$cardService = (function ($entityManager, $repository, $environment, $fileService) {
    return new CardService($entityManager, $repository, $environment, $fileService);
})($entityManager, $repository, $environmentService, $fileService);

$container->set("cardService", $cardService);

$authService = (function ($database, $repository) {
    return new AuthService($database, $repository);
})($database, $repository);

$permissionService = (function($authService) {
    return new PermissionService($authService);
})($authService);

$homeController = (function ($cardService, $permissionService) {
    return new HomeController($cardService, $permissionService);
})($cardService, $permissionService);

$container->set("homeController", $homeController);

$registerController = (function($repository, $entityManager) {
    return new RegisterController($repository, $entityManager);
})($repository, $entityManager);

$container->set("registerController", $registerController);

$adminAccessMiddleware = (function($permissionService) {
    return new AdminAccessMiddleware($permissionService);
})($permissionService);

$container->set("adminAccessMiddleware", $adminAccessMiddleware);

$premiumAccessMiddleware = (function($permissionService) {
    return new PremiumAccessMiddleware($permissionService);
})($permissionService);

$container->set("premiumAccessMiddleware", $premiumAccessMiddleware);

$loginController = (function($repository, $authService) {
    return new LoginController($repository, $authService);
})($repository, $authService);

$container->set("loginController", $loginController);

$authMiddleware = (function ($authService) {
    return new AuthMiddleware($authService);
})($authService);

$container->set("authMiddleware", $authMiddleware);

$deckService = (function ($repository, $entityManager, $authService) {
    return new DeckService($repository, $entityManager, $authService);
})($repository, $entityManager, $authService);

$deckController = (function ($deckService, $cardService) {
    return new DeckController($deckService, $cardService);
})($deckService, $cardService);

$container->set("deckController", $deckController);

$userService = (function($entityMangerService,$repositoryService, $authService) {
    return new \Service\UserService($entityMangerService, $repositoryService, $authService);
})($entityManager, $repository, $authService);

$adminController = (function ($userService, $cardService) {
    return new AdminDashboardController($userService, $cardService);
})($userService, $cardService);

$container->set("adminController", $adminController);

$cardController = (function ($cardService) {
    return new CardController($cardService);
})($cardService);

$container->set("cardController", $cardController);

$router = (function ($container) {
    $routeRepository = new RouteRepositoryService($container);
    $requestHandler = new RequestHandlerService($routeRepository, $container);
    return new RoutingService($routeRepository, $requestHandler);
})($container);

$container->set('router', $router);

return $container;