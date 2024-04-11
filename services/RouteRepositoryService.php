<?php

namespace Service;

use Core\Controller;
use Core\DependencyContainer;
use Core\Route;
use Exception;

class RouteRepositoryService
{
    private DependencyContainer $container;

    /**
     * @param DependencyContainer $container
     */
    public function __construct(DependencyContainer $container)
    {
        $this->container = $container;
    }

    /**
     * @throws Exception
     */
    public function getRoutes(): array
    {
        global $routes;
        include 'routes.php';

        $routeList = [];
        foreach ($routes as $route)
        {
            $routeObject = new Route();
            $routeObject->setMethod($route["method"]);
            $routeObject->setUri($route["uri"]);
            $routeObject->setController($this->container->get($route["controller"]));
            $routeObject->setFunction($route["function"]);

            $routeList[] = $routeObject;
        }

        return $routeList;
    }

    public function getMiddleware(string $uri): array
    {
        global $routes;
        include 'routes.php';

        $routeList = [];
        foreach ($routes as $route)
        {
            if ($route['uri'] === $uri){
                return $route["middleware"];
            }
        }

        return [];
    }
}